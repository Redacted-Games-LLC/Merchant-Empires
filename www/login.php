<?php
/**
 * Primary login page for the game.
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

	if (detect_iexplore11()) {
		header('Location: unsupported_browser.php');
	}
	
	if (USER_ID > 0) {
		if (PLAYER_ID > 0) {
			header('Location: viewport.php');
			die();
		}
		
		header('Location: select_player.php');
		die();
	}
	
	$tmpl['page_title'] = 'Log In';

	include_once('tmpl/html_begin.php');

	if (LOGIN_LOCKED) {
		echo '<div class="login_locked">';

		echo '<strong>Notice!</strong> Logins and signups are currently disabled while the game ';
		echo 'is under maintenance. Please try again shortly.';

		echo '</div>';
	}
	else {
		
		$round_start = START_OF_ROUND - PAGE_START_TIME;

		if ($round_start > 0 && !DEV_ROUND) {
			echo '<div class="login_locked">';

			echo '<strong>Notice!</strong> Logins are currently disabled because the round has not ';
			echo 'started yet. The round will start in ';

			if ($round_start > 86400 * 2) {
				$round_start /= 86400;
				echo ceil($round_start) . ' day' . ($round_start == 1 ? '' : 's') . '.';
			}
			else if ($round_start > 3600 * 2) {
				$round_start /= 3600;
				echo ceil($round_start) . ' hour' . ($round_start == 1 ? '' : 's') . '.';
			}
			else if ($round_start > 60 * 2) {
				$round_start /= 60;
				echo ceil($round_start) . ' minute' . ($round_start == 1 ? '' : 's') . '.';
			}
			else {
				echo ceil($round_start) . ' second' . ($round_start == 1 ? '' : 's') . '.';
			}

			echo '</div>';
		}
	}


	mt_srand(microtime(true));
	$test_x = mt_rand(300,700);
	$test_y = mt_rand(300,700);
	$test_dx = mt_rand(0, 10) - 5;
	$test_dy = mt_rand(0, 10) - 5;

	$x_word = '';

	if ($test_dx < 0) {
		$x_word = ($test_dx * -1) . ' unit' . ($test_dx == -1 ? '' : 's') . ' west';
	}
	else if ($test_dx > 0) {
		$x_word = $test_dx . ' unit' . ($test_dx == 1 ? '' : 's') . ' east';
	}
	else {
		$x_word = 'on the same x position';
	}

	$y_word = '';

	if ($test_dy < 0) {
		$y_word = ($test_dy * -1) . ' unit' . ($test_dy == -1 ? '' : 's') . ' south';
	}
	else if ($test_dy > 0) {
		$y_word = $test_dy . ' unit' . ($test_dy == 1 ? '' : 's') . ' north';
	}
	else {
		$y_word = 'on the same y position';
	}

	$phrase = 'Which sector would be ' . $x_word . ' of and ' . $y_word . ' from the sector ' . $test_x . ', ' . $test_y . '?';

?>


	<div id="login_form_box">
		<form id="login_form" action="handler.php" method="post">
			<div class="login_intro">
				Enter your username and password to join the game!
			</div>
			
			<div class="login_form_item">
				&nbsp;
				<div class="left_column login_form_label">
					<label for="usernamea">Username:</label>
				</div>
				<div class="right_column">
					<input id="usernamea" name="username" type="text" maxlength="16" size="20" />
				</div>
			</div>
			
			<div class="login_form_item">
				&nbsp;
				<div class="left_column login_form_label">
					<label for="password1a">Password:</label>
				</div>
				<div class="right_column">
					<input id="password1a" name="password1" type="password" maxlength="128" size="20" />
				</div>
			</div>
			
			<div class="login_form_upper_button">
				<script type="text/javascript">drawButton('lb1', 'login', 'validate_login()');</script>
			</div>
			
			<input id="login_task" name="task" type="hidden" value="1ogin" />
		</form>
		
		<hr noshade="noshade" size="2" />
		
		<form id="signup_form" action="handler.php" method="post">
			<div class="login_intro">
				If you don't have an account enter your information
				here and we'll get you started.
			</div>
		
			<div class="login_form_item">
				&nbsp;
				<div class="left_column login_form_label">
					<label for="usernameb">Username:</label>
				</div>
				<div class="right_column">
					<input id="usernameb" name="username" type="text" maxlength="16" size="20" />
				</div>
			</div>
			
			<div class="login_form_item">
				&nbsp;
				<div class="left_column login_form_label">
					<label for="password1b">Pass (6+ chrs):</label><br />
				</div>
				<div class="right_column">
					<input id="password1b" name="password1" type="password" maxlength="128" size="20" />
				</div>
			</div>
			
			<div class="login_form_item">
				&nbsp;
				<div class="left_column login_form_label">
					<label for="password2b">Confirm Password:</label>
				</div>
				<div class="right_column">
					<input id="password2b" name="password2" type="password" maxlength="128" size="20" />
				</div>
			</div>
			
			<div class="login_form_item">
				&nbsp;
				<div class="left_column login_form_label">
					<label for="email">EMail:</label>
				</div>
				<div class="right_column">
					<input id="emailb" name="email" type="text" maxlength="128" size="30" />
				</div>
			</div>

			<div class="login_form_item">
				<input id="policyb" name="policy" type="checkbox" />
				<label for="policyb">Check here to agree with <a href="docs.php?page=policy" target="_blank">our policies</a>.</label>
			</div>
			
			<div class="login_form_item">
				&nbsp;
				<div class="left_long_column login_form_label">
					<?php echo $phrase; ?>
				</div>
				<div class="right_short_column">
					<input id="try_x" name="try_x" type="text" maxlength="4" size="6" />,
					<input id="try_y" name="try_y" type="text" maxlength="4" size="6" />
					<!-- 
						This is not bot prevention. This is a filter for those who can't
						handle coordinate systems.
					-->
					<input type="hidden" name="test_x" value="<?php echo $test_x; ?>" />
					<input type="hidden" name="test_y" value="<?php echo $test_y; ?>" />
					<input type="hidden" name="test_dx" value="<?php echo $test_dx; ?>" />
					<input type="hidden" name="test_dy" value="<?php echo $test_dy; ?>" />
				</div>
			</div>
			<div>&nbsp;<br />&nbsp;<br />&nbsp;</div>
			<div class="login_form_lower_button">
				<script type="text/javascript">drawButton('sb1', 'signup', 'validate_signup()');</script>
			</div>
			
			<input id="signup_task" name="task" type="hidden" value="1ogin" />
		</form>
	</div>




	
<?php	
	include_once('tmpl/html_end.php');
?>