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

	include_once('inc/common.php');
	include_once('inc/game.php');
	include_once('inc/places.php');
	
	do { // Dummy loop
		if ((!isset($_REQUEST['plid'])) || (!is_numeric($_REQUEST['plid'])))  {
			header('Location: viewport.php?rc=1040');
			die();
		}

		$place_id = $_REQUEST['plid'];

		if (!isset($spacegame['places'][$place_id])) {
			header('Location: viewport.php?rc=1040');
			die();
		}

		if ($spacegame['places'][$place_id]['type'] != 'Warp') {
			header('Location: viewport.php?rc=1040');
			die();
		}

		$spacegame['warp'] = array();

		$rs = $db->get_db()->query("select * from warps where place = '". $place_id ."' limit 1");
		
		$rs->data_seek(0);
		while ($row = $rs->fetch_assoc()) {
			$spacegame['warp'] = $row;
		}
	} while (false);