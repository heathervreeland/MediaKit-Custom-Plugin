<?php

/* 
 * Inserts the latest posts
 **************************************/

function insert_latest_posts( $num=0 ) {

  // create a variable for later use
  $num_posts;

  // check to see if we've been passed a variable $num
  if ( $num != 0) {

    $num_posts = $num;

  // if $num has not been set then let's default to 3 posts
  } else {

    $num_posts = 3;

  }

  //global $post;

  $myposts = get_posts(array('post_type' => 'post', 'numberposts' => $num_posts, 'offset' => 0,'post_status'=>'publish'));

  foreach($myposts as $apost) {

    // grabbing the post ID
    $post_id = $apost->ID;

    // let's get the date and format it
    $post_date = date( 'F d, Y', strtotime( $apost->post_date ));

    // start the html wrap
    $output .=	'<div class="recent-post cf">';
    $output .=	'  <div class="recent-post-image">';

    // output a thumbnail 
    if(has_post_thumbnail( $post_id )) {

      $output .=	'    <a href="' . get_permalink( $post_id ) . '">';
      $output .=	get_the_post_thumbnail( $post_id, "medium" );
      $output .=	'    </a>';

    } else { 

      $output .=	'    <img src="' . get_bloginfo('stylesheet_directory') . '/images/occasions-magazine-seal.png" alt="Occasions">';

    }

    $output .=	'  </div>';
    $output .=	'  <div class="recent-post-content">';
    $output .=	'    <h2><a href="' . get_permalink( $post_id ) . '">' .  get_the_title( $post_id ) . '</a></h2>';
    $output .=	'    <p class="recent-post-date">' . $post_date . '</p>';
    $output .=	get_excerpt_by_id( $post_id ); 
    $output .=	'  </div><!-- end recent-post-content -->';
    $output .=	'</div>';
  }

  return $output;

}
?>
