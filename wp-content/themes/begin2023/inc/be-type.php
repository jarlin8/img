<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if (zm_get_option('no_bulletin')) {
// 公告
add_action( 'init', 'post_type_bulletin' );
function post_type_bulletin() {
	$type_url = zm_get_option('bull_url');
	$labels = array(
		'name'               => '公告',
		'singular_name'      => '公告',
		'menu_name'          => '公告',
		'name_admin_bar'     => '公告',
		'add_new'            => '发布公告',
		'add_new_item'       => '发布新公告', 
		'new_item'           => '新公告',
		'edit_item'          => '编辑公告',
		'view_item'          => '查看公告',
		'all_items'          => '所有公告',
		'search_items'       => '搜索公告',
		'parent_item_colon'  => 'Parent 公告:',
		'not_found'          => '你还没有发布公告。',
		'not_found_in_trash' => '回收站中没有公告。'
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => $type_url ),
		'capability_type'    => 'post',
		'menu_icon'          => 'dashicons-controls-volumeon',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => 26,
		'show_in_rest'       => true,
		'supports'           => array( 'title', 'editor', 'author', 'excerpt', 'comments', 'custom-fields' )
	);

	register_post_type( 'bulletin', $args );
}
}

// 公告分类
add_action( 'init', 'create_bulletin_taxonomies', 0 );
function create_bulletin_taxonomies() {
	$type_url = zm_get_option('bull_cat_url');
	$labels = array(
		'name'              => '公告分类',
		'singular_name'     => '公告分类',
		'search_items'      => '搜索公告分类',
		'all_items'         => '所有公告分类',
		'parent_item'       => '父级分类',
		'parent_item_colon' => '父级分类:',
		'edit_item'         => '编辑公告分类',
		'update_item'       => '更新公告分类',
		'add_new_item'      => '添加新公告分类',
		'new_item_name'     => 'New Genre Name',
		'menu_name'         => '公告分类',
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => $type_url ),
	);

	register_taxonomy( 'notice', array( 'bulletin' ), $args );
}


if (zm_get_option('no_gallery')) {
// 图片
add_action( 'init', 'post_type_picture' );
function post_type_picture() {
	$type_url = zm_get_option('img_url');
	$labels = array(
		'name'               => '图片',
		'singular_name'      => '图片',
		'menu_name'          => '图片',
		'name_admin_bar'     => '图片',
		'add_new'            => '发布图片',
		'add_new_item'       => '发布新图片',
		'new_item'           => '新图片',
		'edit_item'          => '编辑图片',
		'view_item'          => '查看图片',
		'all_items'          => '所有图片',
		'search_items'       => '搜索图片',
		'parent_item_colon'  => 'Parent 图片:',
		'not_found'          => '你还没有发布图片。',
		'not_found_in_trash' => '回收站中没有图片。'
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_rest'      => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => $type_url ),
		'capability_type'    => 'post',
		'menu_icon'          => 'dashicons-format-image',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => 26,
		'show_in_rest'       => true,
		'supports'           => array( 'title', 'editor', 'author', 'excerpt', 'comments', 'thumbnail', 'revisions', 'custom-fields' )
	);

	register_post_type( 'picture', $args );
}
}

// 图片分类
add_action( 'init', 'create_picture_taxonomies', 0 );
function create_picture_taxonomies() {
	$type_url = zm_get_option('img_cat_url');
	$labels = array(
		'name'              => '图片分类',
		'singular_name'     => '图片分类',
		'search_items'      => '搜索图片分类',
		'all_items'         => '所有图片分类',
		'parent_item'       => '父级分类',
		'parent_item_colon' => '父级分类:',
		'edit_item'         => '编辑图片分类',
		'update_item'       => '更新图片分类',
		'add_new_item'      => '添加新图片分类',
		'new_item_name'     => 'New Genre Name',
		'menu_name'         => '图片分类',
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => $type_url ),
	);

	register_taxonomy( 'gallery', array( 'picture' ), $args );
}

