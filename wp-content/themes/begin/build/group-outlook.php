<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_build( get_the_ID(), 'group_outlook' ) ) { ?>
<div class="group-outlook-bg g-row g-line" style="background: url('<?php echo be_build( get_the_ID(), 'group_outlook_bg' ); ?>') no-repeat center / cover;" <?php aos(); ?>>
	<div class="group-outlook-rgb">
		<div class="group-outlook-dec"></div>
		<div class="g-col">
			<div class="group-outlook-box">
				<?php if ( be_build( get_the_ID(), 'group_outlook_t' ) ) { ?>
					<div class="group-outlook-title" <?php aos_b(); ?>>
						<h3><?php echo be_build( get_the_ID(), 'group_outlook_t' ); ?></h3>
						<div class="clear"></div>
					</div>
				<?php } ?>

				<div class="group-outlook-content text-back be-text" <?php aos_b(); ?>>
					<?php echo wpautop( be_build( get_the_ID(), 'group_outlook_content' ) ); ?>
				</div>

				<?php if ( be_build( get_the_ID(), 'group_outlook_more' ) ) { ?>
					<div class="group-outlook-more" <?php aos_b(); ?>>
						<a class="tup" href="<?php echo be_build( get_the_ID(), 'group_outlook_url' ); ?>" target="_blank" rel="external nofollow"><?php echo be_build( get_the_ID(), 'group_outlook_more' ); ?></a>
						<?php if ( be_build( get_the_ID(), 'group_outlook_btn' ) ) { ?>
							<a class="group-outlook-btn tup" href="<?php echo be_build( get_the_ID(), 'group_btn_url' ); ?>" target="_blank" rel="external nofollow"><?php echo be_build( get_the_ID(), 'group_outlook_btn' ); ?></a>
						<?php } ?>
					</div>
				<?php } ?>

			</div>
			<?php bu_help( $text = '展望', $number = 'group_outlook_s' ); ?>
		</div>
	</div>
	<?php if ( be_build( get_the_ID(), 'group_outlook_water' ) ) { ?>
		<?php waves(); ?>
	<?php } ?>
</div>
<?php } ?>