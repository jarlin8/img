<?php
/**
 * Common background updater pro class.
 *
 * @package common-pro/
 * @since       8.64.0
 * @version     8.77.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'SA_Manager_Pro_Background_Updater' ) ) {
	/**
	 * Class properties and methods will go here.
	 */
	class SA_Manager_Pro_Background_Updater extends SA_Manager_Background_Updater {

		/**
		 * Variable to hold instance of background updater base class
		 *
		 * @var $instance
		 */
		public $background_updater_base = null;

		/**
		 * Variable to hold instance of this class
		 *
		 * @var $instance
		 */
		protected static $instance = null;

		public static function instance( $plugin_data = array() ) {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self( $plugin_data );
			}
			return self::$instance;
		}

		public function __construct( $plugin_data = array() ) {
			parent::__construct( $plugin_data );
			$this->background_updater_base = SA_Manager_Background_Updater::instance( $plugin_data );
			$plugin_sku = ! empty( $this->plugin_data['plugin_sku'] ) ? $this->plugin_data['plugin_sku'] : $this->plugin_sku;
			add_action( 'storeapps_' . $plugin_sku . '_scheduled_actions', array( $this, 'schedule_bulk_edit_actions' ) );
			add_action( 'action_scheduler_canceled_action', array( $this, 'delete_ids_option_on_cancelled_bulk_edit_scheduled_action' ), 1 );
		}

		public function __call( $function_name, $arguments = array() ) {
			if( empty( $this->background_updater_base ) ) {
				return;
			}

			if ( ! is_callable( array( $this->background_updater_base, $function_name ) ) ) {
				return;
			}

			if ( ! empty( $arguments ) ) {
				return call_user_func_array( array( $this->background_updater_base, $function_name ), $arguments );
			} else {
				return call_user_func( array( $this->background_updater_base, $function_name ) );
			}
		}

		/**
		 * Schedule bulk edit actions
		 *
		 * @param array $args arguments of bulk edit action.
		 * @return void
		 */
		public function schedule_bulk_edit_actions( $args = array() ) {
			if ( empty( $args ) || ! is_array( $args ) || empty( $args['callback']['class_path'] ) || empty( $args['dashboard_key'] ) ) {
				return;
			}
			$class_path           = ( function_exists( 'wc_clean' ) ) ? wc_clean( wp_unslash( $args['callback']['class_path'] ) ) : sanitize_text_field( wp_unslash( $args['callback']['class_path'] ) );
			$supported_post_types = is_callable( 'sa_get_supported_post_types' ) ? sa_get_supported_post_types() : array();
			$supported_classes    = array();
			if ( ( ! empty( $supported_post_types ) ) && is_array( $supported_post_types ) ) {
				$supported_classes = array_merge(
					array(
						'class-sa-manager-pro-background-updater.php',
						'class-sa-manager-pro-base.php',
					),
					array_map(
						function ( $post_type ) {
							return 'class-sa-manager-pro-' . $post_type . '.php';
						},
						$supported_post_types
					)
				);
			}
			if ( ! in_array( $class_path, $supported_classes, true ) ) {
				return false;
			}
			$plugin_sku         = ! empty( $this->plugin_data['plugin_sku'] ) ? $this->plugin_data['plugin_sku'] : '';
			$plugin_folder_flag = ! empty( $this->plugin_data['folder_flag'] ) ? $this->plugin_data['folder_flag'] : '';
			$plugin_folder_flag = ( '/lib' === $this->plugin_data['folder_flag'] ) ? $this->plugin_data['folder_flag'] : '';
			$constant_name      = strtoupper( $plugin_sku ) . '_PLUGIN_DIR_PATH';
			$plugin_dir         = defined( $constant_name ) ? constant( $constant_name ) . $plugin_folder_flag : '';
			include_once $plugin_dir . '/common-core/classes/class-sa-manager-base.php';

			// include all the supported_post_types class files.
			foreach ( $supported_classes as $class ) {
				$file = realpath( dirname( __FILE__ ) . '/' . $class );
				if ( is_file( $file ) ) {
					include_once $file;
				}
			}

			$args['scheduled_for'] = '0000-00-00 00:00:00';
			$is_process_running    = ( ! empty( get_option( $this->identifier . '_remaining', array() ) ) ) ? true : false;
			$obj                   = ( 'SA_Manager_Pro_' . ucfirst( str_replace( '-', '_', $args['dashboard_key'] ) ) )::instance( $this->plugin_data );
			if ( ! $is_process_running && is_callable( array( 'SA_Manager_Pro_Base', 'send_to_background_process' ) ) ) {
				SA_Manager_Pro_Base::send_to_background_process( $args );
			} else {
				$plugin_sku           = ( ( ! empty( $this->plugin_data['plugin_sku'] ) ) ) ? $this->plugin_data['plugin_sku'] : '';
				$rescheduled_interval = apply_filters( 'sa_' . $plugin_sku . '_bulk_edit_action_rescheduled_interval', intval( get_option( 'sa_' . $plugin_sku . 'bulk_edit_action_rescheduled_interval', 30 ) ) );
				as_schedule_single_action( strtotime( gmdate( 'Y-m-d H:i:s', strtotime( '+' . $rescheduled_interval . ' minutes' ) ) ), strtoupper( $plugin_sku ) . '_SCHEDULED_ACTIONS' );
			}
		}

		/**
		 * Delete stored option ids when Bulk Edit scheduled action is cancelled.
		 *
		 * @param int $action_id Action Scheduler action ID.
		 *
		 * @return void
		 */
		public function delete_ids_option_on_cancelled_bulk_edit_scheduled_action( $action_id = 0 ) {
			if ( empty( $action_id ) ) {
				return;
			}

			$store = ActionScheduler::store();
			if ( ! is_callable( array( $store, 'fetch_action' ) ) ) {
				return;
			}

			$action = $store->fetch_action( $action_id );
			if ( empty( $action ) || ! is_callable( array( $action, 'get_hook' ) ) ) {
				return;
			}

			$plugin_sku = ! empty( $this->plugin_data['plugin_sku'] ) ? $this->plugin_data['plugin_sku'] : '';
			if ( 'storeapps_' . $plugin_sku . '_scheduled_actions' !== $action->get_hook() ) {
				return;
			}

			// Fetch action args.
			$args = is_callable( array( $action, 'get_args' ) ) ? $action->get_args() : array();
			if ( empty( $args ) || ( ! is_array( $args ) ) || ( ! is_array( $args[0] ) ) || empty( $args[0]['selected_ids_option_key'] ) ) {
				return;
			}
			// Delete the option from database.
			delete_option( sanitize_key( $args[0]['selected_ids_option_key'] ) );
		}
	}
	SA_Manager_Pro_Background_Updater::instance();
}
