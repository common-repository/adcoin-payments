<?php
namespace AdCoinPayments;
defined('ABSPATH') or die;

class CallbackHandler {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action('rest_api_init', [$this, 'init_routes']);
	}

	/**
	 * Register the custom routes used by the plugin.
	 */
	public function init_routes() {
		// /wp-json/adcoin-payments/return/
		register_rest_route(
			'adcoin-payments', '/return/(?P<token>[a-z0-9-]+)', [
				'methods'  => 'GET',
				'callback' => [$this, 'route_return']
			]
		);

		// /wp-json/adcoin-payments/webhook/
		register_rest_route(
			'adcoin-payments', '/webhook/', [
				'methods'  => 'POST',
				'callback' => [$this, 'route_webhook']
			]
		);
	}

	/**
	 * AdCoin payment gateway return route.
	 *
	 * @param WP_REST_Request $request
	 */
	public function route_return(\WP_REST_Request $request) {
		// check status
		if (!isset($_GET['status'], $request['token']))
			die(Settings::get_debug() ? 'invalid' : '');

		// load form attributes
		$form_data = new FormData();
		$properties = $form_data->fetch($request['token']);
		if (!$properties) {
			wp_redirect(get_home_url());
			die;
		}
		$form_data->remove($request['token']);

		// determine whether the payment was successful
		if ('pending' == $_GET['status']) {
			$url = get_page_link($properties['url_success']);
			// mark order as paid (unconfirmed)
			PaymentList::set_payment_status($request['token'], 1, 'Token');
		} else {
			$url = get_page_link($properties['url_cancel']);
		}

		// redirect
		if (empty($url))
			$url = get_home_url();
		wp_redirect($url);
		die;
	}

	/**
	 * AdCoin payment gateway webhook function.
	 *
	 * @param WP_REST_Request $request
	 *
	 * @throws Exception
	 */
	public function route_webhook(\WP_REST_Request $request) {
		try {
			// check whether the required POST data is present
			if (!isset($_POST['id'],
					   $_POST['created_at'],
					   $_POST['status'],
					   $_POST['metadata'],
					   $_POST['hash'])) {
				throw new \Exception('Request lacks required data.');
			}

			// check whether the provided hash matches the provided POST data
			$query = http_build_query(array(
				'id'         => $_POST['id'],
				'created_at' => $_POST['created_at'],
				'status'     => $_POST['status'],
				'metadata'   => stripslashes($_POST['metadata'])
			));
			$query_hash = hash_hmac('sha512', $query, Settings::get_api_key());
			if ($_POST['hash'] != $query_hash)
				throw new \Exception('Provided hash does not match POST data.');

			// check payment status
			switch ($_POST['status']) {
			case 'paid': break;
			case 'timed_out':
				PaymentList::set_payment_status($_POST['id'], 4);
				throw new \Exception('Payment timed out for order ' . $metadata['order_id']);
			default:
				throw new \Exception('Given payment status is invalid: "'.$_POST['status'].'"');
			}

			// mark the payment as confirmed
			PaymentList::set_payment_status($_POST['id'], 2);

		} catch (\Exception $e) {
			if (Settings::get_debug())
				die($e->getMessage());
			// error_log($e->getMessage());
		}
	}

	/**
	 * Get the URL to redirect the user to after payment.
	 *
	 * @return string
	 */
	public static function get_return_url($token) {
		return trailingslashit(get_bloginfo('wpurl')).'wp-json/adcoin-payments/return/'.$token;
	}

	/**
	 * Get the URL that will be given to the AdCoin payment gateway.
	 * When the payment has been confirmed (or when it has timed out) this URL
	 * will be called.
	 *
	 * @return string
	 */
	public static function get_webhook_url() {
		return trailingslashit(get_bloginfo('wpurl')).'wp-json/adcoin-payments/webhook/';
	}

}