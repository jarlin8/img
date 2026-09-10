<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$one_delete =
array(
	"hot" => array("name" => "hot"),
	"cat_top" => array("name" => "cat_top"),
	"direct" => array("name" => "direct"),
	"direct_btn" => array("name" => "direct_btn"),
	"link_inf" => array("name" => "link_inf"),
	"from" => array("name" => "from"),
	"copyright" => array("name" => "copyright"),
	"file_os" => array("name" => "file_os"),
	"file_inf" => array("name" => "file_inf"),
	"small" => array("name" => "small"),
	"product" => array("name" => "product"),
	"pricex" => array("name" => "pricex"),
	"pricey" => array("name" => "pricey"),
	"taourl" => array("name" => "taourl"),
	"discount" => array("name" => "discount"),
	"discounturl" => array("name" => "discounturl"),
	"sites_link" => array("name" => "sites_link"),
	"header_img" => array("name" => "header_img"),
	"mark" => array("name" => "mark"),
	"baidu_pan_btn" => array("name" => "baidu_pan_btn"),
	"down_local_btn" => array("name" => "down_local_btn"),
	"down_official_btn" => array("name" => "down_official_btn"),
	"sidebar_l" => array("name" => "sidebar_l"),
	"no_abstract" => array("name" => "no_abstract"),
	"special" => array("name" => "special"),
	"user_only" => array("name" => "user_only"),
	"special_img" => array("name" => "special_img"),
	"no_today" => array("name" => "no_today"),
	"not_more" => array("name" => "not_more"),
	"button1" => array("name" => "button1"),
	"url1" => array("name" => "url1"),
	"down_doc" => array("name" => "down_doc"),
	"doc_name" => array("name" => "doc_name"),
	"go_direct" => array("name" => "go_direct"),
	"no_toc" => array("name" => "no_toc"),
	"show_line" => array("name" => "show_line"),
	"toc_four" => array("name" => "toc_four"),
	"allow_copy" => array("name" => "allow_copy"),
	"sub_section" => array("name" => "sub_section"),
	"g_s_c" => array("name" => "g_s_c"),
	"fancy_box" => array("name" => "fancy_box"),
	"poster_img" => array("name" => "poster_img"),
	"sites_des" => array("name" => "sites_des"),
	"sites_url" => array("name" => "sites_url"),
	"sites_ico" => array("name" => "sites_ico"),
	"taourl_t" => array("name" => "taourl_t"),
	"m_taourl" => array("name" => "m_taourl"),
	"spare_t" => array("name" => "spare_t"),
	"tao_img_t" => array("name" => "tao_img_t"),
	"be_down_name" => array("name" => "be_down_name"),
	"be_file_os" => array("name" => "be_file_os"),
	"be_file_inf" => array("name" => "be_file_inf"),
	"be_down_size" => array("name" => "be_down_size"),
	"slider_gallery_n" => array("name" => "slider_gallery_n"),
	"show_title" => array("name" => "show_title"),
	"img_title" => array("name" => "img_title"),
	"cp_style" => array("name" => "cp_style"),
	"cp_number" => array("name" => "cp_number"),
	"cp_column" => array("name" => "cp_column"),
	"cp_sidebar_r" => array("name" => "cp_sidebar_r"),
	"cp_more" => array("name" => "cp_more"),
	"cp_infinite" => array("name" => "cp_infinite"),
	"be_img_fill" => array("name" => "be_img_fill"),
	"be_bg_img" => array("name" => "be_bg_img"),
);

function save_one_delete($post_id) {
	global $post, $one_delete;
	foreach ($one_delete as $meta_box) {
		if ( !isset($_POST[$meta_box['name'] . '_noncename']) || !$data = isset($_POST[$meta_box['name'] . '']) ? $_POST[$meta_box['name'] . ''] : null) {
			delete_post_meta($post_id, $meta_box['name'] . '', get_post_meta($post_id, $meta_box['name'] . '', true));
		}
	}
}
add_action('save_post', 'save_one_delete');