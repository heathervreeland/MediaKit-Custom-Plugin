<?php
/*
* Plugin Name: Testimonials by BKT
* Plugin URI: http://www.benkaplan.info/
* Description: A plugin that makes adding testimonials to your pages, posts or sidebars super easy! 
* Version: 1.1 
* Author: Ben Kaplan 
* Author URI: http://www.benkaplan.info/

* General stuff
*
* Register_custom_post_type 
* Register a custom post type, 'bkttestimonial' 
*
* Update the standard Administration listing
*
* Create_Testimonial_Details 
* Create an admin form to manage specific data fields for custom post type
*
* Testimonial_shortcode
* Create a shortcode for use in pages or posts 
* - @testimonial_category 
* - @testimonial_name
*
* Testimonial_sidebar_widget 
* Create a widget for use in the sidebar
* - @widget_category
* - @show_thumbnail
*/


/*
 *
 * General Stuff
 *************************************************/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}

// backward compatible constants
if ( ! function_exists( 'is_ssl' ) ) {
  function is_ssl() {
    if ( isset($_SERVER['HTTPS']) ) {
      if ( 'on' == strtolower($_SERVER['HTTPS']) )
      return true;
      if ( '1' == $_SERVER['HTTPS'] )
      return true;
    } elseif ( isset($_SERVER['SERVER_PORT']) && ( '443' == $_SERVER['SERVER_PORT'] ) ) {
      return true;
    }
    return false;
  }
}

if ( version_compare( get_bloginfo( 'version' ) , '3.0' , '<' ) && is_ssl() ) {
  $wp_content_url = str_replace( 'http://' , 'https://' , get_option( 'siteurl' ) );
} else {
  $wp_content_url = get_option( 'siteurl' );
}
$wp_content_url .= '/wp-content';
$wp_content_dir = ABSPATH . 'wp-content';
$wp_plugin_url = $wp_content_url . '/plugins';
$wp_plugin_dir = $wp_content_dir . '/plugins';
$wpmu_plugin_url = $wp_content_url . '/mu-plugins';
$wpmu_plugin_dir = $wp_content_dir . '/mu-plugins';

/*
 * Register_custom_post_type - Testimonials 
 *************************************************/

add_action( 'init', 'create_testimonial_post_type' );

function create_testimonial_post_type() {

  global $wp_plugin_url;

  $icon = plugins_url( 'images/script-code-single.png', __FILE__ );

  // create the custom post type
  register_post_type( 'bkttestimonial',
    array(
      'labels' => array(
        'name' => __( 'Testimonials' ), 
        'singular_name' => __( 'Testimonial' ), 
        'all_items' => __( 'All Testimonials' ), 
        'add_new' => _x('Add New Testimonial', 'bkttestimonial'), 
        'add_new_item' => __('Add New Testimonial'), 
        'edit_item' => __('Edit Testimonial'), 
        'new_item' => __('New Testimonial'), 
        'view_item' => __('View Testimonial'), 
        'search_items' => __('Search Testimonials'), 
        'not_found' =>  __('No testimonials found'), 
        'not_found_in_trash' => __('No testimonials found in Trash'), 
        'parent_item_colon' => '', 
        'menu_name' => 'Testimonials'
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
      'menu_position' => 21,
      'menu_icon' => $icon,
      'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions', 'page-attributes')
    )
  );

  // create categories for the custom post type
  register_taxonomy(
    "bkttestimonial-categories", 
    array("bkttestimonial"), 
    array(
      "hierarchical" => true, 
      "labels" => array(
        'name' => _x( 'Testimonial Categories', 'taxonomy general name' ),
        'singular_name' => _x( 'Category', 'taxonomy singular name' ),
        'search_items' =>  __( 'Search Testimonial Categories' ),
        'popular_items' => __( 'Popular Testimonial Categories' ),
        'all_items' => __( 'All Testimonial Categories' ),
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => __( 'Edit Testimonial Category' ), 
        'update_item' => __( 'Update Testimonial Category' ),
        'add_new_item' => __( 'Add New Testimonial Category' ),
        'new_item_name' => __( 'New Testimonial Category Name' ),
        'separate_items_with_commas' => __( 'Separate testimonial categories with commas' ),
        'add_or_remove_items' => __( 'Add or remove testimonial categories' ),
        'choose_from_most_used' => __( 'Choose from the most used testimonial categories' ),
        'menu_name' => __( 'Testimonial Categories' ),
      ),
      "show_ui" => true, 
      "query_var" => true, 
      'rewrite' => array( 'slug' => 'bkttestimonial', 'with_front' => true, 'heirarchical' => true )
    )
  );
} // create_testimonial_post_type()

/*
 * Add a new page to the menu for Documentation (aka how the heck does the end-user use this damn testimonial) 
 *

add_action('admin_menu' , 'bkt_testimonial_enable_pages');
 
function bkt_testimonial_enable_pages() {
 add_submenu_page('edit.php?post_type=bkttestimonial', 'Testimonial Help', 'Help', 'edit_posts', basename('testimonial-help'), 'testimonial_submenu_page_callback');
}

function testimonial_submenu_page_callback() {
  include( 'themefiles/testimonial-help.php' );
}
 *****************************************************/


