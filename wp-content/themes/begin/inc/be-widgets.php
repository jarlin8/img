<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// 最新文章
class new_cat extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'new_cat',
			'description' =>'显示全部分类或某个分类的最新文章',
			'customize_selective_refresh' => true,
		);
		parent::__construct('new_cat', '最新文章', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_thumbs'   => 1,
			'show_icon'   => 0,
			'show_time'   => 1,
			'cat_child'   => 1,
			'title_z'     => '',
			'show_svg'     => '',
			'numposts'     => 5,
			'cat'     => '',
			'title'     => '最新文章',
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

<?php if (zm_get_option('cat_icon') && $instance['show_icon']) { ?>
	<?php $q = '&category__and='.$instance['cat']; query_posts($q);?>
	<h3 class="widget-title-icon cat-w-icon da">
		<a href="<?php echo $titleUrl; ?>" rel="bookmark">
			<?php $term_id = get_query_var('cat');if (get_option('zm_taxonomy_icon'.$term_id)) { ?><i class="t-icon <?php echo zm_taxonomy_icon_code(); ?>"></i><?php } ?>
			<?php $term_id = get_query_var('cat');if (get_option('zm_taxonomy_svg'.$term_id)) { ?><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo zm_taxonomy_svg_code(); ?>"></use></svg><?php } ?>
			<?php echo $instance['title_z']; ?><?php more_i(); ?>
		</a>
	</h3>
<?php } ?>

<?php if ($instance['show_thumbs']) { ?>
<div class="new_cat">
<?php } else { ?>
<div class="post_cat">
<?php } ?>
	<ul>
	<?php 
		global $post;
		if ($instance['cat_child']) {
			if ( is_single() ) {
				$q =  new WP_Query(array(
					'ignore_sticky_posts' => 1,
					'showposts' => $instance['numposts'],
					'post__not_in' => array($post->ID),
					'cat' => $instance['cat'],
				));
			} else {
				$q =  new WP_Query(array(
					'ignore_sticky_posts' => 1,
					'showposts' => $instance['numposts'],
					'cat' => $instance['cat'],
				));
			}
		} else {
			if ( is_single() ) {
				$q =  new WP_Query(array(
					'ignore_sticky_posts' => 1,
					'showposts' => $instance['numposts'],
					'post__not_in' => array($post->ID),
					'category__and' => $instance['cat'],
				));
			} else {
				$q =  new WP_Query(array(
					'ignore_sticky_posts' => 1,
					'showposts' => $instance['numposts'],
					'category__and' => $instance['cat'],
				));
			}
		}
	?>
	<?php while ($q->have_posts()) : $q->the_post(); ?>
		<?php if ($instance['show_thumbs']) { ?>
			<li>
				<span class="thumbnail">
					<?php zm_thumbnail(); ?>
				</span>
				<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
				<?php grid_meta(); ?>
				<?php views_span(); ?>
			</li>
		<?php } else { ?>
			<li class="only-title<?php if ($instance['show_time']) { ?> only-title-date<?php } ?>">
				<?php if ($instance['show_time']) { ?><?php grid_meta(); ?><?php } ?>
				<?php the_title( sprintf( '<a class="srm get-icon" href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
			</li>
		<?php } ?>
		<?php endwhile; ?>
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
	$instance['cat_child'] = !empty($new_instance['cat_child']) ? 1 : 0;
	$instance['show_icon'] = !empty($new_instance['show_icon']) ? 1 : 0;
	$instance['show_time'] = !empty($new_instance['show_time']) ? 1 : 0;
	$instance['hideTitle'] = !empty($new_instance['hideTitle']) ? 1 : 0;
	$instance['newWindow'] = !empty($new_instance['newWindow']) ? 1 : 0;
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
		'title' => '最新文章',
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
			<label for="<?php echo $this->get_field_id( 'numposts' ); ?>">显示数量：</label>
			<input class="number-text" id="<?php echo $this->get_field_id( 'numposts' ); ?>" name="<?php echo $this->get_field_name( 'numposts' ); ?>" type="number" step="1" min="1" value="<?php echo $instance['numposts']; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
			<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => '全部分类', 'hide_empty'=>0, 'hierarchical'=>1, 'selected'=>$instance['cat'])); ?></label>
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_thumbs') ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>">显示缩略图</label>
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('cat_child') ); ?>" name="<?php echo esc_attr( $this->get_field_name('cat_child') ); ?>" <?php checked( (bool) $instance["cat_child"], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id('cat_child') ); ?>">显示子分类</label>
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_time') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_time') ); ?>" <?php checked( (bool) $instance["show_time"], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id('show_time') ); ?>">无缩略图显示时间</label>
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_icon') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_icon') ); ?>" <?php checked( (bool) $instance["show_icon"], true ); ?>>
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
			'numposts'     => 4,
			'cat'     => '',
			'title'     => '分类图片',
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

<?php if (zm_get_option('cat_icon') && $instance['show_icon']) { ?>
	<?php $q = '&category__and='.$instance['cat']; query_posts($q);?>
	<h3 class="widget-title-icon cat-w-icon da">
		<a href="<?php echo $titleUrl; ?>" rel="bookmark">
			<?php $term_id = get_query_var('cat');if (get_option('zm_taxonomy_icon'.$term_id)) { ?><i class="t-icon <?php echo zm_taxonomy_icon_code(); ?>"></i><?php } ?>
			<?php $term_id = get_query_var('cat');if (get_option('zm_taxonomy_svg'.$term_id)) { ?><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo zm_taxonomy_svg_code(); ?>"></use></svg><?php } ?>
			<?php echo $instance['title_z']; ?><?php more_i(); ?>
		</a>
	</h3>
<?php } ?>

<div class="picture img_cat bgt">
	<?php 
		global $post;
		if ( is_single() ) {
		$q =  new WP_Query(array(
			'ignore_sticky_posts' => 1,
			'showposts' => $instance['numposts'],
			'post__not_in' => array($post->ID),
			'category__and' => $instance['cat'],
		));
		} else {
		$q =  new WP_Query(array(
			'ignore_sticky_posts' => 1,
			'showposts' => $instance['numposts'],
			'category__and' => $instance['cat'],
		));
	} ?>
	<?php while ($q->have_posts()) : $q->the_post(); ?>
	<span class="img-box">
		<span class="img-x2">
			<span class="insets">
				<span class="img-title"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title( sprintf( '<span class="img-title-t over">'), '</span>' ); ?></a></span>
				<?php zm_thumbnail(); ?>
			</span>
		</span>
	</span>
	<?php endwhile;?>
	<?php wp_reset_query(); ?>
	<div class="clear"></div>
</div>

<?php
	echo $after_widget;
}

