== AdCoin Payments ==
Contributors: appels, adcoin
Tags: adcoin, acc, cryptocurrency, payment, gateway, checkout, ecommerce, e-commerce, payments, blockchain, cryptocurrency
Requires at least: 4.7
Requires PHP: 5.3
Tested up to: 4.9.4
Stable tag: 0.9.9
License: GPLv2
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html

== Description ==

Quickly integrate and enable AdCoin Payments in Wordpress, wherever you need them. Simply drop them ready-made with the form builder into any page or post with this powerful plugin by AdCoin Click BV. With the simple order management module, you can enable payments without complex e-commerce plugins. AdCoin is dedicated to making payments better, easier and cheaper.

> Cryptocurrency payments, for Wordpress

No need to spend weeks on paperwork or security compliance procedures. No more lost conversions because you don't support a shopper's local payment method, you need to ask for address data or because they don't feel safe. We made payments intuitive and safe for merchants and their customers.

= GETTING STARTED =

Please go to the [AdCoin Wallet](https://wallet.getadcoin.com/register) to create a new AdCoin Wallet and hit 'Generate API key' under your personal settings tab. Contact support@getadcoin.com if you have any questions or comments about this plugin.

> No startup fees, no monthly fees, and no gateway fees. Receive and send payments instantly and for free. 

= FEATURES =

* Stand-alone solution, no need to install e-commerce plugins into your Wordpress installation.
* Accept payments directly into your personal AdCoin wallet.
* Accept payment in AdCoin for physical, digital downloadable products and/or services.
* Zero fees and no commissions for AdCoin payments processing from any third party.
* [Powerful dashboard](https://wallet.getadcoin.com) on wallet.getadcoin.com to easily keep track of your payments.
* Fast in-house support. You will always be helped by someone who knows our products intimately.

== Frequently Asked Questions ==

= I can't install the plugin, the plugin is displayed incorrectly =

Please temporarily enable the [WordPress Debug Mode](https://codex.wordpress.org/Debugging_in_WordPress). Edit your `wp-config.php` and set the constants `WP_DEBUG` and `WP_DEBUG_LOG` to `true` and try
it again. When the plugin triggers an error, WordPress will log the error to the log file `/wp-content/debug.log`. Please check this file for errors. When done, don't forget to turn off
the WordPress debug mode by setting the two constants `WP_DEBUG` and `WP_DEBUG_LOG` back to `false`.

= I get a white screen when opening ... =

Most of the time a white screen means a PHP error. Because PHP won't show error messages on default for security reasons, the page is white. Please turn on the WordPress Debug Mode to turn on PHP error messages (see previous answer).

= The AdCoin payment gateway isn't displayed on my pages or post = 

* Please go to AdCoin Payments -> Settings and check if you entered a valid AdCoin Wallet API key
* Go to 'Pages' and select a page where you want to enable someone to pay in AdCoin. Hit 'AdCoin Paymentform' above your editor to create a new and custom payment form into your page.

= After receiving a payment, the status is 'Paid (Unconfirmed)' =

The payment is for 99.9% ensured. However, the transaction is not yet confirmed by the AdCoin Blockchain. This will normally take up to 3 hours. 

= Can I carry out my order when the status is unconfirmed? =

Yes, you can. However, there is a change that the blockchain is hacked and the transaction is fake.

== Screenshots ==

1. Simple AdCoin Payment overview.
2. The AdCoin Payment Form into any page or post.
3. AdCoin Payment Gateway
4. When the customer completes the payment, they will be redirected back to the shop.
5. Order received page.

== Installation ==

= Minimum Requirements =

* PHP version 5.3 or greater
* PHP extensions enabled: cURL
* WordPress 3.8 or greater

= Automatic installation =

1. Install the plugin via Plugins -> New plugin. Search for 'AdCoin Payments'.
2. Activate the 'AdCoin Payments' plugin through the 'Plugins' menu in WordPress
3. Set your AdCoin API key at AdCoin Payments -> Settings
4. You're done, your customers can now pay with AdCoin on any page on your website by inserting an AdCoin Payments form.

= Manual installation =

1. Unpack the download package
2. Upload the directory 'adcoin-payments' to the `/wp-content/plugins/` directory
3. Activate the 'AdCoin Payments' plugin through the 'Plugins' menu in WordPress
3. Set your AdCoin API key at AdCoin Payments -> Settings
4. You're done, your customers can now pay with AdCoin on any page on your website by inserting an AdCoin Payments form.

Please contact support@getadcoin.com if you need help installing the AdCoin Payments plugin. Please provide your AdCoin Wallet ID and website URL.

= Updating =

Automatic updates should work like a charm; as always though, ensure you backup your site just in case.

== Changelog ==

