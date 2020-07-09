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
	
	define("TABLEHEADER_LVL", '<th class="lvlHeader"><strong>Lvl</strong></th>');
	define("TABLEHEADER_GOODCAPTION", '<th class="captionHeader"><strong>Good Caption</strong></th>');
	define("DIV_OPEN", '<div>');
	define("DIV_CLOSE", '</div>');
	define("TBL_OPEN", '<table>');
	define("TBL_CLOSE", '</table>');
	define("DIV_GOODLEVEL_OPEN", '<div class="goodLevel">');
	define("DIV_GOODCAPTION_OPEN", '<div class="goodCaption">');
	
	function print_goods($goods_list) {
		
		foreach ($goods_list as $good_id => $good) {
			
			echo DIV_OPEN;
			
			echo DIV_GOODLEVEL_OPEN;
			echo '<em>' . $good['level'] . '</em>';
			echo DIV_CLOSE;
			
			echo DIV_GOODCAPTION_OPEN;
			echo '<img src="res/goods/' . $good['safe_caption'] . '.png" width="20" height="20" />';
			echo '&nbsp;&nbsp;';
			echo '<a href="admin.php?page=good&amp;id='. $good_id .'">';
			$good_caption = $good['caption'];
			if (strlen($good_caption) >= 20) {
				echo substr($good_caption, 0, 17) . '...';
			}
			else {
				echo $good_caption;
			}
			echo '</a>';
			echo DIV_CLOSE;
			
			echo DIV_CLOSE;
		}
	}

?>

<div class="header2 header_bold">Trade Goods</div>
<div class="docs_text">
	There are many goods available to trade. All ports spawn with a random
	selection of level 1 goods. Each planet may also demand just about
	anything.
</div>
<hr />
<div class="header3 header_bold">List of Goods</div>
<br />
<div>

	<?php
		
		$columns = 3;
		echo TBL_OPEN;
		echo '<caption hidden>List of Goods</caption>';
		for ($i = 0; $i < $columns; $i++) {
			echo TABLEHEADER_LVL;
			echo TABLEHEADER_GOODCAPTION;
		}
		echo TBL_CLOSE;

		echo '<div class="good goodContainer1">';
		
		print_goods($spacegame['goods']);
		
		echo DIV_CLOSE;

	?>
</div>