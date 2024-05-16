jQuery(document).ready(function () {
    fifu_open_quick_lightbox();
    fifu_register_help_quick_edit();
});

var currentLightbox = null;
var fifuPreviousInputs = [];

function fifu_open_quick_lightbox() {
    jQuery("div.fifu-quick").on('click', function (evt) {
        evt.stopImmediatePropagation();
        post_id = jQuery(this).attr('post-id');
        video_url = jQuery(this).attr('video-url');
        image_url = jQuery(this).attr('image-url');
        video_src = jQuery(this).attr('video-src');
        is_ctgr = jQuery(this).attr('is-ctgr');

        currentLightbox = post_id;

        // display
        DISPLAY_NONE = 'display:none';
        EMPTY = '';
        showVideo = (fifuColumnVars.isVideoEnabled || video_url) ? EMPTY : DISPLAY_NONE;
        showImageGallery = fifuColumnVars.onProductsPage ? EMPTY : DISPLAY_NONE;
        showSlider = fifuColumnVars.isSliderEnabled && !fifuColumnVars.onCategoriesPage ? EMPTY : DISPLAY_NONE;
        showVideoGallery = fifuColumnVars.isVideoEnabled && fifuColumnVars.onProductsPage ? EMPTY : DISPLAY_NONE;
        showUploadButton = fifuColumnVars.isUploadEnabled ? EMPTY : DISPLAY_NONE;

        url = image_url;
        url = (url == 'about:invalid' ? '' : url);
        if (video_url) {
            media = `<iframe id="fifu-quick-preview" src="" post-id="${post_id}" style="min-height:200px; max-height:600px; width:100%;" allowfullscreen frameborder="0"></iframe>`;
            url = '';
        } else
            media = `<img id="fifu-quick-preview" src="" post-id="${post_id}" style="max-height:600px; width:100%;">`;
        box = `
            <table>
                <tr>
                    <td id="fifu-left-column" style="background-color:#f6f7f7">${media}</td>
                    <td style="vertical-align:top; padding: 10px; background-color:#f6f7f7; width:250px">
                        <div>
                            <div style="padding-bottom:5px">
                                <span class="dashicons dashicons-camera" style="font-size:20px;cursor:auto;" title="${fifuColumnVars.tipImage}"></span>
                                <b>${fifuColumnVars.labelImage}</b>
                            </div>
                            <input id="fifu-quick-input-url" type="text" placeholder="${fifuColumnVars.urlImage}" value="" style="width:98%"/>
                            <br><br>

                            <div style="${showImageGallery}">
                                <div style="padding-bottom:5px">
                                    <span class="dashicons dashicons-format-gallery" style="font-size:20px;cursor:auto;"></span>
                                    <b>${fifuColumnVars.labelImageGallery}</b>
                                </div>
                                <div id="gridDemoImage"></div>
                                <div id="inputHiddenImages"></div>
                                <input type="hidden" id="inputHiddenImageListIds" name="inputHiddenImageListIds" val=""/>
                                <input type="hidden" id="inputHiddenImageLength" name="inputHiddenImageLength" val=""/>
                                <br>
                            </div>

                            <div style="${showVideo}">
                                <div style="padding-bottom:5px">
                                    <span class="dashicons dashicons-video-alt3" style="font-size:20px;cursor:auto;" title="${fifuColumnVars.tipVideo}"></span>
                                    <b>${fifuColumnVars.labelVideo}</b>
                                </div>
                                <input id="fifu-quick-video-input-url" type="text" placeholder="${fifuColumnVars.urlVideo}" value="" style="width:98%"/>
                                <br><br>
                            </div>

                            <div style="${showVideoGallery}">
                                <div style="padding-bottom:5px">
                                    <span class="dashicons dashicons-format-video" style="font-size:20px;cursor:auto;"></span>
                                    <b>${fifuColumnVars.labelVideoGallery}</b>
                                </div>
                                <div id="gridDemoVideo"></div>
                                <div id="inputHiddenVideos"></div>
                                <input type="hidden" id="inputHiddenVideoListIds" name="inputHiddenVideoListIds" val=""/>
                                <input type="hidden" id="inputHiddenVideoLength" name="inputHiddenVideoLength" val=""/>
                                <br>
                            </div>

                            <div style="${showSlider}">
                                <div style="padding-bottom:5px">
                                    <span class="dashicons dashicons-images-alt2" style="font-size:20px;cursor:auto;"></span>
                                    <b>${fifuColumnVars.labelSlider}</b>
                                </div>
                                <div id="gridDemoSlider"></div>
                                <div id="inputHiddenSliders"></div>
                                <input type="hidden" id="inputHiddenSliderListIds" name="inputHiddenSliderListIds" val=""/>
                                <input type="hidden" id="inputHiddenSliderLength" name="inputHiddenSliderLength" val=""/>
                                <br>
                            </div>

                            <div style="padding-bottom:5px">
                                <span class="dashicons dashicons-search" style="font-size:20px;cursor:auto" title="${fifuColumnVars.tipSearch}"></span>
                                <b>${fifuColumnVars.labelSearch}</b>
                                <span id="fifu_help_quick_edit" 
                                    class="dashicons dashicons-editor-help" 
                                    style="font-size:20px;cursor:pointer;">
                                </span>
                            </div>
                            <div>
                                <input id="fifu-quick-search-input-keywords" type="text" placeholder="${fifuColumnVars.keywords}" value="" style="width:75%"/>
                                <button id="fifu-search-button" class="fifu-quick-button" type="button" style="width:50px;border-radius:5px;height:30px;position:absolute;background-color:#3c434a"><span class="dashicons dashicons-search" style="font-size:16px"></span></button>
                            </div>
                            <br><br>
                        </div>
                        <div style="width:100%">
                            <button id="fifu-clean-button" class="fifu-quick-button" type="button" style="background-color: #e7e7e7; color: black;">${fifuColumnVars.buttonClean}</button>
                            <button id="fifu-save-button" post-id="${post_id}" is-ctgr="${is_ctgr}" class="fifu-quick-button" type="button">${fifuColumnVars.buttonSave}</button>
                            <br>
                            <div style="${showUploadButton}">
                                <button id="fifu-upload-button" post-id="${post_id}" is-ctgr="${is_ctgr}" onclick="fifu_upload_images_quick_api()" class="fifu-quick-button" style="background-color: #3c434a; width:97.5%; position:relative; top:2px" type="button">${fifuColumnVars.buttonUpload}</button>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>                           
        `;
        fifu_include_input_hidden(post_id);
        jQuery.fancybox.open(box, {
            touch: false,
            afterShow: function () {
                if (currentLightbox) {
                    fifu_get_image_info(currentLightbox);
                    fifu_get_video_info(currentLightbox);
                }

                if (!fifuColumnVars.onCategoriesPage) {
                    if (fifuColumnVars.onProductsPage) {
                        fifu_box_init();
                        if (fifuColumnVars.isVideoEnabled)
                            fifu_video_box_init();
                    }
                    if (fifuColumnVars.isSliderEnabled)
                        fifu_slider_box_init();
                }
            },
            afterClose: function () {
                jQuery('input[id^=fifu-quick-video-input]').remove();
            },
        }
        );
        jQuery('#fifu-left-column').css('display', url || video_url ? 'table-cell' : 'none');
        if (video_url)
            jQuery('#fifu-quick-video-input-url').select();
        else
            jQuery('#fifu-quick-input-url').select();
        fifu_change_image_event();
        fifu_save_event();
        fifu_keypress_event();
        fifu_search_event();
    });
}

