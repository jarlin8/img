<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('video_box')) { ?>
<?php if (zm_get_option('video')) { ?>
	<div class="line-four line-four-video-item sort" name="<?php echo zm_get_option('video_s'); ?>">
		<?php
			$args = array(
				'post_type' => 'video',
				'showposts' => zm_get_option('video_n'), 
			);

			if (zm_get_option('video_id')) {
				$args = array(
					'showposts' => zm_get_option('video_n'), 
					'tax_query' => array(
						array(
							'taxonomy' => 'videos',
							'terms' => explode(',',zm_get_option('video_id') )
						),
					)
				);
			}
		?>
		<?php $be_query = new WP_Query($args); while ($be_query->have_posts()) : $be_query->the_post(); ?>
		<div class="xl4 xm4">
			<div class="picture-cms ms bk" <?php aos_a(); ?>>
				<figure class="picture-cms-img">
					<?php videos_thumbnail(); ?>
					<a rel="external nofollow" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-play"></i></a>
				</figure>
				<?php the_title( sprintf( '<h2 class="picture-cms-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
			</div>
		</div>

		<?php endwhile; ?>
		<?php wp_reset_query(); ?>
		<div class="clear"></div>
	</div>
<?php } ?>

<?php if (zm_get_option('video_post')) { ?>
<div class="line-four line-four-video-item sort" name="<?php echo zm_get_option('video_s'); ?>">
	<?php query_posts('showposts='.zm_get_option('video_n').'&category__and='.zm_get_option('video_post_id')); while (have_posts()) : the_post(); ?>
	<div class="xl4 xm4">
		<div class="picture-cms ms bk" <?php aos_a(); ?>>
			<figure class="picture-cms-img">
				<?php zm_thumbnail(); ?>
				<a rel="external nofollow" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-play"></i></a>
			</figure>
			<?php the_title( sprintf( '<h2 class="picture-cms-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		</div>
	</div>
	<?php endwhile; ?>
	<?php wp_reset_query(); ?>
	<div class="clear"></div>
</div>
<?php } ?>
<?php } ?>