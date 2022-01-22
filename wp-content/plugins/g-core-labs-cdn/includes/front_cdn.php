<?php

$gcore_enable_cdn = get_option('gcore_enable_cdn');

if ($gcore_enable_cdn == 1) {

    function g_core_labs_change_url($data, $urls)
    {
        global $gcore_cdn_url;

        $gcore_cdn_url = get_option('gcore_cdn_url');
        if (stripos($gcore_cdn_url, '[SITE_URL]') !== false) {
            $tempHomeURL = parse_url(get_home_url(), PHP_URL_HOST);
            $gcore_cdn_url = str_replace('[SITE_URL]', $tempHomeURL, $gcore_cdn_url);
        }

        $gcore_cdn_exceptions = get_option('gcore_cdn_exceptions');
        $gcore_cdn_exceptions = json_decode($gcore_cdn_exceptions, true);
        if ($gcore_cdn_exceptions == 0 or count($gcore_cdn_exceptions) < 1) $gcore_cdn_exceptions = array();

        $gcore_folder_advanced = get_option('gcore_folder_advanced');
        if ($gcore_folder_advanced == 0) {
            $gcore_folder_templates = get_option('gcore_folder_templates');
            $gcore_folder_plugins = get_option('gcore_folder_plugins');
            $gcore_folder_content = get_option('gcore_folder_content');
            $gcore_folder_wp = get_option('gcore_folder_wp');
            $gcore_cdn_folders = array();
            if ($gcore_folder_templates == 1) {
                $gcore_cdn_folders[] = "/wp-content/themes/";
            }
            if ($gcore_folder_plugins == 1) {
                $gcore_cdn_folders[] = "/wp-content/plugins/";
            }
            if ($gcore_folder_content == 1) {
                $gcore_cdn_folders[] = "/wp-content/uploads/";
            }
            if ($gcore_folder_wp == 1) {
                $gcore_cdn_folders[] = "/wp-includes/";
            }
        } else {
            $gcore_cdn_folders = get_option('gcore_cdn_folders');
            $gcore_cdn_folders = json_decode($gcore_cdn_folders, true);
            if ($gcore_cdn_folders == 0 or count($gcore_cdn_folders) < 1) $gcore_cdn_folders = array();
        }

        $gcore_type_advanced = get_option('gcore_type_advanced');
        if ($gcore_type_advanced == 0) {
            $gcore_type_image = get_option('gcore_type_image');
            $gcore_type_video = get_option('gcore_type_video');
            $gcore_type_audio = get_option('gcore_type_audio');
            $gcore_type_js = get_option('gcore_type_js');
            $gcore_type_css = get_option('gcore_type_css');
            $gcore_type_archive = get_option('gcore_type_archive');
            $temp_cdn_type_temp = array();
            if ($gcore_type_image == 1) {
                $temp_cdn_type_temp[] = array("jpg", "jpeg", "gif", "png", "bmp", "svg", "webp", "tif");
            }
            if ($gcore_type_video == 1) {
                $temp_cdn_type_temp[] = array("mp4", "mov", "webm", "ogv");
            }
            if ($gcore_type_audio == 1) {
                $temp_cdn_type_temp[] = array("mp3", "wav", "ogg");
            }
            if ($gcore_type_js == 1) {
                $temp_cdn_type_temp[] = array("json", "js");
            }
            if ($gcore_type_css == 1) {
                $temp_cdn_type_temp[] = array("css", "map", "less");
            }
            if ($gcore_type_archive == 1) {
                $temp_cdn_type_temp[] = array("zip", "rar", "tar", "gz", "bz");
            }
            $temp_cdn_type = array();
            foreach ($temp_cdn_type_temp as $e) {
                $temp_cdn_type = array_merge($temp_cdn_type, $e);
            }
            $gcore_cdn_types = array();
        } else {
            $gcore_cdn_types = get_option('gcore_cdn_types');
            $gcore_cdn_types = json_decode($gcore_cdn_types, true);
            if ($gcore_cdn_types == 0 or count($gcore_cdn_types) < 1) $gcore_cdn_types = array();
            $temp_cdn_type = array();
        }

        $urls_temp = explode(",", $urls);
        $urls_temp = array_diff($urls_temp, array(''));

        if (is_array($urls_temp) and count($urls_temp) > 0) {
            foreach ($urls_temp as $url) {
                $url = trim($url);
                $url = explode("?", $url);
                $url = $url[0];
                $url = explode(" ", $url);
                $url = $url[0];

                $parsed_url = parse_url($url);

                if (isset($parsed_url['scheme'])) {
                    $origin_url = $parsed_url['scheme'] . "://" . $parsed_url['host'];
                } else {
                    $origin_url = "//" . $parsed_url['host'];
                }
                if ($origin_url == get_home_url()) {

                    if (!in_array($url, $gcore_cdn_exceptions)) {

                        if (count($gcore_cdn_folders) > 0) {
                            foreach ($gcore_cdn_folders as $folder) {
                                if (substr($parsed_url['path'], 0, strlen($folder)) === $folder) {
                                    $ext = explode(".", $parsed_url['path']);
                                    $ext = end($ext);
                                    $ext = explode(" ", $ext);
                                    $ext = trim($ext[0]);
                                    if (in_array($ext, $gcore_cdn_types) or in_array($ext, $temp_cdn_type)) {
                                        $new_url = str_replace(get_home_url() . "/", $gcore_cdn_url, $url);
                                        $data = str_replace($url, $new_url, $data);
                                    }
                                }
                            }
                        } else {
                            $ext = explode(".", $parsed_url['path']);
                            $ext = end($ext);
                            $ext = explode(" ", $ext);
                            $ext = trim($ext[0]);
                            if (in_array($ext, $gcore_cdn_types) or in_array($ext, $temp_cdn_type)) {
                                $new_url = str_replace(get_home_url() . "/", $gcore_cdn_url, $url);
                                $data = str_replace($url, $new_url, $data);
                            }
                        }

                    }
                }
            }
        }
        return $data;
    }

    function g_core_labs_get_include_contents($filename)
    {
        if (is_file($filename)) {
            ob_start();
            include $filename;
            return ob_get_clean();
        }
        return false;
    }

    $string = g_core_labs_get_include_contents($template);

    preg_match_all('/<img[^>]+>/i', $string, $parse_url);
    foreach ($parse_url[0] as $url) {
        preg_match('/<*img[^>]*src *= *["\']?([^"\']*)/i', $url, $result_element);
        if (isset($result_element[1]) and $result_element[1] != '') {
            $string = g_core_labs_change_url($string, $result_element[1]);
        }
        preg_match('/<*img[^>]*srcset *= *["\']?([^"\']*)/i', $url, $result_element);
        if (isset($result_element[1]) and $result_element[1] != '') {
            $string = g_core_labs_change_url($string, $result_element[1]);
        }

    }

    preg_match_all('/<link[^>]+>/i', $string, $parse_url);
    foreach ($parse_url[0] as $url) {
        preg_match('/<*link[^>]*href *= *["\']?([^"\']*)/i', $url, $result_element);
        if (isset($result_element[1]) and $result_element[1] != '') {
            $string = g_core_labs_change_url($string, $result_element[1]);
        }

    }

    preg_match_all('/<script [^>]+>/i', $string, $parse_url);
    foreach ($parse_url[0] as $url) {
        preg_match('/<*script [^>]*src *= *["\']?([^"\']*)/i', $url, $result_element);
        if (isset($result_element[1]) and $result_element[1] != '') {
            $string = g_core_labs_change_url($string, $result_element[1]);
        }

    }

    echo $string;
} else
    include($template);
