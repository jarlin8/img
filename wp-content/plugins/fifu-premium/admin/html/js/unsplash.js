function fifu_get_unsplash_urls(keywords, limit, size) {
    const urls = [];
    var count = 1;
    var LIMIT = limit;
    var sleepyAlert = setInterval(function () {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function (e) {
            if (xhr.status == 200 && xhr.readyState == 4) {
                url = xhr.responseURL;
                imageId = url.split('-')[2].split('?')[0];
                if (!idSet.has(imageId)) {
                    idSet.add(imageId);
                    urls.push(url);
                } else
                    LIMIT--;
            }
        };
        xhr.open("GET", "https://source.unsplash.com/" + size + "/?" + keywords + "&" + Math.random() * 10000, true);
        xhr.send();
        if (count++ >= LIMIT) {
            clearInterval(sleepyAlert);
            (async() => {
                // waiting for urls
                while (urls.length < LIMIT)
                    await new Promise(resolve => setTimeout(resolve, 250));
                // ready
                for (i = 0; i < urls.length; i++) {
                    jQuery('div.masonry').append('<div class="mItem" style="max-width:400px;object-fit:content"><img src="' + urls[i] + '" style="width:100%"></div>');
                }
                jQuery('#fifu-loading').remove();
                fifu_scrolling = false;
            })();
        }
    }, 50);
}

function fifu_get_ddg_urls(post_id) {
    postTitle = fifu_get_post_title();
    if (!postTitle && !post_id)
        return;

    let aux_vars =
            typeof fifuScriptVars !== 'undefined' ? fifuScriptVars :
            typeof fifuColumnVars !== 'undefined' ? fifuColumnVars :
            null;

    const urls = [];

    jQuery.ajax({
        method: "POST",
        url: aux_vars.restUrl + 'fifu-premium/v2/ddg_search/',
        async: false,
        data: {
            "keywords": postTitle,
            "post_id": post_id
        },
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-WP-Nonce', aux_vars.nonce);
        },
        success: function (data) {
            urls.push(...data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        },
        complete: function (data) {
            (async () => {
                // ready
                for (let i = 0; i < urls.length; i++) {
                    jQuery('div.masonry').append('<div class="mItem" style="max-width:400px;object-fit:content"><img src="' + urls[i].url + '" style="width:100%" loading="lazy" onerror="fifu_handle_image_error(this);"></div>');
                }
                jQuery('#fifu-loading').remove();
                fifu_scrolling = false;
            })();
        },
    });
}

function fifu_handle_image_error(imageElement) {
    imageElement.parentNode.remove();
}

var fifu_scrolling = false;
var idSet = new Set();

function fifu_start_lightbox(keywords, unsplash, post_id) {
    idSet = new Set();
    fifu_register_unsplash_click_event();

    size = 'featured';

    txt_loading = typeof fifuMetaBoxVars !== 'undefined' ? fifuMetaBoxVars.txt_loading : '';
    txt_more = typeof fifuMetaBoxVars !== 'undefined' ? fifuMetaBoxVars.txt_more : '';

    jQuery.fancybox.open('<div><div class="masonry"></div></div>');
    jQuery('div.masonry').after('<center><div id="fifu-loading"><img src="https://cdnjs.cloudflare.com/ajax/libs/jquery.lazyloadxt/1.1.0/loading.gif"><div>' + txt_loading + '</div><div></center>');

    if (!unsplash) {
        fifu_get_ddg_urls(post_id);
        return;
    }

    fifu_get_unsplash_urls(keywords, 10, size);
    jQuery('div[class^=fancybox]').scroll(function () {
        if (jQuery(this).scrollTop() + jQuery('div.fancybox-container')[0].scrollHeight > parseInt(jQuery('div.fancybox-slide > div.fancybox-content').last().height())) {
            if (!fifu_scrolling) {
                fifu_scrolling = true;
                jQuery('#fifu-loading').remove();
                jQuery('div.masonry').after('<center><div id="fifu-loading"><img src="https://cdnjs.cloudflare.com/ajax/libs/jquery.lazyloadxt/1.1.0/loading.gif"><div>' + txt_more + '</div><div></center>');
                fifu_get_unsplash_urls(keywords, 10, size);
            }
        }
    });
}

function fifu_register_unsplash_click_event() {
    jQuery('body').on('click', 'div.mItem > img', function (evt) {
        evt.stopImmediatePropagation();
        // meta-box
        if (jQuery("#fifu_input_url").length) {
            jQuery("#fifu_input_url").val(jQuery(this).attr('src'));
            previewImage();
        }
        // quick-edit
        if (jQuery("#fifu-quick-search-input-keywords").length) {
            jQuery("#fifu-quick-input-url").val(jQuery(this).attr('src'));
            // jQuery("#fifu-quick-input-url").trigger('input');
            jQuery("#fifu-quick-search-input-keywords").val('');
            jQuery('#fifu-save-button').click();
        }
        jQuery.fancybox.close();
    });
}

function fifu_get_post_title() {
    if (wp && wp.data && wp.data.select('core/editor'))
        return wp.data.select('core/editor').getEditedPostAttribute('title');

    var titleElement = document.getElementById('title');
    if (titleElement)
        return titleElement.value;

    return null;
}
