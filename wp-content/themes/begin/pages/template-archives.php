<?php
/*
Template Name: 文章归档
*/
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php get_header(); ?>

<style type="text/css">
#primary {
	width: 100%;
}

.archives-meta {
	margin: 20px 0;
}
.year {
	font-size: 16px;
	margin: 10px -20px 10px -21px;
	padding: 0 20px;
	border-bottom: 1px solid #ebebeb;
	border-left: 5px solid #0088cc;
}
.mon {
	font-weight: 700;
	line-height: 30px; 
	margin: 5px 0 5px 5px;
	cursor: pointer;
}
.post_list li {
	line-height: 230%;
	padding: 0 0 0 50px;
}
.post_list {
	color: #999;
	margin: 0 0 10px 0;
}
.mon-num {
	font-size: 14px;
	color: #999;
	font-weight: 400;
	margin: 0 0 0 10px;
}
.night .year {
	border-bottom: 1px solid #262626 !important;
}
.night .mon {
	color: #808080 !important;
}
</style>
<?php
// 文章归档
if ( ! defined( 'ABSPATH' ) ) exit;
function be_archives_list() {
	if ( !$output = get_option('be_archives_list') ){
		$output = '<div id="all_archives">';
        $the_query = new WP_Query( 'posts_per_page=-1&ignore_sticky_posts=1' );
		$year=0; $mon=0; $i=0; $j=0;
		while ( $the_query->have_posts() ) : $the_query->the_post();
			$year_tmp = get_the_time('Y');
			$mon_tmp = get_the_time('m');
			$y=$year; $m=$mon;
			if ($mon != $mon_tmp && $mon > 0) $output .= '</ul></li>';
			if ($year != $year_tmp && $year > 0) $output .= '</ul>';
			if ($year != $year_tmp) {
				$year = $year_tmp;
				$output .= '<h3 class="year">'. $year .' 年</h3><ul class="mon_list">';
			}
			if ($mon != $mon_tmp) {
				$mon = $mon_tmp;
				$output .= '<li><span class="mon">'. $mon .'月</span><ul class="post_list">';
			}
			$output .= '<li><time datetime="'.get_the_date('Y-m-d').' ' . get_the_time('H:i:s').'">'. get_the_time('d日 ') .'</time><a href="'. get_permalink() .'">'. get_the_title() .'</a>';
		endwhile;
		wp_reset_postdata();
		$output .= '</ul></li></ul></div>';
		update_option('be_archives_list', $output);
	}
	echo $output;
}
?>
	<div id="primary" class="content-area">
		<main id="main" class="be-main site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class('bk da'); ?>>

				<?php if ( get_post_meta(get_the_ID(), 'header_img', true) || get_post_meta(get_the_ID(), 'header_bg', true) ) { ?>
				<?php } else { ?>
					<header class="entry-header">
						<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
					</header><!-- .entry-header -->
				<?php } ?>

				<div class="archives-meta">
					站点统计：<?php echo $count_categories = wp_count_terms('category'); ?>个分类&nbsp;&nbsp;
					<?php echo $count_tags = wp_count_terms('post_tag'); ?>个标签&nbsp;&nbsp;
					<?php $count_posts = wp_count_posts(); echo $published_posts = $count_posts->publish;?> 篇文章&nbsp;&nbsp;
					<?php $my_email = get_bloginfo ( 'admin_email' ); echo $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments where comment_author_email!='$my_email'");?>条留言&nbsp;&nbsp;
					浏览量：<?php echo all_view(); ?>&nbsp;&nbsp;
					最后更新：<?php $last = $wpdb->get_results("SELECT MAX(post_modified) AS MAX_m FROM $wpdb->posts WHERE (post_type = 'post' OR post_type = 'page') AND (post_status = 'publish' OR post_status = 'private')");$last = date('Y年n月j日', strtotime($last[0]->MAX_m));echo $last; ?>
				</div>

					<div class="archives"><?php be_archives_list(); ?></div>

				</article><!-- #page -->

			<?php endwhile;?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<script type="text/javascript">
jQuery(document).ready(function($){
	$('#all_archives span.mon').each(function(){
		var num=$(this).next().children('li').size();
		var text=$(this).text();
		$(this).html(text+' <span class="mon-num">'+num+' 篇</span>');
	});
	var $al_post_list=$('#all_archives ul.post_list'),
		$al_post_list_f=$('#all_archives ul.post_list:first');
	$al_post_list.hide(1,function(){
		$al_post_list_f.show();
	});
	$('#all_archives span.mon').click(function(){
		$(this).next().slideToggle(400);
		return false;
	});
 });
</script>
<?php get_footer(); ?>