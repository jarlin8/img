<?php
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-08-30 11:10:06
 * @LastEditors: iowen
 * @LastEditTime: 2023-01-19 00:24:31
 * @FilePath: \onenav\inc\framework\customize\options-function.php
 * @Description: 
 */
function active_html(){
        if (IO_PRO) {
            $con = '<div id="authorization_form" class="ajax-form" ajax-url="' . esc_url(admin_url('admin-ajax.php')) . '">
            <div class="vip-certificate">
            <div class="ok-icon"><svg t="1585712312243" class="icon" style="width: 1em; height: 1em;vertical-align: middle;fill: currentColor;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="3845" data-spm-anchor-id="a313x.7781069.0.i0"><path d="M115.456 0h793.6a51.2 51.2 0 0 1 51.2 51.2v294.4a102.4 102.4 0 0 1-102.4 102.4h-691.2a102.4 102.4 0 0 1-102.4-102.4V51.2a51.2 51.2 0 0 1 51.2-51.2z m0 0" fill="#FF6B5A" p-id="3846"></path><path d="M256 13.056h95.744v402.432H256zM671.488 13.056h95.744v402.432h-95.744z" fill="#FFFFFF" p-id="3847"></path><path d="M89.856 586.752L512 1022.72l421.632-435.2z m0 0" fill="#6DC1E2" p-id="3848"></path><path d="M89.856 586.752l235.52-253.952h372.736l235.52 253.952z m0 0" fill="#ADD9EA" p-id="3849"></path><path d="M301.824 586.752L443.136 332.8h137.216l141.312 253.952z m0 0" fill="#E1F9FF" p-id="3850"></path><path d="M301.824 586.752l209.92 435.2 209.92-435.2z m0 0" fill="#9AE6F7" p-id="3851"></path></svg></div>
            <div class="ok-text" style="font-size: 15px; ">恭喜您! 已完成授权</div>
            <input type="hidden" ajax-name="action" value="get_iotheme_delete_authorization">
            '.wp_nonce_field('delete_authorization','_ajax_nonce').'
            <a href="javascript:;" title="撤销授权" id="authorization_submit" class="bnt-svg ajax-submit"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52.3 52.3"><path d="M26.1 1.1c-13.8 0-25 11.2-25 25s11.2 25 25 25 25-11.2 25-25-11.2-25-25-25zm11.7 32.7c1.1 1.1 1.1 2.9 0 4-.5.5-1.3.8-2 .8s-1.4-.3-2-.8l-7.7-7.7-7.7 7.7c-.5.5-1.3.8-2 .8s-1.4-.3-2-.8c-1.1-1.1-1.1-2.9 0-4l7.7-7.7-7.7-7.7c-1.1-1.1-1.1-2.9 0-4s2.9-1.1 4 0l7.7 7.7 7.7-7.7c1.1-1.1 2.9-1.1 4 0s1.1 2.9 0 4l-7.7 7.7 7.7 7.7z" fill="#efefef"/></svg></a>
            </div>
            <div class="ajax-notice"></div>
            </div>';
        } else {
            $con = '<div id="authorization_form" class="authorization-form ajax-form" ajax-url="' . esc_url(admin_url('admin-ajax.php')) . '">
            <h4 class="aut-title">授权主题</h4>
            <div class="aut-content">
            <p style="color:#fd4c73;">请先使用订单提供的激活码<a href="//www.iotheme.cn/user?try=reg" target="_blank" title="注册域名">注册域名</a>。 如果没有购买，请访问<a href="//www.iotheme.cn/store/onenav.html" target="_blank" title="购买主题">iTheme</a>购买。</p>
            <div style="margin-bottom: 20px">
            <input class="regular-text not-change" type="text" ajax-name="key_code" value="" placeholder="请输入激活码">
            <input type="hidden" ajax-name="action" value="get_iotheme_authorization">
            </div>
            <a href="javascript:;" id="authorization_submit" class="but c-blue ajax-submit curl-aut-submit">一键授权</a>
            <div class="ajax-notice"></div>
            </div>
            </div>';
        }
        echo $con;
}
function add_customize_scripts(){
    wp_register_style('admin-options', CSF::include_plugin_url('customize/css/options.css'), array(), VERSION, '');
    wp_enqueue_style('admin-options'); 
    
    wp_register_script( 'admin-options', CSF::include_plugin_url('customize/js/options.js'), array('jquery'), VERSION, true );
    wp_enqueue_script( 'admin-options' ); 
}
add_action('csf_enqueue','add_customize_scripts');
$cats_id = false;
// 所有文章分类ID
function get_cats_id(){  
    if( ! is_admin() ) { return; }
    global $cats_id;
    if (!$cats_id) {
        $categories = get_categories(array('hide_empty' => 0));
        foreach ($categories as $cat) {
            $cats_id .= '<span style="margin-right: 15px;">[ <b>'.$cat->cat_ID.'</b>：'.$cat->cat_name.' ]</span>';
        }
    }
    return $cats_id;
}
// 获取自定义文章父分类
if(!function_exists('get_all_taxonomy')){
	function get_all_taxonomy(){  
        if( ! is_admin() ) { return; }
        $term_query = new WP_Term_Query( array(
            'taxonomy'   =>  array('favorites','apps'),
            'hide_empty' => false,
        ));
        $customize = array(); 
        if ( ! empty( $term_query->terms ) ) {
            foreach ( $term_query ->terms as $term ) { 
                if($term->parent == 0)
                    $customize["id_".$term->term_id] = $term->name;
            }
        }  
        return $customize;
    }
}

