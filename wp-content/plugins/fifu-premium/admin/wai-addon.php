<?php

include 'rapid-addon.php';
$fifu = fifu_get_strings_wai();
$fifu_wai_addon = new RapidAddon('<div style="color:#777"><span class="dashicons dashicons-camera" style="font-size:30px;padding-right:10px"></span> FIFU</div>', 'fifu_wai_addon');
$fifu_wai_addon->add_field('fifu_image_url', '<div title="fifu_image_url">' . $fifu['title']['image']() . '</div>', 'text', null, null, false, null);
$fifu_wai_addon->add_field('fifu_image_alt', '<div title="fifu_image_alt">' . $fifu['title']['title']() . '</div>', 'text', null, null, false, null);
$fifu_wai_addon->add_field('fifu_video_url', '<div title="fifu_video_url">' . $fifu['title']['video']() . '</div>', 'text', null, null, false, null);
$fifu_wai_addon->add_field('fifu_list_url', '<div title="fifu_list_url">' . $fifu['title']['images']() . '</div>', 'text', null, $fifu['info']['delimited'](), false, null);
$fifu_wai_addon->add_field('fifu_list_alt', '<div title="fifu_list_alt">' . $fifu['title']['titles']() . '</div>', 'text', null, $fifu['info']['delimited'](), false, null);
$fifu_wai_addon->add_field('fifu_list_video_url', '<div title="fifu_list_video_url">' . $fifu['title']['videos']() . '</div>', 'text', null, $fifu['info']['delimited'](), false, null);
$fifu_wai_addon->add_field('fifu_slider_list_url', '<div title="fifu_slider_list_url">' . $fifu['title']['slider']() . '</div>', 'text', null, $fifu['info']['delimited'](), false, null);
$fifu_wai_addon->add_field('fifu_delimiter', '<div>' . $fifu['title']['delimiter']() . '</div>', 'text', null, $fifu['info']['default'](), false, null);
$fifu_wai_addon->add_field('fifu_isbn', '<div title="fifu_isbn">' . $fifu['title']['isbn']() . '</div>', 'text', null, null, false, null);
$fifu_wai_addon->add_field('fifu_finder_url', '<div title="fifu_finder_url">' . $fifu['title']['finder']() . '</div>', 'text', null, $fifu['info']['finder'](), false, null);
$fifu_wai_addon->set_import_function('fifu_wai_addon_save');
$fifu_wai_addon->run();

function fifu_wai_addon_save($post_id, $data, $import_options, $article) {
    $delimiter = $data['fifu_delimiter'];
    $delimiter = empty($delimiter) ? '|' : $delimiter;

    $fields = array();

    /* if fifu_list_url, ignore fifu_image_url */
    if (empty($data['fifu_list_url'])) {
        if (!empty($data['fifu_image_url']) && empty($data['fifu_video_url']))
            array_push($fields, 'fifu_image_url');
    } else
        array_push($fields, 'fifu_list_url');

    /* if fifu_list_alt, ignore fifu_image_alt */
    if (empty($data['fifu_list_alt'])) {
        if (!empty($data['fifu_image_alt']))
            array_push($fields, 'fifu_image_alt');
    } else
        array_push($fields, 'fifu_list_alt');

    /* if fifu_list_video_url or fifu_image_url, ignore fifu_video_url */
    /* if fifu_list_url, ignore fifu_list_video_url */
    if (empty($data['fifu_list_video_url'])) {
        if (!empty($data['fifu_video_url']))
            array_push($fields, 'fifu_video_url');
    } else {
        array_push($fields, 'fifu_list_video_url');
    }

    /* if fifu_image_url or fifu_video_url or fifu_list_url or fifu_list_video_url, ignore fifu_slider_list_url */
    if (empty($data['fifu_image_url']) && empty($data['fifu_video_url']) && empty($data['fifu_list_url']) && empty($data['fifu_list_video_url'])) {
        if (!empty($data['fifu_slider_list_url']))
            array_push($fields, 'fifu_slider_list_url');
    }

    /* isbn */
    if (!empty($data['fifu_isbn']))
        array_push($fields, 'fifu_isbn');

    /* finder */
    if (!empty($data['fifu_finder_url']))
        array_push($fields, 'fifu_finder_url');

    /* default */
    if (empty($fields)) {
        if (fifu_is_off('fifu_enable_default_url'))
            return;
    }

    $is_ctgr = $article['post_type'] == 'taxonomies';
    $update = false;
    foreach ($fields as $field) {
        $current_value = get_post_meta($post_id, $field, true);
        if ($current_value != $data[$field]) {
            $update = true;
            if (in_array($field, array('fifu_list_url', 'fifu_list_alt', 'fifu_list_video_url', 'fifu_slider_list_url')))
                $value = str_replace($delimiter, '|', $data[$field]);
            else
                $value = $data[$field];
            if ($is_ctgr)
                update_term_meta($post_id, $field, $value);
            else
                update_post_meta($post_id, $field, $value);
        }
    }

    global $fifu_wai_addon;
    if (!$update && !$fifu_wai_addon->can_update_image($import_options))
        return;

    fifu_wai_save($post_id, $is_ctgr);
    fifu_wai_video_save($post_id, $is_ctgr);
    fifu_slider_wai_save($post_id);

    /* metadata */
    add_action('pmxi_saved_post', 'fifu_update_fake_attach_id');
}

