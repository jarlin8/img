<?php

$tabs = array(
    'main' => array('name' => __('General', "gcore_translate")),
    'types' => array('name' => __('File types', "gcore_translate")),
    'folders' => array('name' => __('Folders', "gcore_translate")),
    'exceptions' => array('name' => __('Exceptions', "gcore_translate"))
);
if (isset($_GET['tab']) and in_array($_GET['tab'], array('types', 'folders', 'exceptions'))) {
    $get_tab = $_GET['tab'];
} else {
    $get_tab = 'main';
}


if (isset($_GET['del'])) {
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
        $gcore_folder_wp = get_option('gcore_folder_wp');
        $gcore_folder_wp_checked = $gcore_folder_wp == 1 ? ' checked="checked"' : '';
    } else {
        $gcore_folder_templates_checked = ' disabled';
        $gcore_folder_plugins_checked = ' disabled';
        $gcore_folder_content_checked = ' disabled';
        $gcore_folder_wp_checked = ' disabled';
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
    if ($gcore_enable_cdn == 0) {
        $li_style = "style=\"opacity:0.3\"";
        $href = "#";
    } else {
        $li_style = "";
        $href = $admin_url . 'admin.php?page=gcore_labs&tab=' . $key;
    }
    $data .= '<a ' . $li_style . ' class="g-d nav-tab' . $c . '" href="' . $href . '" data-href="' . $admin_url . 'admin.php?page=gcore_labs&tab=' . $key . '">' . $value['name'] . '</a>';
}
$data .= '</h3>
</div>
';

