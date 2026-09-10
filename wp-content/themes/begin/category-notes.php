<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * category Template: 文档模板-章节
 */
get_header( 'note' ); ?>

<section id="note" class="content-area note-area">
	<div class="be-note-nav-box">
		<div class="note-show-all" title="<?php _e( '展开全部', 'begin' ); ?>"><div class="note-nav-show-ico"><i class="be be-more"></i></div></div>
		<div class="be-note-nav<?php if ( zm_get_option( 'note_nav_widget' ) ) { ?> be-note-nav-widget<?php } ?><?php if ( zm_get_option( 'note_nav_show' ) ) { ?> note-nav-widget-show<?php } ?>">
			<div class="be-note-nav-main">

				<?php if ( zm_get_option( 'note_nav_widget' ) ) { ?>
					<?php if ( ! dynamic_sidebar( 'notes' ) ) : ?>
						<aside id="add-widgets" class="widget widget_text">
							<div class="textwidget">
								<a href="<?php echo admin_url(); ?>widgets.php" target="_blank">点此为“文档目录”添加菜单小工具</a>
							</div>
						</aside>
					<?php endif; ?>

				<?php } else { ?>

					<?php 
						$category = get_queried_object();

						$terms = get_terms( array(
							'taxonomy'   => 'category',
							'parent'     => $category->term_id,
							'hide_empty' => false,
						));
					?>

					<?php if ( count( $terms ) > 0 ) { ?>

						<?php 
							$terms = array_filter( $terms, function( $term ) use( $category ) {
								return $term->term_id != $category->term_id; 
							});
						?>

						<?php foreach ( $terms as $term ) : ?>
							<ul class="note-menu">
								<li class="note-menu-item note-nav-btn note-nav-show<?php if ( ! zm_get_option( 'note_nav_show' ) ) { ?> note-nav-hide<?php } ?>" <?php post_class(); ?>>
									<a class="note-nav-cat" href="javascript:;"><?php echo $term->name; ?></a>
									<ul id="post-<?php the_ID(); ?>" class="note-sub-menu">

										<?php 
											if ( ! zm_get_option( 'note_nav_order' ) || ( zm_get_option( 'note_nav_order' ) == 'asc' ) ) {
												$order = 'ASC';
											}
											if ( zm_get_option( 'note_nav_order' ) == 'desc' ) {
												$order = 'DESC';
											}

											$posts = get_posts( array(
												'cat'            => $term->term_id,
												'posts_per_page' => -1,
												'order'          => $order,
												'orderby'        => 'date'
											));
										?>

										<?php foreach ( $posts as $post ) : setup_postdata( $post ); ?>

											<li class="note-menu-item-title">
												<a href="<?php echo get_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
											</li>

										<?php endforeach; ?>
										<?php wp_reset_postdata(); ?>
									</ul>
								</li>
							</ul>
						<?php endforeach; ?>

					<?php } else { ?>
						<?php 
							$current_term = get_the_terms( get_the_ID(), 'category' )[0]; 
							$sibling_terms = get_terms(array(
								'taxonomy' => 'category',
								'parent'   => $current_term->parent
							));
						?>
						<?php foreach ( $sibling_terms as $term ) : ?>
							<?php 
								$catid = $term->term_id;
								$note_cats = get_the_terms( get_the_ID(), 'category' );
								$note_id = $note_cats[0]->term_id;
								if ( $note_id == $catid ) {
									$note_nav_show = ' note-current-show';
								} else {
									$note_nav_show = '';
								}
							?>

							<ul class="note-menu">
								<li class="note-menu-item note-nav-btn note-nav-show<?php echo $note_nav_show; ?><?php if ( ! zm_get_option( 'note_nav_show' ) ) { ?> note-nav-hide<?php } ?>" <?php post_class(); ?>>
									<a class="note-nav-cat" href="javascript:;"><?php echo $term->name; ?></a>
									<ul id="post-<?php the_ID(); ?>" class="note-sub-menu">

										<?php 
											if ( ! zm_get_option( 'note_nav_order' ) || ( zm_get_option( 'note_nav_order' ) == 'asc' ) ) {
												$order = 'ASC';
											}
											if ( zm_get_option( 'note_nav_order' ) == 'desc' ) {
												$order = 'DESC';
											}

											$args = array(
												'cat'            => $term->term_id,
												'posts_per_page' => -1,
												'order'          => $order,
												'orderby'        => 'date'
											);

											$posts = get_posts($args);
										?>

										<?php foreach ( $posts as $post ) : setup_postdata( $post ); ?>

											<li class="note-menu-item-title">
												<a href="<?php echo get_permalink(); ?>" rel="bookmark"><?php echo $post->post_title; ?></a>
											</li>
										<?php endforeach; ?>
										<?php wp_reset_postdata(); ?>
									</ul>
								</li>
							</ul>
						<?php endforeach; ?>

					<?php } ?>
				<?php } ?>
			</div>
		</div>
	</div>
	<main id="main" class="be-main site-main note-main note-cat-main<?php if ( zm_get_option( 'note_main_overall' ) ) { ?> all<?php } ?>" role="main">

		<?php be_breadcrumbs_nav(); ?>

		<article id="post-<?php the_ID(); ?>" class="post-item-list post">
			<div class="note-nav-switch on-note-nav"><i class="be be-sort"></i><?php _e( '目录', 'begin' ); ?></div>
			<!--
			<header class="entry-header meta-t">
				<h1 class="entry-title"><?php single_cat_title(); ?></h1>
			</header>
			-->

			<div class="header-sub header-sub-img">
				<div class="cat-des" <?php aos_a(); ?>>
					<div class="cat-des-img<?php if ( zm_get_option( 'cat_des_img_zoom' ) ) { ?> cat-des-img-zoom<?php } ?>"><img src="<?php if ( function_exists( 'zm_taxonomy_image_url' ) ) echo zm_taxonomy_image_url(); ?>" alt="<?php single_cat_title(); ?>"><div class="clear"></div></div>
				</div>

				<div class="des-title des-title-box">
					<h1 class="des-t"><?php single_cat_title(); ?></h1>
				</div>
			</div>

			<div class="note-entry-content begd">
				<div class="entry-content">
					<div class="single-content">
						<h4 class="note-cat-des"><span class="dashicons dashicons-book"></span><?php _e( '简介', 'begin' ); ?></h4>
						<div class="note-content-text"><?php echo the_archive_description(); ?></div>

						<fieldset class="note-new-digest">
							<legend><span class="dashicons dashicons-welcome-write-blog"></span><?php _e( '更新', 'begin' ); ?></legend>
							<?php
								$args = array(
									'showposts' => '1',
									'tax_query' => array(
										array(
											'taxonomy' => 'category',
											'terms' => get_query_var( 'cat' )
										),
									)
								);
							?>
							<?php $be_query = new WP_Query( $args ); while ( $be_query->have_posts() ) : $be_query->the_post(); ?>
							<div class="note-content-text">
								<?php the_title( sprintf( '<h2 class="note-new-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a><span class="note-new">'. sprintf(__( '最新', 'begin' )) . '</span></h2>' ); ?>
								<?php 
									$content = get_the_content();
									$content = strip_shortcodes( $content );
									if ( get_bloginfo( 'language' ) === 'en-US' ) {
										echo begin_strimwidth( strip_tags( $content), 0, '280', '...' );
									} else {
										echo wp_trim_words( $content, '280', '...' );
									}
								?>

								<?php dynamic_sidebar( 'single-foot' ); ?>
								<?php logic_notice(); ?>

								<?php if ( zm_get_option( 'note_notice' ) ) { ?>
									<div class="note-notice betip">
										<?php echo wpautop( zm_get_option( 'note_notice' ) ); ?>
										<?php be_help( $text = '主题选项 → 分类模板 → 常规模式 → 文档模板设置 → 底部固定内容' ); ?>
									</div>
								<?php } ?>

							</div>
							<?php endwhile; ?>
							<?php wp_reset_postdata(); ?>
						</fieldset>
						<div class="clear"></div>
					</div>
				</div>
			</div>

		</article>

	</main>
</section>

<?php get_footer(); ?>