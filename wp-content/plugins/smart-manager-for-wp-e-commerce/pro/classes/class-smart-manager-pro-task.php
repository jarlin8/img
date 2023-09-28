<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Smart_Manager_Pro_Task' ) ) {
	/**
	 * Class that extends Smart_Manager_Pro_Base
	 */
	class Smart_Manager_Pro_Task extends Smart_Manager_Pro_Base {
		/**
		 * Current dashboard name
		 *
		 * @var string
		 */
		public $dashboard_key = '';
		/**
		 * Selected record ids
		 *
		 * @var array
		 */
		public $selected_ids = array();
		/**
		 * Entire task records
		 *
		 * @var boolean
		 */
		public $entire_task = false;
		/**
		 * Singleton class
		 *
		 * @var object
		 */
		protected static $_instance = null;
		/**
		 * Advanced search table types
		 *
		 * @var array
		 */
		public $advanced_search_table_types = array(
			'flat' => array(
				'sm_tasks' => 'id'
			) 
		);
		/**
		 * Instance of the class
		 *
		 * @param string $dashboard_key Current dashboard name.
		 * @return object
		 */
		public static function instance( $dashboard_key ) {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self( $dashboard_key );
			}
			return self::$_instance;
		}
		/**
		 * Constructor is called when the class is instantiated
		 *
		 * @param string $dashboard_key $dashboard_key Current dashboard name.
		 * @return void
		 */
		function __construct( $dashboard_key ) {
			add_filter(
				'sm_search_table_types',
				function( $advanced_search_table_types = array() ) {
					return $this->advanced_search_table_types;
				}
			); // should be kept before calling the parent class constructor.
			parent::__construct( $dashboard_key );
			self::actions();
			$this->dashboard_key = $dashboard_key;
			global $current_user;
			$this->store_col_model_transient_option_nm = 'sa_sm_' . $this->dashboard_key . '_tasks';
			add_filter( 'sm_default_dashboard_model', array( &$this, 'generate_dashboard_model' ) );
			add_filter( 'sm_data_model', array( &$this, 'generate_data_model' ), 10, 2 );
			add_filter(
				'sm_beta_load_default_store_model',
				function() {
					return false;
				}
			);
			add_filter(
				'sm_beta_load_default_data_model',
				function() {
					return false;
				}
			);
		}
		/**
		 * Add filters for doing actions
		 *
		 * @return void
		 */
		public static function actions() {
			add_filter( 'sm_beta_background_entire_store_ids_from', __CLASS__ . '::undo_all_task_ids_from_clause' );
			add_filter( 'sm_beta_background_entire_store_ids_where', __CLASS__ . '::undo_all_task_ids_where_clause' );
			add_filter( 'sm_post_batch_update_db_updates', __CLASS__ . '::post_undo', 10, 2 );
			add_action( 'sm_background_process_complete', __CLASS__ . '::background_process_complete' );
		}

		/**
		 * Generate dashboard model
		 *
		 * @param array $dashboard_model array contains the dashboard_model data.
		 * @return array $dashboard_model returns dashboard_model data
		 */
		public function generate_dashboard_model( $dashboard_model = array() ) {
			global $wpdb, $current_user;
			$col_model = array();
			$results   = $wpdb->get_results( "SHOW COLUMNS FROM {$wpdb->prefix}sm_tasks", 'ARRAY_A' );
			$num_rows  = $wpdb->num_rows;
			$enum_fields = array( 'status', 'type' );
			$display_names = array(
				'id' => __( 'ID', 'smart-manager-for-wp-e-commerce' )
			);
			if ( $num_rows > 0 ) {
				foreach ( $results as $result ) {
					$field_nm = ( ! empty( $result['Field'] ) ) ? $result['Field'] : '';
					$args = array(
						'table_nm' => 'sm_tasks',
						'col'      => $field_nm,
						'db_type'  => ( ! empty( $result['Type'] ) ) ? $result['Type'] : '',
						'editable' => false,
						'editor'   => false,
					);
					if ( 'post_type' === $field_nm ) {
						$args['type'] = 'dropdown';
						$args['search_values'][] = array( 'key' => $this->dashboard_key, 'value' => __( ucwords( str_replace( '_', ' ', $this->dashboard_key ) ), 'smart-manager-for-wp-e-commerce' ) );
					} elseif ( in_array( $field_nm, $enum_fields, true ) ) {
						$args['type'] = 'dropdown';
						$args['width'] = 100;
						if ( ! empty( $this->get_col_values( $field_nm ) ) && is_array( $this->get_col_values( $field_nm ) ) ) {
							foreach ( $this->get_col_values( $field_nm ) as $key => $value ) {
								$args['search_values'][] = array(
									'key'   => $key,
									'value' => $value,
								);
							}
						}
					} elseif ( 'actions' === $field_nm ) {
						$args['editor'] = 'sm.serialized';
					} elseif ( 'record_count' === $field_nm ) {
						$args['width'] = 100;
					}

					if( ! empty( $display_names[$field_nm] ) ){
						$args['name'] = $display_names[$field_nm];
					}

					$col_model [] = $this->get_default_column_model( $args );
				}
			}

			return array(
				'display_name'   => __( ucwords( str_replace( '_', ' ', $this->dashboard_key . '_tasks' ) ), 'smart-manager-for-wp-e-commerce' ),
				'columns'        => $col_model,
				'per_page_limit' => '', // blank, 0, -1 all values refer to infinite scroll.
				'treegrid'       => false, // flag for setting the treegrid.
			);
		}

		/**
		 * Generate data model
		 *
		 * @param array $data_model array containing the data model.
		 * @param array $data_col_params array containing column params.
		 * @return array $data_model returns data_model array
		 */
		public function generate_data_model( $data_model = array(), $data_col_params = array() ) {
			global $wpdb;
			$current_user_id     = get_current_user_id();
			$items               = array();
			$index               = 0;
			$join = $post_type  = '';
			$where               = apply_filters( 'sm_where_tasks_cond', ' AND post_type = %s AND author = %d' );
			$order_by            = apply_filters( 'sm_orderby_tasks_cond', $wpdb->prefix . 'sm_tasks.id DESC ' );
			$group_by            = apply_filters( 'sm_groupby_tasks_cond', ' ' . $wpdb->prefix . 'sm_tasks.id ' );
			$start               = ( ! empty( $this->req_params['start'] ) ) ? intval( $this->req_params['start'] ) : 0;
			$limit               = ( ! empty( $this->req_params['sm_limit'] ) ) ? intval( $this->req_params['sm_limit'] ) : 50;
			$current_page        = ( ! empty( $this->req_params['sm_page'] ) ) ? $this->req_params['sm_page'] : '1';
			$start_offset        = ( ( $current_page > 1 ) && ( ! empty( $limit ) ) ) ? intval( ( ( $current_page - 1 ) * $limit ) ) : $start;
			$current_store_model = self::get_store_model_transient();
			if ( ! empty( $current_store_model ) && ! is_array( $current_store_model ) ) {
				$current_store_model = json_decode( $current_store_model, true );
			}
			$col_model = ( ! empty( $current_store_model['columns'] ) ) ? $current_store_model['columns'] : array();
			if ( empty( $col_model ) || ! is_array( $col_model ) ) {
				return;
			}
			$search_cols_type = array(); // array for col & its type for advanced search.
			// Code to handle simple search functionality.
			if ( ! empty( $this->req_params['search_text'] ) || ( ! empty( $this->req_params['advanced_search_query'] ) && '[]' !== $this->req_params['advanced_search_query'] ) ) {
				if ( ! empty( $this->req_params['search_text'] ) ) {
					$where_cond  = array();
					$search_text = $wpdb->_real_escape( $this->req_params['search_text'] );
				}
				// Code for getting tasks table condition.
				foreach ( $col_model as $col ) {
					switch ( true ) {
						case ( ! empty( $this->req_params['search_text'] ) ):
							if ( empty( $col['src'] ) ) {
								break;
							}
							$src_exploded = explode( '/', $col['src'] );
							if ( ! empty( $src_exploded[0] ) && ( 'sm_tasks' === $src_exploded[0] ) ) {
								$where_cond[] = "( {$wpdb->prefix}sm_tasks." . $src_exploded[1] . " LIKE %s )";
							}
							break;
						default:
							if ( ! empty( $col['table_name'] ) && ! empty( $col['col_name'] ) ) {
								$search_cols_type[ $col['table_name'] . '.' . $col['col_name'] ] = $col['type'];
							}
					}
				}
				if ( ! empty( $this->req_params['search_text'] ) ) {
					$where .= ( ! empty( $where_cond ) ) ? ' AND (' . implode( ' OR ', $where_cond ) . ' )' : '';
				}
			}
			// Code fo handling advanced search functionality.
			$sm_advanced_search_results_persistent = 0; // flag to handle persistent search results.
			if ( ! empty( $this->req_params['advanced_search_query'] ) && ( '[]' !== $this->req_params['advanced_search_query'] ) ) {
				$this->req_params['advanced_search_query'] = json_decode( stripslashes( $this->req_params['advanced_search_query'] ), true );
				if ( ! empty( $this->req_params['advanced_search_query'] ) ) {
					if ( ! empty( $this->req_params['table_model']['posts']['where']['post_type'] ) ) {
						$post_type = ( is_array( $this->req_params['table_model']['posts']['where']['post_type'] ) ) ? $this->req_params['table_model']['posts']['where']['post_type'] : array( $this->req_params['table_model']['posts']['where']['post_type'] );
					}
					$this->process_search_cond(
						array(
							'post_type' => $post_type,
							'search_query' => ( ! empty( $this->req_params['advanced_search_query'] ) ) ? $this->req_params['advanced_search_query'] : array(),
							'SM_IS_WOO30' => ( ! empty( $this->req_params['SM_IS_WOO30'] ) ) ? $this->req_params['SM_IS_WOO30'] : '',
							'search_cols_type' => $search_cols_type,
							'data_col_params' => $data_col_params,
						)
					);
				}
				$join = " JOIN {$wpdb->base_prefix}sm_advanced_search_temp
								ON ({$wpdb->base_prefix}sm_advanced_search_temp.product_id = {$wpdb->prefix}sm_tasks.id)";
				$where .= " AND {$wpdb->base_prefix}sm_advanced_search_temp.flag > 0";
			}
			// Code for sorting tas records.
			if ( ! empty( $this->req_params['sort_params'] ) ) {
				if ( ! empty( $this->req_params['sort_params']['column'] ) && ! empty( $this->req_params['sort_params']['sortOrder'] ) ) {
					if ( false !== strpos( $this->req_params['sort_params']['column'], '/' ) ) {
						$col_exploded = explode( '/', $this->req_params['sort_params']['column'] );
						$order_by                                     = $wpdb->prefix . $col_exploded[0] . '.' . $col_exploded[1] . ' ' . strtoupper( $this->req_params['sort_params']['sortOrder'] ) . ' ';
					}
				}
			}
			$query_limit_str  = ( ! empty( $this->req_params['cmd'] ) && ( 'get_export_csv' === $this->req_params['cmd'] ) ) ? '' : 'LIMIT ' . $start_offset . ', ' . $limit;
			$args = ( ! empty( $this->req_params['search_text'] ) ) ? array(
			1,
			$this->dashboard_key,
			$current_user_id,
			'%' . $wpdb->esc_like( $search_text ) . '%',
			'%' . $wpdb->esc_like( $search_text ) . '%',
			'%' . $wpdb->esc_like( $search_text ) . '%',
			'%' . $wpdb->esc_like( $search_text ) . '%',
			'%' . $wpdb->esc_like( $search_text ) . '%',
			'%' . $wpdb->esc_like( $search_text ) . '%',
			'%' . $wpdb->esc_like( $search_text ) . '%',
			'%' . $wpdb->esc_like( $search_text ) . '%',
			'%' . $wpdb->esc_like( $search_text ) . '%',
			'%' . $wpdb->esc_like( $search_text ) . '%'
			) : array( 1, $this->dashboard_key, $current_user_id );
			$ids              = $wpdb->get_col(
				$wpdb->prepare(
					"SELECT DISTINCT {$wpdb->prefix}sm_tasks.id
					FROM {$wpdb->prefix}sm_tasks
					". $join ."
					WHERE 1 = %d" . $where,
					$args
				)
			);
			$total_count      = $wpdb->num_rows;
			// Code for saving the task ids.
			if ( ( defined( 'SMPRO' ) && true === SMPRO ) && ( ! empty( $this->req_params['search_text'] ) ) || ( ! empty( $this->req_params['advanced_search_query'] ) && '[]' === $this->req_params['advanced_search_query'] ) && ( ! empty( $ids ) ) ) {
				set_transient( 'sa_sm_search_post_ids', implode( ',', $ids ), WEEK_IN_SECONDS );
			}
			$task_results = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT {$wpdb->prefix}sm_tasks.* 
					FROM {$wpdb->prefix}sm_tasks
					" . $join . '
					WHERE 1=%d 
					' . $where . '
					GROUP BY' . $group_by . '
					ORDER BY ' . $order_by . '
					' . $query_limit_str,
					$args
				),
				ARRAY_A
			);
			$total_pages  = 1;
			if ( ( ! empty( $total_count ) ) && ( $total_count > $limit ) && ( 'get_export_csv' !== $this->req_params['cmd'] ) ) {
				$total_pages = ceil( $total_count / $limit );
			}
			if ( ! empty( $task_results ) ) {
				foreach ( $task_results as $tasks ) {
					foreach ( $tasks as $key => $value ) {
						if ( is_array( $data_col_params['data_cols'] ) && ! empty( $data_col_params['data_cols'] ) ) {
							if ( false === array_search( $key, $data_col_params['data_cols'] ) ) {
								continue; // cond for checking col in col model.
							}
						}
						$key_mod                  = 'sm_tasks_' . strtolower( str_replace( ' ', '_', $key ) );
						$items[ $index ][ $key_mod ] = $value;
					}
					$index++;
				}
			}
			$data_model ['items']       = ( ! empty( $items ) ) ? $items : array();
			$data_model ['start']       = $start + $limit;
			$data_model ['page']        = $current_page;
			$data_model ['total_pages'] = ( ! empty( $total_pages ) ) ? $total_pages : 0;
			$data_model ['total_count'] = ( ! empty( $total_count ) ) ? $total_count : 0;
			return $data_model;
		}

		/**
		 * Task updation
		 *
		 * @param array $params contains status, completed date, title, date, post type, author, type, status, actions, record_count.
		 * @return int inserted task id in case of insertion or number of affected rows in case of updation
		 */
		public static function task_update( $params = array() ) {
			global $wpdb;
			if ( empty( $params ) && ( ! is_array( $params ) ) ) {
				return;
			}
			if ( ( ! empty( $params['task_id'] ) ) && ( ( ! empty( $params['status'] ) ) || ( ! empty( $params['completed_date'] ) ) ) ) {
				$set_query = '';
				switch ( $params ) {
					case ( ! empty( $params['status'] ) && ( ! isset( $params['completed_date'] ) ) ):
						$set_query = "status = '{$params['status']}'";
						break;
					case ( ! isset( $params['status'] ) && ( ! empty( $params['completed_date'] ) ) ):
						$set_query = "completed_date = '{$params['completed_date']}'";
						break;
					default:
						$set_query = "status = '{$params['status']}', completed_date = '{$params['completed_date']}'";
					}
				if ( empty( $set_query ) ) {
					return;
				}
				return $wpdb->query( "UPDATE {$wpdb->prefix}sm_tasks SET " . $set_query . " WHERE id = " . $params['task_id'] . "" );
			} elseif ( ! empty( $params['title'] ) && ! empty( $params['post_type'] ) && ! empty( $params['type'] ) && ! empty( $params['actions'] ) && ! empty( $params['record_count'] ) ) {
				$wpdb->query(
					$wpdb->prepare(
						"INSERT INTO {$wpdb->prefix}sm_tasks( title, date, completed_date, post_type, author, type, status, actions, record_count)
						VALUES( %s, %s, %s, %s, %d, %s, %s, %s, %d )",
						$params['title'],
						( ! empty( $params['created_date'] ) ) ? $params['created_date'] : '0000-00-00 00:00:00',
						'0000-00-00 00:00:00',
						$params['post_type'],
						get_current_user_id(),
						$params['type'],
						( ! empty( $params['status'] ) ) ? $params['status'] : 'in-progress',
						json_encode( $params['actions'] ),
						$params['record_count']
					)
				);
			}
			return ( ! is_wp_error( $wpdb->insert_id ) ) ? $wpdb->insert_id : 0;
		}

		/**
		 * Insert task details into sm_task_details table
		 *
		 * @param array $params contains task_id, action, status, record_id, field, prev_val, updated_val.
		 * @return void
		 */
		public static function task_details_update() {
			global $wpdb;
			$params = ( ! empty( property_exists( 'Smart_Manager_Base', 'update_task_details_params' ) ) ) ? Smart_Manager_Base::$update_task_details_params : array();
			if ( empty( $params ) && ( ! is_array( $params ) ) ) {
				return;
			}
			$task_id         = array();
			$task_details_id = array();
			foreach ( $params as $param ) {
				if ( empty( $param['task_id'] ) || empty( $param['action'] ) || empty( $param['status'] ) || empty( $param['record_id'] ) || empty( $param['field'] ) ) {
					continue;
				}
				$task_id = array( $param['task_id'] );
				$wpdb->query(
					$wpdb->prepare(
						"INSERT INTO {$wpdb->prefix}sm_task_details( task_id, action, status, record_id, field, prev_val, updated_val )
						VALUES( %d, %s, %s, %d, %s, %s, %s )",
						$param['task_id'],
						$param['action'],
						$param['status'],
						$param['record_id'],
						$param['field'],
						( isset( $param['prev_val'] ) ) ? $param['prev_val'] : '',
						( isset( $param['updated_val'] ) ) ? $param['updated_val'] : ''
					)
				);
				$task_details_id[] = ( ! is_wp_error( $wpdb->insert_id ) ) ? $wpdb->insert_id : array();
			}
			if ( ( ! empty( $task_details_id ) ) && ( count( $params ) === count( $task_details_id ) ) ) {
				self::task_update(
					array(
						'task_id' => implode( '', $task_id ),
						'status' => 'completed',
						'completed_date' => date( 'Y-m-d H:i:s' )
					)
				);
			}
		}

		/**
		 * Undo changes for task records
		 *
		 * @return void
		 */
		public function undo() {
			$this->get_task_detail_ids( '_undo_task_id' );
			$this->send_to_background_process(
				array(
					'process_name' => 'Undo Tasks',
					'callback'     => array(
						'class_path' => $this->req_params['class_path'],
						'func'       => array(
							$this->req_params['class_nm'],
							'process_undo',
						),
					),
				)
			);
		}

		/**
		 * Processing undo for task record
		 *
		 * @param array $args contains task_details_ids, fetch.
		 * @return void
		 */
		public static function process_undo( $args = array() ) {
			if ( empty( $args )|| empty( $args['id'] ) ) {
				return;
			}
			$col_data_type = parent::get_column_data_type( $args['dashboard_key'] );
			$dashboard_key = $args['dashboard_key'];
			$args = self::get_task_details(
				array(
					'task_details_ids' => ( ! is_array( $args['id'] ) ) ? array( $args['id'] ) : $args['id'],
					'fetch'            => 'all',
				)
			);
			$type = apply_filters( 'sm_custom_field_name', $args['type'] );
			$args['date_type'] = ( ! empty( $col_data_type[ $type ] ) ) ? $col_data_type[ $type ] : 'text';
			$args['dashboard_key'] = $dashboard_key;
			$arg_values = ( ! empty( $args['value'] ) ) ? explode( ',', $args['value'] ) : '';

			if ( 'set_to' === $args['operator'] && 'sm.multilist' === $args['date_type'] && ( ! empty( $arg_values ) && ( count( $arg_values ) > 0 ) ) && ( ! empty( $args['updated_value'] ) ) ) {
				$arg_updated_values = explode( ',', $args['updated_value'] );
				foreach ( $arg_updated_values as $arg_updated_value ) {
					$args['value'] = $arg_updated_value;
					$args['operator'] = 'remove_from';
					if ( ! empty( $args ) ) {
						parent::process_batch_update( $args );
					}
				}
				foreach ( $arg_values as $value ) {
					$args['value'] = $value;
					$args['operator'] = 'add_to';
					if ( ! empty( $args ) ) {
						parent::process_batch_update( $args );
					}
				}
			} elseif ( ! empty( $args ) ) {
				$args = parent::process_batch_update( $args );
			}
		}

		/**
		 *  Function to update the from clause for getting entire task ids from tasks table
		 *
		 * @param string $from from string.
		 * @return string from query
		 */
		public static function undo_all_task_ids_from_clause( $from = '' ) {
			return ( empty( $from ) ) ? $from : str_replace( 'posts', 'sm_tasks', $from );
		}

		/**
		 * Function to update the where clause for getting entire task ids from tasks table
		 *
		 * @param string $where where string.
		 * @return string where query
		 */
		public static function undo_all_task_ids_where_clause( $where = '' ) {
			return ( ! empty( $where ) && ( false === strpos( $where, 'WHERE' ) ) ) ? 'WHERE 1=1 ' : str_replace( "AND post_status != 'trash'", '', $where );
		}

		/**
		 * Get task ids from tasks table based on completed and scheduled date time
		 *
		 * @param string $scheduled_datetime scheduled datetime.
		 * @return array $task_ids task ids array
		 */
		public static function get_task_ids( $scheduled_datetime = '' ) {
			if ( empty( $scheduled_datetime ) ) {
				return;
			}
			global $wpdb;
			$task_ids = $wpdb->get_col(
				"SELECT id
				FROM {$wpdb->prefix}sm_tasks
				WHERE completed_date < '" . $scheduled_datetime . "'"
			);
			return ( ! is_wp_error( $task_ids ) ) ? $task_ids : array();
		}

		/**
		 * Get task details
		 *
		 * @param array $params task_ids, task_details_ids, fetch.
		 * @return array task details [ids( tasks/task details ), count of id, record_id, field, prev_value, operator]
		 */
		public static function get_task_details( $params = array() ) {
			if ( empty( $params ) ) {
				return;
			}
			global $wpdb;
			$task_ids         = ( ! empty( $params['task_ids'] ) ) ? $params['task_ids'] : array();
			$task_details_ids = ( ! empty( $params['task_details_ids'] ) ) ? $params['task_details_ids'] : array();
			$fetch            = ( ! empty( $params['fetch'] ) ) ? $params['fetch'] : array();
			switch ( $params ) {
				case ( ( ! empty( $task_ids ) ) && ( ! empty( $fetch ) ) && ( 'ids' === $fetch ) ):
					return $wpdb->get_results(
						"SELECT task_id AS task_id, id AS task_details_id
						FROM {$wpdb->prefix}sm_task_details
						WHERE task_id IN (" . implode( ',', $task_ids ) . ')',
						'ARRAY_A'
					);
				case ( ( ! empty( $task_details_ids ) ) && ( ! empty( $fetch ) ) && ( 'all' === $fetch ) ):
					return $wpdb->get_row(
						"SELECT id AS task_details_id, record_id AS id, field AS type, prev_val AS value, action AS operator, updated_val AS updated_value
						FROM {$wpdb->prefix}sm_task_details
						WHERE id IN (" . implode( ',', $task_details_ids ) . ')',
						'ARRAY_A'
					);
				case ( ( ! empty( $task_ids ) ) && ( ! empty( $fetch ) ) && ( 'count' === $fetch ) ):
					return $wpdb->get_results(
						"SELECT task_id AS id, IFNULL( count(id), 0 ) AS count
						FROM {$wpdb->prefix}sm_task_details
						WHERE task_id IN (" . implode( ',', $task_ids ) . ')
						GROUP BY task_id',
						'ARRAY_A'
					);
			}
		}

		/**
		 * Delete tasks
		 *
		 * @return void
		 */
		public function delete() {
			$this->get_task_detail_ids( '_delete_task_id' );
			$this->send_to_background_process(
				array(
					'process_name' => 'Delete Tasks',
					'callback' => array(
						'class_path' => $this->req_params['class_path'],
						'func' => array(
							$this->req_params['class_nm'], 'process_delete'
						),
					),
				)
			);
		}

		/**
		 * Process the deletion of task details record
		 *
		 * @param array $args record id.
		 * @return boolean
		 */
		public static function process_delete( $args = array() ) {
			if ( empty( $args ) && empty( $args['id'] ) ) {
				return false;
			}
			return ( self::delete_task_details( ( ! is_array( $args['id'] ) ? array( $args['id'] ) : $args['id'] ) ) ) ? true : false;
		}

		/**
		 * Delete tasks from tasks table
		 *
		 * @param array $task_ids array of task ids.
		 * @return boolean true if number of rows deleted, or false on error
		 */
		public static function delete_tasks( $task_ids = array() ) {
			if ( empty( $task_ids ) || ( ! is_array( $task_ids ) ) ) {
				return false;
			}
			global $wpdb;
			return ( ! is_wp_error(
				$wpdb->query(
					"DELETE FROM {$wpdb->prefix}sm_tasks
					WHERE id IN (" . implode( ',', $task_ids ) . ')'
				)
			) ) ? true : false;
		}

		/**
		 * Delete task details from task details table
		 *
		 * @param array $task_detail_ids task detail ids.
		 * @return boolean true if number of rows deleted, or false on error
		 */
		public static function delete_task_details( $task_detail_ids = array() ) {
			if ( empty( $task_detail_ids ) && ! is_array( $task_detail_ids ) ) {
				return false;
			}
			global $wpdb;
				return ( ! is_wp_error(
					$wpdb->query(
						"DELETE FROM {$wpdb->prefix}sm_task_details
						WHERE id IN (" . implode( ',', $task_detail_ids ) . ')'
					)
				) ) ? true : false;
		}

		/**
		 * Schedule task deletion after x number of days
		 *
		 * @return void
		 */
		public static function schedule_task_deletion() {
			if ( ! function_exists( 'as_has_scheduled_action' ) ) {
				return;
			}
			$is_scheduled = as_has_scheduled_action( 'sm_schedule_tasks_cleanup' ) ? true : false;
			if ( ! ( ( false === $is_scheduled ) && function_exists( 'as_schedule_single_action' ) ) ) {
				return;
			}
			$task_deletion_days = intval( get_option( 'sa_sm_tasks_cleanup_interval_days' ) );
			if ( empty( $task_deletion_days ) ) {
				$task_deletion_days = intval( apply_filters( 'sa_sm_tasks_cleanup_interval_days', 90 ) );
				if ( empty( $task_deletion_days ) ) {
					return;
				}
				update_option( 'sa_sm_tasks_cleanup_interval_days', $task_deletion_days, 'no' );
			}
			$timestamp = strtotime( date('Y-m-d H:i:s', strtotime( "+".$task_deletion_days." Days" ) ) );
			if ( empty( $timestamp ) ) {
				return;
			}
			as_schedule_single_action( $timestamp, 'sm_schedule_tasks_cleanup' ); 
		}

		/**
		 * Get previous data
		 *
		 * @param int    $post_id for getting previous data by passing post id.
		 * @param string $table for getting previous data by passing table name.
		 * @param string $column for getting previous data by passing column name.
		 * @return result of function call
		 */
		public static function get_previous_data( $post_id = 0, $table = '', $column = '' ) {
			if ( empty( $post_id ) || empty( $table ) || empty( $column ) ) {
				return;
			}
			switch ( $table ) {
				case 'posts':
					return get_post_field( $column, $post_id );
				case 'postmeta':
					return get_post_meta( $post_id, $column, true );
				case 'terms':
					return wp_get_object_terms( $post_id, $column, 'orderby=none&fields=ids' );
			}
		}

		/**
		 * Get store column model transient
		 *
		 * @return result of function call
		 */
		public function get_store_model_transient() {
			if ( empty( $this->store_col_model_transient_option_nm ) ) {
				return;
			}
			return get_transient( $this->store_col_model_transient_option_nm );
		}

		/**
		 * Delete task details after changes are undone
		 *
		 * @param boolean $delete_flag flag for delete.
		 * @param array   $params task_details_id.
		 * @return boolean
		 */
		public static function post_undo( $delete_flag = true, $params = array() ) {
			if ( empty( $params['task_details_id'] ) && ( empty( $delete_flag ) ) ) {
				return;
			}
			return ( self::delete_task_details( ( ! is_array( $params['task_details_id'] ) ? array( $params['task_details_id'] ) : $params['task_details_id'] ) ) ) ? true : false;
		}

		/**
		 * Delete tasks from tasks table and delete undo/delete option from options table after completing undo/delete action
		 *
		 * @param string $identifier identifier name - either undo or delete.
		 * @return void
		 */
		public static function background_process_complete( $identifier = '' ) {
			if ( empty( $identifier ) ) {
				return $identifier;
			}
			$failed_task_ids = array();
			$option_nm = self::get_process_option_name( $identifier );
			if ( empty( $option_nm ) ) {
				return;
			}
			$task_ids = get_option( $identifier . $option_nm );
			if ( empty( $task_ids ) ) {
				return;
			}
			$results = self::get_task_details(
				array(
					'task_ids' => $task_ids,
					'fetch'    => 'count',
				)
			);
			if ( ! empty( $results ) ) {
				foreach ( $results as $result ) {
					if ( ! empty( $result['count'] ) ) {
						$failed_task_ids[] = $result['id'];
					}
				}
			}
			$delete_task_ids = ( ! empty( $failed_task_ids ) && is_array( $failed_task_ids ) && is_array( $task_ids ) ) ? array_diff( $task_ids, $failed_task_ids ) : $task_ids;
			if ( empty( $delete_task_ids ) ) {
				return;
			}
			if ( self::delete_tasks( $delete_task_ids ) ) {
				delete_option( $identifier . $option_nm );
			}
		}

		/**
		 * Get task detail ids using selected task ids and store them in options table in case of undo and delete actions
		 *
		 * @param string $option_nm option name - either _undo_task_id or _delete_task_id.
		 * @return array $fetched_task_details_ids ids of task details
		 */
		public function get_task_detail_ids( $option_nm = '' ) {
			if ( empty( $option_nm ) ) {
				return;
			}
			$identifier = '';
			$task_ids = ( ! empty( $this->req_params['selected_ids'] ) ) ? json_decode( stripslashes( $this->req_params['selected_ids'] ), true ) : array();
			if ( ( ! empty( $this->req_params['storewide_option'] ) ) && ( 'entire_store' === $this->req_params['storewide_option'] ) && ( ! empty( $this->req_params['active_module'] ) ) ) { 
				$task_ids = $this->get_entire_store_ids();
				$this->entire_task = true;
			}
			if ( is_callable( array( 'Smart_Manager_Pro_Background_Updater', 'get_identifier' ) ) ) {
				$identifier = Smart_Manager_Pro_Background_Updater::get_identifier();
			}
			if ( ! empty( $identifier ) && ( ! empty( $task_ids ) ) ) {
				update_option( $identifier . $option_nm, $task_ids, 'no' );
			}
			$task_details_ids = self::get_task_details(
				array(
					'task_ids' => $task_ids,
					'fetch' => 'ids',
				)
			);
			$fetched_task_details_ids = array();
			foreach ( $task_details_ids as $task_details_id ) {
				$fetched_task_details_ids[] = $task_details_id['task_details_id'];
			}
			$this->req_params['selected_ids'] = ( ! empty( $fetched_task_details_ids ) && is_array( $fetched_task_details_ids ) ) ? json_encode( $fetched_task_details_ids ) : $this->req_params['selected_ids']; // ids of task details.
		}

		/**
		 * Get process option name from options table incase of undo and delete actions
		 *
		 * @param string $identifier identifier name - either undo or delete.
		 * @return string | boolean process option name if true, else false
		 */
		public static function get_process_option_name( $identifier = '' ) {
			if ( empty( $identifier ) ) {
				return;
			}
			$params = get_option( $identifier . '_params' );
			if ( empty( $params['process_name'] ) ) {
				return;
			}
			$process_names = array( 'Undo Tasks', 'Delete Tasks' );
			return ( in_array( $params['process_name'], $process_names, true ) ) ? ( ( 'Undo Tasks' === $params['process_name'] ) ? '_undo_task_id' : '_delete_task_id' ) : false;
		}
		/**
		 * Get column values for particular column
		 *
		 * @param string $field_nm field name - column/field name
		 * @return array array of column values for particular column
		 */
		public function get_col_values( $field_nm = '' ) {
			if ( empty( $field_nm ) ) {
				return;
			}
			switch( $field_nm ) {
				case 'status':
					return array(
						'in-progress' => __( 'In Progress', 'smart-manager-for-wp-e-commerce' ),
						'completed' => __( 'Completed', 'smart-manager-for-wp-e-commerce' ),
						'scheduled' => __( 'Scheduled', 'smart-manager-for-wp-e-commerce' ),
					);
				case 'type':
					return  array(
						'inline' => __( 'Inline', 'smart-manager-for-wp-e-commerce' ),
						'bulk_edit' => __( 'Bulk Edit', 'smart-manager-for-wp-e-commerce' ),
					);
			}
		}
	}
}
