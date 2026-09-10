<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
/*
Template Name: 文档模板
Template Post Type: post
*/
get_header( 'note' ); ?>
<section id="note" class="content-area note-area">
	<div class="be-note-nav-box be-note-nav-only">
		<!--<div class="note-show-all" title="<?php _e( '展开全部', 'begin' ); ?>"><div class="note-nav-show-ico"><i class="be be-more"></i></div></div>-->
		<div class="be-note-nav">
			<div class="be-note-nav-main">
				<?php $current_term = get_the_terms( get_the_ID(), 'category' )[0]; ?>

				<ul class="note-menu">
					<li class="note-menu-item note-nav-btn note-nav-show" <?php post_class(); ?>>
						<!--<a class="note-nav-cat" href="javascript:;"><?php echo $current_term->name; ?></a>-->
						<a class="note-nav-cat" href="javascript:;"><?php _e( '目录', 'begin' ); ?></a>
						<ul id="post-<?php the_ID(); ?>" class="note-sub-menu">

							<?php
								if ( ! zm_get_option( 'note_nav_order' ) || ( zm_get_option( 'note_nav_order' ) == 'asc' ) ) {
									$order = 'ASC';
								}
								if ( zm_get_option( 'note_nav_order' ) == 'desc' ) {
									$order = 'DESC';
								}

								$terms = get_the_terms( get_the_ID(), 'category' ); 
								$args = array(
									'post_type'      => 'post',
									'order'          => $order,
									'orderby'        => 'date',
									'posts_per_page' => -1,
									'tax_query'      => array(
										array(
											'taxonomy' => 'category',
											'terms' => $terms[0]->term_id 
										)
									) 
								);

								$query = new WP_Query( $args );
							?>

							<?php if ( $query->have_posts() ) : ?>
								<?php 
									while ( $query->have_posts() ) : $query->the_post();
									if ( is_single( get_the_ID() ) ) {
										$current_post = ' note-current-post';
									} else {
										$current_post = '';
									}
								 ?>

									<li class="note-menu-item-title<?php echo $current_post; ?>">
										<a href="<?php echo get_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
									</li>

								<?php endwhile; ?>
								<?php wp_reset_postdata(); ?>
							<?php else : ?>
								暂无文章
							<?php endif; ?>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<main id="main" class="be-main site-main note-main begd<?php if ( zm_get_option( 'note_main_overall' ) ) { ?> all<?php } ?><?php if ( zm_get_option( 'p_first' ) ) { ?> p-em<?php } ?><?php if ( zm_get_option( 'code_css' ) ) { ?> code-css<?php } ?><?php if (get_post_meta(get_the_ID(), 'sub_section', true) ) { ?> sub-h<?php } ?>" role="main">
		<?php be_breadcrumbs_nav(); ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" class="post-item post">
				<div class="note-nav-switch on-note-nav"><i class="be be-sort"></i><?php _e( '目录', 'begin' ); ?></div>
				<header class="entry-header meta-t">
					<?php the_title( '<h1 class="entry-title">', t_mark() . '</h1>' ); ?>
				</header>
				<div class="entry-content">
					<?php begin_single_meta(); ?>
					<div class="single-content">
						<?php begin_abstract(); ?>
						<?php get_template_part( 'ad/ads', 'single' ); ?>
						<?php the_content(); ?>

						<?php if ( zm_get_option( 'note_notice' ) ) { ?>
							<div class="note-notice betip">
								<?php echo wpautop( zm_get_option( 'note_notice' ) ); ?>
								<?php be_help( $text = '主题选项 → 分类模板 → 常规模式 → 文档模板设置 → 底部固定内容' ); ?>
							</div>
						<?php } ?>

						<?php dynamic_sidebar( 'single-foot' ); ?>

						<?php logic_notice(); ?>
					</div>
					<?php if ( zm_get_option( 'note_support' ) ) { ?>
						<?php content_support(); ?>
					<?php } ?>
					<div class="clear"></div>
				</div>
			</article>

			<?php if ( zm_get_option( 'note_comments' ) ) { ?>
				<?php begin_comments(); ?>
			<?php } ?>
		<?php endwhile; ?>
	</main>
</section>

<?php get_footer(); ?>