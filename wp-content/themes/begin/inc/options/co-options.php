<?php if ( ! defined( 'ABSPATH' )  ) { die; }
if ( ! function_exists( 'co_get_option' ) ) {
	function co_get_option( $option = '', $default = null ) {
		$options = get_option( 'co_home' );
		return ( isset( $options[$option] ) ) ? $options[$option] : $default;
	}
}

$prefix = 'co_home';

ZMOP::createOptions( $prefix, array(
	'framework_title'         => '公司主页',
	'framework_class'         => 'be-box',

	'menu_title'              => '公司主页',
	'menu_slug'               => 'co-options',
	'menu_type'               => 'submenu',
	'menu_capability'         => 'manage_options',
	'menu_icon'               => null,
	'menu_position'           => null,
	'menu_hidden'             => false,
	'menu_parent'             => 'themes.php',

	'show_bar_menu'           => false,
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

ZMOP::createSection( $prefix, array(
	'title'       => '公司幻灯',
	'icon'  => 'dashicons dashicons-cover-image',
	'description' => $repeat,
	'fields'      => array(

		array(
			'id'       => 'group_slider',
			'type'     => 'switcher',
			'title'    => '幻灯',
			'default'  => true,
		),

		array(
			'id'     => 'slider_group',
			'class'  => 'be-child-item',
			'type'   => 'group',
			'title'  => '添加幻灯',
			'accordion_title_by' => array( 'slider_group_title_b' ),
			'accordion_title_number' => true,

			'fields' => array(
				array(
					'id'      => 'slider_group_img',
					'class'   => 'be-child-item',
					'type'    => 'upload',
					'title'   => '背景图片',
					'preview' => true,
				),

				array(
					'id'       => 'group_blur',
					'class'    => 'be-child-item',
					'type'     => 'switcher',
					'title'    => '模糊背景图片',
				),

				array(
					'id'      => 'slider_group_small_img',
					'class'   => 'be-child-item',
					'type'    => 'upload',
					'title'   => '浮动小图片',
					'preview' => true,
				),

				array(
					'id'      => 'slider_group_video',
					'class'   => 'be-child-item',
					'type'    => 'upload',
					'title'   => '浮动视频',
				),

				array(
					'id'       => 'slider_group_c',
					'class'    => 'be-child-item',
					'type'     => 'switcher',
					'title'    => '无小图时文字居中',
				),

				array(
					'id'      => 'group_slider_color',
					'class'   => 'be-child-item',
					'type'    => 'color',
					'title'   => '文字颜色',
				),

				array(
					'id'      => 'slider_group_title_a',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '第一行字',
				),

				array(
					'id'      => 'slider_group_title_b',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '第二行大字',
				),

				array(
					'id'      => 'slider_group_title_c',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '第三行字',
				),

				array(
					'id'      => 'slider_group_url',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '链接',
				),

				array(
					'id'      => 'slider_group_btu',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '按钮文字',
				),

				array(
					'id'      => 'slider_group_btu_url',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '按钮链接',
				),

			),

			'default' => array(
				array(
					'slider_group_img'        => $imgdefault . '/options/1200.jpg',
					'slider_group_video'      => '',
					'slider_group_small_img'  => $imgdefault . '/random/320.jpg',
					'slider_group_title_a'    => '响应式设计',
					'slider_group_title_b'    => '集成SEO自定义功能',
					'slider_group_title_c'    => '众多实用小工具',
					'slider_group_c'          => true,
					'group_slider_color'      => '#fff',
					'slider_group_url'        => '',
					'slider_group_btu'        => '按钮',
					'slider_group_btu_url'    => '',
				),

				array(
					'slider_group_img'        => $imgdefault . '/options/1200.jpg',
					'slider_group_video'      => '',
					'slider_group_small_img'  => '',
					'slider_group_small_img'  => '',
					'slider_group_title_a'    => 'CSS3+HTML5、响应式设计',
					'slider_group_title_b'    => 'WordPress多功能主题： Begin',
					'slider_group_title_c'    => '博客、杂志、图片、公司企业多种布局可选',
					'slider_group_c'          => true,
					'slider_group_url'        => '',
					'slider_group_btu'        => '',
					'slider_group_btu_url'    => '',
				),
			)
		),

		array(
			'id'       => 'big_back_img_h',
			'type'     => 'number',
			'title'    => '高度',
			'after'    => '<span class="after-perch">默认 500</span>',
			'default'  => 500,
		),

		array(
			'id'       => 'big_back_img_m_h',
			'type'     => 'number',
			'title'    => '移动端高度',
			'after'    => '<span class="after-perch">用于移动端显示全图，留空默认 240</span>',
		),

		array(
			'title'   => '切换间隔',
			'type'    => 'content',
			'content' => '首页设置 → 首页幻灯 → 切换间隔',
		),

		array(
			'id'       => 'group_nav',
			'type'     => 'switcher',
			'title'    => '菜单浮在幻灯上',
		),

		array(
			'id'       => 'group_slide_progress',
			'type'     => 'switcher',
			'title'    => '进度条',
			'default'  => true,
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
			'class'    => 'be-child-item',
			'type'     => 'upload',
			'title'    => 'MP4视频',
		),

		array(
			'id'       => 'group_slider_video_img',
			'class'    => 'be-child-item',
			'type'     => 'upload',
			'title'    => '视频封面',
		),

		array(
			'id'     => 'group_video',
			'class'  => 'be-child-item',
			'type'   => 'group',
			'title'  => '添加文字',
			'accordion_title_by' => array( 'group_video_text' ),
			'accordion_title_number' => true,

			'fields' => array(

				array(
					'id'      => 'group_video_text',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '视频文字',
				),

				array(
					'id'      => 'group_video_btn',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '按钮文字',
				),

				array(
					'id'      => 'group_video_url',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '按钮链接',
				),

			),

			'default' => array(
				array(
					'group_video_text'   => '视频大标题',
					'group_video_btn'    => '按钮',
					'group_video_url'    => '#',
				),

				array(
					'group_video_text'   => '视频标题',
					'group_video_btn'    => '链接按钮',
					'group_video_url'    => '#',
				),
			)
		),
	)
));

ZMOP::createSection( $prefix, array(
	'title'       => '关于我们',
	'icon'        => 'dashicons dashicons-businessman',
	'description' => '',
	'fields'      => array(

		array(
			'id'       => 'group_contact',
			'type'     => 'switcher',
			'title'    => '启用',
			'default'  => true,
		),

		array(
			'id'     => 'group_contact_item',
			'class'  => 'be-child-item',
			'type'   => 'group',
			'title'  => '添加',
			'accordion_title_by' => array( 'group_contact_s', 'group_contact_t' ),
			'accordion_title_number' => false,
			'accordion_title_prefix' => '排序',

			'fields' => array(
				array(
					'id'       => 'group_contact_t',
					'class'    => 'be-child-item',
					'type'     => 'text',
					'title'    => '标题',
				),

				array(
					'id'       => 'group_contact_s',
					'class'    => 'be-child-item',
					'type'     => 'number',
					'title'    => '排序',
					'after'    => '<span class="after-perch">默认 1',
					'default'  => 1,
				),

				array(
					'id'      => 'contact_bg',
					'class'   => 'be-child-item be-child-last-item',
					'type'    => 'radio',
					'title'   => '背景颜色',
					'inline'  => true,
					'options' => array(
						'auto'  => '自动',
						'white' => '白色',
						'gray'  => '灰色',
					),
				),

				array(
					'id'        => 'contact_p',
					'class'     => 'be-child-item',
					'type'      => 'wp_editor',
					'title'     => '内容',
					'height'    => '150px',
					'sanitize'  => false,
				),

				array(
					'id'       => 'tr_contact',
					'class'    => 'be-child-item',
					'type'     => 'switcher',
					'title'    => '移动端截断文字',
				),

				array(
					'id'       => 'group_contact_bg',
					'class'    => 'be-child-item',
					'type'     => 'switcher',
					'title'    => '显示图片',
				),

				array(
					'id'       => 'group_contact_img',
					'class'    => 'be-child-item',
					'class'    => 'be-child-item',
					'type'     => 'upload',
					'title'    => '上传图片',
					'preview'  => true,
				),

				array(
					'id'      => 'contact_img_m',
					'class'   => 'be-child-item be-child-last-item',
					'type'    => 'radio',
					'title'   => '图片显示模式',
					'inline'  => true,
					'options' => array(
						'contact_img_left'   => '居左',
						'contact_img_center' => '居中',
						'contact_img_right'  => '居右',
					),
				),

				array(
					'id'       => 'group_more_z',
					'type'     => 'text',
					'title'    => '详细查看按钮文字',
					'after'    => '留空则不显示',
				),

				array(
					'id'       => 'group_more_url',
					'class'    => 'be-child-item be-sub-last-item',
					'type'     => 'text',
					'title'    => '详细查看链接地址',
				),

				array(
					'id'       => 'group_contact_z',
					'type'     => 'text',
					'title'    => '联系方式按钮文字',
					'after'    => '留空则不显示',
				),

				array(
					'id'       => 'group_contact_url',
					'class'    => 'be-child-item be-child-last-item',
					'type'     => 'text',
					'title'    => '联系方式链接地址',
				),

			),

			'default' => array(
				array(
					'contact_bg'         => 'auto',
					'group_contact_s'    => '1',
					'group_contact_t'    => '关于我们',
					'contact_p'          => '<p>HTML5+CSS3 响应式设计，博客、杂志、图片、公司企业多种布局可选，集成SEO自定义功能，丰富的主题选项，众多实用小工具。</p>',
					'tr_contact'         => '',
					'group_contact_bg'   => true,
					'group_contact_img'  => $imgdefault . '/options/1200.jpg',
					'contact_img_m'      => 'contact_img_right',
					'group_more_z'       => '详细查看',
					'group_more_url'     => '链接地址',
					'group_contact_z'    => '关于我们',
					'group_contact_url'  => '链接地址',
				),
			)
		),

	)
));

ZMOP::createSection( $prefix, array(
	'title'       => '说明',
	'icon'        => 'dashicons dashicons-edit-page',
	'description' => '',
	'fields'      => array(

		array(
			'id'       => 'group_explain',
			'type'     => 'switcher',
			'title'    => '启用',
			'default'  => true,
		),

		array(
			'id'       => 'group_explain_s',
			'class'    => 'be-child-item',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认 2</span>',
			'default'  => 2,
		),

		array(
			'id'     => 'group_explain_item',
			'class'  => 'be-child-item',
			'type'   => 'group',
			'title'  => '添加',
			'accordion_title_by' => array( 'group_explain_t' ),
			'accordion_title_number' => true,

			'fields' => array(
				array(
					'id'      => 'explain_bg',
					'class'    => 'be-child-item be-child-last-item',
					'type'    => 'radio',
					'title'   => '背景颜色',
					'inline'  => true,
					'options' => array(
						'auto'  => '自动',
						'white' => '白色',
						'gray'  => '灰色',
					),
				),

				array(
					'id'       => 'group_explain_t',
					'class'    => 'be-child-item',
					'type'     => 'text',
					'title'    => '标题',
				),

				array(
					'id'       => 'group_explain_des',
					'class'    => 'be-child-item',
					'type'     => 'text',
					'title'    => '说明',
				),

				array(
					'id'       => 'explain_p',
					'class'    => 'be-child-item',
					'type'     => 'wp_editor',
					'title'    => '内容',
					'height'   => '150px',
					'sanitize' => false,
				),

				array(
					'id'       => 'explain_indent',
					'class'    => 'be-child-item',
					'type'     => 'switcher',
					'title'    => '段首缩进',
				),

				array(
					'id'       => 'ex_thumbnail_a',
					'class'    => 'be-child-item',
					'type'     => 'upload',
					'title'    => '上传左图片',
					'preview'  => true,
				),

				array(
					'id'       => 'ex_thumbnail_b',
					'class'    => 'be-child-item',
					'type'     => 'upload',
					'title'    => '上传右图片',
					'preview'  => true,
				),

				array(
					'id'       => 'group_explain_more',
					'class'    => 'be-child-item',
					'type'     => 'text',
					'title'    => '按钮文字',
				),

				array(
					'id'       => 'group_explain_url',
					'class'    => 'be-child-item',
					'type'     => 'text',
					'title'    => '按钮链接',
				),
			),

			'default' => array(
				array(
					'explain_bg'            => 'auto',
					'group_explain_t'       => '公司说明',
					'group_explain_des'     => '公司说明模块',
					'explain_p'             => '<p>HTML5+CSS3 响应式设计，博客、杂志、图片、公司企业多种布局可选，集成SEO自定义功能，丰富的主题选项，众多实用小工具。</p>',
					'group_explain_url'     => '#',
					'group_explain_more'    => '按钮文字',
					'ex_thumbnail_a'        => $imgdefault . '/random/320.jpg',
					'ex_thumbnail_b'        =>  $imgdefault . '/random/320.jpg',
				),
			)
		),
	)
));

ZMOP::createSection( $prefix, array(
	'title'       => '关于本站',
	'icon'        => 'dashicons dashicons-admin-site',
	'description' => '',
	'fields'      => array(

		array(
			'id'       => 'group_about',
			'type'     => 'switcher',
			'title'    => '启用',
			'default'  => true,
		),

		array(
			'id'       => 'group_about_s',
			'class'    => 'be-child-item',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认 3</span>',
			'default'  => 3,
		),

		array(
			'id'       => 'group_about_t',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '标题',
			'default'  => '关于本站',
		),

		array(
			'id'       => 'group_about_content',
			'class'    => 'be-child-item',
			'type'     => 'wp_editor',
			'title'    => '内容',
			'height'   => '150px',
			'sanitize' => false,
			'default'  => '<p>HTML5+CSS3 响应式设计，博客、杂志、图片、公司企业多种布局可选，集成SEO自定义功能，丰富的主题选项，众多实用小工具。</p>
							<p>HTML5+CSS3 响应式设计，博客、杂志、图片、公司企业多种布局可选，集成SEO自定义功能，丰富的主题选项，众多实用小工具。</p>',
		),

		array(
			'id'       => 'group_about_more',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '按钮文字',
			'default'   => '按钮',
		),

		array(
			'id'       => 'group_about_url',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '按钮链接',
		),

		array(
			'id'      => 'group_about_color',
			'class'   => 'be-about-color color-f be-child-item',
			'type'    => 'color',
			'title'    => '文字背景颜色',
			'default' => 'rgba(221,153,51,0.96)',
		),

		array(
			'id'       => 'group_about_bg',
			'class'    => 'be-child-item',
			'type'     => 'upload',
			'title'    => '上传背景图片',
			'default'  => $imgdefault . '/options/1200.jpg',
			'preview'  => true,
		),
	)
));

ZMOP::createSection( $prefix, array(
	'title'       => '公示板',
	'icon'        => 'dashicons dashicons-admin-comments',
	'description' => '',
	'fields'      => array(

		array(
			'id'       => 'group_notice',
			'type'     => 'switcher',
			'title'    => '启用',
			'default'  => true,
		),

		array(
			'id'       => 'group_notice_s',
			'class'    => 'be-child-item',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认 4</span>',
			'default'  => 4,
		),

		array(
			'id'      => 'notice_bg',
			'class'   => 'be-child-item',
			'type'    => 'radio',
			'title'   => '背景颜色',
			'inline'  => true,
			'options' => array(
				'auto'  => '自动',
				'white' => '白色',
				'gray'  => '灰色',
			),
			'default'   => 'auto',
		),

		array(
			'id'       => 'group_notice_t',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '标题',
			'default'  => '公示板',
		),

		array(
			'id'       => 'group_notice_des',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '说明',
			'default'  => '公示板说明',
		),

		array(
			'id'        => 'group_notice_inf',
			'class'     => 'be-child-item',
			'type'      => 'wp_editor',
			'title'     => '输入右侧文字信息',
			'height'    => '150px',
			'sanitize'  => false,
			'default'   => '<p><h2>H2 响应式设计</h2><div class="clear"></div><h3>H3 自定义颜色风格</h3><h4>H4 响应式设计不依赖任何前端框架</h4><h5>H5 不依赖任何前端框架</h5><h6>H6 响应式设计自定义颜色风格不依赖任何前端框架风格不依赖任何风格不依赖任何</h6></p>',
		),

		array(
			'id'       => 'group_notice_img',
			'class'    => 'be-child-item',
			'type'     => 'upload',
			'title'    => '左侧图片',
			'default'  => $imgdefault . '/random/560.jpg',
			'preview'  => true,
		),
	)
));


ZMOP::createSection( $prefix, array(
	'title'       => '分类封面',
	'icon'        => 'dashicons dashicons-format-image',
	'description' => '',
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
			'after'    => '<span class="after-perch">默认 5</span>',
			'default'  => 5,
		),

		array(
			'id'      => 'cover_bg',
			'type'    => 'radio',
			'title'   => '背景颜色',
			'inline'  => true,
			'options' => array(
				'auto'  => '自动',
				'white' => '白色',
				'gray'  => '灰色',
			),
			'default'   => 'auto',
		),

		array(
			'id'       => 'group_cat_cover_id',
			'type'     => 'text',
			'title'    => '输入分类ID',
			'after'    => $mid .'，支持所有分类法',
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
	)
));

ZMOP::createSection( $prefix, array(
	'title'       => '服务项目',
	'icon'        => 'dashicons dashicons-schedule',
	'description' => $repeat,
	'fields'      => array(

		array(
			'id'       => 'dean',
			'type'     => 'switcher',
			'title'    => '服务项目',
			'default'  => true,
		),

		array(
			'id'       => 'dean_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认 6</span>',
			'default'  => 6,
		),

		array(
			'id'      => 'dean_bg',
			'type'    => 'radio',
			'title'   => '背景颜色',
			'inline'  => true,
			'options' => array(
				'auto'  => '自动',
				'white' => '白色',
				'gray'  => '灰色',
			),
			'default'   => 'auto',
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
			'id'     => 'group_dean_item',
			'class'  => 'be-child-item',
			'type'   => 'group',
			'title'  => '添加',
			'accordion_title_by' => array( 'group_dean_title' ),
			'accordion_title_number' => true,

			'fields' => array(
				array(
					'id'      => 'group_dean_t1',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '第一行文字',
				),

				array(
					'id'      => 'group_dean_title',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '第二行文字（浮在图片上）',
				),

				array(
					'id'        => 'group_dean_t2',
					'class'     => 'be-child-item',
					'type'      => 'wp_editor',
					'title'     => '第三行文字',
					'height'    => '150px',
					'sanitize'  => false,
					'media_buttons' => false,
				),

				array(
					'id'       => 'group_dean_l',
					'class'    => 'be-child-item',
					'type'     => 'switcher',
					'title'    => '文字居左',
				),

				array(
					'id'      => 'group_dean_btn',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '按钮',
				),

				array(
					'id'      => 'group_dean_url',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '链接',
				),

				array(
					'id'      => 'group_dean_img',
					'class'   => 'be-child-item',
					'type'    => 'upload',
					'title'   => '图片',
					'preview' => true,
				),
			),

			'default' => array(
				array(
					'group_dean_title' => '标题',
					'group_dean_t1'    => '第一行文字',
					'group_dean_t2'    => '第三行文字',
					'group_dean_l'     => '',
					'group_dean_img'   => $imgdefault . '/random/560.jpg',
					'group_dean_btn'   => '按钮',
					'group_dean_url'   => '#',
				),

				array(
					'group_dean_title' => '标题',
					'group_dean_t1'    => '第一行文字',
					'group_dean_t2'    => '第三行文字',
					'group_dean_l'     => '',
					'group_dean_img'   => $imgdefault . '/random/560.jpg',
					'group_dean_btn'   => '按钮',
					'group_dean_url'   => '#',
				),

				array(
					'group_dean_title' => '标题',
					'group_dean_t1'    => '第一行文字',
					'group_dean_t2'    => '第三行文字',
					'group_dean_l'     => '',
					'group_dean_img'   => $imgdefault . '/random/560.jpg',
					'group_dean_btn'   => '按钮',
					'group_dean_url'   => '#',
				),

				array(
					'group_dean_title' => '标题',
					'group_dean_t1'    => '第一行文字',
					'group_dean_t2'    => '第三行文字',
					'group_dean_l'     => '',
					'group_dean_img'   => $imgdefault . '/random/560.jpg',
					'group_dean_btn'   => '按钮',
					'group_dean_url'   => '#',
				),
			)
		),

		array(
			'id'      => 'deanm_f',
			'type'    => 'radio',
			'title'   => '分栏',
			'inline'  => true,
			'options' => $fl3456,
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

ZMOP::createSection( $prefix, array(
	'title'       => '推荐',
	'icon'        => 'dashicons dashicons-thumbs-up',
	'description' => $repeat,
	'fields'      => array(

		array(
			'id'       => 'group_foldimg',
			'type'     => 'switcher',
			'title'    => '推荐',
			'default'  => true,
		),

		array(
			'id'       => 'foldimg_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认 7</span>',
			'default'  => 7,
		),

		array(
			'id'      => 'foldimg_bg',
			'type'    => 'radio',
			'title'   => '背景颜色',
			'inline'  => true,
			'options' => array(
				'auto'  => '自动',
				'white' => '白色',
				'gray'  => '灰色',
			),
			'default'   => 'auto',
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
			'id'     => 'group_foldimg_item',
			'class'  => 'be-child-item',
			'type'   => 'group',
			'title'  => '添加',
			'accordion_title_by' => array( 'group_foldimg_title' ),
			'accordion_title_number' => true,

			'fields' => array(
				array(
					'id'      => 'group_foldimg_title',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '标题',
				),

				array(
					'id'      => 'group_foldimg_img',
					'class'   => 'be-child-item',
					'type'    => 'upload',
					'title'   => '图片',
					'preview' => true,
				),

				array(
					'id'        => 'group_foldimg_des',
					'class'     => 'be-child-item',
					'type'      => 'wp_editor',
					'height'    => '150px',
					'title'     => '内容',
					'sanitize'  => false,
					'after'     => '<span class="after-top">使用 <i class="mce-ico mce-i-bullist"></i> 项目符号列表，换行显示</span>',
				),

				array(
					'id'      => 'group_foldimg_btn',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '按钮',
				),

				array(
					'id'      => 'group_foldimg_url',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '链接',
				),

			),

			'default' => array(
				array(
					'group_foldimg_title' => '标题',
					'group_foldimg_img'   => $imgdefault . '/random/320.jpg',
					'group_foldimg_des'   => '<p>内容内容</p><p>内容内容</p><p>内容内容</p>',
					'group_foldimg_btn'   => '按钮',
					'group_foldimg_url'   => '#',
				),

				array(
					'group_foldimg_title' => '标题',
					'group_foldimg_img'   => $imgdefault . '/random/320.jpg',
					'group_foldimg_des'   => '<p>内容内容</p><p>内容内容</p><p>内容内容</p>',
					'group_foldimg_btn'   => '按钮',
					'group_foldimg_url'   => '#',
				),

				array(
					'group_foldimg_title' => '标题',
					'group_foldimg_img'   => $imgdefault . '/random/320.jpg',
					'group_foldimg_des'   => '<p>内容内容</p><p>内容内容</p><p>内容内容</p>',
					'group_foldimg_btn'   => '按钮',
					'group_foldimg_url'   => '#',
				),

				array(
					'group_foldimg_title' => '标题',
					'group_foldimg_img'   => $imgdefault . '/random/320.jpg',
					'group_foldimg_des'   => '<p>内容内容</p><p>内容内容</p><p>内容内容</p>',
					'group_foldimg_btn'   => '按钮',
					'group_foldimg_url'   => '#',
				),
			)
		),

		array(
			'id'       => 'foldimg_height',
			'type'     => 'number',
			'title'    => '高度',
			'after'    => '<span class="after-perch">默认360</span>',
			'default'  => 360,
		),

		array(
			'id'       => 'foldimg_one_col',
			'type'     => 'switcher',
			'title'    => '移动端强制1栏',
		),

	)
));

ZMOP::createSection( $prefix, array(
	'title'       => '流程',
	'icon'        => 'dashicons dashicons-coffee',
	'description' => $repeat,
	'fields'      => array(

		array(
			'id'       => 'group_process',
			'type'     => 'switcher',
			'title'    => '流程',
			'default'  => true,
		),

		array(
			'id'       => 'process_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认 8</span>',
			'default'  => 8,
		),

		array(
			'id'      => 'process_bg',
			'type'    => 'radio',
			'title'   => '背景颜色',
			'inline'  => true,
			'options' => array(
				'auto'  => '自动',
				'white' => '白色',
				'gray'  => '灰色',
			),
			'default'   => 'auto',
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
			'id'       => 'process_order',
			'type'     => 'switcher',
			'title'    => '显示序号',
			'default'  => true,
		),

		array(
			'id'     => 'group_process_item',
			'type'   => 'group',
			'title'  => '添加',
			'accordion_title_by' => array( 'group_process_title' ),
			'accordion_title_number' => true,

			'fields' => array(
				array(
					'id'      => 'group_process_title',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '标题',
				),

				array(
					'id'       => 'group_process_des',
					'class'    => 'be-child-item textarea-30',
					'type'     => 'textarea',
					'title'    => '描述',
					'sanitize' => false,
				),

				array(
					'id'      => 'group_process_ico',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '图标',
				),

				array(
					'id'      => 'group_process_color',
					'class'   => 'be-child-item color-f',
					'type'    => 'color',
					'title'   => '颜色',
				),

			),

			'default' => array(
				array(
					'group_process_title' => '标题',
					'group_process_des'   => '描述',
					'group_process_ico'   => 'be be-display',
					'group_process_color' => '#41a0bb',
				),

				array(
					'group_process_title' => '标题',
					'group_process_des'   => '描述',
					'group_process_ico'   => 'cx cx-haibao',
					'group_process_color' => '#c4475d',
				),

				array(
					'group_process_title' => '标题',
					'group_process_des'   => '描述',
					'group_process_ico'   => 'be be-skyatlas',
					'group_process_color' => '#9f935d',
				),

				array(
					'group_process_title' => '标题',
					'group_process_des'   => '描述',
					'group_process_ico'   => 'be be-home',
					'group_process_color' => '#6096a4',
				),

				array(
					'group_process_title' => '标题',
					'group_process_des'   => '描述',
					'group_process_ico'   => 'be be-star',
					'group_process_color' => '#b78a6a',
				),

				array(
					'group_process_title' => '标题',
					'group_process_des'   => '描述',
					'group_process_ico'   => 'be be-search',
					'group_process_color' => '#8e4671',
				),
			)
		),

	)
));

ZMOP::createSection( $prefix, array(
	'title'       => '支持',
	'icon'        => 'dashicons dashicons-whatsapp',
	'description' => $repeat,
	'fields'      => array(

		array(
			'id'       => 'group_assist',
			'type'     => 'switcher',
			'title'    => '支持',
			'default'  => true,
		),

		array(
			'id'       => 'group_assist_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认 9</span>',
			'default'  => 9,
		),

		array(
			'id'      => 'assist_bg',
			'type'    => 'radio',
			'title'   => '背景颜色',
			'inline'  => true,
			'options' => array(
				'auto'  => '自动',
				'white' => '白色',
				'gray'  => '灰色',
			),
			'default'   => 'auto',
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
			'id'     => 'group_assist_item',
			'type'   => 'group',
			'title'  => '添加',
			'accordion_title_by' => array( 'group_assist_title' ),
			'accordion_title_number' => true,

			'fields' => array(
				array(
					'id'      => 'group_assist_title',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '标题',
				),

				array(
					'id'      => 'group_assist_des',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '描述',
				),

				array(
					'id'      => 'group_assist_ico',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '图标',
				),

				array(
					'id'      => 'group_assist_color',
					'class'   => 'be-child-item',
					'type'    => 'color',
					'title'   => '颜色',
				),

				array(
					'id'      => 'group_assist_url',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '链接',
				),

			),

			'default' => array(
				array(
					'group_assist_title' => '标题',
					'group_assist_des'   => '描述',
					'group_assist_ico'   => 'be be-display',
					'group_assist_url'   => '',
					'group_assist_color' => '#999',
				),

				array(
					'group_assist_title' => '标题',
					'group_assist_des'   => '描述',
					'group_assist_ico'   => 'be be-schedule',
					'group_assist_url'   => '',
					'group_assist_color' => '#a87d94',
				),

				array(
					'group_assist_title' => '标题',
					'group_assist_des'   => '描述',
					'group_assist_ico'   => 'be be-personoutline',
					'group_assist_url'   => '',
					'group_assist_color' => '#88b7cc',
				),

				array(
					'group_assist_title' => '标题',
					'group_assist_des'   => '描述',
					'group_assist_ico'   => 'be be-favoriteoutline',
					'group_assist_url'   => '',
					'group_assist_color' => '#e38b8d',
				),
			)
		),
	)
));

ZMOP::createSection( $prefix, array(
	'title'       => '咨询',
	'icon'        => 'dashicons dashicons-format-chat',
	'description' => '',
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
			'after'    => '<span class="after-perch">默认 10</span>',
			'default'  => 10,
		),

		array(
			'id'      => 'strong_bg',
			'type'    => 'radio',
			'title'   => '背景颜色',
			'inline'  => true,
			'options' => array(
				'auto'  => '自动',
				'white' => '白色',
				'gray'  => '灰色',
			),
			'default'   => 'auto',
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
			'id'      => 'group_strong_id',
			'type'    => 'text',
			'title'   => '右侧文章',
			'after'   => '输入文章ID，多个ID用英文半角逗号","隔开，按先后排序',
		),

		array(
			'id'       => 'group_strong_title_c',
			'class'    => 'be-child-item be-sub-last-item',
			'type'     => 'switcher',
			'title'    => '标题居中',
		),

		array(
			'id'        => 'group_strong_inf',
			'type'      => 'wp_editor',
			'height'    => '150px',
			'title'     => '内容',
			'sanitize'  => false,
			'default'   => '<h2>H2 响应式设计</h2><div class="clear"></div><h3>H3 自定义颜色风格</h3><h4>H4 响应式设计不依赖任何前端框架</h4><h5>H5 不依赖任何前端框架</h5><h6>H6 响应式设计自定义颜色风格不依赖任何前端框架风格</h6>',
		),
	)
));

ZMOP::createSection( $prefix, array(
	'title'       => '帮助',
	'icon'        => 'dashicons dashicons-book',
	'description' => '',
	'fields'      => array(

		array(
			'id'       => 'group_help',
			'type'     => 'switcher',
			'title'    => '帮助',
			'default'  => true,
		),

		array(
			'id'       => 'group_help_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认 11</span>',
			'default'  => 11,
		),

		array(
			'id'      => 'help_bg',
			'type'    => 'radio',
			'title'   => '背景颜色',
			'inline'  => true,
			'options' => array(
				'auto'  => '自动',
				'white' => '白色',
				'gray'  => '灰色',
			),
			'default'   => 'auto',
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
			'id'     => 'group_help_item',
			'type'   => 'group',
			'title'  => '添加',
			'accordion_title_by' => array( 'group_help_title' ),
			'accordion_title_number' => true,

			'fields' => array(
				array(
					'id'      => 'group_help_title',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '标题',
				),

				array(
					'id'        => 'group_help_text',
					'type'      => 'wp_editor',
					'title'     => '内容',
					'height'    => '150px',
					'sanitize'  => false,
					'media_buttons' => false,
				),
			),

			'default' => array(
				array(
					'group_help_title' => '专业在线咨询',
					'group_help_text'  => 'HTML5+CSS3 响应式设计，博客、杂志、图片、公司企业多种布局可选，集成SEO自定义功能，丰富的主题选项，众多实用小工具。',
				),

				array(
					'group_help_title' => '专业在线咨询',
					'group_help_text'  => 'HTML5+CSS3 响应式设计，博客、杂志、图片、公司企业多种布局可选，集成SEO自定义功能，丰富的主题选项，众多实用小工具。',
				),

				array(
					'group_help_title' => '专业在线咨询',
					'group_help_text'  => 'HTML5+CSS3 响应式设计，博客、杂志、图片、公司企业多种布局可选，集成SEO自定义功能，丰富的主题选项，众多实用小工具。',
				),
			)
		),

		array(
			'id'       => 'group_help_num',
			'class'    => 'be-child-item be-child-last-item',
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

ZMOP::createSection( $prefix, array(
	'title'       => '工具',
	'icon'        => 'dashicons dashicons-admin-tools',
	'description' => $repeat,
	'fields'      => array(

		array(
			'id'       => 'group_tool',
			'type'     => 'switcher',
			'title'    => '工具',
			'default'  => true,
		),

		array(
			'id'       => 'tool_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认 12</span>',
			'default'  => 12,
		),

		array(
			'id'      => 'tool_bg',
			'type'    => 'radio',
			'title'   => '背景颜色',
			'inline'  => true,
			'options' => array(
				'auto'  => '自动',
				'white' => '白色',
				'gray'  => '灰色',
			),
			'default'   => 'auto',
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
			'id'     => 'group_tool_item',
			'class'  => 'be-child-item',
			'type'   => 'group',
			'title'  => '添加',
			'accordion_title_by' => array( 'group_tool_title' ),
			'accordion_title_number' => true,

			'fields' => array(
				array(
					'id'      => 'group_tool_title',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '标题',
				),

				array(
					'id'        => 'group_tool_txt',
					'type'      => 'wp_editor',
					'title'     => '内容',
					'height'    => '150px',
					'sanitize'  => false,
					'media_buttons' => false,
				),

				array(
					'id'      => 'group_tool_img',
					'class'   => 'be-child-item',
					'type'    => 'upload',
					'title'   => '图片',
					'preview' => true,
				),

				array(
					'id'      => 'group_tool_ico',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '图标',
				),

				array(
					'id'      => 'group_tool_svg',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '彩色图标',
				),

				array(
					'id'      => 'group_tool_btn',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '按钮文字',
				),

				array(
					'id'      => 'group_tool_url',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '按钮链接',
				),

			),

			'default' => array(
				array(
					'group_tool_title' => '标题',
					'group_tool_ico'   => 'be be-eye',
					'group_tool_svg'   => '',
					'group_tool_txt'   => '内容文字',
					'group_tool_btn'   => '按钮',
					'group_tool_url'   => '#',
					'group_tool_img'   => $imgdefault . '/random/320.jpg',
				),

				array(
					'group_tool_title' => '标题',
					'group_tool_ico'   => 'be be-schedule',
					'group_tool_svg'   => '',
					'group_tool_txt'   => '内容文字',
					'group_tool_btn'   => '按钮',
					'group_tool_url'   => '#',
					'group_tool_img'   => $imgdefault . '/random/320.jpg',
				),

				array(
					'group_tool_title' => '标题',
					'group_tool_ico'   => 'be be-favoriteoutline',
					'group_tool_svg'   => '',
					'group_tool_txt'   => '内容文字',
					'group_tool_btn'   => '按钮',
					'group_tool_url'   => '#',
					'group_tool_img'   => $imgdefault . '/random/320.jpg',
				),

				array(
					'group_tool_title' => '标题',
					'group_tool_ico'   => 'be be-skyatlas',
					'group_tool_svg'   => '',
					'group_tool_txt'   => '内容文字',
					'group_tool_btn'   => '按钮',
					'group_tool_url'   => '#',
					'group_tool_img'   => $imgdefault . '/random/320.jpg',
				),
			)
		),

		array(
			'id'      => 'stool_f',
			'type'    => 'radio',
			'title'   => '分栏',
			'inline'  => true,
			'options' => $fl3456,
			'default' => '4',
		),

		array(
			'id'       => 'group_tool_txt_c',
			'type'     => 'switcher',
			'title'    => '说明文字居中',
			'default'  => true,
		),

		array(
			'id'       => 'group_tool_txt_h',
			'type'     => 'switcher',
			'title'    => '移动端隐藏说明文字',
		),
	)
));


ZMOP::createSection( $prefix, array(
	'title'       => '项目模块',
	'icon'        => 'dashicons dashicons-tide',
	'description' => '',
	'fields'      => array(

		array(
			'id'       => 'group_show',
			'type'     => 'switcher',
			'title'    => '项目模块',
		),

		array(
			'id'       => 'group_show_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认 13</span>',
			'default'  => 13,
		),

		array(
			'id'      => 'show_bg',
			'type'    => 'radio',
			'title'   => '背景颜色',
			'inline'  => true,
			'options' => array(
				'auto'  => '自动',
				'white' => '白色',
				'gray'  => '灰色',
			),
			'default'   => 'auto',
		),

		array(
			'id'       => 'group_show_n',
			'type'     => 'number',
			'title'    => '篇数',
			'default'  => 4,
		),

		array(
			'id'       => 'group_show_t',
			'type'     => 'text',
			'title'    => '标题',
			'default'  => '主要项目',
		),

		array(
			'id'       => 'group_show_des',
			'type'     => 'text',
			'title'    => '说明',
			'default'  => '项目日志模块',
		),

		array(
			'id'       => 'group_show_id',
			'type'     => 'text',
			'title'    => '输入分类ID',
			'after'    => $mid,
		),

		array(
			'id'       => 'group_show_url',
			'type'     => 'text',
			'title'    => '输入更多按钮链接地址',
			'after'    => '留空则不显示',
		),

		array(
			'id'      => 'group_show_f',
			'type'    => 'radio',
			'title'   => '分栏',
			'inline'  => true,
			'options' => $fl456,
			'default' => '4',
		),
	)
));

ZMOP::createSection( $prefix, array(
	'title'       => '服务宗旨',
	'icon'        => 'dashicons dashicons-feedback',
	'description' => '',
	'fields'      => array(

		array(
			'id'       => 'service',
			'type'     => 'switcher',
			'title'    => '服务宗旨',
			'default'  => true,
		),

		array(
			'id'       => 'service_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认 14</span>',
			'default'  => 14,
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
			'id'       => 'service_bg_img',
			'type'     => 'upload',
			'title'    => '背景图片',
			'default'  => $imgdefault . '/options/1200.jpg',
			'preview'  => true,
		),

		array(
			'id'       => 'service_c_img',
			'type'     => 'upload',
			'title'    => '中间模块图片',
			'default'  => $imgdefault . '/options/1200.jpg',
			'preview'  => true,
		),

		array(
			'id'        => 'service_c_txt',
			'type'      => 'wp_editor',
			'title'     => '内容',
			'height'    => '150px',
			'sanitize'  => false,
			'default'   => '<p>HTML5+CSS3 响应式设计，博客、杂志、图片、公司企业多种布局可选，集成SEO自定义功能，丰富的主题选项，众多实用小工具。</p>',
		),

		array(
			'id'     => 'group_service_l',
			'class'  => 'be-child-item',
			'type'   => 'group',
			'title'  => '添加左侧模块',
			'accordion_title_by' => array( 'group_service_title_l' ),
			'accordion_title_number' => true,

			'fields' => array(
				array(
					'id'      => 'group_service_title_l',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '标题',
				),

				array(
					'id'       => 'group_service_txt_l',
					'class'   => 'be-child-item textarea-30',
					'type'     => 'textarea',
					'title'    => '描述',
					'sanitize' => false,
				),

				array(
					'id'      => 'group_service_ico_l',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '图标',
				),

				array(
					'id'       => 'group_service_img_l',
					'class'    => 'be-child-item',
					'type'     => 'upload',
					'title'    => '图片',
					'preview'  => true,
				),
			),

			'default' => array(
				array(
					'group_service_title_l' => '模块标题',
					'group_service_ico_l'   => 'be be-search',
					'group_service_img_l'   => '',
					'group_service_txt_l'   => '输入一段简短的模块文字描述',
				),

				array(
					'group_service_title_l' => '模块标题',
					'group_service_ico_l'   => 'be be-schedule',
					'group_service_img_l'   => '',
					'group_service_txt_l'   => '输入一段简短的模块文字描述',
				),

				array(
					'group_service_title_l' => '模块标题',
					'group_service_ico_l'   => 'be be-skyatlas',
					'group_service_img_l'   => '',
					'group_service_txt_l'   => '输入一段简短的模块文字描述',
				),
			)
		),

		array(
			'id'     => 'group_service_r',
			'class'  => 'be-child-item',
			'type'   => 'group',
			'title'  => '添加右侧模块',
			'accordion_title_by' => array( 'group_service_title_r' ),
			'accordion_title_number' => true,

			'fields' => array(
				array(
					'id'      => 'group_service_title_r',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '标题',
				),

				array(
					'id'       => 'group_service_txt_r',
					'class'   => 'be-child-item textarea-30',
					'type'     => 'textarea',
					'title'    => '描述',
					'sanitize' => false,
				),

				array(
					'id'      => 'group_service_ico_r',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '图标',
				),

				array(
					'id'       => 'group_service_img_r',
					'class'    => 'be-child-item',
					'type'     => 'upload',
					'title'    => '图片',
					'preview'  => true,
				),

			),

			'default' => array(
				array(
					'group_service_title_r' => '模块标题',
					'group_service_ico_r'   => 'be be-thumbs-up-o',
					'group_service_img_r'   => '',
					'group_service_txt_r'   => '输入一段简短的模块文字描述',
				),

				array(
					'group_service_title_r' => '模块标题',
					'group_service_ico_r'   => 'be be-email',
					'group_service_img_r'   => '',
					'group_service_txt_r'   => '输入一段简短的模块文字描述',
				),

				array(
					'group_service_title_r' => '模块标题',
					'group_service_ico_r'   => 'be be-favoriteoutline',
					'group_service_img_r'   => '',
					'group_service_txt_r'   => '输入一段简短的模块文字描述',
				),
			)
		),
	)
));

ZMOP::createSection( $prefix, array(
	'title'       => 'WOO产品',
	'icon'        => 'dashicons dashicons-cart',
	'description' => '需要安装商城插件 WooCommerce 并发表产品',
	'fields'      => array(

		array(
			'id'       => 'g_product',
			'type'     => 'switcher',
			'title'    => 'WOO产品',
		),

		array(
			'id'       => 'g_product_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认 15</span>',
			'default'  => 15,
		),

		array(
			'id'      => 'product_bg',
			'type'    => 'radio',
			'title'   => '背景颜色',
			'inline'  => true,
			'options' => array(
				'auto'  => '自动',
				'white' => '白色',
				'gray'  => '灰色',
			),
			'default'   => 'auto',
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

ZMOP::createSection( $prefix, array(
	'title'       => '特色',
	'icon'        => 'dashicons dashicons-share-alt',
	'description' => $repeat,
	'fields'      => array(

		array(
			'id'       => 'group_ico',
			'type'     => 'switcher',
			'title'    => '特色',
			'default'  => true,
		),

		array(
			'id'       => 'group_ico_s',
			'type'     => 'number',
			'title'    => '排序',
			'default'  => 16,
			'after'    => '<span class="after-perch">默认 16</span>',
		),

		array(
			'id'      => 'ico_bg',
			'type'    => 'radio',
			'title'   => '背景颜色',
			'inline'  => true,
			'options' => array(
				'auto'  => '自动',
				'white' => '白色',
				'gray'  => '灰色',
			),
			'default'   => 'auto',
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
			'id'     => 'group_ico_item',
			'class'  => 'be-child-item',
			'type'   => 'group',
			'title'  => '添加',
			'accordion_title_by' => array( 'group_ico_title' ),
			'accordion_title_number' => true,

			'fields' => array(
				array(
					'id'      => 'group_ico_title',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '标题',
				),

				array(
					'id'      => 'group_ico_ico',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '图标',
				),

				array(
					'id'      => 'group_ico_svg',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '彩色图标',
				),

				array(
					'id'      => 'group_ico_color',
					'class'   => 'be-child-item',
					'type'    => 'color',
					'title'   => '颜色',
				),

				array(
					'id'       => 'group_ico_txt',
					'class'    => 'be-child-item textarea-30',
					'type'     => 'textarea',
					'title'    => '内容',
					'sanitize' => false,
				),

				array(
					'id'      => 'group_ico_url',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '链接',
				),

				array(
					'id'      => 'group_ico_img',
					'class'   => 'be-child-item',
					'type'    => 'upload',
					'title'   => '图片',
					'preview' => true,
				),

			),

			'default' => array(
				array(
					'group_ico_title' => '标题',
					'group_ico_ico'   => 'be be-editor',
					'group_ico_svg'   => '',
					'group_ico_color' => '#e38b8d',
					'group_ico_txt'   => '内容文字',
					'group_ico_url'   => '#',
					'group_ico_img'   => '',
				),

				array(
					'group_ico_title' => '标题',
					'group_ico_ico'   => 'be be-schedule',
					'group_ico_svg'   => '',
					'group_ico_color' => '#a87d94',
					'group_ico_txt'   => '内容文字',
					'group_ico_url'   => '#',
					'group_ico_img'   => '',
				),

				array(
					'group_ico_title' => '标题',
					'group_ico_ico'   => 'be be-editor',
					'group_ico_svg'   => '',
					'group_ico_color' => '#89b8cd',
					'group_ico_txt'   => '内容文字',
					'group_ico_url'   => '#',
					'group_ico_img'   => '',
				),

				array(
					'group_ico_title' => '标题',
					'group_ico_ico'   => 'be be-schedule',
					'group_ico_svg'   => '',
					'group_ico_color' => '#afb4aa',
					'group_ico_txt'   => '内容文字',
					'group_ico_url'   => '#',
					'group_ico_img'   => '',
				),

				array(
					'group_ico_title' => '标题',
					'group_ico_ico'   => 'be be-editor',
					'group_ico_svg'   => '',
					'group_ico_color' => '#d6c2c1',
					'group_ico_txt'   => '内容文字',
					'group_ico_url'   => '#',
					'group_ico_img'   => '',
				),

				array(
					'group_ico_title' => '标题',
					'group_ico_ico'   => 'be be-schedule',
					'group_ico_svg'   => '',
					'group_ico_color' => '#feaba3',
					'group_ico_txt'   => '内容文字',
					'group_ico_url'   => '#',
					'group_ico_img'   => '',
				),
			)
		),

		array(
			'id'      => 'grid_ico_group_n',
			'type'    => 'radio',
			'title'   => '分栏',
			'inline'  => true,
			'options' => array(
				'2' => '两栏',
				'4' => '四栏',
				'5' => '五栏',
				'6' => '六栏',
				'7' => '七栏',
				'8' => '八栏'
				),
			'default' => '6',
		),

		array(
			'id'       => 'group_ico_b',
			'type'     => 'switcher',
			'title'    => '图标无背景色',
		),

		array(
			'id'       => 'group_img_ico',
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


ZMOP::createSection( $prefix, array(
	'title'       => '描述',
	'icon'        => 'dashicons dashicons-welcome-write-blog',
	'description' => '',
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
			'default'  => 17,
			'after'    => '<span class="after-perch">默认 17</span>',
		),

		array(
			'id'      => 'post_bg',
			'type'    => 'radio',
			'title'   => '背景颜色',
			'inline'  => true,
			'options' => array(
				'auto'  => '自动',
				'white' => '白色',
				'gray'  => '灰色',
			),
			'default'   => 'auto',
		),

		array(
			'id'       => 'group_post_id',
			'type'     => 'text',
			'title'    => '输入文章或页面ID',
			'after'    => $mid,
		),
	)
));

ZMOP::createSection( $prefix, array(
	'title'       => '简介',
	'icon'        => 'dashicons dashicons-admin-page',
	'description' => '',
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
			'default'  => 18,
			'after'    => '<span class="after-perch">默认 18</span>',
		),

		array(
			'id'      => 'features_bg',
			'type'    => 'radio',
			'title'   => '背景颜色',
			'inline'  => true,
			'options' => array(
				'auto'  => '自动',
				'white' => '白色',
				'gray'  => '灰色',
			),
			'default'   => 'auto',
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
			'title'       => '选择分类',
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

ZMOP::createSection( $prefix, array(
	'title'       => '展示',
	'icon'        => 'dashicons dashicons-welcome-view-site',
	'description' => '',
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
			'default'  => 19,
			'after'    => '<span class="after-perch">默认 19</span>',
		),

		array(
			'id'       => 'group_img_n',
			'type'     => 'number',
			'title'    => '篇数',
			'default'  => 4,
		),

		array(
			'id'      => 'img_bg',
			'type'    => 'radio',
			'title'   => '背景颜色',
			'inline'  => true,
			'options' => array(
				'auto'  => '自动',
				'white' => '白色',
				'gray'  => '灰色',
			),
			'default'   => 'auto',
		),

		array(
			'id'       => 'group_img_id',
			'type'     => 'text',
			'title'    => '输入分类ID',
			'after'  => $mid .'，支持所有分类法',
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

ZMOP::createSection( $prefix, array(
	'title'       => '计数器',
	'icon'        => 'dashicons dashicons-clock',
	'description' => $repeat,
	'fields'      => array(

		array(
			'id'       => 'group_counter',
			'type'     => 'switcher',
			'title'    => '启用',
			'default'  => true,
		),

		array(
			'id'       => 'counter_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认 20</span>',
			'default'  => 20,
		),

		array(
			'id'      => 'group_counter_f',
			'type'    => 'radio',
			'title'   => '分栏',
			'inline'  => true,
			'options' => $fl234,
			'default' => 3,
		),

		array(
			'id'      => 'group_counter_bg',
			'type'    => 'upload',
			'title'   => '背景图片',
			'preview' => true,
			'default' => $imgdefault . '/options/1200.jpg',
		),

		array(
			'id'     => 'group_counter_item',
			'type'   => 'group',
			'title'  => '添加',
			'accordion_title_by' => array( 'group_counter_title' ),
			'accordion_title_number' => true,

			'fields' => array(
				array(
					'id'      => 'group_counter_title',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '标题',
				),

				array(
					'id'      => 'group_counter_num',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '数值',
				),

				array(
					'id'      => 'group_counter_speed',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '速度（在多少秒内达到设定的数值）',
				),

				array(
					'id'      => 'group_counter_unit',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '后缀',
				),

				array(
					'id'      => 'group_counter_ico',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '图标',
				),

				array(
					'id'      => 'group_counter_color',
					'class'   => 'be-child-item',
					'type'    => 'color',
					'title'   => '图标颜色',
				),

			),

			'default' => array(
				array(
					'group_counter_title' => '浏览量',
					'group_counter_num'   => '987654',
					'group_counter_speed' => '80000',
					'group_counter_ico'   => 'be be-favoriteoutline',
					'group_counter_color' => '#fff',
					'group_counter_unit'  => '+',
				),

				array(
					'group_counter_title' => '在线用户',
					'group_counter_num'   => '123456',
					'group_counter_speed' => '80000',
					'group_counter_ico'   => 'be be-skyatlas',
					'group_counter_color' => '#fff',
					'group_counter_unit'  => '+',
				),

				array(
					'group_counter_title' => '集团公司',
					'group_counter_num'   => '65432',
					'group_counter_speed' => '80000',
					'group_counter_ico'   => 'be be-clouddownload',
					'group_counter_color' => '#fff',
					'group_counter_unit'  => '+',
				),

				array(
					'group_counter_title' => '软件著作权',
					'group_counter_num'   => '665',
					'group_counter_speed' => '80000',
					'group_counter_ico'   => 'be be-display',
					'group_counter_color' => '#fff',
					'group_counter_unit'  => '+',
				),

				array(
					'group_counter_title' => '实行标准',
					'group_counter_num'   => '9432',
					'group_counter_speed' => '80000',
					'group_counter_ico'   => 'be be-schedule',
					'group_counter_color' => '#fff',
					'group_counter_unit'  => '+',
				),

				array(
					'group_counter_title' => '用户选择',
					'group_counter_num'   => '58400',
					'group_counter_speed' => '80000',
					'group_counter_ico'   => 'be be-thumbs-up-o',
					'group_counter_color' => '#fff',
					'group_counter_unit'  => '+',
				),

			)
		),

	)
));

ZMOP::createSection( $prefix, array(
	'title'       => '合作',
	'icon'        => 'dashicons dashicons-groups',
	'description' => $repeat,
	'fields'      => array(

		array(
			'id'       => 'group_coop',
			'type'     => 'switcher',
			'title'    => '启用',
			'default'  => true,
		),

		array(
			'id'       => 'group_coop_t',
			'type'     => 'text',
			'title'    => '标题',
			'default'  => '合作伙伴',
		),

		array(
			'id'       => 'group_coop_des',
			'type'     => 'text',
			'title'    => '自定义描述',
			'default'  => '我们合作的伙伴',
		),

		array(
			'id'       => 'coop_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认 21</span>',
			'default'  => 21,
		),

		array(
			'id'      => 'coop_bg',
			'type'    => 'radio',
			'title'   => '背景颜色',
			'inline'  => true,
			'options' => array(
				'auto'  => '自动',
				'white' => '白色',
				'gray'  => '灰色',
			),
			'default'   => 'auto',
		),

		array(
			'id'      => 'group_coop_f',
			'type'    => 'radio',
			'title'   => '分栏',
			'inline'  => true,
			'options' => $fl23456,
			'default' => '4',
		),

		array(
			'id'       => 'group_coop_h',
			'type'     => 'number',
			'title'    => '图片比例',
			'after'    => '<span class="after-perch">默认 40，100为正文形，超过100为竖向长方</span>',
			'default'  => 40,
		),

		array(
			'id'     => 'group_coop_item',
			'type'   => 'group',
			'title'  => '添加',
			'accordion_title_by' => array( 'group_coop_title' ),
			'accordion_title_number' => true,

			'fields' => array(
				array(
					'id'      => 'group_coop_title',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '标题',
				),

				array(
					'id'       => 'group_coop_title_no',
					'class'    => 'be-child-item',
					'type'     => 'switcher',
					'title'    => '不显示标题',
					'default'  => true,
				),

				array(
					'id'       => 'group_coop_gray',
					'class'    => 'be-child-item',
					'type'     => 'switcher',
					'title'    => '灰色图片',
					'default'  => true,
				),

				array(
					'id'      => 'group_coop_img',
					'class'   => 'be-child-item',
					'type'    => 'upload',
					'title'   => '图片',
					'preview' => true,
				),

				array(
					'id'      => 'group_coop_bg',
					'class'   => 'be-child-item',
					'type'    => 'upload',
					'title'   => '翻转图片(可选)',
					'preview' => true,
				),

				array(
					'id'      => 'group_coop_url',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '链接',
				),

			),

			'default' => array(
				array(
					'group_coop_title'    => '标题',
					'group_coop_img'      => $imgdefault . '/random/320.jpg',
					'group_coop_bg'       => '',
					'group_coop_url'      => '',
					'group_coop_title_no' => true,
				),

				array(
					'group_coop_title'    => '标题',
					'group_coop_img'      => $imgdefault . '/random/320.jpg',
					'group_coop_bg'       => '',
					'group_coop_url'      => '',
					'group_coop_title_no' => true,
				),

				array(
					'group_coop_title'    => '标题',
					'group_coop_img'      => $imgdefault . '/random/320.jpg',
					'group_coop_bg'       => '',
					'group_coop_url'      => '',
					'group_coop_title_no' => true,
				),

				array(
					'group_coop_title'    => '标题',
					'group_coop_img'      => $imgdefault . '/random/320.jpg',
					'group_coop_bg'       => '',
					'group_coop_url'      => '',
					'group_coop_title_no' => true,
				),
			)
		),

	)
));

ZMOP::createSection( $prefix, array(
	'title'       => '分类左右图',
	'icon'        => 'dashicons dashicons-format-gallery',
	'description' => '图片调用方法：编辑该分类一篇文章，在下面“将文章添加到”面板中，勾选“分类推荐文章”，并更新发表',
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
			'after'    => '<span class="after-perch">默认 22</span>',
			'default'  => 22,
		),

		array(
			'id'      => 'wd_bg',
			'type'    => 'radio',
			'title'   => '背景颜色',
			'inline'  => true,
			'options' => array(
				'auto'  => '自动',
				'white' => '白色',
				'gray'  => '灰色',
			),
			'default'   => 'auto',
		),

		array(
			'id'       => 'group_wd_id',
			'type'     => 'text',
			'title'    => '输入分类ID',
			'after'    => $mid,
		),
	)
));

ZMOP::createSection( $prefix, array(
	'title'       => '一栏小工具',
	'icon'        => 'dashicons dashicons-align-wide',
	'description' => '',
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
			'after'    => '<span class="after-perch">默认 23</span>',
			'default'  => 23,
		),

		array(
			'id'      => 'widget_one_bg',
			'type'    => 'radio',
			'title'   => '背景颜色',
			'inline'  => true,
			'options' => array(
				'auto'  => '自动',
				'white' => '白色',
				'gray'  => '灰色',
			),
			'default'   => 'auto',
		),

	)
));

ZMOP::createSection( $prefix, array(
	'title'       => '最新文章',
	'icon'        => 'dashicons dashicons-format-aside',
	'description' => 'Ajax最新网站文章',
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
			'after'    => '<span class="after-perch">默认 24</span>',
			'default'  => 24,
		),

		array(
			'id'      => 'new_bg',
			'type'    => 'radio',
			'title'   => '背景颜色',
			'inline'  => true,
			'options' => array(
				'auto'  => '自动',
				'white' => '白色',
				'gray'  => '灰色',
			),
			'default'   => 'auto',
		),

		array(
			'id'       => 'group_new_t',
			'type'     => 'text',
			'title'    => '标题',
			'default'  => '最新文章',
		),

		array(
			'id'       => 'group_new_des',
			'type'     => 'text',
			'title'    => '说明',
			'default'  => '这里是本站最新发表的文章',
		),

		array(
			'id'      => 'group_new_code_style',
			'type'    => 'radio',
			'title'   => '显示模式',
			'inline'  => true,
			'options' => array(
				'grid'    => '卡片',
				'photo'   => '图片',
				'title'   => '标题',
			),
			'default' => 'grid',
		),

		array(
			'id'      => 'group_new_code_f',
			'type'    => 'radio',
			'title'   => '分栏',
			'inline'  => true,
			'options' => $fl23456,
			'default' => '3',
		),

		array(
			'id'       => 'group_new_code_n',
			'type'     => 'number',
			'title'    => '每页篇数',
			'default'  => 6,
		),

		array(
			'id'      => 'group_new_more',
			'type'    => 'radio',
			'title'   => '翻页模式',
			'inline'  => true,
			'options' => array(
				'true'   => '数字翻页',
				'more'   => '更多按钮',
				'full'   => '同时显示',
			),
			'default' => 'full',
		),

		array(
			'id'      => 'group_new_code_no_cat_btn',
			'type'    => 'radio',
			'title'   => '分类按钮',
			'inline'  => true,
			'options' => array(
				'yes'   => '显示',
				'no'    => '不显示',
			),
			'default' => 'no',
		),

		array(
			'id'      => 'group_new_code_cat_chil',
			'type'    => 'radio',
			'title'   => '子分类文章',
			'inline'  => true,
			'options' => array(
				'true'   => '显示',
				'false'  => '不显示',
			),
			'default' => 'false',
		),

		array(
			'id'      => 'group_new_code_id',
			'type'    => 'checkbox',
			'title'   => '选择分类',
			'inline'  => true,
			'options' => 'categories',
			'query_args' => array(
				'orderby'  => 'ID',
				'order'    => 'ASC',
			),
		),
	)
));

ZMOP::createSection( $prefix, array(
	'title'       => '商品模块',
	'icon'        => 'dashicons dashicons-cart',
	'description' => '',
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
			'after'    => '<span class="after-perch">默认 25</span>',
			'default'  => 25,
		),

		array(
			'id'      => 'tao_bg',
			'type'    => 'radio',
			'title'   => '背景颜色',
			'inline'  => true,
			'options' => array(
				'auto'  => '自动',
				'white' => '白色',
				'gray'  => '灰色',
			),
			'default'   => 'auto',
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
			'title'   => '文章排序',
			'inline'  => true,
			'options' => array(
				'time'  => '发表时间',
				'views' => '浏览量',
			),
			'default' => 'time',
		),

		array(
			'id'      => 'g_tao_home_f',
			'type'    => 'radio',
			'title'   => '分栏',
			'inline'  => true,
			'options' => array(
				'4' => '四栏',
				'5' => '五栏',
				'6' => '六栏',
			),

			'default' => '4',
		),

		array(
			'id'       => 'g_tao_h_id',
			'type'     => 'text',
			'title'    => '输入分类ID',
			'after'    => $mid,
		),
	)
));

ZMOP::createSection( $prefix, array(
	'title'       => '三栏小工具',
	'icon'        => 'dashicons dashicons-editor-insertmore',
	'description' => '',
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
			'after'    => '<span class="after-perch">默认 26</span>',
			'default'  => 26,
		),

		array(
			'id'      => 'widget_three_bg',
			'type'    => 'radio',
			'title'   => '背景颜色',
			'inline'  => true,
			'options' => array(
				'auto'  => '自动',
				'white' => '白色',
				'gray'  => '灰色',
			),
			'default'   => 'auto',
		),
	)
));

ZMOP::createSection( $prefix, array(
	'title'       => '新闻资讯A',
	'icon'        => 'dashicons dashicons-editor-table',
	'description' => '',
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
			'default'  => 27,
			'after'    => '<span class="after-perch">默认 27</span>',
		),

		array(
			'id'      => 'cat_a_bg',
			'type'    => 'radio',
			'title'   => '背景颜色',
			'inline'  => true,
			'options' => array(
				'auto'  => '自动',
				'white' => '白色',
				'gray'  => '灰色',
			),
			'default'   => 'auto',
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
			'after'    => '<span class="after-perch">编辑分类一篇文章，在下面“将文章添加到”面板中勾选“分类推荐文章”</span>',
		),
	)
));

ZMOP::createSection( $prefix, array(
	'title'       => '两栏小工具',
	'icon'        => 'dashicons dashicons-columns',
	'description' => '',
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
			'after'    => '<span class="after-perch">默认 28</span>',
			'default'  => 28,
		),

		array(
			'id'      => 'widget_two_bg',
			'type'    => 'radio',
			'title'   => '背景颜色',
			'inline'  => true,
			'options' => array(
				'auto'  => '自动',
				'white' => '白色',
				'gray'  => '灰色',
			),
			'default'   => 'auto',
		),

	)
));

ZMOP::createSection( $prefix, array(
	'title'       => '新闻资讯B',
	'icon'        => 'dashicons dashicons-editor-table',
	'description' => '',
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
			'default'  => 29,
			'after'    => '<span class="after-perch">默认 29</span>',
		),

		array(
			'id'      => 'cat_b_bg',
			'type'    => 'radio',
			'title'   => '背景颜色',
			'inline'  => true,
			'options' => array(
				'auto'  => '自动',
				'white' => '白色',
				'gray'  => '灰色',
			),
			'default'   => 'auto',
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
			'title'    => '第一篇调用分类推荐文章',
			'after'    => '<span class="after-perch">编辑分类一篇文章，在下面“将文章添加到”面板中勾选“分类推荐文章”</span>',
		),
	)
));

ZMOP::createSection( $prefix, array(
	'title'       => 'AJAX分类',
	'icon'        => 'dashicons dashicons-category',
	'description' => 'Ajax方式加载分类',
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
			'default'  => 30,
			'after'    => '<span class="after-perch">默认 30</span>',
		),

		array(
			'id'      => 'tab_bg',
			'type'    => 'radio',
			'title'   => '背景颜色',
			'inline'  => true,
			'options' => array(
				'auto'  => '自动',
				'white' => '白色',
				'gray'  => '灰色',
			),
			'default'   => 'auto',
		),

		array(
			'id'       => 'group_tab_t',
			'type'     => 'text',
			'title'    => '标题',
			'default'  => 'AJAX分类',
		),

		array(
			'id'       => 'group_tab_des',
			'type'     => 'text',
			'title'    => '自定义描述',
			'default'  => '这里是描述',
		),

		array(
			'id'     => 'group_tab_items',
			'class'  => 'be-child-item',
			'type'   => 'group',
			'title'  => '添加',
			'fields' => array(
				array(
					'id'    => 'group_tab_items_title',
					'class' => 'be-child-item',
					'type'  => 'text',
					'title' => '模块名称',
				),

				array(
					'id'       => 'group_tab_cat_id',
					'class'    => 'be-child-item',
					'type'     => 'text',
					'title'    => '输入分类ID',
					'after'    => $mid,
				),

				array(
					'id'       => 'group_tab_n',
					'class'    => 'be-child-item',
					'type'     => 'number',
					'title'    => '每页篇数',
					'after'    => $anh,
				),

				array(
					'id'      => 'group_tab_cat_btn',
					'class'   => 'be-child-item',
					'type'    => 'radio',
					'title'   => '分类按钮',
					'inline'  => true,
					'options' => array(
						'yes' => '显示',
						'no'  => '不显示',
					),
				),

				array(
					'id'      => 'group_tab_cat_chil',
					'class'   => 'be-child-item',
					'type'    => 'radio',
					'title'   => '子分类文章',
					'inline'  => true,
					'options' => array(
						'true'   => '显示',
						'false'  => '不显示',
					),
				),

				array(
					'id'      => 'group_tabs_mode',
					'class'   => 'be-child-item',
					'type'    => 'radio',
					'title'   => '显示模式',
					'inline'  => true,
					'options' => array(
						'photo'    => '图片',
						'grid'     => '卡片',
						'title'    => '标题',
						'img'      => '附加',
					),
				),

				array(
					'id'      => 'group_tabs_f',
					'class'   => 'be-child-item',
					'type'    => 'radio',
					'title'   => '分栏',
					'inline'  => true,
					'options' => $fl3456,
				),

				array(
					'id'      => 'group_tabs_nav_btn',
					'class'   => 'be-child-item',
					'type'    => 'radio',
					'title'   => '翻页模式',
					'inline'  => true,
					'options' => array(
						'turn'   => '数字翻页',
						'more'   => '更多按钮',
						'full'   => '同时显示',
					),
				),

				array(
					'class'   => 'be-home-help',
					'title'   => '附加模式说明',
					'type'    => 'content',
					'dependency' => array( 'group_tabs_mode', '==', 'img' ),
					'content' => '以图片模式为基础，在第一篇位置添加自定义内容',
				),

				array(
					'id'    => 'group_tab_sub_name',
					'class' => 'be-child-item',
					'type'  => 'text',
					'title' => '分类名称',
					'dependency' => array( 'group_tabs_mode', '==', 'img' ),
				),

				array(
					'id'    => 'group_tab_sub_des',
					'class' => 'be-child-item',
					'type'  => 'text',
					'title' => '分类描述',
					'dependency' => array( 'group_tabs_mode', '==', 'img' ),
				),

				array(
					'id'    => 'group_tab_sub_btn',
					'class' => 'be-child-item',
					'type'  => 'text',
					'title' => '链接按钮',
					'dependency' => array( 'group_tabs_mode', '==', 'img' ),
				),

				array(
					'id'    => 'group_tab_sub_btn_url',
					'class' => 'be-child-item',
					'type'  => 'text',
					'title' => '链接地址',
					'dependency' => array( 'group_tabs_mode', '==', 'img' ),
				),

				array(
					'id'      => 'group_tab_sub_color',
					'class'   => 'be-child-item',
					'type'    => 'color',
					'title'   => '文字颜色',
					'dependency' => array( 'group_tabs_mode', '==', 'img' ),
				),

				array(
					'id'       => 'group_tab_sub_big',
					'class'    => 'be-child-item',
					'type'     => 'switcher',
					'title'    => '大字号',
					'dependency' => array( 'group_tabs_mode', '==', 'img' ),
				),

				array(
					'id'       => 'group_tab_sub_thumb',
					'class'    => 'be-child-item',
					'type'     => 'upload',
					'title'    => '上传图片',
					'preview'  => true,
					'dependency' => array( 'group_tabs_mode', '==', 'img' ),
				),
			),

			'default' => array(
				array(
					'group_tab_items_title' => '模块A',
					'group_tab_cat_id'      => '1',
					'group_tab_n'           => '10',
					'group_tabs_mode'       => 'photo',
					'group_tabs_f'          => '5',
					'group_tabs_nav_btn'    => 'full',
					'group_tab_cat_chil'    => true,
					'group_tab_cat_btn'     => 'yes',
					'group_tab_sub_name'   => '分类名称',
					'group_tab_sub_des'     => '分类描述',
					'group_tab_sub_btn'     => '链接按钮',
					'group_tab_sub_btn_url' => '链接地址',
					'group_tab_sub_color' => '',
					'group_tab_sub_big' => '',
					'group_tab_sub_thumb'   => $imgdefault . '/random/320.jpg',
				),

			)
		),

		array(
			'id'       => 'group_tab_title_h',
			'type'     => 'switcher',
			'title'    => '图片模式标题浮在图片上',
			'default'  => true,
		),

		array(
			'id'      => 'group_tab_title_m',
			'class'   => 'be-child-item be-sub-last-item',
			'type'    => 'radio',
			'title'   => '显示模式',
			'inline'  => true,
			'options' => array(
				'yes' => '一直显示',
				'no'  => '悬停显示',
			),
			'default' => 'yes',
		),

		array(
			'id'       => 'group_tab_title_c',
			'type'     => 'switcher',
			'title'    => '图片模式标题居中',
			'default'  => true,
		),

		array(
			'id'       => 'group_tab_img_meta',
			'type'     => 'switcher',
			'title'    => '图片模式显示文章信息',
		),
	)
));

