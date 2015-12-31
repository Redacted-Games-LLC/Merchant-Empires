<?php
/**
 * Popup template to show player information.
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
	include_once('inc/ranks.php');

	if (!isset($_REQUEST['player_id']) || !is_numeric($_REQUEST['player_id']) || $_REQUEST['player_id'] < 0 || $_REQUEST['player_id'] != floor($_REQUEST['player_id'])) {
		$return_codes[] = 1087;
		break;
	}

	$player_id = $_REQUEST['player_id'];
	$player = null;
	
	$db = isset($db) ? $db : new DB;

	$rs = $db->get_db()->query("select * from players where record_id = '$player_id' limit 1");
	
	$rs->data_seek(0);
	if ($row = $rs->fetch_assoc()) {
		$player = $row;
	}


	if (is_null($player)) {
?>

	<div class="header2">Player Information : <em>Uknown Player</em></div>
	<div class="docs_text">
		Please return to the <a href="alliance.php?page=players&amp;start=0">player list</a>
		and try again.
	</div>

<?php } else { ?>

	<div class="header2">Player Information : <?php echo $player['caption']; ?></div>
	<div class="docs_text">
		Level: <?php echo $player['level']; ?><br />
		Experience: <?php echo number_format($player['experience']); ?><br />
		Alignment: <?php echo $player['alignment']; ?><br />
		Rank: <?php echo $spacegame['ranks'][$player['rank']]['caption']; ?><br />
	</div>
	<hr />
	<?php if ($player['alliance'] > 0) { ?>
		<div class="header2">Alliance : <?php echo $spacegame['alliances'][$player['alliance']]['caption']; ?></div>
		<div class="docs_text">
			<a href="alliance.php?page=members&amp;alliance_id=<?php echo $player['alliance'] ?>">View all members</a> of this alliance.
		</div>
	<?php } else { ?>
		<div class="header2">Alliance : <em>NO ALLIANCE</em></div>
		<div class="docs_text">
			This player is not a member of an alliance.
		</div>
	<?php } ?>

<?php } ?>