// 获取效果列表
if(!function_exists('get_all_fx_bg')){
	function get_all_fx_bg(){  
        $fxbg = array();
        for($i=0;$i<=17;$i++){
            if($i==0)
                $fxbg[$i] = '随机';// $i;
            else
                $fxbg[sprintf("%02d", $i)] = $i;
        }
        $fxbg['custom'] = '自定义';
        return $fxbg;
    }
}
/**
 * Sitemap 设置选项
 */
function io_site_map_but() {
    if( class_exists( 'DX_Seo_Sitemap_Do_Sitemap' ) ) {
        echo '<div id="settings-container"><h2 class="menu-title"></h2>';
        DX_Seo_Sitemap_Do_Sitemap::xml_notice();
        echo '<h2></h2></div>
        <a id="generate-baidu" class="button button-primary generate-sitemap">生成sitemap</a>
        <a id="delete-baidu" class="button button-secondary delete-sitemap">删除sitemap</a>
        <p id="sitemap-progress" class="test-mail-text" style="line-height:30px;color:#dd0c0c">修改SiteMAP选项后请保存成功再点“生成sitemap”</p>';
        DX_Seo_Sitemap_Do_Sitemap::sitemap_jquery();
    }else{
        echo '<h2 style="line-height:30px;color:#dd0c0c">请先保存设置，然后刷新页面...</h2>';
    }
}
/**
 * Get taxonomies
 */
function setting_get_taxes() {
	$taxes = get_taxonomies( array( '_builtin' => false ), 'objects' );
	if( $taxes ) {
		foreach( $taxes as $key => $tax ) {
			$res[ $key ] = $tax->labels->name;
		}
	}		
	$res['post_tag'] = __('标签','i_theme');
	$res['category'] = __('分类目录','i_theme');
	$res = array_reverse( $res ); 
	return $res;
}

function io_test_mail_action(){
    $email = $_POST['email'];
    
    if (empty($email)) {
        exit(json_encode(array('error' => 1, 'msg' => '请输入邮箱号码')));
    }
	$subject = __('发信测试邮件', 'i_theme');
	$args = array(
		'content' =>'收到这封邮件就说明你的设置正确！',
	);
	$result = io_mail('', $email, $subject, $args, 'pure');
    if (is_array($result)) {
        exit(json_encode($result));
    }elseif($result){
        exit(json_encode(array('error' => 0, 'msg' => '发送成功')));
    }else{
        exit(json_encode(array('error' => 1, 'msg' => '发送失败')));
    }
}
add_action('wp_ajax_nopriv_test_mail', 'io_test_mail_action');  
add_action('wp_ajax_test_mail', 'io_test_mail_action');

