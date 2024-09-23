<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
get_header(); ?>

<?php if ( is_tax( 'special', explode( ',', zm_get_option( 'ajax_special_code_a' ) ) ) ) { ?>
	<?php if ( ! zm_get_option( 'ajax_layout_code_a_r' ) ) { ?><div class="ajax-content-area content-area"><?php } else { ?><div id="primary" class="ajax-content-area content-area"><?php } ?>
		<main id="main" class="site-main ajax-site-main<?php if ( zm_get_option( 'post_no_margin') ) { ?> domargin<?php } ?>" role="main">
			<?php 
				if ( ! zm_get_option( 'ajax_code_a_orderby' ) || ( zm_get_option( 'ajax_code_a_orderby' ) == 'date' ) ) {
					$orderby = 'date';
					$meta_key = '';
				}
				if ( zm_get_option( 'ajax_code_a_orderby' ) == 'modified' ) {
					$orderby = 'modified';
					$meta_key = '';
				}
				if ( zm_get_option( 'ajax_code_a_orderby' ) == 'comment_count' ) {
					$orderby = 'comment_count';
					$meta_key = '';
				}
				if ( zm_get_option( 'ajax_code_a_orderby' ) == 'views' ) {
					$orderby = 'meta_value_num';
					$meta_key = 'views';
				}
				echo do_shortcode( '[be_ajax_post posts_per_page="' . zm_get_option( 'ajax_layout_code_a_n' ) . '" column="' . zm_get_option( 'ajax_layout_code_a_f' ) . '" special="true" btn_all="no" btn="no" terms="' . get_queried_object_id() . '" more="' . zm_get_option( 'nav_btn_a' ) . '" infinite="' . zm_get_option( 'more_infinite_a' ) . '" meta_key="' . $meta_key . '" orderby="' . $orderby . '" order="DESC"]' );
			?>
		</main>
		<div class="clear"></div>
	</div>
	<?php if ( zm_get_option( 'ajax_layout_code_a_r' ) ) { ?>
	<?php get_sidebar(); ?>
	<?php } ?>
<?php } elseif ( is_tax( 'special', explode( ',', zm_get_option( 'ajax_special_code_b' ) ) ) ) { ?>
	<?php if ( ! zm_get_option( 'ajax_layout_code_b_r' ) ) { ?><div class="ajax-content-area content-area"><?php } else { ?><div id="primary" class="ajax-content-area content-area"><?php } ?>
		<main id="main" class="site-main ajax-site-main<?php if ( zm_get_option( 'post_no_margin') ) { ?> domargin<?php } ?>" role="main">
			<?php 
				if ( ! zm_get_option( 'ajax_code_b_orderby' ) || ( zm_get_option( 'ajax_code_b_orderby' ) == 'date' ) ) {
					$orderby = 'date';
					$meta_key = '';
				}
				if ( zm_get_option( 'ajax_code_b_orderby' ) == 'modified' ) {
					$orderby = 'modified';
					$meta_key = '';
				}
				if ( zm_get_option( 'ajax_code_b_orderby' ) == 'comment_count' ) {
					$orderby = 'comment_count';
					$meta_key = '';
				}
				if ( zm_get_option( 'ajax_code_b_orderby' ) == 'views' ) {
					$orderby = 'meta_value_num';
					$meta_key = 'views';
				}
				echo do_shortcode( '[be_ajax_post posts_per_page="' . zm_get_option( 'ajax_layout_code_b_n' ) . '" column="' . zm_get_option( 'ajax_layout_code_b_f' ) . '" style="grid" special="true" btn_all="no" btn="no" terms="' . get_queried_object_id() . '" more="' . zm_get_option( 'nav_btn_b' ) . '" infinite="' . zm_get_option( 'more_infinite_b' ) . '" meta_key="' . $meta_key . '" orderby="' . $orderby . '" order="DESC"]' );
			?>
		</main>
		<div class="clear"></div>
	</div>
	<?php if ( zm_get_option( 'ajax_layout_code_b_r' ) ) { ?>
	<?php get_sidebar(); ?>
	<?php } ?>
<?php } elseif ( is_tax( 'special', explode( ',', zm_get_option( 'ajax_special_code_c' ) ) ) ) { ?>
	<?php if ( ! zm_get_option( 'ajax_layout_code_c_r' ) ) { ?><div class="ajax-content-area content-area"><?php } else { ?><div id="primary" class="ajax-content-area content-area"><?php } ?>
		<main id="main" class="site-main ajax-site-main<?php if ( zm_get_option( 'post_no_margin') ) { ?> domargin<?php } ?>" role="main">
			<?php 
				if ( ! zm_get_option( 'ajax_code_c_orderby' ) || ( zm_get_option( 'ajax_code_c_orderby' ) == 'date' ) ) {
					$orderby = 'date';
					$meta_key = '';
				}
				if ( zm_get_option( 'ajax_code_c_orderby' ) == 'modified' ) {
					$orderby = 'modified';
					$meta_key = '';
				}
				if ( zm_get_option( 'ajax_code_c_orderby' ) == 'comment_count' ) {
					$orderby = 'comment_count';
					$meta_key = '';
				}
				if ( zm_get_option( 'ajax_code_c_orderby' ) == 'views' ) {
					$orderby = 'meta_value_num';
					$meta_key = 'views';
				}
				echo do_shortcode( '[be_ajax_post posts_per_page="' . zm_get_option( 'ajax_layout_code_c_n' ) . '" column="' . zm_get_option( 'ajax_layout_code_c_f' ) . '" style="title" special="true" btn_all="no" btn="no" terms="' . get_queried_object_id() . '" more="' . zm_get_option( 'nav_btn_c' ) . '" infinite="' . zm_get_option( 'more_infinite_c' ) . '" meta_key="' . $meta_key . '" orderby="' . $orderby . '" order="DESC"]' );
			?>
		</main>
		<div class="clear"></div>
	</div>
	<?php if ( zm_get_option( 'ajax_layout_code_c_r' ) ) { ?>
	<?php get_sidebar(); ?>
	<?php } ?>


<?php } elseif ( is_tax( 'special', explode( ',', zm_get_option( 'all_special_item' ) ) ) ) { ?>
<section id="primary-cover" class="content-area">
	<main id="main" class="be-main site-main" role="main">
		<div class="cat-cover-box">
			<?php
				$cat_id =  get_queried_object()->term_id;
				$args = array(
					'taxonomy' => 'special',
					'child_of' => $cat_id
				);

				$child_cats  = get_terms( $args );
			?>

			<?php foreach ($child_cats as $cat) : ?>

				<div class="cover4x grid-cat-<?php echo be_get_option( 'img_f' ); ?>">
					<div class="cat-cover-main" <?php aos_a(); ?>>
						<div class="cat-cover-img thumbs-b lazy">
							<?php if ( zm_get_option( 'lazy_s' ) ) { ?>
								<a class="thumbs-back" href="<?php echo get_category_link( $cat->term_id ) ?>" rel="bookmark" <?php echo goal(); ?> data-src="<?php echo cat_cover_url( $cat->term_id ); ?>">
							<?php } else { ?>
								<a class="thumbs-back" href="<?php echo get_category_link( $cat->term_id ) ?>" rel="bookmark" <?php echo goal(); ?> style="background-image: url(<?php echo cat_cover_url( $cat->term_id ); ?>);">
							<?php } ?>

								<div class="cover-des-box">
									<div class="special-count fd"><?php echo $cat->count; ?><?php _e( '篇', 'begin' ); ?></div>
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

<?php } elseif ( is_tax( 'special', explode( ',', zm_get_option( 'ajax_special_code_d' ) ) ) ) { ?>
	<?php if ( ! zm_get_option( 'ajax_layout_code_f_r' ) ) { ?><div class="ajax-content-area content-area"><?php } else { ?><div id="primary" class="ajax-content-area content-area"><?php } ?>
		<main id="main" class="site-main ajax-site-main<?php if ( zm_get_option( 'post_no_margin') ) { ?> domargin<?php } ?>" role="main">
			<?php 
				if ( ! zm_get_option( 'ajax_code_f_orderby' ) || ( zm_get_option( 'ajax_code_f_orderby' ) == 'date' ) ) {
					$orderby = 'date';
					$meta_key = '';
				}
				if ( zm_get_option( 'ajax_code_f_orderby' ) == 'modified' ) {
					$orderby = 'modified';
					$meta_key = '';
				}
				if ( zm_get_option( 'ajax_code_f_orderby' ) == 'comment_count' ) {
					$orderby = 'comment_count';
					$meta_key = '';
				}
				if ( zm_get_option( 'ajax_code_f_orderby' ) == 'views' ) {
					$orderby = 'meta_value_num';
					$meta_key = 'views';
				}
				echo do_shortcode( '[be_ajax_post posts_per_page="' . zm_get_option( 'ajax_layout_code_f_n' ) . '" style="list" special="true" btn_all="no" btn="no" terms="' . get_queried_object_id() . '" more="' . zm_get_option( 'nav_btn_f' ) . '" infinite="' . zm_get_option( 'more_infinite_f' ) . '" meta_key="' . $meta_key . '" orderby="' . $orderby . '" order="DESC"]' );
			?>
		</main>
		<div class="clear"></div>
	</div>
	<?php if ( zm_get_option( 'ajax_layout_code_f_r' ) ) { ?>
	<?php get_sidebar(); ?>
	<?php } ?>
<?php } else { ?>
<div id="primary" class="content-area">
	<main id="main" class="site-main ajax-site-main<?php if ( zm_get_option( 'post_no_margin') ) { ?> domargin<?php } ?>" role="main">
		<?php 
			if ( ! zm_get_option( 'ajax_code_d_orderby' ) || ( zm_get_option( 'ajax_code_d_orderby' ) == 'date' ) ) {
				$orderby = 'date';
				$meta_key = '';
			}
			if ( zm_get_option( 'ajax_code_d_orderby' ) == 'modified' ) {
				$orderby = 'modified';
				$meta_key = '';
			}
			if ( zm_get_option( 'ajax_code_d_orderby' ) == 'comment_count' ) {
				$orderby = 'comment_count';
				$meta_key = '';
			}
			if ( zm_get_option( 'ajax_code_d_orderby' ) == 'views' ) {
				$orderby = 'meta_value_num';
				$meta_key = 'views';
			}
			echo do_shortcode( '[be_ajax_post posts_per_page="' . zm_get_option( 'ajax_layout_code_d_n' ) . '" style="default" special="true" btn_all="no" btn="no" terms="' . get_queried_object_id() . '" more="' . zm_get_option( 'nav_btn_d' ) . '" infinite="' . zm_get_option( 'more_infinite_d' ) . '" meta_key="' . $meta_key . '" orderby="' . $orderby . '" order="DESC"]' );
		?>
	</main>
	<div class="clear"></div>
</div>
<?php get_sidebar(); ?>
<?php } ?>
<?php get_footer(); ?>