<?php
		$tipping_enabled =  get_option( 'wc_settings_tab_wpslash_tipping_enabled', true );
if ('yes' == $tipping_enabled) {
	add_action( 'woocommerce_review_order_after_cart_contents', 'wpslash_tipping_woocommerce_checkout_order_review_form', 10, 0 );
}

function wpslash_tipping_woocommerce_checkout_order_review_form() {
		$tipping_title =  !empty(get_option( 'wc_settings_tab_wpslash_tipping_title', true )) ? get_option( 'wc_settings_tab_wpslash_tipping_title', true ) : '' ;
		$tipping_title_enabled =  !empty(get_option( 'wc_settings_tab_wpslash_tipping_title_enabled', true )) ? get_option( 'wc_settings_tab_wpslash_tipping_title_enabled', true ) : 'no' ;
		$tipping_percentage=  !empty(get_option( 'wc_settings_tab_wpslash_tipping_percentage', true )) ? get_option( 'wc_settings_tab_wpslash_tipping_percentage', true )  : '';
		$tipping_percentage_enabled=  !empty(get_option( 'wc_settings_tab_wpslash_tipping_percentage_enabled', true )) ? get_option( 'wc_settings_tab_wpslash_tipping_percentage_enabled', true ) :'no'  ;
		$tipping_percentage_display=  !empty(get_option( 'wc_settings_tab_wpslash_tipping_percentage_display', true )) ?  get_option( 'wc_settings_tab_wpslash_tipping_percentage_display', true ) : 'percentage';
		$tipping_percentage_display=  !empty(get_option( 'wc_settings_tab_wpslash_tipping_percentage_display', true )) ?  get_option( 'wc_settings_tab_wpslash_tipping_percentage_display', true ) : 'percentage';
		$tipping_default_amount=  !empty(get_option( 'wc_settings_tab_wpslash_tipping_default_amount', true )) ?  get_option( 'wc_settings_tab_wpslash_tipping_default_amount', true ) : 0;
		$tipping_taxable=  ( !empty(get_option( 'wc_settings_tab_wpslash_tipping_taxable', true )) ) && ( get_option( 'wc_settings_tab_wpslash_tipping_taxable', true ) =='yes' )  ?  true : false;
		$tax_class =  !empty(get_option( 'wc_settings_tab_wpslash_tipping_tax_class', true )) ?  get_option( 'wc_settings_tab_wpslash_tipping_tax_class', true ) : '';

	if (!is_ajax()) {
		?>
	<div class="wpslash-tip-wrapper">
		<?php if (!empty($tipping_title) && ( 'yes' == $tipping_title_enabled )) : ?>		
	<div class="wpslash-tip-title">
			<?php echo esc_html($tipping_title); ?>
	</div>	
<?php endif; ?>
	<div class="wpslash-tipping-form-wrapper">
	<input type="number" value="<?php echo esc_html($tipping_default_amount); ?>" class="wpslash-tip-input" />
	<a class="wpslash-tip-submit"><?php esc_html_e('Mancia', 'wpslash-tipping'); ?></a>
	</div>
		<?php 
		if ('yes' == $tipping_percentage_enabled) :
			$tipping_percentages = explode(',', str_replace(' ', '', $tipping_percentage));

			?>
	<div class="wpslash-percentage-tip-buttons">
			<?php foreach ($tipping_percentages as $percentage) : ?>
				<?php 
				$subtotal =WC()->cart->get_subtotal();
				$subtotal_tax =WC()->cart->get_subtotal_tax();

				$amount =  ( ( $subtotal+$subtotal_tax ) * ( $percentage/100 ) );
				?>
	<a class="wpslash-tip-percentage-btn" percentage="<?php echo floatval($percentage); ?>"><?php echo esc_html(wpslash_tipping_percentage_display_format($percentage, $amount, $tipping_percentage_display)); ?></a>
	<?php endforeach; ?>

	</div>
	<?php endif; ?>
	</div>
		<?php
	}

}

add_filter('woocommerce_cart_totals_fee_html', 'wpslash_tipping_add_remove_btn', 10, 2);
function wpslash_tipping_add_remove_btn( $cart_total_fees, $fee) {
	$tip_name = WC()->session->get( 'wpslash_tip_name', true );

	if ($fee->name == $tip_name ) {
		$cart_total_fees .='<a class="wpslash_tip_remove_btn">' . esc_html__('x', 'wpslash-tipping') . '</a>';

	}
	return $cart_total_fees;
}

function wpslash_tipping_percentage_display_format( $percentage, $amount, $format) {
	switch ($format) {
		case 'percentage':
			/* translators: %s: Percentage of Tip */
			return sprintf(esc_html__('%s%%', 'wpslash-tipping'), $percentage);
			break;

		case 'amount':
			/* translators: %s: Tip Amount */
			return sprintf(esc_html__('Mancia %s', 'wpslash-tipping'), strip_tags(wc_price($amount)));
			break;

		case 'percentage-amount':
			/* translators: 1:Tip Percentage 2:Tip amount */
			return sprintf( esc_html__('Mancia %1$s %% (%2$s)', 'wpslash-tipping'), $percentage, strip_tags(wc_price($amount)) );
			break;

	}

}
