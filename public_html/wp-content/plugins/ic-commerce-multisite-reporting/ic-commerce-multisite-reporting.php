<?php
/**
Plugin Name: WooCommerce Mutlisite Reporting
Author: Deepak Shah
Description: WordPress multi-site reporting plug-in idea is to get consolidated various summaries on single dashboard.
Version: 2.3
Author URL: http://woochamps.com/

Copyright: © 2020 - http://woochamps.com/ - All Rights Reserved

Text Domain: icwoocommerce_textdomains
Domain Path: /languages/

Tested Wordpress Version: 5.4.x
WC requires at least: 3.3.4
WC tested up to: 4.3.x

Last Update Date: March 18, 2019
Last Update Date: April 12, 2020
Last Update Date: July 24, 2020
**/ 
 
if(!class_exists('IC_Commerce_Mutlisite_Reporting')){
	 
	 /*
	 * Class Name IC_Commerce_Mutlisite_Reporting 
	 */ 
	 class IC_Commerce_Mutlisite_Reporting{
		
		/* variable declaration*/
		var $constants = array();
		
		/*
		 * Function Name __construct
		 *
		 * Initialize Class Default Settings, Assigned Variables
		 *
		 * @param $constants (array) settings
		*/		 
		function __construct($constants = array()){
			
			$constants['capability'] 			= 'manage_woocommerce';
			$constants['plugin_key'] 			= 'icmsreporting';
			$constants['menu_slug'] 			= 'multi_site_reporting';
			$constants['__FILE__'] 				= __FILE__;			
			
			$constants['yesterday_date'] 		= '';
			$constants['today_date'] 			= '';
			$constants['prefix'] 				= '';
			$constants['base_prefix'] 			= '';
			$constants['shop_order_status'] 	= apply_filters( 'woocommerce_reports_order_statuses', array( 'completed', 'processing', 'on-hold'));
			$constants['start_date'] 			= '';
			$constants['end_date'] 				= '';
			$constants['ajax_action'] 			= $constants['plugin_key']."_ajax";
			$constants['post_order_status_found'] = 1;
			
			$this->constants = $constants;
			add_action('init', 	array($this, 'init'));
		}
		
		/*
		 * Function Name init
		 *
		 * Initialize Class Default Settings, Assigned Variables, call woocommerce hooks for add  and update order item meta
		 *		 
		*/
		function init(){
			include_once("includes/ic-commerce-multisite-reporting-functions.php");
			include_once("includes/ic-commerce-multisite-reporting-init.php");
			$obj = new IC_Commerce_Mutlisite_Reporting_Init($this->constants);
		}
	}
		
	$GLOBALS['IC_Commerce_Mutlisite_Reporting'] =  new IC_Commerce_Mutlisite_Reporting();
}
