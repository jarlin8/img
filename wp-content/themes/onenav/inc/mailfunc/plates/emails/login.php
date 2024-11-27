<?php 
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-08-20 10:24:32
 * @LastEditors: iowen
 * @LastEditTime: 2022-06-11 00:45:44
 * @FilePath: \onenav\inc\mailfunc\plates\emails\login.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$this->layout('base', array('blogName' => get_bloginfo('name'), 'logo' => io_get_option('logo_small_light'), 'home' => home_url())) ?>

<p>你好！你的博客空间(<?php echo get_bloginfo('name'); ?>)有成功登录！</p>
<p>请确定是您自己的登录, 以防别人攻击! 登录信息如下: </p>
<div style="background-color:#fefcc9; padding:10px 15px; border:1px solid #f7dfa4; font-size: 12px;line-height:160%;">
    登录名: <?=$this->e($loginName)?>
    <br>登录密码: ******
    <br>登录时间: <?php echo date("Y-m-d H:i:s",current_time( 'timestamp' )); ?>
    <br>登录IP: <?=$this->e($ip)?><?php echo ' [' . io_get_ip_location($ip) . ']'; ?>
</div>