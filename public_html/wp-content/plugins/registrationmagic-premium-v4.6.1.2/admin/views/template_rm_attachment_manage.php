<?php
if (!defined('WPINC')) {
    die('Closed');
}

//s echo "<pre>", var_dump($data);
global $rm_env_requirements;
?>

<?php if (!($rm_env_requirements & RM_REQ_EXT_ZIP)){ ?>
 <div class="shortcode_notification"><p class="rm-notice-para"><?php echo RM_UI_Strings::get('RM_ERROR_EXTENSION_ZIP');?></p></div>
 <?php } ?>

<div class="rmagic">
    <form method="post" id="rm_attachment_manager_form">
        <input type="hidden" name="rm_slug" value="" id="rm_slug_input_field">
        <!-----Operationsbar Starts----->
        <div class="operationsbar">
            <div class="rmtitle"><?php echo RM_UI_Strings::get("TITLE_ATTACHMENT_PAGE"); ?></div>
            <div class="icons">
                <a href="?page=rm_options_general"><img alt="" src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))) . 'images/general-settings.png'; ?>"></a>
            </div>
            <div class="nav">
                <ul>  
                    <li onclick="window.history.back()"><a href="javascript:void(0)"><?php echo RM_UI_Strings::get("LABEL_BACK"); ?></a></li>
                
                    <li onclick="jQuery.rm_do_action('rm_attachment_manager_form', 'rm_attachment_download_all')"><a href="javascript:void(0)"><?php echo RM_UI_Strings::get('LABEL_DOWNLOAD_ALL'); ?></a></li>
                    <li id="rm-delete-attachment" class="rm_deactivated" onclick="jQuery.rm_do_action('rm_attachment_manager_form', 'rm_attachment_download_selected')"><a href="javascript:void(0)"><?php echo RM_UI_Strings::get('LABEL_DOWNLOAD'); ?></a></li>

                    <li class="rm-form-toggle">
                        <?php
                        if (count($data->forms) !== 0)
                        {
                            echo RM_UI_Strings::get('LABEL_TOGGLE_FORM');
                            ?>
                            <select id="rm_form_dropdown" name="form_id" onchange = "rm_load_page(this, 'attachment_manage')">
                                <?php
                                foreach ($data->forms as $form_id => $form)
                                    if ($data->form_id == $form_id)
                                        echo "<option value=$form_id selected>$form</option>";
                                    else
                                        echo "<option value=$form_id>$form</option>";
                                ?>
                            </select>
                            <?php
                        }
                        ?></li>
                </ul>
            </div>

        </div>
        <!--------Operationsbar Ends----->


        <!----Slab View---->
        <div class="rmagic-cards">
            <div class="rmnotice-row">
        <div class="rmnotice">
            You can define allowed attachment types and number in <a target="_blank" href="<?php echo admin_url('admin.php?page=rm_options_general'); ?>">Global Settings</a>.            
        </div>
    </div>
            <?php
            if (count($data->forms) === 0)
                echo "<div class='rmnotice-container'><div class='rmnotice'>" . RM_UI_Strings::get('MSG_NO_FORMS_ATTACHMENTS') . "</div></div>";

            elseif (is_array($data->attachments) || is_object($data->attachments))
            {

                for ($i = $data->offset; $i < $data->offset + $data->entries_on_this_page; $i++)
                {
                    $entry = $data->attachments[$i];
                    // if(!wp_get_attachment_url($entry)) continue;
                    ?><?php // echo $entry;  ?>

                    <div id="<?php echo $entry; ?>" class="rmcard">

                        <div class="cardtitle">
                            <input class="rm_checkbox_group" type="checkbox" name="rm_selected[]" onclick= "rm_on_attachment_selection()" value="<?php echo $entry; ?>"><?php echo get_the_title($entry); ?></div>
                        <div class="rmattachment">

                            <?php
                            echo wp_get_attachment_link($entry, 'thumbnail', false, true, false);
                            ?>
                        </div>
                        <div class="rm-form-links">
                            <div class="rm-form-row"><a href="?page=rm_attachment_download&rm_att_id=<?php echo $entry; ?>"><?php echo RM_UI_Strings::get('LABEL_DOWNLOAD'); ?></a></div>
                            <!--div class="rm-form-row"><a href="?page=rm_attachment_manage&rm_form_id=<?php //echo $data->form_id  ?>&rm_action=delete&rm_att_id=<?php //echo $entry;  ?>">Delete</a></div-->

                        </div>

                    </div>
                    <?php
                }
            } else
                echo "<div class='rmnotice-container'><div class='rmnotice'>" . RM_UI_Strings::get('LABEL_NO_ATTACHMENTS') . "</div></div>";
            ?>
        </div>
    </form>



    <?php
    /*     * ********** Pagination Logic ************** */
    $max_pages_without_abb = 10;
    $max_visible_pages_near_current_page = 3; //This many pages will be shown on both sides of current page number.

    if ($data->total_pages > 1):
        ?>
        <ul class="rmpagination">
            <?php
            if ($data->curr_page > 1):
                ?>
                <li><a href="?page=<?php echo $data->rm_slug ?>&rm_form_id=<?php echo $data->form_id; ?>&rm_reqpage=1"><?php echo RM_UI_Strings::get('LABEL_FIRST'); ?></a></li>
                <li><a href="?page=<?php echo $data->rm_slug ?>&rm_form_id=<?php echo $data->form_id; ?>&rm_reqpage=<?php echo $data->curr_page - 1; ?>"><?php echo RM_UI_Strings::get('LABEL_PREVIOUS'); ?></a></li>
                <?php
            endif;
            if ($data->total_pages > $max_pages_without_abb):
                if ($data->curr_page > $max_visible_pages_near_current_page + 1):
                    ?>
                    <li><a> ... </a></li>
                    <?php
                    $first_visible_page = $data->curr_page - $max_visible_pages_near_current_page;
                else:
                    $first_visible_page = 1;
                endif;

                if ($data->curr_page < $data->total_pages - $max_visible_pages_near_current_page):
                    $last_visible_page = $data->curr_page + $max_visible_pages_near_current_page;
                else:
                    $last_visible_page = $data->total_pages;
                endif;
            else:
                $first_visible_page = 1;
                $last_visible_page = $data->total_pages;
            endif;
            for ($i = $first_visible_page; $i <= $last_visible_page; $i++):
                if ($i != $data->curr_page):
                    ?>
                    <li><a href="?page=<?php echo $data->rm_slug ?>&rm_form_id=<?php echo $data->form_id; ?>&rm_reqpage=<?php echo $i; ?>"><?php echo $i; ?></a></li> 
                <?php else:
                    ?>
                    <li><a class="active" href="?page=<?php echo $data->rm_slug ?>&rm_form_id=<?php echo $data->form_id; ?>&rm_reqpage=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                    <?php
                    endif;
                endfor;
                if ($data->total_pages > $max_pages_without_abb):
                    if ($data->curr_page < $data->total_pages - $max_visible_pages_near_current_page):
                        ?>
                    <li><a> ... </a></li>
                    <?php
                endif;
            endif;
            ?>
            <?php
            if ($data->curr_page < $data->total_pages):
                ?>
                <li><a href="?page=<?php echo $data->rm_slug ?>&rm_form_id=<?php echo $data->form_id; ?>&rm_reqpage=<?php echo $data->curr_page + 1; ?>"><?php echo RM_UI_Strings::get('LABEL_NEXT'); ?></a></li>
                <li><a href="?page=<?php echo $data->rm_slug ?>&rm_form_id=<?php echo $data->form_id; ?>&rm_reqpage=<?php echo $data->total_pages; ?>"><?php echo RM_UI_Strings::get('LABEL_LAST'); ?></a></li>
                <?php
            endif;
            ?>
        </ul>
    <?php endif; ?>
</div>

 <pre class="rm-pre-wrapper-for-script-tags"><script type="text/javascript">
    
    function rm_on_attachment_selection(){
        var selected_attach = jQuery("input.rm_checkbox_group:checked");
        if(selected_attach.length > 0) {   
            jQuery("#rm-delete-attachment").removeClass("rm_deactivated"); 
            } else {
            jQuery("#rm-delete-attachment").addClass("rm_deactivated");
        }
    }

</script></pre>


