<?php 
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-03-01 10:19:00
 * @LastEditors: iowen
 * @LastEditTime: 2022-07-03 23:49:44
 * @FilePath: \onenav\404.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>



<style type="text/css">
.data-null {text-align: center;padding:100px 0}
.data-null h1 {font-size: 6rem;padding: 0;}
.data-null .btn-home {color: #fff;position: relative;top: 0;padding: 10px 75px;background: #f1404b;font-weight: 600;border-radius: 900px;transition: .2s;box-shadow: 0px 5px 20px -3px rgba(249, 100, 90, .6);}
.data-null .btn-home:hover {top:2px;background:#333;box-shadow:none;transition:.2s}
.screen-reader-text{display: none;}
</style>
<div class="container">
    <div class="row">
        <div class="col-12 col-lg-12">
            <section class="data-null">
                <h1 class="font-theme d-none">404</h1>
                <img src="<?php echo get_theme_file_uri('/images/404.svg') ?>" style="max-width: 560px;" alt="404"/>
                <p><?php _e('抱歉，没有你要找的内容...','i_theme') ?></p>
                <div style="margin-top: 30px">
                    <a class="btn-home" href="<?php echo esc_url( home_url() ) ?>"><?php _e('返回首页','i_theme') ?></a>
                </div>
            </section>
        </div>
    </div>
</div><!-- main-content end -->
<?php get_footer(); ?>