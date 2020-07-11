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
	include_once('inc/place_types.php');

	$doc_good = array();

	if (isset($_REQUEST['id']) && isset($spacegame['goods'][$_REQUEST['id']])) {

		$good_id = $_REQUEST['id'];

		$doc_good = $spacegame['goods'][$good_id];

		$db = isset($db) ? $db : new DB;

		$doc_starts = array();
		$doc_starts_count = 0;

		$rs = $db->get_db()->query("select * from start_goods where good = '$good_id' order by place_type, supply, record_id");
		
		$rs->data_seek(0);
				
		while ($row = $rs->fetch_assoc()) {
			$doc_starts[$row['record_id']] = $row;
			$doc_starts_count++;
		}

		$doc_requirements = array();
		$doc_requirements_count = 0;

		$doc_upgrades = array();
		$doc_upgrades_count = 0;

		$rs = $db->get_db()->query("select * from good_upgrades where good = '$good_id' or target = '$good_id' order by good, record_id");
		
		$rs->data_seek(0);
				
		while ($row = $rs->fetch_assoc()) {
			if ($row['good'] == $good_id) {
				$doc_upgrades[$row['target']] = $row;
				$doc_upgrades_count++;
			}
			elseif ($row['target'] == $good_id) {
				$doc_requirements[$row['good']] = $row;
				$doc_requirements_count++;
			}
		}
		
	}

?>
<div class="header2 header_bold"><a href="docs.php?page=goods">Good Information</a> :: <?php echo isset($doc_good['caption']) ? $doc_good['caption'] : 'No Good Selected'; ?></div>
<?php if (!isset($doc_good['caption'])) { ?>
	<div class="docs_text">
		You must select a good first. <a href="docs.php?page=goods">Click here</a> to go back.
	</div>
<?php } else { ?>
<div class="docs_goods align_center">
	<img src="res/goods/<?php echo $doc_good['safe_caption']; ?>.png" width="12" height="12" alt="doc good" />
	<img src="res/goods/<?php echo $doc_good['safe_caption']; ?>.png" width="20" height="20" alt="doc good" />
	<img src="res/goods/<?php echo $doc_good['safe_caption']; ?>.png" width="32" height="32" alt="doc good" />
	<img src="res/goods/<?php echo $doc_good['safe_caption']; ?>.png" width="48" height="48" alt="doc good" />
</div>
<div class="docs_text">
	<?php echo $doc_good['caption']; ?> is a level <?php echo $doc_good['level']; ?> good.
	For an upgrade of this good to be possible a port must sell at least one, but not all,
	of the following goods:
</div>
<div class="docs_text">
	<ul>
	<?php
		if ($doc_requirements_count > 0) {
			foreach ($doc_requirements as $id => $requirement) {
				
				echo '<li>';
				echo '<a href="docs.php?page=good&amp;id=' . $spacegame['goods'][$requirement['good']]['record_id'] . '">';
				echo '<img src="res/goods/'. $spacegame['goods'][$requirement['good']]['safe_caption'] .'.png" width="20" height="20" />';
				echo $spacegame['goods'][$requirement['good']]['caption'];
				echo '</a>';
				echo '</li>';
			}
		}
		else {
			echo '<li><em>No Requirements</em></li>';
		}
	?>
	</ul>
</div>
<div class="docs_text">
	Goods which may use this in an upgrade are:
</div>
<div class="docs_text">
	<ul>
	<?php
		if ($doc_upgrades_count > 0) {
			foreach ($doc_upgrades as $id => $upgrade) {
				
				echo '<li>';
				echo '<a href="docs.php?page=good&amp;id=' . $spacegame['goods'][$upgrade['target']]['record_id'] . '">';
				echo '<img src="res/goods/'. $spacegame['goods'][$upgrade['target']]['safe_caption'] .'.png" width="20" height="20" />';
				echo $spacegame['goods'][$upgrade['target']]['caption'];
				echo '</a>';
				echo '</li>';
			}
		}
		else {
			echo '<li><em>No Upgrades</em></li>';
		}
	?>
	</ul>
</div>
<hr />
<div class="header3 header_bold">Planetoid Availability</div>
<div class="docs_text">
	The following are the <em>weighted</em> chances a good will be in supply
	or demand. 
</div>
<div class="docs_text">
	<ul>
	<?php
		if ($doc_starts_count > 0) {
			foreach ($doc_starts as $id => $start) {
				
				echo '<li>';
				echo $start['percent'] . '%';
				echo '&nbsp;';

				echo $start['supply'] ? 'Supply' : 'Demand';
				echo '&nbsp;chance on a(n)&nbsp;';

				echo $spacegame['place_types'][$start['place_type']]['caption'];

				echo '</li>';
			}
		}
		else {
			echo '<li><em>No Starts</em></li>';
		}
	?>
</ul>
</div>
<?php 
	}
?>