<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// 分类封面
function cat_cover() { ?>
	<div class="cat-rec-box">
		<?php if ( be_get_option( 'cat_cover_id' ) ) { ?>
			<?php
				$terms = get_terms(
					array(
						'taxonomy'   => array( 'category', 'post_tag', 'notice', 'gallery', 'videos', 'taobao', 'favorites', 'products' ),
						'include'    => be_get_option( 'cat_cover_id' ),
						'hide_empty' => false,
						'orderby'    => 'include', 
						'order'      => 'ASC',
					)
				);
				foreach ( $terms as $term ) {
			?>
				<div class="cat-rec-main cat-rec-<?php echo be_get_option( 'cover_img_f' ); ?>">
					<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" rel="bookmark" <?php echo goal(); ?>>
						<div class="boxs1">
							<div class="cat-rec-content tra ms" <?php aos_a(); ?>>
								<div class="cat-rec lazy<?php if ( get_option( 'zm_taxonomy_svg' . $term->term_id ) ) { ?> cat-rec-ico-svg<?php } else { ?> cat-rec-ico-img<?php } ?>">
									<?php if ( be_get_option( 'cat_rec_m' ) == 'cat_rec_img' ) { ?>
										<?php if ( zm_get_option( 'cat_cover' ) ) { ?>
											<div class="cat-rec-back" data-src="<?php echo cat_cover_url( $term->term_id ); ?>"></div>
										<?php } ?>
									<?php } ?>
									<?php if ( !be_get_option( 'cat_rec_m' ) || ( be_get_option( 'cat_rec_m' ) == 'cat_rec_ico' ) ) { ?>
										<?php if ( zm_get_option( 'cat_icon' ) ) { ?>
											<?php if ( !get_option('zm_taxonomy_svg' . $term->term_id ) ) { ?>
												<?php if ( get_option('zm_taxonomy_icon' . $term->term_id ) ) { ?><i class="cat-rec-icon fd <?php echo zm_taxonomy_icon_code( $term->term_id ); ?>"></i><?php } ?>
											<?php } else { ?>
												<?php if ( get_option('zm_taxonomy_svg' . $term->term_id ) ) { ?><svg class="cat-rec-svg fd icon" aria-hidden="true"><use xlink:href="#<?php echo zm_taxonomy_svg_code( $term->term_id ); ?>"></use></svg><?php } ?>
											<?php } ?>
										<?php } ?>
									<?php } ?>
								</div>
								<h4 class="cat-rec-title"><?php echo $term->name; ?></h4>
								<?php if ( category_description( $term->term_id ) ) { ?>
									<div class="cat-rec-des"><?php echo category_description( $term->term_id ); ?></div>
								<?php } else { ?>
									<div class="cat-rec-des"><?php _e( '暂无描述', 'begin' ); ?></div>
								<?php } ?>
								<?php if ( be_get_option( 'cat_cover_adorn' ) ) { ?>
									<div class="rec-adorn-s"></div><div class="rec-adorn-x"></div>
								<?php } ?>
								<div class="clear"></div>
							</div>
						</div>
					</a>
				</div>
			<?php } ?>
		<?php } else { ?>
			<div class="cat-rec-main cat-rec-<?php echo be_get_option( 'cover_img_f' ); ?>">
				<div class="cat-rec-content ms" <?php aos_a(); ?>>
					<h4 class="cat-rec-title">未添加分类ID</h4>
						<div class="cat-rec-des"><?php _e( '未添加分类ID', 'begin' ); ?></div>
					<div class="rec-adorn-s"></div><div class="rec-adorn-x"></div>
					<div class="clear"></div>
				</div>
			</div>
		<?php } ?>
		<div class="clear"></div>
	</div>
<?php }