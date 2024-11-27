<?php if ( ! defined( 'ABSPATH' )  ) { die; }
if ( ! function_exists( 'zm_get_option' ) ) {
	function zm_get_option( $option = '', $default = null ) {
		$options = get_option( 'begin' );
		return ( isset( $options[$option] ) ) ? $options[$option] : $default;
	}
}

if ( ! zm_get_option( 'save_ajax' ) || ( zm_get_option( 'save_ajax' ) == 'ajax' ) ) {
	$save = true;
}
if ( zm_get_option( 'save_ajax' ) == 'normal' ) {
	$save = false;
}

$select_template = array('');
foreach ( $select_template as $select_template ) {
	$options_select_template['archive-default'] = "默认模板";
	$options_select_template['category-code-a'] = "Ajax图片布局";
	$options_select_template['category-code-b'] = "Ajax卡片布局";
	$options_select_template['category-code-c'] = "Ajax标题布局";
	$options_select_template['category-code-f'] = "Ajax标题列表";
	$options_select_template['category-code-e'] = "Ajax问答布局";
	$options_select_template['category-code-c'] = "Ajax标准布局";
	$options_select_template['category-img']    = "图片布局";
	$options_select_template['category-grid']   = "图片布局，单独设置大小";
	$options_select_template['category-img-s']  = "图片布局，有侧边栏";
	$options_select_template['category-list']   = "标题列表";
	$options_select_template['category-fall']   = "瀑布流";
	$options_select_template['category-square'] = "网格布局";
	$options_select_template['category-full']   = "通长缩略图";
}

$select_template_tag = array('');
foreach ( $select_template_tag as $select_template_tag ) {
	$options_select_template_tag['archive-default'] = "默认模板";
	$options_select_template_tag['category-img']    = "图片布局";
	$options_select_template_tag['category-grid']   = "图片布局，单独设置大小";
	$options_select_template_tag['category-img-s']  = "图片布局，有侧边栏";
	$options_select_template_tag['category-list']   = "标题列表";
	$options_select_template_tag['category-fall']   = "瀑布流";
	$options_select_template_tag['category-square'] = "网格布局";
	$options_select_template_tag['category-full']   = "通长缩略图";
}

$imgdefault  = get_template_directory_uri() . '/img/default';
$imgpath     = get_template_directory_uri() . '/img';
$bloghome    = home_url( '/' );
$bloglogin   = home_url( '/' ).'wp-login.php';
$qq_auth     = home_url( '/' ).'wp-content/themes/begin/inc/social/qq-auth.php';
$weibo_auth  = home_url( '/' ).'wp-content/themes/begin/inc/social/sina-auth.php';
$weixin_auth = home_url( '/' );
$selectemail = get_option( 'admin_email' );
$mid         = '多个ID用英文半角逗号","隔开';
$idcat       = '输入分类ID，多个ID用英文半角逗号","隔开';
$anh         = '<span class="after-perch">留空则以后台阅读设置为准</span>';
$shortcode_help = '
<span><strong>代码示例</strong></span>[be_ajax_post style="grid" column="2" terms="1,2,3" posts_per_page="4"]<br />
<span><strong>示例说明</strong></span>卡片模式，2栏，分别调用ID为1,2,3的分类文章，每页显示4篇<br /><br />
<span><strong>可选参数</strong></span>可组合使用，多个参数用一个半角空格隔开，不能有多余的空格，可重复添加多个短代码<br />
<span><strong>图片模式</strong></span>[be_ajax_post]<br />
<span><strong>卡片模式</strong></span>[be_ajax_post style="grid"]<br />
<span><strong>标题模式</strong></span>[be_ajax_post style="title"]<br />
<span><strong>标题列表</strong></span>[be_ajax_post style="list"]<br />
<span><strong>问答模式</strong></span>[be_ajax_post style="qa"]<br />
<span><strong>标准模式</strong></span>[be_ajax_post style="default"]<br />
<span><strong>调用标签</strong></span>[be_ajax_post column="4" cat="34,39" tags="tag"]<br />
<span><strong>分栏</strong></span>[be_ajax_post column="4"]<br />
<span><strong>可选分栏数</strong></span>图片模式 4 5 6&nbsp;/&nbsp;卡片与标题模式 1 2 3 4&nbsp;/&nbsp;标题列表仅1栏<br />
<span><strong>无全部文章按钮</strong></span>[be_ajax_post btn_all="no"]<br />
<span><strong>无选择按钮</strong></span>[be_ajax_post btn="no"]<br />
<span><strong>显示首页幻灯</strong></span>[be_ajax_post slider="1"]<br />
<span><strong>调用指定分类</strong></span>[be_ajax_post terms="1,2,3,4"]<br />
<span><strong>每页4篇文章</strong></span>[be_ajax_post posts_per_page="4"]<br />
<span><strong>图片模式缩略图</strong></span>[be_ajax_post img="1"]<br />
<span><strong>随机排序</strong></span>[be_ajax_post orderby="rand"]<br />
<span><strong>按发表日期排序</strong></span>[be_ajax_post orderby="date" order="DESC"]<br />
<span><strong>按更新日期排序</strong></span>[be_ajax_post orderby="modified" order="DESC"]<br />
<span><strong>按评论数排序</strong></span>[be_ajax_post orderby="comment_count" order="DESC"]<br />
<span><strong>按浏览量排序</strong></span>[be_ajax_post meta_key="views" orderby="meta_value_num" order="DESC"]<br />
<span><strong>无限加载按钮</strong></span>[be_ajax_post more="more"]<br />
<span><strong>无限滚动加载</strong></span>[be_ajax_post more="more" infinite="true"]<br /><br />
<strong>可在文章、页面和“增强文本”小工具中添加上述短代码</strong><br />
<strong>在“增强文本”小工具中，可以在小工具“CSS类”中添加“apc”或者“nobg”让小工具无背景色</strong>';

$test_array = array(
	''  => '中',
	't' => '上',
	'b' => '下',
	'l' => '左',
	'r' => '右'
);

$rand_link = array(
	'rating' => '正常',
	'rand'   => '随机'
);