if (zm_get_option('no_videos')) {
// 视频
add_action( 'init', 'post_type_video' );
function post_type_video() {
	$type_url = zm_get_option('video_url');
	$labels = array(
		'name'               => '视频',
		'singular_name'      => '视频',
		'menu_name'          => '视频',
		'name_admin_bar'     => '视频',
		'add_new'            => '发布视频',
		'add_new_item'       => '发布新视频',
		'new_item'           => '新视频',
		'edit_item'          => '编辑视频',
		'view_item'          => '查看视频',
		'all_items'          => '所有视频',
		'search_items'       => '搜索视频', 
		'parent_item_colon'  => 'Parent 视频:',
		'not_found'          => '你还没有发布视频。',
		'not_found_in_trash' => '回收站中没有视频。'
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_rest'       => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => $type_url ),
		'capability_type'    => 'post',
		'menu_icon'          => 'dashicons-video-alt2',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => 26,
		'show_in_rest'       => true,
		'supports'           => array( 'title', 'editor', 'author', 'excerpt', 'comments', 'thumbnail', 'revisions', 'custom-fields' )
	);

	register_post_type( 'video', $args );
}
}

// 视频分类
add_action( 'init', 'create_video_taxonomies', 0 );
function create_video_taxonomies() {
	$type_url = zm_get_option('video_cat_url');
	$labels = array(
		'name'              => '视频分类',
		'singular_name'     => '视频分类',
		'search_items'      => '搜索视频分类',
		'all_items'         => '所有视频分类',
		'parent_item'       => '父级分类',
		'parent_item_colon' => '父级分类:',
		'edit_item'         => '编辑视频分类',
		'update_item'       => '更新视频分类',
		'add_new_item'      => '添加新视频分类',
		'new_item_name'     => 'New Genre Name',
		'menu_name'         => '视频分类',
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => $type_url ),
	);

	register_taxonomy( 'videos', array( 'video' ), $args );
}

if (zm_get_option('no_tao')) {
// 商品
add_action( 'init', 'post_type_tao' );
function post_type_tao() {
	$type_url = zm_get_option('sp_url');
	$labels = array(
		'name'               => '商品',
		'singular_name'      => '商品',
		'menu_name'          => '商品',
		'name_admin_bar'     => '商品',
		'add_new'            => '发布商品',
		'add_new_item'       => '发布新商品',
		'new_item'           => '新商品',
		'edit_item'          => '编辑商品',
		'view_item'          => '查看商品',
		'all_items'          => '所有商品',
		'search_items'       => '搜索商品',
		'parent_item_colon'  => 'Parent 商品:',
		'not_found'          => '你还没有发布商品。',
		'not_found_in_trash' => '回收站中没有商品。'
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_rest'      => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => $type_url ),
		'capability_type'    => 'post',
		'menu_icon'          => 'dashicons-cart',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => 26,
		'show_in_rest'       => true,
		'supports'           => array( 'title', 'editor', 'author', 'excerpt', 'comments', 'thumbnail', 'revisions', 'custom-fields' )
	);

	register_post_type( 'tao', $args );
}
}


// 商品分类
add_action( 'init', 'create_tao_taxonomies', 0 );
function create_tao_taxonomies() {
	$type_url = zm_get_option('sp_cat_url');
	$labels = array(
		'name'              => '商品分类',
		'singular_name'     => '商品分类',
		'search_items'      => '搜索商品分类',
		'all_items'         => '所有商品分类',
		'parent_item'       => '父级分类',
		'parent_item_colon' => '父级分类:',
		'edit_item'         => '编辑商品分类',
		'update_item'       => '更新商品分类',
		'add_new_item'      => '添加新商品分类',
		'new_item_name'     => 'New Genre Name',
		'menu_name'         => '商品分类',
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => $type_url ),
	);

	register_taxonomy( 'taobao', array( 'tao' ), $args );
}

if (zm_get_option('no_favorites')) {
// 网址
add_action( 'init', 'post_type_sites' );
function post_type_sites() {
	$type_url = zm_get_option('favorites_url');
	$labels = array(
		'name'               => '网址',
		'singular_name'      => '网址',
		'menu_name'          => '网址',
		'name_admin_bar'     => '网址',
		'add_new'            => '添加网址',
		'add_new_item'       => '添加新网址',
		'new_item'           => '新网址',
		'edit_item'          => '编辑网址',
		'view_item'          => '查看网址',
		'all_items'          => '所有网址',
		'search_items'       => '搜索网址',
		'parent_item_colon'  => 'Parent 网址:',
		'not_found'          => '你还没有发布网址。',
		'not_found_in_trash' => '回收站中没有网址。'
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_rest'      => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => $type_url ),
		'capability_type'    => 'post',
		'menu_icon'          => 'dashicons-admin-site',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => 26,
		'show_in_rest'       => true,
		'supports'           => array( 'title', 'editor', 'author', 'excerpt', 'comments', 'custom-fields' )
	);

	register_post_type( 'sites', $args );
}
}

