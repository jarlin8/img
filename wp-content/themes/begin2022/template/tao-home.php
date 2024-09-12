<div class="xl4 xm4" <?php aos_b(); ?>>
	<div class="tao-h sup bk">
		<figure class="tao-h-img">
			<?php tao_thumbnail(); ?>
			<?php if ( get_post_meta( $post->ID, 'tao_img_t', true ) ) : ?>
				<div class="tao-dis"><?php $tao_img_t = get_post_meta( $post->ID, 'tao_img_t', true );{ echo $tao_img_t; } ?></div>
			<?php endif; ?>
		</figure>
		<div class="product-box">
			<?php the_title( sprintf( '<h2><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
			<div class="ded">
				<ul class="price">
					<li class="pricex"><strong>￥ <?php $price = get_post_meta( $post->ID, 'pricex', true );{ echo $price; } ?>元</strong></li>
					<li class="pricey">
						<?php if ( !get_post_meta( $post->ID, 'pricey', true ) && !get_post_meta( $post->ID, 'spare_t', true ) ){ ?>
							已售：<?php views_tao(); ?>
						<?php } else { ?>
							<?php if ( get_post_meta( $post->ID, 'pricey', true ) ) : ?>
								<del>市场价：<?php $price = get_post_meta( $post->ID, 'pricey', true );{ echo $price; } ?>元</del>
							<?php endif; ?>

							<?php if ( get_post_meta( $post->ID, 'spare_t', true ) ) : ?>
								<?php $spare_t = get_post_meta( $post->ID, 'spare_t', true);{ echo $spare_t; } ?>
							<?php endif; ?>
						<?php } ?>
					</li>
				</ul>
				<div class="go-url">
					<div class="taourl">
						<?php if ( get_post_meta( $post->ID, 'taourl', true ) ) : ?>
							<?php
								if ( get_post_meta( $post->ID, 'm_taourl', true ) && wp_is_mobile() ) {
									$url = get_post_meta( $post->ID, 'm_taourl', true );
								} else {
									$url = get_post_meta( $post->ID, 'taourl', true );
								}
								echo '<div class="taourl"><a href=' . $url . ' rel="external nofollow" target="_blank" class="url">购买</a></div>';
							?>
						<?php endif; ?>
					</div>
					<div class="detail"><a href="<?php the_permalink(); ?>" rel="bookmark">详情</a></div>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	</div>
</div>