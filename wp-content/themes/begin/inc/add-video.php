<?php
$addvideo = new addvideo();
class addvideo{
	private $edit = false;
	private $width = '100%';
	private $height = '500px';
	private $youku_client_id = 'd0b1b77a17cded3b';
	public function __construct(){
		if (!empty($youku_client_id) && strlen($youku_client_id) == 16){
			$this->youku_client_id = $youku_client_id;
		}

		wp_embed_unregister_handler('youku');
		wp_embed_unregister_handler('56com');
		wp_embed_unregister_handler('youtube');

		// video
		wp_embed_register_handler( 'addvideo_youku',
			'#https?://(?:[v|vo]+)\.youku\.com/v_show/id_(?<video_id>[a-z0-9_=\-]+)#i',
			array($this, 'addvideo_embed_handler_youku') );

		wp_embed_register_handler( 'addvideo_qq',
			'#https?://v\.qq\.com/(?:[a-z0-9_\./]+\?vid=(?<video_id1>[a-z0-9_=\-]+)|(?:[a-z0-9/]+)/(?<video_id2>[a-z0-9_=\-]+))#i',
			array($this, 'addvideo_embed_handler_qq') );

		wp_embed_register_handler( 'addvideo_wasu',
			'#https?://www\.wasu\.cn/play/show/id/(?<video_id>\d+)#i',
			array($this, 'addvideo_embed_handler_wasu') );

        wp_embed_register_handler( 'addvideo_youtube',
			'#https?://www\.youtube\.com/watch\?v=(?<video_id>[a-zA-Z0-9_=\-]+)#i',
			array($this, 'addvideo_embed_handler_youtube') );

		wp_embed_register_handler( 'addvideo_bilibili',
			'#https?://www\.bilibili\.com/video/av(?:(?<video_id1>\d+)/(?:index_|\#page=)(?<video_id2>\d+)|(?<video_id>\d+))#i',
			array($this, 'addvideo_embed_handler_bilibili') );

		wp_embed_register_handler( 'addvideo_bilibili_bv',
			'#https?://www\.bilibili\.com/video/BV(?:(?<video_id1>[a-z0-9_=\-]+)/(?:index_|\#page=)(?<video_id2>[a-z0-9_=\-]+)|(?<video_id>[a-z0-9_=\-]+))#i',
			array($this, 'addvideo_embed_handler_bilibili_bv') );

		wp_embed_register_handler( 'addvideo_iqiyi',
			'#https?://www\.iqiyi\.com/(?:[a-zA-Z]+)_(?<video_id>[a-z0-9_~\-]+)#i',
			array($this, 'addvideo_embed_handler_iqiyi') );

        // music
		wp_embed_register_handler( 'addvideo_music163',
			'#https?://music\.163\.com/\#/song\?id=(?<video_id>\d+)#i',
			array($this, 'addvideo_embed_handler_music163') );

		wp_embed_register_handler( 'addvideo_musicqq',
			'#https?://y\.qq\.com/n/yqq/song/(?<video_id>\w+)\.html#i',
			array($this, 'addvideo_embed_handler_musicqq') );

		wp_embed_register_handler( 'addvideo_xiami',
			'#https?://www\.xiami\.com/song/(?<video_id>\d+)#i',
			array($this, 'addvideo_embed_handler_xiami') );
	}

    # video
	public function addvideo_embed_handler_youku( $matches, $attr, $url, $rawattr ) {
		$embed = $this->get_iframe("//player.youku.com/embed/{$matches['video_id']}?client_id={$this->youku_client_id}", $url);
		return apply_filters( 'embed_youku', $embed, $matches, $attr, $url, $rawattr );
	}

	public function addvideo_embed_handler_qq( $matches, $attr, $url, $rawattr ) {
		$matches['video_id'] = $matches['video_id1'] == '' ? $matches['video_id2'] : $matches['video_id1'];
		$embed = $this->get_iframe("//v.qq.com/iframe/player.html?vid={$matches['video_id']}&auto=0", $url);
		return apply_filters( 'embed_qq', $embed, $matches, $attr, $url, $rawattr );
	}

	public function addvideo_embed_handler_wasu( $matches, $attr, $url, $rawattr ) {
		$embed = $this->get_iframe("http://www.wasu.cn/Play/iframe/id/{$matches['video_id']}", $url);
		return apply_filters( 'embed_wasu', $embed, $matches, $attr, $url, $rawattr );
	}

