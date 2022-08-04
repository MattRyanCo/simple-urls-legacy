<?php
/**
 * Simple_Urls_Admin file.
 *
 * @package simple-urls
 */

/**
 * Simple_Urls_Admin class.
 */
class Simple_Urls_Admin {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'post_updated_messages', array( $this, 'updated_message' ) );
		add_action( 'admin_menu', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'meta_box_save' ), 1, 2 );
		add_action( 'manage_posts_custom_column', array( $this, 'columns_data' ) );
		add_filter( 'manage_edit-surl_columns', array( $this, 'columns_filter' ) );
	}

	/**
	 * Colum filter.
	 *
	 * @param  array $columns Columns.
	 *
	 * @return array          Filtered columns.
	 */
	public function columns_filter( $columns ) {

		$columns = array(
			'cb'        => '<input type="checkbox" />',
			'title'     => __( 'Title', 'simple-urls' ),
			'url'       => __( 'Redirect to', 'simple-urls' ),
			'permalink' => __( 'Permalink', 'simple-urls' ),
			'clicks'    => __( 'Clicks', 'simple-urls' ),
		);

		return $columns;

	}

	/**
	 * Columns data.
	 *
	 * @param  array $column Columns.
	 */
	public function columns_data( $column ) {

		global $post;

		$url   = get_post_meta( $post->ID, '_surl_redirect', true );
		$count = get_post_meta( $post->ID, '_surl_count', true );

		$allowed_tags = array(
			'a' => array(
				'href' => array(),
				'rel'  => array(),
			),
		);

		if ( 'url' === $column ) {
			echo wp_kses( make_clickable( esc_url( $url ? $url : '' ) ), $allowed_tags );
		} elseif ( 'permalink' === $column ) {
			echo wp_kses( make_clickable( get_permalink() ), $allowed_tags );
		} elseif ( 'clicks' === $column ) {
			echo esc_html( $count ? $count : 0 );
		}

	}

	/**
	 * Update message.
	 *
	 * @param  array $messages Messages.
	 *
	 * @return array           Messages.
	 */
	public function updated_message( $messages ) {

		$surl_object = get_post_type_object( 'surl' );

		$messages['surl'] = $surl_object->labels->messages;

		$permalink = get_permalink();

		if ( $permalink ) {
			foreach ( $messages['surl'] as $id => $message ) {
				$messages['surl'][ $id ] = sprintf( $message, $permalink );
			}
		}

		return $messages;

	}

	/**
	 * Add metabox.
	 */
	public function add_meta_box() {
		add_meta_box( 'surl', __( 'URL Information', 'simple-urls' ), array( $this, 'meta_box' ), 'surl', 'normal', 'high' );
	}

	/**
	 * Metabox.
	 */
	public function meta_box() {

		global $post;

		printf( '<input type="hidden" name="_surl_nonce" value="%s" />', esc_attr( wp_create_nonce( plugin_basename( __FILE__ ) ) ) );

		printf( '<p><label for="%s">%s</label></p>', '_surl_redirect', esc_html__( 'Redirect URI', 'simple-urls' ) );
		printf( '<p><input style="%s" type="text" name="%s" id="%s" value="%s" /></p>', 'width: 99%;', '_surl_redirect', '_surl_redirect', esc_attr( get_post_meta( $post->ID, '_surl_redirect', true ) ) );
		printf( '<p><span class="description">%s</span></p>', esc_html__( 'This is the URL that the Redirect Link you create on this page will redirect to when accessed in a web browser.', 'simple-urls' ) );

		$count = isset( $post->ID ) ? get_post_meta( $post->ID, '_surl_count', true ) : 0;
		/* translators: %d is the counter of clicks. */
		echo '<p>' . sprintf( esc_html__( 'This URL has been accessed %d times', 'simple-urls' ), esc_attr( $count ) ) . '</p>';

	}

	/**
	 * Metabox save function.
	 *
	 * @param  string  $post_id Post Id.
	 * @param  WP_Post $post   Post.
	 */
	public function meta_box_save( $post_id, $post ) {

		$key = '_surl_redirect';

		// Verify the nonce.
		// phpcs:ignore
		if ( ! isset( $_POST['_surl_nonce'] ) || ! wp_verify_nonce( $_POST['_surl_nonce'], plugin_basename( __FILE__ ) ) ) {
			return;
		}

		// Don't try to save the data under autosave, ajax, or future post.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		};

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		};

		if ( defined( 'DOING_CRON' ) && DOING_CRON ) {
			return;
		};

		// Is the user allowed to edit the URL?
		if ( ! current_user_can( 'edit_posts' ) || 'surl' !== $post->post_type ) {
			return;
		}

		// phpcs:ignore
		$value = isset( $_POST[ $key ] ) ? $_POST[ $key ] : '';

		if ( $value ) {
			// Save/update.
			update_post_meta( $post->ID, $key, $value );
		} else {
			// Delete if blank.
			delete_post_meta( $post->ID, $key );
		}

	}
}
