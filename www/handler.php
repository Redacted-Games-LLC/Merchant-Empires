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

	include_once('inc/config.php');
	

	$return_page = 'viewport';
	
	if (isset($_REQUEST['return'])) {
		if (preg_match('/^[_a-zA-Z0-9]{1,15}$/', $_REQUEST['return'])) {
			$return_page = $_REQUEST['return'];
		}
		else {
			//TODO: Log malformed return link as fixit item.
		}
	} else {
		// TODO: Log missing return link as a fixit item.
	}

	$return_codes = array();
	$return_vars = array();
	
	if (preg_match('/^[_a-zA-Z0-9]{1,15}$/', $_REQUEST['task'])) {

		$task = $_REQUEST['task'];
	
		if (!include_once("hndl/{$task}.php")) {
			error_log("Someone at {$_SERVER['REMOTE_ADDR']} attempted to access an invalid form handler.");
			$return_codes[] = 1010;
		}

		if (!is_array($return_codes)) {
			error_log("Task {$task} malformed the return_codes array. Fix it!");
		}
	}
	else {
		$return_codes[] = 1001;
	}


	
	$return_code_list = implode($return_codes, ',');
	$return_var_list = http_build_query($return_vars);
	header("Location: {$return_page}.php?rc={$return_code_list}&{$return_var_list}");
	die();
?>