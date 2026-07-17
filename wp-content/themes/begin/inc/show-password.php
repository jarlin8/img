<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// 密码查看网站
if ( ! is_user_logged_in() ) {
	add_action( 'init', 'be_check_page' );
}
function be_check_page() {
	if ( zm_get_option( 'be_password_status' ) ) {
		if ( ! isset( $_COOKIE["accessPassword"] ) || $_COOKIE["accessPassword"] != zm_get_option( 'be_password_pass' ) ) {
			add_filter( 'template_include', 'be_pass_page' );
			function be_pass_page(){
				$analysePass = zm_get_option( 'be_password_pass' );
				$error = '';

				if ( isset($_COOKIE['accessPassword']) && $_COOKIE['accessPassword'] != $analysePass ) {
					setcookie( 'accessPassword',' ', time() - 3600, "/" );
				}

				if ( isset( $_POST['passw10'] ) ) {
					$passTime = sanitize_text_field( $_POST['passw10'] );
					if ( $passTime == $analysePass ) {
						setcookie( 'accessPassword', $analysePass, time() + ( 86400 * 30 ) * 15, "/" );
						$url = add_query_arg( array() );
						header( "Location: $url" );
					} else {
						$error = __( '密码错误', 'begin' );
					}
				}
				?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0<?php if ( zm_get_option( 'mobile_viewport' ) ) { ?>, maximum-scale=1.0, maximum-scale=0.0, user-scalable=no<?php } ?>">
<meta http-equiv="Cache-Control" content="no-transform" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<?php do_action( 'title_head' ); ?>
<?php do_action( 'favicon_ico' ); ?>
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php wp_head(); ?>
<?php do_action( 'head_other' ); ?>

</head>
<body>
	<?php be_back_img(); ?>
	<div class="be-check-page">
		<div class="check-form">
			<form method="post" action="">
				<div class="row">
					<?php reg_logo(); ?>
					<div class="check-hint"><?php _e( '请输入密码访问网站', 'begin' ); ?>
						<?php if ( zm_get_option( 'be_show_password' ) ) { ?>
							<p class="check-pass-txt"><?php _e( '密码', 'begin' ); ?>：<?php echo zm_get_option( 'be_password_pass' ); ?></p>
						<?php } ?>
					</div>
				</div>
				<div class="row inp">
				<div class="check-errors"><?php if ( $error != '' ) { echo $error; } ?></div>
					<input type="password" name="passw10" class="check-passw" autofocus>
				</div>
				<button class="button btn-accept"><?php _e( '确定', 'begin' ); ?></button>
			</form>
		</div>
	</div>
</body>
</html>
			<?php }
		}
	}
}