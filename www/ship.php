<?php
/**
 * Popup page for ship information 
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
	include_once('inc/ships.php');

	function get_ship_link($page, $caption) {
		echo "<a href='ship.php?page={$page}'>{$caption}</a>";
	}

	$tmpl['no_fluff'] = true;
	$tmpl['page_title'] = 'Ship Information';

	include_once('tmpl/html_begin.php');

	$db = isset($db) ? $db : new DB;
?>
<div class="popup_spread">
	<div class="port_update_button">
		<a href="viewport.php" target="_top">
			<script type="text/javascript">drawButton('close', 'close', 'return true;');</script>
		</a>
	</div>
	<div class="header1">Ship Console</div>
	<div class="popup_menu">
		<?php include_once('tmpl/ship/menu.php'); ?>
	</div>
	<div class="popup_content">
		<?php
			
			$ship_page = 'main';
			$ship_file = "tmpl/ship/ship_{$ship_page}.php";
			
			if (isset($_REQUEST['page']) && preg_match('/^[_a-zA-Z0-9]{1,12}$/i', $_REQUEST['page']) > 0) {
				
				$ship_page = $_REQUEST['page'];
				$file = "tmpl/ship/ship_{$ship_page}.php";
					
				if (file_exists($file)) {
					$ship_file = $file;
				}
			}
			
			include_once($ship_file);
		?>
		<br class="clear" />
	</div>
	<br class="clear" />
</div>
	
<?php
	include_once('tmpl/html_end.php');
?>
