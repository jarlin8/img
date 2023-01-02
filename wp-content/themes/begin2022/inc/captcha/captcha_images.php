<?php
include("shared.php");
class CaptchaSecurityImages {
	var $font = 'monofont.ttf';
	function GenerateImage( $width, $height, $code ) {
		$font_path = realpath( '.' );
		putenv( 'GDFONTPATH=' . $font_path );
		// 字大小 */
		$font_size = $height * 0.65;
		$image = @imagecreate( $width, $height ) or die( 'Cannot initialize new GD image stream' );
		// 颜色*/
		$background_color = imagecolorallocate( $image, 255, 255, 255 );
		$text_color = imagecolorallocate( $image, 38, 38, 38 );
		$noise_color = imagecolorallocate( $image, 114, 114, 114 );
		// 线
		for( $i = 0; $i<( $width*$height)/260; $i++ ) {
			imageline( $image, mt_rand( 0, $width ), mt_rand( 0, $height ), mt_rand( 0, $width ), mt_rand( 0, $height ), $noise_color );
		}
		// 文字
		$textbox = imagettfbbox( $font_size, 0, $this->font, $code ) or die( '出错了' );
		$x = ( $width - $textbox[4] )/2;
		$y = ( $height - $textbox[5] )/2;
		imagettftext( $image, $font_size, 0, $x, $y, $text_color, $this->font , $code ) or die( '出错了' );
		/* 输出 */
		ob_clean();
		header( 'Content-Type: image/jpeg' );
		imagejpeg( $image );
		imagedestroy( $image );
	}
}

$width = isset( $_GET['width'] ) ? $_GET['width'] : '120';
$height = isset( $_GET['height'] ) ? $_GET['height'] : '35';
$code = isset( $_GET['code'] ) ? $_GET['code'] : '';
if ( isset( $_GET['code'] ) ) {
	$captcha = new CaptchaSecurityImages();
	$captcha-> GenerateImage( $width, $height, be_str_decrypt( $code ) );
}