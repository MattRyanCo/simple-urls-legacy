<?php
/**
 * Callbacks for `admin_notices` action to load HTML notices.
 *
 * @package simple-urls-legacy
 * @since 0.10.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Display notice message if Simple Urls ala Lasso is active.
 *
 * Callback for WordPress 'admin_notices' action.
 *
 * @since 1.0
 */
function surleg_lasso_notice() {
	include SURLEG_ADMIN_DIR . '/notice-other-version-active.php';
}

