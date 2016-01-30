<?php
/**
 * Administration page for individual base rooms.
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

	if (isset($_REQUEST['room'])) {
		foreach ($spacegame['room_index'] as $caption => $room_id) {
			if ($_REQUEST['room'] == $caption) {
				$spacegame['room'] = $spacegame['room_types'][$room_id];
			}
		}

		include_once('inc/goods.php');
		include_once('inc/research.php');
	}

?>
	<div class="header2">Base Room Administration</div>
	<?php
		if (!isset($spacegame['room'])) {
			echo '<div class="docs_text">';
			echo 'Please return to the <a href="admin.php?page=build">room list</a> and select a room.';
			echo '</div>';
		}
		else {
			echo '<div class="docs_text">';
			echo 'Editing room: <strong>' . $spacegame['room']['caption'] . '</strong>';
			echo '</div>';

			echo '<hr />';
		
			echo '<div class="header3">Room Details</div>';
			?>
			<div class="docs_text">
				<div class="float_right">
					<img src="res/base/rooms/<?php echo $spacegame['room']['safe_caption']; ?>.png" width="260px" />
				</div>
				<form action="handler.php" method="post">

					<p>
						<label for="caption">Caption:</label>
						<input type="text" id="caption" name="caption" maxlength="24" size="25" value="<?php echo $spacegame['room']['caption']; ?>" />
						(max 24 chars)
					</p>

					<p>
						<label for="width">Width:</label>
						<input type="text" id="width" name="width" maxlength="2" size="4" value="<?php echo $spacegame['room']['width']; ?>" />
						&nbsp;&nbsp;
						<label for="height">Height:</label>
						<input type="text" id="height" name="height" maxlength="2" size="4" value="<?php echo $spacegame['room']['height']; ?>" />
						(max <?php echo MAX_BASE_ROOM_SIZE; ?> each)
					</p>

					<p>
						<label for="build_limit">Limit Per Base:</label>
						<input type="text" id="build_limit" name="build_limit" maxlength="3" size="4" value="<?php echo $spacegame['room']['build_limit']; ?>" />
					</p>

					<p>
						<input type="checkbox" id="can_land" name="can_land" <?php if ($spacegame['room']['can_land'] > 0) { echo 'checked="checked"'; } ?> value="land" />
						<label for="can_land">Can be used as a landing pad</label>
					</p>

					<p>
						<label for="build_time">Build Time:</label>
						<input type="text" id="build_time" name="build_time" maxlength="8" size="9" value="<?php echo $spacegame['room']['build_time']; ?>" />
						&nbsp;&nbsp;
						<label for="build_cost">Cost:</label>
						<input type="text" id="build_cost" name="build_cost" maxlength="8" size="9" value="<?php echo $spacegame['room']['build_cost']; ?>" />
						<img src="res/credits.png" width="16" />
					</p>

					<p>
						<label for="turn_cost">Turn Cost:</label>
						<input type="text" id="turn_cost" name="turn_cost" maxlength="4" size="5" value="<?php echo $spacegame['room']['turn_cost']; ?>" />
					</p>

					<p>
						<label for="experience">Experience earned:</label>
						<input type="text" id="experience" name="experience" maxlength="8" size="9" value="<?php echo $spacegame['room']['experience']; ?>" />
					</p>

					<p>
						<label for="power">Power Generated/-Used:</label>
						<input type="text" id="power" name="power" maxlength="4" size="5" value="<?php echo $spacegame['room']['power']; ?>" />
					</p>

					<p>
						<label for="armor">Armor:</label>
						<input type="text" id="armor" name="armor" maxlength="4" size="5" value="<?php echo $spacegame['room']['hit_points']; ?>" />
						&nbsp;&nbsp;
						<label for="shield_generators">Shield Gens:</label>
						<input type="text" id="shield_generators" name="shield_generators" maxlength="2" size="3" value="<?php echo $spacegame['room']['shield_generators']; ?>" />
					</p>

					<p>
						<label for="turrets">Turrets:</label>
						<input type="text" id="turrets" name="turrets" maxlength="2" size="3" value="<?php echo $spacegame['room']['turrets']; ?>" />
						&nbsp;&nbsp;
						<label for="turret_damage">Damage:</label>
						<input type="turret_damage" id="turret_damage" name="turret_damage" maxlength="4" size="5" value="<?php echo $spacegame['room']['turret_damage']; ?>" />
					</p>

					<p>
						<label for="good">Good:</label>
						<select id="good" name="good">
							<option value="[none]">[None]</option>
							<?php

								foreach ($spacegame['goods'] as $good_id => $good) {
									echo '<option value="' . $good['safe_caption'] . '"';

									if ($good_id == $spacegame['room']['good']) {
										echo ' selected="selected"';
									}

									echo '>' . $good['caption'] . '</option>';
								}
							?>
						</select>
						&nbsp;&nbsp;
						<label for="production">Production:</label>
						<input type="text" id="production" name="production" maxlength="3" size="4" value="<?php echo $spacegame['room']['production']; ?>" />
					</p>

					<script type="text/javascript">drawButton('update', 'update', 'validate_update()')</script>

					<input type="hidden" name="task" value="room" />
					<input type="hidden" name="subtask" value="edit" />
					<input type="hidden" name="room" value="<?php echo $spacegame['room']['safe_caption']; ?>" />
				</form>
			</div>

			<hr />
			<div class="header3">Room Requirements</div>
			<div class="docs_text">
				This section was rushed through. Right now there is no checking for duplicates and
				deletions take everything out. This will be fixed in a later version.
			</div>
			<?php
				if ($spacegame['room']['goods_count'] + $spacegame['room']['researches_count'] + $spacegame['room']['builds_count'] <= 0) {
					echo '<div class="docs_text">';
					echo 'There are no requirements for this structure.';
					echo '</div>';
				}
				else {
					echo '<div class="docs_text">';
					echo '<a href="handler.php?task=room&amp;subtask=delete_requirement&amp;room='. $spacegame['room']['safe_caption'] .'">Click here</a>';
					echo ' to delete all requirements. This structure requires the following:';
					echo '</div>';

					if ($spacegame['room']['goods_count'] > 0) {
						echo '<div class="header4">Goods</div>';
						echo '<div class="docs_text">';
					
						foreach ($spacegame['room']['goods_needed'] as $good_id) {
							echo $spacegame['goods'][$good_id]['caption'] . '<br />';
						}

						echo '</div>';
					}

					if ($spacegame['room']['researches_count'] > 0) {
						echo '<div class="header4">Researches</div>';
						echo '<div class="docs_text">';
					
						foreach ($spacegame['room']['researches_needed'] as $research_id) {
							echo $spacegame['research_items'][$researcn_id]['caption'] . '<br />';
						}

						echo '</div>';
					}

					if ($spacegame['room']['builds_count'] > 0) {
						echo '<div class="header4">Builds</div>';
						echo '<div class="docs_text">';
					
						foreach ($spacegame['room']['builds_needed'] as $build_id) {
							echo $spacegame['room_types'][$build_id]['caption'] . '<br />';
						}

						echo '</div>';
					}
				}

			?>
		
			<div class="header4">Add Requirement</div>
			<div class="docs_text">
				<form action="handler.php" method="post">

					<p>
						<label for="required_good">Required Good:</label>
						<select id="required_good" name="good">
							<option value="[none]">[None]</option>
							<?php

								foreach ($spacegame['goods'] as $good_id => $good) {
									echo '<option value="' . $good['safe_caption'] . '"';
									echo '>' . $good['caption'] . '</option>';
								}
							?>
						</select>
						&nbsp;&nbsp;
						<label for="amount">Amount:</label>
						<input type="text" id="amount" name="amount" maxlength="5" size="6" value="0" />
					</p>

					<p>
						<label for="required_research">Required Research:</label>
						<select id="required_research" name="research">
							<option value="[none]">[None]</option>
							<?php

								foreach ($spacegame['research_items'] as $research_id => $research) {
									echo '<option value="' . $research['safe_caption'] . '"';
									echo '>' . $research['caption'] . '</option>';
								}
							?>
						</select>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<label for="required_build">Required Build:</label>
						<select id="required_build" name="build">
							<option value="[none]">[None]</option>
							<?php

								foreach ($spacegame['room_types'] as $room_id => $room) {
									if ($room_id == $spacegame['room']['record_id']) {
										continue;
									}

									echo '<option value="' . $room['safe_caption'] . '"';
									echo '>' . $room['caption'] . '</option>';
								}
							?>
						</select>
					</p>


					<script type="text/javascript">drawButton('add', 'add', 'validate_add()')</script>

					<input type="hidden" name="task" value="room" />
					<input type="hidden" name="subtask" value="add_requirement" />
					<input type="hidden" name="room" value="<?php echo $spacegame['room']['safe_caption']; ?>" />
				</form>
			</div>
			<hr />
			<div class="header3">Delete This Room</div>
			<?php 
				if ($spacegame['room']['upgrade_count'] > 0) { ?>

				<div class="docs_text">
					You cannot delete this room because the following rooms depend on it:
				</div>

				<div class="docs_text">
				<?php
					echo '<div class="room_list">';

					foreach ($spacegame['room']['upgrades'] as $room_type_id) {

						echo '<div class="room_list_item">';

						echo '<a href="admin.php?page=room&amp;room='. $spacegame['room_types'][$room_type_id]['safe_caption'] .'">';
						echo $spacegame['room_types'][$room_type_id]['caption'];
						echo '</a>';

						echo '</div>';
					}

					echo '</div>';
				?>
				</div>

			<?php } else { ?>

				<div class="docs_text">
					<a href="handler.php?task=room&amp;subtask=delete&amp;room=<?php echo $spacegame['room']['safe_caption']; ?>">Completely delete</a> this room.
				</div>

			<?php } ?>

			


		<?php
		}
	?>



