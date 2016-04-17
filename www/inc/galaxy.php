<?php
/**
 * Dump file for a bunch of galaxy generation functions
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

	function build_star_map($galaxy_size, $seed, &$star_count = 0) {

		$distance_between_stars = 12;
		$star_variance = 10;
		$random_star_drop = 0.9;
		$protected_systems_per_race = 3;
		$protected_systems_chance = 0.01;

		$galaxy_radius = $galaxy_size / 2.1;
	
		mt_srand($seed);

		// Generate the initial stars

		$stars = array();
		$star_count = 0;
		$theta = 0;
	
		for ($theta = 0; $theta <= 2 * M_PI * $galaxy_radius; $theta += (M_PI / 12)) {
			
			if (mt_rand(0,1000) < ($random_star_drop * 1000)) {
				continue;
			}

			$r = $theta / (2 * M_PI);

			$star = array();
			
			$star['x'] = floor($r * cos($theta) + mt_rand(-$star_variance, $star_variance));
			$star['y'] = floor($r * sin($theta) + mt_rand(-$star_variance, $star_variance));

			$angle = M_PI + atan2($star['y'], $star['x']);

			if ($angle < M_PI * 2 / 3) {
				$star['race'] = 1;
			}
			elseif ($angle < M_PI * 4 / 3) {
				$star['race'] = 2;
			}
			else {
				$star['race'] = 3;
			}

			$star['protected'] = false;
			$star['system_id'] = 0;

			$stars[] = $star;
			$star_count++;
		}

		// Do some pruning of the stars we came up with.

		$new_stars = array();
		$new_star_count = 0;

		for ($i = $star_count - 1; $i > 0; $i--) {
			$this_star = $stars[$i];
			
			$neighbor_count = 0;
			
			for ($j = $i - 1; $j >= 0; $j--) {
				$that_star = $stars[$j];
				
				$dist2 = (($that_star['x'] - $this_star['x']) ** 2) + (($that_star['y'] - $this_star['y']) ** 2);
				
				if ($dist2 < $distance_between_stars ** 2) {
					continue 2;
				}	
			}
		
			
			$new_stars[] = $this_star;
			$new_star_count++;
		}

		$stars = $new_stars;
		$star_count = $new_star_count;

		// Now we are going to look for protected systems to lay government stuff

		$r1 = $galaxy_radius * 2 / 5;
		$r2 = $galaxy_radius * 4 / 5;

		$race_angle = M_PI * 2 / 3;
		$gap_angle = M_PI / 6 + M_PI / 12;
	
		$prot = array();
		
		for ($r = 1; $r <= 3; $r++) {
			$prot[$r][1]['c'] = null;
			$prot[$r][1]['d'] = 1000000;
			$prot[$r][1]['x'] = $r2 * cos($race_angle);
			$prot[$r][1]['y'] = $r2 * sin($race_angle);
			$prot[$r][1]['n'] = 0;
			$prot[$r][2]['c'] = null;
			$prot[$r][2]['d'] = 1000000;
			$prot[$r][2]['x'] = $r1 * cos($race_angle - $gap_angle);
			$prot[$r][2]['y'] = $r1 * sin($race_angle - $gap_angle);
			$prot[$r][2]['n'] = -1;
			$prot[$r][3]['c'] = null;
			$prot[$r][3]['d'] = 1000000;
			$prot[$r][3]['x'] = $r1 * cos($race_angle + $gap_angle);
			$prot[$r][3]['y'] = $r1 * sin($race_angle + $gap_angle);
			$prot[$r][3]['n'] = 1;

			$race_angle += M_PI * 2 / 3;
		}

		// We now have a list of coordinates where we want to place protected
		// systems near. We must find the closets to each of these. This next
		// mess loops through all stars continually updating the closest one.
		
		for ($i = 0; $i < $star_count; $i++) {
			$star = $stars[$i];
			
			for ($r = 1; $r <= 3; $r++) {
				$race_prot = $prot[$r];

				for ($j = 1; $j <= 3; $j++) {
					$target = $race_prot[$j];

					$dist = ($target['x'] - $star['x']) ** 2 + ($target['y'] - $star['y']) ** 2;

					if ($dist < $target['d']) {
						$prot[$r][$j]['d'] = $dist;
						$prot[$r][$j]['c'] = $i;
					}
				}
			}

			$stars[$i]['size'] = mt_rand(2,5);
		}

		// Now the $prot array knows the closest star we can go ahead and update
		// that star with it's new status.

		//TODO: get this from elsewhere.
		$races = array(0=>"Mawlor", 1=>"Zyck'lirg", 2=>'Xollian', 3=>"Mawlor", 4=>"Zyck'lirg");


		for ($r = 1; $r <= 3; $r++) {
			$race_prot = $prot[$r];

			for ($j = 1; $j <= 3; $j++) {
				$target = $race_prot[$j];
				$stars[$prot[$r][$j]['c']]['protected'] = true;
				$stars[$prot[$r][$j]['c']]['size'] = 5;

				if ($prot[$r][$j]['n'] == 0) {
					$stars[$prot[$r][$j]['c']]['name'] = $races[$r] . ' Prime';
				}
				else {
					$stars[$prot[$r][$j]['c']]['name'] = substr($races[$r], 0, 4) . '-' . substr($races[$r + $prot[$r][$j]['n']], 0, 4) . ' Hub';
				}
			}
		}


		return $stars;
	}



	function generate_warps($stars, $star_count) {

		$warps = array();

		for ($i = 0; $i < $star_count; $i++) {
			if (!$stars[$i]['protected']) {
				continue;
			}

			for ($j = 0; $j < $star_count; $j++) {
				
				if ($i == $j) {
					continue;
				}

				if (!$stars[$j]['protected']) {
					continue;
				}

				// The next few lines makes sure we don't create too long of a warp,
				// which prevents warps connecting strangely.

				$comp = 30000;

				if ($stars[$i]['race'] == $stars[$j]['race']) {
					$comp = 100000;
				}

				$dist = ($stars[$i]['x'] - $stars[$j]['x']) ** 2 + ($stars[$i]['y'] - $stars[$j]['y']) ** 2;
				
				if ($dist > $comp) {
					continue;
				}

				$warps[] = array(
					'i1' => $i,
					's1' => $stars[$i]['system_id'],
					'x1' => $stars[$i]['x'],
					'y1' => $stars[$i]['y'],
					'i2' => $j,
					's2' => $stars[$j]['system_id'],
					'x2' => $stars[$j]['x'],
					'y2' => $stars[$j]['y']
				);
			}
		}

		return $warps;

	}



	function generate_system($star) {

		$stuff_grid = array();


		if ($star['size'] >= BINARY_STAR_MIN_RADIUS && mt_rand(0,1000) < 1000 * BINARY_STAR_CHANCE) {
			// Binary star

			$sx = 0;
			$sy = 0;

			do {
				$sx = (mt_rand() % 3) - 1;
				$sy = (mt_rand() % 3) - 1;
			} while ($sx == 0 && $sy == 0);
			
			$stuff_grid[$sx][$sy] = array('type' => 'Star', 'details' => $star);
			$stuff_grid[-$sx][-$sy] = array('type' => 'Star', 'details' => $star);
		}
		else {
			// Single star
			$stuff_grid[0][0] = array('type' => 'Star', 'details' => $star);
		}

		// TODO: Find a better way of getting this info
		$planets = array(
			'Rocky Planet',
			'Earth Planet',
			'Ice Giant'
		);

		$planets_available = count($planets);

		$planet_count = (PLANETS_PER_RADIUS * $star['size']) + $star['size'];

		while ($planet_count > 0) {

			$sx = mt_rand(-$star['size'], $star['size']);
			$sy = mt_rand(-$star['size'], $star['size']);

			if (isset($stuff_grid[$sx][$sy])) {
				continue;
			}

			$planet_count -= 1;
			$stuff_grid[$sx][$sy] = array('type' => $planets[mt_rand(0, $planets_available - 1)]);
		}

		return $stuff_grid;
	}




	function insert_place($caption, $system_id, $x, $y, $type_id, $return_codes = array()) {

		if (is_null($caption) || strlen($caption) > 24) {
			error_log(__FILE__ . '::' . __LINE__ . " Caption is invalid for insert: $caption");
			$return_codes[] = 1050;
			return 0;
		}

		if ((!is_numeric($x)) || (!is_numeric($y))) {
			error_log(__FILE__ . '::' . __LINE__ . " Both x and y values must be numeric.");
			$return_codes[] = 1050;
			return 0;
		}

		global $db;
		$db = isset($db) ? $db : new DB;
		
		
		if (!($st = $db->get_db()->prepare("insert into places (caption, system, x, y, type) values (?,?,?,?,?)"))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			return 0;
		}

		$st->bind_param("siiii", $caption, $system_id, $x, $y, $type_id);
		
		if (!$st->execute()) {
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
			$return_codes[] = 1006;
			return 0;
		}

		return $db->last_insert_id('places');
	}



	function find_empty_sector(&$x, &$y, $variance, $no_centering = false) {

		if ((!is_numeric($x)) || (!is_numeric($y))) {
			error_log(__FILE__ . '::' . __LINE__ . " The x and y location must both be numeric.");
			return false;
		}

		global $db;
		$db = isset($db) ? $db : new DB;

		$test_x = (int)$x;
		$test_y = (int)$y;
		$attempts = $variance ** 5 + 1;

		while ($attempts > 0) {

			$attempts -= 1;

			$test_x = mt_rand() % (($variance * 2) + 1);
			$test_y = mt_rand() % (($variance * 2) + 1);

			$test_x -= $variance;
			$test_y -= $variance;

			$test_x += $x;
			$test_y += $y;

			if ($no_centering && $x == $test_x && $y == $test_y) {
				continue;
			}

			$rs = $db->get_db()->query("select record_id from places where x = '$test_x' and y = '$test_y' limit 1");
	
			if (!$rs) {
				error_log(__FILE__ . '::' . __LINE__ . " Query selection failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				return false;
			}

			$rs->data_seek(0);

			if (!($row = $rs->fetch_assoc())) {
				$x = $test_x;
				$y = $test_y;
				return true;
			}
		}

		return false;
	}


	function insert_warp($place_id, $x, $y) {

		global $db;
		$db = isset($db) ? $db : new DB;

		if (!($st = $db->get_db()->prepare("insert into warps (place, x, y) values (?,?,?)"))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			return 0;
		}

		$st->bind_param("iii", $place_id, $x, $y);
		
		if (!$st->execute()) {
			error_log(__FILE__ . '::' . __LINE__ . " Execution failed: (" . $st->errno . ") " . $st->error);
			return 0;
		}

		return $db->last_insert_id('warps');
	}





	function insert_port($caption, $system, $x, $y, $supply_count, $demand_count, $planet_type, $update_distances = true) {

		global $db;
		$db = isset($db) ? $db : new DB;

		global $spacegame;
		global $return_codes;

		$demand_start_amount = -PORT_LIMIT;
		$supply_start_amount = PORT_LIMIT;

		if (!($st = $db->get_db()->prepare("insert into places (caption, system, x, y, type) values (?,?,?,?,?)"))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			return false;
		}
		
		$st->bind_param("siiii", $caption, $system, $x, $y, $spacegame['place_types_index']['Port']);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
			return false;
		}

		$place_id = $db->last_insert_id('places');

		// Grab a list of goods this place will supply or demand

		$supplies = array();
		$demands = array();

		$error_limiter = 1000;

		while ($supply_count > 0 && $error_limiter > 0) {
			$error_limiter--;

			foreach ($spacegame['start_goods'][$planet_type]['supply'] as $good => $percent) {
				if (isset($supplies[$good])) {
					continue;
				}

				if (mt_rand(0, 100) <= $percent) {
					// One chosen so it can be used later as part of the port_goods query
					$supplies[$good] = 1;
					$supply_count--;

					if ($supply_count <= 0) {
						break 2;
					}
				}
			}
		}

		$error_limiter = 1000;

		while ($demand_count > 0  && $error_limiter > 0) {
			$error_limiter--;

			foreach ($spacegame['start_goods'][$planet_type]['demand'] as $good => $percent) {

				// Note the check for both demand and supply here
				if (isset($demands[$good]) || isset($supplies[$good])) {
					continue;
				}

				if (mt_rand(0, 100) <= $percent) {
					// Zero chosen so it can be used later as part of the port_goods query
					$demands[$good] = 0;
					$demand_count--;

					if ($demand_count <= 0) {
						break 2;
					}
				}
			}
		}

		$time = PAGE_START_TIME;
		// Add our supplies and demands to the database
		$goods_array = null;

		foreach ($supplies as $good => $supply) {

			if (!($st = $db->get_db()->prepare("insert into port_goods (place, good, amount, supply, last_update) values (?,?,?,?,?)"))) {
				error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				$return_codes[] = 1006;
				return false;
			}
			
			$st->bind_param("iiiii", $place_id, $good, $supply_start_amount, $supply, $time);
			
			if (!$st->execute()) {
				$return_codes[] = 1006;
				error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
				return false;
			}	

			$goods_array[] = $good;
		}

		foreach ($demands as $good => $supply) {

			if (!($st = $db->get_db()->prepare("insert into port_goods (place, good, amount, supply, last_update) values (?,?,?,?,?)"))) {
				error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				$return_codes[] = 1006;
				return false;
			}
			
			$st->bind_param("iiiii", $place_id, $good, $demand_start_amount, $supply, $time);
			
			if (!$st->execute()) {
				$return_codes[] = 1006;
				error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
				return false;
			}	

			$goods_array[] = $good;
		}

		if ($update_distances) {
			update_distances($goods_array);
		}

		return true;
	}



	function update_distances($goods_array = null) {

		global $db;
		$db = isset($db) ? $db : new DB;

		$tail = "";

		if (is_array($goods_array)) {
			$tail = ' and good in ('. join(',', $goods_array) .')';
		}

		// Run distance updates

		$distance_items = array();
		$distance_index = array();

		$rs = $db->get_db()->query("select port_goods.record_id, port_goods.place, port_goods.supply, port_goods.good, places.x, places.y from places, place_types, port_goods where places.type = place_types.record_id and place_types.caption = 'Port' and port_goods.place = places.record_id " . $tail);

		if (!$rs || !$rs->data_seek(0)) {
			error_log(__FILE__ . '::' . __LINE__ . " Query selection failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			return;
		}

		while ($row = $rs->fetch_assoc()) {
			$distance_items[$row['good']]['records'][] = $row;
			$distance_items[$row['good']]['count'] = isset($distance_items[$row['good']]['count']) ? $distance_items[$row['good']]['count'] + 1 : 1; 

			$distance_index[$row['supply']][$row['good']] = true;
		}

		foreach ($distance_items as $good => $item) {

			for ($i = 0; $i < $item['count']; $i++) {
				$distance = MAX_DISTANCE + 1;

				for ($j = 0; $j < $item['count']; $j++) {

					if ($i == $j) {
						continue;
					}

					if ($item['records'][$i]['place'] == $item['records'][$j]['place']) {
						continue;
					}

					if ($item['records'][$i]['supply'] == $item['records'][$j]['supply']) {
						continue;
					}

					if ($item['records'][$i]['supply'] == 0 && !isset($distance_index[1][$item['records'][$j]['good']])) {
						continue;
					}

					if ($item['records'][$i]['supply'] == 1 && !isset($distance_index[0][$item['records'][$j]['good']])) {
						continue;
					}

					$test_distance = max(abs($item['records'][$i]['x'] - $item['records'][$j]['x']), abs($item['records'][$i]['y'] - $item['records'][$j]['y']));

					if ($test_distance < $distance) {
						$distance = $test_distance;
					}
				}

				if ($distance > MAX_DISTANCE) {
					$distance = 0;
				}

				if (!($st = $db->get_db()->prepare("update port_goods set distance = ? where record_id = ?"))) {
					error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					break 2;
				}
				
				$st->bind_param("ii", $distance, $item['records'][$i]['record_id']);
				
				if (!$st->execute()) {
					error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
					break 2;
				}
			}
		}
	}




	function insert_solar_collector($caption, $system, $x, $y) {

		$db = isset($db) ? $db : new DB;


		$place_type = 0;
		$item_type = 0;
		$good_id = 0;
		$good_level = 0;
		$amount = 0;
		$time = PAGE_START_TIME;


		$rs = $db->get_db()->query("select record_id from place_types where caption = 'Solar Collector'");
	
		if (!($rs && $rs->data_seek(0))) {
			error_log(__FILE__ . '::' . __LINE__ . " Query selection failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			return false;
		}

		if ($row = $rs->fetch_assoc()) {
			$place_type = $row['record_id'];
		}


		$rs = $db->get_db()->query("select record_id, max_stock from item_types where caption = 'Goods'");
	
		if (!($rs && $rs->data_seek(0))) {
			error_log(__FILE__ . '::' . __LINE__ . " Query selection failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			return false;
		}

		if ($row = $rs->fetch_assoc()) {
			$item_type = $row['record_id'];
			$amount = $row['max_stock'];
		}

		$rs = $db->get_db()->query("select record_id, level from goods where caption = 'Energy'");
	
		if (!($rs && $rs->data_seek(0))) {
			error_log(__FILE__ . '::' . __LINE__ . " Query selection failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			return false;
		}

		if ($row = $rs->fetch_assoc()) {
			$good_id = $row['record_id'];
			$good_level = $row['level'];
		}

		$rs = $db->get_db()->query("select record_id from places where type = '" . $place_type . "' and x = '". $x ."' and y = '". $y ."'");
	
		if (!$rs) {
			error_log(__FILE__ . '::' . __LINE__ . " Query selection failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			return false;
		}

		$rs->data_seek(0);

		$dealer_id = 0;
		$inventory_count = 0;

		if ($row = $rs->fetch_assoc()) {
			$dealer_id = $row['record_id'];

			$rs = $db->get_db()->query("select count(*) as count from dealer_inventory where place = " . $dealer_id);
	
			if (!$rs) {
				error_log(__FILE__ . '::' . __LINE__ . " Query selection failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				return false;
			}

			$rs->data_seek(0);

			if ($row = $rs->fetch_assoc()) {
				$inventory_count = $row['count'];
			}
		}
		else {
			if (!($st = $db->get_db()->prepare("insert into places (caption, system, x, y, type) values (?,?,?,?,?)"))) {
				error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				$return_codes[] = 1006;
				return false;
			}

			$st->bind_param("siiii", $caption, $system, $x, $y, $place_type);
			
			if (!$st->execute()) {
				$return_codes[] = 1006;
				error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				return false;
			}

			$dealer_id = $db->last_insert_id('places');
		}
	
		if ($inventory_count >= SOLAR_COLLECTORS_PER_SECTOR) {
			return false;
		}

		if (!($st = $db->get_db()->prepare("insert into dealer_inventory (place, item_type, item, stock, price, last_update) values (?,?,?,?,?,?)"))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			return false;
		}

		$st->bind_param("iiiiii", $dealer_id, $item_type, $good_id, $amount, $good_level, $time);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			return false;
		}		


		return true;
	}




	function insert_base($caption, $system, $x, $y, $owner, $alliance) {

		global $db;
		$db = isset($db) ? $db : new DB;

		$place_type = 0;


		$rs = $db->get_db()->query("select record_id from place_types where caption = 'Base'");
	
		if (!($rs && $rs->data_seek(0))) {
			error_log(__FILE__ . '::' . __LINE__ . " Query selection failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			return false;
		}

		if ($row = $rs->fetch_assoc()) {
			$place_type = $row['record_id'];
		}
		else {
			return false;
		}

		$room = array();

		$rs = $db->get_db()->query("select record_id, width, height, build_time from room_types where caption = 'Control Pad'");
	
		if (!($rs && $rs->data_seek(0))) {
			error_log(__FILE__ . '::' . __LINE__ . " Query selection failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			return false;
		}

		if ($row = $rs->fetch_assoc()) {
			$room = $row;
		}
		else {
			error_log('Missing control pad in base room types.');
			return false;
		}



		if (!($st = $db->get_db()->prepare("insert into places (caption, system, x, y, type) values (?,?,?,?,?)"))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			return 0;
		}

		$st->bind_param("siiii", $caption, $system, $x, $y, $place_type);
		
		if (!$st->execute()) {
			error_log(__FILE__ . '::' . __LINE__ . " Execution failed: (" . $st->errno . ") " . $st->error);
			return 0;
		}

		$place_id = $db->last_insert_id('places');

		mt_srand($place_id);
		$seed = mt_rand();
		$shields = START_BASE_SHIELDS;

		if ($alliance > 0) {
			if (!($st = $db->get_db()->prepare("insert into bases (owner, alliance, seed, shields, place) values (?,?,?,?,?)"))) {
				error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				return 0;
			}

			$st->bind_param("iiiii", $owner, $alliance, $seed, $shields, $place_id);
			
			if (!$st->execute()) {
				error_log(__FILE__ . '::' . __LINE__ . " Execution failed: (" . $st->errno . ") " . $st->error);
				return 0;
			}
		}
		else {
			if (!($st = $db->get_db()->prepare("insert into bases (owner, seed, shields, place) values (?,?,?,?)"))) {
				error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				return 0;
			}

			$st->bind_param("iiii", $owner, $seed, $shields, $place_id);
			
			if (!$st->execute()) {
				error_log(__FILE__ . '::' . __LINE__ . " Execution failed: (" . $st->errno . ") " . $st->error);
				return 0;
			}
		}
		
		$base_id = $db->last_insert_id('bases');
		$x = 50 + mt_rand($room['width'] * 2) - $room['width'];
		$y = 50 + mt_rand($room['height'] * 2) - $room['height'];
		$time = PAGE_START_TIME + (HAVOC_ROUND ? $room['build_time'] * HAVOC_SHIP_COST : $room['build_time']);

		// Insert the Landing Pad

		if (!($st = $db->get_db()->prepare("insert into base_rooms (base, room, x, y, finish_time) values (?,?,?,?,?)"))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			return 0;
		}

		$st->bind_param("iiiii", $base_id, $room['record_id'], $x, $y, $time);
		
		if (!$st->execute()) {
			error_log(__FILE__ . '::' . __LINE__ . " Execution failed: (" . $st->errno . ") " . $st->error);
			return 0;
		}


		return $base_id;
	}




?>