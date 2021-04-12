<?php
/**
 * WooFood functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WooFood
 */
require_once get_template_directory() . '/class-tgm-plugin-activation.php';

require_once( get_template_directory().'/inc/merlin/vendor/autoload.php' );
require_once( get_template_directory().'/inc/merlin/class-merlin.php' );
require_once( get_template_directory().'/inc/merlin-config.php' );
require_once( get_template_directory().'/inc/update.php' );

add_action( 'tgmpa_register', 'woofood_requires_register_required_plugins' );
//disable woocommerce styling//
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * Register the required plugins for this theme.
 *
 * In this example, we register five plugins:
 * - one included with the TGMPA library
 * - two from an external source, one from an arbitrary source, one from a GitHub repository
 * - two from the .org repo, where one demonstrates the use of the `is_callable` argument
 *
 * The variables passed to the `tgmpa()` function should be:
 * - an array of plugin arrays;
 * - optionally a configuration array.
 * If you are not changing anything in the configuration array, you can remove the array and remove the
 * variable from the function call: `tgmpa( $plugins );`.
 * In that case, the TGMPA default settings will be used.
 *
 * This function is hooked into `tgmpa_register`, which is fired on the WP `init` action on priority 10.
 */
add_filter( 'woocommerce_enable_setup_wizard', function( $true ) { return false; } );
define("WOOFOOD_THEME_VERSION", "2.3.3");

function woofood_demo_import_files() {
 $demo_array = (array)json_decode(woofood_get_data("https://www.wpslash.com/content/files/woofood/get-demo/"), true);
  return  $demo_array;
}
add_filter( 'merlin_import_files', 'woofood_demo_import_files' );