function update( $new_instance, $old_instance ) {
	$instance = $old_instance;
	$instance['show_icon'] = !empty($new_instance['show_icon']) ? 1 : 0;
	$instance['hideTitle'] = !empty($new_instance['hideTitle']) ? 1 : 0;
	$instance['newWindow'] = !empty($new_instance['newWindow']) ? 1 : 0;
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
		<h3 class="widget-title-cat-icon cat-w-icon da"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon da"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
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
			<a href="<?php echo get_permalink($comment->comment_post_ID); ?>#anchor-comment-<?php echo $comment->comment_ID; ?>" title="<?php _e( '发表在', 'begin' ); ?>：<?php echo get_the_title($comment->comment_post_ID); ?>" rel="external nofollow">
				<?php if (zm_get_option('cache_avatar')) { ?>
					<?php echo begin_avatar( $comment->comment_author_email, $avatar_size, '', get_comment_author( $comment->comment_ID ) ); ?>
				<?php } else { ?>
					<?php if ( !zm_get_option( 'avatar_load' ) ) {
						echo get_avatar( $comment->comment_author_email, $avatar_size, '', get_comment_author( $comment->comment_ID ) );
					} else {
						echo '<img class="avatar photo" src="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=" alt="'. get_comment_author( $comment->comment_ID ) .'"  width="30" height="30" data-original="' . preg_replace(array('/^.+(src=)(\"|\')/i', '/(\"|\')\sclass=(\"|\').+$/i'), array('', ''), get_avatar( $comment->comment_author_email, $avatar_size, '', get_comment_author( $comment->comment_ID ) )) . '" />';
					} ?>
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
					<span class="comment_author da"><?php echo get_comment_author( $comment->comment_ID ); ?></span>
				<?php } ?>
				<span class="say-comment da"><?php echo convert_smilies( $comment->comment_content ); ?></span>
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
		<label for="<?php echo $this->get_field_id('authornot'); ?>">排除的用户ID：</label>
		<p><input id="<?php echo $this->get_field_id( 'authornot' ); ?>" name="<?php echo $this->get_field_name( 'authornot' ); ?>" type="text" value="<?php echo $authornot; ?>" /></p>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>">显示数量：</label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'ellipsis' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'ellipsis' ) ); ?>" <?php checked( ( bool ) $instance["ellipsis"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id( 'ellipsis' ) ); ?>">简化样式</label>
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
			'title_all'   => 0,
			'mycat'       => 0,
			'title_z'     => '',
			'show_icon'   => '',
			'show_svg'    => '',
			'number'      => 5,
			'days'        => 90,
			'title'       => '热评文章',
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
		<h3 class="widget-title-cat-icon cat-w-icon da"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon da"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
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
							<?php zm_thumbnail(); ?>
						</span>
						<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
						<?php grid_meta(); ?>
						<span class="discuss"><?php comments_number( '', '<i class="be be-speechbubble ri"></i>1 ', '<i class="be be-speechbubble ri"></i>%' ); ?></span>
					</li>
				<?php } else { ?>
					<?php if ( ! $instance['title_all'] ) { ?>
						<li class="title-all-item">
							<span class='li-icon li-icon-<?php echo $i; ?>'><?php echo $i; ?></span>
							<span class="title-all">
								<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
								<span class="title-all-inf">
									<?php grid_meta(); ?>
									<span class="discuss"><?php comments_number( '', '<i class="be be-speechbubble ri"></i>1 ', '<i class="be be-speechbubble ri"></i>%' ); ?></span>
								</span>
							</span>
						</li>
					<?php } else { ?>
						<li class="srm"><span class='li-icon li-icon-<?php echo $i; ?>'><?php echo $i; ?></span><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></li>
					<?php } ?>
				<?php } ?>
			<?php endwhile;?>
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
		$instance['title_all'] = !empty($new_instance['title_all']) ? 1 : 0;
		$instance['mycat'] = !empty($new_instance['mycat']) ? 1 : 0;
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
		<h3 class="widget-title-cat-icon cat-w-icon da"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon da"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
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
		<h3 class="widget-title-cat-icon cat-w-icon da"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon da"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
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
		$query_posts = new WP_Query();
		$query_posts->query($args);
		while ($query_posts->have_posts()) : $query_posts->the_post();
		?>

		<?php if ($instance['show_thumbs']) { ?>
			<li>
				<span class="thumbnail">
					<?php zm_thumbnail(); ?>
				</span>
				<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
				<?php grid_meta(); ?>
				<?php views_span(); ?>
			</li>
		<?php } else { ?>
			<li class="srm the-icon"><?php the_title( sprintf( '<a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?></li>
		<?php } ?>

		<?php endwhile;?>
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
		<h3 class="widget-title-cat-icon cat-w-icon da"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon da"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
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
						<?php zm_thumbnail(); ?>
					</span>
					<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
					<?php grid_meta(); ?>
					<?php views_span(); ?>
				</li>
			<?php } else { ?>
				<li class="srm the-icon"><?php the_title( sprintf( '<a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?></li>
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
						<?php zm_thumbnail(); ?>
					</span>
					<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
					<?php grid_meta(); ?>
					<?php views_span(); ?>
				</li>
			<?php } else { ?>
				<li class="srm"><?php the_title( sprintf( '<a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?></li>
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
		<h3 class="widget-title-cat-icon cat-w-icon da"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon da"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<ul>
		<?php $i = 1; query_posts( array ( 'meta_key' => 'hot', 'showposts' => $number, 'ignore_sticky_posts' => 1 ) ); while ( have_posts() ) : the_post(); ?>

			<?php if ($instance['show_thumbs']) { ?>
				<li>
					<span class="thumbnail">
						<?php zm_thumbnail(); ?>
					</span>
					<span class="hot-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
					<?php views_span(); ?>
					<span class="be-like"><i class="be be-thumbs-up-o ri"></i><?php zm_get_current_count(); ?></span>
				</li>
			<?php } else { ?>
				<li class="srm"><span class="new-title"><span class="li-icon li-icon-<?php echo($i); ?>"><?php echo($i++); ?></span><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span></li>
			<?php } ?>

		<?php endwhile;?>
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
		parent::__construct('readers', '读者墙', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'title_z'       => '',
			'show_icon'     => '',
			'show_svg'      => '',
			'number'        => 6,
			'days'          => 90,
			'title'         => '读者墙',
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
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 6;
		$days = strip_tags($instance['days']) ? absint( $instance['days'] ) : 90;
?>
<?php if ($instance['show_icon']) { ?>
	<h3 class="widget-title-cat-icon cat-w-icon da"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
<?php } ?>
<?php if ($instance['show_svg']) { ?>
	<h3 class="widget-title-cat-icon cat-w-icon da"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
<?php } ?>
<div id="readers_widget" class="readers">
	<?php
		global $wpdb;
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
				AND user_id = '0'
				GROUP BY comment_author_email
				ORDER BY cnt DESC
				LIMIT $number
			");
		}
		$mostactive = '';
		if ( $counts ) {
			wp_cache_set( 'mostactive', $counts );
			foreach ($counts as $count) {
				$c_url = $count->comment_author_url;
				if (zm_get_option('cache_avatar')) {
					$mostactive .= '<div class="readers-avatar"><span>' . '<a href="'. $c_url . '" title="' . $count->comment_author .'  '. $count->cnt . ' 个脚印" target="_blank" rel="external nofollow">' . begin_avatar($count->comment_author_email, 96, '', $count->comment_author) . '</a></span></div>';
				} else {
					$mostactive .= '<div class="readers-avatar"><span>' . '<a href="'. $c_url . '" title="' . $count->comment_author .'  '. $count->cnt . ' 个脚印" target="_blank" rel="external nofollow">' . get_avatar($count->comment_author_email, 96, '', $count->comment_author) . '</a></span></div>';
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
			'rssurl'         => 'http://域名/feed/',
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
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title;
?>

<div id="feed_widget">
	<div class="feed-rss">
		<?php if ( $instance[ 'weixin' ] ) { ?>
			<div class="feed-t weixin">
				<span class="weixin-b">
					<span class="weixin-qr yy fd bk">
						<img src="<?php echo $instance['weixin']; ?>" alt=" weixin"/>
						<span class="clear"></span>
						<span class="arrow-down"></span>
					</span>
					<a><i class="be be-weixin"></i></a>
				</span>
			</div>
		<?php } ?>

		<?php if ( $instance[ 'tsina' ] ) { ?>
			<div class="feed-t tsina"><a title="" href="<?php echo $instance['tsinaurl']; ?>" target="_blank" rel="external nofollow"><i class="<?php echo $instance['tsina']; ?>"></i></a></div>
		<?php } ?>

		<?php if ( $instance[ 'tqq' ] ) { ?>
			<div class="feed-t tqq"><a target=blank rel="external nofollow" href=http://wpa.qq.com/msgrd?V=3&uin=<?php echo $instance['tqqurl']; ?>&Site=QQ&Menu=yes><i class="<?php echo $instance['tqq']; ?>"></i></a></div>
		<?php } ?>

		<?php if ( $instance[ 'rss' ] ) { ?>
			<div class="feed-t feed"><a title="" href="<?php echo $instance['rssurl']; ?>" target="_blank" rel="external nofollow"><i class="<?php echo $instance['rss']; ?>"></i></a></div>
		<?php } ?>
		<div class="clear"></div>
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
		$instance = wp_parse_args((array) $instance, array('rssurl' => 'http://域名/feed/'));
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
			'rssurl'             => 'http://域名/feed/',
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
			<div class="about-the-mixed bgt">
				<img src="<?php echo $instance['about_img']; ?>" alt="name"/>
				<div class="about-name"><?php echo $instance['about_name']; ?></div>
				<?php echo $instance['about_the']; ?>
			</div>
		<?php } else { ?>
			<?php if ( $instance[ 'about_back' ]  ) { ?>
				<div class="author-back" style="background-image: url('<?php echo $instance['about_back']; ?>');"></div>
			<?php } ?>
			<div class="about-main bgt">
				<div class="about-img bgt">
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
						<span class="weixin-b">
							<span class="weixin-qr yy fd bk">
								<img src="<?php echo $instance['weixin']; ?>" alt=" weixin"/>
								<span class="clear"></span>
								<span class="arrow-down"></span>
							</span>
							<a class="dah"><i class="be be-weixin"></i></a>
						</span>
					</div>
				<?php } ?>

				<?php if ( $instance[ 'tqq' ] ) { ?>
					<?php if ( $instance[ 'cqqurl' ]  ) { ?>
						<div class="feed-t tqq"><a class="dah" target=blank rel="external nofollow" href="<?php echo $instance['cqqurl']; ?>" target="_blank" rel="external nofollow"><i class="<?php echo $instance['tqq']; ?>"></i></a></div>
					<?php } else { ?>
						<div class="feed-t tqq"><a class="dah" target=blank rel="external nofollow" href=http://wpa.qq.com/msgrd?V=3&uin=<?php echo $instance['tqqurl']; ?>&Site=QQ&Menu=yes><i class="<?php echo $instance['tqq']; ?>"></i></a></div>
					<?php } ?>
				<?php } ?>

				<?php if ( $instance[ 'tsina' ] ) { ?>
					<div class="feed-t tsina"><a class="dah" title="" href="<?php echo $instance['tsinaurl']; ?>" target="_blank" rel="external nofollow"><i class="<?php echo $instance['tsina']; ?>"></i></a></div>
				<?php } ?>

				<?php if ( $instance[ 'rss' ] ) { ?>
					<div class="feed-t feed"><a class="dah" title="" href="<?php echo $instance['rssurl']; ?>" target="_blank" rel="external nofollow"><i class="<?php echo $instance['rss']; ?>"></i></a></div>
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
		$instance = wp_parse_args((array) $instance, array('rssurl' => 'http://域名/feed/'));
		$rssurl = $instance['rssurl'];
		$instance = wp_parse_args((array) $instance, array('tqq' => 'be be-qq'));
		$tqq = $instance['tqq'];
		$instance = wp_parse_args((array) $instance, array('tqqurl' => '88888'));
		$tqqurl = $instance['tqqurl'];
		$instance = wp_parse_args((array) $instance, array('cqqurl' => ''));
		$cqqurl = $instance['cqqurl'];
?>

	<p>
		<label for="<?php echo $this->get_field_id('about_img'); ?>">头像：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'about_img' ); ?>" name="<?php echo $this->get_field_name( 'about_img' ); ?>" type="text" value="<?php echo $about_img; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('about_back'); ?>">背景图片：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'about_back' ); ?>" name="<?php echo $this->get_field_name( 'about_back' ); ?>" type="text" value="<?php echo $about_back; ?>" />
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
			'cat'    => '',
			'number'     => 4,
			'title'     => '最新图片',
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
	<h3 class="widget-title-cat-icon cat-w-icon da"><a href="<?php echo $titleUrl; ?>" target="_blank"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?><?php more_i(); ?></a></h3>
<?php } ?>
<?php if ($instance['show_svg']) { ?>
	<h3 class="widget-title-cat-icon cat-w-icon da"><a href="<?php echo $titleUrl; ?>" target="_blank"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?><?php more_i(); ?></a></h3>
<?php } ?>
<div class="picture img_widget bgt">
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
 		?>
	<?php $be_query = new WP_Query($args); while ($be_query->have_posts()) : $be_query->the_post(); ?>
	<span class="img-box">
		<span class="img-x2">
			<span class="insets">
				<?php zm_thumbnail(); ?>
			</span>
		</span>
	</span>
	<?php endwhile;?>
	<?php wp_reset_query(); ?>
	<span class="clear"></span>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['hideTitle'] = !empty($new_instance['hideTitle']) ? 1 : 0;
		$instance['newWindow'] = !empty($new_instance['newWindow']) ? 1 : 0;
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['show_svg'] = strip_tags($new_instance['show_svg']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['titleUrl'] = strip_tags($new_instance['titleUrl']);
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
		<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
		<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => '全部分类', 'hide_empty'=>0, 'hierarchical'=>1,	'taxonomy' => 'gallery', 'selected'=>$instance['cat'])); ?></label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>">显示数量：</label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>

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
	<h3 class="widget-title-cat-icon cat-w-icon da"><a href="<?php echo $titleUrl; ?>" target="_blank"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?><?php more_i(); ?></a></h3>
<?php } ?>
<?php if ($instance['show_svg']) { ?>
	<h3 class="widget-title-cat-icon cat-w-icon da"><a href="<?php echo $titleUrl; ?>" target="_blank"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?><?php more_i(); ?></a></h3>
<?php } ?>
<div class="picture video_widget">
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
 		?>
	<?php $be_query = new WP_Query($args); while ($be_query->have_posts()) : $be_query->the_post(); ?>
	<span class="img-box">
		<span class="img-x2">
			<span class="insets">
				<?php videos_thumbnail(); ?>
			</span>
		</span>
	</span>
	<?php endwhile;?>
	<?php wp_reset_query(); ?>
	<span class="clear"></span>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['hideTitle'] = !empty($new_instance['hideTitle']) ? 1 : 0;
		$instance['newWindow'] = !empty($new_instance['newWindow']) ? 1 : 0;
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['show_svg'] = strip_tags($new_instance['show_svg']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['titleUrl'] = strip_tags($new_instance['titleUrl']);
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
		<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
		<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => '全部分类', 'hide_empty'=>0, 'hierarchical'=>1,	'taxonomy' => 'videos', 'selected'=>$instance['cat'])); ?></label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>">显示数量：</label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>

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
	<h3 class="widget-title-cat-icon cat-w-icon da"><a href="<?php echo $titleUrl; ?>" target="_blank"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?><?php more_i(); ?></a></h3>
<?php } ?>
<?php if ($instance['show_svg']) { ?>
	<h3 class="widget-title-cat-icon cat-w-icon da"><a href="<?php echo $titleUrl; ?>" target="_blank"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?><?php more_i(); ?></a></h3>
<?php } ?>
<div class="picture tao_widget bgt">
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
 		?>
	<?php $be_query = new WP_Query($args); while ($be_query->have_posts()) : $be_query->the_post(); ?>
	<span class="img-box">
		<span class="img-x2">
			<span class="insets">
				<?php tao_thumbnail(); ?>
			</span>
		</span>
	</span>
	<?php endwhile;?>
	<?php wp_reset_query(); ?>
	<span class="clear"></span>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['hideTitle'] = !empty($new_instance['hideTitle']) ? 1 : 0;
		$instance['newWindow'] = !empty($new_instance['newWindow']) ? 1 : 0;
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['show_svg'] = strip_tags($new_instance['show_svg']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['titleUrl'] = strip_tags($new_instance['titleUrl']);
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
		<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
		<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => '全部分类', 'hide_empty'=>0, 'hierarchical'=>1,	'taxonomy' => 'taobao', 'selected'=>$instance['cat'])); ?></label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>">显示数量：</label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>

	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

// 多功能小工具
class php_text extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'php_text',
			'description' => '支持PHP、JavaScript、短代码等',
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
		eval('?>' . $text);
		$text = ob_get_contents();
		ob_end_clean();
		$text = do_shortcode($text);

		if (!empty($text)) {
			echo $bare ? '' : $before_widget;
		if ($newWindow) $newWindow = 'target="_blank"';
			if (!$hideTitle && $title) {
				if ($titleUrl) $title = "<a href='$titleUrl' $newWindow>$title $more_i</a>";
				echo $bare ? $title : $before_title . $title_w . $title . $after_title;
			}
			if ($instance['show_icon']) {
				if ($instance['titleUrl']) {
					echo '<h3 class="widget-title-cat-icon cat-w-icon da"><a href=' . $titleUrl . ' ' . $newWindow . '><i class="t-icon ' . $instance['show_icon'] . '"></i>' . $instance['title_z'], more_i() . '</a></h3>';
				} else {
					echo '<h3 class="widget-title-cat-icon cat-w-icon da"><i class="t-icon ' . $instance['show_icon'] . '"></i>' . $instance['title_z'] . '</h3>';
				}
			}

			if ($instance['show_svg']) {
				if ($instance['titleUrl']) {
					echo '<h3 class="widget-title-cat-icon cat-w-icon da"><a href=' . $titleUrl . '><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#'.$instance['show_svg'].'"></use></svg>' . $instance['title_z'], more_i() . '</a></h3>';
				} else {
					echo '<h3 class="widget-title-cat-icon cat-w-icon da"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#'.$instance['show_svg'].'"></use></svg>' . $instance['title_z'] . '</h3>';
				}
			}

			echo $bare ? '' : '<div class="textwidget widget-text">';

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
		<h3 class="widget-title-cat-icon cat-w-icon da"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon da"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<ul>
		<?php
			$be_query = new WP_Query( array ( 'post_status' => 'future', 'order' => 'ASC', 'showposts' => $number, 'ignore_sticky_posts' => '1'));
			if ($be_query->have_posts()) {
				while ($be_query->have_posts()) : $be_query->the_post();
					$do_not_duplicate = get_the_ID(); ?>
					<li><i class="be be-schedule"> <?php the_time('G:i') ?></i><?php the_title(); ?></li>
				<?php endwhile; wp_reset_query();
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
class author_widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'author_widget',
			'description' => '显示所有作者头像',
			'customize_selective_refresh' => true,
		);
		parent::__construct('author_widget', '作者墙', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'exclude_author'   => '',
			'author_numbers'   => '',
			'title'            => '作者墙',
		);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title_w = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title; 
?>
<div class="author_widget_box">
<?php 
	$authors = get_users(
		array(
			'orderby'  => 'post_count',
			'order'    => 'DESC'
		)
	);

	foreach ($authors as $author){
		if (count_user_posts($author->ID) > 0){
			echo '<ul class="xl9">';
			echo '<li class="author_box">';
			echo '<a href="'. get_author_posts_url( $author->ID ) .'" rel="external nofollow" target="_blank" title="'. count_user_posts( $author->ID ) .'篇文章">';
			echo '<span class="load">';
			if (zm_get_option('cache_avatar')) {
				echo begin_avatar( $author->user_email, $size = 96, '', $author->display_name);
			} else {
				if ( !zm_get_option( 'avatar_load' ) ) {
					echo get_avatar( $author->user_email, $size = 96, '', $author->display_name);
				} else {
					echo '<img class="avatar photo" src="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=" alt="'. $author->display_name .'"  width="96" height="96" data-original="' . preg_replace(array('/^.+(src=)(\"|\')/i', '/(\"|\')\sclass=(\"|\').+$/i'), array('', ''), get_avatar( $author->user_email, $size = 96, '', $author->display_name )) . '" />';
				}
			}
			echo '</span>';
			echo '<span class="clear"></span>';
			echo $author->display_name;
			echo '</a>';
			echo '</li>';
			echo '</ul>';
		}
	}
?>
	<div class="clear"></div>
</div>

<?php
	echo $after_widget;
}
function form( $instance ) {
	$defaults = $this -> zm_defaults();
	$instance = wp_parse_args( (array) $instance, $defaults );
	$instance = wp_parse_args( (array) $instance, array( 
		'title' => '作者墙'
		)); 
		?> 
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
			<input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
	<?php
	}
}
function author_widget_init() {
	register_widget( 'author_widget' );
}
add_action( 'widgets_init', 'author_widget_init' );

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
			'show_posts'    => 1,
			'show_comment'  => 1,
			'show_views'    => 1,
			'show_like'     => 1,
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
				<div class="author-avatar-place"></div>
			<?php } ?>
			<h4 class="author-the"><?php the_author(); ?></h4>
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
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
		<div class="author-th">
			<div class="author-description"><?php the_author_meta( 'user_description' ); ?></div>
			<?php if ($instance['show_inf']) { ?>
				<div class="author-th-inf<?php if ( zm_get_option( 'languages_en' ) ) { ?> author-th-inf-en<?php } ?>">
					<?php if ($instance['show_posts'] ) { ?><div class="author-n author-nickname"><?php _e( '文章', 'begin' ); ?><br /><span><?php the_author_posts(); ?></span></div><?php } ?>
					<?php if ($instance['show_comment'] ) { ?><div class="author-n"><?php _e( '评论', 'begin' ); ?><br /><span><?php echo $comment_count;?></span></div><?php } ?>
					<?php if ($instance['show_views'] && zm_get_option('post_views')) { ?><div class="author-n"><?php _e( '浏览', 'begin' ); ?><br /><span><?php author_posts_views(get_the_author_meta('ID'));?></span></div><?php } ?>
					<?php if ($instance['show_like'] && zm_get_option('post_views')) { ?><div class="author-n author-th-views"><?php _e( '点赞', 'begin' ); ?><br /><span><?php like_posts_views(get_the_author_meta('ID'));?></span></div><?php } ?>
				</div>
			<?php } ?>
			<div class="author-m"><a class="bk" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>"><?php _e( '更多文章', 'begin' ); ?></a></div>
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
		$instance['show_inf'] = !empty($new_instance['show_inf']) ? 1 : 0;
		$instance['show_posts'] = !empty($new_instance['show_posts']) ? 1 : 0;
		$instance['show_comment'] = !empty($new_instance['show_comment']) ? 1 : 0;
		$instance['show_views'] = !empty($new_instance['show_views']) ? 1 : 0;
		$instance['show_like'] = !empty($new_instance['show_like']) ? 1 : 0;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['author_back'] = $new_instance['author_back'];
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
		<label for="<?php echo esc_attr( $this->get_field_id('show_like') ); ?>">占赞</label>
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
			'number'      => 5,
			'days'      => 15,
			'title'         => '最近更新过的文章',
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
		$days = strip_tags($instance['days']) ? absint( $instance['days'] ) : 15;
?>

<div class="post_cat">
	<?php if ($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon da"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon da"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<ul>
		<?php if ( function_exists('recently_updated_posts') ) recently_updated_posts( $number,$days ); ?>
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
class ajax_login extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'ajax-login-widget',
			'description' => 'Ajax 登录注册小工具',
			'customize_selective_refresh' => true,
		);
		parent::__construct('ajax_login', '登录注册', $widget_ops);
	}

	function widget($args, $instance) {
		$title_w = title_i_w();
		echo $args['before_widget'];

	?>

	<div class="be-login-widget">
		<?php be_login_reg(); ?>
	</div>

	<?php
		echo $args['after_widget'];
	}
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance = array();
			return $instance;
		}
		function form($instance) {
			global $wpdb;
	?>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

function ajax_login_init() {
	register_widget( 'ajax_login' );
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
		<h3 class="widget-title-cat-icon cat-w-icon da"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon da"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
	<?php } ?>

	<ul>
		<?php 
		$no_comments = false;
		$avatar_size = 64;
		$comments_query = new WP_Comment_Query();
		$comments = $comments_query->query( array_merge( array( 'number' => $number, 'status' => 'approve', 'type' => 'comments', 'post_status' => 'publish', 'post_id' => $instance["pages_id"], 'author__not_in' => explode(',',$instance["authornot"]) ) ) );
		if ( $comments ) : foreach ( $comments as $comment ) : ?>

		<li>
			<a href="<?php echo get_permalink($comment->comment_post_ID); ?>#anchor-comment-<?php echo $comment->comment_ID; ?>" title="<?php _e( '发表在', 'begin' ); ?>：<?php echo get_the_title($comment->comment_post_ID); ?>" rel="external nofollow">
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
		parent::__construct('be_tabs', 'Tab组合小工具', $widget_ops);
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
							<?php zm_thumbnail(); ?>
						</span>
						<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
						<?php grid_meta(); ?>
						<?php views_span(); ?>
					<?php } else { ?>
						<?php the_title( sprintf( '<a class="get-icon" href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
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
							<?php zm_thumbnail(); ?>
						</span>
						<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
						<?php grid_meta(); ?>
						<span class="discuss"><?php comments_number( '', '<i class="be be-speechbubble ri"></i>1 ', '<i class="be be-speechbubble ri"></i>%' ); ?></span>
					<?php } else { ?>
						<a class="get-icon" href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
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
					<a href="<?php echo get_permalink($comment->comment_post_ID); ?>#anchor-comment-<?php echo $comment->comment_ID; ?>" title="<?php _e( '发表在', 'begin' ); ?>：<?php echo get_the_title($comment->comment_post_ID); ?>" rel="external nofollow">
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
		<h3 class="widget-title-cat-icon cat-w-icon da"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon da"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
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
	<?php if ($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon da"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon da"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<ul>
		<?php if (zm_get_option('post_views')) { ?>
		<?php if ($instance['show_thumbs']) { ?>
			<?php get_timespan_most_viewed_img('post',$number,$days, true, true); ?>
		<?php } else { ?>
		    <?php get_timespan_most_viewed('post',$number,$days, true, true); ?>
		<?php } ?>
		<?php wp_reset_query(); ?>
		<?php } else { ?>
			<li>需要启用文章浏览统计</li>
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
		<h3 class="widget-title-cat-icon cat-w-icon da"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon da"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
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
if ( !class_exists('ajax_widget') ) {
	class ajax_widget extends WP_Widget {
		function __construct() {
			// ajax functions
			add_action('wp_ajax_ajax_widget_content', array(&$this, 'ajax_ajax_widget_content'));
			add_action('wp_ajax_nopriv_ajax_widget_content', array(&$this, 'ajax_ajax_widget_content'));
			$widget_ops = array(
				'classname' => 'widget_ajax',
				'description' => '最新文章、热门文章、推荐文章、热门文章等'
			);
			parent::__construct('ajax_widget', 'Ajax组合小工具', $widget_ops);
		}

		function form( $instance ) {
			$instance = wp_parse_args( (array) $instance, array( 
				'tabs' => array('recent' => 1, 'popular' => 1, 'hot' => 1, 'review' => 1, 'comments' => 1, 'recommend' => 1), 
				'tab_order' => array('recent' => 1, 'popular' => 2, 'hot' => 3, 'review' => 4, 'comments' => 5, 'recommend' => 6),
				'allow_pagination' => 1,
				'post_num' => '5', 
				'comment_num' => '12',
				'show_thumb' => '1', 
				'viewe_days' => '90',
				'authornot' => '1',
				'review_days' => '3',
				'like_days' => '90', 
				'pcat' => '', 
			) );
			
			extract($instance);
			?>

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
					<label class="alignleft" style="display: block; width: 50%; margin-bottom: 5px;" for="<?php echo $this->get_field_id("tabs"); ?>_comments">
						<input type="checkbox" class="checkbox ajax_enable_comments" id="<?php echo $this->get_field_id("tabs"); ?>_comments" name="<?php echo $this->get_field_name("tabs"); ?>[comments]" value="1" <?php if (isset($tabs['comments'])) { checked( 1, $tabs['comments'], true ); } ?> />
						最近留言
					</label>
					<label class="alignleft" style="display: block; width: 50%; margin-bottom: 5px;" for="<?php echo $this->get_field_id("tabs"); ?>_recommend">
						<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("tabs"); ?>_recommend" name="<?php echo $this->get_field_name("tabs"); ?>[recommend]" value="1" <?php if (isset($tabs['recommend'])) { checked( 1, $tabs['recommend'], true ); } ?> />
						推荐阅读
					</label>
				</div>
				<div class="clear"></div>

				<h4 class="ajax_tab_order_header">顺序</h4>

				<div class="ajax_tab_order">
					<label class="alignleft" for="<?php echo $this->get_field_id('tab_order'); ?>_recent" style="width: 50%;">
						<input id="<?php echo $this->get_field_id('tab_order'); ?>_recent" name="<?php echo $this->get_field_name('tab_order'); ?>[recent]" type="number" min="1" step="1" value="<?php echo $tab_order['recent']; ?>" style="width: 48px;" />
						最新文章
					</label>
					<label class="alignleft" for="<?php echo $this->get_field_id('tab_order'); ?>_popular" style="width: 50%;">
						<input id="<?php echo $this->get_field_id('tab_order'); ?>_popular" name="<?php echo $this->get_field_name('tab_order'); ?>[popular]" type="number" min="1" step="1" value="<?php echo $tab_order['popular']; ?>" style="width: 48px;" />
						大家喜欢
					</label>
					<label class="alignleft" for="<?php echo $this->get_field_id('tab_order'); ?>_hot" style="width: 50%;">
						<input id="<?php echo $this->get_field_id('tab_order'); ?>_hot" name="<?php echo $this->get_field_name('tab_order'); ?>[hot]" type="number" min="1" step="1" value="<?php echo $tab_order['hot']; ?>" style="width: 48px;" />
						热门文章
					</label>
					<label class="alignleft" for="<?php echo $this->get_field_id('tab_order'); ?>_review" style="width: 50%;">
						<input id="<?php echo $this->get_field_id('tab_order'); ?>_review" name="<?php echo $this->get_field_name('tab_order'); ?>[review]" type="number" min="1" step="1" value="<?php echo $tab_order['review']; ?>" style="width: 48px;" />
						热评文章
					</label>
					<label class="alignleft" for="<?php echo $this->get_field_id('tab_order'); ?>_comments" style="width: 50%;">
						<input id="<?php echo $this->get_field_id('tab_order'); ?>_comments" name="<?php echo $this->get_field_name('tab_order'); ?>[comments]" type="number" min="1" step="1" value="<?php echo $tab_order['comments']; ?>" style="width: 48px;" />
						最近留言
					</label>
					<label class="alignleft" for="<?php echo $this->get_field_id('tab_order'); ?>_recommend" style="width: 50%;">
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
							<label for="<?php echo $this->get_field_id('post_num'); ?>">显示数量
								<input class="number-text" id="<?php echo $this->get_field_id('post_num'); ?>" name="<?php echo $this->get_field_name('post_num'); ?>" type="number" min="1" step="1" value="<?php echo $post_num; ?>" />
							</label>
						</p>

					    <p>
							<label for="<?php echo $this->get_field_id('comment_num'); ?>">
								评论显示数量
								<input class="number-text" type="number" min="1" step="1" id="<?php echo $this->get_field_id('comment_num'); ?>" name="<?php echo $this->get_field_name('comment_num'); ?>" value="<?php echo $comment_num; ?>" />
							</label>
						</p>

						<p>
							<label for="<?php echo $this->get_field_id('like_days'); ?>">大家喜欢时间限定（天）：
								<input class="number-text-d" id="<?php echo $this->get_field_id('like_days'); ?>" name="<?php echo $this->get_field_name('like_days'); ?>" type="number" min="1" step="1" value="<?php echo $like_days; ?>" />
							</label>
						</p>

						<p>
							<label for="<?php echo $this->get_field_id('viewe_days'); ?>">热门文章时间限定（天）：
								<input class="number-text-d" id="<?php echo $this->get_field_id('viewe_days'); ?>" name="<?php echo $this->get_field_name('viewe_days'); ?>" type="number" min="1" step="1" value="<?php echo $viewe_days; ?>" />
							</label>
						</p>
					
						<p>
							<label for="<?php echo $this->get_field_id('review_days'); ?>">热评文章时间限定（月）：
								<input class="number-text-d" id="<?php echo $this->get_field_id('review_days'); ?>" name="<?php echo $this->get_field_name('review_days'); ?>" type="number" min="1" step="1" value="<?php echo $review_days; ?>" />
							</label>
						</p>

						<p>
							<label for="<?php echo $this->get_field_id('authornot'); ?>">评论排除的用户ID：
								<br />
								<input id="<?php echo $this->get_field_id('authornot'); ?>" name="<?php echo $this->get_field_name('authornot'); ?>" type="text" value="<?php echo $authornot; ?>" />
							</label>
						</p>

						<p>
							<label for="<?php echo $this->get_field_id('pcat'); ?>">最新文章排除的分类ID，如：1,3：
								<br />
								<input id="<?php echo $this->get_field_id('pcat'); ?>" name="<?php echo $this->get_field_name('pcat'); ?>" type="text" value="<?php echo $pcat; ?>" />
							</label>
						</p>
					</div>
				</div>
			</div>
			<?php 
		}
		
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['tabs'] = $new_instance['tabs'];
			$instance['tab_order'] = $new_instance['tab_order']; 
			$instance['allow_pagination'] = isset( $new_instance['allow_pagination'] ) ? $new_instance['allow_pagination'] : '';
			$instance['post_num'] = $new_instance['post_num'];
			$instance['comment_num'] =  $new_instance['comment_num'];
			$instance['viewe_days'] =  $new_instance['viewe_days'];
			$instance['review_days'] =  $new_instance['review_days'];
			$instance['like_days'] =  $new_instance['like_days'];
			$instance['show_thumb'] = isset( $new_instance['show_thumb'] ) ? $new_instance['show_thumb'] : '';
			$instance['pcat'] = $new_instance['pcat'];
			$instance['authornot'] = $new_instance['authornot'];
			return $instance;
		}
		function widget( $args, $instance ) {
			extract($args);
			extract($instance);
			$title_w = title_i_w();
			wp_enqueue_script('ajax_widget');
			wp_enqueue_style('ajax_widget');
			if (empty($tabs)) $tabs = array('recent' => 1, 'popular' => 1);
			$tabs_count = count($tabs);
			if ($tabs_count <= 1) {
				$tabs_count = 1;
			} elseif ($tabs_count > 5) {
				$tabs_count = 6;
			}

			$available_tabs = array(
				'recent' => __('最新文章', 'begin'), 
				'popular' => __('大家喜欢', 'begin'), 
				'hot' => __('热门文章', 'begin'), 
				'review' => __('热评文章', 'begin'), 
				'comments' => __('最近留言', 'begin'),
				'recommend' => __('推荐阅读', 'begin')
			);
			array_multisort($tab_order, $available_tabs);
			?>

			<?php echo $before_widget; ?>
			<div class="ajax_widget_content" id="<?php echo $widget_id; ?>_content" data-widget-number="<?php echo esc_attr( $this->number ); ?>">
				<div class="ajax-tabs <?php echo "has-$tabs_count-"; ?>tabs">
					<?php foreach ($available_tabs as $tab => $label) { ?>
						<?php if (!empty($tabs[$tab])): ?>
							<span class="tab_title"><a href="#" title="<?php echo $label; ?>" id="<?php echo $tab; ?>-tab"></a></span>
						<?php endif; ?>
					<?php } ?> 
					<div class="clear"></div>
				</div>
				<!--end .tabs-->
				<div class="clear"></div>

				<div class="new_cat">
					<?php if (!empty($tabs['popular'])): ?>
						<div id="popular-tab-content" class="tab-content">
						</div>
					<?php endif; ?>

					<?php if (!empty($tabs['recent'])): ?>	
						<div id="recent-tab-content" class="tab-content">
						</div>
					<?php endif; ?>

					<?php if (!empty($tabs['recommend'])): ?>
						<div id="recommend-tab-content" class="tab-content">
							<ul></ul>
						</div>
					<?php endif; ?>

					<?php if (!empty($tabs['hot'])): ?>
						<div id="hot-tab-content" class="tab-content">
							<ul></ul>
						</div>
					<?php endif; ?>

					<?php if (!empty($tabs['review'])): ?>
						<div id="review-tab-content" class="tab-content">
							<ul></ul>
						</div>
					<?php endif; ?>

					<?php if (!empty($tabs['comments'])): ?>
						<div id="comments-tab-content" class="tab-content">
							<ul></ul>
						</div>
					<?php endif; ?>

					<div class="clear"></div>
				</div> <!--end .inside -->

				<div class="clear"></div>
			</div><!--end #tabber -->
			<?php 
			unset($instance['tabs'], $instance['tab_order']);
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
			$tab = $_POST['tab'];
			$args = $_POST['args'];
			$number = isset( $_POST['widget_number'] ) ? intval( $_POST['widget_number'] ) : '';
			$page = intval($_POST['page']);
			if ($page < 1)
				$page = 1;

			if ( !is_array( $args ) || empty( $args ) ) { // json_encode() failed
				$ajax_widgets = new ajax_widget();
				$settings = $ajax_widgets->get_settings();

				if ( isset( $settings[ $number ] ) ) {
					$args = $settings[ $number ];
				} else {
					die('<ul><li>请到外观→小工具页面，添加此小工具！</li></ul>');
				}
			}

			// sanitize args
			$post_num = (empty($args['post_num']) ? 5 : intval($args['post_num']));
			if ($post_num > 20 || $post_num < 1) { // max 20 posts
				$post_num = 5;
			}
			$comment_num = (empty($args['comment_num']) ? 5 : intval($args['comment_num']));
			if ($comment_num > 20 || $comment_num < 1) {
				$comment_num = 5;
			}
			$viewe_days = (empty($args['viewe_days']) ? 90 : intval($args['viewe_days']));
			$review_days = (empty($args['review_days']) ? 3 : intval($args['review_days']));
			$like_days = (empty($args['like_days']) ? 90 : intval($args['like_days']));
			$show_thumb = !empty($args['show_thumb']);
			$pcat = strip_tags($args['pcat']);
			$authornot = (empty($args['authornot']) ? 1 : intval($args['authornot']));
			$allow_pagination = !empty($args['allow_pagination']);
	        
			switch ($tab) { 

				/* ---------- Recent Posts ---------- */ 
				case "recent":
					?>
					<ul>
						<h4><?php _e( '最新文章', 'begin' ); ?></h4>
							<?php 
								$recent = new WP_Query(array(
									'posts_per_page' => $post_num,
									'orderby' => 'post_date',
									'order' => 'desc',
									'post_status' => 'publish',
									'category__not_in' => explode(',',$pcat ),
									'paged' => $page,
									'ignore_sticky_posts' => 1,
								));
							?>

							<?php $last_page = $recent->max_num_pages; while ($recent->have_posts()) : $recent->the_post(); ?>
							<li>
								<?php if ( $show_thumb == 1 ) { ?>
									<span class="thumbnail">
										<?php zm_thumbnail(); ?>
									</span>

									<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
									<?php grid_meta(); ?>
									<?php views_span(); ?>
								<?php } else { ?>
									<?php the_title( sprintf( '<a class="get-icon" href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
								<?php } ?>
								<div class="clear"></div>
							</li>
						<?php endwhile; wp_reset_query(); ?>
					</ul>
	                <div class="clear"></div>
					<?php if ($allow_pagination) : ?>
						<?php $this->tab_pagination($page, $last_page); ?>
					<?php endif; ?>
					<?php 
				break;

				/* ---------- Popular Posts ---------- */
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

							$recent = new WP_Query(array(
							'meta_key' => 'zm_like',
							'orderby' => 'meta_value_num',
							'posts_per_page'=>$post_num,
							'date_query' => $like_days,
							'paged' => $page,
							'order' => 'DESC'
							));

							$last_page = $recent->max_num_pages; if ( $recent->have_posts() ) : while ($recent->have_posts()) : $recent->the_post();
						?>
						<li>
							<?php if ( $show_thumb == 1 ) { ?>
								<span class="thumbnail">
									<?php zm_thumbnail(); ?>
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
						<?php wp_reset_query(); ?>
					</ul>

		            <div class="clear"></div>
					<?php if ($allow_pagination) : ?>
						<?php $this->tab_pagination($page, $last_page); ?>
					<?php endif; ?>

					<?php 
				break;

				/* ---------- hot ---------- */
				case "hot":
					?> 
					<ul> 
						<h4><?php _e( '热门文章', 'begin' ); ?></h4>
						<?php if (zm_get_option('post_views')) { ?>
						<?php 
							$date_query = array(
								array(
									'column' => 'post_date',
									'before' => date('Y-m-d H:i',time()),
									'after'  => date('Y-m-d H:i',time()-3600*24*$viewe_days)
								)
							);

							$recent = new WP_Query(array(
							'meta_key' => 'views',
							'orderby' => 'meta_value_num',
							'post_status' => 'publish',
							'posts_per_page'=> $post_num,
							'date_query' => $date_query,
							'paged' => $page,
							'order' => 'DESC'
						));

						$last_page = $recent->max_num_pages; if ( $recent->have_posts() ) : while ($recent->have_posts()) : $recent->the_post();
						?>

						<li>
							<?php if ( $show_thumb == 1 ) { ?>
								<span class="thumbnail">
									<?php zm_thumbnail(); ?>
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
						<?php wp_reset_query(); ?>
						<?php } else { ?>
							<li>需要启用文章浏览统计</a></li>
						<?php } ?>
					</ul>

		            <div class="clear"></div>
					<?php if ($allow_pagination) : ?>
						<?php $this->tab_pagination($page, $last_page); ?>
					<?php endif; ?>

					<?php 
				break;

				/* ---------- Latest recommend ---------- */
				case "recommend":
					?> 
					<ul>
						<h4><?php _e( '推荐阅读', 'begin' ); ?></h4>
						<?php 
							$recent = new WP_Query(array(
							'meta_key' => 'hot',
							'showposts' => $post_num,
							'ignore_sticky_posts' => 1,
							'paged' => $page,
							'order' => 'DESC'
							));

							$last_page = $recent->max_num_pages; if ( $recent->have_posts() ) : while ($recent->have_posts()) : $recent->the_post();
						?>
							<li>
								<?php if ( $show_thumb == 1 ) { ?>
									<span class="thumbnail">
										<?php zm_thumbnail(); ?>
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
						<?php wp_reset_query(); ?>
					</ul>

	                <div class="clear"></div>
					<?php if ($allow_pagination) : ?>
						<?php $this->tab_pagination($page, $last_page); ?>
					<?php endif; ?>

					<?php 
				break;

				/* ---------- Latest comments ---------- */
				case "comments":
					?> 
					<div class="message-tab message-widget gaimg load">
						<ul>
							<h4><?php _e( '最近留言', 'begin' ); ?></h4>
							<?php 
							$no_comments = false;
							$avatar_size = 64;
							$comment_length = 90;
							$comment_args = apply_filters(
								'wpt_comments_tab_args',
								array(
									'type' => 'comments',
									'status' => 'approve'
								)
							);
							$comments_total = new WP_Comment_Query();
							$comments_total_number = $comments_total->query( array_merge( array('count' => 1 ), $comment_args ) );
							$last_page = (int) ceil($comments_total_number / $comment_num);
							$comments_query = new WP_Comment_Query();
							$offset = ($page-1) * $comment_num;
							$comments = $comments_query->query( array_merge( array( 'number' => $comment_num, 'offset' => $offset, 'author__not_in' => explode( ',',$authornot ) ), $comment_args ) );
							if ( $comments ) : foreach ( $comments as $comment ) : ?>

							<li>
								<a href="<?php echo get_permalink($comment->comment_post_ID); ?>#anchor-comment-<?php echo $comment->comment_ID; ?>" title="<?php _e( '发表在', 'begin' ); ?>：<?php echo get_the_title($comment->comment_post_ID); ?>" rel="external nofollow">
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
								<li class="be-none-w"><?php _e( '暂无留言', 'begin' ); ?></li>
								<?php $no_comments = true;
							endif; ?>
						</ul>
					</div>
	                <div class="clear"></div>
					<?php if ($allow_pagination) : ?>
						<?php $this->tab_pagination($page, $last_page); ?>
					<?php endif; ?>
					<?php 
				break;

				/* ---------- Latest review ---------- */
				case "review":
					?>

					<?php
						$review = new WP_Query( array(
							'post_type'           => array( 'post' ),
							'showposts'           => $post_num,
							'ignore_sticky_posts' => true,
							'post_status'         => 'publish',
							'orderby'             => 'comment_count',
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
									<?php zm_thumbnail(); ?>
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
						<?php wp_reset_query(); ?>
					</ul>

	                <div class="clear"></div>
					<?php if ($allow_pagination) : ?>
						<?php $this->tab_pagination($page, $last_page); ?>
					<?php endif; ?>

					<?php 
				break;
			} 
			die();
		}
		function tab_pagination($page, $last_page) {
			?>
			<div class="ajax-pagination">
				<div class="clear"></div>
				<?php if ($page > 1) : ?>
					<a href="#" class="previous"><span><i class="be be-arrowleft"></i><?php _e('上页', 'begin'); ?></span></a>
				<?php endif; ?>
				<?php if ($page != $last_page) : ?>
					<a href="#" class="next"><span><?php _e('下页', 'begin'); ?><i class="be be-arrowright"></i></span></a>
				<?php endif; ?>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
			<input type="hidden" class="page_num" name="page_num" value="<?php echo $page; ?>" />
			<?php 
		}
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
		<h3 class="widget-title-cat-icon cat-w-icon da"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon da"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
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
				<span class="thumbnail"><?php zm_thumbnail(); ?></span>
				<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
				<?php grid_meta(); ?>
				<span class="widget-cat"><i class="be be-sort"></i><?php zm_category(); ?></span>
			<?php } else { ?>
				<?php the_title( sprintf( '<a class="get-icon" href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
			<?php } ?>
			<div class="clear"></div>
		</li>

		<?php endwhile;?>
		<?php wp_reset_query(); ?>
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
					<span class="thumbnail"><?php zm_thumbnail(); ?></span>
					<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
					<span class="s-cat"><?php zm_category(); ?></span>
					<?php grid_meta(); ?>
				<?php } else { ?>
					<?php the_title( sprintf( '<a class="get-icon" href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
				<?php } ?>
				<div class="clear"></div>
			</li>
		<?php endwhile;?>
		<?php wp_reset_query(); ?>
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

<div class="new_cat">
	<?php if ($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon da"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon da"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
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
			<span class="thumbnail"><?php zm_thumbnail(); ?></span>
			<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
			<span class="discuss"><?php comments_number( '', '<i class="be be-speechbubble ri"></i>1 ', '<i class="be be-speechbubble ri"></i>%' ); ?></span>
			<?php grid_meta(); ?>
			<div class="clear"></div>
		</li>

		<?php endwhile;?>
		<?php wp_reset_query(); ?>
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
<?php }
}
add_action( 'widgets_init', 'be_specified_post_init' );
function be_specified_post_init() {
	register_widget( 'be_specified_post' );
}

// 产品
class be_show_widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'be_show_widget',
			'description' => '调用产品文章',
			'customize_selective_refresh' => true,
		);
		parent::__construct('be_show_widget', '最新产品', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'title_z'       => '',
			'show_icon'     => '',
			'show_svg'      => '',
			'cat'           => '',
			'number'        => 4,
			'title'         => '最新产品',
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
		<h3 class="widget-title-cat-icon cat-w-icon da"><a href="<?php echo $titleUrl; ?>" target="_blank"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?><?php more_i(); ?></a></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon da"><a href="<?php echo $titleUrl; ?>" target="_blank"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?><?php more_i(); ?></a></h3>
	<?php } ?>
<div class="picture img_widget">
	<?php
		$args = array(
			'post_type' => 'show',
			'showposts' => $number, 
			);

			if ($instance['cat']) {
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
 		?>
	<?php $be_query = new WP_Query($args); while ($be_query->have_posts()) : $be_query->the_post(); ?>
	<span class="img-box">
		<span class="img-x2">
			<span class="insets">
				<?php zm_thumbnail(); ?>
				<span class="show-t"></span>
			</span>
		</span>
	</span>
	<?php endwhile;?>
	<?php wp_reset_query(); ?>
	<span class="clear"></span>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array();
		$instance['hideTitle'] = !empty($new_instance['hideTitle']) ? 1 : 0;
		$instance['newWindow'] = !empty($new_instance['newWindow']) ? 1 : 0;
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['show_svg'] = strip_tags($new_instance['show_svg']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['titleUrl'] = strip_tags($new_instance['titleUrl']);
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
			$title = '最新产品';
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
		<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
		<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => '全部分类', 'hide_empty'=>0, 'hierarchical'=>1,'taxonomy' => 'products', 'selected'=>$instance['cat'])); ?></label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>">显示数量：</label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>

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
			'show_thumbs'   => 1,
			'number'        => 5,
			'orderby'       => 'ASC',
		);
	}

	function widget($args, $instance) {
		extract($args);
		$title_w = title_i_w();
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( is_single() ) {
			if ( is_single() ) : global $post; $categories = get_the_category(); foreach ($categories as $category) : 
			$title =  $category->name;
			endforeach; endif;
			wp_reset_query();
			echo $before_widget;
			if ( ! empty( $title ) )
			echo $before_title . $title_w . $title . $after_title;
		}
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
		$orderby = strip_tags($instance['orderby']) ? absint( $instance['orderby'] ) : ASC;
?>

<?php if ( is_single() ) { ?>
<?php if ($instance['show_thumbs']) { ?>
<div class="new_cat">
<?php } else { ?>
<div class="post_cat">
<?php } ?>
	<ul>
		<?php 
			global $post;
			$catid = '';
			$cat = get_the_category();
			foreach($cat as $key=>$category){
				$catid = $category->term_id;
			}
			$q = new WP_Query( array(
				'showposts' => $number,
				'post_type' => 'post',
				'cat' => $catid,
				'post__not_in' => array($post->ID),
				'order' => $instance['orderby'],
				'ignore_sticky_posts' => 1
			) );
		?>
		<?php while ($q->have_posts()) : $q->the_post(); ?>
			<?php if ($instance['show_thumbs']) { ?>
				<li class="cat-title">
				<span class="thumbnail">
					<?php zm_thumbnail(); ?>
				</span>
				<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
				<?php grid_meta(); ?>
				<?php views_span(); ?>
			</li>
			<?php } else { ?>
				<li class="srm the-icon"><?php the_title( sprintf( '<a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?></li>
			<?php } ?>

		<?php endwhile; ?>
		<?php wp_reset_query(); ?>
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
		$instance['show_thumbs'] = !empty($new_instance['show_thumbs']) ? 1 : 0;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = strip_tags($new_instance['number']);
		$instance['orderby'] = strip_tags($new_instance['orderby']);
		return $instance;
	}
	function form($instance) {
		global $wpdb;
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$number = strip_tags($instance['number']);
		$instance = wp_parse_args((array) $instance, array('orderby' => 'ASC'));
		$orderby = strip_tags($instance['orderby']);

?>
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
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_thumbs') ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>">显示缩略图</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
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

<div id="slider-widge" class="owl-carousel slider-widge">
	<?php query_posts( array ( 'post__in' => explode(',',$postid), 'ignore_sticky_posts' => 1 ) ); while ( have_posts() ) : the_post(); ?>
	<div class="slider-widge-main">
		<div class="slider-widge-img"><?php zm_widge_thumbnail(); ?></div>
		<?php the_title( sprintf( '<div class="widge-caption"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></div>' ); ?>
	</div>
	<?php endwhile;?>
	<?php wp_reset_query(); ?>
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
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	<p><label for="<?php echo $this->get_field_id( 'post_id' ); ?>">输入文章ID：</label>
	<textarea style="height:200px;" class="widefat" id="<?php echo $this->get_field_id( 'post_id' ); ?>" name="<?php echo $this->get_field_name( 'post_id' ); ?>"><?php echo stripslashes(htmlspecialchars(( $instance['post_id'] ), ENT_QUOTES)); ?></textarea></p>
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
		<h3 class="widget-title-cat-icon cat-w-icon da"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon da"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
	<?php } ?>

	<ul>
		<?php if ($instance['tag_id']) { ?>
			<?php $recent = new WP_Query( array( 'posts_per_page' => $number, 'tag__in' => explode(',', $tag_id)) ); ?>
			<?php while($recent->have_posts()) : $recent->the_post(); ?>
			<li>
				<?php if ($instance['show_thumbs']) { ?>
					<span class="thumbnail">
						<?php zm_thumbnail(); ?>
					</span>
				<?php } ?>
				<?php if ($instance['show_thumbs']) { ?>
					<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
				<?php } else { ?>
					<?php the_title( sprintf( '<a class="get-icon" href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
				<?php } ?>
				<?php if ($instance['show_thumbs']) { ?>
					<?php grid_meta(); ?>
					<?php views_span(); ?>
				<?php } ?>
			</li>
			<?php endwhile; ?>
			<?php wp_reset_query(); ?>
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
		<h3 class="widget-title-cat-icon cat-w-icon da"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon da"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<ul>
		<?php 
		$args = array(
			'ignore_sticky_posts' => 1,
			'post__in'   => explode(',', $id_post)
		);
		query_posts($args)
		 ?>
		<?php while (have_posts()) : the_post(); ?>
		<li>
			<?php if ($instance['show_thumbs']) { ?>
				<span class="thumbnail">
					<?php zm_thumbnail(); ?>
				</span>
			<?php } ?>
			<?php if ($instance['show_thumbs']) { ?>
				<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
			<?php } else { ?>
				<?php the_title( sprintf( '<a class="get-icon" href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
			<?php } ?>
			<?php if ($instance['show_thumbs']) { ?>
				<?php grid_meta(); ?>
				<span class="widget-cat"><i class="be be-sort"></i><?php zm_category(); ?></span>
			<?php } ?>
		</li>
		<?php endwhile; ?>
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
		<label for="<?php echo $this->get_field_id('id_post'); ?>">文章 ID：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'id_post' ); ?>" name="<?php echo $this->get_field_name( 'id_post' ); ?>" type="text" value="<?php echo $id_post; ?>" size="15" />
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

// 折叠菜单小工具
class tree_menu extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'description' => '添加折叠树型菜单。',
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'tree_menu', '折叠菜单', $widget_ops );
	}
	public function widget( $args, $instance ) {
		$title_w = title_i_w();
		// Get menu
		$nav_menu = ! empty( $instance['nav_menu'] ) ? wp_get_nav_menu_object( $instance['nav_menu'] ) : false;

		if ( !$nav_menu )
			return;

		$instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		echo $args['before_widget'];
		if ( !empty($instance['title']) )
			echo $args['before_title'] . $title_w . $instance['title'] . $args['after_title'];
		$nav_menu_args = array(
			'fallback_cb' => '',
			'theme_location'	=> 'widget-tree',
			'menu_class'		=> 'tree-menu',
			'menu'        => $nav_menu
		);
		
		wp_nav_menu( apply_filters( 'widget_nav_menu_args', $nav_menu_args, $nav_menu, $args, $instance ) );
		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		if ( ! empty( $new_instance['title'] ) ) {
			$instance['title'] = sanitize_text_field( $new_instance['title'] );
		}
		if ( ! empty( $new_instance['nav_menu'] ) ) {
			$instance['nav_menu'] = (int) $new_instance['nav_menu'];
		}
		return $instance;
	}

	public function form( $instance ) {
		global $wp_customize;
		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		$nav_menu = isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : '';

		// Get menus
		$menus = wp_get_nav_menus();
		?>
		<p class="nav-menu-widget-no-menus-message" <?php if ( ! empty( $menus ) ) { echo ' style="display:none" '; } ?>>
			<?php
			if ( $wp_customize instanceof WP_Customize_Manager ) {
				$url = 'javascript: wp.customize.panel( "nav_menus" ).focus();';
			} else {
				$url = admin_url( 'nav-menus.php' );
			}
			?>
			<?php echo sprintf( __( 'No menus have been created yet. <a href="%s">Create some</a>.' ), esc_attr( $url ) ); ?>
		</p>
		<div class="nav-menu-widget-form-controls" <?php if ( empty( $menus ) ) { echo ' style="display:none" '; } ?>>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ) ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>"/>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'nav_menu' ); ?>"><?php _e( 'Select Menu:' ); ?></label>
				<select id="<?php echo $this->get_field_id( 'nav_menu' ); ?>" name="<?php echo $this->get_field_name( 'nav_menu' ); ?>">
					<option value="0"><?php _e( '&mdash; Select &mdash;' ); ?></option>
					<?php foreach ( $menus as $menu ) : ?>
						<option value="<?php echo esc_attr( $menu->term_id ); ?>" <?php selected( $nav_menu, $menu->term_id ); ?>>
							<?php echo esc_html( $menu->name ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</p>
		</div>
		<?php
	}
}

add_action( 'widgets_init', 'tree_menu_init' );
function tree_menu_init() {
	register_widget( 'tree_menu' );
}

// 分类模块
class t_img_cat extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 't_img_cat',
			'description' => '显示全部分类或某个分类的文章',
			'customize_selective_refresh' => true,
		);
		parent::__construct('t_img_cat', '分类模块', $widget_ops);
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
			if (!$hideTitle && $title) {
				if ($titleUrl) $title = "<a href='$titleUrl' $newWindow>$title $more_i</a>";
			}
		if ( ! empty( $title ) )
		echo $before_title . $title_w . $title . $after_title; 
?>

<div class="w-img-cat<?php if ($instance['show_time']) { ?> w-img-cat-time<?php } ?>">
	<?php if (zm_get_option('cat_icon') && $instance['show_icon']) { ?>
		<?php $q = '&category__and='.$instance['cat']; query_posts($q);?>
	<h3 class="widget-title-icon cat-w-icon da">
		<a href="<?php echo $titleUrl; ?>" rel="bookmark">
			<?php $term_id = get_query_var('cat');if (get_option('zm_taxonomy_icon'.$term_id)) { ?><i class="t-icon <?php echo zm_taxonomy_icon_code(); ?>"></i><?php } ?>
			<?php $term_id = get_query_var('cat');if (get_option('zm_taxonomy_svg'.$term_id)) { ?><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo zm_taxonomy_svg_code(); ?>"></use></svg><?php } ?>
			<?php echo $instance['title_z']; ?><?php more_i(); ?>
		</a>
	</h3>
	<?php } ?>
		<?php 
			global $post;
			if ( is_single() ) {
			$q =  new WP_Query(array(
				'ignore_sticky_posts' => 1,
				'showposts' => '1',
				'post__not_in' => array($post->ID),
				'category__and' => $instance['cat'],
			));
			} else {
			$q =  new WP_Query(array(
				'ignore_sticky_posts' => 1,
				'showposts' => '1',
				'category__and' => $instance['cat']
			));
		} ?>
		<?php while ($q->have_posts()) : $q->the_post(); ?>
			<figure class="w-thumbnail"><?php zm_long_thumbnail(); ?></figure>
		<?php endwhile; ?>
		<?php wp_reset_query(); ?>
	<ul class="title-img-cat">
		<?php 
			global $post;
			if ( is_single() ) {
			$q =  new WP_Query(array(
				'ignore_sticky_posts' => 1,
				'showposts' => $instance['numposts'],
				'post__not_in' => array($post->ID),
				'category__and' => $instance['cat'],
			));
			} else {
			$q =  new WP_Query(array(
				'ignore_sticky_posts' => 1,
				'showposts' => $instance['numposts'],
				'category__and' => $instance['cat']
			));
		} ?>
		<?php while ($q->have_posts()) : $q->the_post(); ?>
		<?php if ($instance['show_time']) { ?><span class="w-list-date"><?php the_time('m/d'); ?></span><?php } ?>
		<li class="srm the-icon"><?php the_title( sprintf( '<a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?></li>
		<?php endwhile; ?>
		<?php wp_reset_query(); ?>
	</ul>
</div>

<?php
	echo $after_widget;
}

function update( $new_instance, $old_instance ) {
	$instance = $old_instance;
	$instance = array();
	$instance['show_icon'] = !empty($new_instance['show_icon']) ? 1 : 0;
	$instance['show_time'] = !empty($new_instance['show_time']) ? 1 : 0;
	$instance['hideTitle'] = !empty($new_instance['hideTitle']) ? 1 : 0;
	$instance['newWindow'] = !empty($new_instance['newWindow']) ? 1 : 0;
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
		'title' => '分类模块',
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
			'title'         => '',
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
		$postid = $instance['cat_id'];
?>

<div class="widget-cat-cover">
	<?php if ( $instance['show_tags'] ) { ?>
		<?php 
			$args=array( 'include' => $instance['cat_id'] );
			$tags = get_tags($args);
			foreach ($tags as $tag) { 
				$tagid = $tag->term_id; 
				query_posts("tag_id=$tagid");
		?>
		<div class="cat-rec-widget">
			<div class="cat-rec-main">
				<a href="<?php echo get_tag_link($tagid); ?>" rel="bookmark">
				<div class="cat-rec-content ms bk" <?php aos_a(); ?>>
					<div class="cat-rec lazy<?php $term_id = get_query_var( 'cat' );if ( get_option( 'zm_taxonomy_svg'.$term_id ) ) { ?> cat-rec-ico-svg<?php } else { ?> cat-rec-ico-img<?php } ?>">
						<?php if ( $instance['show_cat_ico'] ) { ?>
							<?php if ( zm_get_option( 'cat_icon' ) ) { ?>
								<?php $term_id = get_query_var( 'cat' );if ( !get_option('zm_taxonomy_svg'.$tagid ) ) { ?>
									<?php $term_id = get_query_var( 'cat' );if ( get_option('zm_taxonomy_icon'.$tagid ) ) { ?><i class="cat-rec-icon fd <?php echo zm_taxonomy_icon_code(); ?>"></i><?php } ?>
								<?php } else { ?>
									<?php $term_id = get_query_var( 'cat' );if ( get_option('zm_taxonomy_svg'.$tagid ) ) { ?><svg class="cat-rec-svg bgt fd icon" aria-hidden="true"><use xlink:href="#<?php echo zm_taxonomy_svg_code(); ?>"></use></svg><?php } ?>
								<?php } ?>
							<?php } ?>
						<?php } else { ?>
							<?php if ( zm_get_option( 'cat_cover' ) ) { ?>
								<div class="cat-rec-back" data-src="<?php echo cat_cover_url(); ?>"></div>
							<?php } ?>
						<?php } ?>
					</div>
					<h4 class="cat-rec-title bgt"><?php echo $tag->name; ?></h4>
					<?php if ( get_the_archive_description() ) { ?>
						<?php echo the_archive_description( '<div class="cat-rec-des bgt">', '</div>' ); ?>
					<?php } else { ?>
						<div class="cat-rec-des bgt"><?php _e( '暂无描述', 'begin' ); ?></div>
					<?php } ?>
					<div class="rec-adorn-s"></div><div class="rec-adorn-x"></div>
					<div class="clear"></div>
				</div></a>
			</div>
		</div>
		<?php } wp_reset_query(); ?>
	<?php } else { ?>
		<?php
			$args=array( 'include' => $instance['cat_id'] );
			$cats = get_categories($args);
			foreach ( $cats as $cat ) {
				query_posts( 'cat=' . $cat->cat_ID );
		?>
		<div class="cat-rec-widget">
			<div class="cat-rec-main">
				<a href="<?php echo get_category_link( $cat->cat_ID ); ?>" rel="bookmark">
				<div class="cat-rec-content ms bk" <?php aos_a(); ?>>
					<div class="cat-rec lazy<?php $term_id = get_query_var( 'cat' );if ( get_option( 'zm_taxonomy_svg'.$term_id ) ) { ?> cat-rec-ico-svg<?php } else { ?> cat-rec-ico-img<?php } ?>">
						<?php if ( $instance['show_cat_ico'] ) { ?>
							<?php if ( zm_get_option( 'cat_icon' ) ) { ?>
								<?php $term_id = get_query_var( 'cat' );if ( !get_option('zm_taxonomy_svg'.$term_id ) ) { ?>
									<?php $term_id = get_query_var( 'cat' );if ( get_option('zm_taxonomy_icon'.$term_id ) ) { ?><i class="cat-rec-icon fd <?php echo zm_taxonomy_icon_code(); ?>"></i><?php } ?>
								<?php } else { ?>
									<?php $term_id = get_query_var( 'cat' );if ( get_option('zm_taxonomy_svg'.$term_id ) ) { ?><svg class="cat-rec-svg bgt fd icon" aria-hidden="true"><use xlink:href="#<?php echo zm_taxonomy_svg_code(); ?>"></use></svg><?php } ?>
								<?php } ?>
							<?php } ?>
						<?php } else { ?>
							<?php if ( zm_get_option( 'cat_cover' ) ) { ?>
								<div class="cat-rec-back" data-src="<?php echo cat_cover_url(); ?>"></div>
							<?php } ?>
						<?php } ?>
					</div>
					<h4 class="cat-rec-title bgt"><?php echo $cat->cat_name; ?></h4>
					<?php if ( get_the_archive_description() ) { ?>
						<?php echo the_archive_description( '<div class="cat-rec-des bgt">', '</div>' ); ?>
					<?php } else { ?>
						<div class="cat-rec-des bgt"><?php _e( '暂无描述', 'begin' ); ?></div>
					<?php } ?>
					<div class="rec-adorn-s"></div><div class="rec-adorn-x"></div>
					<div class="clear"></div>
				</div></a>
			</div>
		</div>
		<?php } wp_reset_query(); ?>
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
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['cat_id'] = strip_tags($new_instance['cat_id']);
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '';
		}
		global $wpdb;
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>

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
		<h3 class="widget-title-cat-icon cat-w-icon da"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon da"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
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
			<a href="<?php echo get_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
		<?php endwhile; ?>
		<?php wp_reset_query(); ?>
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
		<h3 class="widget-title-cat-icon cat-w-icon da"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon da"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<ul>
		<?php
		if ( $instance['hide_empty'] ) {
			$hide_empty = 0;
		} else {
			$hide_empty = 1;
		}
			$args=array(
				'exclude' => $e_cat,
				'taxonomy' => array( 'category', 'taobao', 'gallery', 'videos', 'products', 'notice' ),
				'hide_empty' => $hide_empty
			);
			$cats = get_categories($args);
			foreach ( $cats as $cat ) {
			query_posts( 'cat=' . $cat->cat_ID );
		?>
		<li>
			<a href="<?php echo get_category_link($cat->cat_ID);?>" rel="bookmark">
				<?php if (zm_get_option('cat_icon') && $instance['show_icon_no']) { ?>
					<?php $term_id = get_query_var('cat');if (get_option('zm_taxonomy_icon'.$term_id)) { ?><i class="widget-icon <?php echo zm_taxonomy_icon_code(); ?>"></i><?php } ?>
					<?php $term_id = get_query_var('cat');if (get_option('zm_taxonomy_svg'.$term_id)) { ?><svg class="widget-icon icon" aria-hidden="true"><use xlink:href="#<?php echo zm_taxonomy_svg_code(); ?>"></use></svg><?php } ?>
				<?php } ?>
				<?php echo $cat->cat_name; ?>
			</a>
		</li>
		<?php } ?>
		<?php wp_reset_query(); ?>
	</ul>
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

// 折叠分类目录
class widget_tree_cat extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'widget_tree_cat',
			'description' => '以折叠目录树方式显示子分类目录',
			'customize_selective_refresh' => true,
		);
		parent::__construct('widget_tree_cat', '折叠分类', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'title_z'      => '',
			'show_icon'    => '',
			'show_tree'    => '1',
			'show_svg'     => '',
			'count'        => '',
			'e_cat'        => '',
			'hide_empty'     => '',
			'title'        => '折叠分类',
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
		<h3 class="widget-title-cat-icon cat-w-icon da"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon da"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
	<?php } ?>

<ul class="<?php if ($instance['show_tree']) { ?>tree_cat_enable<?php } else { ?>tree_cat<?php } ?><?php if ( ! $instance['count'] ) { ?> cat_count_no<?php } ?>">
	<?php
		if ( $instance['hide_empty'] ) {
			$hide_empty = 0;
		} else {
			$hide_empty = 1;
		}
		$args = wp_list_categories( array(
			'exclude'            => $e_cat,
			'hide_empty'         => $hide_empty,
			'echo'               => false,
			'show_count'         => $c,
			'use_desc_for_title' => 0,
			'include'            => '',
			'title_li'           => ''
		) );

		$args= preg_replace( '~\((\d+)\)(?=\s*+<)~', '$1', $args );
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
		$instance['show_tree'] = ! empty( $new_instance['show_tree'] ) ? 1 : 0;
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '折叠分类';
		}
		global $wpdb;
		$e_cat = strip_tags($instance['e_cat']);
		$count = isset( $instance['count'] ) ? (bool) $instance['count'] : false;
		$show_tree = isset( $instance['show_tree'] ) ? (bool) $instance['show_tree'] : false;
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
		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'show_tree' ); ?>" name="<?php echo $this->get_field_name( 'show_tree' ); ?>"<?php checked( $show_tree ); ?> />
		<label for="<?php echo $this->get_field_id( 'show_tree' ); ?>">折叠显示</label><br />
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

add_action( 'widgets_init', 'widget_tree_cat_init' );
function widget_tree_cat_init() {
	register_widget( 'widget_tree_cat' );
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
		<h3 class="widget-title-cat-icon cat-w-icon da"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon da"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
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
			<?php the_title( sprintf( '<li><a href="%s" rel="bookmark"><i class="be be-volumedown ri"></i>', esc_url( get_permalink() ) ), '</a></li>' ); ?>
			<?php endwhile;?>
			<?php wp_reset_query(); ?>
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
		<h3 class="widget-title-cat-icon cat-w-icon da"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon da"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
	<?php } ?>

	<?php if ($instance['pages_id']) { ?>
		<?php 
			global $post;
			$posts = get_posts( array( 'post_type' => 'any', 'orderby' => 'menu_order', 'include' => $instance['pages_id']) ); if ($posts) : foreach( $posts as $post ) : setup_postdata( $post );
		?>
		<div class="cover4x">
			<div class="cat-cover-main">
				<div class="cat-cover-img thumbs-b lazy">
					<?php $image = get_post_meta(get_the_ID(), 'thumbnail', true); ?>
					<a class="thumbs-back" href="<?php echo get_permalink($post->ID); ?>" rel="bookmark" data-src="<?php echo $image; ?>">
						<div class="special-mark fd"><?php _e( '专题', 'begin' ); ?></div>
						<div class="cover-des-box">
							<?php 
								$special = get_post_meta(get_the_ID(), 'special', true);
								echo '<div class="special-count bgt fd">';
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
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

add_action( 'widgets_init', 'widget_special_init' );
function widget_special_init() {
	register_widget( 'widget_special' );
}

// 最近浏览的文章
class be_recently_viewed extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'be_recently_viewed',
			'description' => '最近浏览的文章',
			'customize_selective_refresh' => true,
		);
		parent::__construct('be_recently_viewed', '最近浏览的文章', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'title_z'     => '',
			'show_icon'   => '',
			'show_svg'    => '',
			'title'       => '最近浏览的文章',
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
		<h3 class="widget-title-cat-icon cat-w-icon da"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon da"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
	<?php } ?>

	<div class="post_cat rp">
		<div id="recently-viewed"></div>
		<div class="clear"></div>
		<?php wp_enqueue_script( 'viewed', get_template_directory_uri() . '/js/viewed.js', array(), version, false ); ?>
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
			$title = '最近浏览的文章';
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
add_action( 'widgets_init', 'be_recently_viewed_init' );
function be_recently_viewed_init() {
	register_widget( 'be_recently_viewed' );
}

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
			if ( ! empty( $title ) ) {
				echo $before_title . $title_w . $title . $after_title;
			}
		}
?>

<?php if ( ! is_page() ) { ?>
<div class="widget-filter">
	<?php if ($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon da"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon da"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
	<?php } ?>

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
		$title = '条件筛选';
	}
	global $wpdb;

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
		<h3 class="widget-title-cat-icon cat-w-icon da"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon da"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
	<?php } ?>

<ul class="color-cat">
	<?php
		$args=array(
			'exclude' => $e_cat,
			'include'       => '',
			'taxonomy' => array('category','taobao', 'gallery', 'videos', 'products', 'notice'),
			'hide_empty' => 0,
			'hierarchical'    => false,
			'title_li'      =>  ''
		);
		$cats = get_categories($args);
		foreach ( $cats as $cat ) {
		query_posts( 'cat=' . $cat->cat_ID );
	?>
	<li><a class="get-icon" href="<?php echo get_category_link($cat->cat_ID);?>" rel="bookmark"><?php echo $cat->cat_name; ?></a></li>
	<?php } ?>
	<?php wp_reset_query(); ?>
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
		<h3 class="widget-title-cat-icon cat-w-icon da"><a href="<?php echo $instance['titleUrl']; ?>" rel="bookmark"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?><?php more_i(); ?></a></h3>
	<?php } ?>
	<?php
		$args = array(
			'posts_per_page' => '1', 
			'post_type' => 'page', 
			'meta_key' => 'special',
			'meta_value' => $instance['tag_slug'],
			'ignore_sticky_posts' => 1
		);
		query_posts($args);
		while (have_posts()) : the_post();
	?>

	<div class="cover4x">
		<div class="cat-cover-main bgt">
			<div class="cat-cover-img">
				<a href="<?php echo get_permalink(); ?>" rel="bookmark">
					<div class="special-mark bz fd"><?php _e( '专题', 'begin' ); ?></div>
					<figure class="cover-img">
						<?php 
							global $wpdb, $post;
							$image = get_post_meta(get_the_ID(), 'thumbnail', true);
							echo '<img src=';
							if (zm_get_option('special_thumbnail')) {
								echo get_template_directory_uri().'/prune.php?src='.$image.'&w='.zm_get_option('img_k_w').'&h='.zm_get_option('img_k_h').'&a='.zm_get_option('crop_top').'&zc=1';
							} else {
								echo $image;
							}
							echo ' alt="'.$post->post_title .'" />'; 
						?>
					</figure>
					<div class="cover-des-box"><div class="cover-des"><?php $description = get_post_meta(get_the_ID(), 'description', true);{echo $description;} ?></div></div>
				</a>
				<div class="clear"></div>
			</div>
			<a href="<?php echo get_permalink(); ?>" rel="bookmark">
				<div class="special-title-widget"><?php the_title(); ?>
				<span class="special-more">
					<?php 
						global $wpdb, $post;
						$special = get_post_meta(get_the_ID(), 'special', true);
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
	<?php wp_reset_query(); ?>

	<ul class="post_cat">

		<?php
			$args = array(
				'tag_id' => get_tag_id_slug($instance['tag_slug']),
				'posts_per_page' => $instance['numposts'],
			);
			query_posts($args);
		?>
		<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
		<?php the_title( sprintf( '<li class="srm the-icon"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></li>' ); ?>
		<?php endwhile; ?>
		<?php else : ?>
			<?php _e( '未添加文章', 'begin' ); ?>
		<?php endif; wp_reset_query();?>
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
		<h3 class="widget-title-cat-icon cat-w-icon da"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon da"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
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
					<?php zm_thumbnail(); ?>
				</span>
				<span class="hot-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
				<?php views_span(); ?>
				<span class="be-like"><i class="be be-thumbs-up-o ri"></i><?php zm_get_current_count(); ?></span>
			</li>
			<?php } else { ?>
				<li class="srm"><span class="new-title"><span class='li-icon li-icon-<?php echo($i); ?>'><?php echo($i++); ?></span><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span></li>
			<?php } ?>
		<?php endwhile;?>
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
		<h3 class="widget-title-cat-icon cat-w-icon da"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon da"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
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
	<h3 class="widget-title-cat-icon cat-w-icon da"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
<?php } ?>
<?php if ($instance['show_svg']) { ?>
	<h3 class="widget-title-cat-icon cat-w-icon da"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
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
		<h3 class="widget-title-cat-icon cat-w-icon da"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon da"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
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
		<h3 class="widget-title-cat-icon cat-w-icon da"><a href="<?php echo $titleUrl; ?>" target="_blank"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?><?php more_i(); ?></a></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon da"><a href="<?php echo $titleUrl; ?>" target="_blank"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?><?php more_i(); ?></a></h3>
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
				<a href="<?php echo get_category_link( $instance['cat'] ); ?>" rel="bookmark">
					<span class="meta-author-avatar shuo-avatar">
						<?php 
							if (zm_get_option('cache_avatar')) {
								echo begin_avatar( get_the_author_meta('email'), '96', '', get_the_author() );
							} else {
								echo get_avatar( get_the_author_meta('email'), '96', '', get_the_author() );
							}
						?>
					</span>

					<span class="shuo-w dah">
						<span class="arrow-left"></span>
						<span class="shuo-entry-meta dah">
							<span class="shuo-author dah"><?php the_author(); ?></span>
							<span class="clear"></span>
							<span class="shuo-inf shuo-date dah"><?php time_ago( $time_type ='post' ); ?></span>
							<span class="shuo-inf shuo-time dah"><?php echo get_the_time('H:i:s'); ?></span>
						</span>
						<span class="clear"></span>
						<?php 
							$content = get_the_content();
							$content = wp_strip_all_tags(str_replace(array('[',']'),array('<','>'),$content));
							echo wp_trim_words( $content, zm_get_option('words_n'), '...' );
						?>
					</span>
				</a>
			<?php } else { ?>
				<?php if ($instance['show_time']) { ?><span class="date-n"><span class="day-n"><?php the_time('m') ?></span>/<span class="month-n"><?php the_time('d') ?></span></span><?php } else { ?><i class="be be-volumedown"></i><?php } ?>
				<?php the_title( sprintf( '<a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
			<?php } ?>
		</li>
		<?php endwhile; ?>
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

// Ajax搜索
class ajax_search_widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'ajax_search_widget',
			'description' => 'Ajax搜索',
			'customize_selective_refresh' => true,
		);
		parent::__construct('ajax_search_widget', 'Ajax搜索', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_tags'    => 0,
			'title_z'      => '',
			'show_icon'    => '',
			'show_svg'     => '',
			'title'        => 'Ajax搜索',
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

<div class="widget-ajax-searchd">
	<?php if ($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon da"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon da"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<div class="ajax-search-input">
		<input class="search-input dah" type="text" autocomplete="off" value="<?php echo get_search_query(); ?>" name="s" id="searchInput" onkeyup="search_data_w()" placeholder="<?php _e( '输入关键字', 'begin' ); ?>" />
		<script type="text/javascript">document.getElementById('searchInput').addEventListener('input', function(e) {var value = search_data_w();});</script>
	</div>
	<div id="searchdata" class="da"></div>
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
			$title = 'Ajax搜索';
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

add_action( 'widgets_init', 'ajax_search_widget_init' );
function ajax_search_widget_init() {
	register_widget( 'ajax_search_widget' );
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
		<h3 class="widget-title-cat-icon cat-w-icon da"><a href="<?php echo $titleUrl; ?>" target="_blank"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?><?php more_i(); ?></a></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon da"><a href="<?php echo $titleUrl; ?>" target="_blank"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?><?php more_i(); ?></a></h3>
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
						<div class="sites-ico dah bk load"><img class="sites-img" src="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=" data-original="<?php echo zm_get_option( 'favicon_api' ); ?><?php echo $sites_url; ?>" alt="<?php the_title(); ?>"></div>
					</a>
				<?php } else { ?>
					<a href="<?php echo $sites_link; ?>" target="_blank" rel="external nofollow">
						<div class="sites-ico dah bk load"><img class="sites-img" src="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=" data-original="<?php echo zm_get_option( 'favicon_api' ); ?><?php echo $sites_link; ?>" alt="<?php the_title(); ?>"></div>
					</a>
				<?php } ?>
			<?php } ?>

			<?php if ($instance['show_time']) { ?><span class="date"><span><?php _e( '收录', 'begin' ); ?></span><time datetime="<?php echo get_the_date('Y-m-d'); ?> <?php echo get_the_time('H:i:s'); ?>"><?php the_time('m/d') ?></time></span><?php } ?>
			<?php the_title( sprintf( '<a class="sites-title-w" href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
		</li>
		<?php endwhile; ?>
		<?php wp_reset_query(); ?>
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

add_action( 'widgets_init', 'be_site_init' );
function be_site_init() {
	register_widget( 'be_site_widget' );
}

// RSS
class be_widget_rss extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'be_widget_rss',
			'description' => '调用任意站点RSS和feed文章',
			'customize_selective_refresh' => true,
		);
		parent::__construct('be_widget_rss', 'RSS', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_tags'   => 0,
			'title_z'     => '',
			'show_icon'   => '',
			'show_svg'    => '',
			'feed'    => '',
			'numposts'     => 5,
			'title'       => 'RSS',
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

<div class="post_cat">
	<?php if ($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon da"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon da"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
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
				<li class="srm the-icon">
					<a href="<?php echo esc_url( $item->get_permalink() ); ?>" rel="external nofollow" target="_blank">
						<?php echo esc_html( $item->get_title() ); ?>
					</a>
				</li>
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
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['show_svg'] = strip_tags($new_instance['show_svg']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['feed'] = strip_tags($new_instance['feed']);
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
			$title = 'RSS';
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
			'description' => '文章目录',
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
		<h3 class="widget-title-cat-icon cat-w-icon da"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ($instance['show_svg']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon da"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
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
					echo '<span class="annex-down show-layer" data-show-layer="login-layer"></span>';
				}
				echo '</div>';

				echo '<div class="annex-inf bgt">';
				echo sprintf(__( '编号', 'begin' )) . '<span>' . $image_id . '</span>';
				echo '<div class="bgt">' . sprintf(__( '分辨率', 'begin' )) . '<span>';
				echo $metadata['width'];
				echo '&times;' . $metadata['height'] . '</span></div>';
				echo '<div class="bgt">' . sprintf(__( '大小', 'begin' )) . '<span>' . size_format( filesize( get_attached_file( $image_id ) ), 2 ) . '</span></div>';
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
				<h3 class="widget-title-cat-icon cat-w-icon da"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
			<?php } ?>

			<?php
				$output = '<ul id="sticky-comments-widget">';
				if ( $comments ) {
					foreach ( $comments as $comment ) {
						$output .= '<li class="sticky-comments-item load"><a href="' . get_permalink( $comment->comment_post_ID ) . '#anchor-comment-' . $comment->comment_ID . '" title="' . sprintf(__( '发表在', 'begin' )) . '：' . get_the_title( $comment->comment_post_ID ) . '" rel="external nofollow">';
						if ( ! zm_get_option( 'avatar_load' ) ) {
							$output .= get_avatar( $comment->comment_author_email, '96', '', get_comment_author( $comment->comment_ID ) );
						} else {
							$output .= '<img class="avatar photo" src="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=" alt="' . get_comment_author( $comment->comment_ID ) . '" width="96" height="96" data-original="' . preg_replace( array( '/^.+(src=)(\"|\')/i', '/(\"|\')\sclass=(\"|\').+$/i' ), array( '', '' ), get_avatar( $comment->comment_author_email, '96' ) ) . '" />';
						}
						$output .= '<span class="comment_author da">';
						$output .= get_comment_author( $comment->comment_ID );
						$output .= '</span>';
						$output .= '<span class="say-comment da">';
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
		<h3 class="widget-title-cat-icon cat-w-icon da">
			<a href="<?php echo $instance['titleUrl']; ?>" rel="bookmark">
				<i class="t-icon <?php echo $instance['show_icon']; ?>"></i>
				<?php echo $instance['title_z']; ?>
				<?php more_i(); ?>
			</a>
		</h3>
	<?php } else { ?>
		<?php if ( $instance['show_icon'] ) { ?>
			<h3 class="widget-title-cat-icon cat-w-icon da"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
		<?php } ?>
		<?php if ( $instance['show_svg'] ) { ?>
			<h3 class="widget-title-cat-icon cat-w-icon da"><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo $instance['show_svg']; ?>"></use></svg><?php echo $instance['title_z']; ?></h3>
		<?php } ?>
	<?php } ?>
	<ul>
		<?php
			$special = array(
				'taxonomy'      => 'special',
				'show_count'    => 1,
				'orderby'       => 'menu_order',
				'order'         => 'ASC',
				'include'       => $instance['special_id'],
				'hide_empty'    => 0,
				'hierarchical'  => 0
			);
			$cats = get_categories( $special );
		?>
		<?php foreach( $cats as $cat ) :  ?>
			<li>
				<span class="thumbnail lazy">
					<?php if ( zm_get_option( 'lazy_s' ) ) { ?>
						<a class="thumbs-back sc" href="<?php echo get_category_link( $cat->term_id ) ?>" rel="bookmark" data-src="<?php echo cat_cover_url( $cat->term_id ); ?>"></a>
					<?php } else { ?>
						<a class="thumbs-back sc" href="<?php echo get_category_link( $cat->term_id ) ?>" rel="bookmark" style="background-image: url(<?php echo cat_cover_url( $cat->term_id ); ?>);"></a>
					<?php } ?>
				</span>
				<span class="new-title column-title-w">
					<a href="<?php echo get_category_link( $cat->term_id ) ?>" rel="bookmark">
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
	</p>
<?php }
}

add_action( 'widgets_init', 'be_column_cover_init' );
function be_column_cover_init() {
	register_widget( 'be_column_cover' );
}

// 分类法
if (zm_get_option('no_gallery')) {
add_action( 'widgets_init', 'be_img_widget_init' );
function be_img_widget_init() {
	register_widget( 'be_img_widget' );
}
}
if (zm_get_option('no_videos')) {
add_action( 'widgets_init', 'be_video_widget_init' );
function be_video_widget_init() {
	register_widget( 'be_video_widget' );
}
}
if (zm_get_option('no_tao')) {
add_action( 'widgets_init', 'be_tao_widget_init' );
function be_tao_widget_init() {
	register_widget( 'be_tao_widget' );
}
}
if (zm_get_option('no_products')) {
add_action( 'widgets_init', 'be_show_widget_init' );
function be_show_widget_init() {
	register_widget( 'be_show_widget' );
}
}
if (zm_get_option('no_bulletin')) {
add_action( 'widgets_init', 'widget_notice_init' );
function widget_notice_init() {
	register_widget( 'widget_notice' );
}
}