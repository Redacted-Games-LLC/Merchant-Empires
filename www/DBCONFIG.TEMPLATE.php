<?php
/**
 * Boilerplate database configuration details.
 *
 * Copy this file with or without the header license crap and change the
 * defines to those which match your system.
 *
 * The output file name should be:  DBCONFIG.php
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
 *
 * Copy this file to DBCONFIG.php and edit the following fields.
 */


	// DELETE THE FOLLOWING LINE WHEN YOU HAVE FINISHED MAKING CHANGES.
	die('I did not delete the line I was supposed to in DBCONFIG.php when done.');

	// This information is for the database server which holds all game tables
	define('DB_HOST', 'localhost');
	define('DB_USER', 'spacegame');
	define('DB_PASS', 'db password here');
	define('DB_NAME', 'spacegame');

	// This information is for the database server which holds user tables
	define('USER_DB_HOST', 'localhost');
	define('USER_DB_USER', 'spacegame');
	define('USER_DB_PASS', 'db password here');
	define('USER_DB_NAME', 'spacegame');

	// IMPORTANT: The following is a global salt used to encrypt a password for
	// storage in the database. You should change this to a different random
	// string. If you don't have access to a secure random number generator you
	// *could* fall back on grabbing some random chars from https://www.random.org/
	//
	//        https://www.random.org/bytes/
	//
	// If you set this to about 15 or 20 hexadecimal bytes you can remove the
	// spaces and dump the output into this define.
	//
	// CHANGING THIS INVALIDATES ALL PASSWORDS FOR ALL USERS

	define('GLOBAL_SALT', '8b5991b7d11a3cf7cf54ce85237a336d');



?>