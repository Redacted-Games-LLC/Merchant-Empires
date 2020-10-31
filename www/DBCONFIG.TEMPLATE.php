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
 * Copy this file to DBCONFIG.php and edit the following fields.
 */

	// This prevents anyone from logging into a game unless they have been
	// granted admin or dev status. If you need to log everyone out at once
	// then use admin tools in game
	define('LOGIN_LOCKED', true);

	// Email for support
	define('EMAIL', 'email_here');

	// Email for gold support
	define('GOLD_EMAIL', 'email_here');

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
	// Changing this should not affect existing users, only new signups. It should
	// be safe to occasionally update this, and maybe you should do just that.
	define('GLOBAL_SALT', 'c3c2526615fb94a6b32681b5c11403e3');

	// Enter a username here to bypass login and signup restrictions. This name will
	// be granted admin status when it signs up. This only needs to be used once 
	// when setting up the round or if no admin accounts are accessible.
	define('SIGNUP_ADMIN', '');

	// SSL CA cert for DB connections
	// Un-comment following lines and replace with appropriate CA cert, if needed.
	// Follow directions in inc/db.php to complete procedure.
	// Remember to enable php_openssl.dll in php.ini for SSL connection to work.
	//define('SQL_SSL_CA_CERT', 'DigiCertGlobalRootG2.crt.pem');
	//define('DB_PORT', '3306');
	//define('USER_DB_PORT', '3306');

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

	// DELETE THE FOLLOWING LINE WHEN YOU HAVE FINISHED MAKING CHANGES.
	die('I did not delete the line I was supposed to in DBCONFIG.php when done.');