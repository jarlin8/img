<?php
/* ����ͨ��B */
if ( !class_exists('ThemeUpdateChecker') ):
class ThemeUpdateChecker {
	public $theme = '';              // ������������صĸ���
	public $metadataUrl = '';        // �����ļ���URL��
	public $enableAutomaticChecking = true; // ����/�����Զ����¼�顣
	
	protected $optionName = '';      // �洢������Ϣ��λ��.
	protected $automaticCheckDone = false;
	protected static $filterPrefix = 'tuc_request_update_';

	// �����ຯ����
	public function __construct($theme, $metadataUrl, $enableAutomaticChecking = true){
		$this->metadataUrl = $metadataUrl;
		$this->enableAutomaticChecking = $enableAutomaticChecking;
		$this->theme = $theme;
		$this->optionName = 'external_theme_updates-'.$this->theme;
		
		$this->installHooks();
	}

	// ��װ���ж��ڸ��¼���ע�������Ϣ����Ĺ���
	public function installHooks(){
		//Check for updates when WordPress does. We can detect when that happens by tracking
		//updates to the "update_themes" transient, which only happen in wp_update_themes().
		if ( $this->enableAutomaticChecking ){
			add_filter('pre_set_site_transient_update_themes', array($this, 'onTransientUpdate'));
		}
		
		// ��������Ϣ���뵽WP�����б��С�
		add_filter('site_transient_update_themes', array($this,'injectUpdate')); 
		
		// WPɾ��������Ϣʱ��ɾ�����������Ϣ��
		add_action('delete_site_transient_update_themes', array($this, 'deleteStoredData'));
	}

	// �����õ�����URL����������Ϣ��
	public function requestUpdate($queryArgs = array()){
		// ��ѯҪ���ӵ�URL�Ĳ������������ͨ��ʹ�ù������ص�(�μ�addQueryArgFilter())������Լ��Ĺ�������
		$queryArgs['installed_version'] = $this->getInstalledVersion(); 
		$queryArgs = apply_filters(self::$filterPrefix.'query_args-'.$this->theme, $queryArgs);
		
		// wp_remote_get()���õĸ���ѡ�����Ҳ���Թ�����Щ���ݡ�
		$options = array(
			'timeout' => 10, // ��
		);
		$options = apply_filters(self::$filterPrefix.'options-'.$this->theme, $options);
		
		$url = $this->metadataUrl; 
		if ( !empty($queryArgs) ){
			$url = add_query_arg($queryArgs, $url);
		}

		// ��������
		$result = wp_remote_get($url, $options);

		// ���Խ�����Ӧ
		$themeUpdate = null;
		$code = wp_remote_retrieve_response_code($result);
		$body = wp_remote_retrieve_body($result);
		if ( ($code == 200) && !empty($body) ){
			$themeUpdate = ThemeUpdate::fromJson($body);
			// �°汾�ȵ�ǰ��װ�İ汾�¸���
			if ( ($themeUpdate != null) && version_compare($themeUpdate->version, $this->getInstalledVersion(), '<=') ){
				$themeUpdate = null;
			}
		}

		$themeUpdate = apply_filters(self::$filterPrefix.'result-'.$this->theme, $themeUpdate, $result);
		return $themeUpdate;
	}
	

	// ��ȡ��ǰ��װ������汾
	public function getInstalledVersion(){
		if ( function_exists('wp_get_theme') ) {
			$theme = wp_get_theme($this->theme);
			return $theme->get('Version');
		}

		// ���ڼ���WP 3.3�����°汾��
		foreach(get_themes() as $theme){
			if ( $theme['Stylesheet'] === $this->theme ){
				return $theme['Version'];
			}
		}
		return '';
	}

	// ����������
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

	// �����Զ����¼�飬ÿ��ҳ����ز�Ҫ����һ��
	public function onTransientUpdate($value){
		if ( !$this->automaticCheckDone ){
			$this->checkForUpdates();
			$this->automaticCheckDone = true;
		}
		return $value;
	}

	// ��������ӵ�WP�����б��С�
	public function injectUpdate($updates){
		$state = get_option($this->optionName);

		// �Ƿ���Ӹ���
		if ( !empty($state) && isset($state->update) && !empty($state->update) ){
			$update = $state->update;
			if ( is_string($state->update) ) {
				$update = ThemeUpdate::fromJson($state->update);
			}
			$updates->response[$this->theme] = $update->toWpFormat();
		}
		return $updates;
	}

	// ɾ�����д洢�Ĳ������ݡ�
	public function deleteStoredData(){
		delete_option($this->optionName);
	}

	// ע��һ���ص����������˲�ѯ������
	public function addQueryArgFilter($callback){
		add_filter(self::$filterPrefix.'query_args-'.$this->theme, $callback);
	}

	// ע��һ���ص����������˴��ݸ�wp_remote_get()�Ĳ�����
	public function addHttpRequestArgFilter($callback){
		add_filter(self::$filterPrefix.'options-'.$this->theme, $callback);
	}

	// ע��һ���ص������ڹ��˴��ⲿAPI��������������Ϣ��
	public function addResultFilter($callback){
		add_filter(self::$filterPrefix.'result-'.$this->theme, $callback, 10, 2);
	}
}

endif;

if ( !class_exists('ThemeUpdate') ):

// ���ڱ����йظ��µ���Ϣ
class ThemeUpdate {
	public $version;      // �汾�š�
	public $details_url;  // �°汾����˵��URL��
	public $download_url; //���������URL����ѡ�ġ�

	 // ��json����ı�ʾ����ThemeUpdate����ʵ����
	public static function fromJson($json){
		$apiResponse = json_decode($json);
		if ( empty($apiResponse) || !is_object($apiResponse) ){
			return null;
		}
		
		// ��������֤
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

	// ��������Ϣ���л�ΪJSON
	public function toJson() {
		return json_encode($this);
	}

	// ת��ΪWordPress���¸�ʽ
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