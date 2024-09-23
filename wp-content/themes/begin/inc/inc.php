<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// 移除默认小工具
function remove_default_wp_widgets() {
	unregister_widget( 'WP_Widget_Recent_Comments' );
	unregister_widget( 'WP_Widget_Recent_Posts' );
	unregister_widget( 'WP_Widget_Meta' );
	unregister_widget( 'WP_Widget_Media_Gallery' );
	unregister_widget( 'WP_Widget_RSS' );
	unregister_widget( 'WP_Widget_Pages' );
}
add_action( 'widgets_init', 'remove_default_wp_widgets', 11 );
// 移除后台标题WP
add_filter( 'admin_title', 'zm_custom_admin_title', 10, 2 );
function zm_custom_admin_title( $admin_title, $title ) {
	return $title . ' &lsaquo; ' . get_bloginfo( 'name' );
}
add_filter( 'login_title', 'zm_custom_login_title', 10, 2 );
function zm_custom_login_title( $login_title, $title ){
	return $title.' &lsaquo; '.get_bloginfo( 'name' );
}
// 移除WP标志
function hidden_admin_bar_remove() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu( 'wp-logo' );
}
add_action( 'wp_before_admin_bar_render', 'hidden_admin_bar_remove', 0 );
// 移除隐私
function be_disable_privacy( $required_capabilities, $requested_capability, $user_id, $args ) {
	$privacy_capabilities = array( 'manage_privacy_options', 'erase_others_personal_data', 'export_others_personal_data' );
	if ( in_array( $requested_capability, $privacy_capabilities ) ) {
		$required_capabilities[] = 'do_not_allow';
	}
	return $required_capabilities;
}
add_filter( 'map_meta_cap', 'be_disable_privacy', 10, 4 );
require get_template_directory() . '/inc/license.php';
add_filter( 'comment_reply_link', 'begin_reply_link', 10, 4 );
function begin_reply_link( $link, $args, $comment, $post ) {
	$onclick = sprintf( 'return addComment.moveForm( "%1$s-%2$s", "%2$s", "%3$s", "%4$s" )',
		$args['add_below'], $comment->comment_ID, $args['respond_id'], $post->ID
	);
	$link = sprintf( "<span class='reply'><a rel='nofollow' class='comment-reply-link' href='%s' onclick='%s' aria-label='%s'>%s</a></span>",
		esc_url( add_query_arg( 'replytocom', $comment->comment_ID, get_permalink( $post->ID ) ) ) . "#" . $args['respond_id'],
		$onclick,
		esc_attr( sprintf( $args['reply_to_text'], $comment->comment_author ) ),
		$args['reply_text']
	);
	return $link;
}
be_themes_install();

// 提交信息
if ( cx_get_option( 'publish_info' ) ) {
	require get_template_directory() . '/inc/publish-profile.php';
	require get_template_directory() . '/inc/profile-insert.php';
}

// 文本模式面板
if ( zm_get_option( 'edit_in_html' ) ) {
	require get_template_directory() . '/inc/edit-html.php';
}

// 筛选小工具
if ( zm_get_option( 'filter_widgets' ) ) {
	require get_template_directory() . '/inc/widgets-filter.php';
}

// 图片滑块
require get_template_directory() . '/inc/tinymce/insert-moveslider.php';

// rewrite paged url
if ( zm_get_option( 'rewrite_paged_url' ) ) {
function be_base_paged() {
	$GLOBALS['wp_rewrite']->pagination_base = zm_get_option( 'rewrite_paged_url_txt' );
}

add_action( 'init', 'be_base_paged' );

function be_rewrite_paged( $rules ) {
	$new_rules = array(
	'obchod/zm_get_option( "rewrite_paged_url_txt" )/([0-9]{1,})/?$' => 'index.php?post_type=product&paged=$matches[1]',
	);

	$rules = array_merge( $new_rules, $rules );
	return $rules;
}

add_filter( 'rewrite_rules_array', 'be_rewrite_paged' );
}

function views_span() {
	if ( zm_get_option('post_views') ) {
		be_the_views( true, '<span class="views"><i class="be be-eye ri"></i>','</span>' );
	}
}

function be_views_inf() {
	if ( zm_get_option('post_views') ) {
		return be_the_views( false, '<span class="views">','</span>' );
	}
}

function be_views_ico_inf() {
	if ( zm_get_option('post_views') ) {
		return be_the_views( false, '<span class="views"><i class="be be-eye ri"></i>','</span>' );
	}
}

function views_li() {
	if ( zm_get_option('post_views') ) {
		be_the_views( true, '<li class="views"><i class="be be-eye ri"></i>','</li>' );
	}
}

function views_qa() {
	if ( zm_get_option('post_views') ) {
		be_the_views( true, '<span class="qa-meta qa-views"><i>' . sprintf(__( '浏览', 'begin' )) . '</i>','</span>' );
	}
}

function views_tao() {
	if ( zm_get_option('post_views') ) {
		be_the_views( true, '', '件' );
	}
}

function views_videos() {
	if ( zm_get_option('post_views') ) {
		be_the_views( true, '' . sprintf( __( '观看', 'begin' ) ) . '： ','' );
	}
}

function views_video() {
	if ( zm_get_option('post_views') ) {
		be_the_views( true, '' . sprintf( __( '观看', 'begin' ) ) . '：',' ' . sprintf( __( '次', 'begin' ) ) . '' );
	}
}

function views_print() {
	if ( zm_get_option('post_views') ) { print ''; be_the_views(); print ''; }
}

function views_group() {
	if ( zm_get_option('post_views') ) { be_the_views( true, '<div class="group-views"><i class="be be-eye ri"></i>','</div>' ); }
}

function collect_meta_new() {
	echo '<span class="cat">';
	zm_category();
	echo '</span>';
	echo '<span class="date">';
	echo '<time datetime="';
	echo get_the_date('Y-m-d');
	echo ' ' . get_the_time('H:i:s');
	echo '">';
	time_ago( $time_type ='post' );
	echo '</time></span>';
}

function collect_meta() {
	if ( zm_get_option('post_views') ) {
		be_the_views( true, '<span class="views"><i class="be be-eye ri"></i>','</span>' );
	}

	echo '<span class="date">';
	echo '<time datetime="';
	echo get_the_date('Y-m-d');
	echo ' ' . get_the_time('H:i:s');
	echo '">';
	time_ago( $time_type ='post' );
	echo '</time></span>';
}

function collect_meta_comment() {
	if ( post_password_required() ) { 
		echo '<span class="comment"><a href=""><i class="icon-scroll-c ri"></i>' . sprintf(__( '密码保护', 'begin' )) . '</a></span>';
	} else {
		if ( ! zm_get_option( 'close_comments' ) ) {
			echo '<span class="comment">';
				comments_popup_link( '<span class="no-comment"><i class="be be-speechbubble ri"></i>' . sprintf(__( '评论', 'begin' )) . '</span>', '<i class="be be-speechbubble ri"></i>1 ', '<i class="be be-speechbubble ri"></i>%' );
			echo '</span>';
		}
	}

	echo '<span class="meta-cat">';
		zm_category();
	echo '</span>';
}

function collect_meta_vip() {
	$rec = ( array ) be_get_option( 'rectab' );
	if ( is_plugin_active( 'erphpdown/erphpdown.php' ) ) {
		echo '<span class="vip-cat">';
		zm_category();
		echo '</span>';
		be_vip_meta();
	} else {
		if ( $rec['cms_collect_asset_zan'] !== 'zm_like' ) {
			zm_category();
		} else {
			echo '<i class="be be-thumbs-up-o"> ' . get_post_meta(get_the_ID(), 'zm_like', true ) . '</i>';
		}
	}
	echo '<span class="date">';
	echo '<time datetime="';
	echo get_the_date('Y-m-d');
	echo ' ' . get_the_time('H:i:s');
	echo '">';
	time_ago( $time_type ='post' );
	echo '</time></span>';
}

function collect_meta_qa() {
	if ( class_exists( 'AnsPress' ) ) {
		echo '<span class="ap-ans-count">';
		echo esc_attr( sprintf( _n( '%d 答案', '%d 答案', ap_get_answers_count(), 'begin' ), ap_get_answers_count() ) );
		echo '</span>';

		echo '<span class="ap-vote-count">';
		echo esc_attr( sprintf( _n( '%d 投票', '%d 投票', ap_get_votes_net(), 'begin' ), ap_get_votes_net() ) );
		echo '</span>';
	}
}

// 添加属性
if ( zm_get_option( 'lightbox_on' ) ) {
add_filter( 'the_content', 'pirobox_auto', 99 );
add_filter( 'the_excerpt', 'pirobox_auto', 99 );
}
function pirobox_auto( $content ) {
	global $post;
	$pattern = "/<a(.*?)href=('|\")([A-Za-z0-9\/_\.\~\:-]*?)(\.bmp|\.gif|\.jpg|\.jpeg|\.webp|\.png)('|\")([^\>]*?)>/i";
	$replacement = '<a$1href=$2$3$4$5$6 data-fancybox="gallery">';
	$content = preg_replace( $pattern, $replacement, $content );
	return $content;
}

if ( zm_get_option( 'lazy_e' ) ) {
	add_filter ('the_content', 'lazyload' );
	function lazyload( $content ) {
		$loadimg_url = get_template_directory_uri() . '/img/loading.png';
		if ( ! is_feed() || ! is_robots() ) {
			$content = preg_replace( '/<img(.+)src=[\'"]([^\'"]+)[\'"](.*)>/i', "<img$1data-original=\"$2\" src=\"$loadimg_url\"$3>\n", $content );
		}
		return $content;
	}
}

if ( zm_get_option( 'auto_img_link' ) ) {
	add_filter ( 'the_content', 'be_img_link', 0 );
}
function be_img_link( $content ) {
	$content = preg_replace( '/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i', "<a href=\"$2\" ><img src=\"$2\" /></a>", $content );
	return $content;
}

// add post class
function be_post( $classes ) {
	$classes[] = 'post';
	return $classes;
}

function be_set_title(){
	$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
	echo $title = $term->name;
}

// seo
function zm_og_excerpt( $len=220 ) {
	if ( is_single() || is_page() ) {
		global $post;
		if ( $post->post_excerpt) {
			if ( preg_match( '/<p>(.*)<\/p>/iU',trim( strip_tags( $post->post_excerpt,"<p>" ) ), $result ) ) {
				$post_content = $result['1'];
			} else {
				$post_content_r = explode( "\n",trim( strip_tags( strip_shortcodes($post->post_excerpt ) ) ) );
				$post_content = wp_strip_all_tags( str_replace( array( '[','][/',']' ), array('<','>' ), $post_content_r['0'] ) );
			}
			$excerpt = preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,0}'.'((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,' . $len . '}).*#s', '$1', $post_content);
		} else {
			if ( preg_match( '/<p>(.*)<\/p>/iU',trim( strip_tags( $post->post_content,"<p>" ) ), $result ) ) {
				$post_content = $result['1'];
			} else {
				$post_content_r = explode( "\n",trim( strip_tags( strip_shortcodes($post->post_content ) ) ) );
				$post_content = wp_strip_all_tags( str_replace( array( '[','][/',']' ), array('<','>' ), $post_content_r['0'] ) );
			}
			$excerpt = preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+ ){0,0}'.'((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,' . $len . '}).*#s', '$1', $post_content);
		}
		return str_replace( array( "\r\n", "\r", "\n" ), "", $excerpt );
	}
}

function og_post_img() {
	global $post;
	$src = '';
	$content = $post->post_content;
	preg_match_all( '/<img .*?src=[\"|\'](.+?)[\"|\'].*?>/', $content, $strResult, PREG_PATTERN_ORDER );
	$n = count( $strResult[1] );
	if ( $n >= 3 ){
		$src = $strResult[1][0];
	} else {
		if ( $values = get_post_custom_values( "thumbnail" ) ) {
			$values = get_post_custom_values( "thumbnail" );
			$src = $values[0];
		} elseif ( has_post_thumbnail() ) {
			$thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
			$src = $thumbnail_src[0];
		} else {
			if ( $n > 0 ) {
				$src = $strResult[1][0];
			}
		}
	}
	return $src;
}

// 只搜索文章标题
function only_search_by_title( $search, $wp_query ) {
	if ( ! empty( $search ) && ! empty( $wp_query->query_vars['search_terms'] ) ) {
		global $wpdb;
		$q = $wp_query->query_vars;
		$n = ! empty( $q['exact'] ) ? '' : '%';
		$search = array();
		foreach ( ( array ) $q['search_terms'] as $term )
			$search[] = $wpdb->prepare( "$wpdb->posts.post_title LIKE %s", $n . $wpdb->esc_like( $term ) . $n );
		if ( ! is_user_logged_in() )
			$search[] = "$wpdb->posts.post_password = ''";
		$search = ' AND ' . implode( ' AND ', $search );
	}
	return $search;
}

// 修改搜索URL
function redirect_to_search_url() {
	if ( isset( $_GET['s'] ) ) {
		$search_term = sanitize_text_field( $_GET['s'] );
		wp_redirect( home_url( '/search/' ) . urlencode( $search_term ) . '/');
		exit();
	}
}

if ( ! zm_get_option( 'search_option' ) || ( zm_get_option('search_option' ) == 'search_url' ) ) {
	add_action('template_redirect', 'redirect_to_search_url');
}
// 搜索跳转
if ( zm_get_option( 'auto_search_post' ) ) {
add_action('template_redirect', 'redirect_search_post');
}
function redirect_search_post() {
	if (is_search()) {
		global $wp_query;
		if ($wp_query->post_count == 1 && $wp_query->max_num_pages == 1) {
			wp_redirect( get_permalink( $wp_query->posts['0']->ID ) );
			exit;
		}
	}
}

// 分类搜索
function search_cat_args() { 
	$args = array(
		'exclude'    => zm_get_option('not_search_cat'),
		'orderby'    => 'menu_order',
		'hide_empty' => 0
	);
	$categories = get_categories( $args );
?>
<span class="search-cat">
	<select name="cat" class="s-veil">
		<option value="">所有分类</option>
		<?php foreach( $categories as $category ){ ?>
			<option value="<?php echo $category->term_id; ?>"><?php echo $category->name; ?></option>
		<?php } ?>
	</select>
</span>
<?php }

// 排除分类
add_filter( 'pre_get_posts', 'search_filter_cat' );
function search_filter_cat( $query ) {
	if ( zm_get_option( 'search_post_type' ) ) {
		$be_search_post_type = implode( ',', zm_get_option( 'search_post_type' ) );
	} else {
		$be_search_post_type = '';
	}
	if ( !is_admin() && $query->is_search && $query->is_main_query() ) {
		$query->set( 'category__not_in', explode( ',', zm_get_option( 'not_search_cat' ) ) );
		$query->set( 'post_type', explode( ',', $be_search_post_type ) );
	}
	return $query;
}

// 禁用WP搜索
function disable_search( $query, $error = true ) {
	if (is_search() && !is_admin()) {
		$query->is_search = false;
		$query->query_vars['s'] = false;
		$query->query['s'] = false;
		if ( $error == true )
		//$query->is_home = true;
		$query->is_404 = true;
	}
}
if (! zm_get_option('wp_s')) {
add_action( 'parse_query', 'disable_search' );
add_filter( 'get_search_form', function($a){return null;});
}

// 字数统计
function count_words() {
	$post_content = get_post_field( 'post_content', get_the_ID() );
	$striped_tags = strip_tags( $post_content );
	$zh_words = preg_match_all( '/[\x{4e00}-\x{9fa5}]/u', $striped_tags, $matches );
	$en_words = str_word_count( $striped_tags );
	$total_words = $zh_words + $en_words;
	return '<span class="word-count">' . sprintf( __( '字数 %s', 'begin' ), $total_words ) . '</span>';
}

function get_reading_time( $content ) {
	$zm_format = '<span class="reading-time">' . sprintf( __( '阅读', 'begin' ) ) . '%min%' . sprintf( __( '分', 'begin' ) ) . '%sec%' . sprintf( __( '秒', 'begin' ) ) . '</span>';
	$zm_chars_per_minute = 300;

	$post_content = get_post_field( 'post_content', get_the_ID() );
	$striped_tags = strip_tags( $post_content );
	$zh_words = preg_match_all( '/[\x{4e00}-\x{9fa5}]/u', $striped_tags, $matches );
	$en_words = str_word_count( $striped_tags );
	$total_words = $zh_words + $en_words;

	$minutes = floor( $total_words / $zm_chars_per_minute);
	$seconds = floor( $total_words % $zm_chars_per_minute / ( $zm_chars_per_minute / 60) );
	return str_replace( '%sec%', $seconds, str_replace( '%min%', $minutes, $zm_format ) );
}

function reading_time() {
	echo get_reading_time( get_the_content() );
}

// 字数统计
function word_num () {
	global $post;
	$text_num = mb_strlen(preg_replace('/\s/','',html_entity_decode(strip_tags($post->post_content))),'UTF-8');
	return $text_num;
}

