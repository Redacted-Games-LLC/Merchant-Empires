/**
 * Javascript package for Merchant Empires by [Redacted] Games LLC
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

var form_validate_lock = false;



function page_onload(no_fluff) {


	if (!no_fluff) {
		var popupDiv = document.createElement('div');
		popupDiv.id = 'main_popup';
		popupDiv.addEventListener("click", function () { hide_div('main_popup'); location.href = 'viewport.php'; });

		var popupFrame = document.createElement('iframe');
		popupFrame.id = 'main_iframe';
		popupDiv.appendChild(popupFrame);

		document.body.appendChild(popupDiv);
	}


	if (messages) {

		var messageDiv = document.createElement('div');
		messageDiv.id = 'floating_message_box';
		
		if (no_fluff) {
			messageDiv.classList.add('floating_message_nofluff');
		} else {
			messageDiv.classList.add('floating_message_fluff');
		}

		messageDiv.addEventListener("click", function () { hide_div('floating_message_box'); });
		

		var messageClose = document.createElement('input');
		messageClose.id = 'floating_message_close';
		messageClose.type = 'image';
		messageClose.src = 'res/btn/close.png';
		messageClose.addEventListener('onmouseup', function () { document.getElementById('floating_message_close').src = 'res/btn/close.png'; });
		messageClose.addEventListener('onmousedown', function () { document.getElementById('floating_message_close').src = 'res/btn/close_d.png'; });
		messageClose.addEventListener('click', function () { hide_div('floating_message_box'); });

		messageDiv.appendChild(messageClose);

		var index;

		for (index = 0; index < messages.length; ++index) {
    		var messageItem = document.createElement('div');
    		messageItem.classList.add('floating_message');

    		var messageText = document.createTextNode(msgCodes[index] + ': ' + messages[index]);
    		
    		messageItem.appendChild(messageText);
    		messageDiv.appendChild(messageItem);
		}


		document.body.appendChild(messageDiv);
	}
	
}

function hide_div(object) {
	var element = document.getElementById(object);
	element.style.visibility = 'hidden';
}

function show_div(object) {
	var element = document.getElementById(object);
	element.style.visibility = 'visible';
}



function validate_login() {
	if (!lock_validation()) {
		return false;
	}
	
	var username = document.getElementById('usernamea').value;
	var password = document.getElementById('password1a').value;
	
	if (!validate_username(username)) {
		alert("Username is 2-16 characters and can only contain letters and numbers.");
		unlock_validation();
		return false;
	}
	
	if (!validate_password(password)) {
		alert("Password is 6-32 characters.");
		unlock_validation();
		return false;
	}
	
	document.getElementById('login_task').value = 'login';
	document.getElementById('login_form').submit();
	return true;
}





function validate_create_player(number) {
	if (!lock_validation()) {
		return false;
	}
	
	var player_name = document.getElementById('player_name' + number).value;
	
	if (!validate_playername(player_name)) {
		alert("Playername is 2-12 characters and can only contain letters and numbers.");
		unlock_validation();
		return false;
	}
	
	//TODO: dropdown race list
	
	document.getElementById('select_player_task' + number).value = 'create_player';
	document.getElementById('select_player_form' + number).submit();
	return true;
}




function validate_signup() {
	if (!lock_validation()) {
		return false;
	}
	
	var username = document.getElementById('usernameb').value;
	var password1 = document.getElementById('password1b').value;
	var password2 = document.getElementById('password2b').value;
	var email = document.getElementById('emailb').value;
	var policy = document.getElementById('policyb').checked;
	
	if (!policy) {
		alert('You must agree to our policies before you can sign up.');
		unlock_validation();
		return false;
	}
	
	if (!validate_username(username)) {
		alert("Username is 2-16 characters and can only contain letters and numbers.");
		unlock_validation();
		return false;
	}
	
	if (!validate_password(password1)) {
		alert("Password is 6-32 characters.");
		unlock_validation();
		return false;
	}
	
	if (!(password1 == password2)) {
		alert("Confirmation password doesn't match.");
		unlock_validation();
		return false;
	}

	if (!validate_email(email)) {
		alert("Email must be 128 bytes (unicode uses 2 bytes). If your email address should be supported then send us a direct email from it.");
		unlock_validation();
		return false;
	}
	
	document.getElementById('signup_task').value = 'signup';
	document.getElementById('signup_form').submit();
	return true;
}



function validate_playername(playname) {
	return /^[a-zA-Z0-9]{2,12}$/.test(playname);
}

function validate_username(username) {
	return /^[a-zA-Z0-9]{2,16}$/.test(username);
}


function validate_password(password) {
	return /^.{6,32}$/.test(password);
}

function validate_email(email) {
	if (email.length > 128) {
		return false;
	}

	// http://badsyntax.co/post/javascript-email-validation-rfc822
	return /^([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x22([^\x0d\x22\x5c\x80-\xff]|\x5c[\x00-\x7f])*\x22)(\x2e([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x22([^\x0d\x22\x5c\x80-\xff]|\x5c[\x00-\x7f])*\x22))*\x40([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x5b([^\x0d\x5b-\x5d\x80-\xff]|\x5c[\x00-\x7f])*\x5d)(\x2e([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x5b([^\x0d\x5b-\x5d\x80-\xff]|\x5c[\x00-\x7f])*\x5d))*$/.test(email);
}


function lock_validation() {
	if (form_validate_lock == true) {
		return false;
	}
	
	form_validate_lock = true;
	return true;
}

function unlock_validation() {
	form_validate_lock = false;
}


function drawButton(id, label, action, classes) {
	
	document.write('<input id="' + id + '" type="image" ');
	document.write('name="btn_' + label + '" ')
	document.write('class="form_button ' + classes + '" ')
	document.write('src="res/btn/' + label + '.png" ');
	document.write('onmousedown="document.getElementById(' + "'" + id + "').src='res/btn/" + label + "_d.png';" + '"' );
	document.write('onmouseup="document.getElementById(' + "'" + id + "').src='res/btn/" + label + ".png';" + '"' );
	document.write('onclick="return ' + action + ';" />');
	
}

function draw_number_list(name, level) {

	if (!level) {
		level = 1;
	}

	document.write('<select name="level">');
		
	for (i = 1; i <= 15; i++) {
		document.write('<option value="' + i + '"');

		if (i == level) {
			document.write('selected="selected"');
		}

		document.write('>' + i + '</option>');
	}
		
	document.write('</select>');
}


function draw_level_table() {

	// experience = 2000 * level ^ 3
	document.write('<table class="levels">')

	for (var row = 1; row <= 20; row++) {

		document.write('<tr class="levels">');

		for (var column = 0; column < 5; column++) {
			var level = (column * 20) + row;
			var experience = 2000 * level * level * level;

			document.write('<td class="level">' + level + '</td>');
			document.write('<td class="experience">' + numberWithCommas(experience) + '</td>');
		}
		
		document.write('</tr>');
	}

	document.write('</table>');
}


// Thanks to http://stackoverflow.com/a/2901298
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}



function target_link(x, y, player_x, player_y, type) {
	
	var dist = Math.max(Math.abs(x - player_x), Math.abs(y - player_y));

	document.write('<a href="handler.php?task=target&amp;x=' + x + '&amp;y=' + y + '&amp;type=' + type + '"');
	document.write('title="' + dist + ' Sector(s) away" target="_top">');
	document.write(x + " " + y);
	document.write('</a>');
}


function open_scan(x, y) {
	
	var frame = document.getElementById('main_iframe');
	frame.src = 'scan.php?x=' + x + '&y=' + y;


	show_div('main_popup');
	return false;
}

function open_port(port_id) {
	
	var frame = document.getElementById('main_iframe');
	frame.src = 'port.php?plid=' + port_id;


	show_div('main_popup');
	return false;
}

function open_dealer(dealer_id) {
	
	var frame = document.getElementById('main_iframe');
	frame.src = 'dealer.php?plid=' + dealer_id;


	show_div('main_popup');
	return false;
}

function open_base(base_id) {
	
	location.href = 'viewport.php?base=1&plid=' + base_id;
	return false;
}

function open_locator() {
	
	var frame = document.getElementById('main_iframe');
	frame.src = 'nav.php';

	show_div('main_popup');
	return false;
}

function open_player(id) {
	
	var frame = document.getElementById('main_iframe');
	frame.src = 'alliance.php?page=player&player_id=' + id;

	show_div('main_popup');
	return false;
}


function open_ship() {
	
	var frame = document.getElementById('main_iframe');
	frame.src = 'ship.php';

	show_div('main_popup');
	return false;
}

function open_alliance() {
	
	var frame = document.getElementById('main_iframe');
	frame.src = 'alliance.php';

	show_div('main_popup');
	return false;

}

function open_message() {
	
	var frame = document.getElementById('main_iframe');
	frame.src = 'message.php';

	show_div('main_popup');
	return false;

}

function open_base_build() {
	
	var frame = document.getElementById('main_iframe');
	frame.src = 'build.php';

	show_div('main_popup');
	return false;
}

function open_base_learn() {
	
	var frame = document.getElementById('main_iframe');
	frame.src = 'learn.php';

	show_div('main_popup');
	return false;
}



var _internal_seed = 0;

function _internal_random() {
	// Found on stack overflow somewhere
	var x = Math.sin(_internal_seed++) * 10000;
    return x - Math.floor(x);
}

function srandom(seed) {
	_internal_seed = seed;
}

function random(max) {
	return Math.floor((_internal_random() * 2000000000) % max);
}




function base_number_format(n) {
	if (n < 10) {
		return '0' + n;
	}
	else {
		return '' + n;
	}
}




/**
 * Generates the base field in the browser
 *
 * WARNING: This code was written over multiple sessions over lots of beer.
 */
