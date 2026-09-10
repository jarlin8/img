<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_get_option( 'cms_cat_novel' ) ) { ?>
	<div class="betip">
		<div class="cms-novel-cover">
			<?php
				$terms = get_terms(
					array(
						'taxonomy'   => 'category',
						'include'    => be_get_option( 'cms_cat_novel_id' ),
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
										<a class="thumbs-back sc" href="<?php echo esc_url( get_term_link( $term ) ); ?>" rel="bookmark" <?php echo goal(); ?>>
											<div class="novel-cove-img" data-src="<?php echo cat_cover_url( $term->term_id ); ?>"></div>
										</a>
									<?php } else { ?>
										<div class="cat-cover-tip">未启用分类封面</div>
									<?php } ?>

									<?php if ( be_get_option( 'cms_novel_mark' ) ) { ?>
										<div class="special-mark bz fd"><?php echo be_get_option( 'cms_novel_mark' ); ?></div>
									<?php } ?>
								</div>
							</div>

							<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" rel="bookmark" <?php echo goal(); ?>>
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
		<?php cms_help( $text = '首页设置 → 杂志布局 → 小说书籍', $number = 'cms_cat_novel_s' ); ?>
	</div>
<?php } ?>