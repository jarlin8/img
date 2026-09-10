<?php

$tabs = array(
    'main' => array('name' => __('General', "gcore_translate")),
    'types' => array('name' => __('File types', "gcore_translate")),
    'folders' => array('name' => __('Folders', "gcore_translate")),
    'exceptions' => array('name' => __('Exceptions', "gcore_translate"))
);
if (isset($_GET['tab']) AND in_array($_GET['tab'], array('types', 'folders', 'exceptions'))) {
    $get_tab = $_GET['tab'];
} else {
    $get_tab = 'main';
}


if (isset($_POST['save'])) {
    if ($get_tab == "main") {
        $gcore_cdn_url = sanitize_text_field(esc_url(trim($_POST['gcore_cdn_url'])));
        if ($gcore_cdn_url != '') {
            $gcore_cdn_url = trailingslashit(untrailingslashit($gcore_cdn_url));
        }
        update_option('gcore_cdn_url', $gcore_cdn_url);
        update_option('gcore_enable_cdn', intval($_POST['gcore_enable_cdn']));
    }
    if ($get_tab == "types") {
        $gcore_cdn_types = get_option('gcore_cdn_types');
        $new_type = preg_replace('/[^a-zA-Z0-9]/ui', '', strtolower(trim($_POST['new_type'])));
        if ($new_type != '') {
            if ($gcore_cdn_types != '') {
                $gcore_cdn_types = json_decode($gcore_cdn_types, true);
            } else {
                $gcore_cdn_types = array();
            }
            array_push($gcore_cdn_types, $new_type);
            $gcore_cdn_types = array_unique($gcore_cdn_types);
            $gcore_cdn_types = json_encode($gcore_cdn_types);
            update_option('gcore_cdn_types', $gcore_cdn_types);
        }
        update_option('gcore_type_image', intval($_POST['gcore_type_image']));
        update_option('gcore_type_video', intval($_POST['gcore_type_video']));
        update_option('gcore_type_audio', intval($_POST['gcore_type_audio']));
        update_option('gcore_type_js', intval($_POST['gcore_type_js']));
        update_option('gcore_type_css', intval($_POST['gcore_type_css']));
        update_option('gcore_type_archive', intval($_POST['gcore_type_archive']));
        update_option('gcore_type_advanced', intval($_POST['gcore_type_advanced']));

    }
    if ($get_tab == "folders") {
        $gcore_cdn_folders = get_option('gcore_cdn_folders');
        $new_folder = sanitize_text_field(trim($_POST['new_folder']));
        if ($new_folder != '') {
            $new_folder = trailingslashit(untrailingslashit($new_folder));
            $first = substr($new_folder, 0, 1);
            if ($first != "/") {
                $new_folder = "/" . $new_folder;
            }
            if ($gcore_cdn_folders != '') {
                $gcore_cdn_folders = json_decode($gcore_cdn_folders, true);
            } else {
                $gcore_cdn_folders = array();
            }
            array_push($gcore_cdn_folders, $new_folder);
            $gcore_cdn_folders = array_unique($gcore_cdn_folders);
            $gcore_cdn_folders = json_encode($gcore_cdn_folders);
            update_option('gcore_cdn_folders', $gcore_cdn_folders);
        }
        update_option('gcore_folder_templates', intval($_POST['gcore_folder_templates']));
        update_option('gcore_folder_plugins', intval($_POST['gcore_folder_plugins']));
        update_option('gcore_folder_content', intval($_POST['gcore_folder_content']));
        update_option('gcore_folder_advanced', intval($_POST['gcore_folder_advanced']));

    }
    if ($get_tab == "exceptions") {
        $gcore_cdn_exceptions = get_option('gcore_cdn_exceptions');
        $new_exception = sanitize_text_field(esc_url(trim($_POST['new_exception'])));
        $new_exception = explode("?", $new_exception);
        $new_exception = explode("&", $new_exception[0]);
        $new_exception = $new_exception[0];
        if ($new_exception != '') {
            if ($gcore_cdn_exceptions != '') {
                $gcore_cdn_exceptions = json_decode($gcore_cdn_exceptions, true);
            } else {
                $gcore_cdn_exceptions = array();
            }
            array_push($gcore_cdn_exceptions, $new_exception);
            $gcore_cdn_exceptions = array_unique($gcore_cdn_exceptions);
            $gcore_cdn_exceptions = json_encode($gcore_cdn_exceptions);
            update_option('gcore_cdn_exceptions', $gcore_cdn_exceptions);
        }
    }
} elseif (isset($_GET['del'])) {
    if ($get_tab == "types") {
        $gcore_cdn_types = get_option('gcore_cdn_types');
        $new_type = preg_replace('/[^a-zA-Z0-9]/ui', '', strtolower(trim($_GET['del'])));
        if ($new_type != '') {
            if ($gcore_cdn_types != '') {
                $gcore_cdn_types = json_decode($gcore_cdn_types, true);
                if (($k = array_search($new_type, $gcore_cdn_types)) !== false) {
                    unset($gcore_cdn_types[$k]);
                    $gcore_cdn_types = json_encode($gcore_cdn_types);
                    update_option('gcore_cdn_types', $gcore_cdn_types);
                }
            }
        }
    }
    if ($get_tab == "folders") {
        $gcore_cdn_folders = get_option('gcore_cdn_folders');
        $new_folder = sanitize_text_field(trim($_GET['del']));
        if ($new_folder != '') {
            $new_folder = trailingslashit(untrailingslashit($new_folder));
            $first = substr($new_folder, 0, 1);
            if ($first != "/") {
                $new_folder = "/" . $new_folder;
            }
            if ($gcore_cdn_folders != '') {
                $gcore_cdn_folders = json_decode($gcore_cdn_folders, true);

                if (($k = array_search($new_folder, $gcore_cdn_folders)) !== false) {
                    unset($gcore_cdn_folders[$k]);
                    $gcore_cdn_folders = json_encode($gcore_cdn_folders);
                    update_option('gcore_cdn_folders', $gcore_cdn_folders);
                }
            }
        }
    }
    if ($get_tab == "exceptions") {
        $gcore_cdn_exceptions = get_option('gcore_cdn_exceptions');
        $new_exception = sanitize_text_field(esc_url(trim($_GET['del'])));
        if ($new_exception != '') {
            if ($gcore_cdn_exceptions != '') {
                $gcore_cdn_exceptions = json_decode($gcore_cdn_exceptions, true);

                if (($k = array_search($new_exception, $gcore_cdn_exceptions)) !== false) {
                    unset($gcore_cdn_exceptions[$k]);
                    $gcore_cdn_exceptions = json_encode($gcore_cdn_exceptions);
                    update_option('gcore_cdn_exceptions', $gcore_cdn_exceptions);
                }
            }
        }
    }
}

