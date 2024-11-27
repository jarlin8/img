<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( co_get_option( 'group_ajax_cat' ) ) {
	if ( ! co_get_option( 'ajax_bg' ) || ( co_get_option( 'ajax_bg' ) == 'auto' ) ) {
		$bg = '';
	}
	if ( co_get_option( 'ajax_bg' ) == 'white' ) {
		$bg = ' group-white';
	}
	if ( co_get_option( 'ajax_bg' ) == 'gray' ) {
		$bg = ' group-gray';
	}
?>
	<div class="g-row g-line group-ajax-cat-post<?php echo $bg; ?>">
		<div class="g-col">
			<?php echo do_shortcode( co_get_option( 'group_ajax_cat_post_code' ) ); ?>
			<?php co_help( $text = '公司主页 → 分类短代码', $number = 'group_ajax_cat_post_s' ); ?>
		</div>
	</div>
<?php } ?>