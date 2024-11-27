<?php

function fifu_convert($url) {
    if (fifu_from_google_drive($url))
        return fifu_google_drive_url($url);

    if (fifu_from_onedrive($url))
        return fifu_onedrive_url($url);

    if (fifu_has_special_char($url))
        return fifu_escape_special_char($url);

    return $url;
}

//Google Drive

function fifu_from_google_drive($url) {
    return strpos($url, 'drive.google.com') !== false;
}

function fifu_google_drive_id($url) {
    preg_match("/[-\w]{25,}/", $url, $matches);
    return $matches[0];
}

function fifu_google_drive_url($url) {
    return 'https://drive.google.com/uc?id=' . fifu_google_drive_id($url);
}

function fifu_is_google_drive_file($url) {
    return strpos($url, 'drive.google.com/file') !== false;
}

function fifu_get_final_google_drive_url($url) {
    $headers = get_headers($url, 1);
    return $headers['Location'];
}

//OneDrive

function fifu_from_onedrive($url) {
    return strpos($url, '1drv.ms') !== false;
}

function fifu_onedrive_id($url) {
    return explode("?", explode("/", $url)[4])[0];
}

function fifu_onedrive_url($url) {
    $id = fifu_onedrive_id($url);
    return "https://api.onedrive.com/v1.0/shares/{$id}/root/content";
}

//Special char

function fifu_has_special_char($url) {
    return strpos($url, "'") !== false;
}

function fifu_escape_special_char($url) {
    return str_replace("'", "%27", $url);
}