// 分类优化
function zm_category() {
	$post_type = get_post_type();
	if ( $post_type !== 'post' ) {
		$taxonomies = get_post_taxonomies( get_the_ID() );
		$term_names = array();

		foreach ( $taxonomies as $taxonomy ) {
			if ( ! is_taxonomy_hierarchical( $taxonomy ) ) {
				continue;
			}

			$terms = get_the_terms( get_the_ID(), $taxonomy );

			if ( $terms && ! is_wp_error( $terms ) ) {
				foreach ( $terms as $term ) {
					$term_link = get_term_link( $term, $taxonomy );
					if ( ! is_wp_error( $term_link ) ) {
						$term_names[] = '<a href="' . esc_url( $term_link ) . '">' . $term->name . '</a>';
					} else {
						$term_names[] = $term->name;
					}
				}
			}
		}

		if ( ! empty( $term_names ) ) {
			echo implode( ', ', $term_names );
		}
	}

	$category = get_the_category();
	if ( $category && $firstCategory = reset( $category ) ) {
		echo '<a href="' . esc_url( get_category_link( $firstCategory->term_id ) ) . '">' . esc_html( $firstCategory->cat_name ) . '</a>';
	}
}

function be_category_inf() {
	$category = get_the_category();
	if ( $category && $firstCategory = reset( $category ) ) {
		return '<a href="' . esc_url( get_category_link( $firstCategory->term_id ) ) . '">' . esc_html( $firstCategory->cat_name ) . '</a>';
	}
}

// 文章数
function be_get_cat_postcount( $id ) {
	$cat = get_category( $id );
	$count = ( int ) $cat->count;
	$tax_terms = get_terms( 'category', array( 'child_of' => $id ) );
	foreach ( $tax_terms as $tax_term ) {
		$count +=$tax_term->count;
	}
	return $count;
}

// 附件ID
function be_get_image_id( $image_url ) {
	if ( is_active_widget( '', '', 'be_attachment_widget' ) ) {
		global $wpdb;
		$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ) ); 
		return $attachment[0];
	}
}

// 点赞
function zm_get_current_count() {
	global $wpdb;
	$current_post = get_the_ID();
	$query = "SELECT post_id, meta_value, post_status FROM $wpdb->postmeta";
	$query .= " LEFT JOIN $wpdb->posts ON post_id=$wpdb->posts.ID";
	$query .= " WHERE post_status='publish' AND meta_key='zm_like' AND post_id = '".$current_post."'";
	$results = $wpdb->get_results($query);
	if ($results) {
		foreach ($results as $o):
			echo $o->meta_value;
		endforeach;
	} else {echo( '0' );}
}

// toc
if ( zm_get_option( 'be_toc') ) {
	function be_toc_content( $content ) {
		global $post; $page;
		$html_comment = '<!--betoc-->';
		$comment_found = strpos( $content, $html_comment ) ? true : false;
		$fixed_location = true;
		if ( !$fixed_location && !$comment_found ) {
			return $content;
		}

		if ( ! is_admin() ) {
			if ( get_post_meta( get_the_ID(), 'no_toc', true ) ) {
				$page_id = get_the_ID();
				$post_id = array( $post->ID );
				if ( is_page( $page_id ) ) {
					return $content;
				}
				if ( is_single( $post_id ) ) {
					return $content;
				}
		 	}

			if ( ! is_singular() ) {
				return $content;
			}
	 	}

		if ( ! zm_get_option( 'toc_mode' ) || ( zm_get_option( 'toc_mode') == 'toc_four' ) ) {
			$regex = "~(<h([4]))(.*?>(.*)<\/h[4]>)~";
		}

		if ( zm_get_option( 'toc_mode') == 'toc_all' ) {
			if ( get_post_meta( get_the_ID(), 'toc_four', true ) ) {
				$regex = "~(<h([4]))(.*?>(.*)<\/h[4]>)~";
			} else {
				$regex = "~(<h([2-6]))(.*?>(.*)<\/h[2-6]>)~";
			}
		}

		preg_match_all( $regex, $content, $heading_results );

		$num_match = count( $heading_results[0] );
		if ( $num_match < zm_get_option( 'toc_title_n' ) ) {
			return $content;
		}

		for ( $i = 0; $i < $num_match; ++ $i ) {
			if ( ! zm_get_option( 'toc_style' ) || ( zm_get_option( 'toc_style' ) == 'tocjq' ) ) {
				$new_heading = "<div class='toc-box-h' name='toc-$i'>" . $heading_results[1][$i] . " id='$i' " . $heading_results[3][$i] . "</div>";
			}

			if ( zm_get_option( 'toc_style' ) == 'tocphp' ) {
				$new_heading = $heading_results[1][$i] . " class='toch' id='$i' " . $heading_results[3][$i];
			}
			$old_heading = $heading_results[0][$i];
			$content = str_replace( $old_heading, $new_heading, $content );
		}
		
		return $content;
	}
	add_filter( 'the_content', 'be_toc_content' );

	function be_toc() {
		global $post; $page;
		$html_comment = "<!--betoc-->";
		if ( have_posts() ) :
			$comment_found = strpos( $post->post_content, $html_comment ) ? true : false;
		else :
			$comment_found = '';
		endif;
		$fixed_location = true;
		if ( ! $fixed_location && !$comment_found ) {
			return $post->post_content;
		}

		if ( get_post_meta( get_the_ID(), 'no_toc', true ) ) {
			if ( have_posts() ) :
				$page_id = get_the_ID();
			else :
				$page_id = '';
			endif;
			$post_id = array( $post->ID );
			if ( is_page( $page_id ) ) {
				return $post->post_content;
			}
			if ( is_single( $post_id ) ) {
				return $post->post_content;
			}
		}
		if ( ! is_singular() ) {
			if ( have_posts() ) :
				return $post->post_content;
			else :
				return '';
			endif;
		}

		if ( !zm_get_option( 'toc_mode' ) || ( zm_get_option( 'toc_mode' ) == 'toc_four' ) ) {
			$regex = "~(<h([4]))(.*?>(.*)<\/h[4]>)~";
		}
		if ( zm_get_option( 'toc_mode' ) == 'toc_all' ) {
			if ( get_post_meta(get_the_ID(), 'toc_four', true ) ) {
				$regex = "~(<h([4]))(.*?>(.*)<\/h[4]>)~";
			} else {
				$regex = "~(<h([2-6]))(.*?>(.*)<\/h[2-6]>)~";
			}
		}

		preg_match_all( $regex, $post->post_content, $heading_results );

		$num_match = count( $heading_results[0] );
		if ( $num_match < zm_get_option( 'toc_title_n' ) ) {
			return $post->post_content;
		}

		$link_list = "";
		for ( $i = 0; $i < $num_match; ++ $i ) {
			$new_heading = $heading_results[1][$i] . " class='toch' id='$i' " . $heading_results[3][$i];
			$old_heading = $heading_results[0][$i];
			$link_list .= "<li class='toc-level toc-level-" . $heading_results[2][$i] . "'><a class='fd' href='#$i' rel='external nofollow'>" . strip_tags( $heading_results[4][$i]) . "</a></li>";
		}

		if ( ! zm_get_option( "toc_style" ) || ( zm_get_option( "toc_style" ) == "tocjq" ) ) {
			$tocli = '<div class="toc-ul-box"><ul class="toc-ul tocjq"></ul></div>';
		}
		if ( zm_get_option( "toc_style" ) == "tocphp" ) {
			$tocli = '<div class="toc-ul-box"><ul class="toc-ul">' . $link_list . '</ul></div>';
		}
		$link_list = '<div class="toc-box">';
		$link_list .= '<nav class="toc-main fd">' . $tocli . '<span class="toc-zd"><span class="toc-close"><i class="dashicons dashicons-dismiss"></i></span></span></nav>';
		$link_list .= '</div>';
		echo $link_list;
	}

// toc footer
function toc_footer() { ?>
	<?php be_toc(); ?>
<?php }

if ( function_exists( 'be_toc' ) ) {
	if ( ! is_active_widget( '', '', 'be_toc_widget' ) || wp_is_mobile() ) {
		add_action( 'betoc', 'toc_footer' );
	}
}
}
// widget content
add_filter( 'the_content', 'be_content_widget' );
function be_content_widget( $content ) {
	ob_start();
	$sidebar = dynamic_sidebar('be-content');
	$new_content = ob_get_clean();
	if ( is_single() && ! is_admin() ) {
		return widget_content( $new_content, zm_get_option('widget_p'), $content );
	}
	return $content;
}

function widget_content( $new_content, $paragraph_id, $content ) {
	$closing_p = '</p>';
	$paragraphs = explode( $closing_p, $content );
	foreach ($paragraphs as $index => $paragraph) {
		if ( trim( $paragraph ) ) {
			$paragraphs[$index] .= $closing_p;
		}
		if ( $paragraph_id == $index + 1 ) {
			$paragraphs[$index] .= $new_content;
		}
	}
	return implode( '', $paragraphs );
}

// copyright disturb
if ( zm_get_option( 'copy_upset' ) ) {
	add_filter( 'the_content', 'copyright_disturb_content' );
	function copyright_disturb_content( $content ) {
		if ( is_single() && 'docs' !== get_post_type() && ! is_admin() ) {
			return be_insert_after_paragraph( $content );
		}
		return $content;
	}

	function be_insert_after_paragraph( $content ) {
		$insert_paragraph_num = zm_get_option( 'copy_upset_n' );
		$indexes = range( 1, $insert_paragraph_num );
		$closing_p = '</p>';
		$paragraphs = explode( $closing_p, $content );
		foreach ( $paragraphs as $index => $paragraph ) {
		if  ( in_array( $index, $indexes ) ) {
				$paragraphs[$index] .= '<span class="beupset' . mt_rand( 10, 100 ) . '">' . zm_get_option( 'copy_upset_txt' ) . '';
				$paragraphs[$index] .= get_bloginfo( 'name' );
				$paragraphs[$index] .= '-';
				$paragraphs[$index] .= get_permalink();
				$paragraphs[$index] .= '</span>';
			}
		}
		return implode( '', $paragraphs );
	}
}

// 图片alt
function add_alt_img( $content ) {
	global $post;
	$pattern = '/<p>.*?<img[^>]+src=["\'](.*?)["\'][^>]*>.*?<\/p>/s';
	preg_match_all( $pattern, $content, $matches );
	if ( ! empty( $matches[0] ) ) {
		foreach ( $matches[0] as $index => $img_tag ) {
			$src = preg_replace( '/<img[^>]+src=[\"\'](.+?)[\"\'].*?>/i', '$1', $img_tag );
			$alt_text = count( $matches[0] ) > 1 ? get_the_title() . '-' . __( '图片', 'begin' ) . ( $index + 1 ) : get_the_title();
			$new_img_tag = str_replace( '<img', '<img alt="' . $alt_text . '"', $img_tag );
			$content = str_replace( $img_tag, $new_img_tag, $content );
		}
	}

	$content = preg_replace( '/\s+alt=""\s*/', ' ', $content );
	return $content;
}

if ( zm_get_option( 'image_alt' ) ) {
	add_filter( 'the_content', 'add_alt_img', 99999 );
}

// 形式名称
function be_post_format( $safe_text ) {
	if ( $safe_text == '引语' )
		return '软件';
	if ( $safe_text == '相册' )
		return '宽图';
	return $safe_text;
}

// 待审角标
function be_menu_pending_counter() {
	global $menu;

	$post_types = get_post_types( array( 'public' => true ), 'objects' );

	foreach ( $post_types as $post_type ) {
		if ( $post_type->name === 'attachment' ) {
			continue;
		}

		$pending_count = wp_count_posts( $post_type->name )->pending;

		if ( $pending_count > 0 ) {
			foreach ( $menu as $key => $item ) {
				if ( $post_type->name === 'post' && $item[2] === 'edit.php' ) {
					$menu[$key][0] .= sprintf( '<span class="menu-pending-counter count-%1$d" title="%1$d 待审文章"><span class="count">%1$d</span></span>', $pending_count );
				} elseif ( $item[2] == 'edit.php?post_type=' . $post_type->name ) {
					$menu[$key][0] .= sprintf( '<span class="menu-pending-counter count-%1$d" title="%1$d 待审%2$s"><span class="count">%1$d</span></span>', $pending_count, $post_type->labels->name );
				}
			}
		}
	}
}

if ( zm_get_option( 'pending_counter' ) ) {
	add_action( 'admin_menu', 'be_menu_pending_counter' );
}

// 链接角标
function be_link_visible_counter() {
	global $menu, $wpdb;
	$private_links_count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->links WHERE link_visible = 'N'" );

	if ( $private_links_count > 0 ) {
		foreach ( $menu as $key => $item ) {
			if ( $item[2] === 'link-manager.php' ) {
				$menu[$key][0] .= sprintf( '<span class="menu-pending-counter count-%1$d" title="%1$d 待审链接"><span class="count">%1$d</span></span>', $private_links_count );
				break;
			}
		}
	}
}

if ( zm_get_option( 'link_visible_counter' ) ) {
	add_action( 'admin_menu', 'be_link_visible_counter' );
}

// 点击最多文章
function get_timespan_most_viewed($mode = '', $limit = 10, $days = 7, $display = true) {
	global $wpdb, $post;
	$limit_date = current_time('timestamp') - ($days*86400);
	$limit_date = date("Y-m-d H:i:s",$limit_date);	
	$where = '';
	$temp = '';
	if (!empty($mode) && $mode != 'both') {
		$where = "post_type = '$mode'";
	} else {
		$where = '1=1';
	}
	$most_viewed = $wpdb->get_results("SELECT $wpdb->posts.*, (meta_value+0) AS views FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE post_date < '".current_time('mysql')."' AND post_date > '".$limit_date."' AND $where AND post_status = 'publish' AND meta_key = 'views' AND post_password = '' ORDER  BY views DESC LIMIT $limit");
	if ($most_viewed) {
		$i = 1;
		foreach ($most_viewed as $post) {
			$post_title = get_the_title();
			$post_views = intval($post->views);
			$post_views = number_format($post_views);
			$temp .= "<li class=\"srm\"><span class='li-icon li-icon-$i'>$i</span><a href=\"".get_permalink()."\">$post_title</a></li>";
			$i++;
		}
	} else {
		$temp = '<li>暂无文章</li>';
	}
	if ($display) {
		echo $temp;
	} else {
		return $temp;
	}
}

// 热门文章
function get_timespan_most_viewed_img( $mode = '', $limit = 10, $days = 7, $display = true ) {
	global $wpdb, $post;
	$limit_date = current_time('timestamp') - ($days*86400);
	$limit_date = date("Y-m-d H:i:s",$limit_date);	
	$where = '';
	$temp = '';
	if ( ! empty( $mode ) && $mode != 'both' ) {
		$where = "post_type = '$mode'";
	} else {
		$where = '1=1';
	}
	$most_viewed = $wpdb->get_results("SELECT $wpdb->posts.*, (meta_value+0) AS views FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE post_date < '".current_time('mysql')."' AND post_date > '".$limit_date."' AND $where AND post_status = 'publish' AND meta_key = 'views' AND post_password = '' ORDER BY views DESC LIMIT $limit");
	if ( $most_viewed ) {
		$i = 1;
		foreach ( $most_viewed as $post ) {
			$post_title = get_the_title();
			$post_views = intval( $post->views );
			$post_views = number_format( $post_views );
			echo "<li>";
			echo "<span class='thumbnail'>";
			echo "<span class='li-icon li-icon-$i'>$i</span>";
			echo zm_thumbnail();
			$i++;
			echo "</span>"; 
			echo the_title( sprintf( '<span class="new-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></span>' ); 
			echo "<span class='date'>";
			echo the_time('m/d');
			echo "</span>";
			echo views_span();
			echo "</li>"; 
		}
	}
}

//点赞最多文章
function get_like_most($mode = '', $limit = 10, $days = 7, $display = true) {
	global $wpdb, $post;
	$limit_date = current_time('timestamp') - ($days*86400);
	$limit_date = !empty($limit_date) || date("Y-m-d H:i:s",$limit_date);
	$where = '';
	$temp = '';
	if (!empty($mode) && $mode != 'both') {
		$where = "post_type = '$mode'";
	} else {
		$where = '1=1';
	}
	$most_viewed = $wpdb->get_results("SELECT $wpdb->posts.*, (meta_value+0) AS zm_like FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE post_date < '".current_time('mysql')."' AND post_date > '".$limit_date."' AND $where AND post_status = 'publish' AND meta_key = 'zm_like' AND post_password = '' ORDER BY zm_like DESC LIMIT $limit");
	if ($most_viewed) {
		$i = 1;

		foreach ($most_viewed as $post) {
			$post_title = get_the_title();
			$temp .= "<li><span class='li-icon li-icon-$i'>$i</span><a href=\"".get_permalink()."\">$post_title</a></li>";
			$i++;
		}
	} else {
		$temp = '<li>暂无文章</li>';
	}
	if ($display) {
		echo $temp;
	} else {
		return $temp;
	}
}

// 点赞最多有图
function get_like_most_img($mode = '', $limit = 10, $days = 7, $display = true) {
	global $wpdb, $post;
	$limit_date = current_time('timestamp') - ($days*86400);
	$limit_date = !empty($limit_date) || date("Y-m-d H:i:s",$limit_date);
	$where = '';
	$temp = '';
	if (!empty($mode) && $mode != 'both') {
		$where = "post_type = '$mode'";
	} else {
		$where = '1=1';
	}
	$most_viewed = $wpdb->get_results("SELECT $wpdb->posts.*, (meta_value+0) AS zm_like FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE post_date < '".current_time('mysql')."' AND post_date > '".$limit_date."' AND $where AND post_status = 'publish' AND meta_key = 'zm_like' AND post_password = '' ORDER BY zm_like DESC LIMIT $limit");
	if ($most_viewed) {
		$i = 1;
		foreach ($most_viewed as $post) {
			$post_title = get_the_title();
			echo "<li>";
			echo "<span class='thumbnail'>";
			echo zm_thumbnail();
			echo "</span>";
			echo the_title( sprintf( '<span class="new-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></span>' );
			echo "<span class='discuss'><i class='be be-thumbs-up-o'>&nbsp;";
			echo zm_get_current_count();
			echo "</i></span>";
			echo "<span class='date'>";
			echo the_time( 'm/d' );
			echo "</span>";
			echo "</li>";
		}
	}
}

// 点赞
function begin_like(){
	global $wpdb,$post;
	$id = $_POST["um_id"];
	$action = $_POST["um_action"];
	if ( $action == 'ding'){
		$bigfa_raters = get_post_meta($id,'zm_like',true);
		$expire = time() + 99999999;
		$domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
		setcookie('zm_like_'.$id,$id,$expire,'/',$domain,false);
		if (!$bigfa_raters || !is_numeric($bigfa_raters)) {
			update_post_meta($id, 'zm_like', 1);
		}
		else {
			update_post_meta($id, 'zm_like', ($bigfa_raters + 1));
		}
		echo get_post_meta($id,'zm_like',true);
	}
	die;
}

// 评论贴图
if (zm_get_option('embed_img')) {
add_action('comment_text', 'comments_embed_img', 2);
}
function comments_embed_img($comment) {
	$size = 'auto';
	$comment = preg_replace(array('#(http://[^\s]*.(jpg|gif|png|JPG|GIF|PNG))#','#(https://[^\s]*.(jpg|gif|png|JPG|GIF|PNG))#'),'<a href="$1" data-fancybox="gallery"><img src="$1" alt="comment" style="width:'.$size.'; height:'.$size.'"></a>', $comment);
	return $comment;
}

// connector
function connector() {
	if ( zm_get_option( 'blank_connector') ) {
		echo '';
	} else {
		echo ' ';
	}
	echo zm_get_option( 'connector');
	if ( zm_get_option( 'blank_connector' ) ) {
		echo '';
	} else {
		echo ' ';
	}
}

function title_name() {
	connector();
	if ( ! zm_get_option( 'blog_name' ) ) {
		bloginfo( 'name' );
	}
}

// title
if (zm_get_option('wp_title')) {
// filters title
function custom_filters_title() { 
	$separator = ''.zm_get_option('connector').'';
	return $separator;
}
add_filter('document_title_separator', 'custom_filters_title');
} else {
function begin_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() ) {
		return $title;
	}
	$title .= get_bloginfo( 'name', 'display' );
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title = "$title $sep $site_description";
	}
	if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
		$title = "$title $sep " . sprintf( __( 'Page %s', 'twentyfourteen' ), max( $paged, $page ) );
	}

	return $title;
}
add_filter( 'wp_title', 'begin_wp_title', 10, 2 );
}