$gcore_enable_cdn = get_option('gcore_enable_cdn');
$gcore_cdn_disabled = $gcore_enable_cdn == 1 ? '' : ' disabled';

if ($get_tab == "main") {
    $gcore_cdn_url = get_option('gcore_cdn_url');
    $gcore_enable_cdn_checked = $gcore_enable_cdn == 1 ? ' checked="checked"' : '';
}
if ($get_tab == "types") {

    $gcore_type_advanced = get_option('gcore_type_advanced');
    $gcore_type_advanced_checked = $gcore_type_advanced == 1 ? ' checked="checked"' : '';

    if ($gcore_type_advanced == 0) {
        $gcore_type_image = get_option('gcore_type_image');
        $gcore_type_image_checked = $gcore_type_image == 1 ? ' checked="checked"' : '';
        $gcore_type_video = get_option('gcore_type_video');
        $gcore_type_video_checked = $gcore_type_video == 1 ? ' checked="checked"' : '';
        $gcore_type_audio = get_option('gcore_type_audio');
        $gcore_type_audio_checked = $gcore_type_audio == 1 ? ' checked="checked"' : '';
        $gcore_type_js = get_option('gcore_type_js');
        $gcore_type_js_checked = $gcore_type_js == 1 ? ' checked="checked"' : '';
        $gcore_type_css = get_option('gcore_type_css');
        $gcore_type_css_checked = $gcore_type_css == 1 ? ' checked="checked"' : '';
        $gcore_type_archive = get_option('gcore_type_archive');
        $gcore_type_archive_checked = $gcore_type_archive == 1 ? ' checked="checked"' : '';
    } else {
        $gcore_type_image_checked = ' disabled';
        $gcore_type_video_checked = ' disabled';
        $gcore_type_audio_checked = ' disabled';
        $gcore_type_js_checked = ' disabled';
        $gcore_type_css_checked = ' disabled';
        $gcore_type_archive_checked = ' disabled';
    }


    $gcore_cdn_types = get_option('gcore_cdn_types');
    $gcore_cdn_types = json_decode($gcore_cdn_types, true);
    if ($gcore_cdn_types == '') {
        $gcore_cdn_types = array();
    }
}
if ($get_tab == "folders") {

    $gcore_folder_advanced = get_option('gcore_folder_advanced');
    $gcore_folder_advanced_checked = $gcore_folder_advanced == 1 ? ' checked="checked"' : '';

    if ($gcore_folder_advanced == 0) {
        $gcore_folder_templates = get_option('gcore_folder_templates');
        $gcore_folder_templates_checked = $gcore_folder_templates == 1 ? ' checked="checked"' : '';
        $gcore_folder_plugins = get_option('gcore_folder_plugins');
        $gcore_folder_plugins_checked = $gcore_folder_plugins == 1 ? ' checked="checked"' : '';
        $gcore_folder_content = get_option('gcore_folder_content');
        $gcore_folder_content_checked = $gcore_folder_content == 1 ? ' checked="checked"' : '';
    } else {
        $gcore_folder_templates_checked = ' disabled';
        $gcore_folder_plugins_checked = ' disabled';
        $gcore_folder_content_checked = ' disabled';
    }

    $gcore_cdn_folders = get_option('gcore_cdn_folders');
    $gcore_cdn_folders = json_decode($gcore_cdn_folders, true);
    if ($gcore_cdn_folders == '') {
        $gcore_cdn_folders = array();
    }
}
if ($get_tab == "exceptions") {
    $gcore_cdn_exceptions = get_option('gcore_cdn_exceptions');
    $gcore_cdn_exceptions = json_decode($gcore_cdn_exceptions, true);
    if ($gcore_cdn_exceptions == '') {
        $gcore_cdn_exceptions = array();
    }
}


