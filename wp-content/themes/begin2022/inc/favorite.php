<?php
if ( ! defined( 'ABSPATH' ) ) exit;
class Begin_Favorite_Posts {
	private $table;
	private $db;
	public function __construct() {
		global $wpdb;
		$this->db = $wpdb;
		$this->table = $this->db->prefix . 'be_favorite';
		// add_action( 'after_switch_theme', array($this, 'favorite_data') );
		if (zm_get_option('shar_favorite')) {
			// add_action( 'optionsframework_after', array($this, 'favorite_data') );
			add_action( 'optionsframework_after_validate', array($this, 'favorite_data') );
		}
		add_action( 'wp_enqueue_scripts', array($this, 'enqueue_scripts') );
		add_shortcode( 'favorite-post-btn', array($this, 'button_shortcode') );
		add_shortcode( 'favorite-post', array($this, 'display_shortcode') );
		add_action( 'wp_ajax_keep_action', array($this, 'favorite_post') );
	}

	public static function init() {
		static $instance = false;
		if ( !$instance ) {
			$instance = new Begin_Favorite_Posts();
		}
		return $instance;
	}

	// 创建数据表
	public function favorite_data() {
		$sql = "CREATE TABLE if not exists {$this->table} (
		`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		`user_id` int(11) unsigned NOT NULL DEFAULT '0',
		`post_id` int(11) unsigned NOT NULL DEFAULT '0',
		`post_type` varchar(20) NOT NULL,PRIMARY KEY (`id`),
		KEY `user_id` (`user_id`),
		KEY `post_id` (`post_id`))
		ENGINE=MyISAM DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
		$this->db->query( $sql );
	}

	public function enqueue_scripts() {  
		wp_enqueue_script( 'favorite', get_template_directory_uri() . '/js/favorite-script.js', array('jquery'), version, true );
		$keep = 'var keep = ' . wp_json_encode( array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'nonce' => wp_create_nonce( 'keep_nonce' ), 'errorMessage' => __( '出错了', 'begin' ) ) ) . '; ';
		wp_add_inline_script('favorite', $keep, 'before');
	}

	// Ajax
	function favorite_post() {
		check_ajax_referer( 'keep_nonce', 'nonce' );
		if ( !is_user_logged_in() ) {
			wp_send_json_error();
		}
		$post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
		$user_id = get_current_user_id();
		if ( !$this->get_post_status( $post_id, $user_id ) ) {
			$this->insert_favorite( $post_id, $user_id );
			wp_send_json_success( '<span class="keep-favorite dah" title="'. sprintf(__( '取消收藏', 'begin' )) .'"></span>' );
		} else {
			$this->delete_favorite( $post_id, $user_id );
			wp_send_json_success( '<span class="keep-not-favorite dah" title="'. sprintf(__( '收藏', 'begin' )) .'"></span>' );
		}
	}

	function get_post_status( $post_id, $user_id ) {
		$sql = "SELECT post_id FROM {$this->table} WHERE post_id = %d AND user_id = %d";
		return $this->db->get_row( $this->db->prepare( $sql, $post_id, $user_id ) );
	}

	public function insert_favorite( $post_id, $user_id ) {
		$post_type = get_post_field( 'post_type', $post_id );
		return $this->db->insert(
			$this->table,
			array(
				'post_id' => $post_id,
				'post_type' => $post_type,
				'user_id' => $user_id,
			),
			array('%d', '%s', '%d')
		);
	}

	public function delete_favorite( $post_id, $user_id ) {
		$query = "DELETE FROM {$this->table} WHERE post_id = %d AND user_id = %d";
		return $this->db->query( $this->db->prepare( $query, $post_id, $user_id ) );
	}

	function get_favorites( $post_type = 'all', $user_id = 0, $count = 10, $offset = 0 ) {
		$where = 'WHERE user_id = ';
		$where .= $user_id ? $user_id : get_current_user_id();
		$where .= $post_type == 'all' ? '' : " AND post_type = '$post_type'";

		$sql = "SELECT post_id, post_type FROM {$this->table} $where GROUP BY post_id ORDER BY post_type LIMIT $offset, $count";
		$result = $this->db->get_results( $sql );
		return $result;
	}

	function link_button( $post_id ) {
		if ( !is_user_logged_in() ) {
			return;
		}
		$status = $this->get_post_status( $post_id, get_current_user_id() );
		?>
		<span class="favorite-box">
		<a class="keep-favorite-link zmy-btn-beshare bk" href="#" data-id="<?php echo $post_id; ?>">
			<?php if ( $status ) { ?>
				<span class="keep-favorite zmy-btn-favorite dah" data-hover="<?php _e( '取消收藏', 'begin' ); ?>"><span class="arrow-share"></span></span>
			<?php } else { ?>
				<span class="keep-not-favorite zmy-btn-favorite dah" data-hover="<?php _e( '收藏', 'begin' ); ?>"><span class="arrow-share"></span></span>
			<?php } ?>
		</a>
		</span>
		<?php
	}

	// 调用文章
	function display_favorites( $post_type = 'all', $user_id = false, $limit = 10, $show_remove = true ) {
		$posts = $this->get_favorites( $post_type, $user_id, $limit );
		echo '<ul>';
		if ( $posts ) {
			$remove_title = __( '取消收藏', 'begin' );
			$remove_link = ' <a href="#" data-id="%s" title="%s" class="keep-remove-favorite"><i class="be be-star"></i></a>';
			foreach ($posts as $item) {
				$extra = $show_remove ? sprintf( $remove_link, $item->post_id, $remove_title ) : '';
				printf( '<li>'.$extra.'<a href="%s" target="_blank">%s</a>%s</li>', get_permalink( $item->post_id ), get_the_title( $item->post_id ), '' );
			}
		} else {
			printf( '<li>%s</li>', __( '暂无收藏', 'begin' ) );
		}
		echo '</ul>';
	}

	// 短代码
	function button_shortcode( $atts ) {
		global $post;
		ob_start();
		$atts = extract( shortcode_atts( array('post_id' => 0), $atts ) );
		if ( !$post_id ) {
			$post_id = $post->ID;
		}
		$this->link_button( $post_id );
		return ob_get_clean();
	}

	function display_shortcode( $atts ) {
		global $post;
		ob_start();
		$atts = extract( shortcode_atts( array('user_id' => 0, 'count' => 100, 'post_type' => 'all', 'remove_link' => false), $atts ) );
		if ( !$user_id ) {
			$user_id = get_current_user_id();
		}
		$this->display_favorites( $post_type, $user_id, $count, $remove_link );
		return $html;
	}
}

$favorite_post = Begin_Favorite_Posts::init();

function keep_button( $post_id = null ) {
	global $post;
	if ( !$post_id ) {
		$post_id = $post->ID;
	}
	Begin_Favorite_Posts::init()->link_button( $post_id );
}

function like_button() {
	if (zm_get_option('shar_favorite')) {
		if ( is_user_logged_in() ) {
			keep_button();
		} else {
			echo '<span class="favorite-box show-layer" data-show-layer="login-layer"><a class="zmy-btn-beshare bk no-favorite dah" rel="external nofollow"><span class="like-number sharetip bz">'. sprintf(__( '登录收藏', 'begin' )) .'</span><div class="triangle-down"></div></a></span>';
		}
	}
}

class begin_favorite_post_widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'begin_favorite_post_widget',
			'description' => __( '显示用户个人收藏的文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('begin_favorite_post_widget', '我的收藏', $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args, EXTR_SKIP );
		if ( is_user_logged_in()) {
			$title_w = title_i_w();
			echo $before_widget;
			$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
			if ( $title ) {
				echo $before_title . $title_w . $title . $after_title;
			}
			$show_remove = !empty($instance['remove_link']) ? true : false;
			Begin_Favorite_Posts::init()->display_favorites( $instance['post_type'], false, $instance['limit'], $show_remove );
			echo $after_widget;
		}
	}
	function update( $new_instance, $old_instance ) {
		$updated_instance = $new_instance;
		return $updated_instance;
	}
	function form( $instance ) {
		$defaults = array(
			'title' => '我的收藏',
			'post_type' => 'all',
			'limit' => 10,
			'remove_link' => 'off'
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = esc_attr( $instance['title'] );
		$post_type = esc_attr( $instance['post_type'] );
		$limit = esc_attr( $instance['limit'] );
		$remove_link = $instance['remove_link'] == 'on' ? 'on' : 'off';
		$post_types = get_post_types( array( 'public' => true ) );
		?>
		<p>
			<label><?php _e( '标题：', 'begin' ); ?> </label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label><?php _e( '文章类型：', 'begin' ) ?></label>
			<select id="<?php echo $this->get_field_id( 'post_type' ); ?>" name="<?php echo $this->get_field_name( 'post_type' ); ?>" >
				<option value="all" <?php selected( $post_type, '全部' ) ?>><?php _e( '全部', 'begin' ) ?></option>
				<?php foreach ($post_types as $pt) { ?>
					<option value="<?php echo $pt; ?>" <?php selected( $post_type, $pt ) ?>><?php echo $pt; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
			<input class="number-text" id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" type="number" step="1" min="1" value="<?php echo $instance['limit']; ?>" size="3" />
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $remove_link, 'on' ); ?> id="<?php echo $this->get_field_id( 'remove_link' ); ?>" name="<?php echo $this->get_field_name( 'remove_link' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'remove_link' ); ?>"><?php _e( '显示删除按钮', 'begin' ) ?></label>
		</p>
		<?php
	}
}

add_action( 'widgets_init', 'pavorite_post_init' );
function pavorite_post_init() {
	register_widget( 'begin_favorite_post_widget' );
}