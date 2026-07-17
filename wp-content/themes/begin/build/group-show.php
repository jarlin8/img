<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_build( get_the_ID(), 'group_show' ) ) {
	if ( ! be_build( get_the_ID(), 'show_bg' ) || ( be_build( get_the_ID(), 'show_bg' ) == 'auto' ) ) {
		$bg = '';
	}
	if ( be_build( get_the_ID(), 'show_bg' ) == 'white' ) {
		$bg = ' group-white';
	}
	if ( be_build( get_the_ID(), 'show_bg' ) == 'gray' ) {
		$bg = ' group-gray';
	}
?>
<div class="g-row g-line group-features-line<?php echo $bg; ?>" <?php aos(); ?>>
	<div class="g-col">
		<div class="group-features">
			<div class="group-title" <?php aos_b(); ?>>
				<?php if ( ! be_build( get_the_ID(), 'group_show_t' ) == '' ) { ?>
					<h3><?php echo be_build( get_the_ID(), 'group_show_t' ); ?></h3>
				<?php } ?>

				<?php if ( ! be_build( get_the_ID(), 'group_show_des') == '' ) { ?>
					<div class="group-des"><?php echo be_build( get_the_ID(), 'group_show_des' ); ?></div>
				<?php } ?>
				<div class="clear"></div>
			</div>
			<div class="section-box">
				<?php
					$args = array(
						'post_type' => 'show',
						'showposts' => be_build( get_the_ID(), 'group_show_n' ), 
					);

					if ( be_build( get_the_ID(), 'group_show_id' ) ) {
						$args = array(
							'showposts' => be_build( get_the_ID(), 'group_show_n' ), 
							'tax_query' => array(
								array(
									'taxonomy' => 'products',
									'terms' => explode(',', be_build( get_the_ID(), 'group_show_id' ) )
								),
							)
						);
					}
				?>

				<?php $build = get_the_ID(); ?>

				<?php $be_query = new WP_Query( $args ); while ( $be_query->have_posts()) : $be_query->the_post(); ?>
				<div class="g4 g<?php echo be_build( $build, 'group_show_f' ); ?>">
					<div class="box-4" <?php aos_b(); ?>>
						<figure class="picture-cms-img">
							<?php echo img_thumbnail(); ?>
						</figure>
						<?php the_title( sprintf( '<h3 class="g4-title"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
					</div>
				</div>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
				<div class="clear"></div>
				<?php if ( be_build( get_the_ID(), 'group_show_url' ) == '' ) { ?>
				<?php } else { ?>
					<div class="group-post-more"><a href="<?php echo be_build( get_the_ID(), 'group_show_url' ); ?>" title="<?php _e( '更多', 'begin' ); ?>" rel="bookmark" <?php echo goal(); ?>><i class="be be-more"></i></a></div>
				<?php } ?>
			</div>
		</div>
		<?php bu_help( $text = '项目模块', $number = 'group_show_s' ); ?>
		<div class="clear"></div>
	</div>
</div>
<?php } ?>