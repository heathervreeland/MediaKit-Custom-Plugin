<?php
/*
* Plugin Name: OccasionsOnline Custom Functionality 
* Plugin URI: http://www.occasionsonline.com/
* Description: A plugin that adds functionality specifically design for the Occasions theme 
* Version: 1.2 
* Author: Ben Kaplan 
* Author URI: http://www.benkaplan.info/

* General stuff
*
* Register_custom_post_type 
* Register a custom post type, 'bits' 
*
*/

$plugin_path = dirname(__FILE__);

/*
 *
 * General Stuff
 *************************************************/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
	exit;
}

/*
 * Register_custom_post_type 
 *************************************************/
require( $plugin_path . '/custom-post-types/networking-event.php' );
require( $plugin_path . '/custom-post-types/testimonials.php' );
require( $plugin_path . '/custom-post-types/bits.php' );

/*
 * some functions
 *************************************************/
require( $plugin_path . '/functions/photo-gallery.php' );

?>
