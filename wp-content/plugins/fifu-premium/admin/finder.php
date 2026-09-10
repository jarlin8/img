<?php

function fifu_find_featured_image($url, $find_video) {
    $html = fifu_get_html_code($url);
    if (!$html)
        return;

    if ($find_video) {
        $video_src = fifu_get_video($html, $url);
        if ($video_src)
            return $video_src;
    }

    $image_url = fifu_get_og_image($html);
    if ($image_url)
        return $image_url;

    return fifu_get_largest_img($html, $url);
}

function fifu_get_html_code($url) {
    $context = stream_context_create(
            array(
                "http" => array(
                    "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
                )
            )
    );
    try {
        // #1st try: file_get_contents
        if (!get_transient('fifu_html_code_try_curl')) {
            $html = @file_get_contents($url, false, $context);
            if ($html && !fifu_is_binary($html))
                return $html;
        }

        // #2nd attempt: curl
        if (function_exists('curl_init')) {
            set_transient('fifu_html_code_try_curl', new DateTime(), 0);
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_ENCODING, "");
            $html = fifu_curl($ch, $url);
            return $html;
        }

        return null;
    } catch (Exception $e) {
        return null;
    }
}

function fifu_get_og_image($html) {
    libxml_use_internal_errors(true);
    $doc = new DomDocument();
    $doc->loadHTML($html);
    $xpath = new DOMXPath($doc);
    $query = '//*/meta[starts-with(@property, \'og:\')]';
    $metas = $xpath->query($query);
    $rmetas = array();
    foreach ($metas as $meta) {
        $property = $meta->getAttribute('property');
        $content = $meta->getAttribute('content');
        $rmetas[$property] = $content;
    }
    if ($rmetas && isset($rmetas['og:image']))
        return $rmetas['og:image'];
    return null;
}

function fifu_get_largest_img($html, $url) {
    $dom = new DOMDocument();
    $dom->loadHTML($html);
    $imgs = $dom->getElementsByTagname('img');
    $largest = "";
    $largest_area = 0;
    foreach ($imgs as $img) {
        $img_url = $img->getAttribute("src");
        if (empty($img_url)) {
            continue;
        }
        if (substr($img_url, 0, 2) === "//") {
            $img_url = "http:" . $img_url;
        } else if (substr($img_url, 0, 1) === "/") {
            $img_url = $url . $img_url;
        }
        $size = getimagesize($img_url);
        if ($size) {
            $width = $size[0];
            $height = $size[1];
            if ($width * $height > $largest_area) {
                $largest = $img_url;
                $largest_area = $width * $height;
            }
        }
    }
    return $largest;
}

function fifu_get_video($html, $url) {
    $dom = new DOMDocument();
    $dom->loadHTML($html);
    $iframes = $dom->getElementsByTagname('iframe');
    foreach ($iframes as $iframe) {
        $src = $iframe->getAttribute("src");
        if (empty($src)) {
            continue;
        }
        if (fifu_is_video($src))
            return $src;
    }
    return null;
}

define('FIFU_PROXY_TRANSLATE', 'https://translate.google.com/translate?hl=en&sl=pt&u=');

// http://googleweblight.com/i?u=https://...

function fifu_find_amazon_images($url, $post_id) {
    libxml_use_internal_errors(true);

    $html = fifu_get_html_code(FIFU_PROXY_TRANSLATE . $url);
    sleep(6);

    $counter = get_post_meta($post_id, 'fifu_finder_counter', true);
    $counter = !$counter ? 1 : $counter + 1;
    update_post_meta($post_id, 'fifu_finder_counter', $counter);

    error_log('[' . $post_id . '] (' . $counter . '): ' . $url);

    if (!$html)
        return;

    $arr_urls = array('image' => array(), 'video' => array(), 'thumb' => array());

    // get thumbnails urls
    // $dom = new DOMDocument();
    // $dom->loadHTML($html);
    // $imgs = $dom->getElementById('altImages')->getElementsByTagname('img');
    // foreach ($imgs as $img) {
    //     $img_url = $img->getAttribute("src");
    //     if (strpos($img_url, 'media-amazon.com') !== false && strpos($img_url, 'play-icon') === false)
    //         array_push($arr_urls, $img_url);
    // }
    // get large images urls
    preg_match_all('/\[\{\"hiRes\".*\}\]/', $html, $json);
    if ($json && isset($json[0]) && isset(($json[0][0]))) {
        $images = json_decode($json[0][0]);
        foreach ($images as $img)
            array_push($arr_urls['image'], $img->hiRes);
    }

    preg_match_all('/\"videos\"\:\[\{\"[^\]]*]/', $html, $json);
    if ($json && isset($json[0]) && isset(($json[0][0]))) {
        $videos = json_decode(str_replace('"videos":', '', $json[0][0]));
        foreach ($videos as $video) {
            array_push($arr_urls['thumb'], $video->slateUrl);
            $thumb_id = explode('/', $video->slateUrl)[5];
            $thumb_id = str_replace('522', '1600', $thumb_id);
            array_push($arr_urls['video'], "{$video->url}?thumb-id={$thumb_id}");
        }
    }

    if (!$arr_urls)
        return;

    error_log(count($arr_urls['image']) . ' images found');
    error_log(count($arr_urls['video']) . ' videos found');
    delete_post_meta($post_id, 'fifu_finder_counter');

    return $arr_urls;
}

function fifu_is_binary($str) {
    return strpos($str, "\0") !== FALSE;
}

