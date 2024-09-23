<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( zm_get_option( 'group_help' ) ) { ?>
<div class="g-row g-line group-help-line sort" name="<?php echo zm_get_option( 'group_help_s' ); ?>" <?php aos(); ?>>
	<div class="g-col">
		<div class="group-help-box">
			<div class="group-title" <?php aos_b(); ?>>
				<?php if ( ! zm_get_option( 'group_help_t') == '' ) { ?>
					<h3><?php echo zm_get_option( 'group_help_t' ); ?></h3>
				<?php } ?>
				<?php if ( ! zm_get_option('group_new_des') == '' ) { ?>
					<div class="group-des"><?php echo zm_get_option( 'group_help_des' ); ?></div>
				<?php } ?>
				<div class="clear"></div>
			</div>
			<div class="group-help-wrap">
				<div class="group-help-img" <?php aos_g(); ?>>
					<div class="group-help-bg">
						<img alt="<?php echo zm_get_option( 'group_help_t' ); ?>" src="<?php echo zm_get_option( 'group_help_img' ); ?>">
						<div class="group-help-txt fd"><?php echo zm_get_option( 'group_help_t' ); ?></div>
					</div>
				</div>
				<div class="group-help-main">
					<?php 
						$i = 0;
						$posts = get_posts(
							array(
								'post_type' => 'any',
								'orderby' => 'menu_order',
								'orderby' => 'date',
								'order' => 'ASC',
								'meta_key' => 'group_help_post',
								'numberposts' => '60'
							)
						);

						if ( $posts ) : foreach( $posts as $post ) : setup_postdata( $post );
						$i++;
					?>
					
					<div class="group-help-area <?php if ( $i < 2 ) { ?> active<?php } ?>" <?php aos_b(); ?>>
						<div class="group-help-title group-help-title-<?php echo $i; ?>">
							<span class="help-ico"></span>
							<?php if ( zm_get_option( 'group_help_num') ) { ?>
								<span class="group-help-num"><?php echo $i; ?></span>
							<?php } ?>
							<?php the_title(); ?>
						</div>
						<div class="group-help-content group-help-content-<?php echo $i; ?>">
							<?php
								$content = get_the_content();
								$content = strip_shortcodes( $content );
								echo wp_trim_words( $content, '500', '' );
							?>
						</div>
						<?php edit_post_link('<i class="be be-editor"></i>', '<span class="edit-help-but group-edit bgt">', '</span>' ); ?>
					</div>
					<?php endforeach; endif; ?>
					<?php wp_reset_query(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php } ?>