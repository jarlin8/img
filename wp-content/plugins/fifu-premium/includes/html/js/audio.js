jQuery(document).ready(function ($) {
    if (!fifuAudioVars.fifu_lazy)
        fifuLoadAllAudios();
});

function fifuLoadAllAudios() {
    for (const [imageUrl, audioUrl] of Object.entries(fifuAudioVars.audios)) {
        selector = `img[src="${imageUrl}"]`;
        if (jQuery(selector)[0].complete) {
            // load from cache
            fifuReplaceByAudio(selector, imageUrl, audioUrl);
        } else {
            // load from source
            jQuery(selector).on('load', function () {
                fifuReplaceByAudio(this, imageUrl, audioUrl);
            });
        }
    }
}

function fifuLoadAudio(imageUrl) {
    audioUrl = fifuAudioVars.audios[imageUrl];
    selector = `img[src="${imageUrl}"]`;

    if (jQuery(selector)[0].complete) {
        // load from cache
        fifuReplaceByAudio(selector, imageUrl, audioUrl);
    } else {
        // load from source
        jQuery(selector).on('load', function () {
            fifuReplaceByAudio(this, imageUrl, audioUrl);
        });
    }
}

function fifuReplaceByAudio(image, imageUrl, audioUrl) {
    width = jQuery(image)[0].clientWidth;
    if (width < 150)
        return;
    height = jQuery(image)[0].clientHeight;
    jQuery(image).replaceWith(`<video src="${audioUrl}" poster="${imageUrl}" preload="none" controls="controls" width="${width}" height="${height}"></video>`);
}
