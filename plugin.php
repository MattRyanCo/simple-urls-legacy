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
// function register_my_settings(){
// 	$option_group = 'general';
// 	$option_name = 'surleg_import_file';
// 	$args = array(
// 		'type' 		=> 'string',
// 		'sanitize_callback' => 'sanitize_text_field',
// 		'description' => 'Basic description here.',
// 		'default' => NULL,
// 		'show_in_rest' => true,
// 	);
// 	register_setting( $option_group, $option_name, $args );

// 	// register a new section in the "reading" page
// 	// add_settings_section(
// 	// 'surleg_settings_section',
// 	// 'Simple URLs Legacy Settings Section', 'surleg_settings_section_callback',
// 	// 'options'
// 	// );

// 	// // register a new field in the "surleg_settings_section" section, inside the "reading" page
// 	// add_settings_field(
// 	// 	'surleg_settings_field',
// 	// 	'Simple URLs Legacy Setting', 'surleg_settings_field_callback',
// 	// 	'reading',
// 	// 	'surleg_settings_section'
// 	// );

// }

/**
 * callback functions
 */

// section content cb
// function surleg_settings_section_callback() {
// 	echo '<p>Simple URLs Legacy Section Introduction.</p>';
// }

// field content cb
// function surleg_settings_field_callback() {
// 	// get the value of the setting we've registered with register_setting()
// 	$setting = get_option('surleg_setting_name');
// 	// output the field
// 	?>
// 	<input type="text" name="surleg_setting_name" value="<?php echo isset( $setting ) ? esc_attr( $setting ) : ''; ?>">
// 	<?php
// }

add_action( 'admin_menu', 'surleg_add_settings' );

function surleg_add_settings(){

	add_submenu_page(
		'edit.php?post_type=surl',
		__( 'Simple URLs Legacy Settings', 'textdomain' ),
		__( 'SURLeg Settings', 'textdomain' ),
		'manage_options',
		'surleg-settings-page',
		'surleg_options_page_html'  // cb function.
	);

}

// ****************************************************************************** //

/**
 * @internal never define functions inside callbacks.
 * these functions could be run multiple times; this would result in a fatal error.
 */

/**
 * custom option and settings
 */
function surleg_settings_init() {
	// Register a new setting for 'surleg' page.
	register_setting( 'surleg', 'surleg_options' );

	// Register a new section in the "wporg" page.
	add_settings_section(
		'surleg_section_developers',  // id of the section.
		__( 'Optional settings to customize plugin operation.', 'surleg' ), // Title.
		'surleg_section_developers_callback',  //
		'surleg'  // slug of the setting page
	);

	// Register a new field in the "surleg_section_developers" section, inside the "surleg-settings-page" page.
	add_settings_field(
		'surleg_field_pill', // $id.
		__( 'Import Method', 'surleg' ),  // field title
		'surleg_field_pill_cb', // cb function
		'surleg',  // page
		'surleg_section_developers', // section id
		array(
			'label_for'         => 'surleg_field_pill',   // section title wrapped in <label> tag
			'class'             => 'surleg_row', // css class added to <tr> element for output
			'surleg_custom_data' => 'custom', // ??
		)
	);
	// Register a new field in the "surleg_section_developers" section, inside the "surleg-settings-page" page.
	add_settings_field(
		'surleg_field_filename', // $id.
		__( 'Import File', 'surleg' ),  // field title
		'surleg_field_filename_cb', // cb function
		'surleg',  // page
		'surleg_section_developers', // section id
		array(
			'label_for'         => 'surleg_field_filename',   // section title wrapped in <label> tag
			'class'             => 'surleg_row', // css class added to <tr> element for output
			'surleg_custom_data' => 'custom_option2', // ??
		)
	);

	add_settings_field(
		'surleg_field_export', // $id.
		__( 'Export Option', 'surleg' ),  // field title
		'surleg_field_export_cb', // cb function
		'surleg',  // page
		'surleg_section_developers', // section id
		array(
			'label_for'         => 'surleg_field_export',   // section title wrapped in <label> tag
			'class'             => 'surleg_row', // css class added to <tr> element for output
			'surleg_custom_data' => 'custom_export', // ??
		)
	);


	// add_settings_field(
	// 	'surleg_field_option3', // $id.
	// 	__( 'Option 3', 'surleg' ),  // field title
	// 	'surleg_field_option3_cb', // cb function
	// 	'surleg',  // page
	// 	'surleg_section_developers', // section id
	// 	array(
	// 		'label_for'         => 'surleg_field_option3',   // section title wrapped in <label> tag
	// 		'class'             => 'surleg_row', // css class added to <tr> element for output
	// 		'surleg_custom_data' => 'custom_option3', // ??
	// 	)
	// );
}

/**
 * Register our surleg_settings_init to the admin_init action hook.
 */
add_action( 'admin_init', 'surleg_settings_init' );


/**
 * Custom option and settings:
 *  - callback functions
 */


/**
 * Developers section callback function.
 *
 * @param array $args  The settings array, defining title, id, callback.
 */
function surleg_section_developers_callback( $args ) {
	?>
	<p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Section +++++++++++++++++++++++++++++++++++++' ); ?></p>
	<?php
}

/**
 * Pill field callback function.
 *
 * WordPress has magic interaction with the following keys: label_for, class.
 * - the "label_for" key value is used for the "for" attribute of the <label>.
 * - the "class" key value is used for the "class" attribute of the <tr> containing the field.
 * Note: you can add custom key value pairs to be used inside your callbacks.
 *
 * @param array $args
 */