/*
 * Update the standard Administration listing
 *
 *****************************************************/

add_filter('manage_edit-bkttestimonial_columns', 'add_new_bkttestimonial_columns');

function add_new_bkttestimonial_columns($bkttestimonial_columns) {

  unset($bkttestimonial_columns);

  return array(
  'cb' => '<input type="checkbox" />',
  'title' => __('Testimonail Name'),
  'bkttestimonial-categories' => __('Testimonial Categories'),
  'date' => __('Date')
  );

}

// populate the new admin columns
add_action( 'manage_pages_custom_column' , 'custom_columns' );

function custom_columns( $bkttestimonial_columns ) {

  global $post;  

  switch ( $bkttestimonial_columns ) {  

    case 'bkttestimonial-categories':  
      $terms = get_the_term_list($post->ID, 'bkttestimonial-categories', '', ', ','');  
      if ( is_string( $terms ) ) {
        echo $terms;
      } else {
        echo 'No Categories';
      }
      break;  

  }  

}  

/* 
 * Create_Testimonial_Details information for custom post types 'bkttestimonial'
 * - add enctype="multipart/form-data" to post edit form
 * - adding forms to custom post type, 'theme', edit page

    @testimonial_headline 
    @testimonial_author
    @testimonial_thumbnail
    @testimonial_thumbnail_del
    @testimonial_thumbnail_title
    @testimonial_title
    @testimonial_company
    @testimonial_url

 *****************************************************/

// add a specific thumbnail size that only sets the width
if ( function_exists( 'add_image_size' ) ) { 
  add_image_size( 'testimonial-thumb', 150, 9999 ); //150 pixels wide (and unlimited height)
}

// first add new enctype to post edit form to handle upload field
function bkt_add_edit_form_multipart_encoding() {
  echo ' enctype="multipart/form-data"';
}
add_action('post_edit_form_tag', 'bkt_add_edit_form_multipart_encoding');


// next, start creating new form fields 
add_action("admin_init", "register_testimonial_meta");

// register the new section and create a meta box
function register_testimonial_meta() {
  add_meta_box( 'testimonial-meta', 'Testimonial Details', 'setup_testimonial_meta_options', 'bkttestimonial', 'normal', 'high' );
}


// create form 
function setup_testimonial_meta_options() {
  global $post;
  $post_type = $post->post_type;
  $post_parent = $post->post_parent;
  $post_id = $post->ID;

  // first toss any errors to the admin GUI
  $errors = get_transient('settings_errors_file-upload');

  if($errors) {
    // pull the last (aka the first in the array) error from the file-upload settings error
    $my_error = $errors[0]['message'];

    // create a standard admin error
    echo '<div class="error">' . $my_error . '</div>';

    // set the appropriate file upload fieldset background to the standard admin error style
    echo '<style>#file-upload {  background-color: #FFEBE8; border-color: #CC0000; }</style>';

    // just to be clean, remove the error
    delete_transient('settings_errors_file-upload');
  }

// create meta box ONLY if this is a custom post type of 'bkttestimonial' 
  if ( $post_type == 'bkttestimonial') {

    // pull hidden flag. This helps differentiate between manual saves and auto-saves (in auto-saves, the file wouldn't be passed).
    $testimonail_manual_save_flag = get_post_meta($post_id, '_testimonial_manual_save_flag', TRUE);

    // pull form fields
    $testimonial_headline = esc_attr( get_post_meta($post_id, '_testimonial_headline', TRUE) ); 
    $testimonial_author = esc_attr( get_post_meta($post_id, '_testimonial_author', TRUE) );
    $testimonial_title = esc_attr( get_post_meta($post_id, '_testimonial_title', TRUE) );
    $testimonial_company = esc_attr( get_post_meta($post_id, '_testimonial_company', TRUE) );
    $testimonial_url = esc_attr( get_post_meta($post_id, '_testimonial_url', TRUE) );
    $testimonial_thumbnail_title = esc_attr( get_post_meta($post_id, '_testimonial_thumbnail_title', TRUE) );

    // the thumbnail image attachments are managed with the attachment features
    // the following are placed into the poost meta table when calling attachment functions in the save function

    // get image attachment ID from post_meta table
    $testimonial_thumbnail_id = get_post_meta($post_id, '_testimonial_thumbnail_id', TRUE);
    // get image attachment URL from post_meta table
    $testimonial_thumbnail_url = esc_attr( get_post_meta($post_id, '_testimonial_thumbnail_url', TRUE) );
    // get image attachment file name from post_meta table
    $testimonial_thumbnail_file = get_post_meta($post_id, '_testimonial_thumbnail_file', TRUE);

  // print out a hidden flag. This helps differentiate between manual saves and auto-saves (in auto-saves, the file wouldn't be passed).
    echo '<input type="hidden" name="testimonial_manual_save_flag" value="true" />';

  // print out the form fields
    echo "<fieldset class='meta-fields'><label for='testimonial_author'>Testimonial Author:</label><input type='text' name='testimonial_author' value='{$testimonial_author}' /><p class='input-description'>The person who wrote the testimonial.</p></fieldset>";
    //echo "<fieldset class='meta-fields'><label for='testimonial_headline'>Testimonial Headline:</label><input type='text' name='testimonial_headline' value='{$testimonial_headline}' /><p class='input-description'>A lead-in sentence, or short quote that appears at the top of the testimonial.</p></fieldset>";
    echo "<input type='hidden' name='testimonial_headline' value='{$testimonial_headline}' />";
    //echo "<fieldset class='meta-fields'><label for='testimonial_title'>Author's Title or Job Title:</label><input type='text' name='testimonial_title' value='{$testimonial_title}' /><p class='input-description'>This is the Tesimonial Author's job title, or their expert title.</p></fieldset>";
    echo "<input type='hidden' name='testimonial_title' value='{$testimonial_title}' />";
    echo "<fieldset class='meta-fields'><label for='testimonial_company'>Author's Company:</label><input type='text' name='testimonial_company' value='{$testimonial_company}' /><p class='input-description'>The company the Author works for or owns.</p></fieldset>";
    //echo "<fieldset class='meta-fields'><label for='testimonial_url'>Author URL:</label><input type='text' name='testimonial_url' value='{$testimonial_url}' /><p class='input-description'>The website for the Author or their company.</p></fieldset>";
    echo "<input type='hidden' name='testimonial_url' value='{$testimonial_url}' />";
    echo "<fieldset class='meta-fields'><label for='testimonial_thumbnail_title'>Author Thumbnail Title:</label><input type='text' name='testimonial_thumbnail_title' value='{$testimonial_thumbnail_title}' /><p class='input-description'>This is the caption that can show up under the thumbnail image of the Author.</p></fieldset>";

    echo "<fieldset class='meta-fields' id='file-upload'>
      <h4>Author Thumbnail Image</h4>
      <label for='testimonial_thumbnail'>Upload New Thumbnail:</label><input type='file' name='testimonial_thumbnail' />
      <p>Current File Name: {$testimonial_thumbnail_file}</p>
      <p><img src='{$testimonial_thumbnail_url}' /></p>
      <input type='checkbox' name='testimonial_thumbnail_del' class='file-upload-checkbox' /><label for='testimonial_thumbnail_del'>DELETE the current Testimonial Thumbnail?</label></fieldset>";

//var_dump($post);
  } // end if ( $post_type == 'bkttestimonial')
  
  // add the save action here so that the $post is fully available to the save function

} // end setup_testimonial_meta_options()


