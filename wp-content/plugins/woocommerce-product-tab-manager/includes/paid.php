<?php
class BeRocket_tab_manager_Paid extends BeRocket_plugin_variations {
    public $plugin_name = 'tab_manager';
    public $version_number = 20;
    public $types;
    function __construct() {
        parent::__construct();
        add_filter('brfr_berocket_tab_manager_tab_editor_cp_tab_qa', array($this, 'section_cp_tab_qa'), $this->version_number, 4);
        add_filter('brfr_data_berocket_tab_manager_tab_editor', array($this, 'custom_post_tab_data'), $this->version_number);
        add_filter('berocket_get_custom_tab_echo_content', array($this, 'echo_additional_content'), $this->version_number, 2);
        add_filter('berocket_tab_manager_location_editor_conditions_list', array( $this, 'condition_types'), $this->version_number);
        add_shortcode('brtabm_display_post_meta', array($this, 'shortcode_display_post_meta'));
    }

    public function condition_types($conditions) {
        $conditions[] = 'condition_product_category';
        $conditions[] = 'condition_product_featured';
        $conditions[] = 'condition_product_stockstatus';
        $conditions[] = 'condition_product_price';
        $conditions[] = 'condition_product_age';
        $conditions[] = 'condition_product_type';
        $conditions[] = 'condition_product_attribute';
        return $conditions;
    }

