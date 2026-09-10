<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// 加载资源
add_action( 'wp_enqueue_scripts', 'be_epd_styles', 99 );
function be_epd_styles() {
	if ( zm_get_option( 'no_epd_css' ) ) {
		wp_dequeue_style( 'erphpdown' );
	}
	if ( is_plugin_active( 'erphpdown/erphpdown.php' ) ) {
		wp_enqueue_style( 'be-shop', get_template_directory_uri() . '/shop/css/be-shop.css', array(), version );
		wp_enqueue_style( 'shop-fonts', get_template_directory_uri() . '/shop/css/icofonts/iconfont.css', array(), version );
	}
}

// 加载资源
function vip_img() {
	$img = explode(',' , zm_get_option('random_image_url'));
	$img_array = array_rand($img);
	$vipimg = $img[$img_array];
	echo $vipimg;
}

// 移除插件模块
if ( zm_get_option( 'be_down_show' ) ) {
	remove_filter('the_content', 'erphpdown_content_show');
}

// 菜单VIP按钮
if ( zm_get_option( 'menu_vip' ) ) {
	add_filter( 'wp_nav_menu_items', 'be_menu_vip', 10, 2 );
}
function be_menu_vip ( $items, $args ) {
	if ( $args->theme_location == 'navigation') {
		$items .= '<li class="menu-vip-btu">';
			if ( ! is_user_logged_in() ) {
				$items .= '<a class="menu-vip show-layer cur"><i class="cx cx-svip"></i><span>订购</span></a>';
			} else {
				$items .= '<a class="menu-vip" href="' . zm_get_option('be_vip_but_url') . '"><i class="cx cx-svip"></i><span>订购</span></a>';
			}
		$items .= '</li>';
	}
	return $items;
}

function epd_goods_count () {
	// 出售统计
	global $wpdb, $post;
	if ( get_post_meta( $post->ID, 'zm_goods_count', true ) ) {
		$goods_count = get_post_meta( $post->ID, 'zm_goods_count', true );
	} else {
		$goods_count = '';
	}
	return $goods_count . $wpdb->get_var("SELECT COUNT(ice_id) FROM $wpdb->icealipay WHERE ice_post='" . $post->ID . "' and ice_success=1");
}

// vip meta
function be_vip_meta_inf() {
	if ( function_exists( 'epd_assets_vip' ) && zm_get_option( 'vip_meta' ) ) {
		global $post;
		if ( zm_get_option( 'menu_vip_color' ) ) {
			$viplow = '';
		} else {
			$viplow = ' vip-meta-low';
		}
		if ( get_post_meta( get_the_ID(), 'start_down', true ) || get_post_meta( get_the_ID(), 'down_url_free', true )) {
			if ( get_post_meta( $post->ID, 'down_price', true ) ) {
				echo '<span class="be-vip-meta' . $viplow . '"><span class="be-vip-price"><i class="ep ep-jifen ri"></i>'. get_post_meta($post->ID, 'down_price', true) . '</span></span>';
			}

			if ( ! get_post_meta( $post->ID, 'down_price_type', true ) ) {
				if ( ! get_post_meta( $post->ID, 'down_price', true ) ) {
					if ( get_post_meta( $post->ID, 'member_down', true ) ) {
						$member_down = get_post_meta( $post->ID, 'member_down', true );
						if ( $member_down > 3 && $member_down = 1 ){
							echo '<span class="be-vip-meta' . $viplow . '"><span class="be-vip-down"><i class="cx cx-svip"></i></span></span>';
						} else {
							echo '<span class="be-vip-meta' . $viplow . '"><span class="be-vip-down">免费</span></span>';
						}
					}
				}
			} else {
				echo '<span class="be-vip-meta' . $viplow . '"><span class="be-vip-down">付费</span></span>';
			}
		}
	}
}

// single vip meta
function be_single_vip_meta_inf() {
	if ( function_exists( 'epd_assets_vip' ) && zm_get_option( 'vip_meta' ) ) {
		echo '<span class="be-vip-meta-single">';
		be_vip_meta();

		if ( get_post_meta( get_the_ID(), 'start_down', true ) || get_post_meta( get_the_ID(), 'down_url_free', true )) {
			$down_price = get_post_meta( get_the_ID(), 'down_price', true );
			if ( ! $down_price > 0 ) {
				echo '' .sprintf( __( '资源', 'begin' ) );
			}
		}
		echo '</span>';
	}
}

// 缩略图
function ed_thumbnail() {
	global $post;
	if ( get_post_meta( get_the_ID(), 'ed_down_img', true ) ) {
		$image = get_post_meta( get_the_ID(), 'ed_down_img', true );
		return '<img class="inf-x" src="' . $image . '" alt="' . $post->post_title . '" />';
	} else {
		$content = $post->post_content;
		preg_match_all( '/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER );
		$n = count( $strResult[1] );
		if( $n > 0 ) {
			return  '<img class="inf-x" src="' . $strResult[1][0] . '" alt="' . $post->post_title . '" />';
		} else {
			return '<img class="inf-x" src="' . zm_get_option( 'down_widget_img' ) . '" alt="' . $post->post_title . '" />';
		}
	}
}

function be_down_widget_img() {
	global $post;
	if ( get_post_meta( get_the_ID(), 'ed_down_img', true ) ) {
		$image = get_post_meta( get_the_ID(), 'ed_down_img', true );
		return $image;
	} else {
		$content = $post->post_content;
		preg_match_all( '/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER );
		$n = count( $strResult[1] );
		if( $n > 0 ) {
			return  $strResult[1][0];
		} else {
			return zm_get_option( 'down_widget_img' );
		}
	}
}

add_filter( 'the_content', 'add_ed_down_inf' );
function add_ed_down_inf( $content ) {
	if ( get_post_meta( get_the_ID(), 'ed_down_start', true ) ) {
		if( ! is_feed() && ! is_home() && is_singular() && is_main_query() ) {
			$inf_content = be_ed_down_inf();
			$html = '';
			if ( get_post_meta( get_the_ID(), 'ed_show_bottom', true ) ) {
				$html = $content . $inf_content;
			} else {
				$html = $inf_content . $content;
			}
		}
	} else {
		$html = $content;
	}
	return $html;
}

function be_ed_down_inf() {
	if ( get_post_meta( get_the_ID(), 'start_down', true ) || get_post_meta( get_the_ID(), 'down_url_free', true ) ) {
		$click_count_on = '';

		if ( ! get_post_meta( get_the_ID(), 'down_assets_ed', true  ) ) {
			$down_assets = '<strong>' . zm_get_option( 'down_assets_inf' ) . '</strong>';
		} else {
			$down_assets = '<strong>' . get_post_meta( get_the_ID(), 'ed_assets_down', true ). '</strong>';
		}

		if ( get_post_meta( get_the_ID(), 'down_name_ed', true ) && get_post_meta( get_the_ID(), 'ed_down_name', true ) ) {
			$down_name = '<strong>' . zm_get_option( 'down_name_inf' ) . '</strong>' . get_post_meta( get_the_ID(), 'ed_down_name', true );
		} else {
			$down_name = get_post_meta( get_the_ID(), 'ed_down_name', true );
		}

		if ( get_post_meta( get_the_ID(), 'file_os_ed', true ) && get_post_meta( get_the_ID(), 'ed_file_os', true ) ) {
			$file_os = '<strong>' . zm_get_option( 'down_os_inf' ) . '</strong>' . get_post_meta( get_the_ID(), 'ed_file_os', true );
		} else {
			$file_os = get_post_meta( get_the_ID(), 'ed_file_os', true );
		}

		if ( get_post_meta( get_the_ID(), 'file_inf_ed', true ) && get_post_meta( get_the_ID(), 'ed_file_inf', true ) ) {
			$file_inf = '<strong>' . zm_get_option( 'down_versions_inf' ) . '</strong>' . get_post_meta( get_the_ID(), 'ed_file_inf', true );
		} else {
			$file_inf = get_post_meta( get_the_ID(), 'ed_file_inf', true );
		}

		if ( get_post_meta( get_the_ID(), 'down_size_ed', true ) && get_post_meta( get_the_ID(), 'ed_down_size', true ) ) {
			$down_size = '<strong>' . zm_get_option( 'down_size_inf' ) . '</strong>' . get_post_meta( get_the_ID(), 'ed_down_size', true );
		} else {
			$down_size  = get_post_meta( get_the_ID(), 'ed_down_size', true );
		}
		$ed_down_img  = get_post_meta( get_the_ID(), 'ed_down_img', true );
		$ed_down_demo_url  = get_post_meta( get_the_ID(), 'ed_down_demo_url', true );
		$links_id          = get_post_meta( get_the_ID(), 'links_id', true);
		if ( !$click_count = get_post_meta( $links_id, '_surl_count', true ) ) {
			$click_count   = 0;
		}
		if ( $links_id ) {
			$click_count_on .=  '<span><strong>文件下载</strong>'.$click_count.'&nbsp;次</span>';
		}

		$html = '<div class="be-ed-down-box">';
		$html .= '<div class="ed-inf-img">';
		$html .= ed_thumbnail();
		if ( get_post_meta( get_the_ID(), 'ed_down_demo_url', true ) ) {
			$html .= '<a class="ed-down-demo" rel="external nofollow" target="_blank" href="' . $ed_down_demo_url . '">演示</a>';
		}
		$html .= '</div>';
		$html .= '<div class="down-content">';
		if ( zm_get_option( 'down_assets_inf' ) ) {
			$html .= '<div class="ed-inf ed-assets-inf">' . $down_assets . '</div>';
		}

		if ( ! get_post_meta( get_the_ID(), 'ed_down_basic', true ) ) {
			$html .= '<div class="ed-inf">' .$down_name . '</div>';
			$html .= '<div class="ed-inf">' .$file_os . '</div>';
			$html .= '<div class="ed-inf">' .$file_inf . '</div>';
			$html .= '<div class="ed-inf">' .$down_size . '</div>';
		} else {
			$html .= '<div class="ed-inf"><strong>' . sprintf( __( '分类', 'begin' ) ) . '</strong>' . be_category_inf() . '</div>';
			$html .= '<div class="ed-inf"><strong>' . sprintf( __( '热度', 'begin' ) ) . '</strong>' . be_views_inf() . '</div>';
		}

		$html .= '<div class="ed-inf">' .$click_count_on . '</div>';
		if ( ! get_post_meta( get_the_ID(), 'ed_updated_date', true ) ) {
			$html .= '<div class="ed-inf"><strong>' . zm_get_option( 'down_update_inf' ) . '</strong>';
			if ( get_post_meta( get_the_ID(), 'ed_latest_date', true ) ) {
				$html .= get_post_meta( get_the_ID(), 'ed_latest_date', true );
			} else {
				$html .= get_the_modified_time('Y-n-j');
			}

			$html .= '</div>';
		}

		if ( epd_goods_count () > 0 && zm_get_option( 'goods_count' ) ) {
			$html .= '<div class="ed-inf"><strong>' . zm_get_option( 'down_sold_inf' ) . '</strong>' .epd_goods_count () . '</div>';
		}

		$html .= be_erphpdown_show();
		$html .= '<div class="clear"></div></div>';
		$html .= '<div class="clear"></div></div>';
		return $html;
	}
}

// 下载信息面板
$ed_down_meta_boxes = array(

	"ed_down_start" => array(
		"name"  => "ed_down_start",
		"std"   => "",
		"title" => "显示",
		"type"  =>"checkbox"
	),

	"ed_down_basic" => array(
		"name"  => "ed_down_basic",
		"std"   => "",
		"title" => "仅显示基本信息",
		"type"  =>"checkbox"
	),

	"ed_updated_date" => array(
		"name"        => "ed_updated_date",
		"std"         => "",
		"title"       => "不显示更新日期",
		"type"        => "checkbox"
	),

	"ed_show_bottom" => array(
		"name"       => "ed_show_bottom",
		"std"        => "",
		"title"      => "显示在底部",
		"type"       => "checkbox"
	),

	"zm_goods_count" => array(
		"name"  => "zm_goods_count",
		"std"   => "",
		"title" => "初始出售数",
		"type"  => "number",
		"des"   => "仅是在实际数量前加个数值，建议为个位数",
	),

	"ed_down_help"  => array(
		"name"  => "ed_down_help",
		"title" => "说明：",
		"after" => "勾选预设文字或用&lt;strong&gt;标题&lt;/strong&gt;加粗文字",
		"type"  => "help"
	),

	"ed_down_name" => array(
		"name"  => "ed_down_name",
		"std"   => "",
		"title" => "名称",
		"type"  => "be_text"
	),

	"down_name_ed" => array(
		"name"  => "down_name_ed",
		"std"   => "",
		"title" => "预设文字",
		"type"  => "be_checkbox"
	),

	"ed_file_os" => array(
		"name"   => "ed_file_os",
		"std"    => "",
		"title"  => "平台",
		"type"   => "be_text"
	),

	"file_os_ed" => array(
		"name"   => "file_os_ed",
		"std"    => "",
		"title"  => "预设文字",
		"type"   => "be_checkbox"
	),

	"ed_file_inf" => array(
		"name" => "ed_file_inf",
		"std" => "",
		"title" => "版本",
		"type"=>"be_text"
	),

	"file_inf_ed" => array(
		"name"    => "file_inf_ed",
		"std"     => "",
		"title"   => "预设文字",
		"type"    => "be_checkbox"
	),

	"ed_down_size" => array(
		"name"     => "ed_down_size",
		"std"      => "",
		"title"    => "大小",
		"type"    => "be_text"
	),

	"down_size_ed" => array(
		"name"     => "down_size_ed",
		"std"      => "",
		"title"    => "预设文字",
		"type"     => "be_checkbox"
	),

	"ed_assets_down" => array(
		"name"  => "ed_assets_down",
		"std"   => "",
		"title" => "会员",
		"type"  => "be_text"
	),

	"down_assets_ed" => array(
		"name"  => "down_assets_ed",
		"std"   => "",
		"title" => "自定义文字",
		"type"  => "be_checkbox"
	),

	"ed_latest_date" => array(
		"name"       => "ed_latest_date",
		"std"        => "",
		"title"      => "更新日期",
		"after"      => "（留空则显示最后更新日期）",
		"type"       => "text2"
	),

	"ed_down_img" => array(
		"name"    => "ed_down_img",
		"std"     => "",
		"title"   => "演示图片",
		"type"    => "upload"
	),

	"ed_down_demo_url" => array(
		"name"         => "ed_down_demo_url",
		"std"          => "",
		"title"        => "演示链接",
		"type"         => "text2"
	),

	"meta_sub_after" => array(
		"name"  => "meta_sub_after",
		"std"   => "",
		"title" => "后缀信息",
		"after" => "（仅显示在软件下载模块中）",
		"type"  => "text2"
	),

	"down_tab_quid" => array(
		"name"  => "down_tab_quid",
		"std"   => "",
		"title" => "常见问题页面ID",
		"after" => "（仅用于会员资源模板）",
		"type"  => "text2"
	),

	"tab_qu_title" => array(
		"name"  => "tab_qu_title",
		"std"   => "",
		"title" => "常见问题标题",
		"type"  => "text2"
	),

	"empty"     => array(
		"name"  => "empty",
		"type"  => "empty"
	),
);

function ed_down_meta_boxes() {
	global $post, $ed_down_meta_boxes;
	foreach ($ed_down_meta_boxes as $meta_box) {
		$meta_box_value = get_post_meta($post->ID, $meta_box['name'] . '', true);
		if ($meta_box_value != "")
		$meta_box['std'] = $meta_box_value;
		echo '<input type="hidden" name="' . $meta_box['name'] . '_noncename" id="' . $meta_box['name'] . '_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
		switch ($meta_box['type']) {
			case 'title':
				echo '<h4>' . $meta_box['title'] . '</h4>';
			break;
			case 'number':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<span class="be-form-field form-field"><input type="number" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" /></span><span class="des-field">' . $meta_box['des'] . '</span><br />';
				break;
			case 'text':
				echo '<h4>' . $meta_box['title'];
				if ( isset( $meta_box['after'] ) ) {
					echo '<span class="field-after">' . $meta_box['after'] . '</span>';
				}
				echo '</h4>';
				echo '<span class="form-field"><input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" /></span><br />';
			break;
			case 'text2':
				echo '<span class="be-field" style="display: inline-block;width:48.7%">';
				echo '<h4>' . $meta_box['title'];
				if ( isset( $meta_box['after'] ) ) {
					echo '<span class="field-after">' . $meta_box['after'] . '</span>';
				}
				echo '</h4>';
				echo '<span class="form-field"><input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" /></span><br />';
				echo '</span>';
			break;
			case 'be_text':
				echo '<span class="be-field" style="display: inline-block;width:48.7%">';
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<span class="form-field"><input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" /></span>';

			break;
			case 'be_checkbox':
				if (isset($meta_box['std']) && $meta_box['std'] == 'true') $checked = 'checked = "checked"';
				else $checked = '';
				echo '<br /><label class="be-label"><input type="checkbox" class="checkbox" name="' . $meta_box['name'] . '" value="true"  ' . $checked . ' />';
				echo '' . $meta_box['title'] . '</label>';
				echo '</span>';
			break;
			case 'upload':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<span class="form-field file-uploads"><input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" /><a href="javascript:;" class="begin_file button">选择图片</a></span><br />';
			break;
			case 'textarea':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<textarea id="seo-excerpt" cols="40" rows="2" name="' . $meta_box['name'] . '">' . $meta_box['std'] . '</textarea><br />';
			break;
			case 'radio':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				$counter = 1;
				foreach ($meta_box['buttons'] as $radiobutton) {
					$checked = "";
					if (isset($meta_box['std']) && $meta_box['std'] == $counter) {
						$checked = 'checked = "checked"';
					}
					echo '<input ' . $checked . ' type="radio" class="kcheck" value="' . $counter . '" name="' . $meta_box['name'] . '_value"/>' . $radiobutton;
					$counter++;
				}
			break;
			case 'checkbox':
				echo '<span class="be-field" style="display: inline-block;width:50%">';
				if (isset($meta_box['std']) && $meta_box['std'] == 'true') $checked = 'checked = "checked"';
				else $checked = '';
				echo '<br /><label><input type="checkbox" class="checkbox" name="' . $meta_box['name'] . '" value="true"  ' . $checked . ' />';
				echo '' . $meta_box['title'] . '</label><br />';
				echo '</span>';
			break;
			case 'help':
				echo '<h4>' . $meta_box['title'];
				if ( isset( $meta_box['after'] ) ) {
					echo '<span class="field-after">' . $meta_box['after'] . '</span>';
				}
				echo '</h4>';
				if ( isset( $meta_box['std'] ) ) {
					echo '<span class="form-field">' . $meta_box['std'] . '</span>';
				}
			break;
			case 'empty':
				echo '<p></p>';
			break;
		}
	}
}

function ed_down_meta_box() {
	$be_post_types = array( 'post', 'tao', 'other_custom_post_type' );
	foreach ( $be_post_types as $post_type ) {
		add_meta_box(
			'ed_down_meta_box',
			'资源信息',
			'ed_down_meta_boxes',
			$post_type,
			'normal',
			'high'
		);
	}
}

function save_ed_down_data($post_id) {
	global $post, $ed_down_meta_boxes;
	foreach ($ed_down_meta_boxes as $meta_box) {
		if ( !isset($_POST[$meta_box['name'] . '_noncename']) || !wp_verify_nonce( $_POST[$meta_box['name'] . '_noncename'], plugin_basename(__FILE__) )) {
			return $post_id;
		}
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) return $post_id;
		} else {
			if (!current_user_can('edit_post', $post_id)) return $post_id;
		}
		$data = isset($_POST[$meta_box['name'] . '']) ? $_POST[$meta_box['name'] . ''] : null;
		if (get_post_meta($post_id, $meta_box['name'] . '') == "") add_post_meta($post_id, $meta_box['name'] . '', $data, true);
		elseif ($data != get_post_meta($post_id, $meta_box['name'] . '', true)) update_post_meta($post_id, $meta_box['name'] . '', $data);
		elseif ($data == "") delete_post_meta($post_id, $meta_box['name'] . '', get_post_meta($post_id, $meta_box['name'] . '', true));
	}
}

