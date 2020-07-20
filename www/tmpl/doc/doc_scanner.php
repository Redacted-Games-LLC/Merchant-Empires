<?php
/**
 * Documentation for the viewport scanner
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
<div class="header2 header_bold">Scanner</div>
<div class="docs_text">
	The scanner is an item which identifies hostile forces and ships in the immediate
	area. The data returned is minimal but directly fed to the viewport allowing further
	action to be taken.
</div>
<div class="docs_text">
	The scanner looks like the following image:
</div>
<div class="docs_text">
	<img src="res/doc/scanner.png" alt="Example of scanner features" title="Features of the scanner" width="240" />
</div>
<div class="docs_text">
	<ul>
		<li>Hostile Forces - a row of 5 small red pentagons in the upper part of a sector.</li>
		<li>Allied Forces - a row of 5 small blue pentagons in the lower part of a sector.</li>
		<li>Hostile Ship - a big red pentagon in the middle left.</li>
		<li>Allied Ship (not shown) - a big blue pentagon in the middle right.</li>
		<li>Scan Link - click here to perform an active scan for a cost of <?php echo SCAN_TURN_COST; ?> turn(s).</li>
		<li>Empty sector shown for comparison.</li>
	</ul>
</div>
<div class="docs_text">
	<strong>WARNING: </strong> Members of the <a href="docs.php?page=xollian">Xollian</a>
	race possess ships with cloak technology. If a Xollian has a higher level than you
	and have their cloak active you <em>will not</em> see their pentagon on the scanner.
	<a href="docs.php?page=mawlor">Mawlors</a> can also learn to fly Xollian ships.
</div>
<div class="header3 header_bold">Current Sector Telemetry Data</div>
<div class="docs_text">
	The installed scanner also provides a list of forces and ships in the current sector
	and enough telemetry data to interact or attack or attack them. For ships you will
	also have access to player name and alliance but for forces you'll have to perform
	an active scan to see who owns them.
</div>
<div class="header3 header_bold">Active Scan</div>
<div class="docs_text">
	To perform an active scan, click on the sector number in the scanner. A popup will
	appear detailing the forces found. You can do this for your current sector or any
	of the eight surrounding sectors.
</div>