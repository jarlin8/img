<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

class Thrive_Dash_List_Manager {
	public static $ADMIN_HAS_ERROR = false;

	public static $API_TYPES
		= array(
			'autoresponder' => 'Email Marketing',
			'webinar'       => 'Webinars',
			'other'         => 'Other',
			'recaptcha'     => 'Recaptcha',
			'social'        => 'Social',
			'sellings'      => 'Sales',
			'integrations'  => 'Integration Services',
			'email'         => 'Email Delivery',
			'storage'       => 'File Storage',
		);

	public static $AVAILABLE
		= array(
			'email'                => 'Thrive_Dash_List_Connection_Email',
			'activecampaign'       => 'Thrive_Dash_List_Connection_ActiveCampaign',
			'arpreach'             => 'Thrive_Dash_List_Connection_ArpReach',
			'aweber'               => 'Thrive_Dash_List_Connection_AWeber',
			'campaignmonitor'      => 'Thrive_Dash_List_Connection_CampaignMonitor',
			'constantcontact'      => 'Thrive_Dash_List_Connection_ConstantContact',
			'convertkit'           => 'Thrive_Dash_List_Connection_ConvertKit',
			'drip'                 => 'Thrive_Dash_List_Connection_Drip',
			'facebook'             => 'Thrive_Dash_List_Connection_Facebook',
			'fluentcrm'            => 'Thrive_Dash_List_Connection_FluentCRM',
			'get-response'         => 'Thrive_Dash_List_Connection_GetResponse',
			'google'               => 'Thrive_Dash_List_Connection_Google',
			'gotowebinar'          => 'Thrive_Dash_List_Connection_GoToWebinar',
			'hubspot'              => 'Thrive_Dash_List_Connection_HubSpot',
			'icontact'             => 'Thrive_Dash_List_Connection_iContact',
			'infusionsoft'         => 'Thrive_Dash_List_Connection_Infusionsoft',
			'klicktipp'            => 'Thrive_Dash_List_Connection_KlickTipp',
			'madmimi'              => 'Thrive_Dash_List_Connection_MadMimi',
			'mailchimp'            => 'Thrive_Dash_List_Connection_Mailchimp',
			'mailerlite'           => 'Thrive_Dash_List_Connection_MailerLite',
			'mailpoet'             => 'Thrive_Dash_List_Connection_MailPoet',
			'mailrelay'            => 'Thrive_Dash_List_Connection_MailRelay',
			'mautic'               => 'Thrive_Dash_List_Connection_Mautic',
			'ontraport'            => 'Thrive_Dash_List_Connection_Ontraport',
			'recaptcha'            => 'Thrive_Dash_List_Connection_ReCaptcha',
			'sendreach'            => 'Thrive_Dash_List_Connection_Sendreach',
			'sendgrid'             => 'Thrive_Dash_List_Connection_SendGrid',
			'sendinblue'           => 'Thrive_Dash_List_Connection_SendinblueV3',
			'sendy'                => 'Thrive_Dash_List_Connection_Sendy',
			'sg-autorepondeur'     => 'Thrive_Dash_List_Connection_SGAutorepondeur',
			'twitter'              => 'Thrive_Dash_List_Connection_Twitter',
			'webinarjamstudio'     => 'Thrive_Dash_List_Connection_WebinarJamStudio',
			'wordpress'            => 'Thrive_Dash_List_Connection_Wordpress',
			'mailster'             => 'Thrive_Dash_List_Connection_Mailster',
			'sendfox'              => 'Thrive_Dash_List_Connection_Sendfox',
			'zoho'                 => 'Thrive_Dash_List_Connection_Zoho',

			/* notification manger - these are now included in the dashboard - services for email notifications */
			'awsses'               => 'Thrive_Dash_List_Connection_Awsses',
			'campaignmonitoremail' => 'Thrive_Dash_List_Connection_CampaignMonitorEmail',
			'mailgun'              => 'Thrive_Dash_List_Connection_Mailgun',
			'mandrill'             => 'Thrive_Dash_List_Connection_Mandrill',
			'mailrelayemail'       => 'Thrive_Dash_List_Connection_MailRelayEmail',
			'postmark'             => 'Thrive_Dash_List_Connection_Postmark',
			'sendgridemail'        => 'Thrive_Dash_List_Connection_SendGridEmail',
			'sendinblueemail'      => 'Thrive_Dash_List_Connection_SendinblueEmailV3',
			'sparkpost'            => 'Thrive_Dash_List_Connection_SparkPost',
			'sendowl'              => 'Thrive_Dash_List_Connection_SendOwl',
			'sendlane'             => 'Thrive_Dash_List_Connection_Sendlane',
			'zoom'                 => 'Thrive_Dash_List_Connection_Zoom',
			'everwebinar'          => 'Thrive_Dash_List_Connection_EverWebinar',

			/* integrations services */
			'zapier'               => 'Thrive_Dash_List_Connection_Zapier',

			/* File Storage */
			'google_drive'         => 'Thrive_Dash_List_Connection_FileUpload_GoogleDrive',
			'dropbox'              => 'Thrive_Dash_List_Connection_FileUpload_Dropbox',
		);

