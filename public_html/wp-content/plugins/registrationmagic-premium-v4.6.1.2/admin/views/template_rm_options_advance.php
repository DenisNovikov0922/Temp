<?php
if (!defined('WPINC')) {
    die('Closed');
}
?>
<div class="rmagic">

    <!--Dialogue Box Starts-->
    <div class="rmcontent">


        <?php
//PFBC form
        $form = new RM_PFBC_Form("options_advance");
        $form->configure(array(
            "prevent" => array("bootstrap", "jQuery"),
            "action" => ""
        ));
        $form->addElement(new Element_HTML('<div class="rmheader">'.__('Advance Options','registrationmagic-gold').'</div>'));
        $form->addElement(new Element_Checkbox(__('Enable Stripe Library','registrationmagic-gold'), "include_stripe", array("yes" => ''), array("value" =>$data['include_stripe'], "longDesc" => __('Disabling this option will exclude Stripe library from inclusion.','registrationmagic-gold'))));
        $form->addElement(new Element_Select("<b>".__('Session Management','registrationmagic-gold')."</b>", "session_policy", array('db'=>'Database','file'=>'File'), array("value" =>$data['session_policy'], "class" => "rm_static_field rm_required", "longDesc"=>__('Change session save policy.','registrationmagic-gold'))));
        $form->addElement(new Element_HTMLL('&#8592; &nbsp; '.__('Cancel','registrationmagic-gold'), '?page=rm_options_manage', array('class' => 'cancel')));
        $form->addElement(new Element_Button(RM_UI_Strings::get('LABEL_SAVE')));

        $form->render();
        ?>

    </div>
</div>
