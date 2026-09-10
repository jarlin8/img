<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_build( get_the_ID(), 'group_scheme' ) ) {
	if ( ! be_build( get_the_ID(), 'scheme_bg' ) || ( be_build( get_the_ID(), 'scheme_bg' ) == 'auto' ) ) {
		$bg = '';
	}
	if ( be_build( get_the_ID(), 'scheme_bg' ) == 'white' ) {
		$bg = ' group-white';
	}
	if ( be_build( get_the_ID(), 'scheme_bg' ) == 'gray' ) {
		$bg = ' group-gray';
	}
?>
<div class="g-row g-line group-scheme<?php echo $bg; ?>" <?php aos(); ?>>
	<div class="g-col">
		<div class="group-scheme-box">
			<div class="group-title" <?php aos_b(); ?>>
				<?php if ( ! be_build( get_the_ID(),'group_scheme_t') == '' ) { ?>
					<h3><?php echo be_build( get_the_ID(), 'group_scheme_t' ); ?></h3>
				<?php } ?>
				<?php if ( ! be_build( get_the_ID(),'group_scheme_des') == '' ) { ?>
					<div class="group-des"><?php echo be_build( get_the_ID(), 'group_scheme_des' ); ?></div>
				<?php } ?>
				<div class="clear"></div>
			</div>

			<div class="group-scheme-main">
				<?php 
					$scheme = ( array ) be_build( get_the_ID(), 'group_scheme_add' );
					foreach ( $scheme as $items ) {
				?>
					<div class="group-scheme-item group-scheme-<?php echo be_build( get_the_ID(), 'group_scheme_fl' ); ?>" style="height: <?php echo be_build( get_the_ID(), 'group_scheme_img_h' ); ?>px">
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
								<div class="group-scheme-des<?php if ( be_build( get_the_ID(), 'group_scheme_text_hide' ) ) { ?> group-scheme-text-hide<?php } ?>">
								<?php echo wpautop( $items['group_scheme_text'] ); ?>
								</div>
							<?php } ?>

						</div>
					</div>
				<?php } ?>
			</div>
		</div>

		<?php bu_help( $text = '解决方案', $number = 'group_scheme_s' ); ?>
	</div>
</div>
<?php } ?>