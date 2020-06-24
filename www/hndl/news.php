<?php
/**
 * Handles news stuff by passing it off to another function
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

	include_once('inc/page.php');
	include_once('inc/game.php');

	$return_page = 'admin';
	$return_vars['page'] = 'news';

	if (!get_user_field(USER_ID, 'admin', 'news')) {
		header('Location: viewport.php?rc=1030');
		die();
	}

	if (isset($_SESSION['form_id'])) {
		if (!isset($_REQUEST['form_id']) || $_SESSION['form_id'] != $_REQUEST['form_id']) {
			header('Location: viewport.php?rc=1181');
			die();
		}
	}
	

	do { // Dummy loop

		if (isset($_REQUEST['btn_preview_x'])) {

			define('CANCEL_REDIRECT', 1);

			$_REQUEST['page'] = 'news';
			$_REQUEST['task'] = 'preview';

			include_once('admin.php');
			break;

		}
		elseif (isset($_REQUEST['btn_send_x'])) {

			define('SKIP_ARTICLES', 1);
			include_once('inc/news.php');

			$headline = htmlentities(strip_tags($_REQUEST['headline']));
			$abstract = trim(strip_tags($_REQUEST['abstract'], ALLOWED_ABSTRACT_TAGS));
			$article = trim(strip_tags($_REQUEST['article'], ALLOWED_ARTICLE_TAGS));
			$author = $_REQUEST['author'];
			$live = $_REQUEST['live_date'];
			$archive = $_REQUEST['archive_date'];
			$expiration = $_REQUEST['expiration_date'];

			if (insert_article($headline, $abstract, $article, $author, $live, $archive, $expiration, $return_codes)) {
				$return_codes[] = 1163;
				break;
			}
			else {
				$return_codes[] = 1164;
				break;
			}

		}
		else {
			$return_codes[] = 1041;
			break;
 		}

	} while (false);

?>