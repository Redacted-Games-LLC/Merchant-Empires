<?php
/**
 * This is a catch-all file for viewport popups which produce a fatal error.
 *
 * Sometimes a player might try to load a port in a sector where there is no
 * port, or a dealer etc. It might not be intentional, maybe they hit move
 * after clicking a port but then the port loads. This is a redirect catch
 * for those situations.
 *
 * Note that a good game design wouldn't need this file. Search for callers
 * and eliminate them.
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
	
	$tmpl['page_title'] = 'Error';
	$tmpl['no_fluff'] = true;

	include_once('tmpl/html_begin.php');

?>
<div class="popup_spread">
	<div class="header1 header_bold">Error</div>
	<hr />
	<div class="docs_text">
		You have reached this page because something you did generated
		an error. The message(s) should have popped up.
	</div>
	<div class="docs_text">
		You can <a href="viewport.php" target="_top">click here</a> to
		get back to the viewport.
	</div>
</div>	
<?php
	include_once('tmpl/html_end.php');
?>