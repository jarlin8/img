<?php

namespace AAWP\ActivityLogs;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Settings class for Logs.
 *
 * @since 3.19
 */
class Settings {

	/**
	 * Hooks.
	 *
	 * @since 3.18
	 */
	public function hooks() {

		// Should probably be in 'init' hook.
		$this->schedule();
		$this->save_settings();
	}

	/**
	 * Get the AAWP Logs Settings.
	 *
	 * @since 3.19
	 *
	 * @return array.
	 */
	public function get_settings() {
		return apply_filters(
			'aawp_logs_settings',
			[
				'enable'   => [
					'id'      => 'aawp-logs-enable',
					'name'    => 'aawp_logs_enable',
					'type'    => 'checkbox',
					'default' => 'off',
					'label'   => __( 'Enable Logging', 'aawp' ),
					'desc'    => '',
				],

				'log_retention_period' => [
					'id'      => 'aawp-logs-log-retention-period',
					'name'    => 'aawp_logs_log_retention_period',
					'type'    => 'number',
					'default' => '90',
					'label'   => __( 'Log Retention Period (Days)', 'aawp' ),
					'desc'    => __( 'Logs older than the selected period will be permanently deleted from the database.', 'aawp' ),
				],
			]
		);
	}

	/**
	 * Add contents to the settings page.
	 *
	 * @since 3.19
	 */
	public function render_page() { //phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh

		$this->hooks();

		?>	
			<?php do_action( 'aawp_logs_settings_init' ); ?>
			<div class="aawp-logs-settings-container">
				<div class="aawp-logs-settings-settings" style="max-width: 80%">
					<form method="post">
						<table class="form-table">
							<?php foreach ( (array) $this->get_settings() as $key => $settings ) : ?>
							<tr valign="top" class="<?php echo esc_attr( $settings['id'] ); ?>">
								<th scope="row"><label for="<?php echo esc_attr( $settings['id'] ); ?>"><?php echo esc_html( $settings['label'] ); ?></label></th>
									<td>

										<?php
										$saved = get_option( 'aawp_logs_settings' );
										switch ( $settings['type'] ) {
											case 'checkbox':
												$value = isset( $saved[ $key ] ) ? $saved[ $key ] : $settings['default'];

												?>
													<input type="checkbox"
														id="<?php echo esc_attr( $settings['id'] ); ?>"
														name="<?php echo esc_attr( $settings['name'] ); ?>"
														<?php checked( $value, 'on', true ); ?>
													/>
												<?php

												break;

											default:
												?>
													<input type="<?php echo esc_attr( $settings['type'] ); ?>"
														value="<?php echo isset( $saved[ $key ] ) ? esc_attr( $saved[ $key ] ) : esc_attr( $settings['default'] ); ?>"
														id="<?php echo esc_attr( $settings['id'] ); ?>"
														name="<?php echo esc_attr( $settings['name'] ); ?>"
													/>
												<?php
										}//end switch
										?>

										<?php if ( '' !== $settings['desc'] ) : ?>
												<?php $description = '<i class="desc">' . $settings['desc'] . '</i>'; ?>
												<?php echo 'checkbox' !== $settings['type'] ? '<br/><p>' . wp_kses_post( $description ) . '</p>' : wp_kses_post( $description ); ?>
										<?php endif; ?>
									</td>
							</tr>
							<?php endforeach; ?>

						</table>
							<?php wp_nonce_field( 'aawp_logs_settings', 'aawp_logs_settings_nonce' ); ?>
							<?php submit_button(); ?>
					</form>
				</div>

				<?php do_action( 'aawp_logs_settings_after' ); ?>
			</div>
		<?php
	}

	/**
	 * Save settings to the database.
	 *
	 * @since 3.19
	 *
	 * @return void.
	 */
	public function save_settings() {

		if ( ! isset( $_POST['submit'] ) ) {
			return;
		}

		if (
			! isset( $_POST['aawp_logs_settings_nonce'] ) ||
			! wp_verify_nonce( sanitize_key( $_POST['aawp_logs_settings_nonce'] ), 'aawp_logs_settings' ) // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		) {
			return;
		}

		$save_to_db = [];
		foreach ( $this->get_settings() as $key => $settings ) {
			$save_to_db[ $key ] = ! empty( $_POST[ $settings['name'] ] ) ? sanitize_text_field( wp_unslash( $_POST[ $settings['name'] ] ) ) : '';
		}

		update_option( 'aawp_logs_settings', $save_to_db );

		add_action(
			'aawp_logs_settings_init',
			static function () {
				?>
				<div style="margin-left: 0px;" class="notice notice-success amzn-link-shortener-notice is-dismissible">
					<p><strong><?php esc_html_e( 'Settings Saved.', 'link-shortener-amzn' ); ?></strong></p>
				</div>
				<?php
			}
		);
	}

	/**
	 * Setup schedule to delete the old logs as per the retention period set in settings.
	 *
	 * @since 3.19
	 */
	public function schedule() {

		$settings = get_option( 'aawp_logs_settings' );

		if ( empty( $settings['log_retention_period'] ) ) {
			return;
		}

		if ( ! wp_next_scheduled( 'aawp_clear_logs' ) ) {
			wp_schedule_event( time(), 'daily', 'aawp_clear_logs' );
		}
	}
}
