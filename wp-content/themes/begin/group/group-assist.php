<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( zm_get_option( 'group_assist' ) ) { ?>
<div class="g-row g-line group-assist-line sort" name="<?php echo zm_get_option( 'group_assist_s' ); ?>" <?php aos(); ?>>
	<div class="g-col">
		<div class="group-assist-box">
			<div class="group-title" <?php aos_b(); ?>>
				<?php if ( ! zm_get_option( 'group_assist_t') == '' ) { ?>
					<h3><?php echo zm_get_option( 'group_assist_t' ); ?></h3>
				<?php } ?>
				<?php if ( ! zm_get_option('group_assist_des') == '' ) { ?>
					<div class="group-des"><?php echo zm_get_option( 'group_assist_des' ); ?></div>
				<?php } ?>
				<div class="clear"></div>
			</div>
			<div class="group-assist-wrap">
				<?php 
					$i = 0;
					$posts = get_posts(
						array(
							'post_type' => 'any',
							'orderby' => 'menu_order',
							'orderby' => 'date',
							'order' => 'ASC',
							'meta_key' => 'assist_ico',
							'numberposts' => '60'
						)
					);

					if ( $posts ) : foreach( $posts as $post ) : setup_postdata( $post );
					$i++;
				?>
				<?php
					$assist_ico = get_post_meta( get_the_ID(), 'assist_ico', true );
				?>

				<div class="group-assist-main-box">
					<div class="group-assist-main bkh ass-<?php echo $i; ?>" <?php aos_b(); ?>>
						<?php if ( zm_get_option( 'group_assist_url' ) ) { ?><a href="<?php echo get_permalink(); ?>" rel="bookmark"><?php } ?>
						<div class="group-assist">
							<div class="group-assist-content">
								<h4 class="group-assist-title gat"><?php the_title(); ?></h4>
								<div class="group-assist-des">
									<?php
										$content = get_the_content();
										$content = strip_shortcodes( $content );
										echo wp_trim_words( $content, '260', '' );
									?>
								</div>
							</div>
							<div class="group-assist-ico"><i class="<?php echo $assist_ico; ?>"></i></div>
							<div class="clear"></div>
						</div>
						<?php if (zm_get_option( 'group_assist_url' ) ) { ?></a><?php } ?>
						<?php edit_post_link('<i class="be be-editor"></i>', '<span class="edit-assist-but group-edit bgt">', '</span>' ); ?>
					</div>
				</div>
				<?php endforeach; endif; ?>
				<?php wp_reset_query(); ?>
			</div>
		</div>
	</div>
</div>
<?php } ?>