<?php
/*
Plugin Name:       AdCoin Payments
Plugin URI:        http://www.getadcoin.com/
Description:       Allow your visitors to donate or buy products and services with AdCoins through a customisable payment form.
Version:           1.0.0
Author:            Adcoin Click B.V.
Author URI:        http://www.getadcoin.com
Text Domain:       adcoin-payments
*/
defined('ABSPATH') or die;
define('ADCOIN_PAYMENTS_FILE', __FILE__);
define('ADCOIN_PAYMENTS_BASE_PATH', plugin_dir_path(__FILE__));
define('ADCOIN_PAYMENTS_BASE_URL', plugin_dir_url(__FILE__));

require_once ADCOIN_PAYMENTS_BASE_PATH . 'includes/functions.php';
if (!class_exists('WP_List_Table')) {
	require_once ABSPATH.'wp-admin/includes/class-wp-list-table.php';
}
if (!class_exists('AdCoin\\Exception\\ClientException')) {
	require_once ADCOIN_PAYMENTS_BASE_PATH . 'includes/wallet-api-wrapper/Exception/ClientException.php';
	require_once ADCOIN_PAYMENTS_BASE_PATH . 'includes/wallet-api-wrapper/API/PaymentGateway.php';
}
require_once ADCOIN_PAYMENTS_BASE_PATH . 'class/admin/admin-backend.php';
require_once ADCOIN_PAYMENTS_BASE_PATH . 'class/admin/editor-button.php';
require_once ADCOIN_PAYMENTS_BASE_PATH . 'class/callback-handler.php';
require_once ADCOIN_PAYMENTS_BASE_PATH . 'class/payment-list.php';
require_once ADCOIN_PAYMENTS_BASE_PATH . 'class/payment-form.php';
require_once ADCOIN_PAYMENTS_BASE_PATH . 'class/form-data.php';
require_once ADCOIN_PAYMENTS_BASE_PATH . 'class/settings.php';
require_once ADCOIN_PAYMENTS_BASE_PATH . 'class/plugin.php';

AdCoinPayments\Plugin::get_instance();
?>