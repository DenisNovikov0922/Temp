<?php wp_enqueue_script('jquery-ui-accordion'); 
$onReadyScript = ''; 
$tab_index = false;
wp_enqueue_script('rm_wc_script');
wp_enqueue_script('rm_jquery_conditionalize');
?>
<!-- container -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<div class="rmwc rmwc-accordion">
    <?php $global_option = new RM_Options; ?>
    <?php  
    foreach ($pages->order as $order => $page_no):
        ?>
        <?php if(count($pages->data) > 1): ?>
        <h3><?php echo $pages->data[$page_no]; ?></h3>
        <?php endif; ?>
        <div class="rmwc-section" id="section-<?php echo $page_no; ?>">
            <input id="rm_wc_stat_id_holder" style="display: none" type="text" name="stat_id" value="<?php echo $stat_id; ?>" autocomplete="off">
            <input type="hidden" name="rm_tp_timezone" value="" id="id_rm_tp_timezone">
            <?php
            
            if($role_picker_ele && $page_no == 0) {
                ?><div class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide rmwc-field rmrow">
                    <label><?php echo $role_picker_ele->getLabel(); ?>
                    <?php echo $global_option->get_value_of('show_asterix')!="yes" ? '' : '<span class="required">*</span>'; ?>
                    </label><div class='rmwc-input'><?php
                $role_picker_ele->render(); ?></div></div><?php
            }
            
            foreach ($fields as $fname => $field): 
                //$field = new RM_Frontend_Field_Base;
                if ($field->get_page_no() != $page_no + 1 ) 
                    continue;

                if(in_array($field->get_field_id(), $invalid_fields) && $tab_index === false)
                    $tab_index = $order;    
                
                
                
                $field_opt = $field->get_field_options();
                $f_type = $field->get_field_type(); 
                
                ?>
                <div class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide rmwc-field rmrow">
                    <?php if(!in_array($f_type, array("HTMLH","HTMLP","HTMLCustomized","RichText","Timer","Link","YouTubeV"))): ?>
                    <label><?php echo $field->get_field_label(true); ?>
                        
                        <?php echo (empty($field_opt['required']) || $global_option->get_value_of('show_asterix')!="yes") ? '' : '<span class="required">*</span>'; ?>
                    </label>
                    <?php endif; ?>
                    <div class='rmwc-input'>
                    <?php echo $field->render(); ?>
                    </div>
                    <?php
                    if(!empty($field_opt['longDesc'])){
                            echo '<div class="rmnote"><div class="rmprenote"></div>';
                            echo '<div class="rmnotecontent">', $field_opt['longDesc'], '</div></div>';
                    } 
                    ?>
                </div>
                <?php
                $onReadyScript .= $field->jquery_document_ready();
            endforeach;
            ?>
        </div>
        <?php
    endforeach;
    ?>
</div>


<script type="text/javascript">
    jQuery(document).ready(function () {
        if(jQuery('#id_rm_tp_timezone').length > 0)
            jQuery('#id_rm_tp_timezone').val(-new Date().getTimezoneOffset()/60);
        rmwcRegisterStatID();
        if(<?php echo count($pages->data); ?>>1)
        jQuery(".rmwc-accordion").accordion({heightStyle: "content", active: <?php echo $tab_index?:0; ?>});
        <?php echo $onReadyScript; ?>
        <?php             
            foreach($invalid_fields as $field_name => $field_id):
             ?>
              var input = jQuery("[name='<?php echo $field_name; ?>']");
              input.addClass('rmwc_not_valid');
              input.focus();
            <?php
            endforeach;
            ?>
                    
         //Help text invocation
           jQuery(".rmwc-input").on ({
                click: function () {rmwcHelpTextIn2(this);},
                mouseleave: function () {rmwcHelpTextOut2(this);}
            });
            jQuery("input, select, textarea").blur(function (){
                jQuery(this).parents(".rmwc-input").siblings(".rmnote").fadeOut('fast');
            });
    });
    
    //Helptext function 
function rmwcHelpTextIn2(a) {
    var helpTextNode = jQuery(a).siblings(".rmnote");
    var fieldHeight = jQuery(a).parent().outerHeight();
    var fieldWidth = jQuery(a).parent().outerWidth();
    var fieldPaddingLeft = jQuery(a).parent().css('padding-left').replace("px", "");
    var fieldPaddingRight = jQuery(a).parent().css('padding-right').replace("px", "");
    var topPos = fieldHeight - 50;
    helpTextNode.css('width', (fieldWidth - fieldPaddingLeft - fieldPaddingRight ) + "px");
    var id = setInterval(frame, 2);
    helpTextNode.fadeIn(500);
    function frame() {
        if (topPos === fieldHeight) {
            clearInterval(id);
        } else {
            topPos++;
            helpTextNode.css('top', topPos + "px");
            }
        }
    } 

function rmwcHelpTextOut2(a) {
    jQuery(a).siblings(".rmnote").fadeOut('fast');
}

function rmwcRegisterStatID() {
    $stat_id = jQuery("input#rm_wc_stat_id_holder");
    
    if($stat_id.length <= 0 || $stat_id.val() !== "__uninit")
        return;
    
    var form_ids = ["form_"+<?php echo $form_id; ?>+"_1"];
    var data = {
                    'action': 'rm_register_stat_ids',
                    'form_ids': form_ids
               };
    
    jQuery.post(rm_ajax_url,
                data,
                function(resp){
                    resp = JSON.parse(resp);
                    if(typeof resp === 'object') {
                        var stat_id = null, stat_field;
                        for(var key in resp) {
                            if(resp.hasOwnProperty(key)) {
                                if(key === form_ids[0]) {
                                    stat_id = resp[key];    
                                    $stat_id.val(stat_id);
                                }                                
                            }
                        }
                    }                    
                });
}

</script>
<style>
    .rmwc-accordion .rmwc-field{
        position: relative !important;
    }
    
    .rmwc-field .rmnote {
    z-index: 9999999;
    display: none;
    position: absolute !important;
    padding: 10px;
    border-radius: 5px;
    -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    -ms-border-radius: 5px;
    -o-border-radius: 5px;
    font-size: 14px;
    color: #fff;

    }
</style>
<script>
jQuery(document).ready(function(){
    rm_woocommerce_form_layer();
});
</script>
<?php unset($_SESSION['rm_wc_errors']);?>
