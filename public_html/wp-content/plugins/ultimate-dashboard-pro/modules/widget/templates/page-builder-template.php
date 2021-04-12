<?php
/**
 * Page builder template.
 *
 * @package Ultimate Dashboard PRO
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

use UdbPro\Helpers\Content_Helper;

/**
 * Inherited variables from `public function render_dashboard_page()`:
 *
 * @var string $builder The page builder name.
 * @var int $template_id The template's post ID.
 */

$content_helper = new Content_Helper();
?>

<div class="notice udb-page-builder-template">

	<?php $content_helper->output_content_using_builder( $template_id, $builder ); ?>

</div>
