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
		// Add product taxonomies to frontmatter
		add_filter( 'markdown_alternate_frontmatter_taxonomies', array( $this, 'add_frontmatter_product_taxonomies' ), 10, 2 );
		// Add product details to frontmatter
		add_filter( 'markdown_alternate_frontmatter_content_lines', array( $this, 'add_frontmatter_product_details' ), 10, 2 );
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

	/**
	 * Add product taxonomies to frontmatter
	 * Only availabe if this branch is merged: https://github.com/webdados/markdown-alternate/tree/refactor-frontmatter
	 *
	 * @param array   $taxonomies Supported taxonomies
	 * @param WP_Post $post The post object
	 * @return array
	 */
	public function add_frontmatter_product_taxonomies( $taxonomies, $post ) {
		$post_type = get_post_type( $post );
		if ( $post_type === 'product' ) {
			// Unset default post taxonomies
			if ( isset( $taxonomies['category'] ) ) {
				unset( $taxonomies['category'] );
			}
			if ( isset( $taxonomies['post_tag'] ) ) {
				unset( $taxonomies['post_tag'] );
			}
			// Add product categories and tags
			$taxonomies['product_cat'] = 'categories';
			$taxonomies['product_tag'] = 'tags';
			// Add product brand, only if registered
			if ( taxonomy_exists( 'product_brand' ) && is_object_in_taxonomy( 'product', 'product_brand' ) ) {
				$taxonomies['product_brand'] = 'brands';
			}
		}
		return $taxonomies;
	}

	/**
	 * Add product details to frontmatter
	 * - Price
	 * - Currency
	 * - Stock
	 * Very simple implementation just for single products
	 * Only availabe if this branch is merged: https://github.com/webdados/markdown-alternate/tree/refactor-frontmatter
	 *
	 * @param array   $content_lines Supported content lines
	 * @param WP_Post $post The post object
	 * @return array
	 */
	public function add_frontmatter_product_details( $content_lines, $post ) {
		$post_type = get_post_type( $post );
		if ( $post_type === 'product' ) {
			$product = wc_get_product( $post->ID );
			if ( $product ) {
				$content_lines['price']    = array(
					'type'    => 'number',
					'content' => $product->get_price(),
				);
				$content_lines['currency'] = array(
					'type'    => 'string',
					'content' => get_woocommerce_currency(),
				);
				$content_lines['stock']    = array(
					'type'    => 'number',
					'content' => $product->get_stock_quantity(),
				);
			}
		}
		return $content_lines;
	}
}

/* If you're reading this you must know what you're doing ;-) Greetings from sunny Portugal! */
