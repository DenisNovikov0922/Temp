<?php
/**
 * WooFood Theme Customizer
 *
 * @package WooFood
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */

   
$theme_defaults = array();
$theme_defaults["woofood_top_bar_enabled"] = 0;
$theme_defaults["woofood_header_style_selected"] = "default";
$theme_defaults["woofood_top_bar_style"] = "default";
$theme_defaults["woofood_top_bar_background_color"] = "#515151";
$theme_defaults["woofood_top_bar_text_color"] = "#ffffff";
$theme_defaults["woofood_top_bar_left_available"] = esc_html__('Siamo Aperti!', 'woofood');
$theme_defaults["woofood_top_bar_right_available"] = esc_html__('Accettiamo ordini a domicilio/asporto!', 'woofood');
$theme_defaults["woofood_top_bar_left_unavailable"] = esc_html__('Siamo Chiusi!', 'woofood');
$theme_defaults["woofood_top_bar_right_unavailable"] = esc_html__('Il ristorante al momento è chiuso', 'woofood');
$theme_defaults["woofood_top_bar_left"] = "Easy Delivery";
$theme_defaults["woofood_top_bar_right"] ='Designed by <a href="https://www.dsgn.cc">DSGN</a>';
$theme_defaults["woofood_social_facebook"] ='https://www.facebook.com';
$theme_defaults["woofood_social_instagram"] ='https://www.instagram.com';
$theme_defaults["woofood_social_twitter"] ='https://www.twitter.com';
$theme_defaults["woofood_social_pinterest"] ='https://www.pinterest.com';
$theme_defaults["woofood_social_youtube"] ='https://www.youtube.com';
$theme_defaults["woofood_social_contact_email"] ='supporto@easy-delivery.it';
$theme_defaults["woofood_social_contact_phone"] ='02-123456789';
$theme_defaults["woofood_social_contact_address"] ='Via Roma 1, 00121 Roma (RM)';
$theme_defaults["woofood_login_register_auto"] =1;
$theme_defaults["woofood_login_register_required_first_name"] =1;
$theme_defaults["woofood_login_register_required_last_name"] =1;
$theme_defaults["woofood_login_register_required_address"] =1;
$theme_defaults["woofood_login_register_required_city"] =1;
$theme_defaults["woofood_login_register_required_postcode"] =1;
$theme_defaults["woofood_login_register_required_phone"] =1;
$theme_defaults["woofood_login_register_gdpr_enabled"] =0;
$theme_defaults["woofood_menu_background_color"] ='#000000';
$theme_defaults["woofood_menu_bar_background_color"] ='#000000';
$theme_defaults["woofood_menu_text_color"] ='#ffffff';
$theme_defaults["woofood_menu_text_active_color"] ='#ffffff';
$theme_defaults["woofood_menu_text_hover_color"] ='#ffffff';
$theme_defaults["woofood_menu_background_hover_color"] ='#cc0000';
$theme_defaults["woofood_menu_background_active_color"] ='#cc0000';
$theme_defaults["woofood_footer_text_color"] ='#ffffff';
$theme_defaults["woofood_footer_background_color"] ='#000000';
$theme_defaults["woofood_widget_text_color"] ='#000000';
$theme_defaults["woofood_widget_background_color"] ='transparent';
$theme_defaults["woofood_button_background_color"] ='';
$theme_defaults["woofood_button_text_color"] ='';
$theme_defaults["woofood_accordion_background_color"] ='';
$theme_defaults["woofood_accordion_text_color"] ='';
$theme_defaults["woofood_footer_left"] ='Powered by Easy Delivery';
$theme_defaults["woofood_footer_right"] ='Designed by <a href="https://www.dsgn.cc">DSGN</a>';
$theme_defaults["theme_style_select"] ='default';
$theme_defaults["woofood_header_menu_text_transform"] ='none';
$theme_defaults["woofood_header_menu_text_font_size"] =16;
$theme_defaults["woofood_header_menu_align"] ="";
$theme_defaults["woofood_header_cart_icon_selected"] ="woofood-icon-cart-7";
$theme_defaults["woofood_header_menu_text_spacing"] ="8";
$theme_defaults["woofood_header_max_logo_width"] ="250";
$theme_defaults["woofood_header_padding"] ="10";

$theme_defaults["woofood_header_menu_text_style"] ="";




define("WOOFOOD_THEME_DEFAULTS", $theme_defaults);



