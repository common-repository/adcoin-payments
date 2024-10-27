<?php
namespace AdCoinPayments;
defined('ABSPATH') or die;

class PaymentList extends \WP_List_Table {
	/**
	 * Retrieve a status' description.
	 * @param mixed $status The status number.
	 * @return string Status description.
	 */
	public static function get_status_text($status) {
		switch ($status) {
		case 0: return __('Awaiting payment', 'adcoin-payments');
		case 1: return __('Paid (unconfirmed)', 'adcoin-payments');
		case 2: return __('Payment confirmed', 'adcoin-payments');
		case 3: return __('Completed', 'adcoin-payments');
		case 4: return __('Failed (timed out)', 'adcoin-payments');
		}
	}



	/***************************************************************************
	 * Setup
	 **************************************************************************/

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct([
			'singular' => __('Payment', 'adcoin-payments'),
			'plural'   => __('Payments', 'adcoin-payments'),
			'ajax'     => false,
		]);
	}



	/***************************************************************************
	 * Database interaction
	 **************************************************************************/

	/**
	 * Inserts a new payment record into the database.
	 *
	 * @param array $payment Array containing the record's column data.
	 *     $payment = [
	 *         'PaymentID'    => (string) Payment ID.
	 *         'Amount'       => (float)  Price.
	 *         'Time'         => (string) When the payment was opened.
	 *         'Name'         => (string) Name that the user filled in.
	 *         'Email'        => (string) Email address that the user filled in.
	 *         'CustomFields' => (array)  JSON object of the custom fields and
	 *                                    their values provided by the user.
	 *         'Token'        => (string) Form token.
	 *     ]
	 */
	public static function open_payment(array $payment) {
		global $wpdb;
		$wpdb->insert(
			"{$wpdb->prefix}adcoin_payments",
			[
				'PaymentID'    => $payment['PaymentID'],
				'Amount'       => $payment['Amount'],
				'Time'         => $payment['Time'],
				'Name'         => (empty($payment['Name']) ? 'Anonymous' : $payment['Name']),
				'Email'        => $payment['Email'],
				'CustomFields' => json_encode($payment['CustomFields']),
				'Status'       => 0, // Default status
				'Token'        => $payment['Token']
			]
		);
	}

	 /**
	 * Creates all the database tables associated with this class.
	 */
	public static function create_db_tables() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$sql = 'CREATE TABLE '.$wpdb->prefix.'adcoin_payments (
			PaymentID VARCHAR(36) NOT NULL,
			Amount DECIMAL(5, 2),
			Time DATETIME,
			Name VARCHAR(40),
			Email VARCHAR(40),
			CustomFields VARCHAR(1024),
			Status INT(2) DEFAULT 0,
			Token VARCHAR(128),
			PRIMARY KEY (PaymentID)
		) '.$charset_collate;
		require_once ABSPATH.'wp-admin/includes/upgrade.php';
		dbDelta($sql);
	}

	 /**
	 * Fetch payments from database.
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return array List of payments.
	 */
	public static function get_payments($per_page = 20, $page_number = 1) {
		global $wpdb;
		$sql = "SELECT * FROM {$wpdb->prefix}adcoin_payments";
		if (!empty($_REQUEST['orderby'])) {
			$sql .= ' ORDER BY '.esc_sql($_REQUEST['orderby']);
			$sql .= !empty($_REQUEST['order']) ? ' '.esc_sql($_REQUEST['order']) : ' ASC';
		}
		$sql .= " LIMIT $per_page";
		$sql .= ' OFFSET '.($page_number - 1) * $per_page;

		return $wpdb->get_results($sql, ARRAY_A);
	}

	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public static function record_count() {
		global $wpdb;
		$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}adcoin_payments";
		return $wpdb->get_var($sql);
	}

	/**
	 * Deletes a payment record from the database.
	 *
	 * @param string $payment_id
	 */
	public function delete_payment($payment_id) {
		global $wpdb;
		$wpdb->delete($wpdb->prefix.'adcoin_payments', ['PaymentID' => $payment_id], ['%s']);
	}

	/**
	 * Retrieves a single payment record from the database.
	 *
	 * @param string $payment_id
	 *
	 * @return array
	 */
	public function get_payment($payment_id) {
		global $wpdb;
		$sql = "SELECT * FROM {$wpdb->prefix}adcoin_payments WHERE PaymentID='".esc_sql($payment_id)."'";
		return $wpdb->get_row($sql, ARRAY_A);
	}

	/**
	 * Sets the payment status of a given payment record.
	 *
	 * @param string $payment_id
	 * @param int    $status
	 * @param string $by         Should be either 'PaymentID' or 'Token'.
	 *
	 * @throws Exception
	 */
	public static function set_payment_status($payment_id, $status, $by = 'PaymentID') {
		global $wpdb;
		$result = $wpdb->update(
			"{$wpdb->prefix}adcoin_payments",
			['Status' => $status],
			[$by      => $payment_id]
		);
		if (false === $result)
			throw new \Exception('Unable to update payment status.');
	}



	/***************************************************************************
	 * Bulk actions
	 **************************************************************************/

	/**
	 * Returns an associative array containing the bulk actions.
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		$actions = [
			'bulk-delete' => __('Delete')
		];
		return $actions;
	}

	/**
	 * Process bulk actions.
	 */
	public function process_bulk_action() {
		// detect when a bulk action is being triggered
		if ('delete' === $this->current_action()) {
			// in our file that handles the request, verify the nonce
			$nonce = esc_attr($_REQUEST['_wpnonce']);
			if (!wp_verify_nonce($nonce, 'adcoin_payments_delete_payment')) {
				die('Invalid request.');
			} else {
				self::delete_payment($_GET['payment']);

				wp_redirect(esc_url_raw(strtok($_SERVER['REQUEST_URI'], '&')));
				exit;
			}
		}

		// if the delete bulk action is triggered
		if ((isset($_POST['action'])  && $_POST['action']  == 'bulk-delete')
		 || (isset($_POST['action2']) && $_POST['action2'] == 'bulk-delete')) {
			$delete_ids = esc_sql($_POST['bulk-delete']);

			// loop over the array of record IDs and delete them
			foreach ($delete_ids as $id) {
				self::delete_payment($id);
			}

			wp_redirect(esc_url_raw(strtok($_SERVER['REQUEST_URI'], '&')));
			exit;
		}
	}



	/***************************************************************************
	 * Data specific functions
	 **************************************************************************/
	/**
	 * Decode a custom fields JSON string from a given row.
	 *
	 * @param array $row
	 *
	 * @return array The decoded list of fields.
	 */
	public function decode_fields($row) {
		$fields = json_decode(stripslashes(stripslashes($row['CustomFields'])));
		return $fields;
	}



	/***************************************************************************
	 * Parent overrides
	 **************************************************************************/
	/** Text displayed when no payments are available. */
	public function no_items() {
		_e('No payments found.', 'adcoin-payments');
	}

	/**
	 * Render the name column.
	 *
	 * @param array $item An array of DB data.
	 *
	 * @return string
	 */
	public function column_name($item) {
		$view_nonce = wp_create_nonce('adcoin_payments_view_payment');
		$delete_nonce = wp_create_nonce('adcoin_payments_delete_payment');

		$href = sprintf(
			'?page=%s&action=view&payment=%s&_wpnonce=%s',
			$_REQUEST['page'], $item['PaymentID'], $view_nonce
		);
		$title = '<strong><a href="'.$href.'">'.$item['Name'].'</a></strong>';

		$actions = [
			'delete' => sprintf(
				'<a href="?page=%s&action=%s&payment=%s&_wpnonce=%s">%s</a>',
				esc_attr($_REQUEST['page']), 'delete', $item['PaymentID'], $delete_nonce, __('Delete', 'adcoin-payments')
			)
		];

		return $title . $this->row_actions($actions);
	}

	/**
	 * Render a column when no column specific method exists.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default($item, $column_name) {
		switch ($column_name) {
		case 'Time':
			return date_i18n(get_option('date_format'), strtotime($item['Time']));
		case 'Status':
			$o = '<mark class="adcoin-payments-status" value="'.$item['Status'].'"><span>';
			$o.= self::get_status_text($item['Status']);
			$o.= '</span></mark>';
			return $o;
		case 'Amount':
			return $item['Amount'].' ACC';
		default:
			return print_r($item, true);
		}
	}

	/**
	 * Render the bulk edit checkbox.
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	public function column_cb($item) {
		return sprintf(
			'<input type="checkbox" name="bulk-delete[]" value="%s">', $item['PaymentID']
		);
	}

	/**
	 * Associative array of columns.
	 *
	 * @return array
	 */
	public function get_columns() {
		$columns = [
			'cb'     => '<input type="checkbox">',
			'Name'   => __('Name', 'adcoin-payments'),
			'Time'   => __('Date', 'adcoin-payments'),
			'Status' => __('Status', 'adcoin-payments'),
			'Amount' => __('Amount', 'adcoin-payments')
		];
		return $columns;
	}

	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = [
			'Name'   => ['Name', true],
			'Time'   => ['Time', false],
			'Status' => ['Status', false],
			'Amount' => ['Amount', false]
		];
		return $sortable_columns;
	}

	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {
		$columns = $this->get_columns();
		$hidden = [];
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = [$columns, $hidden, $sortable];

		$per_page     = $this->get_items_per_page('payments_per_page', 20);
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();

		$this->set_pagination_args([
			'total_items' => $total_items,
			'per_page'    => $per_page
		]);

		$this->items = self::get_payments($per_page, $current_page);
	}

}
?>