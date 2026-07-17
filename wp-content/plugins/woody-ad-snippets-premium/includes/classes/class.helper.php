<?php
/**
 * Helper
 *
 * @author        Webcraftic <wordpress.webraftic@gmail.com>
 * @copyright (c) 08.02.2019, Webcraftic
 * @version       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WASP_Helper
 */
class WASP_Helper {

	/**
	 * Get operating system
	 *
	 * @return mixed|string
	 */
	public static function get_os() {
		$user_agent  = $_SERVER['HTTP_USER_AGENT'];
		$os_platform = 'Unknown';

		$os_array = array(
			'/windows nt 10/i'      => 'Windows',
			'/windows nt 6.3/i'     => 'Windows',
			'/windows nt 6.2/i'     => 'Windows',
			'/windows nt 6.1/i'     => 'Windows',
			'/windows nt 6.0/i'     => 'Windows',
			'/windows nt 5.2/i'     => 'Windows',
			'/windows nt 5.1/i'     => 'Windows',
			'/windows xp/i'         => 'Windows',
			'/windows nt 5.0/i'     => 'Windows',
			'/windows me/i'         => 'Windows',
			'/win98/i'              => 'Windows',
			'/win95/i'              => 'Windows',
			'/win16/i'              => 'Windows',
			'/macintosh|mac os x/i' => 'Mac OS',
			'/mac_powerpc/i'        => 'Mac OS',
			'/linux/i'              => 'Linux',
			'/ubuntu/i'             => 'Ubuntu',
			'/iphone/i'             => 'iPhone',
			'/ipod/i'               => 'iPod',
			'/ipad/i'               => 'iPad',
			'/android/i'            => 'Android',
			'/cros/i'               => 'Chrome OS',
			'/blackberry/i'         => 'BlackBerry',
			'/webos/i'              => 'Mobile',
		);

		foreach ( $os_array as $regex => $value ) {
			if ( preg_match( $regex, $user_agent ) ) {
				$os_platform = $value;
			}
		}

		return $os_platform;
	}

	/**
	 * Get browser
	 *
	 * @return mixed|string
	 */
	public static function get_browser() {
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		$browser    = 'Unknown';

		$browsers = array(
			'/msie/i'      => 'Internet Explorer',
			'/firefox/i'   => 'Firefox',
			'/safari/i'    => 'Safari',
			'/chrome/i'    => 'Chrome',
			'/edge/i'      => 'Edge',
			'/opera/i'     => 'Opera',
			'/netscape/i'  => 'Netscape',
			'/maxthon/i'   => 'Maxthon',
			'/yabrowser/i' => 'Yandex',
			'/konqueror/i' => 'Konqueror',
			'/ucbrowser/i' => 'UCBrowser',
			'/ubrowser/i'  => 'UCBrowser',
		);

		foreach ( $browsers as $regex => $value ) {
			if ( preg_match( $regex, $user_agent ) ) {
				$browser = $value;
			}
		}

		return $browser;
	}

	/**
	 * Get device
	 *
	 * @return string
	 */
	public static function get_device() {
		$user_agent = $_SERVER['HTTP_USER_AGENT'];

		$devices_types = array(
			'Desktop' => array(
				'msie 10',
				'msie 9',
				'msie 8',
				'windows.*firefox',
				'windows.*chrome',
				'x11.*chrome',
				'x11.*firefox',
				'macintosh.*chrome',
				'macintosh.*firefox',
				'opera',
			),
			'Tablet'  => array( 'tablet', 'android', 'ipad', 'tablet.*firefox' ),
			'Mobile'  => array( 'mobile', 'android.*mobile', 'iphone', 'ipod', 'opera mobi', 'opera mini' ),
			'Bot'     => array(
				'googlebot',
				'mediapartners-google',
				'adsbot-google',
				'duckduckbot',
				'msnbot',
				'bingbot',
				'ask',
				'facebook',
				'yahoo',
				'addthis',
			),
		);
		foreach ( $devices_types as $device_type => $devices ) {
			foreach ( $devices as $device ) {
				if ( preg_match( '/' . $device . '/i', $user_agent ) ) {
					return $device_type;
				}
			}
		}

		return 'Unknown';
	}

}
