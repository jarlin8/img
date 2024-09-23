<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_build( get_the_ID(), 'group_down' ) ) {
	if ( ! be_build( get_the_ID(), 'down_bg' ) || ( be_build( get_the_ID(), 'down_bg' ) == 'auto' ) ) {
		$bg = '';
	}
	if ( be_build( get_the_ID(), 'down_bg' ) == 'white' ) {
		$bg = ' group-white';
	}
	if ( be_build( get_the_ID(), 'down_bg' ) == 'gray' ) {
		$bg = ' group-gray';
	}
?>
<div class="betip line-group-assets g-row g-line<?php echo $bg; ?>" <?php aos(); ?>>
	<div class="g-col">
		<div class="flexbox-card-box">
			<div class="betip down-card-<?php echo be_build( get_the_ID(), 'group_down_f' ); ?>">
				<?php if ( ! be_build( get_the_ID(), 'group_down_get' ) || ( be_build( get_the_ID(), "group_down_get" ) == 'cat' ) ) { ?>
					<?php $cmscatlist = explode( ',',be_build( get_the_ID(), 'group_down_id' ) ); foreach ( $cmscatlist as $category ) {
						$cat = ( be_build( get_the_ID(), 'no_cat_child' ) ) ? 'category' : 'category__in';
						$btntext = be_build( get_the_ID(), 'group_down_btn_text' );
						$becat = $category;
						if ( function_exists( 'icl_object_id' ) && defined( 'ICL_LANGUAGE_CODE' ) ) {
							$becat = icl_object_id( $category, 'category', true );
						}
					?>

						<div class="group-title" <?php aos_b(); ?>>
							<a href="<?php echo get_category_link( $category ); ?>" title="<?php _e( '更多', 'begin' ); ?>" rel="bookmark" <?php echo goal(); ?>>
								<h3><?php echo get_cat_name( $becat ); ?></h3>
							</a>
							<?php if ( category_description( $category ) ) { ?>
								<div class="group-des"><?php echo category_description( $category ); ?></div>
							<?php } ?>
							<div class="clear"></div>
						</div>

						<div class="flexbox-card">
							<?php
								$img = get_posts( array(
									'posts_per_page' => be_build( get_the_ID(), 'group_down_n' ),
									'post_status'    => 'publish',
									$cat             => $category, 
									'orderby'        => 'date',
									'order'          => 'DESC', 
								) );

								foreach ( $img as $post ) : setup_postdata( $post );
								require get_template_directory() . '/template/down-card.php';
								endforeach;
								wp_reset_postdata();
							?>
							<div class="clear"></div>
						</div>
					<?php } ?>
				<?php } ?>

				<?php if ( be_build( get_the_ID(), 'group_down_get' ) == 'post' ) { ?>
					<?php
						$args = array(
							'post__in'  => explode( ',', be_build( get_the_ID(), 'group_down_post_id' ) ),
							'orderby'   => 'post__in', 
							'order'     => 'DESC',
							'ignore_sticky_posts' => true,
							);
						$query = new WP_Query( $args );
					?>

					<div class="group-title" <?php aos_b(); ?>>
						<?php if ( ! be_build( get_the_ID(),'group_down_t') == '' ) { ?>
							<h3><?php echo be_build( get_the_ID(),'group_down_t'); ?></h3>
						<?php } ?>
						<?php if ( ! be_build( get_the_ID(),'group_down_des') == '' ) { ?>
							<div class="group-des"><?php echo be_build( get_the_ID(),'group_down_des'); ?></div>
						<?php } ?>
						<div class="clear"></div>
					</div>

					<div class="flexbox-card">
						<?php
							$btntext = be_build( get_the_ID(), 'group_down_btn_text' );
							if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
								require get_template_directory() . '/template/down-card.php';
							endwhile;
							else :
								echo '<div class="flex-card-item" data-aos="fade-up"><div class="flex-card-area card-tip">公司主页 → 软件下载，输入文章ID</div></div>';
							endif;
							wp_reset_postdata();
						?>
						<div class="clear"></div>
					</div>
				<?php } ?>
			</div>
			<?php if ( ! be_build( get_the_ID(), 'group_down_get' ) || ( be_build( get_the_ID(), "group_down_get" ) == 'cat' ) ) { ?>
				<div class="group-cat-img-more"><a href="<?php echo get_category_link( $category ); ?>" title="<?php _e( '更多', 'begin' ); ?>" rel="bookmark" <?php echo goal(); ?>><i class="be be-more"></i></a></div>
			<?php } ?>
		</div>
		<?php bu_help( $text = '软件下载', $number = 'group_down_s' ); ?>
	</div>
</div>
<?php } ?>