<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( !is_search() && is_archive() ) { ?>
	<?php if ( zm_get_option( 'cat_des' ) ) { ?>
		<?php if ( !is_author() && !is_tag() && category_description() ){ ?>
			<?php archive_img(); ?>
		<?php } ?>

		<?php if ( !is_author() && is_tag() && !is_category() && tag_description() ){ ?>
			<?php archive_img(); ?>
		<?php } ?>

	<?php } ?>
	<?php if ( zm_get_option( 'child_cat' ) ) { ?>
		<?php if ( is_category() && !is_category( explode(',',zm_get_option( 'child_cat_no' ) ) ) ) { ?>
			<?php
				global $cat;
				$cat_term_id = get_category( $cat )->term_id;
				$cat_taxonomy = get_category( $cat )->taxonomy;
			?>
			<?php if ( sizeof( get_term_children( $cat_term_id, $cat_taxonomy ) ) == 0 ) { ?>
				<?php
					$cat_term_id = get_category_id( $cat );
					$cat_taxonomy = get_category( $cat )->taxonomy;
				?>
				<?php if ( sizeof ( get_term_children( $cat_term_id, $cat_taxonomy ) ) != 0 ) { ?>
					<div class="header-sub">
						<ul class="child-cat child-cat-<?php echo zm_get_option( 'child_cat_f' ); ?>" <?php aos_a(); ?>>
							<?php
								if ( zm_get_option( 'child_cat_exclude' ) ) {
									$exclude = array( $cat );
								} else {
									$exclude = '';
								}
								$term = get_queried_object();
								$sibcat = get_terms( $term->taxonomy, array(
									'parent'     => $term->parent,
									'exclude'    => $exclude,
									'hide_empty' => false,
								) );

								if ( $sibcat ) {
									foreach( $sibcat as $sibcat ) {
										echo '<li class="child-cat-item da"><a class="ms" href="' . esc_url( get_term_link( $sibcat, $sibcat->taxonomy ) ) . '">' . $sibcat->name . '</a></li>';
									}
								}
							?>
							<ul class="clear"></ul>
						</ul>
					</div>
				<?php } ?>
			<?php } else { ?>
				<?php
					global $cat;
					$father_id = get_category( $cat )->term_id;
					$cat_taxonomy = get_category( $cat )->taxonomy;
				?>
				<?php if ( sizeof ( get_term_children( $father_id, $cat_taxonomy ) ) != 0 ) { ?>
					<div class="header-sub">
						<ul class="child-cat child-cat-<?php echo zm_get_option( 'child_cat_f' ); ?>" <?php aos_a(); ?>>
							<?php
								$term = get_queried_object();
								$children = get_terms( $term->taxonomy, array(
									'parent'    => $term->term_id,
									'hide_empty' => false
								) );
								if ( $children ) {
									foreach( $children as $subcat ) {
										echo '<li class="child-cat-item da"><a class="ms" href="' . esc_url( get_term_link( $subcat, $subcat->taxonomy ) ) . '">' . $subcat->name . '</a></li>';
									}
								}
							?>
							<ul class="clear"></ul>
						</ul>
					</div>
				<?php } ?>
			<?php } ?>
		<?php } ?>
	<?php } ?>

	<?php if ( is_author() ) : ?>
		<?php
			global $wpdb;
			$author_id = get_the_author_meta( 'ID' );
			$comment_count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->comments  WHERE comment_approved='1' AND user_id = '$author_id' AND comment_type not in ('trackback','pingback')" );
		?>
		<div class="header-sub">
			<div class="cat-des ms" <?php aos_a(); ?>>
				<div class="cat-des-img<?php if ( zm_get_option( 'cat_des_img_zoom' ) ) { ?> cat-des-img-zoom<?php } ?>">
					<img src="<?php if ( get_the_author_meta( 'userimg') ) { ?><?php echo the_author_meta( 'userimg' ); ?><?php } else { ?><?php echo zm_get_option( 'header_author_img' ); ?><?php } ?>" alt="<?php the_author(); ?>">
				</div>
				<div class="header-author bgt">
					
					<div class="header-author-inf fadeInUp animated bgt">
						<div class="header-avatar bgt load">
							<?php if ( zm_get_option( 'cache_avatar' ) ) { ?>
								<?php echo begin_avatar( get_the_author_meta( 'user_email' ), '96', '', get_the_author() ); ?>
							<?php } else { ?>
								<?php be_avatar_author(); ?>
							<?php } ?>
						</div>
						<div class="header-user-author bgt">
							<h1 class="des-t bgt"><?php the_author(); ?></h1>
							<?php if ( get_the_author_meta( 'description' ) ) { ?>
								<p class="header-user-des bgt"><?php the_author_meta( 'user_description' ); ?></p>
							<?php } else { ?>
								<p class="header-user-des bgt">暂无个人说明</p>
							<?php } ?>
						</div>
					</div>
				</div>
				<p class="header-user-inf ease bgt">
					<span><i class="be be-editor"></i><?php the_author_posts(); ?></span>
					<span><i class="be be-speechbubble"></i><?php echo $comment_count;?></span>
					<?php if ( zm_get_option( 'post_views' ) ) { ?><span><i class="be be-eye"></i><?php author_posts_views( get_the_author_meta( 'ID' ) );?></span><?php } ?>
					<?php if ( zm_get_option( 'post_views' ) ) { ?><span><i class="be be-thumbs-up-o"></i><?php like_posts_views( get_the_author_meta( 'ID' ) );?></span><?php } ?>
				</p>
			</div>
		</div>
	<?php endif; ?>

<?php } ?>

