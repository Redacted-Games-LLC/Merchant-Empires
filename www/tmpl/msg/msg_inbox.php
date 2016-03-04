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

	if (isset($_REQUEST['all'])) {
		define('HIDDEN_MESSAGES', true);
	}
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
			echo 'This page shows you the messages you have received. You can hide or ';
			echo 'delete individual messages for ' . MSG_HIDE_DELETE_TURN_COST . ' turn';
			echo (MSG_HIDE_DELETE_TURN_COST == 1 ? ', ' : 's, ');
			echo 'or you can ignore all messages from a player for ' . PLAYER_MESSAGE_IGNORE_COST;
			echo ' turn' . (PLAYER_MESSAGE_IGNORE_COST == 1 ? '.' : 's.');

			if (defined('HIDDEN_MESSAGES')) {
				echo '<p>Showing all messages. ';
				echo '<a href="message.php?page=inbox">Click here</a>';
				echo ' to conceal hidden messages.</p>';
			}
			else {
				echo '<p>Concealing hidden messages. ';
				echo '<a href="message.php?page=inbox&amp;all=1">Click here</a>';
				echo ' to show all messages.</p>';
			}

			include_once('tmpl/msg/msg.php');
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
			echo 'Click on the player name to remove them from the ignore list. This action ';
			echo 'will cost ' . MSG_HIDE_DELETE_TURN_COST . ' turn';
			echo (MSG_HIDE_DELETE_TURN_COST == 1 ? '. ' : 's. ');
			echo '<br /><br />';

			echo '<div class="ingore_menu">';
			echo '<ul class="ignore_list">';

			foreach ($spacegame['ignore_list'] as $record_id => $row) {
				echo '<li class="ignore_list">';
				echo '<a href="handler.php?task=message&amp;subtask=ignore&amp;player='. $row['caption'] .'&amp;p='. $spacegame['page_number'] .'&amp;pp='. $spacegame['per_page'] .'&amp;form_id='. $_SESSION['form_id'] .'">';
				echo $row['caption'];
				echo '</a>';
				echo '</li>';
			}

			echo '</ul>';
			echo '<div>';
		}

	?>
</div>