function surleg_field_pill_cb( $args ) {
	// Get the value of the setting we've registered with register_setting()
	$options = get_option( 'surleg_options' );
	?>
	<select
			id="<?php echo esc_attr( $args['label_for'] ); ?>"
			data-custom="<?php echo esc_attr( $args['surleg_custom_data'] ); ?>"
			name="surleg_options[<?php echo esc_attr( $args['label_for'] ); ?>]">
		<option value="red" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'red', false ) ) : ( '' ); ?>>
			<?php esc_html_e( 'Restore', 'surleg' ); ?>
		</option>
 		<option value="blue" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'blue', false ) ) : ( '' ); ?>>
			<?php esc_html_e( 'Add', 'surleg' ); ?>
		</option>
	</select>
	<p class="description">
		<?php esc_html_e( 'Restore => Restore/update/add replacement redirects in bulk. Replaces existing. Adds new.', 'surleg' ); ?>
	</p>
	<p class="description">
		<?php esc_html_e( 'Add => Add new redirects in bulk.' , 'surleg' ); ?>
	</p>
	<p class="description">
		<hr>
		<div><?php echo __( 'Format the file with one redirect per line, PIPE ( <strong>|</strong> ) separated. CSV / TXT files only.', 'surleg' );?></div>
		<code>
		<?php echo __( 'redirect<strong>|</strong>destination<strong>|</strong>newwindow<strong>|</strong>nofollow', 'surleg' );?></code><br/>
		<br><p><?php echo __( 'Example:', 'surleg' );?></p>
		<code><?php echo __( '/old-location<strong>|</strong>https://example.com/new-destination/|0|1', 'surleg' );?></code><br />
		<code><?php echo __( '/donate/<strong>|</strong>https://example.com/donate-online/<strong>|</strong>1<strong>|</strong>1', 'surleg' );?></code><br/>
		<br/>
		<!-- <strong><?php echo __( 'IMPORTANT:', 'surleg' );?></strong> <?php echo __( 'Make Sure any destination URLs that have a PIPE in the querystring data are URL encoded before adding them!', 'surleg' );?><br/> -->
	</p>
	<?php
}
/**
 * Filename field callback function.
 *
 * WordPress has magic interaction with the following keys: label_for, class.
 * - the "label_for" key value is used for the "for" attribute of the <label>.
 * - the "class" key value is used for the "class" attribute of the <tr> containing the field.
 * Note: you can add custom key value pairs to be used inside your callbacks.
 *
 * @param array $args
 */
function surleg_field_filename_cb( $args ) {

	// Get the value of the setting we've registered with register_setting()
	$options = get_option( 'surleg_options' );
	?>
	<label>
		<?php echo esc_html( $field['label'] ) ?>
		<input
			type="file"
			id="<?php echo esc_attr( $args['label_for'] ); ?>"
			data-custom="<?php echo esc_attr( $args['surleg_custom_data'] ); ?>"
			name="surleg_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
			accept=".csv,.txt"
			>
	</label>
	<?php
}

/**
 * Option3 field callback function.
 *
 * @param array $args
 */
function surleg_field_export_cb( $args ) {

	// Get the value of the setting we've registered with register_setting()
	$options = get_option( 'surleg_options' );
	?>

	<p class="description">
		<?php esc_html_e( 'Export functionality & description goes here.', 'surleg' ); ?>
	</p>
	<?php
	// export_file_init();
}

/**
 * Top level menu callback function
 */
function surleg_options_page_html() {
	// check user capabilities
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// add error/update messages

	// check if the user have submitted the settings
	// WordPress will add the "settings-updated" $_GET parameter to the url
	if ( isset( $_GET['settings-updated'] ) ) {
		// add settings saved message with the class of "updated"
		add_settings_error( 'surleg_messages', 'surleg_message', __( 'Settings Saved', 'surleg' ), 'updated' );
	}

	// show error/update messages
	settings_errors( 'surleg_messages' );
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form action="options.php" method="post">
			<?php
			// output security fields for the registered settings "surleg"
			settings_fields( 'surleg' );
			// output setting sections and their fields
			// (sections are registered for "surleg", each field is registered to a specific section)
			do_settings_sections( 'surleg' );
			// output save settings button
			submit_button( 'Save Settings' );
			?>
		</form>
	</div>
	<?php
}

function export_file_init() {
	$file = plugin_dir_path( __FILE__ ) . '/surl_export.txt';
	$open = fopen( $file, "w" );

	$old_url = $new_destination = $window_flag = $nofollow_flag = '';
	$not_eof = true;
	// WP_Query arguments
	$args = array(
		'post_type'              => array( 'surl' ),
		'post_status'            => array( 'published' ),
	);

	// The Query
	$query = new WP_Query( $args );

	// The Loop
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$old_url = get_permalink();
			$new_destination = get_post_meta( $post->ID, '_surl_redirect', true );
			$output = sprintf("%s|%s|%s|%s\n", $old_url, $new_destination, $window_flag, $nofollow_flag);
			$write = fputs( $open, $output );// do something
		}
	} else {
		// no posts found
	}

	// Restore original Post Data
	wp_reset_postdata();
	while ( $not_eof ) {
		// Get next redirect
		// Parse line to 4 fields - old_url, new_destination, window_flag, nofollow_flag
		$output = sprintf("%s|%s|%s|%s\n", $old_url, $new_destination, $window_flag, $nofollow_flag);
		$write = fputs( $open, $output );
	}
	// $output has the redirect line in export/inport format.

	fclose( $open );
	// Prompt to download file after completed.

}
