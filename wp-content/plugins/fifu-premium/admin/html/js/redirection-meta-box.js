jQuery(document).ready(function () {
    jQuery("div#redirectionMetaBox").find('h2').replaceWith('<h4 style="top:7px;position:relative;"><span class="dashicons dashicons-external" style="font-size:15px"></span>' + jQuery("div#redirectionMetaBox").find('h2').text() + '</h4>');
});
