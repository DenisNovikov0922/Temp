<?php
if (!defined('WPINC')) {
    die('Closed');
}
?>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <div class="rmagic">

    <!-----Operations bar Starts----->

    <div class="operationsbar">
        <div class="rmtitle"><?php echo RM_UI_Strings::get("TITLE_SUBMISSION_MANAGER"); ?></div>
        <div class="icons">
            <a href="?page=rm_options_manage"><img alt="" src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))) . 'images/global-settings.png'; ?>"></a>

        </div>
        <div class="nav">
            <ul>
                <li onclick="window.history.back()"><a href="javascript:void(0)"><?php echo RM_UI_Strings::get("LABEL_BACK"); ?></a></li>
                
                <li onclick="jQuery.rm_do_action('rm_submission_manager_form', 'rm_submission_export')"><a href="javascript:void(0)"><?php echo RM_UI_Strings::get("LABEL_EXPORT_ALL"); ?></a></li>
                
                <?php
                    if(!$data->is_filter_active) {
                ?>
                <li onclick="jQuery.rm_do_action('rm_submission_manager_form', 'rm_submission_mark_all_read')"><a href="javascript:void(0)"><?php echo RM_UI_Strings::get("LABEL_MARK_ALL_READ"); ?></a></li>
                <?php
                    }
                ?>
                
                <li id="rm-delete-submission" class="rm_deactivated" onclick="jQuery.rm_do_action('rm_submission_manager_form', 'rm_submission_remove')"><a href="javascript:void(0)"><?php echo RM_UI_Strings::get("LABEL_DELETE"); ?></a></li>

                <li class="rm-form-toggle">
                    <?php if (count($data->forms) !== 0)
                    {
                        echo RM_UI_Strings::get('LABEL_TOGGLE_FORM');
                        ?>
                        <select id="rm_form_dropdown" name="form_id" onchange = "rm_load_page(this, 'submission_manage')">
                            <?php
                            foreach ($data->forms as $form_id => $form)
                                if ($data->filter->form_id == $form_id)
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
    <div class="rmnotice-row">
        <div class="rmnotice">
            You can set logo and text for submission PDFs in <a target="_blank" href="<?php echo admin_url('admin.php?page=rm_options_general'); ?>">Global Settings</a>.            
        </div>
    </div>
    <?php
    if(count($data->forms) === 0){
        ?><div class="rmnotice-container">
            <div class="rmnotice">
        <?php echo RM_UI_Strings::get('MSG_NO_FORM_SUB_MAN'); ?>
            </div>
        </div><?php
    }
    elseif ( $data->submissions || $data->filter->filters['rm_interval'] != 'all' || $data->filter->searched)
    {
        ?>
        <div class="rmagic-table">

            <div class="sidebar">
                <span class="rm-search-sidebar-searchtab active" onclick="show_sidebar('search')"><?php _e('Search','registrationmagic-gold') ?></span>
                <span class="rm-search-sidebar-filtertab" onclick="show_sidebar('filter')"><?php _e('Filters','registrationmagic-gold') ?></span>
                <div id="searching_sidebar">
                <div class="sb-filter">
                    <?php echo RM_UI_Strings::get("LABEL_TIME"); ?>
                    <div class="filter-row"><input type="radio" onclick='rm_load_page_multiple_vars(this, "submission_manage", "interval",<?php echo json_encode(array('form_id' => $data->filter->form_id)); ?>)' name="filter_between" value="all"   <?php if ($data->filter->filters['rm_interval'] == "all") echo "checked"; ?>><?php echo RM_UI_Strings::get("LABEL_ALL"); ?> </div>
                    <div class="filter-row"><input type="radio" onclick='rm_load_page_multiple_vars(this, "submission_manage", "interval",<?php echo json_encode(array('form_id' => $data->filter->form_id)); ?>)' name="filter_between" value="today" <?php if ($data->filter->filters['rm_interval'] == "today") echo "checked"; ?>><?php echo RM_UI_Strings::get("LABEL_TODAY"); ?> </div>
                    <div class="filter-row"><input type="radio" onclick='rm_load_page_multiple_vars(this, "submission_manage", "interval",<?php echo json_encode(array('form_id' => $data->filter->form_id)); ?>)' name="filter_between" value="week"  <?php if ($data->filter->filters['rm_interval'] == "week") echo "checked"; ?>><?php echo RM_UI_Strings::get("LABEL_THIS_WEEK"); ?></div>
                    <div class="filter-row"><input type="radio" onclick='rm_load_page_multiple_vars(this, "submission_manage", "interval",<?php echo json_encode(array('form_id' => $data->filter->form_id)); ?>)' name="filter_between" value="month" <?php if ($data->filter->filters['rm_interval'] == "month") echo "checked"; ?>><?php echo RM_UI_Strings::get("LABEL_THIS_MONTH"); ?></div>
                    <div class="filter-row"><input type="radio" onclick='rm_load_page_multiple_vars(this, "submission_manage", "interval",<?php echo json_encode(array('form_id' => $data->filter->form_id)); ?>)' name="filter_between" value="year"  <?php if ($data->filter->filters['rm_interval'] == "year") echo "checked"; ?>><?php echo RM_UI_Strings::get("LABEL_THIS_YEAR"); ?></div>
                    <div class="filter-row"><input type="radio" onclick='rm_load_page_multiple_vars(this, "submission_manage", "interval",<?php echo json_encode(array('form_id' => $data->filter->form_id)); ?>)' name="filter_between" value="custom"  <?php if ($data->filter->filters['rm_interval'] == "custom") echo "checked"; ?>><?php echo RM_UI_Strings::get("LABEL_CUSTOM_RANGE"); ?></div>
                  <?php if($data->filter->filters['rm_interval'] == "custom") 
                  {
                      ?>
                    <div id="date_box">
                    <?php
                        }
                        else
                      {
                      ?>
                    <div id="date_box" style="display:none">
                        <?php
                        }  
                        ?>
                        <div class="filter-row"><span><?php echo RM_UI_Strings::get("LABEL_CUSTOM_RANGE_FROM_DATE"); ?></span><input type="text" onchange='rm_load_page_multiple_vars(this, "submission_manage", "interval",<?php echo json_encode(array('form_id' => $data->filter->form_id)); ?>)' class="rm_custom_subfilter_dates" id="rm_id_custom_subfilter_date_from" name="rm_custom_subfilter_date_from" value="<?php echo $data->filter->filters['rm_fromdate']; ?>"<?php if ($data->filter->filters['rm_interval'] != "custom") echo "disabled"; ?>></div>
                        <div class="filter-row"><span><?php echo RM_UI_Strings::get("LABEL_CUSTOM_RANGE_UPTO_DATE"); ?></span> <input type="text" onchange='rm_load_page_multiple_vars(this, "submission_manage", "interval",<?php echo json_encode(array('form_id' => $data->filter->form_id)); ?>)' class="rm_custom_subfilter_dates" id="rm_id_custom_subfilter_date_upto" name="rm_custom_subfilter_date_upto" value="<?php echo $data->filter->filters['rm_dateupto']; ?>"<?php if ($data->filter->filters['rm_interval'] != "custom") echo "disabled"; ?>></div>
               
                    </div>
                </div>
                    
                <div class="sb-filter">
                      <span><?php echo RM_UI_Strings::get("LABEL_STATUS_FILTERS"); ?></span>
                      <div class="filter-row">
                          <?php
                          $form_id = $data->filter->form_id;
                          //$submission_id = $data->submission->get_submission_id();
                          $form= new RM_Forms();
                          $form->load_from_db($form_id);
                          $form_options= $form->get_form_options();
                          //echo '<pre>';print_r($form_options->custom_status);echo '</pre>';
                          ?>
                          <select name="custom_status" class="rm_custom_status_filter" multiple="">
                              <option value=""><?php _e('Select Custom Status','registrationmagic-gold') ?></option>
                              <?php
                              if(!empty($form_options->custom_status)){
                                  $search_cs = array();
                                  if(!empty($_GET['custom_status_ind'])){
                                      $search_cs = explode(',',$_GET['custom_status_ind']);
                                  }
                                  foreach($form_options->custom_status as $key=>$value){
                                      echo '<option value="'.$key.'" '.((in_array($key, $search_cs))?'selected':'').' >'.$value['label'].'</option>';
                                  }
                              }
                              ?>
                          </select>
                      </div>
                      <div class="filter-row"><input type="button" name="submit" value="<?php _e('Search','registrationmagic-gold') ?>" onclick="rm_load_custom_status()"></div>
                </div>
                    
                <div class="sb-filter">
                      <span><?php echo RM_UI_Strings::get("LABEL_PROPERTY_FILTERS"); ?></span>
                      <div class="filter-row"><input type="text" name="filter_tags" class="sb-search rm-auto-tag rm-submission-tag"></div> 
                </div>
                    
                <div class="sb-filter">
                    <?php echo RM_UI_Strings::get("LABEL_MATCH_FIELD"); ?>
                    <form action="" method="post">
                        <div class="filter-row">
                            <select name="rm_field_to_search">
                                <?php
                                foreach ($data->fields as $f)
                                {   
                                    if (!in_array($f->field_type,  RM_Utilities::submission_manager_excluded_fields()))
                                    {  
                                        ?>
                                        <option value="<?php echo $f->field_id; ?>" <?php if($data->filter->filters['rm_field_to_search'] === $f->field_id)echo "selected";?>><?php echo $f->field_label; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="filter-row"><input type="text" name="rm_value_to_search" class="sb-search" value="<?php echo $data->filter->filters['rm_value_to_search']; ?>"></div>
                        <input type="hidden" name="rm_search_initiated" value="yes">
                        <div class="filter-row"><input type="submit" name="submit" value="<?php _e('Search', 'registrationmagic-gold'); ?>"></div>
                    </form>
                </div>


            </div>
                    <div id="filtering_sidebar" style="display:none">
                        <div class="sb-filter">
                             <span><?php echo RM_UI_Strings::get("SAVE_SEARCH"); ?></span>
                              <?php 
                              $admin=  admin_url();
                              $criteria = explode("?",$_SERVER['REQUEST_URI']);
                              $gopts= new RM_Options;
                              $custom_filters=$gopts->get_value_of('rm_submission_filters');
                              $custom_filters=  maybe_unserialize($custom_filters); ?>
                        <div id="add_filter_div">
                            <div class="filter-row">
                                <input id="filter_name" class="sb-search" type ="text" placeholder="Enter filter name" value="" autocomplete="off"/> 
                            </div>  
                            <div class="filter-row">
                                <input type ="button" value ="<?php _e('Save', 'registrationmagic-gold'); ?>" onclick ="add_filter('<?php echo $criteria[1] ;?>')">
                            </div> 
                        </div>       
                             </div>
                             <?php
                              if($custom_filters != null)
                              {

                              ?>
                              <div class="sb-filter">
                                 <?php echo RM_UI_Strings::get("LABEL_CUSTOM_FILTERS"); ?>
                             <div id="filter_div">
                            <div class="filter-row">
                            <select id="filter_options">
                                <?php 
                                foreach($custom_filters as $filter_name => $filter_url)
                                {
                                    $sub_model = new RM_Submissions;
                                    $sub_counts=$sub_model->get_subs_counts($filter_url);
                                    ?>
                                <option value="<?php echo $filter_url; ?>"> <?php echo $filter_name,'(',$sub_counts,')'; ?> </option>
                                <?php 
                                }

                                ?>
                            </select>
                            </div>
                                 <div class="filter-row">
                                  <input type="button" value="<?php _e('Apply','registrationmagic-gold') ?>" onclick="apply_filter('<?php echo $admin ?>')">
                                  <input type="button" value="<?php _e('Delete','registrationmagic-gold') ?>" onclick="delete_filter('<?php echo $admin ?>')">
                                 </div>
                              </div>
                             </div>
                                <?php 
                                }
                              ?>



                             
                          </div>
        </div>
            <!--*******Side Bar Ends*********-->

            <form method="post" action="" name="rm_submission_manage" id="rm_submission_manager_form">
                <input type="hidden" name="rm_slug" value="" id="rm_slug_input_field" />
                  <input type="hidden" name="rm_form_id" value="<?php echo $data->filter->form_id; ?>" id="rm_form_id_input_field" />
                <input type="hidden" name="rm_interval" value="<?php echo $data->filter->filters['rm_interval']; ?>" />
                <?php if ($data->filter->searched && isset($data->filter->filters['rm_field_to_search']))
                {
                    ?>
                    <input type="hidden" name="rm_field_to_search" value="<?php echo $data->filter->filters['rm_field_to_search']; ?>">
                    <input type="hidden" name="rm_value_to_search" value="<?php echo $data->filter->filters['rm_value_to_search']; ?>">
                    <?php
                }
                ?>
                <table class="rm_submissions_manager_table">
                    <?php 
                    if ($data->submissions)
                    {
                        //echo "<pre>",  var_dump($data->submissions);
                        ?>
                        <tr>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>

                            <?php
                            //echo "<pre>";var_dump($data->submissions);die();


                            $field_names = array();
                            $i = $j = 0;

                            for ($i = 0; $j < 4; $i++):
                                if ((isset($data->fields[$i]->field_type) && !in_array($data->fields[$i]->field_type,  RM_Utilities::submission_manager_excluded_fields())) || !isset($data->fields[$i]->field_type))
                                {

                                    $label = isset($data->fields[$i]->field_label) ? $data->fields[$i]->field_label : null;
                                    ?><th><?php echo $label; ?></th>

                                    <?php
                                    $field_names[$j] = isset($data->fields[$i]->field_id) ? $data->fields[$i]->field_id : null;
                                    $j++;
                                }

                            endfor;
                            ?>

                            <th><?php echo RM_UI_Strings::get("ACTION"); ?></th></tr>

                        <?php
                       
                        if (is_array($data->submissions) || is_object($data->submissions))
                            foreach ($data->submissions as $submission):
                                
                                $submission->data_us = RM_Utilities::strip_slash_array(maybe_unserialize($submission->data));
                                $read_status= $submission->is_read==1 ? 'readed': 'unreaded';
                        ?>
                                <tr  class="<?php echo $read_status; ?>">
                                    <td><input class="rm_checkbox_group" onclick="rm_on_selected_submissions()" type="checkbox" value="<?php echo $submission->submission_id; ?>" name="rm_selected[]">
                                      
                                    </td>
                                    <td>
                                        <?php
                                      $submission_model=new RM_Submissions;
                                      $submission_model->load_from_db($submission->submission_id);
                                      $have_attchment=$submission_model-> is_have_attcahment();
                                      $isblocked=$submission_model->is_blocked();
                                      $payment_status=$submission_model->get_payment_status();
                                      $payment_status = strtolower($payment_status);
                                      $note_status=$submission_model->get_note_status();
                                      if($payment_status=='canceled' || $payment_status==strtolower(__( 'Canceled', 'registrationmagic-gold' )))
                                      {
                                      ?>
                                        <img  class="rm_submission_icon" alt="" src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))) . 'images/canceled_payment.png'; ?>">
                                      <?php  
                                      }
                                      if($payment_status=='refunded' || $payment_status==strtolower(__( 'Refunded', 'registrationmagic-gold' )))
                                      {
                                      ?>
                                      <img  class="rm_submission_icon" alt="" src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))) . 'images/refunded_payment.png'; ?>">
                                      <?php    
                                      }
                                      if($payment_status == 'pending' || $payment_status==strtolower(__( 'Pending', 'registrationmagic-gold' )))
                                      {
                                         ?>
                                        <img  class="rm_submission_icon" alt="" src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))) . 'images/pending_payment.png'; ?>">
                                      <?php  
                                      }
                                      if(in_array($payment_status,array('completed','succeeded',strtolower(__( 'Completed', 'registrationmagic-gold' )))))
                                      {
                                         ?>
                                        <img  class="rm_submission_icon" alt="" src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))) . 'images/payment_completed.png'; ?>">
                                      <?php  
                                      }
                                      if($isblocked){?>
                                        <img  class="rm_submission_icon" alt="" src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))) . 'images/blocked.png'; ?>">
                                      <?php }
                                      
                                      if($note_status['note'] == 1)
                                      {
                                         ?>
                                        <img  class="rm_submission_icon" alt="" src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))) . 'images/note.png'; ?>">
                                      <?php  
                                      }
                                       if($note_status['message'] == 1)
                                      {
                                         ?>
                                        <img  class="rm_submission_icon" alt="" src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))) . 'images/message.png'; ?>">
                                      <?php  
                                      }
                                       if($have_attchment)
                                      {
                                         ?>
                                        <img  class="rm_submission_icon" alt="" src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))) . 'images/attachment.png'; ?>">
                                      <?php  
                                      }
                                      ?>
                                    </td>
                                    <?php
                                    
                                    for ($i = 0; $i < 4; $i++):

                                        $value = null;
                                            $type=null;
                                              
                                        if (is_array($submission->data_us) || is_object($submission->data_us))
                                            foreach ($submission->data_us as $key => $sub_data)
                                                 
                                                if ($key == $field_names[$i])
                                                {
                                                    $type =  isset($sub_data->type)?$sub_data->type:'';
                                                    $meta =  isset($sub_data->meta)?$sub_data->meta:'';
                                                    if($type=='Checkbox' || $type == 'Select' || $type == 'Radio')
                                                        $value = RM_Utilities::get_lable_for_option($key, $sub_data->value);
                                                    else
                                                        $value = $sub_data->value;
                                                }
                                                
                                        ?>

                                        <td class="rm_data"><?php
                                            if (is_array($value))
                                                $value = implode(', ', $value);
                                            
                                            if($type=='Rating')
                                            {
                                                $r_sub = array('value' => $value,
                                                                'readonly' => 1,
                                                                'star_width' => 16,
                                                                'max_stars' => 5,
                                                                'star_face' => 'star',
                                                                'star_color' => 'FBC326');
                                                if(isset($meta) && is_object($meta)) {
                                                    if(isset($meta->max_stars))
                                                        $r_sub['max_stars'] = $meta->max_stars;
                                                    if(isset($meta->star_face))
                                                        $r_sub['star_face'] = $meta->star_face;
                                                    if(isset($meta->star_color))
                                                        $r_sub['star_color'] = $meta->star_color;
                                                }
                                                $rf = new Element_Rating("", "", $r_sub);
                                                $rf->render();
                                                    //echo  '<div class="rateit" id="rateit5" data-rateit-min="0" data-rateit-max="'.$value.'" data-rateit-value="'.$value.'" data-rateit-ispreset="true" data-rateit-readonly="true"></div>';
                                            }
                                            else { 
                                                if(function_exists('mb_strimwidth'))
                                                    echo mb_strimwidth($value, 0, 70, "...");
                                                else
                                                    echo $value;
                                            }
                                            ?>
                                        </td>

                                        <?php
                                    endfor;
                                    ?>
                                    <td><a href="?page=rm_submission_view&rm_submission_id=<?php echo $submission->submission_id; ?>"><?php echo RM_UI_Strings::get("VIEW"); ?></a></td>
                                </tr>

                                <?php
                            endforeach;
                        ?>
                        <?php
                    }elseif ($data->filter->searched)
                    {
                        ?>
                        <div class="rmnotice" style="max-width: 80%;">
                            <?php echo RM_UI_Strings::get('MSG_NO_SUBMISSION_MATCHED'); ?>
                            </div>
                    <?php
                    } else
                    {
                        ?>
                         <div class="rmnotice" style="max-width: 80%;">
                            <?php echo RM_UI_Strings::get('MSG_NO_SUBMISSION_SUB_MAN_INTERVAL'); ?>
                            </div>
    <?php }
    ?>
                </table>
            </form>
            <?php include RM_ADMIN_DIR.'views/template_rm_submission_legends.php'; ?>
        </div>
        <?php
        echo $data->filter->render_pagination();
    }else
    {
        ?><div class="rmnotice-container">
            <div class="rmnotice">
        <?php echo RM_UI_Strings::get('MSG_NO_SUBMISSION_SUB_MAN'); ?>
            </div>
        </div>
    <?php
}
$qry_str = '';
if(!empty($_GET)){
    foreach($_GET as $key=>$value){
        if($key!='custom_status_ind'){
            $qry_str.= $key.'='.$value.'&';
        }
    }
}
$qry_str = trim($qry_str,'&');
?>
    <pre class="rm-pre-wrapper-for-script-tags"><script>
    function apply_filter(url)
    {

       var filter= jQuery("#filter_options").val();
         if(filter != '' && filter != null)
       {
     window.location =  url+'admin.php?'+filter;
       }
    }
    function show_label_div()
     {
         jQuery("#add_filter_div").show();
     }
     function show_sidebar(val)
     {
         if(val == 'search')
         {
            jQuery("#filtering_sidebar").hide();
            jQuery(".rm-search-sidebar-filtertab").removeClass('active');
            jQuery("#searching_sidebar").show();
            jQuery(".rm-search-sidebar-searchtab").addClass('active');
         }
         else
         {
            jQuery("#searching_sidebar").hide();
            jQuery(".rm-search-sidebar-searchtab").removeClass('active');
            jQuery("#filtering_sidebar").show();
            jQuery(".rm-search-sidebar-filtertab").addClass('active');
         }
     }
    function delete_filter()
   {
      var filter= jQuery("#filter_options").val();
      if(filter != ''  && filter != null)
      {
      var data = {
 			'action': 'rm_delete_filter',
 			'url':filter
 		};
      jQuery.post(ajaxurl, data, function(response) {
          alert(response);
              location.reload(); 
                  
 		});
      }
   }
     function add_filter(url)
     {
        
         var name =jQuery("#filter_name").val();
         if(name == '')
             alert('Please povide filter name');
         else
         {
           var data = {
 			'action': 'rm_add_filter',
 			'name': name,
 			'url':url
 		};
      jQuery.post(ajaxurl, data, function(response) {
          
          if(response == 'NAME_EXIST')
              alert("<?php _e('Filter already exists! Please try with a different name.','registrationmagic-gold') ?>");
          
          else if(response == 'URL_EXIST')
              alert("<?php _e('This Search is already saved, please try with different search criteria.','registrationmagic-gold') ?>");
          else
              location.reload(); 
                  
 		});
             }
     }
     
     function rm_on_selected_submissions(){
         var selected_submission = jQuery("input.rm_checkbox_group:checked");
        if(selected_submission.length > 0) {   
            jQuery("#rm-delete-submission").removeClass("rm_deactivated"); 
            } 
            else 
            {
                jQuery("#rm-delete-submission").addClass("rm_deactivated");
            }                     
        }
    function rm_load_custom_status(){
        status_val = '';
        var status_arr = jQuery('.rm_custom_status_filter').val();
        //console.log(status_arr);
        if(status_arr && status_arr.length>0){
            status_val = status_arr.join(',');
        }
        
        var qry_str = '?<?php echo $qry_str; ?>';
        if(status_val!=''){
            var qry_str = '?<?php echo $qry_str; ?>&custom_status_ind='+status_val;
        }
        window.location = qry_str;
    }
     </script></pre>
            
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.min.css"/>

    <form id="rm_filter_form">
        <input type="hidden" name="filter_by_tags" id="filter_by_tags" />
    </form>
</div>

<?php 
/*
 * Enqueue autocomplete js/css files
 */
wp_enqueue_script('rm-tockenized-autocomplete', RM_BASE_URL. 'admin/js/script_rm_tokens.js', array(), null, false); 

?>