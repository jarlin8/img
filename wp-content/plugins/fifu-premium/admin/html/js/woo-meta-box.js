// sizes
function fifu_woo_get_sizes(i) {
    url = jQuery('input[id^=fifu_input_url_' + i + ']').val();
    if (!url || !url.startsWith("http"))
        return;
    fifu_woo_get_image(url, i);
}

function fifu_woo_get_image(url, i) {
    var image = new Image();
    jQuery(image).attr('onload', 'fifu_woo_store_sizes(this,' + i + ');');
    jQuery(image).attr('src', url);
}

function fifu_woo_store_sizes($, i) {
    jQuery("#fifu_input_width_" + i).val($.naturalWidth);
    jQuery("#fifu_input_height_" + i).val($.naturalHeight);
}

var maxImage = 0;

// run once
jQuery(document).ready(function () {
    fifu_box_init();
});

function fifu_box_init() {
    jQuery("div#wooGalleryMetaBox").find('h2').replaceWith('<h4 style="top:5px;position:relative;"><span class="dashicons dashicons-format-gallery" style="font-size:15px"></span>' + jQuery("div#wooGalleryMetaBox").find('h2').text() + '</h4>');

    const MIN = 11;

    // quick edit
    if (currentLightbox)
        fifu_image_gallery_info(currentLightbox);

    numberUrls = fifuBoxImageVars.urls ? fifuBoxImageVars.urls.length : 0;
    numberInputs = numberUrls <= MIN ? MIN : numberUrls;

    // add placeholders
    for (i = 0; i < numberInputs; i++) {
        jQuery('#gridDemoImage').append(`<div id="fifu-image-${i}" class="grid-square image"></div>`);
        maxImage = i;
    }

    // add plus button
    jQuery('#gridDemoImage').append(`<div id="fifu-add-image" class="grid-square image-add"></div>`);

    // add images
    for (i = 0; i < numberInputs; i++) {
        url = fifuBoxImageVars.urls[i];
        alt = fifuBoxImageVars.alts[i];

        url = url !== undefined ? url : "";
        alt = alt !== undefined ? alt : "";

        if (url) {
            selector = `#fifu-image-${i}`;
            jQuery(selector).css('background', `url("${url}") center center / cover no-repeat`);
            jQuery(selector).css('opacity', '1');
        }

        // add input hiddens
        jQuery('#inputHiddenImages').append(`
            <input type="hidden" id="fifu_input_width_${i}" name="fifu_input_width_${i}" value="" >
            <input type="hidden" id="fifu_input_height_${i}" name="fifu_input_height_${i}" value="" >
            <input type="hidden" id="fifu_input_url_${i}" name="fifu_input_url_${i}" value="${url}">
            <input type="hidden" id="fifu_input_alt_${i}" name="fifu_input_alt_${i}" value="${alt}">
        `);

        // get sizes
        if (url)
            fifu_woo_get_sizes(i);
    }

    // start lists
    updateImageList();

    /////////////////////////////////////////////////

    // init sortable
    if (jQuery('#gridDemoImage').length) {
        new Sortable(jQuery('#gridDemoImage')[0], {
            animation: 150,
            ghostClass: 'blue-background-class'
        });
    }

    // prepare fancy boxes 
    addFancyBoxImage();

    // add new image: onclick event
    jQuery('#fifu-add-image').on('click', function (evt) {
        evt.stopImmediatePropagation();
        maxImage++;
        jQuery('#gridDemoImage').append(`<div id="fifu-image-${maxImage}" class="grid-square image"></div>`);

        jQuery('#inputHiddenImages').append(`
            <input type="hidden" id="fifu_input_width_${maxImage}" name="fifu_input_width_${maxImage}" value="" >
            <input type="hidden" id="fifu_input_height_${maxImage}" name="fifu_input_height_${maxImage}" value="" >
            <input type="hidden" id="fifu_input_url_${maxImage}" name="fifu_input_url_${maxImage}" value="">
            <input type="hidden" id="fifu_input_alt_${maxImage}" name="fifu_input_alt_${maxImage}" value="">
        `);
        addFancyBoxImage();
    });

    jQuery('div.grid-square').on('mouseout', function (evt) {
        evt.stopImmediatePropagation();
        updateImageList();
    });
}

