<?php
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-11-26 23:51:17
 * @LastEditors: iowen
 * @LastEditTime: 2022-07-09 16:58:39
 * @FilePath: \onenav\inc\widgets\w.hot.api.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

CSF::createWidget( 'hot_api', array(
    'title'       => '今日热榜api',
    'classname'   => 'io-widget-hot-api',
    'description' => __( '按条件显示热门网址，可选“浏览数”“点赞收藏数”“评论量”','io_setting' ),
    'fields'      => array(
        array(
            'id'        => 'name',
            'type'      => 'text',
            'title'     => '名称',
            'default'   => '百度热点',
        ),
        array(
            'id'           => 'hot_type',
            'type'         => 'button_set',
            'title'        => __('类型','io_setting'),
            'options'      => array(
                'json'  => 'JSON',
                'rss'   => 'RSS',
                'api'   => 'API（需 KEY）',
            ),
            'default'      => 'api',
        ),
        array(
            'type'    => 'submessage',
            'style'   => 'success',
            'content' => '<h4>前往“<a href="'.esc_url(add_query_arg('page', 'hot_search_settings', admin_url('options-general.php'))).'">自定义热榜</a>”设置配置自定义热榜</h4>下方ID为对应规则的序号，如1，6，8',
            'dependency' => array( 'hot_type', '!=', 'api')
        ),
        array(
            'id'        => 'rule_id',
            'type'      => 'text',
            'title'     => '热榜ID',
            'after'     =>'如果选择 JSON 或者 RSS ，此项填“自定义热榜”对应类型的序号，如 JSON 类型的第一个，则填 1<br>如果选择 API ，请前往“ID列表”查看ID<br>
            <i class="fa fa-fw fa-info-circle fa-fw"></i> api热榜ID列表：<a target="_blank" href="https://www.ionews.top/list.html">查看</a>',
            'default'   => '100000',
        ),
        array(
            'id'        => 'ico',
            'type'      => 'upload',
            'title'     => 'ico',
            'add_title' => __('上传','io_setting'),
            'after'     => '<p class="cs-text-muted">'.__('建议30px30','io_setting'),
            'default'   => get_theme_file_uri('/images/hotico/baidu.png'),
        ),
        array(
            'id'      => 'is_iframe',
            'type'    => 'switcher',
            'title'   => 'iframe 加载',
            'label'   => '在页面内以 iframe 加载，如果目标站不支持，请关闭',
            'default' => false
        ),
    )
) );
if ( ! function_exists( 'hot_api' ) ) {
    function hot_api( $args, $instance ) {
        echo $args['before_widget'];
        hot_search($instance); 
        echo $args['after_widget'];
    }
}
