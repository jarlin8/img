<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_build( get_the_ID(), 'group_tool' ) ) {
	if ( ! be_build( get_the_ID(), 'tool_bg' ) || ( be_build( get_the_ID(), 'tool_bg' ) == 'auto' ) ) {
		$bg = '';
	}
	if ( be_build( get_the_ID(), 'tool_bg' ) == 'white' ) {
		$bg = ' group-white';
	}
	if ( be_build( get_the_ID(), 'tool_bg' ) == 'gray' ) {
		$bg = ' group-gray';
	}
?>
<div class="g-row g-line group-tool-line<?php echo $bg; ?>" <?php aos(); ?>>
	<div class="g-col">
		<div class="group-tool-box">
			<div class="group-title" <?php aos_b(); ?>>
				<?php if ( ! be_build( get_the_ID(),'tool_t') == '' ) { ?>
					<h3><?php echo be_build( get_the_ID(),'tool_t'); ?></h3>
				<?php } ?>
				<?php if ( ! be_build( get_the_ID(),'tool_des') == '' ) { ?>
					<div class="group-des"><?php echo be_build( get_the_ID(),'tool_des'); ?></div>
				<?php } ?>
				<div class="clear"></div>
			</div>
			<?php 
				$tool = ( array ) be_build( get_the_ID(), 'group_tool_item' );
				foreach ( $tool as $items ) {
			?>
				<div class="sx4 edit-buts stool-<?php echo be_build( get_the_ID(),'stool_f'); ?>">
					<div class="boxs1">
						<div class="group-tool<?php if ( ! empty( $items['group_tool_btn'] ) ) { ?> group-tool-have<?php } ?>">
							<div class="group-tool-img">
								<?php if ( ! empty( $items['group_tool_img'] ) ) { ?>
									<div class="group-tool-img-top"></div>
									<div class="img40 lazy"><div class="bgimg" style="background-image: url(<?php echo $items['group_tool_img']; ?>) !important;"></div></div>
								<?php } ?>
							</div>

							<div class="group-tool-pu">
								<div class="group-tool-ico" <?php aos_b(); ?>>
									<?php if ( ! empty( $items['group_tool_svg'] ) ) { ?>
										<svg class="icon" aria-hidden="true"><use xlink:href="#<?php echo $items['group_tool_svg']; ?>"></use></svg>
									<?php } ?>
									<?php if ( ! empty( $items['group_tool_ico'] ) ) { ?>
										<i class="<?php echo $items['group_tool_ico']; ?>"></i>
									<?php } ?>
								</div>
								<?php if ( empty( $items['group_tool_btn'] ) ) { ?>
									<h3 class="group-tool-title" <?php aos_b(); ?>><?php echo $items['group_tool_title']; ?></h3>
								<?php } else { ?>
									<a href="<?php echo $items['group_tool_url']; ?>" target="_blank" rel="external nofollow"><h3 class="group-tool-title"><?php echo $items['group_tool_title']; ?></h3></a>
								<?php } ?>

								<?php if ( ! empty( $items['group_tool_txt'] ) ) { ?>
									<div class="group-tool-p<?php if ( be_build( get_the_ID(), 'group_tool_txt_c' ) ) { ?> group-tool-c<?php } ?><?php if ( be_build( get_the_ID(), 'group_tool_txt_h' ) ) { ?> group-tool-h<?php } ?>">
										<?php echo wpautop( $items['group_tool_txt'] ); ?>
									</div>
								<?php } ?>
							</div>
							<?php if ( ! empty( $items['group_tool_btn'] ) ) { ?>
								<div class="group-tool-link"><a href="<?php echo $items['group_tool_url']; ?>" target="_blank" rel="external nofollow" data-hover="<?php echo $items['group_tool_btn']; ?>"><span><i class="be be-more"></i></span></a></div>
							<?php } ?>
						</div>
					</div>
				</div>
			<?php } ?>
			<?php bu_help( $text = '工具', $number = 'tool_s' ); ?>
		</div>
	</div>
</div>
<?php } ?>