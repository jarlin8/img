<?php

function fifu_add_cron_schedules($schedules) {
    if (!isset($schedules["fifu_schedule_metadata"])) {
        $schedules['fifu_schedule_metadata'] = array(
            'interval' => 1 * 60,
            'display' => 'fifu-metadata'
        );
    }
    if (!isset($schedules["fifu_schedule_auto_set"])) {
        $schedules['fifu_schedule_auto_set'] = array(
            'interval' => 1 * 60,
            'display' => 'fifu-auto-set'
        );
    }
    if (!isset($schedules["fifu_schedule_isbn"])) {
        $schedules['fifu_schedule_isbn'] = array(
            'interval' => 1 * 60,
            'display' => 'fifu-isbn'
        );
    }
    if (!isset($schedules["fifu_schedule_finder"])) {
        $schedules['fifu_schedule_finder'] = array(
            'interval' => 1 * 60,
            'display' => 'fifu-finder'
        );
    }
    if (!isset($schedules["fifu_schedule_tags"])) {
        $schedules['fifu_schedule_tags'] = array(
            'interval' => 1 * 60,
            'display' => 'fifu-tags'
        );
    }
    if (!isset($schedules["fifu_schedule_upload"])) {
        $schedules['fifu_schedule_upload'] = array(
            'interval' => 1 * 60,
            'display' => 'fifu-upload'
        );
    }
    if (!isset($schedules["fifu_schedule_cloud_upload_auto"])) {
        $schedules['fifu_schedule_cloud_upload_auto'] = array(
            'interval' => 5 * 60,
            'display' => 'fifu-cloud-upload-auto'
        );
    }
    return $schedules;
}

add_filter('cron_schedules', 'fifu_add_cron_schedules');

function fifu_create_metadata_hook() {
    if (fifu_is_off('fifu_fake'))
        return;

    if (fifu_active_job('fifu_metadata_semaphore', 5))
        return;

    $result = fifu_db_get_all_posts_without_meta();
    foreach ($result as $res) {
        set_transient('fifu_metadata_semaphore', new DateTime(), 0);
        fifu_split_lists($res->post_id);
        fifu_update_fake_attach_id($res->post_id);
    }

    if (fifu_is_on('fifu_auto_category'))
        fifu_db_insert_auto_category_image();

    $result = fifu_db_get_categories_without_meta();
    foreach ($result as $res) {
        set_transient('fifu_metadata_semaphore', new DateTime(), 0);
        fifu_db_ctgr_update_fake_attach_id($res->term_id);
    }

    delete_transient('fifu_metadata_semaphore');
}

add_action('fifu_create_metadata_event', 'fifu_create_metadata_hook');

function fifu_create_auto_set_hook() {
    if (fifu_active_job('fifu_auto_set_semaphore', 5))
        return;

    $post_types = join("','", explode(',', get_option('fifu_auto_set_cpt')));

    $result = fifu_db_get_post_types_without_featured_image($post_types);
    foreach ($result as $res) {
        set_transient('fifu_auto_set_semaphore', new DateTime(), 0);
        $image = fifu_ddg_search($res->post_title, $res->id, false);
        if ($image) {
            if (isset($image['url']) && $image['url']) {
                delete_post_meta($res->id, 'fifu_search');
                fifu_save_image_data($res->id, $image['url'], $image['width'], $image['height']);
                fifu_update_or_delete($res->id, 'fifu_redirection_url', $image['author_url']);
            }
        } else {
            $attempts = get_post_meta($res->id, 'fifu_search', true);
            $attempts = $attempts ? $attempts : 0;
            update_post_meta($res->id, 'fifu_search', $attempts + 1);
        }
        sleep(6);
    }
    delete_transient('fifu_auto_set_semaphore');
}

add_action('fifu_create_auto_set_event', 'fifu_create_auto_set_hook');

function fifu_create_isbn_hook() {
    if (fifu_active_job('fifu_isbn_semaphore', 5))
        return;

    $result = fifu_db_get_isbns_without_featured_image();
    foreach ($result as $res) {
        set_transient('fifu_isbn_semaphore', new DateTime(), 0);
        $isbn = $res->isbn;
        if (strpos($isbn, 'not-found') !== false || strpos($isbn, 'invalid') !== false || empty($isbn))
            continue;

        $post_id = $res->post_id;

        if (!fifu_api_valid_isbn($isbn)) {
            update_post_meta($post_id, 'fifu_isbn', 'invalid:' . $isbn);
            continue;
        }

        $image_url = fifu_isbn_search($isbn);
        if ($image_url) {
            fifu_save_image_data($post_id, $image_url, null, null);

            if (get_option('fifu_isbn_custom_field') && !get_post_meta($post_id, 'fifu_isbn', true))
                update_post_meta($post_id, 'fifu_isbn', $isbn);
        } else
            update_post_meta($post_id, 'fifu_isbn', 'not-found:' . $isbn);
    }
    delete_transient('fifu_isbn_semaphore');
}

add_action('fifu_create_isbn_event', 'fifu_create_isbn_hook');

