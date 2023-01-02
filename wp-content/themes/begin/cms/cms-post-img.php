<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php query_posts( array ( 'meta_key' => 'post_img', 'showposts' => zm_get_option( 'post_img_n' ), 'ignore_sticky_posts' => 1, 'post__not_in' => $do_not_duplicate ) ); if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<div class="xl4 xm4">
	<div class="picture-cms picture-cms-img-item ms bk<?php if ( zm_get_option('post_no_margin' ) && zm_get_option( 'news_model' ) == 'news_normal' ) { ?> addclose<?php } ?>" <?php aos_a(); ?>>
		<figure class="picture-cms-img">
			<?php zm_thumbnail(); ?>
			<div class="posting-title over"><a  class="bgt" href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></div>
		</figure>
	</div>
</div>
<?php endwhile; ?>
<?php else : ?>
<div class="be-none-img da bk ms">编辑文章，勾选“杂志布局图文模块”</div>
<?php endif; ?>
<?php wp_reset_query(); ?>
<div class="clear"></div>