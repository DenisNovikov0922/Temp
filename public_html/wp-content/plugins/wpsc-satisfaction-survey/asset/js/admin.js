function get_ratings_report(){
  jQuery('.wpsc_setting_pills li').removeClass('active');
  jQuery('#wpsc_rp_rating_reports').addClass('active');
  jQuery('.wpsc_setting_col2').html(wpsc_admin.loading_html)
  var data = {
    action: 'get_ratings_report',
  };
  jQuery.post(wpsc_admin.ajax_url, data, function(response_str) {
    jQuery('.wpsc_report_setting_pill').html(response_str);
  });
}

function wpsc_sf_report_graph(){
  jQuery('#wpsc_sf_graph').html('');
  var dataform = new FormData(jQuery('#frm_sf_reports')[0]);
  date_filter =  jQuery('#wpsc_sf_month_filters').val();
  dataform.append('date_filter',date_filter);
  var custom_date_start = jQuery('#wpsc_sf_custom_date_start').val();
  var custom_date_end   = jQuery('#wpsc_sf_custom_date_end').val();
  dataform.append('custom_date_start',custom_date_start);
  dataform.append('custom_date_end',custom_date_end);
  jQuery('#wpsc_sf_graph').html(wpsc_admin.loading_html);
  jQuery.ajax({
    url: wpsc_admin.ajax_url,
    type: 'POST',
    data: dataform,
    processData: false,
    contentType: false
  })
  .done(function (response_str) {
    jQuery('#wpsc_sf_graph').html(response_str);
  });
}

function wpsc_get_sf_email_notification_setting(){

  jQuery('.wpsc_setting_pills li').removeClass('active');
  jQuery('#wpsc_sf_ticket_notifications').addClass('active');
  jQuery('.wpsc_setting_col2').html(wpsc_admin.loading_html);
  
  var data = {
    action: 'wpsc_sf_email_notification_settings',
  };

  jQuery.post(wpsc_admin.ajax_url, data, function(response) {
    jQuery('.wpsc_setting_col2').html(response);
  });
  
}
