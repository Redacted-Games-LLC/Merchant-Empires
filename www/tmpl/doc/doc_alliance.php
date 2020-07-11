<?php
/**
 * Docomentation for joining, recruiting, and managing alliances
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

	include_once('tmpl/common.php');
?>
<div class="header2 header_bold">Alliances</div>
<div class="docs_text">
	Players can group together into mixed-race alliances. Members of alliances can
	safely enter sectors mined by their mates and avoid targeting by drones or
	scouts. They can land on allied bases and use allied stargates.
</div>
<div class="docs_text">
	Alliances cannot tax their members above the Imperial Government tax described
	in the next section. There is no alliance account and no way for an alliance
	to pool money from the players.
</div>
<div class="header3 header_bold">Imperial Tax and Member Limits</div>
<div class="docs_text">
	There is no limit to the number of players who can join an alliance.
	However, there is a tax. The rate is 1% plus 0.2% per alliance member, rounded
	up. Eight people will pay 3%, 15 will pay 4% while 16-20 members will pay 5%,
	and so forth.
</div>
<div class="header3 header_bold">Treaties and Politics</div>
<div class="docs_text">
	There are <strong>no treaties</strong> allowed between alliances.
	Alliances are not considered racial. Each player is still responsible
	for their own alignment.
</div>
<div class="header3 header_bold">Creating an Alliance</div>
<div class="docs_text">
	Any player who has reached level <?php echo ALLIANCE_CREATION_LEVEL; ?>
	may form an alliance.
</div>
<div class="docs_text">
	The player who creates an alliance is called a Founder and can manipulate 
	alliance settings such as recruitment status.
</div>
<div class="header3 header_bold">Joining an Alliance</div>
<div class="docs_text">
	Players who are not in an alliance can request to join up to <?php echo ALLIANCE_REQUEST_LIMIT; ?>
	recruiting alliances. To find out if an alliance is recruiting, click on the
	<strong>TEAM</strong> button in the main viewport, then select 'Alliance List.'
</div>
<div class="docs_text">
	Alliances that are recruiting will have a green circle, while alliances not
	recruiting will have a red X. Click on a recruiting alliance to get a 'Join'
	button. Hitting this sends a request that will remain active for 
	<?php echo OPEN_REQUEST_DAYS; ?> days or until rejected.
</div>
<div class="docs_text">
	<strong>DO NOT</strong> send a message to the alliance telling them you have
	an open join request. They can see open requests on their Alliance Member List
	page and the lead was already messaged for you.
</div>
<div class="header3 header_bold">Leaving an Alliance</div>
<div class="docs_text">
	Players can leave an alliance at any time in the Alliance Status page, or they
	can be kicked out from the member page. Alliance leaders must demote themselves
	or elect a new Founder unless they are the last remaining member.
</div>
<div class="docs_text">
	Alliances with no remaining members are dissolved.
</div>
<div class="docs_text">
	WARNING: Leaving an alliance renders your forces hostile to all other remaining
	alliance members. If you are leaving peacefully, you should clean up your forces
	first.
</div>