<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BeAudioIgniter {
	protected static $instance = null;
	public $sanitizer = null;
	public $post_type = 'ai_playlist';

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __construct() {}

	public function audio_setup() {
		require get_template_directory() . '/inc/audio-sanitizer.php';
		$this->sanitizer = new BeAudioIgniter_Sanitizer();
		$this->init();
		$this->admin_init();
		$this->frontend_init();
		do_action( 'audioigniter_loaded' );
	}

	protected function init() {
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		add_action( 'init', array( $this, 'register_scripts' ) );
		add_action( 'init', array( $this, 'register_playlist_endpoint' ) );
		add_action( 'init', array( $this, 'register_shortcodes' ) );
		add_action( 'widgets_init', array( $this, 'register_widgets' ) );
		add_filter( 'audioigniter_get_playlist_data_attributes_array', array( $this, 'filter_get_playlist_data_attributes_array' ), 10, 2 );
		add_action( 'wp_footer', array( $this, 'global_footer_player' ) );
		do_action( 'audioigniter_init' );
	}

	protected function admin_init() {
		if ( ! is_admin() ) {
			return;
		}

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_post' ) );

		add_filter( "manage_{$this->post_type}_posts_columns", array( $this, 'filter_posts_columns' ) );
		add_action( "manage_{$this->post_type}_posts_custom_column", array( $this, 'add_custom_columns' ), 10, 2 );
		add_action( 'save_post', array( $this, 'invalidate_transient_global_footer_player_id' ) );
		add_action( 'delete_post', array( $this, 'invalidate_transient_global_footer_player_id' ) );
		do_action( 'audioigniter_admin_init' );
	}

	// 分类
	public function register_taxonomies() {
		$labels = array(
			'name'              => esc_html_x( '音频分类', 'taxonomy general name', 'begin' ),
			'singular_name'     => esc_html_x( '音频分类', 'taxonomy singular name', 'begin' ),
			'search_items'      => esc_html__( '搜索分类', 'begin' ),
			'all_items'         => esc_html__( '全部分类', 'begin' ),
			'parent_item'       => esc_html__( '父级分类', 'begin' ),
			'parent_item_colon' => esc_html__( '父级:', 'begin' ),
			'edit_item'         => esc_html__( '编辑分类', 'begin' ),
			'update_item'       => esc_html__( '更新分类', 'begin' ),
			'add_new_item'      => esc_html__( '新增分类', 'begin' ),
			'new_item_name'     => esc_html__( '分类名称', 'begin' ),
			'menu_name'         => esc_html__( '音频分类', 'begin' ),
			'view_item'         => esc_html__( '查看音频分类', 'begin' ),
			'popular_items'     => esc_html__( '热门音频分类', 'begin' ),
		);

		register_taxonomy( 'ai_playlist_category', array( BeAudioIgniter()->post_type ), array(
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_menu'      => true,
			'show_in_nav_menus' => false,
			'rewrite'           => array( 'slug' => _x( '音频分类', 'taxonomy slug', 'begin' ) ),
			'description'       => esc_html__( '用于音频分类', 'begin' ),
		) );
	}

	public function get_global_footer_player_id() {
		$transient = get_transient( 'audioigniter_global_footer_player_id' );

		if ( false === $transient ) {
			$q = new WP_Query( array(
				'post_type'      => BeAudioIgniter()->post_type,
				'posts_per_page' => 1,
				'meta_query'     => array(
					array(
						'key'   => '_audioigniter_player_type',
						'value' => 'global-footer',
					),
				),
			) );

			$transient = array(
				'id' => false,
			);

			if ( $q->have_posts() ) {
				while ( $q->have_posts() ) {
					$q->the_post();
					$transient['id'] = get_the_ID();
					break;
				}
				wp_reset_postdata();
			}

			set_transient( 'audioigniter_global_footer_player_id', $transient );
		}

		return $transient['id'];
	}

	public function global_footer_player() {
		$id = $this->get_global_footer_player_id();

		if ( empty( $id ) || ! BeAudioIgniter()->is_playlist( $id ) ) {
			return;
		}

		$post = get_post( $id );

		$params = apply_filters( 'audioigniter_global_footer_player_data_attributes_array', BeAudioIgniter()->get_playlist_data_attributes_array( $id ), $id, $post );
		$params = array_filter( $params, array( BeAudioIgniter()->sanitizer, 'array_filter_empty_null' ) );
		$params = BeAudioIgniter()->sanitizer->html_data_attributes_array( $params );

		if ( false !== apply_filters( 'audioigniter_global_footer_player_shortcircuit', false, $id, $post, $params ) ) {
			return;
		}

		$data = '';
		foreach ( $params as $attribute => $value ) {
			$data .= sprintf( '%s="%s" ', sanitize_key( $attribute ), esc_attr( $value ) );
		}

		$output = sprintf( '<div id="audioigniter-%s" class="audioigniter-root" %s></div>',
			esc_attr( $id ),
			$data
		);

		echo $output;
	}

	// 注册在前端运行的操作。
	protected function frontend_init() {
		if ( is_admin() ) {
			return;
		}

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'template_redirect', array( $this, 'handle_playlist_endpoint' ) );
		add_filter( 'audioigniter_get_playlist_data_attributes_array', array( $this, 'filter_get_playlist_data_attributes_array' ), 10, 2 );
		do_action( 'audioigniter_frontend_init' );
	}

	public function register_scripts() {
		wp_register_style( 'be-audio', get_template_directory_uri() . '/css/player.css', array(), version );
		wp_register_script( 'be-audio', get_template_directory_uri() . '/js/player.js', array(), version, true );

		wp_localize_script( 'be-audio', 'aiStrings', apply_filters( 'audioigniter_aiStrings', array(
			/* translators: %s is the track's title. */
			'play_title'          => esc_html__( '播放 %s', 'begin' ),
			/* translators: %s is the track's title. */
			'pause_title'         => esc_html__( '暂停 %s', 'begin' ),
			'previous'            => esc_html__( '上一曲', 'begin' ),
			'next'                => esc_html__( '下一曲', 'begin' ),
			'toggle_list_repeat'  => esc_html__( '切换列表循环播放', 'begin' ),
			'toggle_track_repeat' => esc_html__( '单曲循环', 'begin' ),
			'toggle_list_visible' => esc_html__( '显示隐藏列表', 'begin' ),
			'volume_up'           => esc_html__( '增大音量', 'begin' ),
			'volume_down'         => esc_html__( '减小音量', 'begin' ),
			'shuffle'             => esc_html__( '随机播放', 'begin' ),
		) ) );

		wp_register_script( 'audio-admin', get_template_directory_uri() . '/js/audio-admin.js', array(), version, true );
		wp_localize_script( 'audio-admin', 'ai_scripts', array(
			'messages' => array(
				'confirm_clear_tracks'     => esc_html__( '删除所有的音频，但并不会删除音频文件', 'begin' ),
				'media_title_upload'       => esc_html__( '选择或上传音频', 'begin' ),
				'media_title_upload_cover' => esc_html__( '选择封面', 'begin' ),
			),
		) );
	}

	// 加载前端脚本和样式
	public function enqueue_scripts() {
		wp_enqueue_style( 'be-audio' );
		wp_enqueue_style( 'grey' );
		wp_enqueue_script( 'be-audio' );
	}

	// 加载后台脚本和样式
	public function enqueue_admin_scripts( $hook ) {
		$screen = get_current_screen();

		if ( 'post' === $screen->base && $screen->post_type === $this->post_type ) {
			wp_enqueue_media();
			wp_enqueue_script( 'audio-admin' );
		}

//		if ( 'ai_playlist_page_audioigniter-upsell' === $screen->id ) {
//			wp_enqueue_style( 'audioigniter-admin-settings' );
//		}
	}

	public function register_post_types() {
		$labels = array(
			'name'               => esc_html_x( '音频', 'post type general name', 'begin' ),
			'singular_name'      => esc_html_x( '音频', 'post type singular name', 'begin' ),
			'menu_name'          => esc_html_x( '音频', 'admin menu', 'begin' ),
			'all_items'          => esc_html_x( '所有音频', 'admin menu', 'begin' ),
			'name_admin_bar'     => esc_html_x( '音频', 'add new on admin bar', 'begin' ),
			'add_new'            => esc_html__( '添加音频', 'begin' ),
			'add_new_item'       => esc_html__( '添加音频', 'begin' ),
			'edit_item'          => esc_html__( '编辑音频', 'begin' ),
			'new_item'           => esc_html__( '新建音频', 'begin' ),
			'view_item'          => esc_html__( '查看音频', 'begin' ),
			'search_items'       => esc_html__( '搜索音频', 'begin' ),
			'not_found'          => esc_html__( '没有找到音频', 'begin' ),
			'not_found_in_trash' => esc_html__( '回收站里没有找到音频', 'begin' ),
		);

		$args = array(
			'labels'          => $labels,
			'singular_label'  => esc_html_x( '音频', 'post type singular name', 'begin' ),
			'public'          => false,
			'show_ui'         => true,
			'capability_type' => 'post',
			'hierarchical'    => false,
			'has_archive'     => false,
			'supports'        => array( 'title' ),
			'menu_icon'       => 'dashicons-format-audio',
		);

		if ( function_exists( 'be_themes_install' ) )
		register_post_type( $this->post_type, $args );
	}

	// 为ai_playlist文章类型添加自定义面板
	public function add_meta_boxes() {
		add_meta_box( 'ai-meta-box-tracks', esc_html__( '添加音频', 'begin' ), array( $this, 'metabox_tracks' ), $this->post_type, 'normal', 'high' );
		add_meta_box( 'ai-meta-box-settings', esc_html__( '设置', 'begin' ), array( $this, 'metabox_settings' ), $this->post_type, 'normal', 'high' );
		add_meta_box( 'ai-box-shortcode', esc_html__( '短代码', 'begin' ), array( $this, 'metabox_shortcode' ), $this->post_type, 'side', 'default' );
	}

	// 输出Tracks元框的标记
	public function metabox_tracks( $object, $box ) {
		$tracks = $this->get_post_meta( $object->ID, '_audioigniter_tracks', array() );

		wp_nonce_field( basename( __FILE__ ), $object->post_type . '_nonce' );
		?>

		<div class="ai-container">
			<?php $this->metabox_tracks_field_controls( 'top', $object->ID ); ?>

			<?php $container_classes = apply_filters( 'audioigniter_metabox_tracks_container_classes', array( 'ai-fields-container ai-fields-sortable ui-sortable' ) ); ?>

			<div class="<?php echo esc_attr( implode( ' ', $container_classes ) ); ?>">
				<?php
					if ( ! empty( $tracks ) ) {
						foreach ( $tracks as $track ) {
							$this->metabox_tracks_repeatable_track_field( $track );
						}
					} else {
						$this->metabox_tracks_repeatable_track_field();
					}
				?>
			</div>

			<?php $this->metabox_tracks_field_controls( 'bottom', $object->ID ); ?>
		</div>

		<?php
	}

	// 输出Settings元框的标记语法输出Tracks元框的页脚标记
	protected function metabox_tracks_repeatable_track_field( $track = array() ) {
		$track = wp_parse_args( $track, self::get_default_track_values() );

		$cover_id                = $track['cover_id'];
		$title                   = $track['title'];
		$artist                  = $track['artist'];
		$track_url               = $track['track_url'];
		$cover_link              = $track['cover_link'];

		$cover_url = wp_get_attachment_image_src( intval( $cover_id ), 'thumbnail' );
		if ( ! empty( $cover_url[0] ) ) {
			$cover_url  = $cover_url[0];
			$cover_data = wp_prepare_attachment_for_js( intval( $cover_id ) );
		} else {
			$cover_url  = '';
			$cover_data = '';
		}

		$cover_link = $track['cover_link'];

		$uid = uniqid();

		$field_classes = apply_filters( 'audioigniter_metabox_track_classes', array( 'ai-field-repeatable' ), $track_url );
		?>
		<div class="<?php echo esc_attr( implode( ' ', $field_classes ) ); ?>" data-uid="<?php echo esc_attr( $uid ); ?>">
			<div class="ai-field-head">
				<?php do_action( 'audioigniter_metabox_tracks_repeatable_track_field_before_title' ); ?>

				<span class="ai-field-title"><i class="dashicons dashicons-format-audio"></i><?php echo wp_kses( $title, array() ); ?></span>

				<button type="button" class="ai-field-toggle button-link">
					<span class="screen-reader-text">
						<?php esc_html_e( '切换单曲可见性', 'begin' ); ?>
					</span>
					<span class="toggle-indicator"></span>
				</button>
			</div>

			<div class="ai-field-container">
				<div class="ai-field-cover">
					<a href="#" class="ai-field-upload-cover <?php echo ! empty( $cover_url ) ? 'ai-has-cover' : ''; ?> <?php echo ! empty( $cover_link ) ? 'ai-has-cover-link' : ''; ?>">
						<span class="ai-remove-cover">
							<span class="screen-reader-text">
								<?php esc_html_e( '移除封面', 'begin' ); ?>
							</span>
							<span class="dashicons dashicons-no-alt"></span>
						</span>

						<?php if ( ! empty( $cover_link ) ) { ?>
							<img src="<?php echo esc_url( $cover_link ); ?>" alt="<?php echo esc_attr( $title ); ?>">
						<?php } else { ?>
							<?php if ( ! empty( $cover_url ) ) : ?>
								<img src="<?php echo esc_url( $cover_url ); ?>" alt="<?php echo esc_attr( $cover_data['alt'] ); ?>">
							<?php else : ?>
								<img src="#" alt="">
							<?php endif; ?>
						<?php } ?>

						<div class="ai-field-cover-placeholder">
							<span class="ai-cover-prompt">
								<?php esc_html_e( '上传封面', 'begin' ); ?>
							</span>
						</div>
					</a>

					<input
						type="hidden"
						id="ai_playlist_tracks-<?php echo esc_attr( $uid ); ?>-cover_id"
						name="ai_playlist_tracks[<?php echo esc_attr( $uid ); ?>][cover_id]"
						value="<?php echo esc_attr( $cover_id ); ?>"
					/>
				</div>

				<div class="ai-field-split">
					<div class="ai-form-field">
						<label
							for="ai_playlist_tracks-<?php echo esc_attr( $uid ); ?>-title"
							class="screen-reader-text">
							<?php esc_html_e( '标题', 'begin' ); ?>
						</label>
						<input
							type="text"
							id="ai_playlist_tracks-<?php echo esc_attr( $uid ); ?>-title"
							class="ai-track-title"
							name="ai_playlist_tracks[<?php echo esc_attr( $uid ); ?>][title]"
							placeholder="<?php esc_attr_e( '标题', 'begin' ); ?>"
							value="<?php echo esc_attr( $title ); ?>"
						/>
					</div>
					<div class="ai-form-field">
						<label
							for="ai_playlist_tracks-<?php echo esc_attr( $uid ); ?>-artist"
							class="screen-reader-text">
							<?php esc_html_e( '艺术家', 'begin' ); ?>
						</label>
						<input
							type="text"
							id="ai_playlist_tracks-<?php echo esc_attr( $uid ); ?>-artist"
							class="ai-track-artist"
							name="ai_playlist_tracks[<?php echo esc_attr( $uid ); ?>][artist]"
							placeholder="<?php esc_attr_e( '艺术家', 'begin' ); ?>"
							value="<?php echo esc_attr( $artist ); ?>"
						/>
					</div>

					<?php do_action( 'audioigniter_metabox_tracks_repeatable_track_fields_column_1', $track, $uid ); ?>
				</div>

				<div class="ai-field-split">
					<div class="ai-form-field">
						<label
							for="ai_playlist_tracks-<?php echo esc_attr( $uid ); ?>-track_url"
							class="screen-reader-text">
							<?php esc_html_e( '上传或输入音频链接', 'begin' ); ?>
						</label>

						<div class="ai-form-field-addon">
							<?php
								$originalUrl = esc_url( $track_url );

								if ( preg_match( '/^https?:\/\/music\.163\.com\/#\/song\?id=(\d+)/', $originalUrl, $matches ) ) {
									$songId = $matches[1];
									$newUrl = 'https://music.163.com/song/media/outer/url?id=' . $songId . '.mp3';
									$track_url = esc_url( $newUrl );
								}
							?>
							<input
								type="text"
								id="ai_playlist_tracks-<?php echo esc_attr( $uid ); ?>-track_url"
								class="ai-track-url"
								name="ai_playlist_tracks[<?php echo esc_attr( $uid ); ?>][track_url]"
								placeholder="<?php esc_attr_e( '上传或输入音频链接', 'begin' ); ?>"
								value="<?php echo esc_url( $track_url ); ?>"
							/>
							<button type="button" class="button ai-upload">
								<?php esc_html_e( '上传', 'begin' ); ?>
							</button>

							<?php do_action( 'audioigniter_metabox_tracks_repeatable_track_field_after_track_upload_button' ); ?>
						</div>
					</div>

					<div class="ai-form-field">
						<label
							for="ai_playlist_tracks-<?php echo esc_attr( $uid ); ?>-cover_link"
							class="screen-reader-text">
							<?php esc_html_e( '封面图片', 'begin' ); ?>
						</label>
						<input
							type="text"
							id="ai_playlist_tracks-<?php echo esc_attr( $uid ); ?>-cover_link"
							class="ai-track-cover_link"
							name="ai_playlist_tracks[<?php echo esc_attr( $uid ); ?>][cover_link]"
							placeholder="<?php esc_attr_e( '封面图片', 'begin' ); ?>"
							value="<?php echo esc_attr( $cover_link ); ?>" 
						/>
					</div>

					<?php do_action( 'audioigniter_metabox_tracks_repeatable_track_fields_column_2', $track, $uid ); ?>

					<button type="button" class="button ai-remove-field">
						<?php esc_html_e( '删除', 'begin' ); ?>
					</button>
				</div>

			</div>
		</div>
		<?php
	}

	protected function metabox_tracks_field_controls( $location, $post_id ) {
		?>
		<div class="ai-field-controls-wrap">
			<div class="ai-field-controls">
				<button type="button" class="button ai-add-field ai-add-field-<?php echo esc_attr( $location ); ?>">
					<?php esc_html_e( '添加', 'begin' ); ?>
				</button>

				<?php do_action( 'audioigniter_metabox_tracks_field_controls', $location, $post_id ); ?>

				<button type="button" class="button ai-remove-all-fields">
					<?php esc_html_e( '删除全部', 'begin' ); ?>
				</button>
			</div>

			<div class="ai-field-controls-visibility">
				<a href="#" class="ai-fields-expand-all">
					<?php esc_html_e( '展开', 'begin' ); ?>
				</a>
				<a href="#" class="ai-fields-collapse-all">
					<?php esc_html_e( '折叠', 'begin' ); ?>
				</a>
			</div>
		</div>
		<?php
	}

	// 输出Settings元框的标记
	public function metabox_settings( $object, $box ) {
		$type                       = $this->get_post_meta( $object->ID, '_audioigniter_player_type', 'full' );
		$style                      = $this->get_post_meta( $object->ID, '_audioigniter_player_style', 'plain' );
		$numbers                    = $this->get_post_meta( $object->ID, '_audioigniter_show_numbers', 0 );
		$show_limit                 = $this->get_post_meta( $object->ID, '_audioigniter_show_limit', 0 );
		$numbers_reverse            = $this->get_post_meta( $object->ID, '_audioigniter_show_numbers_reverse', 0 );
		$thumb                      = $this->get_post_meta( $object->ID, '_audioigniter_show_covers', 1 );
		$active_thumb               = $this->get_post_meta( $object->ID, '_audioigniter_show_active_cover', 1 );
		$artist                     = $this->get_post_meta( $object->ID, '_audioigniter_show_artist', 1 );
		$cycle_tracks               = $this->get_post_meta( $object->ID, '_audioigniter_cycle_tracks', 1 );
		$track_listing              = $this->get_post_meta( $object->ID, '_audioigniter_show_track_listing', 1 );
		$track_listing_allow_toggle = $this->get_post_meta( $object->ID, '_audioigniter_allow_track_listing_toggle', 1 );
		$track_listing_allow_loop   = $this->get_post_meta( $object->ID, '_audioigniter_allow_track_listing_loop', 1 );
		$limit_tracklisting_height  = $this->get_post_meta( $object->ID, '_audioigniter_limit_tracklisting_height', 0 );
		$tracklisting_height        = $this->get_post_meta( $object->ID, '_audioigniter_tracklisting_height', 185 );
		$volume                     = $this->get_post_meta( $object->ID, '_audioigniter_volume', 100 );
		$max_width                  = $this->get_post_meta( $object->ID, '_audioigniter_max_width' );
		$credit                     = $this->get_post_meta( $object->ID, '_audioigniter_show_credit', 1 );
		$track_allow_loop           = $this->get_post_meta( $object->ID, '_audioigniter_allow_track_loop', 1 );
		$stop_on_track_finish       = $this->get_post_meta( $object->ID, '_audioigniter_stop_on_track_finish', 0 );
		$remember_last_player_position = $this->get_post_meta( $object->ID, '_audioigniter_remember_last_player_position', 1 );

		wp_nonce_field( basename( __FILE__ ), $object->post_type . '_nonce' );
		?>
		<div class="ai-module ai-module-settings ai-module-box">
			<div class="ai-form-field-group">
				<h3 class="ai-form-field-group-title"><?php esc_html_e( '播放器设置', 'begin' ); ?></h3>

				<div class="ai-form-field">
					<div class="ai-player-type-message ai-info-box"></div>
					<label for="_audioigniter_player_style">
						<?php esc_html_e( '选择颜色', 'begin' ); ?>
					</label>

					<select
						class="widefat ai-form-select-player-style"
						id="_audioigniter_player_style"
						name="_audioigniter_player_style"
					>
						<?php foreach ( $this->get_player_styles() as $player_css => $player_style ) : ?>
							<option
								value="<?php echo esc_attr( $player_css ); ?>"
								data-no-support="<?php echo esc_attr( implode( ', ', $player_style['no-support'] ) ); ?>"
								data-info="<?php echo esc_attr( $player_style['info'] ); ?>"
								<?php selected( $style, $player_css ); ?>
							>
								<?php echo wp_kses( $player_style['label'], 'strip' ); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="ai-form-field">
					<div class="ai-player-type-message ai-info-box"></div>
					<label for="_audioigniter_player_type">
						<?php esc_html_e( '显示模式', 'begin' ); ?>
					</label>

					<select
						class="widefat ai-form-select-player-type"
						id="_audioigniter_player_type"
						name="_audioigniter_player_type"
					>
						<?php foreach ( $this->get_player_types() as $player_key => $player_type ) : ?>
							<option
								value="<?php echo esc_attr( $player_key ); ?>"
								data-no-support="<?php echo esc_attr( implode( ', ', $player_type['no-support'] ) ); ?>"
								data-info="<?php echo esc_attr( $player_type['info'] ); ?>"
								<?php selected( $type, $player_key ); ?>
							>
								<?php echo wp_kses( $player_type['label'], 'strip' ); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="ai-form-field">
					<label for="_audioigniter_max_width">
						<?php esc_html_e( '最大宽度', 'begin' ); ?>
					</label>

					<input type="number" min="200" id="_audioigniter_max_width" class="ai-track-title" name="_audioigniter_max_width" placeholder="<?php esc_attr_e( '自适应，留空即可', 'begin' ); ?>" value="<?php echo esc_attr( $max_width ); ?>"/>
				</div>

				<div class="ai-form-field">
					<label for="_audioigniter_volume">
						<?php esc_html_e( '初始音量', 'begin' ); ?>
					</label>

					<input
						type="number"
						min="10"
						max="100"
						step="10"
						id="_audioigniter_volume"
						class="ai-track-title"
						name="_audioigniter_volume"
						placeholder="<?php esc_attr_e( '0-100', 'begin' ); ?>"
						value="<?php echo esc_attr( $volume ); ?>"
					/>
				</div>

				<?php 
					$shuffle = BeAudioIgniter()->get_post_meta( $object->ID, '_audioigniter_shuffle', '' );

					if ( ! in_array( $shuffle, array( '', 'show', 'show_on' ), true ) ) {
						if ( '1' === (string) $shuffle ) {
							$shuffle = 'show_on';
						} elseif ( '0' === (string) $shuffle ) {
							$shuffle = '';
						}
					}

					if ( '' === $shuffle ) {
						$shuffle = 'show';
					}
				?>
				<div class="ai-form-field">
					<label for="_audioigniter_shuffle">
						<?php esc_html_e( '随机按钮', 'begin' ); ?>
					</label>

					<select class="ai-dropdown" id="_audioigniter_shuffle" name="_audioigniter_shuffle">
						<option value="" <?php selected( $shuffle, '' ); ?>><?php esc_html_e( '不显示', 'begin' ); ?></option>
						<option value="show" <?php selected( $shuffle, 'show' ); ?>><?php esc_html_e( '显示', 'begin' ); ?></option>
						<option value="show_on" <?php selected( $shuffle, 'show_on' ); ?>><?php esc_html_e( '显示并启用', 'begin' ); ?></option>
					</select>
				</div>

				<div class="ai-form-field">
					<input
						type="checkbox"
						class="ai-checkbox checkbox"
						id="_audioigniter_show_track_listing"
						name="_audioigniter_show_track_listing"
						value="1" <?php checked( $track_listing, true ); ?>
					/>

					<label for="_audioigniter_show_track_listing">
						<?php esc_html_e( '默认显示列表', 'begin' ); ?>
					</label>
				</div>

				<div class="ai-form-field">
					<input
						type="checkbox"
						class="ai-checkbox checkbox"
						id="_audioigniter_limit_tracklisting_height"
						name="_audioigniter_limit_tracklisting_height"
						value="1" <?php checked( $limit_tracklisting_height, true ); ?>
					/>

					<label for="_audioigniter_limit_tracklisting_height">
						<?php esc_html_e( '限制列表高度', 'begin' ); ?>
					</label>
				</div>

				<div class="ai-form-field">
					<label for="_audioigniter_tracklisting_height">
						<?php esc_html_e( '列表高度', 'begin' ); ?>
					</label>

					<input
						type="number"
						min="80"
						step="5"
						id="_audioigniter_tracklisting_height"
						class="ai-track-title"
						name="_audioigniter_tracklisting_height"
						placeholder="<?php esc_attr_e( '列表高度', 'begin' ); ?>"
						value="<?php echo esc_attr( $tracklisting_height ); ?>"
					/>
					</p>
				</div>

				<div class="ai-form-field">
					<input
						type="checkbox"
						class="ai-checkbox checkbox"
						id="_audioigniter_allow_track_listing_toggle"
						name="_audioigniter_allow_track_listing_toggle"
						value="1" <?php checked( $track_listing_allow_toggle, true ); ?>
					/>

					<label for="_audioigniter_allow_track_listing_toggle">
						<?php esc_html_e( '列表按钮', 'begin' ); ?>
					</label>
				</div>

				<div class="ai-form-field">
					<input
						type="checkbox"
						class="ai-checkbox checkbox"
						id="_audioigniter_show_limit"
						name="_audioigniter_show_limit"
						value="1" <?php checked( $show_limit, true ); ?>
					/>

					<label for="_audioigniter_show_limit">
						<?php esc_html_e( '底部模式自动隐藏', 'begin' ); ?>
					</label>
				</div>

				<div class="ai-form-field">
					<input
						type="checkbox"
						class="ai-checkbox checkbox"
						id="_audioigniter_show_numbers_revese"
						name="_audioigniter_show_numbers_reverse"
						value="1" <?php checked( $numbers_reverse, true ); ?>
					/>

					<label for="_audioigniter_show_numbers_revese">
						<?php esc_html_e( '倒序排列', 'begin' ); ?>
					</label>
				</div>

				<div class="ai-form-field">
					<input
						type="checkbox"
						class="ai-checkbox checkbox"
						id="_audioigniter_show_credit"
						name="_audioigniter_show_credit"
						value="1" <?php checked( $credit, true ); ?>
					/>

					<label for="_audioigniter_show_credit">
						<?php esc_html_e( '编辑按钮', 'begin' ); ?>
					</label>
				</div>

				<?php do_action( 'audioigniter_metabox_settings_group_player_track_listing_fields', $object, $box ); ?>
			</div>

			<div class="ai-form-field-group">
				<h3 class="ai-form-field-group-title">音频信息</h3>

				<div class="ai-form-field">
					<input
						type="checkbox"
						class="ai-checkbox checkbox"
						id="_audioigniter_show_covers"
						name="_audioigniter_show_covers"
						value="1" <?php checked( $thumb, true ); ?>
					/>

					<label for="_audioigniter_show_covers">
						<?php esc_html_e( '显示封面', 'begin' ); ?>
					</label>
				</div>

				<div class="ai-form-field">
					<input
						type="checkbox"
						class="ai-checkbox checkbox"
						id="_audioigniter_show_active_cover"
						name="_audioigniter_show_active_cover"
						value="1" <?php checked( $active_thumb, true ); ?>
					/>

					<label for="_audioigniter_show_active_cover">
						<?php esc_html_e( "当前封面", 'begin' ); ?>
					</label>
				</div>

				<div class="ai-form-field">
					<input
						type="checkbox"
						class="ai-checkbox checkbox"
						id="_audioigniter_show_artist"
						name="_audioigniter_show_artist"
						value="1" <?php checked( $artist, true ); ?>
					/>

					<label for="_audioigniter_show_artist">
						<?php esc_html_e( '艺术家', 'begin' ); ?>
					</label>
				</div>

				<div class="ai-form-field">
					<input
						type="checkbox"
						class="ai-checkbox checkbox"
						id="_audioigniter_show_numbers"
						name="_audioigniter_show_numbers"
						value="1" <?php checked( $numbers, true ); ?>
					/>

					<label for="_audioigniter_show_numbers">
						<?php esc_html_e( '序号', 'begin' ); ?>
					</label>
				</div>

				<?php do_action( 'audioigniter_metabox_settings_group_tracks_fields', $object, $box ); ?>
			</div>

			<div class="ai-form-field-group">
				<h3 class="ai-form-field-group-title"><?php esc_html_e( '循环设置', 'begin' ); ?></h3>

				<div class="ai-form-field">
					<input
						type="checkbox"
						class="ai-checkbox checkbox"
						id="_audioigniter_cycle_tracks"
						name="_audioigniter_cycle_tracks"
						value="1" <?php checked( $cycle_tracks, true ); ?>
					/>

					<label for="_audioigniter_cycle_tracks">
						<?php esc_html_e( '列表循环', 'begin' ); ?>
					</label>
				</div>

				<div class="ai-form-field">
					<input
						type="checkbox"
						class="ai-checkbox checkbox"
						id="_audioigniter_allow_track_listing_loop"
						name="_audioigniter_allow_track_listing_loop"
						value="1" <?php checked( $track_listing_allow_loop, true ); ?>
					/>

					<label for="_audioigniter_allow_track_listing_loop">
						<?php esc_html_e( '循环按钮', 'begin' ); ?>
					</label>
				</div>

				<div class="ai-form-field">
					<input
						type="checkbox"
						class="ai-checkbox checkbox"
						id="_audioigniter_allow_track_loop"
						name="_audioigniter_allow_track_loop"
						value="1" <?php checked( $track_allow_loop, true ); ?>
					/>

					<label for="_audioigniter_allow_track_loop">
						<?php esc_html_e( '单曲循环', 'begin' ); ?>
					</label>
				</div>

				<div class="ai-form-field">
					<input
						type="checkbox"
						class="ai-checkbox checkbox"
						id="_audioigniter_stop_on_track_finish"
						name="_audioigniter_stop_on_track_finish"
						value="1" <?php checked( $stop_on_track_finish, true ); ?>
					/>

					<label for="_audioigniter_stop_on_track_finish">
						<?php esc_html_e( '单曲停止', 'begin' ); ?>
					</label>
				</div>

				<div class="ai-form-field">
					<input
						type="checkbox"
						class="ai-checkbox checkbox"
						id="_audioigniter_remember_last_player_position"
						name="_audioigniter_remember_last_player_position"
						value="1" <?php checked( $remember_last_player_position, true ); ?>
					/>

					<label for="_audioigniter_remember_last_player_position">
						<?php esc_html_e( '记住上次播放位置', 'begin' ); ?>
					</label>
				</div>

				<?php do_action( 'audioigniter_metabox_settings_group_player_track_track_listing_repeat_fields', $object, $box ); ?>
			</div>
		</div>
		<?php
	}

	// 短代码面板
	public function metabox_shortcode( $object, $box ) {
		?>
		<div class="ai-module ai-module-shortcode">
			<div class="ai-form-field">
				<label for="ai_shortcode">
					<?php esc_html_e( '复制短代码，添加到文章中', 'begin' ); ?>
				</label>

				<input
					type="text"
					class="code"
					id="ai_shortcode"
					name="ai_shortcode"
					value="<?php echo esc_attr( sprintf( '[ai_playlist id="%s"]', $object->ID ) ); ?>"
				/>

			</div>
		</div>
		<?php
	}

	// 播放器显示模式
	public function get_player_types() {
		$player_types = array(
			'full'   => array(
				'label'      => __( '完整', 'begin' ),
				'no-support' => array(),
				'info'       => '',
			),

			'simple' => array(
				'label'      => __( '简易', 'begin' ),
				'no-support' => array(
					'show_track_listing',
					'show_covers',
					'show_active_cover',
					'limit_tracklisting_height',
					'tracklisting_height',
					'allow_track_listing_loop',
					'allow_track_listing_toggle',
					'skip_amount',
					'initial_track',
				),
				'info'       => '',
			),

			'footer' => array(
				'label'      => __( '页脚', 'begin' ),
				'no-support' => array(
					'show_credit',
					'max_width',
				),
				'info'       => '',
			),

			'global-footer' => array(
				'label'      => __( '全站页脚', 'begin' ),
				'no-support' => array(
					'show_credit',
					'max_width',
				),
				'info'       => '',
			),

		);

		return apply_filters( 'audioigniter_player_types', $player_types );
	}

	public function get_player_styles() {
		$player_styles = array(
			'plain' => array(
				'label'      => __( '简约', 'begin' ),
				'no-support' => array(),
				'info'       => '',
			),

			'white'   => array(
				'label'      => __( '白色', 'begin' ),
				'no-support' => array(),
				'info'       => '',
			),
			'black'   => array(
				'label'      => __( '黑色', 'begin' ),
				'no-support' => array(),
				'info'       => '',
			),

			'grey' => array(
				'label'      => __( '灰色', 'begin' ),
				'no-support' => array(),
				'info'       => '',
			),
		);

		return apply_filters( 'audioigniter_player_styles', $player_styles );
	}

	// 全局页脚
	public function invalidate_transient_global_footer_player_id( $post_id ) {
		delete_transient( 'audioigniter_global_footer_player_id' );
	}

	public function filter_get_playlist_data_attributes_array( $attrs, $post_id ) {
		$ai = BeAudioIgniter();
		$attrs['data-allow-track-loop']    = $ai->convert_bool_string( $ai->get_post_meta( $post_id, '_audioigniter_allow_track_loop', 0 ) );
		$attrs['data-stop-on-finish']      = $ai->convert_bool_string( $ai->get_post_meta( $post_id, '_audioigniter_stop_on_track_finish', 0 ) );
		$attrs['data-remember-last']       = $ai->convert_bool_string( $ai->get_post_meta( $post_id, '_audioigniter_remember_last_player_position', 0 ) );
		$attrs['data-shuffle']             = $ai->convert_bool_string( in_array( $ai->get_post_meta( $post_id, '_audioigniter_shuffle', '' ), array( 'show_on', 'show', '1', 1 ), true ) );
		$attrs['data-shuffle-default']     = $ai->convert_bool_string( in_array( $ai->get_post_meta( $post_id, '_audioigniter_shuffle', '' ), array( 'show_on', '1', 1 ), true ) );
		return $attrs;
	}

	public function save_post( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return false; }
		if ( isset( $_POST['post_view'] ) && 'list' === $_POST['post_view'] ) { return false; }
		if ( ! isset( $_POST['post_type'] ) || $_POST['post_type'] !== $this->post_type ) { return false; }
		if ( ! isset( $_POST[ $this->post_type . '_nonce' ] ) || ! wp_verify_nonce( $_POST[ $this->post_type . '_nonce' ], basename( __FILE__ ) ) ) { return false; }
		$post_type_obj = get_post_type_object( $this->post_type );
		if ( ! current_user_can( $post_type_obj->cap->edit_post, $post_id ) ) { return false; }

		update_post_meta( $post_id, '_audioigniter_tracks', $this->sanitizer->metabox_playlist( $_POST['ai_playlist_tracks'], $post_id ) );
		update_post_meta( $post_id, '_audioigniter_allow_track_loop', BeAudioIgniter()->sanitizer->checkbox_ref( $_POST['_audioigniter_allow_track_loop'] ) );
		update_post_meta( $post_id, '_audioigniter_show_numbers', $this->sanitizer->checkbox_ref( $_POST['_audioigniter_show_numbers'] ) );
		update_post_meta( $post_id, '_audioigniter_show_limit', $this->sanitizer->checkbox_ref( $_POST['_audioigniter_show_limit'] ) );
		update_post_meta( $post_id, '_audioigniter_show_numbers_reverse', $this->sanitizer->checkbox_ref( $_POST['_audioigniter_show_numbers_reverse'] ) );
		update_post_meta( $post_id, '_audioigniter_show_covers', $this->sanitizer->checkbox_ref( $_POST['_audioigniter_show_covers'] ) );
		update_post_meta( $post_id, '_audioigniter_show_active_cover', $this->sanitizer->checkbox_ref( $_POST['_audioigniter_show_active_cover'] ) );
		update_post_meta( $post_id, '_audioigniter_show_artist', $this->sanitizer->checkbox_ref( $_POST['_audioigniter_show_artist'] ) );
		update_post_meta( $post_id, '_audioigniter_cycle_tracks', $this->sanitizer->checkbox_ref( $_POST['_audioigniter_cycle_tracks'] ) );
		update_post_meta( $post_id, '_audioigniter_show_track_listing', $this->sanitizer->checkbox_ref( $_POST['_audioigniter_show_track_listing'] ) );
		update_post_meta( $post_id, '_audioigniter_allow_track_listing_toggle', $this->sanitizer->checkbox_ref( $_POST['_audioigniter_allow_track_listing_toggle'] ) );
		update_post_meta( $post_id, '_audioigniter_allow_track_listing_loop', $this->sanitizer->checkbox_ref( $_POST['_audioigniter_allow_track_listing_loop'] ) );
		update_post_meta( $post_id, '_audioigniter_player_type', $this->sanitizer->player_type( $_POST['_audioigniter_player_type'] ) );
		update_post_meta( $post_id, '_audioigniter_show_credit', $this->sanitizer->checkbox_ref( $_POST['_audioigniter_show_credit'] ) );
		update_post_meta( $post_id, '_audioigniter_player_style', $this->sanitizer->player_style( $_POST['_audioigniter_player_style'] ) );
		update_post_meta( $post_id, '_audioigniter_limit_tracklisting_height', $this->sanitizer->checkbox_ref( $_POST['_audioigniter_limit_tracklisting_height'] ) );
		update_post_meta( $post_id, '_audioigniter_tracklisting_height', intval( $_POST['_audioigniter_tracklisting_height'] ) );
		update_post_meta( $post_id, '_audioigniter_volume', intval( $_POST['_audioigniter_volume'] ) );
		update_post_meta( $post_id, '_audioigniter_max_width', $this->sanitizer->intval_or_empty( $_POST['_audioigniter_max_width'] ) );
		update_post_meta( $post_id, '_audioigniter_remember_last_player_position', BeAudioIgniter()->sanitizer->checkbox_ref( $_POST['_audioigniter_remember_last_player_position'] ) );
		update_post_meta( $post_id, '_audioigniter_stop_on_track_finish', BeAudioIgniter()->sanitizer->checkbox_ref( $_POST['_audioigniter_stop_on_track_finish'] ) );
		$shuffle = sanitize_key( $_POST['_audioigniter_shuffle'] );
		update_post_meta( $post_id, '_audioigniter_shuffle', in_array( $shuffle, array( '', 'show', 'show_on' ), true ) ? $shuffle : '' );
		do_action( 'audioigniter_save_post', $post_id );
	}

	public static function get_default_track_values() {
		return apply_filters( 'audioigniter_default_track_values', array(
			'cover_id'    => '',
			'title'       => '',
			'artist'      => '',
			'track_url'   => '',
			'cover_link'  => '',
		) );
	}

	public function register_widgets() {
		$widgets = apply_filters( 'audioigniter_register_widgets', array() );

		foreach ( $widgets as $class => $file ) {
			require_once( $file );
			register_widget( $class );
		}
	}

	public function register_shortcodes() {
		add_shortcode( 'ai_playlist', array( $this, 'shortcode_ai_playlist' ) );
	}

	// 检查传递的文章对象或 ID 是否为播放列表。
	public function is_playlist( $post ) {
		$post = get_post( $post );

		if ( is_wp_error( $post ) || empty( $post ) || is_null( $post ) || $post->post_type !== $this->post_type ) {
			return false;
		}

		return true;
	}

	// 返回给定播放列表的数据组数
	public function get_playlist_data_attributes_array( $post_id ) {
		$post_id = intval( $post_id );

		if ( ! $this->is_playlist( $post_id ) ) {
			return array();
		}

		if ( current_user_can( 'administrator' ) ) {
			$pid = home_url( '/' ) . 'wp-admin/post.php?post=' .$post_id . '&action=edit';
			$edit = $this->convert_bool_string( $this->get_post_meta( $post_id, '_audioigniter_show_credit', 1 ) );
		} else {
			$pid = '';
			$edit = '';
		}

		$attrs = array(
			'data-player-type'              => $this->get_post_meta( $post_id, '_audioigniter_player_type', 'full' ),
			'data-player-style'             => $this->get_post_meta( $post_id, '_audioigniter_player_style', 'plain' ),
			'data-tracks-url'               => add_query_arg( array( 'audioigniter_playlist_id' => $post_id ), home_url( '/' ) ),
			'data-edit-url'                 => $pid,
			'data-display-track-no'         => $this->convert_bool_string( $this->get_post_meta( $post_id, '_audioigniter_show_numbers', 0 ) ),
			'data-show_limit'         => $this->convert_bool_string( $this->get_post_meta( $post_id, '_audioigniter_show_limit', 0 ) ),
			'data-reverse-track-order'      => $this->convert_bool_string( $this->get_post_meta( $post_id, '_audioigniter_show_numbers_reverse', 0 ) ),
			'data-display-tracklist-covers' => $this->convert_bool_string( $this->get_post_meta( $post_id, '_audioigniter_show_covers', 1 ) ),
			'data-display-active-cover'     => $this->convert_bool_string( $this->get_post_meta( $post_id, '_audioigniter_show_active_cover', 1 ) ),
			'data-display-artist-names'     => $this->convert_bool_string( $this->get_post_meta( $post_id, '_audioigniter_show_artist', 1 ) ),
			'data-cycle-tracks'             => $this->convert_bool_string( $this->get_post_meta( $post_id, '_audioigniter_cycle_tracks', 1 ) ),
			'data-display-tracklist'        => $this->convert_bool_string( $this->get_post_meta( $post_id, '_audioigniter_show_track_listing', 1 ) ),
			'data-allow-tracklist-toggle'   => $this->convert_bool_string( $this->get_post_meta( $post_id, '_audioigniter_allow_track_listing_toggle', 1 ) ),
			'data-allow-tracklist-loop'     => $this->convert_bool_string( $this->get_post_meta( $post_id, '_audioigniter_allow_track_listing_loop', 1 ) ),
			'data-limit-tracklist-height'   => $this->convert_bool_string( $this->get_post_meta( $post_id, '_audioigniter_limit_tracklisting_height', 0 ) ),
			'data-display-credits'          => $edit,
			'data-volume'                   => intval( $this->get_post_meta( $post_id, '_audioigniter_volume', 100 ) ),
			'data-tracklist-height'         => intval( $this->get_post_meta( $post_id, '_audioigniter_tracklisting_height', 200 ) ),
			'data-max-width'                => $this->get_post_meta( $post_id, '_audioigniter_max_width' ),
		);

		return apply_filters( 'audioigniter_get_playlist_data_attributes_array', $attrs, $post_id );
	}

	// 返回 [ai_playlist] 短代码的输出
	public function shortcode_ai_playlist( $atts, $content, $tag ) {
		$atts = shortcode_atts( array(
			'id'    => '',
			'class' => '',
		), $atts, $tag );

		$id         = intval( $atts['id'] );
		$class_name = $atts['class'];

		if ( ! $this->is_playlist( $id ) ) {
			return '';
		}

		$post = get_post( $id );

		$params = apply_filters( 'audioigniter_shortcode_data_attributes_array', $this->get_playlist_data_attributes_array( $id ), $id, $post );
		$params = array_filter( $params, array( $this->sanitizer, 'array_filter_empty_null' ) );
		$params = $this->sanitizer->html_data_attributes_array( $params );

		// 如果从过滤器中返回一个真值（truthy value），将会中断执行短代码。
		if ( false !== apply_filters( 'audioigniter_shortcode_shortcircuit', false, $id, $post, $params ) ) {
			return '';
		}

		$data = '';
		foreach ( $params as $attribute => $value ) {
			$data .= sprintf( '%s="%s" ', sanitize_key( $attribute ), esc_attr( $value ) );
		}

		$player_classes = array_merge( array(
			'audioigniter-root',
		), explode( ' ', $class_name ) );

		$output = sprintf( '<div id="audioigniter-%s" class="%s" %s></div>',
			esc_attr( $id ),
			esc_attr( implode( ' ', $player_classes ) ),
			$data
		);

		return $output;
	}

	public function convert_bool_string( $value ) {
		if ( $value ) {
			return 'true';
		}

		return 'false';
	}

	public function register_playlist_endpoint() {
		add_rewrite_tag( '%audioigniter_playlist_id%', '([0-9]+)' );
		add_rewrite_rule( '^audioigniter/playlist/([0-9]+)/?', 'index.php?audioigniter_playlist_id=$matches[1]', 'bottom' );
	}

	public function handle_playlist_endpoint() {
		global $wp_query;

		$playlist_id = $wp_query->get( 'audioigniter_playlist_id' );

		if ( empty( $playlist_id ) ) {
			return;
		}

		$playlist_id = intval( $playlist_id );
		$post        = get_post( $playlist_id );

		if ( empty( $post ) || $post->post_type !== $this->post_type ) {
			wp_send_json_error( __( "ID 与播放列表不匹配", 'begin' ) );
		}

		$response = array();
		$tracks   = $this->get_post_meta( $playlist_id, '_audioigniter_tracks', array() );

		if ( empty( $tracks ) ) {
			$tracks = array();
		}

		foreach ( $tracks as $track ) {
			$track          = wp_parse_args( $track, self::get_default_track_values() );
			$track_response = array();

			$track_response['title']            = $track['title'];
			$track_response['subtitle']         = $track['artist'];
			$track_response['audio']            = $track['track_url'];
			$track_response['cover_link']       = $track['cover_link'];

			$cover_link = $track['cover_link'];

			if ( empty( $cover_link ) ) {
				$cover_url = wp_get_attachment_image_src( intval( $track['cover_id'] ), 'audioigniter_cover' );
				if ( ! empty( $cover_url[0] ) ) {
					$cover_img = $cover_url[0];
				} else {
					$cover_img = '';
				}
			} else {
				if ( ! empty( $cover_link ) ) {
					$cover_img = $cover_link;
				} else {
					$cover_img = '';
				}
			}

			$track_response['cover'] = $cover_img;

			$track_response = apply_filters( 'audioigniter_playlist_endpoint_track', $track_response, $track, $playlist_id, $post );

			$response[] = $track_response;
		}

		wp_send_json( $response );
	}

	public function filter_posts_columns( $columns ) {
		$date = $columns['date'];
		unset( $columns['date'] );

		$columns['shortcode'] = '短代码';
		$columns['date']      = $date;

		return $columns;
	}

	public function add_custom_columns( $column, $post_id ) {
		if ( 'shortcode' === $column ) {
			?><input type="text" class="code" value="<?php echo esc_attr( sprintf( '[ai_playlist id="%s"]', $post_id ) ); ?>"><?php
		}
	}

	function get_filename_from_url( $url ) {
		$struct = wp_parse_url( $url );

		if ( ! empty( $struct['path'] ) ) {
			return basename( $struct['path'] );
		}

		return '';
	}

	public function get_all_playlists( $orderby = 'date', $order = 'DESC' ) {
		$q = new WP_Query( array(
			'post_type'      => $this->post_type,
			'posts_per_page' => - 1,
			'orderby'        => $orderby,
			'order'          => $order,
		) );

		return $q->posts;
	}

	public function get_post_meta( $post_id, $key, $default = '' ) {
		$keys = get_post_custom_keys( $post_id );

		$value = $default;

		if ( is_array( $keys ) && in_array( $key, $keys, true ) ) {
			$value = get_post_meta( $post_id, $key, true );
		}

		return $value;
	}
}

function BeAudioIgniter() {
	return BeAudioIgniter::instance();
}

function be_audio() {
	BeAudioIgniter()->audio_setup();
}
add_action( 'init', 'be_audio', 1 );