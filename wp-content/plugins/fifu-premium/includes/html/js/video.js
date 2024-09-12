document.addEventListener("DOMContentLoaded", function () {
    if (typeof fifuVideoThumbVars !== 'undefined' && typeof fifuVideoThumbVarsFooter !== 'undefined') {
        Object.assign(fifuVideoThumbVars.thumbs, fifuVideoThumbVarsFooter);
    }
});

jQuery(document).ready(function ($) {
    // don't put timeout here, otherwise the lazyload will fail for videos

    if (!fifuVideoVars.fifu_should_wait_ajax)
        replaceVideoThumb($);

    jQuery(".pswp__counter").bind("DOMSubtreeModified", function ($) {
        replaceImageDlg($);
    });

    setTimeout(function () {
        fifu_fix_youtube_thumbnails();
    }, 2000);

    setTimeout(function () {
        // video height
        wrapper = jQuery('div.fifu_wrapper')[0];
        if (wrapper) {
            height = wrapper.clientHeight;

            if (fifuVideoVars.fifu_is_divi_active) {
                if (wrapper.clientHeight > wrapper.clientWidth) {
                    height /= 2;
                    jQuery('iframe.fifu_iframe').parent().attr('style', '');
                }
            }
        }

        if (fifuVideoVars.fifu_woocommerce)
            fifu_fix_zoom();

        if (fifuVideoVars.fifu_is_divi_active)
            fifu_fix_divi();

        if (fifuVideoVars.fifu_is_elementor_active) {
            fifu_fix_elementor();
            jQuery('article.elementor-portfolio-item').on('mouseenter', function ($) {
                play = jQuery(this).find('a div div.fifu_play');
                if (play.length == 1)
                    play.mouseenter();
            });
        }

        if (fifuVideoVars.fifu_is_play_type_lightbox) {
            jQuery("div.woocommerce-product-gallery__image").find("div.fifu_play.start").on("click", function ($) {
                jQuery.fancybox.open([{src: jQuery(this).find('a').attr('href')}]);
            });
        }
    }, 200);

    if (fifuVideoVars.fifu_essential_grid_active) {
        fifu_fix_essential_grid();
    }
});

jQuery(document).click(function ($) {
    fifu_fix_youtube_thumbnails();

    // zoom
    jQuery("a.woocommerce-product-gallery__trigger").on("click", function ($) {
        setTimeout(function () {
            replaceImageDlg($);
        }, 100);
    });
    // arrows
    jQuery("button.pswp__button, button.pswp__button--arrow--left, button.pswp__button--arrow--right").on("click", function ($) {
        replaceImageDlg($);
    });

    jQuery('.pswp__zoom-wrap').on("click", function ($) {
        if (fifuVideoVars.fifu_is_flatsome_active)
            jQuery('div.pswp').removeClass('pswp--open');
    });
});

jQuery(document).on("mouseover", function ($) {
    jQuery("div.woocommerce-product-gallery__image").on("mouseover", function ($) {
        if (is_video_img(jQuery(this)[0].dataset.thumb))
            fifu_fix_zoom();
    });
})

jQuery(document).keydown(function (e) {
    setTimeout(function () {
        switch (e.which) {
            case 37:// left
                replaceImageDlg($);
                break;
            case 39:// right
                replaceImageDlg($);
                break;
        }
    }, 100);
});

