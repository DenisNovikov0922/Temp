<?php

$guest_checkout_warning = null;

$wc_guest_checkout= get_option('woocommerce_enable_guest_checkout');
if($wc_guest_checkout=="yes")
{
    $guest_checkout_warning = RM_WC_UI_Strings::get('ALERT_GUEST_CHECKOUT_ENABLED');
    $woocomm_url = admin_url( 'admin.php?page=wc-settings&tab=checkout');
    $guest_checkout_warning = '<div id="rmwc_guest_checkout_notice">'.sprintf($guest_checkout_warning, $woocomm_url).'</div>';    
}

?>


<div class="rmagic">

    <!--Dialogue Box Starts-->
    <div class="rmcontent">


        <?php        
        $form = new RM_PFBC_Form("options_rmwc");
        $form->configure(array(
            "prevent" => array("bootstrap", "jQuery"),
            "action" => ""
        ));

        $form->addElement(new Element_HTML('<div class="rmheader">' . RM_WC_UI_Strings::get('LABEL_RM_GLOBAL_SETTING_MENU') . '</div>'));
        $form->addElement(new Element_Select(RM_WC_UI_Strings::get('LABEL_WOO_REG_FORM'), "rm_wc_reg_form_id", $data->rm_forms, array("value" => $data->selected, "longDesc" => RM_WC_UI_Strings::get('HELP_WOO_REG_FORM'))));
        if($guest_checkout_warning) $form->addElement(new Element_HTML($guest_checkout_warning));        
        $form->addElement(new Element_Checkbox(RM_WC_UI_Strings::get('LABEL_ENABLE_CART_IN_FAB'), "rm_wc_enable_cart_in_fab", array("yes" => ''), array("value" => $data->enable_cart_in_fab, "longDesc" => RM_WC_UI_Strings::get('HELP_ENABLE_CART_IN_FAB'))));
        $form->addElement(new Element_Checkbox(RM_WC_UI_Strings::get('LABEL_ENABLE_RM_ROLE_OVERRIDE'), "rm_wc_enable_rm_role_override", array("yes" => ''), array("value" => $data->enable_rm_role_override, "longDesc" => RM_WC_UI_Strings::get('HELP_ENABLE_RM_ROLE_OVERRIDE'))));
        $form->addElement(new Element_HTMLL('&#8592; &nbsp; '.__('Cancel','registrationmagic-gold'), '?page=rm_options_manage', array('class' => 'cancel')));
        $form->addElement(new Element_Button(RM_UI_Strings::get('LABEL_SAVE')));
        $form->render();
        ?> 

    </div>
</div>
<style>
    #rmwc_guest_checkout_notice {
    width: 98%;
    display: inline-block;
    margin-left: 1%;
    float: left;
    background-color: #fafafa;
    padding: 15px;
    font-size: 12px;
    text-align:left;
    border: 1px solid #eee;
    border-left: 4px solid #94cdc9;
}
</style>