function fifu_change_image_event() {
    // image
    jQuery('#fifu-quick-input-url').on('input', function () {
        url = jQuery('#fifu-quick-input-url').val();
        post_id = jQuery('#fifu-save-button').attr('post-id');
        jQuery('#fifu-left-column').css('display', url ? 'table-cell' : 'none');
        jQuery('#fifu-quick-preview').remove();
        jQuery('#fifu-quick-video-input-url').val('');

        jQuery('#fifu-left-column').append(`<img id="fifu-quick-preview" src="${url}" post-id="${post_id}" style="max-height:600px; width:100%;">`);
    });
    // video
    jQuery('#fifu-quick-video-input-url').on('input', function () {
        url = jQuery('#fifu-quick-video-input-url').val();
        post_id = jQuery('#fifu-save-button').attr('post-id');
        jQuery('#fifu-left-column').css('display', url ? 'table-cell' : 'none');
        jQuery('#fifu-quick-preview').remove();
        jQuery('#fifu-quick-input-url').val('');

        src = srcVideo(url);
        imgColumn = jQuery('.fifu-quick[post-id="' + post_id + '"]');
        imgColumn.attr('video-url', url);
        imgColumn.attr('video-src', src);
        src = src ? src : '#';

        if (url) {
            // ignore thumbnail function when it's just a parameter change
            if (fifu_format_previous_input(url) != fifuPreviousInputs['fifu-quick-video-input-url']) {
                video_thumb_url = fifu_video_image_thumbnail(url, fifuColumnVars);
                fifu_quick_video_get_image(video_thumb_url);
                fifuPreviousInputs['fifu-quick-video-input-url'] = fifu_format_previous_input(url);
            }
        }

        if (fifu_is_video(url))
            jQuery('#fifu-left-column').append(`<iframe id="fifu-quick-preview" src="${src}" post-id="${post_id}" style="min-height:200px; max-height:600px; width:100%;" allowfullscreen frameborder="0"></iframe>`);
    });
    // clean
    jQuery('#fifu-clean-button').on('click', function () {
        jQuery('#fifu-left-column').css('display', 'none');
        jQuery('#fifu-quick-preview').remove();
        jQuery('#fifu-quick-input-url').val('');
        jQuery('#fifu-quick-video-input-url').val('');

        // galleries and slider
        jQuery('[id^=fifu_input_], [id^=fifu_video_input_], [id^=fifu_slider_input_]').each(function () {
            jQuery(this).val('');
        });
        jQuery('[id^=fifu-image-], [id^=fifu-video-], [id^=fifu-slider-]').each(function () {
            jQuery(this).css('background', '');
            jQuery(this).css('opacity', '');
        });
    });
}

