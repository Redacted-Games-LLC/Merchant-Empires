<?php
/**
 * Template page for ship attacks
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

	include_once('tmpl/common.php');

	if ($spacegame['player']['level'] < MINIMUM_KILLABLE_LEVEL) {
		header('Location: ship.php?page=weapons&rc=1194');
		die();
	}

	if (!isset($_REQUEST['player_id']) || !is_numeric($_REQUEST['player_id']) || $_REQUEST['player_id'] <= 0) {
		header('Location: ship.php?page=weapons&rc=1014');
		die();
	}

	$player_id = $_REQUEST['player_id'];

	$db = isset($db) ? $db : new DB;

	$rs = $db->get_db()->query("select * from players where record_id = '" . $player_id . "' and x = '" . $spacegame['player']['x'] . "' and y = '" . $spacegame['player']['y'] . "' and base_id = '" . $spacegame['player']['base_id'] . "'");

	$rs->data_seek(0);

	if (!($player = $rs->fetch_assoc())) {
		header('Location: ship.php?page=weapons&rc=1014');
		die();
	}

	if ($player['ship_type'] <= 0) {
		header('Location: ship.php?page=weapons&rc=1195');
		die();
	}

	if ($player['level'] < MINIMUM_KILLABLE_LEVEL) {
		//header('Location: ship.php?page=weapons&rc=1194');
		//die();
	}

	$ship = $spacegame['ships'][$player['ship_type']];

	include_once('inc/solutions.php');
	include_once('inc/cargo.php');
	include_once('inc/ranks.php');
?>
<div class="header2">Attacking Player: <?php echo $player['caption']; ?></div>
<div class="docs_text">
	<strong>Target Information</strong>
</div>
<div class="docs_text">
	<?php
		echo 'Race: ' . $spacegame['races'][$player['race']]['caption'];

		if ($spacegame['player']['race'] == $player['race']) {
			echo ' <span class="no_race_war">(No War)</span>';
		}
		else {
			echo ' <span class="race_war">(War)</span>';
		}
		
		echo '<br />';
		echo 'Level: ' . $player['level'] . '<br />';
		echo 'Rank: ' . $spacegame['ranks'][$player['rank']]['caption'] . '<br />';
		echo 'Ship: ' . $spacegame['races'][$ship['race']]['caption'] . ' ' . $ship['caption'];
		
		if (strlen($player['ship_name']) > 0) {
			echo ' "' . $player['ship_name'] . '"';
		}
		else {
			echo ' "' . DEFAULT_SHIP_NAME . '"';
		}
	?>
</div>
<hr />
<?php
	if ($spacegame['weapon_count'] <= 0) {
		echo '<div class="docs_text">';
		echo 'There are no discovered weapons in the galaxy.';
		echo '</div>';
	}
	elseif (WEAPON_SOLUTION_LIMIT <= 0) {
		echo '<div class="docs_text">';
		echo 'Weapon solutions are not available at this time.';
		echo '</div>';
	}
	elseif ($spacegame['player']['ship_type'] <= 0) {
		echo '<div class="docs_text">';
		echo 'You must be in a ship to manipulate weapon solutions.';
		echo '</div>';
	}
	else {

		echo '<div class="attack_buttons">';


		$carried_weapons = array();
		$carried_count = 0;

		foreach ($spacegame['weapons'] as $weapon_id => $weapon) {
			if (isset($spacegame['cargo_index'][$weapon['good']]) && $spacegame['cargo'][$spacegame['cargo_index'][$weapon['good']]]['amount'] > 0) {
				$carried_weapons[] = $weapon;
				$carried_count++;
			}
		}

		
		$solution_groups = array_reverse($spacegame['solution_groups'], true);
		$solution_keys = array_keys($spacegame['solution_groups']);

		for ($s = 0; $s < WEAPON_SOLUTION_LIMIT; $s++) {
			$solution_group = array_pop($solution_groups);

			if ($solution_group == null) {
				break;
			}
			
			$recharge_part = 0;
			$recharge_whole = 0;

			$total_damage = 0;
			$count = 0;
			$total_time = 0;

			foreach ($solution_group as $index => $solution_id) {
				$count++;

				$solution = $spacegame['solutions'][$solution_id];
				$weapon = $spacegame['weapons'][$solution['weapon']];
				
				$total_damage += $weapon['general_damage'] * $weapon['volley'];
				$total_damage += $weapon['shield_damage'] * $weapon['volley'];
				$total_damage += $weapon['armor_damage'] * $weapon['volley'];

				$total_time += (PAGE_START_TIME - $solution['fire_time']);
			}

			$recharge_whole = $total_damage * RECHARGE_TIME_PER_DAMAGE;

			if ($recharge_whole < 1) {
				$recharge_whole = 1;
			}

			$recharge_part = $total_time / $count;

			if ($recharge_part > $recharge_whole) {
				$recharge_part = $recharge_whole;
			}

			echo '<div class="attack_button" onclick="return attack_player('. $solution_keys[$s] .',' . $player['record_id'] . ');">';
			
			echo '<div class="attack_solution">';
			echo '#' . ($s + 1);
			echo '</div>';

			echo '<svg class="attack_recharge" width="107" height="12">';
	  		echo '<rect width="105" height="10" style="fill:rgb(0,64,0);stroke-width:2;stroke:rgb(255,255,255)" />';
  			echo '<rect width="' . (105 * $recharge_part / $recharge_whole) . '" height="10" style="fill:rgb(0,255,128);stroke-width:2;stroke:rgb(255,255,255)" />';
			echo '</svg>';

			echo '</div>';




		}

		echo '</div>';
	}
?>



