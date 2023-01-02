<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// 博客布局
function blog_template() { ?>
<div id="primary" class="content-area common">
	<main id="main" class="site-main<?php if (zm_get_option('post_no_margin')) { ?> domargin<?php } ?>" role="main">
		<?php if (zm_get_option('slider')) { ?>
			<?php
				global $wpdb, $post;
				if ( !is_paged() ) :
					require get_template_directory() . '/template/slider.php';
				endif;
			?>
		<?php } ?>

		<?php if (zm_get_option('cms_top')) { ?>
			<?php
				if ( !is_paged() ) :
					get_template_part( 'template/b-top' );
				endif;
			?>
		<?php } ?>

		<?php
			if ( !is_paged() ) :
				get_template_part( 'template/blog-special' );
			endif;
		?>

		<?php if (zm_get_option('cat_all')) { ?>
			<?php
				if ( !is_paged() ) :
					require get_template_directory() . '/template/all-cat.php';
				endif;
			?>
		<?php } ?>

		<?php 
			if ( !is_paged() ) :
			get_template_part( '/template/b-cover' ); 
			endif;
		?>

		<?php
			if ( !is_paged() ) :
				get_template_part( '/inc/filter-general' );
			endif;
		?>

		<?php if ( !is_paged() && !zm_get_option('new_cat_id')== '' ) { ?>
			<div class="be-new-nav bk ms" <?php aos_f(); ?>>
				<ul class="new-tabs-all tab-but current"><li><?php _e( '最新文章', 'begin' ); ?></li></ul>
				<ul class="new-tabs-cat">
					<?php $display_categories = explode(',',zm_get_option('new_cat_id') ); foreach ($display_categories as $category) { ?>
						<?php query_posts( array( 'cat' => $category) ); ?>
						<li><a class="tags-cat tab-but" data-id="<?php echo $category; ?>" href="#"><?php single_cat_title(); ?></a></li>
					<?php wp_reset_query(); ?>
					<?php } ?>
				</ul>
			</div>
			<div class="clear"></div>
			<div class="ajax-cat-cntent cat-border catpast"></div>
			<div class="ajax-new-cntent netcurrent">
		<?php } ?>

<?php }

// 图片布局
function grid_template_a() { ?>
	<?php if (zm_get_option('slider')) { ?>
		<?php
			global $wpdb, $post;
			if ( !is_paged() ) :
				require get_template_directory() . '/template/slider.php';
			endif;
		?>
	<?php } ?>

	<?php if (zm_get_option('cms_top')) { ?>
		<?php
			if ( !is_paged() ) :
				get_template_part( 'template/img-top' );
			endif;
		?>
	<?php } ?>

	<?php if (zm_get_option('cat_all')) { ?>
		<?php require get_template_directory() . '/template/all-cat.php'; ?>
	<?php } ?>

	<?php 
		if ( !is_paged() ) :
		get_template_part( '/template/blog-special' ); 
		endif;
	?>

	<?php 
		if ( !is_paged() ) :
		get_template_part( '/template/b-cover' ); 
		endif;
	?>

	<?php 
		if ( !is_paged() ) :
		get_template_part( '/template/cat-tab' ); 
		endif;
	?>

	<?php
		if ( !is_paged() ) :
			get_template_part( '/inc/filter-general' );
		endif;
	?>
<?php }
function grid_template_b() { ?>
	<?php grid_template_a(); ?>
	<section id="picture" class="content-area grid-cat-<?php echo zm_get_option('img_f'); ?>">
		<main id="main" class="site-main" role="main">
		<?php if ( !is_paged() && !zm_get_option('new_cat_id')== '' ) { ?>
			<div class="be-new-nav-img" <?php aos_f(); ?>>
				<ul class="new-tabs-all-img current"><li><?php _e( '最新文章', 'begin' ); ?></li></ul>
				<ul class="new-tabs-cat-img">
					<?php $display_categories = explode(',',zm_get_option('new_cat_id') ); foreach ($display_categories as $category) { ?>
						<?php query_posts( array( 'cat' => $category) ); ?>
						<li><a class="tags-cat-img" data-id="<?php echo $category; ?>" href="#"><?php single_cat_title(); ?></a></li>
					<?php wp_reset_query(); ?>
					<?php } ?>
				</ul>
			</div>
			<div class="clear"></div>
			<div class="ajax-cat-cntent-img cat-border catpast"></div>
			<div class="ajax-new-cntent-img netcurrent">
		<?php } ?>
<?php }