ZMOP::createSection( $prefix, array(
	'title'       => '展望',
	'icon'        => 'dashicons dashicons-image-filter',
	'description' => '',
	'fields'      => array(

		array(
			'id'       => 'group_outlook',
			'type'     => 'switcher',
			'title'    => '启用',
			'default'  => true,
		),

		array(
			'id'       => 'group_outlook_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认 31</span>',
			'default'  => 31,
		),

		array(
			'id'       => 'group_outlook_t',
			'type'     => 'text',
			'title'    => '标题',
			'default'  => '展示未来',
		),

		array(
			'id'        => 'group_outlook_content',
			'type'      => 'wp_editor',
			'title'     => '内容',
			'height'    => '150px',
			'sanitize'  => false,
			'default'   => '<p>HTML5+CSS3 响应式设计，博客、杂志、图片、公司企业多种布局可选，集成SEO自定义功能，丰富的主题选项，众多实用小工具。</p>',
		),

		array(
			'id'       => 'group_outlook_more',
			'type'     => 'text',
			'title'    => '按钮文字',
			'default'  => '展望按钮',
		),

		array(
			'id'       => 'group_outlook_url',
			'type'     => 'text',
			'title'    => '链接',
		),

		array(
			'id'       => 'group_outlook_btn',
			'type'     => 'text',
			'title'    => '按钮文字',
			'default'   => '展望按钮',
		),

		array(
			'id'       => 'group_btn_url',
			'type'     => 'text',
			'title'    => '链接',
		),

		array(
			'id'       => 'group_outlook_bg',
			'type'     => 'upload',
			'title'    => '上传背景图片',
			'default'  => $imgdefault . '/options/1200.jpg',
			'preview'  => true,
		),

		array(
			'id'       => 'group_outlook_water',
			'type'     => 'switcher',
			'title'    => '动画',
			'default'  => true,
		),

	)
));

