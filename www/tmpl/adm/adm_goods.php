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
	include_once('inc/goods.php');
	include_once('inc/good_upgrades.php');

?>
<div class="header2">Goods Administration</div>
<div class="docs_text">
	You can manipulate existing goods or create new ones using this tool.
</div>
<hr />
<div class="header3">List of Goods</div>
<div class="docs_text">
	<table width="100%">
	<?php
		$columns = 3;
		$column = 0;
		
		echo '<tr>';

		for ($i = 0; $i < $columns; $i++) {
			echo '<td width="35"><strong>Lvl</strong></td>';
			echo '<td width="195"><strong>Good Caption</strong></td>';
		}

		foreach ($spacegame['goods'] as $good_id => $good) {

			if ($column <= 0) {
				echo '</tr>';
				echo '<tr>';
				$column = $columns;
			}
			
			$column -= 1;

			echo '<td><em>' . $good['level'] . '</em></td>';

			echo '<td>';

			echo '<img src="res/goods/' . $good['safe_caption'] . '.png" width="20" height="20" />';

			echo '&nbsp;&nbsp;';

			echo '<a href="admin.php?page=good&amp;id='. $good_id .'">';

			if (strlen($good['caption']) > 15) {
				echo substr($good['caption'], 0, 12) . '...';
			}
			else {
				echo $good['caption'];
			}
			echo '</a>';

			echo '</td>';
			
			
		}

		echo '</tr>';
	?>
	</table>
</div>
<hr />
<div class="header3">Goods Which Do not Upgrade</div>
<div class="docs_text">
	<table width="100%">
	<?php
		$column = 0;
		
		echo '<tr>';

		for ($i = 0; $i < $columns; $i++) {
			echo '<td width="35"><strong>Lvl</strong></td>';
			echo '<td width="195"><strong>Good Caption</strong></td>';
		}

		foreach ($spacegame['goods'] as $good_id => $good) {

			if ($good['target_count'] > 0) {
				continue;
			}

			if ($column <= 0) {
				echo '</tr>';
				echo '<tr>';
				$column = $columns;
			}
			
			$column -= 1;

			echo '<td><em>' . $good['level'] . '</em></td>';

			echo '<td>';

			echo '<img src="res/goods/' . $good['safe_caption'] . '.png" width="20" height="20" />';

			echo '&nbsp;&nbsp;';

			echo '<a href="admin.php?page=good&amp;id='. $good_id .'">';

			if (strlen($good['caption']) > 15) {
				echo substr($good['caption'], 0, 12) . '...';
			}
			else {
				echo $good['caption'];
			}
			echo '</a>';

			echo '</td>';
			
			
		}

		echo '</tr>';
	?>
	</table>

</div>
<hr />
<div class="header3">Goods With No Sources</div>
<div class="docs_text">
	<table width="100%">
	<?php
		$column = 0;
		
		echo '<tr>';

		for ($i = 0; $i < $columns; $i++) {
			echo '<td width="35"><strong>Lvl</strong></td>';
			echo '<td width="195"><strong>Good Caption</strong></td>';
		}

		foreach ($spacegame['goods'] as $good_id => $good) {

			if ($good['source_count'] > 0) {
				continue;
			}

			if ($column <= 0) {
				echo '</tr>';
				echo '<tr>';
				$column = $columns;
			}
			
			$column -= 1;

			echo '<td><em>' . $good['level'] . '</em></td>';

			echo '<td>';

			echo '<img src="res/goods/' . $good['safe_caption'] . '.png" width="20" height="20" />';

			echo '&nbsp;&nbsp;';

			echo '<a href="admin.php?page=good&amp;id='. $good_id .'">';

			if (strlen($good['caption']) > 15) {
				echo substr($good['caption'], 0, 12) . '...';
			}
			else {
				echo $good['caption'];
			}
			echo '</a>';

			echo '</td>';
			
			
		}

		echo '</tr>';

	?>
	</table>

</div>
<hr />
<div class="header3">Create New Good</div>
<div class="docs_text">
	Enter the information below to create a new good.
</div>
<div class="docs_text">
	<form action="handler.php" method="post">
		<label for="new_name">Name:</label>
		<input id="new_name" name="name" type="text" maxlength="12" size="13" />
		&nbsp;&nbsp;&nbsp;
		<label>Level:</label>
		<script type="text/javascript">draw_number_list('level')</script>
		&nbsp;&nbsp;&nbsp;
		<script type="text/javascript">drawButton('create', 'create', 'validate_create_good()')</script>
		<input type="hidden" name="task" value="good" />
		<input type="hidden" name="subtask" value="create" />
	</form>
</div>

