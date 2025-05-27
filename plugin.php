<?php
/**
 * Plugin Name: Simple URLs Legacy
 * Plugin URI: https://github.com/mattryanco/simple-urls-legacy/
 * Description: Simple URLs Legacy is a fork of the original Simple URLs plugin from Nathan Rice.
 * Author: Matt Ryan
 * Author URI: https://mattryan.co/
 * Version: 1.0.4
 *
 * Domain Path: /languages
 *
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package simple-urls-legacy
 */

/*
Simple URLs Legacy is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Simple URLs Legacy is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Simple URLs Legacy. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


add_action( 'after_setup_theme', 'surleg_setup' );
/**
 * Setup Simple Urls Legacy.
 *
 * Checks whether the new Simple Urls Lasso is active.
 * If it is, bail. If not, let's roll.
 * Loads the necessary files, actions and filters for the plugin
 * to do its thing.
 *
 * @since 0.9.0
 */
function surleg_setup() {
	include_once SURLEG_ADMIN_DIR . '/notices.php';

	$ready = true;

	if ( ! function_exists( 'is_plugin_active' ) ) {
		include_once ABSPATH . '/wp-admin/includes/plugin.php';
	}

	define( 'SURLEG_PLUGIN_VERSION', get_plugin_data(__FILE__ )['Version'] );

	if ( is_plugin_active( 'simple-urls/plugin.php' ) ) {
		add_action( 'admin_notices', 'surleg_lasso_notice' );

		$ready = false;
	}

	if ( ! $ready ) {
		return;
	}
}

define( 'SURLEG_DIR', dirname( __FILE__ ) );
define( 'SURLEG_ADMIN_DIR', dirname( __FILE__ ) . '/admin' );
require_once SURLEG_DIR . '/includes/class-simple-urls-legacy.php';

new Simple_Urls_Legacy();

if ( is_admin() ) {
	include_once SURLEG_DIR . '/includes/class-simple-urls-legacy-admin.php';
	new Simple_Urls_Legacy_Admin();
}