if (zm_get_option('refused_spam')) {
	// 禁止无中文留言
	if (!current_user_can( 'manage_options' )) {
		function refused_spam_comments( $comment_data ) {
			$pattern = '/[一-龥]/u';
			if (!preg_match($pattern,$comment_data['comment_content'])) {
				err('评论必须含中文！');
			}
			return( $comment_data );
		}
		add_filter('preprocess_comment','refused_spam_comments');
	}
}

// @回复
if (zm_get_option('at')) {
function comment_at( $comment_text, $comment = '') {
	global $comment;
	if ( @$comment->comment_parent > 0) {
		$comment_text = '<span class="at">@ <a href="#comment-' . $comment->comment_parent . '">'.get_comment_author( $comment->comment_parent ) . '</a></span> ' . $comment_text;
	}
	return $comment_text;
}
add_filter( 'comment_text' , 'comment_at', 20, 2);
}

// 登录显示评论
function begin_comments() {
	if (zm_get_option('login_comment')) {
		if ( is_user_logged_in()){
			if ( comments_open() || get_comments_number() ) :
				if ( zm_get_option( 'comment_counts' ) ) {
					comment_counts_stat();
				}
				if ( zm_get_option( 'sticky_comments' ) && zm_get_option( 'comments_top' ) ) {
					be_sticky_comments();
				}
				comments_template( '', true );
			endif;
		}
	} else {
	if ( comments_open() || get_comments_number() ) :
		if ( zm_get_option( 'comment_counts' ) && get_comments_number( get_the_ID() ) > 1 ) {
			comment_counts_stat();
		}
		if ( zm_get_option( 'sticky_comments' ) && zm_get_option( 'comments_top' ) ) {
			be_sticky_comments();
		}
		comments_template( '', true );
	endif;
	}
}

// 打开或关闭文章评论
if ( zm_get_option( 'on_comment' ) ) {
	global $wpdb;
	$wpdb->query( "UPDATE wp_posts SET comment_status='open'" );
}
if ( zm_get_option( 'off_comment' ) ) {
	global $wpdb;
	$wpdb->query( "UPDATE wp_posts SET comment_status='close'" );
}

// 浏览总数
function all_view(){
	global $wpdb;
	$count = $wpdb->get_var("SELECT sum(meta_value) FROM $wpdb->postmeta WHERE meta_key='views'");
	return $count;
}

// 作者被浏览数
function author_posts_views( $display = true ) {
	global $wpdb;
	$author_id = get_the_author_meta( 'ID' );
	$sql = "SELECT SUM(meta_value+0) FROM $wpdb->posts left join $wpdb->postmeta on ($wpdb->posts.ID = $wpdb->postmeta.post_id) WHERE meta_key = 'views' AND post_author =$author_id";
	$comment_views = intval( $wpdb->get_var( $sql ) );
	if ( $display ) {
		echo begin_postviews_round_number( $comment_views );
	} else {
		return $comment_views;
	}
}

// 作者被点赞数
function like_posts_views( $display = true ) {
	global $wpdb;
	$author_id = get_the_author_meta( 'ID' );
	$sql = "SELECT SUM(meta_value+0) FROM $wpdb->posts left join $wpdb->postmeta on ($wpdb->posts.ID = $wpdb->postmeta.post_id) WHERE meta_key = 'zm_like' AND post_author =$author_id";
	$comment_like = intval( $wpdb->get_var( $sql ) );
	if ( $display ) {
		echo $comment_like;
	} else {
		return $comment_like;
	}
}

// 编辑_blank
function edit_blank($text) {
	return str_replace('<a', '<a target="_blank"', $text);
}
add_filter('edit_post_link', 'edit_blank');
add_filter('edit_comment_link', 'edit_blank');

// 新标签打开链接
function goal() {
	if ( ! be_get_option( 'target_blank' ) || ( be_get_option( 'target_blank' ) == 'home' ) ) {
		$target = ( be_get_option( 'target_blank' ) && is_home() ) ? 'target="_blank"' : '';
	}

	if ( be_get_option( 'target_blank' ) == 'archive' ) {
		$target = ( be_get_option( 'target_blank' ) && is_home() || is_archive() ) ? 'target="_blank"' : '';
	}
	
	if ( be_get_option( 'target_blank' ) == 'none' ) {
		$target = '';
	}
	return $target;
}

// 登录提示
function zm_login_title() {
	return get_bloginfo('name');
}
if (get_bloginfo('version') >= 5.2 ) {
add_filter('login_headertext', 'zm_login_title');
} else {
add_filter('login_headertitle', 'zm_login_title');
}
// logo url
function custom_loginlogo_url($url) {
	return get_bloginfo('url');
}
add_filter( 'login_headerurl', 'custom_loginlogo_url' );

// 登录注册message
function be_authenticate_username_password( $user, $username, $password ) {
	if ( is_a( $user, 'WP_User' ) )
		return $user;

	if ( empty( $username ) || empty( $password ) ) {
		if ( is_wp_error( $user ) )
		return $user;

		$error = new WP_Error();

		if ( empty( $username ) )
			$error->add( 'empty_username', cx_get_option( 'reg_name' ) );

		if ( empty( $password ) )
			$error->add( 'empty_password', cx_get_option( 'reg_password' ) );

		return $error;
	}

	$user = get_user_by( 'login', $username );

	if ( ! $user )
		return new WP_Error( 'invalid_username', cx_get_option( 'reg_name_error' ) );

	$user = apply_filters( 'wp_authenticate_user', $user, $password );
	if ( is_wp_error( $user ) )
		return $user;

	if ( ! wp_check_password( $password, $user->user_pass, $user->ID ) )
		return new WP_Error( 'incorrect_password', cx_get_option( 'reg_password_error' ) );

	return $user;
}

function be_registration_error_message( $errors ) {
	if ( isset( $errors->errors['empty_username'] ) ) {
		$errors->errors['empty_username'][0] = cx_get_option( 'reg_name' );
	}

	if ( isset( $errors->errors['username_exists'] ) ) {
		$errors->errors['username_exists'][0] = cx_get_option( 'reg_change_name' );
	}

	if ( isset( $errors->errors['empty_email'] ) ) {
		$errors->errors['empty_email'][0] = __( '请填写您的邮箱', 'begin' );
	}

	if ( isset( $errors->errors['invalid_email'] ) ) {
		$errors->errors['invalid_email'][0] = __( '您的邮箱格式不对', 'begin' );
	}

	if ( isset( $errors->errors['email_exists'] ) ) {
		$errors->errors['email_exists'][0] = cx_get_option( 'reg_change_email' );
	}
	return $errors;
}

// 外链nofollow
if ( zm_get_option( 'link_external' ) ) {
add_filter( 'the_content', 'be_add_nofollow_content' );
	function be_add_nofollow_content( $content ) {
		$content = preg_replace_callback( '/<a[^>]*href=["|\']([^"|\']*)["|\'][^>]*>([^<]*)<\/a>/i',
		function( $m ) {
			$site_link = get_option( 'siteurl' );
			$site_link_other = get_option( 'siteurl' );
			$site_link_admin = admin_url();
			if ( ( strpos( $m[1], "javascript:;" ) !== false ) || ( strpos( $m[1], $site_link_admin ) !== false ) ) {
				return '<a href="'.$m[1].'">'.$m[2].'</a>';
			} else {
				if ( ( strpos( $m[1], $site_link ) !== false ) || ( strpos( $m[1], $site_link_other ) !== false ) ) {
					if ( zm_get_option( 'link_internal' ) ) {
						return '<a href="'.$m[1].'" target="_blank">'.$m[2].'</a>';
					} else {
						return '<a href="'.$m[1].'">'.$m[2].'</a>';
					}
				} else {
					return '<a href="'.$m[1].'" rel="external nofollow" target="_blank">'.$m[2].'</a>';
				}
			}
		},
		$content );
		return $content;
	}
}

// 评论链接添加_blank
function comment_author_link_go( $content ) {
	preg_match_all( '/\shref=(\'|\")(http[^\'\"#]*?)(\'|\")([\s]?)/',$content,$matches );
	if ( $matches ){
		foreach( $matches[2] as $val ){
			if ( strpos( $val,home_url() )===false ){
				$rep = $matches[1][0].$val.$matches[3][0];
				$go = '"'. $val .'" rel="external nofollow" target="_blank"';
				$content = str_replace( "$rep","$go", $content );
			}
		}
	}
	return $content;
}

add_filter( 'comment_text', 'comment_author_link_go', 99 );
add_filter( 'get_comment_author_link', 'comment_author_link_go', 99 );

// 评论链接跳转
function comment_link_go( $link ) {
	$confirm_page_url = esc_url( get_permalink( zm_get_option( 'comment_link_go_id' ) ) );
	if ( strpos( $link, home_url() ) === false ) {
		$link = str_replace( 'href="', 'href="' . $confirm_page_url . '?target=', $link );
		$link = preg_replace( '/(<a [^>]*?)(>)/i', '$1 target="_blank"$2', $link );
	}

	$allowed_html = array(
		'a' => array(
			'href'   => array(),
			'target' => array()
		)
	);

	$link = wp_kses( $link, $allowed_html );
	return $link;
}

if ( zm_get_option( 'comment_link_go' ) ) {
	add_filter( 'get_comment_author_link', 'comment_link_go' );
}

// 添加斜杠
function nice_trailingslashit($string, $type_of_url) {
	if ( $type_of_url != 'single' && $type_of_url != 'page' && $type_of_url != 'single_paged' )
		$string = trailingslashit($string);
	return $string;
}
if ( zm_get_option( 'category_x' ) ) {
	add_filter( 'user_trailingslashit', 'nice_trailingslashit', 10, 2 );
}
function be_html_page_permalink() {
	global $wp_rewrite;
	if ( !strpos($wp_rewrite->get_page_permastruct(), '.html')){
		$wp_rewrite->page_structure = $wp_rewrite->page_structure . '.html';
		// $wp_rewrite->flush_rules();
	}
}

// 文章分页
function begin_link_pages() {
	if (zm_get_option('link_pages_all')) {
		if (zm_get_option('turn_small')) {
			echo '<div class="turn-small">';
			wp_link_pages();
			echo '</div>';
		} else {
			wp_link_pages();
		}
	} else {
		if (zm_get_option('turn_small')) {
			wp_link_pages(array('before' => '<div class="page-links turn-small">', 'after' => '', 'next_or_number' => 'next', 'previouspagelink' => '<span class="all-page-link"><i class="be be-arrowleft"></i></span>', 'nextpagelink' => ""));
		} else {
			wp_link_pages(array('before' => '<div class="page-links">', 'after' => '', 'next_or_number' => 'next', 'previouspagelink' => '<span><i class="be be-arrowleft"></i></span>', 'nextpagelink' => ""));
		}
		wp_link_pages(array('before' => '', 'after' => '', 'next_or_number' => 'number', 'link_before' =>'<span class="next-page">', 'link_after'=>'</span>'));
		wp_link_pages(array('before' => '', 'after' => '</div>', 'next_or_number' => 'next', 'previouspagelink' => '', 'nextpagelink' => '<span class="all-page-link"><i class="be be-arrowright"></i></span> '));
	}
}

function be_user_contact( $user_contactmethods ){
	unset( $user_contactmethods['aim'] );
	unset( $user_contactmethods['yim'] );
	unset( $user_contactmethods['jabber'] );
	$user_contactmethods['userimg']   = sprintf( __( '图片', 'begin' ) );
	$user_contactmethods['qq']        = 'QQ';
	$user_contactmethods['weixin']    = sprintf( __( '微信', 'begin' ) );
	$user_contactmethods['weixinqr']  = sprintf( __( '微信二维码', 'begin' ) );
	$user_contactmethods['weibo']     = sprintf( __( '微博', 'begin' ) );
	$user_contactmethods['phone']     = sprintf( __( '电话', 'begin' ) );
	$user_contactmethods['remark']    = sprintf( __( '备注', 'begin' ) );
	return $user_contactmethods;
}

// meta boxes
if ( ! cx_get_option( 'meta_delete' ) ) {
	require get_template_directory() . '/inc/meta-delete.php';
}
require get_template_directory() . '/inc/meta-boxes.php';
require get_template_directory() . '/inc/options/bu-metabox.php';
require get_template_directory() . '/inc/ext-inf.php';
if ( zm_get_option( 'enable_cleaner' ) ) {
	require get_template_directory() . '/inc/be-cleaner.php';
}
// 密码提示
function change_protected_title_prefix() {
	return '%s';
}
add_filter('protected_title_format', 'change_protected_title_prefix');

// 评论等级
if (zm_get_option('vip')) {
	function get_author_class($comment_author_email,$user_id){
		global $wpdb;
		$author_count = count($wpdb->get_results(
		"SELECT comment_ID as author_count FROM $wpdb->comments WHERE comment_author_email = '$comment_author_email' "));
		$adminEmail = get_option('admin_email');if ($comment_author_email ==$adminEmail) return;
		if ($author_count>=0 && $author_count<2)
			echo '<a class="vip vip0" title="评论达人 VIP.0"><i class="be be-favoriteoutline"></i><span class="lv">0</span></a>';
		else if ($author_count>=2 && $author_count<5)
			echo '<a class="vip vip1" title="评论达人 VIP.1"><i class="be be-favorite"></i><span class="lv">1</span></a>';
		else if ($author_count>=5 && $author_count<10)
			echo '<a class="vip vip2" title="评论达人 VIP.2"><i class="be be-favorite"></i><span class="lv">2</span></a>';
		else if ($author_count>=10 && $author_count<20)
			echo '<a class="vip vip3" title="评论达人 VIP.3"><i class="be be-favorite"></i><span class="lv">3</span></a>';
		else if ($author_count>=20 && $author_count<50)
			echo '<a class="vip vip4" title="评论达人 VIP.4"><i class="be be-favorite"></i><span class="lv">4</span></a>';
		else if ($author_count>=50 && $author_count<100)
			echo '<a class="vip vip5" title="评论达人 VIP.5"><i class="be be-favorite"></i><span class="lv">5</span></a>';
		else if ($author_count>=100 && $author_count<200)
			echo '<a class="vip vip6" title="评论达人 VIP.6"><i class="be be-favorite"></i><span class="lv">6</span></a>';
		else if ($author_count>=200 && $author_count<300)
			echo '<a class="vip vip7" title="评论达人 VIP.7"><i class="be be-favorite"></i><span class="lv">7</span></a>';
		else if ($author_count>=300 && $author_count<400)
			echo '<a class="vip vip8" title="评论达人 VIP.8"><i class="be be-favorite"></i><span class="lv">8</span></a>';
		else if ($author_count>=400)
			echo '<a class="vip vip9" title="评论达人 VIP.9"><i class="be be-favorite"></i><span class="lv">9</span></a>';
	}
}

