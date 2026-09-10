<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// logged_manage
function logged_manage() { ?>
<div class="sidebox">
	<?php if ( zm_get_option('user_back') ) { ?>
		<div class="author-back"><img src="<?php echo zm_get_option('user_back'); ?>" alt="bj"></div>
	<?php } ?>

	<div class="usericon load">
		<?php if (current_user_can( 'manage_options' )) { ?>
			<a href="<?php echo admin_url(); ?>" rel="external nofollow" target="_blank" title="<?php _e( '后台管理', 'begin' ); ?>">
		<?php } else { ?>
			<?php if ( !zm_get_option('user_url') == '' ) { ?>
				<a href="<?php echo get_permalink( zm_get_option('user_url') ); ?>" rel="external nofollow">
			<?php } else { ?>
				<a href="javascript:;" rel="external nofollow">
			<?php } ?>
		<?php } ?>
			<?php 
				global $user_identity, $userdata;
				if ( zm_get_option( 'cache_avatar' ) ) {
					echo begin_avatar( $userdata->user_email, 96, '', $user_identity );
				} else {
					be_avatar_user();
				}
			?>
		</a>
	</div>
	<h4>
		<?php global $current_user; wp_get_current_user();
			echo '<a href="' . admin_url() . '" rel="external nofollow">';
			echo '<div class="ml-name">';
			echo '' . $current_user->display_name . "\n";
			echo '</div>';
			echo '</a>';
		?>
	</h4>

	<?php if ( function_exists( 'epd_assets_vip' ) ) { ?>
		<div class="be-vip-userinfo-name"><?php epd_vip_name(); ?></div>
	<?php } ?>

	<?php if ( function_exists( 'epd_assets_vip' ) ) { ?>
		<div class="be-vip-userinfo">
			<?php epd_vip_btu(); ?>
		</div>
	<?php } ?>

	<div class="userinfo">
		<div>
			<?php if (zm_get_option('user_url')) { ?>
				<a class="user-url" href="<?php echo get_permalink( zm_get_option( 'user_url' ) ); ?>" target="_blank"><?php _e( '个人中心', 'begin' ); ?></a>
			<?php } ?>
			<a href="<?php echo wp_logout_url( get_permalink() ); ?>"><?php _e( '安全退出', 'begin' ); ?></a>
			<div class="clear"></div>
		</div>
	</div>
</div>
<?php }

// login_but
function login_but() { ?>
	<?php if ( zm_get_option( 'menu_login' ) && ( ! wp_is_mobile() ) ) { ?>
		<div class="menu-login-box">
			<?php if ( is_user_logged_in() ) { ?>
				<span class="menu-login"><?php login_info(); ?></span>
			<?php } else { ?>
				<span class="menu-login menu-login-btu<?php nav_ace(); ?><?php if ( zm_get_option( 'menu_reg' ) && get_option('users_can_register') ) { ?> menu-login-reg-btu<?php } ?>"><?php login_info(); ?></span>
			<?php } ?>
		</div>
	<?php } ?>
<?php }

