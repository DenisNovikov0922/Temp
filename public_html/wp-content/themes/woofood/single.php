<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WooFood
 */

get_header(); ?>

	<div class="main" id="main">
		<div class="main-inner">
		<div class="container">
			<div class="col-md-8 float-left">
			<div class="wrapper">

		<?php
		while ( have_posts() ) : the_post();

			get_template_part( 'template-parts/content', get_post_format() );

			the_post_navigation();

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>
		</div>
			</div><!-- #container -->
	
			<div class="col-md-4 float-left">
			<?php get_sidebar(); ?>

			</div>
			</div>
	</div><!-- #main-inner -->
	</div><!-- #main -->

<?php
get_footer();
