<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'ActionScheduler' ) && file_exists( SM_PLUGIN_DIR_PATH. '/pro/libraries/action-scheduler/action-scheduler.php' ) ) {
	include_once SM_PLUGIN_DIR_PATH. '/pro/libraries/action-scheduler/action-scheduler.php';
}

/**
 * SM_Background_Updater Class.
 */
if ( ! class_exists( 'Smart_Manager_Pro_Background_Updater' ) ) {
	class Smart_Manager_Pro_Background_Updater {

		/**
		 * @var string
		 */
		public static $_prefix = 'wp';

		public static $_action = 'sm_beta_background_update';

		public static $batch_handler_hook = 'storeapps_smart_manager_batch_handler';

		const SM_WP_CRON_SCHEDULE = 'every_5_seconds';

		protected $action = '';

		protected $identifier = '';

		protected static $_instance = null;

		protected $batch_start_time = '';

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public static function get_identifier() {
			return self::$_prefix . '_' . self::$_action;
		}

		/**
		 * Initiate new background process
		 */
		public function __construct() {
			$this->action = self::$_action;
			$this->identifier = self::get_identifier();

			add_action( 'storeapps_smart_manager_batch_handler', array( $this, 'storeapps_smart_manager_batch_handler' ) );
			add_action( 'action_scheduler_failed_action', array( $this, 'restart_failed_action' ) );
			add_action( 'admin_notices', array( $this, 'background_process_notice' ) );
			add_action( 'admin_head', array( $this, 'background_heartbeat' ) );
			add_filter( 'cron_schedules', array( $this, 'cron_schedules' ), 1000 ); // phpcs:ignore 
			add_filter( 'action_scheduler_run_schedule', array( $this, 'modify_action_scheduler_run_schedule' ), 1000 ); // phpcs:ignore 
			add_action( 'wp_ajax_sa_sm_stop_background_process', array( $this, 'stop_background_process' ) );
			add_action( 'sm_schedule_tasks_cleanup', array( &$this, 'schedule_tasks_cleanup_cron' ) ); // For handling deletion of tasks those are more than x number of days.
			add_action( 'storeapps_smart_manager_scheduled_actions', array( &$this, 'schedule_bulk_edit_actions' ) );
			add_action( 'storeapps_smart_manager_scheduled_export_actions', array( &$this, 'scheduled_export_actions' ) );
			add_action( 'storeapps_smart_manager_scheduled_export_cleanup', array( &$this, 'scheduled_exports_cleanup_cron' ) ); // For handling deletion of scheduled export files those are more than x number of days.
		}

		/**
		 * Task
		 *
		 * Override this method to perform any actions required on each
		 * queue item. Return the modified item for further processing
		 * in the next pass through. Or, return false to remove the
		 * item from the queue.
		 *
		 * @param array $callback Update callback function
		 * @return mixed
		 */
		protected function task( $params ) {
			if ( is_callable( array( 'Smart_Manager', 'log' ) ) ) {
				Smart_Manager::log( 'info', _x( 'Background process task params ', 'background process task params', 'smart-manager-for-wp-e-commerce' ) . print_r( $params, true ) );
			}
			if ( !empty($params['callback']) && !empty($params['args']) ) {
				try {
					include_once dirname( __FILE__ ) .'/class-smart-manager-pro-utils.php';
					include_once( SM_PLUGIN_DIR_PATH . '/classes/class-smart-manager-base.php' );
					include_once dirname( __FILE__ ) .'/class-smart-manager-pro-base.php';
					include_once dirname( __FILE__ ) .'/'. $params['callback']['class_path'];
					if( ! class_exists( 'Smart_Manager_Task' ) && file_exists( SM_PLUGIN_DIR_PATH .'/classes/class-smart-manager-task.php' ) ){
						include_once SM_PLUGIN_DIR_PATH .'/classes/class-smart-manager-task.php';
					}
					if( ! class_exists( 'Smart_Manager_Pro_Task' ) && file_exists( dirname( __FILE__ ) .'/class-smart-manager-pro-task.php' ) ){
						include_once dirname( __FILE__ ) .'/class-smart-manager-pro-task.php';
					}

					if( !empty($params['args']) && is_array($params['args']) ) {
						if( !empty($params['args']['dashboard_key']) && file_exists(dirname( __FILE__ ) . '/class-smart-manager-pro-'. str_replace( '_', '-', $params['args']['dashboard_key'] ) .'.php')) {
							include_once dirname( __FILE__ ) . '/class-smart-manager-pro-'. str_replace( '_', '-', $params['args']['dashboard_key'] ) .'.php';
							$class_name = 'Smart_Manager_Pro_'.ucfirst( str_replace( '-', '_', $params['args']['dashboard_key'] ) );
							$obj = $class_name::instance($params['args']['dashboard_key']);
						}
						if( is_callable( array( $params['callback']['func'][0], 'actions' ) ) ) {
							call_user_func(array($params['callback']['func'][0],'actions'));
						}
						call_user_func($params['callback']['func'],$params['args']);
					}	
				} catch ( Exception $e ) {
					if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
						trigger_error( 'Transactional email triggered fatal error for callback ' . $callback['filter'], E_USER_WARNING );
					}
				}
			}
			return false;
		}

		public function background_heartbeat() {
			if ( ( ! is_admin() ) || empty( $_GET['page'] ) || ( ( ! empty( $_GET['page'] ) ) && ( 'smart-manager' !== $_GET['page'] ) ) ) {
                return;
            }

			?>
			<script type="text/javascript">
				var sa_sm_background_process_heartbeat = function(delay = 0, process = '') {
					
					let admin_ajax_url = '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>';
					admin_ajax_url = (admin_ajax_url.indexOf('?') !== -1) ? admin_ajax_url + '&action=sm_beta_include_file' : admin_ajax_url + '?action=sm_beta_include_file';

					// let isBackground = false;

					// if( jQuery('#sa_sm_background_process_progress').length > 0 && jQuery('#sa_sm_background_process_progress').is(":visible") === true ) {
					// 	isBackground = true;
					// }

					var ajaxParams = {
									url: admin_ajax_url,
									method: 'post',
									dataType: 'json',
									data: {
										cmd: 'get_background_progress',
										active_module: 'Background_Updater',
										security: '<?php echo esc_attr( wp_create_nonce( 'smart-manager-security' ) ); ?>',
										pro: true,
									},
									success: function( response ) {
										let isBackground = false;

										if( jQuery('#sa_sm_background_process_progress').length > 0 && jQuery('#sa_sm_background_process_progress').is(":visible") === true ) {
											isBackground = true;
										}

										if( response.ack == 'Success' ) {
											//Code for updating the progressbar

											let per = parseInt(response.per),
												remainingSeconds = response.remaining_seconds;
											if( isBackground ) {
												jQuery('#sa_sm_remaining_time').html(Math.round(parseInt(per)) + '<?php echo esc_html__( '% completed' , 'smart-manager-for-wp-e-commerce' ); ?>');

												let hours = 0,
													minutes = 0,
													seconds = 0;

												hours   = Math.floor(remainingSeconds / 3600);
												remainingSeconds   %= 3600;
												minutes = Math.floor(remainingSeconds / 60);
												seconds = remainingSeconds % 60;

												hours   = hours < 10 ? "0" + hours : hours;
												minutes = minutes < 10 ? "0" + minutes : minutes;
												seconds = seconds < 10 ? "0" + seconds : seconds;

												jQuery('#sa_sm_remaining_time').append(' ['+ hours + ":" + minutes + ":" + seconds + ' left]')

											} else {
												if( jQuery('.sm_beta_background_update_progressbar').html() == 'Initializing...' ) {
													jQuery('.sm_beta_background_update_progressbar').html('');
												}
												jQuery('.sm_beta_background_update_progressbar').progressbar({ value: parseInt(per) }).children('.ui-progressbar-value').css({"background": "#508991", "height":"2.5em", "color":"#FFF"});
												jQuery('.sm_beta_background_update_progressbar_text').html(Math.round(parseInt(per)) + '<?php echo esc_html__( '% Completed' , 'smart-manager-for-wp-e-commerce' ); ?>');
											}


											if( per < 100 ) {
												setTimeout(function(){
													sa_sm_background_process_heartbeat(0, process);
												}, 1000);
											} else {
												if( isBackground ) {
													jQuery('#sa_sm_background_process_progress').fadeOut();
													jQuery('#sa_sm_background_process_complete').fadeIn();
													setTimeout( function() {
														jQuery('#sa_sm_background_process_complete').fadeOut();							            			
													}, 10000);
												} else {
													window.smart_manager.modal = {}
													if(typeof (window.smart_manager.getDefaultRoute) !== "undefined" && typeof (window.smart_manager.getDefaultRoute) === "function"){
														window.smart_manager.showPannelDialog('',window.smart_manager.getDefaultRoute(true))
													}
													
													jQuery('#sa_sm_background_process_complete').fadeIn();
													window.smart_manager.showLoader();
													let processName = process;
													if (processName) {
														processName = _x(processName.replace(/_/g, ' ').replace(/\b\w/g, function(match) {
															return match.toUpperCase();
														}), 'capitalized process name', 'smart-manager-for-wp-e-commerce');
													}
													let noOfRecords = ('undefined' !== typeof( window.smart_manager.selectedRows ) && window.smart_manager.selectedRows && window.smart_manager.selectedRows.length > 0 && window.smart_manager.selectAll === false) ? window.smart_manager.selectedRows.length : (window.smart_manager.selectAll ? _x('All', 'all records', 'smart-manager-for-wp-e-commerce') : 0);
													setTimeout( function() {
														jQuery('#sa_sm_background_process_complete').fadeOut();
														window.smart_manager.notification = {status:'success', message: _x(`${processName} ${_x('for', 'success message', 'smart-manager-for-wp-e-commerce')} ${noOfRecords} ${_x(`${noOfRecords == 1 ? 'record' : 'records'}`, 'success notification', 'smart-manager-for-wp-e-commerce')} ${_x(' completed successfully!', 'success message', 'smart-manager-for-wp-e-commerce')}`, 'success notification', 'smart-manager-for-wp-e-commerce')}
														window.smart_manager.showNotification()
														if(process == 'bulk_edit'){ //code to refresh all the pages for BE
															let p = 1;
															while(p <= window.smart_manager.page){
																window.smart_manager.getData({refreshPage: p});
																p++;
															}
															
															if(window.smart_manager.hot){
																if(window.smart_manager.hot.selection){
																	if(window.smart_manager.hot.selection.highlight){
																		if(window.smart_manager.hot.selection.highlight.selectAll){
																			delete window.smart_manager.hot.selection.highlight.selectAll
																		}
																		window.smart_manager.hot.selection.highlight.selectedRows = []
																	}
																}
															}
															window.smart_manager.hot.render();

															window.smart_manager.selectedRows = [];
															window.smart_manager.selectAll = false;
															window.smart_manager.addRecords_count = 0;
															window.smart_manager.dirtyRowColIds = {};
															window.smart_manager.editedData = {};
															window.smart_manager.updatedEditedData = {};
															window.smart_manager.processContent = '';
															window.smart_manager.updatedTitle = '';
															window.smart_manager.modifiedRows = new Array();
															window.smart_manager.isRefreshingLoadedPage = false;
															window.smart_manager.showLoader(false);
														} else{
															window.smart_manager.refresh();
														}
													}, 1000);
												}
											}
										}
									}

								}

					setTimeout(function(){
						jQuery.ajax(ajaxParams);
					}, delay);
				}
			</script>
			<?php
		}

		/**
		 * Check if batch scheduled action is running
		 *
		 * @return boolean
		 */
		public function is_action_scheduled() {
			$is_scheduled = false;
			if( function_exists( 'as_has_scheduled_action' ) ) {
				$is_scheduled = ( as_has_scheduled_action( self::$batch_handler_hook ) ) ? true : false;
			} else if( function_exists( 'as_next_scheduled_action' ) ) {
				$is_scheduled = ( as_next_scheduled_action( self::$batch_handler_hook ) ) ? true : false;
			}
			return $is_scheduled;
		}

		/**
		 * Stop all scheduled actions by this plugin
		 */
		public function stop_scheduled_actions() {
			if ( function_exists( 'as_unschedule_action' ) ) {
				as_unschedule_action( self::$batch_handler_hook );
			}
			$this->clean_scheduled_action_data(true);
		}

		/**
		 * Stop batch background process via AJAX
		 */
		public function stop_background_process() {
			check_ajax_referer( 'smart-manager-security', 'security' );
			$this->stop_scheduled_actions();
			wp_send_json_success();
		}

		/**
		 * Clean scheduled action data
		 * 
		 * @param  boolean $abort flag whether the process has been forcefully stopped or not.
		 */
		public function clean_scheduled_action_data( $abort = false ) {
			delete_option( $this->identifier.'_start_time' );
			delete_option( $this->identifier.'_current_time' );
			delete_option( $this->identifier.'_tot' );
			delete_option( $this->identifier.'_remaining' );
			delete_option( $this->identifier.'_initial_process' );

			if( ! empty( $abort ) ) {
				delete_option( $this->identifier.'_ids' );
				delete_option( $this->identifier.'_params' );
				delete_option( $this->identifier.'_is_background' );
			}
		}

		/**
		 * Function to display admin notice in case of background process
		 *
		 */
		public function background_process_notice() {

			if ( ! is_admin() ) {
				return;
			}

			if( !( !empty( $_GET['page'] ) && 'smart-manager' === $_GET['page'] ) ) {
				return;
			}

			$initial_process = get_option( $this->identifier.'_initial_process', false );

			if( !empty( $initial_process ) ) {
				if( false === get_option( '_sm_update_42191', false ) ) {
					delete_option( $this->identifier.'_initial_process' );
					update_option( '_sm_update_42191', 1, 'no' );
				}

				$progress = $this->calculate_background_process_progress();
				$percent = ( !empty( $progress['percent_completion'] ) ) ? $progress['percent_completion'] : 0;

				if($percent >= 100){
					return;
				}
			}

			if ( ! $this->is_process_running() && empty( $initial_process ) ) {
				return;
			}

			update_option( $this->identifier.'_is_background', 1, 'no' );

			$batch_params = get_option( $this->identifier.'_params', array() );

			$process_name = ( !empty( $batch_params['process_name'] ) ) ? $batch_params['process_name'] : 'Batch';
			$current_dashboard = ( !empty( $batch_params['active_dashboard'] ) ) ? $batch_params['active_dashboard'] : 'Products';
			$no_of_records = ( ( !empty( $batch_params['entire_store'] ) ) ? __( 'All', 'smart-manager-for-wp-e-commerce' ) : $batch_params['id_count'] ) .' '. esc_html( $current_dashboard ); 
			$admin_email = get_option( 'admin_email', false );
			$admin_email = ( empty( $admin_email ) ) ? 'admin email' : $admin_email;

			?>
			<div id="sa_sm_background_process_progress" class="error" style="display: none;">
				<?php
				if ( empty( $this->is_action_scheduled() ) && empty( $initial_process ) ) {
					$this->clean_scheduled_action_data(true);
					?>
						<p>
						<?php
							/* translators: 1. Error title 2. The bulk process */
							echo sprintf( esc_html__( '%1$s: The %2$s process has stopped. Please review the Smart Manager dashboard to check the status.', 'smart-manager-for-wp-e-commerce' ), '<strong>' . esc_html__( 'Error', 'smart-manager-for-wp-e-commerce' ) . '</strong>', '<strong>' . esc_html( strtolower( $process_name ) ) . '</strong>' );
						?>
						</p>
						<?php
				} else {
					?>
						<p>
							<?php
								echo '<strong>' . esc_html__( 'Important', 'smart-manager-for-wp-e-commerce' ) . '</strong>:';
								echo '&nbsp;' . esc_html( $process_name ) . '&nbsp;'. esc_html__( 'request is running', 'smart-manager-for-wp-e-commerce' ) .'&nbsp;';
								echo esc_html__( 'in the background. You will be notified on', 'smart-manager-for-wp-e-commerce' ) .'&nbsp; <code>'. esc_html( $admin_email ) .'</code>&nbsp; '. esc_html__( 'when it is completed.', 'smart-manager-for-wp-e-commerce' ) . '&nbsp;';
							?>
						</p>
						<p>
								<span id="sa_sm_remaining_time_label">
									<?php echo esc_html__( 'Progress', 'smart-manager-for-wp-e-commerce' ); ?>:&nbsp;
										<strong><span id="sa_sm_remaining_time"><?php echo esc_html__( '--:--:--', 'smart-manager-for-wp-e-commerce' ); ?></span></strong>&nbsp;&nbsp;
										<a id="sa-sm-stop-batch-process" href="javascript:void(0);" style="color: #dc3232;"><?php echo esc_html__( 'Stop', 'woocommerce-smart-coupons' ); ?></a>
								</span>
						</p>
						<p>
							<?php
								echo '<strong>' . esc_html__( 'NOTE', 'smart-manager-for-wp-e-commerce' ) . '</strong>:&nbsp'; 
								echo $batch_params['backgroundProcessRunningMessage']; 
							?>
						</p>
					</div>
					<div id="sa_sm_background_process_complete" class="updated" style="display: none;">
						<p>
							<strong><?php echo esc_html( $process_name ); ?></strong>
							<?php echo esc_html__( 'for', 'smart-manager-for-wp-e-commerce' ). ' <strong>' . esc_html( $no_of_records ) . '</strong> ' .  esc_html__( 'completed successfully', 'smart-manager-for-wp-e-commerce' ) ; ?>
						</p>
					</div>
					<script type="text/javascript">
						sa_sm_background_process_heartbeat(0, '<?php echo esc_html( $process_name ); ?>');

						jQuery('body').on('click', '#sa-sm-stop-batch-process', function(e){
							e.preventDefault();
							<?php /* translators: 1. The bulk process */ ?>
							let admin_ajax_url = '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>';
							admin_ajax_url = (admin_ajax_url.indexOf('?') !== -1) ? admin_ajax_url + '&action=sm_beta_include_file' : admin_ajax_url + '?action=sm_beta_include_file';
							let result = window.confirm('<?php echo sprintf(
								/* translators: %s: process name */
								esc_html__( 'Are you sure you want to stop the %s process? Click OK to stop.', 'smart-manager-for-wp-e-commerce' ), esc_html( $process_name ) ); ?>');
							if (result) {
								jQuery.ajax({
									url     : admin_ajax_url,
									method  : 'post',
									dataType: 'json',
									data    : {
										action  : 'sa_sm_stop_background_process',
										security: '<?php echo esc_attr( wp_create_nonce( 'smart-manager-security' ) ); ?>',
										pro		: true 
									},
									success: function( response ) {
										location.reload();
									}
								});
							}
						});

					</script>
					<?php
				}
				?>
				<script type="text/javascript">
					jQuery('#sa_sm_background_process_progress').fadeIn();
				</script>
			</div>
			<?php
		}

		/**
		 * Calculate progress of background process
		 *
		 * @return array $progress
		 */
		public function calculate_background_process_progress() {
			$progress = array( 'percent_completion' => 0, 'remaining_seconds' => 0 );

			$start_time            = get_option( $this->identifier.'_start_time', false );
			$current_time            = get_option( $this->identifier.'_current_time', false );
			$all_tasks_count       = get_option( $this->identifier.'_tot', false );
			$remaining_tasks_count = get_option( $this->identifier.'_remaining', false );

			if( empty( $start_time ) && empty( $current_time ) && empty( $all_tasks_count ) && empty( $remaining_tasks_count ) ) {
				$progress = array( 'percent_completion' => 100, 'remaining_seconds' => 0 );
			} else {
				$percent_completion = floatval( 0 );
				if ( false !== $all_tasks_count && false !== $remaining_tasks_count ) {
					$percent_completion             = ( ( intval( $all_tasks_count ) - intval( $remaining_tasks_count ) ) * 100 ) / intval( $all_tasks_count );
					$progress['percent_completion'] = floatval( $percent_completion );
				}

				if ( $percent_completion > 0 && false !== $start_time && false !== $current_time ) {
					$time_taken_in_seconds         = intval($current_time) - intval($start_time);
					$time_remaining_in_seconds     = ( $time_taken_in_seconds / $percent_completion ) * ( 100 - $percent_completion );
					$progress['remaining_seconds'] = ceil( $time_remaining_in_seconds );

				}

				if( $progress['percent_completion'] >= 100 ) { //on process completion
					$this->clean_scheduled_action_data();
				}
			}
			
			return $progress;
		}

		/**
		 * Get background process progress via ajax
		 */
		public function get_background_progress() {

			$response = array();

			$progress = $this->calculate_background_process_progress();

			$percent = ( !empty( $progress['percent_completion'] ) ) ? $progress['percent_completion'] : 0;
			$remaining_seconds = ( !empty( $progress['remaining_seconds'] ) ) ? $progress['remaining_seconds'] : 0;
			$response = array( 'ack' => 'Success', 'per' => $percent, 'remaining_seconds' => $remaining_seconds );

			wp_send_json( $response );
		}

		/**
		 * Initiate Batch Process
		 *
		 * initiate batch process and pass control to batch_handler function
		 */
		public function initiate_batch_process() {

			$update_ids = get_option( $this->identifier.'_ids', array() );

			if( !empty( $update_ids ) ) {
				update_option( $this->identifier.'_tot', count( $update_ids ), 'no' );
				update_option( $this->identifier.'_remaining', count( $update_ids ), 'no' );
				update_option( $this->identifier.'_start_time', time(), 'no' );
				update_option( $this->identifier.'_current_time', time(), 'no' );
				update_option( $this->identifier.'_initial_process', 1, 'no' );

				as_unschedule_action( self::$batch_handler_hook );

				if( is_callable( array( $this, 'storeapps_smart_manager_batch_handler' ) ) ) {
					$this->storeapps_smart_manager_batch_handler();
				}
			}
		}

		/**
		 * Batch Handler
		 *
		 * Pass each queue item to the task handler, while remaining
		 * within server memory and time limit constraints.
		 */
		public function storeapps_smart_manager_batch_handler() {
			$batch_params = get_option( $this->identifier.'_params', array() );
			$update_ids = get_option( $this->identifier.'_ids', array() );
			if ( is_callable( array( 'Smart_Manager', 'log' ) ) ) {
				Smart_Manager::log( 'info', _x( 'Batch handler params ', 'batch handler params', 'smart-manager-for-wp-e-commerce' ) . print_r( $batch_params, true ) );
				Smart_Manager::log( 'info', _x( 'Batch handler update ids ', 'batch handler update ids', 'smart-manager-for-wp-e-commerce' ) . print_r( $update_ids, true ) );
			}
			if ( empty( $batch_params ) || empty( $update_ids ) || ( ! is_array( $batch_params ) ) || ( ! is_array( $update_ids ) ) || empty( $batch_params['process_name'] ) || empty( $batch_params['process_key'] ) ) {
				return;
			}
			$start_time = get_option( $this->identifier.'_start_time', false );
			if ( empty( $start_time ) ) {
				update_option( $this->identifier.'_start_time', time(), 'no' );
			}
			$this->batch_start_time = time();
			$batch_complete = false;
			$update_remaining_count = get_option( $this->identifier.'_remaining', false );
			$update_tot_count = get_option( $this->identifier.'_tot', false );
			if ( ! in_array( $batch_params['process_key'], array( 'duplicate_records', 'delete_non_post_type_records' ) ) ) {
				$this->batch_process(
					array(
						'update_ids' => $update_ids,
						'update_remaining_count' => ( ! empty( $update_remaining_count ) ) ? $update_remaining_count : 0,
						'batch_params' => $batch_params
					)
				);
				return;
			}
			// Code for duplicate functionality
			foreach ( $update_ids as $key => $update_id ) {
 				$args = array( 'dashboard_key' => $batch_params['dashboard_key'], 'id' => $update_id );
				$args = ( ! empty( $batch_params['callback_params'] ) && is_array( $batch_params['callback_params'] ) ) ? array_merge( $args, $batch_params['callback_params'] ) : $args;
				$this->task( array( 'callback' => $batch_params['callback'], 'args' => $args ) );
				update_option( $this->identifier.'_current_time', time(), 'no' );
				$batch_complete = $this->sm_batch_process_log( $batch_params );
				//Code for post update
				$update_remaining_count = $update_remaining_count - 1;
				update_option( $this->identifier.'_remaining', $update_remaining_count, 'no' );
				if ( 0 === $update_remaining_count ) { // Code for handling when the batch has completed.
					do_action( 'sm_background_process_complete', $this->identifier ); // For triggering task deletion after successfully completing undo task/deleting task.
					delete_option( $this->identifier.'_ids' );
					( ! empty( get_option( $this->identifier.'_is_background', false ) ) ) ? $this->complete() : delete_option( $this->identifier.'_params' );
					delete_option( $this->identifier.'_is_background' );
				} elseif ( ! empty( $batch_complete ) ) { //Code for continuing the batch
					$update_ids = array_slice( $update_ids, $key+1 );
					update_option( $this->identifier.'_remaining', $update_remaining_count, 'no' );
					update_option( $this->identifier.'_ids', $update_ids, 'no' );
					if ( function_exists( 'as_schedule_single_action' ) ) {
						as_schedule_single_action( time(), self::$batch_handler_hook );
					}
					break;
				}
			}
		}

		/**
		 * Memory exceeded
		 *
		 * Ensures the batch process never exceeds 90%
		 * of the maximum WordPress memory.
		 *
		 * @return bool
		 */
		protected function memory_exceeded() {
			$memory_limit   = $this->get_memory_limit() * 0.9; // 90% of max memory
			$current_memory = memory_get_usage( true );

			if ( $current_memory >= $memory_limit ) {
				return true;
			}

			return false;
		}

		/**
		 * Get memory limit.
		 *
		 * @return int
		 */
		protected function get_memory_limit() {
			if ( function_exists( 'ini_get' ) ) {
				$memory_limit = ini_get( 'memory_limit' );
			} else {
				// Sensible default.
				$memory_limit = '128M';
			}

			if ( ! $memory_limit || -1 === intval( $memory_limit ) ) {
				// Unlimited, set to 32GB.
				$memory_limit = '32G';
			}

			return wp_convert_hr_to_bytes( $memory_limit );
		}

		/**
		 * Time exceeded.
		 *
		 * Ensures the batch never exceeds a sensible time limit.
		 * A timeout limit of 30s is common on shared hosting.
		 *
		 * @return bool
		 */
		protected function time_exceeded() {

			if( empty( $this->batch_start_time ) ) {
				$return = false;
			}

			$finish = $this->batch_start_time + apply_filters( $this->identifier . '_batch_default_time_limit', 20 ); // 20 seconds
			$return = false;

			if ( time() >= $finish ) {
				$return = true;
			}

			return apply_filters( $this->identifier . '_batch_time_exceeded', $return );
		}

		public function complete() {
			Smart_Manager_Pro_Base::batch_process_complete();
		}

		/**
		 * Checks if background process is running
		 *
		 * @return bool  $is_process_running
		 */
		public function is_process_running() {
			$batch_params = get_option( $this->identifier.'_params', array() );
			return ( ! empty( $batch_params ) ) ? true : false;
		}

		/**
		 * Restart scheduler after one minute if it fails
		 *
		 * @param  array $action_id id of failed action.
		 */
		public function restart_failed_action( $action_id ) {

			if ( ! class_exists( 'ActionScheduler' ) || ! is_callable( array( 'ActionScheduler', 'store' ) ) || ! function_exists( 'as_schedule_single_action' ) ) {
				return;
			}

			$action      = ActionScheduler::store()->fetch_action( $action_id );
			$action_hook = $action->get_hook();

			if ( self::$batch_handler_hook === $action_hook ) {
				as_schedule_single_action( time() + MINUTE_IN_SECONDS, self::$batch_handler_hook );
			}
		}

		/**
		 * Function to modify the action sceduler run schedule
		 *
		 * @param string $wp_cron_schedule schedule interval key.
		 * @return string $wp_cron_schedule
		 */
		public function modify_action_scheduler_run_schedule( $wp_cron_schedule ) {
			return self::SM_WP_CRON_SCHEDULE;
		}

		/**
		 * Function to add entry to cron_schedules
		 *
		 * @param array $schedules schedules with interval and display.
		 * @return array $schedules
		 */
		public function cron_schedules( $schedules ) {

			$schedules[self::SM_WP_CRON_SCHEDULE] = array(
				'interval' => 5,
				'display'  => __( 'Every 5 Seconds', 'smart-manager-for-wp-e-commerce' ),
			);

			return $schedules;
		}
		/**
		 * Delete tasks from tasks table those are more than x number of days
		 * 
		 * @return void
		 */
		public function schedule_tasks_cleanup_cron() {
			$tasks_cleanup_interval_days = get_option( 'sa_sm_tasks_cleanup_interval_days' );
			if ( empty( $tasks_cleanup_interval_days ) ) {
				return;
			}
			include_once( SM_PLUGIN_DIR_PATH . '/classes/class-smart-manager-base.php' );
			include_once dirname( __FILE__ ) . '/class-smart-manager-pro-base.php';
			include_once dirname( __FILE__ ) . '/class-smart-manager-pro-task.php';
			if ( is_callable( array( 'Smart_Manager_Pro_Task', 'delete_tasks' ) ) && is_callable( array( 'Smart_Manager_Pro_Task', 'get_task_ids' ) ) ) {
				Smart_Manager_Pro_Task::delete_tasks( Smart_Manager_Pro_Task::get_task_ids( date( 'Y-m-d H:i:s', strtotime( "-" . $tasks_cleanup_interval_days . " Days" ) ) ) );	
			}
		    if ( is_callable( array( 'Smart_Manager_Pro_Task', 'schedule_task_deletion' ) ) ) {
				Smart_Manager_Pro_Task::schedule_task_deletion();
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
			$file_paths = array( 
				SM_PLUGIN_DIR_PATH . '/classes/class-smart-manager-base.php',
				dirname( __FILE__ ) . '/class-smart-manager-pro-base.php',
				dirname( __FILE__ ) .'/'. $args['callback']['class_path'],
				SM_PLUGIN_DIR_PATH . '/classes/class-smart-manager-task.php'
		 	);
			foreach ( $file_paths as $file_path ) {
				if ( file_exists( $file_path ) ) {
					include_once $file_path;
				}
			}
			$args['scheduled_for'] = '0000-00-00 00:00:00';
			$is_process_running = ( ! empty( get_option( $this->identifier.'_params', array() ) ) ) ? true : false;
			$obj = ( 'Smart_Manager_Pro_' . ucfirst( str_replace( '-', '_', $args['dashboard_key'] ) ) )::instance( $args['dashboard_key'] );
			if ( ! $is_process_running && is_callable( array( 'Smart_Manager_Pro_Base', 'send_to_background_process' ) ) ) {
				Smart_Manager_Pro_Base::send_to_background_process( $args );	
			} else {
				$rescheduled_interval = apply_filters( 'sa_sm_bulk_edit_action_rescheduled_interval', intval( get_option( 'sa_sm_bulk_edit_action_rescheduled_interval', 30 ) ) );
				as_schedule_single_action( strtotime( date( 'Y-m-d H:i:s', strtotime( "+" . $rescheduled_interval . " minutes" ) ) ), 'storeapps_smart_manager_scheduled_actions' );
			}
		}

		/**
		 * Process the deletion/bulk edit/undo of records.
		 *
		 * @param array $params Required params array
		*/
		public function batch_process( $params = array() ) {
			if ( empty( $params )|| ( ! is_array( $params ) ) || empty( $params['update_ids'] ) || empty( $params['update_remaining_count'] ) || empty( $params['batch_params'] ) ) {
				return;
			}
			$remaining_ids = $params['update_ids']; // Initially remaining ids are equal to total ids.
			$update_remaining_count = $params['update_remaining_count'];
			$batch_params = $params['batch_params'];
			$batch_complete = false;
			// Get a slice of 100 records from $remaining_ids
			$batch_size = get_option( 'sa_sm_batch_size', 100 );
			// Process the current batch of IDs.				
			while ( ( is_array( $remaining_ids ) ) && ( 0 !== $update_remaining_count ) ) {
				$batch_ids_to_process =  ( ! empty( $remaining_ids ) ) ? array_slice( $remaining_ids, 0, $batch_size ) : array();
				if ( empty( $batch_ids_to_process ) || ( ! is_array( $batch_ids_to_process ) ) ) {
					break;
				}
				// Call your task with the current batch
				$this->task(
					array(
						'callback' => $batch_params['callback'],
						'args' => array(
							'batch_params' => $batch_params,
							'selected_ids' => $batch_ids_to_process,
							'dashboard_key' => $batch_params['dashboard_key']
						)
					)
				);
				update_option( $this->identifier . '_current_time', time(), 'no' );
				$batch_complete = $this->sm_batch_process_log( $batch_params );
				// Check if we've processed all records.
				$remaining_ids = array_slice( $remaining_ids, $batch_size );
				$update_remaining_count = count( $remaining_ids );
				if ( 0 === $update_remaining_count ) {
					// All records processed.
					do_action( 'sm_background_process_complete', $this->identifier );
					delete_option($this->identifier . '_ids');
					( ! empty( get_option( $this->identifier . '_is_background', false ) ) ) ? $this->complete() : delete_option( $this->identifier . '_params' );
					delete_option($this->identifier . '_is_background');
					delete_option( $this->identifier.'_start_time' );
					delete_option( $this->identifier.'_current_time' );
					delete_option( $this->identifier.'_tot' );
					delete_option( $this->identifier.'_remaining' );
					break;
				} elseif ( ! empty( $batch_complete ) ) { // Code for continuing the batch.
					update_option( $this->identifier . '_remaining', $update_remaining_count, 'no' );
					update_option( $this->identifier . '_ids', $remaining_ids, 'no' ); //remaining ids to update.
					// Schedule the next batch.
					if ( function_exists( 'as_schedule_single_action' ) ) {
						as_schedule_single_action( time(), self::$batch_handler_hook );
					}
					break;
				}
			}
		}

		/**
		 * Logs the batch process status and checks for time or memory exceedance and set the $batch_complete to true.
		 *
		 *
		 * @param array $batch_params The parameters for the batch process, including 'process_name'.
		 * 
		 * @return boolean true if time or memory exceeds else false
		 */
		public function sm_batch_process_log( $batch_params = array() ) {
			if ( empty( $batch_params ) || ( ! is_array( $batch_params ) ) ) {
				return;
			}
			if ( $this->time_exceeded() || $this->memory_exceeded() ) { // Code for continuing the batch
				if ( is_callable( array( 'Smart_Manager', 'log' ) ) && ( ! empty( $batch_params['process_name'] ) ) ) {
					if ( $this->time_exceeded() ) {
						/* translators: %s: process name */
						Smart_Manager::log( 'notice', sprintf( _x('Time is exceeded for %s', 'batch handler time exceed status', 'smart-manager-for-wp-e-commerce' ), $batch_params['process_name'] ) );
					}
					if ( $this->memory_exceeded() ) {
						/* translators: %s: process name */
						Smart_Manager::log( 'notice', sprintf( _x( 'Memory is exceeded for %s', 'batch handler memory exceed status', 'smart-manager-for-wp-e-commerce' ), $batch_params['process_name'] ) );
					}
				}
				$initial_process = get_option( $this->identifier . '_initial_process', false );
				if ( ! empty( $initial_process ) ) {
					delete_option( $this->identifier . '_initial_process' );
				}
				return true;
			}
			return false;
		}

		/**
		 * Schedule export actions
		 * 
		 * @param array $args arguments for schedule export action.
		 * @return void
		 */
		public function scheduled_export_actions( $args = array() ) {
			if ( empty( $args ) || ! is_array( $args ) || empty( $args['class_path'] ) || empty( $args['dashboard_key'] ) || empty( $args['scheduled_export_params'] ) || empty( $args['scheduled_export_params']['schedule_export_interval'] ) ) {
				return;
			}
			$file_paths = array( 
				SM_PLUGIN_DIR_PATH . '/classes/class-smart-manager-base.php',
				dirname( __FILE__ ) . '/class-smart-manager-pro-base.php',
				dirname( __FILE__ ) .'/'. $args['class_path']
			);
			foreach ( $file_paths as $file_path ) {
				if ( file_exists( $file_path ) ) {
					include_once $file_path;
				}
			}
			// Validate if the class exists and method is callable before proceeding.
			if ( ! class_exists( $args['class_nm'] ) || ! is_callable( array( $args['class_nm'], 'instance' ) ) || ! is_callable( array( 'Smart_Manager_Pro_Base', 'get_scheduled_exports_advanced_search_query' ) ) ) {
				return;
			}
			$table_data = ( empty( Smart_Manager::$sm_is_wc_hpos_tables_exists ) ) ? array( 'table_nm' => 'posts', 'status_col' => 'post_status', 'date_col' => 'post_date' ) : ( ! empty( $args['table_model']['wc_orders'] ) ? array( 'table_nm' => 'wc_orders', 'status_col' => 'status', 'date_col' => 'date_created_gmt' ) : array() );
			if ( empty( $table_data ) ) {
				return;
			}
			// Get the advanced search query.
			$args['advanced_search_query'] = Smart_Manager_Pro_Base::get_scheduled_exports_advanced_search_query(
				array(
					'interval_days'  => absint( $args['scheduled_export_params']['schedule_export_interval'] ),
					'order_statuses' => ( ! empty( $args['scheduled_export_params']['schedule_export_order_statuses'] ) ) ? $args['scheduled_export_params']['schedule_export_order_statuses'] : array(),
					'table_nm' => ( ! empty( $table_data['table_nm'] ) ) ? sanitize_key( $table_data['table_nm'] ) : '',
					'status_col' => ( ! empty( $table_data['status_col'] ) ) ? sanitize_key( $table_data['status_col'] ) : '',
					'date_col' => ( ! empty( $table_data['date_col'] ) ) ? sanitize_key( $table_data['date_col'] ) : ''
				)
			);
			// Get class instance safely.
			$class_instance = call_user_func( array( $args['class_nm'], 'instance' ), $args['dashboard_key'] );
			if ( ( ! is_object( $class_instance ) ) || ( ! is_callable( array( $class_instance, 'get_export_csv' ) ) ) ) {
				return;
			}
			$class_instance->get_export_csv( $args ); //convert to static function.
		}

		/**
		 * Deletes scheduled export attachments older than a specified number of days.
		 *
		 * The default expiration is 30 days, but this can be overridden using the
		 * 'sm_scheduled_export_file_expiration_days' filter.
		 * @return void
		*/
		public function scheduled_exports_cleanup_cron() {
			$expiration_days = absint( get_option( 'sa_sm_scheduled_export_file_expiration_days' ) );
			if ( empty( $expiration_days ) ) {
				return;
			}
			global $wpdb;
			// Calculate expiration date.
			$expiration_date = gmdate( 'Y-m-d', time() - ( $expiration_days * DAY_IN_SECONDS ) ) . ' 00:00:00';
			// Prepare query to get expired attachment IDs with meta key.
			$attachment_ids = $wpdb->get_col(
				$wpdb->prepare(
					"SELECT p.ID 
					FROM {$wpdb->postmeta} pm 
					JOIN {$wpdb->posts} p ON p.ID = pm.post_id 
					WHERE pm.meta_key = %s 
					  AND pm.meta_value = %s 
					  AND p.post_type = %s 
					  AND p.post_status = %s 
					  AND p.post_date < %s
					",
					'sa_sm_is_scheduled_export_file',
					'1',
					'attachment',
					'inherit',
					$expiration_date
				)
			);
			if ( ( is_wp_error( $attachment_ids ) ) || ( empty( $attachment_ids ) ) || ( ! is_array( $attachment_ids ) ) ) {
				return;
			}
			// Loop and delete each attachment permanently.
			foreach ( $attachment_ids as $attachment_id ) {
				if ( empty( $attachment_id ) ) {
					continue;
				}
				wp_delete_attachment( (int) $attachment_id, true );
			}
		}
	}
}

Smart_Manager_Pro_Background_Updater::instance();
