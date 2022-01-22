<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// gravatar头像调用
function cn_avatar($avatar) {
	$avatar = preg_replace( '/.*\/avatar\/(.*)\?s=([\d]+)&.*/','<img src="https://cn.gravatar.com/avatar/$1?s=$2&d=mm" alt="avatar" class="avatar avatar-$2" height="$2" width="$2">', $avatar );
	return $avatar;
}

function ssl_avatar($avatar) {
	$avatar = preg_replace( '/.*\/avatar\/(.*)\?s=([\d]+)&.*/','<img src="https://secure.gravatar.com/avatar/$1?s=$2&d=mm" alt="avatar" class="avatar avatar-$2" height="$2" width="$2">', $avatar );
	return $avatar;
}

if (zm_get_option('no') !== 'no') :
	if ( !zm_get_option( 'gravatar_url' ) || ( zm_get_option( "gravatar_url") == 'zh' ) ) {
		add_filter( 'get_avatar', function ( $avatar ) { return str_replace( ['www.gravatar.com/avatar/', '0.gravatar.com/avatar/', '1.gravatar.com/avatar/', '2.gravatar.com/avatar/', 'secure.gravatar.com/avatar/', 'cn.gravatar.com/avatar/'], ''. zm_get_option( 'zh_url' ) .'', $avatar ); } );
	}

	if ( zm_get_option( 'gravatar_url' ) == 'cn' ) {
		add_filter( 'get_avatar', 'cn_avatar' );
	}

	if ( zm_get_option( 'gravatar_url' ) == 'ssl' ) {
		add_filter( 'get_avatar', 'ssl_avatar' );
	}
endif;

// 默认头像
if ( !zm_get_option( 'default_avatar_m' ) || ( zm_get_option( 'default_avatar_m' ) == 'default_avatar_f' ) ) {
	add_filter( 'avatar_defaults', 'be_default_avatar', 10, 1 );
}

if ( zm_get_option( 'default_avatar_m' ) == 'default_avatar_r' ) {
	add_filter( 'pre_option_avatar_default', 'be_random_default_avatar' );
}
function be_default_avatar( $avatar_defaults ) {
	$beavatar = zm_get_option( 'default_avatar' );
	$avatar_defaults[ $beavatar ] = '自定义';
	return $avatar_defaults;
}

function be_random_default_avatar ( $value ) {
	$random_img = explode( ',' , zm_get_option( 'random_avatar_url' ) );
	$random_img_array = array_rand( $random_img );
	return $random_img[$random_img_array];
}


// 后台禁止头像
if ( zm_get_option('ban_avatars') && is_admin() ) {
	add_filter( 'get_avatar' , 'ban_avatar' , 1 , 1 );
}
function ban_avatar( $avatar ) {
	$avatar = "";
}

function be_avatar_comment() {
	global $comment;
	if ( !zm_get_option( 'avatar_load' ) ) {
		echo get_avatar( $comment, 96, '', get_comment_author() );
	} else {
		echo '<img class="avatar photo" src="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=" alt="'. get_comment_author() .'" width="96" height="96" data-original="' . preg_replace(array('/^.+(src=)(\"|\')/i', '/(\"|\')\sclass=(\"|\').+$/i'), array('', ''), get_avatar( $comment, 96, '', get_comment_author() )) . '" />';
	}
}

function be_avatar_authors() {
	global $author;
	if ( !zm_get_option( 'avatar_load' ) ) {
		echo get_avatar( $author->user_email, $size = 96, '', $author->display_name );
	} else {
		echo '<img class="avatar photo" src="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=" alt="'. $author->display_name .'" width="96" height="96" data-original="' . preg_replace(array('/^.+(src=)(\"|\')/i', '/(\"|\')\sclass=(\"|\').+$/i'), array('', ''), get_avatar( $author->user_email, $size = 96, '', $author->display_name )) . '" />';
	}
}

function be_avatar_author() {
	if ( !zm_get_option( 'avatar_load' ) ) {
		echo get_avatar( get_the_author_meta('user_email'), 96, '', get_the_author() );
	} else {
		echo '<img class="avatar photo" src="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=" alt="'. get_the_author() .'" width="96" height="96" data-original="' . preg_replace(array('/^.+(src=)(\"|\')/i', '/(\"|\')\sclass=(\"|\').+$/i'), array('', ''), get_avatar( get_the_author_meta('user_email'), 96, '', get_the_author() )) . '" />';
	}
}

function be_avatar_user() {
	global $userdata, $user_identity;
	if ( !zm_get_option( 'avatar_load' ) ) {
		echo get_avatar($userdata->ID, 96, '', $user_identity);
	} else {
		echo '<img class="avatar photo" src="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=" alt="'. get_the_author() .'" width="96" height="96" data-original="' . preg_replace(array('/^.+(src=)(\"|\')/i', '/(\"|\')\sclass=(\"|\').+$/i'), array('', ''), get_avatar($userdata->ID, 96, '', $user_identity)) . '" />';
	}
}