$ajax_orderby = array(
	'date'          => '发表日期',
	'modified'      => '最后更新',
	'comment_count' => '评论数',
	'views'         => '浏览量'
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

$fl24568 = array(
	'2' => '两栏',
	'4' => '四栏',
	'5' => '五栏',
	'6' => '六栏',
	'8' => '八栏'
);

$fl2456 = array(
	'2' => '两栏',
	'4' => '四栏',
	'5' => '五栏',
	'6' => '六栏',
);

$fl245 = array(
	'2' => '两栏',
	'4' => '四栏',
	'5' => '五栏',
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

$fl3456 = array(
	'3' => '3栏',
	'4' => '4栏',
	'5' => '5栏',
	'6' => '6栏'
);

$fl23456 = array(
	'2' => '2栏',
	'3' => '3栏',
	'4' => '4栏',
	'5' => '5栏',
	'6' => '6栏'
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

$prefix = 'begin';

CSF::createOptions( $prefix, array(
	'framework_title'         => '主题选项',
	'framework_class'         => 'be-box',

	'menu_title'              => '主题选项',
	'menu_slug'               => 'begin-options',
	'menu_type'               => 'submenu',
	'menu_capability'         => 'manage_options',
	'menu_icon'               => null,
	'menu_position'           => null,
	'menu_hidden'             => false,
	'menu_parent'             => 'themes.php',

	'show_bar_menu'           => true,
	'show_sub_menu'           => false,
	'show_in_network'         => true,
	'show_in_customizer'      => false,

	'show_search'             => false,
	'show_reset_all'          => true,
	'show_reset_section'      => true,
	'show_footer'             => true,
	'show_all_options'        => true,
	'show_form_warning'       => true,
	'sticky_header'           => true,
	'save_defaults'           => true,
	'ajax_save'               => $save,

	'admin_bar_menu_icon'     => 'cx cx-begin',
	'admin_bar_menu_priority' => 80,

	'footer_text'             => '',
	'footer_after'            => '',
	'footer_credit'           => '',

	'database'                => '',
	'transient_time'          => 0,

	'contextual_help'         => array(),
	'contextual_help_sidebar' => '',

	'enqueue_webfont'         => true,
	'async_webfont'           => false,

	'output_css'              => true,

	'nav'                     => 'normal',
	'theme'                   => 'be',
	'class'                   => '',

	'defaults'                => array(),

));

CSF::createSection( $prefix, array(
	'title'       => '操作说明',
	'icon'  => 'dashicons dashicons-buddicons-groups',
	'description' => '',
	'fields'      => array(

		array(
			'id'      => 'save_ajax',
			'type'    => 'radio',
			'title'   => '保存模式',
			'inline'  => true,
			'options' => array(
				'ajax'    => 'Ajax无刷新',
				'normal'  => '正常模式',
			),
			'default' => 'ajax',
		),

		array(
			'class'    => 'be-button-url be-button-help-url',
			'type'     => 'subheading',
			'title'    => '使用文档',
			'content'  => '<span class="button-primary"><a href="https://zmingcx.com/begin-guide.html" rel="external nofollow" target="_blank">查看文档</a></span>',
		),

		array(
			'class'    => 'be-home-help',
			'title'   => '温馨提示',
			'type'    => 'content',
			'content' => '主题设置开关较多，不要把所有开关都打开，有些功能您并不一定能用到',
		),

		array(
			'class'    => 'be-home-help',
			'title'   => '快速定位设置项',
			'type'    => 'content',
			'content' => '点击左上&nbsp;&nbsp;<i class="beico dashicons dashicons-ellipsis"></i>展开所有设置按钮，在同一个页面显示所有设置，利用浏览器搜索功能（Ctrl+f），输入关键字定位到设置项',
		),

		array(
			'class'    => 'be-home-help',
			'title'   => '左上按钮',
			'type'    => 'content',
			'content' => '<i class="beico dashicons dashicons-ellipsis"></i>展开所有设置选项',
		),

		array(
			'class'    => 'be-home-help',
			'title'   => '左侧按钮',
			'type'    => 'content',
			'content' => '<i class="dashicons dashicons-plus-alt2"></i>展开所有菜单',
		),

		array(
			'class'    => 'be-home-help',
			'title'   => '右上按钮',
			'type'    => 'content',
			'content' => '<i class="dashicons dashicons-update-alt"></i>保存设置',
		),

		array(
			'class'    => 'be-home-help',
			'title'   => '右侧按钮',
			'type'    => 'content',
			'content' => '<i class="be be-sort"></i>查看分类及专题页面 ID',
		),

		array(
			'class'    => 'be-home-help',
			'title'   => '右下按钮',
			'type'    => 'content',
			'content' => '<i class="to-down-up"></i>返回顶部及转至底部',
		),
	)
));

CSF::createSection( $prefix, array(
	'id'     => 'home_setting',
	'title'  => '首页设置',
	'icon'   => 'dashicons dashicons-admin-home',
) );

// 首页选择
CSF::createSection( $prefix, array(
	'parent'      => 'home_setting',
	'title'       => '首页布局',
	'icon'        => '',
	'description' => '选择一个首页布局',
	'fields'      => array(

		array(
			'id'      => 'layout',
			'type'    => 'radio',
			'title'   => '首页布局选择',
			'options' => array(
				'blog'   => '博客布局',
				'img'    => '图片布局',
				'grid'   => '分类图片',
				'cms'    => '杂志布局',
				'group'  => '公司主页',
			),
			'default' => 'blog',
		),

		array(
			'title'   => '页面使用首页布局',
			'type'    => 'content',
			'content' => '新建页面 → 右侧页面属性面板 → 模板，选择对应的模板发表即可。',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'home_setting',
	'title'       => '博客布局',
	'icon'        => '',
	'description' => '首页博客布局设置',
	'fields'      => array(

		array(
			'id'       => 'blog_top',
			'type'     => 'switcher',
			'title'    => '推荐文章',
		),

		array(
			'id'       => 'blog_top_n',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'number',
			'title'    => '显示篇数',
			'default'  => 4,
		),

		array(
			'id'       => 'blog_special',
			'type'     => 'switcher',
			'title'    => '专题',
		),

		array(
			'id'       => 'blog_special_list',
			'type'     => 'switcher',
			'title'    => '专题列表',
		),

		array(
			'id'       => 'blog_cat_cover',
			'type'     => 'switcher',
			'title'    => '分类封面',
		),

		array(
			'id'       => 'blog_ajax',
			'type'     => 'switcher',
			'title'    => 'Ajax加载文章列表',
			'label'    => '',
		),

		array(
			'id'       => 'blog_ajax_n',
			'class'    => 'be-child-item',
			'type'     => 'number',
			'title'    => '每页篇数',
			'default'  => '15',
			'after'    => $anh,
		),

		array(
			'id'      => 'blog_ajax_id',
			'class'   => 'be-child-item',
			'type'    => 'checkbox',
			'title'   => '选择分类',
			'inline'  => true,
			'options' => 'categories',
			'query_args' => array(
				'orderby'  => 'ID',
				'order'    => 'ASC',
			),
		),

		array(
			'id'      => 'blog_ajax_cat_style',
			'class'   => 'be-child-item',
			'type'    => 'radio',
			'title'   => '样式',
			'inline'  => true,
			'options' => array(
				'default' => '标准样式',
				'grid'    => '卡片样式',
			),
			'default' => 'default',
		),

		array(
			'id'      => 'blog_ajax_cat_btn',
			'class'   => 'be-child-item',
			'type'    => 'radio',
			'title'   => '分类按钮',
			'inline'  => true,
			'options' => array(
				'yes'  => '显示',
				'no'   => '不显示',
			),
			'default' => 'yes',
		),

		array(
			'id'      => 'blog_ajax_nav_btn',
			'class'   => 'be-child-item',
			'type'    => 'radio',
			'title'   => '翻页模式',
			'inline'  => true,
			'options' => array(
				'true'   => '数字翻页',
				'more'   => '更多按钮',
			),
			'default' => 'true',
		),

		array(
			'id'      => 'blog_ajax_infinite',
			'class'   => 'be-child-item be-child-last-item',
			'type'    => 'radio',
			'title'   => '更多按钮滚动加载',
			'inline'  => true,
			'options' => array(
				'false'  => '否',
				'true'   => '是',
			),
			'default' => 'false',
		),

		array(
			'type'    => 'content',
			'title'   => '以下为非Ajax加载模式设置',
		),

		array(
			'id'      => 'blog_not_cat',
			'type'    => 'checkbox',
			'title'   => '排除的分类文章',
			'inline'  => true,
			'options' => 'categories',
			'query_args' => array(
				'orderby'  => 'ID',
				'order'    => 'ASC',
			),
		),

	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'home_setting',
	'title'       => '图片布局',
	'icon'        => '',
	'description' => '首页图片布局设置',
	'fields'      => array(

		array(
			'id'       => 'img_top',
			'type'     => 'switcher',
			'title'    => '推荐文章',
		),

		array(
			'id'       => 'img_top_n',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'number',
			'title'    => '显示篇数',
			'default'  => 4,
		),

		array(
			'id'       => 'img_special',
			'type'     => 'switcher',
			'title'    => '专题',
		),

		array(
			'id'       => 'img_cat_cover',
			'type'     => 'switcher',
			'title'    => '分类封面',
		),

		array(
			'id'       => 'img_ajax',
			'type'     => 'switcher',
			'title'    => 'Ajax加载文章列表',
			'label'    => '',
		),

		array(
			'id'       => 'img_ajax_n',
			'class'    => 'be-child-item',
			'type'     => 'number',
			'title'    => '每页篇数',
			'default'  => '15',
			'after'    => $anh,
		),

		array(
			'id'      => 'img_ajax_id',
			'class'   => 'be-child-item',
			'type'    => 'checkbox',
			'title'   => '选择分类',
			'inline'  => true,
			'options' => 'categories',
			'query_args' => array(
				'orderby'  => 'ID',
				'order'    => 'ASC',
			),
		),

		array(
			'id'      => 'img_ajax_cat_btn',
			'class'   => 'be-child-item',
			'type'    => 'radio',
			'title'   => '分类按钮',
			'inline'  => true,
			'options' => array(
				'yes'   => '显示',
				'no'   => '不显示',
			),
			'default' => 'yes',
		),

		array(
			'id'      => 'img_ajax_feature',
			'class'   => 'be-child-item',
			'type'    => 'radio',
			'title'   => '缩略图模式',
			'inline'  => true,
			'options' => array(
				'0'   => '标准',
				'1'   => '图片',
			),
			'default' => '0',
		),

		array(
			'id'      => 'img_ajax_f',
			'class'    => 'be-child-item',
			'type'    => 'radio',
			'title'   => '分栏',
			'inline'  => true,
			'options' => $fl456,
			'default' => '5',
		),

		array(
			'id'      => 'img_ajax_nav_btn',
			'class'   => 'be-child-item',
			'type'    => 'radio',
			'title'   => '翻页模式',
			'inline'  => true,
			'options' => array(
				'true'   => '数字翻页',
				'more'   => '更多按钮',
			),
			'default' => 'true',
		),

		array(
			'id'      => 'img_ajax_infinite',
			'class'   => 'be-child-item be-child-last-item',
			'type'    => 'radio',
			'title'   => '更多按钮滚动加载',
			'inline'  => true,
			'options' => array(
				'false'  => '否',
				'true'   => '是',
			),
			'default' => 'false',
		),

		array(
			'type'    => 'content',
			'title'   => '以下为非Ajax加载模式设置',
		),

		array(
			'id'      => 'grid_not_cat',
			'type'    => 'checkbox',
			'title'   => '排除的分类文章',
			'inline'  => true,
			'options' => 'categories',
			'query_args' => array(
				'orderby'  => 'ID',
				'order'    => 'ASC',
			),
		),

		array(
			'id'       => 'grid_fall',
			'type'     => 'switcher',
			'title'    => '使用瀑布流',
			'label'    => '',
		),

		array(
			'id'      => 'img_f',
			'type'    => 'radio',
			'title'   => '图片布局分栏',
			'inline'  => true,
			'options' => $fl456,
			'default' => '4',
		),

		array(
			'id'      => 'img_top_f',
			'type'    => 'radio',
			'title'   => '最新文章分栏',
			'inline'  => true,
			'options' => $fl456,
			'default' => '4',
		),

		array(
			'id'       => 'hide_box',
			'type'     => 'switcher',
			'title'    => '图片布局显示摘要',
		),

	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'home_setting',
	'title'       => '首页幻灯',
	'icon'        => '',
	'description' => '调用方法：编辑文章在下面“将文章添加到幻灯”面板中添加图片链接，即可将该文章显示在幻灯中',
	'fields'      => array(

		array(
			'id'       => 'slider',
			'type'     => 'switcher',
			'title'    => '幻灯',
			'default'  => true,
		),

		array(
			'id'       => 'slider_n',
			'type'     => 'number',
			'title'    => '篇数',
			'default'  => 2,
		),

		array(
			'id'       => 'owl_time',
			'type'     => 'number',
			'after'    => '<span class="after-perch">默认4000毫秒</span>',
			'title'    => '间隔',
			'default'  => 4000,
		),

		array(
			'id'       => 'slide_progress',
			'type'     => 'switcher',
			'title'    => '进度条',
			'label'    => '',
		),

		array(
			'id'       => 'show_img_crop',
			'type'     => 'switcher',
			'title'    => '自动裁剪图片',
			'label'    => '',
		),

		array(
			'id'       => 'slider_edit',
			'type'     => 'switcher',
			'title'    => '编辑时打开',
			'label'    => '',
		),

		array(
			'id'       => 'slide_post',
			'class'    => 'be-parent-item',
			'type'     => 'switcher',
			'title'    => '右侧模块',
			'label'    => '',
		),

		array(
			'id'       => 'slide_post_m',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '移动端显示',
			'label'    => '',
			'default'  => true,
		),

		array(
			'id'       => 'slide_post_id',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'text',
			'title'    => '输入文章ID',
			'subtitle' => '',
			'before'   => '',
			'after'    => '输入两个文章ID，用英文半角逗号","隔开',
		),

		array(
			'id'       => 'show_slider_video',
			'class'    => 'be-parent-item',
			'type'     => 'switcher',
			'title'    => '仅显示一个视频',
			'label'    => '',
		),

		array(
			'id'       => 'show_slider_video_url',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'upload',
			'title'    => 'MP4视频',
		),

		array(
			'id'       => 'slider_only_img',
			'class'    => 'be-parent-item',
			'type'     => 'switcher',
			'title'    => '仅显示一张图片',
			'label'    => '',
		),

		array(
			'id'       => 'show_slider_img',
			'class'    => 'be-child-item',
			'type'     => 'upload',
			'title'    => '图片',
			'default'  => $imgdefault . '/options/1200.jpg',
			'preview'  => true,
		),

		array(
			'id'       => 'show_slider_img_url',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'text',
			'title'    => '图片链接到',
			'subtitle' => '',
			'before'   => '',
			'after'    => '点击图片跳转的地址',
		),

	//end
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'home_setting',
	'title'       => '文章排序',
	'icon'        => '',
	'description' => '在首页博客、图片布局及分类归档页面，显示文章排序按钮',
	'fields'      => array(

		array(
			'id'       => 'order_btu',
			'type'     => 'switcher',
			'title'    => '首页排序按钮',
			'label'    => '',
		),

		array(
			'id'       => 'cat_order_btu',
			'type'     => 'switcher',
			'title'    => '归档页排序按钮',
			'label'    => '',
		),

		array(
			'title'    => '选择按钮',
			'class'    => 'be-parent-title',
			'type'     => 'subheading',
		),

		array(
			'id'       => 'order_date',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '发表',
			'label'    => '按文章发表日期排序',
			'default'  => true,
		),

		array(
			'id'       => 'order_modified',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '更新',
			'label'    => '按文章最后更新日期排序',
			'default'  => true,
		),

		array(
			'id'       => 'order_rand',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '随机',
			'label'    => '随机排序',
			'default'  => true,
		),

		array(
			'id'       => 'order_commented',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '热评',
			'label'    => '按文章评论数排序',
			'default'  => true,
		),

		array(
			'id'       => 'order_views',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '热门',
			'label'    => '按文章浏览量排序',
			'default'  => true,
		),

		array(
			'id'       => 'order_like',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '点赞',
			'label'    => '按文章点赞数排序',
			'default'  => true,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'home_setting',
	'title'       => '专题专栏',
	'icon'        => '',
	'description' => '在首页显示专题专栏封面及列表',
	'fields'      => array(

		array(
			'type'     => 'content',
			'title'    => '专题专栏封面',
		),

		array(
			'id'       => 'code_special_id',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '输入专栏ID',
			'after'    => $mid,
		),

		array(
			'id'      => 'special_f',
			'class'    => 'be-child-item be-child-last-item',
			'type'    => 'radio',
			'title'   => '分栏',
			'inline'  => true,
			'options' => $fl456,
			'default' => '4',
		),

		array(
			'id'       => 'blog_special_id',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '输入专题页面ID',
			'after'    => $mid,
		),

		array(
			'type'     => 'content',
			'title'    => '专题专栏列表',
		),

		array(
			'id'       => 'code_special_list_id',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '输入专栏ID',
			'after'    => $mid,
		),

		array(
			'id'       => 'blog_special_list_id',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '输入专题页面ID',
			'after'    => $mid,
		),

		array(
			'id'          => 'column_url',
			'type'        => 'select',
			'title'       => '全部专栏',
			'placeholder' => '选择页面',
			'options'     => 'pages',
			'after'       => '用于专栏面包屑导航前缀链接',
			'query_args'  => array(
				'posts_per_page' => -1
			)
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'home_setting',
	'title'       => '首页分类封面',
	'icon'        => '',
	'description' => '在首页显示分类封面',
	'fields'      => array(

		array(
			'id'       => 'single_cover',
			'type'     => 'switcher',
			'title'    => '同时显示在正文页面顶部',
		),

		array(
			'id'       => 'cat_cover_id',
			'type'     => 'text',
			'title'    => '输入分类ID',
			'after'    => $mid,
		),

		array(
			'id'      => 'cat_rec_m',
			'type'    => 'radio',
			'title'   => '模式选择',
			'inline'  => true,
			'options' => array(
				'cat_rec_ico'   => '图标',
				'cat_rec_img'   => '图片',
			),
			'default' => 'cat_rec_ico',
		),

		array(
			'id'      => 'cover_img_f',
			'type'    => 'radio',
			'title'   => '分栏',
			'inline'  => true,
			'options' => $cover234,
			'default' => '4',
		),

		array(
			'id'       => 'cat_tag_cover',
			'type'     => 'switcher',
			'title'    => '调用标签',
		),

		array(
			'id'       => 'cat_cover_tag_id',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '输入标签ID',
			'after'    => $mid,
		),

	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'home_setting',
	'title'       => '首页其它设置',
	'icon'        => '',
	'description' => '首页多条件筛选等',
	'fields'      => array(

		array(
			'id'       => 'blank',
			'type'     => 'switcher',
			'title'    => '首页新窗口或标签打开链接',
		),

		array(
			'id'       => 'mobile_home_url',
			'type'     => 'text',
			'title'    => '移动端首页显示的页面',
			'after'    => '输入链接地址，不使用请留空',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'home_setting',
	'title'       => '首页页脚链接',
	'icon'        => '',
	'description' => '在首页页脚显示友情链接',
	'fields'      => array(

		array(
			'id'       => 'footer_link',
			'type'     => 'switcher',
			'title'    => '首页页脚链接',
		),

		array(
			'id'       => 'link_f_cat',
			'type'     => 'text',
			'title'    => '链接分类',
			'after'    => '可以输入链接分类ID，显示特定的链接在首页，留空则显示全部链接',
		),

		array(
			'id'       => 'home_link_ico',
			'type'     => 'switcher',
			'title'    => '显示网站 Favicon 图标',
		),

		array(
			'id'       => 'footer_img',
			'type'     => 'switcher',
			'title'    => '图片链接',
		),

		array(
			'id'       => 'footer_link_no',
			'type'     => 'switcher',
			'title'    => '移动端不显示',
		),

		array(
			'id'          => 'link_url',
			'type'        => 'select',
			'title'       => '更多链接按钮',
			'placeholder' => '选择页面',
			'options'     => 'pages',
			'query_args'  => array(
				'posts_per_page' => -1
			)
		),
	)
));

// 杂志开始
CSF::createSection( $prefix, array(
	'id'     => 'cms_setting',
	'title'  => '杂志布局',
	'icon'  => 'dashicons dashicons-welcome-widgets-menus',
) );

CSF::createSection( $prefix, array(
	'parent'      => 'cms_setting',
	'title'       => '幻灯显示模式',
	'icon'        => '',
	'description' => '选择幻灯显示模式',
	'fields'      => array(

		array(
			'id'      => 'slider_l',
			'type'    => 'radio',
			'title'   => '幻灯显示模式',
			'inline'  => true,
			'options' => array(
				'slider_n' => '标准',
				'slider_w' => '通栏',
			),
			'default' => 'slider_n',
		),
	)
));


CSF::createSection( $prefix, array(
	'parent'      => 'cms_setting',
	'title'       => '推荐文章',
	'icon'        => '',
	'description' => '调用方法：编辑文章在下面“将文章添加到”面板，勾选“首页推荐文章”，并更新发表',
	'fields'      => array(

		array(
			'id'       => 'cms_top',
			'type'     => 'switcher',
			'title'    => '推荐文章',
		),

		array(
			'id'       => 'cms_top_s',
			'class'    => 'be-child-item',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认：1</span>',
			'default'  => 1,
		),

		array(
			'id'       => 'cms_top_n',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'number',
			'title'    => '显示篇数',
			'default'  => 4,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'cms_setting',
	'title'       => '专题',
	'icon'        => '',
	'description' => '在首页设置中添加专题页面 ID',
	'fields'      => array(

		array(
			'id'       => 'cms_special',
			'type'     => 'switcher',
			'title'    => '专题',
		),

		array(
			'id'       => 'cms_special_s',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认2</span>',
			'default'  => 2,
		),

		array(
			'id'       => 'cms_special_list',
			'type'     => 'switcher',
			'title'    => '专题列表',
		),

		array(
			'id'       => 'cms_special_list_s',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认3</span>',
			'default'  => 3,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'cms_setting',
	'title'       => '分类封面',
	'icon'        => '',
	'description' => '在首页显示分类封面',
	'fields'      => array(

		array(
			'id'       => 'h_cat_cover',
			'type'     => 'switcher',
			'title'    => '首页分类封面',
		),
		array(
			'id'       => 'cms_cover_s',
			'type'     => 'number',
			'title'    => '排序',
			'default'  => 2,
			'after'    => '<span class="after-perch">默认：2</span>',
		),

	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'cms_setting',
	'title'       => '最新文章',
	'icon'        => '',
	'description' => '设置最新文章显示模式',
	'fields'      => array(

		array(
			'id'       => 'news',
			'type'     => 'switcher',
			'title'    => '最新文章',
			'default'  => true,
		),

		array(
			'id'       => 'news_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认4</span>',
			'default'  => 4,
		),

		array(
			'id'      => 'news_model',
			'type'    => 'radio',
			'title'   => '显示模式',
			'inline'  => true,
			'options' => array(
				'news_grid'   => '网格模式',
				'news_normal' => '标准模式',
			),
			'default' => 'news_grid',
		),

		array(
			'id'       => 'news_grid_sticky',
			'type'     => 'switcher',
			'title'    => '网格模式显示置顶文章',
			'default'  => true,
		),

		array(
			'id'       => 'news_n',
			'type'     => 'number',
			'title'    => '篇数',
			'default'  => 4,
		),

		array(
			'id'      => 'not_news_n',
			'type'    => 'checkbox',
			'title'   => '排除的分类',
			'inline'  => true,
			'options' => 'categories',
			'query_args' => array(
				'orderby'  => 'ID',
				'order'    => 'ASC',
			),
		),

		array(
			'id'       => 'post_img',
			'type'     => 'switcher',
			'title'    => '图文模块',
		),

		array(
			'id'       => 'post_img_n',
			'class'    => 'be-child-item',
			'type'     => 'number',
			'title'    => '篇数',
			'default'  => 4,
		),

		array(
			'class'    => 'be-child-item be-child-last-item',
			'title'   => '说明',
			'type'    => 'content',
			'content' => '位于最新文章模块中，编辑文章在下面“将文章添加到”面板中，勾选“杂志布局图文模块”，并更新文章',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'cms_setting',
	'title'       => '最新分类',
	'icon'        => '',
	'description' => 'AJAX分类最新文章',
	'fields'      => array(

		array(
			'id'       => 'cms_new_code_cat',
			'type'     => 'switcher',
			'title'    => '显示',
		),

		array(
			'id'       => 'cms_new_code_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认4</span>',
			'default'  => 4,
		),

		array(
			'id'      => 'cms_new_code_style',
			'type'    => 'radio',
			'title'   => '显示模式',
			'inline'  => true,
			'options' => array(
				'grid'    => '卡片',
				'title'   => '标题',
				'list'    => '列表',
				'default' => '标准',
			),
			'default' => 'grid',
		),

		array(
			'id'      => 'cms_new_code_f',
			'type'    => 'radio',
			'title'   => '分栏',
			'inline'  => true,
			'options' => $fl1234,
			'default' => '2',
		),

		array(
			'id'       => 'cms_new_code_n',
			'type'     => 'number',
			'title'    => '每页篇数',
			'default'  => 4,
		),

		array(
			'id'      => 'cms_new_prev_next_btn',
			'type'    => 'radio',
			'title'   => '上下页按钮',
			'inline'  => true,
			'options' => array(
				'true'   => '显示',
				'false'  => '不显示',
			),
			'default' => 'true',
		),

		array(
			'id'      => 'cms_new_code_no_cat_btn',
			'type'    => 'radio',
			'title'   => '分类按钮',
			'inline'  => true,
			'options' => array(
				'yes'   => '显示',
				'no'    => '不显示',
			),
			'default' => 'yes',
		),

		array(
			'id'      => 'cms_new_code_id',
			'type'    => 'checkbox',
			'title'   => '选择分类',
			'inline'  => true,
			'options' => 'categories',
			'query_args' => array(
				'orderby'  => 'ID',
				'order'    => 'ASC',
			),
		),

		array(
			'id'       => 'cms_new_code_post_img',
			'type'     => 'switcher',
			'title'    => '图文模块',
		),

		array(
			'class'    => 'be-child-item be-child-last-item',
			'title'   => '说明',
			'type'    => 'content',
			'content' => '位于最新文章模块中，编辑文章在下面“将文章添加到”面板中，勾选“杂志布局图文模块”，并更新文章',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'cms_setting',
	'title'       => '多条件筛选',
	'icon'        => '',
	'description' => '设置是否在杂志首页显示多条件筛选',
	'fields'      => array(

		array(
			'id'       => 'cms_filter_h',
			'type'     => 'switcher',
			'title'    => '多条件筛选',
		),

		array(
			'id'       => 'cms_filter_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认5</span>',
			'default'  => 5,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'cms_setting',
	'title'       => '首字母分类/标签',
	'icon'        => '',
	'description' => '以首字母分组排序显示分类/标签',
	'fields'      => array(

		array(
			'id'       => 'letter_show',
			'type'     => 'switcher',
			'title'    => '首字母分类/标签',
		),

		array(
			'id'       => 'letter_show_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认6</span>',
			'default'  => 6,
		),

		array(
			'id'       => 'letter_t',
			'type'     => 'text',
			'title'    => '标题文字',
			'default'  => '全部分类',
		),

		array(
			'id'      => 'letter_show_md',
			'type'    => 'radio',
			'title'   => '调用模式',
			'inline'  => true,
			'options' => array(
				'letter_cat' => '分类',
				'letter_tag' => '标签',
			),
			'default' => 'letter_cat',
		),

		array(
			'id'       => 'letter_exclude',
			'type'     => 'text',
			'title'    => '输入排除的分类/标签ID',
			'after'    => $mid,
		),

		array(
			'id'       => 'letter_hidden',
			'type'     => 'switcher',
			'title'    => '默认展开',
			'default'  => true,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'cms_setting',
	'title'       => '杂志单栏小工具',
	'icon'        => '',
	'description' => '杂志单栏小工具',
	'fields'      => array(

		array(
			'id'       => 'cms_widget_one',
			'type'     => 'switcher',
			'title'    => '杂志单栏小工具',
		),

		array(
			'id'       => 'cms_widget_one_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认7</span>',
			'default'  => 7,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'cms_setting',
	'title'       => '杂志菜单小工具',
	'icon'        => '',
	'description' => '杂志菜单小工具',
	'fields'      => array(

		array(
			'id'       => 'cms_two_menu',
			'type'     => 'switcher',
			'title'    => '杂志菜单小工具',
		),

		array(
			'id'       => 'cms_two_menu_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认8</span>',
			'default'  => 8,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'cms_setting',
	'title'       => 'AJAX分类',
	'icon'        => '',
	'description' => '设置AJAX加载分类',
	'fields'      => array(

		array(
			'id'       => 'cms_cat_tab',
			'type'     => 'switcher',
			'title'    => 'AJAX分类',
		),

		array(
			'id'       => 'cms_cat_tab_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认9</span>',
			'default'  => 9,
		),

		array(
			'id'       => 'cms_cat_tab_n',
			'type'     => 'number',
			'title'    => '篇数',
			'default'  => 10,
		),

		array(
			'id'       => 'cms_cat_tab_id',
			'type'     => 'text',
			'title'    => '输入分类ID',
			'after'    => $mid,
		),

		array(
			'id'       => 'cms_cat_tab_img',
			'type'     => 'switcher',
			'title'    => '缩略图',
			'default'  => true,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'cms_setting',
	'title'       => '图片模块',
	'icon'        => '',
	'description' => '图片模块',
	'fields'      => array(

		array(
			'id'       => 'picture_box',
			'type'     => 'switcher',
			'title'    => '图片模块',
		),

		array(
			'id'       => 'picture_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认10</span>',
			'default'  => 10,
		),

		array(
			'id'       => 'picture_n',
			'type'     => 'number',
			'title'    => '篇数',
			'default'  => 4,
		),

		array(
			'id'       => 'img_id',
			'type'     => 'text',
			'title'    => '正常文章分类',
			'after'    => '输入分类ID，多个分类用英文半角逗号","隔开，留空则不显示',
		),

		array(
			'id'       => 'picture_id',
			'type'     => 'text',
			'title'    => '调用图片分类',
			'after'    => '输入分类ID，多个分类用英文半角逗号","隔开，留空则不显示',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'cms_setting',
	'title'       => '杂志两栏小工具',
	'icon'        => '',
	'description' => '杂志两栏小工具',
	'fields'      => array(

		array(
			'id'       => 'cms_widget_two',
			'type'     => 'switcher',
			'title'    => '杂志两栏小工具',
		),

		array(
			'id'       => 'cms_widget_two_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认11</span>',
			'default'  => 11,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'cms_setting',
	'title'       => '单栏分类列表(5篇文章)',
	'icon'        => '',
	'description' => '单栏分类列表(5篇文章)',
	'fields'      => array(

		array(
			'id'       => 'cat_one_5',
			'type'     => 'switcher',
			'title'    => '单栏分类列表(5篇文章)',
		),

		array(
			'id'       => 'cat_one_5_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认12</span>',
			'default'  => 12,
		),

		array(
			'id'       => 'cat_one_5_id',
			'type'     => 'text',
			'title'    => '输入分类ID',
			'after'    => $mid,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'cms_setting',
	'title'       => '单栏分类列表(无缩略图)',
	'icon'        => '',
	'description' => '单栏分类列表(无缩略图)',
	'fields'      => array(

		array(
			'id'       => 'cat_one_on_img',
			'type'     => 'switcher',
			'title'    => '单栏分类列表(无缩略图)',
		),

		array(
			'id'       => 'cat_one_on_img_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认13</span>',
			'default'  => 13,
		),

		array(
			'id'       => 'cat_one_on_img_n',
			'type'     => 'number',
			'title'    => '篇数',
			'default'  => 4,
		),

		array(
			'id'       => 'cat_one_on_img_id',
			'type'     => 'text',
			'title'    => '输入分类ID',
			'after'    => $mid,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'cms_setting',
	'title'       => '单栏分类列表(10篇文章)',
	'icon'        => '',
	'description' => '单栏分类列表(10篇文章)',
	'fields'      => array(

		array(
			'id'       => 'cat_one_10',
			'type'     => 'switcher',
			'title'    => '单栏分类列表(10篇文章)',
		),

		array(
			'id'       => 'cat_one_10_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认14</span>',
			'default'  => 14,
		),

		array(
			'id'       => 'cat_one_10_id',
			'type'     => 'text',
			'title'    => '输入分类ID',
			'after'    => $mid,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'cms_setting',
	'title'       => '视频模块',
	'icon'        => '',
	'description' => '视频模块',
	'fields'      => array(

		array(
			'id'       => 'video_box',
			'type'     => 'switcher',
			'title'    => '视频模块',
		),

		array(
			'id'       => 'video_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认15</span>',
			'default'  => 15,
		),

		array(
			'id'       => 'video_n',
			'type'     => 'number',
			'title'    => '篇数',
			'default'  => 4,
		),

		array(
			'id'       => 'video',
			'type'     => 'switcher',
			'title'    => '调用视频日志',
		),

		array(
			'id'       => 'video_id',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'text',
			'title'    => '输入分类ID',
			'after'    => $mid,
		),

		array(
			'id'       => 'video_post',
			'type'     => 'switcher',
			'title'    => '调用分类文章',
		),

		array(
			'id'          => 'video_post_id',
			'class'    => 'be-child-item be-child-last-item',
			'type'        => 'select',
			'title'       => '选择一个分类',
			'placeholder' => '选择分类',
			'options'     => 'categories',
		),
	)
));


CSF::createSection( $prefix, array(
	'parent'      => 'cms_setting',
	'title'       => '混排分类列表',
	'icon'        => '',
	'description' => '混排分类列表',
	'fields'      => array(

		array(
			'id'       => 'cat_lead',
			'type'     => 'switcher',
			'title'    => '混排分类列表',
		),

		array(
			'id'       => 'cat_lead_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认16</span>',
			'default'  => 16,
		),

		array(
			'id'       => 'cat_lead_n',
			'type'     => 'number',
			'title'    => '篇数',
			'default'  => 4,
		),

		array(
			'id'       => 'no_lead_img',
			'type'     => 'switcher',
			'title'    => '显示小图',
			'default'  => true,
		),

		array(
			'id'       => 'cat_lead_id',
			'type'     => 'text',
			'title'    => '输入分类ID',
			'after'    => $mid,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'cms_setting',
	'title'       => '两栏分类列表',
	'icon'        => '',
	'description' => '两栏分类列表',
	'fields'      => array(

		array(
			'id'       => 'cat_small',
			'type'     => 'switcher',
			'title'    => '两栏分类列表',
		),

		array(
			'id'       => 'cat_small_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认17</span>',
			'default'  => 17,
		),

		array(
			'id'       => 'cat_small_n',
			'type'     => 'number',
			'title'    => '篇数',
			'default'  => 4,
		),

		array(
			'id'       => 'cat_small_z',
			'type'     => 'switcher',
			'title'    => '不显示第一篇摘要',
			'default'  => true,
		),

		array(
			'id'       => 'cat_small_img_no',
			'type'     => 'switcher',
			'title'    => '不显示缩略图',
		),

		array(
			'id'       => 'cat_small_id',
			'type'     => 'text',
			'title'    => '输入分类ID',
			'after'    => $mid,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'cms_setting',
	'title'       => 'Tab组合分类',
	'icon'        => '',
	'description' => 'AJAX调用分类文章',
	'fields'      => array(

	array(
			'id'       => 'cms_ajax_tabs',
			'type'     => 'switcher',
			'title'    => 'Tab组合分类',
			'label'    => '',
		),

		array(
			'id'       => 'tab_h_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认18</span>',
			'default'  => 18,
		),

		array(
			'id'       => 'tab_b_n',
			'type'     => 'number',
			'title'    => '显示篇数',
			'default'  => 8,
		),

		array(
			'id'       => 'home_tab_cat_id',
			'type'     => 'text',
			'title'    => '输入分类ID',
			'after'    => $mid,
		),

		array(
			'id'      => 'tabs_mode',
			'type'    => 'radio',
			'title'   => '显示模式',
			'inline'  => true,
			'options' => array(
				'imglist'  => '列表',
				'grid'     => '卡片',
				'default'  => '标准',
				'photo'    => '图片',
			),
			'default' => 'imglist',
		),

		array(
			'id'      => 'home_tab_code_f',
			'type'    => 'radio',
			'title'   => '分栏（卡片/图片）',
			'inline'  => true,
			'options' => $fl23456,
			'default' => '4',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'cms_setting',
	'title'       => '杂志侧边栏',
	'icon'        => '',
	'description' => '杂志侧边栏',
	'fields'      => array(

		array(
			'id'       => 'cms_no_s',
			'type'     => 'switcher',
			'title'    => '杂志侧边栏',
			'default'  => true,
		),

		array(
			'id'       => 'cms_slider_sticky',
			'type'     => 'switcher',
			'title'    => '侧边栏跟随滚动',
			'default'  => true,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'cms_setting',
	'title'       => '产品模块',
	'icon'        => '',
	'description' => '产品模块',
	'fields'      => array(

		array(
			'id'       => 'products_on',
			'type'     => 'switcher',
			'title'    => '产品模块',
		),

		array(
			'id'       => 'products_on_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">21</span>',
			'default'  => 21,
		),

		array(
			'id'       => 'products_n',
			'type'     => 'number',
			'title'    => '产品显示个数',
			'after'    => '<span class="after-perch">默认4</span>',
			'default'  => 4,
		),

		array(
			'id'       => 'products_id',
			'type'     => 'text',
			'title'    => '输入产品分类ID',
			'after'    => $mid,
		),
	)
));


CSF::createSection( $prefix, array(
	'parent'      => 'cms_setting',
	'title'       => '特色模块',
	'icon'        => '',
	'description' => '调用内容方法：编辑页面或者文章，在下面“仅用于特色模块”面板中输入相关内容',
	'fields'      => array(

		array(
			'id'       => 'grid_ico_cms',
			'type'     => 'switcher',
			'title'    => '特色模块',
		),

		array(
			'id'       => 'grid_ico_cms_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认22</span>',
			'default'  => 22,
		),

		array(
			'id'       => 'cms_ico_b',
			'type'     => 'switcher',
			'title'    => '图标无背景色',
		),

		array(
			'id'      => 'grid_ico_cms_n',
			'type'    => 'radio',
			'title'   => '分栏',
			'inline'  => true,
			'options' => $fl24568,
			'default' => '6',
		),
	)
));


CSF::createSection( $prefix, array(
	'parent'      => 'cms_setting',
	'title'       => '工具模块',
	'icon'        => '',
	'description' => '调用内容方法：编辑页面或者文章，在下面“仅用于工具模块”面板中输入相关内容',
	'fields'      => array(

		array(
			'id'       => 'cms_tool',
			'type'     => 'switcher',
			'title'    => '工具模块',
		),

		array(
			'id'       => 'cms_tool_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认23</span>',
			'default'  => 23,
		),
	)
));


CSF::createSection( $prefix, array(
	'parent'      => 'cms_setting',
	'title'       => '杂志三栏小工具',
	'icon'        => '',
	'description' => '杂志三栏小工具',
	'fields'      => array(

		array(
			'id'       => 'cms_widget_three',
			'type'     => 'switcher',
			'title'    => '杂志三栏小工具',
		),

		array(
			'id'       => 'cat_widget_three_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认24</span>',
			'default'  => 24,
		),

		array(
			'id'      => 'cms_widget_three_fl',
			'type'    => 'radio',
			'title'   => '分栏',
			'inline'  => true,
			'options' => $fl1234,
			'default' => '3',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'cms_setting',
	'title'       => '分类图片',
	'icon'        => '',
	'description' => '分类图片',
	'fields'      => array(

		array(
			'id'       => 'cat_square',
			'type'     => 'switcher',
			'title'    => '分类图片',
		),

		array(
			'id'       => 'cat_square_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认25</span>',
			'default'  => 25,
		),

		array(
			'id'       => 'cat_square_n',
			'type'     => 'number',
			'title'    => '篇数',
			'default'  => 6,
		),

		array(
			'id'       => 'cat_square_id',
			'type'     => 'text',
			'title'    => '输入分类ID',
			'after'    => $mid,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'cms_setting',
	'title'       => '分类网格',
	'icon'        => '',
	'description' => '分类网格',
	'fields'      => array(

		array(
			'id'       => 'cat_grid',
			'type'     => 'switcher',
			'title'    => '分类网格',
		),

		array(
			'id'       => 'cat_grid_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认26</span>',
			'default'  => 26,
		),

		array(
			'id'       => 'cat_grid_n',
			'type'     => 'number',
			'title'    => '篇数',
			'default'  => 6,
		),

		array(
			'id'       => 'cat_grid_id',
			'type'     => 'text',
			'title'    => '输入分类ID',
			'after'    => $mid,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'cms_setting',
	'title'       => '图片滚动模块',
	'icon'        => '',
	'description' => '图片滚动模块',
	'fields'      => array(

		array(
			'id'       => 'flexisel',
			'type'     => 'switcher',
			'title'    => '图片滚动模块',
		),

		array(
			'id'       => 'flexisel_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认27</span>',
			'default'  => 27,
		),

		array(
			'id'       => 'flexisel_n',
			'type'     => 'number',
			'title'    => '篇数',
			'default'  => 8,
		),

		array(
			'id'      => 'flexisel_m',
			'type'    => 'radio',
			'title'   => '调用方式',
			'inline'  => true,
			'options' => array(
				'flexisel_cat' => '文章分类',
				'flexisel_img' => '图片分类',
				'flexisel_key' => '指定文章',
			),
			'default' => 'flexisel_cat',
		),

		array(
			'class'    => 'be-parent-title',
			'type'     => 'subheading',
			'content'  => '文章分类',
		),

		array(
			'id'          => 'flexisel_cat_id',
			'class'    => 'be-child-item be-child-last-item',
			'type'        => 'select',
			'title'       => '选择一个分类',
			'placeholder' => '选择分类',
			'options'     => 'categories',
		),

		array(
			'class'    => 'be-parent-title',
			'type'     => 'subheading',
			'content'  => '图片分类',
		),

		array(
			'id'       => 'gallery_id',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'text',
			'title'    => '输入分类ID',
			'after'    => $mid,
		),

		array(
			'class'    => 'be-parent-title',
			'type'     => 'subheading',
			'content'  => '指定文章',
		),

		array(
			'id'       => 'key_n',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'text',
			'title'    => '添加自定义字段',
			'after'    => '通过为文章添加自定义字段，调用指定文章',
			'default'  => 'views',
		),

		array(
			'id'      => 'flexisel_f',
			'type'    => 'radio',
			'title'   => '分栏',
			'inline'  => true,
			'options' => $fl56,
			'default' => '5',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'cms_setting',
	'title'       => '底部分类列表',
	'icon'        => '',
	'description' => '底部分类列表',
	'fields'      => array(

		array(
			'id'       => 'cat_big',
			'type'     => 'switcher',
			'title'    => '底部分类列表',
		),

		array(
			'id'       => 'cat_big_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认28</span>',
			'default'  => 28,
		),

		array(
			'id'       => 'cat_big_n',
			'type'     => 'number',
			'title'    => '篇数',
			'default'  => 4,
		),

		array(
			'id'       => 'cat_big_three',
			'type'     => 'switcher',
			'title'    => '三栏',
			'default'  => true,
		),

		array(
			'id'       => 'cat_big_z',
			'type'     => 'switcher',
			'title'    => '不显示第一篇摘要',
			'default'  => true,
		),

		array(
			'id'       => 'cat_big_id',
			'type'     => 'text',
			'title'    => '输入分类ID',
			'after'    => $mid,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'cms_setting',
	'title'       => '商品',
	'icon'        => '',
	'description' => '商品',
	'fields'      => array(

		array(
			'id'       => 'tao_h',
			'type'     => 'switcher',
			'title'    => '商品',
		),

		array(
			'id'       => 'tao_h_s',
			'type'     => 'number',
			'title'    => '标题',
			'after'    => '<span class="after-perch">默认29</span>',
			'default'  => 29,
		),

		array(
			'id'       => 'tao_h_n',
			'type'     => 'number',
			'title'    => '篇数',
			'default'  => 4,
		),

		array(
			'id'      => 'h_tao_sort',
			'type'    => 'radio',
			'title'   => '排序',
			'inline'  => true,
			'options' => array(
				'time'  => '发表时间',
				'views' => '浏览量',
			),
			'default' => 'time',
		),

		array(
			'id'       => 'tao_h_id',
			'type'     => 'text',
			'title'    => '输入分类ID',
			'after'    => $mid,
		),
	)
));
	
if ( function_exists( 'is_shop' ) ) {
	CSF::createSection( $prefix, array(
		'parent'      => 'cms_setting',
		'title'       => 'WOO产品',
		'icon'        => '',
		'description' => '需要安装商城插件 WooCommerce 并发表产品',
		'fields'      => array(

			array(
				'id'       => 'product_h',
				'type'     => 'switcher',
				'title'    => 'WOO产品',
			),

			array(
				'id'       => 'product_h_s',
				'type'     => 'number',
				'title'    => '排序',
				'after'    => '<span class="after-perch">默认30</span>',
				'default'  => 30,
			),

			array(
				'id'       => 'product_h_n',
				'type'     => 'number',
				'title'    => '产品商品显示数量',
				'after'    => '<span class="after-perch">默认4</span>',
				'default'  => 4,
			),

			array(
				'id'       => 'product_h_id',
				'type'     => 'text',
				'title'    => '输入分类ID',
				'after'    => $mid,
			),

			array(
				'id'      => 'cms_woo_f',
				'type'    => 'radio',
				'title'   => '分栏',
				'inline'  => true,
				'options' => $fl456,
				'default' => '4',
			),
		)
	));
}

CSF::createSection( $prefix, array(
	'parent'      => 'cms_setting',
	'title'       => '底部无缩略图分类列表',
	'icon'        => '',
	'description' => '底部无缩略图分类列表',
	'fields'      => array(

		array(
			'id'       => 'cat_big_not',
			'type'     => 'switcher',
			'title'    => '底部无缩略图分类列表',
		),

		array(
			'id'       => 'cat_big_not_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认31</span>',
			'default'  => 31,
		),

		array(
			'id'       => 'cat_big_not_n',
			'type'     => 'number',
			'title'    => '篇数',
			'default'  => 4,
		),

		array(
			'id'       => 'cat_big_not_three',
			'type'     => 'switcher',
			'title'    => '三栏',
		),

		array(
			'id'       => 'cat_big_not_id',
			'type'     => 'text',
			'title'    => '输入分类ID',
			'after'    => $mid,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'cms_setting',
	'title'       => 'Ajax分类短代码',
	'icon'        => '',
	'description' => '通过添加短代码调用分类文章',
	'fields'      => array(

		array(
			'id'       => 'cms_ajax_cat',
			'type'     => 'switcher',
			'title'    => '显示',
		),

		array(
			'id'       => 'cms_ajax_cat_post_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认32</span>',
			'default'  => 32,
		),

		array(
			'id'    => 'cms_ajax_cat_post_code',
			'type'  => 'textarea',
			'title' => '输入短代码',
			'default'  => '[be_ajax_post]',
		),

		array(
			'class'    => 'be-help-code',
			'title'    => '短代码示例',
			'type'    => 'content',
			'content' => $shortcode_help,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'cms_setting',
	'title'       => '其它',
	'icon'        => '',
	'description' => '设置是显示文章日期及子分类',
	'fields'      => array(

		array(
			'id'       => 'list_date',
			'type'     => 'switcher',
			'title'    => '文章列表日期',
			'default'  => true,
		),

		array(
			'id'       => 'no_cat_child',
			'type'     => 'switcher',
			'title'    => '分类列表是否显示子分类文章',
		),
	)
));
// 杂志结束

// 公司开始
CSF::createSection( $prefix, array(
	'id'    => 'group_setting',
	'title' => '公司主页',
	'icon'  => 'dashicons dashicons-cover-image',
) );


CSF::createSection( $prefix, array(
	'parent'      => 'group_setting',
	'title'       => '幻灯',
	'icon'        => '',
	'description' => '调用方法， 新建文章或页面，在编辑器下面“用于公司主页幻灯”面板中输入图片地址，发表即可',
	'fields'      => array(

		array(
			'id'       => 'group_slider',
			'type'     => 'switcher',
			'title'    => '幻灯',
		),

		array(
			'id'       => 'group_slider_n',
			'type'     => 'number',
			'title'    => '篇数',
			'default'  => 3,
		),

		array(
			'id'       => 'big_back_img_h',
			'type'     => 'number',
			'title'    => '高度',
			'after'    => '<span class="after-perch">默认500</span>',
			'default'  => 500,
		),

		array(
			'id'       => 'big_back_img_m_h',
			'type'     => 'number',
			'title'    => '移动端高度',
			'after'    => '<span class="after-perch">用于移动端显示全图，留空默认240</span>',
		),

		array(
			'id'       => 'group_slider_url',
			'type'     => 'switcher',
			'title'    => '链接到目标',
			'default'  => true,
		),

		array(
			'id'       => 'group_slider_t',
			'type'     => 'switcher',
			'title'    => '显示文字',
			'default'  => true,
		),

		array(
			'id'       => 'group_blur',
			'type'     => 'switcher',
			'title'    => '模糊大背景图片',
		),

		array(
			'id'       => 'group_nav',
			'type'     => 'switcher',
			'title'    => '菜单浮在幻灯上',
		),

		array(
			'id'       => 'group_slider_video',
			'class'    => 'be-parent-item',
			'type'     => 'switcher',
			'title'    => '仅显示一个视频',
			'label'    => '',
		),

		array(
			'id'       => 'group_slider_video_url',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'upload',
			'title'    => 'MP4视频',
		),

		array(
			'id'       => 'group_only_img',
			'class'    => 'be-parent-item',
			'type'     => 'switcher',
			'title'    => '仅显示一张图片',
			'label'    => '',
		),

		array(
			'id'       => 'group_slider_img',
			'class'    => 'be-child-item',
			'type'     => 'upload',
			'title'    => '图片',
			'default'  => '',
			'preview'  => true,
		),

		array(
			'id'       => 'group_slider_img_url',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'text',
			'title'    => '图片链接到',
			'subtitle' => '',
			'before'   => '',
			'after'    => '点击图片跳转的地址',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'group_setting',
	'title'       => '关于我们',
	'icon'        => '',
	'description' => '关于我们',
	'fields'      => array(

		array(
			'id'       => 'group_contact',
			'type'     => 'switcher',
			'title'    => '关于我们',
		),

		array(
			'id'       => 'group_contact_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认1</span>',
			'default'  => 1,
		),

		array(
			'id'       => 'group_contact_t',
			'type'     => 'text',
			'title'    => '标题',
			'default'  => '关于我们',
		),

		array(
			'id'          => 'contact_p',
			'type'        => 'select',
			'title'       => '选择页面',
			'placeholder' => '选择页面',
			'options'     => 'pages',
			'query_args'  => array(
				'posts_per_page' => -1
			)
		),

		array(
			'id'       => 'contact_words_n',
			'type'     => 'number',
			'title'    => '显示字数',
			'after'    => '<span class="after-perch">默认210</span>',
			'default'  => 210,
		),

		array(
			'id'       => 'tr_contact',
			'type'     => 'switcher',
			'title'    => '移动端截断文字',
			'default'  => true,
		),

		array(
			'id'       => 'group_contact_bg',
			'type'     => 'switcher',
			'title'    => '显示图片',
		),

		array(
			'id'       => 'group_contact_img',
			'class'    => 'be-child-item',
			'type'     => 'upload',
			'title'    => '上传图片',
			'default'  => $imgdefault . '/options/1200.jpg',
			'preview'  => true,
		),

		array(
			'id'      => 'contact_img_m',
			'class'    => 'be-child-item be-child-last-item',
			'type'    => 'radio',
			'title'   => '图片显示模式',
			'inline'  => true,
			'options' => array(
				'contact_img_center' => '居中',
				'contact_img_right' => '居右',
			),
			'default' => 'contact_img_center',
		),

		array(
			'id'       => 'group_more_z',
			'type'     => 'text',
			'title'    => '详细查看按钮文字',
			'after'    => '留空则不显示',
			'default'  => '详细查看',
		),

		array(
			'id'       => 'group_more_ico',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '图标代码',
			'default'  => 'be be-stack',
		),

		array(
			'id'       => 'group_more_url',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'text',
			'title'    => '详细查看链接地址',
		),

		array(
			'id'       => 'group_contact_z',
			'type'     => 'text',
			'title'    => '联系方式按钮文字',
			'after'    => '留空则不显示',
			'default'  => '关于我们',
		),

		array(
			'id'       => 'group_contact_ico',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '图标代码',
			'default'  => 'be be-phone',
		),

		array(
			'id'       => 'group_contact_url',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'text',
			'title'    => '联系方式链接地址',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'group_setting',
	'title'       => '说明',
	'icon'        => '',
	'description' => '说明',
	'fields'      => array(

		array(
			'id'       => 'group_explain',
			'type'     => 'switcher',
			'title'    => '说明',
		),

		array(
			'id'       => 'group_explain_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认2</span>',
			'default'  => 2,
		),

		array(
			'id'          => 'explain_p',
			'type'        => 'select',
			'title'       => '选择简介页面',
			'placeholder' => '选择页面',
			'options'     => 'pages',
			'query_args'  => array(
				'posts_per_page' => -1
			)
		),

		array(
			'id'       => 'explain_words_n',
			'type'     => 'number',
			'title'    => '显示字数',
			'after'    => '<span class="after-perch">默认180</span>',
			'default'  => 180,
		),

		array(
			'id'       => 'group_explain_t',
			'type'     => 'text',
			'title'    => '标题',
			'default'  => '公司说明',
		),

		array(
			'id'       => 'group_explain_des',
			'type'     => 'text',
			'title'    => '说明',
			'default'  => '公司说明模块',
		),

		array(
			'id'       => 'explain_content_t',
			'type'     => 'text',
			'title'    => '文字说明小标题',
			'default'  => '文字说明小标题',
		),

		array(
			'id'       => 'group_explain_url',
			'type'     => 'text',
			'title'    => '自定义链接',
		),

		array(
			'id'       => 'group_explain_more',
			'type'     => 'text',
			'title'    => '自定义链接文字',
		),

		array(
			'id'       => 'group_explain_more_no',
			'type'     => 'switcher',
			'title'    => '不显示链接按钮',
		),

		array(
			'id'       => 'ex_thumbnail_only',
			'type'     => 'switcher',
			'title'    => '仅显示一张图',
		),

		array(
			'id'       => 'ex_thumbnail_a',
			'class'    => 'be-child-item',
			'type'     => 'upload',
			'title'    => '上传左图片',
			'default'  => $imgdefault . '/random/320.jpg',
			'preview'  => true,
		),


		array(
			'id'       => 'ex_thumbnail_b',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'upload',
			'title'    => '上传右图片',
			'default'  => $imgdefault . '/random/320.jpg',
			'preview'  => true,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'group_setting',
	'title'       => '公示板',
	'icon'        => '',
	'description' => '公示板',
	'fields'      => array(

		array(
			'id'       => 'group_notice',
			'type'     => 'switcher',
			'title'    => '公示板',
		),

		array(
			'id'       => 'group_notice_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认3</span>',
			'default'  => 3,
		),

		array(
			'id'       => 'group_notice_t',
			'type'     => 'text',
			'title'    => '标题',
			'default'  => '公示板',
		),

		array(
			'id'       => 'group_notice_des',
			'type'     => 'text',
			'title'    => '说明',
			'default'  => '公示板说明',
		),

		array(
			'id'       => 'group_notice_inf',
			'type'     => 'textarea',
			'title'    => '输入右侧文字信息',
			'sanitize' => false,
			'after'    => '可使用HTML代码',
			'default'  => '<h2>H2 响应式设计</h2><div class="clear"></div><h3>H3 自定义颜色风格</h3><h4>H4 响应式设计不依赖任何前端框架</h4><h5>H5 不依赖任何前端框架</h5><h6>H6 响应式设计自定义颜色风格不依赖任何前端框架风格不依赖任何风格不依赖任何</h6>',
		),

		array(
			'id'       => 'group_notice_img',
			'type'     => 'upload',
			'title'    => '左侧图片',
			'default'  => $imgdefault . '/random/560.jpg',
			'preview'  => true,
		),
	)
));


CSF::createSection( $prefix, array(
	'parent'      => 'group_setting',
	'title'       => '分类封面',
	'icon'        => '',
	'description' => '分类封面',
	'fields'      => array(

		array(
			'id'       => 'group_cat_cover',
			'type'     => 'switcher',
			'title'    => '分类封面',
		),

		array(
			'id'       => 'group_cat_cover_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认4</span>',
			'default'  => 4,
		),

		array(
			'id'       => 'group_cat_cover_id',
			'type'     => 'text',
			'title'    => '输入分类ID',
			'after'    => $mid,
		),

		array(
			'id'      => 'group_cover_f',
			'type'    => 'radio',
			'title'   => '分栏',
			'inline'  => true,
			'options' => $fl345,
			'default' => '4',
		),

		array(
			'id'       => 'group_cover_gray',
			'type'     => 'switcher',
			'title'    => '图片灰色',
			'default'  => true,
		),

		array(
			'id'       => 'group_cover_title',
			'type'     => 'switcher',
			'title'    => '显示分类名称',
			'default'  => true,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'group_setting',
	'title'       => '服务项目',
	'icon'        => '',
	'description' => '调用方法：编辑页面或者文章，在下面"仅用于公司主页服务模块"面板中输入相关内容',
	'fields'      => array(

		array(
			'id'       => 'dean',
			'type'     => 'switcher',
			'title'    => '服务项目',
		),

		array(
			'id'       => 'dean_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认5</span>',
			'default'  => 5,
		),

		array(
			'id'       => 'dean_t',
			'type'     => 'text',
			'title'    => '标题',
			'default'  => '服务项目',
		),

		array(
			'id'       => 'dean_des',
			'type'     => 'text',
			'title'    => '说明',
			'default'  => '服务项目模块',
		),

		array(
			'id'      => 'deanm_f',
			'type'    => 'radio',
			'title'   => '分栏',
			'inline'  => true,
			'options' => $fl345,
			'default' => '4',
		),

		array(
			'id'       => 'deanm_fm',
			'type'     => 'switcher',
			'title'    => '移动端强制1栏',
			'default'  => true,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'group_setting',
	'title'       => '推荐',
	'icon'        => '',
	'description' => '调用方法：编辑页面或者文章，在下面"仅用于公司主页推荐模块"面板中添加图片及相关内容',
	'fields'      => array(

		array(
			'id'       => 'group_foldimg',
			'type'     => 'switcher',
			'title'    => '推荐',
		),

		array(
			'id'       => 'foldimg_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认6</span>',
			'default'  => 6,
		),

		array(
			'id'       => 'foldimg_t',
			'type'     => 'text',
			'title'    => '标题',
			'default' => '推荐',
		),

		array(
			'id'       => 'foldimg_des',
			'type'     => 'text',
			'title'    => '说明',
			'default' => '推荐说明',
		),

		array(
			'id'       => 'foldimg_fl',
			'type'     => 'switcher',
			'title'    => '移动端显示1栏',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'group_setting',
	'title'       => '流程',
	'icon'        => '',
	'description' => '调用方法：编辑页面或者文章，在下面找到"公司流程模块图标代码"输入图标代码',
	'fields'      => array(

		array(
			'id'       => 'group_process',
			'type'     => 'switcher',
			'title'    => '流程',
		),

		array(
			'id'       => 'process_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认7</span>',
			'default'  => 7,
		),

		array(
			'id'       => 'process_t',
			'type'     => 'text',
			'title'    => '标题',
			'default'  => '工作流程',
		),

		array(
			'id'       => 'process_des',
			'type'     => 'text',
			'title'    => '说明',
			'default'  => '工作流程说明',
		),

		array(
			'id'       => 'process_turn',
			'type'     => 'switcher',
			'title'    => '显示动画',
			'default'  => true,
		),
	)
));


CSF::createSection( $prefix, array(
	'parent'      => 'group_setting',
	'title'       => '支持',
	'icon'        => '',
	'description' => '调用方法：编辑页面或者文章，在下面找到"公司支持模块图标代码"输入图标代码',
	'fields'      => array(

		array(
			'id'       => 'group_assist',
			'type'     => 'switcher',
			'title'    => '支持',
		),

		array(
			'id'       => 'group_assist_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认8</span>',
			'default'  => 8,
		),

		array(
			'id'       => 'group_assist_t',
			'type'     => 'text',
			'title'    => '标题',
			'default'  => '协助支持',
		),

		array(
			'id'       => 'group_assist_des',
			'type'     => 'text',
			'title'    => '说明',
			'default'  => '协助支持说明',
		),

		array(
			'id'       => 'group_assist_url',
			'type'     => 'switcher',
			'title'    => '链接到文章',
			'default'  => true,
		),

	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'group_setting',
	'title'       => '咨询',
	'icon'        => '',
	'description' => '调用方法：编辑页面或者文章，在下面勾选"添加到公司首页咨询模块"',
	'fields'      => array(

		array(
			'id'       => 'group_strong',
			'type'     => 'switcher',
			'title'    => '咨询',
		),

		array(
			'id'       => 'group_strong_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认9</span>',
			'default'  => 9,
		),

		array(
			'id'       => 'group_strong_t',
			'type'     => 'text',
			'title'    => '标题',
			'default'  => '咨询',
		),

		array(
			'id'       => 'group_strong_des',
			'type'     => 'text',
			'title'    => '说明',
			'default'  => '咨询说明',
		),

		array(
			'id'       => 'group_strong_title_c',
			'type'     => 'switcher',
			'title'    => '标题居中',
			'default'  => true,
		),

		array(
			'id'       => 'group_strong_inf',
			'type'     => 'textarea',
			'title'    => '输入左侧文字信息',
			'sanitize' => false,
			'after'    => '可使用HTML代码',
			'default'  => '<h2>H2 响应式设计</h2><div class="clear"></div><h3>H3 自定义颜色风格</h3><h4>H4 响应式设计不依赖任何前端框架</h4><h5>H5 不依赖任何前端框架</h5><h6>H6 响应式设计自定义颜色风格不依赖任何前端框架风格</h6>',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'group_setting',
	'title'       => '帮助',
	'icon'        => '',
	'description' => '调用方法：编辑页面或者文章，在下面勾选"添加到公司首页帮助模块"',
	'fields'      => array(

		array(
			'id'       => 'group_help',
			'type'     => 'switcher',
			'title'    => '帮助',
		),

		array(
			'id'       => 'group_help_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认10</span>',
			'default'  => 10,
		),

		array(
			'id'       => 'group_help_t',
			'type'     => 'text',
			'title'    => '标题',
			'default'  => '帮助',
		),

		array(
			'id'       => 'group_help_des',
			'type'     => 'text',
			'title'    => '说明',
			'default'  => '帮助说明',
		),

		array(
			'id'       => 'group_help_num',
			'type'     => 'switcher',
			'title'    => '显示序号',
			'default'  => true,
		),

		array(
			'id'       => 'group_help_img',
			'type'     => 'upload',
			'title'    => '左侧图片',
			'default'  => $imgdefault . '/random/320.jpg',
			'preview'  => true,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'group_setting',
	'title'       => '工具',
	'icon'        => '',
	'description' => '调用方法：编辑页面或者文章，在下面"仅用于工具模块"面板中输入相关内容',
	'fields'      => array(

		array(
			'id'       => 'group_tool',
			'type'     => 'switcher',
			'title'    => '工具',
		),

		array(
			'id'       => 'tool_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认11</span>',
			'default'  => 11,
		),

		array(
			'id'       => 'tool_t',
			'type'     => 'text',
			'title'    => '标题',
			'default'  => '工具',
		),

		array(
			'id'       => 'tool_des',
			'type'     => 'text',
			'title'    => '说明',
			'default'  => '实用工具',
		),

		array(
			'id'      => 'stool_f',
			'type'    => 'radio',
			'title'   => '分栏',
			'inline'  => true,
			'options' => $fl3456,
			'default' => '4',
		),
	)
));


CSF::createSection( $prefix, array(
	'parent'      => 'group_setting',
	'title'       => '产品模块',
	'icon'        => '',
	'description' => '产品模块',
	'fields'      => array(

		array(
			'id'       => 'group_products',
			'type'     => 'switcher',
			'title'    => '产品模块',
		),

		array(
			'id'       => 'group_products_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认12</span>',
			'default'  => 12,
		),

		array(
			'id'       => 'group_products_n',
			'type'     => 'number',
			'title'    => '篇数',
			'default'  => 4,
		),

		array(
			'id'       => 'group_products_t',
			'type'     => 'text',
			'title'    => '标题',
			'default'  => '主要产品',
		),

		array(
			'id'       => 'group_products_des',
			'type'     => 'text',
			'title'    => '说明',
			'default'  => '产品日志模块',
		),

		array(
			'id'       => 'group_products_id',
			'type'     => 'text',
			'title'    => '输入分类ID',
			'after'    => $mid,
		),

		array(
			'id'       => 'group_products_url',
			'type'     => 'text',
			'title'    => '输入更多按钮链接地址',
			'after'    => '留空则不显示',
		),

		array(
			'id'      => 'group_products_f',
			'type'    => 'radio',
			'title'   => '分栏',
			'inline'  => true,
			'options' => $fl456,
			'default' => '4',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'group_setting',
	'title'       => '服务宗旨',
	'icon'        => '',
	'description' => '服务宗旨',
	'fields'      => array(

		array(
			'id'       => 'service',
			'type'     => 'switcher',
			'title'    => '服务宗旨',
		),

		array(
			'id'       => 'service_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认13</span>',
			'default'  => 13,
		),

		array(
			'id'       => 'service_t',
			'type'     => 'text',
			'title'    => '标题',
			'default'  => '服务宗旨',
		),


		array(
			'id'       => 'service_des',
			'type'     => 'text',
			'title'    => '说明',
			'default'  => '服务宗旨模块',
		),

		array(
			'id'       => 'service_l_id',
			'type'     => 'text',
			'title'    => '输入左侧模块文章或页面ID',
			'after'    => $mid,
		),

		array(
			'id'       => 'service_r_id',
			'type'     => 'text',
			'title'    => '输入右侧模块文章或页面ID',
			'after'    => $mid,
		),

		array(
			'id'       => 'service_c_id',
			'type'     => 'text',
			'title'    => '输入中间模块文章或页面ID',
		),

		array(
			'id'       => 'service_c_img',
			'type'     => 'upload',
			'title'    => '输入中间模块图片地址',
			'default'  => $imgdefault . '/options/1200.jpg',
			'preview'  => true,
		),

		array(
			'id'       => 'service_bg_img',
			'type'     => 'upload',
			'title'    => '背景图片',
			'default'  => $imgdefault . '/options/1200.jpg',
			'preview'  => true,
		),

		array(
			'id'       => 'service_img_edit',
			'type'     => 'switcher',
			'title'    => '图片无链接',
		),
	)
));

if (function_exists( 'is_shop' )) {
	CSF::createSection( $prefix, array(
		'parent'      => 'group_setting',
		'title'       => 'WOO产品',
		'icon'        => '',
		'description' => 'WOO产品',
		'fields'      => array(

		array(
			'type'    => 'content',
			'content' => '需要安装商城插件 WooCommerce 并发表产品',
		),

			array(
				'id'       => 'g_product',
				'type'     => 'switcher',
				'title'    => 'WOO产品',
			),

			array(
				'id'       => 'g_product_s',
				'type'     => 'number',
				'title'    => '排序',
				'after'    => '<span class="after-perch">默认14</span>',
				'default'  => 14,
			),

			array(
				'id'       => 'g_product_t',
				'type'     => 'text',
				'title'    => '标题',
				'default'  => 'WOO产品',
			),

			array(
				'id'       => 'g_product_des',
				'type'     => 'text',
				'title'    => '说明',
				'default'  => 'WOO产品模块',
			),

			array(
				'id'       => 'g_product_id',
				'type'     => 'text',
				'title'    => '输入产品分类ID',
				'after'    => $mid,
			),

			array(
				'id'       => 'g_product_n',
				'type'     => 'number',
				'title'    => '产品显示数量',
				'default'  => 4,
			),

			array(
				'id'      => 'group_woo_f',
				'type'    => 'radio',
				'title'   => '分栏',
				'inline'  => true,
				'options' => $fl456,
				'default' => '4',
			),
		)
	));
}

CSF::createSection( $prefix, array(
	'parent'      => 'group_setting',
	'title'       => '特色',
	'icon'        => '',
	'description' => '调用方法：编辑页面或者文章，在下面"仅用于特色模块"面板中输入相关内容',
	'fields'      => array(

		array(
			'id'       => 'group_ico',
			'type'     => 'switcher',
			'title'    => '特色',
		),

		array(
			'id'       => 'group_ico_s',
			'type'     => 'number',
			'title'    => '排序',
			'default'  => 15,
			'after'    => '<span class="after-perch">默认15</span>',
		),

		array(
			'id'       => 'group_ico_t',
			'type'     => 'text',
			'title'    => '标题',
			'default'  => '特色',
		),

		array(
			'id'       => 'group_ico_des',
			'type'     => 'text',
			'title'    => '说明',
			'default'  => '特色模块',
		),

		array(
			'id'      => 'grid_ico_group_n',
			'type'    => 'radio',
			'title'   => '分栏',
			'inline'  => true,
			'options' => $fl24568,
			'default' => '6',
		),

		array(
			'id'       => 'group_ico_b',
			'type'     => 'switcher',
			'title'    => '图标无背景色',
		),

		array(
			'id'       => 'group_ico_img',
			'type'     => 'switcher',
			'title'    => '不显示文字(仅适用于图片)',
		),

		array(
			'id'       => 'group_md_gray',
			'type'     => 'switcher',
			'title'    => '图片灰色',
		),
	)
));


CSF::createSection( $prefix, array(
	'parent'      => 'group_setting',
	'title'       => '描述',
	'icon'        => '',
	'description' => '描述',
	'fields'      => array(

		array(
			'id'       => 'group_post',
			'type'     => 'switcher',
			'title'    => '描述',
		),

		array(
			'id'       => 'group_post_s',
			'type'     => 'number',
			'title'    => '排序',
			'default'  => 16,
			'after'    => '<span class="after-perch">默认16</span>',
		),

		array(
			'id'       => 'group_post_id',
			'type'     => 'text',
			'title'    => '输入文章或页面ID',
			'after'    => $mid,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'group_setting',
	'title'       => '简介',
	'icon'        => '',
	'description' => '简介',
	'fields'      => array(

		array(
			'id'       => 'group_features',
			'type'     => 'switcher',
			'title'    => '简介',
		),

		array(
			'id'       => 'group_features_s',
			'type'     => 'number',
			'title'    => '排序',
			'default'  => 17,
			'after'    => '<span class="after-perch">默认17</span>',
		),

		array(
			'id'       => 'features_t',
			'type'     => 'text',
			'title'    => '自定义标题',
			'default'  => '本站简介',
		),

		array(
			'id'       => 'features_des',
			'type'     => 'text',
			'title'    => '自定义描述',
			'default'  => '本站简介描述',
		),

		array(
			'id'          => 'features_id',
			'type'        => 'select',
			'title'       => '选择一个分类',
			'placeholder' => '选择分类',
			'options'     => 'categories',
		),

		array(
			'id'       => 'features_n',
			'type'     => 'number',
			'title'    => '篇数',
			'default'  => 4,
		),
		array(
			'id'      => 'group_features_f',
			'type'    => 'radio',
			'title'   => '分栏',
			'inline'  => true,
			'options' => $fl456,
			'default' => '4',
		),

		array(
			'id'       => 'features_url',
			'type'     => 'text',
			'title'    => '输入更多按钮链接地址',
			'after'    => '留空则不显示',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'group_setting',
	'title'       => '展示',
	'icon'        => '',
	'description' => '展示',
	'fields'      => array(

		array(
			'id'       => 'group_img',
			'type'     => 'switcher',
			'title'    => '展示',
		),

		array(
			'id'       => 'group_img_s',
			'type'     => 'number',
			'title'    => '排序',
			'default'  => 18,
			'after'    => '<span class="after-perch">默认18</span>',
		),

		array(
			'id'       => 'group_img_n',
			'type'     => 'number',
			'title'    => '篇数',
			'default'  => 4,
		),

		array(
			'id'       => 'group_img_id',
			'type'     => 'text',
			'title'    => '输入分类ID',
			'after'  => $mid,
		),

		array(
			'id'      => 'group_img_f',
			'type'    => 'radio',
			'title'   => '分栏',
			'inline'  => true,
			'options' => $fl456,
			'default' => '4',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'group_setting',
	'title'       => '分类左右图',
	'icon'        => '',
	'description' => '图片调用方法：编辑选定的分类中的一篇文章，在编辑框下面“将文章添加到”面板中，勾选“分类推荐文章”，并更新发表',
	'fields'      => array(

		array(
			'id'       => 'group_wd',
			'type'     => 'switcher',
			'title'    => '分类左右图',
		),

		array(
			'id'       => 'group_wd_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认19</span>',
			'default'  => 19,
		),

		array(
			'id'       => 'group_wd_id',
			'type'     => 'text',
			'title'    => '输入分类ID',
			'after'    => $mid,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'group_setting',
	'title'       => '一栏小工具',
	'icon'        => '',
	'description' => '一栏小工具',
	'fields'      => array(

		array(
			'id'       => 'group_widget_one',
			'type'     => 'switcher',
			'title'    => '一栏小工具',
		),

		array(
			'id'       => 'group_widget_one_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认20</span>',
			'default'  => 20,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'group_setting',
	'title'       => '最新文章',
	'icon'        => '',
	'description' => '最新文章',
	'fields'      => array(

		array(
			'id'       => 'group_new',
			'type'     => 'switcher',
			'title'    => '最新文章',
			'default'  => true,
		),

		array(
			'id'       => 'group_new_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认21</span>',
			'default'  => 21,
		),

		array(
			'id'       => 'group_new_t',
			'type'     => 'text',
			'title'    => '标题',
			'default'  => '最新文章',
		),

		array(
			'id'       => 'group_new_more_url',
			'type'     => 'text',
			'title'    => '标题链接',
		),

		array(
			'id'       => 'group_new_des',
			'type'     => 'text',
			'title'    => '说明',
			'default'  => '这里是本站最新发表的文章',
		),

		array(
			'id'       => 'group_new_list',
			'type'     => 'switcher',
			'title'    => '标题模式',
			'default'  => true,
		),

		array(
			'id'       => 'group_new_n',
			'type'     => 'number',
			'title'    => '篇数',
			'default'  => 10,
		),

		array(
			'id'      => 'not_group_new',
			'type'    => 'checkbox',
			'title'   => '排除的分类',
			'inline'  => true,
			'options' => 'categories',
			'query_args' => array(
				'orderby'  => 'ID',
				'order'    => 'ASC',
			),
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'group_setting',
	'title'       => '商品模块',
	'icon'        => '',
	'description' => '商品模块',
	'fields'      => array(

		array(
			'id'       => 'g_tao_h',
			'type'     => 'switcher',
			'title'    => '商品模块',
		),

		array(
			'id'       => 'g_tao_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认22</span>',
			'default'  => 22,
		),

		array(
			'id'       => 'g_tao_h_n',
			'type'     => 'number',
			'title'    => '篇数',
			'default'  => 4,
		),

		array(
			'id'      => 'g_tao_sort',
			'type'    => 'radio',
			'title'   => '排序',
			'inline'  => true,
			'options' => array(
				'time'  => '发表时间',
				'views' => '浏览量',
			),
			'default' => 'time',
		),

		array(
			'id'       => 'g_tao_h_id',
			'type'     => 'text',
			'title'    => '输入分类ID',
			'after'    => $mid,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'group_setting',
	'title'       => '三栏小工具',
	'icon'        => '',
	'description' => '三栏小工具',
	'fields'      => array(

		array(
			'id'       => 'group_widget_three',
			'type'     => 'switcher',
			'title'    => '三栏小工具',
		),

		array(
			'id'       => 'group_widget_three_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认23</span>',
			'default'  => 23,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'group_setting',
	'title'       => '新闻资讯A',
	'icon'        => '',
	'description' => '新闻资讯A',
	'fields'      => array(

		array(
			'id'       => 'group_cat_a',
			'type'     => 'switcher',
			'title'    => '新闻资讯A',
		),

		array(
			'id'       => 'group_cat_a_s',
			'type'     => 'number',
			'title'    => '排序',
			'default'  => 24,
			'after'    => '<span class="after-perch">默认24</span>',
		),

		array(
			'id'       => 'group_cat_a_n',
			'type'     => 'number',
			'title'    => '篇数',
			'default'  => 4,
		),

		array(
			'id'       => 'group_cat_a_id',
			'type'     => 'text',
			'title'    => '输入分类ID',
			'after'    => $mid,
		),

		array(
			'id'       => 'group_cat_a_top',
			'type'     => 'switcher',
			'title'    => '第一篇调用分类推荐文章',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'group_setting',
	'title'       => '两栏小工具',
	'icon'        => '',
	'description' => '两栏小工具',
	'fields'      => array(

		array(
			'id'       => 'group_widget_two',
			'type'     => 'switcher',
			'title'    => '两栏小工具',
		),

		array(
			'id'       => 'group_widget_two_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认25</span>',
			'default'  => 25,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'group_setting',
	'title'       => '新闻资讯B',
	'icon'        => '',
	'description' => '新闻资讯B',
	'fields'      => array(

		array(
			'id'       => 'group_cat_b',
			'type'     => 'switcher',
			'title'    => '新闻资讯B',
		),

		array(
			'id'       => 'group_cat_b_s',
			'type'     => 'number',
			'title'    => '排序',
			'default'  => 26,
			'after'    => '<span class="after-perch">默认26</span>',
		),

		array(
			'id'       => 'group_cat_b_n',
			'type'     => 'number',
			'title'    => '篇数',
			'default'  => 4,
		),

		array(
			'id'       => 'group_cat_b_id',
			'type'     => 'text',
			'title'    => '输入分类ID',
			'after'    => $mid,
		),

		array(
			'id'       => 'group_cat_b_top',
			'type'     => 'switcher',
			'title'    => '第一篇调用分类置顶文章',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'group_setting',
	'title'       => 'AJAX分类',
	'icon'        => '',
	'description' => 'AJAX加载TAB分类',
	'fields'      => array(

		array(
			'id'       => 'group_tab',
			'type'     => 'switcher',
			'title'    => 'AJAX分类',
		),

		array(
			'id'       => 'group_tab_s',
			'type'     => 'number',
			'title'    => '排序',
			'default'  => 27,
			'after'    => '<span class="after-perch">默认27</span>',
		),

		array(
			'id'       => 'group_tab_t',
			'type'     => 'text',
			'title'    => '标题',
			'default'  => 'AJAX分类',
		),

		array(
			'id'       => 'group_tab_n',
			'type'     => 'number',
			'title'    => '篇数',
			'default'  => 8,
		),

		array(
			'id'       => 'group_tab_cat_id',
			'type'     => 'text',
			'title'    => '输入分类ID',
			'after'    => $mid,
		),

		array(
			'id'      => 'group_tabs_mode',
			'type'    => 'radio',
			'title'   => '显示模式',
			'inline'  => true,
			'options' => array(
				'photo'    => '图片',
				'grid'     => '卡片',
				'title'    => '标题',
			),
			'default' => '',
		),

		array(
			'id'      => 'stab_f',
			'type'    => 'radio',
			'title'   => '分栏',
			'inline'  => true,
			'options' => $fl3456,
			'default' => '4',
		),

		array(
			'id'       => 'group_tab_img_meta',
			'type'     => 'switcher',
			'title'    => '图片模式显示文章信息',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'group_setting',
	'title'       => '新闻资讯C',
	'icon'        => '',
	'description' => '新闻资讯C',
	'fields'      => array(

		array(
			'id'       => 'group_cat_c',
			'type'     => 'switcher',
			'title'    => '新闻资讯C',
		),

		array(
			'id'       => 'group_cat_c_s',
			'type'     => 'number',
			'title'    => '排序',
			'default'  => 28,
			'after'    => '<span class="after-perch">默认28</span>',
		),

		array(
			'id'       => 'group_cat_c_n',
			'type'     => 'number',
			'title'    => '篇数',
			'default'  => 4,
		),

		array(
			'id'       => 'group_cat_c_id',
			'type'     => 'text',
			'title'    => '输入分类ID',
			'after'    => $mid,
		),

		array(
			'id'       => 'group_cat_c_img',
			'type'     => 'switcher',
			'title'    => '第一篇显示缩略图',
			'default'  => true,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'group_setting',
	'title'       => '热门推荐',
	'icon'        => '',
	'description' => '热门推荐',
	'fields'      => array(

		array(
			'id'       => 'group_carousel',
			'type'     => 'switcher',
			'title'    => '热门推荐',
		),

		array(
			'id'       => 'group_carousel_s',
			'type'     => 'number',
			'title'    => '排序',
			'default'  => 29,
			'after'    => '<span class="after-perch">默认29</span>',
		),

		array(
			'id'       => 'carousel_n',
			'type'     => 'number',
			'title'    => '篇数',
			'default'  => 8,
		),

		array(
			'id'       => 'group_carousel_t',
			'type'     => 'text',
			'title'    => '标题',
			'default'  => '热门推荐',
		),

		array(
			'id'       => 'carousel_des',
			'type'     => 'text',
			'title'    => '说明',
			'default'  => '热门推荐说明',
		),

		array(
			'id'          => 'group_carousel_id',
			'type'        => 'select',
			'title'       => '选择一个分类',
			'placeholder' => '选择分类',
			'options'     => 'categories',
		),

		array(
			'id'       => 'group_carousel_c',
			'type'     => 'switcher',
			'title'    => '标题居中',
			'default'  => true,
		),

		array(
			'id'       => 'group_gallery',
			'type'     => 'switcher',
			'title'    => '调用图片日志',
		),

		array(
			'id'       => 'group_gallery_id',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'text',
			'title'    => '输入图片分类ID',
			'after'    => $mid,
		),

		array(
			'id'       => 'carousel_bg_img',
			'type'     => 'upload',
			'title'    => '背景图片',
			'default'  => $imgdefault . '/options/1200.jpg',
			'preview'  => true,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'group_setting',
	'title'       => '新闻资讯D',
	'icon'        => '',
	'description' => '以左右模块展示两个分类文章列表',
	'fields'      => array(

		array(
			'id'       => 'group_cat_d',
			'type'     => 'switcher',
			'title'    => '新闻资讯D',
		),

		array(
			'id'       => 'group_cat_d_s',
			'type'     => 'number',
			'title'    => '排序',
			'default'  => 30,
			'after'    => '<span class="after-perch">默认30</span>',
		),

		array(
			'id'       => 'group_cat_d_n',
			'type'     => 'number',
			'title'    => '篇数',
			'default'  => 8,
		),

		array(
			'id'          => 'group_cat_d_l_id',
			'type'        => 'select',
			'title'       => '选择左侧分类',
			'placeholder' => '选择分类',
			'options'     => 'categories',
		),

		array(
			'id'       => 'group_cat_d_l_img',
			'type'     => 'upload',
			'title'    => '左侧图片',
			'default'  => $imgdefault . '/random/560.jpg',
			'preview'  => true,
		),

		array(
			'id'          => 'group_cat_d_r_id',
			'type'        => 'select',
			'title'       => '选择右侧分类',
			'placeholder' => '选择分类',
			'options'     => 'categories',
		),

		array(
			'id'       => 'group_cat_d_r_img',
			'type'     => 'upload',
			'title'    => '右侧图片',
			'default'  => $imgdefault . '/random/560.jpg',
			'preview'  => true,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'group_setting',
	'title'       => 'Ajax分类短代码',
	'icon'        => '',
	'description' => '通过添加短代码调用分类文章',
	'fields'      => array(

		array(
			'id'       => 'group_ajax_cat',
			'type'     => 'switcher',
			'title'    => '显示',
		),

		array(
			'id'       => 'group_ajax_cat_post_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认31</span>',
			'default'  => 31,
		),

		array(
			'id'    => 'group_ajax_cat_post_code',
			'type'  => 'textarea',
			'title' => '输入短代码',
			'default'  => '[be_ajax_post]',
		),

		array(
			'class'    => 'be-help-code',
			'title'    => '短代码示例',
			'type'    => 'content',
			'content' => $shortcode_help,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'group_setting',
	'title'       => '其它',
	'icon'        => '',
	'description' => '其它',
	'fields'      => array(

		array(
			'id'       => 'g_line',
			'type'     => 'switcher',
			'title'    => '隔行变色',
			'default'  => true,
		),

		array(
			'id'       => 'group_no_cat_child',
			'type'     => 'switcher',
			'title'    => '不显示子分类文章',
		),
	)
));

// 公司结束

CSF::createSection( $prefix, array(
	'id'    => 'catimg_setting',
	'title' => '分类图片',
	'icon'  => 'dashicons dashicons-format-gallery',
) );

CSF::createSection( $prefix, array(
	'parent'      => 'catimg_setting',
	'title'       => '最新文章',
	'icon'        => '',
	'description' => '分类图片首页最新文章',
	'fields'      => array(

		array(
			'id'       => 'grid_cat_new',
			'type'     => 'switcher',
			'title'    => '最新文章',
			'default'  => true,
		),

		array(
			'id'       => 'grid_cat_news_n',
			'type'     => 'number',
			'title'    => '篇数',
			'default'  => 4,
		),

		array(
			'id'       => 'grid_new_cat_tab',
			'type'     => 'switcher',
			'title'    => '分类链接',
		),

		array(
			'id'       => 'grid_new_cat_id',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'text',
			'title'    => '输入分类ID',
			'after'    => $mid,
		),

		array(
			'id'       => 'grid_new_cat_n',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'switcher',
			'title'    => '分类文章数不受上面限制',
			'default'  => true,
		),

		array(
			'id'      => 'grid_new_f',
			'type'    => 'radio',
			'title'   => '分栏',
			'inline'  => true,
			'options' => $fl456,
			'default' => '4',
		),

	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'catimg_setting',
	'title'       => '其它模块',
	'icon'        => '',
	'description' => '分类封面、条件筛选等',
	'fields'      => array(

		array(
			'id'       => 'catimg_cat_cover',
			'type'     => 'switcher',
			'title'    => '分类封面',
		),

		array(
			'id'       => 'catimg_filter',
			'type'     => 'switcher',
			'title'    => '显示多条件筛选',
		),

		array(
			'id'       => 'catimg_special',
			'type'     => 'switcher',
			'title'    => '专题',
		),
	)
));


CSF::createSection( $prefix, array(
	'parent'      => 'catimg_setting',
	'title'       => '分类模块A',
	'icon'        => '',
	'description' => '分类模块A',
	'fields'      => array(

		array(
			'id'       => 'grid_cat_a',
			'type'     => 'switcher',
			'title'    => '分类模块A',
		),

		array(
			'id'       => 'grid_cat_a_n',
			'type'     => 'number',
			'title'    => '篇数',
			'default'  => 4,
		),

		array(
			'id'       => 'grid_cat_a_id',
			'type'     => 'text',
			'title'    => '输入分类ID',
			'after'    => $mid,
		),

		array(
			'id'      => 'grid_cat_a_f',
			'type'    => 'radio',
			'title'   => '分栏',
			'inline'  => true,
			'options' => $fl456,
			'default' => '4',
		),

		array(
			'id'       => 'grid_cat_a_child',
			'type'     => 'switcher',
			'title'    => '同级/子分类链接',
			'default'  => true,
		),

		array(
			'id'       => 'grid_cat_a_des',
			'type'     => 'switcher',
			'title'    => '分类描述',
			'default'  => true,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'catimg_setting',
	'title'       => '杂志单栏小工具',
	'icon'        => '',
	'description' => '杂志单栏小工具',
	'fields'      => array(

		array(
			'id'       => 'grid_widget_one',
			'type'     => 'switcher',
			'title'    => '杂志单栏小工具',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'catimg_setting',
	'title'       => '分类滚动模块',
	'icon'        => '',
	'description' => '分类滚动模块',
	'fields'      => array(

		array(
			'id'       => 'grid_carousel',
			'type'     => 'switcher',
			'title'    => '分类滚动模块',
		),

		array(
			'id'       => 'grid_carousel_n',
			'type'     => 'number',
			'title'    => '篇数',
			'default'  => 8,
		),

		array(
			'id'          => 'grid_carousel_id',
			'type'        => 'select',
			'title'       => '选择一个分类',
			'placeholder' => '选择分类',
			'options'     => 'categories',
		),

		array(
			'id'      => 'grid_carousel_f',
			'type'    => 'radio',
			'title'   => '分栏',
			'inline'  => true,
			'options' => $fl456,
			'default' => '4',
		),

		array(
			'id'       => 'grid_carousel_des',
			'type'     => 'switcher',
			'title'    => '分类描述',
			'default'  => true,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'catimg_setting',
	'title'       => '分类模块B',
	'icon'        => '',
	'description' => '分类模块B',
	'fields'      => array(

		array(
			'id'       => 'grid_cat_b',
			'type'     => 'switcher',
			'title'    => '分类模块B',
		),

		array(
			'id'       => 'grid_cat_b_n',
			'type'     => 'number',
			'title'    => '篇数',
			'default'  => 5,
		),

		array(
			'id'       => 'grid_cat_b_id',
			'type'     => 'text',
			'title'    => '输入分类ID',
			'after'    => $mid,
		),

		array(
			'id'      => 'grid_cat_b_f',
			'type'    => 'radio',
			'title'   => '分栏',
			'inline'  => true,
			'options' => $fl456,
			'default' => '5',
		),

		array(
			'id'       => 'grid_cat_b_des',
			'type'     => 'switcher',
			'title'    => '分类描述',
			'default'  => true,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'catimg_setting',
	'title'       => '杂志三栏小工具',
	'icon'        => '',
	'description' => '杂志三栏小工具',
	'fields'      => array(

		array(
			'id'       => 'grid_widget_two',
			'type'     => 'switcher',
			'title'    => '杂志三栏小工具',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'catimg_setting',
	'title'       => 'Ajax分类短代码',
	'icon'        => '',
	'description' => '通过添加短代码调用分类文章',
	'fields'      => array(

		array(
			'id'       => 'catimg_ajax_cat',
			'type'     => 'switcher',
			'title'    => '显示',
		),

		array(
			'id'    => 'catimg_ajax_cat_post_code',
			'type'  => 'textarea',
			'title' => '输入短代码',
			'default'  => '[be_ajax_post]',
		),

		array(
			'class'    => 'be-help-code',
			'title'    => '短代码示例',
			'type'    => 'content',
			'content' => $shortcode_help,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'catimg_setting',
	'title'       => '分类模块C',
	'icon'        => '',
	'description' => '分类模块C',
	'fields'      => array(

		array(
			'id'       => 'grid_cat_c',
			'type'     => 'switcher',
			'title'    => '分类模块C',
		),

		array(
			'id'       => 'grid_cat_c_n',
			'type'     => 'number',
			'title'    => '篇数',
			'default'  => 4,
		),

		array(
			'id'       => 'grid_cat_c_id',
			'type'     => 'text',
			'title'    => '输入分类ID',
			'after'    => $mid,
		),

		array(
			'id'      => 'grid_cat_c_f',
			'type'    => 'radio',
			'title'   => '分栏',
			'inline'  => true,
			'options' => $fl456,
			'default' => '4',
		),

		array(
			'id'       => 'grid_cat_c_des',
			'type'     => 'switcher',
			'title'    => '分类描述',
			'default'  => true,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'catimg_setting',
	'title'       => '子分类文章',
	'icon'        => '',
	'description' => '分类模块中是否显示子分类文章',
	'fields'      => array(

		array(
			'id'       => 'no_grid_cat_child',
			'type'     => 'switcher',
			'title'    => '不显示子分类文章',
		),
	)
));

CSF::createSection( $prefix, array(
	'id'    => 'admin_setting',
	'title' => '站点管理',
	'icon'        => 'dashicons dashicons-admin-users',
) );

CSF::createSection( $prefix, array(
	'parent'      => 'admin_setting',
	'title'       => '管理站点',
	'icon'        => '',
	'description' => '登录注册相关设置',
	'fields'      => array(

		array(
			'id'       => 'login',
			'type'     => 'switcher',
			'title'    => '前端登录',
			'label'    => '',
			'default'  => true,
		),

		array(
			'id'       => 'profile',
			'type'     => 'switcher',
			'title'    => '顶部菜单登录按钮',
			'default'  => true,
		),

		array(
			'id'       => 'menu_login',
			'type'     => 'switcher',
			'title'    => '主菜单登录按钮',
			'default'  => true,
		),
		array(
			'id'       => 'menu_reg',
			'type'     => 'switcher',
			'title'    => '菜单注册按钮',
			'default'  => true,
		),

		array(
			'id'       => 'mobile_login',
			'type'     => 'switcher',
			'title'    => '移动端登录按钮',
			'default'  => true,
		),

		array(
			'id'       => 'reset_pass',
			'type'     => 'switcher',
			'title'    => '前端显示找回密码',
			'default'  => true,
		),
		array(
			'id'       => 'login_captcha',
			'type'     => 'switcher',
			'title'    => '登录验证码',
		),

		array(
			'id'       => 'register_captcha',
			'type'     => 'switcher',
			'title'    => '注册验证码',
			'default'  => true,
		),

		array(
			'id'       => 'lost_captcha',
			'type'     => 'switcher',
			'title'    => '找回密码验证码',
			'default'  => true,
		),
		array(
			'id'       => 'go_reg',
			'type'     => 'switcher',
			'title'    => '注册输入密码',
			'default'  => true,
		),

		array(
			'id'       => 'reg_captcha',
			'type'     => 'switcher',
			'title'    => '注册邮箱验证',
			'default'  => true,
			'after'    => '<span class="after-perch">需要与"注册输入密码"同时使用</span>',
		),

		array(
			'id'       => 'reg_above',
			'type'     => 'switcher',
			'title'    => '用户注册页面首屏为注册表单',
			'default'  => true,
		),
		array(
			'id'       => 'register_auto',
			'type'     => 'switcher',
			'title'    => '注册后自动登录',
		),

		array(
			'id'       => 'no_admin',
			'type'     => 'switcher',
			'title'    => '非管理员和编辑禁止进后台',
			'default'  => true,
		),

		array(
			'id'       => 'only_social_login',
			'type'     => 'switcher',
			'title'    => '仅允许社会化登录',
		),

		array(
			'id'       => 'user_l',
			'type'     => 'text',
			'title'    => '自定义登录按钮链接',
		),

		array(
			'id'       => 'reg_l',
			'type'     => 'text',
			'title'    => '注册按钮链接',
		),

		array(
			'id'       => 'logout_to',
			'type'     => 'text',
			'title'    => '退出登录后跳转的页面',
		),

		array(
			'id'       => 'wel_come',
			'type'     => 'text',
			'title'    => '顶部欢迎语',
			'default'  => '欢迎光临！',
		),

		array(
			'id'       => 'reg_clause',
			'type'     => 'textarea',
			'title'    => '注册登录协议说明文字',
			'sanitize' => false,
			'default'  => '<p style="text-align: center;">注册登录即视为同意以上条款</p>',
			'after'    => '可使用HMTL代码',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'admin_setting',
	'title'       => '用户中心',
	'icon'        => '',
	'description' => '设置前端用户中心',
	'fields'      => array(

		array(
			'id'          => 'user_url',
			'type'        => 'select',
			'title'       => '用户中心',
			'placeholder' => '选择页面',
			'options'     => 'pages',
			'query_args'  => array(
				'posts_per_page' => -1
			)
		),

		array(
			'id'          => 'tou_url',
			'type'        => 'select',
			'title'       => '用户投稿',
			'placeholder' => '选择页面',
			'options'     => 'pages',
			'query_args'  => array(
				'posts_per_page' => -1
			)
		),

		array(
			'id'       => 'personal_img',
			'type'     => 'upload',
			'title'    => '用户中心背景图片',
			'default'  => $imgdefault . '/options/1200.jpg',
			'preview'  => true,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'admin_setting',
	'title'       => '背景图片',
	'icon'        => '',
	'description' => '上传一些页面及模块的背景图片',
	'fields'      => array(

		array(
			'id'       => 'custom_login',
			'type'     => 'switcher',
			'title'    => '启用后台登录美化',
			'default'  => true,
		),

		array(
			'id'       => 'login_img',
			'class'    => 'be-child-item',
			'type'     => 'upload',
			'title'    => '背景图片',
			'default'  => 'https://desk-fd.zol-img.com.cn/t_s1920x1080c5/g7/M00/0E/09/ChMkLGMxCYeIYVapABUiNwouqU0AAH5vQICQNEAFSJP842.jpg',
			'preview'  => true,
		),

		array(
			'id'       => 'bing_login',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'switcher',
			'title'    => '必应每日壁纸',
		),

		array(
			'id'       => 'reg_img',
			'type'     => 'upload',
			'title'    => '注册页面背景图片',
			'default'  => 'https://desk-fd.zol-img.com.cn/t_s1920x1080c5/g7/M00/0E/09/ChMkLGMxCYeIYVapABUiNwouqU0AAH5vQICQNEAFSJP842.jpg',
			'preview'  => true,
		),

		array(
			'id'       => 'bing_reg',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'switcher',
			'title'    => '必应每日壁纸',
		),

		array(
			'id'       => 'reg_content_img',
			'type'     => 'upload',
			'title'    => '注册页面小背景图片',
			'default'  => 'http://sjbz.fd.zol-img.com.cn/t_s480x800c/g7/M00/0E/09/ChMkK2MxCo-IcMYqABUiNwouqU0AAH5wALKDPIAFSJP124.jpg',
			'preview'  => true,
			'after'    => '<span class="after-perch">图片大小（≈350×550px）</span>',
		),

		array(
			'id'       => 'no_reg_content_img',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'switcher',
			'title'    => '注册页面仅显示毛玻璃效果',
			'default'  => true,
		),

		array(
			'id'       => 'header_author_img',
			'type'     => 'upload',
			'title'    => '作者存档头部图片',
			'default'  => $imgdefault . '/options/1200.jpg',
			'preview'  => true,
		),

		array(
			'id'       => 'user_back',
			'type'     => 'upload',
			'title'    => '用户信息背景图片',
			'default'  => $imgdefault . '/options/user.jpg',
			'preview'  => true,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'admin_setting',
	'title'       => '重定向登录注册链接',
	'icon'        => '',
	'description' => '重定向默认登录注册页面到指定链接，选择一个自己认为适合的',
	'fields'      => array(

		array(
			'id'       => 'redirect_login',
			'type'     => 'switcher',
			'title'    => '重定向默认登录链接',
			'after'    => '<span class="after-perch">适合不想让别人进入默认登录注册页面，又不影响重置密码</span>',
		),

		array(
			'id'       => 'redirect_login_link',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'text',
			'title'    => '重定向网址',
		),


		array(
			'id'       => 'login_link',
			'type'     => 'switcher',
			'title'    => '修改默认登录链接',
			'after'    => '<span class="after-perch">适合不想让别人知道默认登录注册页面链接，但会影响重置密码</span>',
		),

		array(
			'id'       => 'pass_h',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '前缀',
			'default'  => 'my',
		),

		array(
			'id'       => 'word_q',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '后缀',
			'default'  => 'the',
		),

		array(
			'id'       => 'go_link',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '跳转网址',
			'default'  => '链接地址',
		),

		array(
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'content',
			'content'  => '要记住修改后的链接，默认登录地址：<b>http://域名/wp-login.php?my=the</b>',
		),
	)
));

CSF::createSection( $prefix, array(
	'id'    => 'seo_setting',
	'title' => 'SEO设置',
	'icon'  => 'dashicons dashicons-chart-bar',
) );


CSF::createSection( $prefix, array(
	'parent'      => 'seo_setting',
	'title'       => '站点SEO',
	'icon'        => '',
	'description' => '与SEO相关的设置',
	'fields'      => array(

		array(
			'id'       => 'wp_title',
			'type'     => 'switcher',
			'title'    => '启用SEO功能',
			'default'  => true,
			'label'    => '如使用其它SEO插件，取消勾选，以免重复显示SEO内容',
		),

		array(
			'id'       => 'og_title',
			'type'     => 'switcher',
			'title'    => '显示OG协议标签',
			'default'  => true,
		),

		array(
			'id'    => 'description',
			'type'  => 'textarea',
			'title' => '首页描述（Description）',
			'default'  => '一般不超过200个字符',
		),

		array(
			'id'    => 'keyword',
			'type'  => 'textarea',
			'title' => '首页关键词（KeyWords）',
			'default'  => '一般不超过100个字符',
		),

		array(
			'id'       => 'home_title',
			'type'     => 'text',
			'title'    => '自定义网站首页title',
			'after'    => '留空则不显示自定义title',
		),

		array(
			'id'       => 'home_info',
			'type'     => 'text',
			'title'    => '自定义网站首页副标题',
			'after'    => '留空则不显示自定义副标题',
		),

		array(
			'id'       => 'blog_info',
			'type'     => 'switcher',
			'title'    => '首页显示站点副标题',
			'default'  => true,
		),

		array(
			'id'       => 'connector',
			'type'     => 'text',
			'title'    => '修改站点分隔符',
			'default'  => '|',
		),

		array(
			'id'       => 'blank_connector',
			'type'     => 'switcher',
			'title'    => '分隔符无空格',
		),

		array(
			'id'       => 'blog_name',
			'type'     => 'switcher',
			'title'    => '正文title不显示网站名称',
			'label'    => '同时删除分隔符及勾选分隔符无空格',
		),

		array(
			'id'       => 'seo_title_tag',
			'type'     => 'switcher',
			'title'    => '正文title显示为标签+文章标题',
		),

		array(
			'id'       => 'seo_tag_number',
			'class'    => 'be-child-item',
			'type'     => 'number',
			'title'    => '标签数量',
			'default'  => '5',
		),

		array(
			'id'       => 'seo_separator_tag',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'text',
			'title'    => '标签分隔符',
			'default'  => '-',
		),

		array(
			'id'       => 'home_paged_ban',
			'type'     => 'switcher',
			'title'    => '杂志、公司布局首页分页链接301转向',
			'default'  => true,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'seo_setting',
	'title'       => '站点地图',
	'icon'        => '',
	'description' => '更新站点地图，勾选启用或改动设置后，需要保存两次设置，才会生成站点地图文件，用后取消勾选',
	'fields'      => array(

		array(
			'id'       => 'wp_sitemap_no',
			'type'     => 'switcher',
			'title'    => 'WP原生站点地图',
			'default'  => true,
			'after'    => '<span class="after-perch">默认链接：<a href="' . home_url() . '/wp-sitemap.xml" target="_blank">wp-sitemap.xml</a></span>',
		),

		array(
			'class'    => 'be-child-item be-child-last-item',
			'title'    => '提示',
			'type'    => 'content',
			'content' => '原生站点地图，支持大部分搜索引擎，因动态生成，可能索引速度较慢',
		),

		array(
			'id'       => 'sitemap_xml',
			'type'     => 'switcher',
			'title'    => '更新xml格式站点地图',
			'after'    => '<span class="after-perch">链接：<a href="' . home_url() . '/' . zm_get_option( 'sitemap_name' ) . '.xml" target="_blank">' . zm_get_option( 'sitemap_name' ) . '.xml</a></span>',
		),

		array(
			'id'       => 'sitemap_txt',
			'type'     => 'switcher',
			'title'    => '更新txt格式站点地图',
			'after'    => '<span class="after-perch">链接：<a href="' . home_url() . '/' . zm_get_option( 'sitemap_name' ) . '.txt" target="_blank">' . zm_get_option( 'sitemap_name' ) . '.txt</a></span>',
		),

		array(
			'id'       => 'sitemap_name',
			'type'     => 'text',
			'title'    => '自定义地图文件名称',
			'default'  => 'sitemap',
			'after'    => '防止被恶意采集利用',
		),

		array(
			'id'       => 'sitemap_n',
			'type'     => 'number',
			'title'    => '更新文章数',
			'default'  => '1000',
			'after'    => '<span class="after-perch">输入“-1”为全部，更新完取消勾选并保存设置</span>',
		),

		array(
			'title'    => '<span style="color: #cf4944;">提示</span>',
			'type'    => 'content',
			'content' => '<span style="color: #cf4944;">同时更新上万文章可能会卡死，造成主题选项不能使用，酌情设置</span>',
		),

		array(
			'class'    => 'be-parent-title',
			'type'     => 'subheading',
			'content'  => '拆分生成多个地图文件',
		),

		array(
			'id'       => 'offset_n',
			'class'    => 'be-child-item',
			'type'     => 'number',
			'title'    => '排除文章数',
			'default'  => '0',
			'after'    => '<span class="after-perch">默认保持 0</span>',
		),

		array(
			'id'       => 'sitemap_m',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'text',
			'title'    => '后缀',
			'after'    => '例如输入：a，则地图文件名为：sitemap-a，默认留空',
		),

		array(
			'class'    => 'be-child-item be-child-last-item be-help-inf',
			'title'    => '拆分操作说明',
			'type'    => 'content',
			'content' => '
			<b>第1个地图文件</b>&nbsp;&nbsp;&nbsp;&nbsp;设置"更新文章数" 1000，生成第1个地图文件 sitemap.xml<br />
			<b>第2个地图文件</b>&nbsp;&nbsp;&nbsp;&nbsp;在“排除文章数”中输入 1000 排除最新的 1000 篇文章，在“后缀”输入a，生成第2个地图文件 sitemap-a.xml<br />
			<b>第3个地图文件</b>&nbsp;&nbsp;&nbsp;&nbsp;在“排除文章数”中输入 2000 排除最新的 2000 篇文章，在“后缀”输入b，生成第3个地图文件 sitemap-b.xml<br />
			以此类推...<br />
			每次更改需要保存两次设置，否则不会生成新的站点地图文件',
		),

		array(
			'id'       => 'no_sitemap_pages',
			'type'     => 'switcher',
			'title'    => '包括页面',
			'default'  => true,
		),

		array(
			'id'       => 'no_sitemap_cat',
			'type'     => 'switcher',
			'title'    => '包括分类',
			'default'  => true,
		),

		array(
			'id'       => 'no_sitemap_tag',
			'type'     => 'switcher',
			'title'    => '包括标签',
			'default'  => true,
		),

		array(
			'id'       => 'no_sitemap_type',
			'type'     => 'switcher',
			'title'    => '包括公告、图片、视频、商品等',
			'default'  => true,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'seo_setting',
	'title'       => '更新文章归档页面',
	'icon'        => '',
	'description' => '更新文章归档页面，勾选启用后，需要保存两次设置，用后取消勾选。因在同一个页面显示上万文章可能会卡死，酌情使用！',
	'fields'      => array(

		array(
			'id'       => 'update_be_archives',
			'type'     => 'switcher',
			'title'    => '刷新文章归档页面',
			'after'    => '<span class="after-perch">需保存两次主题选项设置，用后关闭，文章较多可能会卡死</span>',
		),

		array(
			'id'       => 'update_up_archives',
			'type'     => 'switcher',
			'title'    => '刷新文章更新页面',
			'after'    => '<span class="after-perch">需保存两次主题选项设置，用后关闭，设置一下时间段，防止文章较多卡死</span>',
		),

		array(
			'title'    => '说明',
			'class'    => 'be-child-item',
			'type'    => 'content',
			'content' => '可以单独设置年、月、分类，留空则显示全部文章',
		),

		array(
			'id'       => 'year_n',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '年份',
		),

		array(
			'id'       => 'mon_n',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '月份',
		),

		array(
			'id'       => 'cat_up_n',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'text',
			'title'    => '分类ID',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'seo_setting',
	'title'       => '关键词内链',
	'icon'        => '',
	'description' => '设置关键词内链',
	'fields'      => array(


		array(
			'id'       => 'keyword_link',
			'type'     => 'switcher',
			'title'    => '关键词',
			'default'  => true,
		),

		array(
			'class'    => 'be-button-url be-child-item be-child-last-item',
			'type'     => 'subheading',
			'title'    => '设置关键词',
			'content'  => '<span class="button-primary"><a href="' . home_url() . '/wp-admin/options-general.php?page=keywordlink" target="_blank">添加关键词</a></span>'
		),

		array(
			'id'       => 'tag_c',
			'type'     => 'switcher',
			'title'    => '用文章标签作为关键词添加内链',
		),

		array(
			'id'       => 'chain_n',
			'class'    => 'be-child-item',
			'type'     => 'number',
			'title'    => '数量',
			'default'  => 2,
		),

		array(
			'class'    => 'be-child-item be-child-last-item',
			'title'    => '提示',
			'type'    => 'content',
			'content' => '<span style="color: #cf4944;">如果网站标签较多，更新文章时可能会卡死，酌情使用</span>',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'seo_setting',
	'title'       => '自动添加标签',
	'icon'        => '',
	'description' => '自动为文章添加使用过的标签',
	'fields'      => array(

		array(
			'id'       => 'auto_tags',
			'type'     => 'switcher',
			'title'    => '自动添加标签',
		),

		array(
			'id'       => 'auto_tags_n',
			'type'     => 'number',
			'title'    => '添加数量',
			'default'  => 6,
		),

		array(
			'id'       => 'auto_tags_random',
			'type'     => 'switcher',
			'title'    => '随机',
		),

		array(
			'title'    => '提示',
			'type'    => 'content',
			'content' => '<span style="color: #cf4944;">如果网站标签较多，更新文章时可能会卡死，酌情使用</span>',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'seo_setting',
	'title'       => '百度收录',
	'icon'        => '',
	'description' => '将文章自动提交给百度',
	'fields'      => array(

		array(
			'id'       => 'baidu_link',
			'type'     => 'switcher',
			'title'    => '百度普通收录',
		),

		array(
			'id'       => 'link_token',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'text',
			'title'    => '准入密钥',
		),

		array(
			'id'       => 'baidu_daily',
			'type'     => 'switcher',
			'title'    => '百度快速收录',
		),

		array(
			'id'       => 'daily_token',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'text',
			'title'    => '准入密钥',
		),

		array(
			'id'       => 'baidu_time',
			'type'     => 'switcher',
			'title'    => '百度时间因子',
			'after'    => '<span class="after-perch">主题时间标注清晰符合要求，百度官方也未提供任何具体代码，有效与否自行判断</span>',
		),

	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'seo_setting',
	'title'       => '自定义分类法固定链接',
	'icon'        => '',
	'description' => '用于修改公告、图片、视频、商品、产品等固定链接。',
	'fields'      => array(

		array(
			'id'       => 'begin_types_link',
			'type'     => 'switcher',
			'title'    => '自定义分类法固定链接',
		),

		array(
			'id'      => 'begin_types',
			'type'    => 'radio',
			'title'   => '选择',
			'inline'  => true,
			'options' => array(
				'link_id'   => '文章ID.html',
				'link_name' => '文章名称.html',
			),
			'default' => 'link_id',
		),

		array(
			'title'   => '提示',
			'type'    => 'content',
			'content' => '修改后到WP后台→设置→固定链接设置，保存一下，否则不会生效',
		),
	)
));


CSF::createSection( $prefix, array(
	'parent'      => 'seo_setting',
	'title'       => '自定义分类法链接前缀',
	'icon'        => '',
	'description' => '修改保存主题设置后，必需保存一次固定链接设置才会生效',
	'fields'      => array(

		array(
			'id'       => 'bull_url',
			'type'     => 'text',
			'title'    => '公告链接前缀',
			'default'  => 'bulletin',
		),

		array(
			'id'       => 'bull_cat_url',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'text',
			'title'    => '公告分类链接前缀',
			'default'  => 'notice',
		),

		array(
			'id'       => 'img_url',
			'type'     => 'text',
			'title'    => '图片链接前缀',
			'default'  => 'picture',
		),

		array(
			'id'       => 'img_cat_url',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'text',
			'title'    => '图片分类链接前缀',
			'default'  => 'gallery',
		),

		array(
			'id'       => 'video_url',
			'type'     => 'text',
			'title'    => '视频链接前缀',
			'default'  => 'video',
		),

		array(
			'id'       => 'video_cat_url',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'text',
			'title'    => '视频分类链接前缀',
			'default'  => 'videos',
		),

		array(
			'id'       => 'sp_url',
			'type'     => 'text',
			'title'    => '商品链接前缀',
			'default'  => 'tao',
		),

		array(
			'id'       => 'sp_cat_url',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'text',
			'title'    => '商品分类链接前缀',
			'default'  => 'taobao',
		),

		array(
			'id'       => 'favorites_url',
			'type'     => 'text',
			'title'    => '网址链接前缀',
			'default'  => 'sites',
		),

		array(
			'id'       => 'favorites_cat_url',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'text',
			'title'    => '网址分类链接前缀',
			'default'  => 'favorites',
		),

		array(
			'id'       => 'show_url',
			'type'     => 'text',
			'title'    => '产品链接前缀',
			'default'  => 'show',
		),

		array(
			'id'       => 'show_cat_url',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'text',
			'title'    => '产品分类链接前缀',
			'default'  => 'products',
		),

		array(
			'id'       => 'be_special_url',
			'type'     => 'text',
			'title'    => '专题链接前缀',
			'default'  => 'special',
		),

	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'seo_setting',
	'title'       => '流量统计代码',
	'icon'        => '',
	'description' => '用于添加流量统计代码',
	'fields'      => array(

		array(
			'id'        => 'tongji_h',
			'type'      => 'textarea',
			'title'     => '异步',
			'sanitize'  => false,
			'after'     => '用于在页头添加异步统计代码（例如：百度统计）',
		),

		array(
			'id'        => 'tongji_f',
			'type'      => 'textarea',
			'title'     => '同步',
			'sanitize'  => false,
			'after'     => '用于在页脚添加同步统计代码（例如：站长统计）',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'seo_setting',
	'title'       => '页脚信息',
	'icon'        => '',
	'description' => '修改添加页脚信息',
	'fields'      => array(

		array(
			'id'        => 'footer_inf_t',
			'type'      => 'wp_editor',
			'title'     => '页脚信息',
			'sanitize'  => false,
			'after'     => '回行显示，选择文字“居中对齐”',
			'default'   => '<p style="text-align: center;">Copyright &copy;&nbsp;&nbsp;站点名称&nbsp;&nbsp;版权所有.</p><p style="text-align: center;">主题选项→SEO选项卡，最下面修改页脚信息</p><p style="text-align: center;"><a title="主题设计：知更鸟" href="http://zmingcx.com/" target="_blank" rel="external nofollow"><img src="' . get_template_directory_uri() . '/img/logo.png" alt="Begin主题" width="120" height="27" /></a></p>',
		),

		array(
			'id'       => 'yb_info',
			'type'     => 'text',
			'title'    => '域名备案号',
		),

		array(
			'id'       => 'yb_url',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '工信部链接',
		),

		array(
			'id'       => 'yb_img',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'upload',
			'title'    => '域名备案小图标',
			'default'  => '',
			'preview'  => true,
		),

		array(
			'id'       => 'wb_info',
			'type'     => 'text',
			'title'    => '公网安备号',
		),

		array(
			'id'       => 'wb_url',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '公网安备链接',
		),

		array(
			'id'       => 'wb_img',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'upload',
			'title'    => '公网安备小图标',
			'default'  => '',
			'preview'  => true,
		),
	)
));

// 基本设置
CSF::createSection( $prefix, array(
	'id'    => 'basic_setting',
	'title' => '基本设置',
	'icon'  => 'dashicons dashicons-admin-generic',
) );

CSF::createSection( $prefix, array(
	'parent'      => 'basic_setting',
	'title'       => '文章列表截断字数',
	'description' => '可以根据页面宽度适当调整文章列表摘要的显示字数',
	'fields'      => array(

		array(
			'id'       => 'words_n',
			'type'     => 'number',
			'title'    => '自动截断字数',
			'after'    => '<span class="after-perch">默认值：100</span>',
			'default'  => 100,
		),

		array(
			'id'       => 'word_n',
			'type'     => 'number',
			'title'    => '摘要截断字数',
			'after'    => '<span class="after-perch">默认值：90</span>',
			'default'  => 90,
		),
	)
) );

CSF::createSection( $prefix, array(
	'parent'      => 'basic_setting',
	'title'       => '阅读全文按钮',
	'icon'        => '',
	'description' => '自定义文章列表阅读全文按钮文字',
	'fields'      => array(

		array(
			'id'       => 'more_w',
			'type'     => 'text',
			'title'    => '阅读全文按钮文字',
			'after'    => '留空则不显示',
			'default'  => '',
		),

		array(
			'id'       => 'direct_w',
			'type'     => 'text',
			'title'    => '直达链接按钮文字',
			'after'    => '留空则不显示',
			'default'  => '直达链接',
		),

		array(
			'id'       => 'more_hide',
			'type'     => 'switcher',
			'title'    => '默认隐藏',
			'label'    => '鼠标悬停时显示',
			'default'  => true,
		),

	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'basic_setting',
	'title'       => '图片延迟加载',
	'icon'        => '',
	'description' => '延迟加载图片，提高页面加载速度',
	'fields'      => array(

		array(
			'id'       => 'lazy_s',
			'type'     => 'switcher',
			'title'    => '缩略图延迟加载',
			'default'  => true,
		),

		array(
			'id'       => 'lazy_e',
			'type'     => 'switcher',
			'title'    => '正文图片延迟加载',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'basic_setting',
	'title'       => '公告',
	'icon'        => '',
	'description' => '用滚动公告代替首页面包屑导航',
	'fields'      => array(

		array(
			'id'       => 'bulletin',
			'type'     => 'switcher',
			'title'    => '公告',
		),

		array(
			'id'       => 'bulletin_id',
			'type'     => 'text',
			'title'    => '输入公告分类ID',
			'after'    => '调用指定的分类',
		),

		array(
			'id'       => 'bulletin_n',
			'type'     => 'number',
			'title'    => '篇数',
			'default'  => 2,
		),

		array(
			'id'      => 'notice_m',
			'type'    => 'radio',
			'title'   => '公告分类模板选择',
			'inline'  => true,
			'options' => array(
				'notice_d' => '默认',
				'notice_s' => '说说',
			),
			'default' => 'notice_s',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'basic_setting',
	'title'       => '弹窗公告',
	'icon'        => '',
	'description' => '设置定时弹出的公告',
	'fields'      => array(

		array(
			'id'       => 'placard_layer',
			'type'     => 'switcher',
			'title'    => '弹窗公告',
		),

		array(
			'id'       => 'admin_placard',
			'type'     => 'switcher',
			'title'    => '排除管理员',
		),

		array(
			'id'          => 'placard_cat_id',
			'type'        => 'select',
			'title'       => '选择一个分类',
			'placeholder' => '选择分类',
			'options'     => 'categories',
		),

		array(
			'id'       => 'placard_id',
			'type'     => 'text',
			'title'    => '输入文章ID',
			'after'    => '调用指定文章，留空则显示5篇分类文章',
		),

		array(
			'id'       => 'placard_time',
			'type'     => 'number',
			'title'    => '默认30分钟弹出一次',
			'after'    => '<span class="after-perch">分钟</span>',
			'default'  => 30,
		),

		array(
			'id'       => 'placard_img',
			'type'     => 'switcher',
			'title'    => '显示最新文章图片',
			'default'  => true,
		),

		array(
			'id'       => 'custom_placard',
			'type'     => 'switcher',
			'title'    => '自定义内容',
		),

		array(
			'id'       => 'custom_placard_title',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '标题',
		),

		array(
			'id'       => 'custom_placard_url',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '链接',
		),

		array(
			'id'       => 'custom_placard_img',
			'class'    => 'be-child-item',
			'type'     => 'upload',
			'title'    => '图片',
			'default'  => $imgdefault . '/random/320.jpg',
			'preview'  => true,
		),

		array(
			'id'        => 'custom_placard_content',
			'class'     => 'be-child-item be-child-last-item',
			'type'      => 'textarea',
			'title'     => '内容',
			'sanitize'  => false,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'basic_setting',
	'title'       => 'Ajax加载文章',
	'icon'        => '',
	'description' => '滚动页面时，自动加载下一页文章',
	'fields'      => array(

		array(
			'id'       => 'infinite_post',
			'type'     => 'switcher',
			'title'    => 'Ajax 加载文章',
			'default'  => true,
		),

		array(
			'id'       => 'pages_n',
			'type'     => 'number',
			'title'    => '加载页数',
			'default'  => 3,
		),

	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'basic_setting',
	'title'       => '页号显示',
	'icon'        => '',
	'description' => '设置文章列表分页按钮显示模式',
	'fields'      => array(

		array(
			'id'       => 'first_mid_size',
			'type'     => 'number',
			'title'    => '首页页号数',
			'default'  => 2,
		),

		array(
			'id'       => 'mid_size',
			'type'     => 'number',
			'title'    => '其它页号数',
			'default'  => 4,
		),

		array(
			'id'       => 'input_number',
			'type'     => 'switcher',
			'title'    => '输入页号跳转',
			'default'  => true,
		),

		array(
			'id'       => 'turn_small',
			'type'     => 'switcher',
			'title'    => '移动端简化分页',
			'default'  => true,
		),

		array(
			'id'       => 'no_pagination',
			'type'     => 'switcher',
			'title'    => '不显示分页按钮',
		),

		array(
			'id'       => 'rewrite_paged_url',
			'type'     => 'switcher',
			'title'    => '自定义分页链接前缀',
			'label'    => '更改后，需要保存一次固定链接设置',
		),

		array(
			'id'       => 'rewrite_paged_url_txt',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '自定义分页链接前缀',
			'default'  => 'mypage',
		),

		array(
			'title'   => '说明',
			'class'    => 'be-help-code be-child-item be-child-last-item',
			'type'    => 'content',
			'content' => '为恶意采集增加难度，可与上面“不显示分页按钮”同时使用<br /><span>默认翻页链接</span>/page/2/<br /><span>修改后翻页链接</span>/mypage/2/<br /><strong>更改后需要保存一次固定链接设置</strong>',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'basic_setting',
	'title'       => '替换用户默认链接',
	'icon'        => '',
	'description' => '替换文章作者归档页默认链接，防止暴露登录名称',
	'fields'      => array(

		array(
			'id'      => 'my_author',
			'type'    => 'radio',
			'title'   => '作者链接后缀',
			'inline'  => true,
			'options' => array(
				'first_name' => '用户名字',
				'last_name'  => '用户姓氏',
			),
			'default' => 'first_name',
		),

		array(
			'title'   => '提示',
			'type'    => 'content',
			'content' => '后台 → 用户 → 个人资料页面 → 显示名称，在名字或姓氏中输入字母单词，不能使用中文，并在“公开显示为”选择除登录名之外的',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'basic_setting',
	'title'       => '生成文章索引目录',
	'icon'        => '',
	'description' => '用段落标题生成文章索引目录',
	'fields'      => array(

		array(
			'id'       => 'be_toc',
			'type'     => 'switcher',
			'title'    => '生成文章索引目录',
		),

		array(
			'id'      => 'toc_mode',
			'type'    => 'radio',
			'title'   => '选择几级标题',
			'inline'  => true,
			'options' => array(
				'toc_four' => '仅四级标题',
				'toc_all'  => '二至六级标题',
			),
			'default' => 'toc_four',
		),

		array(
			'id'      => 'toc_style',
			'type'    => 'radio',
			'title'   => '层级显示',
			'inline'  => true,
			'options' => array(
				'tocjq'  => '单级显示',
				'tocphp' => '层级显示',
			),
			'default' => 'tocjq',
		),

		array(
			'id'       => 'toc_title_n',
			'type'     => 'number',
			'title'    => '几个标题时生成目录',
			'default'  => 4,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'basic_setting',
	'title'       => '正文设置',
	'icon'        => '',
	'description' => '正文显示内容设置',
	'fields'      => array(

		array(
			'id'       => 'begin_today',
			'type'     => 'switcher',
			'title'    => '历史上的今天',
		),

		array(
			'id'       => 'lightbox_on',
			'type'     => 'switcher',
			'title'    => '图片 Lightbox 放大查看',
			'default'  => true,
		),

		array(
			'id'       => 'auto_img_link',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'switcher',
			'title'    => '图片自动添加超链接',
			'after'    => '<span class="after-perch">用于触发放大查看，酌情开启</span>',
		),

		array(
			'id'       => 'p_first',
			'type'     => 'switcher',
			'title'    => '段首缩进',
			'default'  => true,
		),

		array(
			'id'       => 'all_more',
			'type'     => 'switcher',
			'title'    => '正文继续阅读按钮',
		),

		array(
			'id'       => 'custum_font',
			'type'     => 'switcher',
			'title'    => '编辑器增加中文字体',
			'default'  => true,
		),

		array(
			'id'       => 'copy_tips',
			'type'     => 'switcher',
			'title'    => '复制提示',
		),

		array(
			'id'       => 'copyright_pro',
			'type'     => 'switcher',
			'title'    => '禁止复制及右键',
			'after'    => '<span class="after-perch">管理员登录无效</span>',
		),

		array(
			'id'       => 'no_copy',
			'type'     => 'switcher',
			'title'    => '禁止复制CSS版',
		),

		array(
			'id'       => 'copy_upset',
			'type'     => 'switcher',
			'title'    => '在段落末尾添加隐藏的版权链接',
			'after'    => '<span class="after-perch">为恶意采集增加难度</span>',
		),

		array(
			'id'       => 'copy_upset_txt',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'text',
			'title'    => '自定义文字',
			'after'    => '默认文章源自+网站名称',
			'default'  => '文章源自',
		),

		array(
			'id'       => 'link_pages_all',
			'type'     => 'switcher',
			'title'    => '文章分页显示全部按钮',
			'default'  => true,
		),

		array(
			'id'       => 'link_external',
			'type'     => 'switcher',
			'title'    => '文章外链接添加nofollow',
			'default'  => true,
		),

		array(
			'id'       => 'link_internal',
			'type'     => 'switcher',
			'title'    => '文章内链接新窗口打开',
			'after'    => '<span class="after-perch">需与上面的选项同时使用</span>',
		),
		array(
			'id'       => 'single_no_sidebar',
			'type'     => 'switcher',
			'title'    => '正文无侧边栏',
		),

		array(
			'id'       => 'photo_album_n',
			'type'     => 'number',
			'title'    => '相册短代码默认每页显示图片数',
			'default'  => 4,
		),

	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'basic_setting',
	'title'       => '代码高亮',
	'icon'        => '',
	'description' => '显示代码高亮',
	'fields'      => array(

		array(
			'id'       => 'be_code',
			'type'     => 'switcher',
			'title'    => '自动代码高亮显示',
			'default'  => true,
		),

		array(
			'id'       => 'highlight',
			'type'     => 'switcher',
			'title'    => '手动代码高亮显示',
			'default'  => true,
			'after'    => '<span class="after-perch">仅为兼容老版本主题</span>',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'basic_setting',
	'title'       => 'AJAX分类短代码',
	'icon'        => '',
	'description' => '在文章或页面中添加短代码，以Ajax调用分类文章',
	'fields'      => array(

		array(
			'id'       => 'ajax_cat_btn_flow',
			'type'     => 'switcher',
			'title'    => '按钮不回行',
			'after'    => '<span class="after-perch">分类按钮较多时，在移动端一行显示</span>',
		),

		array(
			'class'    => 'be-help-code',
			'title'    => '短代码示例',
			'type'    => 'content',
			'content' => $shortcode_help,
		),
	)
));

// 子项
CSF::createSection( $prefix, array(
	'parent'      => 'basic_setting',
	'title'       => '功能优化',
	'icon'        => '',
	'description' => '用于优化WordPress功能',
	'fields'      => array(

		array(
			'id'       => 'no_category',
			'type'     => 'switcher',
			'title'    => '去掉分类链接中的"category"',
			'after'    => '<span class="after-perch">更改后需保存一次固定链接设置</span>',
		),

		array(
			'id'       => 'category_x',
			'type'     => 'switcher',
			'title'    => '分类归档链接添加"/"斜杠',
			'after'    => '<span class="after-perch">更改后需保存一次固定链接设置</span>',
		),

		array(
			'id'       => 'page_html',
			'type'     => 'switcher',
			'title'    => '页面添加.html后缀',
			'after'    => '<span class="after-perch">更改后需保存一次固定链接设置</span>',
		),

		array(
			'id'       => 'image_alt',
			'type'     => 'switcher',
			'title'    => '自动将文章标题作为图片 alt 标签内容',
			'default'  => true,
		),

		array(
			'id'       => 'be_upload_name',
			'type'     => 'switcher',
			'title'    => '上传附件自动按时间重命名',
			'default'  => true,
		),

		array(
			'id'       => 'last_login',
			'type'     => 'switcher',
			'title'    => '显示用户登录注册时间',
			'default'  => true,
		),

		array(
			'id'       => 'bulk_actions_post',
			'type'     => 'switcher',
			'title'    => '文章批量操作',
			'default'  => true,
		),

		array(
			'id'       => 'ajax_move_post',
			'type'     => 'switcher',
			'title'    => '后台 Ajax 移动文章到回收站',
			'default'  => true,
		),

		array(
			'id'       => 'meta_key_filter',
			'type'     => 'switcher',
			'title'    => '后台自定义字段筛选',
			'default'  => true,
		),

		array(
			'id'       => 'post_ssid',
			'type'     => 'switcher',
			'title'    => '后台显示文章ID',
			'default'  => true,
		),

		array(
			'id'       => 'clone_post',
			'type'     => 'switcher',
			'title'    => '后台复制文章',
		),

		array(
			'id'       => 'xmlrpc_no',
			'type'     => 'switcher',
			'title'    => '禁用 xmlrpc',
			'default'  => true,
		),

		array(
			'id'       => 'script_defer',
			'type'     => 'switcher',
			'title'    => '延迟加载脚本',
		),

		array(
			'id'       => 'embed_no',
			'type'     => 'switcher',
			'title'    => '禁用oEmbed',
			'default'  => true,
		),

		array(
			'id'       => 'revisions_no',
			'type'     => 'switcher',
			'title'    => '禁止修订版本',
			'default'  => true,
			'after'    => '<span class="after-perch">效果不太好</span>',
		),

		array(
			'id'       => 'disable_api',
			'type'     => 'switcher',
			'title'    => '禁用 REST API',
			'after'    => '<span class="after-perch">使用区块编辑器、连接小程序需取消</span>',
		),

		array(
			'id'       => 'x-frame',
			'type'     => 'switcher',
			'title'    => '禁止被 iframe 网页嵌套',
		),

		array(
			'id'       => 'be_safety',
			'type'     => 'switcher',
			'title'    => '阻止恶意URL请求',
		),

		array(
			'id'       => 'forget_password',
			'type'     => 'switcher',
			'title'    => '修正QQ邮箱密码链接',
			'default'  => true,
		),

		array(
			'id'       => 'delete_enclosure',
			'type'     => 'switcher',
			'title'    => '禁止添加 enclosed 字段',
			'after'    => '<span class="after-perch">酌情</span>',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'basic_setting',
	'title'       => '侧边栏小工具',
	'icon'        => '',
	'description' => '与侧边栏小工具相关的设置',
	'fields'      => array(

		array(
			'id'       => 'sidebar_sticky',
			'type'     => 'switcher',
			'title'    => '侧边栏跟随滚动',
			'default'  => true,
		),

		array(
			'id'       => 'widget_logic',
			'type'     => 'switcher',
			'title'    => '小工具条件判断',
			'default'  => true,
		),

		array(
			'id'       => 'clone_widgets',
			'type'     => 'switcher',
			'title'    => '小工具克隆',
			'default'  => true,
		),

		array(
			'id'       => 'single_e',
			'type'     => 'switcher',
			'title'    => '正文底部小工具',
		),

		array(
			'id'      => 'single_e_f',
			'class'    => 'be-child-item be-child-last-item',
			'type'    => 'radio',
			'title'   => '分栏',
			'inline'  => true,
			'options' => $swf12,
			'default' => '2',
		),

		array(
			'id'       => 'header_widget',
			'type'     => 'switcher',
			'title'    => '头部小工具',
		),

		array(
			'id'       => 'h_widget_p',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '移动端不显示',
		),

		array(
			'id'      => 'h_widget_m',
			'class'    => 'be-child-item',
			'type'    => 'radio',
			'title'   => '显示位置',
			'inline'  => true,
			'options' => array(
				'cat_single_m' => '在分类及正文页面显示',
				'cat_m'        => '仅在分类页面显示',
				'all_m'        => '全局显示',
			),
			'default' => 'cat_single_m',
		),

		array(
			'id'      => 'header_widget_f',
			'class'    => 'be-child-item',
			'type'    => 'radio',
			'title'   => '分栏',
			'inline'  => true,
			'options' => $fl34,
			'default' => '4',
		),

		array(
			'id'       => 'footer_w',
			'type'     => 'switcher',
			'title'    => '页脚小工具',
			'default'  => true,
		),

		array(
			'id'       => 'mobile_footer_w',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '移动端不显示',
		),

		array(
			'id'      => 'footer_w_f',
			'class'    => 'be-child-item',
			'type'    => 'radio',
			'title'   => '分栏',
			'inline'  => true,
			'options' => $fl3456,
			'default' => '3',
		),

		array(
			'id'       => 'footer_contact',
			'type'     => 'switcher',
			'title'    => '右侧固定内容',
		),

		array(
			'id'        => 'footer_contact_html',
			'class'    => 'be-child-item',
			'type'      => 'textarea',
			'title'     => '输入内容，支持HTML代码',
			'sanitize'  => false,
		),


		array(
			'id'       => 'footer_widget_img',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'upload',
			'title'    => '页脚小工具背景图片',
			'default'  => $imgdefault . '/options/1200.jpg',
			'preview'  => true,
		),

		array(
			'id'       => 'widget_p',
			'type'     => 'number',
			'title'    => '文章小工具段落插入位置',
			'after'    => '<span class="after-perch">在第几个段后</span>',
			'default'  => 3,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'basic_setting',
	'title'       => '自定义文章显示数',
	'icon'        => '',
	'description' => '一般用于使用图片布局的分类或标签，自定义文章显示数',
	'fields'      => array(

		array(
			'id'       => 'cat_posts_id',
			'type'     => 'text',
			'title'    => '输入分类ID',
			'after'    => $mid,
		),

		array(
			'id'       => 'posts_n',
			'type'     => 'number',
			'title'    => '显示数',
			'default'  => 20,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'basic_setting',
	'title'       => '分类设置',
	'icon'        => '',
	'description' => '与分类相关的一些设置',
	'fields'      => array(

		array(
			'id'       => 'cat_top',
			'type'     => 'switcher',
			'title'    => '显示分类推荐文章',
		),

		array(
			'id'       => 'no_child',
			'type'     => 'switcher',
			'title'    => '分类归档不显示子分类文章',
		),

		array(
			'id'       => 'select_templates',
			'type'     => 'switcher',
			'title'    => '选择分类模板',
		),

		array(
			'id'       => 'cat_order',
			'type'     => 'switcher',
			'title'    => '分类排序',
			'default'  => true,
		),

		array(
			'id'       => 'cat_icon',
			'type'     => 'switcher',
			'title'    => '分类图标',
		),

		array(
			'id'       => 'cat_cover',
			'type'     => 'switcher',
			'title'    => '分类封面',
			'default'  => true,
		),

		array(
			'id'       => 'child_cat',
			'type'     => 'switcher',
			'title'    => '在分类归档页显示父子分类链接',
		),

		array(
			'id'      => 'child_cat_f',
			'class'    => 'be-child-item',
			'type'    => 'radio',
			'title'   => '分栏',
			'inline'  => true,
			'options' => $fl789,
			'default' => '8',
		),

		array(
			'id'       => 'child_cat_no',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '输入排除的分类ID',
			'after'    => $mid,
		),

		array(
			'id'       => 'child_cat_exclude',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '同级分类排除本身',
			'default'  => true,
		),

		array(
			'id'       => 'cat_des',
			'type'     => 'switcher',
			'title'    => '分类图片',
			'after'    => '<span class="after-perch">分类填写描述才能显示</span>',
			'default'  => true,
		),

		array(
			'id'       => 'cat_des_img_d',
			'class'    => 'be-child-item',
			'type'     => 'upload',
			'title'    => '默认图片',
			'default'  => $imgdefault . '/options/1200.jpg',
			'after'    => '用于未单独设置分类图片的分类',
			'preview'  => true,
		),

		array(
			'id'       => 'cat_des_p',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '显示描述',
		),

		array(
			'id'       => 'cat_area',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '单独显示描述',
		),

		array(
			'id'       => 'cat_des_img',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '自动裁剪图片',
		),

		array(
			'id'       => 'des_title_l',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '标题居左',
		),

		array(
			'id'       => 'header_title_narrow',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '不显示标题',
		),

		array(
			'id'       => 'cat_des_img_zoom',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'switcher',
			'title'    => '移动端图片缩放',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'basic_setting',
	'title'       => '正文标签文章',
	'icon'        => '',
	'description' => '用AJAX加载标签文章替换文章末尾默认标签',
	'fields'      => array(

		array(
			'id'       => 'single_tab_tags',
			'type'     => 'switcher',
			'title'    => '启用',
			'default'  => true,
		),

		array(
			'id'       => 'single_tab_tags_n',
			'type'     => 'number',
			'title'    => '篇数',
			'default'  => 4,
		),

		array(
			'id'      => 'single_tab_tags_order',
			'type'    => 'radio',
			'title'   => '文章排序',
			'inline'  => true,
			'options' => array(
				'date'   => '时间',
				'rand'    => '随机',
			),
			'default' => 'rand',
		),

		array(
			'id'      => 'single_tab_tags_style',
			'type'    => 'radio',
			'title'   => '显示模式',
			'inline'  => true,
			'options' => array(
				'photo'   => '图片',
				'grid'    => '卡片',
				'list'    => '列表',
			),
			'default' => 'photo',
		),

		array(
			'id'      => 'single_tab_tags_f',
			'type'    => 'radio',
			'title'   => '分栏',
			'inline'  => true,
			'options' => $fl245,
			'default' => '4',
		),

	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'basic_setting',
	'title'       => '正文上下篇文章链接',
	'icon'        => '',
	'description' => '设置上下篇文章链接模式',
	'fields'      => array(

		array(
			'id'      => 'post_nav_mode',
			'type'    => 'radio',
			'title'   => '显示模式',
			'inline'  => true,
			'options' => array(
				'full_site' => '全站',
				'same_cat'  => '同分类',
			),
			'default' => 'full_site',
		),

		array(
			'id'       => 'post_nav_img',
			'type'     => 'switcher',
			'title'    => '显示缩略图',
			'default'  => true,
		),

		array(
			'id'       => 'post_nav_no',
			'type'     => 'switcher',
			'title'    => '不显示该模块',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'basic_setting',
	'title'       => '正文底部滚动同分类文章',
	'icon'        => '',
	'description' => '以图片形式在正文底部滚动显示同分类文章',
	'fields'      => array(

		array(
			'id'       => 'single_rolling',
			'type'     => 'switcher',
			'title'    => '正文底部滚动同分类文章',
			'default'  => true,
		),

		array(
			'id'       => 'single_rolling_n',
			'type'     => 'number',
			'title'    => '篇数',
			'default'  => 10,
		),

		array(
			'id'      => 'not_single_rolling_cat',
			'type'    => 'checkbox',
			'title'   => '排除的分类',
			'inline'  => true,
			'options' => 'categories',
			'query_args' => array(
				'orderby'  => 'ID',
				'order'    => 'ASC',
			),
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'basic_setting',
	'title'       => '正文相关文章',
	'icon'        => '',
	'description' => '在正文底部显示相同标签的文章',
	'fields'      => array(

		array(
			'id'      => 'related_img',
			'type'    => 'radio',
			'title'   => '显示位置',
			'inline'  => true,
			'options' => array(
				'related_no'      => '不显示',
				'related_inside'  => '显示在文章中',
				'related_outside' => '显示在文章下面'
			),
			'default' => 'related_inside',
		),

		array(
			'id'      => 'related_orderby',
			'type'    => 'radio',
			'title'   => '排序',
			'inline'  => true,
			'options' => array(
				'related_date'     => '发表时间',
				'related_rand'     => '随机显示',
				'related_modified' => '最后更新时间'
			),
			'default' => 'related_date',
		),

		array(
			'id'      => 'related_mode',
			'type'    => 'radio',
			'title'   => '显示模式',
			'inline'  => true,
			'options' => array(
				'related_normal' => '标准',
				'slider_grid'    => '图片',
			),
			'default' => 'slider_grid',
		),

		array(
			'id'       => 'related_n',
			'type'     => 'number',
			'title'    => '篇数',
			'default'  => 4,
		),

		array(
			'id'      => 'not_related_cat',
			'type'    => 'checkbox',
			'title'   => '排除的分类',
			'inline'  => true,
			'options' => 'categories',
			'query_args' => array(
				'orderby'  => 'ID',
				'order'    => 'ASC',
			),
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'basic_setting',
	'title'       => '自定义分类法文章显示数',
	'icon'        => '',
	'description' => '自定义图片、视频、商品、产品、网址文章归档及页面显示文章数',
	'fields'      => array(

		array(
			'id'       => 'type_posts_n',
			'type'     => 'number',
			'title'    => '归档篇数',
			'default'  => 20,
		),

		array(
			'id'       => 'type_cat',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'switcher',
			'title'    => '显示该类型所有分类链接',
			'default'  => true,
		),

		array(
			'id'       => 'custom_cat_n',
			'type'     => 'number',
			'title'    => '页面篇数',
			'default'  => 12,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'basic_setting',
	'title'       => '最新文章图标',
	'icon'        => '',
	'description' => '最新文章图标设置',
	'fields'      => array(

		array(
			'id'       => 'news_ico',
			'type'     => 'switcher',
			'title'    => '最新文章图标',
			'default'  => true,
		),

		array(
			'id'       => 'news_date',
			'type'     => 'switcher',
			'title'    => '突出最新文章日期',
			'default'  => true,
		),

		array(
			'id'       => 'new_n',
			'type'     => 'number',
			'title'    => '显示时限',
			'default'  => 168,
			'after'    => '<span class="after-perch">小时，默认一周（168小时）内发表的文章显示，最短24小时</span>',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'basic_setting',
	'title'       => '友情链接页面',
	'icon'        => '',
	'description' => '友情链接页面设置',
	'fields'      => array(

		array(
			'id'       => 'add_link',
			'type'     => 'switcher',
			'title'    => '自助友情链接',
			'default'  => true,
		),

		array(
			'id'       => 'site_inks_des',
			'type'     => 'switcher',
			'title'    => '显示描述',
		),

		array(
			'id'       => 'inks_adorn',
			'type'     => 'switcher',
			'title'    => '装饰动画',
			'default'  => true,
		),

		array(
			'id'      => 'links_model',
			'type'    => 'radio',
			'title'   => '显示模式',
			'inline'  => true,
			'options' => array(
				'links_ico'     => '图标模式',
				'links_default' => '默认模式',
			),
			'default' => 'links_ico',
		),

		array(
			'id'      => 'link_favicon',
			'type'    => 'radio',
			'title'   => '图标模式选择',
			'inline'  => true,
			'options' => array(
				'favicon_ico' => 'Favicon图标',
				'first_ico'   => '首字图标',
			),
			'default' => 'favicon_ico',
		),

		array(
			'id'      => 'rand_link',
			'type'    => 'radio',
			'title'   => '图标模式排序',
			'inline'  => true,
			'options' => $rand_link, 
			'default' => 'rating',
		),

		array(
			'id'      => 'links_img_txt',
			'type'    => 'radio',
			'title'   => '默认模式选择',
			'inline'  => true,
			'options' => $inks_img_txt,
			'default' => '0',
		),

		array(
			'id'       => 'link_cat',
			'type'     => 'text',
			'title'    => '输入排除的链接ID',
			'after'    => $mid,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'basic_setting',
	'title'       => '网站Favicon图标API',
	'icon'        => '',
	'description' => '设置获取网站Favicon图标API',
	'fields'      => array(

		array(
			'id'       => 'favicon_api',
			'type'     => 'text',
			'title'    => '获取图标API地址',
			'default' => 'https://favicon.cccyun.cc/',
			'after'    => '输入获取网站favicon图标API地址，默认：https://favicon.cccyun.cc/',
		),
	)
));

CSF::createSection( $prefix, array(
	'id'    => 'menu_setting',
	'title' => '菜单设置',
	'icon'        => 'dashicons dashicons-menu-alt',
) );

CSF::createSection( $prefix, array(
	'parent'      => 'menu_setting',
	'title'       => '菜单外观',
	'icon'        => '',
	'description' => '设置菜单外观样式及显示模式',
	'fields'      => array(

		array(
			'id'      => 'menu_m',
			'type'    => 'radio',
			'title'   => '导航菜单固定模式',
			'inline'  => true,
			'options' => array(
				'menu_d' => '正常模式',
				'menu_n' => '永不固定',
				'menu_g' => '保持固定',
			),
			'default' => 'menu_d',
		),

		array(
			'class'    => 'be-parent-title',
			'type'     => 'subheading',
			'content'  => '主要菜单样式',
		),

		array(
			'id'       => 'menu_block',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '色块模式',
			'default'  => true,
		),

		array(
			'id'       => 'nav_ace',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '文字加粗',
			'default'  => true,
		),

		array(
			'id'       => 'menu_glass',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '半透明',
			'default'  => true,
		),

		array(
			'id'       => 'site_nav_left',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'switcher',
			'title'    => '居左',
			'default'  => true,
		),

		array(
			'id'       => 'top_nav_show',
			'type'     => 'switcher',
			'title'    => '顶部菜单及站点管理',
			'default'  => true,
		),

		array(
			'id'       => 'nav_extend',
			'type'     => 'switcher',
			'title'    => '伸展菜单',
			'default'  => true,
		),

		array(
			'id'       => 'nav_width',
			'class'    => 'be-child-item',
			'type'     => 'number',
			'title'    => '伸展菜单宽度',
			'after'    => '<span class="after-perch">px 默认1250，不使用自定义宽度请留空</span>',
		),

		array(
			'id'       => 'nav_full_width',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'switcher',
			'title'    => '100%宽',
		),

		array(
			'id'       => 'subjoin_menu',
			'type'     => 'switcher',
			'title'    => '附加菜单',
			'default'  => true,
		),

		array(
			'id'       => 'mega_menu',
			'type'     => 'switcher',
			'title'    => '超级菜单',
			'default'  => true,
		),

		array(
			'id'       => 'select_menu',
			'type'     => 'switcher',
			'title'    => '选择菜单',
			'default'  => true,
		),

		array(
			'id'       => 'assign_menus',
			'type'     => 'switcher',
			'title'    => '指派菜单',
			'default'  => true,
			'after'    => '<span class="after-perch">为每个页面<a href="' . home_url() . '/wp-admin/nav-menus.php?action=locations" target="_blank"><b> 设置 </b></a>不同的菜单</span>',
		),

		array(
			'id'       => 'menu_visibility',
			'type'     => 'switcher',
			'title'    => '菜单条件判断',
			'default'  => true,
		),

		array(
			'id'       => 'menu_des',
			'type'     => 'switcher',
			'title'    => '二级菜单显示描述',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'menu_setting',
	'title'       => '通用头部模式',
	'icon'        => '',
	'description' => '网站名称在上，主菜单在下的一种导航菜单模式，比较适合在桌面端使用',
	'fields'      => array(

		array(
			'id'       => 'header_normal',
			'type'     => 'switcher',
			'title'    => '通用头部模式',
		),

		array(
			'id'      => 'h_main_o',
			'type'    => 'radio',
			'title'   => '右侧显示',
			'inline'  => true,
			'options' => array(
				'h_search'  => '搜索框',
				'h_contact' => '自定义内容',
			),
			'default' => 'h_search',
		),

		array(
			'id'       => 'logo_box_height',
			'type'     => 'number',
			'title'    => '头部高度',
			'after'    => '<span class="after-perch">px，默认80</span>',
			'default'  => 80,
		),

		array(
			'id'      => 'header_color',
			'type'    => 'color',
			'title'   => '背景颜色',
			'default' => '#ffffff',
		),

		array(
			'id'        => 'header_contact',
			'type'      => 'textarea',
			'title'     => '自定义内容',
			'sanitize'  => false,
		),

		array(
			'id'       => 'top_bg',
			'type'     => 'upload',
			'title'    => '背景图片',
			'default'  => $imgdefault . '/options/1200.jpg',
			'preview'  => true,
		),

		array(
			'id'      => 'top_bg_m',
			'class'    => 'be-child-item be-child-last-item',
			'type'    => 'radio',
			'title'   => '图片显示模式',
			'inline'  => true,
			'options' => array(
				'repeat_x' => '重复显示',
				'repeat_y' => '不重复显示',
			),
			'default' => 'repeat_x',
		),

	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'menu_setting',
	'title'       => '移动端菜单',
	'icon'        => '',
	'description' => '设置移动端菜单',
	'fields'      => array(

		array(
			'id'       => 'footer_menu',
			'type'     => 'switcher',
			'title'    => '移动端页脚菜单',
			'default'  => true,
		),

		array(
			'id'       => 'footer_menu_no',
			'type'     => 'switcher',
			'title'    => '移动端页脚菜单自动隐藏',
			'default'  => true,
		),

		array(
			'id'       => 'nav_weixin_on',
			'type'     => 'switcher',
			'title'    => '移动端页脚菜单微信',
			'default'  => true,
		),

		array(
			'id'       => 'mobile_nav',
			'type'     => 'switcher',
			'title'    => '移动端菜单与PC端不同',
		),

		array(
			'id'       => 'm_nav',
			'type'     => 'switcher',
			'title'    => '单独的移动端菜单',
			'after'    => '<span class="after-perch">不能有二级菜单，有特殊需要时启用</span>',
		),

		array(
			'id'       => 'nav_weixin_img',
			'type'     => 'upload',
			'title'    => '微信二维码图片',
			'default'  => $imgpath . '/favicon.png',
			'preview'  => true,
		),

		array(
			'id'       => 'nav_no',
			'type'     => 'switcher',
			'title'    => '移动端导航按钮链接到页面',
		),

		array(
			'id'          => 'nav_url',
			'class'    => 'be-child-item be-child-last-item',
			'type'        => 'select',
			'title'       => '选择页面',
			'placeholder' => '选择页面',
			'options'     => 'pages',
			'query_args'  => array(
				'posts_per_page' => -1
			)
		),
	)
));

CSF::createSection( $prefix, array(
	'id'    => 'thumbnail_setting',
	'title' => '缩略图',
	'icon'        => 'dashicons dashicons-format-image',
) );

CSF::createSection( $prefix, array(
	'parent'      => 'thumbnail_setting',
	'title'       => '缩略图',
	'icon'        => '',
	'description' => '缩略图方式及大小比例',
	'fields'      => array(

		array(
			'id'      => 'img_way',
			'type'    => 'radio',
			'title'   => '缩略图方式',
			'inline'  => true,
			'options' => array(
				'd_img'    => '默认缩略图',
				'o_img'    => '阿里云OSS',
				'q_img'    => '七牛云',
				'upyun'    => '又拍云',
				'cos_img'  => '腾讯COS',
				'no_thumb' => '不裁剪',
			),
			'default' => 'no_thumb',
		),

		array(
			'class'    => 'be-parent-title',
			'type'     => 'subheading',
			'content'  => '自动裁剪设置',
		),

		array(
			'id'      => 'crop_top',
			'class'    => 'be-child-item',
			'type'    => 'radio',
			'inline'  => true,
			'title'   => '缩略裁剪位置',
			'options' => $test_array,
			'default' => '',
		),

		array(
			'class'    => 'be-parent-title',
			'type'     => 'subheading',
			'content'  => '标准缩略图',
		),

		array(
			'id'       => 'img_w',
			'class'    => 'be-child-item be-child-number',
			'type'     => 'number',
			'title'    => '宽',
			'after'    => '<span class="after-perch">默认280</span>',
			'default'  => 280,
		),

		array(
			'id'       => 'img_h',
			'class'    => 'be-child-item be-child-last-number',
			'type'     => 'number',
			'title'    => '高',
			'after'    => '<span class="after-perch">默认210</span>',
			'default'  => 210,
		),

		array(
			'class'    => 'be-parent-title',
			'type'     => 'subheading',
			'content' => '杂志分类模块缩略图',
		),

		array(
			'id'       => 'img_k_w',
			'class'    => 'be-child-item be-child-number',
			'type'     => 'number',
			'title'    => '宽',
			'after'    => '<span class="after-perch">默认560</span>',
			'default'  => 560,
		),

		array(
			'id'       => 'img_k_h',
			'class'    => 'be-child-item be-child-last-number',
			'type'     => 'number',
			'title'    => '高',
			'after'    => '<span class="after-perch">默认230</span>',
			'default'  => 230,
		),

		array(
			'class'    => 'be-parent-title',
			'type'     => 'subheading',
			'content' => '图片布局缩略图',
		),

		array(
			'id'       => 'grid_w',
			'class'    => 'be-child-item be-child-number',
			'type'     => 'number',
			'title'    => '宽',
			'after'    => '<span class="after-perch">默认280</span>',
			'default'  => 280,
		),

		array(
			'id'       => 'grid_h',
			'class'    => 'be-child-item be-child-last-number',
			'type'     => 'number',
			'title'    => '高',
			'after'    => '<span class="after-perch">默认210</span>',
			'default'  => 210,
		),

		array(
			'class'    => 'be-parent-title',
			'type'     => 'subheading',
			'content' => '图片缩略图',
		),

		array(
			'id'       => 'img_i_w',
			'class'    => 'be-child-item be-child-number',
			'type'     => 'number',
			'title'    => '宽',
			'after'    => '<span class="after-perch">默认280</span>',
			'default'  => 280,
		),

		array(
			'id'       => 'img_i_h',
			'class'    => 'be-child-item be-child-last-number',
			'type'     => 'number',
			'title'    => '高',
			'after'    => '<span class="after-perch">默认210</span>',
			'default'  => 210,
		),

		array(
			'class'    => 'be-parent-title',
			'type'     => 'subheading',
			'content' => '视频缩略图',
		),

		array(
			'id'       => 'img_v_w',
			'class'    => 'be-child-item be-child-number',
			'type'     => 'number',
			'title'    => '宽',
			'after'    => '<span class="after-perch">默认280</span>',
			'default'  => 280,
		),

		array(
			'id'       => 'img_v_h',
			'class'    => 'be-child-item be-child-last-number',
			'type'     => 'number',
			'title'    => '高',
			'after'    => '<span class="after-perch">默认210</span>',
			'default'  => 210,
		),

		array(
			'class'    => 'be-parent-title',
			'type'     => 'subheading',
			'content' => '商品缩略图',
		),

		array(
			'id'       => 'img_t_w',
			'class'    => 'be-child-item be-child-number',
			'type'     => 'number',
			'title'    => '宽',
			'after'    => '<span class="after-perch">默认400</span>',
			'default'  => 400,
		),

		array(
			'id'       => 'img_t_h',
			'class'    => 'be-child-item be-child-last-number',
			'type'     => 'number',
			'title'    => '高',
			'after'    => '<span class="after-perch">默认400</span>',
			'default'  => 400,
		),

		array(
			'class'    => 'be-parent-title',
			'type'     => 'subheading',
			'content' => '首页幻灯',
		),

		array(
			'id'       => 'img_h_w',
			'class'    => 'be-child-item be-child-number',
			'type'     => 'number',
			'title'    => '宽',
			'after'    => '<span class="after-perch">默认800</span>',
			'default'  => 800,
		),

		array(
			'id'       => 'img_h_h',
			'class'    => 'be-child-item be-child-last-number',
			'type'     => 'number',
			'title'    => '高',
			'after'    => '<span class="after-perch">默认300</span>',
			'default'  => 300,
		),

		array(
			'class'    => 'be-parent-title',
			'type'     => 'subheading',
			'content' => '幻灯小工具',
		),

		array(
			'id'       => 'img_s_w',
			'class'    => 'be-child-item be-child-number',
			'type'     => 'number',
			'title'    => '宽',
			'after'    => '<span class="after-perch">默认350</span>',
			'default'  => 350,
		),

		array(
			'id'       => 'img_s_h',
			'class'    => 'be-child-item be-child-last-number',
			'type'     => 'number',
			'title'    => '高',
			'after'    => '<span class="after-perch">默认260</span>',
			'default'  => 260,
		),

		array(
			'class'    => 'be-parent-title',
			'type'     => 'subheading',
			'content' => '分类宽图',
		),

		array(
			'id'       => 'img_full_w',
			'class'    => 'be-child-item be-child-number',
			'type'     => 'number',
			'title'    => '宽',
			'after'    => '<span class="after-perch">默认900</span>',
			'default'  => 900,
		),

		array(
			'id'       => 'img_full_h',
			'class'    => 'be-child-item be-child-last-number',
			'type'     => 'number',
			'title'    => '高',
			'after'    => '<span class="after-perch">默认350</span>',
			'default'  => 350,
		),

		array(
			'class'    => 'be-parent-title',
			'type'     => 'subheading',
			'content' => '网址缩略图',
		),

		array(
			'id'       => 'sites_w',
			'class'    => 'be-child-item be-child-number',
			'type'     => 'number',
			'title'    => '宽',
			'after'    => '<span class="after-perch">默认280</span>',
			'default'  => 280,
		),

		array(
			'id'       => 'sites_h',
			'class'    => 'be-child-item be-child-last-number',
			'type'     => 'number',
			'title'    => '高',
			'after'    => '<span class="after-perch">默认210</span>',
			'default'  => 210,
		),

		array(
			'class'    => 'be-parent-title',
			'type'     => 'subheading',
			'content' => '分类图片',
		),

		array(
			'id'       => 'img_des_w',
			'class'    => 'be-child-item be-child-number',
			'type'     => 'number',
			'title'    => '宽',
			'after'    => '<span class="after-perch">默认1200</span>',
			'default'  => 1200,
		),

		array(
			'id'       => 'img_des_h',
			'class'    => 'be-child-item be-child-last-number',
			'type'     => 'number',
			'title'    => '高',
			'after'    => '<span class="after-perch">默认250</span>',
			'default'  => 250,
		),

		array(
			'class'    => 'be-parent-title',
			'type'     => 'subheading',
			'content'  => '不裁剪显示比例',
		),

		array(
			'class'    => 'be-child-item',
			'type'    => 'content',
			'content' => '不使用自定义请留空',
		),

		array(
			'id'       => 'img_bl',
			'class'    => 'be-child-item',
			'type'     => 'number',
			'title'    => '标准缩略图',
			'after'    => '<span class="after-perch">默认75</span>',
		),

		array(
			'id'       => 'img_k_bl',
			'class'    => 'be-child-item',
			'type'     => 'number',
			'title'    => '杂志分类模块缩略图',
			'after'    => '<span class="after-perch">默认41</span>',
		),

		array(
			'id'       => 'grid_bl',
			'class'    => 'be-child-item',
			'type'     => 'number',
			'title'    => '图片布局缩略图',
			'after'    => '<span class="after-perch">默认75</span>',
		),

		array(
			'id'       => 'img_v_bl',
			'class'    => 'be-child-item',
			'type'     => 'number',
			'title'    => '视频缩略图',
			'after'    => '<span class="after-perch">默认75</span>',
		),

		array(
			'id'       => 'img_t_bl',
			'class'    => 'be-child-item',
			'type'     => 'number',
			'title'    => '商品缩略图',
			'after'    => '<span class="after-perch">默认100</span>',
		),

		array(
			'id'       => 'img_s_bl',
			'class'    => 'be-child-item',
			'type'     => 'number',
			'title'    => '幻灯小工具',
			'after'    => '<span class="after-perch">默认75</span>',
		),

		array(
			'id'       => 'img_l_bl',
			'class'    => 'be-child-item',
			'type'     => 'number',
			'title'    => '横向滚动',
			'after'    => '<span class="after-perch">默认75</span>',
		),

		array(
			'id'       => 'img_full_bl',
			'class'    => 'be-child-item',
			'type'     => 'number',
			'title'    => '分类宽图',
			'after'    => '<span class="after-perch">默认33.3</span>',
		),

		array(
			'id'       => 'sites_bl',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'number',
			'title'    => '网址缩略图',
			'after'    => '<span class="after-perch">默认75</span>',
		),

		array(
			'id'       => 'fall_width',
			'type'     => 'number',
			'title'    => '瀑布流',
			'after'    => '<span class="after-perch">默认190，当调整了页面宽度或者调整分栏，修改这个值，直至两侧对齐</span>',
			'default'  => 190,
		),

		array(
			'class'    => 'be-parent-title',
			'type'     => 'subheading',
			'content'  => '限制文章列表缩略图',
		),

		array(
			'id'       => 'thumbnail_width',
			'class'    => 'be-child-item',
			'type'     => 'number',
			'title'    => '缩略图最大宽度',
			'after'    => '<span class="after-perch">默认值200</span>',
		),

		array(
			'id'       => 'meta_left',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'number',
			'title'    => '调整信息位置',
			'after'    => '<span class="after-perch">默认距左240</span>',
		),

		array(
			'id'       => 'wp_thumbnails',
			'type'     => 'switcher',
			'title'    => '特色图片',
			'after'    => '<span class="after-perch">如不使用该功能请不要开启</span>',
		),

		array(
			'id'       => 'clipping_thumbnails',
			'type'     => 'switcher',
			'title'    => '特色图片自动裁剪',
		),

		array(
			'id'       => 'disable_img_sizes',
			'type'     => 'switcher',
			'title'    => '禁止WP自动裁剪图片',
			'default'  => true,
		),

		array(
			'id'       => 'manual_thumbnail',
			'type'     => 'switcher',
			'title'    => '手动缩略图自动裁剪',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'thumbnail_setting',
	'title'       => '随机缩略图',
	'icon'        => '',
	'description' => '设置随机缩略图',
	'fields'      => array(

		array(
			'id'       => 'no_rand_img',
			'type'     => 'switcher',
			'title'    => '文章中无图，不显示随机缩略图',
			'default'  => true,
		),

		array(
			'id'       => 'clipping_rand_img',
			'type'     => 'switcher',
			'title'    => '自动裁剪',
		),

		array(
			'id'       => 'rand_img_n',
			'type'     => 'number',
			'title'    => '随机图数量',
			'after'    => '<span class="after-perch">默认5</span>',
			'default'  => 5,
		),

		array(
			'id'    => 'random_image_url',
			'type'  => 'textarea',
			'title' => '标准随机缩略图链接',
			'after'    => '多张图片中间用英文半角逗号","隔开',
			'default'  => $imgdefault . '/random/320.jpg',
		),

		array(
			'id'    => 'random_long_url',
			'type'  => 'textarea',
			'title' => '分类模块随机缩略图链接',
			'after'    => '多张图片中间用英文半角逗号","隔开',
			'default'  => $imgdefault . '/random/560.jpg',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'thumbnail_setting',
	'title'       => '图片本地化',
	'icon'        => '',
	'description' => '将文章中外链图片自动下载到本地，有些外链图片（如头条），需要切换到“文本”编辑模式，更新发表。',
	'fields'      => array(

		array(
			'id'       => 'save_image',
			'type'     => 'switcher',
			'title'    => '外链图片自动本地化',
			'after'    => '<span class="after-perch">酌情开启</span>',
		),
	)
));

CSF::createSection( $prefix, array(
	'title'       => '分类模板',
	'icon'        => 'dashicons dashicons-category',
	'description' => '选择分类模板，让分类显示不同的外观布局',
	'fields'      => array(

		array(
			'id'          => 'default_cat_template',
			'type'        => 'select',
			'title'       => '分类默认模板',
			'placeholder' => '',
			'options'     => $options_select_template,
		),

		array(
			'id'          => 'default_tag_template',
			'type'        => 'select',
			'title'       => '标签默认模板',
			'placeholder' => '',
			'options'     => $options_select_template_tag,
		),

		array(
			'class'    => 'be-parent-title',
			'type'     => 'subheading',
			'content'  => '分类Ajax模板选择',
		),

		array(
			'class'    => 'be-child-item',
			'type'    => 'content',
			'content' => '输入分类ID，不可重复添加',
		),

		array(
			'id'       => 'ajax_layout_code_a',
			'class'    => 'be-normal-item',
			'type'     => 'text',
			'title'    => 'Ajax图片布局',
			'after'    => $idcat,
		),

		array(
			'id'      => 'ajax_layout_code_a_f',
			'class'    => 'be-child-item',
			'type'    => 'radio',
			'title'   => '分栏',
			'inline'  => true,
			'options' => $fl456,
			'default' => '5',
		),

		array(
			'id'       => 'ajax_layout_code_a_n',
			'class'    => 'be-child-item',
			'type'     => 'number',
			'title'    => '每页篇数',
			'default'  => '15',
			'after'    => $anh,
		),

		array(
			'id'       => 'ajax_layout_code_a_r',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '侧边栏',
		),

		array(
			'id'       => 'ajax_layout_code_a_btn',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '子分类按钮',
			'default'  => true,
		),

		array(
			'id'      => 'ajax_layout_code_a_img',
			'class'   => 'be-child-item',
			'type'    => 'radio',
			'title'   => '缩略图',
			'inline'  => true,
			'options' => array(
				'0'   => '正常',
				'1'   => '图片',
			),
			'default' => '0',
		),

		array(
			'id'      => 'ajax_code_a_orderby',
			'class'   => 'be-child-item',
			'type'    => 'radio',
			'title'   => '文章排序',
			'inline'  => true,
			'options' => $ajax_orderby,
			'default' => 'date',
		),

		array(
			'id'      => 'nav_btn_a',
			'class'   => 'be-child-item',
			'type'    => 'radio',
			'title'   => '翻页模式',
			'inline'  => true,
			'options' => array(
				'true'   => '数字翻页',
				'more'   => '更多按钮',
			),
			'default' => 'true',
		),

		array(
			'id'      => 'more_infinite_a',
			'class'   => 'be-child-item be-child-last-item',
			'type'    => 'radio',
			'title'   => '更多按钮滚动加载',
			'inline'  => true,
			'options' => array(
				'false'  => '否',
				'true'   => '是',
			),
			'default' => 'false',
		),

		array(
			'id'       => 'ajax_layout_code_b',
			'type'     => 'text',
			'title'    => 'Ajax卡片布局',
			'after'    => $idcat,
		),

		array(
			'id'      => 'ajax_layout_code_b_f',
			'class'    => 'be-child-item',
			'type'    => 'radio',
			'title'   => '分栏',
			'inline'  => true,
			'options' => $fl1234,
			'default' => '3',
		),

		array(
			'id'       => 'ajax_layout_code_b_n',
			'class'    => 'be-child-item',
			'type'     => 'number',
			'title'    => '每页篇数',
			'default'  => '12',
			'after'    => $anh,
		),

		array(
			'id'       => 'ajax_layout_code_b_r',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '侧边栏',
		),

		array(
			'id'       => 'ajax_layout_code_b_btn',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '子分类按钮',
			'default'  => true,
		),

		array(
			'id'      => 'ajax_code_b_orderby',
			'class'   => 'be-child-item',
			'type'    => 'radio',
			'title'   => '文章排序',
			'inline'  => true,
			'options' => $ajax_orderby,
			'default' => 'date',
		),

		array(
			'id'      => 'nav_btn_b',
			'class'   => 'be-child-item',
			'type'    => 'radio',
			'title'   => '翻页模式',
			'inline'  => true,
			'options' => array(
				'true'   => '数字翻页',
				'more'   => '更多按钮',
			),
			'default' => 'true',
		),

		array(
			'id'      => 'more_infinite_b',
			'class'   => 'be-child-item be-child-last-item',
			'type'    => 'radio',
			'title'   => '更多按钮滚动加载',
			'inline'  => true,
			'options' => array(
				'false'  => '否',
				'true'   => '是',
			),
			'default' => 'false',
		),

		array(
			'id'       => 'ajax_layout_code_c',
			'type'     => 'text',
			'title'    => 'Ajax标题布局',
			'after'    => $idcat,
		),

		array(
			'id'      => 'ajax_layout_code_c_f',
			'class'    => 'be-child-item',
			'type'    => 'radio',
			'title'   => '分栏',
			'inline'  => true,
			'options' => $fl1234,
			'default' => '3',
		),

		array(
			'id'       => 'ajax_layout_code_c_n',
			'class'    => 'be-child-item',
			'type'     => 'number',
			'title'    => '每页篇数',
			'default'  => '12',
			'after'    => $anh,
		),

		array(
			'id'       => 'ajax_layout_code_c_r',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '侧边栏',
		),

		array(
			'id'       => 'ajax_layout_code_c_btn',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '子分类按钮',
			'default'  => true,
		),

		array(
			'id'      => 'ajax_code_c_orderby',
			'class'   => 'be-child-item',
			'type'    => 'radio',
			'title'   => '文章排序',
			'inline'  => true,
			'options' => $ajax_orderby,
			'default' => 'date',
		),

		array(
			'id'      => 'nav_btn_c',
			'class'   => 'be-child-item',
			'type'    => 'radio',
			'title'   => '翻页模式',
			'inline'  => true,
			'options' => array(
				'true'   => '数字翻页',
				'more'   => '更多按钮',
			),
			'default' => 'true',
		),

		array(
			'id'      => 'more_infinite_c',
			'class'   => 'be-child-item be-child-last-item',
			'type'    => 'radio',
			'title'   => '更多按钮滚动加载',
			'inline'  => true,
			'options' => array(
				'false'  => '否',
				'true'   => '是',
			),
			'default' => 'false',
		),

		array(
			'id'       => 'ajax_layout_code_f',
			'type'     => 'text',
			'title'    => 'Ajax标题列表',
			'after'    => $idcat,
		),

		array(
			'id'       => 'ajax_layout_code_f_n',
			'class'    => 'be-child-item',
			'type'     => 'number',
			'title'    => '每页篇数',
			'default'  => '12',
			'after'    => $anh,
		),

		array(
			'id'       => 'ajax_layout_code_f_r',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '侧边栏',
		),

		array(
			'id'       => 'ajax_layout_code_f_btn',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '子分类按钮',
			'default'  => true,
		),

		array(
			'id'      => 'ajax_code_f_orderby',
			'class'   => 'be-child-item',
			'type'    => 'radio',
			'title'   => '文章排序',
			'inline'  => true,
			'options' => $ajax_orderby,
			'default' => 'date',
		),

		array(
			'id'      => 'nav_btn_f',
			'class'   => 'be-child-item',
			'type'    => 'radio',
			'title'   => '翻页模式',
			'inline'  => true,
			'options' => array(
				'true'   => '数字翻页',
				'more'   => '更多按钮',
			),
			'default' => 'true',
		),

		array(
			'id'      => 'more_infinite_f',
			'class'   => 'be-child-item be-child-last-item',
			'type'    => 'radio',
			'title'   => '更多按钮滚动加载',
			'inline'  => true,
			'options' => array(
				'false'  => '否',
				'true'   => '是',
			),
			'default' => 'false',
		),

		array(
			'id'       => 'ajax_layout_code_e',
			'type'     => 'text',
			'title'    => 'Ajax问答布局',
			'after'    => $idcat,
		),

		array(
			'id'       => 'ajax_layout_code_e_n',
			'class'    => 'be-child-item',
			'type'     => 'number',
			'title'    => '每页篇数',
			'default'  => '10',
			'after'    => $anh,
		),

		array(
			'id'       => 'ajax_layout_code_e_btn',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '子分类按钮',
			'default'  => true,
		),

		array(
			'id'       => 'ajax_layout_code_e_btn_m',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '标准按钮',
			'after'    => '<span class="after-perch">子分类较多时选择</span>',
		),

		array(
			'id'      => 'ajax_code_e_orderby',
			'class'   => 'be-child-item',
			'type'    => 'radio',
			'title'   => '文章排序',
			'inline'  => true,
			'options' => $ajax_orderby,
			'default' => 'date',
		),

		array(
			'id'      => 'nav_btn_e',
			'class'   => 'be-child-item',
			'type'    => 'radio',
			'title'   => '翻页模式',
			'inline'  => true,
			'options' => array(
				'true'   => '数字翻页',
				'more'   => '更多按钮',
			),
			'default' => 'true',
		),

		array(
			'id'      => 'more_infinite_e',
			'class'   => 'be-child-item be-child-last-item',
			'type'    => 'radio',
			'title'   => '更多按钮滚动加载',
			'inline'  => true,
			'options' => array(
				'false'  => '否',
				'true'   => '是',
			),
			'default' => 'false',
		),

		array(
			'id'       => 'single_layout_qa',
			'type'     => 'text',
			'title'    => '正文问答模板',
			'after'    => '可与分类Ajax问答模板配套使用',
		),

		array(
			'id'       => 'ajax_layout_code_d',
			'type'     => 'text',
			'title'    => 'Ajax标准布局',
			'after'    => $idcat,
		),

		array(
			'id'       => 'ajax_layout_code_d_n',
			'class'    => 'be-child-item',
			'type'     => 'number',
			'title'    => '每页篇数',
			'default'  => '10',
			'after'    => $anh,
		),

		array(
			'id'       => 'ajax_layout_code_d_btn',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '子分类按钮',
			'default'  => true,
		),

		array(
			'id'      => 'ajax_code_d_orderby',
			'class'   => 'be-child-item',
			'type'    => 'radio',
			'title'   => '文章排序',
			'inline'  => true,
			'options' => $ajax_orderby,
			'default' => 'date',
		),

		array(
			'id'      => 'nav_btn_d',
			'class'   => 'be-child-item',
			'type'    => 'radio',
			'title'   => '翻页模式',
			'inline'  => true,
			'options' => array(
				'true'   => '数字翻页',
				'more'   => '更多按钮',
			),
			'default' => 'true',
		),

		array(
			'id'      => 'more_infinite_d',
			'class'    => 'be-child-item be-child-last-item',
			'type'    => 'radio',
			'title'   => '更多按钮滚动加载',
			'inline'  => true,
			'options' => array(
				'false'  => '否',
				'true'   => '是',
			),
			'default' => 'false',
		),

		array(
			'class'    => 'be-parent-title',
			'type'     => 'subheading',
			'content'  => '专栏Ajax模板选择',
		),

		array(
			'class'    => 'be-child-item',
			'type'    => 'content',
			'content' => '输入分类ID，不可重复添加，其它选项与上面分类设置相同',
		),

		array(
			'id'      => 'ajax_special_code_a',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'   => 'Ajax图片布局',
			'after'    => $idcat,
		),

		array(
			'id'      => 'ajax_special_code_b',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'   => 'Ajax卡片布局',
			'after'    => $idcat,
		),

		array(
			'id'      => 'ajax_special_code_c',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'   => 'Ajax标题布局',
			'after'    => $idcat,
		),

		array(
			'id'      => 'ajax_special_code_d',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'   => 'Ajax标题列表',
			'after'    => $idcat,
		),

		array(
			'class'    => 'be-parent-title',
			'type'     => 'subheading',
			'content'  => '选择不同分类/标签布局',
		),

		array(
			'class'    => 'be-child-item',
			'type'    => 'content',
			'content' => '输入分类/标签 ID，多个ID用英文半角逗号","隔开，不可重复添加，会覆盖上面AJAX模板设置',
		),

		array(
			'id'       => 'cat_layout_default',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'text',
			'title'    => '默认模板',
		),

		array(
			'id'       => 'cat_layout_img',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '图片布局',
		),

		array(
			'id'       => 'cat_layout_img_s',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '图片布局， 有侧边栏',
		),

		array(
			'id'       => 'cat_layout_grid',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '图片布局，可单独设置缩略图大小',
		),

		array(
			'id'       => 'cat_layout_play',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '图片布局，有播放图标',
		),

		array(
			'id'       => 'cat_layout_full',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '通长缩略图',
		),

		array(
			'id'       => 'cat_layout_list',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '标题列表',
		),

		array(
			'id'       => 'cat_layout_title',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '格子标题',
		),

		array(
			'id'       => 'cat_layout_square',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '网格布局',
		),

		array(
			'id'       => 'cat_layout_line',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '时间轴',
		),

		array(
			'id'       => 'cat_layout_fall',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '瀑布流',
		),

		array(
			'id'       => 'cat_child_cover',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '子分类封面',
		),

		array(
			'id'       => 'cat_layout_child',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '子分类',
		),

		array(
			'id'       => 'cat_layout_child_img',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'text',
			'title'    => '子分类图片',
		),

		array(
			'id'       => 'gallery_fall',
			'type'     => 'switcher',
			'title'    => '图片分类归档使用瀑布流',
			'default'  => true,
		),

		array(
			'id'       => 'fall_inf',
			'type'     => 'switcher',
			'title'    => '瀑布流显示文章信息',
			'default'  => true,
		),

		array(
			'id'       => 'child_cover_ico',
			'type'     => 'switcher',
			'title'    => '子分类封面图标模式',
			'default'  => true,
		),
	)
));

CSF::createSection( $prefix, array(
	'title'       => '文章信息',
	'icon'  => 'dashicons dashicons-edit-page',
	'description' => '文章相关信息设置',
	'fields'      => array(

		array(
			'class'    => 'be-parent-title',
			'type'     => 'subheading',
			'content'  => '文章信息设置',
		),

		array(
			'id'       => 'meta_b',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '文章信息显示在标题下面',
			'default'  => true,
		),

		array(
			'id'       => 'title_c',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '正文标题居中',
			'default'  => true,
		),

		array(
			'id'       => 'inf_back',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '不居中时文章信息两行',
		),

		array(
			'id'       => 'meta_author_single',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '正文显示作者信息',
			'default'  => true,
		),

		array(
			'id'       => 'meta_author',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '文章列表显示作者信息',
			'default'  => true,
		),

		array(
			'id'       => 'author_hide',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '网格模块不显示作者信息',
			'default'  => true,
		),

		array(
			'id'       => 'reading_m',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '阅读模式',
			'default'  => true,
		),

		array(
			'id'       => 'print_on',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '打印按钮',
		),

		array(
			'id'       => 'word_count',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '文章字数',
		),

		array(
			'id'       => 'reading_time',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '阅读时间',
		),

		array(
			'id'       => 'word_time',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '移动端隐藏字数和阅读时间',
		),

		array(
			'id'       => 'meta_time',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '使用标准日期格式',
		),

		array(
			'id'       => 'no_year',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '标准日期文章列表不显示年',
		),

		array(
			'id'       => 'meta_time_second',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '显示时间',
			'default'  => true,
		),

		array(
			'id'       => 'post_cat',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '显示文章分类',
			'default'  => true,
		),

		array(
			'id'       => 'meta_zm_like',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '点赞数',
			'default'  => true,
		),

		array(
			'id'       => 'post_replace',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '最后更新日期',
		),

		array(
			'id'       => 'post_tags',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '显示文章标签',
			'default'  => true,
		),

		array(
			'id'       => 'baidu_record',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'switcher',
			'title'    => '显示百度收录与否',
		),


		array(
			'id'       => 'copyright_info',
			'type'     => 'switcher',
			'title'    => '显示文章末尾固定信息',
			'default'  => true,
		),

		array(
			'id'        => 'copyright_content',
			'class'     => 'be-child-item be-child-last-item',
			'type'      => 'textarea',
			'title'     => '输入信息，可使用HTML代码',
			'sanitize'  => false,
			'default'   => '文章末尾固定信息',
		),

		array(
			'id'       => 'no_thumbnail_cat',
			'type'     => 'switcher',
			'title'    => '缩略图上分类名称',
			'default'  => true,
			'after'    => '<span class="after-perch">鼠标悬停显示</span>',
		),

		array(
			'id'       => 'limit_tags_number',
			'type'     => 'number',
			'title'    => '文章标签显示数量',
			'after'    => '<span class="after-perch">留空显示全部</span>',
		),

		array(
			'id'       => 'post_tag_cloud',
			'type'     => 'switcher',
			'title'    => '文章列表显示标签',
			'default'  => true,
		),

		array(
			'id'       => 'post_tag_cloud_n',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'number',
			'title'    => '数量',
			'default'  => 2,
		),

		array(
			'id'       => 'auto_add_like',
			'type'     => 'switcher',
			'title'    => '自动添加点赞字段',
			'default'  => true,
			'label'    => '用于文章排序',
		),

		array(
			'id'       => 'copyright',
			'type'     => 'switcher',
			'title'    => '显示正文底部版权信息',
			'default'  => true,
		),

		array(
			'id'       => 'copyright_avatar',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '显示作者头像',
			'default'  => true,
		),

		array(
			'id'       => 'copyright_statement',
			'class'    => 'be-child-item',
			'type'     => 'textarea',
			'title'    => '自定义版权信息第一行',
			'sanitize' => false,
			'after'    => '可使用HTML代码',
		),

		array(
			'id'        => 'copyright_indicate',
			'class'     => 'be-child-item be-child-last-item',
			'type'      => 'textarea',
			'title'     => '自定义版权信息第二行',
			'sanitize'  => false,
			'default'   => '<strong>转载请务必保留本文链接：</strong>{{link}}',
			'after'     => '{{title}}表示文章标题，{{link}}表示文章链接，比如获取文章标题和链接：＜a href="{{link}}">{{title}}＜/a＞',
		),
	)
));




CSF::createSection( $prefix, array(
	'id'          => 'comments_setting',
	'title'       => '评论设置',
	'icon'        => 'dashicons dashicons-admin-comments',
	'description' => '与评论相关的设置',
	'fields'      => array(

		array(
			'id'       => 'comment_ajax',
			'type'     => 'switcher',
			'title'    => 'Ajax评论',
			'default'  => true,
			'label'    => '启用后，删除WP程序根目录的wp-comments-post，可防垃圾评论',
		),

		array(
			'id'       => 'infinite_comment',
			'type'     => 'switcher',
			'title'    => '评论Ajax翻页',
		),

		array(
			'id'       => 'at',
			'type'     => 'switcher',
			'title'    => '评论@回复',
			'default'  => true,
		),

		array(
			'id'       => 'qq_info',
			'type'     => 'switcher',
			'title'    => 'QQ快速评论',
			'default'  => true,
		),

		array(
			'id'       => 'mail_notify',
			'type'     => 'switcher',
			'title'    => '回复邮件通知',
			'default'  => true,
		),

		array(
			'id'       => 'qt',
			'type'     => 'switcher',
			'title'    => '解锁提交评论',
			'default'  => true,
		),

		array(
			'id'       => 'not_comment_form',
			'type'     => 'switcher',
			'title'    => '默认隐藏评论表单',
		),

		array(
			'id'       => 'no_comment_url',
			'type'     => 'switcher',
			'title'    => '不显示评论网址表单',
		),

		array(
			'id'       => 'no_email',
			'type'     => 'switcher',
			'title'    => '评论只填写昵称',
			'label'    => '同时需要到讨论设置中，取消“评论者必须填入名字和电子邮箱地址”勾选',
		),

		array(
			'id'       => 'login_reply_btn',
			'type'     => 'switcher',
			'title'    => '不显示登录回复按钮',
		),

		array(
			'id'       => 'comment_honeypot',
			'type'     => 'switcher',
			'title'    => '防机器人',
			'default'  => true,
		),

		array(
			'id'       => 'refused_spam',
			'type'     => 'switcher',
			'title'    => '评论检查中文',
			'default'  => true,
		),

		array(
			'id'       => 'sticky_comments',
			'type'     => 'switcher',
			'title'    => '评论置顶',
			'default'  => true,
		),

		array(
			'id'       => 'comments_top',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'switcher',
			'title'    => '显示在评论模块上面',
			'default'  => true,
		),

		array(
			'id'       => 'comment_time',
			'type'     => 'switcher',
			'title'    => '评论时间',
			'default'  => true,
		),

		array(
			'id'       => 'vip',
			'type'     => 'switcher',
			'title'    => '评论等级',
			'default'  => true,
		),

		array(
			'id'       => 'comment_floor',
			'type'     => 'switcher',
			'title'    => '评论楼层',
			'default'  => true,
		),

		array(
			'id'       => 'comment_remark',
			'type'     => 'switcher',
			'title'    => '备注信息',
		),

		array(
			'id'       => 'comment_region',
			'type'     => 'switcher',
			'title'    => '地区信息',
			'after'    => '<span class="after-perch">需上传IP数据库dat文件到网站根目录</span>',
		),

		array(
			'id'       => 'ip_dat_name',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'text',
			'title'    => '数据库文件名称',
			'default'  => 'ipbe',
		),

		array(
			'id'       => 'embed_img',
			'type'     => 'switcher',
			'title'    => '评论贴图',
		),

		array(
			'id'       => 'emoji_show',
			'type'     => 'switcher',
			'title'    => '评论表情',
			'default'  => true,
		),

		array(
			'id'       => 'comment_html',
			'type'     => 'switcher',
			'title'    => '禁止评论HTML',
		),

		array(
			'id'       => 'del_comment',
			'type'     => 'switcher',
			'title'    => '删除评论按钮',
			'default'  => true,
		),

		array(
			'id'       => 'comment_url',
			'type'     => 'switcher',
			'title'    => '禁止评论超链接',
		),

		array(
			'id'       => 'comment_counts',
			'type'     => 'switcher',
			'title'    => '评论信息',
			'default'  => true,
		),

		array(
			'id'       => 'be_show_avatars',
			'type'     => 'switcher',
			'title'    => '申请头像按钮',
			'default'  => true,
		),

		array(
			'id'       => 'close_comments',
			'type'     => 'switcher',
			'title'    => '关闭评论',
		),

		array(
			'id'       => 'login_comment',
			'type'     => 'switcher',
			'title'    => '登录显示评论模块',
		),

		array(
			'id'       => 'check_admin',
			'type'     => 'switcher',
			'title'    => '禁止冒充管理员留言',
		),

		array(
			'id'       => 'admin_name',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '管理员名称',
		),

		array(
			'id'       => 'admin_email',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'text',
			'title'    => '管理员邮箱',
		),

		array(
			'id'       => 'comment_vip',
			'type'     => 'switcher',
			'title'    => '显示评论VIP',
		),

		array(
			'id'      => 'roles_vip',
			'class'   => 'be-child-item be-child-last-item',
			'type'    => 'radio',
			'title'   => '选择显示评论VIP的角色',
			'inline'  => true,
			'options' => array(
				'administrator' => '管理员',
				'editor'        => '编辑',
				'author'        => '作者',
				'contributor'   => '贡献者',
				'subscriber'    => '订阅者',
				'vip_roles'     => '自定义角色'
			),
			'default' => 'contributor',
		),

		array(
			'id'       => 'comment_hint',
			'type'     => 'text',
			'title'    => '评论提示文字',
			'default'  => '赠人玫瑰，手留余香...',
			'after'    => '留空不显示',
		),
	)
));

CSF::createSection( $prefix, array(
	'title'       => '搜索设置',
	'icon'        => 'dashicons dashicons-search',
	'description' => '搜索设置',
	'fields'      => array(

		array(
			'class'    => 'be-parent-title',
			'type'     => 'subheading',
			'content'  => '默认搜索设置',
		),

		array(
			'id'       => 'wp_s',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '默认搜索设置',
			'default'  => true,
		),

		array(
			'id'       => 'menu_search',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '弹窗搜索',
			'default'  => true,
		),

		array(
			'id'       => 'search_captcha',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '搜索验证',
			'default'  => true,
		),

		array(
			'id'       => 'search_title',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '仅搜索标题',
			'default'  => true,
		),

		array(
			'id'       => 'auto_search_post',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '结果仅一个自动跳转',
			'default'  => true,
		),

		array(
			'id'      => 'search_option',
			'class'    => 'be-child-item',
			'type'    => 'radio',
			'title'   => '搜索选项',
			'inline'  => true,
			'options' => array(
				'search_default' => '默认',
				'search_url'     => '修改搜索URL',
				'search_cat'     => '分类搜索',
			),
			'default' => 'search_default',
		),

		array(
			'id'       => 'not_search_cat',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'text',
			'title'    => '输入排除的分类ID',
			'after'    => $mid,
		),

		array(
			'id'      => 'search_post_type',
			'type'    => 'checkbox',
			'title'   => '选择参与搜索的文章类型',
			'inline'  => true,
			'options' => 'post_types',
 			'default'      => array( 'post', 'page', 'bulletin', 'picture', 'video', 'tao', 'show', 'sites' )
		),

		array(
			'id'       => 'ajax_search',
			'type'     => 'switcher',
			'title'    => 'Ajax搜索替换默认',
		),

		array(
			'id'       => 'ajax_search_n',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'number',
			'title'    => 'Ajax搜索显示篇数',
			'default'  => '16',
		),

		array(
			'id'      => 'search_the',
			'type'    => 'radio',
			'title'   => '搜索结果布局',
			'inline'  => true,
			'options' => array(
				'search_list'   => '标题布局',
				'search_img'    => '图片布局',
				'search_normal' => '标准布局',
			),
			'default' => 'search_list',
		),

		array(
			'id'       => 'search_sidebar',
			'type'     => 'switcher',
			'title'    => '显示侧边栏',
			'default'  => true,
		),

		array(
			'class'    => 'be-flex-parent-title',
			'type'     => 'subheading',
		),

		array(
			'class'    => 'be-flex-switcher-parent-title',
			'type'     => 'subheading',
			'content'  => '搜索引擎',
		),

		array(
			'id'       => 'baidu_s',
			'class'    => 'be-flex-switcher be-flex-switcher-first',
			'type'     => 'switcher',
			'default'  => true,
			'before'   => '百度',
		),

		array(
			'id'       => 'google_s',
			'class'    => 'be-flex-switcher',
			'type'     => 'switcher',
			'default'  => true,
			'before'   => 'Google',
		),

		array(
			'id'       => 'bing_s',
			'class'    => 'be-flex-switcher',
			'type'     => 'switcher',
			'default'  => true,
			'before'   => '必应',
		),

		array(
			'id'       => '360_s',
			'class'    => 'be-flex-switcher',
			'type'     => 'switcher',
			'default'  => true,
			'before'   => '360',
		),

		array(
			'id'       => 'sogou_s',
			'class'    => 'be-flex-switcher',
			'type'     => 'switcher',
			'default'  => true,
			'before'   => '搜狗',
		),

		array(
			'id'       => 'google_id',
			'type'     => 'text',
			'title'    => 'Google 搜索ID',
			'default'  => '005077649218303215363:ngrflw3nv8m',
			'after'    => '申请地址：https://cse.google.com/',
		),

		array(
			'id'       => 'search_nav',
			'type'     => 'switcher',
			'title'    => '搜索推荐',
			'default'  => true,
		),

		array(
			'id'       => 'menu_search_button',
			'type'     => 'switcher',
			'title'    => '主菜单搜索按钮',
			'default'  => true,
		),
	)
));

CSF::createSection( $prefix, array(
	'title'       => '网站标志',
	'icon'  => 'dashicons dashicons-wordpress-alt',
	'description' => '上传设置网站LOGO及标志等',
	'fields'      => array(

		array(
			'id'      => 'site_sign',
			'type'    => 'radio',
			'title'   => '站点LOGO/标志',
			'inline'  => true,
			'options' => array(
				'logos'      => 'LOGO',
				'logo_small' => '标志+标题',
				'no_logo'    => '仅标题',
			),
			'default' => 'logo_small',
		),

		array(
			'id'       => 'logo_css',
			'type'     => 'switcher',
			'title'    => '站点名称扫光动画',
			'label'    => '',
			'default'  => true,
		),

		array(
			'id'       => 'logo',
			'type'     => 'upload',
			'title'    => '网站 logo',
			'default'  => $imgpath . '/logo.png',
			'preview'  => true,
			'after'    => '透明png或svg图片最佳，默认高度50px',
		),

		array(
			'id'       => 'logo_small_b',
			'type'     => 'upload',
			'title'    => '网站标志',
			'default'  => $imgpath . '/logo-s.png',
			'preview'  => true,
			'after'    => '透明png或svg正方形图片最佳，默认高度50px',
		),


		array(
			'id'       => 'favicon',
			'type'     => 'upload',
			'title'    => '自定义 Favicon',
			'default'  => $imgpath . '/favicon.ico',
			'preview'  => true,
			'after'    => '上传favicon.ico(普通图片格式的也可以)，并通过FTP上传到网站根目录',
		),

		array(
			'id'       => 'apple_icon',
			'type'     => 'upload',
			'title'    => '自定义 iOS 图标',
			'default'  => $imgpath . '/favicon.png',
			'preview'  => true,
			'after'    => '上传苹果移动设备主屏幕图标',
		),
	)
));

CSF::createSection( $prefix, array(
	'id'    => 'aux_setting',
	'title' => '辅助功能',
	'icon'  => 'dashicons dashicons-image-filter',
) );

CSF::createSection( $prefix, array(
	'parent'      => 'aux_setting',
	'title'       => '阿里图标库',
	'icon'        => '',
	'description' => '添加设置阿里图标库',
	'fields'      => array(

		array(
			'class'    => 'be-button-url',
			'type'     => 'subheading',
			'title'    => '访问阿里图标库',
			'content'  => '<span class="button-primary"><a href="https://www.iconfont.cn/" target="_blank">添加图标</a></span>',
		),

		array(
			'class'    => 'be-parent-title',
			'type'     => 'subheading',
			'content'  => '阿里图标外链',
		),

		array(
			'class'    => 'be-child-item be-help-item',
			'title'    => '说明',
			'type'    => 'content',
			'content'  => '添加修改图标库后，需重新添加链接',
		),

		array(
			'id'       => 'iconfont_url',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '单色图标链接',
			'after'    => '（Font class）后缀为css',
		),

		array(
			'id'       => 'iconsvg_url',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'text',
			'title'    => '彩色图标链接',
			'after'    => '（Symbol）后缀为js',
		),

		array(
			'class'    => 'be-parent-title',
			'type'     => 'subheading',
			'content'  => '阿里图标本地',
		),

		array(
			'class'    => 'be-child-item be-help-item',
			'title'    => '说明',
			'type'    => 'content',
			'content'  => '在本地调图标库，不使用请不要勾选',
		),


		array(
			'id'       => 'black_font',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '单色图标',
			'after'    => '<span class="after-perch">将下载的图标库文件夹改名为font，上传到 wp-content 目录</span>',
		),

		array(
			'id'       => 'color_icon',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'switcher',
			'title'    => '彩色图标',
			'after'    => '<span class="after-perch">将下载的图标库文件夹改名为icon，上传到 wp-content 目录</span>',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'aux_setting',
	'title'       => '编辑器切换',
	'icon'        => '',
	'description' => '用于在区块编辑器与经典编辑器间切换',
	'fields'      => array(

		array(
			'id'       => 'start_classic_editor',
			'type'     => 'switcher',
			'title'    => '经典编辑器',
			'default'  => true,
		),

		array(
			'id'       => 'classic_widgets',
			'type'     => 'switcher',
			'title'    => '经典小工具编辑器',
			'default'  => true,
		),

		array(
			'id'       => 'disable_block_styles',
			'type'     => 'switcher',
			'title'    => '禁止加载区块样式',
			'default'  => true,
			'after'    => '<span class="after-perch">禁止加载区块编辑器style和script</span>',
		),

		array(
			'id'       => 'remove_global_css',
			'type'     => 'switcher',
			'title'    => '禁止加载区块全局样式',
			'default'  => true,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'aux_setting',
	'title'       => '头像设置',
	'icon'        => '',
	'description' => 'Gravatar 头像设置',
	'fields'      => array(

		array(
			'id'      => 'gravatar_url',
			'type'    => 'radio',
			'title'   => '获取头像方式',
			'inline'  => true,
			'options' => array(
				'no'  => '默认',
				'cn'  => 'cn获取',
				'ssl' => 'ssl获取',
				'zh'  => '自定义'
			),
			'default' => 'zh',
		),

		array(
			'id'       => 'zh_url',
			'type'     => 'text',
			'title'    => '自定义获取头像地址',
			'after'    => '默认：cravatar.cn/avatar/',
			'default'  => 'cravatar.cn/avatar/',
		),

		array(
			'id'       => 'ban_avatars',
			'type'     => 'switcher',
			'title'    => '后台禁止头像',
		),

		array(
			'id'       => 'avatar_load',
			'type'     => 'switcher',
			'title'    => '头像延迟加载',
			'default'  => true,
		),

		array(
			'id'      => 'default_avatar_m',
			'type'    => 'radio',
			'title'   => '自定义默认头像',
			'inline'  => true,
			'options' => array(
				'default_avatar_f' => '固定',
				'default_avatar_r' => '随机'
			),
			'default' => 'default_avatar_f',
		),

		array(
			'id'       => 'default_avatar',
			'class'    => 'be-child-item',
			'type'     => 'upload',
			'title'    => '默认头像',
			'default'  => $imgpath . '/logo-s.png',
			'preview'  => true,
			'after'    => '上传更改自定义固定默认头像后，需进入设置 → 讨论 → 默认头像，勾选“自定义”，并保存更改',
		),


		array(
			'id'       => 'local_avatars',
			'type'     => 'switcher',
			'title'    => '允许上传本地头像',
			'default'  => true,
		),

		array(
			'id'       => 'all_local_avatars',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '允许所有角色上传头像',
		),

		array(
			'id'       => 'avatar_size',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'number',
			'title'    => '上传图片限制大小',
			'after'    => '<span class="after-perch">默认200约等于200KB</span>',
			'default'  => '200',
		),

		array(
			'id'       => 'cache_avatar',
			'type'     => 'switcher',
			'title'    => '头像缓存到本地',
			'after'    => '<span class="after-perch">设置 wp-content/uploads/avatar 目录权限为 755 或 777</span>',
		),

		array(
			'id'       => 'gravatar_origin',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '获取头像地址',
			'default'  => 'cravatar.cn',
			'after'    => '默认：https://www.gravatar.com/avatar/',
		),

		array(
			'id'      => 'avatar_url',
			'class'    => 'be-child-item',
			'type'    => 'radio',
			'title'   => '未设置头像则显示',
			'inline'  => true,
			'options' => array(
				'letter_img' => '首字图片',
				'rand_img'   => '随机图片',
			),
			'default' => 'letter_img',
		),

		array(
			'id'    => 'random_avatar_url',
			'class'    => 'be-child-item be-child-last-item',
			'type'  => 'textarea',
			'title' => '随机头像',
			'after'    => '多张图片链接用英文半角逗号","隔开',
			'default' => $imgpath . '/favicon.png',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'aux_setting',
	'title'       => '社会化登录',
	'icon'        => '',
	'description' => '社会化登录',
	'fields'      => array(

		array(
			'id'       => 'be_social_login',
			'type'     => 'switcher',
			'title'    => '社会化登录',
		),

		array(
			'id'       => 'login_data',
			'type'     => 'switcher',
			'title'    => '创建数据表',
			'after'    => '<span class="after-perch">开启后，需保存两次设置，然后取消勾选</span>',
		),

		array(
			'title'    => 'QQ',
			'class'    => 'be-parent-title',
			'type'     => 'subheading',
		),

		array(
			'title'    => '申请地址',
			'class'    => 'be-child-item',
			'type'    => 'content',
			'content' => '<a href="https://connect.qq.com/" target="_blank" title="申请地址">https://connect.qq.com</a>',
		),

		array(
			'title'    => '网站回调域',
			'class'    => 'be-child-item',
			'type'    => 'content',
			'content' => $qq_auth,
		),

		array(
			'id'       => 'qq_app_id',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => 'QQ APP ID',
		),

		array(
			'id'       => 'qq_key',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'text',
			'title'    => 'QQ APP Key',
		),

		array(
			'title'    => '微博',
			'class'    => 'be-parent-title',
			'type'     => 'subheading',
		),

		array(
			'title'    => '申请地址',
			'class'    => 'be-child-item',
			'type'    => 'content',
			'content' => '<a href="https://open.weibo.com/" target="_blank" title="申请地址">https://open.weibo.com</a>',
		),

		array(
			'title'    => '应用地址',
			'class'    => 'be-child-item',
			'type'    => 'content',
			'content' => $weibo_auth,
		),

		array(
			'id'       => 'weibo_key',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '微博 App Key',
		),

		array(
			'id'       => 'weibo_secret',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'text',
			'title'    => '微博 App Secret',
		),

		array(
			'title'    => '微信',
			'class'    => 'be-parent-title',
			'type'     => 'subheading',
		),

		array(
			'title'    => '申请地址（企业资格认证）',
			'class'    => 'be-child-item',
			'type'    => 'content',
			'content' => '<a href="https://open.weixin.qq.com/" target="_blank" title="申请地址">https://open.weixin.qq.com</a>',
		),

		array(
			'title'    => '授权回调域',
			'class'    => 'be-child-item',
			'type'    => 'content',
			'content' => $weixin_auth,
		),

		array(
			'id'       => 'weixin_id',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '微信 APP ID',
		),

		array(
			'id'       => 'weixin_secret',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'text',
			'title'    => '微信 App Secret',
		),

		array(
			'id'       => 'social_login_url',
			'type'     => 'text',
			'title'    => '登录后跳转的地址',
			'default'  => $bloghome,
			'after'    => '比如网站首页链接',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'aux_setting',
	'title'       => '邮件SMTP',
	'icon'        => '',
	'description' => '大部分主机默认情况下都不允许发送邮件，通过第三方邮件 SMTP 实现邮件发送',
	'fields'      => array(

		array(
			'id'       => 'setup_email_smtp',
			'type'     => 'switcher',
			'title'    => '邮件SMTP',
			'default'  => true,
		),

		array(
			'id'       => 'email_name',
			'type'     => 'text',
			'title'    => '发件人名称',
			'default'  => '来自网站',
		),

		array(
			'id'       => 'email_smtp',
			'type'     => 'text',
			'title'    => '邮箱SMTP服务器',
			'default'  => 'smtp.163.com',
			'after'    => '如：smtp.qq.com、smtp.126.com、smtp.163.com',
		),

		array(
			'id'       => 'email_account',
			'type'     => 'text',
			'title'    => '邮箱账户',
			'default'  => 'beginthemes@163.com',
		),

		array(
			'id'       => 'email_authorize',
			'type'     => 'text',
			'title'    => '客户端授权密码',
			'after'    => '非邮箱登录密码',
			'default'  => 'NLSUYCUSEXUGUYHR',
		),

		array(
			'id'       => 'email_port',
			'type'     => 'text',
			'title'    => '端口',
			'after'    => '不需要改',
			'default'  => '465',
		),

		array(
			'id'       => 'email_secure',
			'type'     => 'text',
			'title'    => '加密类型',
			'after'    => '端口25时 留空，465时 ssl，不需要改',
			'default'  => 'ssl',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'aux_setting',
	'title'       => '多条件筛选',
	'icon'        => '',
	'description' => '多条件筛选',
	'fields'      => array(

		array(
			'id'       => 'filters',
			'type'     => 'switcher',
			'title'    => '多条件筛选',
		),

		array(
			'id'       => 'filters_hidden',
			'type'     => 'switcher',
			'title'    => '筛选条件默认折叠',
		),

		array(
			'id'       => 'filters_img',
			'type'     => 'switcher',
			'title'    => '筛选结果使用图片布局',
		),

		array(
			'class'    => 'be-parent-title',
			'type'     => 'subheading',
			'content'  => '筛选项选择',
		),

		array(
			'id'       => 'filters_cat',
			'class'    => 'be-flex-switcher',
			'type'     => 'switcher',
			'default'  => true,
			'before'   => '筛选分类',
		),

		array(
			'id'       => 'filters_a',
			'class'    => 'be-flex-switcher',
			'type'     => 'switcher',
			'default'  => true,
			'before'   => '筛选 A',
		),

		array(
			'id'       => 'filters_b',
			'class'    => 'be-flex-switcher',
			'type'     => 'switcher',
			'default'  => true,
			'before'   => '筛选 B',
		),

		array(
			'id'       => 'filters_c',
			'class'    => 'be-flex-switcher',
			'type'     => 'switcher',
			'default'  => true,
			'before'   => '筛选 C',
		),

		array(
			'id'       => 'filters_d',
			'class'    => 'be-flex-switcher',
			'type'     => 'switcher',
			'default'  => true,
			'before'   => '筛选 D',
		),

		array(
			'id'       => 'filters_e',
			'class'    => 'be-flex-switcher',
			'type'     => 'switcher',
			'default'  => true,
			'before'   => '筛选 E',
		),

		array(
			'id'       => 'filters_f',
			'class'    => 'be-flex-switcher',
			'type'     => 'switcher',
			'default'  => true,
			'before'   => '筛选 F',
		),

		array(
			'id'       => 'filters_g',
			'class'    => 'be-flex-switcher',
			'type'     => 'switcher',
			'default'  => true,
			'before'   => '筛选 G',
		),

		array(
			'id'       => 'filters_h',
			'class'    => 'be-flex-switcher',
			'type'     => 'switcher',
			'default'  => true,
			'before'   => '筛选 H',
		),

		array(
			'id'       => 'filters_i',
			'class'    => 'be-flex-switcher',
			'type'     => 'switcher',
			'default'  => true,
			'before'   => '筛选 I',
		),

		array(
			'id'       => 'filters_j',
			'class'    => 'be-flex-switcher',
			'type'     => 'switcher',
			'default'  => true,
			'before'   => '筛选 J',
		),

		array(
			'class'    => 'be-parent-title',
			'type'     => 'subheading',
			'content'  => '分类及文字',
		),

		array(
			'id'       => 'filters_cat_id',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '输入筛选分类ID',
			'after'    => $mid,
		),

		array(
			'id'       => 'filter_t',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '标题文字',
			'default'  => '条 件 筛 选',
		),


		array(
			'id'       => 'filters_a_t',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '筛选 A 文字',
			'default'  => '风格',
		),

		array(
			'id'       => 'filters_b_t',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '筛选 B 文字',
			'default'  => '价格',
		),

		array(
			'id'       => 'filters_c_t',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '筛选 C 文字',
			'default'  => '功能',
		),

		array(
			'id'       => 'filters_d_t',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '筛选 D 文字',
			'default'  => '大小',
		),

		array(
			'id'       => 'filters_e_t',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '筛选 E 文字',
			'default'  => '地域',
		),

		array(
			'id'       => 'filters_f_t',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '筛选 F 文字',
			'default'  => '品牌',
		),

		array(
			'id'       => 'filters_g_t',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '筛选 G 文字',
			'default'  => '国家',
		),

		array(
			'id'       => 'filters_h_t',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '筛选 H 文字',
			'default'  => '尺寸',
		),

		array(
			'id'       => 'filters_i_t',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '筛选 I 文字',
			'default'  => '时间',
		),

		array(
			'id'       => 'filters_j_t',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'text',
			'title'    => '筛选 J 文字',
			'default'  => '参数',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'aux_setting',
	'title'       => '前端投稿',
	'icon'        => '',
	'description' => '用于前端发表文章，新建页面并添加短代码 [bet_submission_form]',
	'fields'      => array(

		array(
			'id'       => 'front_tougao',
			'type'     => 'switcher',
			'title'    => '前端投稿',
			'default'  => true,
		),

		array(
			'id'       => 'instantly_publish',
			'type'     => 'switcher',
			'title'    => '不审核立即发表',
			'default'  => true,
		),

		array(
			'id'      => 'tougao_mode',
			'type'    => 'radio',
			'title'   => '模式选择',
			'inline'  => true,
			'options' => array(
				'post_mode' => '文章投稿',
				'info_mode' => '信息提交',
			),
			'default' => 'post_mode',
		),

		array(
			'class'    => 'be-parent-title',
			'type'     => 'subheading',
			'content'  => '文章投稿设置',
		),

		array(
			'id'       => 'thumbnail_required',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '允许特色图像',
		),

		array(
			'id'      => 'user_upload',
			'class'    => 'be-child-item',
			'type'    => 'radio',
			'title'   => '投稿（贡献）者上传权限',
			'inline'  => true,
			'options' => array(
				'removecap' => '禁止',
				'addcap'    => '允许',
			),
			'default' => 'removecap',
		),

		array(
			'id'      => 'not_front_cat',
			'type'    => 'checkbox',
			'title'   => '排除的分类',
			'inline'  => true,
			'options' => 'categories',
			'query_args' => array(
				'orderby'  => 'ID',
				'order'    => 'ASC',
			),
		),

		array(
			'class'    => 'be-parent-title',
			'type'     => 'subheading',
			'content'  => '信息提交设置',
		),

		array(
			'id'       => 'submit_bulletin',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '仅提交到“公告”文章',
			'default'  => true,
		),

		array(
			'id'       => 'info_cat',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '输入一个分类ID',
		),

		array(
			'class'    => 'be-parent-title be-child-item',
			'type'     => 'subheading',
			'content'  => '表单文字，留空不显示',
		),

		array(
			'id'       => 'info_a',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '表单 A 文字',
			'default'  => '姓名',
		),

		array(
			'id'       => 'info_b',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '表单 B 文字',
			'default'  => '职业',
		),

		array(
			'id'       => 'info_c',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '表单 C 文字',
			'default'  => '学历',
		),

		array(
			'id'       => 'info_d',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '表单 D 文字',
			'default'  => '电话',
		),

		array(
			'id'       => 'info_e',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '表单 E 文字',
			'default'  => '微信/QQ',
		),

		array(
			'id'       => 'info_f',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'text',
			'title'    => '表单 F 文字',
			'default'  => '邮箱',
		),

		array(
			'class'    => 'be-parent-title be-child-item',
			'type'     => 'subheading',
			'content'  => '单选文字，留空不显示',
		),

		array(
			'id'       => 's_info_a',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '单选 A 文字',
			'default'  => '选择',
		),

		array(
			'id'       => 's_info_b',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '单选 B 文字',
			'default'  => '高中',
		),

		array(
			'id'       => 's_info_e',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '单选 C 文字',
			'default'  => '大专',
		),

		array(
			'id'       => 's_info_d',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '单选 D 文字',
			'default'  => '本科',
		),

		array(
			'id'       => 's_info_c',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '单选 C 文字',
			'default'  => '本科以上',
		),

		array(
			'id'       => 's_info_f',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '单选 F 文字',
			'default'  => '备用选项',
		),
	)
));


CSF::createSection( $prefix, array(
	'parent'      => 'aux_setting',
	'title'       => '文章浏览统计',
	'icon'        => '',
	'description' => '用于统计文章被浏览点击次数',
	'fields'      => array(

		array(
			'id'       => 'post_views',
			'type'     => 'switcher',
			'title'    => '文章浏览统计',
			'default'  => true,
		),

		array(
			'id'       => 'user_views',
			'type'     => 'switcher',
			'title'    => '仅登录可见',
		),

		array(
			'class'    => 'be-button-url',
			'type'     => 'subheading',
			'title'    => '详细设置',
			'content'  => '<span class="button-primary"><a href="' . home_url() . '/wp-admin/options-general.php?page=views_options" target="_blank">更多设置</a></span>',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'aux_setting',
	'title'       => '图片压缩',
	'icon'        => '',
	'description' => '上传图片时，对图片进行自动压缩',
	'fields'      => array(

		array(
			'id'       => 'reduce_img',
			'type'     => 'switcher',
			'title'    => '图片压缩',
		),

		array(
			'class'    => 'be-button-url',
			'type'     => 'subheading',
			'title'    => '详细设置',
			'content'  => '<span class="button-primary"><a href="' . home_url() . '/wp-admin/options-general.php?page=resize-after-upload" target="_blank">设置压缩</a></span>',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'aux_setting',
	'title'       => '注册邀请码',
	'icon'        => '',
	'description' => '用于在注册表单中添加邀请码',
	'fields'      => array(

		array(
			'id'       => 'invitation_code',
			'type'     => 'switcher',
			'title'    => '注册邀请码',
		),

		array(
			'id'       => 'code_data',
			'type'     => 'switcher',
			'title'    => '创建数据表',
			'after'    => '<span class="after-perch">开启后，需保存两次设置，然后取消勾选</span>',
		),

		array(
			'class'    => 'be-button-url',
			'type'     => 'subheading',
			'title'    => '添加邀请码',
			'content'  => '<span class="button-primary"><a href="' . home_url() . '/wp-admin/admin.php?page=be_invitation_code_add" target="_blank">添加邀请码</a></span>',
		),
		array(
			'class'    => 'be-button-url',
			'type'     => 'subheading',
			'title'    => '前端显示邀请码',
			'content'  => '新建页面 → 添加短代码 [be_reg_codes] 并发表',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'aux_setting',
	'title'       => '仅登录访问',
	'icon'        => '',
	'description' => '仅登录访问网站',
	'fields'      => array(

		array(
			'id'       => 'force_login',
			'type'     => 'switcher',
			'title'    => '仅登录访问',
		),

		array(
			'id'       => 'force_login_url',
			'type'     => 'text',
			'title'    => '登录注册页面链接',
			'default'  => $bloglogin,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'aux_setting',
	'title'       => '密码访问网站',
	'icon'        => '',
	'description' => '密码访问网站',
	'fields'      => array(

		array(
			'id'       => 'be_password_status',
			'type'     => 'switcher',
			'title'    => '密码访问网站',
		),

		array(
			'id'       => 'be_password_pass',
			'type'     => 'text',
			'title'    => '访问密码',
		),

		array(
			'id'       => 'be_show_password',
			'type'     => 'switcher',
			'title'    => '显示密码',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'aux_setting',
	'title'       => '跟随按钮设置',
	'icon'        => '',
	'description' => '跟随按钮设置',
	'fields'      => array(

		array(
			'id'       => 'scroll_z',
			'type'     => 'switcher',
			'title'    => '返回首页按钮',
		),

		array(
			'id'       => 'placard_but',
			'type'     => 'switcher',
			'title'    => '公告按钮',
		),

		array(
			'id'       => 'scroll_h',
			'type'     => 'switcher',
			'title'    => '返回顶部按钮',
			'default'  => true,
		),

		array(
			'id'       => 'scroll_b',
			'type'     => 'switcher',
			'title'    => '转到底部按钮',
			'default'  => true,
		),

		array(
			'id'       => 'read_night',
			'type'     => 'switcher',
			'title'    => '夜间模式',
			'default'  => true,
		),

		array(
			'id'       => 'scroll_s',
			'type'     => 'switcher',
			'title'    => '跟随搜索按钮',
		),

		array(
			'id'       => 'scroll_c',
			'type'     => 'switcher',
			'title'    => '跟随评论按钮',
			'default'  => true,
		),

		array(
			'id'       => 'gb2',
			'type'     => 'switcher',
			'title'    => '简繁体转换按钮',
		),

		array(
			'id'       => 'qrurl',
			'type'     => 'switcher',
			'title'    => '显示本页二维码按钮',
			'default'  => true,
		),

		array(
			'id'       => 'but_scroll_show',
			'type'     => 'switcher',
			'title'    => '显隐动画',
			'default'  => true,
		),

		array(
			'id'       => 'mobile_scroll',
			'type'     => 'switcher',
			'title'    => '移动端不显示',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'aux_setting',
	'title'       => '自定义分类法',
	'icon'        => '',
	'description' => '是否使用自定义分类法',
	'fields'      => array(

		array(
			'id'       => 'no_bulletin',
			'type'     => 'switcher',
			'title'    => '公告',
			'default'  => true,
		),

		array(
			'id'       => 'no_gallery',
			'type'     => 'switcher',
			'title'    => '图片',
			'default'  => true,
		),

		array(
			'id'       => 'no_videos',
			'type'     => 'switcher',
			'title'    => '视频',
			'default'  => true,
		),

		array(
			'id'       => 'no_tao',
			'type'     => 'switcher',
			'title'    => '商品',
			'default'  => true,
		),

		array(
			'id'       => 'no_favorites',
			'type'     => 'switcher',
			'title'    => '网址',
			'default'  => true,
		),

		array(
			'id'       => 'no_products',
			'type'     => 'switcher',
			'title'    => '产品',
			'default'  => true,
		),

		array(
			'id'       => 'no_type',
			'type'     => 'switcher',
			'title'    => '仅管理员及编辑可见后台自定义分类法',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'aux_setting',
	'title'       => '新浪微博关注按钮',
	'icon'        => '',
	'description' => '显示在网站名称右侧',
	'fields'      => array(

		array(
			'id'       => 'weibo_t',
			'type'     => 'switcher',
			'title'    => '新浪微博关注按钮',
		),

		array(
			'id'       => 'weibo_id',
			'type'     => 'text',
			'title'    => '输入新浪微博ID',
			'default'  => '1882973105',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'aux_setting',
	'title'       => '联系我们',
	'icon'        => '',
	'description' => '用于发送电子邮件',
	'fields'      => array(

		array(
			'id'       => 'mail_form_phone',
			'type'     => 'switcher',
			'title'    => '表单中显示电话',
			'default'  => true,
		),

		array(
			'id'       => 'mail_form_subject',
			'type'     => 'switcher',
			'title'    => '表单中显示主题',
			'default'  => true,
		),

		array(
			'id'       => 'fix_mail_form',
			'type'     => 'switcher',
			'title'    => '显示左侧固定表单',
		),

		array(
			'id'       => 'to_email',
			'type'     => 'text',
			'title'    => '收件邮箱',
			'default'  => $selectemail,
		),

		array(
			'class'    => 'be-parent-title',
			'type'     => 'subheading',
			'content'  => '自定义表单文字',
		),

		array(
			'id'       => 'mail_name',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '名称',
			'default'  => '您的姓名',
		),

		array(
			'id'       => 'mail_email',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '邮箱',
			'default'  => '您的邮箱',
		),

		array(
			'id'       => 'mail_phone',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '电话',
			'default'  => '您的电话',
		),

		array(
			'id'       => 'mail_subject',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '主题',
			'default'  => '邮件主题',
		),

		array(
			'id'       => 'mail_message',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'text',
			'title'    => '内容',
			'default'  => '邮件内容',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'aux_setting',
	'title'       => '获取公众号验证码',
	'icon'        => '',
	'description' => '关注微信公众号获取验证码',
	'fields'      => array(

		array(
			'id'       => 'wechat_fans',
			'type'     => 'text',
			'title'    => '微信公众号名称',
			'default'  => '公众号名称',
		),

		array(
			'id'       => 'wechat_unite',
			'type'     => 'switcher',
			'title'    => '统一的密码和关键字',
		),

		array(
			'id'       => 'weifans_pass',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '统一密码',
		),

		array(
			'id'       => 'weifans_key',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'text',
			'title'    => '统一关键字',
		),

		array(
			'id'       => 'wechat_qr',
			'type'     => 'upload',
			'title'    => '微信公众号二维码图片',
			'default'  => $imgpath . '/favicon.png',
			'preview'  => true,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'aux_setting',
	'title'       => '在线咨询',
	'icon'        => '',
	'description' => '用于添加联系信息',
	'fields'      => array(

		array(
			'id'       => 'contact_us',
			'type'     => 'switcher',
			'title'    => '在线咨询',
		),

		array(
			'id'       => 'weixing_us_t',
			'type'     => 'text',
			'title'    => '微信文字',
			'default'  => '微信咨询',
		),

		array(
			'id'       => 'weixing_us',
			'type'     => 'upload',
			'title'    => '微信二维码',
			'default'  => $imgpath . '/favicon.png',
			'preview'  => true,
		),

		array(
			'id'       => 'usqq_t',
			'type'     => 'text',
			'title'    => 'QQ文字',
			'default'  => 'QQ咨询',
		),

		array(
			'id'       => 'usqq_id',
			'type'     => 'text',
			'title'    => 'QQ号码',
			'default'  => '8888',
		),

		array(
			'id'       => 'usshang_t',
			'type'     => 'text',
			'title'    => '在线咨询文字',
			'default'  => '在线咨询',
		),

		array(
			'id'       => 'usshang_url',
			'type'     => 'text',
			'title'    => '在线咨询链接',
			'default'  => '#',
		),

		array(
			'id'       => 'us_phone_t',
			'type'     => 'text',
			'title'    => '电话文字',
			'default'  => '服务热线',
		),

		array(
			'id'       => 'us_phone',
			'type'     => 'text',
			'title'    => '电话号码',
			'default'  => '1308888888',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'aux_setting',
	'title'       => 'QQ在线',
	'icon'        => '',
	'description' => '用于显示联系方式QQ、微信、电话等',
	'fields'      => array(

		array(
			'id'       => 'qq_online',
			'type'     => 'switcher',
			'title'    => 'QQ在线',
			'default'  => true,
		),

		array(
			'id'       => 'qq_name',
			'type'     => 'text',
			'title'    => '自定义文字',
			'default'  => '在线咨询',
		),

		array(
			'id'       => 'qq_id',
			'type'     => 'text',
			'title'    => '输入QQ号码',
			'default'  => '8888',
		),

		array(
			'id'       => 'm_phone',
			'type'     => 'text',
			'title'    => '输入手机号',
			'default'  => '13688888888',
		),

		array(
			'id'       => 'weixing_t',
			'type'     => 'text',
			'title'    => '微信说明',
			'default'  => '微信',
		),

		array(
			'id'       => 'weixing_qr',
			'type'     => 'upload',
			'title'    => '微信二维码',
			'default'  => $imgpath . '/favicon.png',
			'preview'  => true,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'aux_setting',
	'title'       => '正文末尾微信二维码',
	'icon'        => '',
	'description' => '用于在正文末尾显示微信二维码',
	'fields'      => array(

		array(
			'id'       => 'single_weixin',
			'type'     => 'switcher',
			'title'    => '正文末尾微信二维码',
			'default'  => true,
		),

		array(
			'id'       => 'single_weixin_one',
			'type'     => 'switcher',
			'title'    => '只显示一个微信二维码',
			'default'  => true,
		),

		array(
			'id'       => 'weixin_h',
			'type'     => 'text',
			'title'    => '微信文字',
			'default'  => '我的微信',
		),

		array(
			'id'       => 'weixin_h_w',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '微信说明文字',
			'default'  => '微信扫一扫',
		),

		array(
			'id'       => 'weixin_h_img',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'upload',
			'title'    => '微信二维码图片',
			'default'  => $imgpath . '/favicon.png',
			'preview'  => true,
		),


		array(
			'id'       => 'weixin_g',
			'type'     => 'text',
			'title'    => '微信公众号文字',
			'default'  => '我的微信公众号',
		),

		array(
			'id'       => 'weixin_g_w',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '微信公众号说明文字',
			'default'  => '微信扫一扫',
		),

		array(
			'id'       => 'weixin_g_img',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'upload',
			'title'    => '微信公众号二维码图片',
			'default'  => $imgpath . '/favicon.png',
			'preview'  => true,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'aux_setting',
	'title'       => '点赞分享',
	'icon'        => '',
	'description' => '用于在正文末尾显示点赞、打赏、分享等',
	'fields'      => array(

		array(
			'id'       => 'shar_donate',
			'type'     => 'switcher',
			'title'    => '打赏',
			'default'  => true,
		),

		array(
			'id'       => 'shar_like',
			'type'     => 'switcher',
			'title'    => '点赞',
			'default'  => true,
		),

		array(
			'id'       => 'shar_favorite',
			'type'     => 'switcher',
			'title'    => '收藏',
			'default'  => true,
		),

		array(
			'id'       => 'favorite_data',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'switcher',
			'title'    => '创建数据表',
			'after'    => '<span class="after-perch">开启后，需保存两次设置，然后取消勾选</span>',
		),


		array(
			'id'       => 'shar_share',
			'type'     => 'switcher',
			'title'    => '分享',
			'default'  => true,
		),

		array(
			'id'       => 'shar_link',
			'type'     => 'switcher',
			'title'    => '链接',
			'default'  => true,
		),

		array(
			'id'       => 'shar_poster',
			'type'     => 'switcher',
			'title'    => '海报',
			'default'  => true,
		),

		array(
			'id'       => 'be_like_content',
			'type'     => 'switcher',
			'title'    => '仅移动端显示',
		),

		array(
			'id'       => 'like_left',
			'type'     => 'switcher',
			'title'    => '同时显示在左侧',
			'default'  => true,
		),

		array(
			'class'    => 'be-parent-title',
			'type'     => 'subheading',
			'content'  => '海报设置',
		),

		array(
			'id'       => 'poster_site_name',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '网站名称',
			'after'    => '输入空格不显示网站名称',
		),

		array(
			'id'       => 'poster_site_tagline',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '网站副标题',
			'after'    => '输入空格不显示网站副标题',
		),

		array(
			'id'       => 'poster_logo',
			'class'    => 'be-child-item',
			'type'     => 'upload',
			'title'    => '海报LOGO',
			'default'  => '',
			'preview'  => true,
			'after'    => '默认调用网站标志',
		),

		array(
			'id'       => 'poster_default_img',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'upload',
			'title'    => '海报默认图片',
			'default'  => $imgdefault . '/random/320.jpg',
			'preview'  => true,
			'after'    => '文章中无图时显示默认图片',
		),

		array(
			'class'    => 'be-parent-title',
			'type'     => 'subheading',
			'content'  => '打赏二维码',
		),

		array(
			'id'       => 'qr_a',
			'class'    => 'be-child-item',
			'type'     => 'upload',
			'title'    => '微信收款二维码图片',
			'default'  => $imgpath . '/favicon.png',
			'preview'  => true,
		),

		array(
			'id'       => 'qr_b',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'upload',
			'title'    => '支付宝收钱二维码图片',
			'default'  => $imgpath . '/favicon.png',
			'preview'  => true,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'aux_setting',
	'title'       => '会员登录查看',
	'icon'        => '',
	'description' => '用于指定用户登录后查看文章内容',
	'fields'      => array(

		array(
			'id'      => 'user_roles',
			'type'    => 'radio',
			'title'   => '选择一个有权查看隐藏内容的角色',
			'inline'  => true,
			'options' => array(
				'administrator' => '管理员',
				'editor'        => '编辑',
				'author'        => '作者',
				'contributor'   => '贡献者',
				'subscriber'    => '订阅者',
				'vip_roles'     => '自定义角色'
			),
			'default' => 'contributor',
		),

		array(
			'id'       => 'role_visible_t',
			'type'     => 'text',
			'title'    => '自定义提示',
			'default'  => '隐藏的内容',
		),

		array(
			'id'       => 'role_visible_w',
			'type'     => 'text',
			'title'    => '无权限查看提示文字',
			'default'  => '无权限查看',
		),

		array(
			'id'       => 'role_visible_c',
			'type'     => 'textarea',
			'title'    => '自定义说明',
			'default'  => '会员登录后查看',
			'sanitize' => false,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'aux_setting',
	'title'       => '新建角色',
	'icon'        => '',
	'description' => '创建自定义角色',
	'fields'      => array(

		array(
			'id'      => 'del_new_roles',
			'type'    => 'radio',
			'title'   => '创建一个新角色',
			'inline'  => true,
			'options' => array(
				'no_roles'  => '无新角色',
				'new_roles' => '新建角色',
				'del_roles' => '删除角色'
			),
			'default' => 'no_roles',
		),

		array(
			'id'       => 'roles_name',
			'type'     => 'text',
			'title'    => '角色名称',
			'default'  => '会员',
		),

		array(
			'id'       => 'user_edit_posts',
			'type'     => 'switcher',
			'title'    => '允许编辑文章',
		),

		array(
			'id'       => 'user_upload_files',
			'type'     => 'switcher',
			'title'    => '允许上传附件',
		),

		array(
			'class'    => 'be-help-inf',
			'title'    => '设置说明',
			'type'    => 'content',
			'content' => '
			<b>修改角色名称</b>&nbsp;&nbsp;&nbsp;&nbsp;先选择“删除角色”，保存两次设置，修改文字，选择“新建角色”，保存两次设置<br />
			<b>修改角色权限</b>&nbsp;&nbsp;&nbsp;&nbsp;先选择“删除角色”，保存两次设置，选择权限，选择“新建角色”，保存两次设置<br />
			修改调整设置后，必须保存两次设置，否则不会生效<br />',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'aux_setting',
	'title'       => '登录/评论查看自定义文字',
	'icon'        => '',
	'description' => '登录/评论查看自定义文字',
	'fields'      => array(

		array(
			'class'    => 'be-parent-title',
			'type'     => 'subheading',
			'content'  => '评论查看自定义文字',
		),

		array(
			'id'       => 'reply_read_t',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '自定义提示',
			'default'  => '此处为隐藏的内容',
		),

		array(
			'id'       => 'reply_read_c',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'textarea',
			'title'    => '自定义说明',
			'default'  => '发表评论并刷新，方可查看',
			'sanitize' => false,
		),

		array(
			'class'    => 'be-parent-title',
			'type'     => 'subheading',
			'content'  => '登录查看自定义文字',
		),

		array(
			'id'       => 'login_read_t',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '自定义提示',
			'default'  => '此处为隐藏的内容',
		),

		array(
			'id'       => 'login_read_c',
			'class'    => 'be-child-item be-child-last-item',
			'type'     => 'textarea',
			'title'    => '自定义说明',
			'default'  => '注册登录后，方可查看',
		),
	)
));


CSF::createSection( $prefix, array(
	'parent'      => 'aux_setting',
	'title'       => '自定义404页面',
	'icon'        => '',
	'description' => '自定义404页面',
	'fields'      => array(

		array(
			'id'       => '404_t',
			'type'     => 'text',
			'title'    => '自定义404页面标题',
			'default'    => '亲，你迷路了！',
		),

		array(
			'id'       => '404_c',
			'type'     => 'textarea',
			'title'    => '自定义404页面内容',
			'default'  => '亲，该网页可能搬家了！<br />',
			'sanitize' => false,
		),

		array(
			'id'      => '404_go',
			'type'    => 'radio',
			'title'   => '404跳转',
			'inline'  => true,
			'options' => array(
				'404_s' => '读秒跳转',
				'404_h' => '直接跳转',
				'404_d' => '不跳转'
			),
			'default' => '404_d',
		),

		array(
			'id'       => '404_url',
			'type'     => 'text',
			'title'    => '自定义跳转链接',
			'default'  => home_url(),
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'aux_setting',
	'title'       => '切换主题语言',
	'icon'        => '',
	'description' => '用于主题前端显示英文语言，后台→设置→常规选项→站点语言→选择 English (United States)',
	'fields'      => array(

		array(
			'id'       => 'languages_en',
			'type'     => 'switcher',
			'title'    => '英文',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'aux_setting',
	'title'       => '网址页面',
	'icon'        => '',
	'description' => '网址页面',
	'fields'      => array(

		array(
			'id'      => 'site_f',
			'type'    => 'radio',
			'title'   => '分栏',
			'inline'  => true,
			'options' => $fsl45,
			'default' => '4',
		),

		array(
			'id'       => 'site_p_n',
			'type'     => 'number',
			'title'    => '显示篇数',
			'default'  => 100,
		),

		array(
			'id'       => 'sites_cat_id',
			'type'     => 'text',
			'title'    => '网址页面排除的父分类ID',
		),

		array(
			'id'       => 'sites_ico',
			'type'     => 'switcher',
			'title'    => '网址显示Favicon图标',
			'default'  => true,
		),

		array(
			'id'       => 'sites_adorn',
			'type'     => 'switcher',
			'title'    => '装饰动画',
			'default'  => true,
		),

		array(
			'id'       => 'all_site_cat',
			'type'     => 'switcher',
			'title'    => '分类目录',
			'default'  => true,
		),

		array(
			'id'       => 'site_cat_fixed',
			'type'     => 'switcher',
			'title'    => '固定在左侧',
			'default'  => true,
		),

		array(
			'class'    => 'be-parent-title',
			'type'     => 'subheading',
			'content'  => '网址页面小工具',
		),

		array(
			'id'       => 'sites_widgets_one_n',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '输入网址分类ID，显示在指定分类下',
		),

		array(
			'id'      => 'sw_f',
			'class'    => 'be-child-item be-child-last-item',
			'type'    => 'radio',
			'title'   => '分栏',
			'inline'  => true,
			'options' => $swf12,
			'default' => '2',
		),

		array(
			'id'       => 'site_sc',
			'type'     => 'switcher',
			'title'    => '网址正文显示网站截图',
			'default'  => true,
		),

		array(
			'id'      => 'screenshot_api',
			'class'    => 'be-child-item be-child-last-item',
			'type'    => 'radio',
			'title'   => '截图API接口',
			'inline'  => true,
			'options' => array(
				'api_wp'        => 's0.wp',
				'api_wordpress' => 's0.wordpress.com',
				'api_urlscan'   => 'urlscan.io',
			),
			'default' => 'api_wp',
		),

		array(
			'id'       => 'sites_url_error',
			'type'     => 'switcher',
			'title'    => '获取网站描述出错时勾选，用后取消勾选',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'aux_setting',
	'title'       => '会员模块',
	'icon'        => '',
	'description' => '需要安装 ErphpDown 插件',
	'fields'      => array(

		array(
			'id'       => 'be_down_show',
			'type'     => 'switcher',
			'title'    => '不加载插件下载模块',
			'default'  => true,
		),

		array(
			'id'       => 'no_epd_css',
			'type'     => 'switcher',
			'title'    => '不加载插件样式',
			'default'  => true,
		),

		array(
			'id'       => 'be_login_epd',
			'type'     => 'switcher',
			'title'    => '弹窗登录',
			'default'  => true,
		),

		array(
			'id'       => 'goods_count',
			'type'     => 'switcher',
			'title'    => '已售数量',
			'default'  => true,
		),

		array(
			'id'       => 'vip_meta',
			'type'     => 'switcher',
			'title'    => '资源信息',
			'default'  => true,
		),

		array(
			'id'       => 'menu_vip',
			'type'     => 'switcher',
			'title'    => '菜单VIP',
			'default'  => true,
		),

		array(
			'id'       => 'vip_scroll',
			'type'     => 'switcher',
			'title'    => '跟随VIP',
			'default'  => true,
		),

		array(
			'id'       => 'be_rec_but_url',
			'type'     => 'text',
			'title'    => '充值链接',
		),

		array(
			'id'       => 'be_vip_but_url',
			'type'     => 'text',
			'title'    => '会员链接',
		),

		array(
			'id'       => 'vip10img',
			'type'     => 'upload',
			'title'    => '终身会员背景图片',
			'default'  => '',
			'preview'  => true,
		),

		array(
			'id'       => 'vip9img',
			'type'     => 'upload',
			'title'    => '包年会员背景图片',
			'default'  => '',
			'preview'  => true,
		),

		array(
			'id'       => 'vip8img',
			'type'     => 'upload',
			'title'    => '包季会员背景图片',
			'default'  => '',
			'preview'  => true,
		),

		array(
			'id'       => 'vip7img',
			'type'     => 'upload',
			'title'    => '包月会员背景图片',
			'default'  => '',
			'preview'  => true,
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'aux_setting',
	'title'       => '文档设置',
	'icon'        => '',
	'description' => '需要上传文档模板补丁',
	'fields'      => array(

		array(
			'id'       => 'docs_name',
			'type'     => 'text',
			'title'    => '文档标题',
			'default'  => '主题使用文档',
		),

		array(
			'id'       => 'docs_name_url',
			'type'     => 'text',
			'title'    => '文档标题链接',
			'default'  => '',
		),

		array(
			'id'       => 'docs_bread_txt',
			'type'     => 'text',
			'title'    => '面包屑导航文字',
			'default'  => '使用文档',
		),

		array(
			'id'       => 'docs_bread_url',
			'type'     => 'text',
			'title'    => '面包屑导航链接',
			'default'  => '',
		),

		array(
			'id'       => 'docs_logo',
			'type'     => 'upload',
			'title'    => '标志',
			'default'  => $imgpath . '/logo-s.png',
			'preview'  => true,
			'after'    => '透明png或svg图片最佳，比例 50×50px',
		),

		array(
			'id'       => 'docs_logo_svg',
			'type'     => 'textarea',
			'title'    => '输入SVG图标代码',
			'sanitize' => false,
		),

		array(
			'id'       => 'docs_notice',
			'type'     => 'switcher',
			'title'    => '文档公告',
			'default'  => true,
		),

		array(
			'id'       => 'docs_notice_id',
			'type'     => 'text',
			'title'    => '调用指定的分类',
			'after'    => '输入一个公告分类ID',
		),

		array(
			'id'       => 'docs_notice_n',
			'type'     => 'number',
			'title'    => '公告滚动篇数',
			'default'  => '2',
		),

		array(
			'id'       => 'docs_nav_but',
			'type'     => 'switcher',
			'title'    => '菜单自定义按钮',
			'default'  => true,
		),

		array(
			'id'       => 'docs_nav_but_text',
			'type'     => 'text',
			'title'    => '按钮文字',
		),

		array(
			'id'       => 'docs_nav_but_url',
			'type'     => 'text',
			'title'    => '链接地址',
			'default'  => '',
		),

		array(
			'id'       => 'docs_footer',
			'type'     => 'textarea',
			'title'    => '页脚信息',
			'sanitize' => false,
			'default'  => 'Copyright ©  站点名称  版权所有.',
		),
	)
));

if ( function_exists( 'is_shop' ) ) {
	CSF::createSection( $prefix, array(
		'parent'      => 'aux_setting',
		'title'       => 'WOO商店',
		'icon'        => '',
		'description' => '需要安装商店插件 WooCommerce ',
		'fields'      => array(

			array(
				'id'       => 'woo_cols_n',
				'type'     => 'number',
				'title'    => '每页显示数量',
				'default'  => 20,
			),

			array(
				'id'       => 'woo_related_n',
				'type'     => 'number',
				'title'    => '相关文章数量',
				'default'  => 4,
			),

			array(
				'id'      => 'woo_f',
				'type'    => 'radio',
				'title'   => '分栏',
				'inline'  => true,
				'options' => $fl456,
				'default' => '5',
			),

			array(
				'id'       => 'woo_thumbnail',
				'type'     => 'upload',
				'title'    => '默认缩略图',
				'default'  => $imgdefault . '/random/320.jpg',
				'preview'  => true,
			),

			array(
				'id'       => 'shop_header_img',
				'type'     => 'upload',
				'title'    => '商店页面默认图片',
				'default'  => $imgdefault . '/options/1200.jpg',
				'preview'  => true,
			),
		)
	));
}

CSF::createSection( $prefix, array(
	'parent'      => 'aux_setting',
	'title'       => '自定义仪表盘',
	'icon'        => '',
	'description' => '隐藏仪表盘默认元素，并添加自定义内容',
	'fields'      => array(

		array(
			'id'       => 'hide_dashboard',
			'type'     => 'switcher',
			'title'    => '隐藏仪表盘默认元素',
			'default'  => true,
		),

		array(
			'id'       => 'add_dashboard',
			'type'     => 'switcher',
			'title'    => '显示自定义内容',
			'default'  => true,
		),

		array(
			'id'       => 'dashboard_title',
			'type'     => 'text',
			'title'    => '自定义标题',
			'default'  => '欢迎光临本站',
		),

		array(
			'id'       => 'dashboard_content',
			'type'     => 'textarea',
			'title'    => '自定义内容',
			'sanitize' => false,
			'default'  => '辅助功能 → 自定义仪表盘，修改此内容 ',
			'after'    => '支持HTML代码',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'aux_setting',
	'title'       => '自定义媒体路径',
	'icon'        => '',
	'description' => '修改默认媒体路径',
	'fields'      => array(

		array(
			'id'       => 'be_upload_path',
			'type'     => 'switcher',
			'title'    => '启用',
		),

		array(
			'id'       => 'be_upload_path_url',
			'type'     => 'text',
			'title'    => '自定义路径',
			'default'  => 'wp-content/media',
			'after'    => 'WP缺省为wp-content/uploads',
		),

		array(
			'class'    => 'be-button-url be-button-help-url',
			'type'     => 'subheading',
			'title'    => '媒体设置',
			'content'  => '<span class="button-primary"><a href="' . home_url() . '/wp-admin/options-media.php" target="_blank">媒体设置</a></span>',
		),

	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'aux_setting',
	'title'       => '综合辅助',
	'icon'        => '',
	'description' => '综合辅助',
	'fields'      => array(

		array(
			'id'       => 'links_click',
			'type'     => 'switcher',
			'title'    => '短链接',
			'label'    => '用于链接按钮点击统计',
		),

		array(
			'id'       => 'login_down_key',
			'type'     => 'switcher',
			'title'    => '下载模块登录查看密码',
		),

		array(
			'id'       => 'remove_jqmigrate',
			'type'     => 'switcher',
			'title'    => '移除jQuery迁移辅助',
			'after'    => '<span class="after-perch">如发现有错误，取消勾选</span>',
		),

		array(
			'id'       => 'web_queries',
			'type'     => 'switcher',
			'title'    => '在页脚显示查询次数及加载时间',
		),

		array(
			'id'       => 'all_settings',
			'type'     => 'switcher',
			'title'    => '显示WordPress设置选项字段',
		),

		array(
			'id'       => 'delete_favorite',
			'type'     => 'switcher',
			'title'    => '删除文章收藏数据表',
		),

		array(
			'id'       => 'no_referrer',
			'type'     => 'switcher',
			'title'    => '头部添加“referrer”标签',
			'after'    => '<span class="after-perch">仅用于解决微相册等外链图片限制</span>',
		),

		array(
			'id'       => 'meta_delete',
			'type'     => 'switcher',
			'title'    => '防止文章选项丢失',
			'after'    => '<span class="after-perch">只有临时使用文章快速编辑和定时发布时使用</span>',
		),

		array(
			'id'       => 'be_feed_cache',
			'type'     => 'number',
			'title'    => 'RSS小工具缓存时间',
			'default'  => '',
			'after'    => '<span class="after-perch">例如：7200，2天</span>',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'aux_setting',
	'title'       => '下载页面',
	'icon'        => '',
	'description' => '设置单独的下载页面',
	'fields'      => array(

		array(
			'id'       => 'root_down_url',
			'type'     => 'switcher',
			'title'    => '下载链接到根目录，并执行下面的操作',
		),

		array(
			'id'       => 'root_file_move',
			'type'     => 'switcher',
			'title'    => '复制下载模板到网站根目录',
			'after'    => '<span class="after-perch">自动将“inc/download.php”文件复制到网站根目录，勾选后需保存两次设置，用后取消勾选</span>',
		),

		array(
			'id'       => 'down_header_img',
			'type'     => 'upload',
			'title'    => '页面背景图片',
			'default'  => $imgdefault . '/options/1200.jpg',
			'preview'  => true,
		),

		array(
			'id'       => 'down_explain',
			'type'     => 'textarea',
			'title'    => '版权说明',
			'default'  => '本站大部分下载资源收集于网络，只做学习和交流使用，版权归原作者所有。若您需要使用非免费的软件或服务，请购买正版授权并合法使用。本站发布的内容若侵犯到您的权益，请联系站长删除，我们将及时处理。',
			'sanitize' => false,
			'after'    => '可使用HTML代码',
		),
	)
));

CSF::createSection( $prefix, array(
	'title'       => '广告位',
	'icon'        => 'dashicons dashicons-admin-site-alt2',
	'description' => '设置广告位',
	'fields'      => array(

		array(
			'id'       => 'ad_h_t',
			'type'     => 'switcher',
			'title'    => '头部通栏广告位',
		),

		array(
			'id'         => 'ad_h_t_h',
			'class'      => 'be-child-item',
			'type'       => 'switcher',
			'title'      => '只在首页显示',
			'dependency' => array( 'ad_h_t', '==', 'true' ),
		),

		array(
			'id'         => 'ad_ht_c',
			'class'      => 'be-child-item',
			'type'       => 'textarea',
			'title'      => '输入头部通栏广告代码',
			'before'     => 'PC端',
			'sanitize'   => false,
			'dependency' => array( 'ad_h_t', '==', 'true' ),
			'default'    => '<a href="#" target="_blank"><img src="' . $imgdefault . '/options/gg.jpg" alt="广告也精彩" /></a>',
		),

		array(
			'id'         => 'ad_ht_m',
			'class'      => 'be-child-item be-child-last-item',
			'type'       => 'textarea',
			'title'      => '输入头部通栏广告代码',
			'before'     => '移动端',
			'sanitize'   => false,
			'dependency' => array( 'ad_h_t', '==', 'true' ),
			'default'    => '<a href="#" target="_blank"><img src="' . $imgdefault . '/options/gg.jpg" alt="广告也精彩" /></a>',
		),

		array(
			'id'       => 'ad_h',
			'type'     => 'switcher',
			'title'    => '头部两栏广告位',
		),

		array(
			'id'         => 'ad_h_h',
			'class'      => 'be-child-item',
			'type'       => 'switcher',
			'title'      => '只在首页显示',
			'dependency' => array( 'ad_h', '==', 'true' ),
		),

		array(
			'id'         => 'ad_h_c',
			'class'      => 'be-child-item',
			'type'       => 'textarea',
			'title'      => '输入头部左侧广告代码',
			'before'     => 'PC端',
			'sanitize'   => false,
			'dependency' => array( 'ad_h', '==', 'true' ),
			'default'    => '<a href="#" target="_blank"><img src="' . $imgdefault . '/options/gg.jpg" alt="广告也精彩" /></a>',
		),

		array(
			'id'         => 'ad_h_c_m',
			'class'      => 'be-child-item',
			'type'       => 'textarea',
			'title'      => '输入头部左侧广告代码',
			'before'     => '移动端',
			'sanitize'   => false,
			'dependency' => array( 'ad_h', '==', 'true' ),
			'default'    => '<a href="#" target="_blank"><img src="' . $imgdefault . '/options/gg.jpg" alt="广告也精彩" /></a>',
		),

		array(
			'id'         => 'ad_h_cr',
			'class'      => 'be-child-item be-child-last-item',
			'type'       => 'textarea',
			'title'      => '输入头部右侧广告代码',
			'before'     => 'PC端',
			'sanitize'   => false,
			'dependency' => array( 'ad_h', '==', 'true' ),
			'default'    => '<a href="#" target="_blank"><img src="' . $imgdefault . '/options/ggr.jpg" alt="广告也精彩" /></a>',
		),

		array(
			'id'       => 'ad_a',
			'type'     => 'switcher',
			'title'    => '文章列表广告位',
		),

		array(
			'id'         => 'ad_a_c',
			'class'      => 'be-child-item',
			'type'       => 'textarea',
			'title'      => '输入文章列表广告代码',
			'before'     => 'PC端',
			'sanitize'   => false,
			'dependency' => array( 'ad_a', '==', 'true' ),
			'default'    => '<a href="#" target="_blank"><img src="' . $imgdefault . '/options/gg.jpg" alt="广告也精彩" /></a>',
		),

		array(
			'id'         => 'ad_a_c_m',
			'class'      => 'be-child-item be-child-last-item',
			'type'       => 'textarea',
			'title'      => '输入文章列表广告代码',
			'before'     => '移动端',
			'sanitize'   => false,
			'dependency' => array( 'ad_a', '==', 'true' ),
			'default'    => '<a href="#" target="_blank"><img src="' . $imgdefault . '/options/gg.jpg" alt="广告也精彩" /></a>',
		),

		array(
			'id'       => 'ad_s',
			'type'     => 'switcher',
			'title'    => '正文标题广告位',
		),

		array(
			'id'         => 'ad_s_c',
			'class'      => 'be-child-item',
			'type'       => 'textarea',
			'title'      => '输入文章列表广告代码',
			'before'     => 'PC端',
			'sanitize'   => false,
			'dependency' => array( 'ad_s', '==', 'true' ),
			'default'    => '<a href="#" target="_blank"><img src="' . $imgdefault . '/options/gg.jpg" alt="广告也精彩" /></a>',
		),

		array(
			'id'         => 'ad_s_c_m',
			'class'      => 'be-child-item be-child-last-item',
			'type'       => 'textarea',
			'title'      => '输入文章列表广告代码',
			'before'     => '移动端',
			'sanitize'   => false,
			'dependency' => array( 'ad_s', '==', 'true' ),
			'default'    => '<a href="#" target="_blank"><img src="' . $imgdefault . '/options/gg.jpg" alt="广告也精彩" /></a>',
		),

		array(
			'id'       => 'ad_s_b',
			'type'     => 'switcher',
			'title'    => '正文底部广告位',
		),

		array(
			'id'         => 'ad_s_c_b',
			'class'      => 'be-child-item',
			'type'       => 'textarea',
			'title'      => '输入文章列表广告代码',
			'before'     => 'PC端',
			'sanitize'   => false,
			'dependency' => array( 'ad_s_b', '==', 'true' ),
			'default'    => '<a href="#" target="_blank"><img src="' . $imgdefault . '/options/gg.jpg" alt="广告也精彩" /></a>',
		),

		array(
			'id'         => 'ad_s_c_b_m',
			'class'      => 'be-child-item be-child-last-item',
			'type'       => 'textarea',
			'title'      => '输入文章列表广告代码',
			'before'     => '移动端',
			'sanitize'   => false,
			'dependency' => array( 'ad_s_b', '==', 'true' ),
			'default'    => '<a href="#" target="_blank"><img src="' . $imgdefault . '/options/gg.jpg" alt="广告也精彩" /></a>',
		),

		array(
			'id'       => 'ad_c',
			'type'     => 'switcher',
			'title'    => '评论上方广告位',
		),

		array(
			'id'         => 'ad_c_c',
			'class'      => 'be-child-item',
			'type'       => 'textarea',
			'title'      => '输入文章列表广告代码',
			'before'     => 'PC端',
			'sanitize'   => false,
			'dependency' => array( 'ad_c', '==', 'true' ),
			'default'    => '<a href="#" target="_blank"><img src="' . $imgdefault . '/options/gg.jpg" alt="广告也精彩" /></a>',
		),

		array(
			'id'         => 'ad_c_c_m',
			'class'      => 'be-child-item be-child-last-item',
			'type'       => 'textarea',
			'title'      => '输入文章列表广告代码',
			'before'     => '移动端',
			'sanitize'   => false,
			'dependency' => array( 'ad_c', '==', 'true' ),
			'default'    => '<a href="#" target="_blank"><img src="' . $imgdefault . '/options/gg.jpg" alt="广告也精彩" /></a>',
		),

		array(
			'id'       => 'ad_s_k',
			'type'     => 'switcher',
			'title'    => '设置正文短代码广告位',
		),

		array(
			'id'         => 'ad_s_z',
			'class'      => 'be-child-item',
			'type'       => 'textarea',
			'title'      => '输入文章列表广告代码',
			'before'     => 'PC端',
			'sanitize'   => false,
			'dependency' => array( 'ad_s_k', '==', 'true' ),
			'default'    => '<a href="#" target="_blank"><img src="' . $imgdefault . '/options/gg.jpg" alt="广告也精彩" /></a>',
		),

		array(
			'id'         => 'ad_s_z_m',
			'class'      => 'be-child-item be-child-last-item',
			'type'       => 'textarea',
			'title'      => '输入文章列表广告代码',
			'before'     => '移动端',
			'sanitize'   => false,
			'dependency' => array( 'ad_s_k', '==', 'true' ),
			'default'    => '<a href="#" target="_blank"><img src="' . $imgdefault . '/options/gg.jpg" alt="广告也精彩" /></a>',
		),

		array(
			'id'       => 'ad_down',
			'type'     => 'textarea',
			'title'    => '文件下载页面广告代码',
			'sanitize' => false,
			'default'    => '<a href="#" target="_blank"><img src="' . $imgdefault . '/options/gg.jpg" alt="广告也精彩" /></a>',
		),

		array(
			'id'       => 'ad_down_file',
			'type'     => 'textarea',
			'title'    => '弹窗下载广告位',
			'sanitize' => false,
			'default'    => '<a href="#" target="_blank"><img src="' . $imgdefault . '/options/gg.jpg" alt="广告也精彩" /></a>',
		),

		array(
			'id'       => 'ad_t',
			'type'     => 'textarea',
			'title'    => '需要在页头<head></head>之间加载的广告代码',
			'sanitize' => false,
			'default'  => '',
		),
	)
));

CSF::createSection( $prefix, array(
	'id'     => 'style_setting',
	'title'  => '定制风格',
	'icon'  => 'dashicons dashicons-admin-appearance',
) );

CSF::createSection( $prefix, array(
	'parent'      => 'style_setting',
	'title'       => '颜色风格',
	'icon'        => '',
	'description' => '择自己喜欢的颜色，不使用自定义颜色清除即可',
	'fields'      => array(

		array(
			'id'      => 'all_color',
			'type'    => 'color',
			'title'    => '统一颜色',
			'default' => '',
		),

		array(
			'type'    => 'content',
			'title'    => '分别选择颜色',
			'content' => '',
		),

		array(
			'id'      => 'blogname_color',
			'class'   => 'be-flex-color',
			'type'    => 'color',
			'default' => '',
			'before'  => '站点标题',
		),

		array(
			'id'      => 'blogdescription_color',
			'class'   => 'be-flex-color',
			'type'    => 'color',
			'default' => '',
			'before'  => '站点副标题',
		),

		array(
			'id'      => 'link_color',
			'class'   => 'be-flex-color',
			'type'    => 'color',
			'default' => '',
			'before'  => '超链接',
		),

		array(
			'id'      => 'menu_color',
			'class'   => 'be-flex-color',
			'type'    => 'color',
			'default' => '',
			'before'  => '菜单颜色',
		),

		array(
			'id'      => 'button_color',
			'class'   => 'be-flex-color',
			'type'    => 'color',
			'default' => '',
			'before'  => '按钮',
		),

		array(
			'id'      => 'cat_color',
			'class'   => 'be-flex-color',
			'type'    => 'color',
			'default' => '',
			'before'  => '分类名称',
		),

		array(
			'id'      => 'slider_color',
			'class'   => 'be-flex-color',
			'type'    => 'color',
			'default' => '',
			'before'  => '幻灯',
		),

		array(
			'id'      => 'h_color',
			'class'   => 'be-flex-color',
			'type'    => 'color',
			'default' => '',
			'before'  => '正文H标签',
		),

		array(
			'id'      => 'z_color',
			'class'   => 'be-flex-color',
			'type'    => 'color',
			'default' => '',
			'before'  => '分享按钮',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'style_setting',
	'title'       => '页面宽度',
	'icon'        => '',
	'description' => '自定义主题全局宽度，如启用了菜单设置 → 菜单外观→ 伸展菜单，需要相应调整宽度数值',
	'fields'      => array(

		array(
			'id'       => 'custom_width',
			'type'     => 'number',
			'title'    => '固定宽度',
			'default'  => '',
			'after'    => '<span class="after-perch">px 默认1122，不使用自定义宽度请留空</span>',
		),

		array(
			'id'       => 'adapt_width',
			'type'     => 'number',
			'title'    => '百分比',
			'default'  => '',
			'after'    => '<span class="after-perch">% 小于99，不使用自定义宽度请留空</span>',
		),

		array(
			'class'    => 'be-help-inf',
			'title'    => '提示',
			'type'    => 'content',
			'content' => '建议选择固定宽度，按百分比显示可能在笔记本小屏上还可以，但在27寸、34寸显示器中就太宽了',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'style_setting',
	'title'       => '自定义样式',
	'icon'        => '',
	'description' => '自定义主题 CSS 样式',
	'fields'      => array(

		array(
			'id'       => 'custom_css',
			'type'     => 'textarea',
			'title'    => '自定义样式 CSS',
			'sanitize' => false,
		),

		array(
			'class'    => 'be-parent-title',
			'type'     => 'subheading',
			'content'  => '样式代码示例',
		),

		array(
			'class'   => 'be-child-item be-child-item-css',
			'title'   => '顶部菜单改为渐变色',
			'type'    => 'content',
			'content' => '
				.header-top {<br/>
				background: linear-gradient(to right, #ffecea, #c4e7f7, #ffecea, #c4e7f7, #ffecea);<br/>
				border-bottom: none;<br/>
				}',
		),

		array(
			'class'   => 'be-child-item be-child-last-item be-child-item-css',
			'title'   => '主菜单改为黑色',
			'type'    => 'content',
			'content' => '
			#menu-container, .headroom--not-top .menu-glass {<br/>
			background: #262626 !important;<br/>
			border-bottom: 1px solid #262626;<br/>
			}<br/>
			.nav-ace .down-menu > li > a, 
			#menu-container .sf-arrows .sf-with-ul:after {<br/>
			color: #ccc;<br/>
			}<br/>

			.logo-site::before {<br/>
			background: linear-gradient(to right, rgba(0, 0, 0, 0) 46%, rgba(0, 0, 0, 0.2) 50%, rgba(0, 0, 0, 0) 54%) 50% 50%;<br/>
			}<br/>
			.menu-login-btu .nav-login-l a, .menu-login-btu .nav-login .show-layer {<br/>
			color: #000 !important;<br/>
			border: 1px solid #939393;<br/>
			}<br/>

			.menu-login-btu .nav-reg a {<br/>
			border: 1px solid #939393;<br/>
			}',
		),
	)
));

CSF::createSection( $prefix, array(
	'parent'      => 'style_setting',
	'title'       => '其它样式',
	'icon'        => '',
	'description' => '修改设置样式',
	'fields'      => array(

		array(
			'id'       => 'aos_scroll',
			'type'     => 'switcher',
			'title'    => '动画特效',
		),

		array(
			'id'      => 'aos_data',
			'class'    => 'be-child-item be-child-last-item',
			'type'    => 'radio',
			'title'   => '动画效果',
			'inline'  => true,
			'options' => array(
				'fade-up' => '向上',
				'fade-in' => '渐显',
				'zoom-in' => '缩放',
			),
			'default' => 'fade-up',
		),

		array(
			'id'       => 'post_no_margin',
			'type'     => 'switcher',
			'title'    => '文章列表无下边距',
		),

		array(
			'id'       => 'hover_box',
			'type'     => 'switcher',
			'title'    => '鼠标悬停阴影',
		),

		array(
			'id'       => 'title_i',
			'type'     => 'switcher',
			'title'    => '模块标题前装饰',
			'default'  => true,
		),

		array(
			'id'       => 'title_l',
			'type'     => 'switcher',
			'title'    => '文章列表悬停装饰',
			'default'  => true,
		),

		array(
			'id'       => 'more_im',
			'type'     => 'switcher',
			'title'    => '彩色标题更多按钮',
			'default'  => true,
		),

		array(
			'id'       => 'fresh_no',
			'type'     => 'switcher',
			'title'    => '标题无背景色（测试中）',
		),

		array(
			'id'       => 'mobile_viewport',
			'type'     => 'switcher',
			'title'    => '移动端禁止缩放',
			'default'  => true,
		),

		array(
			'id'       => 'mouse_cursor',
			'type'     => 'switcher',
			'title'    => '鼠标特效',
		),
	)
));

CSF::createSection( $prefix, array(
	'title'       => '备份设置',
	'icon'        => 'dashicons dashicons-update',
	'description' => '将主题设置数据导出为 backup + 日期.json 文件，用于备份恢复选项设置',
	'fields'      => array(

		array(
			'type' => 'backup',
		),

		array(
			'title'   => '警告',
			'type'    => 'content',
			'content' => '不要随意输入内容，并执行导入操作，否则所有设置将消失！',
		),
	)
) );