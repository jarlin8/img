<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// 文章信息
function begin_entry_meta() {
	global $post;
	if ( ! is_single() ) :
		if (zm_get_option('meta_author')) {
			if (zm_get_option('author_hide')) {echo '<span class="meta-author author-hide">';} else {echo '<span class="meta-author">';}
			simple_author_inf();
			echo '</span>';
		}

		if ( zm_get_option( 'news_date' ) ) {
			$t1 = $post->post_date;
			$t2 = date( "Y-m-d H:i:s" );
			$t3 = zm_get_option( 'new_n' );
			$newest = ( strtotime( $t2 )-strtotime( $t1 ) )/3600;
			if ( $newest < $t3 ) {
				echo '<span class="date date-new">';
			} else {
				echo '<span class="date">';
			}
		} else {
			echo '<span class="date">';
		}
	echo '<time datetime="';
	echo get_the_date('Y-m-d');
	echo ' ' . get_the_time('H:i:s');
	echo '">';
	time_ago( $time_type ='post' );
	echo '</time></span>';
	views_span();
	be_vip_meta();
	if ( post_password_required() ) { 
		echo '<span class="comment"><a href=""><i class="icon-scroll-c ri"></i>' . sprintf(__( '密码保护', 'begin' )) . '</a></span>';
	} else {
		if (!zm_get_option('close_comments')) {
			echo '<span class="comment">';
				comments_popup_link( '<span class="no-comment"><i class="be be-speechbubble ri"></i>' . sprintf(__( '评论', 'begin' )) . '</span>', '<i class="be be-speechbubble ri"></i>1 ', '<i class="be be-speechbubble ri"></i>%' );
			echo '</span>';
		}
	}
	post_tag_cloud();
	else :
	echo '<ul class="single-meta">';
		if (zm_get_option('baidu_record')) {baidu_record_t();}

		views_li();
	if (zm_get_option('reading_m')) {
		echo '<span class="reading-open' . cur() . '">'. sprintf(__( '阅读模式', 'begin' )) .'</span>';
	}
		edit_post_link('<i class="be be-editor"></i>', '<li class="edit-link">', '</li>' );
	echo '</ul>';
	if (zm_get_option('reading_m')) {
		echo '<span class="reading-close bk dah' . cur() . '"><i class="be be-cross"></i></span>';
	}
	echo '<span class="s-hide' . cur() . '"><span class="off-side"></span></span>';
	endif;
}

// timeline
function timeline_meta() {
		if (zm_get_option('meta_author')) {
			if (zm_get_option('author_hide')) {echo '<span class="meta-author author-hide">';} else {echo '<span class="meta-author">';}
			simple_author_inf();
			echo '</span>';
		}
	echo '<span class="cat">' . zm_category() . '</span>';
	echo '<span class="timeline_meta_r">';
	views_span();
	if ( post_password_required() ) { 
		echo '<span class="comment"><a href=""><i class="icon-scroll-c ri"></i>' . sprintf(__( '密码保护', 'begin' )) . '</a></span>';
	} else {
		if (!zm_get_option('close_comments')) {
			echo '<span class="comment">';
				comments_popup_link( '<span class="no-comment"><i class="be be-speechbubble ri"></i>' . sprintf(__( '评论', 'begin' )) . '</span>', '<i class="be be-speechbubble ri"></i>1 ', '<i class="be be-speechbubble ri"></i>%' );
			echo '</span>';
		}
	}
	echo '</span>';
}

// 日志信息
function begin_format_meta() {
	global $post;
	if (zm_get_option('meta_author')) {
		if (zm_get_option('author_hide')) {echo '<span class="meta-author author-hide">';} else {echo '<span class="meta-author">';}
		simple_author_inf();
		echo '</span>';
	}

		if ( zm_get_option( 'news_date' ) ) {
			$t1 = $post->post_date;
			$t2 = date( "Y-m-d H:i:s" );
			$t3 = zm_get_option( 'new_n' );
			$newest = ( strtotime( $t2 )-strtotime( $t1 ) )/3600;
			if ( $newest < $t3 ) {
				echo '<span class="date date-new">';
			} else {
				echo '<span class="date">';
			}
		} else {
			echo '<span class="date">';
		}
	echo '<time datetime="';
	echo get_the_date('Y-m-d');
	echo ' ' . get_the_time('H:i:s');
	echo '">';
	time_ago( $time_type ='post' );
	echo '</time></span>';
	echo '<span class="format-cat">';
		zm_category();
	if ( is_tax('notice') ) {
		echo get_the_term_list( get_the_ID(), 'notice');
	}
	echo '</span>';
	views_span();

	if ( post_password_required() ) { 
		echo '<span class="comment"><a href=""><i class="icon-scroll-c ri"></i>' . sprintf(__( '密码保护', 'begin' )) . '</a></span>';
	} else {
		if (!zm_get_option('close_comments')) {
			echo '<span class="comment">';
				comments_popup_link( '<span class="no-comment"><i class="be be-speechbubble ri"></i>' . sprintf(__( '评论', 'begin' )) . '</span>', '<i class="be be-speechbubble ri"></i>1 ', '<i class="be be-speechbubble ri"></i>%' );
			echo '</span>';
		}
	}
	post_tag_cloud();
}

