<?php

/**
 * Geo snippet
 *
 * Класс для работы с геоданными.
 * Позволяет скачивать свежие базы по расписанию и в ручном режиме.
 * Так же занимаетя учётом данных активности пользователей с последующей её обработкой в связке с геоданными.
 *
 * @author        Webcraftic <wordpress.webraftic@gmail.com>
 * @copyright (c) 11.02.2019, Webcraftic
 * @version       1.0
 */

/**
 * Class WASP_Geo_Snippet
 */
class WASP_Geo_Snippet {
	const WASP_GEO_DB_FILENAME_DEFAULT = 'GeoLite2-City.mmdb';
	const WASP_GEO_DB_UPDATE_HOOK      = 'wbcr_inp_update_geo_db';
	const WASP_GEO_USER_PAGE_VIEWS     = 'wbcr_inp_user_page_views';
	const WASP_GEO_USER_VISITS         = 'wbcr_inp_user_visits';
	const WASP_DB_UPDATE_DAYS          = 30;

	/**
	 * WASP_Geo_Snippet constructor.
	 */
	public function __construct() {
	}

	/**
	 * Register hooks
	 */
	public function register_hooks() {
		add_action( 'cron_schedules', array( $this, 'cron_schedules' ) );
		add_action( 'init', array( $this, 'wp_init' ) );
		add_action( 'wp', array( $this, 'wp_hook' ) );
		add_action( self::WASP_GEO_DB_UPDATE_HOOK, array( $this, 'update_databases' ) );
		register_deactivation_hook( __FILE__, array( 'WASP_GEO_Snippet', 'on_deactivation' ) );
	}

	/**
	 * Add new schedules interval
	 *
	 * @param $schedules
	 *
	 * @return mixed
	 */
	public function cron_schedules( $schedules ) {
		$schedules ['monthly'] = array(
			'interval' => 2635200,
			'display'  => 'Once Monthly',
		);

		return $schedules;
	}

	/**
	 * Регистрация ежемесячного события планировщика
	 */
	public function wp_init() {
		$timestamp = wp_next_scheduled( self::WASP_GEO_DB_UPDATE_HOOK );
		if ( false == $timestamp ) {
			wp_schedule_event( time(), 'monthly', self::WASP_GEO_DB_UPDATE_HOOK );
		}
	}

	/**
	 * Set cookie
	 *
	 * @param $param_name
	 * @param $value
	 * @param $expiration
	 */
	private function set_cookie( $param_name, $value, $expiration ) {
		$is_secure = is_ssl() && 'https' === parse_url( get_option( 'home' ), PHP_URL_SCHEME );

		setcookie( $param_name, $value, $expiration, COOKIEPATH, COOKIE_DOMAIN, $is_secure );
		if ( COOKIEPATH != SITECOOKIEPATH ) {
			setcookie( $param_name, $value, $expiration, SITECOOKIEPATH, COOKIE_DOMAIN, $is_secure );
		}
	}

	/**
	 * Данный метод учитывает посещения пользователей и сохраняет их в куках
	 */
	public function wp_hook() {
		if ( ! is_admin() ) {

			if ( is_singular() ) {
				// Количество просмотров страниц в течении одной сессии для пользователя
				$param_page_views = self::WASP_GEO_USER_PAGE_VIEWS;
				$expiration       = time() + 7 * DAY_IN_SECONDS;

				$value = ! isset( $_COOKIE[ $param_page_views ] ) ? 1 : $_COOKIE[ $param_page_views ] + 1;
				$this->set_cookie( $param_page_views, $value, $expiration );
			}

			// Идентификатор начала сессии пользователя
			$param_session      = 'wbcr_inp_session_id';
			$expiration_session = 0;

			// Количество визитов (сессий) одного пользователя
			$param_visits      = self::WASP_GEO_USER_VISITS;
			$expiration_visits = time() + 2 * MONTH_IN_SECONDS;

			if ( ! isset( $_COOKIE[ $param_session ] ) ) {
				$this->set_cookie( $param_session, 1, $expiration_session );

				$value = ! isset( $_COOKIE[ $param_visits ] ) ? 1 : $_COOKIE[ $param_visits ] + 1;
				$this->set_cookie( $param_visits, $value, $expiration_visits );
			}
		}
	}

