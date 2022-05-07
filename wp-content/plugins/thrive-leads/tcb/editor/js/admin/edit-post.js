( $ => {

	const TCBYoastPlugin = require( './classes/tcb-yoast-plugin' ),
		TCBRankMathPlugin = require( './classes/tcb-rankmath-plugin' ),
		RankMathInstance = new TCBRankMathPlugin(),
		YoastInstance = new TCBYoastPlugin();

	window.TCBYoastPlugin = TCBYoastPlugin;

	/**
	 * YoastSEO content analysis integration
	 */
	$( window ).on( 'YoastSEO:ready', () => {
		YoastInstance.init();
	} );

	/**
	 * RankMath content analysis integration
	 */
	$( document ).ready( function () {
		if ( typeof window.rankMath !== 'undefined' ) {
			RankMathInstance.init();
		}
	} );

	/**
	 * this is not been used anymore, I don't think we managed to identify whether or not the post has been saved as draft
	 *
	 * @param element
	 */
	function tve_page_not_ready_notification( element ) {
		jQuery( element ).pointer( {
			content: "<h3>You can't edit the post yet!</h3>" +
			         "<p>In order to edit the post with the Content Builder you have to have:-</p>" +
			         "<ol><li>Saved the post before (using the 'Save Draft' button)</li>" +
			         "<li>Set a title for the post / page</li></ol>" +
			         "<p>Make these changes, and you'll be able to click this button and edit the page!",
			position: {
				edge: 'left',
				align: 'center'
			},
			close: function () {
				// Once the close button is hit
			}
		} ).pointer( 'open' );
	}

	function show_loader() {
		$( '#tcb-admin-page-loader' ).show();
	}

	$( function () {
		const $document = $( document );

		$document.on( 'click.tcb', '#tcb2-migrate-post', ( index, element ) => {
			show_loader();

			$.ajax( {
				type: 'post',
				url: ajaxurl,
				dataType: 'json',
				data: {
					_nonce: TCB_Post_Edit_Data.admin_nonce,
					post_id: TCB_Post_Edit_Data.post_id,
					action: 'tcb_admin_ajax_controller',
					route: 'migrate_post_content'
				}
			} ).done( function () {
				location.href = element.getAttribute( 'data-edit' );
			} ).fail( function ( jqXHR ) {
				alert( 'ERROR: ' + jqXHR.responseText );
			} );
		} )
		         .on( 'click', '#tcb2-show-wp-editor', function () {
			         /**
			          * Enable the hidden input that will disable TCB editor when saving the post
			          */
			         const $editlink = $document.find( '.tcb-enable-editor' ),
				         $postbox = $editlink.closest( '.postbox' );

			         $postbox.next( '.tcb-flags' ).find( 'input' ).prop( 'disabled', false );
			         $postbox.before( $editlink );
			         $postbox.remove();
			         $( 'body' ).removeClass( 'tcb-hide-wp-editor' );
		         } )
		         .on( 'click', '.tcb-enable-editor', function () {
			         $( 'body' ).addClass( 'tcb-hide-wp-editor' );

			         $.ajax( {
				         type: 'post',
				         url: ajaxurl,
				         dataType: 'json',
				         data: {
					         _nonce: TCB_Post_Edit_Data.admin_nonce,
					         post_id: this.getAttribute( 'data-id' ),
					         action: 'tcb_admin_ajax_controller',
					         route: 'enable_tcb'
				         }
			         } ).done( function () {
				         $( window ).off( 'beforeunload.edit-post' );
				         $( 'input#save-post' ).trigger( 'click' );
			         } );
		         } );
	} );

} )( jQuery );