function woofood_get_data($Url) {
    if (!function_exists('curl_init')){ 
        die('CURL is not installed!');
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $Url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}
function woofood_requires_register_required_plugins() {
  /*
   * Array of plugin arrays. Required keys are name and slug.
   * If the source is NOT from the .org repo, then source is also required.
   */
  $plugins = array(

   
    // This is an example of how to include a plugin from the WordPress Plugin Repository.
    array(
      'name'      => 'WooCommerce',
      'slug'      => 'woocommerce',
      'required'  => true,
    ),
    

    // This is an example of how to include a plugin bundled with a theme.
    array(
      'name'               => 'WooFood Plugin', // The plugin name.
      'slug'               => 'woofood-plugin', // The plugin slug (typically the folder name).
      'source'             => get_template_directory() . '/plugins/woofood-plugin.zip', // The plugin source.
      'required'           => true, // If false, the plugin is only 'recommended' instead of required.
     
      'force_activation'   => true, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
      'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
      'external_url'       => '', // If set, overrides default API URL and points to an external URL.
      'is_callable'        => '', // If set, this callable will be be checked for availability to determine if a plugin is active.
    ),
       array(
      'name'               => 'WooFood Gutenberg Blocks', // The plugin name.
      'slug'               => 'woofood-blocks', // The plugin slug (typically the folder name).
      'source'             => get_template_directory() . '/plugins/woofood-blocks.zip', // The plugin source.
      'required'           => true, // If false, the plugin is only 'recommended' instead of required.
     
      'force_activation'   => true, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
      'force_deactivation' => true, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
      'external_url'       => '', // If set, overrides default API URL and points to an external URL.
      'is_callable'        => '', // If set, this callable will be be checked for availability to determine if a plugin is active.
    ),

    // This is an example of the use of 'is_callable' functionality. A user could - for instance -
    // have WPSEO installed *or* WPSEO Premium. The slug would in that last case be different, i.e.
    // 'wordpress-seo-premium'.
    // By setting 'is_callable' to either a function from that plugin or a class method
    // `array( 'class', 'method' )` similar to how you hook in to actions and filters, TGMPA can still
    // recognize the plugin as being installed.
  

  );

  /*
   * Array of configuration settings. Amend each line as needed.
   *
   * TGMPA will start providing localized text strings soon. If you already have translations of our standard
   * strings available, please help us make TGMPA even better by giving us access to these translations or by
   * sending in a pull-request with .po file(s) with the translations.
   *
   * Only uncomment the strings in the config array if you want to customize the strings.
   */
  $config = array(
    'id'           => 'woofood',                 // Unique ID for hashing notices for multiple instances of TGMPA.
    'default_path' => '',                      // Default absolute path to bundled plugins.
    'menu'         => 'tgmpa-install-plugins', // Menu slug.
    'has_notices'  => true,                    // Show admin notices or not.
    'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
    'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
    'is_automatic' => true,                   // Automatically activate plugins after installation or not.
    'message'      => '',                      // Message to output right before the plugins table.

   
  );

  tgmpa( $plugins, $config );
}










if ( ! function_exists( 'woofood_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function woofood_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on WooFood, use a find and replace
	 * to change 'woofood' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'woofood', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'woofood_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );
}
endif;
add_action( 'after_setup_theme', 'woofood_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function woofood_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'woofood_content_width', 640 );
}
add_action( 'after_setup_theme', 'woofood_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function woofood_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'woofood' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'woofood' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'woofood_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function woofood_scripts() {

	wp_enqueue_script( 'woofood-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'woofood-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'woofood_scripts' );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function woofood_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'woofood_pingback_header' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Additional features to allow styling of the templates.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';





/* Theme setup */
function register_header_menu() {
  register_nav_menu('header-menu',__( 'Header Menu' ));
}
add_action( 'init', 'register_header_menu' );




      // The CSS files for Bootstrap
function theme_styles() {
  $rtl="";
  if(function_exists("woofood_plugin_is_rtl"))
  {
      $rtl = woofood_plugin_is_rtl();

  }
  else
  {
    $rtl="";
  }
//define("WOOFOOD_THEME_RTL", woofood_plugin_is_rtl());

   wp_enqueue_style('bootstrap-css', get_template_directory_uri() . '/css/bootstrap4/bootstrap.min'.$rtl.'.css', array(), WOOFOOD_THEME_VERSION, 'all');
 



     wp_enqueue_style('bsnav-css', get_template_directory_uri() . '/css/bsnav/bsnav.min'.$rtl.'.css', array('bootstrap-css'), WOOFOOD_THEME_VERSION, 'all');

    wp_enqueue_style('niceselect-css', get_template_directory_uri() . '/css/niceselect/nice-select'.$rtl.'.css', array('bootstrap-css'), WOOFOOD_THEME_VERSION, 'all');

     wp_enqueue_style('woofood-icons-theme', get_template_directory_uri() . '/css/icons.css', array(), WOOFOOD_THEME_VERSION, 'all'); 


  //wp_enqueue_style('jquery-mobile-css', get_template_directory_uri() . '/css/jquery-mobile/woofood.min.css', array(), '', 'all'); 

  //wp_enqueue_style('jquery-mobile-icons-css', get_template_directory_uri() . '/css/jquery-mobile/jquery.mobile.icons.min.css', array(), '', 'all'); 
   //wp_enqueue_style('jquery-mobile-structure-css', get_template_directory_uri() . '/css/jquery-mobile/jquery.mobile.structure-1.4.5.min.css', array(), '', 'all'); 
  $woofood_theme_style = get_theme_mod( 'theme_style_select' );

    if ($woofood_theme_style=="default" ||$woofood_theme_style=="style-1" || $woofood_theme_style=="style-3" || !$woofood_theme_style)
    {
      wp_enqueue_style('woofood-theme', get_template_directory_uri() . '/css/main'.$rtl.'.css', array(), WOOFOOD_THEME_VERSION, 'all');
      wp_enqueue_style('woocommerce-theme', get_template_directory_uri() . '/css/woocommerce'.$rtl.'.css', array(), WOOFOOD_THEME_VERSION, 'all');

    }

   

    if ($woofood_theme_style=="style-2")
    {
      wp_enqueue_style('woofood-theme', get_template_directory_uri() . '/css/main'.$rtl.'.css', array(), WOOFOOD_THEME_VERSION, 'all');
      wp_enqueue_style('woocommerce-theme', get_template_directory_uri() . '/css/woocommerce'.$rtl.'.css', array(), WOOFOOD_THEME_VERSION, 'all');

    }

}

// The JavaScript files for Bootstrap
function theme_js() {
  


    wp_enqueue_script( 'bsnav-js', get_template_directory_uri() .'/js/bsnav/bsnav.min.js', array('jquery'), WOOFOOD_THEME_VERSION, true );
    wp_enqueue_script( 'bootstrap-js', get_template_directory_uri() .'/js/bootstrap4/bootstrap.bundle.min.js', array('jquery'), WOOFOOD_THEME_VERSION, true );
    wp_enqueue_script( 'niceselect-js', get_template_directory_uri() .'/js/niceselect/jquery.nice-select.min.js', array('jquery'), WOOFOOD_THEME_VERSION, true );



}

//Load Options from Customizer
function theme_styling_options(){

   $woofood_top_bar_background_color = get_theme_mod( 'woofood_top_bar_background_color' );
   $woofood_top_bar_text_color = get_theme_mod( 'woofood_top_bar_text_color' );

	$woofood_menu_bar_background_color = get_theme_mod( 'woofood_menu_bar_background_color' );


	$woofood_menu_text_color = get_theme_mod( 'woofood_menu_text_color' );
	$woofood_menu_text_hover_color = get_theme_mod( 'woofood_menu_text_hover_color' );
	$woofood_menu_text_active_color = get_theme_mod( 'woofood_menu_text_active_color' );


	$woofood_menu_background_color = get_theme_mod( 'woofood_menu_background_color' );
	$woofood_menu_background_hover_color = get_theme_mod( 'woofood_menu_background_hover_color' );
	$woofood_menu_background_active_color = get_theme_mod( 'woofood_menu_background_active_color' );

	$woofood_footer_text_color = get_theme_mod( 'woofood_footer_text_color' );
	$woofood_footer_background_color = get_theme_mod( 'woofood_footer_background_color' );

	$woofood_widget_text_color = get_theme_mod( 'woofood_widget_text_color' );
	$woofood_widget_background_color = get_theme_mod( 'woofood_widget_background_color' );

  $woofood_accordion_text_color = get_theme_mod( 'woofood_accordion_text_color' );
  $woofood_accordion_background_color = get_theme_mod( 'woofood_accordion_background_color' );

  $woofood_button_text_color = get_theme_mod( 'woofood_button_text_color' );
  $woofood_button_background_color = get_theme_mod( 'woofood_button_background_color' );
  $woofood_header_menu_text_transform = get_option( 'woofood_header_menu_text_transform' );

  $woofood_header_menu_text_font_size = get_option( 'woofood_header_menu_text_font_size' );
  $woofood_header_menu_text_spacing = get_option( 'woofood_header_menu_text_spacing' );
  $woofood_header_max_logo_width = get_option( 'woofood_header_max_logo_width' );
  $woofood_header_padding = get_option( 'woofood_header_padding' );
  $woofood_header_menu_text_style = get_option( 'woofood_header_menu_text_style' );
  $menu_text_decoration = "";
  if(!empty( $woofood_header_menu_text_style))
  {
    $woofood_header_menu_text_style = explode(",", $woofood_header_menu_text_style);
    if(in_array("bold",$woofood_header_menu_text_style))
    {
        $menu_text_decoration .= "font-weight:bold;";

    }
     if(in_array("uppercase",$woofood_header_menu_text_style))
    {
        $menu_text_decoration .= "text-transform:uppercase;";

    }
    if(in_array("italic",$woofood_header_menu_text_style))
    {
        $menu_text_decoration .= "font-style:italic;";

    }
     if(in_array("underline",$woofood_header_menu_text_style))
    {
        $menu_text_decoration .= "text-decoration:underline;";

    }

  }

 echo '<style type="text/css">
 .header, .bsnav-mobile .navbar {
 	background: ' . $woofood_menu_bar_background_color . '!important; 
 	border-color: ' . $woofood_menu_bar_background_color . '!important; 




 }
  .header {
  padding: ' . $woofood_header_padding . 'px!important; 




 }
 .navbar-brand>img
 {
  max-width:'.$woofood_header_max_logo_width.'px!important;
 }
 .top-bar {
              background:'.$woofood_top_bar_background_color.'; 
              color: '.$woofood_top_bar_text_color.'; 

          }
 .header .navbar-nav li a, .bsnav-mobile .navbar-nav li a { 
 	color: ' . $woofood_menu_text_color . '!important; 
 	background: ' . $woofood_menu_background_color . '!important; 
  
  font-size: '. $woofood_header_menu_text_font_size . 'px;
  '.$menu_text_decoration.'
}
.cart-icon i, .cart-icon span, .header-cart span, .header-cart i, .cart-icon a
{
  color: ' . $woofood_menu_text_color . '!important; 

}
.navbar-toggler i 
{
    color: ' . $woofood_menu_text_color . '!important; 

}
.navbar-toggler .navbar-toggler-icon, .navbar-toggler .navbar-toggler-icon::after, .navbar-toggler .navbar-toggler-icon::before
{
      background: ' . $woofood_menu_text_color . '!important; 

}
.navbar-expand-sm .navbar-nav .nav-link
{
       padding-left: ' . $woofood_header_menu_text_spacing . 'px!important; 
      padding-right: ' . $woofood_header_menu_text_spacing . 'px!important; 

}

.dropdown-menu{
  background: ' . $woofood_menu_bar_background_color . '!important; 
  border-color: ' . $woofood_menu_bar_background_color . '!important; 

}

.dropdown-menu .menu-col a{

    color: ' . $woofood_menu_text_color . '!important; 

}

.navbar-toggle span { 
  background: ' . $woofood_menu_text_color . '!important; 
}

.header .navbar-nav>li>a:focus, .navbar-default .navbar-nav>li>a:hover{ 
 	color: ' . $woofood_menu_text_hover_color . '!important; 
 	background: ' . $woofood_menu_background_hover_color . '!important; 
}
.header .navbar-nav>.active>a, .navbar-default .navbar-nav>.active>a:focus, .navbar-default .navbar-nav>.active>a:hover{
	
	color: ' . $woofood_menu_text_active_color . '!important; 
 	background: ' . $woofood_menu_background_active_color . '!important; 

}
.footer{

	color: ' . $woofood_footer_text_color . '!important; 
 	background: ' . $woofood_footer_background_color . '!important; 


}
.footer a ,.footer span, .footer i{

  color: ' . $woofood_footer_text_color . '!important; 


}
.widget-title{

	color: ' . $woofood_widget_text_color . '!important; 
 	background: ' . $woofood_widget_background_color . '!important; 


}

.widget-title{

  color: ' . $woofood_widget_text_color . '!important; 
  background: ' . $woofood_widget_background_color . '!important; 


}


.woofood-accordion .panel-heading{
 color: ' . $woofood_accordion_text_color . '!important; 
  background: ' . $woofood_accordion_background_color . '!important; 

  }

  .add_to_cart_button{

 color: ' . $woofood_button_text_color . '!important; 
  background: ' . $woofood_button_background_color . '!important; 


  }

  .single_add_to_cart_button{
 color: ' . $woofood_button_text_color . '!important; 
  background: ' . $woofood_button_background_color . '!important; 

  }

  .checkout-button.button.alt.wc-forward{
 color: ' . $woofood_button_text_color . '!important; 
  background: ' . $woofood_button_background_color . '!important; 


  }


 </style>';




}


add_action( 'wp_enqueue_scripts', 'theme_styles' );
add_action( 'wp_enqueue_scripts', 'theme_js' );
add_action( 'wp_head', 'theme_styling_options' );
//add styles also to admin for gutenberg blocks//
//add_action( 'admin_enqueue_scripts', 'theme_styles' );

 function woofood_blocks_styles_scripts_admin()
{
  global $pagenow ,$post;
  if ((( $pagenow == 'post.php' || $pagenow == 'post-new.php') && 'page' == $post->post_type)) {
theme_styles();
echo "<style>
.editor-writing-flow .wp-block {
  max-width: 1100px;
}
</style>";
//theme_js();

}

}
add_action( 'admin_enqueue_scripts', 'woofood_blocks_styles_scripts_admin' );

    // Register WP Navigation Walker
	require_once('bootstrap-navwalker.php');
    // Register YAMM Navigation Walker
//	require_once('yamm_nav_walker.php');


    // Bootstrap navigation


// Bootstrap mega menu navigation
/*function bootstrap_menu_nav()
{
	wp_nav_menu( array(
        'theme_location'    => 'header-menu',
        'depth'             => 4,
        'container'         => 'div',
        'container_class'   => 'collapse navbar-collapse',
        'container_id'      => 'bootstrap-navbar-collapse-1',
        'menu_class'        => 'nav navbar-nav yamm',
        'fallback_cb'       => 'Yamm_Nav_Walker_menu_fallback',
        'walker'            => new Yamm_Nav_Walker())
    );
}*/


function bootstrap_menu_nav($menu_classes)
{
   wp_nav_menu( array(
            'theme_location' => 'header-menu', // Defined when registering the menu
            'menu_id'        => 'primary-menu',
            'container'      => false,
            'depth'          => 3,
            'menu_class'     => $menu_classes,
            'walker'         => new WP_Bootstrap_Navwalker(), // This controls the display of the Bootstrap Navbar
            'fallback_cb'    => 'WP_Bootstrap_Navwalker::fallback', // For menu fallback
        ) );
}


/*unhook Woocommerce wrappers*/
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

//declare WC support
function woofood_wc_support() {
  add_theme_support( 'woocommerce' );


}
add_action( 'after_setup_theme', 'woofood_wc_support' );

// remove default sorting dropdown
 
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

// Remove the result count from WooCommerce
remove_action( 'woocommerce_before_shop_loop' , 'woocommerce_result_count', 20 );


//reload cart count on header//
add_filter( 'woocommerce_add_to_cart_fragments', 'iconic_cart_count_fragments', 10, 1 );

function iconic_cart_count_fragments( $fragments ) {
    $cart_icon = get_option("woofood_header_cart_icon_selected");

    //$fragments['div.header-cart-count'] = '<div class="header-cart-count">' . WC()->cart->get_cart_contents_count() . '</div>';
  //  $fragments['div.header-cart-items'] = '<div class="header-cart-items col-lg-8 col-md-8 col-xs-8"><span>'.WC()->cart->get_cart_contents_count(). " ".__('items in cart', 'woofood').'</span></div>';
   // $fragments['div.cart-icon'] = '<div class="cart-icon p-3 m-auto">'.esc_html__('Total:', 'woofood').':'.$woocommerce->cart->get_cart_subtotal().'<i class="fas fa-shopping-cart"></i><div>';
   
   $fragments['div.header-cart'] = '<div class="header-cart">
                 <div class="d-flex align-items-center justify-content-center" >

    <div class="cart-icon">
     
                     <i class="'.$cart_icon.'"><div class="header-cart-count">'.WC()->cart->get_cart_contents_count().'</div></i>
                                 </div>
                                 <span class="m-3 p-0 float-left">'.WC()->cart->get_cart_contents_count(). " ".__('items in cart', 'woofood').'</span>
                                 </div>
                                 </div>';
      $fragments['div.header-cart-center'] = '<div class="header-cart-center">

    <div class="cart-icon mx-auto m-auto p-0 float-left">
                      
                       <a href="'.wc_get_cart_url().'"><i class="'.$cart_icon.'"><div class="header-cart-count">'.WC()->cart->get_cart_contents_count().'</div></i></a>
                                 </div>
                                 </div>';                            
    return $fragments;
    
}





/* Change Product Quantity Inputn */
function woocommerce_quantity_input($data = null) {
 global $product;

if (!$data) {

$defaults = array(
'input_name'   => 'quantity',
'input_value'   => '1',
'max_value'     => apply_filters( 'woocommerce_quantity_input_max', '', $product ),
'min_value'     => apply_filters( 'woocommerce_quantity_input_min', '', $product ),
'step'         => apply_filters( 'woocommerce_quantity_input_step', '1', $product ),
'style'         => apply_filters( 'woocommerce_quantity_style', 'float:left;', $product )
);
} else {
$defaults = array(
 
'input_name'   => isset($data["input_name"]) ? $data["input_name"] : 'quantity',
 
'input_value'   => $data['input_value'],
'step'         => apply_filters( 'woocommerce_quantity_input_max', '', $product ),
 
'max_value'     => apply_filters( 'woocommerce_quantity_input_max', '', $product ),
'min_value'     => apply_filters( 'woocommerce_quantity_input_min', '', $product ),
'step'         => apply_filters( 'woocommerce_quantity_input_step', '1', $product ),
'style'         => apply_filters( 'woocommerce_quantity_style', 'float:left;', $product )
 
);
 
}
  
if (!empty($defaults['max_value']))
 {
    $max = $defaults['max_value'];

 }
  else
  {
    $max = 20;
  } 

   if (!empty($defaults['input_value']))
   {
      $current_value = $defaults['input_value'];

   }
  else {
    $current_value = 1;
  }

   if (!empty($defaults['input_name']))
   {
      $current_input_name = $defaults['input_name'];

   }
  else 
    {
      $current_input_name = "quantity";
    }
   
 if (!empty($defaults['step']))
 {
    $step = $defaults['step'];

 }
  else
  {
    $step = 1;
  } 
  
  if (!empty($defaults['min_value']))
  {
      $min = $defaults['min_value'];
      $current_value =  $min;


  }
  else{
    $min = 1; 
  } 
   
 $options = '';
 for($count = $min;$count <= $max;$count = $count+$step){
  $options .= '<option value="' . $count . '">' . $count . '</option>';
 }

 
  
 echo '<div class="quantity" style="' . $defaults['style'] . '"> <a class="minus-qty qty-change-button"><i class="woofood-icon-minus"></i></a><input type="number" class="input-text qty text" min="'.$min.'"  value="'.$current_value.'" step="'.$step.'" max="'.$max.'" name="'.$current_input_name.'" title="' . _x( 'Qty', 'Product quantity input tooltip', 'woocommerce' ) . '" value="'.$current_value .'" class="qty"><a class="plus-qty qty-change-button"><i class="woofood-icon-plus"></i></a></div>';
  ?>
 
<?php
}

//change quantity styling ..add javascript//
function qty_woofood_change_style(){
    if( ! is_admin() || is_admin() ) { ?>
  <script>
    
    jQuery(function($) {

$( document ).on( 'click', '.minus-qty', function() {
     var $input = jQuery(this).next('input.qty');
     var min = $input.attr('min');
     var count = parseInt($input.val()) - 1;
     count = count < 1 ? 1 : count;
      if(min> 0)
      {
         if(count >= min)
     {
     $input.val(count);
     $input.change();
     }

      }
      else
      {
      $input.val(count);
     $input.change();

      }
    
   
     $( 'div.woocommerce > form input[name="update_cart"]' ).prop( 'disabled', false );
     return false;
});


$( document ).on( 'click', '.plus-qty', function() {
     var $input = jQuery(this).prev('input.qty');
     var max = $input.attr('max');
     var count = parseInt($input.val()) + 1;


      if(max > 0)
      {
            if(count <= max)
     {
     $input.val(count);
     $input.change();
     }

      }
      else
      {
         $input.val(count);
     $input.change();

      }

  
     $( 'div.woocommerce > form input[name="update_cart"]' ).prop( 'disabled', false );
     return false;
});

});

  

</script>
<?php }
}
//add_action( 'woocommerce_before_add_to_cart_button', 'qty_woofood_change_style' );
//add_action( 'woocommerce_after_cart_table', 'qty_woofood_change_style' );
add_action( 'wp_footer', 'qty_woofood_change_style' );





add_filter( 'woocommerce_form_field_args', 'woofood_bootstrap_fields_checkout', 10, 3 );
function woofood_bootstrap_fields_checkout( $args, $key, $value ) { 
     $args['input_class'] =array( 'form-control' );
           
     $args['class'][] ='form-group';
    




    return $args;
};





function pt_login_register_modal() {
    // only show the registration/login form to non-logged-in members
  if( ! is_user_logged_in() ){ 
?>
    <div class="modal fade pt-user-modal" id="pt-user-modal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog" data-active-tab="">
        <div class="modal-content">
          <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php
              if( get_option('users_can_register') ){ ?>

                <!-- Register form -->
                <div class="pt-register">
               
                  <h3><?php printf( esc_html__('Join %s', 'woofood'), get_bloginfo('name') ); ?></h3>
                  <hr>

                  <form id="pt_registration_form" action="<?php echo home_url( '/' ); ?>" method="POST">

                    <?php

                    $form_fields = woofood_get_login_register_fields();

                    foreach($form_fields as $name => $settings)
                    {
                      if($settings["type"] =="text" || $settings["type"] =="password")
                      {


                      ?>
                       <div class="form-field <?php if($settings["required"]) { echo " required"; } ?>">
                      <input class="form-control input-lg" name="<?php echo $name; ?>" placeholder="<?php echo $settings["placeholder"]; ?>" type="<?php echo $settings["type"]; ?>"/>
                    </div>

                      <?php
                       }
                       else if ($settings["type"] =="select")
                       {
                        ?>
                         <div class="form-field <?php if($settings["required"]) { echo " required"; } ?>">
                      <select class="form-control input-lg" name="<?php echo $name; ?>" type="<?php echo $settings["type"]; ?>">
                        <?php foreach($settings["options"] as $option_value =>$option_name): ?>
                          <option value="<?php echo $option_value;?>"><?php echo $option_name;?></option>
                        <?php endforeach; ?>

                      </select>
                    </div>

                        <?php


                       }

                        else if($settings["type"] =="checkbox")
                      {


                      ?>
                       <div class="form-field <?php if($settings["required"]) { echo " required"; } ?>">
                      <input  name="<?php echo $name; ?>" placeholder="<?php echo $settings["placeholder"]; ?>" type="<?php echo $settings["type"]; ?>" value="1" /><label for="<?php echo $name; ?>"><?php echo $settings["placeholder"]; ?></label>
                    </div>

                      <?php
                       }



                    }


                     ?>

                   
                    <div class="form-actions">
                      <input type="hidden" name="action" value="pt_register_member"/>
                      <button class="btn btn-theme btn-lg" data-loading-text="<?php esc_html_e('Loading...', 'woofood') ?>" type="submit"><?php esc_html_e('Sign up', 'woofood'); ?></button>
                              <?php do_action('woofood_login_popup_after_register'); ?>

                    </div>
                    <?php wp_nonce_field( 'ajax-login-nonce', 'register-security' ); ?>
                  </form>
                  <div class="pt-errors"></div>
                </div>

                <!-- Login form -->
                <div class="pt-login">
               
              
               
                  <form id="pt_login_form" action="<?php echo home_url( '/' ); ?>" method="post">

                    <div class="form-field">
                      <input class="form-control input-lg required" placeholder="<?php esc_html_e('username/email','woofood'); ?>" name="user_login" type="text"/>
                    </div>
                    <div class="form-field">
                      
                      <input class="form-control input-lg required" placeholder="<?php esc_html_e('password','woofood'); ?>" name="user_pass" id="pt_user_pass" type="password"/>
                    </div>
                    <div class="form-actions">
                      <input type="hidden" name="action" value="pt_login_member"/>
                      <button class="btn btn-theme btn-lg" data-loading-text="<?php esc_html_e('Loading...', 'woofood') ?>" type="submit"><?php esc_html_e('Login', 'woofood'); ?></button> 
                      <a class="lost-password" href="<?php echo wp_lostpassword_url(); ?>"><?php esc_html_e('Lost Password?', 'woofood') ?></a>
                    <?php do_action('woofood_login_popup_after_login'); ?>
                    </div>
                    <?php wp_nonce_field( 'ajax-login-nonce', 'login-security' ); ?>
                  </form>
                  <div class="pt-errors"></div>
                </div>

                <div class="pt-loading">
                  <p><i class="fa fa-refresh fa-spin"></i><br><?php esc_html_e('Loading...', 'woofood') ?></p>
                </div><?php
              } else {
                echo '<h3>'.esc_html__('Login access is disabled', 'woofood').'</h3>';
              } ?>
          </div>
          <div class="modal-footer">
              <span class="pt-register-footer"><?php esc_html_e('Don\'t have an account?', 'woofood'); ?> <a class="btn btn-theme" href="#pt-register"><?php esc_html_e('Sign Up', 'woofood'); ?></a></span>
              <span class="pt-login-footer"><?php esc_html_e('Already have an account?', 'woofood'); ?> <a class="btn btn-theme" href="#pt-login"><?php esc_html_e('Login', 'woofood'); ?></a></span>
          </div>        
        </div>
      </div>
    </div>
<?php
  }
}
add_action('wp_footer', 'pt_login_register_modal', 0);

//handle login//
function pt_login_member(){
      // Get variables
    $user_login   = $_POST['user_login']; 
    $user_pass    = $_POST['user_pass'];
    

    // Check CSRF token
    /*if( !check_ajax_referer( 'ajax-login-nonce', 'login-security', false) ){
      echo json_encode(array('error' => true, 'message'=> '<div class="alert alert-danger">'.__('Session token has expired, please reload the page and try again', 'woofood').'</div>'));
    }*/
    
    // Check if input variables are empty
  if( empty($user_login) || empty($user_pass) ){
      echo json_encode(array('error' => true, 'message'=> '<div class="alert alert-danger">'.__('Please fill all form fields', 'woofood').'</div>'));
    } else { // Now we can insert this account
      $user = wp_signon( array('user_login' => $user_login, 'user_password' => $user_pass), false );
        if( is_wp_error($user) ){
        echo json_encode(array('error' => true, 'message'=> '<div class="alert alert-danger">'.$user->get_error_message().'</div>'));
      } else{
        echo json_encode(array('error' => false, 'message'=> '<div class="alert alert-success">'.__('Login successful, reloading page...', 'woofood').'</div>'));
      }
    }
    die();
}
add_action('wp_ajax_nopriv_pt_login_member', 'pt_login_member');

// handle registration//
function pt_register_member(){
      // Get variables
   /* $user_pass  = $_POST['user_pass'];  
    $user_login = $_POST['user_login'];
    $first_name   = $_POST['first_name'];
    $last_name    = $_POST['last_name'];
    $billing_address_1    = $_POST['billing_address_1'];
    $billing_city   = $_POST['billing_city'];
    $billing_postcode   = $_POST['billing_postcode'];
    $billing_country    = $_POST['billing_country'];*/

    $all_fields =  woofood_get_login_register_fields();
    $required_fields = array();
        $posted_fields = array();
    $not_posted_required_fields = array();

  foreach($all_fields as $field_name =>$settings)
      {


        if(isset($_POST[$field_name]) && $_POST[$field_name]!="")
        {
            $posted_fields[$field_name] = $_POST[$field_name] ;

        }
        else
        {
          if($settings["required"])
        {
               $not_posted_required_fields[] = $field_name;
        }

        }
        if($settings["required"])
        {
            $required_fields[] =  $field_name;
        }

      }


/*    foreach ($_POST as $var_key=> $var_value){
    if (isset($var_value) && $var_value!="" ) {

      $posted_fields[$var_key] = $var_value ;


    }
    else if(in_array($var_key, $required_fields))
    {



     $not_posted_required_fields[] = $var_key;

    }
}*/


        /*  echo json_encode(array('error' => true, 'message'=> json_encode($posted_fields)));
          die();*/

    // Check CSRF token
    if( !check_ajax_referer( 'ajax-login-nonce', 'register-security', false) ){
      echo json_encode(array('error' => true, 'message'=> '<div class="alert alert-danger">'.esc_html__('Session token has expired, please reload the page and try again', 'woofood').'</div>'));
      die();
    }
    
    // Check if input variables are empty
    elseif(!empty($not_posted_required_fields)){
      echo json_encode(array('error' => true, 'message'=> '<div class="alert alert-danger">'.esc_html__('Please fill all form fields', 'woofood').'</div>'));
      die();
    }
    
    //$errors = register_new_user($user_login, $user_email);  
    $new_user_id = wp_create_user( $posted_fields["user_login"],$posted_fields["user_pass"],$posted_fields["user_login"]  );

    
    
    if( is_wp_error($new_user_id) ){
      $registration_error_messages = $new_user_id->errors;
      $display_errors = '<div class="alert alert-danger">';
      
        foreach($registration_error_messages as $error){
          $display_errors .= '<p>'.$error[0].'</p>';
        }
      $display_errors .= '</div>';
      echo json_encode(array('error' => true, 'message' => $display_errors));
    } else {
      echo json_encode(array('error' => false, 'message' => '<div class="alert alert-success">'.esc_html__( 'Registration complete. You can order now.', 'woofood').'</p><script> jQuery("#pt-user-modal").modal("hide"); window.location.reload(false); 
 </script>'));

      $user = new WP_User($new_user_id);
      $user->set_role('customer');

        $user_login ="";
        $user_pass ="";
        $first_name = "";
        $last_name = "";
        $display_name ="";
      foreach($posted_fields as $key =>$value)
      {

       

        if($key !="user_login" && $key !="user_pass")
        {
                       update_user_meta( $new_user_id, $key, sanitize_text_field( $value ) );

          
        }



      }
      if(array_key_exists("billing_first_name", $posted_fields))
      {
                $display_name =$posted_fields["billing_first_name"];


      }
       if(array_key_exists("billing_last_name", $posted_fields))
      {
                $display_name .=" ".$posted_fields["billing_last_name"];


      }
      update_user_meta( $new_user_id, "display_name", sanitize_text_field( $display_name ) );




             
             wp_set_current_user( $new_user_id );
            wp_set_auth_cookie( $new_user_id );




    }
   
    die();

}
add_action('wp_ajax_nopriv_pt_register_member', 'pt_register_member');



//load style and js//

function ajax_login_scripts() {
  if(function_exists("woofood_plugin_is_rtl"))
  {
      $rtl = woofood_plugin_is_rtl();

  }
  else
  {
    $rtl="";
  }
  $rtl =".rtl";
  $required_registration_fields = array('first_name', 'last_name', 'billing_address_1', 'billing_phone', 'user_login', 'user_pass', 'billing_postcode');
  $validation_messages = array('first_name'=>esc_html__('First Name is required!','woofood'), 'last_name'=>esc_html__('Last Name is required!','woofood'), 'billing_address_1'=>esc_html__('Address  is required!','woofood'), 'billing_phone'=>esc_html__('Phone is required!','woofood'), 'user_login'=>esc_html__('Email is required!','woofood'), 'user_pass'=>esc_html__('Password is required!','woofood'), 'billing_postcode'=>esc_html__('Postal Code is required!','woofood'));

  wp_enqueue_style( 'user-login', get_template_directory_uri() . '/css/user-login'.$rtl.'.css', array(), null );

  wp_enqueue_script( 'ajax-login-register-script', get_template_directory_uri() . '/js/user-login.js', array( 'jquery' ), null, true );

  wp_localize_script('ajax-login-register-script', 'ptajax', array( 
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'required' => $required_registration_fields,
            'validation_messages' => $validation_messages

          ));
}
add_action( 'wp_enqueue_scripts', 'ajax_login_scripts' );




//add login/register to menu//

add_filter( 'wp_nav_menu_items', 'pt_login_link_to_menu', 10, 2 );
function pt_login_link_to_menu ( $items, $args ) {
    if( ! is_user_logged_in() && $args->theme_location == apply_filters('login_menu_location', 'header-menu') ) {
        $items .= '<li class="menu-item login-link"><a href="#pt-login" class="nav-link">'.__( 'Login/Register', 'woofood' ).'</a></li>';
    }
    else if(is_user_logged_in() && $args->theme_location == apply_filters('login_menu_location', 'header-menu') ){
        $items .= '<li class="menu-item login-link "><a href="'.wp_logout_url(home_url()).'" class="nav-link">'.__( 'Logout', 'woofood' ).'</a></li>';
    
    }
    return $items;
}

add_filter( 'woocommerce_endpoint_order-received_title', 'woofood_theme_thankyou_title_hook' );
 
function woofood_theme_thankyou_title_hook( $old_title ){
 
  return '<div><svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="50" height="50" viewBox="0 0 24 24" style="fill: #cc0000;text-align: center;margin: 20px;border: 1px solid black;padding: 9px;border-radius: 99999px;"><g id="surface1"><path style=" fill-rule:evenodd;" d="M 22.59375 3.5 L 8.0625 18.1875 L 1.40625 11.5625 L 0 13 L 8.0625 21 L 24 4.9375 Z "></path></g></svg></div>'.$old_title;
 
}



function woofood_get_login_register_fields()
{
  $countries_obj   = new WC_Countries();
  $countries   = $countries_obj->get_allowed_countries();
  $fields = array(

    "user_login" => array("type"=>"text", "required"=> true, "placeholder"=>esc_html__('Your email address', 'woofood') ) ,
    "user_pass" => array("type"=>"password", "required"=> true, "placeholder"=>esc_html__('Password', 'woofood') ) ,
    "billing_first_name" => array("type"=>"text", "required"=> woofood_login_register_required_check('woofood_login_register_required_first_name'), "placeholder"=>esc_html__('First Name', 'woofood') ) ,
    "billing_last_name" => array("type"=>"text", "required"=> woofood_login_register_required_check('woofood_login_register_required_last_name'), "placeholder"=>esc_html__('Last Name', 'woofood') ) ,
    "billing_phone" => array("type"=>"text", "required"=> woofood_login_register_required_check('woofood_login_register_required_phone'), "placeholder"=>esc_html__('Telefono', 'woofood') ) ,
    "billing_address_1" => array("type"=>"text", "required"=> woofood_login_register_required_check('woofood_login_register_required_address'), "placeholder"=>esc_html__('Address', 'woofood') ) ,
    "billing_city" => array("type"=>"text", "required"=> woofood_login_register_required_check('woofood_login_register_required_city'), "placeholder"=>esc_html__('City', 'woofood') ) ,
    "billing_postcode" => array("type"=>"text", "required"=> woofood_login_register_required_check('woofood_login_register_required_postcode'), "placeholder"=>esc_html__('Postal Code', 'woofood') ) ,

    "billing_country" => array("type"=>"select", "required"=> false, "options"=>$countries ) 



  );

   if(woofood_login_register_required_check('woofood_login_register_gdpr_enabled'))
   {
    $fields["gdpr_checkbox"] =  array("type"=>"checkbox", "required"=> woofood_login_register_required_check('woofood_login_register_gdpr_enabled'), "placeholder"=>esc_html__('I accept the Privacy Policy in order to create an account', 'woofood') ) ;
  }

  return apply_filters("woofood_login_register_fields_filter", $fields);
}

function woofood_login_register_required_check($field)

{
  if ( get_theme_mod( $field ) == 1 ) { 
    return true ;
}
else
{
  return false ;

}
return false;
}




//add extra fields on registration page//
function wf_extra_register_fields() {?>
  <?php

                    $form_fields = woofood_get_login_register_fields();


                    unset($form_fields["user_login"]); 

                    unset($form_fields["user_pass"]); 

                    foreach($form_fields as $name => $settings)
                    {
                      if($settings["type"] =="text" || $settings["type"] =="password")
                      {


                      ?>
                       <div class="form-field <?php if($settings["required"]) { echo " required"; } ?>">
                      <input class="form-control input-lg" name="<?php echo $name; ?>" placeholder="<?php echo $settings["placeholder"]; ?>" type="<?php echo $settings["type"]; ?>"/>
                    </div>

                      <?php
                       }
                       else if ($settings["type"] =="select")
                       {
                        ?>
                         <div class="form-field <?php if($settings["required"]) { echo " required"; } ?>">
                      <select class="form-control input-lg" name="<?php echo $name; ?>" type="<?php echo $settings["type"]; ?>">
                        <?php foreach($settings["options"] as $option_value =>$option_name): ?>
                          <option value="<?php echo $option_value;?>"><?php echo $option_name;?></option>
                        <?php endforeach; ?>

                      </select>
                    </div>

                        <?php


                       }

                        else if($settings["type"] =="checkbox" && $name !="gdpr_checkbox" )
                      {


                      ?>
                       <div class="form-field <?php if($settings["required"]) { echo " required"; } ?>">
                      <input  name="<?php echo $name; ?>" placeholder="<?php echo $settings["placeholder"]; ?>" type="<?php echo $settings["type"]; ?>" value="1" /><label for="<?php echo $name; ?>"><?php echo $settings["placeholder"]; ?></label>
                    </div>

                      <?php
                       }



                    }


                    
 }
 add_action( 'woocommerce_register_form_start', 'wf_extra_register_fields');
function wf_save_extra_register_fields( $customer_id ) {

    $all_fields =  woofood_get_login_register_fields();
    $required_fields = array();
        $posted_fields = array();
    $not_posted_required_fields = array();

  foreach($all_fields as $field_name =>$settings)
      {


        if(isset($_POST[$field_name]) && $_POST[$field_name]!="")
        {
            $posted_fields[$field_name] = $_POST[$field_name] ;

        }
        else
        {
               $not_posted_required_fields[] = $field_name;

        }
        if($settings["required"])
        {
            $required_fields[] =  $field_name;
        }

      }


       foreach($posted_fields as $key =>$value)
      {

       

        if($key !="user_login" && $key !="user_pass")
        {
                       update_user_meta( $customer_id, $key, sanitize_text_field( $value ) );

          
        }



      }
      if(array_key_exists("billing_first_name", $posted_fields))
      {
                $display_name =$posted_fields["billing_first_name"];


      }
       if(array_key_exists("billing_last_name", $posted_fields))
      {
                $display_name .=" ".$posted_fields["billing_last_name"];


      }
      update_user_meta( $customer_id, "display_name", sanitize_text_field( $display_name ) );

 

}
add_action( 'woocommerce_created_customer', 'wf_save_extra_register_fields' );




add_action( 'woocommerce_register_post', 'wpslash_woofood_validate_register_fields', 10, 3 );
 
function wpslash_woofood_validate_register_fields( $username, $email, $errors ) {
if(!is_checkout())
    {
     $all_fields =  woofood_get_login_register_fields();
unset($all_fields["user_login"]);
unset($all_fields["user_pass"]);
unset($all_fields["gdpr_checkbox"]);

    $required_fields = array();
        $posted_fields = array();
    $not_posted_required_fields = array();

  foreach($all_fields as $field_name =>$settings)
      {


        if(isset($_POST[$field_name]) && $_POST[$field_name]!="")
        {
            $posted_fields[$field_name] = $_POST[$field_name] ;

        }
        else
        {
          if($field_name!="gdpr_checkbox")
          {
                           $not_posted_required_fields[] = $field_name;

          }

        }
        if($settings["required"])
        {
            $required_fields[] =  $field_name;
        }

      }
 
  if ( !empty( $not_posted_required_fields) ) {
    $errors->add( 'missing_fields_error', esc_html("Complete all Required fields", 'woofood') );
  }
 }
}


add_action( 'wp_enqueue_scripts', 'woofood_dequee_selectwoo_select2', 100 );
function woofood_dequee_selectwoo_select2() {
    if ( class_exists( 'woocommerce' ) ) {
        wp_dequeue_style( 'selectWoo' );
        wp_deregister_style( 'selectWoo' );
        wp_dequeue_script( 'selectWoo');
        wp_deregister_script('selectWoo');
    } 
} 


add_filter( 'woocommerce_account_menu_items', 'custom_remove_downloads_my_account', 999 );
 
function custom_remove_downloads_my_account( $items ) {
unset($items['downloads']);
return $items;
}


?>