function meta_back() {
	if (zm_get_option('inf_back')) {
		return' meta-back';
	}
}

function begin_single_meta() {
	global $wpdb, $post;
	if (zm_get_option('title_c')) {
	echo '<div class="begin-single-meta begin-single-meta-c">';
	} else {
	echo '<div class="begin-single-meta' . meta_back() .'">';
	}
		if (zm_get_option('meta_author_single')) {
			echo '<span class="meta-author">';
				echo '<span class="meta-author-avatar load">';
				if (zm_get_option('cache_avatar')) {
					echo begin_avatar( get_the_author_meta('email'), '96', '', get_the_author() );
				} else {
					be_avatar_author();
				}
				echo '</span>';
				author_inf();
			echo '</span>';
		}

		echo '<span class="meta-date">';
		echo '<time datetime="';
		echo get_the_modified_date('Y-m-d');
		echo ' ' . get_the_modified_time('H:i:s');
		echo '">';
		time_ago( $time_type ='posts' );
		echo '</time></span>';
		echo '<span class="meta-block"></span>';
		if ( zm_get_option( 'inf_back' ) && !zm_get_option( 'title_c' ) ) {
			echo '<span class="meta-margin"></span>';
		}
		if (zm_get_option('post_cat')) {
			echo '<span class="meta-cat">';
			the_category( ' ' );
			echo get_the_term_list( $post->ID, 'gallery', '' );
			echo '</span>';
		}
		$from = get_post_meta(get_the_ID(), 'from', true);
		$copyright = get_post_meta(get_the_ID(), 'copyright', true);
		if ( get_post_meta(get_the_ID(), 'from', true) ) :
			echo '<span class="meta-source">';
			echo sprintf(__( '来源：', 'begin' ));
			if ( get_post_meta(get_the_ID(), 'copyright', true) ) :
				echo '<a href="';
				echo $copyright;
				echo '" rel="nofollow" target="_blank">';
				echo $from;
				echo '</a>';
			else:
				echo $from;
			endif;
			echo '</span>';
		endif;

		if (!zm_get_option('close_comments')) {
			if ( post_password_required() ) { 
				echo '<span class="comment"><a href="#comments">' . sprintf(__( '密码保护', 'begin' )) . '</a></li>';
			} else {
				echo '<span class="comment">';
					comments_popup_link( '<i class="be be-speechbubble ri"></i><span class="comment-qa"></span><em>' . sprintf(__( '评论', 'begin' )) . '</em>', '<i class="be be-speechbubble ri"></i>1 ', '<i class="be be-speechbubble ri"></i>%' );
				echo '</span>';
			}
		}

		if (zm_get_option('baidu_record')) {baidu_record_b();}

		views_span();

		if ( get_post_meta( get_the_ID(), 'zm_like', true ) && zm_get_option('meta_zm_like') ){
			echo '<span class="post-like">';
			echo '<i class="be be-thumbs-up-o ri"></i>';
			echo get_post_meta( get_the_ID(), 'zm_like', true );
			echo '</span>';
		}
		if (zm_get_option('print_on')) {
			echo '<span class="print"><a href="javascript:printme()" target="_self" title="' . sprintf(__( '打印', 'begin' )) . '"><i class="be be-print"></i></a></span>';
		}
		if (zm_get_option('word_time') && wp_is_mobile()) {} else {
			echo '<span class="word-time">';
				if (zm_get_option('word_count')) {
					$text = '';
					echo count_words ($text);
				}
				if (zm_get_option('reading_time')) {reading_time();}
			echo '</span>';
		}
	if (zm_get_option('reading_m')) {
		echo '<span class="reading-open' . cur() . '">'. sprintf(__( '阅读模式', 'begin' )) .'</span>';
	}
		edit_post_link('<i class="be be-editor"></i>', '<span class="edit-link">', '</span>' );
	echo '</div>';
	if (zm_get_option('reading_m')) {
		echo '<span class="reading-close bk dah' . cur() . '"><i class="be be-cross"></i></span>';
	}
	if ( get_post_type() !== 'bulletin' ) {
		echo '<span class="s-hide' . cur() . '"><span class="off-side"></span></span>';
	}
	if ( function_exists( 'be_single_vip_meta_inf' ) ) {
		be_single_vip_meta_inf();
	}
}

