<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="post bk da" <?php aos_a(); ?> >
	<section class="no-results not-found">
		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
			<p><?php _e( '目前还没有文章！', 'begin' ); ?></p>
			<p><a href="<?php echo get_option('siteurl'); ?>/wp-admin/post-new.php"><?php _e( '点击这里发布您的第一篇文章', 'begin' ); ?></a></p>>
		<?php elseif ( is_search() ) : ?>
			<header class="entry-header">
				<h1 class="page-title"><?php _e( '没有您要找的文章！', 'begin' ); ?></h1>
			</header>
			<p><?php _e( '抱歉，没有找到您的搜索内容。 请尝试换个关键字再试一次。', 'begin' ); ?></p>
			<?php search_class(); ?>
		<?php else : ?>
			<p><?php _e( '目前还没有文章！可以尝试使用下面的搜索功能，查找您喜欢的文章！', 'begin' ); ?></p>
			<?php search_class(); ?>
		<?php endif; ?>
	</section>
</div>