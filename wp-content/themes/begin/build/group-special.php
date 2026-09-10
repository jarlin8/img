<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_build( get_the_ID(), 'group_special' ) ) {
	$args = [
		'auto'  => '',
		'white' => ' group-white',
		'gray'  => ' group-gray',
	];

	$bg = '';
	if ( isset( $items['special_bg'] ) && isset( $args[$items['special_bg']] ) ) {
		$bg = $args[$items['special_bg']];
	}

	$nav = $items['group_special_paged'] ? '' : 'no';

?>
<div class="g-row g-line group-special<?php echo $bg; ?>" <?php aos(); ?>>
	<div class="g-col">
		<div class="group-special-box<?php if ( $items['group_special_img_m'] == 'right' ) { ?> group-special-r<?php } ?>">
			<div class="group-special-cat">
				<a href="<?php echo esc_url( get_category_link( $items['group_special_id'] ) ); ?>" rel="bookmark" <?php echo goal(); ?>>
					<div class="group-special-img" style="background-image: url(<?php echo $items['group_special_img']; ?>);"></div>
					<div class="group-special-img-text" style="color: <?php echo $items['group_pecial_color']; ?>;">
						<h3 class="group-special-title">
							<?php echo $items['group_special_name']; ?>
						</h3>
						<div class="group-special-des">
							<?php echo $items['group_special_des']; ?>
						</div>
					</div>
				</a>
			</div>

			<div class="group-special-list">
				<?php 
					$children = ( $items['group_special_chil'] === 'true' ) ? 'true' : 'false';
					echo do_shortcode( '[be_ajax_post style="list" terms="' . $items['group_special_id'] . '" posts_per_page="' . $items['group_special_n'] . '" column="2" children="' . $children . '" nav="' . $nav . '" more="true" btn="no" btn_all="no"]' );
				?>
			</div>
		</div>
		<?php co_help_n( $text = '专题新闻', $items['group_special_s'] ); ?>
	</div>
</div>
<?php } ?>