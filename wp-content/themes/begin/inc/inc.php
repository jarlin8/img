<?php
if ( ! defined( 'ABSPATH' ) ) exit;
be_setup();
if (zm_get_option('input_number')) {
	function page_nav_form() {
		global $wp_query;
		$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
		$page_max_num = $wp_query->max_num_pages;
		if ( $page_max_num > 1 ) :
	?>
		<form class="page-nav-form" action="<?php $_SERVER['REQUEST_URI']; ?>" method="get">
			<input class="input-number dah" type="number" autocomplete="off" min="1" max="<?php echo $page_max_num; ?>" onblur="if(this.value==''){this.value='<?php echo $paged; ?>';}" onfocus="if(this.value=='<?php echo $paged; ?>'){this.value='';}" value="<?php echo $paged; ?>" name="paged" />
			<?php if ( is_search() ) { ?>
				<input type="hidden" id="s" name="s" value="<?php echo get_search_query(); ?>" />
			<?php } ?>
			<div class="page-button-box"><input class="page-button dah" value="" type="submit"></div>
		</form>
		<?php
			if ( $paged > 1 ) :
			echo '<span class="max-num">';
			echo $wp_query->max_num_pages;
			echo '</span>';
			endif;
		?>
	<?php endif;
	}
}

function begin_pagenav() {
	if (is_paged()) {
		echo '<div class="clear"></div>';
	}

	if (zm_get_option('turn_small')) {
		echo '<div class="turn turn-small">';
	} else {
		echo '<div class="turn turn-normal">';
	}

	if ( zm_get_option('input_number')) {
		echo page_nav_form();
	}

	if (zm_get_option('infinite_post')) {
		global $wp_query;
		if ( $wp_query->max_num_pages > 1 ) : ?>
			<nav id="nav-below">
				<div class="nav-next"><?php previous_posts_link(''); ?></div>
				<div class="nav-previous"><?php next_posts_link(''); ?></div>
			</nav>
		<?php endif;
	}
	if (!is_paged()) {
		the_posts_pagination( array(
			'mid_size'           => zm_get_option('first_mid_size'),
			'prev_text'          => '<i class="be be-arrowleft dah"></i>',
			'next_text'          => '<i class="be be-arrowright dah"></i>',
		) );
	} else {
		the_posts_pagination( array(
			'mid_size'           => zm_get_option('mid_size'),
			'prev_text'          => '<i class="be be-arrowleft dah"></i>',
			'next_text'          => '<i class="be be-arrowright dah"></i>',
		) );
	}
	echo '<div class="clear"></div></div>';
}

function views_span() {
	if( zm_get_option('post_views') ) {
		be_the_views( true, '<span class="views"><i class="be be-eye ri"></i>','</span>' );
	}
}

function views_li() {
	if( zm_get_option('post_views') ) {
		be_the_views( true, '<li class="views"><i class="be be-eye ri"></i>','</li>' );
	}
}

function views_qa() {
	if( zm_get_option('post_views') ) {
		be_the_views( true, '<span class="qa-meta qa-views"><i>' . sprintf(__( '浏览', 'begin' )) . '</i>','</span>' );
	}
}

function views_tao() {
	if( zm_get_option('post_views') ) {
		be_the_views( true, '', '件' );
	}
}

function views_videos() {
	if( zm_get_option('post_views') ) {
		be_the_views( true, '<i class="be be-eye ri"></i>' . sprintf( __( '观看', 'begin' ) ) . '：  ','' );
	}
}

function views_video() {
	if( zm_get_option('post_views') ) {
		be_the_views( true, '' . sprintf( __( '观看', 'begin' ) ) . '：',' ' . sprintf( __( '次', 'begin' ) ) . '' );
	}
}

function views_print() {
	if( zm_get_option('post_views') ) { print ''; be_the_views(); print ''; }
}

