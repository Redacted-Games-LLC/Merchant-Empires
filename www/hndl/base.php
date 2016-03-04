<?php
/**
 * Handles base specific stuff by passing it down.
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

	if (isset($_SESSION['form_id'])) {
		if (!isset($_REQUEST['form_id']) || $_SESSION['form_id'] != $_REQUEST['form_id']) {
			header('Location: viewport.php?rc=1181');
			die();
		}
	}

	$return_page = 'viewport';


	do { // Dummy loop

		switch ($_REQUEST['subtask']) {

			case 'move':
			case 'land':
			case 'build':
			case 'learn':

				include_once('inc/base.php');

				if (!isset($spacegame['base'])) {
					$return_page = 'error';
					$return_codes[] = 1114;
					break;
				}

				$return_vars['plid'] = $spacegame['base']['place'];
				$return_vars['base'] = '1';

	
				$sub_page = $_REQUEST['subtask'];
				$sub_file = "hndl/sub/base_{$sub_page}.php";

				if (!file_exists($sub_file)) {
					$return_codes[] = 1041;
					error_log(__FILE__ . '::' . __LINE__ . ' Valid subtask does not have an include.');
					break;
				}
				
				include_once($sub_file);
				break;

			default:
				$return_codes[] = 1041;
				break;
 		}
	} while (false);


?>