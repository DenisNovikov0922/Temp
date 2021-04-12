<?php
$options_woofood = get_option('woofood_options');

$woofood_hide_country_option = isset($options_woofood['woofood_hide_country_option']) ? $options_woofood['woofood_hide_country_option'] : null ;

/*if delivery time has been set*/

if($woofood_hide_country_option!=0){

	add_action('wp_footer', 'wf_hide_country_field');
function wf_hide_country_field() 
{
	?>
	<style>
	#billing_country_field{
		display:none!important;
	}


	</style>
    <?php
 
}


}




?>