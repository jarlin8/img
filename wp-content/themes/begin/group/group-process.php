<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('group_process')) { ?>
<div class="g-row g-line group-process-line sort" name="<?php echo zm_get_option('process_s'); ?>" <?php aos(); ?>>
	<div class="g-col">
		<div class="group-process-box">
			<div class="group-title" <?php aos_b(); ?>>
				<?php if ( ! zm_get_option( 'process_t') == '' ) { ?>
					<h3><?php echo zm_get_option( 'process_t' ); ?></h3>
				<?php } ?>
				<?php if ( ! zm_get_option('process_des') == '' ) { ?>
					<div class="group-des"><?php echo zm_get_option( 'process_des' ); ?></div>
				<?php } ?>
				<div class="clear"></div>
			</div>
			<div class="group-process-wrap">
				<?php 
					$i = 0;
					$posts = get_posts(
						array(
							'post_type' => 'any',
							'orderby' => 'menu_order',
							'orderby' => 'date',
							'order' => 'ASC',
							'meta_key' => 'process_ico',
							'numberposts' => '60'
						)
					);

					if ( $posts ) : foreach( $posts as $post ) : setup_postdata( $post );
					$i++;
				?>
				<?php
					$process_ico = get_post_meta( get_the_ID(), 'process_ico', true );
					$process_explain = get_post_meta( get_the_ID(), 'process_explain', true );
				?>
				<div class="process-main da ess-<?php echo $i; ?>" <?php aos_b(); ?>>
					<div class="group-process">
						<div class="process-round<?php if ( zm_get_option( 'process_turn' ) ) { ?> process-round-on<?php } ?>"></div>
						<div class="group-process-order ces"><?php echo $i; ?></div>
						<div class="group-process-ico"><i class="ces <?php echo $process_ico; ?>"></i></div>
						<h3 class="group-process-title ces bgt bz"><?php the_title(); ?></h3>
					</div>

					<div class="group-process-explain">
						<div class="group-process-explain-main bz">
							<?php
								$content = get_the_content();
								$content = strip_shortcodes( $content );
								echo wp_trim_words( $content, '160', '' );
							?>
						</div>
						<?php edit_post_link('<i class="be be-editor"></i>', '<span class="edit-process-but bgt">', '</span>' ); ?>
					</div>
				</div>

				<?php endforeach; endif; ?>
				<?php wp_reset_query(); ?>
			</div>
		</div>
	</div>
</div>
<?php } ?>