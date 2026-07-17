<?php

namespace AAWP\Admin\AdvancedAds;

use Advanced_Ads_Ad_Type_Abstract as AdvancedAdsType;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Class for AAWP Ad.
 *
 * @since 3.19
 */
class Ad extends AdvancedAdsType {

	/**
	 * ID - internal type of the ad type
	 *
	 * @var string $ID ad type id.
	 */
	public $ID = 'aawp';

	/**
	 * Set basic attributes
	 */
	public function __construct() {
		$this->title       = 'Amazon Products';
		$this->description = __( 'Add Amazon Products with AAWP.', 'aawp' );
		$this->parameters  = [
			'content' => '',
		];

		add_filter( 'advanced-ads-types-without-size', [ $this, 'remove_size' ] );
		add_action( 'wp_ajax_aawp_ad_ads_preview', [ $this, 'ajax_ad_preview' ] );
		add_filter( 'advanced-ads-tracking-clickable-types', [ $this, 'add_aawp_ad' ] );
	}

	/**
	 * Remove the default size parameter from the Amazon Products Ad type.
	 *
	 * @param array $types The Ad Types excluding the default Size Parameter.
	 *
	 * @since 3.19.
	 */
	public function remove_size( $types ) {
		$types[] = 'aawp';

		return $types;
	}

	/**
	 * Render icon on the ad overview list
	 *
	 * @param Advanced_Ads_Ad $ad ad object.
	 */
	public function render_icon( $ad ) {
		printf( '<img src="%s" width="50" height="50"/>', esc_url( AAWP_PLUGIN_URL . 'assets/img/advanced-ads.svg' ) );
	}

