<?php
class Linksclick {
	// Constructor
	public function __construct() {
		//register_activation_hook( __FILE__, 'flush_rewrite_rules' );
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'manage_posts_custom_column', array( $this, 'columns_data' ) );
		add_filter( 'manage_edit-surl_columns', array( $this, 'columns_filter' ) );
		add_filter( 'post_updated_messages', array( $this, 'updated_message' ) );
		add_action( 'admin_menu', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'meta_box_save' ), 1, 2 );
		add_action( 'template_redirect', array( $this, 'count_and_redirect' ) );
	}

	public function register_post_type() {
		$slug = 'surl';
		$labels = array(
			'name'               => '短链接',
			'singular_name'      => '链接',
			'add_new'            => '添加链接',
			'add_new_item'       => '添加新链接',
			'edit'               => '编辑',
			'edit_item'          => '编辑链接',
			'new_item'           => '新的链接',
			'view'               => '查看链接',
			'view_item'          => '查看链接',
			'search_items'       => '搜索链接',
			'not_found'          => '你还没有发表链接',
			'not_found_in_trash' => '回收站中没有链接',
			'messages'           => array(
				 0 => '', // Unused. Messages start at index 1.
				 1 => '链接更新。<a href="%s">查看链接</a>',
				 2 => '自定义字段已更新。',
				 3 => '自定义字段已删除。',
				 4 => '链接已更新。',
				/* translators: %s: date and time of the revision */
				 5 => isset( $_GET['revision'] ) ? sprintf( '文章已从 %s 恢复到修订版本', wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
				 6 => '链接已更新。 <a href="%s">查看链接</a>',
				 7 => '链接已保存',
				 8 => '链接已提交。',
				 9 => '链接定时',
				10 => '链接草稿已更新。',
			),
		);

		$labels = apply_filters( 'simple_urls_cpt_labels', $labels );
		$rewrite_slug = apply_filters( 'simple_urls_slug', 'go' );
		register_post_type( $slug,
			array(
				'labels'        => $labels,
				'public'        => true,
				'query_var'     => true,
				'menu_position' => 57,
				'menu_icon'     => 'dashicons-editor-unlink',
				'supports'      => array( 'title', 'custom-fields' ),
				'rewrite'       => array( 'slug' => $rewrite_slug, 'with_front' => false ),
			)
		);
	}

	public function columns_filter( $columns ) {
		$columns = array(
			'cb'        => '<input type="checkbox" />',
			'title'     => '标题',
			'url'       => '重定向链接',
			'permalink' => '自定义链接',
			'clicks'    => '点击',
			'id'        => 'ID',
		);
		return $columns;
	}

	public function columns_data( $column ) {
		global $post;
		$url   = get_post_meta( get_the_ID(), '_surl_redirect', true );
		$count = get_post_meta( get_the_ID(), 'surl_count', true );
		if ( 'url' == $column ) {
			echo make_clickable( esc_url( $url ? $url : '' ) );
		}
		elseif ( 'permalink' == $column ) {
			echo make_clickable( get_permalink() );
		}
		elseif ( 'clicks' == $column ) {
			echo esc_html( $count ? $count : 0 );
		}
		if ( 'id' == $column ) {
			echo url_to_postid(get_permalink() );
		}
	}

	public function updated_message( $messages ) {
		$surl_object = get_post_type_object( 'surl' );
		$messages['surl'] = $surl_object->labels->messages;
		if ( $permalink = get_permalink() ) {
			foreach ( $messages['surl'] as $id => $message ) {
				$messages['surl'][ $id ] = sprintf( $message, $permalink );
			}
		}
		return $messages;
	}

	public function add_meta_box() {
		add_meta_box( 'surl', '链接信息', array( $this, 'meta_box' ), 'surl', 'normal', 'high' );
	}

	public function meta_box() {
		global $post;
		printf( '<input type="hidden" name="_surl_nonce" value="%s" />', wp_create_nonce( plugin_basename(__FILE__) ) );
		printf( '<p><label for="%s">%s</label></p>', '_surl_redirect', '重定向链接');
		printf( '<p><input style="%s" type="text" name="%s" id="%s" value="%s" /></p>', 'width: 99%;', '_surl_redirect', '_surl_redirect', esc_attr( get_post_meta( get_the_ID(), '_surl_redirect', true ) ) );
		printf( '<p><label for="%s">%s</label></p>', '', '重定向后，实际的访问链接' );
		$count = isset( $post->ID ) ? get_post_meta(get_the_ID(), 'surl_count', true) : 0;
		echo '<p>' . sprintf( '该链接已被点击 %d 次', esc_attr( $count ) ) . '</p>';
	}

	public function meta_box_save( $post_id, $post ) {
		$key = '_surl_redirect';
		if ( ! isset( $_POST['_surl_nonce'] ) || ! wp_verify_nonce( $_POST['_surl_nonce'], plugin_basename( __FILE__ ) ) ) {
			return;
		}

		// don't try to save the data under autosave, ajax, or future post.
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
		if ( defined('DOING_AJAX') && DOING_AJAX ) return;
		if ( defined('DOING_CRON') && DOING_CRON ) return;
		// is the user allowed to edit the URL?
		if ( ! current_user_can( 'edit_posts' ) || 'surl' != $post->post_type )
			return;
		$value = isset( $_POST[ $key ] ) ? $_POST[ $key ] : '';
		if ( $value ) {
			// save/update
			update_post_meta( $post->ID, $key, $value );
		} else {
			// delete if blank
			delete_post_meta( $post->ID, $key );
		}
	}

	public function count_and_redirect() {
		if ( ! is_singular( 'surl' ) ) {
			return;
		}
		global $wp_query;
		// Update the count
		$count = isset( $wp_query->post->surl_count ) ? (int) $wp_query->post->surl_count : 0;
		if ( ! current_user_can( 'manage_options' ) ){
			update_post_meta( $wp_query->post->ID, 'surl_count', $count + 1 );
		}
		// Handle the redirect
		$redirect = isset( $wp_query->post->ID ) ? get_post_meta( $wp_query->post->ID, '_surl_redirect', true ) : '';
		// Filter the redirect URL.
		$redirect = apply_filters( 'simple_urls_redirect_url', $redirect, $count );
		 // Action hook that fires before the redirect.
		do_action( 'simple_urls_redirect', $redirect, $count );
		if ( ! empty( $redirect ) ) {
			wp_redirect( esc_url_raw( $redirect ), 301 );
			exit;
		} else {
			wp_redirect( home_url(), 302 );
			exit;
		}
	}
}

new Linksclick;