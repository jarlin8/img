// add meta box icon
jQuery(document).ready(function () {
    jQuery("div#wooCommerceVideoGalleryMetaBox").find('h2').replaceWith('<h4 style="top:5px;position:relative;"><span class="dashicons dashicons-format-video" style="font-size:15px"></span>' + jQuery("div#wooCommerceVideoGalleryMetaBox").find('h2').text() + '</h4>');
});

// sizes
function fifu_woo_video_get_sizes(i) {
    video_url = jQuery('input[id^=fifu_video_input_url_' + i + ']').val();
    if (!video_url || (!video_url.startsWith("http") && !video_url.startsWith("//")))
        return;
    image_url = fifu_video_image_thumbnail(video_url, fifuVideoVars);
    fifu_woo_video_get_image(image_url, i);
}

function fifu_woo_video_get_image(url, i) {
    var image = new Image();
    jQuery(image).attr('onload', 'fifu_woo_video_store_sizes(this,' + i + ');');
    jQuery(image).attr('src', url);
}

function fifu_woo_video_store_sizes($, i) {
    jQuery("#fifu_video_input_width_" + i).val($.naturalWidth);
    jQuery("#fifu_video_input_height_" + i).val($.naturalHeight);
    if ($.naturalWidth == 120 && $.naturalHeight == 90)
        jQuery("#fifu_video_input_image_src_" + i).val($.src.replace('maxresdefault', 'mqdefault'));
    else
        jQuery("#fifu_video_input_image_src_" + i).val($.src);

    // load thumbnail gallery
    src = jQuery("#fifu_video_input_image_src_" + i).val();
    selector = `#fifu-video-${i}`;
    jQuery(selector).css('background', `url("${src}") center center / cover no-repeat`);
    jQuery(selector).css('opacity', '1');
}

var maxVideo = 0;

// run once
jQuery(document).ready(function () {
    fifu_video_box_init();
});

function fifu_video_box_init() {
    const MIN = 11;

    // quick edit
    if (currentLightbox)
        fifu_video_gallery_info(currentLightbox);

    numberUrls = fifuVideoVars.videoUrls ? fifuVideoVars.videoUrls.length : 0;
    numberInputs = numberUrls <= MIN ? MIN : numberUrls;

    // add placeholders
    for (i = 0; i < numberInputs; i++) {
        jQuery('#gridDemoVideo').append(`<div id="fifu-video-${i}" class="grid-square video"></div>`);
        maxVideo = i;
    }

    // add plus button
    jQuery('#gridDemoVideo').append(`<div id="fifu-add-video" class="grid-square image-add"></div>`);

    // add images
    for (i = 0; i < numberInputs; i++) {
        videoURL = fifuVideoVars.videoUrls[i];
        imageURL = fifuVideoVars.imageUrls[i];

        videoURL = videoURL !== undefined ? videoURL : "";
        imageURL = imageURL !== undefined ? imageURL : "";

        // add input hiddens
        jQuery('#inputHiddenVideos').append(`
            <input type="hidden" id="fifu_video_input_width_${i}" name="fifu_video_input_width_${i}" value="" >
            <input type="hidden" id="fifu_video_input_height_${i}" name="fifu_video_input_height_${i}" value="" >
            <input type="hidden" id="fifu_video_input_url_${i}" name="fifu_video_input_url_${i}" value="${videoURL}">
            <input type="hidden" id="fifu_video_input_image_src_${i}" name="fifu_video_input_image_src_${i}" value="">
        `);

        // get sizes
        if (imageURL)
            fifu_woo_video_get_sizes(i);
    }

    // start lists
    updateVideoList();

    /////////////////////////////////////////////////

    // init sortable
    if (jQuery('#gridDemoVideo').length) {
        new Sortable(jQuery('#gridDemoVideo')[0], {
            animation: 150,
            ghostClass: 'blue-background-class'
        });
    }

    // prepare fancy boxes 
    addFancyBoxVideo();

    // add new image: onclick event
    jQuery('#fifu-add-video').on('click', function (evt) {
        evt.stopImmediatePropagation();
        maxVideo++;
        jQuery('#gridDemoVideo').append(`<div id="fifu-video-${maxVideo}" class="grid-square video"></div>`);

        jQuery('#inputHiddenVideos').append(`
            <input type="hidden" id="fifu_video_input_width_${maxVideo}" name="fifu_video_input_width_${maxVideo}" value="" >
            <input type="hidden" id="fifu_video_input_height_${maxVideo}" name="fifu_video_input_height_${maxVideo}" value="" >
            <input type="hidden" id="fifu_video_input_url_${maxVideo}" name="fifu_video_input_url_${maxVideo}" value="">
            <input type="hidden" id="fifu_video_input_alt_${maxVideo}" name="fifu_video_input_alt_${maxVideo}" value="">
        `);
        addFancyBoxVideo();
    });

    jQuery('div.grid-square').on('mouseout', function (evt) {
        evt.stopImmediatePropagation();
        updateVideoList();
    });
}

