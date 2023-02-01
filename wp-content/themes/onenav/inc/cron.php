<?php
/*
 * @Theme Name:One Nav
 * @Theme URI:https://www.iotheme.cn/
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2022-01-25 03:01:51
 * @LastEditors: iowen
 * @LastEditTime: 2022-06-24 22:13:05
 * @FilePath: \onenav\inc\cron.php
 * @Description: 定时任务
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * 执行的定时任务
 * daily 每天   hourly 每小时    twicedaily 每日两次    10min 10分钟
 */
function io_setup_cron_events_schedule(){
    if (io_get_option('leader_board')) {
        if(!wp_next_scheduled('io_clean_expired_ranking_data')){
            wp_schedule_event(time(), 'daily', 'io_clean_expired_ranking_data');
        }
    } else {
        wp_clear_scheduled_hook( 'io_clean_expired_ranking_data' );
    }
    if(IO_PRO && !wp_next_scheduled('io_daily_state_event')){
        wp_schedule_event(time(), 'daily', 'io_daily_state_event');
    }
    if ( io_get_option('server_link_check',false) ) {
        if ( ! wp_next_scheduled( 'io_cron_check_links' ) ) {
            wp_schedule_event( time(), 'hourly', 'io_cron_check_links' );
        }
    } else {
        wp_clear_scheduled_hook( 'io_cron_check_links' );
    }
}
add_action('wp', 'io_setup_cron_events_schedule');

/**
 * 自动删除排行榜历史事件.
 * 
 * @return bool
 */
function io_auto_delete_close_order(){
    global $wpdb;
    $day = io_get_option('how_long');
    $sql = "DELETE FROM {$wpdb->ioviews} where DATEDIFF(curdate(), `time`)>{$day}";
    // `time` < date_sub(curdate(), INTERVAL 30 DAY 
    //mysqli_query($link, $sql) or die('删除数据出错：' . mysqli_error($link));
    $delete = $wpdb->query($sql);
    return (bool) $delete;
}
add_action('io_clean_expired_ranking_data', 'io_auto_delete_close_order');
