<?php
/**
 * Sidebar with ship information
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
	include_once('inc/ships.php');
?>
<div class="viewport_ship_info">
	<br />
	<div class="sidebar_ship_type">
		<?php echo $spacegame['ship']['caption']; ?>
		<?php
			echo $spacegame['player']['attack_rating'] . ':' . compute_dr($spacegame['player']);
		?>

	</div>
	<div class="sidebar_ship_name">
		<?php echo $spacegame['player']['ship_name'] == '' ? DEFAULT_SHIP_NAME : $spacegame['player']['ship_name']; ?>
	</div>
	<div class="sidebar_ship_turns">
		<strong><?php echo $spacegame['ship']['tps']; ?></strong> Turn<?php echo $spacegame['ship']['tps'] == 1 ? '' : 's'; ?> Per Sector
	</div>
	<?php if ($spacegame['player']['ship_type'] > 0) { ?>

		<br />
		<div class="sidebar_ship_combat">
			<div class="sidebar_ship_armor">
				<strong><?php echo number_format($spacegame['player']['armor']) . '/' . number_format($spacegame['ship']['armor']); ?></strong> 
				<svg width="60" height="12">
	  				<rect width="60" height="10" style="fill:rgb(64,0,0);stroke-width:2;stroke:rgb(255,255,255)" />
	  				<rect width="<?php echo (60 * $spacegame['player']['armor'] / $spacegame['ship']['armor']); ?>" height="10" style="fill:rgb(0,255,128);stroke-width:2;stroke:rgb(255,255,255)" />
				</svg>
				<img src="res/armor.png" width="16" height="16" alt="Armor" title="Armor" /> +0
			</div>

			<div class="sidebar_ship_shields">
				<strong><?php echo number_format($spacegame['player']['shields']) . '/' . number_format($spacegame['ship']['shields']); ?></strong>
				<svg width="60" height="12">
	  				<rect width="60" height="10" style="fill:rgb(64,0,0);stroke-width:2;stroke:rgb(255,255,255)" />
	  				<rect width="<?php echo (60 * $spacegame['player']['shields'] / $spacegame['ship']['shields']); ?>" height="10" style="fill:rgb(0,255,128);stroke-width:2;stroke:rgb(255,255,255)" />
				</svg>
				<img src="res/shields.png" width="16" height="16" alt="Shields" title="Shields" /> +0
			</div>
		</div>

		<?php if (isset($spacegame['cargo'])) { ?>
			<br />
			<div class="sidebar_ship_holds">
				<strong><?php echo number_format($spacegame['cargo_volume']) . '/' . number_format($spacegame['ship']['holds']); ?></strong>
				<svg width="60" height="12">
	  				<rect width="60" height="10" style="fill:rgb(0,64,0);stroke-width:2;stroke:rgb(255,255,255)" />
  					<rect width="<?php echo (60 * $spacegame['cargo_volume'] / $spacegame['ship']['holds']); ?>" height="10" style="fill:rgb(0,255,128);stroke-width:2;stroke:rgb(255,255,255)" />
				</svg>
				<img src="res/holds.png" width="16" height="16" alt="Holds" title="Cargo Holds" />
			</div>
			<div class="sidebar_ship_goods">
				&nbsp;
				<?php
					foreach ($spacegame['cargo'] as $id => $cargo) {
						echo '<div class="sidebar_ship_good_row">';
							$good = $spacegame['goods'][$cargo['good']];

							echo '<div class="sidebar_ship_good_image">';
								echo '<img src="res/goods/' . strtolower(str_replace(' ', '_', $good['caption'])) . '.png" width="20" height="20" />';
							echo '</div>';
							
							echo '<div class="sidebar_ship_good_caption">';
								echo $good['caption'];
							echo '</div>';

							echo '<div class="sidebar_ship_good_amount">';
								echo number_format($cargo['amount']) . '<br />';
							echo '</div>';

						echo '</div>';
					}
				?>

			</div>
			
		<?php } ?>
	<?php } ?>
	<br class="clear" />
</div>
