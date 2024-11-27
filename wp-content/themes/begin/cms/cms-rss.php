<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_get_option( 'cms_rss' ) ) { ?>
<div class="cms-rss betip">
	<?php 
		$rss = (array) be_get_option( 'cms_rss_item' );
		foreach ( $rss as $items ) {
	?>
		<?php
			$rss = fetch_feed( $items['cms_rss_url'] );
			$maxitems = 0;
			if ( ! is_wp_error( $rss ) ) :
				$maxitems = $rss->get_item_quantity( be_get_option( 'cms_rss_n' ) );
				$rss_items = $rss->get_items( 0, $maxitems );
			endif;
		?>

		<div class="cms-rss-box rss<?php echo be_get_option( 'cms_rss_f' ); ?>">
			<div class="cms-rss-main ms betip" <?php aos_a(); ?>>

				<div class="cms-rss-main-head">
					<?php if ( ! empty( $items['cms_rss_title'] ) ) { ?>
						<h3 class="cms-rss-head-text">
							<?php echo $items['cms_rss_title']; ?>
						</h3>
					<?php } ?>
					<?php if ( ! empty( $items['cms_rss_img'] ) ) {
						if ( zm_get_option( 'lazy_s' ) ) {
								$be_get_img = 'data-src="' . $items['cms_rss_img'] . '"';
							} else {
								$be_get_img = 'style="background-image: url(' . $items['cms_rss_img'] . ');"';
							}
						 ?>
						<figure class="small-thumbnail">
							<div class="thumbs-rss lazy"><div class="bgimg sc" <?php echo $be_get_img ; ?></div></div>
						</figure>
					<?php } ?>
				</div>

				<ul class="rss-list">
					<?php if ( $maxitems == 0 ) : ?>
						<li class="srm"><?php _e( '暂无文章', 'begin' ); ?></li>
					<?php else : ?>
						<?php foreach ( $rss_items as $item ) : ?>
							<?php if ( ! empty( $items['cms_rss_date'] ) ) { ?>
								<li class="list-date"><time datetime="<?php echo esc_html( date( 'Y-m-d H:i:s', strtotime( $item->get_date() ) ) ); ?>"><?php echo esc_html( date( 'm/d', strtotime( $item->get_date() ) ) ); ?></time></li>
							<?php } ?>
							<li class="list-title<?php if ( empty( $items['cms_rss_date'] ) ) { ?> cms-rss-no-date<?php } ?>">
								<h2 class="cms-list-title"><a class="srm" href="<?php echo esc_url( $item->get_permalink() ); ?>" rel="external nofollow" target="_blank"><?php echo esc_html( $item->get_title() ); ?></a></h2>
							</li>
						<?php endforeach; ?>
					<?php endif; ?>
				</ul>

			</div>
		</div>
	<?php } ?>
	<?php cms_help( $text = '首页设置 → 杂志布局 → RSS聚合', $number = 'cms_rss_s' ); ?>
</div>
<?php } ?>