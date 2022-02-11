jQuery(document).ready(function($) {
	$('.of-color').wpColorPicker();

	$('.of-radio-img-img').click(function() {
		$(this).parent().parent().find('.of-radio-img-img').removeClass('of-radio-img-selected');
		$(this).addClass('of-radio-img-selected');
	});

	$('.of-radio-img-label').hide();
	$('.of-radio-img-img').show();
	$('.of-radio-img-radio').hide();

	if ($('.nav-tab-wrapper').length > 0) {
		options_framework_tabs();
	}

	function options_framework_tabs() {
		var $group = $('.group'),
		$navtabs = $('.nav-tab-wrapper a'),
		active_tab = '';

		$group.hide();

		if (typeof(localStorage) != 'undefined') {
			active_tab = localStorage.getItem('active_tab');
		}

		if (active_tab != '' && $(active_tab).length) {
			$(active_tab).fadeIn();
			$(active_tab + '-tab').addClass('nav-tab-active');
		} else {
			$('.group:first').fadeIn();
			$('.nav-tab-wrapper a:first').addClass('nav-tab-active');
		}

		$navtabs.click(function(e) {

			e.preventDefault();

			$navtabs.removeClass('nav-tab-active');

			$(this).addClass('nav-tab-active').blur();

			if (typeof(localStorage) != 'undefined') {
				localStorage.setItem('active_tab', $(this).attr('href'));
			}

			var selected = $(this).attr('href');

			$group.hide();
			$(selected).fadeIn();

		});
	}

	$('.group-heading').click(function(){
		$(this).next('.group-content').fadeToggle(300);
		$(this).children('.heading-arrow').toggleClass("cover");
		$(this).children('.heading-arrow').toggleClass("show");
	});

	$('.all_expand_all,#show_box').click(function(){
		$('.group-content').fadeToggle(300);
		$('.heading-arrow').toggleClass("cover");
		$('.heading-arrow').toggleClass("show");
	});

	$('.be-nav-menu').click(function() {
		$('.be-nav-tab').fadeToggle(400);
	});

	if ( $(window).width() < 783 ) {
		$('.nav-tab').click(function() {
			$('.be-nav-tab').hide(400);
		});
	}

	$('.to-top').click(function() {
		$('html,body').animate({
			scrollTop: $('.wp-toolbar').offset().top
		},
		500);
	});

	$('.options-caid').click(function(){
		$(this).next('.op-id-list').fadeToggle();
		$('.special-id-list').hide();
	});

	$('.special-id').click(function(){
		$(this).next('.op-id-list').fadeToggle();
		$('.catid-list').hide();
	});

	$(document).on('scroll', function() {
		var distanceFromBottom = Math.floor($(document).height() - $(document).scrollTop() - $(window).height());
		if(distanceFromBottom < 500) {
			$('.to-top').fadeIn("slow");
		}else{
			$('.to-top').fadeOut("slow");
		}
	});

	$('#section-qq_id_url a, #section-weibo_key_url a, #section-keyword_link_settings a, #section-sitemap_xml a, #section-sitemap_txt a, #section-iconfont_cn a, #section-setup_views a, #section-add_invitation a').attr({target: "_blank"});

});