	private static $_available = [];

	/**
	 * If the snake_case version of the function does not exist, attempt to call the camelCase version.
	 * Can be deleted after we switch everything to snake_case.
	 *
	 * @param $method_name
	 * @param $arguments
	 *
	 * @return mixed
	 */
	public static function __callStatic( $method_name, $arguments ) {
		$camel_case_method_name = tve_dash_to_camel_case( $method_name );

		/* in order to fit the old name of 'getAvailableAPIs' and 'getAvailableApisByType', this extra replace has to be done - ugly, but temporary */
		$camel_case_method_name = str_replace( 'getAvailableApis', 'getAvailableAPIs', $camel_case_method_name );

		return method_exists( __CLASS__, $camel_case_method_name ) ? call_user_func_array( [
			static::class,
			$camel_case_method_name
		], $arguments ) : null;
	}

	public static $default_api_filter = [
		'include_types' => [], /* by default everything is included */
		'exclude_types' => [], /* only taken into account if 'include' is empty ( they are mutually exclusive ) */
		'only_names'    => false,
	];

	/**
	 * todo: This is deprecated and will be deleted in 2-3 releases. - when deleting, also delete Test_API_Manager::test_getAvailableAPIs
	 * For now it has to stay because we changed the parameters of the function and this is called from the other plugins ( as long as they're not updated )
	 *
	 * @param bool  $only_connected
	 * @param array $exclude_types
	 * @param bool  $only_names
	 *
	 * @return array
	 * @deprecated
	 */
	public static function getAvailableAPIs( $only_connected = false, $exclude_types = [], $only_names = false ) {
		/* map the old parameters to the new filter array */
		$api_filter = [
			'exclude_types' => $exclude_types,
			'only_names'    => $only_names,
		];

		return static::get_available_apis( $only_connected, $api_filter );
	}

	/**
	 * @param bool  $only_connected
	 * @param array $api_filter
	 *
	 * @return array
	 */
	public static function get_available_apis( $only_connected = false, $api_filter = [] ) {
		$lists = [];

		$api_filter = array_merge( static::$default_api_filter, $api_filter );

		foreach ( static::available() as $key => $api ) {
			if ( ! class_exists( $api ) ) {
				continue;
			}

			/** @var Thrive_Dash_List_Connection_Abstract $instance */
			$instance = static::connection_instance( $key );

			if (
				$instance &&
				static::should_include_api( $instance, $api_filter ) &&
				( ! $only_connected || static::is_api_connected( $key, $instance ) )
			) {
				$lists[ $key ] = $api_filter['only_names'] ? $instance->getTitle() : $instance;
			}
		}

		return apply_filters( 'tvd_api_available_connections', $lists, $only_connected, $api_filter );
	}

	/**
	 * @param $key
	 * @param $instance
	 *
	 * @return bool
	 */
	public static function is_api_connected( $key, $instance ) {
		return ! empty( static::credentials( $key ) ) && $instance->is_connected();
	}

