<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_get_option( 'cms_ads' ) ) {
	$items = $args['items'];
?>

	<div class="reuse-tgs cms-reuse-tgs betip">
		<?php if ( ! empty( $items['cms_ads_txt'] ) ) { ?>
			<?php echo $items['cms_ads_txt']; ?>
		<?php } ?>
		<?php if ( ! empty( $items['cms_ads_visual'] ) ) { ?>
			<?php echo $items['cms_ads_visual']; ?>
		<?php } ?>
		<?php cms_help_n( $text = '杂志布局 → 广告信息', $items['cms_ads_s'] ); ?>
	</div>

<?php } ?>