<?php
/**
 * Documentation for ship weapons
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
<div class="header2 header_bold">Weapons</div>
<div class="docs_text">
	Ships are able to carry weapons to <a href="docs.php?page=damage">damage and destroy</a> other ships.
</div>
<div class="docs_text">
	Weapons are purchased from tech dealers in the racial prime and hub systems as goods and can
	be installed using the Ship Weapons page accessible from the viewport. They are installed in
	groups called Weapon Solutions. Further information below.
</div>
<div class="docs_text">
	Nearly all weapons require some form of ammunition which can be carried in the cargo
	holds. For example, laser weapons use <a href="docs.php?page=good&amp;good=energy">energy</a>.
</div>
<div class="docs_text">
	Some weapons may fire in a volley of more than one projectile or emission in a single
	attack, like a three-round burst. The ammunition requirement will increase when this
	happens; each attack still will use more units from the cargo holds.
</div>
<div class="docs_text">
	All weapons need to recharge after firing or they will be less effective. Even perfectly accurate
	weapons such as lasers may not fire at all even though ammunition is consumed. The time it takes
	to recharge is 1 second for every <?php echo round(1.0 / RECHARGE_TIME_PER_DAMAGE); ?> points of
	damage the weapon is capable of. Ships have their own recharge delay which is added to this.
	Sometimes firing at less than full charge is necessary.
</div>
<div class="header3 header_bold">Racks and Stations</div>
<div class="docs_text">
	There are two types of hardpoints available on ships for the installation of weapons,
	lighter Stations and heavier Racks. Stations are used for low recoil weapons such as
	lasers and plasma cannons while racks can hold larger launchers and projectile 
	weapons. Some weapons require attachment to more than one hardpoint.
</div>
<div class="docs_text">
	Hardpoints differ by race but there are weapons with universal attachments. All ships
	should come with at least one hardpoint except in rare circumstances, and most will
	have several.
</div>
<div class="header3 header_bold">Weapon Solutions</div>
<div class="docs_text">
	Attaching a group of weapons to their appropriate racks and stations and binding them
	to an attack button is called a Weapon Solution. You can create weapon solutions in
	the ship console, accessible from the viewport. A player can have up to 3 weapon
	solutions active at one time.
</div>
<div class="docs_text">
	To create a weapon solution you must have the weapons available in your cargo holds
	and enough racks or stations available on your ship to handle all weapons you are
	adding. You will be able to specify the sequence in which the weapons fire in the
	group. A weapon cannot be bound to more than one solution.
</div>
<div class="docs_text">
	Even though weapons in a solution fire as a group each weapon experiences its own
	recharge time. Your <a href="docs.php?page=ship_rating">Attack Rating</a> is
	computed from all solutions and weapons active on the ship.
</div>
<div class="docs_text">
	Players can have a maximum of 3 active weapon solutions active at a time. A
	solution can be dismantled back into weapons for reconfiguring but more
	importantly for changing ships. If you do not have an active
	<a href="docs.php?page=gold">Gold Membership</a> you will lose all weapon
	solutions, and just as important, the expensive weapons if you don't
	dismantle them and store them somewhere. With a Gold Membership, your weapon
	solutions are saved for <em>all</em> ships automatically. 
</div>
