<?php
/*!
 * Theme Name:One Nav
 * Theme URI:https://www.iotheme.cn/
 * Author:iowen
 * Author URI:https://www.iowen.cn/
 */

 // https://s0.wp.com/mshots/v1/www.google.com?w=383&h=328  网址截图
 
if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php while( have_posts() ): the_post();?>
            <div class="row site-content py-4 py-md-5 mb-xl-5 mb-0 mx-xxl-n5">
                <?php get_template_part( 'templates/fx' ); ?>
                <!-- 网址信息 -->
                <div class="col-12 col-sm-5 col-md-4 col-lg-3">
                    <?php 
                    $m_link_url = get_post_meta(get_the_ID(), '_sites_link', true);  
                    $is_dead    = get_post_meta(get_the_ID(), '_affirm_dead_url', true);
                    $is_preview = false;
                    $sitetitle  = get_the_title();
                    $imgurl     = get_site_thumbnail($sitetitle, $m_link_url, $sites_type, true ,$is_preview);
                    $views      = function_exists('the_views')? the_views(false) :  '0' ;
                    ?>
                    <div class="siteico">
                        <?php if(!$is_preview){ ?>
                        <div class="blur blur-layer" style="background: transparent url(<?php echo $imgurl ?>) no-repeat center center;-webkit-background-size: cover;-moz-background-size: cover;-o-background-size: cover;background-size: cover;animation: rotate 30s linear infinite;"></div>
                        <?php 
                            if(io_get_option('is_letter_ico',false) && io_get_option('first_api_ico',false)){
                                echo get_lazy_img( $imgurl, $sitetitle, 'auto', 'img-cover', '', true,'onerror=null;src=ioLetterAvatar(alt,98)');
                            }else{
                                echo get_lazy_img( $imgurl, $sitetitle, 'auto', 'img-cover', '');
                            }
                        } 
                        if($is_preview){ 
                            echo get_lazy_img( $imgurl, $sitetitle, 'auto', 'img-cover');
                        }
                        if($country = get_post_meta(get_the_ID(),'_sites_country', true)) {
                            echo '<div id="country" class="text-xs custom-piece_c_b country-piece loadcountry"><i class="iconfont icon-globe mr-1"></i>'.$country.'</div>';
                        }else{
                            echo '<div id="country" class="text-xs custom-piece_c_b country-piece" style="display:none;"><i class="iconfont icon-loading icon-spin"></i></div>';
                        }
                        ?>
                        <div class="tool-actions text-center mt-md-4">
                            <?php like_button(get_the_ID()) ?>
                            <a href="javascript:;" class="btn-share-toggler btn btn-icon btn-light rounded-circle p-2 mx-3 mx-md-2" data-toggle="tooltip" data-placement="top" title="<?php _e('浏览','i_theme') ?>">
                                <span class="flex-column text-height-xs">
                                    <i class="icon-lg iconfont icon-chakan"></i>
                                    <small class="share-count text-xs mt-1"><?php echo $views ?></small>
                                </span>
                            </a> 
                        </div>
                        <?php if ($is_dead) { ?>
                            <div class="link-dead"><i class="iconfont icon-subtract mr-1"></i><?php _e('链接已失效','i_theme') ?></div>
                        <?php } ?>
                    </div>
                </div>
                <div class="col mt-4 mt-sm-0">
                    <div class="site-body text-sm">
                        <?php 
                        $terms = get_the_terms( get_the_ID(), 'favorites' ); 
                        if(isset($_GET['mininav-id'])){
                            echo '<a class="btn-cat custom_btn-d mr-1" href="' . esc_url( get_permalink(intval($_GET['mininav-id'])) ) . '">' . get_post( intval($_GET['mininav-id']) )->post_title . '</a>';
                            echo '<i class="iconfont icon-arrow-r-m custom-piece_c" style="font-size:50%;color:#f1404b;vertical-align:0.075rem"></i>';
                        }
                        if( !empty( $terms ) ){
                            foreach( $terms as $term ){
                                if($term->parent != 0){
                                    $parent_category = get_term( $term->parent );
                                    echo '<a class="btn-cat custom_btn-d mr-1" href="' . esc_url( get_category_link($parent_category->term_id)) . '">' . esc_html($parent_category->name) . '</a>';
                                    echo '<i class="iconfont icon-arrow-r-m custom-piece_c" style="font-size:50%;color:#f1404b;vertical-align:0.075rem"></i>';
                                    break;
                                }
                            } 
                            foreach( $terms as $term ){
                                $name = $term->name;
                                $link = esc_url( get_term_link( $term, 'favorites' ) );
                                echo "<a class='btn-cat custom_btn-d mr-1' href='$link'>".$name."</a>";
                            }
                        }  
                        ?>
                        <h1 class="site-name h3 my-3"><?php echo $sitetitle ?>
                        <?php $language = get_post_meta(get_the_ID(), '_sites_language', true); if($m_link_url!="" && $language && !find_character($language,['中文','汉语','zh','cn','简体']) ){ ?>
                            <a class="text-xs" href="//fanyi.baidu.com/transpage?query=<?php echo format_url($m_link_url,true) ?>&from=auto&to=zh&source=url&render=1" target="_blank" rel="nofollow noopener noreferrer"><?php _e('翻译站点','i_theme') ?><i class="iconfont icon-wailian text-ss"></i></a>
                        <?php } ?>
                        <?php edit_post_link('<i class="iconfont icon-modify mr-1"></i>'.__('编辑','i_theme'), '<span class="edit-link text-xs text-muted">', '</span>' ); ?>
                        </h1>
                        <div class="mt-2">
                            <?php 
                            $width = 150;
                            if(get_post_meta_img(get_the_ID(), '_wechat_qr', true) || $sites_type == 'wechat'){
                                $m_qrurl = get_post_meta_img(get_the_ID(), '_wechat_qr', true);
                                $qrurl = "<img src='".$m_qrurl."' width='{$width}'>";
                                if(get_post_meta(get_the_ID(),'_is_min_app', true) ){
                                    $qrname = __("小程序",'i_theme');
                                    if($m_qrurl == "")
                                        $qrurl = '<p>'.__('居然没有添加二维码','i_theme').'</p>';
                                }else{
                                    $qrname = __("公众号",'i_theme');
                                    if($m_qrurl == ""){
                                        if($wechat_id = get_post_meta_img(get_the_ID(), '_wechat_id', true)){
                                            $qrurl = "<img src='https://open.weixin.qq.com/qr/code?username=".$wechat_id."' width='{$width}'>";
                                        }else{
                                            $qrurl = '<p>'.__('居然没有添加二维码','i_theme').'</p>';
                                        }
                                    }
                                }
                            }else{
                                $m_post_link_url = $m_link_url ?: get_permalink(get_the_ID());
                                $qrurl = "<img src='".get_qr_url($m_post_link_url, $width)."' width='{$width}'>";
                                $qrname = __("手机查看",'i_theme');
                            }
                            ?>
                            <p class="mb-2"><?php echo io_get_excerpt(170,'_sites_sescribe') ?></p> 
                            <?php the_terms( get_the_ID(), 'sitetag',__('标签：','i_theme').'<span class="mr-1">', '<i class="iconfont icon-wailian text-ss"></i></span> <span class="mr-1">', '<i class="iconfont icon-wailian text-ss"></i></span>' ); ?>
                            <?php 
                            if($sites_type == "sites" && !$is_dead && io_get_option('url_rank',false)){
                                $aizhan = go_to('https://baidurank.aizhan.com/baidu/'.format_url($m_link_url,true),true);
                                echo '<div class="mt-2">'.__('爱站权重：','i_theme');
                                echo '<span class="mr-2">PC <a href="'. $aizhan .'" title="百度权重" target="_blank"><img class="" src="//baidurank.aizhan.com/api/br?domain='.format_url($m_link_url,true).'&style=images" alt="百度权重" title="百度权重" style="height:18px"></a></span>';
                                echo '<span class="mr-2">'.__('移动','i_theme') .' <a href="'. $aizhan .'" title="百度移动权重" target="_blank"><img class="" src="//baidurank.aizhan.com/api/mbr?domain='.format_url($m_link_url,true).'&style=images" alt="百度移动权重" title="百度移动权重" style="height:18px"></a></span>';
                                echo '</div>';
                            }
                            ?>
                            <div class="site-go mt-3">
                                <?php 
                                if ($m_link_url != "") {
                                    $a_class = '';
                                    $a_ico   = 'icon-arrow-r-m';
                                    if ($is_dead) {
                                        $m_link_url = esc_url(home_url());
                                        $a_class = ' disabled';
                                        $a_ico   = 'icon-subtract';
                                    }
                                ?>
                                    <div id="security_check_img"></div>
                                    <span class="site-go-url">
                                    <a href="<?php echo go_to($m_link_url) ?>" title="<?php echo $sitetitle ?>" target="_blank" class="btn btn-arrow mr-2<?php echo $a_class ?>"><span><?php _e('链接直达', 'i_theme') ?><i class="iconfont <?php echo $a_ico ?>"></i></span></a>
                                    </span>
                                <?php } ?>
                                <?php if(!$is_dead){ ?>
                                <a href="javascript:" class="btn btn-arrow qr-img"  data-toggle="tooltip" data-placement="bottom" data-html="true" title="<?php echo $qrurl ?>"><span><?php echo $qrname ?><i class="iconfont icon-qr-sweep"></i></span></a>
                                <?php } ?>
                                <?php get_report_button() ?>
                            </div>
                            <?php if(!$is_dead && $spare_link =get_post_meta(get_the_ID(),'_spare_sites_link', true)) { ?>
                                <div class="spare-site mb-3"> 
                                <i class="iconfont icon-url"></i><span class="mr-3"><?php _e('其他站点:','i_theme') ?></span>
                                <?php for ($i=0;$i<count($spare_link);$i++) { ?>
                                <a class="mb-2 mr-3" href="<?php echo go_to($spare_link[$i]['spare_url']) ?>" title="<?php echo $spare_link[$i]['spare_note'] ?>" target="_blank" style="white-space:nowrap"><span><?php echo $spare_link[$i]['spare_name'] ?><i class="iconfont icon-wailian"></i></span></a>
                                <?php } ?> 
                                </div>
                            <?php } ?>
                            <?php if($is_dead){ ?>
                            <p class="text-xs link-dead-msg"><i class="iconfont icon-warning mr-2"></i><?php _e('经过确认，此站已经关闭，故本站不再提供跳转，仅保留存档。','i_theme') ?></p> 
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <!-- 网址信息 end -->
                <?php get_sidebar('sitestop') ?> 
            </div>
<main class="content" role="main">
    <div class="content-wrap">
		<div class="content-layout">
            <div class="panel site-content card transparent"> 
                <div class="card-body p-0">
                    <div class="apd-bg">
                        <?php  show_ad('ad_app_content_top',false, '<div class="apd apd-right">' , '</div>');  ?>
                    </div> 
                    <div class="panel-body single my-4 ">
                            <?php  
                            $contentinfo = get_the_content();
                            if( $contentinfo ){
                                echo apply_filters('the_content', $contentinfo);
                                thePostPage();
                            }else{
                                echo htmlspecialchars(get_post_meta(get_the_ID(), '_sites_sescribe', true));
                            }
                            ?>
                    </div>

                        
                </div>
            </div>
            <?php if( io_get_option('leader_board',false) && io_get_option('details_chart',false)){ //图表统计?>
            <h2 class="text-gray text-lg my-4"><i class="iconfont icon-zouxiang mr-1"></i><?php _e('数据统计','i_theme') ?></h2>
            <div class="card io-chart"> 
                <div id="chart-container" class="" style="height:300px" data-type="<?php echo $sites_type ?>" data-post_id="<?php echo get_the_ID() ?>" data-nonce="<?php echo wp_create_nonce( 'post_ranking_data' ) ?>">
                    <div class="chart-placeholder p-4">
                        <div class="legend">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                        <div class="pillar">
                            <span style="height:40%"></span>
                            <span style="height:60%"></span>
                            <span style="height:30%"></span>
                            <span style="height:70%"></span>
                            <span style="height:80%"></span>
                            <span style="height:60%"></span>
                            <span style="height:90%"></span>
                            <span style="height:50%"></span>
                        </div>
                    </div>
                </div> 
            </div> 
            <?php } ?>
            <?php get_data_evaluation($sitetitle,$views,$m_link_url) ?>
<?php endwhile; ?>