// 判断作者
function begin_comment_by_post_author( $comment = null ) {
	if ( is_object( $comment ) && $comment->user_id > 0 ) {
		$user = get_userdata( $comment->user_id );
		$post = get_post( $comment->comment_post_ID );
		if ( ! empty( $user ) && ! empty( $post ) ) {
			return $comment->user_id === $post->post_author;
		}
	}
	return false;
}

if ( zm_get_option( 'tag_c' ) ) {
// 关键词加链接
$match_num_from = 1; //一个关键字少于多少不替换
$match_num_to = zm_get_option( 'chain_n' );

add_filter( 'the_content', 'be_tag_link', 1 );

function be_tag_sort( $a, $b ){
	if ( $a->name == $b->name ) return 0;
	return ( strlen( $a->name ) > strlen( $b->name ) ) ? -1 : 1;
}

function be_tag_link( $content ){
global $match_num_from,$match_num_to;
$posttags = get_the_tags();
	if ( $posttags ) {
		usort( $posttags, "be_tag_sort" );
		foreach( $posttags as $tag ) {
			$link = get_tag_link( $tag->term_id );
			$keyword = $tag->name;
			if ( preg_match_all('|(<h[^>]+>)(.*?)'.$keyword.'(.*?)(</h[^>]*>)|U', $content, $matchs ) ) {continue;}
			if ( preg_match_all('|(<a[^>]+>)(.*?)'.$keyword.'(.*?)(</a[^>]*>)|U', $content, $matchs ) ) {continue;}

			$cleankeyword = stripslashes( $keyword );
			$url = "<a href=\"$link\" title=\"".str_replace( '%s',addcslashes($cleankeyword, '$'),__( '查看与 %s 相关的文章', 'begin' ) ) . "\"";
			$url .= ' target="_blank"';
			$url .= "><span class=\"tag-key\">" . addcslashes( $cleankeyword, '$' ) . "</span></a>";
			$limit = rand( $match_num_from,$match_num_to );
			global $ex_word;
			$case = "";
			$content = preg_replace( '|(<a[^>]+>)(.*)('.$ex_word.')(.*)(</a[^>]*>)|U'.$case, '$1$2%&&&&&%$4$5', $content );
			$content = preg_replace( '|(<img)(.*?)('.$ex_word.')(.*?)(>)|U'.$case, '$1$2%&&&&&%$4$5', $content );
			$cleankeyword = preg_quote($cleankeyword,'\'');
			$regEx = '\'(?!((<.*?)|(<a.*?)))('. $cleankeyword . ')(?!(([^<>]*?)>)|([^>]*?</a>))\'s' . $case;
			$content = preg_replace( $regEx,$url,$content,$limit );
			$content = str_replace( '%&&&&&%', stripslashes($ex_word), $content );
		}
	}
	return $content;
}
}

// 防冒充管理员
function usercheck($incoming_comment) {
	$isSpam = 0;
	if (trim($incoming_comment['comment_author']) == ''.zm_get_option('admin_name').'')
	$isSpam = 1;
	if (trim($incoming_comment['comment_author_email']) == ''.zm_get_option('admin_email').'')
	$isSpam = 1;
	if (!$isSpam)
	return $incoming_comment;
	err('<i class="be be-info"></i>请勿冒充管理员发表评论！');
}

// 页面添加标签
class PTCFP{
	function __construct(){
	add_action( 'init', array( $this, 'taxonomies_for_pages' ) );
		if ( ! is_admin() ) {
			add_action( 'pre_get_posts', array( $this, 'tags_archives' ) );
		}
	}
	function taxonomies_for_pages() {
		register_taxonomy_for_object_type( 'post_tag', 'page' );
	}
	function tags_archives( $wp_query ) {
	if ( $wp_query->get( 'tag' ) )
		$wp_query->set( 'post_type', 'any' );
	}
}
$ptcfp = new PTCFP();

// 获取当前页面地址
function currenturl() {
	$current_url = home_url(add_query_arg(array()));
	if (is_single()) {
		$current_url = preg_replace('/(\/comment|page|#).*$/','',$current_url);
	} else {
		$current_url = preg_replace('/(comment|page|#).*$/','',$current_url);
	}
	echo $current_url;
}

// 自定义类型面包屑
function begin_taxonomy_terms( $product_id, $taxonomy, $args = array() ) {
	$terms = wp_get_post_terms( $product_id, $taxonomy, $args );
	return apply_filters( 'begin_taxonomy_terms' , $terms, $product_id, $taxonomy, $args );
}

// 子分类
function get_category_id($cat) {
	$this_category = get_category($cat);
	while($this_category->category_parent) {
		$this_category = get_category($this_category->category_parent);
	}
	return $this_category->term_id;
}

// 父分类
function father_cat() {
	$category = get_the_category();
	return $category[0]->category_parent;
}

// 图片数量
if ( !function_exists('get_post_images_number') ){
	function get_post_images_number(){
		global $post;
		$content = $post->post_content; 
		preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $result, PREG_PATTERN_ORDER);
		return count($result[1]);
	}
}

// user_only
if ( !is_admin() ) {
add_filter('the_content','user_only');
}
function user_only( $text ) {
	global $post; $user_only;
	$user_only = get_post_meta( get_the_ID(), 'user_only', true );
	if ( $user_only ) {
		global $user_ID;
		if ( !$user_ID ) {
			$redirect = urlencode( get_permalink( $post->ID ) );
			$text = '
			<div class="read-point-box">
				<div class="read-point-content">
					<div class="read-point-title"><i class="be be-info"></i>' . sprintf(__( '提示！', 'begin' ) ) . '</div>
					<div class="read-point-explain">' . sprintf(__( '本文登录后方可查看！', 'begin' ) ) . '</div>
				</div>
				<div class="read-btn read-btn-login"><div class="flatbtn show-layer cur"><i class="read-btn-ico"></i>' . sprintf(__( '登录', 'begin' )) . '</div></div>
			</div>';
		}
	}
	return $text;
}

// 头部冗余代码
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'feed_links_extra', 3 );
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );

// 编辑器增强
function enable_more_buttons($buttons) {
	$buttons[] = 'del';
	$buttons[] = 'copy';
	$buttons[] = 'cut';
	$buttons[] = 'fontselect';
	$buttons[] = 'fontsizeselect';
	$buttons[] = 'styleselect';
	$buttons[] = 'wp_page';
	$buttons[] = 'backcolor';
	return $buttons;
}
add_filter( "mce_buttons_2", "enable_more_buttons" );

// 禁止代码标点转换
remove_filter( 'the_content', 'wptexturize' );

if ( zm_get_option( 'xmlrpc_no' ) ) {
// 禁用xmlrpc
add_filter('xmlrpc_enabled', '__return_false');
}

// 禁止评论自动超链接
if ( zm_get_option( 'comment_url' ) ) {
remove_filter( 'comment_text', 'make_clickable', 9 );
}
// 禁止评论HTML
if ( zm_get_option('comment_html' ) ) {
add_filter( 'comment_text', 'wp_filter_nohtml_kses' );
add_filter( 'comment_text_rss', 'wp_filter_nohtml_kses' );
add_filter( 'comment_excerpt', 'wp_filter_nohtml_kses' );
}

// 链接管理
add_filter( 'pre_option_link_manager_enabled', '__return_true' );

// RSS cache
if ( zm_get_option( 'be_feed_cache' ) ) {
	add_filter( 'wp_feed_cache_transient_lifetime' , 'feed_cache_time' );
	function feed_cache_time( $seconds ) {
		return zm_get_option( 'be_feed_cache' );
	}
}

// 删除RSS缓存
if ( zm_get_option( 'del_feed_cache' ) ) {
	global $wpdb;
	$prefix = '_transient_feed_';
	$sql = "DELETE FROM $wpdb->options WHERE option_name LIKE '%s'";
	$wpdb->query( $wpdb->prepare( $sql, $prefix . '%' ) );

	$prefix = '_transient_timeout_feed_';
	$sql = "DELETE FROM $wpdb->options WHERE option_name LIKE '%s'";
	$wpdb->query( $wpdb->prepare( $sql, $prefix . '%' ) );
}

// 禁止后台加载谷歌字体
function wp_remove_open_sans_from_wp_core() {
	wp_deregister_style( 'open-sans' );
	wp_register_style( 'open-sans', false );
	wp_enqueue_style('open-sans','');
}
add_action( 'init', 'wp_remove_open_sans_from_wp_core' );

// 禁用emoji
function disable_emojis() {
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
}
add_action( 'init', 'disable_emojis' );

// 移除表情
remove_action( 'wp_head' , 'print_emoji_detection_script', 7 );

// Classic Widgets
if (zm_get_option('classic_widgets')) {
function be_classic_widgets() {
	remove_theme_support( 'widgets-block-editor' );
}
add_action( 'after_setup_theme', 'be_classic_widgets' );
}

// 禁用oembed/rest
function disable_embeds_init() {
	global $wp;
	$wp->public_query_vars = array_diff( $wp->public_query_vars, array(
		'embed',
	) );
	remove_action( 'rest_api_init', 'wp_oembed_register_route' );
	add_filter( 'embed_oembed_discover', '__return_false' );
	remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	add_filter( 'tiny_mce_plugins', 'disable_embeds_tiny_mce_plugin' );
	add_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );
}
if (zm_get_option('embed_no')) {
	add_action( 'init', 'disable_embeds_init', 9999 );
}

remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );

function disable_embeds_tiny_mce_plugin( $plugins ) {
	return array_diff( $plugins, array( 'wpembed' ) );
}
function disable_embeds_rewrites( $rules ) {
	foreach ( $rules as $rule => $rewrite ) {
		if ( false !== strpos( $rewrite, 'embed=true' ) ) {
			unset( $rules[ $rule ] );
		}
	}
	return $rules;
}
function disable_embeds_remove_rewrite_rules() {
	add_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'disable_embeds_remove_rewrite_rules' );
function disable_embeds_flush_rewrite_rules() {
	remove_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'disable_embeds_flush_rewrite_rules' );

// 禁止dns-prefetch
function remove_dns_prefetch( $hints, $relation_type ) {
	if ( 'dns-prefetch' === $relation_type ) {
		return array_diff( wp_dependencies_unique_hosts(), $hints );
	}
	return $hints;
}
add_filter( 'wp_resource_hints', 'remove_dns_prefetch', 10, 2 );

// 禁用REST API
if ( zm_get_option( 'disable_api' ) ) {
	add_filter( 'json_enabled', '__return_false');
	add_filter( 'json_jsonp_enabled', '__return_false' );
	add_filter( 'rest_enabled', '__return_false');
	add_filter( 'rest_jsonp_enabled', '__return_false' );
}

// 移除wp-json链接
remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );

// 替换用户链接
add_filter( 'request', 'author_first_name' );
function author_first_name( $query_vars ) {
	if ( array_key_exists( 'author_name', $query_vars ) ) {
		global $wpdb;
		if ( ! zm_get_option( 'my_author' ) || ( zm_get_option( 'my_author') == 'first_name' ) ) {
			$author_id = $wpdb->get_var( $wpdb->prepare( "SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key='first_name' AND meta_value = %s", $query_vars['author_name'] ) );
		}
		if ( zm_get_option( 'my_author') == 'last_name' ) {
			$author_id = $wpdb->get_var( $wpdb->prepare( "SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key='last_name' AND meta_value = %s", $query_vars['author_name'] ) );
		}
		if ( $author_id ) {
			$query_vars['author'] = $author_id;
			unset( $query_vars['author_name'] );
		}
	}
	return $query_vars;
}

add_filter( 'author_link', 'author_first_name_link', 10, 3 );
function author_first_name_link( $link, $author_id, $author_nicename ) {
	if ( ! zm_get_option( 'my_author' ) || ( zm_get_option( 'my_author') == 'first_name' ) ) {
		$my_name = get_user_meta( $author_id, 'first_name', true );
	}
	if ( zm_get_option( 'my_author') == 'last_name' ) {
		$my_name = get_user_meta( $author_id, 'last_name', true );
	}
	if ( $my_name ) {
		$link = str_replace( $author_nicename, $my_name, $link );
	}
	return $link;
}

// 移除区块样板菜单
if ( zm_get_option( 'remove_patterns' ) ) {
add_action( 'admin_menu', 'remove_patterns_menu' );
}
function remove_patterns_menu() {
	remove_submenu_page( 'themes.php', 'site-editor.php?path=/patterns' );
}

// 屏蔽用户名称类
function remove_comment_body_author_class( $classes ) {
	foreach( $classes as $key => $class ) {
	if (strstr($class, "comment-author-")||strstr($class, "author-")) {
			unset( $classes[$key] );
		}
	}
	return $classes;
}

// 判断用户
function be_check_user_role( $roles, $user_id = null ) {
	if ( $user_id ) $user = get_userdata( $user_id );
	else $user = wp_get_current_user();
	if ( empty( $user ) ) return false;
	foreach ( $user->roles as $role ) {
		if ( in_array($role, $roles ) ) {
			return true;
		}
	}
	return false;
}

// 最近更新过
function be_updated_posts( $num=10, $days=7 ) {
	if ( ! $output = get_option( 'recently_updated_posts' ) ) {
		$args = array(
			'orderby'             => 'modified',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true, 
		);
		$query = new WP_Query( $args );
		$i=0;
		while ( $query->have_posts() && $i < $num  ) : $query->the_post();
			if ( current_time('timestamp') - get_the_time('U') > 60*60*24*$days ) {
				$i++;
				$output .= '<li>';
				$output .= '<span class="thumbnail">';
				$output .= zm_thumbnail();
				$output .= '</span>';
				$output .= '<span class="new-title"><a href="' . get_permalink().'">' . get_the_title() . '</a></span>';
				$output .= '<span class="views">' . sprintf(__( '更新日期', 'begin' ) ) . '</span>';
				$output .= '<span class="date">' . get_the_modified_time( 'Y/n/j' ) . '</span>';
				$output .= '</li>';
			}
		endwhile;
		wp_reset_postdata();
		if ( ! empty( $output ) ) update_option( 'recently_updated_posts', $output );
	}
	$output = ( $output == '' ) ? '<li class="srm the-icon">' . sprintf(__( '暂无', 'begin' ) ) . '</li>' : $output;
	echo $output;
}

function clear_cache_recently() {
	update_option( 'recently_updated_posts', '' );
}
add_action( 'save_post', 'clear_cache_recently' );



// 注册时间
function user_registered(){
	$userinfo=get_userdata(get_current_user_id());
	$authorID= $userinfo->ID;
	$user = get_userdata( $authorID );
	$registered = $user->user_registered;
	echo '' . date( "" . sprintf(__( 'Y年m月d日', 'begin' )) . "", strtotime( $registered ) );
}

// 文章归档更新已停用
function be_archives() {
	update_option('be_archives_list', '');
}

if ( zm_get_option( 'update_be_archives' ) ) {
	if ( is_admin() ) {
		add_action( 'init', 'be_archives' );
	}
}

function be_up_archives() {
	update_option('up_archives_list', '');
}

if ( zm_get_option( 'update_up_archives' ) ) {
	if ( is_admin() ) {
		add_action( 'init', 'be_up_archives' );
	}
}

// 登录时间
function be_user_last_login($user_login) {
	global $user_ID;
	date_default_timezone_set('PRC');
	$user = get_user_by( 'login', $user_login );
	update_user_meta($user->ID, 'last_login', date('Y-m-d H:i:s'));
}

function get_last_login($user_id) {
	$last_login = get_user_meta($user_id, 'last_login', true);
	$date_format = get_option('date_format') . ' ' . get_option('time_format');
	$the_last_login = mysql2date($date_format, $last_login, false);
	echo $the_last_login;
}

// 登录角色
function get_user_role() {
	global $current_user;
	$user_roles = $current_user->roles;
	$user_role = array_shift($user_roles);
	return $user_role;
}

// 禁止进后台
function begin_redirect_wp_admin() {
	if ( zm_get_option('user_url') == '' ) {
		$url = home_url();
	} else {
		$url = get_permalink( zm_get_option('user_url') );
	}
	if ( is_admin() && is_user_logged_in() && !current_user_can( 'publish_pages' ) && !current_user_can( 'manage_options' ) && ( !defined( 'DOING_AJAX' ) || !DOING_AJAX ) ){
		wp_safe_redirect( $url );
		exit;
	}
}

