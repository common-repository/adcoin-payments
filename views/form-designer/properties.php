<h2><?php _e('Properties', 'adcoin-payments');?></h2>

<table class="adcoin-payments-designer-table">
	<tr>
		<td>
			<select id="adcoin-payments-alignment">
				<option value="" selected></option>
				<option value="left"><?php _e('Left', 'adcoin-payments');?></option>
				<option value="center"><?php _e('Center', 'adcoin-payments');?></option>
				<option value="right"><?php _e('Right', 'adcoin-payments');?></option>
			</select>
		</td>
		<td>
			<i>&nbsp;<?php _e('Form alignment (Optional)', 'adcoin-payments');?></i>
		</td>
	</tr>
	<tr>
		<td>
			<textarea id="adcoin-payments-description"
				   placeholder="<?php _e('Payment description', 'adcoin-payments');?>"
				   size="40"></textarea>
		</td>
		<td>
			<i>&nbsp;<?php _e('Text that will be shown on the payment page', 'adcoin-payments');?></i>
		</td>
	</tr>
	<tr>
		<td>
			<input id="adcoin-payments-price"
				   placeholder="<?php _e('Price', 'adcoin-payments');?>"
				   title="<?php _e('The price in ACC. Example: 12.34', 'adcoin-payments');?>"
				   type="number" min="0.01" max="1000000.00" step="0.01" value="" required>
		</td>
		<td>
			<i>&nbsp;<?php _e('The price in ACC. Example: 12.34', 'adcoin-payments');?></i>
		</td>
	</tr>
	<tr>
		<td>
			<select id="adcoin-payments-success-url">
				<?php
				?><option value="" selected>----------</option><?php
				if ($pages = get_pages()) {
					foreach ($pages as $page) {
						?><option value="<?php echo $page->ID;?>"><?php echo $page->post_title;?></option><?php
					}
				}
				?>
			</select>
		</td>
		<td>
			<i>&nbsp;<?php _e('After the customer successfully pays on the payment page, they will be redirected to this URL.'.
			                  '<br>(The default link is the home page.)', 'adcoin-payments');?></i>
		</td>
	</tr>
	<tr>
		<td>
			<select id="adcoin-payments-cancel-url">
				<?php
				?><option value="" selected>----------</option><?php
				if ($pages = get_pages()) {
					foreach ($pages as $page) {
						?><option value="<?php echo $page->ID;?>"><?php echo $page->post_title;?></option><?php
					}
				}
				?>
			</select>
		</td>
		<td>
			<i>&nbsp;<?php _e('If the customer cancels the payment on the payment page, they will be redirected to this URL.'.
			                  '<br>(The default link is the home page.)', 'adcoin-payments');?></i>
		</td>
	</tr>

</table>