<?php
/**
 * Handles reconciling damage reports and making news reports of deaths.
 * 
 * @package [Redacted]Me
 * ---------------------------------------------------------------------------
 *
 * Merchant Empires by [Redacted] Games LLC - A space merchant game of war
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

	include_once('inc/events.php');

	define('SKIP_ARTICLES', 1);
	include_once('inc/news.php');

	register_event(new Event_Player_Log());

	class Event_Player_Log extends Event {
		
		public function getRunTime() {
			return EVENT_PLAYER_LOG_TIME;
		}

		public function run() {
		
			global $db;
			$db = isset($db) ? $db : new DB;

			$time = time();

			// Grab a batch of log entries sorted by time descending...

			$rs = $db->get_db()->query("select * from player_log where reconciled <= 0 and (action >= 7 and action <= 10) order by timestamp desc");

			if (!$rs) {
				error_log(__FILE__ . '::' . __LINE__ . " Query selection failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				return;
			}

			if (!$rs->data_seek(0)) {
				return;
			}

			$log_items = array();
			$log_item_count = 0;

			while ($row = $rs->fetch_assoc()) {
				if (!isset($log_items[$row['player']])) {
					$log_items[$row['player']] = array();
				}

				$log_items[$row['player']][$row['record_id']] = $row;
				$log_item_count++;
			}

			if ($log_item_count <= 0) {
				return;
			}

			// List of players involved in any log, for retrieving names etc later
			$players = array();

			// List of log entries we will process for this time around.
			$reconciled_log_items = array();

			// Current death buffer for the player
			$death = array();

			// All deaths we know of
			$deaths = array();

			// Build the list of deaths

			foreach ($log_items as $player_id => $logs) {

				foreach ($logs as $record_id => $row) {

					if ($time - $row['timestamp'] < ASSIST_TIME) {
						// Ignore this log so it can be processed later in case it is part of 
						// a future death. ASSIST_TIME won't let it live long...
						continue;
					}

					switch ($row['action']) {

						case 7: // Death
							if (isset($death[$row['player']])) {
								// Add completed death to the buffer of deaths.
								$deaths[] = $death;
							}

							$y = floor($row['target'] / 1000);

							$death[$row['player']] = array(
								'record_id' => $row['record_id'],
								'timestamp' => $row['timestamp'],
								'player' => $row['player'],
								'x' => $row['target'] - ($y * 1000),
								'y' => $y,
								'assists' => array(),
								'friendly_assists' => array(),
							);

							$players[$row['player']] = null;
							break;

						case 8: // General damage
						case 10: // War Damage
							if (isset($death[$row['player']]) && $death[$row['player']]['timestamp'] - $row['timestamp'] <= ASSIST_TIME) {
								$death[$row]['player']['assists'][] = $row;
								$players[$row['player']] = null;
							}
							break;

						case 9: // Friendly Damage
							if (isset($death[$row['player']]) && $death[$row['player']]['timestamp'] - $row['timestamp'] <= ASSIST_TIME) {
								$death[$row]['player']['friendly_assists'][] = $row;
								$players[$row['player']] = null;
							}

						default:
							// Do nothing, not ours to reconcile.
							continue;
					}
		
					$reconciled_log_items[] = $record_id;
				}
				
			}

			// Cleanup leftover deaths from previous loop

			foreach ($death as $player_id => $row) {
				$deaths[] = $row;
			}

			// Mark log entries we processed as reconciled so we don't process them again.

			foreach ($reconciled_log_items as $log_id) {

				if (!($st = $db->get_db()->prepare("update player_log set reconciled = ? where record_id = ?"))) {
					error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					$return_codes[] = 1006;
					return false;
				}
				
				$st->bind_param("ii", $time, $log_id);
				
				if (!$st->execute()) {
					$return_codes[] = 1006;
					error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					return false;
				}
			}

			// Check to see if we even have work to do.

			if (count($players) <= 0) {
				// Assume there is no death logs to process assists for.
				return;
			}

			// Grab a list of player names for battle reports.

			$player_list = implode(',', array_keys($players));
			$rs = $db->get_db()->query("select record_id, caption from players where record_id in (". $player_list .")");

			if (!$rs) {
				error_log(__FILE__ . '::' . __LINE__ . " Query selection failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				return;
			}

			$rs->data_seek(0);

			while ($row = $rs->fetch_assoc()) {
				$players[$row['record_id']] = $row['caption'];
			}

			// Loop through deaths and compute alignment/exp changes

			$player_updates = array();

			foreach ($deaths as $death) {
				$death['caption'] = $players[$death['player']];

				$offender_names = array();
				$offender_count = 0;

				foreach ($death['assists'] as $record_id => $assist) {
					if (!isset($player_updates[$assist['player']])) {
	 					$player_updates[$assist['player']] = array(
							'alignment' => 0,
							'experience' => $death['amount1'] * ASSIST_EXP_PER_DAMAGE,
						);
					}
					else {
						$player_updates[$assist['player']]['experience'] += ($death['amount1'] * ASSIST_EXP_PER_DAMAGE);
					}

					$offender_names[] = $players[$assist['player']];
					$offender_count++;
				}

				foreach ($death['friendly_assists'] as $record_id => $assist) {
					if (!isset($player_updates[$assist['player']])) {
						$player_updates[$assist['player']] = array(
							'alignment' => -RACIAL_KILL_PENALTY,
							'experience' => 0,
						);
					}
					else {
						$player_updates[$assist['player']]['alignment'] -= RACIAL_KILL_PENALTY;
					}
					
					$offender_names[] = $players[$assist['player']];
					$offender_count++;
				}

				// Send news

				if ($offender_count <= 0) {
					// Player died to forces or a base.

					$headline = $death['caption'] . ' Narrowly Survives';
					$abstract = 'With a compromised ship, the pilot ejected just in time.';
					$article = 'Player "' . $death['caption'] . '" was seen ejecting from their ';
					$article .= 'fatally damaged ship in sector ' . $death['x'] . ',' . $death['y'];
					$article .= '. It is unknown if they will be able to continue fighting.';
					$author = -1;
					$live = $time;
					$archive = $time + DEFAULT_NEWS_ARCHIVE_TIME;
					$expiration = $time + DEFAULT_NEWS_EXPIRATION_TIME;

					insert_article($headline, $abstract, $article, $author, $live, $archive, $expiration, $return_codes);
				}
				else {
					// Player died to other players.

					//TODO: Player died to other players.

					$offender_list = implode(', ', $offender_names);
					print_r($offender_names);
				}
			}

			// Apply the final updates to each player

			foreach ($player_updates as $player_id => $updates) {

				if (!($st = $db->get_db()->prepare('update players set `alignment` = `alignment` + ?, experience = experience + ? where record_id = ?'))) {
					echo (__FILE__ . '::' . __LINE__ . "Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					return;
				}
			
				$st->bind_param("iii", $updates['alignment'], $updates['experience'], $player_id);
					
				if (!$st->execute()) {
					echo ("Query execution failed: (" . $st->errno . ") " . $st->error);
					return;
				}

			}




		}

	};



?>