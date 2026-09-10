<?php

global $post;

if (fifu_is_rey_active()) {
    echo fifu_gallery_get_html(
            $post->ID, null,
            'fifu-woo-gallery woocommerce-product-gallery--with-images woocommerce-product-gallery--columns-4 images',
            ''
    );
} else {
    echo fifu_gallery_get_html(
            $post->ID, null,
            'fifu-woo-gallery woocommerce-product-gallery woocommerce-product-gallery--with-images woocommerce-product-gallery--columns-4 images',
            ''
    );
}