// 网址分类
add_action( 'init', 'create_sites_taxonomies', 0 );
function create_sites_taxonomies() {
	$type_url = zm_get_option('favorites_cat_url');
	$labels = array(
		'name'              => '网址分类',
		'singular_name'     => '网址分类',
		'search_items'      => '搜索网址分类',
		'all_items'         => '所有网址分类',
		'parent_item'       => '父级分类',
		'parent_item_colon' => '父级分类:',
		'edit_item'         => '编辑网址分类',
		'update_item'       => '更新网址分类',
		'add_new_item'      => '添加新网址分类',
		'new_item_name'     => 'New Genre Name',
		'menu_name'         => '网址分类',
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => $type_url ),
	);

	register_taxonomy( 'favorites', array( 'sites' ), $args );
}

if (zm_get_option('no_products')) {
// 产品
add_action( 'init', 'post_type_show' );
function post_type_show() {
	$type_url = zm_get_option('show_url');
	$labels = array(
		'name'               => '产品',
		'singular_name'      => '产品',
		'menu_name'          => '产品',
		'name_admin_bar'     => '产品',
		'add_new'            => '发布产品',
		'add_new_item'       => '发布新产品',
		'new_item'           => '新产品',
		'edit_item'          => '编辑产品',
		'view_item'          => '查看产品',
		'all_items'          => '所有产品',
		'search_items'       => '搜索产品',
		'parent_item_colon'  => 'Parent 产品:',
		'not_found'          => '你还没有发布产品。',
		'not_found_in_trash' => '回收站中没有产品。'
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_rest'      => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => $type_url ),
		'capability_type'    => 'post',
		'menu_icon'          => 'dashicons-album',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => 26,
		'show_in_rest'       => true,
		'supports'           => array( 'title', 'editor', 'author', 'excerpt', 'comments', 'thumbnail', 'revisions', 'custom-fields' )
	);

	register_post_type( 'show', $args );
}
}

// 产品分类
add_action( 'init', 'create_show_taxonomies', 0 );
function create_show_taxonomies() {
	$type_url = zm_get_option('show_cat_url');
	$labels = array(
		'name'              => '产品分类',
		'singular_name'     => '产品分类',
		'search_items'      => '搜索产品分类',
		'all_items'         => '所有产品分类',
		'parent_item'       => '父级分类',
		'parent_item_colon' => '父级分类:',
		'edit_item'         => '编辑产品分类',
		'update_item'       => '更新产品分类',
		'add_new_item'      => '添加新产品分类',
		'new_item_name'     => 'New Genre Name',
		'menu_name'         => '产品分类',
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => $type_url ),
	);

	register_taxonomy( 'products', array( 'show' ), $args );
}

// 公告筛选
add_action( 'restrict_manage_posts', 'add_be_notice_filters', 10, 1 ); 
function add_be_notice_filters( $post_type ){
	if ( 'bulletin' !== $post_type ){
		return;
	}

	$taxonomies_slugs = array(
		'notice',
	);

	foreach( $taxonomies_slugs as $slug ){
		$taxonomy = get_taxonomy( $slug );
		$selected = '';
		$selected = isset( $_REQUEST[ $slug ] ) ? $_REQUEST[ $slug ] : '';
		wp_dropdown_categories( array(
			'show_option_all' =>  $taxonomy->labels->all_items,
			'taxonomy'        =>  $slug,
			'name'            =>  $slug,
			'orderby'         =>  'name',
			'value_field'     =>  'slug',
			'selected'        =>  $selected,
			'show_count'      =>  true,
			'hierarchical'    =>  true,
		) );
	}
}


add_action( 'init', 'be_special', 0 );
function be_special() {
	$type_url = zm_get_option( 'be_special_url' );
	$labels = array(
		'name'              => '专栏',
		'singular_name'     => '专栏',
		'search_items'      => '搜索专栏',
		'all_items'         => '所有专栏',
		'parent_item'       => '父级专栏',
		'parent_item_colon' => '父级专栏:',
		'edit_item'         => '编辑专栏',
		'update_item'       => '更新专栏',
		'add_new_item'      => '添加新专栏',
		'new_item_name'     => 'New Genre Name',
		'menu_name'         => '专栏',
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => $type_url ),
	);
	register_taxonomy( 'special', array( 'post' ), $args );
}