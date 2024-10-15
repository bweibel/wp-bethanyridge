<?php
/**
 * WP_Rig\WP_Rig\Remove_Comments\Component class
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig\Remove_Comments;

use WP_Rig\WP_Rig\Component_Interface;
use function add_action;

/**
 * Class for removing comments sidebar from admin section
 */

class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.-white
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'remove_comments';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'admin_menu', array( $this, 'remove_admin_menus' ) );
		add_action( 'init', array( $this, 'remove_comment_support' ) );
		add_action( 'wp_before_admin_bar_render', array( $this, 'remove_from_admin_bar' ) );
	}

	/**
	 * Removes comments option from admin menu
	 */
	public function remove_admin_menus() {
		remove_menu_page( 'edit-comments.php' );
	}

	/**
	 * Removes comment support from posts and pages
	 */
	public function remove_comment_support() {
		remove_post_type_support( 'post', 'comments' );
		remove_post_type_support( 'page', 'comments' );
	}

	/**
	 * Remove from admin bar
	 */
	public function remove_from_admin_bar() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu( 'comments' );
	}

}