	/**
	 * Deactivate scheduled hook
	 */
	public static function on_deactivation() {
		wp_clear_scheduled_hook( self::WASP_GEO_DB_UPDATE_HOOK );
	}

	/**
	 * Get default path to db file
	 *
	 * @return string
	 */
	public function get_default_db_path_name() {
		return WP_CONTENT_DIR . '/uploads/' . self::WASP_GEO_DB_FILENAME_DEFAULT;
	}

	/**
	 * Get database path name from settings
	 *
	 * @return string
	 */
	public function get_db_path_name() {
		$path = WINP_Plugin::app()->getOption( 'geo_db_path', '' );

		if ( empty( $path ) ) {
			$path = $this->get_default_db_path_name();
		} else {
			$path = ABSPATH . str_replace( ABSPATH, $path, '' );
		}

		return $path;
	}

	/**
	 * Get Maxmind db path from settings
	 *
	 * @return bool|string
	 */
	public function get_maxmind_db() {
		if (
			'maxmind' == WINP_Plugin::app()->getOption( 'geo_db' )
			&& 'enabled' == WINP_Plugin::app()->getOption( 'geo_db_updates' )
			&& $this->db_file_exist()
		) {
			return $this->get_db_path_name();
		}

		return false;
	}

	/**
	 * Check geo db file exist
	 *
	 * @return bool
	 */
	public function db_file_exist() {
		$path = $this->get_db_path_name();

		return file_exists( $path );
	}

	/*
	 * Get client IP address
	 */
	function get_client_ip_address() {
		$server_addr = isset( $_SERVER ['SERVER_ADDR'] ) ? $_SERVER ['SERVER_ADDR'] : '';
		foreach (
			array(
				'HTTP_CF_CONNECTING_IP',
				'HTTP_CLIENT_IP',
				'HTTP_X_FORWARDED_FOR',
				'HTTP_X_FORWARDED',
				'HTTP_X_CLUSTER_CLIENT_IP',
				'HTTP_FORWARDED_FOR',
				'HTTP_FORWARDED',
				'REMOTE_ADDR',
			) as $key
		) {
			if ( array_key_exists( $key, $_SERVER ) === true ) {
				foreach ( explode( ',', $_SERVER[ $key ] ) as $ip ) {
					$ip = str_replace( 'for=', '', $ip );
					$ip = trim( $ip );
					if ( '' != $server_addr && $ip == $server_addr ) {
						continue 2;
					}

					return $ip;
				}
			}
		}

		return '';
	}

