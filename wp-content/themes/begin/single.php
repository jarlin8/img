<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if ( in_category( explode( ',',zm_get_option( 'single_layout_qa' ) ) ) ) {
	get_template_part( 'single-beqa' );
}
elseif ( in_category( explode( ',',zm_get_option( 'novel_layout' ) ) ) ) {
	get_template_part( 'single-novel' );
}
elseif ( in_category( explode( ',',zm_get_option( 'single_layout_down' ) ) ) ) {
	get_template_part( 'single-download' );
}
elseif ( in_category( explode( ',',zm_get_option( 'single_layout_vip' ) ) ) ) {
	get_template_part( 'single-vip' );
}
elseif ( in_category( explode( ',',zm_get_option( 'single_layout_display' ) ) ) ) {
	get_template_part( 'single-display' );
}
elseif ( in_category( explode( ',',zm_get_option( 'single_layout_full' ) ) ) ) {
	get_template_part( 'single-full' );
}
elseif ( in_category( explode( ',',zm_get_option( 'single_layout_note' ) ) ) ) {
	get_template_part( 'single-note' );
}
elseif ( in_category( explode( ',',zm_get_option( 'single_layout_bio' ) ) ) ) {
	get_template_part( 'single-bio' );
}
elseif ( in_category( explode( ',',zm_get_option( 'single_layout_vip' ) ) ) ) {
	get_template_part( 'single-vip' );
}
elseif ( in_category( explode( ',',zm_get_option( 'single_layout_notes' ) ) ) ) {
	get_template_part( 'single-notes' );
}
elseif ( in_category( explode( ',',zm_get_option( 'single_layout_goods' ) ) ) ) {
	get_template_part( 'single-goods' );
}
else {
	get_template_part( 'single-default' );
}