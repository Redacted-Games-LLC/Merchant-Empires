<?php
/**
 * Popup page for sending messages
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

	$tmpl['no_fluff'] = true;
	$tmpl['page_title'] = 'Communications';

	include_once('tmpl/html_begin.php');
	
	function get_msg_link($page, $caption) {
		echo "<a href='message.php?page={$page}'>{$caption}</a>";
	}

?>
<div class="popup_spread">
	<div class="port_update_button">
		<a href="viewport.php" target="_top">
			<script type="text/javascript">drawButton('close', 'close', 'return true;');</script>
		</a>
	</div>
	<div class="header1 header_bold">Communications Terminal</div>
	<div class="docs_text">
		
	</div>

	<div class="popup_menu">
		<?php include_once('tmpl/msg/menu.php'); ?>
	</div>

	<div class="popup_content">
		<?php
			
			$content_page = 'main';
			$content_file = "tmpl/msg/msg_{$content_page}.php";
			
			if (isset($_REQUEST['page']) && preg_match('/^[_a-zA-Z0-9]{1,12}$/i', $_REQUEST['page']) > 0) {
				
				$content_page = $_REQUEST['page'];
				if (in_array($content_page, $tmpl_msg_array)) {
					$file = "tmpl/msg/msg_{$content_page}.php";
					
					if (file_exists($file)) {
						$content_file = $file;
					}
				}
			}
			
			include_once($content_file);
		?>
		<br class="clear" />
	</div>
</div>
<?php
	include_once('tmpl/html_end.php');
?>