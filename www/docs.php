<?php
/**
 * Primary entry page for game documentation.
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

	$tmpl['page_title'] = 'Documentation';
	
	include_once('tmpl/html_begin.php');

?>

<div class="full_spread">
	<div class="docs_menu">
		<?php include_once('tmpl/doc/menu.php'); ?>
	</div>

	<div class="docs_content">
		<?php
			
			$doc_page = 'main';
			$doc_file = "tmpl/doc/doc_{$doc_page}.php";
			
			if (isset($_REQUEST['page']) && preg_match('/^[_a-zA-Z0-9]{1,18}$/i', $_REQUEST['page']) > 0) {
				
				$doc_page = $_REQUEST['page'];
				if (in_array($doc_page, $tmpl_doc_array)) {
					$file = "tmpl/doc/doc_{$doc_page}.php";
					
					if (file_exists($file)) {
						$doc_file = $file;
					}
				}
				else {
					$doc_file = "tmpl/doc/missing.php";
				}
			}
			
			include_once($doc_file);
		?>
		<br class="clear" />
	</div>
	<br class="clear" />
</div>
	
<?php	
	include_once('tmpl/html_end.php');
?>