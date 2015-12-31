<?php
/**
 * Administration page for the galaxy.
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
	
	$seed = GALAXY_SEED;

	if (isset($_REQUEST['seed']) && is_numeric($_REQUEST['seed'])) {
		$seed = $_REQUEST['seed'];
	}

?>
<div class="header2">Galaxy System Administration</div>
<div class="docs_text">
	You can manipulate existing systems or create new systems using
	this interface. Numerous systems can impact game performance so
	use some thought with this tool.
</div>
<hr />
<div class="header3">
	Regenerate Galaxy
</div>
<div class="docs_text">
	Enter a seed to regenerate the galaxy. Note that the existing galaxy will
	be erased. Sorry!
</div>
<div class="docs_text">
	<form action="handler.php" method="post">
		<label for="seed">Seed:</label>
		<input id="seed" name="seed" type="text" maxlength="12" size="13" value="<?php echo $seed; ?>" />

		<script type="text/javascript">drawButton('generate1', 'update', 'validate_generate()')</script>
		<input type="hidden" name="task" value="generate_galaxy" />
	</form>
</div>
<div class="docs_text">
	<img src="map.php?seed=<?php echo $seed; ?>" width="600" height="600" alt="Galaxy Map" title="Generated Galaxy Map" />
</div>


