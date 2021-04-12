jQuery(document).ready(function($) {
	"use strict";

jQuery('.woofood-tabs-menu').on('click', 'a', function(e){
  var tab  = jQuery(this),
      tabPanel = jQuery(this).closest('.woofood-tabs-menu'),
      selected_tab = jQuery(this).attr("href"),
      tabPane = jQuery(selected_tab);
      tabPanel.find('.active').removeClass('active');
    jQuery('.woofood-tabs-menu').parent().find('.active').removeClass('show active');

  tab.addClass('active');

  tabPane.addClass('show active');
  return false;
});
});


  jQuery('.woofood-tabs-menu').on('click', 'a', function(e){
  var tab  = jQuery(this),
      tabPanel = jQuery(this).closest('.woofood-tabs-menu'),
      selected_tab = jQuery(this).attr("href"),
      tabPane = jQuery(selected_tab);
      tabPanel.find('.active').removeClass('active');
    jQuery('.woofood-tabs-menu').parent().find('.active').removeClass('show active');

  tab.addClass('active');

  tabPane.addClass('show active');
  return false;
});






