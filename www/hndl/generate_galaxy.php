<?php
/**
 * Performs the task of generating the galaxy.
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
	include_once('inc/galaxy.php');

	$return_page = 'admin';
	$return_vars['page'] = 'system';

	if (!get_user_field(USER_ID, 'admin', 'system')) {
		header('Location: viewport.php?rc=1030');
		die();
	}

	do { /* Dummy loop for "break" support. */

		$seed = PAGE_START_TIME;

		if (isset($_REQUEST['seed']) && is_numeric($_REQUEST['seed'])) {
			$seed = $_REQUEST['seed'];
		}

		set_user_field(USER_ID, 'admin', 'system_seed', $seed);

		$db = isset($db) ? $db : new DB;

		if (!($st = $db->get_db()->prepare("update players set alliance = null where record_id > 0"))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
			break;
		}


		$tables = array(
			'user_players',
			'alliance_invitations',
			'messages',
			'port_goods',
			'dealer_inventory',
			'places',
			'ordnance',
			'systems',
			'player_cargo',
			'player_log',
			'alliances',
			'players',
			'warps'
		);

		foreach ($tables as $table) {
			if (!($st = $db->get_db()->prepare("delete from `$table`"))) {
				error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				$return_codes[] = 1006;
				break;
			}
			
			if (!$st->execute()) {
				$return_codes[] = 1006;
				error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
				break;
			}

			if (!($st = $db->get_db()->prepare("ALTER TABLE `$table` AUTO_INCREMENT = 1"))) {
				error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				$return_codes[] = 1006;
				break;
			}
			
			if (!$st->execute()) {
				$return_codes[] = 1006;
				error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
				break;
			}
		}


		$race_list = array();
		$race_count = 0;
		
		$rs = $db->get_db()->query("select record_id, caption from races order by record_id");
		
		$rs->data_seek(0);
		while ($row = $rs->fetch_assoc()) {
			$race_list[$row['record_id']] = substr($row['caption'], 0, 4);
			$race_count++;
		}

		$time = PAGE_START_TIME;

		$number = 100;
		
		$star_count = 0;
		$stars = build_star_map(GALAXY_SIZE, $seed, $star_count);
		$system_ids = array();

		for ($star_i = 0; $star_i < $star_count; $star_i++) {

			$star = $stars[$star_i];

			$race = $star['race'];
			$x = 500 + $star['x'];
			$y = 500 + $star['y'];
			$protected = $star['protected'] ? 1 : 0;
			$radius = $star['size'];

			$name = $star['protected'] ? $star['name'] : $race_list[$star['race']] . ' ' . $number;
			$number += mt_rand(1, 3);

			$stars[$star_i]['name'] = $name;

			if (!($st = $db->get_db()->prepare("insert into systems (caption, x, y, radius, protected, race) values (?,?,?,?,?,?)"))) {
				error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				$return_codes[] = 1006;
				break;
			}

			$st->bind_param("siiiii", $name, $x, $y, $radius, $protected, $race);
			
			if (!$st->execute()) {
				$return_codes[] = 1006;
				error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
				break;
			}

			$system_id = $db->last_insert_id('systems');

			$stars[$star_i]['system_id'] = $system_id;

			$place_types = array();

			$rs = $db->get_db()->query('select * from place_types');
		
			$rs->data_seek(0);
			while ($row = $rs->fetch_assoc()) {
				$place_types[$row['caption']] = $row['record_id'];
			}

			$item_types = array();

			$rs = $db->get_db()->query('select * from item_types');
		
			$rs->data_seek(0);
			while ($row = $rs->fetch_assoc()) {
				$item_types[$row['caption']] = $row;
			}

			$goods = array();

			$rs = $db->get_db()->query('select * from goods where level <= 1');
		
			$rs->data_seek(0);
			while ($row = $rs->fetch_assoc()) {
				$goods[$row['caption']] = $row;
			}

			$ships = array();

			$rs = $db->get_db()->query('select * from ships order by caption, rank');

			$rs->data_seek(0);
			while ($row = $rs->fetch_assoc()) {
				$ships[$row['race']][$row['record_id']] = $row;
			}



			$place_candidates = array();
			$place_candidate_count = 0;

			$letters = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
			$star_pos = 0;
			$planet_pos = 4;

			// Add stars and planets
			$system_grid = generate_system($star);
			
			foreach ($system_grid as $px => $y_grid) {
				foreach ($y_grid as $py => $place) {

					$x = $px + 500 + $star['x'];
					$y = $py + 500 + $star['y'];

					$type = $place['type'];

					$planet_name = $name . ' ';

					if ($type == 'Star') {
						$planet_name .= substr($letters, $star_pos, 1);
						$star_pos++;
					}
					else {
						$planet_name .= substr($letters, $planet_pos, 1);
						$planet_pos++;
					}

					if (!($st = $db->get_db()->prepare("insert into places (caption, system, x, y, type) values (?,?,?,?,?)"))) {
						error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
						$return_codes[] = 1006;
						break 3;
					}

					$st->bind_param("siiii", $planet_name, $system_id, $x, $y, $place_types[$type]);
					
					if (!$st->execute()) {
						$return_codes[] = 1006;
						error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
						break 3;
					}

					if ($protected) {
						if ($type == 'Star') {
							// Add free solar collector

							$dealer_name = $name . ' Energy';

							if (!($st = $db->get_db()->prepare("insert into places (caption, system, x, y, type) values (?,?,?,?,?)"))) {
								error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
								$return_codes[] = 1006;
								break 3;
							}

							$st->bind_param("siiii", $dealer_name, $system_id, $x, $y, $place_types['Solar Collector']);
							
							if (!$st->execute()) {
								$return_codes[] = 1006;
								error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
								break 3;
							}

							$dealer_id = $db->last_insert_id('places');
						
							if (!($st = $db->get_db()->prepare("insert into dealer_inventory (place, item_type, item, stock, price, last_update) values (?,?,?,?,?,?)"))) {
								error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
								$return_codes[] = 1006;
								break 3;
							}

							$st->bind_param("iiiiii", $dealer_id, $item_types['Goods']['record_id'], $goods['Energy']['record_id'], $item_types['Goods']['max_stock'], $goods['Energy']['level'], $time);
							
							if (!$st->execute()) {
								$return_codes[] = 1006;
								error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
								break 3;
							}

						}
						else {
							$place_candidates[] = array('x' => $x, 'y' => $y);
							$place_candidate_count++;
						}
					}
				}
			}

			if (!$protected) {
				// Everything after this point is for protected space.
				continue;
			}

			if ($place_candidate_count <= 0) {
				// No place to add facilities. Sorry.
				continue;
			}

			$dealer_name = $name . ' Ships';

			$place_candidate = $place_candidates[mt_rand(0, $place_candidate_count - 1)];

			$x = $place_candidate['x'];
			$y = $place_candidate['y'];

			if (!($st = $db->get_db()->prepare("insert into places (caption, system, x, y, type) values (?,?,?,?,?)"))) {
				error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				$return_codes[] = 1006;
				break 3;
			}

			$st->bind_param("siiii", $dealer_name, $system_id, $x, $y, $place_types['Ship Dealer']);
			
			if (!$st->execute()) {
				$return_codes[] = 1006;
				error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				break 3;
			}

			$dealer_id = $db->last_insert_id('places');
		

			// Prepared query is reused for a few things below it

			if (!($st = $db->get_db()->prepare("insert into dealer_inventory (place, item_type, item, stock, price, last_update) values (?,?,?,?,?,?)"))) {
				error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				$return_codes[] = 1006;
				break 3;
			}


			// Starter ships of non races first

			foreach ($ships as $ship_race => $ship_list) {

				if ($race == $ship_race) {
					continue;
				}

				foreach ($ship_list as $ship_id => $ship) {

					if ($ship['rank'] > 1) {
						continue;
					}

					$st->bind_param("iiiiii", $dealer_id, $item_types['Ships']['record_id'], $ship['record_id'], $item_types['Ships']['max_stock'], $ship['price'], $time);
				
					if (!$st->execute()) {
						$return_codes[] = 1006;
						error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
						break 4;
					}
				}
				
			}

			// Remaining racial ships next

			foreach ($ships[$race] as $ship_id => $ship) {

				$st->bind_param("iiiiii", $dealer_id, $item_types['Ships']['record_id'], $ship['record_id'], $item_types['Ships']['max_stock'], $ship['price'], $time);
				
				if (!$st->execute()) {
					$return_codes[] = 1006;
					error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					break 3;
				}
			}

			// Done with ships, now tech dealers

			$tech_goods = array();

			$rs = $db->get_db()->query('select * from goods where tech > 0');
		
			$rs->data_seek(0);
			while ($row = $rs->fetch_assoc()) {
				$tech_goods[$row['record_id']] = $row;
			}



			$dealer_name = $name . ' Tech';

			$place_candidate = $place_candidates[mt_rand(0, $place_candidate_count - 1)];

			$x = $place_candidate['x'];
			$y = $place_candidate['y'];

			if (!($st = $db->get_db()->prepare("insert into places (caption, system, x, y, type) values (?,?,?,?,?)"))) {
				error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				$return_codes[] = 1006;
				break 3;
			}

			$st->bind_param("siiii", $dealer_name, $system_id, $x, $y, $place_types['Tech Dealer']);
			
			if (!$st->execute()) {
				$return_codes[] = 1006;
				error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				break 3;
			}

			$dealer_id = $db->last_insert_id('places');
		
			if (!($st = $db->get_db()->prepare("insert into dealer_inventory (place, item_type, item, stock, price, last_update) values (?,?,?,?,?,?)"))) {
				error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				$return_codes[] = 1006;
				break 3;
			}

			foreach ($tech_goods as $good_id => $good) {

				$st->bind_param("iiiiii", $dealer_id, $item_types['Goods']['record_id'], $good_id, $item_types['Goods']['max_stock'], $good['tech'], $time);
				
				if (!$st->execute()) {
					$return_codes[] = 1006;
					error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					break 3;
				}
			}

		}


		// Add warps
		$warps = generate_warps($stars, $star_count);


		foreach ($warps as $warp) {

			$x1 = 500 + $warp['x1'];
			$y1 = 500 + $warp['y1'];
			$x2 = 500 + $warp['x2'];
			$y2 = 500 + $warp['y2'];

			// Find a spot for the warps with no other locations first.

			if (!find_empty_sector($x1, $y1, WARP_LOCATION_VARIANCE)) {
				error_log(__FILE__ . '::' . __LINE__ . " Could not find an empty sector for the source warp.");
				$return_codes[] = 1050;
				break 2;
			}

			/*
			if (!find_empty_sector($x2, $y2, WARP_LOCATION_VARIANCE)) {
				error_log(__FILE__ . '::' . __LINE__ . " Could not find an empty sector for the destination warp.");
				$return_codes[] = 1050;
				break 2;
			}
			*/

			// We got a spot for a warps, let's place them

			$place_id = insert_place("Warp to " . $stars[$warp['i2']]['name'], $warp['s1'], $x1, $y1, 9, $return_codes);

			if ($place_id <= 0) {
				error_log(__FILE__ . '::' . __LINE__ . " Failed to insert place.");
				$return_codes[] = 1050;
				break 2;
			}

			$warp_id = insert_warp($place_id, $x2, $y2);

			if ($warp_id <= 0) {
				error_log(__FILE__ . '::' . __LINE__ . " Failed to insert warp.");
				$return_codes[] = 1050;
				break 2;
			}

			/*
			$place_id = insert_place("Warp to {$x1} {$y1}", $warp['s2'], $x2, $y2, 9, $return_codes);

			if ($place_id <= 0) {
				error_log(__FILE__ . '::' . __LINE__ . " Failed to insert place.");
				$return_codes[] = 1050;
				break 2;
			}

			$warp_id = insert_warp($place_id, $x1, $y1);

			if ($warp_id <= 0) {
				error_log(__FILE__ . '::' . __LINE__ . " Failed to insert warp.");
				$return_codes[] = 1050;
				break 2;
			}
			*/
		}

		
		
	} while (false);


	$return_codes[] = 1000;
?>