<?php
/**
 * Entry template page for alliance information
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
<div class="header2">Ship Status</div>
<hr />
<div class="header3">Ship Name</div>
<div class="docs_text">
	Your ship name can contain 2 to 12 characters using letters, numbers, and underscore only,
	unless you have an active <a href="docs.php?page=gold" target="_blank">Gold Membership</a>.
</div>
<div class="docs_text">
	<form action="handler.php" method="post">
		<label for="ship_name"><small>Ship Name</small></label><br />
		<input class="ship_form_input" type="text" id="ship_name" name="ship_name" maxlength="12" value="<?php echo $spacegame['player']['ship_name']; ?>" /><br />
		<br />

<!-- Non-working code. Keep as gold benefit.
		<label for="ship_style"><small>Ship Style</small></label><br />
		<input class="ship_form_input" type="text" id="ship_style" name="ship_style" maxlength="80" size="40" /><br />
		
		<br />
-->
		<script type="text/javascript">drawButton('rename_ship', 'update', 'validate_rename_ship()');</script>
		<input type="hidden" name="task" value="ship" />
		<input type="hidden" name="subtask" value="rename" />
		<input type="hidden" name="return" value="ship" />
		<input type="hidden" name="form_id" value="<?php echo $_SESSION['form_id']; ?>" />
	</form>
</div>

<!-- Non-working code. Keep as gold benefit.
	<div class="docs_text">
		Style strings are made up of decoration tags which are used to decorate the ship name.
		Each tag performs the operation for the specified number of characters. If the rules run
		out of characters the rest are ignored. If the rules run out before the end of the name
		the rest of the characters are dumped with no decoration.
	</div>
	<div class="docs_text">
		Here are some example tags:
	</div>
	<div class="docs_text">
		<ul>
			<li><strong>1#ccc;</strong> - Color one character grey (note only 3 char css hex colors are supported).</li>
			<li><strong>4#ccc#ccc;</strong> - Color 4 characters with a css3 gradient.</li>
			<li><strong>3x;</strong> - Just dump 3 characters with no decoration.</li>
			<li><strong>5b;</strong> - Bold 5 characters, can do 'i' and 'u' as well.</li>
			<li><strong>b;</strong> - Toggle bold without moving pointer, can do 'i' and 'u'</li>
		</ul>	
	</div>
	<div class="docs_text">
		The maximum length of a style string is 80 characters which is enough to style the 
		gaudiest ship name in the galaxy.
	</div>
-->