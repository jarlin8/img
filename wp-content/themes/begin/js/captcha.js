function SliderCaptcha() {
	(function($) {
		'use strict';

		var SliderCaptcha = function(element, options) {
			this.$element = $(element);
			this.options = $.extend({},
			SliderCaptcha.DEFAULTS, options);
			this.$element.css({
				'position': 'relative',
				'width': this.options.width + 'px',
				'margin': '0 auto'
			});
			this.init();
		};

		SliderCaptcha.VERSION = '1.0';
		SliderCaptcha.Author = 'argo@163.com';
		SliderCaptcha.DEFAULTS = {
			width: 280,
			// canvas width
			height: 155,
			// canvas height
			PI: Math.PI,
			sliderL: 42,
			// Slider side length
			sliderR: 9,
			// Slider radius
			offset: 5,
			// Fault tolerance
			loadingText: 'Loading...',
			failedText: 'Try It Again',
			barText: 'Drag to solve the Puzzle',
			repeatIcon: '',
			maxLoadCount: 3,
			localImages: function() {
				return captcha_images[Math.floor(Math.random() * captcha_images.length)];
			},
			setSrc: function() { // uses local images instead
				return captcha_images[Math.floor(Math.random() * captcha_images.length)];
			},
			verify: function(arr, url) {
				var ret = false;
				$.ajax({
					url: url,
					data: JSON.stringify(arr),
					async: false,
					cache: false,
					type: 'POST',
					contentType: 'application/json',
					dataType: 'json',
					success: function(result) {
						ret = result;
					}
				});
				return ret;
			},
			remoteUrl: null
		};

		function Plugin(option) {
			return this.each(function() {
				var $this = $(this);
				var data = $this.data('lgb.SliderCaptcha');
				var options = typeof option === 'object' && option;

				if (data && !/reset/.test(option)) return;
				if (!data) $this.data('lgb.SliderCaptcha', data = new SliderCaptcha(this, options));
				if (typeof option === 'string') data[option]();
			});
		}

		$.fn.sliderCaptcha = Plugin;
		$.fn.sliderCaptcha.Constructor = SliderCaptcha;

		var _proto = SliderCaptcha.prototype;
		_proto.init = function() {
			this.initDOM();
			this.initImg();
			this.bindEvents();
		};

		_proto.initDOM = function() {
			var createElement = function(tagName, className) {
				var elment = document.createElement(tagName);
				elment.className = className;
				return elment;
			};

			var createCanvas = function(width, height) {
				var canvas = document.createElement('canvas');
				canvas.width = width;
				canvas.height = height;
				return canvas;
			};

			var canvas = createCanvas(this.options.width - 2, this.options.height);
			var block = canvas.cloneNode(true); // Slider
			var sliderContainer = createElement('div', 'bec-slidercontainer');
			var sliderMask = createElement('div', 'bec-slidermask');
			var sliderbg = createElement('div', 'bec-sliderbg');
			var slider = createElement('div', 'bec-slider');
			var sliderIcon = createElement('i', 'slidericon');
			var text = createElement('div', 'bec-slidertext');

			block.className = 'bec-block';
			text.innerHTML = this.options.barText;

			var el = this.$element;
			el.append($(canvas));
			el.append($(block));
			slider.appendChild(sliderIcon);
			sliderMask.appendChild(slider);
			sliderContainer.appendChild(sliderbg);
			sliderContainer.appendChild(sliderMask);
			sliderContainer.appendChild(text);
			el.append($(sliderContainer));

			var _canvas = {
				canvas: canvas,
				block: block,
				sliderContainer: $(sliderContainer),
				slider: slider,
				sliderMask: sliderMask,
				sliderIcon: sliderIcon,
				text: $(text),
				canvasCtx: canvas.getContext('2d'),
				blockCtx: block.getContext('2d')
			};

			if ($.isFunction(Object.assign)) {
				Object.assign(this, _canvas);
			} else {
				$.extend(this, _canvas);
			}
		};

		_proto.initImg = function() {
			var that = this;
			var isIE = window.navigator.userAgent.indexOf('Trident') > -1;
			var L = this.options.sliderL + this.options.sliderR * 2 + 3;
			var drawImg = function(ctx, operation) {
				var l = that.options.sliderL;
				var r = that.options.sliderR;
				var PI = that.options.PI;
				var x = that.x;
				var y = that.y;
				ctx.beginPath();
				ctx.moveTo(x, y);
				ctx.arc(x + l / 2, y - r + 2, r, 0.72 * PI, 2.26 * PI);
				ctx.lineTo(x + l, y);
				ctx.arc(x + l + r - 2, y + l / 2, r, 1.21 * PI, 2.78 * PI);
				ctx.lineTo(x + l, y + l);
				ctx.lineTo(x, y + l);
				ctx.arc(x + r - 2, y + l / 2, r + 0.4, 2.76 * PI, 1.24 * PI, true);
				ctx.lineTo(x, y);
				ctx.lineWidth = 2;
				ctx.fillStyle = 'rgba(0, 0, 0, 0.5)';
				ctx.strokeStyle = 'rgba(255, 255, 255, 0.7)';
				ctx.stroke();
				ctx[operation]();
				ctx.globalCompositeOperation = isIE ? 'xor': 'destination-over';
			};

			var getRandomNumberByRange = function(start, end) {
				return Math.round(Math.random() * (end - start) + start);
			};
			var img = new Image();
			img.crossOrigin = "Anonymous";
			var loadCount = 0;
			img.onload = function() {
				// Randomly create the position of the slider
				that.x = getRandomNumberByRange(L + 10, that.options.width - (L + 10));
				that.y = getRandomNumberByRange(10 + that.options.sliderR * 2, that.options.height - (L + 10));
				drawImg(that.canvasCtx, 'fill');
				drawImg(that.blockCtx, 'clip');

				that.canvasCtx.drawImage(img, 0, 0, that.options.width - 2, that.options.height);
				that.blockCtx.drawImage(img, 0, 0, that.options.width - 2, that.options.height);
				var y = that.y - that.options.sliderR * 2 - 1;
				var ImageData = that.blockCtx.getImageData(that.x - 3, y, L, L);
				that.block.width = L;
				that.blockCtx.putImageData(ImageData, 0, y + 1);
				that.text.text(that.text.attr('data-text'));
			};
			img.onerror = function() {
				loadCount++;
				if (window.location.protocol === 'file:') {
					loadCount = that.options.maxLoadCount;
					console.error("can't load pic resource file from File protocal. Please try http or https");
				}
				if (loadCount >= that.options.maxLoadCount) {
					that.text.text('加载失败').addClass('bec-text-danger');
					return;
				}
				img.src = that.options.localImages();
			};
			img.setSrc = function() {
				var src = '';
				loadCount = 0;
				that.text.removeClass('bec-text-danger');
				if ($.isFunction(that.options.setSrc)) src = that.options.setSrc();
				//if (!src || src === '') src = 'https://picsum.photos/' + that.options.width + '/' + that.options.height + '/?image=' + Math.round(Math.random() * 20);
				if (isIE) {
					var xhr = new XMLHttpRequest();
					xhr.onloadend = function(e) {
						var file = new FileReader();
						file.readAsDataURL(e.target.response);
						file.onloadend = function(e) {
							img.src = e.target.result;
						};
					};
					xhr.open('GET', src);
					xhr.responseType = 'blob';
					xhr.send();
				} else img.src = src;
			};
			img.setSrc();
			this.text.attr('data-text', this.options.barText);
			this.text.text(this.options.loadingText);
			this.img = img;
		};

		_proto.clean = function() {
			this.canvasCtx.clearRect(0, 0, this.options.width, this.options.height);
			this.blockCtx.clearRect(0, 0, this.options.width, this.options.height);
			this.block.width = this.options.width;
		};

		_proto.bindEvents = function() {
			var that = this;
			this.$element.on('selectstart',
			function() {
				return false;
			});

			// 重置
			$('.button-primary, .button-reg, .refreshimg, .button-reg-no, .bec-captcha canvas').on('click',
			function() {
				that.text.text(that.options.barText);
				that.reset();
				if ($.isFunction(that.options.onRefresh)) that.options.onRefresh.call(that.$element);
			});

			$(document).on('keydown', function(e) {
				// 回车键
				if (e.which === 13) {
					that.text.text(that.options.barText);
					that.reset();
					if ($.isFunction(that.options.onRefresh)) that.options.onRefresh.call(that.$element);
				}
			})

			var originX, originY, trail = [],
			isMouseDown = false;

			var handleDragStart = function(e) {
				if (that.text.hasClass('text-danger')) return;
				originX = e.clientX || e.touches[0].clientX;
				originY = e.clientY || e.touches[0].clientY;
				isMouseDown = true;
			};

			var handleDragMove = function(e) {
				//e.preventDefault();
				if (!isMouseDown) return false;
				var eventX = e.clientX || e.touches[0].clientX;
				var eventY = e.clientY || e.touches[0].clientY;
				var moveX = eventX - originX;
				var moveY = eventY - originY;
				if (moveX < 0 || moveX + 40 > that.options.width) return false;
				that.slider.style.left = (moveX - 1) + 'px';
				var blockLeft = (that.options.width - 40 - 20) / (that.options.width - 40) * moveX;
				that.block.style.left = blockLeft + 'px';

				that.sliderContainer.addClass('bec-slidercontainer_active');
				that.sliderMask.style.width = (moveX + 4) + 'px';
				trail.push(moveY);
			};

			var handleDragEnd = function(e) {
				if (!isMouseDown) return false;
				isMouseDown = false;
				var eventX = e.clientX || e.changedTouches[0].clientX;
				if (eventX === originX) return false;
				that.sliderContainer.removeClass('bec-bec-slidercontainer_active');
				that.trail = trail;
				var data = that.verify();
				if (data.spliced && data.verified) {
					that.sliderContainer.addClass('bec-slidercontainer_success');
					if ($.isFunction(that.options.onSuccess)) that.options.onSuccess.call(that.$element);
				} else {
					that.sliderContainer.addClass('bec-slidercontainer_fail');
					if ($.isFunction(that.options.onFail)) that.options.onFail.call(that.$element);
					setTimeout(function() {
						that.sliderContainer.removeClass('bec-slidercontainer_active');
						that.reset();
						that.slider.style.transition = 'left 0.3s ease-in-out';
						that.slider.style.left = '0px';
						setTimeout(function() {
							that.slider.style.transition = '';
							that.text.text(that.options.failedText);
						},
						300);
					},
					1000);
				}
			};

			this.slider.addEventListener('mousedown', handleDragStart);
			this.slider.addEventListener('touchstart', handleDragStart);
			document.addEventListener('mousemove', handleDragMove);
			document.addEventListener('touchmove', handleDragMove);
			document.addEventListener('mouseup', handleDragEnd);
			document.addEventListener('touchend', handleDragEnd);

			document.addEventListener('mousedown',
			function() {
				return false;
			});
			document.addEventListener('touchstart',
			function() {
				return false;
			});
			document.addEventListener('swipe',
			function() {
				return false;
			});
		};

		_proto.verify = function() {
			var arr = this.trail; // The moving distance of the y-axis when dragging
			var left = parseInt(this.block.style.left);
			var verified = false;
			if (this.options.remoteUrl !== null) {
				verified = this.options.verify(arr, this.options.remoteUrl);
			} else {
				var sum = function(x, y) {
					return x + y;
				};
				var square = function(x) {
					return x * x;
				};
				var average = arr.reduce(sum) / arr.length;
				var deviations = arr.map(function(x) {
					return x - average;
				});
				var stddev = Math.sqrt(deviations.map(square).reduce(sum) / arr.length);
				verified = stddev !== 0;
			}
			return {
				spliced: Math.abs(left - this.x) < this.options.offset,
				verified: verified
			};
		};

		_proto.reset = function() {
			this.sliderContainer.removeClass('bec-slidercontainer_fail bec-slidercontainer_success');
			this.slider.style.left = 0;
			this.block.style.left = 0;
			this.sliderMask.style.width = 0;
			this.clean();
			this.text.attr('data-text', this.text.text());
			this.text.text(this.options.loadingText);
			this.img.setSrc();
			jQuery(".slidercaptcha").closest('form').find('input[type="submit"]').attr("disabled", true);
		};
	})(jQuery);

	jQuery(document).ready(function($) {
		$(".bec-slidercaptcha").closest('form').find('input[type="submit"]').attr("disabled", true);
		$('.bec-captcha').sliderCaptcha({
			barText: jQuery(".bec-captcha").data('slider'),
			failedText: jQuery(".bec-captcha").data('tryagain'),
			loadingText:'加载中...',
			onSuccess: function() {
				var $currentForm = $(this).closest('form'); // 获取当前表单
				$.ajax({
					url: verify_ajax.ajax_url,
					data: {
						action: 'be_ajax_verify',
						form: jQuery('.bec-captcha').data("form")
					},
					type: 'POST',
					success: function(result) {
						$(".bec-slidercaptcha").closest('form').find('input[type="submit"]').removeAttr("disabled");
						$(".bec-slidercaptcha").closest('form').find('button[type="submit"]').removeAttr("disabled");
						//$(".bec-slidercaptcha").closest('form').submit();
						$currentForm.submit();
						$(".off-login").fadeIn();
						$(".slidercaptcha-box").fadeOut();
						$(".bec-slidercontainer").removeClass('bec-slidercontainer_active');
						return true;
					}
				});
			}
		});

		$(document).on('click', '.button-primary, .button-reg', function() {
			$(".slidercaptcha-box").fadeIn();
			$(".off-login").fadeOut();
			$(".slidercaptcha-box").on('touchmove',
			function(e) {
				e.preventDefault();
			});
		});

		$(document).on('keydown', function(e) {
			// 回车键
			if (e.which === 13) {
				$(".slidercaptcha-box").fadeIn();
				$(".off-login").fadeOut();
				$(".slidercaptcha-box").on('touchmove',
				function(e) {
					e.preventDefault();
				});
			}
		});

		$(".becclose").click(function() {
			$(".slidercaptcha-box").fadeOut();
			$(".off-login").fadeIn();
		});

		$(".bec-slidercaptcha").click(function(e) {
			e.stopPropagation();
		});

		$('.noticecheckbox').on('change', function() {
			if ($(this).is(':checked')) {
				$('.reg-button').removeClass('button-reg-no').addClass('button-reg');
			} else {
				$('.reg-button').removeClass('button-reg').addClass('button-reg-no');
			}
		});

		$(document).on('click', '.button-reg-no', function() {
			$(".reg-notice-agree").fadeIn();
			setTimeout(function() {
				$(".reg-notice-agree").fadeOut();
			}, 3000);
		});
	});
}

SliderCaptcha();