ZMOP::createSection( $prefix, array(
	'title'       => '新闻资讯C',
	'icon'        => 'dashicons dashicons-editor-table',
	'description' => '',
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
			'default'  => 32,
			'after'    => '<span class="after-perch">默认 32</span>',
		),

		array(
			'id'      => 'cat_c_bg',
			'type'    => 'radio',
			'title'   => '背景颜色',
			'inline'  => true,
			'options' => array(
				'auto'  => '自动',
				'white' => '白色',
				'gray'  => '灰色',
			),
			'default'   => 'auto',
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

ZMOP::createSection( $prefix, array(
	'title'       => '热门推荐',
	'icon'        => 'dashicons dashicons-buddicons-groups',
	'description' => '',
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
			'default'  => 33,
			'after'    => '<span class="after-perch">默认 33</span>',
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
			'class'    => 'be-child-item be-sub-last-item',
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

ZMOP::createSection( $prefix, array(
	'title'       => '新闻资讯D',
	'icon'        => 'dashicons dashicons-editor-table',
	'description' => '',
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
			'default'  => 34,
			'after'    => '<span class="after-perch">默认 34</span>',
		),

		array(
			'id'      => 'cat_d_bg',
			'type'    => 'radio',
			'title'   => '背景颜色',
			'inline'  => true,
			'options' => array(
				'auto'  => '自动',
				'white' => '白色',
				'gray'  => '灰色',
			),
			'default'   => 'auto',
		),

		array(
			'id'       => 'group_cat_d_n',
			'type'     => 'number',
			'title'    => '篇数',
			'default'  => 8,
		),

		array(
			'id'     => 'group_cat_d_item',
			'class'  => 'be-child-item',
			'type'   => 'group',
			'title'  => '添加',
			'accordion_title_by' => array( 'group_cat_d_id' ),
			'accordion_title_number' => false,
			'accordion_title_prefix' => '分类',

			'fields' => array(
				array(
					'id'          => 'group_cat_d_id',
					'type'        => 'select',
					'title'       => '选择分类',
					'placeholder' => '选择分类',
					'options'     => 'categories',
				),

				array(
					'id'       => 'group_cat_d_img',
					'type'     => 'upload',
					'title'    => '上传图片',
					'default'  => $imgdefault . '/random/560.jpg',
					'preview'  => true,
				),

			),
			'default' => array(
				array(
					'group_cat_d_id'      => '1',
					'group_cat_d_img'         => $imgdefault . '/random/560.jpg',
				),

				array(
					'group_cat_d_id'      => '1',
					'group_cat_d_img'         => $imgdefault . '/random/560.jpg',
				),
			)
		),
	)
));

ZMOP::createSection( $prefix, array(
	'title'       => '分类短代码',
	'icon'        => 'dashicons dashicons-welcome-widgets-menus',
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
			'after'    => '<span class="after-perch">默认 35</span>',
			'default'  => 35,
		),

		array(
			'id'      => 'ajax_bg',
			'type'    => 'radio',
			'title'   => '背景颜色',
			'inline'  => true,
			'options' => array(
				'auto'  => '自动',
				'white' => '白色',
				'gray'  => '灰色',
			),
			'default'   => 'auto',
		),

		array(
			'id'       => 'group_ajax_cat_post_code',
			'class'    => 'textarea-30',
			'type'     => 'textarea',
			'title'    => '输入短代码',
			'default'  => '[be_ajax_post]',
		),

		array(
			'class'    => 'be-help-code',
			'title'    => '短代码示例',
			'type'     => 'content',
			'content'  => $shortcode_help,
		),
	)
));

