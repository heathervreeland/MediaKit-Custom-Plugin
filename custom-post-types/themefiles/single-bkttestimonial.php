<?php
/**
 * The Template for displaying all single testimonials.
 *
 * @package WordPress
 * @subpackage BKT 
 * @since BKT 2.1
 */

get_header(); ?>

		<div id="primary">
			<div id="content" role="main">

				<?php while ( have_posts() ) : the_post(); ?>

          <div id="bkt-testimonials">

<?php 
$post_id = $post->ID; 

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
?>

            <div id="bkt-testimonial-wrap">
              <div id="bkt-testimonials" class="horizontal-only">
                <div class="testimonial-container">
                  <div class="testimonial-slide">
                    <div class="testimonial-content-wrap">
<?php      if ( $testimonial_content != '' ) {?>
                      <div class="testimonial-content"><?php echo $testimonial_content; ?>
                        <div class="testimonial-author-wrap">
<?php      if ( $testimonial_author != '' ) {?>
                          <div class="testimonial-author">&mdash;<?php echo $testimonial_author; ?></div>
<?php } ?>
<?php      if ( $testimonial_company != '' ) {?>
                          <div class="testimonial-company"><?php echo $testimonial_company; ?></div>
<?php } ?>
                      </div><!-- testimonial-content -->
<?php } ?>
                    </div><!-- testimonial-content-wrap -->
                    <div class="testimonial-thumb-wrap">
<?php if ( $testimonial_thumbnail_featured != '' ) { ?>
                      <div class="testimonial-thumbnail-featured"><?php echo $testimonial_thumbnail_featured; ?> </div>
<?php } ?>
<?php if ( $testimonial_thumbnail_full != '' ) { ?>

                      <div class="testimonial-thumbnail"><?php echo $testimonial_thumbnail_full; ?></div>

<?php } ?>
                    </div><!-- end .testimonial-thumb-wrap -->

                  </div><!-- end .testimonial-slide -->
                </div><!-- end .testimoniali-container -->
              </div><!-- end #bkt-testimonials -->
            </div><!-- end #bkt-testimonial-wrap -->





				<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>
