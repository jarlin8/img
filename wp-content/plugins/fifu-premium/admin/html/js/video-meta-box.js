var fifuPreviousInputs = [];

function removeVideo() {
    jQuery("#fifu_video").hide();
    jQuery("#fifu_video_link").hide();

    jQuery("#fifu_video_local").hide();
    jQuery("#fifu_capture_thumbnail").hide();

    jQuery("#fifu_video_custom").hide();

    jQuery("#fifu_video_input_url").val("");

    jQuery("#fifu_video_button").show();
}

function previewVideo() {
    var $url = jQuery("#fifu_video_input_url").val();

    $new_url = fifu_convert_video($url);
    if ($url != $new_url) {
        jQuery("#fifu_video_input_url").val($new_url);
        $url = $new_url;
    }

    if ($url) {
        jQuery("#fifu_video_button").hide();

        let $src = srcVideo($url);
        if (isLocalVideoUrl($url)) {
            jQuery("#fifu_video_tag").attr("src", $src);
            jQuery("#fifu_video_local").show();
            jQuery("#fifu_capture_thumbnail").show();
            setTimeout(function () {
                capture();
            }, 500);
        } else if ($src) {
            jQuery("#fifu_video_iframe").attr("src", $src);
            jQuery("#fifu_video").show();
            jQuery("#fifu_capture_thumbnail").hide();
        } else {
            jQuery("#fifu_video_custom_tag").attr("src", $src);
            jQuery("#fifu_video_custom").show();
            jQuery("#fifu_capture_thumbnail").hide();
        }

        jQuery("#fifu_video_link").show();
    }
}

jQuery(document).ready(function () {
    // start
    fifu_video_get_sizes();

    url = jQuery("#fifu_video_input_url").val();
    fifuPreviousInputs['fifu_video_input_url'] = fifu_format_previous_input(url);

    // blur
    jQuery("#fifu_video_input_url").on('input', function (evt) {
        evt.stopImmediatePropagation();

        // ignore thumbnail function when it's just a parameter change
        url = jQuery(this).val();
        if (url && fifu_format_previous_input(url) != fifuPreviousInputs['fifu_video_input_url']) {
            fifu_video_get_sizes();
            fifuPreviousInputs['fifu_video_input_url'] = fifu_format_previous_input(url);
        }
    });

    // title
    jQuery("div#wooVideoUrlMetaBox").find('h2').replaceWith('<h4 style="top:5px;position:relative;"><span class="dashicons dashicons-video-alt3" style="font-size:15px"></span>' + jQuery("div#wooVideoUrlMetaBox").find('h2').text() + '</h4>');
    jQuery("div#videoUrlMetaBox").find('h2').replaceWith('<h4 style="top:7px;position:relative;"><span class="dashicons dashicons-video-alt3" style="font-size:15px"></span>' + jQuery("div#videoUrlMetaBox").find('h2').text() + '</h4>');
});

function fifu_video_get_sizes() {
    video_url = jQuery("#fifu_video_input_url").val();
    if (!video_url || (!video_url.startsWith("http") && !video_url.startsWith("//")))
        return;

    // custom
    if (!fifu_is_video(video_url))
        return;

    image_url = fifu_video_image_thumbnail(video_url, fifuVideoMetaBoxVars);
    fifu_video_get_image(image_url);
}

function fifu_video_get_image(url) {
    var image = new Image();
    jQuery(image).attr('onload', 'fifu_video_store_sizes(this);');
    jQuery(image).attr('src', url);
}

function fifu_video_store_sizes($) {
    jQuery("#fifu_video_input_image_width").val($.naturalWidth);
    jQuery("#fifu_video_input_image_height").val($.naturalHeight);
    if ($.naturalWidth == 120 && $.naturalHeight == 90)
        jQuery("#fifu_video_input_image_src").val($.src.replace('maxresdefault', 'mqdefault'));
    else
        jQuery("#fifu_video_input_image_src").val($.src);
}

function fifu_video_src(url) {
    var response;

    jQuery.ajax({
        method: "POST",
        url: fifuVideoMetaBoxVars.restUrl + 'fifu-premium/v2/video_src/',
        async: false,
        data: {
            "url": url,
        },
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-WP-Nonce', fifuVideoMetaBoxVars.nonce);
        },
        success: function (data) {
            response = data;
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        },
        complete: function (data) {
        },
    });

    return decodeURI(response);
}

function capture() {
    var canvas = document.getElementById('canvas');
    var video = document.getElementById('fifu_video_tag');
    jQuery('#canvas').attr('width', video.videoWidth);
    jQuery('#canvas').attr('height', video.videoHeight);
    canvas.getContext('2d').drawImage(video, 0, 0, video.videoWidth, video.videoHeight);
    var imageURL = canvas.toDataURL();
    jQuery('#fifu_video_captured_frame').val(imageURL);
    jQuery('#canvas').hide();
    setTimeout(function () {
        jQuery.fancybox.open('<img src="' + imageURL + '" style="max-height:600px">');
    }, 1000);
}
