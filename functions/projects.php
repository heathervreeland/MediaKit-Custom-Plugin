<?php

/*
 * Register_custom_post_type - Projects 
 * - this custom post type is mainly utilized for when you need to have a project of content embedded
 *   within a page template somewhere, and need a place to manage it from. This allows us to not
 *   put the content inside of a regular Post and have it lost when the blog fills up.
 *************************************************/

add_action( 'init', 'create_project_post_type' );

function create_project_post_type() {

  $icon = plugins_url() . '/solamar-custom/custom-post-types/images/script-code-single.png';

  // create the custom post type
  register_post_type( 'project',
    array(
      'labels' => array(
        'name' => __( 'Projects' ), 
        'singular_name' => __( 'Project' ), 
        'add_new' => _x('Add New', 'project'), 
        'add_new_item' => __('Add New Project'), 
        'edit_item' => __('Edit Project'), 
        'new_item' => __('New Project'), 
        'view_item' => __('View Project'), 
        'search_items' => __('Search Projects'), 
        'not_found' =>  __('No projects found'), 
        'not_found_in_trash' => __('No projects found in Trash'), 
        'parent_item_colon' => '', 
        'menu_name' => 'Projects'
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
      'menu_position' => 21, // tosses the menu just below Pages and above Comments
      'menu_icon' => $icon,
      'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions', 'page-attributes')
    )
  );

  // create categories for the custom post type
  register_taxonomy(
    "projects", 
    array("project"), 
    array(
      "hierarchical" => true, 
      "labels" => array(
        'name' => _x( 'Project Categories', 'taxonomy general name' ),
        'singular_name' => _x( 'Project Category', 'taxonomy singular name' ),
        'search_items' =>  __( 'Search Project Categories' ),
        'popular_items' => __( 'Popular Project Categories' ),
        'all_items' => __( 'All Project Categories' ),
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => __( 'Edit Project Category' ), 
        'update_item' => __( 'Update Project Category' ),
        'add_new_item' => __( 'Add New Project Category' ),
        'new_item_name' => __( 'New Project Category Name' ),
        'separate_items_with_commas' => __( 'Separate project categories with commas' ),
        'add_or_remove_items' => __( 'Add or remove project category' ),
        'choose_from_most_used' => __( 'Choose from the most used project categories' ),
        'menu_name' => __( 'Project Categories' ),
      ),
      "show_ui" => true, 
      "query_var" => true, 
      'rewrite' => array( 'slug' => 'projects', 'with_front' => true, 'heirarchical' => true )
    )
  );
}

/* 
 * Create_Project_Details information for custom post types 'project'
 * - adding forms to custom post type, 'theme', edit page

    @project_headline 
    @project_description
 *****************************************************/

// next, start creating new form fields 
add_action("admin_init", "register_project_meta");

// register the new section and create a meta box
function register_project_meta() {
  add_meta_box( 'project-meta', 'Project Details', 'setup_project_meta_options', 'project', 'normal', 'high' );
}

// create form 
function setup_project_meta_options() {
  global $post;
  $post_type = $post->post_type;
  $post_parent = $post->post_parent;
  $post_id = $post->ID;

// create meta box ONLY if this is a custom post type of 'project' 
  if ( $post_type == 'project') {

    // pull hidden flag. This helps differentiate between manual saves and auto-saves (in auto-saves, the file wouldn't be passed).
    $testimonail_manual_save_flag = get_post_meta($post_id, '_project_manual_save_flag', TRUE);

    // pull form fields
    $project_headline = esc_attr( get_post_meta($post_id, '_project_headline', TRUE) ); 
    $project_description = esc_attr( get_post_meta($post_id, '_project_description', TRUE) );

  // print out a hidden flag. This helps differentiate between manual saves and auto-saves (in auto-saves, the file wouldn't be passed).
    echo '<input type="hidden" name="project_manual_save_flag" value="true" />';

  // print out the form fields
    echo '<style> .project { padding-top:15px; } .project label { float:left;display:block;width:125px; } .project textarea { width:450px;height:200px; } </style>';
    echo '<h2>Only fill in these two fields if you want this project to appear on the home page.</h2>';
    echo "<fieldset class='meta-fields project'><label for='project_headline'>Project Headline:</label><input type='text' name='project_headline' value='{$project_headline}' /></fieldset>";
    echo "<fieldset class='meta-fields project'><label for='project_description'>Project Description:</label><textarea name='project_description'>{$project_description}</textarea></fieldset>";

  } // end if ( $post_type == 'project')
  
} // end setup_project_meta_options()

