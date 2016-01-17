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
 
 WARNING: Game will not be ready to play until these steps are completed,
 so don't just unzip the source into the web root, put it somewhere outside
 of the web root and protect web access to it until you are ready to go.

  0) Install Apache, PHP, and MySQL.
  
  1) Enable php mods gd2 and mysqli.
  
  2) Ensure that everything from step 1 and 2 is working, perhaps by 
  using <?php phpinfo(); ?> through a served up page.

  3) Create a schema on MySQL for the game and grant privs to a user
  so it can access it.
  
  4) Insert the db sql files in order from the db folder.

  5) Copy www/DBCONFIG.TEMPLATE.php to www/DBCONFIG.php and edit the files
  
  6) Open up www/inc/config.php and make sure to edit the following items:
  
        LOGIN_LOCKED    Set this to "true" and only admins can log in and
                        signups are blocked. A user has to be created before
                        it can be made an admin, though.

        DEV_ROUND       This dramatically reduces build and research times
                        for development.

        START_OF_ROUND  A timestamp for when the round is considered to have
                        started. Inflation is calculated from this.

        END_OF_ROUND    End of round timestamp sets when players can recover
                        unused Gold key time for the next round and signals
                        the beginning of HAVOC round.						
  
        Remaining configs should be checked over but beware making early
		adjustments as the balance can be thrown radically off.
 
  7) Start the event system using something like:
        
		nohup php events.php > /var/log/space_events.log 2>&1&
		
  8) THE GAME IS NOT READY TO PLAY but now is the time for you to access it from
  a browser to complete setup.

  
CREATING A GALAXY AND STARTING THE GAME.

  0) Create a user but don't create or select a player.

	1) From the command prompt, elevate this user to a superadmin.

	2) Load admin.php in your browser by manually typing the link, select the
	System Editor tool. If a galaxy already exists the map will present it,
	otherwise each load of this page will produce a random galaxy.

	The image generator "map.php" can be loaded in a separate frame and
	refreshed until the numbers are acceptable. Across the top, the numbers
	are the total star count, the seed, non-racial sectors (should be zero),
	followed by Xollian, Mawlor, and Zyck'lirg system count. Ideally the
	number of racial sectors should be even, so refresh a few times until
	they are no more than one off or so and copy the seed.

    3) Update inc/CONFIG.php with the GALAXY_SEED you are going to want to use,
	then go back and reload the system edit. Your selected galaxy should show
	up each refresh now.
	
    4) Hit the Update button. After a few moments the new galaxy will be created.
	Warning: any logged in player will be logged out forcefully in a way which
	tells them a spoof is detected. Be ready to explain what you did.

    5) Right click on the generated galaxy image and save it as "galaxy.png" in
    the www/res folder. This is used by DSS, docs, and other places.

    6) The game expects a "goods" folder in the "www/res" folder. You must add
	this or link to another goods folder. If you add it you can use admin.php to
    create goods and upgrades.

    7) In the admin.php page select Port Editor. Hit Reset Ports to build up the
       ports in the protected sectors.

    8) TODO .. add ships

    9) You should be ready to make the game public and play!

 *
 *
 *
 */
 