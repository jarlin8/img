<?php if ( ! defined( 'ABSPATH' ) ) exit;
if ( be_build( get_the_ID(), 'group_contact' ) ) {
	$items = $args['items'];

	$args = [
		'auto'  => '',
		'white' => ' group-white',
		'gray'  => ' group-gray',
	];
	$bg = '';
	if ( isset( $items['contact_bg'] ) && isset( $args[$items['contact_bg']] ) ) {
		$bg = $args[$items['contact_bg']];
	}
?>
	<div class="g-row g-line sort contact<?php echo $bg; ?>" <?php aos(); ?>>
		<div class="g-col">
			<div class="section-box group-contact-wrap">
				<div class="group-title" <?php aos_b(); ?>>
					<?php if ( ! empty( $items['group_contact_t'] ) ) { ?>
						<h3><?php echo $items['group_contact_t']; ?></h3>
					<?php } ?>
					<div class="clear"></div>
				</div>
				<div class="group-contact be-text sanitize<?php if ( $items['contact_img_m'] == 'contact_img_right' ) { ?> group-contact-lr<?php } ?><?php if ( $items['contact_img_m'] == 'contact_img_left' ) { ?> group-contact-ll<?php } ?>">
					<div class="single-content text-back<?php if ( $items['tr_contact'] ) { ?> group-contact-main<?php } else { ?> group-contact-main-all<?php } ?>" <?php aos_f(); ?>>
						<?php echo wpautop( $items['contact_p'] ); ?>
					</div>
					<?php if ( ! empty( $items['group_contact_bg'] ) ) { ?>
						<div class="group-contact-img tup" <?php aos_b(); ?>>
							<img src="<?php echo $items['group_contact_img']; ?>" alt="<?php echo $items['group_contact_t']; ?>">
						</div>
					<?php } ?>

					<div class="clear"></div>
					<?php if ( ! empty( $items['group_more_z'] ) || ! empty( $items['group_contact_z'] ) ) { ?>
						<div class="group-contact-more">
							<?php if ( ! empty( $items['group_more_z'] ) ) { ?>
								<span class="group-more" <?php aos_b(); ?>>
									<?php if ( ! empty( $items['group_more_url'] ) ) { ?>
										<a href="<?php echo $items['group_more_url']; ?>" rel="bookmark" <?php echo goal(); ?>><?php if ( ! empty( $items['group_more_ico'] ) ) { ?><i class="<?php echo $items['group_more_ico']; ?>"></i><?php } ?><?php echo $items['group_more_z']; ?></a>
									<?php } ?>
								</span>
							<?php } ?>
							<?php if ( ! empty( $items['group_contact_z'] ) ) { ?><span class="group-phone" <?php aos_b(); ?>><a href="<?php echo $items['group_contact_url']; ?>" rel="bookmark" <?php echo goal(); ?>><?php if ( ! empty( $items['group_contact_ico'] ) ) { ?><i class="<?php echo $items['group_contact_ico']; ?>"></i><?php } ?><?php echo $items['group_contact_z']; ?></a></span><?php } ?>
							<div class="clear"></div>
						</div>
					<?php } ?>
				</div>
				<div class="clear"></div>
			</div>
			<?php co_help_n( $text = '关于我们', $items['group_contact_s'] ); ?>
			<div class="clear"></div>
		</div>
	</div>
<?php } ?>