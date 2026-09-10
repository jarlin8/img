<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
// 网址分类
get_header();

if ( is_tax( 'favorites' ) ) {
	$current_term = get_queried_object();
	$term_id = $current_term->term_id;

	$query = new WP_Query( array(
		'post_type' => 'sites',
		'tax_query' => array(
			array(
				'taxonomy' => 'favorites',
				'terms'    => $term_id,
				'include_children' => false
			)
		),
		'posts_per_page' => 1
	));

	if ( ! $query->have_posts() ) {
		get_template_part( 'pages/template-favorites' );
	} else {
		?>
		<div id="primary-width" class="content-area">
			<main id="main" class="be-main site-main sites-main site-main-cat<?php if ( cx_get_option( 'site_cat_fixed' ) ) { ?> site-cat-fixed<?php } ?>" role="main">
				<?php if ( cx_get_option( 'all_site_cat' ) ) { ?>
					<?php all_sites_cat(); ?>
				<?php } ?>
				<div class="sites-box">
					<?php 
						echo do_shortcode( '[be_ajax_post style="sites" name="true" btn="no" sites="true" moretext="' . cx_get_option( 'more_site' ) . '" cat="' . get_queried_object_id() . '" more="more" posts_per_page="' . cx_get_option( 'site_c_n' ) . '"]' ); 
					?>
				</div>
			</main>
		</div>
		<?php 
	}
}

get_footer();