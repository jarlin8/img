<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * category Template: 书籍封面
 */
remove_filter( 'be_header_sub', 'top_sub' );
//remove_filter( 'be_header_sub', 'header_img' );
remove_filter( 'be_header_sub', 'header_sub' );
get_header(); ?>
<section id="picture" class="all-novel-area content-area">
	<main id="main" class="be-main all-novel site-main" role="main">
		<?php if (zm_get_option('cat_cover')) { ?>
			<div class="cms-novel-cover">
				<?php
					$terms = get_terms( 
						array(
							'taxonomy'   => 'category',
							'hide_empty' => false,
							'child_of'   => get_query_var( 'cat' )
						)
					);

					foreach ( $terms as $term ) {
				?>
					<div class="cms-novel-main">
						<div class="boxs1">
							<div class="cms-novel-box" <?php aos_a(); ?>>
								<div class="cms-novel-cove-img-box">
									<div class="cms-novel-cove-img">
										<a class="thumbs-back sc" href="<?php echo esc_url( get_term_link( $term ) ); ?>" rel="bookmark" <?php echo goal(); ?>>
											<div class="novel-cove-img" data-src="<?php echo cat_cover_url( $term->term_id ); ?>"></div>
										</a>
										<?php if ( be_get_option( 'cms_novel_mark' ) ) { ?>
											<div class="special-mark bz fd"><?php echo be_get_option( 'cms_novel_mark' ); ?></div>
										<?php } ?>
									</div>
								</div>

								<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" rel="bookmark" <?php echo goal(); ?>>
									<div class="novel-cover-des">
										<h4 class="cat-novel-title"><?php echo $term->name; ?></h4>

										<?php if ( be_get_option( 'cms_novel_author' ) ) { ?>
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
												$description = category_description( $term->term_id );
												echo mb_strimwidth( $description, 0, 62, '...' ); 
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
		<?php } ?>
	</main>
</section>

<?php get_footer(); ?>