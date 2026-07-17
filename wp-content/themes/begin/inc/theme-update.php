<?php
/* 更新通道B */
if ( !class_exists('ThemeUpdateChecker') ):
class ThemeUpdateChecker {
	public $theme = '';              // 检查与该主题相关的更新
	public $metadataUrl = '';        // 主题文件的URL。
	public $enableAutomaticChecking = true; // 启用/禁用自动更新检查。
	
	protected $optionName = '';      // 存储更新信息的位置.
	protected $automaticCheckDone = false;
	protected static $filterPrefix = 'tuc_request_update_';

	// 构造类函数。
	public function __construct($theme, $metadataUrl, $enableAutomaticChecking = true){
		$this->metadataUrl = $metadataUrl;
		$this->enableAutomaticChecking = $enableAutomaticChecking;
		$this->theme = $theme;
		$this->optionName = 'external_theme_updates-'.$this->theme;
		
		$this->installHooks();
	}

	// 安装运行定期更新检查和注入更新信息所需的钩子
	public function installHooks(){
		//Check for updates when WordPress does. We can detect when that happens by tracking
		//updates to the "update_themes" transient, which only happen in wp_update_themes().
		if ( $this->enableAutomaticChecking ){
			add_filter('pre_set_site_transient_update_themes', array($this, 'onTransientUpdate'));
		}
		
		// 将更新信息插入到WP更新列表中。
		add_filter('site_transient_update_themes', array($this,'injectUpdate')); 
		
		// WP删除更新信息时，删除主题更新信息。
		add_action('delete_site_transient_update_themes', array($this, 'deleteStoredData'));
	}

	// 从配置的数据URL检索更新信息。
	public function requestUpdate($queryArgs = array()){
		// 查询要附加到URL的参数。主题可以通过使用过滤器回调(参见addQueryArgFilter())来添加自己的过滤器。
		$queryArgs['installed_version'] = $this->getInstalledVersion(); 
		$queryArgs = apply_filters(self::$filterPrefix.'query_args-'.$this->theme, $queryArgs);
		
		// wp_remote_get()调用的各种选项。主题也可以过滤这些内容。
		$options = array(
			'timeout' => 10, // 秒
		);
		$options = apply_filters(self::$filterPrefix.'options-'.$this->theme, $options);
		
		$url = $this->metadataUrl; 
		if ( !empty($queryArgs) ){
			$url = add_query_arg($queryArgs, $url);
		}

		// 发送请求。
		$result = wp_remote_get($url, $options);

		// 尝试解析响应
		$themeUpdate = null;
		$code = wp_remote_retrieve_response_code($result);
		$body = wp_remote_retrieve_body($result);
		if ( ($code == 200) && !empty($body) ){
			$themeUpdate = ThemeUpdate::fromJson($body);
			// 新版本比当前安装的版本新更新
			if ( ($themeUpdate != null) && version_compare($themeUpdate->version, $this->getInstalledVersion(), '<=') ){
				$themeUpdate = null;
			}
		}

		$themeUpdate = apply_filters(self::$filterPrefix.'result-'.$this->theme, $themeUpdate, $result);
		return $themeUpdate;
	}
	

	// 获取当前安装的主题版本
	public function getInstalledVersion(){
		if ( function_exists('wp_get_theme') ) {
			$theme = wp_get_theme($this->theme);
			return $theme->get('Version');
		}

		// 用于兼容WP 3.3及以下版本。
		foreach(get_themes() as $theme){
			if ( $theme['Stylesheet'] === $this->theme ){
				return $theme['Version'];
			}
		}
		return '';
	}

	// 检查主题更新
	public function checkForUpdates(){
		$state = get_option($this->optionName);
		if ( empty($state) ){
			$state = new StdClass;
			$state->lastCheck = 0;
			$state->checkedVersion = '';
			$state->update = null;
		}

		$state->lastCheck = time();
		$state->checkedVersion = $this->getInstalledVersion();
		update_option($this->optionName, $state); //Save before checking in case something goes wrong 
		
		$update = $this->requestUpdate();
		$state->update = ($update instanceof ThemeUpdate) ? $update->toJson() : $update;
		update_option($this->optionName, $state);
	}

	// 运行自动更新检查，每次页面加载不要超过一次
	public function onTransientUpdate($value){
		if ( !$this->automaticCheckDone ){
			$this->checkForUpdates();
			$this->automaticCheckDone = true;
		}
		return $value;
	}

	// 将更新添加到WP更新列表中。
	public function injectUpdate($updates){
		$state = get_option($this->optionName);

		// 是否添加更新
		if ( !empty($state) && isset($state->update) && !empty($state->update) ){
			$update = $state->update;
			if ( is_string($state->update) ) {
				$update = ThemeUpdate::fromJson($state->update);
			}
			$updates->response[$this->theme] = $update->toWpFormat();
		}
		return $updates;
	}

	// 删除所有存储的簿记数据。
	public function deleteStoredData(){
		delete_option($this->optionName);
	}

	// 注册一个回调函数来过滤查询参数。
	public function addQueryArgFilter($callback){
		add_filter(self::$filterPrefix.'query_args-'.$this->theme, $callback);
	}

	// 注册一个回调函数来过滤传递给wp_remote_get()的参数。
	public function addHttpRequestArgFilter($callback){
		add_filter(self::$filterPrefix.'options-'.$this->theme, $callback);
	}

	// 注册一个回调，用于过滤从外部API检索到的主题信息。
	public function addResultFilter($callback){
		add_filter(self::$filterPrefix.'result-'.$this->theme, $callback, 10, 2);
	}
}

endif;

if ( !class_exists('ThemeUpdate') ):

// 用于保存有关更新的信息
class ThemeUpdate {
	public $version;      // 版本号。
	public $details_url;  // 新版本更新说明URL。
	public $download_url; //主题的下载URL。可选的。

	 // 从json编码的表示创建ThemeUpdate的新实例。
	public static function fromJson($json){
		$apiResponse = json_decode($json);
		if ( empty($apiResponse) || !is_object($apiResponse) ){
			return null;
		}
		
		// 基本的验证
		$valid = isset($apiResponse->version) && !empty($apiResponse->version) && isset($apiResponse->details_url) && !empty($apiResponse->details_url);
		if ( !$valid ){
			return null;
		}
		
		$update = new self();
		foreach(get_object_vars($apiResponse) as $key => $value){
			$update->$key = $value;
		}
		
		return $update;
	}

	// 将更新信息序列化为JSON
	public function toJson() {
		return json_encode($this);
	}

	// 转换为WordPress更新格式
	public function toWpFormat(){
		$update = array(
			'new_version' => $this->version,
			'url' => $this->details_url,
		);
		
		if ( !empty($this->download_url) ){
			$update['package'] = $this->download_url;
		}
		
		return $update;
	}
}

endif;