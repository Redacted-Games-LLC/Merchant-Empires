<?php
/**
 * Navigation popup to help players find things.
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

	include_once('inc/page.php');
	include_once('inc/game.php');

	$places = array();

	$db = isset($db) ? $db : new DB;

	$rs = $db->get_db()->query("select places.*, place_types.caption as type_caption, (((x - ". $spacegame['player']['x'] .") * (x - ". $spacegame['player']['x'] .")) + ((y - ". $spacegame['player']['y'] .") * (y - ". $spacegame['player']['y'] ."))) as dist from places, place_types where places.type = place_types.record_id and place_types.caption in ('Warp', 'Ship Dealer', 'Tech Dealer') order by place_types.caption, dist");

	$rs->data_seek(0);

	while ($row = $rs->fetch_assoc()) {
		$places[$row['type_caption']][$row['record_id']] = $row;
	}

	$tmpl['page_title'] = 'Locate Place';
	$tmpl['no_fluff'] = true;

	include_once('tmpl/html_begin.php');

?>

<div class="popup_spread">
	<div class="port_update_button">
		<a href="viewport.php" target="_top">
			<script type="text/javascript">drawButton('close', 'close', 'return true;');</script>
		</a>
	</div>
	<div class="header2 header_bold">Locate Place</div>
	<div class="popup_text">
		Lost? The Imperial Government can help! The following places are
		sorted by distance (mouseover the links to see how far). When you
		click on a link an arrow will appear in your navigation panel you
		may follow to the destination. <strong>Each click will cost 
		<?php echo TARGET_TURN_COST; ?> turn(s).</strong>
	</div>
	<br />
	<?php
		foreach ($places as $caption => $list) {
			echo '<hr />';
			echo '<div class="header3 header_bold">';
			echo $caption;
			echo '</div>';

			echo '<div class="places_to_locate align_center">';
			
			foreach ($list as $id => $place) {
				echo '<div class="place_to_locate align_center">';
				echo $place['caption'];
				echo ' ('; 
				$dist = max(abs($place['x'] - $spacegame['player']['y']), abs($place['y'] - $spacegame['player']['y']));

				echo '<a href="handler.php?task=target&amp;x=' . $place['x'] . '&amp;y=' . $place['y'] . '&amp;type=1&amp;form_id='. $_SESSION['form_id'] .'"';
				echo 'title="' . $dist . ' Sector(s) away" target="_top">';
				echo $place['x'] . " " . $place['y'];
				echo '</a>';
			
				echo ')';
				echo '</div>';
			}
			
			echo '</div>';
		}
	?>
</div>

<?php
	include_once('tmpl/html_end.php');
?>