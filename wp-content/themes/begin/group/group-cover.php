<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option( 'group_cat_cover' ) ) { ?>
<div class="g-row g-line sort group-cover" name="<?php echo zm_get_option( 'group_cat_cover_s' ); ?>" <?php aos(); ?>>
	<div class="g-col">
		<div class="group-cat-cover-box">
			<?php
				$args=array( 'include' => zm_get_option( 'group_cat_cover_id' ), 'hide_empty' => 0 );
				$cats = get_categories($args);
				foreach ( $cats as $cat ) {
					query_posts( 'cat=' . $cat->cat_ID );
			?>
			<div class="group-cat-cover-main group-cover-f<?php echo zm_get_option( 'group_cover_f' ); ?>">
				<div class="group-cat-cover bk sup">
					<div class="group-cat-cover-img<?php if ( zm_get_option( 'group_cover_gray' ) ) { ?> img-gray<?php } ?>" <?php aos_b(); ?>>
						<a rel="external nofollow" href="<?php echo get_category_link( $cat->cat_ID ); ?>">
							<?php if (zm_get_option( 'lazy_s' ) ) { ?>
								<span class="load"><img src="<?php echo get_template_directory_uri(); ?>/img/loading.png" alt="<?php echo $cat->cat_name; ?>" data-original="<?php echo cat_cover_url(); ?>"></span>
							<?php } else { ?>
								<img src="<?php echo cat_cover_url(); ?>" alt="<?php echo $cat->cat_name; ?>">
							<?php } ?>
						</a>
						<?php if ( zm_get_option( 'group_cover_title' ) ) { ?><h4 class="group-cat-cover-title"><?php echo $cat->cat_name; ?></h4><?php } ?>
					</div>
				</div>

			</div>
			<?php } wp_reset_query(); ?>
			<div class="clear"></div>
		</div>
	</div>
</div>
<?php } ?>