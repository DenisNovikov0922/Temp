<?php
if (!defined('WPINC')) {
    die('Closed');
}
?>

    <div id="rm-form-publish-info">

        <div class="rmrow">
        <div id="rm-form-publish-shortcode-info">
            <?php _e('Paste following shortcode in a post or page to publish this form.', 'registrationmagic-gold'); ?>
            <div id="rm-form-publish-shortcode" class="rmcode">
                <span id="rmformshortcode"><?php echo "[RM_Form id='{$data->form_id}']"; ?></span>
                <button onclick="rm_copy_content(document.getElementById('rmformshortcode'))"><?php _e('Copy', 'registrationmagic-gold'); ?></button>
            </div>
        </div>
            
        </div>
        
        <div class="rmrow">
        <div id="rm-form-publish-embedcode-info">
            <?php _e('Or, you can use following embed code to display this form outside Wordpress.', 'registrationmagic-gold'); ?>
            <div id="rm-form-publish-embedcode" class="rmcode">
                <span id="rmformembedcode">
                    <?php echo htmlentities('<iframe class="regmagic_embed" width="500" height="500" src="'.admin_url('admin-ajax.php?action=registrationmagic_embedform&form_id=' . $data->form_id).'"></iframe>'); ?>
                </span>
                <button onclick="rm_copy_content(document.getElementById('rmformembedcode'))"><?php _e('Copy', 'registrationmagic-gold'); ?></button>
            </div>
        </div>
        </div>
        
        <div style="display:none" id="rm_msg_copied_to_clipboard"><?php _e('Copied to clipboard', 'registrationmagic-gold'); ?></div>
        <div style="display:none" id="rm_msg_not_copied_to_clipboard"><?php _e('Could not be copied. Please try manually.', 'registrationmagic-gold'); ?></div>
    </div>


<script>
function rm_copy_content(target) {

    var text_to_copy = jQuery(target).text();

    var tmp = jQuery("<input id='fd_form_shortcode_input' readonly>");
    var target_html = jQuery(target).html();
    jQuery(target).html('');
    jQuery(target).append(tmp);
    tmp.val(text_to_copy).select();
    var result = document.execCommand("copy");

    if (result != false) {
        jQuery(target).html(target_html);
        jQuery("#rm_msg_copied_to_clipboard").fadeIn('slow');
        jQuery("#rm_msg_copied_to_clipboard").fadeOut('slow');
    } else {
        jQuery(document).mouseup(function (e) {
            var container = jQuery("#fd_form_shortcode_input");
            if (!container.is(e.target) // if the target of the click isn't the container... 
                    && container.has(e.target).length === 0) // ... nor a descendant of the container 
            {
                jQuery(target).html(target_html);
            }
        });
    }
}
</script>