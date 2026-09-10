<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// 显示数量
add_filter( 'loop_shop_per_page', 'be_loop_shop_per_page', 20 );
function be_loop_shop_per_page( $cols ) {
	$cols = cx_get_option( 'woo_cols_n' );
	return $cols;
}

// 相关数量
add_filter( 'woocommerce_output_related_products_args', 'be_number_related_products' );
function be_number_related_products( $args ) {
	$args['posts_per_page'] = cx_get_option( 'woo_related_n' );
	$args['columns'] = 4;
	$args['orderby'] = 'rand';
	return $args;
}

// 默认图片
add_filter('woocommerce_placeholder_img_src', 'be_placeholder_img_src');
function be_placeholder_img_src( $src ) {
	$upload_dir = wp_upload_dir();
	$uploads = untrailingslashit( $upload_dir['baseurl'] );
	$src = cx_get_option( 'woo_thumbnail' );
	return $src;
}

// 商店页面默认图片
add_filter('woocommerce_before_main_content', 'shop_headerimg');
function shop_headerimg() {
	if ( is_shop() ) {
		echo '<div class="header-sub">';
		echo '<div class="cat-des">';
		echo '<div class="cat-des-img"><img src="';
		echo cx_get_option( 'shop_header_img' );
		echo '"></div>';
		echo '</div>';
		echo '</div>';
	}
}

// 禁用可变产品价格范围
add_filter( 'woocommerce_variable_sale_price_html', 'bewoo_variation_price_format', 10, 2 );
add_filter( 'woocommerce_variable_price_html', 'bewoo_variation_price_format', 10, 2 );

function bewoo_variation_price_format( $price, $product ) {
	// 最高
	$prices = array( $product->get_variation_price( 'min', true ),
	$product->get_variation_price( 'max', true ) );
	$price = $prices[0] !== $prices[1] ? sprintf( __( '%1$s', 'woocommerce' ), wc_price( $prices[0] ) ) : wc_price( $prices[0] );

	// 最低
	$prices = array( $product->get_variation_regular_price( 'min', true ), $product->get_variation_regular_price( 'max', true ) );
	sort( $prices );
	$saleprice = $prices[0] !== $prices[1] ? sprintf( __( '%1$s', 'woocommerce' ), wc_price( $prices[0] ) ) : wc_price( $prices[0] );

	if ( $price !== $saleprice ) {
		$price = '<del>' . $saleprice . '</del> <ins>' . $price . '</ins>';
	}
	return $price;
}