<?php if ( zm_get_option( 'h_widget_m' ) !== 'all_m' ) { ?>
<?php top_widget(); ?>
<?php } ?>

<?php if ( zm_get_option( 'filters' ) && is_category( explode( ',',zm_get_option( 'filters_cat_id' ) ) ) && !is_singular() && !is_home() && !is_author() && !is_search() && !is_tag() ) { ?>
<div class="header-sub">
	<?php get_template_part( '/inc/filter' ); ?>
</div>
<?php } ?>

<?php if ( is_single() && zm_get_option( 'single_cover' ) ) { ?>
<div class="header-sub">
	<?php cat_cover(); ?>
</div>
<?php } ?>

<?php
function archive_img() { ?>
	<div class="header-sub">
		<div class="cat-des" <?php aos_a(); ?>>
			<?php if ( zm_get_option( 'cat_icon' ) ) { ?>
				<?php $term_id = get_query_var( 'cat' ); if ( get_option( 'zm_taxonomy_icon'.$term_id ) ) { ?><i class="header-cat-icon <?php echo zm_taxonomy_icon_code(); ?>"></i><?php } ?>
				<?php $term_id = get_query_var( 'cat' ); if ( get_option( 'zm_taxonomy_svg'.$term_id ) ) { ?><svg class="header-cat-icon icon" aria-hidden="true"><use xlink:href="#<?php echo zm_taxonomy_svg_code(); ?>"></use></svg><?php } ?>
			<?php } ?>
			<?php $term_id = get_query_var( 'cat' ); if ( zm_taxonomy_image_url( $term_id, NULL, TRUE ) == ZM_IMAGE_PLACEHOLDER ) { ?>
				<div class="cat-des-img<?php if ( zm_get_option( 'cat_des_img_zoom' ) ) { ?> cat-des-img-zoom<?php } ?>"><img src="<?php echo zm_get_option( 'cat_des_img_d' ); ?>" alt="<?php single_cat_title(); ?>"></div>
			<?php } else { ?>
				<?php if ( zm_get_option( 'cat_des_img' ) ) { ?>
					<div class="cat-des-img<?php if ( zm_get_option( 'cat_des_img_zoom' ) ) { ?> cat-des-img-zoom<?php } ?>"><img src="<?php if ( function_exists( 'zm_taxonomy_image_url' ) ) echo get_template_directory_uri().'/prune.php?src=' . zm_taxonomy_image_url() . '&w=' . zm_get_option( 'img_des_w' ).'&h=' . zm_get_option( 'img_des_h' ) . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1'; ?>" alt="<?php single_cat_title(); ?>"></div>
				<?php } else { ?>
					<div class="cat-des-img<?php if ( zm_get_option( 'cat_des_img_zoom' ) ) { ?> cat-des-img-zoom<?php } ?>"><img src="<?php if ( function_exists( 'zm_taxonomy_image_url' ) ) echo zm_taxonomy_image_url(); ?>" alt="<?php single_cat_title(); ?>"></div>
				<?php } ?>
			<?php } ?>
			<div class="des-title bgt<?php if ( zm_get_option( 'des_title_l' ) ) { ?> des-title-l<?php } ?><?php if ( zm_get_option( 'header_title_narrow' ) ) { ?> title-narrow<?php } ?>">
				<h1 class="des-t fadeInUp animated bgt"><?php single_cat_title(); ?></h1>
				<?php if ( zm_get_option( 'cat_des_p' ) && !zm_get_option( 'cat_area' ) ) { ?><?php echo the_archive_description( '<div class="des-p fadeInUp animated bgt">', '</div>' ); ?><?php } ?>
			</div>
			<?php
				$special = array(
					'taxonomy'   => 'special',
					'include'    => get_queried_object_id(),
				);
				$cats = get_categories( $special );
				foreach( $cats as $cat ) { ?>
				<div class="header-special-all"><a href="<?php echo get_permalink( zm_get_option( 'column_url' ) ); ?>"><i class="be be-sort"></i> <?php _e( '全部', 'begin' ); ?></a></div>
				<div class="header-special-count"><?php _e( '包含', 'begin' ); ?> <?php echo $cat->count; ?> <?php _e( '篇', 'begin' ); ?></div>
			<?php } ?>
		</div>

		<?php if ( zm_get_option( 'cat_des_p' ) && zm_get_option( 'cat_area' ) && !is_paged() ) { ?>
			<div class="des-cat ms bk da" <?php aos_a(); ?>>
				<div class="single-content">
					<?php echo the_archive_description(); ?>
				</div>
			</div>
		<?php } ?>
	</div>
<?php } ?>