    function echo_additional_content($echo_content, $id) {
        $BeRocket_tab_manager = BeRocket_tab_manager::getInstance();
        $options = $BeRocket_tab_manager->get_option();
        $BeRocket_tab_manager_product_tab = BeRocket_tab_manager_product_tab::getInstance();
        $id = intval($id);
        $tab_setting = $BeRocket_tab_manager_product_tab->get_option($id);
        ob_start();
        if ( $tab_setting['additional'] == 'faq' && isset($tab_setting['additional_faq']) && is_array($tab_setting['additional_faq']) ) {
            wp_enqueue_script('jquery-ui-core');
            wp_enqueue_script('jquery-ui-accordion');
            wp_enqueue_script('jquery-ui-tabs');
            echo '<div class="br_qa_tabs">';
            foreach ($tab_setting['additional_faq'] as $qa) {
                echo '<h3>'.$qa['q'].'</h3>';
                echo '<div>'.$qa['a'].'</div>';
            }
            echo '</div>';
            ?>
            <script>jQuery(document).ready( function () {
                    jQuery('.br_qa_tabs').accordion({
                        heightStyle: "content"
                    });
                });</script>
            <style>
                .br_qa_tabs.ui-accordion .ui-accordion-header {
                <?php if( ! empty($options['styles']['border_color']) ) echo 'border: 1px solid '.($options['styles']['border_color'][0] != '#' ? '#'.$options['styles']['border_color'] : $options['styles']['border_color']).'!important;'; ?>
                <?php if( ! empty($options['styles']['question_color']) ) echo 'background-color:'.($options['styles']['question_color'][0] != '#' ? '#'.$options['styles']['question_color'] : $options['styles']['question_color']).';'; ?>
                <?php if( ! empty($options['styles']['question_size']) ) echo 'font-size:'.$options['styles']['question_size'].'px;'; ?>
                }
                .br_qa_tabs.ui-accordion .ui-accordion-header.ui-state-active {
                <?php echo 'background-color:'.( ! empty($options['styles']['q_opened_color']) ? ($options['styles']['q_opened_color'][0] != '#' ? '#'.$options['styles']['q_opened_color'] : $options['styles']['q_opened_color']) : 'initial').';'; ?>
                }
                .br_qa_tabs .ui-accordion-content {
                <?php if( ! empty($options['styles']['border_color']) ) echo 'border-color:'.($options['styles']['border_color'][0] != '#' ? '#'.$options['styles']['border_color'] : $options['styles']['border_color']).';'; ?>
                <?php if( ! empty($options['styles']['answer_size']) ) echo 'font-size:'.$options['styles']['answer_size'].'px;'; ?>
                }
            </style>
            <?php
        } elseif( $tab_setting['additional'] == 'product_list' && $tab_setting['additional_product']['count'] > 0 ) {
            if($tab_setting['additional_product']['type'] == 'products') {
                $products = $tab_setting['additional_product']['products'];
                $args = array(
                    'post_type'         => array('product', 'product_variation'),
                    'post__in'          => $products,
                    'posts_per_page'    => $tab_setting['additional_product']['count'],
                    'orderby'           => 'rand'
                );
                if( ! $tab_setting['additional_product']['products'] ) {
                    return;
                }
            } elseif( $tab_setting['additional_product']['type'] == 'category' ) {
                if( ! $tab_setting['additional_product']['category'] ) {
                    return;
                }
                $args = array(
                    'post_type'         => array('product', 'product_variation'),
                    'posts_per_page'    => $tab_setting['additional_product']['count'],
                    'orderby'           => 'rand',
                    'tax_query'         => array(
                        'relation'          => 'AND',
                        array(
                            'taxonomy'          => 'product_cat',
                            'field'             => 'term_id',
                            'terms'             => $tab_setting['additional_product']['category'],
                            'operator'          => 'IN'
                        ),
                    ),
                );
            }
            $loop = new WP_Query( $args );
            woocommerce_product_loop_start();
            do_action('woocommerce_before_shop_loop_products');
            $x = 0;
            global $wp_query;
            $old_wp_query = $wp_query;
            $wp_query = $loop;

            if (have_posts()) : while (have_posts()) : the_post(); global $product, $post;
                $product = wc_get_product(get_the_ID());
                $post = get_post( get_the_ID() );
                if ( !$product->is_visible() ) continue;
                wc_get_template_part( 'content', 'product' );
            endwhile; endif;
            do_action('woocommerce_after_shop_loop_products');
            woocommerce_product_loop_end();
            $wp_query = $old_wp_query;
        }
        $echo_content .= ob_get_clean();
        return $echo_content;
    }
    public function settings_page($data) {
        $data['Styles'] = array(
            array(
                "section"  => "header",
                "type"     => 3,
                "label"    => __("Question/Answer list style", "product-tabs-manager-for-woocommerce"),
            ),
            'question_size' => array(
                "label"     => __('Question font size', 'product-tabs-manager-for-woocommerce'),
                "type"      => "number",
                "name"      => array("styles", "question_size"),
                "value"     => '',
                "extra"     => 'min="0"',
            ),
            'answer_size' => array(
                "label"     => __('Answer font size', 'product-tabs-manager-for-woocommerce'),
                "type"      => "number",
                "name"      => array("styles", "answer_size"),
                "value"     => '',
                "extra"     => 'min="0"',
            ),
            'border_color' => array(
                "label"     => __('Border color', 'product-tabs-manager-for-woocommerce'),
                "type"      => "color",
                "name"      => array("styles", "border_color"),
                "value"     => '',
            ),
            'question_color' => array(
                "label"     => __('Background question color', 'product-tabs-manager-for-woocommerce'),
                "type"      => "color",
                "name"      => array("styles", "question_color"),
                "value"     => '',
            ),
            'q_opened_color' => array(
                "label"     => __('Background opened question color', 'product-tabs-manager-for-woocommerce'),
                "type"      => "color",
                "name"      => array("styles", "q_opened_color"),
                "value"     => '',
            ),
        );
        return $data;
    }
    public function settings_tabs($data) {
        $data = berocket_insert_to_array(
            $data, 
            'General', 
            array(
                'Styles' => array(
                    'icon' => 'eye',
                    'name' => __('Styles', 'product-tabs-manager-for-woocommerce'),
                )
            )
        );
        return $data;
    }

