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

			<div class="col-md-8 float-left">
			<div class="wrapper">
			<?php woocommerce_content(); ?>
			</div>
	</div><!-- #container -->
		
<?php if (!is_cart() && !is_checkout() && !is_wc_endpoint_url( 'order-received' ) ): ?>

			<div class="col-md-4 float-left">
			<?php get_sidebar(); ?>

			</div>
	
<?php endif;?>
	</div>

	</div>
</div>

<?php
get_footer(); ?>
