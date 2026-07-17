<?php
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-12-28 23:32:36
 * @LastEditors: iowen
 * @LastEditTime: 2021-12-28 23:41:22
 * @FilePath: \onenav\inc\mailfunc\plates\emails\add-links.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$this->layout('base', array('blogName' => get_bloginfo('name'), 'logo' => io_get_option('logo_small_light'), 'home' => home_url())) ?>
<p>网站有新的链接提交：</p>
<p style="border-bottom:#ddd 1px solid;border-left:#ddd 1px solid;padding-bottom:20px;background-color:#eee;margin:15px 0px;padding-left:20px;padding-right:20px;border-top:#ddd 1px solid;border-right:#ddd 1px solid;padding-top:20px">
链接名称：<?=$this->e($link_name)?><br>
链接地址：<?=$this->e($link_url)?><br>
链接简介：<?=$this->e($link_description)?><br>
链接Logo：<?=$this->e($link_image)?>
</p>
<p>您可以点击 <a style="color:#00bbff;text-decoration:none" href="<?=$this->e($link_admin)?>" target="_blank">以审核该链接</a></p>
