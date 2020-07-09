<?php
/**
 * Administration page for the news desk
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

	if (!get_user_field(USER_ID, 'admin', 'news')) {
		header('Location: viewport.php?rc=1030');
		die();
	}

	include_once('inc/news.php');

	$preview = isset($_REQUEST['task']) && $_REQUEST['task'] == 'preview';
	$article['headline'] = 'Alphanumeric and space only';
	$article['abstract'] = 'Allowed HTML tags: a, em, font, img, s, span, strong, u';
	$article['article'] = 'Allowed HTML tags: a, br, em, font, h1 to h6, img, li, ol, p, s, span, strong, table, td, th, tr, u, ul';
	$article['author'] = 0;
	$article['live'] = PAGE_START_TIME;
	$article['archive'] = PAGE_START_TIME + DEFAULT_NEWS_ARCHIVE_TIME;
	$article['expiration'] = PAGE_START_TIME + DEFAULT_NEWS_EXPIRATION_TIME;

	if ($preview) {
		$article['headline'] = htmlentities(strip_tags($_REQUEST['headline']));
		$article['abstract'] = trim(strip_tags($_REQUEST['abstract'], ALLOWED_ABSTRACT_TAGS));
		$article['article'] = trim(strip_tags($_REQUEST['article'], ALLOWED_ARTICLE_TAGS));
		$article['author'] = $_REQUEST['author'];
		$article['live'] = $_REQUEST['live_date'];
		$article['archive'] = $_REQUEST['archive_date'];
		$article['expiration'] = $_REQUEST['expiration_date'];
	}

?>

<div class="header2 header_bold">News Desk</div>
<?php if ($preview) { ?>
	<div class="docs_text">
		Here is your preview:
		<?php
			include_once('tmpl/news_article.php');
		?>
	</div>
	<hr />
<?php } ?>
<div class="docs_text">
	<strong>Warning:</strong> "Preview" will preserve your article but <em><u>not</u></em> publish it.
	If there is an error, your article will be lost. Please write it up using a separate tool and
	paste the content in.
</div>
<div class="docs_text">
	<form action="handler.php" method="post">
		<table class="message" role="presentation">
			<tr class="message">
				<td class="message">&nbsp;</td>
				<td class="message align_right">
					<span class="characters_left" id="headline_characters_left">&nbsp;</span>
				</td>
			</tr>
			<tr class="message">
				<td class="message">Headline:</td>
				<td class="message"><input class="msg_form_input" id="msg_headline" type="text" name="headline" maxlength="<?php echo NEWS_HEADLINE_LIMIT; ?>" value="<?php echo $article['headline']; ?>" size="58" /></td>
				<td class="message align_right">
					<span class="characters_left" id="abstract_characters_left">&nbsp;</span>
				</td>
			</tr>
			<tr class="message">
				<td class="message">Abstract:</td>
				<td class="message" colspan="2">
					<textarea class="msg_form_input" id="msg_abstract" name="abstract" rows="3" cols="58" maxlength="<?php echo NEWS_ABSTRACT_LIMIT; ?>"><?php echo $article['abstract']; ?></textarea>
				</td>
			</tr>
			<tr class="message">
				<td class="message">Author:</td>
				<td class="message">
					<select name="author">
						<?php
							foreach ($spacegame['news']['authors'] as $value => $caption) {
								echo '<option value="' . $value . '"';

								if ($value == $article['author']) {
									echo ' selected="selected"';
								}

								echo '>' . $caption . '</option>';
							}
						?>
					</select>
				</td>
				<td class="message align_right">
					<span class="characters_left" id="article_characters_left">&nbsp;</span>
				</td>
			</tr>
			<tr class="message">
				<td class="message">Article:</td>
				<td class="message" colspan="2">
					<textarea class="msg_form_input" id="msg_article" name="article" rows="20" cols="58" maxlength="<?php echo NEWS_ARTICLE_LIMIT; ?>"><?php echo $article['article']; ?></textarea>
				</td>
			</tr>
			<tr class="message">
				<td class="message">Publish&nbsp;Timestamp:</td>
				<td class="message" colspan="2"><input class="msg_form_input" type="text" name="live_date" maxlength="10" size="12" value="<?php echo $article['live']; ?>" /></td>
			</tr>
			<tr class="message">
				<td class="message">Archive&nbsp;Timestamp:</td>
				<td class="message" colspan="2"><input class="msg_form_input" type="text" name="archive_date" maxlength="10" size="12" value="<?php echo $article['archive']; ?>" /></td>
			</tr>
			<tr class="message">
				<td class="message">Purge&nbsp;Timestamp:</td>
				<td class="message" colspan="2"><input class="msg_form_input" type="text" name="expiration_date" maxlength="10" size="12" value="<?php echo $article['expiration']; ?>" /></td>
			</tr>
			<tr class="message">
				<td class="message">Submit:</td>
				<td class="message" colspan="2">
					<script type="text/javascript">
						drawButton('preview', 'preview', 'validate_preview()');
						register_textarea_length_handlers('msg_headline', 'headline_characters_left', <?php echo NEWS_HEADLINE_LIMIT; ?>);
						register_textarea_length_handlers('msg_abstract', 'abstract_characters_left', <?php echo NEWS_ABSTRACT_LIMIT; ?>);
						register_textarea_length_handlers('msg_article', 'article_characters_left', <?php echo NEWS_ARTICLE_LIMIT; ?>);
					</script>
					&nbsp;
					<script type="text/javascript">
						drawButton('send', 'send', 'validate_send()');
					</script>
				</td>
			</tr>
		</table>
		<input type="hidden" name="task" value="news" />
		<input type="hidden" name="subtask" value="submit" />
		<input type="hidden" name="form_id" value="<?php echo $_SESSION['form_id']; ?>" />
	</form>
</div>