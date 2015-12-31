<?php
/**
 * Popup page for scanning
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
	include_once('inc/scan.php');
	include_once('inc/alliances.php');
	include_once('inc/goods.php');

	$tmpl['no_fluff'] = true;
	$tmpl['page_title'] = 'Scan Sector';

	include_once('tmpl/html_begin.php');

	$db = isset($db) ? $db : new DB;
?>
<div class="popup_spread">
	<div class="port_update_button">
		<a href="viewport.php" target="_top">
			<script type="text/javascript">drawButton('close', 'close', 'return true;');</script>
		</a>
	</div>

	<div class="header1">Scan Sector</div>
	<hr />
	<?php if (isset($spacegame['scan'])) {
			if ($spacegame['scan_count'] <= 0) { 
	?>
				<div class="docs_text">
					There are no forces in that sector.
				</div>

	<?php
			} else { 

				foreach ($spacegame['scan'] as $record_id => $row) {
					echo '<img src="res/goods/' . $spacegame['goods'][$row['good']]['safe_caption'] . '.png" width="20" height="20" /> ';
					echo $row['amount'] . ' &quot;' . $spacegame['goods'][$row['good']]['caption'] . '&quot; ';
					echo 'belonging to <a href="alliance.php?page=player&amp;player_id='. $row['owner'] .'">' . $row['caption'] . '</a> ';
					echo $row['alliance'] > 0 ? ' of <a href="alliance.php?page=members&amp;alliance_id='. $row['alliance'] .'">' . $spacegame['alliances'][$row['alliance']]['caption'] . '</a>' : '(unallied)';
					echo '<br />';
				}

			}
	?>
	<?php } ?>
</div>

<?php
	include_once('tmpl/html_end.php');
?>