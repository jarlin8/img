<?php
/*
用户信息
*/

global $current_user, $wp_roles;
$error = array();
if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'update-user' ) {
	if ( !empty($_POST['pass1'] )) {
		if ( $_POST['pass1'] == $_POST['pass2'] )
			wp_update_user( array( 'ID' => $current_user->ID, 'user_pass' => esc_attr( $_POST['pass1'] ) ) );
		else
		$error[] = __('错误：密码不一致，请输入正确的重复密码！', 'begin');
	}

	if ( !empty( $_POST['url'] ) )
		wp_update_user( array( 'ID' => $current_user->ID, 'user_url' => esc_url( $_POST['url'] ) ) );

	if ( !empty( $_POST['email'] ) ){
		if (!is_email(esc_attr( $_POST['email'] )))
			$error[] = __('错误：您输入的电子邮件地址不正确！', 'begin');
		elseif( email_exists(esc_attr( $_POST['email'] ) ) && email_exists(esc_attr( $_POST['email'] )) != $current_user->ID )
			$error[] = __('错误：该电子邮件已被其他用户使用！', 'begin');
		else{
			wp_update_user( array ('ID' => $current_user->ID, 'user_email' => esc_attr( $_POST['email'] )));
		}
	}

	// if ( !empty( $_POST['nickname'] ) )
		// update_user_meta($current_user->ID, 'nickname', esc_attr( $_POST['nickname'] ) );

	if ( !empty( $_POST['nickname'] ) ) {
		wp_update_user( array ('ID' => $current_user->id, 'display_name' => esc_attr( $_POST['nickname'] ) ) ) ;
		update_user_meta($current_user->id, 'nickname', esc_attr( $_POST['nickname'] ) );
		update_user_meta($current_user->id, 'display_name', esc_attr( $_POST['nickname'] ) );
	}

	if ( !empty( $_POST['description'] ) )
		update_user_meta( $current_user->ID, 'description', esc_attr( $_POST['description'] ) );

	if ( !empty( $_POST['userimg'] ) )
		update_user_meta( $current_user->ID, 'userimg', esc_attr( $_POST['userimg'] ) );

	if ( count($error) == 0 ) {
		do_action('edit_user_profile_update', $current_user->ID);
		wp_redirect( get_permalink().'?updated=true' );
		exit;
	}
}
?>
<fieldset class="bk">
<legend><h4 class="update-user-t"><?php _e( '修改个人信息', 'begin' ); ?></h4></legend>
<div id="my-profile" class="user-profile" role="main">
	<?php if(is_user_logged_in()){ ?>
		<div class="profile-message">
			<?php if ( isset( $_GET['updated'] ) ) : ?>
				<legend><p class="profile-error profile-updated"><?php _e( '个人信息已更新！', 'begin' ); ?></p></legend>
			<?php endif; ?>
			<?php if ( count($error) > 0 ) echo '<p class="profile-error">' . implode("<br />", $error) . '</p>'; ?>
		</div>
		<p class="clear"></p>

		<form name="profile" id="your-profile" action="" method="post" enctype="multipart/form-data">
			<p class="fp form-username">
				<label for="nickname"><?php _e( '用户名', 'begin' ); ?></label>
				<input class="text-input bky" name="user_login" type="text" id="user_login" disabled="disabled" value="<?php echo $current_user->user_login; ?>" /><span class="description"><?php _e( '不可更改', 'begin' ); ?></span>
			</p>
			<p class="fp form-username">
				<label for="nickname"><?php _e( '昵称', 'begin' ); ?><i class="be be-star"></i></label>
				<input class="text-input dah" name="nickname" type="text" id="nickname" value="<?php the_author_meta( 'nickname', $current_user->ID ); ?>" />
			</p>
			<p class="fp form-password">
				<label for="pass1"><?php _e( '新密码', 'begin' ); ?></label>
				<input class="text-input user_pwd1 dah" name="pass1" type="password" id="pass1" />
				<span class="togglepass"><i class="be be-eye"></i></span>
			</p>
			<p class="fp form-password">
				<label for="pass2"><?php _e( '重复密码', 'begin' ); ?></label>
				<input class="text-input user_pwd2 dah" name="pass2" type="password" id="pass2" />
				<span class="togglepass"><i class="be be-eye"></i></span>
			</p>
			<p class="fp form-email">
				<label for="email"><?php _e( '电子邮件', 'begin' ); ?><i class="be be-star"></i></label>
				<input class="text-input dah" name="email" type="text" id="email" value="<?php the_author_meta( 'user_email', $current_user->ID ); ?>" />
			</p>
			<p class="fp form-userimg">
				<label for="url"><?php _e( '我的图片', 'begin' ); ?></label>
				<input class="text-input dah" name="userimg" type="text" id="userimg" value="<?php the_author_meta( 'userimg', $current_user->ID ); ?>" />
			</p>
			<p class="fp form-url">
				<label for="url"><?php _e( '我的网站', 'begin' ); ?></label>
				<input class="text-input dah" name="url" type="text" id="url" value="<?php the_author_meta( 'user_url', $current_user->ID ); ?>" />
			</p>
			<p class="fp form-textarea">
				<label for="description"><?php _e( '个人说明', 'begin' ); ?></label>
				<textarea name="description" id="description" class="des-textarea dah" rows="3"><?php the_author_meta( 'description', $current_user->ID ); ?></textarea>
			</p>

			<p class="fp form-textarea">
				<?php _e( '设置头像', 'begin' ); ?>
			</p>

			<p class="form-avatar load">
				<?php 
					global $current_user; wp_get_current_user();
					if (zm_get_option('cache_avatar')):
						echo begin_avatar( $current_user->user_email, 96, '', $current_user->display_name);
					else :
						echo get_avatar( $current_user->user_email, 96, '', $current_user->display_name);
					endif;
				?>
				<?php do_action('edit_user_profile', $current_user); ?>

				<?php if ( get_option( 'show_avatars' ) ) { ?>
					<div class="my-gravatar-apply">
						<?php 
							if ( zm_get_option( 'gravatar_url' ) == 'zh' ) {
								$gravatarurl = 'https://cravatar.cn/';
							} else {
								$gravatarurl = 'https://cn.gravatar.com/';
							}
							echo '<a class="bk bet-btn" href="' . $gravatarurl . '" rel="external nofollow" target="_blank">' . sprintf(__( '申请头像', 'begin' )) . '</a>';
						?>
					</div>
				<?php } ?>

				<p class="clear"></p>
			</p>
			<p class="form-submit">
				<input name="updateuser" type="submit" id="updateuser" class="submit button dah bet-btn" value="<?php _e( '更新个人信息', 'begin' ); ?>" />
				<?php wp_nonce_field( 'update-user' ) ?>
				<input name="action" type="hidden" id="action" value="update-user" />
			</p>
		</form>
		<script type="text/javascript">
			if ( window.history.replaceState ) {
				window.history.replaceState( null, null, window.location.href );
			}

			document.getElementById("be-local-avatar").onchange = function() {
				document.getElementById("userfile").value = document.getElementById("be-local-avatar").value;
			}

			jQuery(document).ready(function($){
				jQuery('.profile-updated').show().delay(2000).fadeOut();
			});
		</script>
	<?php } else { ?>
		<?php
			wp_redirect( home_url() );
			exit;
		?>
	<?php } ?>
</div>
</fieldset>