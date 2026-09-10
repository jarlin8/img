<?php

add_filter('wp_insert_post_data', 'fifu_remove_first_image_ext', 10, 2);

function fifu_remove_first_image_ext($data, $postarr) {
    /* invalid or internal or ignore */
    if (isset($_POST['fifu_input_url']) || isset($_POST['fifu_ignore_auto_set']))
        return $data;

    $post_id = $postarr['ID'];
    if (fifu_has_local_featured_image($post_id) || !fifu_is_valid_cpt($post_id))
        return $data;

    $content = $postarr['post_content'];
    if (!$content)
        return $data;

    $contentClean = fifu_show_all_images($content);
    $contentClean = fifu_show_all_videos($contentClean);
    $data['post_content'] = str_replace($content, $contentClean, $data['post_content']);

    $img = fifu_first_img_in_content($contentClean);
    $video = fifu_first_video_in_content($contentClean);

    // there is no iframe
    if (fifu_is_on('fifu_get_first') && $video && strpos($video, 'iframe') === false) {
        if (!$img || ($img && fifu_is_on('fifu_video_priority')))
            return $data;
    }

    if (!$img && !$video)
        return $data;

    if ($img && $video)
        $media = fifu_is_on('fifu_video_priority') ? $video : $img;
    else
        $media = $img ? $img : $video;

    if (fifu_is_off('fifu_pop_first')) {
        $data['post_content'] = str_replace($media, fifu_show_media($media), $data['post_content']);
        return $data;
    }

    $data['post_content'] = str_replace($media, fifu_hide_media($media), $data['post_content']);
    return $data;
}

add_action('save_post', 'fifu_save_properties_ext');

function fifu_save_properties_ext($post_id) {
    if (isset($_POST['fifu_input_url']))
        return;

    $first = fifu_first_url_in_content($post_id, null, false);
    $image_url = $first ? esc_url_raw(rtrim($first)) : null;

    $first = fifu_first_url_in_content($post_id, null, true);
    $video_url = $first ? esc_url_raw(rtrim($first)) : null;

    if ($image_url && $video_url && fifu_is_on('fifu_video_priority'))
        return;

    if ((!isset($_POST['action']) || $_POST['action'] != 'elementor_ajax') && $image_url && fifu_is_on('fifu_get_first') && !fifu_has_local_featured_image($post_id) && fifu_is_valid_cpt($post_id)) {
        update_post_meta($post_id, 'fifu_image_url', fifu_convert($image_url));
        fifu_db_update_fake_attach_id($post_id);
        return;
    }

    if (!$image_url && get_option('fifu_default_url') && fifu_is_on('fifu_enable_default_url')) {
        if (fifu_is_valid_default_cpt($post_id))
            fifu_db_update_fake_attach_id($post_id);
    }
}

add_action('save_post', 'fifu_save_properties_video_ext');

function fifu_save_properties_video_ext($post_id) {
    if (isset($_POST['fifu_video_input_url']))
        return;

    $first = fifu_first_url_in_content($post_id, null, false);
    $image_url = $first ? esc_url_raw(rtrim($first)) : null;

    $first = fifu_first_url_in_content($post_id, null, true);
    $video_url = $first ? esc_url_raw(rtrim($first)) : null;

    if ($image_url && $video_url && fifu_is_off('fifu_video_priority'))
        return;

    if ($video_url && fifu_is_on('fifu_video') && fifu_is_on('fifu_get_first') && !fifu_has_local_featured_image($post_id) && fifu_is_valid_cpt($post_id)) {
        update_post_meta($post_id, 'fifu_video_url', $video_url);
        fifu_db_update_fake_attach_id($post_id);
    }
}

function fifu_first_img_in_content($content) {
    $content = fifu_is_on('fifu_decode') ? html_entity_decode($content) : $content;

    $nth = get_option('fifu_spinner_nth') - 1;

    preg_match_all('/<img[^>]*>/', $content, $matches);
    if ($matches && $matches[0]) {
        $skip_list = get_option('fifu_skip');
        if (!$skip_list)
            return $matches[0][$nth];

        return fifu_skip_urls($skip_list, $matches[0], $nth);
    }

    return null;
}

function fifu_skip_urls($skip_list, $img_list, $nth) {
    $i = 0;
    foreach ($img_list as $img) {
        if ($i < $nth) {
            $i++;
            continue;
        }

        $skip = false;
        foreach (explode(',', $skip_list) as $word) {
            if (strpos($img, $word) !== false) {
                $skip = true;
                break;
            }
        }

        if ($skip) {
            $i++;
            continue;
        }
        return $img_list[$i];
    }
    return null;
}

function fifu_first_video_in_content($content) {
    $content = fifu_is_on('fifu_decode') ? html_entity_decode($content) : $content;
    $matches = array();
    // iframe
    preg_match_all('/<iframe[^>]*(youtu|vimeo|cloudinary|tumblr|publit|9cache|odysee)[^>]*>/', $content, $matches);
    if ($matches && $matches[0])
        return $matches[0][0];

    // no tag (just a Youtube URL)
    preg_match("/(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user|shorts)\/))([^ \\\?&\"'><\[\n]+)/", $content, $matches);
    if ($matches && $matches[0])
        return $matches[0];

    // no tag (just a Vimeo URL)
    preg_match("/https:\/\/vimeo.com\/[0-9]+[\/]?([0-9a-z]+[\/]?)?/", $content, $matches);
    if ($matches && $matches[0])
        return $matches[0];

    // no tag (just a 9GAG URL)
    preg_match("/https:\/\/[^ \"]+9cache.com[^ \"]+mp4/", $content, $matches);
    if ($matches && $matches[0])
        return $matches[0];

    return null;
}

