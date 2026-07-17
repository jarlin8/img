var fifu_slider;

(function ($) {
    $(document).ready(function () {
        fifu_slider = fifu_load_slider();
    });
})(jQuery);

function fifu_load_slider() {
    return jQuery(".gallery.list-unstyled:not(.already-loaded)").lightSlider({
        gallery: true,
        mode: fifuSliderVars.fifu_slider_vertical ? 'slide' : 'fade',
        item: 1,
        thumbItem: 5,
        slideMargin: 0,
        adaptiveHeight: fifuSliderVars.fifu_is_product && fifuSliderVars.fifu_adaptive_height && !fifuSliderVars.fifu_slider_vertical ? true : false,
        speed: fifuSliderVars.fifu_slider_vertical ? 0 : fifuSliderVars.fifu_slider_speed,
        auto: fifuSliderVars.fifu_slider_auto,
        loop: fifuSliderVars.fifu_slider_vertical ? false : true,
        freeMove: true,
        enableDrag: false,
        enableTouch: true,
        pager: fifuSliderVars.fifu_slider_thumb, // true can cause lazy load problems
        vertical: fifuSliderVars.fifu_slider_vertical ? true : false,
        verticalHeight: fifuSliderVars.fifu_is_product ? '350' : 'auto',
        vThumbWidth: 50,
        slideEndAnimation: false,
        pause: fifuSliderVars.fifu_slider_pause,
        controls: fifuSliderVars.fifu_slider_ctrl && !(fifuSliderVars.fifu_is_product && fifuSliderVars.fifu_slider_vertical),
        pauseOnHover: fifuSliderVars.fifu_slider_stop,
        prevHtml: '<img class="fifu-arrow" src="' + fifuSliderVars.fifu_slider_left + '"/>',
        nextHtml: '<img class="fifu-arrow" src="' + fifuSliderVars.fifu_slider_right + '"/>',
        onSliderLoad: function (el) {
            // el == this == ul
            jQuery(el).removeClass("cS-hidden");

            // avoid duplicated slider elements after ajax calls
            jQuery(el).addClass("already-loaded");

            // use local video instead of slider
            if (el.find('video').length) {
                // hide thumbnails gallery
                if (el.getTotalSlideCount() == 1)
                    jQuery(el).closest('.fifu-slider').find('ul.lSPager.lSGallery').hide();
                return;
            }

            // use 1st image instead of slider
            if (!el.find('img').length)
                return;
            if (el[0].clientWidth < 175 || (el.find('img')[0].clientWidth > 0 && el.find('img')[0].clientWidth < 175)) {
                imgs = el.find('img');
                firstImage = imgs[0];
                el.parents('div.fifu-slider').replaceWith(firstImage);
                jQuery(firstImage).css('height', '');
                jQuery(firstImage).css('width', '');
                if (fifuSliderVars.fifu_lazy)
                    jQuery(firstImage).addClass('lazyload');
                return false;
            }

            // zoom
            if (fifuSliderVars.fifu_is_product && !fifuSliderVars.fifu_is_mobile && fifuSliderVars.fifu_wc_zoom) {
                jQuery(el).find('li').each(function (index) {
                    if (jQuery(this).attr('data-srcset')) {
                        urls = jQuery(this).attr('data-srcset').split(', ');
                        last = urls[urls.length - 1].split(' ')[0];
                        if (!last.includes('video-thumb') && (!fifuSliderVars.fifu_video || !is_video_img(last))) {
                            jQuery(this).zoom({url: last});
                        }
                    } else if (jQuery(this).attr('data-src')) {
                        url = jQuery(this).attr('data-src');
                        if (!url.includes('video-thumb') && (!fifuSliderVars.fifu_video || !is_video_img(url)))
                            jQuery(this).zoom({url: url});
                    }
                });
            }

            // add slider gallery
            setTimeout(() => {
                if (fifuSliderVars.fifu_slider_gallery) {
                    lg = el[0];

                    // settings
                    const inlineGallery = lightGallery(lg, {
                        hash: true,
                        closable: true,
                        showMaximizeIcon: false,
                        slideDelay: 400,
                        plugins: [lgThumbnail, lgVideo],
                        download: false,
                        zoom: true,
                        counter: true,
                        thumbWidth: 60,
                        thumbHeight: "40px",
                        thumbMargin: 10,
                        mobileSettings: {showCloseIcon: true},
                    });

                    // events
                    lg.addEventListener('lgAfterOpen', ($) => {
                        // lazy load for woocommerce gallery
                        urls = [];
                        jQuery(el).find('li').each(function (index) {
                            urls.push(jQuery(this).attr('data-thumb'));
                        });
                        // add thumbnails to the gallery
                        jQuery('div.lg-show').find('div.lg-thumb-item').find('img').each(function (index) {
                            if (urls[index].includes('.fifu.app'))
                                jQuery(this).attr('data-src', urls[index]);
                            else if (urls[index].includes('.wp.com')) {
                                aux = urls[index].split('?')[0];
                                jQuery(this).attr('src', `${aux}?w=100&resize=100`);
                            } else
                                jQuery(this).attr('src', urls[index]);
                        });

                        // add unsupported videos to the product gallery
                        add_videos_to_the_gallery();
                    });

                    lg.addEventListener('lgAfterAppendSlide', () => {
                        // onerror for slider gallery
                        if (fifuSliderVars.fifu_error_url) {
                            jQuery('div.lg').find('img').each(function (index) {
                                jQuery(this).attr('onerror', "this.src='" + fifuSliderVars.fifu_error_url + "'");
                            });
                        }
                    });
                }
            }, 1500);

            // thumbnail (click)
            jQuery('ul.lSPager li a img').on('click', function () {
                if (fifuSliderVars.fifu_lazy) {
                    src = jQuery(this).attr('src');
                    // for jetpack
                    if (src.includes('.wp.com'))
                        src = src.split('?')[0];
                    fifu_add_lazyload(jQuery('img[data-src^="' + src + '"]')[0]);
                }
            });

            // fix elementor
            jQuery("article.elementor-portfolio-item > a.elementor-post__thumbnail__link > div.elementor-post__thumbnail > div.fifu-slider").parent().parent().attr("class", "");
            jQuery("div.elementor-post__thumbnail > div.fifu-slider").parent().attr("class", "");

            // external counter
            if (fifuSliderVars.fifu_slider_counter)
                jQuery(el).closest('.fifu-slider').find('#counter-slider').text(el.getCurrentSlideCount() + ' / ' + el.getTotalSlideCount());

            // hide thumbnails gallery
            if (el.getTotalSlideCount() == 1)
                jQuery(el).closest('.fifu-slider').find('ul.lSPager.lSGallery').hide();


            if (fifuSliderVars.fifu_is_product) {
                if (fifuSliderVars.fifu_slider_vertical) {
                    jQuery('div.fifu-woo-gallery img').each(function (index) {
                        jQuery(this).css('max-height', '350px'); // verticalHeight
                        jQuery(this).css('object-fit', 'fit');
                        jQuery(this).parent().css('text-align', 'center');
                        jQuery(this).css('display', 'inline');
                        jQuery(this).css('vertical-align', 'middle');
                    });
                } else {
                    // max-height (for images with large height but small width)
                    jQuery('div.fifu-woo-gallery ul').each(function (index) {
                        jQuery(this).css('max-height', '650px');
                    });
                    jQuery('div.fifu-woo-gallery li').each(function (index) {
                        jQuery(this).css('max-height', '650px');
                        jQuery(this).css('text-align', 'center');
                    });
                    jQuery('ul.fifu-product-gallery img').each(function (index) {
                        jQuery(this).css('max-height', '650px');
                        jQuery(this).css('object-fit', 'contain');
                        if (!fifuSliderVars.fifu_adaptive_height)
                            jQuery(this).css('vertical-align', 'middle');
                    });
                    jQuery('div.fifu-woo-gallery').css('z-index', '8');
                }
            }
        },
        onBeforeStart: function (el) {
        },
        onBeforeNextSlide: function (el) {
            if (fifuSliderVars.fifu_lazy) {
                jQuery(el).find('li.lslide.active').each(function (index) {
                    fifu_add_lazyload(jQuery(this).next().find('img'))
                    fifu_add_lazyload(jQuery(this).next().next().find('img'))
                    fifu_add_lazyload(jQuery(this).next().next().next().find('img'))
                });
            }
        },
        onBeforePrevSlide: function (el) {
            if (fifuSliderVars.fifu_lazy) {
                jQuery(el).find('li.lslide').each(function (index) {
                    if (jQuery(this).hasClass('active')) {
                        if (index == 0)
                            fifu_add_lazyload(jQuery(el).find('li.lslide').last().find('img'));
                        else {
                            fifu_add_lazyload(jQuery(this).prev().find('img'));
                            fifu_add_lazyload(jQuery(this).prev().prev().find('img'));
                            fifu_add_lazyload(jQuery(this).prev().prev().prev().find('img'));
                        }
                    }
                });
            }
        },
        onBeforeSlide: function (el) {
            // external counter
            if (fifuSliderVars.fifu_slider_counter)
                jQuery(el).closest('.fifu-slider').find('#counter-slider').text(el.getCurrentSlideCount() + ' / ' + el.getTotalSlideCount());

            // add unsupported videos to the product gallery
            add_videos_to_the_gallery();
        },
    });
}

function add_videos_to_the_gallery() {
    if (fifuSliderVars.fifu_video && typeof fifuVideoThumbVars !== 'undefined') {
        jQuery('div.lg-item').find('img').each(function (index) {
            videoThumb = jQuery(this);
            src = videoThumb.attr('src');
            if (is_googledrive_video_img(src) || is_mega_video_img(src)) {
                videoThumb.parent().find('.lg-video-play-button').remove();
                videoSrc = fifuVideoThumbVars['thumbs'][src];
                iframeId = simpleHash(videoSrc);
                $iframe_class = 'fifu_iframe';
                $iframe_class += fifuVideoVars.fifu_lazy ? ' lazyload' : '';
                video = '<div class="fifu_wrapper"><div class="fifu_h_iframe" style="position:relative"><img class="fifu_ratio" src="' + src + '"/><iframe id="' + iframeId + '" class="' + $iframe_class + '" ' + fifuVideoVars.fifu_lazy_src_type + '"' + videoSrc + '" allowfullscreen frameborder="0"></iframe></div></div>';
                videoThumb.replaceWith(video);
            }
        });
    }
}
