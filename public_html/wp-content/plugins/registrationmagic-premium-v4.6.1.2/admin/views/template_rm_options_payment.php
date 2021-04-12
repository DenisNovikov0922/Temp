<?php
if (!defined('WPINC')) {
    die('Closed');
}

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


//$data [] = 
$curr_arr = array('USD' => __("US Dollars",'registrationmagic-gold'),
    'EUR' => __("Euros",'registrationmagic-gold'),
    'GBP' => __("Pounds Sterling",'registrationmagic-gold'),
    'AUD' => __("Australian Dollars",'registrationmagic-gold'),
    'BRL' => __("Brazilian Real",'registrationmagic-gold'),
    'CAD' => __("Canadian Dollars",'registrationmagic-gold'),
    'CZK' => __("Czech Koruna",'registrationmagic-gold'),
    'DKK' => __("Danish Krone",'registrationmagic-gold'),
    'HKD' => __("Hong Kong Dollar",'registrationmagic-gold'),
    'HUF' => __("Hungarian Forint",'registrationmagic-gold'),
    'ILS' => __("Israeli Shekel",'registrationmagic-gold'),
    'JPY' => __("Japanese Yen",'registrationmagic-gold'),
    'MYR' => __("Malaysian Ringgits",'registrationmagic-gold'),
    'MXN' => __("Mexican Peso",'registrationmagic-gold'),
    'NZD' => __("New Zealand Dollar",'registrationmagic-gold'),
    'NOK' => __("Norwegian Krone",'registrationmagic-gold'),
    'PHP' => __("Philippine Pesos",'registrationmagic-gold'),
    'PLN' => __("Polish Zloty",'registrationmagic-gold'),
    'SGD' => __("Singapore Dollar",'registrationmagic-gold'),
    'SEK' => __("Swedish Krona",'registrationmagic-gold'),
    'CHF' => __("Swiss Franc",'registrationmagic-gold'),
    'TWD' => __("Taiwan New Dollars",'registrationmagic-gold'),
    'THB' => __("Thai Baht",'registrationmagic-gold'),
    'INR' => __("Indian Rupee",'registrationmagic-gold'),
    'TRY' => __("Turkish Lira",'registrationmagic-gold'),
    'RIAL' => __("Iranian Rial",'registrationmagic-gold'),
    'RUB' => __("Russian Rubles",'registrationmagic-gold'),
    'NGN' => __("Nigerian Naira",'registrationmagic-gold'),
    'ZAR' => __("South African Rand",'registrationmagic-gold'),
    'ZMW' => __("Zambian Kwacha",'registrationmagic-gold'),
    'GHS' => __("Ghanaian cedi",'registrationmagic-gold')
    );
    

$ssl_available = RM_Utilities::is_ssl();
?>

<div class="rmagic">

    <!--Dialogue Box Starts-->
    <div class="rmcontent">


        <?php
        $gopts = new RM_Options;
        $include_stripe= $gopts->get_value_of('include_stripe');
