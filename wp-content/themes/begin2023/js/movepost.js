jQuery(function($) {
	$('.row-actions .trash a').click(function(event) {
		event.preventDefault();
		var url = new URL($(this).attr('href')),
		nonce = url.searchParams.get('_wpnonce'),
		row = $(this).closest('tr'),
		postID = url.searchParams.get('post'),
		postTitle = row.find('.row-title').text();

		row.css('background-color', '#ffafaf').fadeOut(300,
		function() {
			row.removeAttr('style').html('<td colspan="5">文章 <strong>' + postTitle + '</strong> 已移至回收站</td>').show();
		});

		$.ajax({
			method: 'POST',
			url: ajaxurl,
			data: {
				'action': 'moveposttotrash',
				'post_id': postID,
				'_wpnonce': nonce
			}
		});
	});
});