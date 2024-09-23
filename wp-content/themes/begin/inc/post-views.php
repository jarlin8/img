<?php
add_action( 'wp_head', 'begin_process_postviews' );
function begin_process_postviews() {
	global $user_ID, $post;
	if ( is_int( $post ) ) {
		$post = get_post( $post );
	}

	if ( ! wp_is_post_revision( $post ) && ! is_preview() ) {
		if ( is_single() || is_page() ) {
			$id = intval( $post->ID );
			if ( !$post_views = get_post_meta( get_the_ID(), 'views', true ) ) {
				$post_views = 0;
			}

			$should_count = false;
			switch( intval( zm_get_option( 'views_count' ) ) ) {
				case 0:
					$should_count = true;
					break;
				case 1:
					if (empty( $_COOKIE[USER_COOKIE] ) && intval( $user_ID ) === 0) {
						$should_count = true;
					}
					break;
				case 2:
					if ( intval( $user_ID ) > 0 ) {
						$should_count = true;
					}
					break;
			}

			if ( intval( zm_get_option( 'exclude_bots' ) ) === 1 ) {
				$bots = array(
					'Google Bot' => 'google'
					, 'MSN' => 'msnbot'
					, 'Alex' => 'ia_archiver'
					, 'Lycos' => 'lycos'
					, 'Ask Jeeves' => 'jeeves'
					, 'Altavista' => 'scooter'
					, 'AllTheWeb' => 'fast-webcrawler'
					, 'Inktomi' => 'slurp@inktomi'
					, 'Turnitin.com' => 'turnitinbot'
					, 'Technorati' => 'technorati'
					, 'Yahoo' => 'yahoo'
					, 'Findexa' => 'findexa'
					, 'NextLinks' => 'findlinks'
					, 'Gais' => 'gaisbo'
					, 'WiseNut' => 'zyborg'
					, 'WhoisSource' => 'surveybot'
					, 'Bloglines' => 'bloglines'
					, 'BlogSearch' => 'blogsearch'
					, 'PubSub' => 'pubsub'
					, 'Syndic8' => 'syndic8'
					, 'RadioUserland' => 'userland'
					, 'Gigabot' => 'gigabot'
					, 'Become.com' => 'become.com'
					, 'Baidu' => 'baiduspider'
					, 'so.com' => '360spider'
					, 'Sogou' => 'spider'
					, 'soso.com' => 'sosospider'
					, 'Yandex' => 'yandex'
				);

				$useragent = isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : '';
				foreach ( $bots as $name => $lookfor ) {
					if ( ! empty( $useragent ) && ( stristr( $useragent, $lookfor ) !== false ) ) {
						$should_count = false;
						break;
					}
				}
			}

			if ( $should_count && ( ( null !== zm_get_option( 'use_ajax' ) && intval( zm_get_option( 'use_ajax' ) ) === 0 ) || ( !defined( 'WP_CACHE' ) || !WP_CACHE ) ) ) {
				if ( null !== zm_get_option( 'random_count' ) && intval( zm_get_option( 'random_count' ) ) === 1 ) {
					update_post_meta( $id, 'views', ( $post_views + mt_rand( 1, zm_get_option( 'rand_mt' ) ) ) );
					do_action( 'postviews_increment_views', ( $post_views + mt_rand( 1, zm_get_option( 'rand_mt' ) ) ) );
				} else {
					update_post_meta( $id, 'views', ( $post_views + 1 ) );
					do_action( 'postviews_increment_views', ( $post_views + 1 ) );
				}
			}
		}
	}
}

add_action( 'wp_enqueue_scripts', 'begin_postview_cache_count_enqueue' );
function begin_postview_cache_count_enqueue() {
	global $user_ID, $post;

	if ( !defined( 'WP_CACHE' ) || !WP_CACHE )
		return;

	if ( null !== zm_get_option( 'use_ajax' ) && intval( zm_get_option( 'use_ajax' ) ) === 0 )
		return;

	if ( ! wp_is_post_revision( $post ) && ( is_single() || is_page() ) ) {
		$should_count = false;
		switch( intval( zm_get_option( 'views_count' ) ) ) {
			case 0:
				$should_count = true;
				break;
			case 1:
				if ( empty( $_COOKIE[USER_COOKIE] ) && intval( $user_ID ) === 0) {
					$should_count = true;
				}
				break;
			case 2:
				if ( intval( $user_ID ) > 0 ) {
					$should_count = true;
				}
				break;
		}
		if ( $should_count ) {
			wp_enqueue_script( 'wp-postviews-cache', get_template_directory_uri() . '/js/postviews-cache.js', array(), '', true );
			wp_localize_script( 'wp-postviews-cache', 'viewsCacheL10n', array( 'admin_ajax_url' => admin_url( 'admin-ajax.php' ), 'post_id' => intval( $post->ID ) ) );
		}
	}
}