function load_base_field() {


	var field = document.getElementById('base_field');

	var grid = document.createElement('div');
	grid.className = 'base_grid';

	var display_size = 14;
	var image_size = 40;

	if (base_id > 0) {
		display_size = 7;
		image_size = 80;
	}

	var sx = base_x - Math.ceil(display_size / 2);
	var sy = base_y - Math.ceil(display_size / 2);

	srandom(base_seed);

	// 0 north, 1 above equator, 2 below equator, 3 south
	var lattitude = random(4);
		
	for (x = 0; x <= 99; x++) {
		for (y = 0; y <= 99; y++) {

			var grass_number = random(3) + 1;
			var rock_number = random(40) + 1;

			// Now move on
			if (x <= sx || x > sx + display_size) {
				continue;
			}

			if (y <= sy || y > sy + display_size) {
				continue;
			}

			var tx = x - sx;
			var ty = y - sy;
			
			var grid_item = document.createElement('div');
						
			if (base_id > 0) {
				grid_item.className = 'base_grid_item';
			}
			else {
				grid_item.className = 'base_small_grid_item';
			}

			grid_item.style.top = ((display_size - ty) * image_size) + 'px';
			grid_item.style.left = ((tx - 1) * image_size) + 'px';



			if (lattitude == 0) {

				// Southern part of a planet

				if (y < 2) {
					grid_item.style.backgroundImage = 'url(res/base/snow.png)';
					rock_number++;
				}
				else if (y < 10) {
					if (grass_number == 3) {
						grid_item.style.backgroundImage = 'url(res/base/grass_3.png)';
					}
					else {
						grid_item.style.backgroundImage = 'url(res/base/snow.png)';
						rock_number++;
					}
				}
				else if (y < 20) {
					if (grass_number == 2) {
						grid_item.style.backgroundImage = 'url(res/base/snow.png)';
						rock_number++;
					}
					else {
						grid_item.style.backgroundImage = 'url(res/base/grass_3.png)';
					}
				}
				else if (y < 40) {
					if (grass_number == 2) {
						grass_number = 3;
					}
					
					grid_item.style.backgroundImage = 'url(res/base/grass_' + grass_number + '.png)';
				}
				else if (y > 95) {
					grass_number = 2;
					rock_number--;
					grid_item.style.backgroundImage = 'url(res/base/grass_' + grass_number + '.png)';
				}
				else if (y > 80) {
					if (grass_number == 3) {
						grass_number = 2;
						rock_number--;
					}

					grid_item.style.backgroundImage = 'url(res/base/grass_' + grass_number + '.png)';
				}
				else if (y > 60) {
					if (grass_number == 3) {
						grass_number = 1;
					}
					
					grid_item.style.backgroundImage = 'url(res/base/grass_' + grass_number + '.png)';
				}
				else {
					grid_item.style.backgroundImage = 'url(res/base/grass_' + grass_number + '.png)';
				}
				
			}
			else if (lattitude == 1) {


				// Mid southern part of a planet

				if (y < 20) {
					grid_item.style.backgroundImage = 'url(res/base/grass_3.png)';
				}
				else if (y < 40) {
					if (grass_number == 2) {
						grid_item.style.backgroundImage = 'url(res/base/grass_3.png)';
					}
					else {
						grid_item.style.backgroundImage = 'url(res/base/grass_1.png)';
					}
				}
				else if (y > 80) {
					grid_item.style.backgroundImage = 'url(res/base/grass_2.png)';
					rock_number--;
				}
				else if (y > 60) {
					if (grass_number == 3) {
						grid_item.style.backgroundImage = 'url(res/base/grass_2.png)';
						rock_number--;
					}
					else {
						grid_item.style.backgroundImage = 'url(res/base/grass_1.png)';
					}
				}
				else {
					grid_item.style.backgroundImage = 'url(res/base/grass_' + grass_number + '.png)';
				}
				

			}
			else if (lattitude == 2) {

				// Mid northern part of a planet

				if (y >= 80) {
					grid_item.style.backgroundImage = 'url(res/base/grass_3.png)';
				}
				else if (y >= 60) {
					if (grass_number == 2) {
						grid_item.style.backgroundImage = 'url(res/base/grass_3.png)';
					}
					else {
						grid_item.style.backgroundImage = 'url(res/base/grass_1.png)';
					}
				}
				else if (y <= 20) {
					grid_item.style.backgroundImage = 'url(res/base/grass_2.png)';
					rock_number--;
				}
				else if (y <= 40) {
					if (grass_number == 3) {
						grid_item.style.backgroundImage = 'url(res/base/grass_2.png)';
						rock_number--;
					}
					else {
						grid_item.style.backgroundImage = 'url(res/base/grass_1.png)';
					}
				}
				else {
					grid_item.style.backgroundImage = 'url(res/base/grass_' + grass_number + '.png)';
				}
				



			}
			else if (lattitude == 3) {

				// Northern part of a planet

				if (y >= 98) {
					grid_item.style.backgroundImage = 'url(res/base/snow.png)';
					rock_number++;
				}
				else if (y >= 90) {
					if (grass_number == 3) {
						grid_item.style.backgroundImage = 'url(res/base/grass_3.png)';
					}
					else {
						grid_item.style.backgroundImage = 'url(res/base/snow.png)';
						rock_number++;
					}
				}
				else if (y >= 80) {
					if (grass_number == 2) {
						grid_item.style.backgroundImage = 'url(res/base/snow.png)';
						rock_number++;
					}
					else {
						grid_item.style.backgroundImage = 'url(res/base/grass_3.png)';
					}
				}
				else if (y >= 60) {
					if (grass_number == 2) {
						grass_number = 3;
					}
					
					grid_item.style.backgroundImage = 'url(res/base/grass_' + grass_number + '.png)';
				}
				else if (y < 4) {
					grass_number = 2;
					rock_number--;
					grid_item.style.backgroundImage = 'url(res/base/grass_' + grass_number + '.png)';
				}
				else if (y < 19) {
					if (grass_number == 3) {
						grass_number = 2;
						rock_number--;
					}

					grid_item.style.backgroundImage = 'url(res/base/grass_' + grass_number + '.png)';
				}
				else if (y < 39) {
					if (grass_number == 3) {
						grass_number = 1;
					}
					
					grid_item.style.backgroundImage = 'url(res/base/grass_' + grass_number + '.png)';
				}
				else {
					grid_item.style.backgroundImage = 'url(res/base/grass_' + grass_number + '.png)';
				}
				


			}
			else {
				grid_item.style.backgroundImage = 'url(res/base/grass_' + grass_number + '.png)';
			}
		

			if (rock_number <= 3) {
				var grid_image = document.createElement('div');

				var grid_image_class = '';

				if (base_id > 0) {
					grid_image.className = 'base_grid_image';
					grid_image_class = 'base_grid_image';
				}
				else {
					grid_image.className = 'base_small_grid_image';
					grid_image_class = 'base_small_grid_image';
				}

				if (rock_number <= 1) {
					grid_image.innerHTML = '<img class="'+ grid_image_class +'" src="res/base/rock_1.png" />';
				}
				else if (rock_number <= 2) {
					grid_image.innerHTML = '<img class="'+ grid_image_class +'" src="res/base/rock_2.png" />';
				}
				else if (rock_number <= 3) {
					grid_image.innerHTML = '<img class="'+ grid_image_class +'" src="res/base/rock_3.png" />';
				}

				grid_item.appendChild(grid_image);
			}


			if (false) {
				var grid_link_div = document.createElement('div');
				grid_link_div.className = 'base_grid_link';
				
				if (base_x - x <= 1 && x - base_x <= 1 && base_y - y <= 1 && y - base_y <= 1) {
					var grid_link = document.createElement('a');
					grid_link.setAttribute('href', 'handler.php?task=base_move&plid='+ base_place +'&x=' + x + '&y=' + y);
					grid_link.innerHTML = base_number_format(x) + '&nbsp;' + base_number_format(y);

					grid_link_div.appendChild(grid_link);
				}
				else {
					grid_link_div.innerHTML = base_number_format(x) + '&nbsp;' + base_number_format(y);
				}

				grid_item.appendChild(grid_link_div);
			}
			
			grid.appendChild(grid_item);
		}
	}




	var over_links = [];

	var ds = Math.floor(display_size / 2);

	var bx = base_x;
	var by = base_y;

	if (base_id > 0) {
		//bx = bx * 2;
		//by = by * 2;
	}

	var i;
	for (i = 0; i < base_rooms.length; i++) {
		room = base_rooms[i];

		if (room.x > bx + ds) {
			continue;
		}

		if (room.y > by + ds) {
			continue;
		}

		if (room.x + room.width < bx - ds + 1) {
			continue;
		}

		if (room.y + room.height < by - ds + 1) {
			continue;
		}


		var safe_caption = room.caption.toLowerCase().replace(' ', '_');

		if (room.over) {
			switch (safe_caption) {
				case 'control_pad':

					if (base_id > 0) {
						over_links.push('<a href="#" onclick="open_base_build()">Build Something</a>');
						over_links.push('<a href="#" onclick="open_base_learn()">Learn Something</a>');
					}
					else {
						over_links.push('<a href="handler.php?task=base&amp;subtask=land&amp;plid='+ base_place +'">Land Here</a>');
					}

					break;

			}
		}




		var img = document.createElement('img');

		img.className = 'grid_room';
		img.setAttribute('src', 'res/base/' + safe_caption + '.png');

		img.setAttribute('width', room.width * image_size);
		img.setAttribute('height', room.height * image_size);

		if (base_id > 0) {
			img.style.left = ((room.x - bx + ds) * image_size) + 'px';
			img.style.top = 560 - ((room.y - by + ds + room.height) * image_size) + 'px';
		}
		else {
			img.style.left = (((room.x - bx + ds) * image_size) - 40) + 'px';
			img.style.top = 600 - ((room.y - by + ds + room.height) * image_size) + 'px';
		}
		

		grid.appendChild(img);
	}




	for (x = 0; x < 7; x++) {
		for (y = 0; y < 7; y++) {

			var grid_item = document.createElement('div');

			grid_item.className = 'base_overgrid_item';
			
			grid_item.style.top = 480 - (y * 80) + 'px';
			grid_item.style.left = (x * 80) + 'px';

			var tx;
			var ty;
			var nx;
			var ny;

			if (base_id > 0) {

				tx = base_x - sx - 1;
				ty = base_y - sy - 1;
				
				nx = base_x - 3 + x;
				ny = base_y - 3 + y;

				if (nx < 0 || ny < 0 || nx > 100 || ny > 100) {
					continue;
				}

			}
			else {

				tx = Math.floor((base_x - sx) / 2);
				ty = Math.floor((base_y - sy) / 2);
				
				nx = Math.floor((base_x / 2) - 3 + x);
				ny = Math.floor((base_y / 2) - 3 + y);

				if (nx < 0 || ny < 0 || nx >= 50 || ny >= 50) {
					continue;
				}
			}

			var grid_link_div = document.createElement('div');
			grid_link_div.className = 'base_grid_link';

			if (tx - x <= 1 && ty - y <= 1 && x - tx <= 1 && y - ty <= 1) {
				
				var grid_link = document.createElement('a');
				var nav_link = 'handler.php?task=base&subtask=move&plid='+ base_place +'&x=' + nx + '&y=' + ny;

				grid_link.setAttribute('href', nav_link);
				grid_link.innerHTML = base_number_format(nx) + '&nbsp;' + base_number_format(ny);

				grid_link_div.appendChild(grid_link);

				grid_item.setAttribute('title', 'Move Here');
				grid_item.setAttribute('onclick', 'location.href = "' + nav_link + '"');
				
				grid_item.className += ' pointer';
			}
			else {
				grid_link_div.innerHTML = '&nbsp;';
				//grid_link_div.innerHTML = base_number_format(nx) + '&nbsp;' + base_number_format(ny);
			}


			grid_item.appendChild(grid_link_div);
			grid.appendChild(grid_item);
		}
	}

	field.appendChild(grid);


	if (over_links.length > 0) {

		var over_link_div = document.createElement('div');
		over_link_div.className = 'over_links';

		for (i = 0; i < over_links.length; i++) {

			var link_wrapper = document.createElement('p');
			link_wrapper.className = 'over_link';
			link_wrapper.innerHTML = over_links[i];

			over_link_div.appendChild(link_wrapper);

		}

		document.body.appendChild(over_link_div);
	}

}