add_action('save_post', 'save_project_meta', 10, 2);
// save the field data to posts and attachments 
function save_project_meta() {

  global $post;
  $post_type = ''; 
  $post_id = ''; 

  // pull object variables once for later use throughout this function
  if ($post != NULL) {
    $post_type = $post->post_type;
    $post_id = $post->ID;
  }

  // check to see if this is a custom post type of 'project', and the manual save flag exists to ensure this is not the result of an auto-save
  if( $post_type == 'project' && isset($_POST['project_manual_save_flag'])) {

    update_post_meta($post_id, '_project_headline', $_POST['project_headline']);
    update_post_meta($post_id, '_project_description', $_POST['project_description']);

  } // end if 'project' post type and manual save flag was set
}

/*
 * used to insert multiple loops on page-projects.php
 *********************************************************/
function insert_project_loop($a_term, $title) {

  // pull the new Project Category
  $this_term = $a_term;

  // pull a new wp_query object based upon the Project Category
  $loop = new WP_Query( array( 'post_type' => 'project', 'tax_query' => array( array('taxonomy' => 'projects', 'field' => 'slug', 'terms' => $this_term ) ), 'order' => 'ASC', 'orderby' => 'menu_order', 'posts_per_page' => '-1' ) );

  // make reuse of the Project Category
  $overlay_rel = $this_term;

  // a simple counter to track columns
  $thumbcounter=1;


  if ( $loop->have_posts() ) : 

    // add the section header with the title of the Project Category
    echo '<h2>' . $title . '</h2>';

    while ( $loop->have_posts() ) : $loop->the_post(); 

      $post_id = get_the_ID();

      // set a variable that will be used in maintaining the layout for a four column grid
      $position = '';

      // if we are on the fourth column add the class .last
      if ( $thumbcounter == 4 ) {
        $position = ' last';
      }

      // pull the attachments from the Project.  These will be used to populate the Fancybox Overlay
      $attachments = get_posts( array( 'post_type' => 'attachment', 'numberposts' => '-1', 'post_parent' => $post_id, 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order'=> 'asc' ) );

      $overlay_image = '';

      if ( $attachments ) { 

        foreach ( $attachments as $attachment ) { 

          $attachment_id = $attachment->ID;

          $attachment_meta = get_post_custom($attachment_id);

          $imageoverlaylocation = $attachment_meta['_sola_image_location'];

          if ( $imageoverlaylocation[0] == 'overlay' ) {
            $overlay_image = wp_get_attachment_url( $attachment_id, 'full', true, false, false );
          }

        }

      }

      // output the Thumbnail image of the Project and group it by Project Category using the rel parameter
      echo '<div class="project-thumb' . $position . '"><a rel="group_' . $overlay_rel . '" href="' . $overlay_image . '">' . get_the_post_thumbnail( $post_id, array(241,241) ) . '<div class="mask sepia on2off"></div></a></div>';

      // reset the column counter
      if ( $thumbcounter == 4 ) {
        $thumbcounter = 1;
      } else {
        $thumbcounter++;
      }

    endwhile; 

    // out put a Fancybox script for this particular Project Gategory at the end of the section
echo <<< EOT
        <script>
          $(document).ready(function() {
            $("a[rel=group_{$overlay_rel}]").fancybox({
              'cyclic'          : 'true',
              'transitionIn'    : 'elastic',
              'transitionOut'   : 'elastic',
              'titleShow'       : 'false',
              'titlePosition'   : 'outside',
              'titleFormat'   : function(title, currentArray, currentIndex, currentOpts) {
              return '<span id="fancybox-title-over">Image ' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';
              }
            });
          });
        </script>
EOT;

  endif; 
}

?>
