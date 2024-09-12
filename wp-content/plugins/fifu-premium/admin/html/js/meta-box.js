var restUrl = fifuScriptVars.restUrl;

function removeImage() {
    jQuery("#fifu_input_alt").hide();
    jQuery("#fifu_image").hide();
    jQuery("#fifu_upload").hide();

    jQuery("#fifu_link").hide();

    jQuery("#fifu_input_alt").val("");
    jQuery("#fifu_input_url").val("");
    jQuery("#fifu_keywords").val("");

    jQuery("#fifu_button").show();
    jQuery("#fifu_help").show();

    if (fifuMetaBoxVars.is_sirv_active)
        jQuery("#fifu_sirv_button").show();
}

function previewImage() {
    var $url = jQuery("#fifu_input_url").val();

    if (jQuery("#fifu_input_url").val() && jQuery("#fifu_keywords").val())
        $message = fifuMetaBoxVars.wait;
    else
        $message = '';

    if (!$url.startsWith("http") && !$url.startsWith("//")) {
        jQuery("#fifu_keywords").val($url);
        if (fifuMetaBoxVars.is_taxonomy)
            jQuery('#fifu_button').parent().parent().block({message: $message, css: {backgroundColor: 'none', border: 'none', color: 'white'}});
        else
            jQuery('#fifu_button').parent().parent().parent().block({message: $message, css: {backgroundColor: 'none', border: 'none', color: 'white'}});
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function (e) {
            if (xhr.status == 200 && xhr.readyState == 4) {
                if ($url != xhr.responseURL) {
                    $url = xhr.responseURL;
                    jQuery("#fifu_input_url").val($url);
                    runPreview($url);
                }
                setTimeout(function () {
                    if (fifuMetaBoxVars.is_taxonomy)
                        jQuery('#fifu_button').parent().parent().unblock();
                    else
                        jQuery('#fifu_button').parent().parent().parent().unblock();
                }, 500);
            }
        }
        if (!$url || $url == ' ') {
            xhr.open("GET", 'https://source.unsplash.com/random', true);
            if (fifu_get_post_title())
                fifu_start_lightbox($url, false, null);
        } else {
            xhr.open("GET", 'https://source.unsplash.com/featured/?' + $url, true);
            fifu_start_lightbox($url, true, null);
        }
        xhr.send();
        if (!$url)
            jQuery("#fifu_keywords").val(' ');
    } else {
        runPreview($url);
    }
}

function runPreview($url) {
    $url = fifu_convert($url);

    jQuery("#fifu_lightbox").attr('href', $url);

    if ($url) {
        if (jQuery('#fifu_screenshot').is(":checked")) {
            $url = 'https://s.wp.com/mshots/v1/' + $url;
            jQuery("#fifu_input_url").val($url)
        }

        fifu_get_sizes();

        jQuery("#fifu_button").hide();
        jQuery("#fifu_help").hide();

        jQuery("#fifu_image").css('background-image', "url('" + $url + "')");

        jQuery("#fifu_input_alt").show();
        jQuery("#fifu_image").show();
        if (fifuMetaBoxVars.enable_upload)
            jQuery("#fifu_upload").show();
        jQuery("#fifu_link").show();
        jQuery("#sliderImageUrlMetaBox").show();

        if (fifuMetaBoxVars.is_sirv_active)
            jQuery("#fifu_sirv_button").hide();

        // hide default featured image field
        fifu_hide_regular_featured_image_field();
    }
}

jQuery(document).ready(function () {
    // help
    fifu_register_help();

    // lightbox
    fifu_open_lightbox();

    // start
    fifu_get_sizes();

    // input
    fifu_type_url();

    // title
    jQuery("div#imageUrlMetaBox").find('h2').replaceWith('<h4 style="top:7px;position:relative;"><span class="dashicons dashicons-camera" style="font-size:15px"></span>' + jQuery("div#imageUrlMetaBox").find('h2').text() + '</h4>');
    jQuery("div#urlMetaBox").find('h2').replaceWith('<h4 style="top:5px;position:relative;"><span class="dashicons dashicons-camera" style="font-size:15px"></span>' + jQuery("div#urlMetaBox").find('h2').text() + '</h4>');
});

function fifu_get_sizes() {
    image_url = jQuery("#fifu_input_url").val();
    if (image_url && !image_url.startsWith("http") && !image_url.startsWith("//"))
        return;
    fifu_get_image(image_url);
}

function fifu_get_image(url) {
    var image = new Image();
    jQuery(image).attr('onload', 'fifu_store_sizes(this);');
    jQuery(image).attr('src', url);
}

function fifu_store_sizes($) {
    jQuery("#fifu_input_image_width").val($.naturalWidth);
    jQuery("#fifu_input_image_height").val($.naturalHeight);
}

function fifu_open_lightbox() {
    jQuery("#fifu_image").on('click', function (evt) {
        evt.stopImmediatePropagation();
        jQuery.fancybox.open('<img src="' + fifu_convert(jQuery("#fifu_input_url").val()) + '" style="max-height:600px">');
    });
}

function fifu_type_url() {
    jQuery("#fifu_input_url").on('input', function (evt) {
        evt.stopImmediatePropagation();
        fifu_get_sizes();
    });
}

