var hasChanged = false;
function confirmExit() {
	var mce = typeof(tinyMCE) != "undefined" ? tinyMCE.activeEditor: false;
	if (hasChanged || (mce && !mce.isHidden() && mce.isDirty())) {
		return inf_messages.unsaved_changes_warning
	}
}
window.onbeforeunload = confirmExit;
function substr_count(mainString, subString) {
	var re = new RegExp(subString, "g");
	if (!mainString.match(re) || !mainString || !subString) {
		return 0
	}
	var count = mainString.match(re);
	return count.length
}
function str_word_count(s) {
	if (!s.length) {
		return 0
	}
	s = s.replace(/(^\s*)|(\s*$)/gi, "");
	s = s.replace(/[ ]{2,}/gi, " ");
	s = s.replace(/\n /, "\n");
	return s.split(" ").length
}
function countTags(s) {
	if (!s.length) {
		return 0
	}
	return s.split(",").length
}

function post_has_errors(title, content, category, tags, fimg) {
	var error_string = "";
	if (bet_rules.check_required == false) {
		return false
	}
	if ((bet_rules.min_words_title != 0 && title === "") || category == -1 || (bet_rules.min_tags != 0 && tags === "")) {
		error_string = inf_messages.required_field_error + ""
	}
	var stripped_content = content.replace(/(<([^>]+)>)/ig, "");
	if (title != "" && str_word_count(title) < bet_rules.min_words_title) {
		error_string += inf_messages.title_short_error + ""
	}
	if (content != "" && str_word_count(title) > bet_rules.max_words_title) {
		error_string += inf_messages.title_long_error + ""
	}
	if (content != "" && str_word_count(stripped_content) < bet_rules.min_words_content) {
		error_string += inf_messages.article_short_error + ""
	}
	if (str_word_count(stripped_content) > bet_rules.max_words_content) {
		error_string += inf_messages.article_long_error + ""
	}
	if (substr_count(content, "</a>") > bet_rules.max_links) {
		error_string += inf_messages.too_many_article_links_error + ""
	}
	if (tags != "" && countTags(tags) < bet_rules.min_tags) {
		error_string += inf_messages.too_few_tags_error + ""
	}
	if (countTags(tags) > bet_rules.max_tags) {
		error_string += inf_messages.too_many_tags_error + ""
	}
	if (bet_rules.thumbnail_required && bet_rules.thumbnail_required == "true" && fimg == -1) {
		error_string += inf_messages.featured_image_error + ""
	}
	if (error_string == "") {
		return false
	} else {
		var errorMessage = inf_messages.general_form_error + error_string;
		setTimeout(function() {
			var errorElement = document.getElementById('bet-message');
			if (errorElement) {
				errorElement.style.display = 'none';
			}
		}, 3000);
		return errorMessage;
	}
}
jQuery(document).ready(function($) {
	$("input, textarea, #bet-post-content").keydown(function() {
		hasChanged = true
	});
	$("select").change(function() {
		hasChanged = true
	});
	$("td.post-delete a").click(function(event) {
		var id = $(this).siblings(".post-id").first().val();
		var nonce = $("#betnonce_delete").val();
		var loadimg = $(this).siblings(".bet-loading-img").first();
		var row = $(this).closest(".bet-row");
		var message_box = $("#bet-message");
		var post_count = $("#bet-posts .count");
		var confirmation = confirm(inf_messages.confirmation_message);
		if (!confirmation) {
			return
		}
		$(this).hide();
		loadimg.show().css({
			"float": "none",
			"box-shadow": "none"
		});
		$.ajax({
			type: "POST",
			url: betajaxhandler.ajaxurl,
			data: {
				action: "inf_delete_posts",
				post_id: id,
				delete_nonce: nonce
			},
			success: function(data, textStatus, XMLHttpRequest) {
				var arr = $.parseJSON(data);
				message_box.html("");
				if (arr.success) {
					row.hide();
					message_box.show().addClass("success").append(arr.message);
					post_count.html(Number(post_count.html()) - 1)
				} else {
					message_box.show().addClass("warning").append(arr.message)
				}
				if (message_box.offset().top < $(window).scrollTop()) {
					$("html, body").animate({
						scrollTop: message_box.offset().top - 10
					},
					"slow")
				}
			},
			error: function(MLHttpRequest, textStatus, errorThrown) {
				alert(errorThrown)
			}
		});
		event.preventDefault()
	});
	$("#bet-submit-post.active-btn").on("click",
	function() {
		tinyMCE.triggerSave();
		var title = $("#bet-post-title").val();
		var info1 = $("#bet-info-1").val();
		var info2 = $("#bet-info-2").val();
		var info3 = $("#bet-info-3").val();
		var info4 = $("#bet-info-4").val();
		var info5 = $("#bet-info-5").val();
		var info6 = $("#bet-info-6").val();
		var info7 = $("#bet-info-7").val();
		var info8 = $("#bet-info-8").val();
		var info9 = $("#bet-info-9").val();
		var info10 = $("#bet-info-10").val();
		var info11 = $("#bet-info-11").val();
		var info12 = $("#bet-info-12").val();
		var info13 = $("#bet-info-13").val();
		var info14 = $("#bet-info-14").val();
		var info15 = $("#bet-info-15").val();
		var info16 = $("#bet-info-16").val();
		var info17 = $("#bet-info-17").val();
		var info18 = $("#bet-info-18").val();
		var info19 = $("#bet-info-19").val();
		var info20 = $("#bet-info-20").val();
		var content = $("#bet-post-content").val();
		var category = $("#bet-category").val();
		var tags = $("#bet-tags").val();
		var pid = $("#bet-post-id").val();
		var fimg = $("#bet-featured-image-id").val();
		var nonce = $("#betnonce").val();
		var message_box = $("#bet-message");
		var form_container = $("#bet-new-post");
		var submit_btn = $("#bet-submit-post");
		var load_img = $("img.bet-loading-img");
		var submission_form = $("#bet-submission-form");
		var post_id_input = $("#bet-post-id");
		var errors = post_has_errors(title, content, category, tags, fimg);
		if (errors) {
			if (form_container.offset().top < $(window).scrollTop()) {
				$("html, body").animate({
					scrollTop: form_container.offset().top - 10
				},
				"slow")
			}
			message_box.removeClass("success").addClass("warning").html("").show().append(errors);
			return
		}
		load_img.show();
		submit_btn.attr("disabled", true).removeClass("active-btn").addClass("passive-btn");
		$.ajaxSetup({
			cache: false
		});
		$.ajax({
			type: "POST",
			url: betajaxhandler.ajaxurl,
			data: {
				action: "inf_process_form_input",
				post_title: title,
				post_content: content,
				post_category: category,
				post_tags: tags,
				post_id: pid,
				featured_img: fimg,
				info_1: info1,
				info_2: info2,
				info_3: info3,
				info_4: info4,
				info_5: info5,
				info_6: info6,
				info_7: info7,
				info_8: info8,
				info_9: info9,
				info_10: info10,
				info_11: info11,
				info_14: info12,
				info_13: info13,
				info_14: info14,
				info_15: info15,
				info_16: info16,
				info_17: info17,
				info_18: info18,
				info_19: info19,
				info_20: info20,
				post_nonce: nonce
			},
			success: function(data, textStatus, XMLHttpRequest) {
				hasChanged = false;
				var arr = $.parseJSON(data);
				if (arr.success) {
					submission_form.hide();
					post_id_input.val(arr.post_id);
					message_box.removeClass("warning").addClass("success")
				} else {
					message_box.removeClass("success").addClass("warning")
				}
				message_box.html("").append(arr.message).show();
				/* 
				if (form_container.offset().top < $(window).scrollTop()) {
					$("html, body").animate({
						scrollTop: form_container.offset().top - 10
					},
					"slow")
				}
				*/
				$("html, body").animate({
					scrollTop: 0
				}, "slow");

				load_img.hide();
				submit_btn.attr("disabled", false).removeClass("passive-btn").addClass("active-btn")
			},
			error: function(MLHttpRequest, textStatus, errorThrown) {
				alert(errorThrown)
			}
		})
	});
	$("body").on("click", "#bet-continue-editing",
	function(e) {
		$("#bet-message").hide();
		$("#bet-submission-form").show();
		e.preventDefault()
	});

});

function closetou() {
	host = document.referrer;
	window.location.href = host;
	window.history.back();
	window.opener = null;
	window.open('', '_self');
	window.close();
	WeixinJSBridge.call('closeWindow');
}