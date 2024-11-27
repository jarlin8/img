<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( !is_search() && is_archive() ) { ?>
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
										echo '<li class="child-cat-item"><a class="ms" href="' . esc_url( get_term_link( $sibcat, $sibcat->taxonomy ) ) . '" rel="bookmark" ' . goal() . '>' . $sibcat->name . '</a></li>';
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
										echo '<li class="child-cat-item"><a class="ms" href="' . esc_url( get_term_link( $subcat, $subcat->taxonomy ) ) . '" rel="bookmark" ' . goal() . '>' . $subcat->name . '</a></li>';
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
		<div class="header-sub header-author">
			<div class="cat-des ms"<?php if ( zm_get_option( 'title_img_h' ) ) { ?> style="max-height: <?php echo zm_get_option( 'title_img_height' ); ?>px;"<?php } ?> <?php aos_a(); ?>>
				<div class="cat-des-img author-es-img<?php if ( zm_get_option( 'cat_des_img_zoom' ) ) { ?> cat-des-img-zoom<?php } ?>">
					<img src="<?php if ( get_the_author_meta( 'userimg') ) { ?><?php echo the_author_meta( 'userimg' ); ?><?php } else { ?><?php echo zm_get_option( 'header_author_img' ); ?><?php } ?>" alt="<?php the_author(); ?>">
				</div>
				<div class="header-author-main">
					<div class="header-author-inf fadeInUp animated">
						<div class="header-avatar load">
							<?php if ( zm_get_option( 'cache_avatar' ) ) { ?>
								<?php echo begin_avatar( get_the_author_meta( 'user_email' ), '96', '', get_the_author() ); ?>
							<?php } else { ?>
								<?php be_avatar_author(); ?>
							<?php } ?>
						</div>
						<div class="header-user-author">
							<h1 class="des-t"><?php the_author(); ?></h1>
							<?php if ( get_the_author_meta( 'description' ) ) { ?>
								<p class="header-user-des"><?php the_author_meta( 'user_description' ); ?></p>
							<?php } else { ?>
								<p class="header-user-des"><?php _e( '暂无个人说明', 'begin' ); ?></p>
							<?php } ?>
						</div>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			<div class="header-user-inf">
				<div class="user-inf-item"><span class="user-inf-item-name"><?php _e( '文章', 'begin' ); ?></span><?php the_author_posts(); ?></div>
				<div class="user-inf-item"><span class="user-inf-item-name"><?php _e( '评论', 'begin' ); ?></span><?php echo $comment_count;?></div>
				<div class="user-inf-item "><span class="user-inf-item-name"><?php _e( '点赞', 'begin' ); ?></span><?php like_posts_views( get_the_author_meta( 'ID' ) );?></div>
				<div class="user-inf-item"><span class="user-inf-item-name"><?php _e( '浏览', 'begin' ); ?></span><?php author_posts_views( get_the_author_meta( 'ID' ) ); ?></div>
				<?php if ( zm_get_option( 'user_inf_remarks' ) ) { ?>
					<div class="user-inf-item">
						<span class="user-inf-item-name"><?php _e( 'QQ', 'begin' ); ?></span>
						<?php if ( get_the_author_meta( 'qq' ) ) { ?>
							<?php the_author_meta( 'qq' ); ?>
						<?php } else { ?>
							<?php _e( '暂无', 'begin' ); ?>
						<?php } ?>
					</div>
					<div class="user-inf-item">
						<span class="user-inf-item-name"><?php _e( '微信', 'begin' ); ?></span>
						<?php if ( get_the_author_meta( 'weixin' ) ) { ?>
							<?php the_author_meta( 'weixin' ); ?>
						<?php } else { ?>
							<?php _e( '暂无', 'begin' ); ?>
						<?php } ?>
					</div>
					<div class="user-inf-item">
						<span class="user-inf-item-name"><?php _e( '电话', 'begin' ); ?></span>
						<?php if ( get_the_author_meta( 'phone' ) ) { ?>
						<?php the_author_meta( 'phone' ); ?>
						<?php } else { ?>
							<?php _e( '暂无', 'begin' ); ?>
						<?php } ?>
					</div>
				<?php } ?>
			</div>
		</div>
	<?php endif; ?>

<?php } ?>

<?php if ( zm_get_option( 'h_widget_m' ) !== 'all_m' ) { ?>
<?php top_widget(); ?>
<?php } ?>

<?php if ( zm_get_option( 'filters' ) && is_category( explode( ',', zm_get_option( 'filters_cat_id' ) ) ) && ! is_singular() && ! is_home() && ! is_author() && ! is_search() && ! is_tag() ) { ?>
<div class="header-sub">
	<?php get_template_part( '/inc/filter' ); ?>
	<?php be_help( $text = '主题选项 → 辅助功能 → 多条件筛选' ); ?>
</div>
<?php } ?>

<?php if ( is_single() && be_get_option( 'single_cover' ) ) { ?>
<div class="header-sub single-cover">
	<?php cat_cover(); ?>
	<?php be_help( $text = '首页设置 → 分类封面 → 同时显示在正文页面顶部' ); ?>
</div>
<?php } ?>

<?php if ( zm_get_option( 'subjoin_menu' ) ) { ?>
<?php if ( ! get_post_meta( get_the_ID(), 'header_bg', true ) && ( ! get_post_meta( get_the_ID(), 'header_img', true ) ) ) { ?>
	<?php
		wp_nav_menu( array(
			'theme_location'  => 'submenu',
			'menu_class'      => 'submenu',
			'fallback_cb'     => 'assign',
			'container'       => 'nav',
			'container_class' => 'submenu-nav header-sub',
			'container_id'    => '',
			'echo'            => true,
		) );
	?>
<?php } ?>
<?php } ?>