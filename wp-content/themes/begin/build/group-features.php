<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_build( get_the_ID(), 'group_features' ) ) {
	if ( ! be_build( get_the_ID(), 'features_bg' ) || ( be_build( get_the_ID(), 'features_bg' ) == 'auto' ) ) {
		$bg = '';
	}
	if ( be_build( get_the_ID(), 'features_bg' ) == 'white' ) {
		$bg = ' group-white';
	}
	if ( be_build( get_the_ID(), 'features_bg' ) == 'gray' ) {
		$bg = ' group-gray';
	}
?>
<div class="g-row g-line group-features-line<?php echo $bg; ?>">
	<div class="g-col">
		<div class="group-features">
			<div class="group-title" <?php aos_b(); ?>>
				<?php if ( ! be_build( get_the_ID(), 'features_t') == '' ) { ?>
					<h3><?php echo be_build( get_the_ID(), 'features_t' ); ?></h3>
				<?php } ?>
				<?php if ( ! be_build( get_the_ID(), 'features_des' ) == '' ) { ?>
					<div class="group-des"><?php echo be_build( get_the_ID(), 'features_des' ); ?></div>
				<?php } ?>
				<div class="clear"></div>
			</div>
			<div class="section-box">
				<?php
					$features = get_posts( array(
						'posts_per_page' => be_build( get_the_ID(), 'features_n' ),
						'category__and'  => be_build( get_the_ID(), 'features_id' ),
					) );
				?>

				<?php $build = get_the_ID(); ?>

				<?php foreach ( $features as $post ) : setup_postdata( $post ); ?>
					<div class="g4 g<?php echo be_build( $build, 'group_features_f' ); ?>">
						<div class="box-4">
							<figure class="section-thumbnail" <?php aos_b(); ?>>
								<?php echo tao_thumbnail(); ?>
							</figure>
							<?php the_title( sprintf( '<h2 class="g4-title"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
						</div>
					</div>
				<?php endforeach; ?>
				<?php wp_reset_postdata(); ?>
				<div class="clear"></div>
				<?php if ( ! be_build( get_the_ID(), 'features_url' ) == '' ) { ?>
					<div class="group-post-more">
						<a href="<?php echo be_build( get_the_ID(), 'features_url' ); ?>" title="<?php _e( '更多', 'begin' ); ?>" rel="bookmark" <?php echo goal(); ?>><i class="be be-more"></i></a>
					</div>
				<?php } ?>
			</div>
		</div>
		<?php bu_help( $text = '简介', $number = 'group_features_s' ); ?>
		<div class="clear"></div>
	</div>
</div>
<?php } ?>