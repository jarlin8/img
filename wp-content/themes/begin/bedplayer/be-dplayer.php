<?php
define( 'bedpversion', '1.0' );
class Be_Dplayer {
	public function __construct() {
		add_shortcode( 'mine_video', array( $this, 'bedplayer_shortcode' ) );
		add_shortcode( 'be_dplayer', array( $this, 'bedplayer_shortcode' ) );
	}

	public function be_get_player($id){
		$pfs = array(
			array(
				'id'   => 'mp4dplayer',
				'name' => 'Mp4',
				'api'  => 'dplayer',
			),

			array(
				'id'   => 'm3u8_dplayer',
				'name' => 'M3U8',
				'api'  => 'dplayer',
			),

			array(
				'id'   => 'm3u8dplayer',
				'name' => 'M3U8',
				'api'  => 'dplayer',
			)
		);

		if ( is_array( $pfs ) ){
			foreach( $pfs as $pf ){
				if ( $pf['id'] == $id ){
					return $pf;
					break;
				}
			}
		}
	}

	public function bedplayer_shortcode( $atts, $content=null ) {
		global $pagenow;
		if ( $pagenow == 'post.php' ) return false;
		extract( shortcode_atts( array( "type" => 'common' ), $atts ) );

		$url = $content ? $content : ( $atts['vid'] ? $atts['vid'] : '' );
		if ( !$url ) return '<div class="dplayer-id-no">未添加视频</div>';
		if ( wp_is_mobile() ) {
			$h = '196';
		} else {
			$h = '420';
		}

		$typearr             = explode( '^', $type );
		$type                = $typearr[0];
		$typestr             = '';
		$urlarr              = explode( '^', $url );
		$vlistarr            = array();
		$vliststr            = '';
		$jxapistr            = '';
		$r                   = rand( 1000,99999 );
		$typelen             = count( $typearr );
		$vgshoworhide        = '';
		$be_dplayerconfig  = '';

		for( $ti=0; $ti<$typelen; $ti++ ) {
			$player_cur = $this->be_get_player( $typearr[$ti] );
			if ( $ti == 0 ) {
				$typestr .= '<div class="layui-this"> '. $player_cur['name'] . '</div>';
				$vliststr .= '<div class="layui-tab-item layui-show"><div id="be-set-list_'.$typearr[$ti].'_' . $r . '" class="be-set-list"><div class="anthology" id="anthology' . $typearr[$ti] . '_' . $r . '">';
			} else {
				$typestr .= '<div>' . $player_cur['name'] . '</div>';
				$vliststr .= '<div class="layui-tab-item"><div id="be-set-list_' . $typearr[$ti] . '_' . $r . '" class="be-set-list"><div class="anthology" id="anthology' . $typearr[$ti] . '_' . $r . '">';
			}

			$vidgroup = explode( ',', $urlarr[$ti] );
			$vidlen = count( $vidgroup );
			if ( $typelen == 1 && $vidlen == 1 ) $vgshoworhide = 'display:none;';
			$jxapi_cur = trim( $player_cur['api'] );
			if ( $jxapi_cur == 'self' ) {
				$jxapi_cur = '{vid}';
			}

			for( $vi=0; $vi<$vidlen;$vi++ ) {
				$vidtemp = explode( '$', $vidgroup[$vi] );
				if( !isset( $vidtemp[1] ) ) {
					$vidtemp[1] = $vidtemp[0];
					$vidtemp[0] = '' . ( intval( $vi+0 ) < 9 ? '0':'') . ( $vi+1 ).'';
				}
				$vlid = $vi;
				if( isset( $vlistarr[$typearr[$ti]] ) && count( $vlistarr[$typearr[$ti]] ) > $vi ) {
					$vlid = count( $vlistarr[$typearr[$ti]] );
				}
				$vlistarr[$typearr[$ti]][] = array( 'id'=>$vlid, 'pre' => $vidtemp[0], 'video' => html_entity_decode( $vidtemp[1] ) );
				$vliststr .= '<div class="anthology-box"><a class="bk dah" href="javascript:void(0)" onclick="MP_' . $r . '.Go(' . $vlid. ', \'' . $typearr[$ti] . '\' );return false;">'.$vidtemp[0].'</a></div>';
			}
			$vliststr .= '</div></div></div>';
			$jxapistr_cur = '<input type="hidden" id="be_ifr_' . $typearr[$ti] . '_' . $r . '" value=\'<i'.'fr'.'ame border="0" src="' . $jxapi_cur . '" width="100%" height="' . $h . '" marginwidth="0" framespacing="0" marginheight="0" frameborder="0" scrolling="no" vspale="0" noresize="" allowfullscreen="true" id="bewindow_' . $typearr[$ti] . '_' . $r . '"></'.'if'.'rame>\'/>';
			do_action( 'be_video_jxcss', $jxapi_cur );
			$jxapistr .= apply_filters( 'be_video_jxapistr', $jxapistr_cur, $typearr, $jxapi_cur, $r, $ti, $vlistarr );
		}
		if ( function_exists( 'be_themes_install' ) ) {
		wp_enqueue_script( 'be_video_layuijs', get_template_directory_uri() . '/bedplayer/js/layui.js', array(), bedpversion , false );
		wp_add_inline_script( 'be_video_layuijs', 'layui.use( \'element\', function(){var $ = layui.jquery,element = layui.element;$(".layui-tab-content a").click(function(){$(".layui-tab-content a").removeClass("list_on");$(this).addClass("list_on");});});' );
		wp_enqueue_script( 'be_video_player', get_template_directory_uri() . '/bedplayer/js/beplayer.js', array(), bedpversion , false );
		wp_add_inline_script( 'be_video_player', 'var be_di_' . $r . '="",be_ji_' . $r . '="",be_playing_' . $r . '="";var bevideo_type_' . $r . '="' . $type . '";var bevideo_vids_' . $r . '=' . json_encode($vlistarr) . ';var MP_' . $r . ' = new BePlayer(' . $r . ');layui.use(\'element\', function(){var $ = layui.jquery;MP_' . $r . '.Go(0);});' );
		}
		$player = '<div id="beplayer' . $r . '" class="beplayer"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tbody><tr style="display:none;"><td height="26"><table border="0" cellpadding="0" cellspacing="0" id="playtop_' . $r . '" class="playtop"><tbody><tr><td id="topleft"><a target="_self" href="javascript:void(0)" onclick="MP_' . $r . '.GoPreUrl();return false;"></a><a target="_self" href="javascript:void(0)" onclick="MP_' . $r . '.GoNextUrl();return false;"></a></td><td id="topcc"><div id="topdes_' . $r . '" class="topdes"></div></td><td id="topright_' . $r . '" class="topright"></td></tr></tbody></table></td></tr><tr><td><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td id="playleft_' . $r . '" class="playleft" valign="top" style="height:' . $h . 'px;"></td><td id="playright_' . $r . '" valign="top"></td></tr></tbody></table></td></tr></tbody></table></div>' . $jxapistr . '<div class="layui-tab layui-tab-brief" lay-filter="videoGroup" style="margin:10px auto;' . $vgshoworhide . '"><div class="dplayer-tab-content layui-tab-content" style="height: auto;padding-left:0;">' . $vliststr . '</div></div>';
		return $player;
	}
}

