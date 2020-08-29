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

	if (!defined('SPACEGAME')) {
		error_log('Files in the inc directory may be included by authorized scripts only. This check is in: hndl/common.php');
		die('Unauthorized script access. An entry has been made in the error log file with more information.');
	}

	// This should be used in place of time() outside of events. Since this file
	// is included early it actions can be computed from delta page load, not 
	// after some other stuff has been running.
	define('PAGE_START_TIME', time());
	
	// This is used for page build later on.
	define('PAGE_START_OFFSET', -microtime(true));
	mt_srand(PAGE_START_TIME);

	include_once('inc/config.php');
	include_once('inc/db.php');
	include_once('inc/return_codes.php');
	include_once('inc/whitelist.php');

	function dump_r($dump = array()) {
		echo '<pre class="dump align_left">';
		
		if (is_array($dump)) {
			echo htmlentities(print_r($dump, true));
		}
		else {
			echo htmlentities($dump);
		}
		
		echo '</pre>';
	}

	function quit($dump = null) {
		
		if (is_null($dump)) {
			global $spacegame;
			dump_r($spacegame);
		}
		else {
			dump_r($dump);
		}
		
		die();
	}

	// Need to initialize this before session.php checks for CLI.
	// We should use this instead of just REMOTE_ADDR so that we 
	// can get the client IP address instead of proxies on client-
	// and server-side.
	function getClientIP() {
		if ((array_key_exists('HTTP_CLIENT_IP', $_SERVER)) && (filter_var($_SERVER["HTTP_CLIENT_IP"], FILTER_VALIDATE_IP))) {
			return $_SERVER["HTTP_CLIENT_IP"];
		}
		elseif ((array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) && (filter_var($_SERVER["HTTP_X_FORWARDED_FOR"], FILTER_VALIDATE_IP))) {
			return $_SERVER["HTTP_X_FORWARDED_FOR"];
		}
		elseif ((array_key_exists('REMOTE_ADDR', $_SERVER)) && (filter_var($_SERVER["REMOTE_ADDR"], FILTER_VALIDATE_IP))) {
			return $_SERVER["REMOTE_ADDR"];
		}
		else {
			return null;
		}
	}