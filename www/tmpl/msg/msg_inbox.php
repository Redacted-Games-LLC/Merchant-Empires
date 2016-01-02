<?php
/**
 * Inbox page for messaging
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
	include_once('inc/messages.php');


?>
<div class="header2">Inbox</div>
<div class="docs_text">
	<?php
		if ($spacegame['message_count'] <= 0) {
			echo 'You have no messages in your inbox.';
		}
		else {
			foreach ($spacegame['messages'] as $record_id => $message) {

				echo '<div class="message">';
					echo '<div class="message_head">';
						echo '<div class="message_actions">';

							if ($message['sender'] > 0) {
								echo '<a href="message.php?page=player&amp;name='. $spacegame['message_senders'][$message['sender']] . '">';
								echo 'Reply';
								echo '</a>';
								echo '&nbsp;&nbsp;&nbsp;&nbsp;';
							}

							echo 'Hide&nbsp;&nbsp;&nbsp;&nbsp;Delete';
						echo '</div>';
						
						switch ($message['type']) {
							case 0:
								echo 'Official Broadcast';
								break;

							case 1:
								echo 'Message';
								break;

							case 2:
								echo 'Alliance message';
								break;

							case 3:
								echo 'Subspace broadcast';
								break;

							default:
								echo 'Unknown Message';
								break;
						}

						if ($message['sender'] > 0) {
							echo ' from ';
							echo '<a href="alliance.php?page=player&amp;player_id=' . $message['sender'] . '">';
							echo $spacegame['message_senders'][$message['sender']];
							echo '</a>';
						}

					echo '</div>';
					echo '<div class="message_text">';
						echo htmlentities($message['message']);
					echo '</div>';
					echo '<div class="message_posted">';
						echo date(DATE_RFC850, $message['posted']);
					echo '</div>';
					
				echo '</div>';
			}
		}

	?>
</div>

<hr />
<div class="header3">Ignore List</div>
<div class="docs_text">
	You are not ignoring anybody.
</div>