add_action('save_post', 'save_testimonial_meta', 10, 2);
// save the field data to posts and attachments 
function save_testimonial_meta() {

  global $post;
  $post_type = ''; 
  $post_id = ''; 

  // pull object variables once for later use throughout this function
  if ($post != NULL) {
    $post_type = $post->post_type;
    $post_id = $post->ID;
  }

  // check to see if this is a custom post type of 'bkttestimonial', and the manual save flag exists to ensure this is not the result of an auto-save
  if( $post_type == 'bkttestimonial' && isset($_POST['testimonial_manual_save_flag'])) {

    update_post_meta($post_id, '_testimonial_author', $_POST['testimonial_author']);
    update_post_meta($post_id, '_testimonial_headline', $_POST['testimonial_headline']);
    update_post_meta($post_id, '_testimonial_title', $_POST['testimonial_title']);
    update_post_meta($post_id, '_testimonial_company', $_POST['testimonial_company']);
    update_post_meta($post_id, '_testimonial_url', $_POST['testimonial_url']);
    update_post_meta($post_id, '_testimonial_thumbnail_title', $_POST['testimonial_thumbnail_title']);

    // pull for later use in thumbnail storage
    $testimonial_thumbnail_title = '';
    if ( isset( $_POST['testimonial_thumbnail_title'] ) ) {
      $testimonial_thumbnail_title = $_POST['testimonial_thumbnail_title'];
    }

    // make sure we test to see if it's there before checking on it
    $testimonial_thumbnail_del = FALSE;
    if ( isset( $_POST['testimonial_thumbnail_del'] ) ) {
      $testimonial_thumbnail_del = $_POST['testimonial_thumbnail_del']; 
    }

    if ( $testimonial_thumbnail_del == TRUE ) {
      
      delete_post_meta($post_id, '_testimonial_thumbnail_title');
      delete_post_meta($post_id, '_testimonial_thumbnail_id');
      delete_post_meta($post_id, '_testimonial_thumbnail_url');
      delete_post_meta($post_id, '_testimonial_thumbnail_file');

    // HANDLE THE FILE UPLOAD
    // If the upload field has a file in it
    } elseif (isset($_FILES['testimonial_thumbnail']) && ($_FILES['testimonial_thumbnail']['size'] > 0)) {

      // Get the type of the uploaded file. This is returned as "type/extension"
      $arr_file_type = wp_check_filetype(basename($_FILES['testimonial_thumbnail']['name']));
      $uploaded_file_type = $arr_file_type['type'];

      // Set an array containing a list of acceptable formats - in this case an image
      $allowed_file_types = array('image/png','image/gif','image/jpg','image/jpeg');

      // If the uploaded file is the right format
      if(in_array($uploaded_file_type, $allowed_file_types)) {

        // Options array for the wp_handle_upload function. 'test_upload' => false
        $upload_overrides = array( 'test_form' => false ); 

        // Handle the upload using WP's wp_handle_upload function. Takes the posted file and an options array
        $uploaded_file = wp_handle_upload($_FILES['testimonial_thumbnail'], $upload_overrides);

        // If the wp_handle_upload call returned a local path for the image
        if(isset($uploaded_file['file'])) {

          // The wp_insert_attachment function needs the literal system path, which was passed back from wp_handle_upload
          $file_name_and_location = $uploaded_file['file'];

          // strip out the path for display purposes
          $file_name = basename($file_name_and_location);

          // Generate a title for the image that'll be used in the media library
          $file_title_for_media_library = $post->post_title;

          // Set up options array to add this file as an attachment
          $attachment = array(
            'post_mime_type' => $uploaded_file_type,
            'post_title' => 'thumbnail-' . $testimonial_thumbnail_title,
            'post_content' => '',
            'post_status' => 'inherit'
          );

          // Run the wp_insert_attachment function. 
          // This adds the file to the media library and generates the thumbnails. 
          // If you wanted to attch this image to a post, you could pass the post id as a third param and it'd magically happen.
          $attach_id = wp_insert_attachment( $attachment, $file_name_and_location, $post_id );

          $testimonial_thumbnail_url = wp_get_attachment_url($attach_id);
          $testimonial_thumbnail_id = (int)$attach_id;

          require_once(ABSPATH . "wp-admin" . '/includes/file.php');

          $attach_data = wp_generate_attachment_metadata( $attach_id, $file_name_and_location );

          wp_update_attachment_metadata($attach_id,  $attach_data);

          // Before we update the post meta, trash any previously uploaded files for this post.
          // You might not want this behavior, depending on how you're using the uploaded files.
          $existing_uploaded_file = (int) get_post_meta($post_id,'_testimonial_thumbnail_id', true);

          if(is_numeric($existing_uploaded_file)) {
            wp_delete_attachment($existing_uploaded_file);
          }

          // Now, update the post meta to associate the new image with the post
          update_post_meta($post_id,'_testimonial_thumbnail_id',$testimonial_thumbnail_id);
          
          // toss the file name in the post meta for display purposes
          update_post_meta($post_id,'_testimonial_thumbnail_file',$file_name);

          // also update the URL to the attached file
          update_post_meta($post_id,'_testimonial_thumbnail_url',$testimonial_thumbnail_url);

          // Set the feedback flag to false, since the upload was successful
          $upload_feedback = false;

        } else { // wp_handle_upload returned some kind of error. the return does contain error details, so you can use it here if you want.

          $upload_feedback = 'THERE WAS A PROBLEM WITH YOUR UPLOAD.';

        }
      } else { // wrong file type

        add_settings_error('file-upload','file_format','<p>Sorry, you tried to upload a thumbnail that was not an image file (.jpg, .png, .gif).</p><p>The rest of your video data was saved, but the thumbnail was not saved or updated.</p><p>Please upload only image files.</p>', 'error');
        set_transient('settings_errors_file-upload', get_settings_errors('file-upload'), 30);

      }

    } else { // No file was passed

      $upload_feedback = false;

    }

  } // end if 'bkttestimonial' post type and manual save flag was set
  
}

