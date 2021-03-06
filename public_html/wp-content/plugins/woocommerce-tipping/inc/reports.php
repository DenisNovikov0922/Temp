<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! defined( 'WC_ABSPATH' ) ) {
	exit;
}
require_once WC_ABSPATH . 'includes/admin/reports/class-wc-admin-report.php';


class WPSlash_Tipping_Reports extends \WC_Admin_Report {

	/**
	 * Color for the graph.
	 */
	const COLOR = '#572ff8';
	/**
	 * Name of the meta key for Stripe fees (old and new).
	 */
	const LEGACY_META_NAME_FEE = 'Mancia';
	const META_NAME_FEE = '_wpslash_tip';
	/**
	 * Name of tweak for customize query
	 */
	const TWEAK_REQ = '___wpslash_tip___';

	/**
	 * Chart data table.
	 *
	 * @var $chart_data
	 */
	private $chart_data;
	/**
	 * Data table to build the graph.
	 *
	 * @var $report_data
	 */
	private $report_data;

	/**
	 * WooSFR constructor.
	 */
	public function __construct() {
		$this->init_variable();
		$this->init_widget_dashboard();

		add_filter( 'woocommerce_admin_report_data', array( $this, 'add_report_data' ), 10, 1 );
		add_filter( 'woocommerce_reports_get_order_report_query', array( $this, 'clean_query_get_order' ), 10, 1 );

		add_filter( 'woocommerce_admin_report_chart_data', array( $this, 'chart_add_report_data' ), 10, 1 );
		add_action( 'admin_print_footer_scripts', array( $this, 'chart_add_legend' ), 10 );
		add_action( 'admin_print_footer_scripts', array( $this, 'chart_update_legend_placeholder' ), 10 );
		add_action( 'admin_print_footer_scripts', array( $this, 'chart_draw' ), 10 );
		add_action( 'admin_print_footer_scripts', array( $this, 'chart_style' ), 10 );

	}

	/**
	 * Set the default values to start the class.
	 */
	private function init_variable() {

		$current_range = ! empty( $_GET['range'] ) ? sanitize_text_field( wp_unslash( $_GET['range'] ) ) : '7day';

		if ( ! in_array( $current_range, array( 'custom', 'year', 'last_month', 'month', '7day' ), true ) ) {
			$current_range = '7day';
		}

		$this->check_current_range_nonce( $current_range );
		$this->calculate_current_range( $current_range );
	}

	/**
	 * Adjust the "total net" value in the widget on the dashboard.
	 */
	private function init_widget_dashboard() {

		global $pagenow;

		if ( 'index.php' === $pagenow ) {
			$this->start_date = strtotime( gmdate( 'Y-m-01', current_time( 'timestamp' ) ) );
			$this->end_date   = current_time( 'timestamp' );
		}

	}

	/**
	 * Add Stripes fees to the chart data table.
	 *
	 * @param array $report_data Data table.
	 *
	 * @return array $this->_report_data
	 */
	public function add_report_data( $report_data ) {


		$migrate_old_fee = get_transient( 'wpslash_tipping_migrate_old_data' );	

		if (!$migrate_old_fee) {
			$args = array(

				'status' => array( 'completed', 'processing', 'on-hold'),
				'extra_store_name' => $store_name,
				'return' => 'ids',
				'limit' => -1


			);


			$orders = wc_get_orders( $args );

			foreach ($orders as $order_id) {
				$order = wc_get_order($order_id);
				foreach ( $order->get_items(array('fee')) as $item_id => $item_fee ) {



					$fee_name = $item_fee->get_name();

					if ('Tip' == $fee_name) {
						update_post_meta($order_id, '_wpslash_tip', $item_fee->get_total() );
					}





				}
			}
			set_transient('wpslash_tipping_migrate_old_data', true);
		}


		// Set report_data in class.
		$this->report_data = $report_data;

		// Search Stripe fees values.
		$wpslash_tip = $this->get_order_report_data( array(
			'data'         => array(
				self::TWEAK_REQ => array(
					'type'     => 'meta',
					'function' => 'SUM',
					'name'     => 'total',
				),
				'post_date'     => array(
					'type'     => 'post_data',
					'function' => '',
					'name'     => 'post_date',
				),
			),
			'group_by'     => $this->group_by_query,
			'order_by'     => 'post_date ASC',
			'query_type'   => 'get_results',
			'filter_range' => true,
			'order_types'  => wc_get_order_types( 'sales-reports' ),
			'order_status' => array( 'completed', 'processing', 'on-hold'),
		) );


		$wpslash_tip_count = $this->get_order_report_data( array(
			'data'         => array(
				self::TWEAK_REQ => array(
					'type'     => 'meta',
					'function' => 'COUNT',
					'name'     => 'total',
				),
				'post_date'     => array(
					'type'     => 'post_data',
					'function' => '',
					'name'     => 'post_date',
				),
			),
			'group_by'     => $this->group_by_query,
			'order_by'     => 'post_date ASC',
			'query_type'   => 'get_results',
			'filter_range' => true,
			'order_types'  => wc_get_order_types( 'sales-reports' ),
			'order_status' => array( 'completed', 'processing', 'on-hold'),
		) );

		

		// Add fees in Report Data.
		$this->report_data->wpslash_tip = $wpslash_tip;
		$this->report_data->wpslash_tip_count = $wpslash_tip_count;

		$this->report_data->total_wpslash_tip = wc_format_decimal( array_sum( wp_list_pluck( $this->report_data->wpslash_tip, 'total' ) ), 2 );
		$this->report_data->wpslash_tips_count = array_sum( wp_list_pluck( $this->report_data->wpslash_tip_count, 'total' ) );


		return $this->report_data;
	}

