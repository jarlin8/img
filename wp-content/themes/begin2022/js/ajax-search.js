function search_data_w(){
	var keyword = jQuery( '#searchInput' ).val();
	if( keyword == "" ){
		jQuery( '#searchdata' ).html("");
	} else {
		jQuery.ajax({
			url: ajax_fetch.ajaxurl,
			type: 'post',
			data: { action: 'ajax_fetch', keyword: keyword },
			success: function( data ) {
				jQuery( '#searchdata' ).html( data );
				lazy();
				jQuery( '.ajax-search-not' ).show().delay(3000).fadeOut();
			}
		});
	}
}

function ajax_search_s(){
	var keyword = jQuery( '#wpsearchInput' ).val();
	if( keyword == "" ){
		jQuery( '#wpsearchdata' ).html("");
	} else {
		jQuery.ajax({
			url: ajax_search.ajaxurl,
			type: 'post',
			data: { action: 'ajax_search', keyword: keyword },
			success: function( data ) {
				jQuery( '#wpsearchdata' ).html( data );
				lazy();
			}
		});
	}
}