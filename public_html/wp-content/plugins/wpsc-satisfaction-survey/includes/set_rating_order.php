<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}

$rating_ids = isset($_POST) && isset($_POST['rating_ids']) ? $_POST['rating_ids'] : array();

foreach ($rating_ids as $key => $rating_id) {
	update_term_meta(intval($rating_id), 'load_order', intval($key));
}

echo '{ "sucess_status":"1","messege":"'.__('Ratings order saved.','wpsc').'" }';
