<?php

add_action( 'wp_enqueue_scripts', 'enqueue_parent_theme_style' );
function enqueue_parent_theme_style() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
	if (is_rtl()) {
		 wp_enqueue_style( 'parent-rtl', get_template_directory_uri().'/rtl.css', array(), RH_MAIN_THEME_VERSION);
	}     
}

// Gravatar自定义替换
add_filter( 'get_avatar' , 'my_custom_avatar' , 1 ,5 );
function my_custom_avatar( $avatar, $id_or_email, $size, $default, $alt) {
   	if ( ! empty( $id_or_email->user_id ) ) {
        $avatar = "https://testingcf.jsdelivr.net/gh/jarlin8/OSS@main/Gavatar/circle-flags/216.svg";
    }else{
			$random = mt_rand(1,227);
			$avatar = 'https://testingcf.jsdelivr.net/gh/jarlin8/OSS@main/Gavatar/circle-flags/'.$random .'.svg';
         }
    $avatar = "<img alt='{$alt}' src='{$avatar}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";

    return $avatar;
}

//静态文件CDN加速
if ( !is_admin() ) {
	add_action('wp_loaded','yuncai_ob_start');
	
	function yuncai_ob_start() {
		ob_start('yuncai_qiniu_cdn_replace');
	}	

	function yuncai_qiniu_cdn_replace($html){
		$local_host = 'https://ka.laowei8.com'; //博客域名
		$qiniu_host = 'https://testingcf.jsdelivr.net/gh/jarlin8/img@master'; //CDN域名
		$cdn_exts   = 'css|js|woff2|woff|ttf|gif'; //扩展名（使用|分隔）
		$cdn_dirs   = 'wp-content|wp-includes'; //目录（使用|分隔）
		$cdn_dirs   = str_replace('-', '\-', $cdn_dirs);
		$local_img_host = 'https://ka.laowei8.com/wp-content/uploads/sites/3'; //图片前缀部分
		$qiniu_img_host = 'https://testingcf.jsdelivr.net/gh/jarlin8/OSS@main/ka'; //CDN回源域名
		$cdn_img_exts   = 'jpg|jpeg|jpe|png|gif|webp|bmp|tiff|svg'; //图片扩展名（使用|分隔）
		$cdn_img_dirs   = '2024|2025|plugins'; //目录（使用|分隔）
		$cdn_img_dirs   = str_replace('-', '\-', $cdn_img_dirs);	
		
		if ($cdn_dirs) {
			$regex	=  '/' . str_replace('/', '\/', $local_host) . '\/((' . $cdn_dirs . ')\/[^\s\?\\\'\"\;\>\<]{1,}.(' . $cdn_exts . '))([\"\\\'\s\?]{1})/';
			$html =  preg_replace($regex, $qiniu_host . '/$1$4', $html);
		} else {
			$regex	= '/' . str_replace('/', '\/', $local_host) . '\/([^\s\?\\\'\"\;\>\<]{1,}.(' . $cdn_exts . '))([\"\\\'\s\?]{1})/';
			$html =  preg_replace($regex, $qiniu_host . '/$1$3', $html);
		}

		if ($cdn_img_dirs) {
			$regex = '/' . str_replace('/', '\/', $local_img_host) . '\/((' . $cdn_img_dirs . ')\/[^\s\?\\\'\"\;\>\<]{1,}\.(jpg|jpeg|png|gif))/i';
            // 第一步：替换图片链接
            $html = preg_replace($regex, $qiniu_img_host . '/$1$4', $html);
            // 第二步：剔除文件名中的尺寸信息
            $html = preg_replace('/-\d+x\d+/', '', $html);
            // 第三步：替换除部分图片外的后缀为 .jpg
            $html = preg_replace('/(?<!bg_yellow|SIM)\.png/i', '.jpg', $html);
			// 替换插件目录下的图片
            $regex = '/https:\/\/ka\.laowei8\.com\/wp-content\/plugins\/(.*)\.(jpg|jpeg|png|gif)/i';
            $html = preg_replace($regex, 'https://testingcf.jsdelivr.net/gh/jarlin8/img@master/wp-content/plugins/$1.$2', $html);
		} else {
			$regex	= '/' . str_replace('/', '\/', $local_img_host) . '\/([^\s\?\\\'\"\;\>\<]{1,}.(' . $cdn_img_exts . '))([\"\\\'\s\?]{1})/';
			$html =  preg_replace($regex, $qiniu_img_host . '/$1$3', $html);
		}
		
		return $html;
	}
}

?>