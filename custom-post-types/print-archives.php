<?php

/*
 * Register_custom_post_type - Print Archives 
 *************************************************/

add_action( 'init', 'create_print_archive_post_type' );

function create_print_archive_post_type() {

  $icon = plugins_url() . '/occasions-custom/custom-post-types/images/script-code-single.png';

  // create the custom post type
  register_post_type( 'print-archive',
    array(
      'labels' => array(
        'name' => __( 'Print Archives' ), 
        'singular_name' => __( 'Print Archive' ), 
        'add_new' => _x('Add New', 'print archive'), 
        'add_new_item' => __('Add New Print Archive'), 
        'edit_item' => __('Edit Print Archive'), 
        'new_item' => __('New Print Archive'), 
        'view_item' => __('View Print Archive'), 
        'search_items' => __('Search Print Archives'), 
        'not_found' =>  __('No print archives found'), 
        'not_found_in_trash' => __('No print archivess found in Trash'), 
        'parent_item_colon' => '', 
        'menu_name' => 'Print Archives'
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
    "print-archive-categories", 
    array("print-archive"), 
    array(
      "hierarchical" => true, 
      "labels" => array(
        'name' => _x( 'Print Archive Categories', 'taxonomy general name' ),
        'singular_name' => _x( 'Print Archive Category', 'taxonomy singular name' ),
        'search_items' =>  __( 'Search Print Archive Categories' ),
        'popular_items' => __( 'Popular Print Archive Categories' ),
        'all_items' => __( 'All Print Archive Categories' ),
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => __( 'Edit Print Archive Category' ), 
        'update_item' => __( 'Update Print Archive Category' ),
        'add_new_item' => __( 'Add New Print Archive Category' ),
        'new_item_name' => __( 'New Print Archive Category Name' ),
        'separate_items_with_commas' => __( 'Separate print archive categories with commas' ),
        'add_or_remove_items' => __( 'Add or remove print archive category' ),
        'choose_from_most_used' => __( 'Choose from the most used print archive categories' ),
        'menu_name' => __( 'Print Archive Categories' ),
      ),
      "show_ui" => true, 
      "query_var" => true, 
      'rewrite' => array( 'slug' => 'print-archive-categories', 'with_front' => true, 'heirarchical' => true )
    )
  );

  flush_rewrite_rules( false );
}

/*
 * Update the standard Administration listing
 *
 *****************************************************/

add_filter('manage_edit-print-archive_columns', 'add_new_print_archive_columns');

function add_new_print_archive_columns($print_archive_columns) {

  unset($print_archive_columns);

  return array(
  'cb' => '<input type="checkbox" />',
  'title' => __('Print Archive Name'),
  'print-archive-categories' => __('Print Archive Categories'),
  'date' => __('Date')
  );

}

// populate the new admin columns
add_action( 'manage_pages_custom_column' , 'custom_print_archive_columns' );

function custom_print_archive_columns( $print_archive_columns ) {

  global $post;  

  switch ( $print_archive_columns ) {  

    case 'print-archive-categories':  
      $terms = get_the_term_list($post->ID, 'print-archive-categories', '', ', ','');  
      if ( is_string( $terms ) ) {
        echo $terms;
      } else {
        echo 'No Categories';
      }
      break;  

  }  

}  

/* 
 * Create_Print_Archive_Details information for custom post types 'print-archive'
**************************************/

// start creating new form fields 
add_action("admin_init", "register_print_archive_meta");

// register the new section and create a meta box
function register_print_archive_meta() {
  add_meta_box( 'print-archive-meta', 'Print Archive Details', 'setup_print_archive_meta_options', 'print-archive', 'side', 'high' );
}

// create form 
function setup_print_archive_meta_options() {
  global $post;
  $post_type = $post->post_type;
  $post_id = $post->ID;

// create meta box ONLY if this is a custom post type of 'print-archive' 
  if ( $post_type == 'print-archive') {

    // pull hidden flag. This helps differentiate between manual saves and auto-saves (in auto-saves, the file wouldn't be passed).
    $testimonail_manual_save_flag = get_post_meta($post_id, '_print_archive_manual_save_flag', TRUE);

    // pull form fields
    $print_archive_link = esc_attr( get_post_meta($post_id, '_print_archive_link', TRUE) ); 
  
    // print out a hidden flag. This helps differentiate between manual saves and auto-saves (in auto-saves, the file wouldn't be passed).
    echo '<input type="hidden" name="print_archive_manual_save_flag" value="true" />';

    echo "<fieldset class='meta-fields'><label for='print_archive_link'>Print Archive Link:</label><input type='text' name='print_archive_link' value='{$print_archive_link}' /><p class='input-description'>A link to the print archive.</p></fieldset>";
  
  } // end if ( $post_type == 'print-archive')

} // end if ( $post_type == 'print-archive')


add_action('save_post', 'save_print_archive_meta', 10, 2);
// save the field data to posts and attachments 
function save_print_archive_meta() {

  global $post;
  $post_type = ''; 
  $post_id = ''; 

  // pull object variables once for later use throughout this function
  if ($post != NULL) {
    $post_type = $post->post_type;
    $post_id = $post->ID;
  }

  // check to see if this is a custom post type of 'print-archive', and the manual save flag exists to ensure this is not the result of an auto-save
  if( $post_type == 'print-archive' && isset($_POST['print_archive_manual_save_flag'])) {

    update_post_meta($post_id, '_print_archive_link', $_POST['print_archive_link']);

  }

}


/* 
* set a sepcific thumbnail size for print archive thumbnails 
**************************************/

if ( function_exists( 'add_image_size' ) ) {
  add_image_size( 'print-archive-thumb', '125', '165' );
}


/* 
* insert print archive out put 
* @category - taxonomy of archive
**************************************/

function insert_print_archive ($atts) {

    extract( shortcode_atts( array(
      'category' => 'florida',
    ), $atts ) );

    $paloop = new WP_Query( array( 'post_type' => 'print-archive', 'print-archive-categories' => $category, 'order' => 'DESC', 'orderby' => 'date', 'posts_per_page' => '-1' ) );

    if ( $paloop ) :
      
      $myCount = $paloop->post_count;

      $i = 0;

      $output = NULL;

      $output .= '<h2 class="print-archive-category-header">' . $category . '</h2>';

      $output .= '<div class="print-archive-content-wrap">';

      while ( $paloop->have_posts() ) : $paloop->the_post();

        if ( $i%4 ==  0 ) {
          $output .= '<div style="height:5px;clear:both;width:100%;"></div>';
        }
        $post_id = get_the_ID();

        // get the published month
        $month = get_the_time( 'F', $post_id );

        // get the published year
        $year = get_the_time( 'Y', $post_id );

        // get the link 
        $print_archive_link = get_post_meta( $post_id, '_print_archive_link', TRUE );

        
        $output .= '<div class="print-archive-item-wrap">';

        $output .= '  <div class="print-archive-thumbmail"><a href="' . $print_archive_link . '" target="_blank">' . get_the_post_thumbnail( $post_id, 'print-archive-thumb' ) . '</a></div>';
        
        $output .= '  <div class="print-archive-date"><a href="' . $print_archive_link . '" target="_blank">' . $month . ' ' . $year . '</a></div>';

        $output .= '</div>'; 

        $i++;

      endwhile;

      $output .= '</div>';

      return $output;

      $i = 0;

    endif;

}

add_shortcode( 'print-archive', 'insert_print_archive' );

/* 
* Set up Display pages
**************************************/

//Template fallback
add_action("template_redirect", 'print_archive_theme_redirect');

function print_archive_theme_redirect() {

  global $wp;

  $plugindir = dirname( __FILE__ );

  $is_post = array_key_exists('post_type', $wp->query_vars );

  if ( $wp->query_vars ) {
    //A Specific Custom Post Type
    if ( $is_post && $wp->query_vars["post_type"] == 'print-archive') {

      $templatefilename = 'single-print-archive.php';

      if (file_exists(TEMPLATEPATH . '/' . $templatefilename)) {
        $return_template = TEMPLATEPATH . '/' . $templatefilename;
      } else {
        $return_template = $plugindir . '/themefiles/' . $templatefilename;
      }   

      do_print_archive_theme_redirect($return_template);

    }
  }

}

function do_print_archive_theme_redirect($url) {

  global $post, $wp_query;

  if (have_posts()) {
    include($url);
    die();
  } else {
    $wp_query->is_404 = true;
  }

}
?>
