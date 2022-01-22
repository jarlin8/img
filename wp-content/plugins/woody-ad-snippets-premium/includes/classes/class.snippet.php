<?php
/**
 * Main snippet functions
 *
 * Данный класс содержит методы для работы с хуками, а также основной функционал плагина
 *
 * @author        Webcraftic <wordpress.webraftic@gmail.com>
 * @copyright (c) 07.02.2019, Webcraftic
 * @version       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WASP_Snippet
 */
class WASP_Snippet {

	/**
	 * WASP_Snippet constructor.
	 */
	public function __construct() {
		$this->register_hooks();
	}

	/**
	 * Register hooks
	 *
	 * Данный метод в основном использует хуки базового плагина для изменения его поведения
	 * и для фильтрации и изменения каких-то данных
	 *
	 * Хук wbcr_factory_000_page_is_hidden_import-' . WINP_Plugin::app()->getPluginName() нужен для скрытия
	 * меню Import базового плагина. В последствии это меню заменится изменённым разделом.
	 */
	public function register_hooks() {
		add_action( 'wbcr/inp/settings/after_construct', array( $this, 'after_settings_construct' ) );
		add_action( 'wbcr/inp/settings/after_form_save', array( $this, 'after_settings_form_save' ) );

		add_filter( 'wbcr/inp/visibility/filter_params', array( $this, 'add_filter_params' ) );
		add_filter( 'wbcr/inp/execute/check_condition', array( $this, 'check_condition' ), 10, 4 );
		add_filter( 'wbcr/inp/settings/form_options', array( $this, 'form_options' ) );
		add_filter( 'wbcr/inp/import/snippet', array( $this, 'import_snippet' ), 10, 5 );
		add_filter( 'wbcr/inp/viewtable/where_use', array( $this, 'where_use' ), 10, 2 );
		add_filter( 'wbcr/inp/helper/get_shortcode_data', array( $this, 'update_shortcode_data' ), 10, 2 );
		add_filter( 'wbcr/inp/gutenberg/shortcode_attributes', array( $this, 'shortcode_attributes' ), 10, 2 );
		add_filter( 'wbcr/inp/gutenberg/shortcode_name', array( $this, 'shortcode_name' ), 10, 2 );
		add_filter( 'wbcr/inp/base_option/on_saving_form', array( $this, 'on_saving_form' ) );
	}

	/**
	 * Call checkByOperator method from base plugin
	 *
	 * @param $operation
	 * @param $first
	 * @param $second
	 * @param bool $third
	 *
	 * @return bool
	 */
	private function check_by_operator( $operation, $first, $second, $third = false ) {
		return WINP_Plugin::app()->getExecuteObject()->checkByOperator( $operation, $first, $second, $third );
	}

	/**
	 * Call getDateTimestamp method from base plugin
	 *
	 * @param $value
	 *
	 * @return mixed
	 */
	private function get_date_timestamp( $value ) {
		return WINP_Plugin::app()->getExecuteObject()->getDateTimestamp( $value );
	}