function woofood_customize_register( $wp_customize ) {

$theme_defaults = WOOFOOD_THEME_DEFAULTS;
     class SuperFlex_Custom_Radio_Image_Control extends WP_Customize_Control {
        
        /**
         * Declare the control type.
         *
         * @access public
         * @var string
         */
        public $type = 'radio-image';
        
        /**
         * Enqueue scripts and styles for the custom control.
         * 
         * Scripts are hooked at {@see 'customize_controls_enqueue_scripts'}.
         * 
         * Note, you can also enqueue stylesheets here as well. Stylesheets are hooked
         * at 'customize_controls_print_styles'.
         *
         * @access public
         */
        public function enqueue() {
            wp_enqueue_script( 'jquery-ui-button' );
        }
        
        /**
         * Render the control to be displayed in the Customizer.
         */
        public function render_content() {
            if ( empty( $this->choices ) ) {
                return;
            }           
            
            $name = '_customize-radio-' . $this->id;
            ?>
            <span class="customize-control-title">
                <?php echo esc_attr( $this->label ); ?>
                <?php if ( ! empty( $this->description ) ) : ?>
                    <span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
                <?php endif; ?>
            </span>
            <div id="input_<?php echo $this->id; ?>" class="image">
                <?php foreach ( $this->choices as $value => $label ) : ?>
                    <input class="image-select" type="radio" value="<?php echo esc_attr( $value ); ?>" id="<?php echo $this->id . $value; ?>" name="<?php echo esc_attr( $name ); ?>" <?php $this->link(); checked( $this->value(), $value ); ?>>
                        <label for="<?php echo $this->id . $value; ?>">
                            <img src="<?php echo esc_html( $label ); ?>" alt="<?php echo esc_attr( $value ); ?>" title="<?php echo esc_attr( $value ); ?>">
                        </label>
                    </input>
                <?php endforeach; ?>
            </div>
            <script>jQuery(document).ready(function($) { $( '[id="input_<?php echo $this->id; ?>"]' ).buttonset(); });</script>
            <?php
        }
    }



     class SuperFlex_Custom_Radio_Icon_Control extends WP_Customize_Control {
        
        /**
         * Declare the control type.
         *
         * @access public
         * @var string
         */
        public $type = 'radio-image';
        
        /**
         * Enqueue scripts and styles for the custom control.
         * 
         * Scripts are hooked at {@see 'customize_controls_enqueue_scripts'}.
         * 
         * Note, you can also enqueue stylesheets here as well. Stylesheets are hooked
         * at 'customize_controls_print_styles'.
         *
         * @access public
         */
        public function enqueue() {
            wp_enqueue_script( 'jquery-ui-button' );
                 wp_enqueue_style('woofood-icons-theme', get_template_directory_uri() . '/css/icons.css', array(), WOOFOOD_THEME_VERSION, 'all'); 

        }
        
        /**
         * Render the control to be displayed in the Customizer.
         */
        public function render_content() {

            if ( empty( $this->choices ) ) {
                return;
            }           
            
            $name = '_customize-radio-' . $this->id;
            ?>
            <span class="customize-control-title">
                <?php echo esc_attr( $this->label ); ?>
                <?php if ( ! empty( $this->description ) ) : ?>
                    <span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
                <?php endif; ?>
            </span>
            <div id="input_<?php echo $this->id; ?>" class="image">
                <?php foreach ( $this->choices as $value => $label ) : ?>
                    <input class="image-select" type="radio" value="<?php echo esc_attr( $value ); ?>" id="<?php echo $this->id . $value; ?>" name="<?php echo esc_attr( $name ); ?>" <?php $this->link(); checked( $this->value(), $value ); ?>>
                        <label for="<?php echo $this->id . $value; ?>">
                            <i class="<?php echo esc_attr( $value ); ?>"></i>
                        </label>
                    </input>
                <?php endforeach; ?>
            </div>
            <script>jQuery(document).ready(function($) { $( '[id="input_<?php echo $this->id; ?>"]' ).buttonset(); });</script>
            <?php
        }
    }


    class Superflex_Text_Radio_Button_Custom_Control extends WP_Customize_Control {
        /**
         * The type of control being rendered
         */
        public $type = 'text_radio_button';
        /**
         * Enqueue our scripts and styles
         */
        public function enqueue() {
                 wp_enqueue_style('woofood-customizer-css', get_template_directory_uri() . '/css/customizer.css', array(), WOOFOOD_THEME_VERSION, 'all'); 
        }
        /**
         * Render the control in the customizer
         */
        public function render_content() {
        ?>
            <div class="text_radio_button_control">
                <?php if( !empty( $this->label ) ) { ?>
                    <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                <?php } ?>
                <?php if( !empty( $this->description ) ) { ?>
                    <span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
                <?php } ?>

                <div class="radio-buttons">
                    <?php foreach ( $this->choices as $key => $value ) { ?>
                        <label class="radio-button-label">
                            <input type="radio" name="<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr( $key ); ?>" <?php $this->link(); ?> <?php checked( esc_attr( $key ), $this->value() ); ?>/>
                            <span><?php echo esc_attr( $value ); ?></span>
                        </label>
                    <?php   } ?>
                </div>
            </div>
        <?php
        }
    }

         class Superflex_Image_Checkbox_Custom_Control extends WP_Customize_Control {
        /**
         * The type of control being rendered
         */
        public $type = 'image_checkbox';
        /**
         * Enqueue our scripts and styles
         */
        public function enqueue() {
                 wp_enqueue_style('woofood-customizer-css', get_template_directory_uri() . '/css/customizer.css', array(), WOOFOOD_THEME_VERSION, 'all'); 
                                         wp_enqueue_script( 'woofood-customizer-controls-js', get_template_directory_uri() . '/js/customizer-controls.js', array( 'jquery', 'jquery-ui-core' ), '1.0', true );

        }
        /**
         * Render the control in the customizer
         */
        public function render_content() {
        ?>
          <div class="image_checkbox_control">
                <?php if( !empty( $this->label ) ) { ?>
                    <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                <?php } ?>
                <?php if( !empty( $this->description ) ) { ?>
                    <span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
                <?php } ?>
                <?php   $chkboxValues = explode( ',', esc_attr( $this->value() ) ); ?>
                <input type="hidden" id="<?php echo esc_attr( $this->id ); ?>" name="<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr( $this->value() ); ?>" class="customize-control-multi-image-checkbox" <?php $this->link(); ?> />
                <?php foreach ( $this->choices as $key => $value ) { ?>
                    <label class="checkbox-label">
                        <input type="checkbox" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $key ); ?>" <?php checked( in_array( esc_attr( $key ), $chkboxValues ), 1 ); ?> class="multi-image-checkbox"/>
                        <img src="<?php echo esc_attr( $value['image'] ); ?>" alt="<?php echo esc_attr( $value['name'] ); ?>" title="<?php echo esc_attr( $value['name'] ); ?>" />
                    </label>
                <?php   } ?>
            </div>
        <?php
        }
    }

    if ( ! function_exists( 'superflex_text_sanitization' ) ) {
        function superflex_text_sanitization( $input ) {
            if ( strpos( $input, ',' ) !== false) {
                $input = explode( ',', $input );
            }
            if( is_array( $input ) ) {
                foreach ( $input as $key => $value ) {
                    $input[$key] = sanitize_text_field( $value );
                }
                $input = implode( ',', $input );
            }
            else {
                $input = sanitize_text_field( $input );
            }
            return $input;
        }
    }



    class Superflex_Slider_Custom_Control extends WP_Customize_Control {
        /**
         * The type of control being rendered
         */
        public $type = 'slider_control';
        /**
         * Enqueue our scripts and styles
         */
        public function enqueue() {
            wp_enqueue_style('woofood-customizer-css', get_template_directory_uri() . '/css/customizer.css', array(), WOOFOOD_THEME_VERSION, 'all'); 
                        wp_enqueue_script( 'woofood-customizer-controls-js', get_template_directory_uri() . '/js/customizer-controls.js', array( 'jquery', 'jquery-ui-core' ), '1.0', true );

        }
        /**
         * Render the control in the customizer
         */
        public function render_content() {
        ?>
            <div class="slider-custom-control">
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span><input type="number" id="<?php echo esc_attr( $this->id ); ?>" name="<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr( $this->value() ); ?>" class="customize-control-slider-value" <?php $this->link(); ?> />
                <div class="slider" slider-min-value="<?php echo esc_attr( $this->input_attrs['min'] ); ?>" slider-max-value="<?php echo esc_attr( $this->input_attrs['max'] ); ?>" slider-step-value="<?php echo esc_attr( $this->input_attrs['step'] ); ?>"></div><span class="slider-reset dashicons dashicons-image-rotate" slider-reset-value="<?php echo esc_attr( $this->value() ); ?>"></span>
            </div>

        <?php
        }
    }


	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
    



	$wp_customize->add_setting( 'woofood_logo' ); // Add setting for logo uploader
         
    // Add control for logo uploader (actual uploader)
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'woofood_logo', array(
        'label'    => esc_html__( 'Upload Logo (replaces text)', 'woofood' ),
        'section'  => 'title_tagline',
        'settings' => 'woofood_logo',
    ) ) );
    // Add control for logo uploader (actual uploader)



 $wp_customize->add_section('woofood_header_style', array(
        'title'    => esc_html__('Header Style', 'woofood'),
        'priority' => 20,
    ));

  $wp_customize->add_section('woofood_top_bar', array(
        'title'    => esc_html__('Top Bar', 'woofood'),
        'priority' => 21,
    ));


  $wp_customize->add_setting( 'woofood_top_bar_enabled', array(
    'default'           => $theme_defaults["woofood_top_bar_enabled"],
    'transport'         => 'refresh',
    'capability'     => 'edit_theme_options',
    'type'           => 'theme_mod',

    ) );

    
    $wp_customize->add_control( 'woofood_top_bar_enabled', array(
        'label'      => esc_html__( 'Attiva Top Bar', 'woofood' ),
        'section'    => 'woofood_top_bar',
        'settings'   =>'woofood_top_bar_enabled',
        'type'       => 'checkbox',


    ) ); 
     

  $wp_customize->add_setting('woofood_header_style_selected', array(
        'default'        => $theme_defaults["woofood_header_style_selected"],
        'capability'     => 'edit_theme_options',
        'type'           => 'option',

    ));


   





    $wp_customize->add_control(new SuperFlex_Custom_Radio_Image_Control( 
            // $wp_customize object
            $wp_customize,
            // $id
            'woofood_header_style_selected'
            , array(
        'label'      => esc_html__('Header Style', 'woofood'),
        'section'    => 'woofood_header_style',
        'settings'   => 'woofood_header_style_selected',
        'choices'    => array(
            'default' =>  get_template_directory_uri().'/inc/imgs/default.png',
            'logo-center' => get_template_directory_uri().'/inc/imgs/logo-center.png',
        ),
        )
    ));


     $wp_customize->add_setting('woofood_header_max_logo_width', array(
        'default'        => $theme_defaults["woofood_header_max_logo_width"],
        'capability'     => 'edit_theme_options',
        'type'           => 'option'
    ));

