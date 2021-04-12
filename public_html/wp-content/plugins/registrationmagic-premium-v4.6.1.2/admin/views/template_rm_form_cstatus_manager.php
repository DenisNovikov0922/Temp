<?php
if (!defined('WPINC')) {
    die('Closed');
}
?>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <div class="rmagic">

    <!-----Operations bar Starts----->

    <div class="operationsbar">
        <div class="rmtitle"><?php echo RM_UI_Strings::get("TITLE_CSTATUS_MANAGER"); ?></div>
        <div class="icons">
            <a href="?page=rm_options_manage"><img alt="" src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))) . 'images/global-settings.png'; ?>"></a>

        </div>
        <div class="nav">
            <ul>
                <li onclick="window.history.back()"><a href="javascript:void(0)"><?php echo RM_UI_Strings::get("LABEL_BACK"); ?></a></li>
                
                <li><a href="<?php echo admin_url('admin.php?page=rm_form_add_cstatus&rm_form_id='.$data->form_id); ?>"><?php echo RM_UI_Strings::get("LABEL_NEW_STATUS"); ?></a></li>
                
                <li id="rm-delete-cstatus" class="rm_deactivated" onclick="jQuery.rm_do_action('rm_cstatus_manager_form', 'rm_cstatus_remove')"><a href="javascript:void(0)"><?php echo RM_UI_Strings::get("LABEL_DELETE"); ?></a></li>

                <li class="rm-form-toggle">
                    <?php if (count($data->forms) !== 0)
                    {
                        echo RM_UI_Strings::get('LABEL_TOGGLE_FORM');
                        ?>
                        <select id="rm_form_dropdown" name="form_id" onchange = "rm_load_page(this, 'form_manage_cstatus')">
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
                    ?>
                </li>
            </ul>
        </div>

    </div>
    <!--  Operations bar Ends----->


    <!-------Content area Starts----->

    <?php
    if(count($data->custom_status) === 0){
        ?><div class="rmnotice-container">
            <div class="rmnotice">
        <?php echo RM_UI_Strings::get('MSG_NO_FORM_CSTATUS_MAN'); ?>
            </div>
        </div><?php
    }
   else
    {?>
    <div class="form-cstatus-table-wrapper">
        <form action="" method="post" id="rm_cstatus_manager_form">
        <table class="rm-form-cstatus">
            <tr>
                <th><input type="checkbox" id="rm_cstatus_select_all" onchange="selectAll(this)" /></th>
                <th>Color</th>
                <th>Label</th>
                <th>&nbsp;</th>
            </tr>
       <?php foreach($data->custom_status as $key=>$custom_status): ?>
            <tr>
                <td><input class="rm-cstatus-index" type="checkbox" name="rm_cstatus_index[]" value="<?php echo $key; ?>"/></td>
                <td><div class="rm-cstatus-color" style="background-color: #<?php echo $custom_status['color']; ?>">&nbsp;<span style="border-color: #<?php echo $custom_status['color']; ?>"></span></div></td>
                <td><?php echo $custom_status['label']; ?></td>
                <td><a href="<?php echo admin_url('admin.php?page=rm_form_add_cstatus&rm_form_id='.$data->form_id.'&cstatus_id='.$key); ?>"><?php echo RM_UI_Strings::get('LABEL_VIEW'); ?></a></td>
            </tr>     
       <?php endforeach; ?>
        </table>
            <input type="hidden" id="rm_slug_input_field" name="remove_cstatus" />    
        </form>    
    </div>
    <?php 
    }
?>
            
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.min.css"/>
</div>

<script>
function selectAll(obj){
    if(jQuery(obj).is(':checked')){
        jQuery('.rm-cstatus-index').attr('checked',true);
        jQuery('#rm-delete-cstatus').removeClass('rm_deactivated');
    }
    else{
        jQuery('.rm-cstatus-index').attr('checked',false);
        jQuery('#rm-delete-cstatus').addClass('rm_deactivated');
    }
}
jQuery(document).ready(function(){
    jQuery('.rm-cstatus-index').change(function(){
        if(jQuery('.rm-cstatus-index:checked').length>0){
           jQuery('#rm-delete-cstatus').removeClass('rm_deactivated');
           return;
        }
        jQuery('#rm-delete-cstatus').addClass('rm_deactivated');
        jQuery('#rm_cstatus_select_all').attr('checked',false);
    });
});
</script>
