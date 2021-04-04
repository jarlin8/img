jQuery(function($){
	count = 0;
	all_texts = new Array();
	$('.ajax-content-box, .ajax-text-widget').each(function(){
		$widget = $(this);
		$widget.attr('id','ajax-content'+count);
		var text = $widget.attr('data-text');
		
		if( text !== null && typeof text !== 'undefined' )
			all_texts[count] = text;

		if( $('.ajax-content-box, .ajax-text-widget').length == count+1 ){
			$.post(ajax_content.ajax_url, { action: 'ajax_content', data: all_texts }, function(response){
				response = $.parseJSON(response);
				for( i=0; i <= response.length; i++ ){
					$('#ajax-content'+i).html(response[i]);
				}
			});
		}
		count++;
	});
});