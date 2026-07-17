<?php

function fifu_amazon_search($keywords) {
    $url = 'https://www.amazon.es/s?k=' . $keywords . '&i=stripbooks';
    $ch = curl_init($url);
    return fifu_curl($ch, $url);
}

function fifu_amazon_first_result($html, $isbn) {
    preg_match('/<a class="a-link-normal s-no-outline" href="[^>]*keywords=' . $isbn . '[^>]*>/', $html, $tag);
    if (!$tag)
        return null;
    $href = fifu_get_attribute('href', $tag[0]);
    $url = 'https://www.amazon.es' . $href;
    $ch = curl_init($url);
    return fifu_curl($ch, $url);
}

function fifu_get_amazon_book_images($html, $isbn) {
    // check if the product contains the isbn
    preg_match('/<span>' . $isbn . '<\/span>/', $html, $tag);
    if (!$tag) {
        preg_match('/<span>' . substr($isbn, 0, 3) . '-' . substr($isbn, -10) . '<\/span>/', $html, $tag);
        if (!$tag)
            return null;
    }

    // find the image urls
    preg_match('/imageGalleryData((?!\}\],).)*\}\],/', $html, $values);
    if (!$values)
        return null;
    $html = $values[0];

    // url list
    $urls = '';
    preg_match('/"mainUrl":"[^\"]*"/', $html, $values);
    $i = 0;
    foreach ($values as $value) {
        $url = explode('"', $value)[3];
        $urls .= ($i == 0 ? $url : '|' . $url);
        $i++;
    }
    return array('urls' => $urls);
}

function fifu_curl($ch, $url) {
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.182 Safari/537.36');
    return curl_exec($ch);
}

