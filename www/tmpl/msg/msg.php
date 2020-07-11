<?php
/**
 * Handles looping through and displaying messages.
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

	do { // Dummy loop

		$ignore_counter = 0;

		foreach ($spacegame['messages'] as $record_id => $message) {

			if ($message['type'] != 4) {
				if (defined('SENT_MSG_TYPE')) {
					if (isset($spacegame['ignore_index'][$message['id']])) {
						$ignore_counter += 1;
						continue;
					}
				} else {
					if (isset($spacegame['ignore_index'][$message['sender']])) {
						$ignore_counter += 1;
						continue;
					}
				}
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
					$msg_type = 'Official Message';
					$msg_box = 'official_message';
					$msg_head = 'official_message_head';
					break;

				case 1:
					$msg_type = 'Message';
					break;

				case 2:
					$msg_type = 'Alliance Message';

					if ($message['id'] > 0 && $message['id'] == $spacegame['player']['alliance']) {
						$msg_box = 'friendly_alliance_message';
						$msg_head = 'friendly_alliance_message_head';	
					}
					else {
						$msg_box = 'hostile_alliance_message';
						$msg_head = 'hostile_alliance_message_head';
					}

					
					break;

				case 3:
					$msg_type = 'Subspace Broadcast';
					$msg_box = 'subspace_message';
					$msg_head = 'subspace_message_head';
					break;

				case 4:
					$msg_type = 'Battle Report';
					$msg_box = 'battle_message';
					$msg_head = 'battle_message_head';
					break;

			}


			echo '<div class="' . $msg_box . '">';
				echo '<div class="'. $msg_head .'">';

					if (!defined('SENT_MSG_TYPE')) {

						echo '<div class="message_actions">';

							if ($message['sender'] > 0) {
								echo '<a href="message.php?page=player&amp;name='. $spacegame['message_senders'][$message['sender']] . '">';
								echo 'Reply';
								echo '</a>';
								echo '&nbsp;&nbsp;&nbsp;&nbsp;';
							}

							echo '<a href="handler.php?task=message&amp;subtask=hide&amp;message='. $message['message_id'] .'&amp;p='. $spacegame['page_number'] .'&amp;pp='. $spacegame['per_page'] .'&amp;form_id='. $_SESSION['form_id'] . (defined('HIDDEN_MESSAGES') ? '&amp;all=1' : '')  .'">';

							if ($message['hidden'] > 0) {
								echo 'Unhide';
							}
							else {
								echo 'Hide';
							}

							echo '</a>';
							echo '&nbsp;&nbsp;&nbsp;&nbsp;';
							echo '<a href="handler.php?task=message&amp;subtask=delete&amp;message='. $message['message_id'] .'&amp;p='. $spacegame['page_number'] .'&amp;pp='. $spacegame['per_page'] .'&amp;form_id='. $_SESSION['form_id'] .'">Delete</a>';

							if ($message['sender'] > 0) {
								echo '&nbsp;&nbsp;&nbsp;&nbsp;';
								echo '<a href="handler.php?task=message&amp;subtask=ignore&amp;player='. $spacegame['message_senders'][$message['sender']] .'&amp;p='. $spacegame['page_number'] .'&amp;pp='. $spacegame['per_page'] .'&amp;form_id='. $_SESSION['form_id'] .'">Ignore</a>';
							}
							
						echo '</div>';
					}

					echo $msg_type;

					if (defined('SENT_MSG_TYPE')) {
						if ($message['type'] != 3 && $message['id'] > 0) {
							echo ' to ';
							echo '<a href="alliance.php?page=player&amp;player_id=' . $message['id'] . '">';
							echo $spacegame['message_receivers'][$message['id']];
							echo '</a>';
						}
					}
					else {
						if ($message['sender'] > 0) {
							echo ' from ';
							echo '<a href="alliance.php?page=player&amp;player_id=' . $message['sender'] . '">';
							echo $spacegame['message_senders'][$message['sender']];
							echo '</a>';
						}
					}

				echo '</div>';
				echo '<div class="message_text">';
					if ($message['type'] != 4) {
						echo htmlentities($message['message']);
					}
					else {
						echo $message['message'];
					}
				echo '</div>';
				echo '<div class="message_posted align_right">';
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
		echo '<script type="text/Javascript">load_pagination(';
		echo $spacegame['page_number'] .', '. $spacegame['max_pages'] .',';
		echo '"message.php?page=inbox&';

		if (defined('HIDDEN_MESSAGES')) {
			echo 'all=1&';
		}

		echo 'pp='. $spacegame['per_page'] .'"';
		echo ')</script>';

	} while (false);


?>