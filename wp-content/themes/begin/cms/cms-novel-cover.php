<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_get_option( 'cms_novel_cover' ) ) { ?>
	<div class="betip">
		<?php 
			$novel = ( array ) be_get_option( 'cms_novel_cover_cat' );
			foreach ( $novel as $items ) {
		?>
			<div class="cms-novel-cover-cat<?php if ( $items['cms_novel_cover_m'] !== 'novel_cover_grid' ) { ?> cms-novel-cat-box ms<?php } ?>">

				<?php if ( ! $items['cms_novel_cover_m'] || ( $items['cms_novel_cover_m'] == 'novel_cover_cat' ) ) { ?>
					<h3 class="cat-square-title">
						<a href="<?php echo get_category_link( $items['cms_novel_cover_id'] ); ?>" rel="bookmark" <?php echo goal(); ?>>
							<?php if ( zm_get_option( 'cat_icon' ) ) { ?>
								<?php if ( get_option( 'zm_taxonomy_icon' .$items['cms_novel_cover_id'] ) ) { ?><i class="t-icon <?php echo zm_taxonomy_icon_code( $items['cms_novel_cover_id'] ); ?>"></i><?php } ?>
								<?php if ( get_option( 'zm_taxonomy_svg' . $items['cms_novel_cover_id'] ) ) { ?><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo zm_taxonomy_svg_code( $items['cms_novel_cover_id'] ); ?>"></use></svg><?php } ?>
								<?php if ( ! get_option( 'zm_taxonomy_icon' . $items['cms_novel_cover_id'] ) && ! get_option( 'zm_taxonomy_svg'.$items['cms_novel_cover_id'] ) ) { ?><?php title_i(); ?><?php } ?>
							<?php } else { ?>
								<?php title_i(); ?>
							<?php } ?>
							<?php echo get_cat_name( $items['cms_novel_cover_id'] ); ?>
							<?php more_i(); ?>
						</a>
					</h3>
				<?php } ?>

				<div class="cms-novel-cover-main all-novel">

					<?php
						if ( empty( $items['cms_novel_cover_random'] ) ) {
							$number = $items['cms_novel_cover_n'];
						} else {
							$number = '';
						}
						$terms = get_terms( 
							array(
								'taxonomy'   => 'category',
								'hide_empty' => false,
								'child_of'   => $items['cms_novel_cover_id'],
								'number'     => $number
							)
						);

						if ( ! empty( $items['cms_novel_cover_random'] ) ) {
							$cat_id = $items['cms_novel_cover_id'];
							$current_cat_obj = get_term( $cat_id );

							if ( $current_cat_obj == false ) {
								$terms = array_filter(
									$terms, 
									function ($term) use ($current_cat_obj) {
										return $term->term_id !== $current_cat_obj->term_id;
									}
								);
							}

							shuffle( $terms );
							$random_terms = array_slice( $terms, 0, $items['cms_novel_cover_n'] );
						} else {
							$random_terms = $terms;
						}
						foreach ( $random_terms as $term ) {
					?>
						<div class="cms-novel-main">
							<?php if ( $items['cms_novel_cover_m'] == 'novel_cover_grid' ) { ?><div class="boxs1"><?php } ?>
							<div class="cms-novel-box tra" <?php aos_a(); ?>>
								<div class="cms-novel-cove-img-box">
									<div class="cms-novel-cove-img">
										<?php if ( zm_get_option( 'cat_cover' ) ) { ?>
											<a class="thumbs-back sc" href="<?php echo esc_url( get_term_link( $term ) ); ?>" rel="bookmark" <?php echo goal(); ?>>
												<div class="novel-cove-img" data-src="<?php echo cat_cover_url( $term->term_id ); ?>"></div>
											</a>
										<?php } else { ?>
											<div class="cat-cover-tip">未启用分类封面</div>
										<?php } ?>
										<?php if ( ! empty( $items['cms_novel_cover_mark'] ) ) { ?>
											<div class="special-mark bz fd"><?php echo $items['cms_novel_cover_mark']; ?></div>
										<?php } ?>
									</div>
								</div>

								<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" rel="bookmark" <?php echo goal(); ?>>
									<div class="novel-cover-des">
										<h4 class="cat-novel-title"><?php echo $term->name; ?></h4>
									
										<?php if ( ! empty( $items['cms_novel_cover_author'] ) ) { ?>
											<div class="cat-novel-author">
												<?php if ( be_get_option( 'cms_novel_author' ) ) { ?>
													<?php if ( get_option( 'cat-author-' . $term->term_id ) ) { ?>
														<span><?php echo be_get_option('novel_author_t'); ?></span>
														<?php echo get_option( 'cat-author-' . $term->term_id ); ?>
													<?php } ?>
												<?php } ?>
											</div>
										<?php } ?>

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
							<?php if ( $items['cms_novel_cover_m'] == 'novel_cover_grid' ) { ?></div><?php } ?>
						</div>
					<?php } ?>

					<?php if ( empty( $random_terms ) ) { ?>
					 	<div class="cms-novel-main">
							<div class="cms-novel-box" <?php aos_a(); ?>>
								<div class="novel-cover-des">
									<h4 class="cat-novel-title">该分类没有子分类</h4>
								</div>
							</div>
						</div>
					<?php } ?>

					<div class="clear"></div>
				</div>
			</div>
		<?php } ?>
		<?php cms_help( $text = '首页设置 → 杂志布局 → 书籍封面', $number = 'cms_novel_cover_s' ); ?>
	</div>
<?php } ?>