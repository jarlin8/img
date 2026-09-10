<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php 
	$sticky = be_get_option( 'news_grid_sticky' ) ? '0' : '1';
	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$notcat = be_get_option( 'not_news_n' ) ? implode( ',', be_get_option( 'not_news_n') ) : '';
	$top_id = be_get_option( 'cms_top' ) ? explode( ',', be_get_option( 'cms_top_id' ) ) : '';

	$args = array(
		'posts_per_page'      => be_get_option( 'news_n' ),
		'category__not_in'    => explode( ',', $notcat ),
		'post__not_in'        => $top_id,
		'ignore_sticky_posts' => $sticky,
		'paged'               => $paged
	);

	$recent = new WP_Query($args);
?>
<div class="cms-news-normal-box betip<?php if ( zm_get_option( 'post_no_margin' ) ) { ?> cms-news-normal<?php } ?>">
	<?php global $count; while( $recent->have_posts() ) : $recent->the_post(); $count++; $do_not_duplicate[] = $post->ID; ?>
		<?php get_template_part( 'template/content', get_post_format() ); ?>
		<?php if ( $count == 1 ) : ?>
			<?php if ( be_get_option( 'cms_new_post_img' ) ) { ?>
				<div class="line-four betip" <?php aos_a(); ?>>
					<?php require get_template_directory() . '/cms/cms-post-img.php'; ?>
				</div>
			<?php } ?>
		<?php endif; ?>
	<?php endwhile; ?>
	<?php wp_reset_postdata(); ?>
	<?php cms_help( $text = '首页设置 → 杂志布局 → 最新文章', $number = 'news_s' ); ?>
</div>
<?php if ( be_get_option( 'post_no_margin' ) || be_get_option( 'news_model' ) ) { ?><div class="domargin"></div><?php } ?>
<div class="clear"></div>