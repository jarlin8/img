<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( is_array( zm_get_option( 'filters_item' ) ) && in_array( 'filters_a', zm_get_option( 'filters_item' ) ) ) {
	add_action( 'init', 'create_filtersa' );
	function create_filtersa() {
	$labels = array(
		'name'          => '筛选 ' . zm_get_option( 'filters_a_t' ),
		'singular_name' => 'filtersa',
		'search_items'  => '搜索',
		'edit_item'     => '编辑',
		'update_item'   => '更新',
		'add_new_item'  => '添加新的',
		);

	register_taxonomy( 'filtersa',array( 'post' ),array(
		'public'       => false,
		'show_ui'      => true,
		'publicly_queryable' => true,
		'hierarchical' => false,
		'show_in_rest' => true,
		'rewrite'      => array( 'slug' => 'filtersa' ),
		'labels'       => $labels
		));
	}
}

if ( is_array( zm_get_option( 'filters_item' ) ) && in_array( 'filters_b', zm_get_option( 'filters_item' ) ) ) {
	add_action( 'init', 'create_filtersb' );
	function create_filtersb() {
	$labels = array(
		'name'          => '筛选 ' . zm_get_option( 'filters_b_t' ),
		'singular_name' => 'filtersb',
		'search_items'  => '搜索',
		'edit_item'     => '编辑',
		'update_item'   => '更新',
		'add_new_item'  => '添加新的',
		);

	register_taxonomy( 'filtersb',array( 'post' ),array(
		'public'       => false,
		'show_ui'      => true,
		'publicly_queryable' => true,
		'hierarchical' => false,
		'show_in_rest' => true,
		'rewrite'      => array( 'slug' => 'filtersb' ),
		'labels'       => $labels
		));
	}
}

if ( is_array( zm_get_option( 'filters_item' ) ) && in_array( 'filters_c', zm_get_option( 'filters_item' ) ) ) {
	add_action( 'init', 'create_filtersc' );
	function create_filtersc() {
	$labels = array(
		'name'          => '筛选 ' . zm_get_option( 'filters_c_t' ),
		'singular_name' => 'filtersc',
		'search_items'  => '搜索',
		'edit_item'     => '编辑',
		'update_item'   => '更新',
		'add_new_item'  => '添加新的',
		);

	register_taxonomy( 'filtersc',array( 'post' ),array(
		'public'       => false,
		'show_ui'      => true,
		'publicly_queryable' => true,
		'hierarchical' => false,
		'show_in_rest' => true,
		'rewrite'      => array( 'slug' => 'filtersc' ),
		'labels'       => $labels
		));
	}
}

if ( is_array( zm_get_option( 'filters_item' ) ) && in_array( 'filters_d', zm_get_option( 'filters_item' ) ) ) {
	add_action( 'init', 'create_filtersd' );
	function create_filtersd() {
	$labels = array(
		'name'          => '筛选 ' . zm_get_option( 'filters_d_t' ),
		'singular_name' => 'filtersd',
		'search_items'  => '搜索',
		'edit_item'     => '编辑',
		'update_item'   => '更新',
		'add_new_item'  => '添加新的',
		);

	register_taxonomy( 'filtersd',array( 'post' ),array(
		'public'       => false,
		'show_ui'      => true,
		'publicly_queryable' => true,
		'hierarchical' => false,
		'show_in_rest' => true,
		'rewrite'      => array( 'slug' => 'filtersd' ),
		'labels'       => $labels
		));
	}
}

if ( is_array( zm_get_option( 'filters_item' ) ) && in_array( 'filters_e', zm_get_option( 'filters_item' ) ) ) {
	add_action( 'init', 'create_filterse' );
	function create_filterse() {
	$labels = array(
		'name'          => '筛选 ' . zm_get_option( 'filters_e_t' ),
		'singular_name' => 'filterse',
		'search_items'  => '搜索',
		'edit_item'     => '编辑',
		'update_item'   => '更新',
		'add_new_item'  => '添加新的',
		);

	register_taxonomy( 'filterse',array( 'post' ),array(
		'public'       => false,
		'show_ui'      => true,
		'publicly_queryable' => true,
		'hierarchical' => false,
		'show_in_rest' => true,
		'rewrite'      => array( 'slug' => 'filterse' ),
		'labels'       => $labels
		));
	}
}

