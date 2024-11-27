jQuery(function($) {
	$('a.keep-favorite-link').on('click',
	function(event) {
		event.preventDefault();
		var $self = $(this);
		var data = {
			post_id: $self.data('id'),
			nonce: keep.nonce,
			action: 'keep_action'
		};
		$self.addClass('keep-loading');

		$.post(keep.ajaxurl, data,
		function(res) {
			if (res.success) {
				$self.html(res.data);

			} else {
				alert(keep.errorMessage);
			}
			// remove loader
			$self.removeClass('keep-loading');
		});
	});
     // delete favorite
	$('a.keep-remove-favorite').on('click',
	function(event) {
		event.preventDefault();

		var $self = $(this);
		var data = {
			post_id: $self.data('id'),
			nonce: keep.nonce,
			action: 'keep_action'
		};
		$self.addClass('keep-loading');
		$.post(keep.ajaxurl, data,
		function(res) {
			if (res.success) {
				window.location.reload();
			} else {
				alert(keep.errorMessage);
			}
			// remove loader
			$self.removeClass('keep-loading');
		});
	});
});