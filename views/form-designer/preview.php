<h2><?php _e('Preview', 'adcoin-payments');?></h2>
<div class="adcoin-payments-form">
  <div>
  	<form method="post" action="">
  		<h3 class="adcoin-payments-title">
  			<input id="adcoin-payments-preview-title"
  				   placeholder="<?php _e('Title', 'adcoin-payments');?>"
  				   title="<?php _e('The title shown in the top of the form.', 'adcoin-payments');?>"
  				   size="25"
  				   type="text" value="">
  		</h3>

  		<p>
        <input class="adcoin-payments-preview-field"
    			   id="adcoin-payments-preview-name"
    			   placeholder="<?php _e('Name', 'adcoin-payments');?>"
    			   size="22"
    			   type="text" disabled>
    		<label class="adcoin-payments-preview-switch switch">
    			<input id="adcoin-payments-name-enabled" name="adcoin_payments_name_enabled" type="checkbox" checked>
    			<span class="slider round"></span>
    		</label>
      </p>

      <p>
    		<input class="adcoin-payments-preview-field"
    			     id="adcoin-payments-preview-email"
    			     placeholder="<?php _e('Email', 'adcoin-payments');?>"
    			     size="22"
    			     type="text" disabled>
    		<label class="adcoin-payments-preview-switch switch">
    			<input id="adcoin-payments-email-enabled" name="adcoin_payments_email_enabled" type="checkbox" checked>
    			<span class="slider round"></span>
    		</label>
      </p>


  		<div id="adcoin-payments-preview-custom-fields"></div>


      <button type="submit" name="adcoin_payments_pay" disabled>
          <input id="adcoin-payments-button-text"
  				     placeholder="<?php _e('Button text', 'adcoin-payments');?>"
  				     size="12"
  				     type="text">
      </button>

  	</form>
  </div>
</div>