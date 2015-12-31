<?php
/**
 * Abstract class for event processor events and supporting code.
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

	function register_event($event) {
		global $spacegame;
		$spacegame['events'][] = $event;
	}

	abstract class Event {
		private $time_left = 0;

		public function __construct() {
			$this->resetTimeLeft();
		}

		abstract public function run();
    	abstract public function getRunTime();

    	public function getTimeLeft($elapsed = 0) {
    		$this->time_left -= $elapsed;
    		return $this->time_left;
    	}

    	public function resetTimeLeft() {
    		$this->time_left = $this->getRunTime();
    	}
	}


	do {

		$spacegame['events'] = array();

		$files = scandir('inc/events');

		foreach ($files as $file) {
			if (preg_match('/^evt_[_a-zA-Z0-9]{2,15}\.php$/', $file) > 0) {
				include_once('inc/events/' . $file);
			}
		}

	} while (false);


?>