<?php 

function tommusrhodus_framework_add_elementor_widget_categories( $elements_manager ) {
	
	$elements_manager->add_category(
		'melissa-elements',
		array(
			'title' => 'Melissa Elements'
		)
	);

}
add_action( 'elementor/elements/categories_registered', 'tommusrhodus_framework_add_elementor_widget_categories', 10, 1 );

function tommusrhodus_framework_register_elementor_locations( $elementor_theme_manager ) {

	$elementor_theme_manager->register_location( 'header' );
	$elementor_theme_manager->register_location( 'footer' );

}
add_action( 'elementor/theme/register_locations', 'tommusrhodus_framework_register_elementor_locations', 10, 1 );