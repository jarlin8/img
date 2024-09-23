<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="g-row">
	<div class="show-header-box">
		<?php 
			$s_a_img_d = get_post_meta( get_the_ID(), 's_a_img_d', true );
			$s_a_img_x = get_post_meta( get_the_ID(), 's_a_img_x', true );
			$s_a_t_a   = get_post_meta( get_the_ID(), 's_a_t_a', true );
			$s_a_t_b   = get_post_meta( get_the_ID(), 's_a_t_b', true );
			$s_a_t_c   = get_post_meta( get_the_ID(), 's_a_t_c', true );
			$s_a_n_b   = get_post_meta( get_the_ID(), 's_a_n_b', true );
			$s_a_n_b_l = get_post_meta( get_the_ID(), 's_a_n_b_l', true );
		?>
		<div class="show-header-img">
			<?php if ( get_post_meta( get_the_ID(), 's_a_img_d', true ) ) { ?>
				<div class="show-big-img"><img src="<?php echo $s_a_img_d; ?>"></div>
				<div class="show-header-main<?php if ( get_post_meta( get_the_ID(), 's_a_img_x', true ) ) { ?> show-img<?php } else { ?> show-p<?php } ?>">
					<div class="show-small-img"><img class="str1" src="<?php echo $s_a_img_x; ?>"></div>
					<?php if ( get_post_meta(get_the_ID(), 's_a_t_b', true) ) { ?>
						<div class="show-header-w">
							<div class="show-header-content">
								<p class="s-t-a str1"><?php echo $s_a_t_a; ?></p>
								<p class="s-t-b str2"><?php echo $s_a_t_b; ?></p>
								<p class="s-t-c str3"><?php echo $s_a_t_c; ?></p>
							</div>
							<?php if ( get_post_meta( get_the_ID(), 's_a_n_b', true ) ) { ?>
								<div class="group-img-more str4"><a href="<?php echo $s_a_n_b_l; ?>" rel="bookmark" target="_blank"><?php echo $s_a_n_b; ?></a></div>
							<?php } ?>
						</div>
					<?php } ?>
				</div>
			<?php } ?>
		</div>
	</div>
</div>