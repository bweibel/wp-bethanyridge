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

	}

	/**
	 * Declares Woocommerce Support for the theme.
	 */
	public function action_add_woocommerce_support() {
		add_theme_support( 'woocommerce' );
	}

	/**
	 * Add age-check checkbox to checkout.
	 */
	public function bethany_woocommerce_add_checkout_age_check( $checkout ) {
		if ( $this->bethany_woocommerce_check_is_beer() ) {
			woocommerce_form_field(
				'bethany_age_checkbox',
				array(
					'type'          => 'checkbox',
					'class'         => array('input-checkbox'),
					'label'         => __('By clicking this box, I certify that I am over 21+ years of age. <strong>ID will be checked upon delivery.</strong>'),
					'required'  => true,
				),
			$checkout->get_value( 'bethany_age_checkbox' ) );

		}
	}

	/**
	 * Process the checkout
	 */
	public function crux_woocommerce_age_checkout_field_process() {
		global $woocommerce;

		if ( $this->crux_woocommerce_check_is_beer() ) {
			// Check if set, if its not set add an error.
			if (!$_POST['crux_age_checkbox'])
				$woocommerce->add_error( __('You must certify that you are over 21 years of age.' ) );
		}
	}

	public function crux_woocommerce_age_checkout_field_update_order_meta( $order_id ) {
		if ( $this->crux_woocommerce_check_is_beer() ) {
			if ($_POST['crux_age_checkbox']) {
				update_post_meta( $order_id, 'Age Certification', 'I am over 21' );
			}
		}
	}

	/**
	 * Display field value on the order edit page
	 */
	public function crux_woocommerce_age_checkout_field_display_admin_order_meta( $order ) {
		$over21 = get_post_meta( $order->id, 'Age Certification', true );
		if ( ! $over21 ) {
			$over21 = 'No';
		}
		echo '<p><strong>' . __( 'Over 21+ years of age' ) . ':</strong> ' . $over21 . '</p>';
	}

	/**
	 * Add custom "crux_age_checkbox" field to emails
	 *  1. Add this snippet to your theme's functions.php file
	 *  2. Change the meta key names in the snippet
	 *  3. Create a custom field in the order post - e.g. key = "Tracking Code" value = abcdefg
	 *  4. When next updating the status, or during any other event which emails the user, they will see this field in their email
	 */
	public function crux_woocommerce_custom_order_meta_keys( $keys ) {
		$keys[] = 'crux_age_checkbox';
		$keys[] = 'Age Certification';
		return $keys;
	}

	// Only allow shipping in Oregon if they have beer in their carts.
	public function crux_woocommerce_limit_state_for_beer( $states ) {
		if ( $this->crux_woocommerce_check_is_beer() ) {
			/*
			$states['US'] = array(
				'OR' => __( 'Oregon', 'woocommerce' )
			);
			*/
		} elseif ( $this->crux_woocommerce_check_is_na_beer() ) {
			$states['US'] = array(
				'AZ' => __( 'Arizona', 'woocommerce' ),
				'CA' => __( 'California', 'woocommerce' ),
				'OR' => __( 'Oregon', 'woocommerce' ),
				'NV' => __( 'Nevada', 'woocommerce' ),
				'WA' => __( 'Washington', 'woocommerce' ),
			);
		}
		return $states;
	}

	/**
	 * Hide shipping rates when free shipping is available.
	 * Updated to support WooCommerce 2.6 Shipping Zones.
	 *
	 * @param array $rates Array of rates found for the package.
	 * @return array
	 */
	public function crux_show_shipping_based_on_beer( $rates ) {
		$is_beer = $this->crux_woocommerce_check_is_beer();
		$is_na_beer = $this->crux_woocommerce_check_is_na_beer();

		$subtotal = WC()->cart->get_subtotal();

		$min_free_shipping = 75;

		$new_rates = array();
		foreach ( $rates as $rate_id => $rate ) {
			if ( $subtotal >= $min_free_shipping ) {
				if ( 'Free shipping' == $rate->label ) {
					$new_rates[ $rate_id ] = $rate;
					//break;
				}
			} else {
				if ( 'Beer (Flat Rate)' == $rate->label ) {
					if ( $is_beer || $is_na_beer ) {
						$new_rates[ $rate_id ] = $rate;
					}
					//break;
				} elseif ( ! $is_beer && ! $is_na_beer ) {
					$new_rates[ $rate_id ] = $rate;
				}
			}
		}
		return $new_rates;
		//return ! empty( $new_rates ) ? $new_rates : $rates;
	}
}