	public function addvideo_embed_handler_youtube( $matches, $attr, $url, $rawattr ) {
		$embed = $this->get_iframe("//www.youtube.com/embed/{$matches['video_id']}", $url);
		return apply_filters( 'embed_youtube', $embed, $matches, $attr, $url, $rawattr );
	}

	public function addvideo_embed_handler_bilibili( $matches, $attr, $url, $rawattr ) {
		$matches['video_id'] = ($matches['video_id1'] == '') ? $matches['video_id'] : $matches['video_id1'];
		$page = ($matches['video_id2'] > 1) ? $matches['video_id2'] : 1;
		$cid = '';
		$embed = $this->get_iframe("//player.bilibili.com/player.html?aid={$matches['video_id']}&cid={$cid}&page={$page}", $url);
		return apply_filters( 'embed_bilibili', $embed, $matches, $attr, $url, $rawattr );
	}

	public function addvideo_embed_handler_bilibili_bv( $matches, $attr, $url, $rawattr ) {
		$matches['video_id'] = ($matches['video_id1'] == '') ? $matches['video_id'] : $matches['video_id1'];
		$page = ($matches['video_id2'] > 1) ? $matches['video_id2'] : 1;
		$cid = '';
		$embed = $this->get_iframe("//player.bilibili.com/player.html?bvid=BV{$matches['video_id']}&cid={$cid}&page={$page}", $url);
		return apply_filters( 'embed_bilibili', $embed, $matches, $attr, $url, $rawattr );
	}

	public function addvideo_embed_handler_iqiyi( $matches, $attr, $url, $rawattr ) {
		$embed = '';
		try{
			$request = new WP_Http();
			$data = (array)$request->request($url, array('timeout' => 3));
			if (!isset($data['body'])){
				$data['data'] = '';
			}
			preg_match('/"vid":"(\w+)"/i', (string)$data['body'], $match);
			$vid = $match[1];
			preg_match('/"tvId":(\d+)/i', (string)$data['body'], $match);
			$tvid = $match[1];
			if ($tvid > 0 && !empty($vid)) {
				$embed = $this->get_iframe("//open.iqiyi.com/developer/player_js/coopPlayerIndex.html?vid={$vid}&tvId={$tvid}&height=100%&width=100%&autoplay=0", $url);
			}
		}catch(Exception $e){}
		if (empty($embed)){
			$embed = '½âÎöÊ§°Ü£¬ÇëË¢ÐÂÒ³ÃæÖØÊÔ';
		}
		return apply_filters( 'embed_iqiyi', $embed, $matches, $attr, $url, $rawattr );
	}

	# video widthout h5
	public function addvideo_embed_handler_tudou( $matches, $attr, $url, $rawattr ) {
		$embed = $this->get_link($url);
		return apply_filters( 'embed_tudou', $embed, $matches, $attr, $url, $rawattr );
	}
    
	# music
	public function addvideo_embed_handler_music163( $matches, $attr, $url, $rawattr ) {
		$embed = $this->get_iframe("//music.163.com/outchain/player?type=2&id={$matches['video_id']}&auto=0&height=90", '', '100%', '110px');
		return apply_filters( 'embed_music163', $embed, $matches, $attr, $url, $rawattr );
	}

	public function addvideo_embed_handler_musicqq( $matches, $attr, $url, $rawattr ) {
		$embed = $this->get_iframe("//cc.stream.qqmusic.qq.com/C100{$matches['video_id']}.m4a?fromtag=52", '', '100%', '110px');
		return apply_filters( 'embed_musicqq', $embed, $matches, $attr, $url, $rawattr );
	}

	public function addvideo_embed_handler_xiami( $matches, $attr, $url, $rawattr ) {
		$embed =
			'<div class="smartvideo" style="background: transparent;">
				<script src="http://www.xiami.com/widget/player-single?uid=0&sid='.$matches['video_id'].'&autoplay=0&mode=js" type="text/javascript"></script>
			</div>';
		return apply_filters( 'embed_xiami', $embed, $matches, $attr, $url, $rawattr );
	}

	private function get_iframe($url = '', $source = ''){
		$html = '';
		$html .='<div class="smartvideo"><div class="player"><iframe src="' . $url . '" width="100%" height="100%" frameborder="0" allowfullscreen="true"></iframe></div>';
		$html .= '</div>';
		return $html;
	}

	private function is_https(){
		if (strtolower($_SERVER['HTTPS']) == 'on'){
			return true;
		}else{
			return false;
		}
	}
}