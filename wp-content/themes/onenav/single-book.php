<?php
/*
 * @Theme Name:One Nav
 * @Theme URI:https://www.iotheme.cn/
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-06-03 08:56:00
 * @LastEditors: iowen
 * @LastEditTime: 2023-02-10 02:15:07
 * @FilePath: \onenav\single-book.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>
    <div id="content" class="container my-4 my-md-5">
                <?php 
                $user_level = get_post_meta($post->ID, '_user_purview_level', true);
                $book_type = get_post_meta(get_the_ID(), '_book_type', true);
                if((!is_user_logged_in() && $user_level && $user_level != 'all')){
                    get_user_level_directions_html( 'book');
                } else {
                    include(get_theme_file_path('/templates/content-book.php'));
                }
                ?>
                <h2 class="text-gray text-lg my-4"><i class="site-tag iconfont icon-book icon-lg mr-1" ></i><?php echo sprintf(__('相关%s','i_theme'),get_book_type_name($book_type)) ?></h2>
                <div class="row mb-n4"> 
                    <?php get_template_part( 'templates/related','book' ); ?>
                </div>
                <?php 
                if ( comments_open() || get_comments_number() ) :
                    comments_template();
                endif; 
                ?>
            </div><!-- content-layout end -->
        </div><!-- content-wrap end -->
        <?php get_sidebar('book');  ?>
        </main>
    </div>
<?php get_footer(); ?>