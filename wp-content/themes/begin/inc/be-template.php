<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// 博客布局
function blog_template() { ?>
<div id="primary" class="content-area common<?php if ( be_get_option( 'blog_no_sidebar' ) ) { ?> no-sidebar<?php } ?>">
	<main id="main" class="site-main<?php if ( ! be_get_option( 'blog_ajax' ) ) { ?> be-main<?php } ?><?php if (zm_get_option('post_no_margin')) { ?> domargin<?php } ?>" role="main">
		<?php if ( be_get_option( 'slider' ) ) { ?>
			<?php
				global $wpdb, $post;
				if ( !is_paged() ) :
					require get_template_directory() . '/template/slider.php';
				endif;
			?>
		<?php } ?>

		<?php if ( be_get_option( 'blog_top' ) ) { ?>
			<?php
				if ( !is_paged() ) :
					get_template_part( 'template/b-top' );
				endif;
			?>
		<?php } ?>

		<?php
			if ( be_get_option( 'blog_special' ) ) {
				if ( !is_paged() ) :
					page_special();
				endif;
			}
		?>

		<?php
			if ( be_get_option( 'blog_special_list' ) ) {
				if ( !is_paged() ) :
					page_special_list();
				endif;
			}
		?>

		<?php 
			if ( be_get_option( 'blog_cat_cover' ) ) {
				if ( !is_paged() ) :
					echo '<div class="blog-cover betip">';
						cat_cover();
						be_help( $text = '首页设置 → 博客布局 → 其它模块 → 分类封面' );
					echo '</div>';
				endif;
			}
		?>
<?php }

