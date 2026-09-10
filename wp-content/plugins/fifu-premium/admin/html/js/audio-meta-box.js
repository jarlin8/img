jQuery(document).ready(function () {
    jQuery("div#audioMetaBox").find('h2').replaceWith('<h4 style="top:7px;position:relative;"><span class="dashicons dashicons-controls-volumeon" style="font-size:15px"></span>' + jQuery("div#audioMetaBox").find('h2').text() + '</h4>');
});
