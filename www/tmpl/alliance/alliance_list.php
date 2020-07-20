<?php
/**
 * Shows a list of alliances known to the game.
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
<div class="header2 header_bold">Alliance List</div>
<?php 

	if ($spacegame['alliances_count'] <= 0) {
		echo '<div class="docs_text">';
		echo 'There are no known alliances in the game.';
		echo '</div>';
	}
	else {

		foreach ($spacegame['alliances'] as $alliance_id => $alliance) { 
?>

			<div class="alliance_list align_left">
				<div class="alliance_list_item align_left">
					<a href="alliance.php?page=members&amp;alliance_id=<?php echo $alliance_id; ?>">
						<?php echo $alliance['caption']; ?>
						<?php if ($alliance['recruiting'] > 0) { ?>
							<img class="alliance_list_recruit_image" src="res/yes.png" alt="Recruiting!" title="Accepting new members" />
						<?php } else { ?>
							<img class="alliance_list_recruit_image" src="res/no.png" alt="Not Recruiting." title="Not accepting new members" />
						<?php } ?>
					</a>
				</div>
			</div>
<?php
		}
	}
?>	