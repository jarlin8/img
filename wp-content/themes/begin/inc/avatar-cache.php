<?php
// 头像缓存
function begin_avatar( $email = 'aaaaa@aaaaaa.com', $size = '96', $default = '', $alt = '', $letter = '', $headers = '') {
	$f = md5( strtolower( $email ) );
	$upload_dir = wp_upload_dir();
	$poster_dir = $upload_dir['basedir'].'/avatar';
	if (!is_dir($poster_dir)){
		wp_mkdir_p($poster_dir);
	}

	$a = $upload_dir['baseurl'] . '/avatar/'. $f . $size . '.png';
	$e = $upload_dir['basedir'] . '/avatar/' . $f . $size . '.png';
	$d = $upload_dir['basedir'] . '/avatar/' . $f . '-d.png';

	if ($default=='')
	$random_avata = explode(',' , zm_get_option('random_avatar_url'));
	$random_avata_array = array_rand($random_avata);
	$src = $random_avata[$random_avata_array];
	$default = $src;
	$t = 2592000;
	if ( !is_file($e) || (time() - filemtime($e)) > $t ) {
		if ( !is_file($d) || (time() - filemtime($d)) > $t ) {
			$uri = zm_get_option( 'gravatar_origin' ) . $f . '?d=404';
			$headers = @get_headers($uri);
			if (!preg_match("|200|", @$headers[0])) {
				$handle = fopen($d, 'w');
				fclose($handle);
				$a = $default;
			} else {
				$r = get_option('avatar_rating');
				$g = zm_get_option( 'gravatar_origin' ) . $f. '?s='. $size. '&r=' . $r;
				copy($g, $e);
			}

		} else {
			if (!zm_get_option('avatar_url') || (zm_get_option("avatar_url") == 'letter_img')) {
				$a = zm_get_option( 'logo_small_b' );
				$letter = ' letter';
			}
			$a = $default;
		}
	}

	if (!zm_get_option('avatar_url') || (zm_get_option("avatar_url") == 'letter_img')) {
		if ( !zm_get_option( 'avatar_load' ) ) {
			$avatar = "<img alt='{$alt}' src='{$a}' class='avatar avatar-{$size} photo".$letter."' height='{$size}' width='{$size}' />";
		} else {
			$avatar = "<img alt='{$alt}' src='data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=' data-original='{$a}' class='avatar avatar-{$size} photo".$letter."' height='{$size}' width='{$size}' />";
		}
	}

	if (zm_get_option('avatar_url') == 'rand_img') {
		if ( !zm_get_option( 'avatar_load' ) ) {
			$avatar = "<img alt='{$alt}' src='{$a}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
		} else {
			$avatar = "<img alt='{$alt}' src='data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=' data-original='{$a}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
		}
	}

	if ( get_option( 'show_avatars' ) ) {
		return apply_filters( 'begin_avatar', $avatar, $email, $size, $default, $alt );
	}
}