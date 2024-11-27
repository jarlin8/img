<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * category Template: Ajax瀑布流
 */
get_header(); ?>
<div class="ajax-content-area content-area">
	<main id="main" class="site-main ajax-site-main" role="main">
		<?php 
			$orderby = 'date';
			$meta_key = '';

			$order_option = zm_get_option('ajax_code_g_orderby');

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

			$btns     = zm_get_option( 'ajax_layout_code_g_btn' ) ? be_cat_btn() : 'no';
			$children = ( zm_get_option( 'ajax_layout_code_g_chil' ) == 'false' ) ? 'false' : 'true';

			echo do_shortcode( '[be_ajax_post posts_per_page="' . zm_get_option( 'ajax_layout_code_g_n' ) . '" cat="' . get_query_var( 'cat' ) . ',' . be_subcat_id() . '" btn="no" btn_all= "no" more="' . zm_get_option( 'nav_btn_g' ) . '" infinite="' . zm_get_option( 'more_infinite_g' ) . '" meta_key="' . $meta_key . '" orderby="' . $orderby . '" children="' . $children . '" style="falls" more="more" order="DESC"]' );
		?>
	</main>
	<div class="clear"></div>
</div>
<?php get_footer(); ?>