function fifu_show_all_images($content) {
    $content = fifu_is_on('fifu_decode') ? html_entity_decode($content) : $content;
    $matches = array();
    preg_match_all('/<img[^>]*display:[ ]*none[^>]*>/', $content, $matches);
    foreach ($matches[0] as $img) {
        $content = str_replace($img, fifu_show_media($img), $content);
    }
    return $content;
}

function fifu_show_all_videos($content) {
    $content = fifu_is_on('fifu_decode') ? html_entity_decode($content) : $content;
    $matches = array();
    preg_match_all('/<iframe[^>]*(youtu|vimeo|cloudinary|tumblr|publit|9cache)[^>]*display:[ ]*none[^>]*>/', $content, $matches);
    foreach ($matches[0] as $video) {
        $content = str_replace($video, fifu_show_media($video), $content);
    }
    return $content;
}

function fifu_hide_media($img) {
    $img = fifu_is_on('fifu_decode') ? html_entity_decode($img) : $img;
    $img = stripslashes($img);
    if (strpos($img, 'style=') !== false)
        return preg_replace('/style=[\'\"][^\'\"]*[\'\"]/', 'style="display:none"', $img);
    return preg_replace('/[\/]*>/', ' style="display:none">', $img);
}

function fifu_show_media($img) {
    $img = fifu_is_on('fifu_decode') ? html_entity_decode($img) : $img;
    return preg_replace('/style=[\\\]*.display:[ ]*none[\\\]*./', '', $img);
}

function fifu_first_url_in_content($post_id, $content, $is_video) {
    $content = $content ? $content : get_post_field('post_content', $post_id);
    $content = fifu_is_on('fifu_decode') ? html_entity_decode($content) : $content;
    if (!$content)
        return;

    $matches = array();

    if ($is_video) {
        // iframe
        preg_match_all('/<iframe[^>]*(youtu|vimeo|cloudinary|tumblr|publit|9cache|odysee)[^>]*>/', $content, $matches);

        // no tag
        if (!$matches[0]) {
            // youtube
            preg_match("/(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user|shorts)\/))([^ \\\?&\"'><\[\n]+)/", $content, $matches);
            if (sizeof($matches) > 0)
                return $matches[0];

            // vimeo
            preg_match("/https:\/\/vimeo.com\/[0-9]+[\/]?([0-9a-z]+[\/]?)?/", $content, $matches);
            if (sizeof($matches) > 0)
                return $matches[0];

            // 9GAG
            preg_match("/https:\/\/[^ \"]+9cache.com[^ \"]+mp4/", $content, $matches);
            if (sizeof($matches) > 0)
                return $matches[0];
        }
    } else
        preg_match_all('/<img[^>]*>/', $content, $matches);

    if (sizeof($matches) == 0)
        return;

    $nth = get_option('fifu_spinner_nth');

    // $matches
    $tag = null;
    if (sizeof($matches) != 0) {
        $i = 0;
        foreach ($matches[0] as $tag) {
            $i++;
            if (($tag && strpos($tag, 'data:image/jpeg') !== false) || ($i < $nth))
                continue;

            // skip
            $skip_list = get_option('fifu_skip');
            if ($skip_list) {
                $skip = false;
                foreach (explode(',', $skip_list) as $word) {
                    if (strpos($tag, $word) !== false) {
                        $skip = true;
                        break;
                    }
                }
                if ($skip)
                    continue;
            }

            break;
        }
    }

    if (!$tag)
        return null;

    // src
    $src = fifu_get_attribute('src', $tag);

    //query strings
    if (fifu_is_on('fifu_query_strings'))
        return preg_replace('/\?.*/', '', $src);

    return $src;
}

// WordPress >= 5.6
// add_action('wp_after_insert_post', 'fifu_wp_after_insert_post', 10, 3);
// function fifu_wp_after_insert_post($post, $update, $post_before) {
//     $post = get_post($post);
//     if (!$post)
//         return;
//     $post_id = $post->ID;
// }

add_action('aawp_post_insert_product', 'fifu_aawp_post_insert_product', 10, 2);

function fifu_aawp_post_insert_product($aawp_id, $data) {
    global $post;
    $post_id = $post->ID;
    if (fifu_is_aawp_active() && !fifu_has_local_featured_image($post_id)) {
        $url = fifu_get_url_from_aawp($post_id);
        if ($url) {
            fifu_update_or_delete($post_id, 'fifu_image_url', $url);
            fifu_db_update_fake_attach_id($post_id);
            return;
        }
    };
}

function fifu_update_fake_attach_id($post_id) {
    fifu_db_update_fake_attach_id($post_id);
    fifu_db_update_fake_attach_id_gallery($post_id);
}

add_action('added_post_meta', 'fifu_after_post_meta', 10, 4);

function fifu_after_post_meta($meta_id, $post_id, $meta_key, $meta_value) {
    if ('fifu_list_url' == $meta_key && isset($_REQUEST['wp_automatic']))
        fifu_dev_set_image_list($post_id, str_replace(',', '|', $meta_value));
}

add_action('updated_option', 'fifu_after_update_option', 10, 3);

function fifu_after_update_option($option_name, $old_value, $new_value) {
    $field = 'fifu_';
    if ($option_name == $field . 'email' && $new_value == base64_decode('bWFpbEBnbWFpbC5jb20='))
        update_option($field . 'email', str_repeat("*", 14));
}