/*    $wp_customize->add_control(  new WP_Customize_Control($wp_customize, 'woofood_header_menu_text_font_size',  array(
    'label'      => esc_html__( 'Menu Font Size', 'woofood' ), //Admin-visible name of the control
    'description' => esc_html__( 'Select Menu Font Size' ),
    'settings'   => 'woofood_header_menu_text_font_size', //Which setting to load and manipulate (serialized is okay)
    'priority'   => 10, //Determines the order this control appears in for the specified section
    'section'    => 'woofood_header_style', //ID of the section this control should render in (can be one of yours, or a WordPress default section)
    'type'    => 'number',
)    ));*/


    $wp_customize->add_control( new Superflex_Slider_Custom_Control( $wp_customize, 'woofood_header_max_logo_width',
    array(
        'label' => esc_html__( 'Max Logo Width (px)', 'woofood' ),
        'section' => 'woofood_header_style',
        'input_attrs' => array(
            'min' => 50, // Required. Minimum value for the slider
            'max' => 500, // Required. Maximum value for the slider
            'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values
        ),
    )
) );


 $wp_customize->add_setting('woofood_header_padding', array(
        'default'        => $theme_defaults["woofood_header_padding"],
        'capability'     => 'edit_theme_options',
        'type'           => 'option'
    ));

/*    $wp_customize->add_control(  new WP_Customize_Control($wp_customize, 'woofood_header_menu_text_font_size',  array(
    'label'      => esc_html__( 'Menu Font Size', 'woofood' ), //Admin-visible name of the control
    'description' => esc_html__( 'Select Menu Font Size' ),
    'settings'   => 'woofood_header_menu_text_font_size', //Which setting to load and manipulate (serialized is okay)
    'priority'   => 10, //Determines the order this control appears in for the specified section
    'section'    => 'woofood_header_style', //ID of the section this control should render in (can be one of yours, or a WordPress default section)
    'type'    => 'number',
)    ));*/


    $wp_customize->add_control( new Superflex_Slider_Custom_Control( $wp_customize, 'woofood_header_padding',
    array(
        'label' => esc_html__( 'Header Padding (px)', 'woofood' ),
        'section' => 'woofood_header_style',
        'input_attrs' => array(
            'min' => 0, // Required. Minimum value for the slider
            'max' => 200, // Required. Maximum value for the slider
            'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values
        ),
    )
) );




    $wp_customize->add_setting('woofood_header_cart_icon_selected', array(
        'default'        => $theme_defaults["woofood_header_cart_icon_selected"],
        'capability'     => 'edit_theme_options',
        'type'           => 'option',
          'transport' => 'postMessage', // or postMessage

    ));


   





    $wp_customize->add_control(new SuperFlex_Custom_Radio_Icon_Control( 
            // $wp_customize object
            $wp_customize,
            // $id
            'woofood_header_cart_icon_selected'
            , array(
        'label'      => esc_html__('Cart Icon', 'woofood'),
        'section'    => 'woofood_header_style',
        'settings'   => 'woofood_header_cart_icon_selected',
        'choices'    => array(
            'woofood-icon-cart-1' =>  "Style 1",
            'woofood-icon-cart-2' =>  "Style 2",
            'woofood-icon-cart-3' =>  "Style 3",
            'woofood-icon-cart-4' =>  "Style 4",
            'woofood-icon-cart-5' =>  "Style 5",
            'woofood-icon-cart-6' =>  "Style 6",
            'woofood-icon-cart-7' =>  "Style 7"

        ),
        )
    ));










    $wp_customize->add_setting( 'woofood_header_menu_text_style',
    array(
        'default' => $theme_defaults["woofood_header_menu_text_style"],
        'transport' => 'refresh',
        'sanitize_callback' => 'superflex_text_sanitization',
          'capability'     => 'edit_theme_options',
        'type'           => 'option'
           //    'type'           => 'option'

    )
);
$wp_customize->add_control( new Superflex_Image_checkbox_Custom_Control( $wp_customize, 'woofood_header_menu_text_style',
    array(
        'label' => __( 'Menu Text Style', 'woofood' ),
        'description' => esc_html__( 'Menu decoration' ),

        'section' => 'woofood_header_style',
        'choices' => array(
            'bold' => array( // Required. Setting for this particular radio button choice
                'image' => trailingslashit( get_template_directory_uri() ) . '/inc/imgs/bold.png', // Required. URL for the image
                'name' => __( 'Bold', 'woofood' ) // Required. Title text to display
            ),
            'italic' => array(
                'image' => trailingslashit( get_template_directory_uri() ) . '/inc/imgs/italic.png',
                'name' => __( 'Italic','woofood' )
            ),
            'uppercase' => array(
                'image' => trailingslashit( get_template_directory_uri() ) . '/inc/imgs/allcaps.png',
                'name' => __( 'All Caps', 'woofood' )
            ),
            'underline' => array(
                'image' => trailingslashit( get_template_directory_uri() ) . '/inc/imgs/underline.png',
                'name' => __( 'Underline', 'woofood' )
            )
        )
    )
) );



    



         $wp_customize->add_setting('woofood_header_menu_text_font_size', array(
        'default'        => $theme_defaults["woofood_header_menu_text_font_size"],
        'capability'     => 'edit_theme_options',
        'type'           => 'option'
    ));

/*    $wp_customize->add_control(  new WP_Customize_Control($wp_customize, 'woofood_header_menu_text_font_size',  array(
    'label'      => esc_html__( 'Menu Font Size', 'woofood' ), //Admin-visible name of the control
    'description' => esc_html__( 'Select Menu Font Size' ),
    'settings'   => 'woofood_header_menu_text_font_size', //Which setting to load and manipulate (serialized is okay)
    'priority'   => 10, //Determines the order this control appears in for the specified section
    'section'    => 'woofood_header_style', //ID of the section this control should render in (can be one of yours, or a WordPress default section)
    'type'    => 'number',
)    ));*/


    $wp_customize->add_control( new Superflex_Slider_Custom_Control( $wp_customize, 'woofood_header_menu_text_font_size',
    array(
        'label' => esc_html__( 'Menu Font Size (px)', 'woofood' ),
        'section' => 'woofood_header_style',
        'input_attrs' => array(
            'min' => 10, // Required. Minimum value for the slider
            'max' => 35, // Required. Maximum value for the slider
            'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values
        ),
    )
) );



$wp_customize->add_setting('woofood_header_menu_text_spacing', array(
        'default'        => $theme_defaults["woofood_header_menu_text_spacing"],
        'capability'     => 'edit_theme_options',
        'type'           => 'option'
    ));