if ( cx_get_option( 'ed_down_meta' ) ) {
	if ( ! cx_get_option( 'ed_down_box' ) || current_user_can( 'manage_options' ) ) {
		add_action( 'admin_menu', 'ed_down_meta_box' );
		add_action( 'save_post', 'save_ed_down_data' );
	}
}

// 管理员可见Erphpdown属性面板
if ( cx_get_option( 'erphpdown_meta' ) ) {
	if ( cx_get_option( 'erphpdown_box' ) && ! current_user_can( 'manage_options' ) ) {
		remove_action( 'admin_menu', 'erphpdown_create_metabox' );
	}
}

// 下载小工具
class Be_Down_Show_Widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'ed-down',
			'description' => __( '用于显示当前文章下载信息' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'Be_Down_Show_Widget', '资源信息', $widget_ops );
	}

	public function zm_defaults() {
		return array(
			'title'     => '资源信息',
			'model'     => 1,
		);
	}

	function widget($args, $instance) {
		extract($args);
		$defaults = $this -> zm_defaults();
		$title_w = title_i_w();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);

		if ( get_post_meta( get_the_ID(), 'start_down', true ) || get_post_meta( get_the_ID(), 'down_url_free', true )) {
			$title = apply_filters( 'widget_title', $instance['title'] );
			echo $before_widget;
			if ( ! empty( $title ) )
			echo $before_title . $title_w . $title . $after_title;
		}
?>
<?php if ( get_post_meta( get_the_ID(), 'start_down', true ) || get_post_meta( get_the_ID(), 'down_url_free', true ) ) { ?>

	<?php if ( ! $instance['model'] ) { ?>
		<style type="text/css">@media screen and (min-width: 1024px){.single-content .erphpdown, .down-content-show {display: none;}}</style>
	<?php } ?>

	<?php
		$click_count_on = '';
		if ( ! get_post_meta( get_the_ID(), 'down_assets_ed', true  ) ) {
			$down_assets = '<strong>' . zm_get_option( 'down_assets_inf' ) . '</strong>';
		} else {
			$down_assets = '<strong>' . get_post_meta( get_the_ID(), 'ed_assets_down', true ). '</strong>';
		}

		if ( get_post_meta( get_the_ID(), 'down_name_ed', true ) && get_post_meta( get_the_ID(), 'ed_down_name', true ) ) {
			$down_name = '<strong>' . zm_get_option( 'down_name_inf' ) . '</strong>' . get_post_meta( get_the_ID(), 'ed_down_name', true );
		} else {
			$down_name = get_post_meta( get_the_ID(), 'ed_down_name', true );
		}

		if ( get_post_meta( get_the_ID(), 'file_os_ed', true ) && get_post_meta( get_the_ID(), 'ed_file_os', true ) ) {
			$file_os = '<strong>' . zm_get_option( 'down_os_inf' ) . '</strong>' . get_post_meta( get_the_ID(), 'ed_file_os', true );
		} else {
			$file_os = get_post_meta( get_the_ID(), 'ed_file_os', true );
		}

		if ( get_post_meta( get_the_ID(), 'file_inf_ed', true ) && get_post_meta( get_the_ID(), 'ed_file_inf', true ) ) {
			$file_inf = '<strong>' . zm_get_option( 'down_versions_inf' ) . '</strong>' . get_post_meta( get_the_ID(), 'ed_file_inf', true );
		} else {
			$file_inf = get_post_meta( get_the_ID(), 'ed_file_inf', true );
		}

		if ( get_post_meta( get_the_ID(), 'down_size_ed', true ) && get_post_meta( get_the_ID(), 'ed_down_size', true ) ) {
			$down_size = '<strong>' . zm_get_option( 'down_size_inf' ) . '</strong>' . get_post_meta( get_the_ID(), 'ed_down_size', true );
		} else {
			$down_size  = get_post_meta( get_the_ID(), 'ed_down_size', true );
		}

		$links_id = get_post_meta( get_the_ID(), 'links_id', true);
		if ( ! $click_count = get_post_meta( $links_id, '_surl_count', true ) ) {
			$click_count = 0;
		}

		if ( $links_id ) {
			$click_count_on .=  '<span><strong>文件下载</strong>' . $click_count . '&nbsp;次</span>';
		}
	?>
	<?php
		if ( ! get_post_meta( get_the_ID(), 'ed_down_img', true ) ) {
			$image = be_down_widget_img();
		} else {
			$image = get_post_meta( get_the_ID(), 'ed_down_img', true );
		}
		$html = '<div class="ed-down-box" style="background-image: url(' . $image . ');background-repeat: no-repeat;background-position: center;background-size: cover;">';
		$html .= '<div class="ed-down-main">';
		$html .= '<div class="down-inf-widget">';
		if ( zm_get_option( 'down_assets_inf' ) ) {
			$html .= '<div class="down-inf-title ed-assets-inf">' . $down_assets . '</div>';
		}
		$html .= '<div class="down-inf-title">下载信息</div>';
		if ( ! get_post_meta( get_the_ID(), 'ed_down_basic', true ) ) {
			$html .= '<div class="xz">' .$down_name . '</div>';
			$html .= '<div class="xz">' .$file_os . '</div>';
			$html .= '<div class="xz">' .$file_inf . '</div>';
			$html .= '<div class="xz">' .$down_size . '</div>';
		} else {
			$html .= '<div class="xz"><strong>' . sprintf( __( '分类', 'begin' ) ) . '</strong>' . be_category_inf() . '</div>';
			$html .= '<div class="xz"><strong>' . sprintf( __( '热度', 'begin' ) ) . '</strong>' . be_views_inf() . '</div>';
		}
		$html .= '<div class="xz">' .$click_count_on . '</div>';
		if ( ! get_post_meta( get_the_ID(), 'ed_updated_date', true ) ) {
			$html .= '<div class="xz"><strong>' . zm_get_option( 'down_update_inf' ) . '</strong>';
			if ( get_post_meta( get_the_ID(), 'ed_latest_date', true ) ) {
				$html .= get_post_meta( get_the_ID(), 'ed_latest_date', true );
			} else {
				$html .= get_the_modified_time('Y-n-j');
			}

			$html .= '</div>';
		}

		if ( epd_goods_count () > 0 && zm_get_option( 'goods_count' ) ) {
			$html .= '<div class="xz"><strong>' . zm_get_option( 'down_sold_inf' ) . '</strong>' .epd_goods_count () . '</div>';
		}
		$html .= '</div><div class="clear"></div>';
		$html .= '<div class="ed-down-show">';
		$html .= be_erphpdown_show();
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</div>';
		echo $html;
	?>
<?php } ?>
<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['down_btu'] = !empty($new_instance['down_btu']) ? 1 : 0;
		$instance['model'] = !empty($new_instance['model']) ? 1 : 0;
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '会员资源';
		}
		global $wpdb;
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('model') ); ?>" name="<?php echo esc_attr( $this->get_field_name('model') ); ?>" <?php checked( (bool) $instance["model"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('model') ); ?>">显示正文下载模块</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', 'be_down_show_widget_init' );
function be_down_show_widget_init() {
	register_widget( 'Be_Down_Show_Widget' );
}

// 今日签到
function refresh_checkin_core() {
	date_default_timezone_set('Asia/Shanghai');
	global $wpdb;
	$userid = get_current_user_id();
	$result = $wpdb->get_var( "select count(ID) from $wpdb->checkin where TO_DAYS(create_time) = TO_DAYS(NOW()) and user_id=".$userid );
	echo '<div class="ed-checkin-record-btu">';
		if ( is_user_logged_in() ) {
			$user_info = wp_get_current_user();
			if ( get_option( 'ice_ali_money_checkin' ) ) {
				if ( $result ) {
					echo '<a href="javascript:;" class="usercheck active"><i class="ep ep-tixianchuli"></i>已签到</a>';
				} else {
					echo '<a href="javascript:;" class="usercheck erphp-checkin"><i class="ep ep-tixianchuli"></i>立即签到</a>';
				}
			}
		}
	echo '</div>';

	// 获取小工具设置
	$widget_instances = get_option( 'widget_be_checkin_record_widget' );
	$days_value = '';
	if ( is_array( $widget_instances ) ) {
		foreach ( $widget_instances as $key => $value ) {
			if ( isset( $value['days'] ) ) {
				$days_value = $value['days'];
				break;
			}
		}
	}

	$numposts_value = '';
	if ( is_array( $widget_instances ) ) {
		foreach ( $widget_instances as $key => $value ) {
			if ( isset( $value['numposts'] ) ) {
				$numposts_value = $value['numposts'];
				break;
			}
		}
	}

	$money_value = '';
	if ( is_array( $widget_instances ) ) {
		foreach ( $widget_instances as $key => $value ) {
			if ( isset( $value['money'] ) ) {
				$money_value = $value['money'];
				break;
			}
		}
	}

	$top_value = '';
	if ( is_array( $widget_instances ) ) {
		foreach ( $widget_instances as $key => $value ) {
			if ( isset( $value['top'] ) ) {
				$top_value = $value['top'];
				break;
			}
		}
	}

	$list_value = '';
	if ( is_array( $widget_instances ) ) {
		foreach ( $widget_instances as $key => $value ) {
			if ( isset( $value['list'] ) ) {
				$list_value = $value['list'];
				break;
			}
		}
	}

	$time_value = '';
	if ( is_array( $widget_instances ) ) {
		foreach ( $widget_instances as $key => $value ) {
			if ( isset( $value['time'] ) ) {
				$time_value = $value['time'];
				break;
			}
		}
	}

	// 获取签到数据
	global $wpdb;
	$adds = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT * FROM $wpdb->checkin WHERE create_time >= %s ORDER BY ID DESC",
			date( 'Y-m-d', strtotime( '-' . $days_value . ' days' ) )
		)
	);

	// 读取签到
	$user_counts = array(); // 用于记录用户及其次数的数组
	$user_info = array(); // 用于记录用户信息的数组
	$target_user_id = get_current_user_id(); // 指定的用户ID

	foreach ( $adds as $value ) {
		// 将创建时间转换为日期格式
		// $create_date = date( 'm月d日', strtotime( $value->create_time ) );
		$create_time = new DateTime( $value->create_time );
		$current_time = new DateTime();

		$diff = $current_time->diff( $create_time );
		$diff_days = $diff->days;

		if ( $diff_days === 0 ) {
			$create_date = '今天';
		} elseif ( $diff_days === 1 ) {
			$create_date = '昨天';
		} else {
			$create_date = $diff_days . '天前';
		}

		$newest = $create_date . ' ' . $create_time->format( 'H:i' );

		if ( ! array_key_exists( $value->user_id, $user_counts ) ) {
			// 如果该用户还没有出现过，则将其添加到 $user_counts 数组中，次数为 1
			$user_counts[ $value->user_id ] = 1;
			// 记录用户信息
			$user_info[ $value->user_id ]['display_name'] = get_the_author_meta( 'display_name', $value->user_id );
			$user_info[ $value->user_id ]['user_email'] = get_the_author_meta( 'user_email', $value->user_id );
			$user_info[ $value->user_id ]['last_checkin_date'] = $create_date; // 记录最近的签到日期
			$user_info[$value->user_id]['user_id'] = $value->user_id; // 记录用户ID
		} else {
			// 如果该用户已经出现过，则将其次数加 1
			$user_counts[ $value->user_id ]++;
			// 更新最近的签到日期
			if ( strtotime( $create_date ) > strtotime( $user_info[ $value->user_id ]['last_checkin_date'] ) ) {
				$user_info[ $value->user_id ]['last_checkin_date'] = $create_date;
			}
		}
	}

	echo '<div class="ed-checkin-record-list">';
	if ( $top_value ) {
		// 单独添加指定用户的信息
		if ( array_key_exists( $target_user_id, $user_counts ) ) {
			$target_user_count = $user_counts[ $target_user_id ];
			$target_user_last_checkin_date = $user_info[ $target_user_id ]['last_checkin_date'];

			// 输出指定用户信息
			echo '<div class="ed-checkin-record-item">';
			echo '<a href="' . get_author_posts_url( $user_info[$target_user_id]['user_id'] ) . '" target="_blank" rel="external nofollow">';
				echo '<div class="ed-checkin-record-avatar-name">';
				echo '<span class="ed-checkin-record-avatar load">';
				if ( ! zm_get_option( 'avatar_load' ) ) {
					echo get_avatar( $user_info[ $target_user_id ]['user_email'], '96', '', $user_info[ $target_user_id ]['display_name'] );
					} else {
					echo '<img class="avatar photo" src="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=" alt="' . $user_info[ $target_user_id ]['display_name'] . '" width="96" height="96" data-original="' . preg_replace(array('/^.+(src=)(\"|\')/i', '/(\"|\')\sclass=(\"|\').+$/i'), array('', ''), get_avatar($user_info[ $target_user_id ]['user_email'], '96', '', $user_info[ $target_user_id ]['display_name'])) . '" />';
				}
				echo '</span>';

				echo '<div class="ed-checkin-record-name-date my-record-date">';
				echo '<span class="ed-checkin-record-name">';
				echo $user_info[ $target_user_id ]['display_name'];
				echo '</span>';
				echo '<div class="ed-checkin-record-date">';
				echo '我的签到';
				echo '</div>';
				echo '</div>';
				echo '</div>';
			echo '</a>';
			if ( $money_value && get_option( 'ice_ali_money_checkin' ) ) {
				echo '<span class="ed-checkin-record-money"><i class="ep ep-jifen ri"></i>';
				echo $target_user_count * get_option( 'ice_ali_money_checkin' );
				echo '</span>';
			} else {
				echo '<span class="ed-checkin-record-count"><i class="be be-favoriteoutline ri"></i>';
				echo $target_user_count;
				echo '</span>';
			}
			echo '</div>';
		}

		// 找到指定用户的数组索引，并删除该索引对应的数组元素
		if ( array_key_exists( $target_user_id, $user_counts ) ) {
			unset( $user_counts[ $target_user_id ] );
			unset( $user_info[ $target_user_id ] );
		}
	}

	// 使用次数对数组进行排序
	array_multisort( $user_counts, SORT_DESC, $user_info );
	$sn = 0;
	$display_count = $numposts_value; // 设置要显示的用户数量
	$counter = 0; // 计数器

	// 遍历 $user_counts 数组，输出每个用户及其次数和最近的签到日期
	foreach ( $user_counts as $user_id => $count ) {
		$sn++;
		if ( count_user_posts($user_info[$user_id]['user_id']) > 0 ) {
			$useurl = get_author_posts_url( $user_info[$user_id]['user_id'] );
		} else {
			$useurl = home_url( '/' ); 
		}
		echo '<div class="ed-checkin-record-item sn-' . $sn . '">';
			echo '<span class="ed-checkin-sn">' . $sn . '</span>';
			echo '<a href="' . $useurl . '" target="_blank" rel="external nofollow">';
				echo '<div class="ed-checkin-record-avatar-name">';
					echo '<span class="ed-checkin-record-avatar load">';
						if ( ! zm_get_option( 'avatar_load' ) ) {
							echo get_avatar( $user_info[ $user_id ]['user_email'], '96', '', $user_info[ $user_id ]['display_name'] );
						} else {
							echo '<img class="avatar photo" src="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=" alt="' . $user_info[ $user_id ]['display_name'] . '" width="96" height="96" data-original="' . preg_replace(array('/^.+(src=)(\"|\')/i', '/(\"|\')\sclass=(\"|\').+$/i'), array('', ''), get_avatar($user_info[ $user_id ]['user_email'], '96', '', $user_info[ $user_id ]['display_name'])) . '" />';
						}
					echo '</span>';

					echo '<div class="ed-checkin-record-name-date">';
						echo '<span class="ed-checkin-record-name">';
						echo $user_info[ $user_id ]['display_name'];
						echo '</span>';
						echo '<div class="ed-checkin-record-date">';
						echo $user_info[ $user_id ]['last_checkin_date']; // 输出最近签到日期
						echo '</div>';
					echo '</div>';
				echo '</div>';
			echo '</a>';

			if ( $money_value && get_option( 'ice_ali_money_checkin' ) ) {
				echo '<span class="ed-checkin-record-money"><i class="ep ep-jifen ri"></i>';
				echo $count * get_option( 'ice_ali_money_checkin' );
				echo '</span>';
			} else {
				echo '<span class="ed-checkin-record-count"><i class="be be-favoriteoutline ri"></i>';
				echo $count;
				echo '</span>';
			}
			$counter++; // 每次循环增加计数器
			if ( $counter >= $display_count ) {
				break; // 达到显示数量后跳出循环
			}
		echo '</div>';
	}
	echo '</div>';
}

add_action( 'wp_ajax_refresh_checkin_list', 'refresh_checkin_list' );
add_action( 'wp_ajax_nopriv_refresh_checkin_list', 'refresh_checkin_list' );
function refresh_checkin_list() {
	refresh_checkin_core();
	wp_die();
}

