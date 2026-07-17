<?php

class FifuDdg {

    function __construct() {
        $this->site = 'https://duckduckgo.com/';
        $this->headers = array(
            'authority' => 'duckduckgo.com',
            'accept' => 'application/json, text/javascript, */*; q=0.01',
            'sec-fetch-dest' => 'empty',
            'x-requested-with' => 'XMLHttpRequest',
            'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36',
            'sec-fetch-site' => 'same-origin',
            'sec-fetch-mode' => 'cors',
            'referer' => 'https://duckduckgo.com/',
            'accept-language' => 'en-US,en;q=0.9'
        );
    }

    function get_image_url($keywords, $post_id, $many) {
        $width = get_option('fifu_auto_set_width');
        $height = get_option('fifu_auto_set_height');
        $blocklist = get_option('fifu_auto_set_blocklist');
        $blocklist = $blocklist ? explode(PHP_EOL, $blocklist) : null;
        $sources = explode(',', get_option('fifu_auto_set_source'));
        $license = get_option('fifu_auto_set_license');

        $new_md5 = md5($this->concatenate_variables($keywords, $width, $height, $blocklist, $sources, $license));
        $json_arr = $post_id ? get_post_meta($post_id, 'fifu_search_proxy', true) : null;

        $keywords = $sources ? "{$keywords}" . $this->get_str_sources($sources) : $keywords;

        error_log('fifu-ddg: ' . $keywords);
        if (get_option('fifu_ck'))
            return;

        $token = $this->get_token($keywords, $license);
        if (!$token) {
            error_log('fifu-ddg: no token');
            sleep(30);
            return;
        }

        $params = array(
            'headers' => $this->headers,
            'l' => 'us-en',
            'o' => 'json',
            'q' => $keywords,
            'vqd' => $token,
            'f' => $this->get_license_param($license),
            'p' => '1',
            'v7exp' => 'a',
        );
        $js_url = $this->site . 'i.js';

        while (true) {
            while (true) {
                try {
                    $is_proxy = false;
                    $res = wp_safe_remote_get($js_url . '?' . http_build_query($params));

                    if ($res['response']['code'] == 403) {
                        if ($json_arr) {
                            $arr = json_decode($json_arr, true);
                            $old_md5 = $arr[0];
                            $attempts = ($old_md5 == $new_md5) ? $arr[1] : 1;
                        } else {
                            $attempts = 1;
                        }
                        if ($attempts < 3) {
                            $is_proxy = true;
                            $ddg_url = $js_url . '?' . http_build_query($params);
                            $res = wp_safe_remote_get('https://ddg.featuredimagefromurl.com/' . get_option('fifu_ws_key_ddg') . '?url=' . base64_encode($ddg_url));
                        }
                    }

                    if (is_wp_error($res)) {
                        sleep(30);
                        continue;
                    }
                    $data = json_decode($res['body']);
                    break;
                } catch (Exception $e) {
                    error_log('fifu-ddg: ' . $e . ':' . $res);
                    sleep(30);
                    continue;
                }
            }

            if (!isset($data->results)) {
                if ($post_id)
                    $this->update_proxy_meta($is_proxy, $json_arr, $new_md5, $post_id);

                error_log('fifu-ddg: not found');
                sleep(30);
                return null;
            }

            $results = array();
            foreach ($data->results as $res) {
                // validate width
                if ($width && $res->width < $width)
                    continue;

                // validate height
                if ($height && $res->height < $height)
                    continue;

                // validate blocklist
                if ($blocklist) {
                    $skip = false;
                    foreach ($blocklist as $block) {
                        if (strpos($res->image, trim($block)) !== false) {
                            $skip = true;
                            break;
                        }
                    }
                    if ($skip)
                        continue;
                }

                if ($post_id)
                    delete_post_meta($post_id, 'fifu_search_proxy');

                $result = array('url' => $res->image, 'width' => $res->width, 'height' => $res->height, 'author_url' => $res->url);

                if ($many) {
                    array_push($results, $result);
                } else {
                    error_log('fifu-ddg: ' . $res->image);
                    return $result;
                }
            }

            if ($many && !empty($results)) {
                return $results;
            }

            if (!isset($data->next)) {
                if ($post_id)
                    $this->update_proxy_meta($is_proxy, $json_arr, $new_md5, $post_id);

                return null;
            }

            $js_url = $this->site . $data->next;
        }
    }

    function get_token($keywords, $license) {
        $params = array('q' => $keywords, 'iaf' => $this->get_license_param($license));
        $args = array('timeout' => 30, 'sslverify' => false);
        $res = wp_safe_remote_get($this->site . '?' . http_build_query($params), $args);
        $data = json_encode($res);
        preg_match('/vqd=([\d-]+)\&/', $data, $matches);
        return $matches ? $matches[1] : null;
    }

    function get_str_sources($sources) {
        if (empty($sources))
            return '';

        $aux = '';
        $i = 0;
        foreach ($sources as $src) {
            if (!$src)
                continue;

            $aux .= ($i == 0) ? ' ' : ' | ';
            $aux .= "site:{$src}";
            $i++;
        }
        return $aux;
    }

    function get_license_param($license) {
        // https://github.com/deedy5/duckduckgo_search/blob/main/duckduckgo_search/duckduckgo_search.py
        switch ($license) {
            case 'public':
                return 'license:Public';
            case 'personal':
                return 'license:Share';
            case 'commercial':
                return 'license:ShareCommercially';
            case 'all':
            case '':
            default:
                return ',,,';
        }
    }

    function concatenate_variables($keywords, $width, $height, $blocklist, $sources, $license) {
        $concatenated_values = $keywords . $width . $height . $license;

        if (is_array($blocklist)) {
            foreach ($blocklist as $item) {
                $concatenated_values .= $item;
            }
        }

        if (is_array($sources)) {
            foreach ($sources as $item) {
                $concatenated_values .= $item;
            }
        }

        return $concatenated_values;
    }

    function update_proxy_meta($is_proxy, $json_arr, $new_md5, $post_id) {
        if ($is_proxy) {
            if ($json_arr) {
                $arr = json_decode($json_arr, true);
                if (is_array($arr) && count($arr) >= 2) {
                    $old_md5 = $arr[0];
                    $attempts = ($old_md5 == $new_md5) ? $arr[1] + 1 : 1;
                } else {
                    $attempts = 1;
                }
            } else {
                $attempts = 1;
            }

            $new_arr = array($new_md5, $attempts);
            $json_arr = json_encode($new_arr);
            update_post_meta($post_id, 'fifu_search_proxy', $json_arr);
        }
    }

}

function fifu_ddg_search($post_title, $post_id, $many) {
    $ddg = new FifuDdg();
    return $ddg->get_image_url($post_title, $post_id, $many);
}