/*    $wp_customize->add_control(  new WP_Customize_Control($wp_customize, 'woofood_header_menu_text_font_size',  array(
    'label'      => esc_html__( 'Menu Font Size', 'woofood' ), //Admin-visible name of the control
    'description' => esc_html__( 'Select Menu Font Size' ),
    'settings'   => 'woofood_header_menu_text_font_size', //Which setting to load and manipulate (serialized is okay)
    'priority'   => 10, //Determines the order this control appears in for the specified section
    'section'    => 'woofood_header_style', //ID of the section this control should render in (can be one of yours, or a WordPress default section)
    'type'    => 'number',
)    ));*/


    $wp_customize->add_control( new Superflex_Slider_Custom_Control( $wp_customize, 'woofood_header_menu_text_spacing',
    array(
        'label' => esc_html__( 'Menu Spacing (px)', 'woofood' ),
        'section' => 'woofood_header_style',
        'input_attrs' => array(
            'min' => 0, // Required. Minimum value for the slider
            'max' => 40, // Required. Maximum value for the slider
            'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values
        ),
    )
) );


   $wp_customize->add_setting('woofood_header_menu_align', array(
        'default'        => $theme_defaults["woofood_header_menu_align"],
        'capability'     => 'edit_theme_options',
        'type'           => 'option'
    ));

    $wp_customize->add_control(  new Superflex_Text_Radio_Button_Custom_Control($wp_customize, 'woofood_header_menu_align',  array(
    'label'      => esc_html__( 'Menu Align', 'woofood' ), //Admin-visible name of the control
    'description' => esc_html__( 'Select Menu Align' ),
    'settings'   => 'woofood_header_menu_align', //Which setting to load and manipulate (serialized is okay)
    'priority'   => 10, //Determines the order this control appears in for the specified section
    'section'    => 'woofood_header_style', //ID of the section this control should render in (can be one of yours, or a WordPress default section)
    //'type'    => 'select',
    'choices' => array(
        'mr-auto' => esc_html__('Left', 'woofood'),
        'mx-auto' => esc_html__('Center', 'woofood'),
        'ml-auto' => esc_html__('Right', 'woofood')


      /*  'phone-social' => esc_html__('Left Column (Phone Number) - Right Column(Social Media)')*/

    )
)    ));




  $wp_customize->add_setting('woofood_top_bar_style', array(
        'default'        => $theme_defaults["woofood_top_bar_style"],
        'capability'     => 'edit_theme_options',
        'type'           => 'option',
    ));
