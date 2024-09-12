<?php

function fifu_upload_image($post_id, $url, $alt, $is_category) {
    require_once(ABSPATH . '/wp-load.php');
    require_once(ABSPATH . '/wp-admin/includes/image.php');
    require_once(ABSPATH . '/wp-admin/includes/file.php');
    require_once(ABSPATH . '/wp-admin/includes/media.php');

    if (strpos($url, ".fifu.app") !== false)
        return null;

    if (get_option('fifu_ck'))
        return null;

    if (fifu_from_google_drive($url))
        $url = fifu_get_final_google_drive_url($url);

    $att_id_md5 = fifu_db_get_thumbnail_id_by_md5($url);
    if ($att_id_md5) {
        if (get_post($att_id_md5))
            return $att_id_md5;
        fifu_db_delete_md5_by_thumbnail_id($att_id_md5);
    }

    if (fifu_is_base64($url)) {
        $tmp = get_temp_dir() . date("Ymd-His") . '.jpg';
        file_put_contents($tmp, file_get_contents($url));
    } else {
        $tmp = fifu_is_on('fifu_upload_proxy') ? fifu_proxy_download($url, false) : download_url($url);
        // $tmp = fifu_is_on('fifu_upload_proxy') ? fifu_proxy_download($url, false) : download_url(FIFU_PROXY_TRANSLATE . $url);
    }

    if (!$tmp)
        return null;

    if (!$alt && fifu_is_on('fifu_dynamic_alt'))
        $alt = get_the_title($post_id);

    $desc = $alt;
    $file_array = array();
    $file_array['name'] = ($alt ? sanitize_title($alt) : date("Ymd-His")) . '.jpg';
    $file_array['tmp_name'] = $tmp;
    if (is_wp_error($tmp)) {
        @unlink($file_array['name']);
        return null;
    }

    $att_id = media_handle_sideload($file_array, $post_id, $desc);
    if (is_wp_error($att_id)) {
        @unlink($file_array['tmp_name']);
        return $att_id;
    }

    fifu_db_insert_md5($url, $att_id);

    return $att_id;
}

function fifu_upload_all_images($is_cron) {
    // post type
    $result = fifu_db_get_posts_types_with_url();

    if (!$result)
        error_log('Posts: no URLs found.');

    foreach ($result as $res) {
        if ($is_cron && fifu_is_off('fifu_upload_job'))
            return;

        if (fifu_should_stop_job('fifu_upload_job'))
            return;

        if ($is_cron)
            set_transient('fifu_upload_semaphore', new DateTime(), 0);

        $post_id = $res->post_id;
        $post_type = $res->post_type;

        $url = get_post_meta($post_id, 'fifu_image_url', true);
        $alt = get_post_meta($post_id, 'fifu_image_alt', true);
        if (!$url)
            continue;

        if (fifu_upload_skip_url($url))
            continue;

        try {
            /* featured image */
            error_log("[{$post_id}]: {$url}");
            $att_id = fifu_upload_image($post_id, $url, $alt, false);
            if (!$att_id || is_wp_error($att_id)) {
                error_log('ERROR: fifu_upload_image(' . $post_id . ')');
                continue;
            }
            update_post_meta($att_id, '_wp_attachment_image_alt', $alt);
            wp_update_post(array('ID' => $att_id, 'post_content' => $url));

            /* gallery */
            $error = false;
            $i = 0;
            $gallery = fifu_db_get_image_gallery_urls($post_id);
            $att_ids = '';
            foreach ($gallery as $item) {
                $id = explode('_', $item->meta_key)[3];
                $gal_url = $item->meta_value;
                $gal_alt = get_post_meta($post_id, 'fifu_image_alt_' . $id, true);
                $gal_att_id = fifu_upload_image($post_id, $gal_url, $gal_alt, false);
                if (!$gal_att_id || is_wp_error($gal_att_id)) {
                    error_log('ERROR: fifu_upload_image(' . $post_id . ')');
                    $error = true;
                    break;
                }
                update_post_meta($gal_att_id, '_wp_attachment_image_alt', $gal_alt);
                wp_update_post(array('ID' => $gal_att_id, 'post_content' => $gal_url));
                $att_ids .= ($i++ == 0) ? $gal_att_id : ',' . $gal_att_id;
            }

            if ($error)
                continue;
        } catch (Exception $e) {
            error_log($e->getMessage());
            error_log('ERROR: fifu_upload_image(' . $post_id . ')');
            continue;
        }

        /* featured image */
        set_post_thumbnail($post_id, $att_id);
        delete_post_meta($post_id, 'fifu_image_url');
        delete_post_meta($post_id, 'fifu_image_alt');
        fifu_db_update_fake_attach_id($post_id);

        /* gallery */
        foreach ($gallery as $item) {
            $id = explode('_', $item->meta_key)[3];
            delete_post_meta($post_id, $item->meta_key);
            delete_post_meta($post_id, 'fifu_image_alt_' . $id);
        }
        update_post_meta($post_id, '_product_image_gallery', $att_ids);

        /* additional */
        if ($post_type == 'product_variation')
            update_post_meta($post_id, '_wc_additional_variation_images', $att_ids);
    }

    // category
    $result = fifu_db_get_terms_with_url();
    foreach ($result as $res) {
        if ($is_cron)
            set_transient('fifu_upload_semaphore', new DateTime(), 0);

        $term_id = $res->term_id;

        $url = get_term_meta($term_id, 'fifu_image_url', true);
        $alt = get_term_meta($term_id, 'fifu_image_alt', true);
        if (!$url)
            continue;

        if (fifu_upload_skip_url($url))
            continue;

        try {
            $att_id = fifu_upload_image(null, $url, $alt, true);
            if (!$att_id || is_wp_error($att_id)) {
                error_log('ERROR: fifu_upload_image(' . $post_id . ')');
                continue;
            }
            update_post_meta($att_id, '_wp_attachment_image_alt', $alt);
            wp_update_post(array('ID' => $att_id, 'post_content' => $url));
            delete_term_meta($term_id, 'fifu_image_url');
            delete_term_meta($term_id, 'fifu_image_alt');
            fifu_db_ctgr_update_fake_attach_id($term_id);
            update_term_meta($term_id, 'thumbnail_id', $att_id);
        } catch (Exception $e) {
            error_log($e->getMessage());
            error_log('ERROR: fifu_upload_image(' . $post_id . ')');
            continue;
        }
    }
}

