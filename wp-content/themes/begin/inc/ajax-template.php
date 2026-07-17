<?php
if ( ! defined( 'ABSPATH' ) ) exit;
function be_catimg_items_a() {
	$html = '';
	$catimg = ( array ) be_get_option( 'catimg_items_a' );
	foreach ( $catimg as $items ) {
		$becat = $items['catimg_items_id'];
		if ( function_exists( 'icl_object_id' ) && defined( 'ICL_LANGUAGE_CODE' ) ) {
			$becat = icl_object_id( $becat, 'category', true );
		}

		if ( ! empty( $items['catimg_items_id'] ) ) {
			$html .= '<div class="grid-cat-title-box betip">';
			$html .= '<h3 class="grid-cat-title" data-aos="zoom-in"><a href="' . get_category_link( $items['catimg_items_id'] ) . '">' . get_cat_name( $becat ) . '</a></h3>';
			if ( ! empty( $items['catimg_des'] ) ) {
				$html .= '<div class="grid-cat-des" data-aos="fade-up">';
				if ( ! empty( $items['catimg_items_des'] ) ) {
					$html .= $items['catimg_items_des'];
				} else {
					if ( category_description( $items['catimg_items_id'] ) ) {
						$html .=category_description( $items['catimg_items_id'] );
					} else {
						$html .= sprintf( __( '暂无描述', 'begin' ) );
					}
				}
				$html .= '</div>';
			}
			$html .= be_help_img( $text = '首页设置 → 分类图片 → 分类模块A' );
			$html .= '</div>';
			$html .= do_shortcode( '[be_ajax_post posts_per_page="' . $items['catimg_items_n'] . '" column="' . $items['catimg_items_fl'] . '" img="' . $items['catimg_items_img'] . '" terms="' . $items['catimg_items_id'] . '" btn="' . $items['catimg_items_btn'] . '" more="' . $items['catimg_items_nav_btn'] . '" children="' . $items['catimg_items_chil'] . '" style="' . $items['catimg_items_mode'] . '" btn_all= "no" orderby="date" order="DESC"]' );
		}
	}
	return $html;
}

function be_catimg_items_b() {
	$html = '';
	$catimg = ( array ) be_get_option( 'catimg_items_b' );
	foreach ( $catimg as $items ) {
		$becat = $items['catimg_items_id'];
		if ( function_exists( 'icl_object_id' ) && defined( 'ICL_LANGUAGE_CODE' ) ) {
			$becat = icl_object_id( $becat, 'category', true );
		}

		if ( ! empty( $items['catimg_items_id'] ) ) {
			$html .= '<div class="grid-cat-title-box betip">';
			$html .= '<h3 class="grid-cat-title" data-aos="zoom-in"><a href="' . get_category_link( $items['catimg_items_id'] ) . '">' . get_cat_name( $becat ) . '</a></h3>';
			if ( ! empty( $items['catimg_des'] ) ) {
				$html .= '<div class="grid-cat-des" data-aos="fade-up">';
				if ( ! empty( $items['catimg_items_des'] ) ) {
					$html .= $items['catimg_items_des'];
				} else {
					if ( category_description( $items['catimg_items_id'] ) ) {
						$html .=category_description( $items['catimg_items_id'] );
					} else {
						$html .= sprintf( __( '暂无描述', 'begin' ) );
					}
				}
				$html .= '</div>';
			}
			$html .= be_help_img( $text = '首页设置 → 分类图片 → 分类模块B' );
			$html .= '</div>';
			$html .= do_shortcode( '[be_ajax_post posts_per_page="' . $items['catimg_items_n'] . '" column="' . $items['catimg_items_fl'] . '" img="' . $items['catimg_items_img'] . '" terms="' . $items['catimg_items_id'] . '" btn="' . $items['catimg_items_btn'] . '" more="' . $items['catimg_items_nav_btn'] . '" children="' . $items['catimg_items_chil'] . '" style="' . $items['catimg_items_mode'] . '" btn_all= "no" orderby="date" order="DESC"]' );
		}
	}
	return $html;
}

function be_catimg_items_c() {
	$html = '';
	$catimg = ( array ) be_get_option( 'catimg_items_c' );
	foreach ( $catimg as $items ) {
		$becat = $items['catimg_items_id'];
		if ( function_exists( 'icl_object_id' ) && defined( 'ICL_LANGUAGE_CODE' ) ) {
			$becat = icl_object_id( $becat, 'category', true );
		}

		if ( ! empty( $items['catimg_items_id'] ) ) {
			$html .= '<div class="grid-cat-title-box betip">';
			$html .= '<h3 class="grid-cat-title" data-aos="zoom-in"><a href="' . get_category_link( $items['catimg_items_id'] ) . '">' . get_cat_name( $becat ) . '</a></h3>';
			if ( ! empty( $items['catimg_des'] ) ) {
				$html .= '<div class="grid-cat-des" data-aos="fade-up">';
				if ( ! empty( $items['catimg_items_des'] ) ) {
					$html .= $items['catimg_items_des'];
				} else {
					if ( category_description( $items['catimg_items_id'] ) ) {
						$html .=category_description( $items['catimg_items_id'] );
					} else {
						$html .= sprintf( __( '暂无描述', 'begin' ) );
					}
				}
				$html .= '</div>';
			}
			$html .= be_help_img( $text = '首页设置 → 分类图片 → 分类模块C' );
			$html .= '</div>';
			$html .= do_shortcode( '[be_ajax_post posts_per_page="' . $items['catimg_items_n'] . '" column="' . $items['catimg_items_fl'] . '" img="' . $items['catimg_items_img'] . '" terms="' . $items['catimg_items_id'] . '" btn="' . $items['catimg_items_btn'] . '" more="' . $items['catimg_items_nav_btn'] . '" children="' . $items['catimg_items_chil'] . '" style="' . $items['catimg_items_mode'] . '" btn_all= "no" orderby="date" order="DESC"]' );
		}
	}
	return $html;
}