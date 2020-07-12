
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=desmondkung_Merchant-Empires&metric=alert_status)](https://sonarcloud.io/dashboard?id=desmondkung_Merchant-Empires)

**NOTICE:**<br>
This game requires a fair amount of technical setup.<br>
It will not be ready to play until the following steps to set it up are completed.

**SECURITY WARNING!**<br>
Do **NOT** unzip this source into your web root.<br>
Place the unzipped files somewhere in-accessible by others, and protect web access to it until you are ready to go.

## Pre-reqs:
 - MySQL 5.7.30
 - PHP 5.6.40

> Software versions listed are old with some already out of support as of CY2020.<br>
> This project was created when those software versions were still current.<br>
> Pre-requisite deviation from the above is known to cause undesired side effects.<br>
> Upgrading of codes to support current versions is planned.

<br>

# Installation Steps:

1. Install a common web server (Apache, IIS, etc.), PHP, and MySQL.

2. Enable php mods gd2 and mysqli.<br>
Edit PHP.ini to allow for script execution time of up to 2 minutes during setup which you can then reduce to 30 seconds when your game is ready to play.

3. Create **TWO** schemas in MySQL - one for user logins, one for game data.<br>
Grant the following privileges to access them:

    - ALTER, DELETE, EXECUTE, INSERT, SELECT, SHOW VIEW, UPDATE

4. Import the following files in order:
 - **db/structure_users.sql** into user schema
 - **db/structure_game.sql** into game schema
 - **db/data_game.sql** into game schema
 - files in db/patches, if any.

    There are usually no patches in a release version.<br>

    **Note:** There is no data to insert into user schema.

5. Get ahold of a game package containing ships, goods, and weapons.<br>
You can get one from the official Merchant Empires by [Redacted] Games LLC at the following link:

    https://www.dropbox.com/s/yaa6ffjbssu9279/round1_rel1.zip?dl=0

    - There will be a file called "data.sql".<br>
    Insert this into your game database.<br>
    
    - Copy or link the goods and rooms as follows:        
    > www/res/goods goods<br>
    > www/res/base/rooms rooms

6. Copy www/DBCONFIG.TEMPLATE.php to www/DBCONFIG.php and edit the new DBCONFIG.php file, following the instructions within.<br>
Change `GLOBAL_SALT` and enter a username into `SIGNUP_ADMIN` to prepare for your first admin.

7. Copy www/ROUNDCONFIG.TEMPLATE.php to www/ROUNDCONFIG.php and edit the new ROUNDCONFIG.php file, following the instructions within.<br>
Pay attention to these settings:

- `DEV_ROUND`<br>
This setting dramatically reduces build and research times for development.

- `START_OF_ROUND`<br>
A timestamp for when the round is considered to have started.<br>
Inflation is calculated from this setting.<br>
A future startup time is the same as a login lock above.<br>

  **Note:** Timestamp can be generated at various websites.

- `LENGTH_OF_ROUND`<br>
How long the round should last.<br>
Un-used gold key time can be recovered from this point, and HAVOC round begins, where ships and weapons are cheaper.

8. Start the event system using the following commands:
  
**Linux**
> `nohup php events.php > /var/log/spacegame/events.log 2>&1`

**Windows**
> `php events.php > events.log`
- A sample events.cmd is provided at the root of this package.

  The event processor should run in the background whenever the game server is running.<br>
  It handles giving turns and upgrading ports, amongst other chores.<br>
  It is quiet. It just runs and works.<br>
  The commands listed above capture events to a log.<br>
  Should the log balloon in size, it indicates possible problem(s) with the system.

9. Sign a user up to be an admin.<br>
Use the username you entered into www/DBCONFIG.php earlier.<br>
Do **NOT** create a player just yet; any player created at this point will appear in an empty galaxy and be unable to move.

10. Select the System Editor tool.<br>
If a galaxy already exists, the map will present it, otherwise each time you reload this page, it will produce a random galaxy.

    ## WARNING: If you were to hit the update button now, the image you see will become the galaxy in the game.<br>

    WAIT A MOMENT.

    You'll want to refresh the page a few times.<br>
    There is a "CLICK HERE" link that gives you a new random galaxy.<br>
    You are going to want a galaxy where each race has a fair number of start systems.<br>
    Here's what to look out for:

- The numbers at the top of the galaxy represent the total star count, followed by the seed used.<br>
After that, the numbers are non-racial, Xollian, Mawlor, and Zyck'lirg count.

- The non-racial count should always be zero!<br>
If this is otherwise, it is an absolute bug and should be reported.

- You'll want the remaining three racial counts to be as equal as possible.<br>
It takes a few moments of refreshing but it won't take long to get a galaxy with numbers like 114 114 113 or so.<br>
I think within 10 of each other is getting to be too much, so give a few refreshes until you see the perfect galaxy for your patience.

- Once you find a galaxy you like, **NOTE THE SEED** from the image - the second number.<br>
**Copy this number before you do anything further!**

11. Update www/ROUNDCONFIG.php with the `GALAXY_SEED` you want to use, then go back and reload the System Editor.<br>
Your selected galaxy should appear each time you refresh. 

12. Right-click on the generated galaxy image and save it as "galaxy.png" in the www/res folder.<br>
This is used by DSS and game docs. **THIS IS CRITICAL.**

13. Now, go ahead and hit the Update button.<br>
After a few moments, the new galaxy will be created.<br>
**Warning:** Any logged-in player will be forcefully logged off in a manner that tells them a spoof is detected.

    If your galaxy fails to create because of script execution timeout, edit your PHP.ini and run it again (refer to step 2).<br>
    You can even create galaxies over a live server!<br>
    But everyone will be logged off quickly.

14. The game expects a "goods" folder in the "www/res" folder.<br>
You must add this or link to another goods folder.<br>
Without it, your goods will have no images.<br>
The default image pack should be made available for download as a separate package by the source maintainer.

15. Once goods are added, go back to the Admin tool "System Editor" and scroll to the bottom.<br>
Hit the "Reset" button to generate the initial ports inside the Imperial zones.<br>
The event processor should then add the upgrades within a few minutes.

16. You should be ready to make the game public and play.<br>
Reduce PHP.ini script execution time to 30 seconds.<br>
Open up DBCONFIG.php and set `LOGIN_LOCKED` to false.
