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

/*
 *
 * Security 
 *************************************************/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}

/*
 * on to more productive things
 *************************************************/
$plugin_path = dirname(__FILE__);

/*
 * Register_custom_post_type 
 *************************************************/
require( $plugin_path . '/custom-post-types/networking-event.php' );
require( $plugin_path . '/custom-post-types/testimonials.php' );
require( $plugin_path . '/custom-post-types/bits.php' );
require( $plugin_path . '/custom-post-types/print-archives.php' );

/*
 * some functions
 *************************************************/
require( $plugin_path . '/functions/photo-gallery.php' );
require( $plugin_path . '/functions/slider.php' );
require( $plugin_path . '/functions/insert-latest-posts.php' );
require( $plugin_path . '/functions/get-excerpt-by-id.php' );

?>
