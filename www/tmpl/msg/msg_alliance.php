<?php
/**
 * Compose page for messaging an alliance
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

	$name = '';

	if (isset($_REQUEST['name']) && validate_alliancename($_REQUEST['name'])) {
		$name = $_REQUEST['name'];
	}

?>
<div class="header2">Message Alliance</div>
<div class="docs_text">
	Sends a message to an <a href="alliance.php?page=list">alliance</a> by name. All
	members of the alliance will receive the message.
</div>
<div class="docs_text">
	<form action="handler.php" method="post">
		<table class="message">
			<tr class="message">
				<td class="message">Alliance:</td>
				<td class="message"><input class="msg_form_input" type="text" name="alliance" value="<?php echo $name ?>" maxlength="24" size="24" /></td>
			</tr>
			<tr class="message">
				<td class="message">Message:</td>
				<td class="message"><textarea class="msg_form_input" name="message" rows="6" cols="60" maxlength="400"></textarea></td>
			</tr>
			<tr class="message">
				<td class="message">&nbsp;</td>
				<td class="message">
					<script type="text/javascript">drawButton('send', 'send', 'validate_send()');</script>
					<span class="turn_cost">This action costs <?php echo MESSAGE_TURN_COST; ?> turns.</span>
				</td>
			</tr>
		</table>
		<input type="hidden" name="task" value="message" />
		<input type="hidden" name="subtask" value="alliance" />
	</form>
</div>
<div class="docs_text">
	Alliance messages expire after <?php echo floor(MESSAGE_EXPIRY / 86400); ?>
	days.
</div>

