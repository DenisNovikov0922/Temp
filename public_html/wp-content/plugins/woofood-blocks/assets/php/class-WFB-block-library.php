<?php
/**
 * Register the scripts, styles, and blocks needed for the block editor.
 * NOTE: DO NOT edit this file in WooCommerce core, this is generated from woocommerce-gutenberg-products-block.
 *
 * @package WooFood\Blocks
 * @version 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WooFood_Block_Library Class.
 */
class WooFood_Block_Library {

	/**
	 * Class instance.
	 *
	 * @var WooFood_Block_Library instance
	 */
	protected static $instance = null;

	/**
	 * Get class instance
	 */
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		if ( function_exists( 'register_block_type' ) ) {
			add_action( 'init', array( 'WooFood_Block_Library', 'init' ) );
		}
	}

	/**
	 * Initialize block library features.
	 */
	public static function init() {
		// Shortcut out if we see the feature plugin, v1.4 or below.
		// note: `FP_VERSION` is transformed to `WFB_VERSION` in the grunt copy task.
		if ( defined( 'FP_VERSION' ) && version_compare( FP_VERSION, '1.4.0', '<=' ) ) {
		//	return;
		}
		self::register_blocks();
		self::register_assets();
		add_action( 'admin_print_footer_scripts', array( 'WooFood_Block_Library', 'print_script_settings' ), 1 );
		add_action( 'body_class', array( 'WooFood_Block_Library', 'add_theme_body_class' ), 1 );
	}

	/**
	 * Get the file modified time as a cache buster if we're in dev mode.
	 *
	 * @param string $file Local path to the file.
	 * @return string The cache buster value to use for the given file.
	 */
	protected static function get_file_version( $file ) {
		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			$file = trim( $file, '/' );
			return filemtime( WFB_ABSPATH . $file );
		}
		return WFB_VERSION;
	}

	/**
	 * Registers a script according to `wp_register_script`, additionally loading the translations for the file.
	 *
	 * @since 2.0.0
	 *
	 * @param string $handle    Name of the script. Should be unique.
	 * @param string $src       Full URL of the script, or path of the script relative to the WordPress root directory.
	 * @param array  $deps      Optional. An array of registered script handles this script depends on. Default empty array.
	 * @param bool   $has_i18n  Optional. Whether to add a script translation call to this file. Default 'true'.
	 */
	protected static function register_script( $handle, $src, $deps = array(), $has_i18n = true ) {
		$filename = str_replace( plugins_url( '/', WFB_PLUGIN_FILE ), '', $src );
		$ver      = self::get_file_version( $filename );
		wp_register_script( $handle, $src, $deps, $ver, true );
		if ( $has_i18n && function_exists( 'wp_set_script_translations' ) ) {
			wp_set_script_translations( $handle, 'woo-gutenberg-products-block', WFB_ABSPATH . 'languages' );
		}
	}

	/**
	 * Registers a style according to `wp_register_style`.
	 *
	 * @since 2.0.0
	 *
	 * @param string $handle Name of the stylesheet. Should be unique.
	 * @param string $src    Full URL of the stylesheet, or path of the stylesheet relative to the WordPress root directory.
	 * @param array  $deps   Optional. An array of registered stylesheet handles this stylesheet depends on. Default empty array.
	 * @param string $media  Optional. The media for which this stylesheet has been defined. Default 'all'. Accepts media types like
	 *                       'all', 'print' and 'screen', or media queries like '(orientation: portrait)' and '(max-width: 640px)'.
	 */
	protected static function register_style( $handle, $src, $deps = array(), $media = 'all' ) {
		$filename = str_replace( plugins_url( '/', WFB_PLUGIN_FILE ), '', $src );
		$ver      = self::get_file_version( $filename );
		wp_register_style( $handle, $src, $deps, $ver, $media );
	}

	/**
	 * Register block scripts & styles.
	 *
	 * @since 2.0.0
	 */
	public static function register_assets() {
		self::register_style( 'wf-block-editor', plugins_url( 'build/editor.css', WFB_PLUGIN_FILE ), array( 'wp-edit-blocks' ) );
		self::register_style( 'wf-block-style', plugins_url( 'build/style.css', WFB_PLUGIN_FILE ), array() );

		// Shared libraries and components across all blocks.
		self::register_script( 'wf-blocks', plugins_url( 'build/blocks.js', WFB_PLUGIN_FILE ), array(), false );
		self::register_script( 'wf-vendors', plugins_url( 'build/vendors.js', WFB_PLUGIN_FILE ), array(), false );

		$block_dependencies = array(
			'wp-api-fetch',
			'wp-blocks',
			'wp-components',
			'wp-compose',
			'wp-data',
			'wp-date',
			'wp-dom',
			'wp-element',
			'wp-editor',
			'wp-hooks',
			'wp-i18n',
			'wp-url',
			'lodash',
			'wf-blocks',
			'wf-vendors',
		);
		self::register_script( 'wf-handpicked-products-tabs', plugins_url( 'build/handpicked-products-tabs.js', WFB_PLUGIN_FILE ), $block_dependencies );

		self::register_script( 'wf-handpicked-products', plugins_url( 'build/handpicked-products.js', WFB_PLUGIN_FILE ), $block_dependencies );
	}

	/**
	 * Register blocks, hooking up assets and render functions as needed.
	 *
	 * @since 2.0.0
	 */
	public static function register_blocks() {
		require_once dirname( __FILE__ ) . '/class-WFB-block-grid-base.php';
		require_once dirname( __FILE__ ) . '/class-WFB-block-featured-product.php';

		register_block_type(
			'woofood/accordion-handpicked',
			array(
				'render_callback' => array(__CLASS__, 'render_handpicked_products'),
				'editor_script'   => 'wf-handpicked-products',
				'editor_style'    => 'wf-block-editor',
				'style'           => 'wf-block-style',
				'attributes'      => array(
					'columns'           => array(
						'type'    => 'number',
						'default' => wc_get_theme_support( 'product_blocks::default_columns', 2 ),
					),
					'editMode'          => array(
						'type'    => 'boolean',
						'default' => true,
					),
					'orderby'           => array(
						'type'    => 'string',
						//'enum'    => array( 'date', 'popularity', 'price_asc', 'price_desc', 'rating', 'title', 'menu_order' ),
						'default' => 'date',
					),
					'order'           => array(
						'type'    => 'string',
						'enum'    => array( 'ASC', 'DESC' ),
						'default' => 'DESC',
					),
					'products'          => array(
						'type'    => 'array',
						'items'   => array(
							'type' => 'number',
						),
						'default' => array(),
					),
					'title'          => array(
						'type'    => 'string',
						'default' => esc_html__('Type a Name', 'woofood'),
					),
						'BackgroundColor'          => array(
						'type'    => 'string',
						'default' => '',
					),
						'titleTextColor'          => array(
						'type'    => 'string',
						'default' => '',
					),
						'borderColor'          => array(
						'type'    => 'string',
						'default' => '',
					),
						'icon'          => array(
						'type'    => 'string',
						'default' => '',
					),
					'contentVisibility' => self::get_schema_content_visibility(),
				),
			)
		);


		register_block_type(
			'woofood/tabs-handpicked',
			array(
				'render_callback' => array(__CLASS__, 'render_handpicked_products_tabs'),
				'editor_script'   => 'wf-handpicked-products-tabs',
				'editor_style'    => 'wf-block-editor',
				'style'           => 'wf-block-style',
				'attributes'      => array(

					'editMode' => array(
						'type'    => 'boolean',
						'default' => false,
					),
					'BackgroundColor'          => array(
						'type'    => 'string',
						'default' => '',
					),
						'titleTextColor'          => array(
						'type'    => 'string',
						'default' => '',
					),
						'borderColor'          => array(
						'type'    => 'string',
						'default' => '',
					),
					'tabs'          => array(

					'type'    => 'array',
					'items'   => array(
							'type' => 'object',
						),
					


						'default' => array(
							'align' =>   array(
						'type'    => 'string',
						'default' => '',
					),
							'columns'  => wc_get_theme_support( 'product_blocks::default_columns', 2 ),
							'editMode' => array(
						'type'    => 'boolean',
						'default' => true,
					),
								'order'           => array(
						'type'    => 'string',
						'enum'    => array( 'ASC', 'DESC'),
						'default' => 'DESC',
					),
							'order_by'           => array(
						'type'    => 'string',
						'enum'    => array( 'date', 'popularity', 'price_asc', 'price_desc', 'rating', 'title', 'menu_order' ),
						'default' => 'date',
					),
								
								'products'          => array(
						'type'    => 'array',
						'items'   => array(
							'type' => 'number',
						),
						'default' => array(),
					),

							'title'          =>array(
						'type'    => 'string',
						'default' => 'Tab Name',
					),
							'BackgroundColor'          => array(
						'type'    => 'string',
						'default' => '',
					),
								'titleTextColor'          => array(
						'type'    => 'string',
						'default' => '',
					),
								'borderColor'          => array(
						'type'    => 'string',
						'default' => '',
					),
								'icon'          => array(
						'type'    => 'string',
						'default' => '',
					),
							'contentVisibility' => self::get_schema_content_visibility(),


							),
					),


									),
			)
		);
		
	}

	/**
	 * Output useful globals before printing any script tags.
	 *
	 * These are used by @woocommerce/components & the block library to set up defaults
	 * based on user-controlled settings from WordPress.
	 *
	 * @since 2.0.0
	 */
	public static function print_script_settings() {
		global $wp_locale;
		$code           = get_woocommerce_currency();
		$product_counts = wp_count_posts( 'product' );

		// NOTE: wcSettings is not used directly, it's only for @woocommerce/components
		//
		// Settings and variables can be passed here for access in the app.
		// Will need `wcAdminAssetUrl` if the ImageAsset component is used.
		// Will need `dataEndpoints.countries` if Search component is used with 'country' type.
		// Will need `orderStatuses` if the OrderStatus component is used.
		// Deliberately excluding: `embedBreadcrumbs`, `trackingEnabled`.
		$settings = array(
			'adminUrl'      => admin_url(),
			'wcAssetUrl'    => plugins_url( 'assets/', WC_PLUGIN_FILE ),
			'siteLocale'    => esc_attr( get_bloginfo( 'language' ) ),
			'currency'      => array(
				'code'      => $code,
				'precision' => wc_get_price_decimals(),
				'symbol'    => get_woocommerce_currency_symbol( $code ),
				'position'  => get_option( 'woocommerce_currency_pos' ),
			),
			'stockStatuses' => wc_get_product_stock_status_options(),
			'siteTitle'     => get_bloginfo( 'name' ),
			'dataEndpoints' => array(),
			'l10n'          => array(
				'userLocale'    => get_user_locale(),
				'weekdaysShort' => array_values( $wp_locale->weekday_abbrev ),
			),
		);
		// NOTE: wcSettings is not used directly, it's only for @woocommerce/components.
		$settings = apply_filters( 'woocommerce_components_settings', $settings );

		// Global settings used in each block.
		$block_settings = array(
			'min_columns'       => wc_get_theme_support( 'product_blocks::min_columns', 1 ),
			'max_columns'       => wc_get_theme_support( 'product_blocks::max_columns', 6 ),
			'default_columns'   => wc_get_theme_support( 'product_blocks::default_columns', 2 ),
			'min_rows'          => wc_get_theme_support( 'product_blocks::min_rows', 1 ),
			'max_rows'          => wc_get_theme_support( 'product_blocks::max_rows', 6 ),
			'default_rows'      => wc_get_theme_support( 'product_blocks::default_rows', 1 ),
			'thumbnail_size'    => wc_get_theme_support( 'thumbnail_image_width', 300 ),
			'placeholderImgSrc' => wc_placeholder_img_src(),
			'min_height'        => wc_get_theme_support( 'featured_block::min_height', 500 ),
			'default_height'    => wc_get_theme_support( 'featured_block::default_height', 500 ),
			'isLargeCatalog'    => $product_counts->publish > 200,
		);
		$woofood_tabs_js_url =  WOOFOOD_PLUGIN_URL."js/tabs-menu.js";
		?>
		<script type="text/javascript">
			var wcSettings = wcSettings || JSON.parse( decodeURIComponent( '<?php echo rawurlencode( wp_json_encode( $settings ) ); ?>' ) );
			var wc_product_block_data = JSON.parse( decodeURIComponent( '<?php echo rawurlencode( wp_json_encode( $block_settings ) ); ?>' ) );
			var woofood_plugin_tabs_js_url  = '<?php echo $woofood_tabs_js_url;  ?>';
		</script>
		<?php
	}

	/**
	 * Get the schema for the contentVisibility attribute
	 *
	 * @return array List of block attributes with type and defaults.
	 */
	public static function get_schema_content_visibility() {
		return array(
			'type'       => 'object',
			'properties' => array(
				'title'  => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'price'  => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'rating' => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'button' => array(
					'type'    => 'boolean',
					'default' => true,
				),
			),
		);
	}

	/**
	 * Get a set of attributes shared across most of the grid blocks.
	 *
	 * @return array List of block attributes with type and defaults.
	 */
	public static function get_shared_attributes() {
		return array(
			'columns'           => array(
				'type'    => 'number',
				'default' => wc_get_theme_support( 'product_blocks::default_columns', 3 ),
			),
			'rows'              => array(
				'type'    => 'number',
				'default' => wc_get_theme_support( 'product_blocks::default_rows', 1 ),
			),
			'categories'        => array(
				'type'    => 'array',
				'items'   => array(
					'type' => 'number',
				),
				'default' => array(),
			),
			'catOperator'       => array(
				'type'    => 'string',
				'default' => 'any',
			),
			'contentVisibility' => self::get_schema_content_visibility(),
		);
	}

	/**
	 * New products: Include and render the dynamic block.
	 *
	 * @param array  $attributes Block attributes. Default empty array.
	 * @param string $content    Block content. Default empty string.
	 * @return string Rendered block type output.
	 */
	public static function render_product_new( $attributes, $content ) {
		require_once dirname( __FILE__ ) . '/class-WFB-block-product-new.php';

		$block = new WFB_Block_Product_New( $attributes, $content );
		return $block->render();
	}

	/**
	 * Sale products: Include and render the dynamic block.
	 *
	 * @param array  $attributes Block attributes. Default empty array.
	 * @param string $content    Block content. Default empty string.
	 * @return string Rendered block type output.
	 */
	public static function render_product_on_sale( $attributes, $content ) {
		require_once dirname( __FILE__ ) . '/class-WFB-block-product-on-sale.php';

		$block = new WFB_Block_Product_On_Sale( $attributes, $content );
		return $block->render();
	}

	/**
	 * Products by category: Include and render the dynamic block.
	 *
	 * @param array  $attributes Block attributes. Default empty array.
	 * @param string $content    Block content. Default empty string.
	 * @return string Rendered block type output.
	 */
	public static function render_product_category( $attributes, $content ) {
		require_once dirname( __FILE__ ) . '/class-WFB-block-product-category.php';

		$block = new WFB_Block_Product_Category( $attributes, $content );
		return $block->render();
	}

	/**
	 * Products by attribute: Include and render the dynamic block.
	 *
	 * @param array  $attributes Block attributes. Default empty array.
	 * @param string $content    Block content. Default empty string.
	 * @return string Rendered block type output.
	 */
	public static function render_products_by_attribute( $attributes, $content ) {
		require_once dirname( __FILE__ ) . '/class-WFB-block-products-by-attribute.php';

		$block = new WFB_Block_Products_By_Attribute( $attributes, $content );
		return $block->render();
	}

	/**
	 * Top rated products: Include and render the dynamic block.
	 *
	 * @param array  $attributes Block attributes. Default empty array.
	 * @param string $content    Block content. Default empty string.
	 * @return string Rendered block type output.
	 */
	public static function render_product_top_rated( $attributes, $content ) {
		require_once dirname( __FILE__ ) . '/class-WFB-block-product-top-rated.php';

		$block = new WFB_Block_Product_Top_Rated( $attributes, $content );
		return $block->render();
	}

	/**
	 * Best Selling Products: Include and render the dynamic block.
	 *
	 * @param array  $attributes Block attributes. Default empty array.
	 * @param string $content    Block content. Default empty string.
	 * @return string Rendered block type output.
	 */
	public static function render_product_best_sellers( $attributes, $content ) {
		require_once dirname( __FILE__ ) . '/class-WFB-block-product-best-sellers.php';

		$block = new WFB_Block_Product_Best_Sellers( $attributes, $content );
		return $block->render();
	}

	/**
	 * Hand-picked Products: Include and render the dynamic block.
	 *
	 * @param array  $attributes Block attributes. Default empty array.
	 * @param string $content    Block content. Default empty string.
	 * @return string Rendered block type output.
	 */
	public static function render_handpicked_products( $attributes, $content ) {
		$is_gb_editor = \defined( 'REST_REQUEST' ) && REST_REQUEST && ! empty( $_REQUEST['context'] ) && 'edit' === $_REQUEST['context'];
?>
<?php if ( !$is_gb_editor) : ?>
<?php ob_start(); ?>	
		<div class="woofood-accordion"> 
          <a class="collapsed" data-toggle="collapse" data-target="#wf-accordion-<?php echo str_replace('%20', '-', rawurlencode($attributes["title"])); ?>" href="#wf-accordion-<?php echo str_replace('%20', '-', rawurlencode($attributes["title"])); ?>"  aria-expanded="false" aria-controls="collapseThree"> 
            <div class="panel-heading panel-heading-title" style="
              background:<?php echo $attributes["BackgroundColor"]; ?>;
              border-color:<?php echo $attributes["borderColor"]; ?>;"
            >
            <?php if ($attributes["icon"]): ?>
            <img src="<?php echo $attributes["icon"];?>"/>
        <?php endif;?>
              <h4 class="panel-title" style="color: <?php echo $attributes["titleTextColor"]; ?>;">
                <?php echo $attributes["title"]; ?>                  
                </h4>
                  <div class="accordion-plus-icon">
                <i class="woofood-icon-plus-circled rotate-icon" style="color: <?php echo $attributes["titleTextColor"]; ?>;"></i>
              </div>  
            </div>
          
          </a>  
             <div id="wf-accordion-<?php echo str_replace('%20', '-', rawurlencode($attributes["title"])) ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree" aria-expanded="false">
          <div class="panel-body">
          <?php

          		//echo do_shortcode("[products columns='".$attributes["columns"]."' ids='".implode($attributes["products"], ",")."']");
          		$attributes = array('columns'=> $attributes["columns"], 'ids'=>implode(",", $attributes["products"]), "orderby"=>$attributes["orderby"],  "order"=>$attributes["order"]);	
          		woofood_products($attributes);
         ?>

          </div>
        </div> 
        </div>
        <?php

        return ob_get_clean();

        ?>
<?php else: ?>
<?php
				ob_start(); 

          			//echo do_shortcode("[products columns='".$attributes["columns"]."' ids='".implode($attributes["products"], ",")."']");
          			$attributes = array('columns'=> $attributes["columns"], 'ids'=>implode(",", $attributes["products"]), "orderby"=>$attributes["orderby"],  "order"=>$attributes["order"]);	
          			woofood_products($attributes);
          		return ob_get_clean();


         ?>
<?php endif;?>
<?php
        return null;
	}


	public static function render_handpicked_products_tabs( $attributes, $content ) {

		$is_gb_editor = defined( 'REST_REQUEST' ) && REST_REQUEST && ! empty( $_REQUEST['context'] ) && 'edit' === $_REQUEST['context'];
?>
<?php if ( !$is_gb_editor) : ?>

<?php ob_start(); ?>	
		<style>
.woofood-tabs-menu .nav-item a {
 	
 	color: <?php echo $attributes["titleTextColor"]; ?>;
  text-decoration: none;
  background: <?php echo $attributes["BackgroundColor"];?>;
  display: inline-block;
  padding: 15px 20px;
  position: relative;
}
.woofood-tabs-menu .nav-item a:after {    
  background: none repeat scroll 0 0 transparent;
  bottom: 0;
  content: "";
  display: block;
  height: 3px;
  left: 50%;
  position: absolute;
  background: <?php echo $attributes["borderColor"];?>;
  transition: width 0.5s ease 0s, left 0.5s ease 0s;
  width: 0;
}
.woofood-tabs-menu .nav-item a:hover:after, .woofood-tabs-menu .nav-item .active:after  { 
  width: 100%; 
  left: 0; 
}
 .woofood-tabs-menu .nav-item a:before {
  content: "";
  position: absolute;
  width: 100%;
  height: 3px;
  bottom: 0;
  left: 0;
  background: #9CF5A6;
  visibility: hidden;
  border-radius: 5px;
  transform: scaleX(0);
  transition: .25s linear;
}
		

		</style>
		<div class="woofood-tabs-wrapper">
			
					<ul class="nav justify-content-center woofood-tabs-menu">
<?php 


?>
<?php foreach($attributes["tabs"] as $index => $current_attribute) : ?>

	<?php
	/*echo "<pre>";
	print_r($current_attribute);
	echo "</pre>";
	exit;*/

	?>
  <li class="nav-item">
    <a class="nav-link <?php if($index ==0){echo "active show";} ?>" data-toggle="tab" role="tab" aria-controls="wf-tab-<?php echo $index; ?>" id="nav-wf-tab-<?php echo $index; ?>" href="#wf-tab-<?php echo $index; ?>" style="
              background:<?php echo isset($current_attribute["BackgroundColor"]) ? $current_attribute["BackgroundColor"] : '';  ?>;
              border-color:<?php echo isset($current_attribute["borderColor"]) ? $current_attribute["borderColor"] : ''; ?>;
              color: <?php echo isset($current_attribute["titleTextColor"]) ? $current_attribute["titleTextColor"] : ''; ; ?>;"
            > <?php if (isset($current_attribute["icon"])): ?>
            <img src="<?php echo $current_attribute["icon"];?>"/>
<?php endif;?><?php echo $current_attribute["title"]; ?></a>
  </li>
  

		
<?php endforeach; ?>
        		</ul>


                  <div class="tab-content" id="nav-tabContent">


		<?php foreach($attributes["tabs"] as $index => $current_attribute) : ?>
		<div class="tab-pane fade  <?php if($index ==0){echo "show active";} ?>" id="wf-tab-<?php echo $index; ?>" role="tabpanel" aria-labelledby="nav-wf-tab-<?php echo $index; ?>">

        
          <?php

          		//echo do_shortcode("[products columns='".$current_attribute["columns"]."' ids='".implode($current_attribute["products"], ",")."']");
          		$attributes = array('columns'=> isset($current_attribute["columns"]) ?$current_attribute["columns"] : 2 , "orderby"=>isset($current_attribute["order_by"]) ? $current_attribute["order_by"] : "menu_order" ,  "order"=>$current_attribute["order"], 'ids'=>isset($current_attribute["products"])? implode(",", $current_attribute["products"]) : "");	

          		woofood_products($attributes);

         ?>
         


         
        </div>
<?php endforeach; ?>

</div>
</div>

<?php
$content = ob_get_clean();

        return $content;

?>
<?php else: ?>
<?php ob_start(); ?>


				                  <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">

					<?php foreach($attributes["tabs"] as $index => $current_attribute) : ?>
		
		<div class="tab-pane fade  <?php if($index ==0){echo "show active";} ?>" id="wf-tab-<?php echo $index; ?>" role="tabpanel" aria-labelledby="nav-wf-tab-<?php echo $index; ?>">

        
          <?php

          		//echo do_shortcode("[products columns='".$current_attribute["columns"]."' ids='".implode($current_attribute["products"], ",")."']");
          $attributes = array('columns'=> $current_attribute["columns"], "orderby"=>$current_attribute["order_by"],  "order"=>$current_attribute["order"], 'ids'=>implode($current_attribute["products"], ","));	
          		woofood_products($attributes);

         ?>
         


         
        </div>
<?php endforeach; ?>
        		</div>

<?php return ob_get_clean();


         ?>
<?php endif;?>
<?php
        
	}

	/**
	 * Add body classes.
	 *
	 * @param array $classes Array of CSS classnames.
	 * @return array Modified array of CSS classnames.
	 */
	public static function add_theme_body_class( $classes = array() ) {
		$classes[] = 'theme-' . get_template();
		return $classes;
	}
}


 function render_handpicked_products_test( $attributes, $content ) {
		$is_gb_editor = \defined( 'REST_REQUEST' ) && REST_REQUEST && ! empty( $_REQUEST['context'] ) && 'edit' === $_REQUEST['context'];


		?>
<?php if ( !$is_gb_editor) : ?>
		<?php ob_start(); ?>	
		<div class="woofood-accordion"> 
          <a class data-toggle="collapse" data-parent="#accordion" href="#wf-accordion-<?php echo str_replace('%20', '-', rawurlencode($attributes["title"])); ?>"  aria-expanded="false" aria-controls="collapseThree"> 
            <div class="panel-heading panel-heading-title collapsed " role="tab" id="headingThree" data-toggle="collapse" data-parent="#accordion" href="#wf-accordion-<?php echo str_replace('%20', '-', rawurlencode($attributes["title"])); ?>" aria-expanded="false" style="
              background:<?php echo $attributes["BackgroundColor"]; ?>;
              border-color:<?php echo $attributes["borderColor"]; ?>;"
            >
              <h4 class="panel-title" style="color: <?php echo $attributes["titleTextColor"]; ?>;">
                <?php echo $attributes["title"]; ?>                  
                </h4>
                  <div class="accordion-plus-icon">
                <i class="woofood-icon-plus-circled rotate-icon" style="color: <?php echo $attributes["titleTextColor"]; ?>;"></i>
              </div>  
            </div>
          
          </a>  
             <div id="wf-accordion-<?php echo str_replace('%20', '-', rawurlencode($attributes["title"])) ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree" aria-expanded="false">
          <div class="panel-body">
<?php
          		echo do_shortcode("[products columns='".$attributes["columns"]."' ids='".implode($attributes["products"], ",")."']");
          		
?>

          </div>
        </div> 
        </div>
<?php

        return ob_get_clean();

?>

<?php else: ?>
<?php
				ob_start(); 
          		echo do_shortcode("[products columns='".$attributes["columns"]."' ids='".implode($attributes["products"], ",")."']");
          		return ob_get_clean();


?>
<?php endif;?>
<?php
        return null;
	}

WooFood_Block_Library::get_instance();
