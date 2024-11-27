<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( get_the_author_meta( 'weixinqr' ) && zm_get_option( 'author_weixin_one' ) ) { ?>
	<div class="s-weixin-one b-weixin weixin-author betip" data-aos="zoom-in">
		<div class="weimg-one">
			<div class="copy-weixin">
				<img src="<?php the_author_meta( 'weixinqr' ); ?>" alt="weinxin">
				<div class="weixinbox">
					<div class="btn-weixin-copy"></div>
					<div class="weixin-id"><?php the_author_meta( 'weixin' ); ?></div>
					<div class="copy-success-weixin fd"><div class="copy-success-weixin-text"><span class="dashicons dashicons-saved"></span><?php _e( '已复制', 'begin' ); ?></div></div>
				</div>
			</div>
			<div class="weixin-inf">
				<div class="weixin-h"><strong><?php the_author(); ?></strong></div>
				<div class="weixin-h-w">
					<?php if ( get_the_author_meta( 'qq' ) ) { ?>
						<p><a target=blank rel="external nofollow" href="https://wpa.qq.com/msgrd?V=3&uin=<?php the_author_meta( 'qq' ); ?>&Site=QQ&Menu=yes"><i class="be be-qq"></i><?php the_author_meta( 'qq' ); ?></a></p>
					<?php } ?>
					<p>添加作者微信：<?php the_author_meta( 'weixin' ); ?></p>
					<p>微信扫一扫，或点击二维码复制微信号</p>
				</div>
				<div class="clear"></div>
			</div>
		</div>
		<?php echo be_help( $text = '主题选项 → 文章设置 → 正文末尾微信二维码' ); ?>
	</div>
<?php } else { ?>
	<?php if (zm_get_option('single_weixin_one')) { ?>
		<div class="s-weixin-one b-weixin betip" data-aos="zoom-in">
			<div class="weimg-one">
				<div class="copy-weixin">
					<img src="<?php echo zm_get_option('weixin_h_img'); ?>" alt="weinxin">
					<div class="weixinbox">
						<div class="btn-weixin-copy"></div>
						<div class="weixin-id"><?php echo zm_get_option( 'weixin_s_id' ); ?></div>
						<div class="copy-success-weixin fd"><div class="copy-success-weixin-text"><span class="dashicons dashicons-saved"></span><?php echo zm_get_option('weixin_h_tip'); ?></div></div>
					</div>
				</div>
				<div class="weixin-h"><strong><?php if ( zm_get_option('weixin_h') == '' ) { ?><?php } else { ?><?php echo zm_get_option('weixin_h'); ?><?php } ?></strong></div>
				<div class="weixin-h-w"><?php if ( zm_get_option('weixin_h_w') == '' ) { ?><?php } else { ?><?php echo zm_get_option('weixin_h_w'); ?><?php } ?></div>
				<div class="clear"></div>
			</div>
			<?php echo be_help( $text = '主题选项 → 文章设置 → 正文末尾微信二维码' ); ?>
		</div>
	<?php } else { ?>
		<div class="s-weixin b-weixin betip" data-aos="zoom-in">
			<div class="weimg-my weimg1">
				<div>
					<strong><?php if ( zm_get_option('weixin_h') == '' ) { ?><?php } else { ?><?php echo zm_get_option('weixin_h'); ?><?php } ?></strong>
				</div>
				<div><?php if ( zm_get_option('weixin_h_w') == '' ) { ?><?php } else { ?><?php echo zm_get_option('weixin_h_w'); ?><?php } ?></div>
				<div class="copy-weixin">
					<img src="<?php echo zm_get_option('weixin_h_img'); ?>" alt="weinxin">
					<div class="weixinbox">
						<div class="btn-weixin-copy"></div>
						<div class="weixin-id"><?php echo zm_get_option( 'weixin_s_id' ); ?></div>
						<div class="copy-success-weixin fd"><div class="copy-success-weixin-text"><span class="dashicons dashicons-saved"></span><?php echo zm_get_option('weixin_h_tip'); ?></div></div>
					</div>
				</div>
			</div>
			<div class="weimg-my weimg2">
				<div>
					<strong><?php if ( zm_get_option('weixin_g') == '' ) { ?><?php } else { ?><?php echo zm_get_option('weixin_g'); ?><?php } ?></strong>
				</div>
				<div><?php if ( zm_get_option('weixin_g_w') == '' ) { ?><?php } else { ?><?php echo zm_get_option('weixin_g_w'); ?><?php } ?></div>
				<div class="copy-weixin">
					<img src="<?php echo zm_get_option('weixin_g_img'); ?>" alt="weinxin">
					<div class="weixinbox">
						<div class="btn-weixin-copy"></div>
						<div class="weixin-id"><?php echo zm_get_option( 'weixin_g_id' ); ?></div>
						<div class="copy-success-weixin fd"><div class="copy-success-weixin-text"><span class="dashicons dashicons-saved"></span><?php echo zm_get_option('weixin_g_tip'); ?></div></div>
					</div>
				</div>
			</div>
			<?php echo be_help( $text = '主题选项 → 文章设置 → 正文末尾微信二维码' ); ?>
			<div class="clear"></div>
		</div>
	<?php } ?>
<?php } ?>