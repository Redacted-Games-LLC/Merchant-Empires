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
	echo "\n\n";
	echo date("Y-mM-d") . " Starting event processor. Welcome!\n";

	// Load the events.
	include_once('inc/events.php');

	echo "\n";
	echo "The following events are being monitored:\n\n";
	$spacegame['event_names'] = array();

	foreach ($spacegame['events'] as $event) {

		$event_name = get_class($event);

		if (substr_compare($event_name, "Event_", 0, 6) >= 0) {
			
			$event_name = substr($event_name, 6);
			$len = strlen($event_name);

			echo "    " . $event_name . str_repeat(" ", 20 - $len) . $event->getRunTime() . " seconds\n";

			$spacegame['event_names'][$event_name] = $event;
		}

		
	}	

	echo "\n";
	echo "The event queue is starting now.\n\n";


	$current_time = PAGE_START_TIME;
	$running = true;

	while ($running) { // depends on ($running)

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

		sleep(1);

	}; // while $running

	echo "Terminating Event Processor. Goodbye!\n";

?>