<?php
/**
 * Handles messaging stuff by passing it off.
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

	$return_page = 'message';


	do { // Dummy loop

		$request_subtask = $_REQUEST['subtask'];
		
		if (!isset($request_subtask)) {
			$return_codes[] = 1041;
		}

		switch ($request_subtask) {

			case 'admin':
			case 'player':
			case 'alliance':
			case 'subspace':
			case 'ignore':
			case 'hide':
			case 'delete':
			case 'read':

				$page = $request_subtask;
				$file = "hndl/sub/msg_{$page}.php";

				if (!file_exists($file)) {
					$return_codes[] = 1041;
					error_log(__FILE__ . '::' . __LINE__ . ' Valid subtask does not have an include.');
					break;
				}
				
				include_once($file);
				break;

			default:
				$return_codes[] = 1041;
				break;
 		}
	} while (false);

?>