    function custom_post_tab_data($data) {
        $category_names = array();
        $categories     = get_terms('product_cat');
        foreach( $categories as $category ) {
            $category_names[] = array( 'value' => $category->term_id, 'text' => __( $category->name, 'woocommerce') );
        }
        $data['General'] = array_merge($data['General'], array(
            'additional_info' => array(
                "type"     => "selectbox",
                "label"    => __('Type of additional info', 'product-tabs-manager-for-woocommerce'),
                "name"     => "additional",
                "options"  => array(
                    array('value' => '', 'text' => __('No additional info', 'product-tabs-manager-for-woocommerce')),
                    array('value' => 'faq', 'text' => __('Question/Answer list', 'product-tabs-manager-for-woocommerce')),
                    array('value' => 'product_list', 'text' => __('Products list', 'product-tabs-manager-for-woocommerce')),
                ),
                "class"    => 'br_additional_selector',
                "value"    => '',
            ),
            'products_type' => array(
                "type"     => "selectbox",
                "label"    => __('Type of products', 'product-tabs-manager-for-woocommerce'),
                "name"     => array("additional_product", "type"),
                "options"  => array(
                    array('value' => 'products', 'text' => __('Products', 'product-tabs-manager-for-woocommerce')),
                    array('value' => 'category', 'text' => __('Category', 'product-tabs-manager-for-woocommerce')),
                ),
                "value"    => '',
                "class"    => 'type_to_display',
                "tr_class" => 'additional_ additional_product_list',
            ),
            'products_view' => array(
                "type"     => "selectbox",
                "label"    => __('Products category', 'product-tabs-manager-for-woocommerce'),
                "name"     => array("additional_product", "category"),
                "options"  => $category_names,
                "value"    => '',
                "tr_class" => 'additional_ type_to_display_ type_to_display_category',
            ),
            'product_display' => array(
                "type"     => "products",
                "label"    => __('Products to display', 'product-tabs-manager-for-woocommerce'),
                "name"     => array("additional_product", "products"),
                "value"    => '',
                "tr_class" => 'additional_ type_to_display_ type_to_display_products',
            ),
            'product_display_count' => array(
                "type"     => "number",
                "label"    => __('Product count to display', 'product-tabs-manager-for-woocommerce'),
                "name"     => array("additional_product", "count"),
                "value"    => '',
                "tr_class" => 'additional_ type_to_display_ type_to_display_products type_to_display_category',
            ),
            'qa_display' => array(
                "section"  => "cp_tab_qa",
                "tr_class" => "additional_ additional_faq",
            ),
        ));
        return $data;
    }

