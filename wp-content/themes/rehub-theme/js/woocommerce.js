/*
   Author: Igor Sunzharovskyi
   Author URI: https://wpsoul.com
*/

jQuery(document).on( 'change', '.rh_woo_drop_cat', function(e) {
   var catid = jQuery(this).val(),
   inputField = jQuery(this).parent().find('.re-ajax-search');
   if(inputField.length){
    inputField.attr("data-catid", catid);
    var inputValue = inputField.val();
        if(inputValue !=''){
            re_ajax_cache.remove(inputValue);
            re_ajax_search.do_ajax_call(inputField);
        }
   }
});
jQuery( '.variations_form' ).on( 'woocommerce_update_variation_values', function () {
    var rhswatches = jQuery('.rh-var-selector');
    rhswatches.find('.rh-var-label').removeClass('rhhidden');
    rhswatches.each(function(){
        var variationselect = jQuery(this).prev();
        jQuery(this).find('.rh-var-label').each(function(){
            if (variationselect.find('option[value="'+ jQuery(this).attr("data-value") +'"]').length <= 0) {
                jQuery(this).addClass('rhhidden');
            }
        });
    });
});
jQuery( '.variations_form' ).on( 'click', '.reset_variations', function () {
    var rhswatches = jQuery('.rh-var-selector');
    rhswatches.find('.rh-var-label').removeClass('rhhidden');
    rhswatches.each(function(){
        jQuery(this).find('.rh-var-input').each(function(){
            jQuery(this).prop( "checked", false );
        });
    });
});
jQuery(document).on("mouseenter", "#main_header .rh_woocartmenu_cell", function(){
    if(typeof wc_cart_fragments_params === 'undefined'){
        return false;
    }
    var widgetCartContent = jQuery(this).find(".widget_shopping_cart");
    widgetCartContent.addClass("loaded re_loadingbefore");
    jQuery.ajax({
        type: "post",
        url: wc_cart_fragments_params.wc_ajax_url.toString().replace('%%endpoint%%', 'get_refreshed_fragments'),
        data: {
            time: new Date().getTime()
        },
        timeout: wc_cart_fragments_params.request_timeout
    }).done(function(data){
        if (data && data.fragments) {
            widgetCartContent.html(data.fragments["div.widget_shopping_cart_content"]);
            widgetCartContent.removeClass("re_loadingbefore");
        }                                   
    });          
})
jQuery(document).on("mouseleave", "#main_header .rh_woocartmenu_cell", function(){
    var widgetCartContent = jQuery(this).find(".widget_shopping_cart");
    widgetCartContent.removeClass("loaded");
    widgetCartContent.html("");
});

jQuery(document).ready(function($) {
   'use strict';

    $('.rhniceselect, .woocommerce-ordering .orderby').niceSelect();

    $(document).on('added_to_cart',  function(e, fragments, cart_hash, $button){
        if ($button) {
            var cartNotice =wc_add_to_cart_params.i18n_added_to_cart +' <a href="' + wc_add_to_cart_params.cart_url +'" class="added_to_cart wc-forward" title="'+ wc_add_to_cart_params.i18n_view_cart +'">'+ wc_add_to_cart_params.i18n_view_cart +'</a>';
            $button.next('.added_to_cart').remove();
            $.simplyToast(cartNotice, 'success', {delay: 6000});
        }
    });

    if($('#section-woo-ce-pricehistory').length > 0){
      if($('#nopricehsection').length > 0){
         $('#section-woo-ce-pricehistory').remove();
         $('#tab-title-woo-ce-pricehistory').remove();
      }
    }                   

}); //END Document.ready