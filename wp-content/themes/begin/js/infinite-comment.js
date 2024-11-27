jQuery(document).ready(function($){
	var ias = $.ias({
		container: "#comments",
		item: ".comment-list",
		pagination: ".scroll-links",
		next: ".scroll-links .nav-previous a",
	});
	ias.extension(new IASTriggerExtension({
		text: '<i class="be be-circledown"></i>',
		offset: -20,
	}));
	ias.extension(new IASSpinnerExtension());
	ias.extension(new IASNoneLeftExtension({
		text: '',
	}));
	ias.on('rendered',
	function(items) {
		$("img").lazyload({
			effect: "fadeIn",
			failure_limit: 10
		});
	});
});