<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * category Template: Ajax问答布局
 */
get_header(); ?>

<div id="primary" class="ajax-content-area content-area">
	<main id="main" class="site-main ajax-site-main-qa<?php if ( zm_get_option( 'ajax_layout_code_e_btn_m' ) ) { ?> ajax-qa-btn<?php } else { ?> ajax-qa-btn-tab<?php } ?>" role="main">
		<?php 
			if ( ! zm_get_option( 'ajax_code_e_orderby' ) || ( zm_get_option( 'ajax_code_e_orderby' ) == 'date' ) ) {
				$orderby = 'date';
				$meta_key = '';
			}
			if ( zm_get_option( 'ajax_code_e_orderby' ) == 'modified' ) {
				$orderby = 'modified';
				$meta_key = '';
			}
			if ( zm_get_option( 'ajax_code_e_orderby' ) == 'comment_count' ) {
				$orderby = 'comment_count';
				$meta_key = '';
			}
			if ( zm_get_option( 'ajax_code_e_orderby' ) == 'views' ) {
				$orderby = 'meta_value_num';
				$meta_key = 'views';
			}

			if ( zm_get_option( 'ajax_layout_code_e_btn' ) ) {
				$btns = be_cat_btn();
			} else {
				$btns = 'no';
			}
			echo do_shortcode( '[be_ajax_post posts_per_page="' . zm_get_option( 'ajax_layout_code_e_n' ) . '" column="' . zm_get_option( 'ajax_layout_code_e_f' ) . '" style="qa" cat="' . get_query_var( 'cat' ) . ',' . be_subcat_id() . '" btn="' . $btns . '" more="' . zm_get_option( 'nav_btn_e' ) . '" infinite="' . zm_get_option( 'more_infinite_e' ) . '" meta_key="' . $meta_key . '" orderby="' . $orderby . '" order="DESC"]' );
		?>
	</main>
	<div class="clear"></div>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>