<?php
/**
 * Article page
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

?>
<div class="news <?php echo 'news_author' . $article['author']; ?>">
	<div class="news_date">
		<?php echo date(DATE_RFC850, $article['live']); ?><br />
		<em>By: <strong><?php echo $spacegame['news']['authors'][$article['author']]; ?></strong></em>
	</div>
	<div class="news_headline">
		<?php echo $article['headline']; ?>
	</div>
	<div class="news_abstract">
		&nbsp;<br />
		<?php echo $article['abstract']; ?>
	</div>
	<hr noshade="noshade" />
	<div class="news_article">
		<?php echo $article['article']; ?>
	</div>
</div>
