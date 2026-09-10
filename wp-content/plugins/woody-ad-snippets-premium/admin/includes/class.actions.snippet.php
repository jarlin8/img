<?php
/**
 * Actions snippet
 *
 * @author        Webcraftic <wordpress.webraftic@gmail.com>
 * @copyright (c) 11.02.2019, Webcraftic
 * @version       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WASP_Actions_Snippet
 */
class WASP_Actions_Snippet {

	/**
	 * WASP_Actions_Snippet constructor.
	 */
	public function __construct() {
	}

	/**
	 * Register hooks
	 */
	public function register_hooks() {
		add_filter( 'post_row_actions', array( $this, 'post_row_actions' ), 10, 2 );
		add_filter( 'bulk_actions-edit-' . WINP_SNIPPETS_POST_TYPE, array( $this, 'action_bulk_edit_post' ) );
		add_filter(
			'handle_bulk_actions-edit-' . WINP_SNIPPETS_POST_TYPE,
			array(
				$this,
				'handle_action_bulk_edit_post',
			),
			10,
			3
		);

		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}

	/**
	 * Get clone url
	 *
	 * @param $post_id
	 *
	 * @return string
	 */
	private function get_clone_url( $post_id ) {
		$url = admin_url( 'post.php?post=' . $post_id );

		return add_query_arg(
			array(
				'action' => 'clone',
				'nonce'  => wp_create_nonce( 'wasp_clone_snippet' ),
			),
			$url
		);
	}

	/**
	 * Action post_row_actions
	 *
	 * @param $actions
	 * @param $post
	 *
	 * @return mixed
	 */
	public function post_row_actions( $actions, $post ) {
		if ( WINP_SNIPPETS_POST_TYPE == $post->post_type ) {
			$clone_link = $this->get_clone_url( $post->ID );

			if ( isset( $actions['trash'] ) ) {
				$trash = $actions['trash'];
				unset( $actions['trash'] );
			}

			$actions['clone'] = sprintf(
				'<a href="%1$s">%2$s</a>',
				esc_url( $clone_link ),
				esc_html( __( 'Clone', 'insert-php' ) )
			);

			if ( isset( $trash ) ) {
				$actions['trash'] = $trash;
			}
		}

		return $actions;
	}

	/**
	 * Action bulk_edit_post
	 *
	 * @param $bulk_actions
	 *
	 * @return mixed
	 */
	public function action_bulk_edit_post( $bulk_actions ) {
		$bulk_actions['clone'] = __( 'Clone', 'insert-php' );

		return $bulk_actions;
	}

	/**
	 * Handle bulk actions
	 *
	 * @param $redirect_to
	 * @param $doaction
	 * @param $post_ids
	 *
	 * @return mixed
	 */
	public function handle_action_bulk_edit_post( $redirect_to, $doaction, $post_ids ) {
		$actions = array(
			'clone' => 1,
		);

		if ( ! isset( $actions[ $doaction ] ) ) {
			return $redirect_to;
		}

		if ( count( $post_ids ) ) {
			switch ( $doaction ) {
				case 'clone':
					$nonce = WINP_Plugin::app()->request->get( '_wpnonce', '' );
					if ( wp_verify_nonce( $nonce, 'bulk-posts' ) ) {
						$redirect_to = $this->clone_snippets( $post_ids );
					}
					break;
			}
		}

		return $redirect_to;
	}

	/**
	 * Update taxonomy tags
	 *
	 * @param $snippet_id
	 * @param $tags
	 */
	private function update_taxonomy_tags( $snippet_id, $tags ) {
		if ( ! empty( $tags ) ) {
			foreach ( $tags as $tag_slug ) {
				$term = get_term_by( 'slug', $tag_slug, WINP_SNIPPETS_TAXONOMY );
				if ( $term ) {
					wp_set_post_terms( $snippet_id, array( $term->term_id ), WINP_SNIPPETS_TAXONOMY, true );
				}
			}
		}
	}

	/**
	 * Get taxonomy tags
	 *
	 * @param $snippet_id
	 *
	 * @return array
	 */
	private function get_taxonomy_tags( $snippet_id ) {
		$tags = array();

		if ( $snippet_id ) {
			return wp_get_post_terms( $snippet_id, WINP_SNIPPETS_TAXONOMY, array( 'fields' => 'slugs' ) );
		}

		return $tags;
	}

	/**
	 * Get post meta
	 *
	 * @param $post_id
	 * @param $meta_name
	 *
	 * @return mixed
	 */
	private function get_meta( $post_id, $meta_name ) {
		return get_post_meta( $post_id, WINP_Plugin::app()->getPrefix() . $meta_name, true );
	}

	/**
	 * Prepare data
	 *
	 * @param $ids
	 *
	 * @return array
	 */
	private function prepare_data( $ids ) {
		$snippets = array();

		if ( count( $ids ) ) {
			foreach ( $ids as $id ) {
				$post       = get_post( $id );
				$snippets[] = array(
					'name'            => $post->post_name,
					'title'           => $post->post_title,
					'content'         => $post->post_content,
					'location'        => $this->get_meta( $id, 'snippet_location' ),
					'type'            => $this->get_meta( $id, 'snippet_type' ),
					'code'            => $this->get_meta( $id, 'snippet_code' ),
					'filters'         => $this->get_meta( $id, 'snippet_filters' ),
					'changed_filters' => $this->get_meta( $id, 'changed_filters' ),
					'scope'           => $this->get_meta( $id, 'snippet_scope' ),
					'description'     => $this->get_meta( $id, 'snippet_description' ),
					'attributes'      => $this->get_meta( $id, 'snippet_tags' ),
					'tags'            => $this->get_taxonomy_tags( $id ),
				);
			}
		}

		return $snippets;
	}

