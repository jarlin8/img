<?php
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2022-02-21 12:46:57
 * @LastEditors: iowen
 * @LastEditTime: 2023-02-09 23:09:52
 * @FilePath: \onenav\inc\functions\io-single-site.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

if(!function_exists('get_data_evaluation')):
/**
 * 数据评估HTML
 * @param mixed $name
 * @param mixed $views
 * @param mixed $url
 * @return void
 */
function get_data_evaluation($name,$views,$url){
    if(!io_get_option('sites_default_content',false)) return;
    global $post;
    $aizhan_data = go_to('https://www.aizhan.com/seo/'. format_url($url,true));
    $chinaz_data = go_to('https://seo.chinaz.com/?q='. format_url($url,true));
?>
    <h2 class="text-gray  text-lg my-4"><i class="iconfont icon-tubiaopeizhi mr-1"></i><?php _e('数据评估','i_theme') ?></h2>
    <div class="panel site-content sites-default-content card"> 
        <div class="card-body">
            <p class="viewport">
            <?php echo $name ?>浏览人数已经达到<?php echo $views ?>，如你需要查询该站的相关权重信息，可以点击"<a class="external" href="<?php echo $aizhan_data ?>" rel="nofollow" target="_blank">爱站数据</a>""<a class="external" href="<?php echo $chinaz_data ?>" rel="nofollow" target="_blank">Chinaz数据</a>"进入；以目前的网站数据参考，建议大家请以爱站数据为准，更多网站价值评估因素如：<?php echo $name ?>的访问速度、搜索引擎收录以及索引量、用户体验等；当然要评估一个站的价值，最主要还是需要根据您自身的需求以及需要，一些确切的数据则需要找<?php echo $name ?>的站长进行洽谈提供。如该站的IP、PV、跳出率等！</p>
            <div class="text-center my-2"><span class=" content-title"><span class="d-none">关于<?php echo $name ?></span>特别声明</span></div>
            <p class="text-muted text-sm m-0">
            本站<?php bloginfo('name'); ?>提供的<?php echo $name ?>都来源于网络，不保证外部链接的准确性和完整性，同时，对于该外部链接的指向，不由<?php bloginfo('name'); ?>实际控制，在<?php echo the_time(TIME_FORMAT) ?>收录时，该网页上的内容，都属于合规合法，后期网页的内容如出现违规，可以直接联系网站管理员进行删除，<?php bloginfo('name'); ?>不承担任何责任。</p>
        </div>
        <div class="card-footer text-muted text-xs">
            <div class="d-flex"><span><?php bloginfo('name'); ?>致力于优质、实用的网络站点资源收集与分享！</span><span class="ml-auto d-none d-md-block">本文地址<?php the_permalink() ?>转载请注明</span></div>
        </div>
    </div>
<?php
}
endif;

if(!function_exists('get_report_button')):
function get_report_button($post_id=''){
    if(!io_get_option('report_button',true))
        return;
    if($post_id==''){
        global $post;
        $post_id = get_the_ID();
    }                                            
    echo '<a href="javascript:" class="btn btn-danger qr-img tooltip-toggle rounded-lg" data-post_id="'.$post_id.'" data-toggle="modal" data-placement="top" data-target="#report-sites-modal" title="'. __('反馈','i_theme') .'"><i class="iconfont icon-statement icon-lg"></i></a>';
}
endif;

function get_report_reason(){
    $reasons = array(
        '1' => __('已失效','i_theme'),
        '2' => __('重定向&变更','i_theme'),//必须为 2
        '3' => __('已屏蔽','i_theme'),
        '4' => __('敏感内容','i_theme'),
        '0' => __('其他','i_theme'), //必须为 0
    );
    return apply_filters('io_sites_report_reason', $reasons);
}

