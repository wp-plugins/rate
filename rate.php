<?php
/*
Plugin Name: Rate
Description: Ratings: clean, lightweight and easy
Author: Scott Taylor
Version: 0.4
Author URI: http://tsunamiorigami.com
*/

$dir = dirname( __FILE__ );
include_once( $dir . '/php/functions.php' );
include_once( $dir . '/php/plugin.php' );

add_action( 'plugins_loaded', array( 'RatePlugin', 'get_instance' ) );