// 图片布局
function grid_template_a() { ?>
	<?php if ( be_get_option( 'slider' ) ) { ?>
		<?php
			global $wpdb, $post;
			if ( !is_paged() ) :
				require get_template_directory() . '/template/slider.php';
			endif;
		?>
	<?php } ?>

	<?php 
		if ( be_get_option( 'img_cat_cover' ) ) {
			if ( !is_paged() ) :
				echo '<div class="grid-cover betip">';
					cat_cover();
					be_help( $text = '首页设置 → 图片布局 → 其它模块 → 分类封面' );
				echo '</div>';
			endif;
		}
	?>

	<?php if ( be_get_option( 'img_top' ) ) { ?>
		<?php
			if ( !is_paged() ) :
				get_template_part( 'template/img-top' );
			endif;
		?>
	<?php } ?>

	<?php
		if ( be_get_option( 'img_special' ) ) {
			if ( !is_paged() ) :
				page_special();
			endif;
		}
	?>

<?php }
function grid_template_b() { ?>
	<?php grid_template_a(); ?>
	<section id="picture" class="picture-area content-area grid-cat-<?php echo be_get_option('img_f'); ?>">
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
<article id="post-<?php the_ID(); ?>" class="post-item-list post ajax-grid-img scl" <?php aos_a(); ?>>
	<div class="boxs1">
		<div class="picture-box ms<?php echo fill_class(); ?>"<?php echo be_img_fill(); ?>>
			<figure class="picture-img">
				<?php echo be_img_excerpt(); ?>
				<?php if ( get_post_meta(get_the_ID(), 'direct', true) ) { ?>
					<?php $direct = get_post_meta(get_the_ID(), 'direct', true); ?>
					<?php echo zm_thumbnail_link(); ?>
				<?php } else { ?>
					<?php echo zm_grid_thumbnail(); ?>
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
	</div>
</article>
<?php endwhile;?>
<?php }

// 分类图片
function grid_cat_template() { ?>
<?php if ( be_get_option( 'grid_cat_slider' ) ) { ?>
	<?php
		global $post;
		require get_template_directory() . '/template/slider.php';
	?>
<?php } ?>

<section id="grid-cat" class="grid-cat-content content-area">
	<main id="main" class="be-main site-main" role="main">
		<?php 
			global $wpdb, $post; $do_not_duplicate[] = '';
			if ( be_get_option( 'catimg_cat_cover' ) ) {
					echo '<div class="catimg-cover betip">';
					cat_cover();
					be_help( $text = '首页设置 → 分类图片 → 其它模块 → 分类封面' );
					echo '</div>';
			}
			if ( be_get_option( 'catimg_top' ) ) {
				require get_template_directory() . '/grid/grid-top.php';
			}
			require get_template_directory() . '/grid/grid-cat-new.php';
			if ( zm_get_option( 'filters' ) && be_get_option( 'catimg_filter' ) ) {
				get_template_part( '/inc/filter-general' );
			}
			if ( be_get_option( 'catimg_special' ) ) {
				page_special();
			}
			if ( be_get_option( 'imgcat_items_a' ) ) {
				echo be_catimg_items_a();
			}
			get_template_part( '/grid/grid-widget-one' );
			require get_template_directory() . '/grid/grid-cat-carousel.php';
			if ( be_get_option( 'imgcat_items_b' ) ) {
				echo be_catimg_items_b();
			}
			get_template_part( '/grid/grid-widget-two' );
			if ( be_get_option( 'catimg_special_list' ) ) {
				echo '<div class="catimg-cover-box">';
					page_special_list();
				echo '</div>';
			}

			if ( be_get_option( 'catimg_ajax_cat' ) ) {
				echo '<div class="catimg-ajax-cat-post betip">';
				echo do_shortcode( be_get_option( 'catimg_ajax_cat_post_code' ) );
				be_help( $text = '首页设置 → 分类图片 → Ajax分类短代码' );
				echo '</div>';
			}
			if ( be_get_option( 'imgcat_items_c' ) ) {
				echo be_catimg_items_c();
			}
		 ?>
	</main>
</section>
<?php }

// grid new
function grid_new() { ?>
<?php global $wpdb, $post; ?>
	<article id="post-<?php the_ID(); ?>" class="post-item-list post gn aos-animate" <?php aos_a(); ?>>
		<div class="boxs1">
			<div class="grid-cat-bx4 grid-cat-new ms<?php echo fill_class(); ?>"<?php echo be_img_fill(); ?>>
				<figure class="picture-img">
					<?php if ( get_post_meta(get_the_ID(), 'direct', true) ) { ?>
						<?php echo zm_thumbnail_link(); ?>
					<?php } else { ?>
						<?php echo zm_thumbnail(); ?>
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
		</div>
	</article>
<?php }

// 杂志布局
function cms_template() {
	global $wpdb, $post; $do_not_duplicate[] = '';
	$output = [];
	$order  = [];

	// 推荐文章
	ob_start();
	get_template_part( '/cms/cms-top' );
	$order[] = [
		'sort_key' => be_get_option( 'cms_top_s' ),
		'type'     => 'top'
	];
	$output[] = ob_get_clean();

	// 专题
	ob_start();
	if ( be_get_option( 'cms_special' ) ) {
		if ( ! is_paged() ) :
		echo '<div class="cms-cover-box">';
			page_special();
		echo '</div>';
		endif;
	}
	$order[] = [
		'sort_key' => be_get_option( 'cms_special_s' ),
		'type'     => 'special'
	];
	$output[] = ob_get_clean();

	// 专题列表
	ob_start();
	if ( be_get_option( 'cms_special_list' ) ) {
		if ( ! is_paged() ) :
		echo '<div class="cms-cover-box">';
			page_special_list();
		echo '</div>';
		endif;
	}
	$order[] = [
		'sort_key' => be_get_option( 'cms_special_list_s' ),
		'type'     => 'pecial_list'
	];
	$output[] = ob_get_clean();

	// 分类封面
	ob_start();
	get_template_part( '/cms/cat-cover' );
	$order[] = [
		'sort_key' => be_get_option( 'cms_cover_s' ),
		'type'     => 'cover'
	];
	$output[] = ob_get_clean();

	// 最新文章
	ob_start();
	require get_template_directory() . '/cms/cms-news.php';
	$order[] = [
		'sort_key' => be_get_option( 'news_s' ),
		'type'     => 'news'
	];
	$output[] = ob_get_clean();

	// 最新分类
	ob_start();
	require get_template_directory() . '/cms/cms-new-code.php';
	$order[] = [
		'sort_key' => be_get_option( 'cms_new_code_s' ),
		'type'     => 'newcode'
	];
	$output[] = ob_get_clean();

	// 组合推荐
	ob_start();
	require get_template_directory() . '/cms/cms-collect.php';
	$order[] = [
		'sort_key' => be_get_option( 'cms_collect_s' ),
		'type'     => 'collect'
	];
	$output[] = ob_get_clean();

	// 小说书籍
	ob_start();
	get_template_part( '/cms/cms-novel' );
	$order[] = [
		'sort_key' => be_get_option( 'cms_cat_novel_s' ),
		'type'     => 'novel'
	];
	$output[] = ob_get_clean();

	// 分类模块A
	ob_start();
	require get_template_directory() . '/cms/cms-ajax-cat-a.php';
	$order[] = [
		'sort_key' => be_get_option( 'cms_ajax_item_a_s' ),
		'type'     => 'ajaxcata'
	];
	$output[] = ob_get_clean();

	// 多条件筛选A
	ob_start();
	get_template_part( '/inc/filter-home' );
	$order[] = [
		'sort_key' => be_get_option( 'cms_filter_s' ),
		'type'     => 'filter'
	];
	$output[] = ob_get_clean();

	// 首字母分类/标签A
	ob_start();
	get_template_part( '/template/letter-show' );
	$order[] = [
		'sort_key' => be_get_option( 'letter_show_s' ),
		'type'     => 'letter'
	];
	$output[] = ob_get_clean();

	// 杂志单栏小工具
	ob_start();
	get_template_part( '/cms/cms-widget-one' );
	$order[] = [
		'sort_key' => be_get_option( 'cms_widget_one_s' ),
		'type'     => 'widgetone'
	];
	$output[] = ob_get_clean();

	// 杂志菜单小工具
	ob_start();
	get_template_part( '/cms/cms-widget-two-menu' );
	$order[] = [
		'sort_key' => be_get_option( 'cms_two_menu_s' ),
		'type'     => 'twomenu'
	];
	$output[] = ob_get_clean();

	// AJAX分类
	ob_start();
	require get_template_directory() . '/cms/cms-cat-tab.php';
	$order[] = [
		'sort_key' => be_get_option( 'cms_cat_tab_s' ),
		'type'     => 'cattab'
	];
	$output[] = ob_get_clean();

	// 图片模块
	ob_start();
	require get_template_directory() . '/cms/cms-picture.php';
	$order[] = [
		'sort_key' => be_get_option( 'picture_s' ),
		'type'     => 'picture'
	];
	$output[] = ob_get_clean();

	// 杂志两栏小工具
	ob_start();
	get_template_part( '/cms/cms-widget-two' );
	$order[] = [
		'sort_key' => be_get_option( 'cms_widget_two_s' ),
		'type'     => 'widgettwo'
	];
	$output[] = ob_get_clean();

	// 单栏分类列表(5篇文章)
	ob_start();
	require get_template_directory() . '/cms/cms-cat-one-5.php';
	$order[] = [
		'sort_key' => be_get_option( 'cat_one_5_s' ),
		'type'     => 'one5'
	];
	$output[] = ob_get_clean();

	// 单栏分类列表(无缩略图)
	ob_start();
	require get_template_directory() . '/cms/cms-cat-one-no-img.php';
	$order[] = [
		'sort_key' => be_get_option( 'cat_one_on_img_s' ),
		'type'     => 'oneno'
	];
	$output[] = ob_get_clean();

	// 单栏分类列表(10篇文章)
	ob_start();
	require get_template_directory() . '/cms/cms-cat-one-10.php';
	$order[] = [
		'sort_key' => be_get_option( 'cat_one_10_s' ),
		'type'     => 'one10'
	];
	$output[] = ob_get_clean();

	// 视频模块
	ob_start();
	require get_template_directory() . '/cms/cms-video.php';
	$order[] = [
		'sort_key' => be_get_option( 'video_s' ),
		'type'     => 'video'
	];
	$output[] = ob_get_clean();

	// 混排分类列表
	ob_start();
	require get_template_directory() . '/cms/cms-cat-lead.php';
	$order[] = [
		'sort_key' => be_get_option( 'cat_lead_s' ),
		'type'     => 'lead'
	];
	$output[] = ob_get_clean();

	// 两栏分类列表
	ob_start();
	require get_template_directory() . '/cms/cms-cat-small.php';
	$order[] = [
		'sort_key' => be_get_option( 'cat_small_s' ),
		'type'     => 'small'
	];
	$output[] = ob_get_clean();

	// Tab组合分类
	ob_start();
	if ( be_get_option( 'cms_ajax_tabs' ) ) {
		echo '<div class="betip">';
		get_template_part( '/cms/cms-code-cat-tab' ); 
		cms_help( $text = '首页设置→杂志布局→Tab组合分类', $number = 'tab_h_s' );
		echo '</div>';
	}
	$order[] = [
		'sort_key' => be_get_option( 'tab_h_s' ),
		'type'     => 'codetab'
	];
	$output[] = ob_get_clean();

	// 会员资源
	ob_start();
	require get_template_directory() . '/cms/cms-vip.php';
	$order[] = [
		'sort_key' => be_get_option( 'cms_vip_s' ),
		'type'     => 'vip'
	];
	$output[] = ob_get_clean();

	// 项目模块
	ob_start();
	require get_template_directory() . '/cms/cms-show.php';
	$order[] = [
		'sort_key' => be_get_option( 'products_on_s' ),
		'type'     => 'show'
	];
	$output[] = ob_get_clean();

	// 特色模块
	ob_start();
	if ( be_get_option( 'grid_ico_cms' ) ) { grid_md_cms(); }
	$order[] = [
		'sort_key' => be_get_option( 'grid_ico_cms_s' ),
		'type'     => 'md_cms'
	];
	$output[] = ob_get_clean();

	// 分类模块B
	ob_start();
	require get_template_directory() . '/cms/cms-ajax-cat-b.php';
	$order[] = [
		'sort_key' => be_get_option( 'cms_ajax_item_b_s' ),
		'type'     => 'catb'
	];
	$output[] = ob_get_clean();

	// 工具模块
	ob_start();
	get_template_part( '/cms/cms-tool' );
	$order[] = [
		'sort_key' => be_get_option( 'cms_tool_s' ),
		'type'     => 'tool'
	];
	$output[] = ob_get_clean();

	// 热门推荐
	ob_start();
	get_template_part( '/cms/cms-hot' );
	$order[] = [
		'sort_key' => be_get_option( 'cms_hot_s' ),
		'type'     => 'hot'
	];
	$output[] = ob_get_clean();

	// 杂志三栏小工具
	ob_start();
	get_template_part( '/cms/cms-widget-three' );
	$order[] = [
		'sort_key' => be_get_option( 'cat_widget_three_s' ),
		'type'     => 'widgetthree'
	];
	$output[] = ob_get_clean();

	// 分类图片
	ob_start();
	require get_template_directory() . '/cms/cms-cat-square.php';
	$order[] = [
		'sort_key' => be_get_option( 'cat_square_s' ),
		'type'     => 'square'
	];
	$output[] = ob_get_clean();

	// 书籍封面
	ob_start();
	require get_template_directory() . '/cms/cms-novel-cover.php';
	$order[] = [
		'sort_key' => be_get_option( 'cms_novel_cover_s' ),
		'type'     => 'novelcover'
	];
	$output[] = ob_get_clean();

	// 分类网格
	ob_start();
	require get_template_directory() . '/cms/cms-cat-grid.php';
	$order[] = [
		'sort_key' => be_get_option( 'cat_grid_s' ),
		'type'     => 'grid'
	];
	$output[] = ob_get_clean();

	// 图片滚动模块
	ob_start();
	require get_template_directory() . '/cms/cms-scrolling.php';
	$order[] = [
		'sort_key' => be_get_option( 'flexisel_s' ),
		'type'     => 'scrolling'
	];
	$output[] = ob_get_clean();

	// 底部分类列表
	ob_start();
	require get_template_directory() . '/cms/cms-cat-big.php';
	$order[] = [
		'sort_key' => be_get_option( 'cat_big_s' ),
		'type'     => 'big'
	];
	$output[] = ob_get_clean();

	// 会员商品
	ob_start();
	require get_template_directory() . '/cms/cms-assets.php';
	$order[] = [
		'sort_key' => be_get_option( 'cms_assets_s' ),
		'type'     => 'assets'
	];
	$output[] = ob_get_clean();

	// 商品
	ob_start();
	require get_template_directory() . '/cms/cms-tao.php';
	$order[] = [
		'sort_key' => be_get_option( 'tao_h_s' ),
		'type'     => 'tao'
	];
	$output[] = ob_get_clean();

	// WOO产品
	ob_start();
	if (function_exists( 'is_shop' )) {
		get_template_part( '/woocommerce/be-woo/cms-woo' );
	}
	$order[] = [
		'sort_key' => be_get_option( 'product_h_s' ),
		'type'     => 'woo'
	];
	$output[] = ob_get_clean();

	// 底部无缩略图分类列表
	ob_start();
	require get_template_directory() . '/cms/cms-cat-big-n.php';
	$order[] = [
		'sort_key' => be_get_option( 'cat_big_not_s' ),
		'type'     => 'bign'
	];
	$output[] = ob_get_clean();

	// 标签标题
	ob_start();
	require get_template_directory() . '/cms/cms-cat-tdk.php';
	$order[] = [
		'sort_key' => be_get_option( 'cat_tdk_s' ),
		'type'     => 'tdk'
	];
	$output[] = ob_get_clean();

	// Ajax分类短代码
	ob_start();
	require get_template_directory() . '/cms/cms-cat-code.php';
	$order[] = [
		'sort_key' => be_get_option( 'cms_ajax_cat_post_s' ),
		'type'     => 'catcode'
	];
	$output[] = ob_get_clean();

	// 软件下载
	ob_start();
	require get_template_directory() . '/cms/cms-down.php';
	$order[] = [
		'sort_key' => be_get_option( 'cms_down_s' ),
		'type'     => 'down'
	];
	$output[] = ob_get_clean();

	// 分类组合
	ob_start();
	require get_template_directory() . '/cms/cms-portfolio.php';
	$order[] = [
		'sort_key' => be_get_option( 'cms_portfolio_s' ),
		'type'     => 'portfolio'
	];
	$output[] = ob_get_clean();

	// 热门分类
	ob_start();
	require get_template_directory() . '/cms/cms-cat-hot.php';
	$order[] = [
		'sort_key' => be_get_option( 'cms_cat_hot_s' ),
		'type'     => 'cathot'
	];
	$output[] = ob_get_clean();

	// RSS聚合
	ob_start();
	require get_template_directory() . '/cms/cms-rss.php';
	$order[] = [
		'sort_key' => be_get_option( 'cms_rss_s' ),
		'type'     => 'rss'
	];
	$output[] = ob_get_clean();

	// 图片新闻
	ob_start();
	require get_template_directory() . '/cms/cms-cat-img.php';
	$order[] = [
		'sort_key' => be_get_option( 'cms_cat_img_s' ),
		'type'     => 'catimg'
	];
	$output[] = ob_get_clean();

	// 图文幻灯
	ob_start();
	require get_template_directory() . '/cms/cms-sliders-a.php';
	$order[] = [
		'sort_key' => be_get_option( 'cms_sliders_a_s' ),
		'type'     => 'slidersa'
	];
	$output[] = ob_get_clean();

	// 网址收藏
	ob_start();
	require get_template_directory() . '/cms/cms-sites.php';
	$order[] = [
		'sort_key' => be_get_option( 'cms_sites_s' ),
		'type'     => 'sites'
	];
	$output[] = ob_get_clean();

	// 分类幻灯
	ob_start();
	require get_template_directory() . '/cms/cms-sliders-cat.php';
	$order[] = [
		'sort_key' => be_get_option( 'cms_sliders_cat_s' ),
		'type'     => 'sliderscat'
	];
	$output[] = ob_get_clean();

	// 图文卡片
	ob_start();
	require get_template_directory() . '/cms/cms-each.php';
	$order[] = [
		'sort_key' => be_get_option( 'cms_each_s' ),
		'type'     => 'each'
	];
	$output[] = ob_get_clean();

	// 广告信息
	ob_start();
	$cmsads = ( array ) be_get_option( 'cms_ads_item' );
	foreach ( $cmsads as $items ) {
		ob_start();
		get_template_part( '/cms/cms-ads', null, array( 'items' => $items ) );
		$output[] = ob_get_clean();
		$order[]  = [
			'sort_key' => $items['cms_ads_s'],
			'type'     => 'ads'
		];
	}

	if ( ! be_get_option( 'slider_l' ) || ( be_get_option( 'slider_l' ) == 'slider_w' ) ) {
		require get_template_directory() . '/template/slider.php';
	}

	if ( be_get_option( 'slider_l' ) == 'slider_t' ) {
		require get_template_directory() . '/cms/cms-slider.php';
	}

	if ( be_get_option( 'cms_slider_sticky' ) && be_get_option( 'cms_no_s' ) ) {
		echo '<div id="primary-cms">';
	}

	if ( be_get_option( 'cms_no_s' ) ) {
		echo '<div id="primary" class="content-area">';
	} else {
		echo '<div id="cms-primary" class="content-area">';
	}
	echo '<main id="main" class="site-main" role="main">';
	if ( be_get_option( 'slider_l' ) == 'slider_n' ) {
		require get_template_directory() . '/template/slider.php';
	}

	// 进行排序
	array_multisort( array_column( $order, 'sort_key' ), $output );

	if ( be_get_option( 'cms_no_s' ) ) {
		// 有侧边栏输出前24个元素
		foreach ( array_slice( $output, 0, be_get_option( 'cms_sort_part' ) ) as $content ) {
			echo $content;
		}
	}
	echo '</main>';

	if ( ! be_get_option( 'cms_no_s' ) ) {
		// 无侧边栏时输出全部
		foreach ( $output as $content ) {
			echo $content;
		}
		
	} else {
		echo '</div>';
		echo get_sidebar('cms');

		if ( be_get_option( 'cms_slider_sticky' ) ) {
			echo '</div>';
		}

		// 有侧边栏输出剩余的
		foreach ( array_slice( $output, be_get_option( 'cms_sort_part' ) ) as $content ) {
			echo $content;
		}
	}

	echo '<div class="clear"></div>';
	if ( ! be_get_option( 'cms_no_s' ) ) {
		echo '</div>';
	}
}

// 公司布局
function group_template() {
	echo '<div class="container">';
	get_template_part( '/group/group-slider' );

	$line = co_get_option( 'g_line' ) ? 'line-color' : 'line-white';
	echo '<div id="group-section" class="group-section ' . $line . '">';
	function group() {
		global $wpdb, $post; $do_not_cat[] = '';
		$output = [];
		$order  = [];

		// 关于我们
		ob_start();
		$contact = ( array ) co_get_option( 'group_contact_item' );
		foreach ( $contact as $items ) {
			ob_start();
			get_template_part( '/group/group-contact', null, array( 'items' => $items ) );
			if ( isset( $items['group_contact_s'] ) ) {
				$output[] = ob_get_clean();
				$order[]  = [
					'sort_key' => $items['group_contact_s'],
					'type'     => 'contact'
				];
			}
		}

		// 说明
		ob_start();
		get_template_part( '/group/group-explain' );
		$order[] = [
			'sort_key' => co_get_option( 'group_explain_s' ),
			'type'     => 'explain'
		];
		$output[] = ob_get_clean();

		// 关于本站
		ob_start();
		get_template_part( '/group/group-about' );
		$order[] = [
			'sort_key' => co_get_option( 'group_about_s' ),
			'type'     => 'about'
		];
		$output[] = ob_get_clean();

		// 公示板
		ob_start();
		get_template_part( '/group/group-notice' );
		$order[] = [
			'sort_key' => co_get_option( 'group_notice_s' ),
			'type'     => 'notice'
		];
		$output[] = ob_get_clean();

		// 分类封面
		ob_start();
		get_template_part( '/group/group-cover' );
		$order[] = [
			'sort_key' => co_get_option( 'group_cat_cover_s' ),
			'type'     => 'cover'
		];
		$output[] = ob_get_clean();

		// 服务项目
		ob_start();
		get_template_part( '/group/group-dean' );
		$order[] = [
			'sort_key' => co_get_option( 'dean_s' ),
			'type'     => 'dean'
		];
		$output[] = ob_get_clean();

		// 推荐
		ob_start();
		get_template_part( '/group/group-foldimg' );
		$order[] = [
			'sort_key' => co_get_option( 'foldimg_s' ),
			'type'     => 'foldimg'
		];
		$output[] = ob_get_clean();

		// 流程
		ob_start();
		get_template_part( '/group/group-process' );
		$order[] = [
			'sort_key' => co_get_option( 'process_s' ),
			'type'     => 'process'
		];
		$output[] = ob_get_clean();

		// 支持
		ob_start();
		get_template_part( '/group/group-assist' );
		$order[] = [
			'sort_key' => co_get_option( 'group_assist_s' ),
			'type'     => 'assist'
		];
		$output[] = ob_get_clean();

		// 咨询
		ob_start();
		require get_template_directory() . '/group/group-strong.php';
		$order[] = [
			'sort_key' => co_get_option( 'group_strong_s' ),
			'type'     => 'strong'
		];
		$output[] = ob_get_clean();

		// 帮助
		ob_start();
		get_template_part( '/group/group-help' );
		$order[] = [
			'sort_key' => co_get_option( 'group_help_s' ),
			'type'     => 'help'
		];
		$output[] = ob_get_clean();

		// 工具
		ob_start();
		get_template_part( '/group/group-tool' );
		$order[] = [
			'sort_key' => co_get_option( 'tool_s' ),
			'type'     => 'tool'
		];
		$output[] = ob_get_clean();

		// 项目模块
		ob_start();
		get_template_part( '/group/group-show' );
		$order[] = [
			'sort_key' => co_get_option( 'group_show_s' ),
			'type'     => 'show'
		];
		$output[] = ob_get_clean();

		// 服务宗旨
		ob_start();
		get_template_part( '/group/group-service' );
		$order[] = [
			'sort_key' => co_get_option( 'service_s' ),
			'type'     => 'service'
		];
		$output[] = ob_get_clean();

		// WOO产品
		ob_start();
		if ( function_exists( 'is_shop' ) ) {
			get_template_part( '/woocommerce/be-woo/group-woo' );
		}
		$order[] = [
			'sort_key' => co_get_option( 'g_product_s' ),
			'type'     => 'woo'
		];
		$output[] = ob_get_clean();

		// 特色
		ob_start();
		if ( co_get_option( 'group_ico' ) ) { grid_md_group(); }
		$order[] = [
			'sort_key' => co_get_option( 'group_ico_s' ),
			'type'     => 'md'
		];
		$output[] = ob_get_clean();

		// 描述
		ob_start();
		get_template_part( '/group/group-post' );
		$order[] = [
			'sort_key' => co_get_option( 'group_post_s' ),
			'type'     => 'post'
		];
		$output[] = ob_get_clean();

		// 简介
		ob_start();
		get_template_part( '/group/group-features' );
		$order[] = [
			'sort_key' => co_get_option( 'group_features_s' ),
			'type'     => 'features'
		];
		$output[] = ob_get_clean();

		// 展示
		ob_start();
		get_template_part( '/group/group-cat-img' );
		$order[] = [
			'sort_key' => co_get_option( 'group_img_s' ),
			'type'     => 'catimg'
		];
		$output[] = ob_get_clean();

		// 计数器
		ob_start();
		get_template_part( '/group/group-counter' );
		$order[] = [
			'sort_key' => co_get_option( 'counter_s' ),
			'type'     => 'counter'
		];
		$output[] = ob_get_clean();

		// 合作
		ob_start();
		get_template_part( '/group/group-coop' );
		$order[] = [
			'sort_key' => co_get_option( 'coop_s' ),
			'type'     => 'coop'
		];
		$output[] = ob_get_clean();

		// 分类左右图
		ob_start();
		get_template_part( '/group/group-wd' );
		$order[] = [
			'sort_key' => co_get_option( 'group_wd_s' ),
			'type'     => 'wd'
		];
		$output[] = ob_get_clean();

		// 一栏小工具
		ob_start();
		get_template_part( '/group/group-widget-one' );
		$order[] = [
			'sort_key' => co_get_option( 'group_widget_one_s' ),
			'type'     => 'widgetone'
		];
		$output[] = ob_get_clean();

		// 最新文章
		ob_start();
		require get_template_directory() . '/group/group-news.php';
		$order[] = [
			'sort_key' => co_get_option( 'group_new_s' ),
			'type'     => 'news'
		];
		$output[] = ob_get_clean();

		// 商品模块
		ob_start();
		get_template_part( '/group/group-tao' );
		$order[] = [
			'sort_key' => co_get_option( 'g_tao_s' ),
			'type'     => 'tao'
		];
		$output[] = ob_get_clean();

		// 三栏小工具
		ob_start();
		get_template_part( '/group/group-widget-three' );
		$order[] = [
			'sort_key' => co_get_option( 'group_widget_three_s' ),
			'type'     => 'widgetthree'
		];
		$output[] = ob_get_clean();

		// 新闻资讯A
		ob_start();
		require get_template_directory() . '/group/group-cat-a.php';
		$order[] = [
			'sort_key' => co_get_option( 'group_cat_a_s' ),
			'type'     => 'cata'
		];
		$output[] = ob_get_clean();

		// 两栏小工具
		ob_start();
		get_template_part( '/group/group-widget-two' );
		$order[] = [
			'sort_key' => co_get_option( 'group_widget_two_s' ),
			'type'     => 'widgettwo'
		];
		$output[] = ob_get_clean();

		// 新闻资讯B
		ob_start();
		require get_template_directory() . '/group/group-cat-b.php';
		$order[] = [
			'sort_key' => co_get_option( 'group_cat_b_s' ),
			'type'     => 'catb'
		];
		$output[] = ob_get_clean();

		// AJAX分类
		ob_start();
		require get_template_directory() . '/group/group-tab.php';
		$order[] = [
			'sort_key' => co_get_option( 'group_tab_s' ),
			'type'     => 'tab'
		];
		$output[] = ob_get_clean();

		// 展望
		ob_start();
		require get_template_directory() . '/group/group-outlook.php';
		$order[] = [
			'sort_key' => co_get_option( 'group_outlook_s' ),
			'type'     => 'outlook'
		];
		$output[] = ob_get_clean();

		// 新闻资讯C
		ob_start();
		require get_template_directory() . '/group/group-cat-c.php';
		$order[] = [
			'sort_key' => co_get_option( 'group_cat_c_s' ),
			'type'     => 'catc'
		];
		$output[] = ob_get_clean();

		// 热门推荐
		ob_start();
		require get_template_directory() . '/group/group-carousel.php';
		$order[] = [
			'sort_key' => co_get_option( 'group_carousel_s' ),
			'type'     => 'carousel'
		];
		$output[] = ob_get_clean();

		// 新闻资讯D
		ob_start();
		require get_template_directory() . '/group/group-cat-d.php';
		$order[] = [
			'sort_key' => co_get_option( 'group_cat_d_s' ),
			'type'     => 'catd'
		];
		$output[] = ob_get_clean();

		// 分类短代码
		ob_start();
		require get_template_directory() . '/group/group-cat-code.php';
		$order[] = [
			'sort_key' => co_get_option( 'group_ajax_cat_post_s' ),
			'type'     => 'code'
		];
		$output[] = ob_get_clean();

		// 会员商品
		ob_start();
		require get_template_directory() . '/group/group-assets.php';
		$order[] = [
			'sort_key' => co_get_option( 'group_assets_s' ),
			'type'     => 'assets'
		];
		$output[] = ob_get_clean();

		// 软件下载
		ob_start();
		require get_template_directory() . '/group/group-down.php';
		$order[] = [
			'sort_key' => co_get_option( 'group_down_s' ),
			'type'     => 'down'
		];
		$output[] = ob_get_clean();

		// 分类组合
		ob_start();
		require get_template_directory() . '/group/group-portfolio.php';
		$order[] = [
			'sort_key' => co_get_option( 'group_portfolio_s' ),
			'type'     => 'portfolio'
		];
		$output[] = ob_get_clean();

		// 热门分类
		ob_start();
		require get_template_directory() . '/group/group-cat-hot.php';
		$order[] = [
			'sort_key' => co_get_option( 'group_cat_hot_s' ),
			'type'     => 'hot'
		];
		$output[] = ob_get_clean();

		// 图文幻灯
		ob_start();
		require get_template_directory() . '/group/group-sliders-text.php';
		$order[] = [
			'sort_key' => co_get_option( 'group_slides_text_s' ),
			'type'     => 'slidersa'
		];
		$output[] = ob_get_clean();

		// 图文简介
		ob_start();
		require get_template_directory() . '/group/group-intro.php';
		$order[] = [
			'sort_key' => co_get_option( 'group_intro_s' ),
			'type'     => 'intro'
		];
		$output[] = ob_get_clean();

		// 解决方案
		ob_start();
		require get_template_directory() . '/group/group-scheme.php';
		$order[] = [
			'sort_key' => co_get_option( 'group_scheme_s' ),
			'type'     => 'scheme'
		];
		$output[] = ob_get_clean();

		// 联系我们
		ob_start();
		require get_template_directory() . '/group/group-email.php';
		$order[] = [
			'sort_key' => co_get_option( 'group_email_s' ),
			'type'     => 'email'
		];
		$output[] = ob_get_clean();

		// 行业新闻
		ob_start();
		require get_template_directory() . '/group/group-each.php';
		$order[] = [
			'sort_key' => co_get_option( 'group_each_s' ),
			'type'     => 'each'
		];
		$output[] = ob_get_clean();

		// 图片说明
		ob_start();
		$groupdes = ( array ) co_get_option( 'group_des_item' );
		foreach ( $groupdes as $items ) {
			ob_start();
			get_template_part( '/group/group-des', null, array( 'items' => $items ) );
			$output[] = ob_get_clean();
			$order[]  = [
				'sort_key' => $items['group_des_s'],
				'type'     => 'des'
			];
		}

		// 专题新闻
		ob_start();
		$special = ( array ) co_get_option( 'group_special_item' );
		foreach ( $special as $items ) {
			ob_start();
			$items = $items;
			require get_template_directory() . '/group/group-special.php';
			$output[] = ob_get_clean();
			$order[]  = [
				'sort_key' => $items['group_special_s'],
				'type'     => 'special'
			];
		}

		// 广告信息
		ob_start();
		$groupads = ( array ) co_get_option( 'group_ads_item' );
		foreach ( $groupads as $items ) {
			ob_start();
			get_template_part( '/group/group-ads', null, array( 'items' => $items ) );
			$output[] = ob_get_clean();
			$order[]  = [
				'sort_key' => $items['group_ads_s'],
				'type'     => 'ads'
			];
		}

		// 排序输出
		array_multisort( array_column( $order, 'sort_key' ), $output );
		foreach ( $output as $content ) {
			echo $content;
		}
	}

	group();
	echo '</div>';
	echo '<div class="clear"></div>';
	echo '</div>';
}

// fall
function fall_main() { ?>
<section id="post-fall" class="content-area">
	<main id="main" class="be-main fall-main post-fall" role="main">
		<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" class="post-item-list post fall fall-off scl" <?php aos_a(); ?>>
			<div class="boxs1">
				<div class="fall-box load<?php echo fill_class(); ?>"<?php echo be_img_fill(); ?>>
					<?php 
					global $post;
					$content = $post->post_content;
					preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
					$n = count($strResult[1]);
					if ( $n > 0 ) { ?>
						<figure class="fall-img">
							<?php echo zm_waterfall_img(); ?>
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
<article id="post-<?php the_ID(); ?>" class="post-item-list post ms doclose scl" <?php aos_a(); ?>>
<?php } else { ?>
<article id="post-<?php the_ID(); ?>" class="post-item-list post ms scl" <?php aos_a(); ?>>
<?php } ?>
	<?php if ( get_option( 'show_avatars' ) ) { ?>
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
	<?php } ?>
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


// 页面构建
function build_template() {
	echo '<div class="container">';
	get_template_part( '/build/group-slider' );

	$line = be_build( get_the_ID(), 'g_line' ) ? 'line-color' : 'line-white';
	echo '<div id="group-section" class="group-section ' . $line . '">';
	function co_build() {
		global $wpdb, $post; $do_not_cat[] = '';
		$output = [];
		$order  = [];

		// 关于我们
		ob_start();
		$contact = ( array ) be_build( get_the_ID(), 'group_contact_item' );
		foreach ( $contact as $items ) {
			ob_start();
			get_template_part( '/build/group-contact', null, array( 'items' => $items ) );
			if ( isset( $items['group_contact_s'] ) ) {
				$output[] = ob_get_clean();
				$order[]  = [
					'sort_key' => $items['group_contact_s'],
					'type'     => 'contact'
				];
			}
		}

		// 说明
		ob_start();
		get_template_part( '/build/group-explain' );
		$order[] = [
			'sort_key' => be_build( get_the_ID(), 'group_explain_s' ),
			'type'     => 'explain'
		];
		$output[] = ob_get_clean();

		// 关于本站
		ob_start();
		get_template_part( '/build/group-about' );
		$order[] = [
			'sort_key' => be_build( get_the_ID(), 'group_about_s' ),
			'type'     => 'about'
		];
		$output[] = ob_get_clean();

		// 公示板
		ob_start();
		get_template_part( '/build/group-notice' );
		$order[] = [
			'sort_key' => be_build( get_the_ID(), 'group_notice_s' ),
			'type'     => 'notice'
		];
		$output[] = ob_get_clean();

		// 分类封面
		ob_start();
		get_template_part( '/build/group-cover' );
		$order[] = [
			'sort_key' => be_build( get_the_ID(), 'group_cat_cover_s' ),
			'type'     => 'cover'
		];
		$output[] = ob_get_clean();

		// 服务项目
		ob_start();
		get_template_part( '/build/group-dean' );
		$order[] = [
			'sort_key' => be_build( get_the_ID(), 'dean_s' ),
			'type'     => 'dean'
		];
		$output[] = ob_get_clean();

		// 推荐
		ob_start();
		get_template_part( '/build/group-foldimg' );
		$order[] = [
			'sort_key' => be_build( get_the_ID(), 'foldimg_s' ),
			'type'     => 'foldimg'
		];
		$output[] = ob_get_clean();

		// 流程
		ob_start();
		get_template_part( '/build/group-process' );
		$order[] = [
			'sort_key' => be_build( get_the_ID(), 'process_s' ),
			'type'     => 'process'
		];
		$output[] = ob_get_clean();

		// 支持
		ob_start();
		get_template_part( '/build/group-assist' );
		$order[] = [
			'sort_key' => be_build( get_the_ID(), 'group_assist_s' ),
			'type'     => 'assist'
		];
		$output[] = ob_get_clean();

		// 咨询
		ob_start();
		require get_template_directory() . '/build/group-strong.php';
		$order[] = [
			'sort_key' => be_build( get_the_ID(), 'group_strong_s' ),
			'type'     => 'strong'
		];
		$output[] = ob_get_clean();

		// 帮助
		ob_start();
		get_template_part( '/build/group-help' );
		$order[] = [
			'sort_key' => be_build( get_the_ID(), 'group_help_s' ),
			'type'     => 'help'
		];
		$output[] = ob_get_clean();

		// 工具
		ob_start();
		get_template_part( '/build/group-tool' );
		$order[] = [
			'sort_key' => be_build( get_the_ID(), 'tool_s' ),
			'type'     => 'tool'
		];
		$output[] = ob_get_clean();

		// 项目模块
		ob_start();
		get_template_part( '/build/group-show' );
		$order[] = [
			'sort_key' => be_build( get_the_ID(), 'group_show_s' ),
			'type'     => 'show'
		];
		$output[] = ob_get_clean();

		// 服务宗旨
		ob_start();
		get_template_part( '/build/group-service' );
		$order[] = [
			'sort_key' => be_build( get_the_ID(), 'service_s' ),
			'type'     => 'service'
		];
		$output[] = ob_get_clean();

		// 正文
		ob_start();
		get_template_part( '/build/build-content' );

		$order[] = [
			'sort_key' => be_build( get_the_ID(), 'build_content_s' ),
			'type'     => 'content'
		];
		$output[] = ob_get_clean();

		// 特色
		ob_start();
		if ( be_build( get_the_ID(), 'group_ico' ) ) { grid_md_build(); }
		$order[] = [
			'sort_key' => be_build( get_the_ID(), 'group_ico_s' ),
			'type'     => 'md'
		];
		$output[] = ob_get_clean();

		// 描述
		ob_start();
		get_template_part( '/build/group-post' );
		$order[] = [
			'sort_key' => be_build( get_the_ID(), 'group_post_s' ),
			'type'     => 'post'
		];
		$output[] = ob_get_clean();

		// 简介
		ob_start();
		get_template_part( '/build/group-features' );
		$order[] = [
			'sort_key' => be_build( get_the_ID(), 'group_features_s' ),
			'type'     => 'features'
		];
		$output[] = ob_get_clean();

		// 展示
		ob_start();
		get_template_part( '/build/group-cat-img' );
		$order[] = [
			'sort_key' => be_build( get_the_ID(), 'group_img_s' ),
			'type'     => 'catimg'
		];
		$output[] = ob_get_clean();

		// 计数器
		ob_start();
		get_template_part( '/build/group-counter' );
		$order[] = [
			'sort_key' => be_build( get_the_ID(), 'counter_s' ),
			'type'     => 'counter'
		];
		$output[] = ob_get_clean();

		// 合作
		ob_start();
		get_template_part( '/build/group-coop' );
		$order[] = [
			'sort_key' => be_build( get_the_ID(), 'coop_s' ),
			'type'     => 'coop'
		];
		$output[] = ob_get_clean();

		// 分类左右图
		ob_start();
		get_template_part( '/build/group-wd' );
		$order[] = [
			'sort_key' => be_build( get_the_ID(), 'group_wd_s' ),
			'type'     => 'wd'
		];
		$output[] = ob_get_clean();

		// 一栏小工具
		ob_start();
		get_template_part( '/build/group-widget-one' );
		$order[] = [
			'sort_key' => be_build( get_the_ID(), 'group_widget_one_s' ),
			'type'     => 'widgetone'
		];
		$output[] = ob_get_clean();

		// 最新文章
		ob_start();
		require get_template_directory() . '/build/group-news.php';
		$order[] = [
			'sort_key' => be_build( get_the_ID(), 'group_new_s' ),
			'type'     => 'news'
		];
		$output[] = ob_get_clean();

		// 商品模块
		ob_start();
		get_template_part( '/build/group-tao' );
		$order[] = [
			'sort_key' => be_build( get_the_ID(), 'g_tao_s' ),
			'type'     => 'tao'
		];
		$output[] = ob_get_clean();

		// 三栏小工具
		ob_start();
		get_template_part( '/build/group-widget-three' );
		$order[] = [
			'sort_key' => be_build( get_the_ID(), 'group_widget_three_s' ),
			'type'     => 'widgetthree'
		];
		$output[] = ob_get_clean();

		// 新闻资讯A
		ob_start();
		require get_template_directory() . '/build/group-cat-a.php';
		$order[] = [
			'sort_key' => be_build( get_the_ID(), 'group_cat_a_s' ),
			'type'     => 'cata'
		];
		$output[] = ob_get_clean();

		// 两栏小工具
		ob_start();
		get_template_part( '/build/group-widget-two' );
		$order[] = [
			'sort_key' => be_build( get_the_ID(), 'group_widget_two_s' ),
			'type'     => 'widgettwo'
		];
		$output[] = ob_get_clean();

		// 新闻资讯B
		ob_start();
		require get_template_directory() . '/build/group-cat-b.php';
		$order[] = [
			'sort_key' => be_build( get_the_ID(), 'group_cat_b_s' ),
			'type'     => 'catb'
		];
		$output[] = ob_get_clean();

		// AJAX分类
		ob_start();
		require get_template_directory() . '/build/group-tab.php';
		$order[] = [
			'sort_key' => be_build( get_the_ID(), 'group_tab_s' ),
			'type'     => 'tab'
		];
		$output[] = ob_get_clean();

		// 展望
		ob_start();
		require get_template_directory() . '/build/group-outlook.php';
		$order[] = [
			'sort_key' => be_build( get_the_ID(), 'group_outlook_s' ),
			'type'     => 'outlook'
		];
		$output[] = ob_get_clean();

		// 新闻资讯C
		ob_start();
		require get_template_directory() . '/build/group-cat-c.php';
		$order[] = [
			'sort_key' => be_build( get_the_ID(), 'group_cat_c_s' ),
			'type'     => 'catc'
		];
		$output[] = ob_get_clean();

		// 热门推荐
		ob_start();
		require get_template_directory() . '/build/group-carousel.php';
		$order[] = [
			'sort_key' => be_build( get_the_ID(), 'group_carousel_s' ),
			'type'     => 'carousel'
		];
		$output[] = ob_get_clean();

		// 新闻资讯D
		ob_start();
		require get_template_directory() . '/build/group-cat-d.php';
		$order[] = [
			'sort_key' => be_build( get_the_ID(), 'group_cat_d_s' ),
			'type'     => 'catd'
		];
		$output[] = ob_get_clean();

		// 分类短代码
		ob_start();
		require get_template_directory() . '/build/group-cat-code.php';
		$order[] = [
			'sort_key' => be_build( get_the_ID(), 'group_ajax_cat_post_s' ),
			'type'     => 'code'
		];
		$output[] = ob_get_clean();

		// 会员商品
		ob_start();
		require get_template_directory() . '/build/group-assets.php';
		$order[] = [
			'sort_key' => be_build( get_the_ID(), 'group_assets_s' ),
			'type'     => 'assets'
		];
		$output[] = ob_get_clean();

		// 软件下载
		ob_start();
		require get_template_directory() . '/build/group-down.php';
		$order[] = [
			'sort_key' => be_build( get_the_ID(), 'group_down_s' ),
			'type'     => 'down'
		];
		$output[] = ob_get_clean();

		// 分类组合
		ob_start();
		require get_template_directory() . '/build/group-portfolio.php';
		$order[] = [
			'sort_key' => be_build( get_the_ID(), 'group_portfolio_s' ),
			'type'     => 'portfolio'
		];
		$output[] = ob_get_clean();

		// 热门分类
		ob_start();
		require get_template_directory() . '/build/group-cat-hot.php';
		$order[] = [
			'sort_key' => be_build( get_the_ID(), 'group_cat_hot_s' ),
			'type'     => 'hot'
		];
		$output[] = ob_get_clean();

		// 图文幻灯
		ob_start();
		require get_template_directory() . '/build/group-sliders-text.php';
		$order[] = [
			'sort_key' => be_build( get_the_ID(), 'group_slides_text_s' ),
			'type'     => 'slidersa'
		];
		$output[] = ob_get_clean();

		// 图文简介
		ob_start();
		require get_template_directory() . '/build/group-intro.php';
		$order[] = [
			'sort_key' => be_build( get_the_ID(), 'group_intro_s' ),
			'type'     => 'intro'
		];
		$output[] = ob_get_clean();

		// 解决方案
		ob_start();
		require get_template_directory() . '/build/group-scheme.php';
		$order[] = [
			'sort_key' => be_build( get_the_ID(), 'group_scheme_s' ),
			'type'     => 'scheme'
		];
		$output[] = ob_get_clean();

		// 联系我们
		ob_start();
		require get_template_directory() . '/build/group-email.php';
		$order[] = [
			'sort_key' => be_build( get_the_ID(), 'group_email_s' ),
			'type'     => 'email'
		];
		$output[] = ob_get_clean();

		// 行业新闻
		ob_start();
		require get_template_directory() . '/build/group-each.php';
		$order[] = [
			'sort_key' => be_build( get_the_ID(), 'group_each_s' ),
			'type'     => 'each'
		];
		$output[] = ob_get_clean();

		// 图片说明
		ob_start();
		$groupdes = ( array ) be_build( get_the_ID(), 'group_des_item' );
		foreach ( $groupdes as $items ) {
			ob_start();
			get_template_part( '/build/group-des', null, array( 'items' => $items ) );
			$output[] = ob_get_clean();
			$order[]  = [
				'sort_key' => $items['group_des_s'],
				'type'     => 'des'
			];
		}

		// 专题新闻
		ob_start();
		$special = ( array ) be_build( get_the_ID(), 'group_special_item' );
		foreach ( $special as $items ) {
			ob_start();
			$items = $items;
			require get_template_directory() . '/build/group-special.php';
			$output[] = ob_get_clean();
			$order[]  = [
				'sort_key' => $items['group_special_s'],
				'type'     => 'special'
			];
		}

		// 广告信息
		ob_start();
		$groupads = ( array ) be_build( get_the_ID(), 'group_ads_item' );
		foreach ( $groupads as $items ) {
			ob_start();
			get_template_part( '/build/group-ads', null, array( 'items' => $items ) );
			$output[] = ob_get_clean();
			$order[]  = [
				'sort_key' => $items['group_ads_s'],
				'type'     => 'ads'
			];
		}

		// 排序输出
		array_multisort( array_column( $order, 'sort_key' ), $output );
		foreach ( $output as $content ) {
			echo $content;
		}
	}

	co_build();
	echo '</div>';
	echo edit_post_link('<i class="be be-edit"></i> 编辑', '<div class="page-edit-link edit-link">', '</div>' );
	echo '<div class="clear"></div>';
	echo '</div>';
}