function be_the_views( $display = true, $prefix = '', $postfix = '' ) {
	$post_views = intval( get_post_meta( get_the_ID(), 'views', true ) );

	if ( ! zm_get_option( 'viewsm' ) || ( zm_get_option( 'viewsm' ) == 'normal' ) ) {
		$template = '%VIEW_COUNT%';
	}
	if ( zm_get_option( 'viewsm' ) == 'unit' ) {
		$template = '%VIEW_COUNT_ROUNDED%';
	}

	$output = $prefix.str_replace( array( '%VIEW_COUNT%', '%VIEW_COUNT_ROUNDED%' ), array( number_format_i18n( $post_views ), begin_postviews_round_number( $post_views) ), stripslashes( $template ) ).$postfix;
	if ( zm_get_option( 'user_views' ) ) {
		if ( is_user_logged_in()) {
			if ( $display ) {
				echo apply_filters( 'be_the_views', $output );
			} else {
				return apply_filters( 'be_the_views', $output );
			}
		}
	} else {
		if ( $display ) {
			echo apply_filters( 'be_the_views', $output );
		} else {
			return apply_filters( 'be_the_views', $output );
		}
	}
}

add_action('publish_post', 'begin_add_views_fields');
add_action('publish_page', 'begin_add_views_fields');
function begin_add_views_fields( $post_ID ) {
	global $wpdb;
	if ( ! wp_is_post_revision( $post_ID ) ) {
		add_post_meta( $post_ID, 'views', 0, true );
	}
}

add_filter( 'query_vars', 'begin_views_variables' );
function begin_views_variables( $public_query_vars ) {
	$public_query_vars[] = 'v_sortby';
	$public_query_vars[] = 'v_orderby';
	return $public_query_vars;
}

add_action( 'wp_ajax_postviews', 'begin_increment_views' );
add_action( 'wp_ajax_nopriv_postviews', 'begin_increment_views' );
function begin_increment_views() {
	if ( empty( $_GET['postviews_id'] ) )
		return;

	if ( !defined( 'WP_CACHE' ) || !WP_CACHE )
		return;

	if ( null !== zm_get_option( 'use_ajax' ) && intval( zm_get_option( 'use_ajax' ) ) === 0 )
		return;

	$post_id = intval( $_GET['postviews_id'] );
	if ( $post_id > 0 ) {
		$post_views = get_post_custom( $post_id );
		$post_views = intval( $post_views['views'][0] );
		if ( intval( zm_get_option( 'random_count' ) ) === 1 ) {
			update_post_meta( $post_id, 'views', ( $post_views + mt_rand( 1, zm_get_option( 'rand_mt' ) ) ) );
			do_action( 'postviews_increment_views_ajax', ( $post_views + mt_rand( 1, zm_get_option( 'rand_mt' ) ) ) );
		} else {
			update_post_meta( $post_id, 'views', ( $post_views + 1 ) );
			do_action( 'postviews_increment_views_ajax', ( $post_views + 1 ) );
		}
		echo ( $post_views + 1 );
		exit();
	}
}

add_action( 'manage_posts_custom_column', 'begin_add_postviews_column_content' );
add_filter( 'manage_posts_columns', 'begin_add_postviews_column');
add_action( 'manage_pages_custom_column', 'begin_add_postviews_column_content' );
add_filter( 'manage_pages_columns', 'begin_add_postviews_column');
function begin_add_postviews_column( $defaults ) {
	$defaults['views'] = '浏览';
	return $defaults;
}

function begin_add_postviews_column_content($column_name) {
	if ( $column_name == 'views' ) {
		if ( function_exists( 'be_the_views' ) ) {
			be_the_views(true, '', '', true);
		}
	}
}

add_filter( 'manage_edit-post_sortable_columns', 'begin_sort_postviews_column');
add_filter( 'manage_edit-page_sortable_columns', 'begin_sort_postviews_column' );
function begin_sort_postviews_column( $defaults ) {
	$defaults['views'] = 'views';
	return $defaults;
}

add_action( 'pre_get_posts', 'begin_sort_postviews' );
function begin_sort_postviews( $query ) {
	if ( ! is_admin() ) {
		return;
	}

	$orderby = $query->get( 'orderby' );
	if ( 'views' === $orderby ) {
		$query->set( 'meta_key', 'views' );
		$query->set( 'orderby', 'meta_value_num' );
	}
}

function begin_postviews_round_number( $number, $min_value = 1000, $decimal = 1 ) {
	if ( $number < $min_value ) {
		return number_format_i18n( $number );
	}
	$alphabets = array( 1000000000 => 'B', 1000000 => sprintf( __( '百万', 'begin' ) ), 1000 => sprintf( __( '千', 'begin' ) ) );
	foreach( $alphabets as $key => $value )
		if ( $number >= $key ) {
			return round( $number / $key, $decimal ) . '' . $value;
		}
}