function fifu_create_finder_hook() {
    if (fifu_active_job('fifu_finder_semaphore', 5))
        return;

    $result = fifu_db_get_finders_without_featured_image();
    foreach ($result as $res) {
        if (fifu_should_stop_job('fifu_finder'))
            return;

        set_transient('fifu_finder_semaphore', new DateTime(), 0);
        $post_id = $res->post_id;
        $webpage_url = $res->webpage_url;
        if (empty($webpage_url))
            continue;

        $find_video = fifu_is_on('fifu_video_finder');

        preg_match('/[^a-z]amazon[.][a-z]+/', $webpage_url, $aux);
        $is_amazon = $aux ? true : false;

        if ($is_amazon) {
            $arr_urls = fifu_find_amazon_images($webpage_url, $post_id);
            $url = implode('|', $arr_urls['image']);
            $url_video = implode('|', $arr_urls['video']);
        } else
            $url = fifu_find_featured_image($webpage_url, $find_video);

        if (!$url) {
            delete_transient('fifu_finder_semaphore');
            delete_transient('fifu_html_code_try_curl');
            continue;
        }

        if ($find_video && fifu_is_video($url))
            fifu_dev_set_video($post_id, $url);
        else {
            if ($is_amazon) {
                if (fifu_is_off('fifu_amazon_finder')) {
                    $url = explode('|', $url)[0];
                    $url_video = null;
                }
                fifu_dev_set_image_list($post_id, $url);
                if ($url_video)
                    fifu_dev_set_video_list($post_id, $url_video);
            } else
                fifu_save_image_data($post_id, $url, null, null);
        }

        if (get_option('fifu_finder_custom_field') && !get_post_meta($post_id, 'fifu_finder_url', true))
            update_post_meta($post_id, 'fifu_finder_url', $webpage_url);
    }
    delete_transient('fifu_finder_semaphore');
}

add_action('fifu_create_finder_event', 'fifu_create_finder_hook');

function fifu_create_cloud_upload_auto_hook() {
    if (fifu_active_job('fifu_cloud_upload_auto_semaphore', 5))
        return;

    $urls = fifu_db_get_all_urls(0);
    foreach ($urls as $url) {
        if (strpos($url->meta_key, 'video') !== false) {
            $url->video_url = $url->url;
            $url->url = fifu_video_img_large($url->url, $url->post_id, $url->category);
        }
    }
    fifu_create_thumbnails_list($urls, null, true);

    delete_transient('fifu_cloud_upload_auto_semaphore');
}

add_action('fifu_create_cloud_upload_auto_event', 'fifu_create_cloud_upload_auto_hook');

function fifu_create_tags_hook() {
    if (fifu_active_job('fifu_tags_semaphore', 5))
        return;

    $size = 'featured';

    $result = fifu_db_get_tags_without_featured_image();
    foreach ($result as $res) {
        set_transient('fifu_tags_semaphore', new DateTime(), 0);
        $post_id = $res->post_id;
        $tags = $res->tags;
        if (empty($tags))
            continue;

        $response = wp_safe_remote_get('https://source.unsplash.com/' . $size . '/?' . $tags);
        if (is_wp_error($response))
            continue;

        $url = $response['http_response']->get_response_object()->url;
        if (!$url)
            continue;

        $imageSize = getImageSize($url);
        $width = $imageSize[0];
        $height = $imageSize[1];
        fifu_save_image_data($post_id, $url, $width, $height);
    }
    delete_transient('fifu_tags_semaphore');
}

add_action('fifu_create_tags_event', 'fifu_create_tags_hook');

function fifu_create_upload_hook() {
    if (fifu_active_job('fifu_upload_semaphore', 5))
        return;

    error_log('Upload job started');

    fifu_upload_all_images(true);

    delete_transient('fifu_upload_semaphore');
}

add_action('fifu_create_upload_event', 'fifu_create_upload_hook');

function fifu_save_image_data($post_id, $url, $width, $height) {
    fifu_dev_set_image($post_id, $url);
    if ($width && $height) {
        $att_id = get_post_thumbnail_id($post_id);
        fifu_save_dimensions($att_id, $width, $height);
    }
}

function fifu_save_ctgr_image_data($term_id, $url, $width, $height) {
    fifu_dev_set_category_image($term_id, $url);
    if ($width && $height) {
        $att_id = get_term_meta($term_id, 'thumbnail_id', true);
        fifu_save_dimensions($att_id, $width, $height);
    }
}

function fifu_active_job($semaphore, $minutes) {
    $date = get_transient($semaphore);
    if (!$date)
        return false;

    if (gettype($date) != 'object') {
        set_transient($semaphore, new DateTime(), 0);
        return true;
    }

    return date_diff(new DateTime(), $date)->format('%i') < $minutes;
}

function fifu_stop_job($option_name) {
    $field = $option_name . '_stop';
    update_option($field, true, 'no');
}

function fifu_should_stop_job($option_name) {
    $field = $option_name . '_stop';

    global $wpdb;
    if ($wpdb->get_col("SELECT option_value FROM " . $wpdb->options . " WHERE option_name = '" . $field . "'")) {
        delete_option($field);
        return true;
    }
    return false;
}

function fifu_run_cron_now() {
    wp_remote_request(site_url('wp-cron.php'));
}

