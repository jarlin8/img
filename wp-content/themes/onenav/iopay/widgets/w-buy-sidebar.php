<?php
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2023-03-11 17:11:50
 * @LastEditors: iowen
 * @LastEditTime: 2023-03-24 23:02:10
 * @FilePath: \onenav\iopay\widgets\w-buy-sidebar.php
 * @Description: 
 */

/**
 * 付费小窗口
 * 
 * @param bool   $echo
 * @return mixed
 */
function iopay_buy_sidebar_html($echo = true){
    global $post;
    $post_id   = $post->ID;
    $post_type = $post->post_type;

    $html='';
    $user_level = get_post_meta($post_id, '_user_purview_level', true);
    if (!$user_level) {
        update_post_meta($post_id, '_user_purview_level', 'all');
        return $html;
    }

    if ($user_level && 'buy' === $user_level) {
        $buy_option = get_post_meta($post_id, 'buy_option', true);
    }
    if (isset($buy_option)) {
        if ('annex' === $buy_option['buy_type']) {
            $html = '<div class=" col-12 col-md-12 col-lg-4 mt-4 mt-lg-0">';
            $html .= iopay_buy_sidebar_widgets();
            $html .= '</div>';
        }
    }

    if($echo){
        echo $html;
    } else {
        return $html;
    }
}

/**
 * widgets
 * 
 * @param mixed $post
 * @return string
 */
function iopay_buy_sidebar_widgets($post = ''){
    if(!$post){
        global $post;
    }
    $post_id   = $post->ID;
    $buy_option = get_post_meta($post_id, 'buy_option', true);
    $unit       = '<span class="text-xs">' . io_get_option('pay_unit', '￥') . '</span>';
    $btn_name   = __('立即购买', 'i_theme');

    $_c = '';
    if ('single' == $buy_option['price_type']) {
        $pay_price = round((float) $buy_option['pay_price'], 2);
        $org_price = round((float) $buy_option['price'], 2);
        $org_price = $org_price && $org_price > $pay_price ? '<span class="original-price d-inline-block">' . $unit . $org_price . '</span>' : '';
        $icon      = '<i class="iconfont icon-buy_car mr-2"></i>';

        $_c .= '<div class="bg-blur-20 io-radius shadow mb-3">';
        $_c .= '<div class="p-2 text-center"><span class="text-64 font-weight-bold">' . $unit . $pay_price . '</span> ' . $org_price . '</div>';
        $_c .= '</div>';

        $url = esc_url(add_query_arg(array('action' => 'pay_cashier_modal', 'id' => $post_id, 'index' => 0), admin_url('admin-ajax.php')));
        $_c .= '<a href="' . $url . '" class="position-relative btn btn-block vc-blue btn-shadow io-ajax-modal-get nofx mb-3"  title="' . $btn_name . '">' . $icon . $btn_name . '</a>';
    } else {
        switch ($post->post_type) {
            case 'post':
            case 'sites':
                $lists = $buy_option['annex_list'];
                $pay_price = 0;
                $org_price = 0;
                // 资源列表按钮
                $l_btn = '';
                $_i = '<img src="' . get_theme_file_uri('/iopay/assets/img/annex.svg') . '" alt="annex" width="24" height="24">';
                foreach ($lists as $l) {
                    $_pay_price = round((float) $l['pay_price'], 2);
                    $_org_price = round((float) $l['price'], 2);
                    $pay_price += $_pay_price;
                    $org_price += (empty($_org_price) ? $_pay_price : $_org_price);
                    $url        = esc_url(add_query_arg(array('action' => 'pay_cashier_modal', 'id' => $post_id, 'index' => $l['index']), admin_url('admin-ajax.php')));
                    $_name      = empty($l['name']) ? __('资源', 'i_theme') . $l['index'] : $l['name'];
                    $_org_price = $_org_price && $_org_price > $_pay_price ? ' <span class="original-price d-inline-block">' . $unit . $_org_price . '</span>' : '';
                    $icon       = '<i class="iconfont icon-buy_car"></i>';

                    $l_btn .= '<div class="d-flex align-items-center bg-muted io-radius p-2 mb-2">';
                    $l_btn .= $_i;
                    $l_btn .= '<span class="ml-1">' . $_name . '</span>';
                    $l_btn .= '<div class="ml-auto">' . $unit . '<span class="text-xl">' . $_pay_price . '</span>' . $_org_price . '</div>';
                    $l_btn .= '<a href="' . $url . '" class="btn vc-blue io-ajax-modal-get nofx ml-2"  title="' . $_name . '">' . $icon . '</a>';
                    $l_btn .= '</div>';
                }
                $org_price = $org_price && $org_price > $pay_price ? '<div class="original-price d-inline-block">' . $unit . $org_price . '</span></div>' : '';
                $_c .= '<div class="bg-blur-20 io-radius shadow mb-3">';
                $_c .= '<div class="p-2 text-center"><span class="text-64 font-weight-bold">' . $unit . $pay_price . '</span> ' . $org_price . '</div>';
                $_c .= '</div>';
                $_c .= '<div class="position-relative buy-btn-group">' . $l_btn . '</div>';
                break;

            default:
                break;
        }
    }
    $class = 'fx-blue';
    $html = '<div class="io-pay-box modal-header-bg semi-white overflow-hidden position-relative shadow io-radius px-3 pb-3 pt-2 ' . $class . '">';
    $html .= '<div class="mb-2">'.iopay_get_buy_type_name($buy_option['buy_type'],true).'</div>';
    $html .= '<div class="pay-box-body">';
    $html .= $_c;
    $html .= iopay_pay_tips_box('');
    $html .= '</div>';
    $html .= '</div>';

    return $html;
}
