<?php
/**
 * Galaxy map generator for admins only.
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

	include_once('inc/page.php');
	include_once('inc/galaxy.php');
	
	if (!get_user_field(USER_ID, 'admin', 'system')) {
		header('Location: viewport.php?rc=1030');
		die();
	}

	$galaxy_size = GALAXY_SIZE;
	
	$seed = time();
	
	if (isset($_REQUEST['seed']) && is_numeric($_REQUEST['seed'])) {
		$seed = $_REQUEST['seed'];
	}

	$star_count = 0;
	$stars = build_star_map($galaxy_size, $seed, $star_count);


	$races = array();
	$races[0]['count'] = 0;
	$races[1]['count'] = 0;
	$races[2]['count'] = 0;
	$races[3]['count'] = 0;
	
	for ($i = 0; $i < count($stars); $i++) {
		$races[$stars[$i]['race']]['count'] += 1;
	}


	$warps = generate_warps($stars, $star_count);
	


	header('Content-type: image/png');
	
	$i_hnd = imagecreatetruecolor($galaxy_size, $galaxy_size) or die('Cannot Initialize new GD image stream');
	
	$races[0]['color'] = imagecolorallocate($i_hnd, 255, 255, 255);
	$races[1]['color'] = imagecolorallocate($i_hnd, 128, 255, 128);
	$races[2]['color'] = imagecolorallocate($i_hnd, 255, 128, 128);
	$races[3]['color'] = imagecolorallocate($i_hnd, 128, 128, 255);

	$warp_color = imagecolorallocate($i_hnd, 128, 128, 128);
	
	
	
	$cx = $galaxy_size / 2;
	$cy = $galaxy_size / 2;

	imagesetthickness($i_hnd, 3);

	foreach ($warps as $warp) {
		imageline($i_hnd, $cx + $warp['x1'], $galaxy_size - ($cy + $warp['y1']), $cx + $warp['x2'], $galaxy_size - ($cy + $warp['y2']), $warp_color);
	}

	imagesetthickness($i_hnd, 2);
	
	foreach($stars as $star) {
		$pixel_size = $star['size'] + 2;
		
		if ($star['protected'] > 0) {
			imagearc($i_hnd, $cx + $star['x'], $galaxy_size - ($cy + $star['y']), $pixel_size + 6, $pixel_size + 6, 0, 359.9, $races[$star['race']]['color']);
			//imageellipse($i_hnd, $cx + $star['x'], $galaxy_size - ($cy + $star['y']), $pixel_size + 6, $pixel_size + 6, $races[$star['race']]['color']);
		}
		
		imagefilledellipse($i_hnd, $cx + $star['x'], $galaxy_size - ($cy + $star['y']), $pixel_size, $pixel_size, $races[$star['race']]['color']);
	}
	
	imagestring($i_hnd, 5, 0, 0, $star_count . " " . $seed . ' ' . $races[0]['count'] . ' ' . $races[1]['count'] . ' ' . $races[2]['count'] . ' ' . $races[3]['count'], $races[0]['color']);
	
	imagepng($i_hnd);
	imagedestroy($i_hnd);
?>