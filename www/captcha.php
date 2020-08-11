<?php
/**
 * Captcha generator
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

	$captcha_length = 5;
	$captcha_font = 'res/font/orbitron/orbitron-light.otf';
	$captcha_font_size = 32;
	$captcha_width = 200;
	$captcha_height = 100;

	$bounds = imagettfbbox($captcha_font_size, 0, $captcha_font, 'm');

	$text_width = $bounds[2] - $bounds[0];
	$text_height = $bounds[1] - $bounds[5];

	$center_x = $captcha_width / 2;
	$center_y = ($captcha_height + $text_height) / 2;

	mt_srand(microtime(true));
	$chars = substr(hash('sha256', getClientIP() . microtime()), 0, $captcha_length);

	header('Content-type: image/png');

	$image_handle = imagecreatetruecolor($captcha_width, $captcha_height) or die('Cannot Initialize new GD image stream');
	
	$text_color = imagecolorallocatealpha($image_handle, 255, 255, 255, 77);
	$dot_color = imagecolorallocatealpha($image_handle, 255, 255, 255, 99);
	
	$dot_width = $captcha_width / 20;
	$dot_height = $captcha_height / 10;

	for ($y = $dot_height; $y < $captcha_height; $y += ceil($dot_height * 1.5)) {
		for ($x = $dot_width; $x < $captcha_width; $x += ceil($dot_width * 1.5)) {
			imagefilledellipse($image_handle, $x + mt_rand(0,3) - 2, $y + mt_rand(0,3) - 2, $dot_width + mt_rand(0, 1), $dot_height + mt_rand(0,1), $dot_color);
		}
	}

	for ($i = 0; $i < $captcha_length; $i++) {
		$x = $center_x - ((($captcha_length / 2) - $i) * $text_width / 1.2);
		$y = $center_y + (mt_rand(0, 20)) - 10;
		$r = mt_rand(0, 15) - 5;

		imagettftext($image_handle, $captcha_font_size, $r, $x, $y, $text_color, $captcha_font, $chars[$i]);	
	}	

	imagepng($image_handle);
	imagedestroy($image_handle);