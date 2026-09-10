<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_build( get_the_ID(), 'group_ajax_cat' ) ) {
	if ( ! be_build( get_the_ID(), 'ajax_bg' ) || ( be_build( get_the_ID(), 'ajax_bg' ) == 'auto' ) ) {
		$bg = '';
	}
	if ( be_build( get_the_ID(), 'ajax_bg' ) == 'white' ) {
		$bg = ' group-white';
	}
	if ( be_build( get_the_ID(), 'ajax_bg' ) == 'gray' ) {
		$bg = ' group-gray';
	}
?>
	<div class="g-row g-line group-ajax-cat-post<?php echo $bg; ?>">
		<div class="g-col">
			<?php echo do_shortcode( be_build( get_the_ID(), 'group_ajax_cat_post_code' ) ); ?>
			<?php bu_help( $text = '分类短代码', $number = 'group_ajax_cat_post_s' ); ?>
		</div>
	</div>
<?php } ?>