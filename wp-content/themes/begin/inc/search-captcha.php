<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if ( zm_get_option( 'search_captcha' ) ) {
	// 搜索验证
	function be_search_captcha( $query, $error = true ) {
		if ( is_search() && !is_admin() ) {
			if ( ! isset( $_COOKIE[zm_get_option( 'search_captcha_cookie' )] ) ) {
				$query->is_search = false;
				$query->query_vars['s'] = false;
				$query->query['s'] = false;

				if ( $error == true ){
					//$query->is_404 = true;
					if ( isset( $_POST['result'] ) ) {
						if ( $_POST['result'] == $_COOKIE['result'] ) {
							$_COOKIE[zm_get_option( 'search_captcha_cookie' )] = 1;
							setcookie( zm_get_option( 'search_captcha_cookie' ), 1, time() + zm_get_option( 'search_captcha_time' ), '/' );
							echo '<script>location.reload();</script>';
						}
					}

					$num1 = rand( 1,10 );
					$num2 = rand( 1,10 );
					$result = $num1+$num2;
					$_COOKIE['result'] = $result;
					setcookie( 'result', urldecode($result), time() + zm_get_option( 'search_captcha_time' ), '/' );
					?>
					<?php get_header(); ?>
					<title><?php _e( '搜索验证', 'begin' ); ?><?php connector(); ?><?php if ( ! zm_get_option( 'blog_name' ) ) {bloginfo('name');} ?></title>
					<?php be_back_img(); ?>
					<div class="be-search-captcha-box">
						<div class="be-search-captcha fd">
							<div class="be-search-captcha-tip"><?php _e( '输入答案查看搜索结果', 'begin' ); ?></div>
							<form action="" method="post" autocomplete="off">
								<?php echo $num1; ?> + <?php echo $num2; ?> = <input type="text" name="result" required autofocus />
								<button type="submit"><?php _e( '确定', 'begin' ); ?></button>
							</form>
							<a class="be-search-captcha-btu" href="<?php echo esc_url( home_url('/') ); ?>"><?php _e( '返回首页', 'begin' ); ?></a>
						</div>
					</div>
					<?php
					get_footer(); exit;
				}
			}
		}
	}

	if ( ! current_user_can( 'administrator' ) ) {
		add_action( 'parse_query', 'be_search_captcha' );
		if ( ! isset( $_COOKIE['result'] )) {
			$_COOKIE['result'] = 0;
		}
	}
}