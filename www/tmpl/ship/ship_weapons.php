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
?>
<div class="header2">Weapon Solutions</div>
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
	else {

?>
<div class="header3">Current Status</div>
<?php 
	
	$general_damage = 0.0;
	$shield_damage = 0.0;
	$armor_damage = 0.0;

	foreach ($spacegame['solutions'] as $solution) {
		$weapon = $spacegame['weapons'][$solution['weapon']];

		$accuracy = $weapon['volley'] * $weapon['accuracy'];

		$general_damage += $weapon['general_damage'] * $weapon['volley'] * $accuracy;
		$shield_damage += $weapon['shield_damage'] * $weapon['volley'] * $accuracy;
		$armor_damage += $weapon['armor_damage'] * $weapon['volley'] * $accuracy;
	}

	$potency = $general_damage + $shield_damage + $armor_damage;




	$carried_weapons = array();
	$carried_count = 0;

	foreach ($spacegame['weapons'] as $weapon_id => $weapon) {
		if (isset($spacegame['cargo_index'][$weapon['good']]) && $spacegame['cargo'][$spacegame['cargo_index'][$weapon['good']]]['amount'] > 0) {
			$carried_weapons[] = $weapon;
			$carried_count++;
		}
	}

	
	$solution_groups = array_reverse($spacegame['solution_groups']);

	for ($s = 0; $s < WEAPON_SOLUTION_LIMIT; $s++) {
?>		
<hr />
<div class="header4">Solution #<?php echo $s + 1; ?></div>
<div class="docs_text">
	<?php

		$solution_group = array_pop($solution_groups);

		if ($solution_group == null) {
			if ($carried_count <= 0) {
				echo '<p>You are not carrying any weapons to add.</p>';
			}
			else {
				echo '<p>Add a weapon to this solution to create it.</p>';

				
				echo '<table class="add_weapon">';

				foreach ($carried_weapons as $weapon) {
					
					echo '<tr class="add_weapon">';

					echo '<td class="add_weapon" rowspan="2"><strong>';
					echo $weapon['caption'];
					echo '</strong></td>';

					echo '<td class="add_weapon_center" colspan="2">';

					if ($weapon['race'] <= 0) {
						echo 'Neutral';
					}
					else {
						echo $spacegame['races'][$weapon['race']]['caption'];
					}

					echo ' Race';
					echo '</td>';

					echo '<td class="add_weapon_right">';
					echo $weapon['stations'] . '&nbsp;';
					echo '<img src="res/station.png" alt="station" title="Stations Needed" width="16" />';
					echo '</td>';

					echo '<td class="add_weapon_right">';
					echo $weapon['racks'] . '&nbsp;';
					echo '<img src="res/rack.png" alt="rack" title="Racks Needed" width="16" />';
					echo '</td>';

					echo '<td class="add_weapon">';
					echo '&nbsp;';
					echo '</td>';
					
					echo '<td class="add_weapon" rowspan="2">';
					?>

						<form action="handler.php" method="post">
						

							<script type="text/javascript">drawButton('add', 'add', 'validate_add()')</script>

							<input type="hidden" name="task" value="weapon" />
							<input type="hidden" name="subtask" value="add" />
							<input type="hidden" name="group" value="0" />
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

					echo '<td class="add_weapon_right">';
					echo $weapon['general_damage'] . '&nbsp;';
					echo '<img src="res/shields.png" alt="shields" title="Shield Damage" width="16" />+';
					echo '<img src="res/armor.png" alt="armor" title="Armor Damage" width="16" />';
					echo '</td>';

					echo '<td class="add_weapon_right">';
					echo $weapon['shield_damage'] . '&nbsp;';
					echo '<img src="res/shields.png" alt="shields" title="Shield Damage" width="16" />';
					echo '</td>';

					echo '<td class="add_weapon_right">';
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
		}
		else {


			echo '<table class="add_weapon">';

			foreach ($carried_weapons as $weapon) {
				
				echo '<tr class="add_weapon">';

				echo '<td class="add_weapon" rowspan="2"><strong>';
				echo $weapon['caption'];
				echo '</strong></td>';

				echo '<td class="add_weapon_center" colspan="2">';

				if ($weapon['race'] <= 0) {
					echo 'Neutral';
				}
				else {
					echo $spacegame['races'][$weapon['race']]['caption'];
				}

				echo ' Race';
				echo '</td>';

				echo '<td class="add_weapon_right">';
				echo $weapon['stations'] . '&nbsp;';
				echo '<img src="res/station.png" alt="station" title="Stations Needed" width="16" />';
				echo '</td>';

				echo '<td class="add_weapon_right">';
				echo $weapon['racks'] . '&nbsp;';
				echo '<img src="res/rack.png" alt="rack" title="Racks Needed" width="16" />';
				echo '</td>';

				echo '<td class="add_weapon">';
				echo '&nbsp;';
				echo '</td>';
				
				echo '<td class="add_weapon" rowspan="2">';
				?>

					<form action="handler.php" method="post">
					

						<script type="text/javascript">drawButton('remove', 'remove', 'validate_remove()')</script>

						<input type="hidden" name="task" value="weapon" />
						<input type="hidden" name="subtask" value="remove" />
						<input type="hidden" name="solution_group" value="<?php echo $solution_group; ?>" />
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

				echo '<td class="add_weapon_right">';
				echo $weapon['general_damage'] . '&nbsp;';
				echo '<img src="res/shields.png" alt="shields" title="Shield Damage" width="16" />+';
				echo '<img src="res/armor.png" alt="armor" title="Armor Damage" width="16" />';
				echo '</td>';

				echo '<td class="add_weapon_right">';
				echo $weapon['shield_damage'] . '&nbsp;';
				echo '<img src="res/shields.png" alt="shields" title="Shield Damage" width="16" />';
				echo '</td>';

				echo '<td class="add_weapon_right">';
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





			if ($carried_count <= 0) {
				echo '<p>You are not carrying any weapons to add.</p>';
			}
			else {
				echo '<p>You can add another weapon to this solution.</p>';
			}
		}
		
	?>

</div>

<?php
	}
}
?>



