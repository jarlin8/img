jQuery(document).ready(function() {
	var copy_text_label = '<i class="be be-stack"></i>';
	var copied_text_label = '<i class="be be-clipboard"></i>';
	var copy_text_label_safari = 'Press "⌘ + C" to copy';
	var copy_text_label_other_browser = 'Press "Ctrl + C" to copy';

	var copyButton = '<div class="btn-clipboard" title="复制" data-original-title="Copy to clipboard">' + copy_text_label + '</div>';
	jQuery('pre').each(function() {
		jQuery(this).wrap('<div class="codebox"/>');
	});

	jQuery('div.codebox').prepend(jQuery(copyButton)).children('.btn-clipboard').show();
	var copyCode = new ClipboardJS('.btn-clipboard', {
		target: function(trigger) {
			return trigger.nextElementSibling;
		}
	});

	copyCode.on('success',
	function(event) {
		event.clearSelection();
		event.trigger.innerHTML = copied_text_label;
		window.setTimeout(function() {
			event.trigger.innerHTML = copy_text_label;
		},
		2000);
	});

	copyCode.on('error',
	function(event) {
		var is_safari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);

		if (is_safari) {
			event.trigger.textContent = copy_text_label_safari;
		} else if (navigator.userAgent.match(/ipad|ipod|iphone/i)) {
			event.trigger.textContent = copy_text_label_other_browser;
		} else {
			event.trigger.textContent = copy_text_label_other_browser;
		}

		window.setTimeout(function() {
			event.trigger.textContent = copy_text_label;
		},
		5000);
	});

});