<?php
/**
 * Left-side menu for game docs.
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

<div class="header4 header_bold"><a href="docs.php?page=main">Game Documentation</a></div>
<hr noshade="noshade" size="1" />
<ul class="docs_menu">
	<li class="docs_menu"><a href="docs.php?page=quick">Quick Start</a></li>
	<li class="docs_menu">
		<a href="docs.php?page=players">Players
		<ul class="docs_menu">
			<li class="docs_menu"><a href="docs.php?page=turns">Turns</a></li>
			<li class="docs_menu"><a href="docs.php?page=levels">Experience and Levels</a></li>
			<li class="docs_menu"><a href="docs.php?page=alignment">Alignment and Ranks</a></li>
			<li class="docs_menu"><a href="docs.php?page=alliance">Alliances</a></li>
			<li class="docs_menu">
				<a href="docs.php?page=races">Races</a>
				<ul class="docs_menu">
					<li class="docs_menu"><a href="docs.php?page=xollian">Xollian</a></li>
					<li class="docs_menu"><a href="docs.php?page=mawlor">Mawlor</a></li>
					<li class="docs_menu"><a href="docs.php?page=zycklirg">Zyck'lirg</a></li>
				</ul>
			</li>	
		</ul>
	</li>
	<li class="docs_menu">
		<a href="docs.php?page=ships">Ships</a>
		<ul class="docs_menu">
			<li class="docs_menu">
				<a href="docs.php?page=nav">Navigation</a>
				<ul class="docs_menu">
					<li class="docs_menu"><a href="docs.php?page=warps">Warps</a></li>
				</ul>
			</li>
			<li class="docs_menu"><a href="docs.php?page=scanner">Scanner</a></li>
			<li class="docs_menu">
				<a href="docs.php?page=trade">Trade</a>
				<ul class="docs_menu">
					<li class="docs_menu"><a href="docs.php?page=ports">Ports</a></li>
					<li class="docs_menu"><a href="docs.php?page=goods">Trade Goods</a></li>
				</ul>
			</li>
			<li class="docs_menu">
				<a href="docs.php?page=ordnance">Sector Ordnance</a>
				<ul class="docs_menu">
					<li class="docs_menu"><a href="docs.php?page=mines">Mines</a></li>
					<li class="docs_menu"><a href="docs.php?page=drones">Drones</a></li>
				</ul>
			</li>
			<li class="docs_menu">
				<a href="docs.php?page=combat">Combat</a>
				<ul class="docs_menu">
					<li class="docs_menu"><a href="docs.php?page=ship_rating">Attack / Defense Rating</a></li>
					<li class="docs_menu"><a href="docs.php?page=damage">Damage Types</a></li>
					<li class="docs_menu"><a href="docs.php?page=weapons">Weapons</a></li>
				</ul>
			</li>
			<li class="docs_menu"><a href="docs.php?page=tech">Ship Technology</a></li>
		</ul>
	</li>
	<li class="docs_menu">
		<a href="docs.php?page=bases">Bases</a>
		<ul class="docs_menu">
			<li class="docs_menu"><a href="docs.php?page=base_package">Package Deployment</a></li>
			<li class="docs_menu"><a href="docs.php?page=base_defense">Defense</a></li>
			<li class="docs_menu"><a href="docs.php?page=base_sharing">Alliance Use</a></li>
			<li class="docs_menu">
				<a href="docs.php?page=construction">Construction</a>
				<ul class="docs_menu">
					<li class="docs_menu"><a href="docs.php?page=buildings">Buildings and Tiles</a></li>
				</ul>
			</li>
			<li class="docs_menu">
				<a href="docs.php?page=research">Research</a>
				<ul class="docs_menu">
					<li class="docs_menu"><a href="docs.php?page=labs">Laboratory Types</a></li>
					<li class="docs_menu">
						<a href="docs.php?page=research_goals">Goals</a>
						<ul class="docs_menu">
							<li class="docs_menu"><a href="docs.php?page=combat_goals">Combat</a></li>
							<li class="docs_menu"><a href="docs.php?page=trade_goals">Trade</a></li>
						</ul>
					</li>
				</ul>
			</li>

		</ul>
	</li>
	<li class="docs_menu">
		<a href="docs.php?page=policy">Game Policies</a>
		<ul class="docs_menu">
			<li class="docs_menu"><a href="docs.php?page=privacy">Privacy</a></li>
			<li class="docs_menu"><a href="docs.php?page=bots">Bots</a></li>
			<li class="docs_menu"><a href="docs.php?page=terms">Terms of Use</a></li>
		</ul>
	</li>
	<li class="docs_menu">
		<a href="docs.php?page=contributing">Contributing</a>
		<ul class="docs_menu">
			<li class="docs_menu"><a href="docs.php?page=gold">Gold Membership</a></li>
		</ul>
	</li>
	<li class="docs_menu"><a href="docs.php?page=hosting">Downloading and Hosting</a></li>
	<li class="docs_menu"><a href="docs.php?page=license">License</a></li>
	<li class="docs_menu"><a href="docs.php?page=roadmap">Roadmap</a></li>
	<li class="docs_menu"><a href="docs.php?page=credits">Credits</a></li>
	
</ul>