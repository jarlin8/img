<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// 特色模块
function grid_md_cms() { ?>
<div class="grid-md grid-md<?php echo be_get_option('grid_ico_cms_n'); ?> betip">
	<div class="md-main">
		<?php 
			$ico = (array) be_get_option( 'cms_ico_item' );
			foreach ( $ico as $items ) {
		?>
			<div class="gw-box edit-buts gw-box<?php echo be_get_option('grid_ico_cms_n'); ?>" <?php aos_a(); ?>>
				<div class="boxs6">
					<div class="gw-main tra ms gw-main-<?php if ( be_get_option('cms_ico_b')) { ?>b<?php } ?>">
						<div class="gw-area" <?php aos_b(); ?>>
							<?php if ( ! empty( $items['cms_ico_url'] ) ) { ?><a class="gw-link" href="<?php echo $items['cms_ico_url']; ?>" rel="bookmark"><?php } ?>
							<?php if ( ! empty( $items['cms_ico_img'] ) ) { ?>
								<div class="gw-img">
									<div class="img100 lazy"><div class="bgimg" style="background-image: url(<?php echo $items['cms_ico_img']; ?>) !important;"></div></div>
								</div>
							<?php } ?>
							<?php if ( ! empty( $items['cms_ico_svg'] ) ) { ?>
								<div class="gw-ico"><svg class="icon" aria-hidden="true"><use xlink:href="#<?php echo $items['cms_ico_svg']; ?>"></use></svg></div>
							<?php } ?>
							<?php if ( ! empty( $items['cms_ico_ico'] ) ) { ?>
								<?php if ( be_get_option( 'cms_ico_b' ) ) { ?>
								<div class="gw-ico"><i class="<?php echo $items['cms_ico_ico']; ?>" style="color:<?php echo $items['cms_ico_color']; ?>"></i></div>
								<?php } else { ?>
								<div class="gw-ico"><i class="<?php echo $items['cms_ico_ico']; ?>" style="background:<?php echo $items['cms_ico_color']; ?>"></i></div>
								<?php } ?>
							<?php } ?>
							<?php if ( ! empty( $items['cms_ico_title'] ) ) { ?>
								<h3 class="gw-title"><?php echo $items['cms_ico_title']; ?></h3>
							<?php } ?>

							<?php if ( ! empty( $items['cms_ico_url'] ) ) { ?></a><?php } ?>
							<?php if ( ! empty( $items['cms_ico_txt'] ) ) { ?>
								<div class="gw-content"><?php echo $items['cms_ico_txt']; ?></div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
		<div class="clear"></div>
	</div>
	<?php cms_help( $text = '首页设置 → 杂志布局 → 特色模块', $number = 'grid_ico_cms_s' ); ?>
	<div class="clear"></div>
</div>
<?php }

function grid_md_group() {
	if ( ! co_get_option( 'ico_bg' ) || ( co_get_option( 'ico_bg' ) == 'auto' ) ) {
		$bg = '';
	}
	if ( co_get_option( 'ico_bg' ) == 'white' ) {
		$bg = ' group-white';
	}
	if ( co_get_option( 'ico_bg' ) == 'gray' ) {
		$bg = ' group-gray';
	}
?>
<div class="g-row g-line<?php if ( co_get_option('group_ico_img' ) ) { ?> gw-only<?php } else { ?> g-stress<?php } ?><?php echo $bg; ?>">
	<div class="g-col">
		<div class="grid-md grid-md<?php echo co_get_option('grid_ico_group_n'); ?>">
			<div class="group-title" <?php aos_b(); ?>>
				<?php if ( co_get_option( 'group_ico_t' ) == '' ) { ?>
				<?php } else { ?>
					<h3><?php echo co_get_option( 'group_ico_t' ); ?></h3>
					<div class="separator"></div>
				<?php } ?>
				<div class="group-des"><?php echo co_get_option( 'group_ico_des' ); ?></div>
				<div class="clear"></div>
			</div>
			<div class="md-main">
				<?php 
					$ico = (array) co_get_option( 'group_ico_item' );
					foreach ( $ico as $items ) {
				?>
				<div class="gw-box edit-buts gw-box<?php echo co_get_option( 'grid_ico_group_n' ); ?><?php if ( co_get_option( 'group_img_ico' ) ) { ?> gw-img-only-bk<?php } ?>" <?php aos_c(); ?>>
					<div class="boxs6">
						<div class="gw-main gw-main-<?php if ( co_get_option( 'group_ico_b' ) ) { ?>b<?php } ?>">
							<?php if ( ! co_get_option('group_img_ico')) { ?>
								<div class="gw-area" <?php aos_b(); ?>>
									<?php if ( ! empty( $items['group_ico_url'] ) ) { ?><a class="gw-link" href="<?php echo $items['group_ico_url']; ?>" rel="bookmark"><?php } ?>

									<?php if ( ! empty( $items['group_ico_img'] ) ) { ?>
										<?php if ( co_get_option( 'group_img_ico' ) ) { ?>
											<div class="gw-img-only<?php if ( co_get_option( 'group_md_gray' ) ) { ?> img-gray<?php } ?>"><img src="<?php echo $items['group_ico_img']; ?>" alt="<?php echo $items['group_ico_title']; ?>"></div>
										<?php } else { ?>
											<div class="gw-img">
												<div class="img100 lazy"><div class="bgimg" style="background-image: url(<?php echo $items['group_ico_img']; ?>) !important;"></div></div>
											</div>
										<?php } ?>
									<?php } ?>
									<?php if ( ! empty( $items['group_ico_svg'] ) ) { ?>
										<div class="gw-ico"><svg class="icon" aria-hidden="true"><use xlink:href="#<?php echo $items['group_ico_svg']; ?>"></use></svg></div>
									<?php } ?>
									<?php if ( ! empty( $items['group_ico_ico'] ) ) { ?>
										<?php if ( co_get_option( 'group_ico_b' ) ) { ?>
										<div class="gw-ico"><i class="<?php echo $items['group_ico_ico']; ?>" style="color:<?php echo $items['group_ico_color']; ?>"></i></div>
										<?php } else { ?>
										<div class="gw-ico"><i class="<?php echo $items['group_ico_ico']; ?>" style="background:<?php echo $items['group_ico_color']; ?>"></i></div>
										<?php } ?>
									<?php } ?>
									<?php if ( ! empty( $items['group_ico_title'] ) ) { ?>
										<h3 class="gw-title"><?php echo $items['group_ico_title']; ?></h3>
									<?php } ?>
									<?php if ( ! empty( $items['group_ico_url'] ) ) { ?></a><?php } ?>
									<?php if ( ! empty( $items['group_ico_txt'] ) ) { ?>
										<div class="gw-content"><?php echo $items['group_ico_txt']; ?></div>
									<?php } ?>
								</div>
							<?php } else { ?>
								<?php if ( ! empty( $items['group_ico_url'] ) ) { ?></a><?php } ?>
							<?php } ?>
						</div>
					</div>
				</div>
				<?php } ?>
				<div class="clear"></div>
			</div>
		</div>
		<?php co_help( $text = '公司主页 → 特色', $number = 'group_ico_s' ); ?>
	</div>
</div>
<?php }


function grid_md_build() {
	if ( ! be_build( get_the_ID(), 'ico_bg' ) || ( be_build( get_the_ID(), 'ico_bg' ) == 'auto' ) ) {
		$bg = '';
	}
	if ( be_build( get_the_ID(), 'ico_bg' ) == 'white' ) {
		$bg = ' group-white';
	}
	if ( be_build( get_the_ID(), 'ico_bg' ) == 'gray' ) {
		$bg = ' group-gray';
	}
?>
<div class="g-row g-line<?php if ( be_build( get_the_ID(), 'group_ico_img' ) ) { ?> gw-only<?php } else { ?> g-stress<?php } ?><?php echo $bg; ?>">
	<div class="g-col">
		<div class="grid-md grid-md<?php echo be_build( get_the_ID(), 'grid_ico_group_n'); ?>">
			<div class="group-title" <?php aos_b(); ?>>
				<?php if ( be_build( get_the_ID(), 'group_ico_t' ) == '' ) { ?>
				<?php } else { ?>
					<h3><?php echo be_build( get_the_ID(), 'group_ico_t' ); ?></h3>
					<div class="separator"></div>
				<?php } ?>
				<div class="group-des"><?php echo be_build( get_the_ID(), 'group_ico_des' ); ?></div>
				<div class="clear"></div>
			</div>
			<div class="md-main">
				<?php 
					$ico = (array) be_build( get_the_ID(), 'group_ico_item' );
					foreach ( $ico as $items ) {
				?>
				<div class="gw-box edit-buts gw-box<?php echo be_build( get_the_ID(), 'grid_ico_group_n' ); ?><?php if ( be_build( get_the_ID(), 'group_img_ico' ) ) { ?> gw-img-only-bk<?php } ?>" <?php aos_c(); ?>>
					<div class="boxs6">
						<div class="gw-main ms gw-main-<?php if ( be_build( get_the_ID(), 'group_ico_b' ) ) { ?>b<?php } ?>">
							<?php if ( ! be_build( get_the_ID(), 'group_img_ico')) { ?>
								<div class="gw-area" <?php aos_b(); ?>>
									<?php if ( ! empty( $items['group_ico_url'] ) ) { ?><a class="gw-link" href="<?php echo $items['group_ico_url']; ?>" rel="bookmark"><?php } ?>

									<?php if ( ! empty( $items['group_ico_img'] ) ) { ?>
										<?php if ( be_build( get_the_ID(), 'group_img_ico' ) ) { ?>
											<div class="gw-img-only<?php if ( be_build( get_the_ID(), 'group_md_gray' ) ) { ?> img-gray<?php } ?>"><img src="<?php echo $items['group_ico_img']; ?>" alt="<?php echo $items['group_ico_title']; ?>"></div>
										<?php } else { ?>
											<div class="gw-img">
												<div class="img100 lazy"><div class="bgimg" style="background-image: url(<?php echo $items['group_ico_img']; ?>) !important;"></div></div>
											</div>
										<?php } ?>
									<?php } ?>
									<?php if ( ! empty( $items['group_ico_svg'] ) ) { ?>
										<div class="gw-ico"><svg class="icon" aria-hidden="true"><use xlink:href="#<?php echo $items['group_ico_svg']; ?>"></use></svg></div>
									<?php } ?>
									<?php if ( ! empty( $items['group_ico_ico'] ) ) { ?>
										<?php if ( be_build( get_the_ID(), 'group_ico_b' ) ) { ?>
										<div class="gw-ico"><i class="<?php echo $items['group_ico_ico']; ?>" style="color:<?php echo $items['group_ico_color']; ?>"></i></div>
										<?php } else { ?>
										<div class="gw-ico"><i class="<?php echo $items['group_ico_ico']; ?>" style="background:<?php echo $items['group_ico_color']; ?>"></i></div>
										<?php } ?>
									<?php } ?>
									<?php if ( ! empty( $items['group_ico_title'] ) ) { ?>
										<h3 class="gw-title"><?php echo $items['group_ico_title']; ?></h3>
									<?php } ?>
									<?php if ( ! empty( $items['group_ico_url'] ) ) { ?></a><?php } ?>
									<?php if ( ! empty( $items['group_ico_txt'] ) ) { ?>
										<div class="gw-content"><?php echo $items['group_ico_txt']; ?></div>
									<?php } ?>
								</div>
							<?php } else { ?>
								<?php if ( ! empty( $items['group_ico_url'] ) ) { ?></a><?php } ?>
							<?php } ?>
						</div>
					</div>
				</div>
				<?php } ?>
				<div class="clear"></div>
			</div>
		</div>
		<?php bu_help( $text = '特色', $number = 'group_ico_s' ); ?>
	</div>
</div>
<?php }