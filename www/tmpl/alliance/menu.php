<?php
/**
 * Menu of alliance and team related pages.
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
?>
	
	<ul class="popup_list">
		<li class="popup_list"><a href="alliance.php?page=main">Alliance Status</a></li>

		<?php if ($spacegame['player']['alliance'] > 0) { ?>
		
			<li class="popup_list"><?php echo get_alliance_link('members', 'Members'); ?></li>
		
		<?php } else { ?>
		
			<li class="popup_list"><?php echo get_alliance_link('create', 'Create Alliance'); ?></li>
		
		<?php } ?>

		<li class="popup_list"><?php echo get_alliance_link('list', 'Alliance List'); ?></li>
		<li class="popup_list"><?php echo get_alliance_link('players', 'All Players'); ?></li>
	</ul>

	<hr />