// 读者排行
function top_comment_authors( $amount = 98 ) {
	global $wpdb;
	$prepared_statement = $wpdb->prepare(
	'SELECT
	COUNT(comment_author) AS comments_count, comment_author, comment_author_url, comment_author_email, MAX( comment_date ) as last_commented_date
	FROM '.$wpdb->comments.'
	WHERE comment_author != "" AND comment_type not in ("trackback","pingback") AND comment_approved = 1 AND user_id = ""
	GROUP BY comment_author
	ORDER BY comments_count DESC, comment_author ASC
	LIMIT %d',
	$amount);
	$results = $wpdb->get_results( $prepared_statement );
	$output = '<div class="top-comments">';
	foreach( $results as $result ) {
		$c_url = $result->comment_author_url;
		$confirm_url = esc_url( get_permalink( zm_get_option( 'comment_link_go_id' ) ) );

		if ( empty( $c_url ) || ! filter_var( $c_url, FILTER_VALIDATE_URL ) ) {
			$url = '';
		} else {
			$url = zm_get_option( 'comment_link_go' ) ? $confirm_url . '?target=' . esc_url( $c_url ) : esc_url( $c_url );
		}

		$output .= '<div class="top-author-item"><div class="boxs1"><div class="top-author load">';
			if ( zm_get_option( 'cache_avatar') ) {
				$output .= '<div class="top-comment"><a href="' . $url . '" target="_blank" rel="external nofollow">' . begin_avatar($result->comment_author_email, 96, '', $result->comment_author) . '<div class="author-url"><strong> ' . $result->comment_author . '</div></strong></a></div>';
			} else {
				if ( !zm_get_option( 'avatar_load' ) ) {
					$output .= '<div class="top-comment"><a href="' . $url . '" target="_blank" rel="external nofollow">' . get_avatar($result->comment_author_email, 96, '', $result->comment_author) . '<div class="author-url"><strong> ' . $result->comment_author . '</div></strong></a></div>';
				} else {
					$output .= '<div class="top-comment"><a href="' . $url . '" target="_blank" rel="external nofollow"><img class="avatar photo" src="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=" alt="'. get_the_author() .'" width="96" height="96" data-original="' . preg_replace(array('/^.+(src=)(\"|\')/i', '/(\"|\')\sclass=(\"|\').+$/i'), array('', ''), get_avatar($result->comment_author_email, 96, '', $result->comment_author)) . '"><div class="author-url"><strong> ' . $result->comment_author . '</div></strong></a></div>';
				}
			}
		$output .= '<div class="top-comment">'.$result->comments_count.'条留言</div><div class="top-comment">' . human_time_diff(strtotime($result->last_commented_date)) . '前</div></div></div></div>';
	}
	$output .= '<div class="clear"></div></div>';
	echo $output;
}

// 评论列表
function get_comment_authors_list( $id = null ) {
	$post_id = $id ? $id : get_the_ID();
	if ( $post_id ) {
		$comments = get_comments( array(
			'post_id'         => $post_id,
			'status'          => 'approve',
			'order'           => 'ASC',
			'author__not_in'  => get_the_author_meta('ID'),
			'type'            => 'comment',
		) );

		$names = array();
		foreach ( $comments as $comment ) {
			$arr = explode( ' ', trim( $comment->comment_author ) );
			if ( ! empty( $arr[0] ) && ! in_array( $arr[0], $names ) ) {
				$names[] = $arr[0];
			echo '<a class="names-scroll"><li>';
			if (zm_get_option('cache_avatar')) {
				echo begin_avatar( $comment->comment_author_email, 96, '', get_comment_author( $comment->comment_ID ) );
			} else {
				echo get_avatar( $comment->comment_author_email, 96, '', get_comment_author( $comment->comment_ID ) );
			}
			echo get_comment_author( $comment->comment_ID );
			echo '</li></a>';
			}
		}
		unset( $comments );
	}
}

function qa_get_comment_last( $id = null ) {
	$post_id = $id ? $id : get_the_ID();
	if ( $post_id ) {
		$comments = get_comments( array(
			'post_id' => $post_id,
			'status'  => 'approve',
			'type'    => 'comment',
			'number'  => '1',
		) );

		$names = array();
		foreach ( $comments as $comment ) {
			$arr = explode( ' ', trim( $comment->comment_author ) );
			if ( ! empty( $arr[0] ) && ! in_array( $arr[0], $names ) ) {
				$names[] = $arr[0];
				echo '<span class="qa-meta qa-last"><span class="qa-meta-class"></span>';
				echo '<a href="'.esc_url( get_permalink() ).'#comments"><span>' . sprintf(__( '最后回复', 'begin' )) . '<span class="qa-meta-class"></span>';
				echo '<span class="qa-meta-name">';
				echo get_comment_author( $comment->comment_ID );
				echo '</span>';
				echo '</span></a>';
				echo '</span>';
			}
		}
		unset( $comments );
	}
}

// 网址描述
add_action( 'publish_sites', 'be_sites_des' );
function be_sites_des( $post_ID ) {
	$post_type = get_post_type( $post_ID );
	if ( $post_type == 'sites' ) {
		global $wpdb;
		if ( ! wp_get_post_revisions( $post_ID ) ) {
			if ( empty( get_post_meta( get_the_ID(), 'sites_link', true ) ) ) {
				$meta_tags = '';
			} else {
				$url = get_post_meta( get_the_ID(), 'sites_link', true );
				$response = wp_remote_get( $url, array( 'timeout' => 10 ) ); // 设置超时时间为 10 秒
				if ( is_wp_error( $response ) ) {
					return; // 直接停止执行
				}
				$html = wp_remote_retrieve_body( $response );
				$html = htmlspecialchars( $html, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8' );
				// 忽略警告
				libxml_use_internal_errors(true);
				// 尝试解析HTML
				$dom = new DOMDocument();
				$dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
				// 清除错误
				libxml_clear_errors();
				$metas = $dom->getElementsByTagName( 'meta' );
				foreach( $metas as $meta ) {
					if ( $meta->getAttribute( 'name' ) === 'description' ) {
						$desc = $meta->getAttribute( 'content' );
						break;
					}
				}

				if ( ! isset( $desc ) ) {
					$tags = get_meta_tags( $url );
					if ( isset( $tags['description'] ) && $tags['description'] !== null ) {
						$desc = $tags['description'];
					} else {
						$desc = '暂无网站描述';
					}
				}

				$meta_tags['description'] = $desc;
			}

			if ( 'sites_url' == ! get_post_meta( get_the_ID(), 'sites_url', false ) ) {
				if ( isset( $meta_tags['description'] ) ) {
					$metas = $meta_tags['description'];
					add_post_meta( $post_ID, 'sites_description', $metas, true );
				}
			}
		}
	}
}

// 分类ID
function show_id() {
	$categories = get_categories( array( 'taxonomy' => array( 'category' ), 'orderby' => 'ID', 'order' => 'ASC', 'hide_empty' => 0 ) ); 
	foreach ( $categories as $cat ) {
		$output = '<ol class="show-id">' . $cat->cat_name . '<span>' . $cat->cat_ID . '</span></ol>';
		echo $output;
	}
	if ( ! $categories ) {
		$output = '<ol class="show-id">暂无分类</ol>';
		echo $output;
	}
}

function notice_show_id() {
	$categories = get_categories( array( 'taxonomy' => array( 'notice' ), 'orderby' => 'ID', 'order' => 'ASC', 'hide_empty' => 0 ) );
	foreach ( $categories as $cat ) {
		$output = '<ol class="show-id">' . $cat->cat_name . '<span>' . $cat->cat_ID . '</span></ol>';
		echo $output;
	}
	if ( ! $categories ) {
		$output = '<ol class="show-id">暂无分类</ol>';
		echo $output;
	}
}

function gallery_show_id() {
	$categories = get_categories( array( 'taxonomy' => array( 'gallery' ), 'orderby' => 'ID', 'order' => 'ASC', 'hide_empty' => 0 ) );
	foreach ( $categories as $cat ) {
		$output = '<ol class="show-id">' . $cat->cat_name . '<span>' . $cat->cat_ID . '</span></ol>';
		echo $output;
	}
	if ( ! $categories ) {
		$output = '<ol class="show-id">暂无分类</ol>';
		echo $output;
	}
}

function videos_show_id() {
	$categories = get_categories( array( 'taxonomy' => array( 'videos' ), 'orderby' => 'ID', 'order' => 'ASC', 'hide_empty' => 0 ) );
	foreach ( $categories as $cat ) {
		$output = '<ol class="show-id">' . $cat->cat_name . '<span>' . $cat->cat_ID . '</span></ol>';
		echo $output;
	}
	if ( ! $categories ) {
		$output = '<ol class="show-id">暂无分类</ol>';
		echo $output;
	}
}

function taobao_show_id() {
	$categories = get_categories( array( 'taxonomy' => array( 'taobao' ), 'orderby' => 'ID', 'order' => 'ASC', 'hide_empty' => 0 ) );
	foreach ( $categories as $cat ) {
		$output = '<ol class="show-id">' . $cat->cat_name . '<span>' . $cat->cat_ID . '</span></ol>';
		echo $output;
	}
	if ( ! $categories ) {
		$output = '<ol class="show-id">暂无分类</ol>';
		echo $output;
	}
}

function products_show_id() {
	$categories = get_categories( array( 'taxonomy' => array( 'products' ), 'orderby' => 'ID', 'order' => 'ASC', 'hide_empty' => 0 ) );
	foreach ( $categories as $cat ) {
		$output = '<ol class="show-id">' . $cat->cat_name . '<span>' . $cat->cat_ID . '</span></ol>';
		echo $output;
	}
	if ( ! $categories ) {
		$output = '<ol class="show-id">暂无分类</ol>';
		echo $output;
	}
}

function favorites_show_id() {
	$categories = get_categories( array( 'taxonomy' => array( 'favorites' ), 'orderby' => 'ID', 'order' => 'ASC', 'hide_empty' => 0 ) );
	foreach ( $categories as $cat ) {
		$output = '<ol class="show-id">' . $cat->cat_name . '<span>' . $cat->cat_ID . '</span></ol>';
		echo $output;
	}
	if ( ! $categories ) {
		$output = '<ol class="show-id">暂无分类</ol>';
		echo $output;
	}
}

function product_show_id() {
	$categories = get_categories( array( 'taxonomy' => array( 'product_cat' ), 'orderby' => 'ID', 'order' => 'ASC', 'hide_empty' => 0 ) );
	foreach ( $categories as $cat ) {
		$output = '<ol class="show-id">' . $cat->cat_name . '<span>' . $cat->cat_ID . '</span></ol>';
		echo $output;
	}
	if ( ! $categories ) {
		$output = '<ol class="show-id">暂无分类</ol>';
		echo $output;
	}
}

// 专题ID
function special_show_id() {
	$options_pages = get_pages( array( 'meta_key' => 'special', 'hierarchical' => 0, 'sort_column' => 'ID' ) );
	foreach ( $options_pages as $page ) {
		$output = '<ol class="show-id">' . $page->post_title . '<span>' . $page->ID . '</span></ol>';
		echo $output;
	}
	if ( ! $options_pages ) {
		$output = '<ol class="show-id">暂无专题</ol>';
		echo $output;
	}
}

function column_show_id() {
	$categories = get_categories( array( 'taxonomy' => array( 'special' ), 'orderby' => 'ID', 'order' => 'ASC', 'hide_empty' => 0 ) );
	foreach ( $categories as $cat ) {
		$output = '<ol class="show-id">' . $cat->cat_name . '<span>' . $cat->cat_ID . '</span></ol>';
		echo $output;
	}
	if ( ! $categories ) {
		$output = '<ol class="show-id">暂无专栏</ol>';
		echo $output;
	}
}

function search_cat(){
	$categories = get_categories();
	foreach ( $categories as $cat ) {
	$output = '<option value="' . $cat->cat_ID . '">' . $cat->cat_name . '</option>';
		echo $output;
	}
}

// 热评文章
function hot_comment_viewed($number, $days){
	global $wpdb;
	$sql = "SELECT ID , post_title , comment_count
			FROM $wpdb->posts
			WHERE post_type = 'post' AND post_status = 'publish' AND TO_DAYS(now()) - TO_DAYS(post_date) < $days
			ORDER BY comment_count DESC LIMIT 0 , $number ";
	$posts = $wpdb->get_results($sql);
	$i = 1;
	$output = "";
	foreach ($posts as $post){
		$output .= "\n<li class='srm'><span class='li-icon li-icon-$i'>$i</span><a href= \"".get_permalink($post->ID)."\" rel=\"bookmark\" title=\" (".$post->comment_count."条评论)\" >".$post->post_title."</a></li>";
		$i++;
	}
	echo $output;
}

// 历史今天
function begin_today(){
	global $wpdb;
	$today_post = '';
	$result = '';
	$post_year = get_the_time('Y');
	$post_month = get_the_time('m');
	$post_day = get_the_time('j');
	$sql = "select ID, year(post_date_gmt) as today_year, post_title, comment_count FROM 
			$wpdb->posts WHERE post_password = '' AND post_type = 'post' AND post_status = 'publish'
			AND year(post_date_gmt)!='$post_year' AND month(post_date_gmt)='$post_month' AND day(post_date_gmt)='$post_day'
			order by post_date_gmt DESC limit 8";
	$histtory_post = $wpdb->get_results($sql);
	if ( $histtory_post ){
		foreach( $histtory_post as $post ){
			$today_year = $post->today_year;
			$today_post_title = $post->post_title;
			$today_permalink = get_permalink( $post->ID );
			// $today_comments = $post->comment_count;
			$today_post .= '<li><a href="'.$today_permalink.'" target="_blank"><span>'.$today_year.'</span>'.$today_post_title.'</a></li>';
		}
	}
	if ( $today_post ){
		$result = '<div class="begin-today rp"><fieldset><legend><h5>'. sprintf(__( '历史上的今天', 'begin' )) .'</h5></legend><div class="today-date"><div class="today-m">'.get_the_date( 'F' ).'</div><div class="today-d">'.get_the_date( 'j' ).'</div></div><ul>'.$today_post.'</ul></fieldset></div>';
	}
	return $result;
}

// 更新
function today_renew(){
	$today = getdate();
	$query = new WP_Query( 'year=' . $today["year"] . '&monthnum=' . $today["mon"] . '&cat='.zm_get_option('cat_up_n').'&day=' . $today["mday"]);
	$postsNumber = $query->found_posts;
	echo $postsNumber;
}

function week_renew(){
	$week = date( 'W' );
	$year = date( 'Y' );
	$query = new WP_Query( 'year=' . $year . '&cat=&w=' . $week );
	$postsNumber = $query->found_posts;
	echo $postsNumber;
}

// menu description
function begin_nav_description( $item_output, $item, $depth, $args ) {
	if ( 'navigation' == $args->theme_location && $item->description ) {
		$item_output = str_replace( $args->link_after . '</a>', '<div class="menu-des">' . $item->description . '</div>' . $args->link_after . '</a>', $item_output );
	}
	return $item_output;
}
if ( zm_get_option( 'menu_des' ) ) {
add_filter( 'walker_nav_menu_start_el', 'begin_nav_description', 10, 4 );
}

// 编辑器字体
function custum_font_family( $initArray ) {
	$default_fonts = "Andale Mono='andale mono';Arial='arial';Arial Black='arial black';Book Antiqua='book antiqua';Comic Sans MS='comic sans ms';Courier New='courier new';Georgia='georgia';Helvetica='helvetica';Symbol='symbol';Tahoma='tahoma';Terminal='terminal';Times New Roman='times new roman';Trebuchet MS='trebuchet ms';Verdana='verdana';Microsoft YaHei='Microsoft YaHei'";
	$custom_fonts = "微软雅黑='微软雅黑';华文彩云='华文彩云';华文行楷='华文行楷';华文琥珀='华文琥珀';华文新魏='华文新魏';华文中宋='华文中宋';华文仿宋='华文仿宋';华文楷体='华文楷体';华文隶书='华文隶书';华文细黑='华文细黑';宋体='宋体';仿宋='仿宋';黑体='黑体';隶书='隶书';幼圆='幼圆'";
	$initArray['font_formats'] = $custom_fonts . ';' . $default_fonts;
	return $initArray;
}

// 仅管理可见分类法
function be_remove_menus() {
	$menus_to_remove = array(
		'edit.php?post_type=bulletin',
		'edit.php?post_type=picture',
		'edit.php?post_type=video',
		'edit.php?post_type=tao',
		'edit.php?post_type=sites',
		'edit.php?post_type=show',
		'link-manager.php',
		'upload.php',
		'edit-comments.php',
		'tools.php',
		'edit.php?post_type=surl',
		'edit.php?post_type=becode',
		'edit.php?post_type=ai_playlist',
	);

	foreach ( $menus_to_remove as $menu ) {
		remove_menu_page( $menu );
	}
}

function disable_create_newpost() {
	global $wp_post_types;

	$post_types_to_disable = array(
		'bulletin',
		'picture',
		'video',
		'tao',
		'sites',
		'show',
		'becode',
		'ai_playlist'
	);

	foreach ( $post_types_to_disable as $post_type ) {
		if ( isset( $wp_post_types[ $post_type ] ) ) {
			$wp_post_types[ $post_type ]->cap->create_posts = 'do_not_allow';
		}
	}
}

if ( zm_get_option( 'admin_type' ) ) {
	if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'publish_pages' ) ) {
		add_action( 'admin_menu', 'be_remove_menus' );
		add_action('init','disable_create_newpost');
	}
}



// 仅自己的文章可见
function filter_posts_by_author( $query ) {
	global $pagenow;
	if ( is_admin() && 'edit.php' === $pagenow && ! current_user_can( 'administrator' ) && ! current_user_can( 'editor' ) ) {
		$query->set( 'author', get_current_user_id() );
	}
}
if ( zm_get_option( 'only_posts' ) ) {
	add_action( 'pre_get_posts', 'filter_posts_by_author' );
}

// 复制提示
function zm_copyright_tips() {
	echo '<script>document.body.oncopy=function(){alert("\u590d\u5236\u6210\u529f\uff01\u8f6c\u8f7d\u8bf7\u52a1\u5fc5\u4fdd\u7559\u539f\u6587\u94fe\u63a5\uff0c\u7533\u660e\u6765\u6e90\uff0c\u8c22\u8c22\u5408\u4f5c\uff01");}</script>';
}

// ajax content
function ajax_content(){
	$data = $_POST['data'];
	$return = array();
	if ( is_array( $data ) ){
		foreach ( $data as $key => $text ) {
			$return[$key] = do_shortcode( base64_decode( $text ) );
		}
	}
	echo json_encode( $return );
	exit;
}

// 显示全部分类
add_filter( 'widget_categories_args', 'show_empty_cats' );
function show_empty_cats($cat_args) {
	$cat_args['hide_empty'] = 0;
	return $cat_args;
}

