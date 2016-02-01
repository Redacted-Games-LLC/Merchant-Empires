<?php
/**
 * Loads search results
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

	include_once('inc/common.php');
	
	if (!get_user_field(USER_ID, 'admin', 'users')) {
		header('Location: viewport.php?rc=1030');
		die();
	}

	do { // Dummy Loop

		include_once('inc/pagination.php');

		if ($spacegame['page_number'] <= 0) {
			$spacegame['page_number'] = 1;
		}

		$db = isset($db) ? $db : new DB;

		if (isset($_REQUEST['search']) && validate_username($_REQUEST['search'])) {
			$spacegame['search_results'] = array();
			$spacegame['search_result_count'] = 0;

			$rs = $db->get_db()->query("select username FROM users WHERE lower(username) LIKE '%". $_REQUEST['search'] ."%' ORDER BY lower(username) limit ". (($spacegame['page_number'] - 1) * $spacegame['per_page']) . "," . $spacegame['per_page']);

			$rs->data_seek(0);

			while ($row = $rs->fetch_assoc()) {
				$spacegame['search_results'][] = $row['username'];
				$spacegame['search_result_count']++;
			}
		}
		else {

			$spacegame['users'] = array();
			$spacegame['user_count'] = 0;


			$rs = $db->get_db()->query("select SQL_CALC_FOUND_ROWS username from users order by username limit ". (($spacegame['page_number'] - 1) * $spacegame['per_page']) . "," . $spacegame['per_page']);

			$total_count = $db->found_rows();

			$rs->data_seek(0);

			while ($row = $rs->fetch_assoc()) {
				$spacegame['users'][] = $row['username'];
				$spacegame['user_count']++;
			}

			$spacegame['max_pages'] = ceil($total_count / $spacegame['per_page']);
		}
	
	} while (false);


?>