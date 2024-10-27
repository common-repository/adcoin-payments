<?php
namespace AdCoinPayments;
defined('ABSPATH') or die;

class Settings {
	public static function add_options() {
		add_option('adcoin_payments_api_key');
		add_option('adcoin_payments_css');
		add_option('adcoin_payments_debug');
	}



	public static function get_api_key() {
		return self::setting_get('api_key');
	}

	public static function get_debug() {
		return self::setting_get('debug');
	}

	public static function get_css() {
		$css = stripslashes(preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", self::setting_get('css')));
		return $css;
	}



	/**
	 * Retrieves a plugin setting.
	 *
	 * @param string $name The setting's name.
	 * @param mixed $default Default value.
	 *
	 * @returns mixed Setting value.
	 */
	private static function setting_get($name, $default = false) {
		return get_option('adcoin_payments_'.$name, $default);
	}

	/**
	 * Updates the value of a plugin setting.
	 *
	 * @param string $name The setting's name.
	 * @param string $type Input type, can be 'text', 'url'.
	 */
	public static function update_from_post($name, $type = 'text') {
		$field_name = 'adcoin_payments_'.$name;
		switch ($type) {
		case 'text':
			update_option($field_name, sanitize_text_field(trim($_POST[$field_name])));
			break;
		/*case 'url':
			update_option($field_name, esc_url_raw(sanitize_text_field(trim($_POST[$field_name]))));
			break;*/
		case 'textarea':
			update_option($field_name, sanitize_textarea_field(trim($_POST[$field_name])));
			break;
		case 'checkbox':
			update_option($field_name, (isset($_POST[$field_name]) ? true : false));
			break;
		}
	}
}
?>