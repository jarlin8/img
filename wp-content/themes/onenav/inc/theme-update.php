<?php
/*
 * @Theme Name:One Nav
 * @Theme URI:https://www.iotheme.cn/
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-04-15 04:33:41
 * @LastEditors: iowen
 * @LastEditTime: 2023-01-18 23:31:48
 * @FilePath: \onenav\inc\theme-update.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

if( is_admin() ){
    updataDB();
}
function updataDB(){
    if(is_admin()){
        $version = get_option( 'onenav_version',false );
        $rewrite = false;
        if(!$version){
            $version = VERSION;
            update_option( 'onenav_version', $version );
        }
        if ( version_compare( $version, '3.0330', '<' ) && version_compare( $version, '2.0407', '>' ) ) {
            global $wpdb;
            $list = $wpdb->get_results("SELECT * FROM $wpdb->users");
            if($list) {
                foreach($list as $value){
                    if(substr($value->user_login , 0 , 2)=="io"){
                        //update_user_meta($value->ID, 'name_change', 1);
                        if($value->qq_id && !get_user_meta($value->ID,'qq_openid')){
                            update_user_meta($value->ID, 'qq_avatar', get_user_meta($value->ID,'avatar',true));
                            update_user_meta($value->ID, 'qq_name', $value->display_name);
                            update_user_meta($value->ID, 'qq_openid', $value->qq_id);
                            update_user_meta($value->ID, 'avatar_type', 'qq');
                        }
                        if($value->wechat_id && !get_user_meta($value->ID,'wechat_openid')){
                            update_user_meta($value->ID, 'wechat_avatar', get_user_meta($value->ID,'avatar',true));
                            update_user_meta($value->ID, 'wechat_name', $value->display_name);
                            update_user_meta($value->ID, 'wechat_openid', $value->wechat_id);
                            update_user_meta($value->ID, 'avatar_type', 'wechat');
                        }
                        if($value->sina_id && !get_user_meta($value->ID,'sina_openid')){
                            update_user_meta($value->ID, 'sina_avatar', get_user_meta($value->ID,'avatar',true));
                            update_user_meta($value->ID, 'sina_name', $value->display_name);
                            update_user_meta($value->ID, 'sina_openid', $value->sina_id);
                            update_user_meta($value->ID, 'avatar_type', 'sina');
                        }
                    }
                }
            }
            $wpdb->query("ALTER TABLE `$wpdb->iocustomurl` CHANGE `url` `url` TEXT DEFAULT NULL");
            $wpdb->query("ALTER TABLE `$wpdb->iocustomurl` CHANGE `url_name` `url_name` TEXT DEFAULT NULL");
            $wpdb->query("ALTER TABLE `$wpdb->iocustomurl` CHANGE `url_ico` `url_ico` TEXT DEFAULT NULL");
            $wpdb->query("ALTER TABLE `$wpdb->iocustomterm` CHANGE `name` `name` TEXT DEFAULT NULL");
            $wpdb->query("ALTER TABLE `$wpdb->iocustomterm` CHANGE `ico` `ico` TEXT DEFAULT NULL");

            if(!$wpdb->query("SELECT post_id FROM $wpdb->iocustomurl")){
                $wpdb->query("ALTER TABLE $wpdb->iocustomurl ADD post_id bigint(20)");
            }
            if(!$wpdb->query("SELECT summary FROM $wpdb->iocustomurl")){
                $wpdb->query("ALTER TABLE $wpdb->iocustomurl ADD summary varchar(255) DEFAULT NULL");
            }
            update_option( 'onenav_version', '3.0330' );
            $rewrite = true;
        }
        if ( version_compare( $version, '3.0731', '<' ) && version_compare( $version, '3.0330', '>=' ) ) {
            global $wpdb;
            $wpdb->query("ALTER TABLE $wpdb->iocustomterm ADD INDEX `user_id` (`user_id`);");
            $wpdb->query("ALTER TABLE $wpdb->iocustomurl ADD INDEX `user_id` (`user_id`);");
            $wpdb->query("ALTER TABLE $wpdb->iocustomurl ADD INDEX `term_id` (`term_id`);");
            update_option( 'onenav_version', '3.0731' );
            $rewrite = true;
        }
        if ( version_compare( $version, '3.0901', '<' ) && version_compare( $version, '3.0731', '>=' ) ) {
            global $wpdb;
            $wpdb->query("ALTER TABLE `$wpdb->iomessages` CHANGE `msg_read` `msg_read` TEXT DEFAULT NULL");
            if(!$wpdb->query("SELECT `meta` FROM $wpdb->iomessages")){
                $wpdb->query("ALTER TABLE $wpdb->iomessages ADD `meta` text DEFAULT NULL");
            }
            update_option( 'onenav_version', '3.0901' );
            $rewrite = true;
        }
        if ( version_compare( $version, '3.1421', '<' ) ) {
            global $wpdb ,$iodb;
            //$iodb = new IODB();
            $list = $wpdb->get_results("SELECT * FROM `$wpdb->postmeta` WHERE (`meta_key` IN ('_app_screenshot','_sites_screenshot') AND `meta_value` != '')");
            if($list){
                //$datas=array();
                foreach($list as $value){
                    $app_screen = explode( ',', $value->meta_value );
                    $data = array();
                    for ($i=0;$i<count($app_screen);$i++) {
                        $data[] = array(
                            'img'=>wp_get_attachment_image_src($app_screen[$i], 'full')[0]
                        );
                    }
                    update_post_meta( $value->post_id, '_screenshot', $data );
                    //$datas[] = array( $value->post_id, '_screenshot', maybe_serialize($data)); 
                }
                //$wpdb->query($iodb->multArrayInsert($wpdb->postmeta, array("post_id","meta_key","meta_value"),$datas));
            }
            update_option( 'onenav_version', '3.1421' );
            $rewrite = true;
        }
        if ( version_compare( $version, '3.1918', '<' ) && version_compare( $version, '3.0330', '>=' ) ) {
            global $wpdb;
            $wpdb->query("ALTER TABLE $wpdb->iocustomterm ADD `parent` bigint(20) NOT NULL DEFAULT 0 AFTER `user_id`");
            update_option( 'onenav_version', '3.1918' );
            $rewrite = true;
        }
        if( version_compare( $version, '3.2118', '<' ) ){
            global $wpdb;
            $list = $wpdb->get_results("SELECT * FROM $wpdb->users WHERE `user_email` REGEXP '^io.*@io.com$'");
            if($list ){
                foreach($list as $value){
                    $wpdb->query("UPDATE $wpdb->users SET `user_email`='' WHERE `ID`=$value->ID");
                }
            }
            update_option( 'onenav_version', '3.2118' );
        }
        if($rewrite){
            wp_cache_flush();
            global $wp_rewrite;
            $wp_rewrite->flush_rules();
        }
    }
}
