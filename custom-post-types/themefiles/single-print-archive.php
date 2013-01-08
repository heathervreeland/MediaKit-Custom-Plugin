<?php
/**
 * The Template for displaying all single Print Archives.
 *
 * @package WordPress
 * @subpackage BKT 
 * @since BKT 2.1
 */

get_header(); ?>

		<div id="primary">
			<div id="content" role="main">

				<?php 
        while ( have_posts() ) : the_post();

          $post_id = get_the_ID();

          // get the published month
          $month = get_the_time( 'F', $post_id );

          // get the published year
          $year = get_the_time( 'Y', $post_id );

          // get the link 
          $print_archive_link = get_post_meta( $post_id, '_print_archive_link', TRUE );

        ?>

          <h1 class="print-archive-category-header"><?php echo $category; ?></h1>

          <div class="print-archive-content-wrap">

            <div class="print-archive-item-wrap">

              <div class="print-archive-date"><a href="<?php echo $print_archive_link; ?>" target="_blank"><?php echo $month . ' ' . $year;?></a></div>

              <div class="print-archive-thumbmail"><a href="<?php echo $print_archive_link; ?>" target="_blank"><?php echo get_the_post_thumbnail( $post_id, 'print-archive-thumb' ); ?></a></div>

            </div>

          </div>

        <?php
				endwhile; // end of the loop. 
        ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>
