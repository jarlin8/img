<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// 博客布局
function blog_template() { ?>
<div id="<?php if ( get_post_meta(get_the_ID(), 'sidebar_l', true) ) { ?>primary-l<?php } else { ?>primary<?php } ?>" class="content-area common<?php if ( get_post_meta( get_the_ID(), 'no_sidebar', true ) ) { ?> no-sidebar<?php } ?>">
	<main id="main" class="site-main<?php if ( ! zm_get_option( 'blog_ajax' ) ) { ?> be-main<?php } ?><?php if (zm_get_option('post_no_margin')) { ?> domargin<?php } ?>" role="main">
		<?php if ( zm_get_option( 'slider' ) ) { ?>
			<?php
				global $wpdb, $post;
				if ( !is_paged() ) :
					require get_template_directory() . '/template/slider.php';
				endif;
			?>
		<?php } ?>

		<?php if ( zm_get_option( 'blog_top' ) ) { ?>
			<?php
				if ( !is_paged() ) :
					get_template_part( 'template/b-top' );
				endif;
			?>
		<?php } ?>

		<?php
			if ( zm_get_option( 'blog_special' ) ) {
				if ( !is_paged() ) :
					page_special();
				endif;
			}
		?>

		<?php
			if ( zm_get_option( 'blog_special_list' ) ) {
				if ( !is_paged() ) :
					page_special_list();
				endif;
			}
		?>

		<?php 
			if ( zm_get_option( 'blog_cat_cover' ) ) {
				if ( !is_paged() ) :
					cat_cover();
				endif;
			}
		?>
<?php }

// 图片布局
function grid_template_a() { ?>
	<?php if ( zm_get_option( 'slider' ) ) { ?>
		<?php
			global $wpdb, $post;
			if ( !is_paged() ) :
				require get_template_directory() . '/template/slider.php';
			endif;
		?>
	<?php } ?>

	<?php if ( zm_get_option( 'img_top' ) ) { ?>
		<?php
			if ( !is_paged() ) :
				get_template_part( 'template/img-top' );
			endif;
		?>
	<?php } ?>

	<?php
		if ( zm_get_option( 'img_special' ) ) {
			if ( !is_paged() ) :
				page_special();
			endif;
		}
	?>

	<?php 
		if ( zm_get_option( 'img_cat_cover' ) ) {
			if ( !is_paged() ) :
				echo '<span class="grid-cover">';
					cat_cover();
				echo '</span>';
			endif;
		}
	?>
<?php }
function grid_template_b() { ?>
	<?php grid_template_a(); ?>
	<section id="picture" class="picture-area content-area grid-cat-<?php echo zm_get_option('img_f'); ?>">
		<main id="main" class="be-main site-main" role="main">
<?php }

function grid_template_c() { ?>
<?php grid_template_d(); ?>
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
			<?php echo be_img_excerpt(); ?>
			<?php if ( get_post_meta(get_the_ID(), 'direct', true) ) { ?>
				<?php $direct = get_post_meta(get_the_ID(), 'direct', true); ?>
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
				<?php if ( get_post_meta(get_the_ID(), 'link_inf', true) ) { ?>
					<span class="link-inf"><?php $link_inf = get_post_meta(get_the_ID(), 'link_inf', true);{ echo $link_inf;}?></span>
				<?php } else { ?>
					<span class="g-cat"><?php zm_category(); ?></span>
				<?php } ?>
			<?php } else { ?>
				<?php if (zm_get_option('meta_author')) { ?><span class="grid-author"><?php grid_author_inf(); ?></span><?php } ?>
				<span class="g-cat"><?php zm_category(); ?></span>
			<?php } ?>
			<span class="grid-inf-l">
				<?php echo be_vip_meta(); ?>
				<?php if ( !has_post_format('link') ) { ?><?php grid_meta(); ?><?php } ?>
				<?php views_span(); ?>
				<?php if ( get_post_meta(get_the_ID(), 'zm_like', true) ) : ?><span class="grid-like"><span class="be be-thumbs-up-o">&nbsp;<?php zm_get_current_count(); ?></span></span><?php endif; ?>
			</span>
		</span>
		<div class="clear"></div>
	</div>
</article>
<?php endwhile;?>
<?php }

