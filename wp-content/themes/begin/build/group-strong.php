<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_build( get_the_ID(), 'group_strong' ) ) {
	if ( ! be_build( get_the_ID(), 'strong_bg' ) || ( be_build( get_the_ID(), 'strong_bg' ) == 'auto' ) ) {
		$bg = '';
	}
	if ( be_build( get_the_ID(), 'strong_bg' ) == 'white' ) {
		$bg = ' group-white';
	}
	if ( be_build( get_the_ID(), 'strong_bg' ) == 'gray' ) {
		$bg = ' group-gray';
	}
?>
<div class="g-row g-line group-strong-line<?php echo $bg; ?>" <?php aos(); ?>>
	<div class="g-col">
		<div class="group-strong-box">
			<div class="group-title" <?php aos_b(); ?>>
				<?php if ( ! be_build( get_the_ID(), 'group_strong_t' ) == '' ) { ?>
					<h3><?php echo be_build( get_the_ID(), 'group_strong_t' ); ?></h3>
				<?php } ?>
				<?php if ( ! be_build( get_the_ID(), 'group_strong_des') == '' ) { ?>
					<div class="group-des"><?php echo be_build( get_the_ID(), 'group_strong_des'); ?></div>
				<?php } ?>
				<div class="clear"></div>
			</div>

			<div class="group-strong-content single-content sanitize" <?php aos_f(); ?>>
				<div class="text-back be-text"><?php echo wpautop( be_build( get_the_ID(), 'group_strong_inf' ) ); ?></div>
			</div>

			<div class="group-strong-slider owl-carousel slider-strong">
				<?php 
					$posts = get_posts(
						array(
							'post_type' => 'any',
							'orderby'   => 'post__in', 
							'order'     => 'DESC',
							'post__in'  => explode( ',', be_build( get_the_ID(), 'group_strong_id' ) ),
						)
					);
					if ( $posts ) : foreach( $posts as $post ) : setup_postdata( $post );
				?>

				<div class="strong-strong-post" <?php aos_f(); ?>>
					<div class="strong-thumbnail"><?php echo zm_thumbnail_scrolling(); ?></div>
					<div class="clear"></div>
					<?php 
						$title = be_build( get_the_ID(), 'group_strong_title_c' ) ? ' group-strong-title-c' : '';
						the_title( sprintf( '<h2 class="group-strong-title over' . $title . '"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' );
					?>
					<div class="clear"></div>
				</div>
				<?php endforeach; endif; ?>
				<?php wp_reset_postdata(); ?>
			</div>
		</div>
		<?php bu_help( $text = '咨询', $number = 'group_strong_s' ); ?>
	</div>
</div>
<?php } ?>