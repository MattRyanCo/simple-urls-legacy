<?php

/**
 * Simple URLs Legacy file.
 *
 * @package simple-urls-legacy
 */

/**
 * Simple URLs Legacy class.
 */
class Simple_Urls_Legacy {


	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'template_redirect', array( $this, 'count_and_redirect' ) );
	}

	/**
	 * Load textdomain.
	 */
	public function load_textdomain() {
		 load_plugin_textdomain( 'simple-urls-legacy', false, SIMPLE_URLS_LEGACY_DIR . '/languages' );
	}

	/**
	 * Register Post Type.
	 */
	public function register_post_type() {
		$slug = 'surleg';

		$rewrite_slug_default = 'go';

		$labels = array(
			'name'               => __( 'Simple URLs Legacy', 'simple-urls-legacy' ),
			'singular_name'      => __( 'URL', 'simple-urls-legacy' ),
			'add_new'            => __( 'Add New', 'simple-urls-legacy' ),
			'add_new_item'       => __( 'Add New URL', 'simple-urls-legacy' ),
			'edit'               => __( 'Edit', 'simple-urls-legacy' ),
			'edit_item'          => __( 'Edit URL', 'simple-urls-legacy' ),
			'new_item'           => __( 'New URL', 'simple-urls-legacy' ),
			'view'               => __( 'View URL', 'simple-urls-legacy' ),
			'view_item'          => __( 'View URL', 'simple-urls-legacy' ),
			'search_items'       => __( 'Search URL', 'simple-urls-legacy' ),
			'not_found'          => __( 'No URLs found', 'simple-urls-legacy' ),
			'not_found_in_trash' => __( 'No URLs found in Trash', 'simple-urls-legacy' ),
			'messages'           => array(
				0  => '', // Unused. Messages start at index 1.
				/* translators: %s: link for the update */
				1  => __( 'URL updated. <a href="%s">View URL</a>', 'simple-urls-legacy' ),
				2  => __( 'Custom field updated.', 'simple-urls-legacy' ),
				3  => __( 'Custom field deleted.', 'simple-urls-legacy' ),
				4  => __( 'URL updated.', 'simple-urls-legacy' ),
				/* translators: %s: date and time of the revision */
				5  => isset($_GET['revision']) ? sprintf(__('Post restored to revision from %s', 'simple-urls-legacy'), wp_post_revision_title((int) $_GET['revision'], false)) : false, // phpcs:ignore
				/* translators: %s: URL to view */
				6  => __( 'URL updated. <a href="%s">View URL</a>', 'simple-urls-legacy' ),
				7  => __( 'URL saved.', 'simple-urls-legacy' ),
				8  => __( 'URL submitted.', 'simple-urls-legacy' ),
				9  => __( 'URL scheduled', 'simple-urls-legacy' ),
				10 => __( 'URL draft updated.', 'simple-urls-legacy' ),
			),
		);

		$labels = apply_filters( 'simple_urls_legacy_cpt_labels', $labels );

		$rewrite_slug = apply_filters( 'simple_urls_legacy_slug', $rewrite_slug_default );

		$rewrite_slug = sanitize_title( $rewrite_slug, $rewrite_slug_default );

		// Ref: https://developer.wordpress.org/reference/functions/add_post_type_support/.
		$supports_array = apply_filters( 'simple_urls_legacy_post_type_supports', array( 'title' ) );

		// Ref: https://developer.wordpress.org/reference/functions/register_post_type/.
		register_post_type(
			$slug,
			array(
				'labels'              => $labels,
				'public'              => true,
				'exclude_from_search' => apply_filters( 'simple_urls_legacy_exclude_from_search', true ),
				'show_ui'             => true,
				'query_var'           => true,
				'menu_position'       => 20,
				'supports'            => $supports_array,
				'rewrite'             => array(
					'slug'       => $rewrite_slug,
					'with_front' => false,
				),
				'show_in_rest'        => true,
			)
		);
	}

	/**
	 * Count and redirect function.
	 */
	public function count_and_redirect() {
		if ( ! is_singular( 'surleg' ) ) {
			return;
		}

		global $wp_query;

		// Update the count.
		$count = isset( $wp_query->post->_surleg_count ) ? (int) $wp_query->post->_surleg_count : 0;
		update_post_meta( $wp_query->post->ID, '_surleg_count', $count + 1 );

		// Handle the redirect.
		$redirect = isset( $wp_query->post->ID ) ? get_post_meta( $wp_query->post->ID, '_surleg_redirect', true ) : '';

		/**
		 * Filter the redirect URL.
		 *
		 * @since 0.9.5
		 *
		 * @param string  $redirect The URL to redirect to.
		 * @param int  $var The current click count.
		 */
		$redirect = apply_filters( 'simple_urls_legacy_redirect_url', $redirect, $count );

		/**
		 * Action hook that fires before the redirect.
		 *
		 * @since 0.9.5
		 *
		 * @param string  $redirect The URL to redirect to.
		 * @param int  $var The current click count.
		 */
		do_action( 'simple_urls_legacy_redirect', $redirect, $count );

		if ( ! empty( $redirect ) ) {
			wp_redirect( esc_url_raw( $redirect ), 301 ); // phpcs:ignore WordPress.Security.SafeRedirect.wp_redirect_wp_redirect -- the redirect URL was added by a user with access to the admin and is filterable. Adding to allowed_redirect_hosts does little to improve security here.
			exit;
		} else {
			wp_safe_redirect( home_url(), 302 );
			exit;
		}
	}
}
