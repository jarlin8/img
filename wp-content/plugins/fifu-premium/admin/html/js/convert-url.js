function fifu_convert($url) {
    if (!$url)
        return $url;

    if (fifu_from_google_drive($url))
        return fifu_google_drive_url($url);

    if (fifu_from_onedrive($url))
        return fifu_onedrive_url($url);

    if (fifu_has_special_char($url))
        return fifu_escape_special_char($url);

    return $url;
}

function fifu_convert_video($url) {
    if (fifu_from_youtube_shorts($url))
        return fifu_youtube_url($url);

    if (fifu_from_amazon($url))
        return fifu_amazon_url($url);

    return $url;
}

//Google Drive

function fifu_from_google_drive($url) {
    return $url.includes('drive.google.com');
}

function fifu_google_drive_id($url) {
    return $url.match(/[-\w]{25,}/);
}

function fifu_google_drive_url($url) {
    return 'https://drive.google.com/uc?id=' + fifu_google_drive_id($url);
}

//OneDrive

function fifu_from_onedrive($url) {
    return $url.includes('1drv.ms');
}

function fifu_onedrive_id($url) {
    return $url.split('/')[4].split('?')[0];
}

function fifu_onedrive_url($url) {
    id = fifu_onedrive_id($url);
    return `https://api.onedrive.com/v1.0/shares/${id}/root/content`;
}

//YouTube Shorts

function fifu_from_youtube_shorts($url) {
    return $url.match('youtube.com/shorts/');
}

function fifu_youtube_url($url) {
    return $url.replace('shorts/', 'watch?v=');
}

//Amazon.com

function fifu_from_amazon($url) {
    return $url.match('www.amazon.');
}

function fifu_amazon_url($url) {
    const workerUrl = 'https://corsproxy.io/?https://get-amazon-video-url.fifu.workers.dev/?url=' + $url;

    video_url = null;

    jQuery.ajax({
        url: workerUrl,
        type: 'GET',
        async: false,
        success: function (data) {
            videoUrl = data[0]['url'];
            thumbUrl = data[0]['slateUrl'].replace('522', '1600');
            thumbId = thumbUrl.split('/')[5];
            video_url = `${videoUrl}?thumb-id=${thumbId}`;
        },
        error: function (error) {
            console.error(error);
        }
    });

    return video_url;
}

//Special char

function fifu_has_special_char($url) {
    return $url.includes("'");
}

function fifu_escape_special_char($url) {
    return $url.replace("'", "%27");
}