// 签到小工具
class be_checkin_record_widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'be-checkin-record-widget',
			'description' => '按时间显示签到用户',
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'be_checkin_record_widget', '今日签到', $widget_ops );
	}

	public function zm_defaults() {
		return array(
			'money'        => 1,
			'top'          => 1,
			'time'         => '',
			'list'         => '',
			'title_z'      => '',
			'show_icon'    => '',
			'show_svg'     => '',
			'show_bg'      => 1,
			'numposts'     => 10,
			'days'         => 30,
			'title'        => '今日签到',
			'checkin_tip'  => '每日签到获得奖励',
		);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title_w  = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( ( array ) $instance, $defaults );
		$title    = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title;
?>

<div class="ed-checkin-record<?php if ( $instance['show_bg'] ) { ?> revery-bg<?php } ?>">
	<?php if ( $instance['show_icon'] ) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon da"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ( $instance['show_svg'] ) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon da"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
	<?php } ?>

	<div class="ed-checkin-record-wrap<?php if ( $instance['list'] ) { ?> qd-list<?php } else { ?> qd-flex<?php } ?>">
		<div class="ed-widget-checkin-tip"><?php echo $instance['checkin_tip']; ?></div>
		<?php 
			if ( ! is_user_logged_in() ) {
				echo '<div class="ed-checkin-record-btu no-record-btu"><a href="javascript:;" class="show-layer usercheck"><i class="ep ep-tixianchuli"></i>立即签到</a></div>';
			}
		?>
		<div class="ed-checkin-record-main">
			<?php refresh_checkin_core(); ?>
		</div>
	</div>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['title_z']     = strip_tags( $new_instance['title_z'] );
		$instance['show_icon']   = strip_tags( $new_instance['show_icon'] );
		$instance['show_svg']    = strip_tags( $new_instance['show_svg'] );
		$instance['show_bg']    = strip_tags( $new_instance['show_bg'] );
		$instance['checkin_tip'] = strip_tags( $new_instance['checkin_tip'] );
		$instance['title']       = strip_tags( $new_instance['title'] );
		$instance['money']       = ! empty( $new_instance['money'] ) ? 1 : 0;
		$instance['top']         = ! empty( $new_instance['top'] ) ? 1 : 0;
		$instance['time']        = ! empty( $new_instance['time'] ) ? 1 : 0;
		$instance['list']        = ! empty( $new_instance['list'] ) ? 1 : 0;
		$instance['numposts']    = $new_instance['numposts'];
		$instance['days']        = $new_instance['days'];
		return $instance;
	}
	function form( $instance ) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( ( array ) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		} else {
			$title = '今日签到';
		}
		global $wpdb;
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'title_z' ); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title_z' ); ?>" name="<?php echo $this->get_field_name( 'title_z' ); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'show_icon' ); ?>">单色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name( 'show_icon' ); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'show_svg' ); ?>">彩色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'show_svg' ); ?>" name="<?php echo $this->get_field_name( 'show_svg' ); ?>" type="text" value="<?php echo $instance['show_svg']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('checkin_tip'); ?>">说明：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('checkin_tip'); ?>" name="<?php echo $this->get_field_name( 'checkin_tip' ); ?>" type="text" value="<?php echo $instance['checkin_tip']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'days' ); ?>">显示：</label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'days' ); ?>" name="<?php echo $this->get_field_name( 'days' ); ?>" type="number" step="1" min="1" value="<?php echo $instance['days']; ?>" size="3" /> 天内的
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'numposts' ); ?>">数量：</label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'numposts' ); ?>" name="<?php echo $this->get_field_name( 'numposts' ); ?>" type="number" step="1" min="1" value="<?php echo $instance['numposts']; ?>" size="3" />
	</p>
	<!-- 
	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'time' ) ); ?>">标准日期：</label>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'time' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'time' ) ); ?>" <?php checked( (bool) $instance["time"], true ); ?>>
	</p>
 	-->
	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'show_bg' ) ); ?>">背景装饰：</label>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_bg' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_bg' ) ); ?>" <?php checked( (bool) $instance["show_bg"], true ); ?>>
	</p>
	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'list' ) ); ?>">列表样式：</label>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'list' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'list' ) ); ?>" <?php checked( (bool) $instance["list"], true ); ?>>
	</p>
	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'money' ) ); ?>">奖励积分：</label>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'money' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'money' ) ); ?>" <?php checked( (bool) $instance["money"], true ); ?>>
	</p>
	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'top' ) ); ?>">我的置顶：</label>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'top' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'top' ) ); ?>" <?php checked( (bool) $instance["top"], true ); ?>>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id( 'submit' ); ?>" name="<?php echo $this->get_field_name( 'submit' ); ?>" value="1" />
<?php }
}

add_action( 'widgets_init', 'be_checkin_record_widget_init' );
function be_checkin_record_widget_init() {
	register_widget( 'be_checkin_record_widget' );
}

function epd_assets_vip() { ?>
<!-- 资产 -->
<?php 
	global $wpdb;
	$user_Info   = wp_get_current_user();
	$userMoney=$wpdb->get_row("select * from ".$wpdb->iceinfo." where ice_user_id=".$user_Info->ID);
	if(!$userMoney){
		$okMoney=0;
	}else{
		$okMoney=$userMoney->ice_have_money - $userMoney->ice_get_money;
	}
?>

<div class="profile-assets<?php if ( class_exists( 'myCRED_Core' ) && get_option( 'erphp_mycred' ) == 'yes' ) { ?> profile-assets-cred<?php }?>">
	<!-- VIP信息 -->
	<div class="be-assets-box">
		<div class="be-assets-main be-assets-main-d ms<?php $userTypeId=getUsreMemberType(); if ( $userTypeId == 0 ) { ?> be-assets-main-d-f<?php } ?>">
		<div class="be-assets-caption">您目前为</div>
		<div class="be-assets-count be-assets-count-vip">
			<?php 
				$erphp_life_name    = get_option('erphp_life_name')?get_option('erphp_life_name'):'终身VIP';
				$erphp_year_name    = get_option('erphp_year_name')?get_option('erphp_year_name'):'包年VIP';
				$erphp_quarter_name = get_option('erphp_quarter_name')?get_option('erphp_quarter_name'):'包季VIP';
				$erphp_month_name  = get_option('erphp_month_name')?get_option('erphp_month_name'):'包月VIP';
				$erphp_day_name  = get_option('erphp_day_name')?get_option('erphp_day_name'):'体验VIP';

				$erphp_year_price    = get_option('ciphp_year_price');
				$erphp_quarter_price = get_option('ciphp_quarter_price');
				$erphp_month_price  = get_option('ciphp_month_price');
				$erphp_life_price  = get_option('ciphp_life_price');
				$erphp_day_price  = get_option('ciphp_day_price');

				$vip_update_pay = 0;
				if(get_option('vip_update_pay')){
					$vip_update_pay = 1;
					global $current_user;
				}

				$userTypeId=getUsreMemberType();
				if($userTypeId==6) {
					echo "".$erphp_day_name;
				} elseif($userTypeId==7) {
					echo "".$erphp_month_name;
				} elseif ($userTypeId==8) {
					echo "".$erphp_quarter_name;
				} elseif ($userTypeId==9) {
					echo "".$erphp_year_name;
                } elseif ($userTypeId==10) {
					echo "".$erphp_life_name;
				} else  { ?>
					普通用户
					<div class="be-assets-inf be-assets-vip-inf">无下载查看收费内容权限</div>
					</div></div>
				<?php }
			?>

		<?php if ( ! $userTypeId == 0 ) { ?></div><?php } ?>
		<?php if ( ! $userTypeId == 0 ) { ?>
			<div class="be-assets-inf be-assets-vip-inf">
				<?php if ( $userTypeId == 10 ) { ?>
					<?php echo (($userTypeId>0 && $userTypeId<10) || ($userTypeId == 10 && !get_option('erphp_life_days'))) ? '<span class="be-expire-date bgt">有效期至</span>永久':'';?>
				<?php } else { ?>
					<?php echo (($userTypeId>0 && $userTypeId<10) || ($userTypeId == 10 && get_option('erphp_life_days'))) ? '<span class="be-expire-date bgt">有效期至</span>' . getUsreMemberTypeEndTime() :'';?>
				<?php } ?>
			</div>
		</div>
		<?php } ?>
		<!-- VIP信息结束 -->
	</div>

	<div class="be-assets-box">
		<div class="be-assets-main be-assets-main-a ms">
			<div class="be-assets-caption">当前余额
				<div class="be-assets-count"><?php echo sprintf("%.2f",$okMoney); ?><div class="be-assets-inf"><?php echo get_option('ice_name_alipay');?></div></div>
			</div>
		</div>
	</div>

	<div class="be-assets-box">
		<div class="be-assets-main be-assets-main-b ms">
			<div class="be-assets-caption">累计消费</div>
			<div class="be-assets-count"><?php echo $userMoney?sprintf("%.2f",$userMoney->ice_get_money):0; ?><div class="be-assets-inf"><?php echo get_option('ice_name_alipay');?></div></div>
		</div>
	</div>

	<?php if ( class_exists( 'myCRED_Core' ) && get_option( 'erphp_mycred' ) == 'yes' ) { ?>
		<div class="be-assets-box">
			<div class="be-assets-main be-assets-main-c ms">
				<div class="be-assets-caption">您目前有</div>
				<div class="be-assets-count"><?php echo mycred_get_users_cred( $user_Info->ID ); ?>
					<div class="be-assets-inf">
						<?php $mycred_core = get_option('mycred_pref_core'); ?>
						<?php echo $mycred_core['name']['plural']; ?>
					</div>
				</div>
			</div>
		</div>
	<?php }?>
	<!-- 资产结束 -->
</div>
<?php }

// VIP
function epd_vip_name() { ?>
<?php 
	global $wpdb;
	$user_Info   = wp_get_current_user();
	$userMoney=$wpdb->get_row("select * from ".$wpdb->iceinfo." where ice_user_id=".$user_Info->ID);
	if(!$userMoney){
		$okMoney=0;
	}else{
		$okMoney=$userMoney->ice_have_money - $userMoney->ice_get_money;
	}
?>

<?php 
	$erphp_life_name    = get_option('erphp_life_name')?get_option('erphp_life_name'):'终身VIP';
	$erphp_year_name    = get_option('erphp_year_name')?get_option('erphp_year_name'):'包年VIP';
	$erphp_quarter_name = get_option('erphp_quarter_name')?get_option('erphp_quarter_name'):'包季VIP';
	$erphp_month_name   = get_option('erphp_month_name')?get_option('erphp_month_name'):'包月VIP';
	$erphp_day_name     = get_option('erphp_day_name')?get_option('erphp_day_name'):'体验VIP';

	$erphp_year_price    = get_option('ciphp_year_price');
	$erphp_quarter_price = get_option('ciphp_quarter_price');
	$erphp_month_price   = get_option('ciphp_month_price');
	$erphp_life_price    = get_option('ciphp_life_price');
	$erphp_day_price     = get_option('ciphp_day_price');

	$vip_update_pay = 0;
	if(get_option('vip_update_pay')){
		$vip_update_pay = 1;
		global $current_user;
	}

	$userTypeId=getUsreMemberType();
	if ( ! $userTypeId == 0 ) {
		echo '<span class="ed-vip-name-inf ed-vip-name-tip">';
	} else {
		echo '<span class="ed-vip-name-inf">';
	}
	if($userTypeId==6) {
		echo '' . $erphp_day_name;
	} elseif($userTypeId==7) {
		echo '' .$erphp_month_name;
	} elseif ($userTypeId==8) {
		echo '' .$erphp_quarter_name;
	} elseif ($userTypeId==9) {
		echo '' .$erphp_year_name;
    } elseif ($userTypeId==10) {
		echo '' .$erphp_life_name;
	} else {
		echo '<span class="ed-non-vip">普通用户</span>';
	}
	echo '</span>';
	if ( ! $userTypeId == 0 ) {
		if ( $userTypeId == 10 ) {
		} else {
			echo ( ( $userTypeId > 0 && $userTypeId < 10 ) || ( $userTypeId == 10 && get_option( 'erphp_life_days' ) ) ) ? '<span class="ed-vip-time-inf"><span class="ed-vip-inf-time">有效期</span><span class="ed-vip-time">' . getUsreMemberTypeEndTime() . '</span></span>' : '' ;
		}
	}
?>
<?php }

function epd_vip_btu() { ?>
<!-- VIP -->
<?php 
	global $wpdb;
	$user_Info   = wp_get_current_user();
	$userMoney=$wpdb->get_row("select * from ".$wpdb->iceinfo." where ice_user_id=".$user_Info->ID);
	if(!$userMoney){
		$okMoney=0;
	}else{
		$okMoney=$userMoney->ice_have_money - $userMoney->ice_get_money;
	}
?>

<?php 
	$erphp_life_name    = get_option('erphp_life_name')?get_option('erphp_life_name'):'终身VIP';
	$erphp_year_name    = get_option('erphp_year_name')?get_option('erphp_year_name'):'包年VIP';
	$erphp_quarter_name = get_option('erphp_quarter_name')?get_option('erphp_quarter_name'):'包季VIP';
	$erphp_month_name   = get_option('erphp_month_name')?get_option('erphp_month_name'):'包月VIP';
	$erphp_day_name     = get_option('erphp_day_name')?get_option('erphp_day_name'):'体验VIP';

	$erphp_year_price    = get_option('ciphp_year_price');
	$erphp_quarter_price = get_option('ciphp_quarter_price');
	$erphp_month_price   = get_option('ciphp_month_price');
	$erphp_life_price    = get_option('ciphp_life_price');
	$erphp_day_price     = get_option('ciphp_day_price');

	$vip_update_pay = 0;
	if(get_option('vip_update_pay')){
		$vip_update_pay = 1;
		global $current_user;
	}

	$userTypeId=getUsreMemberType();
	echo '<div class="be-epd-vip-box">';
	echo '<a class="be-vip-btu-url be-vip-member" href="' .zm_get_option( 'be_vip_but_url' ) . '">';
	if($userTypeId==6) {
		echo '<span class="cx cx-svip"></span><div>我的</div>';
	} elseif($userTypeId==7) {
		echo '<span class="cx cx-svip"></span><div>我的</div>';
	} elseif ($userTypeId==8) {
		echo '<span class="cx cx-svip"></span><div>我的</div>';
	} elseif ($userTypeId==9) {
		echo '<span class="cx cx-svip"></span><div>我的</div>';
    } elseif ($userTypeId==10) {
		echo '<span class="cx cx-svip"></span><div>我的</div>';
	} else {
		echo '<span class="cx cx-svip"></span><div>订购</div>';
	}
	echo '</a>';
	echo '<a class="be-vip-btu-url be-vip-recharge" href="' .zm_get_option( 'be_rec_but_url' ) . '"><span class="ep ep-yue"></span><div>充值</div></a>';
	echo '</div>';
?>
<?php }

// 提现申请
function ed_cash_application() {
	if(!is_user_logged_in()){
		exit;
	}
	global $wpdb;
	$fee=get_option("ice_ali_money_site");
	$fee=isset($fee) ?$fee :100;
	$user_Info   = wp_get_current_user();
	$userMoney=$wpdb->get_row("select * from ".$wpdb->iceinfo." where ice_user_id=".$user_Info->ID);
	if(!$userMoney) {
		$okMoney=0;
	} else {
		$okMoney=$userMoney->ice_have_money - $userMoney->ice_get_money;
	}
	if(isset($_POST['Submit'])) {
		$getinfo=$wpdb->get_row("select * from ".$wpdb->iceget." where ice_user_id=".$user_Info->ID." and ice_success=0");
		if ( $getinfo ) {
			die('<p class="alert">您已经申请提现，请等待管理员处理!</p>');
		}

		$check7day=$wpdb->get_row("select * from ".$wpdb->iceget." where ice_user_id=".$user_Info->ID."  order by ice_id desc");
		if ( $check7day && ( time()-strtotime( $check7day->ice_time ) < 7*24*3600 ) ) {
			die('<p class="alert">您好，7天内只能申请一次提现!上次申请提现时间：'.$check7day->ice_time )."</p>";
		}

		$ice_alipay = $wpdb->escape($_POST['ice_alipay']);
		$ice_name   = $wpdb->escape($_POST['ice_name']);
		$ice_money  = isset( $_POST['ice_money'] ) && is_numeric($_POST['ice_money'] ) ?$wpdb->escape($_POST['ice_money'] ) : 0;
		if ( $ice_money<get_option( 'ice_ali_money_limit' ) ) {
			echo "<p class='alert'>提现金额必须大于等于".get_option('ice_ali_money_limit').get_option('ice_name_alipay')."</p>";
		} elseif ( empty( $ice_name) || empty( $ice_alipay ) ) {
			echo "<p class='alert'>请输入支付宝帐号和姓名</p>";
		} elseif ($ice_money > $okMoney) {
			echo "<p class='alert'>提现金额大于可提现金额".$okMoney."</p>";
		} else {
			$sql="insert into ".$wpdb->iceget."(ice_money,ice_user_id,ice_time,ice_success,ice_success_time,ice_note,ice_name,ice_alipay)values
				( '".$ice_money."','".$user_Info->ID."','".date("Y-m-d H:i:s")."',0,'".date("Y-m-d H:i:s")."','','$ice_name','$ice_alipay' )";
			if ( $wpdb->query( $sql ) ) {
				addUserMoney($user_Info->ID, '-'.$ice_money);
				echo "<p class='alert'>申请成功！等待管理员处理!</p>";
			} else {
				echo "<p class='alert'>系统错误请稍后重试</p>";
			}
		}
	}
	$userAli=$wpdb->get_row( "select * from ".$wpdb->iceget." where ice_user_id=".$user_Info->ID );

	?>
	<div class="erphpdown-sc ed-tixian">
		<form method="post" action="">
			<h2>提现申请</h2>
			<p class="alert">提现支付宝信息设置后，不可更改！</p>
			<table class="form-table">
				<dl class="dl-horizontal">
					<dt>支付宝账号</dt>
					<dd>
						<?php if(!$userAli){?>
							<input type="text" id="ice_alipay" class="profile-input" name="ice_alipay" maxlength="50" size="50" />
						<?php }else{
							echo '<span class="hidden-input">' . $userAli->ice_alipay . '</span>';
							echo '<input type="hidden" id="ice_alipay" name="ice_alipay" value="'.$userAli->ice_alipay.'"/>';
						}?>
					</dd>
				</dl>
				<dl class="dl-horizontal">
					<dt>支付宝姓名</dt>
					<dd>
						<?php if(!$userAli){?>
							<input type="text" id="ice_name" class="profile-input" name="ice_name" maxlength="50" size="50" />
						<?php }else{
							echo '<span class="hidden-input">' . $userAli->ice_name . '</span>';
							echo '<input type="hidden" id="ice_name" class="profile-input" name="ice_name" value="'.$userAli->ice_name.'"/>';
						}?>
					</dd>
				</dl>
				<dl class="dl-horizontal">
					<dt>手续费</dt>
					<dd>
						<span class="hidden-input"><?php echo get_option("ice_ali_money_site")?>%</span>
					</dd>
				</dl>
				<dl class="dl-horizontal">
					<dt>提现金额</dt>
					<dd>
						<input type="text" id="ice_money" class="profile-input" name="ice_money" />（可提现余额：<?php echo sprintf("%.2f",$okMoney)?><?php echo get_option('ice_name_alipay')?><!--最多可提现：￥<?php echo sprintf("%.2f",$okMoney*(100-$fee)/100)?>-->）
					</dd>
				</dl>
				<dl class="dl-horizontal">
					<dd>
						<input type="submit" name="Submit" value="提交申请" class="profile-btn"/>
					</dd>
				</dl>
			</table>
		</form>
	</div>
<?php
}

