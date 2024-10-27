<?php
namespace AdCoinPayments;
defined('ABSPATH') or die;

class PaymentForm {
	/**
	 * Constructor. Tests for form submission and registers the form shortcode.
	 */
	public function __construct() {
		// test for form submission
		if (isset($_POST['adcoin_payments_pay'])) {
			try {
				$this->handle_form_submit();
			} catch (\Exception $e) {
				if (Settings::get_debug())
					wp_die($e->getMessage());
				error_log($e->getMessage());
			}
		}

		// register payment form shortcode
		add_shortcode('adcoin_payment', [$this, 'shortcode_adcoin_payment']);
	}

	/**
	 * Expands an AdCoin Payment Form shortcode.
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	public function shortcode_adcoin_payment($atts) {
		// get shortcode attributes
		$atts = shortcode_atts(
			[
				'alignment'    => '',
				'description'  => 'Hooray! The payment form works!',
				'price'        => '1',
				'url_success'  => '',
				'url_cancel'   => '',
				'title'        => 'Pay using AdCoin',
				'enable_name'  => 'true',
				'enable_email' => 'true',
				'button_text'  => 'Pay!',
				'fields'       => []
			],
			$atts
		);

		// sanitize and change values
		if (!is_float((float)$atts['price']))
			return false;
		$atts['price']        = number_format((float)$atts['price'], 2);
		$atts['url_success']  = sanitize_text_field($atts['url_success']);
		$atts['url_cancel']   = sanitize_text_field($atts['url_cancel']);
		$atts['enable_name']  = ('true' === $atts['enable_name']) ? true : false;
		$atts['enable_email'] = ('true' === $atts['enable_email']) ? true : false;
		$atts['button_text']  = sanitize_text_field($atts['button_text']);

		switch ($atts['alignment']) {
		case 'left':
			$alignment = 'style="float: left;"';
			break;
		case 'right':
			$alignment = 'style="float: right;"';
			break;
		case 'center':
			$alignment = 'style="margin-left: auto; margin-right: auto; width: 64px;"';
			break;
		default:
			$alignment = '';
		}
		unset($atts['alignment']);

		// decode fields string
		if (!empty($atts['fields'])) {
			$atts['fields'] = json_decode('['.$atts['fields'].']', true);
			if (!$atts['fields'])
				return false;
		} else {
			unset($atts['fields']);
		}

		// temporarily store form properties
		$form_data = new FormData();
		$token = $form_data->store($atts);

		// render button and it's popup contents
		ob_start();
		include ADCOIN_PAYMENTS_BASE_PATH . 'views/payment-form.php';
		return ob_get_clean();
	}

	/**
	 * Open a payment on the AdCoin payment gateway and register it to the database.
	 *
	 * @throws Exception
	 */
	private function handle_form_submit() {
		// make sure payment token was given
		if (!isset($_POST['adcoin_payments_token']) || empty($_POST['adcoin_payments_token']))
			throw new \Exception('Payment token not present.');
		$token = $_POST['adcoin_payments_token'];

		// gather and validate form properties and remove token afterwards
		$form_data = new FormData();
		$properties = $this->sanitize_form_properties($form_data->fetch($token));
		if (!$properties)
			throw new \Exception('Invalid form token.');

		// make sure all required post data is present
		if (!$this->are_form_values_present($properties))
			throw new \Exception('Not all form values were present.');
		$form_values = $this->get_form_values($properties);

		// open payment on the AdCoin payment gateway
		$gateway = new \AdCoin\API\PaymentGateway(Settings::get_api_key());
		$gateway_payment = $gateway->openPayment(
			$properties['price'],
			$properties['description'],
			CallbackHandler::get_return_url($token),
			CallbackHandler::get_webhook_url(),
			[]
		);

		// register the newly opened payment on the database
		$payment_record = [
			'PaymentID'    => $gateway_payment['id'],
			'Amount'       => $properties['price'],
			'Time'         => $gateway_payment['created_at'],
			'Name'         => $form_values['name'],
			'Email'        => $form_values['email'],
			'CustomFields' => $form_values['custom'],
			'Token'        => $token
		];
		PaymentList::open_payment($payment_record);

		// redirect user to the AdCoin payment gateway
		wp_redirect($gateway_payment['links']['paymentUrl']);
		die;
	}

	/**
	 * Checks whether all required form values are present in POST data.
	 *
	 * @param array $properties
	 *
	 * @return bool
	 */
	private function are_form_values_present($properties) {
		// check whether name and email values were provided if they were required
		if ( ($properties['enable_name']  && !isset($_POST['adcoin_payments_name'])) ||
		     ($properties['enable_email'] && !isset($_POST['adcoin_payments_email'])) )
			return false;

		// @TODO: custom form values required check

		return true;
	}

	/**
	 * Retrieve the values of the submitted payment form.
	 *
	 * @param array $properties Form properties.
	 *
	 * @return array The form's submitted values:
	 *     $values = [
	 *         'name'   => Name.
	 *         'email'  => Email.
	 *         'custom' => [
	 *             <field description> => <field value>
	 *         ]
	 *     ]
	 */
	private function get_form_values(array $properties) {
		// gather values
		$values = [];
		$values['name']  = $properties['enable_name']  ? sanitize_text_field($_POST['adcoin_payments_name'])  : '';
		$values['email'] = $properties['enable_email'] ? sanitize_text_field($_POST['adcoin_payments_email']) : '';
		for ($i = 0; $i < count($properties['fields']); ++$i)
			$values['custom'][$properties['fields'][$i]['description']] = sanitize_text_field($_POST["adcoin_payments_custom{$i}"]);
		return $values;
	}

	/**
	 * Validate given form properties and sanitize them.
	 *
	 * @param array $properties
	 *
	 * @return array Corrected properties array.
	 */
	private function sanitize_form_properties($properties) {
		// price
		if (!is_numeric($properties['price']))
			throw new \Exception('Invalid price given.');
		$properties['price'] = (float)$properties['price'];

		// description
		$properties['description'] = sanitize_text_field($properties['description']);

		return $properties;
	}
}
?>