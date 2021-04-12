<?php

function rmw_modify_rm_tabs($tabs)
{
    $tabs = $tabs+array("really_unique_id" => (object) array('title'=>"really nice title", 'icon' => "<img src='".rm_wc()->images_url."woocommerce-logo.png'>"));
    return $tabs;
}

function rmw_modify_rm_contents($tabs, $user_id)
{
    $tab_content = '';
    $orders = rmwch_get_orders($user_id);
    $downloads = wc_get_customer_available_downloads($user_id);
    $customer_shipping_address = rmwch_get_formatted_shipping_name_and_address($user_id, "<br/>");
    $customer_billing_address = rmwch_get_formatted_billing_name_and_address($user_id, "<br/>");
    
    ob_start();
    require_once plugin_dir_path(__FILE__).'views/view_user_details.php'; 
    $tab_content = ob_get_clean();
    
    $tabs = $tabs + array("really_unique_id" => $tab_content);
    return $tabs;
}

function rm_wc_form_card_class($class,$rm_form_id)
{
    $wc_form_id= get_rm_wc_register_form();
    if($wc_form_id):
        if($wc_form_id == $rm_form_id)
            return $class.' rmwc-form-integrated';
    endif;
    
    return $class;
}

function rmw_add_total_revenue_to_userview($html, $uid)
{
    $total_spent = wc_price(wc_get_customer_total_spent($uid));
    $total_rev_text = RM_WC_UI_Strings::get('LABEL_TOTAL_REVENUE');
    ob_start();
    echo "<div class='rmwc-userpage-total-rev'>$total_rev_text $total_spent</div>";
    ?>
    <style>
        .rmwc-userpage-total-rev{
          display: inline-block;
          width: auto;
          font-size: 14px;
          text-align: center;
          color: #647a88;
          background-color: #e6ecf1;
          margin: 8px auto;
          padding: 4px 8px;
          border-radius: 2px;
        }
    </style>
    <?php
    $uvtr_html = ob_get_clean();
    
    return $html.$uvtr_html;
}

function rm_wc_extended_global_setting($html)
{
    $opt = '';
    ob_start();
    ?>
    <a href="admin.php?page=rm_wc_settings">
                <div class="rm-settings-box">
                    <img class="rm-settings-icon" src="<?php echo rm_wc()->images_url; ?>woo.png">
                    <div class="rm-settings-description">

                    </div>
                    <div class="rm-settings-subtitle"><?php echo RM_WC_UI_Strings::get("LABEL_RM_GLOBAL_SETTING_MENU"); ?></div>
                    <span><?php echo RM_WC_UI_Strings::get("SUBTITLE_RM_GLOBAL_SETTING_MENU"); ?></span>
                </div></a>
    <?php
    $opt = ob_get_clean();
    return $html.$opt;
}

function rm_wc_admin_menu() {
	do_action( 'rm_wc_admin_menu' );
}

function rm_wc_admin_enqueue() {
     wp_enqueue_style('rm_woocommerce', RM_WC()->includes_url.'admin/views/css/style.css',array(), RM_WC()->version, 'all');
}

function rmwc_handle_ajax_req()
{
    $req_type = $_POST['req_type'];
    $payload_data = $_POST['payload_data'];
    
    if($req_type == 'load_order_detail')
    {
        $order_id = $payload_data;
        $data =  rmwc_get_order_detail($order_id);
        require_once plugin_dir_path(__FILE__).'views/view_order_details.php'; 
        wp_die();
    }
    else
        die('coolol');
}