// 下载模块
function be_erphpdown_show(){
	global $wpdb;
	$content = '';
	$down_box_hide = get_post_meta(get_the_ID(), 'down_box_hide', true);

	$erphp_url_front_login = wp_login_url();
	if( get_option('erphp_url_front_login' ) ) {
		$erphp_url_front_login = get_option( 'erphp_url_front_login' );
	}

	if ( zm_get_option( 'be_login_epd' ) ) {
		$ed_login = '<a class="erphp-login-must ed-login-btu show-layer">登录</a>';
	} else {
		$ed_login = '<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">登录</a>';
	}

	$erphp_post_types = get_option('erphp_post_types');
	if(is_singular() && in_array(get_post_type(),$erphp_post_types)){

		$content2 = $content;

		$erphp_see2_style = get_option('erphp_see2_style');
		$erphp_life_name    = get_option('erphp_life_name')?get_option('erphp_life_name'):'终身VIP';
		$erphp_year_name    = get_option('erphp_year_name')?get_option('erphp_year_name'):'包年VIP';
		$erphp_quarter_name = get_option('erphp_quarter_name')?get_option('erphp_quarter_name'):'包季VIP';
		$erphp_month_name  = get_option('erphp_month_name')?get_option('erphp_month_name'):'包月VIP';
		$erphp_day_name  = get_option('erphp_day_name')?get_option('erphp_day_name'):'体验VIP';
		$erphp_vip_name  = get_option('erphp_vip_name')?get_option('erphp_vip_name'):'VIP';

		$erphp_down=get_post_meta(get_the_ID(), 'erphp_down', true);
		$start_down=get_post_meta(get_the_ID(), 'start_down', true);
		$start_down2=get_post_meta(get_the_ID(), 'start_down2', true);
		$start_see=get_post_meta(get_the_ID(), 'start_see', true);
		$start_see2=get_post_meta(get_the_ID(), 'start_see2', true);

		$days=get_post_meta(get_the_ID(), 'down_days', true);
		$price=get_post_meta(get_the_ID(), 'down_price', true);
		$price_type=get_post_meta(get_the_ID(), 'down_price_type', true);
		$url=get_post_meta(get_the_ID(), 'down_url', true);
		$urls=get_post_meta(get_the_ID(), 'down_urls', true);
		$url_free=get_post_meta(get_the_ID(), 'down_url_free', true);
		$memberDown=get_post_meta(get_the_ID(), 'member_down',TRUE);
		$hidden=get_post_meta(get_the_ID(), 'hidden_content', true);

		$userType=getUsreMemberType();
		$down_info = null;$downMsgFree = '';$down_checkpan = '';$yituan = '';$down_tuan=0;$erphp_popdown='';$iframe='';$down_repeat=0;$down_info_repeat = null;$down_can = 0;

		if(function_exists('erphpdown_tuan_install')){
			$down_tuan=get_post_meta(get_the_ID(), 'down_tuan', true);
		}

		$down_repeat = get_post_meta(get_the_ID(), 'down_repeat', true);
		
		$erphp_url_front_vip = get_bloginfo('wpurl').'/wp-admin/admin.php?page=erphpdown/admin/erphp-update-vip.php';
		if(get_option('erphp_url_front_vip')){
			$erphp_url_front_vip = get_option('erphp_url_front_vip');
		}
		$erphp_url_front_login = wp_login_url();
		if(get_option('erphp_url_front_login')){
			$erphp_url_front_login = get_option('erphp_url_front_login');
		}

		if(get_option('erphp_popdown')){
			$erphp_popdown=' erphpdown-down-layui';
			$iframe = '&iframe=1';
		}

		if(is_user_logged_in()){
			$erphp_url_front_vip2 = $erphp_url_front_vip;
		}else{
			$erphp_url_front_vip2 = $erphp_url_front_login;
		}

		$erphp_blank_domains = get_option('erphp_blank_domains')?get_option('erphp_blank_domains'):'pan.baidu.com';
		$erphp_colon_domains = get_option('erphp_colon_domains')?get_option('erphp_colon_domains'):'pan.baidu.com';

		if($down_tuan && is_user_logged_in()){
			global $current_user;
			$yituan = $wpdb->get_var("select ice_status from $wpdb->tuanorder where ice_user_id=".$current_user->ID." and ice_post=".get_the_ID()." and ice_status>0");
		}

		if($url_free){
			$downMsgFree .= '<div class="erphpdown-title">免费资源</div><div class="erphpdown-free">';
			$downList=explode("\r\n",$url_free);
			foreach ($downList as $k=>$v){
				$filepath = $downList[$k];
				if($filepath){

					if($erphp_colon_domains){
						$erphp_colon_domains_arr = explode(',', $erphp_colon_domains);
						foreach ($erphp_colon_domains_arr as $erphp_colon_domain) {
							if(strpos($filepath, $erphp_colon_domain)){
								$filepath = str_replace('：', ': ', $filepath);
								break;
							}
						}
					}

					$erphp_blank_domain_is = 0;
					if($erphp_blank_domains){
						$erphp_blank_domains_arr = explode(',', $erphp_blank_domains);
						foreach ($erphp_blank_domains_arr as $erphp_blank_domain) {
							if(strpos($filepath, $erphp_blank_domain)){
								$erphp_blank_domain_is = 1;
								break;
							}
						}
					}

					if(strpos($filepath,',')){
						$filearr = explode(',',$filepath);
						$arrlength = count($filearr);
						if($arrlength == 1){
							$downMsgFree.="<div class='erphpdown-item'>文件".($k+1)."地址<a href='".$filepath."' target='_blank' class='erphpdown-down'>点击下载</a></div>";
						}elseif($arrlength == 2){
							$downMsgFree.="<div class='erphpdown-item'>".$filearr[0]."<a href='".$filearr[1]."' target='_blank' class='erphpdown-down'>点击下载</a></div>";
						}elseif($arrlength == 3){
							$filearr2 = str_replace('：', ': ', $filearr[2]);
							$downMsgFree.="<div class='erphpdown-item'>".$filearr[0]."<a href='".$filearr[1]."' target='_blank' class='erphpdown-down'>点击下载</a>".$filearr2."<a class='erphpdown-copy' data-clipboard-text='".str_replace('提取码: ', '', $filearr2)."' href='javascript:;'>复制</a></div>";
						}
					}elseif(strpos($filepath,'  ') && $erphp_blank_domain_is){
						$filearr = explode('  ',$filepath);
						$arrlength = count($filearr);
						if($arrlength == 1){
							$downMsgFree.="<div class='erphpdown-item'>文件".($k+1)."地址<a href='".$filepath."' target='_blank' class='erphpdown-down'>点击下载</a></div>";
						}elseif($arrlength >= 2){
							$filearr2 = explode(':',$filearr[0]);
							$filearr3 = explode(':',$filearr[1]);
							$downMsgFree.="<div class='erphpdown-item'>".$filearr2[0]."<a href='".trim($filearr2[1].':'.$filearr2[2])."' target='_blank' class='erphpdown-down'>点击下载</a>提取码: ".trim($filearr3[1])."<a class='erphpdown-copy' data-clipboard-text='".trim($filearr3[1])."' href='javascript:;'>复制</a></div>";
						}
					}elseif(strpos($filepath,' ') && $erphp_blank_domain_is){
						$filearr = explode(' ',$filepath);
						$arrlength = count($filearr);
						if($arrlength == 1){
							$downMsgFree.="<div class='erphpdown-item'>文件".($k+1)."地址<a href='".$filepath."' target='_blank' class='erphpdown-down'>点击下载</a></div>";
						}elseif($arrlength == 2){
							$downMsgFree.="<div class='erphpdown-item'>".$filearr[0]."<a href='".$filearr[1]."' target='_blank' class='erphpdown-down'>点击下载</a></div>";
						}elseif($arrlength >= 3){
							$downMsgFree.="<div class='erphpdown-item'>".str_replace(':', '', $filearr[0])."<a href='".$filearr[1]."' target='_blank' class='erphpdown-down'>点击下载</a>".$filearr[2].' '.$filearr[3]."<a class='erphpdown-copy' data-clipboard-text='".$filearr[3]."' href='javascript:;'>复制</a></div>";
						}
					}else{
						$downMsgFree.="<div class='erphpdown-item'>文件".($k+1)."地址<a href='".$filepath."' target='_blank' class='erphpdown-down'>点击下载</a></div>";
					}
				}
			}

			$downMsgFree .= '</div>';
			if(get_option('ice_tips_free')) $downMsgFree.='<div class="erphpdown-tips erphpdown-tips-free">'.get_option('ice_tips_free').'</div>';
			if($start_down2 || $start_down){
				$downMsgFree .= '<div class="erphpdown-title">付费资源</div>';
			}
		}
		
		if($start_down2){
			$downMsg = '';
			if($url){
				if(function_exists('epd_check_pan_callback')){
					if(strpos($url,'pan.baidu.com') !== false || (strpos($url,'lanzou') !== false && strpos($url,'.com') !== false) || strpos($url,'cloud.189.cn') !== false){
						$down_checkpan = '<a class="erphpdown-buy erphpdown-checkpan2" href="javascript:;" data-id="'.get_the_ID().'" data-post="'.get_the_ID().'">点击检测网盘有效后购买</a>';
					}
				}

				$content.='<div class="be-erphpdown be-erphpdown-default" id="be-erphpdown"><legend>资源下载</legend>'.$downMsgFree;
				
				$user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;
				$wppay = new EPD(get_the_ID(), $user_id);

				$ews_erphpdown = get_option("ews_erphpdown");

				if($wppay->isWppayPaid() || $wppay->isWppayPaidNew() || !$price || ($memberDown == 3 && $userType) || ($memberDown == 16 && $userType >= 8) || ($memberDown == 6 && $userType >= 9) || ($memberDown == 7 && $userType >= 10) || ($ews_erphpdown && function_exists("ews_erphpdown") && isset($_COOKIE['ewd_'.get_the_ID()]))){
					$down_can = 1;
					$downList=explode("\r\n",trim($url));
					foreach ($downList as $k=>$v){
						$filepath = trim($downList[$k]);
						if($filepath){

							if($erphp_colon_domains){
								$erphp_colon_domains_arr = explode(',', $erphp_colon_domains);
								foreach ($erphp_colon_domains_arr as $erphp_colon_domain) {
									if(strpos($filepath, $erphp_colon_domain)){
										$filepath = str_replace('：', ': ', $filepath);
										break;
									}
								}
							}

							$erphp_blank_domain_is = 0;
							if($erphp_blank_domains){
								$erphp_blank_domains_arr = explode(',', $erphp_blank_domains);
								foreach ($erphp_blank_domains_arr as $erphp_blank_domain) {
									if(strpos($filepath, $erphp_blank_domain)){
										$erphp_blank_domain_is = 1;
										break;
									}
								}
							}

							if(strpos($filepath,',')){
								$filearr = explode(',',$filepath);
								$arrlength = count($filearr);
								if($arrlength == 1){
									$downMsg.="<div class='erphpdown-item'>文件".($k+1)."地址<a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>点击下载</a></div>";
								}elseif($arrlength == 2){
									$downMsg.="<div class='erphpdown-item'>".$filearr[0]."<a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>点击下载</a></div>";
								}elseif($arrlength == 3){
									$filearr2 = str_replace('：', ': ', $filearr[2]);
									$downMsg.="<div class='erphpdown-item'>".$filearr[0]."<a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>点击下载</a>（".$filearr2."）<a class='erphpdown-copy' data-clipboard-text='".str_replace('提取码: ', '', $filearr2)."' href='javascript:;'>复制</a></div>";
								}
							}elseif(strpos($filepath,'  ') && $erphp_blank_domain_is){
								$filearr = explode('  ',$filepath);
								$arrlength = count($filearr);
								if($arrlength == 1){
									$downMsg.="<div class='erphpdown-item'>文件".($k+1)."地址<a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>点击下载</a></div>";
								}elseif($arrlength >= 2){
									$filearr2 = explode(':',$filearr[0]);
									$filearr3 = explode(':',$filearr[1]);
									$downMsg.="<div class='erphpdown-item'>".$filearr2[0]."<a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>点击下载</a>（提取码: ".trim($filearr3[1])."）<a class='erphpdown-copy' data-clipboard-text='".trim($filearr3[1])."' href='javascript:;'>复制</a></div>";
								}
							}elseif(strpos($filepath,' ') && $erphp_blank_domain_is){
								$filearr = explode(' ',$filepath);
								$arrlength = count($filearr);
								if($arrlength == 1){
									$downMsg.="<div class='erphpdown-item'>文件".($k+1)."地址<a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>点击下载</a></div>";
								}elseif($arrlength == 2){
									$downMsg.="<div class='erphpdown-item'>".$filearr[0]."<a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>点击下载</a></div>";
								}elseif($arrlength >= 3){
									$downMsg.="<div class='erphpdown-item'>".str_replace(':', '', $filearr[0])."<a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>点击下载</a>（".$filearr[2].' '.$filearr[3]."）<a class='erphpdown-copy' data-clipboard-text='".$filearr[3]."' href='javascript:;'>复制</a></div>";
								}
							}else{
								$downMsg.="<div class='erphpdown-item'>文件".($k+1)."地址<a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>点击下载</a></div>";
							}
						}
					}
					$content .= $downMsg;	
					if($hidden){
						$content .= '<div class="erphpdown-item">提取码：'.$hidden.' <a class="erphpdown-copy" data-clipboard-text="'.$hidden.'" href="javascript:;">复制</a></div>';
					}
				}else{
					if($url){
						$tname = '资源下载';
					}else{
						$tname = '内容查看';
					}
					if($memberDown == 3 || $memberDown == 16 || $memberDown == 6 || $memberDown == 7){
						$wppay_vip_name = $erphp_vip_name;
						if($memberDown == 16){
							$wppay_vip_name = $erphp_quarter_name;
						}elseif($memberDown == 6){
							$wppay_vip_name = $erphp_year_name;
						}elseif($memberDown == 7){
							$wppay_vip_name = $erphp_life_name;
						}

						if($down_checkpan) $content .= $tname.'价格<span class="erphpdown-price">'.$price.'</span>元'.$down_checkpan.'&nbsp;&nbsp;<b>或</b>&nbsp;&nbsp;升级'.$wppay_vip_name.'后免费<a href="'.$erphp_url_front_vip2.'" target="_blank" class="erphpdown-vip'.(is_user_logged_in()?'':' erphp-login-must').'">升级'.$wppay_vip_name.'</a>';
						else $content .= $tname.'价格<span class="erphpdown-price">'.$price.'</span>元<a href="javascript:;" class="erphp-wppay-loader erphpdown-buy" data-post="'.get_the_ID().'">立即购买</a>&nbsp;&nbsp;<b>或</b>&nbsp;&nbsp;升级'.$wppay_vip_name.'后免费<a href="'.$erphp_url_front_vip2.'" target="_blank" class="erphpdown-vip'.(is_user_logged_in()?'':' erphp-login-must').'">升级'.$wppay_vip_name.'</a>';
					}else{
						if($down_checkpan) $content .= $tname.'价格<span class="erphpdown-price">'.$price.'</span>元'.$down_checkpan;
						else $content .= $tname.'价格<span class="erphpdown-price">'.$price.'</span>元<a href="javascript:;" class="erphp-wppay-loader erphpdown-buy" data-post="'.get_the_ID().'">立即购买</a>';
					}

					$ews_erphpdown = get_option("ews_erphpdown");
					if(!$down_can && $ews_erphpdown && function_exists("ews_erphpdown")){
						$ews_erphpdown_btn = get_option("ews_erphpdown_btn");
						$ews_erphpdown_btn = $ews_erphpdown_btn?$ews_erphpdown_btn:'关注公众号免费下载';
						$content.='<a class="erphpdown-buy ews-erphpdown-button" data-id="'.get_the_ID().'" href="javascript:;">'.$ews_erphpdown_btn.'</a>';
					}
				}
				
				if(get_option('ice_tips')) $content.='<div class="erphpdown-tips">'.get_option('ice_tips').'</div>';
				$content.='</div>';
			}

		}elseif($start_down){
			$tuanHtml = '';
			$content.='<div class="be-erphpdown be-erphpdown-default" id="be-erphpdown">'.$downMsgFree;
			if($down_tuan == '2' && function_exists('erphpdown_tuan_install')){
				$tuanHtml = erphpdown_tuan_html();
				$content .= $tuanHtml;
			}else{
				if($price_type){
					if($urls){
						$cnt = count($urls['index']);
	        			if($cnt){
	        				for($i=0; $i<$cnt;$i++){
	        					$index = $urls['index'][$i];
	        					$index_name = $urls['name'][$i];
	        					$price = $urls['price'][$i];
	        					$index_url = $urls['url'][$i];
	        					$index_vip = $urls['vip'][$i];

	        					$indexMemberDown = $memberDown;
	        					if($index_vip){
	        						$indexMemberDown = $index_vip;
	        					}

	        					$content .= '<div class="erphpdown-child">'.$index_name.'';

	        					$down_checkpan = '';
	        					if(function_exists('epd_check_pan_callback')){
									if(strpos($index_url,'pan.baidu.com') !== false || (strpos($index_url,'lanzou') !== false && strpos($index_url,'.com') !== false) || strpos($index_url,'cloud.189.cn') !== false){
										$down_checkpan = '<a class="erphpdown-buy erphpdown-checkpan" href="javascript:;" data-id="'.get_the_ID().'" data-index="'.$index.'" data-buy="'.constant("erphpdown").'buy.php?postid='.get_the_ID().'&index='.$index.'">点击检测网盘有效后购买</a>';
									}
								}

	        					if(is_user_logged_in()){
									if($price){
										if($indexMemberDown != 4 && $indexMemberDown != 15 && $indexMemberDown != 8 && $indexMemberDown != 9)
											$content.='下载价格为<span class="erphpdown-price">'.$price.'</span>'.get_option("ice_name_alipay");
									}else{
										if($indexMemberDown != 4 && $indexMemberDown != 15 && $indexMemberDown != 8 && $indexMemberDown != 9)
											$content.='免费资源';
									}

									if($price || $indexMemberDown == 4 || $indexMemberDown == 15 || $indexMemberDown == 8 || $indexMemberDown == 9){
										$user_info=wp_get_current_user();
										$down_info=$wpdb->get_row("select * from ".$wpdb->icealipay." where ice_post='".get_the_ID()."' and ice_index='".$index."' and ice_success=1 and ice_user_id=".$user_info->ID." order by ice_time desc");
										if($days > 0 && $down_info){
											$lastDownDate = date('Y-m-d H:i:s',strtotime('+'.$days.' day',strtotime($down_info->ice_time)));
											$nowDate = date('Y-m-d H:i:s');
											if(strtotime($nowDate) > strtotime($lastDownDate)){
												$down_info = null;
											}
										}

										if($down_repeat){
											$down_info_repeat = $down_info;
											$down_info = null;
										}

										$buyText = '立即购买';
										if($down_repeat && $down_info_repeat && !$down_info){
											$buyText = '再次购买';
										}

										if( ($userType && ($indexMemberDown==3 || $indexMemberDown==4)) || $down_info || (($indexMemberDown==15 || $indexMemberDown==16) && $userType >= 8) || (($indexMemberDown==6 || $indexMemberDown==8) && $userType >= 9) || (($indexMemberDown==7 || $indexMemberDown==9 || $indexMemberDown==13 || $indexMemberDown==14) && $userType == 10) || (!$price && $indexMemberDown!=4 && $indexMemberDown!=15 && $indexMemberDown!=8 && $indexMemberDown!=9)){

											if($indexMemberDown==3){
												$content.='，'.$erphp_vip_name.'免费';
											}elseif($indexMemberDown==2){
												$content.='，'.$erphp_vip_name.' 5折';
											}elseif($indexMemberDown==13){
												$content.='，'.$erphp_vip_name.' 5折、'.$erphp_life_name.'免费';
											}elseif($indexMemberDown==5){
												$content.='，'.$erphp_vip_name.' 8折';
											}elseif($indexMemberDown==14){
												$content.='，'.$erphp_vip_name.' 8折、'.$erphp_life_name.'免费';
											}elseif($indexMemberDown==16){
												$content .= '，'.$erphp_quarter_name.'免费';
											}elseif($indexMemberDown==6){
												$content .= '，'.$erphp_year_name.'免费';
											}elseif($indexMemberDown==7){
												$content .= '，'.$erphp_life_name.'免费';
											}elseif($indexMemberDown==4){
												$content .= '仅限'.$erphp_vip_name.'下载';
											}elseif($indexMemberDown == 15){
												$content .= '仅限'.$erphp_quarter_name.'下载';
											}elseif($indexMemberDown == 8){
												$content .= '仅限'.$erphp_year_name.'下载';
											}elseif($indexMemberDown == 9){
												$content .= '仅限'.$erphp_life_name.'下载';
											}elseif ($indexMemberDown==10){
												$content .= '仅限'.$erphp_vip_name.'购买';
											}elseif ($indexMemberDown==11){
												$content .= '仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 5折';
											}elseif ($indexMemberDown==12){
												$content .= '仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 8折';
											}

											$content.="<a href='".constant("erphpdown").'download.php?postid='.get_the_ID()."&index=".$index.$iframe."' class='erphpdown-down".$erphp_popdown."' rel='external nofollow' target='_blank'>立即下载</a>";
										}else{
											$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
											if($userType){
												$vipText = '';
												if(($indexMemberDown == 13 || $indexMemberDown == 14) && $userType < 10){
													$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_life_name.'</a>';
												}
											}

											if($indexMemberDown==3){
												$content.='，'.$erphp_vip_name.'免费'.$vipText;
											}elseif ($indexMemberDown==2){
												$content.='，'.$erphp_vip_name.' 5折'.$vipText;
											}elseif ($indexMemberDown==13){
												$content.='，'.$erphp_vip_name.' 5折、'.$erphp_life_name.'免费'.$vipText;
											}elseif ($indexMemberDown==5){
												$content.='，'.$erphp_vip_name.' 8折'.$vipText.'';
											}elseif ($indexMemberDown==14){
												$content.='，'.$erphp_vip_name.' 8折、'.$erphp_life_name.'免费'.$vipText;
											}elseif ($indexMemberDown==16){
												if($userType < 8){
													$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_quarter_name.'</a>';
												}
												$content.='，'.$erphp_quarter_name.'免费'.$vipText;
											}elseif ($indexMemberDown==6){
												if($userType < 9){
													$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_year_name.'</a>';
												}
												$content.='，'.$erphp_year_name.'免费'.$vipText;
											}elseif ($indexMemberDown==7){
												if($userType < 10){
													$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_life_name.'</a>';
												}
												$content.='，'.$erphp_life_name.'免费'.$vipText;
											}elseif ($indexMemberDown==4){
												if($userType){
													$content.=''.$erphp_vip_name.'专享资源';
												}
											}elseif ($indexMemberDown==15){
												if($userType >= 9){
													$content.=''.$erphp_quarter_name.'专享资源';
												}
											}elseif ($indexMemberDown==8){
												if($userType >= 9){
													$content.=''.$erphp_year_name.'专享资源';
												}
											}elseif ($indexMemberDown==9){
												if($userType >= 10){
													$content.=''.$erphp_life_name.'专享资源';
												}
											}
											

											if($indexMemberDown==4){
												$content.='仅限'.$erphp_vip_name.'下载<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
											}elseif($indexMemberDown==15){
												$content.='仅限'.$erphp_quarter_name.'下载<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip">升级'.$erphp_quarter_name.'</a>';
											}elseif($indexMemberDown==8){
												$content.='仅限'.$erphp_year_name.'下载<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip">升级'.$erphp_year_name.'</a>';
											}elseif($indexMemberDown==9){
												$content.='仅限'.$erphp_life_name.'下载<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip">升级'.$erphp_life_name.'</a>';
											}elseif($indexMemberDown==10){
												if($userType){
													$content.='仅限'.$erphp_vip_name.'购买';
													if($down_checkpan) $content .= $down_checkpan;
													else $content.='<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().'&index='.$index.' target="_blank">'.$buyText.'</a>';

													if($days){
														$content.= '购买后'.$days.'天内可下载';
													}
												}else{
													$content.='仅限'.$erphp_vip_name.'购买）<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
												}
											}elseif($indexMemberDown==11){
												if($userType){
													$content.='仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 5折';
													if($down_checkpan) $content .= $down_checkpan;
													else $content.='<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().'&index='.$index.' target="_blank">'.$buyText.'</a>';

													if($days){
														$content.= '购买后'.$days.'天内可下载';
													}
												}else{
													$content.='仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 5折）<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
												}
											}elseif($indexMemberDown==12){
												if($userType){
													$content.='仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 8折';
													if($down_checkpan) $content .= $down_checkpan;
													else $content.='<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().'&index='.$index.' target="_blank">'.$buyText.'</a>';

													if($days){
														$content.= '购买后'.$days.'天内可下载';
													}
												}else{
													$content.='仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 8折）<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
												}
											}else{
												if($down_checkpan) $content .= $down_checkpan;
												else $content.='<a class="erphpdown-iframe erphpdown-buy" href="'.constant("erphpdown").'buy.php?postid='.get_the_ID().'&index='.$index.'" target="_blank">'.$buyText.'</a>';

												if($days){
													$content.= '购买后'.$days.'天内可下载';
												}
											}

										}
										
									}else{
										$content.="<a href='".constant("erphpdown").'download.php?postid='.get_the_ID()."&index=".$index.$iframe."' class='erphpdown-down".$erphp_popdown."' rel='external nofollow' target='_blank'>立即下载</a>";
									}
									
								}else{
									if($indexMemberDown == 4 || $indexMemberDown == 15 || $indexMemberDown == 8 || $indexMemberDown == 9){
										$content.='仅限'.$erphp_vip_name.'下载' . $ed_login;
									}else{
										if($price){
											$content.='下载价格为<span class="erphpdown-price">'.$price.'</span>'.get_option('ice_name_alipay').'' . $ed_login;
										}else{
											$content.='免费资源' . $ed_login;
										}
									}
								}
								if(get_option('erphp_repeatdown_btn') && $down_repeat && $down_info_repeat && !$down_info){
									$content.='<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().'&index='.$index.$iframe.' class="erphpdown-down'.$erphp_popdown.'" rel="external nofollow" target="_blank">立即下载</a>';
								}
	        					$content .= '</div>';
	        				}
	        			}
					}
				}else{
					if(function_exists('erphpdown_tuan_install')){
						$tuanHtml = erphpdown_tuan_html();
					}

					if(function_exists('epd_check_pan_callback')){
						if(strpos($url,'pan.baidu.com') !== false || (strpos($url,'lanzou') !== false && strpos($url,'.com') !== false) || strpos($url,'cloud.189.cn') !== false){
							$down_checkpan = '<a class="erphpdown-buy erphpdown-checkpan" href="javascript:;" data-id="'.get_the_ID().'" data-index="0" data-buy="'.constant("erphpdown").'buy.php?postid='.get_the_ID().'">点击检测网盘有效后购买</a>';
						}
					}
					if(is_user_logged_in()){
						if($price){
							if($memberDown != 4 && $memberDown != 15 && $memberDown != 8 && $memberDown != 9)
								$content.='下载价格为<span class="erphpdown-price">'.$price.'</span>'.get_option("ice_name_alipay");
						}else{
							if($memberDown != 4 && $memberDown != 15 && $memberDown != 8 && $memberDown != 9)
								$content.='仅限注册用户下载';
						}

						if($price || $memberDown == 4 || $memberDown == 15 || $memberDown == 8 || $memberDown == 9){
							$user_info=wp_get_current_user();
							$down_info=$wpdb->get_row("select * from ".$wpdb->icealipay." where ice_post='".get_the_ID()."' and ice_success=1 and (ice_index is null or ice_index = '') and ice_user_id=".$user_info->ID." order by ice_time desc");
							if($days > 0 && $down_info){
								$lastDownDate = date('Y-m-d H:i:s',strtotime('+'.$days.' day',strtotime($down_info->ice_time)));
								$nowDate = date('Y-m-d H:i:s');
								if(strtotime($nowDate) > strtotime($lastDownDate)){
									$down_info = null;
								}
							}

							if($down_repeat){
								$down_info_repeat = $down_info;
								$down_info = null;
							}

							$buyText = '立即购买';
							if($down_repeat && $down_info_repeat && !$down_info){
								$buyText = '再次购买';
							}

							$user_id = $user_info->ID;
							$wppay = new EPD(get_the_ID(), $user_id);

							$ews_erphpdown = get_option("ews_erphpdown");
							if($ews_erphpdown && function_exists("ews_erphpdown") && isset($_COOKIE['ewd_'.get_the_ID()])){
								$down_can = 1;
								$content.="<a href=".constant("erphpdown").'download.php?postid='.get_the_ID().$iframe." class='erphpdown-down".$erphp_popdown."' rel='external nofollow' target='_blank'>立即下载</a>";

							}elseif( ($userType && ($memberDown==3 || $memberDown==4)) || (($wppay->isWppayPaid() || $wppay->isWppayPaidNew()) && !$down_repeat) || $down_info || (($memberDown==15 || $memberDown==16) && $userType >= 8) || (($memberDown==6 || $memberDown==8) && $userType >= 9) || (($memberDown==7 || $memberDown==9 || $memberDown==13 || $memberDown==14) && $userType == 10) || (!$price && $memberDown!=4 && $memberDown!=15 && $memberDown!=8 && $memberDown!=9)){

								$down_can = 1;

								if($memberDown==3){
									$content.='，'.$erphp_vip_name.'免费';
								}elseif($memberDown==2){
									$content.='，'.$erphp_vip_name.' 5折';
								}elseif($memberDown==13){
									$content.='，'.$erphp_vip_name.' 5折、'.$erphp_life_name.'免费';
								}elseif($memberDown==5){
									$content.='，'.$erphp_vip_name.' 8折';
								}elseif($memberDown==14){
									$content.='，'.$erphp_vip_name.' 8折、'.$erphp_life_name.'免费';
								}elseif($memberDown==16){
									$content .= '，'.$erphp_quarter_name.'免费';
								}elseif($memberDown==6){
									$content .= '，'.$erphp_year_name.'免费';
								}elseif($memberDown==7){
									$content .= '，'.$erphp_life_name.'免费';
								}elseif($memberDown==4){
									$content .= '仅限'.$erphp_vip_name.'下载';
								}elseif($memberDown==15){
									$content .= '仅限'.$erphp_quarter_name.'下载';
								}elseif($memberDown==8){
									$content .= '仅限'.$erphp_year_name.'下载';
								}elseif($memberDown==9){
									$content .= '仅限'.$erphp_life_name.'下载';
								}elseif ($memberDown==10){
									$content .= '仅限'.$erphp_vip_name.'购买';
								}elseif ($memberDown==11){
									$content .= '仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 5折';
								}elseif ($memberDown==12){
									$content .= '仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 8折';
								}

								$content.="<a href=".constant("erphpdown").'download.php?postid='.get_the_ID().$iframe." class='erphpdown-down".$erphp_popdown."' rel='external nofollow' target='_blank'>立即下载</a>";

							}else{
							
								$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
								if($userType){
									$vipText = '';
									if(($memberDown == 13 || $memberDown == 14) && $userType < 10){
										$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_life_name.'</a>';
									}
								}
								if($memberDown==3){
									$content.='，'.$erphp_vip_name.'免费'.$vipText;
								}elseif ($memberDown==2){
									$content.='，'.$erphp_vip_name.' 5折'.$vipText;
								}elseif ($memberDown==13){
									$content.='，'.$erphp_vip_name.' 5折、'.$erphp_life_name.'免费'.$vipText;
								}elseif ($memberDown==5){
									$content.='，'.$erphp_vip_name.' 8折'.$vipText;
								}elseif ($memberDown==14){
									$content.='，'.$erphp_vip_name.' 8折、'.$erphp_life_name.'免费'.$vipText;
								}elseif ($memberDown==16){
									if($userType < 8){
										$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_quarter_name.'</a>';
									}
									$content.='，'.$erphp_quarter_name.'免费'.$vipText;
								}elseif ($memberDown==6){
									if($userType < 9){
										$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_year_name.'</a>';
									}
									$content.='，'.$erphp_year_name.'免费'.$vipText;
								}elseif ($memberDown==7){
									if($userType < 10){
										$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_life_name.'</a>';
									}
									$content.='，'.$erphp_life_name.'免费'.$vipText;
								}elseif ($memberDown==4){
									if($userType){
										$content.=''.$erphp_vip_name.'专享资源';
									}
								}elseif ($memberDown==15){
									if($userType >= 9){
										$content.=''.$erphp_quarter_name.'专享资源';
									}
								}elseif ($memberDown==8){
									if($userType >= 9){
										$content.=''.$erphp_year_name.'专享资源';
									}
								}elseif ($memberDown==9){
									if($userType >= 10){
										$content.=''.$erphp_life_name.'专享资源';
									}
								}


								if($memberDown==4){
									$content.='仅限'.$erphp_vip_name.'下载<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip erphpdown-vip-one">升级'.$erphp_vip_name.'</a>';
								}elseif($memberDown==15){
									$content.='仅限'.$erphp_quarter_name.'下载<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip erphpdown-vip-one">升级'.$erphp_quarter_name.'</a>';
								}elseif($memberDown==8){
									$content.='仅限'.$erphp_year_name.'下载<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip erphpdown-vip-one">升级'.$erphp_year_name.'</a>';
								}elseif($memberDown==9){
									$content.='仅限'.$erphp_life_name.'下载<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip erphpdown-vip-one">升级'.$erphp_life_name.'</a>';
								}elseif($memberDown==10){
									if($userType){
										$content.='仅限'.$erphp_vip_name.'购买';
										if($down_checkpan) $content .= $down_checkpan;
										else $content.='<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">'.$buyText.'</a>';

										if($days){
											$content.= '购买后'.$days.'天内可下载';
										}
									}else{
										$content.='仅限'.$erphp_vip_name.'购买）<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip erphpdown-vip-one">升级'.$erphp_vip_name.'</a>';
									}
								}elseif($memberDown==11){
									if($userType){
										$content.='仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 5折';
										if($down_checkpan) $content .= $down_checkpan;
										else $content.='<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">'.$buyText.'</a>';

										if($days){
											$content.= '购买后'.$days.'天内可下载';
										}
									}else{
										$content.='仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 5折）<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
									}
								}elseif($memberDown==12){
									if($userType){
										$content.='仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 8折';
										if($down_checkpan) $content .= $down_checkpan;
										else $content.='<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">'.$buyText.'</a>';

										if($days){
											$content.= '购买后'.$days.'天内可下载';
										}
									}else{
										$content.='仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 8折）<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
									}
								}else{
									
									if($down_checkpan) $content .= $down_checkpan;
									else $content.='<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">'.$buyText.'</a>';

									if($days){
										$content.= '购买后'.$days.'天内可下载';
									}
								}
							}
							
						}else{
							$down_can = 1;
							$content.="<a href=".constant("erphpdown").'download.php?postid='.get_the_ID().$iframe." class='erphpdown-down".$erphp_popdown."' rel='external nofollow' target='_blank'>立即下载</a>";
						}
						
					}else{
						if($memberDown == 4){
							$content.='仅限'.$erphp_vip_name.'下载' . $ed_login;
						}elseif($memberDown == 15){
							$content.='仅限'.$erphp_quarter_name.'下载' . $ed_login;
						}elseif($memberDown == 8){
							$content.='仅限'.$erphp_year_name.'下载' . $ed_login;
						}elseif($memberDown == 9){
							$content.='仅限'.$erphp_life_name.'下载' . $ed_login;
						}elseif($memberDown == 10){
							$content.='仅限'.$erphp_vip_name.'购买' . $ed_login;
						}elseif($memberDown == 11){
							$content.='仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 5折' . $ed_login;
						}elseif($memberDown == 12){
							$content.='仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 8折' . $ed_login;
						}else{
							$vip_content = '';
							if($memberDown==3){
								$vip_content.='，'.$erphp_vip_name.'免费';
							}elseif($memberDown==2){
								$vip_content.='，'.$erphp_vip_name.' 5折';
							}elseif($memberDown==13){
								$vip_content.='，'.$erphp_vip_name.' 5折、'.$erphp_life_name.'免费';
							}elseif($memberDown==5){
								$vip_content.='，'.$erphp_vip_name.' 8折';
							}elseif($memberDown==14){
								$vip_content.='，'.$erphp_vip_name.' 8折、'.$erphp_life_name.'免费';
							}elseif($memberDown==16){
								$vip_content .= '，'.$erphp_quarter_name.'免费';
							}elseif($memberDown==6){
								$vip_content .= '，'.$erphp_year_name.'免费';
							}elseif($memberDown==7){
								$vip_content .= '，'.$erphp_life_name.'免费';
							}

							if(get_option('erphp_wppay_down')){
								$user_id = 0;
								$wppay = new EPD(get_the_ID(), $user_id);
								if($wppay->isWppayPaid() || $wppay->isWppayPaidNew()){
									$down_can = 1;
									if($price){
										$content.='下载价格为<span class="erphpdown-price">'.$price.'</span>'.get_option("ice_name_alipay");
									}
									$content.="<a href=".constant("erphpdown").'download.php?postid='.get_the_ID().$iframe." class='erphpdown-down".$erphp_popdown."' rel='external nofollow' target='_blank'>立即下载</a>";
								}else{
									if($price){
										// 改
										$content .= '下载价格为<span class="erphpdown-price">' . $price . '</span>' . get_option('ice_name_alipay');
										if ($vip_content) {
											if ( zm_get_option( 'be_login_epd' ) ) {
												$content .= $vip_content . '<a class="erphp-login-must ed-login-vip-btu show-layer erphpdown-vip">立即升级</a>';
											} else {
												$content .= $vip_content . '<a href="' . $erphp_url_front_login . '" target="_blank" class="erphpdown-vip erphp-login-must">立即升级</a>';
											}
										}

										if ($down_checkpan) {
											$content .= $down_checkpan;
										} else {
											$content .= '<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">立即购买</a>';
										}

									}else{
										if(!get_option('erphp_free_login')){
											$down_can = 1;
											$content.="为免费资源<a href=".constant("erphpdown").'download.php?postid='.get_the_ID().$iframe." class='erphpdown-down".$erphp_popdown."' rel='external nofollow' target='_blank'>立即下载</a>";
										}else{
											$content.='仅限注册用户下载' . $ed_login;
										}
									}
								}
							}else{
								if($price){
									$content.='下载价格为<span class="erphpdown-price">'.$price.'</span>'.get_option('ice_name_alipay').$vip_content.'' . $ed_login;
								}else{
									$content.='仅限注册用户下载' . $ed_login;
								}
							}
						}
					}

					if(get_option('erphp_repeatdown_btn') && $down_repeat && $down_info_repeat && !$down_info){
						$down_can = 1;
						$content.='<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().$iframe.' class="erphpdown-down'.$erphp_popdown.'" rel="external nofollow" target="_blank">立即下载</a>';
					}
					
				}

				$ews_erphpdown = get_option("ews_erphpdown");
				if(!$down_can && $ews_erphpdown && function_exists("ews_erphpdown")){
					$ews_erphpdown_btn = get_option("ews_erphpdown_btn");
					$ews_erphpdown_btn = $ews_erphpdown_btn?$ews_erphpdown_btn:'关注公众号免费下载';
					$content.='<a class="erphpdown-buy ews-erphpdown-button" data-id="'.get_the_ID().'" href="javascript:;">'.$ews_erphpdown_btn.'</a>';
				}
				
				if(get_option('ice_tips')) $content.='<div class="erphpdown-tips">'.get_option('ice_tips').'</div>';

				$content .= $tuanHtml;
			}
			$content.='</div>';
		}elseif($start_see){
			
			if(is_user_logged_in()){
				$user_info=wp_get_current_user();
				$down_info=$wpdb->get_row("select * from ".$wpdb->icealipay." where ice_post='".get_the_ID()."' and ice_success=1 and (ice_index is null or ice_index = '') and ice_user_id=".$user_info->ID." order by ice_time desc");
				if($days > 0 && $down_info){
					$lastDownDate = date('Y-m-d H:i:s',strtotime('+'.$days.' day',strtotime($down_info->ice_time)));
					$nowDate = date('Y-m-d H:i:s');
					if(strtotime($nowDate) > strtotime($lastDownDate)){
						$down_info = null;
					}
				}

				$user_id = $user_info->ID;
				$wppay = new EPD(get_the_ID(), $user_id);

				if( ($userType && ($memberDown==3 || $memberDown==4)) || $wppay->isWppayPaid() || $wppay->isWppayPaidNew() || $down_info || (($memberDown==15 || $memberDown==16) && $userType >= 8) || (($memberDown==6 || $memberDown==8) && $userType >= 9) || (($memberDown==7 || $memberDown==9 || $memberDown==13 || $memberDown==14) && $userType == 10) || (!$price && $memberDown!=4 && $memberDown!=15 && $memberDown!=8 && $memberDown!=9)){
					return $content;
				}else{
				
					$content2='<div class="be-erphpdown be-erphpdown-default be-erphpdown-see" id="be-erphpdown">内容查看';
					if($price){
						if($memberDown != 4 && $memberDown != 15 && $memberDown != 8 && $memberDown != 9){
							$content2.='此内容查看价格为<span class="erphpdown-price">'.$price.'</span>'.get_option('ice_name_alipay');
						}
					}

					$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
					if($userType){
						$vipText = '';
						if(($memberDown == 13 || $memberDown == 14) && $userType < 10){
							$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_life_name.'</a>';
						}
					}
					if($memberDown==3){
						$content2.='，'.$erphp_vip_name.'免费'.$vipText;
					}elseif ($memberDown==2){
						$content2.='，'.$erphp_vip_name.' 5折'.$vipText;
					}elseif ($memberDown==13){
						$content2.='，'.$erphp_vip_name.' 5折、'.$erphp_life_name.'免费'.$vipText;
					}elseif ($memberDown==5){
						$content2.='，'.$erphp_vip_name.' 8折'.$vipText;
					}elseif ($memberDown==14){
						$content2.='，'.$erphp_vip_name.' 8折、'.$erphp_life_name.'免费'.$vipText;
					}elseif ($memberDown==16){
						if($userType < 8){
							$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_quarter_name.'</a>';
						}
						$content2.='，'.$erphp_quarter_name.'免费'.$vipText;
					}elseif ($memberDown==6){
						if($userType < 9){
							$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_year_name.'</a>';
						}
						$content2.='，'.$erphp_year_name.'免费'.$vipText;
					}elseif ($memberDown==7){
						if($userType < 10){
							$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_life_name.'</a>';
						}
						$content2.='，'.$erphp_life_name.'免费'.$vipText;
					}
					
					if($memberDown==4)
					{
						$content2.='此内容仅限'.$erphp_vip_name.'查看<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
					}elseif($memberDown==15)
					{
						$content2.='此内容仅限'.$erphp_quarter_name.'查看<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_quarter_name.'</a>';
					}elseif($memberDown==8)
					{
						$content2.='此内容仅限'.$erphp_year_name.'查看<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_year_name.'</a>';
					}elseif($memberDown==9)
					{
						$content2.='此内容仅限'.$erphp_life_name.'查看<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_life_name.'</a>';
					}elseif($memberDown==10){
						if($userType){
							$content2.='仅限'.$erphp_vip_name.'购买）<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">立即购买</a>';

							if($days){
								$content2.= '购买后'.$days.'天内可查看';
							}
						}else{
							$content2.='仅限'.$erphp_vip_name.'购买）<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
						}
					}elseif($memberDown==11){
						if($userType){
							$content2.='仅限'.$erphp_vip_name.'购买，'.$erphp_year_name.' 5折）<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">立即购买</a>';

							if($days){
								$content2.= '购买后'.$days.'天内可查看';
							}
						}else{
							$content2.='仅限'.$erphp_vip_name.'购买，'.$erphp_year_name.' 5折）<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
						}
					}elseif($memberDown==12){
						if($userType){
							$content2.='仅限'.$erphp_vip_name.'购买，'.$erphp_year_name.' 8折）<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">立即购买</a>';

							if($days){
								$content2.= '购买后'.$days.'天内可查看';
							}
						}else{
							$content2.='仅限'.$erphp_vip_name.'购买，'.$erphp_year_name.' 8折）<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
						}
					}else{
						$content2.='<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().'>立即购买</a>';

						if($days){
							$content2.= '购买后'.$days.'天内可查看';
						}
					}
				}

			}else{
				$content2='<div class="be-erphpdown be-erphpdown-default be-erphpdown-see" id="be-erphpdown"><div>内容查看</div>';

				if($memberDown == 4){
					$content2.='此内容仅限'.$erphp_vip_name.'查看' . $ed_login;
				}elseif($memberDown == 15){
					$content2.='此内容仅限'.$erphp_quarter_name.'查看' . $ed_login;
				}elseif($memberDown == 8){
					$content2.='此内容仅限'.$erphp_year_name.'查看' . $ed_login;
				}elseif($memberDown == 9){
					$content2.='此内容仅限'.$erphp_life_name.'查看' . $ed_login;
				}elseif($memberDown == 10){
					$content2.='此内容仅限'.$erphp_vip_name.'购买' . $ed_login;
				}elseif($memberDown == 11){
					$content2.='此内容仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 5折' . $ed_login;
				}elseif($memberDown == 12){
					$content2.='此内容仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 8折' . $ed_login;
				}else{
					$vip_content = '';
					if($memberDown==3){
						$vip_content.='，'.$erphp_vip_name.'免费';
					}elseif($memberDown==2){
						$vip_content.='，'.$erphp_vip_name.' 5折';
					}elseif($memberDown==13){
						$vip_content.='，'.$erphp_vip_name.' 5折、'.$erphp_life_name.'免费';
					}elseif($memberDown==5){
						$vip_content.='，'.$erphp_vip_name.' 8折';
					}elseif($memberDown==14){
						$vip_content.='，'.$erphp_vip_name.' 8折、'.$erphp_life_name.'免费';
					}elseif($memberDown==16){
						$vip_content .= '，'.$erphp_quarter_name.'免费';
					}elseif($memberDown==6){
						$vip_content .= '，'.$erphp_year_name.'免费';
					}elseif($memberDown==7){
						$vip_content .= '，'.$erphp_life_name.'免费';
					}

					if(get_option('erphp_wppay_down')){
						$user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;
						$wppay = new EPD(get_the_ID(), $user_id);
						if($wppay->isWppayPaid() || $wppay->isWppayPaidNew()){
							return $content;
						}else{
							if($price){
								$content2.='此内容查看价格为<span class="erphpdown-price">'.$price.'</span>'.get_option('ice_name_alipay');
								$content2.='<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">立即购买</a>';

								if ( zm_get_option( 'be_login_epd' ) ) {
									$content2 .= $vip_content?($vip_content.'<a class="erphp-login-must ed-login-vip-btu show-layer erphpdown-vip">立即升级</a>'):'';
								} else {
									$content2 .= $vip_content?($vip_content.'<a href="'.$erphp_url_front_login.'" target="_blank" class="erphpdown-vip erphp-login-must">立即升级</a>'):'';
								}

							}else{
								if(!get_option('erphp_free_login')){
									return $content;
								}else{
									$content2.='此内容仅限注册用户查看' . $ed_login;
								}
							}
						}
					}else{
						if($price){
							$content2.='此内容查看价格为<span class="erphpdown-price">'.$price.'</span>'.get_option('ice_name_alipay').$vip_content.'' . $ed_login;
						}else{
							$content2.='此内容仅限注册用户查看' . $ed_login;
						}
						
					}
				}
				
			}
			if(get_option('ice_tips')) $content2.='<div class="erphpdown-tips">'.get_option('ice_tips').'</div>';
			$content2.='</div>';
			return $content2;
			
		}elseif($start_see2 && $erphp_see2_style){
			
			if(is_user_logged_in()){
				$user_info=wp_get_current_user();
				$down_info=$wpdb->get_row("select * from ".$wpdb->icealipay." where ice_post='".get_the_ID()."' and ice_success=1 and (ice_index is null or ice_index = '') and ice_user_id=".$user_info->ID." order by ice_time desc");
				if($days > 0 && $down_info){
					$lastDownDate = date('Y-m-d H:i:s',strtotime('+'.$days.' day',strtotime($down_info->ice_time)));
					$nowDate = date('Y-m-d H:i:s');
					if(strtotime($nowDate) > strtotime($lastDownDate)){
						$down_info = null;
					}
				}
				if( ($userType && ($memberDown==3 || $memberDown==4)) || $down_info || (($memberDown==15 || $memberDown==16) && $userType >= 8) || (($memberDown==6 || $memberDown==8) && $userType >= 9) || (($memberDown==7 || $memberDown==9 || $memberDown==13 || $memberDown==14) && $userType == 10) || (!$price && $memberDown!=4 && $memberDown!=15 && $memberDown!=8 && $memberDown!=9)){
					
				}else{
				
					$content.='<div class="be-erphpdown be-erphpdown-default be-erphpdown-see" id="be-erphpdown"><div>内容查看</div>';
					if($price){
						if($memberDown != 4 && $memberDown != 15 && $memberDown != 8 && $memberDown != 9)
							$content.='本文隐藏内容查看价格为<span class="erphpdown-price">'.$price.'</span>'.get_option('ice_name_alipay');
					}
					

					$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
					if($userType){
						$vipText = '';
						if(($memberDown == 13 || $memberDown == 14) && $userType < 10){
							$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_life_name.'</a>';
						}
					}
					if($memberDown==3){
						$content.='，'.$erphp_vip_name.'免费'.$vipText;
					}elseif ($memberDown==2){
						$content.='，'.$erphp_vip_name.' 5折'.$vipText;
					}elseif ($memberDown==13){
						$content.='，'.$erphp_vip_name.' 5折、'.$erphp_life_name.'免费'.$vipText;
					}elseif ($memberDown==5){
						$content.='，'.$erphp_vip_name.' 8折'.$vipText;
					}elseif ($memberDown==14){
						$content.='，'.$erphp_vip_name.' 8折、'.$erphp_life_name.'免费'.$vipText;
					}elseif ($memberDown==16){
						if($userType < 9){
							$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_quarter_name.'</a>';
						}
						$content.='，'.$erphp_quarter_name.'免费'.$vipText;
					}elseif ($memberDown==6){
						if($userType < 9){
							$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_year_name.'</a>';
						}
						$content.='，'.$erphp_year_name.'免费'.$vipText;
					}elseif ($memberDown==7){
						if($userType < 10){
							$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_life_name.'</a>';
						}
						$content.='，'.$erphp_life_name.'免费'.$vipText;
					}
					
					if($memberDown==4)
					{
						$content.='本文隐藏内容仅限'.$erphp_vip_name.'查看<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
					}elseif($memberDown==15)
					{
						$content.='本文隐藏内容仅限'.$erphp_quarter_name.'查看<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_quarter_name.'</a>';
					}elseif($memberDown==8)
					{
						$content.='本文隐藏内容仅限'.$erphp_year_name.'查看<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_year_name.'</a>';
					}elseif($memberDown==9)
					{
						$content.='本文隐藏内容仅限'.$erphp_life_name.'查看<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_life_name.'</a>';
					}elseif($memberDown==10){
						if($userType){
							$content.='仅限'.$erphp_vip_name.'购买）<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">立即购买</a>';

							if($days){
								$content.= '购买后'.$days.'天内可查看';
							}
						}else{
							$content.='仅限'.$erphp_vip_name.'购买）<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
						}
					}elseif($memberDown==11){
						if($userType){
							$content.='仅限'.$erphp_vip_name.'购买，'.$erphp_year_name.' 5折）<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">立即购买</a>';
							if($days){
								$content.= '购买后'.$days.'天内可查看';
							}
						}else{
							$content.='仅限'.$erphp_vip_name.'购买，'.$erphp_year_name.' 5折）<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
						}
					}elseif($memberDown==12){
						if($userType){
							$content.='仅限'.$erphp_vip_name.'购买，'.$erphp_year_name.' 8折）<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">立即购买</a>';

							if($days){
								$content.= '购买后'.$days.'天内可查看';
							}
						}else{
							$content.='仅限'.$erphp_vip_name.'购买，'.$erphp_year_name.' 8折）<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
						}
					}else{
						
						$content.='<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().'>立即购买</a>';
						if($days){
							$content.= '购买后'.$days.'天内可查看';
						}
					}

					if(get_option('ice_tips')) $content.='<div class="erphpdown-tips">'.get_option('ice_tips').'</div>';
					$content.='</div>';
				}

			}else{
				$content.='<div class="be-erphpdown be-erphpdown-default be-erphpdown-see" id="be-erphpdown"><div>内容查看</div>';
				
				if($memberDown == 4){
					$content.='本文隐藏内容仅限'.$erphp_vip_name.'查看' . $ed_login;
				}elseif($memberDown == 15){
					$content.='本文隐藏内容仅限'.$erphp_quarter_name.'查看' . $ed_login;
				}elseif($memberDown == 8){
					$content.='本文隐藏内容仅限'.$erphp_year_name.'查看' . $ed_login;
				}elseif($memberDown == 9){
					$content.='本文隐藏内容仅限'.$erphp_life_name.'查看' . $ed_login;
				}elseif($memberDown == 10){
					$content.='本文隐藏内容仅限'.$erphp_vip_name.'购买' . $ed_login;
				}elseif($memberDown == 11){
					$content.='本文隐藏内容仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 5折' . $ed_login;
				}elseif($memberDown == 12){
					$content.='本文隐藏内容仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 8折' . $ed_login;
				}else{
					$vip_content = '';
					if($memberDown==3){
						$vip_content.='，'.$erphp_vip_name.'免费';
					}elseif($memberDown==2){
						$vip_content.='，'.$erphp_vip_name.' 5折';
					}elseif($memberDown==13){
						$vip_content.='，'.$erphp_vip_name.' 5折、'.$erphp_life_name.'免费';
					}elseif($memberDown==5){
						$vip_content.='，'.$erphp_vip_name.' 8折';
					}elseif($memberDown==14){
						$vip_content.='，'.$erphp_vip_name.' 8折、'.$erphp_life_name.'免费';
					}elseif($memberDown==16){
						$vip_content .= '，'.$erphp_quarter_name.'免费';
					}elseif($memberDown==6){
						$vip_content .= '，'.$erphp_year_name.'免费';
					}elseif($memberDown==7){
						$vip_content .= '，'.$erphp_life_name.'免费';
					}

					if(get_option('erphp_wppay_down')){
						$user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;
						$wppay = new EPD(get_the_ID(), $user_id);
						if($wppay->isWppayPaid() || $wppay->isWppayPaidNew()){
							return '';
						}else{
							if($price){
								$content.='本文隐藏内容查看价格为<span class="erphpdown-price">'.$price.'</span>'.get_option('ice_name_alipay');
								$content.='<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">立即购买</a>';

								if ( zm_get_option( 'be_login_epd' ) ) {
									$content .= $vip_content?($vip_content.'<a class="erphp-login-must ed-login-vip-btu show-layer erphpdown-vip">立即升级</a>'):'';
								} else {
									$content .= $vip_content?($vip_content.'<a href="'.$erphp_url_front_login.'" target="_blank" class="erphpdown-vip erphp-login-must">立即升级</a>'):'';
								}
							}else{
								if(!get_option('erphp_free_login')){
									return '';
								}else{
									$content.='此内容仅限注册用户查看' . $ed_login;
								}
							}
						}
					}else{
						if($price){
							$content.='本文隐藏内容查看价格为<span class="erphpdown-price">'.$price.'</span>'.get_option('ice_name_alipay').$vip_content.'' . $ed_login;
						}else{
							$content.='本文隐藏内容仅限注册用户查看' . $ed_login;
						}
						
					}
				}
				if(get_option('ice_tips')) $content.='<div class="erphpdown-tips">'.get_option('ice_tips').'</div>';
				$content.='</div>';
			}

			return $content;

		}elseif($erphp_down == 6){
			$content .= '<div class="be-erphpdown be-erphpdown-default" id="be-erphpdown"><div>自动发卡</div>';
			$content .= '此卡密价格为<span class="erphpdown-price">'.$price.'</span>'.get_option("ice_name_alipay");
			$content .= '<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">立即购买</a>';
			if(function_exists('getErphpActLeft')) $content .= '库存：'.getErphpActLeft(get_the_ID()).'';
			$content .= '</div>';
		}else{
			if($downMsgFree) $content.='<div class="be-erphpdown be-erphpdown-default" id="be-erphpdown">'.$downMsgFree.'</div>';
		}
		
	}else{
		$start_see=get_post_meta(get_the_ID(), 'start_see', true);
		if($start_see){
			return '';
		}
	}

	if ( epd_goods_count () > 0 && zm_get_option( 'goods_count' ) ) {
		$content .= '<div class="epd-goods-count"><span class="epd-goods">' . zm_get_option( 'down_sold_inf' ) . '</span>' . epd_goods_count () . '</div>';
	}

	return $content;
}

