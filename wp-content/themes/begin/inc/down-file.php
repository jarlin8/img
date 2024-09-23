<?php 
function begin_down_file() {
	global $post;
	$str = '';
	if ( get_post_meta( get_the_ID(), 'button1', true ) ) :
		$str.= '<div class="down-box"><div class="off-down"></div>';
		$str.= '<div class="down-file">';
		$str.= '<div class="down-file-t">' . sprintf(__( '文件下载', 'begin' )) . "</div>";
		$str.= '<div class="file-gg">' . stripslashes( zm_get_option('ad_down_file' ) ) . '</div>';
		$str.= '<div class="down-button-box"><div class="down-button">';

		if ( get_post_meta( get_the_ID(), 'button1', true ) ) :
			$button1 = get_post_meta( get_the_ID(), 'button1', true );
			$url1 = get_post_meta( get_the_ID(), 'url1', true);
			$str.= '<a href="' . $url1 .'" rel="external nofollow" target="_blank"><i class="be be-download"></i>' . $button1 . '</a>';
		endif;

		if ( get_post_meta( get_the_ID(), 'button2', true ) ) :
			$button1 = get_post_meta( get_the_ID(), 'button2', true );
			$url2 = get_post_meta( get_the_ID(), 'url2', true );
			$str.= '<a href="' . $url2 .'" rel="external nofollow" target="_blank"><i class="be be-download"></i>' . $button1 . '</a>';
		endif;

		$str.= '<div class="clear"></div></div></div></div></div>';
	endif;
	echo $str;
}
add_action('wp_footer', 'begin_down_file', 99);