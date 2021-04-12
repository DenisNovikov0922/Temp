<?php
$woofood_options = get_option('woofood_options');
$woofood_enable_ajax_option = isset($woofood_options['woofood_enable_ajax_option']) ? $woofood_options['woofood_enable_ajax_option'] : null;
if ($woofood_enable_ajax_option) 
{





function wf_hidden_inputs_simple()
{
global $post, $product;

if ($product->is_type("simple"))
{
echo "<input type='hidden' name='add-to-cart' value='".$post->ID."'/>";
echo "<input type='hidden' name='product_id' value='".$post->ID."'/>";



}

echo "<input type='hidden' id='ajax_loading_text' name='ajax_loading_text' value='".esc_html__('Please wait..', 'woofood-plugin')."'/>";




}




add_action('woocommerce_before_add_to_cart_button', 'wf_hidden_inputs_simple');

function wf_quickview_scripts() {
$woofood_plugin_rtl = woofood_plugin_is_rtl();
wp_enqueue_style( 'wf_quickview_css', WOOFOOD_PLUGIN_URL. 'css/wf_ajax_quickview'.$woofood_plugin_rtl.'.css', array(), WOOFOOD_PLUGIN_VERSION, 'all' );

wp_enqueue_script( 'wf-ajax-quickview-script', WOOFOOD_PLUGIN_URL. 'js/wf_quickview.js', array( 'jquery' ), WOOFOOD_PLUGIN_VERSION, 'all' );

wp_localize_script('wf-ajax-quickview-script', 'wfquickajax', array( 
'ajaxurl' => admin_url( 'admin-ajax.php' ),
'ajax_nonce' => wp_create_nonce('wpslash_woofood_plugin_nonce'),

));

}
add_action( 'wp_footer', 'wf_quickview_scripts' );

function wf_quickview_ajax(){

    //check_ajax_referer('wpslash_woofood_plugin_nonce', 'security');

global $woocommerce;
$woofood_options = get_option('woofood_options');
$woofood_enable_hide_images = $woofood_options['woofood_enable_hide_images'];
$woofood_enable_upsell_products = $woofood_options['woofood_enable_ajax_upsell_option'];
$woofood_enable_related_products = $woofood_options['woofood_enable_ajax_related_option'];

$product_id = (int) $_POST['product_id'];
$params = array('p' => $product_id,
'post_type' => array('product'));
$query = new WP_Query($params);
if($query->have_posts()){
while ($query->have_posts()){
$query->the_post();

$product  = wc_get_product($product_id);
    //add_action( 'woofood_quickview_product_content', 'woocommerce_product_thumbnails', 5 );

    add_action( 'woofood_quickview_product_content', 'woocommerce_template_single_rating', 10 );
    add_action( 'woofood_quickview_product_content', 'woocommerce_template_single_price', 15 );
    add_action( 'woofood_quickview_product_content', 'woocommerce_template_single_excerpt', 20 );
    add_action( 'woofood_quickview_product_content', 'woocommerce_template_single_add_to_cart', 25 );
    if($woofood_enable_upsell_products)
    {

    add_action( 'woofood_quickview_product_content', 'wf_quickview_upsell_products', 26 );
   
    }
    if($woofood_enable_related_products)
    {

    add_action( 'woofood_quickview_product_content', 'wf_quickview_related_products', 27 );
   
    }




    //add_action( 'woofood_quickview_product_content', 'woocommerce_template_single_meta', 30 );
   // add_action( 'woofood_quickview_product_footer', 'woocommerce_template_single_add_to_cart', 5 );
   // add_action( 'woofood_quickview_product_content', 'woofood_tm_quickview_compatibility', 35 );


     

    ?>
     <header class="modal__header ccc">
          <h2 class="modal__title" id="modal-1-title">
          <?php echo get_the_title(); ?>
          </h2>
          <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
        </header>
<div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">

        <main class="modal__content" id="modal-1-content">

  <script type="text/javascript" src="<?php echo  $woocommerce->plugin_url() . '/assets/js/prettyPhoto/jquery.prettyPhoto.min.js'; ?>"></script>

  <script type="text/javascript" src="<?php echo  $woocommerce->plugin_url() . '/assets/js/frontend/add-to-cart-variation.js'; ?>"></script>


  <?php if ($woofood_enable_hide_images) : ?>

    <?php
     do_action( 'woofood_quickview_product_content' ); 
    ?>

  <?php else : ?>


<?php 
  $attachment_ids = $product->get_gallery_image_ids();
  $i=0; 
  $j=1;
  if ($attachment_ids) {
  
?>
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" data-interval="false">
      
        <!-- <div class="carousel-item active">
          <?php //the_post_thumbnail( apply_filters('woofood_quickview_image_size','woofood-quickview'));  ?>
        </div> -->


      <ol class="carousel-indicators">
        <?php 

          foreach( $attachment_ids as $attachment_id ) {
            if($i==0){
              ?>
                    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active">
                      <?php
                      // Display Image instead of URL
                        echo wp_get_attachment_image($attachment_id, 'full');
                      ?>
                    </li>
                      
                    
                    <?php
            }else{
              ?>
                    <li data-target="#carouselExampleIndicators" data-slide-to="<?php echo $i ?>">
                      <?php
                      // Display Image instead of URL
                        echo wp_get_attachment_image($attachment_id, 'full');
                      ?>
                    </li>
                    <?php
            }
            $i++;
                    
            }

        ?>
        <!-- <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li> -->
      </ol>
      <div class="column-50-wf image-column">
      <div class="carousel-inner">
<?php
foreach( $attachment_ids as $attachment_id ) {
  if($j==1){
    ?>
          <div class="carousel-item active">
            <?php
            // Display Image instead of URL
            echo wp_get_attachment_image($attachment_id, 'full');
            ?>
          </div>
          <?php
  }else{
    ?>
          <div class="carousel-item">
            <?php
            // Display Image instead of URL
            echo wp_get_attachment_image($attachment_id, 'full');
            ?>
          </div>
          <?php
  }
  $j++;
          
  }
?>


        <?php //do_action( 'woocommerce_product_thumbnails' ); ?>
      </div>
      <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Indietro</span>
      </a>
      <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Avanti</span>
      </a>

    </div></div>
  <?php } else{


?>
<div class="column-50-wf image-column"><?php the_post_thumbnail( apply_filters('woofood_quickview_image_size','woofood-quickview'));  ?></div>
<?php

  } ?>
    



    <div class="column-50-wf image-column"><?php //the_post_thumbnail( apply_filters('woofood_quickview_image_size','woofood-quickview'));  ?></div>
    <div id="moreinfo-thumbnails"><?php // do_action( 'woocommerce_product_thumbnails' ); ?></div>
    <div class="column-50-wf">
    
      <?php



    ?>
                    <?php do_action( 'woofood_quickview_product_content' );


                    ?>



      
      </div>

    <?php

    ?>


  <?php endif; ?>

</main>


</div>
<footer class="modal__footer">

        </footer>

<?php

}
}
wp_reset_postdata();
die();
}//end function

  function woofood_quickview_tm_compatibility($product_id)
  {
    /*do_action("woocommerce_tm_epo",$product_id);
    do_action( 'woocommerce_tm_epo_enqueue_scripts');*/

  }



function wf_quickview_upsell_products()
{
  global $product;
  $upsell_product_ids = $product->get_upsell_ids(  );
//print_r($upsell_product_ids);
$attributes = array();
$attributes["ids"] = implode(",", $upsell_product_ids);
if(!empty($upsell_product_ids))
{
  ?>
  <div class="wf_quickview_upsell_products">
      <div class="wf_quickview_upsell_products_header">
        <?php esc_html_e("You may also like", "woofood-plugin"); ?>
      </div>
      <div class="wf_quickview_upsell_products_content">

  <?php woofood_products($attributes); ?>
</div>
  </div>
  <?php

}
}




function wf_quickview_category_ajax(){

$woofood_options = get_option('woofood_options');
$woofood_enable_hide_images = $woofood_options['woofood_enable_hide_images'];

global $woocommerce;
$category_slug = $_POST['category_slug'];
$term = get_term_by('slug', $category_slug , 'product_cat')


?>
<div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
        
        <main class="modal__content" id="modal-1-content">
<header class="modal__header">
          <h2 class="modal__title" id="modal-1-title">
            <?php echo $term->name; ?>
          </h2>
          <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
        </header>


<script type="text/javascript" src="<?php echo  $woocommerce->plugin_url() . '/assets/js/prettyPhoto/jquery.prettyPhoto.min.js'; ?>"></script>

<script type="text/javascript" src="<?php echo  $woocommerce->plugin_url() . '/assets/js/frontend/add-to-cart-variation.js'; ?>"></script>
<?php echo do_shortcode('[product_category category="'.$category_slug.'" per_page="-1"]'); ?>




</main>
</div>


<?php


die();
}//end function

add_filter( 'wc_add_to_cart_message_html', '__return_null' );


// Remove product in the cart using ajax
function wf_ajax_product_remove()
{
// Get mini cart
ob_start();

foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item)
{
if($cart_item['product_id'] == $_POST['product_id'] && $cart_item_key == $_POST['cart_item_key'] )
{
WC()->cart->remove_cart_item($cart_item_key);

}  
}



woocommerce_mini_cart();

$mini_cart = ob_get_clean();

// Fragments and mini cart are returned
$data = array(
'fragments' => apply_filters( 'woocommerce_add_to_cart_fragments', array(
'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>'
)
),
'cart_hash' => apply_filters( 'woocommerce_add_to_cart_hash', WC()->cart->get_cart_for_session() ? md5( json_encode( WC()->cart->get_cart_for_session() ) ) : '', WC()->cart->get_cart_for_session() )
);

wp_send_json( $data );









die();
}

