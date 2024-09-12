<?php 
/*
Plugin Name: DMD淘宝客
Description: 代码狗开发的一款在文章中快速插入淘宝客推广产品的插件。
Plugin URI: https://www.daimadog.com/5956.html
Version: 1.4.0
Author: 代码狗
Author URI: https://www.daimadog.com/
Text Domain: daimadog
*/

/*
 * The plugin was originally created by daimadog.com
 */



//添加编辑器功能扩展
function dmd_tbk_plugin() {
   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
      return;
   }
 
   if ( get_user_option('rich_editing') == 'true' ) {
      add_filter( 'mce_external_plugins', 'dmd_tbk_external_plugins_filter' );
      add_filter( 'mce_buttons', 'dmd_tbk_buttons_filter' );
   }
 
}
add_action('admin_head', 'dmd_tbk_plugin');
function dmd_tbk_external_plugins_filter($plugin_array) {
    $plugin_array['dmd_tbk_plugin'] = plugin_dir_url(__FILE__ ) . 'zy/tbkkz.js';
        
    return $plugin_array;
}
function dmd_tbk_buttons_filter($buttons) {
    array_push($buttons, 'dmd_tbk_plugin');
        
    return $buttons;
}
//后台编辑器添加css支持
add_editor_style( plugin_dir_url(__FILE__ ) . 'zy/taobaok.css' );
//添加插件输出js、css支持
add_action('wp_head', 'dmd_tbk_plugin_headzy');
function dmd_tbk_plugin_headzy(){
	echo "<link rel='stylesheet' id='_main-css'  href='".plugin_dir_url(__FILE__ )."zy/taobaok.css' type='text/css' media='all' />\n";
	$dmd_tbk_jquery= esc_attr(get_option('dmd_tbk_jquery'));
	if($dmd_tbk_jquery){
		echo "<script type='text/javascript' src='".plugin_dir_url(__FILE__ )."zy/jquery.min.js'></script>\n";
	}
}
add_action('wp_footer','dmd_tbk_plugin_footerzy',20,1);
function dmd_tbk_plugin_footerzy(){
	echo "<script type='text/javascript' src='".plugin_dir_url(__FILE__ )."zy/taobaok.js'></script>\n";
}
//插件配置页面
add_action('admin_menu', 'dmd_tbk_submenu');
function dmd_tbk_submenu() {
  add_options_page(__('DMD淘宝客设置'), __('淘宝客设置'), 'administrator', 'dmd-tbk-plugin', 'add_dmd_tbk_submenu');
}

function add_dmd_tbk_submenu() {
  if($_POST['dmd_tbk_hidden'] == 'y') {
       update_option('dmd_tbk_appKey',$_POST['dmd_tbk_appKey']); 
       update_option('dmd_tbk_appSecret',$_POST['dmd_tbk_appSecret']); 
       update_option('dmd_tbk_jquery',$_POST['dmd_tbk_jquery']); 
       update_option('dmd_tbk_link',$_POST['dmd_tbk_link']); 
       
       update_option('dmd_jd_unionId',$_POST['dmd_jd_unionId']); 
       
?>
     <div id="message" style="background-color: green; color: #ffffff;">保存成功 !</div>
<?php
   }
?>
  <div>
      <h2>DMD淘宝客设置</h2>
      <form action="" method="post" id="my_plugin_test_form">
          <p>
              <label for="test_insert_options">大淘客appKey:</label>
              <input type="text" id="dmd_tbk_appKey" name="dmd_tbk_appKey" value="<?php  echo esc_attr(get_option('dmd_tbk_appKey')); ?>"  />
          </p>
          <p>
              <label for="test_insert_options">大淘客appSecret:</label>
              <input type="text" id="dmd_tbk_appSecret" name="dmd_tbk_appSecret" value="<?php  echo esc_attr(get_option('dmd_tbk_appSecret')); ?>"  />
          </p>
        
           <p>
              <label for="test_insert_options">京推广联盟ID:</label>
              <input type="text" id="dmd_jd_unionId" name="dmd_jd_unionId" value="<?php  echo esc_attr(get_option('dmd_jd_unionId')); ?>"  />
          </p>
          
          
          <p>
            <label for="dmd_jquery_options">jquery支持:</label>
			<input <?php echo esc_attr(get_option('dmd_tbk_jquery'))? "checked='true'":""; ?> id="mode_s" class="checkbox of-input" type="checkbox" name="dmd_tbk_jquery">
			<label class="explain" for="mode_s">头部加载jquery（复制淘口令需要jquery支持），如果你的主题已经加载过jquery，可以不开启</label></div>
          </p>
          <p>
            <label for="dmd_link_options">推广链接设置:</label>
			<input <?php echo esc_attr(get_option('dmd_tbk_link'))? "checked='true'":""; ?> id="dmd_link" class="checkbox of-input" type="checkbox" name="dmd_tbk_link">
			<label class="explain" for="dmd_link">淘宝客推广链接默认使用短链接，需要长链接请勾选此项！</label></div>
          </p>
          <p>
              <input type="submit" name="submit" value="保存" class="button button-primary" />
              <input type="hidden" name="dmd_tbk_hidden" value="y"  />
          </p>
      </form>
  </div>
 <?php 
}
//短代码支持
//淘宝客推广短代码
function dmd_tg($array,$content) 
{
    extract(shortcode_atts(array(
		'num' => 10,
		'action'=>'zhb',
		'lx'=>'jd'
	), $array));
	$html= tg_retrun($lx,$action,$num,$content);
return $html;
} 
add_shortcode('tg', 'dmd_tg');

function tg_retrun($lx,$action,$num,$content){
    	switch ($action) {
	    case 'search':
	        $data='{"lx":"'.$lx.'","action":"'.$action.'","keyword":"'.$content.'","num":'.$num.'}';
	        break;
	    default:
	         $data='{"lx":"'.$lx.'","action":"'.$action.'","num":'.$num.'}';
	        break;
	}
   return '<div id="cpctg" class="cpctg" data-url="'.plugin_dir_url(__FILE__ ).'zy/cpcapi.php" data-text='.$data.'><div style="margin: 0px auto;width:250px;">商品加载中，请稍后······</div></div>';
}
?>