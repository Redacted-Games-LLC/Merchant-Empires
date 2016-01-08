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

	define('LOAD_IGNORE_CAPTIONS', true);
	include_once('inc/msg_functions.php');

	

?>
<div class="header2">Inbox</div>
<div class="docs_text">
	<?php
		if ($spacegame['message_count'] <= 0) {
			echo 'You have no messages in your inbox.';
		}
		else {


			$ignore_counter = 0;

			foreach ($spacegame['messages'] as $record_id => $message) {

				if ($message['type'] != 4 && isset($spacegame['ignore_index'][$message['sender']])) {
					$ignore_counter += 1;
					continue;
				}

				if ($ignore_counter > 0) {

					echo '<div class="message_ignored">';
					echo $ignore_counter . ' message' . ($ignore_counter == 1 ? '' : 's');
					echo ' ignored.';
					echo '</div>';

					$ignore_counter = 0;
				}

				$msg_type = 'Unknown Message';
				$msg_box = 'message';
				$msg_head = 'message_head';
				

				switch ($message['type']) {
					case 0:
						$msg_type = 'Official Broadcast';
						$msg_box = 'official_message';
						$msg_head = 'official_message_head';
						break;

					case 1:
						$msg_type = 'Message';
						break;

					case 2:
						$msg_type = 'Alliance message';
						$msg_box = 'alliance_message';
						$msg_head = 'alliance_message_head';
						break;

					case 3:
						$msg_type = 'Subspace broadcast';
						$msg_box = 'subspace_message';
						$msg_head = 'subspace_message_head';
						break;

					case 4:
						$msg_type = 'Battle report';
						$msg_box = 'battle_message';
						$msg_head = 'battle_message_head';
						break;

				}


				echo '<div class="' . $msg_box . '">';
					echo '<div class="'. $msg_head .'">';
						echo '<div class="message_actions">';

							if ($message['sender'] > 0) {
								echo '<a href="message.php?page=player&amp;name='. $spacegame['message_senders'][$message['sender']] . '">';
								echo 'Reply';
								echo '</a>';
								echo '&nbsp;&nbsp;&nbsp;&nbsp;';
							}

							echo '<a href="handler.php?task=message&amp;subtask=hide&amp;message='. $message['message_id'] .'&amp;p='. $spacegame['page_number'] .'&amp;pp='. $spacegame['per_page'] .'">Hide</a>';
							echo '&nbsp;&nbsp;&nbsp;&nbsp;';
							echo '<a href="handler.php?task=message&amp;subtask=delete&amp;message='. $message['message_id'] .'&amp;p='. $spacegame['page_number'] .'&amp;pp='. $spacegame['per_page'] .'">Delete</a>';

							if ($message['sender'] > 0) {
								echo '&nbsp;&nbsp;&nbsp;&nbsp;';
								echo '<a href="handler.php?task=message&amp;subtask=ignore&amp;player='. $spacegame['message_senders'][$message['sender']] .'&amp;p='. $spacegame['page_number'] .'&amp;pp='. $spacegame['per_page'] .'">Ignore</a>';
							}
							
						echo '</div>';
						
						echo $msg_type;

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
						echo 'Sent '. date(DATE_RFC850, $message['posted']);
					echo '</div>';
					
				echo '</div>';
			}

			if ($ignore_counter > 0) {

				echo '<div class="message_ignored">';
				echo $ignore_counter . ' message' . ($ignore_counter == 1 ? '' : 's');
				echo ' ignored.';
				echo '</div>';
			}

			echo '<div id="pagination">';
			echo '<br clear="all" />';
			echo '</div>';
			echo '<script type="text/Javascript">load_pagination('. $spacegame['page_number'] .', '. $spacegame['max_pages'] .',"message.php?page=inbox&pp='. $spacegame['per_page'] .'&")</script>';
		}

	?>
</div>

<hr />
<div class="header3">Ignore List</div>
<div class="docs_text">
	<?php

		if ($spacegame['ignore_list_count'] <= 0) {

			echo 'You are not ignoring anybody.';

		}
		else {
			echo 'Click on the player name to remove them from the ignore list.';
			echo '<br /><br />';

			echo '<div class="ingore_menu">';
			echo '<ul class="ignore_list">';

			foreach ($spacegame['ignore_list'] as $record_id => $row) {
				echo '<li class="ignore_list">';
				echo '<a href="handler.php?task=message&amp;subtask=ignore&amp;player='. $row['caption'] .'&amp;p='. $spacegame['page_number'] .'&amp;pp='. $spacegame['per_page'] .'">';
				echo $row['caption'];
				echo '</a>';
				echo '</li>';
			}

			echo '</ul>';
			echo '<div>';
		}

	?>
</div>