<?php if ( zm_get_option( 'subjoin_menu' ) ) { ?>
<?php if ( ! get_post_meta( get_the_ID(), 'header_bg', true ) && ( ! get_post_meta( get_the_ID(), 'header_img', true ) ) ) { ?>
<nav class="submenu-nav header-sub hz">
	<?php
		wp_nav_menu( array(
			'theme_location' => 'submenu',
			'menu_class'     => 'submenu',
			'fallback_cb'    => 'assign'
		) );
	?>
	<div class="clear"></div>
</nav>
<?php } ?>
<?php } ?>

<?php if ( zm_get_option( 'cat_order_btu' ) && is_category() && ! is_home() && ! is_singular() && ! is_paged() ) { ?>
	<?php if ( ( ! is_category( explode( ',', zm_get_option( 'ajax_layout_code_a' ) ) ) ) && ( ! is_category( explode( ',', zm_get_option( 'ajax_layout_code_b' ) ) ) ) && ( ! is_category( explode( ',', zm_get_option( 'ajax_layout_code_c' ) ) ) ) && ( ! is_category( explode( ',', zm_get_option( 'ajax_layout_code_d' ) ) ) ) && ( ! is_category( explode( ',', zm_get_option( 'ajax_layout_code_f' ) ) ) ) && ( ! is_category( explode( ',', zm_get_option( 'ajax_layout_code_e' ) ) ) ) ) { ?>
		<div class="header-sub"><?php be_order_btu();?></div>
	<?php } ?>
<?php } ?>