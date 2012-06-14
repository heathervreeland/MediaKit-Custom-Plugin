<?php
/**
 * The Template for displaying all single testimonials.
 *
 * @package WordPress
 * @subpackage Solamar
 * @since Solamar 2.1
 */

get_header(); ?>

		<div id="primary">
			<div id="content" role="main">

				<?php while ( have_posts() ) : the_post(); ?>

          <div id="solamar-testimonials">

<?php 
$post_id = $post->ID; 

// get the content
$testimonial_content = wpautop(get_the_content());

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

            <div class="testimonial-wrapper">
              <div class="testimonial-top"></div>
              <dl class="testimonial-slide">

<?php if ( $testimonial_thumbnail_full != '' ) { ?>

                <dd class="testimonial-thumbnail"><?php echo $testimonial_thumbnail_full; ?>
                  <br /><span class="testimonial-thumbnail-title"><?php echo $testimonial_thumbnail_title; ?></span>
                </dd>

<?php } ?>

<?php      if ( $testimonial_headline != '' ) { ?>
                <dd class="testimonial-headline"><?php echo $testimonial_headline; ?></dd>
<?php } ?>

<?php      if ( $testimonial_content != '' ) {?>
                <dd class="testimonial-content"><?php echo $testimonial_content; ?></dd>
<?php } ?>

<?php      if ( $testimonial_author != '' ) {?>
                <dd class="testimonial-author">&mdash;<?php echo $testimonial_author; ?></dd>
<?php } ?>

<?php      if ( $testimonial_title != '' ) {?>
                <dd class="testimonial-title"><?php echo $testimonial_title; ?></dd>
<?php } ?>

<?php      if ( $testimonial_company != '' ) {?>
                <dd class="testimonial-company"><?php echo $testimonial_company; ?></dd>
<?php } ?>

<?php      if ( $testimonial_url != '' ) {?>
                <dd class="testimonial-url"><?php echo $testimonial_url; ?></dd>
<?php } ?>

              </dl>
              <div class="testimonial-bottom"></div>
            </div><!-- end .testimonial-wrapper -->

          </div><!-- end #solamar-testimonials -->


				<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_sidebar('blog'); ?>
<?php get_footer(); ?>
