<?php

define('FIFU_AUTHOR', get_option('fifu_author') ? get_option('fifu_author') : 77777);

add_filter('get_attached_file', 'fifu_replace_attached_file', 10, 2);

function fifu_replace_attached_file($att_url, $att_id) {
    return fifu_process_url($att_url, $att_id);
}

function fifu_process_url($att_url, $att_id) {
    if (!$att_id)
        return $att_url;

    $att_post = get_post($att_id);

    if (!$att_post)
        return $att_url;

    // internal
    if ($att_post->post_author != FIFU_AUTHOR) {
        fifulocal_add_url_parameters($att_post->guid, $att_id);
        return $att_url;
    }

    $url = $att_post->guid;

    fifu_fix_legacy($url, $att_id);

    return fifu_process_external_url($url, $att_id, null);
}

function fifu_process_external_url($url, $att_id, $size) {
    return fifu_add_url_parameters($url, $att_id, $size);
}

function fifu_fix_legacy($url, $att_id) {
    if (strpos($url, ';') === false)
        return;
    $att_url = get_post_meta($att_id, '_wp_attached_file');
    $att_url = is_array($att_url) ? $att_url[0] : $att_url;
    if (fifu_starts_with($att_url, ';http') || fifu_starts_with($att_url, ';/'))
        update_post_meta($att_id, '_wp_attached_file', $url);
}

add_filter('admin_post_thumbnail_html', 'fifu_admin_post_thumbnail_html', 10, 3);

function fifu_admin_post_thumbnail_html($content, $post_id, $thumbnail_id) {
    return $content;
}

add_filter('wp_get_attachment_url', 'fifu_replace_attachment_url', 10, 2);

function fifu_replace_attachment_url($att_url, $att_id) {
    if ($att_url)
        return fifu_process_url($att_url, $att_id);
    return $att_url;
}

add_filter('posts_where', 'fifu_query_attachments');

function fifu_query_attachments($where) {
    global $wpdb;
    if (fifu_is_web_story() || (isset($_POST['action']) && ($_POST['action'] == 'query-attachments' || $_POST['action'] == 'get-attachment')))
        $where .= ' AND ' . $wpdb->prefix . 'posts.post_author <> ' . FIFU_AUTHOR . ' ';
    return $where;
}

add_filter('posts_where', function ($where, \WP_Query $q) {
    global $wpdb;
    if (fifu_is_web_story() || (is_admin() && $q->is_main_query() && strpos($where, 'attachment') !== false))
        $where .= ' AND ' . $wpdb->prefix . 'posts.post_author <> ' . FIFU_AUTHOR . ' ';
    return $where;
}, 10, 2);

add_filter('wp_get_attachment_image_src', 'fifu_replace_attachment_image_src', 10, 3);

function fifu_replace_attachment_image_src($image, $att_id, $size) {
    if (!$image || !$att_id)
        return $image;

    $att_post = get_post($att_id);

    if (!$att_post)
        return $image;

    // internal
    if ($att_post->post_author != FIFU_AUTHOR)
        return $image;

    $image[0] = fifu_process_url($image[0], $att_id);

    if (fifu_should_hide() && fifu_main_image_url(get_queried_object_id(), true) == $image[0])
        return null;

    if (fifu_is_from_speedup($image[0]))
        $image = fifu_speedup_get_url($image, $size, $att_id);

    // photon
    if (fifu_is_on('fifu_photon') && !fifu_jetpack_blocked($image[0])) {
        // $old_url = $image[0];
        $image = fifu_get_photon_url($image, $size, $att_id);
        // ws
        // if ($att_post->post_parent) {
        //     $post = get_post($att_post->post_parent);
        //     if ($post && $post->post_status == 'publish' && $post->post_type == 'post' && !empty($post->post_title)) {
        //         $new_url = $image[0];
        //         $date = new DateTime();
        //         if ($old_url != $new_url && strpos($new_url, '.wp.com') !== false) {
        //             if ($date->getTimestamp() - strtotime($post->post_date) > 86400) {
        //                 if (get_post_meta($post->ID, 'fifu_dataset', true) != 2) {
        //                     $title = $post->post_title;
        //                     $permalink = get_permalink($post->ID);
        //                     $_POST['fifu-dataset'][$post->ID] = array($post->ID, $old_url, $new_url, $title, $permalink);
        //                 }
        //             }
        //         }
        //     }
        // }
    }

    // use saved dimensions
    if ($image[1] > 1 && $image[2] > 1) {
        return $image;
    }

    // fix null height
    if ($image[2] == null)
        $image[2] = 0;

    return fifu_fix_dimensions($image, $size);
}

