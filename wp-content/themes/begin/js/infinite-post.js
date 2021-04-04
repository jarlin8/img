jQuery(document).ready(function($){
	var pages_n = Ajaxpost.pages_n;
	var ias = $.ias({
		container: "#main",
		item: "article",
		pagination: "#nav-below",
		next: "#nav-below .nav-previous a",
	});
	ias.extension(new IASTriggerExtension({
		text: '<i class="be be-circledown"></i>',
		offset: pages_n,
	}));
	ias.extension(new IASSpinnerExtension());
	ias.extension(new IASNoneLeftExtension({
		text: '已是最后',
	}));
	ias.on('rendered',
	function(items) {
		$("img").lazyload({
			effect: "fadeIn",
			failure_limit: 70
		});
 		$('.lazy a').Lazy({
			effect: 'fadeIn',
			effectTime: 300
		});
		$(".meta-author").mouseover(function() {
			$(this).children(".meta-author-box").show();
		})
		$(".meta-author").mouseout(function() {
			$(this).children(".meta-author-box").hide();
		});
	})
});