<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( zm_get_option( 'news_ico' ) ) { ?>
	<?php
		$t1 = $post->post_date;
		$t2 = date( "Y-m-d H:i:s" );
		$t3 = zm_get_option( 'new_n' );
		$newest = ( strtotime( $t2 )-strtotime( $t1 ) )/3600;
		if ( $newest < $t3 ) {
	?>
		<span class="new-icon fd"><i class="be be-new"></i></span>
	<?php } ?>
<?php } ?>