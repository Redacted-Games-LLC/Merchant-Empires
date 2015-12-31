<?php
/**
 * 
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

	if (USER_ID > 0) {
		if (PLAYER_ID > 0) {
			header('Location: viewport.php');
			die();
		}
		
		header('Location: select_player.php');
		die();
	}
	
	$tmpl['page_title'] = 'Login User';

	include_once('tmpl/html_begin.php');
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
					<input id="password1a" name="password1" type="password" maxlength="16" size="20" />
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
					<label for="password1b">Password:</label>
				</div>
				<div class="right_column">
					<input id="password1b" name="password1" type="password" maxlength="16" size="20" />
				</div>
			</div>
			

			<div class="login_form_item">
				&nbsp;
				<div class="left_column login_form_label">
					<label for="password2b">Confirm Password:</label>
				</div>
				<div class="right_column">
					<input id="password2b" name="password2" type="password" maxlength="16" size="20" />
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
			
			<div class="login_form_item login_form_label">
				<input id="policyb" name="policy" type="checkbox" />
				<label for="policyb">Check here to agree with <a href="docs.php?page=policy" target="_blank">our policies</a>.</label>
			</div>
			
			<div class="login_form_lower_button">
				<script type="text/javascript">drawButton('sb1', 'signup', 'validate_signup()');</script>
				
			</div>
			
			<input id="signup_task" name="task" type="hidden" value="1ogin" />
		</form>
	</div>


	
<?php	
	include_once('tmpl/html_end.php');
?>