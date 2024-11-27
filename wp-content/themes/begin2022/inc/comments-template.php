<?php
if ( ! defined( 'ABSPATH' ) ) exit;
function begin_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);

	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
	if ( zm_get_option( 'comment_floor' ) ) { 
		$comorder = get_option( 'comment_order' );
		if( $comorder == 'asc' ){
			global $commentcount;
			if( !$commentcount ) {
				if ( get_query_var('cpage') > 0 )
				$page = get_query_var('cpage')-1;
				else $page = get_query_var( 'cpage' );
				$cpp = get_option( 'comments_per_page' );
				$commentcount = $cpp * intval( $page );
			}
		} else {
			global $commentcount, $wpdb, $post;
			if( !$commentcount ) {
				$comments = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_post_ID = $post->ID AND comment_type not in ('trackback','pingback') AND comment_approved = '1' AND !comment_parent");
				$cnt = count( $comments );
				//$comments = get_comments( array( 'status' => 'approve', 'parent' => '0', 'post_id' => $post_id, 'count' => true) );
				//$cnt = $comments;
				$page = get_query_var( 'cpage' );
				$cpp = get_option('comments_per_page');
				if ( ceil($cnt / $cpp ) == 1 || ( $page> 1 && $page == ceil( $cnt / $cpp ) ) ) {
					$commentcount = $cnt + 1;
				} else {
					$commentcount = intval( $cpp ) * intval( $page ) + 1;
				}
			}
		}
	}
?>

	<li class="comments-anchor"><ul id="anchor-comment-<?php comment_ID() ?>"></ul></li>
	<<?php echo $tag ?> <?php comment_class( empty( $args['has_children'] ) ? 'ms bk' : 'parent ms bk' ) ?> id="comment-<?php comment_ID() ?>" <?php aos_a(); ?>>
	<?php if ( 'div' != $args['style'] ) : ?>
		<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
	<?php endif; ?>
	<div class="comment-author vcard">
		<?php if ( get_option( 'show_avatars' ) ) { ?>
			<div class="comment-avatar load<?php if ( get_option( 'show_avatars' ) ) { ?> comment-avatar-show bk<?php } ?>">
				<?php if (zm_get_option('cache_avatar')) { ?>
					<?php echo begin_avatar( $comment->comment_author_email, 96, '', get_comment_author() ); ?>
				<?php } else { ?>
					<?php be_avatar_comment(); ?>
				<?php } ?>
			</div>
		<?php } ?>

		<strong><?php comment_author_link(); ?></strong>
		<?php 
			if ($comment->comment_author_email == get_option('admin_email')) {
				echo '<span class="author-mark author-admin"><i class="be be-personoutline" title="'. sprintf(__( '管理员', 'begin' )) .'"></i></span>';
			} else {
				$post_author = begin_comment_by_post_author( $comment );
				if ( $post_author ) {
					echo '<span class="author-mark post-author"><i class="be be-personoutline" title="'. sprintf(__( '作者', 'begin' )) .'"></i></span>';
				}
			}
		?>
		<?php 
			$post_author = begin_comment_by_post_author( $comment );
			if ( zm_get_option('vip') && ! $post_author ) {
				get_author_class( $comment->comment_author_email, $comment->user_id );
				if( current_user_can('manage_options') ) {}
			}
		?>
		<span class="comment-meta commentmetadata">
			<a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>"></a><br />
			<span class="comment-aux">
				<?php echo get_comment_date(); ?>
				<span class="comment-time"><?php echo get_comment_time(); ?></span>
				<?php edit_comment_link( '<i class="be be-editor"></i>' , '&nbsp;', '' ); ?>

				<?php if (zm_get_option('del_comment')) { ?>
				<?php
					if ( current_user_can('level_10') ) {
						$url = home_url();
						echo '<a id="delete-'. $comment->comment_ID .'" href="' . wp_nonce_url( admin_url( "comment.php?action=deletecomment&p=" . $comment->comment_post_ID . '&c=' . $comment->comment_ID ), 'delete-comment_' . $comment->comment_ID) . '" title="' . sprintf(__( '删除', 'begin' )) . '" class="comment-del ease bgt"><span class="dashicons dashicons-ellipsis"></span></a>';
					}
				?>
				<?php } ?>

				<?php 
					if ( in_category( explode( ',',zm_get_option( 'single_layout_qa' ) ) ) ) {
						$my_id = $comment->user_id;
						$user_info = get_userdata( $my_id );
						if ( $my_id && $user_info->remark ) {
							echo '<span class="remark-txt"><span class="dashicons dashicons-location"></span>' . $user_info->remark . '</span>';
						}
					}
				?>

				<?php if (zm_get_option('comment_floor')) { ?>
					<span class="floor">
						<?php
							if($comorder == 'asc'){
								if(!$parent_id = $comment->comment_parent){
									switch ($commentcount){
										case 0 : echo "<span class='floor-c floor-s'><i>1</i><em>F</em></span>"; ++$commentcount; break;
										case 1 : echo "<span class='floor-c floor-b'><i>2</i><em>F</em></span>"; ++$commentcount; break;
										case 2 : echo "<span class='floor-c floor-d'><i>3</i><em>F</em></span>"; ++$commentcount; break;
										default : printf('<span class="floor-c floor-l"><i>%1$s</i><em>F</em></span>', ++$commentcount);
									}
								}
							} else {
								if(!$parent_id = $comment->comment_parent){
									switch ($commentcount){
										case 2 : echo "<span class='floor-c floor-s'><i>1</i><em>F</em></span>"; --$commentcount; break;
										case 3 : echo "<span class='floor-c floor-b'><i>2</i><em>F</em></span>"; --$commentcount; break;
										case 4 : echo "<span class='floor-c floor-d'><i>3</i><em>F</em></span>"; --$commentcount; break;
										default : printf('<span class="floor-c floor-l"><i>%1$s</i><em>F</em></span>', --$commentcount);
									}
								}
							}
						?>
						<?php if( $depth > 1 ){ printf('<span class="floor-c floor-l floor-l-b"><em>B</em><span class="floor-b-count">%1$s</span></span>', $depth-1); } ?>
					</span>
				<?php } ?>
				<br />
				<?php if($args['max_depth']!=$depth) { ?>
					<?php if( get_option( 'comment_registration' ) && ! is_user_logged_in() ) { ?>
						<span class="login-show show-layer" data-show-layer="login-layer" role="button"><i class="be be-stack"> </i><?php _e( '登录回复', 'begin' ); ?></span>
					<?php } else { ?>
						<?php
						comment_reply_link(
							array_merge(
								$args,
								array(
									'add_below' => $add_below,
									'reply_text' => '<i class="be be-stack"> </i>' . sprintf(__( '回复', 'begin' )) . '',
									'login_text' => '',
									'depth'     => $depth,
									'max_depth' => $args['max_depth'],
									'before'    => '<span class="reply">',
									'after'     => '</span>',
								)
							)
						);
						?>
					<?php } ?>
				<?php } else { ?>
					<span class="reply"><i class="be be-sort"></i></span>
				<?php } ?>
			</span>
		</span>
	</div>
	<div class="clear"></div>
	<?php comment_text(); ?>
	<?php if ( $comment->comment_approved == '0' ) : ?>
		<div class="comment-awaiting-moderation"><?php _e( '您的评论正在等待审核！', 'begin' ); ?></div>
	<?php endif; ?>
	<?php if ( 'div' != $args['style'] ) : ?>
	</div>
	<?php endif; ?>
<?php
}