	/**
	 * Unfortunately, the Stripe Gateway extension used meta_key in the past whose name contained a space,
	 * now it uses underscores, but did not put any updater to update the old data.
	 * With this method, the query is modified to be operational.
	 *
	 * @param array $query Request.
	 *
	 * @return array $query
	 */
	public function clean_query_get_order( $query ) {

		preg_match( '/' . self::TWEAK_REQ . '/', $query['select'], $match );
		if ( $match && is_array( $query ) ) {
			$query['select'] = str_replace( self::TWEAK_REQ, 'key', $query['select'] );
			$query['join']   = str_replace( 'meta_' . self::TWEAK_REQ, 'meta_key', $query['join'] );
			$query['join']   = str_replace( "= '" . self::TWEAK_REQ . "'", "IN ('" . self::META_NAME_FEE . "','" . self::LEGACY_META_NAME_FEE . "')", $query['join'] );
		}

		return $query;
	}

	/**
	 * Added query results in the chart.
	 *
	 * @param array $data Data table.
	 *
	 * @return array $this->chart_data
	 */
	public function chart_add_report_data( $data ) {

		// Set $data in class.
		$this->chart_data = $data;

		// Add Stripe fees in data graph array.
		$this->chart_data['wpslash_tip'] = $this->prepare_chart_data( $this->report_data->wpslash_tip, 'post_date', 'total', $this->chart_interval, $this->start_date, $this->chart_groupby );

		// Update Net Sale - Stripe fees.
		foreach ( $this->chart_data['net_order_amounts'] as $order_amount_key => $order_amount_value ) {
			$this->chart_data['net_order_amounts'][ $order_amount_key ][1] -= $this->chart_data['wpslash_tip'][ $order_amount_key ][1];
		}

		return $this->chart_data;
	}

	/**
	 * Add the new legend in chart.
	 */
	public function chart_add_legend() {

		if ( ! $this->chart_data ) {
			return;
		}
		$link = sprintf(
			'<li style="border-color: %s" class="highlight_series" data-series="9">%s</li>',
			self::COLOR,
			sprintf(
				// translators: %s is WPSlash Tipping  fees.
				esc_html_x( '%s importo totale mance', 'number', 'wpslash-tipping' ),
				'<strong>' . wc_price( $this->report_data->total_wpslash_tip ) . '</strong>'
			)
		);

		$link_count = sprintf(
			'<li style="border-color: %s" class="highlight_series" data-series="10">%s</li>',
			self::COLOR,
			sprintf(
				// translators: %s is WPSlash Tipping fees.
				esc_html_x( '%s ordini con mancia', 'number', 'wpslash-tipping' ),
				'<strong>' . $this->report_data->wpslash_tips_count . '</strong>'
			)
		);
		$allowed_html = array(
		'li' => array(
		'style' => array(),
		'class' => array(),
		'data-series' => array()
		),
		'strong' => array(),
		'br' => array(),
		'em' => array(),
		'strong' => array(),
		);
		?>

		<script type="text/javascript">
			jQuery('ul.chart-legend').append('<?php echo wp_kses($link, $allowed_html); ?>');
			jQuery('ul.chart-legend').append('<?php echo wp_kses($link_count, $allowed_html); ?>');

		</script>

		<?php

	}

