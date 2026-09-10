<?php if ( be_get_option( 'cms_collect' ) ) { ?>
<?php
	if ( ! defined( 'ABSPATH' ) ) exit;
	if ( ! be_get_option( 'cms_collect_tab' ) || ( be_get_option( 'cms_collect_tab' ) == 'tab' ) ) {
		$mode = 'colltab';
		$collimg = 'style="padding: ' . be_get_option( 'cms_collect_img_h' ) . 'px 15px 0 12px;background:url(' . be_get_option( 'cms_collect_img' ) . ');"';
	}

	if ( be_get_option( 'cms_collect_tab' ) == 'tag' ) {
		$mode = 'colltag';
		$collimg = '';
	}
?>
<?php $rec = ( array ) be_get_option( 'rectab' ); ?>

<div class="collect-box ms <?php echo $mode; ?>">
	<div class="collect-menu" <?php echo $collimg; ?>>
		<?php if ( ! empty( $rec['cms_collect_new'] ) ) { ?>
			<div class="collect-link collect-new collect-active"><?php if ( ! empty( $rec['cms_collect_new_t'] ) ) { ?><?php echo $rec['cms_collect_new_t']; ?><?php } ?></div>
		<?php } ?>
		<div class="collect-link collect-views<?php if ( empty( $rec['cms_collect_new'] ) ) { ?> collect-active<?php } ?>"><?php if ( ! empty( $rec['cms_collect_views_t'] ) ) { ?><?php echo $rec['cms_collect_views_t']; ?><?php } ?></div>
		<?php if ( ! empty( $rec['cms_collect_comment'] ) ) { ?>
			<div class="collect-link collect-comment"><?php echo $rec['cms_collect_comment_t']; ?></div>
		<?php } ?>
		<?php if ( ! empty( $rec['cms_collect_cat'] ) ) { ?>
			<div class="collect-link collect-cat"><?php echo $rec['cms_collect_cat_t']; ?></div>
		<?php } ?>
		<?php if ( ! empty( $rec['cms_collect_asset'] ) ) { ?>
			<div class="collect-link collect-asset"><?php echo $rec['cms_collect_asset_t']; ?></div>
		<?php } ?>
		<?php if ( class_exists( 'AnsPress' ) && ! empty( $rec['cms_collect_qa'] ) ) { ?>
			<div class="collect-link collect-qa"><?php echo $rec['cms_collect_qa_t']; ?></div>
		<?php } ?>
	</div>

	<section class="collect-content">
		<?php if ( ! empty( $rec['cms_collect_new'] ) ) { ?>
			<?php
				$new_type   = isset( $rec['cms_collect_new_type'] ) ? $rec['cms_collect_new_type'] : '';
				$post_types = array_diff( get_post_types( array( 'public' => true ) ), ( array ) $new_type );
				$notcat     = isset( $rec['cms_collect_not_new'] ) ? $rec['cms_collect_not_new'] : '';
				$notpost    = isset( $rec['cms_collect_not_new_post'] ) ? $rec['cms_collect_not_new_post'] : '';
				$args = array(
					'post_type'           => $post_types,
					'posts_per_page'      => $rec['cms_collect_new_n'],
					'post_status'         => 'publish',
					'ignore_sticky_posts' => true,
					'orderby'             => 'date',
					'order'               => 'DESC',
					'category__not_in'    => explode( ',', $notcat ),
					'post__not_in'        => explode( ',', $notpost ),
				);

				$query = new WP_Query( $args );

				if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); $do_not_duplicate[] = $post->ID;
			?>
				<article id="post-<?php the_ID(); ?>" class="collect-item <?php if ( ! empty( $rec['cms_collect_new_img'] ) ) { ?>collect-img<?php } else { ?>collect-list<?php } ?>" <?php aos_a(); ?>>
					<?php if ( ! empty( $rec['cms_collect_new_img'] ) ) { ?>
						<figure class="thumbnail">
							<?php echo zm_thumbnail(); ?>
						</figure>
					<?php } ?>
					<div class="collect-area">
						<h2 class="collect-title"><a href="<?php the_permalink(); ?>" rel="bookmark" <?php echo goal(); ?>><?php the_title(); ?></a></h2>
						<div class="collect-meta"><?php echo collect_meta_new(); ?></div>
					</div>
				</article>
			<?php
				endwhile;endif;
				wp_reset_postdata();
			?>
		<?php } else { ?>
			<?php
				$views_type = isset( $rec['cms_collect_views_type'] ) ? $rec['cms_collect_views_type'] : '';
				$post_types = array_diff( get_post_types( array( 'public' => true ) ), ( array ) $views_type );
				$notcat     = isset( $rec['cms_collect_not_views'] ) ? $rec['cms_collect_not_views'] : '';
				$days       = isset( $rec['cms_collect_not_views'] ) ? $rec['cms_collect_views_days'] : '';
				$n          = isset( $rec['cms_collect_views_n'] ) ? $rec['cms_collect_views_n'] : '';
				$notpost    = isset( $rec['cms_collect_not_views_post'] ) ? $rec['cms_collect_not_views_post'] : '';
				$args = array(
					'post_type'           => $post_types,
					'posts_per_page'      => $n,
					'post_status'         => 'publish',
					'ignore_sticky_posts' => true,
					'meta_key'            => 'views',
					'orderby'             => 'meta_value_num',
					'order'               => 'DESC',
					'category__not_in'    => explode( ',', $notcat ),
					'post__not_in'        => explode( ',', $notpost ),
					'date_query'          => array(
						array(
							'after'       => $days . ' days ago'
						)
					)
				);

				$query = new WP_Query( $args );

				if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
			?>
				<article id="post-<?php the_ID(); ?>" class="collect-item <?php if ( ! empty( $rec['cms_collect_views_img'] ) ) { ?>collect-img<?php } else { ?>collect-list<?php } ?>" <?php aos_a(); ?>>
					<?php if ( ! empty( $rec['cms_collect_views_img'] ) ) { ?>
						<figure class="thumbnail">
							<?php echo zm_thumbnail(); ?>
						</figure>
					<?php } ?>
					<div class="collect-area">
						<h2 class="collect-title"><a href="<?php the_permalink(); ?>" rel="bookmark" <?php echo goal(); ?>><?php the_title(); ?></a></h2>
						<div class="collect-meta"><?php echo collect_meta(); ?></div>
					</div>
				</article>
			<?php
				endwhile;endif;
				wp_reset_postdata();
			?>
		<?php } ?>
		<div class="collect-loading"><span class="dual-ring"></span><span class="dual-ring"></span></div>
	</section>
	<?php cms_help( $text = '首页设置 → 杂志布局 → 组合推荐', $number = 'cms_collect_s' ); ?>
</div>
<?php } ?>