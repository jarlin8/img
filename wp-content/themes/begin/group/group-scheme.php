<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( co_get_option( 'group_scheme' ) ) {
	if ( ! co_get_option( 'scheme_bg' ) || ( co_get_option( 'scheme_bg' ) == 'auto' ) ) {
		$bg = '';
	}
	if ( co_get_option( 'scheme_bg' ) == 'white' ) {
		$bg = ' group-white';
	}
	if ( co_get_option( 'scheme_bg' ) == 'gray' ) {
		$bg = ' group-gray';
	}
?>
<div class="g-row g-line group-scheme<?php echo $bg; ?>" <?php aos(); ?>>
	<div class="g-col">
		<div class="group-scheme-box">
			<div class="group-title" <?php aos_b(); ?>>
				<?php if ( ! co_get_option('group_scheme_t') == '' ) { ?>
					<h3><?php echo co_get_option( 'group_scheme_t' ); ?></h3>
				<?php } ?>
				<?php if ( ! co_get_option('group_scheme_des') == '' ) { ?>
					<div class="group-des"><?php echo co_get_option( 'group_scheme_des' ); ?></div>
				<?php } ?>
				<div class="clear"></div>
			</div>

			<div class="group-scheme-main">
				<?php 
					$scheme = ( array ) co_get_option( 'group_scheme_add' );
					foreach ( $scheme as $items ) {
				?>
					<div class="group-scheme-item group-scheme-<?php echo co_get_option( 'group_scheme_fl' ); ?>" style="height: <?php echo co_get_option( 'group_scheme_img_h' ); ?>px">
						<?php if ( ! empty( $items['group_scheme_img'] ) ) { ?>
							<div class="group-scheme-bg" style="background-image: url(<?php echo $items['group_scheme_img']; ?>)"></div>
						<?php } ?>

						<div class="group-scheme-area">
							<?php if ( ! empty( $items['group_scheme_url'] ) ) { ?><a class="group-scheme-url" target="_blank" href="<?php echo $items['group_scheme_url']; ?>"></a><?php } ?>
							<?php if ( ! empty( $items['group_scheme_number'] ) ) { ?>
								<div class="group-scheme-number">
									<?php echo $items['group_scheme_number']; ?>
								</div>
							<?php } ?>

							<?php if ( ! empty( $items['group_scheme_title'] ) ) { ?>
								<div class="group-scheme-title">
									<?php echo $items['group_scheme_title']; ?>
								</div>
							<?php } ?>

							<?php if ( ! empty( $items['group_scheme_text'] ) ) { ?>
								<div class="group-scheme-des<?php if ( co_get_option( 'group_scheme_text_hide' ) ) { ?> group-scheme-text-hide<?php } ?>">
								<?php echo wpautop( $items['group_scheme_text'] ); ?>
								</div>
							<?php } ?>

						</div>
					</div>
				<?php } ?>
			</div>
		</div>

		<?php co_help( $text = '公司主页 → 解决方案', $number = 'group_scheme_s' ); ?>
	</div>
</div>
<?php } ?>