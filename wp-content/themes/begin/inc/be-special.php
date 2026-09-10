<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// 专题专栏
function page_special() {
	global $post;
?>
<?php if ( be_get_option( 'blog_special_id' ) ) { ?>
	<div class="cat-cover-box<?php if ( be_get_option( 'special_slider' ) ) { ?> cat-cover-slider<?php } ?>">
		<div class="special-main <?php if ( be_get_option( 'special_slider' ) ) { ?> special-slider owl-carousel<?php } ?>">
			<?php $posts = get_posts( array( 'post_type' => 'any', 'orderby' => 'menu_order', 'include' => be_get_option('blog_special_id'), 'ignore_sticky_posts' => 1 ) ); if ($posts) : foreach( $posts as $post ) : setup_postdata( $post ); ?>
				<div class="grid-cat-<?php echo be_get_option( 'special_f' ); ?><?php if ( ! be_get_option( 'special_slider' ) ) { ?> cover4x<?php } ?>">
					<div class="cat-cover-main ms" <?php aos_a(); ?>>
						<div class="cat-cover-img thumbs-b lazy">
							<?php $image = get_post_meta( get_the_ID(), 'thumbnail', true ); ?>
								<?php if ( zm_get_option( 'lazy_s' ) ) { ?>
									<a class="thumbs-back" href="<?php echo get_permalink(); ?>" data-src="<?php echo $image; ?>" rel="bookmark" <?php echo goal(); ?>>
								<?php } else { ?>
									<a class="thumbs-back" href="<?php echo get_permalink(); ?>" style="background-image: url(<?php echo $image; ?>);" rel="bookmark" <?php echo goal(); ?>>
								<?php } ?>
								<div class="special-mark bz fd"><?php _e( '专题', 'begin' ); ?></div>
								<div class="cover-des-box">
									<?php
										$special = get_post_meta( get_the_ID(), 'special', true );
										if ( get_post_meta( get_the_ID(), 'special', true ) ) {
											echo '<div class="special-count fd">';
											if ( get_tag_post_count( $special ) > 0 ) {
												echo get_tag_post_count( $special );
												echo _e( '篇', 'begin' );
											} else {
												echo _e( '未添加文章', 'begin' );
											}
											echo '</div>';
										}
									?>
									<div class="cover-des">
										<div class="cover-des-main over">
											<?php
											$description = get_post_meta( get_the_ID(), 'description', true );
											if ( get_post_meta( get_the_ID(), 'description', true ) ) { ?>
												<?php echo $description; ?>
											<?php } else { ?>
												<?php the_title(); ?>
											<?php } ?>
										</div>
									</div>
								</div>
							</a>
							<h4 class="cat-cover-title"><?php the_title(); ?></h4>
						</div>
					</div>
				</div>
			<?php endforeach; endif; ?>
			<?php wp_reset_query(); ?>
		</div>
		<?php cms_help( $text = '首页设置 → 专栏专题 ', $number = 'cms_special_s' ); ?>
		<div class="clear"></div>
	</div>
<?php } ?>

<?php if ( be_get_option( 'code_special_id' ) ) { ?>
	<div class="cat-cover-box<?php if ( be_get_option( 'special_slider' ) ) { ?> cat-cover-slider<?php } ?>">
		<?php
			$special = array(
				'taxonomy'      => get_taxonomies(),
				'show_count'    => 1,
				'include'       => be_get_option( 'code_special_id' ),
				'orderby'       => 'include',
				'order'         => 'ASC',
				'hide_empty'    => 0,
				'hierarchical'  => 0
			);
			$cats = get_categories( $special );
		?>
		<div class="special-main <?php if ( be_get_option( 'special_slider' ) ) { ?> special-slider owl-carousel<?php } ?>">
			<?php foreach( $cats as $cat ) : ?>
				<div class="grid-cat-<?php echo be_get_option( 'special_f' ); ?><?php if ( ! be_get_option( 'special_slider' ) ) { ?> cover4x<?php } ?>">
					<div class="boxs1">
						<div class="cat-cover-main ms" <?php aos_a(); ?>>
							<div class="cat-cover-img thumbs-b lazy">
								<?php if ( zm_get_option( 'cat_cover' ) ) { ?>
									<?php if ( zm_get_option( 'lazy_s' ) ) { ?>
										<a class="thumbs-back" href="<?php echo get_category_link( $cat->term_id ) ?>" data-src="<?php echo cat_cover_url( $cat->term_id ); ?>" rel="bookmark" <?php echo goal(); ?>>
									<?php } else { ?>
										<a class="thumbs-back" href="<?php echo get_category_link( $cat->term_id ) ?>" style="background-image: url(<?php echo cat_cover_url( $cat->term_id ); ?>);" rel="bookmark" <?php echo goal(); ?>>
									<?php } ?>
								<?php } else { ?>
									<div class="cat-cover-tip">未启用分类封面</div>
								<?php } ?>

									<div class="special-mark bz fd"><?php _e( '专题', 'begin' ); ?></div>
									<div class="cover-des-box">
										<div class="special-count fd"><?php echo $cat->count; ?><?php _e( '篇', 'begin' ); ?></div>
										<div class="cover-des">
											<div class="cover-des-main over">
												<?php echo term_description( $cat->term_id ); ?>
											</div>
										</div>
									</div>
								</a>
								<h4 class="cat-cover-title"><?php echo $cat->name; ?></h4>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
			<?php wp_reset_postdata(); ?>
		</div>
		<?php cms_help( $text = '首页设置 → 专栏专题 ', $number = 'cms_special_s' ); ?>
		<div class="clear"></div>
	</div>
<?php } ?>
<?php }