add_action( 'wp_ajax_product_remove', 'wf_ajax_product_remove' );
add_action( 'wp_ajax_nopriv_product_remove', 'wf_ajax_product_remove' );

add_action('wp_ajax_woofood_quickview_ajax','wf_quickview_ajax');
add_action('wp_ajax_nopriv_woofood_quickview_ajax','wf_quickview_ajax');

//for category
add_action('wp_ajax_woofood_quickview_category_ajax','wf_quickview_category_ajax');
add_action('wp_ajax_nopriv_woofood_quickview_category_ajax','wf_quickview_category_ajax');


add_action('wp_footer', 'woofood_ajax_add_to_cart_script'); 

  function woofood_ajax_add_to_cart_script()
  {
    wp_enqueue_script( 'woofood-ajax-add-to-cart', WOOFOOD_PLUGIN_URL . 'js/ajax_add_to_cart.js', array('jquery'), WOOFOOD_PLUGIN_VERSION, 'all' )
    ?>

<?php
  }

function wf_quickview_dialog(){
global $woocommerce;
echo '<div class="modal micromodal-slide wf_product_view" id="product_view" aria-hidden="true" >
    <div class="modal__overlay" tabindex="-1" data-micromodal-close>
      
          <div class="content">
</div>


'.wp_enqueue_script( 'wc-add-to-cart-variation', $woocommerce->plugin_url() . '/assets/js/frontend/add-to-cart-variation.js', array(), null, 'all' ).'  
        
      
  </div>
  </div>

<div class="wf_quickview_loading">Loading&#8230;</div>

';

?>


<?php


wp_enqueue_script( 'wf-delete-cart-ajax', WOOFOOD_PLUGIN_URL . 'js/wf_delete_cart.js', array( 'jquery' ), null, true );

wp_localize_script('wf-delete-cart-ajax', 'wfdeletecartajax', array( 
'ajaxurl' => admin_url( 'admin-ajax.php' ),
));
}


