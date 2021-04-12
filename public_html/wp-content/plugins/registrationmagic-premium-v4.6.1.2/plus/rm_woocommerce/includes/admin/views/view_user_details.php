<?php
// var_dump($downloads);

$loader_html = '<div id="rmwc-ajax-loader" style="height: 100px;text-align: center;padding-top: 40px;"><img src="'.RM_IMG_URL.'rm_f_ajax_loader_wide.gif'.'"><span style="display: block;">'.__("Loading",'registrationmagic-gold').'</span></div>';
?>

<!-- New love -->

<div class="rm-profile-fields" id="really_unique_id">
    <ul class="rm-woo-com-tab">
        <li class="rm-woo-com-tab-item"><a class="rm-wc-tab-elemet" href="#rmwc_user_view_orders"><?php echo RM_WC_UI_Strings::get('LABEL_ORDERS');?></a></li>
        <li class="rm-woo-com-tab-item"><a class="rm-wc-tab-elemet" href="#rmwc_user_view_downloads"><?php echo RM_WC_UI_Strings::get('LABEL_DOWNLOADS');?></a></li>
        <li class="rm-woo-com-tab-item"><a class="rm-wc-tab-elemet" href="#rmwc_user_view_addresses"><?php echo RM_WC_UI_Strings::get('LABEL_ADDRESSES');?></a></li>
    </ul>
    
<div id="rmwc_user_view_orders">
    <div id="rmwc_user_view_orders_table">
    <table class="user-content ui-tabs-panel ui-widget-content ui-corner-bottom" aria-labelledby="ui-id-2" role="tabpanel" aria-hidden="false" style="display: table;">
        <tbody>
            <tr>
                <th>&#35;</th>
                <th><?php echo RM_WC_UI_Strings::get('LABEL_ORDER_STATUS'); ?></th>
                <th><?php echo RM_WC_UI_Strings::get('LABEL_ITEMS'); ?></th>
                <th><?php echo RM_WC_UI_Strings::get('LABEL_SHIPPING_ADDRESS'); ?></th>
                <th><?php echo RM_WC_UI_Strings::get('LABEL_PLACED_ON'); ?></th>
                <th><?php echo RM_WC_UI_Strings::get('LABEL_AMOUNT'); ?></th>
                <th>&nbsp;</th>
            </tr>
            <?php 
            foreach($orders as $o)
                echo "<tr><td>$o->id</td><td>$o->status</td><td>$o->item_count</td><td>$o->shipping_address 1</td><td>$o->placed_on</td><td>$o->total</td><td onclick='rm_wc_load_order_detail($o->id)'><a href='javascript:void(0)'>".RM_WC_UI_Strings::get('LABEL_VIEW')."</a></td></tr>";
            
            
            ?>

        </tbody>
    </table>
    </div>
        
    <div id="id_prodigy" class="rm-woocommerce-order-wrapper" style="display:none">
        <!-- Order -->
        <?php echo $loader_html; ?>
        
    </div>
  
    
</div>

<div id="rmwc_user_view_downloads">
    <table class="user-content ui-tabs-panel ui-widget-content ui-corner-bottom" aria-labelledby="ui-id-2" role="tabpanel" aria-hidden="false" style="display: table;">
        <tbody>
            <tr>
                <th>&#35;</th>
                <th><?php echo RM_WC_UI_Strings::get('LABEL_NAME'); ?></th>
                <th><?php echo RM_WC_UI_Strings::get('LABEL_REMAINING_DOWNLOADS'); ?></th>
                <th><?php echo RM_WC_UI_Strings::get('LABEL_ACCESS_EXPIRES'); ?></th>
                <th>&nbsp;</th>
            </tr>
            <?php 
            $i =1;
            foreach($downloads as $dl)
            {
              
                $rem_dls = (strlen($dl['downloads_remaining']) === 0) ? RM_WC_UI_Strings::get('LABEL_REMAINING_DLS_UNLIMITED') : $dl['downloads_remaining'];                
                $acc_exp = (!$dl['access_expires']) ? RM_WC_UI_Strings::get('LABEL_ACCESS_EXPIRES_NEVER') : strtok($dl['access_expires'],' ');
                
                echo "<tr><td>$i</td><td>{$dl['download_name']}</td><td>{$rem_dls}</td><td>{$acc_exp}</td><td><a href='{$dl['file']['file']}'>".RM_WC_UI_Strings::get('LABEL_DOWNLOAD')."</a></td></tr>";
                $i++;
            }
            
            ?>

        </tbody>
    </table>
