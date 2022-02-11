<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('group_bulletin')) { ?>
<div class="g-row g-line group-notice sort" name="<?php echo zm_get_option('group_bulletin_s'); ?>" <?php aos(); ?>>
	<div class="g-col ">
		<div class="section-box">
			<div id="group-scrolldiv">
				<div class="noticetext">
					<div class="noticeico"><i class="be be-volumedown"></i></div>
					<ul class="placardtxt owl-carousel">
						<?php
							$args = array(
								'post_type' => 'bulletin',
								'showposts' => zm_get_option('group_bulletin_n'), 
							);

							if(zm_get_option('group_bulletin_id')) {
								$args = array(
									'showposts' => zm_get_option('group_bulletin_n'), 
									'tax_query' => array(
										array(
											'taxonomy' => 'notice',
											'terms' => explode(',',zm_get_option('group_bulletin_id') )
										),
									)
								);
							}
						?>
						<?php $be_query = new WP_Query($args); while ($be_query->have_posts()) : $be_query->the_post(); ?>
							<?php the_title( sprintf( '<li class="scrolltext-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></li>' ); ?>
						<?php endwhile; ?>
						<?php wp_reset_query(); ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<?php } ?>