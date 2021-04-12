<?php 

function tommusrhodus_framework_add_elementor_widget_categories( $elements_manager ) {
	
	$elements_manager->add_category(
		'philament-elements',
		array(
			'title' => 'Philament Elements'
		)
	);

}
add_action( 'elementor/elements/categories_registered', 'tommusrhodus_framework_add_elementor_widget_categories', 10, 1 );