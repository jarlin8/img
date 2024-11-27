<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if (function_exists('register_sidebar')){
	register_sidebar( array(
		'name'          => '博客布局侧边栏',
		'id'            => 'sidebar-h',
		'description'   => '显示在首页博客布局侧边栏',
		'before_widget' => '<aside id="%1$s" class="widget %2$s ms bk" data-aos="">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title bkx da">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '正文侧边栏',
		'id'            => 'sidebar-s',
		'description'   => '显示在文章正文及页面侧边栏',
		'before_widget' => '<aside id="%1$s" class="widget %2$s ms bk" data-aos="">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title bkx da">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '分类归档侧边栏',
		'id'            => 'sidebar-a',
		'description'   => '显示在文章归档页、搜索、404页侧边栏 ',
		'before_widget' => '<aside id="%1$s" class="widget %2$s ms bk" data-aos="">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title bkx da">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '杂志布局侧边栏',
		'id'            => 'cms-s',
		'description'   => '只显示在杂志布局侧边栏',
		'before_widget' => '<aside id="%1$s" class="widget %2$s ms bk" data-aos="">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title bkx da">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '杂志单栏小工具',
		'id'            => 'cms-one',
		'description'   => '显示在首页CMS杂志布局',
		'before_widget' => '<aside id="%1$s" class="widget %2$s ms bk" data-aos="">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title bkx da">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '杂志菜单小工具',
		'id'            => 'cms-two-menu',
		'description'   => '仅适合在杂志布局添加导航菜单小工具',
		'before_widget' => '<div class="wmm"><aside id="%1$s" class="widget %2$s" data-aos="">',
		'after_widget'  => '<div class="clear"></div></aside></div>',
		'before_title'  => '<h3 class="widget-title bkx da">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '杂志两栏小工具',
		'id'            => 'cms-two',
		'description'   => '显示在首页CMS杂志布局',
		'before_widget' => '<div class="xl2"><aside id="%1$s" class="widget %2$s ms bk" data-aos="">',
		'after_widget'  => '<div class="clear"></div></aside></div>',
		'before_title'  => '<h3 class="widget-title bkx da">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '杂志三栏小工具',
		'id'            => 'cms-three',
		'description'   => '显示在首页CMS杂志布局',
		'before_widget' => '<div class="flw"><aside id="%1$s" class="widget %2$s ms bk" data-aos="">',
		'after_widget'  => '<div class="clear"></div></aside></div>',
		'before_title'  => '<h3 class="widget-title bkx da">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '正文底部小工具',
		'id'            => 'sidebar-e',
		'description'   => '显示在正文底部',
		'before_widget' => '<aside id="%1$s" class="widget %2$s ms bk" data-aos="">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title bkx da"><span class="s-icon"></span>',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '公司主页“一栏”小工具',
		'id'            => 'group-one',
		'description'   => '显示在公司主页布局',
		'before_widget' => '<aside id="%1$s" class="widget %2$s ms bk" data-aos="">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title bkx da">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '公司主页“两栏”小工具',
		'id'            => 'group-two',
		'description'   => '显示在公司主页布局',
		'before_widget' => '<div class="xl2"><aside id="%1$s" class="widget %2$s ms bk" data-aos="">',
		'after_widget'  => '<div class="clear"></div></aside></div>',
		'before_title'  => '<h3 class="widget-title bkx da">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '公司主页“三栏”小工具',
		'id'            =>  'group-three',
		'description'   => '显示在公司主页布局',
		'before_widget' => '<div class="xl3"><aside id="%1$s" class="widget %2$s ms bk" data-aos="">',
		'after_widget'  => '<div class="clear"></div></aside></div>',
		'before_title'  => '<h3 class="widget-title bkx da">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '头部小工具',
		'id'            => 'header-widget',
		'description'   => '显示在页头',
		'before_widget' => '<aside id="%1$s" class="widget %2$s" data-aos="">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title bkx da"><span class="s-icon"></span>',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '页脚小工具',
		'id'            => 'sidebar-f',
		'description'   => '显示在页脚',
		'before_widget' => '<aside id="%1$s" class="widget %2$s" data-aos="">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title bkx da"><span class="s-icon"></span>',
		'after_title'   => '</h3>',
	) );

	register_sidebar(array(
		'name'          => '文章小工具',
		'id'            => 'be-content',
		'description'   => '用于在文章中添加小工具',
		'before_widget' => '<aside id="%1$s" class="widget be-content %2$s ms bk" data-aos="">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title bkx da">',
		'after_title'   => '</h3>',
	));

	register_sidebar( array(
		'name'          => '菜单页面',
		'id'            => 'all-cat',
		'description'   => '只适用于菜单页面模板，添加自定义菜单小工具',
		'before_widget' => '<aside id="%1$s" class="widget %2$s" data-aos="">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title bkx da">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '网址侧边栏',
		'id'            => 'favorites',
		'description'   => '只适用于网址正文页面 ',
		'before_widget' => '<aside id="%1$s" class="widget %2$s ms bk" data-aos="">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title bkx da">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '网址页面小工具',
		'id'            => 'favorites-one',
		'description'   => '显示在网址页面模板',
		'before_widget' => '<aside id="%1$s" class="widget %2$s ms bk" data-aos="">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title bkx da">',
		'after_title'   => '</h3>',
	) );

	if (function_exists( 'is_shop' )) {
		register_sidebar( array(
			'name'          => 'WOO商店侧边栏',
			'id'            => 'woo',
			'description'   => '显示在WOO商店',
			'before_widget' => '<aside id="%1$s" class="widget %2$s ms bk" data-aos="">',
			'after_widget'  => '<div class="clear"></div></aside>',
			'before_title'  => '<h3 class="widget-title bkx da">',
			'after_title'   => '</h3>',
		) );
	}
}