</div>

    <div id="rmwc_user_view_addresses">
    <table class="user-content ui-tabs-panel ui-widget-content ui-corner-bottom" aria-labelledby="ui-id-2" role="tabpanel" aria-hidden="false" style="display: table;">                
        <div class="rmwc-address-wrapper">
            <div class="rm-shipping-address inline">
                <h4>Billing Address</h4>
                <?php if($customer_billing_address == '') { ?>
                <p><?php echo RM_WC_UI_Strings::get('NOTICE_NO_BILLING_ADDRESS_USER'); ?></p>
                <?php } else { ?>
                <p><?php echo $customer_billing_address; ?></p> 
                 <?php } ?>
            </div>

            <div class="rm-shipping-address inline">
                <h4>Shipping Address</h4>
                <?php if($customer_shipping_address == '') { ?>
                <p><?php echo RM_WC_UI_Strings::get('NOTICE_NO_SHIPPING_ADDRESS_USER'); ?></p>
                <?php } else { ?>
                <p><?php echo $customer_shipping_address; ?></p> 
                 <?php } ?>
            </div>
        </div>
    </table>
    </div>    
    
</div>

<style>
    /*Woo-Commerce Tab*/

.rmagic .rm-woo-com-tab {
    clear: both;
    width: 100%;
    text-align: center;
    float: left;
    background: transparent;
    border: none;
    padding: 0;
}
.rmagic .rm-woo-com-tab .rm-woo-com-tab-item {
    display: inline-block;
    margin: 0px;
    border-top: 1px solid #EEE;
    margin-right:-8px;
}
.rmagic .rm-woo-com-tab .rm-woo-com-tab-item .rm-wc-tab-elemet {
    padding: 10px 15px;
    background: #FFF;
    border-top: 1px solid #EEE;
    border-bottom: 1px solid #EEE;
    color: #c4c4c4;
}
.rmagic .rm-woo-com-tab .rm-woo-com-tab-item.ui-state-active .rm-wc-tab-elemet,
.rmagic .rm-woo-com-tab .rm-woo-com-tab-item .rm-wc-tab-elemet:hover {
    background: #c4c4c4;
    color: #fff;
}
.rmagic .rm-woo-com-tab .rm-woo-com-tab-item:nth-child(1) .rm-wc-tab-elemet {
    border-radius: 5px 0px 0px 5px;
    border-left: 1px solid #EEE;
}
.rmagic .rm-woo-com-tab .rm-woo-com-tab-item:nth-last-child(1) .rm-wc-tab-elemet{
    border-radius: 0px 5px 5px 0px;
    border-right: 1px solid #EEE;
}
.rmagic  .rmwc-address-wrapper .rm-shipping-address.inline {
    display: inline-block;
    width: 50%;
    float: left;
}
.rmagic  .rmwc-address-wrapper:after {
    content: "";
    display: block;
    clear:both;
}
#rmwc_user_view_orders { position: relative; clear:both; padding: 0px;}
#id_prodigy {
    position: absolute;
    display: block;
    width: 100%;
    top: -50px;
    box-shadow: 0px 0px 15px 1px #999;
    border-radius: 5px;
    overflow: hidden;
    background: #fff;
}
#id_prodigy .rm-order-details {
        height: 420px;
    overflow-y: scroll;
}

/*jQ Fix*/
.rmagic .rm-woo-com-tab .ui-state-default{ width:auto;}

.ui-tabs .ui-tabs-nav li.rm-woo-com-tab-item {
    float: none
}

.rmagic .rm-user-area .rm-user-content .rm-woo-com-tab .ui-state-default {
    width: auto !important;
}
    
</style>


<script>

jQuery('document').ready(function(){
    //Anything you need to execute at startup, do it here, do it quickly.
    
    
    //    alert('from extension');
    jQuery("#really_unique_id").tabs();
    
    });

jQuery(document).mouseup(function (e) {
        var container = jQuery("#id_prodigy");
        if (!container.is(e.target) // if the target of the click isn't the container... 
                && container.has(e.target).length === 0) // ... nor a descendant of the container 
        {
            container.hide();
        }
    });

function rm_wc_load_order_detail(order_id)
{
    jQuery('#id_prodigy').html('<?php echo $loader_html; ?>');
    jQuery('#id_prodigy').show();
    jQuery('#id_prodigy').load(ajaxurl, {action: 'rmwc_req', req_type: 'load_order_detail', payload_data: order_id});
}
</script>

