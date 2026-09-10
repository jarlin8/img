<?php
/*
Template Name: 分类封面
*/
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php get_header(); ?>

<section id="primary-cover" class="content-area">
	<main id="main" class="be-main site-main" role="main">
		<?php if (zm_get_option('cat_cover')) { ?>
			<div class="cat-cover-box">
				<?php
					$terms = get_terms( array(
						'taxonomy'   => array( 'category' ),
						'orderby'    => 'menu_order',
						'order'      => 'ASC',
						'hide_empty' => 0
					) );

					foreach ( $terms as $term ) {
				?>
					<div class="cover4x grid-cat-<?php echo be_get_option( 'img_f' ); ?>">
						<div class="cat-cover-main" <?php aos_a(); ?>>
							<div class="cat-cover-img thumbs-b lazy">
								<a class="thumbs-back" href="<?php echo esc_url( get_term_link( $term ) ); ?>" rel="bookmark" data-src="<?php echo cat_cover_url( $term->term_id ); ?>">
									<?php if ( zm_get_option( 'cat_icon' ) ) { ?><i class="cover-icon <?php echo zm_taxonomy_icon_code( $term->term_id ); ?>"></i><?php } ?>
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