<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if ( zm_get_option( 'search_history' ) ) {
	// 创建数据库表
	function be_create_search_history_table() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'be_search_history';
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
			ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			search_term varchar(255) NOT NULL,
			search_time datetime NOT NULL,
			count int(11) NOT NULL DEFAULT 1,
			PRIMARY KEY (ID)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

	add_action( 'after_switch_theme', 'be_create_search_history_table' );

	if ( is_admin() && zm_get_option( 'search_history_data' ) ) {
		add_action( 'init', 'be_create_search_history_table' );
	}

	// 处理搜索请求
	function be_process_search_request() {
		$request_uri = $_SERVER['REQUEST_URI'];
		$exclude_paths = array( '/avatar/' ); // 定义需要排除的路径

		foreach ( $exclude_paths as $exclude_path ) {
			if ( strpos( $request_uri, $exclude_path ) !== false ) {
				return; // 如果请求 URI 包含需要排除的路径，则直接返回
			}
		}

		if ( isset( $_GET['s'] ) ) {
			$search_term = sanitize_text_field( $_GET['s'] );
			be_save_search_history( $search_term );
			return; // 只记录一次搜索历史
		}

		$search_path = '/search/';

		if ( strpos( $request_uri, $search_path ) !== false ) {
			$search_terms = explode( '/', trim( parse_url( $request_uri, PHP_URL_PATH), '/' ) );
			$search_key = array_search( 'search', $search_terms );
			if ( $search_key !== false && isset( $search_terms[$search_key + 1] ) ) {
				$search_term = sanitize_text_field( urldecode( $search_terms[$search_key + 1] ) );
				be_save_search_history( $search_term );
				return;
			}
		}
	}

	if ( ! current_user_can( 'administrator' ) ) {
		add_action( 'template_redirect', 'be_process_search_request' );
	}

	// 保存搜索记录到数据库
	function be_save_search_history( $search_term ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'be_search_history';

		// 检查是否已存在相同的搜索记录
		$existing_record = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM $table_name WHERE search_term = %s",
				$search_term
			)
		);

		if ( $existing_record ) {
			// 如果已存在相同的搜索记录，则更新计数
			$wpdb->update(
				$table_name,
				array(
					'search_time' => current_time( 'mysql' ),
					'count' => $existing_record->count + 1
				),
				array( 'ID' => $existing_record->ID ),
				array( '%s', '%d' ),
				array( '%d' )
			);
		} else {
			// 如果不存在相同的搜索记录，则插入新记录
			$wpdb->insert(
				$table_name,
				array(
					'search_term' => $search_term,
					'search_time' => current_time( 'mysql' ),
					'count' => 1
				),
				array('%s', '%s', '%d')
			);
		}
	}

	// 处理搜索记录删除请求
	function be_process_search_history_delete() {
		if ( isset( $_GET['action'] ) && $_GET['action'] === 'delete_search_history' ) {
			be_delete_all_search_history();
		} else if ( isset( $_GET['action'] ) && $_GET['action'] === 'delete_search_history_item' && isset( $_GET['search_id'] ) ) {
			$search_id = intval( $_GET['search_id'] );
			be_delete_search_history_item( $search_id );
		}
	}
	add_action( 'init', 'be_process_search_history_delete' );

	// 删除所有搜索记录
	function be_delete_all_search_history() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'be_search_history';
		$wpdb->query( "DELETE FROM $table_name" );
		// 重定向到搜索历史页面或其他适当的位置
		wp_redirect( home_url() . '/wp-admin/tools.php?page=search-log' );
		exit;
	}

	// 删除单个搜索记录
	function be_delete_search_history_item( $search_id ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'be_search_history';

		$wpdb->delete(
			$table_name,
			array( 'ID' => $search_id ),
			array( '%d' )
		);

		// 可以选择重定向到搜索历史页面或其他适当的位置
		wp_redirect( home_url() . '/wp-admin/tools.php?page=search-log' );
		exit;
	}

	// 获取搜索记录
	// ORDER BY date_time DESC, total_count DESC// 按月排序
	function be_get_search_history( $limit = 10, $title = false ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'be_search_history';

		// 获取当前的排序规则（如果存在），按数量total_count
		$orderby = isset( $_GET['orderby'] ) ? $_GET['orderby'] : 'date_time';
		$order = isset( $_GET['order'] ) ? $_GET['order'] : 'desc';

		$results = $wpdb->get_results(
			"SELECT ID, search_term, SUM(count) AS total_count, DATE_FORMAT(search_time, '%Y-%m-%d %H:%i:%s') AS date_time
			FROM $table_name
			GROUP BY date_time, search_term
			ORDER BY $orderby $order"
		);

		// 获取关键词的总数
		$total_keywords = $wpdb->get_var(
			"SELECT COUNT(DISTINCT search_term)
			FROM $table_name"
		);

		$html = '<div class="wrap recently-searches">';
		if ( $title ) {
			$html .= '<h3 class="searches-title recently-searches-title bezm-settings">' . esc_html( $title ) . '</h3>';
		}
		$html .= '<p class="searches-title recently-searches-title">' . $total_keywords . ' 个关键词</p>';

		if ( $results ) {
			$paged = ( isset( $_GET['paged']) && ! empty( $_GET['paged'] ) ) ? $_GET['paged'] : 1;
			$per_page = $limit;
			$total_pages = ceil( $total_keywords / $per_page );
			$start = ( $paged - 1 ) * $per_page + 1;
			$end = $paged * $per_page;
			$current_page = $paged;

			// 分页
			if ( $total_pages > 1 ) {
				$html .= '<div class="tablenav-pages">';
				for ( $i = 1; $i <= $total_pages; $i++ ) {
					$active = ( $i == $current_page ) ? 'active' : '';
					$html .= '<a class="pagination-links button ' . $active . '" href="' . esc_url( add_query_arg( array( 'paged' => $i ) ) ) . '">' . $i . '</a>';
				}
				$html .= '</div>';
			}

			$html .= '<table class="wp-list-table widefat fixed striped searches-table">';
			$html .= '<thead>';
			$html .= '<tr>';
			$html .= '<th scope="col" style="width: 40px;">序号</th>';
			$html .= '<th scope="col">关键词</th>';
			$html .= '<th scope="col" class="sorted" style="width: 120px;"><a href="' . esc_url( add_query_arg( array( 'orderby' => 'total_count', 'order' => $orderby == 'total_count' && $order == 'asc' ? 'desc' : 'asc' ) ) ) . '">搜索次数</a></th>';
			$html .= '<th scope="col" class="sorted"><a href="' . esc_url( add_query_arg( array( 'orderby' => 'date_time', 'order' => $orderby == 'date_time' && $order == 'asc' ? 'desc' : 'asc' ) ) ) . '">日期时间</a></th>';
			$html .= '<th scope="col">操作</th>';
			$html .= '</tr>';
			$html .= '</thead>';
			$html .= '<tbody>';
			$count = 1;
			foreach ( $results as $result ) {
				if ($count >= $start && $count <= $end) {
					$html .= '<tr>';
					$html .= '<td>' . $count . '</td>';
					$html .= '<td><a href="' . home_url( '/' ) . '?s=' . esc_html( $result->search_term ) . '" target="_blank">' . esc_html( $result->search_term ) . '</a></td>';
					$html .= '<td>' . esc_html( $result->total_count ) . '</td>';
					$html .= '<td>' . esc_html( $result->date_time ) . '</td>';
					$html .= '<td><a href="' . esc_url( add_query_arg( array( 'action' => 'delete_search_history_item', 'search_id' => $result->ID ) ) ) . '">删除</a></td>';
					$html .= '</tr>';
				}
				$count++;
			}
			$html .= '</tbody>';
			$html .= '</table>';

			// 分页
			if ( $total_pages > 1 ) {
				$html .= '<div class="tablenav-pages">';
				for ( $i = 1; $i <= $total_pages; $i++ ) {
					$active = ( $i == $current_page ) ? 'active' : '';
					$html .= '<a class="pagination-links button ' . $active . '" href="' . esc_url( add_query_arg( array( 'paged' => $i ) ) ) . '">' . $i . '</a>';
				}
				$html .= '</div>';
			}

			$html .= '<div class="tablenav bottom">';
			$html .= '<a class="button" href="' . esc_url( add_query_arg( 'action', 'delete_search_history' ) ) . '" class="recently-searches-del">清理全部</a>';
			$html .= '</div>';
		} else {
			$count = 1;
			$html .= '<table class="wp-list-table widefat fixed striped searches-table">';
			$html .= '<thead>';
			$html .= '<tr>';
			$html .= '<th scope="col" style="width: 40px;">序号</th>';
			$html .= '<th scope="col">关键词</th>';
			$html .= '<th scope="col" class="sorted" style="width: 120px;">搜索次数</th>';
			$html .= '<th scope="col" class="sorted">日期时间</th>';
			$html .= '<th scope="col">操作</th>';
			$html .= '</tr>';
			$html .= '</thead>';
			$html .= '<tbody>';
			$html .= '<tr>';
			$html .= '<td>' . $count . '</td>';
			$html .= '<td>暂无搜索记录</td>';
			$html .= '<td>无</td>';
			$html .= '<td>无</td>';
			$html .= '<td>无</td>';
			$html .= '</tr>';
			$html .= '</tbody>';
			$html .= '</table>';
		}
		$html .= '</div>';
		return $html;
	}

	// 添加菜单
	function display_search_log() {
		echo be_get_search_history( zm_get_option( 'search_history_n' ), '搜索记录' );
	}

	function add_search_log_page() {
		add_management_page( '搜索记录', '<span class="bem"></span>搜索记录', 'manage_options', 'search-log', 'display_search_log' );
	}

	add_action( 'admin_menu', 'add_search_log_page' );

	// 热门搜索
	function be_hot_search( $limit = 10, $title = false ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'be_search_history';
		$results = $wpdb->get_results(
			"SELECT search_term, SUM(count) AS total_count FROM $table_name GROUP BY search_term ORDER BY total_count DESC LIMIT $limit"
		);

		$html = '<div class="recently-searches">';
		if ( $title ) {
			$html .= '<h3 class="searches-title recently-searches-title">' . esc_html( $title ) . '</h3>';
		}
		$html .= '<ul class="recently-searches">';
		foreach ( $results as $result ) {
			$html .= '<li class="search-item"><a href="' . home_url( '/' ) . '?s=' . esc_html( $result->search_term ) . '">' . esc_html( $result->search_term ) . '</a></li>';
		}
		$html .= '</ul>';
		$html .= '</div>';
		return $html;
	}

	// AJAX加载热门搜索记录
	function load_hot_search_list() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'be_search_history';
		$limit = zm_get_option( 'search_hot_n' );
		$results = $wpdb->get_results(
			"SELECT search_term, SUM(count) AS total_count FROM $table_name GROUP BY search_term ORDER BY total_count DESC LIMIT $limit"
		);

		$html = '<div class="recently-searches">';
		$html .= '<h3 class="searches-title recently-searches-title">' . sprintf(__( '热门搜索', 'begin' ) ) . '</h3>';

		$html .= '<ul class="recently-searches">';
		if ( $results ) {
			foreach ( $results as $result ) {
				$html .= '<li class="search-item"><a href="' . home_url( '/' ) . '?s=' . esc_html( $result->search_term ) . '">' . esc_html( $result->search_term ) . '</a></li>';
			}
		}
		$html .= '</ul>';
		$html .= '</div>';
		echo $html;
		wp_die();
	}
	add_action('wp_ajax_search_hot_list', 'load_hot_search_list');
	add_action('wp_ajax_nopriv_search_hot_list', 'load_hot_search_list');
}