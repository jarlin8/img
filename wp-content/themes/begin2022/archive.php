<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if ( is_category(explode(',',zm_get_option('cat_layout_img'))) || is_tag(explode(',',zm_get_option('cat_layout_img'))) ) {
	get_template_part( 'category-img' );
}
elseif ( is_category(explode(',',zm_get_option('cat_layout_img_s'))) || is_tag(explode(',',zm_get_option('cat_layout_img_s'))) ) {
	get_template_part( 'category-img-s' );
}
elseif ( is_category(explode(',',zm_get_option('cat_layout_grid'))) || is_tag(explode(',',zm_get_option('cat_layout_grid'))) ) {
	get_template_part( 'category-grid' );
}
elseif ( is_category(explode(',',zm_get_option('cat_layout_fall'))) || is_tag(explode(',',zm_get_option('cat_layout_fall'))) ) {
	get_template_part( 'category-fall' );
}
elseif ( is_category(explode(',',zm_get_option('cat_layout_play'))) || is_tag(explode(',',zm_get_option('cat_layout_play'))) ) {
	get_template_part( 'category-play' );
}
elseif ( is_category(explode(',',zm_get_option('cat_layout_full'))) || is_tag(explode(',',zm_get_option('cat_layout_full'))) ) {
	get_template_part( 'category-full' );
}
elseif ( is_category(explode(',',zm_get_option('cat_layout_list'))) || is_tag(explode(',',zm_get_option('cat_layout_list'))) ) {
	get_template_part( 'category-list' );
}
elseif ( is_category(explode(',',zm_get_option('cat_layout_title'))) || is_tag(explode(',',zm_get_option('cat_layout_title'))) ) {
	get_template_part( 'category-title' );
}
elseif ( is_category(explode(',',zm_get_option('cat_layout_square'))) || is_tag(explode(',',zm_get_option('cat_layout_square'))) ) {
	get_template_part( 'category-square' );
}
elseif ( is_category(explode(',',zm_get_option('cat_layout_line'))) || is_tag(explode(',',zm_get_option('cat_layout_line'))) ) {
	get_template_part( 'category-line' );
}
elseif ( is_category(explode(',',zm_get_option('cat_child_cover'))) || is_tag(explode(',',zm_get_option('cat_child_cover'))) ) {
	get_template_part( 'category-child-cover' );
}
elseif ( is_category(explode(',',zm_get_option('cat_layout_child'))) || is_tag(explode(',',zm_get_option('cat_layout_child'))) ) {
	get_template_part( 'category-child-nosidebar' );
}
elseif ( is_category(explode(',',zm_get_option('cat_layout_child_img'))) || is_tag(explode(',',zm_get_option('cat_layout_child_img'))) ) {
	get_template_part( 'category-child-img' );
}
elseif ( is_category(explode(',',zm_get_option('cat_layout_qa'))) || is_tag(explode(',',zm_get_option('cat_layout_qa'))) ) {
	get_template_part( 'category-beqa' );
}
else {
	get_template_part( 'archive-default' );
}
?>