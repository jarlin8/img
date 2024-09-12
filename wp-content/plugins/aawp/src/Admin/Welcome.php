<?php

namespace AAWP\Admin;

/**
 * The Welcome Class.
 *
 * @since 3.19
 */
class Welcome {

	/**
	 * Initialize.
	 */
	public function init() {
		add_action( 'admin_init', [ $this, 'redirect' ] );
		add_action( 'aawp_admin_menu', [ $this, 'add_welcome_page' ], 10 );
		add_action( 'admin_print_scripts', [ $this, 'remove_notices' ] );
	}

	/**
	 * Redirect to the welcome page by checking the transient set during activation.
	 *
	 * @since 3.19
	 */
	public function redirect() {

		if ( ! get_transient( '_transient_aawp_welcome_screen_activation_redirect' ) ) {
			return;
		}

		// Now delete the transient.
		delete_transient( '_transient_aawp_welcome_screen_activation_redirect' );

		// Bail if activating from network, or bulk.
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}

		wp_safe_redirect( add_query_arg( [ 'page' => 'aawp-welcome' ], admin_url( 'admin.php' ) ) );
		exit();
	}

	/**
	 * The welcome submenu under AAWP Menu.
	 *
	 * @param string $menu_slug AAWP Menu Slug (aawp-welcome).
	 *
	 * @since 3.19
	 */
	public function add_welcome_page( $menu_slug ) {
		add_submenu_page(
			$menu_slug,
			esc_html__( 'AAWP - Welcome', 'aawp' ),
			esc_html__( 'Get Started', 'aawp' ),
			'edit_pages',
			'aawp-welcome',
			[ $this, 'content' ]
		);
	}

	/**
	 * Remove admin notices from the welcome page.
	 *
	 * @since 3.19
	 */
	public function remove_notices() { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh

		global $wp_filter;

		if ( ! isset( $_REQUEST['page'] ) || 'aawp-welcome' !== $_REQUEST['page'] ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}

		foreach ( [ 'user_admin_notices', 'admin_notices', 'all_admin_notices' ] as $wp_notice ) {
			if ( ! empty( $wp_filter[ $wp_notice ]->callbacks ) && is_array( $wp_filter[ $wp_notice ]->callbacks ) ) {
				foreach ( $wp_filter[ $wp_notice ]->callbacks as $priority => $hooks ) {
					foreach ( $hooks as $name => $arr ) {
						unset( $wp_filter[ $wp_notice ]->callbacks[ $priority ][ $name ] );
					}
				}
			}
		}
	}

	/**
	 * The welcome page content.
	 *
	 * @since 3.19.
	 */
	public function content() {

		ob_start();

		?>
			<div class="aawp-welcome-wrap-section-1">
				<div class="intro">
					<img src="<?php echo esc_url( AAWP_PLUGIN_URL . 'assets/img/aawp-banner.svg' ); ?> " >
					<h1> <?php echo esc_html__( 'Best WordPress Plugin for Amazon Affiliates', 'aawp' ); ?> </h1>
					<h3> 
						<?php echo esc_html__( 'Increase the value of your affiliate page and your earned commissions!', 'aawp' ); ?>
					</h3>
				</div>
				<div class="get-started">
					<p class="headline"> <?php echo esc_html__( 'How to Get Started', 'aawp' ); ?> </p>
					<div class="get-started-items">
						<div class="item">
								<img src="<?php echo esc_url( AAWP_PLUGIN_URL . 'assets/img/license-key.svg' ); ?> " >
							<p><?php echo esc_html__( 'Enter the license key in the plugin settings that you received when you purchased AAWP.', 'aawp' ); ?></p>
						</div>
						<div class="item">
								<img src="<?php echo esc_url( AAWP_PLUGIN_URL . 'assets/img/api-key.svg' ); ?> " >
							<p>
							<?php
							echo sprintf(
								wp_kses(
									/* translators: %s - docs link. */
									__( 'Enter your Amazon API Key. If you have questions about this, read our <a href="%s" target="_blank" rel="noopener noreferer">documentation</a>.', 'aawp' ),
									[
										'a' => [
											'href'   => [],
											'rel'    => [],
											'target' => [],
										],
									]
								),
								aawp_is_lang_de() ? 'https://aawp.de/docs/article/amazon-product-advertising-api-zugangsdaten/' : 'https://getaawp.com/docs/article/amazon-product-advertising-api-credentials/' //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							);
							?>
							</p>
						</div>
						<div class="item">
								<img src="<?php echo esc_url( AAWP_PLUGIN_URL . 'assets/img/products.svg' ); ?> " >
							<p><?php echo esc_html__( 'Choose the products you want to display on your website and place them anywhere you like!', 'aawp' ); ?></p>

						</div>
					</div>
				</div>

				<div class="power">
					<h1> <?php echo esc_html__( 'The Highlights of AAWP', 'aawp' ); ?> </h1>
					<h3 class="desc"> 
						<?php echo esc_html__( 'Here\'s a selection of the most popular features of our plugin.', 'aawp' ); ?>
					</h3>

					<div class="gutenberg">
						<div class="block">
							<h1> <?php echo esc_html__( 'Gutenberg Block', 'aawp' ); ?> </h1>
							<h3> 
								<?php echo esc_html__( 'Place products easily and conveniently via our Interactive Gutenberg Block.', 'aawp' ); ?>
							</h3>
							<p class="link"><a href="<?php echo aawp_is_lang_de() ? 'https://aawp.de/docs/article/gutenberg-block/' : 'https://getaawp.com/docs/article/gutenberg-block/'; ?>" target="_blank" rel="noopener noreferer"><?php echo esc_html__( 'See how it works', 'aawp' ); ?></a></p>
						</div>

						<div class="block">
								<img src="<?php echo esc_url( AAWP_PLUGIN_URL . 'assets/img/gutenberg-block.svg' ); ?> " >
						</div>
					</div>

					<div class="features">
						<div class="feature">
							<img src="<?php echo esc_url( AAWP_PLUGIN_URL . 'assets/img/product-box-3.svg' ); ?> " >
							<h3> <?php echo esc_html__( 'Product Box', 'aawp' ); ?> </h3>
							<p class="description"> <?php echo esc_html__( 'Promote specific products with visually appealing and conversion optimized product boxes.', 'aawp' ); ?> </p>
							<p class="link"><a href="<?php echo aawp_get_page_url( 'docs:box' ); ?>" target="_blank" rel="noopener noreferer"><?php echo esc_html__( 'See how it works', 'aawp' ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a></p>
						</div>
						<div class="feature">
							<img src="<?php echo esc_url( AAWP_PLUGIN_URL . 'assets/img/bestseller-list.svg' ); ?> " >
							<h3> <?php echo esc_html__( 'Bestseller Lists', 'aawp' ); ?> </h3>
							<p class="description"> <?php echo esc_html__( 'Create automated bestseller lists & set the number of products individually: e.g. Top 3, Top 10 etc.', 'aawp' ); ?> </p>
							<p class="link"><a href="<?php echo aawp_get_page_url( 'docs:bestseller' ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" target="_blank" rel="noopener noreferer"><?php echo esc_html__( 'See how it works', 'aawp' ); ?></a></p>
						</div>
						<div class="feature">
							<img src="<?php echo esc_url( AAWP_PLUGIN_URL . 'assets/img/product-comparison.svg' ); ?> " >
							<h3> <?php echo esc_html__( 'Comparison Tables', 'aawp' ); ?> </h3>
							<p class="description"> <?php echo esc_html__( 'Compare multiple products with each other by creating a comparison table with our handy table builder.', 'aawp' ); ?> </p>
							<p class="link"><a href="<?php echo aawp_get_page_url( 'docs:comparison_tables' ); ?>" target="_blank" rel="noopener noreferer"><?php echo esc_html__( 'See how it works', 'aawp' ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a></p>
						</div>
						<div class="customizable">
							<h1> <?php echo esc_html__( 'Increase Conversion and Click-through rates (CTR)', 'aawp' ); ?> </h1>
							<p class="description"> <?php echo esc_html__( 'Our product displays are designed to catch the visitor\'s attention and uses familiar patterns that get the visitor to click & buy!', 'aawp' ); ?> </p>
						</div>
					</div>

					<div class="all-features">
						<p class="link"><a href="<?php echo aawp_is_lang_de() ? 'https://aawp.de/funktionen/' : 'https://getaawp.com/features/'; ?>" target="_blank" rel="noopener noreferer"><?php echo esc_html__( 'See all powerful Features of AAWP', 'aawp' ); ?></a></p>
					</div>

				</div>
			</div>

			<div class="aawp-welcome-wrap-section-2">
				<div class="customer-support-heading">
					<h1> <?php echo esc_html__( 'First Class Customer Support', 'aawp' ); ?> </h1>
					<h3> <?php echo esc_html__( 'You have problems or questions? Contact us! We are always happy to help!', 'aawp' ); ?> </h3>
				</div>

				<div class="customer-support-container">
					<div class="resources">
						<div class="resource">

							<div class="heading">
								<img src="<?php echo esc_url( AAWP_PLUGIN_URL . 'assets/img/beginners-guide.svg' ); ?> " >
								<h3> <?php echo esc_html__( 'Beginners Guide', 'aawp' ); ?> </h3>
							</div>
							<p class="description"> <?php echo esc_html__( 'For all newcomers, we have created a detailed guide, which should make your start with AAWP as easy as possible.', 'aawp' ); ?> </p>
							<p class="link"><a href="<?php echo aawp_is_lang_de() ? 'https://aawp.de/docs/article/guide/' : 'https://getaawp.com/docs/article/guide/'; ?>" target="_blank" rel="noopener noreferer"><?php echo esc_html__( 'Read Beginners Guide', 'aawp' ); ?></a></p>

						</div>
						<div class="resource">
							<div class="heading">
								<img src="<?php echo esc_url( AAWP_PLUGIN_URL . 'assets/img/documentation.svg' ); ?> " >
								<h3> <?php echo esc_html__( 'Read Documentation', 'aawp' ); ?> </h3>
							</div>
							<p class="description"> <?php echo esc_html__( 'Detailed instructions on how to install, configure and use our plugin, you can find in our documentation.', 'aawp' ); ?> </p>
							<p class="link"><a href="<?php echo aawp_is_lang_de() ? 'https://aawp.de/docs/' : 'https://getaawp.com/docs/'; ?>" target="_blank" rel="noopener noreferer"><?php echo esc_html__( 'Read Documentation', 'aawp' ); ?></a></p>
						</div>
					</div>
					<div class="questions">
						<h3> <?php echo esc_html__( 'Do you need assistance?', 'aawp' ); ?> </h3>
						<p class="description"> <?php echo esc_html__( 'We know that sometimes it can be overwhelming to learn a new subject. That\'s why we want to make it as easy as possible for you to get started.', 'aawp' ); ?><br/><br/>
												<?php echo esc_html__( 'You\'ll find many resources in our knowledge base, and if your questions can\'t be answered there, we have a friendly and dedicated support team that you can communicate with directly. Do not hesitate! We are always here for you and happy to help you with your affiliate website!', 'aawp' ); ?> 
						</p>
						<hr/>
						<div class="details">
							<p class="documentation"><a href="<?php echo aawp_is_lang_de() ? 'https://aawp.de/docs/' : 'https://getaawp.com/docs/'; ?>" target="_blank" rel="noopener noreferer"> <?php echo esc_html__( 'VIEW POPULAR QUESTIONS', 'aawp' ); ?> </a>  </p>
							<p class="contact-us"><a href="<?php echo aawp_is_lang_de() ? 'https://aawp.de/kontakt/' : 'https://getaawp.com/contact/'; ?>" target="_blank" rel="noopener noreferer"> <?php echo esc_html__( 'Contact Support', 'aawp' ); ?> </a> </p>
						</div>
					</div>
				</div>
			</div>
		<?php

		echo ob_get_clean(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
