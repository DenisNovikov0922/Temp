<?php
if(!$data->is_user) return;
?>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<div class="rm-submission" id="rm_my_details_tab">
            <div class="rm-user-details-card">
                <div class="rm-user-fields-container1">
                    <?php
                    if ($data->user->first_name)
                    {
                        ?>
                        <div class="rm-user-field-row">
                            <div class="rm-user-field-label"><?php echo RM_UI_Strings::get('FIELD_TYPE_FNAME'); ?>:</div>
                            <div class="rm-user-field-value"><?php echo $data->user->first_name; ?></div>
                        </div>
                        <?php
                    }
                    if ($data->user->last_name)
                    {
                        ?>

                        <div class="rm-user-field-row">
                            <div class="rm-user-field-label"><?php echo RM_UI_Strings::get('FIELD_TYPE_LNAME'); ?>:</div>
                            <div class="rm-user-field-value"><?php echo $data->user->last_name; ?></div>
                        </div>
                        <?php
                    }
                    if ($data->user->description)
                    {
                        ?>

                        <div class="rm-user-field-row">
                            <div class="rm-user-field-label"><?php echo RM_UI_Strings::get('LABEL_BIO'); ?>:</div>
                            <div class="rm-user-field-value"><?php echo $data->user->description; ?></div>
                        </div>
                        <?php
                    }
                    if ($data->user->user_email)
                    {
                        ?>

                        <div class="rm-user-field-row">
                            <div class="rm-user-field-label"><?php echo RM_UI_Strings::get('LABEL_EMAIL'); ?>:</div>
                            <div class="rm-user-field-value"><?php echo $data->user->user_email; ?></div>
                        </div>
                        <?php
                    }
                    if ($data->user->sec_email)
                    {
                        ?>

                        <div class="rm-user-field-row">
                            <div class="rm-user-field-label"><?php echo RM_UI_Strings::get('LABEL_SECEMAIL'); ?>:</div>
                            <div class="rm-user-field-value"><?php echo $data->user->sec_email; ?></div>
                        </div>
                        <?php
                    }
                    if ($data->user->nickname)
                    {
                        ?>

                        <div class="rm-user-field-row">
                            <div class="rm-user-field-label"><?php echo RM_UI_Strings::get('FIELD_TYPE_NICKNAME'); ?>:</div>
                            <div class="rm-user-field-value"><?php echo $data->user->nickname; ?></div>
                        </div>
                        <?php
                    }
                    if ($data->user->user_url)
                    {
                        ?>

                        <div class="rm-user-field-row">
                            <div class="rm-user-field-label"><?php echo RM_UI_Strings::get('FIELD_TYPE_WEBSITE'); ?>:</div>
                            <div class="rm-user-field-value"><?php echo $data->user->user_url; ?></div>
                        </div>
                        <?php
                    }
                    if (is_array($data->custom_fields) || is_object($data->custom_fields))
                        foreach ($data->custom_fields as $field_id => $sub)
                        {
                            $key = $sub->label;
                            $meta = $sub->value;
                            $sub_original = $sub;
                            if(!isset($sub->type)){
                                $sub->type = '';
                            }

                            $meta = RM_Utilities::strip_slash_array(maybe_unserialize($meta));
                            ?>
                            <div class="rm-user-field-row">

                                <div class="rm-user-field-label"><?php echo $key; ?></div>
                                <div class="rm-user-field-value">
                                    <?php
                                    if (is_array($meta) || is_object($meta)) {
                                        if (isset($meta['rm_field_type']) && $meta['rm_field_type'] == 'File') {
                                            unset($meta['rm_field_type']);

                                            foreach ($meta as $sub) {

                                                $att_path = get_attached_file($sub);
                                                $att_url = wp_get_attachment_url($sub);
                                                ?>
                                                <div class="rm-submission-attachment">
                                                    <?php echo wp_get_attachment_link($sub, 'thumbnail', false, true, false); ?>
                                                    <div class="rm-submission-attachment-field"><?php echo basename($att_path); ?></div>
                                                    <div class="rm-submission-attachment-field"><a href="<?php echo $att_url; ?>"><?php echo RM_UI_Strings::get('LABEL_DOWNLOAD'); ?></a></div>
                                                </div>

                                                <?php
                                            }
                                        } elseif (isset($meta['rm_field_type']) && $meta['rm_field_type'] == 'Address') {
                                            $sub = $meta['original'] . '<br/>';
                                            if (count($meta) === 8) {
                                                $sub .= '<b>Street Address</b> : ' . $meta['st_number'] . ', ' . $meta['st_route'] . '<br/>';
                                                $sub .= '<b>City</b> : ' . $meta['city'] . '<br/>';
                                                $sub .= '<b>State</b> : ' . $meta['state'] . '<br/>';
                                                $sub .= '<b>Zip code</b> : ' . $meta['zip'] . '<br/>';
                                                $sub .= '<b>Country</b> : ' . $meta['country'];
                                            }
                                                echo $sub;
                                        } elseif ($sub->type == 'Time') {                                  
                                    echo $meta['time'].", Timezone: ".$meta['timezone'];
                                } elseif ($sub->type == 'Checkbox') {   
                                    echo implode(', ',RM_Utilities::get_lable_for_option($field_id, $meta));
                                } else {
                                            $sub = implode(', ', $meta);
                                            echo $sub;
                                        }
                                    } else {
                                        if($sub->type=='Rating')
                                        {
                                           echo RM_Utilities::enqueue_external_scripts('script_rm_rating', RM_BASE_URL . 'public/js/rating3/jquery.rateit.js');
                                           $r_sub = array('value' => $sub->value,
                                                   'readonly' => 1,
                                                   'max_stars' => 5,
                                                   'star_face' => 'star',
                                                   'star_color' => 'FBC326');
                                            if(isset($sub->meta) && is_object($sub->meta)) {
                                                if(isset($sub->meta->max_stars))
                                                    $r_sub['max_stars'] = $sub->meta->max_stars;
                                                if(isset($sub->meta->star_face))
                                                    $r_sub['star_face'] = $sub->meta->star_face;
                                                if(isset($sub->meta->star_color))
                                                    $r_sub['star_color'] = $sub->meta->star_color;
                                            }
                                            $rf = new Element_Rating("", "", $r_sub);
                                            $rf->render();                 
                                 
                                        }
                                        elseif ($sub->type == 'Radio' || $sub->type == 'Select') {   
                                            echo RM_Utilities::get_lable_for_option($field_id, $meta);
                                        }
                                        else
                                        echo $meta;
                                    }
                                    ?>
                                </div>
                            </div>
                    <?php
                        }
                    ?>
                </div>
            </div>
    </div>
<style>
    #rm_my_details_tab .rm-user-field-row:nth-child(1) { border-top: 2px solid #e5e5e5;}
</style>