<?php

function fifu_proxy_get_list() {
    $private = get_option('fifu_upload_private_proxy');
    if ($private) {
        $list = array();
        preg_match_all("/([^ ,]+[:][^ ,]+[@])*([0-9]{1,3}[.]){3}[0-9]{1,3}[:][0-9]{1,5}/", $private, $matches);
        foreach ($matches[0] as $match) {
            $match = explode('@', $match);
            if (count($match) > 1) {
                $username = explode(':', $match[0])[0];
                $password = explode(':', $match[0])[1];
                $proxy = explode(':', $match[1])[0];
                $port = explode(':', $match[1])[1];
            } else {
                $username = null;
                $password = null;
                $proxy = explode(':', $match[0])[0];
                $port = explode(':', $match[0])[1];
            }
            array_push($list, array($proxy, $port, $username, $password));
        }
        return $list;
    }

    $list = get_transient('fifu_proxy_list');
    if ($list)
        return $list;

    $html = fifu_get_html_code('https://free-proxy-list.net/');
    if (!$html)
        return null;

    $list = fifu_proxy_scrape($html);
    if ($list)
        set_transient('fifu_proxy_list', $list, 300);

    return $list;
}

function fifu_proxy_scrape($html) {
    $proxies = array();
    $internalErrors = libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    $dom->loadHTML($html);
    $trs = $dom->getElementsByTagname('tr');
    foreach ($trs as $tr) {
        $td = $tr->childNodes[0];
        preg_match('/([0-9]{1,3}[.]){3}[0-9]{1,3}/', $td->nodeValue, $tag);
        if (!$tag)
            continue;
        $ip = $tr->childNodes[0]->nodeValue;
        $port = $tr->childNodes[1]->nodeValue;
        array_push($proxies, array($ip, $port));
    }
    libxml_use_internal_errors($internalErrors);
    return $proxies;
}

function fifu_proxy_get_random($proxies) {
    if ($proxies) {
        $index = array_rand($proxies, 1);
        return $proxies[$index];
    }
    return null;
}

function fifu_proxy_download_url($url, $ip, $port, $user, $password, $get_html) {
    $crl = curl_init();
    curl_setopt($crl, CURLOPT_PROXY, "{$ip}:{$port}");
    curl_setopt($crl, CURLOPT_URL, $url);
    curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($crl, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($crl, CURLOPT_HTTPPROXYTUNNEL, true);
    curl_setopt($crl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($crl, CURLOPT_VERBOSE, true);

    if ($get_html)
        curl_setopt($crl, CURLOPT_ENCODING, "");

    if ($user)
        curl_setopt($crl, CURLOPT_PROXYUSERPWD, "{$user}:{$password}");

    try {
        $ret = curl_exec($crl);
    } catch (Exception $e) {
        return null;
    }

    $content_type = curl_getinfo($crl, CURLINFO_CONTENT_TYPE);
    $verbose = curl_getinfo($crl);

    curl_close($crl);

    if ($get_html) {
        return $ret;
    } elseif ($content_type) {
        if (strpos($content_type, 'image') !== false)
            return $ret;
        return 'invalid-type';
    }

    return null;
}

function fifu_proxy_get_cache() {
    $cache = get_option('fifu_cache_proxy');
    return $cache ? unserialize($cache) : null;
}

function fifu_proxy_download($url, $get_html) {
    $host = fifu_get_host($url);
    $cache_proxy = fifu_proxy_get_cache();
    $content = null;
    if ($cache_proxy) {
        foreach ($cache_proxy as $i => $proxy) {
            if (isset($proxy[$host])) {
                $params = array();
                for ($j = 0; $j <= 3; $j++)
                    array_push($params, isset($proxy[$host][$j]) ? $proxy[$host][$j] : null);

                error_log('Cached proxy: ' . $params[0]);

                // two attempts with the same before unset
                $content = fifu_proxy_download_url($url, $params[0], $params[1], $params[2], $params[3], $get_html);
                if (!$content) {
                    $content = fifu_proxy_download_url($url, $params[0], $params[1], $params[2], $params[3], $get_html);
                    if (!$content) {
                        unset($cache_proxy[$i]);
                    }
                } else
                    break;
            }
            $i++;
        }
    } else {
        $cache_proxy = array();
    }
    if (!$content) {
        $proxies = fifu_proxy_get_list();
        $i = 0;
        foreach ($proxies as $proxy) {
            $params = array();
            for ($j = 0; $j <= 3; $j++)
                array_push($params, isset($proxy[$j]) ? $proxy[$j] : null);

            error_log("Trying proxy {$i}: {$params[0]}:{$params[1]}");
            $i++;

            if (fifu_should_stop_job('fifu_upload_job')) {
                error_log("Stopping job");
                return null;
            }

            if (get_transient('fifu_upload_semaphore'))
                set_transient('fifu_upload_semaphore', new DateTime(), 0);

            $content = fifu_proxy_download_url($url, $params[0], $params[1], $params[2], $params[3], $get_html);
            if ($content == 'invalid-type') {
                continue;
            }
            if ($content) {
                $arr[$host] = $proxy;
                array_push($cache_proxy, $arr);
                update_option('fifu_cache_proxy', serialize($cache_proxy), 'no');
                error_log('Good one!');
                break;
            }
        }
    }
    if ($get_html) {
        // return html
        return $content;
    } elseif ($content) {
        // download image
        $tmp = get_temp_dir() . date("Ymd-His") . '.jpg';
        file_put_contents($tmp, $content);
        return $tmp;
    }
    return null;
}

