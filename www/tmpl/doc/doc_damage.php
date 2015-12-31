<?php
/**
 * Documentation on types of damage
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
<div class="header2">Damage Types</div>
<div class="docs_text">
	There are three types of damage types which can be delivered to ships
	or forces: general damage, shield damage, and armor damage. When a
	ship runs out of armor it is destroyed and the player inside is
	ejected in an escape pod.
</div>
<div class="header3">General Damage</div>
<div class="docs_text">
	General damage will reduce shields first, then armor. This type of damage
	will come mostly from <a href="docs.php?page=ordnance">sector ordnance</a>
	such as mines and drones. 
</div>
<div class="header3">Shield and Armor Damage</div>
<div class="docs_text">
	Like the names imply, these damage types directly affect the shield and
	armor ratings on a ship. Armor cannot be damaged until shields have been
	depleted.
</div>