	/**
	 * Add filter parameters
	 *
	 * Добавляет новые фильтры в раздел Conditional execution logic for the snippet на странице редактирования сниппета
	 *
	 * @param $filters
	 *
	 * @return array
	 */
	public function add_filter_params( $filters ) {
		if ( ! empty( $filters ) ) {
			foreach ( $filters as $key => $filter ) {
				if ( 'technology' == $filter['id'] || 'auditory' == $filter['id'] ) {
					unset( $filters[ $key ] );
				}
			}
		}

		$filters[] = array(
			'id'    => 'technology',
			'title' => __( 'Technology', 'insert-php' ),
			'items' => array(
				array(
					'id'          => 'technology-addblocker',
					'title'       => __( 'Addblocker', 'insert-php' ),
					'type'        => 'select',
					'values'      => array(
						array(
							'value' => 'yes',
							'title' => __( 'Yes', 'insert-php' ),
						),
						array(
							'value' => 'no',
							'title' => __( 'No', 'insert-php' ),
						),
					),
					'description' => __( 'Determines whether the user use Addblocker on website.', 'insert-php' ),
				),
				array(
					'id'          => 'technology-browser',
					'title'       => __( 'Browser', 'insert-php' ),
					'type'        => 'select',
					'values'      => array(
						array(
							'value' => 'google-chrome',
							'title' => __( 'Google Chrome', 'insert-php' ),
						),
						array(
							'value' => 'chrome-mobile',
							'title' => __( 'Chrome Mobile', 'insert-php' ),
						),
						array(
							'value' => 'chromium',
							'title' => __( 'Chromium', 'insert-php' ),
						),
						array(
							'value' => 'android-browser',
							'title' => __( 'Android Browser', 'insert-php' ),
						),
						array(
							'value' => 'firefox',
							'title' => __( 'Firefox', 'insert-php' ),
						),
						array(
							'value' => 'firefox-mobile',
							'title' => __( 'Firefox Mobile', 'insert-php' ),
						),
						array(
							'value' => 'opera',
							'title' => __( 'Opera', 'insert-php' ),
						),
						array(
							'value' => 'opera-mobile',
							'title' => __( 'Opera Mobile', 'insert-php' ),
						),
						array(
							'value' => 'opera-mini',
							'title' => __( 'Opera Mini', 'insert-php' ),
						),
						array(
							'value' => 'yandex',
							'title' => __( 'Яндекс.Браузер', 'insert-php' ),
						),
						array(
							'value' => 'safari',
							'title' => __( 'Safari', 'insert-php' ),
						),
						array(
							'value' => 'mobile-safari',
							'title' => __( 'Mobile Safari', 'insert-php' ),
						),
						array(
							'value' => 'msie',
							'title' => __( 'MSIE', 'insert-php' ),
						),
						array(
							'value' => 'msie-mobile',
							'title' => __( 'MSIE Mobile', 'insert-php' ),
						),
						array(
							'value' => 'ucweb',
							'title' => __( 'UCWEB', 'insert-php' ),
						),
						array(
							'value' => 'samsung-internet',
							'title' => __( 'Samsung Internet', 'insert-php' ),
						),
						array(
							'value' => 'other',
							'title' => __( 'Other', 'insert-php' ),
						),
					),
					'description' => __( 'Determines whether the user use selected browser.', 'insert-php' ),
				),
				array(
					'id'          => 'technology-use-cookie',
					'title'       => __( 'Use cookie', 'insert-php' ),
					'type'        => 'select',
					'values'      => array(
						array(
							'value' => 'yes',
							'title' => __( 'Yes', 'insert-php' ),
						),
						array(
							'value' => 'no',
							'title' => __( 'No', 'insert-php' ),
						),
					),
					'description' => __( 'Determines whether the user use cookie on website.', 'insert-php' ),
				),
				array(
					'id'          => 'technology-use-javascript',
					'title'       => __( 'Use javascript', 'insert-php' ),
					'type'        => 'select',
					'values'      => array(
						array(
							'value' => 'yes',
							'title' => __( 'Yes', 'insert-php' ),
						),
						array(
							'value' => 'no',
							'title' => __( 'No', 'insert-php' ),
						),
					),
					'description' => __( 'Determines whether the user use javascript on website.', 'insert-php' ),
				),
				array(
					'id'          => 'technology-operating-system',
					'title'       => __( 'Operating system', 'insert-php' ),
					'type'        => 'select',
					'values'      => array(
						array(
							'value' => 'windows',
							'title' => __( 'Windows', 'insert-php' ),
						),
						array(
							'value' => 'mac-os',
							'title' => __( 'Mac OS', 'insert-php' ),
						),
						array(
							'value' => 'gnu-linux',
							'title' => __( 'GNU/Linux', 'insert-php' ),
						),
						array(
							'value' => 'google-android',
							'title' => __( 'Google Android', 'insert-php' ),
						),
						array(
							'value' => 'google-chrome-os',
							'title' => __( 'Google Chrome OS', 'insert-php' ),
						),
						array(
							'value' => 'other-java-me',
							'title' => __( 'Other с Java ME', 'insert-php' ),
						),
					),
					'description' => __( 'Determines whether the user use selected OS.', 'insert-php' ),
				),
				array(
					'id'          => 'technology-device-type',
					'title'       => __( 'Device type', 'insert-php' ),
					'type'        => 'select',
					'values'      => array(
						array(
							'value' => 'mobile',
							'title' => __( 'Mobile', 'insert-php' ),
						),
						array(
							'value' => 'tabled',
							'title' => __( 'Tabled', 'insert-php' ),
						),
						array(
							'value' => 'phone',
							'title' => __( 'Phone', 'insert-php' ),
						),
						array(
							'value' => 'desktop',
							'title' => __( 'Desktop', 'insert-php' ),
						),
					),
					'description' => __( 'Determines whether the user use selected device type.', 'insert-php' ),
				),
			),
		);

		$filters[] = array(
			'id'    => 'auditory',
			'title' => __( 'Auditory', 'insert-php' ),
			'items' => array(
				array(
					'id'          => 'auditory-country',
					'title'       => __( 'User country', 'insert-php' ),
					'type'        => 'select',
					'values'      => array(
						'type'   => 'ajax',
						'action' => 'wbcr_inp_ajax_get_user_country',
					),
					'description' => __( 'Geolocation', 'insert-php' ),
				),
				array(
					'id'          => 'auditory-viewing',
					'title'       => __( 'Viewing depth', 'insert-php' ),
					'type'        => 'integer',
					'description' => __( 'The number of pages viewed by the user per session', 'insert-php' ),
				),
				array(
					'id'          => 'auditory-attendance',
					'title'       => __( 'Attendance by time of day', 'insert-php' ),
					'type'        => 'date-between',
					'description' => __( 'The time interval during which the user entered', 'insert-php' ),
				),
				array(
					'id'          => 'auditory-visits',
					'title'       => __( 'Total number of visits', 'insert-php' ),
					'type'        => 'integer',
					'description' => __( 'The total number of sessions opened by the user', 'insert-php' ),
				),
			),
		);

		return $filters;
	}

