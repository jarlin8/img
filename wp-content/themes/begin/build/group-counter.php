<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_build( get_the_ID(), 'group_counter' ) ) { ?>
	<div class="line-counter g-row" style="background: url('<?php echo be_build( get_the_ID(), 'group_counter_bg' ); ?>') no-repeat fixed center / cover;" <?php aos(); ?>>
		<div class="g-col">
			<div class="group-counter-box">
				<?php 
					$i = 0;
					$counter = ( array ) be_build( get_the_ID(), 'group_counter_item' );
					foreach ( $counter as $items ) {
					$i++;
				?>
					<div class="group-counter-main g-c-m g-c-m-<?php echo be_build( get_the_ID(), 'group_counter_f' ); ?> be_count_<?php echo $i; ?>">
						<div class="group-counters-icon">
							<?php if ( ! empty( $items['group_counter_ico'] ) ) { ?>
								<i class="<?php echo $items['group_counter_ico']; ?>" style="color:<?php echo $items['group_counter_color']; ?>"></i>
							<?php } ?>
						</div>
						<div class="group-counters-item">
							<div class="counters" <?php aos_b(); ?>>
								<?php if ( ! empty( $items['group_counter_num'] ) ) { ?>
									<div class="counter" data-targetnum="<?php echo $items['group_counter_num']; ?>" data-speed="<?php echo $items['group_counter_speed']; ?>">0</div>
									<?php if ( ! empty( $items['group_counter_unit'] ) ) { ?>
										<i class="group-counter-unit"><?php echo $items['group_counter_unit']; ?></i>
									<?php } ?>
								<?php } ?>
							</div>
							<?php if ( ! empty( $items['group_counter_title'] ) ) { ?>
								<div class="group-counter-title"><?php echo $items['group_counter_title']; ?></div>
							<?php } ?>
						</div>
					</div>
				<?php } ?>
				<?php bu_help( $text = '计数器', $number = 'counter_s' ); ?>
			</div>
		</div>
	</div>
<?php } ?>