<?php
?>
<script type="text/javascript">
	var adcoin_payments_custom_fields_id = 0;
	var adcoin_payments_custom_fields    = [];



	function adcoin_payments_EscapeJSON(text){
		return text.replace('[', '').replace(']', '');
	}

	function adcoin_payments_EscapeHTML(text) {
		'use strict';
		return text.replace(/[\"&<>]/g, function (a) {
			return { '"': '&quot;', '&': '&amp;', '<': '&lt;', '>': '&gt;' }[a];
		});
	}

	function adcoin_payments_SanitizeText(text){
		return adcoin_payments_EscapeHTML(
			text.replace('[', '&#91;').replace(']', '&#93;')
		);
	}



	function adcoin_payments_CreateShortcode(
		alignment, description, price, url_success, url_cancel,
		title, enable_name, enable_email, button_text,
		custom_fields
	) {
		// add form properties to shortcode
		var shortcode = '[adcoin_payment ';
		if ("" != alignment) {
			shortcode += "alignment='"+alignment+"' ";
		}
		shortcode += "description='"+description+"' ";
		shortcode += "price='"+price+"' ";
		if ("" != url_success) {
			shortcode += "url_success='"+url_success+"' ";
		}
		if ("" != url_cancel) {
			shortcode += "url_cancel='"+url_cancel+"' ";
		}
		shortcode += "title='"+title+"' ";
		shortcode += "enable_name='"+enable_name+"' ";
		shortcode += "enable_email='"+enable_email+"' ";
		shortcode += "button_text='"+button_text+"' ";

		// add custom fields to shortcode
		var shortcode_fields = [];
		for (var key in custom_fields) {
			if (custom_fields.hasOwnProperty(key)) {
				shortcode_fields.push(custom_fields[key]);
			}
		}
		shortcode_fields = JSON.stringify(shortcode_fields);
		shortcode += " fields='"+adcoin_payments_EscapeJSON(shortcode_fields)+"']";

		// put shortcode in editor
		window.send_to_editor(shortcode);
	}

	function adcoin_payments_DecimalPlaces(num) {
		var match = (''+num).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);
		if (!match) { return 0; }
		return Math.max(
		0,
		// Number of digits right of decimal point.
		(match[1] ? match[1].length : 0)
		// Adjust for scientific notation.
		- (match[2] ? +match[2] : 0));
	}



	function adcoin_payments_InsertPaymentForm() {
		// gather form values
		var element_alignment    = document.getElementById("adcoin-payments-alignment");
		var element_description  = document.getElementById("adcoin-payments-description");
		var element_price        = document.getElementById("adcoin-payments-price");
		var element_url_success  = document.getElementById("adcoin-payments-success-url");
		var element_url_cancel   = document.getElementById("adcoin-payments-cancel-url");
		var element_price        = document.getElementById("adcoin-payments-price");
		var element_title        = document.getElementById("adcoin-payments-preview-title");
		var element_enable_name  = document.getElementById("adcoin-payments-name-enabled");
		var element_enable_email = document.getElementById("adcoin-payments-email-enabled");
		var element_button_text  = document.getElementById("adcoin-payments-button-text");
		var alignment    = element_alignment.value;
		var description  = element_description.value;
		var price        = element_price.value;
		var url_success  = element_url_success.value;
		var url_cancel   = element_url_cancel.value;
		var title        = element_title.value;
		var enable_name  = element_enable_name.checked;
		var enable_email = element_enable_email.checked;
		var button_text  = element_button_text.value;

		// validate form values
		if (!description.match(/\S/)) {
			alert("<?php _e('Payment description is required.', 'adcoin-payments');?>");
			return false;
		}
		if (!price.match(/\S/)) {
			alert("<?php _e('Price is required.', 'adcoin-payments');?>");
			return false;
		}
		if (adcoin_payments_DecimalPlaces(price) > 2) {
			alert("<?php _e('Price has a maximum of 2 decimals. For example: 12.34');?>");
			return false;
		}
		if (!title.match(/\S/)) {
			alert("<?php _e('Title is required.', 'adcoin-payments');?>");
			return false;
		}
		if (!button_text.match(/\S/)) {
			alert("<?php _e('Button text is required.', 'adcoin-payments');?>");
			return false;
		}

		// create shortcode
		adcoin_payments_CreateShortcode(
			alignment, description, price, url_success, url_cancel,
			title, enable_name, enable_email, button_text,
			adcoin_payments_custom_fields
		);

		// empty the form values
		element_alignment.value      = "";
		element_description.value    = "";
		element_price.value          = "";
		element_url_success.value    = "";
		element_url_cancel.value     = "";
		element_title.value          = "";
		element_enable_name.checked  = true;
		element_enable_email.checked = true;
		element_button_text.value    = "";
		jQuery('#adcoin-payments-preview-custom-fields').empty();
		jQuery('#adcoin-payments-custom-description').val('');
		jQuery('#adcoin-payments-custom-required').prop('checked', false);
	}



  /*
   * Field slider checkboxes.
   */
	jQuery(document).ready(function() {
		jQuery('#adcoin-payments-name-enabled').change(function() {
			if (jQuery(this).is(':checked')) {
				jQuery('#adcoin-payments-preview-name').removeClass('adcoin-payments-disabled-field');
			} else {
				jQuery('#adcoin-payments-preview-name').addClass('adcoin-payments-disabled-field');
			}
		});
		jQuery('#adcoin-payments-email-enabled').change(function() {
			if (jQuery(this).is(':checked')) {
				jQuery('#adcoin-payments-preview-email').removeClass('adcoin-payments-disabled-field');
			} else {
				jQuery('#adcoin-payments-preview-email').addClass('adcoin-payments-disabled-field');
			}
		});
	});



	function adcoin_payments_InsertCustomField() {
		// get field properties
		var description = adcoin_payments_EscapeHTML(jQuery('#adcoin-payments-custom-description').val());
		var type        = jQuery('#adcoin-payments-custom-type').val();
		var required    = jQuery('#adcoin-payments-custom-required').is(':checked');

		// make SURE that the field name is non-empty
		if (!description.match(/\S/)) {
			alert("<?php _e('Field description is required.', 'adcoin-payments');?>");
			return false;
		}

		// determine custom field id
		var field_id = adcoin_payments_custom_fields_id;
		adcoin_payments_custom_fields_id++;
		adcoin_payments_custom_fields.push(
			{
				description: description,
				type       : type,
				required   : required
			}
		);
		// console.debug(adcoin_payments_custom_fields);

		// show field in preview
		// jQuery('#adcoin-payments-preview-custom-fields').append(
		// 	'<p id="adcoin-payments-preview-field-'+field_id+'">' +
		// 	'    <input name="'+field_id+'"' +
		// 	'           class="adcoin-payments-preview-field"' +
		// 	'           placeholder="'+description+'"' +
		// 	'           size="22"' +
		// 	'           type="text" disabled '+required+'>' +
		// 	'    <input class="adcoin-payments-preview-field-remove"' +
		// 	'           type="button"' +
		// 	'           onclick="adcoin_payments_RemoveCustomField('+field_id+')"' +
		// 	'           value="X">' +
		// 	'</p>'
		// );
		jQuery('#adcoin-payments-preview-custom-fields').append(
			'<p id="adcoin-payments-preview-field-'+field_id+'">' +
			'    <input name="'+field_id+'"' +
			'           class="adcoin-payments-preview-field"' +
			'           placeholder="'+description+'"' +
			'           size="22"' +
			'           type="text" disabled '+required+'>' +
			'    <img src="<?php echo ADCOIN_PAYMENTS_BASE_URL.'assets/trash.png';?>"' +
			'         onclick="adcoin_payments_RemoveCustomField('+field_id+')"' +
			'         width="40" height="40"/>' +
			'</p>'
		);

		// reset custom field insertion values
		jQuery('#adcoin-payments-custom-description').val('');
		jQuery('#adcoin-payments-custom-required').prop('checked', false);
	}



	function adcoin_payments_RemoveCustomField(id) {
		// remove field from array
		delete adcoin_payments_custom_fields[id];

		// remove field and delete button from preview
		jQuery('#adcoin-payments-preview-field-'+id).remove();
	}


</script>



<div id="adcoin-payments-form-designer">
	<?php include ADCOIN_PAYMENTS_BASE_PATH.'views/form-designer/properties.php';?>
	<?php include ADCOIN_PAYMENTS_BASE_PATH.'views/form-designer/preview.php';?>
	<?php include ADCOIN_PAYMENTS_BASE_PATH.'views/form-designer/custom-field.php';?>
	<br><input type="button"
				 id="adcoin_payments_insert"
				 class="button-primary adcoin-payments-admin-button"
				 onclick="adcoin_payments_InsertPaymentForm();"
				 value="<?php _e('Insert Payment Form', 'adcoin-payments');?>">
</div>