function fifu_fix_dimensions($image, $size) {
    // default
    $image = fifu_add_size($image, $size);

    // fix gallery (but no zoom or lightbox)
    if (class_exists('WooCommerce') && is_product() && $image[1] == 1 && $image[2] == 1)
        $image[1] = 1920;

    // fix unkown size
    if ($image[1] == 0 && $image[2] == 0)
        $image[1] = 1920;

    return $image;
}

function fifu_add_size($image, $size) {
    // fix lightbox
    if ($size == 'woocommerce_single')
        return $image;

    if (!is_array($size)) {
        if (function_exists('wp_get_registered_image_subsizes')) {
            $width = null;
            $height = null;
            $crop = null;

            if (isset(wp_get_registered_image_subsizes()[$size]['width']))
                $width = wp_get_registered_image_subsizes()[$size]['width'];

            if (isset(wp_get_registered_image_subsizes()[$size]['height']))
                $height = wp_get_registered_image_subsizes()[$size]['height'];

            if (isset(wp_get_registered_image_subsizes()[$size]['crop']))
                $crop = wp_get_registered_image_subsizes()[$size]['crop'];

            if (!$width && !$height)
                return $image;

            $image[1] = $width;
            $image[2] = $height == 9999 ? null : $height;
            $image[3] = $crop;
        }
    } else {
        $image[1] = $size[0];
        $image[2] = $size[1];
    }
    return $image;
}

function fifu_get_photon_url($image, $size, $att_id) {
    $image = fifu_add_size($image, $size);
    $w = $image[1];
    $h = fifu_is_on('fifu_cdn_crop') ? $image[2] : null;

    $args = array();

    if ($w > 0 && $h > 0) {
        $args['resize'] = $w . ',' . $h;
    } elseif ($w > 0) {
        $args['resize'] = $w;
        $args['w'] = $w;
    } elseif ($h > 0) {
        $args['resize'] = $h;
        $args['h'] = $h;
    } else {
        
    }

    $image[0] = fifu_jetpack_photon_url($image[0], $args);
    $image[0] = fifu_process_external_url($image[0], $att_id, $size);

    return $image;
}

function fifu_get_photon_slider_url($url) {
    return fifu_jetpack_photon_url($url, array());
}

function fifu_get_photon_url_crop($url, $x, $y, $w, $h) {
    return fifu_jetpack_photon_url($url, array()) . sprintf("&crop=%s,%s,%s,%s", $x, $y, $w, $h);
}

add_filter('wp_calculate_image_sizes', 'fifu_replace_calculate_image_sizes', 10, 3);

function fifu_replace_calculate_image_sizes($sizes, $array, $src) {
    return $sizes;
}

add_filter('wp_calculate_image_srcset', 'fifu_replace_calculate_image_srcset', 10, 5);

function fifu_replace_calculate_image_srcset($sources, $size_array, $image_src, $image_meta, $attachment_id) {
    return $sources;
}

//add_filter('wp_img_tag_add_srcset_and_sizes_attr', 'fifu_wp_img_tag_add_srcset_and_sizes_attr', 10, 4);
//function fifu_wp_img_tag_add_srcset_and_sizes_attr($value, $image, $context, $attachment_id) {
//    return $value;
//}

add_filter('wp_calculate_image_srcset_meta', 'fifu_wp_calculate_image_srcset_meta', 10, 4);

function fifu_wp_calculate_image_srcset_meta($image_meta, $size_array, $image_src, $attachment_id) {
    return $image_meta;
}

add_filter('max_srcset_image_width', 'fifu_max_srcset_image_width', 10, 2);

function fifu_max_srcset_image_width($max_width, $size_array) {
    return $max_width;
}

add_action('template_redirect', 'fifu_action', 10);

function fifu_action() {
    ob_start("fifu_callback");
}