// 分类图片
function grid_cat_template() { ?>
<?php if ( zm_get_option( 'slider' ) ) { ?>
	<?php
		global $post;
		require get_template_directory() . '/template/slider.php';
	?>
<?php } ?>

<section id="grid-cat" class="grid-cat-area content-area">
	<main id="main" class="be-main site-main" role="main">
		<?php 
			global $wpdb, $post; $do_not_duplicate[] = '';
			if ( zm_get_option( 'catimg_top' ) ) {
				require get_template_directory() . '/grid/grid-top.php';
			}
			require get_template_directory() . '/grid/grid-cat-new.php';
			if ( zm_get_option( 'catimg_cat_cover' ) ) {
				cat_cover();
			}
			if (zm_get_option( 'filters' ) && zm_get_option( 'catimg_filter' ) ){
				get_template_part( '/inc/filter-general' );
			}
			if ( zm_get_option( 'catimg_special' ) ) {
				page_special();
			}

			require get_template_directory() . '/grid/grid-cat-a.php';
			get_template_part( '/grid/grid-widget-one' );
			require get_template_directory() . '/grid/grid-cat-carousel.php';
			require get_template_directory() . '/grid/grid-cat-b.php';
			get_template_part( '/grid/grid-widget-two' );
			if ( zm_get_option( 'catimg_special_list' ) ) {
				echo '<div class="catimg-cover-box">';
					page_special_list();
				echo '</div>';
			}

			if ( zm_get_option( 'catimg_ajax_cat' ) ) {
				echo '<div class="catimg-ajax-cat-post">';
				echo do_shortcode( zm_get_option( 'catimg_ajax_cat_post_code' ) );
				echo '</div>';
			}
			require get_template_directory() . '/grid/grid-cat-c.php';
		 ?>
	</main>
</section>
<?php }

