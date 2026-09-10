<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// login_info
function login_info() { ?>
<div id="user-profile">
<?php
	global $user_identity,$user_level;
	wp_get_current_user();
	if ( $user_identity ) { ?>
		<div class="user-box">
			<?php if ( get_option( 'show_avatars' ) ) { ?>
				<div class="user-my load">
					<?php if ( zm_get_option( 'cache_avatar' ) ) { ?>
						<?php global $userdata; wp_get_current_user(); echo begin_avatar( $userdata->user_email, 96, '', $user_identity ); ?>
					<?php } else { ?>
						<?php global $userdata; wp_get_current_user(); be_avatar_user(); ?>
					<?php } ?>
					<span class="hi-user"><a href="javascript:void(0)"><?php echo $user_identity; ?>，<?php if ( ! zm_get_option('user_url') == '' ) { ?><?php _e( '个人中心', 'begin' ); ?><?php } else { ?><?php _e( '您好！', 'begin' ); ?><?php } ?></a></span>
				</div>
			<?php } else { ?>
				<span class="hi-user show-avatars"><a href="javascript:void(0)"><i class="be be-personoutline"></i><span class="show-avatars-user"><?php echo $user_identity; ?>，<?php _e( '您好！', 'begin' ); ?></span></a></span>
			<?php } ?>

			<div class="user-info">
				<div class="arrow-up"></div>
				<div class="user-info-min">
					<?php if ( current_user_can( 'manage_options' ) ) { ?>
						<a href="<?php echo admin_url(); ?>" rel="external nofollow" target="_blank" title="<?php _e( '后台管理', 'begin' ); ?>">
					<?php } else { ?>
						<?php if ( zm_get_option( 'no_admin' ) ) { ?>
							<?php if ( !zm_get_option('user_url') == '' ) { ?>
								<a href="<?php echo get_permalink( zm_get_option('user_url') ); ?>" rel="external nofollow" title="<?php _e( '个人中心', 'begin' ); ?>">
							<?php } else { ?>
								<a href="javascript:;" rel="external nofollow">
							<?php } ?>
						<?php } else { ?>
							<a href="<?php echo admin_url(); ?>" rel="external nofollow" title="<?php _e( '后台管理', 'begin' ); ?>">
						<?php } ?>

					<?php } ?>
						<h3 class="fd"><?php echo $user_identity; ?></h3>
						<?php if ( get_option( 'show_avatars' ) ) { ?>
							<div class="usericon fd load">
								<?php if (zm_get_option('cache_avatar')) { ?>
									<?php global $userdata; wp_get_current_user(); echo begin_avatar($userdata->user_email, 96, '', $user_identity); ?>
								<?php } else { ?>
									<?php global $userdata; wp_get_current_user(); be_avatar_user(); ?>
								<?php } ?>
							</div>
						<?php } else { ?>
							<div class="usericon fd load">
								<div class="usericon-place"><i class="be be-timerauto"></i></div>
							</div>
						<?php } ?>
					</a>
					<?php if ( function_exists( 'epd_assets_vip' ) ) { ?>
						<div class="be-vip-userinfo-name"><?php epd_vip_name(); ?></div>
					<?php } ?>

					<?php if ( function_exists( 'epd_assets_vip' ) ) { ?>
						<div class="be-vip-userinfo">
							<?php epd_vip_btu(); ?>
						</div>
						<div class="clear"></div>
					<?php } ?>
					<div class="userinfo fd">
						<p>
							<?php if ( ! zm_get_option( 'user_url' ) == '' ) { ?>
								<a href="<?php echo get_permalink( zm_get_option( 'user_url' ) ); ?>"><?php _e( '个人中心', 'begin' ); ?></a>
							<?php } else { ?>
								<a href="<?php echo home_url( '/' ); ?>user-center<?php if ( zm_get_option( 'page_html' ) ) { ?>.html<?php } ?>"><?php _e( '个人中心', 'begin' ); ?></a>
							<?php } ?>
							<a class="user-logout" href="<?php echo wp_logout_url( get_permalink() ); ?>"><?php _e( '安全退出', 'begin' ); ?></a>
						</p>
						<div class="clear"></div>
					</div>
				</div>
			</div>
		</div>
	<?php } else { ?>
		<?php if ( ! is_user_logged_in() ) { ?>
			<?php if ( ! zm_get_option( 'wel_come' ) == '' ) { ?>
			<div class="greet-top">
				<?php if ( isset( $_COOKIE["comment_author_" . COOKIEHASH] ) && $_COOKIE["comment_author_" . COOKIEHASH] != "" ) { ?>
					<?php printf(__('%s 欢迎回来！'), $_COOKIE["comment_author_" . COOKIEHASH]); ?>
				<?php } else { ?>
					<div class="user-wel"><?php echo stripslashes( zm_get_option('wel_come' ) ); ?></div>
				<?php } ?>
			</div>
			<?php } ?>
		<?php } ?>

	<?php } ?>

	<div class="login-reg login-admin">
		<?php if ( ! zm_get_option( 'user_l' ) == '' ) { ?>
			<?php if ( ! is_user_logged_in() ){ ?>
				<div class="nav-set">
				 	<div class="nav-login-l">
						<a href="<?php echo zm_get_option( 'user_l' ); ?>" rel="external nofollow" target="_blank"><i class="be be-personoutline"></i><?php _e( '登录', 'begin' ); ?></a>
					</div>
				</div>
			<?php } ?>
		<?php } else { ?>
			<?php if ( ! is_user_logged_in() ){ ?>
				<div class="nav-set">
				 	<div class="nav-login">
						<div class="show-layer<?php echo cur(); ?>"><i class="be be-personoutline"></i><?php _e( '登录', 'begin' ); ?></div>
					</div>
				</div>
			<?php } ?>
		<?php } ?>

		<?php if ( zm_get_option( 'menu_reg' ) && ! is_user_logged_in() && get_option( 'users_can_register' ) ) { ?>
			<div class="nav-set">
				 <div class="nav-reg nav-reg-no">
					<a class="hz" href="<?php echo zm_get_option( 'reg_l' ); ?>" rel="external nofollow" target="_blank"><i class="be be-timerauto"></i><?php _e( '注册', 'begin' ); ?></a>
				</div>
				<div class="clear"></div>
			</div>
		<?php } ?>
	</div>
</div>
<?php }

