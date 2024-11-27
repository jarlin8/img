<?php

function fifu_rest_get($data, $post, $request) {
    $_data = $data->data;

    $url = get_post_meta($post->ID, 'fifu_image_url', true);
    if ($url)
        $_data['fifu_image_url'] = $url;

    $url = get_post_meta($post->ID, 'fifu_video_url', true);
    if ($url)
        $_data['fifu_video_url'] = $url;

    $i = 0;
    while (true) {
        $url = get_post_meta($post->ID, 'fifu_slider_image_url_' . $i, true);
        if (!$url)
            break;
        $_data['fifu_slider_image_url_' . $i++] = $url;
    }

    $alt = get_post_meta($post->ID, 'fifu_image_alt', true);
    if ($alt)
        $_data['fifu_image_alt'] = $alt;

    $isbn = get_post_meta($post->ID, 'fifu_isbn', true);
    if ($isbn)
        $_data['fifu_isbn'] = $isbn;

    $finder_url = get_post_meta($post->ID, 'fifu_finder_url', true);
    if ($finder_url)
        $_data['fifu_finder_url'] = $finder_url;

    $data->data = $_data;
    return $data;
}

function fifu_rest_post($post, $request, $creating) {
    $url = $request['fifu_image_url'];
    if ($url || $url === '')
        fifu_update_or_delete($post->ID, 'fifu_image_url', esc_url_raw(rtrim($url)));

    $url = $request['fifu_video_url'];
    if ($url || $url === '')
        fifu_update_or_delete($post->ID, 'fifu_video_url', esc_url_raw(rtrim($url)));

    $i = 0;
    $urls = $request['fifu_slider_list_url'];
    if ($urls) {
        $urls = explode("|", $urls);
        foreach ($urls as $url) {
            $url = esc_url_raw(trim($url));
            if ($url) {
                fifu_update_or_delete($post->ID, 'fifu_slider_image_url_' . $i, $url);
                $i++;
            }
        }
    } else {
        $i = 0;
        while (true) {
            $aux = $request['fifu_slider_image_url_' . $i];
            $url = $aux ? esc_url_raw(trim($aux)) : null;
            if (!$url)
                break;
            fifu_update_or_delete($post->ID, 'fifu_slider_image_url_' . $i++, $url);
        }
    }

    $alt = $request['fifu_image_alt'];
    if ($alt || $alt === '')
        fifu_update_or_delete_value($post->ID, 'fifu_image_alt', $alt);

    $isbn = $request['fifu_isbn'];
    if ($isbn || $isbn === '')
        fifu_update_or_delete_value($post->ID, 'fifu_isbn', $isbn);

    $finder_url = $request['fifu_finder_url'];
    if ($finder_url || $finder_url === '')
        fifu_update_or_delete($post->ID, 'fifu_finder_url', $finder_url);

    fifu_save($post->ID, false);
}

add_filter('rest_api_init', 'fifu_rest_api_init');

function fifu_rest_api_init() {
    foreach (fifu_get_post_types() as $cpt) {
        add_filter('rest_insert_' . $cpt, 'fifu_rest_post', 10, 3);
        add_filter('rest_prepare_' . $cpt, 'fifu_rest_get', 10, 3);
    }
}

