<?php

/*
 * Register_custom_post_type - Networking Events 
 *************************************************/

add_action( 'init', 'create_networking_event_post_type' );

function create_networking_event_post_type() {

  $icon = plugins_url( 'images/script-code-single.png', __FILE__ );

  // create the custom post type
  register_post_type( 'networking-event',
    array(
      'labels' => array(
        'name' => __( 'Networking Events' ), 
        'singular_name' => __( 'Networking Event' ), 
        'add_new' => _x('Add New', 'networking event'), 
        'add_new_item' => __('Add New Networking Event'), 
        'edit_item' => __('Edit Networking Event'), 
        'new_item' => __('New Networking Event'), 
        'view_item' => __('View Networking Event'), 
        'search_items' => __('Search Networking Events'), 
        'not_found' =>  __('No networking events found'), 
        'not_found_in_trash' => __('No networking events found in Trash'), 
        'parent_item_colon' => '', 
        'menu_name' => 'Networking Events'
      ),
      'public' => true,
      'publicly_queryable' => true,
      'show_ui' => true, 
      'show_in_menu' => true, 
      'query_var' => true,
      'rewrite' => true,
      'capability_type' => 'post',
      'has_archive' => true, 
      'hierarchical' => true,
      'menu_position' => 20, // tosses the menu just below Pages and above Comments
      'menu_icon' => $icon,
      'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions', 'page-attributes')
    )
  );

  // create categories for the custom post type
  register_taxonomy(
    "networking-events", 
    array("networking-event"), 
    array(
      "hierarchical" => true, 
      "labels" => array(
        'name' => _x( 'Networking Event Categories', 'taxonomy general name' ),
        'singular_name' => _x( 'Networking Event Category', 'taxonomy singular name' ),
        'search_items' =>  __( 'Search Networking Event Categories' ),
        'popular_items' => __( 'Popular Networking Event Categories' ),
        'all_items' => __( 'All Networking Event Categories' ),
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => __( 'Edit Networking Event Category' ), 
        'update_item' => __( 'Update Networking Event Category' ),
        'add_new_item' => __( 'Add New Networking Event Category' ),
        'new_item_name' => __( 'New Networking Event Category Name' ),
        'separate_items_with_commas' => __( 'Separate networking event categories with commas' ),
        'add_or_remove_items' => __( 'Add or remove networking event category' ),
        'choose_from_most_used' => __( 'Choose from the most used networking event categories' ),
        'menu_name' => __( 'Networking Event Categories' ),
      ),
      "show_ui" => true, 
      "query_var" => true, 
      'rewrite' => array( 'slug' => 'networking-event-categories', 'with_front' => true, 'heirarchical' => true )
    )
  );
}

// Let's be sure to use custom post type specific template pages for display
add_action("template_redirect", 'my_theme_redirect');

function my_theme_redirect() {
  global $wp;
  $public_query_vars = $wp->public_query_vars;  
  $plugindir = dirname( __FILE__ );

  //A Specific Custom Post Type
  if ($wp->query_vars["post_type"] == 'networking-event') {
    $templatefilename = 'single-network.php';
    if (file_exists(TEMPLATEPATH . '/' . $templatefilename)) {
      $return_template = TEMPLATEPATH . '/' . $templatefilename;
    } else {
      $return_template = $plugindir . '/themefiles/' . $templatefilename;
    }   
    do_theme_redirect($return_template);

    //A Simple Index Page
  } elseif ($wp->query_vars["pagename"] == 'daily-journal') {
    $templatefilename = 'page-daily-journals.php';
    if (file_exists(TEMPLATEPATH . '/' . $templatefilename)) {
      $return_template = TEMPLATEPATH . '/' . $templatefilename;
    } else {
      $return_template = $plugindir . '/themefiles/' . $templatefilename;
    }   
    do_theme_redirect($return_template);
  }
}

function do_theme_redirect($url) {
  global $post, $wp_query;
  if (have_posts()) {
    include($url);
    die();
  } else {
    $wp_query->is_404 = true;
  }
}

?>
