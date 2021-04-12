<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wpdb,$wpscfunction;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}

$wpsc_sf_thankyou_text = stripslashes(get_option('wpsc_sf_thankyou_text'));
$wpsc_sf_age           = get_option('wpsc_sf_age','0');

$ratings = get_terms([
	'taxonomy'   => 'wpsc_sf_rating',
	'hide_empty' => false,
	'orderby'    => 'meta_value_num',
	'order'    	 => 'ASC',
	'meta_query' => array('order_clause' => array('key' => 'load_order')),
]);

$directionality = $wpscfunction->check_rtl();
?>
<h4><?php _e('Satisfaction Survey','wpsc-sf');?></h4><br>

<strong>
	<?php _e('Ratings','wpsc-sf');?>
	<button style="margin-left:10px;" class="btn btn-success btn-sm" id="wpsc_add_rating_btn" onclick="wpsc_get_add_rating();"><?php _e('+Add New','wpsc-sf');?></button>
</strong>

<div class="wpsc_padding_space"></div>

<ul class="wpsc-sortable">
	<?php foreach ( $ratings as $rating ) :
		$color = get_term_meta( $rating->term_id, 'color', true);
    ?>
		<li class="ui-state-default" data-id="<?php echo $rating->term_id?>">
			<div class="wpsc-flex-container" style="background-color:<?php echo $color?>;color:#ffffff;">
				<div class="wpsc-sortable-handle"><i class="fa fa-bars"></i></div>
				<div class="wpsc-sortable-label"><strong><?php echo $rating->name?></strong></div>
				<div class="wpsc-sortable-edit" onclick="wpsc_get_edit_rating(<?php echo $rating->term_id?>);"><i class="fa fa-edit"></i></div>
				<div class="wpsc-sortable-delete" onclick="wpsc_delete_rating(<?php echo $rating->term_id?>);"><i class="fa fa-trash"></i></div>
			</div>
		</li>
	<?php endforeach;?>
</ul>

<form id="frm_sf_settings" action="javascript:wpsc_set_sf_settings();" method="post">
	
	<h4 style="margin: 50px 0 20px;"><?php _e('General Settings','wpsc-sf');?></h4>
	
	<div class="form-group">
	  <label for="wpsc_sf_page"><?php _e('Survey Page','wpsc-sf');?></label>
	  <p class="help-block"><?php _e('Select page for survey where user will submit ratings and reviews for ticket. This should have shortcode [wpsc_sf]','wpsc-sf');?></p>
	  <select class="form-control" name="wpsc_sf_page" id="wpsc_sf_page">
			<option value=""></option>
			<?php
			$args = array(
				'sort_order'  => 'asc',
				'sort_column' => 'post_title',
				'post_type'   => 'page',
				'post_status' => 'publish'
			);
			$sf_page_id = get_option('wpsc_sf_page');
			$pages = get_pages( $args );
			foreach ( $pages as $page ) :
				$selected = $sf_page_id == $page->ID ? 'selected="selected"' : '';
				echo '<option '.$selected.' value="'.$page->ID.'">'.$page->post_title.'</option>';
			endforeach;
			?>
		</select>
	</div>
	
	<div class="form-group">
		<label for="wpsc_sf_thankyou_text"><?php _e('Thank you text','wpsc-sf');?></label>
		<input type="text" class="form-control" name="wpsc_sf_thankyou_text" id="wpsc_sf_thankyou_text" value="<?php echo $wpsc_sf_thankyou_text?>" />
	</div>
	
	<button type="submit" class="btn btn-success" id="wpsc_rating_save_changes_btn"><?php _e('Save Changes','wpsc-sf');?></button>
	<img class="wpsc_submit_wait" style="display:none;" src="<?php echo WPSC_PLUGIN_URL.'asset/images/ajax-loader@2x.gif';?>">
	<input type="hidden" name="action" value="wpsc_sf_save_settings" />
	
</form>