register_nav_menus(
	array(
		'navigation' => '主要菜单',
		'header' => '顶部菜单',
		'mobile' => '移动端菜单',
		'footer' => '移动端底部',
		'search' => '搜索推荐'
	)
);

add_theme_support( 'custom-background' );
add_theme_support( 'post-formats', array(
	'aside', 'image', 'video', 'quote', 'link', 'gallery'
) );
require get_template_directory() . '/inc/lib.php';
if ( zm_get_option('languages_en')) {
add_action('after_setup_theme', 'begin_theme_setup');
function begin_theme_setup(){
	load_theme_textdomain( 'begin', get_template_directory() . '/languages' );
}
}
if ( ! zm_get_option( 'wp_title' ) ) {
	add_theme_support( 'title-tag' );
}
if (function_exists( 'is_shop' )) {
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
if ( zm_get_option( 'visual_editor' ) ) {
	add_editor_style( 'css/editor-style.css' );
	if ( zm_get_option( 'p_first' ) ) {
		add_editor_style( 'css/editor.css' );
	}
}
// Block styles
function block_editor_styles() {
	wp_enqueue_style( 'block-editor-style', get_theme_file_uri( '/css/editor-blocks.css' ), array(), version );
}
add_action( 'enqueue_block_editor_assets', 'block_editor_styles' );

add_theme_support( 'automatic-feed-links' );
show_admin_bar(false);
function default_menu() {
	if ( current_user_can( 'manage_options' ) ) {
		echo '<ul class="nav-menu down-menu"><li><a href="' . home_url() . '/wp-admin/nav-menus.php">点此设置主要菜单</a></li></ul>';
	}
}
function mobile_menu() {
	if ( current_user_can( 'manage_options' ) ) {
		echo '<ul class="nav-menu down-menu"><li><a href="' . home_url() . '/wp-admin/nav-menus.php">点此设置移动端底部菜单</a></li></ul>';
	}
}
function default_top_menu() {
	if ( current_user_can( 'manage_options' ) ) {
		echo '<ul class="top-menu"><li><a href="' . home_url() . '/wp-admin/nav-menus.php">点此设置顶部菜单</a></li></ul>';
	}
}

function search_menu() {
	if ( current_user_can( 'manage_options' ) ) {
		echo '<ul class="search-menu"><li><a href="' . home_url() . '/wp-admin/nav-menus.php">设置搜索推荐</a></li></ul>';
	}
}

if (is_admin()) :
function options_css() {
	wp_enqueue_style( 'options', get_template_directory_uri() . '/css/options.css', array(), version );
}
add_action( 'init', 'options_css', 20 );
endif;

function begin_scripts() {
	$my_theme = wp_get_theme();
	$theme_version = $my_theme->get( 'Version' );
	wp_enqueue_style( 'begin-style', get_stylesheet_uri(), array(), esc_attr( $theme_version ) );
	wp_enqueue_style( 'be', get_template_directory_uri() . '/css/be.css', array(), version );
	wp_enqueue_style( 'dashicons' );
	wp_enqueue_style( 'fonts', get_template_directory_uri() . '/css/fonts/fonts.css', array(), version );
	wp_enqueue_style( 'icons', get_template_directory_uri() . '/css/icons/icons.css', array(), version );

	if ( zm_get_option( 'iconfont_url' ) ) {
		wp_enqueue_style( 'iconfontd', 'https:'.zm_get_option('iconfont_url'), array(), version );
	}

	if ( zm_get_option( 'iconfont' ) ) {
		wp_enqueue_style( 'iconfont', get_template_directory_uri() . '/css/iconfont/iconfont.css', array(), version );
		wp_enqueue_script( 'iconfont', get_template_directory_uri() . '/css/iconfont/iconfont.js', array(), version, true );
	}
	if (!zm_get_option('disable_block_styles')) {
		wp_enqueue_style( 'blocks-front', get_template_directory_uri() . '/css/blocks-front.css', array(), version );
	}

	if ( zm_get_option( 'highlight' ) ) {
		if ( is_singular() ) {
			wp_enqueue_style( 'highlight', get_template_directory_uri() . '/css/highlight.css', array(), version );
		}
	}

	if (function_exists( 'is_shop' )) {
		wp_enqueue_style( 'woo', get_template_directory_uri() . '/woocommerce/css/woo.css', array(), version );
	}

	if ( !is_admin() ) {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'lazyload', get_template_directory_uri() . '/js/jquery.lazyload.js', array(), version, false );
		if ( zm_get_option( 'iconsvg_url' ) ) {
			wp_enqueue_script( 'iconsvg', 'https:'.zm_get_option('iconsvg_url'), array(), version, false );
		}

		if ( zm_get_option( 'copyright_pro' ) && !is_page() && !current_user_can( 'level_10' ) ) {
			wp_enqueue_script( 'copyrightpro', get_template_directory_uri() . '/js/copyrightpro.js', array(), version, false );
		}

		wp_register_script( '3dtag', get_template_directory_uri() . '/js/3dtag.js', array(), version, false );
		wp_enqueue_script( 'html5', get_theme_file_uri( '/js/html5.js' ), array(), version, false );
		wp_script_add_data( 'html5', 'conditional', 'lt IE 9' );
		wp_enqueue_script( 'superfish', get_template_directory_uri() . "/js/superfish.js", array(), version, true );
		wp_enqueue_script( 'be_script', get_template_directory_uri() . '/js/begin-script.js', array(), version, true );

		if ( zm_get_option( 'menu_post' ) || zm_get_option( 'nav_cat' ) || zm_get_option( 'ajax_text_widget' ) ) {
			wp_enqueue_script( 'ajax-content', get_template_directory_uri() . "/js/ajax-content.js", array(), version, true );
			$ajaxcontent = 'var ajax_content = ' . wp_json_encode( array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) ) . '; ';
			wp_add_inline_script('ajax-content', $ajaxcontent, 'after');
		}

		if ( zm_get_option( 'gb2' ) ) {
			wp_enqueue_script( 'gb2big5', get_template_directory_uri() . "/js/gb2big5.js", array(), version, true );
		}

		if ( zm_get_option( 'shar_poster' ) || zm_get_option( 'shar_share' ) || zm_get_option( 'qrurl' ) ) {
			wp_enqueue_script( 'qrious-js', get_template_directory_uri() . '/js/qrious.js', array(), version, true );
		}
	}

	wp_enqueue_script( 'owl', get_template_directory_uri() . "/js/owl.js", array(), version, true );
	$beowl = 'var Timeout = ' . wp_json_encode( array( 'owl_time' => zm_get_option( 'owl_time' ) ) ) . '; ';
	$beowl .= 'var gridcarousel = ' . wp_json_encode( array( 'grid_carousel_f' => zm_get_option( 'grid_carousel_f' ) ) ) . '; ';
	$beowl .= 'var flexiselitems = ' . wp_json_encode( array( 'flexisel_f' => zm_get_option( 'flexisel_f' ) ) ) . '; ';
	wp_add_inline_script('owl', $beowl, 'after');
	$beplt = 'var host = ' . wp_json_encode( array( 'site' => home_url() ) ) . '; ';
	$beplt .= 'var plt =  ' . wp_json_encode( array( 'time' => zm_get_option( 'placard_time' ) ) ) . '; ';
	wp_add_inline_script('be_script', $beplt, 'after');

	if ( zm_get_option( 'shar_link' ) ) {
		$copyurl = 'var copiedurl = ' . wp_json_encode( array( 'copied' =>  __( '已复制', 'begin' ) ) ) . '; ';
		$copyurl .= 'var copiedlink = ' . wp_json_encode( array( 'copylink' => __( '复制链接', 'begin' ) ) ) . '; ';
		wp_add_inline_script( 'be_script', $copyurl, 'after' );
	}

	if (zm_get_option('sidebar_sticky')) {
		wp_enqueue_script( 'sticky', get_template_directory_uri() . '/js/sticky.js', array(), version, true );
	}

	if ( zm_get_option( 'aos_scroll' ) && !wp_is_mobile() ) {
		wp_enqueue_script( 'aos', get_template_directory_uri() . '/js/aos.js', array(), version, true );
	}

	wp_enqueue_script( 'ias', get_template_directory_uri() . '/js/ias.js', array(), version, true );

	wp_enqueue_script( 'nice-select', get_template_directory_uri() . '/js/nice-select.js', array(), version, true );

	if ( zm_get_option('infinite_post') && !is_single() && !is_paged() ) {
		wp_enqueue_script( 'infinite-post', get_template_directory_uri() . '/js/infinite-post.js', array(), version, true );

		$beinfinite = 'var Ajaxpost = ' . wp_json_encode( array( 'pages_n' => zm_get_option( 'pages_n' ) ) ) . '; ';
		wp_add_inline_script('infinite-post', $beinfinite, 'after' );
	}

	if ( zm_get_option( 'infinite_comment' ) && is_singular() ) {
		wp_enqueue_script( 'infinite-comment', get_template_directory_uri() . '/js/infinite-comment.js', array(), version, true );
	}

	if ( zm_get_option( 'cache_avatar' ) ) {
		wp_enqueue_script( 'letter', get_template_directory_uri() . '/js/letter.js', array(), version, true );
	}

	if ( zm_get_option( 'ajax_search' ) ) { 
		wp_enqueue_script( 'ajax_search', get_template_directory_uri() . "/js/ajax-search.js", array(), version, true );
		$ajaxsearch = 'var ajax_search = ' . wp_json_encode( array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) ) . '; ';
		$ajaxsearch .= 'var ajax_fetch = ' . wp_json_encode( array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) ) . '; ';
		wp_add_inline_script( 'ajax_search', $ajaxsearch, 'after' );
	}

	wp_enqueue_script( 'ajax_tab', get_template_directory_uri() . "/js/begin-tab.js", array(), version, true );
	$ajaxtab = 'var ajax_tab = ' . wp_json_encode( array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) ) . '; ';
	if ( !zm_get_option('new_cat_id')== '' ) {
		$ajaxtab .= 'var be_tabs = ' . wp_json_encode( array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) ) . '; ';
		$ajaxtab .= 'var img_tabs = ' . wp_json_encode( array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) ) . '; ';
	}

	if ( !zm_get_option('grid_new_cat_id')== '' ) {
		$ajaxtab .= 'var grid_tabs = ' . wp_json_encode( array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) ) . '; ';
	}

	if ( zm_get_option('cms_cat_tab')) {
		$ajaxtab .= 'var cms_tabs = ' . wp_json_encode( array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) ) . '; ';
	}
	wp_add_inline_script( 'ajax_tab', $ajaxtab, 'after' );

	wp_enqueue_script( 'fancybox', get_template_directory_uri() . '/js/fancybox.js', array(), version, true);
	if ( is_singular() ) {
		if ( zm_get_option( 'qq_info' ) ) {
			wp_enqueue_script( 'qqinfo', get_template_directory_uri() . '/js/getqqinfo.js', array(), version, true );
			$qqinfo = 'var goqq = ' . wp_json_encode( array( 'qqinf' => get_bloginfo("template_url"). '/inc/qq-info.php' ) ) . '; ';
			wp_add_inline_script( 'qqinfo', $qqinfo, 'after' );
		}
		if ( zm_get_option( 'be_code' ) && get_bloginfo( 'version') >= 5.2 ) {
			wp_enqueue_script( 'clipboard-js', '/wp-includes/js/clipboard.min.js', array(), version, true );
			wp_enqueue_script( 'copy-code', get_template_directory_uri() . '/js/copy-code.js', array(), version, true );
		}
		if ( zm_get_option( 'be_code' ) ) {
			wp_enqueue_style( 'prettify', get_template_directory_uri() . '/css/prettify.css', array(), version );
			wp_enqueue_script( 'prettify', get_template_directory_uri() . '/js/prettify.js', array(), version, true );
		}
		if ( zm_get_option( 'shar_poster' ) || zm_get_option( 'shar_like' ) || zm_get_option( 'shar_share' ) ) {
			wp_enqueue_script( 'social-share', get_template_directory_uri() . '/js/social-share.js', array( 'jquery' ), version, true );
		}
	}

	if ( zm_get_option( 'qt' ) ) {
		if ( is_singular() ) {
			wp_enqueue_script( 'jquery-ui', get_template_directory_uri() . '/js/jquery-ui.js', array(), version, true);
			wp_enqueue_script( 'qaptcha', get_template_directory_uri() . '/js/qaptcha.js', array(), version, true);
		}
	}

	$qrious = 'var ajaxqrurl = ' . wp_json_encode( array( 'qrurl' => zm_get_option( 'qrurl' ) ) ) . '; ';
	wp_add_inline_script( 'qrious-js', $qrious, 'after' );

	if ( get_post_type() == 'sites' ) {
		global $post;
		if ( get_post_meta( $post->ID, 'sites_link', true ) ) {
			$sites_link = get_post_meta( $post->ID, 'sites_link', true );
			$qrsites = 'var sites = ' . wp_json_encode( array( 'sitesul' => $sites_link ) ) . '; ';
			wp_add_inline_script( 'qrious-js', $qrsites, 'after' );
		}
	}

	if ( zm_get_option( 'comment_ajax' ) && is_singular() ) {
		wp_enqueue_script( 'comments-ajax', get_template_directory_uri() . '/js/comments-ajax.js', array(), version, true );
		$commentsajax = 'var aqt = ' . wp_json_encode( array( 'qt' => zm_get_option( 'qt' ) ) ) . '; ';
		wp_add_inline_script('comments-ajax', $commentsajax, 'before');
	}

	if ( !zm_get_option('comment_ajax') && is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'begin_scripts' );