//备份
function io_backup()
{ 
    if (IO_PRO) {
        $csf = array();
        $prefix = 'io_get_option';
        $options = get_option($prefix . '_backup');
        $lists = '暂无备份数据！';
        $admin_ajax_url = admin_url('admin-ajax.php');
        $delete_but = '';
        if ($options) {
            $lists = '';
            $options = array_reverse($options);
            $count = 0;
            foreach ($options as $key => $val) {
                $ajax_url = add_query_arg('key', $key, $admin_ajax_url);
                $del = '<a href="' . add_query_arg('action', 'options_backup_delete', $ajax_url) . '" data-confirm="确认要删除此备份[' . $key . ']？删除后不可恢复！" class="but c-yellow ajax-get ml10">删除</a>';
                $restore = '<a href="' . add_query_arg('action', 'options_backup_restore', $ajax_url) . '" data-confirm="确认将主题设置恢复到此备份吗？[' . $key . ']？" class="but c-blue ajax-get ml10">恢复</a>';
                $lists .= '<div class="backup-item flex ac jsb">';
                $lists .= '<div class="item-left"><div>' . $val['time'] . '</div><div> [' . $val['type'] . ']</div></div>';
                $lists .= '<span class="shrink-0">' . $restore . $del .  '</span>';
                $lists .= '</div>';
                $count++;
            }
            if ($count > 3) {
                $delete_but = '<a href="' . add_query_arg(array('action' => 'options_backup_delete_surplus', 'key' => 'all'), $admin_ajax_url) . '" data-confirm="确认要删除多余的备份数据吗？删除后不可恢复！" class="button csf-warning-primary ajax-get">删除备份 保留最新三份</a>';
            }
        }
        echo'<div class="csf-submessage csf-submessage-warning"><h3 style="color:#fd4c73;"><i class="csf-tab-icon fa fa-fw fa-copy"></i> 备份及恢复</h3>
        <ajaxform class="ajax-form">
        <div style="margin:10px 0">
        <p>系统会在重置、更新等重要操作时自动备份主题设置，您可以此进行恢复备份或手动备份</p>
        <p><b>备份列表：</b></p>
        <div class="card-box backup-box">
        ' . $lists . '
        </div>
        </div>
        <a href="' . add_query_arg('action', 'options_backup', $admin_ajax_url) . '" class="button button-primary ajax-get">备份当前配置</a>
        ' . $delete_but . '
        <p><i class="fa fa-fw fa-info-circle fa-fw"></i> 仅能保存主题设置，不能保存整站数据。（此操作可能会清除设置数据，请谨慎操作）</p>
        <div class="ajax-notice"></div>
        </ajaxform>
        </div>';
    }
}

//备份主题设置
function io_ajax_options_backup()
{
    $type = !empty($_REQUEST['type']) ? $_REQUEST['type'] : '手动备份';
    $backup = io_options_backup($type);
    echo (json_encode(array('error' => 0, 'reload' => 1, 'msg' => '当前配置已经备份')));
    exit();
}
add_action('wp_ajax_options_backup', 'io_ajax_options_backup');
//备份主题数据
function io_options_backup($type = '自动备份')
{
    $prefix = 'io_get_option';
    $options = get_option($prefix);

    $options_backup = get_option($prefix . '_backup');
    if (!$options_backup) $options_backup = array();
    $time = current_time('Y-m-d H:i:s');
    $options_backup[$time] = array(
        'time' => $time,
        'type' => $type,
        'data' => $options,
    );
    return update_option($prefix . '_backup', $options_backup);
}

