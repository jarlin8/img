<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( ! is_search() && is_archive() ) {
	function archive_img() { ?>
		<div class="header-sub header-sub-img">
			<?php if ( ! zm_get_option( 'top_sub' ) || zm_get_option( 'top_sub_img' ) ) { ?>
				<div class="cat-des"<?php if ( zm_get_option( 'cat_img_h' ) ) { ?> style="max-height: <?php echo zm_get_option( 'cat_des_height' ); ?>px;"<?php } ?> <?php aos_a(); ?>>
					<?php if ( zm_get_option( 'cat_icon' ) ) { ?>
						<?php $term_id = get_query_var( 'cat' ); if ( get_option( 'zm_taxonomy_icon'.$term_id ) ) { ?><i class="header-cat-icon <?php echo zm_taxonomy_icon_code(); ?>"></i><?php } ?>
						<?php $term_id = get_query_var( 'cat' ); if ( get_option( 'zm_taxonomy_svg'.$term_id ) ) { ?><svg class="header-cat-icon icon" aria-hidden="true"><use xlink:href="#<?php echo zm_taxonomy_svg_code(); ?>"></use></svg><?php } ?>
					<?php } ?>

					<?php if ( zm_get_option( 'cat_des_img_crop' ) ) { ?>
						<div class="cat-des-img<?php if ( zm_get_option( 'cat_des_img_zoom' ) ) { ?> cat-des-img-zoom<?php } ?>"><img src="<?php if ( function_exists( 'zm_taxonomy_image_url' ) ) echo get_template_directory_uri().'/prune.php?src=' . zm_taxonomy_image_url() . '&w=' . zm_get_option( 'img_des_w' ).'&h=' . zm_get_option( 'img_des_h' ) . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1'; ?>" alt="<?php single_cat_title(); ?>"><div class="clear"></div></div>
					<?php } else { ?>
						<div class="cat-des-img<?php if ( zm_get_option( 'cat_des_img_zoom' ) ) { ?> cat-des-img-zoom<?php } ?>"><img src="<?php if ( function_exists( 'zm_taxonomy_image_url' ) ) echo zm_taxonomy_image_url(); ?>" alt="<?php single_cat_title(); ?>"><div class="clear"></div></div>
					<?php } ?>
		
					<div class="des-title<?php if ( zm_get_option( 'des_title_l' ) ) { ?> des-title-l<?php } ?><?php if ( zm_get_option( 'header_title_narrow' ) ) { ?> title-narrow<?php } ?>">
						<h1 class="des-t fadeInUp animated">
							<?php single_cat_title(); ?>
							<?php if ( is_year() ) { ?>
								<?php the_time('Y'); ?>11<?php if ( get_bloginfo( 'language' ) !== 'en-US' ) { ?><?php _e( '年', 'begin' ); ?><?php } ?>
							<?php } ?>
							<?php if ( is_month() ) { ?>
								<?php the_time('Y'); ?><?php if ( get_bloginfo( 'language' ) !== 'en-US' ) { ?><?php _e( '年', 'begin' ); ?><?php } else { ?> <?php } ?><?php the_time('F'); ?>
							<?php } ?>
							<?php if ( is_day() ) { ?>
								<?php the_date(); ?>
							<?php } ?>
						</h1>
						<?php if ( zm_get_option( 'cat_des_p' ) ) { ?><?php echo the_archive_description( '<div class="des-p fadeInUp animated">', '</div>' ); ?><?php } ?>
					</div>
					<?php if ( ! is_year() && ! is_month() && ! is_day() ) { ?>
						<?php
							$term = get_queried_object();
							$special = array(
								'taxonomy'   => 'special',
								'include'    => get_queried_object_id(),
							);
							$cats = get_categories( $special );
							foreach( $cats as $cat ) {
						 ?>
	
						<?php if ( $term->parent == 0 ) { ?>
							<?php 
								$term = get_queried_object();
								$args = array(
									'taxonomy'   => 'special',
									'child_of' => $term->term_id,
									'hierarchical' => true,
									'hide_empty' => false,
								);
								$children = get_terms($args);
								$special_count = count($children);
							?>
									<div class="header-special-count"><?php _e( '包含', 'begin' ); ?> <?php echo $special_count; ?> <?php _e( '专栏', 'begin' ); ?></div>
							<?php } else { ?>
									<div class="header-special-count"><?php _e( '包含', 'begin' ); ?> <?php echo $cat->count; ?> <?php _e( '篇', 'begin' ); ?></div>
							<?php } ?>
						<?php } ?>
					<?php } ?>
				</div>

			<?php } else { ?>

				<div class="cat-des-bg" style="background:<?php echo zm_get_option( 'top_sub_bg' ); ?>;" <?php aos_a(); ?>>
					<div class="cat-des-c<?php if ( zm_get_option( 'des_title_l' ) ) { ?> cat-des-l<?php } ?>">
						<div class="des-title des-title-box<?php if ( zm_get_option( 'des_title_l' ) ) { ?> des-title-l<?php } ?><?php if ( zm_get_option( 'header_title_narrow' ) ) { ?> title-narrow<?php } ?>">
							<h1 class="des-t"><?php single_cat_title(); ?></h1>
							<?php if ( zm_get_option( 'cat_des_p' ) ) { ?><?php echo the_archive_description( '<div class="des-p">', '</div>' ); ?><?php } ?>
						</div>
					</div>
				</div>
			<?php } ?>

			<?php if ( isset( $term ) && get_option( 'cat-message-' . $term->term_id ) ) { ?>
				<div class="des-cat ms" <?php aos_a(); ?>>
					<div class="des-cat-content">
						<?php echo wpautop( get_option( 'cat-message-' . $term->term_id ) ); ?>
					</div>
				</div>
			<?php } ?>
			<?php be_help( $text = '主题选项 → 基本设置 → 分类设置 → 分类图片' ); ?>
		</div>
	<?php } ?>

	<?php 
		if ( zm_get_option( 'cat_des_img' ) ) {
			if ( zm_get_option( 'cat_des' ) ) {
				if ( is_category() ) {
					$category_description = category_description();
					if ( $category_description ) {
						archive_img();
					}
				} elseif ( is_tax() ) {
					$taxonomy = get_queried_object();
					if ( $taxonomy && !empty( $taxonomy->description ) ) {
						archive_img();
					}
				}
			} else {
				archive_img();
			}
		}
	?>
<?php } ?>