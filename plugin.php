<?php
/**
 * Plugin Name: Simple URLs Legacy
 * Plugin URI: https://github.com/mattryanco/simple-urls-legacy/
 * Description: Simple URLs Legacy is a fork of the orignial Simple URLs plugin from Nathan Rice.
 * Author: Matt Ryan
 * Author URI: https://mattryan.co/
 * Version: 1.0.1

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

define( "SURLEG_URLS_SLUG", "surleg" );
define( 'SURLEG_DIR', dirname( __FILE__ ) );
// "C:\Users\EmAre\LocalSites\surldev\app\public\wp-content\plugins\simple-urls-legacy"

define( 'SURLEG_URL', plugins_url( '', __FILE__ ) );
// "http://surldev.test/wp-content/plugins/simple-urls-legacy"

define( 'SURLEG_PLUGIN_PATH', __DIR__ );
// "C:\Users\EmAre\LocalSites\surldev\app\public\wp-content\plugins\simple-urls-legacy"

define( 'SURLEG_ADMIN_DIR', dirname( __FILE__ ) . '/admin' );
// "C:\Users\EmAre\LocalSites\surldev\app\public\wp-content\plugins\simple-urls-legacy/admin"

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

	if ( is_plugin_active( 'simple-urls/plugin.php' ) ) {
		add_action( 'admin_notices', 'surleg_lasso_notice' );

		$ready = false;
	}

	if ( ! $ready ) {
		return;
	}
}

require_once SURLEG_DIR . '/includes/class-simple-urls-legacy.php';

new Simple_Urls_Legacy();

if ( is_admin() ) {
	include_once SURLEG_DIR . '/includes/class-simple-urls-legacy-admin.php';
	new Simple_Urls_Legacy_Admin();
}

// add_action( 'admin_init', 'register_my_settings' );
function register_my_settings(){
	$option_group = 'general';
	$option_name = 'surleg_import_file';
	$args = array(
		'type' 		=> 'string',
		'sanitize_callback' => 'sanitize_text_field',
		'description' => 'Basic description here.',
		'default' => NULL,
		'show_in_rest' => true,
	);
	register_setting( $option_group, $option_name, $args );

	// register a new section in the "reading" page
	// add_settings_section(
	// 'surleg_settings_section',
	// 'Simple URLs Legacy Settings Section', 'surleg_settings_section_callback',
	// 'options'
	// );

	// // register a new field in the "surleg_settings_section" section, inside the "reading" page
	// add_settings_field(
	// 	'surleg_settings_field',
	// 	'Simple URLs Legacy Setting', 'surleg_settings_field_callback',
	// 	'reading',
	// 	'surleg_settings_section'
	// );

}

/**
 * callback functions
 */

// section content cb
// function surleg_settings_section_callback() {
// 	echo '<p>Simple URLs Legacy Section Introduction.</p>';
// }

// field content cb
function surleg_settings_field_callback() {
	// get the value of the setting we've registered with register_setting()
	$setting = get_option('surleg_setting_name');
	// output the field
	?>
	<input type="text" name="surleg_setting_name" value="<?php echo isset( $setting ) ? esc_attr( $setting ) : ''; ?>">
	<?php
}

add_action( 'admin_menu', 'surleg_add_settings' );

function surleg_add_settings(){

	add_submenu_page(
		'edit.php?post_type=surl',
		__( 'SURLeg Settings', 'textdomain' ),
		__( 'SURLeg Settings', 'textdomain' ),
		'manage_options',
		'surleg-settings-page',
		'surleg_settings_page_callback'
	);

}
/**
 * Display callback for the submenu page.
 */
function surleg_settings_page_callback() {
    ?>
    <div class="wrap">
        <h1><?php _e( 'Simple URLs Legacy Settings', 'textdomain' ); ?></h1>
        <p><?php _e( 'Helpful stuff here', 'textdomain' ); ?></p>
    </div>
    <?php
}