// register-form
function register_form() { ?>
	<form class="zml-register-form" action="<?php echo esc_attr(LoginAjax::$url_register); ?>" autocomplete="off" method="post">
		<div>
			<div class="zml-username zml-ico">
				<input type="text" name="user_login" class="user_name input-control" size="20" required="required" autocomplete="new-password" placeholder="<?php _e( '用户名', 'begin' ); ?>" onfocus="this.placeholder=''" onblur="this.placeholder='<?php _e( '用户名', 'begin' ); ?>*'" />
			</div>
			<div class="zml-email zml-ico">
				<input type="text" name="user_email" class="user_email input-control" size="25" required="required" autocomplete="new-password" placeholder="<?php _e( '邮箱', 'begin' ); ?>" onfocus="this.placeholder=''" onblur="this.placeholder='<?php _e( '邮箱', 'begin' ); ?> *'" />
			</div>
			<div class="login-form">
				<?php do_action('be_register_form'); ?>
				<?php do_action('zml_register_form'); ?>
			</div>
			<div class="submit zml-submit-button<?php if ( ! zm_get_option( 'slider_captcha' ) ) { ?> no-captcha<?php } ?>">
				<?php if ( zm_get_option( 'slider_captcha' ) ) { ?>
					<button type="button" class="reg-button button-reg-no<?php echo cur(); ?>"><?php _e( '注册', 'begin' ); ?></button>
					<div class="reg-notice-box">
						<span class="pretty success">
							<input type="checkbox" name="rememberme" value="forever" class="noticecheckbox" />
							<label for="rememberme" type="checkbox">
								<i class="mdi" data-icon=""></i>
								<em><?php _e( '我同意', 'begin' ); ?><?php if ( ! zm_get_option( 'reg_notice_url' ) ) { ?><?php echo zm_get_option( 'reg_notice_text' ); ?><?php } ?></em>
							</label>
						</span>

						<span class="reg-notice">
							<?php if ( zm_get_option( 'reg_notice_url' ) ) { ?>
								<a href="<?php echo get_permalink( zm_get_option( 'reg_notice_url' ) ); ?>" target="_blank" rel="external nofollow"><?php echo zm_get_option( 'reg_notice_text' ); ?></a>
							<?php } ?>
						</span>
						<div class="reg-notice-agree"><?php _e( '必须选择同意', 'begin' ); ?><?php echo zm_get_option( 'reg_notice_text' ); ?></div>
					</div>
				<?php } else { ?>
					<input type="submit" name="wp-submit" class="button-primary<?php echo cur(); ?>" value="<?php _e( '注册', 'begin' ); ?>" tabindex="100" />
				<?php } ?>
				<div class="zml-status"></div>
			</div>
			<input type="hidden" name="login-ajax" value="register" />
		</div>
	</form>
<?php }

// login-form
function login_form() { ?>
	<form class="zml-form" action="<?php echo esc_url(LoginAjax::$url_login); ?>" method="post">
		<div class="zml-username">
			<div class="zml-username-input zml-ico">
				<input class="input-control" type="text" name="log" placeholder="<?php _e( '用户名', 'begin' ); ?>" onfocus="this.placeholder=''" onblur="this.placeholder='<?php _e( '用户名', 'begin' ); ?>'" />
			</div>
		</div>
		<div class="zml-password">
			<div class="zml-password-label pass-input">
				<div class="togglepass"><i class="be be-eye"></i></div>
			</div>
			<div class="zml-password-input zml-ico">
				<input class="login-pass input-control" type="password" name="pwd" placeholder="<?php _e( '密码', 'begin' ); ?>" onfocus="this.placeholder=''" onblur="this.placeholder='<?php _e( '密码', 'begin' ); ?>'" />
			</div>
		</div>
		<div class="login-form">
			<?php do_action('be_login_form'); ?>
			<?php do_action('login_form'); ?>
		</div>
		<div class="zml-submit">
			<div class="zml-submit-button">
				<?php if ( zm_get_option( 'slider_captcha' ) ) { ?>
					<button type="button" class="button-primary<?php echo cur(); ?>"><?php _e( '登录', 'begin' ); ?></button>
				<?php } else { ?>
					<input type="submit" name="wp-submit" class="button-primary<?php echo cur(); ?>" value="<?php _e( '登录', 'begin' ); ?>" tabindex="13" />
				<?php } ?>
				<input type="hidden" name="login-ajax" value="login" />
				<input type="hidden" name="security" value="<?php echo wp_create_nonce( 'security_nonce' ); ?>">
				<input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
				<div class="zml-status"></div>
			</div>
			<div class="rememberme pretty success">
				<input type="checkbox" name="rememberme" value="forever" checked="checked" checked />
				<label for="rememberme" type="checkbox">
					<i class="mdi" data-icon=""></i>
					<em><?php _e( '记住我的登录信息', 'begin' ); ?></em>
				</label>
			</div>
		</div>
	</form>
<?php }