$wp_customize->add_control(  new WP_Customize_Control($wp_customize, 'woofood_top_bar_style',  array(
    'label'      => esc_html__( 'Scegli stile', 'woofood' ), //Admin-visible name of the control
    'description' => esc_html__( 'Scegli lo stile della top bar' ),
    'settings'   => 'woofood_top_bar_style', //Which setting to load and manipulate (serialized is okay)
    'priority'   => 10, //Determines the order this control appears in for the specified section
    'section'    => 'woofood_top_bar', //ID of the section this control should render in (can be one of yours, or a WordPress default section)
    'type'    => 'select',
    'choices' => array(
        'default' => esc_html__('Default (2 Colonne testo libero)'),
        'availability' => esc_html__('Orari attività (Basato sulle impostazioni Easy Delivery)'),
        'text-social' => esc_html__('Colonna SX (Testo libero) - Colonna DX (Icone social)'),
        'menu-social' => esc_html__('Colonna SX (Menu) - Colonna DX (Icone social)'),


      /*  'phone-social' => esc_html__('Left Column (Phone Number) - Right Column(Social Media)')*/

    )
)





    ));



  $wp_customize->add_setting('woofood_top_bar_background_color', array(
        'default'        => $theme_defaults["woofood_top_bar_background_color"],
        'capability'     => 'edit_theme_options',
        'type'           => 'option',
    ));
 $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'woofood_top_bar_background_color', array(
        'label'    => esc_html__('Colore Sfondo', 'woofood'),
        'section'  => 'woofood_top_bar',
        'settings' => 'woofood_top_bar_background_color',
    )));


  $wp_customize->add_setting('woofood_top_bar_text_color', array(
        'default'        => $theme_defaults["woofood_top_bar_text_color"],
        'capability'     => 'edit_theme_options',
        'type'           => 'option',
    ));
 $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'woofood_top_bar_text_color', array(
        'label'    => esc_html__('Colore Testo', 'woofood'),
        'section'  => 'woofood_top_bar',
        'settings' => 'woofood_top_bar_text_color',


    )));


    $wp_customize->add_setting('woofood_top_bar_left_available', array(
        'default'        => $theme_defaults["woofood_top_bar_left_available"],
        'transport'   => 'postMessage',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
    ));

     $wp_customize->add_control('woofood_top_bar_left_available', array(
        'label'      => esc_html__('Colonna SX (Attività aperta)', 'woofood'),
        'description' => esc_html__( 'Visibile con stile "Orari attività"' ),
        'section'    => 'woofood_top_bar',
        'settings'   => 'woofood_top_bar_left_available',
    ));

      $wp_customize->add_setting('woofood_top_bar_right_available', array(
        'default'        => $theme_defaults["woofood_top_bar_right_available"],
        'transport'   => 'postMessage',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
    ));

     $wp_customize->add_control('woofood_top_bar_right_available', array(
        'label'      => esc_html__('Colonna DX (Attività aperta)', 'woofood'),
        'description' => esc_html__( 'Visibile con stile "Orari attività"' ),     
        'section'    => 'woofood_top_bar',
        'settings'   => 'woofood_top_bar_right_available',
    ));



     $wp_customize->add_setting('woofood_top_bar_left_unavailable', array(
        'default'        => $theme_defaults["woofood_top_bar_left_unavailable"],
        'transport'   => 'postMessage',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
    ));

     $wp_customize->add_control('woofood_top_bar_left_unavailable', array(
        'label'      => esc_html__('Colonna SX (Attività chiusa)', 'woofood'),
        'description' => esc_html__( 'Visibile con stile "Orari attività"' ),       
        'section'    => 'woofood_top_bar',
        'settings'   => 'woofood_top_bar_left_unavailable',
    ));

      $wp_customize->add_setting('woofood_top_bar_right_unavailable', array(
        'default'        => $theme_defaults["woofood_top_bar_right_unavailable"],
        'transport'   => 'postMessage',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
    ));

     $wp_customize->add_control('woofood_top_bar_right_unavailable', array(
        'label'      => esc_html__('Colonna DX (Attività chiusa)', 'woofood'),
        'description' => esc_html__( 'Visibile con stile "Orari attività"' ),
        'section'    => 'woofood_top_bar',
        'settings'   => 'woofood_top_bar_right_unavailable',
    ));






      $wp_customize->add_setting('woofood_top_bar_left', array(
        'default'        => $theme_defaults["woofood_top_bar_left"],
        'transport'   => 'postMessage',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
    ));

     $wp_customize->add_control('woofood_top_bar_left', array(
        'label'      => esc_html__('Colonna Sinistra', 'woofood'),
        'section'    => 'woofood_top_bar',
        'settings'   => 'woofood_top_bar_left',
    ));



  
         /*Footer Left Text*/


           /*Footer Right Text*/
     $wp_customize->add_setting('woofood_top_bar_right', array(
        'default'        => $theme_defaults["woofood_top_bar_right"],
        'transport'   => 'postMessage',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
    ));

     $wp_customize->add_control('woofood_top_bar_right', array(
        'label'      => esc_html__('Colonna Destra', 'woofood'),
        'section'    => 'woofood_top_bar',
        'settings'   => 'woofood_top_bar_right',
    ));




      $wp_customize->add_section('woofood_social_details', array(
        'title'    => esc_html__('Social & Contact Details', 'woofood'),
        'priority' => 120,
    ));
    /* Register New Section Footer   */

    /*Facebook*/
    $wp_customize->add_setting( 'woofood_social_facebook', array(
    'default'           => $theme_defaults["woofood_social_facebook"],
    'transport'         => 'postMessage',
    'capability'     => 'edit_theme_options',
    'sanitize_callback' => 'esc_url_raw',
    'type'           => 'theme_mod',

    ) );

    $wp_customize->add_control('woofood_social_facebook', array(
        'label'      => esc_html__('Facebook URL', 'woofood'),
        'section'    => 'woofood_top_bar',
        'settings'   => 'woofood_social_facebook',
    ));

    /*Facebook*/



    /*Twitter*/
    $wp_customize->add_setting( 'woofood_social_twitter', array(
    'default'           => $theme_defaults["woofood_social_twitter"],
    'transport'         => 'postMessage',
    'capability'     => 'edit_theme_options',
    'sanitize_callback' => 'esc_url_raw',
    'type'           => 'theme_mod',

    ) );

    $wp_customize->add_control('woofood_social_twitter', array(
        'label'      => esc_html__('Twitter URL', 'woofood'),
        'section'    => 'woofood_top_bar',
        'settings'   => 'woofood_social_twitter',
    ));

    /*Twitter*/


    /*Google Plus*/
    $wp_customize->add_setting( 'woofood_social_instagram', array(
    'default'           => $theme_defaults["woofood_social_instagram"],
    'transport'         => 'postMessage',
    'capability'     => 'edit_theme_options',
    'sanitize_callback' => 'esc_url_raw',
    'type'           => 'theme_mod',

    ) );

    $wp_customize->add_control('woofood_social_instagram', array(
        'label'      => esc_html__('Instagram URL', 'woofood'),
        'section'    => 'woofood_top_bar',
        'settings'   => 'woofood_social_instagram',
    ));

    /*Google Plus*/


    /*Youtube*/
    $wp_customize->add_setting( 'woofood_social_youtube', array(
    'default'           => $theme_defaults["woofood_social_youtube"],
    'transport'         => 'postMessage',
    'capability'     => 'edit_theme_options',
    'sanitize_callback' => 'esc_url_raw',
    'type'           => 'theme_mod',

    ) );

    $wp_customize->add_control('woofood_social_youtube', array(
        'label'      => esc_html__('Youtube URL', 'woofood'),
        'section'    => 'woofood_top_bar',
        'settings'   => 'woofood_social_youtube',
    ));

    /*Youtube*/


    /*Pinterest*/
    $wp_customize->add_setting( 'woofood_social_pinterest', array(
    'default'           => $theme_defaults["woofood_social_pinterest"],
    'transport'         => 'postMessage',
    'capability'     => 'edit_theme_options',
    'sanitize_callback' => 'esc_url_raw',
    'type'           => 'theme_mod',

    ) );

    $wp_customize->add_control('woofood_social_pinterest', array(
        'label'      => esc_html__('Pinterest URL', 'woofood'),
        'section'    => 'woofood_top_bar',
        'settings'   => 'woofood_social_pinterest',
    ));

    /*Pinterest*/


    /*Contact Email*/
    $wp_customize->add_setting( 'woofood_social_contact_email', array(
    'default'           => $theme_defaults["woofood_social_contact_email"],
    'transport'         => 'postMessage',
    'capability'     => 'edit_theme_options',
    'sanitize_callback' => 'is_email',
    'type'           => 'theme_mod',

    ) );

    $wp_customize->add_control('woofood_social_contact_email', array(
        'label'      => esc_html__('Indirizzo E-Mail', 'woofood'),
        'section'    => 'woofood_top_bar',
        'settings'   => 'woofood_social_contact_email',
    ));

    /*Contact Email*/


    /*Contact Phone*/
    $wp_customize->add_setting( 'woofood_social_contact_phone', array(
    'default'           =>  $theme_defaults["woofood_social_contact_phone"],
    'transport'         => 'postMessage',
    'capability'     => 'edit_theme_options',
    'type'           => 'theme_mod',

    ) );

    $wp_customize->add_control('woofood_social_contact_phone', array(
        'label'      => esc_html__('Numero di Telefono', 'woofood'),
        'section'    => 'woofood_top_bar',
        'settings'   => 'woofood_social_contact_phone',
    ));

    /*Contact Phone*/


    /*Contact Address*/
    $wp_customize->add_setting( 'woofood_social_contact_address', array(
    'default'           => $theme_defaults["woofood_social_contact_address"],
    'transport'         => 'postMessage',
    'capability'     => 'edit_theme_options',
    'type'           => 'theme_mod',

    ) );

    $wp_customize->add_control('woofood_social_contact_address', array(
        'label'      => esc_html__('Indirizzo Attività', 'woofood'),
        'section'    => 'woofood_top_bar',
        'settings'   => 'woofood_social_contact_address',
    ));



    $wp_customize->add_section('woofood_login_register_modal', array(
        'title'    => esc_html__('Login/Register Modal', 'woofood'),
        'priority' => 22,
    ));


     $wp_customize->add_setting( 'woofood_login_register_auto', array(
    'default'           => $theme_defaults["woofood_login_register_auto"],
    'transport'         => 'postMessage',
    'capability'     => 'edit_theme_options',
    'type'           => 'theme_mod',

    ) );

    
    $wp_customize->add_control( 'woofood_login_register_auto', array(
        'label'      => esc_html__( 'Auto Show PopUp', 'woofood' ),
        'description' => esc_html__( 'If enabled the modal will automatically appear not not logged in users', 'woofood' ),
        'section'    => 'woofood_login_register_modal',
        'settings'   =>'woofood_login_register_auto',
        'type'       => 'checkbox'
    ) ); 


    $wp_customize->add_setting( 'woofood_login_register_required_first_name', array(
    'default'           => $theme_defaults["woofood_login_register_required_first_name"],
    'transport'         => 'postMessage',
    'capability'     => 'edit_theme_options',
    'type'           => 'theme_mod',

    ) );

    
    $wp_customize->add_control( 'woofood_login_register_required_first_name', array(
        'label'      => esc_html__( 'First Name (Required)', 'woofood' ),
        'description' => esc_html__( 'If enabled the field will be required', 'woofood' ),
        'section'    => 'woofood_login_register_modal',
        'settings'   =>'woofood_login_register_required_first_name',
        'type'       => 'checkbox'
    ) ); 


    $wp_customize->add_setting( 'woofood_login_register_required_last_name', array(
    'default'           => $theme_defaults["woofood_login_register_required_last_name"],
    'transport'         => 'postMessage',
    'capability'     => 'edit_theme_options',
    'type'           => 'theme_mod',

    ) );

    
    $wp_customize->add_control( 'woofood_login_register_required_last_name', array(
        'label'      => esc_html__( 'Last Name (Required)', 'woofood' ),
        'description' => esc_html__( 'If enabled the field will be required', 'woofood' ),
        'section'    => 'woofood_login_register_modal',
        'settings'   =>'woofood_login_register_required_last_name',
        'type'       => 'checkbox'

    ) ); 


     $wp_customize->add_setting( 'woofood_login_register_required_address', array(
    'default'           => $theme_defaults["woofood_login_register_required_address"],
    'transport'         => 'postMessage',
    'capability'     => 'edit_theme_options',
    'type'           => 'theme_mod',

    ) );

    
    $wp_customize->add_control( 'woofood_login_register_required_address', array(
        'label'      => esc_html__( 'Email(Required)', 'woofood' ),
        'description' => esc_html__( 'If enabled the field will be required', 'woofood' ),
        'section'    => 'woofood_login_register_modal',
        'settings'   =>'woofood_login_register_required_address',
        'type'       => 'checkbox',
    ) ); 




     $wp_customize->add_setting( 'woofood_login_register_required_city', array(
    'default'           => $theme_defaults["woofood_login_register_required_city"],
    'transport'         => 'postMessage',
    'capability'     => 'edit_theme_options',
    'type'           => 'theme_mod'

    ) );

    
    $wp_customize->add_control( 'woofood_login_register_required_city', array(
        'label'      => esc_html__( 'City(Required)', 'woofood' ),
        'description' => esc_html__( 'If enabled the field will be required', 'woofood' ),
        'section'    => 'woofood_login_register_modal',
        'settings'   =>'woofood_login_register_required_city',
        'type'       => 'checkbox'
    ) ); 



     $wp_customize->add_setting( 'woofood_login_register_required_postcode', array(
    'default'           => $theme_defaults["woofood_login_register_required_postcode"],
    'transport'         => 'postMessage',
    'capability'     => 'edit_theme_options',
    'type'           => 'theme_mod'

    ) );

    
    $wp_customize->add_control( 'woofood_login_register_required_postcode', array(
        'label'      => esc_html__( 'Postal Code(Required)', 'woofood' ),
        'description' => esc_html__( 'If enabled the field will be required', 'woofood' ),
        'section'    => 'woofood_login_register_modal',
        'settings'   =>'woofood_login_register_required_postcode',
        'type'       => 'checkbox'
    ) ); 



    $wp_customize->add_setting( 'woofood_login_register_required_phone', array(
    'default'           => $theme_defaults["woofood_login_register_required_phone"],
    'transport'         => 'postMessage',
    'capability'     => 'edit_theme_options',
    'type'           => 'theme_mod',

    ) );

    
    $wp_customize->add_control( 'woofood_login_register_required_phone', array(
        'label'      => esc_html__( 'Phone(Required)', 'woofood' ),
        'description' => esc_html__( 'If enabled the field will be required', 'woofood' ),
        'section'    => 'woofood_login_register_modal',
        'settings'   =>'woofood_login_register_required_phone',
        'type'       => 'checkbox'
    ) ); 


    $wp_customize->add_setting( 'woofood_login_register_gdpr_enabled', array(
    'default'           => $theme_defaults["woofood_login_register_gdpr_enabled"],
    'transport'         => 'postMessage',
    'capability'     => 'edit_theme_options',
    'type'           => 'theme_mod',

    ) );

    
    $wp_customize->add_control( 'woofood_login_register_gdpr_enabled', array(
        'label'      => esc_html__( 'GDPR Enabled Checkbox', 'woofood' ),
        'description' => esc_html__( 'If enabled the field will be required', 'woofood' ),
        'section'    => 'woofood_login_register_modal',
        'settings'   =>'woofood_login_register_gdpr_enabled',
        'type'       => 'checkbox'
    ) ); 









    /* Menu Bar Background Color*/
    $wp_customize->add_setting('woofood_menu_bar_background_color', array(
        'default'           => $theme_defaults["woofood_menu_bar_background_color"],
        'transport'   => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
        'capability'        => 'edit_theme_options',
        'type'           => 'theme_mod'
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'woofood_menu_bar_background_color', array(
        'label'    => esc_html__('Menu Bar Background Color', 'woofood'),
        'section'  => 'colors',
        'settings' => 'woofood_menu_bar_background_color',
    )));

   /* Menu Bar Background Color*/





   /* Menu Text Color*/
    $wp_customize->add_setting('woofood_menu_text_color', array(
        'default'           => $theme_defaults["woofood_menu_text_color"],
        'transport'   => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
        'capability'        => 'edit_theme_options',
        'type'           => 'theme_mod'
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'woofood_menu_text_color', array(
        'label'    => esc_html__('Menu Text Color', 'woofood'),
        'section'  => 'colors',
        'settings' => 'woofood_menu_text_color',
    )));




   /* Menu Text Color*/

   /* Menu Text Hover Color*/
    $wp_customize->add_setting('woofood_menu_text_hover_color', array(
        'default'           => $theme_defaults["woofood_menu_text_hover_color"],
        'transport'   => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
        'capability'        => 'edit_theme_options',
        'type'           => 'theme_mod'
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'woofood_menu_text_hover_color', array(
        'label'    => esc_html__('Menu Text Hover Color', 'woofood'),
        'section'  => 'colors',
        'settings' => 'woofood_menu_text_hover_color',
    )));

   /* Menu Text Hover  Color*/


    /* Menu Text Active Color*/
    $wp_customize->add_setting('woofood_menu_text_active_color', array(
        'default'           => $theme_defaults["woofood_menu_text_active_color"],
        'transport'   => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
        'capability'        => 'edit_theme_options',
        'type'           => 'theme_mod'
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'woofood_menu_text_active_color', array(
        'label'    => esc_html__('Menu Text Active Color', 'woofood'),
        'section'  => 'colors',
        'settings' => 'woofood_menu_text_active_color',
    )));

   /* Menu Text Active  Color*/


   /* Menu  Background  Color*/
    $wp_customize->add_setting('woofood_menu_background_color', array(
        'default'           => $theme_defaults["woofood_menu_background_color"],
        'transport'   => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
        'capability'        => 'edit_theme_options',
        'type'           => 'theme_mod'
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'woofood_menu_background_color', array(
        'label'    => esc_html__('Menu Background Color', 'woofood'),
        'section'  => 'colors',
        'settings' => 'woofood_menu_background_color',
    )));

   /* Menu  Background  Color*/


   /* Menu  Background Hover Color*/
    $wp_customize->add_setting('woofood_menu_background_hover_color', array(
        'default'           => $theme_defaults["woofood_menu_background_hover_color"],
        'transport'   => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
        'capability'        => 'edit_theme_options',
        'type'           => 'theme_mod'
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'woofood_menu_background_hover_color', array(
        'label'    => esc_html__('Menu Background Hover Color', 'woofood'),
        'section'  => 'colors',
        'settings' => 'woofood_menu_background_hover_color',
    )));

   /* Menu  Background Hover  Color*/

   

    /* Menu  Background Active Color*/
    $wp_customize->add_setting('woofood_menu_background_active_color', array(
        'default'           => $theme_defaults["woofood_menu_background_active_color"],
        'transport'   => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
        'capability'        => 'edit_theme_options',
        'type'           => 'theme_mod'
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'woofood_menu_background_active_color', array(
        'label'    => esc_html__('Menu Background Active Color', 'woofood'),
        'section'  => 'colors',
        'settings' => 'woofood_menu_background_active_color',
    )));

   /* Menu  Background Active  Color*/



   /* Footer  Text  Color*/
    $wp_customize->add_setting('woofood_footer_text_color', array(
        'default'           => $theme_defaults["woofood_footer_text_color"],
        'transport'   => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
        'capability'        => 'edit_theme_options',
        'type'           => 'theme_mod'
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'woofood_footer_text_color', array(
        'label'    => esc_html__('Footer Text Color', 'woofood'),
        'section'  => 'colors',
        'settings' => 'woofood_footer_text_color',
    )));

   /* Footer  Text  Color*/



   /* Footer  Background  Color*/
    $wp_customize->add_setting('woofood_footer_background_color', array(
        'default'           => $theme_defaults["woofood_footer_background_color"],
        'transport'   => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
        'capability'        => 'edit_theme_options',
        'type'           => 'theme_mod'
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'woofood_footer_background_color', array(
        'label'    => esc_html__('Footer Background Color', 'woofood'),
        'section'  => 'colors',
        'settings' => 'woofood_footer_background_color',
    )));

   /* Footer  Background  Color*/


   /* Widget  Text  Color*/
    $wp_customize->add_setting('woofood_widget_text_color', array(
        'default'           => $theme_defaults["woofood_widget_text_color"],
        'transport'   => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
        'capability'        => 'edit_theme_options',
        'type'           => 'theme_mod'
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'woofood_widget_text_color', array(
        'label'    => esc_html__('Widget Text Color', 'woofood'),
        'section'  => 'colors',
        'settings' => 'woofood_widget_text_color',
    )));

   /* Widget  Text  Color*/


   /* Widget  Background  Color*/
    $wp_customize->add_setting('woofood_widget_background_color', array(
        'default'           => $theme_defaults["woofood_widget_background_color"],
        'transport'   => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
        'capability'        => 'edit_theme_options',
        'type'           => 'theme_mod'
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'woofood_widget_background_color', array(
        'label'    => esc_html__('Widget Background Color', 'woofood'),
        'section'  => 'colors',
        'settings' => 'woofood_widget_background_color',
    )));

   /* Widget  Background  Color*/




  /* Button  Background Color*/
    $wp_customize->add_setting('woofood_button_background_color', array(
        'default'           => $theme_defaults["woofood_button_background_color"],
        'transport'   => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
        'capability'        => 'edit_theme_options',
        'type'           => 'theme_mod'
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'woofood_button_background_color', array(
        'label'    => esc_html__(' Button Background Color', 'woofood'),
        'section'  => 'colors',
        'settings' => 'woofood_button_background_color',
    )));

  /* Button  Background Color*/




    /* Button  Text Color*/
    $wp_customize->add_setting('woofood_button_text_color', array(
        'default'           => $theme_defaults["woofood_button_text_color"],
        'transport'   => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
        'capability'        => 'edit_theme_options',
        'type'           => 'theme_mod'
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'woofood_button_text_color', array(
        'label'    => esc_html__('Button Text Color', 'woofood'),
        'section'  => 'colors',
        'settings' => 'woofood_button_text_color',
    )));

    /* Button  Text Color*/



    /* Accordion FrontPage Panel Background*/
    $wp_customize->add_setting('woofood_accordion_background_color', array(
        'default'           => $theme_defaults["woofood_accordion_background_color"],
        'transport'   => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
        'capability'        => 'edit_theme_options',
        'type'           => 'theme_mod'
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'woofood_accordion_background_color', array(
        'label'    => esc_html__(' Accordion FrontPage Panel Background Color', 'woofood'),
        'section'  => 'colors',
        'settings' => 'woofood_accordion_background_color',
    )));

    /* Accordion FrontPage Panel Background*/




    /* Accordion FrontPage Panel Text Color*/
    $wp_customize->add_setting('woofood_accordion_text_color', array(
        'default'           => $theme_defaults["woofood_accordion_text_color"],
        'transport'   => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
        'capability'        => 'edit_theme_options',
        'type'           => 'theme_mod'
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'woofood_accordion_text_color', array(
        'label'    => esc_html__('Accordion FrontPage Panel Text Color', 'woofood'),
        'section'  => 'colors',
        'settings' => 'woofood_accordion_text_color',
    )));

    /* Accordion FrontPage Panel Text Color*/




