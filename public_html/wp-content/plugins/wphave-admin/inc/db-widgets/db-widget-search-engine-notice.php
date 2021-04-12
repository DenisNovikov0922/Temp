<?php 

/*
 *******************
 * SEACH ENGINE NOTICE DASHBOARD WIDGET
 *******************
 *
 *	Show this db widget, if the search engine visibility is hidden.
 *
 *  @type	include
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( ! function_exists('wphave_admin_search_engine_notice_widget_content') ) :

    function wphave_admin_search_engine_notice_widget_content() { ?>

        <style>
            .wpat-seo-vis {background:#fdfaf1;margin:-11px -15px -15px -15px;text-align:center;}
            .wpat-seo-vis-focus {line-height:normal;color:#82878c;font-size:40px;border-bottom:1px solid #eee;padding:23px 20px 28px 20px}
            .wpat-seo-vis-focus:last-child {border-bottom:0px}
            .wpat-seo-vis-focus .wpat-seo-vis-num {display:inline-block;color:#ffb900}
            .wpat-seo-vis-focus .wpat-seo-vis-num span {width:auto;height:auto;font-size:40px}
            .wpat-seo-vis-focus .wpat-seo-vis-num ~ div {font-size:16px;font-weight:100;line-height:1.4em;width:100%}
        </style>

        <div class="wpat-seo-vis">
            <div class="wpat-seo-vis-focus">
                <div class="wpat-seo-vis-num">
                    <span class="dashicons dashicons-warning"></span>
                </div>
                <div><?php esc_html_e( 'Your website is currently not visible to search engines!', 'wphave-admin' ); ?></div>
            </div>
        </div>

    <?php }

endif;

/*
 *******************
 * INCLUDE DASHBOARD WIDGET
 *******************
 *
 *	Include this db widget to WordPress admin dashboard.
 *
 *  @type	include
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( ! function_exists('wphave_admin_search_engine_notice_widget') ) :

	function wphave_admin_search_engine_notice_widget() {

		wp_add_dashboard_widget(
			'wp_search_engine_notice_db_widget', esc_html__( 'Search Engine Visibility', 'wphave-admin' ), 'wphave_admin_search_engine_notice_widget_content'
		);

	}

endif;

add_action('wp_dashboard_setup', 'wphave_admin_search_engine_notice_widget');