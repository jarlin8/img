"use strict";

$( function() {

    $( "#aiKitPromptsSubmit" ).click( function() {
        aiKitOnPromptsSubmit();
    });

    $('#aikit-add-prompt').on('click', function() {
        // get the group-template and clone it
        let group = $('.group-template').clone();
        group.removeClass('group-template').addClass('group');

        // generate a unique id
        let id = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);

        // replace the __PROMPT_KEY__ with the unique id all the way down the tree in name and id attributes
        group.find('[name*="__PROMPT_KEY__"]').each(function() {
            $(this).attr('name', $(this).attr('name').replace('__PROMPT_KEY__', id));
        });
        group.find('[id*="__PROMPT_KEY__"]').each(function() {
            $(this).attr('id', $(this).attr('id').replace('__PROMPT_KEY__', id));
        });

        // also replace the __PROMPT_KEY__ in href attributes
        group.find('[href*="__PROMPT_KEY__"]').each(function() {
            $(this).attr('href', $(this).attr('href').replace('__PROMPT_KEY__', id));
        });

        // also replace the __PROMPT_KEY__ in for attributes
        group.find('[for*="__PROMPT_KEY__"]').each(function() {
            $(this).attr('for', $(this).attr('for').replace('__PROMPT_KEY__', id));
        });

        // add the group to the page aikit-prompts-accordion
        $('#aikit-prompts-accordion').append(group);
        // refresh the accordion
        $('#aikit-prompts-accordion').accordion('refresh');

        //scroll to the new group
        $('html, body').animate({
            scrollTop: group.offset().top
        }, 100);

        $(group).find( ".tabs" ).tabs();

        // expand the new group in the accordion
        group.find('.aikit-prompt-accordion-header').click();
    });

    // change to include all future elements
    $('body').on('click', '.text-length-card', function() {
    // $( ".text-length-card" ).click( function() {
        // only if the input is not disabled
        if ( !$( this ).find( "input" ).is( ":disabled" ) ) {
            $( this ).find( "input" ).prop( "checked", true );
        }
    });

    $('body').on('input', '.menu-title-input', function() {
    // $(".menu-title-input").on("input", function() {
        // update the title of the accordion as we type
        $(this).parents(".group").first().find(".aikit-prompt-accordion-header").text($(this).val());
    });

    $('body').on('click', '.requires-text-selection-input', function() {
    // $( ".requires-text-selection-input" ).click( function() {
        aikitEnableOrDisableTextLengthCards($( this ));
    });

    $( ".requires-text-selection-input" ).each( function() {
        aikitEnableOrDisableTextLengthCards($( this ));
    });

    $('body').on('input', '.aikit-slider', function() {
    // $('.aikit-slider').on('input', function() {
        let value = $(this).val();
        $(this).parent().parent().find('.slider-value').text(value + 'x');
    });

    $('.aikit-slider').each(function() {
        let value = $(this).val();
        $(this).parent().parent().find('.slider-value').text(value + 'x');
    });

    $("#aikit-reset-prompts").click( function() {
        let message = $(this).data('confirm-message');
        if (confirm(message)) {
            $("#aikit-prompts-form").append('<input type="hidden" name="reset" value="1">');
            // remove all inputs except the reset input
            $("#aikit-prompts-form").find("input:not([name='reset'])").remove();
            $("form#aikit-prompts-form")[0].submit();
        }

        return false;
    });

    $('body').on('click', '.aikit-remove-prompt', function(event) {
    // $('.aikit-remove-prompt').click( function() {
        let message = $(this).data('confirm-message');
        if (confirm(message)) {
            $(this).parent().parent().remove();
        }

        event.stopPropagation();
        return false;
    });

    $( "#aikit-prompts-accordion" )
        .accordion({
            header: "> div > h3",
            heightStyle: "content",
            icons: false,
        })
        .sortable({
            axis: "y",
            handle: "h3",
            stop: function( event, ui ) {
                // IE doesn't register the blur when sorting
                // so trigger focusout handlers to remove .ui-state-focus
                ui.item.children( "h3" ).triggerHandler( "focusout" );

                // Refresh accordion to handle new order
                $( this ).accordion( "refresh" );
            }
        });

    $( ".tabs" ).tabs();
} );

function aikitEnableOrDisableTextLengthCards( requiresTextSelectionInput ) {
    if ( ! $(requiresTextSelectionInput).is( ":checked" ) ) {
        $( requiresTextSelectionInput ).parents( ".group" ).first().find( ".relative-card" )
            .find( "input[type=\"radio\"]" )
            .prop( "checked", false );

        $( requiresTextSelectionInput ).parents( ".group" ).first().find( ".relative-card" )
            .find( "input" )
            .prop( "disabled", true );

        $( requiresTextSelectionInput ).parents( ".group" ).first().find( ".fixed-card" )
            .find( "input[type=\"radio\"]" )
            .prop( "checked", true );
    } else {
        $( requiresTextSelectionInput ).parents( ".group" ).first().find( ".relative-card" )
            .find( "input" )
            .prop( "disabled", false );
    }
}


function aiKitOnPromptsSubmit () {
    let form = document.getElementById("aikit-prompts-form");
    let fields = form.elements;

    let data = {};

    for (let i = 0; i < fields.length; i++) {
        let field = fields[i];
        let name = field.name;
        let value = field.value;
        if (name.startsWith("prompts")) {
            if ( (field.type === "checkbox" || field.type === "radio") && !field.checked ) {
                continue;
            }

            let keys = name.split("[");
            let current = data;
            for (let j = 0; j < keys.length; j++) {
                let key = keys[j].replace("]", "");
                if (key === "") {
                    continue;
                }
                if (j === keys.length - 1) {
                    current[key] = value;
                } else {
                    if (!current[key]) {
                        current[key] = {};
                    }
                    current = current[key];
                }
            }
        }
    }

    let jsonString = JSON.stringify(data.prompts || {});

    // submit the form using ajax
    $.ajax({
        type: "POST",
        url: window.location.href,
        data: {
            'prompts': jsonString,
            'option_page': form.elements['option_page'].value,
            'action': form.elements['action'].value,
            '_wpnonce': form.elements['_wpnonce'].value,
        }
    }).done(function (response) {
        window.location = window.location.href;
    }).fail(function (response) {
        alert(response.responseText);
    });
}
