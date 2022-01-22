<?php

function optionsframework_option_name() {

	// This gets the theme name from the stylesheet
	$themename = wp_get_theme();
	$themename = preg_replace("/\W/", "_", strtolower($themename) );

	$optionsframework_settings = get_option( 'optionsframework' );
	$optionsframework_settings['id'] = $themename;
	update_option( 'optionsframework', $optionsframework_settings );
}

function optionsframework_options() {

	$blogpath =  get_stylesheet_directory_uri() . '/img';
	$bloghome =  home_url( '/' );
	$bloglogin =  home_url( '/' ).'wp-login.php';
	$qq_auth =  home_url( '/' ).'wp-content/themes/begin/inc/social/qq-auth.php';
	$weibo_auth =  home_url( '/' ).'wp-content/themes/begin/inc/social/sina-auth.php';
	$weixin_auth =  home_url( '/' );

	$options_categories = array();
	$options_categories_obj = get_categories(array('hide_empty' => 0));
	foreach ($options_categories_obj as $category) {
		$options_categories[$category->cat_ID] = $category->cat_name;
	}

	$options_tags = array();
	$options_tags_obj = get_tags();
	foreach ( $options_tags_obj as $tag ) {
		$options_tags[$tag->term_id] = $tag->name;
	}

	$options_pages = array();
	$options_pages_obj = get_pages( 'sort_column=post_parent,menu_order' );
	$options_pages[''] = '选择页面:';
	foreach ($options_pages_obj as $page) {
		$options_pages[$page->ID] = $page->post_title;
	}

	$test_array = array(
		'' => '中',
		't' => '上',
		'b' => '下',
		'l' => '左',
		'r' => '右'
	);

	$rand_link = array(
		'rating' => '正常',
		'rand' => '随机'
	);

	$inks_img_txt = array(
		'0' => '文字',
		'1' => '图片'
	);

	$fl789 = array(
		'7' => '七栏',
		'8' => '八栏',
		'9' => '九栏'
	);

	$fl2468 = array(
		'2' => '两栏',
		'4' => '四栏',
		'6' => '六栏',
		'8' => '八栏'
	);

	$fl1234 = array(
		'1' => '1栏',
		'2' => '2栏',
		'3' => '3栏',
		'4' => '4栏'
	);

	$fl345 = array(
		'3' => '3栏',
		'4' => '4栏',
		'5' => '5栏'
	);

	$fl456 = array(
		'4' => '4栏',
		'5' => '5栏',
		'6' => '6栏'
	);

	$fl56 = array(
		'5' => '5栏',
		'6' => '6栏'
	);

	$fsl45 = array(
		'4' => '4栏',
		'5' => '5栏'
	);

	$fl34 = array(
		'3' => '3栏',
		'4' => '4栏'
	);

	$swf12 = array(
		'1' => '1栏',
		'2' => '2栏'
	);

	$cover234 = array(
		'2' => '2栏',
		'3' => '3栏',
		'4' => '4栏',
		'5' => '5栏'
	);

	$options = array();

	$options[] = array(
		'desc' => '',
		'id' => 'show_box',
		'class' => 'show_box',
		'std' => '0',
		'type' => 'checkbox'
	);

	// 首页设置

	$options[] = array(
		'name' => '首页设置',
		'type' => 'heading'
	);

	$options[] = array(
		'name' => '首页布局选择',
		'type' => 'groupstart'
	);

	$options[] = array(
		'id' => 'layout',
		'class' => 'rr',
		'std' => 'blog',
		'type' => 'radio',
		'options' => array(
			'blog' => '博客布局',
			'img' => '图片布局',
			'grid' => '分类图片',
			'cms' => '杂志布局',
			'group' => '公司主页',
		)
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '首页幻灯',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'slider',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'desc' => '调用方法，编辑准备显示在幻灯中的文章，在编辑器下面“将文章添加到幻灯”面板中输入图片链接地址即可',
		'class' => 'icon_sm el fol '
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'slider_n',
		'std' => '2',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '间隔，默认4000毫秒',
		'id' => 'owl_time',
		'std' => '4000',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '进度条',
		'id' => 'slide_progress',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '自动裁剪图片',
		'id' => 'show_img_crop',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '调用视频',
		'id' => 'show_slider_video',
		'std' => '0',
		'class' => 'hb',
		'type' => 'checkbox'
	);

	$options[] = array(
		'desc' => '上传MP4视频或者输入视频地址',
		'id' => 'show_slider_video_url',
		'type' => 'upload'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '仅显示一张图片',
		'id' => 'slider_only_img',
		'class' => 'hb',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'desc' => '上传图片',
		'id' => 'show_slider_img',
		'type' => 'upload'
	);

	$options[] = array(
		'name' => '',
		'desc' => '图片链接到',
		'id' => 'show_slider_img_url',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '博客（图片）布局排除的分类文章',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '输入排除的分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'not_cat_n',
		'class' => 'tk',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '专题（博客与图片布局）',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'blog_special',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入专题页面ID，多个页面ID用英文半角逗号","隔开',
		'id' => 'blog_special_id',
		'std' => '',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '',
		'id' => 'special_f',
		'std' => '4',
		'class' => 'rr',
		'type' => 'radio',
		'options' => $fl456 
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '博客与图片布局分类链接',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'new_cat_id',
		'class' => 'tk',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '首页推荐文章',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'cms_top',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：1',
		'id' => 'cms_top_s',
		'class' => 'mini',
		'std' => '1',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇',
		'id' => 'cms_top_n',
		'class' => 'mini',
		'std' => '4',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '首页图片布局',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '使用瀑布流',
		'id' => 'grid_fall',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'desc' => ''
	);

	$options[] = array(
		'name' => '最新文章分栏',
		'desc' => '',
		'id' => 'img_top_f',
		'std' => '4',
		'class' => 'rr',
		'type' => 'radio',
		'options' => $fl456 
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => 'Tab组合分类',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'ajax_tabs',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '博客布局不显示',
		'id' => 'blog_ajax_tabs',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '博客布局位置调整，0=第1篇下面，1=第2篇下面...',
		'id' => 'blog_ajax_tabs_n',
		'class' => 'mini',
		'std' => '3',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'tab_b_n',
		'std' => '8',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'id' => 'tabs_mode',
		'class' => 'rr',
		'std' => 'tabs_list_mode',
		'type' => 'radio',
		'options' => array(
			'tabs_list_mode' => '列表',
			'tabs_img_mode' => '图片',
			'tabs_default_mode' => '标准',
		)
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '分类A设置',
		'desc' => '自定义文字',
		'id' => 'tab_b_a',
		'std' => '推荐文章',
		'type' => 'text'
	);

	if ( $options_categories ) {
	$options[] = array(
		'name' => '',
		'desc' => '选择一个分类',
		'id' => 'tab_b_a_id',
		'type' => 'select',
		'options' => $options_categories);
	}

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '分类B设置',
		'desc' => '自定义文字',
		'id' => 'tab_b_b',
		'std' => '专题文章',
		'type' => 'text'
	);

	if ( $options_categories ) {
	$options[] = array(
		'name' => '',
		'desc' => '选择一个分类',
		'id' => 'tab_b_b_id',
		'type' => 'select',
		'options' => $options_categories);
	}

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '分类C设置',
		'desc' => '自定义文字',
		'id' => 'tab_b_c',
		'std' => '分类文章',
		'type' => 'text'
	);

	if ( $options_categories ) {
	$options[] = array(
		'name' => '',
		'desc' => '选择一个分类',
		'id' => 'tab_b_c_id',
		'type' => 'select',
		'options' => $options_categories);
	}

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '分类D设置',
		'desc' => '自定义文字（留空则不显示）',
		'id' => 'tab_b_d',
		'std' => '分类文章',
		'type' => 'text'
	);

	if ( $options_categories ) {
	$options[] = array(
		'name' => '',
		'desc' => '选择一个分类',
		'id' => 'tab_b_d_id',
		'type' => 'select',
		'options' => $options_categories);
	}

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '全部分类链接',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'cat_all',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入排除的分类ID，比如：1,2',
		'id' => 'cat_all_e',
		'std' => '',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '首页分类封面',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'h_cat_cover',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '显示在正文页面顶部',
		'id' => 'single_cover',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '杂志布局排序：2',
		'id' => 'cms_cover_s',
		'class' => 'mini',
		'std' => '2',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '输入分类ID',
		'desc' => '多个ID用英文半角逗号","隔开',
		'id' => 'cat_cover_id',
		'class' => 'tk',
		'std' => '1,2',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '分栏',
		'desc' => '',
		'id' => 'cover_img_f',
		'std' => '4',
		'class' => 'rr',
		'type' => 'radio',
		'options' => $cover234 
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '模式选择',
		'id' => 'cat_rec_m',
		'class' => 'rr',
		'std' => 'cat_rec_ico',
		'type' => 'radio',
		'options' => array(
			'cat_rec_ico' => '图标',
			'cat_rec_img' => '图片'
		)
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'desc' => '调用标签',
		'id' => 'cat_tag_cover',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '输入标签ID',
		'desc' => '多个ID用英文半角逗号","隔开',
		'id' => 'cat_cover_tag_id',
		'class' => 'tk',
		'std' => '1,2',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '图片布局分栏',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '',
		'id' => 'img_f',
		'class' => 'rr',
		'std' => '4',
		'type' => 'radio',
		'options' => $fl456 
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '首页多条件筛选',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'filter_general',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '图片布局显示摘要',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'hide_box',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '首页新窗口或标签打开链接',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '启用',
		'id' => 'blank',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '移动端首页显示的页面',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '输入链接地址，不使用请留空',
		'id' => 'mobile_home_url',
		'class' => 'tk',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '首页页脚链接',
		'class' => 'bel',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'footer_link',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '可以输入链接分类ID，显示特定的链接在首页，留空则显示全部链接',
		'id' => 'link_f_cat',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '显示网站Favicon图标',
		'id' => 'home_link_ico',
		'class' => 'chl',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '图片链接',
		'id' => 'footer_img',
		'class' => 'chl',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '移动端不显示',
		'id' => 'footer_link_no',
		'class' => 'chl',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'class' => '',
	);

	$options[] = array(
		'name' => '更多链接按钮',
		'desc' => '选择友情链接页面',
		'id' => 'link_url',
		'type' => 'select',
		'options' => $options_pages
	);

	$options[] = array(
		'type' => 'groupend'
	);

	// 基本设置

	$options[] = array(
		'name' => '基本设置',
		'type' => 'heading'
	);

	$options[] = array(
		'name' => '文章列表截断字数',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '自动截断字数，默认值：100',
		'id' => 'words_n',
		'std' => '100',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '摘要截断字数，默认值：90',
		'id' => 'word_n',
		'std' => '90',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '图片延迟加载',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '缩略图延迟加载',
		'id' => 'lazy_s',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '正文图片延迟加载',
		'id' => 'lazy_e',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '公告',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示，并代替首页面包屑导航',
		'id' => 'bulletin',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入一个公告分类ID，调用指定的分类',
		'id' => 'bulletin_id',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '公告滚动篇数',
		'id' => 'bulletin_n',
		'std' => '2',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'class' => '',
	);

	$options[] = array(
		'name' => '公告分类模板选择',
		'id' => 'notice_m',
		'class' => 'rr',
		'std' => 'notice_s',
		'type' => 'radio',
		'options' => array(
			'notice_d' => '默认',
			'notice_s' => '说说',
		)
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '弹窗公告',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'placard_layer',
		'std' => '0',
		'type' => 'checkbox'
	);

	if ( $options_categories ) {
	$options[] = array(
		'name' => '',
		'desc' => '选择一个分类',
		'id' => 'placard_cat_id',
		'type' => 'select',
		'options' => $options_categories);
	}

	$options[] = array(
		'desc' => '输入文章ID，留空则显示5篇分类文章',
		'id' => 'placard_id',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '分钟，默认30分钟弹出一次',
		'id' => 'placard_time',
		'std' => '30',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '显示最新文章图片',
		'id' => 'placard_img',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '自定义内容',
		'id' => 'custom_placard',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '标题',
		'id' => 'custom_placard_title',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '链接',
		'id' => 'custom_placard_url',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '图片',
		'id' => 'custom_placard_img',
		"std" => "https://s2.loli.net/2021/12/05/qH1SNgls95RZGJP.jpg",
		'type' => 'upload'
	);

	$options[] = array(
		'name' => '内容',
		'id' => 'custom_placard_content',
		'class' => 't70',
		'std' => '',
		'type' => 'textarea'
	);

	$options[] = array(
		'desc' => '排除管理员',
		'id' => 'admin_placard',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '阅读全文按钮',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '阅读全文按钮文字，留空则不显示',
		'id' => 'more_w',
		'std' => '阅读全文',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '直达链接按钮文字，留空则不显示',
		'id' => 'direct_w',
		'std' => '直达链接',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '按钮默认隐藏',
		'id' => 'more_hide',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => 'Ajax加载文章',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '启用',
		'id' => 'infinite_post',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '加载页数',
		'id' => 'pages_n',
		'std' => '3',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '页号显示',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '首页页号数',
		'id' => 'first_mid_size',
		'std' => '2',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '其它页号数',
		'id' => 'mid_size',
		'std' => '4',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入页号跳转',
		'id' => 'input_number ',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '移动端简化分页',
		'id' => 'turn_small',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '替换用户默认链接',
		'type' => 'groupstart'
	);

	$options[] = array(
		'id' => 'my_author',
		'class' => 'rr',
		'std' => 'author_id',
		'type' => 'radio',
		'options' => array(
			'author_id' => '用户ID',
			'author_link' => '用户名称',
		)
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '用段落标题生成文章索引目录',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '启用',
		'id' => 'be_toc',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'id' => 'toc_style',
		'class' => 'rr',
		'std' => 'tocjq',
		'type' => 'radio',
		'options' => array(
			'tocjq' => '单级显示',
			'tocphp' => '层级显示',
		)
	);

	$options[] = array(
		'name' => '',
		'desc' => '几个标题时生成目录',
		'id' => 'toc_title_n',
		'std' => '4',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'id' => 'toc_mode',
		'class' => 'rr',
		'std' => 'toc_four',
		'type' => 'radio',
		'options' => array(
			'toc_four' => '仅四级标题',
			'toc_all' => '二至六级标题',
		)
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '正文相关设置',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '历史上的今天',
		'id' => 'begin_today',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '图片Lightbox查看',
		'id' => 'lightbox_on',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '可视化编辑器',
		'id' => 'visual_editor',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '段首空格',
		'id' => 'p_first',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '正文继续阅读按钮',
		'id' => 'all_more',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '编辑器增加中文字体',
		'id' => 'custum_font',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '在线视频支持',
		'id' => 'smart_ideo',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '复制提示',
		'id' => 'copy_tips',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '禁止复制及右键，注：管理员登录无效',
		'id' => 'copyright_pro',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '禁止复制CSS版',
		'id' => 'no_copy',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '段落末尾版权链接',
		'id' => 'copy_upset',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '禁止打印',
		'id' => 'print_no',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '文章分页显示全部按钮',
		'id' => 'link_pages_all',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '文章外链接添加nofollow',
		'id' => 'link_external',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '文章内链接新窗口打开，需与上面的选项同时使用',
		'id' => 'link_internal',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '正文无侧边栏',
		'id' => 'single_no_sidebar',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '正文底部Tab组合分类',
		'id' => 'single_tab',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '代码高亮',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '自动代码高亮显示',
		'id' => 'be_code',
		'class' => 'chl',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'desc' => '手动代码高亮显示',
		'id' => 'highlight',
		'class' => 'chl',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '优化相关',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '不显示分类链接中的"category"，更改后需保存一次固定链接设置',
		'id' => 'no_category',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '分类归档链接添加"/"斜杠，更改后需保存一次固定链接设置',
		'id' => 'category_x',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '页面添加.html后缀，更改后需保存一下固定链接设置',
		'id' => 'page_html',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '自动将文章标题作为图片ALT标签内容',
		'id' => 'image_alt',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '上传附件自动按时间重命名',
		'id' => 'be_upload_name',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '显示用户登录注册时间',
		'id' => 'last_login',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '文章批量操作',
		'id' => 'bulk_actions_post',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => 'Ajax移动文章到回收站',
		'id' => 'ajax_move_post',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '自定义字段筛选',
		'id' => 'meta_key_filter',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '显示文章ID',
		'id' => 'post_ssid',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'desc' => '禁用xmlrpc',
		'id' => 'xmlrpc_no',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '禁用文章修订',
		'id' => 'revisions_no',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '禁用oEmbed',
		'id' => 'embed_no',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '禁用 REST API，连接小程序需取消',
		'id' => 'disable_api',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '阻止恶意URL请求',
		'id' => 'be_safety',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '密码链接修正',
		'id' => 'forget_password',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '移除后台菜单分隔符',
		'id' => 'remove_separator',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '侧边栏小工具',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '侧边栏跟随滚动',
		'id' => 'sidebar_sticky',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '小工具条件判断',
		'id' => 'widget_logic',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '小工具CSS类',
		'id' => 'widget_class',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '小工具克隆',
		'id' => 'clone_widgets',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '使用Ajax短代码小工具',
		'id' => 'ajax_text_widget',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '头部小工具',
		'id' => 'header_widget',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'id' => 'header_widget_f',
		'std' => '4',
		'class' => 'rr',
		'type' => 'radio',
		'options' => $fl34 
	);

	$options[] = array(
		'name' => '',
		'id' => 'h_widget_m',
		'class' => 'rr',
		'std' => 'cat_single_m',
		'type' => 'radio',
		'options' => array(
			'cat_single_m' => '在分类及正文页面显示',
			'cat_m' => '仅在分类页面显示',
			'all_m' => '全局显示',
		)
	);

	$options[] = array(
		'name' => '',
		'desc' => '移动端不显示',
		'id' => 'h_widget_p',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '正文底部小工具',
		'id' => 'single_e',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '文章小工具段落插入位置',
		'id' => 'widget_p',
		'std' => '3',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '页脚小工具',
		'id' => 'footer_w',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '移动端不显示页脚小工具',
		'id' => 'mobile_footer_w',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '自定义文章显示数',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '一般用于使用图片布局的分类/标签，自定义文章显示数',
		'class' => 'el'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'cat_posts_id',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '显示数',
		'id' => 'posts_n',
		'class' => 'mini',
		'std' => '20',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '分类相关',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示分类推荐文章',
		'id' => 'cat_top',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '分类归档不显示子分类文章',
		'id' => 'no_child',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '分类排序',
		'id' => 'cat_order',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '分类图标',
		'id' => 'cat_icon',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '启用分类封面',
		'id' => 'cat_cover',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '归档页子分类链接',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'child_cat',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '',
		'id' => 'child_cat_f',
		'class' => 'rr',
		'std' => '8',
		'type' => 'radio',
		'options' => $fl789
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入排除的分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'child_cat_no',
		'class' => 'tk',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '分类图片',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示（分类填写描述才能显示）',
		'id' => 'cat_des',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '显示描述',
		'id' => 'cat_des_p',
		'class' => 'chl',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '单独显示描述',
		'id' => 'cat_area',
		'class' => 'chl',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '自动裁剪图片',
		'id' => 'cat_des_img',
		'class' => 'chl',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '标题居左',
		'id' => 'des_title_l',
		'class' => 'chl',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'class' => ''
	);

	$options[] = array(
		'name' => '默认图片',
		'desc' => '上传默认图片',
		'id' => 'cat_des_img_d',
		"std" => "https://s2.loli.net/2021/12/05/qH1SNgls95RZGJP.jpg",
		'type' => 'upload'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '正文上下篇文章链接',
		'type' => 'groupstart'
	);

	$options[] = array(
		'id' => 'post_nav_mode',
		'class' => 'rr',
		'std' => 'full_site',
		'type' => 'radio',
		'options' => array(
			'full_site' => '全站',
			'same_cat' => '同分类',
		)
	);

	$options[] = array(
		'name' => '',
		'desc' => '缩略图',
		'id' => 'post_nav_img',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '不显示',
		'id' => 'post_nav_no',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '正文底部滚动同分类文章',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'single_rolling',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'single_rolling_n',
		'std' => '10',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '正文相关文章',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'related_img',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'class' => '',
	);

	$options[] = array(
		'name' => '显示模式',
		'id' => 'related_mode',
		'class' => 'rr',
		'std' => 'slider_grid',
		'type' => 'radio',
		'options' => array(
			'related_normal' => '标准',
			'slider_grid' => '图片',
		)
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'related_n',
		'std' => '4',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '正文底部商品',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'single_tao',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'single_tao_n',
		'std' => '4',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '图片/视频/商品/产品/网址文章归档',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '篇数 ，自定义文章显示数',
		'id' => 'type_posts_n',
		'std' => '20',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '显示该类型所有分类链接',
		'id' => 'type_cat',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '图片/视频/商品/Woo产品分类页面模板',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '篇数',
		'id' => 'custom_cat_n',
		'std' => '12',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '最新文章图标',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'news_ico',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '默认一周（168小时）内发表的文章显示，最短24小时',
		'id' => 'new_n',
		'std' => '168',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '友情链接页面',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '自助友情链接',
		'id' => 'add_link',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '显示模式',
		'id' => 'links_model',
		'std' => 'links_ico',
		'class' => 'rr',
		'type' => 'radio',
		'options' => array(
			'links_ico' => '图标模式',
			'links_default' => '默认模式',
		)
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '图标模式选择',
		'desc' => '',
		'id' => 'link_favicon',
		'class' => 'rr',
		'std' => 'favicon_ico',
		'type' => 'radio',
		'options' => array(
			'favicon_ico' => 'Favicon图标',
			'first_ico' => '首字图标',
		)
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '图标模式排序',
		'desc' => '',
		'id' => 'rand_link',
		'class' => 'rr',
		'std' => 'rating',
		'type' => 'radio',
		'options' => $rand_link
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '默认模式选择',
		'desc' => '',
		'id' => 'links_img_txt',
		'class' => 'rr',
		'std' => '0',
		'type' => 'radio',
		'options' => $inks_img_txt
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '排除的链接',
		'desc' => '输入排除的链接ID，多个ID用","隔开',
		'id' => 'link_cat',
		'class' => 'tk',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '链接Favicon图标API',
		'class' => 'bel',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '输入获取链接favicon图标API地址',
		'id' => 'favicon_api',
		'class' => 'tk',
		'std' => 'https://favicon.cccyun.cc/',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	// 站点管理
	$options[] = array(
		'name' => '站点管理',
		'type' => 'heading'
	);

	$options[] = array(
		'name' => '管理站点',
		'desc' => '启用前端登录',
		'id' => 'login',
		'class' => '',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '隐藏顶部菜单及站点管理',
		'id' => 'top_nav_no',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '顶部菜单登录按钮 ',
		'id' => 'profile',
		'class' => '',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '主菜单登录按钮',
		'id' => 'menu_login',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '菜单注册按钮',
		'id' => 'menu_reg',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '移动端登录按钮',
		'id' => 'mobile_login',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '前端显示找回密码',
		'id' => 'reset_pass',
		'class' => '',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '登录验证码',
		'id' => 'login_captcha',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '注册验证码',
		'id' => 'register_captcha',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '找回密码验证码',
		'id' => 'lost_captcha',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '注册直接输入密码',
		'id' => 'go_reg',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '注册后自动登录',
		'id' => 'register_auto',
		'std' => '0',
		'type' => 'checkbox'
	);


	$options[] = array(
		'name' => '',
		'desc' => '非管理员和编辑禁止进后台',
		'id' => 'no_admin',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '只允许社会化登录',
		'id' => 'only_social_login',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '注册登录页面模板只显示注册',
		'id' => 'reg_sign',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '自定义登录按钮链接',
		'id' => 'user_l',
		'class' => 'tk',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '退出登录后跳转的页面',
		'id' => 'logout_to',
		'class' => 'tk',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '注册按钮链接',
		'id' => 'reg_l',
		'class' => 'tk',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '顶部欢迎语',
		'id' => 'wel_come',
		'class' => '',
		'std' => '欢迎光临！',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '用户中心',
		'desc' => '选择用户中心页面',
		'id' => 'user_url',
		'type' => 'select',
		'class' => '',
		'options' => $options_pages
	);

	$options[] = array(
		'name' => '用户投稿',
		'desc' => '选择投稿页面',
		'id' => 'tou_url',
		'type' => 'select',
		'class' => '',
		'options' => $options_pages
	);

	$options[] = array(
		'name' => '用户中心背景图片',
		'desc' => '上传背景图片',
		'id' => 'personal_img',
        "std" => "https://s2.loli.net/2021/12/05/qH1SNgls95RZGJP.jpg",
		'type' => 'upload'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '重定向默认登录链接，适合不想让别人进入默认登录注册页面，又不影响重置密码',
		'id' => 'redirect_login',
		'class' => '',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '重定向网址',
		'id' => 'redirect_login_link',
		'class' => 'tk',
		'std' => '链接地址',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '修改默认登录链接，适合不想让别人知道默认登录注册页面链接，但会影响重置密码',
		'id' => 'login_link',
		'class' => '',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'id' => 'login_link_h'
	);

	$options[] = array(
		'desc' => '前缀',
		'id' => 'pass_h',
		'class' => 'tw3',
		'std' => 'my',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '后缀',
		'id' => 'word_q',
		'class' => 'tw3',
		'std' => 'the',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '跳转网址',
		'id' => 'go_link',
		'std' => '链接地址',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '要记住修改后的链接，默认登录地址：http://域名/wp-login.php?my=the',
		'id' => 'login_s',
		'class' => 'el'
	);

	// 菜单设置
	$options[] = array(
		'name' => '菜单设置',
		'type' => 'heading'
	);

	$options[] = array(
		'name' => '导航菜单固定模式',
		'id' => 'menu_m',
		'class' => 'rr',
		'std' => 'menu_d',
		'type' => 'radio',
		'options' => array(
			'menu_d' => '正常模式',
			'menu_n' => '永不固定',
			'menu_g' => '保持固定',
		)
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '主要菜单样式',
		'id' => 'main_nav',
		'class' => ''
	);

	$options[] = array(
		'desc' => '色块模式',
		'id' => 'menu_block',
		'class' => 'chl',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'desc' => '文字加粗',
		'id' => 'nav_ace',
		'class' => 'chl',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'desc' => '半透明',
		'id' => 'menu_glass',
		'class' => 'chl',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'desc' => '居左',
		'id' => 'site_nav_left',
		'class' => 'chl',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '顶部菜单更多按钮',
		'id' => 'top_nav_more',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '菜单项显示数，实际显示数+1',
		'id' => 'top_nav_n',
		'std' => '8',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '主菜单更多按钮',
		'id' => 'nav_more',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '菜单项显示数，实际显示数+1',
		'id' => 'nav_n',
		'std' => '8',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '通用头部模式',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '启用',
		'id' => 'header_normal',
		'class' => '',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '通长导航',
		'id' => 'menu_full',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'id' => 'h_main_o',
		'class' => 'rr',
		'std' => 'h_search',
		'type' => 'radio',
		'options' => array(
			'h_search' => '搜索框',
			'h_contact' => '自定义内容',
		)
	);

	$options[] = array(
		'name' => '头部自定义内容',
		'desc' => '',
		'id' => 'header_contact',
		'class' => 't70',
		'std' => '<div class="contact-main contact-l"><i class="be be-phone"></i>13088888888</div><div class="contact-main contact-r"><i class="be be-display"></i>联系我们</div>',
		'type' => 'textarea'
	);


	$options[] = array(
		'name' => '',
		'desc' => '背景色',
		'id' => 'header_color',
		'std' => '#ffffff',
		'type' => 'color'
	);

	$options[] = array(
		'name' => '',
		'desc' => '',
		'id' => '',
		'class' => ''
	);

	$options[] = array(
		'name' => '背景图片',
		'desc' => '背景图片',
		'id' => 'top_bg',
		"std" => "",
		'type' => 'upload'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '移动端菜单',
		'desc' => '移动端菜单与PC端不同',
		'id' => 'mobile_nav',
		'class' => '',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '单独的移动端菜单（不能有二级菜单，有特殊需要时启用）',
		'id' => 'm_nav',
		'class' => '',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '移动端页脚菜单',
		'id' => 'footer_menu',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '移动端页脚菜单自动隐藏',
		'id' => 'footer_menu_no',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '移动端页脚菜单微信',
		'id' => 'nav_weixin_on',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '上传微信二维码图片（＜240px）',
		'id' => 'nav_weixin_img',
		'std' => "$blogpath/favicon.png",
		'type' => 'upload'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '菜单条件判断',
		'id' => 'menu_visibility',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '二级菜单显示描述',
		'id' => 'menu_des',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '移动端导航按钮链接到页面',
		'id' => 'nav_no',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '选择页面',
		'id' => 'nav_url',
		'type' => 'select',
		'class' => 'mini',
		'options' => $options_pages
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '菜单图文',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'menu_post',
		'class' => '',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'desc' => '说明：添加一个自定义链接菜单项，URL输入#号，CSS类输入：mynav',
		'id' => 'menu_post_my',
		'class' => 'el',
		'std' => ''
	);

	$options[] = array(
		'name' => '',
		'id' => 'menu_cat_cover_md',
		'class' => 'rr',
		'std' => 'menu_post_id',
		'type' => 'radio',
		'options' => array(
			'menu_post_id' => '指定文章',
			'menu_cover_id' => '分类封面',
			'menu_all_cat' => '全部分类',
		)
	);

	$options[] = array(
		'name' => '指定文章',
		'desc' => '编辑文章，在“将文章添加到”面板中，勾选“菜单图文”调用指定文章',
		'id' => 'menu_post_sm',
		'class' => 'el',
		'std' => ''
	);

	$options[] = array(
		'name' => '分类封面',
		'desc' => '输入添加封面的分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'menu_cat_cover_id',
		'std' => '1,2,3,4',
		'class' => 'tk',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => '排除的分类',
		'desc' => '输入排除的分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'menu_cat_e_id',
		'std' => '',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '菜单分类',
		'class' => 'bel',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'nav_cat',
		'class' => '',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'desc' => '说明：添加一个自定义链接菜单项，URL输入#号，CSS类输入：mycat',
		'id' => 'nav_cat_my',
		'class' => 'el',
		'std' => ''
	);

	$options[] = array(
		'name' => '',
		'id' => 'nav_cat_md',
		'class' => 'rr',
		'std' => 'nav_cat',
		'type' => 'radio',
		'options' => array(
			'nav_cat' => '全部分类',
			'nav_cover' => '分类封面',
		)
	);

	$options[] = array(
		'name' => '排除的分类',
		'desc' => '输入排除的分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'nav_cat_e_id',
		'std' => '',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	// 缩略图
	$options[] = array(
		'name' => '缩略图',
		'type' => 'heading'
	);

	$options[] = array(
		'name' => '缩略图方式',
		'id' => 'img_way',
		'class' => 'rr',
		'std' => 'no_thumb',
		'type' => 'radio',
		'options' => array(
			'd_img' => '默认缩略图',
			'o_img' => '阿里云OSS',
			'q_img' => '七牛云',
			'upyun' => '又拍云',
			'cos_img' => '腾讯COS',
			'no_thumb' => '不裁剪',
		)
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '缩略图不裁剪显示比例',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '不使用自定义请留空！',
		'id' => 'img_bl_t',
		'class' => 'bl el'
	);

	$options[] = array(
		'name' => '',
		'desc' => '标准缩略图 默认75',
		'id' => 'img_bl',
		'class' => 'bl',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '杂志分类模块缩略图 默认41',
		'id' => 'img_k_bl',
		'class' => 'bl',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '图片布局缩略图 默认75',
		'id' => 'grid_bl',
		'class' => 'bl',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '视频缩略图 默认75',
		'id' => 'img_v_bl',
		'class' => 'bl',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '商品缩略图 默认100',
		'id' => 'img_t_bl',
		'class' => 'bl',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '幻灯小工具 默认75',
		'id' => 'img_s_bl',
		'class' => 'bl',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '横向滚动 默认75',
		'id' => 'img_l_bl',
		'class' => 'bl',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '分类宽图 默认33.3',
		'id' => 'img_full_bl',
		'class' => 'bl',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '网址缩略图 默认75',
		'id' => 'sites_bl',
		'class' => 'bl',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '缩略图自动裁剪设置',
		'id' => 'c_img',
		'class' => ''
	);

	$options[] = array(
		'desc' => '缩略裁剪位置',
		'id' => 'img_crop',
	);

	$options[] = array(
		'name' => '',
		'desc' => '',
		'id' => 'crop_top',
		'std' => '',
		'type' => 'radio',
		'options' => $test_array
	);

	$options[] = array(
		'desc' => '标准缩略图',
		'id' => 'img_c',
	);

	$options[] = array(
		'desc' => '宽 默认 280',
		'id' => 'img_w',
		'class' => 'mh',
		'std' => '280',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '高 默认 210',
		'id' => 'img_h',
		'class' => 'mh',
		'std' => '210',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '杂志分类模块缩略图',
		'id' => 'img_c',
	);

	$options[] = array(
		'desc' => '宽 默认 560',
		'id' => 'img_k_w',
		'class' => 'mh',
		'std' => '560',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '高 默认 230',
		'id' => 'img_k_h',
		'class' => 'mh',
		'std' => '230',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '图片布局缩略图',
		'id' => 'img_c',
	);

	$options[] = array(
		'desc' => '宽 默认 280',
		'id' => 'grid_w',
		'class' => 'mh',
		'std' => '280',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '高 默认 210',
		'id' => 'grid_h',
		'class' => 'mh',
		'std' => '210',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '图片缩略图',
		'id' => 'img_c',
	);

	$options[] = array(
		'desc' => '宽 默认 280',
		'id' => 'img_i_w',
		'class' => 'mh',
		'std' => '280',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '高 默认 210',
		'id' => 'img_i_h',
		'class' => 'mh',
		'std' => '210',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '视频缩略图',
		'id' => 'img_c',
	);

	$options[] = array(
		'desc' => '宽 默认 280',
		'id' => 'img_v_w',
		'class' => 'mh',
		'std' => '280',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '高 默认 210',
		'id' => 'img_v_h',
		'class' => 'mh',
		'std' => '210',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '商品缩略图',
		'id' => 'img_c',
	);

	$options[] = array(
		'desc' => '宽 默认 400',
		'id' => 'img_t_w',
		'class' => 'mh',
		'std' => '400',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '高 默认 400',
		'id' => 'img_t_h',
		'class' => 'mh',
		'std' => '400',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '首页幻灯',
		'id' => 'img_c',
	);

	$options[] = array(
		'desc' => '宽 默认 800',
		'id' => 'img_h_w',
		'class' => 'mh',
		'std' => '800',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '高 默认 300',
		'id' => 'img_h_h',
		'class' => 'mh',
		'std' => '300',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '幻灯小工具',
		'id' => 'img_c',
	);

	$options[] = array(
		'desc' => '宽 默认 350',
		'id' => 'img_s_w',
		'class' => 'mh',
		'std' => '350',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '高 默认 260',
		'id' => 'img_s_h',
		'class' => 'mh',
		'std' => '260',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '分类宽图',
		'id' => 'img_c',
	);

	$options[] = array(
		'desc' => '宽 默认 900',
		'id' => 'img_full_w',
		'class' => 'mh',
		'std' => '900',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '高 默认 350',
		'id' => 'img_full_h',
		'class' => 'mh',
		'std' => '350',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '网址缩略图',
		'id' => 'img_c',
	);

	$options[] = array(
		'desc' => '宽 默认 280',
		'id' => 'sites_w',
		'class' => 'mh',
		'std' => '280',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '高 默认 210',
		'id' => 'sites_h',
		'class' => 'mh',
		'std' => '210',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '分类图片',
		'id' => 'img_c',
	);

	$options[] = array(
		'desc' => '宽 默认 1200',
		'id' => 'img_des_w',
		'class' => 'mh',
		'std' => '1200',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '高 默认 250',
		'id' => 'img_des_h',
		'class' => 'mh',
		'std' => '250',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '瀑布流',
		'desc' => '宽度，默认190，当调整了页面宽度或者调整分栏，修改这个值，直至两侧对齐',
		'id' => 'fall_width',
		'class' => '',
		'std' => '190',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '限制文章列表缩略图',
		'desc' => '缩略图最大宽度，默认值：200',
		'id' => 'thumbnail_width',
		'class' => '',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '调整信息位置，默认距左：240',
		'id' => 'meta_left',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '随机缩略图',
		'desc' => '默认 5 张图片',
		'id' => 'rand_img_n',
		'std' => '5',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '自动裁剪',
		'id' => 'clipping_rand_img',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '文章中无图，不显示随机缩略图',
		'id' => 'no_rand_img',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '标准随机缩略图链接，多张图片中间用英文半角逗号","隔开',
		'desc' => '',
		'id' => 'random_image_url',
		'class' => 't70',
		'std' => 'https://s2.loli.net/2021/12/05/xj715tdFgs9ykTw.jpg,https://s2.loli.net/2021/12/05/4ItBJy28PfDLrSn.jpg,https://s2.loli.net/2021/12/05/Xe3IHN2BT1oGtFp.jpg,https://s2.loli.net/2021/12/05/IDkLpfcJrAGUCZV.jpg,https://s2.loli.net/2021/12/05/2BOx8H6R9JjYX4i.jpg',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => '分类模块随机缩略图链接，多张图片中间用英文半角逗号","隔开',
		'desc' => '',
		'id' => 'random_long_url',
		'class' => 't70',
		'std' => 'https://s2.loli.net/2021/12/05/Fda6ThrZHWuIiCM.jpg,https://s2.loli.net/2021/12/05/yhF4eC8SBGW3Oit.jpg,https://s2.loli.net/2021/12/05/I5nOrG3hKq1d6WV.jpg,https://s2.loli.net/2021/12/05/cYKnida45PrzyWl.jpg,https://s2.loli.net/2021/12/05/R6Ay2o9ZfYKwQvH.jpg',
		'type' => 'textarea'
	);


	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '特色图片',
		'desc' => '启用特色图片，如不使用该功能请不要开启',
		'id' => 'wp_thumbnails',
		'class' => '',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '特色图片自动裁剪',
		'id' => 'clipping_thumbnails',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '外链图片自动本地化（酌情开启）',
		'id' => 'save_image',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '禁止WP自动裁剪图片',
		'id' => 'disable_img_sizes',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '手动缩略图自动裁剪',
		'id' => 'manual_thumbnail',
		'std' => '0',
		'type' => 'checkbox'
	);

	// 分类模板
	$options[] = array(
		'name' => '分类模板',
		'type' => 'heading'
	);

	$options[] = array(
		'name' => '选择不同分类（标签）布局',
		'desc' => '图片布局（输入分类ID，多个ID用","隔开，以下相同）',
		'id' => 'cat_layout_img',
		'class' => '',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '图片布局，有侧边栏',
		'id' => 'cat_layout_img_s',
		'class' => '',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '图片布局（可单独设置缩略图大小）',
		'id' => 'cat_layout_grid',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '图片布局，有播放图标',
		'id' => 'cat_layout_play',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '通长缩略图',
		'id' => 'cat_layout_full',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '标题列表',
		'id' => 'cat_layout_list',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '格子标题',
		'id' => 'cat_layout_title',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '网格布局',
		'id' => 'cat_layout_square',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '时间轴',
		'id' => 'cat_layout_line',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '问答模式',
		'id' => 'cat_layout_qa',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '瀑布流',
		'id' => 'cat_layout_fall',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '子分类封面',
		'id' => 'cat_child_cover',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '子分类',
		'id' => 'cat_layout_child',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '子分类图片',
		'id' => 'cat_layout_child_img',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '正文模板选择',
		'desc' => '问答简化（输入分类ID，多个ID用","隔开）',
		'id' => 'single_layout_qa',
		'class' => '',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '图片分类归档使用瀑布流',
		'id' => 'gallery_fall',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '瀑布流显示文章信息',
		'id' => 'fall_inf',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '子分类封面图标模式',
		'id' => 'child_cover_ico',
		'std' => '1',
		'type' => 'checkbox'
	);

	// 文章信息
	$options[] = array(
		'name' => '文章信息',
		'type' => 'heading'
	);

	$options[] = array(
		'name' => '文章信息设置',
		'id' => 'post_meta_inf'
	);

	$options[] = array(
		'desc' => '文章信息显示在标题下面',
		'id' => 'meta_b',
		'class' => '',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '正文标题居中',
		'id' => 'title_c',
		'class' => '',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '不居中时文章信息两行',
		'id' => 'inf_back',
		'class' => '',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '正文显示作者信息',
		'id' => 'meta_author_single',
		'class' => '',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '文章列表显示作者信息',
		'id' => 'meta_author',
		'class' => '',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '文章列表只显示作者链接',
		'id' => 'meta_author_link',
		'class' => '',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '网格模块不显示作者信息',
		'id' => 'author_hide',
		'class' => '',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '阅读模式',
		'id' => 'reading_m',
		'class' => '',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '打印按钮',
		'id' => 'print_on',
		'class' => '',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '文章字数',
		'id' => 'word_count',
		'class' => '',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '阅读时间',
		'id' => 'reading_time',
		'class' => '',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '移动端隐藏字数和阅读时间',
		'id' => 'word_time',
		'class' => '',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '使用标准日期格式',
		'id' => 'meta_time',
		'class' => '',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '标准日期文章列表不显示年',
		'id' => 'no_year',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '显示时间',
		'id' => 'meta_time_second',
		'class' => '',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '显示文章分类',
		'id' => 'post_cat',
		'class' => '',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '点赞数',
		'id' => 'meta_zm_like',
		'class' => '',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '最后更新日期',
		'id' => 'post_replace',
		'class' => '',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '显示文章标签',
		'id' => 'post_tags',
		'class' => '',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '显示百度收录与否',
		'id' => 'baidu_record',
		'class' => '',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '显示文章末尾固定信息',
		'id' => 'copyright_info',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入信息，可使用HTML代码',
		'id' => 'copyright_content',
		'std' => '文章末尾固定信息',
		'type' => 'textarea'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '隐藏缩略图上分类名称',
		'id' => 'no_thumbnail_cat',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '文章列表显示标签',
		'id' => 'post_tag_cloud',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '限制数量',
		'id' => 'post_tag_cloud_n',
		'std' => '2',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '显示正文底部版权信息',
		'id' => 'copyright',
		'class' => '',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '显示作者头像',
		'id' => 'copyright_avatar',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '自定义版权信息第一行，可使用HTML代码',
		'id' => 'copyright_statement',
		'class' => 't70',
		'std' => '',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => '',
		'desc' => '自定义版权信息第二行，可使用HTML代码',
		'id' => 'copyright_indicate',
		'class' => 't70',
		'std' => '<strong>转载请务必保留本文链接：</strong>{{link}}',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => '{{title}}表示文章标题，{{link}}表示文章链接，比如获取文章标题和链接：<a href="{{link}}">{{title}}</a>',
		'desc' => '',
		'id' => 'copyright_case'
	);

	// 评论设置
	$options[] = array(
		'name' => '评论设置',
		'type' => 'heading'
	);

	$options[] = array(
		'name' => '评论相关设置',
		'desc' => '',
		'id' => 'comment_related'
	);

	$options[] = array(
		'desc' => 'Ajax评论',
		'id' => 'comment_ajax',
		'class' => '',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '评论Ajax翻页',
		'id' => 'infinite_comment',
		'class' => '',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '评论@回复',
		'id' => 'at',
		'class' => '',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => 'QQ快速评论',
		'id' => 'qq_info',
		'class' => '',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '回复邮件通知',
		'id' => 'mail_notify',
		'class' => '',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '解锁提交评论',
		'id' => 'qt',
		'class' => '',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '评论只填写昵称',
		'id' => 'no_email',
		'class' => '',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '隐藏评论网址表单',
		'id' => 'no_comment_url',
		'class' => '',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '默认隐藏评论表单',
		'id' => 'not_comment_form',
		'class' => '',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '评论检查中文',
		'id' => 'refused_spam',
		'class' => '',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '评论等级',
		'id' => 'vip',
		'class' => '',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '评论楼层',
		'id' => 'comment_floor',
		'class' => '',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '评论贴图',
		'id' => 'embed_img',
		'class' => '',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '评论表情',
		'id' => 'emoji_show',
		'class' => '',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '禁止评论HTML',
		'id' => 'comment_html',
		'class' => '',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '删除评论按钮',
		'id' => 'del_comment',
		'class' => '',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '禁止评论超链接',
		'id' => 'comment_url',
		'class' => '',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '评论信息',
		'id' => 'comment_counts',
		'class' => '',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '关闭评论',
		'id' => 'close_comments',
		'class' => '',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '登录显示评论模块',
		'id' => 'login_comment',
		'class' => '',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '禁止冒充管理员留言',
		'id' => 'check_admin',
		'class' => '',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '管理员名称',
		'id' => 'admin_name',
		'class' => '',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '管理员邮箱',
		'id' => 'admin_email',
		'class' => '',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '评论提示文字',
		'desc' => '留空不显示',
		'id' => 'comment_hint',
		'class' => 'tk',
		'std' => '赠人玫瑰，手留余香...',
		'type' => 'text'
	);

	// CMS设置
	$options[] = array(
		'name' => '杂志布局',
		'type' => 'heading'
	);

	$options[] = array(
		'name' => '幻灯显示模式',
		'type' => 'groupstart'
	);

	$options[] = array(
		'id' => 'slider_l',
		'class' => 'rr',
		'std' => 'slider_n',
		'type' => 'radio',
		'options' => array(
			'slider_n' => '标准',
			'slider_w' => '通栏',
		)
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '专题',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'cms_special',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：2',
		'id' => 'cms_special_s',
		'class' => 'mini',
		'std' => '2',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入专题页面ID',
		'id' => 'cms_special_id',
		'std' => '',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '最新文章',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'news',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：2',
		'id' => 'news_s',
		'class' => 'mini',
		'std' => '2',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'id' => 'news_model',
		'std' => 'news_grid',
		'class' => 'rr',
		'type' => 'radio',
		'options' => array(
			'news_grid' => '网格模式',
			'news_normal' => '标准模式',
		)
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'news_n',
		'std' => '4',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入排除的分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'not_news_n',
		'std' => '',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '图文模块',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示（位于最新文章模块中）',
		'id' => 'post_img',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'post_img_n',
		'std' => '4',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '多条件筛选',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'cms_filter_h',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：3',
		'id' => 'cms_filter_s',
		'class' => 'mini',
		'std' => '3',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '首字母分类/标签',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'letter_show',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：3',
		'id' => 'letter_show_s',
		'class' => 'mini',
		'std' => '3',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '标题文字',
		'id' => 'letter_t',
		'std' => '全部分类',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'id' => 'letter_show_md',
		'class' => 'rr',
		'std' => 'letter_cat',
		'type' => 'radio',
		'options' => array(
			'letter_cat' => '分类',
			'letter_tag' => '标签',
		)
	);

	$options[] = array(
		'name' => '',
		'desc' => '默认展开',
		'id' => 'letter_hidden',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '杂志单栏小工具',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'cms_widget_one',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：3',
		'id' => 'cms_widget_one_s',
		'class' => 'mini',
		'std' => '3',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '杂志菜单小工具',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'cms_two_menu',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：3',
		'id' => 'cms_two_menu_s',
		'class' => 'mini',
		'std' => '3',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => 'AJAX分类',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'cms_cat_tab',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：3',
		'id' => 'cms_cat_tab_s',
		'class' => 'mini',
		'std' => '3',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'cms_cat_tab_n',
		'std' => '10',
		'class' => 'mini',
		'type' => 'text'
	);

	if ( $options_categories ) {
	$options[] = array(
		'name' => '',
		'desc' => '选择第一个分类',
		'id' => 'cms_cat_tab_one_id',
		'type' => 'select',
		'options' => $options_categories);
	}

	$options[] = array(
		'name' => '',
		'desc' => '输入其余分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'cms_cat_tab_id',
		'class' => 'tk',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '缩略图',
		'id' => 'cms_cat_tab_img',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '图片模块',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'picture_box',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：4',
		'id' => 'picture_s',
		'std' => '4',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'picture_n',
		'std' => '4',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '正常文章分类',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开，留空则不显示',
		'id' => 'img_id',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '调用图片分类',
		'desc' => '输入图片日志分类ID，多个分类用英文半角逗号","隔开，留空则不显示',
		'id' => 'picture_id',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '杂志两栏小工具',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'cms_widget_two',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：5',
		'id' => 'cms_widget_two_s',
		'std' => '5',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '单栏分类列表(5篇文章)',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'cat_one_5',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：6',
		'id' => 'cat_one_5_s',
		'std' => '6',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'cat_one_5_id',
		'std' => '1',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '单栏分类列表(无缩略图)',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'cat_one_on_img',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：6',
		'id' => 'cat_one_on_img_s',
		'std' => '6',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'cat_one_on_img_n',
		'std' => '4',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'cat_one_on_img_id',
		'std' => '1',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '单栏分类列表(10篇文章)',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'cat_one_10',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：7',
		'id' => 'cat_one_10_s',
		'std' => '7',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'cat_one_10_id',
		'std' => '1',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '视频模块',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'video_box',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：8',
		'id' => 'video_s',
		'std' => '8',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'video_n',
		'std' => '4',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '调用视频日志',
		'id' => 'video',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '视频日志分类',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'video_id',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '调用分类文章',
		'id' => 'video_post',
		'std' => '0',
		'type' => 'checkbox'
	);

	if ( $options_categories ) {
	$options[] = array(
		'name' => '',
		'desc' => '选择一个分类',
		'id' => 'video_post_id',
		'type' => 'select',
		'options' => $options_categories);
	}

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '混排分类列表',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'cat_lead',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：9',
		'id' => 'cat_lead_s',
		'std' => '9',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'cat_lead_n',
		'std' => '4',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '显示小图',
		'id' => 'no_lead_img',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'cat_lead_id',
		'std' => '1,2',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '两栏分类列表',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'cat_small',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：9',
		'id' => 'cat_small_s',
		'std' => '9',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'cat_small_n',
		'std' => '4',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '不显示第一篇摘要',
		'id' => 'cat_small_z',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'cat_small_id',
		'std' => '1,2',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '杂志Tab组合分类',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '排序：10',
		'id' => 'tab_h_s',
		'std' => '10',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '杂志侧边栏',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'cms_no_s',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '杂志侧边栏跟随滚动',
		'id' => 'cms_slider_sticky',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '产品模块',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'products_on',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：11',
		'id' => 'products_on_s',
		'std' => '11',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '产品显示个数',
		'id' => 'products_n',
		'std' => '4',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入产品分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'products_id',
		'std' => '',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '特色模块',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'grid_ico_cms',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：12',
		'id' => 'grid_ico_cms_s',
		'std' => '12',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '图标无背景色',
		'id' => 'cms_ico_b',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '',
		'id' => 'grid_ico_cms_n',
		'class' => 'rr',
		'std' => '6',
		'type' => 'radio',
		'options' => $fl2468
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '工具模块',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'cms_tool',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：12',
		'id' => 'cms_tool_s',
		'std' => '12',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '杂志三栏小工具',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'cms_widget_three',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：12',
		'id' => 'cat_widget_three_s',
		'std' => '12',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '',
		'id' => 'cms_widget_three_fl',
		'std' => '3',
		'class' => 'rr',
		'type' => 'radio',
		'options' => $fl1234
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '分类图片',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'cat_square',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：12',
		'id' => 'cat_square_s',
		'std' => '12',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'cat_square_n',
		'std' => '6',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'cat_square_id',
		'std' => '2',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '分类网格',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'cat_grid',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：13',
		'id' => 'cat_grid_s',
		'std' => '13',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'cat_grid_n',
		'std' => '6',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'cat_grid_id',
		'std' => '2',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '图片滚动模块',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'flexisel',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：14',
		'id' => 'flexisel_s',
		'std' => '14',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'flexisel_n',
		'std' => '8',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'class' => '',
	);

	$options[] = array(
		'name' => '调用方式',
		'id' => 'flexisel_m',
		'class' => 'rr',
		'std' => 'flexisel_cat',
		'type' => 'radio',
		'options' => array(
			'flexisel_cat' => '文章分类',
			'flexisel_img' => '图片分类',
			'flexisel_key' => '指定文章',
		)
	);

	if ( $options_categories ) {
	$options[] = array(
		'name' => '文章分类',
		'desc' => '选择一个分类',
		'id' => 'flexisel_cat_id',
		'type' => 'select',
		'options' => $options_categories);
	}

	$options[] = array(
		'name' => '图片分类',
		'desc' => '输入图片分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'gallery_id',
		'std' => '',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '指定文章',
		'desc' => '通过为文章添加自定义栏目，调用指定文章',
		'id' => 'key_n',
		'std' => 'views',
		'type' => 'text'
	);

	$options[] = array(
		'class' => '',
	);

	$options[] = array(
		'name' => '分栏',
		'desc' => '',
		'id' => 'flexisel_f',
		'std' => '5',
		'class' => 'rr',
		'type' => 'radio',
		'options' => $fl56 
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '底部分类列表',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'cat_big',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：15',
		'id' => 'cat_big_s',
		'std' => '15',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'cat_big_n',
		'std' => '4',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '三栏',
		'id' => 'cat_big_three',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'cat_big_id',
		'std' => '1,2',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '不显示第一篇摘要',
		'id' => 'cat_big_z',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '商品',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'tao_h',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：16',
		'id' => 'tao_h_s',
		'std' => '16',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'tao_h_n',
		'std' => '4',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '排序',
		'id' => 'h_tao_sort',
		'class' => 'rr',
		'std' => 'time',
		'type' => 'radio',
		'options' => array(
			'time' => '发表时间',
			'views' => '浏览量',
		)
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'tao_h_id',
		'std' => '',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	if ( function_exists( 'is_shop' ) ) {
		$options[] = array(
			'name' => 'WOO产品',
			'type' => 'groupstart'
		);

		$options[] = array(
			'desc' => '显示，需要安装商城插件 WooCommerce 并发表产品',
			'id' => 'product_h',
			'std' => '0',
			'class' => '',
			'type' => 'checkbox'
		);

		$options[] = array(
			'name' => '',
			'desc' => '排序：17',
			'id' => 'product_h_s',
			'std' => '17',
			'class' => 'mini',
			'type' => 'text'
		);

		$options[] = array(
			'name' => '',
			'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
			'id' => 'product_h_id',
			'class' => 'tk',
			'std' => '',
			'type' => 'text'
		);

		$options[] = array(
			'name' => '',
			'desc' => '产品商品显示数量',
			'id' => 'product_h_n',
			'class' => 'mini',
			'std' => '4',
			'type' => 'text'
		);

		$options[] = array(
			'name' => '分栏',
			'desc' => '',
			'id' => 'cms_woo_f',
			'std' => '4',
			'class' => 'rr',
			'type' => 'radio',
			'options' => $fl456 
		);

		$options[] = array(
			'type' => 'groupend'
		);
	}

	$options[] = array(
		'name' => '底部无缩略图分类列表',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'cat_big_not',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：19',
		'id' => 'cat_big_not_s',
		'std' => '19',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'cat_big_not_n',
		'std' => '4',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '三栏',
		'id' => 'cat_big_not_three',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'cat_big_not_id',
		'std' => '1,2',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '文章列表日期',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'list_date',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '分类列表是否显示子分类文章',
		'class' => 'bel',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '不显示子分类文章',
		'id' => 'no_cat_child',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

// 公司主页

	$options[] = array(
		'name' => '公司主页',
		'type' => 'heading'
	);

	$options[] = array(
		'name' => '幻灯',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'group_slider',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'desc' => '调用方法， 页面 → 新建页面，在编辑器下面的“用于公司主页幻灯”面板中输入图片地址，发表即可',
		'class' => 'icon_sm el fol '
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'group_slider_n',
		'std' => '3',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => 'px 高度，默认500',
		'id' => 'big_back_img_h',
		'std' => '500',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => 'px 用于移动端显示全图，留空默认240',
		'id' => 'big_back_img_m_h',
		'std' => '',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '链接到目标',
		'id' => 'group_slider_url',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '显示文字',
		'id' => 'group_slider_t',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '模糊大背景图片',
		'id' => 'group_blur',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '菜单浮在幻灯上',
		'id' => 'group_nav',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '调用视频',
		'id' => 'group_slider_video',
		'std' => '0',
		'class' => 'hb',
		'type' => 'checkbox'
	);

	$options[] = array(
		'desc' => '上传MP4视频或者输入视频地址',
		'id' => 'group_slider_video_url',
		'type' => 'upload'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '仅显示一张图片',
		'id' => 'group_only_img',
		'class' => 'hb',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'desc' => '上传图片',
		'id' => 'group_slider_img',
		'type' => 'upload'
	);

	$options[] = array(
		'name' => '',
		'desc' => '图片链接到',
		'id' => 'group_slider_img_url',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '关于我们',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'group_contact',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：1',
		'id' => 'group_contact_s',
		'std' => '1',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '自定义标题文字',
		'id' => 'group_contact_t',
		'std' => '关于我们',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '选择页面',
		'id' => 'contact_p',
		'type' => 'select',
		'class' => 'mini',
		'options' => $options_pages
	);

	$options[] = array(
		'name' => '',
		'desc' => '"详细查看"图标',
		'id' => 'group_more_ico',
		'std' => 'be be-stack',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '"详细查看"按钮文字，留空则不显示',
		'id' => 'group_more_z',
		'std' => '详细查看',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '自定义"详细查看"链接地址',
		'id' => 'group_more_url',
		'placeholder' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '"联系方式"图标',
		'id' => 'group_contact_ico',
		'std' => 'be be-phone',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '"联系方式"按钮文字，留空则不显示',
		'id' => 'group_contact_z',
		'std' => '联系方式',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入"联系方式"链接地址',
		'id' => 'group_contact_url',
		'placeholder' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '移动端截断文字',
		'id' => 'tr_contact',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '公司主页公告',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'group_bulletin',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：1',
		'id' => 'group_bulletin_s',
		'std' => '1',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '公告分类ID',
		'id' => 'group_bulletin_id',
		'class' => 'mini',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '公告滚动篇数',
		'id' => 'group_bulletin_n',
		'std' => '2',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '服务项目',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'dean',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：2',
		'id' => 'dean_s',
		'std' => '2',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '自定义标题文字',
		'id' => 'dean_t',
		'std' => '服务项目',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '文字说明',
		'id' => 'dean_des',
		'std' => '服务项目模块',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '调用内容方法',
		'id' => 'dean_d',
		'class' => 'el',
		'desc' => '编辑页面或者文章，在下面"仅用于公司主页服务模块"面板中输入相关内容',
	);

	$options[] = array(
		'class' => '',
	);

	$options[] = array(
		'name' => '分栏',
		'desc' => '',
		'id' => 'deanm_f',
		'class' => 'rr',
		'std' => '4',
		'type' => 'radio',
		'options' => $fl345
	);

	$options[] = array(
		'name' => '',
		'desc' => '移动端强制1栏',
		'id' => 'deanm_fm',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '工具',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'group_tool',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：2',
		'id' => 'tool_s',
		'std' => '2',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '自定义标题文字',
		'id' => 'tool_t',
		'std' => '工具',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '文字说明',
		'id' => 'tool_des',
		'std' => '实用工具',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'class' => '',
	);

	$options[] = array(
		'name' => '分栏',
		'desc' => '',
		'id' => 'stool_f',
		'class' => 'rr',
		'std' => '4',
		'type' => 'radio',
		'options' => $fl345
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '产品模块',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'group_products',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：3',
		'id' => 'group_products_s',
		'std' => '3',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'group_products_n',
		'std' => '4',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '自定义标题文字',
		'id' => 'group_products_t',
		'std' => '主要产品',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '文字说明',
		'id' => 'group_products_des',
		'std' => '产品日志模块',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '产品分类',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'group_products_id',
		'std' => '',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入更多按钮链接地址，留空则不显示',
		'id' => 'group_products_url',
		'placeholder' => '',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'class' => '',
	);

	$options[] = array(
		'name' => '分栏',
		'desc' => '',
		'id' => 'group_products_f',
		'std' => '4',
		'class' => 'rr',
		'type' => 'radio',
		'options' => $fl456 
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '服务宗旨',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'service',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：4',
		'id' => 'service_s',
		'std' => '4',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '文字说明',
		'id' => 'service_des',
		'std' => '服务宗旨模块',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '标题',
		'desc' => '自定义标题文字',
		'id' => 'service_t',
		'std' => '服务宗旨',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入左侧模块文章或页面ID，多个文章用英文半角逗号","隔开',
		'id' => 'service_l_id',
		'std' => '1,2',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入右侧模块文章或页面ID，多个文章用英文半角逗号","隔开',
		'id' => 'service_r_id',
		'std' => '1,2',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入中间模块文章或页面ID',
		'id' => 'service_c_id',
		'std' => '1',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入中间模块图片地址',
		'id' => 'service_c_img',
		'std' => 'https://s2.loli.net/2021/12/05/qH1SNgls95RZGJP.jpg',
		'type' => 'upload'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '背景图片',
		'desc' => '上传背景图片',
		'id' => 'service_bg_img',
		 "std" => "https://s2.loli.net/2021/12/05/Eropm1CV78ZblMn.jpg",
		'type' => 'upload'
	);

		$options[] = array(
			'type' => 'groupend'
		);

	if (function_exists( 'is_shop' )) {
		$options[] = array(
			'name' => 'WOO产品',
			'type' => 'groupstart'
		);

		$options[] = array(
			'desc' => '显示，需要安装商城插件 WooCommerce 并发表产品',
			'id' => 'g_product',
			'class' => '',
			'std' => '0',
			'type' => 'checkbox'
		);

		$options[] = array(
			'name' => '',
			'desc' => '排序：5',
			'id' => 'g_product_s',
			'std' => '5',
			'class' => 'mini',
			'type' => 'text'
		);

		$options[] = array(
			'name' => '',
			'desc' => '自定义标题文字',
			'id' => 'g_product_t',
			'std' => 'WOO产品',
			'type' => 'text'
		);

		$options[] = array(
			'name' => '',
			'desc' => '文字说明',
			'id' => 'g_product_des',
			'std' => 'WOO产品模块',
			'class' => 'tk',
			'type' => 'text'
		);

		$options[] = array(
			'name' => '产品分类',
			'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
			'id' => 'g_product_id',
			'std' => '',
			'type' => 'text'
		);

		$options[] = array(
			'name' => '',
			'desc' => '产品显示数量',
			'id' => 'g_product_n',
			'std' => '4',
			'class' => 'mini',
			'type' => 'text'
		);

		$options[] = array(
			'name' => '分栏',
			'desc' => '',
			'id' => 'group_woo_f',
			'std' => '4',
			'class' => 'rr',
			'type' => 'radio',
			'options' => $fl456 
		);

		$options[] = array(
			'type' => 'groupend'
		);
	}

	$options[] = array(
		'name' => '特色',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'group_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：6',
		'id' => 'group_ico_s',
		'std' => '6',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '自定义标题文字',
		'id' => 'group_ico_t',
		'std' => '特色',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '文字说明',
		'id' => 'group_ico_des',
		'std' => '特色模块',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'class' => '',
	);

	$options[] = array(
		'name' => '分栏',
		'desc' => '',
		'id' => 'grid_ico_group_n',
		'class' => 'rr',
		'std' => '6',
		'type' => 'radio',
		'options' => $fl2468
	);

	$options[] = array(
		'name' => '',
		'desc' => '图标无背景色',
		'id' => 'group_ico_b',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '描述',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'group_post',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：6',
		'id' => 'group_post_s',
		'std' => '6',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入文章或页面ID，多个分类用英文半角逗号","隔开',
		'id' => 'group_post_id',
		'std' => '1,2',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '简介',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'group_features',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：6',
		'id' => 'group_features_s',
		'std' => '6',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '自定义标题文字',
		'id' => 'features_t',
		'std' => '本站简介',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '文字说明',
		'id' => 'features_des',
		'std' => '公司简介模块',
		'class' => 'tk',
		'type' => 'text'
	);

	if ( $options_categories ) {
	$options[] = array(
		'name' => '',
		'desc' => '选择一个分类',
		'id' => 'features_id',
		'type' => 'select',
		'options' => $options_categories);
	}

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'features_n',
		'std' => '4',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入更多按钮链接地址，留空则不显示',
		'id' => 'features_url',
		'placeholder' => '',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'class' => '',
	);

	$options[] = array(
		'name' => '分栏',
		'desc' => '',
		'id' => 'group_features_f',
		'std' => '4',
		'class' => 'rr',
		'type' => 'radio',
		'options' => $fl456 
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '展示',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'group_img',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：6',
		'id' => 'group_img_s',
		'std' => '6',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'group_img_n',
		'std' => '4',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'group_img_id',
		'class' => 'tk',
		'type' => 'select',
		'type' => 'text'
	);

	$options[] = array(
		'class' => '',
	);

	$options[] = array(
		'name' => '分栏',
		'desc' => '',
		'id' => 'group_img_f',
		'std' => '4',
		'class' => 'rr',
		'type' => 'radio',
		'options' => $fl456 
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '分类左右图',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'group_wd',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：7',
		'id' => 'group_wd_s',
		'std' => '7',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'group_wd_id_n',
		'std' => '6',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'group_wd_id',
		'std' => '2',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '说明',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'group_explain',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：9',
		'id' => 'group_explain_s',
		'std' => '9',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '选择简介页面',
		'id' => 'explain_p',
		'type' => 'select',
		'class' => 'mini',
		'options' => $options_pages
	);

	$options[] = array(
		'name' => '',
		'desc' => '自定义标题文字',
		'id' => 'group_explain_t',
		'std' => '公司说明',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '一栏小工具',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'group_widget_one',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：10',
		'id' => 'group_widget_one_s',
		'std' => '10',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '最新文章',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'group_new',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：11',
		'id' => 'group_new_s',
		'std' => '11',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '标题',
		'desc' => '自定义标题文字',
		'id' => 'group_new_t',
		'std' => '最新文章',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '文字说明',
		'id' => 'group_new_des',
		'std' => '这里是本站最新发表的文章',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '标题模式',
		'id' => 'group_new_list',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'group_new_n',
		'std' => '10',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入排除的分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'not_group_new',
		'std' => '',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '商品模块',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'g_tao_h',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：11',
		'id' => 'g_tao_s',
		'std' => '11',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'g_tao_h_n',
		'std' => '4',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '排序',
		'id' => 'g_tao_sort',
		'class' => 'rr',
		'std' => 'time',
		'type' => 'radio',
		'options' => array(
			'time' => '发表时间',
			'views' => '浏览量',
		)
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'g_tao_h_id',
		'std' => '',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '三栏小工具',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'group_widget_three',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：13',
		'id' => 'group_widget_three_s',
		'std' => '13',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '新闻资讯A',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'group_cat_a',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：14',
		'id' => 'group_cat_a_s',
		'std' => '14',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'group_cat_a_n',
		'std' => '4',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'group_cat_a_id',
		'std' => '1,2',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '第一篇调用分类推荐文章',
		'id' => 'group_cat_a_top',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '两栏小工具',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'group_widget_two',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：15',
		'id' => 'group_widget_two_s',
		'std' => '15',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '新闻资讯B',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'group_cat_b',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：16',
		'id' => 'group_cat_b_s',
		'std' => '16',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'group_cat_b_n',
		'std' => '4',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'group_cat_b_id',
		'std' => '1,2',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '第一篇调用分类置顶文章',
		'id' => 'group_cat_b_top',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => 'Tab分类',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'group_tab',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：17',
		'id' => 'group_tab_s',
		'std' => '17',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'group_tab_n',
		'std' => '8',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'group_tab_cat_id',
		'std' => '',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'class' => '',
	);

	$options[] = array(
		'name' => '分栏',
		'desc' => '',
		'id' => 'stab_f',
		'class' => 'rr',
		'std' => '4',
		'type' => 'radio',
		'options' => $fl456 
	);

	$options[] = array(
		'class' => '',
	);

	$options[] = array(
		'desc' => '显示文章信息',
		'id' => 'group_tab_meta',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '新闻资讯C',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'group_cat_c',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：18',
		'id' => 'group_cat_c_s',
		'std' => '18',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'group_cat_c_n',
		'std' => '4',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'group_cat_c_id',
		'std' => '1,2',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '第一篇显示缩略图',
		'id' => 'group_cat_c_img',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '热门推荐',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'group_carousel',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '标题',
		'desc' => '自定义标题文字',
		'id' => 'group_carousel_t',
		'std' => '热门推荐',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '文字说明',
		'id' => 'carousel_des',
		'std' => '文字说明文字说明文字说明文字说明文字说明文字说明',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：19',
		'id' => 'group_carousel_s',
		'std' => '19',
		'class' => 'mini',
		'type' => 'text'
	);

	if ( $options_categories ) {
	$options[] = array(
		'name' => '',
		'desc' => '选择一个分类',
		'id' => 'group_carousel_id',
		'type' => 'select',
		'options' => $options_categories);
	}

	$options[] = array(
		'name' => '',
		'desc' => '调用图片日志',
		'id' => 'group_gallery',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '图片日志分类',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'group_gallery_id',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'carousel_n',
		'std' => '8',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '背景图片',
		'desc' => '上传背景图片',
		'id' => 'carousel_bg_img',
		 "std" => "https://s2.loli.net/2021/12/05/ioc8JHN1MfBgynv.jpg",
		'type' => 'upload'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '隔行变色',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '启用',
		'id' => 'g_line',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '分类列表是否显示子分类文章',
		'class' => 'bel',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '不显示子分类文章',
		'id' => 'group_no_cat_child',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	// 首页分类图片布局

	$options[] = array(
		'name' => '分类图片',
		'type' => 'heading'
	);

	$options[] = array(
		'name' => '分类图片布局最新文章',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'grid_cat_new',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'grid_cat_news_n',
		'std' => '4',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'class' => '',
	);

	$options[] = array(
		'name' => '分栏',
		'desc' => '',
		'id' => 'grid_new_f',
		'std' => '4',
		'class' => 'rr',
		'type' => 'radio',
		'options' => $fl456
	);

	$options[] = array(
		'name' => '分类链接',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'grid_new_cat_id',
		'class' => 'tk',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '分类文章数不受上面限制',
		'id' => 'grid_new_cat_n',
		'class' => 'tk',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '分类模块A',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'grid_cat_a',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'grid_cat_a_n',
		'std' => '4',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'grid_cat_a_id',
		'std' => '1,2',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'class' => '',
	);

	$options[] = array(
		'name' => '分栏',
		'desc' => '',
		'id' => 'grid_cat_a_f',
		'std' => '4',
		'class' => 'rr',
		'type' => 'radio',
		'options' => $fl456
	);

	$options[] = array(
		'name' => '',
		'desc' => '显示同级分类链接',
		'id' => 'grid_cat_a_child',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '杂志单栏小工具',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'grid_widget_one',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '分类滚动模块',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'grid_carousel',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'grid_carousel_n',
		'std' => '8',
		'class' => 'mini',
		'type' => 'text'
	);

	if ( $options_categories ) {
	$options[] = array(
		'name' => '',
		'desc' => '选择一个分类',
		'id' => 'grid_carousel_id',
		'type' => 'select',
		'options' => $options_categories);
	}

	$options[] = array(
		'name' => '分栏',
		'desc' => '',
		'id' => 'grid_carousel_f',
		'std' => '4',
		'class' => 'rr',
		'type' => 'radio',
		'options' => $fl456 
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '分类模块B',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'grid_cat_b',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'grid_cat_b_n',
		'std' => '5',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'grid_cat_b_id',
		'std' => '1,2',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'class' => '',
	);

	$options[] = array(
		'name' => '分栏',
		'desc' => '',
		'id' => 'grid_cat_b_f',
		'std' => '4',
		'class' => 'rr',
		'type' => 'radio',
		'options' => $fl456
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '杂志三栏小工具',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'grid_widget_two',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '分类模块C',
		'type' => 'groupstart'
	);

	$options[] = array(
		'name' => '',
		'desc' => '显示',
		'id' => 'grid_cat_c',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'grid_cat_c_n',
		'std' => '4',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'grid_cat_c_id',
		'std' => '1,2',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'class' => '',
	);

	$options[] = array(
		'name' => '分栏',
		'desc' => '',
		'id' => 'grid_cat_c_f',
		'std' => '4',
		'class' => 'rr',
		'type' => 'radio',
		'options' => $fl456
	);

	$options[] = array(
		'type' => 'groupend'
	);


	$options[] = array(
		'name' => '分类列表是否显示子分类文章',
		'class' => 'bel',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '不显示子分类文章',
		'id' => 'no_grid_cat_child',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	// 搜索设置
	$options[] = array(
		'name' => '搜索设置',
		'type' => 'heading'
	);

	$options[] = array(
		'name' => '默认搜索设置',
		'desc' => '启用',
		'id' => 'wp_s',
		'class' => '',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '只搜索标题',
		'id' => 'search_title',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '搜索结果只有一个自动跳转',
		'id' => 'auto_search_post',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '搜索选项',
		'id' => 'search_option',
		'class' => 'rr',
		'std' => 'search_default',
		'type' => 'radio',
		'options' => array(
			'search_default' => '默认',
			'search_url' => '修改搜索URL',
			'search_cat' => '分类搜索',
		)
	);

	$options[] = array(
		'name' => '',
		'desc' => '排除的分类',
		'id' => 'not_search_cat',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => 'Ajax搜索',
	);

	$options[] = array(
		'desc' => 'Ajax搜索替换默认',
		'id' => 'ajax_search',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => 'Ajax搜索显示篇数',
		'id' => 'ajax_search_n',
		'std' => '16',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '搜索结果布局',
		'id' => 'search_the',
		'class' => 'rr',
		'std' => 'search_list',
		'type' => 'radio',
		'options' => array(
			'search_list' => '标题布局',
			'search_img' => '图片布局',
			'search_normal' => '标准布局',
		)
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '搜索引擎',
	);

	$options[] = array(
		'desc' => '百度',
		'id' => 'baidu_s',
		'class' => 'chl',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'desc' => 'Google',
		'id' => 'Google_s',
		'class' => 'chl',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'desc' => '必应',
		'id' => 'bing_s',
		'class' => 'chl',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'desc' => '360',
		'id' => '360_s',
		'class' => 'chl',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'desc' => '搜狗',
		'id' => 'sogou_s',
		'class' => 'chl',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => 'Google 搜索ID',
		'id' => 'google_t',
		'class' => 'el',
	);

	$options[] = array(
		'desc' => '申请地址：https://cse.google.com/',
		'id' => 'google_id',
		'class' => '',
		'std' => '005077649218303215363:ngrflw3nv8m',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '搜索推荐',
		'id' => 'search_nav',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '主菜单搜索按钮',
		'id' => 'menu_search_button',
		'std' => '1',
		'type' => 'checkbox'
	);

	// 网站标志

	$options[] = array(
		'name' => '网站标志',
		'type' => 'heading'
	);

	$options[] = array(
		'name' => '站点LOGO/标志',
		'id' => 'site_sign',
		'class' => 'rr',
		'std' => 'logo_small',
		'type' => 'radio',
		'options' => array(
			'logos' => 'LOGO',
			'logo_small' => '标志+标题',
			'no_logo' => '仅标题',
		)
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '上传Logo',
		'desc' => '透明png或svg图片最佳，比例 220×50px',
		'id' => 'logo',
		"std" => "$blogpath/logo.png",
		'type' => 'upload'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '上传标志',
		'desc' => '透明png或svg图片最佳，比例 50×50px',
		'id' => 'logo_small_b',
		"std" => "$blogpath/logo-s.png",
		'type' => 'upload'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '站点名称扫光动画',
		'id' => 'logo_css',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '自定义Favicon',
		'desc' => '上传favicon.ico(普通图片格式的也可以)，并通过FTP上传到网站根目录',
		'id' => 'favicon',
		'class' => '',
		"std" => "$blogpath/favicon.ico",
		'type' => 'upload'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '自定义IOS屏幕图标',
		'desc' => '上传苹果移动设备添加到主屏幕图标',
		'id' => 'apple_icon',
		'class' => '',
		"std" => "$blogpath/favicon.png",
		'type' => 'upload'
	);

	// 辅助功能

	$options[] = array(
		'name' => '辅助功能',
		'type' => 'heading'
	);

	$options[] = array(
		'name' => '阿里图标库',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '<a href="https://www.iconfont.cn/">添加图标</a>',
		'id' => 'iconfont_cn',
		'class' => 'icon_sm icon-url'
	);

	$options[] = array(
		'desc' => '单色图标链接（Font class）：',
		'class' => 'icon_sm el fol '
	);

	$options[] = array(
		'id' => 'iconfont_url',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '彩色图标链接（Symbol）：',
		'class' => 'icon_sm el fol'
	);

	$options[] = array(
		'id' => 'iconsvg_url',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '本地图标，将下载的图标库解压，把文件夹改名为iconfont，上传到begin\css目录中',
		'id' => 'iconfont',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '编辑器切换',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '使用经典编辑器',
		'id' => 'start_classic_editor',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '使用经典小工具编辑器',
		'id' => 'classic_widgets',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '前端禁止加载区块编辑器style和script',
		'id' => 'disable_block_styles',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => 'Gravatar 头像设置',
		'type' => 'groupstart'
	);

	$options[] = array(
		'id' => 'gravatar_url',
		'class' => 'rr',
		'std' => 'zh',
		'type' => 'radio',
		'options' => array(
			'no' => '默认',
			'cn' => 'cn获取',
			'ssl' => 'ssl获取',
			'zh' => '自定义'
		)
	);

	$options[] = array(
		'name' => '',
		'desc' => '后台禁止头像',
		'id' => 'ban_avatars',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '头像延迟加载',
		'id' => 'avatar_load',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '自定义获取头像地址',
		'desc' => '默认：cravatar.cn/avatar/',
		'id' => 'zh_url',
		'class' => 'tk',
		'std' => 'cravatar.cn/avatar/',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '自定义默认头像',
	);

	$options[] = array(
		'id' => 'default_avatar_m',
		'class' => 'rr',
		'std' => 'default_avatar_f',
		'type' => 'radio',
		'options' => array(
			'default_avatar_f' => '固定',
			'default_avatar_r' => '随机'
		)
	);

	$options[] = array(
		'desc' => '上传自定义固定默认头像，更改后需进入设置 → 讨论 → 默认头像，勾选“自定义”，并保存更改',
		'class' => 'el fol'
	);

	$options[] = array(
		'id' => 'default_avatar',
		'std' => "$blogpath/logo-s.png",
		'type' => 'upload'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '上传头像',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '允许上传头像',
		'id' => 'local_avatars',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '允许所有角色上传头像',
		'id' => 'all_local_avatars',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '头像缓存到本地',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '启用，设置 wp-content/uploads/avatar 目录权限为 755 或 777',
		'id' => 'cache_avatar',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '获取头像地址',
		'desc' => '默认：http://www.gravatar.com/avatar/',
		'id' => 'gravatar_origin',
		'class' => 'tk',
		'std' => 'cravatar.cn/avatar/',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '未设置头像则显示：',
		'id' => 'avatar_sm',
		'class' => 'el'
	);

	$options[] = array(
		'name' => '',
		'id' => 'avatar_url',
		'class' => 'rr',
		'std' => 'letter_img',
		'type' => 'radio',
		'options' => array(
			'letter_img' => '首字图片',
			'rand_img' => '随机图片',
		)
	);

	$options[] = array(
		'name' => '随机头像，多张图片链接用英文半角逗号","隔开',
		'desc' => '',
		'id' => 'random_avatar_url',
		'class' => 't70',
		'std' => 'https://s2.loli.net/2021/12/05/RtCpJUTL2rMkF6A.png,https://s2.loli.net/2021/12/05/ILhr3MKxnCuUfXz.png,https://s2.loli.net/2021/12/05/NecWi7RSmsqPOjn.png,https://s2.loli.net/2021/12/05/adt7TRIfmNLDVvX.png,https://s2.loli.net/2021/12/05/QEvL8gx9ZzwPYis.png',
		'type' => 'textarea'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '社会化登录',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '启用',
		'id' => 'be_social_login',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => 'QQ',
		'desc' => '申请地址：<a href="https://connect.qq.com/" title="申请地址">https://connect.qq.com</a>',
		'id' => 'qq_id_url',
		'class' => 'social_hide social_sm el'
	);
	
	$options[] = array(
		'desc' => '网站回调域：'. $qq_auth .'',
		'id' => 'qq_id_auth',
		'class' => 'social_hide social_sm el'
	);

	$options[] = array(
		'name' => '',
		'desc' => 'QQ APP ID',
		'id' => 'qq_app_id',
		'class' => 'social_hide',
		'std' => '123',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => 'QQ APP Key',
		'id' => 'qq_key',
		'class' => 'social_hide',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '微博',
		'desc' => '申请地址：<a href="https://open.weibo.com/" title="申请地址">https://open.weibo.com</a>',
		'id' => 'weibo_key_url',
		'class' => 'social_hide social_sm el'
	);

	$options[] = array(
		'desc' => '应用地址：'. $weibo_auth .'',
		'id' => 'weibo_key_auth',
		'class' => 'social_hide social_sm el'
	);

	$options[] = array(
		'name' => '',
		'desc' => '微博 App Key',
		'id' => 'weibo_key',
		'class' => 'social_hide',
		'std' => '123',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '微博 App Secret',
		'id' => 'weibo_secret',
		'class' => 'social_hide',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '微信',
		'desc' => '申请地址（企业认证）：<a href="https://open.weixin.qq.com/" title="申请地址">https://open.weixin.qq.com</a>',
		'id' => 'weibo_key_url',
		'class' => 'social_hide social_sm el'
	);

	$options[] = array(
		'desc' => '授权回调域：'. $weixin_auth .'',
		'id' => 'weibo_key_auth',
		'class' => 'social_hide social_sm el'
	);

	$options[] = array(
		'name' => '',
		'desc' => '微信 APP ID',
		'id' => 'weixin_id',
		'class' => 'social_hide',
		'std' => '123',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '微信 App Secret',
		'id' => 'weixin_secret',
		'class' => 'social_hide',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '登录后跳转的地址',
		'desc' => '比如网站首页链接',
		'id' => 'social_login_url',
		'class' => 'social_hide tk',
		'std' => $bloghome,
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '邮件SMTP',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '启用',
		'id' => 'setup_email_smtp',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '发件人名称',
		'id' => 'email_name',
		'std' => '来自网站',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '邮箱SMTP服务器',
		'id' => 'email_smtp',
		'std' => 'smtp.163.com',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '邮箱账户',
		'id' => 'email_account',
		'std' => 'beginthemes@163.com',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '客户端授权密码 ( 不是邮箱登录密码 )',
		'id' => 'email_authorize',
		'std' => 'NLSUYCUSEXUGUYHR',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '多条件筛选',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '启用',
		'id' => 'filters',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '单项选择'
	);

	$options[] = array(
		'desc' => '筛选分类',
		'id' => 'filters_cat',
		'class' => 'fia-catid chl',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'desc' => '筛选A',
		'id' => 'filters_a',
		'class' => 'fia-catid chl',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'desc' => '筛选B',
		'id' => 'filters_b',
		'class' => 'fia-catid chl',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'desc' => '筛选C',
		'id' => 'filters_c',
		'class' => 'fia-catid chl',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'desc' => '筛选D',
		'id' => 'filters_d',
		'class' => 'fia-catid chl',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'desc' => '筛选E',
		'id' => 'filters_e',
		'class' => 'fia-catid chl',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'desc' => '筛选F',
		'id' => 'filters_f',
		'class' => 'fia-catid chl',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '分类及文字'
	);

	$options[] = array(
		'desc' => '筛选分类，多个","隔开',
		'id' => 'filters_cat_id',
		'class' => 'chl',
		'std' => '1,2',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '标题文字',
		'id' => 'filter_t',
		'class' => 'chl',
		'std' => '条 件 筛 选',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '筛选A文字',
		'id' => 'filters_a_t',
		'class' => 'chl',
		'std' => '风格',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '筛选B文字',
		'id' => 'filters_b_t',
		'class' => 'chl',
		'std' => '价格',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '筛选C文字',
		'id' => 'filters_c_t',
		'class' => 'chl',
		'std' => '功能',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '筛选D文字',
		'id' => 'filters_d_t',
		'class' => 'chl',
		'std' => '大小',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '筛选E文字',
		'id' => 'filters_e_t',
		'class' => 'chl',
		'std' => '地域',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '筛选F文字',
		'id' => 'filters_f_t',
		'class' => 'chl',
		'std' => '品牌',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '筛选条件默认隐藏',
		'id' => 'filters_hidden',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '筛选结果使用图片布局',
		'id' => 'filters_img',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '前端投稿',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '启用，新建页面并添加短代码 [bet_submission_form]',
		'id' => 'front_tougao',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'class' => '',
	);

	$options[] = array(
		'name' => '模式选择',
		'id' => 'tougao_mode',
		'class' => 'rr',
		'std' => 'post_mode',
		'type' => 'radio',
		'options' => array(
			'post_mode' => '文章投稿',
			'info_mode' => '信息提交',
		)
	);

	$options[] = array(
		'class' => '',
	);

	$options[] = array(
		'desc' => '不审核立即发表',
		'id' => 'instantly_publish',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '文章投稿设置',
	);

	$options[] = array(
		'desc' => '允许特色图像',
		'id' => 'thumbnail_required',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'class' => '',
	);

	$options[] = array(
		'name' => '投稿（贡献）者上传权限',
		'id' => 'user_upload',
		'class' => 'rr',
		'std' => 'removecap',
		'type' => 'radio',
		'options' => array(
			'removecap' => '禁止',
			'addcap' => '允许',
		)
	);

	$options[] = array(
		'name' => '分类排除',
		'desc' => '输入排除的分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'not_front_cat',
		'std' => '',
		'class' => 'tk',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '信息提交设置'
	);

	$options[] = array(
		'desc' => '仅提交到“公告”文章',
		'id' => 'submit_bulletin',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入一个分类ID',
		'id' => 'info_cat',
		'class' => 'mini',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'desc' => '表单文字，留空不显示',
		'class' => 'el'
	);

	$options[] = array(
		'name' => '',
		'desc' => '表单A文字',
		'id' => 'info_a',
		'class' => 'mh',
		'std' => '姓名',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '表单B文字',
		'id' => 'info_b',
		'class' => 'mh',
		'std' => '职业',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '表单C文字',
		'id' => 'info_c',
		'class' => 'mh',
		'std' => '学历',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '表单D文字',
		'id' => 'info_d',
		'class' => 'mh',
		'std' => '电话',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '表单E文字',
		'id' => 'info_e',
		'class' => 'mh',
		'std' => '微信/QQ',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '表单F文字',
		'id' => 'info_f',
		'class' => 'mh',
		'std' => '邮箱',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'desc' => '单选文字，留空不显示',
		'class' => 'el'
	);

	$options[] = array(
		'name' => '',
		'desc' => '单选A文字',
		'id' => 's_info_a',
		'class' => 'mh',
		'std' => '选择',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '单选B文字',
		'id' => 's_info_b',
		'class' => 'mh',
		'std' => '高中',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '单选C文字',
		'id' => 's_info_c',
		'class' => 'mh',
		'std' => '大专',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '单选D文字',
		'id' => 's_info_d',
		'class' => 'mh',
		'std' => '本科',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '单选E文字',
		'id' => 's_info_e',
		'class' => 'mh',
		'std' => '本科以上',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '单选F文字',
		'id' => 's_info_f',
		'class' => 'mh',
		'std' => '备用选项',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '文章浏览统计',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '启用',
		'id' => 'post_views',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'class' => '',
	);

	$options[] = array(
		'desc' => '仅登录可见',
		'id' => 'user_views',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '<a href="' . home_url() . '/wp-admin/options-general.php?page=views_options">更多设置</a>',
		'id' => 'setup_views'

	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '邀请码注册',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '启用',
		'id' => 'invitation_code',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '<a href="' . home_url() . '/wp-admin/admin.php?page=be_invitation_code_add">添加邀请码</a>',
		'id' => 'add_invitation'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '仅登录访问',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '启用',
		'id' => 'force_login',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '登录注册页面链接',
		'id' => 'force_login_url',
		'class' => 'tk',
		'std' => $bloglogin,
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '跟随按钮设置',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '返回首页按钮',
		'id' => 'scroll_z',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '公告按钮',
		'id' => 'placard_but',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '返回顶部按钮',
		'id' => 'scroll_h',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '转到底部按钮',
		'id' => 'scroll_b',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '夜间模式',
		'id' => 'read_night',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '跟随搜索按钮',
		'id' => 'scroll_s',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '跟随评论按钮',
		'id' => 'scroll_c',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '显示简繁体转换按钮',
		'id' => 'gb2',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '显示本页二维码按钮',
		'id' => 'qrurl',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '移动端不显示',
		'id' => 'mobile_scroll',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '是否使用自定义分类文章',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '公告',
		'id' => 'no_bulletin',
		'class' => 'chl',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'desc' => '图片',
		'id' => 'no_gallery',
		'class' => 'chl',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'desc' => '视频',
		'id' => 'no_videos',
		'class' => 'chl',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'desc' => '商品',
		'id' => 'no_tao',
		'class' => 'chl',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'desc' => '网址',
		'id' => 'no_favorites',
		'class' => 'chl',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'desc' => '产品',
		'id' => 'no_products',
		'class' => 'chl',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '什么角色显示后台自定义分类法',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '默认作者及投稿者以下不显示',
		'id' => 'no_type',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '修改角色，管理员10，编辑7，作者2，投稿者1',
		'id' => 'user_level',
		'class' => 'mini',
		'std' => '3',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '选择角色显示文章选项面板',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '管理员10，编辑7，作者2，投稿者1',
		'id' => 'boxes_level',
		'class' => 'mini',
		'std' => '3',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '新浪微博关注按钮',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'weibo_t',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入新浪微博ID',
		'id' => 'weibo_id',
		'std' => '1882973105',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '关注微信公众号获取验证码',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '微信公众号名称',
		'id' => 'wechat_fans',
		'std' => '公众号名称',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '统一的密码和关键字',
		'id' => 'wechat_unite',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '统一密码',
		'id' => 'weifans_pass',
		'std' => '123',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '统一关键字',
		'id' => 'weifans_key',
		'std' => '关键字',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '微信公众号二维码图片',
		'desc' => '上传微信公众号二维码图片',
		'id' => 'wechat_qr',
		'std' => "$blogpath/favicon.png",
		'type' => 'upload'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '在线咨询',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'contact_us',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'class' => '',
	);

	$options[] = array(
		'desc' => '默认不隐藏',
		'id' => 'contact_s',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '微信文字',
		'id' => 'weixing_us_t',
		'std' => '微信咨询',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '上传微信二维码',
		'desc' => '微信二维码',
		'id' => 'weixing_us',
        "std" => "$blogpath/favicon.png",
		'type' => 'upload'
	);

	$options[] = array(
		'name' => '',
		'desc' => 'QQ文字',
		'id' => 'usqq_t',
		'std' => 'QQ咨询',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => 'QQ号码',
		'id' => 'usqq_id',
		'std' => '8888',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '在线咨询文字',
		'id' => 'usshang_t',
		'std' => '在线咨询',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '在线咨询链接',
		'id' => 'usshang_url',
		'std' => '#',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '电话文字',
		'id' => 'us_phone_t',
		'std' => '服务热线',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '电话号码',
		'id' => 'us_phone',
		'std' => '1308888888',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => 'QQ在线',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'qq_online',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '自定义文字',
		'id' => 'qq_name',
		'std' => '在线咨询',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入QQ号码',
		'id' => 'qq_id',
		'std' => '8888',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入手机号',
		'id' => 'm_phone',
		'std' => '13688888888',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '微信说明',
		'id' => 'weixing_t',
		'std' => '微信',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '微信二维码',
		'id' => 'weixing_qr',
        "std" => "$blogpath/favicon.png",
		'type' => 'upload'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '正文末尾微信二维码',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'single_weixin',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'class' => '',
	);

	$options[] = array(
		'desc' => '只显示一个微信二维码',
		'id' => 'single_weixin_one',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '微信文字',
		'id' => 'weixin_h',
		'std' => '我的微信',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '微信说明文字',
		'id' => 'weixin_h_w',
		'std' => '微信扫一扫',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '上传微信二维码图片（＜240px）',
		'id' => 'weixin_h_img',
		'std' => "$blogpath/favicon.png",
		'type' => 'upload'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '微信公众号文字',
		'id' => 'weixin_g',
		'std' => '我的微信公众号',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '微信公众号说明文字',
		'id' => 'weixin_g_w',
		'std' => '微信扫一扫',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '上传微信公众号二维码图片（＜240px）',
		'id' => 'weixin_g_img',
		'std' => "$blogpath/favicon.png",
		'type' => 'upload'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '点赞分享',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '打赏',
		'id' => 'shar_donate',
		'class' => 'chl',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'desc' => '点赞',
		'id' => 'shar_like',
		'class' => 'chl',
		'std' => '1',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'desc' => '收藏',
		'id' => 'shar_favorite',
		'class' => 'chl',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'desc' => '分享',
		'id' => 'shar_share',
		'class' => 'chl',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'desc' => '链接',
		'id' => 'shar_link',
		'class' => 'chl',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'desc' => '海报',
		'id' => 'shar_poster',
		'class' => 'chl',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '同时显示在左侧',
		'id' => 'like_left',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '海报设置',
		'type' => 'groupstart'
	);

	$options[] = array(
		'name' => '自定义海报网站名称',
		'desc' => '网站名称',
		'id' => 'poster_site_name',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '网站副标题',
		'id' => 'poster_site_tagline',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '自定义海报LOGO',
		'desc' => '上传LOGO（50×50）',
		'id' => 'poster_logo',
		'std' => '',
		'type' => 'upload'
	);

	$options[] = array(
		'name' => '海报默认图片',
		'desc' => '上传海报默认图片',
		'id' => 'poster_default_img',
		'std' => '',
		'type' => 'upload'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '打赏二维码',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '上传微信收款二维码图片',
		'id' => 'qr_a',
		"std" => "$blogpath/favicon.png",
		'type' => 'upload'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '上传支付宝收钱二维码图片',
		'id' => 'qr_b',
		"std" => "$blogpath/favicon.png",
		'type' => 'upload'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '会员登录查看',
		'type' => 'groupstart'
	);

	$options[] = array(
		'name' => '选择一个有权查看隐藏内容的角色',
		'id' => 'user_roles',
		'class' => 'rr ht',
		'std' => 'author',
		'type' => 'radio',
		'options' => array(
			'administrator' => '管理员',
			'editor'        => '编辑',
			'author'        => '作者',
			'contributor'   => '贡献者',
			'subscriber'    => '订阅者',
			'vip_roles'     => '自定义角色'
		)
	);

	$options[] = array(
		'name' => '自定义提示',
		'desc' => '',
		'id' => 'role_visible_t',
		'class' => 'tk ht',
		'std' => '隐藏的内容',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '无权限查看提示文字',
		'desc' => '',
		'id' => 'role_visible_w',
		'class' => 'tk ht',
		'std' => '无权限查看',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '自定义说明',
		'desc' => '',
		'id' => 'role_visible_c',
		'class' => 'ht',
		'std' => '会员登录后查看',
		'type' => 'textarea'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '新建角色',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '',
		'id' => 'del_new_roles',
		'class' => 'rr',
		'std' => 'no_roles',
		'type' => 'radio',
		'options' => array(
			'no_roles' => '无新角色',
			'new_roles' => '新建角色',
			'del_roles' => '删除角色'
		)
	);

	$options[] = array(
		'name' => '',
		'desc' => '角色名称',
		'id' => 'roles_name',
		'std' => '会员',
		'type' => 'text'
	);

	$options[] = array(
		'class' => 'el',
		'desc' => '改角色名称，先选择→删除角色→保存设置→修改文字→选择→新建角色→保存设置→最后选择→无新角色→保存设置'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '评论查看自定义文字',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '启用',
		'id' => 'reply_read_d',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '自定义提示',
		'desc' => '',
		'id' => 'reply_read_t',
		'class' => 'tk ht',
		'std' => '此处为隐藏的内容',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '自定义说明',
		'desc' => '',
		'id' => 'reply_read_c',
		'class' => 'ht',
		'std' => '发表评论并刷新，方可查看',
		'type' => 'textarea'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '登录查看自定义文字',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '启用',
		'id' => 'login_read_d',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '自定义提示',
		'desc' => '',
		'id' => 'login_read_t',
		'class' => 'tk ht',
		'std' => '此处为隐藏的内容',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '自定义说明',
		'desc' => '',
		'id' => 'login_read_c',
		'class' => 'ht',
		'std' => '注册登录后，方可查看',
		'type' => 'textarea'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '自定义登录/评论查看按钮背景',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '上传图片',
		'id' => 'read_img',
		'class' => '',
        "std" => "https://s2.loli.net/2021/12/05/Eropm1CV78ZblMn.jpg",
		'type' => 'upload'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '自定义404页面',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '自定义404页面标题',
		'id' => '404_t',
		'class' => 'tk',
		'std' => '亲，你迷路了！',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '自定义404页面内容',
		'desc' => '',
		'id' => '404_c',
		'std' => '亲，该网页可能搬家了！<br /><a href="#" target="_blank"><img src="https://s2.loli.net/2021/12/05/7J38OVbmzZHlSjK.jpg" alt="广告也精彩" /></a>',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => '404跳转首页',
		'desc' => '',
		'id' => '404_go',
		'class' => 'rr',
		'std' => '404_d',
		'type' => 'radio',
		'options' => array(
			'404_s' => '读秒跳转',
			'404_h' => '直接跳转',
			'404_d' => '不跳转'
		)
	);

	$options[] = array(
		'name' => '自定义链接',
		'desc' => '',
		'id' => '404_url',
		'class' => 'tk',
		'std' => ''. home_url() .'',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '切换主题为英文语言',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '英文语言',
		'id' => 'languages_en',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '网址页面',
		'type' => 'groupstart'
	);

	$options[] = array(
		'name' => '',
		'desc' => '',
		'id' => 'site_f',
		'std' => '4',
		'class' => 'rr',
		'type' => 'radio',
		'options' => $fsl45 
	);

	$options[] = array(
		'name' => '',
		'desc' => '显示篇数',
		'id' => 'site_p_n',
		'std' => '100',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '网址页面排除的父分类ID',
		'id' => 'sites_cat_id',
		'std' => '',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '网址显示Favicon图标',
		'id' => 'sites_ico',
		'class' => '',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '装饰动画',
		'id' => 'sites_adorn',
		'class' => '',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '分类目录',
		'id' => 'all_site_cat',
		'class' => '',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'class' => '',
	);

	$options[] = array(
		'desc' => '固定在左侧',
		'id' => 'site_cat_fixed',
		'class' => '',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '网址页面小工具，输入网址分类ID，显示在指定分类下',
		'id' => 'sites_widgets_one_n',
		'std' => '',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '',
		'id' => 'sw_f',
		'std' => '2',
		'class' => 'rr',
		'type' => 'radio',
		'options' => $swf12 
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '网址正文显示网站截图',
		'id' => 'site_sc',
		'class' => '',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '获取网站描述出错时勾选，用后取消勾选',
		'id' => 'sites_url_error',
		'class' => '',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	if ( function_exists( 'is_shop' ) ) {
		$options[] = array(
			'name' => 'WOO商店',
			'type' => 'groupstart'
		);

		$options[] = array(
			'name' => '',
			'desc' => '每页显示数量',
			'id' => 'woo_cols_n',
			'std' => '20',
			'class' => 'mini',
			'type' => 'text'
		);

		$options[] = array(
			'name' => '',
			'desc' => '相关文章数量',
			'id' => 'woo_related_n',
			'std' => '4',
			'class' => 'mini',
			'type' => 'text'
		);

		$options[] = array(
			'name' => '分栏',
			'desc' => '',
			'id' => 'woo_f',
			'std' => '5',
			'class' => 'rr',
			'type' => 'radio',
			'options' => $fl456 
		);

		$options[] = array(
			'name' => '默认缩略图',
			'desc' => '默认缩略图',
			'id' => 'woo_thumbnail',
	        "std" => "https://s2.loli.net/2022/01/06/9rRz78gvLDP5plh.jpg",
			'type' => 'upload'
		);

		$options[] = array(
			'name' => '商店页面默认图片',
			'desc' => '商店页面默认图片',
			'id' => 'shop_header_img',
	        "std" => "https://s2.loli.net/2021/12/05/I6agBhOx9Qtyl7p.jpg",
			'type' => 'upload'
		);

		$options[] = array(
			'type' => 'groupend'
		);
	}

	$options[] = array(
		'name' => '综合辅助',
		'type' => 'groupstart'
	);

	$options[] = array(
		'name' => '',
		'desc' => '链接点击统计（短链接）',
		'id' => 'links_click',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '下载模块登录查看密码',
		'id' => 'login_down_key',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '移除jquery migrate',
		'id' => 'remove_jqmigrate',
		'class' => '',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '在页脚显示查询次数及加载时间',
		'id' => 'web_queries',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '显示WordPress设置选项字段',
		'id' => 'all_settings',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '删除文章收藏数据表',
		'id' => 'delete_favorite',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '头部添加“referrer”标签（仅外链新浪微相册时用）',
		'id' => 'no_referrer',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '只有临时使用文章快速编辑和定时发布时使用，防止文章选项丢失',
		'id' => 'meta_delete',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '背景图片',
		'type' => 'groupstart'
	);

	$options[] = array(
		'name' => '',
		'desc' => '启用后台登录美化',
		'id' => 'custom_login',
		'class' => '',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '上传背景图片',
		'id' => 'login_img',
        "std" => "https://s2.loli.net/2021/12/05/DrSqPxyc7WlmYEf.jpg",
		'type' => 'upload'
	);

	$options[] = array(
		'name' => '',
		'desc' => '必应每日壁纸',
		'id' => 'bing_login',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '注册页面背景图片',
		'desc' => '上传背景图片',
		'id' => 'reg_img',
		'class' => '',
		"std" => "https://s2.loli.net/2021/12/05/9xnPUO2ZtFSNfrW.jpg",
		'type' => 'upload'
	);

	$options[] = array(
		'name' => '',
		'desc' => '必应每日壁纸',
		'id' => 'bing_reg',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '下载页面背景图片',
		'desc' => '上传背景图片',
		'id' => 'down_header_img',
		'class' => '',
		"std" => "https://s2.loli.net/2021/12/05/qH1SNgls95RZGJP.jpg",
		'type' => 'upload'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '作者存档头部图片',
		'desc' => '上传背景图片',
		'id' => 'header_author_img',
		'class' => '',
		"std" => "https://s2.loli.net/2021/12/05/qH1SNgls95RZGJP.jpg",
		'type' => 'upload'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '用户信息背景图片',
		'desc' => '上传背景图片',
		'id' => 'user_back',
		'class' => '',
		"std" => "https://s2.loli.net/2021/12/05/FOKvZnDLSYtQk3M.jpg",
		'type' => 'upload'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '页脚小工具背景图片',
		'desc' => '上传背景图片',
		'id' => 'footer_widget_img',
		'class' => '',
		"std" => "https://s2.loli.net/2021/12/05/I6agBhOx9Qtyl7p.jpg",
		'type' => 'upload'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '下载页面',
		'class' => 'bel',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '下载链接到根目录，并执行下面的操作',
		'id' => 'root_down_url',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '自动将“inc/download.php”文件复制到网站根目录，勾选后需保存两次设置，用后取消勾选',
		'id' => 'root_file_move',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '版权说明',
	);

	$options[] = array(
		'desc' => '可使用HTML代码',
		'id' => 'down_explain',
		'std' => '本站大部分下载资源收集于网络，只做学习和交流使用，版权归原作者所有。若您需要使用非免费的软件或服务，请购买正版授权并合法使用。本站发布的内容若侵犯到您的权益，请联系站长删除，我们将及时处理。',
		'type' => 'textarea'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	// SEO设置

	$options[] = array(
		'name' => 'SEO',
		'type' => 'heading'
	);

	$options[] = array(
		'name' => '站点SEO',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '启用主题自带SEO功能，如使用其它SEO插件，请取消勾选',
		'id' => 'wp_title',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'class' => '',
	);

	$options[] = array(
		'desc' => '显示OG协议标签',
		'id' => 'og_title',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '首页描述（Description）',
		'desc' => '',
		'id' => 'description',
		'std' => '一般不超过200个字符',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => '首页关键词（KeyWords）',
		'desc' => '',
		'id' => 'keyword',
		'std' => '一般不超过100个字符',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => '自定义网站首页title',
		'desc' => '留空则不显示自定义title',
		'id' => 'home_title',
		'class' => 'tk',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '自定义网站首页副标题',
		'desc' => '留空则不显示副标题',
		'id' => 'home_info',
		'class' => 'tk',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'class' => '',
	);

	$options[] = array(
		'desc' => '首页显示站点副标题',
		'id' => 'blog_info',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'class' => '',
	);

	$options[] = array(
		'desc' => '文章title无网站名称',
		'id' => 'blog_name',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'class' => '',
	);

	$options[] = array(
		'desc' => '修改站点分隔符',
		'id' => 'connector',
		'std' => '|',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'class' => '',
	);

	$options[] = array(
		'desc' => '分隔符无空格',
		'id' => 'blank_connector',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '杂志、分类图片、公司布局首页分页链接301转向',
		'id' => 'home_paged_ban',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '站点地图',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '更新站点地图xml格式，查看：<a href="' . home_url() . '/sitemap.xml">sitemap.xml</a>',
		'id' => 'sitemap_xml',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'class' => '',
	);

	$options[] = array(
		'desc' => '更新站点地图txt格式，查看：<a href="' . home_url() . '/sitemap.txt">sitemap.txt</a>',
		'id' => 'sitemap_txt',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '更新文章数，输入“-1”为全部',
		'id' => 'sitemap_n',
		'class' => 'mini',
		'std' => '100',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '包括标签',
		'id' => 'no_sitemap_tag',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '同时更新上万文章可能会卡死，酌情设置，更新完取消勾选并保存设置',
		'class' => 'sitemap_h el',
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '文章归档页面',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '刷新文章归档页面，需保存两次主题选项设置，用后关闭',
		'id' => 'update_be_archives',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '文章更新页面',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '刷新文章更新页面，需保存两次主题选项设置，用后关闭',
		'id' => 'update_up_archives',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '可以单独设置年、月、分类，留空则显示全部文章',
		'id' => 'up_t',
		'class' => 'el'
	);

	$options[] = array(
		'name' => '',
		'desc' => '年份',
		'id' => 'year_n',
		'class' => 'tw3',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '月份',
		'id' => 'mon_n',
		'class' => 'tw3',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '分类ID',
		'id' => 'cat_up_n',
		'class' => 'tw3',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '用文章标签作为关键词添加内链',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '启用',
		'id' => 'tag_c',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'class' => '',
	);

	$options[] = array(
		'desc' => '链接数量',
		'id' => 'chain_n',
		'std' => '2',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '关键词',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '启用',
		'id' => 'keyword_link',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '<a href="' . home_url() . '/wp-admin/options-general.php?page=keywordlink">添加关键词</a>',
		'id' => 'keyword_link_settings'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '百度快速收录',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '启用',
		'id' => 'baidu_daily',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'desc' => '准入密钥',
		'id' => 'daily_token',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '百度普通收录',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '启用',
		'id' => 'baidu_link',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'desc' => '准入密钥',
		'id' => 'link_token',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '自定义分类法固定链接',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '启用并选择',
		'id' => 'begin_types_link',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'id' => 'begin_types',
		'class' => 'rr',
		'std' => 'link_id',
		'type' => 'radio',
		'options' => array(
			'link_id' => '文章ID.html',
			'link_name' => '文章名称.html',
		)
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '自定义分类法链接前缀',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '“公告”固定链接前缀',
		'id' => 'bull_url',
		'std' => 'bulletin',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '“公告分类”固定链接前缀',
		'id' => 'bull_cat_url',
		'std' => 'notice',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '“图片”固定链接前缀',
		'id' => 'img_url',
		'std' => 'picture',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '“图片分类”固定链接前缀',
		'id' => 'img_cat_url',
		'std' => 'gallery',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '“视频”固定链接前缀',
		'id' => 'video_url',
		'std' => 'video',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '“视频分类”固定链接前缀',
		'id' => 'video_cat_url',
		'std' => 'videos',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '“商品”固定链接前缀',
		'id' => 'sp_url',
		'std' => 'tao',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '“商品分类”固定链接前缀',
		'id' => 'sp_cat_url',
		'std' => 'taobao',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '“网址”固定链接前缀',
		'id' => 'favorites_url',
		'std' => 'sites',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '“网址分类”固定链接前缀',
		'id' => 'favorites_cat_url',
		'std' => 'favorites',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '“产品”固定链接前缀',
		'id' => 'show_url',
		'std' => 'show',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '“产品分类”固定链接前缀',
		'id' => 'show_cat_url',
		'std' => 'products',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '流量统计代码',
		'type' => 'groupstart'
	);

	$options[] = array(
		'name' => '异步，用于在页头添加异步统计代码',
		'desc' => '',
		'id' => 'tongji_h',
		'std' => '',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => '同步，用于在页脚添加同步统计代码',
		'desc' => '',
		'id' => 'tongji_f',
		'std' => '',
		'type' => 'textarea'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$wp_editor_settings = array(
		'quicktags' => 1,
		'tinymce' => 1,
		'media_buttons' => 1,
		'textarea_rows' => 5,
		'tinymce' => array( 'plugins' => 'wordpress, wplink, textcolor, charmap' )
	);

	$options[] = array(
		'name' => '页脚信息',
		'type' => 'groupstart'
	);

	$options[] = array(
		'id' => 'footer_inf_t',
		'std' => '<p style="text-align: center;">Copyright &copy;&nbsp;&nbsp;站点名称&nbsp;&nbsp;版权所有.</p><p style="text-align: center;">主题选项→SEO选项卡，最下面修改页脚信息</p><p style="text-align: center;"><a title="主题设计：知更鸟" href="http://zmingcx.com/" target="_blank" rel="external nofollow"><img src="' . get_template_directory_uri() . '/img/logo.png" alt="Begin主题" width="120" height="27" /></a></p>',
		'type' => 'editor',
		'settings' => $wp_editor_settings
	);

	$options[] = array(
		'desc' => '回行显示，选择文字“居中对齐”',
		'class' => 'el'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '域名备案信息',
		'class' => 'bel',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '公网安备小图标',
		'id' => 'wb_img',
		'class' => 'chl5',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '域名备案小图标',
		'id' => 'yb_img',
		'class' => 'chl5',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '公网安备号',
		'id' => 'wb_info',
		'class' => 'chl5',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '域名备案号',
		'id' => 'yb_info',
		'class' => 'chl5',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '公网安备链接',
		'id' => 'wb_url',
		'class' => 'chl5',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '工信部链接',
		'id' => 'yb_url',
		'class' => 'chl5',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	// 广告设置

	$options[] = array(
		'name' => '广告位',
		'type' => 'heading'
	);

	$options[] = array(
		'name' => '头部通栏广告位',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'ad_h_t',
		'class' => 'chl',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'desc' => '只在首页显示',
		'id' => 'ad_h_t_h',
		'class' => 'chl',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'class' => '',
	);

	$options[] = array(
		'name' => '输入头部通栏广告代码（非移动端）',
		'desc' => '宽度小于等于 1080px',
		'id' => 'ad_ht_c',
		'class' => '',
		'std' => '<a href="#" target="_blank"><img src="https://s2.loli.net/2021/12/05/7J38OVbmzZHlSjK.jpg" alt="广告也精彩" /></a>',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => '输入头部通栏广告代码（用于移动端）',
		'desc' => '',
		'id' => 'ad_ht_m',
		'std' => '<a href="#" target="_blank"><img src="https://s2.loli.net/2021/12/05/7J38OVbmzZHlSjK.jpg" alt="广告也精彩" /></a>',
		'type' => 'textarea'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '头部两栏广告位',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'ad_h',
		'class' => 'chl',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'desc' => '只在首页显示',
		'id' => 'ad_h_h',
		'class' => 'chl',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'class' => '',
	);

	$options[] = array(
		'name' => '输入头部左侧广告代码（非移动端）',
		'desc' => '宽度小于等于 758px',
		'id' => 'ad_h_c',
		'std' => '<a href="#" target="_blank"><img src="https://s2.loli.net/2021/12/05/7J38OVbmzZHlSjK.jpg" alt="广告也精彩" /></a>',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => '输入头部左侧广告代码（用于移动端）',
		'desc' => '',
		'id' => 'ad_h_c_m',
		'std' => '<a href="#" target="_blank"><img src="https://s2.loli.net/2021/12/05/7J38OVbmzZHlSjK.jpg" alt="广告也精彩" /></a>',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => '输入头部右侧广告代码（非移动端）',
		'desc' => '宽度小于等于 307px',
		'id' => 'ad_h_cr',
		'std' => '<a href="#" target="_blank"><img src="https://s2.loli.net/2021/12/05/2uTMHlGZOLPYCoQ.jpg" alt="广告也精彩" /></a>',
		'type' => 'textarea'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '文章列表广告位',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'ad_a',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '输入文章列表广告代码（非移动端）',
		'desc' => '宽度小于等于 760px',
		'id' => 'ad_a_c',
		'std' => '<a href="#" target="_blank"><img src="https://s2.loli.net/2021/12/05/7J38OVbmzZHlSjK.jpg" alt="广告也精彩" /></a>',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => '输入文章列表广告代码（用于移动端）',
		'desc' => '',
		'id' => 'ad_a_c_m',
		'std' => '<a href="#" target="_blank"><img src="https://s2.loli.net/2021/12/05/7J38OVbmzZHlSjK.jpg" alt="广告也精彩" /></a>',
		'type' => 'textarea'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '正文标题广告位',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'ad_s',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '输入正文标题广告代码（非移动端）',
		'desc' => '宽度小于等于 740px',
		'id' => 'ad_s_c',
		'std' => '<a href="#" target="_blank"><img src="https://s2.loli.net/2021/12/05/7J38OVbmzZHlSjK.jpg" alt="广告也精彩" /></a>',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => '输入正文标题广告代码（用于移动端）',
		'desc' => '',
		'id' => 'ad_s_c_m',
		'std' => '<a href="#" target="_blank"><img src="https://s2.loli.net/2021/12/05/7J38OVbmzZHlSjK.jpg" alt="广告也精彩" /></a>',
		'type' => 'textarea'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '正文底部广告位',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'ad_s_b',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '输入正文底部广告代码（非移动端）',
		'desc' => '宽度小于等于 740px',
		'id' => 'ad_s_c_b',
		'std' => '<a href="#" target="_blank"><img src="https://s2.loli.net/2021/12/05/7J38OVbmzZHlSjK.jpg" alt="广告也精彩" /></a>',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => '输入正文底部广告代码（用于移动端）',
		'desc' => '',
		'id' => 'ad_s_c_b_m',
		'std' => '<a href="#" target="_blank"><img src="https://s2.loli.net/2021/12/05/7J38OVbmzZHlSjK.jpg" alt="广告也精彩" /></a>',
		'type' => 'textarea'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '评论上方广告位',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '显示',
		'id' => 'ad_c',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '输入评论上方广告代码（非移动端）',
		'desc' => '宽度小于等于 760px',
		'id' => 'ad_c_c',
		'std' => '<a href="#" target="_blank"><img src="https://s2.loli.net/2021/12/05/7J38OVbmzZHlSjK.jpg" alt="广告也精彩" /></a>',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => '输入评论上方广告代码（用于移动端）',
		'desc' => '',
		'id' => 'ad_c_c_m',
		'std' => '<a href="#" target="_blank"><img src="https://s2.loli.net/2021/12/05/7J38OVbmzZHlSjK.jpg" alt="广告也精彩" /></a>',
		'type' => 'textarea'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '正文短代码广告位',
		'type' => 'groupstart'
	);

	$options[] = array(
		'name' => '输入正文短代码广告代码（非移动端）',
		'desc' => '宽度小于等于 740px',
		'id' => 'ad_s_z',
		'std' => '<a href="#" target="_blank"><img src="https://s2.loli.net/2021/12/05/7J38OVbmzZHlSjK.jpg" alt="广告也精彩" /></a>',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => '输入正文短代码广告代码（用于移动端）',
		'desc' => '',
		'id' => 'ad_s_z_m',
		'std' => '<a href="#" target="_blank"><img src="https://s2.loli.net/2021/12/05/7J38OVbmzZHlSjK.jpg" alt="广告也精彩" /></a>',
		'type' => 'textarea'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '文件下载页面广告代码',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '',
		'id' => 'ad_down',
		'std' => '<a href="#" target="_blank"><img src="https://s2.loli.net/2021/12/05/7J38OVbmzZHlSjK.jpg" alt="广告也精彩" /></a>',
		'type' => 'textarea'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '弹窗下载广告位',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '',
		'id' => 'ad_down_file',
		'std' => '<a href="#" target="_blank"><img src="https://s2.loli.net/2021/12/05/7J38OVbmzZHlSjK.jpg" alt="广告也精彩" /></a>',
		'type' => 'textarea'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	$options[] = array(
		'name' => '需要在页头<head></head>之间加载的广告代码',
		'class' => 'bel',
		'type' => 'groupstart'
	);

	$options[] = array(
		'desc' => '',
		'id' => 'ad_t',
		'std' => '',
		'type' => 'textarea'
	);

	$options[] = array(
		'type' => 'groupend'
	);

	// 定制CSS

	$options[] = array(
		'name' => '定制风格',
		'type' => 'heading'
	);

	$options[] = array(
		'name' => '页面宽度',
		'desc' => 'px 固定宽度（默认1122）',
		'id' => 'custom_width',
		'class' => '',
		 'std' => '',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => '',
		'desc' => '% 按百分比（小于99）',
		'id' => 'adapt_width',
		 'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'left_explain',
		'class' => 'el',
		'desc' => '不使用自定义宽度请留空'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '文章列表无下边距',
		'id' => 'post_no_margin',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);
	
	$options[] = array(
		'name' => '',
		'desc' => '模块标题前装饰',
		'id' => 'title_i',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '彩色标题更多按钮',
		'id' => 'more_im',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '标题无背景色',
		'id' => 'fresh_no',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '移动端禁止缩放',
		'id' => 'mobile_viewport',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '动画特效',
		'id' => 'aos_scroll',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'id' => 'aos_data',
		'class' => 'rr',
		'std' => 'fade-up',
		'type' => 'radio',
		'options' => array(
			'fade-up' => '向上',
			'fade-in' => '渐显',
			'zoom-in' => '缩放',
		)
	);

	$options[] = array(
		'id' => 'clear'
	);


	$options[] = array(
		'name' => '颜色风格',
		'desc' => '选择自己喜欢的颜色，不使用自定义颜色清空即可',
		'id' => 'custom_color',
		'class' => 'el'
	);

	$options[] = array(
		'name' => '',
		'desc' => '一键换色',
		'id' => 'all_color',
		'std' => '',
		'type' => 'color'
	);

	$options[] = array(
		'name' => '',
		'desc' => '站点标题',
		'id' => 'blogname_color',
		'std' => '',
		'type' => 'color'
	);

	$options[] = array(
		'name' => '',
		'desc' => '副标题',
		'id' => 'blogdescription_color',
		'std' => '',
		'type' => 'color'
	);

	$options[] = array(
		'name' => '',
		'desc' => '超链接',
		'id' => 'link_color',
		'std' => '',
		'type' => 'color'
	);

	$options[] = array(
		'name' => '',
		'desc' => '菜单颜色',
		'id' => 'menu_color',
		'std' => '',
		'type' => 'color'
	);

	$options[] = array(
		'name' => '',
		'desc' => '按钮',
		'id' => 'button_color',
		'std' => '',
		'type' => 'color'
	);

	$options[] = array(
		'name' => '',
		'desc' => '分类名称',
		'id' => 'cat_color',
		'std' => '',
		'type' => 'color'
	);

	$options[] = array(
		'name' => '',
		'desc' => '幻灯',
		'id' => 'slider_color',
		'std' => '',
		'type' => 'color'
	);

	$options[] = array(
		'name' => '',
		'desc' => '正文H标签',
		'id' => 'h_color',
		'std' => '',
		'type' => 'color'
	);

	$options[] = array(
		'name' => '',
		'desc' => '搜索按钮',
		'id' => 's_color',
		'std' => '',
		'type' => 'color'
	);

	$options[] = array(
		'name' => '',
		'desc' => '分享按钮',
		'id' => 'z_color',
		'std' => '',
		'type' => 'color'
	);

	$options[] = array(
		'name' => '',
		'desc' => '',
		'id' => '',
		'class' => ''
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '自定义样式',
		'desc' => '样式代码示例',
		'id' => 'custom_css',
		'class' => '',
		'std' => '',
		'type' => 'textarea'
	);

	$options[] = array(
		'class' => 'el cod',
		'name' => '例1：顶部菜单改为渐变色：',
		'desc' => '.header-top {background: linear-gradient(to right, #ffecea, #c4e7f7, #ffecea, #c4e7f7, #ffecea);border-bottom: none;}'
	);

	$options[] = array(
		'class' => 'el cod',
		'name' => '例2：主菜单改为黑色：',
		'desc' => '#menu-container, .headroom--not-top .menu-glass {background: #323232 !important;}.nav-ace .down-menu > li > a, #menu-container .sf-arrows .sf-with-ul:after {color: #fff;}'
	);

	$options[] = array(
		'name' => '使用帮助',
		'type' => 'heading'
	);

	$options[] = array(
		'name' => '主题使用说明',
		'class' => 'el help_but',
		'desc' => '点击右侧<i></i>按钮，查看主题使用说明',
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '保存设置',
		'class' => 'el save_but',
		'desc' => '修改某个设置后，必须保存设置，右侧<i></i>按钮为保存，快捷键：Enter',
	);

	$options[] = array(
		'class' => 'el',
		'desc' => '有些设置启用后，需要保存两次设置才会生效，比如：文章归档和文章更新页面及站点地图等',
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '选项显示状态',
		'class' => 'el all_but',
		'desc' => '<strong>始终显示：</strong>勾选右上<i></i>选项， 保存设置之后，始终处于全部显示状态',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'class' => 'el expand_but',
		'desc' => '<strong>临时显示：</strong>点击右侧<i></i>按钮，临时处于全部显示或隐藏状态',
	);

	$options[] = array(
		'class' => 'el',
		'desc' => '<strong>单项显示：</strong>点击小标题，可以单个显示或隐藏',
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '快速定位选项',
		'class' => 'el',
		'desc' => '将选项设置为始终显示状态，利用浏览器的查找功能，输入关键字，快速定位到功能设置，快捷键：Ctrl+f',
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '分类/专题/文章/页面ID',
		'class' => 'el cat_but',
		'desc' => '<strong>分类和专题ID：</strong>点击右侧<i></i>查看',
	);

	$options[] = array(
		'class' => 'el',
		'desc' => '<strong>文章和页面ID：</strong>进入文章（页面）→所有文章（所有页面），查看ID',
	);

	return $options;
}