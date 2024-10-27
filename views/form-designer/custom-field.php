<h2><?php _e('Custom Field', 'adcoin-payments');?></h2>
<table class="adcoin-payments-designer-table">
	<tr>
		<td>
		<input id="adcoin-payments-custom-description"
			   name="adcoin_payments_custom_description"
			   placeholder="<?php _e('Description', 'adcoin-payments');?>"
			   size="22"
			   type="text">
		</td>
		<td>
			<i>&nbsp;<?php _e('Field description', 'adcoin-payments');?></i>
		</td>
	</tr>
	<tr>
		<td>
			<label>
				<input id="adcoin-payments-custom-required"
					   name="adcoin_payments_custom_required"
					   type="checkbox">
				<?php _e('Required', 'adcoin-payments');?>
			</label>
		</td>
		<td>
			<i>&nbsp;<?php _e('Is the field required?', 'adcoin-payments');?></i>
		</td>
	</tr>
	<tr>
		<td>
			<input id="adcoin-payments-custom-insert"
			       onclick="adcoin_payments_InsertCustomField();"
				   type="button" value="<?php _e('Add Custom Field', 'adcoin-payments');?>">
		</td>
	</tr>
</table>