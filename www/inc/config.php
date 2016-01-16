<?php
/**
 * Stores all of the config constants used by the game except db login.
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

	// This prevents anyone from logging into a game unless they have been
	// granted admin or dev status. If you need to log everyone out at once
	// then use admin tools in game
	define('LOGIN_LOCKED', true);

	// Whether or not we are in development. This will reduce upgrades, build,
	// and research times to make it easier to test the game.
	define('DEV_ROUND', true);

	// Set this to a time to start the round. Inflation begins here, so be sure
	// to have a good policy of when the flood gates are opened.
	define('START_OF_ROUND', 1445000000);

	// Set this to a time bigger than start of round to end the game. This allows
	// players to create replacement gold keys for their remaining time.
	define('END_OF_ROUND', START_OF_ROUND + 16070400);

	// The title of the game to display at the top of all pages. Can be html...
	define('GAME_TITLE', 'Merchant Empires');

	// This should be used in place of time() outside of events. Since this file
	// is included early it actions can be computed from delta page load, not 
	// after some other stuff has been running.
	define('PAGE_START_TIME', time());
	
	// This is used for page build later on.
	define('PAGE_START_OFFSET', -microtime(true));

	// Defines inflation to be about 3% per day from start of round
	define('INFLATION', (PAGE_START_TIME - START_OF_ROUND) * 3 / 86400);

	// Multiplier to make some computations faster
	define('INFLATION_MULTIPLIER', 1 + (INFLATION / 100));

	// Whether or not we are in havoc round, enabling havoc reduces the cost of
	// ships and weapons and eliminates most alignment restrictions. Currently
	// not implemented in code.
	define('HAVOC_ROUND', PAGE_START_TIME > END_OF_ROUND);
	
	// Maximum number of turns 
	define('MAX_TURNS', 3000);

	// Start turns should be less than MAX_TURNS above so the game triggers an update.
	define('START_TURNS', 2500);

	// How often to give turns to a player. Other stuff is triggered this way as well.
	define('TURN_UPDATE_TIME', 600);

	// How many turns to give per update.
	define('TURNS_PER_UPDATE', 10);
	
	// Players will start within this distance of a ship dealer.
	define('MAX_START_DISTANCE', 10);

	// Players will start with this amount of credits to spend.
	define('START_CREDITS', 10000 * INFLATION_MULTIPLIER);

	// Players can have this many players but check interface code before changing it.
	define('MAX_PLAYERS_PER_USER', 4);

	// This is only really used by the galaxy generator. Setting it to a favored
	// seed saves some development time.
	define('GALAXY_SEED', 1440469921);

	// Defines the size of the galaxy. Note that the game was coded with 720 in mind and 
	// while the limit is 1000x1000 that is for stars; some planets might render outside.
	define('GALAXY_SIZE', 720);

	// Minimum size of a system which can have a binary star
	define('BINARY_STAR_MIN_RADIUS', 4);

	// Chances of a system being binary
	define('BINARY_STAR_CHANCE', 0.4);

	// How many planets per system radius. The radius is randomly chosen for each star.
	define('PLANETS_PER_RADIUS', 2);

	// Maximum amount of solar collectors which can be in a single sector.
	define('SOLAR_COLLECTORS_PER_SECTOR', 4);
	
	// The furthest distance index for port trade goods.
	define('MAX_DISTANCE', 100);

	// How often the event processor runs the process to update ports. The process will
	// look for ports which have not been updated in a certain time (defined below) and
	// run update procedures. This includes checking supply and demand and also running
	// new upgrades.
	define('PORT_EVENT_CYCLE', 23);

	// How often a port wants to regenerate supply and demand, but the time will take
	// longer as a round goes on.
	define('PORT_UPDATE_TIME', 60);

	// How many ports to check per update cycle.
	define('PORTS_PER_UPDATE', 50);
	
	// How many goods to add to a port during update.
	define('GOODS_PER_UPDATE', 5);

	// The maximum amount of goods of one type a port can carry.
	define('PORT_LIMIT', 15000);

	// How many goods needed for upgrade triggers.
	if (DEV_ROUND) {
		define('UPGRADE_START_MULTIPLIER', -100);
	}
	else {
		define('UPGRADE_START_MULTIPLIER', -PORT_LIMIT);
	}

	// Each place type which supports ports has a good count. These next 3
	// lines modify that for starting supply, demand, and upgrades.

	// How much to alter natural good count for new port supply
	define('PLACE_TYPE_SUPPLY_OFFSET', 0);

	// How much to alter natural good count for new port demand
	define('PLACE_TYPE_DEMAND_OFFSET', 1);

	// How much to alter natural good count for new port upgrades
	define('PLACE_TYPE_UPGRADE_OFFSET', -1);

	// When picking an upgrade the game randomly skips over some options. 
	// The larger the value here the more deterministic upgrades will be,
	// and higher level upgrades will be much harder to acheive.
	define('UPGRADE_CHANCE', 30);

	// How long a dot appears on Deep Space Scanner in seconds
	define('DOT_TIME', 20);

	// How far can a dot be seen on Deep Space Scanner
	define('DOT_DISTANCE', 35);

	// For computing attack/defence rating, each player level is worth 10% of an Attack Rating
	define('ATTACK_RATING_PER_LEVEL', 0.1);

	// Each armor on a ship is worth 5% of a defense rating
	define('DEFENSE_RATING_PER_ARMOR', 0.05);

	// Each shield on a ship is worth 4% of a shield rating
	define('DEFENSE_RATING_PER_SHIELD', 0.04);

	// Ship TPS multiplied by this for warp turn cost
	define('WARP_TURN_MULTIPLIER', 4);

	// When generating warps how far from center of system can they be
	define('WARP_LOCATION_VARIANCE', 2);

	define('DEFAULT_SHIP_NAME', '<em>NO SHIP NAME</em>');

	// How much a level 1 player pays to dump cargo before inflation.
	define('CARGO_DUMP_COST', 5000);

	// How many turns a player must use to dump cargo
	define('CARGO_DUMP_TURNS', 100);

	// One of the events spits a timestamp on the console at this interval.
	define('TIMESTAMP_TIME', 600);

	// One of the events performs housekeeping tasks at this interval.
	define('HOUSEKEEPING_TIME', 41);

	// How often the game updates the aligment of a player in seconds.
	define('ALIGNMENT_UPDATE_TIME', 600);

	// How many traded goods is needed to earn an alignment point
	define('TRADES_PER_ALIGNMENT_POINT', 2000);

	// How many upgraded goods is needed to earn an alignment point
	define('UPGRADES_PER_ALIGNMENT_POINT', 1000);

	// How many trades in an enemy port will lose an alignment point.
	define('WAR_TRADES_PER_ALIGNMENT_POINT', 200);

	// How many upgrades in an enemy port will lose an alignment point.
	define('WAR_UPGRADES_PER_ALIGNMENT_POINT', 50);

	// The smallest player level which allows ship-to-ship combat.
	define('MINIMUM_KILLABLE_LEVEL', 6);

	// How much alignment you lose when killing someone of your race.
	define('RACIAL_KILL_PENALTY', 100);

	// Minimum amount of alignment needed to ensure safety in government systems.
	define('SAFE_ALIGNMENT_MINIMUM', -50);

	// How much negative alignment before adjusting the price of a ship
	define('NEG_ALIGN_PER_PERCENT', -5);

	// How much positive alignment before adjusting the price of a ship
	define('POS_ALIGN_PER_PERCENT', 10);

	// Minimum player level to create an alliance
	define('ALLIANCE_CREATION_LEVEL', 5);

	// Percentage of trade income to tax in a zero member alliance
	define('ALLIANCE_START_TAX', 0.01);

	// Percentage to add per member. This is rounded up to whole percent per docs
	define('ALLIANCE_MEMBER_TAX', 0.002);

	// How many alliance join requests a player can have open at one time
	define('ALLIANCE_REQUEST_LIMIT', 3);

	// Number of days an alliance join request will remain open before expiring
	define('OPEN_REQUEST_DAYS', 7);

	// Number of days an alliance rejection will remain visible before disappearing
	define('REJECTED_REQUEST_DAYS', 3);

	// How long a player can remain idle before considered offline
	define('ONLINE_PLAYER_TIME', TURN_UPDATE_TIME + 30);

	// How long a player can not join the game before considered inactive
	define('ACTIVE_PLAYER_TIME', 3600 * 24 * 7 * 6);

	// Turn cost per trade
	define('TRADE_TURN_COST', 1);

	// Turn cost to set a target like to Ship Dealer or Tech Dealer
	define('TARGET_TURN_COST', 2);

	// Turn cost to use the scanner
	define('SCAN_TURN_COST', 3);

	// Turn cost to deploy technology, ordnance etc (also to pick up where allowed)
	define('DEPLOY_TURN_COST', 5);

	// Turn cost to use a jump drive
	define('JUMP_TURN_COST', 50);

	// Turn cost to use a cloak device
	define('CLOAK_TURN_COST', 20);
	
	// Total amount of mines or drones in a sector (so 1000 total)
	define('MAX_ORDNANCE_PER_SECTOR', 500);

	// Total amount of mines or drones in a sector per player (so 100 total)
	define('MAX_ORDNANCE_PER_PLAYER', 50);

	// Max amount of drones per stack that will engage a single player
	define('DRONES_ATTACKING_PER_PLAYER', 0.25);

	// Damage each shot from a drone can do
	define('DRONE_ATTACK_DAMAGE', 2);

	// Chances a mine will a player
	define('MINE_HIT_PERCENT', 60);

	// Max amount of mines per stack that will engage a single player.
	define('MINES_ATTACKING_PER_PLAYER', 0.25);

	// Damage each exploding mine can do
	define('MINE_ATTACK_DAMAGE', 5);

	// Total number of bases that could be on a planet.
	define('MAX_BASES_PER_PLANET', 4);

	// Number of bases a player can own on start.
	define('START_BASE_COUNT', 2);

	// How many levels a player must gain to get more base slots.
	define('LEVELS_PER_EXTRA_BASE', 10);

	// Number of shields a base has at start.
	define('START_BASE_SHIELDS', 1000);

	// Base caption to use if a player does not choose one
	define('DEFAULT_BASE_CAPTION', "I'm a Base");

	// How far away to load players when on a base for icon purposes
	define('BASE_DISTANCE', 5);

	// Turn cost multiplier for flying over a base.
	define('BASE_HOVER_TURN_MULTIPLIER', 2);
	
	// Turn cost multiplier for landing on a base.
	define('BASE_LAND_TURN_MULTIPLIER', 5);

	// Turn cost multiplier for taking off from a base.
	define('BASE_TAKEOFF_TURN_MULTIPLIER', 10);

	// Minimum length of a gold key
	define('MINIMUM_KEY_LENGTH', 17);

	// Maximum length of a gold key
	define('MAXIMUM_KEY_LENGTH', 72);

	// Cannot activate a gold key if there are this many days active already.
	define('KEY_ACTIVATION_LIMIT', 93);

	// Maximum length of a message
	define('MAXIMUM_MESSAGE_LENGTH', 512);

	// Maximum length of a subspace broadcast
	define('MAXIMUM_SUBSPACE_MESSAGE_LENGTH', 128);

	// Subspace messages will expire after this amount of time.
	define('SUBSPACE_MESSAGE_EXPIRY', 600);

	// Regular messages will expire after this amount of time.
	define('MESSAGE_EXPIRY', 604800);

	// Subspace messages cost this amount of turns
	define('SUBSPACE_MESSAGE_TURN_COST', 50);

	// Regular messages cost this amount of turns
	define('MESSAGE_TURN_COST', 10);

	// Alliance messages cost this amount of turns per member
	define('ALLIANCE_MESSAGE_TURN_COST', 5);

	// Turn cost to hide or delete a message
	define('MSG_HIDE_DELETE_TURN_COST', 1);

	// Turn cost to toggle the ignore flag for player messages.
	define('PLAYER_MESSAGE_IGNORE_COST', 5);

	// This is the default ignore length
	define('IGNORE_DURATION', 1814400);

	// Minimum amount of items per page where pagination is used
	define('MIN_PER_PAGE', 3);

	// Maximum amount of items per page where pagination is used
	define('MAX_PER_PAGE', 20);

	// News headline length limit
	define('NEWS_HEADLINE_LIMIT', 48);

	// News abstract length limit
	define('NEWS_ABSTRACT_LIMIT', 128);

	// News headline length limit
	define('NEWS_ARTICLE_LIMIT', 65536);

	// Allowed news abstract tags to use in strip_tags
	define('ALLOWED_ABSTRACT_TAGS', '<font><img><a><span><s><big><strong><em><u>');

	// Allowed news article tags to use in strip_tags
	define('ALLOWED_ARTICLE_TAGS', '<h1><h2><h3><h4><h5><h6><font><img><li><ul><ol><br><a><td><tr><table><span><s><big><strong><em><u><p>');
	
	// When to archive news articles by default
	define('DEFAULT_NEWS_ARCHIVE_TIME', 86400 * 14);

	// When to expire news articles by default
	define('DEFAULT_NEWS_EXPIRY_TIME', 86400 * 31);
?>