<?php
/**
 * Administration page for goods
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

	if (!get_user_field(USER_ID, 'admin', 'goods')) {
		header('Location: viewport.php?rc=1030');
		die();
	}

	include_once('inc/goods.php');
	include_once('inc/good_upgrades.php');

	define("TABLEHEADER_LVL", '<th style="width:20px;"><strong>Lvl</strong></th>');
	define("TABLEHEADER_GOODCAPTION", '<th style="width:200px"><strong>Good Caption</strong></th>');

?>

<!-- To work on sorting goods into tables vertically instead of horizontally --> 

<style>
	.container {
		display: flex;
		flex-flow: column wrap;
		height: 500px;
	}
	
	.container2 {
		display: flex;
		flex-flow: column wrap;
		height: 150px;
	}
	
	.container3 {
		display: flex;
		flex-flow: column wrap;
		height: 150px;
	}
	
	.tab {
		padding-left: 1em;
	}
	
	.tab2 {
		padding-left: 2em;
	}
</style>

<div class="header2">Goods Administration</div>
<div class="docs_text">
	You can manipulate existing goods or create new ones using this tool.
</div>
<hr />
<div class="header3">List of Goods</div>
<div class="docs_text">
	
	
	<?php
		$columns = 3;
		$column = 0;
		echo '<table style="">';
		echo '<caption hidden>List of Goods</caption>';
		for ($i = 0; $i < $columns; $i++) {
			echo TABLEHEADER_LVL;
			echo TABLEHEADER_GOODCAPTION;
		}
		echo '</table>';

		echo '<div class="container">';

		foreach ($spacegame['goods'] as $good_id => $good) {
			
			echo '<div>';
			
			echo '<div style="width:20px; float: left; text-align: right;">';
			echo '<em class="">' . $good['level'] . '</em>';
			echo '</div>';
			
			echo '<div style="width:160px; float: left; padding-left: 35px;">';
			echo '<img class="" src="res/goods/' . $good['safe_caption'] . '.png" width="20" height="20" />';
			echo '&nbsp;&nbsp;';
			echo '<a href="admin.php?page=good&amp;id='. $good_id .'">';
			$good_caption = $good['caption'];
			if (strlen($good_caption) >= 15) {
				echo substr($good_caption, 0, 12) . '...';
			}
			else {
				echo $good_caption;
			}
			echo '</a>';
			echo '</div>';
			
			echo '</div>';
		}
		
		echo '</div>';
		
	?>
	
</div>
<hr />
<div class="header3">Goods That Do Not Upgrade</div>
<div class="docs_text">
	
	<?php
		$columns = 3;
		$column = 0;
		echo '<table style="">';
		echo '<caption hidden>Goods That Do Not Upgrade</caption>';
		for ($i = 0; $i < $columns; $i++) {
			echo TABLEHEADER_LVL;
			echo TABLEHEADER_GOODCAPTION;
		}
		echo '</table>';
		
		echo '<div class="container2">';

		foreach ($spacegame['goods'] as $good_id => $good) {

			if ($good['target_count'] > 0) {
				continue;
			}

			echo '<div>';
			
			echo '<div style="width:20px; float: left; text-align: right;">';
			echo '<em class="">' . $good['level'] . '</em>';
			echo '</div>';
			
			echo '<div style="width:160px; float: left; padding-left: 35px;">';
			echo '<img class="" src="res/goods/' . $good['safe_caption'] . '.png" width="20" height="20" />';
			echo '&nbsp;&nbsp;';
			echo '<a href="admin.php?page=good&amp;id='. $good_id .'">';
			$good_caption = $good['caption'];
			if (strlen($good_caption) >= 15) {
				echo substr($good_caption, 0, 12) . '...';
			}
			else {
				echo $good_caption;
			}
			echo '</a>';
			echo '</div>';
			
			echo '</div>';
		}
		
		echo '</div>';

	?>

</div>
<hr />
<div class="header3">Basic Goods</div>
<div class="docs_text">
	
	<?php
		$columns = 3;
		$column = 0;
		echo '<table style="">';
		echo '<caption hidden>Basic Goods</caption>';
		for ($i = 0; $i < $columns; $i++) {
			echo TABLEHEADER_LVL;
			echo TABLEHEADER_GOODCAPTION;
		}
		echo '</table>';
		
		echo '<div class="container3">';

		foreach ($spacegame['goods'] as $good_id => $good) {

			if ($good['source_count'] > 0) {
				continue;
			}

			echo '<div>';
			
			echo '<div style="width:20px; float: left; text-align: right;">';
			echo '<em class="">' . $good['level'] . '</em>';
			echo '</div>';
			
			echo '<div style="width:160px; float: left; padding-left: 35px;">';
			echo '<img class="" src="res/goods/' . $good['safe_caption'] . '.png" width="20" height="20" />';
			echo '&nbsp;&nbsp;';
			echo '<a href="admin.php?page=good&amp;id='. $good_id .'">';
			$good_caption = $good['caption'];
			if (strlen($good_caption) >= 15) {
				echo substr($good_caption, 0, 12) . '...';
			}
			else {
				echo $good_caption;
			}
			echo '</a>';
			echo '</div>';
			
			echo '</div>';
		}

		echo '</div>';

	?>

</div>
<hr />
<div class="header3">Create New Good</div>
<div class="docs_text">
	Enter the information below to create a new good.
</div>
<div class="docs_text">
	<form action="handler.php" method="post">
		<label for="new_name">Name:</label>
		<input id="new_name" name="name" type="text" maxlength="12" size="13" />
		&nbsp;&nbsp;&nbsp;
		<label>Level:</label>
		<script type="text/javascript">draw_number_list('level')</script>
		&nbsp;&nbsp;&nbsp;
		<script type="text/javascript">drawButton('create', 'create', 'validate_create_good()')</script>
		<input type="hidden" name="task" value="good" />
		<input type="hidden" name="subtask" value="create" />
		<input type="hidden" name="form_id" value="<?php echo $_SESSION['form_id']; ?>" />
	</form>
</div>