<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( co_get_option( 'group_outlook' ) ) { ?>
<div class="group-outlook-bg g-row g-line" style="background: url('<?php echo co_get_option( 'group_outlook_bg' ); ?>') no-repeat center / cover;" <?php aos(); ?>>
	<div class="group-outlook-rgb">
		<div class="group-outlook-dec"></div>
		<div class="g-col">
			<div class="group-outlook-box">
				<?php if ( co_get_option( 'group_outlook_t' ) ) { ?>
					<div class="group-outlook-title" <?php aos_b(); ?>>
						<h3><?php echo co_get_option( 'group_outlook_t' ); ?></h3>
						<div class="clear"></div>
					</div>
				<?php } ?>

				<div class="group-outlook-content text-back be-text" <?php aos_b(); ?>>
					<?php echo wpautop( co_get_option( 'group_outlook_content' ) ); ?>
				</div>

				<?php if ( co_get_option( 'group_outlook_more' ) ) { ?>
					<div class="group-outlook-more" <?php aos_b(); ?>>
						<a class="tup" href="<?php echo co_get_option( 'group_outlook_url' ); ?>" target="_blank" rel="external nofollow"><?php echo co_get_option( 'group_outlook_more' ); ?></a>
						<?php if ( co_get_option( 'group_outlook_btn' ) ) { ?>
							<a class="group-outlook-btn tup" href="<?php echo co_get_option( 'group_btn_url' ); ?>" target="_blank" rel="external nofollow"><?php echo co_get_option( 'group_outlook_btn' ); ?></a>
						<?php } ?>
					</div>
				<?php } ?>

			</div>
			<?php co_help( $text = '公司主页 → 展望', $number = 'group_outlook_s' ); ?>
		</div>
	</div>
	<?php if ( co_get_option( 'group_outlook_water' ) ) { ?>
		<?php waves(); ?>
	<?php } ?>
</div>
<?php } ?>