function replaceVideoThumb($) {
    // check if elementor exists
    $position = typeof jQuery('div.elementor')[0] == "undefined" && fifuVideoVars.fifu_is_flatsome_active ? 'unset' : 'relative';

    var height;
    var width;

    if (fifuVideoVars.fifu_is_home)
        fifu_video_selector = 'img.fifu-video';
    else
        fifu_video_selector = 'img';

    selectors = fifu_video_selector;
    if (!fifuVideoVars.fifu_is_content_views_pro_active)
        selectors += ',[style*="background-image"]';

    jQuery(selectors).each(function (index) {
        if (jQuery(this).prop('tagName') == 'IMG') {
            src = jQuery(this).attr('src');
            background_style = "";
            is_background = false;
        } else {
            src = jQuery(this).css('background-image').split(/url\([\'\"]/)[1].split(/[\'\"]\)/)[0];
            background_style = "style='position:unset'";
            is_background = true;
        }

        // avoid duplicated
        if (jQuery(this).children('iframe').length || jQuery(this).parent().children('iframe').length)
            return;

        // lazy load
        if (!is_video_img(src)) {
            src = jQuery(this).attr('data-src');
        }

        if (!is_video_img(src))
            return;

        // vimeography plugin: ignore images
        if (jQuery(this).hasClass('vimeography-thumbnail-img'))
            return;

        if (jQuery(this).parents('ul.lSPager > li > a').length) {
            if (fifuVideoVars.fifu_is_product && fifuVideoVars.fifu_video_gallery_icon_enabled && jQuery(this).parents().attr('class') != 'fifu_play icon_gallery') {
                jQuery(this).wrap("<div class='fifu_play icon_gallery'></div>");
                jQuery(this).after("<span class='dashicons dashicons-format-video icon_gallery' style='height:24px'></span>");
            }
            return;
        }

        if (jQuery(this).parent().parent().find('.fifu_play').length && !jQuery(this).parent().parent().hasClass('fifu-product-gallery'))
            return;

        if (jQuery(this).parents('ol.flex-control-nav').length)
            return;

        // minimum video width
        minWidth = fifuVideoVars.fifu_video_min_width;
        var width = jQuery(this)[0].clientWidth;
        if (width == 0)
            width = jQuery(this).parent()[0].clientWidth;

        // the last condition is for related products
        if (
                // autoplay for video image thumbnail + play button
                !(should_autoplay())
                &&
                !(fifuVideoVars.fifu_video_background_enabled && is_vimeo_img(src) && (!fifuVideoVars.fifu_video_background_single_enabled || (fifuVideoVars.fifu_video_background_single_enabled && fifuVideoVars.fifu_url == src)))
                &&
                ((fifuVideoVars.fifu_is_product && jQuery(this).parentsUntil('div.woocommerce-product-gallery').length) ||
                        (fifuVideoVars.fifu_video_thumb_enabled_home) ||
                        (fifuVideoVars.fifu_video_thumb_enabled_page) ||
                        (fifuVideoVars.fifu_video_thumb_enabled_post) ||
                        (fifuVideoVars.fifu_video_thumb_enabled_cpt) ||
                        (minWidth && (width != null && (width < Number(minWidth) || width == 0))))
                ) {
            display_play_button = false;
            if ((width == 0 || width > minWidth) && jQuery(this).parent().attr('class') != 'fifu_play' && !jQuery(this).hasClass('fifu_video_thumb_bg') && !fifuVideoVars.fifu_should_hide) {
                display_play_button = fifuVideoVars.fifu_play_button_enabled && !is_suvideo_img(src);
                if (display_play_button) {
                    if (fifuVideoVars.fifu_url != src && ((fifuVideoVars.fifu_play_hide_grid && fifuVideoVars.fifu_is_home && !fifuVideoVars.fifu_is_shop) || (fifuVideoVars.fifu_play_hide_grid_wc && (fifuVideoVars.fifu_is_shop || fifuVideoVars.fifu_is_product_category)))) {
                        jQuery(this).wrap("<div class='fifu_play icon'></div>");
                        jQuery(this).after("<span class='dashicons dashicons-format-video icon'></span>");
                    } else {
                        // ignore thumbnails below slider
                        if (!jQuery(this).parent().parent().parent().hasClass('lSGallery')) {
                            if (is_background) {
                                jQuery(this).addClass('fifu_video_thumb_bg');
                                if (fifuVideoVars.fifu_is_play_type_inline) {
                                    /*** inline ***/

                                    // for WP Grid Builder plugin
                                    if (jQuery(this).parent().hasClass('wpgb-handle-lb')) {
                                        jQuery(this).unwrap();
                                        jQuery(this).next().remove();
                                    }

                                    jQuery(this).append(`
                                        <div class='fifu_play_bg' href='/' onclick='return false'></div>
                                    `);
                                    registerReplaceOnClick();
                                } else {
                                    /*** lightbox ***/

                                    if (fifuVideoVars.fifu_mouse_youtube_enabled || fifuVideoVars.fifu_mouse_vimeo_enabled) {
                                        // mouseover
                                        jQuery(this).after("<div class='fifu_play_bg' onmouseenter='jQuery.fancybox.open([{src:\"" + video_url(src) + "\"}])' data-fancybox href='" + video_url(src) + "'></div>");
                                    } else
                                        jQuery(this).after("<div class='fifu_play_bg' data-fancybox href='" + video_url(src) + "' data-type='iframe'></span></div>");
                                }
                            } else {
                                jQuery(this).wrap("<div class='fifu_play start' " + background_style + "></div>");
                                if (fifuVideoVars.fifu_is_play_type_inline) {
                                    // inline
                                    jQuery(this).after(`
                                        <div class='fifu_link' href='/' onclick='return false'>
                                            <span class='dashicons dashicons-controls-play btn'></span>
                                        </div>
                                    `);
                                    registerReplaceOnClick();
                                } else {
                                    // lightbox                                 
                                    if (fifuVideoVars.fifu_mouse_youtube_enabled || fifuVideoVars.fifu_mouse_vimeo_enabled) {
                                        // mouseover
                                        jQuery(this).after("<div class='fifu_link' onmouseenter='jQuery.fancybox.open([{src:\"" + video_url(src) + "\"}])' data-fancybox href='" + video_url(src) + "'><span class='dashicons dashicons-controls-play btn'></span></div>");
                                    } else
                                        jQuery(this).after("<div class='fifu_link' data-fancybox href='" + video_url(src) + "' data-type='iframe'><span class='dashicons dashicons-controls-play btn'></span></div>");
                                }
                            }
                        }
                    }

                    if (fifuVideoVars.fifu_is_elementor_active) {
                        parentClass = jQuery(this).parent().parent().attr('class');
                        if (parentClass && parentClass.startsWith('elementor-'))
                            jQuery(this).parent().css('position', 'unset')
                    }
                } else if (is_suvideo_img(src)) {
                    if (fifuVideoVars.fifu_is_play_type_inline)
                        registerReplaceOnClick();
                    else
                        jQuery(this).wrap("<div class='fifu_link' data-fancybox href='" + video_url(src) + "'></div>");
                }
            }

            if (fifuVideoVars.fifu_later_enabled) {
                if (!display_play_button || (fifuVideoVars.fifu_play_hide_grid && fifuVideoVars.fifu_is_home && !fifuVideoVars.fifu_is_shop)) {
                    jQuery(this).wrap("<div class='fifu_play start' " + background_style + "></div>");
                    button = "";
                } else
                    button = "<span class='dashicons dashicons-controls-play btn'></span>";

                icon = fifuWatchLaterQueue.has(src) ? 'yes' : 'clock';
                jQuery(this).after(`
                    <div class='fifu_link' href='/' onclick='return false'>
                        ${button}
                        <span title="${fifuVideoVars.text_later}" class='dashicons dashicons-${icon} icon w-later-thumb' thumb='${src}'></span>
                        <span title="${fifuVideoVars.text_queue}" class='dashicons dashicons-playlist-video icon w-later-thumb' style='top:40px'></span>
                    </div>`
                        );
            }

            jQuery(this).css('opacity', 1);
            return;
        }

        if (jQuery(this).attr('class') == 'zoomImg')
            return;

        // too small for autoplay
        if (width < Number(minWidth))
            return;

        if (is_video_img(src)) {
            url = video_url(src);
            if (!url)
                return;
            url = add_parameters(url, src);
            $autoplay = should_autoplay() ? 'allow="autoplay"' : '';
            controls = fifuVideoVars.fifu_video_controls ? '' : ' fifu_no_controls';
            iframeId = simpleHash(url);

            w = 'width:' + jQuery(this)[0].clientWidth + 'px';
            h = 'height:' + jQuery(this)[0].clientHeight + 'px';

            if (is_background) {
                $iframe_class = fifuVideoVars.fifu_lazy ? 'lazyload' : '';

                $video = '<iframe id="' + iframeId + '" class="' + $iframe_class + controls + '" ' + fifuVideoVars.fifu_lazy_src_type + '"' + url + '" allowfullscreen frameborder="0" ' + $autoplay + ' style="' + w + ';' + h + '"></iframe>';
                jQuery(this).append($video);
                jQuery(this).css('background-image', '');
            } else {
                $iframe_class = 'fifu_iframe';
                $iframe_class += fifuVideoVars.fifu_lazy ? ' lazyload' : '';

                if ((is_wpcom_video_img(src) && src.includes('mp4')) || (is_local_video_img(src) && src.includes('mp4'))) {
                    url = url.split('?')[0];
                    $video = `<video id="${iframeId}" class="${controls}" style="${w};${h}" controls autoplay muted><source src="${url}" type="video/mp4"></source></video>`;
                } else {
                    $video = '<div class="fifu_wrapper"><div class="fifu_h_iframe" style="position:' + $position + '"><img class="fifu_ratio" src="' + src + '"/><iframe id="' + iframeId + '" class="' + $iframe_class + controls + '" ' + fifuVideoVars.fifu_lazy_src_type + '"' + url + '" allowfullscreen frameborder="0" ' + $autoplay + '></iframe></div></div>';
                }
                $parent = jQuery(this).parent();
                jQuery(this).replaceWith($video);

                if (fifuVideoVars.fifu_later_enabled) {
                    icon = fifuWatchLaterQueue.has(src) ? 'yes' : 'clock';
                    $parent.prepend(`
                        <div class='fifu_play start'>
                            <div class='fifu_link' href='/' onclick='return false'>
                                <span title="${fifuVideoVars.text_later}" class='dashicons dashicons-${icon} icon w-later-thumb' thumb='${src}'></span>
                                <span title="${fifuVideoVars.text_queue}" class='dashicons dashicons-playlist-video icon w-later-thumb' style='top:40px'></span>
                            </div>
                        </div>`
                            );
                }
            }
            fifu_autoplay_mouseover_youtube(iframeId);
            fifu_autoplay_mouseover_vimeo(iframeId);
        }
    });

    adjust_local_video();
}

function replaceImageDlg($) {
    jQuery('div.pswp__zoom-wrap').each(function () {
        img = jQuery(this).find('img.pswp__img');
        src = img.attr('src');
        if (!is_video_img(src))
            return;
        w = jQuery(window).width() * 0.62;
        img.attr('style', '');
        img.css('display', 'unset');
        img.css('position', 'unset');
        img.css('width', w);
        jQuery(this).replaceWith('<div class="pswp__zoom-wrap">' + '<div class="wrapper"><div class="fifu_play start">' + img[0].outerHTML + '<a data-fancybox data-src="' + video_url(src) + '"><span class="dashicons dashicons-controls-play btn"></span></a></div></div></div>');
        registerReplaceOnClick();
    });
}

// play button event
function registerReplaceOnClick() {
    // no effect on fifu product gallery
    if (jQuery('div.fifu_play.start, div.fifu_play_bg').parents('div.fifu-slider').length) {
        return;
    }

    var events = "click";

    if (fifuVideoVars.fifu_mouse_youtube_enabled || fifuVideoVars.fifu_mouse_vimeo_enabled)
        events += " mouseenter";

    jQuery('div.fifu_play.start, div.fifu_play_bg, img.fifu-video[data-src^="https://cdn.fifu.app"]').on(events, function ($) {
        $.stopPropagation();

        // check if has clicked on the play button instead of the thumbnail
        if (jQuery($.target).attr('class').includes('fifu-video') && (jQuery($.target).attr('data-src') && !jQuery($.target).attr('data-src').includes('https://cdn.fifu.app')))
            return;

        if (jQuery($.target).attr('class').includes('dashicons') && !jQuery($.target).attr('class').includes('controls-play'))
            return;

        tag = jQuery(this)[0].tagName == 'IMG' ? jQuery(this) : jQuery(this).find('img');
        if (tag.length) {
            selector = 'img';
            src = tag[0].src;
            is_background = false;
        } else {
            tag = jQuery(this).parent();
            if (tag.css('background-image') == undefined)
                return;
            src = tag.css('background-image').split(/url\([\'\"]/)[1].split(/[\'\"]\)/)[0];
            is_background = true;
        }

        w = 'width:' + tag[0].clientWidth + 'px';
        h = 'height:' + tag[0].clientHeight + 'px';
        if (!fifuVideoVars.fifu_is_product) {
            // to keep bottom padding
            if (!is_background && ((!fifuVideoVars.fifu_is_home && !fifuVideoVars.fifu_is_post) || fifuVideoVars.fifu_is_shop))
                jQuery(this).after('<img src="" style="width:0px;height:0px;display:block"/>');
        } else {
            // to show the image on woocommerce lightbox
            img = tag[0];
            jQuery(this).after(img);
            jQuery(img).css('height', '0px');
            jQuery(img).css('display', 'block');
        }

        greatGrandFatherClass = jQuery(this).parent().parent().parent().attr('class');
        if (fifuVideoVars.fifu_is_elementor_active && greatGrandFatherClass && greatGrandFatherClass.startsWith('elementor-post'))
            jQuery(this).parent().attr('class', '');

        url = video_url(src);
        // add parameters
        url = add_parameters(url, src);
        if (is_sprout_video(url))
            autoplay = 'autoPlay=true';
        else if (is_rumble_video(url))
            autoplay = 'pub=7a20&rel=5&autoplay=2';
        else if (is_bunny_video(url) || is_googledrive_video(url))
            autoplay = '';
        // else if (is_mega_video(url))
        // autoplay = '!1m1a';
        else
            autoplay = 'autoplay=1';
        url += parameter_char(url) + autoplay;
        controls = fifuVideoVars.fifu_video_controls ? '' : ' fifu_no_controls';

        iframeId = simpleHash(url);

        // mov files are not supported by iframe, except in firefox
        image_url = src;
        if (is_local_video_img(image_url) && image_url.includes('mov') && navigator.userAgent.indexOf("Firefox") == -1) {
            video = '<div style="background:url(https://storage.googleapis.com/featuredimagefromurl/video-loading.gif) no-repeat center center black;' + h + '"><video id="' + iframeId + '" class="' + controls + '" src="' + url + '" style="' + w + ';' + h + '" controls autoplay></video></div>';
            if (is_background) {
                tag.append(video);
                tag.unwrap();
                tag.next().remove();
            } else
                jQuery(this).replaceWith(video);
            return;
        }

        if (is_wpcom_video_img(image_url) && image_url.includes('mp4')) {
            url = url.split('?')[0];
            video = '<div style="background:url(https://storage.googleapis.com/featuredimagefromurl/video-loading.gif) no-repeat center center black;' + h + '"><video id="' + iframeId + '" class="' + controls + '" src="' + url + '" style="' + w + ';' + h + '" controls autoplay muted></video></div>';
            if (is_background) {
                tag.append(video);
                tag.unwrap();
                tag.next().remove();
            } else
                jQuery(this).replaceWith(video);
            return;
        }

        video = `<div style="background:url(https://storage.googleapis.com/featuredimagefromurl/video-loading.gif) no-repeat center center black;${h}"><iframe id="${iframeId}" class="${controls}" src="${url}" style="${w};${h}" allowfullscreen frameborder="0" allow="autoplay" thumb="${image_url}"></iframe></div>`;
        if (is_background) {
            tag.append(video);
            tag.find('.fifu_play_bg').remove();
        } else
            jQuery(this).replaceWith(video);

        if (fifuVideoVars.fifu_mouse_youtube_enabled || fifuVideoVars.fifu_mouse_vimeo_enabled) {
            fifu_autoplay_mouseover_youtube(iframeId);
            fifu_autoplay_mouseover_vimeo(iframeId);
        } else {
            fifu_autoplay_youtube_now(url);
            // fifu_autoplay_vimeo_now(url);
        }

        if (fifuVideoVars.fifu_later_enabled) {
            setTimeout(function () {
                fifu_add_watch_later();
            }, 500);
        }
    });
}

jQuery(document).ajaxComplete(function ($) {
    jQuery('.fifu-video').each(function (index) {
        jQuery(this).css('opacity', 1);
    });
    setTimeout(function () {
        replaceVideoThumb($);
    }, 10);
});

function is_video_img($src) {
    return !$src ? null : is_suvideo_img($src) || is_youtube_img($src) || is_vimeo_img($src) || is_cloudinary_video_img($src) || is_tumblr_video_img($src) || is_local_video_img($src) || is_publitio_video_img($src) || is_gag_video_img($src) || is_wpcom_video_img($src) || is_tiktok_video_img($src) || is_googledrive_video_img($src) || is_mega_video_img($src) || is_bunny_video_img($src) || is_bitchute_video_img($src) || is_brighteon_video_img($src) || is_amazon_video_img($src) || is_jwplayer_img($src) || is_sprout_img($src) || is_rumble_img($src) || is_dailymotion_img($src) || is_twitter_img($src) || is_cloudflarestream_img($src) || is_odysee_video_img($src);
}

function is_youtube_img($src) {
    return $src.includes('img.youtube.com');
}

function is_vimeo_img($src) {
    return $src.includes('i.vimeocdn.com');
}

function is_cloudinary_video_img($src) {
    return $src.includes('res.cloudinary.com') && $src.includes('/video/');
}

function is_tumblr_video_img($src) {
    return $src.includes('tumblr.com');
}

function is_local_video_img($src) {
    return $src.includes('/wp-content/uploads/') && $src.includes('-fifu-');
}

function is_publitio_video_img($src) {
    return $src.includes('publit.io');
}

function is_gag_video_img($src) {
    return $src.includes('9cache.com');
}

function is_wpcom_video_img($src) {
    return $src.includes('videos.files.wordpress.com') && $src.includes('.jpg');
}

function is_tiktok_video_img($src) {
    return $src.includes('tiktokcdn.com');
}

function is_googledrive_video_img($src) {
    return $src.includes('/fifu/videothumb/googledrive/');
}

function is_mega_video_img($src) {
    return $src.includes('/fifu/videothumb/mega/');
}

function is_bunny_video_img($src) {
    return $src.includes('b-cdn.net');
}

function is_bitchute_video_img($src) {
    return $src.includes('bitchute.com/live');
}

function is_brighteon_video_img($src) {
    return $src.includes('photos.brighteon.com');
}

function is_amazon_video_img($src) {
    return $src.includes('m.media-amazon.com') && $src.includes('SX1600_.');
}

function is_jwplayer_img($src) {
    return $src.includes('jwplatform.com');
}

function is_sprout_img($src) {
    return $src.includes('cdn-thumbnails.sproutvideo.com');
}

function is_rumble_img($src) {
    return $src.includes('rmbl.ws');
}

function is_dailymotion_img($src) {
    return $src.includes('dmcdn.net');
}

function is_twitter_img($src) {
    return $src.includes('pbs.twimg.com');
}

function is_cloudflarestream_img($src) {
    return $src.includes('cloudflarestream.com') && $src.includes('/thumbnails/');
}

function is_suvideo_img($src) {
    return $src.includes('cdn.fifu.app') && $src.includes('video-thumb=');
}

function is_odysee_video_img($src) {
    return $src.includes('thumbnails.odycdn.com');
}

function is_sprout_video($src) {
    return $src.includes('videos.sproutvideo.com');
}

function is_googledrive_video($src) {
    return $src.includes('drive.google.com/file');
}

function is_mega_video($src) {
    return $src.includes('mega.nz');
}

function is_bunny_video($src) {
    return $src.includes('video.bunnycdn.com');
}

function is_bitchute_video($src) {
    return $src.includes('www.bitchute.com');
}

function is_brighteon_video($src) {
    return $src.includes('www.brighteon.com');
}

function is_amazon_video($src) {
    return $src.includes("m.media-amazon.com") && $src.includes(".mp4");
}

function is_rumble_video($src) {
    return $src.includes('rumble.com');
}

function is_dailymotion_video($src) {
    return $src.includes('dailymotion.com');
}

function is_twitter_video($src) {
    return $src.includes('twitter.com');
}

function is_cloudflarestream_video($src) {
    return $src.includes('cloudflarestream.com');
}

function video_id($src) {
    if (is_youtube_img($src))
        return youtube_id($src);
    if (is_vimeo_img($src))
        return vimeo_id($src);
    return null;
}

function youtube_parameter($src) {
    return $src.includes('?') ? $src.split('?')[1] : '';
}

function is_jetpack_src($src) {
    return $src.includes('.wp.com/');
}

function youtube_id($src) {
    index_id = is_jetpack_src($src) ? 5 : 4;
    return $src.split('/')[index_id];
}

function vimeo_id($src) {
    return $src.split('?')[1].replace('/', '?h=');
}

function video_url($src) {
    if (is_suvideo_img($src))
        return suvideo_url($src);

    $src = $src.split(/[\?\&]fifu-/)[0];
    if (is_youtube_img($src))
        return youtube_url($src);
    if (is_vimeo_img($src))
        return vimeo_url($src);
    if (is_cloudinary_video_img($src))
        return cloudinary_url($src);
    if (is_tumblr_video_img($src))
        return tumblr_url($src);
    if (is_local_video_img($src))
        return local_url($src);
    if (is_publitio_video_img($src))
        return publitio_url($src);
    if (is_gag_video_img($src))
        return gag_url($src);
    if (is_wpcom_video_img($src))
        return wpcom_url($src);
    if (is_tiktok_video_img($src))
        return tiktok_url($src);
    if (is_googledrive_video_img($src))
        return googledrive_url($src);
    if (is_mega_video_img($src))
        return mega_url($src);
    if (is_bunny_video_img($src))
        return bunny_url($src);
    if (is_bitchute_video_img($src))
        return bitchute_url($src);
    if (is_brighteon_video_img($src))
        return brighteon_url($src);
    if (is_amazon_video_img($src))
        return amazon_url($src);
    if (is_jwplayer_img($src))
        return jwplayer_url($src);
    if (is_sprout_img($src))
        return sprout_url($src);
    if (is_rumble_img($src))
        return rumble_url($src);
    if (is_dailymotion_img($src))
        return dailymotion_url($src);
    if (is_twitter_img($src))
        return twitter_url($src);
    if (is_cloudflarestream_img($src))
        return cloudflarestream_url($src);
    if (is_odysee_video_img($src))
        return odysee_url($src);
    return null;
}

function youtube_url(src) {
    embed_url = fifuVideoThumbVars['thumbs'][src];

    if (fifuVideoVars.fifu_privacy_enabled)
        embed_url = embed_url.replace('www.youtube.com', 'www.youtube-nocookie.com');

    domain = fifuVideoVars.fifu_privacy_enabled ? 'www.youtube-nocookie.com' : 'www.youtube.com';

    param = youtube_parameter(src);
    param_char = param ? '&' : '';

    return embed_url + '?' + youtube_parameter(src) + param_char + 'enablejsapi=1';
}

function vimeo_url($src) {
    return fifuVideoThumbVars['thumbs'][$src];
}

function cloudinary_url($src) {
    return $src.replace('jpg', 'mp4');
}

function tumblr_url($src) {
    $tmp = $src.replace('https://78.media.tumblr.com', 'https://vt.media.tumblr.com');
    return $tmp.replace('_smart1.jpg', '.mp4');
}

function local_url($src) {
    $src = $src.replace('-fifu-mp4.webp', '.mp4');
    $src = $src.replace('-fifu-mov.webp', '.mov');
    $src = $src.replace('-fifu-webm.webp', '.webm');
    return $src;
}

function publitio_url($src) {
    return $src.replace('jpg', 'mp4');
}

function gag_url($src) {
    return $src.split('_')[0] + '_460svvp9.webm';
}

function wpcom_url($src) {
    return fifuVideoThumbVars['thumbs'][$src];
}

function tiktok_url($src) {
    return fifuVideoThumbVars['thumbs'][$src];
}

function googledrive_url($src) {
    return fifuVideoThumbVars['thumbs'][$src];
}

function mega_url($src) {
    return fifuVideoThumbVars['thumbs'][$src];
}

function bunny_url($src) {
    return fifuVideoThumbVars['thumbs'][$src];
}

function bitchute_url($src) {
    return fifuVideoThumbVars['thumbs'][$src];
}

function brighteon_url($src) {
    return fifuVideoThumbVars['thumbs'][$src];
}

function amazon_url($src) {
    return fifuVideoThumbVars['thumbs'][$src];
}

function jwplayer_url($src) {
    return fifuVideoThumbVars['thumbs'][$src];
}

function sprout_url($src) {
    return fifuVideoThumbVars['thumbs'][$src];
}

function rumble_url($src) {
    return fifuVideoThumbVars['thumbs'][$src];
}

function dailymotion_url($src) {
    return fifuVideoThumbVars['thumbs'][$src];
}

function twitter_url($src) {
    return fifuVideoThumbVars['thumbs'][$src];
}

function cloudflarestream_url($src) {
    return $src.replace('thumbnails/thumbnail.jpg', 'iframe');
}

function odysee_url($src) {
    return fifuVideoThumbVars['thumbs'][$src];
}

function suvideo_url($src) {
    aux = $src.split('&resize=')[0];
    aux = fifuVideoThumbVars['thumbs'][aux];
    if (aux)
        return aux;

    aux = $src.split('video-thumb=')[1];
    aux = aux.split('&resize=')[0];
    return video_url(aux);
}

jQuery(window).on('load', function () {
    // lazyload
    jQuery('iframe').on('load', function () {
        fifu_autoplay_mouseover_vimeo(jQuery(this).attr('id'));
        fifu_autoplay_mouseover_youtube(jQuery(this).attr('id'));
    });
});

function fifu_autoplay_mouseover_vimeo(iframeId) {
    enabled = fifuVideoVars.fifu_mouse_vimeo_enabled;
    if (!enabled)
        return;

    src = jQuery(`#${iframeId}`).attr('src');
    datasrc = jQuery(`#${iframeId}`).attr('data-src');
    if ((src && !src.includes("vimeo.com")) || (datasrc && !datasrc.includes("vimeo.com")))
        return;

    setTimeout(function () {
        let vimeoPlayer = new Vimeo.Player(jQuery(`#${iframeId}`));
        jQuery(`#${iframeId}`).on("mouseover", function () {
            vimeoPlayer.play();
            if (!!window.chrome)
                vimeoPlayer.setVolume(0);
        }).mouseout(function () {
            vimeoPlayer.pause();
        });
    }, 500);
}

var fifuPlayers = fifuPlayers ? fifuPlayers : {};
var vimeo_players = [];

function fifu_autoplay_mouseover_youtube(iframeId) {
    enabled = fifuVideoVars.fifu_mouse_youtube_enabled;
    if (!enabled)
        return;

    src = jQuery(`#${iframeId}`).attr('src');
    datasrc = jQuery(`#${iframeId}`).attr('data-src');
    if ((src && !src.includes("youtu")) || (datasrc && !datasrc.includes("youtu")))
        return;

    var fifuPlayers = fifuPlayers ? fifuPlayers : {};

    window.YT.ready(function () {
        fifuPlayers[iframeId] = new YT.Player(iframeId);
    });
    setTimeout(function () {
        jQuery(`#${iframeId}`).on("mouseover", function () {
            if (typeof fifuPlayers[iframeId].playVideo === "function") {
                fifuPlayers[iframeId].playVideo();
                if (!!window.chrome)
                    fifuPlayers[iframeId].mute();
            }
        }).mouseout(function () {
            if (typeof fifuPlayers[iframeId].pauseVideo === "function") {
                fifuPlayers[iframeId].pauseVideo();
            }
        });
    }, 500);
}

function fifu_autoplay_youtube_now(url) {
    jQuery('iframe').each(function (index) {
        if (this.src == url && this.src.includes("youtu") && this.src.includes("autoplay=1") && typeof window.YT !== 'undefined') {
            window.YT.ready(function () {
                fifuPlayers[index] = new YT.Player(this, {
                    events: {
                        'onReady': onPlayerReady,
                        'onStateChange': onPlayerStateChange
                    }
                });
            });
        }
    });
}

function fifu_autoplay_vimeo_now(url) {
    jQuery('iframe').each(function (index) {
        if (this.src == url && this.src.includes("vimeo") && this.src.includes("autoplay=1")) {
            // vimeo_players[index] = new Vimeo.Player(this);
            // vimeo_players[index].on('play', function() {console.log('Playing...')});
            // vimeo_players[index].ready().then(onPlayerReadyVimeo(index));
        }
    });
}

function onPlayerReady(event) {
    event.target.playVideo();
}

function onPlayerStateChange(event) {
}

function onPlayerReadyVimeo(index) {
    // vimeo_players[index].play();
}

function add_parameters(url, src) {
    loop = fifuVideoVars.fifu_loop_enabled;
    autoplay = should_autoplay();
    video_background = fifuVideoVars.fifu_video_background_enabled && !(fifuVideoVars.fifu_video_background_single_enabled && fifuVideoVars.fifu_url != src);

    if ((loop || autoplay))
        url += parameter_char(url) + 'autopause=1';

    if (autoplay) {
        if (is_rumble_video(url))
            url += parameter_char(url) + 'pub=7a20&rel=5&autoplay=2';
        else
            url += parameter_char(url) + 'autoplay=1';
    }

    if (is_youtube_img(this.src)) {
        if (fifu_should_mute())
            url += parameter_char(url) + 'mute=1';
        if (!fifuVideoVars.fifu_video_controls)
            url += parameter_char(url) + 'controls=0';
    } else if (is_vimeo_img(this.src)) {
        if (fifu_should_mute())
            url += parameter_char(url) + 'muted=1';
        if (video_background)
            url += parameter_char(url) + 'background=1';
    }

    if (loop) {
        url += parameter_char(url) + 'loop=1';
        if (is_youtube_img(src))
            url += parameter_char(url) + 'playlist=' + video_id(src);
    }

    return url;
}

function parameter_char(url) {
    return url.includes('?') ? '&' : '?';
}

// for ajax load more plugin
window.almComplete = function (alm) {
    jQuery('img.fifu-video').css('opacity', 1);
    replaceVideoThumb($);
};

/* CSS issues */

function fifu_fix_elementor() {
    jQuery('div.fifu_wrapper').each(function (index) {
        // video height
        if (jQuery(this).parent().attr('class') && jQuery(this).parent().attr('class').startsWith('elementor-')) {
            // ignore featured video
            src = jQuery(this).find('iframe.fifu_iframe')[0].src;
            if (fifuVideoVars.fifu_url && src.includes(fifuVideoVars.fifu_url))
                return;

            height = jQuery(this).parent()[0].clientHeight;
            /* height = jQuery('div.fifu_wrapper').parent()[0].getBoundingClientRect().height; // float precision */
            jQuery(this).find('iframe.fifu_iframe').css('height', height);
            jQuery(this).find('img.fifu_ratio').css('height', height);
        }

        // portfolio
        if (jQuery(this).parent().attr('class') && jQuery(this).parent().attr('class').includes('elementor-portfolio')) {
            jQuery(this).parent().attr('class', '');
            if (jQuery(this).parent().parent().attr('class').includes('elementor-')) {
                jQuery(this).parent().parent().attr('class', '');
            }
        }
        // posts
        if (jQuery(this).parent().attr('class') && jQuery(this).parent().attr('class').includes('elementor-post')) {
            jQuery(this).parent().attr('class', '');
        }
        // product category
        if (jQuery(this).parent().parent().attr('class').includes('product-category')) {
            jQuery(this).parent().parent().attr('class', '');
        }

        // unwrap from layer
        if (jQuery(this).parent().parent().attr('class').includes('elementor-post__thumbnail__link')) {
            jQuery(this).parent().unwrap();
        }
    });
}

function fifu_fix_zoom() {
    jQuery('img[role=presentation]').css('z-index', '-100');
}

function fifu_fix_divi() {
    jQuery('div.fifu_h_iframe > div.fluid-width-video-wrapper').attr('class', '');
}

function fifu_fix_essential_grid() {
    jQuery("div.eg-youtubestream-container, div.esg-cc").click(function ($) {
        setTimeout(function () {
            replaceVideoThumb($);
        }, 10);
    });
}

function fifu_fix_youtube_thumbnails() {
    jQuery('img[src*="mqdefault"]').each(function (index) {
        src = jQuery(this)[0].src;
        jQuery('*[src^="' + src.replace('mqdefault', 'maxresdefault') + '"]').each(function (index) {
            jQuery(this).attr('src', src);
            jQuery(this).attr('data-src', src);
        });
    });
}

// for infinite scroll
jQuery(document.body).on('post-load', function () {
    jQuery('.fifu-video').each(function (index) {
        jQuery(this).css('opacity', 1);
    });
    replaceVideoThumb($);
});


// speed up
jQuery(document).ready(function ($) {
    // remove hyperlinks
    jQuery('a:has(img.fifu-video[data-src^="https://cdn.fifu.app"])').each(function () {
        if (!jQuery(this).hasClass('fifu_link'))
            jQuery(this).replaceWith(jQuery(this).children());
    });

    // cursor
    jQuery('img.fifu-video[data-src^="https://cdn.fifu.app"]').each(function () {
        jQuery(this).css('cursor', 'pointer');
        // jQuery(this).css('cursor', 'url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAADX0lEQVRIS71VXUiaYRQ+n5lpUzEjLCucYxARoyaN2s0aVKxhNMhYkENYUIxBF+tmNSow8KYGQUuom2hUI8ty4MpCGdWiGnTR7Mo1iMiwHzXJLCXRnfeDIJiasOqFw3fxnZ/3PM9zzkvBDR/qhvPDrRZgC4VCmUAguMvhcLgJCQl8FovFDYfDPCaTeQeNid2GScehUIiBJ+T3+0/xv/v8/HzP4/Hs7ezs/MTfe5dRoTsoKip6jfa+qqpKmp6ezsICQIzNZkNycjIkJSVFRDIYDMLx8TFgcjg6OoKlpaUzg8HgWVhYeIIBf0gQKSDu7Oxcbm9vl1wHH06nE8rLy0/X19fvYb59qqCg4MPMzIwmIyPjOvLTOTo6OsBsNs+urq4+p0pKSj7Pz8+romX3+XxwcHAAUqk07guo1Wpwu93e3t7eh1RFRYXOZDK9jBZttVqhoaEBqquroampiebkqtPf3w+7u7swMjLSRSkUiq96vf5FrALYLkxOTrowuUClUiWgxayh0+lgbm4O8HKfqLq6utnR0dFnsQpotVqw2WzLDofjI/oNYNdpjY2NkJeXFzGMJLfb7dDd3W2ilEqlGVspi1Wgr68Ptra2flgsFiI/Fgpj+imenp4eMhv/HJQpDA4OwubmppGqqan5NjExIY9VADmCsbExK2r9TX5+fhfK+hF+Iw8HJpqamgIi1+HhYQtVWVmpNxqNilgF2traAKfWUVtby6yvr0+jqNgbZmhoiFYeXuo7VVZWNoIkKqMV2N7ehoGBAWhtbQUej3eVgOj/iD2kpqYSmL5QxcXFWsTsLe6duILjcdJoNJCTkwMtLS0GKisrqwJbmi4tLWXEExyPT3NzM2RmZhKI1ARMMs0eJISfnZ0dT3xUH9ysNJwEVoQoiBP96oKte7m5ub+QcLKeyToGXNv0lkxJSYFAIAC4vmlDsoHL5YLX66U5cblctA8hlZAvkUhAJpMRzuwrKyv3L8tBgoT34Zp+XFhYmLyxscGRy+WATnQQQglra2uASgIcTCDTPD4+DggtLC4ugkgkIkWCOGA+/NoPDw/f4UY1R9IbWTYPCA54Y6FYLBYlJiYyUATZaBS+OwLy2GCnfpwLBp/Pd2LCwMnJyW98G84wzIa2f4HjrT6Z/0VwtOAb7+Avd+Nvsd4Ume8AAAAASUVORK5CYII=),auto');
    });
});


function adjust_local_video() {
    // changing local video behavior
    jQuery('video').each(function () {
        var video = jQuery(this); // Directly select the video element
        var src = video.find('source').attr('src');
        if (fifu_should_mute())
            video.prop('muted', true); // Set the muted property
        if (fifuVideoVars.fifu_autoplay_enabled)
            video.prop('autoplay', true); // Set the autoplay property
        if (fifuVideoVars.fifu_loop_enabled)
            video.prop('loop', true); // Set the loop property
    });
}

function should_autoplay() {
    if (src.includes('.wp.com')) {
        image_url = src.replace(/i\d.wp.com\//, '');
        same_url = fifuVideoVars.fifu_url && fifuVideoVars.fifu_url.split('?')[0] == image_url.split('?')[0];
    } else
        same_url = fifuVideoVars.fifu_url && fifuVideoVars.fifu_url == src;

    autoplay_single = fifuVideoVars.fifu_autoplay_enabled && same_url && !fifuVideoVars.fifu_is_front_page && !fifuVideoVars.fifu_is_home_or_shop;
    autoplay_front = fifuVideoVars.fifu_autoplay_front_enabled && fifuVideoVars.fifu_is_front_page;
    return autoplay_single || autoplay_front;
}

function fifu_should_mute() {
    return fifuVideoVars.fifu_is_mobile ? fifuVideoVars.fifu_video_mute_mobile_enabled : fifuVideoVars.fifu_video_mute_enabled;
}

const simpleHash = str => {
    let hash = 0;
    for (let i = 0; i < str.length; i++) {
        const char = str.charCodeAt(i);
        hash = (hash << 5) - hash + char;
        hash &= hash; // Convert to 32bit integer
    }
    return new Uint32Array([hash])[0].toString(36);
};

// bunny (animated preview)
jQuery(document).ready(function () {
    let srcType = fifuVideoVars.fifu_lazy ? 'data-src' : 'src';

    setTimeout(function () {
        jQuery(`img[${srcType}*="b-cdn.net"]`).each(function () {
            let src = jQuery(this).attr(srcType);

            jQuery(this).on('mouseover', function () {
                jQuery(this).attr('data-src', src.replace(/thumbnail.*jpg/, 'preview.webp'));
                jQuery(this).attr('src', src.replace(/thumbnail.*jpg/, 'preview.webp'));
            });

            jQuery(this).on('mouseout', function () {
                jQuery(this).attr('data-src', src);
                jQuery(this).attr('src', src);
            });
        });
    }, 1000);
});

document.addEventListener('facetwp-loaded', function () {
    replaceVideoThumb();
});

// for Prime Slider plugin
jQuery(document).ready(function () {
    var listItems = jQuery('ul.bdt-slideshow-items').find('li'); // Get all li elements inside ul.bdt-slideshow-nav

    if (!listItems)
        return;

    var observerConfig = {
        attributes: true,
        attributeFilter: ['class']
    };
    var callback = function (mutationsList, observer) {
        for (var mutation of mutationsList) {
            if (mutation.type === 'attributes') {
                if (jQuery(mutation.target).hasClass('bdt-active')) {
                    replaceVideoThumb();
                }
            }
        }
    };
    // Create an observer instance linked to the callback function for each li
    jQuery.each(listItems, function (index, li) {
        var observer = new MutationObserver(callback);
        observer.observe(li, observerConfig);
    });
});

// for WP Grid Builder plugin
jQuery(document).ready(function () {
    // Select the target element to observe
    var targetElement = document.querySelector('div.wp-grid-builder');

    if (!targetElement)
        return;

    // Create an observer instance with a callback to handle changes
    var observer = new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
            replaceVideoThumb();
        });
    });

    // Specify the observation options (e.g., attributes, child elements, etc.)
    var config = {
        attributes: true,
        childList: true,
        subtree: true
    };

    // Start observing the target element
    observer.observe(targetElement, config);
});
