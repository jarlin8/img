<?php

function fifu_woo_zoom() {
    return fifu_is_on('fifu_wc_zoom') ? 'inline' : 'none';
}

function fifu_woo_lbox() {
    return fifu_is_on('fifu_wc_lbox');
}

function fifu_woo_theme() {
    return file_exists(get_template_directory() . '/woocommerce');
}

# https://docs.woocommerce.com/document/image-sizes-theme-developers/

function fifu_woo_get_image_size() {
    if (class_exists('WooCommerce')) {
        if (is_shop())
            return wc_get_image_size('woocommerce_get_image_size_woocommerce_thumbnail');
        if (is_product())
            return wc_get_image_size('woocommerce_get_image_size_woocommerce_single');
    }
}

define('FIFU_FIX_IMAGES_WITHOUT_DIMENSIONS',
        "function fix_images_without_dimensions() {
        jQuery('img[data-large_image_height=0]').each(function () {
            if (jQuery(this)[0].naturalWidth <= 2)
                return;

            jQuery(this)
                .attr('data-large_image_width', jQuery(this)[0].naturalWidth)
                .attr('data-large_image_height', jQuery(this)[0].naturalHeight);

            jQuery('div.flex-viewport').css('height', jQuery(this)[0].clientHeight);
        });
    }
    fix_images_without_dimensions();"
);

function fifu_woocommerce_gallery_image_html_attachment_image_params($params, $attachment_id, $image_size, $main_image) {
    // fix zoom
    if ($params['data-large_image_width'] == 0) {
        $params['data-large_image_width'] = 1920;
        $params['data-large_image_height'] = 0;
    }

    // fix lightbox
    if (is_product())
        $params['onload'] = FIFU_FIX_IMAGES_WITHOUT_DIMENSIONS;

    return $params;
}

add_filter('woocommerce_gallery_image_html_attachment_image_params', 'fifu_woocommerce_gallery_image_html_attachment_image_params', 10, 4);

function fifu_woo_template_override($template, $slug) {
    global $post;

    $product_page = array('single-product/product-image.php');

    if (fifu_is_on('fifu_gallery') && $post && class_exists('WooCommerce') && is_product() && !fifu_is_elementor_editor()) {
        if (in_array($slug, $product_page)) {
            // if (fifu_is_yith_woocommerce_badges_management_active())
            echo apply_filters('woocommerce_single_product_image_thumbnail_html', '', $post->ID);
            return FIFU_INCLUDES_DIR . '/template.php';
        }
    }
    return $template;
}

add_filter('wc_get_template', 'fifu_woo_template_override', 99, 2);

function fifu_in_gallery($att_id) {
    $att_post = get_post($att_id);
    $post_parent = get_post($att_post->post_parent);
    if (!isset($post_parent->ID))
        return false;
    $gallery_ids = get_post_meta($post_parent->ID, '_product_image_gallery', true);
    if ($gallery_ids)
        $gallery_ids = explode(',', $gallery_ids);
    if (is_array($gallery_ids))
        return in_array($att_id, $gallery_ids);
    return false;
}

add_action('woocommerce_product_duplicate', 'fifu_woocommerce_product_duplicate', 10, 1);

function fifu_woocommerce_product_duplicate($array) {
    if (!$array || !$array->get_meta_data())
        return;

    $post_id = $array->get_id();
    foreach ($array->get_meta_data() as $meta_data) {
        $data = $meta_data->get_data();
        if (in_array($data['key'], array('fifu_image_url', 'fifu_video_url', 'fifu_slider_image_url_0'))) {
            delete_post_meta($post_id, '_thumbnail_id');
        } else if (
                (strpos($data['key'], 'fifu_image_url_') !== false) ||
                (strpos($data['key'], 'fifu_video_url_') !== false) ||
                (strpos($data['key'], 'fifu_slider_image_url_') !== false)) {
            delete_post_meta($post_id, '_product_image_gallery');
        }
    }
}

