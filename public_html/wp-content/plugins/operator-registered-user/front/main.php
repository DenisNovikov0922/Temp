<?php

/**

 * Includes functions for all admin page templates.

 * functions that add menu pages in the dashboard.

 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



class Opregusr_Front {

	

	static $settings;



	

	/*

	 * Class Construct.

	 *

	 * @since 1.0

	 */	

	public function __construct() {

		

		$default = array('form_page'=>'', 'tform'=>'0', 'user_role'=>'administrator', 'force_login'=>'n');

		$settings = get_option('_opregusr_settings', false);

		if($settings != false){

			$default = array_merge($default, $settings);

		}

		self::$settings = $default;

		

		//need session

		add_action( 'init', array($this,'opregusr_front_init'), 99 );

		

		//Allow Email Edit and inject our special hidden field		

		add_action('wp_footer', array($this,'footer_script_inject'), 999);	

		

		//Record submission

		add_action( 'user_register', array($this,'user_register_record'), 99 );

	}

	

	public function opregusr_front_init(){

		ob_start(); 

		if(session_id() == ''){session_start(); }

	}

	

	public function is_valid_page(){

		

		$settings = self::$settings;

		

		$current_page = sanitize_post( $GLOBALS['wp_the_query']->get_queried_object() );

		// Get the page slug

		$current_slug = $current_page->post_name;

		

		if( (is_front_page() && $settings['form_page']== '#home#') || ($current_slug==$settings['form_page']) ){

			return true;

		}

		

		return false;

	}

	

	public function is_valid_user(){

		

		if( is_user_logged_in() ) {

			

		 	$user = wp_get_current_user();

		 	$roles = ( array ) $user->roles;

			

			$settings = self::$settings;

			$need_role = $settings['user_role'];

			

			if( is_array($roles) && ( in_array($need_role, $roles) || in_array('administrator', $roles)) ){

				return true;

			}

		}

		

		return false;

	}

	

	public function is_valid_form($form_id_check=false){



		$settings = self::$settings;

		if( isset($settings['tform']) ){

			if( 0 < (int)$settings['tform']){

				if($form_id_check==false){

					return true;

				}elseif($form_id_check==(int)$settings['tform']){

					return true;

				}

			}

		}

		return false;

	}

		

	public function footer_script_inject(){

		

		if($this->is_valid_page() && $this->is_valid_user() && $this->is_valid_form() ){

		

		$settings = self::$settings;

		$form_id  = (int)$settings['tform'];

	?>

    <script type="text/javascript">	

	jQuery(function($) {

	'use strict';

	

		$( document ).ready(function() {

			 $("#form_<?php echo $form_id; ?>_1 input[type=email]").attr('readonly', false);

			 $("#form_<?php echo $form_id; ?>_1").append('<input type="hidden" name="opregusr_form_field" value="rm"/>');

		});

				

	});



	</script>

    <?php	

		}

	}

	

	public function user_register_record( $user_id ) {

    	

		if(!isset($_POST['opregusr_form_field'])){ return false; }

		

		$form = $_POST['rm_form_sub_id'];

		$form_arr = explode("_", $form);

		$form_id = isset($form_arr[1])?(int)$form_arr[1]:0;

		

		//match the form ID with ourt target Form

		if( $this->is_valid_form($form_id) && $this->is_valid_user() ){

			//Operator user ID
			$op_user = get_current_user_id();
			
			//Mark the user to be indentified later
			update_user_meta($user_id, 'op_registered', $op_user);
			

			global $wpdb;

			$table = $wpdb->prefix."rm_submissions";

			$query = "SELECT `submission_id` FROM `{$table}` WHERE `form_id`='{$form_id}' 

			ORDER BY `submission_id` DESC LIMIT 0,1";

			$submission_id = (int)$wpdb->get_var($query);



			$record_table = $wpdb->prefix."rm_submissions_operator";

			$data_rec = array('operator_user'=>$op_user,

							  'submission_id'=>$submission_id,

							  'registered_user'=>$user_id,

							  'date_created' => date("Y/m/d H:i:s")

							  ); 

			$format = array('%d','%d','%d','%s');	

			$wpdb->insert($record_table,$data_rec,$format);			  

		}

		

	}



}