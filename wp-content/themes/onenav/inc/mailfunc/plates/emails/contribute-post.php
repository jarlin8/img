<?php
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-08-20 13:29:51
 * @LastEditors: iowen
 * @LastEditTime: 2022-07-04 23:37:49
 * @FilePath: \onenav\inc\mailfunc\plates\emails\contribute-post.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$this->layout('base', array('blogName' => get_bloginfo('name'), 'logo' => io_get_option('logo_small_light'), 'home' => home_url())) ?>

<h3>有投稿需要审核。</h3>
<div style="background-color:#fefcc9; padding:10px 15px; border:1px solid #f7dfa4; font-size: 12px;line-height:160%;">
<p>文章标题《<?=$this->e($postTitle)?>》</p>
<p>内容摘要：<?=$this->e($summary)?></p>
<p>投稿时间：<?=$this->e($time)?></p>
</div>
<p>您可以打开下方链接以审核投稿文章：<a style="color:#00bbff;text-decoration:none" href="<?=$this->e($link)?>" target="_blank">前往审核</a></p>