<?php
/**
 * Documentation for turns
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
<div class="header2 header_bold">Turns</div>
<div class="docs_text">
	Just about every game action you perform uses up <em>Turns</em>. These represent
	units of time which smooth the playing field between players with little real time to
	play and those who have nothing but time. 
</div>
<div class="docs_text">
	Players may store a maximum of <?php echo MAX_TURNS; ?> turns, given at a rate of
	<?php echo TURNS_PER_UPDATE; ?> every <?php echo TURN_UPDATE_TIME; ?> seconds. When a
	player runs out of turns, they will be unable to move, trade, or attack.
</div>
<div class="header3 header_bold">Turn Consumers</div>
<div class="docs_text">
	Your ship engines will burn the most turns by far. Movement takes up at least 1 turn
	<em>per sector</em> and can reach 10 on some ships. Traveling 40 sectors to a battle
	can cost two hours of turns! The following list shows other turn users:
</div>
<div class="docs_text">
	<ul>
		<li>
			<strong>Trade</strong> -
			<?php echo TRADE_TURN_COST; ?> turn(s) for every buy and sell.
		</li>
		<li>
			<strong>Targetting</strong> -
			<?php echo TARGET_TURN_COST; ?> turn(s) every sector search, but doesn't
			include natural ship dealer search from an escape pod.
		</li>
		<li>
			<strong>Scanning</strong> -
			<?php echo SCAN_TURN_COST; ?> turn(s) for every sector scan.
		</li>
		<li>
			<strong>Deployment</strong> -
			<?php echo DEPLOY_TURN_COST; ?> turn(s) for every deployment, including
			mines/drones, shields/armor, solar collectors, port packages, etc. Also
			includes picking up drones.
		</li>
		<li>
			<strong>Warping</strong> -
			Ship TPS multiplied by <?php echo WARP_TURN_MULTIPLIER; ?> turn(s) for
			each click of a warp.
		</li>
		<li>
			<strong>Cargo Jettison</strong> -
			<?php echo CARGO_DUMP_COST; ?> turn(s) each time the jettison button is
			clicked.
		</li>
	</ul>
</div>
<div class="docs_text">
	<strong>Warning</strong>: Many actions take turns away first before checking to
	see if the action is valid. This is by design and turns won't be given back. If
	there is a place where this may be happening deceptively, report it so that the admins can
	clarify, but a good player should know when they are performing an invalid action.
</div>