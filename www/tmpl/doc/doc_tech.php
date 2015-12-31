<?php
/**
 * 
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
<div class="header2">Ship Technology</div>
<div class="docs_text">	
	Tech dealers provide goods solar collectors, oil platforms, <a href="docs.php?page=ordnance">ordnance</a>,
	port packages, shields and armor.
</div>
<hr />
<div class="header3">Solar Collectors</div>
<div class="docs_text">	
	Solar collectors are a type of dealer which provides very cheap energy harvested from a
	star. They charge only one 1 <img src="res/credits.png" width="16" height="16" alt="¢" title="Credits" />
	per energy unit before inflation and taxes to cover maintenance. They regenerate at
	<?php echo GOODS_PER_UPDATE; ?> units per <?php echo PORT_UPDATE_TIME; ?> seconds.
</div>
<div class="docs_text">	
	Solar Collectors can be purchased from Technology Dealers present in protected systems
	throughout the galaxy. Up to <?php echo SOLAR_COLLECTORS_PER_SECTOR; ?> collectors can
	be deployed per star.
</div>
<div class="docs_text">	
	Every Imperial Government protected system already has at least one solar collector
	deployed.
</div>
