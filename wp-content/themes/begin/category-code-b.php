<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * category Template: Ajax卡片布局
 */
get_header(); ?>

<?php if ( ! zm_get_option( 'ajax_layout_code_b_r' ) ) { ?><div class="ajax-content-area content-area"><?php } else { ?><div id="primary" class="ajax-content-area content-area"><?php } ?>
	<main id="main" class="site-main ajax-site-main" role="main">
		<?php 
			$orderby = 'date';
			$meta_key = '';

			$order_option = zm_get_option('ajax_code_b_orderby');

			if ( $order_option == 'modified' ) {
				$orderby = 'modified';
			} elseif ( $order_option == 'comment_count' ) {
				$orderby = 'comment_count';
			} elseif ( $order_option == 'views' ) {
				$orderby = 'meta_value_num';
				$meta_key = 'views';
			}

			$btns     = zm_get_option( 'ajax_layout_code_b_btn' ) ? be_cat_btn() : 'no';
			$children = ( zm_get_option( 'ajax_layout_code_b_chil' ) == 'false' ) ? 'false' : 'true';
			echo do_shortcode( '[be_ajax_post posts_per_page="' . zm_get_option( 'ajax_layout_code_b_n' ) . '" column="' . zm_get_option( 'ajax_layout_code_b_f' ) . '" style="grid" cat="' . get_query_var( 'cat' ) . ',' . be_subcat_id() . '" btn="' . $btns . '" btn_all= "no" more="' . zm_get_option( 'nav_btn_b' ) . '" infinite="' . zm_get_option( 'more_infinite_b' ) . '" meta_key="' . $meta_key . '" orderby="' . $orderby . '" children="' . $children . '" order="DESC"]' );
		?>
	</main>
	<div class="clear"></div>
</div>
<?php if ( zm_get_option( 'ajax_layout_code_b_r' ) ) { ?>
<?php get_sidebar(); ?>
<?php } ?>
<?php get_footer(); ?>