// forget-form
function forget_form() { ?>
	<form class="zml-remember" action="<?php echo esc_attr(LoginAjax::$url_remember) ?>" autocomplete="off" method="post">
		<div class="zml-remember-email">
			<div class="zml-remember-t"><?php _e( '输入用户名或邮箱', 'begin' ); ?></div>
			<?php $msg = ''; ?>
			<input type="text" name="user_login" class="input-control remember" value="<?php echo esc_attr($msg); ?>" onfocus="if (this.value == '<?php echo esc_attr($msg); ?>'){this.value = '';}" onblur="if (this.value == ''){this.value = '<?php echo esc_attr($msg); ?>'}" tabindex="1" />
			<?php do_action('be_lostpassword_form'); ?>
		</div>
		<div class="zml-submit-button">
			<?php if ( zm_get_option( 'slider_captcha' ) ) { ?>
				<button type="button" class="button-primary<?php echo cur(); ?>"><?php _e( '获取新密码', 'begin' ); ?></button>
			<?php } else { ?>
				<input type="submit" tabindex="15" value="<?php _e( '获取新密码', 'begin' ); ?>" class="button-primary" />
			<?php } ?>
			<input type="hidden" name="login-ajax" value="remember" />
			<div class="zml-status"></div>
		</div>
		<div class="zml-register-tip"><?php _e( '重置密码链接通过邮箱发送给您', 'begin' ); ?></div>
	</form>
<?php }

function reg_logo() {
	if ( !zm_get_option( 'site_sign' ) || ( zm_get_option( 'site_sign' ) == 'logo_small' ) ) {
		$logourl = zm_get_option( 'logo_small_b' );
	}

	if ( zm_get_option( 'site_sign' ) == 'logos' ) {
		$logourl = zm_get_option( 'logo' );
	}

	if ( zm_get_option( 'site_sign' ) !== 'no_logo' ) {
		echo '<div class="template-reg-logo">';
		echo '<img src="' . $logourl . '" alt="' . get_bloginfo( 'name' ) . '">';
		echo '</div>';
	}

	if ( zm_get_option( 'site_sign' ) == 'no_logo' ) {
		echo '<div class="template-reg-logo">';
		echo '<div class="template-reg-name">';
		echo get_bloginfo( 'name' );
		echo '</div>';
		echo '</div>';
	}
}

