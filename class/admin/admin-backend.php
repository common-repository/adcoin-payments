<?php
namespace AdCoinPayments;
defined('ABSPATH') or die;

class AdminBackend {
	private $payment_list;



	/**
	 * Constructor.
	 */
	public function __construct() {
		// show empty API key notice
		if ( empty(Settings::get_api_key()) ) {
			include ADCOIN_PAYMENTS_BASE_PATH.'/views/msg/no-api-key.php';
		}
		// add admin menu's
		add_filter('set-screen-option', [__CLASS__, 'set_screen'], 10, 3);
		add_action('admin_menu', [$this, 'add_menus']);
	}



	/***************************************************************************
	 * Admin menu's
	 **************************************************************************/

	/**
	 * Add admin menu pages to the WordPress backend.
	 */
	public function add_menus() {
		// Payments page
		$hook = add_menu_page(
			__('AdCoin Payments', 'adcoin-payments'),
			__('AdCoin Payments', 'adcoin-payments'),
			'manage_options',
			'adcoin-payments',
			[$this, 'show_payments_page'],
			get_file_url('assets/dashicon.png'),
			'38.74'
		);
		add_action("load-$hook", [$this, 'preprocess_payments_page']);

		// Settings page
		add_submenu_page(
			'adcoin-payments',
			__('AdCoin Payments Settings', 'adcoin-payments'),
			__('Settings'),
			'manage_options',
			'adcoin-payments-settings',
			[$this, 'show_settings_page']
		);

		add_action('admin_enqueue_scripts', [$this, 'load_admin_css']);
	}

	/**
	 * Preprocess payments page.
	 */
	public function preprocess_payments_page() {
		// payment list
		$this->payment_list = new PaymentList();
		$this->payment_list->process_bulk_action();

		// add screen option
		$option = 'per_page';
		$args   = [
			'label'   => __('Payments', 'adcoin-payments'),
			'default' => 20,
			'option'  => 'payments_per_page'
		];
		add_screen_option($option, $args);
	}

	public static function set_screen($status, $option, $value) {
		return $value;
	}



	/***************************************************************************
	 * Pages
	 **************************************************************************/

	/**
	 * Display contents of the settings page.
	 */
	public function show_settings_page() {
		// check permissions
		if (!current_user_can('manage_options')) {
			wp_die(__('You do not have the sufficient permissions to view this page.', 'adcoin-payments'));
		}
		// check whether the settings were changed by the user
		if (isset($_POST['update'])) {
			$this->settings_update();
		}
		// display page contents
		include ADCOIN_PAYMENTS_BASE_PATH . 'views/admin-menu/settings.php';
	}

	/**
	 * Display contents of the payments page.
	 */
	public function show_payments_page() {
		// check permissions
		if (!current_user_can('manage_options')) {
			wp_die(__('You do not have the sufficient permissions to view this page.', 'adcoin-payments'));
		}
		// display page contents based on action
		if (isset($_REQUEST['action']) && 'view' === $_REQUEST['action']) {
			$nonce = esc_attr($_REQUEST['_wpnonce']);
			if (!wp_verify_nonce($nonce, 'adcoin_payments_view_payment'))
				die(__('Invalid request.', 'adcoin-payments'));
			include ADCOIN_PAYMENTS_BASE_PATH . 'views/admin-menu/view-payment.php';
		} else {
			include ADCOIN_PAYMENTS_BASE_PATH . 'views/admin-menu/payments.php';
		}
	}



	/***************************************************************************
	 * Scripts and stylesheets
	 **************************************************************************/

	/**
	 * Load custom admin CSS stylesheets.
	 */
	public function load_admin_css() {
		wp_enqueue_style('adcoin-payments-admin-style', ADCOIN_PAYMENTS_BASE_URL . '/assets/admin.css');
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



	/***************************************************************************
	 * Settings
	 **************************************************************************/

	/**
	 * Updates the plugin settings with values from the settings form.
	 */
	public function settings_update() {
		if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'adcoin-payments-settings-update')) {
			wp_die(__('Error! Nonce security check failed! Go back to the settings menu and save the settings again.', 'adcoin-payments'));
		}

		Settings::update_from_post('api_key');
		Settings::update_from_post('css', 'textarea');
		Settings::update_from_post('debug', 'checkbox');

		include ADCOIN_PAYMENTS_BASE_PATH . 'views/msg/settings-updated.php';
	}
}