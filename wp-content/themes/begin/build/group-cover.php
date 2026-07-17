<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_build( get_the_ID(), 'group_cat_cover' ) ) {
	if ( ! be_build( get_the_ID(), 'cover_bg' ) || ( be_build( get_the_ID(), 'cover_bg' ) == 'auto' ) ) {
		$bg = '';
	}
	if ( be_build( get_the_ID(), 'cover_bg' ) == 'white' ) {
		$bg = ' group-white';
	}
	if ( be_build( get_the_ID(), 'cover_bg' ) == 'gray' ) {
		$bg = ' group-gray';
	}
?>
<div class="g-row g-line group-cover<?php echo $bg; ?>" <?php aos(); ?>>
	<div class="g-col">
		<div class="group-cat-cover-box">
			<?php if ( be_build( get_the_ID(), 'group_cat_cover_id' ) ){ ?>
				<?php
					$terms = get_terms(
						array(
							'taxonomy'   => get_taxonomies(),
							'include'    => be_build( get_the_ID(), 'group_cat_cover_id' ),
							'hide_empty' => false,
							'orderby'    => 'include',
							'order'      => 'ASC',
						)
					);
					foreach ( $terms as $term ) {
				?>

					<div class="group-cat-cover-main group-cover-f<?php echo be_build( get_the_ID(), 'group_cover_f' ); ?>">
						<div class="group-cat-cover">
							<div class="boxs1">
								<div class="group-cat-cover-img<?php if ( be_build( get_the_ID(), 'group_cover_gray' ) ) { ?> img-gray<?php } ?>" <?php aos_b(); ?>>
									<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" rel="bookmark" <?php echo goal(); ?>>
										<?php if ( zm_get_option( 'lazy_s' ) ) { ?>
											<span class="load"><img src="<?php echo get_template_directory_uri(); ?>/img/loading.png" alt="<?php echo $term->name; ?>" data-original="<?php echo cat_cover_url( $term->term_id ); ?>"></span>
										<?php } else { ?>
											<img src="<?php echo cat_cover_url( $term->term_id ); ?>" alt="<?php echo $term->name; ?>">
										<?php } ?>
									</a>
									<h4 class="group-cat-cover-title"><?php echo $term->name; ?></h4>
								</div>
								<div class="clear"></div>
							</div>
						</div>

					</div>
				<?php } ?>
			<?php } else { ?>
				<div class="group-cat-cover">分类封面 → 输入分类ID</div>
			<?php } ?>
			<div class="clear"></div>
		</div>
		<?php bu_help( $text = '分类封面', $number = 'group_cat_cover_s' ); ?>
	</div>
</div>
<?php } ?>