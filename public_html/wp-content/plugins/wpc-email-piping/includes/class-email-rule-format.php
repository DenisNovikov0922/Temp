<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WPSC_Ticket_Form_Field' ) ) :
    
    class WPSC_Email_rule_set {
        
        var $slug;
        var $type;
        var $label;
        var $extra_info;
				var $status;
				var $options;
        var $required;
        var $width;
        var $col_class;
				var $visibility;
				var $visibility_conditions;
        
        function print_field_format($field,$term_id){
          
          $this->slug = $field->slug;
          $this->term_id=$term_id;
          $this->type = get_term_meta( $field->term_id, 'wpsc_tf_type', true);
          $this->label = get_term_meta( $field->term_id, 'wpsc_tf_label', true);
          $this->extra_info = get_term_meta( $field->term_id, 'wpsc_tf_extra_info', true);
					$this->status = get_term_meta( $field->term_id, 'wpsc_tf_status', true);
					$this->options = get_term_meta( $field->term_id, 'wpsc_tf_options', true);
          $this->required = get_term_meta( $field->term_id, 'wpsc_tf_required', true);
          $this->width = get_term_meta( $field->term_id, 'wpsc_tf_width', true);
					$this->visibility = get_term_meta( $field->term_id, 'wpsc_tf_visibility', true);
					$this->visibility_conditions = is_array($this->visibility) && $this->visibility ? implode(';;', $this->visibility) : '';
					$this->visibility_conditions = str_replace('"','&quot;',$this->visibility_conditions);
					$this->col_class = 'col-sm-12';
          
          if ($this->type=='0') {
            switch ($field->slug) {
              
              case 'ticket_category':
                if ($this->status == '1') {
                	$this->print_ticket_category($field);
                }
                break;
								
							case 'ticket_priority':
                if ($this->status == '1') {
									$this->print_ticket_priority($field);
								}
                break;
              
              default:
                do_action('wpsc_print_default_form_field', $field, $this);
                break;
            }
						
          } else {
						
						switch ($this->type) {
							
							case '1':
								$this->print_text_field($field);
								break;
								
							case '2':
								$this->print_drop_down($field);
								break;
								
							case '3':
								$this->print_checkbox($field);
								break;
								
							case '4':
								$this->print_radio_btn($field);
								break;
								
							case '5':
								$this->print_textarea($field);
								break;
								
							case '6':
								$this->print_date($field);
								break;
								
							case '7':
								$this->print_url($field);
								break;
								
							case '8':
								$this->print_email($field);
								break;
								
							case '9':
								$this->print_numberonly($field);
								break;
							
							case '18':
								$this->print_date_time($field);
								break;

							case '21':
								$this->print_time($field);
								break;	

							default:
								do_action('wpsc_print_ep_rules_custom_form_field', $field, $this);
								break;
						}
						
					}
          
        }
        
				function print_text_field($field){
					global $wpscfunction;					
					$label = get_term_meta($this->term_id, $field->slug, true );
          ?>
					<div class="form-group">
				    <label for="wpsc_ep_has_words"><?php echo $this->label;?></label>
				    <p class="help-block"><?php echo $this->extra_info;?></p>
				    <input type="text" id="<?php echo $this->slug;?>" class="form-control wpsc_textfield" name="<?php echo $this->slug;?>" autocomplete="off" value="<?php echo $label?>">
				  </div>
          <?php
        }
				
				function print_drop_down($field){
					global $wpscfunction;					
					$label = get_term_meta($this->term_id, $field->slug, true );
					?>					
					<div class="form-group">
				    <label for="wpsc_ep_has_words"><?php echo $this->label;?></label>
				    <p class="help-block"><?php echo $this->extra_info;?></p>
						<select id="<?php echo $this->slug;?>" class="form-control wpsc_drop_down" name="<?php echo $this->slug;?>">
			        <option value=""></option>
							<?php
							foreach ( $this->options as $key => $val ) :
									$value = trim(stripcslashes($val));
									$selected = $value == $label ? 'selected="selected"' : '';
                    ?>                    
										<option <?php echo $selected?> value="<?php echo $value?>"><?php echo stripcslashes($val)?></option>										
                    <?php
              endforeach;
			        ?>
			      </select>
				  </div>
          <?php
				}
				
				function print_checkbox($field){
					global $wpscfunction;					
					$label = get_term_meta($this->term_id, $field->slug);          					
					?>					
					<div class="form-group">
				    <label for="wpsc_ep_has_words"><?php echo $this->label;?></label>
				    <p class="help-block"><?php echo $this->extra_info;?></p>
						<?php            
						foreach ( $this->options as $key => $value ) :              
							$checked = in_array($value,$label) ? 'checked="checked"' : '';                             
							?>
							<div class="row">
				        <div class="col-sm-12" style="margin-bottom:10px; display:flex;">
				          <div style="width:25px;"><input <?php echo $checked ?> type="checkbox" class="wpsc_checkbox" name="<?php echo $this->slug?>[]" value="<?php echo str_replace('"','&quot;',$value)?>"></div>
				          <div style="padding-top:3px;"><?php echo $value?></div>
				        </div>
              </div>
							<?php
						endforeach;
						?>
				  </div>
          <?php
				}
				
				function print_radio_btn($field){
					global $wpscfunction;					
					$label = get_term_meta($this->term_id, $field->slug, true );          
					?>					
					<div class="form-group">
				    <label for="wpsc_ep_has_words"><?php echo $this->label;?></label>
				    <p class="help-block"><?php echo $this->extra_info;?></p>
						<?php
						foreach ( $this->options as $key => $value ) :
							$checked = $value == $label ? 'checked="checked"' : '';
              ?>
							<div class="row">
				        <div class="col-sm-12" style="margin-bottom:10px; display:flex;">
				          <div style="width:25px;"><input <?php echo $checked ?>type="radio" class="wpsc_radio_btn" name="<?php echo $this->slug?>" value="<?php echo str_replace('"','&quot;',$value)?>"></div>
				          <div style="padding-top:3px;"><?php echo $value?></div>
				        </div>
              </div>
							<?php
						endforeach;
						?>
				  </div>
          <?php
				}
				
				function print_textarea($field){
					global $wpscfunction;					
					$label = get_term_meta($this->term_id, $field->slug, true );
					$data  = stripslashes($label);
					?>					
					<div class="form-group">
				    <label for="wpsc_ep_has_words"><?php echo $this->label;?></label>
				    <p class="help-block"><?php echo $this->extra_info;?></p>
				   <textarea id="<?php echo $this->slug;?>" class="wpsc_textarea" name="<?php echo $this->slug;?>"><?php echo $data ?></textarea>
				  </div>
          <?php
				}
				
				function print_date($field){
					global $wpscfunction;				
					$value = get_term_meta($this->term_id, $field->slug,true);	
					$date='';
					$date_range = get_term_meta ($field->term_id, 'wpsc_date_range',true);
					if( strlen($value) != 0 ){
						$date = $wpscfunction->datetimeToCalenderFormat($value);
					}
					$dr = "";
					if($date_range == 'future'){
						$dr = "minDate: 0";
					}elseif($date_range == 'past'){
						$dr = "maxDate: 0";
					}
                    
					?>
					<div class="form-group">
				    <label for="wpsc_ep_has_words"><?php echo $this->label;?></label>
				    <p class="help-block"><?php echo $this->extra_info;?></p>
				     <input type="text" id="<?php echo $this->slug;?>" class="form-control wpsc_date" name="<?php echo $this->slug;?>" autocomplete="off" value="<?php  echo $date;?>" onkeypress='return false'>
				  </div>
					<script type="text/javascript">
						jQuery(document).ready(function(){
							jQuery( "#<?php echo $this->slug?>").datepicker({
					        dateFormat : '<?php echo get_option('wpsc_calender_date_format')?>',
					        showAnim : 'slideDown',
					        changeMonth: true,
					        changeYear: true,
					        yearRange: "-50:+50",
							<?php echo $dr?>
					    });
						});
					</script>
          <?php
				}
				
				function print_url($field){
					global $wpscfunction;					
					$label = get_term_meta($this->term_id, $field->slug, true );
					?>
					<div class="form-group">
				    <label for="wpsc_ep_has_words"><?php echo $this->label;?></label>
				    <p class="help-block"><?php echo $this->extra_info;?></p>
				       <input type="text" id="<?php echo $this->slug;?>" class="form-control wpsc_url" name="<?php echo $this->slug;?>" autocomplete="off" value="<?php echo $label ?>">
				  </div>
          <?php
				}
				
				function print_email($field){
					global $wpscfunction;					
					$label = get_term_meta($this->term_id, $field->slug, true );
					?>
					<div class="form-group">
				    <label for="wpsc_ep_has_words"><?php echo $this->label;?></label>
				    <p class="help-block"><?php echo $this->extra_info;?></p>
				       <input type="text" id="<?php echo $this->slug;?>" class="form-control wpsc_email" name="<?php echo $this->slug;?>" autocomplete="off" value="<?php echo $label ?>">
				  </div>
          <?php
				}
				
				function print_numberonly($field){
					global $wpscfunction;					
					$label = get_term_meta($this->term_id, $field->slug, true );
					?>
					<div class="form-group">
				    <label for="wpsc_ep_has_words"><?php echo $this->label;?></label>
				    <p class="help-block"><?php echo $this->extra_info;?></p>
				       <input type="number" id="<?php echo $this->slug;?>" class="form-control wpsc_numberonly" name="<?php echo $this->slug;?>" autocomplete="off" value="<?php echo $label ?>">
				  </div>
          <?php
				}
				
				function print_ticket_category($field){
					$selected_category = get_term_meta($term_id,'category',true);	
					?>
					<div class="form-group">
						<label for="wpsc_ep_has_words"><?php echo $this->label;?></label>
				    <p class="help-block"><?php echo $this->extra_info;?></p>
						<select class="form-control" name="category" >
							<?php
							$categories = get_terms([
								'taxonomy'   => 'wpsc_categories',
								'hide_empty' => false,
								'orderby'    => 'meta_value_num',
								'order'    	 => 'ASC',
								'meta_query' => array('order_clause' => array('key' => 'wpsc_category_load_order')),
							]);
							foreach ( $categories as $category ) :
								$selected = $selected_category == $category->term_id ? 'selected="selected"' : '';
								echo '<option '.$selected.' value="'.$selected_category.'">'.$category->name.'</option>';
							endforeach;
							?>
						</select>
					</div>
          <?php
				}
				
				function print_ticket_priority($field){
					$selected_priority = get_term_meta($this->term_id,'priority',true);
					?>
					<div class="form-group">
						<label for="wpsc_ep_has_words"><?php echo $this->label;?></label>
				    <p class="help-block"><?php echo $this->extra_info;?></p>
 			 			<p class="help-block"><?php echo $slected_priority->extra_info;?></p>
		 			 		<select  class="form-control" name="priority">
		 			 		 <?php
		 			 		 $priorities = get_terms([
		 			 			 'taxonomy'   => 'wpsc_priorities',
		 			 			 'hide_empty' => false,
		 			 			 'orderby'    => 'meta_value_num',
		 			 			 'order'    	 => 'ASC',
		 			 			 'meta_query' => array('order_clause' => array('key' => 'wpsc_priority_load_order')),
		 			 		 ]);
		 			 		 foreach ( $priorities as $priority ) :
		 			 			 $selected = $slected_priority == $priority->term_id ? 'selected="selected"' : '';
		 			 			 echo '<option '.$selected.' value="'.$slected_priority.'">'.$priority->name.'</option>';
		 			 		 endforeach;
		 			 		 ?>
		 			 	 </select>
				  </div>
          <?php
				}
		
			function print_time($field){
				$wpsc_appearance_create_ticket = get_option('wpsc_create_ticket');
				$extra_info_css = 'color:'.$wpsc_appearance_create_ticket['wpsc_extra_info_text_color'].' !important;';
				$label = get_option('wpsc_custom_fields_localize');
				$extra_info = get_option('wpsc_custom_fields_extra_info');
				$value = get_term_meta($this->term_id, $field->slug, true );
				$time_format = get_term_meta($field->term_id,'wpsc_time_format',true);
				$time ='';
				if( strlen($value) != 0 ){
					if($time_format == '12'){
						$time = date("h:i:s a", strtotime($value));
					}else{
						$time = $value;
					}
				}
				?>
				<div class="form-group">
					<label class="wpsc_ct_field_label" for="<?php echo $this->slug;?>">
					<?php echo $label['custom_fields_'.$field->term_id];?> <?php echo $this->required? '<span style="color:red;">*</span>':'';?>
					</label>
					<?php if($extra_info['custom_fields_extra_info_'.$field->term_id]){?><p class="help-block" style="<?php echo $extra_info_css?>"><?php echo $extra_info['custom_fields_extra_info_'.$field->term_id];?></p><?php }?>
					<input type="text" id="<?php echo $this->slug;?>" onkeypress='return false' class="form-control <?php echo $this->slug ?>" name="<?php echo $this->slug;?>" autocomplete="off" value=" <?php echo $time ?> ">
				</div>
				<script type="text/javascript">
					jQuery(document).ready(function(){
						jQuery('.<?php echo $this->slug ?>').timepicker({
							<?php 
							if($time_format == '12'){
								?>
								timeFormat:'hh:mm:ss tt',
								<?php	
							}else{
								?>
								timeFormat:'HH:mm:ss'
								<?php
							}
							?>
						});	
					});
				
				</script>
				<?php
			}
			
			function print_date_time($field){
				$label = get_option('wpsc_custom_fields_localize');
				$extra_info = get_option('wpsc_custom_fields_extra_info');
				$value = get_term_meta($this->term_id, $field->slug, true );
				?>
			  <div class="form-group">
					<label class="wpsc_ct_field_label" for="<?php echo $this->slug;?>">
						<?php echo $label['custom_fields_'.$field->term_id];?> <?php echo $this->required? '<span style="color:red;">*</span>':'';?>
					</label>
					<?php if($extra_info['custom_fields_extra_info_'.$field->term_id]){?><p class="help-block"><?php echo $extra_info['custom_fields_extra_info_'.$field->term_id];?></p><?php }?>
					<input type="text" id="<?php echo $this->slug;?>" class="form-control wpsc_datetime" name="<?php echo $this->slug;?>" autocomplete="off" value="<?php echo $value ?> " onkeypress='return false'>
				</div>
				<script type="text/javascript">
					jQuery('.wpsc_datetime').datetimepicker({
						 dateFormat : '<?php echo get_option('wpsc_calender_date_format')?>',
					 	showAnim : 'slideDown',
				     	changeMonth: true,
						changeYear: true,
			 			timeFormat: 'HH:mm:ss'
		 			});
				
				</script>
				<?php
			}
	}		
    
endif;