add_action('wp_footer', 'wf_quickview_dialog'); 



function wf_quickview_category_dialog(){
global $woocommerce;
echo '<div class="modal micromodal-slide wf_category_view" id="category_view" aria-hidden="true" >
    <div class="modal__overlay" tabindex="-1" data-micromodal-close>
      
          <div class="content">
</div>
'.wp_enqueue_script( 'wc-add-to-cart-variation', $woocommerce->plugin_url() . '/assets/js/frontend/add-to-cart-variation.js', array(), null, 'all' ).'  
       
        <footer class="modal__footer">
         
        </footer>
  </div>
  </div>

<div class="wf_quickview_loading">Loading&#8230;</div>

';
?>

<?php


wp_enqueue_script( 'wf-delete-cart-ajax', WOOFOOD_PLUGIN_URL . 'js/wf_delete_cart.js', array( 'jquery' ), null, true );

wp_localize_script('wf-delete-cart-ajax', 'wfdeletecartajax', array( 
'ajaxurl' => admin_url( 'admin-ajax.php' ),
));
}


add_action('wp_footer', 'wf_quickview_category_dialog'); 




add_filter( 'woocommerce_loop_add_to_cart_link', 'woofood_replace_add_to_cart_with_quickview', 10, 2 );

function woofood_replace_add_to_cart_with_quickview( $html, $product ) {

    if ( method_exists( $product, 'get_id' ) ) {
        $product_id = $product->get_id();
    } else {
        $product_id = $product->id;
    }

  
        // Set HERE your button link
        $link = get_permalink($product_id);
        $html = '<a rel="nofollow" qv-id = "'.$product_id.'" class="woocommerce-LoopProduct-link woofood-quickview-button button">'.esc_html__('Select', 'woofood-plugin').'</a>';
    
    return $html;
}


  remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
  add_action( 'woocommerce_before_shop_loop_item', 'woofood_replace_loop_title_link', 10 );
    

