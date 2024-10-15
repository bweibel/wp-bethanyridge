<?php
/**
 * WP_Rig\WP_Rig\Accessibility\Component class
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig\Woocommerce;

use WP_Rig\WP_Rig\Component_Interface;
use function WP_Rig\WP_Rig\wp_rig;
use WP_Post;
use function add_action;
use function add_filter;


/**
 * Class for Woocommerce Modifications.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'woocommerce';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 * This is very ugly
	 */
	public function initialize() {
		// add_action( 'wp_enqueue_scripts', array( $this, 'action_require_flexslider_script' ) );
		add_action( 'after_setup_theme', array( $this, 'action_add_woocommerce_support' ));
        remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

        add_action('woocommerce_before_main_content', array( $this, 'bethany_woocommerce_theme_wrapper_start' ), 10 );
		add_action('woocommerce_after_main_content', array( $this, 'bethany_woocommerce_theme_wrapper_end' ), 10 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
        add_action( 'woocommerce_before_single_product_summary', 'woocommerce_template_single_title', 5 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
        // remove_filter( 'woocommerce_product_tabs', 'woocommerce_default_product_tabs' );
        add_action( 'woocommerce_after_shop_loop', 'wc_display_product_attributes', 10 );

    }

	/**
	 * Declares Woocommerce Support for the theme.
	 */
	public function action_add_woocommerce_support() {
		add_theme_support( 'woocommerce' );
	}

    public function bethany_woocommerce_theme_wrapper_start() {
        get_template_part( 'template-parts/components/torn_hero', '' );
        echo '<section class="woo-entry">';
	}

	public function bethany_woocommerce_theme_wrapper_end() {
        echo '</section">';
	}


}