	/**
	 * Update Webnet77 database
	 *
	 * Данный метод скачивает новый файл базы данных webnet
	 * Результат работы сохраняется в файл ip2country.log
	 */
	public function update_webnet_77_db() {
		require_once WASP_PLUGIN_DIR . '/includes/geo/process_csv.php';
		require_once WASP_PLUGIN_DIR . '/includes/geo/process6_csv.php';

		if ( is_multisite() && ! is_main_site() ) {
			return;
		}

		$file_path = WASP_PLUGIN_DIR . '/includes/geo';

		if ( ! is_writable( $file_path ) ) {
			return;
		}
		if ( ! is_writable( $file_path . '/ip2country.dat' ) ) {
			return;
		}
		if ( ! is_writable( $file_path . '/ip2country6.dat' ) ) {
			return;
		}

		ob_start();
		echo date( 'Y-m-d H:i:s', time() ), " WEBNET77 IP DB UPDATE START\n\n";

		echo "IPv4\n";
		echo 'ip2country.dat age: ', intval( ( time() - filemtime( $file_path . '/ip2country.dat' ) ) / 24 / 3600 ), " days\n";

		if (
			! file_exists( $file_path . '/ip2country.dat' )
			|| filemtime( $file_path . '/ip2country.dat' ) + self::WASP_DB_UPDATE_DAYS * 24 * 3600 < time()
		) {
			echo "Updating...\n";
			$response = wp_remote_get( 'http://software77.net/geo-ip/?DL=2' );
			if ( is_array( $response ) ) {
				file_put_contents( $file_path . '/ip2country.zip', wp_remote_retrieve_body( $response ) );
				$zip = new ZipArchive;
				$res = $zip->open( $file_path . '/ip2country.zip' );
				if ( true === $res ) {
					$zip->extractTo( $file_path );
					$zip->close();
					if ( file_exists( $file_path . '/IpToCountry.csv' ) ) {
						process_csv( $file_path . '/IpToCountry.csv' );
					} else {
						echo "Error: file IpToCountry.csv not found\n";
					}
				} else {
					echo "Error unzipping ip2country.zip\n";
				}
			}
		}

		echo "\nIPv6\n";
		echo 'ip2country6.dat age: ', intval( ( time() - filemtime( $file_path . '/ip2country6.dat' ) ) / 24 / 3600 ), " days\n";

		if (
			! file_exists( $file_path . '/ip2country6.dat' )
			|| filemtime( $file_path . '/ip2country6.dat' ) + self::WASP_DB_UPDATE_DAYS * 24 * 3600 < time()
		) {
			echo "Updating...\n";
			$response = wp_remote_get( 'http://software77.net/geo-ip/?DL=7' );
			if ( is_array( $response ) ) {
				file_put_contents( $file_path . '/IpToCountry.6R.csv.gz', wp_remote_retrieve_body( $response ) );
				$gz = gzopen( $file_path . '/IpToCountry.6R.csv.gz', 'rb' );
				if ( $gz ) {
					$dest = fopen( $file_path . '/IpToCountry.6R.csv', 'wb' );
					if ( $dest ) {
						stream_copy_to_stream( $gz, $dest );
						fclose( $dest );

						if ( file_exists( $file_path . '/IpToCountry.6R.csv' ) ) {
							process6_csv( $file_path . '/IpToCountry.6R.csv' );
						} else {
							echo "Error: File IpToCountry.6R.csv not found\n";
						}
					} else {
						echo 'Error: Could not open file IpToCountry.6R.csv\n';
					}
					gzclose( $gz );
				} else {
					echo 'Error: Could not open file IpToCountry.6R.csv.gz\n';
				}
			}
		}

		echo "\n", date( 'Y-m-d H:i:s', time() ), " WEBNET77 IP DB UPDATE END\n\n\n";
		$log = ob_get_clean();
		file_put_contents( $file_path . '/ip2country.log', $log, FILE_APPEND );
	}

