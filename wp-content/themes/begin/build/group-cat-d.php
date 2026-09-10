<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_build( get_the_ID(), 'group_cat_d' ) ) {
	if ( ! be_build( get_the_ID(), 'cat_d_bg' ) || ( be_build( get_the_ID(), 'cat_d_bg' ) == 'auto' ) ) {
		$bg = '';
	}
	if ( be_build( get_the_ID(), 'cat_d_bg' ) == 'white' ) {
		$bg = ' group-white';
	}
	if ( be_build( get_the_ID(), 'cat_d_bg' ) == 'gray' ) {
		$bg = ' group-gray';
	}
?>
<div class="g-row g-line group-cat-d<?php echo $bg; ?>" <?php aos(); ?>>
	<div class="g-col">
		<div class="grf-cat-box">
			<?php 
				$catd = ( array ) be_build( get_the_ID(), 'group_cat_d_item' );
				foreach ( $catd as $items ) {
				$cat = be_build( get_the_ID(), 'group_no_cat_child' ) ? 'category' : 'category__in';
			?>

				<div class="grf-cat-item" <?php aos_f(); ?>>
					<a href="<?php echo esc_url( get_category_link( $items['group_cat_d_id'] ) ); ?>" rel="bookmark" <?php echo goal(); ?>>
						<figure class="grf-thumbnail" <?php aos_b(); ?>>
							<h3 class="grf-cat-name" <?php aos_b(); ?>><?php echo get_cat_name( $items['group_cat_d_id'] ); ?></h3>
							<img src="<?php echo $items['group_cat_d_img']; ?>" alt="<?php echo get_cat_name( $items['group_cat_d_id'] ); ?>">
						</figure>
					</a>
					<ul class="grf-cat-list" <?php aos_f(); ?>>
						<?php
							$cat_d = get_posts( array(
								'post_status'    => 'publish',
								'posts_per_page' => be_build( get_the_ID(), 'group_cat_d_n' ),
								$cat             => $items['group_cat_d_id'],
							) );
						?>
						<?php foreach ( $cat_d as $post ) : setup_postdata( $post ); ?>
							<li class="list-date"><time datetime="<?php echo get_the_date( 'Y-m-d' ); ?> <?php echo get_the_time( 'H:i:s' ); ?>"><?php the_time( 'm/d' ) ?></time></li>
							<?php the_title( sprintf( '<li class="list-title"><h2 class="group-list-title"><a class="srm" href="%s" rel="bookmark" ' . goal() . '>' . t_mark(), esc_url( get_permalink() ) ), '</a></h2></li>' ); ?>
						<?php endforeach; ?>
						<?php wp_reset_postdata(); ?>
					</ul>
				</div>
			<?php } ?>
		</div>
		<?php bu_help( $text = '新闻资讯D', $number = 'group_cat_d_s' ); ?>
	</div>
</div>
<?php } ?>