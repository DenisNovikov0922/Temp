<?php
add_action('wp_dashboard_setup', 'my_custom_dashboard_widgets');
  
function my_custom_dashboard_widgets() {
global $wp_meta_boxes;
 
wp_add_dashboard_widget('custom_help_widget', 'Primi passi', 'custom_dashboard_help');
}
 
function custom_dashboard_help() {
    $per=0;
    $first='';
    if (class_exists( 'WooCommerce' ) ) {
    	$check_woo = get_option('woocommerce_onboarding_profile');
    	$check_woo = $check_woo['completed'];
    	
    	if($check_woo == 1){
        	$per=25;
         	$first='checked';
        
   

    $check_ids = get_option('initial_products_init');
    $check_ids = unserialize($check_ids);
    $fb_check = get_option('wc_facebook_page_id');
    
    $all_ids = get_posts( array(
  		'post_type' => 'product',
  		'numberposts' => -1,
  		'post_status' => 'publish',
  		'fields' => 'ids',
  	));
  	$sec='';
    if(!empty($check_ids)){
  	$result = array_intersect($check_ids,$all_ids);
  	 $check_newdel = get_option('new_pro_del_del');
		if($check_newdel == 'true'){
			$per=$per+25;
			 $sec='checked';
		}
  }
	
  if(!empty($check_ids)){
	$check_new = array_diff($all_ids, $check_ids);
	$th='';
  $check_new_prod = get_option('new_pro_added_add');
    if($check_new_prod == 'true'){
		$per=$per+25;
		 $th='checked';
	}
  }

	$four='';
	if($fb_check != ''){
		$per=$per+25;
		 $four='checked';
	}
}
}

$first = ($first != '') ? $first : '';
$sec = ($sec != '') ? $sec : '';
$th = ($th != '') ? $th : '';
$four = ($four != '') ? $four : '';

echo '<div><p><h2>Configura Easy Delivery:</h2><br><a href="/wp-admin/admin.php?page=wc-settings" class="superduper">Configura le informazioni di base</a><br><a href="/wp-admin/edit.php?post_type=product">Elimina i prodotti demo</a><br><a href="/wp-admin/post-new.php?post_type=product">Aggiungi un nuovo prodotto</a><br><a href="/wp-admin/admin.php?page=wc-facebook">Connetti Facebook & Instagram</a></p></div><div class="wrapper progress-bar-task">
  		<div class="progress-bar">
    		<div class="bar" data-size="'.$per.'">
      			<span class="perc">'.$per.'%</span>
    		</div>
  		</div>
  		<label class="container">Configura le informazioni di base
  				<input type="checkbox" '.$first.'>
  				<span class="checkmark"></span>
			</label>
			<label class="container">Elimina i prodotti demo
  				<input type="checkbox" '.$sec.'>
  				<span class="checkmark"></span>
			</label>
			<label class="container">Aggiungi un nuovo prodotto
  				<input type="checkbox" '.$th .'>
  				<span class="checkmark"></span>
			</label>
			<label class="container">Connetti Facebook & Instagram
  				<input type="checkbox" '.$four.'>
  				<span class="checkmark"></span>
			</label>
	</div>';
}


add_action('admin_init','set_default_prouct_ids');
function set_default_prouct_ids(){
	$args = array(
		'post_type' => 'prodduct'
	);
	if (class_exists( 'WooCommerce' ) ) {

		$all_ids = get_posts( array(
  			'post_type' => 'product',
  			'numberposts' => -1,
  			'post_status' => 'publish',
  			'fields' => 'ids',
  		));


		if(!empty($all_ids)){

			$ser_ids = serialize($all_ids);

			$initialize = get_option('intialize_true_true');

			if($initialize != 'true'){
			
				update_option('initial_products_init',$ser_ids);
				update_option('intialize_true_true','true');
			}
		}
	}

}

add_action('save_post_product', 'mp_sync_on_product_save', 10, 3);
function mp_sync_on_product_save( $post_id, $post, $update ) {
	if($post->post_status=='publish'){
       update_option('new_pro_added_add','true');
	}
	if($post->post_status=='trash'){
		 update_option('new_pro_del_del','true');
	}
}