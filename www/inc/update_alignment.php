<?php
/**
 * Updates alignment for a player if need be.
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

	include_once('inc/common.php');
	include_once('inc/game.php');

	do { /* Dummy Loop */

		$align_delta = PAGE_START_TIME - $spacegame['player']['last_alignment'];

		if ($align_delta >= ALIGNMENT_UPDATE_TIME) {

			$db = isset($db) ? $db : new DB;

			$player_id = PLAYER_ID;
			$db->get_db()->autocommit(false);

			$rs = $db->get_db()->query("select * from player_log where player = '{$player_id}' and reconciled <= 0 and action >= 1 and action <= 6 order by timestamp");

			if (!$rs) {
				$db->get_db()->rollback();
				$db->get_db()->autocommit(true);
				error_log(__FILE__ . '::' . __LINE__ . " Query selection failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				break;
			}

			if (!$rs->data_seek(0)) {
				$db->get_db()->rollback();
				$db->get_db()->autocommit(true);
				break;
			}

			$log_items = array();

			while ($row = $rs->fetch_assoc()) {
				$log_items[$row['record_id']] = $row;
			}

			$alignment_adjust = 0;

			$trades = 0;
			$war_trades = 0;
			$upgrades = 0;
			$war_upgrades = 0;

			$reconciled_log_items = array();
			
			foreach ($log_items as $log_id => $log_row) {

				switch ($log_row['action']) {

					case 1: // Buy
					case 2: // Sell
						$trades += $log_row['amount1'];
						break;

					case 3: // Upgrade
						$upgrades += $log_row['amount1'];
						break;

					case 4: // War buy
					case 5: // War sell
						$war_trades += $log_row['amount1'];
						break;

					case 6: // War upgrade
						$war_upgrades += $log_row['amount1'];
						break;

					default:
						// Do nothing
						continue;
				}

				$reconciled_log_items[] = $log_id;
			}
			
			foreach ($reconciled_log_items as $log_id) {

				if (!($st = $db->get_db()->prepare("update player_log set reconciled = ? where record_id = ?"))) {
					$db->get_db()->rollback();
					$db->get_db()->autocommit(true);
					error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					$return_codes[] = 1006;
					break 2;
				}
				
				$st->bind_param("ii", $time, $log_id);
				
				if (!$st->execute()) {
					$db->get_db()->rollback();
					$db->get_db()->autocommit(true);
					$return_codes[] = 1006;
					error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					return;
				}
			}

			$alignment_adjust -= floor($war_upgrades / WAR_UPGRADES_PER_ALIGNMENT_POINT);
			$alignment_adjust -= floor($war_trades / WAR_TRADES_PER_ALIGNMENT_POINT);
			$alignment_adjust += floor($trades / TRADES_PER_ALIGNMENT_POINT);
			$alignment_adjust += floor($upgrades / UPGRADES_PER_ALIGNMENT_POINT);

			if ($alignment_adjust != 0) {
				
				$new_alignment = max(-ALIGNMENT_LIMIT, min(ALIGNMENT_LIMIT, $spacegame['player']['alignment'] + $alignment_adjust));

				if (!($st = $db->get_db()->prepare("update players set alignment = ?, last_alignment = last_alignment + ? where record_id = ?"))) {
					$db->get_db()->rollback();
					$db->get_db()->autocommit(true);
					error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					$return_codes[] = 1006;
					break;
				}

				$st->bind_param("iii", $new_alignment, $align_delta, $player_id);
				
				if (!$st->execute()) {
					$db->get_db()->rollback();
					$db->get_db()->autocommit(true);
					$return_codes[] = 1006;
					error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					break;
				}
			}

			$db->get_db()->commit();
			$db->get_db()->autocommit(true);

		}		
	} while (false);