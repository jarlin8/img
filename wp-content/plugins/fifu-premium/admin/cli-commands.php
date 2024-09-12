<?php

class fifu_cli extends WP_CLI_Command {

    // admin

    function reset() {
        fifu_reset_settings();
        //WP_CLI::line($args[0]);
    }

    // automatic

    function content($args, $assoc_args) {
        if (!empty($assoc_args['position'])) {
            update_option('fifu_spinner_nth', $args[0], 'no');
            return;
        }
        if (!empty($assoc_args['skip'])) {
            update_option('fifu_skip', $args[0], 'no');
            return;
        }
        if (!empty($assoc_args['cpt'])) {
            update_option('fifu_html_cpt', $args[0], 'no');
            return;
        }
        if (!empty($assoc_args['hide'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_pop_first', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_pop_first', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['remove-query'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_query_strings', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_query_strings', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['overwrite'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_ovw_first', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_ovw_first', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['prioritize-video'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_video_priority', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_video_priority', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['decode'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_decode', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_decode', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['check'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_check', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_check', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['all-ignore'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_update_ignore', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_update_ignore', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['all-run'])) {
            update_option('fifu_update_all', 'toggleoff', 'no');
            fifu_db_update_all();
            return;
        }
        switch ($args[0]) {
            case 'on':
                update_option('fifu_get_first', 'toggleon', 'no'); // toggle
                break;
            case 'off':
                update_option('fifu_get_first', 'toggleoff', 'no'); // toggle
                break;
        }
    }

    function search($args, $assoc_args) {
        if (!empty($assoc_args['min-width'])) {
            update_option('fifu_auto_set_width', $args[0], 'no');
            return;
        }
        if (!empty($assoc_args['min-height'])) {
            update_option('fifu_auto_set_height', $args[0], 'no');
            return;
        }
        if (!empty($assoc_args['blocklist'])) {
            update_option('fifu_auto_set_blocklist', str_replace(',', '
', $args[0]), 'no'); // don't edit
            return;
        }
        if (!empty($assoc_args['cpt'])) {
            update_option('fifu_auto_set_cpt', $args[0], 'no');
            return;
        }
        if (!empty($assoc_args['source'])) {
            update_option('fifu_auto_set_source', $args[0], 'no');
            return;
        }
        if (!empty($assoc_args['license'])) {
            update_option('fifu_auto_set_license', $args[0], 'no');
            return;
        }
        switch ($args[0]) {
            case 'on':
                update_option('fifu_auto_set', 'toggleon', 'no'); // toggle
                if (!wp_next_scheduled('fifu_create_auto_set_event'))
                    wp_schedule_event(time(), 'fifu_schedule_auto_set', 'fifu_create_auto_set_event');
                break;
            case 'off':
                update_option('fifu_auto_set', 'toggleoff', 'no'); // toggle
                wp_clear_scheduled_hook('fifu_create_auto_set_event');
                delete_transient('fifu_auto_set_semaphore');
                break;
        }
    }

    function isbn($args, $assoc_args) {
        if (!empty($assoc_args['field'])) {
            update_option('fifu_isbn_custom_field', $args[0], 'no');
            return;
        }
        switch ($args[0]) {
            case 'on':
                update_option('fifu_isbn', 'toggleon', 'no'); // toggle
                if (!wp_next_scheduled('fifu_create_isbn_event'))
                    wp_schedule_event(time(), 'fifu_schedule_isbn', 'fifu_create_isbn_event');
                break;
            case 'off':
                update_option('fifu_isbn', 'toggleoff', 'no'); // toggle
                wp_clear_scheduled_hook('fifu_create_isbn_event');
                delete_transient('fifu_isbn_semaphore');
                break;
        }
    }

    function finder($args, $assoc_args) {
        if (!empty($assoc_args['field'])) {
            update_option('fifu_finder_custom_field', $args[0], 'no');
            return;
        }
        if (!empty($assoc_args['video'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_video_finder', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_video_finder', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['amazon'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_amazon_finder', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_amazon_finder', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        switch ($args[0]) {
            case 'on':
                update_option('fifu_finder', 'toggleon', 'no'); // toggle
                if (!wp_next_scheduled('fifu_create_finder_event'))
                    wp_schedule_event(time(), 'fifu_schedule_finder', 'fifu_create_finder_event');
                break;
            case 'off':
                update_option('fifu_finder', 'toggleoff', 'no'); // toggle
                wp_clear_scheduled_hook('fifu_create_finder_event');
                delete_transient('fifu_finder_semaphore');
                break;
        }
    }

    function tags($args) {
        switch ($args[0]) {
            case 'on':
                update_option('fifu_tags', 'toggleon', 'no'); // toggle
                if (!wp_next_scheduled('fifu_create_tags_event'))
                    wp_schedule_event(time(), 'fifu_schedule_tags', 'fifu_create_tags_event');
                break;
            case 'off':
                update_option('fifu_tags', 'toggleoff', 'no'); // toggle
                wp_clear_scheduled_hook('fifu_create_tags_event');
                delete_transient('fifu_tags_semaphore');
                break;
        }
    }

    function screenshot($args) {
        switch ($args[0]) {
            case 'on':
                update_option('fifu_screenshot', 'toggleon', 'no'); // toggle
                break;
            case 'off':
                update_option('fifu_screenshot', 'toggleoff', 'no'); // toggle
                break;
        }
        return;
    }

    // featured image

    function image($args, $assoc_args) {
        if (!empty($assoc_args['title-copy'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_auto_alt', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_auto_alt', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['title-always'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_dynamic_alt', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_dynamic_alt', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['hide-page'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_hide_page', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_hide_page', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['hide-post'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_hide_post', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_hide_post', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['hide-cpt'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_hide_cpt', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_hide_cpt', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['hide-formats'])) {
            update_option('fifu_hide_format', $args[0], 'no');
            return;
        }
        if (!empty($assoc_args['default'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_enable_default_url', 'toggleon', 'no'); // toggle
                    $default_url = get_option('fifu_default_url');
                    if (!$default_url)
                        fifu_db_delete_default_url();
                    elseif (fifu_is_on('fifu_fake')) {
                        if (!wp_get_attachment_url(get_option('fifu_default_attach_id'))) {
                            $att_id = fifu_db_create_attachment($default_url);
                            update_option('fifu_default_attach_id', $att_id);
                            fifu_db_set_default_url();
                        } else
                            fifu_db_update_default_url($default_url);
                    }
                    break;
                case 'off':
                    update_option('fifu_enable_default_url', 'toggleoff', 'no'); // toggle
                    fifu_db_delete_default_url();
                    break;
            }
            return;
        }
        if (!empty($assoc_args['default-url'])) {
            update_option('fifu_default_url', $args[0], 'no');
            if (fifu_is_off('fifu_enable_default_url'))
                fifu_db_delete_default_url();
            elseif (!$args[0])
                fifu_db_delete_default_url();
            return;
        }
        if (!empty($assoc_args['default-types'])) {
            update_option('fifu_default_cpt', $args[0], 'no');
            return;
        }
        if (!empty($assoc_args['content-page'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_content_page', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_content_page', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['content-post'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_content', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_content', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['content-cpt'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_content_cpt', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_content_cpt', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['height'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_same_size', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_same_size', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['height-sel']) && !empty($assoc_args['selector'])) {
            update_option('fifu_crop' . $args[0], $args[1], 'no');
            return;
        }
        if (!empty($assoc_args['height-ratio'])) {
            update_option('fifu_crop_ratio', $args[0], 'no');
            return;
        }
        if (!empty($assoc_args['height-fit'])) {
            update_option('fifu_fit', $args[0], 'no');
            return;
        }
        if (!empty($assoc_args['height-delay'])) {
            update_option('fifu_crop_delay', $args[0], 'no');
            return;
        }
        if (!empty($assoc_args['height-default'])) {
            update_option('fifu_crop_default', $args[0], 'no');
            return;
        }
        if (!empty($assoc_args['height-ignore'])) {
            update_option('fifu_crop_ignore_parent', $args[0], 'no');
            return;
        }
        if (!empty($assoc_args['replace'])) {
            update_option('fifu_error_url', $args[0], 'no');
            return;
        }
        if (!empty($assoc_args['block'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_block', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_block', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['popup'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_popup', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_popup', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['redirection'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_redirection', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_redirection', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
    }

    function upload($args, $assoc_args) {
        if (!empty($assoc_args['domain'])) {
            update_option('fifu_upload_domain', $args[0], 'no');
            return;
        }
        if (!empty($assoc_args['show-button'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_upload_show', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_upload_show', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['job'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_upload_job', 'toggleon', 'no'); // toggle
                    if (!wp_next_scheduled('fifu_create_upload_event'))
                        wp_schedule_event(time(), 'fifu_schedule_upload', 'fifu_create_upload_event');
                    break;
                case 'off':
                    update_option('fifu_upload_job', 'toggleoff', 'no'); // toggle
                    wp_clear_scheduled_hook('fifu_create_upload_event');
                    delete_transient('fifu_upload_semaphore');
                    break;
            }
        }
        if (!empty($assoc_args['proxy'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_upload_proxy', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_upload_proxy', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['private-proxy'])) {
            update_option('fifu_upload_private_proxy', $args[0], 'no');
            return;
        }
    }

    // shortcodes

    function shortform($args) {
        switch ($args[0]) {
            case 'on':
                update_option('fifu_shortform', 'toggleon', 'no'); // toggle
                break;
            case 'off':
                update_option('fifu_shortform', 'toggleoff', 'no'); // toggle
                break;
        }
    }

    // featured slider

    function slider($args, $assoc_args) {
        if (!empty($assoc_args['pause'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_slider_stop', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_slider_stop', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['buttons'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_slider_ctrl', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_slider_ctrl', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['auto'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_slider_auto', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_slider_auto', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['gallery'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_slider_gallery', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_slider_gallery', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['thumb-gallery'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_slider_thumb', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_slider_thumb', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['counter'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_slider_counter', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_slider_counter', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['crop'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_slider_crop', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_slider_crop', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['single'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_slider_single', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_slider_single', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['vertical'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_slider_vertical', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_slider_vertical', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['time-image'])) {
            update_option('fifu_slider_pause', $args[0], 'no');
            return;
        }
        if (!empty($assoc_args['time-transition'])) {
            update_option('fifu_slider_speed', $args[0], 'no');
            return;
        }
        if (!empty($assoc_args['left'])) {
            update_option('fifu_slider_left', $args[0], 'no');
            return;
        }
        if (!empty($assoc_args['right'])) {
            update_option('fifu_slider_right', $args[0], 'no');
            return;
        }
        switch ($args[0]) {
            case 'on':
                update_option('fifu_slider', 'toggleon', 'no'); // toggle
                break;
            case 'off':
                update_option('fifu_slider', 'toggleoff', 'no'); // toggle
                break;
        }
    }

    // featured video

    function video($args, $assoc_args) {
        if (!empty($assoc_args['thumb-home'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_video_thumb', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_video_thumb', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['thumb-page'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_video_thumb_page', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_video_thumb_page', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['thumb-post'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_video_thumb_post', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_video_thumb_post', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['thumb-cpt'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_video_thumb_cpt', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_video_thumb_cpt', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['play'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_video_play_button', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_video_play_button', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['play-color'])) {
            update_option('fifu_video_color', $args[0], 'no');
            return;
        }
        if (!empty($assoc_args['play-mode'])) {
            update_option('fifu_play_type', $args[0], 'no');
            return;
        }
        if (!empty($assoc_args['play-zindex'])) {
            update_option('fifu_video_zindex', $args[0], 'no');
            return;
        }
        if (!empty($assoc_args['play-hide'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_video_play_hide_grid', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_video_play_hide_grid', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['play-hide-wc'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_video_play_hide_grid_wc', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_video_play_hide_grid_wc', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['min-width'])) {
            update_option('fifu_video_min_width', $args[0], 'no');
            return;
        }
        if (!empty($assoc_args['controls'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_video_controls', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_video_controls', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['mouse-youtube'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_mouse_youtube', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_mouse_youtube', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['mouse-vimeo'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_mouse_vimeo', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_mouse_vimeo', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['autoplay'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_autoplay', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_autoplay', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['autoplay-front'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_autoplay_front', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_autoplay_front', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['loop'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_loop', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_loop', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['mute-desktop'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_video_mute', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_video_mute', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['mute-mobile'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_video_mute_mobile', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_video_mute_mobile', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['background'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_video_background', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_video_background', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['background-single'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_video_background_single', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_video_background_single', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['privacy'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_video_privacy', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_video_privacy', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['later'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_video_later', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_video_later', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        switch ($args[0]) {
            case 'on':
                update_option('fifu_video', 'toggleon', 'no'); // toggle
                break;
            case 'off':
                update_option('fifu_video', 'toggleoff', 'no'); // toggle
                break;
        }
    }

    // license key

    function key($args, $assoc_args) {
        if (!empty($assoc_args['email'])) {
            update_option('fifu_email', $args[0], 'no');
            return;
        }
        if (!empty($assoc_args['number'])) {
            update_option('fifu_key', $args[0], 'no');
            return;
        }
    }

    // metadata

    function metadata($args) {
        switch ($args[0]) {
            case 'on':
                update_option('fifu_fake_stop', false, 'no');
                fifu_enable_fake();
                set_transient('fifu_image_metadata_counter', fifu_db_count_urls_without_metadata(), 0);
                update_option('fifu_fake', 'toggleon', 'no'); // toggle
                break;
            case 'off':
                update_option('fifu_fake_created', false, 'no');
                update_option('fifu_fake_stop', true, 'no');
                set_transient('fifu_image_metadata_counter', fifu_db_count_urls_without_metadata(), 0);
                update_option('fifu_fake', 'toggleoff', 'no'); // toggle
                break;
        }
    }

    function clean() {
        fifu_db_enable_clean();
        update_option('fifu_data_clean', 'toggleoff', 'no');
        set_transient('fifu_image_metadata_counter', fifu_db_count_urls_without_metadata(), 0);
    }

    function dimensions() {
        fifu_run_get_and_save_sizes_api(new WP_REST_Request());
    }

    function sizes() {
        
    }

    function schedule($args, $assoc_args) {
        switch ($args[0]) {
            case 'on':
                update_option('fifu_cron_metadata', 'toggleon', 'no'); // toggle
                if (!wp_next_scheduled('fifu_create_metadata_event'))
                    wp_schedule_event(time(), 'fifu_schedule_metadata', 'fifu_create_metadata_event');
                break;
            case 'off':
                update_option('fifu_cron_metadata', 'toggleoff', 'no'); // toggle
                wp_clear_scheduled_hook('fifu_create_metadata_event');
                delete_transient('fifu_metadata_semaphore');
                break;
        }
    }

    // performance

    function cdn($args, $assoc_args) {
        if (!empty($assoc_args['social'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_cdn_social', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_cdn_social', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['crop'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_cdn_crop', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_cdn_crop', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['content'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_cdn_content', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_cdn_content', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        switch ($args[0]) {
            case 'on':
                update_option('fifu_photon', 'toggleon', 'no'); // toggle
                break;
            case 'off':
                update_option('fifu_photon', 'toggleoff', 'no'); // toggle
                break;
        }
    }

    function lazy($args) {
        switch ($args[0]) {
            case 'on':
                update_option('fifu_lazy', 'toggleon', 'no'); // toggle
                break;
            case 'off':
                update_option('fifu_lazy', 'toggleoff', 'no'); // toggle
                break;
        }
    }

    // audio

    function audio($args) {
        switch ($args[0]) {
            case 'on':
                update_option('fifu_audio', 'toggleon', 'no'); // toggle
                break;
            case 'off':
                update_option('fifu_audio', 'toggleoff', 'no'); // toggle
                break;
        }
    }

    // social

    function social($args, $assoc_args) {
        if (!empty($assoc_args['image-only'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_social_image_only', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_social_image_only', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        switch ($args[0]) {
            case 'on':
                update_option('fifu_social', 'toggleon', 'no'); // toggle
                break;
            case 'off':
                update_option('fifu_social', 'toggleoff', 'no'); // toggle
                break;
        }
    }

    function rss($args, $assoc_args) {
        switch ($args[0]) {
            case 'on':
                update_option('fifu_rss', 'toggleon', 'no'); // toggle
                break;
            case 'off':
                update_option('fifu_rss', 'toggleoff', 'no'); // toggle
                break;
        }
    }

    function bbpress($args, $assoc_args) {
        if (!empty($assoc_args['fields'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_bbpress_fields', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_bbpress_fields', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['title'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_bbpress_title', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_bbpress_title', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
    }

    // woocommerce

    function woo($args, $assoc_args) {
        if (!empty($assoc_args['lightbox'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_wc_lbox', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_wc_lbox', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['zoom'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_wc_zoom', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_wc_zoom', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['category-auto'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_auto_category', 'toggleon', 'no'); // toggle
                    if (!get_option('fifu_auto_category_created')) {
                        fifu_db_insert_auto_category_image();
                        update_option('fifu_auto_category_created', true, 'no');
                    }
                    break;
                case 'off':
                    update_option('fifu_auto_category', 'toggleoff', 'no'); // toggle
                    update_option('fifu_auto_category_created', false, 'no');
                    break;
            }
            return;
        }
        if (!empty($assoc_args['variable'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_variation', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_variation', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['order-email'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_order_email', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_order_email', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['gallery'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_gallery', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_gallery', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['adaptive'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_adaptive_height', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_adaptive_height', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['videos-before'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_videos_before', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_videos_before', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['buy-text'])) {
            update_option('fifu_buy_text', $args[0], 'no');
            return;
        }
        if (!empty($assoc_args['buy-disclaimer'])) {
            update_option('fifu_buy_disclaimer', $args[0], 'no');
            return;
        }
        if (!empty($assoc_args['buy-cf'])) {
            update_option('fifu_buy_cf', $args[0], 'no');
            return;
        }
        if (!empty($assoc_args['buy'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_buy', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_buy', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
    }

}

WP_CLI::add_command('fifu', 'fifu_cli');

add_action('wp_insert_post', function ($post_id, $post, $update) {
    fifu_update_fake_attach_id($post->ID);
}, 10, 3);

