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

	include_once('inc/page.php');

	if (USER_ID <= 0) {
		header('Location: login.php?rc=1029');
		die();
	}
	
	if (!get_user_field(USER_ID, 'admin')) {
		if (PLAYER_ID <= 0) {
			header('Location: select_player.php?rc=1030');
		}
		else {
			header('Location: viewport.php?rc=1030');
		}
		die();
	}

	function get_admin_link($page, $caption, $access) {
		if (get_user_field(USER_ID, 'admin', $access)) {
			echo "<a href='admin.php?page={$page}'>{$caption}</a>";
		}
		else {
			echo "{$caption} <img src='res/locked.png' alt='Locked' title='Locked' width='14' height='14' />";
		}
	}

	$tmpl['page_title'] = 'Administration Pages';

	include_once('tmpl/html_begin.php');
?>

<div class="full_spread">
	<div class="docs_menu">
		<?php include_once('tmpl/adm/menu.php'); ?>
	</div>

	<div class="docs_content">
		<?php
			
			$adm_page = 'main';
			$adm_file = "tmpl/adm/adm_{$adm_page}.php";
			
			if (isset($_REQUEST['page']) && preg_match('/^[a-zA-Z0-9]{1,10}$/i', $_REQUEST['page']) > 0) {
				
				$adm_page = $_REQUEST['page'];
				$file = "tmpl/adm/adm_{$adm_page}.php";
					
				if (file_exists($file)) {
					$adm_file = $file;
				}
			}
			
			include_once($adm_file);
		?>
		<br class="clear" />
	</div>
	<br class="clear" />
</div>


<?php
	include_once('tmpl/html_end.php');
?>