if ( is_array( zm_get_option( 'filters_item' ) ) && in_array( 'filters_f', zm_get_option( 'filters_item' ) ) ) {
	add_action( 'init', 'create_filtersf' );
	function create_filtersf() {
	$labels = array(
		'name'          => '筛选 ' . zm_get_option( 'filters_f_t' ),
		'singular_name' => 'filtersf',
		'search_items'  => '搜索',
		'edit_item'     => '编辑',
		'update_item'   => '更新',
		'add_new_item'  => '添加新的',
		);

	register_taxonomy( 'filtersf',array( 'post' ),array(
		'public'       => false,
		'show_ui'      => true,
		'publicly_queryable' => true,
		'hierarchical' => false,
		'show_in_rest' => true,
		'rewrite'      => array( 'slug' => 'filtersf' ),
		'labels'       => $labels
		));
	}
}

if ( is_array( zm_get_option( 'filters_item' ) ) && in_array( 'filters_g', zm_get_option( 'filters_item' ) ) ) {
	add_action( 'init', 'create_filtersg' );
	function create_filtersg() {
	$labels = array(
		'name'          => '筛选 ' . zm_get_option( 'filters_g_t' ),
		'singular_name' => 'filtersg',
		'search_items'  => '搜索',
		'edit_item'     => '编辑',
		'update_item'   => '更新',
		'add_new_item'  => '添加新的',
		);

	register_taxonomy( 'filtersg',array( 'post' ),array(
		'public'       => false,
		'show_ui'      => true,
		'publicly_queryable' => true,
		'hierarchical' => false,
		'show_in_rest' => true,
		'rewrite'      => array( 'slug' => 'filtersg' ),
		'labels'       => $labels
		));
	}
}

if ( is_array( zm_get_option( 'filters_item' ) ) && in_array( 'filters_h', zm_get_option( 'filters_item' ) ) ) {
	add_action( 'init', 'create_filtersh' );
	function create_filtersh() {
	$labels = array(
		'name'          => '筛选 ' . zm_get_option( 'filters_h_t' ),
		'singular_name' => 'filtersh',
		'search_items'  => '搜索',
		'edit_item'     => '编辑',
		'update_item'   => '更新',
		'add_new_item'  => '添加新的',
		);

	register_taxonomy( 'filtersh',array( 'post' ),array(
		'public'       => false,
		'show_ui'      => true,
		'publicly_queryable' => true,
		'hierarchical' => false,
		'show_in_rest' => true,
		'rewrite'      => array( 'slug' => 'filtersh' ),
		'labels'       => $labels
		));
	}
}

if ( is_array( zm_get_option( 'filters_item' ) ) && in_array( 'filters_i', zm_get_option( 'filters_item' ) ) ) {
	add_action( 'init', 'create_filtersi' );
	function create_filtersi() {
	$labels = array(
		'name'          => '筛选 ' . zm_get_option( 'filters_i_t' ),
		'singular_name' => 'filtersi',
		'search_items'  => '搜索',
		'edit_item'     => '编辑',
		'update_item'   => '更新',
		'add_new_item'  => '添加新的',
		);

	register_taxonomy( 'filtersi',array( 'post' ),array(
		'public'       => false,
		'show_ui'      => true,
		'publicly_queryable' => true,
		'hierarchical' => false,
		'show_in_rest' => true,
		'rewrite'      => array( 'slug' => 'filtersi' ),
		'labels'       => $labels
		));
	}
}

if ( is_array( zm_get_option( 'filters_item' ) ) && in_array( 'filters_j', zm_get_option( 'filters_item' ) ) ) {
	add_action( 'init', 'create_filtersj' );
	function create_filtersj() {
	$labels = array(
		'name'          => '筛选 ' . zm_get_option( 'filters_j_t' ),
		'singular_name' => 'filtersj',
		'search_items'  => '搜索',
		'edit_item'     => '编辑',
		'update_item'   => '更新',
		'add_new_item'  => '添加新的',
		);

	register_taxonomy( 'filtersj',array( 'post' ),array(
		'public'       => false,
		'show_ui'      => true,
		'publicly_queryable' => true,
		'hierarchical' => false,
		'show_in_rest' => true,
		'rewrite'      => array( 'slug' => 'filtersj' ),
		'labels'       => $labels
		));
	}
}
// 仅管理员可见
if ( zm_get_option( 'filter_box' ) && ! current_user_can( 'manage_options' ) && is_admin() ) {
	remove_action( 'init', 'create_filtersa' );
	remove_action( 'init', 'create_filtersb' );
	remove_action( 'init', 'create_filtersc' );
	remove_action( 'init', 'create_filtersd' );
	remove_action( 'init', 'create_filterse' );
	remove_action( 'init', 'create_filtersf' );
	remove_action( 'init', 'create_filtersg' );
	remove_action( 'init', 'create_filtersh' );
	remove_action( 'init', 'create_filtersi' );
	remove_action( 'init', 'create_filtersj' );
}