// generate plugin specific styles and javascript 
if (!is_admin()) {

  add_action('wp_enqueue_scripts', 'show_public_testimonial_javascript');

  // generate testimonial specific javascript if viewing a testimonial on the public (non-admin) side
  function show_public_testimonial_javascript() {

    // check to see if jquery-cycle is already in called by wordpress.  If not, add the latest version as of 10/2011 from the cdn
    if ( ! ( isset( $GLOBALS['wp_scripts']->registered[ 'jquery-cycle' ] ) ) ) {
      wp_register_script('jquery-cycle', 'http://ajax.aspnetcdn.com/ajax/jquery.cycle/2.99/jquery.cycle.all.min.js', array('jquery'), '2.99', true);   
      wp_enqueue_script( 'jquery-cycle' );
    }
    
    // check to see if jquery-ui-core is already in called by wordpress.  If not, add the latest version as of 10/2011 from the cdn
    if ( ! ( isset( $GLOBALS['wp_scripts']->registered[ 'jquery-ui-core' ] ) ) ) {
      wp_enqueue_script('jquery-ui-core');
    }

    // toss in plugin specific javascript and styles
    wp_enqueue_style( 'testimonial-css', '/wp-content/plugins/occasions-custom/custom-post-types/themefiles/testimonial.css' );  
    wp_enqueue_style( 'testimonial-scroll-css', '/wp-content/plugins/occasions-custom/custom-post-types/themefiles/jquery.jscrollpane.css' );  
    wp_enqueue_script('testimonial-scroll-mouse-js', WP_PLUGIN_URL . '/occasions-custom/custom-post-types/themefiles/jquery.mousewheel.js', array('jquery'), true);  
    wp_enqueue_script('testimonial-scroll-js', WP_PLUGIN_URL . '/occasions-custom/custom-post-types/themefiles/jquery.jscrollpane.min.js', array('jquery'), true);  
    wp_enqueue_script('testimonial-js', WP_PLUGIN_URL . '/occasions-custom/custom-post-types/themefiles/testimonial.js', array('jquery'), true);  


  }

} else {

  add_action('wp_enqueue_scripts', 'show_public_testimonial_javascript');

  function show_public_testimonial_javascript() {
    // check to see if jquery-ui-core is already in called by wordpress.  If not, add the latest version as of 10/2011 from the cdn
    if ( ! ( isset( $GLOBALS['wp_scripts']->registered[ 'jquery-ui-core' ] ) ) ) {
      wp_enqueue_script('jquery-ui-core');
    }
  }
}

