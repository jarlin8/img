// add meta box icon
jQuery(document).ready(function () {
    jQuery("div#wooSliderImageUrlMetaBox").find('h2').replaceWith('<h4 style="top:5px;position:relative;"><span class="dashicons dashicons-images-alt2" style="font-size:15px"></span>' + jQuery("div#wooSliderImageUrlMetaBox").find('h2').text() + '</h4>');
    jQuery("div#sliderImageUrlMetaBox").find('h2').replaceWith('<h4 style="top:7px;position:relative;"><span class="dashicons dashicons-images-alt2" style="font-size:15px"></span>' + jQuery("div#sliderImageUrlMetaBox").find('h2').text() + '</h4>');

    if (fifuSliderVars.is_product)
        jQuery('#gridDemoSlider').attr('style', 'position:relative;left:13px');
});

// sizes
function fifu_slider_get_sizes(i) {
    slider_url = jQuery('input[id^=fifu_slider_input_url_' + i + ']').val();
    fifu_slider_get_image(slider_url, i);
}

function fifu_slider_get_image(url, i) {
    var image = new Image();
    jQuery(image).attr('onload', 'fifu_slider_store_sizes(this,' + i + ');');
    jQuery(image).attr('src', url);
}

function fifu_slider_store_sizes($, i) {
    jQuery("#fifu_slider_input_width_" + i).val($.naturalWidth);
    jQuery("#fifu_slider_input_height_" + i).val($.naturalHeight);
}

var maxSlider = 0;

// run once
jQuery(document).ready(function () {
    fifu_slider_box_init();
});

function fifu_slider_box_init() {
    const MIN = 11;

    // quick edit
    if (currentLightbox)
        fifu_slider_info(currentLightbox);

    numberUrls = fifuSliderVars.urls ? fifuSliderVars.urls.length : 0;
    numberInputs = numberUrls <= MIN ? MIN : numberUrls;

    // add placeholders
    for (i = 0; i < numberInputs; i++) {
        jQuery('#gridDemoSlider').append(`<div id="fifu-slider-${i}" class="grid-square image"></div>`);
        maxSlider = i;
    }

    // add plus button
    jQuery('#gridDemoSlider').append(`<div id="fifu-add-slider" class="grid-square image-add"></div>`);

    // add images
    for (i = 0; i < numberInputs; i++) {
        url = fifuSliderVars.urls[i];
        alt = fifuSliderVars.alts[i];

        url = url !== undefined ? url : "";
        alt = alt !== undefined ? alt : "";

        if (url) {
            selector = `#fifu-slider-${i}`;
            jQuery(selector).css('background', `url("${url}") center center / cover no-repeat`);
            jQuery(selector).css('opacity', '1');
        }

        // add input hiddens
        jQuery('#inputHiddenSliders').append(`
            <input type="hidden" id="fifu_slider_input_width_${i}" name="fifu_slider_input_width_${i}" value="" >
            <input type="hidden" id="fifu_slider_input_height_${i}" name="fifu_slider_input_height_${i}" value="" >
            <input type="hidden" id="fifu_slider_input_url_${i}" name="fifu_slider_input_url_${i}" value="${url}">
            <input type="hidden" id="fifu_slider_input_alt_${i}" name="fifu_slider_input_alt_${i}" value="${alt}">
        `);

        // get sizes
        if (url) {
            fifu_slider_get_sizes(i);
        }
    }

    // start lists
    updateSliderList();

    /////////////////////////////////////////////////

    // init sortable
    if (jQuery('#gridDemoSlider').length) {
        new Sortable(jQuery('#gridDemoSlider')[0], {
            animation: 150,
            ghostClass: 'blue-background-class'
        });
    }

    // prepare fancy boxes 
    addFancyBoxSlider();

    // add new image: onclick event
    jQuery('#fifu-add-slider').on('click', function (evt) {
        evt.stopImmediatePropagation();
        maxSlider++;
        jQuery('#gridDemoSlider').append(`<div id="fifu-slider-${maxSlider}" class="grid-square image"></div>`);

        jQuery('#inputHiddenSliders').append(`
            <input type="hidden" id="fifu_slider_input_width_${maxSlider}" name="fifu_slider_input_width_${maxSlider}" value="" >
            <input type="hidden" id="fifu_slider_input_height_${maxSlider}" name="fifu_slider_input_height_${maxSlider}" value="" >
            <input type="hidden" id="fifu_slider_input_url_${maxSlider}" name="fifu_slider_input_url_${maxSlider}" value="">
            <input type="hidden" id="fifu_slider_input_alt_${maxSlider}" name="fifu_slider_input_alt_${maxSlider}" value="">
        `);
        addFancyBoxSlider();
    });

    jQuery('div.grid-square').on('mouseout', function (evt) {
        evt.stopImmediatePropagation();
        updateSliderList();
    });
}

