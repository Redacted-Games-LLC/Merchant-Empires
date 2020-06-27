<?php
/**
 * Documentation on navigation principals 
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
<div class="header2">Navigation</div>
<div class="docs_text">
	This page will attempt to explain the general concepts of navigation to you.
</div>
<div class="docs_text">
	Note that when you start the game you will be in an Escape Pod. Your navigation will
	be severely limited. Please read through this page anyway for once inside a ship it
	will become immediately useful.
</div>
<div class="docs_text">
	The <a href="docs.php?page=quick">Quick Start Guide</a> contains a tutorial which
	combines some features of trade, navigation and warps.
</div>
<div class="header3">Sector Numbers</div>
<div class="docs_text">
	This edition of Merchant Empires has a static galaxy spanning <strong>000 000</strong>
	to <strong>999 999</strong>. You may fly about anywhere in this range by clicking the
	sector numbers in the nav window:
</div>
<div class="docs_text">
	<img src="res/doc/nav_buttons.png" width="300" alt="nav button" />
</div>
<div class="docs_text">
	Things you see in this image are:
	<ul>
		<li><img src="res/solar_scan.png" width="16" alt="solar scan" />Sun</li>
		<li><img src="res/planetoid_scan.png" width="20" alt="planetoid scan" />Planetoid</li>
		<li><img src="res/warp.png" width="20" alt="warp" />Warp</li>
		<li><img src="res/government_scan.png" width="20" alt="government scan" />Imperial Government Protected Sector</li>
	</ul>
</div>
<div class="docs_text">
	Your <strong>horizontal position</strong> increases from left to right while your
	<strong>vertical position</strong> increases from bottom to top. That is, flying
	<em>up and right</em> is the opposite of <em>down and left</em>. When you move this
	navigation panel will update with different sector numbers.
</div>
<div class="docs_text">
	The center sector number is your current location. If you click on this you will just
	refresh the page. Depending on your browser this may be a slightly faster refresh than
	hitting F5.
</div>
<div class="docs_text">
	The other surrounding sectors represent the 8 directions you may move in. When you
	click on these you will use up some <a href="docs.php?page=turns">turns</a> and
	hopefully move to the clicked-on sector uneventfully.
</div>
<div class="header3">Galaxy Structure</div>
<div class="docs_text">
	The galaxy is centered at <strong>500 500</strong> with the Zycklirg to Northwest,
	Mawlor to the East, and Xollian to Southwest. Currently it looks as follows:
</div>
<div class="docs_text">
	<img src="res/galaxy.png" width="600" alt="galaxy" />
</div>
<div class="docs_text">
	Each race has one <em>Prime System</em> and two <em>Hub Systems</em> which serve as
	neutral embassies for the other two remaining races. These systems are connected
	smartly through a warp network established by an old race called the Paragon. The
	remaining systems surround these prime systems and provide places for the players
	to establish their own territories.
</div>
<div class="header3">Deep Space Scanner</div>
<div class="docs_text">
	Between the viewport navigation panel and the scanner is the Deep Space Scanner.
	Centuries ago this device was regulated to the point where only wealthy traders
	could afford it. Some of these ancients used it to gain a significant advantage 
	over others. For this reason it became standard equipment on all craft.
</div>
<div class="docs_text">
	The DSS is imperative if you accidentally move outside of the system you were
	trading in. Just use your DSS to move back towards the star and you will find
	the system again.
</div>
<div class="docs_text">
	Note the DSS is not related to the regular Scanner. The DSS looks like this:
</div>
<div class="docs_text">
	<img src="res/doc/dss.png" width="300" alt="dss" />
</div>
<div class="docs_text">
	The red dot on the DSS is someone who <em>could</em> be hostile, but also might
	not be. <strong>Stay in protected sectors until you can handle an encounter with
	a potentially hostile player.</strong> Protected sectors have a little green star
	in the corner which looks like this: <img src="res/government_scan.png" width="14" height="14" alt="government scan" />
</div>
<div class="docs_text">
	The DSS will also show you allied players in cyan. You can also see protected
	sectors (they have the extra circle around them) and warp paths which can be
	followed if you are lost.
</div>
<div class="header3">Warps</div>
<div class="docs_text">
	<img src="res/doc/warp.png" width="300" alt="warp" />
</div>
<div class="docs_text">
	Long ago a race of scientists known as the Paragon discovered the secret to
	artificial wormholes by "stretching" a created gravity well from the inside
	of a star. They used large, powerful ships channeling incredibly dense magnetic
	fields to nudge these wormholes all towards various government sectors leaving
	a useful warp network.
</div>
<div class="docs_text">
	The nature of warps means they only work in one direction; entering one will take
	you to the star of the destination system, not where a potential return warp would
	be. The Paragon made sure to put the return warp within a few sectors, though.
</div>
<div class="docs_text">
	Warps require <?php echo WARP_TURN_MULTIPLIER; ?> times the normal ship turns per
	sector to use.
</div>
<div class="header4">Mawlor Connection</div>
<div class="docs_text">
	Shortly after the original warp network was completed the Paragon announced plans
	for a ring network circling the galaxy. Due to heavier than normal hostilities between
	Mawlor and Zyck'lirg it was decided to start the network in Xollian space. The Mawlor,
	claiming spying then treason massacred every last Paragon they could find.
</div>
<div class="docs_text">
	The technology used to create new artificial wormholes was never fully recovered but
	the Mawlor were eventually able to derive jump technology, and to this day have kept
	it out of the hands of the other races.
</div>
<div class="header3">Bases</div>
<div class="docs_text">
	In this version of Merchant Empires you can fly over <a href="docs.php?page=bases">Bases</a>
	just as you can fly around a solar system. When you click on a base on a planet you will
	<em>hover</em> over it and can move around above the base. Once you find a <em>landing
	pad</em> which you are authorized to use you can "land" and navigate the surface.
</div>
<div class="docs_text">
	Bases are 100 by 100 sectors 0,0 - 99,99 centered at 50,50. When hovering you cover twice
	the distance, that is 0,0 - 49,49 centered at 25,25.
</div>
<div class="docs_text">
	When interacting with a base the base sectors will sit over the viewport. You can get rid
	of the hover screen by clicking the viewport nav buttons. Likewise when landed you can
	take off to any of the 8 sectors around a base by clicking the sector. <strong>Warning:</strong>
	You can still be attacked from space when hovering!
</div>
<div class="docs_text">
	<img src="res/doc/base_hover.png" width="420" alt="Hover example" title="Hovering over a base" />
</div>
<div class="docs_text">
	Hovering over a base will cost <?php echo BASE_HOVER_TURN_MULTIPLIER; ?> times the normal
	ship TPS and landing will cost <?php echo BASE_LAND_TURN_MULTIPLIER; ?> times. Taking off
	will cost <?php echo BASE_TAKEOFF_TURN_MULTIPLIER; ?> times the number of turns.
</div>
<div class="header4">Landing</div>
<div class="docs_text">
	Whenever you are hovered over a <em>Landing Pad</em> you will be given the option to land.
	All bases have a special type of landing pad called a <em>Control Pad</em> near the center,
	so look around 25,25 in hover.
</div>
<div class="docs_text">
	<img src="res/doc/base_land.png" width="300" alt="base landing" />
</div>
<div class="docs_text">
	Once centered over the landing pad a link will appear to the right of the base navigation
	grid which allows you to land. You should appear near the center of the pad.
</div>