	/**
	 * Вызывает передаваемый метод, если он существует в данном классе, и возвращает результат проверки
	 *
	 * @param $result
	 * @param $method_name
	 * @param $operator
	 * @param $value
	 *
	 * @return bool
	 */
	public function check_condition( $result, $method_name, $operator, $value ) {
		if ( method_exists( $this, $method_name ) ) {
			return $this->$method_name( $operator, $value );
		}

		return $result;
	}

	/**
	 * Делаем проверку на наличие файлов базы геоданных, если они активированы.
	 * При отсутствии выводим сообщение.
	 */
	public function after_settings_construct() {
		if (
			'maxmind' == WINP_Plugin::app()->getOption( 'geo_db' )
			&& 'enabled' == WINP_Plugin::app()->getOption( 'geo_db_updates' )
			&& ! WASP_Core::app()->get_geo_object()->db_file_exist()
		) {
			add_action(
				'admin_notices',
				function () { ?>
					<div class="notice notice-error">
						<p><?php _e( 'MaxMind IP geolocation database file not found.', 'insert-php' ); ?>
							<a href="#wbcr_inp_check_db"><?php _e( 'Check geolocation', 'insert-php' ); ?></a></p>
					</div>
					<?php
				}
			);
		}
	}

	/**
	 * Добавляем скрипты, которые обрабатывают форму настроек раздела геоданных.
	 * Также данный скрипт отправляет ajax запрос на загрузку базы данных при её отсутствии.
	 */
	public function after_settings_form_save() {
		if (
			'maxmind' == WINP_Plugin::app()->getOption( 'geo_db' )
			&& 'enabled' == WINP_Plugin::app()->getOption( 'geo_db_updates' )
			&& ! WASP_Core::app()->get_geo_object()->db_file_exist()
		) {
			?>
			<script type="text/javascript">
				jQuery(document).ready(function($) {
					$('.factory-control-geo_db').
						before(
							"<div id='wbcr_inp_message_geo'><p><?php _e( 'Downloading...', 'insert-php' ); ?></p></div>");
					$.ajax(ajaxurl, {
						type: 'POST',
						data: {
							action: 'wbcr_inp_ajax_update_geo_db'
						}
					}).done(function(data) {
						var message = '<?php _e( 'Complete download', 'insert-php' ); ?>';
						if (data != '') {
							message = data;
						} else {
							$('#wbcr_inp_db_missing').remove();
						}
						$('#wbcr_inp_message_geo').html('<p>' + message + '</p>');
					});
				});
			</script>
			<?php
		}
	}