    function section_cp_tab_qa($html, $item, $tab_set, $name){
        $html = '
        <th>FAQ</th>
        <td>
            <div>
                <div class="qa_fields">';
                $i = 0;
                foreach ( $tab_set['additional_faq'] as $qa ) {
                    $html .= '<h3><span class="qa_question_text">'. $qa['q'] . '</span></h3>';
                    $html .= '<div class="qa_block"><div class="qa_block_content">
                                <div>'.
                                    __( 'Question', 'product-tabs-manager-for-woocommerce' ).
                                    '<input class="qa_question_input" name="br_product_tab[additional_faq]['.$i.'][q]" type="text" value="'.$qa['q'].'">
                                </div>
                                <div>' .
                                     __( 'Answer', 'product-tabs-manager-for-woocommerce' ) .
                                     '<textarea name="br_product_tab[additional_faq]['.$i.'][a]" type="text">'.preg_replace('/\<br(\s*)?\/?\>/i', "", $qa['a']).'</textarea>
                                </div>
                                <div>
                                    <input type="button" class="remove_faq button tiny-button" value="'. __( 'Remove', 'product-tabs-manager-for-woocommerce' ).'">
                                </div>
                              </div></div>';
                    $i++;
                }
        $html .= '
                </div>
                <input type="button" class="add_faq button" value="'. __( 'Add', 'product-tabs-manager-for-woocommerce' ).'">
            </div>
        ';

        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-accordion');
        wp_enqueue_script('jquery-ui-tabs');

        $html .= '
            <script>
                var additional_faq_i = ' . $i . ';
                jQuery(document).on("click", ".additional_faq .add_faq", function() {
                    var $parent = jQuery(this).parents(".additional_faq");
                    var html = \'<h3><span class="qa_question_text"><i><small>Please enter question</small></i></span></h3>\';
                    html += \'<div class="qa_block"><div class="qa_block_content"><div>' .
                            __( 'Question', 'product-tabs-manager-for-woocommerce' ) . '<input class="qa_question_input" name="br_product_tab[additional_faq][\'+additional_faq_i+\'][q]" type="text" value=""></div><div>' .
                            __( 'Answer', 'product-tabs-manager-for-woocommerce' ) . '<textarea name="br_product_tab[additional_faq][\'+additional_faq_i+\'][a]" type="text"></textarea></div><div>\'+
                            \'<input type="button" class="remove_faq button tiny-button" value="'. __( 'Remove', 'product-tabs-manager-for-woocommerce' ).'"></div></div></div>\';
                    var new_el = jQuery(html);
                    $parent.find(\'.qa_fields\').append(new_el);
                    jQuery(\'.qa_fields\').accordion("refresh");
                    additional_faq_i++;
                    jQuery( ".qa_fields" ).accordion({ active: additional_faq_i-1 });
                });
                jQuery(document).on("click", ".additional_faq .remove_faq", function() {
                    $pel = jQuery(this).parents(".qa_block");
                    $pel.prev().remove();
                    $pel.remove();
                    jQuery(\'.qa_fields\').accordion("refresh");
                });
                jQuery(\'.br_additional_selector\').change(br_additional_selector_change);
                function br_additional_selector_change() {
                    jQuery(\'.additional_\').hide();
                    if(jQuery(\'.br_additional_selector\').val()) {
                        jQuery(\'.additional_\'+jQuery(\'.br_additional_selector\').val()).show();
                        if (jQuery(\'.br_additional_selector\').val() == \'product_list\') {
                            jQuery(\'.type_to_display\').change();
                        }
                    }
                }
                br_additional_selector_change();
                jQuery(\'.type_to_display\').change(function() {
                    jQuery(\'.type_to_display_\').hide();
                    if(jQuery(this).val()) {
                        jQuery(\'.type_to_display_\'+jQuery(this).val()).show();
                    }
                });
                jQuery(document).on(\'keyup change\', \'.qa_question_input\', function(){
                    val = jQuery(this).val();
                    if ( val.length == 0 ) val = "&nbsp;";
                    jQuery(this).parents(\'.qa_block\').prev().find(\'span.qa_question_text\').html(val);
                });
                jQuery(document).ready( function () {
                    jQuery(\'.qa_fields\').accordion({
                        heightStyle: "content",
                        collapsible: true,
                        active: false
                    });
                    jQuery(\'.type_to_display\').change();
                    jQuery(\'.br_additional_selector\').change();
                });
            </script>';
        $html .= '</td>';

        return $html;
    }
    public function get_option($options = false) {
        if( $options === false ) {
            $BeRocket_force_sell = BeRocket_force_sell::getInstance();
            $options = $BeRocket_force_sell->get_option();
        }
        return $options;
    }
    public function shortcode_display_post_meta($args = array()) {
        $args = array_merge(array(
            'meta' => ''
        ),$args);
        global $post;
        $value = '';
        if( ! empty($args['meta']) && is_object($post) && property_exists($post, 'ID') ) {
            $value = get_post_meta($post->ID, $args['meta'], true);
            if( empty($value) ) {
                $value = '';
            }
        }
        return $value;
    }
}
new BeRocket_tab_manager_Paid();
