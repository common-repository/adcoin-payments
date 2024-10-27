<?php
namespace AdCoinPayments;
defined('ABSPATH') or die;

$payment = $this->payment_list->get_payment($_GET['payment']);
if (!$payment)
	wp_die(__('Invalid request.', 'adcoin-payments'));

$name = ('Anonymous' === $payment['Name']) ? $payment['Name'] : make_name_possessive($payment['Name']);

?>

<div class="wrap">

	<h1><strong><?php echo $name;?> Payment</strong></h1>

	<?php
	// Update payment status.
	if (isset($_POST['update'])) {
		$payment['Status'] = $_POST['adcoin_payments_status'];
		PaymentList::set_payment_status($_GET['payment'], $payment['Status']);
		include ADCOIN_PAYMENTS_BASE_PATH.'/views/msg/status-updated.php';
	}
	?>

	<form method="post">
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-2">
				<div id="post-body-content">

					<h2><?php _e('Payment Information', 'adcoin-payments');?>:</h2>
					<table class="wp-list-table widefat striped">
						<tr>
							<td><?php _e('Created at', 'adcoin-payments');?></td>
							<td>
								<?php echo date_i18n(get_option('date_format'), strtotime($payment['Time']));?>
							</td>
						</tr>
						<?php
						if (!empty($payment['Email'])) {
							?>
							<tr>
								<td><?php _e('Email', 'adcoin-payments');?></td>
								<td><?php echo $payment['Email'];?></td>
							</tr>
							<?php
						}
						?>
						<tr>
							<td><?php _e('Status', 'adcoin-payments');?></td>
							<td>
								<select name="adcoin_payments_status">
									<option value="0" <?php selected($payment['Status'], 0);?>><?php echo PaymentList::get_status_text(0);?></option>
									<option value="1" <?php selected($payment['Status'], 1);?>><?php echo PaymentList::get_status_text(1);?></option>
									<option value="2" <?php selected($payment['Status'], 2);?>><?php echo PaymentList::get_status_text(2);?></option>
									<option value="3" <?php selected($payment['Status'], 3);?>><?php echo PaymentList::get_status_text(3);?></option>
									<option value="4" <?php selected($payment['Status'], 4);?>><?php echo PaymentList::get_status_text(4);?></option>
								</select>
							</td>
						</tr>
						<tr>
							<td><?php _e('Amount', 'adcoin-payments');?></td>
							<td><?php echo $payment['Amount'];?> ACC</td>
						</tr>
					</table>
					<br>

					<h2><?php _e('Additional Fields', 'adcoin-payments');?>:</h2>
					<table class="wp-list-table widefat striped">
						<thead>
							<tr>
								<th><?php _e('Field', 'adcoin-payments');?></th>
								<th><?php _e('Value', 'adcoin-payments');?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$fields = $this->payment_list->decode_fields($payment);
							foreach ($fields as $key => $field) {
								if ('_empty_' !== $key) {
									?>
									<tr>
										<td><?php echo $key;?></td>
										<td><?php echo $field;?></td>
									</tr>
									<?php
								}
							}
							?>
						</tbody>
					</table>

				</div><!-- END /#post-body-content -->
			</div><!-- END /#post-body -->
			<br class="clear">
			<input class="button-primary adcoin-payments-admin-button" type="submit" name="update" value="<?php _e('Update', 'adcoin-payments');?>">
		</div><!-- END /#poststuff -->
	</form>

</div><!-- END .wrap -->
