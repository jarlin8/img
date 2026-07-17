jQuery( document ).ready( function( $ ) {

    $( "#submit" ).val( slmw_args.i18n_activate_license ).click( function( e ) { // Internationalize

        e.preventDefault();

        var $this            = $( this ),
            activation_email = $.trim( $( "#tap_slmw_activation_email" ).val() ),
            license_key      = $.trim( $( "#tap_slmw_license_key" ).val() );

        $this.val( slmw_args.i18n_activating_license ).attr( "disabled" , "disabled" );

        if ( $this.siblings( '.spinner' ).length > 0 )
            $this.siblings( '.spinner' ).css( { display : "inline-block" } );

        $.ajax( {
            url      : ajaxurl,
            type     : "POST",
            data     : { action : "tap_activate_license" , "activation-email" : activation_email , "license-key" : license_key , "ajax-nonce" : slmw_args.nonce_activate_license },
            dataType : "json"
        } )
        .done( function( data ) {

            if ( data.status === "success" ) {

                if ( $( ".tap-activate-license-notice" ).length > 0 )
                    $( ".tap-activate-license-notice" ).closest( "div.error" ).remove();

                vex.dialog.alert( data.success_msg );

            } else
                vex.dialog.alert( data.error_msg );

        } )
        .fail( function( jqxhr ) {

            vex.dialog.alert( slmw_args.i18n_failed_to_activate_license );
            console.log( jqxhr );

        } )
        .always( function() {

            $this.val( slmw_args.i18n_activate_license ).removeAttr( "disabled" );

            if ( $this.siblings( '.spinner' ).length > 0 )
                $this.siblings( '.spinner' ).css( { display : "none" } );

        } );

    } );

} );