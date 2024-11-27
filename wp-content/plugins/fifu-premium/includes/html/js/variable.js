jQuery(document).ready(function ($) {

    jQuery('table.variations tbody tr td select').on('change', function () {
        ids = null;
        jQuery('table.variations tbody tr td select').each(function (index) {
            attr_name = jQuery(this).attr('name');
            attr_val = jQuery(this).val();

            // continue
            if (!attr_val)
                return;

            if (!ids) {
                if (typeof fifuVariableVars.attribute_map[attr_name] === 'undefined')
                    return;
                ids = fifuVariableVars.attribute_map[attr_name][attr_val];
            } else {
                tmp = fifuVariableVars.attribute_map[attr_name][attr_val];
                ids = ids.filter(value => tmp.includes(value))
            }
        });

        // main
        if (!ids) {
            ids = [fifuVariableVars.post_id];
        } else {
            hasImages = false;
            for (i = 0; i < ids.length; i++) {
                if (fifuVariableVars.url_map[ids[i]][0]) {
                    hasImages = true;
                    break;
                }
            }
            if (!hasImages)
                ids = [fifuVariableVars.post_id];
        }

        dataVideoMap = fifuGetDataVideoMap();

        galParent = jQuery('.fifu-product-gallery').parent().parent().parent();
        galParent.empty();
        galParent.html('<ul id="image-gallery" class="gallery list-unstyled fifu-product-gallery lightSlider"></ul>');

        // add
        urlset = new Set()
        for (i = 0; i < ids.length; i++) {
            for (j = 0; j < fifuVariableVars.url_map[ids[i]].length; j++) {
                if (fifuVariableVars.url_map[ids[i]][0]) {
                    clazz = (i == 0 && j == 0) ? "lslide active" : "lslide";
                    url = fifuVariableVars.url_map[ids[i]][j];

                    // avoid duplicated urls
                    if (urlset.has(url))
                        continue;
                    urlset.add(url);

                    src = url;
                    poster = '';
                    if (fifuVariableVars.fifu_video && is_video_img(url)) {
                        src = video_url(url);
                        poster = `data-poster="${url}"`;
                    }

                    if (fifuVariableVars.fifu_lazy && url.includes('.wp.com')) {
                        thumb_url = `${url}?w=175&resize=175`;
                        srcset = fifuVariableVars.srcset_map[url];
                        jQuery('.fifu-product-gallery').append('<li data-thumb="' + thumb_url + '" ' + poster + ' data-src="' + FIFU_PLACEHOLDER + '" data-srcset="' + srcset + '" class="' + clazz + '"><img fifu-replaced="1" data-src="' + thumb_url + '" data-srcset="' + srcset + '" data-sizes="auto" class="lazyload">' + '</li>');
                    } else {
                        dataVideo = dataVideoMap[url];
                        dataVideo = dataVideo ? `data-video="${dataVideo.replace(/"/g, '&quot;')}"` : `data-src="${src}"`;
                        jQuery('.fifu-product-gallery').append(`<li data-thumb="${url}" ${dataVideo} ${poster} class="${clazz}"><img fifu-replaced="1" src="${url}" class="fifu"></li>`);
                    }
                }
            }
        }

        fifu_slider = fifu_load_slider();
        if (fifuVariableVars.fifu_video)
            replaceVideoThumb($);
    });

    // keep the product when the page is refreshed
    jQuery('table.variations tbody tr td select').trigger('change');
});

function fifuGetDataVideoMap() {
    var map = {};
    jQuery('#image-gallery li').each(function () {
        var thumb = jQuery(this).attr('data-thumb');
        var video = jQuery(this).attr('data-video');
        if (thumb && video) {
            map[thumb] = video;
        }
    });
    return map;
}
