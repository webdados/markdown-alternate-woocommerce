<?php
/**
 * Plugin Name:       Markdown Alternate WooCommerce
 * Description:       WooCommerce integration for Markdown Alternate
 * Plugin URI:        https://github.com/webdados/markdown-alternate-woocommerce
 * Version:           0.1
 * Author:            Naked Cat Plugins (by Webdados)
 * Author URI:        https://nakedcatplugins.com
 * Text Domain:       markdown-alternate-woocommerce
 * Requires at least: 6.0
 * Tested up to:      7.0
 * Requires PHP:      7.4
 * License:           GPL-3.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Requires Plugins:  markdown-alternate, woocommerce
 */

namespace WooNakedCatPlugins\MarkdownAlternateWooCommerce;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Initialize plugin
 *
 * @return MarkDown_Alternate_WooCommerce
 */
function init() {
	// Set version constant
	if ( ! function_exists( 'get_plugin_data' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	$plugin_data = get_plugin_data( __FILE__, false, false );
	define( 'MARKDOWN_ALTERNATE_WOOCOMMERCE_VERSION', $plugin_data['Version'] );
	// Load the main plugin class
	require_once __DIR__ . '/includes/class-markdown-alternate-woocommerce.php';
	// Instantiate the main plugin class
	return MarkDown_Alternate_WooCommerce::instance();
}
add_action( 'init', '\WooNakedCatPlugins\MarkdownAlternateWooCommerce\init' );

/* HPOS and Blocks Compatible */
add_action(
	'before_woocommerce_init',
	function () {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', __FILE__, true );
	}
);

/* If you're reading this you must know what you're doing ;-) Greetings from sunny Portugal! */
