<?php
/**
 * 
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
	include_once('inc/place_types.php');

	$adm_good = array();

	if (isset($_REQUEST['id']) && isset($spacegame['goods'][$_REQUEST['id']])) {
		$adm_good = $spacegame['goods'][$_REQUEST['id']];

		$db = isset($db) ? $db : new DB;

		$adm_starts = array();
		$adm_starts_count = 0;

		$rs = $db->get_db()->query("select * from start_goods where good = '". $_REQUEST['id'] ."' order by place_type, supply, record_id");
		
		$rs->data_seek(0);
				
		while ($row = $rs->fetch_assoc()) {
			$adm_starts[$row['record_id']] = $row;
			$adm_starts_count++;
		}

		$adm_requirements = array();
		$adm_requirements_count = 0;

		$rs = $db->get_db()->query("select * from good_upgrades where target = '". $_REQUEST['id'] ."' order by good, record_id");
		
		$rs->data_seek(0);
				
		while ($row = $rs->fetch_assoc()) {
			$adm_requirements[$row['good']] = $row;
			$adm_requirements_count++;
		}		
	}

	if (ctype_digit($_GET['id'])) {
		$good_id = $_GET['id'];
	}
	else {
		$good_id = null;
	}

?>
<div class="header2 header_bold"><a href="admin.php?page=goods">Goods Administration</a> :: <?php echo isset($adm_good['caption']) ? $adm_good['caption'] : 'No Good Selected'; ?></div>
<?php if (!isset($adm_good['caption'])) { ?>
	<div class="docs_text">
		You must select a good first. <a href="admin.php?page=goods">Click here</a> to go back.
	</div>
<?php } else { ?>
<div class="docs_goods align_center">
	<img src="res/goods/<?php echo $adm_good['safe_caption']; ?>.png" width="12" height="12" alt="admin good" />
	<img src="res/goods/<?php echo $adm_good['safe_caption']; ?>.png" width="16" height="16" alt="admin good" />
	<img src="res/goods/<?php echo $adm_good['safe_caption']; ?>.png" width="20" height="20" alt="admin good" />
	<img src="res/goods/<?php echo $adm_good['safe_caption']; ?>.png" width="24" height="24" alt="admin good" />
	<img src="res/goods/<?php echo $adm_good['safe_caption']; ?>.png" width="32" height="32" alt="admin good" />
	<img src="res/goods/<?php echo $adm_good['safe_caption']; ?>.png" width="48" height="48" alt="admin good" />
	<img src="res/goods/<?php echo $adm_good['safe_caption']; ?>.png" width="64" height="64" alt="admin good" />
</div>
<div class="docs_text">
	<a href="docs.php?page=good&amp;id=<?php echo $good_id; ?>" target="_blank">Click Here</a> to view good information.<br />
	You can make changes to the selected good below:
</div>
<hr />
<div class="header3 header_bold">General Configuration</div>
<div class="docs_text">
	You can change the name and/or level here but make sure to update the image
	file stored on the servers.
</div>
<div class="docs_text">
	<form action="handler.php" method="post">
		<label for="new_name">Name:</label>
		<input id="new_name" name="name" type="text" maxlength="24" size="25" value="<?php echo $adm_good['caption']; ?>" />
		&nbsp;&nbsp;&nbsp;
		<label>Level:</label>
		<script type="text/javascript">draw_number_list('level', <?php echo $adm_good['level']; ?>)</script>
		&nbsp;&nbsp;&nbsp;
		<label for="tech" title="Won't show up as a start demand.">Tech?</label>
		<input id="tech" name="tech" type="text" maxlength="10" size="7" value="<?php echo $adm_good['tech']; ?>" />
		&nbsp;&nbsp;&nbsp;
		<script type="text/javascript">drawButton('update', 'update', 'validate_update_good()')</script>
		<input type="hidden" name="task" value="good" />
		<input type="hidden" name="subtask" value="update" />
		<input type="hidden" name="id" value="<?php echo $adm_good['record_id']; ?>" />
		<input type="hidden" name="form_id" value="<?php echo $_SESSION['form_id']; ?>" />
	</form>
</div>
<hr />
<div class="header3 header_bold">Upgrade Requirements</div>
<div class="docs_text">
	Add or remove upgrade requirements for this good. This will break existing upgrades
	in progress so unless this is a new good be sure to reset all ports to fix things.
	Current requirements:
</div>
<div class="docs_text">
	<?php
		if ($adm_requirements_count > 0) {
			foreach ($adm_requirements as $id => $requirement) {
				
				echo '&nbsp;&nbsp;&nbsp;&nbsp;';
				echo '<a href="handler.php?task=good&amp;subtask=delete_requirement&amp;id=' . $adm_good['record_id'] . '&amp;requirement=' . $id . '&amp;form_id='. $_SESSION['form_id'] .'">';
				echo 'Delete';
				echo '</a>';
				echo '&nbsp;&nbsp;&nbsp;&nbsp;';

				$good = $spacegame['goods'][$requirement['good']];

				echo $good['caption'];

				echo '<br />';
			}
		}
		else {
			echo '<em>No Requirements</em>';
		}
	?>
</div>
<div class="header4 header_bold">Add New Requirement</div>
<div class="docs_text">
	<form action="handler.php" method="post">
		<label>Required Good:</label>
		<select name="requirement">
			<?php
			foreach ($spacegame['goods'] as $id => $good) {
				if ($id == $adm_good['record_id']) {
					continue;
				}
				if (isset($adm_requirements[$id])) {
					continue;
				}

				echo "<option value='$id'>";
				echo $good['caption'];
				echo '</option>';
			}
			?>
		</select>
		&nbsp;&nbsp;&nbsp;
		<script type="text/javascript">drawButton('add_requirement', 'add', 'validate_add_requirement()')</script>
		<input type="hidden" name="task" value="good" />
		<input type="hidden" name="subtask" value="add_requirement" />
		<input type="hidden" name="id" value="<?php echo $adm_good['record_id']; ?>" />
		<input type="hidden" name="form_id" value="<?php echo $_SESSION['form_id']; ?>" />
	</form>
</div>
<hr />
<div class="header3 header_bold">Port Starts</div>
<div class="docs_text">
	Add or remove supply/demand starts for different place types where ports can be
	deployed.
</div>
<div class="docs_text">
	<?php
		if ($adm_starts_count > 0) {
			foreach ($adm_starts as $id => $start) {
				
				echo '&nbsp;&nbsp;&nbsp;&nbsp;';
				echo '<a href="handler.php?task=good&amp;subtask=delete_start&amp;id=' . $adm_good['record_id'] . '&amp;start=' . $id . '&amp;form_id='. $_SESSION['form_id'] .'">';
				echo 'Delete';
				echo '</a>';
				echo '&nbsp;&nbsp;&nbsp;&nbsp;';

				echo $start['percent'] . '%';
				echo '&nbsp;';

				echo $start['supply'] ? 'Supply' : 'Demand';
				echo '&nbsp;chance on a(n)&nbsp;';

				echo $spacegame['place_types'][$start['place_type']]['caption'];

				echo '<br />';
			}
		}
		else {
			echo '<em>No Starts</em>';
		}
	?>
</div>
<div class="header4 header_bold">Add New Start</div>
<div class="docs_text">
	<form action="handler.php" method="post">
		<label>Place Type:</label>
		<select name="target">
			<?php
			foreach ($spacegame['place_types'] as $id => $place_type) {
				echo "<option value='$id'>";
				echo $place_type['caption'];
				echo '</option>';
			}
			?>
		</select>
		&nbsp;&nbsp;
		<label for="percent">Percent:</label>
		<input id="percent" name="percent" type="text" maxlength="3" size="4" value="10" />
		&nbsp;&nbsp;
		<label for="supply">Supply:</label>
		<select id="supply" name="supply">
			<option value="0" selected="selected">No</option>
			<option value="1">Yes</option>
		</select>
		&nbsp;&nbsp;
		<script type="text/javascript">drawButton('add_start', 'add', 'validate_add_start()')</script>
		<input type="hidden" name="task" value="good" />
		<input type="hidden" name="subtask" value="add_start" />
		<input type="hidden" name="id" value="<?php echo $adm_good['record_id']; ?>" />
		<input type="hidden" name="form_id" value="<?php echo $_SESSION['form_id']; ?>" />
	</form>
</div>
<hr />
<div class="header3 header_bold">Completely Delete Good</div>
<div class="docs_text">
	Click the following button to delete all traces of the good from the galaxy. This
	cannot be undone!
</div>
<div class="docs_text">
	<form action="handler.php" method="post">
		<script type="text/javascript">drawButton('delete', 'delete', 'validate_delete_good()')</script>
		<input type="hidden" name="task" value="good" />
		<input type="hidden" name="subtask" value="delete" />
		<input type="hidden" name="id" value="<?php echo $adm_good['record_id']; ?>" />
		<input type="hidden" name="form_id" value="<?php echo $_SESSION['form_id']; ?>" />
	</form>
</div>

<hr />
<?php 
	}
?>