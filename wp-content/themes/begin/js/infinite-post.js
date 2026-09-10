jQuery(document).ready(function($){
	var pages_n = Ajaxpost.pages_n;
	var ias = $.ias({
		container: ".be-main",
		item: ".scl",
		pagination: "#nav-below",
		next: "#nav-below .nav-previous a",
	});
	ias.extension(new IASTriggerExtension({
		text: '<i class="be be-circledown"></i>',
		offset: pages_n,
	}));
	ias.extension(new IASSpinnerExtension());
	ias.extension(new IASNoneLeftExtension({
		text: '',
		html: '<a>{text}</a>',
	}));
	ias.on('rendered',
	function(items) {
		$(".load img").lazyload({
			effect: "fadeIn",
			failure_limit: 70
		});

		$(".load").removeClass('load');

 		$('.lazy a').Lazy({
			effect: 'fadeIn',
			effectTime: 300
		});

		var fall_width = fallwidth.fall_width;
		$('.fall').wookmark({
			itemWidth: 90,
			autoResize: true,
			container: $('.post-fall'),
			offset: 10,
			outerOffset: 0,
			flexibleWidth: fall_width
		});

		$('.fall').removeClass('fall-off');
		$('.fall').addClass('fall-on');

		$('.fancy-box').fancybox({});

		$(".meta-author").mouseover(function() {
			$(this).children(".meta-author-box").show();
		})

		$(".meta-author").mouseout(function() {
			$(this).children(".meta-author-box").hide();
		});
	})
});