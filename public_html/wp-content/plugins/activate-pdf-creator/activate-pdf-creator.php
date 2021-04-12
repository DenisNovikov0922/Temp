<?php

   /*

   Plugin Name: PDF Generator for Form Input

   description: A plugin to create pdf of online showcase

   Version: 1.0

   Author: DSGN

   */



function filter_filename($name) {

    // remove illegal file system characters https://en.wikipedia.org/wiki/Filename#Reserved_characters_and_words

    $name = str_replace(array_merge(

        array_map('chr', range(0, 31)),

        array('<', '>', ':', '"', '/', '\\', '|', '?', '*')

    ), '', $name);

    // maximise filename length to 255 bytes http://serverfault.com/a/9548/44086

    $ext = pathinfo($name, PATHINFO_EXTENSION);

    $name= mb_strcut(pathinfo($name, PATHINFO_FILENAME), 0, 255 - ($ext ? strlen($ext) + 1 : 0), mb_detect_encoding($name)) . ($ext ? '.' . $ext : '');

    return $name;

}





function create_user_fill_pdf($user_id){

    if(!isset($_POST['rm_form_sub_id'])){ return; }

    $upload_dir   = wp_upload_dir();
    if ( ! empty( $upload_dir['basedir'] ) ) {

        $dirname = $upload_dir['basedir'].'/activate_pdf';

        if ( ! file_exists( $dirname ) ) {

            wp_mkdir_p( $dirname );

        }
    }

    
    $current_user = get_user_by("ID", $user_id);

	if( $_POST['rm_form_sub_id'] == 'form_3_1' ){  // nexi
		$data = (object) array(
	
			'name' => $_POST['Textbox_17'],
	
			'surename' => $_POST['Textbox_18'],
	
			'email' => $current_user->user_email,
	
			'phonenumber' => $_POST['Mobile_19'],
	
			'company' => $_POST['Textbox_20'],
	
			'vat_number' => $_POST['Textbox_21'],
	
			'sale_code' => $_POST['Textbox_22'],
	
			'terminal_code' => $_POST['Textbox_23'],
	
		);
	}elseif($_POST['rm_form_sub_id'] == 'form_4_1'){ // sme-factory
		$data = (object) array(

			'name' => $_POST['Textbox_32'],
	
			'surename' => $_POST['Textbox_33'],
	
			'email' => $current_user->user_email,
	
			'phonenumber' => $_POST['Mobile_34'],
	
			'company' => $_POST['Textbox_36'],
	
			'vat_number' => $_POST['Textbox_37'],
	
			'sale_code' => $_POST['Textbox_38'],
	
			'terminal_code' => $_POST['Textbox_39'],
	
		);
		
	}else{
		return;
	}

    

    $fname = date("dmY") . "_" . $user_id . '_' .$data->name . "_" . $data->surename . ".pdf";

    $fname = $dirname . "/" . sanitize_file_name($fname);

    require_once("create_pdf.php");

    

}



add_action("init", "register_activate_create_pdf" ,11);

function register_activate_create_pdf(){

    $c_user = wp_get_current_user();

    if($c_user->ID === 0){

        add_action("user_register", "create_user_fill_pdf", 11, 1);

    }elseif(isset($_POST['rm_form_sub_id']) && $_POST['rm_form_sub_id']=='form_4_1'){
		
		add_action("user_register", "create_user_fill_pdf", 11, 1);	
	}
	
    $c_user = null;

}