	/**
	 * Replaces hover text for legend.
	 * In our case, I use it to change the text concerning the net commands taking into account the Stripes fees.
	 */
	public function chart_update_legend_placeholder() {
		$update_legend = array(
			7 => esc_html__( 'This is the sum of the order totals after any refunds and excluding shipping, taxes and Stripe fees.', 'wpslash-tipping' ), // Net sales amount
		);
		echo '<script type="text/javascript">';
		foreach ( $update_legend as $serie => $text ) {
			printf(
				"jQuery('ul.chart-legend').find('li[data-series=%d]').attr('data-tip', '%s');",
				intval( $serie ),
				esc_js( $text )
			);
		}
		echo '</script>';
	}

	/**
	 * Change the size of the graph to be in harmony with the legend.
	 */
	public function chart_style() {

		echo '<script type="text/javascript">';
		echo 'jQuery(".woocommerce-reports-wide .postbox .chart-placeholder").height(jQuery("ul.chart-legend").height() - 25);';
		echo '</script>';
	}


	public function chart_draw() {

		global $wp_locale;

		if ( ! $this->chart_data ) {
			return;
		}

		$wpslash_tip = wp_json_encode(
			array_map( array( $this, 'round_chart_totals' ), array_values( $this->chart_data['wpslash_tip'] ) )
		);

		?>

		<script type="text/javascript">

			jQuery(function () {

				var wpslash_tip_main_char;

				var drawGraph = function (highlight) {

					// Get Data.
					var series = [];
					var current_series = main_chart.getData();
					jQuery(current_series).each(function (i, value) {

						// Change Yaxis shipping.
						if (value.label === '<?php echo esc_js( __( 'Shipping amount', 'woocommerce' ) ); ?>') {
							value.yaxis = 3;
						}

						series.push(value);
					});

					var wpslash_tip_series = {
						label: "<?php echo esc_js( esc_html__( 'Tips', 'wpslash-tipping' ) ); ?>",
						data: <?php echo esc_js($wpslash_tip); ?>,
						yaxis: 3,
						color: '<?php echo esc_js(self::COLOR); ?>',
						points: {show: true, radius: 5, lineWidth: 2, fillColor: '#fff', fill: true},
						lines: {show: true, lineWidth: 2, fill: false},
						shadowSize: 0,
						<?php echo esc_js($this->get_currency_tooltip());  // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped ?>
					};
					series.push(wpslash_tip_series);

					if (highlight !== 'undefined' && series[highlight]) {
						highlight_series = series[highlight];

						highlight_series.color = '#9c5d90';

						if (highlight_series.bars) {
							highlight_series.bars.fillColor = '#9c5d90';
						}

						if (highlight_series.lines) {
							highlight_series.lines.lineWidth = 5;
						}
					}

					wpslash_tip_main_char = jQuery.plot(
						jQuery('.chart-placeholder.main'),
						series,
						{
							legend: {
								show: false
							},
							grid: {
								color: '#aaa',
								borderColor: 'transparent',
								borderWidth: 0,
								hoverable: true
							},
							xaxes: [{
								color: '#aaa',
								position: "bottom",
								tickColor: 'transparent',
								mode: "time",
								timeformat: "<?php echo ( 'day' === $this->chart_groupby ) ? '%d %b' : '%b'; ?>",
								monthNames: <?php echo json_encode( array_values( $wp_locale->month_abbrev ) ); ?>,
								tickLength: 1,
								minTickSize: [1, "<?php echo esc_js($this->chart_groupby); ?>"],
								font: {
									color: "#aaa"
								}
							}],
							yaxes: [
								{
									min: 0,
									minTickSize: 1,
									tickDecimals: 0,
									color: '#d4d9dc',
									font: {color: "#aaa"}
								},
								{
									position: "right",
									min: 0,
									tickDecimals: 2,
									alignTicksWithAxis: 1,
									color: 'transparent',
									font: {color: "#aaa"}
								},
								{
									position: "right",
									min: 0,
									tickDecimals: 2,
									alignTicksWithAxis: 1,
									color: 'transparent',
									font: {color: "#aaa"},
									autoscaleMargin: 5
								}
							],
						}
					);
					jQuery('.chart-placeholder').resize();
				}

				drawGraph();

				jQuery('.highlight_series').hover(
					function () {
						drawGraph(jQuery(this).data('series'));
					},
					function () {
						drawGraph();
					}
				);

				jQuery( '.export_csv' ).click( function() {
					var exclude_series = jQuery( this ).data( 'exclude_series' ) || '';
					exclude_series    = exclude_series.toString();
					exclude_series    = exclude_series.split( ',' );
					var xaxes_label   = jQuery( this ).data( 'xaxes' );
					var groupby       = jQuery( this ) .data( 'groupby' );
					var index_type    = jQuery( this ).data( 'index_type' );
					var export_format = jQuery( this ).data( 'export' );
					var csv_data      = 'data:text/csv;charset=utf-8,\uFEFF';
					var s, series_data, d;

					if ( 'table' === export_format ) {

						jQuery( this ).offsetParent().find( 'thead tr,tbody tr' ).each( function() {
							jQuery( this ).find( 'th, td' ).each( function() {
								var value = jQuery( this ).text();
								value = value.replace( '[?]', '' ).replace( '#', '' );
								csv_data += '"' + value + '"' + ',';
							});
							csv_data = csv_data.substring( 0, csv_data.length - 1 );
							csv_data += '\n';
						});

						jQuery( this ).offsetParent().find( 'tfoot tr' ).each( function() {
							jQuery( this ).find( 'th, td' ).each( function() {
								var value = jQuery( this ).text();
								value = value.replace( '[?]', '' ).replace( '#', '' );
								csv_data += '"' + value + '"' + ',';
								if ( jQuery( this ).attr( 'colspan' ) > 0 ) {
									for ( i = 1; i < jQuery(this).attr('colspan'); i++ ) {
										csv_data += '"",';
									}
								}
							});
							csv_data = csv_data.substring( 0, csv_data.length - 1 );
							csv_data += '\n';
						});

					} else {

						if ( ! window.main_chart ) {
							return false;
						}

						var the_series = wpslash_tip_main_char.getData();
						var series     = [];
						csv_data      += '"' + xaxes_label + '",';

						jQuery.each( the_series, function( index, value ) {
							if ( ! exclude_series || jQuery.inArray( index.toString(), exclude_series ) === -1 ) {
								series.push( value );
							}
						});

						// CSV Headers
						for ( s = 0; s < series.length; ++s ) {
							csv_data += '"' + series[s].label + '",';
						}

						csv_data = csv_data.substring( 0, csv_data.length - 1 );
						csv_data += '\n';

						// Get x axis values
						var xaxis = {};

						for ( s = 0; s < series.length; ++s ) {
							series_data = series[s].data;
							for ( d = 0; d < series_data.length; ++d ) {
								xaxis[series_data[d][0]] = [];
								// Zero values to start
								for ( var i = 0; i < series.length; ++i ) {
									xaxis[series_data[d][0]].push(0);
								}
							}
						}

						// Add chart data
						for ( s = 0; s < series.length; ++s ) {
							series_data = series[s].data;
							for ( d = 0; d < series_data.length; ++d ) {
								xaxis[series_data[d][0]][s] = series_data[d][1];
							}
						}

						// Loop data and output to csv string
						jQuery.each( xaxis, function( index, value ) {
							var date = new Date( parseInt( index, 10 ) );

							if ( 'none' === index_type ) {
								csv_data += '"' + index + '",';
							} else {
								if ( groupby === 'day' ) {
									csv_data += '"' + date.getUTCFullYear() + '-' + parseInt( date.getUTCMonth() + 1, 10 ) + '-' + date.getUTCDate() + '",';
								} else {
									csv_data += '"' + date.getUTCFullYear() + '-' + parseInt( date.getUTCMonth() + 1, 10 ) + '",';
								}
							}

							for ( var d = 0; d < value.length; ++d ) {
								var val = value[d];

								if ( Math.round( val ) !== val ) {
									val = parseFloat( val );
									val = val.toFixed( 2 );
								}

								csv_data += '"' + val + '",';
							}
							csv_data = csv_data.substring( 0, csv_data.length - 1 );
							csv_data += '\n';
						} );
					}

					// Set data as href and return
					jQuery( this ).attr( 'href', encodeURI( csv_data ) );
					return true;
				});

			});
		</script>
		<?php
	}

	/**
	 * Method from the 'WC_Report_Taxes_By_Date' file required for the construction of the graph
	 *
	 * @param array|string $amount value to transform.
	 *
	 * @return array|string
	 */
	private function round_chart_totals( $amount ) {

		if ( is_array( $amount ) ) {
			return array( $amount[0], wc_format_decimal( $amount[1], wc_get_price_decimals() ) );
		} else {
			return wc_format_decimal( $amount, wc_get_price_decimals() );
		}
	}

}
