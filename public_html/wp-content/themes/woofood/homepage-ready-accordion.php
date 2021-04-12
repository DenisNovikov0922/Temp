<?php
/**
* Template Name: WooFood Accordion Template Ready
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

<div class="main" id="main">
		<div class="main-inner">
    <div class="container">
			<div class="<?php if (is_cart()){echo 'col-md-12';} else { echo 'col-md-8 float-left'; }?>">

     <?php //do_shortcode('[woofood_menu]'); ?>
     <?php $attributes = array(); 

//$attributes["category"] = 'burger';
     ?>
             <?php echo do_shortcode('[woofood_accordion]'); ?>
             	<?php

             	while ( have_posts() ) : the_post();

				the_content();

			endwhile; // End of the loop.

			?>

</div><!-- div -->

<?php if (!is_cart()): ?>
			<div class="col-md-4 float-left">
			<?php get_sidebar(); ?>

			</div>
	
<?php endif;?>
</div><!--#container-->

<?php




?>

 </div><!-- #main-inner -->
  </div><!-- #main -->

<?php get_footer(); ?>