function io_ajax_options_backup_delete()
{
    if (!is_super_admin()) {
        echo (json_encode(array('error' => 1, 'msg' => '操作权限不足')));
        exit();
    }
    if (empty($_REQUEST['key'])) {
        echo (json_encode(array('error' => 1, 'msg' => '参数传入错误')));
        exit();
    }

    $prefix = 'io_get_option';
    if ($_REQUEST['action'] == 'options_backup_delete_all') {
        update_option($prefix . '_backup', false);
        echo (json_encode(array('error' => 0, 'reload' => 1, 'msg' => '已删除全部备份数据')));
        exit();
    }

    $options_backup = get_option($prefix . '_backup');

    if ($_REQUEST['action'] == 'options_backup_delete_surplus') {
        if ($options_backup) {
            $options_backup = array_reverse($options_backup);
            update_option($prefix . '_backup', array_reverse(array_slice($options_backup, 0, 3)));
            echo (json_encode(array('error' => 0, 'reload' => 1, 'msg' => '已删除多余备份数据，仅保留份')));
            exit();
        }
        echo (json_encode(array('error' => 1, 'msg' => '暂无可删除的数据')));
    }

    if (isset($options_backup[$_REQUEST['key']])) {
        unset($options_backup[$_REQUEST['key']]);

        update_option($prefix . '_backup', $options_backup);
        echo (json_encode(array('error' => 0, 'reload' => 1, 'msg' => '所选备份已删除')));
    } else {
        echo (json_encode(array('error' => 1, 'msg' => '此备份已删除')));
    }
    exit();
}
add_action('wp_ajax_options_backup_delete', 'io_ajax_options_backup_delete');
add_action('wp_ajax_options_backup_delete_all', 'io_ajax_options_backup_delete');
add_action('wp_ajax_options_backup_delete_surplus', 'io_ajax_options_backup_delete');


function io_ajax_options_backup_restore()
{
    if (!is_super_admin()) {
        echo (json_encode(array('error' => 1, 'msg' => '操作权限不足')));
        exit();
    }
    if (empty($_REQUEST['key'])) {
        echo (json_encode(array('error' => 1, 'msg' => '参数传入错误')));
        exit();
    }

    $prefix = 'io_get_option';
    $options_backup = get_option($prefix . '_backup');
    if (isset($options_backup[$_REQUEST['key']]['data'])) {
        update_option($prefix, $options_backup[$_REQUEST['key']]['data']);
        echo (json_encode(array('error' => 0, 'reload' => 1, 'msg' => '主题设置已恢复到所选备份[' . $_REQUEST['key'] . ']')));
    } else {
        echo (json_encode(array('error' => 1, 'msg' => '备份恢复失败，未找到对应数据')));
    }
    exit();
}
add_action('wp_ajax_options_backup_restore', 'io_ajax_options_backup_restore');



function io_csf_reset_to_backup()
{
    io_options_backup('重置全部 自动备份');
}
add_action('csf_io_get_option_reset_before', 'io_csf_reset_to_backup');

function io_csf_reset_section_to_backup()
{
    io_options_backup('重置选区 自动备份');
}
add_action('csf_io_get_option_reset_section_before', 'io_csf_reset_section_to_backup');

function io_new_theme_to_backup()
{
    $prefix = 'io_get_option';
    $options_backup = get_option($prefix . '_backup');
    $time = false;

    if ($options_backup) {
        $options_backup = array_reverse($options_backup);
        foreach ($options_backup as $key => $val) {
            if ($val['type'] == '更新主题 自动备份') {
                $time = $key;
                break;
            }
        }
    }
    if (!$time || (floor((strtotime(current_time("Y-m-d H:i:s")) - strtotime($time)) / 3600) > 240)) {
        io_options_backup('更新主题 自动备份');
        wp_cache_flush();
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
    }
}
add_action('new_io_theme_admin_notices', 'io_new_theme_to_backup');

