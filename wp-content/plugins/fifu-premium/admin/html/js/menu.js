jQuery(document).ready(function () {
    jQuery('link[href*="jquery-ui.css"]').attr("disabled", "true");
    jQuery('div.wrap div.header-box div.notice').hide();
    jQuery('div.wrap div.header-box div#message').hide();
    jQuery('div.wrap div.header-box div.updated').remove();
    fifu_check_version();
});

var restUrl = fifu_get_rest_url();

function invert(id) {
    if (jQuery("#fifu_toggle_" + id).attr("class") == "toggleon") {
        jQuery("#fifu_toggle_" + id).attr("class", "toggleoff");
        jQuery("#fifu_input_" + id).val('off');
    } else {
        jQuery("#fifu_toggle_" + id).attr("class", "toggleon");
        jQuery("#fifu_input_" + id).val('on');
    }
}

jQuery(function () {
    var url = window.location.href;

    //forms with id started by...
    jQuery("form[id^=fifu_form]").each(function (i, el) {
        //onchange
        jQuery(this).change(function () {
            save(this);
        });
        if (isClickable(el.id)) {
            //onclick
            jQuery(this).click(function () {
                save(this);
            });
        } else {
            //onsubmit
            jQuery(this).submit(function () {
                save(this);
            });
        }
    });

    jQuery("#tabs-top").tabs();
    jQuery("#fifu_input_spinner_nth").spinner({min: 1});
    jQuery("#fifu_input_slider_speed").spinner({min: 0});
    jQuery("#fifu_input_slider_pause").spinner({min: 0});
    jQuery("#fifu_input_auto_set_width").spinner({min: 0});
    jQuery("#fifu_input_auto_set_height").spinner({min: 0});
    jQuery("#fifu_input_crop_delay").spinner({min: 0, step: 50});
    jQuery("#tabsApi").tabs();
    jQuery("#tabsCrop").tabs();
    jQuery("#tabsMedia").tabs();
    jQuery("#tabsPremium").tabs();
    jQuery("#tabsWooImport").tabs();
    jQuery("#tabsWpAllImport").tabs();
    jQuery("#tabsDefault").tabs();
    jQuery("#tabsHide").tabs();
    jQuery("#tabsShortcode").tabs();
    jQuery("#tabsFifuShortcode").tabs();
    jQuery("#tabsAutoSet").tabs();
    jQuery("#tabsIsbn").tabs();
    jQuery("#tabsFinder").tabs();
    jQuery("#tabsVideo").tabs();
    jQuery("#tabsContent").tabs();
    jQuery("#tabsContentAll").tabs();
    jQuery("#tabsCli").tabs();
    jQuery("#tabsKey").tabs();
    jQuery("#tabsRSS").tabs();
    jQuery("#tabsDimensions").tabs();

    // show settings
    window.scrollTo(0, 0);
    if (fifuScriptVars.lock) {
        jQuery('a[href*="#tabs-a"]').click();
        jQuery('a[href*="#tabs-"]').on('click', function () {
            jQuery('a[href*="#tabs-a"]').click();
        });
    }
    jQuery('.wrap').css('opacity', 1);
});

function isClickable(id) {
    return id.match("fifu_form_slider_speed") || id.match("fifu_form_slider_pause") || id.match("fifu_form_crop_delay");
}

function save(formName, url) {
    var frm = jQuery(formName);
    jQuery.ajax({
        type: frm.attr('method'),
        url: url,
        data: frm.serialize(),
        success: function (data) {
            //alert('saved');
        }
    });
}

function refresh() {
    setInterval(function () {
        jQuery('div[id^=fifu_ajax]').load('admin.php?page=fifu-premium div[id^=fifu_ajax]');
    }, 1000)
}

