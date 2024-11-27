<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
// 小说书籍
function module_novel( $atts ) {
	$atts = shortcode_atts( array(
		'id'   => 1,
		'mark' => 1,
		'marktext' => '书籍',
	), $atts );
	$id   = sanitize_text_field( $atts['id'] );
	$mark     = intval( $atts['mark'] );
	$marktext = $atts['marktext'];
	ob_start();
	?>

	<div class="module-area cms-novel-cover">
		<?php
			$terms = get_terms(
				array(
					'taxonomy'   => 'category',
					'include'    => array_map( 'intval', explode( ',', $id ) ),
					'hide_empty' => false,
					'orderby'    => 'include',
					'order'      => 'ASC',
				)
			);
			foreach ( $terms as $term ) {
		?>
			<div class="cms-novel-main">
				<div class="boxs1">
					<div class="cms-novel-box tra ms" <?php aos_a(); ?>>
						<div class="cms-novel-cove-img-box">
							<div class="cms-novel-cove-img">
								<?php if ( zm_get_option( 'cat_cover' ) ) { ?>
									<a class="thumbs-back sc" href="<?php echo esc_url( get_term_link( $term ) ); ?>" rel="external nofollow">
										<div class="novel-cove-img" data-src="<?php echo cat_cover_url( $term->term_id ); ?>"></div>
									</a>
								<?php } else { ?>
									<div class="cat-cover-tip">未启用分类封面</div>
								<?php } ?>

								<?php if ( ! empty( $atts['mark'] ) ) { ?>
									<div class="special-mark bz fd"><?php echo $marktext; ?></div>
								<?php } ?>
							</div>
						</div>

						<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" rel="bookmark">
							<div class="novel-cover-des">
								<h4 class="cat-novel-title"><?php echo $term->name; ?></h4>
								<div class="cat-novel-author">
									<?php if ( be_get_option( 'cms_novel_author' ) ) { ?>
										<?php if ( get_option( 'cat-author-' . $term->term_id ) ) { ?>
											<span><?php echo be_get_option('novel_author_t'); ?></span>
											<?php echo get_option( 'cat-author-' . $term->term_id ); ?>
										<?php } ?>
									<?php } ?>
								</div>

								<div class="cms-novel-des">
									<?php 
										if ( get_option( 'cat-message-' . $term->term_id ) ) {
											$description = wpautop( get_option( 'cat-message-' . $term->term_id ) );
											echo mb_strimwidth( $description, 0, 60, '...' ); 
										} else {
											if ( category_description( $term->term_id ) ) {
												$description = category_description( $term->term_id );
												echo mb_strimwidth( $description, 0, 60, '...' ); 
											} else {
												echo '为分类添加描述或附加信息';
											}
										}
									?>
								</div>
							</div>
						</a>
						<div class="clear"></div>
					</div>
				</div>
			</div>
		<?php } ?>
	</div>
	<?php
	return ob_get_clean();
}
// [novel id="分类ID" mark="1" mark_text="角标文字"]
add_shortcode( 'novel', 'module_novel' );