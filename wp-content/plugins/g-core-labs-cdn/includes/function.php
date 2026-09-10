<?php

add_action('wp_ajax_gcore_save', 'gcore_ajax_save');
function gcore_ajax_save()
{
    $type = $_POST['t'];
    if (in_array($_POST['t'], array('url', 'int', 'checkbox'))) {
        $value = '';
        $option = $_POST['o'];
        if ($type == 'url') {
            $value = sanitize_text_field(esc_url(trim($_POST['v'])));
            if ($value != '') {
                $value = trailingslashit(untrailingslashit($value));
            }
        } elseif ($type == 'int') {
            $value = intval($_POST['v']);
        } elseif ($type == 'checkbox') {
            $value = intval($_POST['v']);
            if ($option == 'gcore_type_advanced') {
                update_option('gcore_type_image', 0);
                update_option('gcore_type_video', 0);
                update_option('gcore_type_audio', 0);
                update_option('gcore_type_js', 0);
                update_option('gcore_type_css', 0);
                update_option('gcore_type_archive', 0);
            }
            if ($option == 'gcore_folder_advanced') {
                update_option('gcore_folder_templates', 0);
                update_option('gcore_folder_plugins', 0);
                update_option('gcore_folder_content', 0);
            }
        }
        update_option($option, $value);
        echo $value;
    } else {
        echo 0;
    }
    wp_die();
}

add_action('wp_ajax_gcore_advance_param_add', 'gcore_ajax_advance_param_add');
function gcore_ajax_advance_param_add()
{
    $type = $_POST['t'];
    if (in_array($type, array('types', 'folders', 'exceptions'))) {
        $gcore_array = get_option('gcore_cdn_' . $type);
        $value = '';
        if($type == "types") {
            $value = preg_replace('/[^a-zA-Z0-9]/ui', '', strtolower(trim($_POST['v'])));
        } elseif($type == "folders") {
            $value = sanitize_text_field(trim($_POST['v']));
        } elseif($type == "exceptions") {
            $value = sanitize_text_field(esc_url(trim($_POST['v'])));
            $value = explode("?", $value);
            $value = explode("&", $value[0]);
            $value = $value[0];
        }
        if ($value != '') {
            if($type == "folders") {
                $value = trailingslashit(untrailingslashit($value));
                $first = substr($value, 0, 1);
                if ($first != "/") {
                    $value = "/" . $value;
                }
            }
            if ($gcore_array != '') {
                $gcore_array = json_decode($gcore_array, true);
            } else {
                $gcore_array = array();
            }
            array_push($gcore_array, $value);
            $gcore_array = array_unique($gcore_array);
            $gcore_array = json_encode($gcore_array);
            update_option('gcore_cdn_' . $type, $gcore_array);
            echo 1;
        } else {
            echo 0;
        }
    } else {
        echo 0;
    }
    wp_die();
}

add_action('wp_ajax_gcore_advance_param_del', 'gcore_ajax_advance_param_del');
function gcore_ajax_advance_param_del()
{
    $type = $_POST['t'];
    if (in_array($type, array('types', 'folders', 'exceptions'))) {
        $gcore_array = get_option('gcore_cdn_' . $type);
        $value = '';
        if($type == "types") {
            $value = preg_replace('/[^a-zA-Z0-9]/ui', '', strtolower(trim($_POST['v'])));
        } elseif($type == "folders") {
            $value = sanitize_text_field(trim($_POST['v']));
        } elseif($type == "exceptions") {
            $value = sanitize_text_field(esc_url(trim($_POST['v'])));
            $value = explode("?", $value);
            $value = explode("&", $value[0]);
            $value = $value[0];
        }
        if ($value != '') {
            if($type == "folders") {
                $value = trailingslashit(untrailingslashit($value));
                $first = substr($value, 0, 1);
                if ($first != "/") {
                    $value = "/" . $value;
                }
            }
            if ($gcore_array != '') {
                $gcore_array = json_decode($gcore_array, true);
                if (($k = array_search($value, $gcore_array)) !== false) {
                    unset($gcore_array[$k]);
                    $gcore_array = json_encode($gcore_array);
                    update_option('gcore_cdn_' . $type, $gcore_array);
                    echo 1;
                } else {
                    echo 0;
                }
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
    } else {
        echo 0;
    }
    wp_die();
}

add_action('wp_ajax_gcore_advance_param_show', 'gcore_ajax_advance_param_show');
function gcore_ajax_advance_param_show()
{

    $type = $_POST['t'];
    $data = "";
    if (in_array($type, array('types', 'folders', 'exceptions'))) {

        $gcore_array = get_option('gcore_cdn_' . $type);
        $gcore_array = json_decode($gcore_array, true);
        if ($gcore_array == '') {
            $gcore_array = array();
        }
        $example = '';
        if ($type == 'types') {
            $example = 'jpg';
        } elseif ($type == 'folders') {
            $example = '/wp-content/uploads/';
        } elseif ($type == 'exceptions') {
            $example = 'https://example.com/exepstions-page.html';
        }

        foreach ($gcore_array as $element) {
            $data .= '<tr class="form-field form-required">
                <td scope="row">' . $element . '</td>
                <td><button type="buttn" class="button-gcore g-delete" data-e="' . $element . '" data-type="' . $type . '">' . __("Delete", "gcore_translate") . '</button></td>
            </tr>';
        }
        $data .= '<tr class="form-field form-required">
                <td scope="row"><input type="text" class="new-' . $type . '" placeholder="' . __("Example", "gcore_translate") . ': ' . $example . '"></td>
            <td><input type="button" data-type="' . $type . '" class="button-gcore g-add" value="' . __("Add", "gcore_translate") . '"></td>
            </tr>
        ';
    }
    echo $data;
    wp_die();
}