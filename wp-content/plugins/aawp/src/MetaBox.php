<?php

namespace AAWP;

/**
 * MetaBox.
 */
class MetaBox {

	/**
	 * Initialize
	 *
	 * @since 3.18
	 */
	public function init() {

		// Bail if it's not an admin page.
		if ( ! is_admin() ) {
			return;
		}

		// Hooks.
		add_action( 'load-post.php', [ $this, 'init_meta_box' ] );
		add_action( 'load-post-new.php', [ $this, 'init_meta_box' ] );
	}

	/**
	 * Init MetaBox.
	 *
	 * @since 3.18
	 *
	 * @return void.
	 */
	public function init_meta_box() {

		add_action( 'add_meta_boxes', [ $this, 'add_meta_box' ] );
		add_action( 'save_post', [ $this, 'save_meta_box' ], 10, 2 );
	}

	/**
	 * Add Meta box.
	 *
	 * @since 3.18
	 *
	 * @return void.
	 */
	public function add_meta_box() {

		add_meta_box(
			'aawp',
			'<img src="' . esc_url( AAWP_PLUGIN_URL . 'assets/img/icon.svg' ) . '"><span class="label">' . esc_html__( 'AAWP', 'aawp' ) . '</span>',
			[ $this, 'render_meta_box' ],
			[ 'post', 'page', 'aawp_table' ],
			'side',
			'low'
		);
	}

	/**
	 * Render meta box.
	 *
	 * @param object $post A post object.
	 *
	 * @since 3.18
	 *
	 * @return void.
	 */
	public function render_meta_box( $post ) {

		$post_id     = isset( $post->ID ) ? absint( $post->ID ) : 0;
		$tracking_id = get_post_meta( $post_id, 'aawp_tracking_id', true );

		ob_start();
		?>
			<?php do_action( 'aawp_sidebar_metabox_init', $post ); ?>

			<?php if ( isset( $post->post_type ) && 'aawp_table' !== $post->post_type ) : ?>

				<tr valign="top" class="aawp-tracking-id">
					<th scope="row"><p><?php echo esc_html__( 'Tracking ID', 'aawp' ); ?></p></th>
				<td>
				<input type="text" id="aawp-tracking-id" name="aawp_tracking_id" value="<?php echo esc_attr( $tracking_id ); ?>" style="width:100%" /><br/>
				<p class="components-form-token-field__help"> <?php echo esc_html__( 'Replacing the tracking id which will be used for affiliate links.', 'aawp' ); ?></p>
				</td>
				</tr>

			<?php endif; ?>

			<?php do_action( 'aawp_sidebar_metabox' ); ?>
		<?php

		wp_nonce_field( 'aawp_metabox', 'aawp_metabox_nonce' );

		echo ob_get_clean(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Save meta box.
	 *
	 * @param  string $post_id Post ID.
	 *
	 * @return void.
	 */
	public function save_meta_box( $post_id ) {

		// Check if nonce exists.
		if ( empty( $_POST['aawp_metabox_nonce'] ) ) {
			return;
		}

		// Verify nonce.
		if ( ! wp_verify_nonce( sanitize_key( $_POST['aawp_metabox_nonce'] ), 'aawp_metabox' ) ) {
			return;
		}

		// Check if user has permissions to save data.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$tracking_id = isset( $_POST['aawp_tracking_id'] ) ? sanitize_text_field( wp_unslash( $_POST['aawp_tracking_id'] ) ) : '';

		update_post_meta( absint( $post_id ), 'aawp_tracking_id', $tracking_id );
	}
}