function views_group() {
	if( zm_get_option('post_views') ) { be_the_views( true, '<div class="group-views"><i class="be be-eye ri"></i>','</div>' ); }
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
function change_search_url_rewrite() {
	if ( is_search() && ! empty( $_GET['s'] ) ) {
		wp_redirect( home_url( '/search/' ) . urlencode( get_query_var( 's' ) ) . '/');
		exit();
	}
}

if (!zm_get_option('search_option') || (zm_get_option('search_option') == 'search_url')) {
	add_action( 'template_redirect', 'change_search_url_rewrite' );
}
// 搜索跳转
if (zm_get_option('auto_search_post')) {
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

if (zm_get_option('search_option') == 'search_cat') {
function search_cat_args() { ?>
	<span class="search-cat bky">
		<?php $args = array(
			'show_option_all' => ''.sprintf(__( '全部分类', 'begin' )).' ',
			'hide_empty'      => 0,
			'name'            => 'cat',
			'show_count'      => 0,
			'class'           => 's-veil',
			'taxonomy'        => 'category',
			'hierarchical'    => 1,
			'depth'           => -1,
			'echo'            => 1,
			'exclude'         => zm_get_option('not_search_cat'),
		); ?>
		<?php wp_dropdown_categories( $args ); ?>
	</span>
<?php }
}

// 排除分类
add_filter('pre_get_posts','search_filter_cat');
function search_filter_cat($query) {
	if ($query->is_search && !$query->is_admin) {
		$query->set('category__not_in', explode(',',zm_get_option('not_search_cat') ));
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
function count_words ($text) {
	global $post;
	$output = '';
	if ( '' == $text ) {
		$text = $post->post_content;
		if (mb_strlen($output, 'UTF-8') < mb_strlen($text, 'UTF-8')) $output .= '<span class="word-count">' . mb_strlen(preg_replace('/\s/','',html_entity_decode(strip_tags($post->post_content))),'UTF-8') . ''.sprintf(__( '字', 'begin' )).'</span>';
		return $output;
	}
}

// 阅读时间
function get_reading_time($content) {
	$zm_format = '<span class="reading-time">' . sprintf( __( '阅读', 'begin' ) ) . '%min%' . sprintf( __( '分', 'begin' ) ) . '%sec%' . sprintf( __( '秒', 'begin' ) ) . '</span>';
	$zm_chars_per_minute = 300;

	$zm_format = str_replace('%num%', $zm_chars_per_minute, $zm_format);
	$words = mb_strlen(preg_replace('/\s/','',html_entity_decode(strip_tags($content))),'UTF-8');
	//$words = mb_strlen(strip_tags($content));

	$minutes = floor($words / $zm_chars_per_minute);
	$seconds = floor($words % $zm_chars_per_minute / ($zm_chars_per_minute / 60));
	return str_replace('%sec%', $seconds, str_replace('%min%', $minutes, $zm_format));
}

function reading_time() {
	echo get_reading_time(get_the_content());
}

// 字数统计
function word_num () {
	global $post;
	$text_num = mb_strlen(preg_replace('/\s/','',html_entity_decode(strip_tags($post->post_content))),'UTF-8');
	return $text_num;
}

// 分类优化
function zm_category() {
	$category = get_the_category();
	if(@$category[0]){
	echo '<a href="'.get_category_link($category[0]->term_id ).'">'.$category[0]->cat_name.'</a>';
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
if (zm_get_option('be_toc')) {
	function be_toc_content( $content ) {
		global $post; $page;
		$html_comment = '<!--betoc-->';
		$comment_found = strpos( $content, $html_comment ) ? true : false;
		$fixed_location = true;
		if ( !$fixed_location && !$comment_found ) {
			return $content;
		}

		if ( get_post_meta($post->ID, 'no_toc', true) ) {
			$page_id = get_the_ID();
			$post_id = array($post->ID);
			if (is_page($page_id)) {
				return $content;
			}
			if (is_single($post_id)) {
				return $content;
			}
	 	}

		if (!is_singular()) {
			return $post->post_content;
		}

		if (!zm_get_option('toc_mode') || (zm_get_option('toc_mode') == 'toc_four')) {
			$regex = "~(<h([4]))(.*?>(.*)<\/h[4]>)~";
		}

		if (zm_get_option('toc_mode') == 'toc_all') {
			if ( get_post_meta($post->ID, 'toc_four', true) ) {
				$regex = "~(<h([4]))(.*?>(.*)<\/h[4]>)~";
			} else {
				$regex = "~(<h([2-6]))(.*?>(.*)<\/h[2-6]>)~";
			}
		}

		preg_match_all( $regex, $content, $heading_results );

		$num_match = count( $heading_results[0] );
		if( $num_match < zm_get_option('toc_title_n') ) {
			return $content;
		}

		for ( $i = 0; $i < $num_match; ++ $i ) {
			if (!zm_get_option('toc_style') || (zm_get_option('toc_style') == 'tocjq')) {
				$new_heading = "<div class='toc-box-h' name='toc-$i'>" . $heading_results[1][$i] . " id='$i' " . $heading_results[3][$i] . "</div>";
			}

			if (zm_get_option('toc_style') == 'tocphp') {
				$new_heading = $heading_results[1][$i] . " class='toch' id='$i' " . $heading_results[3][$i];
			}
			$old_heading = $heading_results[0][$i];
			$content = str_replace( $old_heading, $new_heading, $content );
		}
		
		return  $content;
	}
	add_filter( 'the_content', 'be_toc_content' );

	function be_toc() {
		global $post; $page;
		$html_comment = "<!--betoc-->";
		$comment_found = strpos( $post->post_content, $html_comment ) ? true : false;
		$fixed_location = true;
		if ( !$fixed_location && !$comment_found ) {
			return $post->post_content;
		}

		if ( get_post_meta($post->ID, 'no_toc', true) ) {
			$page_id = get_the_ID();
			$post_id = array($post->ID);
			if (is_page($page_id)) {
				return $post->post_content;
			}
			if (is_single($post_id)) {
				return $post->post_content;
			}
		}
		if (!is_singular()) {
			return $post->post_content;
		}

		if (!zm_get_option('toc_mode') || (zm_get_option('toc_mode') == 'toc_four')) {
			$regex = "~(<h([4]))(.*?>(.*)<\/h[4]>)~";
		}
		if (zm_get_option('toc_mode') == 'toc_all') {
			if ( get_post_meta($post->ID, 'toc_four', true) ) {
				$regex = "~(<h([4]))(.*?>(.*)<\/h[4]>)~";
			} else {
				$regex = "~(<h([2-6]))(.*?>(.*)<\/h[2-6]>)~";
			}
		}

		preg_match_all( $regex, $post->post_content, $heading_results );

		$num_match = count( $heading_results[0] );
		if( $num_match < zm_get_option('toc_title_n') ) {
			return $post->post_content;
		}

		$link_list = "";
		for ( $i = 0; $i < $num_match; ++ $i ) {
			$new_heading = $heading_results[1][$i] . " class='toch' id='$i' " . $heading_results[3][$i];
			$old_heading = $heading_results[0][$i];
			$link_list .= "<li class='sup toc-level-" . $heading_results[2][$i] . "'><a class='da fd' href='#$i' rel='external nofollow'>" . strip_tags( $heading_results[4][$i]) . "</a></li>";
		}

		if (!zm_get_option("toc_style") || (zm_get_option("toc_style") == "tocjq")) {
			$tocli = '<div class="toc-ul-box"><ul class="toc-ul tocjq"></ul></div>';
		}
		if (zm_get_option("toc_style") == "tocphp") {
			$tocli = '<div class="toc-ul-box"><ul class="toc-ul">' . $link_list . '</ul>';
		}

		$link_list = '<nav class="toc-box bk da fd yy">' . $tocli . '<span class="toc-zd bk dah yy"><span class="toc-close"><i class="be be-cross"></i><strong class="bgt">' . sprintf(__( '文章目录', 'begin' )) . '</strong></span></span></div></nav>';
		echo $link_list;
	}
}

// toc footer
function toc_footer() {
	be_toc();
}

if( function_exists( 'be_toc' ) ) {
	add_action( 'wp_footer', 'toc_footer' );
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
if (zm_get_option('copy_upset')) {
add_filter( 'the_content', 'copyright_disturb' );
function copyright_disturb( $content ) {
	ob_start(); 
	$new_content = ob_get_clean();
	if ( is_single() && ! is_admin() ) {
		return copyright_content( $new_content, 3, $content );
	}
	return $content;
}

function copyright_content( $new_content, $upsetp_id, $content ) {
	$upsetp = '</p>';
	$upset = explode( $upsetp, $content );
	foreach ($upset as $index => $paragraph) {
		if ( trim( $paragraph ) ) {
			$upset[$index] .= '<span class="beupset' . mt_rand(10, 100) . '">' . sprintf(__( '文章源自', 'begin' )) . '';
			$upset[$index] .= get_bloginfo( 'name' );
			$upset[$index] .= '-';
			$upset[$index] .= get_permalink();
			$upset[$index] .= '</span>';
		}
		if ( $upsetp_id == $index + 2 ) {
			$upset[$index] .= $new_content;
		}
	}
	return implode( '', $upset );
}
}
// 图片alt
if (zm_get_option('image_alt')) {
function img_alt($content) {
	global $post;
	preg_match_all('/<img (.*?)\/>/', $content, $images);
	if(!is_null($images)) {
		foreach($images[1] as $index => $value) {
			$new_img = str_replace('<img', '<img alt="'.get_the_title().'"', $images[0][$index]);
			$content = str_replace($images[0][$index], $new_img, $content);
		}
	}
	return $content;
}
add_filter('the_content', 'img_alt', 99999);
}

// 形式名称
function be_post_format( $safe_text ) {
	if ( $safe_text == '引语' )
		return '软件';
	if ( $safe_text == '相册' )
		return '宽图';
	return $safe_text;
}

// 点击最多文章
function get_timespan_most_viewed($mode = '', $limit = 10, $days = 7, $display = true) {
	global $wpdb, $post;
	$limit_date = current_time('timestamp') - ($days*86400);
	$limit_date = date("Y-m-d H:i:s",$limit_date);	
	$where = '';
	$temp = '';
	if(!empty($mode) && $mode != 'both') {
		$where = "post_type = '$mode'";
	} else {
		$where = '1=1';
	}
	$most_viewed = $wpdb->get_results("SELECT $wpdb->posts.*, (meta_value+0) AS views FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE post_date < '".current_time('mysql')."' AND post_date > '".$limit_date."' AND $where AND post_status = 'publish' AND meta_key = 'views' AND post_password = '' ORDER  BY views DESC LIMIT $limit");
	if($most_viewed) {
		$i = 1;
		foreach ($most_viewed as $post) {
			$post_title =  get_the_title();
			$post_views = intval($post->views);
			$post_views = number_format($post_views);
			$temp .= "<li class=\"srm\"><span class='li-icon li-icon-$i'>$i</span><a href=\"".get_permalink()."\">$post_title</a></li>";
			$i++;
		}
	} else {
		$temp = '<li>暂无文章</li>';
	}
	if($display) {
		echo $temp;
	} else {
		return $temp;
	}
}

// 热门文章
function get_timespan_most_viewed_img($mode = '', $limit = 10, $days = 7, $display = true) {
	global $wpdb, $post;
	$limit_date = current_time('timestamp') - ($days*86400);
	$limit_date = date("Y-m-d H:i:s",$limit_date);	
	$where = '';
	$temp = '';
	if(!empty($mode) && $mode != 'both') {
		$where = "post_type = '$mode'";
	} else {
		$where = '1=1';
	}
	$most_viewed = $wpdb->get_results("SELECT $wpdb->posts.*, (meta_value+0) AS views FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE post_date < '".current_time('mysql')."' AND post_date > '".$limit_date."' AND $where AND post_status = 'publish' AND meta_key = 'views' AND post_password = '' ORDER  BY views DESC LIMIT $limit");
	if($most_viewed) {
		foreach ($most_viewed as $post) {
			$post_title = get_the_title();
			$post_views = intval($post->views);
			$post_views = number_format($post_views);
			echo "<li>";
			echo "<span class='thumbnail'>";
			echo zm_thumbnail();
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
	if(!empty($mode) && $mode != 'both') {
		$where = "post_type = '$mode'";
	} else {
		$where = '1=1';
	}
	$most_viewed = $wpdb->get_results("SELECT $wpdb->posts.*, (meta_value+0) AS zm_like FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE post_date < '".current_time('mysql')."' AND post_date > '".$limit_date."' AND $where AND post_status = 'publish' AND meta_key = 'zm_like' AND post_password = '' ORDER  BY zm_like DESC LIMIT $limit");
	if($most_viewed) {
		$i = 1;

		foreach ($most_viewed as $post) {
			$post_title = get_the_title();
			$temp .= "<li><span class='li-icon li-icon-$i'>$i</span><a href=\"".get_permalink()."\">$post_title</a></li>";
			$i++;
		}
	} else {
		$temp = '<li>暂无文章</li>';
	}
	if($display) {
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
	if(!empty($mode) && $mode != 'both') {
		$where = "post_type = '$mode'";
	} else {
		$where = '1=1';
	}
	$most_viewed = $wpdb->get_results("SELECT $wpdb->posts.*, (meta_value+0) AS zm_like FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE post_date < '".current_time('mysql')."' AND post_date > '".$limit_date."' AND $where AND post_status = 'publish' AND meta_key = 'zm_like' AND post_password = '' ORDER  BY zm_like DESC LIMIT $limit");
	if($most_viewed) {
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
	$comment = preg_replace(array('#(http://([^\s]*)\.(jpg|gif|png|JPG|GIF|PNG))#','#(https://([^\s]*)\.(jpg|gif|png|JPG|GIF|PNG))#'),'<img src="$1" alt="评论" style="width:'.$size.'; height:'.$size.'" />', $comment);
	return $comment;
}

// connector
function connector() {
	if (zm_get_option('blank_connector')) {echo '';}else{echo ' ';}
	echo zm_get_option('connector');
	if (zm_get_option('blank_connector')) {echo '';}else{echo ' ';}
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
			if(!preg_match($pattern,$comment_data['comment_content'])) {
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
	if( @$comment->comment_parent > 0) {
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
				comments_template( '', true );
			endif;
		}
	} else {
	if ( comments_open() || get_comments_number() ) :
		comments_template( '', true );
	endif;
	}
}

// 浏览总数
function all_view(){
	global $wpdb;
	$count =  $wpdb->get_var("SELECT sum(meta_value) FROM $wpdb->postmeta WHERE meta_key='views'");
	return $count;
}

// 作者被浏览数
function author_posts_views($author_id = 1 ,$display = true) {
	global $wpdb;
	$sql = "SELECT SUM(meta_value+0) FROM $wpdb->posts left join $wpdb->postmeta on ($wpdb->posts.ID = $wpdb->postmeta.post_id) WHERE meta_key = 'views' AND post_author =$author_id";
	$comment_views = intval($wpdb->get_var($sql));
	if($display) {
		echo begin_postviews_round_number($comment_views);
	} else {
		return $comment_views;
	}
}

// 作者被点赞数
function like_posts_views($author_id = 1 ,$display = true) {
	global $wpdb;
	$sql = "SELECT SUM(meta_value+0) FROM $wpdb->posts left join $wpdb->postmeta on ($wpdb->posts.ID = $wpdb->postmeta.post_id) WHERE meta_key = 'zm_like' AND post_author =$author_id";
	$comment_like = intval($wpdb->get_var($sql));
	if($display) {
		echo begin_postviews_round_number($comment_like);
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

// 登录提示
function  zm_login_title() {
	return get_bloginfo('name');
}
if(get_bloginfo('version') >= 5.2 ) {
add_filter('login_headertext', 'zm_login_title');
} else {
add_filter('login_headertitle', 'zm_login_title');
}
// logo url
function custom_loginlogo_url($url) {
	return get_bloginfo('url');
}
add_filter( 'login_headerurl', 'custom_loginlogo_url' );

// 外链nofollow
if (zm_get_option('link_external')) {
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

// 评论者链接跳转
function comment_author_link_go($content){
	preg_match_all('/\shref=(\'|\")(http[^\'\"#]*?)(\'|\")([\s]?)/',$content,$matches);
	if($matches){
		foreach($matches[2] as $val){
			if(strpos($val,home_url())===false){
				$rep = $matches[1][0].$val.$matches[3][0];
				$go = '"'. $val .'" rel="external nofollow" target="_blank"';
				$content = str_replace("$rep","$go", $content);
			}
		}
	}
	return $content;
}

add_filter('comment_text','comment_author_link_go',99);
add_filter('get_comment_author_link','comment_author_link_go',99);

// 添加斜杠
function nice_trailingslashit($string, $type_of_url) {
	if ( $type_of_url != 'single' && $type_of_url != 'page' && $type_of_url != 'single_paged' )
		$string = trailingslashit($string);
	return $string;
}
if (zm_get_option('category_x')) {
	add_filter('user_trailingslashit', 'nice_trailingslashit', 10, 2);
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
			wp_link_pages(array('before' => '<div class="page-links turn-small">', 'after' => '', 'next_or_number' => 'next', 'previouspagelink' => '<span><i class="be be-arrowleft"></i></span>', 'nextpagelink' => ""));
		} else {
			wp_link_pages(array('before' => '<div class="page-links">', 'after' => '', 'next_or_number' => 'next', 'previouspagelink' => '<span><i class="be be-arrowleft"></i></span>', 'nextpagelink' => ""));
		}
		wp_link_pages(array('before' => '', 'after' => '', 'next_or_number' => 'number', 'link_before' =>'<span class="next-page">', 'link_after'=>'</span>'));
		wp_link_pages(array('before' => '', 'after' => '</div>', 'next_or_number' => 'next', 'previouspagelink' => '', 'nextpagelink' => '<span><i class="be be-arrowright"></i></span> '));
	}
}

function be_user_contact($user_contactmethods){
	unset($user_contactmethods['aim']);
	unset($user_contactmethods['yim']);
	unset($user_contactmethods['jabber']);
	$user_contactmethods['userimg'] = ''.sprintf(__( '图片', 'begin' )).'';
	$user_contactmethods['qq'] = 'QQ';
	$user_contactmethods['weixin'] = ''.sprintf(__( '微信', 'begin' )).'';
	$user_contactmethods['weibo'] = ''.sprintf(__( '微博', 'begin' )).'';
	$user_contactmethods['phone'] = ''.sprintf(__( '电话', 'begin' )).'';
	$user_contactmethods['remark'] = ''.sprintf(__( '备注', 'begin' )).'';
	return $user_contactmethods;
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
		$adminEmail = get_option('admin_email');if($comment_author_email ==$adminEmail) return;
		if($author_count>=0 && $author_count<2)
			echo '<a class="vip vip0" title="评论达人 VIP.0"><i class="be be-favoriteoutline"></i><span class="lv">0</span></a>';
		else if($author_count>=2 && $author_count<5)
			echo '<a class="vip vip1" title="评论达人 VIP.1"><i class="be be-favorite"></i><span class="lv">1</span></a>';
		else if($author_count>=5 && $author_count<10)
			echo '<a class="vip vip2" title="评论达人 VIP.2"><i class="be be-favorite"></i><span class="lv">2</span></a>';
		else if($author_count>=10 && $author_count<20)
			echo '<a class="vip vip3" title="评论达人 VIP.3"><i class="be be-favorite"></i><span class="lv">3</span></a>';
		else if($author_count>=20 && $author_count<50)
			echo '<a class="vip vip4" title="评论达人 VIP.4"><i class="be be-favorite"></i><span class="lv">4</span></a>';
		else if($author_count>=50 && $author_count<100)
			echo '<a class="vip vip5" title="评论达人 VIP.5"><i class="be be-favorite"></i><span class="lv">5</span></a>';
		else if($author_count>=100 && $author_count<200)
			echo '<a class="vip vip6" title="评论达人 VIP.6"><i class="be be-favorite"></i><span class="lv">6</span></a>';
		else if($author_count>=200 && $author_count<300)
			echo '<a class="vip vip7" title="评论达人 VIP.7"><i class="be be-favorite"></i><span class="lv">7</span></a>';
		else if($author_count>=300 && $author_count<400)
			echo '<a class="vip vip8" title="评论达人 VIP.8"><i class="be be-favorite"></i><span class="lv">8</span></a>';
		else if($author_count>=400)
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

if (zm_get_option('tag_c')) {
// 关键词加链接
$match_num_from = 1; //一个关键字少于多少不替换
$match_num_to = zm_get_option('chain_n');

add_filter('the_content','tag_link',1);

function tag_sort($a, $b){
	if ( $a->name == $b->name ) return 0;
	return ( strlen($a->name) > strlen($b->name) ) ? -1 : 1;
}

function tag_link($content){
global $match_num_from,$match_num_to;
$posttags = get_the_tags();
	if ($posttags) {
		usort($posttags, "tag_sort");
		foreach($posttags as $tag) {
			$link = get_tag_link($tag->term_id);
			$keyword = $tag->name;
			if (preg_match_all('|(<h[^>]+>)(.*?)'.$keyword.'(.*?)(</h[^>]*>)|U', $content, $matchs)) {continue;}
			if (preg_match_all('|(<a[^>]+>)(.*?)'.$keyword.'(.*?)(</a[^>]*>)|U', $content, $matchs)) {continue;}

			$cleankeyword = stripslashes($keyword);
			$url = "<a href=\"$link\" title=\"".str_replace('%s',addcslashes($cleankeyword, '$'),__('查看与 %s 相关的文章', 'begin' ))."\"";
			$url .= ' target="_blank"';
			$url .= ">".addcslashes($cleankeyword, '$')."</a>";
			$limit = rand($match_num_from,$match_num_to);
			global $ex_word;
			$case = "";
			$content = preg_replace( '|(<a[^>]+>)(.*)('.$ex_word.')(.*)(</a[^>]*>)|U'.$case, '$1$2%&&&&&%$4$5', $content);
			$content = preg_replace( '|(<img)(.*?)('.$ex_word.')(.*?)(>)|U'.$case, '$1$2%&&&&&%$4$5', $content);
			$cleankeyword = preg_quote($cleankeyword,'\'');
			$regEx = '\'(?!((<.*?)|(<a.*?)))('. $cleankeyword . ')(?!(([^<>]*?)>)|([^>]*?</a>))\'s' . $case;
			$content = preg_replace($regEx,$url,$content,$limit);
			$content = str_replace( '%&&&&&%', stripslashes($ex_word), $content);
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
	if(!$isSpam)
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

// 图片数量
if( !function_exists('get_post_images_number') ){
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
	$user_only = get_post_meta( $post->ID, 'user_only', true );
	if( $user_only ) {
		global $user_ID;
		if( !$user_ID ) {
			$redirect = urlencode( get_permalink( $post->ID ) );
			$text = '
				<div class="reply-read">
					<div class="reply-ts">
						<div class="read-sm"><i class="be be-info"></i>' . sprintf(__( '提示！', 'begin' ) ) . '</div>
						<div class="read-sm"><i class="be be-loader"></i>' . sprintf(__( '本文登录后方可查看！', 'begin' ) ) . '</div>
					</div>
					<div class="read-pl"><a href="#login" class="flatbtn show-layer" data-show-layer="login-layer" role="button"><i class="be be-timerauto"></i>' . sprintf(__( '登录', 'begin' ) ) . '</a></div>
					<div class="clear"></div>
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

if (zm_get_option('xmlrpc_no')) {
// 禁用xmlrpc
add_filter('xmlrpc_enabled', '__return_false');
}

// 禁止评论自动超链接
if (zm_get_option('comment_url')) {
remove_filter('comment_text', 'make_clickable', 9);
}
// 禁止评论HTML
if (zm_get_option('comment_html')) {
add_filter('comment_text', 'wp_filter_nohtml_kses');
add_filter('comment_text_rss', 'wp_filter_nohtml_kses');
add_filter('comment_excerpt', 'wp_filter_nohtml_kses');
}

// 链接管理
add_filter( 'pre_option_link_manager_enabled', '__return_true' );

// 显示全部设置
function all_settings_link() {
	add_options_page(__('All Settings'), __('All Settings'), 'administrator', 'options.php');
}
if (zm_get_option('all_settings')) {
add_action('admin_menu', 'all_settings_link');
}
// 屏蔽自带小工具
function remove_default_wp_widgets() {
	unregister_widget('WP_Widget_Recent_Comments');
	unregister_widget('WP_Widget_Recent_Posts');
	unregister_widget('WP_Widget_Meta');
	unregister_widget('WP_Widget_Media_Gallery');
	unregister_widget('WP_Widget_Categories');
	unregister_widget('WP_Widget_RSS');
	unregister_widget('WP_Widget_Pages');
}
add_action('widgets_init', 'remove_default_wp_widgets', 11);

// 禁用版本修订
if (zm_get_option('revisions_no')) {
	add_filter( 'wp_revisions_to_keep', 'disable_wp_revisions_to_keep', 10, 2 );
}
function disable_wp_revisions_to_keep( $num, $post ) {
	return 0;
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
if (zm_get_option('disable_api')) {
	add_filter('rest_enabled', '_return_false');
	add_filter('rest_jsonp_enabled', '_return_false');
}

// 移除wp-json链接
remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );

// 替换用户链接
if (!zm_get_option('my_author') || (zm_get_option('my_author') == 'author_link')) {
	add_filter( 'request', 'my_author' );
	function my_author( $query_vars ) {
		if ( array_key_exists( 'author_name', $query_vars ) ) {
			global $wpdb;
			$author_id = $wpdb->get_var( $wpdb->prepare( "SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key='first_name' AND meta_value = %s", $query_vars['author_name'] ) );
			if ( $author_id ) {
				$query_vars['author'] = $author_id;
				unset( $query_vars['author_name'] );
			}
		}
		return $query_vars;
	}

	add_filter( 'author_link', 'my_author_link', 10, 3 );
	function my_author_link( $link, $author_id, $author_nicename ) {
		$my_name = get_user_meta( $author_id, 'first_name', true );
		if ( $my_name ) {
			$link = str_replace( $author_nicename, $my_name, $link );
		}
		return $link;
	}
}

if (zm_get_option('my_author') == 'author_id') {
	add_filter( 'author_link', 'author_id', 10, 2 );
	function author_id( $link, $author_id) {
		global $wp_rewrite;
		$author_id = (int) $author_id;
		$link = $wp_rewrite->get_author_permastruct();
		if ( empty($link) ) {
			$file = home_url( '/' );
			$link = $file . '?author=' . $author_id;
		} else {
			$link = str_replace('%author%', $author_id, $link);
			$link = home_url( user_trailingslashit( $link ) );
		}
		return $link;
	}

	add_filter( 'request', 'my_author' );
	function my_author( $query_vars ) {
		if ( array_key_exists( 'author_name', $query_vars ) ) {
			global $wpdb;
			$author_id=$query_vars['author_name'];
			if ( $author_id ) {
				$query_vars['author'] = $author_id;
				unset( $query_vars['author_name'] );
			}
		}
		return $query_vars;
	}
}

// 屏蔽用户名称类
function remove_comment_body_author_class( $classes ) {
	foreach( $classes as $key => $class ) {
	if(strstr($class, "comment-author-")||strstr($class, "author-")) {
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
function recently_updated_posts($num=10,$days=7) {
	if( !$recently_updated_posts = get_option('recently_updated_posts') ) {
		query_posts('post_status=publish&orderby=modified&posts_per_page=-1');
		$i=0;
		while ( have_posts() && $i<$num ) : the_post();
			if (current_time('timestamp') - get_the_time('U') > 60*60*24*$days) {
				$i++;
				$the_title_value=get_the_title();
				$recently_updated_posts.='<li class="srm"><a href="'.get_permalink().'" title="'.$the_title_value.'">'
				.$the_title_value.'</a></li>';
			}
		endwhile;
		wp_reset_query();
		if ( !empty($recently_updated_posts) ) update_option('recently_updated_posts', $recently_updated_posts);
	}
	$recently_updated_posts=($recently_updated_posts == '') ? '<li>目前没有文章被更新</li>' : $recently_updated_posts;
	echo $recently_updated_posts;
}

function clear_cache_recently() {
	update_option('recently_updated_posts', '');
}
add_action('save_post', 'clear_cache_recently');

// code button
if (zm_get_option('be_code')) {require get_template_directory() . '/inc/tinymce/code-button.php';}

// shortcode
require get_template_directory() . '/inc/be-shortcode.php';

// 注册时间
function user_registered(){
	$userinfo=get_userdata(get_current_user_id());
	$authorID= $userinfo->ID;
	$user = get_userdata( $authorID );
	$registered = $user->user_registered;
	echo '' . date( "" . sprintf(__( 'Y年m月d日', 'begin' )) . "", strtotime( $registered ) );
}

// 文章归档更新
function be_archives() {
	update_option('be_archives_list', '');
}

if (zm_get_option('update_be_archives')) {
	add_action( 'optionsframework_after_validate', 'be_archives');
}

function be_up_archives() {
	update_option('up_archives_list', '');
}

if (zm_get_option('update_up_archives')) {
	add_action( 'optionsframework_after_validate', 'be_up_archives');
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
	if ( is_admin() && is_user_logged_in() && !current_user_can( 'publish_pages' ) && !current_user_can( 'manage_options' ) && ( !defined( 'DOING_AJAX' ) || !DOING_AJAX )  ){
		wp_safe_redirect( $url );
		exit;
	}
}

// 读者排行
function top_comment_authors($amount = 98) {
	global $wpdb;
	$prepared_statement = $wpdb->prepare(
	'SELECT
	COUNT(comment_author) AS comments_count, comment_author, comment_author_url, comment_author_email, MAX( comment_date ) as last_commented_date
	FROM '.$wpdb->comments.'
	WHERE comment_author != "" AND comment_type not in ("trackback","pingback") AND comment_approved = 1  AND user_id = ""
	GROUP BY comment_author
	ORDER BY comments_count DESC, comment_author ASC
	LIMIT %d',
	$amount);
	$results = $wpdb->get_results($prepared_statement);
	$output = '<div class="top-comments">';
	foreach($results as $result) {
		$c_url = $result->comment_author_url;
		$output .= '<div class="lx8"><div class="top-author ms bk da load">';
			if (zm_get_option('cache_avatar')) {
				$output .= '<div class="top-comment"><a href="' . $c_url . '" target="_blank" rel="external nofollow">' . begin_avatar($result->comment_author_email, 96, '', $result->comment_author) . '<div class="author-url"><strong> ' . $result->comment_author . '</div></strong></a></div>';
			} else {
				if ( !zm_get_option( 'avatar_load' ) ) {
					$output .= '<div class="top-comment"><a href="' . $c_url . '" target="_blank" rel="external nofollow">' . get_avatar($result->comment_author_email, 96, '', $result->comment_author) . '<div class="author-url"><strong> ' . $result->comment_author . '</div></strong></a></div>';
				} else {
					$output .= '<div class="top-comment"><a href="' . $c_url . '" target="_blank" rel="external nofollow"><img class="avatar photo" src="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=" alt="'. get_the_author() .'" width="96" height="96" data-original="' . preg_replace(array('/^.+(src=)(\"|\')/i', '/(\"|\')\sclass=(\"|\').+$/i'), array('', ''), get_avatar($result->comment_author_email, 96, '', $result->comment_author)) . '" /><div class="author-url"><strong> ' . $result->comment_author . '</div></strong></a></div>';
				}
			}
		$output .= '<div class="top-comment">'.$result->comments_count.'条留言</div><div class="top-comment">' . human_time_diff(strtotime($result->last_commented_date)) . '前</div></div></div>';
	}
	$output .= '<div class="clear"></div></div>';
	echo $output;
}

// 评论列表
function get_comment_authors_list( $id = null ) {
	$post_id = $id ? $id : get_the_ID();
	if ( $post_id ) {
		$comments = get_comments( array(
			'post_id' => $post_id,
			'status'  => 'approve',
			'order' => 'ASC',
			'author__not_in' => get_the_author_meta('ID'),
			'type'    => 'comment',
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
			'number' => '1',
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
	global $wpdb; 
	if( !wp_get_post_revisions( $post_ID ) ) {
		if( 'sites_link' == !get_post_meta( get_the_ID(), 'sites_link', false ) ) {
			$meta_tags = '';
		} else {
			if (zm_get_option('sites_url_error')) {
				$meta_tags = '';
			} else {
				$meta_tags = get_meta_tags( get_post_meta( get_the_ID(), 'sites_link', true ) );
			}
		}
		if( 'sites_url' == !get_post_meta( get_the_ID(), 'sites_url', false ) ) {
			if ( isset( $meta_tags['description'] ) ) {
				$metas = $meta_tags['description'];
				add_post_meta( $post_ID, 'sites_description', $metas, true );
			}
		}
	}
}

// 分类ID
function show_id() {
	$categories = get_categories(array('taxonomy' => array('category'), 'hide_empty' => 0)); 
	foreach ($categories as $cat) {
		$output = '<ol class="show-id">' . $cat->cat_name . '<span>' . $cat->cat_ID . '</span></ol>';
		echo $output;
	}
}

function type_show_id() {
	$categories = get_categories(array('taxonomy' => array('taobao', 'gallery', 'videos', 'products', 'notice', 'favorites'), 'hide_empty' => 0)); 
	foreach ($categories as $cat) {
		$output = '<ol class="show-id">' . $cat->cat_name . '<span>' . $cat->cat_ID . '</span></ol>';
		echo $output;
	}
	$categories = get_categories(array('taxonomy' => array('product_cat'), 'hide_empty' => 0)); 
	foreach ($categories as $cat) {
		$output = '<ol class="show-id">' . $cat->cat_name . '<span>' . $cat->cat_ID . '</span></ol>';
		echo $output;
	}
}

// 专题ID
function special_show_id() {
	$special_id = '';
	$options_pages_obj = get_pages( array( 'meta_key' => 'special' ) );
	foreach ($options_pages_obj as $page) {
	$special_id .= '<li>'.$page->post_title.' [ '.$page->ID.' ]</li>';
		$output = '<ol class="show-id">' . $page->post_title . '<span>' . $page->ID . '</span></ol>';
		echo $output;
	}
}

function search_cat(){
	$categories = get_categories();
	foreach ($categories as $cat) {
	$output = '<option value="'.$cat->cat_ID.'">'.$cat->cat_name.'</option>';
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
	if( $histtory_post ){
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
if (zm_get_option('menu_des')) {
add_filter( 'walker_nav_menu_start_el', 'begin_nav_description', 10, 4 );
}

// custum font
function custum_font_family($initArray){
   $initArray['font_formats'] = "微软雅黑='微软雅黑';华文彩云='华文彩云';华文行楷='华文行楷';华文琥珀='华文琥珀';华文新魏='华文新魏';华文中宋='华文中宋';华文仿宋='华文仿宋';华文楷体='华文楷体';华文隶书='华文隶书';华文细黑='华文细黑';宋体='宋体';仿宋='仿宋';黑体='黑体';隶书='隶书';幼圆='幼圆'";
   return $initArray;
}

// 删除文章菜单
function be_remove_menus(){
	remove_menu_page( 'edit.php?post_type=bulletin' );
	remove_menu_page( 'edit.php?post_type=picture' );
	remove_menu_page( 'edit.php?post_type=video' );
	remove_menu_page( 'edit.php?post_type=tao' );
	remove_menu_page( 'edit.php?post_type=sites' );
	remove_menu_page( 'edit.php?post_type=show' );
	remove_menu_page( 'link-manager.php' );
	remove_menu_page( 'upload.php' );
	remove_menu_page( 'edit-comments.php' );
	remove_menu_page( 'tools.php' );
	remove_menu_page( 'edit.php?post_type=surl' );
}

function disable_create_newpost() {
	global $wp_post_types;
if (zm_get_option('no_bulletin')) {
	$wp_post_types['bulletin']->cap->create_posts = 'do_not_allow';
}
if (zm_get_option('no_gallery')) {
	$wp_post_types['picture']->cap->create_posts = 'do_not_allow';
}
if (zm_get_option('no_videos')) {
	$wp_post_types['video']->cap->create_posts = 'do_not_allow';
}
if (zm_get_option('no_tao')) {
	$wp_post_types['tao']->cap->create_posts = 'do_not_allow';
}
if (zm_get_option('no_favorites')) {
	$wp_post_types['sites']->cap->create_posts = 'do_not_allow';
}
if (zm_get_option('no_products')) {
	$wp_post_types['show']->cap->create_posts = 'do_not_allow';
}
}

if (zm_get_option('no_type')) {
	if ($current_user->user_level < zm_get_option('user_level')) { // 作者及投稿者不可见
		add_action( 'admin_menu', 'be_remove_menus' );
		add_action('init','disable_create_newpost');
	}
}

function be_remove_separator() {
	global $menu;
	unset($menu[4]);
	unset($menu[59]);
}
if (zm_get_option('remove_separator')) {
	add_action('admin_head', 'be_remove_separator');
}
// 复制提示
function zm_copyright_tips() {
	echo '<script>document.body.oncopy=function(){alert("复制成功！转载请务必保留原文链接，申明来源，谢谢合作！");}</script>';
}

// sitemap_xml
if (zm_get_option('sitemap_xml')) {
	function begin_sitemap_refresh() {
		require_once get_template_directory() . '/inc/sitemap-xml.php';
		$sitemap_xml = begin_get_xml_sitemap();
		file_put_contents(ABSPATH.'sitemap.xml', $sitemap_xml);
	}
	add_action( 'optionsframework_after_validate', 'begin_sitemap_refresh' );
}
// sitemap_txt
if (zm_get_option('sitemap_txt')) {
	function begin_sitemap_refresh_txt() {
		require_once get_template_directory() . '/inc/sitemap-txt.php';
		$sitemap_txt = begin_get_txt_sitemap();
		file_put_contents(ABSPATH.'sitemap.txt', $sitemap_txt);
	}
add_action( 'optionsframework_after_validate', 'begin_sitemap_refresh_txt' );
}

// ajax content
function ajax_content(){
	$data = $_POST['data'];
	$return = array();
	if( is_array( $data ) ){
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
	return $tag->count;
}

// 标签别名获取ID
function get_tag_id_slug($tag_slug) {
	$tag = get_term_by( 'slug', $tag_slug, 'post_tag' );
	if ($tag) return $tag->term_id;
	return 0;
}

// 上传头像
if (zm_get_option('local_avatars')) {
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
	$columns['reg_time'] = '注册';
	$columns['last_login'] = '登录';
	return $columns;
}

add_action('manage_users_custom_column',  'show_user_additional_column_content', 10, 3);
function show_user_additional_column_content($value, $column_name, $user_id) {
	$user = get_userdata( $user_id );
	if ( 'user_nickname' == $column_name )
		return $user->nickname;
	if ( 'user_url' == $column_name )
		return '<a href="'.$user->user_url.'" target="_blank">'.$user->user_url.'</a>';
	if('reg_time' == $column_name ){
		return get_date_from_gmt($user->user_registered) ;
	}
	if ( 'last_login' == $column_name && $user->last_login ){
		return get_user_meta( $user->ID, 'last_login', true );
	} else {
		return '暂无记录';
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
	if(!isset($_REQUEST['orderby']) || $_REQUEST['orderby']=='reg_time' ){
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
	if( !isset( $_REQUEST['orderby']) || $_REQUEST['orderby']=='last_login' ){
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
<option value=""><?php _e('自定义字段', 'begin'); ?></option>
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
</select> <?php _e('值', 'begin'); ?> <input type="TEXT" name="BE_FILTER_VALUE" value="<?php echo $current_v; ?>" />
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
	wp_enqueue_script( 'movepost', get_stylesheet_directory_uri() . '/js/movepost.js', array( 'jquery' ) ); 
}

add_action( 'wp_ajax_moveposttotrash', function() {
	check_ajax_referer( 'trash-post_' . $_POST['post_id'] );
	wp_trash_post( $_POST['post_id'] );
	die();
});
}

// 更多菜单
function add_main_nav_more ( $items, $args ) {
	if( 'navigation' === $args -> theme_location ) {
		$items .= '<li class="nav-more"><span class="nav-more-i"><i class="be be-more"></i></span>';
		$items .= '<ul class="menu-more-li"></ul>';
		$items .= '</li>';
	}
	return $items;
}
if (zm_get_option('nav_more') && !wp_is_mobile()) {
	add_filter('wp_nav_menu_items', 'add_main_nav_more', 10, 2);
}

function add_top_nav_more ( $items, $args ) {
	if( 'header' === $args -> theme_location ) {
		$items .= '<li class="nav-more"><span class="nav-more-i"><i class="be be-more"></i></span>';
		$items .= '<ul class="menu-more-li"></ul>';
		$items .= '</li>';
	}
	return $items;
}
if (zm_get_option('top_nav_more') && !wp_is_mobile()) {
	add_filter('wp_nav_menu_items', 'add_top_nav_more', 10, 2);
}

// 注册后登录
function register_auto_login( $user_id ) {
	wp_set_current_user($user_id);
	wp_set_auth_cookie($user_id);
	wp_redirect( home_url() );
	exit;
}
if (zm_get_option('register_auto') && zm_get_option('go_reg') && !zm_get_option('be_social_login')) {
add_action( 'user_register', 'register_auto_login');
}
// 退出后跳转
function logout_redirect_to() {
	wp_redirect(''.zm_get_option('logout_to').'');
	 exit();
}
if (zm_get_option('logout_to')) {
add_action('wp_logout', 'logout_redirect_to');
}
// 隐藏标题WP
add_filter('admin_title', 'zm_custom_admin_title', 10, 2);
	function zm_custom_admin_title($admin_title, $title){
		return $title.' &lsaquo; '.get_bloginfo('name');
}
add_filter('login_title', 'zm_custom_login_title', 10, 2);
	function zm_custom_login_title($login_title, $title){
		return $title.' &lsaquo; '.get_bloginfo('name');
}
// 隐藏WP标志
function hidden_admin_bar_remove() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('wp-logo');
}
add_action('wp_before_admin_bar_render', 'hidden_admin_bar_remove', 0);

// 移除隐私功能
add_action('admin_menu', function () {
	global $menu, $submenu;
	unset($submenu['options-general.php'][45]);
	remove_action( 'admin_menu', '_wp_privacy_hook_requests_page' );
},9);

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
if ( zm_get_option( 'group_nav' ) ) {
		$classes[] ='group-site group-nav';
	} else {
		$classes[] ='group-site';
	}
	return $classes;
}

// qq info
if (zm_get_option('qq_info')) {
function generate_code($length = 3) {
	return rand(pow(10,($length-1)), pow(10,$length)-1);
}
}
// 获取首字母
function getFirstCharter($str){
	if(empty($str)){
		return '';
	}
	if(is_numeric($str[0])) return $str[0];
	$fchar=ord($str[0]);
	if($fchar>=ord('A')&&$fchar<=ord('z')) return strtoupper($str[0]);
	$s1=iconv('UTF-8','gb2312',$str);
	$s2=iconv('gb2312','UTF-8',$s1);
	$s=$s2==$str?$s1:$str;
	$asc=ord($s[0])*256+ord($s[1])-65536;
	if($asc>=-20319&&$asc<=-20284) return 'A';
	if($asc>=-20283&&$asc<=-19776) return 'B';
	if($asc>=-19775&&$asc<=-19219) return 'C';
	if($asc>=-19218&&$asc<=-18711) return 'D';
	if($asc>=-18710&&$asc<=-18527) return 'E';
	if($asc>=-18526&&$asc<=-18240) return 'F';
	if($asc>=-18239&&$asc<=-17923) return 'G';
	if($asc>=-17922&&$asc<=-17418) return 'H';
	if($asc>=-17417&&$asc<=-16475) return 'J';
	if($asc>=-16474&&$asc<=-16213) return 'K';
	if($asc>=-16212&&$asc<=-15641) return 'L';
	if($asc>=-15640&&$asc<=-15166) return 'M';
	if($asc>=-15165&&$asc<=-14923) return 'N';
	if($asc>=-14922&&$asc<=-14915) return 'O';
	if($asc>=-14914&&$asc<=-14631) return 'P';
	if($asc>=-14630&&$asc<=-14150) return 'Q';
	if($asc>=-14149&&$asc<=-14091) return 'R';
	if($asc>=-14090&&$asc<=-13319) return 'S';
	if($asc>=-13318&&$asc<=-12839) return 'T';
	if($asc>=-12838&&$asc<=-12557) return 'W';
	if($asc>=-12556&&$asc<=-11848) return 'X';
	if($asc>=-11847&&$asc<=-11056) return 'Y';
	if($asc>=-11055&&$asc<=-10247) return 'Z';
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

// change posts count
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

// 评论链接
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

if (zm_get_option('web_queries')) {
function queries( $visible = false ) {
	$stat = sprintf(  '%d 次查询 耗时 %.3f 秒, 使用 %.2fMB 内存',
	get_num_queries(),
	timer_stop( 0, 3 ),
	memory_get_peak_usage() / 1024 / 1024
	);
	echo $visible ? $stat : "<!-- {$stat} -->" ;
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

// widget class
if (zm_get_option('widget_class')) {
	if (is_admin()){
		add_filter( 'in_widget_form', 'be_class_widget_form', 10, 3 );
	}

	function be_class_widget_form( $widget, $return, $instance ){
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
}

// widgets title span
function title_i_w() {
	if (zm_get_option('title_i')) {
	return '<span class="title-i"><span></span><span></span><span></span><span></span></span>';
	} else {
	return '<span class="title-w"></span>';
	}
}

// Clone Widgets
if (zm_get_option('clone_widgets')) {
	add_filter('admin_head', 'be_clone_widgets_script');
	function be_clone_widgets_script() {
		global $pagenow;
		if ($pagenow != 'widgets.php')
		return;
		wp_enqueue_script( 'clone_widgets', get_template_directory_uri() . '/js/clone-widgets.js', array( 'jquery' ), false, true );
		wp_localize_script('clone_widgets', 'be_clone_widgets', array(
			'text' => __('复制', 'begin'),
			'title' => __('复制小工具', 'begin')
		));
	}
}

if (zm_get_option('home_paged_ban') ) {
// home redirect pagination
function redirect_home_pagination () {
	global $paged, $page;
	if ( is_front_page() && is_home() && ( $paged >= 2 || $page >= 2 ) ) {
		wp_redirect( home_url() , '301' );
		die;
	}
}
if (zm_get_option('layout') == 'grid' || zm_get_option('layout') == 'cms' || zm_get_option('layout') == 'group') {
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

// Media Libary  Display SVG
function be_display_svg_media($response, $attachment, $meta){
	if($response['type'] === 'image' && $response['subtype'] === 'svg+xml' && class_exists('SimpleXMLElement')){
		try {
			$path = get_attached_file($attachment->ID);
			if(@file_exists($path)){
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
	$role = 'contributor';
	if (!zm_get_option('user_upload') || (zm_get_option('user_upload') == 'removecap')) {
		$role = get_role($role);
		$role->remove_cap('upload_files');
	}

	if (zm_get_option('user_upload') == 'addcap') {
		$role = get_role($role);
		$role->add_cap('upload_files');
	}
}
add_action( 'admin_init', 'user_upload_files');

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
			[ 'jquery-migrate' ]
		);
	}
}
if (zm_get_option('remove_jqmigrate')) {
add_action( 'wp_default_scripts', 'remove_jquery_migrate' );
}
// delete_favorite
function delete_favorite_table(){
	global $wpdb;
	$table = $wpdb->prefix . 'be_favorite';
	$sql = "DROP TABLE IF EXISTS $table";
	$wpdb->query($sql);
}

if (zm_get_option('delete_favorite')) {
	delete_favorite_table();
}

// Night Mode
function be_dark_mode() { ?>
	<script>
		if (localStorage.getItem('beNightMode')) {
			document.body.className +=' night';
		}
	</script>
	<?php
}

if (zm_get_option('read_night') && get_bloginfo('version') <= 5.2) {
// wp_body_open
if ( ! function_exists( 'wp_body_open' ) ) {
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}
}

if (zm_get_option('be_safety')) {
	global $user_ID; if( $user_ID ) {
		if( !current_user_can( 'administrator' ) ) {
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

// 回复replytocom
add_filter('comment_reply_link', 'del_replytocom', 420, 4);
function del_replytocom($link, $args, $comment, $post){
	return preg_replace( '/href=\'(.*(\?|&)replytocom=(\d+)#respond)/', 'href=\'#comment-$3', $link );
}

// 登录震动
function wps_login_error() {
	remove_action('login_head', 'wp_shake_js', 12);
}
add_action('login_head', 'wps_login_error');

// 禁用响应图片
add_filter( 'wp_calculate_image_srcset_meta', '__return_false' );

// add_new_user_role
function add_new_user_role() {
add_role('vip_roles', zm_get_option('roles_name'), array(
		'read' => true,
		'edit_posts' => false,
		'delete_posts' => false,
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
// 修改登录链接
function login_protect(){
	if($_GET[''.zm_get_option('pass_h').''] != ''.zm_get_option('word_q').'')header('Location: '.zm_get_option('go_link').'');// 忘了删除
}

// 重定向登录
function be_redirect_login() {
	global $pagenow;
	$action = (isset($_GET['action'])) ? $_GET['action'] : '';
	if( $pagenow == 'wp-login.php' && ( ! $action || ( $action && ! in_array($action, array('logout', 'lostpassword', 'rp', 'resetpass'))))) {
		$page = zm_get_option('redirect_login_link');
		wp_redirect($page);
		exit();
	}
}

define('ZM_IMAGE_PLACEHOLDER', "data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=");