ZMOP::createSection( $prefix, array(
	'title'       => '会员商品',
	'icon'        => 'dashicons dashicons-carrot',
	'description' => '需配合 ErphpDown 插件',
	'fields'      => array(

		array(
			'id'       => 'group_assets',
			'type'     => 'switcher',
			'title'    => '会员商品',
		),

		array(
			'id'       => 'group_assets_t',
			'type'     => 'text',
			'title'    => '标题',
			'default' => '推荐商品',
		),

		array(
			'id'       => 'group_assets_des',
			'type'     => 'text',
			'title'    => '说明',
			'default' => '推荐商品说明',
		),

		array(
			'id'       => 'group_assets_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认 36</span>',
			'default'  => 36,
		),

		array(
			'id'      => 'assets_bg',
			'type'    => 'radio',
			'title'   => '背景颜色',
			'inline'  => true,
			'options' => array(
				'auto'  => '自动',
				'white' => '白色',
				'gray'  => '灰色',
			),
			'default'   => 'auto',
		),

		array(
			'id'       => 'group_assets_n',
			'type'     => 'number',
			'title'    => '篇数',
			'default'  => 5,
		),

		array(
			'id'      => 'group_assets_get',
			'type'    => 'radio',
			'title'   => '调用模式',
			'inline'  => true,
			'options' => array(
				'cat'   => '分类',
				'post'  => '文章',
			),
			'default' => 'cat',
		),

		array(
			'id'       => 'group_assets_id',
			'type'     => 'text',
			'title'    => '输入分类ID',
			'after'    => $mid . '，支持所有分类法',
		),

		array(
			'id'      => 'group_assets_post_id',
			'type'    => 'text',
			'title'   => '输入文章ID',
			'after'   => '输入文章ID，多个ID用英文半角逗号","隔开，按先后排序，可调用所有类型文章',
		),

		array(
			'id'      => 'group_assets_f',
			'type'    => 'radio',
			'title'   => '分栏',
			'inline'  => true,
			'options' => array(
				'4' => '四栏',
				'5' => '五栏',
				'6' => '六栏',
			),
			'default' => '5',
		),
	)
));

