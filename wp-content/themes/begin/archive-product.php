<?php
defined( 'ABSPATH' ) || exit;
get_header( 'shop' );
do_action( 'woocommerce_before_main_content' );
if ( ! is_shop() ) {
	archive_img();
}
?>
<header class="woocommerce-products-header">
	<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
		<h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
	<?php endif; ?>

	<?php
	do_action( 'woocommerce_archive_description' );
	?>
</header>
<?php
if ( woocommerce_product_loop() ) {
	do_action( 'woocommerce_before_shop_loop' );
	woocommerce_product_loop_start();
	if ( wc_get_loop_prop( 'total' ) ) {
		while ( have_posts() ) {
			the_post();
			do_action( 'woocommerce_shop_loop' );
			echo '<span class="woo-flex-box be-woo-area woo-flex-' . zm_get_option('woo_f' ). '"';
			echo aos_a();
			echo '>';
			wc_get_template_part( 'content', 'product' );
			edit_post_link('<i class="be be-editor"></i>', '<div class="woo-edit-link">', '</div>' );
			echo '</span>';
		}
	}

	woocommerce_product_loop_end();
	do_action( 'woocommerce_after_shop_loop' );
} else {
	do_action( 'woocommerce_no_products_found' );
}
do_action( 'woocommerce_after_main_content' );
get_footer( 'shop' );