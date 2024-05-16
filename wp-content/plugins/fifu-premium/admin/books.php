<?php

class FifuBooks {

    function __construct() {
        $this->headers = array(
            'authority' => 'www.googleapis.com',
            'accept' => 'application/json, text/javascript, */*; q=0.01',
            'sec-fetch-dest' => 'empty',
            'x-requested-with' => 'XMLHttpRequest',
            'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36',
            'sec-fetch-site' => 'same-origin',
            'sec-fetch-mode' => 'cors',
            'referer' => 'https://www.googleapis.com',
            'accept-language' => 'en-US,en;q=0.9',
        );
    }

    function get_image_url($isbn) {
        $res = $this->get_abe_image_url($isbn);
        if ($res)
            return $res['url'];

        $res = $this->get_google_image_url($isbn);
        if ($res)
            return $res['url'];

        $res = $this->get_amazon_images_urls($isbn);
        if ($res)
            return $res['url'];

        return null;
    }

    function get_abe_image_url($isbn) {
        try {
            $image_url = 'https://pictures.abebooks.com/isbn/' . $isbn . '.jpg';
            $res = wp_safe_remote_get($image_url, array('headers' => $this->headers));
            $code = json_decode($res['response']['code']);
            if ($code == 200)
                return array('url' => $image_url);
        } catch (Exception $e) {
            error_log('fifu-books:', $e, $isbn);
        }
        return null;
    }

    function get_google_image_url($isbn) {
        try {
            $endpoint = 'https://www.googleapis.com/books/v1/volumes?q=isbn:' . $isbn;
            $res = wp_safe_remote_get($endpoint, array('headers' => $this->headers));
            $data = json_decode($res['body']);
            if (!isset($data->items))
                return null;
            if (!isset($data->items[0]->volumeInfo->imageLinks))
                return null;
            $image_url = 'https://books.google.com/books/content/images/frontcover/' . $data->items[0]->id . '?fife=h600';
            return array('url' => $image_url);
        } catch (Exception $e) {
            error_log('fifu-books:', $e, $isbn);
        }
        return null;
    }

    function get_amazon_images_urls($isbn) {
        try {
            $html = fifu_amazon_search($isbn);
            if ($html) {
                $amazon_html = fifu_amazon_first_result($html, $isbn);
                $html = utf8_decode($amazon_html);
                $image = fifu_get_amazon_book_images($html, $isbn);
                if ($image && isset($image['urls']) && $image['urls']) {
                    $url = explode("|", $image['urls'])[0];
                    if ($url != 'https://images-na.ssl-images-amazon.com/images/I/01GmVhVRioL.gif')
                        return array('url' => $url);
                }
            }
        } catch (Exception $e) {
            error_log('fifu-books:', $e, $isbn);
        }
        return null;
    }

}

function fifu_isbn_search($isbn) {
    $books = new FifuBooks();
    return $books->get_image_url($isbn);
}