function fifu_save_event() {
    jQuery('#fifu-save-button').on('click', function () {
        post_id = jQuery(this).attr('post-id');
        is_ctgr = jQuery(this).attr('is-ctgr');

        image_url = jQuery("#fifu-quick-input-url")[0].value;
        video_url = jQuery("#fifu-quick-video-input-url")[0].value;
        video_src = jQuery("iframe#fifu-quick-preview").attr('src');

        img = jQuery("img[post-id=" + post_id + "]")[0];
        iframe = jQuery("iframe[post-id=" + post_id + "]")[0];

        width = height = video_thumb_url = null;

        // product gallery
        galleryLength = 0;
        galleryUrls = [];
        galleryAlts = [];
        if (jQuery('#gridDemoImage').length) {
            galleryLength = parseInt(jQuery('#inputHiddenImageLength').val());
            galleryIds = jQuery('#inputHiddenImageListIds').val();
            for (const index of galleryIds.split('|')) {
                galleryUrls.push(jQuery(`#fifu_input_url_${index}`).val());
                galleryAlts.push(jQuery(`#fifu_input_alt_${index}`).val());
            }
        }

        // product video gallery
        galleryVideoLength = 0;
        galleryVideoUrls = [];
        galleryThumbUrls = [];
        if (jQuery('#gridDemoVideo').length) {
            galleryVideoLength = parseInt(jQuery('#inputHiddenVideoLength').val());
            galleryVideoIds = jQuery('#inputHiddenVideoListIds').val();
            for (const index of galleryVideoIds.split('|')) {
                galleryVideoUrls.push(jQuery(`#fifu_video_input_url_${index}`).val());
                galleryThumbUrls.push(jQuery(`#fifu_video_input_image_src_${index}`).val());
            }
        }

        // featured slider
        sliderLength = 0;
        sliderUrls = [];
        sliderAlts = [];
        if (jQuery('#gridDemoSlider').length) {
            sliderLength = parseInt(jQuery('#inputHiddenSliderLength').val());
            sliderIds = jQuery('#inputHiddenSliderListIds').val();
            for (const index of sliderIds.split('|')) {
                sliderUrls.push(jQuery(`#fifu_slider_input_url_${index}`).val());
                sliderAlts.push(jQuery(`#fifu_slider_input_alt_${index}`).val());
            }
        }

        if (image_url) {
            width = img.naturalWidth;
            height = img.naturalHeight;
        } else if (video_url) {
            width = jQuery("#fifu-quick-video-input-image-width")[0].value;
            height = jQuery("#fifu-quick-video-input-image-height")[0].value;
            video_thumb_url = jQuery("#fifu-quick-video-input-image-src")[0].value;
        }

        jQuery.ajax({
            method: "POST",
            url: fifuColumnVars.restUrl + 'fifu-premium/v2/quick_edit_save_api/',
            data: {
                "post_id": post_id,
                "is_ctgr": is_ctgr,
                "width": width,
                "height": height,
                "image_url": image_url,
                "video_url": video_url,
                "video_thumb_url": video_thumb_url,
                "gallery_length": galleryLength,
                "gallery_urls": galleryUrls,
                "gallery_alts": galleryAlts,
                "gallery_video_length": galleryVideoLength,
                "gallery_video_urls": galleryVideoUrls,
                "slider_length": sliderLength,
                "slider_urls": sliderUrls,
                "slider_alts": sliderAlts,
            },
            async: true,
            beforeSend: function (xhr) {
                xhr.setRequestHeader("X-WP-Nonce", fifuColumnVars.nonce);
            },
            success: function (data) {
                // featured image
                if (fifuColumnVars.onCategoriesPage) {
                    fifuQuickEditCtgrVars.terms[post_id]['fifu_image_url'] = image_url;
                    fifuQuickEditCtgrVars.terms[post_id]['fifu_image_alt'] = image_alt;
                } else {
                    fifuQuickEditVars.posts[post_id]['fifu_image_url'] = image_url;
                }

                // featured video
                if (fifuColumnVars.onCategoriesPage) {
                    fifuQuickEditCtgrVars.terms[post_id]['fifu_video_url'] = video_url;
                    fifuQuickEditCtgrVars.terms[post_id]['fifu_video_src'] = video_src;
                } else {
                    fifuQuickEditVars.posts[post_id]['fifu_video_url'] = video_url;
                    fifuQuickEditVars.posts[post_id]['fifu_video_src'] = video_src;
                }

                if (!fifuColumnVars.onCategoriesPage) {
                    // featured slider
                    fifuQuickEditVars.posts[post_id]['fifu_slider_image_urls'] = sliderUrls;
                    fifuQuickEditVars.posts[post_id]['fifu_slider_image_alts'] = sliderAlts;

                    // image gallery
                    fifuQuickEditVars.posts[post_id]['fifu_image_urls'] = galleryUrls;
                    fifuQuickEditVars.posts[post_id]['fifu_image_alts'] = galleryAlts;

                    // video gallery
                    fifuQuickEditVars.posts[post_id]['fifu_video_urls'] = galleryVideoUrls;
                    fifuQuickEditVars.posts[post_id]['fifu_thumb_urls'] = galleryThumbUrls;
                }

                json = JSON.parse(data);
                url = json['thumb_url'];
                url = url ? url : '';
                thumb = jQuery('div.fifu-quick[post-id=' + post_id + ']')[0];
                jQuery(thumb).attr('image-url', url);
                jQuery(thumb).css('background-image', 'url("' + url + '")');
                url ? jQuery(thumb).css('border', 'none') : jQuery(thumb).css('color', '#ca4a1f').css('border', '2px').css('border-style', 'dashed');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            },
            complete: function (data) {
                jQuery.fancybox.close();
            },
        });
    });
}

