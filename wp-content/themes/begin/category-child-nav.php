<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * category Template: 分类导航
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
					<div class="cat-rec-main cat-rec-<?php echo be_get_option( 'img_f' ); ?>">
						<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" rel="bookmark" <?php echo goal(); ?>>
							<div class="boxs1">
								<div class="cat-rec-content ms" <?php aos_a(); ?>>
									<div class="cat-rec lazy<?php if ( get_option( 'zm_taxonomy_svg' . $term->term_id ) ) { ?> cat-rec-ico-svg<?php } else { ?> cat-rec-ico-img<?php } ?>">
									<?php if ( zm_get_option( 'cat_cover' ) ) { ?>
										<div class="cat-rec-back" data-src="<?php echo cat_cover_url( $term->term_id ); ?>"></div>
									<?php } ?>

									</div>
									<h4 class="cat-rec-title"><?php echo $term->name; ?></h4>
									<?php if ( category_description( $term->term_id ) ) { ?>
										<div class="cat-rec-des"><?php echo category_description( $term->term_id ); ?></div>
									<?php } else { ?>
										<div class="cat-rec-des"><?php _e( '暂无描述', 'begin' ); ?></div>
									<?php } ?>
									<span class="cat-nav-count post-count"><?php echo $term_data->count; ?></span>
									<div class="clear"></div>
								</div>
							</div>
						</a>
					</div>
				<?php } ?>
				<div class="clear"></div>
			</div>
		<?php } ?>
	</main>
	<div class="clear"></div>
</section>

<?php get_footer(); ?>