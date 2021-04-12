<?php
	$search = array(
		'*copy*',
		'*current_year*'
	);
	
	$replace = array(
		'&copy;',
		date( 'Y' )
	);
	
	$copyright = str_replace( 
		$search, 
		$replace, 
		get_theme_mod( 'footer_copyright', '*copy* *current_year* Jumpstart. By <a href="http://www.tommusrhodus.com">TommusRhodus</a>' ) 
	);
?>

<div class="text-muted"><?php echo wp_kses_post( $copyright ); ?></div>