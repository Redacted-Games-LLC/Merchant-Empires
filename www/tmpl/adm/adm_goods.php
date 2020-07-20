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

	if (!get_user_field(USER_ID, 'admin', 'goods')) {
		header('Location: viewport.php?rc=1030');
		die();
	}
	
	include_once('tmpl/goods_list.php');

?>
<br />
<hr />
<div class="header3 header_bold">Goods That Do Not Upgrade</div>
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
<div class="header3 header_bold">Basic Goods</div>
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
<div class="header3 header_bold">Create New Good</div>
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