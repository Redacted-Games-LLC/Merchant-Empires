<?php
/**
 * Entry template page for alliance information
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
?>
<div class="header2">Alliance Status</div>
<?php
	if ($spacegame['player']['alliance'] > 0) {

		echo '<div class="docs_text">';

		echo 'Alliance name: ' . $spacegame['alliance']['caption'] . '<br />';
		echo 'Imperial Tax: ' . (($spacegame['alliance']['tax_mult'] - 1.0) * 100) . '%<br />';

		echo '</div>';

		echo '<div class="docs_text">';
			echo '<a href="message.php?page=alliance&amp;name=' . $spacegame['alliance']['caption'] . '">Send Message</a>';
		echo '</div>';
	}
	else {
		?>
		<div class="docs_text">
			You are not in an alliance. Check out the <a href="alliance.php?page=list">alliance list</a>
			and find one which is recruiting. You can request to join up to <?php echo ALLIANCE_REQUEST_LIMIT ?>
			alliances at a time.
		</div>
<?php
	}
?>

<?php
	if ($spacegame['invites_count'] > 0) {
	
		echo '<hr />';
		echo '<div class="header4">Current Recruitment Requests</div>';
		echo '<div class="docs_text">';

		foreach ($spacegame['invites'] as $invite_id => $invite) {
			
			$alliance = $spacegame['alliances'][$invite['alliance']];

			echo '<a href="alliance.php?page=members&amp;alliance_id=' . $invite['alliance'] . '">';
			echo $alliance['caption'] . '</a>';
			echo ' since ' . date('Y-M-d', $invite['requested']) . ', ';

			if ($invite['rejected'] > 0) {
				echo 'rejected ' . date('Y-M-d', $invite['rejected']);
			}
			else {
				echo 'active';
			}

			echo '<br />';
		}

		echo '</div>';
	}

	
	if ($spacegame['player']['alliance']  > 0) {

		$db = isset($db) ? $db : new DB;

		if (defined('ALLIANCE_LEADER') && ALLIANCE_LEADER) {
			echo '<hr />';

			echo '<div class="header4">Current Recruitment Requests</div>';
			echo '<div class="docs_text">';

			if ($spacegame['alliance_invites_count'] > 0) {
				foreach ($spacegame['alliance_invites'] as $invite_id => $invite) {
					
					$player = array();

					$rs = $db->get_db()->query("select caption from players where record_id = '". $invite['player'] ."' limit 1");
					
					$rs->data_seek(0);
					if ($row = $rs->fetch_assoc()) {
						$player = $row;
					}

					echo '<a href="player.php?player_id=' . $invite['player'] . '">';
					echo $player['caption'] . '</a>';
					echo ' since ' . date('Y-M-d', $invite['requested']) . ', ';

					if ($invite['rejected'] > 0) {
						echo 'rejected ' . date('Y-M-d', $invite['rejected']);
					}
					else {
						
						echo ' [<a href="handler.php?task=alliance&amp;subtask=enroll&amp;player_id='. $invite['player'] .'">Enroll</a>]';
						echo ' [<a href="handler.php?task=alliance&amp;subtask=reject&amp;player_id='. $invite['player'] .'">Reject</a>]';
					}

					echo '<br />';
				}
			}
			else {
				echo '<em>No known recruitment requests.</em>';
			}

			echo '</div>';


			echo '<div class="header4">Recruitment Settings</div>';
			echo '<form action="handler.php" method="post">';
			?>
				<input type="hidden" name="task" value="alliance" />
				<input type="hidden" name="subtask" value="recruit" />
				<input type="hidden" name="return" value="alliance" />
			<?php

			if ($spacegame['alliance']['recruiting'] > 0) { 
				?>

				You can disable recruitment by clicking the following button. Note that disabling
				recruitment will reject any active requests to join.<br />
				<br />
				<script type="text/javascript">drawButton('disable', 'disable', 'validate_set_recruitment(false)');</script>
				<input type="hidden" name="value" value="0" />

			<?php } else { ?>

				You can enable recruitment by clicking the following button.<br />
				<br />
				<script type="text/javascript">drawButton('enable', 'enable', 'validate_set_recruitment(true)');</script>
				<input type="hidden" name="value" value="1" />
					
			<?php }

			echo '</form>';
		}
		else {

			echo '<hr />';

			echo '<div class="header4">Leave Alliance</div>';
			echo '<div class="docs_text">';

			echo 'The following button allows you to leave an alliance.';

			echo '<form action="handler.php" method="post">';
			?>
				<input type="hidden" name="task" value="alliance" />
				<input type="hidden" name="subtask" value="leave" />
				<input type="hidden" name="return" value="alliance" />
				<script type="text/javascript">drawButton('leave', 'leave', 'validate_leave_alignment()');</script>
				<input type="hidden" name="player_id" value="<?php echo $spacegame['player']['record_id'] ?>" />
			<?php
			echo '</form>';
			echo '</div>';
		}

	}
?>
