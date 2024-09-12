<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('dean')) { ?>
<div class="g-row g-line sort" name="<?php echo zm_get_option('dean_s'); ?>" <?php aos(); ?>>
	<div class="g-col">
		<div class="deanm">
			<div class="group-title" <?php aos_b(); ?>>
				<?php if ( zm_get_option('dean_t') == '' ) { ?>
				<?php } else { ?>
					<h3><?php echo zm_get_option('dean_t'); ?></h3>
				<?php } ?>
				<div class="group-des"><?php echo zm_get_option('dean_des'); ?></div>
				<div class="clear"></div>
			</div>
			<div class="deanm-main">
				<?php $posts = get_posts( array( 'post_type' => 'any', 'orderby' => 'menu_order', 'order' => 'ASC', 'meta_key' => 'pr_a', 'numberposts' => '16') ); if($posts) : foreach( $posts as $post ) : setup_postdata( $post ); ?>
					<?php 
						$pr_a = get_post_meta($post->ID, 'pr_a', true);
						$pr_b = get_post_meta($post->ID, 'pr_b', true);
						$pr_c = get_post_meta($post->ID, 'pr_c', true);
						$pr_d = get_post_meta($post->ID, 'pr_d', true);
						$pr_e = get_post_meta($post->ID, 'pr_e', true);
						$pr_f = get_post_meta($post->ID, 'pr_f', true);
					?>
				<div class="deanm sup deanmove edit-buts deanmove-<?php echo zm_get_option('deanm_f'); ?> <?php if ( !zm_get_option('deanm_fm') ) { ?> deanm-jd<?php } else { ?> deanm-fm<?php } ?> da bkh">
					<?php edit_post_link('<i class="be be-editor"></i>', '<span class="edit-link-but">', '</span>' ); ?>
					<div class="de-t" <?php aos_b(); ?>><?php the_title(); ?></div>
					<div class="clear"></div>
					<div class="de-a" <?php aos_b(); ?>><?php echo $pr_b; ?></div>
					<div class="deanquan" <?php aos_b(); ?>>
						<div class="de-back lazy">
							<div class="thumbs-de-back" data-src="<?php if ( get_post_meta($post->ID, 'pr_f', true) ) { ?><?php echo $pr_f; ?><?php } else { ?>https://s2.loli.net/2021/12/13/EgHZaXtjlPO8df6.jpg<?php } ?>"></div>
							<div class="de-b bgt bz" <?php aos_b(); ?>><?php echo $pr_a; ?></div>
						</div>
					</div>
					<div class="de-c" <?php aos_b(); ?>><?php echo $pr_c; ?></div>
					<?php if ( get_post_meta($post->ID, 'pr_e', true) ) { ?>
						<div class="de-button" <?php aos_b(); ?>>
							<a class="dah" href="<?php echo $pr_d; ?>" target="_blank" rel="external nofollow"><?php echo $pr_e; ?></a>
						</div>
					<?php } else { ?>
						<div class="de-button-seat"></div>
					<?php } ?>
				</div>
				<?php endforeach; endif; ?>
				<?php wp_reset_query(); ?>
				<div class="clear"></div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
</div>
<?php } ?>