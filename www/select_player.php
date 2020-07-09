<?php
/**
 * Allows a user to create and select players for the game
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

	include_once('inc/page.php');

	if (USER_ID <= 0) {
		header('Location: login.php');
		die();
	}
	
	if (PLAYER_ID > 0) {
		header('Location: viewport.php');
		die();
	}
	
	$tmpl['page_title'] = 'Select Player';

	include_once('tmpl/html_begin.php');
?>

<div class="full_spread">
	<div class="player_select_outer">
		<div class="player_select_title">
			Select Player
		</div>
		<?php
		
		$race_list = array();
		
		$db = isset($db) ? $db : new DB;
		$rs = $db->get_db()->query("select record_id, caption from races order by record_id");
		
		$rs->data_seek(0);
		while ($row = $rs->fetch_assoc()) {
			$race_list[$row['record_id']] = $row['caption'];
		}
		
		$player_list = array();
		$player_count = 0;
		
		$rs = $db->get_db()->query("select user as user_id, player as player_id, players.caption, races.caption as race from user_players, players, races where player = players.record_id and players.race = races.record_id and user = '". USER_ID ."' limit " . MAX_PLAYERS_PER_USER);
		
		if (!$rs){
			error_log(__FILE__ . '::' . __LINE__ . ' Error while a list of players for a user.');
			die('<center>Player Selection is briefly unavailable.</center>');
		}

		$rs->data_seek(0);

		while ($row = $rs->fetch_assoc()) {
			$player_list[] = array('id' => $row['player_id'], 'caption' => $row['caption'], 'race' => $row['race']);
			$player_count += 1;
		}
		
		$player_number = 0;
		
		for ($i = 1; $i <= MAX_PLAYERS_PER_USER; $i++) {
			?>
			<div class="player_select">
				<form id="select_player_form<?php echo $i; ?>" action="handler.php" method="post">
				<?php 
					if ($player_number < $player_count) {
						$player = $player_list[$player_number];
						$player_number++;
						
						?>
						<div>
							&nbsp;<br />
							<br />
							<br />
							<br />
							<?php echo $player['race']; ?><br />
							<br />
						</div>
						
						<div class="header3 header_bold">
							<?php echo $player['caption']; ?><br />
							<br />
						</div>
						
						<script type="text/javascript">drawButton('sel<?php echo $i; ?>', 'join', 'validate_select_player(<?php echo $i; ?>)');</script>
						<input type="hidden" name="player_id" value="<?php echo $player['id']; ?>" />
						<input type="hidden" name="return" value="select_player" />
						<input id="select_player_task<?php echo $i; ?>" name="task" type="hidden" value="select_player" />
						<?php
					}
					else {
					?>
						<label for="player_name<?php echo $i; ?>"><small>Player <?php echo $i; ?> Name</small></label><br />
						<input type="text" class="select_player_name" id="player_name<?php echo $i; ?>" name="player_name" maxlength="12" /><br />
						<br />
						<label for="player_race<?php echo $i; ?>"><small>Racial Origin</small></label><br />
						<select id="player_race<?php echo $i; ?>" name="player_race">
							<?php
								foreach ($race_list as $id => $caption) {
									echo "<option value='{$id}'";
									
									if ($i == $id) {
										echo ' selected="selected"';
									}
									
									echo ">{$caption}</option>";
								}
							
								if ($i == MAX_PLAYERS_PER_USER) {
									echo '<option value="0" selected="selected">[Random Start]</option>';
								}
								else {
									echo '<option value="0">[Random Start]</option>';
								}
									
							?>
							
							
						</select>
						<br />
						<br />
						<small><br />
						<a href="docs.php?page=players" target="_blank">Open Help</a><br />
						<br /></small>
						<br />
						<script type="text/javascript">drawButton('ylw<?php echo $i; ?>', 'create', 'validate_create_player(<?php echo $i; ?>)');</script><br />
						<br />
						This information is public.
					
						<input type="hidden" name="return" value="select_player" />
						<input id="select_player_task<?php echo $i; ?>" name="task" type="hidden" value="create_player" />
				<?php
					}
				
				?>
				<input type="hidden" name="form_id" value="<?php echo $_SESSION['form_id']; ?>" />
				</form>
			</div>
			<?php
		}
		?>
	</div>

</div>

<?php	
	include_once('tmpl/html_end.php');
?>