	/**
	 * Добавляем в форму настроек раздел геоданных с соответствующими параметрами
	 *
	 * @param $options
	 *
	 * @return array
	 */
	public function form_options( $options ) {
		$options[] = array(
			'type' => 'html',
			'html' => '<h3 style="margin-left:0">' . __( 'Geolocation', 'insert-php' ) . '</h3>',
		);

		$options[] = array(
			'type' => 'separator',
		);

		$options[] = array(
			'type'    => 'dropdown',
			'name'    => 'geo_db',
			'title'   => __( 'IP geolocation database', 'insert-php' ),
			'data'    => array(
				array(
					'webnet',
					__( 'Webnet77', 'insert-php' ),
				),
				array(
					'maxmind',
					__( 'MaxMind', 'insert-php' ),
				),
			),
			'default' => 'webnet',
			'hint'    => __( 'Select IP geolocation database', 'insert-php' ),
			'events'  => array(
				'webnet'  => array(
					'hide' => '.factory-control-geo_db_updates,.factory-control-geo_db_path',
				),
				'maxmind' => array(
					'show' => '.factory-control-geo_db_updates,.factory-control-geo_db_path',
				),
			),
		);

		$options[] = array(
			'type'    => 'dropdown',
			'name'    => 'geo_db_updates',
			'title'   => __( 'Automatic update', 'insert-php' ),
			'data'    => array(
				array(
					'disabled',
					__( 'Disabled', 'insert-php' ),
				),
				array(
					'enabled',
					__( 'Enabled', 'insert-php' ),
				),
			),
			'default' => 'disabled',
			'hint'    => __( 'Select "Enabled" for automatic database update once per month.', 'insert-php' ),
		);

		$default_path = WASP_Core::app()->get_geo_object()->get_default_db_path_name();
		$options[]    = array(
			'type'        => 'textbox',
			'name'        => 'geo_db_path',
			'title'       => __( 'Database', 'insert-php' ) . ( WASP_Core::app()->get_geo_object()->db_file_exist() ? ' <span id="wbcr_inp_db_exist">' . __( 'exists', 'insert-php' ) . '</span>' : ' <span id="wbcr_inp_db_missing">' . __( 'missing', 'insert-php' ) . '</span>' ),
			'placeholder' => $default_path,
			/* translators: %s: var/www/site.com/wp-content/uploads/GeoLite2-City.mmdb */
			'hint'        => sprintf( __( 'Specify the path to the location of the database file. (Default: %s)', 'insert-php' ), $default_path ),
		);

		return $options;
	}

	/**
	 * Determines whether the user use Addblocker on website
	 *
	 * @param $operator
	 * @param $value
	 *
	 * @return boolean
	 */
	private function technology_addblocker( $operator, $value ) {
		/* https://stackoverflow.com/questions/4869154/how-to-detect-adblock-on-my-website
		if ( wp_is_mobile() ) {
			return 'equals' === $operator && 'yes' === $value || 'notequal' === $operator && 'no' === $value;
		} else {
			return 'notequal' === $operator && 'yes' === $value || 'equals' === $operator && 'no' === $value;
		}*/
		return false;
	}

	/**
	 * Check use cookies
	 *
	 * @param $operator
	 * @param $value
	 *
	 * @return bool
	 */
	private function technology_use_cookie( $operator, $value ) {
		return false;
	}

	/**
	 * Check use javascript
	 *
	 * @param $operator
	 * @param $value
	 *
	 * @return bool
	 */
	private function technology_use_javascript( $operator, $value ) {
		return false;
	}