//PFBC form
        $form = new RM_PFBC_Form("options_payment");
        $form->configure(array(
            "prevent" => array("bootstrap", "jQuery"),
            "action" => ""
        ));

        $form->addElement(new Element_HTML('<div class="rmheader">' . RM_UI_Strings::get('GLOBAL_SETTINGS_PAYMENT')));
        $form->addElement(new Element_HTML('<div class="rm_payment_guide"><a target="_blank" href="https://registrationmagic.com/setup-payments-on-registrationmagic-form-using-products/"><span class="dashicons dashicons-book-alt"></span>'.RM_UI_Strings::get('LABEL_PAYMENTS_GUIDE'). '</a></div></div>'));
        $config_field = new Element_HTML('<a href=javascript:void(0) onclick="rm_open_payproc_config(this)">'.__('configure','registrationmagic-gold').'</a>');
        $form->addElement(new Element_Checkbox(RM_UI_Strings::get('LABEL_PAYMENT_PROCESSOR'), "payment_gateway", $data['pay_procs_options'], array("value" => $data['payment_gateway'], "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_PYMNT_PROCESSOR')), array('exclass_row'=>'rm_pricefield_checkbox','sub_element'=>$config_field)));
        if(!$ssl_available){
            $form->addElement(new Element_HTML('<div class="rmrow" id="rm_jqntice_row"><div class="rmfield" for="rm_field_value_options_textarea"><label></label></div><div class="rminput" id="rm_jqnotice_text">'.__('SSL encryption is not available on server! Can not use Stripe and Authorize.net.','registrationmagic-gold').'</div></div>'));
        }
        else
        {
            if($include_stripe!='yes')
                $form->addElement(new Element_HTML('<div class="rmrow" id="rm_jqntice_row"><div class="rmfield" for="rm_field_value_options_textarea"><label></label></div><div class="rminput" id="rm_jqnotice_text">'.__('To enable Stripe payments you must include Stripe library from Global Settings --> Advance Options.','registrationmagic-gold').'</div></div>'));
        }
            
        
        ////////////////// Payment Processor configuration popup /////////////////
        $form->addElement(new Element_HTML('<div id="rm_pproc_config_parent_backdrop" style="display:none" class="rm_config_pop_wrap">'));
        $form->addElement(new Element_HTML('<div id="rm_pproc_config_parent" style="display:block" class="rm_config_pop">'));
        foreach($data['pay_procs_configs'] as $pproc_name => $form_elems):
            $form->addElement(new Element_HTML('<div class="rm_pproc_config_single" id="rm_pproc_config_'.$pproc_name.'" style="display:none">'));
                $form->addElement(new Element_HTML("<div class='rm_pproc_config_single_titlebar'><div class='rm_pproc_title'>{$data['pay_procs_options'][$pproc_name]}</div><span onclick='jQuery(\"#rm_pproc_config_parent_backdrop\").hide();' class='rm-popup-close'>&times;</span></div>"));
                $form->addElement(new Element_HTML('<div class="rm_pproc_config_single_elems">'));
            foreach($form_elems as $elem):
                $form->addElement($elem);
            endforeach;
                $form->addElement(new Element_HTML('</div>'));
            $form->addElement(new Element_HTML('</div>'));
        endforeach;
        
        $form->addElement(new Element_HTML('</div>'));
        $form->addElement(new Element_HTML('</div>'));
        ////////////////// End: Payment Processor configuration popup ////////////
        
        $form->addElement(new Element_Select(RM_UI_Strings::get('LABEL_CURRENCY'), "currency", $curr_arr, array("value" => $data['currency'], "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_PYMNT_CURRENCY'))));
        $form->addElement(new Element_Select(RM_UI_Strings::get('LABEL_CURRENCY_SYMBOL'), "currency_symbol_position", array("before" => __("Before amount (Eg.: $10)",'registrationmagic-gold'), "after" => __("After amount (Eg.: 10$)",'registrationmagic-gold')), array("value" => $data['currency_symbol_position'], "longDesc" => RM_UI_Strings::get("LABEL_CURRENCY_SYMBOL_HELP"))));

        $form->addElement(new Element_HTMLL('&#8592; &nbsp; '.__('Cancel','registrationmagic-gold'), '?page=rm_options_manage', array('class' => 'cancel')));
        $form->addElement(new Element_Button(RM_UI_Strings::get('LABEL_SAVE')));

        $form->render();
        ?>

    </div>
</div>
<pre class="rm-pre-wrapper-for-script-tags"><script type="text/javascript">
    
    function rm_open_payproc_config(ele) {        
        var jqele = jQuery(ele);
        
        if(jqele.closest(".rmrow").hasClass("rm_deactivated"))
            return;
        
        var jq_pproc = jqele.parents("li").children('span.rm-pricefield-wrap').children().val();
        
        if(typeof jq_pproc == 'undefined')
            return;
        
        jQuery("#rm_pproc_config_parent").children().hide();
        jQuery("#rm_pproc_config_parent").children("#rm_pproc_config_"+jq_pproc).show();
        jQuery("#rm_pproc_config_parent_backdrop").show();
    }
    
    jQuery(document).mouseup(function (e) {
        var container = jQuery("#rm_pproc_config_parent");
        if (!container.is(e.target) // if the target of the click isn't the container... 
                && container.has(e.target).length === 0 && container.is(":visible")) // ... nor a descendant of the container 
        {
            jQuery("#rm_pproc_config_parent_backdrop").hide();
        }
    });
    
    jQuery(document).ready(function () {
        jQuery('#options_payment-element-1-0').click(function () {
            checkbox_disable_elements(this, 'rm_pp_test_cb-0,rm_pp_email_tb,rm_pp_style_tb', 0);
        });
        jQuery('#options_payment-element-1-1').click(function () {
            checkbox_disable_elements(this, 'rm_s_api_key_tb,rm_s_publish_key_tb', 0);
        });
        <?php if(!$ssl_available){?>
            jQuery('#options_payment-element-1-1').attr('checked', false);
            jQuery('#options_payment-element-1-1').attr('disabled', true);
        <?php ;} ?>               
        
        var pgws_jqel = jQuery("input[name='payment_gateway[]']");
        
        pgws_jqel.each(function(){
            var cbox_jqel = jQuery(this);
            if(cbox_jqel.prop('checked'))
                cbox_jqel.parents("li").children('.rmrow').removeClass("rm_deactivated");
            else
                cbox_jqel.parents("li").children('.rmrow').addClass("rm_deactivated");
        });
        
        pgws_jqel.change(function(){
            var cbox_jqel = jQuery(this);
            if(cbox_jqel.prop('checked'))
                cbox_jqel.parents("li").children('.rmrow').removeClass("rm_deactivated");
            else
                cbox_jqel.parents("li").children('.rmrow').addClass("rm_deactivated");
        });
    });
</script></pre>

<?php   
