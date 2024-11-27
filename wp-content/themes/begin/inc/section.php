<?php
if ( ! defined( 'ABSPATH' ) ) exit;
function decide_h() { ?>
<?php if ( zm_get_option( 'turn_small') ) { ?> site-small<?php } ?><?php if ( zm_get_option( 'infinite_post' ) ) { ?> site-roll<?php } else { ?> site-no-roll<?php } ?><?php if ( is_paged() ) { ?> paged-roll<?php } ?>
<?php }

// 站点图标
function favicon_inf() { ?>
<?php if ( ! zm_get_option( 'favicon') == '' ) { ?>
<link rel="icon" href="<?php echo zm_get_option( 'favicon' ); ?>" sizes="32x32" />
<?php } ?>
<?php if ( ! zm_get_option( 'apple_icon' ) == '' ) { ?>
<link rel="apple-touch-icon" href="<?php echo zm_get_option( 'apple_icon' ); ?>" />
<meta name="msapplication-TileImage" content="<?php echo zm_get_option( 'apple_icon' ); ?>" />
<?php } ?>
<?php }
add_action('admin_head', 'favicon_inf');

// 禁止被嵌套
function be_head_other() {
	if ( zm_get_option( 'x-frame' ) ) {
		header( 'X-Frame-Options:Deny' );
	}
	echo zm_get_option( 'ad_t' );
	echo zm_get_option( 'tongji_h' );
	if ( wp_is_mobile() && is_home() && ! be_get_option( 'mobile_home_url' ) == '' ) {
		$mobile_home_url = be_get_option( 'mobile_home_url' );
		if ( filter_var( $mobile_home_url, FILTER_VALIDATE_URL ) ) {
			header('Location: ' . $mobile_home_url);
			exit();
		} else {
			echo 'Invalid URL.';
		}
	}
}

// like left
function like_left() { ?>
	<?php if ( zm_get_option( 'like_left' ) && is_single() && !wp_is_mobile() ) { ?>
		<div class="like-left-box fds">
			<div class="like-left fadeInDown animated"><?php be_like(); ?></div>
		</div>
	<?php } ?>
<?php }

// share
function be_like() { ?>
	<?php if (zm_get_option('shar_donate') || zm_get_option('shar_like') || zm_get_option('shar_favorite') || zm_get_option('shar_share') || zm_get_option('shar_link') || zm_get_option('shar_poster') ) { ?>
		<?php share_poster(); ?>
	<?php } ?>
<?php }

// title span
function title_i() { ?>
<?php if (zm_get_option('title_i')) { ?>
<span class="title-i"><span></span><span></span><span></span><span></span></span>
<?php } else { ?>
<span class="title-h"></span>
<?php } ?>
<?php }

function more_i() { ?>
<span class="more-i<?php if ( zm_get_option('more_im') ) { ?> more-im<?php } ?>"><span></span><span></span><span></span></span>
<?php }

function vr() { ?><?php if ( !zm_get_option( 'more_w' ) ) { ?> vr<?php } else { ?> lvr<?php } ?><?php }

// entry more
function entry_more() {
	global $wpdb, $post;
?>
	<?php if ( get_post_meta( get_the_ID(), 'direct', true ) ) { ?>
		<?php $direct = get_post_meta( get_the_ID(), 'direct', true ); ?>
		<?php if ( zm_get_option( 'more_w' ) ) { ?><?php if (zm_get_option('more_hide')) { ?><span class="entry-more more-roll ease"><?php } else { ?><span class="entry-more"><?php } ?><a href="<?php echo $direct ?>" target="_blank" rel="external nofollow"><?php echo zm_get_option('direct_w'); ?></a></span><?php } ?>
	<?php } else { ?>
		<?php if ( zm_get_option( 'more_w' ) ) { ?><?php if ( zm_get_option( 'more_hide' ) ) { ?><span class="entry-more more-roll ease"><?php } else { ?><span class="entry-more"><?php } ?><a href="<?php the_permalink(); ?>" rel="external nofollow"><?php echo zm_get_option('more_w'); ?></a></span><?php } ?>
	<?php } ?>
<?php }

