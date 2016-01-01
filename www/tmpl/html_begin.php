<?php
/**
 * Header for the game pages
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
?><!DOCTYPE html>
<html>
<head>
	<title>ME <?php echo $tmpl['page_title']; ?></title>
	<link rel="stylesheet" type="text/css" href="res/game.css" />
	<style type="text/css">
	
		<?php
			echo 'div.dss_position {';

			if (isset($spacegame['player'])) {

				$x = $spacegame['player']['x'] - 500;
				$y = $spacegame['player']['y'] - 500;

				$x += 360;
				$y += 360;

				$y = 720 - $y;

				$x *= -1;
				$y *= -1;

				$x += 96;
				$y += 48;

				echo 'background-position: ' . $x . 'px ' . $y . 'px;';
			}

			echo '}';
		?>
	</style>
	<script src="res/game.js"></script>
	<script>
		<?php
			if (isset($_GET['rc']) && strlen($_GET['rc']) > 0) {
				echo "var messages = new Array();\n";
				echo "var msgCodes = new Array();\n";

				foreach (explode(',', $_GET['rc']) as $code) {
					echo "messages.push('" . get_message($code) . "')\n";
					echo "msgCodes.push('" . $code . "')\n";
				}
			}
			else {
				echo "var messages = null;\n";
				echo "var msgCodes = null;\n";
			}

			if (isset($spacegame['player'])) {
				echo "var base_id = " . $spacegame['player']['base_id'] . ";\n";
				echo 'var base_x = ' . $spacegame['player']['base_x'] . "\n;";
				echo 'var base_y = ' . $spacegame['player']['base_y'] . "\n;";
			}

			if (isset($spacegame['base'])) {
				echo "var base_place = " . $spacegame['base']['place'] . ";\n";
				echo "var base_seed = ". $spacegame['base']['seed'] .";\n";

				echo "var base_rooms = [\n";

				foreach ($spacegame['rooms'] as $room_id => $room) {
					echo "{";
					echo "id: '{$room_id}', ";
					echo "caption: '". $room['caption'] ."', ";
					echo "x: ". $room['x'] .", ";
					echo "y: ". $room['y'] .", ";
					echo "width: ". $room['width'] .", ";
					echo "height: ". $room['height'] .", ";
					echo "over: ". (isset($spacegame['over_rooms'][$room_id]) ? 'true' : 'false') .", ";
					echo "}, \n";
				}

				echo "];\n";

			}
			else {
				echo "var base_place = 0;\n";
				echo "var base_seed = 0;\n";
			}

		?>
	</script>
	<link rel="icon" type="image/png" href="res/redacted_icon.png" />
</head>
<body onload="page_onload(<?php echo isset($tmpl['no_fluff']) ? 'true' : 'false'; ?>);">
	<?php if (!isset($tmpl['no_fluff'])) { ?>
	<div id="first_header">
		<div id="game_logo">
			<img src="res/me_logo.png" width="320" />
		</div>
		<div id="game_title">
			<?php echo ':: ' . $tmpl['page_title']; ?>
		</div>
		<div id="game_menu">
			<?php
				if (USER_ID > 0) {
					if (PLAYER_ID > 0) {
						echo '<a class="game_menu" href="viewport.php">Refresh Viewport</a>';
						echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
					
						echo '<a class="game_menu" href="gold.php">Gold</a>';
						echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
					}
					else {
						echo '<a class="game_menu" href="select_player.php">Select Player</a>';
						echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
					}
				}
				
				echo '<a class="game_menu" href="docs.php">Docs</a>';
				echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
					
				if (USER_ID > 0) {
					if (PLAYER_ID > 0) {
						echo '<a class="game_menu" href="handler.php?task=deselect_player">Select Player</a>';
						echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
					}

					echo '<a class="game_menu" href="handler.php?task=logout">Logout</a>';
				} else {
					echo '<a class="game_menu" href="login.php">Login</a>';
				}
			?>
			
		</div>
		
	</div>
	<?php } ?>