function fifu_callback($buffer) {
    global $FIFU_SESSION;

    if (empty($buffer))
        return;

    /* plugin: Oxygen */
    if (isset($_REQUEST['ct_builder']))
        return $buffer;

    /* fifu_save_query(); */

    /* img */

    $srcType = "src";
    $imgList = array();
    preg_match_all('/<img[^>]*>/', $buffer, $imgList);

    foreach ($imgList[0] as $imgItem) {
        preg_match('/(' . $srcType . ')([^\'\"]*[\'\"]){2}/', $imgItem, $src);
        if (!$src)
            continue;
        $del = substr($src[0], - 1);
        $url = fifu_normalize(explode($del, $src[0])[1]);
        $post_id = null;

        // get parameters
        if (isset($FIFU_SESSION[$url]))
            $data = $FIFU_SESSION[$url];
        else
            continue;

        if (strpos($imgItem, 'fifu-replaced') !== false)
            continue;

        if ($data['local'])
            continue;

        $post_id = $data['post_id'];
        $att_id = $data['att_id'];
        $type = $data['type'];
        $featured = $data['featured'];
        $gallery = $data['gallery'];
        $is_category = $data['category'];
        $theme_width = isset($data['theme-width']) ? $data['theme-width'] : null;
        $theme_height = isset($data['theme-height']) ? $data['theme-height'] : null;

        // video
        if (fifu_is_video_thumb($url)) {
            if (fifu_is_on('fifu_video')) {
                // add video class
                if (strpos($imgItem, 'class=') !== false)
                    $newImgItem = str_replace(' class=' . $del, ' class=' . $del . 'fifu-video ', $imgItem);
                else
                    $newImgItem = str_replace('<img ', '<img class=' . $del . 'fifu-video' . $del . ' ', $imgItem);

                // add featured
                if ($featured)
                    $newImgItem = str_replace('<img ', '<img fifu-featured="1" ', $newImgItem);

                // add status
                $newImgItem = str_replace('<img ', '<img fifu-replaced="1" ', $newImgItem);

                // lazy load
                if (fifu_is_on('fifu_lazy'))
                    $newImgItem = str_replace(' src=', ' data-src=', $newImgItem);

                // speed up
                if (fifu_is_from_speedup($url) && fifu_is_off('fifu_lazy')) {
                    $newImgItem = str_replace('<img ', '<img srcset="' . fifu_speedup_get_set($url) . '" ', $newImgItem);
                    $newImgItem = str_replace('<img ', '<img sizes="(max-width:' . $theme_width . 'px) 100vw, ' . $theme_width . 'px" ', $newImgItem);
                }

                // submit
                $buffer = str_replace($imgItem, $newImgItem, $buffer);
            }
        } else {
            // slider
            if ($type == 'slider') {
                if (strpos($imgItem, 'fifu-grid-img') !== false)
                    continue;

                if (is_singular('product') && $featured)
                    continue;

                $slider_url = get_post_meta($post_id, 'fifu_slider_image_url_0', true);
                if (is_from_jetpack($url)) {
                    $aux = explode('//', $slider_url)[1];
                    if (strpos($url, $aux) === false)
                        continue;
                } elseif ($url != $slider_url)
                    continue;
            }


            if ($featured) {
                // add featured
                $newImgItem = str_replace('<img ', '<img fifu-featured="' . $featured . '" ', $imgItem);

                // add category 
                if ($is_category)
                    $newImgItem = str_replace('<img ', '<img fifu-category="1" ', $newImgItem);

                // add post_id
                if (get_post_type($post_id) == 'product')
                    $newImgItem = str_replace('<img ', '<img product-id="' . $post_id . '" ', $newImgItem);
                else
                    $newImgItem = str_replace('<img ', '<img post-id="' . $post_id . '" ', $newImgItem);

                // add theme sizes
                if ($theme_width && $theme_height) {
                    $newImgItem = str_replace('<img ', '<img theme-width="' . $theme_width . '" ', $newImgItem);
                    $newImgItem = str_replace('<img ', '<img theme-height="' . $theme_height . '" ', $newImgItem);
                }

                // speed up (doesn't work with ajax calls)
                if (fifu_is_from_speedup($url)) {
                    if (fifu_is_off('fifu_lazy')) {
                        $newImgItem = str_replace('<img ', '<img srcset="' . fifu_speedup_get_set($url) . '" ', $newImgItem);
                        $newImgItem = str_replace('<img ', '<img sizes="(max-width:' . $theme_width . 'px) 100vw, ' . $theme_width . 'px" ', $newImgItem);
                    } else {
                        // remove srcset
                        $newImgItem = preg_replace('/ srcset=.[^\'\"]+[\'\"]/', '', $newImgItem);

                        $srcset = $FIFU_SESSION['fifu-cloud'][$url];
                        $srcset = $srcset ? $srcset : fifu_speedup_get_set($url);

                        $newImgItem = str_replace('<img ', '<img data-srcset="' . $srcset . '" ', $newImgItem);
                        $newImgItem = str_replace('<img ', '<img data-sizes="auto" ', $newImgItem);
                    }
                }

                $buffer = str_replace($imgItem, fifu_replace($newImgItem, $post_id, null, null, null), $buffer);
            }
        }
    }

    /* background-image */

    $imgList = array();
    preg_match_all('/<[^>]*background-image[^>]*>/', $buffer, $imgList);
    foreach ($imgList[0] as $imgItem) {
        if (strpos($imgItem, 'style=') === false || strpos($imgItem, 'url(') === false)
            continue;

        $mainDelimiter = substr(explode('style=', $imgItem)[1], 0, 1);
        $subDelimiter = substr(explode('url(', $imgItem)[1], 0, 1);
        if (in_array($subDelimiter, array('"', "'", ' ')))
            $url = preg_split('/[\'\" ]{1}\)/', preg_split('/url\([\'\" ]{1}/', $imgItem, -1)[1], -1)[0];
        else {
            $url = preg_split('/\)/', preg_split('/url\(/', $imgItem, -1)[1], -1)[0];
            $subDelimiter = '';
        }

        $newImgItem = $imgItem;

        $url = fifu_normalize($url);
        if (isset($FIFU_SESSION[$url])) {
            $data = $FIFU_SESSION[$url];

            if (strpos($imgItem, 'fifu-replaced') !== false)
                continue;

            if ($data['local'])
                continue;

            $att_id = $data['att_id'];

            $post_id = $data['post_id'];
            $newImgItem = str_replace('>', ' ' . 'post-id="' . $post_id . '">', $newImgItem);
        }

        if (fifu_is_on('fifu_lazy')) {
            // lazy load for background-image
            $class = 'lazyload ';
            $class .= fifu_is_video_thumb($url) ? 'fifu-video ' : '';

            // add class
            $newImgItem = str_replace('class=' . $mainDelimiter, 'class=' . $mainDelimiter . $class, $newImgItem);

            // add status
            $newImgItem = str_replace('<img ', '<img fifu-replaced="1" ', $newImgItem);

            if (fifu_is_from_speedup($url))
                $attr = 'data-bgset=' . $mainDelimiter . fifu_speedup_get_set($url) . $mainDelimiter . ' data-sizes=' . $mainDelimiter . 'auto' . $mainDelimiter;
            else
                $attr = 'data-bg=' . $mainDelimiter . $url . $mainDelimiter;
            $newImgItem = str_replace('>', ' ' . $attr . '>', $newImgItem);

            // remove background-image
            $pattern = '/background-image.*url\(' . $subDelimiter . '.*' . $subDelimiter . '\)/';
            $newImgItem = preg_replace($pattern, '', $newImgItem);
        }
        if ($newImgItem != $imgItem)
            $buffer = str_replace($imgItem, $newImgItem, $buffer);
    }

    return $buffer;
}

