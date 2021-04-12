<?php
/**
 * Copyright (c) 2019 Nexi Payments S.p.A.
 *
 * @author      iPlusService S.r.l.
 * @category    Payment Module
 * @package     Nexi XPay
 * @version     5.0.2
 * @copyright   Copyright (c) 2019 Nexi Payments S.p.A. (https://ecommerce.nexi.it)
 * @license     GNU General Public License v3.0
 */

class WC_Gateway_XPay_APM extends WC_Gateway_XPay_Pro
{
    public $selectedCard;
    public $recurring;
    public $img;
    public $oNexiRecurring;

    /**
     * Constructor
     */
    public function __construct($module_version, $code, $description, $selectedCard, $img, $recurring)
    {
        if ($module_version != null) {
            $this->module_version = $module_version;
        }

        parent::__construct();
        $this->method_title = $description;
        $this->method_description = $description . " via Nexi XPay";
        $this->id = 'xpay_' . $code;
        $this->title = $this->method_title;//__('Nexi XPay', 'woocommerce-gateway-nexi-xpay');
        //$this->description = $this->method_description;

        $this->selectedCard = $selectedCard;
        $this->recurring = $recurring;

        $this->icon = $img;

        if ($this->is_recurring()) {
            $this->oNexiRecurring = new WC_Gateway_XPay_Subscription('subscriptions', $this->module_version);
            $this->supports = $this->oNexiRecurring->supports;
            add_action('woocommerce_scheduled_subscription_payment_' . $this->id, array($this, 'scheduled_subscription_payment'), 10, 2);
        }

        // Actions
        add_action('woocommerce_api_wc_gateway_xpay', array($this, 'wc_xpay_page_ritorno'));
        add_action('woocommerce_receipt_' . $this->id, array($this, 'wc_xpay_page_invioform'));
    }

    /**
     *
     */
    protected function set_description()
    {
    }

    /**
     * Se non il cliente non è nella pagina di amministrazione account
     * Se la valuta è EUR il modulo è disponibile tra le opzioni
     */
    public function is_available()
    {
        return parent::is_available();
    }

    public function payment_fields()
    {
    }

    public function get_params_form($order_id)
    {
        if ($this->is_recurring()) {
            $params = $this->oNexiRecurring->get_params_form($order_id);
        } else {
            $params = parent::get_params_form($order_id);
        }
        $params['selectedcard'] = $this->selectedCard;
        return $params;
    }

    protected function ctrl_return_subscription($order_id, $api = false, $info_payment = null)
    {
        if ($this->is_recurring()) {
            $this->oNexiRecurring->ctrl_return_subscription($order_id, $api, $info_payment);
        } else {
            parent::ctrl_return_subscription($order_id, $api, $info_payment);
        }
    }

    public function scheduled_subscription_payment($amount_to_charge, $order)
    {
        $this->oNexiRecurring->scheduled_subscription_payment($amount_to_charge, $order);
    }

    public function init_form_fields()
    {
        $this->form_fields = array();
    }

    private function is_recurring()
    {
        return $this->recurring == 'Y' && class_exists('WC_Subscriptions_Order');
    }
}