/* 
* Set up Display pages
**************************************/

//Template fallback
add_action("template_redirect", 'testimonial_theme_redirect');

function testimonial_theme_redirect() {

  global $wp;

  $plugindir = dirname( __FILE__ );

  $is_post = array_key_exists('post_type', $wp->query_vars );

  if($wp->query_vars) {

    //A Specific Custom Post Type
    if ( $is_post && $wp->query_vars["post_type"] == 'bkttestimonial') {

      $templatefilename = 'single-bkttestimonial.php';

      if (file_exists(TEMPLATEPATH . '/' . $templatefilename)) {
        $return_template = TEMPLATEPATH . '/' . $templatefilename;
      } else {
        $return_template = $plugindir . '/themefiles/' . $templatefilename;
      }   

      do_testimonial_theme_redirect($return_template);

    }
  }

}

function do_testimonial_theme_redirect($url) {

  global $post, $wp_query;

  if (have_posts()) {
    include($url);
    die();
  } else {
    $wp_query->is_404 = true;
  }

}



/**
 * Testimonial_shortcode
 * @testimonial_category - for display of testimonials from one category
 * @testimonial_name - for display of individual testimonials
 *************************************************/

// [testimonials category="value"]
// - returns a DL list of testimonials in the given category

// [testimonials name="value"]
// - value = the custom post type title
// - returns a DL list of individual testimonial

// [testimonials name="value" show_thumbnail="on"]
// - show_thumbnial values are either 'on' or 'off'
// - returns a DL list of individual testimonials with or without a thumbnail

// [testimonials category="value" name="value"]
// - name attribute is ignored if category is given 
// - returns a DL list of testimonials in the given category

