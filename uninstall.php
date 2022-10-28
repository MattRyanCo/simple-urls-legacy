<?php
/**
 * Simple_Urls_Legacy Plugin Unistall file.
 * Descriptin:  This file is run automatically when the users deletes the plugin.
 *
 * @package simple-urls-legacy
 *
 * Unistall functionality. This file is run automatically when the users deletes the plugin.
 * Here you can remove all elements added by plugin (e.g. custom options, tables, etc.)
 *
 * More informations: https://developer.wordpress.org/plugins/plugin-basics/uninstall-methods/
 */
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Remove fields
delete_option( 'surleg_fields' );
