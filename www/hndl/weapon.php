<?php
/**
 * Handles weapon solution actions
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
	
	if (isset($_SESSION['form_id'])) {
		if (!isset($_REQUEST['form_id']) || $_SESSION['form_id'] != $_REQUEST['form_id']) {
			header('Location: viewport.php?rc=1181');
			die();
		}
	}

	do { // Dummy loop

		$return_vars['page'] = 'weapons';	

		switch ($_REQUEST['subtask']) {

			case 'add':
			case 'remove':
			case 'move':
			
				$sub_page = $_REQUEST['subtask'];
				
				if (in_array($sub_page, $hndl_sub_weapon_array)) {
					$sub_file = "hndl/sub/weapon_{$sub_page}.php";

					if (!file_exists($sub_file)) {
						$return_codes[] = 1041;
						error_log(__FILE__ . '::' . __LINE__ . ' Valid subtask does not have an include.');
						break;
					}
				
					include_once($sub_file);
					break;
				}
				else {
					$return_codes[] = 1041;
					break;
				}

			default:
				$return_codes[] = 1041;
				break;
 		}
	} while (false);

?>