function bkttestimonial_shortcode_func( $atts ) {

/* PREP FOR PULLING DATA AND SETTING OUTPUT
 ******************************************************/

  extract( shortcode_atts( array(
    'category' => NULL,
    'name' => NULL,
    'show_thumbnail' => 'on' 
  ), $atts ) );

  // create a null variable
  $testimonial_loop = NULL;
  $testimonial_category = NULL; 
  $testimonial_name = NULL; 
  $testimonial_show_thumbnail = 'on'; 

  // clean up the strings
  if ( $category != NULL ) {
    $testimonial_category = esc_attr($category);
  }
  
  if ( $name != NULL ) {
    $testimonial_name = esc_attr($name);
  }

  // check to see if we should be showing the thumbnails
  $testimonial_show_thumbnail = esc_attr($show_thumbnail); 


  // create an empty string for output
  $output = '';
  
  if ( $testimonial_category != NULL ) { // was the category variable passed...aka not null?

    // pull all the testimonials with the given category
    $testimonial_loop = new WP_Query( array( 'post_type' => 'bkttestimonial', 'bkttestimonial-categories' => $testimonial_category, 'order' => 'ASC', 'orderby' => 'menu_order', 'posts_per_page' => '-1' ) );

  } elseif ( $testimonial_name != NULL ) { // was the name variable passed...aka not null?

    // pull all the individual testimonial with the given slug or name 
    $testimonial_loop = new WP_Query( array( 'post_type' => 'bkttestimonial', 'name' => $testimonial_name, 'order' => 'ASC', 'orderby' => 'menu_order', 'posts_per_page' => '-1' ) );

  } else { // what the heck, just go ahead and pull all the testimonials!
    
    // pull all the testimonials if no variables are passed
    $testimonial_loop = new WP_Query( array( 'post_type' => 'bkttestimonial', 'order' => 'ASC', 'orderby' => 'menu_order', 'posts_per_page' => '-1' ) );

  }


  if( $testimonial_loop ) { // check to see if an object was created successfully above





    // wrap all the testimonials
    $output .= '<div id="bkt-testimonials-wrap">';
    $output .= '<div id="bkt-testimonials" class="horizontal-only">';
    $output .= '<div class="testimonial-container">';

    while ( $testimonial_loop->have_posts() ) : $testimonial_loop->the_post();


/* PULL THE DATA
 ******************************************************/

      $post_id = get_the_id(); 

      // get the content
      $testimonial_content = wpautop(get_the_content());

      // get the featured thumbnail
      $testimonial_thumbnail_featured = get_the_post_thumbnail( $post_id, 'full');

      // get the thumbnail
      $testimonial_thumbnail_id = get_post_meta( $post_id, '_testimonial_thumbnail_id', TRUE );
      $testimonial_thumbnail_full = wp_get_attachment_image( $testimonial_thumbnail_id, 'testimonial-thumb');

      // get the thumbnail title
      $testimonial_thumbnail_title = get_post_meta( $post_id, '_testimonial_thumbnail_title', TRUE );

      // get the headline 
      $testimonial_headline = get_post_meta( $post_id, '_testimonial_headline', TRUE );

      // get the title
      $testimonial_title = get_post_meta( $post_id, '_testimonial_title', TRUE );

      // get the company
      $testimonial_company = get_post_meta( $post_id, '_testimonial_company', TRUE );

      // get the url 
      $testimonial_url = get_post_meta( $post_id, '_testimonial_url', TRUE );

      // get the testimonial author 
      $testimonial_author = get_post_meta( $post_id, '_testimonial_author', TRUE );

/* SET THE OUTPUT
 ******************************************************/

      //$output .= '  <div class="testimonial-top"></div>';
      $output .= '  <div class="testimonial-slide">';
      $output .= '  <div class="testimonial-slide-wrap">';

      // only show the <dd>'s if they contain information


      if ( $testimonial_content != '' ) {
        $output .= '    <div class="testimonial-content-wrap">';
        //$output .= '      <div class="testimonial-quote">&ldquo;</div>';
        $output .= '      <div class="testimonial-content">' . $testimonial_content;

        $output .= '      <div class="testimonial-author-wrap">';
        if ( $testimonial_author != '' ) {
          $output .= '        <div class="testimonial-author">' . $testimonial_author . '</div>';
        }

        if ( $testimonial_company != '' ) {
          $output .= '        <div class="testimonial-company">' . $testimonial_company . '</div>';
        }
        $output .= '      </div><!-- testimonial-author-wrap -->';

        $output .= '    </div><!-- testimonial-content -->';

        $output .= '    </div><!-- testimonial-content-wrap -->';
      }


      $output .= '  <div class="testimonial-thumb-wrap">';
      if ( $testimonial_thumbnail_featured != '' ) {
        $output .= '    <div class="testimonial-thumbnail-featured">' . $testimonial_thumbnail_featured . '</div>';
      }
      if ( $testimonial_thumbnail_full != '' & $show_thumbnail == 'on' ) {

        $output .= '    <div class="testimonial-thumbnail">' . $testimonial_thumbnail_full;
        //$output .= '    <br /><span class="testimonial-thumbnail-title">' . $testimonial_thumbnail_title . '</span>';
        $output .= '    </div>';
      }
      $output .= '  </div><!-- testimonial-thumb-wrap -->';

      /*
      if ( $testimonial_headline != '' ) {
        $output .= '    <dd class="testimonial-headline">' . $testimonial_headline . '</dd>';
      }
      */

      /*
      if ( $testimonial_title != '' ) {
        $output .= '    <dd class="testimonial-title">' . $testimonial_title . '</dd>';
      }
      */

      /*
      if ( $testimonial_url != '' ) {
        $output .= '    <dd class="testimonial-url">' . $testimonial_url . '</dd>';
      }
      */


      $output .= '  </div><!-- testimonial-slide-wrap -->';
      $output .= '  </div><!-- testimonial-slide -->';
      //$output .= '  <div class="testimonial-bottom"></div>';

    endwhile;

    $output .= '</div><!-- end .testimonial-container -->';
    $output .= '</div><!-- end #bkt-testimonials -->';
    $output .= '</div><!-- end #bkt-testimonials-wrap -->';


    return $output; 
   
  } else { // if no object is created then return an empty string

    return $output; 

  }

}
add_shortcode( 'bkttestimonial', 'bkttestimonial_shortcode_func' );

/*
 * 
 * Add styles to the admin area for the testimonial custom post type area
 *************************************************/

add_action('admin_print_styles', 'show_testimonial_admin_css');

// generate styles specific to the Testimonial form in the admin area
if (is_admin()) {

  function show_testimonial_admin_css() {

    $post_type = get_post_type();

    if ( $post_type == 'bkttestimonial' ) {

      global $wp_plugin_url;

      wp_enqueue_style( 'testimonial-admin', $wp_plugin_url . '/occasions-custom/themefiles/testimonial-admin.css' );  

    }
  }
}

/**
 * Testimonial_sidebar_widget 
 * @widget_category - sets testimonial category to display
 *************************************************/

class TestimonialWidget extends WP_Widget {

/* PREP FOR PULLING DATA AND SETTING OUTPUT
 ******************************************************/

  /** constructor */

  function __construct() {
    $widget_ops = array('description' => 'A testimonial widget that gives you the ability to add testimonials to the sidebar.' );
    parent::__construct(false, $name = 'TestimonialWidget', $widget_ops );  
  }

