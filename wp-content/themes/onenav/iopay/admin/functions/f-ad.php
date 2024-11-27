<?php
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2023-03-12 15:51:59
 * @LastEditors: iowen
 * @LastEditTime: 2023-03-25 03:13:18
 * @FilePath: \onenav\iopay\admin\functions\f-ad.php
 * @Description: 
 */

class io_auto_ad_list{

	private $items;
	private $column_info;
	private $per_page = 0;
    private $page_id = '';
    

    public function __construct( $items , $column_info ){
        $this->items = $items;
        $this->column_info = $column_info;
        $this->page_id = 'io_ad';
	}
    /**
     * 生成 tbody 元素。
     */
    public function display_rows_or_placeholder() {
        if ( $this->items ) { 
            foreach ($this->items as $item ) {
                echo '<tr>';
                $this->single_row_columns( $item );
                echo '</tr>';
            }
        } else {
            echo '<tr class="no-items"><td class="colspanchange" colspan="' . io_get_column_count(get_column_info()) . '">';
            echo '没有内容';
            echo '</td></tr>';
        }
    }
    /**
     * 生成一行表格内容。
     *
     * @param object|array $item 当前的项目
     */
    public function single_row_columns($item ) {
        list( $columns, $hidden, $sortable, $primary ) = $this->column_info;
        foreach ( $columns as $column_name => $column_display_name ) {
            $classes = "$column_name column-$column_name";
            if ( $primary === $column_name ) {
                $classes .= ' has-row-actions column-primary';
            }
    
            if ( in_array( $column_name, $hidden, true ) ) {
                $classes .= ' hidden';
            }
            
            $data = 'data-colname="' . esc_attr( wp_strip_all_tags( $column_display_name ) ) . '"';
    
            $attributes = "class='$classes' $data";
    
            if ( 'cb' === $column_name ) {
                echo '<th scope="row" class="check-column">';
                echo $this->column_cb($item);
                echo '</th>';
            } elseif ( method_exists( $this,  '_column_' . $column_name ) ) {
                echo call_user_func(
                    array( $this, '_column_' . $column_name ),
                    $item,
                    $classes,
                    $data,
                    $primary
                );
            } elseif ( method_exists( $this,  'column_' . $column_name ) ) {
                echo "<td $attributes>";
                echo call_user_func(  array( $this, 'column_' . $column_name ), $item );
                echo $this->handle_row_actions( $item, $column_name, $primary );
                echo '</td>';
            } else {
                echo "<td $attributes>";
                echo $this->column_default( $item, $column_name );
                echo $this->handle_row_actions( $item, $column_name, $primary );
                echo '</td>';
            }
        }
    }


    /**
     * 添加动作按钮
     * 
     * @param mixed $item
     * @param mixed $column_name
     * @param mixed $primary
     * @return string
     */
    protected function handle_row_actions($item, $column_name, $primary){
        if ($primary !== $column_name) {
            return '';
        }
        $id      = $item['token'];
        $title   = $item['name'];
        $actions = array();

        $actions['edit']   = sprintf(
            '<a href="%s" class="ajax-get-model" aria-label="%s">%s</a>',
            wp_nonce_url(add_query_arg(array('action' => 'edit', 'id' => $id, 'ajax' => 1), admin_url('admin.php?page='.$this->page_id)), $this->page_id.'_action'),
            '编辑' . $title,
            '编辑'
        );
        $actions['delete'] = sprintf(
            '<a href="%s" aria-label="%s">%s</a>',
            wp_nonce_url(add_query_arg(array('action' => 'delete', 'id' => $id), admin_url('admin.php?page='.$this->page_id)), $this->page_id.'_action'),
            '删除' . $title,
            '删除'
        );
        if (!isset($item['check']) || !$item['check']) {
            $actions['check'] = sprintf(
                '<a href="%s" aria-label="%s">%s</a>',
                wp_nonce_url(add_query_arg(array('action' => 'check', 'id' => $id), admin_url('admin.php?page='.$this->page_id)), $this->page_id.'_action'),
                '通过审核' . $title,
                '通过审核'
            );
        }

        return row_actions($actions);
    }

	public function column_loc($item){
		return iopay_get_auto_loc_name($item['loc']);
	}
	public function column_status($item){
		return $item['status']?'已支付':'未支付';
	}
	public function column_check($item){
        if (isset($item['check'])) {
            return $item['check'] ? '已审核' : '未审核';
        }
        return '未审核';
	}

	public function column_default($item, $column_name){
		return $item[$column_name];
	}

	public function column_cb($item){
        $id = $item['token'];
		$name = isset($item['name'])?strip_tags($item['name']):$id;
		return '<label class="screen-reader-text" for="cb-select-' . $id . '">' . sprintf( __( 'Select %s' ), $name ) . '</label>'
				. '<input type="checkbox" name="ids[]" value="' . $id . '" id="cb-select-' . $id . '" />';
	}

}
