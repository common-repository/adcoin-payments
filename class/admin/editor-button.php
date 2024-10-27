<?php
namespace AdCoinPayments;
defined('ABSPATH') or die;

class EditorButton {
	/**
	 * Constructor. Adds the AdCoin Payment Form media button to post/page editor.
	 */
	public function __construct() {
		global $pagenow, $typenow;
		if (in_array($pagenow, ['post.php', 'page.php', 'post-new.php', 'post-edit.php']) && 'download' != $typenow) {
			add_action('media_buttons', [$this, 'show_media_button'], 25);
			add_action('admin_footer', [$this, 'show_form_designer']);
		}
	}

	/**
	 * Include media button HTML.
	 */
	public function show_media_button() {
		include ADCOIN_PAYMENTS_BASE_PATH . 'views/form-designer/media-button.php';
	}

	/**
	 * Include payment form designer HTML.
	 */
	public function show_form_designer() {
		include ADCOIN_PAYMENTS_BASE_PATH . 'views/form-designer/designer.php';
	}
}