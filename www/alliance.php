<?php
/**
 * Popup for the alliance pages
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
	include_once('inc/alliance.php');

	function get_alliance_link($page, $caption) {
		echo "<a href='alliance.php?page={$page}'>{$caption}</a>";
	}

	$tmpl['no_fluff'] = true;
	$tmpl['page_title'] = 'Alliance';

	include_once('tmpl/html_begin.php');

?>

<div class="popup_spread">
	<div class="port_update_button">
		<a href="viewport.php" target="_top">
			<script type="text/javascript">drawButton('close', 'close', 'return true;');</script>
		</a>
	</div>
	
	<div class="header1 header_bold"><?php
		echo 'War Desk';

		if ($spacegame['player']['alliance'] > 0) {
			echo ': ' . $spacegame['alliance']['caption'];
		}
	?></div>

	<div class="popup_menu">
		<?php include_once('tmpl/alliance/menu.php'); ?>
	</div>

	<div class="popup_content">
		<?php
			
			$alliance_page = 'main';
			$alliance_file = "tmpl/alliance/alliance_{$alliance_page}.php";
			
			if (isset($_REQUEST['page']) && preg_match('/^[_a-zA-Z0-9]{1,12}$/i', $_REQUEST['page']) > 0) {
				
				$alliance_page = $_REQUEST['page'];
				if (in_array($alliance_page, $tmpl_alliance_array)) {
					$file = "tmpl/alliance/alliance_{$alliance_page}.php";
					
					if (file_exists($file)) {
						$alliance_file = $file;
					}
				}
			}
			
			include_once($alliance_file);
		?>
		<br class="clear" />
	</div>
	<br class="clear" />
</div>

<?php	
	include_once('tmpl/html_end.php');
?>