	/**
	 * Check browser
	 *
	 * @param $operator
	 * @param $value
	 *
	 * @return bool
	 */
	private function technology_browser( $operator, $value ) {
		switch ( $value ) {
			case 'google-chrome':
				global $is_chrome;
				$result = $is_chrome;
				break;
			case 'chrome-mobile':
				global $is_chrome;
				$result = $is_chrome && wp_is_mobile();
				break;
			case 'chromium':
			case 'android-browser':
			case 'samsung-internet':
				global $is_chrome;
				$result = $is_chrome && WASP_Helper::get_os() === 'Android';
				break;
			case 'firefox':
				$result = WASP_Helper::get_browser() === 'Firefox';
				break;
			case 'firefox-mobile':
				$result = WASP_Helper::get_browser() === 'Firefox' && wp_is_mobile();
				break;
			case 'opera':
				global $is_opera;
				$result = $is_opera;
				break;
			case 'opera-mobile':
			case 'opera-mini':
				global $is_opera;
				$result = $is_opera && wp_is_mobile();
				break;
			case 'yandex':
				$result = WASP_Helper::get_browser() === 'Yandex';
				break;
			case 'safari':
				global $is_safari;
				$result = $is_safari;
				break;
			case 'mobile-safari':
				global $is_safari;
				$result = $is_safari && wp_is_mobile();
				break;
			case 'msie':
				global $is_IE;
				$result = $is_IE;
				break;
			case 'msie-mobile':
				global $is_IE;
				$result = $is_IE && wp_is_mobile();
				break;
			case 'ucweb':
				$result = WASP_Helper::get_browser() === 'UCBrowser';
				break;
			case 'other':
				$result = ! in_array(
					WASP_Helper::get_browser(),
					array(
						'Internet Explorer',
						'Firefox',
						'Safari',
						'Chrome',
						'Edge',
						'Opera',
						'Netscape',
						'Maxthon',
						'Yandex',
						'Konqueror',
						'UCBrowser',
					)
				);
				break;

			default:
				$result = strpos( $_SERVER['HTTP_USER_AGENT'], $value ) !== false;
		}

		return $this->check_by_operator( $operator, true, $result );
	}

	/**
	 * Check operating system
	 *
	 * @param $operator
	 * @param $value
	 *
	 * @return bool
	 */
	private function technology_operating_system( $operator, $value ) {
		switch ( $value ) {
			case 'windows':
				$result = WASP_Helper::get_os() === 'Windows';
				break;
			case 'mac-os':
				$result = WASP_Helper::get_os() === 'Mac OS';
				break;
			case 'gnu-linux':
				$result = WASP_Helper::get_os() === 'Linux' || WASP_Helper::get_os() === 'Ubuntu';
				break;
			case 'google-android':
				$result = WASP_Helper::get_os() === 'Android';
				break;
			case 'google-chrome-os':
				$result = WASP_Helper::get_os() === 'Chrome OS';
				break;
			case 'other':
				$result = ! in_array(
					WASP_Helper::get_os(),
					array(
						'Windows',
						'Mac OS',
						'Linux',
						'Ubuntu',
						'iPhone',
						'iPod',
						'iPad',
						'Android',
						'Chrome OS',
						'BlackBerry',
						'Mobile',
					)
				);
				break;

			default:
				$result = strpos( $_SERVER['HTTP_USER_AGENT'], $value ) !== false;
		}

		return $this->check_by_operator( $operator, true, $result );
	}

	/**
	 * Check device type
	 *
	 * @param $operator
	 * @param $value
	 *
	 * @return bool
	 */
	private function technology_device_type( $operator, $value ) {
		switch ( $value ) {
			case 'mobile':
				$result = wp_is_mobile() || WASP_Helper::get_device() === 'Mobile';
				break;
			case 'tabled':
				$result = WASP_Helper::get_device() === 'Tabled';
				break;
			case 'phone':
				$result = wp_is_mobile() || WASP_Helper::get_device() === 'Mobile' || WASP_Helper::get_os() === 'iPhone';
				break;
			case 'desktop':
				$result = WASP_Helper::get_device() === 'Desktop' || WASP_Helper::get_os() === 'Windows' || WASP_Helper::get_os() === 'Linux' || WASP_Helper::get_os() === 'Ubuntu' || WASP_Helper::get_os() === 'Mac OS';
				break;

			default:
				$result = strpos( $_SERVER['HTTP_USER_AGENT'], $value ) !== false;
		}

		return $this->check_by_operator( $operator, true, $result );
	}

