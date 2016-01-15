<?php
/**
 * Administration page for a specific user.
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

	if (!get_user_field(USER_ID, 'admin', 'users')) {
		header('Location: viewport.php?rc=1030');
		die();
	}


	include_once('inc/user.php');


?>
	<div class="header2">User Administration: <em><?php echo $spacegame['user_info']['username']; ?></em></div>
	<?php if (!isset($spacegame['user_info'])) { ?>

		<div class="docs_text">
			No user information found. Please select another user from the 
			<a href="admin.php?page=users">users page</a>.
		</div>
		
	<?php } else { ?>

		<div class="docs_text">
			Last Activity: <strong><?php echo date(DATE_RFC850, $spacegame['user_info']['session_time']); ?></strong>
		</div>
		<div class="header3">Players</div>

		<?php if ($spacegame['user_info']['player_count'] <= 0) { ?>

			<div class="docs_text">
				This user has not created any players.
			</div>

		<?php } else { ?>

			<div class="docs_text">
				This user has the following players:
			</div>
			<div class="docs_text">
				<?php
				foreach ($spacegame['user_info']['players'] as $player) {

					echo '<a href="admin.php?page=player&amp;name=' . $player['caption'] . '">';
					echo $player['caption'];
					echo '</a>';
					echo ', accessed ' . date(DATE_RFC850, $player['session_time']);
					echo '<br />';
				}
				?>
			</div>


		<?php } ?>


		<div class="header3">User Fields</div>
		<div class="docs_text">
			<?php
				echo '<ul>';

				foreach ($spacegame['user_info']['fields'] as $group_caption => $group) {
					echo '<li>' . $group_caption;
					echo '<ul>';

					foreach ($group as $key_caption => $value) {
						echo '<li>';

						echo '<form action="handler.php" method="post">';

						echo $key_caption . ' = ';
						
						echo '<input class="form_input" name="value" type="text" maxlength="64" size="16" value="'.$value.'" /> ';

						echo "<script type='text/javascript'>drawButton('update_".$group_caption."_".$key_caption."', 'update', 'validate_update()');</script> ";
						echo "<script type='text/javascript'>drawButton('delete_".$group_caption."_".$key_caption."', 'delete', 'validate_delete()');</script> ";
						
						echo '<input type="hidden" name="group" value="'. $group_caption .'" />';
						echo '<input type="hidden" name="key" value="'. $key_caption .'" />';
						echo '<input type="hidden" name="user" value="'. $spacegame['user_info']['username'] .'" />';
						echo '<input type="hidden" name="task" value="users" />';
						echo '<input type="hidden" name="subtask" value="field" />';

						echo '</form>';
						echo '</li>';
					}
					
					echo '</ul>';
					echo '</li>';
				}

				echo '</ul>';
			?>
		</div>
		<div class="docs_text">
			Enter the information to add a new user field:
		</div>
		<div class="docs_text">
			<form action="handler.php" method="post">
				Group: <input class="form_input" name="group" type="text" maxlength="16" size="10" value="" />
				Key: <input class="form_input" name="key" type="text" maxlength="16" size="10" value="" />
				Value: <input class="form_input" name="value" type="text" maxlength="64" size="16" value="" />

				<script type='text/javascript'>drawButton('add', 'add', 'validate_add()');</script>
				
				<input type="hidden" name="user" value="<?php echo $spacegame['user_info']['username']; ?>" />
				<input type="hidden" name="task" value="users" />
				<input type="hidden" name="subtask" value="field" />

			</form>
		</div>

	<?php } ?>


	
<?php


?>