	/**
	 * Update maxmind database
	 *
	 * Данный метод скачивает новый файл базы данных maxmind
	 * Результат работы сохраняется в файл ip2country.log
	 *
	 * @return bool|string
	 */
	public function update_maxmind_db() {
		require_once WASP_PLUGIN_DIR . '/includes/geo/maxmind/autoload.php';

		if ( is_multisite() && ! is_main_site() ) {
			return false;
		}

		if ( 'webnet' == WINP_Plugin::app()->getOption( 'geo_db', 'webnet' ) ) {
			return false;
		}

		$db_file   = $this->get_db_path_name();
		$file_path = dirname( $db_file );

		if ( ! is_dir( $file_path ) ) {
			@mkdir( $file_path, 0755, true );
			file_put_contents( $file_path . '/index.php', "<?php header ('Status: 404 Not found'); ?" . ">\nNot found" );
		}

		if ( ! is_writable( $file_path ) ) {
			return false;
		}

		$error_message = '';

		ob_start();
		echo date( 'Y-m-d H:i:s', time() ), " MAXMIND IP DB UPDATE START\n\n";

		if ( file_exists( $db_file . '.gz' ) ) {
			$tmp_file = $db_file . '.gz';
			$out_file = $db_file;

			echo "Trying to unpack $tmp_file\n";

			$gzip_file_handle = gzopen( $tmp_file, 'r' );
			$db_file_handle   = fopen( $out_file, 'w' );

			if ( $gzip_file_handle ) {
				if ( $db_file_handle ) {
					while ( ( $string = gzread( $gzip_file_handle, 4096 ) ) != false ) {
						fwrite( $db_file_handle, $string, strlen( $string ) );
					}

					gzclose( $gzip_file_handle );
					fclose( $db_file_handle );
					unlink( $tmp_file );
					echo "Unpacked $db_file\n";
				} else {
					echo "Error: database file $out_file could not be written\n";
				}
			} else {
				echo "Error: file $tmp_file could not be opened for reading\n";
			}
		}

		if ( ! file_exists( $db_file ) ) {
			echo $db_file, " not found\n";
		} else {
			echo $db_file, ' age: ', intval( ( time() - filemtime( $db_file ) ) / 24 / 3600 ), " days\n";
		}

		if ( ! file_exists( $db_file ) || filemtime( $db_file ) + self::WASP_DB_UPDATE_DAYS * 24 * 3600 < time() ) {
			require_once( ABSPATH . '/wp-admin/includes/file.php' );

			$download_url = 'http://geolite.maxmind.com/download/geoip/database/GeoLite2-City.mmdb.gz';

			echo "Updating...\n";

			$tmp_file = download_url( $download_url );

			if ( ! is_wp_error( $tmp_file ) ) {
				$gzip_file_handle = gzopen( $tmp_file, 'r' );
				$db_file_handle   = fopen( $db_file, 'w' );

				if ( $gzip_file_handle ) {
					if ( $db_file_handle ) {
						while ( ( $string = gzread( $gzip_file_handle, 4096 ) ) != false ) {
							fwrite( $db_file_handle, $string, strlen( $string ) );
						}

						gzclose( $gzip_file_handle );
						fclose( $db_file_handle );
					} else {
						$error_message = "Database file $db_file could not be written";
					}
				} else {
					$error_message = "Downloaded file $tmp_file could not be opened for reading";
				}
			} else {
				$error_message = 'Download status: ' . $tmp_file->get_error_message();
			}

			if ( '' != $error_message ) {
				echo 'Error: ', $error_message, "\n";
			}

			@unlink( $tmp_file );
		}

		echo "\n", date( 'Y-m-d H:i:s', time() ), " MAXMIND IP DB UPDATE END\n\n\n";
		$log = ob_get_clean();
		file_put_contents( WASP_PLUGIN_DIR . '/includes/geo/ip2country.log', $log, FILE_APPEND );

		return $error_message;
	}

	/**
	 * Update all databases
	 */
	public function update_databases() {
		if (
			is_multisite() && ! is_main_site()
			|| 'enabled' != WINP_Plugin::app()->getOption( 'geo_db_updates' )
		) {
			return;
		}

		$this->update_webnet_77_db();
		if ( 'maxmind' == WINP_Plugin::app()->getOption( 'geo_db', 'webnet' ) ) {
			$this->update_maxmind_db();
		}
	}

	/**
	 * Get country code by client ip
	 *
	 * @return null|string
	 */
	public function get_iso_country_code_by_ip() {
		require_once WASP_PLUGIN_DIR . '/includes/geo/Ip2Country.php';

		$client_ip_address = $this->get_client_ip_address();

		return ip_to_country( $client_ip_address );
	}

	/**
	 * Get user page view count from cookie
	 *
	 * @return int
	 */
	public function get_user_page_view_count() {
		return isset( $_COOKIE[ self::WASP_GEO_USER_PAGE_VIEWS ] ) ? $_COOKIE[ self::WASP_GEO_USER_PAGE_VIEWS ] : 0;
	}

	/**
	 * Get user visits count from cookie
	 *
	 * @return int
	 */
	public function get_user_visits_count() {
		return isset( $_COOKIE[ self::WASP_GEO_USER_VISITS ] ) ? $_COOKIE[ self::WASP_GEO_USER_VISITS ] : 0;
	}

}
