jQuery(document).ready(function($) {
   'use strict';     

    if ( $('.woo_gridloop_btn form.cart .plus-minus-grid-cart').length ) {

        $('.qty-text').on('input', function() {
            $(this).closest('.woo_gridloop_btn').find('a.btn').attr('data-quantity', $(this).val());
        });

        $('.plus-minus-grid-cart .plus').on('click', function () {
            if ($(this).prev().val() < 100) {
                $(this).prev().val(+$(this).prev().val() + 1);
            }
            
            $(this).closest('.woo_gridloop_btn').find('a.btn').attr('data-quantity', $(this).closest('.woo_gridloop_btn').find('.qty-text').val());

            $('button.button').removeAttr("disabled");
        });
        

        $('.plus-minus-grid-cart .minus').on('click', function () {
            if ($(this).next().val() > 1) {
                if ($(this).next().val() > 1) $(this).next().val(+$(this).next().val() - 1);
            }
            
            $(this).closest('.woo_gridloop_btn').find('a.btn').attr('data-quantity', $(this).closest('.woo_gridloop_btn').find('.qty-text').val());
            
            $('button.button').removeAttr("disabled");
        });

        ajax_add_to_cart();
    }         

});