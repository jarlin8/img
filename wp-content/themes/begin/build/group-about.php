<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_build( get_the_ID(), 'group_about' ) ) { ?>
<div class="group-about-bg g-row g-line" style="background: url('<?php echo be_build( get_the_ID(), 'group_about_bg' ); ?>') no-repeat center / cover;">
	<div class="group-about-rgb">
		<div class="group-about-dec" style="background: <?php echo be_build( get_the_ID(), 'group_about_color' ); ?>"></div>
		<div class="g-col">
			<div class="group-about-box" <?php aos_e(); ?>>
				<?php if ( be_build( get_the_ID(), 'group_about_t' ) ) { ?>
					<div class="group-about-title">
						<h3><?php echo be_build( get_the_ID(), 'group_about_t' ); ?></h3>
						<div class="clear"></div>
					</div>
				<?php } ?>

				<div class="group-about-content text-back be-text">
					<?php echo wpautop( be_build( get_the_ID(), 'group_about_content' ) ); ?>
				</div>

				<?php if ( be_build( get_the_ID(), 'group_about_more' ) ) { ?>
					<div class="group-about-more">
						<a href="<?php echo be_build( get_the_ID(), 'group_about_url' ); ?>" rel="bookmark" <?php echo goal(); ?>><?php echo be_build( get_the_ID(), 'group_about_more' ); ?></a>
					</div>
				<?php } ?>

			</div>
			<?php bu_help( $text = '关于本站', $number = 'group_about_s' ); ?>
		</div>
	</div>
</div>
<?php } ?>