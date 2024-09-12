jQuery(document).ready(function () {
    jQuery("div#isbnMetaBox").find('h2').replaceWith('<h4 style="top:7px;position:relative;"><span class="dashicons dashicons-book-alt" style="font-size:15px"></span>' + jQuery("div#isbnMetaBox").find('h2').text() + '</h4>');
});
