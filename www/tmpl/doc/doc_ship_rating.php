<?php
/**
 * Documentation for ship attack/defense rating.
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
<div class="header2">Attack / Defense Rating</div>
<div class="docs_text">	
	Ships are rated as to their attack and defense capabilities. A high-level
	ship with strong weapons will have a higher attack capability than an
	unarmed low-level ship.
</div>
<div class="docs_text">
	The minimum attack:defense rating is 1:1, and you will see it given in that
	form in the game viewport.
</div>
<div class="header3">Attack Rating Contributors</div>
<div class="docs_text">
	The following things will contribute to an attack rating.
</div>
<div class="header4">
	Player Level
</div>
<div class="docs_text">
	Every player level adds <?php echo ATTACK_RATING_PER_LEVEL; ?> to the attack
	rating.
</div>
<div class="header3">Defense Rating Contributors</div>
<div class="docs_text">
	The following things will contribute to a defense rating.
</div>
<div class="header4">
	Armor and Shields
</div>
<div class="docs_text">
	Every armor adds <?php echo DEFENSE_RATING_PER_ARMOR; ?> and every shield adds
	<?php echo DEFENSE_RATING_PER_SHIELD; ?> to the defense rating.
</div>