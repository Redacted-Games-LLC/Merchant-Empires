<?php
/**
 * Template page for ship weapon solutions
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
	include_once('inc/solutions.php');
	include_once('inc/cargo.php');

	define("TH_SOLUTION_CENTER", '<th class="solution align_center">');
	define("TD_SHIP_SOLUTION_CENTER", '<td class="ship solution align_center">');
	define("TD_ADD_WEAPON_RIGHT", '<td class="add_weapon align_right">');
?>
<div class="header2 header_bold">Weapon Solutions</div>
<div class="docs_text">
	Weapon solutions allow for the installation of weapons on your ship. When you create a
	solution, weapons are installed and consumed from your cargo. For further info view the
	<a href="docs.php?page=weapons" target="_blank">documentation on weapons</a>.
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

?>
<div class="header3 header_bold">Current Status</div>
<div class="docs_text">
<?php
	echo '<table class="ship_info">';

	echo '<tr class="ship_info">';
	echo '<td class="ship ship_info_key align_left">Ship Type</td>';
	echo '<td class="ship ship_info_value align_left" colspan="2">' . $spacegame['ship']['caption'] . '</td>';
	echo '</tr>';

	echo '<tr class="ship_info">';
	echo '<td class="ship ship_info_key align_left">Stations</td>';
	echo '<td class="ship ship_info_value align_left">' . $spacegame['ship']['stations'] . ' <img src="res/station.png" alt="station" title="Stations Available" width="16" /></td>';
	echo '<td class="ship ship_info_value align_left"><em>' . ($spacegame['ship']['stations'] - $spacegame['solution_stations']) . ' remaining</em></td>';
	echo '</tr>';

	echo '<tr class="ship_info">';
	echo '<td class="ship ship_info_key align_left">Racks</td>';
	echo '<td class="ship ship_info_value align_left">' . $spacegame['ship']['racks'] . ' <img src="res/rack.png" alt="rack" title="Racks Available" width="16" /></td>';
	echo '<td class="ship ship_info_value align_left"><em>' . ($spacegame['ship']['racks'] - $spacegame['solution_racks']) . ' remaining</em></td>';
	echo '</tr>';

	echo '<tr class="ship_info">';
	echo '<td class="ship ship_info_key align_left">Recharge</td>';
	echo '<td class="ship ship_info_value align_left" colspan="2">' . $spacegame['ship']['recharge'] . ' <img src="res/clock.png" alt="recharge" title="Recharge Delay" width="16" /></td>';
	echo '</tr>';

	echo '</table>';

?>
</div>
<?php 

	$carried_weapons = array();
	$carried_count = 0;

	foreach ($spacegame['weapons'] as $weapon_id => $weapon) {
		if (isset($spacegame['cargo_index'][$weapon['good']]) && $spacegame['cargo'][$spacegame['cargo_index'][$weapon['good']]]['amount'] > 0) {
			$carried_weapons[] = $weapon;
			$carried_count++;
		}
	}

	$solution_groups = array_reverse($spacegame['solution_groups'], true);
	$solution_keys = array_keys($solution_groups);

	for ($s = 0; $s < WEAPON_SOLUTION_LIMIT; $s++) {
?>		
<hr />
<div class="header4 header_bold">Solution #<?php echo $s + 1; ?></div>
<div class="docs_text">
	<?php

		$solution_group = array_pop($solution_groups);

		if ($solution_group == null) {
			echo '<p>Add a weapon to this solution to create it.</p>';
		}
		else {

			echo '<table class="solution">';
			$general_damage = 0;
			$shield_damage = 0;
			$armor_damage = 0;
			$rating = 0;
			$count = 0;

			foreach ($solution_group as $index => $solution_id) {
				$count++;

				echo '<tr class="solution">';

				echo TH_SOLUTION_CENTER;
					echo 'Actions';
				echo '</th>';
				
				echo TH_SOLUTION_CENTER;
					echo 'Seq#';
				echo '</th>';

				echo TH_SOLUTION_CENTER;
					echo 'Weapon';
				echo '</th>';

				echo TH_SOLUTION_CENTER;
					echo 'Ammo';
				echo '</th>';

				echo '<th class="solution align_center" title="Rounds per Volley">';
					echo 'Vol';
				echo '</th>';

				echo TH_SOLUTION_CENTER;
					echo 'Acc%';
				echo '</th>';

				echo '</tr>';

				echo '<tr class="solution">';

				$solution = $spacegame['solutions'][$solution_id];

				$weapon = $spacegame['weapons'][$solution['weapon']];
				$accuracy = $weapon['volley'] * $weapon['accuracy'];

				$general_damage += $weapon['general_damage'] * $weapon['volley'];
				$shield_damage += $weapon['shield_damage'] * $weapon['volley'];
				$armor_damage += $weapon['armor_damage'] * $weapon['volley'];

				echo '<td class="ship solution align_center" rowspan="3">';
				?>
					<form class="solution" action="handler.php" method="post">
						<script type="text/javascript">drawButton('move_up<?php echo $solution_id; ?>', 'up', 'validate_move()')</script>

						<input type="hidden" name="task" value="weapon" />
						<input type="hidden" name="subtask" value="move" />
						<input type="hidden" name="direction" value="up" />
						<input type="hidden" name="solution_id" value="<?php echo $solution_id; ?>" />
						<input type="hidden" name="return" value="ship" />
						<input type="hidden" name="form_id" value="<?php echo $_SESSION['form_id']; ?>" />
					</form>

					&nbsp;&nbsp;

					<form class="solution" action="handler.php" method="post">
						<script type="text/javascript">drawButton('move_down<?php echo $solution_id; ?>', 'down', 'validate_move()')</script>

						<input type="hidden" name="task" value="weapon" />
						<input type="hidden" name="subtask" value="move" />
						<input type="hidden" name="direction" value="down" />
						<input type="hidden" name="solution_id" value="<?php echo $solution_id; ?>" />
						<input type="hidden" name="return" value="ship" />
						<input type="hidden" name="form_id" value="<?php echo $_SESSION['form_id']; ?>" />
					</form>

					<br />
					<br />

					<form class="solution" action="handler.php" method="post">
						<script type="text/javascript">drawButton('remove<?php echo $solution_id; ?>', 'remove', 'validate_remove()')</script>

						<input type="hidden" name="task" value="weapon" />
						<input type="hidden" name="subtask" value="remove" />
						<input type="hidden" name="solution_id" value="<?php echo $solution_id; ?>" />
						<input type="hidden" name="return" value="ship" />
						<input type="hidden" name="form_id" value="<?php echo $_SESSION['form_id']; ?>" />
					</form>

				<?php
				echo '</td>';

				echo TD_SHIP_SOLUTION_CENTER;
				echo $index + 1;
				echo '</td>';

				echo TD_SHIP_SOLUTION_CENTER;
				echo $weapon['caption'];
				echo '</td>';

				echo TD_SHIP_SOLUTION_CENTER;
				$good = $spacegame['goods'][$weapon['ammunition']];
				echo '<img src="res/goods/'. $good['safe_caption'] .'.png" alt="' . $good['caption'] . '" title="Ammunition Required" width="16" />&nbsp;';
				echo $good['caption'];
				echo '</td>';

				echo TD_SHIP_SOLUTION_CENTER;
				echo $weapon['volley'];
				echo '&nbsp;<img src="res/volley.png" width="16" alt="" />';
				echo '</td>';

				echo TD_SHIP_SOLUTION_CENTER;
				echo $weapon['accuracy'];
				echo '&nbsp;<img src="res/accuracy.png" width="16" alt="" />';
				echo '</td>';

				echo '</tr>';

				echo '<tr class="solution">';

				echo TH_SOLUTION_CENTER;
					echo 'Score';
				echo '</th>';

				echo TH_SOLUTION_CENTER;
					echo 'Recharge';
				echo '</th>';

				echo '<th class="solution align_center" title="General Damage">';
					echo 'Gen';
				echo '</th>';

				echo '<th class="solution align_center" title="Shield Damage">';
					echo 'Shl';
				echo '</th>';

				echo '<th class="solution align_center" title="Armor Damage">';
					echo 'Arm';
				echo '</th>';

				echo '</tr>';

				echo '<tr class="solution">';

				echo TD_SHIP_SOLUTION_CENTER;
				$power = floor(10 * $weapon['volley'] * $weapon['accuracy'] * ($weapon['general_damage'] + $weapon['shield_damage'] + $weapon['armor_damage']));
				echo $power;
				$rating += $power;
				echo '&nbsp;<img src="res/power.png" width="16" alt="Power" />';
				echo '</td>';

				echo TD_SHIP_SOLUTION_CENTER;
				echo RECHARGE_TIME_PER_DAMAGE * $weapon['volley'] * ($weapon['armor_damage'] + $weapon['shield_damage'] + $weapon['general_damage']);
				echo '&nbsp;<img src="res/clock.png" width="16" alt="" />';
				echo '</td>';

				echo TD_SHIP_SOLUTION_CENTER;
				echo $weapon['general_damage'];
				echo '&nbsp;<img src="res/shields.png" width="16" alt="" />+<img src="res/armor.png" width="16" alt="" />';
				echo '</td>';

				echo TD_SHIP_SOLUTION_CENTER;
				echo $weapon['shield_damage'];
				echo '&nbsp;<img src="res/shields.png" width="16" alt="" />';
				echo '</td>';

				echo TD_SHIP_SOLUTION_CENTER;
				echo $weapon['armor_damage'];
				echo '&nbsp;<img src="res/armor.png" width="16" alt="" />';
				echo '</td>';

				echo '</tr>';

				echo '<tr class="solution">';
				echo '<th class="solution align_center" colspan="6">';
				echo '<hr />';
				echo '</th>';
				echo '</tr>';

			}

			echo '<tr>';
			
			echo '<th class="solution align_center" rowspan="2">';
			echo 'Expected Results';
			echo '</th>';

			echo TH_SOLUTION_CENTER;
				echo 'Score';
			echo '</th>';

			echo TH_SOLUTION_CENTER;
				echo 'Recharge';
			echo '</th>';

			echo '<th class="solution align_center" title="General Damage">';
				echo 'Gen';
			echo '</th>';

			echo '<th class="solution align_center" title="Shield Damage">';
				echo 'Shl';
			echo '</th>';

			echo '<th class="solution align_center" title="Armor Damage">';
				echo 'Arm';
			echo '</th>';

			echo '</tr>';

			echo TD_SHIP_SOLUTION_CENTER;
			echo $rating;
			echo '<img src="res/power.png" width="16" alt="Power" />';
			echo '</td>';

			echo TD_SHIP_SOLUTION_CENTER;
			echo RECHARGE_TIME_PER_DAMAGE * ($general_damage + $shield_damage + $armor_damage) / $count;
			echo '&nbsp;<img src="res/clock.png" width="16" alt="" />';
			echo '</td>';

			echo TD_SHIP_SOLUTION_CENTER;
			echo $general_damage;
			echo '&nbsp;<img src="res/shields.png" width="16" alt="" />+<img src="res/armor.png" width="16" alt="" />';
			echo '</td>';
			
			echo TD_SHIP_SOLUTION_CENTER;
			echo $shield_damage;
			echo '&nbsp;<img src="res/shields.png" width="16" alt="" />';
			echo '</td>';
			
			echo TD_SHIP_SOLUTION_CENTER;
			echo $armor_damage;
			echo '&nbsp;<img src="res/armor.png" width="16" alt="" />';
			echo '</td>';

			echo '</tr>';

			echo '</table>';
		}

		if ($carried_count <= 0) {
			echo '<p>You are not carrying any weapons to add.</p>';
		}
		else {

			echo '<div class="header5 header_bold">';
			echo 'Add Weapons from Cargo:';
			echo '</div>';

			
			echo '<table class="add_weapon">';

			foreach ($carried_weapons as $weapon) {
				
				echo '<tr>';

				echo '<td class="add_weapon" rowspan="2"><strong>';
				echo nl2br($weapon['caption']);
				echo '</strong></td>';

				echo '<td class="add_weapon align_center" colspan="2">';

				if ($weapon['race'] <= 0) {
					echo 'Neutral';
				}
				else {
					echo $spacegame['races'][$weapon['race']]['caption'];
				}

				echo ' Race';
				echo '</td>';

				echo TD_ADD_WEAPON_RIGHT;
				echo $weapon['stations'] . '&nbsp;';
				echo '<img src="res/station.png" alt="station" title="Stations Needed" width="16" />';
				echo '</td>';

				echo TD_ADD_WEAPON_RIGHT;
				echo $weapon['racks'] . '&nbsp;';
				echo '<img src="res/rack.png" alt="rack" title="Racks Needed" width="16" />';
				echo '</td>';

				echo '<td class="add_weapon align_center" title="Computed Power">';
				echo floor(10 * $weapon['volley'] * $weapon['accuracy'] * ($weapon['general_damage'] + $weapon['shield_damage'] + $weapon['armor_damage']));
				echo '<img src="res/power.png" width="16" alt="Power" />';
				echo '</td>';
				
				echo '<td class="add_weapon" rowspan="2">';
				?>

					<form action="handler.php" method="post">
						<script type="text/javascript">drawButton('add<?php echo $s . $weapon['record_id']; ?>', 'add', 'validate_add()')</script>

						<input type="hidden" name="task" value="weapon" />
						<input type="hidden" name="subtask" value="add" />
						<input type="hidden" name="solution_group" value="<?php echo $solution_group == null ? 0 : $s + 1; ?>" />
						<input type="hidden" name="weapon" value="<?php echo $weapon['record_id']; ?>" />
						<input type="hidden" name="return" value="ship" />
						<input type="hidden" name="form_id" value="<?php echo $_SESSION['form_id']; ?>" />
					</form>
				<?php
				echo '</td>';


				echo '</tr>';
				echo '<tr class="add_weapon_row">';

				echo '<td class="add_weapon">';
				echo $weapon['volley'] . '&nbsp;';
				echo '<img src="res/volley.png" alt="volley" title="Rounds per Volley" width="16" />';
				echo '&nbsp;at&nbsp;';
				echo $weapon['accuracy'] . '&nbsp;';
				echo '<img src="res/accuracy.png" alt="accuracy" title="Accuracy per Round" width="16" />';
				echo '</td>';

				echo TD_ADD_WEAPON_RIGHT;
				echo $weapon['general_damage'] . '&nbsp;';
				echo '<img src="res/shields.png" alt="shields" title="Shield Damage" width="16" />+';
				echo '<img src="res/armor.png" alt="armor" title="Armor Damage" width="16" />';
				echo '</td>';

				echo TD_ADD_WEAPON_RIGHT;
				echo $weapon['shield_damage'] . '&nbsp;';
				echo '<img src="res/shields.png" alt="shields" title="Shield Damage" width="16" />';
				echo '</td>';

				echo TD_ADD_WEAPON_RIGHT;
				echo $weapon['armor_damage'] . '&nbsp;';
				echo '<img src="res/armor.png" alt="armor" title="Armor Damage" width="16" />';
				echo '</td>';

				echo '<td class="add_weapon" title="Ammunition Required">';
				$good = $spacegame['goods'][$weapon['ammunition']];
				echo '<img src="res/goods/'. $good['safe_caption'] .'.png" alt="' . $good['caption'] . '" title="Ammunition Required" width="16" />&nbsp;';
				echo $good['caption'];
				echo '</td>';

				echo '</tr>';
			}
			echo '</table>';
		}
	?>
</div>

<?php
	}
}
?>