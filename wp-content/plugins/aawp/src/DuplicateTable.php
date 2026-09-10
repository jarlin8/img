<?php
/**
 * Cloning functionality of the comparison tables.
 *
 * @since 3.18
 */

namespace AAWP;

/**
 * DuplicateTable.
 */
class DuplicateTable {

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
		add_filter( 'post_row_actions', [ $this, 'duplicate_action_link' ], 10, 2 );
		add_action( 'admin_action_duplicate_aawp_table', [ $this, 'duplicate_table' ] );
		add_action( 'admin_notices', [ $this, 'display_notice' ] );
	}

	/**
	 * Add a "Duplicate" action link.
	 *
	 * @param  array  $actions Current Actions such as Edit, Trash.
	 * @param  Object $post    Post Object.
	 *
	 * @since 3.18
	 *
	 * @return array Actions including the duplicate table.
	 */
	public function duplicate_action_link( $actions, $post ) {

		if ( 'aawp_table' !== $post->post_type || ! current_user_can( 'edit_posts' ) ) {
			return $actions;
		}

		$actions['duplicate'] = sprintf(
			'<a href="%s" title="%s">%s</a>',
			esc_url(
				wp_nonce_url(
					add_query_arg(
						[
							'post'   => $post->ID,
							'action' => 'duplicate_aawp_table',
						],
						'post.php'
					),
					'aawp_duplicate_table'
				)
			),
			esc_attr__( 'Duplicate this table', 'aawp' ),
			esc_html__( 'Duplicate', 'aawp' )
		);

		return $actions;
	}

	/**
	 * Functionality of duplicating table.
	 *
	 * @since 3.18
	 */
	public function duplicate_table() {

		// Check legit.
		if ( empty( $_REQUEST['post'] ) || empty( $_REQUEST['action'] ) || 'duplicate_aawp_table' !== $_REQUEST['action'] ) {
			return;
		}

		// Verify nonce.
		if ( empty( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['_wpnonce'] ), 'aawp_duplicate_table' ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash
			return;
		}

		// Sanitize Post ID.
		$post_id = absint( $_REQUEST['post'] );

		// Get Post Object.
		$post = (array) get_post( $post_id );

		$post['post_title'] = $post['post_title'] . ' - Copy';

		// Unset post ID to create new ID.
		unset( $post['ID'] );

		do_action( 'aawp_table_before_duplicate_post', $post );

		$dupe_post_id = wp_insert_post( $post );

		$this->duplicate_post_meta( absint( $dupe_post_id ), $post_id );

		set_transient( 'aawp_duplicate_table_notice', true, 5 );

		wp_safe_redirect(
			add_query_arg(
				[
					'post_type'  => 'aawp_table',
					'duplicated' => true,
				],
				esc_url( admin_url( 'edit.php' ) )
			)
		);
		exit;
	}

	/**
	 * Copy post meta values from the post.
	 *
	 * @param  int $dupe_post_id Post ID of duplicated post.
	 * @param  int $post_id      Post ID of original post.
	 *
	 * @since 3.18
	 *
	 * @return void.
	 */
	public function duplicate_post_meta( $dupe_post_id, $post_id ) {

		$post_meta = (array) get_post_meta( $post_id );

		foreach ( $post_meta as $meta_key => $meta_values ) {

			foreach ( $meta_values as $meta_value ) {
				add_post_meta( $dupe_post_id, $meta_key, maybe_unserialize( $meta_value ) );
			}
		}

		do_action( 'aawp_table_after_duplicate_post', $dupe_post_id, $post_id );
	}

	/**
	 * Display admin notices when the table is duplicated.
	 *
	 * @since 3.18
	 *
	 * @return void.
	 */
	public function display_notice() {
		$screen = get_current_screen();

		if ( empty( $screen->id ) || 'edit-aawp_table' !== $screen->id ) {
			return;
		}

		if ( isset( $_GET['duplicated'] ) && '1' === $_GET['duplicated'] && get_transient( 'aawp_duplicate_table_notice' ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Table Duplicated.', 'aawp' ) . '</p></div>';

			delete_transient( 'aawp_duplicate_table_notice' );
		}
	}
}