// my-inf
function my_inf() { ?>
<div class="m-personal">
	<div class="personal-bg">
		<img src="<?php echo zm_get_option( 'personal_img' ); ?>">
	</div>

	<p class="be-getmonth">
		<?php if ( get_bloginfo( 'language' ) === 'en-US' ) { ?>
			<script type="text/javascript">
			var d, s = "";
			var x = new Array("Sunday", "Monday", "Tuesday","Wednesday","Thursday", "Friday","Saturday");
			d = new Date();
			s+=d.getDate()+" / "+(d.getMonth() + 1)+" / "+d.getFullYear()+"";
			document.writeln(s);
			</script>
		<?php } else { ?>
			<script type="text/javascript">
			var d, s = "";
			var x = new Array("星期日", "星期一", "星期二","星期三","星期四", "星期五","星期六");
			d = new Date();
			s+=d.getFullYear()+"年"+(d.getMonth() + 1)+"月"+d.getDate()+"日 ";
			s+=x[d.getDay()];
			document.writeln(s);
			</script>
		<?php } ?>
	</p>
	<div class="m-logout"><a href="<?php echo wp_logout_url( home_url() ); ?>"><?php _e( '安全退出', 'begin' ); ?></a></div>
	<div class="my-avatar">
		<?php global $current_user, $userdata, $user_identity; wp_get_current_user();
			if ( get_option( 'show_avatars' ) ) {
				echo '<div class="m-img load">';
				if (zm_get_option('cache_avatar')):
					echo begin_avatar($userdata->user_email, 96, '', $user_identity);
				else :
					echo be_avatar_user();
				endif;
				echo '</div>';
			}
			echo '<div class="m-name"><span class="wyc">' . __('欢迎回来！', 'begin' ) . '</strong>' . '</span><br />';
			echo '' . $current_user->display_name . "\n";
			echo '</div>';
		?>
	</div>
	<div class="clear"></div>
</div>
<?php }

