<?php
/**
 * Common code for pagination stuff
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

	include_once('hndl/common.php');
	
	do { // Dummy Loop

		if (isset($_GET['p']) && is_numeric($_GET['p']) && $_GET['p'] > 0) {
			$spacegame['page_number'] = $_GET['p'];
		}
		else {
			$spacegame['page_number'] = 0;
		}

		if (isset($_GET['pp']) && is_numeric($_GET['pp']) && $_GET['pp'] > 0) {
			$spacegame['per_page'] = $_GET['pp'];

			if ($spacegame['per_page'] < MIN_PER_PAGE) {
				$spacegame['per_page'] = MIN_PER_PAGE;
			}
			
			if ($spacegame['per_page'] > MAX_PER_PAGE) {
				$spacegame['per_page'] = MAX_PER_PAGE;
			}
		}
		else {
			$spacegame['per_page'] = ceil((MAX_PER_PAGE - MIN_PER_PAGE) / 2);
		}

		
		

		
	
	} while (false);


?>