  /** @see WP_Widget::widget 
  * this is the output to the browser
  * - pull the options out of $args
  * - check to see if empty, then assign them value from $instance
  * - output images and links
  */
  function widget($args, $instance) {   

    /* validation code - digits only, no dots */
    function is_digits($element) {
      return !preg_match ("/[^0-9]/", $element);
    }
    

    extract( $args );
    $title = apply_filters('widget_title', $instance['title']);
    $widget_category = empty($instance['widget_category']) ? '0' : apply_filters('widget_category', (int)$instance['widget_category']);
    $testimonial_page_slug = empty($instance['testimonial_page_slug']) ? '0' : apply_filters('testimonial_page_slug', $instance['testimonial_page_slug']);
    $show_thumbnail = isset($instance['show_thumbnail']) ? $instance['show_thumbnail'] : false;

    $slideshow_speed = isset($instance['slideshow_speed']) ? $instance['slideshow_speed'] : false;
    if ( is_digits( $slideshow_speed ) ) {
      $slideshow_speed = $slideshow_speed * 1000;
    } else {
      $slideshow_speed = 4000; 
    }

    $slideshow_transition = isset($instance['slideshow_transition']) ? $instance['slideshow_transition'] : false;
    if ( is_digits( $slideshow_transition ) ) {
      $slideshow_transition = $slideshow_transition * 1000;
    } else {
      $slideshow_transition = 4000; 
    }

    // grab the page
    global $wp_query;

    // pull json wrapper to ensure legacy PHP installs can handle the json call when sending the slideshow speed setting to the necessary javascript
    require('themefiles/jsonwrapper/jsonwrapper.php');

    // grab the query_vars for testing page slug against excluded page slug
    $this_page_vars = $wp_query->query_vars;

    // pull the current page ID 
    $this_pageid = $wp_query->post->ID;

    // set boolean to true 
    $is_not_testimonial_page = true;

    // check if we are on the excluded testimonial page and adjust boolean accordingly
    if ( $this_pageid != '' ) {
      if ( $this_pageid == $testimonial_page_slug ) {
        $is_not_testimonial_page = false;
      }
    }

    // grab the testimonials
    $tloop_args = array( 
      'post_type' => 'bkttestimonial', 
      'tax_query' => array( 
        array(
          'taxonomy' => 'bkttestimonial-categories', 
          'field' => 'id', 
          'terms' => $widget_category
        )
      ), 
      'order' => 'ASC', 
      'orderby' => 'menu_order', 
      'posts_per_page' => '-1' 
    );
    $testimonial_loop = get_posts( $tloop_args );
    /*
    echo '<pre>';
    var_dump($widget_category);
    echo '</pre>';
    */

    // run the test against the excluded page slug, and be sure to only show if there are any testimonials
    if ( $is_not_testimonial_page && $testimonial_loop != NULL ) {

      global $post;

      echo $before_widget; 

    ?>
    <script>
    jQuery(document).ready(function($){
      var $testimonialSlides = $('#testimonial-slideshow');
      var slideshow_speed = <?php echo json_encode( $slideshow_speed ); ?>;
      var slideshow_transition = <?php echo json_encode( $slideshow_transition ); ?>;
      $testimonialSlides.cycle({
        timeout: slideshow_speed,
        speed: slideshow_transition,
        fx:'fade',
        fit:1,
        width:null,
        cleartypeNoBg:true
      });
    }); // end ready()
    </script>


    <h3 class="widget-title"><?php echo $title; ?></h3>
    <div id="testimonial-slideshow">

    <?php 
      foreach ( $testimonial_loop as $side_loop ) {

/* PULL THE DATA 
 ******************************************************/

        //$post_id = get_the_ID(); //$post->ID;
        $post_id = $side_loop->ID; 

        // get the thumbnail
        $testimonial_thumbnail_id = get_post_meta( $post_id, '_testimonial_thumbnail_id', TRUE );
        $testimonial_thumbnail_full = wp_get_attachment_image( $testimonial_thumbnail_id, 'testimonial-thumb' );

        // get the thumbnail title
        $testimonial_thumbnail_title = get_post_meta( $post_id, '_testimonial_thumbnail_title', TRUE );

        // get the headline 
        $testimonial_headline = get_post_meta( $post_id, '_testimonial_headline', TRUE );

        // get the title
        $testimonial_title = get_post_meta( $post_id, '_testimonial_title', TRUE );

        // get the company
        $testimonial_company = get_post_meta( $post_id, '_testimonial_company', TRUE );

        // get the url 
        $testimonial_url = get_post_meta( $post_id, '_testimonial_url', TRUE );

        // get the testimonial author 
        $testimonial_author = get_post_meta( $post_id, '_testimonial_author', TRUE );

        // create a separator and set to show if author exists
        $testimonial_separator = '';
        if ( $testimonial_author != '' ) {
          $testimonial_separator = ', ';
        }

/* SET THE OUTPUT 
 ******************************************************/
    ?>
      <div class="testimonial-slide">
        <div class="testimonial-slide-top"></div>
        <div class="testimonial-slide-content">
<?php if ( $testimonial_thumbnail_full != '' && $show_thumbnail == 'on' ) { ?>
          <div class="testimonial-thumbnail">
            <?php echo $testimonial_thumbnail_full; ?>
            <span><?php echo $testimonial_thumbnail_title; ?></span>
          </div>
<?php } ?>
<?php if ( $testimonial_headline != '' ) { ?>
            <p class="testimonial-headline"><?php echo $testimonial_headline; ?></p>
<?php } ?>
          <p class="testimonial-content"><?php echo $side_loop->post_content; ?></p>
          <p><div><?php  if ( $testimonial_author != '' ) { echo '<span class="testimonial-author">&mdash; ' . $testimonial_author . '</span>'; } if ( $testimonial_title != '' ) { echo $testimonial_separator . $testimonial_title;  };  ?></div>
      <?php
      if ( $testimonial_company != '' ) { ?>
            <div><?php echo $testimonial_company; ?></div>
<?php } ?>
<?php if ( $testimonial_url != '' ) { ?>
            <div><?php echo $testimonial_url; ?></div>
<?php } ?>
          </p>
        </div>
        <div class="testimonial-slide-bottom"></div>
      </div>

    <?php } // end foreach $testimonial_loop ; ?>

    </div><!-- end testimonial-slideshow -->
<?php
    } // end if ( $post->postname != $testimonial_page_slug ) 
  } // end widget()

