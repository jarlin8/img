function captcha_script() {
	jQuery(document).ready(function($) {
		if ($.cookie("BE_captcha")) {
			var count = $.cookie("BE_captcha");
			if (count > 0) {
				$('.be-email-code').addClass("disabled");
			}
		}

		if ($('.be-email-code').hasClass("disabled")) {
			var countdown = 60;
			settime()
			function settime() {
				if (countdown == 0) {
					$('.be-email-code').removeClass("disabled");
					$('.be-email-code').html('重新获取验证码');
					$.cookie("BE_captcha", "0");
				} else {
					$('.be-email-code').addClass("disabled");
					$('.be-email-code').html('重新获取 -' + countdown + '');
					$.cookie("BE_captcha", "1");
					countdown--;
					setTimeout(function() {
						settime()
					},1000)
				}
			}
		}

		$(".login-overlay .user_name").keyup(function(){
			user_name();
		});
		function user_name() {
			$(".reg-page-main .user_name,.ajax-login-widget .user_name").val($('.login-overlay .user_name').val());
		}

		$(".login-overlay .user_email").keyup(function(){
			user_email();
		});
		function user_email() {
			$(".reg-page-main .user_email,.ajax-login-widget .user_email").val($('.login-overlay .user_email').val());
		}

		$(".login-overlay .user_pwd1").keyup(function(){
			user_pwd1();
		});
		function user_pwd1() {
			$(".reg-page-main .user_pwd1,.ajax-login-widget .user_pwd1").val($('.login-overlay .user_pwd1').val());
		}

		$(".login-overlay .user_pwd2").keyup(function(){
			user_pwd2();
		});
		function user_pwd2() {
			$(".reg-page-main .user_pwd2,.ajax-login-widget .user_pwd2").val($('.login-overlay .user_pwd2').val());
		}

		$('.be-email-code').bind('click', function() {
			var captcha = $(this);
			var input_n = $('.user_name, #user_login').val();
			var input_p1 = $('.user_pwd1').val();
			var input_p2 = $('.user_pwd2').val();
			if (captcha.hasClass("disabled")) {
				$('.reg-captcha-message').html('<div class="captcha-result fd" role="alert">您操作太快了</div>').addClass('alert-result animated').show().delay(3000).fadeOut();
			} else if (!input_n) {
				$('.reg-captcha-message').html('<div class="captcha-result fd" role="alert">请填写用户名</div>').addClass('alert-result animated').show().delay(3000).fadeOut();
			} else if (!eer_username($(".user_name").val())) {
				$('.reg-captcha-message').html('<div class="captcha-result fd" role="alert">用户名4-16位，不能含汉字及特殊字符</div>').addClass('alert-result animated').show().delay(3000).fadeOut();
			} else if (!eer_is_mail($(".user_email,#user_email").val())) {
				$('.reg-captcha-message').html('<div class="captcha-result fd" role="alert">请输入正确的邮箱</div>').addClass('alert-result animated').show().delay(3000).fadeOut();
			} else if (!input_p1) {
				$('.reg-captcha-message').html('<div class="captcha-result fd" role="alert">请填写密码</div>').addClass('alert-result animated').show().delay(3000).fadeOut();
			} else if ($(".user_pwd1").val().length < 6)  {
				$('.reg-captcha-message').html('<div class="captcha-result fd" role="alert">密码长度至少6位</div>').addClass('alert-result animated').show().delay(3000).fadeOut();
			} else if (input_p1 !== input_p2) {
				$('.reg-captcha-message').html('<div class="captcha-result fd" role="alert">两次输入的密码不一致</div>').addClass('alert-result animated').show().delay(3000).fadeOut();
			} else {
				captcha.addClass("disabled");
				captcha.html("发送中...");
				$.post(_betip.uri + 'mail-captcha.php?' + Math.random(), {
					action: "eer_captcha",
					email: $(".user_email,#user_email").val(),
					user_name: $(".user_name").val()
				},
				function(data) {
					if ($.trim(data) == "1") {
						$('.reg-captcha-message').html('<div class="captcha-result captcha-result-success fd" role="alert">已发送验证码至邮箱！</div>').addClass('alert-result animated').show().delay(6000).fadeOut();
						var countdown = 60;
						settime()
						function settime() {
							if (countdown == 0) {
								captcha.removeClass("disabled");
								captcha.html("重新获取验证码");
								countdown = 60;
								$.cookie("BE_captcha", "0");
								return;
							} else {
								captcha.addClass("disabled");
								captcha.html("重新获取 -" + countdown + "");
								$.cookie("BE_captcha", "1");
								countdown--;
							}
							setTimeout(function() {
								settime()
							},
							1000)
						}
					} else if ($.trim(data) == "3") {
						$('.reg-captcha-message').html('<div class="captcha-result fd" role="alert">该用户名被占用，请换一个</div>').addClass('alert-result').show().delay(3000).fadeOut();
						captcha.html("获取邮件验证码");
						captcha.removeClass("disabled");
					} else if ($.trim(data) == "2") {
						$('.reg-captcha-message').html('<div class="captcha-result fd" role="alert">该邮箱已存在，请换一个</div>').addClass('alert-result').show().delay(3000).fadeOut();
						captcha.html("获取邮件验证码");
						captcha.removeClass("disabled");
					} else {
						$('.reg-captcha-message').html('<div class="captcha-result fd" role="alert">验证码发送失败，请稍后重试</div>').addClass('alert-result animated').show().delay(3000).fadeOut();
						captcha.html("获取邮件验证码");
						captcha.removeClass("disabled");
					}
				});
			}
		});
	})

	function eer_is_mail(str) {
		return /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/.test(str)
	}

	function eer_username(str) {
		return /^[a-zA-Z0-9_]{4,16}$/.test(str)
	}
}
captcha_script();