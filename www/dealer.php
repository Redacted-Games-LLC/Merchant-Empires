<?php
/**
 * Viewport popup for ship dealers, tech dealers, solar collectors, etc.
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
	include_once('inc/dealer.php');

	// Players in escape pods can only enter ship dealers
	if ($spacegame['player']['ship_type'] <= 0 && $spacegame['places'][$place_id]['type'] != 'Ship Dealer') {
		header('Location: error.php?rc=1031');
		die();
	}

	include_once('inc/cargo.php');
	include_once('inc/ships.php');

	$tmpl['no_fluff'] = true;
	$tmpl['page_title'] = 'Dealer';

	include_once('tmpl/html_begin.php');

	$amount_to_transfer = 1;
?>

<div class="port_update_button">
	<a href="viewport.php" target="_top">
		<script type="text/javascript">drawButton('close', 'close', 'return true;');</script>
	</a>
</div>
<div class="header2 header_bold">
	<?php echo $spacegame['places'][$place_id]['caption']; ?>
</div>
<div class="header3 header_bold">
	<?php
		if (isset($spacegame['system']) && $spacegame['system']['race'] > 0) {
			echo $spacegame['races'][$spacegame['system']['race']]['caption'] . ' ';
		}

		echo $spacegame['places'][$place_id]['type'];
	?>
</div>
<div class="dealer_text">
	<?php
		echo 'Your ' . $spacegame['races'][$spacegame['player']['race']]['caption'] . ' tax is ' . number_format($spacegame['own_tax_rate'], 1) . '%<br />';
		
		if (isset($spacegame['system']) && $spacegame['system']['race'] > 0 && $spacegame['system']['race'] != $spacegame['player']['race']) {
			echo 'The dealer has a ' . $spacegame['races'][$spacegame['system']['race']]['caption'] . ' tax of ' . number_format($spacegame['other_tax_rate'], 1) . '%';
		}
	?>
</div>
<div class="header5 header_bold">
	We carry the following goods. Items which are out of stock will
	be routinely replenished as we are able to.
</div>
<?php foreach ($spacegame['inventory_groups'] as $group => $members) { ?>
	<div class="dealer_shelf">
		<div class="header4 header_bold">
			<?php 
				$type_caption = $spacegame['inventory'][$members[0]]['type_caption'];
				echo $type_caption;
			?>
		</div>
		<?php if ($type_caption == 'Ships') {
			$alignment_adjust = 1.0; ?>
		
			<?php if ($spacegame['player']['ship_type'] <= 0) { ?>
				<div class="dealer_text">
					<span class="bigger">You are in an escape pod. You can select one of the level 1 ships
					below and ignore the price - it will be subsidized for you by the
					Imperial Government.</span>
				</div>
			<?php } else { 
				
				if ($spacegame['player']['alignment'] <= NEG_ALIGN_PER_PERCENT) {
					$alignment_adjust = floor($spacegame['player']['alignment'] / NEG_ALIGN_PER_PERCENT);
				
					?>
					<div class="dealer_text">
						Your negative alignment is abhorrent. I have no choice to but to
						add another <?php echo $alignment_adjust; ?>% to the final cost.
					</div>
				<?php
					$alignment_adjust = 1.0 + ($alignment_adjust / 100);

				} else if ($spacegame['player']['alignment'] >= POS_ALIGN_PER_PERCENT) {
					$alignment_adjust = floor($spacegame['player']['alignment'] / POS_ALIGN_PER_PERCENT);

					?>
					<div class="dealer_text">
						Your positive alignment is commendable. Please allow us to give you
						a <?php echo $alignment_adjust; ?>% discount off the final cost.
					</div>
				<?php
					$alignment_adjust = 1.0 - ($alignment_adjust / 100);

				} ?>
				<div class="dealer_text">
					Be warned you get no credits back for trading in a ship, nor do
					you keep any weapons or cargo.
				</div>
			<?php } ?>

		<?php } ?>
		<div class="dealer_items">
			<?php foreach ($members as $member) {
				$item = $spacegame['inventory'][$member];
				$item['race_locked'] = isset($item['details']['race']) && $item['details']['race'] > 0 && ($item['details']['race'] != $spacegame['player']['race']);
				$item['level_locked'] = isset($item['details']['level']) && $item['details']['level'] > 0 && ($item['details']['level'] > $spacegame['player']['level']);
				$item['rank_locked'] = isset($item['details']['rank']) && $item['details']['rank'] > 1 && ($item['details']['rank'] > $spacegame['player']['rank']);
				$item['div_class'] = strtolower($item['type_caption']);
			?>
				<div class="dealer_item dealer_item_<?php echo $item['div_class']; ?>">
					<form action="handler.php" method="post" target="_top">
					<?php
						switch ($item['type_caption']) {
							case 'Ships':
								$amount_to_transfer = 1;

								?>
								<div class="dealer_ship_icon">&nbsp;</div>
								<div class="dealer_ship_level align_center">
									Rank <?php echo $item['details']['rank']; ?>
								</div>
								<div class="dealer_ship_stats">
									<div class="header5 header_bold">
										<?php echo $item['details']['caption']; ?>
									</div>
									<div class="dealer_ship_text">
										<?php echo $item['details']['shields']; ?><img src="res/shields.png" title="Shields" alt="S" width="16" height="16" />&nbsp;
										<?php echo $item['details']['armor']; ?><img src="res/armor.png" title="Armor" alt="A" width="16" height="16" />&nbsp;
										<?php echo $item['details']['holds']; ?><img src="res/holds.png" title="Holds" alt="H" width="16" height="16" />&nbsp;
									</div>
									<div class="dealer_ship_text">
										<strong>
											<?php
												if ($spacegame['player']['ship_type'] <= 0 && $item['details']['rank'] <= 1) {
													echo '<big>Free!</big> 0';
												}
												else {
													echo number_format($item['final_price'] * $alignment_adjust); 
												}
											?>											
										</strong><img src="res/credits.png" title="Credits" alt="¢" width="16" height="16" />
									</div>
								</div>
								<?php
								break;

							case 'Goods':
								$amount_to_transfer = isset($spacegame['ship']['holds']) ? $spacegame['ship']['holds'] : 0;
								$item['level_locked'] = false;
								$item['rank_locked'] = false;

								if (isset($spacegame['cargo_volume'])) {
									$amount_to_transfer -= $spacegame['cargo_volume'];
								}

								?>
								<div class="dealer_goods_image">
									<img src="res/goods/<?php echo $item['details']['safe_caption']; ?>.png" width="32" height="32" alt="dealer good" />
								</div>
								<div class="dealer_goods_text align_center">
									<?php echo $item['details']['caption']; ?>
								</div>
								<div class="dealer_goods_price align_center">
									<?php echo number_format($item['final_price']); ?><img src="res/credits.png" width="16" height="16" alt="credits" />
								</div>
								<div class="dealer_goods_stock align_center">
									<?php echo number_format($item['stock']); ?> Left
								</div>
								
								<?php
								break;

							default:
								?>
								Don't know what it is, but I have
								<?php echo $item['stock']; ?> of
								them and they run <?php echo $item['final_price']; ?>
								credits.
								<?php
						}

					?>

						<?php if ($item['race_locked']) { ?>
							<div class="dealer_race_locked dealer_item_<?php echo $item['div_class']; ?>">&nbsp;</div>
						<?php } else if ($item['level_locked']) { ?>
							<div class="dealer_level_locked dealer_item_<?php echo $item['div_class']; ?>">&nbsp;</div>
						<?php } else if ($item['rank_locked']) { ?>
							<div class="dealer_rank_locked dealer_item_<?php echo $item['div_class']; ?>">&nbsp;</div>
						<?php } else { ?>
							<div class="dealer_buttons_<?php echo $item['div_class']; ?>">
								<input class="dealer_form_text" id="amount<?php echo $member; ?>" name="amount" type="text" maxlength="4" size="5" value="<?php echo $amount_to_transfer; ?>" />
								<script type="text/javascript">drawButton('sb<?php echo $member; ?>', 'buy', 'validate_dealer()', 'dealer_form_button');</script>
							</div>
							<input type="hidden" name="task" value="dealer" />
							<input type="hidden" name="plid" value="<?php echo $place_id; ?>" />
							<input type="hidden" name="item_id" value="<?php echo $item['id']; ?>" />
						<?php } ?>
						<input type="hidden" name="form_id" value="<?php echo $_SESSION['form_id']; ?>" />
					</form>
				</div>
			<?php } ?>
			<br class="clear" />
		</div>
	</div>
<?php
	}

	if ($spacegame['inventory_count'] <= 0) { ?>

		<div class="dealer_text">
			Sorry, this dealer has no inventory at this time.
		</div>

 <?php } ?>

<?php
	include_once('tmpl/html_end.php');
?>