function fifu_keypress_event() {
    jQuery('div.fancybox-container.fancybox-is-open').keyup(function (e) {
        switch (e.which) {
            case 9:
                // tab (keyword)
                if (jQuery('#fifu-quick-search-input-keywords').val())
                    jQuery('#fifu-search-button').click();
                break;
            case 13:
                jQuery(this).blur();
                // enter (keyword)
                if (jQuery('#fifu-quick-search-input-keywords').val()) {
                    jQuery('#fifu-search-button').focus().click();
                    break;
                }
                // enter (save)
                jQuery('#fifu-save-button').focus().click();
                break;
            case 27:
                // esc
                jQuery.fancybox.close();
                break;
            default:
                break;
        }
    });
}

function fifu_search_event() {
    jQuery('#fifu-search-button').on('click', function () {
        keywords = jQuery('#fifu-quick-search-input-keywords').val();
        if (keywords)
            fifu_start_lightbox(keywords, true, null);
        else
            fifu_start_lightbox(keywords, false, post_id);
    });
}

function fifu_quick_video_get_image(url) {
    var image = new Image();
    jQuery(image).attr('onload', 'fifu_quick_video_store_sizes(this);');
    jQuery(image).attr('src', url);
}

function fifu_quick_video_store_sizes($) {
    jQuery("#fifu-quick-video-input-image-width").val($.naturalWidth);
    jQuery("#fifu-quick-video-input-image-height").val($.naturalHeight);
    if ($.naturalWidth == 120 && $.naturalHeight == 90)
        jQuery("#fifu-quick-video-input-image-src").val($.src.replace('maxresdefault', 'mqdefault'));
    else
        jQuery("#fifu-quick-video-input-image-src").val($.src);
}

