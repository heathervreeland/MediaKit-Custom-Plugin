<?php
/**
 * The Template for displaying all single Networking Event posts.
 *
 * @package WordPress
 * @subpackage wfts 
 * @since wfts 3.0
 *
 */

get_header(); 

/*
  global $wp_query;
  global $post;

  echo '<pre>';
  print_r($wp_query);
  echo '</pre>';
*/
?>

			<section id="content" role="main" class="full-width">

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>


				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
          <header>
            <h1 class="entry-title"><?php the_title(); ?></h1>
          </header>

					<section class="entry-content">

						<?php the_content(); ?>

            <?php echo insert_bkt_gallery(); ?> 

					</section><!-- .entry-content -->


				</article><!-- #post-## -->


<?php endwhile; // end of the loop. ?> 

		</section><!-- #content -->

<?php get_footer(); ?>
