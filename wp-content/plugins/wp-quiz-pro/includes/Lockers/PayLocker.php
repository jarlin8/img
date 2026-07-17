<?php
/**
 * Pay locker
 *
 * @package WPQuiz
 */

namespace WPQuiz\Lockers;

use WPQuiz\Quiz;
use WPQuiz\Template;

/**
 * Class PayLocker
 */
class PayLocker extends Locker {

	/**
	 * Gets locker output.
	 *
	 * @return string
	 */
	public function output() {
		$amount = $this->quiz->get_setting( 'pay_to_play_amount' );
		ob_start();
		Template::load_template(
			'lockers/pay-locker.php',
			array(
				'quiz'   => $this->quiz,
				'amount' => floatval( $amount ) * 100,
			)
		);
		$output = ob_get_clean();

		/**
		 * Allows changing pay locker output.
		 *
		 * @since 2.0.0
		 *
		 * @param string $output Locker output.
		 * @param Quiz   $quiz   Quiz object.
		 */
		return apply_filters( 'wp_quiz_pay_locker_output', $output, $this->quiz );
	}
}