function load_pagination(current_page, total_pages, base_url) {

	var div = document.getElementById('pagination');
	var a = null;

	if (current_page > 2) {
		a = document.createElement('a');
		a.setAttribute('href', base_url + 'p=1');
	}
	else {
		a = document.createElement('span');
	}
	
	a.className = 'pagination_link';
	a.innerHTML = '<<';
	div.appendChild(a);

	if (current_page > 1) {
		a = document.createElement('a');
		a.setAttribute('href', base_url + 'p=' + (current_page - 1));
	}
	else {
		a = document.createElement('span');
	}
	
	a.className = 'pagination_link';
	a.innerHTML = '<';
	div.appendChild(a);


	var start = Math.max(0, Math.min(total_pages, current_page + 5) - 10);

	for (var i = 0; i <= 10; i++) {
		if (i > 0 && i <= total_pages) {
			a = document.createElement('a');
			a.setAttribute('href', base_url + 'p=' + (start + i));

			if (start + i == current_page) {
				a.className = 'pagination_current_link';
			}
			else {
				a.className = 'pagination_link';
			}
			
			a.innerHTML = (start + i);
			div.appendChild(a);
		}
	}

	if (current_page < total_pages) {
		a = document.createElement('a');
		a.setAttribute('href', base_url + 'p=' + (current_page + 1));
	}
	else {
		a = document.createElement('span');
	}
	
	a.className = 'pagination_link';
	a.innerHTML = '>';
	div.appendChild(a);


	if (current_page < total_pages - 1) {
		a = document.createElement('a');
		a.setAttribute('href', base_url + 'p=' + total_pages);
	}
	else {
		a = document.createElement('span');
	}
	
	a.className = 'pagination_link';
	a.innerHTML = '>>';
	div.appendChild(a);


}


function update_textarea_length(input_id, output_id, limit) {
	
	var input_element = document.getElementById(input_id);
	var output_element = document.getElementById(output_id);

	output_element.innerHTML = (limit - input_element.value.length) + ' chars left';
}

function register_textarea_length_handlers(input_id, output_id, limit) {

	var input_element = document.getElementById(input_id);

	input_element.addEventListener('keydown', function() {
		update_textarea_length(input_id, output_id, limit);
	});

	input_element.addEventListener('keyup', function() {
		update_textarea_length(input_id, output_id, limit);
	});

	input_element.addEventListener('change', function() {
		update_textarea_length(input_id, output_id, limit);
	});

	input_element.addEventListener('input', function() {
		update_textarea_length(input_id, output_id, limit);
	});

	input_element.addEventListener('cut', function() {
		update_textarea_length(input_id, output_id, limit);
	});

	input_element.addEventListener('paste', function() {
		update_textarea_length(input_id, output_id, limit);
	});

	update_textarea_length(input_id, output_id, limit);
}

