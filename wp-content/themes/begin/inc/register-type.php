<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if ( is_array( zm_get_option( 'be_type' ) ) && in_array( 'notice', zm_get_option( 'be_type' ) ) ) {
	// 公告
	add_action( 'init', 'post_type_bulletin' );
	function post_type_bulletin() {
		$type_url = zm_get_option( 'bull_url' );
		$labels = array(
			'name'               => '公告',
			'singular_name'      => '公告',
			'menu_name'          => '公告',
			'name_admin_bar'     => '公告',
			'add_new'            => '发布公告',
			'add_new_item'       => '发布公告', 
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
	$type_url = zm_get_option( 'bull_cat_url' );
	$labels = array(
		'name'              => '公告分类',
		'singular_name'     => '公告分类',
		'search_items'      => '搜索分类',
		'all_items'         => '所有分类',
		'parent_item'       => '父级分类',
		'parent_item_colon' => '父级分类:',
		'edit_item'         => '编辑分类',
		'update_item'       => '更新分类',
		'add_new_item'      => '新增分类',
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

if ( is_array( zm_get_option( 'be_type' ) ) && in_array( 'img', zm_get_option( 'be_type' ) ) ) {
	// 图片
	add_action( 'init', 'post_type_picture' );
	function post_type_picture() {
		$type_url = zm_get_option( 'img_url' );
		$labels = array(
			'name'               => '图片',
			'singular_name'      => '图片',
			'menu_name'          => '图片',
			'name_admin_bar'     => '图片',
			'add_new'            => '发布图片',
			'add_new_item'       => '发布图片',
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
			'show_in_rest'       => true,
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
	$type_url = zm_get_option( 'img_cat_url' );
	$labels = array(
		'name'              => '图片分类',
		'singular_name'     => '图片分类',
		'search_items'      => '搜索分类',
		'all_items'         => '所有分类',
		'parent_item'       => '父级分类',
		'parent_item_colon' => '父级分类:',
		'edit_item'         => '编辑分类',
		'update_item'       => '更新分类',
		'add_new_item'      => '新增分类',
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

if ( is_array( zm_get_option( 'be_type' ) ) && in_array( 'video', zm_get_option( 'be_type' ) ) ) {
	// 视频
	add_action( 'init', 'post_type_video' );
	function post_type_video() {
		$type_url = zm_get_option( 'video_url' );
		$labels = array(
			'name'               => '视频',
			'singular_name'      => '视频',
			'menu_name'          => '视频',
			'name_admin_bar'     => '视频',
			'add_new'            => '发布视频',
			'add_new_item'       => '发布视频',
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
	$type_url = zm_get_option( 'video_cat_url' );
	$labels = array(
		'name'              => '视频分类',
		'singular_name'     => '视频分类',
		'search_items'      => '搜索分类',
		'all_items'         => '所有分类',
		'parent_item'       => '父级分类',
		'parent_item_colon' => '父级分类:',
		'edit_item'         => '编辑分类',
		'update_item'       => '更新分类',
		'add_new_item'      => '新增分类',
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

if ( is_array( zm_get_option( 'be_type' ) ) && in_array( 'tao', zm_get_option( 'be_type' ) ) ) {
	// 商品
	add_action( 'init', 'post_type_tao' );
	function post_type_tao() {
		$type_url = zm_get_option( 'sp_url' );
		$labels = array(
			'name'               => '商品',
			'singular_name'      => '商品',
			'menu_name'          => '商品',
			'name_admin_bar'     => '商品',
			'add_new'            => '发布商品',
			'add_new_item'       => '发布商品',
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
			'show_in_rest'       => true,
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
	$type_url = zm_get_option( 'sp_cat_url' );
	$labels = array(
		'name'              => '商品分类',
		'singular_name'     => '商品分类',
		'search_items'      => '搜索分类',
		'all_items'         => '所有分类',
		'parent_item'       => '父级分类',
		'parent_item_colon' => '父级分类:',
		'edit_item'         => '编辑分类',
		'update_item'       => '更新分类',
		'add_new_item'      => '新增分类',
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

// 商品标签
add_action( 'init', 'create_tao_tag_taxonomies', 0 );
function create_tao_tag_taxonomies() {
	$type_url = zm_get_option( 'sp_tag_url' );
	$labels = array(
		'name'              => '商品标签', 'taxonomy general name',
		'singular_name'     => '商品标签', 'taxonomy singular name',
		'search_items'      => '搜索标签',
		'all_items'         => '所有标签',
		'parent_item'       => 'Parent Genre',
		'parent_item_colon' => 'Parent Genre:',
		'edit_item'         => '编辑标签',
		'update_item'       => '更新标签',
		'add_new_item'      => '新增标签',
		'new_item_name'     => 'New Genre Name',
		'menu_name'         => '商品标签',
	);

	$args = array(
		'hierarchical'      => false,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => $type_url ),
	);

	register_taxonomy( 'taotag', array( 'tao' ), $args );
}

if ( is_array( zm_get_option( 'be_type' ) ) && in_array( 'sites', zm_get_option( 'be_type' ) ) ) {
	// 网址
	add_action( 'init', 'post_type_sites' );
	function post_type_sites() {
		$type_url = zm_get_option( 'favorites_url' );
		$labels = array(
			'name'               => '网址',
			'singular_name'      => '网址',
			'menu_name'          => '网址',
			'name_admin_bar'     => '网址',
			'add_new'            => '添加网址',
			'add_new_item'       => '添加网址',
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
			'show_in_rest'       => true,
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
	$type_url = zm_get_option( 'favorites_cat_url' );
	$labels = array(
		'name'              => '网址分类',
		'singular_name'     => '网址分类',
		'search_items'      => '搜索分类',
		'all_items'         => '所有分类',
		'parent_item'       => '父级分类',
		'parent_item_colon' => '父级分类:',
		'edit_item'         => '编辑分类',
		'update_item'       => '更新分类',
		'add_new_item'      => '新增分类',
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

if ( is_array( zm_get_option( 'be_type' ) ) && in_array( 'show', zm_get_option( 'be_type' ) ) ) {
	// 项目
	add_action( 'init', 'post_type_show' );
	function post_type_show() {
		$type_url = zm_get_option( 'show_url' );
		$labels = array(
			'name'               => '项目',
			'singular_name'      => '项目',
			'menu_name'          => '项目',
			'name_admin_bar'     => '项目',
			'add_new'            => '发布项目',
			'add_new_item'       => '发布项目',
			'new_item'           => '新项目',
			'edit_item'          => '编辑项目',
			'view_item'          => '查看项目',
			'all_items'          => '所有项目',
			'search_items'       => '搜索项目',
			'parent_item_colon'  => 'Parent 项目:',
			'not_found'          => '你还没有发布项目。',
			'not_found_in_trash' => '回收站中没有项目。'
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
			'menu_icon'          => 'dashicons-cloud',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => 26,
			'show_in_rest'       => true,
			'supports'           => array( 'title', 'editor', 'author', 'excerpt', 'comments', 'thumbnail', 'revisions', 'custom-fields' )
		);

		register_post_type( 'show', $args );
	}
}

// 项目分类
add_action( 'init', 'create_show_taxonomies', 0 );
function create_show_taxonomies() {
	$type_url = zm_get_option( 'show_cat_url' );
	$labels = array(
		'name'              => '项目分类',
		'singular_name'     => '项目分类',
		'search_items'      => '搜索分类',
		'all_items'         => '所有分类',
		'parent_item'       => '父级分类',
		'parent_item_colon' => '父级分类:',
		'edit_item'         => '编辑分类',
		'update_item'       => '更新分类',
		'add_new_item'      => '新增分类',
		'new_item_name'     => 'New Genre Name',
		'menu_name'         => '项目分类',
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

// 专栏
if ( cx_get_option( 'special_meta' ) ) {
	add_action( 'init', 'be_special', 0 );
	if ( cx_get_option( 'special_box' ) && ! current_user_can( 'manage_options' ) && is_admin() ) {
		remove_action( 'init', 'be_special', 0 );
	}
}
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
		'add_new_item'      => '新增专栏',
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

if ( zm_get_option( 'menu_shortcode' ) ) {
	// 短代码
	add_action( 'init', 'code_type_shortcode' );
	function code_type_shortcode() {
		$labels = array(
			'name'               => '短代码',
			'singular_name'      => '短代码',
			'menu_name'          => '短代码',
			'name_admin_bar'     => '短代码',
			'add_new'            => '编写短代码',
			'add_new_item'       => '编写短代码', 
			'new_item'           => '新短代码',
			'edit_item'          => '编辑短代码',
			'view_item'          => '查看短代码',
			'all_items'          => '所有短代码',
			'search_items'       => '搜索短代码',
			'parent_item_colon'  => 'Parent 短代码:',
			'not_found'          => '你还没有发布短代码。',
			'not_found_in_trash' => '回收站中没有短代码。'
		);

		$args = array(
			'labels'             => $labels,
			'public'             => false,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'becode' ),
			'capability_type'    => 'post',
			'menu_icon'          => 'dashicons-shortcode',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => 56,
			'show_in_rest'       => true,
			'supports'           => array( 'title', 'editor', 'custom-fields' )
		);

		register_post_type( 'becode', $args );
	}

	// add_action( 'init', 'code_cat_shortcode', 0 );
	function code_cat_shortcode() {
		$labels = array(
			'name'              => '短代码分类',
			'singular_name'     => '短代码分类',
			'search_items'      => '搜索分类',
			'all_items'         => '所有分类',
			'parent_item'       => '父级分类',
			'parent_item_colon' => '父级分类:',
			'edit_item'         => '编辑分类',
			'update_item'       => '更新分类',
			'add_new_item'      => '新增分类',
			'new_item_name'     => 'New Genre Name',
			'menu_name'         => '短代码分类',
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'shortcode' ),
		);

		register_taxonomy( 'shortcode', array( 'becode' ), $args );
	}
}

// 分类筛选
add_action( 'restrict_manage_posts', 'add_custom_post_type_filters', 10, 1 );
function add_custom_post_type_filters( $post_type ) {
	/*
	// 自动获取分类法
	$post_types = get_post_types( array( 'public' => true ), 'objects' );
	$filters = array();
	foreach ( $post_types as $post_type_slug => $post_type_object ) {
		$taxonomies = get_object_taxonomies( $post_type_slug, 'objects' );
		if ( ! empty( $taxonomies ) ) {
			foreach ( $taxonomies as $taxonomy_slug => $taxonomy_object ) {
				if ( $taxonomy_object->public && $taxonomy_slug !== 'post_format' ) {
					if ( ! isset( $filters[ $post_type_slug ] ) ) {
						$filters[ $post_type_slug ] = array();
					}
					$filters[ $post_type_slug ][ $taxonomy_slug ] = $taxonomy_slug;
				}
			}
		}
	}

	// 添加自定义的文章类型和分类法
	$filters['ai_playlist'] = array(
		'ai_playlist_category',
	);
	*/

	$filters = array(
		'bulletin'    => array( 'notice'),
		'tao'         => array( 'taobao' ),
		'picture'     => array( 'gallery' ),
		'video'       => array( 'videos' ),
		'sites'       => array( 'favorites'),
		'show'        => array( 'products'),
		'ai_playlist' => array( 'ai_playlist_category' ),
	);

	foreach( $filters as $type_slug => $taxonomy_slugs ) {
		if ( $type_slug !== $post_type ){
			continue;
		}

		foreach( $taxonomy_slugs as $slug ) {
			$taxonomy = get_taxonomy( $slug );
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
}