	/**
	 * Clone snippets
	 *
	 * @param $ids
	 *
	 * @return string
	 */
	public function clone_snippets( $ids ) {
		$snippets = $this->prepare_data( $ids );

		if ( $snippets ) {
			foreach ( $snippets as $snippet ) {
				$data = array(
					'post_title'   => $snippet['title'] . ' copy',
					'post_content' => $snippet['content'],
					'post_status'  => 'publish',
					'post_type'    => WINP_SNIPPETS_POST_TYPE,
				);

				$snippet['id'] = wp_insert_post( $data );

				update_post_meta( $snippet['id'], WINP_Plugin::app()->getPrefix() . 'snippet_location', $snippet['location'] );
				update_post_meta( $snippet['id'], WINP_Plugin::app()->getPrefix() . 'snippet_type', $snippet['type'] );
				update_post_meta( $snippet['id'], WINP_Plugin::app()->getPrefix() . 'snippet_code', $snippet['code'] );
				update_post_meta( $snippet['id'], WINP_Plugin::app()->getPrefix() . 'snippet_filters', $snippet['filters'] );
				update_post_meta( $snippet['id'], WINP_Plugin::app()->getPrefix() . 'changed_filters', $snippet['changed_filters'] );
				update_post_meta( $snippet['id'], WINP_Plugin::app()->getPrefix() . 'snippet_scope', $snippet['scope'] );
				update_post_meta( $snippet['id'], WINP_Plugin::app()->getPrefix() . 'snippet_description', $snippet['description'] );
				update_post_meta( $snippet['id'], WINP_Plugin::app()->getPrefix() . 'snippet_tags', $snippet['attributes'] );
				update_post_meta( $snippet['id'], WINP_Plugin::app()->getPrefix() . 'snippet_custom_name', '' );
				update_post_meta( $snippet['id'], WINP_Plugin::app()->getPrefix() . 'snippet_activate', 0 );
				$this->update_taxonomy_tags( $snippet['id'], $snippet['tags'] );
			}
		}

		return admin_url( 'edit.php?post_type=' . WINP_SNIPPETS_POST_TYPE );
	}

	/**
	 * adminInit
	 */
	public function admin_init() {
		$post   = WINP_Plugin::app()->request->get( 'post', 0 );
		$action = WINP_Plugin::app()->request->get( 'action', '', 'sanitize_key' );

		if ( ! empty( $action ) && ! empty( $post ) ) {
			$ids = is_array( $post ) ? $post : array( absint( $post ) );

			switch ( $action ) {
				case 'clone':
					$nonce = WINP_Plugin::app()->request->get( 'nonce', '' );
					if ( wp_verify_nonce( $nonce, 'wasp_clone_snippet' ) ) {
						$redirect_to = $this->clone_snippets( $ids );
						wp_redirect( $redirect_to );
						exit();
					}
					break;
				default:
					return;
			}
		}
	}

	/**
	 * Set HTTP headers
	 *
	 * @param $filename
	 * @param $mime_type
	 * @param $zipname
	 */
	private function set_headers( $filename, $mime_type, $zipname = '' ) {
		header( 'Content-Disposition: attachment; filename=' . sanitize_file_name( $filename ) );

		if ( '' !== $mime_type ) {
			header( "Content-Type: $mime_type; charset=" . get_bloginfo( 'charset' ) );

			if ( 'application/zip' == $mime_type ) {
				header( 'Content-Length: ' . filesize( $zipname ) );
			}
		}
	}

	/**
	 * Get file name
	 *
	 * @param string $format
	 * @param array $ids
	 * @param array $snippets
	 *
	 * @return string
	 */
	public function get_filename( $format, $ids, $snippets ) {
		$snippets = empty( $snippets ) ? $this->prepare_data( $ids ) : $snippets;

		/* Build the export filename */
		if ( 1 == count( $ids ) ) {
			$name  = $snippets[0]['title'];
			$title = strtolower( $name );
		} else {
			/* Otherwise, use the site name as set in Settings > General */
			$title = strtolower( get_bloginfo( 'name' ) );
		}

		$filename = "{$title}.php-code-snippets.{$format}";

		return $filename;
	}

	/**
	 * Export snippets in JSON format
	 *
	 * @param array $ids
	 * @param bool $zip
	 */
	public function export_snippets( $ids, $zip = false ) {
		$snippets = $this->prepare_data( $ids );

		$format   = $zip ? 'zip' : 'json';
		$filename = $this->get_filename( 'json', $ids, $snippets );

		$data = array(
			'generator'    => 'PHP Code Snippets v' . WINP_PLUGIN_VERSION,
			'date_created' => date( 'Y-m-d H:i' ),
			'snippets'     => $snippets,
		);

		if ( $zip ) {
			$data = wp_json_encode( $data, 0 );

			$zipname = str_replace( '.json', '.zip', $filename );

			$upload_dir = wp_get_upload_dir();
			$filepath   = $upload_dir['path'] . '/' . $filename;
			$zippath    = $upload_dir['path'] . '/' . $zipname;
			file_put_contents( $filepath, $data );

			$zip = new ZipArchive;
			if (
				true === $zip->open( $zippath, ZipArchive::CREATE | ZipArchive::OVERWRITE )
				&& true === $zip->addFile( $filepath, $filename )
			) {
				$zip->close();
				unlink( $filepath );

				$this->set_headers( $zipname, 'application/' . $format, $zippath );
				readfile( $zippath );
			}
		} else {
			$this->set_headers( $filename, 'application/' . $format );
			echo wp_json_encode( $data, 0 );
		}

		exit;
	}

}