	/**
	 * @param $instance
	 * @param $api_filter
	 *
	 * @return bool
	 */
	public static function should_include_api( $instance, $api_filter ) {
		$type = $instance::get_type();

		/* 'include_types' and 'exclude_types' are mutually exclusive, so if we want to include something, we no longer check exclusions */
		if ( empty( $api_filter['include_types'] ) ) {
			if ( is_array( $api_filter['exclude_types'] ) ) {
				$should_include_api = ! in_array( $type, $api_filter['exclude_types'], true );
			} else {
				$should_include_api = ( $type !== $api_filter['exclude_types'] );
			}
		} else {
			if ( is_array( $api_filter['include_types'] ) ) {
				$should_include_api = in_array( $type, $api_filter['include_types'], true );
			} else {
				$should_include_api = ( $type === $api_filter['include_types'] );
			}
		}

		return $should_include_api;
	}

	/**
	 * @param bool $get_localized_data
	 *
	 * @return mixed|void
	 */
	public static function get_third_party_autoresponders( $get_localized_data = true ) {
		return apply_filters( 'tvd_third_party_autoresponders', [], $get_localized_data );
	}

	/**
	 * Build custom fields for all available connections
	 */
	public static function getAvailableCustomFields() {
		$custom_fields = [];
		$apis          = static::get_available_apis( true, [ 'exclude_types' => [ 'email', 'social' ] ] );

		/**
		 * @var string                               $api_name
		 * @var Thrive_Dash_List_Connection_Abstract $api
		 */
		foreach ( $apis as $api_name => $api ) {
			$custom_fields[ $api_name ] = $api->get_api_custom_fields( array(), false, true );
		}

		return $custom_fields;
	}

	/**
	 * Get the harcoded mapper from the first connected API
	 *
	 * @return array
	 */
	public static function getCustomFieldsMapper() {

		$mapper = [];
		$apis   = static::get_available_apis( true, [ 'exclude_types' => [ 'email', 'social' ] ] );

		/**
		 * @var Thrive_Dash_List_Connection_Abstract $api
		 */
		foreach ( $apis as $api ) {

			if ( ! empty( $mapper ) ) {
				break;
			}

			$mapper = $api->getMappedCustomFields();
		}

		return $mapper;
	}

	/**
	 * DEPRECATED
	 * get a list of all available APIs by type
	 * todo: this is deprecated ( moved to get_available_apis() ), get rid of it after 2-3 releases
	 *
	 * @param bool         $onlyConnected if true, it will return only APIs that are already connected
	 * @param string|array $include_types exclude connection by their type
	 *
	 * @return array Thrive_Dash_List_Connection_Abstract[]
	 * @deprecated
	 */
	public static function getAvailableAPIsByType( $onlyConnected = false, $include_types = [] ) {
		if ( ! is_array( $include_types ) ) {
			$include_types = array( $include_types );
		}
		$lists = array();

		$credentials = self::credentials();

		foreach ( self::available() as $key => $api ) {
			/** @var Thrive_Dash_List_Connection_Abstract $instance */
			$instance = self::connectionInstance( $key, isset( $credentials[ $key ] ) ? $credentials[ $key ] : array() );
			if ( ( $onlyConnected && empty( $credentials[ $key ] ) ) || ! in_array( $instance::getType(), $include_types, true ) ) {
				continue;
			}

			if ( $onlyConnected && ! $instance->is_connected() ) {
				continue;
			}

			$lists[ $key ] = $instance;
		}

		return $lists;
	}

	public static $all_credentials = [];

	/**
	 * Fetch the connection credentials for a specific connection (or for all at once)
	 *
	 * @param string $key if empty, all will be returned
	 *
	 * @return array
	 */
	public static function credentials( $key = '' ) {
		if ( empty( static::$all_credentials ) ) {
			$all_credentials = get_option( 'thrive_mail_list_api', [] );

			/* make sure that the email connection is always available */
			if ( empty( $all_credentials['email'] ) ) {
				$all_credentials['email'] = [ 'connected' => true ];
			}
			/* make sure the WP API Connection is always available  */
			if ( empty( $all_credentials['wordpress'] ) ) {
				$all_credentials['wordpress'] = [ 'connected' => true ];
			}

			static::$all_credentials = $all_credentials;
		}

		if ( empty( $key ) ) {
			$credentials = static::$all_credentials;
		} else if ( ! isset( static::$all_credentials[ $key ] ) ) {
			$credentials = [];
		} else {
			$credentials = static::$all_credentials[ $key ];
		}

		return $credentials;
	}