ZMOP::createSection( $prefix, array(
	'title'       => '软件下载',
	'icon'        => 'dashicons dashicons-cloud',
	'description' => '可与 ErphpDown 插件配合使用',
	'fields'      => array(

		array(
			'id'       => 'group_down',
			'type'     => 'switcher',
			'title'    => '软件下载',
		),

		array(
			'id'       => 'group_down_t',
			'type'     => 'text',
			'title'    => '标题',
			'default'  => '软件下载',
		),

		array(
			'id'       => 'group_down_des',
			'type'     => 'text',
			'title'    => '说明',
			'default'  => '软件下载说明',
		),

		array(
			'id'       => 'group_down_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认 37</span>',
			'default'  => 37,
		),

		array(
			'id'      => 'down_bg',
			'type'    => 'radio',
			'title'   => '背景颜色',
			'inline'  => true,
			'options' => array(
				'auto'  => '自动',
				'white' => '白色',
				'gray'  => '灰色',
			),
			'default'   => 'auto',
		),

		array(
			'id'       => 'group_down_n',
			'type'     => 'number',
			'title'    => '篇数',
			'default'  => 6,
		),

		array(
			'id'      => 'group_down_get',
			'type'    => 'radio',
			'title'   => '调用模式',
			'inline'  => true,
			'options' => array(
				'cat'   => '分类',
				'post'  => '文章',
			),
			'default' => 'cat',
		),

		array(
			'id'       => 'group_down_id',
			'type'     => 'text',
			'title'    => '输入分类ID',
			'after'    => $mid,
		),

		array(
			'id'      => 'group_down_post_id',
			'type'    => 'text',
			'title'   => '输入文章ID',
			'after'   => '输入文章ID，多个ID用英文半角逗号","隔开，按先后排序',
		),

		array(
			'id'      => 'group_down_f',
			'type'    => 'radio',
			'title'   => '分栏',
			'inline'  => true,
			'options' => array(
				'2' => '两栏',
				'3' => '三栏',
				'4' => '四栏',
			),
			'default' => '3',
		),

		array(
			'id'      => 'group_down_btn_text',
			'type'    => 'text',
			'title'   => '按钮名称',
			'default' => '下载',
		),
	)
));

