<?php
/**
 * Main plugin class
 */

namespace WooNakedCatPlugins\MarkdownAlternateWooCommerce;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Our main class
 */
final class MarkDown_Alternate_WooCommerce {

	/**
	 * The plugin version
	 *
	 * @var $version The plugin version
	 */
	private $version = MARKDOWN_ALTERNATE_WOOCOMMERCE_VERSION;

	/**
	 * The plugin id
	 *
	 * @var $id The plugin id
	 */
	public $id = 'markdown-alternate-woocommerce';

	/**
	 * Single instance
	 *
	 * @var $instance The classs single instance
	 */
	protected static $instance = null;

	/**
	 * Constructor
	 */
	private function __construct() {
		// Hooks
		$this->init_hooks();
	}

	/**
	 * Ensures only one instance of our plugin is loaded or can be loaded
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Hooks
	 */
	private function init_hooks() {
		// Add product CPT
		add_filter( 'markdown_alternate_supported_post_types', array( $this, 'add_product_cpt' ) );
	}

	/**
	 * Add product CPT to supported post types
	 *
	 * @param array $post_types Supported post types
	 * @return array
	 */
	public function add_product_cpt( $post_types ) {
		$post_types[] = 'product';
		return $post_types;
	}
}

/* If you're reading this you must know what you're doing ;-) Greetings from sunny Portugal! */