/*    ______            __               ____       __        _ __
   / ____/___  ____  / /____  _____   / __ \___  / /_____ _(_) /____
  / /_  / __ \/ __ \/ __/ _ \/ ___/  / / / / _ \/ __/ __ `/ / / ___/
 / __/ / /_/ / /_/ / /_/  __/ /     / /_/ /  __/ /_/ /_/ / / (__  )
/_/    \____/\____/\__/\___/_/     /_____/\___/\__/\__,_/_/_/____/
*/
   
       /* Register New Section Footer   */
     $wp_customize->add_section('woofood_footer_details', array(
        'title'    => esc_html__('Footer Details', 'woofood'),
        'priority' => 120,
    ));
    /* Register New Section Footer   */


        /*Footer Left Text*/
     $wp_customize->add_setting('woofood_footer_left', array(
        'default'        => $theme_defaults["woofood_footer_left"],
        'transport'   => 'postMessage',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
    ));

     $wp_customize->add_control('woofood_footer_left', array(
        'label'      => esc_html__('Footer Left', 'woofood'),
        'section'    => 'woofood_footer_details',
        'settings'   => 'woofood_footer_left',
    ));

         /*Footer Left Text*/


           /*Footer Right Text*/
     $wp_customize->add_setting('woofood_footer_right', array(
        'default'        => $theme_defaults["woofood_footer_right"],
        'transport'   => 'postMessage',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
    ));

     $wp_customize->add_control('woofood_footer_right', array(
        'label'      => esc_html__('Footer Right', 'woofood'),
        'section'    => 'woofood_footer_details',
        'settings'   => 'woofood_footer_right',
    ));

         /*Footer Right Text*/


