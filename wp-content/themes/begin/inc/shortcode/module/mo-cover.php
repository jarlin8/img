<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
// 分类封面
function module_cover( $atts ) {
	$atts = shortcode_atts( array(
		'id'  => 1,
		'ico' => 1,
		'col'  => 4,
	), $atts );
	$cat_id  = sanitize_text_field( $atts['id'] );
	$cat_ico = sanitize_text_field( $atts['ico'] );
	$col      = intval( $atts['col'] );
	ob_start();
	?>
	<div class="module-area cat-rec-box">
			<?php
				$terms = get_terms(
					array(
						'taxonomy'   => array( 'category', 'post_tag', 'notice', 'gallery', 'videos', 'taobao', 'favorites', 'products' ),
						'include'    => array_map( 'intval', explode( ',', $cat_id ) ),
						'hide_empty' => false,
						'orderby'    => 'include', 
						'order'      => 'ASC',
					)
				);
				foreach ( $terms as $term ) {
			?>
				<div class="cat-rec-main cat-rec-<?php echo $col; ?>">
					<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" rel="bookmark">
						<div class="boxs1">
							<div class="cat-rec-content tra ms" <?php aos_a(); ?>>
								<div class="cat-rec lazy<?php if ( get_option( 'zm_taxonomy_svg' . $term->term_id ) ) { ?> cat-rec-ico-svg<?php } else { ?> cat-rec-ico-img<?php } ?>">
									<?php if ( empty( $atts['ico'] ) ) { ?>
										<div class="cat-rec-back" data-src="<?php echo cat_cover_url( $term->term_id ); ?>"></div>
									<?php } else { ?>
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
		<div class="clear"></div>
	</div>
	<?php
	return ob_get_clean();
}
// [cover id="分类ID" col="分栏" ico="1"]
add_shortcode( 'cover', 'module_cover' );