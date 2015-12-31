<?php
/**
 * Popup template to show all players.
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

	include_once('tmpl/common.php');
	include_once('inc/alliances.php');

	$players = array();
	$player_count = 0;
	$start = -1;

	$db = isset($db) ? $db : new DB;

	if (isset($_REQUEST['start']) && is_numeric($_REQUEST['start']) && $_REQUEST['start'] >= 0 && $_REQUEST['start'] == floor($_REQUEST['start'])) {
		$start = $_REQUEST['start'];
		$rs = $db->get_db()->query("select record_id, caption, alliance, level, gold_expiration from players where last_turns >= '" . (PAGE_START_TIME - ACTIVE_PLAYER_TIME) . "' order by experience desc, caption");
	}
	else {
		$rs = $db->get_db()->query("select record_id, caption, alliance, level, gold_expiration from players where last_turns >= '" . (PAGE_START_TIME - ONLINE_PLAYER_TIME) . "' order by experience desc, caption");
	}

	$rs->data_seek(0);
	while ($row = $rs->fetch_assoc()) {
		$players[] = $row;
		$player_count++;
	}



?>
<div class="header2"><?php 
	if ($start >= 0) {
		echo 'Players Active over the Last ' . floor(ACTIVE_PLAYER_TIME / (3600 * 24)) . ' Days';
	}
	else {
		echo 'Players Online within the Last ' . floor(ONLINE_PLAYER_TIME / 60) . ' Minutes';
	}
?></div>
<div class="docs_text"><?php
	if ($start >= 0) {
		echo '<a href="alliance.php?page=players">Click here</a> to show only online players.';
	}
	else {
		echo '<a href="alliance.php?page=players&amp;start=0">Click here</a> to show the full active player list.';
	}
?></div>
<hr />
<div class="header4">Showing <?php echo $player_count . ' Player' . ($player_count == 1 ? '' : 's'); ?>:</div>
<div class="alliance_list">
	<?php
		foreach ($players as $player) {
			echo '<div class="alliance_list_item" title="Level '. $player['level'] .'">';
			
			if ($player['gold_expiration'] > PAGE_START_TIME) {
				echo '<div class="alliance_list_item_gold">';
				echo '<img src="res/gold.png" alt="Gold" title="Gold Member" height="20" />&nbsp;';
				echo '</div>';
			}

			echo '<div class="alliance_list_item_caption">';
			echo '<strong>';
			echo '<a href="alliance.php?page=player&amp;player_id='. $player['record_id'] .'">';
			echo $player['caption'];
			echo '</a>';
			echo '</strong>';
			echo '</div>';

			if ($player['alliance'] > 0) {
				echo '<div class="alliance_list_item_kick">';
				echo '<a href="alliance.php?page=members&amp;alliance_id='. $player['alliance'] .'">';
				echo '<small>' . $spacegame['alliances'][$player['alliance']]['caption'] . '</small>';
				echo '</a>';
				echo '</div>';
			}
			else {
				echo '<div class="alliance_list_item_alliance">';
				echo '<small><em>NO ALLIANCE</em></small>';
				echo '</div>';	
			}

			echo '</div>';
		}
	?>
</div>