// author inf
function author_inf() { ?>
<?php
	global $wpdb;
	$author_id = get_the_author_meta( 'ID' );
	$comment_count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved='1' AND user_id = '$author_id' AND comment_type not in ('trackback','pingback')" );
?>
<div class="meta-author-box">
	<div class="arrow-up"></div>
	<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>" rel="author">
		<div class="meta-author-inf load revery-bg">
			<div class="meta-inf-avatar">
				<?php if ( get_option( 'show_avatars' ) ) { ?>
					<?php if (zm_get_option('cache_avatar')) { ?>
						<?php echo begin_avatar( get_the_author_meta('user_email'), '96', '', get_the_author() ); ?>
					<?php } else { ?>
						<?php be_avatar_author(); ?>
					<?php } ?>
				<?php } else { ?>
					<i class="be be-timerauto"></i>
				<?php } ?>
			</div>
			<div class="meta-inf-name"><?php the_author(); ?></div>
			<div class="meta-inf meta-inf-posts"><span><?php the_author_posts(); ?></span><br /><?php _e( '文章', 'begin' ); ?></div>
			<div class="meta-inf meta-inf-comment"><span><?php echo $comment_count;?></span><br /><?php _e( '评论', 'begin' ); ?></div>
			<div class="clear"></div>
		</div>
	</a>
	<div class="clear"></div>
</div>
<?php }


// // author inf img
function grid_author_inf() { ?>
<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>" rel="author">
	<span class="meta-author grid-meta-author">
		<span class="meta-author-avatar load">
			<?php if (zm_get_option('cache_avatar')) { ?>
				<?php echo begin_avatar( get_the_author_meta('email'), '64', '', get_the_author() ); ?>
			<?php } else { ?>
				<?php be_avatar_author(); ?>
			<?php } ?>
		</span>
	</span>
</a>
<?php }

function simple_author_inf() { ?>
<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>" rel="author">
	<span class="meta-author">
		<span class="meta-author-avatar load">
			<?php if ( get_option( 'show_avatars' ) ) { ?>
				<?php if (zm_get_option('cache_avatar')) { ?>
					<?php echo begin_avatar( get_the_author_meta('email'), '64', '', get_the_author() ); ?>
				<?php } else { ?>
					<?php be_avatar_author(); ?>
				<?php } ?>
			<?php } else { ?>
				<i class="be be-personoutline"></i>
			<?php } ?>
		</span>
	</span>
</a>
<?php }

// search
function search_class() { ?>
<div class="single-content">
	<div class="searchbar ad-searchbar">
		<form method="get" id="searchform" autocomplete="off" action="<?php echo esc_url( home_url() ); ?>/">
			<span class="clear"></span>
			<span class="search-input ad-search-input">
				<input type="text" value="<?php the_search_query(); ?>" name="s" id="s" placeholder="<?php _e( '输入搜索内容', 'begin' ); ?>" required />
				<button type="submit" id="searchsubmit" class="be-btu"><i class="be be-search"></i></button>
			</span>
		</form>
	</div>
</div>
<?php }

// all_content
function all_content() { ?>
<?php if ( word_num() > 800 ) { ?>
	<div class="all-content-box">
		<div class="all-content<?php echo cur(); ?>"><?php _e( '继续阅读', 'begin' ); ?></div>
	</div>
<?php } ?>
<?php }

// 随机文章
function random_post() { ?>
<div class="new_cat">
	<ul>
		<?php
			$cat = get_the_category();
			foreach( $cat as $key=>$category ) {
				$catid = $category->term_id;
			}

			$args = array(
				'orderby' => 'rand',
				'showposts' => 6,
				'ignore_sticky_posts' => 1
			);

			$query = new WP_Query( $args );
			while ( $query->have_posts() ) : $query->the_post();
		?>
		<li>
			<span class="thumbnail">
				<?php echo zm_thumbnail(); ?>
			</span>
			<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
			<span class="date"><?php the_time('m/d'); ?></span>
			<?php views_span(); ?>
		</li>
		<?php endwhile;?>
		<?php wp_reset_postdata(); ?>
	</ul>
</div>
<?php }

function nav_ace() { ?>
<?php if (zm_get_option('nav_ace')) { ?> nav-ace<?php } ?>
<?php }

