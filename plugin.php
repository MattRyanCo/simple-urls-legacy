<?php

/**
 * Plugin Name: Simple URLs Legacy
 * Plugin URI: https://github.com/capwebsolutions/simple-urls-legacy/
 * Description: Simple URLs Legacy is a fork of the orignial plugin by Nathan Rice and the StudioPress team. As such, it is a complete URL management system that allows you create, manage, and track outbound links from your site by using custom post types and 301 redirects, as it was meant to be.
 * Author: Matt Ryan | Cap Web Solutions
 * Author URI: https://mattryan.co/
 * Version: 0.10.1

 * Text Domain: simple-urls-legacy
 * Domain Path: /languages

 * License: GNU General Public License v2.0 (or later)
 * License URI: http://www.opensource.org/licenses/gpl-license.php
 *
 * @package simple-urls-legacy
 */

if (! defined('ABSPATH') ) {
    exit;
}

define('SURLEG_DIR', dirname(__FILE__));
define('SURLEG_ADMIN_DIR', dirname(__FILE__) . '/admin');

add_action('after_setup_theme', 'surleg_setup');
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
function surleg_setup()
{

    include_once SURLEG_ADMIN_DIR . '/notices.php';

    $ready = true;

    if (! function_exists('is_plugin_active') ) {
        include_once ABSPATH . '/wp-admin/includes/plugin.php';
    }

    if (is_plugin_active('simple-urls/plugin.php') ) {
        add_action('admin_notices', 'surleg_lasso_notice');

        $ready = false;
    }

    if (! $ready ) {
        return;
    }
}

require_once SURLEG_DIR . '/includes/class-simple-urls-legacy.php';

new Simple_Urls_Legacy();

if (is_admin() ) {
    include_once SURLEG_DIR . '/includes/class-simple-urls-legacy-admin.php';
    new Simple_Urls_Legacy_Admin();
}