function fifu_crop_image($att_id, $new_height, $post_id, $desc) {
    $sizes = wp_get_attachment_image_src($att_id, 'full');
    $width = $sizes[1];
    $height = $sizes[2];
    $path = wp_crop_image($att_id, 0, 0, $width, $new_height, $width, $new_height);

    $file_array = array();
    $file_array['name'] = date("Ymd-His") . '.jpg';
    $file_array['tmp_name'] = $path;
    if (is_wp_error($path)) {
        @unlink($file_array['name']);
        return null;
    }
    $new_att_id = media_handle_sideload($file_array, $post_id, $desc);
    if (is_wp_error($new_att_id)) {
        @unlink($file_array['tmp_name']);
        return $new_att_id;
    }
    wp_delete_attachment($att_id);
    return $new_att_id;
}

function fifu_resize_image($post_id, $att_id, $desc, $width) {
    $path = wp_get_original_image_path($att_id);

    $file_array = array();
    $file_array['name'] = date("Ymd-His") . '.jpg';
    $file_array['tmp_name'] = $path;

    $image = wp_get_image_editor($path, array());
    if (!is_wp_error($image)) {
        $image->resize($width, null, true);
        $image->save($path);
    }

    $new_att_id = media_handle_sideload($file_array, $post_id, $desc);
    if (is_wp_error($new_att_id)) {
        @unlink($file_array['tmp_name']);
        return $new_att_id;
    }

    wp_delete_attachment($att_id);
    return $new_att_id;
}

function fifu_upload_captured_iframe($frame, $video_url) {
    $path = parse_url($video_url, PHP_URL_PATH);
    $file_name = basename($path);
    $extension = pathinfo($file_name, PATHINFO_EXTENSION);
    $new_name = str_replace('.' . $extension, '-fifu-' . $extension . '.webp', $file_name);
    $image_url = str_replace($file_name, $new_name, $video_url);

    $aux = explode('/uploads/', $path);
    $aux = explode('/', $aux[1]);
    $year = $aux[0];
    $month = $aux[1];

    $upload_dir = wp_upload_dir();
    $image_data = file_get_contents($frame);

    $upload_dir_path = "{$upload_dir['basedir']}/{$year}/{$month}";
    if (wp_mkdir_p($upload_dir_path))
        $file = "{$upload_dir_path}/{$new_name}";
    else
        $file = "{$upload_dir['basedir']}/{$new_name}";

    $att_id = post_exists(sanitize_file_name($new_name));
    if ($att_id)
        wp_delete_attachment($att_id);

    file_put_contents($file, $image_data);

    $wp_filetype = wp_check_filetype($new_name, null);
    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => sanitize_file_name($new_name),
        'post_content' => '',
        'post_status' => 'inherit'
    );
    $att_id = wp_insert_attachment($attachment, $file);
    $attach_data = wp_generate_attachment_metadata($att_id, $file);
    wp_update_attachment_metadata($att_id, $attach_data);
}

function fifu_upload_skip_url($url) {
    if (strpos($url, ".fifu.app") !== false)
        return true;

    $domains = get_option('fifu_upload_domain');
    if ($domains) {
        $skip = true;
        $domains = explode(',', $domains);
        foreach ($domains as $domain) {
            if (strpos($url, $domain) !== false) {
                $skip = false;
                break;
            }
        }
        return $skip;
    }
    return false;
}

function fifu_upload_video_thumbnail($video_url, $thumb_url) {
    require_once(ABSPATH . '/wp-load.php');
    require_once(ABSPATH . '/wp-admin/includes/image.php');
    require_once(ABSPATH . '/wp-admin/includes/file.php');
    require_once(ABSPATH . '/wp-admin/includes/media.php');

    $tmp = download_url($thumb_url);
    $name = null;

    if (!$tmp) {
        error_log("Failed to download URL: $thumb_url");
        return null;
    }

    if (is_wp_error($tmp)) {
        error_log($tmp->get_error_message());
        return null;
    }

    if (fifu_is_googledrive_video($video_url)) {
        $name = fifu_googledrive_id($video_url) . '.jpg';
        $slug = 'googledrive';
    } elseif (fifu_is_mega_video($video_url)) {
        $name = fifu_mega_id($video_url) . '.jpg';
        $name = str_replace('#', '-', $name);
        $slug = 'mega';
    }

    if (!$name)
        return null;

    $upload_dir = wp_upload_dir();
    $custom_subdir = "/fifu/videothumb/{$slug}";

    $custom_dir = $upload_dir['basedir'] . $custom_subdir;
    if (!file_exists($custom_dir))
        wp_mkdir_p($custom_dir);

    $file_contents = file_get_contents($tmp);
    $path = "{$custom_dir}/{$name}";
    file_put_contents($path, $file_contents);

    @unlink($tmp);

    return $upload_dir['baseurl'] . $custom_subdir . '/' . $name;
}

