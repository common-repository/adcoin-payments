<div class="adcoin-payments-form" <?php echo $alignment; ?>>
	<div>
		<h4 class="adcoin-payments-title"><?php echo $atts['title']; ?></h4>
		<form method="post" action="">
			<input type="hidden" name="adcoin_payments_token" value="<?php echo $token; ?>">

			<?php
			// Name field
			if ($atts['enable_name']) {
				?>
				<p>
					<input type="text"
						   name="adcoin_payments_name"
						   size="22"
						   placeholder="Name"
						   required>
				</p>
				<?php
			}

			// Email field
			if ($atts['enable_email']) {
				?>
				<p>
					<input type="text"
						   name="adcoin_payments_email"
						   size="22"
						   placeholder="Email address"
						   id="adcoin_payments_email"
						   required>
					<label id="adcoin_payments_email_result">
					</label>
				</p>
				<?php
			}

			// Custom fields
			if (isset($atts['fields'])) {
				foreach ($atts['fields'] as $id => $field) {
					?>
					<p>
						<input type="text"
							   placeholder="<?php echo $field['description'];?>"
							   name="adcoin_payments_custom<?php echo $id;?>"
							   size="22"
							   <?php echo $field['required'] ? 'required' : '';?>>
					</p>
					<?php
				}
			}


			?>
			<button class="adcoin-payments-icon-adcoin" type="submit" name="adcoin_payments_pay"><span><?php echo $atts['button_text'];?></span></button>
		</form>
	</div>
</div>