/*   _____            _       __   ___        ______            __             __     ____       __        _ __
  / ___/____  _____(_)___ _/ /  ( _ )      / ____/___  ____  / /_____ ______/ /_   / __ \___  / /_____ _(_) /____
  \__ \/ __ \/ ___/ / __ `/ /  / __ \/|   / /   / __ \/ __ \/ __/ __ `/ ___/ __/  / / / / _ \/ __/ __ `/ / / ___/
 ___/ / /_/ / /__/ / /_/ / /  / /_/  <   / /___/ /_/ / / / / /_/ /_/ / /__/ /_   / /_/ /  __/ /_/ /_/ / / (__  )
/____/\____/\___/_/\__,_/_/   \____/\/   \____/\____/_/ /_/\__/\__,_/\___/\__/  /_____/\___/\__/\__,_/_/_/____/
*/

 /* Register New Section Social   */
    

    /*Contact Address*/


/*$wp_customize->add_section('theme_styles' , array(
    'title'      => esc_html__('Theme Styles','woofood'),
    'priority'   => 30, )
   );
*/


/*    Add theme style Changer 
*/

/*    $wp_customize->add_setting('theme_style_select', array(
        'default'           =>  $theme_defaults["theme_style_select"],
        'capability'        => 'edit_theme_options',
        'type'           => 'theme_mod'
    ));
    $wp_customize->add_control(  new WP_Customize_Control($wp_customize, 'theme_style_select',  array(
    'label'      => esc_html__( 'Select Theme Style', 'woofood' ), //Admin-visible name of the control
    'description' => esc_html__( 'Using this option you can change the theme styling' ),
    'settings'   => 'theme_style_select', //Which setting to load and manipulate (serialized is okay)
    'priority'   => 10, //Determines the order this control appears in for the specified section
    'section'    => 'theme_styles', //ID of the section this control should render in (can be one of yours, or a WordPress default section)
    'type'    => 'select',
    'choices' => array(
        'default' => 'Default',
        'style-1' => 'Style 1',
        'style-2' => 'Style 2',
        'style-3' => 'Style 3',
    )
);





    ));
*/




     $wp_customize->add_section( 'linje_google_fonts_section', array(
            'title'       => esc_html__( 'Google Fonts', 'linje' ),
            'priority'       => 24,
        ) );
        $font_choices = array(
            'Source Sans Pro:400,700,400italic,700italic' => 'Source Sans Pro',
            'Open Sans:400italic,700italic,400,700' => 'Open Sans',
            'Oswald:400,700' => 'Oswald',
            'Playfair Display:400,700,400italic' => 'Playfair Display',
            'Montserrat:400,700' => 'Montserrat',
            'Raleway:400,700' => 'Raleway',
            'Droid Sans:400,700' => 'Droid Sans',
            'Lato:400,700,400italic,700italic' => 'Lato',
            'Arvo:400,700,400italic,700italic' => 'Arvo',
            'Lora:400,700,400italic,700italic' => 'Lora',
            'Merriweather:400,300italic,300,400italic,700,700italic' => 'Merriweather',
            'Oxygen:400,300,700' => 'Oxygen',
            'PT Serif:400,700' => 'PT Serif',
            'PT Sans:400,700,400italic,700italic' => 'PT Sans',
            'PT Sans Narrow:400,700' => 'PT Sans Narrow',
            'Cabin:400,700,400italic' => 'Cabin',
            'Fjalla One:400' => 'Fjalla One',
            'Francois One:400' => 'Francois One',
            'Josefin Sans:400,300,600,700' => 'Josefin Sans',
            'Libre Baskerville:400,400italic,700' => 'Libre Baskerville',
            'Arimo:400,700,400italic,700italic' => 'Arimo',
            'Ubuntu:400,700,400italic,700italic' => 'Ubuntu',
            'Bitter:400,700,400italic' => 'Bitter',
            'Droid Serif:400,700,400italic,700italic' => 'Droid Serif',
            'Roboto:400,400italic,700,700italic' => 'Roboto',
            'Open Sans Condensed:700,300italic,300' => 'Open Sans Condensed',
            'Roboto Condensed:400italic,700italic,400,700' => 'Roboto Condensed',
            'Roboto Slab:400,700' => 'Roboto Slab',
            'Yanone Kaffeesatz:400,700' => 'Yanone Kaffeesatz',
            'Rokkitt:400' => 'Rokkitt',
        );
        $wp_customize->add_setting( 'linje_headings_fonts', array(
                'sanitize_callback' => 'linje_sanitize_fonts',
            )
        );
        $wp_customize->add_control( 'linje_headings_fonts', array(
                'type' => 'select',
                'description' => esc_html__('Select your desired font for the headings.', 'linje'),
                'section' => 'linje_google_fonts_section',
                'choices' => $font_choices
            )
        );
        $wp_customize->add_setting( 'linje_body_fonts', array(
                'sanitize_callback' => 'linje_sanitize_fonts'
            )
        );
        $wp_customize->add_control( 'linje_body_fonts', array(
                'type' => 'select',
                'description' => esc_html__( 'Select your desired font for the body.', 'linje' ),
                'section' => 'linje_google_fonts_section',
                'choices' => $font_choices
            )
        );




}
add_action( 'customize_register', 'woofood_customize_register' );








