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

	include_once('tmpl/common.php');

?>
<div class="news <?php echo 'news_author' . $article['author']; ?>">
	<div class="news_date align_right">
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
	<div id="news_readme_show_<?php echo $article['record_id']; ?>" class="news_readme" onclick="show_article_div('news_readme_show_', 'news_readme_hide_', 'news_article_', '<?php echo $article['record_id']; ?>')">
		(Read Article...)
	</div>
	<div id="news_article_<?php echo $article['record_id']; ?>" class="news_article">
		<?php echo $article['article']; ?>
		<div id="news_readme_hide_<?php echo $article['record_id']; ?>" class="news_readme" onclick="hide_article_div('news_readme_show_', 'news_readme_hide_', 'news_article_', '<?php echo $article['record_id']; ?>')">
		(Hide Article...)
		</div>
	</div>
</div>
