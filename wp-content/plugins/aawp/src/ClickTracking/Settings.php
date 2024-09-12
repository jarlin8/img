<?php

namespace AAWP\ClickTracking;

/**
 * Class Settings. Settings stuffs for the click tracking.
 *
 * @todo:: Refactor because ActivityLogs/Settings.php is also very similar to this.
 *
 * @since 3.20
 */
class Settings {

	/**
	 * Initialize Settings Page.
	 *
	 * @since 3.20
	 */
	public function init() {

		$this->save_settings();
		$this->render_page();
	}

	/**
	 * Get the Clicks Settings.
	 *
	 * @since 3.20
	 *
	 * @return array.
	 */
	public function get_settings() {

		global $wp_roles;

		return apply_filters(
			'aawp_clicks_settings',
			[
				'enable'        => [
					'id'      => 'aawp-clicks-enable',
					'name'    => 'aawp_clicks_enable',
					'type'    => 'checkbox',
					'default' => 'off',
					'label'   => __( 'Enable Click Tracking', 'aawp' ),
					'desc'    => __( 'Check to enable tracking Amazon affiliate links clicks.', 'aawp' ),
				],

				'exclude_roles' => [
					'id'      => 'aawp-clicks-exclude-roles',
					'name'    => 'aawp_clicks_exclude_roles',
					'type'    => 'checkbox-multiple',
					'options' => $wp_roles->get_names(),
					'default' => 'off',
					'label'   => __( 'Exclude User Roles', 'aawp' ),
					'desc'    => __( 'Check the user roles that should be excluded from tracking.', 'aawp' ),
				],

				'country'       => [
					'id'      => 'aawp-clicks-country',
					'name'    => 'aawp_clicks_country',
					'type'    => 'checkbox',
					'default' => 'off',
					'label'   => __( 'Enable Location (Country) Tracking', 'aawp' ),
					'desc'    => __( 'Check to enable tracking the location of the visitor.', 'aawp' ),
				],
			]
		);
	}

	/**
	 * Render Settings.
	 *
	 * @todo Make render settings an abstract - logs settings is also using the same.
	 *
	 * @since 3.20
	 */
	public function render_page() {
		?>	
		<?php do_action( 'aawp_clicks_settings_init' ); ?>
		<div class="aawp-clicks-settings-container">
			<div class="aawp-clicks-settings-settings" style="max-width: 80%">
				<form method="post">
					<table class="form-table">
						<?php foreach ( (array) $this->get_settings() as $key => $settings ) : ?>
						<tr valign="top" class="<?php echo esc_attr( $settings['id'] ); ?>">
							<th scope="row"><label for="<?php echo esc_attr( $settings['id'] ); ?>"><?php echo esc_html( $settings['label'] ); ?></label></th>
								<td>
									<?php
									$saved = get_option( 'aawp_clicks_settings' );
									switch ( $settings['type'] ) {
										case 'checkbox':
											$value = isset( $saved[ $key ] ) ? $saved[ $key ] : $settings['default'];
											echo '<fieldset>';
											?>
											<label for="<?php echo esc_attr( $settings['id'] ); ?>" >
												<input type="checkbox"
													id="<?php echo esc_attr( $settings['id'] ); ?>"
													name="<?php echo esc_attr( $settings['name'] ); ?>"
													<?php checked( $value, 'on', true ); ?>
												/>
												<?php esc_html_e( $settings['desc'] ); ?>
											</label>
											<?php
											echo '</fieldset>';
											break;

										case 'checkbox-multiple':
											echo '<fieldset>';
											foreach ( $settings['options'] as $role_value => $role_name ) {

												$value = isset( $saved[ $key ] ) && is_array( $saved[ $key ] ) ? in_array( $role_value, $saved[ $key ] ) : '';
												?>
												<label for="<?php echo $settings['name'] . '_' . esc_attr( $role_value ); ?>" >
													<input type="checkbox"
														id="<?php echo $settings['name'] . '_' . esc_attr( $role_value ); ?>"
														name="<?php echo esc_attr( $settings['name'] ); ?>[]"
														value="<?php echo esc_attr( $role_value ); ?>"
														<?php checked( $value, true, true ); ?>
													/>
													<?php esc_html_e( $role_name ); ?>
												</label></br/>
												<?php
											}
											echo '</fieldset>';
											echo '<br/>';
											esc_html_e( $settings['desc'] );
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
								</td>
						</tr>
						<?php endforeach; ?>

					</table>
						<?php wp_nonce_field( 'aawp_clicks_settings', 'aawp_clicks_settings_nonce' ); ?>
						<?php submit_button(); ?>
						<?php $this->clear_all(); ?>
				</form>
			</div>

			<?php do_action( 'aawp_clicks_settings_after' ); ?>
		</div>
		<?php
	}

	/**
	 * Save settings to the database.
	 *
	 * @since 3.20
	 *
	 * @return void.
	 */
	public function save_settings() {

		if ( ! isset( $_POST['submit'] ) ) {
			return;
		}

		if (
			! isset( $_POST['aawp_clicks_settings_nonce'] ) ||
			! wp_verify_nonce( sanitize_key( $_POST['aawp_clicks_settings_nonce'] ), 'aawp_clicks_settings' ) // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		) {
			return;
		}

		$save_to_db = [];
		foreach ( $this->get_settings() as $key => $settings ) {

			if ( 'checkbox-multiple' === $settings['type'] ) {
				$save_to_db[ $key ] = ! empty( $_POST[ $settings['name'] ] ) ? array_map( 'sanitize_text_field', $_POST[ $settings['name'] ] ) : '';
			} else {
				$save_to_db[ $key ] = ! empty( $_POST[ $settings['name'] ] ) ? sanitize_text_field( wp_unslash( $_POST[ $settings['name'] ] ) ) : '';
			}
		}

		$option = get_option( 'aawp_clicks_settings', [] );

		$save_to_db = array_merge( $option, $save_to_db );

		update_option( 'aawp_clicks_settings', $save_to_db );

		add_action(
			'aawp_clicks_settings_init',
			static function () {
				?>
				<div style="margin-left: 0px;" class="notice notice-success is-dismissible">
					<p><strong><?php esc_html_e( 'Settings Saved.', 'aawp' ); ?></strong></p>
				</div>
				<?php
			}
		);
	}

	/**
	 * Markup for clear all clicks records.
	 *
	 * @since 3.20
	 */
	private function clear_all() {

		$this->process_clear();

		?>
		<button
			name="clear-all"
			type="submit"
			class="button"
			value="1"
			onclick="return confirm( 'Are you sure you want to clear all clicks?' )"><?php esc_html_e( 'Empty Clicks', 'aawp' ); ?></button>
		<?php
	}

	/**
	 * Clear all clicks records.
	 *
	 * @since 3.20
	 */
	private function process_clear() {
		if (
			isset( $_POST['aawp_clicks_settings_nonce'] )
			&& wp_verify_nonce( sanitize_key( $_POST['aawp_clicks_settings_nonce'] ), 'aawp_clicks_settings' ) // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			&& ! empty( $_REQUEST['clear-all'] )
			&& current_user_can( 'manage_options' )
		) {
			$db = new DB();
			$db->clear_all();

			add_action(
				'aawp_clicks_settings_init',
				static function () {
					?>
					<div style="margin-left: 0px;" class="notice notice-success is-dismissible">
						<p><strong><?php esc_html_e( 'All Clicks Cleared.', 'aawp' ); ?></strong></p>
					</div>
					<?php
				}
			);
		}
	}
}