	/**
	 * Output for the ad parameters metabox
	 *
	 * This will be loaded using ajax when changing the ad type radio buttons
	 * echo the output right away here
	 * name parameters must be in the "advanced_ads" array
	 *
	 * @todo Make this a view-template because it's separately used on Classic Editor TinyMCE as well.
	 *
	 * @param Advanced_Ads_Ad $ad Advanced_Ads_Ad.
	 */
	public function render_parameters( $ad ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh

		$display_variant = ! empty( $ad->output['display_variant'] ) ? $ad->output['display_variant'] : '';
		$asin            = ! empty( $ad->output['asin'] ) ? $ad->output['asin'] : '';
		$keywords        = ! empty( $ad->output['keywords'] ) ? $ad->output['keywords'] : '';
		$items           = ! empty( $ad->output['items'] ) ? $ad->output['items'] : 10;
		$template        = ! empty( $ad->output['template'] ) ? $ad->output['template'] : 'default';

		?>

		<div id="aawp-modal" tabindex="-1">

				<div id="aawp-modal-inner">

					<div id="aawp-modal-options">
						<?php
						$looks = [
							'box'        => 'Product Boxes',
							'bestseller' => 'Bestseller (Lists)',
							'new'        => 'New Releases (Lists)',
						];

						// Display Variant Field.
						printf( '<p>%s</p><br/>', esc_html__( 'Choose your display variant', 'aawp' ) );

						printf( '<div class="display-variant-wrap">' );
						foreach ( $looks as $value => $look ) {
							printf( '<div class="display-variant-items">' );
								printf( '<input type="radio" id="' . esc_attr( $value ) . '" name="advanced_ad[output][display_variant]" value="%s" ' . checked( $display_variant, $value, false ) . ' >', esc_attr( $value ) );

								printf( '<label for="' . esc_attr( $value ) . '">' );

							switch ( $value ) {
								case 'box':
									$img = 'Product_Boxes.svg';
									break;

								case 'new':
									$img = 'New_Releases.svg';
									break;

								case 'bestseller':
									$img = 'Bestsellers.svg';
									break;
							}

									printf( '<img width="100" height="100" src="' . esc_url( plugins_url( 'assets/img/' . $img, AAWP_PLUGIN_FILE ) ) . '" alt="' . esc_html( $look ) . '" >' );
									printf( '<p>' . esc_html( $look ) . '</p>' );
								printf( '</label>' );
							printf( '</div>' );
						}//end foreach
						printf( '</div><br/>' );
						// <br/> because the next field is conditional.

						// ASIN Field.
						printf(
							'<div id="aawp-modal-asin-input-container" class="aawp-asin-input-control">
								<label for="aawp-modal-asin-input">%s</label><br/>
								<input type="text" class="regular-text" value="' . esc_attr( $asin ) . '" name="advanced_ad[output][asin]" id="aawp-modal-asin-input">
								<span class="advads-help">
									<span class="advads-tooltip">
										%s
									</span>
								</span>
							</div>',
							esc_html__( 'ASIN', 'aawp' ),
							esc_html__( 'Multiple ASIN values can be separated by comma.', 'aawp' )
						);

						// Product Search Field.
						echo '<div class="aawp-products-search-container">
							' . esc_html__( 'OR,', 'aawp' ) . '
							<button class="button button-secondary aawp-table-add-products-search" href="#aawp-modal-table-product-search" data-aawp-modal="true" data-aawp-table-add-products-search="true">
								<span class="dashicons dashicons-search"></span>
									' . esc_html__( 'Search For Product(s)' ) . '
							</button>
						</div>';

						// Keywords Field.
						printf(
							'<div id="aawp-modal-keywords-input-container">
								<label for="aawp-modal-keywords-input">%s</label><br/>
								<input type="text" value="' . esc_attr( $keywords ) . '"  name="advanced_ad[output][keywords]" id="aawp-modal-keywords-input">
								<span class="advads-help">
									<span class="advads-tooltip">
										%s
									</span>
								</span>
							</div>',
							esc_html__( 'Keywords', 'aawp' ),
							esc_html__( 'E.g. "top 4k monitors"' )
						);

						// Number of Items Field.
						printf(
							'<div id="aawp-modal-items-input-container">
								<label for="aawp-modal-items-input">%s</label><br/>
								<input type="number" value="' . absint( $items ) . '" name="advanced_ad[output][items]" id="aawp-modal-items-input">
								<span class="advads-help">
									<span class="advads-tooltip">
										%s
									</span>
								</span>
							</div>',
							esc_html__( 'Number of Items', 'aawp' ),
							esc_html__( 'Defines the maximum amount of products which will be shown.', 'aawp' )
						);

						// Template Field.
						printf( '<div id="aawp-modal-template-select-container"><label for="aawp-modal-template-select">%s</label><br/>', esc_html__( 'Template', 'aawp' ) );
						echo '<select name="advanced_ad[output][template]" id="aawp-modal-template-select">';

						$templates = [
							''                => esc_html__( 'Default', 'aawp' ),
							'horizontal'      => esc_html__( 'Horizontal', 'aawp' ),
							'vertical'        => esc_html__( 'Vertical', 'aawp' ),
							'list'            => esc_html__( 'List', 'aawp' ),
							'table'           => esc_html__( 'Table', 'aawp' ),
							'widget'          => esc_html__( 'Widget', 'aawp' ),
							'widget-vertical' => esc_html__( 'Widget Vertical', 'aawp' ),
							'widget-small'    => esc_html__( 'Widget Small', 'aawp' ),
						];

						foreach ( $templates as $value => $label ) {
							printf( '<option value="%s" ' . selected( $template, $value ) . '>%s</option>', esc_attr( $value ), esc_html( $label ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
						echo '</select></div>';

						?>
					</div>
				</div>
			</div>

			<div id="aawp-ad-preview">
				<br/><hr/>
				<h2><?php esc_html_e( 'Preview', 'aawp' ); ?> </h2><br/>

				<div id="aawp-ad-preview-contents">
					<?php echo $this->prepare_output( $ad ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</div>
		<?php
	}

	/**
	 * AJAX output.
	 *
	 * @since 3.x.x
	 */
	public function ajax_ad_preview() {

		check_admin_referer( 'aawp-admin-nonce', 'security' );

		$output = [
			'display_variant' => ! empty( $_POST['data']['display_variant'] ) ? sanitize_text_field( wp_unslash( $_POST['data']['display_variant'] ) ) : '',
			'asin'            => ! empty( $_POST['data']['asin'] ) ? sanitize_text_field( wp_unslash( $_POST['data']['asin'] ) ) : '',
			'keywords'        => ! empty( $_POST['data']['keywords'] ) ? sanitize_text_field( wp_unslash( $_POST['data']['keywords'] ) ) : '',
			'items'           => ! empty( $_POST['data']['items'] ) ? absint( $_POST['data']['items'] ) : 10,
			'template'        => ! empty( $_POST['data']['template'] ) ? sanitize_text_field( wp_unslash( $_POST['data']['template'] ) ) : '',
		];

		$ad = [ 'output' => $output ];

		$ad = (object) $ad;

		ob_start();

		echo $this->prepare_output( $ad ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		$content = ob_get_clean();

		wp_send_json( $content );

	}

	/**
	 * Prepare the ads frontend output
	 *
	 * @param Advanced_Ads_Ad $ad ad object.
	 *
	 * @return string $content ad content prepared for frontend output.
	 *
	 * @since 3.x
	 */
	public function prepare_output( $ad ) { //phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh

		if ( empty( $ad->output['display_variant'] ) ) {
			return '';
		}

		switch ( $ad->output['display_variant'] ) {
			case 'box':
				$next_input = $ad->output['asin'];
				break;

			case 'bestseller':
			case 'new':
				$next_input = $ad->output['keywords'];
				break;

			default:
				$next_input = '';
		}

		$template = ! empty( $ad->output['template'] ) ? $ad->output['template'] : 'default';

		$shortcode = '[' . aawp_get_shortcode() . ' ' . $ad->output['display_variant'] . '="' . $next_input . '"';

		if ( 'bestseller' === $ad->output['display_variant'] || 'new' === $ad->output['display_variant'] ) {
			$shortcode = $shortcode . ' items="' . $ad->output['items'] . '"';
		}

		$shortcode = $shortcode . ' template="' . $template . '" ]';

		return do_shortcode( $shortcode );
	}

	/**
	 * Add aawp ad compatibility to click tracking.
	 *
	 * @param array $ads Click trackable ads.
	 *
	 * @since 3.20
	 *
	 * @return array Click trackable ads including aawp ad.
	 */
	public function add_aawp_ad( $ads ) {
		$ads[] = 'aawp';

		return $ads;
	}
}