function only_social() { ?>
	<div class="only-social">
		<?php if ( zm_get_option('user_back') ) { ?>
			<div class="author-back"><img src="<?php echo zm_get_option('user_back'); ?>" alt="bj"></div>
		<?php } ?>
		<h4 class="only-social-title"><?php _e( '加入我们', 'begin' ); ?></h4>

		<?php if ( ! get_option('users_can_register') ) { ?>
			<div class="only-social-but users-can-register"><h4>本站暂未开放注册</h4>设置 → 常规 → 常规选项<br />成员资格 → 勾选“任何人都可以注册”</div>
		<?php } else { ?>
			<div class="only-social-but"><?php do_action('be_login_form'); ?></div>
			<div class="only-social-txt"><?php _e( '仅开放社交账号注册登录', 'begin' ); ?></div>
			<?php if ( zm_get_option( 'reg_notice_url' ) ) { ?>
				<div class="reg-notice<?php if ( zm_get_option( 'reg_notice_m' ) ) { ?> reg-notice-m<?php } ?>">
					<a href="<?php echo get_permalink( zm_get_option( 'reg_notice_url' ) ); ?>" target="_blank" rel="external nofollow"><?php echo zm_get_option( 'reg_notice_text' ); ?></a>
				</div>
			<?php } ?>
		<?php } ?>
	</div>
	<?php 
}
// reg pages
function reg_pages() { ?>
	<div class="login-reg-box">
		<div class="reg-main<?php if ( is_user_logged_in() ) { ?> reg-main-bg<?php } ?><?php if ( !zm_get_option('only_social_login') && !is_user_logged_in()) { ?> wp-login-reg-main<?php } ?>">
			<?php if ( ! is_user_logged_in() ) { ?>
				<div class="reg-sign sign">
					<?php if ( ! zm_get_option( 'only_social_login' ) ) { ?>
						<?php if ( ! zm_get_option( 'reg_content_img' ) || zm_get_option( 'no_reg_content_img' ) ) { ?>
						<div class="reg-content-box reg-sign-flex reg-sign-flex-l">
						<?php } else { ?>
						<div class="reg-content-box reg-sign-flex reg-sign-flex-l" style="background: url(<?php echo zm_get_option( 'reg_content_img' ); ?>) no-repeat center / cover;">
						<?php } ?>
							<div class="reg-sign-glass"></div>
							<div class="reg-content-sign">
								<?php reg_logo(); ?>
								<div class="clear"></div>
								<div class="user-login-t-box">
									<?php if ( get_option('users_can_register') ) { ?>
										<h4 class="user-login-t register-box<?php if ( ! zm_get_option( 'reg_above' ) ) { ?> conceal<?php } ?>"><?php _e( '加入我们', 'begin' ); ?></h4>
									<?php } ?>
									<h4 class="user-login-t login-box<?php if ( zm_get_option( 'reg_above' ) ) { ?> conceal<?php } ?>"><?php _e( '立即登录', 'begin' ); ?></h4>
									<?php if ( zm_get_option('reset_pass') ) { ?>
										<h4 class="user-login-t forget-box conceal"><?php _e( '找回密码', 'begin' ); ?></h4>
									<?php } ?>
									<div class="clear"></div>
								</div>

								<?php if ( get_option( 'users_can_register' ) ) { ?>
									<div class="reg-content fd register-box<?php if ( ! zm_get_option('reg_above') ) { ?> conceal<?php } ?>">

										<?php if ( zm_get_option( 'reg_clause_only' ) ) { ?>
											<?php echo stripslashes( zm_get_option('reg_clause') ); ?>
										<?php } else { ?>
											<?php
												$page_data = get_page( zm_get_option( 'reg_notice_url' ) );
												if ( $page_data ) {
													echo apply_filters( 'the_content', $page_data->post_content );
												} else {
													echo '未选择页面';
												}
											?>
										<?php } ?>
									</div>
								<?php } ?>

								<div class="reg-content fd login-box<?php if ( zm_get_option( 'reg_above' ) ) { ?> conceal<?php } ?>">
									<?php echo stripslashes( zm_get_option( 'login_clause' ) ); ?>
								</div>

								<?php if ( zm_get_option('reset_pass') ) { ?>
									<div class="reg-content fd forget-box conceal">
										<?php echo stripslashes( zm_get_option( 'reset_clause' ) ); ?>
									</div>
								<?php } ?>

								<div class="signature"><?php bloginfo( 'name' ); ?></div>
							</div>
							<div class="clear"></div>
						</div>

						<div class="zml-register reg-sign-flex reg-sign-flex-r">
							<div class="user-login-box register-box<?php if ( ! zm_get_option('reg_above') ) { ?> conceal<?php } ?>">
								<?php if ( get_option( 'users_can_register' ) ) { ?>
									<?php register_form(); ?>
								<?php } else { ?>
									<div class="users-can-register"><h4>本站暂未开放注册</h4>设置 → 常规 → 常规选项<br />成员资格 → 勾选“任何人都可以注册”</div>
								<?php } ?>
								<div class="reg-login-but be-reg-btu"><?php _e( '登录', 'begin' ); ?></div>
								<div class="clear"></div>
							</div>

							<div class="user-login-box login-box<?php if ( zm_get_option('reg_above') ) { ?> conceal<?php } ?>">
								<?php login_form(); ?>
								<?php if ( get_option('users_can_register') ) { ?>
									<div class="reg-login-but be-login-btu"><?php _e( '注册', 'begin' ); ?></div>
								<?php } ?>
								<?php if ( zm_get_option('reset_pass') ) { ?>
									<div class="be-forget-btu"><?php _e( '找回密码', 'begin' ); ?></div>
								<?php } ?>
								<div class="clear"></div>
							</div>

							<?php if ( zm_get_option('reset_pass') ) { ?>
								<div class="user-login-box forget-box conceal">
									<?php forget_form(); ?>
									<div class="reg-login-but be-reg-login-btu"><?php _e( '登录', 'begin' ); ?></div>
									<div class="clear"></div>
								</div>
							<?php } ?>

							<div class="clear"></div>
						</div>
					<?php } else { ?>
						<?php only_social(); ?>
					<?php } ?>
				</div>
			<?php } else { ?>
				<?php logged_manage(); ?>
			<?php } ?>
		</div>
	</div>
<?php }

