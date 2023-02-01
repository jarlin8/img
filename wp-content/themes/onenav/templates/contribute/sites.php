<?php
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2022-07-04 18:39:10
 * @LastEditors: iowen
 * @LastEditTime: 2022-07-19 12:09:56
 * @FilePath: \onenav\templates\contribute\sites.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$u_id   = get_current_user_id();
$option = io_get_option('sites_tg_opt',array(
    'is_publish'  => false,
    'tag_limit'   => 5,
    'img_size'    => 64,
));

if (!$option['is_publish']) {
    $btn_text = __('提交审核','i_theme');
} else {
    $btn_text = __('提交发布','i_theme');
}
// 可投稿内容，如果只要网址，请删除'wechat'行
$lists = apply_filters('io_sites_contribute_list_filters', array(
    'sites'  => __('网址','i_theme'),
    'wechat' => __('公众号','i_theme'),
));
?>
    <form class="post-tg tougao-form" method="post">
        <div class="content-wrap">
            <div class="content-layout">
                <div class="panel card">
                    <div class="card-body">
                        <?php if(count($lists)>1){ ?>
                        <div class='d-inline-block slider_menu' sliderTab="sliderTab">
                            <ul class="nav nav-pills tab-auto-scrollbar menu" role="tablist">
                                <?php 
                                $i = 0;
                                foreach($lists as $k=>$v){
                                echo '<li class="pagenumber nav-item">
                                    <a class="nav-link '.( 0===$i?'active':'').'" data-toggle="pill" data-type="'.$k.'" href="#'.$k.'" onclick="currentType(this)">'.$v.'</a>
                                </li>';
                                $i++;
                                } 
                                ?>
                            </ul>
                        </div> 
                        <?php } ?>
                        <?php if($option['img_size']>0){ ?>
                        <div class="my-2">
                            <label for="tougao_ico"><?php _e('图标:','i_theme') ?></label>
                            <input type="hidden" value="" id="tougao_ico" class="tougao-sites" name="tougao_ico" />
                            <div class="upload_img">
                                <div class="show_ico">
                                    <img id="show_ico" class="show-sites" src="<?php echo get_theme_file_uri('/images/add.png') ?>" alt="<?php _e('图标','i_theme') ?>">
                                    <i id="remove_ico" class="iconfont icon-close-circle remove-ico remove-sites" data-id="" data-type="ico" style="display: none;"></i>
                                </div> 
                                <input type="file" id="upload_ico" class="upload-sites" name="tougao_ico" data-type="ico" accept="image/*" onchange="uploadImg(this)" >
                            </div>
                        </div>
                        <?php } ?>
                        <div class="row row-sm">
                            <div class="col-12 my-2"> 
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="iconfont icon-name icon-fw" aria-hidden="true"></i></span>
                                    </div>
                                    <input type="text" class="form-control sites-title" value="" name="post_title" placeholder="<?php _e('名称','i_theme') ?>" maxlength="30"/>
                                </div>
                            </div>
                            <?php if( in_array('wechat',array_keys($lists))){ 
                                $show_wx = false;
                                if( 1===count($lists) ){
                                    $show_wx = true;
                                }
                            ?>
                            <div class="col-12 my-2 tg-wechat-id" <?php echo($show_wx?'':'style="display:none"') ?>>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="iconfont icon-huabanfuben icon-fw" aria-hidden="true"></i></span>
                                    </div>
                                    <input type="text" class="form-control" value="" id="tougao_wechat_id" name="wechat_id" placeholder="<?php _e('公众号ID(微信号)','i_theme') ?>"/>
                                </div>
                            </div>
                            <?php } ?>
                            <div class="col-12 my-2">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="iconfont icon-url icon-fw" aria-hidden="true"></i></span>
                                    </div>
                                    <input type="text" class="form-control sites-link" value="" name="link" placeholder="<?php _e('链接','i_theme') ?>"/>
                                    <div class="input-group-append tg-sites-url">
                                        <a href="javascript:" id="get_info" class="btn btn-danger custom_btn-d"><?php _e('一键填写','i_theme') ?></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 my-2">
                                <div class="input-group <?php echo(wp_is_mobile()?'':'count-tips') ?>" data-min="0" data-max="80">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="iconfont icon-tishi icon-fw" aria-hidden="true"></i></span>
                                    </div>
                                    <input type="text" class="form-control sites-des" value="" name="sescribe" data-status="true" placeholder="<?php _e('简介','i_theme') ?>" maxlength="80"/>
                                </div>
                            </div>
                            <div class="col-12 my-2">
                                <textarea class="form-control text-sm" rows="16" cols="55" name="post_content" placeholder="<?php _e('输入网址介绍','i_theme') ?>"></textarea>
                            </div>
                        </div> 
                </div>
                </div>
            </div>
        </div>
        <div class="sidebar show-sidebar">
            <div class="card rounded-lg mb-3 relative">
                <div class="card-header widget-header">
                    <h3 class="text-md mb-0"><i class="iconfont icon-publish mr-2"></i><?php _e('网址选项','i_theme') ?></h3>
                </div>
                <div class="card-body">
                    <p class="text-muted text-sm mb-2">分类</p>
                    <div class="form-select">
                            <?php
                            $cat_args = array(
                                'show_option_all'     => __('选择分类','i_theme'),
                                'hide_empty'          => 0,
                                'id'                  => 'post_cat',
                                'taxonomy'            => 'favorites',
                                'name'                => 'category',
                                'class'               => 'form-control',
                                'show_count'          => 1,
                                'hierarchical'        => 1,
                            );
                            if(isset($option['cat'])){
                                $cat_args['include'] = $option['cat'];
                            }
                            wp_dropdown_categories($cat_args);
                            ?>
                    </div>
                    <p class="text-muted text-sm mb-2 mt-3"><?php _e('标签','i_theme') ?></p>
                    <textarea class="form-control sites-keywords" rows="3" name="tags" placeholder="<?php _e('输入标签','i_theme') ?>" tabindex="6"></textarea>
                    <p class="text-muted text-xs mt-2"><i class="iconfont icon-tishi"></i><?php _e('填写标签，每个标签用逗号隔开','i_theme') ?></p>
                </div>
            </div>
            <?php if (!$u_id) { ?>
            <div class="card rounded-lg mb-3 relative">
                <div class="card-header widget-header">
                    <h3 class="text-md mb-0"><i class="iconfont icon-user-circle mr-2"></i><?php _e('用户信息','i_theme') ?></h3>
                </div>
                <div class="card-body">
                    <p class="text-muted text-sm mb-2"><?php _e('昵称','i_theme') ?></p>
                    <div class="mb20">
                        <input class="form-control" name="user_name" placeholder="<?php _e('请输入昵称','i_theme') ?>">
                    </div>
                    <p class="text-muted text-sm mb-2 mt-3"><?php _e('联系方式','i_theme') ?></p>
                    <input class="form-control" name="contact_details" placeholder="<?php _e('输入联系方式','i_theme') ?>">
                </div>
            </div>
            <?php } ?>
            <input type="hidden" name="sites_type" value="sites"></input>
            <input type="hidden" name="action" value="io_post_submit"></input>
			<?php wp_nonce_field('tougao_robot'); ?>
            <div class="card rounded-lg relative">
                <div class="card-body">
                    <?php if( !io_get_option('io_captcha','','tcaptcha_007') ) {
                        wp_enqueue_script('captcha');  ?>
                        <div class="input-group text-sm mb-3">
                            <input id="input_veri" type="text" name="captcha" class="form-control input_veri" maxlength="4" canvas-id="tougao_captcha" placeholder="<?php _e('输入验证码','i_theme') ?>">
                            <div class="input-group-append position-relative match-code">
                                <span class="position-absolute h-100 d-flex align-items-center key-icon"></span>
                                <span class="verification-texts input-group-text text-sm py-0 pr-1 pl-3" data-toggle="tooltip" title="<?php _e('点击刷新','i_theme') ?>"><canvas id="tougao_captcha" canvas-code="tougao_captcha" width="92" height="33"></canvas></span>
                            </div>  
                        </div>
                    <?php } else { ?>
                        <input type="hidden" id="tcaptcha_007" name="tcaptcha_007" value="" />
                        <input type="hidden" id="tcaptcha_ticket" name="tencent_ticket" value="" />
                        <input type="hidden" id="tcaptcha_randstr" name="tencent_randstr" value="" />
                        <button id="TencentCaptcha" type="button" class="btn btn-outline-danger custom_btn-outline col-12 mb-3" data-appid="<?php echo io_get_option('io_captcha','','appid_007') ?>" data-cbfn="callback"><?php _e('验证','i_theme') ?></button>   
                    <?php } ?>
                    <button type="submit" class="btn btn-danger custom_btn-d text-sm col-12 custom-submit"><i class="iconfont icon-upload-wd mr-2"></i><?php echo $btn_text ?></button>
                </div>
            </div>
        </div>
    </form>