// 标签文章数
function get_tag_post_count( $tag_slug ) {
	$tag = get_term_by( 'slug', $tag_slug, 'post_tag' );
	_make_cat_compat( $tag );
	if ( $tag ) return $tag->count;
}

// 标签别名获取ID
function get_tag_id_slug( $tag_slug ) {
	$tag = get_term_by( 'slug', $tag_slug, 'post_tag' );
	if ( $tag ) return $tag->term_id;
	return 0;
}

// 上传头像
if ( zm_get_option( 'local_avatars' ) ) {
	$be_user_avatars = new be_user_avatars;
}

// 登录注册时间
if ( zm_get_option( 'last_login' ) && is_admin() ) {
add_action( 'wp_login', 'insert_last_login' );
function insert_last_login( $login ) {
	global $user_id;
	$user = get_user_by( 'login', $login );
	update_user_meta( $user->ID, 'last_login', current_time( 'mysql' ) );
}

add_filter('manage_users_columns', 'add_user_additional_column');
function add_user_additional_column($columns) {
	$columns['user_nickname'] = '昵称';
	$columns['user_url'] = '网站';
	if ( ! is_plugin_active( 'erphpdown/erphpdown.php' ) ) {
		$columns['reg_time'] = '注册';
	}
	$columns['last_login'] = '登录';
	return $columns;
}

add_action( 'manage_users_custom_column', 'show_user_additional_column_content', 10, 3 );
function show_user_additional_column_content($value, $column_name, $user_id) {
	$user = get_userdata( $user_id );

	if ( 'user_nickname' == $column_name ){
		return $user->nickname;
	}

	if ( 'user_url' == $column_name ){
		return '<a href="'.$user->user_url.'" target="_blank">'.$user->user_url.'</a>';
	}

	if ( ! is_plugin_active( 'erphpdown/erphpdown.php' ) && 'reg_time' == $column_name ) {
		return get_date_from_gmt($user->user_registered) ;
	}

	if ( 'last_login' == $column_name && $user->last_login ){
		return get_user_meta( $user->ID, 'last_login', true );
	}

	return $value;
}

// 登录注册排序
add_filter( "manage_users_sortable_columns", 'be_reg_sortable_columns' );
function be_reg_sortable_columns($sortable_columns){
	$sortable_columns['reg_time'] = 'reg_time';
	return $sortable_columns;
}

add_action( 'pre_user_query', 'be_reg_order' );
function be_reg_order($obj){
	if (!isset($_REQUEST['orderby']) || $_REQUEST['orderby']=='reg_time' ){
		if ( !in_array( isset($_REQUEST['order'] ) ? $_REQUEST['order'] . '' : null, array( 'asc','desc') ) ){
			$_REQUEST['order'] = 'desc';
		}
		$obj->query_orderby = "ORDER BY user_registered ".$_REQUEST['order']."";
	}
}

add_filter( "manage_users_sortable_columns", 'be_user_sortable' );
function be_user_sortable( $sortable_columns ){
	$sortable_columns['last_login'] = 'last_login';
	return $sortable_columns;
}

add_action( 'pre_user_query', 'be_users_order' );
function be_users_order($obj){
	if ( !isset( $_REQUEST['orderby']) || $_REQUEST['orderby']=='last_login' ){
		if ( !in_array( isset($_REQUEST['order'] ) ? $_REQUEST['order'] . '' : null, array( 'asc','desc') ) ){
			$_REQUEST['order'] = 'desc';
		}
		$obj->query_orderby = "ORDER BY user_registered ".$_REQUEST['order']."";
	}
}
}

// 字段筛选
if (zm_get_option('meta_key_filter') && !wp_is_mobile()) {
	add_filter( 'parse_query', 'be_admin_posts_filter' );
	add_action( 'restrict_manage_posts', 'be_admin_posts_filter_restrict' );
}

function be_admin_posts_filter( $query ) {
	global $pagenow;
	if ( is_admin() && $pagenow=='edit.php' && isset($_GET['BE_FIELD_NAME']) && $_GET['BE_FIELD_NAME'] != '') {
		$query->query_vars['meta_key'] = $_GET['BE_FIELD_NAME'];
	if (isset($_GET['BE_FILTER_VALUE']) && $_GET['BE_FILTER_VALUE'] != '')
		$query->query_vars['meta_value'] = $_GET['BE_FILTER_VALUE'];
	}
}

function be_admin_posts_filter_restrict() {
	global $wpdb;
	$sql = 'SELECT DISTINCT meta_key FROM '.$wpdb->postmeta.' ORDER BY 1';
	$fields = $wpdb->get_results($sql, ARRAY_N);
?>
<select name="BE_FIELD_NAME">
<option value="">自定义字段</option>
<?php
	$current = isset($_GET['BE_FIELD_NAME'])? $_GET['BE_FIELD_NAME']:'';
	$current_v = isset($_GET['BE_FILTER_VALUE'])? $_GET['BE_FILTER_VALUE']:'';
	foreach ($fields as $field) {
		if (substr($field[0],0,1) != "_"){
		printf
			(
				'<option value="%s"%s>%s</option>',
				$field[0],
				$field[0] == $current? ' selected="selected"':'',
				$field[0]
			);
		}
	}
?>
</select> 值 <input type="TEXT" name="BE_FILTER_VALUE" value="<?php echo $current_v; ?>" />
<?php
}

// posts order
add_action( 'admin_init', 'be_posts_order' );
function be_posts_order() {
	add_post_type_support( 'post', 'page-attributes' );
	add_post_type_support( 'sites', 'page-attributes' );
}

if ( zm_get_option( 'bulk_actions_post' ) ) {
// 在批量操作下拉列表中添加选项
add_filter( 'bulk_actions-edit-post', 'be_my_bulk_actions' );
function be_my_bulk_actions( $bulk_array ) {
	$bulk_array['be_make_draft'] = '状态改为草稿';
	$bulk_array['be_make_publish'] = '状态改为发表 ';
	return $bulk_array;
}

add_filter( 'handle_bulk_actions-edit-post', 'be_bulk_action_handler', 10, 3 );

function be_bulk_action_handler( $redirect, $doaction, $object_ids ) {
	$redirect = remove_query_arg( array( 'be_make_draft_done', 'be_make_publish_done' ), $redirect );
	if ( $doaction == 'be_make_draft' ) {
		foreach ( $object_ids as $post_id ) {
			wp_update_post( array(
				'ID' => $post_id,
				'post_status' => 'draft'
			) );
		}

		$redirect = add_query_arg( 'be_make_draft_done', count( $object_ids ), $redirect );
	}

	if ( $doaction == 'be_make_publish' ) {
		foreach ( $object_ids as $post_id ) {
			wp_update_post( array(
				'ID' => $post_id,
				'post_status' => 'publish'
			) );
		}

		$redirect = add_query_arg( 'be_make_publish_done', count( $object_ids ), $redirect );
	}

	return $redirect;
}

add_action( 'admin_notices', 'be_bulk_action_notices' );

function be_bulk_action_notices() {
	if ( ! empty( $_REQUEST['be_make_draft_done'] ) ) {
		echo '<div id="message" class="updated notice is-dismissible">
			<p>文章状态已更新。</p>
		</div>';
	}

	if ( ! empty( $_REQUEST['be_make_publish_done'] ) ) {
		echo '<div id="message" class="updated notice is-dismissible">
			<p>文章状态已更新。</p>
		</div>';
	}
}
}
// ajax move post
if (zm_get_option('ajax_move_post')) {
add_action( 'admin_head', 'be_moveposttotrash_script' );
function be_moveposttotrash_script() {
	wp_enqueue_script( 'movepost', get_template_directory_uri() . '/js/movepost.js', array( 'jquery' ) ); 
}

add_action( 'wp_ajax_moveposttotrash', function() {
	check_ajax_referer( 'trash-post_' . $_POST['post_id'] );
	wp_trash_post( $_POST['post_id'] );
	die();
});
}

// 退出后跳转
function logout_redirect_to() {
	wp_redirect( zm_get_option('logout_to') );
	exit();
}
if ( zm_get_option( 'logout_to' ) ) {
	add_action('wp_logout', 'logout_redirect_to');
}

// disable wp image sizes
function be_customize_image_sizes( $sizes ){
	unset( $sizes[ 'thumbnail' ]);
	unset( $sizes[ 'medium' ]);
	unset( $sizes[ 'medium_large' ] );
	unset( $sizes[ 'large' ]);
	unset( $sizes[ 'full' ] );
	unset( $sizes['1536x1536'] );
	unset( $sizes['2048x2048'] );
	return $sizes;
}

// 禁用缩放
add_filter('big_image_size_threshold', '__return_false');

// disable global styles
if ( zm_get_option( 'remove_global_css' ) ) {
	add_action( 'after_setup_theme', function() {
		remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
		remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );
		remove_action( 'wp_footer', 'wp_enqueue_global_styles', 1 );
		remove_filter( 'render_block', 'wp_render_duotone_support' );
		remove_filter( 'render_block', 'wp_restore_group_inner_container' );
		remove_filter( 'render_block', 'wp_render_layout_support_flag' );
	});
}
// post type link
if (zm_get_option('begin_types_link')) {
require get_template_directory() . '/inc/types-permalink.php';
}
// 评论 Cookie
if (zm_get_option('comment_ajax') == '' ) {
	add_action('set_comment_cookies','coffin_set_cookies',10,3);

	function coffin_set_cookies( $comment, $user, $cookies_consent){
		$cookies_consent = true;
		wp_set_comment_cookies($comment, $user, $cookies_consent);
	}
}

function group_body( $classes ) {
if ( co_get_option( 'group_nav' ) ) {
		$classes[] ='group-site group-nav';
	} else {
		$classes[] ='group-site';
	}
	return $classes;
}

// 获取首字母
function getFirstCharter($str){
	if (empty($str)){
		return '';
	}
	if (is_numeric($str[0])) return $str[0];
	$fchar=ord($str[0]);
	if ($fchar>=ord('A')&&$fchar<=ord('z')) return strtoupper($str[0]);
	$s1=iconv('UTF-8','gb2312',$str);
	$s2=iconv('gb2312','UTF-8',$s1);
	$s=$s2==$str?$s1:$str;
	$asc=ord($s[0])*256+ord($s[1])-65536;
	if ($asc>=-20319&&$asc<=-20284) return 'A';
	if ($asc>=-20283&&$asc<=-19776) return 'B';
	if ($asc>=-19775&&$asc<=-19219) return 'C';
	if ($asc>=-19218&&$asc<=-18711) return 'D';
	if ($asc>=-18710&&$asc<=-18527) return 'E';
	if ($asc>=-18526&&$asc<=-18240) return 'F';
	if ($asc>=-18239&&$asc<=-17923) return 'G';
	if ($asc>=-17922&&$asc<=-17418) return 'H';
	if ($asc>=-17417&&$asc<=-16475) return 'J';
	if ($asc>=-16474&&$asc<=-16213) return 'K';
	if ($asc>=-16212&&$asc<=-15641) return 'L';
	if ($asc>=-15640&&$asc<=-15166) return 'M';
	if ($asc>=-15165&&$asc<=-14923) return 'N';
	if ($asc>=-14922&&$asc<=-14915) return 'O';
	if ($asc>=-14914&&$asc<=-14631) return 'P';
	if ($asc>=-14630&&$asc<=-14150) return 'Q';
	if ($asc>=-14149&&$asc<=-14091) return 'R';
	if ($asc>=-14090&&$asc<=-13319) return 'S';
	if ($asc>=-13318&&$asc<=-12839) return 'T';
	if ($asc>=-12838&&$asc<=-12557) return 'W';
	if ($asc>=-12556&&$asc<=-11848) return 'X';
	if ($asc>=-11847&&$asc<=-11056) return 'Y';
	if ($asc>=-11055&&$asc<=-10247) return 'Z';
	return null;
}

// 修正密码链接
function begin_reset_password_message_amend($string) {
	return preg_replace('/<(' . preg_quote(network_site_url(), '/') . '[^>]*)>/', '\1', $string);
}
function begin_user_notification_email_amend( $wp_new_user_notification_email, $user, $user_email ) {
	global $wpdb, $wp_hasher;
	$key = wp_generate_password( 20, false );
	do_action( 'retrieve_password_key', $user->user_login, $key );
	if ( empty( $wp_hasher ) ) {
		require_once ABSPATH . WPINC . '/class-phpass.php';
		$wp_hasher = new PasswordHash( 8, true );
	}
	$hashed = time() . ':' . $wp_hasher->HashPassword( $key );
	$wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user->user_login ) );
	$switched_locale = switch_to_locale( get_user_locale( $user ) );
	$message = sprintf(__('Username: %s'), $user->display_name) . "\r\n\r\n";
	$message .= __('To set your password, visit the following address:') . "\r\n\r\n";
	$message .= '' . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user->user_login), 'login') . "\r\n\r\n";
	$wp_new_user_notification_email['message'] = $message;
	return $wp_new_user_notification_email;
}

// 打开缓冲区
add_action('init', 'do_output_buffer');
function do_output_buffer() {
	ob_start();
}

// 自定义文章数量
function be_set_posts_per_page( $query ) {
	if ( ( ! is_admin() ) && ( $query === $GLOBALS['wp_the_query'] ) && ( is_category( explode( ',', zm_get_option( 'cat_posts_id' ) ) ) ) || ( is_tag( explode( ',', zm_get_option( 'cat_posts_id' ) ) ) ) ) {
		$query->set( 'posts_per_page', zm_get_option( 'posts_n' ) );
	}
}

function be_type_set_posts_per_page( $query ) {
	$args = array('taxonomy' => 'gallery', 'videos', 'taobao', 'products', 'favorites');
	if ( ( ! is_admin() ) && ( $query === $GLOBALS['wp_the_query'] ) && ( is_tax($args) ) ) {
		$query->set( 'posts_per_page', zm_get_option( 'type_posts_n' ) );
	}
}

// upload name
function be_upload_name( $file ) {
	$time = date("YmdHis");
	$file['name'] = $time . "" . mt_rand( 1, 100 ) . "." . pathinfo( $file['name'], PATHINFO_EXTENSION );
	return $file;
}
if (zm_get_option('be_upload_name')) {
	add_filter( 'wp_handle_upload_prefilter', 'be_upload_name' );
}

if ( cx_get_option( 'web_queries' ) ) {
	function queries( $visible = false ) {
		$numQueries = get_num_queries();
		$time       = timer_stop( 0, 3 );
		$memory     = memory_get_peak_usage() / 1024 / 1024;
		$stat       = sprintf( '%d 次查询 耗时 %.3f 秒 占用 %.2fMB 内存', $numQueries, $time, $memory );
		echo $visible ? $stat : "<!-- {$stat} -->";
	}
}

// 分享图片
function share_img(){
	global $post;
	$content = $post->post_content;
	preg_match_all('/<img .*?src=[\"|\'](.+?)[\"|\'].*?>/', $content, $strResult, PREG_PATTERN_ORDER);
	$n = count($strResult[1]);
	if ($n >= 1) {
		$src = $strResult[1][0];
	} else {
		$src = zm_get_option('reg_img');
	}
	return $src;
}

// clone post
function be_clone_post() {
	global $wpdb;
	if ( ! ( isset( $_GET['post'] ) || isset( $_POST['post'] ) || ( isset($_REQUEST['action'] ) && 'be_clone_post' == $_REQUEST['action'] ) ) ) {
		wp_die('No post to clone has been supplied!');
	}

	if ( !isset( $_GET['clone_nonce'] ) || !wp_verify_nonce( $_GET['clone_nonce'], basename( __FILE__ ) ) )
	return;

	$post_id = ( isset($_GET['post'] ) ? absint( $_GET['post'] ) : absint( $_POST['post'] ) );

	$post = get_post( $post_id );

	// 如果不希望当前用户作为新文章作者，将下两行替换为$new_post_author = $post->post_author;
	$current_user = wp_get_current_user();
	$new_post_author = $current_user->ID;
	if ( isset( $post ) && $post != null ) {
		$args = array(
			'comment_status' => $post->comment_status,
			'ping_status'    => $post->ping_status,
			'post_author'    => $new_post_author,
			'post_content'   => $post->post_content,
			'post_excerpt'   => $post->post_excerpt,
			'post_name'      => $post->post_name,
			'post_parent'    => $post->post_parent,
			'post_password'  => $post->post_password,
			'post_status'    => 'draft',
			'post_title'     => $post->post_title,
			'post_type'      => $post->post_type,
			'to_ping'        => $post->to_ping,
			'menu_order'     => $post->menu_order
		);

		$new_post_id = wp_insert_post( $args );

		// 将新的文章设置为草稿
		$taxonomies = get_object_taxonomies( $post->post_type ); // 返回文章类型的分类数组，例如：array("category", "post_tag");
		foreach ($taxonomies as $taxonomy) {
			$post_terms = wp_get_object_terms($post_id, $taxonomy, array( 'fields' => 'slugs' ) );
			wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
		}

		$post_meta_infos = $wpdb->get_results( "SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id" );
		if ( count($post_meta_infos)!=0 ) {
			$sql_query = "INSERT INTO $wpdb->postmeta ( post_id, meta_key, meta_value )";
			foreach ( $post_meta_infos as $meta_info ) {
				$meta_key = $meta_info->meta_key;
				if ( $meta_key == '_wp_old_slug' ) continue;
				$meta_value = addslashes( $meta_info->meta_value );
				$sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
			}
			$sql_query.= implode( " UNION ALL ", $sql_query_sel );
			$wpdb->query($sql_query);
		}
		wp_redirect( admin_url( 'edit.php?post_type='.$post->post_type ) );
		exit;
	} else {
		wp_die( '复制文章失败： ' . $post_id );
	}
}
add_action( 'admin_action_be_clone_post', 'be_clone_post' );