  /** @see WP_Widget::update 
  * updates widget options
  */
  function update($new_instance, $old_instance) {       
    $instance = $old_instance;
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['testimonial_page_slug'] = strip_tags($new_instance['testimonial_page_slug']);
    $instance['widget_category'] = $new_instance['widget_category'];
    $instance['show_thumbnail'] =  strip_tags($new_instance['show_thumbnail']);
    $instance['slideshow_speed'] =  strip_tags($new_instance['slideshow_speed']);
    $instance['slideshow_transition'] =  strip_tags($new_instance['slideshow_transition']);
    return $instance;
  }

  /** @see WP_Widget::form 
  * populates form in admin area for widget
  */
  function form($instance) {        
    $instance = wp_parse_args( 
      (array) $instance, 
      array( 
        'title' => '',
        'widget_category' => '0',
        'testimonial_page_slug' => '0',
        'show_thumbnail' => false,
        'slideshow_speed' => '4',
        'slideshow_transition' => '4'
      ) 
    );
    $title = esc_attr($instance['title']);
    $testimonial_page_slug = (int)$instance['testimonial_page_slug'];
    $testimonial_page_id = get_page_by_path($testimonial_page_slug);
    
    // the testimonial category drop down
    $widget_category = (int)$instance['widget_category'];
    /*
    $taxonomy = 'bkttestimonial-categories';
    $tax_terms = get_terms($taxonomy);
    $widget_category_drop_down = '';
    $widget_category_drop_down .= '<select id="' . $this->get_field_id('widget_category') . '" name="' . $this->get_field_name('widget_category') . '">';
    $selected = '';

    foreach ($tax_terms as $tax_term) {
      if ( $widget_category == $tax_term->name ) {
        $selected = 'selected="selected"';
      }
      $widget_category_drop_down .= '<option class="level-0" ' . $selected . ' >' . $tax_term->name . '</option>';
    }
    $widget_category_drop_down .= '</select>';
    */
    $cat_args = array(
      //'show_option_all'    => true,
      //'show_option_none'   => ,
      'orderby'            => 'ID', 
      'order'              => 'ASC',
      'show_count'         => 0,
      'hide_empty'         => 1, 
      'child_of'           => 0,
      //'exclude'            => ,
      'echo'               => 1,
      'selected'           => $widget_category,
      'hierarchical'       => 0, 
      'name'               => $this->get_field_name('widget_category'),
      'id'                 => $this->get_field_id('widget_category'),
      'class'              => 'postform',
      'depth'              => 0,
      'tab_index'          => 0,
      'taxonomy'           => 'bkttestimonial-categories',
      'hide_if_empty'      => false 
    );

    $show_thumbnail = $instance['show_thumbnail'];
    $slideshow_speed = $instance['slideshow_speed'];
    $slideshow_transition = $instance['slideshow_transition'];
  ?>

    <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Testimonial Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
    <p><label for="<?php echo $this->get_field_id('widget_category'); ?>"><?php _e('Testimonial Category:'); wp_dropdown_categories($cat_args); ?></label></p>
    <p>the widget category id is <?php echo $widget_category; ?></p>
    <p><label for="<?php echo $this->get_field_id('testimonial_page_slug'); ?>"><?php _e('Exclude from page:'); echo '<br>'; wp_dropdown_pages( array( 'echo' => 1, 'show_option_none' => 'Choose a Page', 'selected' => $testimonial_page_slug, 'name' =>  $this->get_field_name('testimonial_page_slug'), 'id' =>  $this->get_field_id('testimonial_page_slug') ) ); ?> </label></p>
    <p>
      <label for="<?php echo $this->get_field_id('show_thumbnail'); ?>"><?php _e('Show Thumbnail?'); ?></label>
      <input class="checkbox" type="checkbox" <?php if ( $show_thumbnail == 'on' ) echo 'checked'; ?> id="<?php echo $this->get_field_id( 'show_thumbnail' ); ?>" name="<?php echo $this->get_field_name( 'show_thumbnail' ); ?>" />
            
    </p>
    <h4>Slideshow Speed</h4>
    <p><label for="<?php echo $this->get_field_id('slideshow_speed'); ?>"><?php _e('Length of Reading Time in seconds:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('slideshow_speed'); ?>" name="<?php echo $this->get_field_name('slideshow_speed'); ?>" type="text" value="<?php echo $slideshow_speed; ?>" /></label></p>
    <p><label for="<?php echo $this->get_field_id('slideshow_transition'); ?>"><?php _e('Length of Slide Transition in seconds:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('slideshow_transition'); ?>" name="<?php echo $this->get_field_name('slideshow_transition'); ?>" type="text" value="<?php echo $slideshow_transition; ?>" /></label></p>
  <?php
  } // end form()
} // end class

add_action( 'widgets_init', create_function( '', 'register_widget( "TestimonialWidget" );' ) );



?>
