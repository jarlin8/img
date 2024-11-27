<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
// 文章
function begin_sitemap_post_txt() {
	if ( zm_get_option( 'sitemap_split' ) ) {
		$batchSize = zm_get_option( 'sitemap_n' );
		$totalPosts = wp_count_posts()->publish;
		$totalBatches = ceil( $totalPosts / $batchSize );

		for ( $i = 0; $i < $totalBatches; $i++ ) {
			$offset = $i * $batchSize;
			$file = ABSPATH . zm_get_option( 'sitemap_name' );
			$file .= ( $i > 0 ) ? '-' . $i : '';
			$file .= '.txt';

			$sitemap = get_home_url() . "\r\n";

			$post_types = ! empty( zm_get_option( 'sitemap_type' ) ) ? ( array ) zm_get_option( 'sitemap_type' ) : get_post_types( array( 'public' => true ) );
		    $args = array(
				'post_type'           => $post_types,
				'offset'              => $offset,
				'posts_per_page'      => $batchSize,
				'post_status'         => 'publish',
				'orderby'             => 'date',
				'order'               => 'DESC',
				'ignore_sticky_posts' => true,
			);

			$query = new WP_Query( $args );
			if ( $query->have_posts() ) {
				file_put_contents( $file, $sitemap );

				while ( $query->have_posts() ) {
					$query->the_post();
					file_put_contents( $file, get_the_permalink() . "\r\n", FILE_APPEND );
				}
				wp_reset_postdata();
			}

			// 在第一个批次添加分类
			if ( $i === 0 && is_array( zm_get_option( 'sitemap_cat_tag' ) ) && in_array( 'cat', zm_get_option( 'sitemap_cat_tag') ) ) {
				$categorys = get_terms( 'category', 'orderby=name&hide_empty=0' );
				foreach ( $categorys as $category ) {
					file_put_contents( $file, get_term_link( $category, $category->slug ) . "\r\n", FILE_APPEND );
				}
			}

			// 在第二个批次添加标签
			if ( $i === 1 && is_array( zm_get_option( 'sitemap_cat_tag' ) ) && in_array( 'tag', zm_get_option( 'sitemap_cat_tag') ) ) {
				$tags = get_terms( 'post_tag', 'orderby=name&hide_empty=0' );
				foreach ( $tags as $tag ) {
					file_put_contents( $file, get_term_link( $tag, $tag->slug ) . "\r\n", FILE_APPEND );
				}
			}

			// 延迟处理下一个批次
			sleep( zm_get_option( 'sitemap_delay' ) );
		}

	} else {
		$file = ABSPATH . zm_get_option( 'sitemap_name' ) . '.txt';

		$sitemap = get_home_url() . "\r\n";
		$post_types = ! empty( zm_get_option( 'sitemap_type' ) ) ? ( array ) zm_get_option( 'sitemap_type' ) : get_post_types( array( 'public' => true ) );
		$args = array(
			'post_type'           => $post_types,
			'posts_per_page'      => zm_get_option( 'sitemap_n' ),
			'post_status'         => 'publish',
			'orderby'             => 'date',
			'order'               => 'DESC',
			'ignore_sticky_posts' => true,
		);

		$query = new WP_Query( $args );
		if ( $query->have_posts() ) {

			file_put_contents( $file, $sitemap );

			while ( $query->have_posts() ) {
				$query->the_post();
				file_put_contents( $file, get_the_permalink() . "\r\n", FILE_APPEND );
			}
			wp_reset_postdata();
		}

		// 分类
		if ( is_array( zm_get_option( 'sitemap_cat_tag' ) ) && in_array( 'cat', zm_get_option( 'sitemap_cat_tag') ) ) {
			$categorys = get_terms( 'category', 'orderby=name&hide_empty=0' );
			foreach ( $categorys as $category ) {
				file_put_contents( $file, get_term_link( $category, $category->slug ) . "\r\n", FILE_APPEND );
			}
		}

		// 标签
		if ( is_array( zm_get_option( 'sitemap_cat_tag' ) ) && in_array( 'tag', zm_get_option( 'sitemap_cat_tag') ) ) {
			$tags = get_terms( 'post_tag', 'orderby=name&hide_empty=0' );
			foreach ( $tags as $tag ) {
				file_put_contents( $file, get_term_link( $tag, $tag->slug ) . "\r\n", FILE_APPEND );
			}
		}
	}
}

add_action( 'be_sitemap_generate', 'begin_sitemap_post_txt' );