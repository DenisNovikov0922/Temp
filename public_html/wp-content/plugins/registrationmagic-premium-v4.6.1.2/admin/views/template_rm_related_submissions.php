<?php
if (!defined('WPINC')) {
    die('Closed');
}

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<div class="rmagic">
    <div class="operationsbar">
        
        <div class="nav">
            <ul>
                <li onclick="window.history.back()"><a href="javascript:void(0)"><?php echo RM_UI_Strings::get("LABEL_BACK"); ?></a></li>
              
            </ul>
        </div>

    </div>
    
    <div class="rm-related-submissions">

        <div class="rm-related-submissions-head">
            <div class="rm-submissions-name"><?php _e('Submission History for', 'registrationmagic-gold'); ?> <?php echo $_GET['rm_user_email']; ?></div>
            <div class="rm-total-submissions"><?php _e('Total', 'registrationmagic-gold'); ?><span><?php echo (isset($data->submissions) && !empty($data->submissions))?count($data->submissions):0; ?></span><?php _e('Submissions', 'registrationmagic-gold'); ?></div>
        </div>

       <!-- Loop Data -->
        <?php
        if(isset($data->submissions)){
            if (is_array($data->submissions) || is_object($data->submissions)){
                foreach ($data->submissions as $submission){
                    if($submission->submission_id != $data->submission_id){
                        $forms=new RM_Forms;
                        $forms->load_from_db($submission->form_id);
                        $form_name=$forms->get_form_name();
                        $form_options= $forms->get_form_options();
                        ?>
                        <div class="rm-related-submissions-wrap">
                            <div class="rm-related-submissions-lf">
                                <div class="rm-submission-form-name"><a href="?page=rm_submission_view&rm_submission_id=<?php echo $submission->submission_id; ?>"><?php echo $form_name; ?></a></div>
                                <div class="rm-submission-date"><i class="material-icons">&#xE192;</i><?php echo $submission->submitted_on; ?></div>
                            </div>  
                            <div class="rm-related-submissions-rf">
                                <div class="rm-submissions-status-wrap">
                                <?php
                                $service = new RM_Services();
                                $custom_statuses = $service->get_custom_statuses($submission->submission_id,$submission->form_id);
                                if(!empty($custom_statuses)){
                                    foreach($custom_statuses as $custom_status){
                                        echo '<div class="rm-submissions-status" style="background-color: #'.$form_options->custom_status[$custom_status->status_index]['color'].'">'.$form_options->custom_status[$custom_status->status_index]['label'].'</div>';
                                    }
                                }else{
                                    echo '<div class="rm-submissions-no-status">'.RM_UI_Strings::get('LABEL_NO_STATUS').'</div>';
                                }
                                ?>
                                </div>
                            </div> 
                        </div>
                        <?php
                    }
                }
            }
        }
        ?>
        <!-- Loop Data End -->
    </div>
</div>
