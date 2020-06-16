<?php
/**
 * Quick start information for impatient players
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
<div class="header2">Quick Start Guide</div>
<div class="docs_text">
	If you just want to get going, then here is a quick start sheet for you. Once
	you get your turns burned down to 1000, go ahead and come for the rest of the
	manual. Make sure you are in a safe location though!
</div>
<div class="header3">Browser Stuff</div>
<div class="docs_text">
	This is a web browser game, so you play by clicking links. The server will
	process your request and update your viewport. You don't need any plugins
	or downloads, just a modern browser. For nostalgia, the old click, process,
	redirect is intended and you might get funky results if you use the back
	button.
</div>
<div class="header3">Joining a Game</div>
<div class="docs_text">
	After signing up, you can link up to 4 players with your account. How many
	players you run will depend on the life commitments you have, but to start
	out, just make one, picking a name and <a href="docs.php?page=races">race</a>.
	If in a hurry, pick Zyck'lirg for early round advantage, Mawlor for late
	round advantage, and Xollian for a balanced round. The current round began
	<?php 

		$round_so_far = ceil((PAGE_START_TIME - START_OF_ROUND) / 86400);
		$round_left = ceil((END_OF_ROUND - PAGE_START_TIME) / 86400);

		echo $round_so_far . ' day' . ($round_so_far == 1 ? '' : 's');
		echo ' ago and will end ';
		echo $round_left . ' day' . ($round_left == 1 ? '' : 's');
		echo ' from now.';


	?>
</div>
<div class="docs_text">
	You will spawn in an escape pod with no scanner or access to ports. You must
	click on the sector numbers above the green arrow to navigate to a ship
	dealer and get your first starter ship. 
</div>
<div class="header4">Viewport</div>
<div class="docs_text">
	Once you are in a real ship, your viewport will come alive.
</div>
<div class="docs_text">
	<strong>The Radar:</strong> The green, spinning radar on the right side
	of the viewport shows you whether are there any hostile forces in the
	adjacent sectors. Check your radar before moving, refresh if necessary.
</div>
<div class="docs_text">
	<strong>Nav Controls:</strong> The navigation panel on the left side of
	the viewport can be clicked to move to another sector. You already know
	to look at the scanner for hostile (red) forces right? 
</div>
<div class="docs_text">
	<img src="res/government_scan.png" />Green stars mean safety! When you
	move, make sure the sector has a green star in it and you can't be
	attacked. Some players make a great living never leaving these zones!
</div>
<div class="header3">Making Money</div>
<div class="docs_text">
	When trading, seek out a <a href="docs.php?page=port">port</a> with a high value demand:
</div>
<div class="docs_image">
	<img src="res/doc/high_demand.png" alt="Example of a high value demand" title="Example of a high value demand" />
</div>
<div class="docs_text">
	When you find one, make sure to write down the sector coordinates and note the system you
	are in. Without this information you will quickly become lost:
</div>
<div class="docs_image">
	<img src="res/doc/location.png" alt="Your location" title="Your location" />
</div>
<div class="docs_text">
	<strong>Warning:</strong> these example images were taken from a round which may have a 
	different randomly generated galaxy; the sector numbers might not match your game.
</div>
<div class="docs_text">
	Now, 81 sectors is a long way to travel. Maybe there is a <a href="docs.php?page=warps">faster way</a>
	to get there. Click on the NAV button and see if there is a warp close to that distance:
</div>
<div class="docs_image">
	<img src="res/doc/demand_warp.png" alt="Finding a warp" title="Sometimes it is not this easy to find." />
</div>
<div class="docs_text">
	Currently we are in the Zyck-Xoll Hub system and we need to get to the Xoll-Mawl Hub
	system. If you check out the docs on <a href="docs.php?page=warps">warps</a>, you will
	note that to get there, we have to travel through Xoll-Zyck. You could take the long
	way around, via Zyck-Mawl and Mawl-Xoll hubs, but that's a waste of turns.
</div>
<div class="docs_text">
	Warps are near the center of the solar system. If you recall, back in early celestial
	<a href="docs.php?page=navigation">navigation</a>, we were taught about terminators,
	and could tell the position of a star just by looking at one of the planets that
	orbit it:
</div>
<div class="docs_image">
	<img src="res/doc/planet_shadow.png" alt="Planet shadow" width="256" title="The shadow on the planet shows which direction to the star." />
</div>
<div class="docs_text">
	Warps can be up to <?php echo (WARP_LOCATION_VARIANCE + 1); ?> units away from a star.
	In a binary system, this could mean a lot of search area. For our search, we find two
	warps near the stars, but the warp we actually need is out by itself:
</div>
<div class="docs_image">
	<img src="res/doc/finding_warps.png" alt="Planet shadow" title="The shadow on the planet shows which direction to the star." />
</div>
<div class="docs_text">
	Passing through the warp, we come face-to-face with a return warp to a different system.
	<br><strong>WARNING:</strong> warps are uni-directional, that is they are one-way only.
	If you accidentally warp and find yourself facing another warp, it may <em>not</em> take
	you where you want to go to. In this case, the apparent return warp would actually take us
	to Xollian Prime:
</div>
<div class="docs_image">
	<img src="res/doc/wrong_warp.png" alt="Wrong warp" title="Apparent return warp actually goes somewhere else." />
</div>
<div class="docs_text">
	We want to make sure we know to find the proper warp to return to. The good news is that
	in this hub system, all three warps are close to each other (prime systems only have two warps): 
</div>
<div class="docs_image">
	<img src="res/doc/all_warps.png" alt="All system warps" title="All three warps in this hub system are close to the star." />
</div>
<div class="docs_text">
	Arriving in Xoll-Mawl, all we can do is look around for the charcoal supplier. While we
	look, we should also check to see if their are other high-credit demands. Oh? What do
	we have here? Houston, we have a problem:
</div>
<div class="docs_image">
	<img src="res/doc/no_traders.png" alt="No Traders" title="This port does not have any nearby traders." />
</div>
<div class="docs_text">
	If we were in the right spot, this port would offer a price for charcoal and it would be
	very low, telling us there is a provider within a couple of sectors. In this case, we are
	told that there are no traders. This could only mean the supplier is further from this port
	than the maximum distance index of <?php echo MAX_DISTANCE; ?>.
</div>
<div class="docs_text">
	The answer is in our original warp search:
</div>
<div class="docs_image">
	<img src="res/doc/demand_warp.png" alt="Finding a warp" title="Sometimes it is not this easy to find." />
</div>
<div class="docs_text">
	The system containing our charcoal <u>has a warp</u> to where we currently are, so
	Xoll-Mawl isn't necessarily the provider we are looking for, and if it had charcoal for
	sale, it would have been a good deal being so close to that other demand.
</div>
<div class="docs_text">
	Our next step will be to take the warps from Xoll-Mawl and try to find our good in one of
	those systems. It doesn't take long to find out that the Xollian Prime system has what we
	are looking for:
</div>
<div class="docs_image">
	<img src="res/doc/found_seller.png" alt="Found supplier" title="Found a charcoal supplier" />
</div>
<div class="docs_text">
	The price is cheap as well, so there must be a nearby demand. We have room for 86 units so
	let's buy what we can for a total 1,548&nbsp;<img src="res/credits.png" width="15" />.
	Don't forget to note the location of the supplier, and remember, this is not the same round
	you are playing so it won't match the live game:
</div>
<div class="docs_image">
	<img src="res/doc/destination.png" alt="Destination" title="Location of a charcoal supplier" />
</div>
<div class="docs_text">
	If you want to, you can fly around and find other high-credit demands in the system so that the next time
	you come out here, you can bring the goods with you. Take note of the location information when
	you do find them. Head back to sell your charcoal when ready.
</div>
<div class="docs_text">
	Remember when we passed the warp to Xollian Prime earlier?
</div>
<div class="docs_image">
	<img src="res/doc/wrong_warp.png" alt="Right warp afterall" title="Apparently this was the warp we were looking for" />
</div>
<div class="docs_text">
	Turns out we don't have to travel back through Xoll-Mawl Hub. If we want to get to Zyck-Xoll
	we can just take the warp to Xoll-Zyck. There are only two warps in Xollian Prime so it should
	be easy to find. The final warp in Xoll-Zyck Hub should also be easy for you to find now.
</div>
<div class="docs_text">
	Back at our demand we see that we spent long enough looking for a supplier for inflation to
	affect the cost:
</div>
<div class="docs_image">
	<img src="res/doc/inflation_effects.png" alt="Inflation effects" title="Inflation increases fairly quickly." />
</div>
<div class="docs_text">
	We can expect 65,876&nbsp;<img src="res/credits.png" width="15" /> for this transaction, with
	a cost basis of 1,548&nbsp;<img src="res/credits.png" width="15" />, leaving a net of
	64,328&nbsp;<img src="res/credits.png" width="15" /> before taxes.
</div>
<div class="docs_text">
	This trade route is going to run out quickly, though. Before we made the sale, there was
	a demand for 400 units and now, look:
</div>
<div class="docs_image">
	<img src="res/doc/upgrade_demand.png" alt="Upgrade Demand" title="Demand levels for port upgrades work a bit differently." />
</div>
<div class="docs_text">
	The demand went from -400 to -314, and the price dropped 4&nbsp;<img src="res/credits.png" width="15" />
	per unit.
</div>
<div class="docs_text">
	Port local demands, that is for goods that go for sale to the planetary population, will
	regularly crawl back up but Port <em>upgrade</em> demands are tied to port trade only. Our
	port wants to sell <img src="res/goods/explosives.png" width="14" /> Explosives, but needs
	<img src="res/goods/niter.png" width="14" /> Niter and <img src="res/goods/charcoal.png" width="14" />
	Charcoal. We can provide Charcoal, but we don't know of any Niter suppliers. Until we also
	supply this port with Niter <strong>and</strong> find a buyer for Explosives, this trade
	route won't provide us credits for very long.
</div>
<div class="docs_text">
	The <a href="docs.php?page=trade">trade page</a> will go further in-depth, but your goal
	will be to upgrade planets far away from each other to maximize your profits. You'll want
	strong, two-way trade routes. Upgraded goods are worth more than low-level goods, and the
	further apart the better.
</div>
<div class="docs_text">
	Have fun! Remember this game is grindy by nature.
</div>