// be_login_reg
function be_login_reg() { ?>
	<div class="login-reg-box">
		<div class="reg-main<?php if ( is_user_logged_in() ) { ?> reg-main-bg<?php } ?><?php if ( !zm_get_option('only_social_login') && !is_user_logged_in()) { ?> wp-login-reg-main<?php } ?>">
			<?php if ( ! is_user_logged_in() ) { ?>
				<div class="reg-sign sign">
					<?php if ( ! zm_get_option( 'only_social_login' ) ) { ?>
						<?php if ( ! zm_get_option( 'reg_content_img' ) ) { ?>
						<div class="reg-content-box reg-sign-flex reg-sign-flex-l">
						<?php } else { ?>
						<div class="reg-content-box reg-sign-flex reg-sign-flex-l" style="background: url(<?php echo zm_get_option('reg_content_img'); ?>) no-repeat center / cover;">
						<?php } ?>
							<div class="reg-sign-glass"></div>
							<div class="reg-content-sign">
								<?php reg_logo(); ?>
								<div class="clear"></div>
								<div class="user-login-t-box">
									<?php if ( get_option('users_can_register') ) { ?>
										<h4 class="user-login-t register-box conceal"><?php _e( '加入我们', 'begin' ); ?></h4>
									<?php } ?>
									<h4 class="user-login-t login-box"><?php _e( '立即登录', 'begin' ); ?></h4>
									<?php if ( zm_get_option('reset_pass') ) { ?>
										<h4 class="user-login-t forget-box conceal"><?php _e( '找回密码', 'begin' ); ?></h4>
									<?php } ?>
									<div class="clear"></div>
								</div>

								<?php if ( get_option( 'users_can_register' ) ) { ?>
									<div class="reg-content fd register-box conceal">

										<?php if ( zm_get_option( 'reg_clause_only' ) ) { ?>
											<?php echo stripslashes( zm_get_option('reg_clause') ); ?>
										<?php } else { ?>
											<?php
												$page_data = get_page( zm_get_option( 'reg_notice_url' ) );
												if ( $page_data ) {
													echo apply_filters( 'the_content', $page_data->post_content );
												} else {
													echo '未选择页面';
												}
											?>
										<?php } ?>
									</div>
								<?php } ?>

								<div class="reg-content fd login-box">
									<?php echo stripslashes( zm_get_option( 'login_clause' ) ); ?>
								</div>

								<?php if ( zm_get_option('reset_pass') ) { ?>
									<div class="reg-content fd forget-box conceal">
										<?php echo stripslashes( zm_get_option( 'reset_clause' ) ); ?>
									</div>
								<?php } ?>
								<div class="signature fd"><?php bloginfo( 'name' ); ?></div>
							</div>
							<div class="clear"></div>
						</div>

						<div class="zml-register reg-sign-flex reg-sign-flex-r">
							<?php if ( get_option( 'users_can_register' ) ) { ?>
								<div class="user-login-box register-box conceal">
									<?php register_form(); ?>
									<div class="reg-login-but be-reg-btu"><?php _e( '登录', 'begin' ); ?></div>
									<div class="clear"></div>
								</div>
							<?php } ?>

							<div class="user-login-box login-box">
								<?php login_form(); ?>
								<?php if ( get_option('users_can_register') ) { ?>
									<div class="reg-login-but be-login-btu"><?php _e( '注册', 'begin' ); ?></div>
								<?php } ?>
								<?php if ( zm_get_option('reset_pass') ) { ?>
									<div class="be-forget-btu"><?php _e( '找回密码', 'begin' ); ?></div>
								<?php } ?>
								<div class="clear"></div>
							</div>

							<?php if ( zm_get_option('reset_pass') ) { ?>
								<div class="user-login-box forget-box conceal">
									<?php forget_form(); ?>
									<div class="reg-login-but be-reg-login-btu"><?php _e( '登录', 'begin' ); ?></div>
									<div class="clear"></div>
								</div>
							<?php } ?>

							<div class="clear"></div>
						</div>
					<?php } else { ?>
						<?php only_social(); ?>
					<?php } ?>
				</div>
			<?php } else { ?>
				<?php logged_manage(); ?>
			<?php } ?>
		</div>
		<div class="off-login"></div>
	</div>
<?php }

// AJAX
add_action( 'wp_ajax_load_login_form', 'load_login_form' );
add_action( 'wp_ajax_nopriv_load_login_form', 'load_login_form' );
function load_login_form() {
	echo be_login_reg();
	die();
}

add_action( 'wp_ajax_load_load_login_pages', 'load_login_pages' );
add_action( 'wp_ajax_nopriv_load_login_pages', 'load_login_pages' );
function load_login_pages() {
	echo reg_pages();
	die();
}