ZMOP::createSection( $prefix, array(
	'title'       => '分类组合',
	'icon'        => 'dashicons dashicons-index-card',
	'description' => '左侧分类最新4篇文章，中间10篇并排除前4篇文章，右侧最早的3篇文章',
	'fields'      => array(

		array(
			'id'       => 'group_portfolio',
			'type'     => 'switcher',
			'title'    => '分类组合',
			'default'  => true,
		),

		array(
			'id'       => 'group_portfolio_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认 38</span>',
			'default'  => 38,
		),

		array(
			'id'      => 'portfolio_bg',
			'type'    => 'radio',
			'title'   => '背景颜色',
			'inline'  => true,
			'options' => array(
				'auto'  => '自动',
				'white' => '白色',
				'gray'  => '灰色',
			),
			'default'   => 'auto',
		),

		array(
			'id'       => 'group_portfolio_id',
			'type'     => 'text',
			'title'    => '输入分类ID',
			'after'    => $mid,
		),

	)
));

ZMOP::createSection( $prefix, array(
	'title'       => '热门分类',
	'icon'        => 'dashicons dashicons-pets',
	'description' => '左上最新2篇文章，左下排除前2篇文章，右侧本分类浏览最多的文章',
	'fields'      => array(

		array(
			'id'       => 'group_cat_hot',
			'type'     => 'switcher',
			'title'    => '热门分类',
			'default'  => true,
		),

		array(
			'id'       => 'group_cat_hot_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认 39</span>',
			'default'  => 39,
		),

		array(
			'id'      => 'hot_bg',
			'type'    => 'radio',
			'title'   => '背景颜色',
			'inline'  => true,
			'options' => array(
				'auto'  => '自动',
				'white' => '白色',
				'gray'  => '灰色',
			),
			'default'   => 'auto',
		),

		array(
			'id'       => 'group_cat_hot_id',
			'type'     => 'text',
			'title'    => '输入分类ID',
			'after'    => $mid,
		),

		array(
			'id'       => 'group_hot_day',
			'type'     => 'number',
			'title'    => '热门文章',
			'after'    => '<span class="after-perch">默认90天内，点击最多的文章</span>',
			'default'  => 90,
		),

		array(
			'id'       => 'group_cat_hot_date',
			'type'     => 'switcher',
			'title'    => '显示日期',
			'default'  => true,
		),
	)
));

