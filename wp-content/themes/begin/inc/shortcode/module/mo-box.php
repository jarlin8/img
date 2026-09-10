<?php

// 居中盒子
function build_item( $atts, $content=null ) {
	extract( shortcode_atts( array(
		'top'    => 0,
		'bottom' => 0,
	),
	$atts ) );

	$html = '<div class="be-build build-item" style="padding: ' . $top . 'px 0 ' . $bottom . 'px 0;">';
	$html .= do_shortcode( strip_tags( $content ) );
	$html .= '</div>';
	$html .= '<div class="clear"></div>';
	return $html;
}
// [norm in="0"]
add_shortcode( 'norm', 'build_item' );

// 全宽盒子
function build_full( $atts, $content=null ) {
	extract( shortcode_atts( array(
		'white'  => 1,
		'top'    => 0,
		'bottom' => 0,
	),
	$atts ) );

	if ( ! empty( $atts['white'] ) ) {
		$html = '<div class="be-build build-full white" style="padding: ' . $top . 'px 0 ' . $bottom . 'px 0;">';
	} else {
		$html = '<div class="be-build build-full" style="padding: ' . $top . 'px 0 ' . $bottom . 'px 0;">';
	}
	$html .= do_shortcode( strip_tags( $content ) );
	$html .= '</div>';
	$html .= '<div class="clear"></div>';
	return $html;
}
add_shortcode( 'full', 'build_full' );

// 两栏盒子
function build_col( $atts, $content=null ) {
	extract( shortcode_atts( array(
		'top'    => 0,
		'bottom' => 0,
	),
	$atts ) );

	$html = '<div class="be-build build-two" style="padding: ' . $top . 'px 0 ' . $bottom . 'px 0;">';
	$html .= do_shortcode( strip_tags( $content ) );
	$html .= '</div>';
	$html .= '<div class="clear"></div>';
	return $html;
}
add_shortcode( 'column', 'build_col' );

// 标题
function module_title($atts, $content=null) {
	extract( shortcode_atts( array(
		'title' => '',
		'des'   => '',
		'url'   => '',
	),
	$atts ) );
	$html = '<div class="clear"></div>';
	$html .= '<div class="group-title">';
	$html .= '<a href="' . $url . '" title="' . sprintf(__( '更多', 'begin' )) . '">';
	$html .= '<h3>' . $title . '</h3>';
	$html .= '</a>';
	if ( ! empty( $atts['des'] ) ) {
		$html .= '<div class="group-des">' . $des . '</div>';
	}
	$html .= '<div class="clear"></div>';
	$html .= '</div>';
	return $html;
}

// [betitle title=标题 url=链接 des=说明]
add_shortcode( 'betitle', 'module_title' );

// 更多按钮
function module_more($atts, $content=null) {
	extract( shortcode_atts( array(
		'text' => '',
		'url'  => '',
	),
	$atts ) );

	$html = '<div class="group-post-more">';
	$html .= '<a href="' . $url . '" title="' . $text . '" rel="external nofollow"><i class="be be-more"></i></a>';
	$html .= '</div>';
	return $html;
}

// [bemore url="链接" text="提示"]
add_shortcode( 'more', 'module_more' );