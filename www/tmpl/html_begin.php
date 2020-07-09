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
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>ME :: <?php echo $tmpl['page_title']; ?></title>
	<link rel="stylesheet" type="text/css" href="res/game.css" />
	<style type="text/css">
	
		<?php
			echo 'div.dss_position {';

			if (isset($spacegame['player'])) {

				// The size of the galaxy is 1000x1000 so get the player
				// pos relative to center of true galaxy.
				$px = $spacegame['player']['x'] - 500;
				$py = $spacegame['player']['y'] - 500;

				// The size of the galaxy.png image file is equal to the
				// galaxy size squared so get offset from player to center
				// of the galaxy image.
				$cx = (GALAXY_SIZE / 2) + $px;
				$cy = (GALAXY_SIZE / 2) - $py;

				// Offset the results by one-half the size of the actual
				// DSS viewport in the browser window.
				$x = -$cx + 96;
				$y = -$cy + 48;

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
				echo "var player_id = " . $spacegame['player']['record_id'] . ";\n";
				echo "var alliance = " . ($spacegame['player']['alliance'] > 0 ? $spacegame['player']['alliance'] : 0) . ";\n";
				echo "var base_id = " . $spacegame['player']['base_id'] . ";\n";
				echo 'var base_x = ' . $spacegame['player']['base_x'] . ";\n";
				echo 'var base_y = ' . $spacegame['player']['base_y'] . ";\n";
			}


			if (isset($players)) {
				echo "var players = [\n";

				foreach ($players as $record_id => $player) {

					if ($record_id == $spacegame['player']['record_id']) {
						continue;
					}

					if ($player['base_id'] <= 0) {
						continue;
					}

					echo "{";
					echo "id: '{$record_id}', ";
					echo "caption: '". $player['caption'] ."', ";
					echo "x: ". $player['x'] .", ";
					echo "y: ". $player['y'] .", ";
					echo "base_x: ". $player['base_x'] .", ";
					echo "base_y: ". $player['base_y'] .", ";
					echo "ar: ". $player['attack_rating'] .", ";
					echo "ship: ". $player['ship_type'] .", ";
					echo "alliance:" . ($player['alliance'] > 0 ? $player['alliance'] : 0) . ",";
					echo "acl: {},";
					echo "}, \n";
				}

				echo "];\n";

			}

			if (isset($spacegame['base'])) {
				echo "var base_owner = " . $spacegame['base']['owner'] . ";\n";
				echo "var base_alliance = " . ($spacegame['base']['alliance'] > 0 ? $spacegame['base']['alliance'] : 0) . ";\n";
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
					echo "build_time: " . ($room['finish_time'] - PAGE_START_TIME)  .", ";
					echo "}, \n";
				}

				echo "];\n";

			}
			else {
				echo "var base_place = 0;\n";
				echo "var base_seed = 0;\n";
			}

			if (isset($_SESSION['form_id'])) {
				echo "var form_id = '" . $_SESSION['form_id'] . "';\n";
			}
		?>
	</script>
	<link rel="icon" type="image/png" href="res/redacted_icon.png" />
</head>
<body onload="page_onload(<?php echo isset($tmpl['no_fluff']) ? 'true' : 'false'; ?>);">
<div id="wrapper">
	<?php if (!isset($tmpl['no_fluff'])) { ?>
	<header>
		<!-- use <span> instead of <div> as we want title text on logo right, not logo bottom -->
		<span id="game_logo"><img src="res/me_logo.png" width="320" alt="Merchant Empires logo" /></span>
		<span id="game_title"><?php echo ':: ' . $tmpl['page_title']; ?></span>
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
						echo '<a class="game_menu" href="handler.php?task=deselect_player&amp;form_id='. $_SESSION['form_id'] .'">Select Player</a>';
						echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
					}

					echo '<a class="game_menu" href="handler.php?task=logout&amp;form_id='. $_SESSION['form_id'] .'">Logout</a>';
				} else {
					echo '<a class="game_menu" href="login.php">Login</a>';
				}
			?>			
		</div>	
	</header>
	<?php } ?>
	<noscript>
	<div class="noscript">
		<div class="header3 header_bold">JavaScript Required</div>
		<p>
			This game requires JavaScript to offload some of the more CPU-intensive tasks of page
			rendering to your browser. It appears your JavaScript is blocked or disabled. If you
			are playing an official game of <a href="http://merchantempires.net">Merchant Empires</a>
			then a recent version of the JavaScript file can be viewed
			<a href="https://github.com/Redacted-Games-LLC/Merchant-Empires/blob/master/www/res/game.js">here</a>.
			We believe this code complies with our <a href="docs.php?page=privacy">privacy policy</a>.
		</p>
		<p>
			If you are playing on a custom or private server you will have to verify yourself if the
			JavaScript code is safe for you to execute.
		</p>
		<p>
			Please enable or unblock JavaScript and reload this page.
		</p>
	</div>
	</noscript>