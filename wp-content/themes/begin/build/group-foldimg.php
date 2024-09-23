<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_build( get_the_ID(), 'group_foldimg' ) ) {
	if ( ! be_build( get_the_ID(), 'foldimg_bg' ) || ( be_build( get_the_ID(), 'foldimg_bg' ) == 'auto' ) ) {
		$bg = '';
	}
	if ( be_build( get_the_ID(), 'foldimg_bg' ) == 'white' ) {
		$bg = ' group-white';
	}
	if ( be_build( get_the_ID(), 'foldimg_bg' ) == 'gray' ) {
		$bg = ' group-gray';
	}
?>
<div class="g-row g-line foldimg-line<?php echo $bg; ?>" <?php aos(); ?>>
	<div class="g-col">
		<div class="foldimg-box">
			<div class="group-title" <?php aos_b(); ?>>
				<?php if ( ! be_build( get_the_ID(), 'foldimg_t' ) == '' ) { ?>
					<h3><?php echo be_build( get_the_ID(), 'foldimg_t' ); ?></h3>
				<?php } ?>
				<?php if ( ! be_build( get_the_ID(), 'foldimg_des' ) == '' ) { ?>
					<div class="group-des"><?php echo be_build( get_the_ID(), 'foldimg_des' ); ?></div>
				<?php } ?>
				<div class="clear"></div>
			</div>
			<div class="foldimg-wrap<?php if ( be_build( get_the_ID(), 'foldimg_one_col' ) ) { ?> foldimg-one<?php } else { ?> foldimg-two<?php } ?>">
				<?php 
					$foldimg = ( array ) be_build( get_the_ID(), 'group_foldimg_item' );
					foreach ( $foldimg as $items ) {
				?>
					<div class="foldimg-main" style="min-height: <?php echo be_build( get_the_ID(), 'foldimg_height' ); ?>px;">
						<?php if ( ! empty( $items['group_foldimg_img'] ) ) { ?>
							<section class="foldimg-img" <?php aos_g(); ?>>
								<figure class="foldimg-bg" style="background-image: url(<?php echo $items['group_foldimg_img']; ?>) !important;"></figure>
							</section>
						<?php } ?>
		
						<section class="foldimg-inc">
							<?php if ( ! empty( $items['group_foldimg_title'] ) ) { ?>
								<div class="foldimg-title">
									<span class="foldimg-title-text"><?php echo $items['group_foldimg_title']; ?></span>
								</div>
							<?php } ?>

							<?php if ( ! empty( $items['group_foldimg_des'] ) ) { ?>
								<div class="foldimg-text sanitize">
								<?php echo wpautop( $items['group_foldimg_des'] ); ?>
								</div>
							<?php } ?>

							<?php if ( ! empty( $items['group_foldimg_btn'] ) ) { ?>
								<div class="foldimg-more">
									<a href="<?php echo $items['group_foldimg_url']; ?>" target="_blank" rel="external nofollow"><?php echo $items['group_foldimg_btn']; ?></a>
								</div>
							<?php } ?>
						</section>
					</div>
				<?php } ?>
			</div>
			<div class="clear"></div>
		</div>
		<?php bu_help( $text = '推荐', $number = 'foldimg_s' ); ?>
	</div>
</div>
<?php } ?>