function be_clone_post_link( $actions, $post ) {
	if ( current_user_can( 'edit_post', $post->ID ) ) {
		$actions['clone'] = '<a href="' . wp_nonce_url( 'admin.php?action=be_clone_post&post=' . $post->ID, basename(__FILE__), 'clone_nonce' ) . '" rel="permalink">复制</a>';
	}
	return $actions;
}
if ( zm_get_option( 'clone_post' ) ) {
	add_filter( 'post_row_actions', 'be_clone_post_link', 10, 2 );
	add_filter( 'page_row_actions', 'be_clone_post_link', 10, 2 );
}

// 小工具添加CSS类
if ( zm_get_option( 'widget_class' ) && is_admin() ) {
	add_filter( 'in_widget_form', 'be_class_widget_form', 10, 3 );
}
function be_class_widget_form( $widget, $return, $instance ) {
	if ( !isset( $instance['classes'] ) ) {
		$instance['classes'] = null;
	}
	echo '<p>';
	echo '<label for="' . $widget->get_field_id('classes') . '">CSS类</label>';
	echo '<input type="text" name="' . $widget->get_field_name('classes'). '" id="' . $widget->get_field_id('classes'). '" class="widefat" value="' . $instance['classes']. '" />';
	echo '</p>';
	return;
}

add_filter( 'widget_update_callback', 'be_class_widget_update', 10, 2 );
function be_class_widget_update( $instance, $new_instance ) {
	$instance['classes'] = ( ! empty( $new_instance['classes'] ) ? $new_instance['classes'] : '' );
	return $instance;
}

add_filter( 'dynamic_sidebar_params', 'be_class_dynamic_sidebar_params' );
function be_class_dynamic_sidebar_params( $params ) {
	global $wp_registered_widgets;
	$widget_id  = $params[0]['widget_id'];
	$widget_obj = $wp_registered_widgets[$widget_id];
	$widget_opt = get_option( $widget_obj['callback'][0]->option_name );
	$widget_num = $widget_obj['params'][0]['number'];

	if ( isset( $widget_opt[$widget_num]['classes'] ) && !empty( $widget_opt[$widget_num]['classes'] ) ) {
		$params[0]['before_widget'] = preg_replace( '/class="/', "class=\"{$widget_opt[$widget_num]['classes']} ", $params[0]['before_widget'], 1 );
	}
	return $params;
}

// widgets title span
function title_i_w() {
	if (zm_get_option('title_i')) {
	return '<span class="title-i"><span></span><span></span><span></span><span></span></span>';
	} else {
	return '<span class="title-w"></span>';
	}
}

if ( zm_get_option( 'home_paged_ban' ) ) {
	// 禁止首页翻页
	function redirect_home_pagination() {
		if ( is_front_page() && is_home() && is_paged() ) {
			wp_redirect(home_url(), 301);
			exit;
		}
	}

	if ( be_get_option( 'layout' ) == 'grid' || be_get_option( 'layout' ) == 'cms' || be_get_option( 'layout' ) == 'group' ) {
		add_action( 'template_redirect', 'redirect_home_pagination' );
	}
}

// SVG
if (current_user_can( 'manage_options' )) {
	add_filter('upload_mimes', function ($mimes) {
		$mimes['svg'] = 'image/svg+xml';
		return $mimes;
	});
}

// Media Libary Display SVG
function be_display_svg_media($response, $attachment, $meta){
	if ($response['type'] === 'image' && $response['subtype'] === 'svg+xml' && class_exists('SimpleXMLElement')){
		try {
			$path = get_attached_file($attachment->ID);
			if (@file_exists($path)){
				$svg                = new SimpleXMLElement(@file_get_contents($path));
				$src                = $response['url'];
				$width              = (int) $svg['width'];
				$height             = (int) $svg['height'];
				$response['image']  = compact( 'src', 'width', 'height' );
				$response['thumb']  = compact( 'src', 'width', 'height' );

				$response['sizes']['full'] = array(
					'height'        => $height,
					'width'         => $width,
					'url'           => $src,
					'orientation'   => $height > $width ? 'portrait' : 'landscape',
				);
			}
		}
		catch(Exception $e){}
	}
	return $response;
}
add_filter('wp_prepare_attachment_for_js', 'be_display_svg_media', 10, 3);

// Admin Styles svg
add_action('admin_head', function () {
	echo "<style>table.media .column-title .media-icon img[src*='.svg']{width: 100%;height: auto;}.components-responsive-wrapper__content[src*='.svg'] {position: relative;}</style>";
});

// user upload files
function user_upload_files() {
	$role = get_role( 'contributor' );
	if ( ! $role ) {
		// 如果角色不存在，则先注册角色
		$role = add_role( 'contributor', __( 'Contributor' ) );
	}

	if ( ! zm_get_option( 'user_upload' ) || ( zm_get_option( 'user_upload' ) == 'removecap' ) ) {
		$role->remove_cap( 'upload_files' );
	}

	if ( zm_get_option( 'user_upload') == 'addcap' ) {
		$role->add_cap( 'upload_files' );
	}
}
add_action( 'admin_init', 'user_upload_files' );

// custom field number
add_filter( 'postmeta_form_limit' , 'customfield_limit' );
function customfield_limit( $limit ) {
	$limit = 100;
	return $limit;
}

//Remove JQuery migrate
function remove_jquery_migrate( $scripts ) {
	if ( ! is_admin() && ! empty( $scripts->registered['jquery'] ) ) {
		$scripts->registered['jquery']->deps = array_diff(
			$scripts->registered['jquery']->deps,
			['jquery-migrate']
		);
	}
}
if ( cx_get_option( 'remove_jqmigrate' ) ) {
add_action( 'wp_default_scripts', 'remove_jquery_migrate' );
}
// JS defer
function be_add_attribute_to_script_tag( $tag, $handle ) {
	$scripts_to_defer = array( 
		'jquery-migrate',
		'lazyload',
		'copyrightpro',
		'3dtag',
		'superfish',
		'be_script',
		'ajax-content',
		'gb2big5',
		'qrious-js',
		'owl',
		'sticky',
		'aos',
		'ias',
		'infinite-post',
		'infinite-comment',
		'letter',
		'ajax_tab',
		'fancybox',
		'qqinfo',
		'clipboard-js',
		'prettify',
		'social-share',
		'jquery-ui',
		'qaptcha',
		'comments-ajax'
	);
	foreach( $scripts_to_defer as $defer_script ) {
		if ( $defer_script === $handle ) {
			return str_replace( ' src', ' defer src', $tag );
		}
	}
	return $tag;
}
if ( zm_get_option( 'script_defer' ) ) {
	add_filter( 'script_loader_tag', 'be_add_attribute_to_script_tag', 10, 2 );
}
// delete_favorite
function delete_favorite_table(){
	global $wpdb;
	$table = $wpdb->prefix . 'be_favorite';
	$sql = "DROP TABLE IF EXISTS $table";
	$wpdb->query($sql);
}

if ( cx_get_option( 'delete_favorite' ) ) {
	delete_favorite_table();
}

// Night Mode
function be_night_mode() { ?>
<script>const SITE_ID = window.location.hostname;if (localStorage.getItem(SITE_ID + '-beNightMode')) {document.body.className += ' night';}</script>
<?php
}

if ( zm_get_option('read_night') && get_bloginfo( 'version' ) <= 5.2 ) {
	// wp_body_open
	if ( ! function_exists( 'wp_body_open' ) ) {
		function wp_body_open() {
			do_action( 'wp_body_open' );
		}
	}
}

if ( zm_get_option( 'be_safety' ) ) {
	global $user_ID; if ( $user_ID ) {
		if ( !current_user_can( 'administrator' ) ) {
			if ( strlen($_SERVER['REQUEST_URI'] ) > 255 ||
			stripos( $_SERVER['REQUEST_URI'], "eval(" ) ||
			stripos( $_SERVER['REQUEST_URI'], "CONCAT" ) ||
			stripos( $_SERVER['REQUEST_URI'], "UNION+SELECT" ) ||
			stripos( $_SERVER['REQUEST_URI'], "base64" ) ) {
				@header("HTTP/1.1 414 Request-URI Too Long" );
				@header( "Status: 414 Request-URI Too Long" );
				@header( "Connection: Close" );
				@exit;
			}
		}
	}
}

if ( zm_get_option( 'delete_enclosure' ) ) {
// 禁enclosed字段
function be_delete_enclosure(){
	return '';
}
add_filter( 'get_enclosed', 'be_delete_enclosure' ); 
add_filter( 'rss_enclosure', 'be_delete_enclosure' );
add_filter( 'atom_enclosure', 'be_delete_enclosure' );
}

if ( ! zm_get_option( 'wp_sitemap' ) ) {
	// 禁wp-sitemap
	add_filter( 'wp_sitemaps_enabled', '__return_false' );
}

// 回复replytocom
add_filter('comment_reply_link', 'del_replytocom', 420, 4);
function del_replytocom($link, $args, $comment, $post){
	return preg_replace( '/href=\'(.*(\?|&)replytocom=(\d+)#respond)/', 'href=\'#comment-$3', $link );
}

// 移除相册样式
add_filter( 'use_default_gallery_style', '__return_false' );

// 登录震动
function wps_login_error() {
	remove_action('login_head', 'wp_shake_js', 12);
}
add_action('login_head', 'wps_login_error');

// 禁用响应图片
add_filter( 'wp_calculate_image_srcset_meta', '__return_false' );

// add_new_user_role
function add_new_user_role() {
if ( zm_get_option( 'user_edit_posts' ) ) {
	$edit_posts = true;
} else {
	$edit_posts = false;
}

if ( zm_get_option( 'user_upload_files' ) ) {
	$upload_files = true;
} else {
	$upload_files = false;
}

add_role(
	'vip_roles',
	zm_get_option( 'roles_name' ),
	array(
		'read'                   => true,
		'edit_posts'             => true,
		'publish_posts'          => $edit_posts,
		'delete_posts'           => $edit_posts,
		'edit_published_posts'   => $edit_posts,
		'delete_published_posts' => $edit_posts,
		'upload_files'           => $upload_files,
	));
}

if (zm_get_option('del_new_roles') == 'new_roles') {
	add_action( 'init', 'add_new_user_role' );
}

function remove_new_user_role() {
	remove_role( 'vip_roles' );
}

if (zm_get_option('del_new_roles') == 'del_roles') {
	add_action( 'init', 'remove_new_user_role' );
}

// is_wp_login
function is_wp_login() {
	$ABSPATH_BE = str_replace( array( '\\','/' ), DIRECTORY_SEPARATOR, ABSPATH );
	return ( ( in_array($ABSPATH_BE.'wp-login.php', get_included_files() ) || in_array( $ABSPATH_BE.'wp-register.php', get_included_files() ) ) || ( isset( $_GLOBALS['pagenow'] ) && $GLOBALS['pagenow'] === 'wp-login.php' ) || $_SERVER['PHP_SELF']== '/wp-login.php' );
}

// 登录访问
if (zm_get_option('force_login')) {
add_action( 'template_redirect', 'be_force_login' );
function be_force_login() {
	if ( ! is_user_logged_in() ) {
		$schema = isset( $_SERVER['HTTPS'] ) && 'on' === $_SERVER['HTTPS'] ? 'https://' : 'http://';
		$url = $schema . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$allowed = apply_filters_deprecated( 'be_force_login_whitelist', array( array( zm_get_option('force_login_url') ) ), '1.0', 'be_force_login_bypass' );
		$bypass = apply_filters( 'be_force_login_bypass', in_array( $url, $allowed ), $url );
		if ( preg_replace( '/\?.*/', '', $url ) !== preg_replace( '/\?.*/', '', wp_login_url() ) && ! $bypass ) {
			nocache_headers();
			$page = zm_get_option('force_login_url');
			wp_safe_redirect( $page, 302 );
			exit;
		}
	}
}
}
if ( zm_get_option('copyright_pro') && !current_user_can('level_10') ) {
function bejs() {
	echo '<noscript><div class="bejs"><p>需启用JS脚本</p></div></noscript>';
}
add_action( 'wp_footer', 'bejs', 100 );
}

// 登录语言
add_filter( 'login_display_language_dropdown', '__return_false' );

// 重定向登录
function be_redirect_login() {
	global $pagenow;
	$action = (isset($_GET['action'])) ? $_GET['action'] : '';
	if ( $pagenow == 'wp-login.php' && ( ! $action || ( $action && ! in_array($action, array('logout', 'lostpassword', 'rp', 'resetpass'))))) {
		$page = zm_get_option('redirect_login_link');
		wp_redirect($page);
		exit();
	}
}

// 复制下载
if ( cx_get_option( 'root_file_move' ) ) {
	function be_root_file_move() {
		$file = get_template_directory() . '/inc/download.php';
		if ( file_exists( $file ) ) {
			$downFile = ABSPATH . '/download.php';
			copy( $file, $downFile );
		}
	}
	if ( is_admin() ) {
		add_action( 'init', 'be_root_file_move' );
	}
}
// 仪表盘
function be_remove_dashboard_meta() {
	remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_site_health', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_primary', 'dashboard', 'normal' );
	remove_action( 'welcome_panel', 'wp_welcome_panel' );
}

function be_remove_stupid_php_nag() {
	remove_meta_box( 'dashboard_php_nag', 'dashboard', 'normal' );
}

if ( cx_get_option( 'hide_dashboard' ) ) {
	add_action( 'admin_init', 'be_remove_dashboard_meta' );
	add_action( 'wp_dashboard_setup', 'be_remove_stupid_php_nag' );
}

// 自定义仪表盘
function be_dashboard_widget() { ?>
	<p>
		<?php echo cx_get_option( 'dashboard_content' ); ?>
		<p class="clear"></p>
	</p>
<?php }

function add_be_dashboard_widget() {
	wp_add_dashboard_widget( 'be_dashboard_widget', cx_get_option( 'dashboard_title' ), 'be_dashboard_widget' );
}
if ( cx_get_option( 'add_dashboard' ) ) {
add_action( 'wp_dashboard_setup', 'add_be_dashboard_widget' );
}