ZMOP::createSection( $prefix, array(
	'title'       => '图文幻灯',
	'icon'        => 'dashicons dashicons-welcome-view-site',
	'description' => $repeat,
	'fields'      => array(

		array(
			'id'       => 'group_slides_text',
			'type'     => 'switcher',
			'title'    => '启用',
			'default'  => true,
		),

		array(
			'id'       => 'group_slides_text_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认 40</span>',
			'default'  => 40,
		),

		array(
			'id'     => 'group_slides_text_add',
			'class'  => 'be-child-item',
			'type'   => 'group',
			'title'  => '添加模块',
			'accordion_title_by' => array( 'group_slides_text_name' ),
			'accordion_title_number' => true,
			'fields' => array(
				array(
					'id'      => 'group_slides_text_name',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '模块名称',
				),

				array(
					'id'      => 'slides_text_bg',
					'class'    => 'be-child-item be-child-last-item',
					'type'    => 'radio',
					'title'   => '背景颜色',
					'inline'  => true,
					'options' => array(
						'auto'  => '自动',
						'white' => '白色',
						'gray'  => '灰色',
					),
				),

				array(
					'id'       => 'group_slides_text_per',
					'class'    => 'be-child-item',
					'type'     => 'switcher',
					'title'    => '文字宽',
				),

				array(
					'id'       => 'group_slides_text_r',
					'class'    => 'be-child-item',
					'type'     => 'switcher',
					'title'    => '图片居左',
				),

				array(
					'id'     => 'group_slides_text_item',
					'class'  => 'be-child-item',
					'type'   => 'group',
					'title'  => '添加幻灯',
					'accordion_title_number' => true,
					'fields' => array(

						array(
							'id'      => 'group_slides_text_title',
							'class'   => 'be-child-item',
							'type'    => 'text',
							'title'   => '标题',
						),

						array(
							'id'        => 'group_slides_text_des',
							'class'     => 'be-child-item',
							'type'      => 'wp_editor',
							'title'     => '内容',
							'height'    => '150px',
							'sanitize'  => false,
						),

						array(
							'id'       => 'group_slides_text_ret',
							'class'    => 'be-child-item',
							'type'     => 'switcher',
							'title'    => '段首缩进',
						),

						array(
							'id'      => 'group_slides_text_img',
							'class'   => 'be-child-item',
							'type'    => 'upload',
							'title'   => '图片',
							'preview' => true,
						),

						array(
							'id'      => 'group_slides_text_btn',
							'class'   => 'be-child-item',
							'type'    => 'text',
							'title'   => '按钮',
						),

						array(
							'id'      => 'group_slides_text_btn_url',
							'class'   => 'be-child-item',
							'type'    => 'text',
							'title'   => '链接',
						),
					),
				),
			),

			'default' => array(
				array(
					'group_slides_text_name'      => '仅用于区分多个模块',
					'slides_text_bg'              => 'auto',
					'group_slides_text_r'         => '',
					'group_slides_text_item'      => array(
						array(
							'group_slides_text_title'    => '标题文字',
							'group_slides_text_des'      => '说明文字',
							'group_slides_text_btn'      => '按钮文字',
							'group_slides_text_btn_url'  => '按钮链接',
							'group_slides_text_img'      => $imgdefault . '/options/1200.jpg',
						),

						array(
							'group_slides_text_title'    => '标题文字',
							'group_slides_text_des'      => '说明文字',
							'group_slides_text_btn'      => '按钮文字',
							'group_slides_text_btn_url'  => '按钮链接',
							'group_slides_text_img'      => $imgdefault . '/options/1200.jpg',
						),
					),
				),
			)
		),
	)
));

ZMOP::createSection( $prefix, array(
	'title'       => '图文简介',
	'icon'        => 'dashicons dashicons-media-default',
	'description' => '',
	'fields'      => array(

		array(
			'id'       => 'group_intro',
			'type'     => 'switcher',
			'title'    => '启用',
			'default'  => true,
		),

		array(
			'id'       => 'group_intro_s',
			'class'    => 'be-child-item',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认 41</span>',
			'default'  => 41,
		),

		array(
			'id'      => 'intro_bg',
			'class'   => 'be-child-item',
			'type'    => 'radio',
			'title'   => '背景颜色',
			'inline'  => true,
			'options' => array(
				'auto'  => '自动',
				'white' => '白色',
				'gray'  => '灰色',
			),
			'default'   => 'auto',
		),

		array(
			'id'       => 'intro_t',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '标题',
			'default'  => '图文简介',
		),

		array(
			'id'       => 'intro_des',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '描述',
			'default'  => '图文简介描述',
		),


		array(
			'id'      => 'intro_f',
			'class'   => 'be-child-item',
			'type'    => 'radio',
			'title'   => '分栏',
			'inline'  => true,
			'options' => $fl3456,
			'default' => '4',
		),

		array(
			'id'     => 'group_intro_item',
			'class'  => 'be-child-item',
			'type'   => 'group',
			'title'  => '添加简介',
			'accordion_title_by' => array( 'group_intro_title' ),
			'accordion_title_number' => true,

			'fields' => array(
				array(
					'id'      => 'group_intro_title',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '标题',
				),

				array(
					'id'      => 'group_intro_url',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '链接',
				),

				array(
					'id'        => 'group_intro_txt',
					'class'     => 'be-child-item',
					'type'      => 'wp_editor',
					'title'     => '内容',
					'height'    => '150px',
					'sanitize'  => false,
				),
			),

			'default' => array(
				array(
					'group_intro_title' => '标题',
					'group_intro_txt'   => '内容文字内容文字内容文字内容文字内容文字内容文字内容文字内容文字',
					'group_intro_url'   => '#',
					'group_intro_img'   => $imgdefault . '/random/320.jpg',
				),

				array(
					'group_intro_title' => '标题',
					'group_intro_txt'   => '内容文字内容文字内容文字内容文字内容文字内容文字内容文字内容文字',
					'group_intro_url'   => '#',
					'group_intro_img'   => $imgdefault . '/random/320.jpg',
				),

				array(
					'group_intro_title' => '标题',
					'group_intro_txt'   => '内容文字内容文字内容文字内容文字内容文字内容文字内容文字内容文字',
					'group_intro_url'   => '#',
					'group_intro_img'   => $imgdefault . '/random/320.jpg',
				),

				array(
					'group_intro_title' => '标题',
					'group_intro_txt'   => '内容文字内容文字内容文字内容文字内容文字内容文字内容文字内容文字',
					'group_intro_url'   => '#',
					'group_intro_img'   => $imgdefault . '/random/320.jpg',
				),
			)
		),

		array(
			'id'       => 'group_intro_txt_c',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '简介文字居中',
		),
	)
));

ZMOP::createSection( $prefix, array(
	'title'       => '解决方案',
	'icon'        => 'dashicons dashicons-edit-large',
	'description' => $repeat,
	'fields'      => array(

		array(
			'id'       => 'group_scheme',
			'type'     => 'switcher',
			'title'    => '启用',
			'default'  => true,
		),

		array(
			'id'      => 'scheme_bg',
			'type'    => 'radio',
			'title'   => '背景颜色',
			'inline'  => true,
			'options' => array(
				'auto'  => '自动',
				'white' => '白色',
				'gray'  => '灰色',
			),
			'default'   => 'auto',
		),

		array(
			'id'       => 'group_scheme_text_hide',
			'class'    => 'be-child-item',
			'type'     => 'switcher',
			'title'    => '默认隐藏说明文字',
		),

		array(
			'id'       => 'group_scheme_s',
			'class'    => 'be-child-item',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认 42</span>',
			'default'  => 42,
		),

		array(
			'id'       => 'group_scheme_img_h',
			'class'    => 'be-child-item',
			'type'     => 'number',
			'title'    => '高度',
			'after'    => '<span class="after-perch">默认 460</span>',
			'default'  => '460',
		),

		array(
			'id'      => 'group_scheme_fl',
			'class'   => 'be-child-item',
			'type'    => 'radio',
			'title'   => '分栏',
			'inline'  => true,
			'options' => $fl246,
			'default' => 4,
		),

		array(
			'id'       => 'group_scheme_t',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '标题',
			'default'  => '解决方案',
		),

		array(
			'id'       => 'group_scheme_des',
			'class'    => 'be-child-item',
			'type'     => 'text',
			'title'    => '描述',
			'default'  => '提供高效、可复用、多场景覆盖的平台能力',
		),

		array(
			'id'     => 'group_scheme_add',
			'class'  => 'be-child-item',
			'type'   => 'group',
			'title'  => '添加模块',
			'accordion_title_by' => array( 'group_scheme_title' ),
			'accordion_title_number' => true,
			'fields' => array(
				array(
					'id'      => 'group_scheme_number',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '大标题',
				),

				array(
					'id'      => 'group_scheme_title',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '小标题',
				),

				array(
					'id'        => 'group_scheme_text',
					'class'     => 'be-child-item',
					'type'      => 'wp_editor',
					'title'     => '说明',
					'height'    => '100px',
					'sanitize'  => false,
				),

				array(
					'id'      => 'group_scheme_img',
					'class'   => 'be-child-item',
					'type'    => 'upload',
					'title'   => '图片',
					'preview' => true,
				),

				array(
					'id'      => 'group_scheme_url',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '链接地址',
				),

			),

			'default' => array(
				array(
					'group_scheme_title'    => '低门槛接入',
					'group_scheme_number'   => '01',
					'group_scheme_text'     => '提供各种音视频能力SDK和可视化的方案配置，助力企业以更低成本和更高效率搭建专属的音视频应用',
					'group_scheme_img'      => $imgdefault . '/random/320.jpg',
					'group_scheme_url'      => '',
				),

				array(
					'group_scheme_title'    => '可扩展性强',
					'group_scheme_number'   => '02',
					'group_scheme_text'     => '开源所有组件，按自己的需求快速个性化改造',
					'group_scheme_img'      => $imgdefault . '/random/320.jpg',
					'group_scheme_url'      => '',
				),

				array(
					'group_scheme_title'    => '全场景支持',
					'group_scheme_number'   => '03',
					'group_scheme_text'     => '覆盖泛娱乐互动直播、电商直播带货、语聊房在线教育等多应用场景。',
					'group_scheme_img'      => $imgdefault . '/random/320.jpg',
					'group_scheme_url'      => '',
				),

				array(
					'group_scheme_title'    => '一站式方案管理',
					'group_scheme_number'   => '04',
					'group_scheme_text'     => '提供基于解决方案的控制台管理平台，可以对创建的音视频应用进行统一管理，提升管理安全性和效率',
					'group_scheme_img'      => $imgdefault . '/random/320.jpg',
					'group_scheme_url'      => '',
				),
			)
		),
	)
));

ZMOP::createSection( $prefix, array(
	'title'       => '联系我们',
	'icon'        => 'dashicons dashicons-email',
	'description' => '用于发送邮件',
	'fields'      => array(

		array(
			'id'       => 'group_email',
			'type'     => 'switcher',
			'title'    => '启用',
			'default'  => true,
		),

		array(
			'id'       => 'group_email_s',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认 43</span>',
			'default'  => 43,
		),

		array(
			'id'       => 'group_email_t',
			'type'     => 'text',
			'title'    => '标题',
			'default'  => '联系我们',
		),

		array(
			'id'        => 'group_email_inf',
			'type'      => 'wp_editor',
			'title'     => '左侧说明',
			'height'    => '150px',
			'sanitize'  => false,
			'default'   => '<strong>联系方式</strong><ul><li>地址：北京市东城区东长安街 01号</li><li>电话：00-11-9999999</li><li>传真：00-11-9999999</li><li>邮编：111111</li><li>网址：zmingcx.com</li><li>微信：zmingcx.</li></ul>',
		),

		array(
			'id'      => 'group_email_bg',
			'type'    => 'upload',
			'title'   => '背景图片',
			'preview' => true,
			'default' => $imgdefault . '/options/1200.jpg',
		),

	)
));

