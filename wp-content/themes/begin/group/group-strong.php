<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (co_get_option('group_strong')) {
	if ( ! co_get_option( 'strong_bg' ) || ( co_get_option( 'strong_bg' ) == 'auto' ) ) {
		$bg = '';
	}
	if ( co_get_option( 'strong_bg' ) == 'white' ) {
		$bg = ' group-white';
	}
	if ( co_get_option( 'strong_bg' ) == 'gray' ) {
		$bg = ' group-gray';
	}
?>
<div class="g-row g-line group-strong-line<?php echo $bg; ?>" <?php aos(); ?>>
	<div class="g-col">
		<div class="group-strong-box">
			<div class="group-title" <?php aos_b(); ?>>
				<?php if ( ! co_get_option('group_strong_t') == '' ) { ?>
					<h3><?php echo co_get_option('group_strong_t'); ?></h3>
				<?php } ?>
				<?php if ( ! co_get_option('group_strong_des') == '' ) { ?>
					<div class="group-des"><?php echo co_get_option('group_strong_des'); ?></div>
				<?php } ?>
				<div class="clear"></div>
			</div>

			<div class="group-strong-content single-content sanitize" <?php aos_f(); ?>>
				<div class="text-back be-text"><?php echo wpautop( co_get_option( 'group_strong_inf' ) ); ?></div>
			</div>

			<div class="group-strong-slider owl-carousel slider-strong">
				<?php 
					$posts = get_posts(
						array(
							'post_type' => 'any',
							'orderby'   => 'post__in', 
							'order'     => 'DESC',
							'post__in'  => explode( ',', co_get_option( 'group_strong_id' ) ),
						)
					);
					if ( $posts ) : foreach( $posts as $post ) : setup_postdata( $post );
				?>

				<div class="strong-strong-post" <?php aos_f(); ?>>
					<div class="strong-thumbnail"><?php echo zm_thumbnail_scrolling(); ?></div>
					<div class="clear"></div>
					<?php 
						$title = co_get_option( 'group_strong_title_c' ) ? ' group-strong-title-c' : '';
						the_title( sprintf( '<h2 class="group-strong-title over' . $title . '"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' );
					?>
					<div class="clear"></div>
				</div>
				<?php endforeach; endif; ?>
				<?php wp_reset_postdata(); ?>
			</div>
		</div>
		<?php co_help( $text = '公司主页 → 咨询', $number = 'group_strong_s' ); ?>
	</div>
</div>
<?php } ?>