// get_bgimg
function get_bgimg() {
	global $post;
	$content = $post->post_content;
	preg_match_all( '/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER );
	echo $strResult[1][0];
}

if ( cx_get_option( 'be_upload_path' ) ) {
	// 修改媒体路径
	if ( get_option( 'upload_path' ) == 'wp-content/uploads' || get_option( 'upload_path' ) == null ) {
		update_option( 'upload_path', cx_get_option( 'be_upload_path_url' ) );
	}
}

// 判断子分类存在否
function has_term_children( $term_id = '', $taxonomy = 'category' ) {
	if ( ! $term_id ) {
		return false;
	}

	$filtered_term_id = filter_var( $term_id, FILTER_VALIDATE_INT );
	$filtered_taxonomy = filter_var( $taxonomy, FILTER_SANITIZE_FULL_SPECIAL_CHARS );

	$term_children = get_term_children( $filtered_term_id, $filtered_taxonomy );

	if ( empty( $term_children ) || is_wp_error( $term_children ) ) {
		return false;
	} else {
		return true;
	}
}

// 输出子分类ID
function be_subcat_id() {
	$args = array(
		'hierarchical' => 1,
		'parent'       => get_query_var( 'cat' ),
		'orderby'      => 'menu_order',
		'order'        => 'ASC',
		'hide_empty'   => 0,
	);

	$subcats = get_categories( $args );
	foreach ( $subcats as $cat ) {
		$catid[]  = $cat->cat_ID;
		$arrid = array_unique( $catid );
		$subid = implode( ",", $arrid );
	}

	if ( has_term_children( get_query_var( 'cat' ) ) ) {
		return $subid;
	}
}

function be_cat_btn() {
	if ( has_term_children( get_query_var( 'cat' ) ) ) {
		$btn = 'yes';
	} else {
		$btn = 'no';
	}
	return $btn;
}

// 排序
function be_order_btu() { ?>
	<div class="be-order-box betip" <?php aos_a(); ?>>
		<div class="be-order-btu be-order-title"><i class="be be-sort"></i> <?php _e( '排序', 'begin' ); ?></div>
		<?php if ( be_get_option( 'order_date' ) ) { ?>
			<div class="be-sort-date sort-btu" title="<?php _e( '按发表日期排序', 'begin' ); ?>"><?php _e( '日期', 'begin' ); ?></div>
		<?php } ?>

		<?php if ( be_get_option( 'order_modified' ) ) { ?>
			<div class="be-sort-modified sort-btu" title="<?php _e( '按最后更新日期排序', 'begin' ); ?>"><?php _e( '更新', 'begin' ); ?></div>
		<?php } ?>

		<?php if ( be_get_option( 'order_comments' ) ) { ?>
			<div class="be-sort-comments sort-btu" title="<?php _e( '按评论数排序', 'begin' ); ?>"><?php _e( '热评', 'begin' ); ?></div>
		<?php } ?>

		<?php if ( be_get_option( 'order_views' ) ) { ?>
			<div class="be-sort-views sort-btu" title="<?php _e( '按点击量排序', 'begin' ); ?>"><?php _e( '热门', 'begin' ); ?></div>
		<?php } ?>

		<?php if ( be_get_option( 'order_like' ) ) { ?>
			<div class="be-sort-like sort-btu" title="<?php _e( '按点赞量排序', 'begin' ); ?>"><?php _e( '点赞', 'begin' ); ?></div>
		<?php } ?>

		<?php if ( be_get_option( 'order_random' ) ) { ?>
			<div class="be-sort-random sort-btu" title="<?php _e( '随机排序', 'begin' ); ?>"><?php _e( '随机', 'begin' ); ?></div>
		<?php } ?>
		<?php be_help( $text = '首页设置 → 博客布局 → 常规模式 → 文章排序按钮' ); ?>
		<div class="clear"></div>
	</div>
<?php }

// 限制标签数量
if ( zm_get_option( 'limit_tags_number' ) ) {
add_filter( 'term_links-post_tag', 'be_limit_tags' );
}
function be_limit_tags( $terms ) {
	return array_slice( $terms, 0, zm_get_option( 'limit_tags_number' ), true );
}

// seo标签
function be_seo_tags() {
	$posttags = get_the_tags();
	$count = 0; $sep = '';
	if ( $posttags ) {
		foreach( $posttags as $tag ) {
			$count++;
			echo $sep, $tag->name;
			$sep = zm_get_option( 'seo_separator_tag' );
			if ( $count > zm_get_option( 'limit_tags_number' ) ) break;
		}
	}
}

if ( zm_get_option( 'auto_tags' ) ) {
	// 根据文章内容自动添加标签
	function be_auto_add_tags( $post_id ) {
		// 检查是否为自动保存，防止无限循环
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

		// 检查用户是否有权限编辑文章
		if ( ! current_user_can( 'edit_post', $post_id ) ) return;

		$post = get_post( $post_id );
		if ( $post ) {
			$post_content = $post->post_content;
			if (! empty( $post_content ) ) {
				$tags = get_tags( ['hide_empty' => false] );
				if ( $tags ) {
					$i = 0;
					if ( zm_get_option( 'auto_tags_random' ) ) {
						shuffle( $tags );
					}
					foreach ( $tags as $tag ) {
						if ( strpos( $post_content, $tag->name) !== false ) {
							if ( $i == zm_get_option( 'auto_tags_n' ) ) break;
							wp_set_post_tags( $post_id, $tag->name, true );
							$i++;
						}
					}
				}
			}
		}
	}

	// 根据文章标题自动添加标签
	function be_auto_title_add_tags( $post_id ) {
		// 检查是否为自动保存，防止无限循环
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;

		// 检查用户是否有权限编辑文章
		if ( ! current_user_can( 'edit_post', $post_id ) ) return;

		$post = get_post( $post_id );
		if ($post) {
			$post_title = $post->post_title;
			if ( ! empty( $post_title ) ) {
				$tags = get_tags( ['hide_empty' => false] );
				if ( $tags ) {
					$i = 0;
					if ( zm_get_option( 'auto_tags_random' ) ) {
						shuffle( $tags );
					}
					foreach ( $tags as $tag ) {
						if ( strpos( $post_title, $tag->name ) !== false) {
							if ( $i == zm_get_option( 'auto_tags_n' ) ) break;
							wp_set_post_tags( $post_id, $tag->name, true );
							$i++;
						}
					}
				}
			}
		}
	}

	// 在保存文章时自动添加标签
	function be_auto_add_tags_save( $post_id ) {
		if ( ! zm_get_option( 'get_auto_tags' ) || ( zm_get_option('get_auto_tags') == 'content' ) ) {
			be_auto_add_tags( $post_id );
		} elseif ( zm_get_option( 'get_auto_tags' ) == 'title' ) {
			be_auto_title_add_tags( $post_id );
		}
	}

	// 在访问文章页面时自动添加标签
	function be_auto_add_tags_view() {
		if ( is_single() ) {
			$post_id = get_the_ID();
			if ( ! zm_get_option( 'get_auto_tags' ) || ( zm_get_option( 'get_auto_tags' ) == 'content' ) ) {
				be_auto_add_tags($post_id);
			} elseif ( zm_get_option( 'get_auto_tags' ) == 'title' ) {
				be_auto_title_add_tags( $post_id );
			}
		}
	}

	// 绑定到保存文章动作钩子上
	if ( is_array( zm_get_option( 'auto_tags_mode' ) ) && in_array( 'save', zm_get_option( 'auto_tags_mode' ) ) ) {
		add_action( 'save_post', 'be_auto_add_tags_save' );
	}

	// 绑定到模板重定向动作钩子上
	if ( is_array( zm_get_option( 'auto_tags_mode' ) ) && in_array( 'view', zm_get_option( 'auto_tags_mode' ) ) ) {
		add_action( 'template_redirect', 'be_auto_add_tags_view' );
	}
}

// 标签固定链接
if ( zm_get_option( 'be_tags_rules' ) ) {
	add_action( 'generate_rewrite_rules', 'be_tag_rewrite_rules' );
	add_filter( 'term_link', 'be_tag_term_link', 10,3 );
	add_action( 'query_vars', 'be_tag_query_vars' );

	function be_tag_rewrite_rules( $wp_rewrite ) {
		$new_rules = array(
			'tag/(\d+)/feed/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?tag_id=$matches[1]&feed=$matches[2]',
			'tag/(\d+)/(feed|rdf|rss|rss2|atom)/?$'      => 'index.php?tag_id=$matches[1]&feed=$matches[2]',
			'tag/(\d+)/embed/?$'                         => 'index.php?tag_id=$matches[1]&embed=true',
			'tag/(\d+)/page/(\d+)/?$'                    => 'index.php?tag_id=$matches[1]&paged=$matches[2]',
			'tag/(\d+)/?$'                               => 'index.php?tag_id=$matches[1]',
		);

		$wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
	}

	function be_tag_term_link( $link, $term, $taxonomy ) {
		if ( $taxonomy=='post_tag' ) {
			return home_url( '/tag/'.$term->term_id );
		}

		return $link;
	}

	function be_tag_query_vars( $public_query_vars ) {
		$public_query_vars[] = 'tag_id';
		return $public_query_vars;
	}
}

// 添加点赞字段
if ( zm_get_option( 'auto_add_like' ) ) {
	add_action( 'publish_post', 'be_add_zm_like' );
	function be_add_zm_like( $post_ID ) {
		global $wpdb;
		if ( ! wp_is_post_revision( $post_ID ) ) {
			add_post_meta( $post_ID, 'zm_like', '0', true );
		}
	}
}

// 不显示子分类文章
function be_exclude_child_cats() {
	if ( zm_get_option( 'no_child' ) && is_category() ) {
		$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
		query_posts( array( 'category__in' => array( get_query_var( 'cat' ) ), 'paged' => $paged ) );
	}
}

// 提示填写邮件
if ( zm_get_option( 'user_bind_email' ) && is_user_logged_in() ) {
	add_action( 'wp_body_open', 'be_user_email_tip' );
	function be_user_email_tip() {
		$current_user = wp_get_current_user();
		if ( empty( $current_user->user_email ) ) {
			echo '<div class="bind-email-box fd">';
			echo '<div class="bind-email-area">';
			echo '<div class="bind-email-tip"><i class="be be-edit"></i> ' . sprintf( __( '请完善个人信息，绑定邮箱！', 'begin' ) ) . '</div>';
			echo '<div class="bind-email-content">' . sprintf( __( '之后可以用邮箱登录', 'begin' ) ) . '<br />' . sprintf( __( '并且此信息也将不再提示！', 'begin') ) . '</div>';
			echo '<a class="bind-email-btn" href="' . get_permalink( zm_get_option( 'user_url' ) ) . '#my-profile">' . sprintf( __( '现在绑定', 'begin' ) ) . '</a>';
			echo '</div>';
			echo '</div>';
		}
	}
}

// 恢复面板位置
if ( zm_get_option( 'be_meta_box_order' ) ) {
	global $wpdb;
	$user_id = get_current_user_id();
	$table_name = $wpdb->prefix . 'usermeta';
	$post_meta_key = 'meta-box-order_post';
	$page_meta_key = 'meta-box-order_page';
	$wpdb->query( $wpdb->prepare( "DELETE FROM `$table_name` WHERE `user_id` = %d AND `meta_key` = %s", $user_id, $post_meta_key ) );
	$wpdb->query( $wpdb->prepare( "DELETE FROM `$table_name` WHERE `user_id` = %d AND `meta_key` = %s", $user_id, $page_meta_key ) );
}

// 直接进入阅读模式
function reading_mode( $classes ) {
	$classes[] = 'read';
	return $classes;
}

function single_eyecare( $classes ) {
	$classes[] = 'eyecare';
	return $classes;
}

function single_novel( $classes ) {
	$classes[] = 'template-novel';
	return $classes;
}

// 移除classic_heme_styles
add_action( 'wp_enqueue_scripts', 'be_remove_classic_heme_styles', 20 );
function be_remove_classic_heme_styles() {
	wp_dequeue_style( 'classic-theme-styles' );
}

// 禁止修订
if ( zm_get_option( 'disable_revisions' ) ) {
	function be_disable_post_type_revisions() {
		$post_types = get_post_types( array(), 'names' );
		foreach ( $post_types as $post_type ) {
			remove_post_type_support( $post_type, 'revisions' );
		}
	}
	add_action( 'init', 'be_disable_post_type_revisions' );
}

// 禁止RSS FEED
if ( zm_get_option( 'disable_feeds' ) ) {
	function be_disable_feeds() {
		wp_die( __('No feeds available!') );
	}

	add_action( 'do_feed', 'be_disable_feeds', 1 );
	add_action( 'do_feed_rdf', 'be_disable_feeds', 1 );
	add_action( 'do_feed_rss', 'be_disable_feeds', 1 );
	add_action( 'do_feed_rss2', 'be_disable_feeds', 1 );
	add_action( 'do_feed_atom', 'be_disable_feeds', 1 );
	add_action( 'do_feed_rss2_comments', 'be_disable_feeds', 1 );
	add_action( 'do_feed_atom_comments', 'be_disable_feeds', 1 );
}

// 修改WP地图文章数
if ( zm_get_option( 'wp_sitemaps_max' ) ) {
add_filter( 'wp_sitemaps_max_urls', 'be_sitemaps_max_urls' );
}
function be_sitemaps_max_urls(){
	return zm_get_option( 'wp_sitemaps_n' );
}

// 保存时刷新固定链接
if ( zm_get_option( 'be_flush_rewrite' ) ) {
	add_action( 'zmop_begin_save_after', function() {
		flush_rewrite_rules();
	} );
}

// 通用菜单
function regular_menu( $classes ) {
	$classes[] = 'head-normal';
	return $classes;
}

if ( zm_get_option( 'header_normal' ) ) {
	add_filter( 'body_class','regular_menu' );
}

// 维护模式
function wp_under_maintenance() {
	$isLoginPage = basename( $_SERVER['PHP_SELF'] ) == 'wp-login.php';
	if ( zm_get_option( 'be_maintain' ) && ! $isLoginPage && ! is_user_logged_in() && ! is_admin() && ! current_user_can( "update_plugins" ) ) {
		get_template_part( '/inc/maintenance' );
		exit();
	}
}

if ( zm_get_option( 'be_maintain' ) ) {
	add_action( 'init', 'wp_under_maintenance', 30 );
}

// 前端英文
function be_language_english() {
	if ( ! is_admin() ) {
		switch_to_locale( 'en_US' );
		load_theme_textdomain( 'begin', get_template_directory() . '/languages' );
	}
}

if ( cx_get_option( 'front_english' ) ) {
	add_action( 'init', 'be_language_english' );
}

// 保持夜间
function be_night( $classes ) {
	$classes[] = ' night';
	return $classes;
}
if ( zm_get_option( 'keep_night' ) ) {
	add_filter( 'body_class','be_night' );
}

// 禁用更新检查
if ( is_admin() && zm_get_option( 'disabling_update' ) ) {
	remove_action( 'admin_init', '_maybe_update_core' );
	remove_action( 'admin_init', '_maybe_update_plugins' );
	remove_action( 'admin_init', '_maybe_update_themes' );
	remove_action( 'load-plugins.php', 'wp_update_plugins' );
	remove_action( 'load-themes.php', 'wp_update_themes' );
	add_filter( 'pre_site_transient_browser_'. md5( $_SERVER['HTTP_USER_AGENT'] ), '__return_empty_array' );
}
// 隐藏菜单
add_action( 'admin_menu', 'hide_remove_menu' );
function hide_remove_menu() {
	if ( is_array( cx_get_option( 'hide_wp_menu' ) ) && in_array( 'link', cx_get_option( 'hide_wp_menu' ) ) ) {
		remove_menu_page( 'link-manager.php' );
	}
	if ( is_array( cx_get_option( 'hide_wp_menu' ) ) && in_array( 'page', cx_get_option( 'hide_wp_menu' ) ) ) {
		remove_menu_page( 'edit.php?post_type=page' );
	}
	if ( is_array( cx_get_option( 'hide_wp_menu' ) ) && in_array( 'comments', cx_get_option( 'hide_wp_menu' ) ) ) {
		remove_menu_page( 'edit-comments.php' );
	}
	if ( is_array( cx_get_option( 'hide_be_menu' ) ) && in_array( 'bulletin', cx_get_option( 'hide_be_menu' ) ) ) {
		remove_menu_page( 'edit.php?post_type=bulletin' );
	}
	if ( is_array( cx_get_option( 'hide_be_menu' ) ) && in_array( 'picture', cx_get_option( 'hide_be_menu' ) ) ) {
		remove_menu_page( 'edit.php?post_type=picture' );
	}
	if ( is_array( cx_get_option( 'hide_be_menu' ) ) && in_array( 'video', cx_get_option( 'hide_be_menu' ) ) ) {
		remove_menu_page( 'edit.php?post_type=video' );
	}
	if ( is_array( cx_get_option( 'hide_be_menu' ) ) && in_array( 'tao', cx_get_option( 'hide_be_menu' ) ) ) {
		remove_menu_page( 'edit.php?post_type=tao' );
	}
	if ( is_array( cx_get_option( 'hide_be_menu' ) ) && in_array( 'sites', cx_get_option( 'hide_be_menu' ) ) ) {
		remove_menu_page( 'edit.php?post_type=sites' );
	}
	if ( is_array( cx_get_option( 'hide_be_menu' ) ) && in_array( 'show', cx_get_option( 'hide_be_menu' ) ) ) {
		remove_menu_page( 'edit.php?post_type=show' );
	}
	if ( is_array( cx_get_option( 'hide_be_menu' ) ) && in_array( 'playlist', cx_get_option( 'hide_be_menu' ) ) ) {
		remove_menu_page( 'edit.php?post_type=ai_playlist' );
	}
	if ( is_array( cx_get_option( 'hide_be_menu' ) ) && in_array( 'surl', cx_get_option( 'hide_be_menu' ) ) ) {
		remove_menu_page( 'edit.php?post_type=surl' );
	}
	if ( is_array( cx_get_option( 'hide_be_menu' ) ) && in_array( 'invite', cx_get_option( 'hide_be_menu' ) ) ) {
		remove_menu_page( 'be_invitation_code' );
	}
	if ( is_array( cx_get_option( 'hide_wp_menu' ) ) && in_array( 'themes', cx_get_option( 'hide_wp_menu' ) ) ) {
		remove_menu_page( 'themes.php' );
	}
	if ( is_array( cx_get_option( 'hide_wp_menu' ) ) && in_array( 'customize', cx_get_option( 'hide_wp_menu' ) ) ) {
		remove_submenu_page( 'themes.php','customize.php?return='. rawurlencode( $_SERVER['REQUEST_URI'] ) );
	}
	if ( is_array( cx_get_option( 'hide_wp_menu' ) ) && in_array( 'plugins', cx_get_option( 'hide_wp_menu' ) ) ) {
		remove_menu_page( 'plugins.php' );
	}
	if ( is_array( cx_get_option( 'hide_wp_menu' ) ) && in_array( 'users', cx_get_option( 'hide_wp_menu' ) ) ) {
		remove_menu_page( 'users.php' );
	}
	if ( is_array( cx_get_option( 'hide_wp_menu' ) ) && in_array( 'tools', cx_get_option( 'hide_wp_menu' ) ) ) {
		remove_menu_page( 'tools.php' );
	}
	if ( is_array( cx_get_option( 'hide_wp_menu' ) ) && in_array( 'options', cx_get_option( 'hide_wp_menu' ) ) ) {
		remove_menu_page( 'options-general.php' );
	}
}

if ( cx_get_option( 'remove_footer_wp' ) ) {
	// 隐藏WP链接和版本
	function remove_footer_wpurl () {
		return cx_get_option( 'wp_footer_inf' );
	}

	function remove_footer_version() {
		return '';
	}
	add_filter( 'admin_footer_text', 'remove_footer_wpurl', 9999 );
	add_filter( 'update_footer', 'remove_footer_version', 9999 );
}

// 修改登录链接
if ( zm_get_option( 'login_link' ) ) {
	add_action('login_enqueue_scripts','login_protect');// 忘了删除
}
function login_protect(){
	if ($_GET[''.zm_get_option('pass_h').''] != ''.zm_get_option('word_q').'')header('Location: '.zm_get_option('go_link').'');
}

// 移除rel="author"
function be_remove_author_rel_link( $link ) {
	$link = str_replace( ' rel="author"', '', $link );
	return $link;
}
add_filter( 'the_author_posts_link', 'be_remove_author_rel_link' );

define('ZM_IMAGE_PLACEHOLDER', "data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=");