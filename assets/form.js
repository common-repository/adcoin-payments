function adcoin_payments_validateEmail(email) {
	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test(email);
}

function adcoin_payments_validate() {
	jQuery("#result").text("");
	var email = jQuery("#adcoin_payments_email").val();
	if (adcoin_payments_validateEmail(email)) {
		jQuery("#adcoin_payments_email").css("background-color", "#c1f7cf");
	} else {
		jQuery("#adcoin_payments_email").css("background-color", "#ff9d9d");
	}
	return false;
}

jQuery(document).ready(function() {
	jQuery("#adcoin_payments_email").bind("click", adcoin_payments_validate);
	jQuery("#adcoin_payments_email").bind("input", adcoin_payments_validate);
});

jQuery(document).click(function(event) {
	if (!jQuery(event.target).closest("#adcoin_payments_email").length) {
		jQuery("#adcoin_payments_email").css("background-color", "#ffffff");
	}
});