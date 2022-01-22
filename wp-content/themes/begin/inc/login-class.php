<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if(!is_user_logged_in()) { ?>
	<?php if ( !zm_get_option('only_social_login') )  { ?>
		<div class="login-tab-product sign da">
			<?php if (zm_get_option('reset_pass') || get_option('users_can_register')) { ?>
				<h2 class="login-tab-hd">
					<?php if ( !get_option('users_can_register') )  { ?>
						<?php if (zm_get_option('reset_pass')) { ?>
							<span class="login-tab-hd-con login-tab-hd-con-a"><a href="javascript:" not="not"><?php _e( '登录', 'begin' ); ?></a></span>
							<span class="login-tab-hd-con login-tab-hd-con-a"><a href="javascript:" not="not"><?php _e( '找回密码', 'begin' ); ?></a></span>
						<?php } ?>
					<?php } else { ?>
						<?php if (zm_get_option('reset_pass')) { ?>
							<span class="login-tab-hd-con"><a href="javascript:" not="not"><?php _e( '登录', 'begin' ); ?></a></span>
							<span class="login-tab-hd-con"><a href="javascript:" not="not"><?php _e( '注册', 'begin' ); ?></a></span>
							<span class="login-tab-hd-con"><a href="javascript:" not="not"><?php _e( '找回密码', 'begin' ); ?></a></span>
						<?php } else { ?>
							<span class="login-tab-hd-con login-tab-hd-con-a"><a href="javascript:" not="not"><?php _e( '登录', 'begin' ); ?></a></span>
							<span class="login-tab-hd-con login-tab-hd-con-a"><a href="javascript:" not="not"><?php _e( '注册', 'begin' ); ?></a></span>
						<?php } ?>
					<?php } ?>
				</h2>
			<?php } ?>
			<div class="login-tab-bd login-dom-display">
			<?php if (zm_get_option('reset_pass') || get_option('users_can_register')) { ?>
			<?php } else { ?>
				<div class="login-logo"><img class="login-logo-b" src="<?php echo zm_get_option('logo_small_b'); ?>" alt="<?php bloginfo( 'name' ); ?>" /></div>
			<?php } ?>
				<div class="login-tab-bd-con login-current">
					<div id="tab1_login" class="tab_content_login">
						<form class="zml-form" action="<?php echo esc_url(LoginAjax::$url_login); ?>" method="post">
							<div class="zml-status"></div>
							<div class="zml-username">
								<div class="zml-username-input zml-ico">
									<?php username_iocn(); ?>
									<input class="input-control dah bk" type="text" name="log" placeholder="<?php _e( '用户名', 'begin' ); ?>" onfocus="this.placeholder=''" onblur="this.placeholder='<?php _e( '用户名', 'begin' ); ?>'" />
								</div>
							</div>
							<div class="zml-password">
								<div class="zml-password-label pass-input">
									<div class="togglepass"><i class="be be-eye"></i></div>
								</div>
								<div class="zml-password-input zml-ico">
									<?php password_iocn(); ?>
									<input class="login-pass input-control dah bk" type="password" name="pwd" placeholder="<?php _e( '密码', 'begin' ); ?>" onfocus="this.placeholder=''" onblur="this.placeholder='<?php _e( '密码', 'begin' ); ?>'" autocomplete="off" />
								</div>
							</div>
								<div class="login-form"><?php do_action('login_form'); ?></div>
							<div class="zml-submit">
								<div class="zml-submit-button">
									<input type="submit" name="wp-submit" class="button-primary" value="<?php _e( '登录', 'begin' ); ?>" tabindex="100" />
									<input type="hidden" name="login-ajax" value="login" />
									<input type="hidden" name="security" value="<?php echo wp_create_nonce( 'security_nonce' );?>">
									<input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
								</div>
								<div class="rememberme pretty success">
									<input type="checkbox" name="rememberme" value="forever" checked="checked" checked />
									<label for="rememberme" type="checkbox"/>
										<i class="mdi" data-icon=""></i>
										<em><?php _e( '记住我的登录信息', 'begin' ); ?></em>
									</label>
								</div>
							</div>
						</form>
					</div>
				</div>

				<?php if( get_option('users_can_register')): ?>
				<div class="login-tab-bd-con">
					<div id="tab2_login" class="tab_content_login">
						<div class="zml-register">
							<?php register_form(); ?>
						</div>
					</div>
				</div>
				<?php endif; ?>

				<?php if (zm_get_option('reset_pass')) { ?>
				<div class="login-tab-bd-con">
					<div id="tab3_login" class="tab_content_login">
						<form class="zml-remember" action="<?php echo esc_attr(LoginAjax::$url_remember) ?>" method="post">
							<div class="zml-status"></div>
							<div class="zml-remember-email">  
								<?php _e( '输入用户名或电子邮件', 'begin' ); ?>
								<?php $msg = ''; ?>
								<input type="text" name="user_login" class="input-control remember dah bk" value="<?php echo esc_attr($msg); ?>" onfocus="if(this.value == '<?php echo esc_attr($msg); ?>'){this.value = '';}" onblur="if(this.value == ''){this.value = '<?php echo esc_attr($msg); ?>'}" />
								<?php do_action('lostpassword_form'); ?>
							</div>
							<div class="zml-submit-button">
								<input type="submit" value="<?php _e( '获取新密码', 'begin' ); ?>" class="button-primary" />
								<input type="hidden" name="login-ajax" value="remember" />
							</div>
							<div class="zml-register-tip"><?php _e( '重置密码链接通过邮箱发送给您', 'begin' ); ?></div>
						</form>
					</div>
				</div>
				<?php } ?>
			</div>
		</div>

	<?php } else { ?>
		<div class="only-social da">
			<?php if ( zm_get_option('user_back') ) { ?>
				<div class="author-back"><img src="<?php echo zm_get_option('user_back'); ?>" alt="bj"/></div>
			<?php } ?>
			<h4 class="only-social-title bgt"><?php _e( '加入我们', 'begin' ); ?></h4>
			<div class="only-social-but"><?php do_action('login_form'); ?></div>
			<div class="only-social-txt"><?php _e( '仅开放社交账号注册登录', 'begin' ); ?></div>
		</div>
	<?php } ?>

<?php } else { ?>
	<?php logged_manage(); ?>
<?php } ?>