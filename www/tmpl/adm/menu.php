<?php
/**
 * Menu for the admin tools
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
	
	<div class="header4"><a href="admin.php?page=main">Game Administration</a></div>
	<hr noshade="noshade" size="1" />
	<ul class="docs_menu">
		<li class="docs_menu"><?php echo get_admin_link('users', 'User Editor', 'users'); ?></li>
		<li class="docs_menu"><?php echo get_admin_link('system', 'System Editor', 'system'); ?></li>
		<li class="docs_menu"><?php echo get_admin_link('ports', 'Port Editor', 'ports'); ?></li>
		<li class="docs_menu"><?php echo get_admin_link('goods', 'Goods Editor', 'goods'); ?></li>
		<li class="docs_menu"><?php echo get_admin_link('gold', 'Gold Keys', 'gold'); ?></li>
		<li class="docs_menu"><?php echo get_admin_link('news', 'News Desk', 'news'); ?></li>
	</ul>

