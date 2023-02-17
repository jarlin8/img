<?php
/*
 * @Theme Name:One Nav
 * @Theme URI:https://www.iotheme.cn/
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-06-03 08:56:00
 * @LastEditors: iowen
 * @LastEditTime: 2023-02-10 02:23:38
 * @FilePath: \onenav\single-app.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>
    <div id="content" class="container my-4 my-md-5">
                <?php  
                $app_type = get_post_meta(get_the_ID(), '_app_type', true);
                $user_level = get_post_meta($post->ID, '_user_purview_level', true);
                if((!is_user_logged_in() && $user_level && $user_level != 'all')){
                    get_user_level_directions_html( 'app' );
                } else {
                    include( get_theme_file_path('/templates/content-app.php') ); 
                }
                ?>
                <h2 class="text-gray text-lg my-4"><i class="site-tag iconfont icon-tag icon-lg mr-1" ></i><?php echo sprintf(__('相关%s','i_theme'),get_app_type_name($app_type)) ?></h2>
                <div class="row mb-n4"> 
                    <?php get_template_part( 'templates/related','app' ); ?>
                </div>
                <?php 
                if ( comments_open() || get_comments_number() ) :
                    comments_template();
                endif; 
                ?>
            </div><!-- content-layout end -->
        </div><!-- content-wrap end -->
        <?php get_sidebar('app');  ?>
        </main>
    </div>
<?php get_footer(); ?>