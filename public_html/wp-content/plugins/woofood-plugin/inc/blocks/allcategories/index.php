<?php
/**
 * BLOCK: WooFood Product Categories Accordion
 *
 * Gutenberg Custom Block assets.
 *
 * @since   1.0.2
 * @package WooFood
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( function_exists( 'register_block_type' ) ) 

  {


    function woofood_block_category( $categories, $post ) {
  return array_merge(
    $categories,
    array(
      array(
        'slug' => 'woofood',
        'title' => esc_html__( 'WooFood Blocks', 'woofood-plugin' ),
      ),
    )
  );
}
add_filter( 'block_categories', 'woofood_block_category', 10, 2);






      //All categories gutenberg block//
//require_once( get_template_directory() . '/inc/blocks/allcategories/index.php' );





// Hook: Editor assets.
add_action( 'enqueue_block_editor_assets', 'woofood_block_01_basic_editor_assets' );

/**
 * Enqueue the block's assets for the editor.
 *
 * `wp-blocks`: includes block type registration and related functions.
 * `wp-element`: includes the WordPress Element abstraction for describing the structure of your blocks.
 * `wp-i18n`: To internationalize the block's text.
 *
 * @since 1.0.0
 */
function woofood_block_01_basic_editor_assets() {
	  $woofood_plugin_rtl = woofood_plugin_is_rtl();

	// Scripts.
	wp_enqueue_script(
		'woofood-block-01-basic', // Handle.
		 WOOFOOD_PLUGIN_URL.'/inc/blocks/allcategories/block.js', // Block.js: We register the block here.
		array( 'wp-blocks', 'wp-i18n', 'wp-element' , 'wp-editor', 'wp-components', 'wp-api-fetch'), // Dependencies, defined above.
		filemtime( WOOFOOD_PLUGIN_DIR . '/inc/blocks/allcategories/block.js' ) // filemtime — Gets file modification time.
	);

	// Styles.
	wp_enqueue_style(
		'woofood-block-01-basic-editor', // Handle.
		WOOFOOD_PLUGIN_URL.'/inc/blocks/allcategories/editor'.$woofood_plugin_rtl.'.css', // Block editor CSS.
		array( 'wp-edit-blocks' ), // Dependency to include the CSS after it.
		filemtime( WOOFOOD_PLUGIN_DIR . '/inc/blocks/allcategories/editor'.$woofood_plugin_rtl.'.css' ) // filemtime — Gets file modification time.
	);
} // End function gb_block_01_basic_editor_assets().


// Hook: Frontend assets.
add_action( 'enqueue_block_assets', 'woofood_block_01_basic_block_assets' );

/**
 * Enqueue the block's assets for the frontend.
 *
 * @since 1.0.0
 */
function woofood_block_01_basic_block_assets() {
		  $woofood_plugin_rtl = woofood_plugin_is_rtl();

	// Styles.
	wp_enqueue_style(
		'woofood-block-01-basic-frontend', // Handle.
		WOOFOOD_PLUGIN_URL.'/inc/blocks/allcategories/style'.$woofood_plugin_rtl.'.css', // Block frontend CSS.
		array( ), // Dependency to include the CSS after it.
		filemtime( WOOFOOD_PLUGIN_DIR. '/inc/blocks/allcategories/editor'.$woofood_plugin_rtl.'.css' ) // filemtime — Gets file modification time.
	);
} // End function gb_block_01_basic_block_assets().




function woofood_accordion_categories_callback($attributes ) {
		ob_start();

	if($attributes["category_slug"] !="")
	{
			echo do_shortcode('[woofood_menu_category category_slug="'.$attributes["category_slug"].'"]');


	}
	else
	{
			echo do_shortcode('[woofood_menu]');

	}
	$total_export = ob_get_clean();	
	return  $total_export;
    


}

function woofood_accordion_categories_callback_admin() {
	//ob_start();
		echo do_shortcode('[woofood_menu]');

	wp_die();
    


}

add_action( 'wp_ajax_woofood_accordion_categories_callback_admin', 'woofood_accordion_categories_callback_admin' );
add_action( 'wp_ajax_nopriv_woofood_accordion_categories_callback_admin', 'woofood_accordion_categories_callback_admin' );



function wooofood_get_product_categories_rest_ajax()
{
	 $taxonomy     = 'product_cat';
  //$orderby      = '';  
  $show_count   = 0;      
  $pad_counts   = 0;      
  $hierarchical = 1;      
  $title        = '';  
  $empty        = 0;

	$args = array(
         'taxonomy'     => $taxonomy,
         //'orderby'      => $orderby,
         'show_count'   => $show_count,
         'pad_counts'   => $pad_counts,
         'hierarchical' => $hierarchical,
         'title_li'     => $title,
         'hide_empty'   => $empty
  );

     $all_categories = get_categories( $args );

	echo json_encode($all_categories);
	wp_die();

}
add_action( 'wp_ajax_wooofood_get_product_categories_rest_ajax', 'wooofood_get_product_categories_rest_ajax' );
add_action( 'wp_ajax_nopriv_wooofood_get_product_categories_rest_ajax', 'wooofood_get_product_categories_rest_ajax' );


function wooofood_get_products_rest_ajax()
{
	global $woocommerce;
// Get latest 3 products.
$args = array(
    'limit' => -1,
);
$products = wc_get_products( $args );
$products_export =array();
foreach($products as $product)
{	
	$products_export[]= $product->get_data();

}
	echo json_encode($products_export);
	wp_die();

}
add_action( 'wp_ajax_wooofood_get_products_rest_ajax', 'wooofood_get_products_rest_ajax' );
add_action( 'wp_ajax_nopriv_wooofood_get_products_rest_ajax', 'wooofood_get_products_rest_ajax' );


register_block_type( 'woofood/accordion', array(

		'attributes'      => array(
			'category_slug'    => array(
				'type'      => 'string',
				'default'   => '',
			)
			),

        'render_callback' => 'woofood_accordion_categories_callback',

) );


  }
  //CHECK IF GUTEN IS ENABLED//