/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function woofood_customize_preview_js() {
	wp_enqueue_script( 'woofood_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'woofood_customize_preview_js' );






function linje_scripts() {
    $headings_font = esc_html(get_theme_mod('linje_headings_fonts'));
    $body_font = esc_html(get_theme_mod('linje_body_fonts'));
    if( $headings_font ) {
        wp_enqueue_style( 'linje-headings-fonts', 'https://fonts.googleapis.com/css?family='. $headings_font );
    } else {
        wp_enqueue_style( 'linje-source-sans', 'https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic');
    }
    if( $body_font ) {
        wp_enqueue_style( 'linje-body-fonts', 'https://fonts.googleapis.com/css?family='. $body_font );
    } else {
        wp_enqueue_style( 'linje-source-body', 'https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,700,600');
    }
}
add_action( 'wp_enqueue_scripts', 'linje_scripts' );
/**
 * Google Fonts
 */

//Sanitizes Fonts
function linje_sanitize_fonts( $input ) {
    $valid = array(
        'Source Sans Pro:400,700,400italic,700italic' => 'Source Sans Pro',
        'Open Sans:400italic,700italic,400,700' => 'Open Sans',
        'Oswald:400,700' => 'Oswald',
        'Playfair Display:400,700,400italic' => 'Playfair Display',
        'Montserrat:400,700' => 'Montserrat',
        'Raleway:400,700' => 'Raleway',
        'Droid Sans:400,700' => 'Droid Sans',
        'Lato:400,700,400italic,700italic' => 'Lato',
        'Arvo:400,700,400italic,700italic' => 'Arvo',
        'Lora:400,700,400italic,700italic' => 'Lora',
        'Merriweather:400,300italic,300,400italic,700,700italic' => 'Merriweather',
        'Oxygen:400,300,700' => 'Oxygen',
        'PT Serif:400,700' => 'PT Serif',
        'PT Sans:400,700,400italic,700italic' => 'PT Sans',
        'PT Sans Narrow:400,700' => 'PT Sans Narrow',
        'Cabin:400,700,400italic' => 'Cabin',
        'Fjalla One:400' => 'Fjalla One',
        'Francois One:400' => 'Francois One',
        'Josefin Sans:400,300,600,700' => 'Josefin Sans',
        'Libre Baskerville:400,400italic,700' => 'Libre Baskerville',
        'Arimo:400,700,400italic,700italic' => 'Arimo',
        'Ubuntu:400,700,400italic,700italic' => 'Ubuntu',
        'Bitter:400,700,400italic' => 'Bitter',
        'Droid Serif:400,700,400italic,700italic' => 'Droid Serif',
        'Roboto:400,400italic,700,700italic' => 'Roboto',
        'Open Sans Condensed:700,300italic,300' => 'Open Sans Condensed',
        'Roboto Condensed:400italic,700italic,400,700' => 'Roboto Condensed',
        'Roboto Slab:400,700' => 'Roboto Slab',
        'Yanone Kaffeesatz:400,700' => 'Yanone Kaffeesatz',
        'Rokkitt:400' => 'Rokkitt',
    );
    if ( array_key_exists( $input, $valid ) ) {
        return $input;
    } else {
        return '';
    }
}


function woofood_customize_fonts()
{
    //Fonts
    $headings_font = esc_html(get_theme_mod('linje_headings_fonts'));
    $body_font = esc_html(get_theme_mod('linje_body_fonts'));
    $custom ="";
    if ( $headings_font ) {
        $font_pieces = explode(":", $headings_font);
        $custom .= "h1, h2, h3, h4, h5, h6 { font-family: {$font_pieces[0]}; }"."\n";
    }
    if ( $body_font ) {
        $font_pieces = explode(":", $body_font);
        $custom .= "body, button, input, select, textarea { font-family: {$font_pieces[0]}; }"."\n";
    }
    ?>
         <style type="text/css">
             .top-bar {
              background: <?php echo get_option('woofood_top_bar_background_color'); ?>; 
              color: <?php echo get_option('woofood_top_bar_text_color'); ?>; 

          }
          <?php echo $custom; ?>
         </style>
    <?php

    

}
add_action( 'wp_head', 'woofood_customize_fonts');





function woofood_customizer_custom_control_css() { 
    ?>
    <style>
    .customize-control-radio-image .image.ui-buttonset input[type=radio] {
        height: auto; 
    }
    .customize-control-radio-image .image.ui-buttonset label {
        display: inline-block;
        margin-right: 5px;
        margin-bottom: 5px; 
    }
    .customize-control-radio-image .image.ui-buttonset label.ui-state-active {
        background: none;
    }
    .customize-control-radio-image .customize-control-radio-buttonset label {
        padding: 5px 10px;
        background: #f7f7f7;
        border-left: 1px solid #dedede;
        line-height: 35px; 
    }
    .customize-control-radio-image label img {
        border: 1px solid #bbb;
        opacity: 0.5;
    }
     .customize-control-radio-image label i {
        border: 1px solid #bbb;
        opacity: 0.5;
    }
    #customize-controls .customize-control-radio-image label img {
        max-width: 100%;
        height: auto;
    }
     #customize-controls .customize-control-radio-image label i {
        max-width: 100%;
        height: auto;
    }
    .customize-control-radio-image label.ui-state-active img {
        background: #dedede; 
        border-color: #000; 
        opacity: 1;
    }
    .customize-control-radio-image label.ui-state-active i {
        background: #dedede; 
        border-color: #000; 
        opacity: 1;
    }
    .customize-control-radio-image label.ui-state-hover img {
        opacity: 0.9;
        border-color: #999; 
    }
     .customize-control-radio-image label.ui-state-hover i {
        opacity: 0.9;
        border-color: #999; 
    }
    .customize-control-radio-buttonset label.ui-corner-left {
        border-radius: 3px 0 0 3px;
        border-left: 0; 
    }
    .customize-control-radio-buttonset label.ui-corner-right {
        border-radius: 0 3px 3px 0; 
    }
    </style>
    <?php
}
add_action( 'customize_controls_print_styles', 'woofood_customizer_custom_control_css' );



