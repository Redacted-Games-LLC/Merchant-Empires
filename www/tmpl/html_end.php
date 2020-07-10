<?php
/**
 * Stuff to do at the end of an html page.
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
	
	if ((!isset($tmpl['no_fluff'])) && (defined('PAGE_START_OFFSET') && USER_ID > 0)) {
			echo '<div id="page_build" class="align_center">';
			echo 'Inflation <strong>';
			echo round(INFLATION, 1);
			echo '%</strong><br />';

			echo 'Page Build <strong>';
			printf ("%7.0f", 1000 * (PAGE_START_OFFSET + microtime(true)));
			echo ' ms</strong><br />';
			
			echo '</div>';
	} ?>
		</div>
		<footer>
			<!-- use <span> instead of <div> as we want license text on logo right, not logo bottom -->
			<span id="footer_left_column align_left"><img src="res/redacted_logo.png" width="280" alt="branding logo" /></span>
			<span id="footer_right_column align_right">Released under the Open Source <a href="docs.php?page=license" target="_blank">GNU GPLv3</a> License</span>

			<!-- required for footer background color -->
			&nbsp;<br />
			&nbsp;<br />
			&nbsp;<br />
			&nbsp;<br />
		</footer>
	</body>
</html>