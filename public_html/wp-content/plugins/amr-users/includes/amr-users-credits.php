<?php
/* -----------------------------------------------------------
function amr_users_give_credit () {  // check if the web owner is okay to give credit on  a public list
	global $amain;
//		'no_credit' => true,
//	'givecreditmessage' => 
	
	if (empty($amain['no_credit'])  OR  ($amain['no_credit'] == 'give_credit')) {
		if (empty($amain['givecreditmessage'])) 
			$message = amr_users_random_message();
		else 
			$message =$amain['givecreditmessage'];
		return ('<a class="credits" style="font-weight: lighter;
			font-style: italic; font-size:0.7em; line-height:0.8em; float:right;" '
			.'href="https://wpusersplugin.com" title="'
			.$message
			.' - amr-users from wpusersplugin.com'
			.'">'.__('credits','amr-users').'</a>');
	}
	else return '';

}
/* -----------------------------------------------------------
function amr_users_random_message () { // offer a number of ways to meaningfully give thanks for the plugin - an seo experiment
	$messages = array(
		__('wordpress searchable user directory plugin','amr-users'),
		__('wordpress searchable member directory plugin','amr-users'),
		__('wordpress user list plugin','amr-users'),
		__('wordpress member list plugin','amr-users'),
		__('wordpress users statistics plugin','amr-users'),
		__('wordpress member statistics plugin','amr-users'),
		__('wordpress sortable member list plugin','amr-users'),
		__('wordpress sortable user list plugin','amr-users'),
		__('wordpress team list plugin','amr-users')
	);
	$randkey = array_rand($messages);
	return $messages[$randkey];
}
/* -----------------------------------------------------------*/
function amr_users_say_thanks_opportunity_form () {
global $amain;

	echo ' <a target="_blank" href="https://wpusersplugin.com/related-plugins/" title="Support development by purchasing add on functionality.">';
	_e('Upgrade','amr-users');
	echo '</a>,&nbsp; ';

	echo '<a target="_blank" href="http://wordpress.org/extend/plugins/amr-users/" title="Mark it as a favourite.">';
	_e('Favourite it','amr-users');
	echo '</a>,&nbsp; ';
	echo '<a target="_blank" href="https://wordpress.org/support/plugin/amr-users/reviews/" title="Rate it at wordpress">';
	_e('Rate it','amr-users');
	echo '</a>,&nbsp; ';
	echo '<a target="_blank" href="https://www.paypal.com" title="Send via paypal to anmari@anmari.com.">';
	_e('Donate','amr-users');
	echo '</a>,&nbsp; ';
	echo '<a target="_blank" href="http://twitter.com/?status='.esc_attr('amr-users plugin from https://wpusersplugin.com').'" title="Share something positive.">';
	_e('Tweet it','amr-users');
	echo '</a>&nbsp; ';

// links policy
// http://wordpress.org/extend/plugins/about/
//http://codex.wordpress.org/Theme_Review#Credit_Links

}

