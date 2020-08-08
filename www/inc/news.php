<?php
/**
 * Loads information about news articles
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

	include_once('inc/common.php');

	if (PLAYER_ID > 0) {
		include_once('inc/game.php');
	}
	
	do { // Dummy Loop
		$spacegame['news']['authors'] = array();
		$spacegame['news']['authors'][0] = 'News Desk Admins';
		$spacegame['news']['authors'][-1] = 'Imperial Government';
		$spacegame['news']['authors'][-2] = 'Battle Report Office';

		$spacegame['news']['articles'] = array();
		$spacegame['news']['article_count'] = 0;

		if (!defined('SKIP_ARTICLES')) {

			include_once('inc/pagination.php');

			$db = isset($db) ? $db : new DB;

			$rs = null;

			if (defined('NEWS_ARCHIVE')) {
				$rs = $db->get_db()->query("select SQL_CALC_FOUND_ROWS * from news where live < '". PAGE_START_TIME ."' and expiration > '". PAGE_START_TIME ."' and archive <= '". PAGE_START_TIME ."' order by live desc limit ". $spacegame['page_number'] .", " . $spacegame['per_page']);
			}
			else {
				$rs = $db->get_db()->query("select SQL_CALC_FOUND_ROWS * from news where live < '". PAGE_START_TIME ."' and expiration > '". PAGE_START_TIME ."' and archive > '". PAGE_START_TIME ."' order by live desc limit ". $spacegame['page_number'] .", " . $spacegame['per_page']);
			}

			$total_count = $db->found_rows();

			$rs->data_seek(0);

			while ($row = $rs->fetch_assoc()) {
				$spacegame['news']['articles'][$row['record_id']] = $row;
				$spacegame['news']['article_count']++;
			}
		
			$spacegame['max_pages'] = ceil($total_count / $spacegame['per_page']);

			if ($spacegame['page_number'] > $spacegame['max_pages']) {
				$spacegame['page_number'] = $spacegame['max_pages'];
			}
		}
	} while (false);

	function insert_article($headline, $abstract, $article, $author, $live_date, $archive_date, $expiration_date, &$return_codes = array(), $trial_run = false) {

		if ($archive_date < $live_date) {
			$return_codes[] = 1157;
			return false;
		}

		if ($expiration_date <= $live_date) {
			$return_codes[] = 1157;
			return false;
		}

		if (strlen($headline) > NEWS_HEADLINE_LIMIT) {
			$return_codes[] = 1158;
			return false;
		}

		if (!preg_match('/^[a-zA-Z0-9 ]{2,'. NEWS_HEADLINE_LIMIT .'}$/', $headline)) {
			$return_codes[] = 1159;
			return false;
		}

		if (strlen($abstract) > NEWS_ABSTRACT_LIMIT) {
			$return_codes[] = 1160;
			return false;
		}

		if (strlen($article) > NEWS_ARTICLE_LIMIT) {
			$return_codes[] = 1161;
			return false;
		}

		$abstract = trim(strip_tags($abstract, ALLOWED_ABSTRACT_TAGS));
		$article = nl2br(trim(strip_tags($article, ALLOWED_ARTICLE_TAGS)));

		if ($abstract == '' || $article == '') {
			$return_codes[] = 1161;
			return false;
		}

		if ($trial_run) {
			return true;
		}

		global $db;
		if (!isset($db)) {
			$db = isset($db) ? $db : new DB;
		}

		if (!($st = $db->get_db()->prepare("insert into news (headline, abstract, article, author, live, archive, expiration) values (?,?,?,?,?,?,?)"))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			return false;
		}
		
		$st->bind_param("sssiiii", $headline, $abstract, $article, $author, $live_date, $archive_date, $expiration_date);
		
		if (!$st->execute()) {
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
			return false;
		}

		return $db->get_db()->affected_rows > 0;
	}