<?php
namespace AdCoinPayments;
defined('ABSPATH') or die;

class FormData {
	// Transient expiration time in seconds
	private $expiration = 3600;


	/**
	 * Temporarily store an array of attributes.
	 *
	 * @param array $atts Button attributes to store.
	 *
	 * @return string Token Used as key to the stored attributes.
	 */
	public function store($atts) {
		do {
			$token = $this->generate_token();
		} while ($this->exists($token));
		set_transient('adcoin_payments_form_'.$token, $atts, $this->expiration);
		return $token;
	}

	/**
	 * Fetch data assigned to a given token.
	 *
	 * @param string $token Token used to identify the data.
	 *
	 * @return array Fetched token metadata.
	 *         bool  false if the token was not found, invalid or exipired.
	 */
	public function fetch($token) {
		if ((strlen($token) != 32) || !$this->exists($token))
			return false;
		return get_transient('adcoin_payments_form_'.$token);
	}

	/**
	 * Delete a given token and it's data from the database.
	 *
	 * @param string $token Token used to identify the data.
	 */
	public function remove($token) {
		if ($this->exists($token))
			delete_transient('adcoin_payments_form_'.$token);
	}

	/**
	 * Checks whether the given token has data assigned to it.
	 *
	 * @param string $token Key used to identify the data.
	 *
	 * @return bool True if it exists, otherwise false.
	 */
	private function exists($token) {
		$timeout = get_option('_transient_timeout_adcoin_payments_form_'.$token);
		return ($timeout > time());
	}

	/**
	 * Generates a random token.
	 *
	 * @return string 32 character string.
	 */
	private function generate_token() {
		return bin2hex(openssl_random_pseudo_bytes(16));
	}
}
?>