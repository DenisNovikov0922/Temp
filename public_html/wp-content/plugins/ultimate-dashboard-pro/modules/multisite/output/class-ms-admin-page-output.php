<?php
/**
 * Multisite's admin page output.
 *
 * Admin page output for multisite.
 *
 * Well, nothing here. The multisite support for admin pages feature is unique.
 * It's inlined in modules/admin-page/inc/output.php.
 *
 * @package Ultimate_Dashboard_Pro
 */

namespace UdbPro\Multisite\Output;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Base\Base_Output;

/**
 * Class to setup the module output.
 */
class Ms_Admin_Page_Output extends Base_Output {

	/**
	 * The class instance.
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * The current module url.
	 *
	 * @var string
	 */
	public $url;

	/**
	 * Module constructor.
	 */
	public function __construct() {

		$this->url = ULTIMATE_DASHBOARD_PRO_PLUGIN_URL . '/modules/multisite';

	}

	/**
	 * Get instance of the class.
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Init the class setup.
	 */
	public static function init() {

		$class = new self();
		$class->setup();

	}

	/**
	 * Setup the module output.
	 */
	public function setup() {}

}