function fifu_default_js() {
    jQuery('#tabs-top').block({message: fifuScriptVars.wait, css: {backgroundColor: 'none', border: 'none', color: 'white'}});

    toggle = jQuery("#fifu_toggle_enable_default_url").attr('class');
    switch (toggle) {
        case "toggleoff":
            option = "disable_default_api";
            break;
        default:
            url = jQuery("#fifu_input_default_url").val();
            option = url ? "none_default_api" : "disable_default_api";
    }
    jQuery.ajax({
        method: "POST",
        url: restUrl + 'fifu-premium/v2/' + option + '/',
        async: true,
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-WP-Nonce', fifuScriptVars.nonce);
        },
        success: function (data) {
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        },
        complete: function () {
            setTimeout(function () {
                jQuery('#tabs-top').unblock();
            }, 1000);
        },
        timeout: 0
    });
}

function fifu_fake_js() {
    jQuery('#tabs-top').block({message: fifuScriptVars.wait, css: {backgroundColor: 'none', border: 'none', color: 'white'}});

    toggle = jQuery("#fifu_toggle_fake").attr('class');
    switch (toggle) {
        case "toggleon":
            option = "enable_fake_api";
            break;
        case "toggleoff":
            option = "disable_fake_api";
            break;
        default:
            return;
    }

    interval = setInterval(function () {
        jQuery("#image_metadata_counter").load(location.href + " #image_metadata_counter");
    }, 3000);

    jQuery.ajax({
        method: "POST",
        url: restUrl + 'fifu-premium/v2/' + option + '/',
        async: true,
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-WP-Nonce', fifuScriptVars.nonce);
        },
        success: function (data) {
            setTimeout(function () {
                jQuery('#tabs-top').unblock();
            }, 1000);
            jQuery("#countdown").load(location.href + " #countdown");
            jQuery("#image_metadata_counter").load(location.href + " #image_metadata_counter");
            clearInterval(interval);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        },
        complete: function () {
        },
        timeout: 0
    });
}

function fifu_clean_js() {
    if (jQuery("#fifu_toggle_data_clean").attr('class') != 'toggleon')
        return;

    fifu_run_clean_js();
}

function fifu_run_clean_js() {
    jQuery('#tabs-top').block({message: fifuScriptVars.wait, css: {backgroundColor: 'none', border: 'none', color: 'white'}});

    jQuery.ajax({
        method: "POST",
        url: restUrl + 'fifu-premium/v2/data_clean_api/',
        async: true,
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-WP-Nonce', fifuScriptVars.nonce);
        },
        success: function (data) {
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        },
        complete: function () {
            setTimeout(function () {
                jQuery("#fifu_toggle_data_clean").attr('class', 'toggleoff');
                jQuery("#fifu_toggle_fake").attr('class', 'toggleoff');
                jQuery("#image_metadata_counter").load(location.href + " #image_metadata_counter");
                jQuery("#countdown").load(location.href + " #countdown");
                jQuery('#tabs-top').unblock();
            }, 1000);
        },
        timeout: 0
    });
}

function fifu_update_all_js() {
    if (jQuery("#fifu_toggle_update_all").attr('class') != 'toggleon')
        return;

    jQuery('#tabs-top').block({message: fifuScriptVars.wait, css: {backgroundColor: 'none', border: 'none', color: 'white'}});

    jQuery.ajax({
        method: "POST",
        url: restUrl + 'fifu-premium/v2/update_all_api/',
        async: true,
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-WP-Nonce', fifuScriptVars.nonce);
        },
        success: function (data) {
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        },
        complete: function () {
            setTimeout(function () {
                jQuery("#fifu_toggle_update_all").attr('class', 'toggleoff');
                jQuery('#tabs-top').unblock();
            }, 1000);
        },
        timeout: 0
    });
}

function fifu_run_delete_all_js() {
    if (jQuery("#fifu_toggle_run_delete_all").attr('class') != 'toggleon')
        return;

    fifu_run_clean_js();

    jQuery('#tabs-top').block({message: fifuScriptVars.wait, css: {backgroundColor: 'none', border: 'none', color: 'white'}});

    jQuery.ajax({
        method: "POST",
        url: restUrl + 'fifu-premium/v2/run_delete_all_api/',
        async: true,
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-WP-Nonce', fifuScriptVars.nonce);
        },
        success: function (data) {
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        },
        complete: function () {
            setTimeout(function () {
                jQuery("#fifu_toggle_run_delete_all").attr('class', 'toggleoff');
                jQuery('#tabs-top').unblock();
            }, 3000);
        },
        timeout: 0
    });
}