new Be_Dplayer();

// dplayer

function be_get_dplayerconfig() {
	if ( function_exists( 'be_themes_install' ) ) {
		$logo = zm_get_option( 'logo_small_b' );
	} else {
		$logo = '';
	}
	$be_dplayerconfig['autoplay']   = false;
	$be_dplayerconfig['logo']       = $logo;
	$be_dplayerconfig['lang']       = 'zh-cn';
	$be_dplayerconfig['preload']    = 'auto';
	$be_dplayerconfig['theme']      = '#3690cf';
	$be_dplayerconfig['loop']       = false;
	$be_dplayerconfig['volume']     = '0.7';
	$be_dplayerconfig['screenshot'] = false;
	$be_dplayerconfig['airplay']    = false;
	$be_dplayerconfig['hotkey']     = false;
	$contextmenu = array( array( 'text' => get_bloginfo( 'name' ), 'link' => esc_url( home_url('/') ) ), array( 'text' => '版权所有' ), array( 'text' => '版本：' . bedpversion ) );
	$be_dplayerconfig['contextmenu'] = $contextmenu;
	$be_dplayerconfig = json_encode( $be_dplayerconfig );
	return $be_dplayerconfig;
}

function be_video_jxapistr_dplayer( $jxapistr_cur, $typearr, $jxapi_cur, $r, $ti, $vlistarr ) {
	$be_dplayerconfig = be_get_dplayerconfig();
	if ( strtolower( $jxapi_cur ) == 'dplayer' ) {
		global $current_user;
		$hashls = false;
		$hasflv = false;
		foreach( $vlistarr as $tavs ) {
			foreach ($tavs as $tav ) {
				if ( strpos( $tav['video'], '.m3u8' ) > 0 ) {
					$hashls = true;
					continue;
				}
				if ( strpos( $tav['video'], '.flv' ) ) {
					$hasflv = true;
					continue;
				}
			}
		}
		if ( $hasflv )wp_enqueue_script( 'be_dplayer_flv', get_template_directory_uri() . '/bedplayer/dplayer/flv.min.js',  array(), bedpversion , false );
		if ( $hashls )wp_enqueue_script( 'be_dplayer_hls', get_template_directory_uri() . '/bedplayer/dplayer/hls.min.js',  array(), bedpversion , false );
		wp_enqueue_script( 'be_dplayer', get_template_directory_uri() . '/bedplayer/dplayer/dplayer.min.js',  array(), bedpversion , false );

		$autoheight = 'document.getElementById(\'playleft_\'+pid).style.height=\'auto\';';

		wp_add_inline_script(
			'be_dplayer',str_replace( array( "\t","\r","\n" ),'','
				var dplayerconfig_' . $r . '=' . $be_dplayerconfig.';
				var dplayer_' . $r . ';
				function be_dplayer_' . $r . '( pid,cur ) {
						if ( !window.dplayer_' . $r . ' ){
						document.getElementById( \'playleft_\'+pid).innerHTML = \'\';
						window.dplayerconfig_' . $r . '.container = document.getElementById( "playleft_"+pid );
						window.dplayerconfig_' . $r . '.video = {url:( cur.video )};
						window.dplayer_' . $r . ' = new DPlayer(window.dplayerconfig_' . $r . ');
						var dp = window.dplayer_' . $r . ';
						' . $autoheight . '
					} else {
						window.dplayer_' . $r . '.switchVideo( {url:(cur.video)} );
						window.dplayer_' . $r . '.play();
					}
				}'
			)
		);
		return '<input type="hidden" id="be_ifr_' . $typearr[$ti] . '_' . $r . '" value=\'' . $jxapi_cur . '\'/>';
	}
	return $jxapistr_cur;
}

add_filter( 'be_video_jxapistr', 'be_video_jxapistr_dplayer', 10, 6 );