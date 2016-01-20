<?php
/**
 * Entry template page for messaging
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
	include_once('inc/news.php');

?>
<div class="header2">Galaxy News</div>
<div class="docs_text">
	<?php

		if ($spacegame['news']['article_count'] <= 0) {
		
			echo 'There are no articles to display.';

		} else { 

			foreach ($spacegame['news']['articles'] as $article) {
				include('tmpl/news_article.php');
			}
		}

		echo '<div id="pagination">';
		echo '<br clear="all" />';
		echo '</div>';
		echo '<script type="text/Javascript">load_pagination(';
		echo $spacegame['page_number'] .', '. $spacegame['max_pages'] .',';
		echo '"message.php?page=main&pp='. $spacegame['per_page'] .'&"';
		echo ')</script>';
	?>

</div>