// prepare fancy boxes
function addFancyBoxSlider() {
    jQuery('div[id^="fifu-slider-"]').on('click', function (evt) {
        evt.stopImmediatePropagation();
        divId = jQuery(this).attr('id');
        index = divId.split('-')[2];

        url = jQuery(`#fifu_slider_input_url_${index}`).val();
        alt = jQuery(`#fifu_slider_input_alt_${index}`).val();
        url = url ? url : "";
        alt = alt ? alt : "";

        imgTag = url ? `<img id="img-fifu-slider" src="${url}" style="width:275px;margin-top:5px;margin-left:1px"><br>` : '<br>';

        jQuery.fancybox.open(`
            <input id="input-${divId}" placeholder="${fifuSliderVars.text_url}" value="${url}" style="width:275px;padding:5px;height:36px"><br>
            <span id="span-img-fifu-slider">
            ${imgTag}
            </span>
            <input id="alt-input-slider-${divId}" placeholder="${fifuSliderVars.text_alt}" value="${alt}" style="width:275px;padding:5px;height:36px"><br>
            <button id="button-fifu-slider" type="button" style="width:275px;padding:5px;height:36px">${fifuSliderVars.text_ok}</button>
        `);
        jQuery(`#input-${divId}`).focus();
        jQuery(`#input-${divId}`).select();
    });
}

// change URL
jQuery(document).on('keyup', 'input[id^="input-fifu-slider"]', function (evt) {
    evt.stopImmediatePropagation();

    inputId = jQuery(this).attr('id');
    divId = inputId.replace('input-', '');
    index = divId.split('-')[2];

    url = jQuery(`#${inputId}`).val();
    url = !url.startsWith('http') ? '' : fifu_convert(url);

    jQuery(`#fifu_slider_input_url_${index}`).val(url);

    jQuery(`#span-img-fifu-slider`).empty();
    jQuery(`#span-img-fifu-slider`).append(`<img id="img-fifu-slider" src="${url}" style="width:275px;margin-top:5px;margin-left:1px"><br>`);

    if (!url)
        jQuery(`#${divId}`).attr('style', '');
    else {
        jQuery(`#${divId}`).css('background', `url("${url}") center center / cover no-repeat`);
        jQuery(`#${divId}`).css('opacity', '1');
        fifu_slider_get_sizes(index);
    }

    updateSliderList();

    if (evt.which === 13 || evt.which === 27)
        jQuery.fancybox.close();
});

// change ALT
jQuery(document).on('keyup', 'input[id^="alt-input-slider-fifu"]', function (evt) {
    evt.stopImmediatePropagation();
    inputId = jQuery(this).attr('id');
    divId = inputId.replace('alt-input-slider-', '');
    index = divId.split('-')[2];

    alt = jQuery(`#${inputId}`).val();
    jQuery(`#fifu_slider_input_alt_${index}`).val(alt);

    updateSliderList();

    if (evt.which === 13 || evt.which === 27)
        jQuery.fancybox.close();
});

// OK button
jQuery(document).on('click', '#button-fifu-slider', function (evt) {
    evt.stopImmediatePropagation();
    updateSliderList();
    jQuery.fancybox.close();
});

// update the list of urls
function updateSliderList() {
    var sliderListIds = "";
    i = 0;
    jQuery('div[id^="fifu-slider"]').each(function (index) {
        divId = jQuery(this).attr('id');
        index = divId.split('-')[2];
        url = jQuery(`#fifu_slider_input_url_${index}`).val();
        if (url && url.startsWith('http')) {
            sliderListIds += (i == 0) ? '' : '|';
            sliderListIds += index;
            i++;
        }
    });
    jQuery('#inputHiddenSliderListIds').val(sliderListIds);
    jQuery('#inputHiddenSliderLength').val(jQuery('div[id^="fifu-slider"]').length);
}

// quick edit

function fifu_slider_info(post_id) {
    fifuSliderVars.urls = fifuQuickEditVars.posts[post_id]['fifu_slider_image_urls'];
    fifuSliderVars.alts = fifuQuickEditVars.posts[post_id]['fifu_slider_image_alts'];
}
