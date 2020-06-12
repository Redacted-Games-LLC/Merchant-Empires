
NOTICE: Before you can start this game you will need to set it up.

WARNING: Game will not be ready to play until these steps are completed,
so don't just unzip the source into the web root, put it somewhere outside
of the web root and protect web access to it until you are ready to go.

0) Install a common web server (APACHE, IIS, whatever), PHP, and MySQL.


1) Enable php mods gd2 and mysqli. Edit PHP.INI to allow for script/execution
time of up to 2 minutes during setup which you can reduce to 30s for production.

2) Create TWO schemas on MySQL, one for the user logins and another for
the game; grant privs to access them.

3) Insert the db/structure_users.sql, db/structure_game.sql, and db/data.sql, 
followed by the files in db/patches in order. There are usually no patches
in a release version. Note db/data.sql goes into the game schema. There is
no data to insert into the user schema.

4) Get ahold of game package containing ships, goods, and weapons. You can
get one from the official Merchant Empires by [Redacted] Games LLC at the
following link:

    https://www.dropbox.com/s/yaa6ffjbssu9279/round1_rel1.zip?dl=0

Insert the data.sql file into your game database and set up links from your
source tree:

      www/res/goods goods
      www/res/base/rooms rooms

5) Copy www/DBCONFIG.TEMPLATE.php to www/DBCONFIG.php and then edit the new
DBCONFIG.php file using the instructions inside of it. Make sure to change
GLOBAL_SALT and enter a name into SIGNUP_ADMIN to prepare for your first
admin.

6) Copy www/ROUNDCONFIG.TEMPLATE.php to www/ROUNDCONFIG.php and then edit the
new ROUNDCONFIG.php file using the instructions inside of it. Make sure to
pay attention to these settings:

      DEV_ROUND         This dramatically reduces build and research times
                        for development.

      START_OF_ROUND    A timestamp for when the round is considered to have
                        started. Inflation is calculated from this. A future
                        startup time is the same as a login lock above.

                        You can generate a timestamp at various websites.

      LENGTH_OF_ROUND   How long the round should last. Unused gold key time
                        can be recovered from this point and a HAVOC round
                        begins, where ships and weapons are cheaper.

7) Start the event system using something like:
  
  # Linux
	nohup php events.php > /var/log/spacegame/events.log 2>&1&

  # Windows
  php events.php > events.log

There is an example events.cmd in the root folder of this package.

The event processor should run in the background whenever the game server is
running. It handles giving turns and upgrading ports, amongst other chores.

8) Signup a user to be an admin. Use the name you entered into www/DBCONFIG.php
earlier. DO NOT create a player just yet; any player created at this point will
appear in an empty galaxy and be unable to move.

9) Select the System Editor tool. If a galaxy already exists the map will present
it, otherwise each time you reload this page it will produce a random galaxy.

If you were to hit the update button now, the image you see would become the 
galaxy in the game. You should probably wait on that.

You'll want to refresh the page a few times. The numbers at the top of the galaxy
represent the total star count, followed by the seed used. After that the numbers
are non-racial, Xollian, Mawlor, and Zyck'lirg count. The non-racial count should
always be zero. You'll want the remaining three racial counts to be as equal as
possible. It takes a few moments but won't be long to get a galaxy with
numbers like 114 114 113 or so.

Once you find a galaxy you like NOTE THE SEED from the image, the second number.

10) Update www/ROUNDCONFIG.php with the GALAXY_SEED you are going to want to use,
then go back and reload the System Editor. Your selected galaxy should appear
each time you refresh. 

11) Right click on the generated galaxy image and save it as "galaxy.png" in
the www/res folder. This is used by DSS and game docs. 

12) Now go ahead and hit the Update button. After a few moments the new galaxy
will be created. Warning: any logged in player will be logged out forcefully
in a way which tells them a spoof is detected.

13) The game expects a "goods" folder in the "www/res" folder. You must add
this or link to another goods folder. Without it your goods will have no
images. The default image pack should be made available for download as a 
separate package by the source maintainer.

14) Once goods are added, go back to the Admin tool "System Editor" and scroll
to the bottom. Hit the "Update Ports" button to generate the initial ports inside
the imperial zones. The event processor should then add the upgrades within a few
minutes.

15) You should be ready to make the game public and play. Open up DBCONFIG.php and
set LOGIN_LOCKED to false.

