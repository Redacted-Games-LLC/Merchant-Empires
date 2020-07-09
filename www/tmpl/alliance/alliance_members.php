<?php
/**
 * Popup template to show a list of alliance members.
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

	$alliance_id = $spacegame['player']['alliance'];

	if (isset($_REQUEST['alliance_id']) && isset($spacegame['alliance_members'][$_REQUEST['alliance_id']])) {
		$alliance_id = $_REQUEST['alliance_id'];
	}


	$members = array();
	$member_count = 0;

	$db = isset($db) ? $db : new DB;

	$rs = $db->get_db()->query("select players.record_id, players.caption, players.level, players.gold_expiration, ranks.caption as rank from players, ranks where alliance = '". $alliance_id ."' and players.rank = ranks.record_id order by experience desc, caption");
	
	$rs->data_seek(0);
	while ($row = $rs->fetch_assoc()) {
		$members[] = $row;
		$member_count++;
	}



?>
<div class="header2 header_bold">Alliance Members : <?php echo $spacegame['alliances'][$alliance_id]['caption']; ?></div>
<hr />
<div class="alliance_recruit">
<?php if ($spacegame['alliances'][$alliance_id]['recruiting'] > 0) { ?>
	This alliance is currently recruiting.
	<?php if ($spacegame['player']['alliance'] <= 0) { ?>
		
		<?php if (isset($spacegame['invite_alliances'][$alliance_id])) { ?>
			You already have a recent request to this alliance. Please wait for it
			to expire before making a new one.
		<?php } elseif ($spacegame['active_invites_count'] >= ALLIANCE_REQUEST_LIMIT) { ?>
			You already have <?php echo ALLIANCE_REQUEST_LIMIT; ?> active request(s).
			Please wait for some responses before making new ones.
		<?php } else { ?>
			Click on the button to request to join. You can have three
			active requests at any one time.<br />
			<br />
			<form action="handler.php" method="post">
				<script type="text/javascript">drawButton('join', 'join', 'validate_request_alliance()');</script>
				<input type="hidden" name="task" value="alliance" />
				<input type="hidden" name="subtask" value="request" />
				<input type="hidden" name="alliance_id" value="<?php echo $alliance_id; ?>" />
				<input type="hidden" name="return" value="alliance" />
				<input type="hidden" name="form_id" value="<?php echo $_SESSION['form_id']; ?>" />
			</form>
		<?php } ?>
	<?php } ?>
<?php } else { ?>
	This alliance is not currently recruiting.
<?php } ?>
</div>
<hr />
<div class="header4 header_bold">This alliance has <?php echo $member_count . ' Member' . ($member_count == 1 ? '' : 's'); ?>:</div>
<div class="alliance_list">
	<?php
		foreach ($members as $member) {
			echo '<div class="alliance_list_item" title="Level '. $member['level'] .'">';

			if ($member['gold_expiration'] > PAGE_START_TIME) {
				echo '<div class="alliance_list_item_gold">';
				echo '<img src="res/gold.png" alt="Gold" height="20" title="Gold Member" />';
				echo '</div>';

				echo '<div class="alliance_list_item_caption">';
				echo '<strong><a href="alliance.php?page=player&amp;player_id='. $member['record_id'] .'">';
				echo $member['caption'] . '</a></strong>&nbsp;';
				echo '</div>';
			}
			else {
				echo '<div class="alliance_list_item_caption">';
				echo '<a href="alliance.php?page=player&amp;player_id='. $member['record_id'] .'">';
				echo $member['caption'] . '</a>&nbsp;';	
				echo '</div>';
			}
			
			echo '<small>' . $member['rank'] . '</small>';

			if ($spacegame['player']['alliance'] == $alliance_id && $spacegame['player']['record_id'] == $spacegame['alliances'][$alliance_id]['founder']) {
			
				if ($spacegame['alliances'][$alliance_id]['founder'] != $member['record_id']) {
					echo '<div class="alliance_list_item_kick">';
					echo '[<a href="handler.php?task=alliance&amp;subtask=leave&amp;player_id=' . $member['record_id'] . '&amp;form_id='. $_SESSION['form_id'] .'">Kick</a>]';
					echo '</div>';
				}
			
			}

			echo '</div>';
		}
	?>
</div>