function fifu_include_input_hidden(post_id) {
    hidden_input = `
        <input 
            post-id="${post_id}"
            type="hidden" 
            id="fifu-quick-video-input-image-width" 
            name="fifu-quick-video-input-image-width" 
            value="" >

        <input
            post-id="${post_id}"
            type="hidden" 
            id="fifu-quick-video-input-image-height" 
            name="fifu-quick-video-input-image-height" 
            value="" >

        <input 
            post-id="${post_id}"
            type="hidden" 
            id="fifu-quick-video-input-image-src" 
            name="fifu-quick-video-input-image-src" 
            value="" >
    `;
    jQuery("div.fifu-quick").after(hidden_input);
}

function fifu_get_image_info(post_id) {
    image_url = null;

    if (fifuColumnVars.onCategoriesPage) {
        image_url = fifuQuickEditCtgrVars.terms[post_id]['fifu_image_url'];
        image_alt = fifuQuickEditCtgrVars.terms[post_id]['fifu_image_alt'];
    } else {
        image_url = fifuQuickEditVars.posts[post_id]['fifu_image_url'];
    }

    if (image_url) {
        jQuery('input#fifu-quick-input-url').val(image_url);
        jQuery('#fifu-quick-input-url').select();
        jQuery('img#fifu-quick-preview').attr('src', image_url);
    }
}

function fifu_get_video_info(post_id) {
    video_url = null;
    video_src = null;

    if (fifuColumnVars.onCategoriesPage) {
        video_url = fifuQuickEditCtgrVars.terms[post_id]['fifu_video_url'];
        video_src = fifuQuickEditCtgrVars.terms[post_id]['fifu_video_src'];
    } else {
        video_url = fifuQuickEditVars.posts[post_id]['fifu_video_url'];
        video_src = fifuQuickEditVars.posts[post_id]['fifu_video_src'];
    }

    if (video_url) {
        jQuery('input#fifu-quick-video-input-url').val(video_url);
        jQuery('#fifu-quick-video-input-url').select();
        jQuery('iframe#fifu-quick-preview').attr('src', video_src);
        fifuPreviousInputs['fifu-quick-video-input-url'] = fifu_format_previous_input(video_url);
    }
}

