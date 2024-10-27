<div class="wrap">
	<h1><strong><?php _e('AdCoin Payments Settings', 'adcoin-payments');?></strong></h1>

	<div id="poststuff">

		<div class="adcoin-payments-settings-title">
			&nbsp; <?php _e('Usage', 'adcoin-payments');?>
		</div>
		<div class="adcoin-payments-settings-contents">
			<table class="form-table"><tr><td>
				<?php _e('In the page or post editor, you will see a new button called "AdCoin Payment Form" located above the text area beside the "Add Media" button.<br>
				With this button you can create shortcodes that will show up as payment forms on your website.<br>
				<br>
				You can place as many forms in a post or page as you want. In order to remove a button, just remove the shortcode text in your post or page.',
				'adcoin-payments');?>
			</td></tr></table>
		</div><br>

		<form method="post" action="">
			<?php echo wp_nonce_field("adcoin-payments-settings-update"); ?>

			<div class="adcoin-payments-settings-title">
				&nbsp;<?php _e('Settings', 'adcoin-payments');?>
			</div>
			<div class="adcoin-payments-settings-contents">
					<table class="form-table">

						<!-- API key -->
						<tr valign="top">
							<td width="25%" align="left">
								<strong><?php _e('AdCoin Wallet API Key', 'adcoin-payments');?></strong>
							</td>
							<td align="left">
								<input type="text" name="adcoin_payments_api_key" maxlength="64" size="75" value="<?php echo \AdCoinPayments\Settings::get_api_key(); ?>">
								<br><i><?php _e('This is the API key from your AdCoin Wallet.'.
								                '<br>You can find the key in your <a href="https://wallet.getadcoin.com">AdCoin Web Wallet</a> under your name > "API Key".', 'adcoin-payments');?></i><br>
							</td>
						</tr>

						<!-- Custom Form CSS -->
						<tr valign="top">
							<td width="25%" align="left">
								<strong><?php _e('Custom Form CSS', 'adcoin-payments');?></strong>
							</td>
							<td align="left">
								<textarea name="adcoin_payments_css" rows="7" cols="77" style="font-family:monospace;"><?php echo \AdCoinPayments\Settings::get_css();?></textarea>
								<br><i><?php _e('When the field above is not empty, the default CSS will be replaced by your custom CSS.<br>
								For reference, the default stylesheet is located in:', 'adcoin-payments');?> <strong>"wp-content/plugins/adcoin-payments/default.css"</strong>.</i><br>
							</td>
						</tr>

						<!-- Debug Mode -->
						<tr valign="top">
							<td width="25%" align="left">
								<strong><?php _e('Debug mode', 'adcoin-payments');?></strong>
							</td>
							<td align="left">
								<?php
								$checked = \AdCoinPayments\Settings::get_debug() ? 'checked' : '';
								?>
								<input type="checkbox" name="adcoin_payments_debug" <?php echo $checked; ?>>
								<i><?php _e('WARNING! Developers only!', 'adcoin-payments');?></i><br>
							</td>
						</tr>

					</table>
			</div><br>

			<input class="button-primary adcoin-payments-admin-button" type="submit" name="update" value="<?php _e('Update', 'adcoin-payments');?>">
		</form>

	</div>
</div>