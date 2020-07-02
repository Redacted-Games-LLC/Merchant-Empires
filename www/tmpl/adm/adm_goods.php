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

	define("TABLEHEADER_LVL", '<th class="lvlHeader"><strong>Lvl</strong></th>');
	define("TABLEHEADER_GOODCAPTION", '<th class="captionHeader"><strong>Good Caption</strong></th>');
	define("DIV_OPEN", '<div>');
	define("DIV_CLOSE", '</div>');
	define("TBL_OPEN", '<table>');
	define("TBL_CLOSE", '</table>');
	define("DIV_GOODLEVEL_OPEN", '<div class="goodLevel">');
	define("DIV_GOODCAPTION_OPEN", '<div class="goodCaption">');
	
	function print_goods($goods_list) {
		
		foreach ($goods_list as $good_id => $good) {
			
			echo DIV_OPEN;
			
			echo DIV_GOODLEVEL_OPEN;
			echo '<em>' . $good['level'] . '</em>';
			echo DIV_CLOSE;
			
			echo DIV_GOODCAPTION_OPEN;
			echo '<img src="res/goods/' . $good['safe_caption'] . '.png" width="20" height="20" />';
			echo '&nbsp;&nbsp;';
			echo '<a href="admin.php?page=good&amp;id='. $good_id .'">';
			$good_caption = $good['caption'];
			if (strlen($good_caption) >= 20) {
				echo substr($good_caption, 0, 17) . '...';
			}
			else {
				echo $good_caption;
			}
			echo '</a>';
			echo DIV_CLOSE;
			
			echo DIV_CLOSE;
		}
	}

?>

<div class="header2">Goods Administration</div>
<div class="docs_text">
	You can manipulate existing goods or create new ones using this tool.
</div>
<br />
<hr />
<div class="header3">List of Goods</div>
<br />
<div>	
	
	<?php
		$columns = 3;
		echo TBL_OPEN;
		echo '<caption hidden>List of Goods</caption>';
		for ($i = 0; $i < $columns; $i++) {
			echo TABLEHEADER_LVL;
			echo TABLEHEADER_GOODCAPTION;
		}
		echo TBL_CLOSE;

		echo '<div class="good goodContainer1">';
		
		print_goods($spacegame['goods']);
		
		echo DIV_CLOSE;
		
	?>
	
</div>
<br />
<hr />
<div class="header3">Goods That Do Not Upgrade</div>
<br />
<div>
	
	<?php
		$columns = 3;
		echo TBL_OPEN;
		echo '<caption hidden>Goods That Do Not Upgrade</caption>';
		for ($i = 0; $i < $columns; $i++) {
			echo TABLEHEADER_LVL;
			echo TABLEHEADER_GOODCAPTION;
		}
		echo TBL_CLOSE;
		
		echo '<div class="good goodContainer2">';
		
		$goods_list = array();

		foreach ($spacegame['goods'] as $good_id => $good) {

			if ($good['target_count'] > 0) {
				continue;
			}
			
			$goods_list[$good_id] = $good;
			
		}
		
		print_goods($goods_list);
		
		echo DIV_CLOSE;

	?>

</div>
<br />
<hr />
<div class="header3">Basic Goods</div>
<br />
<div>
	
	<?php
		$columns = 3;
		echo TBL_OPEN;
		echo '<caption hidden>Basic Goods</caption>';
		for ($i = 0; $i < $columns; $i++) {
			echo TABLEHEADER_LVL;
			echo TABLEHEADER_GOODCAPTION;
		}
		echo TBL_CLOSE;
		
		echo '<div class="good goodContainer2">';
		
		$goods_list = array();

		foreach ($spacegame['goods'] as $good_id => $good) {

			if ($good['source_count'] > 0) {
				continue;
			}

			$goods_list[$good_id] = $good;
		}
		print_goods($goods_list);

		echo DIV_CLOSE;

	?>

</div>
<br />
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