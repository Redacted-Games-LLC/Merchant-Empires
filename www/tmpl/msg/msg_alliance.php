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

	define('SENT_MSG_TYPE', 2);
	include_once('inc/messages.php');

	$name = '';

	if (isset($_REQUEST['name']) && validate_alliancename($_REQUEST['name'])) {
		$name = $_REQUEST['name'];
	}

?>

<!--
Need to remove bolding for <th>.
Need to align table headers.
-->

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
				<td class="message align_right">
					<span class="characters_left" id="characters_left">&nbsp;</span>
				</td>
			</tr>
			<tr class="message">
				<td class="message">Message:</td>
				<td class="message" colspan="2"><textarea class="msg_form_input" id="msg_input" name="message" rows="6" cols="60" maxlength="<?php echo MAXIMUM_MESSAGE_LENGTH; ?>"></textarea></td>
			</tr>
			<tr class="message">
				<td class="message">&nbsp;</td>
				<td class="message" colspan="2">
					<script type="text/javascript">
						drawButton('send', 'send', 'validate_send()');
						register_textarea_length_handlers('msg_input', 'characters_left', <?php echo MAXIMUM_MESSAGE_LENGTH; ?>);
					</script>
					<span class="turn_cost">This action costs <?php echo MESSAGE_TURN_COST; ?> turns.</span>
				</td>
			</tr>
		</table>
		<input type="hidden" name="task" value="message" />
		<input type="hidden" name="subtask" value="alliance" />
		<input type="hidden" name="form_id" value="<?php echo $_SESSION['form_id']; ?>" />
	</form>
</div>
<div class="docs_text">
	Alliance messages expire after <?php echo floor(MESSAGE_EXPIRATION / 86400); ?>
	days.
</div>
<hr />
<div class="header3">Sent Messages</div>
<div class="docs_text">
	<?php 
		if ($spacegame['message_count'] <= 0) {
			echo 'You have no sent messages to display.';
		}
		else {
			include_once('tmpl/msg/msg.php');
		}
	?>
</div>