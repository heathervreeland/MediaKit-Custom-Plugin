<?php

/*
 * Register_custom_post_type - Bits 
 * - this custom post type is mainly utilized for when you need to have a bit of content embedded
 *   within a page template somewhere, and need a place to manage it from. This allows us to not
 *   put the content inside of a regular Post and have it lost when the blog fills up.
 *************************************************/

add_action( 'init', 'create_bit_post_type' );

function create_bit_post_type() {

  $icon = plugins_url() . '/occasions-custom/custom-post-types/images/script-code-single.png';

  // create the custom post type
  register_post_type( 'bit',
    array(
      'labels' => array(
        'name' => __( 'Bits' ), 
        'singular_name' => __( 'Bit' ), 
        'add_new' => _x('Add New', 'bit'), 
        'add_new_item' => __('Add New Bit'), 
        'edit_item' => __('Edit Bit'), 
        'new_item' => __('New Bit'), 
        'view_item' => __('View Bit'), 
        'search_items' => __('Search Bits'), 
        'not_found' =>  __('No bits found'), 
        'not_found_in_trash' => __('No bits found in Trash'), 
        'parent_item_colon' => '', 
        'menu_name' => 'Bits'
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
    "bits", 
    array("bit"), 
    array(
      "hierarchical" => true, 
      "labels" => array(
        'name' => _x( 'Bit Categories', 'taxonomy general name' ),
        'singular_name' => _x( 'Bit Category', 'taxonomy singular name' ),
        'search_items' =>  __( 'Search Bit Categories' ),
        'popular_items' => __( 'Popular Bit Categories' ),
        'all_items' => __( 'All Bit Categories' ),
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => __( 'Edit Bit Category' ), 
        'update_item' => __( 'Update Bit Category' ),
        'add_new_item' => __( 'Add New Bit Category' ),
        'new_item_name' => __( 'New Bit Category Name' ),
        'separate_items_with_commas' => __( 'Separate bit categories with commas' ),
        'add_or_remove_items' => __( 'Add or remove bit category' ),
        'choose_from_most_used' => __( 'Choose from the most used bit categories' ),
        'menu_name' => __( 'Bit Categories' ),
      ),
      "show_ui" => true, 
      "query_var" => true, 
      'rewrite' => array( 'slug' => 'bits', 'with_front' => true, 'heirarchical' => true )
    )
  );
}

?>
