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

	include_once('tmpl/common.php');
?>
<div class="header2">Create Alliance</div>

<?php if ($spacegame['player']['alliance'] > 0) { ?>

	<div class="docs_text">
		You are already in an alliance and must leave it before creating a new one.
	</div>

<?php } elseif ($spacegame['player']['level'] < ALLIANCE_CREATION_LEVEL) { ?>

	<div class="docs_text">
		You must reach Level <?php echo ALLIANCE_CREATION_LEVEL; ?> before you can
		create an alliance.
	</div>

<?php } else { ?>

	<div class="docs_text">
		Alliance names can be up to 24 characters long with letters and numbers.
		You may use spaces but not at the beginning or end and only one at a time.
	</div>
	<div class="docs_text">
		<form action="handler.php" method="post">
			<label class="ship_form_label" for="alliance_name">Alliance Name:</label>
			<input class="ship_form_input" type="text" name="name" id="alliance_name" maxlength="24" size="30" />

			<script type="text/javascript">drawButton('create', 'create', 'validate_create()');</script>
			<input type="hidden" name="task" value="alliance" />
			<input type="hidden" name="subtask" value="create" />
			<input type="hidden" name="return" value="alliance" />
		</form>
	</div>

<?php } ?>
