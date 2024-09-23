<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1<?php if ( zm_get_option('mobile_viewport')) { ?>, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no<?php } ?>" />
<meta http-equiv="Cache-Control" content="no-transform" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<?php do_action( 'title_head' ); ?>
<?php do_action( 'favicon_ico' ); ?>
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php wp_head(); ?>
<?php do_action( 'head_other' ); ?>

</head>
<body <?php body_class(); ?> ontouchstart="">
<?php wp_body_open(); ?>
<div id="page" class="hfeed site<?php page_class(); ?><?php if ( zm_get_option( 'body_width' ) == 'percent' ) { ?> percent<?php } ?><?php if ( zm_get_option( 'be_debug' ) ) { ?> debug<?php } ?><?php if ( ! zm_get_option( 'turn_small' ) ) { ?> nav-normal<?php } ?>">
<?php get_template_part( 'template/menu', 'index' ); ?>
<?php do_action( 'be_header_sub' ); ?>
	<?php if ( zm_get_option( 'filters' ) && ! is_category() ) { ?>
		<div class="header-sub">
			<?php get_template_part( '/inc/filter-results' ); ?>
		</div>
	<?php } ?>
	<div id="content" class="site-content<?php decide_h(); ?>">
	<?php if ( zm_get_option( 'filters_img' ) ) { ?>
		<section id="picture" class="picture-area content-area grid-cat-<?php echo be_get_option( 'img_f' ); ?>">
			<main id="main" class="be-main site-main" role="main">
				<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : the_post(); ?>
				<article class="picture scl" <?php aos_a(); ?>>
					<div class="boxs1">
						<div class="picture-box ms<?php echo fill_class(); ?>"<?php echo be_img_fill(); ?>>
							<figure class="picture-img">
								<?php if (zm_get_option('hide_box')) { ?>
									<a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><div class="hide-box"></div></a>
									<a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><div class="hide-excerpt"><?php if (has_excerpt('')){ echo wp_trim_words( get_the_excerpt(), 62, '...' ); } else { echo wp_trim_words( get_the_content(), 72, '...' ); } ?></div></a>
								<?php } ?>
								<?php echo zm_grid_thumbnail(); ?>
							</figure>
							<?php the_title( sprintf( '<h2 class="grid-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
							<span class="grid-inf">
								<span class="g-cat"><?php zm_category(); ?></span>
								<span class="grid-inf-l">
									<span class="date"><i class="be be-schedule ri"></i><?php the_time( 'm/d' ); ?></span>
									<?php views_span(); ?>
									<?php if ( get_post_meta(get_the_ID(), 'zm_like', true) ) : ?><span class="grid-like"><span class="be be-thumbs-up-o">&nbsp;<?php zm_get_current_count(); ?></span></span><?php endif; ?>
								</span>
				 			</span>
				 			<div class="clear"></div>
						</div>
					</div>
				</article>
				<?php endwhile;?>
				<?php else : ?>
					<section class="no-results">
						<div class="no-resu" <?php aos_a(); ?>>
							<p><?php _e( '无符合的文章！', 'begin' ); ?></p>
						</div>
					</section>
				<?php endif; ?>
			</main><!-- .site-main -->
			<?php begin_pagenav(); ?>
		</section><!-- .content-area -->
	<?php }else { ?>
		<section id="primary" class="content-area">
			<main id="main" class="be-main site-main<?php if ( zm_get_option( 'post_no_margin' ) ) { ?> domargin<?php } ?>" role="main">

				<?php if ( have_posts() ) : ?>

				<?php while ( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'template/content', get_post_format() ); ?>
				<?php endwhile; ?>

				<?php else : ?>
					<section class="no-results">
						<div class="no-resu" <?php aos_a(); ?>>
							<p><?php _e( '无符合的文章！', 'begin' ); ?></p>
						</div>
					</section>

				<?php endif; ?>

			</main><!-- .site-main -->

			<div class="pagenav-clear"><?php begin_pagenav(); ?></div>

		</section><!-- .content-area -->
		<?php get_sidebar(); ?>
	<?php } ?>
<?php get_footer(); ?>