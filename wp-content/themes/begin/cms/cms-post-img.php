<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php
	$args = get_posts( array(
		'post__in'            => explode( ',', be_get_option( 'cms_new_post_img_id' ) ),
		'orderby'             => 'post__in', 
		'ignore_sticky_posts' => true,
		'post__not_in'        => $do_not_duplicate,
	) );
?>
<?php if ( $args ) : foreach ( $args as $post ) : setup_postdata( $post ); ?>
<div class="xl4 xm4">
	<div class="picture-cms picture-cms-img-item ms<?php if ( be_get_option( 'post_no_margin' ) && be_get_option( 'news_model' ) == 'news_normal' ) { ?> addclose<?php } ?>" <?php aos_a(); ?>>
		<figure class="picture-cms-img">
			<?php echo zm_thumbnail(); ?>
			<div class="posting-title over"><a href="<?php the_permalink() ?>" rel="bookmark" <?php echo goal(); ?>><?php the_title(); ?></a></div>
		</figure>
	</div>
</div>
<?php endforeach; wp_reset_postdata(); ?>
<?php else : ?>
	<div class="be-none-img ms">首页设置 → 杂志布局 → 最新文章 → 图文模块，输入文章ID</div>
<?php endif; ?>
<?php be_help( $text = '首页设置 → 杂志布局 → 最新文章 → 图文模块' ); ?>
<div class="clear"></div>