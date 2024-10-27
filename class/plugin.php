<?php
namespace AdCoinPayments;
defined('ABSPATH') or die;

class Plugin {

	// PHP objects
	public $callback_handler;
	public $editor_button;
	public $admin_backend;
	public $payment_form;

	// singleton
	private static $instance;



	/**
	 * Singleton instance.
	 */
	public static function get_instance() {
		if (!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		register_activation_hook(ADCOIN_PAYMENTS_FILE, [$this, 'activate']);
		add_action('plugins_loaded', [$this, 'init']);
	}

	/**
	 * Plugin activation hook.
	 * Registers the available plugin options
	 * Sets up the plugin's database tables.
	 */
	public function activate() {
		Settings::add_options();
		PaymentList::create_db_tables();
	}

	/**
	 * Localizes plugin, creates the PHP objects and hooks script insertion.
	 */
	public function init() {
		// localize plugin
		load_plugin_textdomain('adcoin-payments', false, dirname(plugin_basename(ADCOIN_PAYMENTS_FILE)).'/languages');
		// create objects
		if ( is_admin() ) {
			$this->editor_button = new EditorButton();
			$this->admin_backend = new AdminBackend();
		} else {
			$this->callback_handler = new CallbackHandler();
			$this->payment_form     = new PaymentForm();
		}
		// hook JS and CSS insertion.
		add_action('wp_enqueue_scripts', [$this, 'load_scripts']);
	}

	/**
	 * Load custom JS and CSS.
	 */
	public function load_scripts() {
		// load payment form javascript
		wp_enqueue_script('adcoin-payments-form' , ADCOIN_PAYMENTS_BASE_URL . '/assets/form.js');

		// load fonts
		wp_enqueue_style('adcoin-payments-fonts', 'http://fonts.googleapis.com/css?family=Dosis|Open+Sans');
		// load icons (yes, this is a separate stylesheet, thank you WordPress for not providing us with the ability to provide a path with wp_add_inline_style)
		wp_enqueue_style('adcoin-payments-donate-icon', ADCOIN_PAYMENTS_BASE_URL . '/assets/icons.css');
		// load default or custom CSS
		$css = Settings::get_css();
		if (empty($css)) {
			wp_enqueue_style('adcoin-payments-style', ADCOIN_PAYMENTS_BASE_URL . '/default.css');
		} else {
			wp_register_style('adcoin-payments-style-custom', false);
			wp_enqueue_style('adcoin-payments-style-custom');
			wp_add_inline_style('adcoin-payments-style-custom', $css);
		}
	}

}
?>