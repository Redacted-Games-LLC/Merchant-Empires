<?php
/**
 * Template page for ship deployment.
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
	include_once('inc/cargo.php');
	include_once('inc/systems.php');
	
?>
<div class="header2 header_bold">Deploy Technology</div>
<?php if ($spacegame['tech_count'] > 0) { ?>
	<div class="docs_text">
		Select a technology to deploy.
	</div>
	<?php

	$counter = 0;
	foreach ($spacegame['tech'] as $record_id => $tech) { 

		$deploy_amount = 1;
		$selectable_amount = false;
		$selectable_caption = false;

		// >0 tech amount with type=0 is deployable
		// type=1 is for weapons/solutions
		if ($spacegame['goods'][$tech['good']]['type'] > 0) {
			continue;
		}

		?>

		<div class="header3 header_bold">
			<img src="res/goods/<?php echo $spacegame['goods'][$tech['good']]['safe_caption']; ?>.png" width="24" height="24" alt="tech good" />
			<?php echo $spacegame['goods'][$tech['good']]['caption']; ?>
		</div>
		
		<div class="docs_text">
			<?php
				switch ($spacegame['goods'][$tech['good']]['safe_caption']) {
					case 'port_package':
						?>
						When deployed on a planetoid which does not already have one of these
						a new port is created. Random start goods will be added and it will be
						ready for trade.
						<?php
						
						if ($spacegame['player']['base_id'] > 0) {
							$deploy_amount = 0;
							echo 'You must be in space to deploy a port package.';
						}
						elseif (!isset($spacegame['system'])) {
							$deploy_amount = 0;
							echo 'You must be in a solar system to deploy a port package.';
						}
						elseif ($spacegame['system']['protected']) {
							$deploy_amount = 0;
							echo 'You cannot build a port in protected systems.';
						}
						else {
							$deploy_amount = 1;
						}

						break;

					case 'shields':
					case 'armor':
						
						echo 'Deploying will replenish lost tech. You are carrying ';
						echo $tech['amount'] . ' ' . $spacegame['goods'][$tech['good']]['caption'] . '. ';

						$current_amount = $spacegame['player'][$spacegame['goods'][$tech['good']]['safe_caption']];
						$bonus_level = $spacegame['player'][$spacegame['goods'][$tech['good']]['safe_caption'] . '_bonus'];

						$max_amount = $spacegame['ships'][$spacegame['player']['ship_type']][$spacegame['goods'][$tech['good']]['safe_caption']];
						$bonus_amount = $max_amount * $bonus_level * constant(strtoupper($spacegame['goods'][$tech['good']]['safe_caption']) . '_BONUS');

						$max_amount += $bonus_amount;

						if ($current_amount < $max_amount) {
							$deploy_amount = min($tech['amount'], $max_amount - $current_amount);
							echo 'You can replenish up to ' . $deploy_amount . ' units ';
							echo 'by clicking the following button. ';
						}
						else {
							$deploy_amount = 0;
							echo 'You do not need to replenish any ' . $spacegame['goods'][$tech['good']]['caption'];
						}

						break;

					case 'solar_collectors':
						
						echo 'When deployed on a star you will be able to collect ';
						echo 'energy. If another solar collector already exists this ';
						echo 'will increase its output. A maximum of ' . SOLAR_COLLECTORS_PER_SECTOR . ' ';
						echo 'collectors can be installed on any star.';
						
						if ($spacegame['player']['base_id'] > 0) {
							$deploy_amount = 0;
							echo 'You must be in space to deploy a solar collector.';
						}
						elseif (!isset($spacegame['system'])) {
							$deploy_amount = 0;
							echo 'You must be in a solar system to deploy a solar collector.';
						}
						elseif ($spacegame['system']['protected']) {
							$deploy_amount = 0;
							echo 'You cannot build a solar collector in protected systems.';
						}
						else {
							$deploy_amount = 1;
						}

						break;

					case 'drones':

						$x = $spacegame['player']['x'];
						$y = $spacegame['player']['y'];

						$own_count = 0;
						$total_count = 0;

						$rs = $db->get_db()->query("select owner, amount from ordnance where x = {$x} and y = {$y} and good = '34'");

						$rs->data_seek(0);
						while ($row = $rs->fetch_assoc()) {
							$total_count += $row['amount'];

							if ($spacegame['player']['record_id'] == $row['owner']) {
								$own_count += $row['amount'];
							}
						}

						echo 'A single drone will passively report hostile ship movement. Multiple ';
						echo 'drones will actively attack hostile ships.';

						echo '<br /><br />';
						
						if ($own_count > 0) {
							if ($spacegame['ship']['holds'] - $spacegame['cargo_volume'] < $own_count) {
								echo 'You do not have enough cargo space to pick up all of the drones.';
							}
							else {
								echo 'Click <a href="handler.php?task=ship&amp;subtask=pickup&amp;good=34&amp;return=ship&amp;form_id='. $_SESSION['form_id'] .'">here</a> ';
								echo 'to retrieve '. $own_count .' drone(s).';
							}

							echo '<br /><br />';
						}

						if ($own_count >= MAX_ORDNANCE_PER_PLAYER || $total_count >= MAX_ORDNANCE_PER_SECTOR) {
							$deploy_amount = 0;

							echo 'This sector is at its limit for drones.';
						}
						elseif ($spacegame['player']['base_id'] > 0) {
							$deploy_amount = 0;
							echo 'You must be in space to deploy ordnance.';
						}
						elseif (!isset($spacegame['system'])) {
							$deploy_amount = 0;
							echo 'You must be in a solar system to deploy ordnance.';
						}
						elseif ($spacegame['system']['protected']) {
							$deploy_amount = 0;

							echo 'You cannot deploy drones in a protected sector.';
						}
						else {
							$deploy_amount = min(MAX_ORDNANCE_PER_PLAYER - $own_count, $tech['amount']);
							$deploy_amount = min(MAX_ORDNANCE_PER_SECTOR - $total_count, $deploy_amount);

							$selectable_amount = true;

							echo 'You can deploy up to ' . $deploy_amount . ' drones in this sector.';
						}

						break;

					case 'mines':

						$x = $spacegame['player']['x'];
						$y = $spacegame['player']['y'];
						$own_count = 0;
						$total_count = 0;

						$rs = $db->get_db()->query("select owner, amount from ordnance where x = {$x} and y = {$y} and good = '33'");

						$rs->data_seek(0);
						while ($row = $rs->fetch_assoc()) {
							$total_count += $row['amount'];

							if ($spacegame['player']['record_id'] == $row['owner']) {
								$own_count += $row['amount'];
							}
						}

						echo 'Players have a chance of hitting mines when leaving sectors. ';

						if ($own_count > 0) {
							echo 'Mines cannot be retrieved once deployed.';								
						}

						echo '<br /><br />';

						if ($own_count >= MAX_ORDNANCE_PER_PLAYER || $total_count >= MAX_ORDNANCE_PER_SECTOR) {
							$deploy_amount = 0;

							echo 'This sector is at its limit for mines.';
						}
						elseif ($spacegame['player']['base_id'] > 0) {
							$deploy_amount = 0;
							echo 'You must be in space to deploy ordnance.';
						}
						elseif (!isset($spacegame['system'])) {
							$deploy_amount = 0;
							echo 'You must be in a solar system to deploy ordnance';
						}
						elseif ($spacegame['system']['protected']) {
							$deploy_amount = 0;
							echo 'You cannot deploy mines in a protected sector.';
						}
						else {
							$deploy_amount = min(MAX_ORDNANCE_PER_PLAYER - $own_count, $tech['amount']);
							$deploy_amount = min(MAX_ORDNANCE_PER_SECTOR - $total_count, $deploy_amount);

							$selectable_amount = true;

							echo 'You can deploy up to ' . $deploy_amount . ' mines in this sector.';
						}

						break;

					case 'base_package':

						echo 'A base provides a place for players to remain safe from ';
						echo 'attack, as long as the defenses hold out...';

						if ($spacegame['player']['base_id'] > 0) {
							$deploy_amount = 0;
							echo 'You must be in space to deploy a base package.';
						}
						elseif (!isset($spacegame['system'])) {
							$deploy_amount = 0;
							echo 'You must be in a solar system to deploy a base package.';
						}
						elseif ($spacegame['system']['protected']) {
							$deploy_amount = 0;
							echo 'You cannot build a base in protected systems.';
						}
						else {
							$deploy_amount = 1;
							$selectable_caption = true;
						}

						
						break;


					default:
						echo 'Not sure what this does. Be careful with it...';
						break;
				}

				$counter++;
			?>
			<?php if ($deploy_amount > 0) { ?>
			<div class="docs_text">
				<form action="handler.php" method="post">
					<script type="text/javascript">drawButton('deploy<?php echo $counter; ?>', 'deploy', 'validate_deploy()');</script>
					<input type="hidden" name="task" value="ship" />
					<input type="hidden" name="subtask" value="deploy" />
					<input type="hidden" name="cargo_id" value="<?php echo $record_id; ?>" />
					<input type="hidden" name="return" value="ship" />

					<?php if ($selectable_amount) { ?>
						&nbsp;&nbsp;<input type="text" name="amount" value="<?php echo $deploy_amount; ?>" maxlength="5" size="4" />
					<?php } else { ?>
						<input type="hidden" name="amount" value="<?php echo $deploy_amount; ?>" />
					<?php } ?>

					<?php if ($selectable_caption) { ?>
						<br />
						<br />
						<label for="base_caption">Base Name:</label>
						<input type="text" id="base_caption" name="caption" maxlength="24" size="30" value="<?php echo DEFAULT_BASE_CAPTION; ?>" /><br />
					<?php } ?>
					<input type="hidden" name="form_id" value="<?php echo $_SESSION['form_id']; ?>" />
				</form>
			</div>
			<?php } ?>
			<hr id="noshade" />
		</div>
		
	<?php } ?>
	<div class="docs_text">
		No further technology to deploy.
	</div>
<?php } else { ?>
	<div class="docs_text">
		You are not carrying deployable technology.
	</div>
<?php } ?>
<hr />
<div class="header2 header_bold">Jettison Cargo</div>
<div class="docs_text">
	You can dump your cargo for a fee of <?php echo number_format(($spacegame['player']['level'] + 1) * CARGO_DUMP_COST * INFLATION_MULTIPLIER); ?>
	<img src="res/credits.png" width="20" height="20" alt="credits" /> credits and <?php echo CARGO_DUMP_TURNS; ?>
	turns. We do not advise planning trade routes with the jettisoning of cargo in mind.
</div>
<div class="docs_text">
	<form action="handler.php" method="post">
		<script type="text/javascript">drawButton('jettison_button', 'delete', 'validate_empty_cargo()');</script>
		<input type="hidden" name="task" value="ship" />
		<input type="hidden" name="subtask" value="empty_cargo" />
		<input type="hidden" name="return" value="ship" />
		<input type="hidden" name="form_id" value="<?php echo $_SESSION['form_id']; ?>" />
	</form>
</div>