function fifu_upload_images_quick_api() {
    jQuery('div.fancybox-content').block({message: '', css: {backgroundColor: 'none', border: 'none', color: 'white'}});

    setTimeout(function () {
        url = jQuery("#fifu-quick-input-url").val();
        urls = '';
        alts = '';
        if (fifuColumnVars.onProductsPage) {
            if (jQuery('#gridDemoImage').length) {
                galleryIds = jQuery('#inputHiddenImageListIds').val();
                for (const index of galleryIds.split('|')) {
                    if (index > 0) {
                        urls += '|';
                        alts += '|';
                    }
                    urls += jQuery(`#fifu_input_url_${index}`).val();
                    alts += jQuery(`#fifu_input_alt_${index}`).val();
                }
            }
        }
        if (!url && !urls)
            return;

        jQuery.ajax({
            method: "POST",
            url: fifuColumnVars.restUrl + 'fifu-premium/v2/upload_images/',
            data: {
                "url": url,
                "urls": urls,
                "alts": alts,
                "post_id": currentLightbox,
                "meta_box": false,
                "taxonomy": fifuColumnVars.taxonomy,
            },
            async: false,
            beforeSend: function (xhr) {
                xhr.setRequestHeader("X-WP-Nonce", fifuColumnVars.nonce);
            },
            success: function (data) {
                if (data == null)
                    return;

                // clean preview
                json = JSON.parse(data);
                url = json['local_url'];
                thumb = jQuery('div.fifu-quick[post-id=' + post_id + ']')[0];
                jQuery(thumb).attr('image-url', url);
                jQuery(thumb).css('background-image', 'url("' + url + '")');
                jQuery(thumb).css('color', '#ca4a1f').css('border', '2px').css('border-style', 'dashed');

                // clean lightbox
                jQuery('#fifu-quick-input-url').val('');
                jQuery('[id^=fifu_input_]').each(function () {
                    jQuery(this).val('');
                });
                jQuery('[id^=fifu-image-]').each(function () {
                    jQuery(this).css('background', '');
                    jQuery(this).css('opacity', '');
                });

                // clean json
                if (fifuColumnVars.onCategoriesPage) {
                    fifuQuickEditCtgrVars.terms[currentLightbox]['fifu_image_url'] = '';
                } else {
                    fifuQuickEditVars.posts[currentLightbox]['fifu_image_url'] = '';
                    fifuQuickEditVars.posts[currentLightbox]['fifu_image_urls'] = [];
                    fifuQuickEditVars.posts[currentLightbox]['fifu_image_alts'] = [];
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            },
            complete: function (data) {
                jQuery('div.fancybox-content').unblock();
                jQuery.fancybox.close();
            }
        });
    }, 100);
}

function fifu_register_help_quick_edit() {
    jQuery(document).on('click', '#fifu_help_quick_edit', function () {
        jQuery.fancybox.open(`
            <div style="color:#1e1e1e;width:50%">
                <h1 style="background-color:whitesmoke;padding:20px;padding-left:0">${fifuColumnVars.txt_title_examples}</h1>                
                <h3>${fifuColumnVars.txt_title_keywords}</h3>
                <p style="background-color:#1e1e1e;color:white;padding:10px;border-radius:5px">sea,sun</p>
                <p>${fifuColumnVars.txt_desc_keywords}</p>
                <h3>${fifuColumnVars.txt_title_empty}</h3>
                <p style="background-color:#1e1e1e;color:white;padding:10px;border-radius:5px;height:40px"></p>
                <p>${fifuColumnVars.txt_desc_empty}</p>
            </div>`
                );
    });
}
