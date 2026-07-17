<?php
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2023-03-01 15:24:35
 * @LastEditors: iowen
 * @LastEditTime: 2023-03-22 22:54:07
 * @FilePath: \onenav\iopay\functions\iopay-post.php
 * @Description: 
 */

/**
 * 获取标题
 * @param mixed $pay_mate
 * @param mixed $post
 * @param mixed $index
 * @return mixed
 */
function iopay_get_post_pay_title($buy_option = array(), $post = '', $index = 0){
    if (!$buy_option) {
        if (!$post) {
            $post_id = get_the_ID();
            $post    = get_post($post_id);
        }

        $buy_option = get_post_meta($post->ID, 'buy_option', true);
    }
    $_data = iopay_get_post_pay_price_data($post, $index);
    $_name = '';
    if (isset($_data['name'])) {
        $_name = '-' . $_data['name'];
    }
    $pay_title = !empty($buy_option['pay_title']) ? $buy_option['pay_title'] : get_the_title($post->ID);
    return $pay_title . $_name;
}

/**
 * 根据 index 重新排序资料
 * 
 * @param mixed $annex
 * @return mixed
 */
function iopay_get_annex_sort_by_index($annex){
    $data = array();
    foreach ($annex as $value) {
        if (isset($value['index'])) {
            $data[$value['index']] = $value;
        }
    }
    if (empty($data))
        return $annex;
    else
        return $data;
}

/**
 * 收银台获取商品价格
 * 
 * @param mixed $post
 * @param mixed $index
 * @return array
 */
function iopay_get_post_pay_price_data($post, $index){
    $post_id    = $post->ID;
    $buy_option = get_post_meta($post_id, 'buy_option', true);
    $buy_data   = $buy_option;
    $data       = array();

    if ($index != 0) {
        switch ($post->post_type) {
            case 'app':
                if ('multi' == $buy_option['price_type'] && 'annex' === $buy_option['buy_type']) { //附件模式且是多价格
                    $buy_data            = io_get_app_down_by_index($post_id)[$index];
                    $buy_data['io_name'] = $buy_data['app_version'];
                }
                break;
            case 'sites':
                if ('multi' == $buy_option['price_type'] && 'annex' === $buy_option['buy_type']) { //附件模式且是多价格
                    $buy_data            = io_get_down_by_index($buy_option['annex_list'])[$index];
                    $buy_data['io_name'] = empty($buy_data['name']) ? __('资源', 'i_theme') . $index : $buy_data['name'];
                }
                break;

            default:
                break;
        }
    }
    $data['name']      = isset($buy_data['io_name']) ? $buy_data['io_name'] : $buy_data['pay_title'];
    $data['price']     = isset($buy_data['price']) ? round((float) $buy_data['price'], 2) : 0;
    $data['pay_price'] = isset($buy_data['pay_price']) ? round((float) $buy_data['pay_price'], 2) : 0;

    return $data;
}


/**
 * 根据序号排序资源
 * 
 * @param mixed $post_id
 * @param bool $first 取第一个值
 * @return array
 */
function io_get_down_by_index($lists, $first = false){
    $data  = array();
    foreach ($lists as $val) {
        $data[$val['index']] = $val;
    }
    if ($first) {
        return $lists[0];
    }
    return $data;
}