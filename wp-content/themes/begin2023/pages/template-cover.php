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
					$args=array(
						'hide_empty' => 0
					);
					$cats = get_categories($args);
					foreach ( $cats as $cat ) {
					query_posts( 'cat=' . $cat->cat_ID );
				?>
					<div class="cover4x grid-cat-<?php echo zm_get_option( 'img_f' ); ?>">
						<div class="cat-cover-main sup bk" <?php aos_a(); ?>>
							<div class="cat-cover-img thumbs-b lazy">
								<a class="thumbs-back" href="<?php echo get_category_link($cat->cat_ID);?>" rel="bookmark" data-src="<?php echo cat_cover_url(); ?>">
									<?php if (zm_get_option('cat_icon')) { ?><i class="cover-icon <?php echo zm_taxonomy_icon_code(); ?>"></i><?php } ?>
									<div class="cover-des-box">
										<div class="cover-des">
											<div class="cover-des-main over">
												<?php echo the_archive_description(); ?>
											</div>
										</div>
									</div>
								</a>
								<h4 class="cat-cover-title"><?php echo $cat->cat_name; ?></h4>
							</div>
						</div>
					</div>
				<?php } ?>
				<?php wp_reset_query(); ?>
				<div class="clear"></div>
			</div>
		<?php } ?>
	</main>
	<div class="clear"></div>
</section>

<?php get_footer(); ?>