function rm_wc_setting_manage()
{
    $options = new RM_WC_Options;
    
    if(RM_PFBC_Form::isValid('options_rmwc'))
    {        
        $options->set_value_of('woo_registration_form',$_POST['rm_wc_reg_form_id']);
        
        if(isset($_POST['rm_wc_enable_cart_in_fab']))
            $options->set_value_of('woo_enable_cart_in_fab','yes');
        else
            $options->set_value_of('woo_enable_cart_in_fab','no');
        
        if(isset($_POST['rm_wc_enable_rm_role_override']))
            $options->set_value_of('woo_enable_rm_role_override','yes');
        else
            $options->set_value_of('woo_enable_rm_role_override','no');
        
        RM_Utilities::redirect(admin_url('/admin.php?page=rm_options_manage'));
    }
    else
    {
        $data = new stdClass;
        $service = new RM_Services;
        $data->rm_forms = array(''=>'Select a form')+RM_Utilities::get_forms_dropdown($service);
        $data->selected = $options->get_value_of('woo_registration_form');
        $data->enable_cart_in_fab = $options->get_value_of('woo_enable_cart_in_fab');
        $data->enable_rm_role_override = $options->get_value_of('woo_enable_rm_role_override');
        do_action('rm_pre_admin_template_render', "rmwc_global_settings");
        require_once plugin_dir_path(__FILE__).'views/view_settings.php';     
    }
}

function rmwc_get_order_detail($order_id)
{
    $_of = new WC_Order_Factory();
    $od = new stdClass;
    $order = $_of->get_order($order_id);
    //print_r(woocommerce_order_details_table($order_id));
    $order_types = wc_get_order_types();
    $order_statuses = wc_get_order_statuses();
    $order_date = $order->get_date_created();
    $order_date = $order_date->date_i18n('Y-m-d H:i:s');
    $od->edit_url = get_edit_post_link($order_id,'');// admin_url("post.php?post=".$order_id."&action=edit");
    $od->status = $order_statuses["wc-".$order->get_status()];
    $od->placed_on = $order_date;// $order->order_date;
    $od->order_notes = rmwc_get_order_notes($order_id);//$order->get_order_notes();
    $items = $order->get_items();
    //$_pf = new WC_Product_Factory();
    $products = array();
    foreach($items as $item)
    {
        $product = new stdClass;
        $product->id = $item['product_id'];
        
        //$prodo = $order->get_product_from_item($item);
       
        $product->cost = wc_price($order->get_item_subtotal($item));//$prodo->get_display_price();
        //if($product->is_downloadable() and $product->has_file())
        //    $customer_downloads += $product->get_files();

        $product->url = get_permalink( $item['product_id'] );
        $product->name = $item['name'];
        $product->qty = $item['qty'];
        $product->line_subtotal = wc_price($order->get_line_subtotal($item));//$item['line_subtotal'];
        $product->img = wp_get_attachment_image_src( get_post_thumbnail_id( $item['product_id'] ));
        
        $products[] = $product;
    }
    $od->products = $products;
    $od->subtotal = $order->get_subtotal_to_display();
    $od->discount = $order->get_discount_to_display();
    $od->shipping_cost = wc_price($order->get_total_shipping( ));
    $od->total = $order->get_formatted_order_total();
    $od->shipping_address = $order->get_formatted_shipping_address();
    $od->billing_address = $order->get_formatted_billing_address();
    $od->coupons = array();
    $coupons = $order->get_used_coupons();
    
    if(is_array($coupons))
      foreach($coupons as $coupon)
      {
          $co = new WC_Coupon($coupon);
          $od->coupons[$co->id] = $coupon;
      }
      
    return $od;
}

//Based on: http://kellenmace.com/get-woocommerce-order-notes/
function rmwc_get_order_notes( $order_id ) {
	remove_filter( 'comments_clauses', array( 'WC_Comments', 'exclude_order_comments' ) );
	$comments = get_comments( array(
		'post_id' => $order_id,
		'orderby' => 'comment_ID',
		'order'   => 'DESC',
		'approve' => 'approve',
		'type'    => 'order_note',
	) );
        
        $pluck_details = function($o){ return (object)array('content'=>$o->comment_content, 'author'=>$o->comment_author, 'date'=>$o->comment_date);};
        
	$notes = array_map($pluck_details, $comments);
	add_filter( 'comments_clauses', array( 'WC_Comments', 'exclude_order_comments' ) );
	return $notes;
}