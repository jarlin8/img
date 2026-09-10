<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_build( get_the_ID(), 'group_process' )) {
	if ( ! be_build( get_the_ID(), 'process_bg' ) || ( be_build( get_the_ID(), 'process_bg' ) == 'auto' ) ) {
		$bg = '';
	}
	if ( be_build( get_the_ID(), 'process_bg' ) == 'white' ) {
		$bg = ' group-white';
	}
	if ( be_build( get_the_ID(), 'process_bg' ) == 'gray' ) {
		$bg = ' group-gray';
	}
?>
<div class="g-row g-line group-process-line<?php echo $bg; ?>" <?php aos(); ?>>
	<div class="g-col">
		<div class="group-process-box">
			<div class="group-title" <?php aos_b(); ?>>
				<?php if ( ! be_build( get_the_ID(), 'process_t') == '' ) { ?>
					<h3><?php echo be_build( get_the_ID(), 'process_t' ); ?></h3>
				<?php } ?>
				<?php if ( ! be_build( get_the_ID(), 'process_des') == '' ) { ?>
					<div class="group-des"><?php echo be_build( get_the_ID(), 'process_des' ); ?></div>
				<?php } ?>
				<div class="clear"></div>
			</div>
			<div class="group-process-wrap">
				<?php 
					$i = 0;
					$process = ( array ) be_build( get_the_ID(), 'group_process_item' );
					foreach ( $process as $items ) {
					$i++;
				?>
					<div class="process-main ess">
						<div class="group-process tup<?php if ( ! empty( $items['group_process_des'] ) ) { ?> group-process-box<?php } ?>">
							<div class="process-round round round-<?php echo $i; ?>" style="border-left: 5px solid <?php echo $items['group_process_color']; ?>"></div>
							<?php if ( be_build( get_the_ID(), 'process_order' ) ) { ?>
								<div class="group-process-order ces" <?php aos_b(); ?>><?php echo $i; ?></div>
							<?php } ?>
							<div class="group-process-ico" <?php aos_b(); ?>>
								<?php if ( ! empty( $items['group_process_ico'] ) ) { ?>
									<i class="ces <?php echo $items['group_process_ico']; ?>"></i>
								<?php } ?>
							</div>

							<h3 class="group-process-title ces" <?php aos_f(); ?>>
								<?php if ( ! empty( $items['group_process_title'] ) ) { ?>
									<?php echo $items['group_process_title']; ?>
								<?php } ?>
							</h3>
						</div>

						<?php if ( ! empty( $items['group_process_des'] ) ) { ?>
							<div class="group-process-explain">
								<div class="group-process-explain-main" style="border: 2px solid <?php echo $items['group_process_color']; ?>">
								<?php if ( ! empty( $items['group_process_title'] ) ) { ?>
									<h3 class="group-process-explain-title"><?php echo $items['group_process_title']; ?></h3>
								<?php } ?>
									<div class="group-process-des"><?php echo $items['group_process_des']; ?></div>
								</div>
							</div>
						<?php } ?>

					</div>
				<?php } ?>
			</div>
		</div>
		<?php bu_help( $text = '流程', $number = 'process_s' ); ?>
	</div>
</div>
<?php } ?>