<?php
/**
 * Command-line script to run background events.
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
 *
 * ----------------------------------------------------------------------
 * Run this from the command line
 *
 *
 *
 */
	if (!(php_sapi_name() === 'cli')) {
		header('Location: login.php');
		die('You must run this from the command line.');
	}

	define('CLI', true);
	echo "Starting event processor. Welcome!\n";

	// Load the events.
	include_once('inc/events.php');
		
	$running = true;
	$current_time = PAGE_START_TIME;

	do { // depends on ($running)

		$elapsed = time() - $current_time;
		$current_time += $elapsed;

		foreach ($spacegame['events'] as $event) {
			
			$time_left = $event->getTimeLeft($elapsed);

			if ($time_left <= 0) {
				$event->run();
				$event->resetTimeLeft();
			}

			sleep(1);
		}

		sleep(mt_rand(1,7));
	} while ($running);

	echo "Terminating Event Processor. Goodbye!\n";

?>