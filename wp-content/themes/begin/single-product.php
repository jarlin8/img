<?php
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
do_action( 'woocommerce_before_main_content' );

while ( have_posts() ) :
the_post();
echo '<div class="be-woo-single be-woo-area ms"';
echo aos_a();
echo '>';
wc_get_template_part( 'content', 'single-product' );
edit_post_link('<i class="be be-editor"></i>', '<div class="woo-edit-link">', '</div>' );
echo '</div>';
endwhile;
do_action( 'woocommerce_after_main_content' );
get_sidebar('woo');
get_footer( 'shop' );