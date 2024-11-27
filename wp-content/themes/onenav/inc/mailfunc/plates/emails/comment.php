<?php 
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-08-20 10:24:32
 * @LastEditors: iowen
 * @LastEditTime: 2021-12-28 23:42:27
 * @FilePath: \onenav\inc\mailfunc\plates\emails\comment.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$this->layout('base', array('blogName' => get_bloginfo('name'), 'logo' => io_get_option('logo_small_light'), 'home' => home_url())) ?>

<p><?=$this->e($commentAuthor)?>在文章<a href="<?=$this->e($commentLink)?>" target="_blank"><?=$this->e($postTitle)?></a>中发表了回复，快去看看吧：<br></p>
<p style="padding:10px 15px;background-color:#f4f4f4;margin-top:10px;color:#000;border-radius:3px;"><?=$this->e($commentContent)?></p>