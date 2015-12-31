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

	include_once('inc/game.php');
	include_once('inc/places.php');
	include_once('inc/systems.php');
	
	do { // Dummy loop
		if ((!isset($_REQUEST['plid'])) || (!is_numeric($_REQUEST['plid'])))  {
			header('Location: error.php?rc=1019');
			die();
		}

		$place_id = $_REQUEST['plid'];

		if (!isset($spacegame['places'][$place_id])) {
			header('Location: error.php?rc=1019');
			die();
		}

		switch ($spacegame['places'][$place_id]['type']) {

			case 'Tech Dealer':
			case 'Ship Dealer':
			case 'Solar Collector':
			case 'Employment':
				break;

			default:
				header('Location: error.php?rc=1019');
				die();
		}


		$spacegame['inventory'] = array();
		$spacegame['inventory_groups'] = array();
		$spacegame['inventory_count'] = 0;

		$db = isset($db) ? $db : new DB;

		$rs = $db->get_db()->query("select dealer_inventory.record_id as id, item, item_type, price, stock, item_types.caption as type_caption from dealer_inventory, item_types where item_types.record_id = dealer_inventory.item_type and place = '" . $place_id . "' order by item_type, item");
		
		$rs->data_seek(0);
		while ($row = $rs->fetch_assoc()) {
			$row['final_price'] = round($row['price'] * INFLATION_MULTIPLIER * $spacegame['tax_multiplier']);

			$spacegame['inventory'][$row['id']] = $row;
			$spacegame['inventory_groups'][$row['item_type']][] = $row['id'];
			$spacegame['inventory_count'] += 1;
		}

		foreach ($spacegame['inventory'] as $id => &$item_ref) {
			$item_ref['details'] = array();

			switch ($item_ref['type_caption']) {
				case 'Ships':
				case 'Goods':
				case 'People':
					$table = strtolower($item_ref['type_caption']);
					break;

				default:
					$return_codes[] = 1020;
					break 3;
			}

			$rs = $db->get_db()->query("select * from $table where record_id = '". $item_ref['item'] ."' limit 1");
			
			$rs->data_seek(0);
			if ($row = $rs->fetch_assoc()) {
				if (isset($row['caption'])) {
					$row['safe_caption'] = strtolower(str_replace(' ', '_', $row['caption']));
				}
				
				$item_ref['details'] = $row;
			}
		}
		
	} while (false);

?>