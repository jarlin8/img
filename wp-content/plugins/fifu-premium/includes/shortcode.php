<?php

function fifu_shortcode_id($atts) {
    if (isset($atts['post_id']))
        return $atts['post_id'];

    global $post;
    return $post->ID;
}

// [fifu post_id="123"]
function fifu_shortcode_main_url($atts) {
    return '<img src="' . fifu_main_image_url(fifu_shortcode_id($atts), true) . '">';
}

add_shortcode('fifu', 'fifu_shortcode_main_url');

// [fifu_slider post_id="123"]
function fifu_shortcode_slider($atts) {
    return fifu_slider_get_html(fifu_shortcode_id($atts), null, null, null, null, null);
}

add_shortcode('fifu_slider', 'fifu_shortcode_slider');

// [fifu_gallery post_id="123"]
function fifu_shortcode_gallery($atts) {
    fifu_add_lightslider(true);
    return fifu_gallery_get_html(
            fifu_shortcode_id($atts), null,
            'fifu-woo-gallery',
            ''
    );
}

add_shortcode('fifu_gallery', 'fifu_shortcode_gallery');

// [fifu_form_image post_id="123"]
function fifu_shortcode_form_image($atts) {
    $strings = fifu_get_strings_shortcode();
    $placeholder = $strings['placeholder']['image']();
    $label = $strings['label']['image']();

    $post_id = fifu_shortcode_id($atts);

    if (!$post_id)
        return;

    return ("
        <script>
            jQuery(document).ready(function ($) {
                jQuery('#fifu-form-input-image-url').on('change', function () {
                    url = jQuery(this).val();
                    fifuSetImageUrl(url);
                });
            });

            function fifuSetImageUrl(url) {
                jQuery.ajax({
                    method: 'POST',
                    url: fifuImageVars.fifu_rest_url + 'fifu-premium/v2/form-set-image-url/',
                    data: {
                        'image_url': url,
                        'post_id': {$post_id}
                    },
                    async: true,
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-WP-Nonce', fifuImageVars.fifu_nonce);
                    },
                    success: function (data) {
                        data = JSON.parse(data);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR);
                        console.log(textStatus);
                        console.log(errorThrown);
                    }
                });
            }
        </script>

        <div id='fifu-form-image'>
            <form action='javascript:void(0)'>
                <!--label for='fifu-form-input-image-url'>{$label}</label-->
                <input type='text' id='fifu-form-input-image-url' name='fifu-form-input-image-url' placeholder='{$placeholder}'>
            </form>
        </div>
    ");
}

add_shortcode('fifu_form_image', 'fifu_shortcode_form_image');

add_action('rest_api_init', function () {
    if (fifu_is_on('fifu_shortform')) {
        register_rest_route('fifu-premium/v2', '/form-set-image-url/', array(
            'methods' => 'POST',
            'callback' => 'fifu_api_form_save_image_url',
            'permission_callback' => 'fifu_is_user_logged_in',
        ));
    }
});

function fifu_is_user_logged_in() {
    return is_user_logged_in();
}

function fifu_api_form_save_image_url(WP_REST_Request $request) {
    $post_id = $request['post_id'];
    $image_url = $request['image_url'];
    fifu_dev_set_image($post_id, $image_url);
    return json_encode(array());
}

// https://developer.wordpress.org/reference/functions/current_user_can/
// https://developer.wordpress.org/reference/functions/map_meta_cap/