	/**
	 * Check user country
	 *
	 * @param $operator
	 * @param $value
	 *
	 * @return boolean
	 */
	private function auditory_country( $operator, $value ) {
		$country = WASP_Core::app()->get_geo_object()->get_iso_country_code_by_ip();
		if ( $country ) {
			return $this->check_by_operator( $operator, $value, $country );
		}

		return false;
	}

	/**
	 * Check the number of pages viewed by the user per session
	 *
	 * @param $operator
	 * @param $value
	 *
	 * @return boolean
	 */
	private function auditory_viewing( $operator, $value ) {
		$number = WASP_Core::app()->get_geo_object()->get_user_page_view_count();
		if ( $number ) {
			return $this->check_by_operator( $operator, $value, $number );
		}

		return false;
	}

	/**
	 * Check the time interval during which the user entered
	 *
	 * @param $operator
	 * @param $value
	 *
	 * @return boolean
	 */
	private function auditory_attendance( $operator, $value ) {
		$start_timestamp = round( $this->get_date_timestamp( $value->start ) / 1000 );
		$end_timestamp   = round( $this->get_date_timestamp( $value->end ) / 1000 );

		$checked_time = current_time( 'timestamp' );

		return $this->check_by_operator( 'between', $start_timestamp, $checked_time, $end_timestamp );
	}

	/**
	 * Check the total number of sessions opened by the user
	 *
	 * @param $operator
	 * @param $value
	 *
	 * @return boolean
	 */
	private function auditory_visits( $operator, $value ) {
		$number = WASP_Core::app()->get_geo_object()->get_user_visits_count();
		if ( $number ) {
			if ( 'between' == $operator ) {
				$value1 = $value->start;
				$value2 = $value->end;
			} else {
				$value1 = $value;
				$value2 = $value;
			}

			return $this->check_by_operator( $operator, $value1, $number, $value2 );
		}

		return false;
	}

	/**
	 * Импортируем сниппеты из архива. Массовый импорт.
	 *
	 * @param $result
	 * @param $extention
	 * @param $mime_type
	 * @param $import_file
	 * @param $dup_action
	 *
	 * @return mixed
	 */
	public function import_snippet( $result, $extention, $mime_type, $import_file, $dup_action ) {
		if ( 'zip' === $extention || 'application/zip' === $mime_type ) {
			$zip = new ZipArchive;

			if ( true === $zip->open( $import_file ) ) {
				$result     = array();
				$upload_dir = wp_get_upload_dir();
				$unzip_path = $upload_dir['path'] . '/winp_folder';

				if ( is_dir( $unzip_path ) ) {
					array_map( 'unlink', glob( "$unzip_path/*.*" ) );
					rmdir( $unzip_path );
				}
				mkdir( $unzip_path );

				$zip->extractTo( $unzip_path );
				$zip->close();

				$dir = opendir( $unzip_path );
				while ( $file = readdir( $dir ) ) {
					$filepath = $unzip_path . '/' . $file;
					if ( is_file( $filepath ) && filesize( $filepath ) ) {
						$_result = class_exists( 'WINP_Import_Snippet' )
							? ( new WINP_Import_Snippet )->importSnippet( $filepath, $dup_action )
							: array();
						if ( $_result ) {
							$result = array_merge( $result, $_result );
						}
					}
				}
			}
		}

		return $result;
	}

