/**
 * General instructions for using this package.
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
 *

 NOTICE: Before you can start this game you need to set it up.

  0) Install Apache, PHP, and MySQL and make sure they all work with
     with each other

  1) Upload db structure and initial data

  2) Make the www folder visible from the web

  3) Create a user and grant admin privs through the db


CREATING A GALAXY AND STARTING THE GAME.

	1) From the admin.php, select System Editor. If a galaxy already exists
	   the map will represent it, otherwise each load of this page will
	   produce a random galaxy.

	   The image generator "map.php" can be loaded in a separate frame and
	   refreshed until the numbers are acceptable. Across the top, the numbers
	   are the total star count, the seed, non-racial sectors (should be zero),
	   followed by Xollian, Mawlor, and Zyck'lirg system count. Ideally the
	   number of racial sectors should be even, so refresh a few times until
	   they are no more than one off or so and copy the seed.

    2) Update inc/CONFIG.php with the GALAXY_SEED you are going to want to use.

    3) When refreshing the System Editor page your galaxy seed should now pop
       up. Go ahead and hit the Update button. After a few moments the new
       galaxy will be created. Warning: any logged in player will be logged out
       forcefully in a way which tells them a spoof is detected. Be ready to
       explain what you did.

    4) Right click on the generated galaxy image and save it as "galaxy.png" in
       the res folder. This is used by DSS, docs, and other places.

    5) The game expects a "goods" folder in the "res" folder. You must add this
       or link to another goods folder. If you add it you can use admin.php to
       create goods and upgrades.

    6) In the admin.php page select Port Editor. Hit Reset Ports to build up the
       ports in the protected sectors.

    7) TODO .. add ships

    8) You should be ready to play!

 *
 *
 *
 */
 