<?php
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2023-02-10 01:36:08
 * @LastEditors: iowen
 * @LastEditTime: 2023-02-10 04:12:46
 * @FilePath: \onenav\inc\functions\io-site.php
 * @Description: 
 */

/**
 * 获取网址类型文章的缩略图
 * 
 * @param string $title      网址标题
 * @param string $link       网址目标地址
 * @param string $type       网址类型
 * @param bool   $show       网址是否可见
 * @param bool   $is_preview 是否显示预览
 * @return string
 */
function get_site_thumbnail($title, $link, $type, $show, &$is_preview = ''){
    $img_url = get_post_meta_img(get_the_ID(), '_thumbnail', true);
    if ($type == "down")
        return $img_url;
    if($show && $type == "wechat"){
        // return get_site_wechat_qr(); TODO 获取微信二维码
    }
    if($img_url == '' || io_get_option('sites_preview','') ){
        if( $link != '' || ($type == "sites" && $link != '') ){
            if($img_url = get_post_meta(get_the_ID(), '_sites_preview', true)){
                $is_preview = true;
            }else{
                if(!io_get_option('sites_preview',false)){
                    if(empty($img_url) && io_get_option('is_letter_ico',false) && !io_get_option('first_api_ico',false)){
                        $img_url = io_letter_ico($title, 160);
                    }else{
                        $img_url = (io_get_option('ico-source','https://api.iowen.cn/favicon/','ico_url') .format_url($link) . io_get_option('ico-source','.png','ico_png'));
                    }
                }else{
                    $img_url = '//s0.wp.com/mshots/v1/'. format_url($link,true) .'?w=383&h=328';
                    $is_preview = true;
                }
            }
        } elseif ($type == "wechat") {
            $img_url = get_theme_file_uri('/images/qr_ico.png');
        } else {
            $img_url = get_theme_file_uri('/images/favicon.png');
        }
    }
    return $img_url;
}