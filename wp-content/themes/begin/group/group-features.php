<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('group_features')) { ?>
<div class="g-row g-line sort" name="<?php echo zm_get_option('group_features_s'); ?>">
	<div class="g-col">
		<div class="group-features">
			<div class="group-title" <?php aos_b(); ?>>
				<?php if ( zm_get_option('features_t') == '' ) { ?>
				<?php } else { ?>
					<h3><?php echo zm_get_option('features_t'); ?></h3>
				<?php } ?>
				<div class="group-des"><?php echo zm_get_option('features_des'); ?></div>
				<div class="clear"></div>
			</div>
			<div class="section-box">
				<?php query_posts('showposts='.zm_get_option('features_n').'&category__and='.zm_get_option('features_id')); while (have_posts()) : the_post(); ?>
				<div class="g4 g<?php echo zm_get_option('group_features_f'); ?>" <?php aos_b(); ?>>
					<div class="box-4">
						<figure class="section-thumbnail">
							<?php tao_thumbnail(); ?>
						</figure>
						<?php the_title( sprintf( '<h3 class="g4-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
					</div>
				</div>
				<?php endwhile; ?>
				<?php wp_reset_query(); ?>
				<div class="clear"></div>
				<?php if ( zm_get_option('features_url') == '' ) { ?>
				<?php } else { ?>
					<div class="img-more"><a href="<?php echo zm_get_option('features_url'); ?>"><?php _e( '更多', 'begin' ); ?> <i class="be be-fastforward"></i></a></div>
				<?php } ?>
			</div>
		</div>
		<div class="clear"></div>
	</div>
</div>
<?php } ?>