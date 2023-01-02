<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if (zm_get_option('filters_a')) {
	add_action( 'init', 'create_filtersa' );
	function create_filtersa() {
	$labels = array(
		'name'          => zm_get_option( 'filters_a_t' ),
		'singular_name' => 'filtersa',
		'search_items'  => '搜索',
		'edit_item'     => '编辑',
		'update_item'   => '更新',
		'add_new_item'  => '添加新的',
		);

	register_taxonomy( 'filtersa',array( 'post' ),array(
		'hierarchical' => false,
		'show_in_rest' => true,
		'rewrite'      => array( 'slug' => 'filtersa' ),
		'labels'       => $labels
		));
	}
}

if (zm_get_option('filters_b')) {
	add_action( 'init', 'create_filtersb' );
	function create_filtersb() {
	$labels = array(
		'name'          => zm_get_option( 'filters_b_t' ),
		'singular_name' => 'filtersb',
		'search_items'  => '搜索',
		'edit_item'     => '编辑',
		'update_item'   => '更新',
		'add_new_item'  => '添加新的',
		);

	register_taxonomy( 'filtersb',array( 'post' ),array(
		'hierarchical' => false,
		'show_in_rest' => true,
		'rewrite'      => array( 'slug' => 'filtersb' ),
		'labels'       => $labels
		));
	}
}

if (zm_get_option('filters_c')) {
	add_action( 'init', 'create_filtersc' );
	function create_filtersc() {
	$labels = array(
		'name'          => zm_get_option( 'filters_c_t' ),
		'singular_name' => 'filtersc',
		'search_items'  => '搜索',
		'edit_item'     => '编辑',
		'update_item'   => '更新',
		'add_new_item'  => '添加新的',
		);

	register_taxonomy( 'filtersc',array( 'post' ),array(
		'hierarchical' => false,
		'show_in_rest' => true,
		'rewrite'      => array( 'slug' => 'filtersc' ),
		'labels'       => $labels
		));
	}
}

if (zm_get_option('filters_d')) {
	add_action( 'init', 'create_filtersd' );
	function create_filtersd() {
	$labels = array(
		'name'          => zm_get_option( 'filters_d_t' ),
		'singular_name' => 'filtersd',
		'search_items'  => '搜索',
		'edit_item'     => '编辑',
		'update_item'   => '更新',
		'add_new_item'  => '添加新的',
		);

	register_taxonomy( 'filtersd',array( 'post' ),array(
		'hierarchical' => false,
		'show_in_rest' => true,
		'rewrite'      => array( 'slug' => 'filtersd' ),
		'labels'       => $labels
		));
	}
}

if (zm_get_option('filters_e')) {
	add_action( 'init', 'create_filterse' );
	function create_filterse() {
	$labels = array(
		'name'          => zm_get_option( 'filters_e_t' ),
		'singular_name' => 'filterse',
		'search_items'  => '搜索',
		'edit_item'     => '编辑',
		'update_item'   => '更新',
		'add_new_item'  => '添加新的',
		);

	register_taxonomy( 'filterse',array( 'post' ),array(
		'hierarchical' => false,
		'show_in_rest' => true,
		'rewrite'      => array( 'slug' => 'filterse' ),
		'labels'       => $labels
		));
	}
}

if (zm_get_option('filters_f')) {
	add_action( 'init', 'create_filtersf' );
	function create_filtersf() {
	$labels = array(
		'name'          => zm_get_option( 'filters_f_t' ),
		'singular_name' => 'filtersf',
		'search_items'  => '搜索',
		'edit_item'     => '编辑',
		'update_item'   => '更新',
		'add_new_item'  => '添加新的',
		);

	register_taxonomy( 'filtersf',array( 'post' ),array(
		'hierarchical' => false,
		'show_in_rest' => true,
		'rewrite'      => array( 'slug' => 'filtersf' ),
		'labels'       => $labels
		));
	}
}

if (zm_get_option('filters_g')) {
	add_action( 'init', 'create_filtersg' );
	function create_filtersg() {
	$labels = array(
		'name'          => zm_get_option( 'filters_g_t' ),
		'singular_name' => 'filtersg',
		'search_items'  => '搜索',
		'edit_item'     => '编辑',
		'update_item'   => '更新',
		'add_new_item'  => '添加新的',
		);

	register_taxonomy( 'filtersg',array( 'post' ),array(
		'hierarchical' => false,
		'show_in_rest' => true,
		'rewrite'      => array( 'slug' => 'filtersg' ),
		'labels'       => $labels
		));
	}
}

if (zm_get_option('filters_h')) {
	add_action( 'init', 'create_filtersh' );
	function create_filtersh() {
	$labels = array(
		'name'          => zm_get_option( 'filters_h_t' ),
		'singular_name' => 'filtersh',
		'search_items'  => '搜索',
		'edit_item'     => '编辑',
		'update_item'   => '更新',
		'add_new_item'  => '添加新的',
		);

	register_taxonomy( 'filtersh',array( 'post' ),array(
		'hierarchical' => false,
		'show_in_rest' => true,
		'rewrite'      => array( 'slug' => 'filtersh' ),
		'labels'       => $labels
		));
	}
}

if (zm_get_option('filters_i')) {
	add_action( 'init', 'create_filtersi' );
	function create_filtersi() {
	$labels = array(
		'name'          => zm_get_option( 'filters_i_t' ),
		'singular_name' => 'filtersi',
		'search_items'  => '搜索',
		'edit_item'     => '编辑',
		'update_item'   => '更新',
		'add_new_item'  => '添加新的',
		);

	register_taxonomy( 'filtersi',array( 'post' ),array(
		'hierarchical' => false,
		'show_in_rest' => true,
		'rewrite'      => array( 'slug' => 'filtersi' ),
		'labels'       => $labels
		));
	}
}

if (zm_get_option('filters_j')) {
	add_action( 'init', 'create_filtersj' );
	function create_filtersj() {
	$labels = array(
		'name'          => zm_get_option( 'filters_j_t' ),
		'singular_name' => 'filtersj',
		'search_items'  => '搜索',
		'edit_item'     => '编辑',
		'update_item'   => '更新',
		'add_new_item'  => '添加新的',
		);

	register_taxonomy( 'filtersj',array( 'post' ),array(
		'hierarchical' => false,
		'show_in_rest' => true,
		'rewrite'      => array( 'slug' => 'filtersj' ),
		'labels'       => $labels
		));
	}
}