function fifu_gallery_get_html($post_id, $original_class, $gallery_class, $gallery_css) {
    $ratio = get_post_meta($post_id, 'fifu_slider_ratio', true);
    $ratio = $ratio ? 'fifu-ratio="' . $ratio . '"' : '';

    $class = fifu_is_lazy() ? "fifu lazyload" : "fifu";
    $class .= ' ' . $original_class;

    $attribute_map = array();
    $url_map = array();
    $srcset_map = array();

    // variable products
    $attributes = fifu_db_get_variation_attributes($post_id);
    if ($attributes) {
        foreach ($attributes as $attribute) {
            if (!isset($attribute_map[$attribute->meta_key])) {
                $attribute_map[$attribute->meta_key] = array();
            }
            if (!isset($attribute_map[$attribute->meta_key][$attribute->meta_value])) {
                $attribute_map[$attribute->meta_key][$attribute->meta_value] = array();
            }
            array_push($attribute_map[$attribute->meta_key][$attribute->meta_value], $attribute->post_id);

            if (!isset($url_map[$attribute->post_id])) {
                $aux = fifu_db_get_featured_and_gallery_urls($attribute->post_id);
                if ($aux) {
                    $urls = $aux[0]->urls;
                    $tmp = $urls ? explode('|', $urls) : array();
                    for ($i = 0; $i < sizeof($tmp); $i++) {
                        $tmp[$i] = fifu_get_cdn_url($tmp[$i]);
                        $url = $tmp[$i];
                        if (is_from_jetpack($url) && fifu_is_lazy())
                            $srcset_map[$url] = fifu_jetpack_get_set($url, true);
                    }
                    $url_map[$attribute->post_id] = $tmp;
                }
            }
        }
    }

    $gallery_css = $gallery_css ? 'style="' . $gallery_css . '"' : '';

    $lightbox_icon = true ? '' : '<i class="fas fa-expand" style="position: absolute;top: 5px;left: 5px;z-index: 1000;color: white;background-color: rgba(0, 0, 0, 0.3);padding: 10px;border-radius: 3px;"></i>';

    $html = sprintf('<div class="fifu-slider %s" id="fifu-slider-%s" %s %s>%s', $gallery_class, $post_id, $ratio, $gallery_css, $lightbox_icon);
    if (fifu_is_on('fifu_slider_counter'))
        $html = $html . '<div style="font-size:12px; padding:2px 5px 2px 5px; background:rgba(0, 0, 0, 0.1); z-index:50; position:absolute; color:white" id="counter-slider"></div>';
    $html = $html . '<ul id="image-gallery" class="gallery list-unstyled cS-hidden fifu-product-gallery">';

    $att_id = get_post_meta($post_id, '_thumbnail_id', true);
    $url = fifu_get_full_image_url($att_id);
    $url = fifu_get_cdn_url($url);
    if (is_from_jetpack($url) && fifu_is_lazy())
        $srcset_map[$url] = fifu_jetpack_get_set($url, true);
    $urls = array($url);
    $image_urls = array();
    $video_urls = array();

    $att_ids = get_post_meta($post_id, '_product_image_gallery', true);
    if ($att_ids) {
        $att_ids = explode(',', $att_ids);
        foreach ($att_ids as $att_id) {
            $url = fifu_get_full_image_url($att_id);
            $url = fifu_get_cdn_url($url);
            if (is_from_jetpack($url) && fifu_is_lazy())
                $srcset_map[$url] = fifu_jetpack_get_set($url, true);
            if (fifu_is_video_thumb($url))
                array_push($video_urls, $url);
            else
                array_push($image_urls, $url);
        }
    }

    if (fifu_is_on("fifu_videos_before")) {
        $urls = array_merge($urls, $video_urls);
        $urls = array_merge($urls, $image_urls);
    } else {
        $urls = array_merge($urls, $image_urls);
        $urls = array_merge($urls, $video_urls);
    }

    // urls of parent product
    $url_map[$post_id] = $urls;

    // js
    wp_enqueue_script('fifu-variable-js', plugins_url('/html/js/variable.js', __FILE__), array('jquery'), fifu_version_number());
    wp_localize_script('fifu-variable-js', 'fifuVariableVars', [
        'attribute_map' => $attribute_map,
        'url_map' => $url_map,
        'srcset_map' => $srcset_map,
        'post_id' => $post_id,
        'fifu_lazy' => fifu_is_on('fifu_lazy'),
        'fifu_video' => fifu_is_on('fifu_video'),
    ]);

    $i = -1;
    $i_video = null;
    foreach ($urls as $url) {
        $i++;
        $error_url = get_option('fifu_error_url');

        // get video URL
        if (fifu_is_video_thumb($url)) {
            if (is_null($i_video)) {
                $video_url = get_post_meta($post_id, 'fifu_video_url', true);
                if (!$video_url) {
                    $video_url = get_post_meta($post_id, 'fifu_video_url_0', true);
                    $i_video = 1;
                } else
                    $i_video = 0;
            } else
                $video_url = get_post_meta($post_id, 'fifu_video_url_' . $i_video++, true);
        }

        if ($url) {
            if (fifu_is_from_speedup($url)) {
                $signed_url = fifu_speedup_get_signed_url($url, 128, 128, null, null, false);
                $set = fifu_speedup_get_set($url);

                if (fifu_is_video($url)) {
                    $html = $html . sprintf(
                                    '<li data-thumb="%s" data-src="%s" data-srcset="%s" data-poster="%s"><img data-src="%s" data-srcset="%s" data-sizes="auto" class="%s" onerror="%s"/></li>',
                                    $signed_url,
                                    $video_url,
                                    $set,
                                    $url,
                                    $url,
                                    $set,
                                    $original_class,
                                    "jQuery(this).hide();"
                    );
                    continue;
                }

                $sizes = fifu_speedup_get_sizes($url);
                $html = $html . sprintf(
                                '<li data-thumb="%s" data-src="%s" data-srcset="%s"><img src="%s" data-src="%s" data-srcset="%s" data-sizes="auto" class="%s" onerror="%s"/></li>',
                                $signed_url,
                                FIFU_PLACEHOLDER,
                                $set,
                                fifu_get_placeholder($sizes[0], $sizes[1]),
                                $signed_url,
                                $set,
                                "fifu lazyload {$original_class}",
                                "jQuery(this).hide();"
                );
                continue;
            } else if (is_from_jetpack($url) && fifu_is_lazy()) {
                $thumbnail = fifu_resize_jetpack_image_size(175, $url);
                $set = fifu_jetpack_get_set($url, true);
                $html = $html . sprintf(
                                '<li data-thumb="%s" data-src="%s" data-srcset="%s"><img data-src="%s" data-srcset="%s" data-sizes="auto" class="%s" onerror="%s"/></li>',
                                $thumbnail,
                                FIFU_PLACEHOLDER,
                                $set,
                                $thumbnail,
                                $set,
                                $original_class,
                                "jQuery(this).hide();"
                );
                continue;
            }

            if (fifu_is_video_thumb($url)) {
                $type = 'data-src';

                // for video files
                if (fifu_is_local_video($video_url) || fifu_is_amazon_video($video_url) || fifu_is_wpcom_video($video_url)) {
                    $type = 'data-video';
                    if (fifu_is_local_video($video_url)) {
                        $extension = pathinfo($video_url, PATHINFO_EXTENSION);
                        $file_type = "video/{$extension}";
                    } else {
                        $file_type = fifu_is_amazon_video($video_url) || fifu_is_wpcom_video($video_url) ? 'video/mp4' : 'video';
                    }
                    $video_url = '{"source": [{"src":"' . $video_url . '", "type":"' . $file_type . '"}], "attributes": {"preload": false, "controls": true}}';
                }

                // for unsupported videos
                if (fifu_is_googledrive_video($video_url))
                    $video_url = fifu_googledrive_src($video_url);
                elseif (fifu_is_mega_video($video_url))
                    $video_url = fifu_mega_src($video_url);

                $html = $html . sprintf(
                                '<li data-thumb="%s" %s=\'%s\' data-poster="%s"><img %s class="img-responsive%s" onerror="%s"/></li>',
                                $url,
                                $type,
                                $video_url,
                                $url,
                                fifu_lazy_url($url),
                                $class ? ' ' . $class : '',
                                $error_url ? sprintf("this.src='%s'", $error_url) : ""
                );
            } else {
                $html = $html . sprintf(
                                '<li data-thumb="%s" data-src="%s"><img %s class="%s" onerror="%s"/></li>',
                                $url,
                                $url,
                                fifu_lazy_url($url),
                                $class,
                                $error_url ? sprintf("this.src='%s'", $error_url) : ""
                );
            }
        }
    }
    // add status
    $html = str_replace('<img ', '<img fifu-replaced="1" ', $html);
    return $html . '</ul></div>';
}

