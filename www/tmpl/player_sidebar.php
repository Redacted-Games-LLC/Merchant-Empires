<?php
/**
 * Right side player information bar
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
?>
<div class="viewport_player_info">
	<br />
	<?php
		if ($spacegame['player']['unread_messages'] > 0) {
			?>
			<div class="sidebar_messages" onclick="return open_message(1)">
				UNREAD MESSAGES
			</div>
			<?php
		}
	?>
	<br />
	<div class="sidebar_race">
		<?php echo $spacegame['races'][$spacegame['player']['race']]['caption']; ?>
	</div>
	<div class="sidebar_name">
		<?php
			if ($spacegame['gold']) {
				echo '<img src="res/gold.png" alt="Gold" title="Gold Member" height="20" />&nbsp;';
			}

			echo '<a href="#" onclick="open_player('. $spacegame['player']['record_id'] .')">';
			echo '<span class="normal" style="font-size: 80%;">';
			echo $spacegame['player']['caption'];
			echo '</span>';
			echo '</a>';
		?>
	</div>
	<div class="sidebar_turns">
		<strong><?php echo str_replace('.50', '.5', str_replace('.00', '', (string)number_format($spacegame['player']['turns'], 2))); ?></strong> Turns Remaining
	</div>
	<div class="sidebar_credits" title="Credits Remaining">
		<strong><?php echo number_format($spacegame['player']['credits']); ?></strong> <img src="res/credits.png" width="13" alt="Credits" />
	</div>
	<br class="clear" />
	<div class="sidebar_level" title="<?php echo 'Experience: ' . number_format($spacegame['player']['experience']) . " \nAlignment: " . $spacegame['player']['alignment']; ?>">
		<div class="sidebar_level_item">
			<?php
				echo $spacegame['player']['level'];

				$nl = $spacegame['player']['level'];
				$this_experience = 2000 * $nl * $nl * $nl; 

				$nl += 1;
				$next_experience = 2000 * $nl * $nl * $nl; 

				$progress = ($spacegame['player']['experience'] - $this_experience) / ($next_experience - $this_experience);
			?>
		</div>
		<div class="sidebar_level_item">
			<svg width="95" height="20">
  				<rect width="95" height="10" style="fill:rgb(0,0,64);stroke-width:2;stroke:rgb(255,255,255)" />
  				<rect width="<?php echo ($progress * 95); ?>" height="10" style="fill:rgb(0,255,128);stroke-width:2;stroke:rgb(255,255,255)" />
			</svg>
		</div>
		<div class="sidebar_level_item">
			<?php echo $spacegame['player']['level'] + 1; ?>
		</div>
	</div>
	<div class="sidebar_experience">
		<?php
			if ($spacegame['player']['experience'] >= $next_experience) {
				echo '<a href="handler.php?task=level&amp;form_id='. $_SESSION['form_id'] .'">Level Up</a>';
			}
			else {
				echo number_format($next_experience - $spacegame['player']['experience']);
				echo ' Points Left';
			}
		?>
	</div>
</div>

