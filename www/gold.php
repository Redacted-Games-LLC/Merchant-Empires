<?php
/**
 * Gold Membership page
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
	include_once('inc/game.php');

	$tmpl['page_title'] = 'Gold Membership';
	
	include_once('tmpl/html_begin.php');

?>

<div class="full_spread">
	<div class="header2"><img src="res/gold.png" width="16" />&nbsp;Gold Membership Information</div>

	<div class="docs_text">
		<?php 
			if ($spacegame['gold']) {
				echo 'Your gold membership expires within ';

				$time_left = $spacegame['player']['gold_expiration'] - PAGE_START_TIME;

				if ($time_left > 604800) {
					$weeks_left = ceil($time_left / 604800);
					echo $weeks_left . ' week' . ($weeks_left == 1 ? '' : 's');
				}
				elseif ($time_left > 86400) {
					$days_left = ceil($time_left / 86400);
					echo $days_left . ' day' . ($days_left == 1 ? '' : 's');
				}
				elseif ($hours_left > 3600) {
					$hours_left = ceil($time_left / 3600);
					echo $hours_left . ' hour' . ($hours_left == 1 ? '' : 's');
				}
				elseif ($time_left > 60) {
					$minutes_left = ceil($time_left / 60);
					echo $minutes_left . ' minute' . ($minutes_left == 1 ? '' : 's');
				}
				else {
					echo $time_left . ' second' . ($time_left == 1 ? '' : 's');
				}

				echo ' on ' . date(DATE_RSS, $spacegame['player']['gold_expiration']) . '. ';
				echo 'To extend your membership enter another key below.';
			}
			else {
				echo 'You do not have an active gold membership.To activate one, enter ';
				echo 'enter a Gold key below.';
			}
		?>
	</div>
	<hr />
	<div class="header3">Activate Membership Key</div>
	<div class="docs_text">
		Enter a key here to activate it on your membership
	</div>
	<div class="docs_text">
		<form action="handler.php" method="post">
			<input type="text" name="key" value="" maxlength="72" size="72" />
			<script type="text/javascript">drawButton('enable', 'enable', 'validate_enable()');</script>
			<input type="hidden" name="task" value="gold" />
			<input type="hidden" name="subtask" value="enable" />
		</form>
	</div>
	<hr />
	<div class="header3">Store Membership Key</div>
	<div class="docs_text">
		Storing a membership key prevents it from being used by any other user
		and will enable you to securely transfer it to another user if you wish.
	</div>
	<div class="docs_text">
		Note that even though keys are stored by user they are transferred by
		player. The receiving user can choose which player to apply the key to.
	</div>
	<div class="docs_text">
		<form action="handler.php" method="post">
			<input type="text" name="key" value="" maxlength="72" size="72" />
			<script type="text/javascript">drawButton('add', 'add', 'validate_add()');</script>
			<input type="hidden" name="task" value="gold" />
			<input type="hidden" name="subtask" value="add" />
		</form>
	</div>
	<hr />
	<div class="header3">Stored Keys</div>
	<div class="docs_text">
		You have no stored keys.
	</div>
	
	<br class="clear" />
</div>

	
<?php	
	include_once('tmpl/html_end.php');
?>