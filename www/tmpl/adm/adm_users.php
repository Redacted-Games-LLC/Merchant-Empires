<?php
/**
 * Administration page for users.
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
	
	if (!get_user_field(USER_ID, 'admin', 'users')) {
		header('Location: viewport.php?rc=1030');
		die();
	}

	include_once('inc/search_users.php');


?>
	<div class="header2">User Administration</div>
	<div class="docs_text">
		You can manipulate registered users using this page.
	</div>
	<hr />
	<div class="header3">Search for User</div>
	<div class="docs_text">
		Enter at least 2 characters to search <strong>both</strong> users and players. You will be
		presented with a list of the best matches.
	</div>
	<div class="docs_text">
		<form action="admin.php" method="get">
			<input class="form_input" type="text" name="search" id="search" maxlength="24" size="24" /> 
			<script type="text/javascript">drawButton('search', 'search', 'validate_search()');</script>
			<input type="hidden" name="page" value="users" />
			<input type="hidden" name="form_id" value="<?php echo $_SESSION['form_id']; ?>" />
		</form>
	</div>
	<?php
		if (isset($spacegame['search_results'])) {

			echo '<hr />';
			echo '<div class="header3">Search Results</div>';
			echo '<div class="docs_text">';

			if ($spacegame['search_result_count'] <= 0) {
				echo 'No results found. Please try again!';
			}
			else {
				echo '<div class="user_list">';

				foreach ($spacegame['search_results'] as $result) {
					echo '<div class="user_list_item">';
					
						echo '<div class="user_list_caption">';
							echo '<a href="admin.php?page=user&amp;user=';
							echo $result;
							echo '">';
							echo $result;
							echo '</a>';
						echo '</div>';

					echo '</div>';
					
				}

				echo '</div>';
			}

			echo '</div>';
		}
		

		if (isset($spacegame['users'])) {

			echo '<hr />';
			echo '<div class="header3">User List</div>';
			echo '<div class="docs_text">';
				echo '<div class="user_list">';

				foreach ($spacegame['users'] as $user) {

					echo '<div class="user_list_item">';

						echo '<div class="user_list_caption">';
						echo '<a href="admin.php?page=user&amp;user=';
						echo $user;
						echo '">';
						echo $user;
						echo '</a>';

						echo '</div>';

					echo '</div>';
				}

				echo '</div>';
			echo '</div>';
			?>
			
			<div id="pagination">
				<br clear="all" />
			</div>
			<script type="text/Javascript">
				<?php
					echo 'load_pagination(';
					echo ($spacegame['page_number']) .', '. $spacegame['max_pages'] .',';
					echo '"admin.php?page=users&pp='. $spacegame['per_page'] .'&")';
				?>
			</script>

		<?php

			
		}


?>