<script>
	
	jQuery(function(){
    jQuery( ".wpsc-sortable" ).sortable({ handle: '.wpsc-sortable-handle' });
		jQuery( ".wpsc-sortable" ).on("sortupdate",function(event,ui){
			var ids = jQuery(this).sortable( "toArray", {attribute: 'data-id'} );
			var data = {
		    action: 'wpsc_set_rating_order',
				rating_ids : ids
		  };
			jQuery.post(wpsc_admin.ajax_url, data, function(response_str) {
				var response = JSON.parse(response_str);
		    if (response.sucess_status=='1') {
		      jQuery('#wpsc_alert_success .wpsc_alert_text').text(response.messege);
		    }
		    jQuery('#wpsc_alert_success').slideDown('fast',function(){});
		    setTimeout(function(){ jQuery('#wpsc_alert_success').slideUp('fast',function(){}); }, 3000);
		  });
		});
	});
	
	function wpsc_get_add_rating(){
		wpsc_modal_open('<?php _e('Add New Rating','wpsc-sf')?>');
		var data = {
			action: 'wpsc_get_add_rating',
		};
		jQuery.post(wpsc_admin.ajax_url, data, function(response_str) {
			var response = JSON.parse(response_str);
			jQuery('#wpsc_popup_body').html(response.body);
			jQuery('#wpsc_popup_footer').html(response.footer);
			jQuery('#wpsc_rating_name').focus();
		});
	}
	
	function wpsc_set_add_rating(){
		var rating_name = jQuery('#wpsc_rating_name').val().trim();
		if (rating_name.length == 0) {
			jQuery('#wpsc_rating_name').val('').focus();
			return;
		}
		var rating_color = jQuery('#wpsc_rating_color').val().trim();
		if (rating_color.length == 0) {
			rating_color = '#ffffff';
		}
		jQuery('.wpsc_popup_action').text('<?php _e('Please wait ...','wpsc-sf')?>');
		jQuery('.wpsc_popup_action, #wpsc_popup_body input').attr("disabled", "disabled");
		var data = {
			action: 'wpsc_set_add_rating',
			rating_name : rating_name,
			rating_color: rating_color,
		};
		jQuery.post(wpsc_admin.ajax_url, data, function(response_str) {
			wpsc_modal_close();
			var response = JSON.parse(response_str);
			if (response.sucess_status=='1') {
				jQuery('#wpsc_alert_success .wpsc_alert_text').text(response.messege);
				jQuery('#wpsc_alert_success').slideDown('fast',function(){});
				setTimeout(function(){ jQuery('#wpsc_alert_success').slideUp('fast',function(){}); }, 3000);
				wpsc_get_sf_settings();
			} else {
				jQuery('#wpsc_alert_error .wpsc_alert_text').text(response.messege);
				jQuery('#wpsc_alert_error').slideDown('fast',function(){});
				setTimeout(function(){ jQuery('#wpsc_alert_error').slideUp('fast',function(){}); }, 3000);
			}
		});
	}
	
	function wpsc_get_edit_rating(rating_id){
		wpsc_modal_open('<?php _e('Edit Rating','wpsc-sf')?>');
		var data = {
			action: 'wpsc_get_edit_rating',
			rating_id : rating_id
		};
		jQuery.post(wpsc_admin.ajax_url, data, function(response_str) {
			var response = JSON.parse(response_str);
			jQuery('#wpsc_popup_body').html(response.body);
			jQuery('#wpsc_popup_footer').html(response.footer);
			jQuery('#wpsc_rating_name').focus();
		});
	}
	
	function wpsc_set_edit_rating(rating_id){
    var rating_name = jQuery('#wpsc_rating_name').val().trim();
		if (rating_name.length == 0) {
			jQuery('#wpsc_rating_name').val('').focus();
			return;
		}
		var rating_color = jQuery('#wpsc_rating_color').val().trim();
		if (rating_color.length == 0) {
			rating_color = '#ffffff';
		}
		jQuery('.wpsc_popup_action').text('<?php _e('Please wait ...','wpsc-sf')?>');
		jQuery('.wpsc_popup_action, #wpsc_popup_body input').attr("disabled", "disabled");
		var data = {
			action: 'wpsc_set_edit_rating',
      rating_id : rating_id,
			rating_name : rating_name,
			rating_color: rating_color,
		};
		jQuery.post(wpsc_admin.ajax_url, data, function(response_str) {
			wpsc_modal_close();
			var response = JSON.parse(response_str);
			if (response.sucess_status=='1') {
				jQuery('#wpsc_alert_success .wpsc_alert_text').text(response.messege);
				jQuery('#wpsc_alert_success').slideDown('fast',function(){});
				setTimeout(function(){ jQuery('#wpsc_alert_success').slideUp('fast',function(){}); }, 3000);
				wpsc_get_sf_settings();
			} else {
				jQuery('#wpsc_alert_error .wpsc_alert_text').text(response.messege);
				jQuery('#wpsc_alert_error').slideDown('fast',function(){});
				setTimeout(function(){ jQuery('#wpsc_alert_error').slideUp('fast',function(){}); }, 3000);
			}
		});
	}
	
	function wpsc_delete_rating(rating_id){
		var flag = confirm('<?php _e('Are you sure?','wpsc-sf')?>');
		if (flag) {
			var data = {
				action: 'wpsc_delete_rating',
				rating_id : rating_id
			};
			jQuery.post(wpsc_admin.ajax_url, data, function(response_str) {
				var response = JSON.parse(response_str);
				if (response.sucess_status=='1') {
					jQuery('#wpsc_alert_success .wpsc_alert_text').text(response.messege);
					jQuery('#wpsc_alert_success').slideDown('fast',function(){});
					setTimeout(function(){ jQuery('#wpsc_alert_success').slideUp('fast',function(){}); }, 3000);
					wpsc_get_sf_settings();
				} else {
					jQuery('#wpsc_alert_error .wpsc_alert_text').text(response.messege);
					jQuery('#wpsc_alert_error').slideDown('fast',function(){});
					setTimeout(function(){ jQuery('#wpsc_alert_error').slideUp('fast',function(){}); }, 3000);
				}
			});
		}
	}
	
	function wpsc_set_sf_settings(){
	  
	  jQuery('.wpsc_submit_wait').show();
	  var dataform = new FormData(jQuery('#frm_sf_settings')[0]);
	  
	  jQuery.ajax({
	    url: wpsc_admin.ajax_url,
	    type: 'POST',
	    data: dataform,
	    processData: false,
	    contentType: false
	  })
	  
	  .done(function (response_str) {
	    var response = JSON.parse(response_str);
	    jQuery('.wpsc_submit_wait').hide();
	    if (response.sucess_status=='1') {
	      jQuery('#wpsc_alert_success .wpsc_alert_text').text(response.messege);
	    }
	    jQuery('#wpsc_alert_success').slideDown('fast',function(){});
	    setTimeout(function(){ jQuery('#wpsc_alert_success').slideUp('fast',function(){}); }, 3000);
	  });
	  
	}
	
	tinymce.remove();
	tinymce.init({ 
	  selector:'#wpsc_sf_email_body',
	  body_id: 'thankyou_body',
	  directionality : '<?php echo $directionality; ?>',
	  menubar: false,
		statusbar: false,
	  height : '100',
	  plugins: [
	      'lists link image directionality'
	  ],
	  image_advtab: true,
	  toolbar: 'bold italic underline blockquote | alignleft aligncenter alignright | bullist numlist | rtl | link image',
	  branding: false,
	  autoresize_bottom_margin: 20,
	  browser_spellcheck : true,
	  relative_urls : false,
	  remove_script_host : false,
	  convert_urls : true,
		setup: function (editor) {
	  }
	});
	
</script>
