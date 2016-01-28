<?php
/**
 * Viewport popup for base construction
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
	include_once('inc/base.php');

	if ($spacegame['player']['base_id'] <= 0) {
		header('Location: viewport.php?rc=1116');
		break;
	}

	include_once('inc/rooms.php');

	$tmpl['no_fluff'] = true;
	$tmpl['page_title'] = 'Base Construction';

	include_once('tmpl/html_begin.php');

	
?>

<div class="port_update_button">
	<a href="viewport.php" target="_top">
		<script type="text/javascript">drawButton('close', 'close', 'return true;');</script>
	</a>
</div>
<div class="header2">
	<?php echo $tmpl['page_title']; ?>
</div>
<div class="header3">
	<?php echo $base_caption; ?>
</div>
<div class="dealer_text">
	<?php quit($spacegame); ?>
</div>

	
<?php
	include_once('tmpl/html_end.php');
?>