function fifu_get_cdn_url($url) {
    if (fifu_is_on('fifu_photon'))
        return fifu_jetpack_photon_url($url, null);
    return $url;
}

function fifu_woocommerce_order_item_thumbnail_filter($image, $item) {
    if (strpos($image, 'data-sizes="auto"') !== false)
        return str_replace('data-src', 'src', $image);

    return $image;
}

add_filter('woocommerce_order_item_thumbnail', 'fifu_woocommerce_order_item_thumbnail_filter', 10, 2);

function fifu_woocommerce_email_order_items_table($output, $order) {
    if (fifu_is_off('fifu_order_email'))
        return $output;

    // set a flag so we don't recursively call this filter
    static $run = 0;

    // if we've already run this filter, bail out
    if ($run)
        return $output;

    $args = array(
        'show_image' => true,
        'image_size' => array(100, 100),
    );

    $run++;

    return wc_get_email_order_items($order, $args);
}

add_filter('woocommerce_email_order_items_table', 'fifu_woocommerce_email_order_items_table', 10, 2);

function fifu_on_products_page() {
    return strpos($_SERVER['REQUEST_URI'], 'wp-admin/edit.php?post_type=product') !== false;
}

function fifu_on_categories_page() {
    return strpos($_SERVER['REQUEST_URI'], 'wp-admin/edit-tags.php?taxonomy=product_cat&post_type=product') !== false;
}