function io_csf_save_section_to_backup()
{
    $prefix = 'io_get_option';
    $options_backup = get_option($prefix . '_backup');
    $time = false;

    if ($options_backup) {
        $options_backup = array_reverse($options_backup);
        foreach ($options_backup as $key => $val) {
            if ($val['type'] == '定期自动备份') {
                $time = $key;
                break;
            }
        }
    }
    if (!$time || (floor((strtotime(current_time("Y-m-d H:i:s")) - strtotime($time)) / 3600) > 600)) {
        io_options_backup('定期自动备份');
    }
}
add_action('csf_io_get_option_save_after', 'io_csf_save_section_to_backup');


//主题更新后发送通知
function io_notice_update()
{
    if (IO_PRO) {
        $version = get_option('onenav_update_version');
        if ($version && version_compare($version, VERSION, '<')) {
            do_action('new_io_theme_admin_notices');
            $con = '<div class="notice notice-success is-dismissible">
				<h2 style="color:#f1404b;"><i class="fa fa-hand-o-right fa-fw"></i> 恭喜您！OneNav 主题已更新</h2>
                <p>更新主题请记得清空缓存、刷新CDN，再保存一下<a href="' . io_get_admin_csf_url() . '">主题设置</a>，保存主题设置后此通知会自动关闭。</p>
                <p><a class="button" style="margin: 2px;" href="' . io_get_admin_csf_url() . '">体验新功能</a><a target="_blank" class="button" style="margin: 2px;" href="https://www.iotheme.cn/store/onenav.html#update-log">查看更新日志</a></p>
			</div>';
            echo  $con;
        } elseif (!$version) {
            $con = '<div class="notice notice-info is-dismissible">
				<h2 style="color:#f1404b;"><i class="fa fa-bullhorn fa-fw"></i> 感谢您使用 OneNav 主题</h2>
                <p>首次启动请先完成以下几步：</p>
                <ul>
                <li>1、确保站点“伪静态规则”和“固定链接”设置正确，<a target="_blank" href="https://www.iotheme.cn/wordpressweijingtaihewordpressgudinglianjieshezhi.html">设置方法</a><li>
                <li>2、授权域名并填写激活码到主题设置中，<a target="_blank" href="https://www.iotheme.cn/user?action=reg">前往授权</a><li>
                <li>3、保存<a href="' . io_get_admin_csf_url() . '">主题设置</a>，保存主题设置后此通知会自动关闭。<li>
                </ul>
			</div>';
            echo  $con;
        }
    }else{
        $con = '<div class="notice notice-warning">
        <p style="color:#ff2f86"><i class="fa fa-bullhorn fa-fw"></i></p>
        <b style="font-size: 1.2em;color:#ff2f86;">欢迎使用 OneNav 主题</b>
        <p>当前主题还未授权，部分功能无法使用，请在主题设置中进行授权验证</p><p><a class="button button-primary" href="'.esc_url(admin_url('admin.php?page=theme_settings#tab=%e5%bc%80%e5%a7%8b%e4%bd%bf%e7%94%a8')).'">立即授权</a><a style="margin-left: 10px;" class="button" href="https://www.iotheme.cn/" target="_blank">访问一为主题官网</a></p><p></p>
        </div>';
    echo $con;
    }
}
add_action('admin_notices', 'io_notice_update');

//获取主题设置链接
function io_get_admin_csf_url($tab = '')
{
    $tab_array = explode("/", $tab);
    $tab_array_sanitize = array();
    foreach ($tab_array as $tab_i) {
        $tab_array_sanitize[] = sanitize_title($tab_i);
    }
    $tab_attr = esc_attr(implode("/", $tab_array_sanitize));
    $url = add_query_arg('page', 'theme_settings', admin_url('admin.php'));
    $url = $tab ? $url . '#tab=' . $tab_attr : $url;
    return esc_url($url);
}
//保存主题更新主题版本
function io_save_theme_version()
{
    update_option('onenav_update_version', VERSION);
}
add_action("csf_io_get_option_save_after", 'io_save_theme_version');
