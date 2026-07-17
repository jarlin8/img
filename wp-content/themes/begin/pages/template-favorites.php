<?php
/*
Template Name: 网址收藏
*/
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php get_header(); ?>

<div id="primary-width" class="content-area">
	<main id="main" class="be-main site-main sites-main site-main-cat<?php if ( cx_get_option( 'site_cat_fixed' ) ) { ?> site-cat-fixed<?php } ?>" role="main">
		<?php if ( cx_get_option( 'all_site_cat' ) ) { ?><?php all_sites_cat( true ); ?><?php } ?>
		<div class="sites-box sites-box-first">
			<?php
				foreach ( get_terms( 'favorites' ) as $term ) {
					echo do_shortcode( '[be_ajax_post style="sites" name="true" sites="true" moretext="' . cx_get_option( 'more_site' ) . '" cat="' . $term->term_id . '" more="more" posts_per_page="' . cx_get_option( 'site_p_n' ) . '"]' );
				}
			?>
		</div>
	</main>
</div>

<?php get_footer(); ?>