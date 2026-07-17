<?php

if ( !defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Smart_Manager_Pro_Settings' ) ) {
	class Smart_Manager_Pro_Settings extends Smart_Manager_Settings {

        function __construct() {
			parent::__construct();
        }

		/**
		 * Function to handle hooks.
		 */
		public static function init(){
			add_filter( 'sm_settings_default', array( 'Smart_Manager_Pro_Settings', 'get_pro_defaults' ) );
			add_filter( 'sm_setting_value', array( 'Smart_Manager_Pro_Settings', 'format_setting_value' ), 10, 2 );
			add_action( 'sa_sm_before_settings_update', array( 'Smart_Manager_Pro_Settings', 'before_settings_update' ), 10, 1 );
		}

		/**
		 * Function to add pro default settings
		 *
         * @param array $defaults Default settings array
		 * @return array $defaults Updated default settings array
		 */
		public static function get_pro_defaults( $defaults = array() ){
			if( empty( $defaults['general'] ) ){
				return $defaults;
			}

			if( empty( $defaults['general']['toggle'] ) ){
				$defaults['general']['toggle'] = array();
			}

			$defaults['general']['toggle']['show_tasks_title_modal'] = 'yes';

			if( empty( $defaults['general']['image'] ) ){
				$defaults['general']['image'] = array();
			}

			$defaults['general']['image']['company_logo_for_print_invoice'] = 0;
			$defaults['general']['toggle']['delete_media_when_permanently_deleting_post_type_records'] = 'no';
			$defaults['general']['toggle']['generate_sku'] = 'no';
			$defaults['general']['select']['ai_integration_settings'] = array(
				'cohere'=> array('label'=>'Cohere','api_key'=>''),
			);
			$defaults[ 'general' ][ 'icon' ] = array( 'sync_wsm_stock_log_data' => '' );
			$defaults['general']['toggle']['track_external_product_changes'] = 'yes';
			$defaults[ 'general' ][ 'select' ][ 'auto_delete_edit_history' ] = array(
				'enabled'  => 'no',
				'interval' => 30,
				'unit'     => 'days'
			);			
			return $defaults;
		}

		/**
		 * Function to format value for pro settings
		 *
         * @param string $value Setting value
         * @param array $args Array containing setting meta info.
		 * @return array/string $value updated value
		 */
		public static function format_setting_value( $value = '', $args = array() ){
			if( empty( $value ) || empty( $args['type'] ) || ( ! empty( $args['type'] ) && 'image' !== $args['type'] ) ){
				return $value;	
			}

			if( is_array( $value ) && ! empty( $value['id'] ) ){
				return intval( $value['id'] );
			} else if( !is_array( $value ) ){
				return array( 'id' => intval( $value ),
								'url' => wp_get_attachment_image_url( intval( $value ), 'full' ) );
			}

			return $value;
		}

		/**
		 * Get the number of days configured for auto-deleting edit history
		 *
		 * @return int|false The number of days after which edit history should be deleted,
		 *                   or false if auto-delete is disabled or not configured.
		 */
		public static function get_auto_delete_edit_history_days() {
			$edit_history_setting = ( ( class_exists( 'Smart_Manager_Settings' ) ) && ( is_callable( array( 'Smart_Manager_Settings', 'get' ) ) ) ) ? Smart_Manager_Settings::get('auto_delete_edit_history') : array();
			return ( ! empty( $edit_history_setting ) && is_array( $edit_history_setting ) && ( ! empty(  $edit_history_setting[ 'enabled' ] ) ) && ( 'yes' === $edit_history_setting[ 'enabled' ] ) && ( ! empty( $edit_history_setting[ 'interval' ] ) ) ) ? absint( $edit_history_setting[ 'interval' ] ) : false;
		}

		/**
		 * Handle scheduled action cleanup before settings are updated.
		 *
		 * @param array $settings The new settings array being saved. Expected structure:
		 *                        $settings['general']['select']['auto_delete_edit_history']
		 *                        containing 'enabled' and 'interval' keys.
		 * @return void
		 */
		public static function before_settings_update( $settings = array() ) {
			if ( ( empty( $settings ) ) || ( ! is_array( $settings ) ) || ( empty( $settings[ 'general' ][ 'select' ][ 'auto_delete_edit_history' ] ) ) || ( ! is_array( $settings[ 'general' ][ 'select' ][ 'auto_delete_edit_history' ] ) ) ) {
				return;
			}
			$auto_delete_edit_history_setting = $settings[ 'general' ][ 'select' ][ 'auto_delete_edit_history' ];
			if ( ( empty( $auto_delete_edit_history_setting[ 'enabled' ] ) ) ) {
				return;
			}
			//Remove the prevous scheduled action if settings changed & new action will scheduled by schedule_task_deletion function based on the settings configured.
			if ( ( 'no' === $auto_delete_edit_history_setting[ 'enabled' ] ) || ( ( 'yes' === $auto_delete_edit_history_setting[ 'enabled' ] ) && ( ( ! empty( $auto_delete_edit_history_setting[ 'interval' ] ) ) ) && ( absint( $auto_delete_edit_history_setting[ 'interval' ] ) !== self::get_auto_delete_edit_history_days() ) ) )  {
				//Delete the auto delete edit history scheduled action.
				if ( function_exists( 'as_unschedule_action' ) ) {
					as_unschedule_action( 'sm_schedule_tasks_cleanup' );
				}
			}
		}

	}
}
Smart_Manager_Pro_Settings::init();
