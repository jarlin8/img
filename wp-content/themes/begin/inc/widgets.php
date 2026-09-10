<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// 最新文章
class new_cat extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'new_cat',
			'description' =>'显示全部分类或某个分类的最新文章',
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'new_cat', '最新文章', $widget_ops );
	}

	public function zm_defaults() {
		return array(
			'show_thumbs'=> 1,
			'show_icon'  => 0,
			'show_time'  => 1,
			'cat_child'  => 1,
			'title_z'    => '',
			'show_svg'   => '',
			'numposts'   => 5,
			'cat'        => '',
			'title'      => '最新文章',
			'out_post'   => '',
		);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$defaults = $this -> zm_defaults();
		$title_w = title_i_w();
		$instance = wp_parse_args( ( array ) $instance, $defaults );
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance );
		$hideTitle = !empty( $instance['hideTitle'] ) ? true : false;
		$titleUrl = empty( $instance['titleUrl'] ) ? '' : $instance['titleUrl'];
		$newWindow = !empty( $instance['newWindow'] ) ? true : false;
		if ( zm_get_option( 'more_im' ) ) {
			$more_i = '<span class="more-i more-im"><span></span><span></span><span></span></span>';
		} else {
			$more_i = '<span class="more-i"><span></span><span></span><span></span></span>';
		}
		echo $before_widget;
		if ( $newWindow) $newWindow = "target='_blank'";
			if ( ! $hideTitle && $title ) {
				if ( $titleUrl ) $title = "<a href='$titleUrl' $newWindow>$title $more_i</a>";
			}
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title; 
?>

<?php if ( zm_get_option( 'cat_icon' ) && $instance['show_icon'] ) { ?>
	<h3 class="widget-title-icon cat-w-icon">
		<a href="<?php echo $titleUrl; ?>" rel="bookmark">
			<?php if ( get_option( 'zm_taxonomy_icon' . $instance['cat'] ) ) { ?><i class="t-icon <?php echo zm_taxonomy_icon_code( $instance['cat'] ); ?>"></i><?php } ?>
			<?php if ( get_option( 'zm_taxonomy_svg' . $instance['cat'] ) ) { ?><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo zm_taxonomy_svg_code( $instance['cat'] ); ?>"></use></svg><?php } ?>
			<?php echo $instance['title_z']; ?><?php more_i(); ?>
		</a>
	</h3>
<?php } ?>

<?php if ( $instance['show_thumbs'] ) { ?>
<div class="new_cat">
<?php } else { ?>
<div class="post_cat">
<?php } ?>
	<ul>
	<?php 
		global $post;

		if ( is_single() ) {
			$not = explode( ',', $instance['out_post'] . ',' . $post->ID );
		} else {
			$not = explode( ',', $instance['out_post'] );
		}

		$and = $instance['cat_child'] ? 'cat' : 'category__and';

		$q =  new WP_Query(array(
			'ignore_sticky_posts' => 1,
			'showposts'           => $instance['numposts'],
			'post__not_in'        => $not,
			$and                  => $instance['cat'],
		));
	?>

	<?php while ( $q->have_posts() ) : $q->the_post(); ?>
		<?php if ( $instance['show_thumbs'] ) { ?>
			<li>
				<span class="thumbnail">
					<?php echo zm_thumbnail(); ?>
				</span>
				<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark" <?php echo goal(); ?>><?php the_title(); ?></a></span>
				<?php grid_meta(); ?>
				<?php views_span(); ?>
			</li>
		<?php } else { ?>
			<li class="only-title<?php if ($instance['show_time']) { ?> only-title-date<?php } ?>">
				<?php if ( $instance['show_time'] ) { ?><?php grid_meta(); ?><?php } ?>
				<?php the_title( sprintf( '<a class="srm get-icon" href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a>' ); ?>
			</li>
		<?php } ?>
		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>
	</ul>
</div>

<?php
	echo $after_widget;
}

function update( $new_instance, $old_instance ) {
	$instance = $old_instance;
	$instance = array();
	$instance['show_thumbs'] = ! empty( $new_instance['show_thumbs'] ) ? 1 : 0;
	$instance['cat_child']   = ! empty( $new_instance['cat_child'] ) ? 1 : 0;
	$instance['show_icon']   = ! empty( $new_instance['show_icon'] ) ? 1 : 0;
	$instance['show_time']   = ! empty( $new_instance['show_time'] ) ? 1 : 0;
	$instance['hideTitle']   = ! empty( $new_instance['hideTitle'] ) ? 1 : 0;
	$instance['newWindow']   = ! empty( $new_instance['newWindow'] ) ? 1 : 0;
	$instance['title']       = strip_tags( $new_instance['title'] );
	$instance['title_z']     = strip_tags( $new_instance['title_z'] );
	$instance['titleUrl']    = strip_tags( $new_instance['titleUrl'] );
	$instance['out_post']    = strip_tags( $new_instance['out_post'] );
	$instance['numposts']    = $new_instance['numposts'];
	$instance['cat']         = $new_instance['cat'];
	return $instance;
}

function form( $instance ) {
	$defaults = $this -> zm_defaults();
	$instance = wp_parse_args( ( array ) $instance, $defaults );
	$instance = wp_parse_args( ( array ) $instance,
		array(
			'title'    => '最新文章',
			'titleUrl' => '',
			'numposts' => 5,
			'cat'      => 0
		)
	);
		$titleUrl = $instance['titleUrl'];
		 ?> 

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'title_z' ); ?>">图标标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title_z' ); ?>" name="<?php echo $this->get_field_name( 'title_z' ); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'titleUrl' ); ?>">标题链接：</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'titleUrl' ); ?>" name="<?php echo $this->get_field_name( 'titleUrl' ); ?>" type="text" value="<?php echo $titleUrl; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'numposts' ); ?>">显示数量：</label>
			<input class="number-text" id="<?php echo $this->get_field_id( 'numposts' ); ?>" name="<?php echo $this->get_field_name( 'numposts' ); ?>" type="number" step="1" min="1" value="<?php echo $instance['numposts']; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'cat' ); ?>">选择分类：
			<?php wp_dropdown_categories( array( 'name' => $this->get_field_name( 'cat' ), 'show_option_all' => '全部分类', 'hide_empty'=> 0, 'hierarchical'=> 1, 'selected'=>$instance['cat'] ) ); ?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'out_post' ); ?>">排除的文章ID：</label>
			<textarea style="height:35px;" class="widefat" id="<?php echo $this->get_field_id( 'out_post' ); ?>" name="<?php echo $this->get_field_name( 'out_post' ); ?>"><?php echo stripslashes(htmlspecialchars(( $instance['out_post'] ), ENT_QUOTES)); ?></textarea>
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_thumbs' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_thumbs' ) ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_thumbs' ) ); ?>">显示缩略图</label>
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('cat_child') ); ?>" name="<?php echo esc_attr( $this->get_field_name('cat_child') ); ?>" <?php checked( (bool) $instance["cat_child"], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id('cat_child') ); ?>">显示子分类</label>
		</p>
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" class="checkbox" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
			<label for="<?php echo $this->get_field_id('newWindow'); ?>">在新窗口打开标题链接</label>
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_time' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_time' ) ); ?>" <?php checked( (bool) $instance["show_time"], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id('show_time') ); ?>">无缩略图显示时间</label>
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_icon' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_icon' ) ); ?>" <?php checked( (bool) $instance["show_icon"], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id('show_icon') ); ?>">显示分类图标</label>
		</p>
<?php }
}

add_action( 'widgets_init', 'new_cat_init' );
function new_cat_init() {
	register_widget( 'new_cat' );
}

// 分类文章（图片）
class img_cat extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'img_cat',
			'description' => '以图片形式调用一个分类的文章',
			'customize_selective_refresh' => true,
		);
		parent::__construct('img_cat', '分类图片', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_icon'   => 0,
			'title_z'     => '',
			'show_icon'   => '',
			'show_svg'    => '',
			'numposts'    => 4,
			'cat_child'   => 1,
			'cat'         => '',
			'show_title'  => 1,
			'title'       => '分类图片',
			'out_post'    => '',
		);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$defaults = $this -> zm_defaults();
		$title_w = title_i_w();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		$titleUrl = empty($instance['titleUrl']) ? '' : $instance['titleUrl'];
		$hideTitle = !empty($instance['hideTitle']) ? true : false;
		$newWindow = !empty($instance['newWindow']) ? true : false;
		if ( zm_get_option('more_im') ) {
			$more_i = '<span class="more-i more-im"><span></span><span></span><span></span></span>';
		} else {
			$more_i = '<span class="more-i"><span></span><span></span><span></span></span>';
		}
		echo $before_widget;
		if ($newWindow) $newWindow = "target='_blank'";
			if (!$hideTitle && $title) {
				if ($titleUrl) $title = "<a href='$titleUrl' $newWindow>$title $more_i</a>";
			}
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title; 
?>

<?php if ( zm_get_option( 'cat_icon' ) && $instance['show_icon'] ) { ?>
	<h3 class="widget-title-icon cat-w-icon">
		<a href="<?php echo $titleUrl; ?>" rel="bookmark">
			<?php if ( get_option( 'zm_taxonomy_icon' . $instance['cat'] ) ) { ?><i class="t-icon <?php echo zm_taxonomy_icon_code( $instance['cat'] ); ?>"></i><?php } ?>
			<?php if ( get_option( 'zm_taxonomy_svg' . $instance['cat'] ) ) { ?><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo zm_taxonomy_svg_code( $instance['cat'] ); ?>"></use></svg><?php } ?>
			<?php echo $instance['title_z']; ?><?php more_i(); ?>
		</a>
	</h3>
<?php } ?>

<div class="widget-flex-img">
	<?php
		global $post;
		if ( is_single() ) {
			$not = explode( ',', $instance['out_post'] . ',' . $post->ID );
		} else {
			$not = explode( ',', $instance['out_post'] );
		}

		$and = $instance['cat_child'] ? 'cat' : 'category__and';

		$q =  new WP_Query(array(
			'ignore_sticky_posts' => 1,
			'showposts'           => $instance['numposts'],
			'post__not_in'        => $not,
			$and                  => $instance['cat'],
		));
		$fl = isset( $instance['fl'] ) ? $instance['fl'] : '';
	?>
	<div class="wimg-main">
		<?php while ($q->have_posts()) : $q->the_post(); ?>
			<div class="wimg-item wimg-<?php echo $fl; ?>">
				<div class="wimg-area">
					<?php if ( $instance['show_title'] ) { ?>
						<div class="img-title"><a href="<?php the_permalink() ?>" rel="bookmark" <?php echo goal(); ?>><?php the_title( sprintf( '<span class="img-title-t over">' ), '</span>' ); ?></a></div>
					<?php } ?>
					<?php echo zm_thumbnail(); ?>
				</div>
			</div>
		<?php endwhile;?>
		<?php wp_reset_postdata(); ?>
		<div class="clear"></div>
	</div>
</div>

<?php
	echo $after_widget;
}

function update( $new_instance, $old_instance ) {
	$instance = $old_instance;
	$instance['show_icon'] = !empty($new_instance['show_icon']) ? 1 : 0;
	$instance['hideTitle'] = !empty($new_instance['hideTitle']) ? 1 : 0;
	$instance['newWindow'] = !empty($new_instance['newWindow']) ? 1 : 0;
	$instance['show_title'] = !empty($new_instance['show_title']) ? 1 : 0;
	$instance['title']     = strip_tags($new_instance['title']);
	$instance['title_z']   = strip_tags($new_instance['title_z']);
	$instance['titleUrl']  = strip_tags($new_instance['titleUrl']);
	$instance['out_post']  = strip_tags( $new_instance['out_post'] );
	$instance['cat_child']   = ! empty( $new_instance['cat_child'] ) ? 1 : 0;
	$instance['numposts']  = $new_instance['numposts'];
	$instance['cat']       = $new_instance['cat'];
	$instance['fl'] = ! empty( $new_instance['fl'] ) ? sanitize_text_field( $new_instance['fl'] ) : '';
	return $instance;
}

function form( $instance ) {
	$defaults = $this -> zm_defaults();
	$instance = wp_parse_args( (array) $instance, $defaults );
	$selected_option = isset( $instance['fl'] ) ? $instance['fl'] : '2';
	$instance = wp_parse_args( (array) $instance, array( 
		'title' => '分类图片',
		'titleUrl' => '',
		'title_z' => '',
		'numposts' => 4,
		'cat' => 0));
		$titleUrl = $instance['titleUrl'];
		?> 

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('titleUrl'); ?>">标题链接：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('titleUrl'); ?>" name="<?php echo $this->get_field_name('titleUrl'); ?>" type="text" value="<?php echo $titleUrl; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'numposts' ); ?>">显示数量：</label>
			<input class="number-text" id="<?php echo $this->get_field_id( 'numposts' ); ?>" name="<?php echo $this->get_field_name( 'numposts' ); ?>" type="number" step="1" min="1" value="<?php echo $instance['numposts']; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
			<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => '全部分类', 'hide_empty'=>0, 'hierarchical'=>1, 'selected'=>$instance['cat'])); ?></label>
		</p>
		<ul class="be-widget-radio">
			<li>
				<input type="radio" id="<?php echo $this->get_field_id( '2' ); ?>" name="<?php echo $this->get_field_name( 'fl' ); ?>" value="2" <?php checked( $selected_option, '2' ); ?>>
				<label for="<?php echo $this->get_field_id( '2' ); ?>">2栏</label>
			</li>
			<li>
				<input type="radio" id="<?php echo $this->get_field_id( '3' ); ?>" name="<?php echo $this->get_field_name( 'fl' ); ?>" value="3" <?php checked( $selected_option, '3' ); ?>>
				<label for="<?php echo $this->get_field_id( '3' ); ?>">3栏</label>
			</li>
			<li>
				<input type="radio" id="<?php echo $this->get_field_id( '4' ); ?>" name="<?php echo $this->get_field_name( 'fl' ); ?>" value="4" <?php checked( $selected_option, '4' ); ?>>
				<label for="<?php echo $this->get_field_id( '4' ); ?>">4栏</label>
			</li>
			<li>
				<input type="radio" id="<?php echo $this->get_field_id( '5' ); ?>" name="<?php echo $this->get_field_name( 'fl' ); ?>" value="5" <?php checked( $selected_option, '5' ); ?>>
				<label for="<?php echo $this->get_field_id( '5' ); ?>">5栏</label>
			</li>
			<li>
				<input type="radio" id="<?php echo $this->get_field_id( '6' ); ?>" name="<?php echo $this->get_field_name( 'fl' ); ?>" value="6" <?php checked( $selected_option, '6' ); ?>>
				<label for="<?php echo $this->get_field_id( '6' ); ?>">6栏</label>
			</li>
		</ul>
		<p>
			<label for="<?php echo $this->get_field_id( 'out_post' ); ?>">排除的文章ID：</label>
			<textarea style="height:35px;" class="widefat" id="<?php echo $this->get_field_id( 'out_post' ); ?>" name="<?php echo $this->get_field_name( 'out_post' ); ?>"><?php echo stripslashes(htmlspecialchars(( $instance['out_post'] ), ENT_QUOTES)); ?></textarea>
		</p>
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" class="checkbox" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
			<label for="<?php echo $this->get_field_id('newWindow'); ?>">在新窗口打开标题链接</label>
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('cat_child') ); ?>" name="<?php echo esc_attr( $this->get_field_name('cat_child') ); ?>" <?php checked( (bool) $instance["cat_child"], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id('cat_child') ); ?>">显示子分类</label>
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_icon') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_icon') ); ?>" <?php checked( (bool) $instance["show_icon"], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id('show_icon') ); ?>">显示分类图标</label>
		</p>
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id( 'show_title' ); ?>" class="checkbox" name="<?php echo $this->get_field_name( 'show_title' ); ?>" <?php checked( isset( $instance['show_title'] ) ? $instance['show_title'] : 0 ); ?> />
			<label for="<?php echo $this->get_field_id( 'show_title' ); ?>">文章标题</label>
		</p>
<?php }
}

add_action( 'widgets_init', 'img_cat_init' );
function img_cat_init() {
	register_widget( 'img_cat' );
}

// 近期留言
class recent_comments extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'recent_comments',
			'description' => '带头像的近期留言',
			'customize_selective_refresh' => true,
		);
		parent::__construct('recent_comments', '近期留言', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'ellipsis'   => 1,
			'title_z'    => '',
			'show_icon'  => '',
			'show_svg'   => '',
			'number'     => 5,
			'authornot'  => 1,
			'title'      => '近期留言',
		);
	}

	function widget($args, $instance) {
		extract($args);
		$title_w = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
		$authornot = strip_tags($instance['authornot']) ? absint( $instance['authornot'] ) : 1;
?>

<div id="message" class="message-widget gaimg<?php if ( ! $instance['ellipsis'] ) { ?> message-item<?php } ?>">
	<?php if ($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<ul>
		<?php 
		$no_comments = false;
		$avatar_size = 96;
		$comments_query = new WP_Comment_Query();
		if ( $instance["authornot"] ) {
			$comments = $comments_query->query( array_merge( array( 'number' => $number, 'status' => 'approve', 'type' => 'comments', 'post_status' => 'publish', 'author__not_in' => explode(',',$instance["authornot"]) ) ) );
		} else {
			$comments = $comments_query->query( array_merge( array( 'number' => $number, 'status' => 'approve', 'type' => 'comments', 'post_status' => 'publish' ) ) );
		}
		if ( $comments ) : foreach ( $comments as $comment ) : ?>

		<li class="message-item-li load">
			<a href="<?php echo get_permalink($comment->comment_post_ID); ?>#anchor-comment-<?php echo $comment->comment_ID; ?>" title="<?php _e( '发表在', 'begin' ); ?>：<?php echo get_the_title($comment->comment_post_ID); ?>" rel="external nofollow" <?php echo goal(); ?>>
				<?php if ( get_option( 'show_avatars' ) ) { ?>
					<?php if (zm_get_option('cache_avatar')) { ?>
						<?php echo begin_avatar( $comment->comment_author_email, $avatar_size, '', get_comment_author( $comment->comment_ID ) ); ?>
					<?php } else { ?>
						<?php if ( !zm_get_option( 'avatar_load' ) ) {
							echo get_avatar( $comment->comment_author_email, $avatar_size, '', get_comment_author( $comment->comment_ID ) );
						} else {
							echo '<img class="avatar photo" src="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=" alt="'. get_comment_author( $comment->comment_ID ) .'"  width="30" height="30" data-original="' . preg_replace(array('/^.+(src=)(\"|\')/i', '/(\"|\')\sclass=(\"|\').+$/i'), array('', ''), get_avatar( $comment->comment_author_email, $avatar_size, '', get_comment_author( $comment->comment_ID ) )) . '" />';
						} ?>
					<?php } ?>
				<?php } ?>
				<?php if ( zm_get_option( 'comment_vip' ) ) { ?>
					<?php
						$authoremail = get_comment_author_email( $comment );
						if ( email_exists( $authoremail ) ) {
							$commet_user_role = get_user_by( 'email', $authoremail );
							$comment_user_role = $commet_user_role->roles[0];
								if ( $comment_user_role !== zm_get_option('roles_vip') ) {
									echo '<span class="comment_author">' . get_comment_author( $comment->comment_ID ) . '</span>';
								} else {
									echo '<span class="comment_author message-widget-vip">' . get_comment_author( $comment->comment_ID ) . '</span>';
								}
						} else {
							echo '<span class="comment_author">' . get_comment_author( $comment->comment_ID ) . '</span>';
						}
					?>
				<?php } else { ?>
					<span class="comment_author"><?php echo get_comment_author( $comment->comment_ID ); ?></span>
				<?php } ?>
				<span class="say-comment"><?php echo convert_smilies( $comment->comment_content ); ?></span>
			</a>
		</li>

		<?php endforeach; else : ?>
			<li><?php _e('暂无留言', 'begin'); ?></li>
			<?php $no_comments = true;
		endif; ?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['ellipsis'] = !empty( $new_instance['ellipsis'] ) ? 1 : 0;
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['show_svg'] = strip_tags($new_instance['show_svg']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = strip_tags($new_instance['number']);
		$instance['authornot'] = strip_tags($new_instance['authornot']);
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '近期留言';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$number = strip_tags($instance['number']);
		$instance = wp_parse_args((array) $instance, array('authornot' => '1'));
		$authornot = strip_tags($instance['authornot']);
?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">单色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_svg'); ?>">彩色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_svg'); ?>" name="<?php echo $this->get_field_name('show_svg'); ?>" type="text" value="<?php echo $instance['show_svg']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('authornot'); ?>">排除管理员ID：</label>
		<p><input id="<?php echo $this->get_field_id( 'authornot' ); ?>" name="<?php echo $this->get_field_name( 'authornot' ); ?>" type="text" value="<?php echo $authornot; ?>" /></p>
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'ellipsis' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'ellipsis' ) ); ?>" <?php checked( ( bool ) $instance["ellipsis"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id( 'ellipsis' ) ); ?>">简化样式</label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>">显示数量：</label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

function recent_comments_init() {
	register_widget( 'recent_comments' );
}
add_action( 'widgets_init', 'recent_comments_init' );

// 热评文章
class hot_comment extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'hot_comment',
			'description' => '调用评论最多的文章',
			'customize_selective_refresh' => true,
		);
		parent::__construct('hot_comment', '热评文章', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_thumbs' => 1,
			'title_all'   => 1,
			'mycat'       => 0,
			'title_z'     => '',
			'show_icon'   => '',
			'show_svg'    => '',
			'number'      => 5,
			'days'        => 90,
			'title'       => '热评文章',
			'e_cat'       => '',
			'out_post'    => '',
		);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title_w = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
		$days = strip_tags($instance['days']) ? absint( $instance['days'] ) : 90;
?>

<?php if ( $instance['show_thumbs'] ) { ?>
<div class="new_cat">
<?php } else { ?>
<div id="hot_comment_widget" class="widget-li-icon<?php if ( ! $instance['title_all'] ) { ?> title-li-all<?php } ?>">
<?php } ?>
	<?php if ( $instance['show_icon'] ) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<ul>

			<?php
				if ( $instance['mycat'] ) {
					$cat = get_the_category();
					foreach( $cat as $key=>$category ){
						$catid = $category->term_id;
					}
				} else {
					$catid = '';
				}
				$review = new WP_Query( array(
					'post_type'           => array( 'post' ),
					'showposts'           => $number,
					'cat'                 => $catid,
					'category__not_in'    => explode( ',', $instance['e_cat'] ),
					'post__not_in'        => explode( ',', $instance['out_post'] ),
					'ignore_sticky_posts' => true,
					'orderby'             => 'comment_count',
					'order'               => 'DESC',
					'date_query'          => array(
						array(
							'after'       => ''.$days. 'month ago',
						),
					),
				) );
			?>
			
			<?php $i=0; while ( $review->have_posts() ): $review->the_post(); $i++; ?>
				<?php if ( $instance['show_thumbs'] ) { ?>
					<li>
						<span class="thumbnail">
							<?php echo zm_thumbnail(); ?>
						</span>
						<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark" <?php echo goal(); ?>><?php the_title(); ?></a></span>
						<?php grid_meta(); ?>
						<span class="discuss"><?php comments_number( '', '<i class="be be-speechbubble ri"></i>1 ', '<i class="be be-speechbubble ri"></i>%' ); ?></span>
					</li>
				<?php } else { ?>
					<?php if ( ! $instance['title_all'] ) { ?>
						<li class="title-all-item">
							<span class='li-icon li-icon-<?php echo $i; ?>'><?php echo $i; ?></span>
							<span class="title-all">
								<a href="<?php the_permalink(); ?>" rel="bookmark" <?php echo goal(); ?>><?php the_title(); ?></a>
								<span class="title-all-inf">
									<?php grid_meta(); ?>
									<span class="discuss"><?php comments_number( '', '<i class="be be-speechbubble ri"></i>1 ', '<i class="be be-speechbubble ri"></i>%' ); ?></span>
								</span>
							</span>
						</li>
					<?php } else { ?>
						<li class="srm"><span class='li-icon li-icon-<?php echo $i; ?>'><?php echo $i; ?></span><a href="<?php the_permalink(); ?>" rel="bookmark" <?php echo goal(); ?>><?php the_title(); ?></a></li>
					<?php } ?>
				<?php } ?>
			<?php endwhile;?>
		<?php wp_reset_postdata(); ?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['show_thumbs'] = !empty($new_instance['show_thumbs']) ? 1 : 0;
		$instance['title_all']   = !empty($new_instance['title_all']) ? 1 : 0;
		$instance['mycat']       = !empty($new_instance['mycat']) ? 1 : 0;
		$instance['title_z']     = strip_tags($new_instance['title_z']);
		$instance['show_icon']   = strip_tags($new_instance['show_icon']);
		$instance['show_svg']    = strip_tags($new_instance['show_svg']);
		$instance['title']       = strip_tags( $new_instance['title'] );
		$instance['number']      = strip_tags($new_instance['number']);
		$instance['days']        = strip_tags($new_instance['days']);
		$instance['e_cat']       = strip_tags($new_instance['e_cat']);
		$instance['out_post']    = strip_tags( $new_instance['out_post'] );
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '热评文章';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$instance = wp_parse_args((array) $instance, array('days' => '90'));
		$number = strip_tags($instance['number']);
		$days = strip_tags($instance['days']);
 ?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	 </p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">单色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_svg'); ?>">彩色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_svg'); ?>" name="<?php echo $this->get_field_name('show_svg'); ?>" type="text" value="<?php echo $instance['show_svg']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>">显示数量：</label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'days' ); ?>">时间限定：</label>
		<input class="number-text-d" id="<?php echo $this->get_field_id( 'days' ); ?>" name="<?php echo $this->get_field_name( 'days' ); ?>" type="number" step="1" min="1" value="<?php echo $days; ?>" size="3" />
		<label>有图/无图：月/天</label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('e_cat'); ?>">排除的分类ID：</label>
		<textarea style="height:35px;" class="widefat" id="<?php echo $this->get_field_id( 'e_cat' ); ?>" name="<?php echo $this->get_field_name( 'e_cat' ); ?>"><?php echo stripslashes(htmlspecialchars(( $instance['e_cat'] ), ENT_QUOTES)); ?></textarea>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'out_post' ); ?>">排除的文章ID：</label>
		<textarea style="height:35px;" class="widefat" id="<?php echo $this->get_field_id( 'out_post' ); ?>" name="<?php echo $this->get_field_name( 'out_post' ); ?>"><?php echo stripslashes(htmlspecialchars(( $instance['out_post'] ), ENT_QUOTES)); ?></textarea>
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_thumbs') ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>">显示缩略图</label>
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('title_all') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title_all') ); ?>" <?php checked( (bool) $instance["title_all"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('title_all') ); ?>">截断标题</label>
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('mycat') ); ?>" name="<?php echo esc_attr( $this->get_field_name('mycat') ); ?>" <?php checked( (bool) $instance["mycat"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('mycat') ); ?>">相同分类</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

function hot_comment_init() {
	register_widget( 'hot_comment' );
}
add_action( 'widgets_init', 'hot_comment_init' );

// 标签云
class cx_tag_cloud extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'cx_tag_cloud',
			'description' => '可实现3D特效',
			'customize_selective_refresh' => true,
		);
		parent::__construct('cx_tag_cloud', '热门标签', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_3d'     => 1,
			'color'     => 1,
			'title_z'     => '',
			'show_icon'   => '',
			'show_svg'    => '',
			'number'      => 20,
			'tags_id'     => '',
			'title'       => '热门标签',
		);
	}

	function widget($args, $instance) {
		extract($args);
		$defaults = $this -> zm_defaults();
		$title_w = title_i_w();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 20;
		$tags_id = strip_tags($instance['tags_id']) ? absint( $instance['tags_id'] ) : 1;
?>
	<?php if ($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_3d']) { ?>
		<div id="tag_cloud_widget" class="cloud-tag">
	<?php } else { ?>
		<?php if ($instance['color']) { ?>
			<div class="tagcloud-color">
		<?php } else { ?>
			<div class="be-tagcloud">
		<?php } ?>
	<?php } ?>
	<?php wp_tag_cloud( array ( 'smallest' => 14, 'largest' => 20, 'unit' => 'px', 'order' => 'RAND', 'hide_empty' => 0, 'number' => $number, 'include' => $instance["tags_id"] ) ); ?>
	<div class="clear"></div>
	<?php if ($instance['show_3d']) : ?><?php wp_enqueue_script('3dtag'); ?><?php endif; ?>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['show_3d'] = !empty($new_instance['show_3d']) ? 1 : 0;
		$instance['color'] = !empty($new_instance['color']) ? 1 : 0;
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['show_svg'] = strip_tags($new_instance['show_svg']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = strip_tags($new_instance['number']);
		$instance['tags_id'] = strip_tags($new_instance['tags_id']);
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '热门标签';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '20'));
		$number = strip_tags($instance['number']);
		$instance = wp_parse_args((array) $instance, array('tags_id' => ''));
		$tags_id = strip_tags($instance['tags_id']);
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">单色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_svg'); ?>">彩色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_svg'); ?>" name="<?php echo $this->get_field_name('show_svg'); ?>" type="text" value="<?php echo $instance['show_svg']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>">显示数量：</label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('tags_id'); ?>">输入标签ID调用指定标签：</label>
		<textarea style="height:50px;" class="widefat" id="<?php echo $this->get_field_id( 'tags_id' ); ?>" name="<?php echo $this->get_field_name( 'tags_id' ); ?>"><?php echo stripslashes(htmlspecialchars(( $instance['tags_id'] ), ENT_QUOTES)); ?></textarea>
	</p>

	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_3d') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_3d') ); ?>" <?php checked( (bool) $instance["show_3d"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_3d') ); ?>">显示3D特效</label>
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('color') ); ?>" name="<?php echo esc_attr( $this->get_field_name('color') ); ?>" <?php checked( (bool) $instance["color"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('color') ); ?>">随机背景色</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

function cx_tag_cloud_init() {
	register_widget( 'cx_tag_cloud' );
}
add_action( 'widgets_init', 'cx_tag_cloud_init' );

// 随机文章
class random_post extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'random_post',
			'description' => '显示随机文章',
			'customize_selective_refresh' => true,
		);
		parent::__construct('random_post', '随机文章', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_thumbs'   => 1,
			'this_cat'      => 0,
			'title_z'       => '',
			'show_icon'     => '',
			'show_svg'      => '',
			'number'        => 5,
			'title'         => '随机文章',
		);
	}

	function widget($args, $instance) {
		extract($args);
		$title_w = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
?>

<?php if ($instance['show_thumbs']) { ?>
<div class="new_cat">
<?php } else { ?>
<div id="random_post_widget">
<?php } ?>
	<?php if ($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<ul>
		<?php
			$cat = get_the_category();
			foreach($cat as $key=>$category){
			    $catid = $category->term_id;
			}
			if ($instance['this_cat']) {
				$args = array( 'orderby' => 'rand', 'showposts' => $number, 'ignore_sticky_posts' => 1,'cat' => $catid );
			} else {
				$args = array( 'orderby' => 'rand', 'showposts' => $number, 'ignore_sticky_posts' => 1 );
			}
			$query = new WP_Query();
			$query->query($args);
			while ($query->have_posts()) : $query->the_post();
		?>

		<?php if ($instance['show_thumbs']) { ?>
			<li>
				<span class="thumbnail">
					<?php echo zm_thumbnail(); ?>
				</span>
				<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark" <?php echo goal(); ?>><?php the_title(); ?></a></span>
				<?php grid_meta(); ?>
				<?php views_span(); ?>
			</li>
		<?php } else { ?>
			<li class="srm the-icon"><?php the_title( sprintf( '<a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a>' ); ?></li>
		<?php } ?>

		<?php endwhile;?>
		<?php wp_reset_postdata(); ?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance = array();
			$instance['show_thumbs'] = !empty($new_instance['show_thumbs']) ? 1 : 0;
			$instance['this_cat'] = !empty($new_instance['this_cat']) ? 1 : 0;
			$instance['title_z'] = strip_tags($new_instance['title_z']);
			$instance['show_icon'] = strip_tags($new_instance['show_icon']);
			$instance['show_svg'] = strip_tags($new_instance['show_svg']);
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			return $instance;
		}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '随机文章';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$number = strip_tags($instance['number']);
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">单色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_svg'); ?>">彩色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_svg'); ?>" name="<?php echo $this->get_field_name('show_svg'); ?>" type="text" value="<?php echo $instance['show_svg']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>">显示数量：</label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('this_cat') ); ?>" name="<?php echo esc_attr( $this->get_field_name('this_cat') ); ?>" <?php checked( (bool) $instance["this_cat"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('this_cat') ); ?>">同分类文章</label>
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_thumbs') ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>">显示缩略图</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

function random_post_init() {
	register_widget( 'random_post' );
}
add_action( 'widgets_init', 'random_post_init' );

// 相关文章
class related_post extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'related_post',
			'description' => '显示相关文章',
			'customize_selective_refresh' => true,
		);
		parent::__construct('related_post', '相关文章', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_thumbs'   => 1,
			'title_z'       => '',
			'show_icon'     => '',
			'show_svg'      => '',
			'number'        => 5,
			'title'         => '相关文章',
		);
	}

	function widget($args, $instance) {
		extract($args);
		$title_w = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
?>

<?php if ($instance['show_thumbs']) { ?>
<div class="new_cat">
<?php } else { ?>
<div class="post_cat">
<?php } ?>
	<?php if ($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<ul>
		<?php
			$post_num = $number;
			global $post;
			$tmp_post = $post;
			$tags = ''; $i = 0;
			if ( get_the_tags( $post->ID ) ) {
			foreach ( get_the_tags( $post->ID ) as $tag ) $tags .= $tag->slug . ',';
			$tags = strtr(rtrim($tags, ','), ' ', '-');
			$myposts = get_posts('numberposts='.$post_num.'&tag='.$tags.'&exclude='.$post->ID);
			foreach($myposts as $post) {
			setup_postdata($post);
		?>

			<?php if ($instance['show_thumbs']) { ?>
				<li>
					<span class="thumbnail">
						<?php echo zm_thumbnail(); ?>
					</span>
					<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark" <?php echo goal(); ?>><?php the_title(); ?></a></span>
					<?php grid_meta(); ?>
					<?php views_span(); ?>
				</li>
			<?php } else { ?>
				<li class="srm the-icon"><?php the_title( sprintf( '<a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a>' ); ?></li>
			<?php } ?>

		<?php
			$i += 1;
			}
			}
			if ( $i < $post_num ) {
			$post = $tmp_post; setup_postdata($post);
			$cats = ''; $post_num -= $i;
			foreach ( get_the_category( $post->ID ) as $cat ) $cats .= $cat->cat_ID . ',';
			$cats = strtr(rtrim($cats, ','), ' ', '-');
			$myposts = get_posts('numberposts='.$post_num.'&category='.$cats.'&exclude='.$post->ID);
			foreach($myposts as $post) {
			setup_postdata($post);
		?>

			<?php if ($instance['show_thumbs']) { ?>
				<li>
					<span class="thumbnail">
						<?php echo zm_thumbnail(); ?>
					</span>
					<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark" <?php echo goal(); ?>><?php the_title(); ?></a></span>
					<?php grid_meta(); ?>
					<?php views_span(); ?>
				</li>
			<?php } else { ?>
				<li class="srm"><?php the_title( sprintf( '<a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a>' ); ?></li>
			<?php } ?>

		<?php
		}
		}
		$post = $tmp_post; setup_postdata($post);
		?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['show_thumbs'] = !empty($new_instance['show_thumbs']) ? 1 : 0;
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['show_svg'] = strip_tags($new_instance['show_svg']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = strip_tags($new_instance['number']);
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '相关文章';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$number = strip_tags($instance['number']);
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">单色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_svg'); ?>">彩色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_svg'); ?>" name="<?php echo $this->get_field_name('show_svg'); ?>" type="text" value="<?php echo $instance['show_svg']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>">显示数量：</label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_thumbs') ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>">显示缩略图</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

function related_post_init() {
	register_widget( 'related_post' );
}
add_action( 'widgets_init', 'related_post_init' );

// 本站推荐
class hot_commend extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'hot_commend',
			'description' => '调用指定的文章',
			'customize_selective_refresh' => true,
		);
		parent::__construct('hot_commend', '本站推荐', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_thumbs'   => 1,
			'title_z'       => '',
			'show_icon'     => '',
			'show_svg'      => '',
			'number'        => 5,
			'title'         => '本站推荐',
		);
	}

	function widget($args, $instance) {
		extract($args);
		$title_w = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
?>

<?php if ($instance['show_thumbs']) { ?>
<div id="hot" class="hot_commend">
<?php } else { ?>
<div class="hot_comment_widget">
<?php } ?>
	<?php if ($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<ul>
		<?php
			$i = 1;
			$args = array(
				'meta_key' => 'hot', 
				'posts_per_page' => $number, 
				'ignore_sticky_posts' => true, 
				);
			$query = new WP_Query( $args );
		?>
		<?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>
			<?php if ($instance['show_thumbs']) { ?>
				<li>
					<span class="thumbnail">
						<?php echo zm_thumbnail(); ?>
					</span>
					<span class="hot-title"><a href="<?php the_permalink(); ?>" rel="bookmark" <?php echo goal(); ?>><?php the_title(); ?></a></span>
					<?php views_span(); ?>
					<span class="be-like"><i class="be be-thumbs-up-o ri"></i><?php zm_get_current_count(); ?></span>
				</li>
			<?php } else { ?>
				<li class="srm"><span class="new-title"><span class="li-icon li-icon-<?php echo($i); ?>"><?php echo($i++); ?></span><a href="<?php the_permalink(); ?>" rel="bookmark" <?php echo goal(); ?>><?php the_title(); ?></a></span></li>
			<?php } ?>
		<?php endwhile; ?>
		<?php else : ?>
			<p class="slider-widge-none">编辑文章，在下面“将文章添加到”面板，勾选“本站推荐小工具中”</p>
		<?php endif; ?>
		<?php wp_reset_postdata(); ?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['show_thumbs'] = !empty($new_instance['show_thumbs']) ? 1 : 0;
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['show_svg'] = strip_tags($new_instance['show_svg']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = strip_tags($new_instance['number']);
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '本站推荐';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$number = strip_tags($instance['number']);
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">单色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_svg'); ?>">彩色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_svg'); ?>" name="<?php echo $this->get_field_name('show_svg'); ?>" type="text" value="<?php echo $instance['show_svg']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>">显示数量：</label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_thumbs') ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>">显示缩略图</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

function hot_commend_init() {
	register_widget( 'hot_commend' );
}
add_action( 'widgets_init', 'hot_commend_init' );

// 读者墙
class readers extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'readers',
			'description' => '最活跃的读者',
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'readers', '读者墙', $widget_ops );
	}

	public function zm_defaults() {
		return array(
			'title_z'       => '',
			'show_icon'     => '',
			'show_svg'      => '',
			'number'        => 6,
			'days'          => 90,
			'exclude'       => 1,
			'title'         => '读者墙',
		);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title_w = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( ( array ) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title;
		$number = strip_tags( $instance['number']) ? absint( $instance['number'] ) : 6;
		$days = strip_tags( $instance['days']) ? absint( $instance['days'] ) : 90;
		$exclude = strip_tags( $instance['exclude'] ) ? absint( $instance['exclude'] ) : 1;
?>
<?php if ($instance['show_icon']) { ?>
	<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
<?php } ?>
<?php if ($instance['show_svg']) { ?>
	<h3 class="widget-title-cat-icon cat-w-icon"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
<?php } ?>
<div id="readers_widget" class="readers">
	<?php
		global $wpdb;
		$exclude_id = "'" . implode( "','", explode( ',', $instance['exclude'] ) ) . "'";
		$counts = wp_cache_get( 'mostactive' );
		if ( false === $counts ) {
			$counts = $wpdb->get_results("
				SELECT COUNT(comment_author) AS cnt, comment_author, comment_author_url, comment_author_email
				FROM {$wpdb->prefix}comments
				WHERE comment_date > date_sub( NOW(), INTERVAL $days DAY )
				AND comment_approved = '1'
				AND comment_author_email != 'example@example.com'
				AND comment_author_email != ''
				AND comment_type not in ('trackback','pingback')
				 " . ( ! empty( $instance['exclude'] ) ? "AND user_id NOT IN ( $exclude_id )" : "" ) . "
				GROUP BY comment_author_email
				ORDER BY cnt DESC
				LIMIT $number
			");
		}
		$mostactive = '';
		if ( $counts ) {
			wp_cache_set( 'mostactive', $counts );
			foreach ( $counts as $count ) {
				$confirm_url = esc_url( get_permalink( zm_get_option( 'comment_link_go_id' ) ) );
				$count_url = esc_url( $count->comment_author_url );

				if ( empty( $count_url ) || ! filter_var( $count_url, FILTER_VALIDATE_URL ) ) {
					$url = '';
				} else {
					$url = zm_get_option( 'comment_link_go' ) ? $confirm_url . '?target=' . esc_url( $count_url ) : esc_url( $count_url );
				}

				if ( zm_get_option( 'cache_avatar' ) ) {
					$mostactive .= '<div class="readers-avatar load"><span class="dzq"><a href="' . $url . '" target="_blank" rel="external nofollow">' . begin_avatar($count->comment_author_email, 96, '', $count->comment_author) . '</a></span><span class="readers-inf"><strong>' . $count->comment_author . ' </strong>' . $count->cnt . '个脚印</span></div>';
				} else {
					if ( !zm_get_option( 'avatar_load' ) ) {
						$mostactive .= '<div class="readers-avatar"><span class="dzq"><a href="' . $url . '" target="_blank" rel="external nofollow">' . get_avatar($count->comment_author_email, 96, '', $count->comment_author) . '</a></span><span class="readers-inf"><strong>' . $count->comment_author . ' </strong>' . $count->cnt . '个脚印</span></div>';
					} else {
						$mostactive .= '<div class="readers-avatar load"><span class="dzq"><a href="' . $url . '" target="_blank" rel="external nofollow"><img class="avatar photo" src="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=" alt="'. $count->comment_author .'"  width="96" height="96" data-original="' . preg_replace(array('/^.+(src=)(\"|\')/i', '/(\"|\')\sclass=(\"|\').+$/i'), array('', ''), get_avatar( $count->comment_author_email, $size = 96, '', $count->comment_author )) . '" /></a></span><span class="readers-inf"><strong>' . $count->comment_author . ' </strong>' . $count->cnt . '个脚印</span></div>';
					}
				}
			}
			echo $mostactive;
		}
	?>
	<div class="clear"></div>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['show_svg'] = strip_tags($new_instance['show_svg']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = strip_tags($new_instance['number']);
		$instance['exclude'] = strip_tags($new_instance['exclude']);
		$instance['days'] = strip_tags($new_instance['days']);
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '读者墙';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '6'));
		$instance = wp_parse_args((array) $instance, array('days' => '90'));
		$instance = wp_parse_args((array) $instance, array('exclude' => '1'));
		$number = strip_tags($instance['number']);
		$days = strip_tags($instance['days']);
 ?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">单色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_svg'); ?>">彩色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_svg'); ?>" name="<?php echo $this->get_field_name('show_svg'); ?>" type="text" value="<?php echo $instance['show_svg']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>">显示数量：</label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'days' ); ?>">时间限定（天）：</label>
		<input class="number-text-d" id="<?php echo $this->get_field_id( 'days' ); ?>" name="<?php echo $this->get_field_name( 'days' ); ?>" type="number" step="1" min="1" value="<?php echo $days; ?>" size="3" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('exclude'); ?>">排除的用户ID：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('exclude'); ?>" name="<?php echo $this->get_field_name('exclude'); ?>" type="text" value="<?php echo $instance['exclude']; ?>" />
		
	</p>

	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

function readers_init() {
	register_widget( 'readers' );
}
add_action( 'widgets_init', 'readers_init' );

// 关注我们
class feed_widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'feed_widget',
			'description' => 'RSS、微信、微博',
			'customize_selective_refresh' => true,
		);
		parent::__construct('feed', '关注我们', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'weixin'         => get_template_directory_uri() . '/img/favicon.png"',
			'tsina'          => 'be be-stsina',
			'tsinaurl'       => '输入链接地址',
			'tqq'            => 'be be-qq',
			'tqqurl'         => '88888',
			'rss'            => 'be be-rss',
			'rssurl'         => home_url( '/' ) . 'feed/',
			'title'          => '关注我们',
		);
	}

	function widget($args, $instance) {
		extract($args);
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title_w = title_i_w();
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
?>

<div id="feed_widget">
	<div class="feed-rss">
		<?php if ( ! empty( $title ) ) { ?>
			<?php 
				if ( isset( $instance[ 'title' ] ) ) {
					$title = $instance[ 'title' ];
				} else {
					$title = '关注我们';
				}
			 ?>
			<h3 class="widget-title"><?php echo title_i_w(); ?><?php echo $title; ?></h3>
		<?php } ?>

		<div class="feed-about-box">
			<?php if ($instance['weixin']) { ?>
				<div class="feed-t weixin">
					<div class="weixin-b">
						<div class="weixin-qr fd">
							<div class="weixin-qr-about">
								<div class="copy-weixin">
									<img src="<?php echo $instance['weixin']; ?>" alt=" weixin"/>
									<div class="be-copy-text"><?php _e( '点击复制', 'begin' ); ?></div>
									<div class="weixinbox">
										<div class="btn-weixin-copy"></div>
										<div class="weixin-id"><?php echo zm_get_option( 'weixin_s_id' ); ?></div>
										<div class="copy-success-weixin fd"><div class="copy-success-weixin-text followus"><span class="dashicons dashicons-saved"></span><?php _e( '已复制', 'begin' ); ?></div></div>
									</div>
								</div>
								<div class="clear"></div>
							</div>
							<div class="arrow-down"></div>
						</div>
						<a><i class="be be-weixin"></i></a>
					</div>
				</div>
			<?php } ?>

			<?php if ( $instance[ 'tsina' ] ) { ?>
				<div class="feed-t tsina"><a title="" href="<?php echo $instance['tsinaurl']; ?>" target="_blank" rel="external nofollow"><i class="<?php echo $instance['tsina']; ?>"></i></a></div>
			<?php } ?>

			<?php if ( $instance[ 'tqq' ] ) { ?>
				<div class="feed-t tqq"><a class="quoteqq" href="https://wpa.qq.com/msgrd?V=3&uin=<?php echo $instance['tqqurl']; ?>&site=QQ&Menu=yes" onclick="copyToClipboard(this)" title="<?php _e( '点击复制', 'begin' ); ?>" target=blank rel="external nofollow"><i class="<?php echo $instance['tqq']; ?>"></i></a></div>
			<?php } ?>

			<?php if ( $instance[ 'rss' ] ) { ?>
				<div class="feed-t feed"><a title="" href="<?php echo $instance['rssurl']; ?>" target="_blank" rel="external nofollow"><i class="<?php echo $instance['rss']; ?>"></i></a></div>
			<?php } ?>
			<div class="clear"></div>
		</div>
	</div>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['weixin'] = $new_instance['weixin'];
		$instance['tsina'] = $new_instance['tsina'];
		$instance['tsinaurl'] = $new_instance['tsinaurl'];
		$instance['tqq'] = $new_instance['tqq'];
		$instance['tqqurl'] = $new_instance['tqqurl'];
		$instance['rss'] = $new_instance['rss'];
		$instance['rssurl'] = $new_instance['rssurl'];
		return $instance;
	}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '关注我们';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('weixin' => '' . get_template_directory_uri() . '/img/favicon.png"'));
		$weixin = $instance['weixin'];
		$instance = wp_parse_args((array) $instance, array('tsina' => 'be be-stsina'));
		$tsina = $instance['tsina'];
		$instance = wp_parse_args((array) $instance, array('tsinaurl' => '输入链接地址'));
		$tsinaurl = $instance['tsinaurl'];
		$instance = wp_parse_args((array) $instance, array('tqq' => 'be be-qq'));
		$tqq = $instance['tqq'];
		$instance = wp_parse_args((array) $instance, array('tqqurl' => '88888'));
		$tqqurl = $instance['tqqurl'];
		$instance = wp_parse_args((array) $instance, array('rss' => 'be be-rss'));
		$rss = $instance['rss'];
		$instance = wp_parse_args((array) $instance, array('rssurl' => home_url( '/' ) . 'feed/'));
		$rssurl = $instance['rssurl'];
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('weixin'); ?>">微信二维码（留空不显示）：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'weixin' ); ?>" name="<?php echo $this->get_field_name( 'weixin' ); ?>" type="text" value="<?php echo $weixin; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('tsina'); ?>">新浪微博图标（留空不显示）：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'tsina' ); ?>" name="<?php echo $this->get_field_name( 'tsina' ); ?>" type="text" value="<?php echo $tsina; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('tsina'); ?>">新浪微博地址：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'tsinaurl' ); ?>" name="<?php echo $this->get_field_name( 'tsinaurl' ); ?>" type="text" value="<?php echo $tsinaurl; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('tqq'); ?>">QQ图标（留空不显示）：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'tqq' ); ?>" name="<?php echo $this->get_field_name( 'tqq' ); ?>" type="text" value="<?php echo $tqq; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('tqqurl'); ?>">QQ号：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'tqqurl' ); ?>" name="<?php echo $this->get_field_name( 'tqqurl' ); ?>" type="text" value="<?php echo $tqqurl; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('rss'); ?>">订阅图标（留空不显示）：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'rss' ); ?>" name="<?php echo $this->get_field_name( 'rss' ); ?>" type="text" value="<?php echo $rss; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('rss'); ?>">订阅地址：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'rssurl' ); ?>" name="<?php echo $this->get_field_name( 'rssurl' ); ?>" type="text" value="<?php echo $rssurl; ?>" />
	</p>

	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
function feed_init() {
	register_widget( 'feed_widget' );
}
add_action( 'widgets_init', 'feed_init' );

// 广告位
class sponsor extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'sponsor',
			'description' => '用于侧边添加广告代码',
			'customize_selective_refresh' => true,
		);
		parent::__construct('sponsor', '广告位', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'text'     => '',
			'title'    => '广告位',
		);
	}

	function widget($args, $instance) {
		extract($args);
		$title_w = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title;

		$text = apply_filters( 'widget_text', empty( $instance['text'] ) ? '' : $instance['text'], $instance );
?>

<div id="sponsor_widget">
	<?php echo !empty( $instance['filter'] ) ? wpautop( $text ) : $text; ?>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		if ( current_user_can('unfiltered_html') )
			$instance['text'] =  $new_instance['text'];
		else
			$instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) ); // wp_filter_post_kses() expects slashed
		$instance['filter'] = ! empty( $new_instance['filter'] );
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '广告位';
		}
		$text = esc_textarea($instance['text']);
		global $wpdb;
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
		<p><label for="<?php echo $this->get_field_id('text'); ?>">内容：</label>
		<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea></p>
		<p><input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>">自动添加段落</label></p>
		<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
function sponsor_init() {
	register_widget( 'sponsor' );
}
add_action( 'widgets_init', 'sponsor_init' );

// 关于本站
class about extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'about',
			'description' => '本站信息、RSS、微信、微博、QQ',
			'customize_selective_refresh' => true,
		);
		parent::__construct('about', '关于本站', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_social_icon'   => 1,
			'show_caption'       => 0,
			'show_inf'           => 1,
			'show_mixed'           => 0,
			'about_back'         => get_template_directory_uri() . '/img/default/options/user.jpg',
			'weixin'             => get_template_directory_uri() . '/img/favicon.png"',
			'about_img'          => get_template_directory_uri() . '/img/favicon.png"',
			'about_name'         => '网站名称',
			'about_the'          => '到小工具中更改此内容',
			'tsina'              => 'be be-stsina',
			'tsinaurl'           => '输入链接地址',
			'rss'                => 'be be-rss',
			'rssurl'             => home_url( '/' ) . 'feed/',
			'tqq'                => 'be be-qq',
			'tqqurl'             => '88888',
			'cqqurl'             => '',
		);
	}

	function widget($args, $instance) {
		extract($args);
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		echo $before_widget;
?>

<div id="feed_widget">
	<div class="feed-about">
		<?php if ( $instance[ 'show_mixed' ]  ) { ?>
			<div class="about-the-mixed">
				<div class="mixed-about-img"><img src="<?php echo $instance['about_img']; ?>" alt="name"/></div>
				<div class="mixed-about">
					<div class="about-name"><?php echo $instance['about_name']; ?></div>
					<span class="about-mixed-the"><?php echo $instance['about_the']; ?></span>
				</div>
			</div>
		<?php } else { ?>
			<?php if ( $instance[ 'about_back' ]  ) { ?>
				<div class="author-back" style="background-image: url('<?php echo $instance['about_back']; ?>');"></div>
			<?php } ?>
			<div class="about-main">
				<div class="about-img">
					<div class="about-img-box"><img src="<?php echo $instance['about_img']; ?>" alt="name"/></div>
				</div>
				<div class="clear"></div>
				<div class="about-name"><?php echo $instance['about_name']; ?></div>
				<div class="about-the<?php if ($instance['show_caption']) { ?> about-the-layout<?php } ?>"><?php echo $instance['about_the']; ?></div>
			</div>
		<?php } ?>
		<?php if ($instance['show_social_icon']) { ?>
			<div class="clear"></div>
			<div class="feed-about-box">
				<?php if ($instance['weixin']) { ?>
					<div class="feed-t weixin">
						<div class="weixin-b">
							<div class="weixin-qr fd">
								<div class="weixin-qr-about">
									<div class="copy-weixin">
										<img src="<?php echo $instance['weixin']; ?>" alt=" weixin"/>
										<div class="be-copy-text"><?php _e( '点击复制', 'begin' ); ?></div>
										<div class="weixinbox">
											<div class="btn-weixin-copy"></div>
											<div class="weixin-id"><?php echo zm_get_option( 'weixin_s_id' ); ?></div>
											<div class="copy-success-weixin fd"><div class="copy-success-weixin-text"><span class="dashicons dashicons-saved"></span><?php _e( '已复制', 'begin' ); ?></div></div>
										</div>
									</div>
									<div class="clear"></div>
								</div>
								<div class="arrow-down"></div>
							</div>
							<a><i class="be be-weixin"></i></a>
						</div>
					</div>
				<?php } ?>

				<?php if ( $instance[ 'tqq' ] ) { ?>
					<?php if ( $instance[ 'cqqurl' ]  ) { ?>
						<div class="feed-t tqq"><a class="quoteqq" onclick="copyToClipboard(this)" title="<?php _e( '点击复制', 'begin' ); ?>" target=blank rel="external nofollow" href="<?php echo $instance['cqqurl']; ?>" target="_blank" rel="external nofollow"><i class="<?php echo $instance['tqq']; ?>"></i></a></div>
					<?php } else { ?>
						<div class="feed-t tqq"><a class="quoteqq" onclick="copyToClipboard(this)" title="<?php _e( '点击复制', 'begin' ); ?>" target=blank rel="external nofollow" href="https://wpa.qq.com/msgrd?V=3&uin=<?php echo $instance['tqqurl']; ?>&Site=QQ&Menu=yes"><i class="<?php echo $instance['tqq']; ?>"></i></a></div>
					<?php } ?>
				<?php } ?>

				<?php if ( $instance[ 'tsina' ] ) { ?>
					<div class="feed-t tsina"><a title="" href="<?php echo $instance['tsinaurl']; ?>" target="_blank" rel="external nofollow"><i class="<?php echo $instance['tsina']; ?>"></i></a></div>
				<?php } ?>

				<?php if ( $instance[ 'rss' ] ) { ?>
					<div class="feed-t feed"><a title="" href="<?php echo $instance['rssurl']; ?>" target="_blank" rel="external nofollow"><i class="<?php echo $instance['rss']; ?>"></i></a></div>
				<?php } ?>
				<div class="clear"></div>
			</div>
		<?php } ?>
		<?php if ($instance['show_inf']) { ?>
			<div class="clear"></div>
			<div class="about-inf">
				<span class="about about-cn"><span><?php _e( '文章', 'begin' ); ?></span><?php $count_posts = wp_count_posts(); echo $published_posts = $count_posts->publish;?></span>
				<span class="about about-pn"><span><?php _e( '留言', 'begin' ); ?></span>
				<?php 
					$my_email = get_bloginfo ( 'admin_email' );
					global $wpdb;echo $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments where comment_author_email!='$my_email' And comment_author_email!=''");
				?>
				</span>
				<span class="about about-cn"><span><?php _e( '访客', 'begin' ); ?></span><?php echo all_view(); ?></span>
			</div>
		<?php } else { ?>
			<span class="social-clear"></span>
		<?php } ?>
	</div>
</div>

<?php
	echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance = $old_instance;
		$instance = array();
		$instance['show_social_icon'] = !empty($new_instance['show_social_icon']) ? 1 : 0;
		$instance['show_caption'] = !empty($new_instance['show_caption']) ? 1 : 0;
		$instance['show_inf'] = !empty($new_instance['show_inf']) ? 1 : 0;
		$instance['show_mixed'] = !empty($new_instance['show_mixed']) ? 1 : 0;
		$instance['about_img'] = $new_instance['about_img'];
		$instance['about_name'] = $new_instance['about_name'];
		$instance['about_back'] = $new_instance['about_back'];
		$instance['about_the'] = $new_instance['about_the'];
		$instance['weixin'] = $new_instance['weixin'];
		$instance['tsina'] = $new_instance['tsina'];
		$instance['tsinaurl'] = $new_instance['tsinaurl'];
		$instance['rss'] = $new_instance['rss'];
		$instance['rssurl'] = $new_instance['rssurl'];
		$instance['tqq'] = $new_instance['tqq'];
		$instance['tqqurl'] = $new_instance['tqqurl'];
		$instance['cqqurl'] = $new_instance['cqqurl'];
		return $instance;
	}

	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('weixin' => '' . get_template_directory_uri() . '/img/favicon.png"'));
		$weixin = $instance['weixin'];
		$instance = wp_parse_args((array) $instance, array('about_img' => '' . get_template_directory_uri() . '/img/favicon.png"'));
		$about_img = $instance['about_img'];
		$instance = wp_parse_args((array) $instance, array('about_name' => '网站名称'));
		$about_name = $instance['about_name'];
		$instance = wp_parse_args((array) $instance, array('about_back' => get_template_directory_uri() . '/img/default/options/user.jpg'));
		$about_back = $instance['about_back'];
		$instance = wp_parse_args((array) $instance, array('about_the' => '到小工具中更改此内容'));
		$about_the = $instance['about_the'];
		$instance = wp_parse_args((array) $instance, array('tsina' => 'be be-stsina'));
		$tsina = $instance['tsina'];
		$instance = wp_parse_args((array) $instance, array('tsinaurl' => '输入链接地址'));
		$tsinaurl = $instance['tsinaurl'];
		$instance = wp_parse_args((array) $instance, array('rss' => 'be be-rss'));
		$rss = $instance['rss'];
		$instance = wp_parse_args((array) $instance, array('rssurl' => home_url( '/' ) . 'feed/'));
		$rssurl = $instance['rssurl'];
		$instance = wp_parse_args((array) $instance, array('tqq' => 'be be-qq'));
		$tqq = $instance['tqq'];
		$instance = wp_parse_args((array) $instance, array('tqqurl' => '88888'));
		$tqqurl = $instance['tqqurl'];
		$instance = wp_parse_args((array) $instance, array('cqqurl' => ''));
		$cqqurl = $instance['cqqurl'];
?>

	<p>
		<label for="<?php echo $this->get_field_id('about_back'); ?>">背景图片：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'about_back' ); ?>" name="<?php echo $this->get_field_name( 'about_back' ); ?>" type="text" value="<?php echo $about_back; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('about_img'); ?>">头像：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'about_img' ); ?>" name="<?php echo $this->get_field_name( 'about_img' ); ?>" type="text" value="<?php echo $about_img; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('about_name'); ?>">网站名称：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'about_name' ); ?>" name="<?php echo $this->get_field_name( 'about_name' ); ?>" type="text" value="<?php echo $about_name; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('about_the'); ?>">说明：</label>
		<textarea class="widefat" rows="5" cols="20" id="<?php echo $this->get_field_id('about_the'); ?>" name="<?php echo $this->get_field_name('about_the'); ?>"><?php echo $about_the; ?></textarea></p>
	</p>

	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_caption') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_caption') ); ?>" <?php checked( (bool) $instance["show_caption"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_caption') ); ?>">长说明</label>
	</p>

	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_mixed') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_mixed') ); ?>" <?php checked( (bool) $instance["show_mixed"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_mixed') ); ?>">混排</label>
	</p>

	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_social_icon') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_social_icon') ); ?>" <?php checked( (bool) $instance["show_social_icon"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_social_icon') ); ?>">显示社交图标</label>
	</p>

	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_inf') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_inf') ); ?>" <?php checked( (bool) $instance["show_inf"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_inf') ); ?>">显示站点信息</label>
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('weixin'); ?>">微信二维码（留空不显示）：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'weixin' ); ?>" name="<?php echo $this->get_field_name( 'weixin' ); ?>" type="text" value="<?php echo $weixin; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('tqq'); ?>">QQ图标（留空不显示）：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'tqq' ); ?>" name="<?php echo $this->get_field_name( 'tqq' ); ?>" type="text" value="<?php echo $tqq; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('tqqurl'); ?>">QQ号：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'tqqurl' ); ?>" name="<?php echo $this->get_field_name( 'tqqurl' ); ?>" type="text" value="<?php echo $tqqurl; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('cqqurl'); ?>">QQ号自定义链接：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'cqqurl' ); ?>" name="<?php echo $this->get_field_name( 'cqqurl' ); ?>" type="text" value="<?php echo $cqqurl; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('tsina'); ?>">新浪微博图标（留空不显示）：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'tsina' ); ?>" name="<?php echo $this->get_field_name( 'tsina' ); ?>" type="text" value="<?php echo $tsina; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('tsina'); ?>">新浪微博地址：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'tsinaurl' ); ?>" name="<?php echo $this->get_field_name( 'tsinaurl' ); ?>" type="text" value="<?php echo $tsinaurl; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('rss'); ?>">订阅图标（留空不显示）：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'rss' ); ?>" name="<?php echo $this->get_field_name( 'rss' ); ?>" type="text" value="<?php echo $rss; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('rss'); ?>">订阅地址：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'rssurl' ); ?>" name="<?php echo $this->get_field_name( 'rssurl' ); ?>" type="text" value="<?php echo $rssurl; ?>" />
	</p>

	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
function about_init() {
	register_widget( 'about' );
}
add_action( 'widgets_init', 'about_init' );

// 图片
class be_img_widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'be_img_widget',
			'description' => '调用最新图片文章',
			'customize_selective_refresh' => true,
		);
		parent::__construct('be_img_widget', '最新图片', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'title_z'     => '',
			'show_icon'   => '',
			'show_svg'    => '',
			'cat'         => '',
			'number'      => 4,
			'show_title'  => 1,
			'title'       => '最新图片',
		);
	}

	function widget($args, $instance) {
		extract($args);
		$title_w = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		$titleUrl = empty($instance['titleUrl']) ? '' : $instance['titleUrl'];
		$newWindow = !empty($instance['newWindow']) ? true : false;
		$hideTitle = !empty($instance['hideTitle']) ? true : false;
		if ( zm_get_option('more_im') ) {
			$more_i = '<span class="more-i more-im"><span></span><span></span><span></span></span>';
		} else {
			$more_i = '<span class="more-i"><span></span><span></span><span></span></span>';
		}
		echo $before_widget;
		if ($newWindow) $newWindow = "target='_blank'";
			if (!$hideTitle && $title) {
				if ($titleUrl) $title = "<a href='$titleUrl' $newWindow>$title $more_i</a>";
			}
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 4;
?>

<?php if ($instance['show_icon']) { ?>
	<h3 class="widget-title-cat-icon cat-w-icon"><a href="<?php echo $titleUrl; ?>" target="_blank"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?><?php more_i(); ?></a></h3>
<?php } ?>
<?php if ($instance['show_svg']) { ?>
	<h3 class="widget-title-cat-icon cat-w-icon"><a href="<?php echo $titleUrl; ?>" target="_blank"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?><?php more_i(); ?></a></h3>
<?php } ?>
<div class="widget-flex-img">
	<?php
		$args = array(
			'post_type' => 'picture',
			'showposts' => $number, 
			);

			if ($instance['cat']) {
				$args = array(
					'showposts' => $number, 
					'tax_query' => array(
						array(
							'taxonomy' => 'gallery',
							'terms' => $instance['cat']
						),
					)
				);
			}
		$fl = isset( $instance['fl'] ) ? $instance['fl'] : '';
 		?>
	<div class="wimg-main">
		<?php $be_query = new WP_Query($args); while ($be_query->have_posts()) : $be_query->the_post(); ?>
			<div class="wimg-item wimg-<?php echo $fl; ?>">
				<div class="wimg-area">
					<?php if ( $instance['show_title'] ) { ?>
						<div class="img-title"><a href="<?php the_permalink() ?>" rel="bookmark" <?php echo goal(); ?>><?php the_title( sprintf( '<span class="img-title-t over">' ), '</span>' ); ?></a></div>
					<?php } ?>
					<?php echo zm_thumbnail(); ?>
				</div>
			</div>
		<?php endwhile;?>
		<?php wp_reset_postdata(); ?>
		<div class="clear"></div>
	</div>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['hideTitle'] = !empty($new_instance['hideTitle']) ? 1 : 0;
		$instance['newWindow'] = !empty($new_instance['newWindow']) ? 1 : 0;
		$instance['show_title'] = !empty($new_instance['show_title']) ? 1 : 0;
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['show_svg'] = strip_tags($new_instance['show_svg']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['titleUrl'] = strip_tags($new_instance['titleUrl']);
		$instance['number'] = strip_tags($new_instance['number']);
		$instance['cat'] = $new_instance['cat'];
		$instance['fl'] = ! empty( $new_instance['fl'] ) ? sanitize_text_field( $new_instance['fl'] ) : '';
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$selected_option = isset( $instance['fl'] ) ? $instance['fl'] : '2';
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '最新图片';
		}
	global $wpdb;
	$instance = wp_parse_args((array) $instance, array('number' => '4'));
	$number = strip_tags($instance['number']);
	$instance = wp_parse_args((array) $instance, array('titleUrl' => ''));
	$titleUrl = $instance['titleUrl'];
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">单色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_svg'); ?>">彩色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_svg'); ?>" name="<?php echo $this->get_field_name('show_svg'); ?>" type="text" value="<?php echo $instance['show_svg']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('titleUrl'); ?>">标题链接：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('titleUrl'); ?>" name="<?php echo $this->get_field_name('titleUrl'); ?>" type="text" value="<?php echo $titleUrl; ?>" />
	</p>
	<p>
		<input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" class="checkbox" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
		<label for="<?php echo $this->get_field_id('newWindow'); ?>">在新窗口打开标题链接</label>
	</p>
	<p>
		<input type="checkbox" id="<?php echo $this->get_field_id( 'show_title' ); ?>" class="checkbox" name="<?php echo $this->get_field_name( 'show_title' ); ?>" <?php checked( isset( $instance['show_title'] ) ? $instance['show_title'] : 0 ); ?> />
		<label for="<?php echo $this->get_field_id( 'show_title' ); ?>">文章标题</label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
		<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => '全部分类', 'hide_empty'=>0, 'hierarchical'=>1,	'taxonomy' => 'gallery', 'selected'=>$instance['cat'])); ?></label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>">显示数量：</label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>
	<ul class="be-widget-radio">
		<li>
			<input type="radio" id="<?php echo $this->get_field_id( '2' ); ?>" name="<?php echo $this->get_field_name( 'fl' ); ?>" value="2" <?php checked( $selected_option, '2' ); ?>>
			<label for="<?php echo $this->get_field_id( '2' ); ?>">2栏</label>
		</li>
		<li>
			<input type="radio" id="<?php echo $this->get_field_id( '3' ); ?>" name="<?php echo $this->get_field_name( 'fl' ); ?>" value="3" <?php checked( $selected_option, '3' ); ?>>
			<label for="<?php echo $this->get_field_id( '3' ); ?>">3栏</label>
		</li>
		<li>
			<input type="radio" id="<?php echo $this->get_field_id( '4' ); ?>" name="<?php echo $this->get_field_name( 'fl' ); ?>" value="4" <?php checked( $selected_option, '4' ); ?>>
			<label for="<?php echo $this->get_field_id( '4' ); ?>">4栏</label>
		</li>
		<li>
			<input type="radio" id="<?php echo $this->get_field_id( '5' ); ?>" name="<?php echo $this->get_field_name( 'fl' ); ?>" value="5" <?php checked( $selected_option, '5' ); ?>>
			<label for="<?php echo $this->get_field_id( '5' ); ?>">5栏</label>
		</li>
		<li>
			<input type="radio" id="<?php echo $this->get_field_id( '6' ); ?>" name="<?php echo $this->get_field_name( 'fl' ); ?>" value="6" <?php checked( $selected_option, '6' ); ?>>
			<label for="<?php echo $this->get_field_id( '6' ); ?>">6栏</label>
		</li>
	</ul>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

// 视频
class be_video_widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'be_video_widget',
			'description' => '调用最新视频文章',
			'customize_selective_refresh' => true,
		);
		parent::__construct('be_video_widget', '最新视频', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'title_z'       => '',
			'show_icon'     => '',
			'show_svg'      => '',
			'cat'           => '',
			'number'        => 4,
			'show_title'    => '',
			'title'         => '最新视频',
		);
	}

	function widget($args, $instance) {
		extract($args);
		$title_w = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		$titleUrl = empty($instance['titleUrl']) ? '' : $instance['titleUrl'];
		$newWindow = !empty($instance['newWindow']) ? true : false;
		$hideTitle = !empty($instance['hideTitle']) ? true : false;
		if ( zm_get_option('more_im') ) {
			$more_i = '<span class="more-i more-im"><span></span><span></span><span></span></span>';
		} else {
			$more_i = '<span class="more-i"><span></span><span></span><span></span></span>';
		}
		echo $before_widget;
		if ($newWindow) $newWindow = "target='_blank'";
			if (!$hideTitle && $title) {
				if ($titleUrl) $title = "<a href='$titleUrl' $newWindow>$title $more_i</a>";
			}
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 4;
?>
<?php if ($instance['show_icon']) { ?>
	<h3 class="widget-title-cat-icon cat-w-icon"><a href="<?php echo $titleUrl; ?>" target="_blank"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?><?php more_i(); ?></a></h3>
<?php } ?>
<?php if ($instance['show_svg']) { ?>
	<h3 class="widget-title-cat-icon cat-w-icon"><a href="<?php echo $titleUrl; ?>" target="_blank"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?><?php more_i(); ?></a></h3>
<?php } ?>
<div class="widget-flex-img">
	<?php
		$args = array(
			'post_type' => 'video',
			'showposts' => $number, 
			);

			if ($instance['cat']) {
				$args = array(
					'showposts' => $number, 
					'tax_query' => array(
						array(
							'taxonomy' => 'videos',
							'terms' => $instance['cat']
						),
					)
				);
			}
			$fl = isset( $instance['fl'] ) ? $instance['fl'] : '';
 		?>

	<div class="wimg-main">
		<?php $be_query = new WP_Query($args); while ($be_query->have_posts()) : $be_query->the_post(); ?>
			<div class="wimg-item wimg-<?php echo $fl; ?>">
				<div class="wimg-area">
					<?php if ( $instance['show_title'] ) { ?>
						<div class="img-title"><a href="<?php the_permalink() ?>" rel="bookmark" <?php echo goal(); ?>><?php the_title( sprintf( '<span class="img-title-t over">' ), '</span>' ); ?></a></div>
					<?php } ?>
					<?php echo videos_thumbnail(); ?>
					<i class="be be-play"></i>
				</div>
			</div>
		<?php endwhile;?>
		<?php wp_reset_postdata(); ?>
		<div class="clear"></div>
	</div>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['hideTitle'] = !empty($new_instance['hideTitle']) ? 1 : 0;
		$instance['newWindow'] = !empty($new_instance['newWindow']) ? 1 : 0;
		$instance['show_title'] = !empty($new_instance['show_title']) ? 1 : 0;
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['show_svg'] = strip_tags($new_instance['show_svg']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['titleUrl'] = strip_tags($new_instance['titleUrl']);
		$instance['number'] = strip_tags($new_instance['number']);
		$instance['cat'] = $new_instance['cat'];
		$instance['fl'] = ! empty( $new_instance['fl'] ) ? sanitize_text_field( $new_instance['fl'] ) : '';
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$selected_option = isset( $instance['fl'] ) ? $instance['fl'] : '2';
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '最新视频';
		}
	global $wpdb;
	$instance = wp_parse_args((array) $instance, array('number' => '4'));
	$number = strip_tags($instance['number']);
	$instance = wp_parse_args((array) $instance, array('titleUrl' => ''));
	$titleUrl = $instance['titleUrl'];
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">单色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_svg'); ?>">彩色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_svg'); ?>" name="<?php echo $this->get_field_name('show_svg'); ?>" type="text" value="<?php echo $instance['show_svg']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('titleUrl'); ?>">标题链接：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('titleUrl'); ?>" name="<?php echo $this->get_field_name('titleUrl'); ?>" type="text" value="<?php echo $titleUrl; ?>" />
	</p>
	<p>
		<input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" class="checkbox" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
		<label for="<?php echo $this->get_field_id('newWindow'); ?>">在新窗口打开标题链接</label>
	</p>
	<p>
		<input type="checkbox" id="<?php echo $this->get_field_id( 'show_title' ); ?>" class="checkbox" name="<?php echo $this->get_field_name( 'show_title' ); ?>" <?php checked( isset( $instance['show_title'] ) ? $instance['show_title'] : 0 ); ?> />
		<label for="<?php echo $this->get_field_id( 'show_title' ); ?>">文章标题</label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
		<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => '全部分类', 'hide_empty'=>0, 'hierarchical'=>1,	'taxonomy' => 'videos', 'selected'=>$instance['cat'])); ?></label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>">显示数量：</label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>
	<ul class="be-widget-radio">
		<li>
			<input type="radio" id="<?php echo $this->get_field_id( '2' ); ?>" name="<?php echo $this->get_field_name( 'fl' ); ?>" value="2" <?php checked( $selected_option, '2' ); ?>>
			<label for="<?php echo $this->get_field_id( '2' ); ?>">2栏</label>
		</li>
		<li>
			<input type="radio" id="<?php echo $this->get_field_id( '3' ); ?>" name="<?php echo $this->get_field_name( 'fl' ); ?>" value="3" <?php checked( $selected_option, '3' ); ?>>
			<label for="<?php echo $this->get_field_id( '3' ); ?>">3栏</label>
		</li>
		<li>
			<input type="radio" id="<?php echo $this->get_field_id( '4' ); ?>" name="<?php echo $this->get_field_name( 'fl' ); ?>" value="4" <?php checked( $selected_option, '4' ); ?>>
			<label for="<?php echo $this->get_field_id( '4' ); ?>">4栏</label>
		</li>
		<li>
			<input type="radio" id="<?php echo $this->get_field_id( '5' ); ?>" name="<?php echo $this->get_field_name( 'fl' ); ?>" value="5" <?php checked( $selected_option, '5' ); ?>>
			<label for="<?php echo $this->get_field_id( '5' ); ?>">5栏</label>
		</li>
		<li>
			<input type="radio" id="<?php echo $this->get_field_id( '6' ); ?>" name="<?php echo $this->get_field_name( 'fl' ); ?>" value="6" <?php checked( $selected_option, '6' ); ?>>
			<label for="<?php echo $this->get_field_id( '6' ); ?>">6栏</label>
		</li>
	</ul>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

// 淘客
class be_tao_widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'be_tao_widget',
			'description' => '调用最新商品',
			'customize_selective_refresh' => true,
		);
		parent::__construct('be_tao_widget', '最新商品', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'title_z'      => '',
			'show_icon'    => '',
			'show_svg'     => '',
			'cat'          => '',
			'number'       => 4,
			'show_title'   => 1,
			'title'        => '最新商品',
		);
	}

	function widget($args, $instance) {
		extract($args);
		$title_w = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		$titleUrl = empty($instance['titleUrl']) ? '' : $instance['titleUrl'];
		$newWindow = !empty($instance['newWindow']) ? true : false;
		$hideTitle = !empty($instance['hideTitle']) ? true : false;
		if ( zm_get_option('more_im') ) {
			$more_i = '<span class="more-i more-im"><span></span><span></span><span></span></span>';
		} else {
			$more_i = '<span class="more-i"><span></span><span></span><span></span></span>';
		}
		echo $before_widget;
		if ($newWindow) $newWindow = "target='_blank'";
			if (!$hideTitle && $title) {
				if ($titleUrl) $title = "<a href='$titleUrl' $newWindow>$title $more_i</a>";
			}
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 4;
?>

<?php if ($instance['show_icon']) { ?>
	<h3 class="widget-title-cat-icon cat-w-icon"><a href="<?php echo $titleUrl; ?>" target="_blank"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?><?php more_i(); ?></a></h3>
<?php } ?>
<?php if ($instance['show_svg']) { ?>
	<h3 class="widget-title-cat-icon cat-w-icon"><a href="<?php echo $titleUrl; ?>" target="_blank"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?><?php more_i(); ?></a></h3>
<?php } ?>
<div class="widget-flex-img">
	<?php
		$args = array(
			'post_type' => 'tao',
			'showposts' => $number, 
			);

			if ($instance['cat']) {
				$args = array(
					'showposts' => $number, 
					'tax_query' => array(
						array(
							'taxonomy' => 'taobao',
							'terms' => $instance['cat']
						),
					)
				);
			}

			$fl = isset( $instance['fl'] ) ? $instance['fl'] : '';
 		?>
	<div class="wimg-main">
		<?php $be_query = new WP_Query($args); while ($be_query->have_posts()) : $be_query->the_post(); ?>
			<div class="wimg-item wimg-<?php echo $fl; ?>">
				<div class="wimg-area">
					<?php if ( $instance['show_title'] ) { ?>
						<div class="img-title"><a href="<?php the_permalink() ?>" rel="bookmark" <?php echo goal(); ?>><?php the_title( sprintf( '<span class="img-title-t over">' ), '</span>' ); ?></a></div>
					<?php } ?>
					<?php echo tao_thumbnail(); ?>
					<?php if ( get_post_meta( get_the_ID(), 'pricex', true ) ) { ?>
						<div class="single-goods-pricex"><?php echo get_post_meta( get_the_ID(), 'pricex', true ); ?>元</div>
					<?php } ?>
					<div class="single-goods-pricex"><?php be_vip_meta(); ?></div>
				</div>
			</div>
		<?php endwhile;?>
		<?php wp_reset_postdata(); ?>
		<div class="clear"></div>
	</div>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['hideTitle'] = !empty($new_instance['hideTitle']) ? 1 : 0;
		$instance['newWindow'] = !empty($new_instance['newWindow']) ? 1 : 0;
		$instance['show_title'] = !empty($new_instance['show_title']) ? 1 : 0;
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['show_svg'] = strip_tags($new_instance['show_svg']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['titleUrl'] = strip_tags($new_instance['titleUrl']);
		$instance['number'] = strip_tags($new_instance['number']);
		$instance['cat'] = $new_instance['cat'];
		$instance['fl'] = ! empty( $new_instance['fl'] ) ? sanitize_text_field( $new_instance['fl'] ) : '';
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$selected_option = isset( $instance['fl'] ) ? $instance['fl'] : '2';
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '最新商品';
		}
	global $wpdb;
	$instance = wp_parse_args((array) $instance, array('number' => '4'));
	$number = strip_tags($instance['number']);
	$instance = wp_parse_args((array) $instance, array('titleUrl' => ''));
	$titleUrl = $instance['titleUrl'];
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">单色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_svg'); ?>">彩色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_svg'); ?>" name="<?php echo $this->get_field_name('show_svg'); ?>" type="text" value="<?php echo $instance['show_svg']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('titleUrl'); ?>">标题链接：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('titleUrl'); ?>" name="<?php echo $this->get_field_name('titleUrl'); ?>" type="text" value="<?php echo $titleUrl; ?>" />
	</p>
	<p>
		<input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" class="checkbox" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
		<label for="<?php echo $this->get_field_id('newWindow'); ?>">在新窗口打开标题链接</label>
	</p>
	<p>
		<input type="checkbox" id="<?php echo $this->get_field_id( 'show_title' ); ?>" class="checkbox" name="<?php echo $this->get_field_name( 'show_title' ); ?>" <?php checked( isset( $instance['show_title'] ) ? $instance['show_title'] : 0 ); ?> />
		<label for="<?php echo $this->get_field_id( 'show_title' ); ?>">文章标题</label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
		<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => '全部分类', 'hide_empty'=>0, 'hierarchical'=>1,	'taxonomy' => 'taobao', 'selected'=>$instance['cat'])); ?></label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>">显示数量：</label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>
	<ul class="be-widget-radio">
		<li>
			<input type="radio" id="<?php echo $this->get_field_id( '2' ); ?>" name="<?php echo $this->get_field_name( 'fl' ); ?>" value="2" <?php checked( $selected_option, '2' ); ?>>
			<label for="<?php echo $this->get_field_id( '2' ); ?>">2栏</label>
		</li>
		<li>
			<input type="radio" id="<?php echo $this->get_field_id( '3' ); ?>" name="<?php echo $this->get_field_name( 'fl' ); ?>" value="3" <?php checked( $selected_option, '3' ); ?>>
			<label for="<?php echo $this->get_field_id( '3' ); ?>">3栏</label>
		</li>
		<li>
			<input type="radio" id="<?php echo $this->get_field_id( '4' ); ?>" name="<?php echo $this->get_field_name( 'fl' ); ?>" value="4" <?php checked( $selected_option, '4' ); ?>>
			<label for="<?php echo $this->get_field_id( '4' ); ?>">4栏</label>
		</li>
		<li>
			<input type="radio" id="<?php echo $this->get_field_id( '5' ); ?>" name="<?php echo $this->get_field_name( 'fl' ); ?>" value="5" <?php checked( $selected_option, '5' ); ?>>
			<label for="<?php echo $this->get_field_id( '5' ); ?>">5栏</label>
		</li>
		<li>
			<input type="radio" id="<?php echo $this->get_field_id( '6' ); ?>" name="<?php echo $this->get_field_name( 'fl' ); ?>" value="6" <?php checked( $selected_option, '6' ); ?>>
			<label for="<?php echo $this->get_field_id( '6' ); ?>">6栏</label>
		</li>
	</ul>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

// 多功能小工具
class php_text extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'php_text',
			'description' => '支持HTML、JavaScript、短代码等',
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'php_text', '增强文本', $widget_ops );
	}

	public function zm_defaults() {
		return array(
			'title_z'     => '',
			'show_icon'   => '',
			'show_svg'    => '',
			'text'        => '',
			'padding'     => '',
		);
	}

	function widget( $args, $instance ) {

		if (!isset($args['widget_id'])) {
			$args['widget_id'] = null;
		}

		extract($args);
		$title_w = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		$titleUrl = empty($instance['titleUrl']) ? '' : $instance['titleUrl'];
		$cssClass = empty($instance['cssClass']) ? '' : $instance['cssClass'];
		$text = apply_filters('widget_enhanced_text', $instance['text'], $instance);
		$hideTitle = !empty($instance['hideTitle']) ? true : false;
		$padding = !empty($instance['padding']) ? true : false;
		$newWindow = !empty($instance['newWindow']) ? true : false;
		$filterText = !empty($instance['filter']) ? true : false;
		$bare = !empty($instance['bare']) ? true : false;
		if ( zm_get_option('more_im') ) {
			$more_i = '<span class="more-i more-im"><span></span><span></span><span></span></span>';
		} else {
			$more_i = '<span class="more-i"><span></span><span></span><span></span></span>';
		}
		if ( $cssClass ) {
			if ( strpos($before_widget, 'class') === false ) {
				$before_widget = str_replace('>', 'class="'. $cssClass . '"', $before_widget);
			} else {
				$before_widget = str_replace('class="', 'class="'. $cssClass . ' ', $before_widget);
			}
		}

		ob_start();
		echo $text;
		$text = ob_get_contents();
		ob_end_clean();
		$text = do_shortcode($text);

		if ( !empty( $text ) ) {
			echo $bare ? '' : $before_widget;
		if ($newWindow) $newWindow = 'target="_blank"';
			if (!$hideTitle && $title) {
				if ($titleUrl) $title = "<a href='$titleUrl' $newWindow>$title $more_i</a>";
				echo $bare ? $title : $before_title . $title_w . $title . $after_title;
			}
			if ($instance['show_icon']) {
				if ($instance['titleUrl']) {
					echo '<h3 class="widget-title-cat-icon cat-w-icon"><a href=' . $titleUrl . ' ' . $newWindow . '><i class="t-icon ' . $instance['show_icon'] . '"></i>' . $instance['title_z'], more_i() . '</a></h3>';
				} else {
					echo '<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon ' . $instance['show_icon'] . '"></i>' . $instance['title_z'] . '</h3>';
				}
			}

			if ($instance['show_svg']) {
				if ($instance['titleUrl']) {
					echo '<h3 class="widget-title-cat-icon cat-w-icon"><a href=' . $titleUrl . '><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#'.$instance['show_svg'].'"></use></svg>' . $instance['title_z'], more_i() . '</a></h3>';
				} else {
					echo '<h3 class="widget-title-cat-icon cat-w-icon"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#'.$instance['show_svg'].'"></use></svg>' . $instance['title_z'] . '</h3>';
				}
			}

			if ( $instance['padding'] ) {
				echo $bare ? '' : '<div class="textwidget widget-text widget-text-padding">';
			} else {
				echo $bare ? '' : '<div class="textwidget widget-text">';
			}
			echo $filterText ? wpautop($text) : $text;
			echo $bare ? '' : '</div>' . $after_widget;
		}
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		if ( current_user_can('unfiltered_html') )
			$instance['text'] =  $new_instance['text'];
		else
			$instance['text'] = wp_filter_post_kses($new_instance['text']);
			$instance['title_z'] = strip_tags($new_instance['title_z']);
			$instance['show_icon'] = strip_tags($new_instance['show_icon']);
			$instance['show_svg'] = strip_tags($new_instance['show_svg']);
			$instance['titleUrl'] = strip_tags($new_instance['titleUrl']);
			$instance['hideTitle'] = !empty($new_instance['hideTitle']) ? 1 : 0;
			$instance['padding'] = !empty($new_instance['padding']) ? 1 : 0;
			$instance['newWindow'] = !empty($new_instance['newWindow']) ? 1 : 0;
			$instance['filter'] = !empty($new_instance['filter']) ? 1 : 0;
			$instance['bare'] = !empty($new_instance['bare']) ? 1 : 0;
		return $instance;
	}

	function form( $instance ) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$instance = wp_parse_args( (array) $instance, array(
			'title' => '增强文本',
			'titleUrl' => '',
			'text' => ''
		));
		$title = $instance['title'];
		$titleUrl = $instance['titleUrl'];
		$text = format_to_edit($instance['text']);
	?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('titleUrl'); ?>">标题链接：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('titleUrl'); ?>" name="<?php echo $this->get_field_name('titleUrl'); ?>" type="text" value="<?php echo $titleUrl; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('show_icon'); ?>">单色图标代码：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('show_svg'); ?>">彩色图标代码：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('show_svg'); ?>" name="<?php echo $this->get_field_name('show_svg'); ?>" type="text" value="<?php echo $instance['show_svg']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('text'); ?>">内容：</label>
			<textarea class="widefat monospace" rows="6" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>
		</p>

		<p>
			<input id="<?php echo $this->get_field_id('hideTitle'); ?>" class="checkbox" name="<?php echo $this->get_field_name('hideTitle'); ?>" type="checkbox" <?php checked(isset($instance['hideTitle']) ? $instance['hideTitle'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('hideTitle'); ?>">不显示标题</label>
		</p>
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" class="checkbox" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
			<label for="<?php echo $this->get_field_id('newWindow'); ?>">在新窗口打开标题链接</label>
		</p>

		<p>
			<input id="<?php echo $this->get_field_id('filter'); ?>" class="checkbox" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>">自动添加段落</label>
		</p>

		<p>
			<input id="<?php echo $this->get_field_id('padding'); ?>" class="checkbox" name="<?php echo $this->get_field_name('padding'); ?>" type="checkbox" <?php checked(isset($instance['padding']) ? $instance['padding'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('padding'); ?>">内边距</label>
		</p>
<?php }
}

function php_text_init() {
	register_widget( 'php_text' );
}
add_action( 'widgets_init', 'php_text_init' );

// 即将发布
class be_timing_post extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'be_timing_post',
			'description' => '即将发表的文章',
			'customize_selective_refresh' => true,
		);
		parent::__construct('be_timing_post', '即将发布', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'title_z'       => '',
			'show_icon'     => '',
			'show_svg'      => '',
			'number'        => 5,
			'title'         => '即将发布',
		);
	}

	function widget($args, $instance) {
		extract($args);
		$title_w = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
?>

<div class="timing_post">
	<?php if ($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<ul>
		<?php
			$be_query = new WP_Query( array ( 'post_status' => 'future', 'order' => 'ASC', 'showposts' => $number, 'ignore_sticky_posts' => '1'));
			if ($be_query->have_posts()) {
				while ($be_query->have_posts()) : $be_query->the_post();
					$do_not_duplicate = get_the_ID(); ?>
					<li><i class="be be-schedule"> <?php the_time('G:i') ?></i><?php the_title(); ?></li>
				<?php endwhile; wp_reset_postdata();
			} else {
				echo '<li>'. __( '暂无文章', 'begin' ) .'</li>';
			}
		?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['show_svg'] = strip_tags($new_instance['show_svg']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = strip_tags($new_instance['number']);
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
	} else {
		$title = '即将发布';
	}
	global $wpdb;
	$instance = wp_parse_args((array) $instance, array('number' => '5'));
	$number = strip_tags($instance['number']);
?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">单色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_svg'); ?>">彩色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_svg'); ?>" name="<?php echo $this->get_field_name('show_svg'); ?>" type="text" value="<?php echo $instance['show_svg']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>">显示数量：</label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
function be_timing_post_init() {
	register_widget( 'be_timing_post' );
}
add_action( 'widgets_init', 'be_timing_post_init' );

// 作者墙
class Be_Author_Widget extends WP_Widget {
	public function __construct() {
		parent::__construct(
			'author_widget',
			'作者墙',
			array(
				'classname' => 'author_widget',
				'description' => '显示所有作者头像',
				'customize_selective_refresh' => true,
			)
		);
	}

	public function zm_defaults() {
		return array(
			'exclude_author'  => '',
			'number'          => '8',
			'title'           => '作者墙',
		);
	}

	public function widget( $args, $instance ) {
		extract( $args );
		$title_w = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $before_widget;

		if ( ! empty( $title ) ) {
			echo $before_title . $title_w . $title . $after_title; 
		}
	?>
	<div class="author_widget_box">
		<?php 

			$authors = get_users(
				array(
					'orderby' => 'post_count',
					'order'   => 'DESC',
					'number'  => $instance['number'],
					'exclude' => explode( ',', $instance['exclude_author'] )
				)
			);

			foreach ( $authors as $author ) { 
		?>

			<?php if ( count_user_posts( $author->ID ) > 0 ) { ?>
				<ul class="xl9">
					<li class="author_box">
						<a href="<?php echo get_author_posts_url( $author->ID ); ?>" target="_blank">
							<span class="load">
								<?php
									if ( zm_get_option('cache_avatar' ) ) {
										echo begin_avatar( $author->user_email, $size = 96, '', $author->display_name );
									} else {
										if ( !zm_get_option( 'avatar_load' ) ) {
											echo get_avatar( $author->user_email, $size = 96, '', $author->display_name );
										} else {
											echo '<img class="avatar photo" src="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=" alt="' . $author->display_name . '"  width="96" height="96" data-original="' . preg_replace( array('/^.+(src=)(\"|\')/i', '/(\"|\')\sclass=(\"|\').+$/i'), array('', ''), get_avatar( $author->user_email, $size = 96, '', $author->display_name ) ) . '" />';
										}
									}
								?>
							</span>
							<span class="clear"></span>
							<?php echo $author->display_name; ?>
							<span class="author-count"><?php echo count_user_posts( $author->ID ); ?> 篇</span>
						</a>
					</li>
				</ul>
			<?php } ?>
		<?php } ?>
		<div class="clear"></div>
	</div>

	<?php
		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['exclude_author'] = $new_instance['exclude_author'];
		$instance['number'] = $new_instance['number'];
		return $instance;
	}

	public function form( $instance ) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( ( array ) $instance, $defaults );
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>">显示数量：</label>
			<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $instance['number']; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'exclude_author' ); ?>">排除的作者ID，多个ID用半角英文逗号隔开：</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'exclude_author' ); ?>" name="<?php echo $this->get_field_name( 'exclude_author' ); ?>" type="text" value="<?php echo $instance['exclude_author']; ?>" />
		</p>
	<?php }
}

function author_widget_init() {
	register_widget( 'Be_Author_Widget' );
}
add_action( 'widgets_init', 'author_widget_init' );

// 用户墙
class Be_Registered_user_Widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'readers',
			'description' => '最近注册的用户',
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'Be_Registered_user_Widget', '用户墙', $widget_ops );
	}

	public function zm_defaults() {
		return array(
			'title_z'  => '',
			'show_icon'=> '',
			'show_svg' => '',
			'number'   => 12,
			'exclude'  => 1,
			'title'    => '用户墙',
			'orderby'  => 'DESC',
		);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title_w = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( ( array ) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title;
		$number = strip_tags( $instance['number']) ? absint( $instance['number'] ) : 12;
		$exclude = strip_tags( $instance['exclude'] ) ? absint( $instance['exclude'] ) : 1;
		$orderby = strip_tags($instance['orderby']) ? absint( $instance['orderby'] ) : DESC;
?>
<?php if ($instance['show_icon']) { ?>
	<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
<?php } ?>
<?php if ($instance['show_svg']) { ?>
	<h3 class="widget-title-cat-icon cat-w-icon"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
<?php } ?>
<div id="readers_widget" class="readers">
<?php

	$args = array(
		'orderby' => 'registered',
		'order'   => $instance['orderby'],
		'number'  => $number,
		'exclude' => explode( ',', $instance['exclude'] ),
	);

	$latest_users = get_users( $args );
	$mostactive = '';
	foreach ( $latest_users as $user ) {
		if ( ! zm_get_option( 'avatar_load' ) ) {
			$mostactive .= '<div class="readers-avatar">
			<span class="dzq">
			<a href="' . get_author_posts_url( $user->ID ) . '" target="_blank" rel="external nofollow">' . get_avatar( $user->ID, '96', '', get_the_author_meta( 'nickname', $user->ID ) ) . '</a>
			</span>
			<span class="readers-inf"><strong>' . get_the_author_meta( 'nickname', $user->ID ) . ' </strong></span></div>';
		} else {
			$mostactive .= '<div class="readers-avatar load">
			<span class="dzq">
			<a href="' . get_author_posts_url( $user->ID ) . '" target="_blank" rel="external nofollow"><img class="avatar photo" src="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=" alt="'. get_the_author_meta( 'nickname', $user->ID ) .'" width="96" height="96" data-original="' . preg_replace( array( '/^.+(src=)(\"|\')/i', '/(\"|\')\sclass=(\"|\').+$/i' ), array( '', '' ), get_avatar( $user->ID, 96, '', get_the_author_meta( 'nickname', $user->ID ) ) ) . '" /></a>
			</span>
			<span class="readers-inf"><strong>' . get_the_author_meta( 'nickname', $user->ID ) . ' </strong></span></div>';
		}
	}
	echo $mostactive;
?>
<div class="clear"></div>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['show_svg'] = strip_tags($new_instance['show_svg']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = strip_tags($new_instance['number']);
		$instance['exclude'] = strip_tags($new_instance['exclude']);
		$instance['orderby'] = strip_tags($new_instance['orderby']);
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '用户墙';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '12'));
		$instance = wp_parse_args((array) $instance, array('exclude' => '1'));
		$number = strip_tags($instance['number']);
		$instance = wp_parse_args((array) $instance, array('orderby' => 'DESC'));
		$orderby = strip_tags($instance['orderby']);
 ?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">单色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_svg'); ?>">彩色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_svg'); ?>" name="<?php echo $this->get_field_name('show_svg'); ?>" type="text" value="<?php echo $instance['show_svg']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>">显示数量：</label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('exclude'); ?>">排除管理员及他用户ID：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('exclude'); ?>" name="<?php echo $this->get_field_name('exclude'); ?>" type="text" value="<?php echo $instance['exclude']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('orderby'); ?>">排序：</label>
		<select name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>" class="widefat">
			<option value="DESC"<?php selected( $instance['orderby'], 'DESC' ); ?>>新的在上面</option>
			<option value="ASC"<?php selected( $instance['orderby'], 'ASC' ); ?>>旧的在上面</option>
		</select>
	</p>

	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

function Be_Registered_user_Widget_init() {
	register_widget( 'Be_Registered_user_Widget' );
}
add_action( 'widgets_init', 'Be_Registered_user_Widget_init' );

// 关于作者
class about_author extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'about_author',
			'description' => '只显示在正文和作者页面',
			'customize_selective_refresh' => true,
		);
		parent::__construct('about_author', '关于作者', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_inf'      => 1,
			'show_meta'     => 1,
			'show_posts'    => 1,
			'show_comment'  => 1,
			'show_views'    => 1,
			'show_like'     => 1,
			'show_role'     => 1,
			'author_back'   => get_template_directory_uri() . '/img/default/options/user.jpg',
		);
	}

	function widget($args, $instance) {
		extract($args);
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( is_author() || is_single() ){ 
			echo $before_widget;
     	}
?>

<?php if ( is_author() || is_single() ) { ?>
<?php
	global $wpdb;
	$author_id = get_the_author_meta( 'ID' );
	$comment_count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->comments  WHERE comment_approved='1' AND user_id = '$author_id' AND comment_type not in ('trackback','pingback')" );
?>
<div id="about_author_widget">
	<div class="author-meta-box">
		<?php if ( $instance[ 'author_back' ]  ) { ?>
			<div class="author-back" style="background-image: url('<?php echo $instance['author_back']; ?>');"></div>
		<?php } ?>
		<div class="author-meta">
			<?php if ( get_option( 'show_avatars' ) ) { ?>
				<div class="author-avatar">
					<div class="author-avatar-box load">
						<?php if (zm_get_option('cache_avatar')) { ?>
							<?php echo begin_avatar( get_the_author_meta('user_email'), 96, '', get_the_author() ); ?>
						<?php } else { ?>
							<?php be_avatar_author(); ?>
						<?php } ?>
						<div class="clear"></div>
					</div>
				</div>
			<?php } else { ?>
				<div class="author-avatar-place"><i class="be be-timerauto"></i></div>
			<?php } ?>
			<h4 class="author-the"><?php the_author(); ?></h4>
			<?php if ($instance['show_role']) { ?>
				<?php 
					if (be_check_user_role(array('administrator'), $author_id)) {
						echo '<div class="the-role the-role1 ease">'. __( '管理员', 'begin' ) .'</div>';
					}
					if (be_check_user_role(array('editor'), $author_id)) {
						echo '<div class="the-role the-role2 ease">'. __( '本站编辑', 'begin' ) .'</div>';
					}
					if (be_check_user_role(array('author'), $author_id)) {
						echo '<div class="the-role the-role3 ease">'. __( '专栏作者', 'begin' ) .'</div>';
					}
					if (be_check_user_role(array('contributor'), $author_id)) {
						echo '<div class="the-role the-role4 ease">'. __( '自由撰稿人', 'begin' ) .'</div>';
					}
				?>
			<?php } ?>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
		<div class="author-th">
			<?php if ( get_the_author_meta( 'description' ) ) { ?>
				<div class="author-description"><?php the_author_meta( 'user_description' ); ?></div>
			<?php } else { ?>
				<div class="author-description">暂无个人说明</div>
			<?php } ?>
	
			<?php if ($instance['show_meta']) { ?>
				<div class="feed-about-box">
					<?php if ( get_the_author_meta( 'weixin' ) ) { ?>
						<div class="feed-t weixin">
							<div class="weixin-b">
								<div class="weixin-qr fd">
									<div class="weixin-qr-about">
										<div class="copy-weixin">
											<img src="<?php the_author_meta( 'weixinqr' ); ?>" alt=" weixin"/>
											<div class="be-copy-text"><?php _e( '点击复制', 'begin' ); ?></div>
											<div class="weixinbox">
												<div class="btn-weixin-copy"></div>
												<div class="weixin-id"><?php the_author_meta( 'weixin' ); ?></div>
												<div class="copy-success-weixin fd"><div class="copy-success-weixin-text aboutweixin"><span class="dashicons dashicons-saved"></span><?php _e( '已复制', 'begin' ); ?></div></div>
											</div>
										</div>
										<div class="clear"></div>
									</div>
									<div class="arrow-down"></div>
								</div>
								<a><i class="be be-weixin"></i></a>
							</div>
						</div>
					<?php } ?>

					<?php if ( get_the_author_meta( 'phone' ) ) { ?>
						<div class="feed-t weixin author-phone">
							<div class="weixin-b">
								<div class="weixin-qr fd">
									<div class="weixin-qr-about">
										<div class="copy-weixin">
											<div class="phone-number"><div class="be-copy-text"><?php _e( '点击复制', 'begin' ); ?></div><?php the_author_meta( 'phone' ); ?></div>
											<div class="weixinbox">
												<div class="btn-weixin-copy"></div>
												<div class="weixin-id"><?php the_author_meta( 'phone' ); ?></div>
												<div class="copy-success-weixin fd"><div class="copy-success-weixin-text telphone"><span class="dashicons dashicons-saved"></span><?php _e( '已复制', 'begin' ); ?></div></div>
											</div>
										</div>
										<div class="clear"></div>
									</div>
									<div class="arrow-down"></div>
								</div>
								<a><i class="be be-phone"></i></a>
							</div>
						</div>
					<?php } ?>

					<?php if ( get_the_author_meta( 'qq' ) ) { ?>
						<div class="feed-t tqq"><a class="quoteqq" href="https://wpa.qq.com/msgrd?V=3&uin=<?php the_author_meta( 'qq' ); ?>&Site=QQ&Menu=yes" onclick="copyToClipboard(this)" title="<?php _e( '点击复制', 'begin' ); ?>" target=blank rel="external nofollow"><i class="be be-qq"></i></a></div>
					<?php } ?>
				</div>
			<?php } ?>

			<?php if ($instance['show_inf']) { ?>
				<div class="author-th-inf<?php if ( get_bloginfo( 'language' ) === 'en-US' ) { ?> author-th-inf-en<?php } ?>">
					<?php if ($instance['show_posts'] ) { ?><div class="author-n author-nickname"><?php _e( '文章', 'begin' ); ?><br /><span><?php the_author_posts(); ?></span></div><?php } ?>
					<?php if ($instance['show_comment'] ) { ?><div class="author-n"><?php _e( '评论', 'begin' ); ?><br /><span><?php echo $comment_count;?></span></div><?php } ?>
					<?php if ($instance['show_views'] && zm_get_option('post_views')) { ?><div class="author-n"><?php _e( '浏览', 'begin' ); ?><br /><span><?php author_posts_views(get_the_author_meta('ID'));?></span></div><?php } ?>
					<?php if ($instance['show_like'] && zm_get_option('post_views')) { ?><div class="author-n author-th-views"><?php _e( '点赞', 'begin' ); ?><br /><span><?php like_posts_views(get_the_author_meta('ID'));?></span></div><?php } ?>
				</div>
			<?php } ?>
			<div class="author-m"><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>"><i class="be be-more"></i></a></div>
			<div class="clear"></div>
		</div>
	<div class="clear"></div>
	</div>
</div>
<?php } ?>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['show_inf']     = !empty($new_instance['show_inf']) ? 1 : 0;
		$instance['show_meta']    = !empty($new_instance['show_meta']) ? 1 : 0;
		$instance['show_posts']   = !empty($new_instance['show_posts']) ? 1 : 0;
		$instance['show_comment'] = !empty($new_instance['show_comment']) ? 1 : 0;
		$instance['show_views']   = !empty($new_instance['show_views']) ? 1 : 0;
		$instance['show_like']    = !empty($new_instance['show_like']) ? 1 : 0;
		$instance['show_role']    = !empty($new_instance['show_role']) ? 1 : 0;
		$instance['title']        = strip_tags( $new_instance['title'] );
		$instance['author_back']  = $new_instance['author_back'];
		// $instance['author_url'] = $new_instance['author_url'];
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('author_back' => get_template_directory_uri() . '/img/default/options/user.jpg'));
		$author_back = $instance['author_back'];
		// $instance = wp_parse_args((array) $instance, array('author_url' => ''));
		// $author_url = $instance['author_url'];
?>
	<p>
		<label for="<?php echo $this->get_field_id('author_back'); ?>">背景图片：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'author_back' ); ?>" name="<?php echo $this->get_field_name( 'author_back' ); ?>" type="text" value="<?php echo $author_back; ?>" />
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_meta') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_meta') ); ?>" <?php checked( (bool) $instance["show_meta"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_meta') ); ?>">联系方式</label>
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_inf') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_inf') ); ?>" <?php checked( (bool) $instance["show_inf"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_inf') ); ?>">显示信息</label>
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_posts') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_posts') ); ?>" <?php checked( (bool) $instance["show_posts"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_posts') ); ?>">文章</label>
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_comment') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_comment') ); ?>" <?php checked( (bool) $instance["show_comment"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_comment') ); ?>">评论</label>
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_views') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_views') ); ?>" <?php checked( (bool) $instance["show_views"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_views') ); ?>">浏览</label>
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_like') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_like') ); ?>" <?php checked( (bool) $instance["show_like"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_like') ); ?>">点赞</label>
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_role') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_role') ); ?>" <?php checked( (bool) $instance["show_role"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_role') ); ?>">角色</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
function about_author_init() {
	register_widget( 'about_author' );
}
add_action( 'widgets_init', 'about_author_init' );

// 最近更新过的文章
class be_updated_posts extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'be_updated_posts',
			'description' => '调用最近更新过的文章',
			'customize_selective_refresh' => true,
		);
		parent::__construct('be_updated_posts', '最近更新过的文章', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'title_z'       => '',
			'show_icon'     => '',
			'show_svg'      => '',
			'number'        => 5,
			'days'          => 15,
			'title'         => '最近更新过的文章',
		);
	}

	function widget($args, $instance) {
		extract($args);
		$title_w = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( ( array ) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title;
		$number = strip_tags( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$days = strip_tags( $instance['days'] ) ? absint( $instance['days'] ) : 15;
?>

<div class="new_cat">
	<?php if ( $instance['show_icon'] ) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ( $instance['show_svg'] ) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<ul>
		<?php if ( function_exists( 'be_updated_posts' ) ) be_updated_posts( $number, $days ); ?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['show_svg'] = strip_tags($new_instance['show_svg']);
		$instance['number'] = strip_tags($new_instance['number']);
		$instance['days'] = strip_tags($new_instance['days']);
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '最近更新过的文章';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$instance = wp_parse_args((array) $instance, array('days' => '15'));
		$number = strip_tags($instance['number']);
		$days = strip_tags($instance['days']);
 ?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">单色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_svg'); ?>">彩色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_svg'); ?>" name="<?php echo $this->get_field_name('show_svg'); ?>" type="text" value="<?php echo $instance['show_svg']; ?>" />
	</p>
	<p><label for="<?php echo $this->get_field_id('number'); ?>">显示数量：</label>
	<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" value="<?php echo $number; ?>" size="3" /></p>
	<p><label for="<?php echo $this->get_field_id('days'); ?>">限制时间（天）：</label>
	<input id="<?php echo $this->get_field_id( 'days' ); ?>" name="<?php echo $this->get_field_name( 'days' ); ?>" type="number" value="<?php echo $days; ?>" size="3" /></p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
function be_updated_posts_init() {
	register_widget( 'be_updated_posts' );
}
add_action( 'widgets_init', 'be_updated_posts_init' );

// 用户登录
class Be_Ajax_login extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'ajax-login-widget',
			'description' => 'Ajax 登录注册小工具',
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'Be_Ajax_login', '登录注册', $widget_ops );
	}

	public function zm_defaults() {
		return array(
			'login_back'   => get_template_directory_uri() . '/img/default/options/user.jpg',
			'login_avatar' => get_template_directory_uri() . '/img/favicon.png"',
			'login_tip'    => '访客，请登录！',
		);
	}

	function widget( $args, $instance ) {
		extract($args);
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		echo $args['before_widget'];
	?>

	<div class="be-login-widget">
		<?php if ( ! is_user_logged_in() ) { ?>
			<?php if ( ! zm_get_option( 'only_social_login' ) ) { ?>
				<div class="sidebox">
					<?php if ( $instance[ 'login_back' ]  ) { ?>
						<div class="author-back"><img src="<?php echo $instance['login_back']; ?>" alt="bj"/></div>
					<?php } ?>

					<div class="usericon load">
						<img alt="<?php echo $instance['login_tip']; ?>" src="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=" data-original="<?php echo $instance['login_avatar']; ?>" class="default-avatar" height="96" width="96">
					</div>

					<div class="no-login-name">
						<?php if ( isset( $_COOKIE["comment_author_" . COOKIEHASH] ) && $_COOKIE["comment_author_" . COOKIEHASH] != "" ) { ?>
							<?php printf( __('%s，您还未登录！'), $_COOKIE["comment_author_" . COOKIEHASH] ) ?>
						<?php } else { ?>
							<?php echo $instance['login_tip']; ?>
						<?php } ?>
					</div>
					<div class="login-form">
						<?php do_action( 'be_login_form' ); ?>
						<?php do_action( 'login_form' ); ?>
					</div>
					<div class="userinfo">
						<div>
							<?php if ( ! zm_get_option( 'user_l' ) == '' ) { ?>
								<span class="widget-login-reg-btn widget-login-btn-l"><a href="<?php echo zm_get_option( 'user_l' ); ?>" rel="external nofollow" target="_blank"><?php _e( '登录', 'begin' ); ?></a></span>
							<?php } else { ?>
								<span class="widget-login-reg-btn widget-login-btn show-layer<?php echo cur(); ?>"><?php _e( '登录', 'begin' ); ?></span>
							<?php } ?>
							<?php if ( zm_get_option( 'menu_reg' ) && get_option( 'users_can_register' ) ) { ?>
								 <span class="widget-login-reg-btn widget-reg-btn"><a href="<?php echo zm_get_option( 'reg_l' ); ?>" rel="external nofollow" target="_blank"><?php _e( '注册', 'begin' ); ?></a></span>
							 <?php } ?>
							<div class="clear"></div>
						</div>
					</div>
				</div>
			<?php } else { ?>
				<?php only_social(); ?>
			<?php } ?>
		<?php } else { ?>
			<?php be_login_reg(); ?>
		<?php } ?>
	</div>

	<?php
		echo $args['after_widget'];
	}
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance = array();
			$instance['login_back']   = $new_instance['login_back'];
			$instance['login_avatar'] = $new_instance['login_avatar'];
			$instance['login_tip']    = $new_instance['login_tip'];
			return $instance;
		}
		function form($instance) {
			global $wpdb;
			$defaults     = $this -> zm_defaults();
			$instance     = wp_parse_args( ( array ) $instance, $defaults );
			$login_back   = $instance['login_back'];
			$login_avatar = $instance['login_avatar'];
			$login_tip    = $instance['login_tip'];
	?>

	<p>
		<label for="<?php echo $this->get_field_id( 'login_back' ); ?>">背景图片：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'login_back' ); ?>" name="<?php echo $this->get_field_name( 'login_back' ); ?>" type="text" value="<?php echo $login_back; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('login_avatar'); ?>">默认头像 ( 正方图片 )：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'login_avatar' ); ?>" name="<?php echo $this->get_field_name( 'login_avatar' ); ?>" type="text" value="<?php echo $login_avatar; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'login_tip' ); ?>">提示文字：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'login_tip' ); ?>" name="<?php echo $this->get_field_name( 'login_tip' ); ?>" type="text" value="<?php echo $login_tip; ?>" />
	</p>

	<input type="hidden" id="<?php echo $this->get_field_id( 'submit' ); ?>" name="<?php echo $this->get_field_name( 'submit' ); ?>" value="1" />
<?php }
}

function ajax_login_init() {
	register_widget( 'Be_Ajax_login' );
}
add_action( 'widgets_init', 'ajax_login_init' );

// 留言板
class be_pages_recent_comments extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'be_pages_recent_comments',
			'description' => '调用指定文章/页面留言',
			'customize_selective_refresh' => true,
		);
		parent::__construct('be_pages_recent_comments', '留言板', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'title_z'       => '',
			'show_icon'     => '',
			'show_svg'      => '',
			'pages_id'      => '',
			'number'        => 5,
			'authornot'     => 1,
			'title'         => '留言板',
		);
	}

	function widget($args, $instance) {
		extract($args);
		$title_w = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
		$authornot = strip_tags($instance['authornot']) ? absint( $instance['authornot'] ) : 1;
?>

<div id="message" class="message-widget gaimg">
	<?php if ($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
	<?php } ?>

	<ul>
		<?php 
		$no_comments = false;
		$avatar_size = 64;
		$comments_query = new WP_Comment_Query();
		$comments = $comments_query->query( array_merge( array( 'number' => $number, 'status' => 'approve', 'type' => 'comments', 'post_status' => 'publish', 'post_id' => $instance["pages_id"], 'author__not_in' => explode(',',$instance["authornot"]) ) ) );
		if ( $comments ) : foreach ( $comments as $comment ) : ?>

		<li>
			<a href="<?php echo get_permalink($comment->comment_post_ID); ?>#anchor-comment-<?php echo $comment->comment_ID; ?>" title="<?php _e( '发表在', 'begin' ); ?>：<?php echo get_the_title($comment->comment_post_ID); ?>" rel="external nofollow" <?php echo goal(); ?>>
				<?php if (zm_get_option('cache_avatar')) { ?>
					<?php echo begin_avatar( $comment->comment_author_email, $avatar_size, '', get_comment_author( $comment->comment_ID ) ); ?>
				<?php } else { ?>
					<?php echo get_avatar( $comment->comment_author_email, $avatar_size, '', get_comment_author( $comment->comment_ID ) ); ?>
				<?php } ?>
				<span class="comment_author"><strong><?php echo get_comment_author( $comment->comment_ID ); ?></strong></span>
				<?php echo convert_smilies($comment->comment_content); ?>
			</a>
		</li>

		<?php endforeach; else : ?>
			<li><?php _e('暂无留言', 'begin'); ?></li>
			<?php $no_comments = true;
		endif; ?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['show_svg'] = strip_tags($new_instance['show_svg']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = strip_tags($new_instance['number']);
		$instance['pages_id'] = $new_instance['pages_id'];
		$instance['authornot'] = strip_tags($new_instance['authornot']);
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '留言板';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$number = strip_tags($instance['number']);
		$instance = wp_parse_args((array) $instance, array('authornot' => '1'));
		$authornot = strip_tags($instance['authornot']);
?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">单色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_svg'); ?>">彩色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_svg'); ?>" name="<?php echo $this->get_field_name('show_svg'); ?>" type="text" value="<?php echo $instance['show_svg']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('pages_id'); ?>">输入文章/页面ID：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('pages_id'); ?>" name="<?php echo $this->get_field_name('pages_id'); ?>" type="text" value="<?php echo $instance['pages_id']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('authornot'); ?>">排除的用户ID：</label>
		<p><input id="<?php echo $this->get_field_id( 'authornot' ); ?>" name="<?php echo $this->get_field_name( 'authornot' ); ?>" type="text" value="<?php echo $authornot; ?>" /></p>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>">显示数量：</label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

function be_pages_recent_comments_init() {
	register_widget( 'be_pages_recent_comments' );
}
add_action( 'widgets_init', 'be_pages_recent_comments_init' );

// Tab
class be_tabs extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'be_tabs',
			'description' => '最新文章、热评文章、热门文章、最近留言',
			'customize_selective_refresh' => true,
		);
		parent::__construct('be_tabs', '组合小工具', $widget_ops);
	}

	public function zm_get_defaults() {
		return array(
			'title'            => '',
			'tabs_category'    => 1,
			'tabs_date'        => 1,
			// Recent posts
			'recent_enable'     => 1,
			'recent_thumbs'     => 1,
			'recent_cat_id'     => '0',
			'recent_num'        => '5',
			// Popular posts
			'popular_enable'    => 1,
			'popular_thumbs'    => 1,
			'popular_cat_id'    => '0',
			'popular_time'      => '0',
			'popular_num'       => '5',
			// Recent comments
			'comments_enable'   => 1,
			'comments_avatars'  => 1,
			'comments_num'      => '5',
			'authornot'        => '1',
			// viewe
			'viewe_enable'     => 1,
			'viewe_thumbs'     => 1,
			'viewe_number'     => '5',
			'viewe_days'       => '90',
		);
	}

	private function _create_tabs($tabs,$count) {
		$titles = array(
			'recent'    => __('最新文章', 'begin'),
			'popular'   => __('热评文章', 'begin'),
			'viewe'     => __('热门文章', 'begin'),
			'comments'  => __('最近留言', 'begin')
		);
		$icons = array(
			'recent'   => 'be be-file',
			'popular'  => 'be be-favoriteoutline',
			'viewe'     => 'be be-eye',
			'comments' => 'be be-speechbubble'
		);

		$output = sprintf('<div class="zm-tabs-nav group tab-count-%s">', $count);
		foreach ( $tabs as $tab ) {
			$output .= sprintf('<span class="zm-tab tab-%1$s"><a href="javascript:"><i class="%3$s"></i><span>%4$s</span></a></span>',
				$tab,
				$tab . '-' . $this -> number,
				$icons[$tab],
				$titles[$tab]
			);
		}
		$output .= '</div>';
		return $output;
	}

	public function widget($args, $instance) {
		extract( $args );
		$title_w = title_i_w();
		$defaults = $this -> zm_get_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );

		$title = apply_filters('widget_title',$instance['title']);
		$title = empty( $title ) ? '' : $title;
		$output = $before_widget."\n";
		if ( ! empty( $title ) )
			$output .= $before_title . $title_w . $title . $after_title;
		ob_start();

	$tabs = array();
	$count = 0;
	$order = array(
		'recent'    => 1,
		'popular'   => 2,
		'viewe'     => 3,
		'comments'  => 4
	);
	asort($order);
	foreach ( $order as $key => $value ) {
		if ( $instance[$key.'_enable'] ) {
			$tabs[] = $key;
			$count++;
		}
	}
	if ( $tabs && ($count > 1) ) {
		$output .= $this->_create_tabs($tabs,$count);
	}
?>

	<div class="zm-tabs-container">
		<?php 
			global $post;
			if ( is_single() ) {
			$recent =  new WP_Query(array(
				'ignore_sticky_posts' => 1,
				'showposts' => $instance['recent_num'],
				'post__not_in' => array($post->ID),
				'cat' => $instance['recent_cat_id'],
			));
			} else {
			$recent =  new WP_Query(array(
				'ignore_sticky_posts' => 1,
				'showposts' => $instance['recent_num'],
				'cat' => $instance['recent_cat_id'],
			));
		} ?>
		<div class="new_cat">
			<ul id="tab-recent-<?php echo $this -> number ?>" class="zm-tab group <?php if ($instance['recent_thumbs']) { echo 'thumbs-enabled'; } ?>" style="display:block;">
				<h4><?php _e( '最新文章', 'begin' ); ?></h4>
				<?php while ($recent->have_posts()): $recent->the_post(); ?>
				<li>
					<?php if ($instance['recent_thumbs']) { ?>
						<span class="thumbnail">
							<?php echo zm_thumbnail(); ?>
						</span>
						<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark" <?php echo goal(); ?>><?php the_title(); ?></a></span>
						<?php grid_meta(); ?>
						<?php views_span(); ?>
					<?php } else { ?>
						<?php the_title( sprintf( '<a class="get-icon" href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a>' ); ?>
					<?php } ?>
				</li>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			</ul>
		</div>

		<?php
			$popular = new WP_Query( array(
				'post_type'             => array( 'post' ),
				'showposts'             => $instance['popular_num'],
				'cat'                   => $instance['popular_cat_id'],
				'ignore_sticky_posts'   => true,
				'orderby'               => 'comment_count',
				'order'                 => 'DESC',
				'date_query' => array(
					array(
						'after' => $instance['popular_time'],
					),
				),
			) );
		?>

		<div class="new_cat">
			<ul id="tab-popular-<?php echo $this -> number ?>" class="zm-tab group <?php if ($instance['popular_thumbs']) { echo 'thumbs-enabled'; } ?>">
				<h4><?php _e( '热评文章', 'begin' ); ?></h4>
				<?php while ( $popular->have_posts() ): $popular->the_post(); ?>
				<li>
					<?php if ($instance['popular_thumbs']) { ?>
						<span class="thumbnail">
							<?php echo zm_thumbnail(); ?>
						</span>
						<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark <?php echo goal(); ?>" <?php echo goal(); ?>><?php the_title(); ?></a></span>
						<?php grid_meta(); ?>
						<span class="discuss"><?php comments_number( '', '<i class="be be-speechbubble ri"></i>1 ', '<i class="be be-speechbubble ri"></i>%' ); ?></span>
					<?php } else { ?>
						<a class="get-icon" href="<?php the_permalink(); ?>" rel="bookmark <?php echo goal(); ?>" <?php echo goal(); ?>><?php the_title(); ?></a>
					<?php } ?>
				</li>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			</ul>
		</div>

		<div class="new_cat">
			<ul id="tab-viewe-<?php echo $this -> number ?>" class="zm-tab group">
				<h4><?php _e( '热门文章', 'begin' ); ?></h4>
				<?php if (zm_get_option('post_views')) { ?>
					<?php if ($instance['viewe_thumbs']) { ?>
						<?php get_timespan_most_viewed_img('post',$instance["viewe_number"],$instance["viewe_days"], true, true); ?>
					<?php } else { ?>
						<?php get_timespan_most_viewed('post',$instance["viewe_number"],$instance["viewe_days"], true, true); ?>
					<?php } ?>
					<?php wp_reset_query(); ?>
				<?php } else { ?>
					<li>需要启用文章浏览统计</a></li>
				<?php } ?>
			</ul>
		</div>

		<?php $comments = get_comments(array('number'=>$instance["comments_num"],'status'=>'approve','post_status'=>'publish')); ?>
		<div class="message-tab message-widget gaimg load">
			<ul>
				<h4><?php _e( '最近留言', 'begin' ); ?></h4>
				<?php 
			
				$no_comments = false;
				$avatar_size = 96;
				$comments_query = new WP_Comment_Query();
				$comments = $comments_query->query( array_merge( array( 'number' => $instance["comments_num"], 'status' => 'approve', 'type' => 'comments', 'post_status' => 'publish', 'author__not_in' => explode(',',$instance["authornot"]) ) ) );
				if ( $comments ) : foreach ( $comments as $comment ) : ?>

				<li>
					<a href="<?php echo get_permalink($comment->comment_post_ID); ?>#anchor-comment-<?php echo $comment->comment_ID; ?>" title="<?php _e( '发表在', 'begin' ); ?>：<?php echo get_the_title($comment->comment_post_ID); ?>" rel="external nofollow" <?php echo goal(); ?>>
						<?php if ($instance['comments_avatars']) : ?>
						<?php if (zm_get_option('cache_avatar')) { ?>
							<?php echo begin_avatar( $comment->comment_author_email, $avatar_size, '', get_comment_author( $comment->comment_ID ) ); ?>
						<?php } else { ?>
							<?php if ( !zm_get_option( 'avatar_load' ) ) {
								echo get_avatar( $comment->comment_author_email, $avatar_size, '', get_comment_author( $comment->comment_ID ) );
							} else {
								echo '<img class="avatar photo" src="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=" alt="'. get_comment_author( $comment->comment_ID ) .'" width="30" height="30" data-original="' . preg_replace(array('/^.+(src=)(\"|\')/i', '/(\"|\')\sclass=(\"|\').+$/i'), array('', ''), get_avatar( $comment->comment_author_email, $avatar_size, '', get_comment_author( $comment->comment_ID ) )) . '" />';
							} ?>
						<?php } ?>
						<?php endif; ?>
						<?php if ( zm_get_option( 'comment_vip' ) ) { ?>
							<?php
								$authoremail = get_comment_author_email( $comment );
								if ( email_exists( $authoremail ) ) {
									$commet_user_role = get_user_by( 'email', $authoremail );
									$comment_user_role = $commet_user_role->roles[0];
										if ( $comment_user_role !== zm_get_option('roles_vip') ) {
											echo '<span class="comment_author">' . get_comment_author( $comment->comment_ID ) . '</span>';
										} else {
											echo '<span class="comment_author message-widget-vip">' . get_comment_author( $comment->comment_ID ) . '</span>';
										}
								} else {
									echo '<span class="comment_author">' . get_comment_author( $comment->comment_ID ) . '</span>';
								}
							?>
						<?php } else { ?>
							<span class="comment_author"><?php echo get_comment_author( $comment->comment_ID ); ?></span>
						<?php } ?>
						<?php echo convert_smilies($comment->comment_content); ?>
					</a>
				</li>

				<?php endforeach; else : ?>
					<li><?php _e('暂无留言', 'begin'); ?></li>
					<?php $no_comments = true;
				endif; ?>
			</ul>
		</div>

	</div>

<?php
		$output .= ob_get_clean();
		$output .= $after_widget."\n";
		echo $output;
	}

	public function update($new,$old) {
		$instance = $old;
		$instance['title'] = strip_tags($new['title']);
		$instance['tabs_category'] = !empty($new['tabs_category']) ? 1 : 0;
		$instance['tabs_date'] = !empty($new['tabs_date']) ? 1 : 0;
	// Recent posts
		$instance['recent_thumbs'] = !empty($new['recent_thumbs']) ? 1 : 0;
		$instance['recent_cat_id'] = strip_tags($new['recent_cat_id']);
		$instance['recent_num'] = strip_tags($new['recent_num']);
	// Popular posts
		$instance['popular_thumbs'] = !empty($new['popular_thumbs']) ? 1 : 0;
		$instance['popular_cat_id'] = strip_tags($new['popular_cat_id']);
		$instance['popular_time'] = strip_tags($new['popular_time']);
		$instance['popular_num'] = strip_tags($new['popular_num']);
	// Recent comments
		$instance['comments_avatars'] = !empty($new['comments_avatars']) ? 1 : 0;
		$instance['comments_num'] = strip_tags($new['comments_num']);
		$instance['authornot'] = strip_tags($new['authornot']);
	// viewe
		$instance['viewe_thumbs'] = !empty($new['viewe_thumbs']) ? 1 : 0;
		$instance['viewe_number'] = strip_tags($new['viewe_number']);
		$instance['viewe_days'] = strip_tags($new['viewe_days']);
		return $instance;
	}

/*  Widget form
/* ------------------------------------ */
	public function form($instance) {
		$defaults = $this -> zm_get_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
?>

	<style>.widget .widget-inside .zm-options-tabs hr {margin: 20px -15px;border-top: 1px solid #dadada;}</style>

	<div class="zm-options-tabs">
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>">标题：</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr($instance["title"]); ?>" />
		</p>

		<h4>最新文章</h4>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('recent_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('recent_thumbs') ); ?>" <?php checked( (bool) $instance["recent_thumbs"], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id('recent_thumbs') ); ?>">显示缩略图</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'recent_num' ); ?>">显示数量：</label>
			<input class="number-text" id="<?php echo $this->get_field_id( 'recent_num' ); ?>" name="<?php echo $this->get_field_name( 'recent_num' ); ?>" type="number" step="1" min="1" value="<?php echo $instance['recent_num']; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id("recent_cat_id") ); ?>">选择分类：</label>
			<?php wp_dropdown_categories( array( 'name' => $this->get_field_name("recent_cat_id"), 'selected' => $instance["recent_cat_id"], 'show_option_all' => '全部分类', 'show_count' => true ) ); ?>
		</p>

		<hr>
		<h4>热评文章</h4>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('popular_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('popular_thumbs') ); ?>" <?php checked( (bool) $instance["popular_thumbs"], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id('popular_thumbs') ); ?>">显示缩略图</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'popular_num' ); ?>">显示数量：</label>
			<input class="number-text" id="<?php echo $this->get_field_id( 'popular_num' ); ?>" name="<?php echo $this->get_field_name( 'popular_num' ); ?>" type="number" step="1" min="1" value="<?php echo $instance['popular_num']; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id("popular_cat_id") ); ?>">选择分类：</label>
			<?php wp_dropdown_categories( array( 'name' => $this->get_field_name("popular_cat_id"), 'selected' => $instance["popular_cat_id"], 'show_option_all' => '全部分类', 'show_count' => true ) ); ?>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id("popular_time") ); ?>">选择时间段：</label>
			<select id="<?php echo esc_attr( $this->get_field_id("popular_time") ); ?>" name="<?php echo esc_attr( $this->get_field_name("popular_time") ); ?>">
				<option value="0"<?php selected( $instance["popular_time"], "0" ); ?>>全部</option>
				<option value="1 year ago"<?php selected( $instance["popular_time"], "1 year ago" ); ?>>一年内</option>
				<option value="1 month ago"<?php selected( $instance["popular_time"], "1 month ago" ); ?>>一月内</option>
				<option value="1 week ago"<?php selected( $instance["popular_time"], "1 week ago" ); ?>>一周内</option>
				<option value="1 day ago"<?php selected( $instance["popular_time"], "1 day ago" ); ?>>24小时内</option>
			</select>
		</p>

		<hr>
		<h4>热门文章</h4>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('viewe_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('viewe_thumbs') ); ?>" <?php checked( (bool) $instance["viewe_thumbs"], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id('viewe_thumbs') ); ?>">显示缩略图</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'viewe_number' ); ?>">显示数量：</label>
			<input class="number-text" id="<?php echo $this->get_field_id( 'viewe_number' ); ?>" name="<?php echo $this->get_field_name( 'viewe_number' ); ?>" type="number" step="1" min="1" value="<?php echo $instance['viewe_number']; ?>" size="3" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'viewe_days' ); ?>">时间限定（天）：</label>
			<input class="number-text-d" id="<?php echo $this->get_field_id( 'viewe_days' ); ?>" name="<?php echo $this->get_field_name( 'viewe_days' ); ?>" type="number" step="1" min="1" value="<?php echo $instance['viewe_days']; ?>" size="3" />
		</p>

		<hr>
		<h4>最新留言</h4>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('comments_avatars') ); ?>" name="<?php echo esc_attr( $this->get_field_name('comments_avatars') ); ?>" <?php checked( (bool) $instance["comments_avatars"], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id('comments_avatars') ); ?>">显示头像</label>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id("authornot") ); ?>">排除的用户ID：</label>
			<input id="<?php echo esc_attr( $this->get_field_id("authornot") ); ?>" name="<?php echo esc_attr( $this->get_field_name("authornot") ); ?>" type="text" value="<?php echo absint($instance["authornot"]); ?>" size='3' />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'comments_num' ); ?>">显示数量：</label>
			<input class="number-text" id="<?php echo $this->get_field_id( 'comments_num' ); ?>" name="<?php echo $this->get_field_name( 'comments_num' ); ?>" type="number" step="1" min="1" value="<?php echo $instance['comments_num']; ?>" size="3" />
		</p>
	</div>
<?php
}
}

function be_tabs_init() {
	register_widget( 'be_tabs' );
}
add_action( 'widgets_init', 'be_tabs_init' );

// 网站概况
class site_profile extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'site_profile',
			'description' => '网站概况',
			'customize_selective_refresh' => true,
		);
		parent::__construct('site_profile', '网站概况', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'title_z'       => '',
			'show_icon'     => '',
			'show_svg'      => '',
			'time'          => 2007-8-1,
			'title'         => '网站概况',
		);
	}

	function widget($args, $instance) {
		extract($args);
		$title_w = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title;
		$time = strip_tags($instance['time']) ? absint( $instance['time'] ) : 2007-8-1;
?>

<div class="site-profile">
	<?php if ($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<ul>
		<li><?php _e( '文章', 'begin' ); ?><span><?php $count_posts = wp_count_posts(); echo $published_posts = $count_posts->publish;?></span></li>
		<li><?php _e( '分类', 'begin' ); ?><span><?php echo $count_categories = wp_count_terms('category'); ?></span></li>
		<li><?php _e( '标签', 'begin' ); ?><span><?php echo $count_tags = wp_count_terms('post_tag'); ?></span></li>
		<li><?php _e( '留言', 'begin' ); ?><span><?php $my_email = get_bloginfo ( 'admin_email' ); global $wpdb; echo $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments where comment_author_email!='$my_email'");?></span></li>
		<li><?php _e( '链接', 'begin' ); ?><span><?php global $wpdb; echo $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->links WHERE link_visible = 'Y'"); ?></span></li>
		<li><?php _e( '浏览', 'begin' ); ?><span><?php echo all_view(); ?></span></li>
		<li><?php _e( '今日', 'begin' ); ?><span><?php today_renew(); ?></span></li>
		<li><?php _e( '本周', 'begin' ); ?><span><?php week_renew(); ?></span></li>
		<li><?php _e( '运行', 'begin' ); ?><span><?php echo floor((time()-strtotime($instance['time']))/86400); ?> <?php _e( '天', 'begin' ); ?></span></li>
		<li><?php _e( '更新', 'begin' ); ?><span><?php global $wpdb; $last =$wpdb->get_results("SELECT MAX(post_modified) AS MAX_m FROM $wpdb->posts WHERE (post_type = 'post' OR post_type = 'page') AND (post_status = 'publish' OR post_status = 'private')");$last = date('Y-n-j', strtotime($last[0]->MAX_m));echo $last; ?></span></li>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['show_svg'] = strip_tags($new_instance['show_svg']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['time'] = strip_tags($new_instance['time']);
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '网站概况';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('time' => '2007-8-1'));
		$time = strip_tags($instance['time']);
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">单色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_svg'); ?>">彩色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_svg'); ?>" name="<?php echo $this->get_field_name('show_svg'); ?>" type="text" value="<?php echo $instance['show_svg']; ?>" />
	</p>
	<p><label for="<?php echo $this->get_field_id('time'); ?>">建站日期：</label>
	<input id="<?php echo $this->get_field_id( 'time' ); ?>" name="<?php echo $this->get_field_name( 'time' ); ?>" type="text" value="<?php echo $time; ?>" size="10" /> 格式：2007-8-1</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
function site_profile_init() {
	register_widget( 'site_profile' );
}
add_action( 'widgets_init', 'site_profile_init' );

// 热门文章
class hot_post_img extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'hot_post_img',
			'description' => '调用点击最多的文章',
			'customize_selective_refresh' => true,
		);
		parent::__construct('hot_post_img', '热门文章', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_thumbs'   => 1,
			'show_serial'   => 1,
			'title_z'       => '',
			'show_icon'     => '',
			'show_svg'      => '',
			'number'        => 5,
			'days'          => 90,
			'title'         => '热门文章',
		);
	}

	function widget($args, $instance) {
		extract($args);
		$title_w = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
		$days = strip_tags($instance['days']) ? absint( $instance['days'] ) : 90;
?>

<?php if ($instance['show_thumbs']) { ?>
<div id="hot_post_widget" class="new_cat">
<?php } else { ?>
<div id="hot_post_widget" class="widget-li-icon">
<?php } ?>
	<div id="hot_post_widget" class="<?php echo $instance['show_thumbs'] ? 'new_cat' : 'widget-li-icon'; ?>">
		<?php if ($instance['show_icon']) { ?>
			<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
		<?php } ?>
		<?php if ($instance['show_svg']) { ?>
			<h3 class="widget-title-cat-icon cat-w-icon"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
		<?php } ?>
		<ul class="widget-ul-hot<?php if ($instance['show_thumbs']) { ?> show-thumbs<?php } ?><?php if ($instance['show_serial']) { ?> hot-icon<?php } ?>">
			<?php if (zm_get_option('post_views')) { ?>
			<?php if ($instance['show_thumbs']) { ?>
				<?php get_timespan_most_viewed_img( 'post',$number,$days, true, true ); ?>
			<?php } else { ?>
			    <?php get_timespan_most_viewed( 'post',$number, $days, true, true ); ?>
			<?php } ?>
			<?php wp_reset_query(); ?>
			<?php } else { ?>
				<li>需要启用文章浏览统计</li>
			<?php } ?>
		</ul>
	</div>
</div>
<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['show_thumbs'] = !empty($new_instance['show_thumbs']) ? 1 : 0;
		$instance['show_serial'] = !empty($new_instance['show_serial']) ? 1 : 0;
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['show_svg'] = strip_tags($new_instance['show_svg']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = strip_tags($new_instance['number']);
		$instance['days'] = strip_tags($new_instance['days']);
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '热门文章';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$instance = wp_parse_args((array) $instance, array('days' => '90'));
		$number = strip_tags($instance['number']);
		$days = strip_tags($instance['days']);
 ?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	 </p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">单色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_svg'); ?>">彩色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_svg'); ?>" name="<?php echo $this->get_field_name('show_svg'); ?>" type="text" value="<?php echo $instance['show_svg']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>">显示数量：</label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'days' ); ?>">时间限定（天）：</label>
		<input class="number-text-d" id="<?php echo $this->get_field_id( 'days' ); ?>" name="<?php echo $this->get_field_name( 'days' ); ?>" type="number" step="1" min="1" value="<?php echo $days; ?>" size="3" />
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_thumbs') ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>">显示缩略图</label>
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_serial') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_serial') ); ?>" <?php checked( (bool) $instance["show_serial"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_serial') ); ?>">缩略图序号</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
function hot_post_img_init() {
	register_widget( 'hot_post_img' );
}
add_action( 'widgets_init', 'hot_post_img_init' );

// 大家喜欢
class be_like_most_img extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'be_like_most_img',
			'description' => '调用点赞最多的文章',
			'customize_selective_refresh' => true,
		);
		parent::__construct('be_like_most_img', '大家喜欢', $widget_ops);
	}
	public function zm_defaults() {
		return array(
			'show_thumbs'   => 1,
			'title_z'       => '',
			'show_icon'     => '',
			'show_svg'      => '',
			'number'        => 5,
			'days'          => 90,
			'title'         => '大家喜欢',
		);
	}


	function widget($args, $instance) {
		extract($args);
		$title_w = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
		$days = strip_tags($instance['days']) ? absint( $instance['days'] ) : 90;
?>

<?php if ($instance['show_thumbs']) { ?>
<div id="like" class="new_cat">
<?php } else { ?>
<div id="like" class="like_most">
<?php } ?>
	<?php if ($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<ul>
		<?php if ($instance['show_thumbs']) { ?>
			<?php get_like_most_img('post',$number,$days, true, true); ?>
		<?php } else { ?>
			<?php get_like_most('post',$number,$days, true, true); ?>
		<?php } ?>
		<?php wp_reset_query(); ?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['show_thumbs'] = !empty($new_instance['show_thumbs']) ? 1 : 0;
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['show_svg'] = strip_tags($new_instance['show_svg']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = strip_tags($new_instance['number']);
		$instance['days'] = strip_tags($new_instance['days']);
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '大家喜欢';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$instance = wp_parse_args((array) $instance, array('days' => '90'));
		$number = strip_tags($instance['number']);
		$days = strip_tags($instance['days']);
 ?>
	<p>
		 <label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	 </p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">单色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_svg'); ?>">彩色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_svg'); ?>" name="<?php echo $this->get_field_name('show_svg'); ?>" type="text" value="<?php echo $instance['show_svg']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>">显示数量：</label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'days' ); ?>">时间限定（天）：</label>
		<input class="number-text-d" id="<?php echo $this->get_field_id( 'days' ); ?>" name="<?php echo $this->get_field_name( 'days' ); ?>" type="number" step="1" min="1" value="<?php echo $days; ?>" size="3" />
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_thumbs') ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>">显示缩略图</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
function be_like_most_img_init() {
	register_widget( 'be_like_most_img' );
}
add_action( 'widgets_init', 'be_like_most_img_init' );

// Ajax组合小工具
if ( ! class_exists( 'ajax_widget' ) ) {
	class ajax_widget extends WP_Widget {
		function __construct() {
			add_action( 'wp_ajax_ajax_widget_content', array( $this, 'ajax_ajax_widget_content' ) );
			add_action( 'wp_ajax_nopriv_ajax_widget_content', array( $this, 'ajax_ajax_widget_content' ) );
			$widget_ops = array(
				'classname'   => 'widget_ajax',
				'description' => '最新文章、热评文章、推荐文章、热门文章等'
			);
			parent::__construct( 'ajax_widget', 'Ajax组合小工具', $widget_ops );
		}

		function form( $instance ) {
			$instance = wp_parse_args( ( array ) $instance, array( 
				'tabs'             => array( 'recent' => 1, 'popular' => 1, 'hot' => 1, 'review' => 1, 'random' => 1, 'recommend' => 1 ), 
				'tab_order'        => array( 'recent' => 1, 'popular' => 2, 'hot' => 3, 'review' => 4, 'random' => 5, 'recommend' => 6 ),
				'allow_pagination' => 1,
				'post_num'         => '5',
				'show_thumb'       => '1',
				'viewe_days'       => '90',
				'review_days'      => '3',
				'like_days'        => '90',
				'pcat'             => '',
			) );

			extract( $instance ); ?>

			<div class="ajax_options_form">

		        <h4>选择</h4>

				<div class="ajax_select_tabs">
					<label class="alignleft" style="display: block; width: 50%; margin-bottom: 5px;" for="<?php echo $this->get_field_id("tabs"); ?>_recent">
						<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("tabs"); ?>_recent" name="<?php echo $this->get_field_name("tabs"); ?>[recent]" value="1" <?php if (isset($tabs['recent'])) { checked( 1, $tabs['recent'], true ); } ?> />		
						最新文章
					</label>
					<label class="alignleft" style="display: block; width: 50%; margin-bottom: 5px" for="<?php echo $this->get_field_id("tabs"); ?>_popular">
						<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("tabs"); ?>_popular" name="<?php echo $this->get_field_name("tabs"); ?>[popular]" value="1" <?php if (isset($tabs['popular'])) { checked( 1, $tabs['popular'], true ); } ?> />
						大家喜欢
					</label>
					<label class="alignleft" style="display: block; width: 50%; margin-bottom: 5px;" for="<?php echo $this->get_field_id("tabs"); ?>_hot">
						<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("tabs"); ?>_hot" name="<?php echo $this->get_field_name("tabs"); ?>[hot]" value="1" <?php if (isset($tabs['hot'])) { checked( 1, $tabs['hot'], true ); } ?> />
						热门文章
					</label>
					<label class="alignleft" style="display: block; width: 50%; margin-bottom: 5px;" for="<?php echo $this->get_field_id("tabs"); ?>_review">
						<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("tabs"); ?>_review" name="<?php echo $this->get_field_name("tabs"); ?>[review]" value="1" <?php if (isset($tabs['review'])) { checked( 1, $tabs['review'], true ); } ?> />
						热评文章
					</label>
					<label class="alignleft" style="display: block; width: 50%; margin-bottom: 5px;" for="<?php echo $this->get_field_id("tabs"); ?>_random">
						<input type="checkbox" class="checkbox ajax_enable_random" id="<?php echo $this->get_field_id("tabs"); ?>_random" name="<?php echo $this->get_field_name("tabs"); ?>[random]" value="1" <?php if (isset($tabs['random'])) { checked( 1, $tabs['random'], true ); } ?> />
						随机文章
					</label>
					<label class="alignleft" style="display: block; width: 50%; margin-bottom: 5px;" for="<?php echo $this->get_field_id("tabs"); ?>_recommend">
						<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("tabs"); ?>_recommend" name="<?php echo $this->get_field_name("tabs"); ?>[recommend]" value="1" <?php if (isset($tabs['recommend'])) { checked( 1, $tabs['recommend'], true ); } ?> />
						推荐阅读
					</label>
				</div>
				<div class="clear"></div>

				<h4 class="ajax_tab_order_header">排序</h4>

				<div class="ajax_tab_order">
					<label class="alignleft" for="<?php echo $this->get_field_id('tab_order'); ?>_recent" style="width: 48%;padding: 2px;">
						<input id="<?php echo $this->get_field_id('tab_order'); ?>_recent" name="<?php echo $this->get_field_name('tab_order'); ?>[recent]" type="number" min="1" step="1" value="<?php echo $tab_order['recent']; ?>" style="width: 48px;" />
						最新文章
					</label>
					<label class="alignleft" for="<?php echo $this->get_field_id('tab_order'); ?>_popular" style="width: 48%;padding: 2px;">
						<input id="<?php echo $this->get_field_id('tab_order'); ?>_popular" name="<?php echo $this->get_field_name('tab_order'); ?>[popular]" type="number" min="1" step="1" value="<?php echo $tab_order['popular']; ?>" style="width: 48px;" />
						大家喜欢
					</label>
					<label class="alignleft" for="<?php echo $this->get_field_id('tab_order'); ?>_hot" style="width: 48%;padding: 2px;">
						<input id="<?php echo $this->get_field_id('tab_order'); ?>_hot" name="<?php echo $this->get_field_name('tab_order'); ?>[hot]" type="number" min="1" step="1" value="<?php echo $tab_order['hot']; ?>" style="width: 48px;" />
						热门文章
					</label>
					<label class="alignleft" for="<?php echo $this->get_field_id('tab_order'); ?>_review" style="width: 48%;padding: 2px;">
						<input id="<?php echo $this->get_field_id('tab_order'); ?>_review" name="<?php echo $this->get_field_name('tab_order'); ?>[review]" type="number" min="1" step="1" value="<?php echo $tab_order['review']; ?>" style="width: 48px;" />
						热评文章
					</label>
					<label class="alignleft" for="<?php echo $this->get_field_id('tab_order'); ?>_random" style="width: 48%;padding: 2px;">
						<input id="<?php echo $this->get_field_id('tab_order'); ?>_random" name="<?php echo $this->get_field_name('tab_order'); ?>[random]" type="number" min="1" step="1" value="<?php echo $tab_order['random']; ?>" style="width: 48px;" />
						随机文章
					</label>
					<label class="alignleft" for="<?php echo $this->get_field_id('tab_order'); ?>_recommend" style="width: 48%;padding: 2px;">
						<input id="<?php echo $this->get_field_id('tab_order'); ?>_recommend" name="<?php echo $this->get_field_name('tab_order'); ?>[recommend]" type="number" min="1" step="1" value="<?php echo $tab_order['recommend']; ?>" style="width: 48px;" />
						推荐阅读
					</label>
				</div>
				<div class="clear"></div>

				<h4 class="ajax_advanced_options_header">选项</h4>

				<div class="ajax_advanced_options">
			        <p>
						<label for="<?php echo $this->get_field_id("allow_pagination"); ?>">
							<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("allow_pagination"); ?>" name="<?php echo $this->get_field_name("allow_pagination"); ?>" value="1" <?php if (isset($allow_pagination)) { checked( 1, $allow_pagination, true ); } ?> />
							显示翻页
						</label>
					</p>

						<p>
							<label for="<?php echo $this->get_field_id("show_thumb"); ?>">
								<input type="checkbox" class="checkbox ajax_show_thumbnails" id="<?php echo $this->get_field_id("show_thumb"); ?>" name="<?php echo $this->get_field_name("show_thumb"); ?>" value="1" <?php if (isset($show_thumb)) { checked( 1, $show_thumb, true ); } ?> />
								显示缩略图
							</label>
						</p>

					<div class="ajax_post_options">

						<p>
							<label for="<?php echo $this->get_field_id('post_num'); ?>">每页显示数量&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input class="number-text" id="<?php echo $this->get_field_id('post_num'); ?>" name="<?php echo $this->get_field_name('post_num'); ?>" type="number" min="1" step="1" value="<?php echo $post_num; ?>" />
							</label>
						</p>

						<p>
							<label for="<?php echo $this->get_field_id('like_days'); ?>">大家喜欢时间限定（天）
								<input class="number-text-d" id="<?php echo $this->get_field_id('like_days'); ?>" name="<?php echo $this->get_field_name('like_days'); ?>" type="number" min="1" step="1" value="<?php echo $like_days; ?>" />
							</label>
						</p>

						<p>
							<label for="<?php echo $this->get_field_id('viewe_days'); ?>">热门文章时间限定（天）
								<input class="number-text-d" id="<?php echo $this->get_field_id('viewe_days'); ?>" name="<?php echo $this->get_field_name('viewe_days'); ?>" type="number" min="1" step="1" value="<?php echo $viewe_days; ?>" />
							</label>
						</p>
					
						<p>
							<label for="<?php echo $this->get_field_id('review_days'); ?>">热评文章时间限定（月）
								<input class="number-text-d" id="<?php echo $this->get_field_id('review_days'); ?>" name="<?php echo $this->get_field_name('review_days'); ?>" type="number" min="1" step="1" value="<?php echo $review_days; ?>" />
							</label>
						</p>

						<p>
							<label for="<?php echo $this->get_field_id('pcat'); ?>">
								最新文章排除的分类ID
								<textarea style="height:35px;" class="widefat" id="<?php echo $this->get_field_id( 'pcat' ); ?>" name="<?php echo $this->get_field_name( 'pcat' ); ?>"><?php echo stripslashes(htmlspecialchars(( $instance['pcat'] ), ENT_QUOTES)); ?></textarea>
							</label>
						</p>
					</div>
				</div>
			</div>
		<?php }

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['tabs']             = $new_instance['tabs'];
			$instance['tab_order']        = $new_instance['tab_order']; 
			$instance['allow_pagination'] = isset( $new_instance['allow_pagination'] ) ? $new_instance['allow_pagination'] : '';
			$instance['post_num']         = $new_instance['post_num'];
			$instance['viewe_days']       =  $new_instance['viewe_days'];
			$instance['review_days']      =  $new_instance['review_days'];
			$instance['like_days']        =  $new_instance['like_days'];
			$instance['show_thumb']       = isset( $new_instance['show_thumb'] ) ? $new_instance['show_thumb'] : '';
			$instance['pcat']             = $new_instance['pcat'];
			return $instance;
		}
		function widget( $args, $instance ) {
			extract( $args );
			extract( $instance );
			$title_w = title_i_w();
			wp_enqueue_script( 'ajax_widget' );
			wp_enqueue_style( 'ajax_widget' );
			if ( empty( $tabs ) ) $tabs = array( 'recent' => 1, 'popular' => 1 );
			$tabs_count = count( $tabs );
			if ( $tabs_count <= 1 ) {
				$tabs_count = 1;
			} elseif ( $tabs_count > 5 ) {
				$tabs_count = 6;
			}

			$available_tabs = array(
				'recent'    => __( '最新文章', 'begin' ), 
				'popular'   => __( '大家喜欢', 'begin'), 
				'hot'       => __( '热门文章', 'begin' ), 
				'review'    => __( '热评文章', 'begin' ), 
				'random'    => __( '随机文章', 'begin' ),
				'recommend' => __( '推荐阅读', 'begin' )
			);

			array_multisort( $tab_order, $available_tabs );
			?>


			<?php echo $before_widget; ?>

			<div class="ajax_widget_content" id="<?php echo $widget_id; ?>_content" data-widget-number="<?php echo esc_attr( $this->number ); ?>">
				<div class="ajax-tabs <?php echo "has-$tabs_count-"; ?>tabs">
					<?php foreach ( $available_tabs as $tab => $label ) { ?>
						<?php if ( ! empty( $tabs[$tab] ) ): ?>
							<span class="tab_title tab-first"><a href="#" title="<?php echo $label; ?>" id="<?php echo $tab; ?>-tab"></a></span>
						<?php endif; ?>
					<?php } ?> 
					<div class="clear"></div>
				</div>
				<!--end .tabs-->
				<div class="clear"></div>

				<div class="new_cat">
					<?php if ( ! empty( $tabs['popular'] ) ) : ?>
						<div id="popular-tab-content" class="tab-content">
							<div class="tab-load">
								<ul>
									<h4><?php _e( '加载中...', 'begin' ); ?></h4>
									<?php if ( $show_thumb == 1 ) { ?>
										<?php echo tab_load_img( $post_num ); ?>
									<?php } else { ?>
										<?php echo tab_load_text( $post_num ); ?>
									<?php } ?>
								</ul>
							</div>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $tabs['recent'] ) ) : ?>
						<div id="recent-tab-content" class="tab-content">
							<div class="tab-load">
								<ul>
									<h4><?php _e( '加载中...', 'begin' ); ?></h4>
									<?php if ( $show_thumb == 1 ) { ?>
										<?php echo tab_load_img( $post_num ); ?>
									<?php } else { ?>
										<?php echo tab_load_text( $post_num ); ?>
									<?php } ?>
								</ul>
							</div>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $tabs['recommend'] ) ) : ?>
						<div id="recommend-tab-content" class="tab-content">
							<div class="tab-load">
								<ul>
									<h4><?php _e( '加载中...', 'begin' ); ?></h4>
									<?php if ( $show_thumb == 1 ) { ?>
										<?php echo tab_load_img( $post_num ); ?>
									<?php } else { ?>
										<?php echo tab_load_text( $post_num ); ?>
									<?php } ?>
								</ul>
							</div>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $tabs['hot'] ) ) : ?>
						<div id="hot-tab-content" class="tab-content">
							<div class="tab-load">
								<ul>
									<h4><?php _e( '加载中...', 'begin' ); ?></h4>
									<?php if ( $show_thumb == 1 ) { ?>
										<?php echo tab_load_img( $post_num ); ?>
									<?php } else { ?>
										<?php echo tab_load_text( $post_num ); ?>
									<?php } ?>
								</ul>
							</div>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $tabs['review'] ) ) : ?>
						<div id="review-tab-content" class="tab-content">
							<div class="tab-load">
								<ul>
									<h4><?php _e( '加载中...', 'begin' ); ?></h4>
									<?php if ( $show_thumb == 1 ) { ?>
										<?php echo tab_load_img( $post_num ); ?>
									<?php } else { ?>
										<?php echo tab_load_text( $post_num ); ?>
									<?php } ?>
								</ul>
							</div>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $tabs['random'] ) ) : ?>
						<div id="random-tab-content" class="tab-content">
							<div class="tab-load">
								<ul>
									<h4><?php _e( '加载中...', 'begin' ); ?></h4>
									<?php if ( $show_thumb == 1 ) { ?>
										<?php echo tab_load_img( $post_num ); ?>
									<?php } else { ?>
										<?php echo tab_load_text( $post_num ); ?>
									<?php } ?>
								</ul>
							</div>
						</div>
					<?php endif; ?>

					<div class="clear"></div>
				</div> <!--end .inside -->

				<div class="clear"></div>
			</div><!--end #tabber -->
			<?php 
				unset( $instance['tabs'], $instance['tab_order'] );
			?>

			<script type="text/javascript">
				jQuery(function($) { 
					$('#<?php echo $widget_id; ?>_content').data('args', <?php echo json_encode($instance); ?>);
				});
			</script>

			<?php echo $after_widget; ?>
			<?php 
		}

		function ajax_ajax_widget_content() {
			$tab    = $_POST['tab'];
			$args   = isset( $_POST['args'] ) ? $_POST['args'] : [];
			$number = isset( $_POST['widget_number'] ) ? intval( $_POST['widget_number'] ) : '';
			$page   = intval( $_POST['page'] );
			if ( $page < 1 )
				$page = 1;

			if ( ! is_array( $args ) || empty( $args ) ) { // json_encode() failed
				$ajax_widgets = new ajax_widget();
				$settings = $ajax_widgets->get_settings();

				if ( isset( $settings[ $number ] ) ) {
					$args = $settings[ $number ];
				} else {
					die('<ul><li>无法加载文章！</li></ul>');
				}
			}

			// sanitize args
			$post_num = ( empty( $args['post_num'] ) ? 5 : intval( $args['post_num'] ) );
			if ($post_num > 20 || $post_num < 1 ) { // max 20 posts
				$post_num = 5;
			}

			$viewe_days       = ( empty( $args['viewe_days']) ? 90 : intval( $args['viewe_days'] ) );
			$review_days      = ( empty( $args['review_days']) ? 3 : intval( $args['review_days'] ) );
			$like_days        = ( empty( $args['like_days']) ? 90 : intval( $args['like_days'] ) );
			$show_thumb       = ! empty( $args['show_thumb'] );
			$pcat             = strip_tags( $args['pcat']);
			$allow_pagination = ! empty( $args['allow_pagination'] );
			switch ($tab) { 

				// Recent Posts
				case "recent":
					?>
					<ul>
						<h4><?php _e( '最新文章', 'begin' ); ?></h4>
						<?php 
							$exclude = isset($_POST['exclude']) ? $_POST['exclude'] : [];
							$recent = new WP_Query( array(
								'posts_per_page'      => $post_num,
								'post__not_in'        => array( $exclude ),
								'orderby'             => 'post_date',
								'order'               => 'desc',
								'post_status'         => 'publish',
								'category__not_in'    => explode(',',$pcat ),
								'paged'               => $page,
								'ignore_sticky_posts' => 1,
							));
						?>

						<?php $last_page = $recent->max_num_pages; while ( $recent->have_posts() ) : $recent->the_post(); ?>
							<li>
								<?php if ( $show_thumb == 1 ) { ?>
									<span class="thumbnail">
										<?php echo zm_thumbnail(); ?>
									</span>

									<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
									<?php grid_meta(); ?>
									<?php views_span(); ?>
								<?php } else { ?>
									<?php the_title( sprintf( '<a class="get-icon" href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
								<?php } ?>
								<div class="clear"></div>
							</li>
					<?php endwhile; wp_reset_postdata(); ?>
					</ul>
	                <div class="clear"></div>
					<?php if ( $allow_pagination ) : ?>
						<?php $this->tab_pagination( $page, $last_page ); ?>
					<?php endif; ?>
					<?php 
				break;

				// Popular Posts
				case "popular":
					?>
					<ul> 
						<h4><?php _e( '大家喜欢', 'begin' ); ?></h4>
						<?php 
							$date_query=array(
								array(
									'column' => 'post_date',
									'before' => date('Y-m-d H:i',time()),
									'after'  => date('Y-m-d H:i',time()-3600*24*$viewe_days)
								)
							);

							$exclude = isset($_POST['exclude']) ? $_POST['exclude'] : [];
							$recent = new WP_Query(array(
								'meta_key'       => 'zm_like',
								'orderby'        => 'meta_value_num',
								'post__not_in'   => array( $exclude ),
								'posts_per_page' => $post_num,
								'date_query'     => $like_days,
								'paged'          => $page,
								'order'          => 'DESC'
							));

							$last_page = $recent->max_num_pages; if ( $recent->have_posts() ) : while ($recent->have_posts()) : $recent->the_post();
						?>
						<li>
							<?php if ( $show_thumb == 1 ) { ?>
								<span class="thumbnail">
									<?php echo zm_thumbnail(); ?>
								</span>

								<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
								<?php grid_meta(); ?>
								<span class="be-like"><i class="be be-thumbs-up-o ri"></i><?php zm_get_current_count(); ?></span>
							<?php } else { ?>
								<?php the_title( sprintf( '<a class="get-icon" href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
							<?php } ?>
							<div class="clear"></div>
						</li>
						<?php endwhile;?>
						<?php else : ?>
							<li class="be-none-w"><?php _e( '暂无被点赞的文章', 'begin' ); ?></li>
						<?php endif; ?>
						<?php wp_reset_postdata(); ?>
					</ul>

		            <div class="clear"></div>
					<?php if ( $allow_pagination ) : ?>
						<?php $this->tab_pagination( $page, $last_page ); ?>
					<?php endif; ?>

					<?php 
				break;

				// hot
				case "hot":
					?> 
					<ul> 
						<h4><?php _e( '热门文章', 'begin' ); ?></h4>
						<?php if ( zm_get_option( 'post_views' ) ) { ?>
						<?php 
							$date_query = array(
								array(
									'column' => 'post_date',
									'before' => date( 'Y-m-d H:i',time() ),
									'after'  => date( 'Y-m-d H:i',time()-3600*24*$viewe_days )
								)
							);

							$exclude = isset($_POST['exclude']) ? $_POST['exclude'] : [];
							$recent = new WP_Query(array(
								'meta_key'       => 'views',
								'orderby'        => 'meta_value_num',
								'post__not_in'   => array( $exclude ),
								'post_status'    => 'publish',
								'posts_per_page' => $post_num,
								'date_query'     => $date_query,
								'paged'          => $page,
								'order'          => 'DESC'
							));

						$last_page = $recent->max_num_pages; if ( $recent->have_posts() ) : while ( $recent->have_posts() ) : $recent->the_post();
						?>

						<li>
							<?php if ( $show_thumb == 1 ) { ?>
								<span class="thumbnail">
									<?php echo zm_thumbnail(); ?>
								</span>

							<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
							<?php grid_meta(); ?>
							<?php views_span(); ?>
							<?php } else { ?>
								<?php the_title( sprintf( '<a class="get-icon" href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
							<?php } ?>
							<div class="clear"></div>
						</li>

						<?php endwhile;?>
						<?php else : ?>
							<li class="be-none-w"><?php _e( '暂无', 'begin' ); ?></li>
						<?php endif; ?>
						<?php wp_reset_postdata(); ?>
						<?php } else { ?>
							<li>需要启用文章浏览统计</a></li>
						<?php } ?>
					</ul>

		            <div class="clear"></div>
					<?php if ( $allow_pagination ) : ?>
						<?php $this->tab_pagination( $page, $last_page ); ?>
					<?php endif; ?>

					<?php 
				break;

				// Latest recommend
				case "recommend":
					?> 
					<ul>
						<h4><?php _e( '推荐阅读', 'begin' ); ?></h4>
						<?php 
							$recent = new WP_Query(array(
							'meta_key'            => 'hot',
							'posts_per_page'      => $post_num,
							'ignore_sticky_posts' => 1,
							'paged'               => $page,
							'order'               => 'DESC'
							));

							$last_page = $recent->max_num_pages; if ( $recent->have_posts() ) : while ($recent->have_posts()) : $recent->the_post();
						?>
							<li>
								<?php if ( $show_thumb == 1 ) { ?>
									<span class="thumbnail">
										<?php echo zm_thumbnail(); ?>
									</span>

								<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
								<?php grid_meta(); ?>
								<?php views_span(); ?>
								<?php } else { ?>
									<?php the_title( sprintf( '<a class="get-icon" href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
								<?php } ?>
								<div class="clear"></div>
							</li>
						<?php endwhile;?>
						<?php else : ?>
							<li class="be-none-w">编辑文章，勾选“本站推荐小工具中”</li>
						<?php endif; ?>
						<?php wp_reset_postdata(); ?>
					</ul>

	                <div class="clear"></div>
					<?php if ( $allow_pagination ) : ?>
						<?php $this->tab_pagination( $page, $last_page ); ?>
					<?php endif; ?>

					<?php 
				break;

				// Latest comments
				case "random":
					?> 
					<ul>
						<h4><?php _e( '随机文章', 'begin' ); ?></h4>
						<?php 
							$recent = new WP_Query(array(
								'posts_per_page'      => $post_num,
								'orderby'             => 'rand',
								'post_status'         => 'publish',
								'category__not_in'    => explode( ',',$pcat ),
								'paged'               => $page,
								'ignore_sticky_posts' => 1,
							));
						?>
						<?php $last_page = $recent->max_num_pages; while ( $recent->have_posts() ) : $recent->the_post(); ?>

						<?php if ( $show_thumb == 1 ) { ?>
							<li>
								<span class="thumbnail">
									<?php echo zm_thumbnail(); ?>
								</span>
								<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
								<?php grid_meta(); ?>
								<?php views_span(); ?>
							</li>
						<?php } else { ?>
							<li class="srm the-icon"><?php the_title( sprintf( '<a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?></li>
						<?php } ?>

						<?php endwhile;?>
						<?php wp_reset_postdata(); ?>
					</ul>

	                <div class="clear"></div>
					<?php if ( $allow_pagination ) : ?>
						<?php $this->tab_pagination( $page, $last_page ); ?>
					<?php endif; ?>
					<?php 
				break;

				// Latest review
				case "review":
					?>

					<?php
						$exclude = isset($_POST['exclude']) ? $_POST['exclude'] : [];
						$review = new WP_Query( array(
							'post_type'           => array( 'post' ),
							'posts_per_page'      => $post_num,
							'ignore_sticky_posts' => true,
							'post_status'         => 'publish',
							'orderby'             => 'comment_count',
							'post__not_in'        => array( $exclude ),
							'order'               => 'DESC',
							'paged'               => $page,
							'date_query' => array(
								array(
									'after' => ''.$review_days. 'month ago',
								),
							),
						) );
					?>

					<ul>
						<h4><?php _e( '热评文章', 'begin' ); ?></h4>
						<?php $last_page = $review->max_num_pages; while ( $review->have_posts() ): $review->the_post(); ?>
						<li>
							<?php if ( $show_thumb == 1 ) { ?>
								<span class="thumbnail">
									<?php echo zm_thumbnail(); ?>
								</span>

							<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
							<?php grid_meta(); ?>
							<span class="discuss"><?php comments_number( '', '<i class="be be-speechbubble ri"></i>1 ', '<i class="be be-speechbubble ri"></i>%' ); ?></span>
						<?php } else { ?>
							<?php the_title( sprintf( '<a class="get-icon" href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
						<?php } ?>
						<div class="clear"></div>
					</li>
						<?php endwhile;?>
						<?php wp_reset_postdata(); ?>
					</ul>

	                <div class="clear"></div>
					<?php if ( $allow_pagination ) : ?>
						<?php $this->tab_pagination( $page, $last_page ); ?>
					<?php endif; ?>

					<?php 
				break;
			}
			die();
		}
		function tab_pagination( $page, $last_page ) { ?>
			<div class="ajax-pagination">
				<div class="clear"></div>
				<?php if ( $page > 1 ) : ?>
					<a href="#" class="previous"><span><i class="be be-roundleft"></i></span></a>
				<?php endif; ?>
				<?php if ( $page != $last_page ) : ?>
					<a href="#" class="next"><span><i class="be be-roundright"></i></span></a>
				<?php endif; ?>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
			<input type="hidden" class="page_num" name="page_num" value="<?php echo $page; ?>" />
		<?php }
	}
}
add_action( 'widgets_init', 'ajax_widget_init' );
function ajax_widget_init() {
	register_widget( 'ajax_widget' );
}

// 今日更新
class be_mday_post extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'be_mday_post',
			'description' => '今日发表的文章',
			'customize_selective_refresh' => true,
		);
		parent::__construct('be_mday_post', '今日更新', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_thumbs'   => 1,
			'title_z'       => '',
			'show_icon'     => '',
			'show_svg'      => '',
			'number'        => 5,
			'title'         => '今日更新',
		);
	}

	function widget($args, $instance) {
		extract($args);
		$title_w = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
?>

<?php if ($instance['show_thumbs']) { ?>
<div class="new_cat">
<?php } else { ?>
<div class="mday_post">
<?php } ?>
	<?php if ($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<ul>
		<?php
		$today = getdate();
		$args = array(
			'ignore_sticky_posts' => 1,
			'date_query' => array(
				array(
					'year'  => $today['year'],
					'month' => $today['mon'],
					'day'   => $today['mday'],
				),
			),
			'posts_per_page' => $number,
		);
		$query = new WP_Query( $args );
		?>
		<?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();?>
		<li>
			<?php if ($instance['show_thumbs']) { ?>
				<span class="thumbnail"><?php echo zm_thumbnail(); ?></span>
				<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark" <?php echo goal(); ?>><?php the_title(); ?></a></span>
				<?php grid_meta(); ?>
				<span class="widget-cat"><i class="be be-sort"></i><?php zm_category(); ?></span>
			<?php } else { ?>
				<?php the_title( sprintf( '<a class="get-icon" href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a>' ); ?>
			<?php } ?>
			<div class="clear"></div>
		</li>

		<?php endwhile;?>
		<?php wp_reset_postdata(); ?>
		<?php else : ?>
		<li>
			<span class="date"><time datetime="<?php echo get_the_date('Y-m-d'); ?> <?php echo get_the_time('H:i:s'); ?>"><?php echo $showtime=date("m/d");?></time></span>
			<span class="new-title-no"><?php _e( '暂无更新', 'begin' ); ?></span>
		</li>
		<?php endif;?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['show_thumbs'] = !empty($new_instance['show_thumbs']) ? 1 : 0;
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['show_svg'] = strip_tags($new_instance['show_svg']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = strip_tags($new_instance['number']);
		return $instance;
		}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '今日更新';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$number = strip_tags($instance['number']);
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">单色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_svg'); ?>">彩色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_svg'); ?>" name="<?php echo $this->get_field_name('show_svg'); ?>" type="text" value="<?php echo $instance['show_svg']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>">显示数量：</label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_thumbs') ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>">显示缩略图</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', 'be_mday_post_init' );
function be_mday_post_init() {
	register_widget( 'be_mday_post' );
}

// 本周更新
class be_week_post extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'be_week_post',
			'description' => '本周更新的文章',
			'customize_selective_refresh' => true,
		);
		parent::__construct('be_week_post', '本周更新', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_thumbs'   => 1,
			'title_z'       => '',
			'show_icon'     => '',
			'show_svg'      => '',
			'number'        => 5,
			'title'         => '本周更新',
		);
	}

	function widget($args, $instance) {
		extract($args);
		$title_w = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
?>

<?php if ($instance['show_thumbs']) { ?>
<div class="new_cat">
<?php } else { ?>
<div class="mday_post">
<?php } ?>
	<ul>
		<?php
			$args = array(
				'ignore_sticky_posts' => 1,
				'date_query' => array(
					array(
						'year' => date( 'Y' ),
						'week' => date( 'W' ),
					),
				),
				'posts_per_page' => $number,
			);
			$query = new WP_Query( $args );
		?>
		<?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();?>
			<li>
				<?php if ($instance['show_thumbs']) { ?>
					<span class="thumbnail"><?php echo zm_thumbnail(); ?></span>
					<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark" <?php echo goal(); ?>><?php the_title(); ?></a></span>
					<span class="s-cat"><?php zm_category(); ?></span>
					<?php grid_meta(); ?>
				<?php } else { ?>
					<?php the_title( sprintf( '<a class="get-icon" href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a>' ); ?>
				<?php } ?>
				<div class="clear"></div>
			</li>
		<?php endwhile;?>
		<?php wp_reset_postdata(); ?>
		<?php else : ?>
		<li>
			<span class="new-title-no"><?php _e( '暂无更新', 'begin' ); ?></span>
		</li>
		<?php endif;?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['show_thumbs'] = !empty($new_instance['show_thumbs']) ? 1 : 0;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = strip_tags($new_instance['number']);
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '本周更新';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$number = strip_tags($instance['number']);
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>">显示数量：</label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_thumbs') ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>">显示缩略图</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', 'be_week_post_init' );
function be_week_post_init() {
	register_widget( 'be_week_post' );
}

// 限定时间内的文章
class be_specified_post extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'be_specified_post',
			'description' => '调用限定时间内的文章',
			'customize_selective_refresh' => true,
		);
		parent::__construct('be_specified_post', '限定时间文章', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_thumbs' => 1,
			'title_z'     => '',
			'show_icon'   => '',
			'show_svg'    => '',
			'from_y'      => 2017,
			'from_m'      => 1,
			'from_d'      => 2,
			'to_y'        => 2017,
			'to_m'        => 5,
			'to_d'        => 31,
			'sp_cat'      => 0,
			'numposts'    => 5,
			'title'       => '限定时间文章',
		);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title_w = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title; 
?>

<?php if ($instance['show_thumbs']) { ?>
<div class="new_cat">
<?php } else { ?>
<div class="mday_post">
<?php } ?>
	<?php if ($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<ul>
		<?php
			$args = array(
			'ignore_sticky_posts' => 1,
				'date_query' => array(
					array(
						'after'     =>  array(
							'year'  => $instance['from_y'],
							'month' => $instance['from_m'],
							'day'   => $instance['from_d'],
						),
						'before'    => array(
							'year'  => $instance['to_y'],
							'month' => $instance['to_m'],
							'day'   => $instance['to_d'],
						),
						'inclusive' => true,
					),
				),
				'posts_per_page' => $instance['numposts'],
				'cat' => $instance['sp_cat'],
			);
			$query = new WP_Query( $args );
		?>
		<?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();?>
			<li>
				<?php if ($instance['show_thumbs']) { ?>
					<span class="thumbnail"><?php echo zm_thumbnail(); ?></span>
					<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark" <?php echo goal(); ?>><?php the_title(); ?></a></span>
					<?php grid_meta(); ?>
					<?php views_span(); ?>
				<?php } else { ?>
					<?php the_title( sprintf( '<a class="srm get-icon" href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a>' ); ?>
				<?php } ?>
				<div class="clear"></div>
			</li>
		<?php endwhile;?>
		<?php wp_reset_postdata(); ?>
		<?php else : ?>
		<li>
			<span class="new-title-no"><?php _e( '暂无文章', 'begin' ); ?></span>
		</li>
		<?php endif;?>
	</ul>
</div>

<?php
	echo $after_widget;
}

function update( $new_instance, $old_instance ) {
	$instance = $old_instance;
	$instance['show_thumbs'] = !empty($new_instance['show_thumbs']) ? 1 : 0;
	$instance['title_z'] = strip_tags($new_instance['title_z']);
	$instance['show_icon'] = strip_tags($new_instance['show_icon']);
	$instance['show_svg'] = strip_tags($new_instance['show_svg']);
	$instance['title'] = strip_tags($new_instance['title']);
	$instance['numposts'] = $new_instance['numposts'];
	$instance['sp_cat'] = $new_instance['sp_cat'];
	$instance['from_y'] = $new_instance['from_y'];
	$instance['from_m'] = $new_instance['from_m'];
	$instance['from_d'] = $new_instance['from_d'];
	$instance['to_y'] = $new_instance['to_y'];
	$instance['to_m'] = $new_instance['to_m'];
	$instance['to_d'] = $new_instance['to_d'];
	return $instance;
}

function form( $instance ) {
	$defaults = $this -> zm_defaults();
	$instance = wp_parse_args( (array) $instance, $defaults );
	$instance = wp_parse_args( (array) $instance, array( 
		'title' => '限定时间文章',
		'numposts' => 5,
		'from_y' => 2017,
		'from_m' => 1,
		'from_d' => 2,
		'to_y' => 2017,
		'to_m' => 5,
		'to_d' => 31,
		'sp_cat' => 0)); ?> 

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('show_icon'); ?>">单色图标代码：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('show_svg'); ?>">彩色图标代码：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('show_svg'); ?>" name="<?php echo $this->get_field_name('show_svg'); ?>" type="text" value="<?php echo $instance['show_svg']; ?>" />
		</p>
		<h4 class="from_m_options_header">输入起止日期</h4>

		<p>
			<label for="<?php echo $this->get_field_id('from_y'); ?>" style="width: 33%;">从 
			<input id="<?php echo $this->get_field_id('from_y'); ?>" name="<?php echo $this->get_field_name('from_y'); ?>" type="text" value="<?php echo $instance['from_y']; ?>" size="3" /> 年 
			</label>
			<label for="<?php echo $this->get_field_id('from_m'); ?>" style="width: 33%;"></label>
			<input id="<?php echo $this->get_field_id('from_m'); ?>" name="<?php echo $this->get_field_name('from_m'); ?>" type="text" value="<?php echo $instance['from_m']; ?>" size="3" /> 月 
			<label for="<?php echo $this->get_field_id('from_d'); ?>" style="width: 33%;"></label>
			<input id="<?php echo $this->get_field_id('from_d'); ?>" name="<?php echo $this->get_field_name('from_d'); ?>" type="text" value="<?php echo $instance['from_d']; ?>" size="3" />日起
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('to_y'); ?>" style="width: 33%;">至 </label>
			<input id="<?php echo $this->get_field_id('to_y'); ?>" name="<?php echo $this->get_field_name('to_y'); ?>" type="text" value="<?php echo $instance['to_y']; ?>" size="3" /> 年 
			<label for="<?php echo $this->get_field_id('to_m'); ?>" style="width: 33%;"></label>
			<input id="<?php echo $this->get_field_id('to_m'); ?>" name="<?php echo $this->get_field_name('to_m'); ?>" type="text" value="<?php echo $instance['to_m']; ?>" size="3" /> 月 
			<label for="<?php echo $this->get_field_id('to_d'); ?>" style="width: 33%;"></label>
			<input id="<?php echo $this->get_field_id('to_d'); ?>" name="<?php echo $this->get_field_name('to_d'); ?>" type="text" value="<?php echo $instance['to_d']; ?>" size="3" /> 日止
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('sp_cat'); ?>">选择分类：
			<?php wp_dropdown_categories(array('name' => $this->get_field_name('sp_cat'), 'show_option_all' => '全部分类', 'hide_empty'=>0, 'hierarchical'=>1, 'selected'=>$instance['sp_cat'])); ?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'numposts' ); ?>">显示数量：</label>
			<input class="number-text" id="<?php echo $this->get_field_id( 'numposts' ); ?>" name="<?php echo $this->get_field_name( 'numposts' ); ?>" type="number" step="1" min="1" value="<?php echo $instance['numposts']; ?>" size="3" />
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_thumbs') ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>">显示缩略图</label>
		</p>
<?php }
}
add_action( 'widgets_init', 'be_specified_post_init' );
function be_specified_post_init() {
	register_widget( 'be_specified_post' );
}

// 项目
class be_show_widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'be_show_widget',
			'description' => '调用项目文章',
			'customize_selective_refresh' => true,
		);
		parent::__construct('be_show_widget', '最新项目', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'title_z'    => '',
			'show_icon'  => '',
			'show_svg'   => '',
			'cat'        => '',
			'number'     => 4,
			'show_title' => 1,
			'title'      => '最新项目',
		);
	}

	function widget($args, $instance) {
		extract($args);
		$title_w = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		$titleUrl = empty($instance['titleUrl']) ? '' : $instance['titleUrl'];
		$newWindow = !empty($instance['newWindow']) ? true : false;
		$hideTitle = !empty($instance['hideTitle']) ? true : false;
		if ( zm_get_option('more_im') ) {
			$more_i = '<span class="more-i more-im"><span></span><span></span><span></span></span>';
		} else {
			$more_i = '<span class="more-i"><span></span><span></span><span></span></span>';
		}
		echo $before_widget;
		if ($newWindow) $newWindow = "target='_blank'";
			if (!$hideTitle && $title) {
				if ($titleUrl) $title = "<a href='$titleUrl' $newWindow>$title $more_i</a>";
			}
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 4;
?>

	<?php if ($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><a href="<?php echo $titleUrl; ?>" target="_blank"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?><?php more_i(); ?></a></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><a href="<?php echo $titleUrl; ?>" target="_blank"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?><?php more_i(); ?></a></h3>
	<?php } ?>
<div class="widget-flex-img">
	<?php
		$args = array(
			'post_type' => 'show',
			'showposts' => $number, 
			);

			if ( $instance['cat'] ) {
				$args = array(
					'showposts' => $number, 
					'tax_query' => array(
						array(
							'taxonomy' => 'products',
							'terms' => $instance['cat']
						),
					)
				);
			}
			$fl = isset( $instance['fl'] ) ? $instance['fl'] : '';
 	?>
	<div class="wimg-main">
		<?php $be_query = new WP_Query($args); while ($be_query->have_posts()) : $be_query->the_post(); ?>
			<div class="wimg-item wimg-<?php echo $fl; ?>">
				<div class="wimg-area">
					<?php if ( $instance['show_title'] ) { ?>
						<div class="img-title"><a href="<?php the_permalink() ?>" rel="bookmark" <?php echo goal(); ?>><?php the_title( sprintf( '<span class="img-title-t over">' ), '</span>' ); ?></a></div>
					<?php } ?>
					<?php echo zm_thumbnail(); ?>
				</div>
			</div>
		<?php endwhile;?>
		<?php wp_reset_postdata(); ?>
		<div class="clear"></div>
	</div>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['hideTitle'] = !empty($new_instance['hideTitle']) ? 1 : 0;
		$instance['newWindow'] = !empty($new_instance['newWindow']) ? 1 : 0;
		$instance['show_title'] = !empty($new_instance['show_title']) ? 1 : 0;
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['show_svg'] = strip_tags($new_instance['show_svg']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['titleUrl'] = strip_tags($new_instance['titleUrl']);
		$instance['number'] = strip_tags($new_instance['number']);
		$instance['cat'] = $new_instance['cat'];
		$instance['fl'] = ! empty( $new_instance['fl'] ) ? sanitize_text_field( $new_instance['fl'] ) : '';
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$selected_option = isset( $instance['fl'] ) ? $instance['fl'] : '2';
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '最新项目';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '4'));
		$number = strip_tags($instance['number']);
		$instance = wp_parse_args((array) $instance, array('titleUrl' => ''));
		$titleUrl = $instance['titleUrl'];
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">单色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_svg'); ?>">彩色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_svg'); ?>" name="<?php echo $this->get_field_name('show_svg'); ?>" type="text" value="<?php echo $instance['show_svg']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('titleUrl'); ?>">标题链接：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('titleUrl'); ?>" name="<?php echo $this->get_field_name('titleUrl'); ?>" type="text" value="<?php echo $titleUrl; ?>" />
	</p>
	<p>
		<input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" class="checkbox" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
		<label for="<?php echo $this->get_field_id('newWindow'); ?>">在新窗口打开标题链接</label>
	</p>
	<p>
		<input type="checkbox" id="<?php echo $this->get_field_id( 'show_title' ); ?>" class="checkbox" name="<?php echo $this->get_field_name( 'show_title' ); ?>" <?php checked( isset( $instance['show_title'] ) ? $instance['show_title'] : 0 ); ?> />
		<label for="<?php echo $this->get_field_id( 'show_title' ); ?>">文章标题</label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
		<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => '全部分类', 'hide_empty'=>0, 'hierarchical'=>1,'taxonomy' => 'products', 'selected'=>$instance['cat'])); ?></label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>">显示数量：</label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>
	<ul class="be-widget-radio">
		<li>
			<input type="radio" id="<?php echo $this->get_field_id( '2' ); ?>" name="<?php echo $this->get_field_name( 'fl' ); ?>" value="2" <?php checked( $selected_option, '2' ); ?>>
			<label for="<?php echo $this->get_field_id( '2' ); ?>">2栏</label>
		</li>
		<li>
			<input type="radio" id="<?php echo $this->get_field_id( '3' ); ?>" name="<?php echo $this->get_field_name( 'fl' ); ?>" value="3" <?php checked( $selected_option, '3' ); ?>>
			<label for="<?php echo $this->get_field_id( '3' ); ?>">3栏</label>
		</li>
		<li>
			<input type="radio" id="<?php echo $this->get_field_id( '4' ); ?>" name="<?php echo $this->get_field_name( 'fl' ); ?>" value="4" <?php checked( $selected_option, '4' ); ?>>
			<label for="<?php echo $this->get_field_id( '4' ); ?>">4栏</label>
		</li>
		<li>
			<input type="radio" id="<?php echo $this->get_field_id( '5' ); ?>" name="<?php echo $this->get_field_name( 'fl' ); ?>" value="5" <?php checked( $selected_option, '5' ); ?>>
			<label for="<?php echo $this->get_field_id( '5' ); ?>">5栏</label>
		</li>
		<li>
			<input type="radio" id="<?php echo $this->get_field_id( '6' ); ?>" name="<?php echo $this->get_field_name( 'fl' ); ?>" value="6" <?php checked( $selected_option, '6' ); ?>>
			<label for="<?php echo $this->get_field_id( '6' ); ?>">6栏</label>
		</li>
	</ul>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

// 父子分类名称
class child_cat extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'child_cat',
			'description' => '用于显示当前文章或者分类，同级分类',
			'customize_selective_refresh' => true,
		);
		parent::__construct('child_cat', '同级分类', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'title'     => '同级分类',
		);
	}

	function widget($args, $instance) {
		extract($args);
		$defaults = $this -> zm_defaults();
		$title_w = title_i_w();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);

		if ( get_post_type() == 'post' && get_category_children( get_category_id( the_category_ID( false ) ) ) != "" ) {
			$title = apply_filters( 'widget_title', $instance['title'] );
			echo $before_widget;
			if ( ! empty( $title ) )
			echo $before_title . $title_w . $title . $after_title;
		}

?>

<?php if ( get_post_type() == 'post' && get_category_children( get_category_id( the_category_ID( false ) ) ) != "" ) { ?>
	<div class="be_widget_cat related-cat">
		<?php
			echo '<ul class="cat_list">';
			echo wp_list_categories( "child_of=".get_category_id( the_category_ID( false ) ). "&depth=0&hide_empty=0&use_desc_for_title=&hierarchical=0&title_li=&orderby=id&order=ASC" );
			echo '</ul>';
		?>
		<div class="clear"></div>
	</div>
<?php } ?>
<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		// $instance['author_url'] = $new_instance['author_url'];
		return $instance;
	}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '同级分类';
		}
		global $wpdb;
		// $instance = wp_parse_args((array) $instance, array('author_url' => ''));
		// $author_url = $instance['author_url'];
?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', 'child_cat_init' );
function child_cat_init() {
	register_widget( 'child_cat' );
}

// 同分类文章
class same_post extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'same_post',
			'description' => '调用相同分类的文章',
			'customize_selective_refresh' => true,
		);
		parent::__construct('same_post', '同分类文章', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_thumbs' => 1,
			'number'      => 5,
			'orderby'     => 'ASC',
			'title'       => '',
		);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title_w  = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( ( array ) $instance, $defaults );
		$title    = empty( $instance['title'] ) ? '' : $instance['title'];
		if ( ! empty( $title ) ) {
			$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance );
		} else {
			if ( is_single() ) {
				if ( is_single() ) : global $post; $categories = get_the_category(); foreach ( $categories as $category ) : 
				$title = $category->name;
				endforeach; endif;
				wp_reset_query();
			}
		}
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title;
		$number  = strip_tags( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$orderby = strip_tags( $instance['orderby'] ) ? absint( $instance['orderby'] ) : ASC;
?>

<?php if ( is_single() ) { ?>
<?php if ( $instance['show_thumbs'] ) { ?>
<div class="new_cat">
<?php } else { ?>
<div class="post_cat">
<?php } ?>
	<ul>
		<?php 
			global $post;
			$cat   = get_the_category();
			$catid = '';
			if ( ! empty( $cat ) ) {
				$catid = $cat[0]->term_id;
			}

			$q = new WP_Query( array(
				'showposts'           => $number,
				'post_type'           => 'post',
				'post_status'         => 'publish',
				'cat'                 => $catid,
				'post__not_in'        => array( $post->ID ),
				'orderby'             => $orderby,
				'order'               => $instance['orderby'],
				'ignore_sticky_posts' => 1
			) );
		?>
		<?php while ( $q->have_posts() ) : $q->the_post(); ?>
			<?php if ( $instance['show_thumbs'] ) { ?>
				<li class="cat-title">
				<span class="thumbnail">
					<?php echo zm_thumbnail(); ?>
				</span>
				<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark" <?php echo goal(); ?>><?php the_title(); ?></a></span>
				<?php grid_meta(); ?>
				<?php views_span(); ?>
			</li>
			<?php } else { ?>
				<li class="srm the-icon"><?php the_title( sprintf( '<a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a>' ); ?></li>
			<?php } ?>

		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>
	</ul>
	<div class="clear"></div>
</div>
<?php } ?>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['show_thumbs'] = !empty( $new_instance['show_thumbs'] ) ? 1 : 0;
		$instance['title']       = strip_tags( $new_instance['title'] );
		$instance['number']      = strip_tags( $new_instance['number'] );
		$instance['orderby']     = strip_tags( $new_instance['orderby'] );
		return $instance;
	}
	function form( $instance ) {
		global $wpdb;
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( ( array ) $instance, $defaults );
		$instance = wp_parse_args( ( array ) $instance, array( 'number' => '5' ) );
		$number = strip_tags( $instance['number'] );
		$instance = wp_parse_args(( array ) $instance, array( 'orderby' => 'ASC' ) );
		$orderby = strip_tags( $instance['orderby'] );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		} else {
			$title = '';
		}

?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">自定义标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $instance['title']; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('orderby'); ?>">文章排序：</label>
		<select name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>" class="widefat">
			<option value="ASC"<?php selected( $instance['orderby'], 'ASC' ); ?>>旧的在上面</option>
			<option value="DESC"<?php selected( $instance['orderby'], 'DESC' ); ?>>新的在上面</option>
		</select>
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>">显示数量：</label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>

	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_thumbs' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_thumbs' ) ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>">显示缩略图</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id( 'submit' ); ?>" name="<?php echo $this->get_field_name( 'submit' ); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', 'same_post_init' );
function same_post_init() {
	register_widget( 'same_post' );
}

// 幻灯小工具
class slider_post extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'slider_post',
			'description' => '以幻灯形式调用指定的文章',
			'customize_selective_refresh' => true,
		);
		parent::__construct('slider_post', '幻灯', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'post_id'     => '',
			'title'       => '幻灯',
		);
	}

	function widget($args, $instance) {
		extract($args);
		$title_w = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title;
		$postid = $instance['post_id'];
?>
<div id="slider-widge" class="slider-widge-box">
	<div class="owl-carousel slider-widge">
		<?php
			$args = array(
				'post__in' => explode( ',', $postid ), 
				'orderby'   => 'post__in', 
				'order'     => 'DESC',
				'ignore_sticky_posts' => true, 
				);
			$query = new WP_Query( $args );
		?>
		<?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>
		<div class="slider-widge-main">
			<div class="slider-widge-img"><?php echo zm_widge_thumbnail(); ?></div>
			<?php the_title( sprintf( '<div class="widge-caption"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></div>' ); ?>
		</div>
		<?php endwhile; ?>
		<?php else : ?>
			<ul class="slider-widge-none">在幻灯小工具中输入文章ID</ul>
		<?php endif; ?>
		<?php wp_reset_postdata(); ?>
	</div>

	<div class="lazy-img ajax-owl-loading">
		<?php
			$args = array(
				'post__in' => explode( ',', $postid ), 
				'orderby'   => 'post__in', 
				'order'     => 'DESC',
				'posts_per_page' => 1,
				'ignore_sticky_posts' => true, 
				);
			$query = new WP_Query( $args );
		?>
		<?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>
		<div class="slider-widge-main">
			<div class="slider-widge-img"><?php echo zm_widge_thumbnail(); ?></div>
			<div class="widge-caption"><a href="#" rel="bookmark"><?php _e( '加载中...', 'begin' ); ?></a></div>
		</div>
		<?php break; ?>
		<?php endwhile; ?>
		<?php endif; ?>
		<?php wp_reset_postdata(); ?>
	</div>
</div>
<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['post_id'] = strip_tags($new_instance['post_id']);
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '幻灯';
		}
		global $wpdb;
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'post_id' ); ?>">输入文章ID：</label>
		<textarea style="height:63px;" class="widefat" id="<?php echo $this->get_field_id( 'post_id' ); ?>" name="<?php echo $this->get_field_name( 'post_id' ); ?>"><?php echo stripslashes(htmlspecialchars(( $instance['post_id'] ), ENT_QUOTES)); ?></textarea>
		多个ID用英文半角逗号","隔开，按先后排序
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', 'slider_post_init' );
function slider_post_init() {
	register_widget( 'slider_post' );
}

// 同标签的文章
class be_tag_post extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'be_tag_post',
			'description' => '调用相同标签的文章',
			'customize_selective_refresh' => true,
		);
		parent::__construct('be_tag_post', '同标签文章', $widget_ops);
	}

	public function zm_get_defaults() {
		return array(
			'show_thumbs'   => 1,
			'title_z'       => '',
			'show_icon'     => '',
			'show_svg'      => '',
			'tag_id'        => '',
			'number'        => 5,
			'title'         => '同标签文章',
		);
	}


	function widget($args, $instance) {
		extract($args);
		$title_w = title_i_w();
		$defaults = $this -> zm_get_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
		$tag_id = strip_tags($instance['tag_id']);
?>

<?php if ($instance['show_thumbs']) { ?>
<div class="new_cat">
<?php } else { ?>
<div class="tag_post">
<?php } ?>
	<?php if ($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
	<?php } ?>

	<ul>
		<?php if ($instance['tag_id']) { ?>
			<?php $recent = new WP_Query( array( 'posts_per_page' => $number, 'tag__in' => explode(',', $tag_id)) ); ?>
			<?php while($recent->have_posts()) : $recent->the_post(); ?>
			<li>
				<?php if ($instance['show_thumbs']) { ?>
					<span class="thumbnail">
						<?php echo zm_thumbnail(); ?>
					</span>
				<?php } ?>
				<?php if ($instance['show_thumbs']) { ?>
					<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark" <?php echo goal(); ?>><?php the_title(); ?></a></span>
				<?php } else { ?>
					<?php the_title( sprintf( '<a class="get-icon" href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a>' ); ?>
				<?php } ?>
				<?php if ($instance['show_thumbs']) { ?>
					<?php grid_meta(); ?>
					<?php views_span(); ?>
				<?php } ?>
			</li>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		<?php } else { ?>
			<li>未输入标签ID</li>
		<?php } ?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['show_thumbs'] = !empty($new_instance['show_thumbs']) ? 1 : 0;
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['show_svg'] = strip_tags($new_instance['show_svg']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['tag_id'] = strip_tags($new_instance['tag_id']);
		$instance['number'] = strip_tags($new_instance['number']);
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_get_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '同标签文章';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$number = strip_tags($instance['number']);
		$tag_id = strip_tags($instance['tag_id']);
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">单色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_svg'); ?>">彩色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_svg'); ?>" name="<?php echo $this->get_field_name('show_svg'); ?>" type="text" value="<?php echo $instance['show_svg']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('tag_id'); ?>">输入调用的标签 ID：</label>
		<input class="widefat"  id="<?php echo $this->get_field_id( 'tag_id' ); ?>" name="<?php echo $this->get_field_name( 'tag_id' ); ?>" type="text" value="<?php echo $tag_id; ?>" size="15" /></p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>">显示数量：</label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_thumbs') ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>">显示缩略图</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', 'be_tag_post_init' );
function be_tag_post_init() {
	register_widget( 'be_tag_post' );
}

// 调用指定ID文章
class ids_post extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'ids_post',
			'description' => '调用指定ID的文章',
			'customize_selective_refresh' => true,
		);
		parent::__construct('ids_post', '指定文章', $widget_ops);
	}

	public function zm_get_defaults() {
		return array(
			'show_thumbs'   => 1,
			'title_z'       => '',
			'show_icon'     => '',
			'show_svg'      => '',
			'id_post'       => '',
			'title'         => '指定文章',
		);
	}

	function widget($args, $instance) {
		extract($args);
		$title_w = title_i_w();
		$defaults = $this -> zm_get_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title;
		$id_post = strip_tags($instance['id_post']);
?>


<?php if ($instance['show_thumbs']) { ?>
<div class="new_cat">
<?php } else { ?>
<div class="ids_post">
<?php } ?>
	<?php if ($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<ul>
	<?php
		$args = array(
			'post__in'  => explode( ',', $id_post ), 
			'orderby'   => 'post__in', 
			'order'     => 'DESC',
			'ignore_sticky_posts' => true, 
			);
		$query = new WP_Query( $args );
	?>
	<?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>
		<li>
			<?php if ($instance['show_thumbs']) { ?>
				<span class="thumbnail">
					<?php echo zm_thumbnail(); ?>
				</span>
			<?php } ?>
			<?php if ($instance['show_thumbs']) { ?>
				<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark" <?php echo goal(); ?>><?php the_title(); ?></a></span>
			<?php } else { ?>
				<?php the_title( sprintf( '<a class="get-icon" href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a>' ); ?>
			<?php } ?>
			<?php if ($instance['show_thumbs']) { ?>
				<?php grid_meta(); ?>
				<span class="widget-cat"><i class="be be-sort"></i><?php zm_category(); ?></span>
			<?php } ?>
		</li>
		<?php endwhile; ?>
		<?php else : ?>
			<p>请输入文章ID</p>
		<?php endif; ?>
		<?php wp_reset_postdata(); ?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['show_thumbs'] = !empty($new_instance['show_thumbs']) ? 1 : 0;
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['show_svg'] = strip_tags($new_instance['show_svg']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['id_post'] = strip_tags($new_instance['id_post']);
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_get_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '指定文章';
		}
		global $wpdb;
		$id_post = strip_tags($instance['id_post']);
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">单色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_svg'); ?>">彩色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_svg'); ?>" name="<?php echo $this->get_field_name('show_svg'); ?>" type="text" value="<?php echo $instance['show_svg']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('id_post'); ?>">输入文章ID：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'id_post' ); ?>" name="<?php echo $this->get_field_name( 'id_post' ); ?>" type="text" value="<?php echo $id_post; ?>" size="15" />
		多个ID用英文半角逗号","隔开，按先后排序
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_thumbs') ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>">显示缩略图</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', 'ids_post_init' );
function ids_post_init() {
	register_widget( 'ids_post' );
}

// 分类模块
class t_img_cat extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 't_img_cat',
			'description' => '显示全部分类或某个分类的文章',
			'customize_selective_refresh' => true,
		);
		parent::__construct( 't_img_cat', '分类模块', $widget_ops );
	}

	public function zm_defaults() {
		return array(
			'show_time'   => 0,
			'show_icon'   => 0,
			'title_z'     => '',
			'numposts'    => 5,
			'cat'         => '',
			'title'       => '分类模块',
		);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title_w = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		$titleUrl = empty($instance['titleUrl']) ? '' : $instance['titleUrl'];
		$newWindow = !empty($instance['newWindow']) ? true : false;
		$hideTitle = !empty($instance['hideTitle']) ? true : false;
		if ( zm_get_option('more_im') ) {
			$more_i = '<span class="more-i more-im"><span></span><span></span><span></span></span>';
		} else {
			$more_i = '<span class="more-i"><span></span><span></span><span></span></span>';
		}
		echo $before_widget;
		if ($newWindow) $newWindow = "target='_blank'";
			if ( ! $hideTitle && $title ) {
				if ($titleUrl) $title = "<a href='$titleUrl' $newWindow>$title $more_i</a>";
			}
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title; 
?>

<div class="w-img-cat<?php if ($instance['show_time']) { ?> w-img-cat-time<?php } ?>">
	<?php if ( zm_get_option( 'cat_icon' ) && $instance['show_icon'] ) { ?>
		<h3 class="widget-title-icon cat-w-icon">
			<a href="<?php echo $titleUrl; ?>" rel="bookmark">
				<?php if ( get_option( 'zm_taxonomy_icon' . $instance['cat'] ) ) { ?><i class="t-icon <?php echo zm_taxonomy_icon_code( $instance['cat'] ); ?>"></i><?php } ?>
				<?php if ( get_option( 'zm_taxonomy_svg' . $instance['cat'] ) ) { ?><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo zm_taxonomy_svg_code( $instance['cat'] ); ?>"></use></svg><?php } ?>
				<?php echo $instance['title_z']; ?><?php more_i(); ?>
			</a>
		</h3>
	<?php } ?>
		<?php 
			global $post;
			if ( is_single() ) {
			$q =  new WP_Query(array(
				'ignore_sticky_posts' => 1,
				'showposts'           => '1',
				'post__not_in'        => array( $post->ID ),
				'category__and'       => $instance['cat'],
			));
			} else {
			$q =  new WP_Query(array(
				'ignore_sticky_posts' => 1,
				'showposts'           => '1',
				'category__and'       => $instance['cat']
			));
		} ?>
		<?php while ( $q->have_posts() ) : $q->the_post(); ?>
			<figure class="w-thumbnail"><?php echo zm_long_thumbnail(); ?></figure>
		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>
	<ul class="title-img-cat">
		<?php 
			global $post;
			if ( is_single() ) {
			$q =  new WP_Query(array(
				'ignore_sticky_posts' => 1,
				'showposts'           => $instance['numposts'],
				'post__not_in'        => array( $post->ID ),
				'category__and'       => $instance['cat'],
			));
			} else {
			$q =  new WP_Query(array(
				'ignore_sticky_posts' => 1,
				'showposts'           => $instance['numposts'],
				'category__and'       => $instance['cat']
			));
		} ?>
		<?php while ( $q->have_posts() ) : $q->the_post(); ?>
		<?php if ( $instance['show_time'] ) { ?><span class="w-list-date"><?php the_time('m/d'); ?></span><?php } ?>
		<li class="srm the-icon"><?php the_title( sprintf( '<a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a>' ); ?></li>
		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>
	</ul>
</div>

<?php
	echo $after_widget;
}

function update( $new_instance, $old_instance ) {
	$instance = $old_instance;
	$instance = array();
	$instance['show_icon'] = ! empty( $new_instance['show_icon'] ) ? 1 : 0;
	$instance['show_time'] = ! empty( $new_instance['show_time'] ) ? 1 : 0;
	$instance['hideTitle'] = ! empty( $new_instance['hideTitle'] ) ? 1 : 0;
	$instance['newWindow'] = ! empty( $new_instance['newWindow'] ) ? 1 : 0;
	$instance['title'] = strip_tags( $new_instance['title']);
	$instance['title_z'] = strip_tags( $new_instance['title_z'] );
	$instance['titleUrl'] = strip_tags( $new_instance['titleUrl'] );
	$instance['numposts'] = $new_instance['numposts'];
	$instance['cat'] = $new_instance['cat'];
	return $instance;
}

function form( $instance ) {
	$defaults = $this -> zm_defaults();
	$instance = wp_parse_args( ( array ) $instance, $defaults );
	$instance = wp_parse_args( ( array ) $instance, array( 
		'title'    => '分类模块',
		'titleUrl' => '',
		'numposts' => 5,
		'cat' => 0 ) );
		$titleUrl = $instance['titleUrl'];
		?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('titleUrl'); ?>">标题链接：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('titleUrl'); ?>" name="<?php echo $this->get_field_name('titleUrl'); ?>" type="text" value="<?php echo $titleUrl; ?>" />
		</p>
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" class="checkbox" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
			<label for="<?php echo $this->get_field_id('newWindow'); ?>">在新窗口打开标题链接</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'numposts' ); ?>">显示数量：</label>
			<input class="number-text" id="<?php echo $this->get_field_id( 'numposts' ); ?>" name="<?php echo $this->get_field_name( 'numposts' ); ?>" type="number" step="1" min="1" value="<?php echo $instance['numposts']; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
			<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => '全部分类', 'hide_empty'=>0, 'hierarchical'=>1, 'selected'=>$instance['cat'])); ?></label>
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_icon') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_icon') ); ?>" <?php checked( (bool) $instance["show_icon"], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id('show_icon') ); ?>">显示分类图标</label>
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_time') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_time') ); ?>" <?php checked( (bool) $instance["show_time"], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id('show_time') ); ?>">显示时间</label>
		</p>

<?php }
}

add_action( 'widgets_init', 't_img_cat_init' );
function t_img_cat_init() {
	register_widget( 't_img_cat' );
}

// 分类封面
class widget_cover extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'widget_cover',
			'description' => '以图片封面形式显示分类',
			'customize_selective_refresh' => true,
		);
		parent::__construct('widget_cover', '分类封面', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_tags'     => 0,
			'show_cat_ico'  => 1,
			'cat_id'        => '',
		);
	}

	function widget($args, $instance) {
		extract($args);
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		echo $before_widget;
		$postid = $instance['cat_id'];
?>

<div class="widget-cat-cover">
		<?php
			if ( $instance['show_tags'] ) {
				$terms = get_tags(
					array(
						'include'    => $instance['cat_id'],
						'hide_empty' => false,
						'orderby'    => 'include', 
						'order'      => 'DESC',
					)
				);
			} else {
				$terms = get_terms(
					array(
						'taxonomy'   => 'category',
						'include'    => $instance['cat_id'],
						'hide_empty' => false,
						'orderby'    => 'include', 
						'order'      => 'DESC',
					)
				);
			}
			foreach ( $terms as $term ) {
		?>
		<div class="cat-rec-widget">
			<div class="cat-rec-main">
				<?php if ( zm_get_option( 'cat_cover' ) ) { ?>
					<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" rel="bookmark">
						<div class="boxs1">
							<div class="cat-rec-content ms" <?php aos_a(); ?>>
								<div class="cat-rec lazy<?php if ( get_option( 'zm_taxonomy_svg' . $term->term_id ) ) { ?> cat-rec-ico-svg<?php } else { ?> cat-rec-ico-img<?php } ?>">
									<?php if ( $instance['show_cat_ico'] ) { ?>
										<?php if ( zm_get_option( 'cat_icon' ) ) { ?>
											<?php if ( !get_option('zm_taxonomy_svg' . $term->term_id ) ) { ?>
												<?php if ( get_option('zm_taxonomy_icon' . $term->term_id ) ) { ?><i class="cat-rec-icon fd <?php echo zm_taxonomy_icon_code( $term->term_id ); ?>"></i><?php } ?>
											<?php } else { ?>
												<?php if ( get_option('zm_taxonomy_svg' . $term->term_id ) ) { ?><svg class="cat-rec-svg fd icon" aria-hidden="true"><use xlink:href="#<?php echo zm_taxonomy_svg_code( $term->term_id ); ?>"></use></svg><?php } ?>
											<?php } ?>
										<?php } ?>
									<?php } else { ?>
										<?php if ( zm_get_option( 'cat_cover' ) ) { ?>
											<div class="cat-rec-back" data-src="<?php echo cat_cover_url( $term->term_id ); ?>"></div>
										<?php } ?>
									<?php } ?>
								</div>
								<h4 class="cat-rec-title"><?php echo $term->name; ?></h4>
								<?php if ( category_description( $term->term_id ) ) { ?>
									<div class="cat-rec-des"><?php echo category_description( $term->term_id ); ?></div>
								<?php } else { ?>
									<div class="cat-rec-des"><?php _e( '暂无描述', 'begin' ); ?></div>
								<?php } ?>
								<div class="rec-adorn-s"></div><div class="rec-adorn-x"></div>
								<div class="clear"></div>
							</div>
						</div>
					</a>
				<?php } else { ?>
					<div class="cat-cover-tip">未启用分类封面</div>
				<?php } ?>
			</div>
		</div>
	<?php } ?>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['show_tags'] = !empty($new_instance['show_tags']) ? 1 : 0;
		$instance['show_cat_ico'] = !empty($new_instance['show_cat_ico']) ? 1 : 0;
		$instance['cat_id'] = strip_tags($new_instance['cat_id']);
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		global $wpdb;
?>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_cat_ico') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_cat_ico') ); ?>" <?php checked( (bool) $instance["show_cat_ico"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_cat_ico') ); ?>">图标模式</label>
	</p>

	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_tags') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_tags') ); ?>" <?php checked( (bool) $instance["show_tags"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_tags') ); ?>">调用标签</label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'cat_id' ); ?>">输入分类或标签ID：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('cat_id'); ?>" name="<?php echo $this->get_field_name('cat_id'); ?>" type="text" value="<?php echo $instance['cat_id']; ?>" />
		多个ID用英文半角逗号","隔开，按先后排序
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

add_action( 'widgets_init', 'widget_cover_init' );
function widget_cover_init() {
	register_widget( 'widget_cover' );
}

// 所有专题
class special_tag extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'special_tag',
			'description' => '调用所有专题标题',
			'customize_selective_refresh' => true,
		);
		parent::__construct('special_tag', '所有专题', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'title_z'     => '',
			'show_icon'   => '',
			'show_svg'    => '',
			'title'       => '所有专题',
		);
	}

	function widget($args, $instance) {
		extract($args);
		$title_w = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title;
?>
	<?php if ($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
	<?php } ?>

	<div class="tagcloud">
		<?php 
			$loop = new WP_Query( 
				array( 
					'meta_key' => 'special',
					'post_type' => 'any',
					'post__not_in' => get_option( 'sticky_posts') 
				) 
			);
		 ?>
		<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
			<a href="<?php echo get_permalink(); ?>" rel="bookmark" <?php echo goal(); ?>><?php the_title(); ?></a>
		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>
		<div class="clear"></div>
	</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['show_svg'] = strip_tags($new_instance['show_svg']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '所有专题';
		}
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">单色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_svg'); ?>">彩色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_svg'); ?>" name="<?php echo $this->get_field_name('show_svg'); ?>" type="text" value="<?php echo $instance['show_svg']; ?>" />
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

function special_tag_init() {
	register_widget( 'special_tag' );
}
add_action( 'widgets_init', 'special_tag_init' );

// 图标分类目录
class widget_cat_icon extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'widget_cat_icon',
			'description' => '分类目录可以显示分类图标',
			'customize_selective_refresh' => true,
		);
		parent::__construct('widget_cat_icon', '分类目录', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_icon_no'   => 0,
			'e_cat'          => '',
			'title_z'        => '',
			'show_icon'      => '',
			'show_svg'       => '',
			'hide_empty'     => 0,
			'title'          => '分类目录',
		);
	}

	function widget($args, $instance) {
		extract($args);
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title_w = title_i_w();
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title;
		$e_cat = strip_tags($instance['e_cat']);
?>


<div class="be_widget_cat<?php if ($instance['show_icon_no']) { ?> widget-cat-ico<?php } ?>">
	<?php if ($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<ul>
		<?php
			if ( $instance['hide_empty'] ) {
				$hide_empty = 0;
			} else {
				$hide_empty = 1;
			}
			$terms = get_terms(
				array(
					'taxonomy'   => array( 'category', 'taobao', 'gallery', 'videos', 'products', 'notice' ),
					'exclude' => $e_cat,
					'hide_empty' => $hide_empty,
					'orderby'    => 'id',
					'order'      => 'ASC',
				)
			);
			foreach ( $terms as $term ) {
		?>

		<li>
			<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" rel="bookmark" <?php echo goal(); ?>>
				<?php if (zm_get_option('cat_icon') && $instance['show_icon_no']) { ?>
					<?php if (get_option('zm_taxonomy_icon'. $term->term_id )) { ?><i class="widget-icon <?php echo zm_taxonomy_icon_code( $term->term_id ); ?>"></i><?php } ?>
					<?php if (get_option('zm_taxonomy_svg'. $term->term_id )) { ?><svg class="widget-icon icon" aria-hidden="true"><use xlink:href="#<?php echo zm_taxonomy_svg_code( $term->term_id ); ?>"></use></svg><?php } ?>
				<?php } ?>
				<?php echo $term->name; ?>
			</a>
		</li>
		<?php } ?>
	</ul>
</div>
<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['show_icon_no'] = !empty($new_instance['show_icon_no']) ? 1 : 0;
		$instance['hide_empty'] = !empty($new_instance['hide_empty']) ? 1 : 0;
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['show_svg'] = strip_tags($new_instance['show_svg']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['e_cat'] = strip_tags($new_instance['e_cat']);
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '分类目录';
		}
		global $wpdb;
		$e_cat = strip_tags($instance['e_cat']);
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">单色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_svg'); ?>">彩色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_svg'); ?>" name="<?php echo $this->get_field_name('show_svg'); ?>" type="text" value="<?php echo $instance['show_svg']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('e_cat'); ?>">输入排除的分类ID：</label>
		<textarea style="height:50px;" class="widefat" id="<?php echo $this->get_field_id( 'e_cat' ); ?>" name="<?php echo $this->get_field_name( 'e_cat' ); ?>"><?php echo stripslashes(htmlspecialchars(( $instance['e_cat'] ), ENT_QUOTES)); ?></textarea>
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('hide_empty') ); ?>" name="<?php echo esc_attr( $this->get_field_name('hide_empty') ); ?>" <?php checked( (bool) $instance["hide_empty"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('hide_empty') ); ?>">显示空分类</label>
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_icon_no') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_icon_no') ); ?>" <?php checked( (bool) $instance["show_icon_no"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_icon_no') ); ?>">显示分类图标</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

add_action( 'widgets_init', 'widget_cat_icon_init' );
function widget_cat_icon_init() {
	register_widget( 'widget_cat_icon' );
}

// 目录树
class Tree_Cat_Widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'tree-cat-widget',
			'description' => '以目录树方式显示子分类目录',
			'customize_selective_refresh' => true,
		);
		parent::__construct('Tree_Cat_Widget', '目录树', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'title_z'      => '',
			'show_icon'    => '',
			'show_tree'    => '1',
			'show_svg'     => '',
			'count'        => '1',
			'e_cat'        => '',
			'hide_empty'     => '',
			'title'        => '目录树',
		);
	}

	function widget($args, $instance) {
		extract($args);
		$title_w = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title;
		$e_cat = strip_tags($instance['e_cat']);
		$c = ! empty( $instance['count'] ) ? '1' : '0';
?>
	<?php if ($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
	<?php } ?>

<ul class="tree-cat<?php if ( ! $instance['count'] ) { ?> tree-cat-count-no<?php } ?>">
	<?php
		if ( $instance['hide_empty'] ) {
			$hide_empty = 0;
		} else {
			$hide_empty = 1;
		}

		if ( $instance['count'] ) {
			$show_count = 1;
		} else {
			$show_count = 0;
		}
		$args = wp_list_categories( array(
			'exclude'            => $e_cat,
			'hide_empty'         => $hide_empty,
			'echo'               => false,
			'show_count'         => $show_count,
			'use_desc_for_title' => 0,
			'include'            => '',
			'title_li'           => ''
		) );

		$args = preg_replace( '~\((\d+)\)(?=\s*+<)~', '<sup class="count">$1</sup>', $args );
		echo $args;
	?>
</ul>
<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['hide_empty'] = !empty($new_instance['hide_empty']) ? 1 : 0;
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['show_svg'] = strip_tags($new_instance['show_svg']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['about_back'] = $new_instance['about_back'];
		$instance['e_cat'] = strip_tags($new_instance['e_cat']);
		$instance['count'] = ! empty( $new_instance['count'] ) ? 1 : 0;
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '目录树';
		}
		global $wpdb;
		$e_cat = strip_tags($instance['e_cat']);
		$count = isset( $instance['count'] ) ? (bool) $instance['count'] : false;
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>"<?php checked( $count ); ?> />
		<label for="<?php echo $this->get_field_id( 'count' ); ?>">显示文章数目</label><br />
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('hide_empty') ); ?>" name="<?php echo esc_attr( $this->get_field_name('hide_empty') ); ?>" <?php checked( (bool) $instance["hide_empty"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('hide_empty') ); ?>">显示空分类</label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">单色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_svg'); ?>">彩色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_svg'); ?>" name="<?php echo $this->get_field_name('show_svg'); ?>" type="text" value="<?php echo $instance['show_svg']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('e_cat'); ?>">输入排除的分类ID：</label>
		<textarea style="height:50px;" class="widefat" id="<?php echo $this->get_field_id( 'e_cat' ); ?>" name="<?php echo $this->get_field_name( 'e_cat' ); ?>"><?php echo stripslashes(htmlspecialchars(( $instance['e_cat'] ), ENT_QUOTES)); ?></textarea>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

add_action( 'widgets_init', 'Tree_Cat_Widget_init' );
function Tree_Cat_Widget_init() {
	register_widget( 'Tree_Cat_Widget' );
}

// 公告
class widget_notice extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'widget_notice',
			'description' => '滚动显示公告',
			'customize_selective_refresh' => true,
		);
		parent::__construct('widget_notice', '滚动公告', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'title_z'        => '',
			'show_icon'      => '',
			'show_svg'       => '',
			'cat'            => '',
			'number'         => 2,
			'notice_back'    => get_template_directory_uri() . '/img/default/random/320.jpg',
		);
	}

	function widget($args, $instance) {
		extract($args);
		$title_w = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 2;
?>

<div class="zm-notice">

	<?php if ($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ( $instance[ 'notice_back' ]  ) { ?>
		<div class="list-img-box"><img src="<?php echo $instance['notice_back']; ?>" alt="notice"></div>
	<?php } ?>
	<div class="clear"></div>
	<?php if ( $instance[ 'notice_back' ]  ) { ?>
	<div class="notice-main notice-main-img">
		<?php } else { ?>
	<div class="notice-main">
	<?php } ?>
		<ul class="list placardtxt owl-carousel">
			<?php
				$args = array(
					'post_type' => 'bulletin',
					'showposts' => $number, 
					'tax_query' => array(
						array(
							'taxonomy' => 'notice',
							'terms' => $instance['cat']
						),
					)
				);
		 	?>
			<?php $be_query = new WP_Query($args); while ($be_query->have_posts()) : $be_query->the_post(); ?>
			<?php the_title( sprintf( '<li><a href="%s" rel="bookmark" ' . goal() . '><i class="be be-volumedown ri"></i>', esc_url( get_permalink() ) ), '</a></li>' ); ?>
			<?php endwhile;?>
			<?php wp_reset_postdata(); ?>
		</ul>
	</div>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['hideTitle'] = !empty($new_instance['hideTitle']) ? 1 : 0;
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['show_svg'] = strip_tags($new_instance['show_svg']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['notice_back'] = $new_instance['notice_back'];
		$instance['number'] = strip_tags($new_instance['number']);
		$instance['cat'] = $new_instance['cat'];
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '滚动公告';
		}
	global $wpdb;
	$instance = wp_parse_args((array) $instance, array('number' => '2'));
	$number = strip_tags($instance['number']);
		$instance = wp_parse_args((array) $instance, array('notice_back' => get_template_directory_uri() . '/img/default/random/320.jpg'));
		$notice_back = $instance['notice_back'];
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">单色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_svg'); ?>">彩色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_svg'); ?>" name="<?php echo $this->get_field_name('show_svg'); ?>" type="text" value="<?php echo $instance['show_svg']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('notice_back'); ?>">背景图片：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'notice_back' ); ?>" name="<?php echo $this->get_field_name( 'notice_back' ); ?>" type="text" value="<?php echo $notice_back; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
		<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => '选择分类', 'hide_empty'=>0, 'hierarchical'=>1,'taxonomy' => 'notice', 'selected'=>$instance['cat'])); ?></label>
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>">显示数量：</label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>

	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

// 专题
class widget_special extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'widget_special',
			'description' => '调用专题封面',
			'customize_selective_refresh' => true,
		);
		parent::__construct('widget_special', '专题封面', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_tags'   => 0,
			'title_z'     => '',
			'show_icon'   => '',
			'show_svg'    => '',
			'pages_id'    => '',
			'title'       => '专题封面',
		);
	}

	function widget($args, $instance) {
		extract($args);
		$title_w = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title;
		$postid = $instance['pages_id'];
?>

<div class="widget-cat-cover">
	<?php if ($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
	<?php } ?>

	<?php if ($instance['pages_id']) { ?>
		<?php 
			global $post;
			$posts = get_posts( array( 'post_type' => 'any', 'orderby' => 'include', 'order' => 'DESC', 'include' => $instance['pages_id']) ); if ($posts) : foreach( $posts as $post ) : setup_postdata( $post );
		?>
		<div class="cover4x">
			<div class="cat-cover-main">
				<div class="cat-cover-img thumbs-b lazy">
					<?php $image = get_post_meta(get_the_ID(), 'thumbnail', true); ?>
					<a class="thumbs-back" href="<?php echo get_permalink( $post->ID ); ?>" rel="bookmark" <?php echo goal(); ?> data-src="<?php echo $image; ?>">
						<div class="special-mark fd"><?php _e( '专题', 'begin' ); ?></div>
						<div class="cover-des-box">
							<?php 
								$special = get_post_meta( get_the_ID(), 'special', true );
								echo '<div class="special-count fd">';
								echo get_tag_post_count( $special );
								echo  _e( '篇', 'begin' );
								echo '</div>';
							?>
							<div class="cover-des">
								<div class="cover-des-main over">
									<?php
									$description = get_post_meta( get_the_ID(), 'description', true );
									if ( get_post_meta( get_the_ID(), 'description', true ) ) { ?>
										<?php echo $description; ?>
									<?php } else { ?>
									<?php echo get_the_title($post->ID); ?>
									<?php } ?>
								</div>
							</div>
						</div>
					</a>
					<h4 class="cat-cover-title"><?php echo get_the_title($post->ID); ?></h4>
				</div>
			</div>
		</div>
		<?php endforeach; endif; ?>
		<?php wp_reset_query(); ?>
	<?php } else { ?>
		<ul><li><?php _e( '未添加专题ID', 'begin' ); ?></li></ul>
	<?php } ?>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['show_svg'] = strip_tags($new_instance['show_svg']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['pages_id'] = strip_tags($new_instance['pages_id']);
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '专题封面';
		}
		global $wpdb;
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">单色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_svg'); ?>">彩色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_svg'); ?>" name="<?php echo $this->get_field_name('show_svg'); ?>" type="text" value="<?php echo $instance['show_svg']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'pages_id' ); ?>">输入专题页面ID：</label>
		<textarea style="height:80px;" class="widefat" id="<?php echo $this->get_field_id( 'pages_id' ); ?>" name="<?php echo $this->get_field_name( 'pages_id' ); ?>"><?php echo stripslashes(htmlspecialchars(( $instance['pages_id'] ), ENT_QUOTES)); ?></textarea>
		多个ID用英文半角逗号","隔开，按先后排序
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

add_action( 'widgets_init', 'widget_special_init' );
function widget_special_init() {
	register_widget( 'widget_special' );
}

// 最近浏览的文章
class Be_recently_viewed extends WP_Widget {
	public function __construct() {
		$widget_options = array(
			'classname'                   => 'be_recently_viewed',
			'description'                 => '显示访问者最近浏览的文章',
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'be_recently_viewed', '最近浏览的文章', $widget_options );
	}

	public function widget( $args, $instance ) {
		$be_cookie_posts = isset( $_COOKIE['astx_recent_posts'] ) ? json_decode( $_COOKIE['astx_recent_posts'], true ) : null;
		if ( isset( $be_cookie_posts ) ) {
			$be_cookie_posts = array_diff( $be_cookie_posts, array( get_the_ID() ) );
			global $post;
			$post_id = get_the_ID();
			if ( count( $be_cookie_posts ) > 0 ):
				$widget_id           = $args['widget_id'];
				$title_w             = title_i_w();
				$widget_id           = str_replace( 'be_recently_viewed-', '', $widget_id );
				$widget_options      = get_option( $this->option_name );
				$instance            = $widget_options[ $widget_id ];
				$title               = ( ! empty( $instance['title'] ) ) ? $instance['title'] :  '最近浏览的文章';
				$title               = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance );
				$number              = ( ! empty( $instance['numberofposts'] ) ) ? absint( $instance['numberofposts'] ) : 5;
				$show_thumbnail      = isset( $instance['showthumbnail'] ) ? $instance['showthumbnail'] : true;
				$clear_cookie        = isset( $instance['clearcookie'] ) ? $instance['clearcookie'] : false;
				$show_time           = isset( $instance['show_time'] ) ? $instance['show_time'] : true;
				$selected_types      = isset( $instance["selected_types"] ) && is_array( $instance["selected_types"] ) ? $instance["selected_types"] : array( 'post', 'page' );
				extract( $args, EXTR_SKIP );
				$count = 0;
				foreach ( $be_cookie_posts as $post_id ) {
					if ( $count >= $number || absint( $post_id ) == 0 ) {
						break;
					}
					$post = get_post( absint( $post_id ) );
					if ( isset( $post ) && in_array( $post->post_type, $selected_types ) ) {
						$count ++;
						if ( $count == 1 ) {
							echo $before_widget;
							if ( $title ) {
								echo $before_title . $title_w . $title . $after_title; 
							}
							if ( $show_thumbnail ) {
								echo '<ul class="new_cat">';
							} else {
								echo '<ul class="post_cat">';
							}
							if ( $clear_cookie ) {
								echo '<div class="clear-cookie" title="' . sprintf( __( '清除', 'begin' ) ) . '"><span class="dashicons dashicons-dismiss"></span></div>';
							}
						}
						$permalink = esc_url( get_permalink( $post->ID ) );
					?>
					<?php if ( $show_thumbnail ) { ?>
						<li>
							<span class="thumbnail">
								<?php echo zm_thumbnail(); ?>
							</span>
							<span class="new-title">
								<a href="<?php echo $permalink; ?>" rel="bookmark" <?php echo goal(); ?>><?php echo apply_filters( 'the_title', $post->post_title, $post->ID ); ?></a>
							</span>
							<?php grid_meta(); ?>
							<?php views_span(); ?>
						</li>
					<?php } else { ?>
						<li class="only-title<?php if ( $show_time ) { ?> only-title-date<?php } ?>">
							<?php if ( $show_time ) { ?><?php grid_meta(); ?><?php } ?>
							<a class="srm get-icon" href="<?php echo $permalink; ?>" rel="bookmark" <?php echo goal(); ?>><?php echo apply_filters( 'the_title', $post->post_title, $post->ID ); ?></a>
						</li>
					<?php } ?>
						<?php
					}
				}
				if ( $count > 0 ) {
					echo '</ul>' . $after_widget;
				}
			endif;
		}
	}

    public function form( $instance ) {
		$widget_id           = str_replace( 'be_recently_viewed-', '', $this->id );
		$title               = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '最近浏览的文章';
		$numberofposts       = isset( $instance['numberofposts'] ) ? absint( $instance['numberofposts'] ) : 5;
		$show_thumbnail      = isset( $instance['showthumbnail'] ) ? (bool) $instance['showthumbnail'] : true;
		$clearcookie         = isset( $instance['clearcookie'] ) ? (bool) $instance['clearcookie'] : false;
		$show_time           = isset( $instance['show_time'] ) ? (bool) $instance['show_time'] : true;
		$selected_types      = isset( $instance['selected_types'] ) && is_array( $instance['selected_types'] ) ? $instance['selected_types'] : array( 'post', 'page' );
		$custom_post_types   = get_post_types( array( 'public' => true, '_builtin' => false ), 'names', 'and' );
		$default_post_types  = array( 'post' => 'post', 'page' => 'page' );
		$post_types          = array_merge( $custom_post_types, $default_post_types );
	?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>"/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'numberofposts' ); ?>">显示数量：</label>
			<input class="number-text" id="<?php echo $this->get_field_id( 'numberofposts' ); ?>" name="<?php echo $this->get_field_name( 'numberofposts' ); ?>" type="text" size="3" value="<?php echo $numberofposts; ?>"/>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked( $show_thumbnail ); ?> id="<?php echo $this->get_field_id( 'showthumbnail' ); ?>" name="<?php echo $this->get_field_name( 'showthumbnail' ); ?>">
			<label for="<?php echo $this->get_field_id( 'showthumbnail' ); ?>">显示缩略图</label>
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $show_time ); ?> id="<?php echo $this->get_field_id( 'show_time' ); ?>" name="<?php echo $this->get_field_name( 'show_time' ); ?>">
			<label for="<?php echo $this->get_field_id( 'show_time' ); ?>">无图显示日期</label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked( $clearcookie ); ?> id="<?php echo $this->get_field_id( 'clearcookie' ); ?>" name="<?php echo $this->get_field_name( 'clearcookie' ); ?>">
			<label for="<?php echo $this->get_field_id( 'clearcookie' ); ?>">清除按钮</label>
		</p>

		<p>
			<label>选择</label>
			<ul class="be-widget-radio">
				<?php 
					$post_types = array_reverse( $post_types );
					foreach ( $post_types as $post_type ) {
						$obj         = get_post_type_object( $post_type );
						$post_name   = $obj->labels->name;
						$is_selected = false;
						if ( in_array( $post_type, $selected_types ) ) {
							$is_selected = true;
						}
						?>
		
						<li>
							<input type="checkbox" class="checkbox" id="be_checkbox_<?php echo $post_type; ?>" name="<?php echo $this->get_field_name( 'selected_types' ) . '[]'; ?>" value="<?php echo $post_type; ?>" <?php checked( $is_selected ); ?>>
							<label><?php echo $post_name; ?></label>
						</li>
						<?php
					}
				?>
			</ul>
		</p>
		<?php if ( $widget_id != '__i__' ) { ?>
			<p>
				<!--<span class="shortcodeTtitle"><?php _e( '短代码:', 'text_domain' ); ?></span>
				<span class="shortcode">[be-recentlyviewed widget_id="<?php echo $widget_id; ?>"]</span>
			</p>
			-->
		<?php }
	}

	public function update( $new_instance, $old_instance ) {
		$instance                   = $old_instance;
		$instance['title']          = isset( $new_instance['title'] ) ? $new_instance['title'] : '';
		$instance['numberofposts']  = isset( $new_instance['numberofposts'] ) ? absint( $new_instance['numberofposts'] ) : 5;
		$instance['showthumbnail']  = isset( $new_instance['showthumbnail'] ) ? (bool) $new_instance['showthumbnail'] : false;
		$instance['clearcookie']    = isset( $new_instance['clearcookie'] ) ? (bool) $new_instance['clearcookie'] : false;
		$instance['show_time']      = isset( $new_instance['show_time'] ) ? (bool) $new_instance['show_time'] : false;
		$instance['selected_types'] = isset( $new_instance['selected_types'] ) && is_array( $new_instance['selected_types'] ) ? $new_instance['selected_types'] : array();

		return $instance;
	}
}

// 注册小部件
function register_recently_viewed() {
	register_widget( 'Be_recently_viewed' );
}
add_action( 'widgets_init', 'register_recently_viewed' );

// 操作cookie
add_action( 'template_redirect', 'be_posts_visited' );
function be_posts_visited() {
	if ( is_single() || is_page() ) {
		$cooki    = 'astx_recent_posts';
		$be_posts = isset( $_COOKIE[ $cooki ] ) ? json_decode( $_COOKIE[ $cooki ], true ) : null;
		if ( isset( $be_posts ) ) {
			// 删除cookie中的当前文章
			$be_posts = array_diff( $be_posts, array( get_the_ID() ) );
			// 用当前的文章更新cookie
			array_unshift( $be_posts, get_the_ID() );
		} else {
			$be_posts = array( get_the_ID() );
		}
		setcookie( $cooki, json_encode( $be_posts ), time() + ( DAY_IN_SECONDS * 31 ), COOKIEPATH, COOKIE_DOMAIN );
	}
}

// 短代码
function be_shortcode_recentlyViewed( $atts ) {
	$args = array(
		'widget_id'    => $atts['widget_id'],
		'by_shortcode' => 'shortcode_',
	);

	ob_start();
	the_widget( 'Be_recently_viewed', '', $args );
	$output = ob_get_clean();
	return $output;
}

add_shortcode( 'be-recentlyviewed', 'be_shortcode_recentlyViewed' );
// 多条件筛选
class widget_filter extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'widget_filter',
			'description' => '多条件筛选',
			'customize_selective_refresh' => true,
		);
		parent::__construct('widget_filter', '条件筛选', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'title_z'     => '',
			'show_icon'   => '',
			'show_svg'    => '',
			'title'       => '条件筛选',
		);
	}

	function widget($args, $instance) {
		extract($args);
		$title_w = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		if ( ! is_page() ) {
			echo $before_widget;
		}
	?>

	<?php if ( ! is_page() ) { ?>
	<div class="widget-filter">
		<div class="filter-box">
			<div class="filter-t"><i class="be be-sort"></i><span><?php echo zm_get_option('filter_t'); ?></span></div>
				<?php if (zm_get_option('filters_hidden')) { ?><div class="filter-box-main filter-box-main-h"><?php } else { ?><div class="filter-box-main"><?php } ?>
				<?php require get_template_directory() . '/inc/filter-core.php'; ?>
				<div class="clear"></div>
			</div>
		</div>
	</div>
	<?php } ?>

	<?php
		echo $after_widget;
		}
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance = array();
			return $instance;
		}
		function form($instance) {
			$defaults = $this -> zm_defaults();
			$instance = wp_parse_args( (array) $instance, $defaults );
			global $wpdb;

	?>
	<p>多条件筛选该小工具</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
	<?php }
}
add_action( 'widgets_init', 'widget_filter_init' );
function widget_filter_init() {
	register_widget( 'widget_filter' );
}

// 单栏分类
class widget_color_cat extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'widget_color_cat',
			'description' => '单栏分类目录',
			'customize_selective_refresh' => true,
		);
		parent::__construct('widget_color_cat', '单栏分类', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'title_z'      => '',
			'show_icon'    => '',
			'show_svg'     => '',
			'e_cat'        => '',
			'title'        => '单栏分类',
		);
	}

	function widget($args, $instance) {
		extract($args);
		$title_w = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title;
		$e_cat = strip_tags($instance['e_cat']);
?>
	<?php if ($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
	<?php } ?>

<ul class="color-cat">
	<?php
		$terms = get_terms(
			array(
				'taxonomy'      => array( 'category', 'taobao', 'gallery', 'videos', 'products', 'notice' ),
				'include'       => '',
				'exclude'       => $e_cat,
				'hide_empty'   => true,
				'hierarchical' => false,
				'title_li'     =>  '',
				'orderby'      => 'id',
				'order'        => 'ASC',
			)
		);
		foreach ( $terms as $term ) {
	?>
	<li><a class="get-icon" href="<?php echo esc_url( get_term_link( $term ) ); ?>" rel="bookmark" <?php echo goal(); ?>><?php echo $term->name; ?></a></li>
	<?php } ?>
</ul>
<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['show_svg'] = strip_tags($new_instance['show_svg']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['about_back'] = $new_instance['about_back'];
		$instance['e_cat'] = strip_tags($new_instance['e_cat']);
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '单栏分类';
		}
		global $wpdb;
		$e_cat = strip_tags($instance['e_cat']);
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">单色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_svg'); ?>">彩色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_svg'); ?>" name="<?php echo $this->get_field_name('show_svg'); ?>" type="text" value="<?php echo $instance['show_svg']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('e_cat'); ?>">输入排除的分类ID：</label>
		<textarea style="height:50px;" class="widefat" id="<?php echo $this->get_field_id( 'e_cat' ); ?>" name="<?php echo $this->get_field_name( 'e_cat' ); ?>"><?php echo stripslashes(htmlspecialchars(( $instance['e_cat'] ), ENT_QUOTES)); ?></textarea>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', 'widget_color_cat_init' );
function widget_color_cat_init() {
	register_widget( 'widget_color_cat' );
}

// 专题模块
class special_list extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'special_list',
			'description' => '调用某个专题文章列表',
			'customize_selective_refresh' => true,
		);
		parent::__construct('special_list', '专题模块', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'tag_slug'    => '',
			'title_z'     => '',
			'show_icon'   => '',
			'show_svg'    => '',
			'show_svg'    => '',
			'numposts'    => 5,
			'title'       => '专题模块',
		);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title_w = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		$titleUrl = empty($instance['titleUrl']) ? '' : $instance['titleUrl'];
		$newWindow = !empty($instance['newWindow']) ? true : false;
		$hideTitle = !empty($instance['hideTitle']) ? true : false;
		if ( zm_get_option('more_im') ) {
			$more_i = '<span class="more-i more-im"><span></span><span></span><span></span></span>';
		} else {
			$more_i = '<span class="more-i"><span></span><span></span><span></span></span>';
		}
		echo $before_widget;
		if ($newWindow) $newWindow = "target='_blank'";
			if (!$hideTitle && $title) {
				if ($titleUrl) $title = "<a href='$titleUrl' $newWindow>$title $more_i</a>";
			}
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title; 
?>

<div class="special-list">
	<?php if ($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><a href="<?php echo $instance['titleUrl']; ?>" rel="bookmark"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?><?php more_i(); ?></a></h3>
	<?php } ?>
	<?php
		$args = array(
			'posts_per_page' => '1', 
			'post_type' => 'page', 
			'meta_key' => 'special',
			'meta_value' => $instance['tag_slug'],
			'ignore_sticky_posts' => true, 
			);
		$query = new WP_Query( $args );
	?>
	<?php while ( $query->have_posts() ) : $query->the_post(); ?>

	<div class="cover4x">
		<div class="cat-cover-main">
			<div class="cat-cover-img">
				<a href="<?php echo get_permalink(); ?>" rel="bookmark" <?php echo goal(); ?>>
					<div class="special-mark bz fd"><?php _e( '专题', 'begin' ); ?></div>
					<figure class="cover-img">
						<?php
							$image = get_post_meta( get_the_ID(), 'thumbnail', true );
							echo '<img src="' . $image. '" alt="' . get_the_title() . '" />'; 
						?>
					</figure>
					<div class="cover-des-box"><div class="cover-des"><?php $description = get_post_meta( get_the_ID(), 'description', true );{echo $description;} ?></div></div>
				</a>
				<div class="clear"></div>
			</div>
			<a href="<?php echo get_permalink(); ?>" rel="bookmark" <?php echo goal(); ?>>
				<div class="special-title-widget"><?php the_title(); ?>
				<span class="special-more">
					<?php 
						global $wpdb, $post;
						$special = get_post_meta( get_the_ID(), 'special', true );
						echo '<span class="special-list-count">';
						if ( get_tag_post_count( $special ) >= 1 ) {
							echo  _e( '共', 'begin' );
							echo get_tag_post_count( $special );
							echo  _e( '篇', 'begin' );
						} else {
							echo '未添加文章</span>';
						}
						echo '</span>  ';
					?>
					<?php _e( '更多', 'begin' ); ?>
				</span>
			</div>
			</a>
		</div>
	</div>
	<?php endwhile; ?>
	<?php wp_reset_postdata(); ?>

	<ul class="post_cat">
	<?php
		$args = array(
			'tag_id' => get_tag_id_slug($instance['tag_slug']),
			'posts_per_page' => $instance['numposts'],
			'ignore_sticky_posts' => true, 
			);
		$query = new WP_Query( $args );
	?>
	<?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>
		<?php the_title( sprintf( '<li class="srm the-icon"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></li>' ); ?>
		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>
		<?php else : ?>
			<?php _e( '未添加文章', 'begin' ); ?>
		<?php endif; ?>
	</ul>
</div>

<?php
	echo $after_widget;
}

function update( $new_instance, $old_instance ) {
	$instance = $old_instance;
	$instance = array();
	$instance['hideTitle'] = !empty($new_instance['hideTitle']) ? 1 : 0;
	$instance['newWindow'] = !empty($new_instance['newWindow']) ? 1 : 0;
	$instance['show_icon'] = strip_tags($new_instance['show_icon']);
	$instance['show_svg'] = strip_tags($new_instance['show_svg']);
	$instance['title'] = strip_tags($new_instance['title']);
	$instance['title_z'] = strip_tags($new_instance['title_z']);
	$instance['titleUrl'] = strip_tags($new_instance['titleUrl']);
	$instance['numposts'] = $new_instance['numposts'];
	$instance['tag_slug'] = $new_instance['tag_slug'];
	return $instance;
}

function form( $instance ) {
	$defaults = $this -> zm_defaults();
	$instance = wp_parse_args( (array) $instance, $defaults );
	$instance = wp_parse_args( (array) $instance, array( 
		'title' => '专题模块',
		'titleUrl' => '',
		'numposts' => 5
	));
		$titleUrl = $instance['titleUrl'];
		$tag_slug = $instance['tag_slug'];
		 ?> 

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('show_icon'); ?>">单色图标代码：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('show_svg'); ?>">彩色图标代码：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('show_svg'); ?>" name="<?php echo $this->get_field_name('show_svg'); ?>" type="text" value="<?php echo $instance['show_svg']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('titleUrl'); ?>">标题链接：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('titleUrl'); ?>" name="<?php echo $this->get_field_name('titleUrl'); ?>" type="text" value="<?php echo $titleUrl; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('tag_slug'); ?>">专题标签别名：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('tag_slug'); ?>" name="<?php echo $this->get_field_name('tag_slug'); ?>" type="text" value="<?php echo $tag_slug; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'numposts' ); ?>">显示数量：</label>
			<input class="number-text" id="<?php echo $this->get_field_id( 'numposts' ); ?>" name="<?php echo $this->get_field_name( 'numposts' ); ?>" type="number" step="1" min="1" value="<?php echo $instance['numposts']; ?>" size="3" />
		</p>
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" class="checkbox" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
			<label for="<?php echo $this->get_field_id('newWindow'); ?>">在新窗口打开标题链接</label>
		</p>
<?php }
}

add_action( 'widgets_init', 'special_list_init' );
function special_list_init() {
	register_widget( 'special_list' );
}

// 同分类热门文章
class cat_popular extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'cat_popular',
			'description' => '调用相同分类的热门文章',
			'customize_selective_refresh' => true,
		);
		parent::__construct('cat_popular', '本类热门', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_thumbs'   => 1,
			'title_z'       => '',
			'show_icon'     => '',
			'show_svg'      => '',
			'number'        => 5,
			'metakey'        => 'views',
			'catid'        => '',
			'title'         => '本类热门',
		);
	}

	function widget($args, $instance) {
		extract($args);
		$title_w = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
		$metakey = strip_tags($instance['metakey']) ? absint( $instance['metakey'] ) : 'views';
?>

<?php if ($instance['show_thumbs']) { ?>
<div id="hot" class="hot_commend">
<?php } else { ?>
<div id="hot_comment_widget">
<?php } ?>
	<?php if ($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<ul>
		<?php 
			global $post, $catid;
			$cat = get_the_category();
			if ( $instance['catid'] ) { 
				$catid = $instance['catid'];
			} else {
				foreach($cat as $key=>$category){
					$catid = $category->term_id;
				}
			}
			$q = new WP_Query( array(
				'showposts' => $number,
				'post_type' => 'any',
				'cat' => $catid,
				'post__not_in' => array($post->ID),
				'meta_key' => $instance['metakey'],
				'orderby' => 'meta_value_num',
				'order' => 'date',
				'ignore_sticky_posts' => 1
			) );
		?>
		<?php $i = 1; while ($q->have_posts()) : $q->the_post(); ?>
			<?php if ($instance['show_thumbs']) { ?>
			<li>
				<span class="thumbnail">
					<?php echo zm_thumbnail(); ?>
				</span>
				<span class="hot-title"><a href="<?php the_permalink(); ?>" rel="bookmark" <?php echo goal(); ?>><?php the_title(); ?></a></span>
				<?php views_span(); ?>
				<span class="be-like"><i class="be be-thumbs-up-o ri"></i><?php zm_get_current_count(); ?></span>
			</li>
			<?php } else { ?>
				<li class="srm"><span class="new-title"><span class='li-icon li-icon-<?php echo($i); ?>'><?php echo($i++); ?></span><a href="<?php the_permalink(); ?>" rel="bookmark" <?php echo goal(); ?>><?php the_title(); ?></a></span></li>
			<?php } ?>
		<?php endwhile;?>
		<?php wp_reset_postdata(); ?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['show_thumbs'] = !empty($new_instance['show_thumbs']) ? 1 : 0;
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['show_svg'] = strip_tags($new_instance['show_svg']);
		$instance['metakey'] = strip_tags($new_instance['metakey']);
		$instance['catid'] = strip_tags($new_instance['catid']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = strip_tags($new_instance['number']);
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '本类热门';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$number = strip_tags($instance['number']);
		$instance = wp_parse_args((array) $instance, array('metakey' => 'views'));
		$metakey = strip_tags($instance['metakey']);
		$catid = strip_tags($instance['catid']);
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">单色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_svg'); ?>">彩色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_svg'); ?>" name="<?php echo $this->get_field_name('show_svg'); ?>" type="text" value="<?php echo $instance['show_svg']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('metakey'); ?>">自定义栏目名称：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('metakey'); ?>" name="<?php echo $this->get_field_name('metakey'); ?>" type="text" value="<?php echo $instance['metakey']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('catid'); ?>">指定分类ID：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('catid'); ?>" name="<?php echo $this->get_field_name('catid'); ?>" type="text" value="<?php echo $instance['catid']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>">显示数量：</label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_thumbs') ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>">显示缩略图</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

function cat_popular_init() {
	register_widget( 'cat_popular' );
}
add_action( 'widgets_init', 'cat_popular_init' );

// AJAX Widget
class ajax_text_widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'ajax_text',
			'description' => '使用Ajax加载短代码',
			'customize_selective_refresh' => true,
		);
		parent::__construct('ajax_text', '短代码', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'title_z'       => '',
			'show_icon'     => '',
			'show_svg'      => '',
			'text'          => '',
			'title'         => '短代码',
		);
	}

	function widget($args, $instance) {
		extract($args);
		$title_w = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$text = $instance['text'];
		$text = base64_encode( $text );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title;
?>

<div class="new_cat">
	<?php if ($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<div class="ajax-text-widget" data-text="<?php echo $text; ?>"><div class="ajax-widget-loading"></div></div>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['show_svg'] = strip_tags($new_instance['show_svg']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['text'] = strip_tags( $new_instance['text'] );
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '短代码';
		}
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">单色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_svg'); ?>">彩色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_svg'); ?>" name="<?php echo $this->get_field_name('show_svg'); ?>" type="text" value="<?php echo $instance['show_svg']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">短代码：</label>
		<textarea rows="1" class="widefat" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $instance['text']; ?></textarea>
		<p>比如<code>[random_post]</code>调用随机文章，避免静态缓存</p>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

function ajax_text_widget_init(){
	register_widget('ajax_text_widget');
}
add_action('widgets_init', 'ajax_text_widget_init');

// 分类标签
class widget_cat_tag extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'widget_cat_tag',
			'description' => '分类标签',
			'customize_selective_refresh' => true,
		);
		parent::__construct('widget_cat_tag', '分类标签', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'title_z'       => '',
			'show_icon'     => '',
			'show_svg'      => '',
			'cat_id'        => '',
			'number'        => 20,
			'title'         => '分类标签',
		);
	}

	function widget($args, $instance) {
		extract($args);
		$title_w = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance );
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 20;
	if ( !is_page() && get_post_type() == 'post' ) {
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title;
		$cat_id = strip_tags($instance['cat_id']);
	}
?>

<?php if ( !is_page() && get_post_type() == 'post' ) { ?>
<?php if ($instance['show_icon']) { ?>
	<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
<?php } ?>
<?php if ($instance['show_svg']) { ?>
	<h3 class="widget-title-cat-icon cat-w-icon"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
<?php } ?>

<div class="tagcloud tagcloud-cat">
	<?php 
		$category = get_the_category();
		$all_tags[] = '';
		if ( $instance['cat_id'] ) {
			$be_catid = $instance['cat_id'];
		} else {
			$be_catid = $category[0]->cat_ID;
		}
		$custom_query = new WP_Query ( array( 'cat' => $be_catid, 'posts_per_page' => -1 ) );

		if ( $custom_query->have_posts() ) :
			while ( $custom_query->have_posts() ) : $custom_query->the_post();
				$posttags = get_the_tags();
				if ( $posttags ) {
					foreach( $posttags as $tag ) {
						$all_tags[] = $tag->term_id;
					}
				}
			endwhile;

			$tags_arr = array_unique( $all_tags );
			$tags_str = implode( ",", $tags_arr );

			$args = array(
				'smallest'  => 12,
				'largest'   => 16,
				'unit'      => 'px',
				'number'    => $number,
				//'format'    => 'list',
				'include'   => $tags_str
			);
			wp_tag_cloud( $args );
		else :
			echo 'No found.';
		endif;
		wp_reset_postdata();
	?>
	<div class="clear"></div>
</div>
<?php } ?>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['show_svg'] = strip_tags($new_instance['show_svg']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['cat_id'] = strip_tags($new_instance['cat_id']);
		$instance['number'] = strip_tags($new_instance['number']);
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '分类标签';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '20'));
		$number = strip_tags($instance['number']);
		$cat_id = strip_tags($instance['cat_id']);
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">单色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_svg'); ?>">彩色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_svg'); ?>" name="<?php echo $this->get_field_name('show_svg'); ?>" type="text" value="<?php echo $instance['show_svg']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>">显示数量：</label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('cat_id'); ?>">输入分类ID：</label>
		<textarea style="height:50px;" class="widefat" id="<?php echo $this->get_field_id( 'cat_id' ); ?>" name="<?php echo $this->get_field_name( 'cat_id' ); ?>"><?php echo stripslashes(htmlspecialchars(( $instance['cat_id'] ), ENT_QUOTES)); ?></textarea>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', 'widget_cat_tag_init' );
function widget_cat_tag_init() {
	register_widget( 'widget_cat_tag' );
}

// 本文评论者列表
class be_comment_list extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'be_comment_list',
			'description' => '显示当前文章评论者列表',
			'customize_selective_refresh' => true,
		);
		parent::__construct('be_comment_list', '评论者列表', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'title_z'     => '',
			'show_icon'   => '',
			'show_svg'    => '',
			'title'       => '评论者列表',
		);
	}

	function widget($args, $instance) {
		extract($args);
		if ( is_single() ) {
			$title_w = title_i_w();
			$defaults = $this -> zm_defaults();
			$instance = wp_parse_args( (array) $instance, $defaults );
			$title = apply_filters( 'widget_title', $instance['title'] );
			echo $before_widget;
			if ( ! empty( $title ) )
			echo $before_title . $title_w . $title . $after_title;
		}
?>
<?php if ( is_single() ) { ?>
	<?php if ($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
	<?php } ?>

<ul class="comment-names gaimg names-area">
	<?php if ( have_comments() ) { ?>
		<?php echo get_comment_authors_list(); ?>
		<div class="all-names-list" title="<?php _e( '更多', 'begin' ); ?>"><i class="be be-more"></i></div>
	<?php } else { ?>
		<a class="names-scroll"><?php echo $instance['about_the']; ?></a>
	<?php } ?>
</ul>
<?php } ?>
<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['show_svg'] = strip_tags($new_instance['show_svg']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['about_the'] = $new_instance['about_the'];
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '评论者列表';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('about_the' => '暂无评论，抢沙发？'));
		$about_the = $instance['about_the'];
	?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">单色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_svg'); ?>">彩色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_svg'); ?>" name="<?php echo $this->get_field_name('show_svg'); ?>" type="text" value="<?php echo $instance['show_svg']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('about_the'); ?>">无评论提示：</label>
		<textarea class="widefat" rows="5" cols="20" id="<?php echo $this->get_field_id('about_the'); ?>" name="<?php echo $this->get_field_name('about_the'); ?>"><?php echo $about_the; ?></textarea></p>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', 'be_comment_list_init' );
function be_comment_list_init() {
	register_widget( 'be_comment_list' );
}

// 网站公告
class bulletin_cat extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'bulletin_cat',
			'description' => '显示最新公告',
			'customize_selective_refresh' => true,
		);
		parent::__construct('bulletin_cat', '网站公告', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_thumbs'   => 0,
			'show_time'    => 1,
			'title_z'      => '',
			'show_icon'    => '',
			'show_svg'     => '',
			'cat'          => '',
			'numposts'     => 5,
			'title'        => '网站公告',
		);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$defaults = $this -> zm_defaults();
		$title_w = title_i_w();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		$hideTitle = !empty($instance['hideTitle']) ? true : false;
		$titleUrl = empty($instance['titleUrl']) ? '' : $instance['titleUrl'];
		$newWindow = !empty($instance['newWindow']) ? true : false;
		if ( zm_get_option('more_im') ) {
			$more_i = '<span class="more-i more-im"><span></span><span></span><span></span></span>';
		} else {
			$more_i = '<span class="more-i"><span></span><span></span><span></span></span>';
		}
		echo $before_widget;
		if ($newWindow) $newWindow = "target='_blank'";
			if (!$hideTitle && $title) {
				if ($titleUrl) $title = "<a href='$titleUrl' $newWindow>$title $more_i</a>";
			}
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title; 
?>

	<?php if ($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><a href="<?php echo $titleUrl; ?>" target="_blank"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?><?php more_i(); ?></a></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><a href="<?php echo $titleUrl; ?>" target="_blank"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?><?php more_i(); ?></a></h3>
	<?php } ?>

<div class="notice-cat<?php if ($instance['show_thumbs']) { ?> notice-shuo<?php } ?>">
	<ul>
		<?php
			$args = array(
				'post_type' => 'bulletin',
				'showposts' => $instance['numposts'], 
			);

			if ($instance['cat']) {
				$args = array(
					'showposts' => $instance['numposts'], 
					'tax_query' => array(
						array(
							'taxonomy' => 'notice',
							'terms' => $instance['cat']
						),
					)
				);
			}
		?>
		<?php $be_query = new WP_Query($args); while ($be_query->have_posts()) : $be_query->the_post(); ?>
		<li>
			<?php if ($instance['show_thumbs']) { ?>
				<a href="<?php echo get_category_link( $instance['cat'] ); ?>" rel="bookmark" <?php echo goal(); ?>>
					<span class="meta-author-avatar shuo-avatar">
						<?php 
							if ( zm_get_option( 'cache_avatar' ) ) {
								echo begin_avatar( get_the_author_meta('email'), '96', '', get_the_author() );
							} else {
								echo get_avatar( get_the_author_meta('email'), '96', '', get_the_author() );
							}
						?>
					</span>

					<span class="shuo-w">
						<span class="shuo-entry-meta">
							<span class="shuo-author"><?php the_author(); ?></span>
							<span class="clear"></span>
							<span class="shuo-inf shuo-date"><?php time_ago( $time_type ='post' ); ?></span>
							<span class="shuo-inf shuo-time"><?php echo get_the_time( 'H:i:s' ); ?></span>
						</span>
						<span class="clear"></span>
						<?php 
							$content = get_the_content();
							$content = wp_strip_all_tags(str_replace( array('[',']'),array('<','>'),$content ) );
							echo wp_trim_words( $content, zm_get_option( 'words_n' ), '...' );
						?>
					</span>
				</a>
			<?php } else { ?>
				<?php if ($instance['show_time']) { ?><span class="date-n"><span class="day-n"><?php the_time('m') ?></span>/<span class="month-n"><?php the_time('d') ?></span></span><?php } else { ?><i class="be be-volumedown"></i><?php } ?>
				<?php the_title( sprintf( '<a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a>' ); ?>
			<?php } ?>
		</li>
		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>
	</ul>
</div>

<?php
	echo $after_widget;
}

function update( $new_instance, $old_instance ) {
	$instance = $old_instance;
	$instance = array();
	$instance['show_thumbs'] = !empty($new_instance['show_thumbs']) ? 1 : 0;
	$instance['show_time'] = !empty($new_instance['show_time']) ? 1 : 0;
	$instance['hideTitle'] = !empty($new_instance['hideTitle']) ? 1 : 0;
	$instance['newWindow'] = !empty($new_instance['newWindow']) ? 1 : 0;
	$instance['show_icon'] = strip_tags($new_instance['show_icon']);
	$instance['show_svg'] = strip_tags($new_instance['show_svg']);
	$instance['title'] = strip_tags($new_instance['title']);
	$instance['title_z'] = strip_tags($new_instance['title_z']);
	$instance['titleUrl'] = strip_tags($new_instance['titleUrl']);
	$instance['numposts'] = $new_instance['numposts'];
	$instance['cat'] = $new_instance['cat'];
	return $instance;
}

function form( $instance ) {
	$defaults = $this -> zm_defaults();
	$instance = wp_parse_args( (array) $instance, $defaults );
	$instance = wp_parse_args( (array) $instance, array( 
		'title' => '网站公告',
		'titleUrl' => '',
		'numposts' => 5,
		'cat' => 0));
		$titleUrl = $instance['titleUrl'];
		 ?> 

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('titleUrl'); ?>">标题链接：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('titleUrl'); ?>" name="<?php echo $this->get_field_name('titleUrl'); ?>" type="text" value="<?php echo $titleUrl; ?>" />
		</p>
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" class="checkbox" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
			<label for="<?php echo $this->get_field_id('newWindow'); ?>">在新窗口打开标题链接</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('show_icon'); ?>">单色图标代码：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('show_svg'); ?>">彩色图标代码：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('show_svg'); ?>" name="<?php echo $this->get_field_name('show_svg'); ?>" type="text" value="<?php echo $instance['show_svg']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'numposts' ); ?>">显示数量：</label>
			<input class="number-text" id="<?php echo $this->get_field_id( 'numposts' ); ?>" name="<?php echo $this->get_field_name( 'numposts' ); ?>" type="number" step="1" min="1" value="<?php echo $instance['numposts']; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
			<?php wp_dropdown_categories(array('taxonomy' => 'notice', 'name' => $this->get_field_name('cat'), 'show_option_all' => '全部分类', 'hide_empty'=>0, 'hierarchical'=>1, 'selected'=>$instance['cat'])); ?></label>
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_time') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_time') ); ?>" <?php checked( (bool) $instance["show_time"], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id('show_time') ); ?>">显示时间</label>
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_thumbs') ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>">链接到分类</label>
		</p>
<?php }
}

add_action( 'widgets_init', 'bulletin_cat_init' );
function bulletin_cat_init() {
	register_widget( 'bulletin_cat' );
}

// 网址
class be_site_widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'be_site_widget',
			'description' => '显示网址',
			'customize_selective_refresh' => true,
		);
		parent::__construct('be_site_widget', '网址收藏', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_favicon'    => 1,
			'show_time'    => 1,
			'title_z'      => '',
			'show_icon'    => '',
			'show_svg'     => '',
			'cat'          => '',
			'numposts'     => 5,
			'title'        => '网址收藏',
		);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$defaults = $this -> zm_defaults();
		$title_w = title_i_w();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		$hideTitle = !empty($instance['hideTitle']) ? true : false;
		$titleUrl = empty($instance['titleUrl']) ? '' : $instance['titleUrl'];
		$newWindow = !empty($instance['newWindow']) ? true : false;
		if ( zm_get_option('more_im') ) {
			$more_i = '<span class="more-i more-im"><span></span><span></span><span></span></span>';
		} else {
			$more_i = '<span class="more-i"><span></span><span></span><span></span></span>';
		}
		echo $before_widget;
		if ($newWindow) $newWindow = "target='_blank'";
			if (!$hideTitle && $title) {
				if ($titleUrl) $title = "<a href='$titleUrl' $newWindow>$title $more_i</a>";
			}
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title; 
?>

	<?php if ($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><a href="<?php echo $titleUrl; ?>" target="_blank"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?><?php more_i(); ?></a></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><a href="<?php echo $titleUrl; ?>" target="_blank"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?><?php more_i(); ?></a></h3>
	<?php } ?>

<div class="sites-widget<?php if (!$instance['show_favicon']) { ?> no-sites-cat-ico<?php } ?>">
	<ul>
		<?php
			$args = array(
				'post_type' => 'sites',
				'showposts' => $instance['numposts'], 
			);

			if ($instance['cat']) {
				$args = array(
					'showposts' => $instance['numposts'], 
					'tax_query' => array(
						array(
							'taxonomy' => 'favorites',
							'terms' => $instance['cat']
						),
					)
				);
			}
		?>
		<?php $be_query = new WP_Query($args); while ($be_query->have_posts()) : $be_query->the_post(); ?>
		<li class="srm">
			<?php 
				global $post; 
				$sites_link = get_post_meta( get_the_ID(), 'sites_link', true );
				$sites_url = get_post_meta( get_the_ID(), 'sites_url', true );
			?>

			<?php if ($instance['show_favicon']) { ?>
				<?php if ( get_post_meta( get_the_ID(), 'sites_url', true )) { ?>
					<a href="<?php echo $sites_url; ?>" target="_blank" rel="external nofollow">
						<div class="sites-ico load"><img class="sites-img" src="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=" data-original="<?php echo zm_get_option( 'favicon_api' ); ?><?php echo $sites_url; ?>" alt="<?php the_title(); ?>"></div>
					</a>
				<?php } else { ?>
					<a href="<?php echo $sites_link; ?>" target="_blank" rel="external nofollow">
						<div class="sites-ico load"><img class="sites-img" src="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=" data-original="<?php echo zm_get_option( 'favicon_api' ); ?><?php echo $sites_link; ?>" alt="<?php the_title(); ?>"></div>
					</a>
				<?php } ?>
			<?php } ?>

			<?php if ($instance['show_time']) { ?><span class="date"><span><?php _e( '收录', 'begin' ); ?></span><time datetime="<?php echo get_the_date('Y-m-d'); ?> <?php echo get_the_time('H:i:s'); ?>"><?php the_time('m/d') ?></time></span><?php } ?>
			<?php the_title( sprintf( '<a class="sites-title-w" href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
		</li>
		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>
	</ul>
</div>

<?php
	echo $after_widget;
}

function update( $new_instance, $old_instance ) {
	$instance = $old_instance;
	$instance = array();
	$instance['show_favicon'] = !empty($new_instance['show_favicon']) ? 1 : 0;
	$instance['show_time'] = !empty($new_instance['show_time']) ? 1 : 0;
	$instance['hideTitle'] = !empty($new_instance['hideTitle']) ? 1 : 0;
	$instance['newWindow'] = !empty($new_instance['newWindow']) ? 1 : 0;
	$instance['show_icon'] = strip_tags($new_instance['show_icon']);
	$instance['show_svg'] = strip_tags($new_instance['show_svg']);
	$instance['title'] = strip_tags($new_instance['title']);
	$instance['title_z'] = strip_tags($new_instance['title_z']);
	$instance['titleUrl'] = strip_tags($new_instance['titleUrl']);
	$instance['numposts'] = $new_instance['numposts'];
	$instance['cat'] = $new_instance['cat'];
	return $instance;
}

function form( $instance ) {
	$defaults = $this -> zm_defaults();
	$instance = wp_parse_args( (array) $instance, $defaults );
	$instance = wp_parse_args( (array) $instance, array( 
		'title' => '网址收藏',
		'titleUrl' => '',
		'numposts' => 5,
		'cat' => 0));
		$titleUrl = $instance['titleUrl'];
		 ?> 

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('titleUrl'); ?>">标题链接：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('titleUrl'); ?>" name="<?php echo $this->get_field_name('titleUrl'); ?>" type="text" value="<?php echo $titleUrl; ?>" />
		</p>
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" class="checkbox" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
			<label for="<?php echo $this->get_field_id('newWindow'); ?>">在新窗口打开标题链接</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('show_icon'); ?>">单色图标代码：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('show_svg'); ?>">彩色图标代码：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('show_svg'); ?>" name="<?php echo $this->get_field_name('show_svg'); ?>" type="text" value="<?php echo $instance['show_svg']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'numposts' ); ?>">显示数量：</label>
			<input class="number-text" id="<?php echo $this->get_field_id( 'numposts' ); ?>" name="<?php echo $this->get_field_name( 'numposts' ); ?>" type="number" step="1" min="1" value="<?php echo $instance['numposts']; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
			<?php wp_dropdown_categories(array('taxonomy' => 'favorites', 'name' => $this->get_field_name('cat'), 'show_option_all' => '全部分类', 'hide_empty'=>0, 'hierarchical'=>1, 'selected'=>$instance['cat'])); ?></label>
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_favicon') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_favicon') ); ?>" <?php checked( (bool) $instance["show_favicon"], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id('show_favicon') ); ?>">显示Favicon图标</label>
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_time') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_time') ); ?>" <?php checked( (bool) $instance["show_time"], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id('show_time') ); ?>">显示日期</label>
		</p>
<?php }
}

// RSS
class be_widget_rss extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'be_widget_rss',
			'description' => '调用任意站点RSS和feed文章',
			'customize_selective_refresh' => true,
		);
		parent::__construct('be_widget_rss', 'RSS聚合', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_thumbs'=> 1,
			'title_z'     => '',
			'show_icon'   => '',
			'show_svg'    => '',
			'show_time'   => 1,
			'feed'        => '',
			'numposts'    => 5,
			'title'       => 'RSS聚合',
		);
	}

	function widget($args, $instance) {
		extract($args);
		$title_w = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title;
?>

<?php if ( $instance['show_thumbs'] ) { ?>
<div class="new_cat rss-new">
<?php } else { ?>
<div class="post_cat rss-new-title">
<?php } ?>
	<?php if ( $instance['show_icon'] ) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php
		include_once( ABSPATH . WPINC . '/feed.php' );
		$rss = fetch_feed( $instance['feed'] );
		$maxitems = 0;
		if ( ! is_wp_error( $rss ) ) :
			$maxitems = $rss->get_item_quantity( $instance['numposts'] );
			$rss_items = $rss->get_items( 0, $maxitems );
		endif;
	?>
	<ul>
		<?php if ( $maxitems == 0 ) : ?>
			<li class="srm"><?php _e( '暂无文章', 'begin' ); ?></li>
		<?php else : ?>
			<?php foreach ( $rss_items as $item ) : ?>
				<?php if ( $instance['show_thumbs'] ) { ?>
		  			<li>
						<span class="thumbnail">
							<?php echo rss_thumbnail( $item->get_content(), $item->get_permalink() ); ?>
						</span>
						<span class="new-title">
							<a href="<?php echo esc_url( $item->get_permalink() ); ?>" rel="external nofollow" target="_blank">
								<?php echo esc_html( $item->get_title() ); ?>
							</a>
						</span>
						<span class="date"><time datetime="<?php echo esc_html( date( 'Y-m-d H:i:s', strtotime( $item->get_date() ) ) ); ?>"><?php echo esc_html( date( 'm/d', strtotime( $item->get_date() ) ) ); ?></time></span>
					</li>
				<?php } else { ?>
					<li class="only-title<?php if ( $instance['show_time'] ) { ?> only-title-date<?php } ?>">
					<?php if ( $instance['show_time'] ) { ?><span class="date only-date"><time datetime="<?php echo esc_html( date( 'Y-m-d H:i:s', strtotime( $item->get_date() ) ) ); ?>"><?php echo esc_html( date( 'm/d', strtotime( $item->get_date() ) ) ); ?></time></span><?php } ?>
						<a class="srm get-icon" href="<?php echo esc_url( $item->get_permalink() ); ?>" rel="external nofollow" target="_blank">
							<?php echo esc_html( $item->get_title() ); ?>
						</a>
					</li>
				<?php } ?>
			<?php endforeach; ?>
		<?php endif; ?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['show_thumbs'] = ! empty( $new_instance['show_thumbs'] ) ? 1 : 0;
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['show_svg'] = strip_tags($new_instance['show_svg']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['show_time']   = ! empty( $new_instance['show_time'] ) ? 1 : 0;
		$instance['feed'] = strip_tags( $new_instance['feed'] );
		$instance['numposts'] = $new_instance['numposts'];
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = 'RSS聚合';
		}
		global $wpdb;
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">单色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_svg'); ?>">彩色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_svg'); ?>" name="<?php echo $this->get_field_name('show_svg'); ?>" type="text" value="<?php echo $instance['show_svg']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'feed' ); ?>">输入RSS feed URL：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('feed'); ?>" name="<?php echo $this->get_field_name('feed'); ?>" type="text" value="<?php echo $instance['feed']; ?>" />
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_thumbs' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_thumbs' ) ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id( 'show_thumbs' ) ); ?>">显示缩略图</label>
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_time' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_time' ) ); ?>" <?php checked( (bool) $instance["show_time"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_time') ); ?>">无缩略图显示时间</label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'numposts' ); ?>">显示数量：</label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'numposts' ); ?>" name="<?php echo $this->get_field_name( 'numposts' ); ?>" type="number" step="1" min="1" value="<?php echo $instance['numposts']; ?>" size="3" />
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

add_action( 'widgets_init', 'be_widget_rss_init' );
function be_widget_rss_init() {
	register_widget( 'be_widget_rss' );
}

// 目录小工具
class be_toc_widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'be-toc-widget toc-no',
			'description' => '文章目录，仅适合添加到正文侧边栏中',
			'customize_selective_refresh' => true,
		);
		parent::__construct('be_toc_widget', '文章目录', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'title' => '文章目录',
		);
	}

	function widget($args, $instance) {
		extract($args);
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		if ( ! wp_is_mobile() ) {
			echo $before_widget;
		}
?>
<?php if ( ! wp_is_mobile() && zm_get_option( 'be_toc') ) { ?>
	<div id="toc-widge" class="toc-widge">
		<div class="toc-widge-title"><i class="be be-sort"></i><?php _e( '文章目录', 'begin' ); ?></div>
		<div class="toc-widge-main">
			<?php be_toc(); ?>
		</div>
		<div class="adorn rec-adorn-s"></div>
		<div class="adorn rec-adorn-x"></div>
	</div>
<?php } ?>
<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		} else {
			$title = '文章目录';
		}
		global $wpdb;
?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	<p style="color: #c40000;">警告：禁止加到正文底部小工具中，否则将无法打开文章</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', 'be_toc_widget_init' );
function be_toc_widget_init() {
	register_widget( 'be_toc_widget' );
}

// 联系我们
class be_contact_widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'be-contact-widget',
			'description' => '用于发送邮件',
			'customize_selective_refresh' => true,
		);
		parent::__construct('be_contact_widget', '联系我们', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'title_z'      => '',
			'show_icon'    => '',
			'show_svg'     => '',
			'title' => '联系我们'
		);
	}

	function widget($args, $instance) {
		extract($args);
		$title_w = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title;
?>

<div class="widget-be-contact">
	<?php if ($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<div class="mail-main"><?php echo be_display_contact_form(); ?></div>
	<div class="clear"></div>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['show_svg'] = strip_tags($new_instance['show_svg']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '联系我们';
		}
		global $wpdb;
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">单色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_svg'); ?>">彩色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_svg'); ?>" name="<?php echo $this->get_field_name('show_svg'); ?>" type="text" value="<?php echo $instance['show_svg']; ?>" />
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

add_action( 'widgets_init', 'be_contact_widget_init' );
function be_contact_widget_init() {
	register_widget( 'be_contact_widget' );
}

// 附件信息
class be_attachment_widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'be-attachment-inf',
			'description' => '用于显示当前文章图片附件信息',
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'be_attachment_widget', '图片信息', $widget_ops );
	}

	public function zm_defaults() {
		return array(
			'title'     => '图片信息',
			'model'     => 0,
			'down_btu'  => 0,
		);
	}

	function widget($args, $instance) {
		extract($args);
		$defaults = $this -> zm_defaults();
		$title_w = title_i_w();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);

		if ( $instance['model'] ) {
			$number = 1;
		} else {
			$number = -1;
		}

		$attachments = get_children( array(
			'post_parent'    => get_the_ID(),
			'post_type'      => 'attachment',
			'numberposts'    => $number,
			'post_status'    => 'inherit',
			'post_mime_type' => 'image',
			'order'          => 'ASC',
			'orderby'        => 'menu_order ASC'
		) );

		if ( $attachments ) {
			$title = apply_filters( 'widget_title', $instance['title'] );
			echo $before_widget;
			if ( ! empty( $title ) )
			echo $before_title . $title_w . $title . $after_title;
		}
?>
<?php if ( $attachments ) { ?>
	<div class="be-attachment-inf-box<?php if ( $instance['model'] ) { ?> annex-one<?php } else { ?> annex-mult<?php } ?>">
		<?php 
			foreach ( $attachments as $attachment_id => $attachment ) {
				echo '<div class="be-attachment-item">';
				$image_url = wp_get_attachment_image_url( $attachment_id );
				$image_id = be_get_image_id( $image_url );
				$metadata = wp_get_attachment_metadata( $image_id );
				$image = get_post_meta( get_the_ID(), 'thumbnail', true );
				echo '<div class="annex-img sc">';
				if ( get_post_meta( get_the_ID(), 'thumbnail', true ) ) {
					echo '<img src="' . $image . '" alt="' . $image_id . '" width="320" height="240" />';
				} else {
					echo '<img src="'. get_template_directory_uri() . '/prune.php?src=' . $image_url . '&w=320&h=240&a=' .zm_get_option('crop_top') . '&zc=1" alt="' . $image_id . '" width="320" height="240" />';
				}
				if ( !$instance['down_btu'] || is_user_logged_in() ) {
					echo '<a class="annex-down" href="' . $image_url . '" download="' . $image_url . '"></a>';
				} else {
					echo '<span class="annex-down show-layer"></span>';
				}
				echo '</div>';

				echo '<div class="annex-inf">';
				echo sprintf(__( '编号', 'begin' )) . '<span>' . $image_id . '</span>';
				echo '<div class="img-inc-width">' . sprintf(__( '分辨率', 'begin' )) . '<span>';
				echo $metadata['width'];
				echo '&times;' . $metadata['height'] . '</span></div>';
				echo '<div class="img-inc-size">' . sprintf(__( '大小', 'begin' )) . '<span>' . size_format( filesize( get_attached_file( $image_id ) ), 2 ) . '</span></div>';
				echo '</div>';
				echo '</div>';
			}
		?>
	</div>
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
			$title = '图片信息';
		}
		global $wpdb;
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('down_btu') ); ?>" name="<?php echo esc_attr( $this->get_field_name('down_btu') ); ?>" <?php checked( (bool) $instance["down_btu"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('down_btu') ); ?>">登录可见下载按钮</label>
	</p>

	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('model') ); ?>" name="<?php echo esc_attr( $this->get_field_name('model') ); ?>" <?php checked( (bool) $instance["model"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('model') ); ?>">仅一张图</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', 'be_attachment_widget_init' );
function be_attachment_widget_init() {
	register_widget( 'be_attachment_widget' );
}

// 评论置顶
class be_sticky_comments_widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'be-attachment-inf',
			'description' => '显示置顶的评论',
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'be_sticky_comments_widget', '评论置顶', $widget_ops );
	}

	public function zm_defaults() {
		return array(
			'ellipsis'   => 0,
			'title_z'    => '',
			'show_icon'  => '',
			'number'     => 5,
			'title'      => '评论置顶',
		);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title_w = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( ( array ) $instance, $defaults );
		// 查询
		global $post;
		$query_args = apply_filters( 'sticky_comments_query', array(
			'number'      => $instance['number'],
			'status'      => 'approve',
			'post_status' => 'publish',
			'meta_query'  => array(
				array(
					'key'    => 'comment_sticky',
					'value'  => '1'
				)
			)
		) );

		$query    = new WP_Comment_Query;
		$comments = $query->query( $query_args );
		if ( $comments ) :

		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
		?>

		<div id="message" class="message-widget gaimg<?php if ( ! $instance['ellipsis'] ) { ?> message-item<?php } ?>">
			<?php if ( $instance['show_icon'] ) { ?>
				<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
			<?php } ?>

			<?php
				$output = '<ul id="sticky-comments-widget">';
				if ( $comments ) {
					foreach ( $comments as $comment ) {
						$output .= '<li class="sticky-comments-item load"><a href="' . get_permalink( $comment->comment_post_ID ) . '#anchor-comment-' . $comment->comment_ID . '" title="' . sprintf(__( '发表在', 'begin' )) . '：' . get_the_title( $comment->comment_post_ID ) . '" rel="external nofollow" ' . goal() . '>';
						if ( ! zm_get_option( 'avatar_load' ) ) {
							$output .= get_avatar( $comment->comment_author_email, '96', '', get_comment_author( $comment->comment_ID ) );
						} else {
							$output .= '<img class="avatar photo" src="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=" alt="' . get_comment_author( $comment->comment_ID ) . '" width="96" height="96" data-original="' . preg_replace( array( '/^.+(src=)(\"|\')/i', '/(\"|\')\sclass=(\"|\').+$/i' ), array( '', '' ), get_avatar( $comment->comment_author_email, '96' ) ) . '" />';
						}
						$output .= '<span class="comment_author">';
						$output .= get_comment_author( $comment->comment_ID );
						$output .= '</span>';
						$output .= '<span class="say-comment">';
						$output .= convert_smilies( $comment->comment_content );
						$output .= '</span>';
						$output .= '</a></li>';
					}
				 }
				$output .= '</ul>';
				echo $output;
				endif;
			?>
		</div>
		<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['ellipsis'] = !empty( $new_instance['ellipsis'] ) ? 1 : 0;
		$instance['title_z'] = strip_tags( $new_instance['title_z'] );
		$instance['show_icon'] = strip_tags( $new_instance['show_icon'] );
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = strip_tags( $new_instance['number'] );
		return $instance;
	}

	function form( $instance ) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( ( array ) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '评论置顶';
		}
		$instance = wp_parse_args( ( array ) $instance, array( 'number' => '5' ) );
		$number = strip_tags( $instance['number'] );
?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	<p>
		<label for="<?php echo $this->get_field_id( 'title_z' ); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name( 'title_z' ); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'show_icon' ); ?>">单色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name( 'show_icon' ); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>">显示数量：</label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'ellipsis' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'ellipsis' ) ); ?>" <?php checked( ( bool ) $instance["ellipsis"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id( 'ellipsis' ) ); ?>">简化样式</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id( 'submit' ); ?>" name="<?php echo $this->get_field_name( 'submit' ); ?>" value="1" />
<?php }
}

add_action( 'widgets_init', 'be_sticky_comments_widget_init' );
function be_sticky_comments_widget_init() {
	register_widget( 'be_sticky_comments_widget' );
}

// 专栏
class be_column_cover extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'be_column_cover',
			'description' =>'显示专栏封面列表',
			'customize_selective_refresh' => true,
		);
		parent::__construct('be_column_cover', '专栏', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_icon' => '',
			'title_z'   => '',
			'show_svg'  => '',
			'title'     => '专栏',
			'special_id'     => '',
		);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$defaults = $this -> zm_defaults();
		$title_w = title_i_w();
		$instance = wp_parse_args( ( array ) $instance, $defaults );
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance );
		$hideTitle = ! empty( $instance['hideTitle'] ) ? true : false;
		$titleUrl = empty( $instance['titleUrl'] ) ? '' : $instance['titleUrl'];
		$newWindow = ! empty( $instance['newWindow'] ) ? true : false;
		if ( zm_get_option( 'more_im' ) ) {
			$more_i = '<span class="more-i more-im"><span></span><span></span><span></span></span>';
		} else {
			$more_i = '<span class="more-i"><span></span><span></span><span></span></span>';
		}
		echo $before_widget;
		if ( $newWindow ) $newWindow = "target='_blank'";
			if ( ! $hideTitle && $title ) {
				if ( $titleUrl ) $title = "<a href='$titleUrl' $newWindow>$title $more_i</a>";
			}
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title; 
?>
<div class="new_cat">
	<?php if ( $instance['titleUrl'] ) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon">
			<a href="<?php echo $instance['titleUrl']; ?>" rel="bookmark">
				<i class="t-icon <?php echo $instance['show_icon']; ?>"></i>
				<?php echo $instance['title_z']; ?>
				<?php more_i(); ?>
			</a>
		</h3>
	<?php } else { ?>
		<?php if ( $instance['show_icon'] ) { ?>
			<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
		<?php } ?>
		<?php if ( $instance['show_svg'] ) { ?>
			<h3 class="widget-title-cat-icon cat-w-icon"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
		<?php } ?>
	<?php } ?>
	<ul>
		<?php
			$special = array(
				'taxonomy'      => 'special',
				'show_count'    => 1,
				'orderby'       => 'include',
				'order'         => 'DESC',
				'include'       => $instance['special_id'],
				'hide_empty'    => 0,
				'hierarchical'  => 0
			);
			$cats = get_categories( $special );
		?>
		<?php foreach( $cats as $cat ) : ?>

			<li>
				<span class="thumbnail lazy">
					<?php if ( zm_get_option( 'cat_cover' ) ) { ?>
						<?php if ( zm_get_option( 'lazy_s' ) ) { ?>
							<a class="thumbs-back sc" href="<?php echo get_category_link( $cat->term_id ) ?>" rel="bookmark" <?php echo goal(); ?> data-src="<?php echo cat_cover_url( $cat->term_id ); ?>"></a>
						<?php } else { ?>
							<a class="thumbs-back sc" href="<?php echo get_category_link( $cat->term_id ) ?>" rel="bookmark" <?php echo goal(); ?> style="background-image: url(<?php echo cat_cover_url( $cat->term_id ); ?>);"></a>
						<?php } ?>
					<?php } else { ?>
						<div class="cat-cover-tip">未启用分类封面</div>
					<?php } ?>

				</span>
				<span class="new-title column-title-w">
					<a href="<?php echo get_category_link( $cat->term_id ) ?>" rel="bookmark" <?php echo goal(); ?>>
						<?php echo $cat->name; ?>
						<span class="special-des-w"><?php echo term_description( $cat->term_id ); ?></span>
					</a>
				</span>
				<span class="views"><?php echo $cat->count; ?><?php _e( '篇', 'begin' ); ?></span>
			</li>
		<?php endforeach; ?>
		<?php wp_reset_postdata(); ?>
	</ul>
</div>

<?php
	echo $after_widget;
}

function update( $new_instance, $old_instance ) {
	$instance = $old_instance;
	$instance = array();
	$instance['show_icon'] = strip_tags( $new_instance['show_icon'] );
	$instance['special_id'] = strip_tags( $new_instance['special_id'] );
	$instance['hideTitle'] = ! empty( $new_instance['hideTitle'] ) ? 1 : 0;
	$instance['newWindow'] = ! empty( $new_instance['newWindow'] ) ? 1 : 0;
	$instance['title'] = strip_tags( $new_instance['title'] );
	$instance['title_z'] = strip_tags( $new_instance['title_z'] );
	$instance['titleUrl'] = strip_tags( $new_instance['titleUrl'] );
	return $instance;
}

function form( $instance ) {
	$defaults = $this -> zm_defaults();
	$instance = wp_parse_args( ( array ) $instance, $defaults );
	$instance = wp_parse_args( ( array ) $instance, 
		array( 
			'title' => '专栏',
			'titleUrl' => '',
		)
	);
	$titleUrl = $instance['titleUrl'];
	?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'title_z' ); ?>">图标标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title_z' ); ?>" name="<?php echo $this->get_field_name( 'title_z' ); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'show_icon' ); ?>">单色图标代码：</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'show_icon' ); ?>" name="<?php echo $this->get_field_name( 'show_icon' ); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'show_svg' ); ?>">彩色图标代码：</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'show_svg' ); ?>" name="<?php echo $this->get_field_name( 'show_svg' ); ?>" type="text" value="<?php echo $instance['show_svg']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'titleUrl' ); ?>">标题链接：</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'titleUrl' ); ?>" name="<?php echo $this->get_field_name( 'titleUrl' ); ?>" type="text" value="<?php echo $titleUrl; ?>" />
		</p>
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id( 'newWindow' ); ?>" class="checkbox" name="<?php echo $this->get_field_name( 'newWindow' ); ?>" <?php checked( isset( $instance['newWindow'] ) ? $instance['newWindow'] : 0); ?> />
			<label for="<?php echo $this->get_field_id( 'newWindow' ); ?>">在新窗口打开标题链接</label>
		</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'special_id' ); ?>">输入专栏ID：</label>
		<textarea style="height:80px;" class="widefat" id="<?php echo $this->get_field_id( 'special_id' ); ?>" name="<?php echo $this->get_field_name( 'special_id' ); ?>"><?php echo stripslashes(htmlspecialchars( ( $instance['special_id'] ), ENT_QUOTES ) ); ?></textarea>
		多个ID用英文半角逗号","隔开，按先后排序
	</p>
<?php }
}

add_action( 'widgets_init', 'be_column_cover_init' );
function be_column_cover_init() {
	register_widget( 'be_column_cover' );
}

// Ajax 日历
class be_ajax_calendar extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'ajax_calendar',
			'description' =>'Ajax方式显示更新日历',
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'ajax_calendar', '更新日历', $widget_ops );
	}


	function widget( $args, $instance ) {
		extract($args);
		$defaults = array(
			'title'      => '更新日历',
			'start_year' => '2018',
		);
		$instance = wp_parse_args( ( array ) $instance, $defaults );

		$title = apply_filters( 'widget_title', $instance['title'] );
		$bengali= '0';
		$title_w = title_i_w();
		echo $before_widget;
		if ( $title )
			echo $before_title . $title_w . $title . $after_title;
			echo $this->calender_html($bengali,$instance["start_year"]);
		?>

		<?php
			echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['start_year'] = strip_tags( $new_instance['start_year'] );
		return $instance;
	}

	function form( $instance ) {
		$defaults = array(
			'title'      => '更新日历',
			'start_year' => '2018',
		);

		$instance = wp_parse_args( ( array ) $instance, $defaults );
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'start_year' ); ?>">起始年份</label>
			<input class="widefat" type="number"  id="<?php echo $this->get_field_id( 'start_year' ); ?>" name="<?php echo $this->get_field_name( 'start_year' ); ?>" value="<?php echo $instance['start_year']; ?>" />
		</p>
		<p>如显示的日期不准，尝试将常规设置中的时区，改为上海</p>
	<?php }

	function calender_html( $bengali, $start_year ) {
		global $wp_locale, $m, $monthnum, $year;
		$calender_html = '';
		$calender_html .= '<div class="ajax-ca-box"><div class="select-bg"></div>';
		$calender_html .= '<div class="ajax-ca">';
		$calender_html .= '<div class="select-ca">';
		$calender_html .= '<select name="month" class="my_month s-veil">';

		$month = array();
		for ( $i = 1; $i <= 12; $i++ ) {
			$monthnums = zeroise( $i, 2 );
			$month[$monthnums] = $wp_locale->get_month( $i );
		}

		if ( empty( $m ) || $m == '' ) {
			$nowm = $monthnum;
			$nowyear = $year;
			if( $monthnum==0 || $monthnum==null ){
				$nowm=date( 'm' );
			}
			if( $nowyear==0 || $nowyear==null ){
				$nowyear=date( 'Y' );
			}
		} else {
			$mmm = str_split( $m, 2 );
			$nowm = zeroise( intval( substr( $m, 4, 2 ) ), 2 );
			$nowyear = $mmm['0'] . $mmm['1'];
		}

		foreach ( $month as $k => $mu ) {
			if ( $k == $nowm ) {
				$calender_html.= '<option value="' . $k . '" selected="selected">' . $mu . '</option>';
			} else {
				$calender_html.= '<option value="' . $k . '">' . $mu . '</option>';
			}
		}
		$calender_html.= '</select>';

		$find     = array( "1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
		$taryear  = date( "Y" );
		$yeararr  = array();
		$lassyear = $start_year;
		for ( $nowyearrr = $lassyear; $nowyearrr <= $taryear; $nowyearrr++ ) {
			$yeararr[$nowyearrr] = $nowyearrr;
		}

		$calender_html .= '<select name="Year" class="my_year s-veil">';

		foreach ( $yeararr as $k => $years ) {
			if ( $k == $nowyear ) {
				$calender_html .= '<option value="' . $k . '" selected="selected">' . $years . '' . sprintf( __( '年', 'begin' ) ) . '</option>';
			} else {
				$calender_html .= '<option value="' . $k . '">' . $years . '' . sprintf( __( '年', 'begin' ) ) . '</option>';
			}
		}
		$calender_html .= '</select>';

		$calender_html .= '</div>';
		$calender_html .= '<div class="clear"></div>';
		$calender_html .= '<div class="ajax-calendar">';
		$calender_html .= '<div class="be-calender">';
		$calender_html .= ajax_ac_calendar('', $bengali,false);
		$calender_html .= '</div>';
		$calender_html .= '<div class="clear"></div>';
		$calender_html .= '</div>';
		$calender_html .='</div>';
		$calender_html .='</div>';
		return $calender_html;
	}
}

add_action( 'wp_ajax_ajax_ac', 'ajax_ac_callback' );
add_action( 'wp_ajax_nopriv_ajax_ac', 'ajax_ac_callback' );

function ajax_ac_callback() {
	$ma = $_GET['ma'];
	$bn = $_GET['bn'];  
	ajax_ac_calendar( $bn, $ma );
	die();
}

function ajax_ac_calendar( $bn, $ma = null, $echo = true ) {
	global $wpdb, $m, $monthnum, $year, $wp_locale, $posts;
	if($ma!=null){
		$m=$ma;
	}

	$cache = array();
	$key = md5( get_locale() . $m . $monthnum . $year );

	if ( $cache = wp_cache_get( 'get_calendar', 'calendar' ) ) {
		if ( is_array( $cache ) && isset( $cache[$key] ) ) {
			if ( $echo ) {
				echo apply_filters( 'get_calendar', $cache[$key] );
				return;
			} else {
				return apply_filters( 'get_calendar', $cache[$key] );
			}
		}
	}

	if ( ! is_array( $cache ) )
		$cache = array();

	if ( ! $posts ) {
		$gotsome = $wpdb->get_var( "SELECT 1 as test FROM $wpdb->posts WHERE post_type = 'post' AND post_status = 'publish' LIMIT 1" );
		if ( ! $gotsome ) {
			$cache[$key] = '';
			wp_cache_set( 'get_calendar', $cache, 'calendar' );
			return;
		}
	}

	if ( isset( $_GET['w'] ) )
		$w = '' . intval( $_GET['w'] );

	$week_begins = intval( get_option( 'start_of_week' ) );

	if ( ! empty( $monthnum ) && ! empty( $year ) ) {
		$thismonth = '' . zeroise( intval( $monthnum ), 2);
		$thisyear = '' . intval( $year );
	} elseif ( ! empty( $w ) ) {
		$thisyear = '' . intval( substr( $m, 0, 4 ) );
		$d = ( ($w - 1 ) * 7 ) + 6;
		$thismonth = $wpdb->get_var( "SELECT DATE_FORMAT( ( DATE_ADD( '{$thisyear}0101', INTERVAL $d DAY ) ), '%m' )" );
	} elseif ( ! empty( $m ) ) {
		$thisyear = '' . intval( substr( $m, 0, 4 ) );
		if ( strlen($m) < 6 )
			$thismonth = '01';
		else
			$thismonth = '' . zeroise( intval( substr( $m, 4, 2 ) ), 2 );
	} else {
		$thisyear  = gmdate( 'Y', current_time( 'timestamp' ) );
		$thismonth = gmdate( 'm', current_time( 'timestamp' ) );
	}

	$unixmonth = mktime( 0, 0, 0, $thismonth, 1, $thisyear );
	$last_day  = date( 't', $unixmonth );

	$calendar_output = '<table class="be-calendar-area"><thead><tr>';
	$myweek = array();

	for ( $wdcount = 0; $wdcount <= 6; $wdcount++ ) {
		$myweek[] = $wp_locale->get_weekday( ( $wdcount + $week_begins ) % 7 );
	}

	foreach ( $myweek as $wd ) {
		$day_name = $wp_locale->get_weekday_abbrev($wd);
		$wd = esc_attr( $wd );
		$calendar_output .= "\n\t\t<th class=\"$day_name\" scope=\"col\" title=\"$wd\">$day_name</th>";
	}

	$calendar_output .= '</tr></thead><tbody><tr>';

    // 有文章
	$dayswithposts = get_posts(array(
		'suppress_filters' => false,
		//'post_type'      => 'post',
		'post_type'        => 'post',
		'post_status'      => 'publish',
		'monthnum'         => $thismonth,
		'year'             => $thisyear,
		'numberposts'      => -1,
	));

	if ( strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false || stripos( $_SERVER['HTTP_USER_AGENT'], 'camino' ) !== false || stripos( $_SERVER['HTTP_USER_AGENT'], 'safari' ) !== false )
		$ak_title_separator = "\n";
	else
		$ak_title_separator = ', ';

	$daywithpost = array();
	$ak_titles_for_day = array();
	if ( $dayswithposts ) {
		foreach ( ( array ) $dayswithposts as $ak_post ) {
			$daywith = date( 'd', strtotime( $ak_post->post_date ) );
			if ( ! in_array( $daywith, $daywithpost ) ) {
				$daywithpost[] = $daywith;
			}
			$post_title = esc_attr( get_the_title( $ak_post ) );
			if ( empty( $ak_titles_for_day[$daywith] ) ) // first one
				$ak_titles_for_day[$daywith] = $post_title;
			else
				$ak_titles_for_day[$daywith] .= $ak_title_separator . $post_title;
		}
	}

	//print_r($daywithpost);
	//print_r($ak_titles_for_day);
	$pad = calendar_week_mod(date('w', $unixmonth) - $week_begins);
	if ( 0 != $pad )
		$calendar_output .= "\n\t\t" . '<td colspan="' . esc_attr($pad) . '" class="pad">&nbsp;</td>';
		$daysinmonth = intval(date('t', $unixmonth));
	for ( $day = 1; $day <= $daysinmonth; ++$day ) {

		$dayrrr = array( '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10', '11' => '11', '12' => '12', '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17', '18' => '18', '19' => '19', '20' => '20', '21' => '21', '22' => '22', '23' => '23', '24' => '24', '25' => '25', '26' => '26', '27' => '27', '28' => '28', '29' => '29', '30' => '30', '31' => '31', );
		$addzeor=array( '1' => '01', '2' => '02', '3' => '03', '4' => '04', '5' => '05', '6' => '06', '7' => '07', '8' => '08', '9' => '09', '10' => '10', '11' => '11', '12' => '12', '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17', '18' => '18', '19' => '19', '20' => '20', '21' => '21', '22' => '22', '23' => '23', '24' => '24', '25' => '25', '26' => '26', '27' => '27', '28' => '28', '29' => '29', '30' => '30', '31' => '31', );

		if ( isset( $newrow ) && $newrow )
			$calendar_output .= "\n\t</tr>\n\t<tr>\n\t\t";
		$newrow = false;
		if ( $day == gmdate('j', current_time( 'timestamp' ) ) && $thismonth == gmdate( 'm', current_time( 'timestamp' ) ) && $thisyear == gmdate( 'Y', current_time( 'timestamp' ) ) )
			$calendar_output .= '<td class="today"  >';
		else
		$calendar_output .= '<td>';

		// number
		$number = get_posts(array(
			'post_status' => 'publish',
			'date_query'    => array(
				'year'  => $thisyear,
				'month' => $thismonth,
				'day'   => $day,
			)
		));

		if ( in_array( $day, $daywithpost ) )
			// $calendar_output .= '<a href="' . get_day_link( $thisyear, $thismonth, $day ) . '" title="' . esc_attr( $ak_titles_for_day[$addzeor[$day]] ) . "\">$dayrrr[$day]</a>";
			$calendar_output .= '<a href="' . get_day_link( $thisyear, $thismonth, $day ) . '" data-hover="' . count( $number ) . '&nbsp;' . sprintf( __( '篇', 'begin' ) ) . '">' . $dayrrr[$day] . '</a>';
		else
			$calendar_output .= '<span class="notpost">' . $dayrrr[$day] . '</span>';
		$calendar_output .= '</td>';
		if ( 6 == calendar_week_mod( date( 'w', mktime( 0, 0, 0, $thismonth, $day, $thisyear ) ) - $week_begins ) )
			$newrow = true;
	}

	$pad = 7 - calendar_week_mod( date( 'w', mktime( 0, 0, 0, $thismonth, $day, $thisyear ) ) - $week_begins );
	if ( $pad != 0 && $pad != 7 )
		$calendar_output .= "\n\t\t" . '<td class="pad" colspan="' . esc_attr($pad) . '">&nbsp;</td>';
	$calendar_output .= "\n\t</tr>\n\t</tbody>\n\t</table>";
	$cache[$key] = $calendar_output;
	wp_cache_set( 'get_calendar', $cache, 'calendar' );
	if ( $echo )
		echo apply_filters('get_calendar', $calendar_output);
	else
		return apply_filters('get_calendar', $calendar_output);
}

add_action( 'widgets_init', 'be_ajax_calendar_int' );
function be_ajax_calendar_int() {
	register_widget( 'be_ajax_calendar' );
}

// 计数器
class be_counter_widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'be-counter',
			'description' => '数字滚动增加动画',
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'be_counter', '动画计数器', $widget_ops );
	}

	public function zm_defaults() {
		return array(
			'title' => '',
			'name'  => '网站访问量',
			'value' => '',
			'speed' => '40000',
			'icon'  => 'be be-eye',
			'rec'   => 1,
		);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( ( array ) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
?>

<div class="be-counter-main<?php if ( ! $instance['icon'] ) { ?> be-counter-main-c<?php } ?>">
	<?php if ( $instance['icon'] ) { ?>
		<div class="counters-icon">
			<i class="<?php echo $instance['icon']; ?>"></i>
		</div>
	<?php } ?>

	<div class="counters-item">
		<?php if ( $instance['value'] ) { ?>
			<?php $value = $instance['value']; ?>
		<?php } else { ?>
			<?php $value = all_view(); ?>
		<?php } ?>

		<div class="counters">
			<div class="counter" data-TargetNum="<?php echo $value; ?>" data-Speed="<?php echo $instance['speed']; ?>">0</div><span class="counter-unit">+</span>
		</div>
		<div class="counter-title"><?php echo $instance['name']; ?></div>
	</div>
	<?php if ( $instance['rec'] ) { ?>
		<div class="rec-adorn-s"></div><div class="rec-adorn-x"></div>
	<?php } ?>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['name']  = strip_tags( $new_instance['name'] );
		$instance['value'] = strip_tags( $new_instance['value'] );
		$instance['speed'] = strip_tags( $new_instance['speed'] );
		$instance['icon']  = strip_tags( $new_instance['icon'] );
		$instance['rec']   = ! empty( $new_instance['rec'] ) ? 1 : 0;
		return $instance;
	}
	function form( $instance ) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( ( array ) $instance, $defaults );
		global $wpdb;
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'name' ); ?>">名称</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'name' ); ?>" name="<?php echo $this->get_field_name( 'name' ); ?>" type="text" value="<?php echo $instance['name']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'icon' ); ?>">图标（留空则不显示）</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'icon' ); ?>" name="<?php echo $this->get_field_name( 'icon' ); ?>" type="text" value="<?php echo $instance['icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'value' ); ?>">数值（留空则为网站浏览总数）</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'value' ); ?>" name="<?php echo $this->get_field_name( 'value' ); ?>" type="text" value="<?php echo $instance['value']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'speed' ); ?>">速度（在多少秒内达到设定的数值）</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'speed' ); ?>" name="<?php echo $this->get_field_name( 'speed' ); ?>" type="text" value="<?php echo $instance['speed']; ?>" />
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'rec' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'rec' ) ); ?>" <?php checked( (bool) $instance["rec"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id( 'rec' ) ); ?>">装饰</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id( 'submit' ); ?>" name="<?php echo $this->get_field_name( 'submit' ); ?>" value="1" />
<?php }
}

add_action( 'widgets_init', 'be_counter_widget_init' );
function be_counter_widget_init() {
	register_widget( 'be_counter_widget' );
}

// Ajax随机
class ajax_random_post extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'ajax-random',
			'description' => 'Ajax动态调用随机文章',
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'ajax_random_post', '随便看看', $widget_ops );
	}

	public function zm_defaults() {
		return array(
			'title_z'       => '',
			'show_icon'     => '',
			'show_svg'      => '',
			'title'         => '随便看看',
		);
	}

	function widget($args, $instance) {
		extract($args);
		$title_w = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title;
?>

<?php if ( zm_get_option( 'random_post_img' ) ) { ?>
<div class="new_cat">
<?php } else { ?>
<div class="ajax-random-list">
<?php } ?>
	<?php if ($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<ul>
		<div class="random-btn"><span class="random-btn-ico"><i class="dashicons dashicons-update"></i></span><?php _e( '换一批', 'begin' ); ?></div>
		<div class="random-content">
			<?php if ( zm_get_option( 'random_post_img' ) ) { ?>
				<?php echo random_load(); ?>
			<?php } else { ?>
				<?php echo random_load(); ?>
			<?php } ?>
		</div>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance = array();
			$instance['title_z'] = strip_tags($new_instance['title_z']);
			$instance['show_icon'] = strip_tags($new_instance['show_icon']);
			$instance['show_svg'] = strip_tags($new_instance['show_svg']);
			$instance['title'] = strip_tags( $new_instance['title'] );
			return $instance;
		}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		} else {
			$title = '随便看看';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$number = strip_tags($instance['number']);
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">单色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_svg'); ?>">彩色图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_svg'); ?>" name="<?php echo $this->get_field_name('show_svg'); ?>" type="text" value="<?php echo $instance['show_svg']; ?>" />
	</p>
	<p>更多选项：</p>
	<p>主题选项 → 基本设置 → 侧边栏小工具 → 随便看看小工具</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

function ajax_random_post_init() {
	register_widget( 'ajax_random_post' );
}
add_action( 'widgets_init', 'ajax_random_post_init' );

// 人生倒计时
class Countdown_Be_Widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'countdown_widget',
			'description' => '日、周、月、年倒计时',
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'Countdown_Be_Widget', '人生倒计时', $widget_ops );
	}

	public function zm_defaults() {
		return array(
			'title_z'      => '',
			'show_icon'    => '',
			'show_svg'     => '',
			'title'        => '人生倒计时',
			'square'       => 0,
			'current_date' => 1,
		);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title_w = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( ( array ) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title;
		wp_enqueue_script( 'countdown' );
?>

<div class="be-countdown-widge">
	<?php if( $instance['show_icon'] ) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon da"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if( $instance['show_svg'] ) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon da"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<div class="countdown-count-main<?php if( $instance['square'] ) { ?> square-progress<?php } ?>">
		<?php if( $instance['current_date'] ) { ?>
			<div class="current-date"><span class="current-time"></span></div>
			<script>
				function updateTime() {
					var currentTime = new Date();
					var year = currentTime.getFullYear();
					var month = (currentTime.getMonth() < 9 ? "0": "") + (currentTime.getMonth() + 1);
					var day = (currentTime.getDate() < 10 ? "0": "") + currentTime.getDate();
					var hours = currentTime.getHours();
					var minutes = currentTime.getMinutes();
					var seconds = currentTime.getSeconds();

					hours = (hours < 10 ? "0": "") + hours;
					minutes = (minutes < 10 ? "0": "") + minutes;
					seconds = (seconds < 10 ? "0": "") + seconds;

					var dateTimeString = year + "年" + month + "月" + day + "日 " + hours + ":" + minutes + ":" + seconds;

					var timeElements = document.getElementsByClassName("current-time");
					for (var i = 0; i < timeElements.length; i++) {
						timeElements[i].innerHTML = " " + dateTimeString;
					}
					setTimeout(updateTime, 1000);
				}
				updateTime();
			</script>
		<?php } ?>

		<div class="countdown-area">
			<div class="countdown-item countdown-day" id="dayprogress">
				<div class="countdown-title"><?php _e( '今日', 'begin' ); ?></div>
				<div class="besea">
					<div class="bewave"></div>
					<div class="bewave"></div>
					<div class="bewave"></div>
					<div class="progress-count">00%</div>
				</div>
				<div class="countdown-time"><?php _e( '已', 'begin' ); ?><span>00</span><?php _e( '小时', 'begin' ); ?></div>
			</div>

			<div class="countdown-item countdown-week" id="weekprogress">
				<div class="countdown-title"><?php _e( '本周', 'begin' ); ?></div>
				<div class="besea">
					<div class="bewave"></div>
					<div class="bewave"></div>
					<div class="bewave"></div>
					<div class="progress-count">00%</div>
				</div>
				<div class="countdown-time"><?php _e( '已', 'begin' ); ?><span>00</span><?php _e( '天', 'begin' ); ?></div>
			</div>

			<div class="countdown-item countdown-month" id="monthprogress">
				<div class="countdown-title"><?php _e( '本月', 'begin' ); ?></div>
				<div class="besea">
					<div class="bewave"></div>
					<div class="bewave"></div>
					<div class="bewave"></div>
					<div class="progress-count">00%</div>
				</div>
				<div class="countdown-time"><?php _e( '已', 'begin' ); ?><span>00</span><?php _e( '天', 'begin' ); ?></div>
			</div>

			<div class="countdown-item countdown-year" id="yearprogress">
				<div class="countdown-title"><?php _e( '今年', 'begin' ); ?></div>
				<div class="besea">
					<div class="bewave"></div>
					<div class="bewave"></div>
					<div class="bewave"></div>
					<div class="progress-count">00%</div>
				</div>
				<div class="countdown-time"><?php _e( '已', 'begin' ); ?><span>00</span><?php _e( '月', 'begin' ); ?></div>
			</div>
		</div>
	</div>
	<div class="clear"></div>
</div>
<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['title_z'] = strip_tags( $new_instance['title_z'] );
		$instance['show_icon'] = strip_tags( $new_instance['show_icon'] );
		$instance['show_svg'] = strip_tags( $new_instance['show_svg'] );
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['square']   = ! empty( $new_instance['square'] ) ? 1 : 0;
		$instance['current_date']   = ! empty( $new_instance['current_date'] ) ? 1 : 0;
		return $instance;
	}

	function form( $instance ) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( ( array ) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '人生倒计时';
		}
		global $wpdb;
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'title_z' ); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name( 'title_z' ); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
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
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'current_date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'current_date' ) ); ?>" <?php checked( (bool) $instance["current_date"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id( 'current_date' ) ); ?>">当前时间</label>
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'square' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'square' ) ); ?>" <?php checked( (bool) $instance["square"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id( 'square' ) ); ?>">柱形</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id( 'submit' ); ?>" name="<?php echo $this->get_field_name( 'submit' ); ?>" value="1" />
<?php }
}

add_action( 'widgets_init', 'countdown_widget_init' );
function countdown_widget_init() {
	register_widget( 'Countdown_Be_Widget' );
}

// 书籍封面
class be_widget_novel extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'be_widget_novel',
			'description' => '调用书籍封面',
			'customize_selective_refresh' => true,
		);
		parent::__construct('be_widget_novel', '书籍封面', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_mark'     => 1,
			'cat_id'        => '',
		);
	}

	function widget($args, $instance) {
		extract($args);
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		echo $before_widget;
		$postid = $instance['cat_id'];
?>


<div class="widget-novel-cover">
	<?php
		$terms = get_terms(
			array(
				'taxonomy'   => 'category',
				'include'    => $instance['cat_id'],
				'hide_empty' => false,
				'orderby'   => 'include', 
				'order'     => 'DESC',
			)
		);
		foreach ( $terms as $term ) {
	?>
		<div class="cms-novel-main">
			<div class="boxs1">
				<div class="cms-novel-box" <?php aos_a(); ?>>
					<div class="cms-novel-cove-img-box">
						<?php if ( zm_get_option( 'cat_cover' ) ) { ?>
							<div class="cms-novel-cove-img">
								<a class="thumbs-back sc" href="<?php echo esc_url( get_term_link( $term ) ); ?>" rel="bookmark" <?php echo goal(); ?>>
									<div class="novel-cove-img" data-src="<?php echo cat_cover_url( $term->term_id ); ?>"></div>
								</a>
								<?php if ( $instance['show_mark'] ) { ?>
									<div class="special-mark bz fd"><?php echo be_get_option( 'cms_novel_mark' ); ?></div>
								<?php } ?>
							</div>
						<?php } else { ?>
							<div class="cat-cover-tip">未启用分类封面</div>
						<?php } ?>

					</div>

					<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" rel="bookmark" <?php echo goal(); ?>>
						<div class="novel-cover-des">
							<h4 class="cat-novel-title"><?php echo $term->name; ?></h4>
							<div class="cat-novel-author">
								<?php if ( be_get_option( 'cms_novel_author' ) ) { ?>
									<?php if ( get_option( 'cat-author-' . $term->term_id ) ) { ?>
										<span><?php echo be_get_option('novel_author_t'); ?></span>
										<?php echo get_option( 'cat-author-' . $term->term_id ); ?>
									<?php } ?>
								<?php } ?>
							</div>
							<div class="cms-novel-des">
								<?php 
									if ( get_option( 'cat-message-' . $term->term_id ) ) {
										$description = wpautop( get_option( 'cat-message-' . $term->term_id ) );
										echo mb_strimwidth( $description, 0, 60, '...' ); 
									} else {
										if ( category_description( $term->term_id ) ) {
											$description = category_description( $term->term_id );
											echo mb_strimwidth( $description, 0, 60, '...' ); 
										} else {
											echo '为分类添加描述或附加信息';
										}
									}
								?>
							</div>
						</div>
					</a>
					<div class="clear"></div>
				</div>
			</div>
		</div>
	<?php } ?>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['show_mark'] = !empty($new_instance['show_mark']) ? 1 : 0;
		$instance['cat_id'] = strip_tags($new_instance['cat_id']);
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( ( array ) $instance, $defaults );
		global $wpdb;
?>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_mark') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_mark') ); ?>" <?php checked( (bool) $instance["show_mark"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_mark') ); ?>">显示角标</label>
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'cat_id' ); ?>">输入分类ID：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'cat_id' ); ?>" name="<?php echo $this->get_field_name( 'cat_id' ); ?>" type="text" value="<?php echo $instance['cat_id']; ?>" />
		多个ID用英文半角逗号","隔开，按先后排序
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id( 'submit' ); ?>" name="<?php echo $this->get_field_name( 'submit' ); ?>" value="1" />
<?php }
}

add_action( 'widgets_init', 'be_widget_novel_init' );
function be_widget_novel_init() {
	register_widget( 'be_widget_novel' );
}

// 会员资源
class Be_Assets_Widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'be-assets-widget',
			'description' => '调用指定会员资源文章',
			'customize_selective_refresh' => true,
		);
		parent::__construct('Be_Assets_Widget', '会员资源', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'post_id' => '',
		);
	}

	function widget( $args, $instance ) {
		extract($args);
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( ( array ) $instance, $defaults );
		echo $before_widget;
?>

<div class="assets-widget">
	<?php
		$args = array(
			'post__in'  => explode( ',', $instance['post_id']),
			'orderby'   => 'post__in', 
			'order'     => 'DESC',
			'ignore_sticky_posts' => true,
			);
		$query = new WP_Query( $args );
	?>

	<div class="flexbox-grid">
		<?php
			if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
				require get_template_directory() . '/template/assets.php';
			endwhile;
			endif;
			wp_reset_postdata();
		?>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>
	<div class="clear"></div>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['post_id'] = strip_tags( $new_instance['post_id'] );
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( ( array ) $instance, $defaults );
		global $wpdb;
?>

	<p>
		<label for="<?php echo $this->get_field_id( 'post_id' ); ?>">输入文章ID：</label>
		<textarea style="height:63px;" class="widefat" id="<?php echo $this->get_field_id( 'post_id' ); ?>" name="<?php echo $this->get_field_name( 'post_id' ); ?>"><?php echo stripslashes(htmlspecialchars(( $instance['post_id'] ), ENT_QUOTES)); ?></textarea>
		多个ID用英文半角逗号","隔开，按先后排序
	</p>


	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', 'be_assets_Widget_init' );
function be_assets_Widget_init() {
	register_widget( 'Be_Assets_Widget' );
}

// 音频
class Be_AudioIgniter_Playlist_Widget extends WP_Widget {
	protected $defaults = array(
		'title'    => '',
		'playlist' => '',
	);

	public function __construct() {
		$widget_ops  = array( 'description' => esc_html__( '音频播放器', 'begin' ) );
		$control_ops = array();
		parent::__construct( 'be_audioigniter_playlist', $name = esc_html__( '音频播放器', 'begin' ), $widget_ops, $control_ops );
	}

	public function widget( $args, $instance ) {
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		$playlist = $instance['playlist'];

		echo $args['before_widget'];

		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		$playlist = intval( $playlist );
		$post     = get_post( $playlist );

		if ( ! empty( $post ) && BeAudioIgniter()->post_type === $post->post_type ) {
			echo do_shortcode(
				sprintf( '[ai_playlist id="%s"]',
					$playlist
				)
			);
		}

		echo $args['after_widget'];

	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title']    = sanitize_text_field( $new_instance['title'] );
		$instance['playlist'] = intval( $new_instance['playlist'] );

		return $instance;
	}

	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		$title    = $instance['title'];
		$playlist = $instance['playlist'];
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php esc_html_e( '标题', 'begin' ); ?>：
			</label>
			<input
				type="text"
				class="widefat"
				id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
				value="<?php echo esc_attr( $title ); ?>"
			/>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_name( 'playlist' ) ); ?>">
				<?php esc_html_e( '选择音频', 'begin' ); ?>：
			</label>
			<?php
				$this->dropdown_posts( array(
					'post_type'            => BeAudioIgniter()->post_type,
					'selected'             => $playlist,
					'class'                => 'widefat posts_dropdown',
					'show_option_none'     => '选择音频',
					'select_even_if_empty' => true,
				), $this->get_field_name( 'playlist' ) );
			?>
		</p>
		<?php
	}

	public function dropdown_posts( $args = '', $name = 'post_id' ) {
		$defaults = array(
			'depth'                 => 0,
			'post_parent'           => 0,
			'selected'              => 0,
			'echo'                  => 1,
			//'name'                => 'page_id', // With this line, get_posts() doesn't work properly.
			'id'                    => '',
			'class'                 => '',
			'show_option_none'      => '',
			'show_option_no_change' => '',
			'option_none_value'     => '',
			'post_type'             => 'post',
			'post_status'           => 'publish',
			'suppress_filters'      => false,
			'numberposts'           => -1,
			'select_even_if_empty'  => false, // If no posts are found, an empty <select> will be returned/echoed.
		);

		$r = wp_parse_args( $args, $defaults );
		extract( $r, EXTR_SKIP );

		$hierarchical_post_types = get_post_types( array( 'hierarchical' => true ) );
		if ( in_array( $r['post_type'], $hierarchical_post_types, true ) ) {
			$pages = get_pages( $r );
		} else {
			$pages = get_posts( $r );
		}

		$output = '';
		// Back-compat with old system where both id and name were based on $name argument
		if ( empty( $id ) ) {
			$id = $name;
		}

		if ( ! empty( $pages ) || $select_even_if_empty == true ) {
			$output = "<select name='" . esc_attr( $name ) . "' id='" . esc_attr( $id ) . "' class='" . esc_attr( $class ) . "'>\n";
			if ( $show_option_no_change ) {
				$output .= "\t<option value=\"-1\">$show_option_no_change</option>";
			}
			if ( $show_option_none ) {
				$output .= "\t<option value=\"" . esc_attr( $option_none_value ) . "\">$show_option_none</option>\n";
			}
			if ( ! empty( $pages ) ) {
				$output .= walk_page_dropdown_tree( $pages, $depth, $r );
			}
			$output .= "</select>\n";
		}

		$output = apply_filters( 'audioigniter_playlist_widget_dropdown_posts', $output, $name, $r );

		if ( $echo ) {
			echo $output;
		}

		return $output;
	}
}

function register_beaudioigniter_playlist_widget() {
	register_widget( 'Be_AudioIgniter_Playlist_Widget' );
}
if ( zm_get_option( 'be_audio' ) ) {
add_action( 'widgets_init', 'register_beaudioigniter_playlist_widget' );
}
// 商品信息
class be_tao_all_widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'be_tao_all_widget',
			'description' => '调用指定商品文章',
			'customize_selective_refresh' => true,
		);
		parent::__construct('be_tao_all_widget', '商品信息', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'post_id' => '',
		);
	}

	function widget( $args, $instance ) {
		extract($args);
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( ( array ) $instance, $defaults );
		echo $before_widget;
?>

<div class="tao_widget">
	<?php
		$args = array(
			'post__in'  => explode( ',', $instance['post_id']),
			'post_type' => 'any',
			'orderby'   => 'post__in', 
			'order'     => 'DESC',
			'ignore_sticky_posts' => true, 
		);
		$be_query = new WP_Query($args);
	?>

	<?php while ($be_query->have_posts()) : $be_query->the_post(); ?>
	<div class="boxs1">
		<div class="tao-h">
			<figure class="tao-h-img">
				<?php echo tao_thumbnail(); ?>
				<?php if ( get_post_meta( get_the_ID(), 'tao_img_t', true ) ) : ?>
					<div class="tao-dis"><?php $tao_img_t = get_post_meta( get_the_ID(), 'tao_img_t', true );{ echo $tao_img_t; } ?></div>
				<?php endif; ?>
			</figure>
			<div class="product-box">
				<?php the_title( sprintf( '<h2><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
				<div class="ded">
					<ul class="price">
						<li class="pricex"><strong>￥ <?php $price = get_post_meta( get_the_ID(), 'pricex', true );{ echo $price; } ?>元</strong></li>
						<li class="pricey">
							<?php if ( !get_post_meta( get_the_ID(), 'pricey', true ) && !get_post_meta( get_the_ID(), 'spare_t', true ) ){ ?>
								已售：<?php views_tao(); ?>
							<?php } else { ?>
								<?php if ( get_post_meta( get_the_ID(), 'pricey', true ) ) : ?>
									<del>市场价：<?php $price = get_post_meta( get_the_ID(), 'pricey', true );{ echo $price; } ?>元</del>
								<?php endif; ?>

								<?php if ( get_post_meta( get_the_ID(), 'spare_t', true ) ) : ?>
									<?php $spare_t = get_post_meta( get_the_ID(), 'spare_t', true);{ echo $spare_t; } ?>
								<?php endif; ?>
							<?php } ?>
						</li>
					</ul>
					<div class="go-url">
						<div class="taourl">
							<?php if ( get_post_meta( get_the_ID(), 'taourl', true ) ) : ?>
								<?php
									if ( get_post_meta( get_the_ID(), 'm_taourl', true ) && wp_is_mobile() ) {
										$url = get_post_meta( get_the_ID(), 'm_taourl', true );
									} else {
										$url = get_post_meta( get_the_ID(), 'taourl', true );
									}
									echo '<div class="taourl"><a href=' . $url . ' rel="external nofollow" target="_blank" class="url">购买</a></div>';
								?>
							<?php endif; ?>
						</div>
						<div class="detail"><a href="<?php the_permalink(); ?>" rel="bookmark" <?php echo goal(); ?>>详情</a></div>
					</div>
					<div class="clear"></div>
				</div>
			</div>
		</div>
	</div>
	<?php endwhile;?>
	<?php wp_reset_postdata(); ?>
	<div class="clear"></div>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['post_id'] = strip_tags( $new_instance['post_id'] );
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( ( array ) $instance, $defaults );
		global $wpdb;
?>

	<p>
		<label for="<?php echo $this->get_field_id( 'post_id' ); ?>">选择分类：

		<p>
			<label for="<?php echo $this->get_field_id( 'post_id' ); ?>">输入文章ID：</label>
			<textarea style="height:35px;" class="widefat" id="<?php echo $this->get_field_id( 'post_id' ); ?>" name="<?php echo $this->get_field_name( 'post_id' ); ?>"><?php echo stripslashes(htmlspecialchars(( $instance['post_id'] ), ENT_QUOTES)); ?></textarea>
		</p>
	</p>

	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

// 分类法
if ( is_array( zm_get_option( 'be_type' ) ) && in_array( 'notice', zm_get_option( 'be_type' ) ) ) {
	add_action( 'widgets_init', 'widget_notice_init' );
	function widget_notice_init() {
		register_widget( 'widget_notice' );
	}
}

if ( is_array( zm_get_option( 'be_type' ) ) && in_array( 'img', zm_get_option( 'be_type' ) ) ) {
	add_action( 'widgets_init', 'be_img_widget_init' );
	function be_img_widget_init() {
		register_widget( 'be_img_widget' );
	}
}

if ( is_array( zm_get_option( 'be_type' ) ) && in_array( 'video', zm_get_option( 'be_type' ) ) ) {
	add_action( 'widgets_init', 'be_video_widget_init' );
	function be_video_widget_init() {
		register_widget( 'be_video_widget' );
	}
}

if ( is_array( zm_get_option( 'be_type' ) ) && in_array( 'tao', zm_get_option( 'be_type' ) ) ) {
	add_action( 'widgets_init', 'be_tao_widget_init' );
	function be_tao_widget_init() {
		register_widget( 'be_tao_widget' );
	}

	add_action( 'widgets_init', 'be_tao_all_widget_init' );
	function be_tao_all_widget_init() {
		register_widget( 'be_tao_all_widget' );
	}
}

if ( is_array( zm_get_option( 'be_type' ) ) && in_array( 'show', zm_get_option( 'be_type' ) ) ) {
	add_action( 'widgets_init', 'be_show_widget_init' );
	function be_show_widget_init() {
		register_widget( 'be_show_widget' );
	}
}

if ( is_array( zm_get_option( 'be_type' ) ) && in_array( 'sites', zm_get_option( 'be_type' ) ) ) {
	add_action( 'widgets_init', 'be_site_init' );
	function be_site_init() {
		register_widget( 'be_site_widget' );
	}
}