<?php
/**
 * Template for pay loader
 *
 * @package WPQuiz
 *
 * @var \WPQuiz\Quiz $quiz
 * @var float        $amount
 */

use WPQuiz\Helper;

if ( Helper::get_option( 'stripe_api_key' ) ) :
	?>
	<script src="https://checkout.stripe.com/checkout.js"></script>
	<script>
		window.stripeHandler = StripeCheckout.configure({
			key: '<?php echo esc_js( Helper::get_option( 'stripe_api_key' ) ); ?>',
			image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
			locale: 'auto',
			currency: '<?php echo Helper::get_option( 'currency' ); ?>',
			token: function( token ) {
				// You can access the token ID with `token.id`.
				// Get the token ID to your server-side code for use.
				jQuery( document ).trigger( 'wp_quiz_stripe_token', [ token ] );
			}
		});
	</script>
	<?php
endif;
?>

<div class="wq-locker wq-pay-locker">
	<p><?php esc_html_e( 'This is the paid quiz, please complete the payment to play.', 'wp-quiz-pro' ); ?></p>
	<p><button type="button" class="wq-js-pay-button wq-pay-locker__button" data-amount="<?php echo floatval( $amount ); ?>"><?php esc_html_e( 'Pay now', 'wp-quiz-pro' ); ?></button></p>
</div><!-- End .wq-pay-locker -->
