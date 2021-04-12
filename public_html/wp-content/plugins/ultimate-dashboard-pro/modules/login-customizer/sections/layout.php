<?php
/**
 * Layout section of Login Customizer.
 *
 * @var $wp_customize This variable is brought from login-customizer.php file.
 *
 * @package Ultimate_Dashboard_PRO
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Udb_Customize_Control;
use Udb\Udb_Customize_Color_Control;
use Udb\Udb_Customize_Range_Control;

$wp_customize->add_setting(
	'udb_login[form_position]',
	array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'default'           => 'default',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	new Udb_Customize_Control(
		$wp_customize,
		'udb_login[form_position]',
		array(
			'type'     => 'select',
			'section'  => 'udb_login_customizer_layout_section',
			'settings' => 'udb_login[form_position]',
			'label'    => __( 'Layout', 'ultimatedashboard' ),
			'choices'  => array(
				'left'    => __( 'Left', 'ultimatedashboard' ),
				'default' => __( 'Default', 'ultimatedashboard' ),
				'right'   => __( 'Right', 'ultimatedashboard' ),
			),
		)
	)
);

$wp_customize->add_setting(
	'udb_login[form_bg_color]',
	array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'default'           => '#ffffff',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_hex_color',
	)
);

$wp_customize->add_control(
	new Udb_Customize_Color_Control(
		$wp_customize,
		'udb_login[form_bg_color]',
		array(
			'label'    => __( 'Background Color', 'ultimatedashboard' ),
			'section'  => 'udb_login_customizer_layout_section',
			'settings' => 'udb_login[form_bg_color]',
		)
	)
);

$wp_customize->add_setting(
	'udb_login[box_width]',
	array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'default'           => '40%',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'esc_attr',
	)
);

$wp_customize->add_control(
	new Udb_Customize_Range_Control(
		$wp_customize,
		'udb_login[box_width]',
		array(
			'type'        => 'range',
			'section'     => 'udb_login_customizer_layout_section',
			'settings'    => 'udb_login[box_width]',
			'label'       => __( 'Box Width', 'ultimatedashboard' ),
			'input_attrs' => array(
				'min'  => 30,
				'max'  => 100,
				'step' => 1,
			),
		)
	)
);

$wp_customize->add_setting(
	'udb_login[form_width]',
	array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'default'           => '320px',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'esc_attr',
	)
);

$wp_customize->add_control(
	new Udb_Customize_Range_Control(
		$wp_customize,
		'udb_login[form_width]',
		array(
			'type'        => 'range',
			'section'     => 'udb_login_customizer_layout_section',
			'settings'    => 'udb_login[form_width]',
			'label'       => __( 'Width', 'ultimatedashboard' ),
			'input_attrs' => array(
				'min'  => 200,
				'max'  => 1000,
				'step' => 1,
			),
		)
	)
);

$wp_customize->add_setting(
	'udb_login[form_top_padding]',
	array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'default'           => '26px',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'esc_attr',
	)
);

$wp_customize->add_control(
	new Udb_Customize_Range_Control(
		$wp_customize,
		'udb_login[form_top_padding]',
		array(
			'type'        => 'range',
			'section'     => 'udb_login_customizer_layout_section',
			'settings'    => 'udb_login[form_top_padding]',
			'label'       => __( 'Top Padding', 'ultimatedashboard' ),
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 300,
				'step' => 1,
			),
		)
	)
);

$wp_customize->add_setting(
	'udb_login[form_bottom_padding]',
	array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'default'           => '46px',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'esc_attr',
	)
);

$wp_customize->add_control(
	new Udb_Customize_Range_Control(
		$wp_customize,
		'udb_login[form_bottom_padding]',
		array(
			'type'        => 'range',
			'section'     => 'udb_login_customizer_layout_section',
			'settings'    => 'udb_login[form_bottom_padding]',
			'label'       => __( 'Bottom Padding', 'ultimatedashboard' ),
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 300,
				'step' => 1,
			),
		)
	)
);

$wp_customize->add_setting(
	'udb_login[form_horizontal_padding]',
	array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'default'           => '24px',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'esc_attr',
	)
);

$wp_customize->add_control(
	new Udb_Customize_Range_Control(
		$wp_customize,
		'udb_login[form_horizontal_padding]',
		array(
			'type'        => 'range',
			'section'     => 'udb_login_customizer_layout_section',
			'settings'    => 'udb_login[form_horizontal_padding]',
			'label'       => __( 'Side Padding', 'ultimatedashboard' ),
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 300,
				'step' => 1,
			),
		)
	)
);

$wp_customize->add_setting(
	'udb_login[form_border_width]',
	array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'default'           => '2px',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'esc_attr',
	)
);

$wp_customize->add_control(
	new Udb_Customize_Range_Control(
		$wp_customize,
		'udb_login[form_border_width]',
		array(
			'type'        => 'range',
			'section'     => 'udb_login_customizer_layout_section',
			'settings'    => 'udb_login[form_border_width]',
			'label'       => __( 'Border Width', 'ultimatedashboard' ),
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 50,
				'step' => 1,
			),
		)
	)
);

$wp_customize->add_setting(
	'udb_login[form_border_color]',
	array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'default'           => '#dddddd',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_hex_color',
	)
);

$wp_customize->add_control(
	new Udb_Customize_Color_Control(
		$wp_customize,
		'udb_login[form_border_color]',
		array(
			'label'    => __( 'Border Color', 'ultimatedashboard' ),
			'section'  => 'udb_login_customizer_layout_section',
			'settings' => 'udb_login[form_border_color]',
		)
	)
);

$wp_customize->add_setting(
	'udb_login[form_border_radius]',
	array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'default'           => '4px',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'esc_attr',
	)
);

$wp_customize->add_control(
	new Udb_Customize_Range_Control(
		$wp_customize,
		'udb_login[form_border_radius]',
		array(
			'type'        => 'range',
			'section'     => 'udb_login_customizer_layout_section',
			'settings'    => 'udb_login[form_border_radius]',
			'label'       => __( 'Border Radius', 'ultimatedashboard' ),
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 80,
				'step' => 1,
			),
		)
	)
);
