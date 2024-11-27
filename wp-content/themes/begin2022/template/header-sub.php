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

	<?php if ( is_category() && !is_category( explode(',',zm_get_option( 'child_cat_no' ) ) ) ) { ?>
		<?php 
		$cat_term_id = get_category_id( $cat );
		$cat_taxonomy = get_category( $cat )->taxonomy;
		if ( zm_get_option( 'child_cat' ) && sizeof( get_term_children( $cat_term_id, $cat_taxonomy ) ) != 0 ) { ?>
			<div class="header-sub">
				<ul class="child-cat child-cat-<?php echo zm_get_option( 'child_cat_f' ); ?>" <?php aos_a(); ?>>
					<?php 
						$args = array(
							'child_of'           => get_category_id( $cat ),
							'depth'              => 0,
							'hierarchical'       => 0,
							'hide_empty'         => 0,
							'title_li'           => '',
							'use_desc_for_title' => 0,
							'orderby'            => 'name',
							'order'              => 'ASC',
							'taxonomy'           => 'category'
						);
					?>
					<?php wp_list_categories( $args );?>
					<ul class="clear"></ul>
				</ul>
			</div>
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
				<div class="cat-des-img">
					<img src="<?php if ( get_the_author_meta( 'userimg') ) { ?><?php echo the_author_meta( 'userimg' ); ?><?php } else { ?><?php echo zm_get_option( 'header_author_img' ); ?><?php } ?>" alt="<?php the_author(); ?>">
				</div>
				<div class="header-author bgt">
					
					<div class="header-author-inf bgt">
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
				<div class="cat-des-img"><img src="<?php echo zm_get_option( 'cat_des_img_d' ); ?>" alt="<?php single_cat_title(); ?>"></div>
			<?php } else { ?>
				<?php if ( zm_get_option( 'cat_des_img' ) ) { ?>
					<div class="cat-des-img"><img src="<?php if ( function_exists( 'zm_taxonomy_image_url' ) ) echo get_template_directory_uri().'/prune.php?src=' . zm_taxonomy_image_url() . '&w=' . zm_get_option( 'img_des_w' ).'&h=' . zm_get_option( 'img_des_h' ) . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1'; ?>" alt="<?php single_cat_title(); ?>"></div>
				<?php } else { ?>
					<div class="cat-des-img"><img src="<?php if ( function_exists( 'zm_taxonomy_image_url' ) ) echo zm_taxonomy_image_url(); ?>" alt="<?php single_cat_title(); ?>"></div>
				<?php } ?>
			<?php } ?>
			<div class="des-title bgt<?php if ( zm_get_option( 'des_title_l' ) ) { ?> des-title-l<?php } ?>">
				<h1 class="des-t bgt"><?php single_cat_title(); ?></h1>
				<?php if ( zm_get_option( 'cat_des_p' ) && !zm_get_option( 'cat_area' ) ) { ?><?php echo the_archive_description( '<div class="des-p bgt">', '</div>' ); ?><?php } ?>
			</div>
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