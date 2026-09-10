jQuery(document).ready(function() {
	var copy_text_label = '<i class="be be-clipboard"></i>';
	var copied_text_label = '<span class="dashicons dashicons-yes"></span>';
	var copyButton = '<div class="btn-clipboard bgt" title="Copy">' + copy_text_label + '</div>';
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
		jQuery('.dashicons-yes').closest('.codebox').addClass('pre-loading');
		window.setTimeout(function() {
			event.trigger.innerHTML = copy_text_label;
			jQuery('.codebox').removeClass('pre-loading');
		},
		500);
	});

	copyCode.on('error',
	function(event) {
		window.setTimeout(function() {
			event.trigger.textContent = copy_text_label;
		},
		2000);
	});

});