<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if ( is_category( explode( ',', zm_get_option( 'cat_layout_img' ) ) ) || is_tag( explode( ',', zm_get_option( 'cat_layout_img' ) ) ) ) {
	get_template_part( 'category-img' );
}
elseif ( is_category( explode( ',', zm_get_option( 'cat_layout_img_s' ) ) ) || is_tag( explode( ',', zm_get_option( 'cat_layout_img_s' ) ) ) ) {
	get_template_part( 'category-img-s' );
}
elseif ( is_category( explode( ',', zm_get_option( 'cat_layout_grid' ) ) ) || is_tag( explode( ',', zm_get_option( 'cat_layout_grid' ) ) ) ) {
	get_template_part( 'category-grid' );
}
elseif ( is_category( explode( ',', zm_get_option( 'cat_layout_fall' ) ) ) || is_tag( explode( ',', zm_get_option( 'cat_layout_fall' ) ) ) ) {
	get_template_part( 'category-fall' );
}
elseif ( is_category( explode( ',', zm_get_option( 'cat_layout_play' ) ) ) || is_tag( explode( ',', zm_get_option( 'cat_layout_play' ) ) ) ) {
	get_template_part( 'category-play' );
}
elseif ( is_category( explode( ',', zm_get_option( 'cat_layout_full' ) ) ) || is_tag( explode( ',', zm_get_option( 'cat_layout_full' ) ) ) ) {
	get_template_part( 'category-full' );
}
elseif ( is_category( explode( ',', zm_get_option( 'cat_layout_list' ) ) ) || is_tag( explode( ',', zm_get_option( 'cat_layout_list' ) ) ) ) {
	get_template_part( 'category-list' );
}
elseif ( is_category( explode( ',', zm_get_option( 'cat_layout_title' ) ) ) || is_tag( explode( ',', zm_get_option( 'cat_layout_title' ) ) ) ) {
	get_template_part( 'category-title' );
}
elseif ( is_category( explode( ',', zm_get_option( 'cat_layout_square' ) ) ) || is_tag( explode( ',', zm_get_option( 'cat_layout_square' ) ) ) ) {
	get_template_part( 'category-square' );
}
elseif ( is_category( explode( ',', zm_get_option( 'cat_layout_line' ) ) ) || is_tag(explode(',',zm_get_option('cat_layout_line'))) ) {
	get_template_part( 'category-line' );
}
elseif ( is_category( explode( ',', zm_get_option( 'cat_child_cover' ) ) ) || is_tag( explode( ',', zm_get_option( 'cat_child_cover' ) ) ) ) {
	get_template_part( 'category-child-cover' );
}
elseif ( is_category( explode( ',', zm_get_option( 'cat_layout_child' ) ) ) || is_tag( explode( ',', zm_get_option( 'cat_layout_child' ) ) ) ) {
	get_template_part( 'category-child-nosidebar' );
}
elseif ( is_category( explode( ',', zm_get_option( 'cat_layout_child_img' ) ) ) || is_tag( explode( ',', zm_get_option( 'cat_layout_child_img' ) ) ) ) {
	get_template_part( 'category-child-img' );
}
elseif ( is_category( explode( ',', zm_get_option( 'ajax_layout_code_a' ) ) ) || is_tag( explode( ',', zm_get_option( 'ajax_layout_code_a' ) ) ) ) {
	get_template_part( 'category-code-a' );
}
elseif ( is_category( explode( ',', zm_get_option( 'ajax_layout_code_b' ) ) ) || is_tag( explode( ',', zm_get_option( 'ajax_layout_code_b' ) ) ) ) {
	get_template_part( 'category-code-b' );
}
elseif ( is_category( explode( ',', zm_get_option( 'ajax_layout_code_c' ) ) ) || is_tag( explode( ',', zm_get_option( 'ajax_layout_code_c' ) ) ) ) {
	get_template_part( 'category-code-c' );
}
elseif ( is_category( explode( ',', zm_get_option( 'ajax_layout_code_d' ) ) ) || is_tag( explode( ',', zm_get_option( 'ajax_layout_code_d' ) ) ) ) {
	get_template_part( 'category-code-d' );
}
elseif ( is_category( explode( ',', zm_get_option( 'ajax_layout_code_e' ) ) ) || is_tag( explode( ',', zm_get_option( 'ajax_layout_code_e' ) ) ) ) {
	get_template_part( 'category-code-e' );
}
elseif ( is_category( explode( ',', zm_get_option( 'ajax_layout_code_f' ) ) ) || is_tag( explode( ',', zm_get_option( 'ajax_layout_code_f' ) ) ) ) {
	get_template_part( 'category-code-f' );
}
elseif ( is_category( explode( ',', zm_get_option( 'cat_layout_default' ) ) ) || is_tag( explode( ',', zm_get_option( 'cat_layout_default' ) ) ) ) {
	get_template_part( 'archive-default' );
}
elseif ( is_category() ) {
	get_template_part( zm_get_option( "default_cat_template" ) );
}
elseif ( is_tag() ) {
	get_template_part( zm_get_option( "default_tag_template" ) );
}
else {
	get_template_part( 'archive-default' );
}