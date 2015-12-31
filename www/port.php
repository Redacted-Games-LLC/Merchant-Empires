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

	include_once('inc/page.php');
	include_once('inc/game.php');
	include_once('inc/port.php');
	include_once('inc/cargo.php');

	if ($spacegame['player']['ship_type'] <= 0) {
		header('Location: error.php?rc=1031');
		die();
	}




	$empty_holds_available = $spacegame['ship']['holds'];

	if (isset($spacegame['cargo_volume'])) {
		$empty_holds_available -= $spacegame['cargo_volume'];
	}


	function make_goods_div($item = array(), $place_id = 0, $holds_available = 1) {

		global $spacegame;

		echo '<div class="port_goods">';

		echo '<div class="port_goods_image">';
		echo '<img src="res/goods/' . $item['details']['safe_caption'] . '.png" width="32" height="32" />';
		echo '</div>';

		echo '<div class="port_goods_text">';
		echo '<strong>';
		echo $item['details']['caption'];
		echo '</strong>';
		echo '</div>';
	
		if ($item['distance'] > 0) {
			echo '<div class="port_goods_price" title="';
			echo 'Distance ' . $item['distance'] . ' sector(s)';
			echo '">';
			echo number_format($item['final_price']);
			echo '<img src="res/credits.png" width="16" height="16" />';
			echo '</div>';
		}

		echo '<div class="port_goods_stock">';
		echo number_format($item['amount']);
		echo '</div>';

		if ($item['upgrade'] > 0) {
			
			echo '<div class="port_goods_target">';
				echo '<div class="port_goods_text">';
				echo 'For&nbsp;';
				echo '</div>';

				echo '<div class="port_goods_image">';
				echo '<img src="res/goods/' . strtolower(str_replace(' ', '_', $spacegame['goods'][$item['upgrade']]['caption'])) . '.png" width="20" height="20" />';
				echo '</div>';

				echo '<div class="port_goods_text">';
				echo '<strong>';
				echo $spacegame['goods'][$item['upgrade']]['caption'];
				echo '</strong>';
				echo '</div>';
			echo '</div>';
				
		}

		if ($item['distance'] <= 0) {
			echo '<div class="port_buttons">';
			echo '<em>NO TRADERS</em>';
			echo '</div>';
		}
		else {
			echo '<div class="port_buttons">';
			
			if ($item['amount'] > 0) {
				echo '<form action="handler.php" method="post" target="_top">';

				echo '<input class="port_form_input" id="amount'. $item['record_id'] . '" name="amount" type="text" maxlength="4" size="5" value="'. max(0, $holds_available) .'" />';
				echo '<script type="text/javascript">drawButton(\'sb' . $item['record_id'] . "', 'buy', 'validate_buy()', 'port_form_button');</script>";
			}
			else if ($item['amount'] < 0) {
				echo '<form action="handler.php" method="post">';
				
				$amount = 0;

				if (isset($spacegame['cargo_index'][$item['good']])) {
					$cargo = $spacegame['cargo'][$spacegame['cargo_index'][$item['good']]];
					$amount = $cargo['amount'];
				}

				echo '<input class="port_form_input" id="amount'. $item['record_id'] . '" name="amount" type="text" maxlength="4" size="5" value="'. max(0, $amount) .'" />';
				echo '<script type="text/javascript">drawButton(\'sb' . $item['record_id'] . "', 'sell', 'validate_sell()', 'port_form_button');</script>";
			}

			echo '<input type="hidden" name="task" value="port" />';
			echo '<input type="hidden" name="plid" value="' . $place_id . '" />';
			echo '<input type="hidden" name="item_id" value="' . $item['record_id'] .'" />';

			echo '</form>';
			echo '</div>';
		}

		echo '</div>';
	}


	function port_trade_buys() {
		global $spacegame;
		global $place_id;
		global $empty_holds_available;

		if ($spacegame['port_trades']['buys_count'] > 0) {
			echo '<div class="header4">We need the following goods if you carry them:</div>';

			echo '<div class="port_item">';
			
			foreach ($spacegame['port_trades']['buys'] as $id => $item) {
				make_goods_div($item, $place_id, $empty_holds_available);
			}

			echo '</div>';
			echo '<br class="clear" />';
		}				
	}

	function port_trade_sells() {
		global $spacegame;
		global $place_id;
		global $empty_holds_available;


		if ($spacegame['port_trades']['sells_count'] > 0) {
			echo '<div class="header4">We have the following goods for your purchase if you want them:</div>';

			echo '<div class="port_item">';
			
			foreach ($spacegame['port_trades']['sells'] as $id => $item) {
				make_goods_div($item, $place_id, $empty_holds_available);
			}

			echo '</div>';
			echo '<br class="clear" />';
		}
	}


	function port_upgrade_buys() {
		global $spacegame;
		global $place_id;
		global $empty_holds_available;
		
		if ($spacegame['port_upgrades']['buys_count'] > 0) {
			echo '<div class="header4">We want to start new production but need the following goods:</div>';

			echo '<div class="port_item">';
			
			foreach ($spacegame['port_upgrades']['buys'] as $id => $item) {
				make_goods_div($item, $place_id, $empty_holds_available);
			}

			echo '</div>';
			echo '<br class="clear" />';
		}
	}

	function port_upgrade_sells() {
		global $spacegame;
		global $place_id;
		global $empty_holds_available;

		if ($spacegame['port_upgrades']['sells_count'] > 0) {
			echo '<div class="header4">We have the following &quot;waste&quot; from new production we will sell undeclared:</div>';

			echo '<div class="port_item">';
			
			foreach ($spacegame['port_upgrades']['sells'] as $id => $item) {
				make_goods_div($item, $place_id, $empty_holds_available);
			}

			echo '</div>';
			echo '<br class="clear" />';
		}
	}


	

	$tmpl['no_fluff'] = true;
	$tmpl['page_title'] = 'Port';

	include_once('tmpl/html_begin.php');
?>

	<div class="port_update_button">
		<a href="viewport.php" target="_top">
			<script type="text/javascript">drawButton('close', 'close', 'return true;');</script>
		</a>
	</div>
	<div  class="header2">
		Port of <?php echo $spacegame['places'][$place_id]['caption']; ?>
	</div>
	<div class="docs_text">
		<?php
			echo 'Your ' . $spacegame['races'][$spacegame['player']['race']]['caption'] . ' tax is ' . number_format($spacegame['own_tax_rate'], 1) . '%<br />';
			
			if (isset($spacegame['system']) && $spacegame['system']['race'] > 0 && $spacegame['system']['race'] != $spacegame['player']['race']) {
				echo 'The port has a ' . $spacegame['races'][$spacegame['system']['race']]['caption'] . ' tax of ' . number_format($spacegame['other_tax_rate'], 1) . '%';
			}
		?>
	</div>
	<br class="clear" />
	<?php
		if ($spacegame['port_trades_count'] > 0) {

			if ($empty_holds_available > 0) {
				port_upgrade_sells();
				port_trade_sells();
				port_trade_buys();
				port_upgrade_buys();
			}
			else {
				port_upgrade_buys();
				port_trade_buys();
				port_trade_sells();
				port_upgrade_sells();
			}
		}
	?>
	
<?php
	include_once('tmpl/html_end.php');
?>
