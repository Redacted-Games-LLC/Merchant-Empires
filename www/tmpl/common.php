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
		error_log('Files in the inc directory may be included by authorized scripts only. This check is in: inc/common.php');
		die('Unauthorized script access. An entry has been made in the error log file with more information.');
	}

	if ((!isset($tmpl)) || (!is_array($tmpl))) {
		die('Set the $tmpl array before including template pages.');
	}