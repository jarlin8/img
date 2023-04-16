"use strict";

jQuery(function($) {

    // on body when aikit-auto-writer-nav is clicked
    $("body").on("click", ".aikit-auto-writer-nav a", function (event) {
        event.preventDefault();
        // parse the href parameters and get the paged value
        let url = new URL($(this).attr('href'));
        let paged = url.searchParams.get("paged");

        loadPostPage(paged);
    });

    $("#aikit-auto-writer-form").submit(function (event) {
        event.preventDefault();

        if (!isProperlyConfigured()) {
            alert('Please enter a valid OpenAI API key in the settings page.');
            return;
        }

        if ($("#aikit-auto-writer-topic").val() === '') {
            alert($('#aikit-auto-writer-topic').data('validation-message'));
            return;
        }

        // add spinner to the button #aikit-auto-writer-generate bootstrap spinner
        $("#aikit-auto-writer-generate").prepend('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
        // disable the button
        $("#aikit-auto-writer-generate").prop('disabled', true);
        $(".aikit-dont-close-page").show();

        let prompts = {};
        $(".aikit-auto-writer-prompt").each(function () {
            prompts[$(this).data('prompt-id')] = $(this).val();
        });

        let formData = {
            topic: $("#aikit-auto-writer-topic").val(),
            include_outline: $("#aikit-auto-writer-include-outline").is(':checked'),
            include_featured_image: $("#aikit-auto-writer-include-featured-image").is(':checked'),
            include_section_images: $("#aikit-auto-writer-include-section-images").is(':checked'),
            include_tldr: $("#aikit-auto-writer-include-tldr").is(':checked'),
            include_conclusion: $("#aikit-auto-writer-include-conclusion").is(':checked'),
            post_type: $("#aikit-auto-writer-post-type").val(),
            post_status: $("#aikit-auto-writer-post-status").val(),
            post_category: $("#aikit-auto-writer-post-category").val(),
            number_of_sections: $("#aikit-auto-writer-sections").val(),
            section_max_length: $("#aikit-auto-writer-words-per-section").val(),
            number_of_articles: $("#aikit-auto-writer-articles").val(),
            seo_keywords: $("#aikit-auto-writer-seo-keywords").val(),
            prompts: prompts,
        };

        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: JSON.stringify(formData),
            dataType: "json",
            encode: true,
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': aikit.nonce,
            },
        }).success(function (response) {
            refreshPosts($("#aikit-auto-writer-articles").val());

            $("#aikit-auto-writer-generate .spinner-border").remove();
            $("#aikit-auto-writer-generate").prop('disabled', false);
            $(".aikit-dont-close-page").hide();

        }).fail(function (response) {
            alert('Error: ' + response.responseText);
            $("#aikit-auto-writer-generate .spinner-border").remove();
            $("#aikit-auto-writer-generate").prop('disabled', false);
            $(".aikit-dont-close-page").hide();
        });
    });

    const refreshPosts = function (number_of_articles) {
        loadPostPage(1, function () {
            $("#aikit-auto-writer-posts tbody tr").slice(0, number_of_articles).css('background-color', '#eff4fb');
            $("#aikit-auto-writer-posts tbody tr").slice(0, number_of_articles).animate({backgroundColor: '#fff'}, 3000);
        });
    }

    const loadPostPage = function (paged, onSuccess) {
        $.ajax({
            type: "GET",
            url: aikit.siteUrl + '/?rest_route=/aikit/auto-writer/v1/list&paged=' + paged,
            dataType: "json",
            encode: true,
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': aikit.nonce,
            },
        }).success(function (response) {
            // replace with new posts
            $("#aikit-auto-writer-posts").html(response.body);

            if (typeof onSuccess === 'function') {
                onSuccess();
            }

        }).fail(function (response) {
            alert('Error: ' + response.responseText);
        });
    }

    const isProperlyConfigured = function () {
        if (aikit.isOpenAIKeyValid === undefined || aikit.isOpenAIKeyValid === "0" || aikit.isOpenAIKeyValid === "" || aikit.isOpenAIKeyValid === false) {
            return false;
        }

        return true;
    }
});
