jQuery(document).ready(function ($) {
    if (!fifuCustomVideoVars.fifu_lazy)
        fifuLoadAllVideos();
});

function fifuLoadAllVideos() {
    for (const [imageUrl, videoUrl] of Object.entries(fifuCustomVideoVars.videos)) {
        selector = `img[src="${imageUrl}"]`;
        jQuery(selector).each(function (index) {
            if (jQuery(this)[0].complete) {
                // load from cache
                fifuReplaceByVideo(this, imageUrl, videoUrl);
            } else {
                // load from source
                jQuery(this).on('load', function () {
                    fifuReplaceByVideo(this, imageUrl, videoUrl);
                });
            }
        });
    }
}

function fifuLoadVideo(imageUrl) {
    videoUrl = fifuCustomVideoVars.videos[imageUrl];
    selector = `img[src="${imageUrl}"]`;

    jQuery(selector).each(function (index) {
        if (jQuery(this)[0].complete) {
            // load from cache
            fifuReplaceByVideo(this, imageUrl, videoUrl);
        } else {
            // load from source
            jQuery(this).on('load', function () {
                fifuReplaceByVideo(this, imageUrl, videoUrl);
            });
        }
    });
}

function fifuReplaceByVideo(image, imageUrl, videoUrl) {
    width = jQuery(image)[0].clientWidth;
    if (width < 150)
        return;
    height = jQuery(image)[0].clientHeight;
    jQuery(image).replaceWith(`<video src="${videoUrl}" poster="${imageUrl}" preload="none" controls="controls" width="${width}" height="${height}"></video>`);
}
