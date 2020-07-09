<?php
/**
 * Documentation for alignment and ranks.
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
	include_once('inc/ranks.php');

?>
<div class="header2 header_bold">Alignment</div>
<div class="docs_text">
	The three <a href="docs.php?page=races">races</a> of the galaxy are at
	war with each other. Your trading and combat actions will adjust your
	<em>alignment</em> as you play; actions supporting your own race will
	generally raise alignment, while against your race will generally lower
	it.
</div>
<div class="docs_text">
	Alignment is given to or taken from a player every <?php echo ALIGNMENT_UPDATE_TIME; ?>
	seconds when online. Actions you have performed are tallied up, computed,
	and rounded down to a whole number. The final result modifies the player's
	alignment.
</div>
<div class="docs_text">
	The range is <strong>-<?php echo ALIGNMENT_LIMIT; ?></strong> to
	<strong>+<?php echo ALIGNMENT_LIMIT; ?></strong>. Benefits and malefits of non-zero
	alignment are explained later in this page. 
</div>
<div class="docs_text">
	Alignment has nothing to do with <a href="docs.php?page=alliance">alliances</a>;
	you will still lose alignment for killing unallied players of your race and so
	forth.
</div>
<div class="header3 header_bold">Trade</div>
<div class="docs_text">
	Every single good you trade at a port is counted. Trading <?php echo number_format(TRADES_PER_ALIGNMENT_POINT); ?>
	goods in your own racial ports will give you one (1) point, while trading
	<?php echo number_format(WAR_TRADES_PER_ALIGNMENT_POINT); ?> goods in enemy ports will remove
	one (1) alignment point.
</div>
<div class="docs_text">
	Upgrading <?php echo number_format(UPGRADES_PER_ALIGNMENT_POINT); ?> goods in racial ports
	will give 1 point, but only <?php echo number_format(WAR_UPGRADES_PER_ALIGNMENT_POINT); ?>
	goods are needed in enemy ports to cost you 1 point.
</div> 
<div class="header3 header_bold">Combat</div>
<div class="docs_text">
	If you kill someone of another race you gain alignment equal to the level of
	the opponent minus <?php echo MINIMUM_KILLABLE_LEVEL; ?>. Killing members of
	your own race will take <?php echo RACIAL_KILL_PENALTY; ?> points each.
</div>
<div class="header3 header_bold">Benefits of Alignment</div>
<div class="docs_text">
	You get a discount on the final price of ships of your own race. Every 10 points
	is a 1% discount.
</div>
<div class="header3 header_bold">Malefits of Alignment</div>
<div class="docs_text">
	You will pay more for ships of your own race. Every 5 points is an extra 1%.
</div>
<div class="docs_text">
	The Imperial Government has an economic interest in perpetuating the war. If your
	alignment drops below <?php echo SAFE_ALIGNMENT_MINIMUM; ?> you will lose safety
	in protected systems regardless of <a href="docs.php?page=ship_rating">ship rating</a>.
</div>
<div class="header3 header_bold">Ranks</div>
<div class="docs_text">
	Alignment and <a href="docs.php?page=levels">level</a> goals must be met in order
	for a player to gain new ranks, which will be necessary to purchase higher level
	<a href="docs.php?page=ships">ships</a> and weapons. Each time you level up with
	deficient alignment is a missed chance to gain a rank.
</div>
<div class="docs_text">
	The list of ranks, including the minimum level and alignment
	for each, are listed below. 
</div>
<div class="docs_text">
	<?php
		if ($spacegame['ranks_count'] <= 0) {
			echo 'Could not find any ranks in the database.';
		}
		else {
			echo '<table>';
				echo '<tr>';
				
				echo '<td><strong>Rank</strong></td>';
				echo '<td><strong>Level</strong>&nbsp;&nbsp;</td>';
				echo '<td><strong>Alignment</strong></td>';
				
				echo '</tr>';

			foreach ($spacegame['ranks'] as $id => $rank) {
				echo '<tr>';

				echo '<td>' . $rank['caption'] . '&nbsp;&nbsp;</td>';
				echo '<td>' . $rank['level'] . '</td>';
				echo '<td>' . $rank['alignment'] . '</td>';

				echo '</tr>';
			}


			echo '</table>';
		}

	?>
</div>
<div class="docs_text">
	Ranks do not have any effect on your combat or trade ability. You cannot lose ranks
	once you earn them. However, if your alignment is in the negative, you will not be able
	to purchase ranked ships you have earned. Make sure you stock up on the ships you want
	before starting your life of crime, or you may just find yourself making a few thousand
	trades in a starter ship to get back to +0.
</div>