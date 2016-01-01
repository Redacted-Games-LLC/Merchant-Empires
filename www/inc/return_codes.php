<?php 
/**
 * Contains an enumarated list of error and/or notice messages.
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
 * ---------------------------------------------------------------------
 *
 *
 *			DO NOT USE SINGLE QUOTES IN THESE RETURN STRINGS.
 *
 */


$RETURN_CODES = array(

	1000 => 'Developer was too lazy to put a better return message in.',
	1001 => 'Invalid form action.',
	1002 => 'Usernames must be between 2-16 characters and contain only letters and numbers. They are not case sensitive.',
	1003 => 'Passwords must be between 6-32 characters. It is your responsibility to make a strong password.',
	1004 => 'Passwords do not match.',
	1005 => 'Invalid email.',
	1006 => 'Query failed to prepare or execute.',
	1007 => 'Login failed.',
	1008 => 'You have been logged out.',
	1009 => 'Invalid player race. This is a game bug.',
	1010 => 'Required handler include missing. This might be a game bug.',
	1011 => 'Player name must be between 2-12 characters and contain only letters and numbers.',
	1012 => 'Player name is in use. Sorry, pick another.',
	1013 => 'You have reached the limit on players for this account.',
	1014 => 'Invalid player id.',
	1015 => 'You do not have access to a viewport.',
	1016 => 'You are unable to move to that location.',
	1017 => 'You are unable to scan that location.',
	1018 => 'You do not have enough turns for that.',
	1019 => 'Unable to locate requested dealer in this sector.',
	1020 => 'Unknown item type. This is a game bug.',
	1021 => 'Invalid item id.',
	1022 => 'Item not available at this dealer.',
	1023 => 'Item out of stock at this dealer.',
	1024 => 'This item is locked for you.',
	1025 => 'You do not have the available credits for this item.',
	1026 => 'Thank you for your transaction.',
	1027 => 'You must specify how many of those you want.',
	1028 => 'You cannot buy that many of those, sorry.',
	1029 => 'You must be logged in to do that.',
	1030 => 'You do not have permission to do that.',
	1031 => 'You must be in a ship with available holds to do that.',
	1032 => 'You do not have enough cargo space for the transfer.',
	1033 => 'You are moving too fast. Slow down.',
	1034 => 'Unable to locate requested port in this sector.',
	1035 => 'You cannot sell that many of those, sorry.',
	1036 => 'You are not carrying any of those to sell.',
	1037 => 'A few items were found in the back. There may be more. Feel free to try again.',
	1038 => 'Nothing was found so far but there is more cargo to check. Feel free to try again.',
	1039 => 'Nothing was found and all cargo was searched. There will be another delivery soon.',
	1040 => 'Unable to locate requested warp in this sector.',
	1041 => 'Invalid subtask, that is multiple forms on one page and one of them is misconfigured.',
	1042 => 'Good name is invalid or contains invalid characters.',
	1043 => 'Good level is missing or out of range.',
	1044 => 'Good name already exists in the database.',
	1045 => 'Good has been added to the database.',
	1046 => 'No change, so no database update.',
	1047 => 'Information has been updated in the database.',
	1048 => 'Equivalent record already exists in the database.',
	1049 => 'Record has been added to the database.',
	1050 => 'Development error. Check the log file for more information.',
	1051 => 'Invalid requirement.',
	1052 => 'You must specify x and y coordinates for this action.',
	1053 => 'Percent must be an integer between 0 and 100.',
	1054 => 'The choices are Supply or Demand and that is it.',
	1055 => 'Record has been deleted from the database.',
	1056 => 'No place type which allows for port deployment.',
	1057 => 'No places available to deploy a port.',
	1058 => 'No start goods for the place list we have.',
	1059 => 'That target type is not supported.',
	1060 => 'Invalid style string. Get it right!',
	1061 => 'You have not earned enough experience for this request.',
	1062 => 'You must gain more levels to rank up.',
	1063 => 'You must gain more allignment to rank up.',
	1064 => 'There are no further rankings available to you, sir.',
	1065 => 'Your ship name has been updated.',
	1066 => 'Your cargo has been jettisoned.',
	1067 => 'Ship names are limited to a-z, 0-9 and underscore characters, up to 12.',
	1068 => 'Your form was incomplete or missing information.',
	1069 => 'Invalid response to a yes/no question.',
	1070 => 'You cannot exit protected systems until you reach level 1.',
	1071 => 'A user with that name already exists.', // I know, I know.
	1072 => 'There is already a port deployed in this sector.',
	1073 => 'There is no suitable place to put a port in this sector.',
	1074 => 'Failed to insert a tech and now it is gone. Check to make sure there someone did not get one in before you. Sorry, no refunds.',
	1075 => 'You are not carrying enough of that tech to deploy.',
	1076 => 'Your new port is installed and upgrades will be available shortly.',
	1077 => 'There are already too many solar collectors in this sector.',
	1078 => 'You are not over a location which you can deploy solar collectors.',
	1079 => 'Your solar collector has been installed and is ready to use.',
	1080 => 'Alliance name must be between 2 and 24 characters with letters, numbers, and spaces only.',
	1081 => 'Alliance name must not begin or end with spaces, or have two consecutive spaces in it.',
	1082 => 'You cannot do that while in an alliance.',
	1083 => 'There is already an alliance with that name. Please pick another.',
	1084 => 'Unknown or invalid alliance information.',
	1085 => 'You cannot perform that action on an alliance you are not a member of.',
	1086 => 'You do not have alliance leadership permission to perform that action.',
	1087 => 'Invalid parameter. You either do or you do not.',
	1088 => 'The alliance recruitment flag has been updated.',
	1089 => 'You already have ' . ALLIANCE_REQUEST_LIMIT . ' active request(s). Wait for some responses first.',
	1090 => 'You already have a recent request to this alliance. Requests are open for '. OPEN_REQUEST_DAYS .' days unless rejected, then it is '. REJECTED_REQUEST_DAYS .' days.',
	1091 => 'Your request will be active for '. OPEN_REQUEST_DAYS . ' days if not approved or rejected.',
	1092 => 'That alliance is not recruiting. Please select another.',
	1093 => 'You have rejected the request to join.',
	1094 => 'That player is already in an alliance. If they had multiple requests open perhaps another got in before you.',
	1095 => 'That player does not have a request to join your alliance. If they had multiple requests open perhaps another got in before you.',
	1096 => 'You have enrolled the player and they should appear on the alliance list.',
	1097 => 'That player is not in your alliance.',
	1098 => 'Alliance leaders and founders must be demoted before being removed.',
	1099 => 'You have kicked that player out of the alliance.',
	1100 => 'That deployed tech is already topped off so nothing changed.',
	1101 => 'You can only deploy ordnance in a solar system.',
	1102 => 'The limit for that ordnance has already been reached for this sector.',
	1103 => 'Only mines and drones can be picked up from a sector.',
	1104 => 'You have none of those to pick up in this sector.',
	1105 => 'You cannot deploy ordnance in a protected system.',
	1106 => 'Must supply valid x and y coordinates to do that.',
	1107 => 'You are not over a location which can support a base.',
	1108 => 'This sector cannot support any more bases.',
	1109 => 'You are at your limit for new bases.',
	1110 => 'Invalid character in base caption, which could also mean too many characters.',
	1111 => 'Consecutive, leading, or trailing whitespace is not allowed.',
	1112 => 'Your new base is installed and the landing Control Pad will be available shortly.',
	1113 => 'You are already on a base.',
	1114 => 'Unable to locate requested base in this sector.',
	1115 => 'You may only land on Landing Pads, including Control Pads.',
	1116 => 'You must be on a base to do that.',
	1117 => 'You may only build over Control Pads and other factories.',
	1118 => 'You may only research over Control Pads and other labs.',
	1119 => 'You cannot land on a base while in an escape pod.',
	1120 => 'Logins are currently restricted to administrators. Please try again shortly.',
	1121 => 'Invalid or missing gold key.',
	1122 => 'Gold keys have been inserted into the database.',
	1123 => 'Gold key not found or not available for you to use, transfer, or store.',
	1124 => 'You already have that gold key stored.',
	1125 => 'Gold key stored.',
	1126 => 'Gold key could not be updated.',
	1127 => 'Gold key removed. I hope you wrote it down!',
	1128 => 'Gold key activated! Your Gold membership time has been extended.',
	1129 => 'You cannot enable a gold key during Havoc Round.',
	1130 => 'Player name not found.',
	1131 => 'The key has been transferred to the player.',
	1132 => 'You cannot obtain an end of round key until Havoc Round.',
	1133 => 'You do not have any Gold Membership time remaining.',
	1134 => 'Your end of round key has been generated and stored.',
	1135 => 'Failed to update the player.',
	1136 => '',
	1137 => '',
	1138 => '',
	1139 => '',
	1140 => '',
	1141 => '',
	1142 => '',
	1143 => '',
	1144 => '',
	1145 => '',
	1146 => '',
	1147 => '',
	1148 => '',
	1149 => '',
	1150 => '',
	1151 => '',
	1152 => '',
	1153 => '',
	1154 => '',
	1155 => '',
	1156 => '',
	1157 => '',
	1158 => '',
	1159 => '',
);

function get_message($code) {
	
	global $RETURN_CODES;
	
	if (isset($RETURN_CODES[$code])) {
			return $RETURN_CODES[$code];
	}
	else {
		return 'Unknown return code.';
	}
	
}



?>