// my-data
function my_data() { ?>
<div class="my-info<?php if ( get_bloginfo( 'language' ) === 'en-US' ) { ?> my-info-en<?php } ?>">
	<h4 class="user-info-t user-info-ico"><i class="be be-personoutline"></i><?php _e( '我的信息', 'begin' ); ?></h4>

	<?php global $current_user; wp_get_current_user();
	function user_role() {
		$role = __('未知', 'begin' );
		if ( current_user_can( 'manage_options' ) ) {
			$role= __('管理员', 'begin' );
		}
		if ( current_user_can( 'publish_pages' ) && !current_user_can( 'manage_options' ) ) {
			$role= __('编辑', 'begin' );
		}
		if ( current_user_can( 'publish_posts' ) && !current_user_can( 'publish_pages' ) ) {
			$role= __('作者', 'begin' );
		}
		if ( current_user_can( 'edit_posts' ) && !current_user_can( 'publish_posts' ) ) {
			$role= __('投稿者', 'begin' );
		}
		if ( current_user_can( 'read' ) && !current_user_can( 'edit_posts' ) ) {
			$role= __('订阅者', 'begin' );
		}
		if ( current_user_can( 'vip_roles' ) && !current_user_can( 'edit_posts' ) ) {
			$role= '<span class="user-inf-role-vip">' . zm_get_option('roles_name') . ' <i class="cx cx-svip"></i></span>';
		}
		return $role;
	}
	echo '<ul class="user-inf-box">';
	if ( function_exists( 'epd_vip_name' ) ) {
		echo '<li><strong>' . __('角色', 'begin' ) . '</strong>';
		echo '<span class="be-ed-vip-name">';
		epd_vip_name();
		echo '</span>';
		echo '</li>';
	} else {
		echo '<li><strong>' . __('角色', 'begin' ) . '</strong>' . user_role() . '</li>';
	}
	echo '<li><strong>' . __( '评论', 'begin' ) . '</strong>' . $comments = get_comments( array( 'user_id' => $current_user->ID, 'count' => true ) ) . '</li>';
	echo '<li><strong>' . __( '文章', 'begin' ) . '</strong>' . count_user_posts( $current_user->ID, 'post', false ) . '</li>';
	echo '</ul>';
	?>

	<ul class="user-inf-box">
		<li>
			<strong><?php _e( '我的站点', 'begin' ); ?></strong>
			<?php
				global $userdata; 
				if ( !$userdata->user_url) {
					echo  __( '无', 'begin' );
				} else {
					wp_get_current_user(); 
					echo esc_attr( $userdata->user_url );
				}
			?>
		</li>
		<li>
			<strong><?php _e( '注册时间', 'begin' ); ?></strong>
			<?php user_registered(); ?>
		</li>
		<li>
			<strong><?php _e( '最后登录', 'begin' ); ?></strong>
			<?php
				global $userdata;
				if ( get_user_meta( $userdata->ID, 'last_login', true ) ) {
					wp_get_current_user();
					get_last_login( $userdata->ID );
				} else {
					echo  __( '暂无记录', 'begin' );
				}
			?>
		</li>
	</ul>
	<div class="clear"></div>
	<?php
		global $userdata;
		if ( ! get_user_meta( $userdata->ID, 'last_login', true ) ) {
			echo '<div class="modify-email">请修改邮箱及密码，之后可以用邮箱登录本站</div>';
		}
	?>
</div>
<?php }

// my-comment
function my_comment() { ?>
<ul class="my-comment">
	<?php 
	$no_comments = false;
	$author_id = get_current_user_id();;
	$comments_query = new WP_Comment_Query();
	$comments = $comments_query->query( array_merge( array( 'number' => 20, 'order' => 'DESC', 'status' => 'approve', 'type' => 'comments', 'post_status' => 'publish', 'user_id' =>$author_id ) ) );
	if ( $comments ) : foreach ( $comments as $comment ) : ?>
	<li class="bkc">
		<a class="my-posts-li" href="<?php echo get_permalink($comment->comment_post_ID); ?>#anchor-comment-<?php echo $comment->comment_ID; ?>" title="发表在：<?php echo get_the_title($comment->comment_post_ID); ?>" rel="external nofollow">
			<?php echo convert_smilies($comment->comment_content); ?>
		</a>
	</li>
	<?php endforeach; else : ?>
		<li><?php _e('暂无留言', 'begin'); ?></li>
		<?php $no_comments = true;
	endif; ?>
</ul>
<?php }

