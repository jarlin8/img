<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_build( get_the_ID(), 'group_email' ) ) { ?>
	<div class="g-row g-line sort group_email" style="background: url('<?php echo be_build( get_the_ID(), 'group_email_bg' ); ?>') no-repeat fixed center / cover;" <?php aos(); ?>>
		<div class="g-col">
			<div class="section-box group-email-wrap">
				<div class="group-title" <?php aos_b(); ?>>
					<?php if ( ! be_build( get_the_ID(), 'group_email_t' ) == '' ) { ?>
						<h3><?php echo be_build( get_the_ID(), 'group_email_t' ); ?></h3>
					<?php } ?>
				</div>
				<div class="group-email-main">
					<div class="group-email-item group-email-inf" <?php aos_f(); ?>>
						<?php echo wpautop(be_build( get_the_ID(), 'group_email_inf' ) ); ?>
					</div>
					<div class="group-email-item group-email-form">
						<?php echo be_display_contact_form(); ?>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			<?php bu_help( $text = '联系我们', $number = 'group_email_s' ); ?>
			<div class="clear"></div>
		</div>
	</div>
<?php } ?>