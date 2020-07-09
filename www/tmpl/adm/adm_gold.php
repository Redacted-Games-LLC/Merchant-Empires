<?php
/**
 * Administration page for the gold membership keys.
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
	

	if (!get_user_field(USER_ID, 'admin', 'gold')) {
		header('Location: viewport.php?rc=1030');
		die();
	}

?>
	<div class="header2 header_bold">Gold Key Administration</div>
	<div class="docs_text">
		You can manipulate gold keys in the game using this page.
	</div>
	<hr />
	<div class="header3 header_bold">Insert Membership Keys</div>
	<div class="docs_text">
		Use this box to bulk insert membership keys to the database. WARNING: make
		sure the keys you insert are securely generated. The limit is 1000 keys per
		insert operation each separated by at least a line feed.
	</div>
	<div class="docs_text">
		<form action="handler.php" method="post">
			<textarea name="keys" rows="10" cols="72" maxlength="100000"></textarea> 
			<script type="text/javascript">drawButton('add', 'add', 'validate_add()');</script>
			<input type="hidden" name="task" value="gold" />
			<input type="hidden" name="subtask" value="insert" />
			<input type="hidden" name="return" value="admin" />
			<input type="hidden" name="form_id" value="<?php echo $_SESSION['form_id']; ?>" />
		</form>
	</div>