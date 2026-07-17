<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( co_get_option( 'group_explain' ) ) { ?>
	<?php 
		$explain = ( array ) co_get_option( 'group_explain_item' );
		foreach ( $explain as $items ) {

		$args = [
			'auto'  => '',
			'white' => ' group-white',
			'gray'  => ' group-gray',
		];
		$bg = '';
		if ( isset( $items['explain_bg'] ) && isset( $args[$items['explain_bg']] ) ) {
			$bg = $args[$items['explain_bg']];
		}
	?>
		<div class="explain g-line<?php echo $bg; ?>" <?php aos(); ?>>
			<div class="g-row">
				<div class="g-col">
					<div class="section-box group-explain-wrap">
						<div class="group-title" <?php aos_b(); ?>>
							<?php if ( ! empty( $items['group_explain_t'] ) ) { ?>
								<h3><?php echo $items['group_explain_t']; ?></h3>
							<?php } ?>
							<?php if ( ! empty( $items['group_explain_des'] ) ) { ?>
								<div class="group-des"><?php echo $items['group_explain_des']; ?></div>
							<?php } ?>
							<div class="clear"></div>
						</div>
						<div class="group-explain-box">
							<div class="group-explain-img-box<?php if ( ! empty( $items['ex_thumbnail_b'] ) ) { ?> explain-img<?php } ?>" <?php aos_b(); ?>>
								<figure class="group-explain-img tup">
									<a href="<?php echo $items['group_explain_url']; ?>" rel="bookmark" <?php echo goal(); ?>><img src="<?php echo $items['ex_thumbnail_a']; ?>" alt="<?php echo $items['group_explain_t']; ?>"></a>
								</figure>

								<?php if ( ! empty( $items['ex_thumbnail_b'] ) ) { ?>
									<figure class="group-explain-img tup">
										<a href="<?php echo $items['group_explain_url']; ?>" rel="bookmark" <?php echo goal(); ?>><img src="<?php echo $items['ex_thumbnail_b']; ?>" alt="<?php echo $items['group_explain_t']; ?>"></a>
									</figure>
								<?php } ?>
							</div>

							<div class="group-explain">
								<div class="group-explain-main edit-buts single-content sanitize" <?php aos_f(); ?>>
									<?php if ( ! empty( $items['explain_p'] ) ) { ?>
										<div class="be-text<?php if ( ! empty( $items['explain_indent'] ) ) { ?> text-back<?php } ?>"><?php echo wpautop( $items['explain_p'] ); ?></div>
									<?php } ?>
									<div class="clear"></div>
									<?php if ( ! empty( $items['group_explain_more'] ) ) { ?>
										<div class="group-explain-more">
											<a href="<?php echo $items['group_explain_url']; ?>" title="<?php _e( '详细查看', 'begin' ); ?>" rel="bookmark" <?php echo goal(); ?>>
												<?php echo $items['group_explain_more']; ?>
											</a>
										</div>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
					<?php co_help( $text = '公司主页 → 说明', $number = 'group_explain_s' ); ?>
				</div>
			</div>
		</div>
	<?php } ?>
<?php } ?>