ZMOP::createSection( $prefix, array(
	'title'       => '行业新闻',
	'icon'        => 'dashicons dashicons-testimonial',
	'description' => $repeat,
	'fields'      => array(

		array(
			'id'       => 'group_each',
			'type'     => 'switcher',
			'title'    => '启用',
			'default'  => true,
		),

		array(
			'id'       => 'group_each_s',
			'class'    => 'be-child-item',
			'type'     => 'number',
			'title'    => '排序',
			'after'    => '<span class="after-perch">默认 44</span>',
			'default'  => 44,
		),

		array(
			'id'     => 'group_each_add',
			'class'  => 'be-child-item',
			'type'   => 'group',
			'title'  => '添加模块',
			'accordion_title_by' => array( 'group_each_t' ),
			'accordion_title_number' => true,
			'fields' => array(
				array(
					'id'      => 'each_bg',
					'class'    => 'be-child-item',
					'type'    => 'radio',
					'title'   => '背景颜色',
					'inline'  => true,
					'options' => array(
						'auto'  => '自动',
						'white' => '白色',
						'gray'  => '灰色',
					),
				),

				array(
					'id'       => 'group_each_t',
					'class'    => 'be-child-item',
					'type'     => 'text',
					'title'    => '标题',
					'default'  => '行业新闻',
				),

				array(
					'id'       => 'group_each_des',
					'class'    => 'be-child-item be-child-last-item',
					'type'     => 'text',
					'title'    => '描述',
					'default'  => '为读者提供丰富及时的行业新闻',
				),

				array(
					'id'          => 'group_each_id',
					'class'       => 'be-child-item be-child-last-item',
					'type'        => 'select',
					'title'       => '选择分类',
					'placeholder' => '选择分类',
					'options'     => 'categories',
				),

				array(
					'id'      => 'group_each_bg',
					'class'   => 'be-child-item',
					'type'    => 'switcher',
					'title'   => '图片模式',
				),

				array(
					'id'      => 'group_each_title',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '分类描述',
				),

				array(
					'id'      => 'group_each_img_url',
					'class'   => 'be-child-item',
					'type'    => 'text',
					'title'   => '自定义分类链接',
				),

				array(
					'id'      => 'group_each_img',
					'class'   => 'be-child-item',
					'type'    => 'upload',
					'title'   => '分类图片',
					'preview' => true,
				),

				array(
					'id'      => 'group_each_img_m',
					'class'   => 'be-child-item be-child-last-item',
					'type'    => 'radio',
					'title'   => '图片位置',
					'inline'  => true,
					'options' => array(
						'group_each_img_left'   => '居左',
						'group_each_img_right'  => '居右',
					),
				),
			),

			'default' => array(
				array(
					'group_each_white'    => '',
					'each_bg'             => 'auto',
					'group_each_t'        => '行业新闻',
					'group_each_des'      => '为读者提供丰富及时的行业新闻',
					'group_each_title'    => '行业新闻',
					'group_each_img'      => $imgdefault . '/random/320.jpg',
					'group_each_img_url'  => '',
					'group_each_id'       => 1,
					'group_each_bg'       => '',
					'group_each_img_m'    => 'group_each_img_left',
				),
			)
		),
	)
));

ZMOP::createSection( $prefix, array(
	'title'       => '图片说明',
	'icon'        => 'dashicons dashicons-images-alt2',
	'description' => '左侧图片，右侧自定义说明及按钮',
	'fields'      => array(

		array(
			'id'       => 'group_des',
			'type'     => 'switcher',
			'title'    => '启用',
			'default'  => true,
		),

		array(
			'id'     => 'group_des_item',
			'class'  => 'be-child-item',
			'type'   => 'group',
			'title'  => '添加',
			'accordion_title_by' => array( 'group_des_s', 'group_des_t' ),
			'accordion_title_number' => false,
			'accordion_title_prefix' => '排序',

			'fields' => array(
				array(
					'id'       => 'group_des_t',
					'class'    => 'be-child-item',
					'type'     => 'text',
					'title'    => '标题',
				),

				array(
					'id'       => 'group_des_s',
					'class'    => 'be-child-item',
					'type'     => 'number',
					'title'    => '排序',
					'after'    => '<span class="after-perch">默认 45',
					'default'  => 1,
				),

				array(
					'id'      => 'des_bg',
					'class'    => 'be-child-item',
					'type'    => 'radio',
					'title'   => '背景颜色',
					'inline'  => true,
					'options' => array(
						'auto'  => '自动',
						'white' => '白色',
						'gray'  => '灰色',
					),
				),

				array(
					'id'        => 'group_des_text',
					'class'     => 'be-child-item',
					'type'      => 'wp_editor',
					'title'     => '内容',
					'height'    => '150px',
					'sanitize'  => false,
				),

				array(
					'id'       => 'group_des_indent',
					'class'    => 'be-child-item',
					'type'     => 'switcher',
					'title'    => '段首缩进',
				),

				array(
					'id'       => 'group_des_img',
					'class'    => 'be-child-item',
					'class'    => 'be-child-item',
					'type'     => 'upload',
					'title'    => '上传图片',
					'preview'  => true,
				),

				array(
					'id'      => 'group_des_img_m',
					'class'    => 'be-child-item be-child-last-item',
					'type'    => 'radio',
					'title'   => '图片位置',
					'inline'  => true,
					'options' => array(
						'left'   => '居左',
						'right'  => '居右',
					),
				),

				array(
					'id'       => 'group_des_btn',
					'type'     => 'text',
					'title'    => '按钮文字',
					'after'    => '留空则不显示',
				),

				array(
					'id'       => 'group_des_btn_url',
					'class'    => 'be-child-item be-child-last-item',
					'type'     => 'text',
					'title'    => '链接地址',
				),

			),

			'default' => array(
				array(
					'group_des_s'        => '45',
					'des_bg'             => 'auto',
					'group_des_t'        => '图文说明',
					'group_des_text'     => '<p>HTML5+CSS3 响应式设计，博客、杂志、图片、公司企业多种布局可选，集成SEO自定义功能，丰富的主题选项，众多实用小工具。</p>',
					'group_des_indent'   => true,
					'group_des_img'      => $imgdefault . '/random/560.jpg',
					'group_des_img_m'    => 'left',
					'group_des_btn'      => '链接按钮',
					'group_des_btn_url'  => '链接地址',
				),
			)
		),

	)
));

ZMOP::createSection( $prefix, array(
	'title'       => '专题新闻',
	'icon'        => 'dashicons dashicons-editor-paste-word',
	'description' => '',
	'fields'      => array(

		array(
			'id'       => 'group_special',
			'type'     => 'switcher',
			'title'    => '启用',
			'default'  => true,
		),

		array(
			'id'     => 'group_special_item',
			'class'  => 'be-child-item',
			'type'   => 'group',
			'title'  => '添加',
			'accordion_title_by' => array( 'group_special_s', 'group_special_name' ),
			'accordion_title_number' => false,
			'accordion_title_prefix' => '排序',

			'fields' => array(
				array(
					'id'       => 'group_special_s',
					'class'    => 'be-child-item',
					'type'     => 'number',
					'title'    => '排序',
					'after'    => '<span class="after-perch">默认 46</span>',
					'default'  => 46,
				),

				array(
					'id'      => 'special_bg',
					'class'   => 'be-child-item',
					'type'    => 'radio',
					'title'   => '背景颜色',
					'inline'  => true,
					'options' => array(
						'auto'  => '自动',
						'white' => '白色',
						'gray'  => '灰色',
					),
				),

				array(
					'id'       => 'group_special_name',
					'class'    => 'be-child-item',
					'type'     => 'text',
					'title'    => '分类名称',
				),

				array(
					'id'       => 'group_special_des',
					'class'    => 'be-child-item',
					'type'     => 'text',
					'title'    => '分类说明',
				),

				array(
					'id'      => 'group_pecial_color',
					'class'   => 'be-child-item',
					'type'    => 'color',
					'title'   => '文字颜色',
				),

				array(
					'id'      => 'group_special_img',
					'class'   => 'be-child-item be-child-last-item',
					'type'    => 'upload',
					'title'   => '分类图片',
					'preview' => true,
				),

				array(
					'id'      => 'group_special_img_m',
					'class'   => 'be-child-item',
					'type'    => 'radio',
					'title'   => '图片位置',
					'inline'  => true,
					'options' => array(
						'left'   => '居左',
						'right'  => '居右',
					),
					'default' => 'true',
				),

				array(
					'id'          => 'group_special_id',
					'class'       => 'be-child-item',
					'type'        => 'select',
					'title'       => '选择分类',
					'placeholder' => '选择分类',
					'options'     => 'categories',
				),

				array(
					'id'       => 'group_special_n',
					'class'    => 'be-child-item',
					'type'     => 'number',
					'title'    => '篇数',
					'default'  => '10',
				),

				array(
					'id'      => 'group_special_chil',
					'class'   => 'be-child-item',
					'type'    => 'radio',
					'title'   => '子分类文章',
					'inline'  => true,
					'options' => array(
						'true'   => '显示',
						'false'  => '不显示',
					),
					'default' => 'true',
				),

				array(
					'id'       => 'group_special_paged',
					'class'    => 'be-child-item',
					'type'     => 'switcher',
					'title'    => '显示分页',
					'default'  => true,
				),

			),

			'default' => array(
				array(
					'group_special_white'  => '',
					'group_special_s'      => '46',
					'special_bg'           => 'auto',
					'group_special_name'   => '新闻专题',
					'group_special_des'    => '分类说明文字',
					'group_pecial_color'   => '',
					'group_special_img'    => $imgdefault . '/random/320.jpg',
					'group_special_img_m'  => 'left',
					'group_special_id'     => '1',
					'group_special_n'      => '10',
					'group_special_chil'   => 'true',
					'group_special_paged'  => true,
				),
			)
		),
	)
));

ZMOP::createSection( $prefix, array(
	'title'       => '广告信息',
	'icon'        => 'dashicons dashicons-bell',
	'description' => '无限添加广告信息',
	'fields'      => array(

		array(
			'id'       => 'group_ads',
			'type'     => 'switcher',
			'title'    => '启用',
		),

		array(
			'id'     => 'group_ads_item',
			'class'  => 'be-child-item',
			'type'   => 'group',
			'title'  => '添加广告信息',
			'accordion_title_by' => array( 'group_ads_s', 'group_ads_name' ),
			'accordion_title_number' => false,
			'accordion_title_prefix' => '排序',
			'fields' => array(
				array(
					'id'       => 'group_ads_s',
					'class'    => 'be-child-item',
					'type'     => 'number',
					'title'    => '排序',
					'after'    => '<span class="after-perch">默认 47',
					'default'  => 47,
				),

				array(
					'id'      => 'ads_bg',
					'class'    => 'be-child-item',
					'type'    => 'radio',
					'title'   => '背景颜色',
					'inline'  => true,
					'options' => array(
						'auto'  => '自动',
						'white' => '白色',
						'gray'  => '灰色',
					),
				),

				array(
					'id'       => 'group_ads_name',
					'class'    => 'be-child-item',
					'type'     => 'text',
					'title'    => '名称',
				),

				array(
					'id'       => 'group_ads_txt',
					'class'    => 'be-child-item textarea-30',
					'type'     => 'textarea',
					'title'    => '输入信息',
					'sanitize' => false,
				),

				array(
					'id'        => 'group_ads_visual',
					'class'     => 'be-child-item',
					'type'      => 'wp_editor',
					'height'    => '150px',
					'title'     => '输入信息',
					'sanitize'  => false,
				),
			),

			'default' => array(
				array(
					'group_ads_s'      => '47',
					'ads_bg'           => 'auto',
					'group_ads_name'   => '广告信息1',
					'group_ads_visual' => '',
					'group_ads_txt'    => '<a href="#" target="_blank"><img src="' . $imgdefault . '/options/1200.jpg" alt="广告也精彩" /></a>',

				),
			)
		),
	)
));

ZMOP::createSection( $prefix, array(
	'title'       => '其它',
	'icon'        => 'dashicons dashicons-lightbulb',
	'description' => '',
	'fields'      => array(

		array(
			'id'       => 'group_no_cat_child',
			'type'     => 'switcher',
			'title'    => '显示子分类文章',
			'default'  => true,
		),

		array(
			'id'       => 'g_line',
			'type'     => 'switcher',
			'title'    => '隔行变色',
			'default'  => true,
		),

		array(
			'id'      => 'line_even_color',
			'class'   => 'be-flex-color',
			'type'    => 'color',
			'default' => '',
			'before'  => '偶数背景色',
			'after'   => '默认灰色',
		),

		array(
			'id'      => 'line_odd_color',
			'class'   => 'be-flex-color',
			'type'    => 'color',
			'default' => '',
			'before'  => '奇数背景色',
			'after'   => '默认白色',
		),
	)
));

ZMOP::createSection( $prefix, array(
	'title'       => '备份设置',
	'icon'        => 'dashicons dashicons-update',
	'description' => '将公司主页设置数据导出为“<span style="color: #000;">公司主页备份 + 日期.json</span>”文件，并下载到本地',
	'fields'      => array(

		array(
			'title'   => '',
			'class'   => 'be-des',
			'type'    => 'content',
			'content' => '将导出的“<span style="color: #000;">公司主页备份 + 日期.json</span>”文件用记事本打开',
		),

		array(
			'class' => 'be-child-item',
			'type'  => 'backup_co',
		),

		array(
			'title'   => '',
			'class'   => 'be-des',
			'type'    => 'content',
			'content' => '请不要随意输入内容，并执行导入操作，否则所有设置将消失！',
		),
	)
) );

require get_template_directory() . '/inc/options/cx-options.php';