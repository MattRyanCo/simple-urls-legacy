<?php

/**
 * Plugin Name: Simple URLs Legacy
 * Plugin URI: https://github.com/capwebsolutions/simple-urls-legacy/
 * Description: Simple URLs Legacy is a fork of the orignial plugin by Nathan Rice and Studiopress team. As such, it is a complete URL management system that allows you create, manage, and track outbound links from your site by using custom post types and 301 redirects, as it was meant to be.
 * Author: Cap Web Solutions/Matt Ryan
 * Author URI: https://capwebsolutions.com/
 * Version: 0.10.0

 * Text Domain: simple-urls-legacy
 * Domain Path: /languages

 * License: GNU General Public License v2.0 (or later)
 * License URI: http://www.opensource.org/licenses/gpl-license.php
 *
 * @package simple-urls-legacy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SIMPLE_URLS_LEGACY_DIR', plugin_dir_path( __FILE__ ) );
define( 'SIMPLE_URLS_LEGACY_URL', plugins_url( '', __FILE__ ) );

require_once SIMPLE_URLS_LEGACY_DIR . '/includes/class-simple-urls-legacy.php';

new Simple_Urls_Legacy();

if ( is_admin() ) {
	include_once SIMPLE_URLS_LEGACY_DIR . '/includes/class-simple-urls-legacy-admin.php';
	new Simple_Urls_Legacy_Admin();
}