function fifu_save_dimensions_all_js() {
    tooMany = '(' + fifuScriptVars.dimensionsSupport + ')';
    if (parseInt(jQuery("#countdown").text()) == -1 || jQuery("#countdown").text() == tooMany) {
        jQuery("#countdown").text(tooMany);
        invert('save_dimensions_all');
        return;
    }

    jQuery('#tabs-top').block({message: fifuScriptVars.dimensionsWait, css: {backgroundColor: 'none', border: 'none', color: 'white'}});

    interval = setInterval(function () {
        jQuery("#countdown").load(location.href + " #countdown");
    }, 3000);

    jQuery.ajax({
        method: "POST",
        url: restUrl + 'fifu-premium/v2/run_get_and_save_sizes_api/',
        async: true,
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-WP-Nonce', fifuScriptVars.nonce);
        },
        success: function (data) {
            jQuery("#countdown").load(location.href + " #countdown");
            clearInterval(interval);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        },
        complete: function () {
            setTimeout(function () {
                invert('save_dimensions_all');
                jQuery('#tabs-top').unblock();
            }, 1000);
        },
        timeout: 0
    });
}

function fifu_sizes_js() {
    jQuery('#tabs-top').block({message: fifuScriptVars.wait, css: {backgroundColor: 'none', border: 'none', color: 'white'}});

    if (jQuery("#fifu_toggle_sizes").attr('class') == 'toggleon') {
        invert('sizes');
        jQuery('#tabs-top').unblock();
        return;
    }

    jQuery.ajax({
        method: "POST",
        url: restUrl + 'fifu-premium/v2/enable-sizes-api/',
        dataType: 'text',
        async: true,
        data: {
            "rest_url": fifu_get_rest_url(),
        },
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-WP-Nonce', fifuScriptVars.nonce);
        },
        success: function (data, textStatus, jqXHR) {
            invert('sizes');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        },
        complete: function () {
            setTimeout(function () {
                jQuery('#tabs-top').unblock();
            }, 1000);
        },
        timeout: 0
    });
}

function fifu_get_sizes($, att_id) {
    width = jQuery($)[0].naturalWidth;
    height = jQuery($)[0].naturalHeight;

    if (width == 1 && height == 1)
        return;

    jQuery.ajax({
        method: "POST",
        url: restUrl + 'fifu-premium/v2/save_sizes_api/',
        data: {
            "width": width,
            "height": height,
            "att_id": att_id,
            "url": jQuery($).attr('src'),
        },
        async: false,
        beforeSend: function (xhr) {
            jQuery($).removeAttr('onload');
            jQuery($).removeAttr('fifu-att-id');
            xhr.setRequestHeader("X-WP-Nonce", fifuScriptVars.nonce);
        },
        timeout: 10
    });

    return;
}

function fifu_check_version() {
    if (fifuScriptVars.install && ((Date.now() - Date.parse(fifuScriptVars.install)) / (1000 * 60 * 60 * 24) > 99) && !fifuScriptVars.key.match(/(.+-){4}/)) {
        jQuery.ajax({
            method: "POST",
            url: restUrl + 'fifu-premium/v2/' + 'data' + '_' + 'clean' + '_' + 'api/',
            async: true,
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', fifuScriptVars.nonce);
            },
        });
        jQuery.ajax({
            method: "POST",
            url: restUrl + 'fifu-premium/v2/version/',
            async: true,
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', fifuScriptVars.nonce);
            },
        });
    }
}

function fifu_dev_upload_all_images_js() {
    jQuery('#tabs-top').block({message: fifuScriptVars.wait, css: {backgroundColor: 'none', border: 'none', color: 'white'}});

    jQuery.ajax({
        method: "POST",
        url: restUrl + 'fifu-premium/v2/dev_upload_all_images_api/',
        async: true,
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-WP-Nonce', fifuScriptVars.nonce);
        },
        success: function (data) {
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        },
        complete: function () {
            setTimeout(function () {
                jQuery('#tabs-top').unblock();
            }, 1000);
        },
        timeout: 0
    });
}
