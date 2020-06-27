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

<!-- Need to sort goods into tables vertically instead of horizontally --> 

<div class="header2">Trade Goods</div>
<div class="docs_text">
	There are many goods available to trade. All ports spawn with a random
	selection of level 1 goods. Each planet may also demand just about
	anything.
</div>
<hr />
<div class="header3">List of Goods</div>
<div class="docs_text">
	<table width="100%">
	<caption hidden>List of Goods</caption>

	<?php
		$columns = 3;
		$column = 0;
		
		echo '<tr>';

		for ($i = 0; $i < $columns; $i++) {
			echo '<th scope="col" width="35"><strong>Lvl</strong></th>';
			echo '<th scope="col" width="195"><strong>Good Caption</strong></th>';
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

			echo '<a href="docs.php?page=good&amp;id='. $good_id .'">';

			if (strlen($good['caption']) >= 15) {
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