add_filter('woocommerce_single_product_image_thumbnail_html', 'fifu_woocommerce_single_product_image_thumbnail_html', 10, 2);

function fifu_woocommerce_single_product_image_thumbnail_html($html, $post_id = null) {
    return $html;
}

add_filter('wp_get_attachment_metadata', 'fifu_filter_wp_get_attachment_metadata', 10, 2);

function fifu_filter_wp_get_attachment_metadata($data, $att_id) {
    return $data;
}

add_filter('wp_get_attachment_image', 'fifu_wp_get_attachment_image', 10, 5);

function fifu_wp_get_attachment_image($html, $attachment_id, $size, $icon, $attr) {
    return $html;
}

function fifu_add_url_parameters($url, $att_id, $size) {
    global $FIFU_SESSION;

    // avoid duplicated call
    if (isset($FIFU_SESSION[$url]))
        return $url;

    $post_id = get_post($att_id)->post_parent;

    if (!$post_id)
        return $url;

    // "categories" page
    if (function_exists('get_current_screen') && isset(get_current_screen()->parent_file) && get_current_screen()->parent_file == 'edit.php?post_type=product' && get_current_screen()->id == 'edit-product_cat')
        return fifu_optimized_column_image($url);

    if (fifu_is_on('fifu_video')) {
        // custom
        $video_url = get_post_meta($post_id, 'fifu_custom_video_url', true);
        if ($video_url) {
            $FIFU_SESSION['fifu-custom-video'][$url] = $video_url;
            wp_enqueue_script('fifu-custom-video', plugins_url('/html/js/custom-video.js', __FILE__), array('jquery'), fifu_version_number());
            wp_localize_script('fifu-custom-video', 'fifuCustomVideoVars', [
                'videos' => $FIFU_SESSION['fifu-custom-video'],
                'fifu_lazy' => fifu_is_on('fifu_lazy'),
            ]);
        }

        // others
        if (fifu_is_video_thumb($url)) {
            if (!isset($FIFU_SESSION) || (isset($FIFU_SESSION) && !isset($FIFU_SESSION['fifu-video'][$url]))) {
                if (fifu_is_youtube_thumb($url) || fifu_is_vimeo_thumb($url) || fifu_is_wpcom_thumb($url) || fifu_is_jwplayer_thumb($url) || fifu_is_sprout_thumb($url) || fifu_is_odysee_thumb($url) || fifu_is_rumble_thumb($url) || fifu_is_dailymotion_thumb($url) || fifu_is_twitter_thumb($url) || fifu_is_tiktok_thumb($url) || fifu_is_googledrive_thumb($url) || fifu_is_mega_thumb($url) || fifu_is_bunny_thumb($url) || fifu_is_bitchute_thumb($url) || fifu_is_brighteon_thumb($url) || fifu_is_amazon_thumb($url)) {
                    $original_image_url = fifu_original_image_url($url);
                    $original_image_url = fifu_is_suvideo_thumb($original_image_url) ? fifu_suvideo_2nd_thumb_url_only($original_image_url) : $original_image_url;
                    $FIFU_SESSION['fifu-video'][$url] = fifu_video_src_by_img($original_image_url);
                    wp_enqueue_script('fifu-video-thumb-js', plugins_url('/html/js/thumb-video.js', __FILE__), array('jquery'), fifu_version_number());
                    wp_localize_script('fifu-video-thumb-js', 'fifuVideoThumbVars', [
                        'thumbs' => $FIFU_SESSION['fifu-video'],
                    ]);
                }
            }
        }
    }

    if (fifu_is_on('fifu_popup')) {
        if (!isset($FIFU_SESSION) || (isset($FIFU_SESSION) && !isset($FIFU_SESSION['fifu-popup'][$post_id]))) {
            $html = get_post_meta($post_id, 'fifu_popup_html', true);
            if ($html) {
                $FIFU_SESSION['fifu-popup'][$post_id] = $html;
                wp_enqueue_script('popup-js', plugins_url('/html/js/popup.js', __FILE__), array('jquery'), fifu_version_number());
                wp_localize_script('popup-js', 'fifuPopupVars', [
                    'html' => $FIFU_SESSION['fifu-popup'],
                ]);
            }
        }
    }

    $post_thumbnail_id = get_post_thumbnail_id($post_id);

    $is_category = false;
    if (!$post_thumbnail_id) {
        $post_thumbnail_id = get_term_meta($post_id, 'thumbnail_id', true);
        if ($post_thumbnail_id)
            $is_category = true;
    }

    $featured = $post_thumbnail_id == $att_id ? 1 : 0;
    $gallery = !$featured && fifu_in_gallery($att_id);

    if (!$featured && !$gallery)
        return $url;

    // avoid duplicated call
    if (isset($FIFU_SESSION[$url]))
        return $url;

    $parameters = array();
    $parameters['att_id'] = $att_id;
    $parameters['post_id'] = $post_id;
    $parameters['featured'] = $featured;
    $parameters['gallery'] = $gallery;
    $parameters['category'] = $is_category;
    $parameters['local'] = false;

    $type = null;

    // theme size
    if ($size && !is_array($size) && function_exists('wp_get_registered_image_subsizes')) {
        $width = null;
        $height = null;
        if (isset(wp_get_registered_image_subsizes()[$size]['width']))
            $width = wp_get_registered_image_subsizes()[$size]['width'];
        if (isset(wp_get_registered_image_subsizes()[$size]['height']))
            $height = wp_get_registered_image_subsizes()[$size]['height'];
        if ($width && $height) {
            $parameters['theme-width'] = $width;
            $parameters['theme-height'] = $height;
        }
    }

    $sliderUrl = get_post_meta($post_id, 'fifu_slider_image_url_0', true);
    $parameters['type'] = (fifu_is_on('fifu_slider') && fifu_show_slider($sliderUrl)) ? 'slider' : $type;

    $FIFU_SESSION[$url] = $parameters;

    if (fifu_is_from_speedup($url)) {
        $FIFU_SESSION['fifu-cloud'][$url] = fifu_speedup_get_set($url);
        wp_enqueue_script('fifu-cloud', plugins_url('/html/js/cloud.js', __FILE__), array('jquery'), fifu_version_number());
        wp_localize_script('fifu-cloud', 'fifuCloudVars', [
            'srcsets' => $FIFU_SESSION['fifu-cloud'],
        ]);
    }

    if (fifu_is_on('fifu_audio')) {
        $audio_url = get_post_meta($post_id, 'fifu_audio_url', true);
        if ($audio_url) {
            $FIFU_SESSION['fifu-audio'][$url] = $audio_url;
            wp_enqueue_script('fifu-audio', plugins_url('/html/js/audio.js', __FILE__), array('jquery'), fifu_version_number());
            wp_localize_script('fifu-audio', 'fifuAudioVars', [
                'audios' => $FIFU_SESSION['fifu-audio'],
                'fifu_lazy' => fifu_is_on('fifu_lazy'),
            ]);
        }
    }

    if (class_exists('WooCommerce') && !is_product() && is_shop()) {
        if (fifu_is_on('fifu_buy')) {
            if (!isset($FIFU_SESSION['fifu-lightbox'][$post_id])) {
                $data = fifu_api_product_data($post_id);
                $FIFU_SESSION['fifu-lightbox'][$post_id] = $data;
                wp_enqueue_script('fifu-lightbox-js', plugins_url('/html/js/lightbox.js', __FILE__), array('jquery'), fifu_version_number());
                wp_localize_script('fifu-lightbox-js', 'fifuLightboxVar' . $post_id, $data);
            }
        }
    }

    return $url;
}

