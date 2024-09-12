<?php if ( ! defined( 'ABSPATH' ) ) exit;
$tag_ids = wp_get_post_tags( get_the_ID(), array( 'fields' => 'ids' ) ); ?>
<?php if ( $tag_ids ) { ?>
<div class="single-code-tag">
<?php 
	$tags_id = implode( ',', $tag_ids );
	echo do_shortcode( '[be_ajax_post column="' . zm_get_option( 'single_tab_tags_f' ) . '" cat="' . $tags_id . '" style="' . zm_get_option( 'single_tab_tags_style' ) . '" posts_per_page="' . zm_get_option( 'single_tab_tags_n' ) . '" orderby="' . zm_get_option( 'single_tab_tags_order' ) . '" order="DESC" tags="tag" btn_all="no" more="more"]' );
?>
</div>
<?php } ?>