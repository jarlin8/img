<?php
// 幻灯
function build_slider( $atts ) {
	$atts = shortcode_atts( array(
	), $atts );

	ob_start();
	require get_template_directory() . '/template/slider.php';
	return ob_get_clean();
}

// [slider]
add_shortcode( 'slider', 'build_slider' );