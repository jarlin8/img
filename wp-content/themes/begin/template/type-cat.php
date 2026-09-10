<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if ( is_tax('videos') ) {
	$terms = get_terms("videos");
}
if ( is_tax('gallery') ) {
	$terms = get_terms("gallery");
}
if ( is_tax('taobao') ) {
	$terms = get_terms("taobao");
}

if ( is_tax('products') ) {
	$terms = get_terms("products");
}

$count = count($terms);
if ( $count > 0 ){
	echo '<ul class="type-cat child-cat child-cat-' . zm_get_option('child_cat_f') . '">';
	foreach ( $terms as $term ) {
		echo '<li class="child-cat-item"><a class="ms" href="' . get_term_link( $term ) . '" >' . $term->name . '</a></li>';
	}
	echo '</ul>';
	echo '<div class="clear"></div>';
}
?>