<?php
/**
 * Administration page for base construction.
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
	
	if (!get_user_field(USER_ID, 'admin', 'build')) {
		header('Location: viewport.php?rc=1030');
		die();
	}

	include_once('inc/rooms.php');


?>
	<div class="header2">Base Construction Administration</div>
	<div class="docs_text">
		You can manipulate base construction using this page.
	</div>
	<hr />
	<div class="header2">Room List</div>
	<?php

		if ($spacegame['room_type_count'] <= 0) {
			echo '<div class="docs_text">';
			echo 'There are no rooms to display. This may be a game bug.';
			echo '</div>';
		}
		else {
			echo '<div class="docs_text">';
			echo '<div class="room_list">';

			foreach ($spacegame['room_types'] as $room_type_id => $room_type) {

				echo '<div class="room_list_item">';

				echo '<a href="admin.php?page=room&amp;room='. $room_type['safe_caption'] .'">';
				echo $room_type['caption'];
				echo '</a>';

				echo '</div>';
			}

			echo '</div>';
			echo '</div>';
		}



	?>
	<hr />
	<div class="header2">Add New Room</div>
	<div class="docs_text">
		Your room will be created with a build limit of 0 preventing it from being used by players
		until you have configured it, but it won't be visible.
	</div>
	<div class="docs_text">

		<form action="handler.php" method="post">
			<p>
				<label for="caption">Caption:</label>
				<input type="text" id="caption" name="caption" maxlength="24" size="25" value="" />
			</p>

			<script type="text/javascript">drawButton('add', 'add', 'validate_add()')</script>

			<input type="hidden" name="task" value="room" />
			<input type="hidden" name="subtask" value="add" />
			<input type="hidden" name="form_id" value="<?php echo $_SESSION['form_id']; ?>" />
		</form>

	</div>

	


