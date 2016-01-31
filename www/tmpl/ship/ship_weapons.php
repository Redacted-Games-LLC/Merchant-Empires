<?php
/**
 * Template page for ship weapon solutions
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
	include_once('inc/solutions.php');
?>
<div class="header2">Weapon Solutions</div>
<div class="docs_text">
	Weapon solutions allow for the installation of weapons on your ship. When you create a
	solution, weapons are installed and consumed from your cargo. For further info view the
	<a href="docs.php?page=weapons" target="_blank">documentation on weapons</a>.
</div>
<hr />
<div class="docs_text">
	<form action="handler.php" method="post">
	

		<script type="text/javascript">drawButton('add', 'add', 'validate_add()')</script>

		<input type="hidden" name="task" value="weapon" />
		<input type="hidden" name="subtask" value="add_solution" />
		<input type="hidden" name="" value="" />

	</form>
</div>




