<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wpscfunction;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {exit;}

$term_id = isset($_POST) && isset($_POST['term_id']) ? intval($_POST['term_id']) : 0;
if(!$term_id) exit;

$condition = get_term_by('id',$term_id,'wpsc_caa');
$agent_term_ids = get_term_meta( $condition->term_id, 'agent_ids', true);

ob_start();
?>
 <form id="wpsc_frm_add_agent" method="post" action="javascript:wpsc_set_edit_condition();">
   <div class="form-group">
      <label for="wpsc_caa_title"><?php _e('Title','wpsc-caa');?></label>
      <p class="help-block"><?php _e('Title to show in Agent list. It will be easier to know what this title is for.','wpsc-caa');?></p>
      <input id="wpsc_caa_title" class="form-control" name="wpsc_caa_title" value="<?php echo $condition->name?>" required />
    </div>
		<div class="form-group">
	    <label for="wpsc_agent_name"><?php _e('Select Agents','wpsc-caa');?></label>
	    <p class="help-block"><?php _e('Insert Agent Name.','wpsc-caa');?></p>
			<div id="assigned_agent">
			  <div class="form-group wpsc_display_assign_agent ">
				    <input class="form-control form-control wpsc_agent_name ui-autocomplete-input" name="assigned_agent"  type="text" autocomplete="off" placeholder="<?php _e('Search agent ...','wpsc-caa')?>" />
						<ui class="wpsp_filter_display_container"></ui>
				</div>
		  </div>
		</div>
		<div id="assigned_agent" class="form-group col-md-12">
			<?php
			   foreach ( $agent_term_ids as $agent ) {
					 $agent_name = get_term_meta( $agent, 'label', true);
           if($agent && $agent_name):
			     ?>
						<div class="form-group wpsp_filter_display_element wpsc_assign_agents ">
							<div class="flex-container" style="padding:6px;font-size:1.0em;">
								<?php echo $agent_name?><span onclick="wpsc_remove_filter(this);"><i class="fa fa-times"></i></span>
								  <input type="hidden" name="assigned_agent[]" value="<?php echo $agent?>" />
							</div>
						</div>
			    <?php
					endif;
				}
			?>
	 </div>
    <div class="row">
      <ul id="wpsc_tf_condition_container" class="wpsp_filter_display_container">
        <?php
        $conditions = get_term_meta($term_id,'conditions',true);
				$wpscfunction->load_conditions_ui('wpsc_edit_agent_rule_conditions',$conditions);
        ?>
      </ul>
    </div>
    
  </div>
  <input type="hidden" name="action" value="wpsc_set_edit_condition" />
  <input type="hidden" name="term_id" value="<?php echo $term_id?>" />
 </from>
 <script>
 jQuery(document).ready(function(){

 	jQuery( ".wpsc_agent_name" ).autocomplete({
 			minLength: 1,
 			appendTo: jQuery('.wpsc_agent_name').parent(),
 			source: function( request, response ) {
 				var term = request.term;
 				request = {
 					action: 'wpsc_tickets',
 					setting_action : 'filter_autocomplete',
 					term : term,
 					field : 'assigned_agent',
 				}
 				jQuery.getJSON( wpsc_admin.ajax_url, request, function( data, status, xhr ) {
 					response( data );
 				});
 			},
 			select: function (event, ui) {
 				var html_str = '<li class="wpsp_filter_display_element">'
 												+'<div class="flex-container">'
 													+'<div class="wpsp_filter_display_text">'
 														+ui.item.label
 														+'<input type="hidden" name="assigned_agent[]" value="'+ui.item.flag_val+'">'
 													+'</div>'
 													+'<div class="wpsp_filter_display_remove" onclick="wpsc_remove_filter(this);"><i class="fa fa-times"></i></div>'
 												+'</div>'
 											+'</li>';
 				jQuery('#assigned_agent .wpsp_filter_display_container').append(html_str);
 			  jQuery(this).val(''); return false;
 			}
 	});
 });
 </script>
<?php
$body = ob_get_clean();
ob_start();
?>
<button type="button" class="btn wpsc_popup_close" onclick="wpsc_modal_close();"><?php _e('Close','wpsc');?></button>
<button type="button" class="btn wpsc_popup_action" onclick="jQuery('#wpsc_frm_add_agent').submit();"><?php _e('Submit','wpsc');?></button>
<?php
$footer = ob_get_clean();

$output = array(
  'body'   => $body,
  'footer' => $footer
);

echo json_encode($output);
