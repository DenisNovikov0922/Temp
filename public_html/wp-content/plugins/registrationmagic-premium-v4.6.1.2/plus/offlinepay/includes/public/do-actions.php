<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

function rm_olp_activation(){
    do_action('rm_olp_activation');
}

function rm_olp_deactivation(){
    do_action('rm_olp_deactivation');
}

function rm_olp_init() {
    do_action( 'rm_olp_init' );
}
