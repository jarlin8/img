<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('group_strong')) { ?>
<div class="g-row g-line group-strong-line sort" name="<?php echo zm_get_option( 'group_strong_s' ); ?>" <?php aos(); ?>>
	<div class="g-col">
		<div class="group-strong-box bgt">
			<div class="group-title bgt" <?php aos_b(); ?>>
				<?php if ( ! zm_get_option('group_strong_t') == '' ) { ?>
					<h3 class="bgt"><?php echo zm_get_option('group_strong_t'); ?></h3>
				<?php } ?>
				<?php if ( ! zm_get_option('group_strong_des') == '' ) { ?>
					<div class="group-des bgt"><?php echo zm_get_option('group_strong_des'); ?></div>
				<?php } ?>
				<div class="clear"></div>
			</div>

			<div class="group-strong-content bgt" <?php aos_b(); ?>>
				<?php echo zm_get_option('group_strong_inf'); ?>
			</div>

			<div class="group-strong-slider owl-carousel slider-strong bgt">
				<?php 
					$posts = get_posts(
						array(
							'post_type' => 'any',
							'orderby' => 'menu_order',
							'orderby' => 'date',
							'order' => 'ASC',
							'meta_key' => 'group_strong',
							'numberposts' => '60'
						)
					);
					if ( $posts ) : foreach( $posts as $post ) : setup_postdata( $post );
				?>

				<?php 
				if ( zm_get_option( 'group_carousel_c' ) ) {
					$title = ' group-strong-title';
				} else {
					$title = '';
				}
				?>

				<div class="strong-strong-post bkh da">
					<div class="strong-thumbnail"><?php zm_thumbnail_scrolling(); ?></div>
					<div class="clear"></div>
					<?php 
					if ( zm_get_option( 'group_strong_title_c' ) ) {
						$title = ' group-strong-title-c';
					} else {
						$title = '';
					}
					the_title( sprintf( '<h2 class="group-strong-title bgt over' . $title . '"><a class="bgt" href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
					<div class="clear"></div>
				</div>
				<?php endforeach; endif; ?>
				<?php wp_reset_query(); ?>
			</div>
		</div>
	</div>
</div>
<?php } ?>