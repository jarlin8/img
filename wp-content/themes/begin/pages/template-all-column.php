<?php
/*
Template Name: 所有专栏
*/
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php get_header(); ?>

<section id="primary-cover" class="content-area">
	<main id="main" class="be-main site-main" role="main">
		<div class="cat-cover-box">
			<?php
				$special = array(
					'taxonomy'      => 'special',
					'show_count'    => 1,
					'orderby'       => 'menu_order',
					'order'         => 'ASC',
					'hide_empty'    => 0,
					'hierarchical'  => 0
				);
				$query = new WP_Query( $special );
				$cats = get_categories( $special );
			?>

			<?php foreach( $cats as $cat ) :  ?>
				<div class="cover4x grid-cat-<?php echo zm_get_option( 'img_f' ); ?>">
					<div class="cat-cover-main sup bk" <?php aos_a(); ?>>
						<div class="cat-cover-img thumbs-b lazy">
							<?php if ( zm_get_option( 'lazy_s' ) ) { ?>
								<a class="thumbs-back" href="<?php echo get_category_link( $cat->term_id ) ?>" rel="bookmark" data-src="<?php echo cat_cover_url( $cat->term_id ); ?>">
							<?php } else { ?>
								<a class="thumbs-back" href="<?php echo get_category_link( $cat->term_id ) ?>" rel="bookmark" style="background-image: url(<?php echo cat_cover_url( $cat->term_id ); ?>);">
							<?php } ?>

								<div class="cover-des-box">
									<div class="special-count bgt fd"><?php echo $cat->count; ?><?php _e( '篇', 'begin' ); ?></div>
									<div class="cover-des">
										<div class="cover-des-main over">
											<?php echo term_description( $cat->term_id ); ?>
										</div>
									</div>
								</div>
							</a>
							<h4 class="cat-cover-title"><?php echo $cat->name; ?></h4>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
			<?php wp_reset_postdata(); ?>
			<div class="clear"></div>
		</div>
	</main>
	<div class="clear"></div>
</section>

<?php get_footer(); ?>