<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_build( get_the_ID(), 'group_intro' ) ) {
	if ( ! be_build( get_the_ID(), 'intro_bg' ) || ( be_build( get_the_ID(), 'intro_bg' ) == 'auto' ) ) {
		$bg = '';
	}
	if ( be_build( get_the_ID(), 'intro_bg' ) == 'white' ) {
		$bg = ' group-white';
	}
	if ( be_build( get_the_ID(), 'intro_bg' ) == 'gray' ) {
		$bg = ' group-gray';
	}
?>
<div class="g-row g-line group-intro-line<?php echo $bg; ?>" <?php aos(); ?>>
	<div class="g-col">
		<div class="group-intro-box">
			<div class="group-title" <?php aos_b(); ?>>
				<?php if ( ! be_build( get_the_ID(), 'intro_t') == '' ) { ?>
					<h3><?php echo be_build( get_the_ID(), 'intro_t' ); ?></h3>
				<?php } ?>
				<?php if ( ! be_build( get_the_ID(), 'intro_des' ) == '' ) { ?>
					<div class="group-des"><?php echo be_build( get_the_ID(), 'intro_des' ); ?></div>
				<?php } ?>
				<div class="clear"></div>
			</div>
			<?php 
				$intro = ( array ) be_build( get_the_ID(), 'group_intro_item' );
				foreach ( $intro as $items ) {
			?>
				<div class="group-intro sx4 intro-<?php echo be_build( get_the_ID(), 'intro_f' ); ?>">
					<span class="boxs6">
						<div class="group-intro-item">
							<?php if ( ! empty( $items['group_intro_url'] ) ) { ?>
								<a class="group-intro-link" href="<?php echo $items['group_intro_url']; ?>" target="_blank" rel="external nofollow"></a>
							<?php } ?>
							<h3 class="group-intro-title" <?php aos_b(); ?>><?php echo $items['group_intro_title']; ?></h3>
							<?php if ( ! empty( $items['group_intro_txt'] ) ) { ?>
								<div class="group-intro-content<?php if ( be_build( get_the_ID(), 'group_intro_txt_c' ) ) { ?> group-intro-c<?php } ?>">
									<?php echo wpautop( $items['group_intro_txt'] ); ?>
								</div>
							<?php } ?>
						</div>
					</span>
				</div>
			<?php } ?>
			<?php co_help( $text = '公司主页 → 图文简介', $number = 'group_intro_s' ); ?>
			<div class="clear"></div>
		</div>
	</div>
</div>
<?php } ?>