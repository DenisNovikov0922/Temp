<?php
add_action( 'add_meta_boxes', 'woofood_add_product_availability_meta_box' );

function woofood_add_product_availability_meta_box( $post ) {
  add_meta_box(
'woofood_product_availability_box', // ID, should be a string.
esc_html__('Product Availability','woofood-plugin'), // Meta Box Title.
'woofood_product_availability_meta_box_callback', // Your call back function, this is where your form field will go.
'product', // The post type you want this to show up on, can be post, page, or custom post type.
'side', // The placement of your meta box, can be normal or side.
'core' // The priority in which this will be displayed.
);
}


function woofood_product_availability_meta_box_callback($post) {

  $product_availability =  get_post_meta( $post->ID, 'woofood_product_availability', true );

?>


 <input type="checkbox" name="woofood_product_availability" id="woofood_product_availability" value="1" <?php if (  $product_availability ) echo ' checked'; ?> /><?php echo esc_html__('Disable', 'woofood-plugin' ); ?><br />


  <?php
 
  ?>
  

  <?php esc_html_e('Checking Disable will make the product unvailable to purchased from customers.','woofood-plugin'); ?>





<?php }


add_action( 'save_post', 'woofood_save_product_availability' );
function woofood_save_product_availability( $post_id ) {
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
    return;
/*  if ( ( isset ( $_POST['my_awesome_nonce'] ) ) && ( ! wp_verify_nonce( $_POST['my_awesome_nonce'], plugin_basename( __FILE__ ) ) ) )
    return;*/
  if ( ( isset ( $_POST['post_type'] ) ) && ( 'page' == $_POST['post_type'] )  ) {
    if ( ! current_user_can( 'edit_page', $post_id ) ) {
      return;
    }    
  } else {
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
      return;
    }
  }

  if( isset( $_POST[ 'woofood_product_availability' ] ) ) {
    update_post_meta( $post_id, 'woofood_product_availability', true );
  } else {
    update_post_meta( $post_id, 'woofood_product_availability', false );
  }


}
//add metabox on product to select on which stores are available//


add_filter('woocommerce_is_purchasable', 'woofood_product_is_purchasable', 10, 2);
function woofood_product_is_purchasable($purchasable, $product) {
	global $woocommerce;
	  $product_disabled =  get_post_meta( $product->get_id(), 'woofood_product_availability', true );

	  if($product_disabled)
	  {
	  	return false;

	  }
	  else
	  {
	  		  	return true;


	  }
}
?>