// prepare fancy boxes
function addFancyBoxVideo() {
    jQuery('div[id^="fifu-video-"]').on('click', function (evt) {
        evt.stopImmediatePropagation();
        divId = jQuery(this).attr('id');
        index = divId.split('-')[2];

        url = jQuery(`#fifu_video_input_url_${index}`).val();
        url = url ? url : "";

        iframeTag = url ? `<iframe id="iframe-fifu-video" width="100%" src="${srcVideo(url)}" allowfullscreen frameborder="0" style="width:275px;margin-top:5px;margin-left:1px"></iframe><br>` : '<br>';

        jQuery.fancybox.open(`
            <input id="input-${divId}" placeholder="${fifuVideoVars.text_url}" value="${url}" style="width:275px;padding:5px;height:36px"><br>
            <span id="span-iframe-fifu-video">
            ${iframeTag}
            </span>
            <button id="button-fifu-video" type="button" style="width:275px;padding:5px;height:36px">${fifuVideoVars.text_ok}</button>
        `);
        jQuery(`#input-${divId}`).focus();
        jQuery(`#input-${divId}`).select();
    });
}

// change URL
jQuery(document).on('keyup', 'input[id^="input-fifu-video"]', function (evt) {
    evt.stopImmediatePropagation();

    inputId = jQuery(this).attr('id');
    divId = inputId.replace('input-', '');
    index = divId.split('-')[2];

    url = jQuery(`#${inputId}`).val();
    url = !url.startsWith('http') ? '' : fifu_convert_video(url);

    jQuery(`#fifu_video_input_url_${index}`).val(url);

    jQuery(`#span-iframe-fifu-video`).empty();

    if (!url)
        jQuery(`#${divId}`).attr('style', '');
    else {
        jQuery(`#span-iframe-fifu-video`).append(`<iframe id="iframe-fifu-video" width="100%" src="${srcVideo(url)}" allowfullscreen frameborder="0" style="width:275px;margin-top:5px;margin-left:1px"></iframe><br>`);
        fifu_woo_video_get_sizes(index);
    }

    updateVideoList();

    if (evt.which === 13 || evt.which === 27)
        jQuery.fancybox.close();
});

// OK button
jQuery(document).on('click', '#button-fifu-video', function (evt) {
    evt.stopImmediatePropagation();
    updateVideoList();
    jQuery.fancybox.close();
});

// // update the list of urls
function updateVideoList() {
    var videoListIds = "";
    i = 0;
    jQuery('div[id^="fifu-video"]').each(function (index) {
        divId = jQuery(this).attr('id');
        index = divId.split('-')[2];
        url = jQuery(`#fifu_video_input_url_${index}`).val();
        if (url && url.startsWith('http')) {
            videoListIds += (i == 0) ? '' : '|';
            videoListIds += index;
            i++;
        }
    });
    jQuery('#inputHiddenVideoListIds').val(videoListIds);
    jQuery('#inputHiddenVideoLength').val(jQuery('div[id^="fifu-video"]').length);
}

// quick edit

function fifu_video_gallery_info(post_id) {
    fifuVideoVars.videoUrls = fifuQuickEditVars.posts[post_id]['fifu_video_urls'];
    fifuVideoVars.imageUrls = fifuQuickEditVars.posts[post_id]['fifu_thumb_urls'];
}
