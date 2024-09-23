<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * category Template: 封面导航
 */
get_header(); ?>
<section id="primary-cover" class="content-area">
	<main id="main" class="site-main" role="main">
		<?php if ( zm_get_option( 'cat_cover' ) ) { ?>
			<div class="cat-cover-box">
				<?php
					$terms = get_terms( 
						array(
							'taxonomy'   => 'category',
							'hide_empty' => false,
							'child_of'   => get_query_var( 'cat' )
						)
					);

					foreach ( $terms as $term ) {
					$term_data = get_term( $term->term_id, 'category' );
				?>
					<div class="cover4x grid-cat-<?php echo be_get_option( 'img_f' ); ?>">
						<div class="cat-cover-main" <?php aos_a(); ?>>
							<div class="cat-cover-img thumbs-b lazy">
								<a class="thumbs-back" href="<?php echo esc_url( get_term_link( $term ) ); ?>" rel="bookmark" <?php echo goal(); ?> data-src="<?php echo cat_cover_url( $term->term_id ); ?>">
									<?php if ( zm_get_option( 'cat_icon' ) ) { ?><i class="cover-icon <?php echo zm_taxonomy_icon_code( $term->term_id ); ?>"></i><?php } ?>
									<span class="cat-child-count post-count"><?php echo $term_data->count; ?></span>
									<div class="cover-des-box">
										<div class="cover-des">
											<div class="cover-des-main over">
												<?php echo category_description( $term ); ?>
											</div>
										</div>
									</div>
								</a>
								<h4 class="cat-cover-title"><?php echo $term->name; ?></h4>
							</div>
						</div>
					</div>
				<?php } ?>
				<div class="clear"></div>
			</div>
		<?php } ?>
	</main>
	<div class="clear"></div>
</section>

<?php get_footer(); ?>