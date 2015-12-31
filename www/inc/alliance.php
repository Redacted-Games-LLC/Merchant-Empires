<?php
/**
 * Loads information about the current alliance a player is in.
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

	include_once('inc/game.php');
	include_once('inc/alliances.php');

	do { // Dummy loop

		$db = isset($db) ? $db : new DB;


		$spacegame['alliance'] = array();

		$spacegame['invites'] = array();
		$spacegame['invite_alliances'] = array();
		$spacegame['invites_count'] = 0;
		$spacegame['active_invites_count'] = 0;

		$spacegame['alliance_invites'] = array();
		$spacegame['alliance_invites_count'] = 0;

		
		$alliance_id = $spacegame['player']['alliance'];

		if ($alliance_id > 0 && isset($spacegame['alliances'][$alliance_id])) {
			$spacegame['alliance'] = $spacegame['alliances'][$alliance_id];
			define('ALLIANCE_LEADER', $spacegame['alliance']['founder'] == PLAYER_ID);

			if (ALLIANCE_LEADER) {
				$rs = $db->get_db()->query("select * from alliance_invitations where alliance = '". $alliance_id ."' order by requested desc");
				
				$rs->data_seek(0);
				while ($row = $rs->fetch_assoc()) {
					$spacegame['alliance_invites'][$row['record_id']] = $row;
					$spacegame['alliance_invites_count']++;
				}
			}
		}
		else {
			define('ALLIANCE_LEADER', false);	

			$rs = $db->get_db()->query("select * from alliance_invitations where player = '". PLAYER_ID ."' order by requested desc");
			
			$rs->data_seek(0);
			while ($row = $rs->fetch_assoc()) {
				$spacegame['invites'][$row['record_id']] = $row;
				$spacegame['invites_count']++;
				$spacegame['invite_alliances'][$row['alliance']] = $row['record_id'];

				if ($row['rejected'] <= 0) {
					$spacegame['active_invites_count']++;
				}
			}
		}
		

	} while (false);


?>