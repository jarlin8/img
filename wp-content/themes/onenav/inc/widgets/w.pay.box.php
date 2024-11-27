<?php
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2023-03-11 21:58:46
 * @LastEditors: iowen
 * @LastEditTime: 2023-03-11 22:01:53
 * @FilePath: \onenav\inc\widgets\w.pay.box.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

CSF::createWidget( 'w_pay_box', array(
    'title'       => '付费购买',
    'classname'   => 'io-widget-pay-box',
    'description' => '显示当前文章的付费内容',
    'fields'      => array(
    )
) );
if ( ! function_exists( 'w_pay_box' ) ) {
    function w_pay_box( $args, $instance ) {
        echo $args['before_widget'];
        hot_search($instance); 
        echo $args['after_widget'];
    }
}