	/**
	 * save the credentials for an instance
	 *
	 * @param Thrive_Dash_List_Connection_Abstract $instance
	 */
	public static function save( $instance ) {
		//put the credentials in this global array
		//so that for the next save call this array contains the previous credentials
		static::$all_credentials[ $instance->getKey() ] = $instance->getCredentials();

		/**
		 * Invalidate cache for Email Connection
		 * - so that Email connection get to know the new email provider
		 */
		if ( $instance::getType() === 'email' ) {
			delete_transient( 'tve_api_data_email' );
		}

		update_option( 'thrive_mail_list_api', static::$all_credentials );
	}

	/**
	 * Factory method for a connection instance
	 * todo: Can be renamed into connection_instance after 2-3 releases.
	 *
	 * @param string $key
	 *
	 * @return Thrive_Dash_List_Connection_Abstract
	 */
	public static function connectionInstance( $key ) {
		$apis = array_merge( static::available(), static::get_third_party_autoresponders( false ) );

		if ( ! isset( $apis[ $key ] ) ) {
			return null;
		}

		if ( is_subclass_of( $apis[ $key ], 'Thrive_Dash_List_Connection_Abstract', true ) ) {
			/** @var Thrive_Dash_List_Connection_Abstract $instance */
			$instance = new $apis[ $key ]( $key );
			$instance->setCredentials( static::credentials( $key ) );
		} else {
			/* if the API class does not extend abstract, we assume that it's a third party API. In this case, the instance should be found in the list */
			$instance = $apis[ $key ];
		}

		return $instance;
	}

	/**
	 * saves a message to be displayed on the next request
	 *
	 * @param string $type
	 * @param string $message
	 */
	public static function message( $type, $message ) {
		if ( $type == 'error' ) {
			self::$ADMIN_HAS_ERROR = true;
		}
		$messages          = get_option( 'tve_api_admin_notices', array() );
		$messages[ $type ] = $message;

		update_option( 'tve_api_admin_notices', $messages );
	}

	/**
	 * reads out all messages (success / error from the options table)
	 */
	public static function flashMessages() {
		$GLOBALS['thrive_list_api_message'] = get_option( 'tve_api_admin_notices', array() );

		delete_option( 'tve_api_admin_notices' );
	}

	/**
	 * transform the array of connections into one input string
	 * this is a really simple encrypt system, is there any need for a more complicated one ?
	 *
	 * @param array $connections a list of key => value pairs (api => list)
	 *
	 * @return string
	 */
	public static function encodeConnectionString( $connections = array() ) {
		return base64_encode( serialize( $connections ) );
	}

	/**
	 * transform the $string into an array of connections
	 *
	 * @param string $string
	 *
	 * @return array
	 */
	public static function decodeConnectionString( $string ) {
		if ( empty( $string ) ) {
			return array();
		}
		$string = @base64_decode( $string );
		if ( empty( $string ) ) {
			return array();
		}
		$data = thrive_safe_unserialize( $string );

		return empty( $data ) || ! is_array( $data ) ? array() : tve_sanitize_data_recursive( $data, 'sanitize_textarea_field' );
	}

	public static function toJSON( $APIs = array() ) {
		if ( ! is_array( $APIs ) || empty( $APIs ) ) {
			return json_encode( array() );
		}

		$list = array();

		foreach ( $APIs as $key => $instance ) {

			/** @var $instance Thrive_Dash_List_Connection_Abstract */
			if ( ! $instance instanceof Thrive_Dash_List_Connection_Abstract ) {
				continue;
			}

			$list[] = $instance->prepareJSON();
		}

		return json_encode( $list );
	}

	public static function available() {
		if ( ! self::$_available ) {
			self::$_available = apply_filters( 'tve_filter_available_connection', self::$AVAILABLE );
		}

		return self::$_available;
	}
}