// prepare fancy boxes
function addFancyBoxImage() {
    jQuery('div[id^="fifu-image-"]').on('click', function (evt) {
        evt.stopImmediatePropagation();
        divId = jQuery(this).attr('id');
        index = divId.split('-')[2];

        url = jQuery(`#fifu_input_url_${index}`).val();
        alt = jQuery(`#fifu_input_alt_${index}`).val();
        url = url ? url : "";
        alt = alt ? alt : "";

        imgTag = url ? `<img id="img-fifu-image" src="${url}" style="width:275px;margin-top:5px;margin-left:1px"><br>` : '<br>';

        jQuery.fancybox.open(`
            <input id="input-${divId}" placeholder="${fifuBoxImageVars.text_url}" value="${url}" style="width:275px;padding:5px;height:36px"><br>
            <span id="span-img-fifu-image">
            ${imgTag}
            </span>
            <input id="alt-input-image-${divId}" placeholder="${fifuBoxImageVars.text_alt}" value="${alt}" style="width:275px;padding:5px;height:36px"><br>
            <button id="button-fifu-image" type="button" style="width:275px;padding:5px;height:36px">${fifuBoxImageVars.text_ok}</button>
        `);
        jQuery(`#input-${divId}`).focus();
        jQuery(`#input-${divId}`).select();
    });
}

// change URL
jQuery(document).on('keyup', 'input[id^="input-fifu-image"]', function (evt) {
    evt.stopImmediatePropagation();

    inputId = jQuery(this).attr('id');
    divId = inputId.replace('input-', '');
    index = divId.split('-')[2];

    url = jQuery(`#${inputId}`).val();
    url = !url.startsWith('http') ? '' : fifu_convert(url);

    jQuery(`#fifu_input_url_${index}`).val(url);

    jQuery(`#span-img-fifu-image`).empty();
    jQuery(`#span-img-fifu-image`).append(`<img id="img-fifu-image" src="${url}" style="width:275px;margin-top:5px;margin-left:1px"><br>`);

    if (!url)
        jQuery(`#${divId}`).attr('style', '');
    else {
        jQuery(`#${divId}`).css('background', `url("${url}") center center / cover no-repeat`);
        jQuery(`#${divId}`).css('opacity', '1');
        fifu_woo_get_sizes(index);
    }

    updateImageList();

    if (evt.which === 13 || evt.which === 27)
        jQuery.fancybox.close();
});

// change ALT
jQuery(document).on('keyup', 'input[id^="alt-input-image-fifu"]', function (evt) {
    evt.stopImmediatePropagation();
    inputId = jQuery(this).attr('id');
    divId = inputId.replace('alt-input-image-', '');
    index = divId.split('-')[2];

    alt = jQuery(`#${inputId}`).val();
    jQuery(`#fifu_input_alt_${index}`).val(alt);

    updateImageList();

    if (evt.which === 13 || evt.which === 27)
        jQuery.fancybox.close();
});

// OK button
jQuery(document).on('click', '#button-fifu-image', function (evt) {
    evt.stopImmediatePropagation();
    updateImageList();
    jQuery.fancybox.close();
});

// update the list of urls
function updateImageList() {
    var imageListIds = "";
    i = 0;
    jQuery('div[id^="fifu-image"]').each(function (index) {
        divId = jQuery(this).attr('id');
        index = divId.split('-')[2];
        url = jQuery(`#fifu_input_url_${index}`).val();
        if (url && url.startsWith('http')) {
            imageListIds += (i == 0) ? '' : '|';
            imageListIds += index;
            i++;
        }
    });
    jQuery('#inputHiddenImageListIds').val(imageListIds);
    jQuery('#inputHiddenImageLength').val(jQuery('div[id^="fifu-image"]').length);
}

// dokan

var fifuBoxInitialized = false;
var observer = new MutationObserver(function (mutations) {
    mutations.forEach(function (mutation) {
        hidden = jQuery(mutation.target).attr('aria-hidden') == 'true';
        if (hidden) {
            fifuBoxInitialized = false;
        } else {
            if (!fifuBoxInitialized) {
                fifu_box_init();
                fifuBoxInitialized = true;
            }
        }
    });
});

var target = document.getElementById('dokan-add-product-popup');
if (target)
    observer.observe(target, {attributes: true, attributeFilter: ['aria-hidden']});


// quick edit

function fifu_image_gallery_info(post_id) {
    fifuBoxImageVars.urls = fifuQuickEditVars.posts[post_id]['fifu_image_urls'];
    fifuBoxImageVars.alts = fifuQuickEditVars.posts[post_id]['fifu_image_alts'];
}
