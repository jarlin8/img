<?php

namespace AAWP\Admin\Tools;

/**
 * Scheduled Actions.
 *
 * @since 3.20.0
 */
class ScheduledActions extends \ActionScheduler_ListTable {

    /**
	 * ScheduledActions constructor.
	 *
	 * @since 3.20.0
	 */
	public function __construct() {

		parent::__construct(
			\ActionScheduler::store(),
			\ActionScheduler::logger(),
			\ActionScheduler::runner()
		);

		$this->process_actions();
	}

	/**
	 * Check if ActionScheduler_AdminView class exists.
	 *
	 * @since 3.20.0
	 *
	 * @return bool
	 */
	private function admin_view_exists() {

		return class_exists( 'ActionScheduler_AdminView' );
	}

    /**
     * Render the Scheduled Actions page.
     *
     * @since 3.20.0
     */
    public function render_page() {

		if ( ! $this->admin_view_exists() ) {
			return;
		}

        $this->display_page();
    }

    /**
	 * Display the table heading.
	 *
	 * @since 3.20.0
	 */
	protected function display_header() {

		?>
		<h2><?php echo esc_html__( 'Scheduled Actions', 'aawp' ); ?></h2>

		<p>
			<?php
			echo sprintf(
				wp_kses( /* translators: %s - Action Scheduler website URL. */
					__( 'AAWP is using the <a href="%s" target="_blank" rel="noopener noreferrer">Action Scheduler</a> library, which allows it to queue and process bigger tasks in the background without making your site slower for your visitors. Below you can see the list of all tasks and their status. This table can be very useful when debugging certain issues.', 'aawp' ),
					[
						'a' => [
							'href'   => [],
							'rel'    => [],
							'target' => [],
						],
					]
				),
				'https://actionscheduler.org/'
			);
			?>
		</p>

		<p>
			<?php echo esc_html__( 'Action Scheduler library is also used by other plugins, like WooCommerce, so you might see tasks that are not related to our plugin in the table below.', 'aawp' ); ?>
		</p>

		<?php
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! empty( $_GET['s'] ) ) {
			?>
			<div id="aawp-reset-filter">
				<?php
				echo wp_kses(
					sprintf( /* translators: %s - search term. */
						__( 'Search results for <strong>%s</strong>', 'aawp' ),
						// phpcs:ignore WordPress.Security.NonceVerification.Recommended
						sanitize_text_field( wp_unslash( $_GET['s'] ) )
					),
					[
						'strong' => [],
					]
				);
				?>
				<a href="<?php echo esc_url( remove_query_arg( 's' ) ); ?>">
					<span class="reset dashicons dashicons-dismiss"></span>
				</a>
			</div>
			<?php
		}
	}
}