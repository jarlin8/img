( $ => {
	module.exports = class TCBYoastPlugin {
		static register() {
			YoastSEO.app.registerPlugin( 'tcbYoastPlugin', {status: 'loading'} );

			TCBYoastPlugin.fetchData()
		}

		static fetchData() {
			$.ajax( {
				url: ajaxurl,
				type: 'post',
				dataType: 'json',
				data: {
					post_id: TCB_Post_Edit_Data.post_id,
					action: 'tve_get_seo_content'
				}
			} ).done( response => {
				YoastSEO.app.pluginReady( 'tcbYoastPlugin' );

				/**
				 * @param modification    {string}    The name of the filter
				 * @param callable        {function}  The callable
				 * @param pluginName      {string}    The plugin that is registering the modification.
				 * @param priority        {number}    (optional) Used to specify the order in which the callables
				 *                                    associated with a particular filter are called. Lower numbers
				 *                                    correspond with earlier execution.
				 */
				YoastSEO.app.registerModification( 'content', content => TCBYoastPlugin.parseTCBContent( content, response.content ), 'tcbYoastPlugin', 5 );
			} );
		}

		static parseTCBContent( content, architectContent ) {
			//remove empty tags because yoast kind fails on parse here
			if ( architectContent ) {
				const contentSelector = '.tcb-style-wrap',
					$content = $( `<div>${architectContent}</div>` ).find( contentSelector );

				$content.find( '*:empty:not(img,input,br)' ).remove();

				architectContent = $content.html();
			}

			return architectContent ? architectContent : content;
		}
	}
} )( jQuery );