// Cat Module h3
function cat_module_title() { ?>
	<?php if (zm_get_option('cat_icon')) { ?>
		<?php $term_id = get_query_var('cat');if (get_option('zm_taxonomy_icon'.$term_id)) { ?><i class="t-icon <?php echo zm_taxonomy_icon_code(); ?>"></i><?php } ?>
		<?php $term_id = get_query_var('cat');if (get_option('zm_taxonomy_svg'.$term_id)) { ?><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo zm_taxonomy_svg_code(); ?>"></use></svg><?php } ?>
		<?php $term_id = get_query_var('cat'); if (!get_option('zm_taxonomy_icon'.$term_id) && !get_option('zm_taxonomy_svg'.$term_id)) { ?><?php title_i(); ?><?php } ?>
	<?php } else { ?>
		<?php title_i(); ?>
	<?php } ?>
	<?php single_cat_title(); ?><?php more_i(); ?>
<?php }

function page_class() {
	global $wpdb, $post;
	if ( zm_get_option( 'no_copy' ) && is_single() && !current_user_can( 'level_10' ) && !get_post_meta( get_the_ID(), 'allow_copy', true ) ) {
		echo ' copies';
	}
	if ( zm_get_option( 'fresh_no' ) ) {
		echo ' fresh';
	}

	if ( zm_get_option( 'aos_scroll' ) && ! wp_is_mobile() ) {
		echo ' beaos';
	}

	if ( zm_get_option( 'hover_boxs' ) && ! wp_is_mobile() ) {
		echo ' be_shadow';
	}
}

if ( ! zm_get_option( 'aos_data' ) ) {
	function aos_a() {}
} else {
	function aos_a() {be_aos_a();}
}

function be_aos_a() { ?>data-aos="<?php echo zm_get_option( 'aos_data' ); ?>"<?php }
function aos_b() { ?>data-aos="zoom-in"<?php }
function aos() { ?>data-aos=""<?php }
function aos_c() { ?>data-aos="fade-zoom-in"<?php }
function aos_d() { ?>data-aos="fade-right"<?php }
function aos_e() { ?>data-aos="fade-left"<?php }
function aos_f() { ?>data-aos="fade-in"<?php }
function aos_g() { ?>data-aos="zoom-out"<?php }

// widgets aos
function widgets_data_aos( $params ) {
	$aos = zm_get_option( 'aos_data' );
	$params[0]['before_widget'] = str_replace('data-aos="', 'data-aos="' . $aos . '', $params[0]['before_widget']);
	return $params;
}

// single tag
function be_tags() { ?>
<?php if ( ! zm_get_option( 'single_tab_tags' ) ) { ?>
<?php if ( zm_get_option( 'post_tags' ) && is_single() ) { ?>
	<div class="single-tag">
		<?php
			$post_tags = get_the_tags();
			if ( $post_tags ) {
				echo '<ul class="be-tags">';
				foreach( $post_tags as $tag ) {
					echo '<li data-aos="zoom-in"><a href="' . get_tag_link( $tag->term_id ) . '" rel="tag" target="_blank">' . $tag->name . '</a></li>';
				}
				echo '</ul>';
			}
		?>
</div>
<?php } ?>
<?php } ?>
<?php }

// 全部网址分类
function all_sites_cat( $turn = false ) {
	$boxs = cx_get_option('sites_boxs') ? ' boxs2' : '';

	echo '<div class="all-site-box' . $boxs .'">';
	echo '<div class="all-site-cat" ';
	$count_posts = wp_count_posts( 'sites' );
	$terms = get_terms( 'favorites' );
	$posts = get_queried_object();
	$count = count( $terms );
	echo aos_b();
	echo '>';

	if ( $count > 0 ) {
		echo '<div class="rec-adorn-s"></div><div class="rec-adorn-x"></div>';
		echo '<ul class="all-site-cat-list">';

		echo '<li class="sites-cat-count">';
		echo sprintf( __( '收录', 'begin' ) );
		echo '<span class="sites-count">&nbsp;';
		if ( is_tax( 'favorites' ) ) {
			if ( $posts->count > 1 ) {
				echo $posts->count;
			}
		} else {
			echo $count_posts->publish;
		}

		if ( ! is_page() && ( isset( $posts->count ) && $posts->count < 1 ) ) {
			echo $count_posts->publish;
		}
		echo '</span>';
		echo '</li>';

		foreach ( $terms as $term ) {
			if ( $turn ) {
				// 无文章不显示
				if ( $term->count == 0 ) {
					continue;
				}
				echo '<li><a class="turn" href="#turn' . $term->term_id . '">' . $term->name . '</a></li>';
			} else {
				echo '<li><a href="' . get_term_link( $term ) . '">' . $term->name . '</a></li>';
			}
		}
		echo '</ul>';
	}
	echo '</div>';
	echo '</div>';
}

// 标题提示
function t_mark() {
	global $post;
	$mark = '';
	if ( get_post_meta( get_the_ID(), 'mark', true ) ) {
		$mark .= '<span class="t-mark">';
		$mark .= get_post_meta( get_the_ID(), 'mark', true );
		$mark .= '</span>';
		return $mark;
	}
}

// 置顶推荐
function be_sticky() {
	if ( is_sticky() ) {
		$sticky = '<span class="be-sticky">' . sprintf( __( '推荐', 'begin' ) ) . '</span>';
	} else {
		$sticky = '';
	}
	return $sticky;
}

function cat_sticky() {
	if ( get_post_meta( get_the_ID(), 'cat_top', true ) ) {
		return '<span class="be-sticky">' . sprintf( __( '推荐', 'begin' ) ) . '</span>';
	}
}
// list date
function list_date() { ?>
<?php if ( be_get_option( 'list_date' ) ) { ?>
	<li class="list-date"><time datetime="<?php echo get_the_date('Y-m-d'); ?> <?php echo get_the_time('H:i:s'); ?>"><?php the_time('m/d'); ?></time></li>
<?php } ?>
<?php }

// 列表时间
function list_date_time() { ?>
	<li class="list-date"><time datetime="<?php echo get_the_date('Y-m-d'); ?> <?php echo get_the_time('H:i:s'); ?>"><?php the_time('m/d'); ?> <span><?php echo get_the_time('H:i'); ?></span></time></li>
<?php }

// date class
function date_class() {
	$dates = '';
	if ( ! be_get_option( 'list_date' ) ) {
		$dates .= '-date';
		return $dates;
	}
}

// post tag cloud
function post_all_tag_cloud() {
	global $post;
	$number = zm_get_option( 'post_tag_cloud_n' );
	$tag_ids = wp_get_post_tags( $post->ID, array( 'fields' => 'ids' ) );
	if ( $tag_ids ) {
		wp_tag_cloud( array(
			'include'  => $tag_ids,
			'smallest' => 14,
			'largest'  => 14,
			'number'   => $number,
			'unit'     => 'px',
		) );
	}
}

function post_tag_cloud() {
	if (zm_get_option('post_tag_cloud')) {
		echo '<span class="post-tag">';
		post_all_tag_cloud();
		echo '</span>';
	}
}

// header widget
function top_widget() {
	if ( !zm_get_option( 'h_widget_p' ) || ( !wp_is_mobile() ) ) {
		if ( !zm_get_option( 'h_widget_m' ) || ( zm_get_option( 'h_widget_m' ) == 'cat_single_m' ) ) {
			if ( is_category() || is_single() ) {
				get_template_part( '/template/header-widget' );
			}
		}

		if ( zm_get_option( 'h_widget_m' ) == 'cat_m' ) {
			if ( is_category() ) {
				get_template_part( '/template/header-widget' );
			}
		}

		if ( zm_get_option( 'h_widget_m' ) == 'all_m' ) {
			get_template_part( '/template/header-widget' );
		}
	}
}

// if nomig
function nomig() {
	global $post;
	$content = $post->post_content;
	preg_match_all( '/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER );
	$n = count( $strResult[1] );
	return $n;
}

function remove_footer() { ?>
<?php 
	add_action( 'wp_footer', 'remove_my_action', 1 );
	function remove_my_action() {
		remove_action( 'wp_footer', 'zm_copyright_tips' );
	}

	add_action( 'wp_footer', 'remove_toc', 1 );
	function remove_toc() {
		remove_action( 'wp_footer', 'toc_footer' );
	}

	add_action( 'wp_footer', 'remove_down_file' );
	function remove_down_file() {
		remove_action( 'wp_footer', 'begin_down_file', 99 );
	}
?>
<?php }

function be_img_excerpt() { ?>
	<?php if ( be_get_option( 'hide_box' ) ) { ?>
		<div class="hide-box">
			<div class="hide-excerpt">
				<?php if ( has_excerpt('') ) {
						echo wp_trim_words( get_the_excerpt(), 30, '...' );
					} else {
						$content = get_the_content();
						$content = wp_strip_all_tags( str_replace( array('[',']' ),array('<','>' ),$content ) );
						echo wp_trim_words( $content, 30, '...' );
					}
				?>
			</div>
		</div>
	<?php } ?>
<?php }

function mouse_cursor() { ?>
	<div class="mouse-cursor cursor-outer"></div>
	<div class="mouse-cursor cursor-inner"></div>
<?php }

// 背景
function be_back_img() {
	if ( zm_get_option( 'bing_reg' ) ) {
		$imgurl = get_template_directory_uri() . '/template/bing.php';
	} else {
		$imgurl = zm_get_option( 'reg_img' );
	}
	echo'<style type="text/css">body.custom-background, body{background: url('.$imgurl.') no-repeat fixed center / cover !important;}</style>';
}

function be_reg_ajax_js() {
	if ( zm_get_option( 'go_reg' ) ) {
		echo'<script>jQuery(document).ready(function($){jQuery.ajax({url:ajax_pages_login.ajax_url,method:"POST",data:{action:"load_login_pages",},success:function(data){jQuery(".reg-page-box").html(data);login_script();captcha_script()}})});</script>';
	} else {
		echo'<script>jQuery(document).ready(function($){jQuery.ajax({url:ajax_pages_login.ajax_url,method:"POST",data:{action:"load_login_pages",},success:function(data){jQuery(".reg-page-box").html(data);login_script();}})});</script>';
	}
}

// 注册登录短代码
add_shortcode( 'beregister', 'be_reg_ajax_js' );

function has_shortcode_in_post( $post_id, $shortcode ) {
	$content = get_post_field( 'post_content', $post_id );
	return strpos( $content, '[' . $shortcode . ']' ) !== false;
}

// 鼠标特效
if ( zm_get_option( 'mouse_cursor' ) && !wp_is_mobile() ) {
	add_action( 'wp_footer', 'mouse_cursor' );
}

function cur() {
	if ( zm_get_option( 'mouse_cursor' ) && !wp_is_mobile() ) {
		return ' cur';
	}
}

function get_all_cat_id() { ?>
	<div class="to-up" title="返回顶部"><div class="to-area"></div></div>
	<div class="to-down" title="转到底部"><div class="to-area"></div></div>
	<div class="opid">
		<div class="options-caid op-caid">
			<i class="be be-sort"></i>
		</div>
		<div class="catid-list op-id-list">
			<div class="catid-site-box">
				<span class="arrow-right">
			</div>
			<div class="catid-site">
				<div class="catid-t">分类ID</div>
				<div class="type-name">分类</div>
				<div class="type-id"><?php show_id(); ?></div>
				<?php if ( post_type_exists( 'bulletin' ) ) { ?>
					<div class="type-name">公告</div>
					<div class="type-id"><?php notice_show_id(); ?></div>
				<?php } ?>

				<?php if ( post_type_exists( 'picture' ) ) { ?>
					<div class="type-name">图片</div>
					<div class="type-id"><?php gallery_show_id(); ?></div>
				<?php } ?>

				<?php if ( post_type_exists( 'video' ) ) { ?>
					<div class="type-name">视频</div>
					<div class="type-id"><?php videos_show_id(); ?></div>
				<?php } ?>

				<?php if ( post_type_exists( 'tao' ) ) { ?>
					<div class="type-name">商品</div>
					<div class="type-id"><?php taobao_show_id(); ?></div>
				<?php } ?>

				<?php if ( post_type_exists( 'show' ) ) { ?>
					<div class="type-name">项目</div>
					<div class="type-id"><?php products_show_id(); ?></div>
				<?php } ?>

				<?php if ( post_type_exists( 'sites' ) ) { ?>
					<div class="type-name">网址</div>
					<div class="type-id"><?php favorites_show_id(); ?></div>
				<?php } ?>

				<?php if ( function_exists( 'is_shop' ) ) { ?>
				<div class="type-name">WOO分类</div>
				<div class="type-id"><?php product_show_id(); ?></div>
				<?php } ?>
			</div>
		</div>
	</div>

	<div class="opid">
		<div class="special-id op-caid"><i class="be be-sort"></i></div>
			<div class="special-id-list op-id-list"><div class="catid-site-box"><span class="arrow-right"></div><div class="catid-site"><div class="catid-t">专栏专题ID</div>
				<div class="type-name">专栏</div>
				<div class="type-id"><?php column_show_id(); ?></div>
				<div class="type-name">专题</div>
				<div class="type-id"><?php special_show_id(); ?></div>
			</div>
		</div>
	</div>
<?php }

function title_l() {
	if ( zm_get_option( 'title_l' ) ) {
		echo '<span class="title-l"></span>';
	}
}

function blog_sidebar() { ?>
<?php if ( is_singular() ) { ?>
<?php if ( get_post_meta(get_the_ID(), 'sidebar_l', true) ) { ?>
<div id="sidebar-l" class="widget-area all-sidebar">
<?php } else { ?>
<div id="sidebar" class="widget-area all-sidebar">
<?php } ?>
<?php } else { ?>
<div id="sidebar" class="widget-area all-sidebar">
<?php } ?>

	<div class="widget-blog">
		<?php if ( ! dynamic_sidebar( 'sidebar-h' ) ) : ?>
			<aside id="add-widgets" class="widget widget_text">
				<h3 class="widget-title"><i class="be be-warning"></i>添加小工具</h3>
				<div class="add-widget-tip">
					<a href="<?php echo admin_url(); ?>widgets.php" target="_blank">点此为“博客布局侧边栏”添加小工具</a>
				</div>
			</aside>
		<?php endif; ?>
	</div>
	<?php be_help( $text = '博客布局侧边栏' ); ?>
</div>
<div class="clear"></div>
<?php }

function search_sidebar() { ?>
<div id="sidebar" class="widget-area all-sidebar">
	<div class="widget-blog">
		<?php if ( ! dynamic_sidebar( 'search-results' ) ) : ?>
			<aside id="add-widgets" class="widget widget_text">
				<h3 class="widget-title"><i class="be be-warning"></i>添加小工具</h3>
				<div class="add-widget-tip">
					<a href="<?php echo admin_url(); ?>widgets.php" target="_blank">点此为“搜索结果侧边栏”添加小工具</a>
				</div>
			</aside>
		<?php endif; ?>
	</div>
</div>
<div class="clear"></div>
<?php }

// sticky comments
function be_sticky_comments() { ?>
	<?php 
		global $post;
		$query_args = array(
			'number'      => '10000',
			'status'      => 'approve',
			'post_status' => 'publish',
			'post_id'     => $post->ID,
			'meta_query'  => array(
				array(
					'key'    => 'comment_sticky',
					'value'  => '1'
				)
			)
		);
		$query    = new WP_Comment_Query;
		$comments = $query->query( $query_args );
	?>

	<?php if ( $comments ) : ?>
		<ul class="sticky-comments-box comment-list">
			<?php if ( $comments ) { ?>
				<?php foreach ( $comments as $comment ) { ?>
					<li class="sticky-comments ms">
						<a class="sticky-comments-inf" href="<?php echo get_permalink( $comment->comment_post_ID ); ?>#anchor-comment-<?php echo $comment->comment_ID; ?>">
							<?php if ( get_option( 'show_avatars' ) ) { ?>
								<span class="sticky-comments-avatar load">
									<?php if ( ! zm_get_option( 'avatar_load' ) ) { ?>
										<?php echo get_avatar( $comment->comment_author_email, '96', '', get_comment_author( $comment->comment_ID ) ); ?>
									<?php } else { ?>
										<img class="avatar photo" src="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=" alt="<?php echo get_comment_author( $comment->comment_ID ); ?>" width="96" height="96" data-original="<?php echo preg_replace( array( '/^.+(src=)(\"|\')/i', '/(\"|\')\sclass=(\"|\').+$/i' ), array( '', '' ), get_avatar( $comment->comment_author_email, '96' ) ); ?>">
									<?php } ?>
								</span>
							<?php } ?>
							<span class="sticky-comments-author"><?php echo get_comment_author( $comment->comment_ID ); ?></span>
						</a>
						<span class="sticky-comments-date">
							<time datetime="<?php echo get_comment_date( 'Y-m-d', $comment->comment_ID ); ?> <?php echo get_comment_date( 'H:i:s', $comment->comment_ID ); ?>"><?php echo get_comment_date( '', $comment->comment_ID ); ?> <?php echo get_comment_date( 'H:i:s', $comment->comment_ID ); ?></time>
						</span>
						<span class="sticky-comments-ico"><i class="be be-thumbs-up-o"></i></span>
						<span class="clear"></span>
						<p><?php echo convert_smilies( $comment->comment_content ); ?></p>
						<span class="clear"></span>
					</li>
				<?php } ?>
			<?php } ?>
		</ul>
	<?php endif; ?>
<?php }

// 评论信息
function comment_counts_stat() { ?>
	<div class="comments-title comment-counts ms betip" <?php aos_a(); ?>>
		<?php
			global $wpdb, $post, $numPingBacks;
			$count_admin = '';
			$my_email = get_bloginfo ( 'admin_email' );
			$str = "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_post_ID = $post->ID 
			AND comment_approved = '1' AND comment_type not in ('trackback','pingback') AND comment_author_email";
			$count_all = $post->comment_count;
			$count_guest = $wpdb->get_var( "$str != '$my_email'" );
			$count_author = $wpdb->get_var( "$str = '$my_email'" );
			if ( $count_author >= 1 ) {
				$count_admin = '&nbsp;&nbsp;<i class="be be-timerauto ri"></i>' . sprintf( __( '作者', 'begin' ) ) . '&nbsp;&nbsp;<span>' . $count_author . '</span>';
			} else {
				$count_admin = '';
			}

			if ( $numPingBacks >= 1 ) {
				$count_ping = '&nbsp;&nbsp;<i class="dashicons dashicons-buddicons-groups ri"></i>' . sprintf( __( '引用', 'begin' ) ) . '&nbsp;&nbsp;<span>' . $numPingBacks . '</span>';
			} else {
				$count_ping = '';
			}
			echo '<i class="be be-speechbubble ri"></i>' . sprintf( __( '评论', 'begin' ) ) . '&nbsp;&nbsp;<span>' . $count_all . '</span>&nbsp;&nbsp;<i class="be be-personoutline ri"></i>' . sprintf( __( '访客', 'begin' ) ) . '&nbsp;&nbsp;<span>' . $count_guest . '</span>' . $count_admin .'' . $count_ping;
		?>
		<?php be_help( $text = '主题选项→ 评论设置 → 评论信息' ); ?>
	</div>
<?php }

// tab widget load
function tab_load_img( $post_num ) { ?>
	<?php
		$i = 0;
		$load = get_posts( array( 'posts_per_page' => $post_num ) );
		foreach ( $load as $post ) {
		$i++;
	?>
		<li>
			<span class="thumbnail"></span>
			<span class="tab-load-item tab-load-title load-item-<?php echo $i; ?>"></span>
			<span class="tab-load-item tab-load-inf"></span>
		</li>
	<?php } ?>
<?php }

function tab_load_text( $post_num ) { ?>
	<?php
		$i = 0;
		$load = get_posts( array( 'posts_per_page' => $post_num ) );
		foreach ( $load as $post ) {
		$i++;
	?>
	<li>
		<span class="tab-load-text load-item-<?php echo $i; ?>"></span>
	</li>
	<?php } ?>
<?php }

// 分类图片通栏
function top_sub() { ?>
	<?php if ( ! is_front_page() ) { ?>
		<?php if ( zm_get_option( 'top_sub' ) ) { ?>
			<div class="top-sub<?php if ( ! zm_get_option( 'breadcrumb_on' ) ) { ?> top-sub-b<?php } ?>">
				<?php get_template_part( 'template/header-img' ); ?>
			</div>
		<?php } ?>

		<?php if ( get_post_meta( get_the_ID(), 'header_img_wide', true ) || get_post_meta( get_the_ID(), 'header_bg_wide', true ) ) { ?>
			<div class="top-sub<?php if ( ! zm_get_option( 'breadcrumb_on' ) ) { ?> top-sub-b<?php } ?>">
				<?php get_template_part( 'template/header-slider' ); ?>
			</div>
		<?php } ?>
	<?php } ?>
<?php }
add_action( 'be_header_sub', 'top_sub' );

// 面包屑导航
function be_breadcrumbs_nav() { ?>
	<?php if ( zm_get_option( 'breadcrumb_on' ) ) { ?>
		<nav class="bread">
			<div class="be-bread">
				<?php be_breadcrumbs(); ?>
			</div>
		</nav>
	<?php } else { ?>
		<div class="bread-clear"></div>
	<?php } ?>
<?php }
add_action( 'be_header_sub', 'be_breadcrumbs_nav' );

// 头部小工具
function header_widget() { ?>
	<?php if ( zm_get_option( 'h_widget_m' ) == 'all_m' ) { ?>
		<?php top_widget(); ?>
	<?php } ?>
<?php }
add_action( 'be_header_sub', 'header_widget' );

// 头部广告
function header_ads() { ?>
	<?php get_template_part( 'ad/ads', 'header' ); ?>
<?php }
add_action( 'be_header_sub', 'header_ads' );

// 分类图片
function header_img() { ?>
	<?php if ( ! zm_get_option( 'top_sub' ) ) { ?>
		<?php get_template_part( 'template/header-img' ); ?>
	<?php } ?>
<?php }
add_action( 'be_header_sub', 'header_img' );

// 头部其它
function header_sub() { ?>
	<?php get_template_part( 'template/header-sub' ); ?>
<?php }
add_action( 'be_header_sub', 'header_sub' );

// 头部幻灯
function header_slider() { ?>
	<?php if ( ! get_post_meta( get_the_ID(), 'header_img_wide', true ) && ! get_post_meta( get_the_ID(), 'header_bg_wide', true ) ) { ?>
		<?php get_template_part( 'template/header-slider' ); ?>
	<?php } ?>
<?php }
add_action( 'be_header_sub', 'header_slider' );

// ajax random post load
function random_load() { ?>
	<?php
		$i = 0;
		$load = get_posts( array( 'posts_per_page' => zm_get_option( 'random_number' ) ) );
		foreach ( $load as $post ) {
		$i++;
	?>
		<li>
			<?php if ( zm_get_option( 'random_post_img' ) ) { ?>
				<span class="thumbnail"></span>
				<span class="tab-load-item tab-load-title load-item-<?php echo $i; ?>"></span>
				<span class="tab-load-item tab-load-inf"></span>
			<?php } else { ?>
				<span class="random-load-item random-load-title load-item-<?php echo $i; ?>"></span>
			<?php } ?>
		</li>
	<?php } ?>
<?php }

// 随机文章
add_action('wp_ajax_random_action', 'random_action_callback');
add_action('wp_ajax_nopriv_random_action', 'random_action_callback'); 

function random_action_callback() {
	random_query();
	wp_die();
}

function random_query() {
	$args = array(
		'orderby'             => 'rand',
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => zm_get_option( 'random_number' ),
		'category__not_in'    => explode( ',', zm_get_option( 'random_exclude_id' ) ),
		'ignore_sticky_posts' => 1
	);

	$query = new WP_Query( $args );
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) : $query->the_post();
		if ( zm_get_option( 'random_post_img' ) ) {
			echo '<li>';
			echo '<span class="thumbnail">';
			echo zm_thumbnail();
			echo '</span>';
			echo '<span class="new-title">';
			echo '<a href="';
			echo the_permalink();
			echo '" rel="bookmark">';
			echo the_title();
			echo '</a>';
			echo '</span>';
			echo grid_meta();
			echo views_span();
			echo '</li>';
		} else {
			echo '<li class="srm the-icon">';
			the_title( sprintf( '<a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' );
			echo '</li>';
		}
		endwhile;
		wp_reset_postdata();
	} else {
		echo '<li>'. __( '暂无文章', 'begin' ) .'</li>';
	}
}

// help
function be_help( $text='' ) { ?>
	<?php if ( zm_get_option( 'be_debug' ) && current_user_can( 'manage_options' ) ) { ?>
		<div class="be-help-box">
			<div class="be-help-btu"><span class="cx cx-begin"></span></div>
			<div class="be-help"><span class="dashicons dashicons-arrow-left"></span><?php echo $text; ?></div>
		</div>
	<?php } ?>
<?php }

function cms_help( $text='', $number='' ) { ?>
	<?php if ( zm_get_option( 'be_debug' ) && current_user_can( 'manage_options' ) ) { ?>
		<div class="be-help-box">
			<div class="be-help-btu"><span class="cx cx-begin"></span></div>
			<div class="be-help sort_help"><span class="dashicons dashicons-arrow-left"></span><span class="help-number"><?php echo be_get_option( $number ); ?></span><?php echo $text; ?></div>
		</div>
	<?php } ?>
<?php }

function cms_help_n( $text='', $number='' ) { ?>
	<?php if ( zm_get_option( 'be_debug' ) && current_user_can( 'manage_options' ) ) { ?>
		<div class="be-help-box">
			<div class="be-help-btu"><span class="cx cx-begin"></span></div>
			<div class="be-help sort_help"><span class="dashicons dashicons-arrow-left"></span><span class="help-number"><?php echo $number; ?></span><?php echo $text; ?></div>
		</div>
	<?php } ?>
<?php }

function co_help( $text='', $number='' ) { ?>
	<?php if ( zm_get_option( 'be_debug' ) && current_user_can( 'manage_options' ) ) { ?>
		<div class="be-help-box">
			<div class="be-help-btu"><span class="cx cx-begin"></span></div>
			<div class="be-help sort_help"><span class="dashicons dashicons-arrow-left"></span><span class="help-number"><?php echo co_get_option( $number ); ?></span><?php echo $text; ?></div>
		</div>
	<?php } ?>
<?php }

function co_help_n( $text='', $number='' ) {
	?>
	<?php if ( zm_get_option( 'be_debug' ) && current_user_can( 'manage_options' ) ) { ?>
		<div class="be-help-box">
			<div class="be-help-btu"><span class="cx cx-begin"></span></div>
			<div class="be-help sort_help"><span class="dashicons dashicons-arrow-left"></span><span class="help-number"><?php echo $number; ?></span><?php echo $text; ?></div>
		</div>
	<?php } ?>
<?php }

function bu_help( $text='', $number='' ) { ?>
	<?php if ( zm_get_option( 'be_debug' ) && current_user_can( 'manage_options' ) ) { ?>
		<div class="be-help-box">
			<div class="be-help-btu"><span class="cx cx-begin"></span></div>
			<div class="be-help sort_help"><span class="dashicons dashicons-arrow-left"></span><span class="help-number"><?php echo be_build( get_the_ID(), $number ); ?></span><?php echo $text; ?></div>
		</div>
	<?php } ?>
<?php }

function be_help_img( $text='' ) {
	$html = '';
	if ( zm_get_option( 'be_debug' ) && current_user_can( 'manage_options' ) ) {
		$html .= '<div class="be-help-box">';
			$html .= '<div class="be-help-btu"><span class="cx cx-begin"></span></div>';
			$html .= '<div class="be-help"><span class="dashicons dashicons-arrow-left"></span>' . $text . '</div>';
		$html .= '</div>';
	}
	return $html;
}

// 波浪
function waves() { ?>
<div class="water">
	<svg class="waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
		viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">
		<defs>
			<path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" />
		</defs>
		<g class="parallax">
			<use xlink:href="#gentle-wave" x="48" y="0" fill="var(--be-wave-0)" />
			<use xlink:href="#gentle-wave" x="48" y="3" fill="var(--be-wave-3)" />
			<use xlink:href="#gentle-wave" x="48" y="5" fill="var(--be-wave-5)" />
			<use xlink:href="#gentle-wave" x="48" y="7" fill="var(--be-wave-7)" />
		</g>
	</svg>
</div>
<?php }

// 滚动位置
if ( zm_get_option( 'head_progress' ) ) {
	add_action('wp_body_open', 'top_progress');
}

function top_progress() { ?>
<span class="be-progress"></span>
<?php }