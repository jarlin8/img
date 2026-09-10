<?php
class BE_Sticky_Comments {
	private static $instance;
	private static $actions;
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new BE_Sticky_Comments;
			self::$instance->init();
			do_action( 'sticky_comments_loaded' );
		}
		return self::$instance;
	}

	/** 过滤器和动作 **/
	private function init() {

		self::$actions = array(
			'feature'   => '<span class="be be-thumbs-up"></span>',
			'unfeature' => '<span class="be be-thumbs-up"></span>',
			'bury'      => '<span class="be be-favorite"></span>',
			'unbury'    => '<span class="be be-favorite"></span>'
		);

		/* Backend */
		add_action( 'edit_comment',             array( $this, 'save_meta_box_postdata' ) );
		add_action( 'admin_menu',               array( $this, 'add_meta_box'           ) );
		add_action( 'wp_ajax_feature_comments', array( $this, 'ajax'                   ) );
		add_filter( 'comment_text',             array( $this, 'comment_text'           ), 10, 3 );
		add_filter( 'comment_row_actions',      array( $this, 'comment_row_actions'    ) );
		add_action( 'wp_print_scripts',         array( $this, 'print_scripts'          ) );
		add_action( 'admin_print_scripts',      array( $this, 'print_scripts'          ) );
		add_filter( 'comment_class',            array( $this, 'comment_class'          ) );
	}

	// Script
	function print_scripts() {
		if ( current_user_can( 'moderate_comments' ) ) {
			wp_enqueue_script( 'sticky_comments', get_template_directory_uri() . "/js/sticky-comments.js", array( 'jquery' ), version, true );
			wp_localize_script( 'sticky_comments', 'sticky_comments', array(
				'ajax_url' => admin_url( 'admin-ajax.php' )
			) );
		}
	}

	function ajax() {
		if ( ! isset( $_POST['do'] ) ) die;
		$action = $_POST['do'];
		$actions = array_keys( self::$actions );
		if ( in_array( $action, $actions ) ) {
			$comment_id = absint( $_POST['comment_id'] );
			$comment    = get_comment( $comment_id );

			if ( ! $comment ) {
				die;
			}

			if ( ! current_user_can( 'edit_comment', $comment_id ) ) {
				die;
			}

			if ( ! wp_verify_nonce( $_POST['nonce'], 'sticky_comments' ) ) {
				die;
			}

			switch ( $action ) {

				case 'feature':
					update_comment_meta( $comment_id, 'comment_sticky', '1' );
					break;

				case 'unfeature':
					update_comment_meta( $comment_id, 'comment_sticky', '0' );
					break;

				case 'bury':
					update_comment_meta( $comment_id, 'comment_buried', '1');
					break;

				case 'unbury':
					update_comment_meta( $comment_id, 'comment_buried', '0');
					break;

				die( wp_create_nonce( 'sticky_comments' ) );

			}
		}
		die;
	}

	function comment_text( $comment_text ) {
		if( is_admin() || ! current_user_can( 'moderate_comments' ) ) {
			return $comment_text;
		}

		global $comment;

		$comment_id = $comment->comment_ID;
		$data_id    = ' data-comment_id=' . $comment_id;

		$current_status = implode( ' ', self::comment_class() );
		$output = '<div class="feature-burry-comments">';
		foreach( self::$actions as $action => $label ) {
			$output .= "<a class='feature-comments {$current_status} {$action}' data-do='{$action}' {$data_id} data-nonce='" . wp_create_nonce( "sticky_comments" ) . "'>{$label}</a> "; }
		$output .= '</div>';

		return $comment_text . $output;
	}

	// 列表按钮
	function comment_row_actions( $actions ) {

		global $comment, $post, $approve_nonce;

		$comment_id = $comment->comment_ID;

		$data_id = ' data-comment_id=' . $comment->comment_ID;

		$current_status = implode( ' ', self::comment_class() );

		$o = '';
		$o .= "<a data-do='unfeature' {$data_id} data-nonce='" . wp_create_nonce( 'sticky_comments' ) . "' class='feature-comments unfeature {$current_status} dim:the-comment-list:comment-{$comment->comment_ID}:unsticky:e7e7d3:e7e7d3:new=unsticky vim-u'>取消置顶</a>";
		$o .= "<a data-do='feature' {$data_id} data-nonce='" . wp_create_nonce( 'sticky_comments' ) . "' class='feature-comments feature {$current_status} dim:the-comment-list:comment-{$comment->comment_ID}:unsticky:e7e7d3:e7e7d3:new=sticky vim-a'>置顶</a>";
		$o .= ' | ';
		$o .= "<a data-do='unbury' {$data_id} data-nonce='" . wp_create_nonce( 'sticky_comments' ) . "' class='feature-comments unbury {$current_status} dim:the-comment-list:comment-{$comment->comment_ID}:unburied:e7e7d3:e7e7d3:new=unburied vim-u'>熄灭</a>";
		$o .= "<a data-do='bury' {$data_id}  data-nonce='" . wp_create_nonce( 'sticky_comments' ) . "' class='feature-comments bury {$current_status} dim:the-comment-list:comment-{$comment->comment_ID}:unburied:e7e7d3:e7e7d3:new=buried vim-a'>点亮</a>";
		$o = "<span class='$current_status'>$o</span>";

		$actions['feature_comments'] = $o;

		return $actions;
	}

	// 编辑面板
	function add_meta_box() {
		add_meta_box( 'comment_meta_box', '置顶评论', array( $this, 'comment_meta_box' ), 'comment', 'normal' );
	}

	function save_meta_box_postdata( $comment_id ) {

		if ( ! wp_verify_nonce( $_POST['sticky_comments_nonce'], 'sticky_comments_nonce' ) ) {
			return;
		}

		if ( !current_user_can( 'moderate_comments', $comment_id ) ) {
			comment_footer_die( '你无权编辑评论。');
		}

		update_comment_meta( $comment_id, 'comment_sticky', isset( $_POST['comment_sticky'] ) ? '1' : '0' );
		update_comment_meta( $comment_id, 'comment_buried',   isset( $_POST['comment_buried'] )   ? '1' : '0' );
	}

	function comment_meta_box() {

		global $comment;
		$comment_id = $comment->comment_ID;
		echo '<p>';
		echo wp_nonce_field( 'sticky_comments_nonce', 'sticky_comments_nonce' );
		echo '<input id = "sticky" type="checkbox" name="comment_sticky" value="true"' . checked( true, self::is_comment_sticky( $comment_id ), false ) . '/>';
		echo ' <label for="sticky">置顶</label>&nbsp;';
		echo '<input id = "buried" type="checkbox" name="comment_buried" value="true"' . checked( true, self::is_comment_buried( $comment_id ), false ) . '/>';
		echo ' <label for="buried">点亮</label>';
		echo '</p>';
	}

	function comment_class( $classes = array() ) {
		global $comment;

		$comment_id = $comment->comment_ID;

		if ( self::is_comment_sticky( $comment_id ) ) {
			$classes[] = 'sticky';
		}

		if ( self::is_comment_buried( $comment_id ) ) {
			$classes [] = 'buried';
		}

		return $classes;
	}

	private function is_comment_sticky( $comment_id ) {
		if ( '1' == get_comment_meta( $comment_id, 'comment_sticky', true ) ) {
			return 1;
		}
		return 0;
	}

	private static function is_comment_buried( $comment_id ) {
		if ( '1' == get_comment_meta( $comment_id, 'comment_buried', true ) ) {
			return 1;
		}
		return 0;
	}
}

function be_sticky_comments_load() {
	return BE_Sticky_Comments::instance();
}

be_sticky_comments_load();