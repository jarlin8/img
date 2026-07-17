<?php
/**
 * AWeber mail service
 *
 * @package WPQuiz
 */

namespace WPQuiz\Modules\Subscription\MailServices;

use AWeberAPI;
use Exception;
use WPQuiz\Helper;

/**
 * Class AWeber
 */
class AWeber extends MailService {

	/**
	 * Consumer key.
	 *
	 * @var string
	 */
	protected $consumer_key = '';

	/**
	 * Consumer secret.
	 *
	 * @var string
	 */
	protected $consumer_secret = '';

	/**
	 * Access key.
	 *
	 * @var string
	 */
	protected $access_key = '';

	/**
	 * Access secret.
	 *
	 * @var string
	 */
	protected $access_secret = '';

	/**
	 * Account ID.
	 *
	 * @var string
	 */
	protected $account_id = '';

	/**
	 * List ID.
	 *
	 * @var string
	 */
	protected $list_id = '';

	/**
	 * Class AWeber constructor.
	 */
	public function __construct() {
		$this->name  = 'aweber';
		$this->title = __( 'AWeber', 'wp-quiz-pro' );

		$options = Helper::get_option( 'aweber' );
		if ( ! empty( $options['consumer_key'] ) ) {
			$this->consumer_key = $options['consumer_key'];
		}
		if ( ! empty( $options['consumer_secret'] ) ) {
			$this->consumer_secret = $options['consumer_secret'];
		}
		if ( ! empty( $options['access_key'] ) ) {
			$this->access_key = $options['access_key'];
		}
		if ( ! empty( $options['access_secret'] ) ) {
			$this->access_secret = $options['access_secret'];
		}
		if ( ! empty( $options['account_id'] ) ) {
			$this->account_id = $options['account_id'];
		}
		if ( ! empty( $options['listid'] ) ) {
			$this->list_id = str_replace( 'awlist', '', $options['listid'] );
		}

		parent::__construct();
	}

	/**
	 * Gets AWeber API object.
	 *
	 * @return AWeberAPI
	 * @throws Exception Exception.
	 */
	protected function get_aweber() {
		if ( ! $this->consumer_key || ! $this->consumer_secret ) {
			throw new Exception( __( 'AWeber is not connected.', 'wp-quiz-pro' ) );
		}

		if ( ! $this->account_id || ! $this->access_key || ! $this->access_secret ) {
			throw new Exception( __( 'The AWeber Account ID is not set.', 'wp-quiz-pro' ) );
		}

		return new AWeberAPI( $this->consumer_key, $this->consumer_secret );
	}

	/**
	 * Connects AWeber account.
	 *
	 * @param string $access_key Access key.
	 * @return array
	 * @throws Exception Exception.
	 */
	public function connect( $access_key ) {
		list( $consumer_key, $consumer_secret, $access_key, $access_secret ) = AWeberAPI::getDataFromAweberID( $access_key );

		if ( empty( $consumer_key ) || empty( $consumer_secret ) || empty( $access_key ) || empty( $access_secret ) ) {
			throw new Exception( esc_html__( 'Unable to connect your AWeber Account. The Authorization Code is incorrect.', 'wp-quiz-pro' ) );
		}

		$aweber  = new AWeberAPI( $consumer_key, $consumer_secret );
		$account = $aweber->getAccount( $access_key, $access_secret );

		$credentials = array(
			'consumer_key'    => $consumer_key,
			'consumer_secret' => $consumer_secret,
			'access_key'      => $access_key,
			'access_secret'   => $access_secret,
			'account_id'      => $account->id,
		);

		return $credentials;
	}

	/**
	 * Subscribes email.
	 *
	 * @param string $email Email address.
	 * @param string $name  Subscriber name.
	 * @return mixed|false Return data base on API response or `false` on failure.
	 */
	public function subscribe( $email, $name ) {
		try {
			$aweber   = $this->get_aweber();
			$account  = $aweber->getAccount( $this->access_key, $this->access_secret );
			$list_url = "/accounts/{$this->account_id}/lists/{$this->list_id}";
			$list     = $account->loadFromUrl( $list_url );

			$params = array(
				'name'        => $name,
				'email'       => $email,
				// phpcs:ignore
				'ip_address'  => $_SERVER['REMOTE_ADDR'],
				'ad_tracking' => 'mythemeshop',
			);

			$subscribers = $list->subscribers;
			$subscribers->create( $params );

			return array( 'status' => 'subscribed' );
		} catch ( Exception $e ) {
			if ( $e instanceof \AWeberAPIException ) {
				error_log( "AWeberAPIException:\nType: {$e->type}\nMessage: {$e->message}\nDocs: $e->documentation_url" );
			} else {
				error_log( $e->getMessage() );
			}
			return false;
		}
	}

	/**
	 * Checks if AWeber account is connected.
	 *
	 * @return bool
	 */
	protected function is_connected() {
		$access_key = Helper::get_option( 'aweber_access_key' );
		return ! empty( $access_key );
	}

	/**
	 * Registers options.
	 *
	 * @param \CMB2 $cmb CMB2 object.
	 */
	public function register_options( \CMB2 $cmb ) {
		$cmb->add_field(
			array(
				'id'   => 'aweber',
				'type' => 'aweber',
				'name' => __( 'AWeber options', 'wp-quiz-pro' ),
				'dep'  => $this->get_dependency(),
			)
		);
	}
}
