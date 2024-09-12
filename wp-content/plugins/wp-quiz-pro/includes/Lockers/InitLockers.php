<?php
/**
 * Init lockers
 *
 * @package WPQuiz
 */

namespace WPQuiz\Lockers;

use WPQuiz\Quiz;

/**
 * Class InitLockers
 */
class InitLockers {

	/**
	 * Initializes.
	 */
	public function init() {
		add_action( 'wp_quiz_before_trivia_quiz', array( $this, 'show_pay_locker' ), 20 );
		add_action( 'wp_quiz_before_personality_quiz', array( $this, 'show_pay_locker' ), 20 );
	}

	/**
	 * Shows pay locker.
	 *
	 * @param Quiz $quiz Quiz object.
	 */
	public function show_pay_locker( Quiz $quiz ) {
		if ( ! empty( $quiz->displayed_question ) || 'on' !== $quiz->get_setting( 'pay_to_play' ) || ! floatval( $quiz->get_setting( 'pay_to_play_amount' ) ) ) {
			return;
		}

		$this->enqueue();
		$locker = new PayLocker( $quiz );
		echo $locker->output(); // WPCS: xss ok.
	}

	/**
	 * Enqueues css and js.
	 */
	protected function enqueue() {
		wp_enqueue_script( 'wp-quiz-pay-locker', wp_quiz()->assets() . 'js/lockers/pay-locker.js', array( 'jquery', 'wp-quiz' ), '2.0.0', true );
	}
}