// 专题专栏列表
function page_special_list() {
	global $post;
?>
<?php if ( be_get_option( 'blog_special_list_id' ) ) { ?>
	<div class="cat-cover-box">
		<?php $posts = get_posts( array( 'post_type' => 'any', 'orderby' => 'menu_order', 'include' => be_get_option('blog_special_list_id'), 'ignore_sticky_posts' => 1 ) ); if ($posts) : foreach( $posts as $post ) : setup_postdata( $post ); ?>
		<div class="special-grid-box" <?php aos_a(); ?>>
			<div class="special-grid-item tra ms">
				<div class="special-list-img">
					<div class="thumbs-special lazy">
						<?php if ( zm_get_option( 'lazy_s' ) ) { ?>
							<a class="thumbs-back sc" href="<?php echo get_permalink(); ?>" data-src="<?php echo get_post_meta( get_the_ID(), 'thumbnail', true ); ?>" rel="bookmark" <?php echo goal(); ?>></a>
						<?php } else { ?>
							<a class="thumbs-back sc" href="<?php echo get_permalink(); ?>" style="background-image: url(<?php echo get_post_meta( get_the_ID(), 'thumbnail', true ); ?>);" rel="bookmark" <?php echo goal(); ?>></a>
						<?php } ?>
					</div>

					<div class="special-mark bz"><?php _e( '专题', 'begin' ); ?></div>
					<?php
						$special = get_post_meta( get_the_ID(), 'special', true );
						if ( get_post_meta( get_the_ID(), 'special', true ) ) {
							echo '<div class="special-grid-count">';
							if ( get_tag_post_count( $special ) > 0 ) {
								echo get_tag_post_count( $special );
								echo _e( '篇', 'begin' );
							} else {
								echo _e( '未添加文章', 'begin' );
							}
							echo '</div>';
						}
					?>
				</div>

				<div class="special-list-box">
					<h4 class="special-name"><a href="<?php echo get_permalink(); ?>" rel="bookmark" <?php echo goal(); ?>><?php the_title(); ?></a></h4>
					<div class="special-list">
						<?php
							$special = get_post_meta( get_the_ID(), 'special', true );
							$args = array(
								'tag'       => $special,
								'showposts' => 3,
								'orderby'   => 'date',
								'order'     => 'DESC',
								'post_type' => 'any',
								'ignore_sticky_posts' => 1
							);
							$the_query = new WP_Query( $args );
						?>

						<?php if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
							<?php the_title( sprintf( '<div class="special-list-title"><a class="srm" href="%s" rel="bookmark" ' . goal() . '>' . t_mark(), esc_url( get_permalink() ) ), '</a></div>' ); ?>
						<?php endwhile; endif; ?>
						<?php wp_reset_postdata(); ?>
					</div>
				</div>
			</div>
		</div>
		<?php endforeach; endif; ?>
		<?php wp_reset_query(); ?>
		<?php cms_help( $text = '首页设置 → 专栏专题 → 专题列表', $number = 'cms_special_list_s' ); ?>
		<div class="clear"></div>
	</div>
<?php } ?>

<?php if ( be_get_option( 'code_special_list_id' ) ) { ?>
	<div class="cat-cover-box">
		<?php
			$tax = get_taxonomies();
			$tax_terms = get_terms( $tax , array(
				'include' => explode( ',', be_get_option( 'code_special_list_id' ) ),
				'orderby' => 'include',
				'order'   => 'ASC',
			));

			if ( $tax_terms ) {
				foreach ( $tax_terms as $tax_term ) { ?>

				<div class="special-grid-box" <?php aos_a(); ?>>
					<div class="boxs1">
						<div class="special-grid-item tra ms">
							<div class="special-list-img">
								<div class="thumbs-special lazy">
									<?php if ( zm_get_option( 'cat_cover' ) ) { ?>
										<?php if ( zm_get_option( 'lazy_s' ) ) { ?>
											<a class="thumbs-back sc" href="<?php echo get_category_link( $tax_term->term_id ) ?>" data-src="<?php echo cat_cover_url( $tax_term->term_id ); ?>" rel="bookmark" <?php echo goal(); ?>></a>
										<?php } else { ?>
											<a class="thumbs-back sc" href="<?php echo get_category_link( $tax_term->term_id ) ?>" style="background-image: url(<?php echo cat_cover_url( $tax_term->term_id ); ?>);" rel="bookmark" <?php echo goal(); ?>></a>
										<?php } ?>
									<?php } else { ?>
										<div class="cat-cover-tip">未启用分类封面</div>
									<?php } ?>
								</div>

								<div class="special-mark bz"><?php _e( '专题', 'begin' ); ?></div>
								<div class="special-grid-count"><?php echo $tax_term->count; ?><?php _e( '篇', 'begin' ); ?></div>
							</div>

							<div class="special-list-box">
								<h4 class="special-name"><a href="<?php echo get_category_link( $tax_term->term_id ) ?>" rel="bookmark" <?php echo goal(); ?>><?php echo $tax_term->name; ?></a></h4>
								<div class="special-list">
									<?php
										$args = array(
											'post_type' => 'any',
											'tax_query' => array(
												array(
													'taxonomy'         => $tax_term->taxonomy,
													'field'            => 'term_id',
													'terms'            => $tax_term->term_id,
												),
											),

											'post_status'         => 'publish',
											'posts_per_page'      => 3,
											'orderby'             => 'date',
											'order'               => 'DESC',
											'ignore_sticky_posts' => 1,
										);

										$query = new WP_Query( $args );
									?>

									<?php while ( $query->have_posts() ) : $query->the_post(); ?>
										<?php the_title( sprintf( '<h2 class="special-list-title"><a class="srm" href="%s" rel="bookmark" ' . goal() . '>' . t_mark(), esc_url( get_permalink() ) ), '</a></h2>' ); ?>
									<?php endwhile; ?>
									<?php wp_reset_postdata(); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>
		<?php } ?>
		<?php cms_help( $text = '首页设置 → 专栏专题 → 专题列表', $number = 'cms_special_list_s' ); ?>
		<div class="clear"></div>
	</div>
<?php } ?>
<?php }

// special_single_content
function special_single_content() {
	global $wpdb, $post;
?>
	<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" class="post-item post">
			<header class="entry-header">
				<?php if ( get_post_meta(get_the_ID(), 'header_img', true) || get_post_meta(get_the_ID(), 'header_bg', true) ) { ?>
					<div class="entry-title-clear"></div>
				<?php } else { ?>
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				<?php } ?>
			</header>

			<div class="entry-content">
				<div class="single-content">
					<?php the_content(); ?>
				</div>
				<div class="clear"></div>
			</div>
			<footer class="page-meta-zt">
				<?php begin_page_meta_zt(); ?>
			</footer>
			<div class="clear"></div>
		</article>
	<?php endwhile; ?>
	<?php wp_reset_query(); ?>
<?php }