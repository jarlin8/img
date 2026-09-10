function sticky_comments_click() {
	jQuery('.feature-comments').unbind('click');
	jQuery('body').on('click', '.feature-comments',
	function() {
		$this = jQuery(this);
		jQuery.post(sticky_comments.ajax_url, {
			'action': 'feature_comments',
			'do': $this.data('do'),
			'comment_id': $this.data('comment_id'),
			'nonce': $this.data('nonce')
		},
		function(response) {
			var action = $this.attr('data-do'),
			comment_id = $this.attr('data-comment_id'),
			$comment = jQuery("#comment-" + comment_id + ", #li-comment-" + comment_id),
			$this_and_comment = $this.siblings('.feature-comments').add($comment).add($this);
			if (action == 'feature') $this_and_comment.addClass('sticky');
			if (action == 'unfeature') $this_and_comment.removeClass('sticky');
			if (action == 'bury') $this_and_comment.addClass('buried');
			if (action == 'unbury') $this_and_comment.removeClass('buried');

			$this.data('nonce', response);
		});
		return false;
	});

}

jQuery(document).ready(function($) {
	sticky_comments_click();
	$('.feature-comments.feature').each(function() {
		$this = $(this);
		$tr = $(this).parents('tr');
		if ($this.hasClass('sticky')) $tr.addClass('sticky');
		if ($this.hasClass('buried')) $tr.addClass('buried');
	});
});