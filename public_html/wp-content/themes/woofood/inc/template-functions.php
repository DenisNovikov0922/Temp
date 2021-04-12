<?php
/**
 * Additional features to allow styling of the templates
 *
 * @package WooFood
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */

/*remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
remove_action('woocommerce_shop_loop_item_title','woocommerce_template_loop_product_title',10);
add_action('woocommerce_shop_loop_item_title','woocommerce_template_loop_product_title',10);
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );*/



register_sidebar( array(
'name' => 'Footer  1',
'id' => 'footer-1',
'description' => 'Appears in the footer area',
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => '</aside>',
'before_title' => '<h4>',
'after_title' => '</h4>',
) );
register_sidebar( array(
'name' => 'Footer  2',
'id' => 'footer-2',
'description' => 'Appears in the footer area',
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => '</aside>',
'before_title' => '<h4>',
'after_title' => '</h4>',
) );
register_sidebar( array(
'name' => 'Footer  3',
'id' => 'footer-3',
'description' => 'Appears in the footer area',
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => '</aside>',
'before_title' => '<h4>',
'after_title' => '</h4>',
) );

register_sidebar( array(
'name' => 'Footer  4',
'id' => 'footer-4',
'description' => 'Appears in the footer area',
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => '</aside>',
'before_title' => '<h4>',
'after_title' => '</h4>',
) );

function woofood_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	return $classes;
}
add_filter( 'body_class', 'woofood_body_classes' );

add_action( 'after_setup_theme', 'register_wpslash_foodmaster_topbar_menu' );
function register_wpslash_foodmaster_topbar_menu() {
  register_nav_menu( 'topbar', esc_html__( 'Top Bar', 'woofoood' ) );
}