// my-post
function my_post() { ?>
<?php wp_enqueue_script( 'my-post-ajax', get_template_directory_uri() . '/js/my-post-ajax.js', array(), version, true); ?>
<div class="my-post">
	<table cellspacing="0" cellpadding="0" border="0">
		<thead>
			<tr>
				<td width="800"><?php _e( '标题', 'begin' ); ?></td>
				<td class="fj" width="120"><?php _e( '日期', 'begin' ); ?></td>
				<td class="fj" width="100"><?php _e( '浏览', 'begin' ); ?></td>
				<td class="fj" width="120"><?php _e( '分类', 'begin' ); ?></td>
				<td class="fj" width="100"><?php _e( '评论', 'begin' ); ?></td>
				<td class="fj" width="80"><?php _e( '状态', 'begin' ); ?></td>
			</tr>
		</thead>

		<tbody>
			<?php
			$userinfo=get_userdata(get_current_user_id());
			$user_id= $userinfo->ID;
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			$args = array(
				'post_type' => array('post','video','picture','bulletin','tao'),
				'author' => $user_id,
				'posts_per_page' =>'20',
				'post_status' => array('publish', 'pending'),
				'orderby' => 'date',
				'paged' => $paged
			);
			query_posts($args);
			if (have_posts()) : while (have_posts()) : the_post();
				switch(get_post(get_the_ID())->post_status){
					case 'publish':
					$status='' . sprintf(__( '通过', 'begin' )) . '';
					break;
					case 'pending':
					$status='<span>' . sprintf(__( '待审', 'begin' )) . '</span>';
					break;
				}
			?>

			<tfoot>
				<tr>
					<td width="800"><?php the_title( sprintf( '<a class="my-posts-li" href="%s" target="_blank" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?></td>
					<td width="120" class="tc fj"><?php the_time( 'Y-m-d' ); ?></td>
					<td width="100" class="tc fj"><?php views_print(); ?></td>
					<td width="120" class="tc fj">
						<?php echo get_the_term_list(get_the_ID(), 'category'); ?>
						<?php echo get_the_term_list(get_the_ID(), 'videos'); ?>
						<?php echo get_the_term_list(get_the_ID(), 'gallery'); ?>
						<?php echo get_the_term_list(get_the_ID(), 'notice'); ?>
						<?php echo get_the_term_list(get_the_ID(), 'taobao'); ?>
					</td>
					<td width="100" class="tc fj"><?php echo get_post(get_the_ID())->comment_count; ?></td>
					<td width="80" class="tc fj"><?php echo $status; ?></td>
				</tr>
			</tfoot>
			<?php endwhile; endif; ?>
		</tbody>
	</table>
</div>
<div id="pagination" class="noajx"><?php next_posts_link(__( '更多', 'begin' )); ?></div>
<div id="loadmore"><div class="ball-pulse"><div></div><div></div><div></div></div></div>
<?php wp_reset_query(); ?>
<div class="clear"></div>
<?php }

// my-favorite
function my_favorite() { ?>
	<?php if (zm_get_option( 'shar_favorite' ) ) { ?>
		<div class="my-favorite">
			<?php Begin_Favorite_Posts::init()->display_favorites( $post_type = 'all', $user_id = false, $limit = 1000, $show_remove = true ); ?>
		</div>
	<?php } ?>
<?php }

// my-tou
function my_tou() { ?>
<?php if ( zm_get_option('tou_url') == '' ) { ?>
<?php } else { ?>
<div class="m-tou">
	<p><a href="<?php echo get_permalink( zm_get_option('tou_url') ); ?>" target="_blank"><i class="be be-editor ri"></i><?php _e( '点击跳转到投稿页面', 'begin' ); ?></a></p>
</div>
<?php } ?>
<?php }