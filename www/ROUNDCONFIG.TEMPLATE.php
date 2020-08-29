<?php
/**
 * Boilerplate game round configuration details.
 *
 * Copy this file with or without the header license crap and change the
 * defines to those which match your system.
 *
 * The output file name should be:  ROUNDCONFIG.php
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
 * ---------------------------------------------------------------------------
 *
 * Copy this file to ROUNDCONFIG.php and edit the following fields.
 */

	// NOTICE: Other configuration options located in:
	//
	//		* inc/config.php
	//		* DBCONFIG.php

	// Whether or not we are in development. This will reduce upgrades, build,
	// and research times to make it easier to test the game.
	define('DEV_ROUND', true);

	// Set this to a time to start the round. Inflation begins here, so be sure
	// to have a good policy of when the flood gates are opened.
	define('START_OF_ROUND', 1591848023);

	// How long your round is expected to last. Beyond this point the round
	// becomes a HAVOC round, where ships and weapons are way cheaper so the
	// players can cut loose.
	define('LENGTH_OF_ROUND', 16070400); // About six months.

	// This is only really used by the galaxy generator. Setting it to a favored
	// seed saves some development time.
	define('GALAXY_SEED', 1629859799);