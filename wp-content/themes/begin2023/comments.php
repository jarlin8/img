<?php if ( ! zm_get_option( 'close_comments' ) ) { ?>
<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if ( post_password_required() ) {
	return;
}
?>

<?php
	$numPingBacks = 0;
	$numComments = 0;
	foreach ( $comments as $comment )
	if ( get_comment_type() != "comment" ) $numPingBacks++; else $numComments++;
?><!-- 引用 -->

<div id="comments" class="comments-area">
	<?php if ( have_comments() ) : ?>
		<ol class="comment-list">
			<?php wp_list_comments( 'type=comment&callback=begin_comment' ); ?>

			<?php if ( $numPingBacks > 0 ) { ?>
				<div id="trackbacks" class="bk da ms" <?php aos_a(); ?>>
					<h2 class="pingback-title"><span class="dashicons dashicons-share-alt"></span><?php _e( '来自外部的引用', 'begin' ); ?></h2>
					<ul class="track">
						<?php foreach ( $comments as $comment ) : ?>
						<?php $comment_type = get_comment_type(); ?>
						<?php if ( $comment_type != 'comment' ) { ?>
							<li class="pingback-link"><span class="dashicons dashicons-buddicons-groups"></span><?php comment_author_link() ?></li>
						<?php } ?>
						<?php endforeach; ?>
			 		</ul>
				</div>
			<?php } ?>
		</ol><!-- .comment-list -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<?php if ( zm_get_option( 'infinite_comment' ) ) { ?><div class="scroll-links"><?php the_comments_navigation(); ?></div><?php } ?>
			<nav class="comment-navigation" <?php aos_a(); ?>>
				<div class="pagination">
					<?php 
						if (wp_is_mobile()) {
							the_comments_pagination( array(
								'mid_size'  => 0,
								'prev_text' => '<i class="be be-arrowleft"></i>',
								'next_text' => '<i class="be be-arrowright"></i>',
								'before_page_number' => '<span class="screen-reader-text">'.sprintf(__( '第', 'begin' )).' </span>',
								'after_page_number'  => '<span class="screen-reader-text"> '.sprintf(__( '页', 'begin' )).'</span>',
							) );
						} else {
							the_comments_pagination( array(
								'mid_size'  => 1,
								'prev_text' => '<i class="be be-arrowleft"></i>',
								'next_text' => '<i class="be be-arrowright"></i>',
								'before_page_number' => '<span class="screen-reader-text">'.sprintf(__( '第', 'begin' )).' </span>',
								'after_page_number'  => '<span class="screen-reader-text"> '.sprintf(__( '页', 'begin' )).'</span>',
							) );
						}
					?>
				</div>
			</nav>
			<div class="clear"></div>
		<?php endif; // Check for comment navigation. ?>

	<?php endif; // have_comments() ?>

	<?php if ( in_category( explode( ',',zm_get_option( 'single_layout_qa' ) ) ) ) { ?>
		<?php if ( zm_get_option( 'tou_url' ) ) { ?>
			<div class="single-qa"><a class="bk" href="<?php echo get_permalink( zm_get_option('tou_url') ); ?>" target="_blank"><?php _e( '我要提问', 'begin' ); ?></a></div>
		<?php } ?>
	<?php } ?>

	<?php if ( comments_open() ) : ?>
		<div class="scroll-comments"></div>
		<div id="respond" class="comment-respond ms bk da" <?php aos_a(); ?>>
			<?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
				<?php if (zm_get_option('user_l')) { ?><a href="<?php echo zm_get_option('user_l'); ?>" rel="external nofollow" target="_blank"><?php } else { ?><span class="show-layer" data-show-layer="login-layer"><?php } ?>
					<div class="comment-nologin">
						<?php if ( in_category( explode( ',',zm_get_option( 'single_layout_qa' ) ) ) ) { ?>
							<h3 id="reply-title" class="comment-reply-title"><?php _e( '回复问题', 'begin' ); ?></h3>
						<?php } else { ?>
							<h3 id="reply-title" class="comment-reply-title"><?php _e( '发表评论', 'begin' ); ?></h3>
						<?php } ?>
						<p class="comment-form-comment<?php echo cur(); ?>"><textarea id="comment" class="da" name="comment" rows="2" tabindex="35" placeholder="<?php echo stripslashes( zm_get_option( 'comment_hint' ) ); ?>" onfocus="this.placeholder=''" onblur="this.placeholder='<?php echo stripslashes( zm_get_option( 'comment_hint' ) ); ?>'"></textarea></p>
					</div>
					<p class="form-submit">
						<input id="submit" class="bk da<?php echo cur(); ?>" name="submit" type="submit" tabindex="36" value="<?php _e( '提交', 'begin' ); ?>"/>
					</p>
				<?php if ( zm_get_option( 'user_l' ) ) { ?></a><?php } else { ?></span><?php } ?>
			<?php else : ?>
				<form action="<?php echo get_option( 'siteurl' ); ?>/wp-comments-post.php" method="post" id="commentform">
					<?php if ( is_user_logged_in() || ( '' != $comment_author ) ) { ?>
					<?php } else { ?>
						<div class="comment-user-inf">
							<?php if ( get_option( 'show_avatars' ) ) { ?>
								<div class="user-avatar load">
									<?php 
										$random_avata = explode( ',' , zm_get_option( 'random_avatar_url' ) );
										$random_avata_array = array_rand( $random_avata );
									 ?>
									<img alt="匿名" src="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=" data-original="<?php echo $random_avata[$random_avata_array]; ?>">
								</div>
							<?php } ?>
							<div class="comment-user-inc">
								<?php if ( in_category( explode( ',',zm_get_option( 'single_layout_qa' ) ) ) ) { ?>
									<h3 id="reply-title" class="comment-reply-title"><span><?php _e( '回复问题', 'begin' ); ?></span></h3>
								<?php } else { ?>
									<h3 id="reply-title" class="comment-reply-title"><span><?php _e( '发表评论', 'begin' ); ?></span></h3>
								<?php } ?>
								<span class="comment-user-name"><?php _e( '匿名网友', 'begin' ); ?></span>
								<span class="comment-user-alter"><?php if ( zm_get_option( 'not_comment_form' ) ) { ?><span class="comment-user-write"><?php _e( '填写信息', 'begin' ); ?></span><?php } ?></span>
							</div>
						</div>
					<?php } ?>

					<?php if ( is_user_logged_in() ) : ?>

						<div class="comment-user-inf">
							<?php if ( get_option( 'show_avatars' ) ) { ?>
								<div class="user-avatar load">
									<?php global $current_user;wp_get_current_user();
										if ( zm_get_option( 'cache_avatar' ) ) {
											echo begin_avatar( $current_user->user_email, 96, '', $user_identity );
										} else {
											echo '<img class="avatar photo" src="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=" alt="'. $user_identity .'" width="96" height="96" data-original="' . preg_replace(array('/^.+(src=)(\"|\')/i', '/(\"|\')\sclass=(\"|\').+$/i'), array('', ''), get_avatar( $current_user->user_email, 96, '', $user_identity )) . '" />';
										}
									?>
								</div>
							<?php } ?>
							<div class="comment-user-inc">
								<?php if ( in_category( explode( ',',zm_get_option( 'single_layout_qa' ) ) ) ) { ?>
									<h3 id="reply-title" class="comment-reply-title"><span><?php _e( '回复问题', 'begin' ); ?></span></h3>
								<?php } else { ?>
									<h3 id="reply-title" class="comment-reply-title"><span><?php _e( '发表评论', 'begin' ); ?></span></h3>
								<?php } ?>
								<span class="comment-user-name comment-user-name-alter"><a href="<?php echo get_option( 'siteurl' ); ?>/wp-admin/profile.php" title="<?php _e( '修改资料', 'begin' ); ?>" target="_blank"><?php echo $user_identity; ?></a></span>
								<span class="comment-user-alter"><a href="<?php echo wp_logout_url(get_permalink()); ?>"><?php _e( '退出登录', 'begin' ); ?></a></span>
							</div>
						</div>

					<?php elseif ( '' != $comment_author ): ?>

						<div class="comment-user-inf">
							<div class="user-avatar load">
								<?php if ( zm_get_option( 'cache_avatar' ) ) {
									echo begin_avatar( $comment_author_email, $size = '96', '', $comment_author );
								} else {
									echo '<img class="avatar photo" src="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=" alt="'. $comment_author .'" width="96" height="96" data-original="' . preg_replace(array('/^.+(src=)(\"|\')/i', '/(\"|\')\sclass=(\"|\').+$/i'), array('', ''), get_avatar($comment_author_email, $size = '96', '', $comment_author )) . '" />';
								} ?>
							</div>
							<div class="comment-user-inc">
								<?php if ( in_category( explode( ',',zm_get_option( 'single_layout_qa' ) ) ) ) { ?>
									<h3 id="reply-title" class="comment-reply-title"><span><?php _e( '回复问题', 'begin' ); ?></span></h3>
								<?php } else { ?>
									<h3 id="reply-title" class="comment-reply-title"><span><?php _e( '发表评论', 'begin' ); ?></span></h3>
								<?php } ?>
								<a href="javascript:CommentAuthorInfo();" title="<?php _e( '修改资料', 'begin' ); ?>"><span class="comment-user-name"><?php printf ('%s', $comment_author); ?></span><span class="comment-user-alter"><i class="be be-editor"></i></span></a>
							</div>
							<script>var changeMsg="<i class='be be-editor'></i>";var closeMsg="<i class='be be-sort'></i>";function CommentAuthorInfo(){jQuery("#comment-author-info").slideToggle("slow",function(){if (jQuery("#comment-author-info").css("display")=="none"){jQuery(".comment-user-alter").html(changeMsg)}else{jQuery(".comment-user-alter").html(closeMsg)}})}jQuery(document).ready(function() {jQuery("#comment-author-info").hide()});</script>
						</div>

					<?php endif; ?>

					<?php if ( get_option( 'show_avatars' ) && zm_get_option( 'be_show_avatars' ) ) { ?>
						<div class="gravatar-apply">
							<?php 
								if ( zm_get_option( 'gravatar_url' ) == 'zh' ) {
									$gravatarurl = 'https://cravatar.cn/';
								} else {
									$gravatarurl = 'https://cn.gravatar.com/';
								}
								echo '<a href="' . $gravatarurl . '" rel="external nofollow" target="_blank" title="' . sprintf( __( '申请头像', 'begin' ) ) . '"></a>';
							?>
						</div>
					<?php } ?>

					<div class="comment-form-comment">
						<?php if ( !zm_get_option( 'comment_hint' ) ) { ?>
							<textarea id="comment" class="dah bk" name="comment" rows="4" tabindex="30" placeholder="<?php echo stripslashes( zm_get_option( 'comment_hint' ) ); ?>"></textarea>
						<?php } else { ?>
							<textarea id="comment" class="dah bk" name="comment" rows="4" tabindex="30" placeholder="<?php echo stripslashes( zm_get_option( 'comment_hint' ) ); ?>" onfocus="this.placeholder=''" onblur="this.placeholder='<?php echo stripslashes( zm_get_option( 'comment_hint' ) ); ?>'"></textarea>
						<?php } ?>
						<p class="comment-tool bgt">
							<?php if ( zm_get_option( 'embed_img' ) ) { ?><a class="tool-img dah bky" href='javascript:embedImage();' title="<?php _e( '图片', 'begin' ); ?>"><i class="icon-img"></i><i class="be be-picture"></i></a><?php } ?>
							<?php if ( zm_get_option( 'emoji_show' ) ) { ?><a class="emoji dah bky" href="" title="<?php _e( '表情', 'begin' ); ?>"><i class="be be-insertemoticon"></i></a><?php } ?>
							<?php if ( zm_get_option( 'emoji_show' ) ) { ?>
								<p class="emoji-box">
									<?php get_template_part( 'inc/smiley' ); ?>
								</p>
							<?php } ?>
							<?php if ( zm_get_option( 'embed_img' ) ) { ?>
								<div class="add-img-box yy da bk">
								<div class="add-img-main">
									<div><textarea class="img-url dah bk" rows="3" placeholder="<?php _e( '图片地址', 'begin' ); ?>" value=" + "/></textarea></div>
									<div class="add-img-but bk dah<?php echo cur(); ?>"><?php _e( '确定', 'begin' ); ?></div>
									<span class="arrow-down"></span>
								</div>
								</div>
							<?php } ?>
						</p>
					</div>

					<?php if ( ! is_user_logged_in() && $req ) { ?>
						<div id="comment-author-info" class="comment-info<?php if ( zm_get_option( 'languages_en' ) ) { ?> comment-en<?php } ?><?php if ( zm_get_option( 'not_comment_form' ) ) { ?> author-form<?php } ?>">
							<p class="comment-form-author pcd">
								<label class="dah" for="author"><?php _e( '昵称', 'begin' ); ?></span></label>
								<input type="text" name="author" id="author" class="commenttext dah" value="<?php echo $comment_author; ?>" tabindex="31" required="required" />
								<span class="required bgt"><i class="be be-personoutline"></i></span>
							</p>
							<?php if ( ! zm_get_option( 'no_email' ) ) { ?>
								<p class="comment-form-email pcd">
									<label class="dah" for="email"><?php _e( '邮箱', 'begin' ); ?></label>
									<input type="text" name="email" id="email" class="commenttext dah" value="<?php echo $comment_author_email; ?>" tabindex="32" required="required" />
									<span class="required bgt"><i class="be be-email"></i></span>
								</p>

								<?php if ( ! zm_get_option( 'no_comment_url' ) ) { ?>
									<p class="comment-form-url pcd<?php if ( !zm_get_option('qq_info' ) ) { ?> qqcd<?php } ?>">
										<label class="dah" for="url"><?php _e( '网址', 'begin' ); ?></label>
										<input type="text" name="url" id="url" class="commenttext dah" value="<?php echo $comment_author_url; ?>" tabindex="33" />
										<span class="required bgt"><i class="be be-link"></i></span>
									</p>
								<?php } ?>
							<?php } ?>
							<?php if ( zm_get_option( 'qq_info' ) ) { ?>
								<p class="comment-form-qq pcd<?php if ( zm_get_option('no_comment_url' ) ) { ?> qqcd<?php } ?>">
									<label class="dah" for="qq"><?php _e( 'QQ', 'begin' ); ?></label>
									<input id="qq" class="dah" name="qq" type="text" value="" size="30" placeholder="输入QQ号点右侧按钮填写信息" tabindex="34" />
									<span id="fillqq" class="fill-but dah<?php echo cur(); ?>" title="一键填写信息"><i class="be be-edit"></i></span>
									<span id="loging"></span>
								</p>
							<?php } ?>
							<?php if ( zm_get_option( 'comment_honeypot' ) ) { ?>
								<p class="comment-form-url">
									<label class="dah no-label" for="url">Address
										<input type="text" id="address" class="dah bk address" name="address" placeholder="">
									</label>
								</p>
							<?php } ?>
							<div class="clear"></div>
						</div>
					<?php } ?>

					<p class="form-submit">
						<input id="submit" class="bk dah<?php echo cur(); ?>" name="submit" type="submit" value="<?php _e( '提交', 'begin' ); ?>"/>
						<span class="cancel-reply"><?php cancel_comment_reply_link( sprintf( __( '取消', 'begin' ) ) ); ?></span>
					</p>

					<?php if ( zm_get_option( 'comment_ajax' ) && zm_get_option( 'qt' ) ) { ?>
						<div class="qaptcha-box">
							<div class="unlocktip" data-hover="<?php _e( '滑动解锁', 'begin' ); ?>"></div>
							<div class="qaptcha"></div>
						</div>
					<?php } ?>

					<?php comment_id_fields(); do_action( 'comment_form', $post->ID ); ?>
				</form>

	 		<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if ( ! comments_open() ) : ?>
		<p class="no-comments bk"><?php _e( '评论已关闭！', 'begin' ); ?></p>
	<?php endif; ?>

</div>
<?php } ?>
<!-- #comments -->