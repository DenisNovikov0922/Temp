<?php 
?>
<div class="rm-popup-item dbfl has-custom-handler" id="rm-wc-cart-open" onclick="rmwc_open_cart_panel()" >    
    <?php echo __("Cart",'registrationmagic-gold')."&nbsp&nbsp&nbsp<span>".$data->cart_content_count."</span>"; ?>
</div>

<?php

if(property_exists($data, 'ajax_request'))
        return;
?>
<script>
jQuery(document).ready(function(){
    
});  

function rmwc_open_cart_panel(){
    jQuery(".rm-magic-popup").hide(200);
    jQuery(".rm-floating-page-top").text('<?php _e("Cart","registrationmagic-gold"); ?>');

        jQuery("#rm-panel-page").css('transform', 'translateX(0%)').show();

        jQuery(".rm-floating-page-content").children().hide();         
        jQuery("#rmwc-pu-cart-panel").show();
        jQuery("#rmwc-pu-cart-panel").children().show();
}

</script>