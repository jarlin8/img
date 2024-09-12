jQuery(document).on('click', '#pagination a:not(.noajx)',function() {
	var _this = jQuery(this);
	var next = _this.attr("href").replace('?ajx=container', '');
	var docH = jQuery(document).height();
	var pagination = '#pagination';
	var pagenav = '#main .page-navigator';
	jQuery(pagination).hide();
	jQuery("#loadmore").show();
	jQuery.ajax({
		url: next,
		beforeSend: function() {
		},
		success: function(data) {
			jQuery('.my-post').append(jQuery(data).find('.my-post tfoot tr'));
			jQuery(pagination).html(jQuery(data).find(pagination).html());
			jQuery(pagenav).html(jQuery(data).find(pagenav).html());
			nextHref = jQuery(data).find("#pagination a").attr("href");
			if (nextHref != undefined) {
				jQuery(pagination).show();
				jQuery("#loadmore").hide();
				jQuery("#pagination a").attr("href", nextHref);
			} else {
				jQuery(pagination).show();
				jQuery("#loadmore").hide();
				jQuery(pagination).html('');
			}
		},
		complete: function() {
		},
		error: function() {
			location.href = next;
		}
	});
	return false;
});