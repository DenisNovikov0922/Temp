<?php 

if( 'yes' == get_theme_mod( 'show_sharing_buttons', 'yes' ) && function_exists( 'tommusrhodus_social_sharing' ) ){
	echo tommusrhodus_social_sharing();
}