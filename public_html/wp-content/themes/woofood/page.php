<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WooFood
 */

get_header(); ?>

	<div class="main side-collapse-container" id="main">
		<div class="main-inner">
		<div class="container">
			<div class="<?php if (is_cart() || is_checkout() || is_wc_endpoint_url( 'order-received' ) ){echo 'col-md-12';} else { echo 'col-md-8 float-left'; }?>">

			<?php
			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/content', 'page' );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.
			?>
</div><!-- #container -->
		
<?php if (!is_cart() && !is_checkout() && !is_wc_endpoint_url( 'order-received' ) ): ?>

			<div class="col-md-4 float-left">
			<?php get_sidebar(); ?>

			</div>

<?php endif;?>
</div>
</div><!-- #main-inner -->
	</div><!-- #main -->

<?php
get_footer(); ?>
