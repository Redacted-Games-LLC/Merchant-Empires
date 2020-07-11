<?php
/**
 * Unsupported browser landing page.
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

	$tmpl['page_title'] = 'Unsupported Browser';
	$tmpl['no_fluff'] = true;

	include_once('tmpl/html_begin.php');

?>
<div class="popup_spread">
	<div class="header1 header_bold">We're Sorry</div>
	<hr />
	<div class="docs_text">
		You have reached this page because Internet Explorer 11 is currently not supported.
	</div>
</div>	
<?php
	include_once('tmpl/html_end.php');
?>