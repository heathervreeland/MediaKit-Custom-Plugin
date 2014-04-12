<?php

add_image_size( 'gallery_index_thumb', 215, 140, true );
add_image_size( 'gallery_carousel_thumb', 140, 60, true );

function insert_gallery_index() {

  // pull a new wp_query object based upon the Project Category
  $loop = new WP_Query( array( 'post_type' => 'networking-event', 'order' => 'ASC', 'orderby' => 'menu_order', 'posts_per_page' => '-1' ) );

  if ( $loop->have_posts() ) :

    $output = '<div class="gallery-index-wrapper clearfix">';

    $i = 1;

    while ( $loop->have_posts() ) : $loop->the_post();

      $third = '';
      $is_third = false;

      if (($i % 3) == 0) {
        $third = " third";
        $is_third = true;
      }

      $post_id = get_the_ID();

      $thumb = get_the_post_thumbnail( $post_id, 'gallery_index_thumb' );

      $title = get_the_title($post_id);

      $output .= '<div class="gallery-item' . $third . '">';
      $output .= '<div class="gallery-thumb"><a href="' . get_permalink() . '">' . $thumb . '</a></div>';
      $output .= '<div class="gallery-title-wrap"><div class="gallery-title"><a href="' . get_permalink() . '">' . $title . '</a></div></div>';
      $output .= '</div>'; 
      
      if ( $is_third ) {
        $output .= '<div class="gallery-row-break"></div>';
      }

      $i++;

    endwhile;

    $i = 0;

    $output .= '</div><!-- end gallery-index-wrapper -->'; 

    $photo_gallery_style = plugins_url('photogallery.css', __FILE__ );

    wp_enqueue_style( 'photo-gallery-style', $photo_gallery_style );

    return $output;

  endif;

}

function shortcode_gallery_index() {
  $output = insert_gallery_index();
  return $output;
}

add_shortcode( 'bkt_gallery_index', 'shortcode_gallery_index' );

function insert_bkt_gallery() {
  
  global $wp_query;
  $post_id = $wp_query->post->ID;

  // pull the attachments from the Networking Event.  These will be used to populate the Gallery  
  $attachments = get_posts( array( 'post_type' => 'attachment', 'numberposts' => '-1', 'post_parent' => $post_id, 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order'=> 'asc' ) );

  if ( $attachments ) { 

    // set up the necessary styles
    $output = '<style type="text/css" media="screen">' .
              '#mySlider {' .
              'width: 100%;' .
              'height: 600px;' .
              '}' .
              '</style>';

    // start the wrapper markup
    $output .= '<div id="mySlider" class="royalSlider default">' .
              '  <!-- Container of slides(images) -->' .
              '  <ul class="royalSlidesContainer">';

    // pull and generate the necessary gallery images
    foreach ( $attachments as $attachment ) { 

      $attachment_id = $attachment->ID;

      $gallery_image = wp_get_attachment_image( $attachment_id, 'full', true, false, false );
      $gallery_image_large = wp_get_attachment_url( $attachment_id, 'full', true, false, false );
      //$gallery_image_thumb = wp_get_attachment_thumb_url( $attachment_id );
      $gallery_image_thumb_array = wp_get_attachment_image_src( $attachment_id, 'gallery_carousel_thumb' );
      $gallery_image_thumb = $gallery_image_thumb_array[0];  

      $output .= '<li class="royalSlide" data-src="' . $gallery_image_large . '" data-thumb="' . $gallery_image_thumb . '">' .
                 $gallery_image . 
                 '</li>';

    }

    // end the wrapper markup
    $output .= '  </ul>' .
               '</div>';

    return $output;

  } 

}


add_action('wp_enqueue_scripts', 'show_public_javascript');

// generate javascript files in footer if we are looking at a Network Event item
function show_public_javascript() {

  global $post;

  if ( $post) {

    $css_rs_file = plugins_url('/js/gallery/royalslider.css', __FILE__ ) ;
    $css_default_file = plugins_url('/js/gallery/royalslider-skins/occasions/default.css', __FILE__ ) ;

    $script_easing_file = plugins_url('/js/gallery/jquery.easing.1.3.min.js', __FILE__ ) ;
    $script_rs_file = plugins_url('/js/gallery/royal-slider-8.1.min.js', __FILE__ ) ;
    $script_gallery_file = plugins_url('/js/gallery/gallery.js', __FILE__ ) ;

    // if we are looking at a Networking Event Custom Post type, then spit out these scripts
    if( $post->post_type == 'networking-event' ) {
      wp_enqueue_style( 'rs-style', $css_rs_file );
      wp_enqueue_style( 'rs-default-style', $css_default_file );

      wp_enqueue_script( 'rs-easing', $script_easing_file, array( 'jquery' ), '1.3.2', false );
      wp_enqueue_script( 'rs-script', $script_rs_file, array( 'jquery','rs-easing' ), '8.1', false );
      wp_enqueue_script( 'rs-gallery-script', $script_gallery_file, array( 'jquery','rs-easing','rs-script' ), '8.1', false );
    }

  }

}


?>
