<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * category Template: Ajax问答布局
 */
get_header(); ?>

<?php if ( ! zm_get_option( 'ajax_layout_code_e_r' ) ) { ?><div class="ajax-content-area content-area"><?php } else { ?><div id="primary" class="ajax-content-area content-area"><?php } ?>
	<main id="main" class="site-main ajax-site-main-qa<?php if ( zm_get_option( 'ajax_layout_code_e_btn_m' ) ) { ?> ajax-qa-btn<?php } else { ?> ajax-qa-btn-tab<?php } ?>" role="main">
		<?php 
			$orderby = 'date';
			$meta_key = '';

			$order_option = zm_get_option('ajax_code_e_orderby');

			switch ( $order_option ) {
				case '':
				case 'date':
					$orderby = 'date';
					break;
				case 'modified':
					$orderby = 'modified';
					break;
				case 'comment_count':
					$orderby = 'comment_count';
					break;
				case 'views':
					$orderby = 'meta_value_num';
					$meta_key = 'views';
					break;
			}

			$btns     = zm_get_option( 'ajax_layout_code_e_btn' ) ? be_cat_btn() : 'no';
			$children = ( zm_get_option( 'ajax_layout_code_e_chil' ) == 'false' ) ? 'false' : 'true';

			echo do_shortcode( '[be_ajax_post posts_per_page="' . zm_get_option( 'ajax_layout_code_e_n' ) . '" column="' . zm_get_option( 'ajax_layout_code_e_f' ) . '" style="qa" cat="' . get_query_var( 'cat' ) . ',' . be_subcat_id() . '" btn="' . $btns . '" more="' . zm_get_option( 'nav_btn_e' ) . '" infinite="' . zm_get_option( 'more_infinite_e' ) . '" meta_key="' . $meta_key . '" orderby="' . $orderby . '" children="' . $children . '" order="DESC"]' );
		?>
	</main>
	<div class="clear"></div>
</div>
<?php if ( zm_get_option( 'ajax_layout_code_e_r' ) ) { ?>
<?php get_sidebar(); ?>
<?php } ?>
<?php get_footer(); ?>