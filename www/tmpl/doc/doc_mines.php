<?php
/**
 * Information about mines and their use
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
<div class="header2 header_bold">
	Mines
</div>
<div class="docs_text">
	Mines are used to make sector passage dangerous to non-allied players. Unlike previous ME
	versions mines <strong>do not trap</strong>; they simply explode.
</div>
<div class="docs_text">
	A player can safely enter a mined sector but may trigger some of them to explode when 
	attempting to leave. There is no other way to back out so it is advisable for a player to
	use a <a href="docs.php?page=scanner">scanner</a> where possible to avoid entering sectors
	with hostile forces.
</div>
<div class="docs_text">
	There can be up to <?php echo MAX_ORDNANCE_PER_SECTOR; ?> mines in a sector. Each player
	can have a stack of up to <?php echo MAX_ORDNANCE_PER_PLAYER; ?> mines, and there is an
	<?php echo MINE_HIT_PERCENT; ?>% Percent chance a player will hit one of those stacks. If
	a stack is hit, up to <?php echo (MINES_ATTACKING_PER_PLAYER * 100); ?>% of the stack can
	hit the player. Finally, each mine does <?php echo MINE_ATTACK_DAMAGE; ?> points of 
	<a href="docs.php?page=damage">general damage</a>.
</div>
<div class="docs_text">
	Once mines are deployed they cannot be picked up. However, they are unstable enough on
	their own to be destroyed easily by any sort of weapon blast. Since targetting is 100%
	efficient you can destroy as many in one shot as weapons installed on your ship.
</div>
<div class="header3 header_bold">
</div>
<div class="docs_text">
</div>
