<?php
/**
 * Information about drones and their capabilities.
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
	Drones
</div>
<div class="docs_text">
	Drones report on ship movement and/or attack non-allied ships on sight.
</div>
<div class="docs_text">
	A single drone deployed by a player will act as a scout and report when a hostile
	ship enters the sector. It will not attack in any case.
</div>
<div class="docs_text">
	More than one drone in a sector will automatically become lethal and attempt to
	attack hostile ships when they enter the sector. They will also return fire if
	they are fired upon. 
</div>
<div class="docs_text">
	There can be up to <?php echo MAX_ORDNANCE_PER_SECTOR; ?> drones in a sector. Each player
	can have a stack of up to <?php echo MAX_ORDNANCE_PER_PLAYER; ?> drones, and every hostile
	stack will contribute towards attacking a player if there are enough drones in it. Up to
	<?php echo (DRONES_ATTACKING_PER_PLAYER * 100); ?>% of the stack can fire upon a player at
	once doing <?php echo DRONE_ATTACK_DAMAGE; ?> points of 
	<a href="docs.php?page=damage">general damage</a> each.
</div>
<div class="docs_text">
	Drones are designed to be as cheap and disposable as possible while retaining a high
	reliability. For instance they are built without a structural frame; the internals are
	coupled together with a single shield generated <em>under</em> the various hull pieces.
	One shot of any type of weapon will disable this setup and the drone literally falls
	apart in space.
</div>
<div class="docs_text">
	Drones can be retrieved by their owner but will carry out firing orders up until they
	are docked. This along with the travel time of weapon blasts can lead some damage reports
	to arrive after drones have been retrieved.
</div>