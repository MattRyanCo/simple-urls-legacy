<?php
/**
 * View for WordPress `admin_notice` if other verion of Simple Urls is active.
 *
 * @package simple-urls-legacy
 * @since 0.10.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div id="message" class="error notice">
	<p>
		<?php esc_html_e( 'Simple URLs Legacy must run without Lasso Simple URLs. Please deactivate the Lasso Lite version of Simple URLs.', 'surleg' ); ?>
	</p>
</div>
