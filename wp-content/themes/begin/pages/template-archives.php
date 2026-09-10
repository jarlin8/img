<?php
/*
Template Name: 文章归档
*/
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php get_header(); ?>

<?php
if ( ! defined( 'ABSPATH' ) ) exit;
function be_archives_list() {
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
			$output .= '<h3 class="beyear">'. $year .' 年</h3><ul class="mon_list">';
		}
		if ($mon != $mon_tmp) {
			$mon = $mon_tmp;
			$output .= '<li><span class="bemon">'. $mon .'月</span><ul class="post_list">';
		}
		$output .= '<li><time datetime="'.get_the_date('Y-m-d').' ' . get_the_time('H:i:s').'">'. get_the_time('d日 ') .'</time><a href="'. get_permalink() .'">'. get_the_title() .'</a>';
	endwhile;
	wp_reset_postdata();
	$output .= '</ul></li></ul></div>';
	echo $output;
}
?>
<div class="archives-area">
	<main id="main" class="be-main site-main" role="main">
		<?php while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" class="post-item post ms" <?php aos_a(); ?>>
				<?php if ( get_post_meta( get_the_ID(), 'header_img', true ) || get_post_meta( get_the_ID(), 'header_bg', true ) ) { ?>
				<?php } else { ?>
					<header class="entry-header">
						<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
					</header><!-- .entry-header -->
				<?php } ?>

				<div class="archives-meta">
					<span><?php echo $count_categories = wp_count_terms('category'); ?></span>个分类
					<span><?php echo $count_tags = wp_count_terms('post_tag'); ?></span>个标签
					<span><?php $count_posts = wp_count_posts(); echo $published_posts = $count_posts->publish;?></span>篇文章
					<span><?php $my_email = get_bloginfo ( 'admin_email' ); echo $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments where comment_author_email!='$my_email'");?></span>条留言
					<span><?php echo all_view(); ?></span>浏览
					<span><?php $last = $wpdb->get_results("SELECT MAX(post_modified) AS MAX_m FROM $wpdb->posts WHERE (post_type = 'post' OR post_type = 'page') AND (post_status = 'publish' OR post_status = 'private')");$last = date('Y年n月j日', strtotime($last[0]->MAX_m));echo $last; ?></span>更新
				</div>
				<div class="clear"></div>
				<div class="archives"><?php be_archives_list(); ?></div>
			</article>
		<?php endwhile;?>

	</main>
</div>

<script type="text/javascript">
jQuery(document).ready(function($){
	$('#all_archives span.bemon').each(function(){
		var num=$(this).next().children('li').size();
		var text=$(this).text();
		$(this).html(text+' <span class="mon-num">'+num+' 篇</span>');
	});
	var $al_post_list=$('#all_archives ul.post_list'),
		$al_post_list_f=$('#all_archives ul.post_list:first');
	$al_post_list.hide(1,function(){
		$al_post_list_f.show();
	});
	$('#all_archives span.bemon').click(function(){
		$(this).next().slideToggle(400);
		return false;
	});
 });
</script>
<?php get_footer(); ?>