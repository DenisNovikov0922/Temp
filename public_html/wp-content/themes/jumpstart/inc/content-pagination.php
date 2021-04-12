<?php

if( isset( $wp_query->show_pagination ) && !( 'show' == $wp_query->show_pagination ) ){
	return false;
}

/**
 * Post pagination, use tommusrhodus_pagination() first and fall back to default
 */
echo function_exists('tommusrhodus_pagination') ? tommusrhodus_pagination() : posts_nav_link();