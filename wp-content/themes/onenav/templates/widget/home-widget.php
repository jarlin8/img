<?php 
/*
 * @Theme Name:One Nav
 * @Theme URI:https://www.iotheme.cn/
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2022-02-05 16:03:25
 * @LastEditors: iowen
 * @LastEditTime: 2022-07-19 15:25:36
 * @FilePath: \onenav\templates\widget\home-widget.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$style = '';
if( io_get_option('search_skin',false,'search_big')=='1' && io_get_option('search_skin','no-bg','big_skin')!='no-bg' && io_get_option('search_skin',false,'post_top') && io_get_option('search_position','') && in_array("home",io_get_option('search_position')) ){
    if (is_home() || is_front_page()) {
        $style = 'style="margin-top:-6rem!important"';
    }else if(get_post_meta(get_queried_object_id(),'search_box',true)){
        $style = 'style="margin-top:-6rem!important"';
    }
}
if( is_home() || is_front_page() ) {
    $widgets = io_get_option('home_widget',array('enabled'=>array()),'enabled');
}else{
    $widgets = '';
    if( $widget = get_post_meta(get_queried_object_id(),'widget',true) ){
        $widgets = isset($widget['enabled'])?$widget['enabled']:'';
    }
}
if(is_array($widgets) && count($widgets)>0){
?>
<div class="" <?php echo $style ?>>
<?php
foreach($widgets as $key => $value){
    get_template_part( 'templates/widget/widget', $key ); 
}
?>
</div>
<?php } ?>