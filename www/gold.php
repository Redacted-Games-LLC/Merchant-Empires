<?php
/**
 * Gold Membership page
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
	include_once('inc/gold.php');

	$tmpl['page_title'] = 'Gold Membership';

	include_once('tmpl/html_begin.php');

?>

<div class="full_spread">
	<div class="header2 header_bold"><img src="res/gold.png" width="16" alt="gold ribbon" />&nbsp;Gold Membership Information</div>

	<div class="docs_text">
		<?php 
			if ($spacegame['gold']) {
				echo 'Your Gold Membership expires in ';
				echo '<strong>';

				$time_left = $spacegame['player']['gold_expiration'] - PAGE_START_TIME;

				if ($time_left > 604800) {
					$weeks_left = ceil($time_left / 604800);
					echo $weeks_left . ' week' . ($weeks_left == 1 ? '' : 's');
				}
				elseif ($time_left > 86400) {
					$days_left = ceil($time_left / 86400);
					echo $days_left . ' day' . ($days_left == 1 ? '' : 's');
				}
				elseif ($time_left > 3600) {
					$hours_left = ceil($time_left / 3600);
					echo $hours_left . ' hour' . ($hours_left == 1 ? '' : 's');
				}
				elseif ($time_left > 60) {
					$minutes_left = ceil($time_left / 60);
					echo $minutes_left . ' minute' . ($minutes_left == 1 ? '' : 's');
				}
				else {
					echo $time_left . ' second' . ($time_left == 1 ? '' : 's');
				}

				echo ' on ' . date(DATE_RFC850, $spacegame['player']['gold_expiration']) . '</strong>. ';

				if (!HAVOC_ROUND) {
					echo 'Extend this time by entering another key below.';
				}
				else {
					echo '<br /><br />';
					echo '<strong>The round is over!</strong> You can obtain a Gold Key with '; 
					echo 'your remaining membership time by ';
					echo '<a href="handler.php?task=gold&amp;subtask=obtain&amp;form_id='. $_SESSION['form_id'] .'">clicking here</a>.</big> ';
					echo ' The key will be stored with your user automatically.';
				}

			}
			else {
				echo 'You do not have an active gold membership. To activate one, ';
				echo 'enter a Gold key below.';
			}
		?>
	</div>
	<hr />
	<div class="header3 header_bold">Enable Gold Key</div>
	<?php if (!HAVOC_ROUND) { ?>
		<div class="docs_text">
			Enter a key here to add Gold Membership time to your player. You can
			not enable a key if you have more than <?php echo KEY_ACTIVATION_LIMIT; ?>
			days remaining already (<?php echo floor(KEY_ACTIVATION_LIMIT / 7); ?>
			weeks).
		</div>
		<div class="docs_text">
			<form action="handler.php" method="post">
				Key: <input type="text" name="key" value="" maxlength="72" size="72" />
				<script type="text/javascript">drawButton('enable', 'enable', 'validate_enable()');</script>
				<input type="hidden" name="task" value="gold" />
				<input type="hidden" name="subtask" value="enable" />
				<input type="hidden" name="form_id" value="<?php echo $_SESSION['form_id']; ?>" />
			</form>
		</div>
	<?php } else { ?>
		<div class="docs_text">
			You cannot enable gold keys during
			<a href="docs.php?page=havoc" target="_blank">Havoc Round</a>.
		</div>
	<?php } ?>
	<hr />
	<div class="header3 header_bold">Store Gold Key</div>
	<div class="docs_text">
		Storing a Gold key protects it by attaching it to your user account. From there
		it can be accessed through this page from any of your active players.
	</div>
	<div class="docs_text">
		Once attached nobody else can use the key unless you remove it or transfer it to
		them.
	</div>
	<div class="docs_text">
		<form action="handler.php" method="post">
			<input type="text" name="key" value="" maxlength="72" size="72" />
			<script type="text/javascript">drawButton('add', 'add', 'validate_add()');</script>
			<input type="hidden" name="task" value="gold" />
			<input type="hidden" name="subtask" value="add" />
			<input type="hidden" name="form_id" value="<?php echo $_SESSION['form_id']; ?>" />
		</form>
	</div>
	<hr />
	<div class="header4 header_bold">Your Stored Keys</div>
	<div class="docs_warning">
		<strong>WARNING:</strong> Removing a key will take it out of storage.
		Not only can the Gold key be activated by anyone who has it, if you have
		no other record of the key it will be lost forever!
	</div>
	
	<?php
		if ($spacegame['gold_key_count'] <= 0) { 
			echo '<div class="docs_text">';
			echo 'You have no stored keys.';
			echo '</div>';
		}
		else {

			echo '<div class="docs_text">';
			
			echo '<table class="gold_key">';
			echo '<tr class="gold_key">';
				echo '<th class="gold_key">Key</th>';
				echo '<th class="gold_key">Days</th>';
				echo '<th class="gold_key">Actions</th>';
			echo '</tr>';
			

			foreach ($spacegame['gold_keys'] as $record_id => $key) {
				echo '<tr class="gold_key">';
				echo '<td class="gold_key gold_padding">' . $key['key'] . '</td>';
				echo '<td class="gold_time gold_padding">' . floor($key['time']/86400) . '</td>';
				echo '<td class="align_center gold_padding">';
					if (!HAVOC_ROUND) {
						echo '[<a href="handler.php?task=gold&amp;subtask=enable&amp;key=' . $key['key'] . '&amp;form_id='. $_SESSION['form_id'] .'">Enable</a>]&nbsp;&nbsp;&nbsp;&nbsp;';
					}
					echo '[<a href="handler.php?task=gold&amp;subtask=remove&amp;key=' . $key['key'] . '&amp;form_id='. $_SESSION['form_id'] .'">Remove</a>]';
				echo '</td>';
				echo '</tr>';
			}

			echo '</table>';

			echo '</div>';

		}
	?>
	<hr />
	<div class="header3 header_bold">Transfer Gold Key</div>
	<div class="docs_text">
		You can transfer a gold key to another player. When you do so it will
		attach to their <em>user</em> and they will be free to enable it on any
		one of their players.
	</div>
	<div class="docs_text">
		They will receive no message or indicator and unless the receiving user
		checks their stored Gold key list they may not even know they have it.
		You may decide if you want to send them a message or not.
	</div>
	<div class="docs_warning">
		<strong>WARNING:</strong> This action cannot and will not be reversed.
		Make sure you insert the correct key and enter the correct player name.
	</div>
	<div class="docs_text">
		<form action="handler.php" method="post">
			Key: <input type="text" name="key" value="" maxlength="72" size="72" /><br />
			<br />
			Player: <input type="text" name="player" value="" maxlength="24" size="24" />
			<script type="text/javascript">drawButton('transfer', 'transfer', 'validate_transfer()');</script>
			<input type="hidden" name="task" value="gold" />
			<input type="hidden" name="subtask" value="transfer" />
			<input type="hidden" name="form_id" value="<?php echo $_SESSION['form_id']; ?>" />
		</form>
	</div>
	
	<br class="clear" />
</div>
	
<?php	
	include_once('tmpl/html_end.php');
?>