if ($get_tab == "main") {
    $data .= '<table class="form-table" style="max-width: 600px;">
            <tr class="form-field form-required">
                <td colspan="2">
                    <label class="el-checkbox el-checkbox-sm">
				        <input id="gcore_enable_cdn" type="checkbox" class="g-c" data-t="checkbox" data-o="gcore_enable_cdn" value="1" ' . $gcore_enable_cdn_checked . '>
				        <span class="el-checkbox-style  pull-right"></span>
				        <span class="margin-r">' . __("Enable CDN", "gcore_translate") . '</span>
			        </label>
                    <p class="description" id="tagline-description">' . __("In the paths to files conforming to the rules specified below, a domain will be replaced with a personal domain.", "gcore_translate") . '</p>
                </td>
            </tr>           
            <tr class="form-field form-required">
                <td scope="row"><label for="user_login">' . __("Personal domain (for configuring CNAME)", "gcore_translate") . '</label></td>
                <td>
                    <input type="text" class="g-d g-c" id="gcore_cdn_url" data-t="url" data-o="gcore_cdn_url" ' . $gcore_cdn_disabled . ' value="' . $gcore_cdn_url . '" placeholder="' . __("Example", "gcore_translate") . ': https://cdn.example.com/">
                    <p class="description" id="tagline-description">' . __("Specify the personal domain with a scheme corresponding to the one specified in G-Core Labs control panel. If you are using a domain in your zone, make sure that this domain is added in the DNS provider settings.", "gcore_translate") . '</p>
                </td>
            </tr>
            <tr>
                <td></td>
                <td style="text-align: right"><a href="' . $admin_url . 'admin.php?page=gcore_labs&tab=types" class="button-gcore">' . __("Next", "gcore_translate") . '</a></td>
            </tr>
        </table>
';
}
if ($get_tab == "types") {
    $data .= '<div class="clear"></div>
        <p class="description" style="margin:15px 0 0 5px">' . __("Specify the types of files you want to distribute via CDN.", "gcore_translate") . '</p>
        <table class="form-table" style="max-width: 600px;">
            <tr class="form-field form-required">
                <td>
                    <label class="el-checkbox el-checkbox-sm">
				        <input id="gcore_type_image" type="checkbox" class="g-c list-ch" data-t="checkbox" data-o="gcore_type_image" value="1" ' . $gcore_type_image_checked . '>
				        <span class="el-checkbox-style  pull-right"></span>
				        <span class="margin-r">' . __("Type Images", "gcore_translate") . '</span>
			        </label>   
			    </td>             
                <td>
                    <label class="el-checkbox el-checkbox-sm">
				        <input id="gcore_type_video" type="checkbox" class="g-c list-ch" data-t="checkbox" data-o="gcore_type_video" value="1" ' . $gcore_type_video_checked . '>
				        <span class="el-checkbox-style  pull-right"></span>
				        <span class="margin-r">' . __("Type Video", "gcore_translate") . '</span>
			        </label>                
                </td>
                <td>
                    <label class="el-checkbox el-checkbox-sm">
				        <input id="gcore_type_audio" type="checkbox" class="g-c list-ch" data-t="checkbox" data-o="gcore_type_audio" value="1" ' . $gcore_type_audio_checked . '>
				        <span class="el-checkbox-style  pull-right"></span>
				        <span class="margin-r">' . __("Type Audio", "gcore_translate") . '</span>
			        </label>
                </td>
            </tr>
            <tr class="form-field form-required">
                <td>
                    <label class="el-checkbox el-checkbox-sm">
				        <input id="gcore_type_js" type="checkbox" class="g-c list-ch" data-t="checkbox" data-o="gcore_type_js" type="checkbox" value="1" ' . $gcore_type_js_checked . '>
				        <span class="el-checkbox-style  pull-right"></span>
				        <span class="margin-r">' . __("Type JS", "gcore_translate") . '</span>
			        </label>
                </td>
                <td>
                    <label class="el-checkbox el-checkbox-sm">
				        <input id="gcore_type_css" type="checkbox" class="g-c list-ch" data-t="checkbox" data-o="gcore_type_css" type="checkbox" value="1" ' . $gcore_type_css_checked . '>
				        <span class="el-checkbox-style  pull-right"></span>
				        <span class="margin-r">' . __("Type CSS", "gcore_translate") . '</span>
			        </label>
                </td>
                <td>
                    <label class="el-checkbox el-checkbox-sm">
				        <input id="gcore_type_archive" type="checkbox" class="g-c list-ch" data-t="checkbox" data-o="gcore_type_archive" type="checkbox" value="1" ' . $gcore_type_archive_checked . '>
				        <span class="el-checkbox-style  pull-right"></span>
				        <span class="margin-r">' . __("Type Archive", "gcore_translate") . '</span>
			        </label>
                </td>
            </tr>
            <tr>
                <td colspan="3">
		            <label class="el-switch">
			            <input id="gcore_type_advanced" type="checkbox" class="g-c" data-t="checkbox" data-o="gcore_type_advanced" type="checkbox" value="1" ' . $gcore_type_advanced_checked . '>
			            <span class="el-switch-style"></span>
		            </label>
		            <span class="margin-r">' . __("Advanced property", "gcore_translate") . '</span>                
                </td>
            </tr>
        ';
    if ($gcore_type_advanced == 0) $disabled = "display:none;"; else $disabled = '';
    $data .= '
        <table class="form-table list-advanced advanced-show" data-t="types" style="max-width: 600px;' . $disabled . '"></table>
        <table class="form-table" style="max-width: 600px;">
            <tr>
                <td style="text-align: left"><a href="' . $admin_url . 'admin.php?page=gcore_labs&tab=main" class="button-gcore">' . __("Previous", "gcore_translate") . '</a></td>
                <td style="text-align: right"><a href="' . $admin_url . 'admin.php?page=gcore_labs&tab=folders" class="button-gcore">' . __("Next", "gcore_translate") . '</a></td>
            </tr>            
        </table>    
    ';
}
if ($get_tab == "folders") {
    $data .= '<div class="clear"></div>
        <p class="description" style="margin:15px 0 0 5px;max-width: 600px;">' . __("Specify folders containing files you want to distribute via CDN. Please note that only files that match file types specified on the File Types tab will be delivered via CDN.", "gcore_translate") . '</p>
        <table class="form-table" style="max-width: 600px;">
            <tr class="form-field form-required">
                <td>
                    <label class="el-checkbox el-checkbox-sm">
				        <input id="gcore_folder_templates" type="checkbox" class="g-c list-ch" data-t="checkbox" data-o="gcore_folder_templates" type="checkbox"  value="1" ' . $gcore_folder_templates_checked . '>
				        <span class="el-checkbox-style  pull-right"></span>
				        <span class="margin-r">' . __("Folder Templates", "gcore_translate") . '</span>
			        </label>  
                </td>
                <td>
                    <label class="el-checkbox el-checkbox-sm">
				        <input id="gcore_folder_plugins" type="checkbox" class="g-c list-ch" data-t="checkbox" data-o="gcore_folder_plugins" type="checkbox" value="1" ' . $gcore_folder_plugins_checked . '>
				        <span class="el-checkbox-style  pull-right"></span>
				        <span class="margin-r">' . __("Folder Plugins", "gcore_translate") . '</span>
			        </label>
                </td>
                <td>
                    <label class="el-checkbox el-checkbox-sm">
				        <input id="gcore_folder_content" type="checkbox" class="g-c list-ch" data-t="checkbox" data-o="gcore_folder_content" type="checkbox" value="1" ' . $gcore_folder_content_checked . '>
				        <span class="el-checkbox-style  pull-right"></span>
				        <span class="margin-r">' . __("Folder Content", "gcore_translate") . '</span>
			        </label>
                </td>
                <td>
                    <label class="el-checkbox el-checkbox-sm">
				        <input id="gcore_folder_wp" type="checkbox" class="g-c list-ch" data-t="checkbox" data-o="gcore_folder_wp" type="checkbox" value="1" ' . $gcore_folder_wp_checked . '>
				        <span class="el-checkbox-style  pull-right"></span>
				        <span class="margin-r">' . __("Folder Wordpress", "gcore_translate") . '</span>
			        </label>
                </td>
            </tr>
            <tr>
                <td colspan="4">
		            <label class="el-switch">
			            <input id="gcore_folder_advanced" type="checkbox" class="g-c" data-t="checkbox" data-o="gcore_folder_advanced" type="checkbox" value="1" ' . $gcore_folder_advanced_checked . '>
			            <span class="el-switch-style"></span>
		            </label>
		            <span class="margin-r">' . __("Advanced property", "gcore_translate") . '</span>                
                </td>
            </tr>
        </table>
        ';
    if ($gcore_folder_advanced == 0) $disabled = "display:none;"; else $disabled = '';
    $data .= '<table class="form-table list-advanced advanced-show" data-t="folders" style="max-width: 600px;' . $disabled . '"></table>
        <table class="form-table" style="max-width: 600px;">
            <tr>
                <td style="text-align: left"><a href="' . $admin_url . 'admin.php?page=gcore_labs&tab=types" class="button-gcore">' . __("Previous", "gcore_translate") . '</a></td>
                <td style="text-align: right"><a href="' . $admin_url . 'admin.php?page=gcore_labs&tab=exceptions" class="button-gcore">' . __("Next", "gcore_translate") . '</a></td>
            </tr>            
        </table>    
    ';
}
if ($get_tab == "exceptions") {
    $data .= '<div class="clear"></div>
        <p class="description" style="margin:15px 0 0 5px">' . __("Specify the URLs you want to add to the exceptions list and not distribute them via CDN.", "gcore_translate") . '</p>
        <table class="form-table list-advanced advanced-show" data-t="exceptions" style="max-width: 600px;' . $disabled . '"></table>
        <table class="form-table" style="max-width: 600px;">
            <tr>
                <td style="text-align: left"><a href="' . $admin_url . 'admin.php?page=gcore_labs&tab=folders" class="button-gcore">' . __("Previous", "gcore_translate") . '</a></td>
                <td></td>
            </tr>            
        </table>    
    ';
}

$data .= '
	<script>
        function msg(t) {
            if(t == "save") {
                jQuery.amaran({"message":"' . __("Saved", "gcore_translate") . '"});
            }
            if(t == "del") {
                jQuery.amaran({"message":"' . __("Deleted", "gcore_translate") . '"});
            }
            if(t == "add") {
                jQuery.amaran({"message":"' . __("Added", "gcore_translate") . '"});
            }
        }
	</script>
';

echo $data;