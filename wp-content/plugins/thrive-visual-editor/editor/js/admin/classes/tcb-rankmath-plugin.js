( $ => {
	module.exports = class TCBRankMathPlugin {
		/**
		 * Class constructor
		 */
		constructor() {
			this.init();
			this.hooks();
		}

		/**
		 * Init the custom fields
		 */
		init() {
			this.content = '';
			this.fetchContent();
		}

		/**
		 * Hook into Rank Math App eco-system
		 */
		hooks() {
			wp.hooks.addFilter( 'rank_math_content', 'rank-math', content => {
				content += this.content;

				return content;
			}, 11 );
		}

		/**
		 * Fetch Post Content from TCB
		 */
		fetchContent() {
			$.ajax( {
				url: ajaxurl,
				type: 'post',
				dataType: 'json',
				data: {
					post_id: TCB_Post_Edit_Data.post_id,
					action: 'tve_get_seo_content'
				}
			} ).done( response => {
				this.content = response.content;
				if ( typeof window.rankMathEditor !== 'undefined' ) {
					rankMathEditor.refresh( 'content' );
				}
			} );
		}
	}
} )( jQuery );