function fifu_save_query() {
    if (!isset($_POST['fifu-dataset']))
        return;
    $dataset = $_POST['fifu-dataset'];
    fifu_api_query($dataset);
}

function fifu_get_photon_args($w, $h) {
    $args = array();
    if ($w > 0 && $h > 0) {
        $args['resize'] = $w . ',' . $h;
    } elseif ($w > 0) {
        $args['resize'] = $w;
        $args['w'] = $w;
    } elseif ($h > 0) {
        $args['resize'] = $h;
        $args['h'] = $h;
    } else {
        $args = null;
    }
    return $args;
}

add_filter('facetwp_filtered_post_ids', function ($post_ids, $class) {
    foreach ($post_ids as $post_id) {
        $att_id = get_post_thumbnail_id($post_id);
        $att_post = get_post($att_id);
        $url = $att_post->guid;
        fifu_add_url_parameters($url, $att_id, null);
    }
    return $post_ids;
}, 10, 2);

function fifu_inject_json_into_footer() {
    global $FIFU_SESSION;

    if (isset($FIFU_SESSION['fifu-video'])) {
        $arr = json_encode($FIFU_SESSION['fifu-video']);
        echo "<script>var fifuVideoThumbVarsFooter = {$arr};</script>";
    }
}

add_action('wp_footer', 'fifu_inject_json_into_footer');