// 短代码
function be_erphpdown_shortcode_see($atts, $content=null){
	$atts = shortcode_atts( array(
        'index' => '',
        'type' => '',
        'image' => '',
        'price' => ''
    ), $atts, 'erphpdown' );
	date_default_timezone_set('Asia/Shanghai'); 
	global $post,$wpdb;

	$type_class = '';
	$type_style = '';
	if($atts['type'] == "video"){
		$type_class = " erphpdown-see-video";
	}
	if($atts['image']){
		$type_style = 'position:relative;background-color:#000 !important;background-image:url('.$atts['image'].') !important;background-repeat:no-repeat !important;background-size:cover !important;background-position:center !important;border:none;text-align:center;color:#fff';
	}

	$erphp_life_name    = get_option('erphp_life_name')?get_option('erphp_life_name'):'终身VIP';
	$erphp_year_name    = get_option('erphp_year_name')?get_option('erphp_year_name'):'包年VIP';
	$erphp_quarter_name = get_option('erphp_quarter_name')?get_option('erphp_quarter_name'):'包季VIP';
	$erphp_vip_name  = get_option('erphp_vip_name')?get_option('erphp_vip_name'):'VIP';

	$erphp_vip_discounts = $erphp_vip_name.'折扣';

	$original_content = $content;

	$erphp_see2_style = get_option('erphp_see2_style');
	$erphp_wppay_vip = get_option('erphp_wppay_vip');

	$days=get_post_meta($post->ID, 'down_days', true);
	$down_info = null;

	$erphp_url_front_vip = get_bloginfo('wpurl').'/wp-admin/admin.php?page=erphpdown/admin/erphp-update-vip.php';
	if(get_option('erphp_url_front_vip')){
		$erphp_url_front_vip = get_option('erphp_url_front_vip');
	}
	$erphp_url_front_login = wp_login_url(get_permalink());
	if(get_option('erphp_url_front_login')){
		$erphp_url_front_login = get_option('erphp_url_front_login');
	}

	if(is_user_logged_in()){
		$erphp_url_front_vip2 = $erphp_url_front_vip;
	}else{
		$erphp_url_front_vip2 = $erphp_url_front_login;
	}

	if($atts['index'] > 0 && is_numeric($atts['index'])){
		if($atts['price'] > 0 && is_numeric($atts['price'])){
			$price_index = $atts['price'];
		}else{
			$price_index = get_post_meta($post->ID, 'down_price', true);
		}

		if($price_index > 0){
			$html='<div class="erphpdown erphpdown-see erphpdown-content-vip" style="display:block">';
			if(is_user_logged_in()){
				$user_info=wp_get_current_user();
				$down_info=$wpdb->get_row("select * from ".$wpdb->iceindex." where ice_post='".$post->ID."' and ice_index=".$atts['index']." and ice_user_id=".$user_info->ID." and ice_price='".$price_index."' order by ice_time desc");
				if($days > 0 && $down_info){
					$lastDownDate = date('Y-m-d H:i:s',strtotime('+'.$days.' day',strtotime($down_info->ice_time)));
					$nowDate = date('Y-m-d H:i:s');
					if(strtotime($nowDate) > strtotime($lastDownDate)){
						$down_info = null;
					}
				}
				if($down_info){
					return '<div class="erphpdown-content-view">'.do_shortcode($content).'</div>';
				}else{
					$html.='此内容查看价格为<span class="erphpdown-price">'.$price_index.'</span>'.get_option('ice_name_alipay');
					$html.='<a class="erphpdown-buy erphpdown-buy-index" href="javascript:;" data-post="'.$post->ID.'" data-index="'.$atts['index'].'" data-price="'.$price_index.'">立即购买</a>';
					if($days){
						$html.= '（购买后'.$days.'天内可查看）';
					}
					$html .= '</div>';
				}
			}else{
				$html.='此内容查看价格为<span class="erphpdown-price">'.$price_index.'</span>'.get_option('ice_name_alipay').'，请先<a href="'.$erphp_url_front_login.'" class="erphp-login-must">登录</a></div>';
			}
			return $html;
		}else{
			return '';
		}
	}else{
		$userType=getUsreMemberType();
		if(is_single()){
			$categories = get_the_category();
			if ( !empty($categories) ) {
				$userCat=getUsreMemberCat(erphpdown_parent_cid($categories[0]->term_id));
				if(!$userType){
					if($userCat){
						$userType = $userCat;
					}
				}else{
					if($userCat){
						if($userCat > $userType){
							$userType = $userCat;
						}
					}
				}
			}
		}
		$memberDown=get_post_meta($post->ID, 'member_down',TRUE);
		$start_down2=get_post_meta($post->ID, 'start_down2', true);
		$start_down=get_post_meta($post->ID, 'start_down', true);
		$start_see2=get_post_meta($post->ID, 'start_see2', true);
		$start_see=get_post_meta($post->ID, 'start_see', true);
		$price=get_post_meta($post->ID, 'down_price', true);

		$user_info=wp_get_current_user();
		if($user_info->ID){
			$down_info=$wpdb->get_row("select * from ".$wpdb->icealipay." where ice_post='".$post->ID."' and ice_success=1 and (ice_index is null or ice_index = '') and ice_user_id=".$user_info->ID." order by ice_time desc");
		}
		$user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;
		$wppay = new EPD($post->ID, $user_id);

		if($start_down2){
			if( $wppay->isWppayPaid() || $wppay->isWppayPaidNew() || ($memberDown == 3 && $userType) || ($memberDown == 16 && $userType >= 8) || ($memberDown == 6 && $userType >= 9) || ($memberDown == 7 && $userType >= 10) || !$price){
				return '<div class="erphpdown-content-view">'.do_shortcode($content).'</div>';
			}else{
				if($memberDown == 3 || $memberDown == 16 || $memberDown == 6 || $memberDown == 7){
					$wppay_vip_name = $erphp_vip_name;
					if($memberDown == 16){
						$wppay_vip_name = $erphp_quarter_name;
					}elseif($memberDown == 6){
						$wppay_vip_name = $erphp_year_name;
					}elseif($memberDown == 7){
						$wppay_vip_name = $erphp_life_name;
					}
					$content = '<div class="erphpdown erphpdown-see erphpdown-content-vip erphpdown-see-pay" style="display:block">此内容查看价格<span class="erphpdown-price">'.$price.'</span>元<a href="javascript:;" class="erphp-wppay-loader erphpdown-buy" data-post="'.$post->ID.'">立即购买</a>&nbsp;&nbsp;<b>或</b>&nbsp;&nbsp;升级'.$wppay_vip_name.'后免费<a href="'.$erphp_url_front_vip2.'" target="_blank" class="erphpdown-vip'.(is_user_logged_in()?'':' erphp-login-must').'">升级'.$wppay_vip_name.'</a>';
				}else{
					$content = '<div class="erphpdown erphpdown-see erphpdown-content-vip erphpdown-see-pay" style="display:block">此内容查看价格<span class="erphpdown-price">'.$price.'</span>元<a href="javascript:;" class="erphp-wppay-loader erphpdown-buy" data-post="'.get_the_ID().'">立即购买</a>';	
				}

				if(get_option('ice_tips_see')) $content.='<div class="erphpdown-tips">'.get_option('ice_tips_see').'</div>';

				$content .= '</div>'; 
				return $content;
			}
		}elseif($start_down || $start_see2 || $start_see){
			if(is_user_logged_in() || ( (($memberDown==3 || $memberDown==4) && $userType) || (($memberDown==15 || $memberDown==16) && $userType >= 8) || (($memberDown==6 || $memberDown==8) && $userType >= 9) || (($memberDown==7 || $memberDown==9 || $memberDown==13 || $memberDown==14 || $memberDown==20) && $userType == 10) )){
				if($days > 0 && $down_info){
					$lastDownDate = date('Y-m-d H:i:s',strtotime('+'.$days.' day',strtotime($down_info->ice_time)));
					$nowDate = date('Y-m-d H:i:s');
					if(strtotime($nowDate) > strtotime($lastDownDate)){
						$down_info = null;
					}
				}

				if(!$price && $memberDown!=4 && $memberDown!=15 && $memberDown!=8 && $memberDown!=9){
					return '<div class="erphpdown-content-view">'.do_shortcode($content).'</div>';
				}

				if( (($memberDown==3 || $memberDown==4) && $userType) || $wppay->isWppayPaid() || $wppay->isWppayPaidNew() || $down_info || (($memberDown==15 || $memberDown==16) && $userType >= 8) || (($memberDown==6 || $memberDown==8) && $userType >= 9) || (($memberDown==7 || $memberDown==9 || $memberDown==13 || $memberDown==14 || $memberDown==20) && $userType == 10) ){

					if(!$wppay->isWppayPaid() && !$wppay->isWppayPaidNew() && !$down_info){

						$erphp_life_times    = get_option('erphp_life_times');
						$erphp_year_times    = get_option('erphp_year_times');
						$erphp_quarter_times = get_option('erphp_quarter_times');
						$erphp_month_times  = get_option('erphp_month_times');
						$erphp_day_times  = get_option('erphp_day_times');

						if(checkDownHas($user_info->ID,$post->ID)){
							return '<div class="erphpdown-content-view">'.do_shortcode($content).'</div>';
						}else{
							if($userType == 6 && $erphp_day_times > 0){
								if( checkSeeLog($user_info->ID,$post->ID,$erphp_day_times,erphpGetIP()) ){
									return '<p class="erphpdown-content-vip erphpdown-content-vip-see">您可免费查看本文隐藏内容！<a href="javascript:;" class="erphpdown-see-btn" data-post="'.$post->ID.'">立即查看</a>（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_day_times-getSeeCount($user_info->ID)).'个）</p>';
								}else{
									return '<p class="erphpdown-content-vip">您暂时无权查看本文隐藏内容，请明天再来！（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_day_times-getSeeCount($user_info->ID)).'个）</p>';
								}
							}elseif($userType == 7 && $erphp_month_times > 0){
								if( checkSeeLog($user_info->ID,$post->ID,$erphp_month_times,erphpGetIP()) ){
									return '<p class="erphpdown-content-vip erphpdown-content-vip-see">您可免费查看本文隐藏内容！<a href="javascript:;" class="erphpdown-see-btn" data-post="'.$post->ID.'">立即查看</a>（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_month_times-getSeeCount($user_info->ID)).'个）</p>';
								}else{
									return '<p class="erphpdown-content-vip">您暂时无权查看本文隐藏内容，请明天再来！（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_month_times-getSeeCount($user_info->ID)).'个）</p>';
								}
							}elseif($userType == 8 && $erphp_quarter_times > 0){
								if( checkSeeLog($user_info->ID,$post->ID,$erphp_quarter_times,erphpGetIP()) ){
									return '<p class="erphpdown-content-vip erphpdown-content-vip-see">您可免费查看本文隐藏内容！<a href="javascript:;" class="erphpdown-see-btn" data-post="'.$post->ID.'">立即查看</a>（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_quarter_times-getSeeCount($user_info->ID)).'个）</p>';
								}else{
									return '<p class="erphpdown-content-vip">您暂时无权查看本文隐藏内容，请明天再来！（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_quarter_times-getSeeCount($user_info->ID)).'个）</p>';
								}
							}elseif($userType == 9 && $erphp_year_times > 0){
								if( checkSeeLog($user_info->ID,$post->ID,$erphp_year_times,erphpGetIP()) ){
									return '<p class="erphpdown-content-vip erphpdown-content-vip-see">您可免费查看本文隐藏内容！<a href="javascript:;" class="erphpdown-see-btn" data-post="'.$post->ID.'">立即查看</a>（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_year_times-getSeeCount($user_info->ID)).'个）</p>';
								}else{
									return '<p class="erphpdown-content-vip">您暂时无权查看本文隐藏内容，请明天再来！（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_year_times-getSeeCount($user_info->ID)).'个）</p>';
								}
							}elseif($userType == 10 && $erphp_life_times > 0){
								if( checkSeeLog($user_info->ID,$post->ID,$erphp_life_times,erphpGetIP()) ){
									return '<p class="erphpdown-content-vip erphpdown-content-vip-see">您可免费查看本文隐藏内容！<a href="javascript:;" class="erphpdown-see-btn" data-post="'.$post->ID.'">立即查看</a>（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_life_times-getSeeCount($user_info->ID)).'个）</p>';
								}else{
									return '<p class="erphpdown-content-vip">您暂时无权查看本文隐藏内容，请明天再来！（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_life_times-getSeeCount($user_info->ID)).'个）</p>';
								}
							}else{
								return '<div class="erphpdown-content-view">'.do_shortcode($content).'</div>';
							}
						}
					}else{
						return '<div class="erphpdown-content-view">'.do_shortcode($content).'</div>';
					}
				}else{
					if($erphp_see2_style){
						$content = '<div class="erphpdown-content-vip erphpdown-content-vip2">'.__('您暂时无权查看此隐藏内容！','erphpdown').'</div>';
					}else{
						$content = '<div class="erphpdown erphpdown-see erphpdown-see-pay erphpdown-content-vip'.$type_class.'" style="display:block;'.$type_style.'">';
						if($price){
							if($memberDown != 4 && $memberDown != 15 && $memberDown != 8 && $memberDown != 9){
								$content.='此内容查看价格为<span class="erphpdown-price">'.$price.'</span>'.get_option('ice_name_alipay');
							}
						}else{
							if($memberDown != 4 && $memberDown != 15 && $memberDown != 8 && $memberDown != 9){
								return '<div class="erphpdown-content-view">'.do_shortcode($content).'</div>';
							}
						}
						

						$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
						if($userType){
							$vipText = '';
							if(($memberDown == 13 || $memberDown == 14 || $memberDown == 20) && $userType < 10){
								$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_life_name.'</a>';
							}
						}
						if($memberDown==3){
							$content.='（'.$erphp_vip_name.'免费）'.$vipText;
						}elseif ($memberDown==2){
							$content.='（'.$erphp_vip_name.' 5折）'.$vipText;
						}elseif ($memberDown==5){
							$content.='（'.$erphp_vip_name.' 8折）'.$vipText;
						}elseif ($memberDown==13){
							$content.='（'.$erphp_vip_name.' 5折、'.$erphp_life_name.'免费）'.$vipText;
						}elseif ($memberDown==14){
							$content.='（'.$erphp_vip_name.' 8折、'.$erphp_life_name.'免费）'.$vipText;
						}elseif ($memberDown==20){
							$content.='（'.$erphp_vip_discounts.'、'.$erphp_life_name.'免费）'.$vipText;
						}elseif ($memberDown==16){
							if($userType < 9){
								$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_quarter_name.'</a>';
							}
							$content.='（'.$erphp_quarter_name.'免费）'.$vipText;
						}elseif ($memberDown==6){
							if($userType < 9){
								$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_year_name.'</a>';
							}
							$content.='（'.$erphp_year_name.'免费）'.$vipText;
						}elseif ($memberDown==7){
							if($userType < 10){
								$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_life_name.'</a>';
							}
							$content.='（'.$erphp_life_name.'免费）'.$vipText;
						}
						

						if($memberDown==4){
							$content.='此内容仅限'.$erphp_vip_name.'查看<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip erphpdown-vip-one">升级'.$erphp_vip_name.'</a>';
						}elseif($memberDown==15)
						{
							$content.='此内容仅限'.$erphp_quarter_name.'查看<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip erphpdown-vip-one">升级'.$erphp_quarter_name.'</a>';
						}elseif($memberDown==8)
						{
							$content.='此内容仅限'.$erphp_year_name.'查看<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip erphpdown-vip-one">升级'.$erphp_year_name.'</a>';
						}elseif($memberDown==9)
						{
							$content.='此内容仅限'.$erphp_life_name.'查看<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip erphpdown-vip-one">升级'.$erphp_life_name.'</a>';
						}elseif($memberDown==10){
							if($userType){
								$content.='（仅限'.$erphp_vip_name.'购买）<a class="erphpdown-iframe erphpdown-buy" href="'.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().'" target="_blank">立即购买</a>';
								if($days){
									$content.= '（购买后'.$days.'天内可查看）';
								}
							}else{
								$content.='（仅限'.$erphp_vip_name.'购买）<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
							}
						}elseif($memberDown==17){
							if($userType >= 8){
								$content.='（仅限'.$erphp_quarter_name.'购买）<a class="erphpdown-iframe erphpdown-buy" href="'.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().'" target="_blank">立即购买</a>';
								if($days){
									$content.= '（购买后'.$days.'天内可查看）';
								}
							}else{
								$content.='（仅限'.$erphp_quarter_name.'购买）<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip">升级'.$erphp_quarter_name.'</a>';
							}
						}elseif($memberDown==18){
							if($userType >= 9){
								$content.='（仅限'.$erphp_year_name.'购买）<a class="erphpdown-iframe erphpdown-buy" href="'.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().'" target="_blank">立即购买</a>';
								if($days){
									$content.= '（购买后'.$days.'天内可查看）';
								}
							}else{
								$content.='（仅限'.$erphp_year_name.'购买）<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip">升级'.$erphp_year_name.'</a>';
							}
						}elseif($memberDown==19){
							if($userType == 10){
								$content.='（仅限'.$erphp_life_name.'购买）<a class="erphpdown-iframe erphpdown-buy" href="'.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().'" target="_blank">立即购买</a>';
								if($days){
									$content.= '（购买后'.$days.'天内可查看）';
								}
							}else{
								$content.='（仅限'.$erphp_life_name.'购买）<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip">升级'.$erphp_life_name.'</a>';
							}
						}elseif($memberDown==11){
							if($userType){
								$content.='（仅限'.$erphp_vip_name.'购买，'.$erphp_year_name.' 5折）';
								$content.='<a class="erphpdown-iframe erphpdown-buy" href="'.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().'" target="_blank">立即购买</a>';
								if($days){
									$content.= '（购买后'.$days.'天内可查看）';
								}
							}else{
								$content.='（仅限'.$erphp_vip_name.'购买，'.$erphp_year_name.' 5折）<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
							}
						}elseif($memberDown==12){
							if($userType){
								$content.='（仅限'.$erphp_vip_name.'购买，'.$erphp_year_name.' 8折）';
								$content.='<a class="erphpdown-iframe erphpdown-buy" href="'.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().'" target="_blank">立即购买</a>';
								if($days){
									$content.= '（购买后'.$days.'天内可查看）';
								}
							}else{
								$content.='（仅限'.$erphp_vip_name.'购买，'.$erphp_year_name.' 8折）<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
							}
						}else{

							$content.='<a class="erphpdown-iframe erphpdown-buy" href="'.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().'" target="_blank">立即购买</a>';
							if($days){
								$content.= '（购买后'.$days.'天内可查看）';
							}
						}

						if(get_option('ice_tips_see')) $content.='<div class="erphpdown-tips">'.get_option('ice_tips_see').'</div>';
						$content.='</div>';
					}
					return $content;
				}
			}else{
				$content2 = $content;
				$content='<div class="erphpdown erphpdown-see erphpdown-see-pay erphpdown-content-vip'.$type_class.'" id="erphpdown" style="display:block;'.$type_style.'">';

				if($memberDown == 4){
					if($erphp_see2_style){
						return '<div class="erphpdown-content-vip erphpdown-content-vip2">'.__('您暂时无权查看此隐藏内容！','erphpdown').'</div>';
					}else{
						if($erphp_wppay_vip){
							$content.='此内容仅限'.$erphp_vip_name.'查看<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip" target="_blank">升级'.$erphp_vip_name.'</a>';
						}else{
							if ( zm_get_option( 'be_login_epd' ) ) {
								$content.='此内容仅限'.$erphp_vip_name.'查看，请先<a class="erphp-login-must ed-login-btu show-layer">登录</a>';
							} else {
								$content.='此内容仅限'.$erphp_vip_name.'查看，请先<a href="'.$erphp_url_front_login.'" class="erphp-login-must">登录</a>';
							}
						}
					}
				}elseif($memberDown == 15){
					if($erphp_see2_style){
						return '<div class="erphpdown-content-vip erphpdown-content-vip2">'.__('您暂时无权查看此隐藏内容！','erphpdown').'</div>';
					}else{
						if($erphp_wppay_vip){
							$content.='此内容仅限'.$erphp_quarter_name.'查看<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip" target="_blank">升级'.$erphp_quarter_name.'</a>';
						}else{

							if ( zm_get_option( 'be_login_epd' ) ) {
								$content.='此内容仅限'.$erphp_quarter_name.'查看，请先<a class="erphp-login-must ed-login-btu show-layer">登录</a>';
							} else {
								$content.='此内容仅限'.$erphp_quarter_name.'查看，请先<a href="'.$erphp_url_front_login.'" class="erphp-login-must">登录</a>';
							}

						}
					}
				}elseif($memberDown == 8){
					if($erphp_see2_style){
						return '<div class="erphpdown-content-vip erphpdown-content-vip2">'.__('您暂时无权查看此隐藏内容！','erphpdown').'</div>';
					}else{
						if($erphp_wppay_vip){
							$content.='此内容仅限'.$erphp_year_name.'查看<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip" target="_blank">升级'.$erphp_year_name.'</a>';
						}else{

							if ( zm_get_option( 'be_login_epd' ) ) {
								$content.='此内容仅限'.$erphp_year_name.'查看，请先<a class="erphp-login-must ed-login-btu show-layer">登录</a>';
							} else {
								$content.='此内容仅限'.$erphp_year_name.'查看，请先<a href="'.$erphp_url_front_login.'" class="erphp-login-must">登录</a>';
							}

						}
					}
				}elseif($memberDown == 9){
					if($erphp_see2_style){
						return '<div class="erphpdown-content-vip erphpdown-content-vip2">'.__('您暂时无权查看此隐藏内容！','erphpdown').'</div>';
					}else{
						if($erphp_wppay_vip){
							$content.='此内容仅限'.$erphp_life_name.'查看<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip" target="_blank">升级'.$erphp_life_name.'</a>';
						}else{
							if ( zm_get_option( 'be_login_epd' ) ) {
								$content.='此内容仅限'.$erphp_life_name.'查看，请先<a class="erphp-login-must ed-login-btu show-layer">登录</a>';
							} else {
								$content.='此内容仅限'.$erphp_life_name.'查看，请先<a href="'.$erphp_url_front_login.'" class="erphp-login-must">登录</a>';
							}
						}
					}
				}elseif($memberDown == 10){
					if($erphp_see2_style){
						return '<div class="erphpdown-content-vip erphpdown-content-vip2">'.__('您暂时无权查看此隐藏内容！','erphpdown').'</div>';
					}else{
						if($erphp_wppay_vip){
							$content.='此内容仅限'.$erphp_vip_name.'购买<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip" target="_blank">升级'.$erphp_vip_name.'</a>';
						}else{
							if ( zm_get_option( 'be_login_epd' ) ) {
								$content.='此内容仅限'.$erphp_vip_name.'查看，请先<a class="erphp-login-must ed-login-btu show-layer">登录</a>';
							} else {
								$content.='此内容仅限'.$erphp_vip_name.'查看，请先<a href="'.$erphp_url_front_login.'" class="erphp-login-must">登录</a>';
							}
						}
					}
				}elseif($memberDown == 17){
					if($erphp_see2_style){
						return '<div class="erphpdown-content-vip erphpdown-content-vip2">'.__('您暂时无权查看此隐藏内容！','erphpdown').'</div>';
					}else{
						if($erphp_wppay_vip){
							$content.='此内容仅限'.$erphp_quarter_name.'购买<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip" target="_blank">升级'.$erphp_quarter_name.'</a>';
						}else{
							if ( zm_get_option( 'be_login_epd' ) ) {
								$content.='此内容仅限'.$erphp_quarter_name.'查看，请先<a class="erphp-login-must ed-login-btu show-layer">登录</a>';
							} else {
								$content.='此内容仅限'.$erphp_quarter_name.'查看，请先<a href="'.$erphp_url_front_login.'" class="erphp-login-must">登录</a>';
							}
						}
					}
				}elseif($memberDown == 18){
					if($erphp_see2_style){
						return '<div class="erphpdown-content-vip erphpdown-content-vip2">'.__('您暂时无权查看此隐藏内容！','erphpdown').'</div>';
					}else{
						if($erphp_wppay_vip){
							$content.='此内容仅限'.$erphp_year_name.'购买<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip" target="_blank">升级'.$erphp_year_name.'</a>';
						}else{
							if ( zm_get_option( 'be_login_epd' ) ) {
								$content.='此内容仅限'.$erphp_year_name.'查看，请先<a class="erphp-login-must ed-login-btu show-layer">登录</a>';
							} else {
								$content.='此内容仅限'.$erphp_year_name.'查看，请先<a href="'.$erphp_url_front_login.'" class="erphp-login-must">登录</a>';
							}
						}
					}
				}elseif($memberDown == 19){
					if($erphp_see2_style){
						return '<div class="erphpdown-content-vip erphpdown-content-vip2">'.__('您暂时无权查看此隐藏内容！','erphpdown').'</div>';
					}else{
						if($erphp_wppay_vip){
							$content.='此内容仅限'.$erphp_life_name.'购买<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip" target="_blank">升级'.$erphp_life_name.'</a>';
						}else{
							$content.='此内容仅限'.$erphp_life_name.'购买，请先<a href="'.$erphp_url_front_login.'" class="erphp-login-must">登录</a>';
							if ( zm_get_option( 'be_login_epd' ) ) {
								$content.='此内容仅限'.$erphp_life_name.'查看，请先<a class="erphp-login-must ed-login-btu show-layer">登录</a>';
							} else {
								$content.='此内容仅限'.$erphp_life_name.'查看，请先<a href="'.$erphp_url_front_login.'" class="erphp-login-must">登录</a>';
							}
						}
					}
				}elseif($memberDown == 11){
					if($erphp_see2_style){
						return '<div class="erphpdown-content-vip erphpdown-content-vip2">'.__('您暂时无权查看此隐藏内容！','erphpdown').'</div>';
					}else{
						if($erphp_wppay_vip){
							$content.='此内容仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 5折<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip" target="_blank">升级'.$erphp_vip_name.'</a>';
						}else{
							if ( zm_get_option( 'be_login_epd' ) ) {
								$content.='此内容仅限'.$erphp_vip_name.'查看，请先<a class="erphp-login-must ed-login-btu show-layer">登录</a>';
							} else {
								$content.='此内容仅限'.$erphp_vip_name.'查看，请先<a href="'.$erphp_url_front_login.'" class="erphp-login-must">登录</a>';
							}
						}
					}
				}elseif($memberDown == 12){
					if($erphp_see2_style){
						return '<div class="erphpdown-content-vip erphpdown-content-vip2">'.__('您暂时无权查看此隐藏内容！','erphpdown').'</div>';
					}else{
						if($erphp_wppay_vip){
							$content.='此内容仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 8折<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip" target="_blank">升级'.$erphp_vip_name.'</a>';
						}else{
							if ( zm_get_option( 'be_login_epd' ) ) {
								$content.='此内容仅限'.$erphp_vip_name.'查看，请先<a class="erphp-login-must ed-login-btu show-layer">登录</a>';
							} else {
								$content.='此内容仅限'.$erphp_vip_name.'查看，请先<a href="'.$erphp_url_front_login.'" class="erphp-login-must">登录</a>';
							}
						}
					}
				}else{
					$vip_content = '';
					if($memberDown==3){
						$vip_content.='（'.$erphp_vip_name.'免费）';
					}elseif($memberDown==2){
						$vip_content.='（'.$erphp_vip_name.' 5折）';
					}elseif($memberDown==13){
						$vip_content.='（'.$erphp_vip_name.' 5折、'.$erphp_life_name.'免费）';
					}elseif($memberDown==5){
						$vip_content.='（'.$erphp_vip_name.' 8折）';
					}elseif($memberDown==14){
						$vip_content.='（'.$erphp_vip_name.' 8折、'.$erphp_life_name.'免费）';
					}elseif($memberDown==20){
						$vip_content.='（'.$erphp_vip_discounts.'、'.$erphp_life_name.'免费）';
					}elseif($memberDown==16){
						$vip_content .= '（'.$erphp_quarter_name.'免费）';
					}elseif($memberDown==6){
						$vip_content .= '（'.$erphp_year_name.'免费）';
					}elseif($memberDown==7){
						$vip_content .= '（'.$erphp_life_name.'免费）';
					}

					if(get_option('erphp_wppay_down')){
						$user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;
						$wppay = new EPD(get_the_ID(), $user_id);
						if($wppay->isWppayPaid() || $wppay->isWppayPaidNew()){
							return '<div class="erphpdown-content-view">'.do_shortcode($content2).'</div>';
						}else{
							if($price){
								if($erphp_see2_style){
									return '<div class="erphpdown-content-vip erphpdown-content-vip2">'.__('您暂时无权查看此隐藏内容！','erphpdown').'</div>';
								}else{
									$content.='此内容查看价格为<span class="erphpdown-price">'.$price.'</span>'.get_option('ice_name_alipay');
									$content.='<a class="erphpdown-iframe erphpdown-buy" href="'.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().'" target="_blank">立即购买</a>';

									if ( zm_get_option( 'be_login_epd' ) ) {
										$content .= $vip_content?($vip_content.'<a class="erphp-login-must ed-login-vip-btu show-layer erphpdown-vip">立即升级</a>'):'';
									} else {
										$content .= $vip_content?($vip_content.'<a href="'.$erphp_url_front_login.'" target="_blank" class="erphpdown-vip erphp-login-must">立即升级</a>'):'';
									}
								}
							}else{
								if(!get_option('erphp_free_login')){
									return '<div class="erphpdown-content-view">'.do_shortcode($content2).'</div>';
								}else{
									if($erphp_see2_style){
										return '<div class="erphpdown-content-vip erphpdown-content-vip2">'.__('您暂时无权查看此隐藏内容！','erphpdown').'</div>';
									}else{
										if ( zm_get_option( 'be_login_epd' ) ) {
											$content.='此内容仅限注册用户查看，请先<a class="erphp-login-must ed-login-btu show-layer">登录</a>';
										} else {
											$content.='此内容仅限注册用户查看，请先<a href="'.$erphp_url_front_login.'" class="erphp-login-must">登录</a>';
										}
									}
								}
							}
						}
					}else{
						if($erphp_see2_style){
							return '<div class="erphpdown-content-vip erphpdown-content-vip2">'.__('您暂时无权查看此隐藏内容！','erphpdown').'</div>';
						}else{
							if($price){
								$content.='此内容查看价格为<span class="erphpdown-price">'.$price.'</span>'.get_option('ice_name_alipay').$vip_content.'，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">登录</a>';
							}else{

								if ( zm_get_option( 'be_login_epd' ) ) {
									$content.='此内容仅限注册用户查看，请先<a class="erphp-login-must ed-login-btu show-layer">登录</a>';
								} else {
									$content.='此内容仅限注册用户查看，请先<a href="'.$erphp_url_front_login.'" class="erphp-login-must">登录</a>';
								}
							}
						}
					}
				}
				
				if(get_option('ice_tips_see')) $content.='<div class="erphpdown-tips">'.get_option('ice_tips_see').'</div>';
				$content.='</div>';
				return $content;
			}
		}
	}
}  
add_shortcode('erphpdown','be_erphpdown_shortcode_see');