	/**
	 * Get custom snippet "where use" value
	 *
	 * @param $value
	 * @param $post_id
	 *
	 * @return mixed
	 */
	public function where_use( $value, $post_id ) {
		$snippet_custom_name = WINP_Helper::getMetaOption( $post_id, 'snippet_custom_name', '' );
		if ( $snippet_custom_name ) {
			return '[' . $snippet_custom_name . ']';
		}

		return $value;
	}

	/**
	 * Update shortcode data
	 *
	 * Устанавливаем кастомное имя сниппета, если оно было задано, для автоматической вставки в редактор.
	 * Так же удалем параметр id для редактора tinymce, т.к. там он там не нужен
	 *
	 * @param array $data
	 * @param boolean $tinymce
	 *
	 * @return mixed
	 */
	public function update_shortcode_data( $data, $tinymce ) {
		if ( $data ) {
			foreach ( $data as $key => $tags ) {
				$snippet_custom_name = WINP_Helper::getMetaOption( $tags['id'], 'snippet_custom_name', '' );

				if ( $snippet_custom_name ) {
					$data[ $key ]['name'] = $snippet_custom_name;

					if ( $tinymce ) {
						unset( $data[ $key ]['id'] );
					}
				}
			}
		}

		return $data;
	}

	/**
	 * Если задано кастомное имя сниппета, то атрибу id не нужно выводить.
	 * Этот метод работает только для редактора gutenberg.
	 *
	 * @param $attributes
	 * @param $snipped_id
	 *
	 * @return mixed
	 */
	public function shortcode_attributes( $attributes, $snipped_id ) {
		if ( WINP_Helper::getMetaOption( $snipped_id, 'snippet_custom_name', '' ) ) {
			return '';
		}

		return $attributes;
	}

	/**
	 * Изменяем имя шорткода, если было задано кастомное имя.
	 * Этот метод работает только для редактора gutenberg.
	 *
	 * @param $name
	 * @param $snipped_id
	 *
	 * @return mixed
	 */
	public function shortcode_name( $name, $snipped_id ) {
		$snippet_custom_name = WINP_Helper::getMetaOption( $snipped_id, 'snippet_custom_name', '' );

		if ( $snippet_custom_name ) {
			return $snippet_custom_name;
		}

		return $name;
	}

	/**
	 * Get unique shortcode name
	 *
	 * Используется для проверки / получения уникального имени шорткода,
	 * когда пользователь вводит это имя на странице редактирования шорткода
	 *
	 * @param $custom_name
	 * @param $post_id
	 *
	 * @return string
	 */
	private function get_unique_shortcode_name( $custom_name, $post_id ) {
		global $wpdb;

		if ( '' === trim( $custom_name ) ) {
			return $custom_name;
		}

		$new_custom_name     = preg_replace( '/[^A-Za-z0-9_\-]/', '', $custom_name );
		$index               = 2;
		$meta_key            = WINP_Plugin::app()->getPrefix() . 'snippet_custom_name';
		$current_custom_name = WINP_Helper::getMetaOption( $post_id, 'snippet_custom_name', '' );

		while (
			shortcode_exists( $new_custom_name )
			&& $current_custom_name != $new_custom_name
			|| $wpdb->get_var( "SELECT meta_id FROM $wpdb->postmeta WHERE meta_key = '$meta_key' AND meta_value = '$new_custom_name' AND post_id <> $post_id LIMIT 1" )
		) {
			$new_custom_name = $custom_name . '-' . $index ++;
		}

		return $new_custom_name;
	}

	/**
	 * Обрабатываем сохранение кастомного имени шорткода
	 *
	 * @param $post_id
	 */
	public function on_saving_form( $post_id ) {
		$snippet_custom_name = WINP_Plugin::app()->request->post(
			WINP_Plugin::app()->getPrefix() . 'snippet_custom_name',
			'',
			'sanitize_text_field'
		);
		$snippet_custom_name = $this->get_unique_shortcode_name( $snippet_custom_name, $post_id );
		$_POST[ WINP_Plugin::app()->getPrefix() . 'snippet_custom_name' ] = $snippet_custom_name;
		WINP_Helper::updateMetaOption( $post_id, 'snippet_custom_name', $snippet_custom_name );
	}

}