function grid_template_c() { ?>
<?php grid_template_d(); ?>
<?php if ( !is_paged() && !zm_get_option('new_cat_id')== '' ) { ?></div><?php } ?>
</main>
<?php begin_pagenav(); ?>
<div class="clear"></div>
</section>
<?php }

function grid_template_d() { ?>
<?php global $wpdb, $post; ?>
<?php while ( have_posts() ) : the_post(); ?>
<article id="post-<?php the_ID(); ?>" <?php aos_a(); ?> <?php post_class('ajax-grid-img scl'); ?>>
	<div class="picture-box sup ms bk">
		<figure class="picture-img">
			<?php if (zm_get_option('hide_box')) { ?>
				<a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><div class="hide-box"></div></a>
				<a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>">
					<div class="hide-excerpt">
						<?php if (has_excerpt('')){
								echo wp_trim_words( get_the_excerpt(), 30, '...' );
							} else {
								$content = get_the_content();
								$content = wp_strip_all_tags(str_replace(array('[',']'),array('<','>'),$content));
								echo wp_trim_words( $content, 30, '...' );
							}
						?>
					</div>
				</a>
			<?php } ?>

			<?php if ( get_post_meta($post->ID, 'direct', true) ) { ?>
				<?php $direct = get_post_meta($post->ID, 'direct', true); ?>
				<?php zm_thumbnail_link(); ?>
			<?php } else { ?>
				<?php zm_grid_thumbnail(); ?>
			<?php } ?>

			<?php if ( has_post_format('video') ) { ?><div class="img-ico"><a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-play"></i></a></div><?php } ?>
			<?php if ( has_post_format('quote') ) { ?><div class="img-ico"><a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-display"></i></a></div><?php } ?>
			<?php if ( has_post_format('image') ) { ?><div class="img-ico"><a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-picture"></i></a></div><?php } ?>
		</figure>
		<?php the_title( sprintf( '<h2 class="grid-title over"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		<span class="grid-inf">
			<?php if ( has_post_format('link') ) { ?>
				<?php if ( get_post_meta($post->ID, 'link_inf', true) ) { ?>
					<span class="link-inf"><?php $link_inf = get_post_meta($post->ID, 'link_inf', true);{ echo $link_inf;}?></span>
				<?php } else { ?>
					<span class="g-cat"><?php zm_category(); ?></span>
				<?php } ?>
			<?php } else { ?>
				<?php if (zm_get_option('meta_author')) { ?><span class="grid-author"><?php grid_author_inf(); ?></span><?php } ?>
				<span class="g-cat"><?php zm_category(); ?></span>
			<?php } ?>
			<span class="grid-inf-l">
				<?php if ( !has_post_format('link') ) { ?><span class="date"><i class="be be-schedule ri"></i><?php the_time( 'm/d' ); ?></span><?php } ?>
				<?php views_span(); ?>
				<?php if ( get_post_meta($post->ID, 'zm_like', true) ) : ?><span class="grid-like"><span class="be be-thumbs-up-o">&nbsp;<?php zm_get_current_count(); ?></span></span><?php endif; ?>
			</span>
		</span>
		<div class="clear"></div>
	</div>
</article>
<?php endwhile;?>
<?php }

// 分类图片
function grid_cat_template() { ?>
<?php if (zm_get_option('slider')) { ?>
	<?php
		global $wpdb, $post;
		if ( !is_paged() ) :
			require get_template_directory() . '/template/slider.php';
		endif;
	?>
<?php } ?>

<section id="grid-cat" class="content-area">
	<main id="main" class="site-main" role="main">
		<?php get_template_part( '/template/b-cover' ); ?>
		<?php 
			global $wpdb, $post; $do_not_duplicate[] = '';
			if (zm_get_option('cms_top')) {
				require get_template_directory() . '/grid/grid-top.php';
			}
			require get_template_directory() . '/grid/grid-cat-new.php';
			get_template_part( '/inc/filter-general' );
			if ( !is_paged() ) :
				get_template_part( '/template/blog-special' ); 
				get_template_part( '/template/cat-tab' );
			endif;
			require get_template_directory() . '/grid/grid-cat-a.php';
			get_template_part( '/grid/grid-widget-one' );
			require get_template_directory() . '/grid/grid-cat-carousel.php';
			require get_template_directory() . '/grid/grid-cat-b.php';
			get_template_part( '/grid/grid-widget-two' );
			require get_template_directory() . '/grid/grid-cat-c.php';
		 ?>
	</main>
</section>
<?php }

// grid new
function grid_new() { ?>
<?php global $wpdb, $post; ?>
<div class="grid-cat-site grid-cat-<?php echo zm_get_option('grid_new_f'); ?>">
	<article id="post-<?php the_ID(); ?>" <?php aos_a(); ?> <?php post_class('bky gn'); ?>>
		<div class="grid-cat-bx4 sup ms bk">
			<figure class="picture-img">
				<?php if ( get_post_meta($post->ID, 'direct', true) ) { ?>
					<?php zm_thumbnail_link(); ?>
				<?php } else { ?>
					<?php zm_thumbnail(); ?>
				<?php } ?>
				<?php if ( has_post_format('video') ) { ?><div class="play-ico"><a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-play"></i></a></div><?php } ?>
				<?php if ( has_post_format('quote') ) { ?><div class="img-ico"><a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-display"></i></a></div><?php } ?>
				<?php if ( has_post_format('image') ) { ?><div class="img-ico"><a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-picture"></i></a></div><?php } ?>
				<?php if ( has_post_format('link') ) { ?><div class="img-ico"><a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-link"></i></a></div><?php } ?>
			</figure>

			<?php if ( get_post_meta($post->ID, 'direct', true) ) { ?>
				<?php $direct = get_post_meta($post->ID, 'direct', true); ?>
				<h2 class="grid-title"><a href="<?php echo $direct ?>" target="_blank" rel="external nofollow"><?php the_title(); ?></a></h2>
			<?php } else { ?>
				<?php the_title( sprintf( '<h2 class="grid-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
			<?php } ?>

			<span class="grid-inf">
				<?php if ( get_post_meta($post->ID, 'link_inf', true) ) { ?>
					<span class="link-inf"><?php $link_inf = get_post_meta($post->ID, 'link_inf', true);{ echo $link_inf;}?></span>
					<span class="grid-inf-l">
					<?php if ( !get_post_meta($post->ID, 'direct', true) ) { ?><span class="g-cat"><?php zm_category(); ?></span><?php } ?>
					<?php echo t_mark(); ?>
					</span>
				<?php } else { ?>
					<?php if (zm_get_option('meta_author')) { ?><span class="grid-author"><?php grid_author_inf(); ?></span><?php } ?>
					<?php if ( !get_post_meta($post->ID, 'direct', true) ) { ?><span class="g-cat"><?php zm_category(); ?></span><?php } ?>
					<span class="grid-inf-l">
						<span class="date"><i class="be be-schedule ri"></i><?php the_time( 'm/d' ); ?></span>
						<?php if ( get_post_meta($post->ID, 'zm_like', true) ) : ?><span class="grid-like"><span class="be be-thumbs-up-o">&nbsp;<?php zm_get_current_count(); ?></span></span><?php endif; ?>
						<?php echo t_mark(); ?>
					</span>
				<?php } ?>
			</span>
			<div class="clear"></div>
		</div>
	</article>
</div>
<?php }

// 杂志布局
function cms_template() { ?>
<?php 
global $wpdb, $post; $do_not_duplicate[] = '';
if (!zm_get_option('slider_l') || (zm_get_option("slider_l") == 'slider_w')) {
	require get_template_directory() . '/template/slider.php';
}

if (zm_get_option('cms_slider_sticky')) {
	echo '<div id="primary-cms">';
}

if (zm_get_option('cms_no_s')) {
	echo '<div id="primary" class="content-area">';
} else {
	echo '<div id="cms-primary" class="content-area">';
 }
echo '<main id="main" class="site-main" role="main">';
if (zm_get_option('slider_l') == 'slider_n') {
	require get_template_directory() . '/template/slider.php';
}
get_template_part( '/cms/cms-top' );
get_template_part( '/cms/cat-special' );
get_template_part( '/cms/cat-cover' );
get_template_part( '/cms/cms-widget-two-menu' );
require get_template_directory() . '/cms/cms-news.php';
get_template_part( '/inc/filter-home' );
require get_template_directory() . '/cms/cms-cat-tab.php';
get_template_part( '/template/letter-show' );
get_template_part( '/cms/cms-widget-one' );
require get_template_directory() . '/cms/cms-cat-one-5.php';
require get_template_directory() . '/cms/cms-cat-one-no-img.php';
require get_template_directory() . '/cms/cms-cat-one-10.php';
require get_template_directory() . '/cms/cms-picture.php';
get_template_part( '/cms/cms-widget-two' );
require get_template_directory() . '/cms/cms-cat-lead.php';
require get_template_directory() . '/cms/cms-cat-small.php';
get_template_part( '/cms/cms-video' );
echo '<div class="sort" name="'. zm_get_option('tab_h_s') .'">';
get_template_part( '/template/cat-tab' ); 
echo '</div>';
echo '</main>';
echo '</div>';

if (zm_get_option('cms_no_s')) {
	echo get_sidebar('cms'); 
} else {
	echo '<div class="clear"></div>';
}
if (zm_get_option('cms_slider_sticky')) {
	echo '</div>';
}
echo '<div id="below-main">';
get_template_part( '/cms/cms-show' );
get_template_part( '/cms/cms-tool' );
if (zm_get_option('grid_ico_cms')) { grid_md_cms(); }
get_template_part( '/cms/cms-widget-three' );
require get_template_directory() . '/cms/cms-cat-square.php';
require get_template_directory() . '/cms/cms-cat-grid.php';
require get_template_directory() . '/cms/cms-scrolling.php';
require get_template_directory() . '/cms/cms-cat-big.php';
get_template_part( '/cms/cms-tao' );
if (function_exists( 'is_shop' )) {
	get_template_part( '/woocommerce/be-woo/cms-woo' );
}
require get_template_directory() . '/cms/cms-cat-big-n.php'; 
echo '</div>';
?>
<?php }

// 公司布局
function group_template() { ?>
<div class="container">
<?php get_template_part( '/group/group-slider' ); ?>
<div id="group-section" class="bgt<?php if (zm_get_option('g_line')) { ?> line-color<?php } else { ?> line-white<?php } ?>">
<?php 
	function group() {
	global $wpdb, $post; $do_not_cat[] = '';
	get_template_part( '/group/group-contact' );
	get_template_part( '/group/group-notice' );
	get_template_part( '/group/group-dean' );
	get_template_part( '/group/group-tool' );
	get_template_part( '/group/group-show' );
	get_template_part( '/group/group-service' );
	if (function_exists( 'is_shop' )) {
		get_template_part( '/woocommerce/be-woo/group-woo' );
	}
	if (zm_get_option('group_ico')) { grid_md_group(); }
	get_template_part( '/group/group-post' );
	get_template_part( '/group/group-features' );
	get_template_part( '/group/group-cat-img' );
	get_template_part( '/group/group-wd' );
	get_template_part( '/group/group-explain' );
	get_template_part( '/group/group-widget-one' );
	get_template_part( '/group/group-dow-tab' );
	require get_template_directory() . '/group/group-news.php';
	get_template_part( '/group/group-widget-three' );
	get_template_part( '/group/group-tao' );
	require get_template_directory() . '/group/group-cat-a.php';
	get_template_part( '/group/group-widget-two' );
	require get_template_directory() . '/group/group-cat-b.php';
	require get_template_directory() . '/group/group-tab.php';
	require get_template_directory() . '/group/group-cat-c.php';
	require get_template_directory() . '/group/group-carousel.php';
} ?>
<?php group(); ?>
</div>
<div class="clear"></div>
</div>
<?php }

// fall
function fall_main() { ?>
<section id="post-fall" class="content-area">
	<main id="main" class="fall-main post-fall" role="main">
		<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class('fall scl fall-off'); ?>>
			<div class="fall-box sup bk load">
				<?php 
				global $post;
				$content = $post->post_content;
				preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
				$n = count($strResult[1]);	
				if ( $n > 0 ) { ?>
					<figure class="fall-img">
						<a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"></a>
						<?php zm_waterfall_img(); ?>
						<?php if ( has_post_format('video') ) { ?><a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-play"></i></a><?php } ?>
						<?php if ( has_post_format('quote') ) { ?><div class="img-ico"><a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-display"></i></a></div><?php } ?>
						<?php if ( has_post_format('image') ) { ?><div class="img-ico"><a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-picture"></i></a></div><?php } ?>
					</figure>
					<?php the_title( sprintf( '<h2 class="fall-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
				<?php } else { ?>
					<?php the_title( sprintf( '<h2 class="fall-title fall-title-img"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
					<div class="archive-content-fall">
						<?php begin_trim_words(); ?>
					</div>
				<?php } ?>
				<?php if (zm_get_option('fall_inf')) { ?><?php fall_inf(); ?><?php } ?>
			 	<div class="clear"></div>
			</div>
		</article>
		<?php endwhile;?>
	</main>
	<div class="clear"></div>
</section>
<div class="other-nav"><?php begin_pagenav(); ?></div>
<?php }