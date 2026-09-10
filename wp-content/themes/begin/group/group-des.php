<?php if ( ! defined( 'ABSPATH' ) ) exit;
if ( co_get_option( 'group_des' ) ) {
	$items = $args['items'];
	$args = [
		'auto'  => '',
		'white' => ' group-white',
		'gray'  => ' group-gray',
	];
	$bg = '';
	if ( isset( $items['des_bg'] ) && isset( $args[$items['des_bg']] ) ) {
		$bg = $args[$items['des_bg']];
	}
?>

	<div class="g-row g-line sort group-des-wrappe<?php echo $bg; ?>" <?php aos(); ?>>
		<div class="g-col">
			<div class="section-box group-des-item<?php if ( $items['group_des_img_m'] == 'right' ) { ?> group-des-img-r<?php } ?><?php if ( $items['group_des_img_m'] == 'left' ) { ?> group-des-img-l<?php } ?>">
				<div class="group-des-area group-des-img" <?php aos_b(); ?>>
					<div class="group-des-img-box tup" <?php aos_b(); ?>>
						<?php if ( ! empty( $items['group_des_img'] ) ) { ?>
							<img alt="<?php if ( ! empty( $items['group_des_t'] ) ) { ?><?php echo $items['group_des_t']; ?><?php } ?>" src="<?php echo $items['group_des_img']; ?>">
						<?php } ?>
					</div>
				</div>
				<div class="group-des-area group-des-content">
					<div class="group-des-text-box">
						<?php if ( ! empty( $items['group_des_t'] ) ) { ?>
							<h3 class="group-des-title"><?php echo $items['group_des_t']; ?></h3>
						<?php } ?>

						<div class="group-des-text<?php if ( ! empty( $items['group_des_indent'] ) ) { ?> text-back<?php } ?>" <?php aos_b(); ?>>
							<?php if ( ! empty( $items['group_des_text'] ) ) { ?>
								<?php echo wpautop( $items['group_des_text'] ); ?>
							<?php } ?>
						</div>
						<div class="group-des-btn" <?php aos_b(); ?>>
							<?php if ( ! empty( $items['group_des_btn'] ) ) { ?>
								<a href="<?php echo $items['group_des_btn_url']; ?>" rel="bookmark" <?php echo goal(); ?>><?php echo $items['group_des_btn']; ?></a>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
			<?php co_help_n( $text = '公司主页 → 图片说明', $items['group_des_s'] ); ?>
			<div class="clear"></div>
		</div>
	</div>
<?php } ?>