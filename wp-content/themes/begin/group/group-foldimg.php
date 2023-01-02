<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('group_foldimg')) { ?>
<div class="g-row g-line foldimg-line sort" name="<?php echo zm_get_option( 'foldimg_s' ); ?>" <?php aos(); ?>>
	<div class="g-col">
		<div class="foldimg-box">
			<div class="group-title" <?php aos_b(); ?>>
				<?php if ( ! zm_get_option('foldimg_t') == '' ) { ?>
					<h3><?php echo zm_get_option( 'foldimg_t' ); ?></h3>
				<?php } ?>
				<?php if ( ! zm_get_option('foldimg_des') == '' ) { ?>
					<div class="group-des"><?php echo zm_get_option( 'foldimg_des' ); ?></div>
				<?php } ?>
				<div class="clear"></div>
			</div>
			<div class="foldimg-wrap<?php if ( zm_get_option( 'foldimg_fl' ) ) { ?> foldimg-one<?php } else { ?> foldimg-two<?php } ?>">
				<?php $posts = get_posts( array( 'post_type' => 'any', 'orderby' => 'menu_order', 'order' => 'ASC', 'meta_key' => 'foldimg_img', 'numberposts' => '60' ) ); if ($posts) : foreach( $posts as $post ) : setup_postdata( $post ); ?>
				<?php
					$foldimg_img = get_post_meta( get_the_ID(), 'foldimg_img', true );
					$foldimg_title = get_post_meta( get_the_ID(), 'foldimg_title', true );
					$foldimg_more = get_post_meta( get_the_ID(), 'foldimg_more', true);
					$foldimg_more_url = get_post_meta( get_the_ID(), 'foldimg_more_url', true );
				?>

				<div class="foldimg-main">
					<span class="foldimg-mask"></span>
					<section class="foldimg-img" <?php aos_g(); ?>>
						<figure class="foldimg-bg" style="background-image: url(<?php echo $foldimg_img; ?>) !important;"></figure>
						<?php edit_post_link('<i class="be be-editor"></i>', '<span class="edit-foldimg-but bgt">', '</span>' ); ?>
					</section>
					<section class="foldimg-inc bgt">
						<div class="foldimg-text bgt"><?php the_title(); ?></div>
						<div class="foldimg-title bgt"><?php echo $foldimg_title; ?></div>
						<?php if ( get_post_meta( get_the_ID(), 'foldimg_more', true ) ) { ?>
							<div class="foldimg-more"><a class="bgt" href="<?php echo $foldimg_more_url; ?>" target="_blank" rel="external nofollow"><?php echo $foldimg_more; ?></a></div>
						<?php } ?>
					</section>
				</div>
				<?php endforeach; endif; ?>
				<?php wp_reset_query(); ?>
			</div>
			<div class="clear"></div>
		</div>
	</div>
</div>
<?php } ?>