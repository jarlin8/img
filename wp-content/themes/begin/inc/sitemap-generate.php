<?php
if ( ! defined( 'ABSPATH' ) ) exit;
add_action( 'admin_menu', 'register_be_sitemap_settings_page' );
function register_be_sitemap_settings_page() {
	add_management_page(
		'生成站点地图',
		'<span class="bem"></span>站点地图',
		'manage_options',
		'sitemap_generate',
		'be_sitemap_settings_page'
	);
}

function be_sitemap_settings_page() {
	$errors = get_settings_errors( 'be_sitemap_settings' );
	?>

	<div class="wrap">
		<h2 class="bezm-settings">生成站点地图</h2>

		<?php if ( ! empty( $errors ) && $errors[0]['type'] === 'success' ) : ?>
			<div class="notice notice-success"><p><?php echo esc_html( $errors[0]['message'] ); ?></p></div>
		<?php endif; ?>

		<div class="card be-area-box">
			<h3>提交后，请不要关闭，直至地图文件生成完毕。</h3>
			<p>如提示错误，将停止生成地图文件，可适当增加PHP内存限制值。</p>
			<p>主题选项 → SEO设置 → 站点地图 → 进行相关设置。</p>
			<?php if ( zm_get_option( 'wp_sitemap' ) ) { ?>
				<p>文章较多可考虑使用：<a style="text-decoration: none;" href="<?php echo home_url(); ?>/wp-sitemap.xml" target="_blank">WordPress原生站点地图</a></span></p>
			<?php } else { ?>
				<p>文章较多可考虑使用WordPress原生站点地图。</p>
			<?php } ?>
		</div>

		<form method="post" action="options.php">
			<?php
				settings_fields( 'be_sitemap_settings' );
				do_settings_sections( 'be_sitemap_settings' );
				submit_button( '生成地图' );
			?>
		</form>
	</div>
  <?php
}

add_action( 'admin_init', 'register_be_sitemap_settings' );
function register_be_sitemap_settings() {
	register_setting(
		'be_sitemap_settings',
		'be_sitemap_settings',
		'be_sitemap_settings_sanitize'
	);
}

function be_sitemap_settings_sanitize( $input ) {
	if ( isset( $_POST['submit'] ) ) {
		set_transient( 'settings_errors', array(), 30 );

		add_settings_error(
			'be_sitemap_settings',
			'be_sitemap_message',
			'站点地图已生成完毕！',
			'success'
		);

		do_action( 'be_sitemap_generate' );
	}
	return $input;
}

// 更新文章时刷新站点地图
if ( zm_get_option( 'publish_sitemap' ) ) {
	add_action( 'publish_post', 'schedule_sitemap_refresh', 10, 3 );
	add_action( 'publish_tao', 'schedule_sitemap_refresh', 10, 3 );
	add_action( 'publish_bulletin', 'schedule_sitemap_refresh', 10, 3 );
	add_action( 'publish_picture', 'schedule_sitemap_refresh', 10, 3 );
	add_action( 'publish_video', 'schedule_sitemap_refresh', 10, 3 );
	add_action( 'publish_sites', 'schedule_sitemap_refresh', 10, 3 );
	function schedule_sitemap_refresh( $post_id, $post, $update ) {
		if ( $update ) {
			wp_clear_scheduled_hook( 'be_sitemap_generate' );
			$next_refresh_time = time() + zm_get_option( 'refresh_sitemap_time' );
			wp_schedule_single_event( $next_refresh_time, 'be_sitemap_generate' );
		}
	}
}