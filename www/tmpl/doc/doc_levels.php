<?php
/**
 * Documentation for experience and levels
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
<div class="header2">Experience and Levels</div>
<div class="docs_text">
	You gain experience for the following activities:
</div>
<div class="docs_text">
	<ul>
		<li>1 point for every 5 goods sold to a port trade demand multiplied by the good level, rounded down. That is 100 L2 goods is 40 xp.</li>
		<li>1 point for every 4 goods sold to a port upgrade demand multiplied by the good level, rounded down. That is 100 L2 goods is 50 xp.</li>
		<li>5% of the experience of <em>enemy</em> players killed, enemy being non-racial or non-allied.</li>
	</ul>
</div>
<div class="docs_text">
	You cannot lose experience.
</div>
<div class="header3">Levels</div>
<div class="docs_text">
	You start the game at level 0. After certain amounts of experience (listed in the table below)
	you will have the option of leveling up your player. There is no immediate requirement to level
	up, experience will continue to collect. When you finally do click the <strong>Level</strong>
	Up link you will only gain one level at a time.
</div>
<div class="docs_text">
	Levels are computed using the following formulae:
</div>
<pre>
	experience = 2000 * level ^ 3
	level = experience^(1/3) / (10 * 2 ^ (1/3))
</pre>
<div class="docs_text">
	Here is a table of the levels:
	<script type="text/javascript">draw_level_table();</script>
</div>
<div class="header4">Levels and Protection</div>
<div class="docs_text">
	When inside protected sectors designated with the icon <img src="res/government_scan.png" width="20" height="20" alt="Green Government Star" title="Government Star" />
	a player with low <a href="docs.php?page=ship_rating">ship attack rating</a> is protected
	from combat actions. Your level raises your attack rating by <?php echo ATTACK_RATING_PER_LEVEL; ?>
	points per level.
</div>
