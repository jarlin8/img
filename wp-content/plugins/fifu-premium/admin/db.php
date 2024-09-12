<?php

class FifuDb {

    private $posts;
    private $postmeta;
    private $termmeta;
    private $term_taxonomy;
    private $term_relationships;
    private $query;
    private $wpdb;
    private $author;
    private $types;
    private $aawp_lists;
    private $aawp_products;

    function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->posts = $wpdb->prefix . 'posts';
        $this->options = $wpdb->prefix . 'options';
        $this->postmeta = $wpdb->prefix . 'postmeta';
        $this->terms = $wpdb->prefix . 'terms';
        $this->termmeta = $wpdb->prefix . 'termmeta';
        $this->term_taxonomy = $wpdb->prefix . 'term_taxonomy';
        $this->term_relationships = $wpdb->prefix . 'term_relationships';
        $this->fifu_md5 = $wpdb->prefix . 'fifu_md5';
        $this->fifu_video_oembed = $wpdb->prefix . 'fifu_video_oembed';
        $this->fifu_invalid_media_su = $wpdb->prefix . 'fifu_invalid_media_su';
        $this->aawp_lists = $wpdb->prefix . 'aawp_lists';
        $this->aawp_products = $wpdb->prefix . 'aawp_products';
        $this->author = fifu_get_author();
        $this->MAX_INSERT = 1500;
        $this->MAX_URL_LENGTH = 2048;
        $this->types = $this->get_types();
    }

    function get_types() {
        $post_types = fifu_get_post_types();
        return join("','", $post_types);
    }

    /* alter table */

    function change_url_length() {
        $length = $this->wpdb->get_col_length($this->posts, 'guid');
        if ($length && $length['length'] >= $this->MAX_URL_LENGTH)
            return;

        $this->wpdb->get_results("
            ALTER TABLE " . $this->posts . "
            MODIFY COLUMN guid VARCHAR(" . $this->MAX_URL_LENGTH . ")"
        );
    }

    /* deprecated data */

    function delete_deprecated_options() {
        $this->wpdb->get_results("
            DELETE FROM " . $this->options . " 
            WHERE option_name IN ('fifu_cpt0','fifu_cpt1','fifu_cpt2','fifu_cpt3','fifu_cpt4','fifu_cpt5','fifu_cpt6','fifu_cpt7','fifu_cpt8','fifu_cpt9','fifu_data_generation','fifu_debug_mode','fifu_fake2','fifu_priority','fifu_update_all_id','fifu_update_all_status','fifu_update_all_timestamp','fifu_update_number','fifu_wc_theme','fifu_max_url','fifu_variation_attach_id_0','fifu_variation_attach_id_1','fifu_variation_attach_id_2','fifu_variation_attach_id_3','fifu_variation_attach_id_4','fifu_variation_attach_id_5','fifu_variation_attach_id_6','fifu_variation_attach_id_7','fifu_variation_attach_id_8','fifu_variation_attach_id_9','fifu_default_width','fifu_video_margin_bottom','fifu_video_vertical_margin','fifu_video_width_rtio','fifu_video_height_rtio','fifu_video_height_arch','fifu_video_height_ctgr','fifu_video_height_home','fifu_video_height_page','fifu_video_height_post','fifu_video_height_prod','fifu_video_height_shop','fifu_video_width_arch','fifu_video_width_ctgr','fifu_video_width_home','fifu_video_width_page','fifu_video_width_post','fifu_video_width_prod','fifu_video_width_shop','fifu_variation_gallery','fifu_video_height','fifu_video_crop','fifu_shortcode_max_height','fifu_image_height_shop','fifu_image_width_shop','fifu_image_height_prod','fifu_image_width_prod','fifu_image_height_cart','fifu_image_width_cart','fifu_image_height_ctgr','fifu_image_width_ctgr','fifu_image_height_arch','fifu_image_width_arch','fifu_image_height_home','fifu_image_width_home','fifu_image_height_page','fifu_image_width_page','fifu_image_height_post','fifu_image_width_post','fifu_parameters','fifu_slider_fade','fifu_flickr_post','fifu_flickr_page','fifu_flickr_arch','fifu_flickr_cart','fifu_flickr_ctgr','fifu_flickr_home','fifu_flickr_prod','fifu_flickr_shop','fifu_original','fifu_save_dimensions','fifu_save_dimensions_redirect','fifu_save_dimensions_all','fifu_clean_dimensions_all','fifu_css','fifu_jquery','fifu_class','fifu_shortcode_min_width','fifu_media_library','fifu_auto_set_blocked','fifu_isbn_blocked','fifu_shortpixel','fifu_video_black','fifu_flickr','fifu_giphy','fifu_video_related','fifu_unsplash_size','fifu_spinner_slider','fifu_spinner_video','fifu_spinner_image','fifu_valid','fifu_column_height','fifu_spinner_db','fifu_spinner_cron_metadata','fifu_confirm_delete_all','fifu_confirm_delete_all_time','fifu_gallery_selector','fifu_video_gallery_icon','fifu_hover','fifu_hover_selector','fifu_shortcode','fifu_grid_category','fifu_rss_width','fifu_bbpress_avatar','fifu_bbpress_copy','fifu_screenshot_high','fifu_screenshot_height','fifu_screenshot_scale')"
        );
    }

    /* autoload no */

    function update_autoload() {
        $this->wpdb->get_results("
            UPDATE " . $this->options . " 
            SET autoload = 'no'
            WHERE option_name IN ('fifu_auto_category_created','fifu_data_clean','fifu_fake','fifu_fake_created','fifu_key','fifu_email','fifu_update_all','fifu_update_ignore')"
        );
    }

    /* wordpress upgrade */

    function fix_guid() {
        $this->wpdb->get_results("
            UPDATE " . $this->posts . " p 
            INNER JOIN " . $this->postmeta . " pm ON (
                pm.post_id = p.id 
                AND	pm.meta_key = '_wp_attached_file'
            )
            SET p.guid = pm.meta_value
            WHERE p.post_author = " . $this->author . "  
            AND LENGTH(p.guid) = 255"
        );
    }

    /* attachment metadata */

    // insert 1 _wp_attached_file for each attachment
    function insert_attachment_meta_url($ids, $is_ctgr) {
        $ctgr_sql = $is_ctgr ? "AND p.post_name LIKE 'fifu-category%'" : "";

        $this->wpdb->get_results("
            INSERT INTO " . $this->postmeta . " (post_id, meta_key, meta_value) (
                SELECT p.id, '_wp_attached_file', p.guid
                FROM " . $this->posts . " p LEFT OUTER JOIN " . $this->postmeta . " b ON p.id = b.post_id AND meta_key = '_wp_attached_file'
                WHERE b.post_id IS NULL
                AND p.post_parent IN (" . $ids . ") 
                AND p.post_type = 'attachment' 
                AND p.post_author = " . $this->author . " 
                " . $ctgr_sql . " 
            )"
        );
    }

    // delete 1 _wp_attached_file or _wp_attachment_image_alt for each attachment
    function delete_attachment_meta($ids, $is_ctgr) {
        $ctgr_sql = $is_ctgr ? "AND p.post_name LIKE 'fifu-category%'" : "";

        $this->wpdb->get_results("
            DELETE FROM " . $this->postmeta . "
            WHERE meta_key IN ('_wp_attached_file', '_wp_attachment_image_alt', '_wp_attachment_metadata', 'fifu_yt_res', 'fifu_embed_url')
            AND EXISTS (
                SELECT 1 
                FROM " . $this->posts . " p
                WHERE p.id = post_id
                AND p.post_parent IN (" . $ids . ")
                AND p.post_type = 'attachment' 
                AND p.post_author = " . $this->author . " 
                " . $ctgr_sql . " 
            )"
        );
    }

    // insert 1 _wp_attachment_image_alt for each attachment
    function insert_attachment_meta_alt($ids, $is_ctgr) {
        if (fifu_is_off('fifu_auto_alt'))
            return;

        $ctgr_sql = $is_ctgr ? "AND p.post_name LIKE 'fifu-category%'" : "";

        $this->wpdb->get_results("
            INSERT INTO " . $this->postmeta . " (post_id, meta_key, meta_value) (
                SELECT p.id, '_wp_attachment_image_alt', p.post_title 
                FROM " . $this->posts . " p LEFT OUTER JOIN " . $this->postmeta . " b ON p.id = b.post_id AND meta_key = '_wp_attachment_image_alt'
                WHERE b.post_id IS NULL
                AND p.post_parent IN (" . $ids . ") 
                AND p.post_type = 'attachment' 
                AND p.post_author = " . $this->author . " 
                " . $ctgr_sql . "
            )"
        );
    }

    // insert 1 _thumbnail_id for each attachment (posts)
    function insert_thumbnail_id($ids, $is_ctgr) {
        $ctgr_sql = $is_ctgr ? "AND p.post_name LIKE 'fifu-category%'" : "";

        $this->wpdb->get_results("
            INSERT INTO " . $this->postmeta . " (post_id, meta_key, meta_value) (
                SELECT p.post_parent, '_thumbnail_id', p.id 
                FROM " . $this->posts . " p LEFT OUTER JOIN " . $this->postmeta . " b ON p.post_parent = b.post_id AND meta_key = '_thumbnail_id'
                WHERE b.post_id IS NULL
                AND p.post_parent IN (" . $ids . ") 
                AND p.post_type = 'attachment' 
                AND p.post_author = " . $this->author . " 
                " . $ctgr_sql . " 
            )"
        );
    }

    // has attachment created by FIFU
    function is_fifu_attachment($att_id) {
        return $this->wpdb->get_row("
            SELECT 1 
            FROM " . $this->posts . " 
            WHERE id = " . $att_id . " 
            AND post_author = " . $this->author
                ) != null;
    }

    function has_fifu_attachment($att_ids) {
        return $this->wpdb->get_row("
            SELECT 1 
            FROM " . $this->posts . " 
            WHERE id IN (" . $att_ids . ")
            AND post_author = " . $this->author
                ) != null;
    }

    // get ids from categories with external media and no thumbnail_id
    function get_categories_without_meta() {
        return $this->wpdb->get_results("
            SELECT DISTINCT term_id
            FROM " . $this->termmeta . " a
            WHERE a.meta_key IN ('fifu_image_url', 'fifu_video_url', 'fifu_slider_image_url_0')
            AND a.meta_value IS NOT NULL 
            AND a.meta_value <> ''
            AND NOT EXISTS (
                SELECT 1 
                FROM " . $this->termmeta . " b 
                WHERE a.term_id = b.term_id 
                AND b.meta_key = 'thumbnail_id'
                AND b.meta_value <> 0
            )"
        );
    }

    // get ids from posts with external media and no _thumbnail_id
    function get_posts_without_meta() {
        return $this->wpdb->get_results("
            SELECT DISTINCT post_id
            FROM " . $this->postmeta . " a
            WHERE a.meta_key IN ('fifu_image_url', 'fifu_video_url', 'fifu_slider_image_url_0')
            AND a.meta_value IS NOT NULL 
            AND a.meta_value <> ''
            AND NOT EXISTS (
                SELECT 1 
                FROM (SELECT post_id FROM " . $this->postmeta . " WHERE meta_key = '_thumbnail_id') AS b
                WHERE a.post_id = b.post_id 
            )"
        );
    }

    // get ids from posts with external media and no _thumbnail_id or _product_image_gallery
    function get_all_posts_without_meta() {
        return $this->wpdb->get_results("
            SELECT DISTINCT post_id
            FROM " . $this->postmeta . " a
            WHERE 
            (
                (
                    (
                        a.meta_key LIKE 'fifu_image_url_%'
                        OR a.meta_key LIKE 'fifu_video_url_%'
                        OR a.meta_key LIKE 'fifu_slider_image_url_%'
                        OR (
                            a.meta_key IN ('fifu_list_url', 'fifu_list_video_url', 'fifu_slider_list_url')
                            AND a.meta_value LIKE '%|%'
                        )
                    )
                    AND NOT EXISTS (
                        SELECT 1 
                        FROM " . $this->postmeta . " b 
                        WHERE a.post_id = b.post_id 
                        AND b.meta_key = '_product_image_gallery'
                    )
                )
                OR
                a.meta_key IN ('fifu_image_url', 'fifu_video_url', 'fifu_slider_image_url_0')
                AND NOT EXISTS (
                    SELECT 1 
                    FROM " . $this->postmeta . " b 
                    WHERE a.post_id = b.post_id 
                    AND b.meta_key = '_thumbnail_id'
                    AND b.meta_value <> 0
                )
            )
            AND a.meta_value IS NOT NULL 
            AND a.meta_value <> ''"
        );
    }

    // get thumbnail_id from category
    function get_category_thumbnail_id($term_id) {
        return $this->wpdb->get_row("
            SELECT meta_value 
            FROM " . $this->termmeta . " 
            WHERE term_id = " . $term_id . " 
            AND meta_key = 'thumbnail_id'"
        );
    }

    // get ids from posts with external url
    function get_posts_with_url() {
        return $this->wpdb->get_results("
            SELECT post_id 
            FROM " . $this->postmeta . " 
            WHERE meta_key = 'fifu_image_url'"
        );
    }

    function get_posts_types_with_url() {
        return $this->wpdb->get_results("
            SELECT pm.post_id, p.post_type  
            FROM " . $this->postmeta . " pm
            INNER JOIN " . $this->posts . " p ON pm.post_id = p.ID 
            WHERE pm.meta_key = 'fifu_image_url'"
        );
    }

    // get ids from terms with external url
    function get_terms_with_url() {
        return $this->wpdb->get_results("
            SELECT term_id 
            FROM " . $this->termmeta . " 
            WHERE meta_key IN ('fifu_image_url', 'fifu_video_url')
            AND meta_value <> ''
            AND meta_value IS NOT NULL"
        );
    }

    // get ids from posts with external gallery
    function get_posts_with_external_gallery_without_meta() {
        return $this->wpdb->get_results("
            SELECT DISTINCT post_id 
            FROM " . $this->postmeta . " a 
            WHERE (
                a.meta_key LIKE 'fifu_image_url_%'
                OR a.meta_key LIKE 'fifu_video_url_%'
                OR a.meta_key LIKE 'fifu_slider_image_url_%'
            )
            AND a.meta_value IS NOT NULL 
            AND a.meta_value <> ''
            AND NOT EXISTS (
                SELECT 1 
                FROM (SELECT post_id FROM " . $this->postmeta . " WHERE meta_key IN ('_product_image_gallery', '_wc_additional_variation_images')) AS b
                WHERE a.post_id = b.post_id 
            )"
        );
    }

    // get urls from external gallery
    function get_gallery_urls($post_id) {
        return $this->wpdb->get_results("
            SELECT meta_value, meta_key
            FROM " . $this->postmeta . " a
            WHERE a.post_id = " . $post_id . "
            AND (
                a.meta_key LIKE 'fifu_image_url_%'
                OR a.meta_key LIKE 'fifu_video_url_%'
                OR (
                    a.meta_key LIKE 'fifu_slider_image_url_%'
                    AND a.meta_key <> 'fifu_slider_image_url_0' 
                )
            )
            AND a.meta_value <> ''
            ORDER BY meta_key"
        );
    }

    // get alts from external gallery
    function get_gallery_alts($post_id) {
        return $this->wpdb->get_results("
                SELECT meta_value, meta_key
                FROM " . $this->postmeta . " a
                WHERE a.post_id = " . $post_id . "
                AND (
                    a.meta_key LIKE 'fifu_image_alt_%'
                )
                AND a.meta_value <> ''
                ORDER BY meta_key"
        );
    }

    // get urls from slider
    function get_slider_urls($post_id) {
        return $this->wpdb->get_results("
            SELECT meta_value, meta_key
            FROM " . $this->postmeta . " a
            WHERE a.post_id = " . $post_id . "
            AND a.meta_key LIKE 'fifu_slider_image_url_%'
            AND a.meta_value <> ''
            ORDER BY meta_key"
        );
    }

    // delete 1 _product_image_gallery for each post
    function delete_product_image_gallery_by($ids, $is_ctgr) {
        $ctgr_sql = $is_ctgr ? "AND post_name LIKE 'fifu-category%'" : "";

        return $this->wpdb->get_results("
            DELETE FROM " . $this->postmeta . " 
            WHERE post_id IN (" . $ids . ")
            AND meta_key IN ('_product_image_gallery', '_wc_additional_variation_images')
            AND EXISTS (
                SELECT 1 
                FROM " . $this->posts . " 
                WHERE post_parent = post_id 
                AND post_author = " . $this->author . "
                " . $ctgr_sql . " 
            )"
        );
    }

    function delete_product_image_gallery_by_attach_ids($ids, $attach_ids) {
        return $this->wpdb->get_results("
            DELETE FROM " . $this->postmeta . " 
            WHERE post_id IN (" . $ids . ")
            AND meta_key IN ('_product_image_gallery', '_wc_additional_variation_images')
            AND NOT EXISTS (
                SELECT 1 
                FROM " . $this->posts . " 
                WHERE id IN (" . $attach_ids . ")
            )"
        );
    }

    // insert 1 _product_image_gallery for each post
    function insert_product_image_gallery($ids, $is_ctgr) {
        $ctgr_sql = $is_ctgr ? "AND p.post_name LIKE 'fifu-category%'" : "";

        $this->wpdb->get_results("
            INSERT INTO " . $this->postmeta . " (post_id, meta_key, meta_value) (
                SELECT post_parent, '_product_image_gallery', GROUP_CONCAT(id) 
                FROM " . $this->posts . " p 
                WHERE p.post_parent IN (" . $ids . ")
                AND p.id NOT IN (
                    SELECT pm.meta_value 
                    FROM " . $this->postmeta . " pm 
                    WHERE pm.post_id = p.post_parent 
                    AND pm.meta_key = '_thumbnail_id'
                )
                AND p.post_type = 'attachment' 
                AND p.post_author = " . $this->author . " 
                " . $ctgr_sql . " 
                GROUP BY post_parent
            )"
        );
    }

    // insert 1 _wc_additional_variation_images for each post
    function insert_wc_additional_variation_images($ids, $is_ctgr) {
        $ctgr_sql = $is_ctgr ? "AND p.post_name LIKE 'fifu-category%'" : "";

        $this->wpdb->get_results("
            INSERT INTO " . $this->postmeta . " (post_id, meta_key, meta_value) (
                SELECT post_parent, '_wc_additional_variation_images', GROUP_CONCAT(id) 
                FROM " . $this->posts . " p 
                WHERE p.post_parent IN (" . $ids . ")
                AND p.id NOT IN (
                    SELECT pm.meta_value 
                    FROM " . $this->postmeta . " pm 
                    WHERE pm.post_id = p.post_parent 
                    AND pm.meta_key = '_thumbnail_id'
                )
                AND p.post_type = 'attachment' 
                AND p.post_author = " . $this->author . " 
                " . $ctgr_sql . " 
                AND (SELECT post_type FROM " . $this->posts . " WHERE id = p.post_parent) = 'product_variation'
                GROUP BY post_parent
            )"
        );
    }

    // get ids from fake attachments
    function get_fake_attachments() {
        return $this->wpdb->get_results("
            SELECT id 
            FROM " . $this->posts . " 
            WHERE post_type = 'attachment' 
            AND post_author = " . $this->author
        );
    }

    // get ids from attachments with gallery
    function get_attachments_with_gallery() {
        return $this->wpdb->get_results("
            SELECT a.post_id 
            FROM " . $this->postmeta . " a 
            WHERE a.meta_key = '_product_image_gallery' 
            AND EXISTS (
                SELECT 1 
                FROM " . $this->postmeta . " b 
                WHERE a.post_id = b.post_id 
                AND ( 
                	b.meta_key LIKE 'fifu_image_url_%' 
                       OR b.meta_key LIKE 'fifu_video_url_%'
                	OR b.meta_key LIKE 'fifu_slider_image_url_%'
                )
            )"
        );
    }

    // get att_id by post and url
    function get_att_id($post_parent, $url, $is_ctgr) {
        $ctgr_sql = $is_ctgr ? "AND p.post_name LIKE 'fifu-category%'" : "";

        $result = $this->wpdb->get_results("
            SELECT p.id 
            FROM " . $this->posts . " p 
            WHERE p.post_parent = " . $post_parent . "
            AND p.guid = '" . $url . "' 
            AND post_author = " . $this->author . "
            " . $ctgr_sql . " 
            LIMIT 1"
        );
        return $result ? $result[0]->id : null;
    }

    // auto set category image
    function insert_category_images_auto() {
        $this->wpdb->get_results("
            INSERT INTO " . $this->termmeta . " (term_id, meta_key, meta_value) (
                SELECT tm.term_id, 'fifu_image_url', pm.meta_value
                FROM (SELECT DISTINCT term_id FROM " . $this->termmeta . ") tm
                INNER JOIN " . $this->term_taxonomy . " tt ON tm.term_id = tt.term_id AND tt.taxonomy = 'product_cat' AND count > 0 
                INNER JOIN (SELECT term_taxonomy_id, MAX(object_id) AS object_id FROM " . $this->term_relationships . " GROUP BY term_taxonomy_id) rs ON tt.term_taxonomy_id = rs.term_taxonomy_id
                INNER JOIN " . $this->postmeta . " pm ON pm.post_id = rs.object_id and pm.meta_key = 'fifu_image_url' AND pm.meta_value <> ''
                INNER JOIN " . $this->posts . " p ON (p.id = pm.post_id)
                WHERE NOT EXISTS (SELECT 1 FROM " . $this->termmeta . " tm2 WHERE tm2.meta_key = 'fifu_image_url' AND tm2.term_id = tm.term_id)
            )"
        );
    }

    function update_category_images_auto() {
        $result = $this->wpdb->get_results("
            SELECT tm.term_id, pm.meta_value
                FROM (SELECT DISTINCT term_id FROM " . $this->termmeta . ") tm
                INNER JOIN " . $this->term_taxonomy . " tt ON tm.term_id = tt.term_id AND tt.taxonomy = 'product_cat' AND count > 0 
                INNER JOIN (SELECT term_taxonomy_id, MAX(object_id) AS object_id FROM " . $this->term_relationships . " GROUP BY term_taxonomy_id) rs ON tt.term_taxonomy_id = rs.term_taxonomy_id
                INNER JOIN " . $this->postmeta . " pm ON pm.post_id = rs.object_id and pm.meta_key = 'fifu_image_url' AND pm.meta_value <> ''
                INNER JOIN " . $this->posts . " p ON (p.id = pm.post_id)
                WHERE EXISTS (SELECT 1 FROM " . $this->termmeta . " tm2 WHERE tm2.meta_key = 'fifu_image_url' AND tm2.term_id = tm.term_id)"
        );
        foreach ($result as $res) {
            $this->wpdb->update($this->termmeta, array('meta_value' => $res->meta_value), array('term_id' => $res->term_id, 'meta_key' => 'fifu_image_url'), null, null);
            wp_cache_flush();
            $this->ctgr_update_fake_attach_id($res->term_id);
        }
    }

    // get category id given post_id
    function get_category_id($post_id) {
        return $this->wpdb->get_results("
            SELECT tm.term_id
            FROM " . $this->termmeta . " tm
            INNER JOIN " . $this->term_taxonomy . " tt ON tm.term_id = tt.term_id
            INNER JOIN " . $this->term_relationships . " rs ON tt.term_taxonomy_id = rs.term_taxonomy_id
            INNER JOIN " . $this->postmeta . " pm ON pm.post_id = rs.object_id
            WHERE pm.post_id = " . $post_id . "
            AND pm.meta_key = 'fifu_image_url'
            AND pm.meta_key = tm.meta_key
            AND pm.meta_value = tm.meta_value
            AND pm.meta_value <> ''
            AND tt.taxonomy = 'product_cat'"
        );
    }

    function get_child_category() {
        return $this->wpdb->get_results("
            SELECT DISTINCT tt.term_id, tt.parent, tt.count
            FROM " . $this->term_taxonomy . " tt
            INNER JOIN " . $this->termmeta . " tm ON tm.term_id = tt.term_id
            WHERE parent <> 0
            AND taxonomy = 'product_cat'
            ORDER BY count DESC"
        );
    }

    function exists_child_with_attachment($term_id, $parent) {
        return $this->wpdb->get_results("
            SELECT 1 
            FROM " . $this->termmeta . "
            WHERE term_id = " . $term_id . "
            AND meta_key = 'thumbnail_id'
            AND meta_value <> 0
            AND NOT EXISTS (
	            SELECT 1 
                FROM " . $this->termmeta . " tm2
                WHERE tm2.term_id = $parent
                AND tm2.meta_key = 'thumbnail_id'
                AND tm2.meta_value <> 0
            )"
                ) != null;
    }

    function delete_duplicated_category_url() {
        return $this->wpdb->get_results("
            DELETE FROM " . $this->termmeta . "
            WHERE meta_key = 'fifu_image_url'
            AND meta_id NOT IN (
                SELECT * FROM (
                    SELECT MAX(tm.meta_id) AS meta_id
                    FROM " . $this->termmeta . " tm
                    WHERE tm.meta_key = 'fifu_image_url'
                    GROUP BY tm.term_id
                ) aux
            )"
        );
    }

    // get post types without url
    function get_post_types_without_url() {
        return $this->wpdb->get_results("
            SELECT *
            FROM " . $this->posts . " p
            WHERE p.post_type IN ('$this->types')
            AND post_status NOT IN ('auto-draft', 'trash')
            AND NOT EXISTS (
                SELECT 1 
                FROM " . $this->postmeta . " pm 
                WHERE p.id = pm.post_id 
                AND pm.meta_key IN ('fifu_image_url', 'fifu_video_url')
            )
            ORDER BY p.ID"
        );
    }

    // get all post types
    function get_all_post_types() {
        return $this->wpdb->get_results("
            SELECT *
            FROM " . $this->posts . " p
            WHERE p.post_type IN ('" . $this->types . "')
            AND post_status NOT IN ('auto-draft', 'trash')
            ORDER BY p.ID"
        );
    }

    // get posts without dimensions
    function get_posts_without_dimensions() {
        return $this->wpdb->get_results("
            SELECT p.ID, p.guid
            FROM " . $this->posts . " p LEFT OUTER JOIN " . $this->postmeta . " b ON p.id = b.post_id AND meta_key = '_wp_attachment_metadata'
            WHERE b.post_id IS NULL
            AND p.post_type = 'attachment' 
            AND p.post_author = " . $this->author . "
            AND p.post_status NOT IN ('auto-draft', 'trash')
            ORDER BY p.id DESC"
        );
    }

    // get posts without dimensions
    function get_posts_without_dimensions_gallery($ids, $is_ctgr) {
        $ctgr_sql = $is_ctgr ? "AND p.post_name LIKE 'fifu-category%'" : "";

        return $this->wpdb->get_results("
            SELECT p.ID, p.guid
            FROM " . $this->posts . " p
            WHERE p.post_type = 'attachment' 
            AND p.post_author = " . $this->author . " 
            " . $ctgr_sql . " 
            AND p.post_status NOT IN ('auto-draft', 'trash')
            AND p.post_parent IN (" . $ids . ")
            AND NOT EXISTS (
                SELECT 1 
                FROM (SELECT post_id FROM " . $this->postmeta . " WHERE meta_key = '_wp_attachment_metadata') AS b
                WHERE p.id = b.post_id 
            )
            ORDER BY p.id DESC"
        );
    }

    // count images without dimensions
    function get_count_posts_without_dimensions() {
        return $this->wpdb->get_results("
            SELECT COUNT(1) AS amount
            FROM " . $this->posts . " p LEFT OUTER JOIN " . $this->postmeta . " b ON p.id = b.post_id AND meta_key = '_wp_attachment_metadata'
            WHERE b.post_id IS NULL
            AND p.post_type = 'attachment' 
            AND p.post_author = " . $this->author
        );
    }

    // count urls with metadata
    function get_count_urls_with_metadata() {
        return $this->wpdb->get_results("
            SELECT COUNT(1) AS amount
            FROM " . $this->posts . " p
            WHERE p.post_author = " . $this->author . ""
        );
    }

    // count urls
    function get_count_urls() {
        return $this->wpdb->get_results("
            SELECT SUM(id) AS amount
            FROM (
                SELECT count(post_id) AS id
                FROM " . $this->postmeta . " pm
                WHERE pm.meta_key LIKE 'fifu_%'
                AND pm.meta_key LIKE '%url%'
                AND pm.meta_key NOT LIKE '%list%'
                UNION 
                SELECT count(term_id) AS id
                FROM " . $this->termmeta . " tm
                WHERE tm.meta_key LIKE 'fifu_%'
                AND tm.meta_key LIKE '%url%'
            ) x"
        );
    }

    // count urls without metadata
    function get_count_urls_without_metadata() {
        return $this->wpdb->get_results("
            SELECT SUM(amount) AS amount
            FROM (
                SELECT COUNT(1) AS amount
                FROM " . $this->postmeta . " pm
                WHERE pm.meta_key LIKE 'fifu_%'
                AND pm.meta_key LIKE '%url%'
                AND pm.meta_key NOT LIKE '%list%'
                UNION 
                SELECT COUNT(1) AS amount
                FROM " . $this->termmeta . " tm
                WHERE tm.meta_key LIKE 'fifu_%'
                AND tm.meta_key LIKE '%url%'
                UNION
                SELECT -COUNT(1) AS amount
                FROM " . $this->posts . " p
                WHERE p.post_author = " . $this->author . "
            ) x"
        );
    }

    // guid size
    function get_guid_size() {
        return $this->wpdb->get_col_length($this->posts, 'guid')['length'];
    }

    // get last (images/videos/sliders)
    function get_last($meta_key) {
        return $this->wpdb->get_results("
            SELECT p.id, pm.meta_value
            FROM " . $this->posts . " p
            INNER JOIN " . $this->postmeta . " pm ON p.id = pm.post_id
            WHERE pm.meta_key = '" . $meta_key . "'
            ORDER BY p.post_date DESC
            LIMIT 3"
        );
    }

    function get_last_image() {
        return $this->wpdb->get_results("
            SELECT pm.meta_value
            FROM " . $this->postmeta . " pm 
            WHERE pm.meta_key = 'fifu_image_url'
            ORDER BY pm.meta_id DESC
            LIMIT 1"
        );
    }

    // get attachments without post
    function get_attachments_without_post($post_id) {
        $result = $this->wpdb->get_results("
            SELECT GROUP_CONCAT(id) AS ids 
            FROM " . $this->posts . " 
            WHERE post_parent = " . $post_id . " 
            AND post_type = 'attachment' 
            AND post_author = " . $this->author . "
            AND post_name NOT LIKE 'fifu-category%' 
            AND NOT EXISTS (
	            SELECT 1
                FROM " . $this->postmeta . "
                WHERE post_id = post_parent
                AND meta_key = '_thumbnail_id'
                AND meta_value = id
            )
            GROUP BY post_parent"
        );
        return $result ? $result[0]->ids : null;
    }

    function get_ctgr_attachments_without_post($term_id) {
        $result = $this->wpdb->get_results("
            SELECT GROUP_CONCAT(id) AS ids 
            FROM " . $this->posts . " 
            WHERE post_parent = " . $term_id . " 
            AND post_type = 'attachment' 
            AND post_author = " . $this->author . " 
            AND post_name LIKE 'fifu-category%' 
            AND NOT EXISTS (
	            SELECT 1
                FROM " . $this->termmeta . "
                WHERE term_id = post_parent
                AND meta_key = 'thumbnail_id'
                AND meta_value = id
            )
            GROUP BY post_parent"
        );
        return $result ? $result[0]->ids : null;
    }

    function get_posts_without_featured_image($post_types) {
        return $this->wpdb->get_results("
            SELECT id, post_title
            FROM " . $this->posts . " 
            WHERE post_type IN ('$post_types')
            AND post_status = 'publish'
            AND NOT EXISTS (
                SELECT 1
                FROM " . $this->postmeta . " 
                WHERE post_id = id
                AND meta_key IN ('_thumbnail_id', 'fifu_image_url', 'fifu_video_url', 'fifu_slider_image_url_0')
            )
            ORDER BY id DESC"
        );
    }

    function get_post_types_without_featured_image($post_types) {
        $default_attach_id = get_option('fifu_default_attach_id');
        $check_default = $default_attach_id ? "OR (meta_key = '_thumbnail_id' AND meta_value <> {$default_attach_id})" : "OR (meta_key = '_thumbnail_id')";

        return $this->wpdb->get_results("
            (
                SELECT id, post_title, 0 AS searches
                FROM " . $this->posts . " 
                WHERE post_type IN ('$post_types')
                AND post_status = 'publish'
                AND NOT EXISTS (
                    SELECT 1
                    FROM " . $this->postmeta . " 
                    WHERE post_id = id
                    AND (
                        meta_key IN ('fifu_image_url', 'fifu_video_url', 'fifu_slider_image_url_0', 'fifu_search')
                        {$check_default}
                    )
                )
                ORDER BY id DESC
            ) UNION (
                SELECT id, post_title, pm.meta_value AS searches
                FROM " . $this->posts . " p 
                INNER JOIN " . $this->postmeta . " pm ON p.id = pm.post_id AND meta_key = 'fifu_search'
                WHERE post_type IN ('$post_types')
                AND post_status = 'publish'
                AND NOT EXISTS (
                    SELECT 1
                    FROM " . $this->postmeta . " 
                    WHERE post_id = id
                    AND (
                        meta_key IN ('fifu_image_url', 'fifu_video_url', 'fifu_slider_image_url_0')
                        {$check_default}
                    )
                )
                ORDER BY pm.meta_value ASC
            )"
        );
    }

    function get_isbns_without_featured_image() {
        $field = get_option('fifu_isbn_custom_field');
        $keys = $field ? "('fifu_isbn', '${field}')" : "('fifu_isbn')";
        return $this->wpdb->get_results("
            SELECT post_id, meta_value AS isbn
            FROM " . $this->postmeta . " pm
            WHERE pm.meta_key IN " . $keys . "
            AND NOT EXISTS (
                SELECT 1
                FROM " . $this->postmeta . " 
                WHERE post_id = pm.post_id
                AND meta_key IN ('_thumbnail_id', 'fifu_image_url', 'fifu_video_url', 'fifu_slider_image_url_0')
            )
            ORDER BY post_id DESC"
        );
    }

    function get_finders_without_featured_image() {
        $default_attach_id = get_option('fifu_default_attach_id');
        $check_default = $default_attach_id ? "OR (meta_key = '_thumbnail_id' AND meta_value <> {$default_attach_id})" : "OR (meta_key = '_thumbnail_id')";

        $field = get_option('fifu_finder_custom_field');
        $keys = $field ? "('fifu_finder_url', '${field}')" : "('fifu_finder_url')";

        return $this->wpdb->get_results("
            SELECT post_id, meta_value AS webpage_url
            FROM " . $this->postmeta . " pm
            WHERE pm.meta_key IN " . $keys . "
            AND NOT EXISTS (
                SELECT 1
                FROM " . $this->postmeta . " 
                WHERE post_id = pm.post_id
                AND (
                    meta_key IN ('fifu_image_url', 'fifu_video_url', 'fifu_slider_image_url_0') 
                    {$check_default}
                    OR (meta_key = 'fifu_finder_counter' AND meta_value >= 3)
                )
            )
            ORDER BY post_id DESC"
        );
    }

    function get_tags_without_featured_image() {
        return $this->wpdb->get_results("
            SELECT id AS post_id, GROUP_CONCAT(name) AS tags
            FROM " . $this->posts . "             
            INNER JOIN " . $this->term_relationships . " tr ON id = object_id
            INNER JOIN " . $this->term_taxonomy . " tt ON tt.term_taxonomy_id = tr.term_taxonomy_id AND tt.taxonomy IN ('post_tag', 'product_tag')
            INNER JOIN " . $this->terms . " t ON t.term_id = tt.term_id 
            WHERE post_type IN ('$this->types')
            AND post_status = 'publish'
            AND NOT EXISTS (
                SELECT 1
                FROM " . $this->postmeta . " 
                WHERE post_id = id
                AND meta_key IN ('_thumbnail_id', 'fifu_image_url', 'fifu_video_url', 'fifu_slider_image_url_0')
            )
            GROUP BY id
            ORDER BY post_id DESC"
        );
    }

    function get_number_of_posts() {
        return $this->wpdb->get_row("
            SELECT count(1) AS n
            FROM " . $this->posts . " 
            WHERE post_type IN ('$this->types')
            AND post_status = 'publish'"
                )->n;
    }

    function get_category_image_url($term_id) {
        return $this->wpdb->get_results("
            SELECT meta_value 
            FROM " . $this->termmeta . " 
            WHERE meta_key = 'fifu_image_url' 
            AND term_id = " . $term_id
        );
    }

    function get_featured_and_gallery_ids($post_id) {
        return $this->wpdb->get_results("
            SELECT GROUP_CONCAT(meta_value SEPARATOR ',') as 'ids'
            FROM " . $this->postmeta . "
            WHERE post_id = " . $post_id . "
            AND meta_key IN ('_thumbnail_id', '_product_image_gallery')"
        );
    }

    function get_featured_and_gallery_urls($post_id) {
        $this->wpdb->query("SET SESSION group_concat_max_len = 100000;"); // because GROUP_CONCAT is limited to 1024 characters
        return $this->wpdb->get_results("
            SELECT GROUP_CONCAT(meta_value SEPARATOR '|') as 'urls'
            FROM " . $this->postmeta . "
            WHERE post_id = " . $post_id . "
            AND meta_key LIKE 'fifu_image_url%'
            ORDER BY meta_key"
        );
    }

    function get_image_gallery_urls($post_id) {
        return $this->wpdb->get_results("
            SELECT meta_key, meta_value
            FROM " . $this->postmeta . "
            WHERE post_id = " . $post_id . "
            AND meta_key LIKE 'fifu_image_url_%'
            ORDER BY meta_key"
        );
    }

    function delete_featured_and_gallery_urls($post_id) {
        return $this->wpdb->get_results("
            DELETE FROM " . $this->postmeta . "
            WHERE post_id = " . $post_id . "
            AND meta_key LIKE 'fifu_image_url%'"
        );
    }

    function get_variantion_products($post_id) {
        return $this->wpdb->get_results("
            SELECT id, post_title 
            FROM " . $this->posts . "
            WHERE post_parent = " . $post_id . "
            AND post_type = 'product_variation'
            AND post_status <> 'trash'
            ORDER BY menu_order"
        );
    }

    function get_variation_attributes($post_id) {
        return $this->wpdb->get_results("
            SELECT *
            FROM " . $this->postmeta . " pm
            WHERE post_id IN (
	            SELECT id
	            FROM " . $this->posts . " p 
	            WHERE p.post_parent = " . $post_id . "
	            AND p.post_type = 'product_variation'
            )
            AND pm.meta_key LIKE 'attribute_%'"
        );
    }

    function get_variation_att_ids($post_id) {
        return $this->wpdb->get_results("
            SELECT post_id, GROUP_CONCAT(meta_value) AS att_ids
            FROM " . $this->postmeta . " pm
            WHERE post_id IN (
	            SELECT id
	            FROM " . $this->posts . " p 
	            WHERE p.post_parent = " . $post_id . "
	            AND p.post_type = 'product_variation'
            )
            AND pm.meta_key IN ('_thumbnail_id', '_product_image_gallery')
            GROUP BY post_id"
        );
    }

    function insert_default_thumbnail_id($value) {
        $this->wpdb->get_results("
            INSERT INTO " . $this->postmeta . " (post_id, meta_key, meta_value)
            VALUES " . $value
        );
    }

    // update post_content

    function update_post_content($id, $post_content) {
        $this->wpdb->update($this->posts, array('post_content' => $post_content), array('id' => $id), null, null);
    }

    function update_post_content_arr($arr_post) {
        $query = "
            INSERT INTO " . $this->posts . " (id, post_content) VALUES ";
        $count = 0;
        foreach ($arr_post as $post) {
            if ($count++ != 0)
                $query .= ", ";
            $query .= "(" . $post["id"] . ",'" . addslashes($post["content"]) . "') ";
        }
        $query .= "ON DUPLICATE KEY UPDATE post_content=VALUES(post_content)";
        return $this->wpdb->get_results($query);
    }

    // clean metadata

    function delete_thumbnail_ids($ids) {
        $this->wpdb->get_results("
            DELETE FROM " . $this->postmeta . " 
            WHERE meta_key = '_thumbnail_id' 
            AND meta_value IN (" . $ids . ")"
        );
    }

    function delete_thumbnail_ids_category($ids) {
        $this->wpdb->get_results("
            DELETE FROM " . $this->termmeta . " 
            WHERE meta_key = 'thumbnail_id' 
            AND term_id IN (" . $ids . ")"
        );
    }

    function delete_image_url_category($term_id) {
        $this->wpdb->get_results("
            DELETE FROM " . $this->termmeta . " 
            WHERE term_id = " . $term_id . " 
            AND meta_key = 'fifu_image_url'"
        );
    }

    function delete_thumbnail_ids_category_without_attachment() {
        $this->wpdb->get_results("
            DELETE FROM " . $this->termmeta . " 
            WHERE meta_key = 'thumbnail_id' 
            AND NOT EXISTS (
                SELECT 1 
                FROM " . $this->posts . " p 
                WHERE p.id = meta_value
            )"
        );
    }

    function delete_invalid_thumbnail_ids($ids) {
        $this->wpdb->get_results("
            DELETE FROM " . $this->postmeta . " 
            WHERE meta_key = '_thumbnail_id' 
            AND post_id IN (" . $ids . ") 
            AND (
                meta_value = -1 
                OR meta_value IS NULL 
                OR meta_value LIKE 'fifu:%'
            )"
        );
    }

    function delete_fake_thumbnail_id($ids) {
        $att_id = get_option('fifu_fake_attach_id');
        if ($att_id) {
            $this->wpdb->get_results("
                DELETE FROM " . $this->postmeta . " 
                WHERE meta_key = '_thumbnail_id' 
                AND post_id IN (" . $ids . ") 
                AND meta_value = " . $att_id
            );
        }
    }

    function delete_attachments($ids) {
        $this->wpdb->get_results("
            DELETE FROM " . $this->posts . " 
            WHERE id IN (" . $ids . ")
            AND post_type = 'attachment'
            AND post_author = " . $this->author
        );
    }

    function delete_attachment_meta_url_and_alt($ids) {
        $this->wpdb->get_results("
            DELETE FROM " . $this->postmeta . " 
            WHERE meta_key IN ('_wp_attached_file', '_wp_attachment_image_alt', '_wp_attachment_metadata', 'fifu_yt_res', 'fifu_embed_url')
            AND post_id IN (" . $ids . ")
            AND EXISTS (
                SELECT 1 
                FROM " . $this->posts . " 
                WHERE id = post_id 
                AND post_author = " . $this->author . "
            )"
        );
    }

    function delete_attachment_meta_url($ids) {
        $this->wpdb->get_results("
            DELETE FROM " . $this->postmeta . " 
            WHERE meta_key = '_wp_attached_file' 
            AND post_id IN (" . $ids . ")"
        );
    }

    function delete_thumbnail_id_without_attachment() {
        if (fifu_is_multisite_global_media_active()) {
            $this->wpdb->get_results("
                DELETE FROM " . $this->postmeta . " 
                WHERE meta_key = '_thumbnail_id' 
                AND meta_value NOT LIKE '100000%' 
                AND NOT EXISTS (
                    SELECT 1 
                    FROM " . $this->posts . " p 
                    WHERE p.id = meta_value
                )"
            );
            return;
        }

        $this->wpdb->get_results("
            DELETE FROM " . $this->postmeta . " 
            WHERE meta_key = '_thumbnail_id' 
            AND NOT EXISTS (
                SELECT 1 
                FROM " . $this->posts . " p 
                WHERE p.id = meta_value
            )"
        );
    }

    function delete_attachment_meta_without_attachment() {
        $this->wpdb->get_results("
            DELETE FROM " . $this->postmeta . " 
            WHERE meta_key IN ('_wp_attached_file', '_wp_attachment_image_alt', '_wp_attachment_metadata', 'fifu_yt_res', 'fifu_embed_url') 
            AND NOT EXISTS (
                SELECT 1
                FROM " . $this->posts . " p 
                WHERE p.id = post_id
            )"
        );
    }

    function delete_product_image_gallery($ids) {
        $this->wpdb->get_results("
            DELETE FROM " . $this->postmeta . "
            WHERE meta_key IN ('_product_image_gallery', '_wc_additional_variation_images')
            AND post_id IN (" . $ids . ")"
        );
    }

    function delete_empty_urls_category() {
        $this->wpdb->get_results("
            DELETE FROM " . $this->termmeta . " 
            WHERE meta_key = 'fifu_image_url'
            AND (
                meta_value = ''
                OR meta_value is NULL
            )"
        );
    }

    function delete_empty_urls() {
        $this->wpdb->get_results("
            DELETE FROM " . $this->postmeta . " 
            WHERE meta_key = 'fifu_image_url'
            AND (
                meta_value = ''
                OR meta_value is NULL
            )"
        );
    }

    function delete_metadata() {
        $fake_attach_id = get_option('fifu_fake_attach_id');
        $default_attach_id = get_option('fifu_default_attach_id');
        $value = '-1';
        $value = $fake_attach_id ? $value . ',' . $fake_attach_id : $value;
        $value = $default_attach_id ? $value . ',' . $default_attach_id : $value;
        $this->wpdb->get_results("
            DELETE FROM " . $this->postmeta . " 
            WHERE meta_key IN ('_thumbnail_id', '_product_image_gallery', '_wc_additional_variation_images')
            AND meta_value IN (" . $value . ")"
        );
        $this->wpdb->get_results("
            DELETE FROM " . $this->postmeta . " 
            WHERE meta_key IN ('fifu_image_dimension', 'fifu_position')"
        );
    }

    /* speed up */

    function get_all_urls($page) {
        $start = $page * 1000;

        $sql = "
            (
                SELECT pm.meta_id, pm.post_id, pm.meta_value AS url, pm.meta_key, p.post_name, p.post_title, p.post_date, false AS category, null AS video_url
                FROM " . $this->postmeta . " pm
                INNER JOIN " . $this->posts . " p ON pm.post_id = p.id
                WHERE (pm.meta_key LIKE 'fifu_%image_url%' OR pm.meta_key LIKE 'fifu_video_url%')
                AND pm.meta_value NOT LIKE '%https://cdn.fifu.app/%'
                AND pm.meta_value NOT LIKE 'http://localhost/%'
                AND p.post_status <> 'trash'
            )
        ";
        if (class_exists('WooCommerce')) {
            $sql .= " 
                UNION
                (
                    SELECT tm.meta_id, tm.term_id AS post_id, tm.meta_value AS url, tm.meta_key, null AS post_name, t.name AS post_title, null AS post_date, true AS category, null AS video_url
                    FROM " . $this->termmeta . " tm
                    INNER JOIN " . $this->terms . " t ON tm.term_id = t.term_id
                    WHERE tm.meta_key IN ('fifu_image_url', 'fifu_video_url')
                    AND tm.meta_value NOT LIKE '%https://cdn.fifu.app/%'
                    AND tm.meta_value NOT LIKE 'http://localhost/%'
                )
            ";
        }
        $sql .= " 
            ORDER BY post_id DESC
            LIMIT {$start},1000
        ";
        return $this->wpdb->get_results($sql);
    }

    function get_all_internal_urls() {
        return $this->wpdb->get_results("
            SELECT pm.meta_id, pm.post_id, att.guid AS url, pm.meta_key, p.post_name, p.post_title, p.post_date
            FROM " . $this->postmeta . " pm
            INNER JOIN " . $this->posts . " p ON pm.post_id = p.id
            INNER JOIN " . $this->posts . " att ON (
                (
                    pm.meta_key = '_thumbnail_id'
                    AND pm.meta_value = att.id
                ) 
                OR 
                (
                    pm.meta_key = '_product_image_gallery'
                    AND FIND_IN_SET(att.id, pm.meta_value) 
                )
            )            
            WHERE NOT EXISTS (
                SELECT 1
                FROM " . $this->postmeta . "
                WHERE post_id = pm.post_id
                AND meta_key LIKE 'fifu_%image_url%'
            )
            AND p.post_status <> 'trash'
            ORDER BY pm.post_id DESC"
        );
    }

    function get_posts_with_internal_featured_image($page) {
        $start = $page * 1000;

        $sql = "
            (
                SELECT 
                    pm.post_id, 
                    att.guid AS url, 
                    p.post_name, 
                    p.post_title, 
                    p.post_date, 
                    att.id AS thumbnail_id,
                    (SELECT meta_value FROM " . $this->postmeta . " pm2 WHERE pm2.post_id = pm.post_id AND pm2.meta_key = '_product_image_gallery') AS gallery_ids,
                    false AS category
                FROM " . $this->postmeta . " pm
                INNER JOIN " . $this->posts . " p ON pm.post_id = p.id
                INNER JOIN " . $this->posts . " att ON (
                    pm.meta_key = '_thumbnail_id'
                    AND pm.meta_value = att.id
                    AND att.post_author <> " . $this->author . "
                )
                WHERE NOT EXISTS (
                    SELECT 1
                    FROM " . $this->postmeta . "
                    WHERE post_id = pm.post_id
                    AND (meta_key LIKE 'fifu_%image_url%' OR meta_key IN ('bkp_thumbnail_id', 'bkp_product_image_gallery'))
                )
                AND (
                    SELECT COUNT(1)
                    FROM " . $this->postmeta . "
                    WHERE post_id = pm.post_id
                    AND meta_key = '_product_image_gallery'
                ) <= 1
                AND p.post_status <> 'trash'
            )
        ";
        if (class_exists('WooCommerce')) {
            $sql .= " 
                UNION 
                (
                    SELECT
                        tm.term_id AS post_id, 
                        att.guid AS url, 
                        null AS post_name, 
                        t.name AS post_title, 
                        null AS post_date, 
                        att.id AS thumbnail_id,
                        null AS gallery_ids,
                        true AS category
                    FROM " . $this->termmeta . " tm
                    INNER JOIN " . $this->terms . " t ON tm.term_id = t.term_id
                    INNER JOIN " . $this->posts . " att ON (
                        tm.meta_key = 'thumbnail_id'
                        AND tm.meta_value = att.id
                        AND att.post_author <> " . $this->author . "
                    )
                    WHERE NOT EXISTS (
                        SELECT 1
                        FROM " . $this->termmeta . "
                        WHERE term_id = tm.term_id
                        AND (meta_key = 'fifu_image_url' OR meta_key = 'bkp_thumbnail_id')
                    )
                )
            ";
        }
        $sql .= " 
            ORDER BY post_id DESC
            LIMIT {$start},1000
        ";
        return $this->wpdb->get_results($sql);
    }

    function get_posts_su($storage_ids) {
        if ($storage_ids) {
            $storage_ids = '"' . implode('","', $storage_ids) . '"';
            $filter_post_image = "AND SUBSTRING_INDEX(SUBSTRING_INDEX(pm.meta_value, '/', 5), '/', -1) IN ({$storage_ids})";
            $filter_term_image = "AND SUBSTRING_INDEX(SUBSTRING_INDEX(tm.meta_value, '/', 5), '/', -1) IN ({$storage_ids})";
            $filter_post_video = "AND SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX(pm.meta_value, 'fifu-thumb=', 5), 'fifu-thumb=', -1), '/', 5), '/', -1) IN ({$storage_ids})";
            $filter_term_video = "AND SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX(tm.meta_value, 'fifu-thumb=', 5), 'fifu-thumb=', -1), '/', 5), '/', -1) IN ({$storage_ids})";
        } else
            $filter_post_image = $filter_term_image = $filter_post_video = $filter_term_video = "";

        $sql = "
            (
                SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(pm.meta_value, '/', 5), '/', -1) AS storage_id, 
                    p.post_title, 
                    p.post_date, 
                    pm.meta_id, 
                    pm.post_id, 
                    pm.meta_key, 
                    false AS category
                FROM " . $this->postmeta . " pm
                INNER JOIN " . $this->posts . " p ON pm.post_id = p.id
                WHERE pm.meta_key LIKE 'fifu_%image_url%'
                AND pm.meta_value LIKE 'https://cdn.fifu.app/%'" .
                $filter_post_image . "
            )
            UNION
            (
                SELECT 
                    SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX(pm.meta_value, 'fifu-thumb=', 5), 'fifu-thumb=', -1), '/', 5), '/', -1) AS storage_id, 
                    p.post_title, 
                    p.post_date, 
                    pm.meta_id, 
                    pm.post_id, 
                    pm.meta_key, 
                    false AS category
                FROM " . $this->postmeta . " pm
                INNER JOIN " . $this->posts . " p ON pm.post_id = p.id
                WHERE pm.meta_key LIKE 'fifu_video_url%'
                AND pm.meta_value LIKE '%https://cdn.fifu.app/%'" .
                $filter_post_video . "
            )
        ";
        if (class_exists('WooCommerce')) {
            $sql .= "            
                UNION
                (
                    SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(tm.meta_value, '/', 5), '/', -1) AS storage_id, 
                        t.name AS post_title, 
                        null AS post_date, 
                        tm.meta_id, 
                        tm.term_id AS post_id, 
                        tm.meta_key, 
                        true AS category
                    FROM " . $this->termmeta . " tm
                    INNER JOIN " . $this->terms . " t ON tm.term_id = t.term_id
                    WHERE tm.meta_key = 'fifu_image_url'
                    AND tm.meta_value LIKE 'https://cdn.fifu.app/%'" .
                    $filter_term_image . "
                )
                UNION
                (
                    SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX(tm.meta_value, 'fifu-thumb=', 5), 'fifu-thumb=', -1), '/', 5), '/', -1) AS storage_id,
                        t.name AS post_title, 
                        null AS post_date, 
                        tm.meta_id, 
                        tm.term_id AS post_id, 
                        tm.meta_key, 
                        true AS category
                    FROM " . $this->termmeta . " tm
                    INNER JOIN " . $this->terms . " t ON tm.term_id = t.term_id
                    WHERE tm.meta_key = 'fifu_video_url'
                    AND tm.meta_value LIKE '%https://cdn.fifu.app/%'" .
                    $filter_term_video . "
                )
            ";
        }
        return $this->wpdb->get_results($sql);
    }

    /* speed up (add) */

    function add_urls_su($bucket_id, $thumbnails) {
        // custom field
        $this->speed_up_custom_fields($bucket_id, $thumbnails, false);

        // two groups
        $featured_list = array();
        $gallery_list = array();
        foreach ($thumbnails as $thumbnail) {
            if ($thumbnail->meta_key == 'fifu_image_url' || $thumbnail->meta_key == 'fifu_slider_image_url_0' || $thumbnail->meta_key == 'fifu_video_url')
                array_push($featured_list, $thumbnail);
            else
                array_push($gallery_list, $thumbnail);
        }

        // featured group
        if (count($featured_list) > 0) {
            $att_ids_map = $this->get_thumbnail_ids($featured_list, false);
            if (count($att_ids_map) > 0) {
                $this->speed_up_attachments($bucket_id, $featured_list, $att_ids_map);
                $meta_ids_map = $this->get_thumbnail_meta_ids($featured_list, $att_ids_map);
                if (count($meta_ids_map) > 0)
                    $this->speed_up_attachments_meta($bucket_id, $featured_list, $meta_ids_map);
            }
        }

        // gallery group
        if (count($gallery_list) > 0) {
            $att_ids_map = $this->get_thumbnail_ids_gallery($gallery_list, false);
            if (count($att_ids_map) > 0) {
                $this->speed_up_attachments($bucket_id, $gallery_list, $att_ids_map);
                $meta_ids_map = $this->get_thumbnail_meta_ids($gallery_list, $att_ids_map);
                if (count($meta_ids_map) > 0)
                    $this->speed_up_attachments_meta($bucket_id, $gallery_list, $meta_ids_map);
            }
        }

        // lists
        $list_ids_map = $this->get_list_ids($thumbnails, $bucket_id, false, null);
        $this->speed_up_list_custom_fields($bucket_id, $thumbnails, $list_ids_map);
    }

    function ctgr_add_urls_su($bucket_id, $thumbnails) {
        // custom field
        $this->speed_up_custom_fields($bucket_id, $thumbnails, true);

        $featured_list = array();
        foreach ($thumbnails as $thumbnail)
            array_push($featured_list, $thumbnail);

        // featured group
        if (count($featured_list) > 0) {
            $att_ids_map = $this->get_thumbnail_ids($featured_list, true);
            if (count($att_ids_map) > 0) {
                $this->speed_up_attachments($bucket_id, $featured_list, $att_ids_map);
                $meta_ids_map = $this->get_thumbnail_meta_ids($featured_list, $att_ids_map);
                if (count($meta_ids_map) > 0)
                    $this->speed_up_attachments_meta($bucket_id, $featured_list, $meta_ids_map);
            }
        }
    }

    function get_su_url($bucket_id, $storage_id) {
        return 'https://cdn.fifu.app/' . $bucket_id . '/' . $storage_id;
    }

    function speed_up_custom_fields($bucket_id, $thumbnails, $is_ctgr) {
        $table = $is_ctgr ? $this->termmeta : $this->postmeta;

        $query = "
            INSERT INTO " . $table . " (meta_id, meta_value) VALUES ";
        $count = 0;
        foreach ($thumbnails as $thumbnail) {
            $su_url = $this->get_su_url($bucket_id, $thumbnail->storage_id);

            if ($thumbnail->video_url && strpos($thumbnail->video_url, 'fifu-thumb=') === false) {
                $qp = parse_url($thumbnail->video_url, PHP_URL_QUERY);
                $del = $qp ? '&' : '?';
                $su_url = rtrim($thumbnail->video_url, ' &?\n\r\t\v\0') . $del . 'fifu-thumb=' . $su_url;
            }

            if ($count++ != 0)
                $query .= ", ";
            $query .= "(" . $thumbnail->meta_id . ",'" . $su_url . "') ";
        }
        $query .= "ON DUPLICATE KEY UPDATE meta_value=VALUES(meta_value)";
        return $this->wpdb->get_results($query);
    }

    function speed_up_list_custom_fields($bucket_id, $thumbnails, $list_ids_map) {
        $map1_image = $map2_image = array();
        $map1_video = $map2_video = array();
        foreach ($thumbnails as $thumbnail) {
            if (!isset($list_ids_map[$thumbnail->meta_id]))
                continue;

            $su_url = $this->get_su_url($bucket_id, $thumbnail->storage_id);

            if ($thumbnail->video_url && strpos($thumbnail->video_url, 'fifu-thumb=') === false) {
                $qp = parse_url($thumbnail->video_url, PHP_URL_QUERY);
                $del = $qp ? '&' : '?';
                $su_url = rtrim($thumbnail->video_url, ' &?\n\r\t\v\0') . $del . 'fifu-thumb=' . $su_url;
                $url = $thumbnail->video_url;
                $map1_video[$thumbnail->post_id] = str_replace($url, $su_url, isset($map1_video[$thumbnail->post_id]) ? $map1_video[$thumbnail->post_id] : $list_ids_map[$thumbnail->meta_id][1]);
                $map2_video[$list_ids_map[$thumbnail->meta_id][0]] = $thumbnail->post_id;
            } else {
                $url = $thumbnail->meta_value;
                $map1_image[$thumbnail->post_id] = str_replace($url, $su_url, isset($map1_image[$thumbnail->post_id]) ? $map1_image[$thumbnail->post_id] : $list_ids_map[$thumbnail->meta_id][1]);
                $map2_image[$list_ids_map[$thumbnail->meta_id][0]] = $thumbnail->post_id;
            }
        }

        if (!empty($map1_image)) {
            $query = "
                INSERT INTO " . $this->postmeta . " (meta_id, meta_value) VALUES ";
            $count = 0;
            foreach ($map2_image as $key => $value) {
                if ($count++ != 0)
                    $query .= ", ";
                $query .= "(" . $key . ",'" . $map1_image[$value] . "') ";
            }
            $query .= "ON DUPLICATE KEY UPDATE meta_value=VALUES(meta_value)";
            $this->wpdb->get_results($query);
        }

        if (!empty($map1_video)) {
            $query = "
                INSERT INTO " . $this->postmeta . " (meta_id, meta_value) VALUES ";
            $count = 0;
            foreach ($map2_video as $key => $value) {
                if ($count++ != 0)
                    $query .= ", ";
                $query .= "(" . $key . ",'" . $map1_video[$value] . "') ";
            }
            $query .= "ON DUPLICATE KEY UPDATE meta_value=VALUES(meta_value)";
            $this->wpdb->get_results($query);
        }
    }

    function get_thumbnail_ids($thumbnails, $is_ctgr) {
        // join post_ids
        $i = 0;
        $ids = null;
        foreach ($thumbnails as $thumbnail)
            $ids = ($i++ == 0) ? $thumbnail->post_id : ($ids . "," . $thumbnail->post_id);

        // get featured ids
        if ($is_ctgr) {
            $result = $this->wpdb->get_results("
                SELECT term_id AS post_id, meta_value AS att_id
                FROM " . $this->termmeta . " 
                WHERE term_id IN (" . $ids . ") 
                AND meta_key = 'thumbnail_id'"
            );
        } else {
            $result = $this->wpdb->get_results("
                SELECT post_id, meta_value AS att_id
                FROM " . $this->postmeta . " 
                WHERE post_id IN (" . $ids . ") 
                AND meta_key = '_thumbnail_id'"
            );
        }

        // map featured ids
        $featured_map = array();
        foreach ($result as $res)
            $featured_map[$res->post_id] = $res->att_id;

        // map thumbnails
        $map = array();
        foreach ($thumbnails as $thumbnail) {
            if (isset($featured_map[$thumbnail->post_id])) {
                $att_id = $featured_map[$thumbnail->post_id];
                $map[$thumbnail->meta_id] = $att_id;
            }
        }
        // meta_id -> att_id
        return $map;
    }

    function get_thumbnail_ids_gallery($thumbnails, $is_delete) {
        // join post_ids
        $i = 0;
        $ids = null;
        foreach ($thumbnails as $thumbnail)
            $ids = ($i++ == 0) ? $thumbnail->post_id : ($ids . "," . $thumbnail->post_id);

        // get gallery ids
        $result = $this->wpdb->get_results("
            SELECT post_id, meta_key, meta_value AS att_ids
            FROM " . $this->postmeta . " 
            WHERE post_id IN (" . $ids . ") 
            AND meta_key IN ('_product_image_gallery', '_wc_additional_variation_images')"
        );

        // map gallery ids
        $gallery_map = array();
        foreach ($result as $res)
            $gallery_map[$res->post_id] = $res->att_ids;

        // map thumbnails
        $map = array();
        $done = array(); // for duplicated URLs
        foreach ($thumbnails as $thumbnail) {
            if (!isset($gallery_map[$thumbnail->post_id])) // no metadata, only custom field
                continue;
            $att_ids = $gallery_map[$thumbnail->post_id];

            if ($is_delete) {
                $result = $this->wpdb->get_results("
                    SELECT id, SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX(guid, '/', 5), '/', -1), '?', 1) AS storage_id
                    FROM " . $this->posts . " 
                    WHERE id IN (" . $att_ids . ")"
                );
                $storage_ids = array();
                foreach ($result as $res)
                    $storage_ids[$res->storage_id] = $res->id;
                $att_id = $storage_ids[$thumbnail->storage_id];
            } else {
                $result = $this->wpdb->get_results("
                    SELECT id, guid
                    FROM " . $this->posts . " 
                    WHERE id IN (" . $att_ids . ")"
                );
                $guids = array();
                foreach ($result as $res) {
                    if (!isset($done[$res->id]))
                        $guids[$res->guid] = $res->id;
                }
                $att_id = $guids[$thumbnail->meta_value];
                $done[$att_id] = true;
            }
            $map[$thumbnail->meta_id] = $att_id;
        }
        return $map;
    }

    function speed_up_attachments($bucket_id, $thumbnails, $att_ids_map) {
        $count = 0;
        $query = "
            INSERT INTO " . $this->posts . " (id, guid) VALUES ";
        foreach ($thumbnails as $thumbnail) {
            if (!isset($att_ids_map[$thumbnail->meta_id])) // no metadata, only custom field
                continue;

            $su_url = $this->get_su_url($bucket_id, $thumbnail->storage_id);

            if ($thumbnail->video_url && strpos($thumbnail->video_url, 'video-thumb=') === false)
                $su_url .= '?video-thumb=' . fifu_video_img_small($thumbnail->video_url);

            if ($count++ != 0)
                $query .= ", ";
            $query .= "(" . $att_ids_map[$thumbnail->meta_id] . ",'" . $su_url . "') ";
        }
        $query .= "ON DUPLICATE KEY UPDATE guid=VALUES(guid)";
        return $this->wpdb->get_results($query);
    }

    function get_thumbnail_meta_ids($thumbnails, $att_ids_map) {
        // join post_ids
        $i = 0;
        $ids = null;
        foreach ($thumbnails as $thumbnail) {
            if (!isset($att_ids_map[$thumbnail->meta_id])) // no metadata, only custom field
                continue;
            $ids = ($i++ == 0) ? $att_ids_map[$thumbnail->meta_id] : ($ids . "," . $att_ids_map[$thumbnail->meta_id]);
        }

        // get meta ids
        $result = $this->wpdb->get_results("
            SELECT meta_id, post_id
            FROM " . $this->postmeta . " 
            WHERE post_id IN (" . $ids . ") 
            AND meta_key = '_wp_attached_file'"
        );

        // map att_id -> meta_id
        $attid_metaid_map = array();
        foreach ($result as $res)
            $attid_metaid_map[$res->post_id] = $res->meta_id;

        // map meta_id (fifu metadata) -> meta_id (atachment metadata)
        $map = array();
        foreach ($thumbnails as $thumbnail) {
            if (!isset($att_ids_map[$thumbnail->meta_id])) // no metadata, only custom field
                continue;
            $att_meta_id = $attid_metaid_map[$att_ids_map[$thumbnail->meta_id]];
            $map[$thumbnail->meta_id] = $att_meta_id;
        }
        return $map;
    }

    function speed_up_attachments_meta($bucket_id, $thumbnails, $meta_ids_map) {
        $count = 0;
        $query = "
            INSERT INTO " . $this->postmeta . " (meta_id, meta_value) VALUES ";
        foreach ($thumbnails as $thumbnail) {
            if (!isset($meta_ids_map[$thumbnail->meta_id])) // no metadata, only custom field
                continue;

            $su_url = $this->get_su_url($bucket_id, $thumbnail->storage_id);

            if ($thumbnail->video_url && strpos($thumbnail->video_url, 'video-thumb=') === false)
                $su_url .= '?video-thumb=' . fifu_video_img_small($thumbnail->video_url);

            if ($count++ != 0)
                $query .= ", ";
            $query .= "(" . $meta_ids_map[$thumbnail->meta_id] . ",'" . $su_url . "') ";
        }
        $query .= "ON DUPLICATE KEY UPDATE meta_value=VALUES(meta_value)";
        return $this->wpdb->get_results($query);
    }

    function get_list_ids($thumbnails, $bucket_id, $is_delete, $video_urls) {
        // join post_ids
        $i_normal = 0;
        $i_slider = 0;
        $post_ids_normal = null;
        $post_ids_slider = null;
        foreach ($thumbnails as $thumbnail) {
            $is_slider = strpos($thumbnail->meta_key, 'slider') !== false;
            if ($is_slider)
                $post_ids_slider = ($i_slider++ == 0) ? $thumbnail->post_id : ($post_ids_slider . "," . $thumbnail->post_id);
            else
                $post_ids_normal = ($i_normal++ == 0) ? $thumbnail->post_id : ($post_ids_normal . "," . $thumbnail->post_id);
        }

        // get slider ids
        if ($post_ids_slider) {
            $result_slider = $this->wpdb->get_results("
                SELECT meta_id, post_id, meta_value
                FROM " . $this->postmeta . " 
                WHERE post_id IN (" . $post_ids_slider . ") 
                AND meta_key = 'fifu_slider_list_url'"
            );
        } else
            $result_slider = array();

        // get normal ids
        if ($post_ids_normal) {
            $result_normal = $this->wpdb->get_results("
                SELECT meta_id, post_id, meta_value
                FROM " . $this->postmeta . " 
                WHERE post_id IN (" . $post_ids_normal . ") 
                AND meta_key IN ('fifu_list_url', 'fifu_list_video_url')"
            );
        } else
            $result_normal = array();

        // map slider ids
        $slider_map = array();
        foreach ($result_slider as $res)
            $slider_map[$res->post_id] = array($res->meta_id, $res->meta_value);

        // map normal ids (post_id: array(array(meta_id, meta_value), arr...)
        // an array for images, another one for videos
        $normal_map = array();
        foreach ($result_normal as $res) {
            if (!isset($normal_map[$res->post_id]))
                $normal_map[$res->post_id] = array();
            array_push($normal_map[$res->post_id], array($res->meta_id, $res->meta_value));
        }

        // map thumbnails
        $map = array();
        foreach ($thumbnails as $thumbnail) {
            $arr = null;
            $is_slider = strpos($thumbnail->meta_key, 'slider') !== false;
            if ($is_slider) {
                if (isset($slider_map[$thumbnail->post_id]))
                    $arr = $slider_map[$thumbnail->post_id];
            } else {
                if (isset($normal_map[$thumbnail->post_id])) {
                    foreach ($normal_map[$thumbnail->post_id] as $list) {
                        if ($is_delete) {
                            $image_url = $this->get_su_url($bucket_id, $thumbnail->storage_id);
                            $video_url = $video_urls && isset($video_urls[$thumbnail->storage_id]) ? $video_urls[$thumbnail->storage_id] : null;
                        } else {
                            $image_url = $thumbnail->meta_value;
                            $video_url = $thumbnail->video_url;
                        }
                        $url = $video_url ? $video_url : $image_url;
                        if (strpos($list[1], $url) !== false) {
                            $arr = $list;
                            break;
                        }
                    }
                }
            }
            if ($arr)
                $map[$thumbnail->meta_id] = $arr;
        }
        return $map;
    }

    /* speed up (remove) */

    function remove_urls_su($bucket_id, $thumbnails, $urls, $video_urls) {
        foreach ($thumbnails as $thumbnail) {
            // post removed
            if (!$thumbnail->meta_id)
                unset($urls[$thumbnail->storage_id]);
        }

        if (empty($urls))
            return;

        // custom field
        $this->revert_custom_fields($thumbnails, $urls, $video_urls, false);

        // two groups
        $featured_list = array();
        $gallery_list = array();
        foreach ($thumbnails as $thumbnail) {
            if ($thumbnail->meta_key == 'fifu_image_url' || $thumbnail->meta_key == 'fifu_slider_image_url_0' || $thumbnail->meta_key == 'fifu_video_url')
                array_push($featured_list, $thumbnail);
            else
                array_push($gallery_list, $thumbnail);
        }

        // featured group
        if (count($featured_list) > 0) {
            $att_ids_map = $this->get_thumbnail_ids($featured_list, false);
            if (count($att_ids_map) > 0) {
                $this->revert_attachments($urls, $featured_list, $att_ids_map);
                $meta_ids_map = $this->get_thumbnail_meta_ids($featured_list, $att_ids_map);
                if (count($meta_ids_map) > 0)
                    $this->revert_attachments_meta($urls, $featured_list, $meta_ids_map);
            }
        }

        // gallery group
        if (count($gallery_list) > 0) {
            $att_ids_map = $this->get_thumbnail_ids_gallery($gallery_list, true);
            if (count($att_ids_map) > 0) {
                $this->revert_attachments($urls, $gallery_list, $att_ids_map);
                $meta_ids_map = $this->get_thumbnail_meta_ids($gallery_list, $att_ids_map);
                if (count($meta_ids_map) > 0)
                    $this->revert_attachments_meta($urls, $gallery_list, $meta_ids_map);
            }
        }

        // lists
        $list_ids_map = $this->get_list_ids($thumbnails, $bucket_id, true, $video_urls);
        $this->revert_list_custom_fields($bucket_id, $urls, $video_urls, $thumbnails, $list_ids_map);
    }

    function ctgr_remove_urls_su($bucket_id, $thumbnails, $urls, $video_urls) {
        foreach ($thumbnails as $thumbnail) {
            // post removed
            if (!$thumbnail->meta_id)
                unset($urls[$thumbnail->storage_id]);
        }

        if (empty($urls))
            return;

        // custom field
        $this->revert_custom_fields($thumbnails, $urls, $video_urls, true);

        $featured_list = array();
        foreach ($thumbnails as $thumbnail)
            array_push($featured_list, $thumbnail);

        // featured group
        if (count($featured_list) > 0) {
            $att_ids_map = $this->get_thumbnail_ids($featured_list, true);
            if (count($att_ids_map) > 0) {
                $this->revert_attachments($urls, $featured_list, $att_ids_map);
                $meta_ids_map = $this->get_thumbnail_meta_ids($featured_list, $att_ids_map);
                if (count($meta_ids_map) > 0)
                    $this->revert_attachments_meta($urls, $featured_list, $meta_ids_map);
            }
        }
    }

    /* speed up (backup att ids) */

    function backup_att_ids($post_ids) {
        $this->wpdb->get_results("
            INSERT INTO " . $this->postmeta . " (post_id, meta_key, meta_value) ( 
                SELECT pm.post_id, CONCAT('bkp', pm.meta_key) AS meta_key, pm.meta_value 
                FROM " . $this->postmeta . " pm
                WHERE pm.post_id IN (" . implode(',', $post_ids) . ")
                AND pm.meta_key IN ('_thumbnail_id', '_product_image_gallery')
                AND NOT EXISTS (
                    SELECT 1
                    FROM " . $this->postmeta . " pm2
                    WHERE pm2.post_id = pm.post_id 
                    AND pm2.meta_key IN ('bkp_thumbnail_id', 'bkp_product_image_gallery')
                )
            )"
        );
    }

    function ctgr_backup_att_ids($term_ids) {
        $this->wpdb->get_results("
            INSERT INTO " . $this->termmeta . " (term_id, meta_key, meta_value) ( 
                SELECT tm.term_id, CONCAT('bkp_', tm.meta_key) AS meta_key, tm.meta_value 
                FROM " . $this->termmeta . " tm
                WHERE tm.term_id IN (" . implode(',', $term_ids) . ")
                AND tm.meta_key = 'thumbnail_id'
                AND NOT EXISTS (
                    SELECT 1
                    FROM " . $this->termmeta . " tm2
                    WHERE tm2.term_id = tm.term_id 
                    AND tm2.meta_key = 'bkp_thumbnail_id'
                )
            )"
        );
    }

    /* speed up (delete att ids) */

    function delete_att_ids($post_ids) {
        $this->wpdb->get_results("
            DELETE FROM " . $this->postmeta . " pm
            WHERE pm.post_id IN (" . implode(',', $post_ids) . ")
            AND pm.meta_key IN ('_thumbnail_id', '_product_image_gallery')"
        );
    }

    function ctgr_delete_att_ids($term_ids) {
        $this->wpdb->get_results("
            DELETE FROM " . $this->termmeta . " tm
            WHERE tm.term_id IN (" . implode(',', $term_ids) . ")
            AND tm.meta_key = 'thumbnail_id'"
        );
    }

    /* speed up (add custom fields) */

    function add_custom_fields($values) {
        $query = "
            INSERT INTO " . $this->postmeta . " (post_id, meta_key, meta_value) VALUES " . $values;
        return $this->wpdb->get_results($query);
    }

    function ctgr_add_custom_fields($values) {
        $query = "
            INSERT INTO " . $this->termmeta . " (term_id, meta_key, meta_value) VALUES " . $values;
        return $this->wpdb->get_results($query);
    }

    function get_internal_urls($post_ids) {
        return $this->wpdb->get_results("
            SELECT p.id AS att_id, p.guid AS url
            FROM " . $this->posts . " p
            WHERE FIND_IN_SET(p.id, 
                (
                    SELECT GROUP_CONCAT(pm.meta_value) AS att_ids
                    FROM " . $this->postmeta . " pm
                    WHERE pm.post_id IN (" . implode(',', $post_ids) . ")
                    AND meta_key IN ('bkp_thumbnail_id', 'bkp_product_image_gallery')
                )
            )"
        );
    }

    function get_ctgr_internal_urls($term_ids) {
        return $this->wpdb->get_results("
            SELECT p.id AS att_id, p.guid AS url
            FROM " . $this->posts . " p
            WHERE FIND_IN_SET(p.id, 
                (
                    SELECT GROUP_CONCAT(tm.meta_value) AS att_ids
                    FROM " . $this->termmeta . " tm
                    WHERE tm.term_id IN (" . implode(',', $term_ids) . ")
                    AND meta_key = 'bkp_thumbnail_id'
                )
            )"
        );
    }

    function revert_custom_fields($thumbnails, $urls, $video_urls, $is_ctgr) {
        $table = $is_ctgr ? $this->termmeta : $this->postmeta;

        $query = "
            INSERT INTO " . $table . " (meta_id, meta_value) VALUES ";
        $count = 0;
        foreach ($thumbnails as $thumbnail) {
            if ($count++ != 0)
                $query .= ", ";
            $video_url = isset($video_urls[$thumbnail->storage_id]) ? $video_urls[$thumbnail->storage_id] : null;
            $url = $video_url ? $video_url : $urls[$thumbnail->storage_id];
            $query .= "(" . $thumbnail->meta_id . ",'" . $url . "')";
        }
        $query .= "ON DUPLICATE KEY UPDATE meta_value=VALUES(meta_value)";
        return $this->wpdb->get_results($query);
    }

    function revert_attachments($urls, $thumbnails, $att_ids_map) {
        $count = 0;
        $query = "
            INSERT INTO " . $this->posts . " (id, guid) VALUES ";
        foreach ($thumbnails as $thumbnail) {
            if (!isset($att_ids_map[$thumbnail->meta_id])) // no metadata, only custom field
                continue;
            if ($count++ != 0)
                $query .= ", ";
            $query .= "(" . $att_ids_map[$thumbnail->meta_id] . ",'" . $urls[$thumbnail->storage_id] . "')";
        }
        $query .= "ON DUPLICATE KEY UPDATE guid=VALUES(guid)";
        return $this->wpdb->get_results($query);
    }

    function revert_attachments_meta($urls, $thumbnails, $meta_ids_map) {
        $count = 0;
        $query = "
            INSERT INTO " . $this->postmeta . " (meta_id, meta_value) VALUES ";
        foreach ($thumbnails as $thumbnail) {
            if (!isset($meta_ids_map[$thumbnail->meta_id])) // no metadata, only custom field
                continue;
            if ($count++ != 0)
                $query .= ", ";
            $query .= "(" . $meta_ids_map[$thumbnail->meta_id] . ",'" . $urls[$thumbnail->storage_id] . "')";
        }
        $query .= "ON DUPLICATE KEY UPDATE meta_value=VALUES(meta_value)";
        return $this->wpdb->get_results($query);
    }

    function revert_list_custom_fields($bucket_id, $urls, $video_urls, $thumbnails, $list_ids_map) {
        $map1_image = $map2_image = array();
        $map1_video = $map2_video = array();
        foreach ($thumbnails as $thumbnail) {
            if (!isset($list_ids_map[$thumbnail->meta_id]))
                continue;

            $video_url = isset($video_urls[$thumbnail->storage_id]) ? $video_urls[$thumbnail->storage_id] : null;
            if ($video_url) {
                $str_list = isset($map1_video[$thumbnail->post_id]) ? $map1_video[$thumbnail->post_id] : $list_ids_map[$thumbnail->meta_id][1];
                $pattern = '/' . str_replace('/', '\/', preg_quote($video_url)) . '.fifu-thumb=[^|]+/';
                $map1_video[$thumbnail->post_id] = preg_replace($pattern, $video_url, $str_list);
                $map2_video[$list_ids_map[$thumbnail->meta_id][0]] = $thumbnail->post_id;
            } else {
                $str_list = isset($map1_image[$thumbnail->post_id]) ? $map1_image[$thumbnail->post_id] : $list_ids_map[$thumbnail->meta_id][1];
                $map1_image[$thumbnail->post_id] = str_replace($this->get_su_url($bucket_id, $thumbnail->storage_id), $urls[$thumbnail->storage_id], $str_list);
                $map2_image[$list_ids_map[$thumbnail->meta_id][0]] = $thumbnail->post_id;
            }
        }

        if (!empty($map1_image)) {
            $query = "
                INSERT INTO " . $this->postmeta . " (meta_id, meta_value) VALUES ";
            $count = 0;
            foreach ($map2_image as $key => $value) {
                if ($count++ != 0)
                    $query .= ", ";
                $query .= "(" . $key . ",'" . $map1_image[$value] . "') ";
            }
            $query .= "ON DUPLICATE KEY UPDATE meta_value=VALUES(meta_value)";
            $this->wpdb->get_results($query);
        }

        if (!empty($map1_video)) {
            $query = "
                INSERT INTO " . $this->postmeta . " (meta_id, meta_value) VALUES ";
            $count = 0;
            foreach ($map2_video as $key => $value) {
                if ($count++ != 0)
                    $query .= ", ";
                $query .= "(" . $key . ",'" . $map1_video[$value] . "') ";
            }
            $query .= "ON DUPLICATE KEY UPDATE meta_value=VALUES(meta_value)";
            $this->wpdb->get_results($query);
        }
    }

    // speed up (db)

    function create_table_invalid_media_su() {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        maybe_create_table($this->fifu_invalid_media_su, "
            CREATE TABLE {$this->fifu_invalid_media_su} (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
                md5 VARCHAR(32) NOT NULL,
                attempts INT NOT NULL,
                UNIQUE KEY (md5)
            )"
        );
    }

    function insert_invalid_media_su($url) {
        if ($this->get_attempts_invalid_media_su($url)) {
            $this->update_invalid_media_su($url);
            return;
        }

        $md5 = md5($url);
        $this->wpdb->get_results("
            INSERT INTO {$this->fifu_invalid_media_su} (md5, attempts) 
            VALUES ('{$md5}', 1)"
        );
    }

    function update_invalid_media_su($url) {
        $md5 = md5($url);
        $this->wpdb->get_results("
            UPDATE {$this->fifu_invalid_media_su} 
            SET attempts = attempts + 1
            WHERE md5 = '{$md5}'"
        );
    }

    function get_attempts_invalid_media_su($url) {
        $md5 = md5($url);
        $result = $this->wpdb->get_row("
            SELECT attempts
            FROM " . $this->fifu_invalid_media_su . " 
            WHERE md5 = '{$md5}'"
        );
        return $result ? (int) $result->attempts : 0;
    }

    function delete_invalid_media_su($url) {
        $md5 = md5($url);
        $this->wpdb->get_results("
            DELETE FROM " . $this->fifu_invalid_media_su . " 
            WHERE md5 = '{$md5}'"
        );
    }

    ///////////////////////////////////////////////////////////////////////////////////

    function count_available_images() {
        $total = 0;

        $featured = $this->wpdb->get_results("
            SELECT COUNT(1) AS total
            FROM " . $this->postmeta . "
            WHERE meta_key = '_thumbnail_id'"
        );

        $total += (int) $featured[0]->total;

        if (class_exists('WooCommerce')) {
            $gallery = $this->wpdb->get_results("
                SELECT SUM(LENGTH(meta_value) - LENGTH(REPLACE(meta_value, ',', '')) + 1) AS total
                FROM " . $this->postmeta . "
                WHERE meta_key = '_product_image_gallery'"
            );

            $total += (int) $gallery[0]->total;

            $category = $this->wpdb->get_results("
                SELECT COUNT(1) AS total
                FROM " . $this->termmeta . "
                WHERE meta_key = 'thumbnail_id'"
            );

            $total += (int) $category[0]->total;
        }

        return $total;
    }

    /* insert attachment */

    function insert_attachment_by($value) {
        $this->wpdb->get_results("
            INSERT INTO " . $this->posts . " (post_author, guid, post_title, post_mime_type, post_type, post_status, post_parent, post_date, post_date_gmt, post_modified, post_modified_gmt, post_content, post_excerpt, to_ping, pinged, post_content_filtered) 
            VALUES " . str_replace('\\', '', $value));
    }

    function insert_or_update_attachment_by($value) {
        $query = "
            INSERT INTO " . $this->posts . " (ID, post_author, guid, post_title, post_mime_type, post_type, post_status, post_parent, post_date, post_date_gmt, post_modified, post_modified_gmt, post_content, post_excerpt, to_ping, pinged, post_content_filtered) 
            VALUES " . str_replace('\\', '', $value) . " 
            ON DUPLICATE KEY UPDATE guid=VALUES(guid), post_title=VALUES(post_title)";
        return $this->wpdb->get_results($query);
    }

    function insert_ctgr_attachment_by($value) {
        $this->wpdb->get_results("
            INSERT INTO " . $this->posts . " (post_author, guid, post_title, post_mime_type, post_type, post_status, post_parent, post_date, post_date_gmt, post_modified, post_modified_gmt, post_content, post_excerpt, to_ping, pinged, post_content_filtered, post_name) 
            VALUES " . str_replace('\\', '', $value));
    }

    function get_formatted_value($url, $alt, $post_parent) {
        return "(" . $this->author . ", '" . $url . "', '" . str_replace("'", "", $alt) . "', 'image/jpeg', 'attachment', 'inherit', '" . $post_parent . "', now(), now(), now(), now(), '', '', '', '', '')";
    }

    function get_formatted_value_with_id($post_id, $url, $alt, $post_parent) {
        return "(" . $post_id . ", " . $this->author . ", '" . $url . "', '" . str_replace("'", "", $alt) . "', 'image/jpeg', 'attachment', 'inherit', '" . $post_parent . "', now(), now(), now(), now(), '', '', '', '', '')";
    }

    function get_ctgr_formatted_value($url, $alt, $post_parent) {
        return "(" . $this->author . ", '" . $url . "', '" . str_replace("'", "", $alt) . "', 'image/jpeg', 'attachment', 'inherit', '" . $post_parent . "', now(), now(), now(), now(), '', '', '', '', '', 'fifu-category-" . $post_parent . "')";
    }

    /* product variation */

    function get_product_image_gallery($post_id) {
        return rtrim(get_post_meta($post_id, '_product_image_gallery', true), ',');
    }

    function get_thumbnail_id($post_id, $is_ctgr) {
        $ctgr_sql = $is_ctgr ? "AND post_name LIKE 'fifu-category%'" : "";

        $result = $this->wpdb->get_results("
            SELECT MIN(id) AS id 
            FROM " . $this->posts . " 
            WHERE post_parent = " . $post_id . " 
            " . $ctgr_sql . " 
            AND post_type = 'attachment'"
        );
        return $result ? $result[0]->id : null;
    }

    function get_attachments($post_id, $is_ctgr) {
        $ctgr_sql = $is_ctgr ? "AND post_name LIKE 'fifu-category%'" : "";

        $ids = null;
        $i = 1;
        $result = $this->wpdb->get_results("
            SELECT id 
            FROM " . $this->posts . " 
            WHERE post_parent = " . $post_id . " 
            " . $ctgr_sql . " 
            AND post_type = 'attachment'"
        );
        foreach ($result as $res)
            $ids = ($i++ == 1) ? $res->id : ($ids . "," . $res->id);
        return $ids;
    }

    function insert_attachment_list($post_id, $urls, $alts, $is_slider, $video_urls) {
        $value = null;
        $value_meta_url = null;
        $value_meta_alt = null;
        $i = 0;
        $video_i = $urls ? 1 : 0;

        // merge the lists of urls
        if ($video_urls) {
            foreach ($video_urls as $video_url) {
                array_push($urls, $video_url);
            }
        }

        $query_meta = "INSERT INTO " . $this->postmeta . " (post_id, meta_key, meta_value) VALUES ";
        foreach ($urls as $url) {
            $url = esc_url_raw(trim($url));
            $alt = ($alts && count($alts) > $i) ? $alts[$i] : '';
            $alt = addslashes($alt);

            $is_video_url = fifu_is_video($url);
            $aux = $this->get_formatted_value($is_video_url ? fifu_video_img_large($url, $post_id, false) : $url, $alt, $post_id);
            $value = ($i == 0) ? $aux : ($value . "," . $aux);

            // urls
            if ($is_slider)
                $aux = "(" . $post_id . ", 'fifu_slider_image_url_" . $i . "', '" . $url . "')";
            elseif ($is_video_url)
                $aux = "(" . $post_id . ", 'fifu_video_url" . ($video_i == 0 ? "" : "_" . ($video_i - 1)) . "', '" . $url . "')";
            else
                $aux = "(" . $post_id . ", 'fifu_image_url" . ($i == 0 ? "" : "_" . ($i - 1)) . "', '" . $url . "')";

            $value_meta_url = ($i == 0) ? $aux : ($value_meta_url . "," . $aux);

            // alt
            if ($is_slider)
                $aux = "(" . $post_id . ", 'fifu_slider_image_alt_" . $i . "', '" . $alt . "')";
            elseif ($is_video_url)
                $aux = null;
            else
                $aux = "(" . $post_id . ", 'fifu_image_alt" . ($i == 0 ? "" : "_" . ($i - 1)) . "', '" . $alt . "')";

            if ($aux)
                $value_meta_alt = ($i == 0) ? $aux : ($value_meta_alt . "," . $aux);
            else
                $value_meta_alt = null;

            $i++;

            if ($is_video_url)
                $video_i++;
        }
        if (!$value)
            return;
        $this->insert_attachment_by($value);
        $this->wpdb->get_results($query_meta . " " . $value_meta_url);
        if ($value_meta_alt)
            $this->wpdb->get_results($query_meta . " " . $value_meta_alt);
        $thumbnail_id = $this->get_thumbnail_id($post_id, false);

        $this->wpdb->get_results("
            INSERT INTO " . $this->postmeta . " (post_id, meta_key, meta_value) 
            VALUES (" . $post_id . ", '_thumbnail_id', " . $thumbnail_id . ")"
        );

        $this->wpdb->get_results("
            INSERT INTO " . $this->postmeta . " (post_id, meta_key, meta_value) (
                SELECT id, '_wp_attached_file', guid 
                FROM " . $this->posts . " 
                WHERE post_parent = " . $post_id . " 
                AND post_name NOT LIKE 'fifu-category%' 
            )"
        );

        $this->wpdb->get_results("
            INSERT INTO " . $this->postmeta . " (post_id, meta_key, meta_value) (
                SELECT id, '_wp_attachment_image_alt', post_title 
                FROM " . $this->posts . " 
                WHERE post_parent = " . $post_id . " 
                AND post_name NOT LIKE 'fifu-category%' 
            )"
        );

        delete_post_meta($post_id, '_product_image_gallery');

        $this->wpdb->get_results("
            INSERT INTO " . $this->postmeta . " (post_id, meta_key, meta_value) (
                SELECT post_parent, '_product_image_gallery', group_concat(id) 
                FROM " . $this->posts . " 
                WHERE post_parent = " . $post_id . " 
                AND post_name NOT LIKE 'fifu-category%' 
                AND id <> " . $thumbnail_id . " 
                AND post_type = 'attachment' 
                GROUP BY post_parent
            )"
        );
    }

    function update_attachment_list($post_id, $urls, $alts, $is_slider, $video_urls) {
        $attachments = $this->get_attachments($post_id, false);
        if ($attachments) {
            $this->wpdb->get_results("DELETE FROM " . $this->postmeta . " WHERE post_id IN (" . $attachments . ")");
            $this->wpdb->get_results("DELETE FROM " . $this->posts . " WHERE id IN (" . $attachments . ")");
        }
        $this->wpdb->get_results("DELETE FROM " . $this->postmeta . " WHERE post_id = " . $post_id . " AND meta_key IN ('_product_image_gallery', '_thumbnail_id')");
        $this->wpdb->get_results("DELETE FROM " . $this->postmeta . " WHERE post_id = " . $post_id . " AND meta_key LIKE 'fifu_%'");
        if (!empty($urls) && !empty($urls[0]))
            $this->insert_attachment_list($post_id, $urls, $alts, $is_slider, $video_urls);
    }

    /* variation gallery */

    function update_wc_additional_variation_images($post_id) {
        wp_cache_flush();
        $ids = '';
        foreach ($this->get_variantion_products($post_id) as $res) {
            $gallery_ids = get_post_meta($res->id, '_product_image_gallery', true);
            if ($gallery_ids)
                update_post_meta($res->id, '_wc_additional_variation_images', $gallery_ids);
            else {
                $additional_ids = get_post_meta($res->id, '_wc_additional_variation_images');
                if ($additional_ids) {
                    $additional_ids = explode(',', $additional_ids[0]);
                    foreach ($additional_ids as $id) {
                        if (get_post($id) == null)
                            update_post_meta($res->id, '_wc_additional_variation_images', '');
                    }
                }
            }
        }
    }

    /* auto set category image */

    function insert_auto_category_image() {
        $this->delete_empty_urls();
        $this->delete_empty_urls_category();

        $this->update_category_images_auto();

        $this->insert_category_images_auto();
        $this->insert_attachment_category();
        $this->insert_auto_subcategory_image();
    }

    function insert_auto_subcategory_image() {
        foreach ($this->get_child_category() as $i) {
            if ($this->exists_child_with_attachment($i->term_id, $i->parent)) {
                $att_id = get_term_meta($i->term_id, 'thumbnail_id', true);
                update_term_meta($i->parent, 'thumbnail_id', $att_id);
            }
        }
    }

    /* insert fake internal featured image */

    function insert_attachment_category() {
        $ids = null;
        $value = null;
        $i = 0;
        // insert 1 attachment for each selected category
        foreach ($this->get_categories_without_meta() as $res) {
            $ids = ($i++ == 0) ? $res->term_id : ($ids . "," . $res->term_id);
            $url = get_term_meta($res->term_id, 'fifu_video_url', true);
            $url = $url ? fifu_video_img_large($url, $res->term_id, true) : get_term_meta($res->term_id, 'fifu_image_url', true);
            if (!$url) {
                $result = $this->get_category_image_url($res->term_id);
                $url = $result[0]->meta_value;
            }
            $url = htmlspecialchars_decode($url);
            $value = $this->get_ctgr_formatted_value($url, get_term_meta($res->term_id, 'fifu_image_alt', true), $res->term_id);
            $this->insert_ctgr_attachment_by($value);
            $att_id = $this->wpdb->insert_id;
            update_term_meta($res->term_id, 'thumbnail_id', $att_id);
        }
        if ($ids) {
            $this->insert_attachment_meta_url($ids, true);
            $this->insert_attachment_meta_alt($ids, true);
        }
    }

    function insert_attachment() {
        $ids = null;
        $value = null;
        $i = 1;
        $count = 1;
        $total = (int) $this->get_count_urls_without_metadata()[0]->amount;
        // insert 1 attachment for each selected post
        $result = $this->get_posts_without_meta();
        foreach ($result as $res) {
            $ids = ($i == 1) ? $res->post_id : ($ids . "," . $res->post_id);
            $url = fifu_main_image_url($res->post_id, false);
            $aux = $this->get_formatted_value($url, get_post_meta($res->post_id, 'fifu_image_alt', true), $res->post_id);
            $value = ($i == 1) ? $aux : ($value . "," . $aux);
            if ($value && (($i % $this->MAX_INSERT == 0) || ($i % $this->MAX_INSERT != 0 && count($result) == $count))) {
                wp_cache_flush();
                $this->insert_attachment_by($value);
                $this->insert_thumbnail_id($ids, false);
                $this->insert_attachment_meta_url($ids, false);
                $this->insert_attachment_meta_alt($ids, false);
                set_transient('fifu_image_metadata_counter', $total - $count, 0);
                if (get_option('fifu_fake_stop'))
                    return;
                $ids = null;
                $value = null;
                $i = 1;
            } else
                $i++;
            $count++;
        }
    }

    function insert_attachment_gallery() {
        $ids = null;
        $value = null;
        $i = 1;
        $j = 1;
        $count = 1;
        $done = 0;
        $total = (int) $this->get_count_urls_without_metadata()[0]->amount;
        // insert 1 attachment for each selected url
        $result = $this->get_posts_with_external_gallery_without_meta();
        foreach ($result as $res) {
            $ids = ($i == 1) ? $res->post_id : ($ids . "," . $res->post_id);
            $result2 = $this->get_gallery_urls($res->post_id);
            foreach ($result2 as $res2) {
                $url = $res2->meta_value;
                $url = fifu_is_video($url) ? fifu_video_img_large($url, $res->post_id, false) : $url;
                $url = htmlspecialchars_decode($url);
                $aux = $this->get_formatted_value($url, '', $res->post_id);
                $value = ($j == 1) ? $aux : ($value . "," . $aux);
                $j++;
            }
            if ($value && (($j >= $this->MAX_INSERT) || ($j < $this->MAX_INSERT && count($result) == $count))) {
                wp_cache_flush();
                $this->insert_attachment_by($value);
                $this->insert_thumbnail_id($ids, false);
                $this->delete_attachment_meta($ids, false);
                $this->insert_attachment_meta_url($ids, false);
                $this->insert_attachment_meta_alt($ids, false);
                $this->delete_product_image_gallery_by($ids, false);
                $this->insert_product_image_gallery($ids, false);
                $this->insert_wc_additional_variation_images($ids, false);
                $done += $j;
                set_transient('fifu_image_metadata_counter', $total - $done, 0);
                if (get_option('fifu_fake_stop'))
                    return;
                $ids = null;
                $value = null;
                $i = 1;
                $j = 1;
            } else
                $i++;
            $count++;
        }
    }

    /* delete fake internal featured image */

    function delete_attachment() {
        $ids = null;
        $i = 1;
        $count = 1;
        // delete fake attachments and _thumbnail_ids
        $result = $this->get_fake_attachments();
        foreach ($result as $res) {
            $ids = ($i == 1) ? $res->id : ($ids . "," . $res->id);
            if ($ids && (($i % $this->MAX_INSERT == 0) || ($i % $this->MAX_INSERT != 0 && count($result) == $count))) {
                wp_cache_flush();
                $this->delete_thumbnail_ids($ids);
                $this->delete_attachments($ids);
                $ids = null;
                $i = 1;
            } else
                $i++;
            $count++;
        }

        $ids = null;
        $i = 1;
        $count = 1;
        // delete attachment data and more _thumbnail_ids
        $result = $this->get_posts_with_url();
        foreach ($result as $res) {
            $ids = ($i == 1) ? $res->post_id : ($ids . "," . $res->post_id);
            if ($ids && (($i % $this->MAX_INSERT == 0) || ($i % $this->MAX_INSERT != 0 && count($result) == $count))) {
                wp_cache_flush();
                $this->delete_invalid_thumbnail_ids($ids);
                $this->delete_fake_thumbnail_id($ids);
                $this->delete_attachment_meta_url($ids);
                $ids = null;
                $i = 1;
            } else
                $i++;
            $count++;
        }

        // delete data without attachment
        $this->delete_thumbnail_id_without_attachment();
        $this->delete_attachment_meta_without_attachment();

        $ids = null;
        $i = 0;
        // delete _product_image_gallery
        foreach ($this->get_attachments_with_gallery() as $res)
            $ids = ($i++ == 0) ? $res->post_id : ($ids . "," . $res->post_id);
        if ($ids)
            $this->delete_product_image_gallery($ids);

        $this->delete_empty_urls();
    }

    function delete_attachment_category() {
        $ids = null;
        $i = 0;
        foreach ($this->get_terms_with_url() as $res)
            $ids = ($i++ == 0) ? $res->term_id : ($ids . "," . $res->term_id);
        if ($ids) {
            $this->delete_thumbnail_ids_category($ids);
            $this->delete_attachment_meta($ids, true);
            $this->delete_thumbnail_ids_category_without_attachment();
        }
        $this->delete_empty_urls_category();
    }

    /* auto set: update all */

    function update_all() {
        $ids = null;
        $value = null;
        $i = 1;
        $count = 1;
        $arr_post = array();
        $auto_alt = fifu_is_on('fifu_auto_alt');

        // get all posts or all posts without url
        $result = fifu_is_on('fifu_update_ignore') ? $this->get_post_types_without_url() : $this->get_all_post_types();
        foreach ($result as $res) {
            $post_id = $res->ID;

            $content = fifu_is_on('fifu_decode') ? html_entity_decode($res->post_content) : $res->post_content;

            // set featured image
            $image_url = fifu_first_url_in_content($post_id, $content, false);
            $video_url = fifu_first_url_in_content($post_id, $content, true);

            if ($image_url || $video_url || $auto_alt) {
                $url = null;
                if ($image_url || $video_url) {
                    $is_image = false;
                    $is_video = false;

                    if ($image_url && $video_url) {
                        if (fifu_is_on('fifu_video_priority'))
                            $is_video = true;
                        else
                            $is_image = true;
                    } elseif ($image_url)
                        $is_image = true;
                    elseif ($video_url)
                        $is_video = true;

                    if ($is_image) {
                        fifu_update_or_delete($post_id, 'fifu_image_url', $image_url);
                        $url = $image_url;
                        $media = fifu_first_img_in_content($content);
                    } elseif ($is_video) {
                        fifu_update_or_delete($post_id, 'fifu_video_url', $video_url);
                        if (fifu_is_on('fifu_video_priority'))
                            fifu_dev_set_image($post_id, '');
                        $url = fifu_video_img_large($video_url, $post_id, false);
                        $media = fifu_first_video_in_content($content);
                    }

                    // hide/show first image
                    if (fifu_is_on('fifu_pop_first'))
                        $new_content = str_replace($media, fifu_hide_media($media), $content);
                    else
                        $new_content = str_replace($media, fifu_show_media($media), $content);
                    array_push($arr_post, ["id" => $post_id, "content" => $new_content]);
                }

                // auto set alt
                $alt = get_post_meta($post_id, 'fifu_image_alt', true);
                $url = $url ? $url : get_post_meta($post_id, 'fifu_image_url', true);
                if (!$alt && $url)
                    fifu_update_or_delete_value($post_id, 'fifu_image_alt', get_the_title($post_id));

                $ids = ($i == 1) ? $post_id : ($ids . "," . $post_id);
                $aux = $this->get_formatted_value_with_id(get_post_thumbnail_id($post_id), $url, fifu_is_on('fifu_auto_alt') ? $res->post_title : null, $post_id);
                $value = ($i == 1) ? $aux : ($value . "," . $aux);
                $i++;
            }

            if ($value && (($i % $this->MAX_INSERT == 0) || ($i % $this->MAX_INSERT != 0 && count($result) == $count))) {
                wp_cache_flush();
                if (!empty($arr_post)) {
                    $this->update_post_content_arr($arr_post);
                }
                $this->insert_or_update_attachment_by($value);
                $this->insert_thumbnail_id($ids, false);
                $this->insert_attachment_meta_url($ids, false);
                $this->insert_attachment_meta_alt($ids, false);

                $ids = null;
                $value = null;
                $i = 1;
            }

            $count++;
        }
    }

    /* dimensions: clean all */

    function clean_dimensions_all() {
        $this->wpdb->get_results("
            DELETE FROM " . $this->postmeta . " pm            
            WHERE pm.meta_key = '_wp_attachment_metadata'
            AND EXISTS (
                SELECT 1 
                FROM " . $this->posts . " p 
                WHERE p.id = pm.post_id
                AND p.post_type = 'attachment'
                AND p.post_author = " . $this->author . " 
            )"
        );
    }

    /* save 1 post */

    function update_fake_attach_id($post_id) {
        $att_id = get_post_thumbnail_id($post_id);
        $url = fifu_main_image_url($post_id, false);

        $has_fifu_attachment = $att_id ? ($this->is_fifu_attachment($att_id) && get_option('fifu_default_attach_id') != $att_id) : false;
        // delete
        if (!$url || $url == get_option('fifu_default_url')) {
            if ($has_fifu_attachment) {
                wp_delete_attachment($att_id);
                delete_post_thumbnail($post_id);
                if (fifu_get_default_url() && fifu_is_valid_default_cpt($post_id))
                    set_post_thumbnail($post_id, get_option('fifu_default_attach_id'));
            } else {
                // when an external image is removed and an internal is added at the same time
                $attachments = $this->get_attachments_without_post($post_id);
                if ($attachments) {
                    $this->delete_attachment_meta_url_and_alt($attachments);
                    $this->delete_attachments($attachments);
                }

                if (fifu_get_default_url() && fifu_is_valid_default_cpt($post_id)) {
                    $post_thumbnail_id = get_post_thumbnail_id($post_id);
                    $hasInternal = $post_thumbnail_id && get_post_field('post_author', $post_thumbnail_id) != $this->author;
                    if (!$hasInternal)
                        set_post_thumbnail($post_id, get_option('fifu_default_attach_id'));
                }
            }
        } else {
            // update
            $alt = get_post_meta($post_id, 'fifu_image_alt', true);
            if (!$alt && fifu_is_on('fifu_auto_alt'))
                $alt = get_the_title($post_id);

            if ($has_fifu_attachment) {
                update_post_meta($att_id, '_wp_attached_file', $url);
                update_post_meta($att_id, '_wp_attachment_image_alt', $alt);
                $this->wpdb->update($this->posts, $set = array('post_title' => $alt, 'guid' => $url), $where = array('id' => $att_id), null, null);
            }
            // insert
            else {
                $value = $this->get_formatted_value($url, $alt, $post_id);
                $this->insert_attachment_by($value);
                $att_id = $this->wpdb->insert_id;
                update_post_meta($post_id, '_thumbnail_id', $att_id);
                update_post_meta($att_id, '_wp_attached_file', $url);
                update_post_meta($att_id, '_wp_attachment_image_alt', $alt);
                $attachments = $this->get_attachments_without_post($post_id);
                if ($attachments) {
                    $this->delete_attachment_meta_url_and_alt($attachments);
                    $this->delete_attachments($attachments);
                }
            }
        }
    }

    /* save 1 gallery */

    function update_fake_attach_id_gallery($post_id) {
        $video_enabled = fifu_is_on('fifu_video');
        $value = null;
        $i = 0;
        $attach_ids = rtrim(get_post_meta($post_id, '_product_image_gallery', true), ',');

        if (!$attach_ids) {
            $attach_ids = rtrim(get_post_meta($post_id, 'fifu_tmp_product_image_gallery', true), ',');
            delete_post_meta($post_id, 'fifu_tmp_product_image_gallery');
        }

        if ($attach_ids) {
            if (!$this->has_fifu_attachment($attach_ids))
                return;
            $this->delete_attachment_meta_url_and_alt($attach_ids);
            $this->delete_attachments($attach_ids);
        }
        $urls = $this->get_gallery_urls($post_id);
        $alts = $this->get_gallery_alts($post_id);
        while ($i < sizeof($urls)) {
            $field_url = $urls[$i]->meta_key;
            $url = $urls[$i]->meta_value;
            $alt = $alts && isset($alts[$i]->meta_value) ? $alts[$i]->meta_value : '';
            $i++;
            if ($video_enabled)
                $url = fifu_is_video($url) ? fifu_video_img_large($url, $post_id, false) : $url;
            if (fifu_is_google_drive_file($url)) {
                $url = fifu_google_drive_url($url);
                update_post_meta($post_id, $field_url, $url);
            }
            $aux = $this->get_formatted_value($url, $alt, $post_id);
            $value = !$value ? $aux : ($value . "," . $aux);
        }
        if ($value) {
            $this->insert_attachment_by($value);
            $this->insert_attachment_meta_url($post_id, false);
            $this->insert_attachment_meta_alt($post_id, false);
        }
        if ($attach_ids)
            $this->delete_product_image_gallery_by_attach_ids($post_id, $attach_ids);
        else
            delete_post_meta($post_id, '_product_image_gallery', '');
        $this->insert_product_image_gallery($post_id, false);
        $this->insert_wc_additional_variation_images($post_id, false);
    }

    /* save 1 category */

    function ctgr_update_fake_attach_id($term_id) {
        $att_id = get_term_meta($term_id, 'thumbnail_id');
        $att_id = $att_id ? $att_id[0] : null;
        $has_fifu_attachment = $att_id ? $this->is_fifu_attachment($att_id) : false;

        $url = null;
        if (fifu_is_on('fifu_video')) {
            $url = get_term_meta($term_id, 'fifu_video_url', true);
            $url = $url ? fifu_video_img_large($url, $term_id, true) : null;
            if (fifu_is_youtube_thumb($url) && strpos($url, 'maxresdefault') !== false) {
                if (wp_remote_get($url)['http_response']->get_response_object()->status_code == 404)
                    $url = str_replace('maxresdefault', 'mqdefault', $url);
            }
        }
        $url = $url ? $url : get_term_meta($term_id, 'fifu_image_url', true);

        // delete
        if (!$url) {
            if ($has_fifu_attachment) {
                wp_delete_attachment($att_id);
                update_term_meta($term_id, 'thumbnail_id', 0);
            }
        } else {
            // update
            $alt = get_term_meta($term_id, 'fifu_image_alt', true);
            if ($has_fifu_attachment) {
                update_post_meta($att_id, '_wp_attached_file', $url);
                update_post_meta($att_id, '_wp_attachment_image_alt', $alt);
                $this->wpdb->update($this->posts, $set = array('guid' => $url, 'post_title' => $alt), $where = array('id' => $att_id), null, null);
            }
            // insert
            else {
                $value = $this->get_ctgr_formatted_value($url, $alt, $term_id);
                $this->insert_ctgr_attachment_by($value);
                $att_id = $this->wpdb->insert_id;
                update_term_meta($term_id, 'thumbnail_id', $att_id);
                update_post_meta($att_id, '_wp_attached_file', $url);
                update_post_meta($att_id, '_wp_attachment_image_alt', $alt);
                $attachments = $this->get_ctgr_attachments_without_post($term_id);
                if ($attachments) {
                    $this->delete_attachment_meta_url_and_alt($attachments);
                    $this->delete_attachments($attachments);
                }
            }
        }
    }

    /* default url */

    function create_attachment($url) {
        $value = $this->get_formatted_value($url, null, null);
        $this->insert_attachment_by($value);
        return $this->wpdb->insert_id;
    }

    function set_default_url() {
        $att_id = get_option('fifu_default_attach_id');
        if (!$att_id)
            return;
        $post_types = join("','", explode(',', str_replace(' ', '', get_option('fifu_default_cpt'))));
        $post_types ? $post_types : $this->types;
        $value = null;
        foreach ($this->get_posts_without_featured_image($post_types) as $res) {
            $aux = "(" . $res->id . ", '_thumbnail_id', " . $att_id . ")";
            $value = $value ? $value . ',' . $aux : $aux;
        }
        if ($value) {
            $this->insert_default_thumbnail_id($value);
            update_post_meta($att_id, '_wp_attached_file', get_option('fifu_default_url'));
        }
    }

    function update_default_url($url) {
        $att_id = get_option('fifu_default_attach_id');
        if ($url != wp_get_attachment_url($att_id)) {
            $this->wpdb->update($this->posts, $set = array('guid' => $url), $where = array('id' => $att_id), null, null);
            update_post_meta($att_id, '_wp_attached_file', $url);
        }
    }

    function delete_default_url() {
        $att_id = get_option('fifu_default_attach_id');
        wp_delete_attachment($att_id);
        delete_option('fifu_default_attach_id');
        $this->wpdb->delete($this->postmeta, array('meta_key' => '_thumbnail_id', 'meta_value' => $att_id));
    }

    function add_default_image($post_id) {
        if (fifu_is_off('fifu_enable_default_url'))
            return;
        $att_id = get_option('fifu_default_attach_id');
        $value = "(" . $post_id . ", '_thumbnail_id', " . $att_id . ")";
        $this->insert_default_thumbnail_id($value);
        update_post_meta($att_id, '_wp_attached_file', get_option('fifu_default_url'));
    }

    /* delete post */

    function before_delete_post($post_id) {
        $default_url_enabled = fifu_is_on('fifu_enable_default_url');
        $default_att_id = $default_url_enabled ? get_option('fifu_default_attach_id') : null;
        $result = $this->get_featured_and_gallery_ids($post_id);
        if ($result) {
            $aux = $result[0]->ids;
            $ids = $aux ? explode(',', $aux) : array();
            $value = null;
            foreach ($ids as $id) {
                if ($id && $id != $default_att_id)
                    $value = ($value == null) ? $id : $value . ',' . $id;
            }
            if ($value) {
                $this->delete_attachment_meta_url_and_alt($value);
                $this->delete_attachments($value);
            }
        }
    }

    function delete_category_image($post_id) {
        if (fifu_is_off('fifu_auto_category'))
            return;

        foreach ($this->get_category_id($post_id) as $i) {
            $term_id = $i->term_id;
            if ($term_id) {
                $this->delete_image_url_category($term_id);
                $aux = $this->get_category_thumbnail_id($term_id);
                $att_id = $aux->meta_value;
                wp_delete_attachment($att_id);
                update_term_meta($term_id, 'thumbnail_id', 0);
            }
        }
    }

    /* clean metadata */

    function enable_clean() {
        $this->delete_metadata();
        $this->delete_duplicated_category_url();
        $this->delete_video_oembed_all();
        wp_delete_attachment(get_option('fifu_fake_attach_id'));
        wp_delete_attachment(get_option('fifu_default_attach_id'));
        delete_option('fifu_fake_attach_id');
        fifu_disable_fake();
        update_option('fifu_fake', 'toggleoff', 'no');
    }

    /* delete all urls */

    function delete_all() {
        sleep(3);
        if (fifu_is_on('fifu_run_delete_all') && get_option('fifu_run_delete_all_time') && FIFU_DELETE_ALL_URLS) {
            $this->wpdb->get_results("
                DELETE FROM " . $this->postmeta . " 
                WHERE meta_key LIKE 'fifu_%'"
            );
        }
    }

    /* md5 */

    function create_table_md5() {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        maybe_create_table($this->fifu_md5, "
            CREATE TABLE {$this->fifu_md5} (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
                md5 VARCHAR(32) NOT NULL,
                thumbnail_id INT NOT NULL,
                UNIQUE KEY (md5)
            )"
        );
    }

    function insert_md5($url, $thumbnail_id) {
        if ($this->get_thumbnail_id_by_md5($url))
            return;

        $md5 = md5($url);
        $this->wpdb->get_results("
            INSERT INTO {$this->fifu_md5} (md5, thumbnail_id) 
            VALUES ('{$md5}', {$thumbnail_id})"
        );
    }

    function get_thumbnail_id_by_md5($url) {
        $md5 = md5($url);
        $result = $this->wpdb->get_row("
            SELECT thumbnail_id
            FROM " . $this->fifu_md5 . " 
            WHERE md5 = '{$md5}'"
        );
        return $result ? $result->thumbnail_id : null;
    }

    function delete_md5_by_thumbnail_id($thumbnail_id) {
        $this->wpdb->get_results("
            DELETE FROM " . $this->fifu_md5 . " 
            WHERE thumbnail_id = '{$thumbnail_id}'"
        );
    }

    /* video_oembed */

    // Error: "Specified key was too long; max key length is 1000 bytes".
    // Possible solution: reduce the maximum length of the video_url, image_url, and embed_url columns to 250 characters or less.

    function create_table_video_oembed() {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        maybe_create_table($this->fifu_video_oembed, "
            CREATE TABLE {$this->fifu_video_oembed} (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                video_url VARCHAR(255) NOT NULL,
                image_url VARCHAR(255) NOT NULL,
                embed_url VARCHAR(255) NOT NULL,
                UNIQUE KEY (video_url),
                INDEX index_fifu_video_oembed_image_url (image_url) USING HASH,
                INDEX index_fifu_video_oembed_embed_url (embed_url) USING HASH
            )"
        );
    }

    function insert_video_oembed($video_url, $image_url, $embed_url) {
        if ($this->video_oembed_exists($video_url))
            return;

        $this->wpdb->get_results("
            INSERT INTO {$this->fifu_video_oembed} (video_url, image_url, embed_url) 
            VALUES ('{$video_url}', '{$image_url}', '{$embed_url}')"
        );
    }

    function video_oembed_exists($video_url) {
        $result = $this->wpdb->get_results("
            SELECT COUNT(1) AS amount
            FROM " . $this->fifu_video_oembed . " 
            WHERE video_url = '{$video_url}'"
        );
        return intval($result[0]->amount) > 0;
    }

    function delete_video_oembed_by_video_url($video_url) {
        $this->wpdb->get_results("
            DELETE FROM " . $this->fifu_video_oembed . " 
            WHERE video_url = '{$video_url}'"
        );
    }

    function delete_video_oembed_by_image_url($image_url) {
        $this->wpdb->get_results("
            DELETE FROM " . $this->fifu_video_oembed . " 
            WHERE image_url = '{$image_url}'"
        );
    }

    function get_image_url_by_video_url($video_url) {
        $result = $this->wpdb->get_row("
            SELECT image_url
            FROM " . $this->fifu_video_oembed . " 
            WHERE video_url = '{$video_url}'"
        );
        return $result ? $result->image_url : null;
    }

    function get_embed_url_by_image_url($image_url) {
        $result = $this->wpdb->get_row("
            SELECT embed_url
            FROM " . $this->fifu_video_oembed . " 
            WHERE image_url = '{$image_url}'"
        );
        return $result ? $result->embed_url : null;
    }

    function get_embed_url_by_video_url($video_url) {
        $result = $this->wpdb->get_row("
            SELECT embed_url
            FROM " . $this->fifu_video_oembed . " 
            WHERE video_url = '{$video_url}'"
        );
        return $result ? $result->embed_url : null;
    }

    function delete_video_oembed_all() {
        $this->wpdb->get_results("
            DELETE FROM " . $this->fifu_video_oembed
        );
    }

    /* aawp */

    function get_aawp_asins($type, $keywords) {
        $result = $this->wpdb->get_row("
            SELECT product_asins
            FROM " . $this->aawp_lists . " 
            WHERE type = '{$type}'
            AND keywords = '{$keywords}'"
        );
        return $result ? $result->product_asins : null;
    }

    function get_aawp_image_ids($asin) {
        $result = $this->wpdb->get_row("
            SELECT image_ids
            FROM " . $this->aawp_products . " 
            WHERE asin = '{$asin}'"
        );
        return $result ? $result->image_ids : null;
    }

}

/* rest api */

function fifu_db_insert($post_id, $urls, $alts, $is_slider, $video_urls) {
    $db = new FifuDb();
    if ($urls || $video_urls)
        $db->insert_attachment_list($post_id, $urls, $alts, $is_slider, $video_urls);
    else
        $db->add_default_image($post_id);
}

function fifu_db_update($post_id, $urls, $alts, $is_slider, $video_urls) {
    $db = new FifuDb();
    if ($urls && $urls[0])
        $db->update_attachment_list($post_id, $urls, $alts, $is_slider, $video_urls);
    else
        $db->add_default_image($post_id);
}

function fifu_db_variantion_products($post_id) {
    $db = new FifuDb();
    return $db->get_variantion_products($post_id);
}

/* auto set category image */

function fifu_db_insert_auto_category_image() {
    $db = new FifuDb();
    $db->insert_auto_category_image();
}

/* fake internal featured image */

function fifu_db_insert_attachment_gallery() {
    $db = new FifuDb();
    $db->insert_attachment_gallery();
}

function fifu_db_insert_attachment_category() {
    $db = new FifuDb();
    $db->insert_attachment_category();
}

function fifu_db_insert_attachment() {
    $db = new FifuDb();
    $db->insert_attachment();
}

function fifu_db_delete_attachment_category() {
    $db = new FifuDb();
    $db->delete_attachment_category();
}

function fifu_db_delete_attachment() {
    $db = new FifuDb();
    $db->delete_attachment();
}

/* product variation gallery */

function fifu_db_update_wc_additional_variation_images($post_id) {
    $db = new FifuDb();
    return $db->update_wc_additional_variation_images($post_id);
}

/* auto set: update all */

function fifu_db_update_all() {
    $db = new FifuDb();
    return $db->update_all();
}

/* change max URL length */

function fifu_db_change_url_length() {
    $db = new FifuDb();
    $db->change_url_length();
}

/* clean depracted data */

function fifu_db_delete_deprecated_data() {
    $db = new FifuDb();
    $db->delete_deprecated_options();
}

/* dimensions: get all */

function fifu_db_get_all_without_dimensions() {
    $db = new FifuDb();
    return $db->get_posts_without_dimensions();
}

/* dimensions: clean all */

function fifu_db_clean_dimensions_all() {
    $db = new FifuDb();
    return $db->clean_dimensions_all();
}

/* dimensions: amount */

function fifu_db_missing_dimensions() {
    $db = new FifuDb();

    // too much
    if (fifu_db_count_urls_with_metadata() > 10000)
        return -1;

    $aux = $db->get_count_posts_without_dimensions()[0];
    return $aux ? $aux->amount : -1;
}

/* count: metadata */

function fifu_db_count_urls_with_metadata() {
    $db = new FifuDb();
    $aux = $db->get_count_urls_with_metadata()[0];
    return $aux ? $aux->amount : 0;
}

function fifu_db_count_urls_without_metadata() {
    $db = new FifuDb();
    $aux = $db->get_count_urls_without_metadata()[0];
    return $aux ? $aux->amount : 0;
}

/* count: urls */

function fifu_db_count_urls() {
    $db = new FifuDb();
    $aux = $db->get_count_urls()[0];
    return $aux ? $aux->amount : 0;
}

/* clean metadata */

function fifu_db_enable_clean() {
    $db = new FifuDb();
    $db->enable_clean();
}

/* delete all urls */

function fifu_db_delete_all() {
    $db = new FifuDb();
    return $db->delete_all();
}

/* set autoload to no */

function fifu_db_update_autoload() {
    $db = new FifuDb();
    $db->update_autoload();
}

/* save post */

function fifu_db_update_fake_attach_id($post_id) {
    $db = new FifuDb();
    $db->update_fake_attach_id($post_id);
}

function fifu_db_update_fake_attach_id_gallery($post_id) {
    $db = new FifuDb();
    $db->update_fake_attach_id_gallery($post_id);
}

/* save category */

function fifu_db_ctgr_update_fake_attach_id($term_id) {
    $db = new FifuDb();
    $db->ctgr_update_fake_attach_id($term_id);
}

/* default url */

function fifu_db_create_attachment($url) {
    $db = new FifuDb();
    return $db->create_attachment($url);
}

function fifu_db_set_default_url() {
    $db = new FifuDb();
    return $db->set_default_url();
}

function fifu_db_update_default_url($url) {
    $db = new FifuDb();
    return $db->update_default_url($url);
}

function fifu_db_delete_default_url() {
    $db = new FifuDb();
    return $db->delete_default_url();
}

/* delete post */

function fifu_db_before_delete_post($post_id) {
    $db = new FifuDb();
    $db->before_delete_post($post_id);
}

function fifu_db_delete_category_image($post_id) {
    $db = new FifuDb();
    $db->delete_category_image($post_id);
}

/* number of posts */

function fifu_db_number_of_posts() {
    $db = new FifuDb();
    return $db->get_number_of_posts();
}

/* all urls */

function fifu_db_get_featured_and_gallery_urls($post_id) {
    $db = new FifuDb();
    return $db->get_featured_and_gallery_urls($post_id);
}

function fifu_db_delete_featured_and_gallery_urls($post_id) {
    $db = new FifuDb();
    return $db->delete_featured_and_gallery_urls($post_id);
}

/* speed up */

function fifu_db_get_all_urls($page) {
    $db = new FifuDb();
    return $db->get_all_urls($page);
}

function fifu_get_all_internal_urls() {
    $db = new FifuDb();
    return $db->get_all_internal_urls();
}

function fifu_db_get_posts_with_internal_featured_image($page) {
    $db = new FifuDb();
    return $db->get_posts_with_internal_featured_image($page);
}

function fifu_get_posts_su($storage_ids) {
    $db = new FifuDb();
    return $db->get_posts_su($storage_ids);
}

function fifu_add_urls_su($bucket_id, $thumbnails) {
    $db = new FifuDb();
    return $db->add_urls_su($bucket_id, $thumbnails);
}

function fifu_ctgr_add_urls_su($bucket_id, $thumbnails) {
    $db = new FifuDb();
    return $db->ctgr_add_urls_su($bucket_id, $thumbnails);
}

function fifu_remove_urls_su($bucket_id, $thumbnails, $urls, $video_urls) {
    $db = new FifuDb();
    return $db->remove_urls_su($bucket_id, $thumbnails, $urls, $video_urls);
}

function fifu_ctgr_remove_urls_su($bucket_id, $thumbnails, $urls, $video_urls) {
    $db = new FifuDb();
    return $db->ctgr_remove_urls_su($bucket_id, $thumbnails, $urls, $video_urls);
}

function fifu_backup_att_ids($post_ids) {
    $db = new FifuDb();
    $db->backup_att_ids($post_ids);
}

function fifu_ctgr_backup_att_ids($term_ids) {
    $db = new FifuDb();
    $db->ctgr_backup_att_ids($term_ids);
}

function fifu_delete_att_ids($post_ids) {
    $db = new FifuDb();
    $db->delete_att_ids($post_ids);
}

function fifu_ctgr_delete_att_ids($term_ids) {
    $db = new FifuDb();
    $db->ctgr_delete_att_ids($term_ids);
}

function fifu_add_custom_fields($values) {
    $db = new FifuDb();
    return $db->add_custom_fields($values);
}

function fifu_ctgr_add_custom_fields($values) {
    $db = new FifuDb();
    return $db->ctgr_add_custom_fields($values);
}

function fifu_db_get_internal_urls($post_ids) {
    $db = new FifuDb();
    return $db->get_internal_urls($post_ids);
}

function fifu_db_get_ctgr_internal_urls($term_ids) {
    $db = new FifuDb();
    return $db->get_ctgr_internal_urls($term_ids);
}

function fifu_db_count_available_images() {
    $db = new FifuDb();
    return $db->count_available_images();
}

/* invalid media */

function fifu_db_create_table_invalid_media_su() {
    $db = new FifuDb();
    return $db->create_table_invalid_media_su();
}

function fifu_db_insert_invalid_media_su($url) {
    $db = new FifuDb();
    return $db->insert_invalid_media_su($url);
}

function fifu_db_delete_invalid_media_su($url) {
    $db = new FifuDb();
    return $db->delete_invalid_media_su($url);
}

function fifu_db_get_attempts_invalid_media_su($url) {
    $db = new FifuDb();
    return $db->get_attempts_invalid_media_su($url);
}

/* schedule */

function fifu_db_get_all_posts_without_meta() {
    $db = new FifuDb();
    return $db->get_all_posts_without_meta();
}

function fifu_db_get_categories_without_meta() {
    $db = new FifuDb();
    return $db->get_categories_without_meta();
}

function fifu_db_get_post_types_without_featured_image($post_types) {
    $db = new FifuDb();
    return $db->get_post_types_without_featured_image($post_types);
}

function fifu_db_get_isbns_without_featured_image() {
    $db = new FifuDb();
    return $db->get_isbns_without_featured_image();
}

function fifu_db_get_finders_without_featured_image() {
    $db = new FifuDb();
    return $db->get_finders_without_featured_image();
}

function fifu_db_get_tags_without_featured_image() {
    $db = new FifuDb();
    return $db->get_tags_without_featured_image();
}

/* get last urls */

function fifu_db_get_last($meta_key) {
    $db = new FifuDb();
    return $db->get_last($meta_key);
}

function fifu_db_get_last_image() {
    $db = new FifuDb();
    return $db->get_last_image();
}

/* wordpress importer */

function fifu_db_delete_thumbnail_id_without_attachment() {
    $db = new FifuDb();
    return $db->delete_thumbnail_id_without_attachment();
}

/* att_id */

function fifu_db_get_att_id($post_parent, $url, $is_ctgr) {
    $db = new FifuDb();
    return $db->get_att_id($post_parent, $url, $is_ctgr);
}

/* wordpress upgrade */

function fifu_db_fix_guid() {
    $db = new FifuDb();
    return $db->fix_guid();
}

/* media library */

function fifu_db_get_posts_types_with_url() {
    $db = new FifuDb();
    return $db->get_posts_types_with_url();
}

function fifu_db_get_image_gallery_urls($post_id) {
    $db = new FifuDb();
    return $db->get_image_gallery_urls($post_id);
}

function fifu_db_get_terms_with_url() {
    $db = new FifuDb();
    return $db->get_terms_with_url();
}

function fifu_db_create_table_md5() {
    $db = new FifuDb();
    return $db->create_table_md5();
}

function fifu_db_get_thumbnail_id_by_md5($url) {
    $db = new FifuDb();
    $db->create_table_md5();
    return $db->get_thumbnail_id_by_md5($url);
}

function fifu_db_delete_md5_by_thumbnail_id($thumbnail_id) {
    $db = new FifuDb();
    return $db->delete_md5_by_thumbnail_id($thumbnail_id);
}

function fifu_db_insert_md5($url, $thumbnail_id) {
    $db = new FifuDb();
    return $db->insert_md5($url, $thumbnail_id);
}

/* video */

function fifu_db_create_table_video_oembed() {
    $db = new FifuDb();
    return $db->create_table_video_oembed();
}

function fifu_db_insert_video_oembed($video_url, $image_url, $embed_url) {
    if ($video_url && $image_url && $embed_url) {
        $db = new FifuDb();
        $db->delete_video_oembed_by_image_url($image_url);
        return $db->insert_video_oembed($video_url, $image_url, $embed_url);
    }
}

function fifu_db_delete_video_oembed_by_video_url($video_url) {
    $db = new FifuDb();
    return $db->delete_video_oembed_by_video_url($video_url);
}

function fifu_db_delete_video_oembed_by_image_url($image_url) {
    $db = new FifuDb();
    return $db->delete_video_oembed_by_image_url($image_url);
}

function fifu_db_get_image_url_by_video_url($video_url) {
    $db = new FifuDb();
    return $db->get_image_url_by_video_url($video_url);
}

function fifu_db_get_embed_url_by_image_url($image_url) {
    $db = new FifuDb();
    return $db->get_embed_url_by_image_url($image_url);
}

function fifu_db_get_embed_url_by_video_url($video_url) {
    $db = new FifuDb();
    return $db->get_embed_url_by_video_url($video_url);
}

function fifu_db_video_oembed_exists($video_url) {
    $db = new FifuDb();
    return $db->video_oembed_exists($video_url);
}

/* fifu gallery */

function fifu_db_get_variation_attributes($post_id) {
    $db = new FifuDb();
    return $db->get_variation_attributes($post_id);
}

function fifu_db_get_variation_att_ids($post_id) {
    $db = new FifuDb();
    return $db->get_variation_att_ids($post_id);
}

/* grid */

function fifu_db_get_slider_urls($post_id) {
    $db = new FifuDb();
    return $db->get_slider_urls($post_id);
}

/* database info */

function fifu_db_get_guid_size() {
    $db = new FifuDb();
    return $db->get_guid_size();
}

/* product gallery */

function fifu_get_gallery_urls($post_id) {
    $db = new FifuDb();
    return $db->get_gallery_urls($post_id);
}

function fifu_get_gallery_alts($post_id) {
    $db = new FifuDb();
    return $db->get_gallery_alts($post_id);
}

/* aawp plugin */

function fifu_get_aawp_asins($type, $keywords) {
    $db = new FifuDb();
    return $db->get_aawp_asins($type, $keywords);
}

function fifu_get_aawp_image_ids($asin) {
    $db = new FifuDb();
    return $db->get_aawp_image_ids($asin);
}