function begin_single_cat() {
	global $wpdb, $post;
	if ( get_post_meta(get_the_ID(), 'header_img', true) || get_post_meta(get_the_ID(), 'header_bg', true) ):
	else:
	endif;
	echo '<div class="single-cat-tag dah">';
		echo '<div class="single-cat dah"><i class="be be-sort"></i>';
			the_category( ' ' );
			echo get_the_term_list( $post->ID, 'special', '' );
			if (zm_get_option('post_replace')) {
				if ( ( get_the_modified_time( 'Y' )*365+get_the_modified_time( 'z' ) ) > (get_the_time( 'Y' )*365+get_the_time( 'z' ) ) ) :
				echo '<span class="single-replace dah">';
					echo sprintf(__( '最后更新', 'begin' ));
					echo '：';;
					the_modified_time('Y-n-j');
				echo '</span>';
				endif;
			}
		echo '</div>';
	echo '</div>';
}

function begin_related_meta() {
	echo '<span class="date">';
	echo '<time datetime="';
	echo get_the_date('Y-m-d');
	echo ' ' . get_the_time('H:i:s');
	echo '">';
	time_ago( $time_type ='post' );
	echo '</time></span>';
	views_span();

	if ( post_password_required() ) { 
		echo '<span class="comment"><a href=""><i class="icon-scroll-c ri"></i>' . sprintf(__( '密码保护', 'begin' )) . '</a></span>';
	} else {
		if (!zm_get_option('close_comments')) {
			echo '<span class="comment">';
				comments_popup_link( '<span class="no-comment"><i class="be be-speechbubble ri"></i>' . sprintf(__( '评论', 'begin' )) . '</span>', '<i class="be be-speechbubble ri"></i>1 ', '<i class="be be-speechbubble ri"></i>%' );
			echo '</span>';
		}
	}
	post_tag_cloud();
}

// 页面信息
function begin_page_meta() {
	echo '<ul class="single-meta">';
		if (zm_get_option('word_time') && wp_is_mobile()) {} else {
			echo '<span class="word-time">';
				if (zm_get_option('word_count')) {echo count_words ($text);}
				if (zm_get_option('reading_time')) {reading_time();}
			echo '</span>';
		}
		edit_post_link('<i class="be be-editor"></i>', '<li class="edit-link">', '</li>' );
		begin_meta_tts();
		if (zm_get_option('print_on')) {
			echo '<li class="print"><a href="javascript:printme()" target="_self" title="' . sprintf(__( '打印', 'begin' )) . '"><i class="be be-print"></i></a></li>';
		}
		if (!zm_get_option('close_comments')) {
			echo '<li class="comment">';
			comments_popup_link( '<i class="be be-speechbubble ri"></i>' . sprintf(__( '评论', 'begin' )) . '', '<i class="be be-speechbubble ri"></i>1 ', '<i class="be be-speechbubble ri"></i>%' );
			echo '</li>';
		}
		views_li();
	echo '</ul>';
	echo '<span class="s-hide' . cur() . '"><span class="off-side"></span></span>';
}

// 专题
function begin_page_meta_zt() {
	global $wpdb, $post;
	echo '<div class="single-meta-zt begin-single-meta">';
		$special = get_post_meta(get_the_ID(), 'special', true);
		echo '<span><i class="be be-stack ri"></i>';
		if ( get_tag_post_count( $special ) >= 1 ) {
			echo get_tag_post_count( $special );
			echo '篇</span>';
		} else {
			echo '未添加文章</span>';
		}
		views_span();
		edit_post_link('<i class="be be-editor"></i>', '<span class="edit-link">', '</span>' );
	echo '</div>';
}

// 其它信息
function begin_grid_meta() {
	echo '<span class="date">';
	echo '<time datetime="';
	echo get_the_date('Y-m-d');
	echo ' ' . get_the_time('H:i:s');
	echo '">';
	the_time( 'm/d' ); 
	echo '</time></span>';
	views_span();
	if (!zm_get_option('close_comments')) {
		if ( post_password_required() ) { 
			echo '<span class="comment"><a href=""><i class="icon-scroll-c ri"></i>' . sprintf(__( '密码保护', 'begin' )) . '</a></span>';
		} else {
			echo '<span class="comment">';
				comments_popup_link( '<span class="no-comment"><i class="be be-speechbubble ri"></i>' . sprintf(__( '评论', 'begin' )) . '</span>', '<i class="be be-speechbubble ri"></i>1 ', '<i class="be be-speechbubble ri"></i>%' );
			echo '</span>';
		}
	}
}