function report_model_body(){
    global $post,$post_type; 
    if ($post_type != 'sites' || ($post_type == 'sites' && get_post_meta( get_the_ID(), '_sites_type', true )=='down')) return;
    ?>
    <div class="modal fade add_new_sites_modal" id="report-sites-modal" tabindex="-1" role="dialog" aria-labelledby="report-sites-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-md" id="report-sites-title"><?php _e('反馈','i_theme') ?></h5>
                    <button type="button" id="close-sites-modal" class="close io-close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="iconfont icon-close-circle text-xl"></i>
                    </button>
                </div>
                <div class="modal-body"> 
                    <div class="alert alert-info" role="alert">
                    <i class="iconfont icon-statement "></i> <?php _e('让我们一起共建文明社区！您的反馈至关重要！','i_theme') ?>
                    </div>
                    <form id="report-form" method="post"> 
                        <input type="hidden" name="post_id" value="<?php echo get_the_ID() ?>">
                        <input type="hidden" name="action" value="report_site_content">
                        <div class="form-row">
                            <?php
                            $option = get_report_reason();
                            if(get_post_meta(get_the_ID(), '_affirm_dead_url', true)){
                                $option = array('666' => __('已可访问','i_theme'));
                            }
                            foreach ($option as $key => $reason) {
                                echo '<div class="col-6 py-1">
                                <label><input type="radio" name="reason" class="reason-type-' . $key . '" value="' . $key . '" ' . (in_array($key,array(1,666)) ? 'checked' : '') . '> ' . $reason . '</label>
                            </div>';
                            }
                            ?>
                        </div>
                        <div class="form-group other-reason-input" style="display: none;">
                            <input type="text" class="form-control other-reason" value="" placeholder="<?php _e('其它信息，可选','i_theme') ?>">
                        </div>  
                        <div class="form-group redirect-url-input" style="display: none;">
                            <input type="text" class="form-control redirect-url" value="" placeholder="<?php _e('重定向&变更后的地址','i_theme') ?>">
                        </div> 
                        <div class=" text-center">
                            <button type="submit" class="btn btn-danger"><?php _e('提交反馈','i_theme') ?></button>
                        </div> 
                    </form>
                </div> 
            </div>
        </div>
        <script>
        $(function () {
            $('.tooltip-toggle').tooltip();
            $('input[type=radio][name=reason]').change(function() {
                var t = $(this); 
                var reason = $('.other-reason-input');
                var url = $('.redirect-url-input');
                reason.hide();
                url.hide();
                if(t.val()==='0'){
                    reason.show();
                }else if(t.val()==='2'){
                    url.show();
                }
            }); 
            $(document).on("submit",'#report-form', function(event){
                event.preventDefault(); 
                var t = $(this); 
                var reason = t.find('input[name="reason"]:checked').val();
                if(reason === "0"){
                    reason = t.find('.other-reason').val();
                    if(reason==""){
                        showAlert(JSON.parse('{"status":4,"msg":"<?php _e('信息不能为空！','i_theme') ?>"}'));
                        return false;
                    }
                }
                if(reason === "2"){
                    if(t.find('.redirect-url').val()==""){
                        showAlert(JSON.parse('{"status":4,"msg":"<?php _e('信息不能为空！','i_theme') ?>"}'));
                        return false;
                    }
                }
                $.ajax({
                    url: '<?php echo admin_url( 'admin-ajax.php' ) ?>',
                    type: 'POST', 
                    dataType: 'json',
                    data: {
                        action : t.find('input[name="action"]').val(),
                        post_id : t.find('input[name="post_id"]').val(),
                        reason : reason,
                        redirect : t.find('.redirect-url').val(),
                    },
                })
                .done(function(response) {   
                    if(response.status == 1){
                        $('#report-sites-modal').modal('hide');
                    } 
                    showAlert(response);
                })
                .fail(function() {  
                    showAlert(JSON.parse('{"status":4,"msg":"<?php _e('网络错误 --.','i_theme') ?>"}'));
                }); 
                return false;
            });
        });
        </script>
    </div>
    <?php
}
if(io_get_option('report_button',true))
add_action('wp_footer', 'report_model_body');