// grid new
function grid_new() { ?>
<?php global $wpdb, $post; ?>
	<article id="post-<?php the_ID(); ?>" <?php aos_a(); ?> <?php post_class('bky gn aos-animate'); ?>>
		<div class="grid-cat-bx4 sup ms bk">
			<figure class="picture-img">
				<?php if ( get_post_meta(get_the_ID(), 'direct', true) ) { ?>
					<?php zm_thumbnail_link(); ?>
				<?php } else { ?>
					<?php zm_thumbnail(); ?>
				<?php } ?>
				<?php if ( has_post_format('video') ) { ?><div class="play-ico"><a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-play"></i></a></div><?php } ?>
				<?php if ( has_post_format('quote') ) { ?><div class="img-ico"><a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-display"></i></a></div><?php } ?>
				<?php if ( has_post_format('image') ) { ?><div class="img-ico"><a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-picture"></i></a></div><?php } ?>
				<?php if ( has_post_format('link') ) { ?><div class="img-ico"><a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-link"></i></a></div><?php } ?>
			</figure>

			<?php if ( get_post_meta(get_the_ID(), 'direct', true) ) { ?>
				<?php $direct = get_post_meta(get_the_ID(), 'direct', true); ?>
				<h2 class="grid-title"><a href="<?php echo $direct ?>" target="_blank" rel="external nofollow"><?php the_title(); ?></a></h2>
			<?php } else { ?>
				<?php the_title( sprintf( '<h2 class="grid-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
			<?php } ?>

			<span class="grid-inf">
				<?php if ( get_post_meta(get_the_ID(), 'link_inf', true) ) { ?>
					<span class="link-inf"><?php $link_inf = get_post_meta(get_the_ID(), 'link_inf', true);{ echo $link_inf;}?></span>
					<span class="grid-inf-l">
					<?php if ( !get_post_meta(get_the_ID(), 'direct', true) ) { ?><span class="g-cat"><?php zm_category(); ?></span><?php } ?>
					<?php echo t_mark(); ?>
					</span>
				<?php } else { ?>
					<?php if (zm_get_option('meta_author')) { ?><span class="grid-author"><?php grid_author_inf(); ?></span><?php } ?>
					<?php if ( !get_post_meta(get_the_ID(), 'direct', true) ) { ?><span class="g-cat"><?php zm_category(); ?></span><?php } ?>
					<span class="grid-inf-l">
						<?php echo be_vip_meta(); ?>
						<?php grid_meta(); ?>
						<?php if ( get_post_meta(get_the_ID(), 'zm_like', true) ) : ?><span class="grid-like"><span class="be be-thumbs-up-o">&nbsp;<?php zm_get_current_count(); ?></span></span><?php endif; ?>
						<?php echo t_mark(); ?>
					</span>
				<?php } ?>
			</span>
			<div class="clear"></div>
		</div>
	</article>
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
if ( zm_get_option( 'cms_special' ) ) {
	if ( !is_paged() ) :
	echo '<div class="cms-cover-box sort" name="' . zm_get_option( 'cms_special_s' ) . '">';
		page_special();
	echo '</div>';
	endif;
}

if ( zm_get_option( 'cms_special_list' ) ) {
	if ( !is_paged() ) :
	echo '<div class="cms-cover-box sort" name="' . zm_get_option( 'cms_special_list_s' ) . '">';
		page_special_list();
	echo '</div>';
	endif;
}
get_template_part( '/cms/cat-cover' );
get_template_part( '/cms/cms-widget-two-menu' );
require get_template_directory() . '/cms/cms-news.php';
require get_template_directory() . '/cms/cms-new-code.php';
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
if ( zm_get_option( 'cms_ajax_tabs' ) ) {
echo '<div class="sort" name="'. zm_get_option('tab_h_s') .'">';
get_template_part( '/cms/cms-code-cat-tab' ); 
echo '</div>';
}
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
require get_template_directory() . '/cms/cms-cat-code.php';
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
	get_template_part( '/group/group-explain' );
	get_template_part( '/group/group-notice' );
	get_template_part( '/group/group-cover' );
	get_template_part( '/group/group-dean' );
	get_template_part( '/group/group-foldimg' );
	get_template_part( '/group/group-process' );
	get_template_part( '/group/group-assist' );
	require get_template_directory() . '/group/group-strong.php';
	get_template_part( '/group/group-help' );
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
	require get_template_directory() . '/group/group-cat-lr.php';
	require get_template_directory() . '/group/group-cat-code.php';
} ?>
<?php group(); ?>
</div>
<div class="clear"></div>
</div>
<?php }

// fall
function fall_main() { ?>
<section id="post-fall" class="content-area">
	<main id="main" class="be-main fall-main post-fall" role="main">
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

// qa
function beqa_article() { ?>
<?php if (zm_get_option('post_no_margin')) { ?>
<article id="post-<?php the_ID(); ?>" <?php aos_a(); ?> <?php post_class( 'post ms bk doclose scl' ); ?>>
<?php } else { ?>
<article id="post-<?php the_ID(); ?>" <?php aos_a(); ?> <?php post_class( 'post ms bk scl' ); ?>>
<?php } ?>
	<?php 
		echo '<div class="qa-cat-avatar load gdz">';
		// echo '<a href="' . get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ) . '">';
		echo '<a href="' . get_permalink() . '" rel="external nofollow">';
			if (zm_get_option( 'cache_avatar' ) ) {
			echo begin_avatar( get_the_author_meta( 'email' ), '96', '', get_the_author() );
			} else {
				echo be_avatar_author();
			}
		echo '</a>';
		echo '</div>';
	?>

	<div class="qa-cat-content">
		<header class="qa-header">
			<?php the_title( sprintf( '<h2 class="entry-title gdz"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		</header>

		<div class="qa-cat-meta gdz">
			<?php 
				echo '<span class="qa-meta qa-cat">';
				the_category( ' ' );
				echo '</span>';

				echo '<span class="qa-meta qa-time"><span class="qa-meta-class"></span>';
				echo '<time datetime="';
				echo get_the_date('Y-m-d');
				echo ' ' . get_the_time('H:i:s');
				echo '">';
				time_ago( $time_type ='post' );
				echo '</time></span>';

				qa_get_comment_last();

				echo '<span class="qa-meta qa-r">';
					if (!zm_get_option('close_comments')) {
						echo '<span class="qa-meta qa-comment">';
							comments_popup_link( '<span class="no-comment"><i>' . sprintf( __( '回复', 'begin' ) ) . '</i>' . sprintf( __( '0', 'begin' ) ) . '</span>', '<i>' . sprintf( __( '回复', 'begin' ) ) . '</i>1 ', '<i>' . sprintf( __( '回复', 'begin' ) ) . '</i>%' );
						echo '</span>';
					}
					views_qa();
				echo '</span>';

			?>
			<div class="clear"></div>
		</div>
	</div>
</article>
<?php }