$title_page = $tabs[$get_tab]['name'];

$admin_url = admin_url();
$data = '';
$data .= '
<h1>' . __("CDN settings", "gcore_translate") . ' - ' . $title_page . '</h1>
<div>
    <h3>
';

foreach ($tabs as $key => $value) {
    $c = $key == $get_tab ? " nav-tab-active" : '';
    $data .= '<a class="nav-tab' . $c . '" href="' . $admin_url . 'admin.php?page=gcore_labs&tab=' . $key . '">' . $value['name'] . '</a>';
}
$data .= '</h3>
</div>
<form method="post" name="preferences" id="preferences" class="validate">';

if ($get_tab == "main") {
    $data .= '<table class="form-table" style="max-width: 600px;">
            <tr class="form-field form-required">
                <td colspan="2">
                    <input type="checkbox" name="gcore_enable_cdn" id="gcore_enable_cdn" value="1" ' . $gcore_enable_cdn_checked . '> <label for="gcore_enable_cdn">' . __("Enable CDN", "gcore_translate") . '</label>
                    <p class="description" id="tagline-description">' . __("In the paths to files conforming to the rules specified below, a domain will be replaced with a personal domain.", "gcore_translate") . '</p>
                </td>
            </tr>           
            <tr class="form-field form-required">
                <td scope="row"><label for="user_login">' . __("Personal domain (for configuring CNAME)", "gcore_translate") . '</label></td>
                <td>
                    <input type="text" name="gcore_cdn_url" id="gcore_cdn_url" ' . $gcore_cdn_disabled . ' value="' . $gcore_cdn_url . '" placeholder="' . __("Example", "gcore_translate") . ': https://cdn.example.com/">
                    <p class="description" id="tagline-description">' . __("Specify the personal domain with a scheme corresponding to the one specified in G-Core Labs control panel. If you are using a domain in your zone, make sure that this domain is added in the DNS provider settings.", "gcore_translate") . '</p>
                </td>
            </tr>
            <tr>
                <td></td>
                <td style="text-align: right"><button type="submit" name="save" id="save" class="button button-primary"><span class="save">' . __("Save", "gcore_translate") . '</span><span class="save_go">' . __("Save and Go", "gcore_translate") . '</span></button></td>
            </tr>
        </table>
';
}
if ($get_tab == "types") {
    $data .= '<div class="clear"></div>
        <p class="description" style="margin:15px 0 0 5px">' . __("Specify the types of files you want to distribute via CDN.", "gcore_translate") . '</p>
        <table class="form-table" style="max-width: 600px;">
            <tr class="form-field form-required">
                <td><input type="checkbox" name="gcore_type_image" id="gcore_type_image" value="1" ' . $gcore_type_image_checked . '> <label for="gcore_type_image">' . __("Type Images", "gcore_translate") . '</label></td>
                <td><input type="checkbox" name="gcore_type_video" id="gcore_type_video" value="1" ' . $gcore_type_video_checked . '> <label for="gcore_type_video">' . __("Type Video", "gcore_translate") . '</label></td>
                <td><input type="checkbox" name="gcore_type_audio" id="gcore_type_audio" value="1" ' . $gcore_type_audio_checked . '> <label for="gcore_type_audio">' . __("Type Audio", "gcore_translate") . '</label></td>
            </tr>
            <tr class="form-field form-required">
                <td><input type="checkbox" name="gcore_type_js" id="gcore_type_js" value="1" ' . $gcore_type_js_checked . '> <label for="gcore_type_js">' . __("Type JS", "gcore_translate") . '</label></td>
                <td><input type="checkbox" name="gcore_type_css" id="gcore_type_css" value="1" ' . $gcore_type_css_checked . '> <label for="gcore_type_css">' . __("Type CSS", "gcore_translate") . '</label></td>
                <td><input type="checkbox" name="gcore_type_archive" id="gcore_type_archive" value="1" ' . $gcore_type_archive_checked . '> <label for="gcore_type_archive">' . __("Type Archive", "gcore_translate") . '</label></td>
            </tr>
        </table>
        <div style="margin: 10px"><input type="checkbox" name="gcore_type_advanced" id="gcore_type_advanced" value="1" ' . $gcore_type_advanced_checked . '> <label for="gcore_type_advanced">' . __("Advanced property", "gcore_translate") . '</label></div>
        ';
    $data .= '
        <table class="form-table" style="max-width: 600px;">
        ';
    if ($gcore_type_advanced == 1) {
        foreach ($gcore_cdn_types as $type) {
            $admin_url = admin_url();
            $data .= '<tr class="form-field form-required">
                <td scope="row">' . $type . '</td>
                <td><a ' . $gcore_cdn_disabled . ' href="' . $admin_url . 'admin.php?page=gcore_labs&tab=types&del=' . $type . '" class="button button-danger">' . __("Delete", "gcore_translate") . '</a></td>
            </tr>';
        }
        $data .= '<tr class="form-field form-required">
                <td scope="row"><input ' . $gcore_cdn_disabled . ' type="text" name="new_type" placeholder="' . __("Example", "gcore_translate") . ': jpg"></td>
            <td><input ' . $gcore_cdn_disabled . ' type="submit" name="save" class="button button-primary" value="' . __("Add", "gcore_translate") . '"></td>
            </tr>
        </table>
        ';
    }
    $data .= '<p class="submit"><input type="submit" name="save" id="save" class="button button-primary" value="' . __("Save", "gcore_translate") . '"></p>';
}
if ($get_tab == "folders") {
    $data .= '<div class="clear"></div>
        <p class="description" style="margin:15px 0 0 5px;max-width: 600px;">' . __("Specify folders containing files you want to distribute via CDN. Leave this field blank to distribute files from all folders via CDN. Please note that only files that match file types specified on the File Types tab will be delivered via CDN.", "gcore_translate") . '</p>
        <table class="form-table" style="max-width: 600px;">
            <tr class="form-field form-required">
                <td><input type="checkbox" name="gcore_folder_templates" id="gcore_folder_templates" value="1" ' . $gcore_folder_templates_checked . '> <label for="gcore_folder_templates">' . __("Folder Templates", "gcore_translate") . '</label></td>
                <td><input type="checkbox" name="gcore_folder_plugins" id="gcore_folder_plugins" value="1" ' . $gcore_folder_plugins_checked . '> <label for="gcore_folder_plugins">' . __("Folder Plugins", "gcore_translate") . '</label></td>
                <td><input type="checkbox" name="gcore_folder_content" id="gcore_folder_content" value="1" ' . $gcore_folder_content_checked . '> <label for="gcore_folder_content">' . __("Folder Content", "gcore_translate") . '</label></td>
            </tr>
        </table>
        <div style="margin: 10px"><input type="checkbox" name="gcore_folder_advanced" id="gcore_folder_advanced" value="1" ' . $gcore_folder_advanced_checked . '> <label for="gcore_folder_advanced">' . __("Advanced property", "gcore_translate") . '</label></div>
        ';
    $data .= '
        <table class="form-table" style="max-width: 600px;">
        ';
    if ($gcore_folder_advanced == 1) {
        foreach ($gcore_cdn_folders as $folder) {
            $folder_code = urlencode($folder);
            $data .= '<tr class="form-field form-required">
                <td scope="row">' . $folder . '</td>
                <td><a ' . $gcore_cdn_disabled . ' href="' . $admin_url . 'admin.php?page=gcore_labs&tab=folders&del=' . $folder_code . '" class="button button-danger">' . __("Delete", "gcore_translate") . '</a></td>
            </tr>';
        }
        $data .= '<tr class="form-field form-required">
            <td scope="row"><input ' . $gcore_cdn_disabled . ' type="text" name="new_folder" placeholder="' . __("Example", "gcore_translate") . ': /wp-content/uploads/"></td>
            <td><input ' . $gcore_cdn_disabled . ' type="submit" name="save" class="button button-primary" value="' . __("Add", "gcore_translate") . '"></td>
        </tr>
        </table>
        ';
    }
    $data .= '<p class="submit"><input type="submit" name="save" id="save" class="button button-primary" value="' . __("Save", "gcore_translate") . '"></p>';
}
if ($get_tab == "exceptions") {
    $data .= '<div class="clear"></div>
        <p class="description" style="margin:15px 0 0 5px">' . __("Specify the URLs you want to add to the exceptions list and not distribute them via CDN.", "gcore_translate") . '</p>
        <table class="form-table" style="max-width: 600px;">';

    foreach ($gcore_cdn_exceptions as $exception) {
        $delete_url = admin_url();
        $exception_code = urlencode($exception);

        $data .= '<tr class="form-field form-required">
                <td scope="row"><a href="' . $exception . '" target="_blank">' . $exception . '</a></td>
                <td><a ' . $gcore_cdn_disabled . ' href="' . $delete_url . 'admin.php?page=gcore_labs&tab=exceptions&del=' . $exception_code . '" class="button button-danger">' . __("Delete", "gcore_translate") . '</a></td>
            </tr>';
    }
    $data .= '<tr class="form-field form-required">
            <td scope="row"><input ' . $gcore_cdn_disabled . ' type="text" name="new_exception" placeholder="' . __("Example", "gcore_translate") . ': https://example.com/exepstions-page.html"></td>
            <td><input ' . $gcore_cdn_disabled . ' type="submit" name="save" class="button button-primary" value="' . __("Add", "gcore_translate") . '"></td>
        </tr>
        </table>';
}
$data .= '</form>';
echo $data;