function fifu_upload_images_api() {
    if (fifuMetaBoxVars.is_taxonomy)
        jQuery('#fifu_upload').parent().parent().block({message: '', css: {backgroundColor: 'none', border: 'none', color: 'white'}});
    else {
        jQuery('#fifu_upload').parent().parent().parent().block({message: '', css: {backgroundColor: 'none', border: 'none', color: 'white'}});
        jQuery('div#wooGalleryMetaBox').block({message: '', css: {backgroundColor: 'none', border: 'none', color: 'white'}});
    }

    setTimeout(function () {
        url = jQuery("#fifu_input_url").val();
        alt = jQuery("#fifu_input_alt").val();
        urls = '';
        alts = '';
        if (fifuMetaBoxVars.is_product) {
            var count = 0;
            jQuery('input[id^="fifu_input_url_"]').each(function (index) {
                url_aux = jQuery(this).attr('value');
                if (url_aux) {
                    urls += count == 0 ? url_aux : '|' + url_aux;
                    alt_aux = jQuery('#fifu_input_alt_' + index).val();
                    alts += count == 0 ? alt_aux : '|' + alt_aux;
                    count++;
                }
            });
        }
        if (!url && !urls)
            return;

        jQuery.ajax({
            method: "POST",
            url: restUrl + 'fifu-premium/v2/upload_images/',
            data: {
                "url": url,
                "alt": alt,
                "urls": urls,
                "alts": alts,
                "post_id": fifuMetaBoxVars.get_the_ID,
                "meta_box": true,
                "taxonomy": fifuMetaBoxVars.is_taxonomy,
            },
            async: false,
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', fifuScriptVars.nonce);
            },
            success: function (data) {
                if (data == null)
                    return;

                obj = JSON.parse(data);

                // remove                
                setTimeout(function () {
                    removeImage();
                    if (fifuMetaBoxVars.is_product) {
                        var count = 0;
                        jQuery('div[id^="fifu_link_"]').each(function (index) {
                            jQuery(this).click();
                        });
                        jQuery('input[id^="fifu_input_url_"]').each(function (index) {
                            jQuery(this).val('');
                        });
                    }
                }, 500);

                // refresh
                if (fifuMetaBoxVars.is_taxonomy) {
                    jQuery('#product_cat_thumbnail_id').val(obj.att_ids[0]);
                    jQuery('#product_cat_thumbnail > img').attr('src', url);
                    jQuery('#product_cat_thumbnail > img').removeAttr('height');
                } else {
                    if (!fifuMetaBoxVars.is_product && !fifuMetaBoxVars.is_classic_editor) {
                        // post image
                        wp.data.select('core/editor').getEditedPostAttribute('featured_media');
                        wp.data.dispatch('core/editor').editPost({featured_media: obj.att_ids[0]});
                    } else {
                        setTimeout(function () {
                            // product image
                            jQuery('#_thumbnail_id').val(obj.att_ids[0]);

                            // product gallery
                            if (obj.att_ids.length > 1) {
                                ids_str = '';
                                for (i = 1; i < obj.att_ids.length; i++)
                                    ids_str += (i == 1) ? obj.att_ids[i] : ',' + obj.att_ids[i];
                                jQuery('#product_image_gallery').val(ids_str);
                            }

                            if (jQuery('input#save-post')[0]) {
                                jQuery('input#save-post').click();
                            } else {
                                jQuery('input#publish').click();
                            }
                        }, 500);
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            },
            complete: function (data) {
                if (!fifuMetaBoxVars.is_classic_editor) {
                    if (fifuMetaBoxVars.is_taxonomy)
                        jQuery('#fifu_upload').parent().parent().unblock();
                    else {
                        if (!fifuMetaBoxVars.is_product)
                            jQuery('#fifu_upload').parent().parent().parent().unblock();
                    }
                }
            }
        });
    }, 100);
}

function fifu_register_help() {
    jQuery('#fifu_help').on('click', function () {
        jQuery.fancybox.open(`
            <div style="color:#1e1e1e;width:50%">
                <h1 style="background-color:whitesmoke;padding:20px;padding-left:0">${fifuMetaBoxVars.txt_title_examples}</h1>
                <h3>${fifuMetaBoxVars.txt_title_url}</h3>
                <p style="background-color:#1e1e1e;color:white;padding:10px;border-radius:5px">https://ps.w.org/featured-image-from-url/assets/banner-1544x500.png</p>
                <p>${fifuMetaBoxVars.txt_desc_url}</p>
                <h3>${fifuMetaBoxVars.txt_title_keywords}</h3>
                <p style="background-color:#1e1e1e;color:white;padding:10px;border-radius:5px">sea,sun</p>
                <p>${fifuMetaBoxVars.txt_desc_keywords}</p>
                <h3>${fifuMetaBoxVars.txt_title_empty}</h3>
                <p style="background-color:#1e1e1e;color:white;padding:10px;border-radius:5px;height:40px"></p>
                <p>${fifuMetaBoxVars.txt_desc_empty}</p>
                <h1 style="background-color:whitesmoke;padding:20px;padding-left:0">${fifuMetaBoxVars.txt_title_more}</h1>
                <p>${fifuMetaBoxVars.txt_desc_more}</p>
            </div>`
                );
    });
}

function fifu_hide_regular_featured_image_field() {
    if (fifuMetaBoxVars.is_product)
        return;

    if (wp.data && wp.data.dispatch('core/edit-post') && wp.data.select('core/edit-post').isEditorPanelOpened('featured-image')) {
        wp.data.dispatch('core/edit-post').toggleEditorPanelOpened('featured-image');
    }
}

jQuery(document).ready(function () {
    setTimeout(function () {
        if (jQuery("#fifu_input_url").val() || jQuery("#fifu_image").css('background-image').includes('http')) {
            fifu_hide_regular_featured_image_field();
        }
    }, 100);
});
