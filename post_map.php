<?php
/*
Plugin Name: Post Map
Plugin URI: https://github.com/bnjmnmrtn/postmap
Description: Provides shortcodes to insert Google Maps associated with a post's location
Version: 1.0
Author: Benjamin Martin
*/

/*
Google Post Map (Wordpress Plugin)
Copyright (C) 2012 Benjamin Martin
Contact me at bnjmnmrtn@hotmail.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

DEFINE('POSTMAP_DEFAULT_WIDTH', '50%');
DEFINE('POSTMAP_DEFAULT_HEIGHT', '250px');
DEFINE('POSTMAP_DEFAULT_ZOOMLEVEL', '8');

DEFINE('PAGEMAP_DEFAULT_WIDTH', '100%');
DEFINE('PAGEMAP_DEFAULT_HEIGHT', '550px');
DEFINE('PAGEMAP_DEFAULT_ZOOMLEVEL', '2');

function common_enqueue() {
    wp_register_style('google-maps-api-style', plugins_url('/style.css', __FILE__));
    wp_register_script('google-maps-api', 'https://maps.googleapis.com/maps/api/js?sensor=false&key=AIzaSyAwKnUxwBC4rHvr9cYXDQx37WdB86zNUi8');

    wp_enqueue_script('google-maps-api');
    wp_enqueue_style('google-maps-api-style');
}

function post_enqueue() {
    common_enqueue(); 
    wp_register_script('post-map', plugins_url('post_map.js', __FILE__));
    wp_enqueue_script('post-map');
}
function page_enqueue() {
    common_enqueue(); 
    wp_register_script('page-map', plugins_url('page_map.js', __FILE__));
    wp_enqueue_script('page-map');
}
function admin_my_enqueue() {
    common_enqueue();
    wp_register_script('location-picker', plugins_url('location_picker.js', __FILE__));
    wp_enqueue_script('location-picker');
}
function header_action() {
    if (is_single()) {
        post_enqueue();
    }
    else if (is_page()) {
        page_enqueue();
    }   
}

add_action('admin_enqueue_scripts', 'admin_my_enqueue');
add_action( 'get_header', 'header_action' );

function postmap_shortcode($settings) {  
    
    $settings = shortcode_atts(array(
    	"width" => POSTMAP_DEFAULT_WIDTH,
    	"height" => POSTMAP_DEFAULT_HEIGHT,
    	"zoom" => POSTMAP_DEFAULT_ZOOMLEVEL
    ), $settings);
    
    $location = get_post_meta(get_the_ID(), 'location', true);

	// Format: +/-123.44566, +/-123.42342345
	// Format: +/-123.44566 +/-123.42342345
	if (preg_match("/(-?\d+(\.\d+)?),? (-?\d+(\.\d+)?)/", $location, $matches)) {
		$lat = $matches[1];
		$lon = $matches[3];
	}
    else {
        return '';
    }
    
    return '<div id="map_canvas" data-zoom="' . $settings['zoom'] . '" data-latitude="' . $lat . '" data-longitude="' . $lon . '" style="width: ' . $settings['width'] . '; height: ' . $settings['height'] . '"></div>';
}
add_shortcode("post-map", "postmap_shortcode");

function getLatLon($string) {
    if (preg_match("/(-?\d+(\.\d+)?),? (-?\d+(\.\d+)?)/", $string, $matches)) {
        $lat = $matches[1];
        $lon = $matches[3];
    } else {
        return null;
    }
    
    return array($lat, $lon);
}

function pagemap_shortcode($settings) {
    
    $settings = shortcode_atts(array(
    	"width" =>  PAGEMAP_DEFAULT_WIDTH,
    	"height" => PAGEMAP_DEFAULT_HEIGHT,
    	"zoom" =>   PAGEMAP_DEFAULT_ZOOMLEVEL
    ), $settings);
    
    //$location = get_post_meta(get_the_ID(), 'location', true);
    $query = new WP_Query(array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => 'location',
                    'value' => '',
                    'compare' => '!='
                )
            )
        )
    );

    $script = "
        <script type='text/javascript'>
            function getDatapoints() {
                return [";
   
            while ( $query->have_posts() ) {
                $query->the_post();
                $location = getLatLon(get_post_meta(get_the_ID(), 'location', true));
                if ($location != null) {
                    $script = $script . '[' . $location[0] . ',' . $location[1] . ',' . '"' . get_the_date() . '",' . '"' . get_the_title() . '","' . get_permalink() . '",' . get_the_date('Y') . '],';
                }
            }

    $script = $script . "];
    }
        </script>
    ";
    return '<div id="map_canvas" data-zoom="' . $settings['zoom'] . '" style="width: ' . $settings['width'] . '; height: ' . $settings['height'] . '">' . $script . '</div>';
}
add_shortcode("page-map", "pagemap_shortcode");


require_once('post_meta.php');
require_once('location_picker.php');
require_once('remaining_posts.php');

?>
