<?php
/**
 * General information about bases
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
<div class="header2 header_bold">Bases!</div>
<div class="docs_text">
	By far the most player time will be spent flying around on bases. A
	base allows players to produce goods which are not available via 
	upgrade paths but are nevertheless in demand. One notable example is
	oil.
</div>
<div class="docs_text">
	Bases also provide a measure of protection depending on how advanced
	they are and the attention to defense.
</div>
<div class="header3 header_bold">Getting a Base</div>
<div class="docs_text">
	A <a href="docs.php?page=base_package">Base Package</a> allows you to deploy a 
	base. Then, just find a planetoid in an unprotected sector. Use the ship screen
	and enter a name for the base. The default name is: <?php echo DEFAULT_BASE_CAPTION; ?>
</div>
<div class="docs_text">
	When you deploy a base a Control Pad will be started automatically for you near
	the center. A Control Pad is a special version of the Landing Pad which cannot be
	removed and allows access to base control functions. Since the only way to land on
	a base is through a Landing Pad you won't be able to land until this Control Pad
	is finished.
</div>
<div class="header3 header_bold">Landing on a Base</div>
<div class="docs_text">
	You can land on a base only on Landing Pads. All bases will have a Control Pad
	near the center. Bases are 100x100 putting the center at 50,50 <strong>but</strong>
	when hovering you are flying over twice the area; the hover center is 25,25.
</div>