function woofood_replace_loop_title_link() {
    global $product;

    // HERE BELOW, Define the Link to be replaced
   if ( method_exists( $product, 'get_id' ) ) {
        $product_id = $product->get_id();
    } else {
        $product_id = $product->id;
    }

    echo '<a rel="nofollow" qv-id = "'.$product_id.'" class="woocommerce-LoopProduct-link woofood-quickview-button">';
}


//remove and replace links from cart to ajax..
add_filter('woocommerce_cart_item_permalink','__return_false');

add_filter( 'woocommerce_cart_item_name', 'wf_remove_cart_product_link', 1, 3 );
function wf_remove_cart_product_link( $product_link, $cart_item, $cart_item_key ) {
$product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
$variation_string ="";
foreach($cart_item['variation'] as $key=> $curr_var){
$meta = get_post_meta($cart_item['variation_id'], $key, true);
$term = get_term_by('slug', $curr_var, str_replace("attribute_","",$key));
if($term)
{
  $variation_string .= " - ". $term->name;

}
  else
  {
      $variation_string .= " - ". $curr_var;

  }

}


return "<a>".$product->get_title().$variation_string."";
}




function woofood_tm_support_css()
{?>
<style>
.fl-overlay
{
  display:none!important;
}
</style>
<?php

}






}//end if ajax is enabled


add_action('wp_footer','woofood_no_ajax_script_added_to_cart');
function woofood_no_ajax_script_added_to_cart(){

        ?>
            <script type="text/javascript">
            var wf_variation_id = 0;
            var wf_variation_extra_options = [];

            var wf_product_added_message = "<?php trim(esc_html_e('Product has been succesfully added to cart', 'woofood-plugin')); ?>";
            var wf_required_fields_not_completed_message = "<?php trim(esc_html_e('Please select or fill all the required fields', 'woofood-plugin')); ?>";
            var wf_product_removed_message = "<?php trim(esc_html_e('Product has been removed from cart', 'woofood-plugin')); ?>";
            var wf_minimum_options_required = "<?php trim(esc_html_e('Please select at least %%options%% %%option_text%%', 'woofood-plugin')); ?>";
            var wf_option_text = "<?php trim(esc_html_e('option', 'woofood-plugin')); ?>";
            var wf_options_text = "<?php trim(esc_html_e('options', 'woofood-plugin')); ?>";

            </script>
        <?php
}

function wf_quickview_related_products()
{
  global $product;
  $related_product_ids = wc_get_related_products($product->get_id());
//print_r($upsell_product_ids);
$attributes = array();
$attributes["ids"] = implode(",", $related_product_ids);
if(!empty($related_product_ids))
{
  ?>
  <div class="wf_quickview_upsell_products">
      <div class="wf_quickview_upsell_products_header">
        <?php esc_html_e("Related Products", "woofood-plugin"); ?>
      </div>
      <div class="wf_quickview_upsell_products_content">

  <?php woofood_products($attributes); ?>
</div>
  </div>
  <?php

}
}




?>