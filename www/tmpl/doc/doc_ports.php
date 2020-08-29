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
<div class="header2 header_bold">
	Ports
</div>
<div class="docs_text">
	<img src="res/doc/port.png" width="400" height="174" title="Port" alt="Port" />
</div>
<div class="docs_text">
	The vast majority of trade will occur at ports. Ports will offer to buy and sell
	<a href="docs.php?page=goods">goods</a> at a price determined by supply/demand,
	distance of other traders, taxes, and inflation. The trade formulas are linear:
</div>
<pre>
  Port Buying:   L * D + ((L + 2) * D * A / M)
  Port Selling:  (L + 4) * D - (4 * D * A / M)

  L = Level of the good, 1+<br />
  D = Distance to nearest supply or demand, default/max <?php echo MAX_DISTANCE; ?><br />
  A = Amount in supply or demand, 0-<?php echo PORT_LIMIT; ?><br />
  M = Maximum supply or demand for ports, currently <?php echo PORT_LIMIT; ?><br />

  Selling a level 5 good 50 sectors to a port with 7500 demand:

    5*50+((5+2)*50*7500/15000) = 425 <img src="res/credits.png" width="16" height="16" title="Credits" alt="Credits" /> per good

  Selling L15 goods D100 to full port demand = 3200 <img src="res/credits.png" width="16" height="16" title="Credits" alt="Credits" /> per good
</pre>
<div class="docs_text">
	The numbers produced by these formulas will be modified by taxes and inflation
	before you see the final price.
</div>
<div class="docs_text">
	Since distance is a factor in both supply and demand you will often find even very
	low level goods costing a lot of credits.
</div>
<div class="header3 header_bold">
	Supply/Demand Replenishment
</div>
<div class="docs_text">
	Ports will recover their supply and demand levels at a rate of <?php echo GOODS_PER_UPDATE; ?>
	every <?php echo PORT_UPDATE_TIME; ?> seconds.	
</div>
<div class="header3 header_bold">
	Upgrading Ports
</div>
<div class="docs_text">
	<img src="res/doc/upgrades.png" width="500" alt="Upgrades Example" title="A two-good upgrade requirement." />
</div>
<div class="docs_text">
	Ports may have new industry options which you may upgrade to. You may view the options
	on the goods page.
</div>