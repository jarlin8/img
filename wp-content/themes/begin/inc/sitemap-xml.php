<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
// 文章
function begin_sitemap_post_xml() {
	if ( zm_get_option( 'sitemap_split' ) ) {
		$batchSize = zm_get_option( 'sitemap_n' );
		$totalPosts = wp_count_posts()->publish;
		$totalBatches = ceil( $totalPosts / $batchSize );

		$sitemap_header = '<?xml version="1.0" encoding="UTF-8"?>' . "\r\n";
		$sitemap_header .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:mobile="http://www.baidu.com/schemas/sitemap-mobile/1/">' . "\r\n";

		$sitemap_home = '<url>' . "\r\n";
		$sitemap_home .= '<loc>' . get_home_url() . '</loc>' . "\r\n";
		$sitemap_home .= '<lastmod>' . gmdate('Y-m-d') . '</lastmod>' . "\r\n";
		$sitemap_home .= '<changefreq>daily</changefreq>' . "\r\n";
		$sitemap_home .= '<priority>1.0</priority>' . "\r\n";
		$sitemap_home .= '</url>' . "\r\n";

		$sitemap_header .= $sitemap_home;

		for ( $i = 0; $i < $totalBatches; $i++ ) {
			$offset = $i * $batchSize;
			$post_types = ! empty( zm_get_option( 'sitemap_type') ) ? ( array ) zm_get_option('sitemap_type') : get_post_types( array( 'public' => true ) );
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
			$sitemap = '';

			while ($query->have_posts()) {
				$query->the_post();
				$sitemap .= '<url>' . "\r\n";
				$sitemap .= '<loc>' . get_the_permalink() . '</loc>' . "\r\n";
				$sitemap .= '<lastmod>' . str_replace(" ", "T", get_post()->post_modified) . '+00:00</lastmod>' . "\r\n";
				$sitemap .= '<changefreq>monthly</changefreq>' . "\r\n";
				$sitemap .= '<priority>0.8</priority>' . "\r\n";
				$sitemap .= '</url>' . "\r\n";
			}

			wp_reset_postdata();

			// 在第一个批次添加分类
			if ( $i === 0 && is_array( zm_get_option( 'sitemap_cat_tag' ) ) && in_array( 'cat', zm_get_option( 'sitemap_cat_tag' ) ) ) {
				$categorys = get_terms( 'category', 'orderby=name&hide_empty=0' );
				foreach ( $categorys as $category ) {
					$sitemap .= '<url>' . "\r\n";
					$sitemap .= '<loc>' . get_term_link( $category, $category->slug ) . '</loc>' . "\r\n";
					$sitemap .= '<changefreq>weekly</changefreq>' . "\r\n";
					$sitemap .= '<priority>0.8</priority>' . "\r\n";
					$sitemap .= '</url>' . "\r\n";
				}
			}

			// 在第二个批次添加标签
			if ( $i === 1 && is_array( zm_get_option( 'sitemap_cat_tag' ) ) && in_array( 'tag', zm_get_option( 'sitemap_cat_tag' ) ) ) {
				$tags = get_terms( 'post_tag', 'orderby=name&hide_empty=0' );
				foreach ( $tags as $tag ) {
					$sitemap .= '<url>' . "\r\n";
					$sitemap .= '<loc>' . get_term_link( $tag, $tag->slug ) . '</loc>' . "\r\n";
					$sitemap .= '<changefreq>monthly</changefreq>' . "\r\n";
					$sitemap .= '<priority>0.6</priority>' . "\r\n";
					$sitemap .= '</url>' . "\r\n";
				}
			}

    		$sitemap .= '</urlset>';

			$file = ABSPATH . zm_get_option( 'sitemap_name' );
			$file .= ( $i > 0 ) ? '-' . $i : '';
			$file .= '.xml';

			file_put_contents( $file, $sitemap_header . $sitemap, LOCK_EX );
			sleep( zm_get_option( 'sitemap_delay' ) );
		}
	} else {
		$file = ABSPATH . zm_get_option( 'sitemap_name' ) . '.xml';
		$sitemap_header = '<?xml version="1.0" encoding="UTF-8"?>' . "\r\n";
		$sitemap_header .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:mobile="http://www.baidu.com/schemas/sitemap-mobile/1/">' . "\r\n";
		file_put_contents( $file, $sitemap_header );

		$sitemap_home = '<url>' . "\r\n";
		$sitemap_home .= '<loc>' . get_home_url() . '</loc>' . "\r\n";
		$sitemap_home .= '<lastmod>' . gmdate('Y-m-d') . '</lastmod>' . "\r\n";
		$sitemap_home .= '<changefreq>daily</changefreq>' . "\r\n";
		$sitemap_home .= '<priority>1.0</priority>' . "\r\n";
		$sitemap_home .= '</url>' . "\r\n";
		file_put_contents( $file, $sitemap_home, FILE_APPEND );

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

		while ( $query->have_posts() ) {
			$query->the_post();
			$sitemap = '<url>' . "\r\n";
			$sitemap .= '<loc>' . get_the_permalink() . '</loc>' . "\r\n";
			$sitemap .= '<lastmod>' . str_replace( " ", "T", get_post()->post_modified ) . '+00:00</lastmod>' . "\r\n";
			$sitemap .= '<changefreq>monthly</changefreq>' . "\r\n";
			$sitemap .= '<priority>0.8</priority>' . "\r\n";
			$sitemap .= '</url>' . "\r\n";
			file_put_contents( $file, $sitemap, FILE_APPEND );
		}
		wp_reset_postdata();

		// 添加分类
		if ( is_array( zm_get_option( 'sitemap_cat_tag' ) ) && in_array( 'cat', zm_get_option( 'sitemap_cat_tag' ) ) ) {
			$categorys = get_terms( 'category', 'orderby=name&hide_empty=0' );
			foreach ( $categorys as $category ) {
				$sitemap = '<url>' . "\r\n";
				$sitemap .= '<loc>' . get_term_link( $category, $category->slug ) . '</loc>' . "\r\n";
				$sitemap .= '<changefreq>weekly</changefreq>' . "\r\n";
				$sitemap .= '<priority>0.8</priority>' . "\r\n";
				$sitemap .= '</url>' . "\r\n";
				file_put_contents( $file, $sitemap, FILE_APPEND );
			}
		}

		// 添加标签
		if ( is_array( zm_get_option( 'sitemap_cat_tag' ) ) && in_array( 'tag', zm_get_option( 'sitemap_cat_tag' ) ) ) {
			$tags = get_terms( 'post_tag', 'orderby=name&hide_empty=0' );
			foreach ( $tags as $tag ) {
				$sitemap = '<url>' . "\r\n";
				$sitemap .= '<loc>' . get_term_link( $tag, $tag->slug ) . '</loc>' . "\r\n";
				$sitemap .= '<changefreq>monthly</changefreq>' . "\r\n";
				$sitemap .= '<priority>0.6</priority>' . "\r\n";
				$sitemap .= '</url>' . "\r\n";
				file_put_contents( $file, $sitemap, FILE_APPEND );
			}
		}

		$sitemap_footer = '</urlset>' . "\r\n";
		file_put_contents( $file, $sitemap_footer, FILE_APPEND );
	}
}
add_action( 'be_sitemap_generate', 'begin_sitemap_post_xml' );