function be_erphpdown_shortcode_vip($atts, $content=null){
	$atts = shortcode_atts( array(
        'type' => '',
    ), $atts, 'vip' );

  	global $post;

  	$erphp_life_name    = get_option('erphp_life_name')?get_option('erphp_life_name'):'终身VIP';
	$erphp_year_name    = get_option('erphp_year_name')?get_option('erphp_year_name'):'包年VIP';
	$erphp_quarter_name = get_option('erphp_quarter_name')?get_option('erphp_quarter_name'):'包季VIP';
	$erphp_month_name  = get_option('erphp_month_name')?get_option('erphp_month_name'):'包月VIP';
	$erphp_day_name  = get_option('erphp_day_name')?get_option('erphp_day_name'):'体验VIP';
	$erphp_vip_name  = get_option('erphp_vip_name')?get_option('erphp_vip_name'):'VIP';

	$erphp_life_times    = get_option('erphp_life_times');
	$erphp_year_times    = get_option('erphp_year_times');
	$erphp_quarter_times = get_option('erphp_quarter_times');
	$erphp_month_times  = get_option('erphp_month_times');
	$erphp_day_times  = get_option('erphp_day_times');

    $erphp_url_front_vip = get_bloginfo('wpurl').'/wp-admin/admin.php?page=erphpdown/admin/erphp-update-vip.php';
	if(get_option('erphp_url_front_vip')){
		$erphp_url_front_vip = get_option('erphp_url_front_vip');
	}
	$erphp_url_front_login = wp_login_url(get_permalink());
	if(get_option('erphp_url_front_login')){
		$erphp_url_front_login = get_option('erphp_url_front_login');
	}
	if(is_user_logged_in()){
		$vip = '<div class="erphpdown erphpdown-see erphpdown-content-vip" style="display:block">此隐藏内容仅限'.$erphp_vip_name.'查看<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_vip_name.'</a></div>';
	}else{
		if ( zm_get_option( 'be_login_epd' ) ) {
			$vip = '<div class="erphpdown erphpdown-see erphpdown-content-vip" style="display:block">此隐藏内容仅限'.$erphp_vip_name.'查看<a class="erphp-login-must ed-login-btu show-layer">登录</a></div>';
		} else {
			$vip = '<div class="erphpdown erphpdown-see erphpdown-content-vip" style="display:block">此隐藏内容仅限'.$erphp_vip_name.'查看<<a href="'.$erphp_url_front_login.'" class="erphp-login-must">登录</a></div>';
		}

	}
	$userType=getUsreMemberType();

	if(is_user_logged_in() || $userType){
		if(is_single()){
			$categories = get_the_category();
			if ( !empty($categories) ) {
				$userCat=getUsreMemberCat(erphpdown_parent_cid($categories[0]->term_id));
				if(!$userType){
					if($userCat){
						$userType = $userCat;
					}
				}else{
					if($userCat){
						if($userCat > $userType){
							$userType = $userCat;
						}
					}
				}
			}
		}

		$user_info = wp_get_current_user();
		if(!$atts['type']){
			if($userType){
				//return '<div class="erphpdown-content-view">'.do_shortcode($content).'</div>';

				if(checkDownHas($user_info->ID,$post->ID)){
					return '<div class="erphpdown-content-view">'.do_shortcode($content).'</div>';
				}else{
					if($userType == 6 && $erphp_day_times > 0){
						if( checkSeeLog($user_info->ID,$post->ID,$erphp_day_times,erphpGetIP()) ){
							return '<p class="erphpdown-content-vip erphpdown-content-vip-see">您可免费查看此隐藏内容！<a href="javascript:;" class="erphpdown-see-btn" data-post="'.$post->ID.'">立即查看</a>（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_day_times-getSeeCount($user_info->ID)).'个）</p>';
						}else{
							return '<p class="erphpdown-content-vip">您暂时无权查看此隐藏内容，请明天再来！（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_day_times-getSeeCount($user_info->ID)).'个）</p>';
						}
					}elseif($userType == 7 && $erphp_month_times > 0){
						if( checkSeeLog($user_info->ID,$post->ID,$erphp_month_times,erphpGetIP()) ){
							return '<p class="erphpdown-content-vip erphpdown-content-vip-see">您可免费查看此隐藏内容！<a href="javascript:;" class="erphpdown-see-btn" data-post="'.$post->ID.'">立即查看</a>（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_month_times-getSeeCount($user_info->ID)).'个）</p>';
						}else{
							return '<p class="erphpdown-content-vip">您暂时无权查看此隐藏内容，请明天再来！（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_month_times-getSeeCount($user_info->ID)).'个）</p>';
						}
					}elseif($userType == 8 && $erphp_quarter_times > 0){
						if( checkSeeLog($user_info->ID,$post->ID,$erphp_quarter_times,erphpGetIP()) ){
							return '<p class="erphpdown-content-vip erphpdown-content-vip-see">您可免费查看此隐藏内容！<a href="javascript:;" class="erphpdown-see-btn" data-post="'.$post->ID.'">立即查看</a>（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_quarter_times-getSeeCount($user_info->ID)).'个）</p>';
						}else{
							return '<p class="erphpdown-content-vip">您暂时无权查看此隐藏内容，请明天再来！（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_quarter_times-getSeeCount($user_info->ID)).'个）</p>';
						}
					}elseif($userType == 9 && $erphp_year_times > 0){
						if( checkSeeLog($user_info->ID,$post->ID,$erphp_year_times,erphpGetIP()) ){
							return '<p class="erphpdown-content-vip erphpdown-content-vip-see">您可免费查看此隐藏内容！<a href="javascript:;" class="erphpdown-see-btn" data-post="'.$post->ID.'">立即查看</a>（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_year_times-getSeeCount($user_info->ID)).'个）</p>';
						}else{
							return '<p class="erphpdown-content-vip">您暂时无权查看此隐藏内容，请明天再来！（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_year_times-getSeeCount($user_info->ID)).'个）</p>';
						}
					}elseif($userType == 10 && $erphp_life_times > 0){
						if( checkSeeLog($user_info->ID,$post->ID,$erphp_life_times,erphpGetIP()) ){
							return '<p class="erphpdown-content-vip erphpdown-content-vip-see">您可免费查看此隐藏内容！<a href="javascript:;" class="erphpdown-see-btn" data-post="'.$post->ID.'">立即查看</a>（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_life_times-getSeeCount($user_info->ID)).'个）</p>';
						}else{
							return '<p class="erphpdown-content-vip">您暂时无权查看此隐藏内容，请明天再来！（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_life_times-getSeeCount($user_info->ID)).'个）</p>';
						}
					}else{
						return '<div class="erphpdown-content-view">'.do_shortcode($content).'</div>';
					}
				}

			}else{
				return $vip;
			}
		}else{
			if($atts['type'] == '6' && $userType < 6){
				return '<div class="erphpdown erphpdown-see erphpdown-content-vip" style="display:block">此隐藏内容仅限'.$erphp_vip_name.'查看<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_vip_name.'</a></div>';
			}elseif($atts['type'] == '7' && $userType < 7){
				return '<div class="erphpdown erphpdown-see erphpdown-content-vip" style="display:block">此隐藏内容仅限'.$erphp_month_name.'查看<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_month_name.'</a></div>';
			}elseif($atts['type'] == '8' && $userType < 8){
				return '<div class="erphpdown erphpdown-see erphpdown-content-vip" style="display:block">此隐藏内容仅限'.$erphp_quarter_name.'查看<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_quarter_name.'</a></div>';
			}elseif($atts['type'] == '9' && $userType < 9){
				return '<div class="erphpdown erphpdown-see erphpdown-content-vip" style="display:block">此隐藏内容仅限'.$erphp_year_name.'查看<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_year_name.'</a></div>';
			}elseif($atts['type'] == '10' && $userType < 10){
				return '<div class="erphpdown erphpdown-see erphpdown-content-vip" style="display:block">此隐藏内容仅限'.$erphp_life_name.'查看<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_life_name.'</a></div>';
			}else{
				//return '<div class="erphpdown-content-view">'.do_shortcode($content).'</div>';

				if(checkDownHas($user_info->ID,$post->ID)){
					return '<div class="erphpdown-content-view">'.do_shortcode($content).'</div>';
				}else{
					if($userType == 6 && $erphp_day_times > 0){
						if( checkSeeLog($user_info->ID,$post->ID,$erphp_day_times,erphpGetIP()) ){
							return '<p class="erphpdown-content-vip erphpdown-content-vip-see">您可免费查看此隐藏内容！<a href="javascript:;" class="erphpdown-see-btn" data-post="'.$post->ID.'">立即查看</a>（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_day_times-getSeeCount($user_info->ID)).'个）</p>';
						}else{
							return '<p class="erphpdown-content-vip">您暂时无权查看此隐藏内容，请明天再来！（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_day_times-getSeeCount($user_info->ID)).'个）</p>';
						}
					}elseif($userType == 7 && $erphp_month_times > 0){
						if( checkSeeLog($user_info->ID,$post->ID,$erphp_month_times,erphpGetIP()) ){
							return '<p class="erphpdown-content-vip erphpdown-content-vip-see">您可免费查看此隐藏内容！<a href="javascript:;" class="erphpdown-see-btn" data-post="'.$post->ID.'">立即查看</a>（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_month_times-getSeeCount($user_info->ID)).'个）</p>';
						}else{
							return '<p class="erphpdown-content-vip">您暂时无权查看此隐藏内容，请明天再来！（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_month_times-getSeeCount($user_info->ID)).'个）</p>';
						}
					}elseif($userType == 8 && $erphp_quarter_times > 0){
						if( checkSeeLog($user_info->ID,$post->ID,$erphp_quarter_times,erphpGetIP()) ){
							return '<p class="erphpdown-content-vip erphpdown-content-vip-see">您可免费查看此隐藏内容！<a href="javascript:;" class="erphpdown-see-btn" data-post="'.$post->ID.'">立即查看</a>（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_quarter_times-getSeeCount($user_info->ID)).'个）</p>';
						}else{
							return '<p class="erphpdown-content-vip">您暂时无权查看此隐藏内容，请明天再来！（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_quarter_times-getSeeCount($user_info->ID)).'个）</p>';
						}
					}elseif($userType == 9 && $erphp_year_times > 0){
						if( checkSeeLog($user_info->ID,$post->ID,$erphp_year_times,erphpGetIP()) ){
							return '<p class="erphpdown-content-vip erphpdown-content-vip-see">您可免费查看此隐藏内容！<a href="javascript:;" class="erphpdown-see-btn" data-post="'.$post->ID.'">立即查看</a>（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_year_times-getSeeCount($user_info->ID)).'个）</p>';
						}else{
							return '<p class="erphpdown-content-vip">您暂时无权查看此隐藏内容，请明天再来！（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_year_times-getSeeCount($user_info->ID)).'个）</p>';
						}
					}elseif($userType == 10 && $erphp_life_times > 0){
						if( checkSeeLog($user_info->ID,$post->ID,$erphp_life_times,erphpGetIP()) ){
							return '<p class="erphpdown-content-vip erphpdown-content-vip-see">您可免费查看此隐藏内容！<a href="javascript:;" class="erphpdown-see-btn" data-post="'.$post->ID.'">立即查看</a>（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_life_times-getSeeCount($user_info->ID)).'个）</p>';
						}else{
							return '<p class="erphpdown-content-vip">您暂时无权查看此隐藏内容，请明天再来！（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_life_times-getSeeCount($user_info->ID)).'个）</p>';
						}
					}else{
						return '<div class="erphpdown-content-view">'.do_shortcode($content).'</div>';
					}
				}
			}
		}
	}else{
		return $vip;
	}
}
add_shortcode('vip','be_erphpdown_shortcode_vip');