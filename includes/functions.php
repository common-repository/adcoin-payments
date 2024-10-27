<?php
namespace AdCoinPayments;
defined('ABSPATH') or die;

/**
 * Returns the possessive variant of a name.
 *
 * @param string $name
 *
 * @return string
 */
function make_name_possessive($name) {
	$name .= "'";
	if ('s' != $name[strlen($name) - 2])
		$name .= 's';
	return $name;
}

/**
 * Returns the URL of a given file resource.
 *
 * @param string $file Path to the file relative to the plugin directory.
 *
 * @return string Full file URL.
 */
function get_file_url($file) {
  return ADCOIN_PAYMENTS_BASE_URL . $file;
}
?>