// grid inf
function grid_inf() {
	global $wpdb, $post;
?>
<span class="grid-inf gdz">
	<?php if ( has_post_format('link') ) { ?>
		<?php if ( get_post_meta(get_the_ID(), 'link_inf', true) ) { ?>
			<span class="link-inf"><?php $link_inf = get_post_meta(get_the_ID(), 'link_inf', true);{ echo $link_inf;}?></span>
		<?php } else { ?>
			<?php if (zm_get_option('meta_author')) { ?><span class="grid-author"><?php grid_author_inf(); ?></span><?php } ?>
			<span class="g-cat"><?php zm_category(); ?></span>
		<?php } ?>
	<?php } else { ?>
		<?php if (zm_get_option('meta_author')) { ?><span class="grid-author"><?php grid_author_inf(); ?></span><?php } ?>
		<span class="g-cat"><?php zm_category(); ?></span>
	<?php } ?>
	<span class="grid-inf-l">
		<?php echo be_vip_meta(); ?>
		<?php views_span(); ?>
		<?php if ( !has_post_format('link') ) { ?><span class="date"><time datetime="<?php echo get_the_date('Y-m-d'); ?> <?php echo get_the_time('H:i:s'); ?>"><?php the_time( 'm/d' ); ?></time></span><?php } ?>
		<?php if ( get_post_meta(get_the_ID(), 'zm_like', true) ) : ?><span class="grid-like"><span class="be be-thumbs-up-o">&nbsp;<?php zm_get_current_count(); ?></span></span><?php endif; ?>
	</span>
</span>
<?php }

// fall
function fall_inf() {
	global $wpdb, $post;
?>
<span class="grid-inf">
	<span class="g-cat"><?php zm_category(); ?><?php echo get_the_term_list( $post->ID, 'gallery', '' ); ?></span>
	<span class="grid-inf-l">
		<span class="date"><time datetime="<?php echo get_the_date('Y-m-d'); ?> <?php echo get_the_time('H:i:s'); ?>"><?php the_time( 'm/d' ); ?></time></span>
		<?php echo be_vip_meta(); ?>
	</span>
</span>
<?php }

// 时间
if (zm_get_option('meta_time')) {
function time_ago( $time_type ){
	switch( $time_type ){
		case 'comment':
			printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time());
		break;
		case 'post';
			if ( zm_get_option( 'languages_en' ) ) {
				if ( zm_get_option( 'no_year' ) ) {
					echo get_the_date('d/m');
				} else {
					echo get_the_date();
				}
			} else {
				if ( zm_get_option( 'no_year' ) ) {
				echo get_the_date('m月d日');
				} else {
				echo get_the_date();
				}
			}
		break;
		case 'posts';
			echo get_the_date();
			if (zm_get_option('meta_time_second')) {
				echo ' ' . get_the_time('H:i:s') . '';
			}
		break;
		case 'pages';
			echo get_the_date();
		break;
	}
}
} else { 
function time_ago( $time_type ){
	switch( $time_type ){
		case 'comment': 
			$time_diff = current_time('timestamp') - get_comment_time('U');
			if ( $time_diff <= 300 )
			echo sprintf(__( '刚刚', 'begin' ));
			elseif (  $time_diff>=300 && $time_diff <= 86400 )
				echo human_time_diff(get_comment_time('U'), current_time('timestamp')).''.sprintf(__( '前', 'begin' )).'';
			else
				printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time());
		break;
		case 'post';
			$time_diff = current_time('timestamp') - get_the_time('U');
			if ( $time_diff <= 300 )
			echo sprintf(__( '刚刚', 'begin' ));
			elseif (  $time_diff>=300 && $time_diff <= 86400 )
				echo human_time_diff(get_the_time('U'), current_time('timestamp')).''.sprintf(__( '前', 'begin' )).'';
			else
				echo the_time( sprintf( __( 'm月d日', 'begin' ) ) );
		break;
		case 'posts';
			//$time_diff = current_time('timestamp') - get_the_time('U');
			//if ( $time_diff <= 300 )
				//echo sprintf(__( '刚刚', 'begin' ));
			//elseif (  $time_diff>=300 && $time_diff <= 86400 )
				//echo human_time_diff(get_the_time('U'), current_time('timestamp')).'前';
			//else
				echo get_the_date();
				if (zm_get_option('meta_time_second')) {
					echo ' ' . get_the_time('H:i:s') . '';
				}
		break;
		case 'pages';
			echo get_the_date();
		break;
	}
}
}

function be_vip_meta() {
	if ( function_exists( 'be_vip_meta_inf' ) ) {
		be_vip_meta_inf();
	}
}

function grid_meta() { ?>
	<span class="date"><time datetime="<?php echo get_the_date('Y-m-d'); ?> <?php echo get_the_time('H:i:s'); ?>"><?php the_time( 'm/d' ); ?></time></span>
<?php }