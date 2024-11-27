<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( "img_way") == 'd_img' ) || ( zm_get_option( "img_way") == 'o_img' ) || ( zm_get_option( "img_way") == 'q_img' ) || ( zm_get_option( "img_way") == 'upyun' ) || ( zm_get_option( "img_way") == 'cos_img' ) ) {
// 标准缩略图
function zm_thumbnail() {
	global $post;
	$random_img = explode( ',', zm_get_option( 'random_image_url' ) );
	$random_img_array = array_rand( $random_img );
	$src = $random_img[$random_img_array];

	$fancy_box = get_post_meta( get_the_ID(), 'fancy_box', true );
	if ( get_post_meta( get_the_ID(), 'fancy_box', true ) ) {
		echo '<a class="fancy-box" rel="external nofollow" href="' . $fancy_box . '"></a>';
	}

	// 手动
	if ( get_post_meta( get_the_ID(), 'thumbnail', true ) ) {
		$image = get_post_meta( get_the_ID(), 'thumbnail', true );
		if ( zm_get_option('lazy_s' ) ) {
			echo '<span class="load"><a class="sc" rel="external nofollow" href="' . get_permalink() . '"><img src="' . get_template_directory_uri() . '/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w=' . zm_get_option( 'img_w' ) . '&h=' . zm_get_option( 'img_h' ) . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1" data-original="';
		} else {
			echo '<a class="sc" rel="external nofollow" href="' . get_permalink() . '"><img src="';
		}
		if ( zm_get_option( 'manual_thumbnail' ) ) {
			echo get_template_directory_uri() . '/prune.php?src=' . $image . '&w=' . zm_get_option( 'img_w' ) . '&h=' . zm_get_option( 'img_h' ) . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1"';
		} else {
			echo $image . '"';
		}
		echo ' alt="' . $post->post_title . '" width="' . zm_get_option( 'img_w' ) . '" height="' . zm_get_option( 'img_h' ) . '" /></a>';
		if ( zm_get_option( 'lazy_s' ) ) {
			echo '</span>';
		}
	} else {
		// 特色
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'content');
			if ( zm_get_option( 'lazy_s' ) ) {
				echo '<span class="load"><a class="sc" rel="external nofollow" href="' . get_permalink() . '">';
			} else {
				echo '<a class="sc" rel="external nofollow" href="' . get_permalink() . '">';
			}
			if ( zm_get_option( 'clipping_thumbnails' ) ) {
				if ( zm_get_option( 'lazy_s' ) ) {
					echo '<img src="' . get_template_directory_uri() . '/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w=' . zm_get_option('img_w') . '&h=' . zm_get_option( 'img_h' ) . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1" data-original="';
				} else {
					echo '<img src="';
				}
				echo get_template_directory_uri() . '/prune.php?src=' . $full_image_url[0] . '&w=' . zm_get_option( 'img_w' ) . '&h=' . zm_get_option( 'img_h' ) . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1" alt="' . get_the_title() . '" width="' . zm_get_option( 'img_w' ) . '" height="' . zm_get_option( 'img_h' ) . '" >';
			} else {
				if ( zm_get_option( 'lazy_s' ) ) {
					echo '<img src="' . get_template_directory_uri() . '/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w=' . zm_get_option( 'img_w' ).'&h=' . zm_get_option( 'img_h' ) . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1" data-original="' . $full_image_url[0] . '" alt="' . get_the_title() . '" width="' . zm_get_option( 'img_w' ) . '" height="' . zm_get_option( 'img_h' ) . '" >';
				} else {
					the_post_thumbnail( 'content', array( 'alt' => get_the_title() ) );
				}
			}
			if ( zm_get_option( 'lazy_s' ) ) {
				echo '</a></span>';
			} else {
				echo '</a>';
			}
		} else {
			// 自动
			$content = $post->post_content;
			preg_match_all( '/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER );
			$n = count( $strResult[1] );
			if ( $n > 0 && !get_post_meta( get_the_ID(), 'rand_img', true ) ) {
				if ( zm_get_option( 'lazy_s' ) ) {
					echo '<span class="load"><a class="sc" rel="external nofollow" href="' . get_permalink() . '"><img src="' . get_template_directory_uri() . '/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('img_w').'&h=' . zm_get_option('img_h') . '&a=' . zm_get_option('crop_top') . '&zc=1" data-original="';
				} else {
					echo '<a class="sc" rel="external nofollow" href="' . get_permalink() . '"><img src="';
				}

				if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( "img_way") == 'd_img' ) ) {
					echo get_template_directory_uri() . '/prune.php?src=' . $strResult[1][0] . '&w=' . zm_get_option( 'img_w' ) . '&h=' . zm_get_option( 'img_h' ) . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_w' ) . '" height="' . zm_get_option( 'img_h' ) . '" /></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'o_img' ) {
					echo $strResult[1][0] . '?x-oss-process=image/resize,m_fill,w_' . zm_get_option( 'img_w' ) . ' ,h_' . zm_get_option( 'img_h' ) . ', limit_0" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_w' ) . '" height="' . zm_get_option( 'img_h' ) . '" /></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'q_img' ) {
					echo $strResult[1][0] . '?imageMogr2/gravity/Center/crop/' . zm_get_option( 'img_w' ) . 'x' . zm_get_option( 'img_h' ) . '" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_w' ) . '" height="' . zm_get_option( 'img_h' ) . '" /></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'upyun' ) {
					echo $strResult[1][0] . '!/both/' . zm_get_option( 'img_w' ) . 'x' . zm_get_option( 'img_h' ) . '/format/webp/lossless/true" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_w' ) . '" height="' . zm_get_option( 'img_h' ) . '" /></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'cos_img' ) {
					echo $strResult[1][0] . '?imageView2/1/w/' . zm_get_option( 'img_w' ) . '/h/' . zm_get_option( 'img_h' ) . '/q/85" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_w' ) . '" height="' . zm_get_option( 'img_h' ) . '" /></a>';
				}

				if ( zm_get_option( 'lazy_s' ) ) {
					echo '</span>';
				}
			} else { 
				// 随机
				if ( zm_get_option( 'rand_img_n' ) ) {
					$random = mt_rand( 1, zm_get_option( 'rand_img_n' ) );
				} else {
					$random = mt_rand( 1, 5 );
				}
				if ( zm_get_option( 'clipping_rand_img' ) ) {
					if ( zm_get_option( 'lazy_s' ) ) {
						echo '<span class="load"><a class="sc" rel="external nofollow" href="' . get_permalink().'"><img src="' . get_template_directory_uri() . '/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w=' . zm_get_option( 'img_w' ) . '&h=' . zm_get_option( 'img_h' ) . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1" data-original="';
					} else {
						echo '<a class="sc" rel="external nofollow" href="' . get_permalink() . '"><img src="';
					}
					echo get_template_directory_uri() . '/prune.php?src=' . $src . '&w=' . zm_get_option( 'img_w' ) . '&h=' . zm_get_option( 'img_h' ) . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_w' ) . '" height="' . zm_get_option( 'img_h' ) . '"/></a>';
					if (zm_get_option('lazy_s')) {
						echo '</span>';
					}
				} else {
					if ( zm_get_option( 'lazy_s' ) ) {
						echo '<span class="load"><a class="sc" rel="external nofollow" href="' . get_permalink() . '"><img src="' . get_template_directory_uri() . '/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w=' . zm_get_option( 'img_w' ) . '&h=' . zm_get_option( 'img_h' ) . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1" data-original="' . $src . '" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_w' ) . '" height="' . zm_get_option( 'img_h' ) . '" /></a></span>';
					} else {
						echo '<a class="sc" rel="external nofollow" href="' . get_permalink() . '"><img src="' . $src . '" alt="' . $post->post_title .'" width="' . zm_get_option( 'img_w' ) . '" height="' . zm_get_option('img_h') . '" /></a>';
					}
				}
			}
		}
	}
}

// 分类模块宽缩略图
function zm_long_thumbnail() {
	$random_img = explode(',' , zm_get_option('random_long_url'));
	$random_img_array = array_rand($random_img);
	$src = $random_img[$random_img_array];
	global $post;
	if ( get_post_meta(get_the_ID(), 'long_thumbnail', true) ) {
		$image = get_post_meta(get_the_ID(), 'long_thumbnail', true);
		if (zm_get_option('lazy_s')) {
			echo '<span class="load"><a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('img_k_w').'&h='.zm_get_option('img_k_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="';
		} else {
			echo '<a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="';
		}
		if (zm_get_option('manual_thumbnail')) {
			echo get_template_directory_uri().'/prune.php?src='.$image.'&w='.zm_get_option('img_k_w').'&h='.zm_get_option('img_k_h').'&a='.zm_get_option('crop_top').'&zc=1"';
		} else {
			echo $image . '"';
		}
		echo ' alt="'.$post->post_title .'" width="'.zm_get_option('img_k_w').'" height="'.zm_get_option('img_k_h').'" /></a>';
		if (zm_get_option('lazy_s')) {
			echo '</span>';
		}
	} else {
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'long');
			if (zm_get_option('lazy_s')) {
				echo '<span class="load"><a class="sc" rel="external nofollow" href="'.get_permalink().'">';
			} else {
				echo '<a class="sc" rel="external nofollow" href="'.get_permalink().'">';
			}
			if (zm_get_option('clipping_thumbnails')) {
				if (zm_get_option('lazy_s')) {
					echo '<img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('img_k_w').'&h='.zm_get_option('img_k_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="';
				} else {
					echo '<img src="';
				}
				echo get_template_directory_uri().'/prune.php?src='.$full_image_url[0].'&w='.zm_get_option('img_k_w').'&h='.zm_get_option('img_k_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.get_the_title().'" width="'.zm_get_option('img_k_w').'" height="'.zm_get_option('img_k_h').'">';
			} else {
				if (zm_get_option('lazy_s')) {
					echo '<img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('img_k_w').'&h='.zm_get_option('img_k_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="'.$full_image_url[0].'" alt="'.get_the_title().'">';
				} else {
					the_post_thumbnail('long', array('alt' => get_the_title()));
				}
			}
			if (zm_get_option('lazy_s')) {
				echo '</a></span>';
			} else {
				echo '</a>';
			}
		} else {
			$content = $post->post_content;
			preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
			$n = count($strResult[1]);
			if ($n > 0 && !get_post_meta( get_the_ID(), 'rand_img', true )) {
				if (zm_get_option('lazy_s')) {
					echo '<span class="load"><a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('img_k_w').'&h='.zm_get_option('img_k_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="';
				} else {
					echo '<a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="';
				}

				if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( "img_way") == 'd_img' ) ) {
					echo get_template_directory_uri() . '/prune.php?src=' . $strResult[1][0] . '&w=' . zm_get_option( 'img_k_w' ) . '&h=' . zm_get_option( 'img_k_h' ) . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_k_w' ) . '" height="' . zm_get_option( 'img_k_h' ) . '" /></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'o_img' ) {
					echo $strResult[1][0] . '?x-oss-process=image/resize,m_fill,w_' . zm_get_option( 'img_k_w' ) . ' ,h_' . zm_get_option( 'img_k_h' ) . ', limit_0" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_k_w' ) . '" height="' . zm_get_option( 'img_k_h' ) . '" /></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'q_img' ) {
					echo $strResult[1][0] . '?imageMogr2/gravity/Center/crop/' . zm_get_option( 'img_k_w' ) . 'x' . zm_get_option( 'img_k_h' ) . '" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_k_w' ) . '" height="' . zm_get_option( 'img_k_h' ) . '" /></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'upyun' ) {
					echo $strResult[1][0] . '!/both/' . zm_get_option( 'img_k_w' ) . 'x' . zm_get_option( 'img_k_h' ) . '/format/webp/lossless/true" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_k_w' ) . '" height="' . zm_get_option( 'img_k_h' ) . '" /></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'cos_img' ) {
					echo $strResult[1][0] . '?imageView2/1/w/' . zm_get_option( 'img_k_w' ) . '/h/' . zm_get_option( 'img_k_h' ) . '/q/85" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_k_w' ) . '" height="' . zm_get_option( 'img_k_h' ) . '" /></a>';
				}

				if (zm_get_option('lazy_s')) {
					echo '</span>';
				}
			} else { 
				if ( zm_get_option('rand_img_n') ) {
					$random = mt_rand(1, zm_get_option('rand_img_n'));
				} else { 
					$random = mt_rand(1, 5);
				}
				if (zm_get_option('clipping_rand_img')) {
					if (zm_get_option('lazy_s')) {
						echo '<span class="load"><a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('img_k_w').'&h='.zm_get_option('img_k_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="';
					} else {
						echo '<a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="';
					}
					echo get_template_directory_uri().'/prune.php?src='.$src.'&w='.zm_get_option('img_k_w').'&h='.zm_get_option('img_k_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.$post->post_title .'" width="'.zm_get_option('img_k_w').'" height="'.zm_get_option('img_k_h').'" /></a>';
					if (zm_get_option('lazy_s')) {
						echo '</span>';
					}
				} else { 
					if (zm_get_option('lazy_s')) {
						echo '<span class="load"><a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('img_k_w').'&h='.zm_get_option('img_k_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="'. $src .'" alt="'.$post->post_title .'" width="'.zm_get_option('img_k_w').'" height="'.zm_get_option('img_k_h').'" /></a></span>';
					} else {
						echo '<a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="'. $src .'" alt="'.$post->post_title .'" width="'.zm_get_option('img_k_w').'" height="'.zm_get_option('img_k_h').'" /></a>';
					}
				}
			}
		}
	}
}

// 图片缩略图
function img_thumbnail() {
	global $post;
	$random_img = explode(',' , zm_get_option('random_image_url'));
	$random_img_array = array_rand($random_img);
	$src = $random_img[$random_img_array];

	$fancy_box = get_post_meta( get_the_ID(), 'fancy_box', true );
	if ( get_post_meta( get_the_ID(), 'fancy_box', true ) ) {
		echo '<a class="fancy-box" rel="external nofollow" href="'. $fancy_box . '"></a>';
	}

	if ( get_post_meta(get_the_ID(), 'thumbnail', true) ) {
		$image = get_post_meta(get_the_ID(), 'thumbnail', true);
		if (zm_get_option('lazy_s')) {
			echo '<span class="load"><a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('img_i_w').'&h='.zm_get_option('img_i_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="';
		} else {
			echo '<a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="';
		}
		if (zm_get_option('manual_thumbnail')) {
			echo get_template_directory_uri().'/prune.php?src='.$image.'&w='.zm_get_option('img_i_w').'&h='.zm_get_option('img_i_h').'&a='.zm_get_option('crop_top').'&zc=1"';
		} else {
			echo $image . '"';
		}
		echo ' alt="'.$post->post_title .'" width="'.zm_get_option('img_i_w').'" height="'.zm_get_option('img_i_h').'" /></a>';
		if (zm_get_option('lazy_s')) {
			echo '</span>';
		}
	} else {
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'content');
			if (zm_get_option('lazy_s')) {
				echo '<span class="load"><a class="sc" rel="external nofollow" href="'.get_permalink().'">';
			} else {
				echo '<a class="sc" rel="external nofollow" href="'.get_permalink().'">';
			}
			if (zm_get_option('clipping_thumbnails')) {
				if (zm_get_option('lazy_s')) {
					echo '<img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('img_i_w').'&h='.zm_get_option('img_i_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="';
				} else {
					echo '<img src="';
				}
				echo get_template_directory_uri().'/prune.php?src='.$full_image_url[0].'&w='.zm_get_option('img_i_w').'&h='.zm_get_option('img_i_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.get_the_title().'" width="'.zm_get_option('img_i_w').'" height="'.zm_get_option('img_i_h').'" >';
			} else {
				if (zm_get_option('lazy_s')) {
					echo '<img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('img_w').'&h='.zm_get_option('img_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="'.$full_image_url[0].'" alt="'.get_the_title().'" width="'.zm_get_option('img_i_w').'" height="'.zm_get_option('img_i_h').'" >';
				} else {
					the_post_thumbnail('content', array('alt' => get_the_title()));
				}
			}
			if (zm_get_option('lazy_s')) {
				echo '</a></span>';
			} else {
				echo '</a>';
			}
		} else {
			$content = $post->post_content;
			preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
			$n = count($strResult[1]);
			if ($n > 0) {
				if (zm_get_option('lazy_s')) {
					echo '<span class="load"><a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('img_i_w').'&h='.zm_get_option('img_i_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="';
				} else {
					echo '<a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="';
				}

				if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( "img_way") == 'd_img' ) ) {
					echo get_template_directory_uri() . '/prune.php?src=' . $strResult[1][0] . '&w=' . zm_get_option( 'img_i_w' ) . '&h=' . zm_get_option( 'img_i_h' ) . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_i_w' ) . '" height="' . zm_get_option( 'img_i_h' ) . '" /></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'o_img' ) {
					echo $strResult[1][0] . '?x-oss-process=image/resize,m_fill,w_' . zm_get_option( 'img_i_w' ) . ' ,h_' . zm_get_option( 'img_i_h' ) . ', limit_0" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_i_w' ) . '" height="' . zm_get_option( 'img_i_h' ) . '" /></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'q_img' ) {
					echo $strResult[1][0] . '?imageMogr2/gravity/Center/crop/' . zm_get_option( 'img_i_w' ) . 'x' . zm_get_option( 'img_i_h' ) . '" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_i_w' ) . '" height="' . zm_get_option( 'img_i_h' ) . '" /></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'upyun' ) {
					echo $strResult[1][0] . '!/both/' . zm_get_option( 'img_i_w' ) . 'x' . zm_get_option( 'img_i_h' ) . '/format/webp/lossless/true" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_i_w' ) . '" height="' . zm_get_option( 'img_i_h' ) . '" /></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'cos_img' ) {
					echo $strResult[1][0] . '?imageView2/1/w/' . zm_get_option( 'img_i_w' ) . '/h/' . zm_get_option( 'img_i_h' ) . '/q/85" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_i_w' ) . '" height="' . zm_get_option( 'img_i_h' ) . '" /></a>';
				}

				if (zm_get_option('lazy_s')) {
					echo '</span>';
				}
			} else { 
				if ( zm_get_option('rand_img_n') ) {
					$random = mt_rand(1, zm_get_option('rand_img_n'));
				} else { 
					$random = mt_rand(1, 5);
				}
				if (zm_get_option('clipping_rand_img')) {
					if (zm_get_option('lazy_s')) {
						echo '<span class="load"><a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('img_i_w').'&h='.zm_get_option('img_i_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="';
					} else {
						echo '<a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="';
					}
					echo get_template_directory_uri().'/prune.php?src='.$src.'&w='.zm_get_option('img_i_w').'&h='.zm_get_option('img_i_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.$post->post_title .'" width="'.zm_get_option('img_i_w').'" height="'.zm_get_option('img_i_h').'" /></a>';
					if (zm_get_option('lazy_s')) {
						echo '</span>';
					}
				} else { 
					if (zm_get_option('lazy_s')) {
						echo '<span class="load"><a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('img_i_w').'&h='.zm_get_option('img_i_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="'. $src .'" alt="'.$post->post_title .'" width="'.zm_get_option('img_i_w').'" height="'.zm_get_option('img_i_h').'" /></a></span>';
					} else {
						echo '<a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="'. $src .'" alt="'.$post->post_title .'" width="'.zm_get_option('img_i_w').'" height="'.zm_get_option('img_i_h').'" /></a>';
					}
				}
			}
		}
	}
}

// 视频缩略图
function videos_thumbnail() {
	$random_img = explode(',' , zm_get_option('random_image_url'));
	$random_img_array = array_rand($random_img);
	$src = $random_img[$random_img_array];
	global $post;
	if ( get_post_meta(get_the_ID(), 'small', true) ) {
		$image = get_post_meta(get_the_ID(), 'small', true);
		if (zm_get_option('lazy_s')) {
			echo '<span class="load"><a class="sc" rel="external nofollow" class="videos" href="'.get_permalink().'"><img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('img_v_w').'&h='.zm_get_option('img_v_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="';
		} else {
			echo '<a class="sc" rel="external nofollow" class="videos" href="'.get_permalink().'"><img src="';
		}
		if (zm_get_option('manual_thumbnail')) {
			echo get_template_directory_uri().'/prune.php?src='.$image.'&w='.zm_get_option('img_v_w').'&h='.zm_get_option('img_v_h').'&a='.zm_get_option('crop_top').'&zc=1"';
		} else {
			echo $image . '"';
		}
		echo ' alt="'.$post->post_title .'" width="'.zm_get_option('img_v_w').'" height="'.zm_get_option('img_v_h').'" /><i class="be be-play"></i></a>';
		if (zm_get_option('lazy_s')) {
			echo '</span>';
		}
	} else {
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'content');
			if (zm_get_option('lazy_s')) {
				echo '<span class="load"><a class="sc" rel="external nofollow" class="videos" href="'.get_permalink().'">';
			} else {
				echo '<a class="sc" rel="external nofollow" class="videos" href="'.get_permalink().'">';
			}
			if (zm_get_option('clipping_thumbnails')) {
				if (zm_get_option('lazy_s')) {
					echo '<img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('img_v_w').'&h='.zm_get_option('img_v_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="';
				} else {
					echo '<img src="';
				}
				echo get_template_directory_uri().'/prune.php?src='.$full_image_url[0].'&w='.zm_get_option('img_v_w').'&h='.zm_get_option('img_v_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.get_the_title().'" width="'.zm_get_option('img_v_w').'" height="'.zm_get_option('img_v_h').'" >';
			} else {
				if (zm_get_option('lazy_s')) {
					echo '<img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('img_v_w').'&h='.zm_get_option('img_v_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="'.$full_image_url[0].'" alt="'.get_the_title().'" width="'.zm_get_option('img_v_w').'" height="'.zm_get_option('img_v_h').'" >';
				} else {
					the_post_thumbnail('content', array('alt' => get_the_title()));
				}
			}
			if (zm_get_option('lazy_s')) {
				echo '<i class="be be-play"></i></a></span>';
			} else {
				echo '<i class="be be-play"></i></a>';
			}
		} else {
			$content = $post->post_content;
			preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
			$n = count($strResult[1]);
			if ($n > 0) {
				if (zm_get_option('lazy_s')) {
					echo '<span class="load"><a class="sc" rel="external nofollow" class="videos" href="'.get_permalink().'"><img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('img_v_w').'&h='.zm_get_option('img_v_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="';
				} else {
					echo '<a class="sc" rel="external nofollow" class="videos" href="'.get_permalink().'"><img src="';
				}

				if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( "img_way") == 'd_img' ) ) {
					echo get_template_directory_uri() . '/prune.php?src=' . $strResult[1][0] . '&w=' . zm_get_option( 'img_v_w' ) . '&h=' . zm_get_option( 'img_v_h' ) . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_v_w' ) . '" height="' . zm_get_option( 'img_v_h' ) . '" /><i class="be be-play"></i></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'o_img' ) {
					echo $strResult[1][0] . '?x-oss-process=image/resize,m_fill,w_' . zm_get_option( 'img_v_w' ) . ' ,h_' . zm_get_option( 'img_v_h' ) . ', limit_0" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_v_w' ) . '" height="' . zm_get_option( 'img_v_h' ) . '" /><i class="be be-play"></i></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'q_img' ) {
					echo $strResult[1][0] . '?imageMogr2/gravity/Center/crop/' . zm_get_option( 'img_v_w' ) . 'x' . zm_get_option( 'img_v_h' ) . '" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_v_w' ) . '" height="' . zm_get_option( 'img_v_h' ) . '" /><i class="be be-play"></i></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'upyun' ) {
					echo $strResult[1][0] . '!/both/' . zm_get_option( 'img_v_w' ) . 'x' . zm_get_option( 'img_v_h' ) . '/format/webp/lossless/true" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_v_w' ) . '" height="' . zm_get_option( 'img_v_h' ) . '" /><i class="be be-play"></i></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'cos_img' ) {
					echo $strResult[1][0] . '?imageView2/1/w/' . zm_get_option( 'img_v_w' ) . '/h/' . zm_get_option( 'img_v_h' ) . '/q/85" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_v_w' ) . '" height="' . zm_get_option( 'img_v_h' ) . '" /><i class="be be-play"></i></a>';
				}

				if (zm_get_option('lazy_s')) {
					echo '</span>';
				}
			} else { 
				if ( zm_get_option('rand_img_n') ) {
					$random = mt_rand(1, zm_get_option('rand_img_n'));
				} else { 
					$random = mt_rand(1, 5);
				}
				if (zm_get_option('clipping_rand_img')) {
					if (zm_get_option('lazy_s')) {
						echo '<span class="load"><a class="sc" rel="external nofollow" class="videos" href="'.get_permalink().'"><img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('img_v_w').'&h='.zm_get_option('img_v_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="';
					} else {
						echo '<a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="';
					}
					echo get_template_directory_uri().'/prune.php?src='.$src.'&w='.zm_get_option('img_v_w').'&h='.zm_get_option('img_v_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.$post->post_title .'" width="'.zm_get_option('img_v_w').'" height="'.zm_get_option('img_v_h').'" /><i class="be be-play"></i></a>';
					if (zm_get_option('lazy_s')) {
						echo '</span>';
					}
				} else { 
					if (zm_get_option('lazy_s')) {
						echo '<span class="load"><a class="sc" rel="external nofollow" class="videos" href="'.get_permalink().'"><img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('img_v_w').'&h='.zm_get_option('img_v_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="'. $src .'" alt="'.$post->post_title .'" /><i class="be be-play"></i></a></span>';
					} else {
						echo '<a class="sc" rel="external nofollow" class="videos" href="'.get_permalink().'"><img src="'. $src .'" alt="'.$post->post_title .'" width="'.zm_get_option('img_v_w').'" height="'.zm_get_option('img_v_h').'" /><i class="be be-play"></i></a>';
					}
				}
			}
		}
	}
}

// 商品缩略图
function tao_thumbnail() {
	global $post;
	$random_img = explode(',' , zm_get_option('random_image_url'));
	$random_img_array = array_rand($random_img);
	$src = $random_img[$random_img_array];

	$fancy_box = get_post_meta( get_the_ID(), 'fancy_box', true );
	if ( get_post_meta( get_the_ID(), 'fancy_box', true ) ) {
		echo '<a class="fancy-box" rel="external nofollow" href="'. $fancy_box . '"></a>';
	}

	$url = get_post_meta(get_the_ID(), 'taourl', true);
	if ( get_post_meta(get_the_ID(), 'thumbnail', true) ) {
		$image = get_post_meta(get_the_ID(), 'thumbnail', true);
		if (zm_get_option('lazy_s')) {
			echo '<span class="load"><a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('img_t_w').'&h='.zm_get_option('img_t_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="';
		} else {
			echo '<a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="';
		}
		if (zm_get_option('manual_thumbnail')) {
			echo get_template_directory_uri().'/prune.php?src='.$image.'&w='.zm_get_option('img_t_w').'&h='.zm_get_option('img_t_h').'&a='.zm_get_option('crop_top').'&zc=1"';
		} else {
			echo $image . '"';
		}
		echo ' alt="'.$post->post_title .'" width="'.zm_get_option('img_t_w').'" height="'.zm_get_option('img_t_h').'" /></a>';
		if (zm_get_option('lazy_s')) {
			echo '</span>';
		}
	} else {
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'tao');
			if (zm_get_option('lazy_s')) {
				echo '<span class="load"><a class="sc" rel="external nofollow" href="'.get_permalink().'">';
			} else {
				echo '<a class="sc" rel="external nofollow" href="'.get_permalink().'">';
			}
			if (zm_get_option('clipping_thumbnails')) {
				if (zm_get_option('lazy_s')) {
					echo '<img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('img_t_w').'&h='.zm_get_option('img_t_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="';
				} else {
					echo '<img src="';
				}
				echo get_template_directory_uri().'/prune.php?src='.$full_image_url[0].'&w='.zm_get_option('img_t_w').'&h='.zm_get_option('img_t_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.get_the_title().'" width="'.zm_get_option('img_t_w').'" height="'.zm_get_option('img_t_h').'" >';
			} else {
				if (zm_get_option('lazy_s')) {
					echo '<img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('img_t_w').'&h='.zm_get_option('img_t_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="'.$full_image_url[0].'" alt="'.get_the_title().'" width="'.zm_get_option('img_t_w').'" height="'.zm_get_option('img_t_h').'">';
				} else {
					the_post_thumbnail('tao', array('alt' => get_the_title()));
				}
			}
			if (zm_get_option('lazy_s')) {
				echo '</a></span>';
			} else {
				echo '</a>';
			}
		} else {
			$content = $post->post_content;
			preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
			$n = count($strResult[1]);
			if ($n > 0) {
				if (zm_get_option('lazy_s')) {
					echo '<span class="load"><a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('img_t_w').'&h='.zm_get_option('img_t_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="';
				} else {
					echo '<a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="';
				}

				if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( "img_way") == 'd_img' ) ) {
					echo get_template_directory_uri() . '/prune.php?src=' . $strResult[1][0] . '&w=' . zm_get_option( 'img_t_w' ) . '&h=' . zm_get_option( 'img_t_h' ) . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_t_w' ) . '" height="' . zm_get_option( 'img_t_h' ) . '" /></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'o_img' ) {
					echo $strResult[1][0] . '?x-oss-process=image/resize,m_fill,w_' . zm_get_option( 'img_t_w' ) . ' ,h_' . zm_get_option( 'img_t_h' ) . ', limit_0" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_t_w' ) . '" height="' . zm_get_option( 'img_t_h' ) . '" /></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'q_img' ) {
					echo $strResult[1][0] . '?imageMogr2/gravity/Center/crop/' . zm_get_option( 'img_t_w' ) . 'x' . zm_get_option( 'img_t_h' ) . '" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_t_w' ) . '" height="' . zm_get_option( 'img_t_h' ) . '" /></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'upyun' ) {
					echo $strResult[1][0] . '!/both/' . zm_get_option( 'img_t_w' ) . 'x' . zm_get_option( 'img_t_h' ) . '/format/webp/lossless/true" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_t_w' ) . '" height="' . zm_get_option( 'img_t_h' ) . '" /></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'cos_img' ) {
					echo $strResult[1][0] . '?imageView2/1/w/' . zm_get_option( 'img_t_w' ) . '/h/' . zm_get_option( 'img_t_h' ) . '/q/85" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_t_w' ) . '" height="' . zm_get_option( 'img_t_h' ) . '" /></a>';
				}

				if (zm_get_option('lazy_s')) {
					echo '</span>';
				}
			} else { 
				if ( zm_get_option('rand_img_n') ) {
					$random = mt_rand(1, zm_get_option('rand_img_n'));
				} else { 
					$random = mt_rand(1, 5);
				}
				if (zm_get_option('clipping_rand_img')) {
					if (zm_get_option('lazy_s')) {
						echo '<span class="load"><a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('img_t_w').'&h='.zm_get_option('img_t_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="';
					} else {
						echo '<a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="';
					}
					echo get_template_directory_uri().'/prune.php?src='.$src.'&w='.zm_get_option('img_t_w').'&h='.zm_get_option('img_t_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.$post->post_title .'" width="'.zm_get_option('img_t_w').'" height="'.zm_get_option('img_t_h').'" /></a>';
					if (zm_get_option('lazy_s')) {
						echo '</span>';
					}
				} else { 
					if (zm_get_option('lazy_s')) {
						echo '<span class="load"><a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('img_t_w').'&h='.zm_get_option('img_t_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="'. $src .'" alt="'.$post->post_title .'" width="'.zm_get_option('img_t_w').'" height="'.zm_get_option('img_t_h').'" /></a></span>';
					} else {
						echo '<a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="'. $src .'" alt="'.$post->post_title .'" width="'.zm_get_option('img_t_w').'" height="'.zm_get_option('img_t_h').'" /></a>';
					}
				}
			}
		}
	}
}

// 图像日志缩略图
function format_image_thumbnail() {
	global $post;
	$content = $post->post_content;
	preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
	$n = count($strResult[1]);
	$img_a = '';
	$img_b = '';
	$img_c = '';
	$img_d = '';
	if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( "img_way") == 'd_img' ) ) {
		$img_a = get_template_directory_uri() . '/prune.php?src=' . $strResult[1][0] . '&w=' . zm_get_option( 'img_w' ) . '&h=' . zm_get_option( 'img_h' ) . '&a='.zm_get_option( 'crop_top' ) . '&zc=1';
		$img_b = get_template_directory_uri() . '/prune.php?src=' . $strResult[1][1] . '&w=' . zm_get_option( 'img_w' ) . '&h=' . zm_get_option( 'img_h' ) . '&a='.zm_get_option( 'crop_top' ) . '&zc=1';
		$img_c = get_template_directory_uri() . '/prune.php?src=' . $strResult[1][2] . '&w=' . zm_get_option( 'img_w' ) . '&h=' . zm_get_option( 'img_h' ) . '&a='.zm_get_option( 'crop_top' ) . '&zc=1';
		$img_d = get_template_directory_uri() . '/prune.php?src=' . $strResult[1][3] . '&w=' . zm_get_option( 'img_w' ) . '&h=' . zm_get_option( 'img_h' ) . '&a='.zm_get_option( 'crop_top' ) . '&zc=1';
	}

	if ( zm_get_option( 'img_way' ) == 'o_img' ) {
		$img_a = $strResult[1][0] . '?x-oss-process=image/resize,m_fill,w_' . zm_get_option( 'img_w' ) . ' ,h_' . zm_get_option( 'img_h' ) . ', limit_0';
		$img_b = $strResult[1][1] . '?x-oss-process=image/resize,m_fill,w_' . zm_get_option( 'img_w' ) . ' ,h_' . zm_get_option( 'img_h' ) . ', limit_0';
		$img_c = $strResult[1][2] . '?x-oss-process=image/resize,m_fill,w_' . zm_get_option( 'img_w' ) . ' ,h_' . zm_get_option( 'img_h' ) . ', limit_0';
		$img_d = $strResult[1][3] . '?x-oss-process=image/resize,m_fill,w_' . zm_get_option( 'img_w' ) . ' ,h_' . zm_get_option( 'img_h' ) . ', limit_0';
	}

	if ( zm_get_option( 'img_way' ) == 'q_img' ) {
		$img_a = $strResult[1][0] . '?imageMogr2/gravity/Center/crop/' . zm_get_option( 'img_w' ) . 'x' . zm_get_option( 'img_h' );
		$img_b = $strResult[1][1] . '?imageMogr2/gravity/Center/crop/' . zm_get_option( 'img_w' ) . 'x' . zm_get_option( 'img_h' );
		$img_c = $strResult[1][2] . '?imageMogr2/gravity/Center/crop/' . zm_get_option( 'img_w' ) . 'x' . zm_get_option( 'img_h' );
		$img_d = $strResult[1][3] . '?imageMogr2/gravity/Center/crop/' . zm_get_option( 'img_w' ) . 'x' . zm_get_option( 'img_h' );
	}

	if ( zm_get_option( 'img_way' ) == 'upyun' ) {
		$img_a = $strResult[1][0] . '!/both/' . zm_get_option( 'img_w' ) . 'x' . zm_get_option( 'img_h' ) . '/format/webp/lossless/true';
		$img_b = $strResult[1][1] . '!/both/' . zm_get_option( 'img_w' ) . 'x' . zm_get_option( 'img_h' ) . '/format/webp/lossless/true';
		$img_c = $strResult[1][2] . '!/both/' . zm_get_option( 'img_w' ) . 'x' . zm_get_option( 'img_h' ) . '/format/webp/lossless/true';
		$img_d = $strResult[1][3] . '!/both/' . zm_get_option( 'img_w' ) . 'x' . zm_get_option( 'img_h' ) . '/format/webp/lossless/true';
	}

	if ( zm_get_option( 'img_way' ) == 'cos_img' ) {
		$img_a = $strResult[1][0] . '?imageView2/1/w/' . zm_get_option( 'img_w' ) . '/h/' . zm_get_option( 'img_h' ) . '/q/85';
		$img_b = $strResult[1][1] . '?imageView2/1/w/' . zm_get_option( 'img_w' ) . '/h/' . zm_get_option( 'img_h' ) . '/q/85';
		$img_c = $strResult[1][2] . '?imageView2/1/w/' . zm_get_option( 'img_w' ) . '/h/' . zm_get_option( 'img_h' ) . '/q/85';
		$img_d = $strResult[1][3] . '?imageView2/1/w/' . zm_get_option( 'img_w' ) . '/h/' . zm_get_option( 'img_h' ) . '/q/85';
	}

	if ($n > 3 ) {
		if (zm_get_option('lazy_s')) {
			echo '<div class="f4"><div class="format-img"><div class="load"><a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('img_w').'&h='.zm_get_option('img_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="' . $img_a . '" alt="'.$post->post_title .'" width="'.zm_get_option('img_w').'" height="'.zm_get_option('img_h').'" /></a></div></div></div>';
			echo '<div class="f4"><div class="format-img"><div class="load"><a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('img_w').'&h='.zm_get_option('img_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="' . $img_b . '" alt="'.$post->post_title .'" width="'.zm_get_option('img_w').'" height="'.zm_get_option('img_h').'" /></a></div></div></div>';
			echo '<div class="f4"><div class="format-img"><div class="load"><a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('img_w').'&h='.zm_get_option('img_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="' . $img_c . '" alt="'.$post->post_title .'" width="'.zm_get_option('img_w').'" height="'.zm_get_option('img_h').'" /></a></div></div></div>';
			echo '<div class="f4"><div class="format-img"><div class="load"><a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('img_w').'&h='.zm_get_option('img_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="' . $img_d . '" alt="'.$post->post_title .'" width="'.zm_get_option('img_w').'" height="'.zm_get_option('img_h').'" /></a></div></div></div>';
		} else {
			echo '<div class="f4"><div class="format-img"><a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="' . $img_a . '" alt="'.$post->post_title .'" width="'.zm_get_option('img_w').'" height="'.zm_get_option('img_h').'" /></a></div></div>';
			echo '<div class="f4"><div class="format-img"><a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="' . $img_b . '" alt="'.$post->post_title .'" width="'.zm_get_option('img_w').'" height="'.zm_get_option('img_h').'" /></a></div></div>';
			echo '<div class="f4"><div class="format-img"><a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="' . $img_c . '" alt="'.$post->post_title .'" width="'.zm_get_option('img_w').'" height="'.zm_get_option('img_h').'" /></a></div></div>';
			echo '<div class="f4"><div class="format-img"><a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="' . $img_d . '" alt="'.$post->post_title .'" width="'.zm_get_option('img_w').'" height="'.zm_get_option('img_h').'" /></a></div></div>';
		}
	} else {
		echo '<div class="f4-tip">文章中至少添加4张图片才能显示</div>';
	}
}

// 图片布局缩略图
function zm_grid_thumbnail() {
	global $post;
	$random_img = explode(',' , zm_get_option('random_image_url'));
	$random_img_array = array_rand($random_img);
	$src = $random_img[$random_img_array];

	$fancy_box = get_post_meta( get_the_ID(), 'fancy_box', true );
	if ( get_post_meta( get_the_ID(), 'fancy_box', true ) ) {
		echo '<a class="fancy-box" rel="external nofollow" href="'. $fancy_box . '"></a>';
	}

	if ( get_post_meta(get_the_ID(), 'thumbnail', true) ) {
		$image = get_post_meta(get_the_ID(), 'thumbnail', true);
		if (zm_get_option('lazy_s')) {
			echo '<span class="load"><a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('grid_w').'&h='.zm_get_option('grid_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="';
		} else {
			echo '<a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="';
		}
		if (zm_get_option('manual_thumbnail')) {
			echo get_template_directory_uri().'/prune.php?src='.$image.'&w='.zm_get_option('grid_w').'&h='.zm_get_option('grid_h').'&a='.zm_get_option('crop_top').'&zc=1"';
		} else {
			echo $image . '"';
		}
		echo ' alt="'.$post->post_title .'" width="'.zm_get_option('grid_w').'" height="'.zm_get_option('grid_h').'" /></a>';
		if (zm_get_option('lazy_s')) {
			echo '</span>';
		}
	} else {
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'content');
			if (zm_get_option('lazy_s')) {
				echo '<span class="load"><a class="sc" rel="external nofollow" href="'.get_permalink().'">';
			} else {
				echo '<a class="sc" rel="external nofollow" href="'.get_permalink().'">';
			}
			if (zm_get_option('clipping_thumbnails')) {
				if (zm_get_option('lazy_s')) {
					echo '<img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('grid_w').'&h='.zm_get_option('grid_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="';
				} else {
					echo '<img src="';
				}
				echo get_template_directory_uri().'/prune.php?src='.$full_image_url[0].'&w='.zm_get_option('grid_w').'&h='.zm_get_option('grid_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.get_the_title().'" width="'.zm_get_option('grid_w').'" height="'.zm_get_option('grid_h').'" >';
			} else {
				if (zm_get_option('lazy_s')) {
					echo '<img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('grid_w').'&h='.zm_get_option('grid_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="'.$full_image_url[0].'" alt="'.get_the_title().'" width="'.zm_get_option('grid_w').'" height="'.zm_get_option('grid_h').'" >';
				} else {
					the_post_thumbnail('content', array('alt' => get_the_title()));
				}
			}
			if (zm_get_option('lazy_s')) {
				echo '</a></span>';
			} else {
				echo '</a>';
			}
		} else {
			$content = $post->post_content;
			preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
			$n = count($strResult[1]);
			if ($n > 0 && !get_post_meta( get_the_ID(), 'rand_img', true )) {
				if (zm_get_option('lazy_s')) {
					echo '<span class="load"><a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('grid_w').'&h='.zm_get_option('grid_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="';
				} else {
					echo '<a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="';
				}

				if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( "img_way") == 'd_img' ) ) {
					echo get_template_directory_uri() . '/prune.php?src=' . $strResult[1][0] . '&w=' . zm_get_option( 'grid_w' ) . '&h=' . zm_get_option( 'grid_h' ) . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1" alt="' . $post->post_title . '" width="' . zm_get_option( 'grid_w' ) . '" height="' . zm_get_option( 'grid_h' ) . '" /></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'o_img' ) {
					echo $strResult[1][0] . '?x-oss-process=image/resize,m_fill,w_' . zm_get_option( 'grid_w' ) . ' ,h_' . zm_get_option( 'grid_h' ) . ', limit_0" alt="' . $post->post_title . '" width="' . zm_get_option( 'grid_w' ) . '" height="' . zm_get_option( 'grid_h' ) . '" /></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'q_img' ) {
					echo $strResult[1][0] . '?imageMogr2/gravity/Center/crop/' . zm_get_option( 'grid_w' ) . 'x' . zm_get_option( 'grid_h' ) . '" alt="' . $post->post_title . '" width="' . zm_get_option( 'grid_w' ) . '" height="' . zm_get_option( 'grid_h' ) . '" /></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'upyun' ) {
					echo $strResult[1][0] . '!/both/' . zm_get_option( 'grid_w' ) . 'x' . zm_get_option( 'grid_h' ) . '/format/webp/lossless/true" alt="' . $post->post_title . '" width="' . zm_get_option( 'grid_w' ) . '" height="' . zm_get_option( 'grid_h' ) . '" /></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'cos_img' ) {
					echo $strResult[1][0] . '?imageView2/1/w/' . zm_get_option( 'grid_w' ) . '/h/' . zm_get_option( 'grid_h' ) . '/q/85" alt="' . $post->post_title . '" width="' . zm_get_option( 'grid_w' ) . '" height="' . zm_get_option( 'grid_h' ) . '" /></a>';
				}

				if (zm_get_option('lazy_s')) {
					echo '</span>';
				}
			} else { 
				if ( zm_get_option('rand_img_n') ) {
					$random = mt_rand(1, zm_get_option('rand_img_n'));
				} else { 
					$random = mt_rand(1, 5);
				}
				if (zm_get_option('clipping_rand_img')) {
					if (zm_get_option('lazy_s')) {
						echo '<span class="load"><a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('grid_w').'&h='.zm_get_option('grid_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="';
					} else {
						echo '<a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="';
					}
					echo get_template_directory_uri().'/prune.php?src='.$src.'&w='.zm_get_option('grid_w').'&h='.zm_get_option('grid_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.$post->post_title .'" width="'.zm_get_option('grid_w').'" height="'.zm_get_option('grid_h').'" /></a>';
					if (zm_get_option('lazy_s')) {
						echo '</span>';
					}
				} else { 
					if (zm_get_option('lazy_s')) {
						echo '<span class="load"><a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('grid_w').'&h='.zm_get_option('grid_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="'. $src .'" alt="'.$post->post_title .'" width="'.zm_get_option('grid_w').'" height="'.zm_get_option('grid_h').'" /></a></span>';
					} else {
						echo '<a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="'. $src .'" alt="'.$post->post_title .'" width="'.zm_get_option('grid_w').'" height="'.zm_get_option('grid_h').'" /></a>';
					}
				}
			}
		}
	}
}

// 宽缩略图分类
function zm_full_thumbnail() {
	$random_img = explode(',' , zm_get_option('random_long_url'));
	$random_img_array = array_rand($random_img);
	$src = $random_img[$random_img_array];
	global $post;
	if ( get_post_meta(get_the_ID(), 'full_thumbnail', true) ) {
		$image = get_post_meta(get_the_ID(), 'full_thumbnail', true);
		if (zm_get_option('lazy_s')) {
			echo '<span class="load"><a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('img_full_w').'&h='.zm_get_option('img_full_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="';
		} else {
			echo '<a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="';
		}
		if (zm_get_option('manual_thumbnail')) {
			echo get_template_directory_uri().'/prune.php?src='.$image.'&w='.zm_get_option('img_full_w').'&h='.zm_get_option('img_full_h').'&a='.zm_get_option('crop_top').'&zc=1"';
		} else {
			echo $image . '"';
		}
		echo ' alt="'.$post->post_title .'" width="'.zm_get_option('img_full_w').'" height="'.zm_get_option('img_full_h').'" /></a>';
		if (zm_get_option('lazy_s')) {
			echo '</span>';
		}
	} else {
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'content');
			if (zm_get_option('lazy_s')) {
				echo '<span class="load"><a class="sc" rel="external nofollow" href="'.get_permalink().'">';
			} else {
				echo '<a class="sc" rel="external nofollow" href="'.get_permalink().'">';
			}
			if (zm_get_option('clipping_thumbnails')) {
				if (zm_get_option('lazy_s')) {
					echo '<img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('img_full_w').'&h='.zm_get_option('img_full_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="';
				} else {
					echo '<img src="';
				}
				echo get_template_directory_uri().'/prune.php?src='.$full_image_url[0].'&w='.zm_get_option('img_full_w').'&h='.zm_get_option('img_full_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.get_the_title().'" width="'.zm_get_option('img_full_w').'" height="'.zm_get_option('img_full_h').'" >';
			} else {
				if (zm_get_option('lazy_s')) {
					echo '<img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('img_full_w').'&h='.zm_get_option('img_full_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="'.$full_image_url[0].'" alt="'.get_the_title().'" width="'.zm_get_option('img_full_w').'" height="'.zm_get_option('img_full_h').'">';
				} else {
					the_post_thumbnail('content', array('alt' => get_the_title()));
				}
			}
			if (zm_get_option('lazy_s')) {
				echo '</a></span>';
			} else {
				echo '</a>';
			}
		} else {
			$content = $post->post_content;
			preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
			$n = count($strResult[1]);
			if ($n > 0 && !get_post_meta( get_the_ID(), 'rand_img', true )) {
				if (zm_get_option('lazy_s')) {
					echo '<span class="load"><a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('img_full_w').'&h='.zm_get_option('img_full_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="';
				} else {
					echo '<a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="';
				}

				if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( "img_way") == 'd_img' ) ) {
					echo get_template_directory_uri() . '/prune.php?src=' . $strResult[1][0] . '&w=' . zm_get_option( 'img_full_w' ) . '&h=' . zm_get_option( 'img_full_h' ) . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_full_w' ) . '" height="' . zm_get_option( 'img_full_h' ) . '" /></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'o_img' ) {
					echo $strResult[1][0] . '?x-oss-process=image/resize,m_fill,w_' . zm_get_option( 'img_full_w' ) . ' ,h_' . zm_get_option( 'img_full_h' ) . ', limit_0" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_full_w' ) . '" height="' . zm_get_option( 'img_full_h' ) . '" /></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'q_img' ) {
					echo $strResult[1][0] . '?imageMogr2/gravity/Center/crop/' . zm_get_option( 'img_full_w' ) . 'x' . zm_get_option( 'img_full_h' ) . '" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_full_w' ) . '" height="' . zm_get_option( 'img_full_h' ) . '" /></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'upyun' ) {
					echo $strResult[1][0] . '!/both/' . zm_get_option( 'img_full_w' ) . 'x' . zm_get_option( 'img_full_h' ) . '/format/webp/lossless/true" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_full_w' ) . '" height="' . zm_get_option( 'img_full_h' ) . '" /></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'cos_img' ) {
					echo $strResult[1][0] . '?imageView2/1/w/' . zm_get_option( 'img_full_w' ) . '/h/' . zm_get_option( 'img_full_h' ) . '/q/85" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_full_w' ) . '" height="' . zm_get_option( 'img_full_h' ) . '" /></a>';
				}

				if (zm_get_option('lazy_s')) {
					echo '</span>';
				}
			} else { 
				if ( zm_get_option('rand_img_n') ) {
					$random = mt_rand(1, zm_get_option('rand_img_n'));
				} else { 
					$random = mt_rand(1, 5);
				}
				if (zm_get_option('clipping_rand_img')) {
					if (zm_get_option('lazy_s')) {
						echo '<span class="load"><a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('img_full_w').'&h='.zm_get_option('img_full_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="';
					} else {
						echo '<a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="';
					}
					echo get_template_directory_uri().'/prune.php?src='.$src.'&w='.zm_get_option('img_full_w').'&h='.zm_get_option('img_full_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.$post->post_title .'" width="'.zm_get_option('img_full_w').'" height="'.zm_get_option('img_full_h').'" /></a>';

					if (zm_get_option('lazy_s')) {
						echo '</span>';
					}
				} else { 
					if (zm_get_option('lazy_s')) {
						echo '<span class="load"><a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('img_full_w').'&h='.zm_get_option('img_full_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="'. $src .'" alt="'.$post->post_title .'" width="'.zm_get_option('img_full_w').'" height="'.zm_get_option('img_full_h').'" /></a></span>';
					} else {
						echo '<a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="'. $src .'" alt="'.$post->post_title .'" width="'.zm_get_option('img_full_w').'" height="'.zm_get_option('img_full_h').'" /></a>';
					}
				}
			}
		}
	}
}

// 公司左右图
function gr_wd_thumbnail() {
	$random_img = explode(',' , zm_get_option('random_image_url'));
	$random_img_array = array_rand($random_img);
	$src = $random_img[$random_img_array];
	global $post;
	if ( get_post_meta(get_the_ID(), 'wd_img', true) ) {
		$image = get_post_meta(get_the_ID(), 'wd_img', true);
		if (zm_get_option('lazy_s')) {
			echo '<span class="load"><a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w=700&h=380&a='.zm_get_option('crop_top').'&zc=1" data-original="';
		} else {
			echo '<a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="';
		}
		if (zm_get_option('manual_thumbnail')) {
			echo get_template_directory_uri().'/prune.php?src='.$image.'&w=700&h=380&a='.zm_get_option('crop_top').'&zc=1"';
		} else {
			echo $image . '"';
		}
		echo ' alt="'.$post->post_title .'" width="700" height="380" /></a>';
		if (zm_get_option('lazy_s')) {
			echo '</span>';
		}
	} else {
		$content = $post->post_content;
		preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
		$n = count($strResult[1]);
		if ($n > 0) {
			if (zm_get_option('lazy_s')) {
				echo '<span class="load"><a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w=700&h=380&a='.zm_get_option('crop_top').'&zc=1" data-original="';
			} else {
				echo '<a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="';
			}

			if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( "img_way") == 'd_img' ) ) {
				echo get_template_directory_uri() . '/prune.php?src=' . $strResult[1][0] . '&w=700&h=380&a=' . zm_get_option( 'crop_top' ) . '&zc=1" alt="' . $post->post_title . '" width="700" height="380" /></a>';
			}

			if ( zm_get_option( 'img_way' ) == 'o_img' ) {
				echo $strResult[1][0] . '?x-oss-process=image/resize,m_fill,w_700,h_380, limit_0" alt="' . $post->post_title . '" width="700" height="380" /></a>';
			}

			if ( zm_get_option( 'img_way' ) == 'q_img' ) {
				echo $strResult[1][0] . '?imageMogr2/gravity/Center/crop/700x380" alt="' . $post->post_title . '" width="700" height="380" /></a>';
			}

			if ( zm_get_option( 'img_way' ) == 'upyun' ) {
				echo $strResult[1][0] . '!/both/700x380/format/webp/lossless/true" alt="' . $post->post_title . '" width="700" height="380" /></a>';
			}

			if ( zm_get_option( 'img_way' ) == 'cos_img' ) {
				echo $strResult[1][0] . '?imageView2/1/w/700/h/380/q/85" alt="' . $post->post_title . '" width="700" height="380" /></a>';
			}

			if (zm_get_option('lazy_s')) {
				echo '</span>';
			}
		} else { 
			if ( zm_get_option('rand_img_n') ) {
				$random = mt_rand(1, zm_get_option('rand_img_n'));
			} else { 
				$random = mt_rand(1, 5);
			}
			if (zm_get_option('clipping_rand_img')) {
				if (zm_get_option('lazy_s')) {
					echo '<span class="load"><a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w=700&h=380&a='.zm_get_option('crop_top').'&zc=1" data-original="';
				} else {
					echo '<a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="';
				}
				echo get_template_directory_uri().'/prune.php?src='.$src.'&w=700&h=380&a='.zm_get_option('crop_top').'&zc=1" alt="'.$post->post_title .'" width="700" height="380" /></a>';
				if (zm_get_option('lazy_s')) {
					echo '</span>';
				}
			} else { 
				if (zm_get_option('lazy_s')) {
					echo '<span class="load"><a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w=700&h=380&a='.zm_get_option('crop_top').'&zc=1" data-original="'. $src .'" alt="'.$post->post_title .'" width="700" height="380" /></a></span>';
				} else {
					echo '<a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src="'. $src .'" alt="'.$post->post_title .'" width="700" height="380" /></a>';
				}
			}
		}
	}
}

// 链接形式
function zm_thumbnail_link() {
	global $post;
	$random_img = explode(',' , zm_get_option('random_image_url'));
	$random_img_array = array_rand($random_img);
	$src = $random_img[$random_img_array];

	$fancy_box = get_post_meta( get_the_ID(), 'fancy_box', true );
	if ( get_post_meta( get_the_ID(), 'fancy_box', true ) ) {
		echo '<a class="fancy-box" rel="external nofollow" href="'. $fancy_box . '"></a>';
	}
	$direct = get_post_meta(get_the_ID(), 'direct', true);

	if ( get_post_meta(get_the_ID(), 'thumbnail', true) ) {
		$image = get_post_meta(get_the_ID(), 'thumbnail', true);
		if (zm_get_option('lazy_s')) {
			echo '<span class="load"><a class="sc" rel="external nofollow" href="'.$direct.'"><img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('img_w').'&h='.zm_get_option('img_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="';
		} else {
			echo '<a class="sc" rel="external nofollow" href="'.$direct.'"><img src="';
		}
		if (zm_get_option('manual_thumbnail')) {
			echo get_template_directory_uri().'/prune.php?src='.$image.'&w='.zm_get_option('img_w').'&h='.zm_get_option('img_h').'&a='.zm_get_option('crop_top').'&zc=1"';
		} else {
			echo $image . '"';
		}
		echo ' alt="'.$post->post_title .'" width="'.zm_get_option('img_w').'" height="'.zm_get_option('img_h').'" /></a>';
		if (zm_get_option('lazy_s')) {
			echo '</span>';
		}
	} else {
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'content');
			if (zm_get_option('lazy_s')) {
				echo '<span class="load"><a class="sc" rel="external nofollow" href="'.$direct.'">';
			} else {
				echo '<a class="sc" rel="external nofollow" href="'.$direct.'">';
			}
			if (zm_get_option('clipping_thumbnails')) {
				if (zm_get_option('lazy_s')) {
					echo '<img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('img_w').'&h='.zm_get_option('img_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="';
				} else {
					echo '<img src="';
				}
				echo get_template_directory_uri().'/prune.php?src='.$full_image_url[0].'&w='.zm_get_option('img_w').'&h='.zm_get_option('img_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.get_the_title().'" width="'.zm_get_option('img_w').'" height="'.zm_get_option('img_h').'" >';
			} else {
				if (zm_get_option('lazy_s')) {
					echo '<img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('img_w').'&h='.zm_get_option('img_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="'.$full_image_url[0].'" alt="'.get_the_title().'" width="'.zm_get_option('img_w').'" height="'.zm_get_option('img_h').'" >';
				} else {
					the_post_thumbnail('content', array('alt' => get_the_title()));
				}
			}
			if (zm_get_option('lazy_s')) {
				echo '</a></span>';
			} else {
				echo '</a>';
			}
		} else {
			$content = $post->post_content;
			preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
			$n = count($strResult[1]);
			if ($n > 0 && !get_post_meta( get_the_ID(), 'rand_img', true )) {
				if (zm_get_option('lazy_s')) {
					echo '<span class="load"><a class="sc" rel="external nofollow" href="'.$direct.'"><img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('img_w').'&h='.zm_get_option('img_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="';
				} else {
					echo '<a class="sc" rel="external nofollow" href="'.$direct.'"><img src="';
				}

				if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( "img_way") == 'd_img' ) ) {
					echo get_template_directory_uri() . '/prune.php?src=' . $strResult[1][0] . '&w=' . zm_get_option( 'img_w' ) . '&h=' . zm_get_option( 'img_h' ) . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_w' ) . '" height="' . zm_get_option( 'img_h' ) . '" /></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'o_img' ) {
					echo $strResult[1][0] . '?x-oss-process=image/resize,m_fill,w_' . zm_get_option( 'img_w' ) . ' ,h_' . zm_get_option( 'img_h' ) . ', limit_0" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_w' ) . '" height="' . zm_get_option( 'img_h' ) . '" /></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'q_img' ) {
					echo $strResult[1][0] . '?imageMogr2/gravity/Center/crop/' . zm_get_option( 'img_w' ) . 'x' . zm_get_option( 'img_h' ) . '" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_w' ) . '" height="' . zm_get_option( 'img_h' ) . '" /></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'upyun' ) {
					echo $strResult[1][0] . '!/both/' . zm_get_option( 'img_w' ) . 'x' . zm_get_option( 'img_h' ) . '/format/webp/lossless/true" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_w' ) . '" height="' . zm_get_option( 'img_h' ) . '" /></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'cos_img' ) {
					echo $strResult[1][0] . '?imageView2/1/w/' . zm_get_option( 'img_w' ) . '/h/' . zm_get_option( 'img_h' ) . '/q/85" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_w' ) . '" height="' . zm_get_option( 'img_h' ) . '" /></a>';
				}

				if (zm_get_option('lazy_s')) {
					echo '</span>';
				}
			} else { 
				if ( zm_get_option('rand_img_n') ) {
					$random = mt_rand(1, zm_get_option('rand_img_n'));
				} else { 
					$random = mt_rand(1, 5);
				}
				if (zm_get_option('clipping_rand_img')) {
					if (zm_get_option('lazy_s')) {
						echo '<span class="load"><a class="sc" rel="external nofollow" href="'.$direct.'"><img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('img_w').'&h='.zm_get_option('img_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="';
					} else {
						echo '<a class="sc" rel="external nofollow" href="'.$direct.'"><img src="';
					}
					echo get_template_directory_uri().'/prune.php?src='.$src.'&w='.zm_get_option('img_w').'&h='.zm_get_option('img_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.$post->post_title .'" width="'.zm_get_option('img_w').'" height="'.zm_get_option('img_h').'" /></a>';
					if (zm_get_option('lazy_s')) {
						echo '</span>';
					}
				} else { 
					if (zm_get_option('lazy_s')) {
						echo '<span class="load"><a class="sc" rel="external nofollow" href="'.$direct.'"><img src="'. get_template_directory_uri().'/prune.php?src=' . get_template_directory_uri() . '/img/loading.png&w='.zm_get_option('img_w').'&h='.zm_get_option('img_h').'&a='.zm_get_option('crop_top').'&zc=1" data-original="'. $src .'" alt="'.$post->post_title .'" width="'.zm_get_option('img_w').'" height="'.zm_get_option('img_h').'" /></a></span>';
					} else {
						echo '<a class="sc" rel="external nofollow" href="'.$direct.'"><img src="'. $src .'" alt="'.$post->post_title .'" width="'.zm_get_option('img_w').'" height="'.zm_get_option('img_h').'" /></a>';
					}
				}
			}
		}
	}
}

// 幻灯小工具
function zm_widge_thumbnail() {
	global $post;
	if ( get_post_meta(get_the_ID(), 'widge_img', true) ) {
		$image = get_post_meta(get_the_ID(), 'widge_img', true);
		echo '<a class="sc" rel="external nofollow" href="'.get_permalink().'"><img src=';
		echo get_template_directory_uri().'/prune.php?src='.$image.'&w='.zm_get_option('img_s_w').'&h='.zm_get_option('img_s_h').'&a='.zm_get_option('crop_top').'&zc=1';
		echo ' alt="'.$post->post_title .'" width="'.zm_get_option('img_s_w').'" height="'.zm_get_option('img_s_h').'" /></a>';
	} else {
		$content = $post->post_content;
		preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
		$n = count($strResult[1]);
		if ($n > 0) {

			if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( "img_way") == 'd_img' ) ) {
				echo '<a class="sc" rel="external nofollow" href="' . get_permalink() . '"><img class="owl-lazy" data-src="' . get_template_directory_uri() . '/prune.php?src=' . $strResult[1][0] . '&w=' . zm_get_option( 'img_s_w' ) . '&h=' . zm_get_option( 'img_s_h' ) . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_s_w' ) . '" height="' . zm_get_option( 'img_s_h' ) . '" /></a>';
			}

			if ( zm_get_option( 'img_way' ) == 'o_img' ) {
				echo '<a class="sc" rel="external nofollow" href="' . get_permalink() . '"><img class="owl-lazy" data-src="' . $strResult[1][0] . '?x-oss-process=image/resize,m_fill,w_' . zm_get_option( 'img_s_w' ) . ' ,h_' . zm_get_option( 'img_s_h' ) . ', limit_0" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_s_w' ) . '" height="' . zm_get_option( 'img_s_h' ) . '" /></a>';
			}

			if ( zm_get_option( 'img_way' ) == 'q_img' ) {
				echo '<a class="sc" rel="external nofollow" href="' . get_permalink() . '"><img class="owl-lazy" data-src="' . $strResult[1][0] . '?imageMogr2/gravity/Center/crop/' . zm_get_option( 'img_s_w' ) . 'x' . zm_get_option( 'img_s_h' ) . '" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_s_w' ) . '" height="' . zm_get_option( 'img_s_h' ) . '" /></a>';
			}

			if ( zm_get_option( 'img_way' ) == 'upyun' ) {
				echo '<a class="sc" rel="external nofollow" href="' . get_permalink() . '"><img class="owl-lazy" data-src="' . $strResult[1][0] . '!/both/' . zm_get_option( 'img_s_w' ) . 'x' . zm_get_option( 'img_s_h' ) . '/format/webp/lossless/true" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_s_w' ) . '" height="' . zm_get_option( 'img_s_h' ) . '" /></a>';
			}

			if ( zm_get_option( 'img_way' ) == 'cos_img' ) {
				echo '<a class="sc" rel="external nofollow" href="' . get_permalink() . '"><img class="owl-lazy" data-src="' . $strResult[1][0] . '?imageView2/1/w/' . zm_get_option( 'img_s_w' ) . '/h/' . zm_get_option( 'img_s_h' ) . '/q/85" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_s_w' ) . '" height="' . zm_get_option( 'img_s_h' ) . '" /></a>';
			}
		}
	}
}

// 图片滚动
function zm_thumbnail_scrolling() {
	global $post;
	$random_img = explode(',' , zm_get_option('random_image_url'));
	$random_img_array = array_rand($random_img);
	$src = $random_img[$random_img_array];

	$fancy_box = get_post_meta( get_the_ID(), 'fancy_box', true );
	if ( get_post_meta( get_the_ID(), 'fancy_box', true ) ) {
		echo '<a class="fancy-box" rel="external nofollow" href="'. $fancy_box . '"></a>';
	}

	if ( get_post_meta(get_the_ID(), 'thumbnail', true) ) {
		$image = get_post_meta(get_the_ID(), 'thumbnail', true);
		echo '<a class="sc" rel="external nofollow" href="'.get_permalink().'"><img class="owl-lazy" data-src="';
		if (zm_get_option('manual_thumbnail')) {
			echo get_template_directory_uri().'/prune.php?src='.$image.'&w='.zm_get_option('img_w').'&h='.zm_get_option('img_h').'&a='.zm_get_option('crop_top').'&zc=1"';
		} else {
			echo $image . '"';
		}
		echo ' alt="'.$post->post_title .'" width="'.zm_get_option('img_w').'" height="'.zm_get_option('img_h').'" /></a>';
	} else {
		if ( has_post_thumbnail() ) {
			echo '<a class="sc" rel="external nofollow" href="'.get_permalink().'">';
			if (zm_get_option('clipping_thumbnails')) {
				$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'content');
				echo '<img src="'.get_template_directory_uri().'/prune.php?src='.$full_image_url[0].'&w='.zm_get_option('img_w').'&h='.zm_get_option('img_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.get_the_title().'" width="'.zm_get_option('img_w').'" height="'.zm_get_option('img_h').'" >';
			} else {
				the_post_thumbnail('content', array('alt' => get_the_title()));
			}
			echo '</a>';
		} else {
			$content = $post->post_content;
			preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
			$n = count($strResult[1]);
			if ($n > 0) {

			if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( "img_way") == 'd_img' ) ) {
				echo '<a class="sc" rel="external nofollow" href="' . get_permalink() . '"><img class="owl-lazy" data-src="' . get_template_directory_uri() . '/prune.php?src=' . $strResult[1][0] . '&w=' . zm_get_option( 'img_w' ) . '&h=' . zm_get_option( 'img_h' ) . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_w' ) . '" height="' . zm_get_option( 'img_h' ) . '" /></a>';
			}

			if ( zm_get_option( 'img_way' ) == 'o_img' ) {
				echo '<a class="sc" rel="external nofollow" href="' . get_permalink() . '"><img class="owl-lazy" data-src="' . get_template_directory_uri() . '/prune.php?src=' . $strResult[1][0] . '?x-oss-process=image/resize,m_fill,w_' . zm_get_option( 'img_w' ) . ' ,h_' . zm_get_option( 'img_h' ) . ', limit_0" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_w' ) . '" height="' . zm_get_option( 'img_h' ) . '" /></a>';
			}

			if ( zm_get_option( 'img_way' ) == 'q_img' ) {
				echo '<a class="sc" rel="external nofollow" href="' . get_permalink() . '"><img class="owl-lazy" data-src="' . get_template_directory_uri() . '/prune.php?src=' . $strResult[1][0] . '?imageMogr2/gravity/Center/crop/' . zm_get_option( 'img_w' ) . 'x' . zm_get_option( 'img_h' ) . '" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_w' ) . '" height="' . zm_get_option( 'img_h' ) . '" /></a>';
			}

			if ( zm_get_option( 'img_way' ) == 'upyun' ) {
				echo '<a class="sc" rel="external nofollow" href="' . get_permalink() . '"><img class="owl-lazy" data-src="' . get_template_directory_uri() . '/prune.php?src=' . $strResult[1][0] . '!/both/' . zm_get_option( 'img_w' ) . 'x' . zm_get_option( 'img_h' ) . '/format/webp/lossless/true" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_w' ) . '" height="' . zm_get_option( 'img_h' ) . '" /></a>';
			}

			if ( zm_get_option( 'img_way' ) == 'cos_img' ) {
				echo '<a class="sc" rel="external nofollow" href="' . get_permalink() . '"><img class="owl-lazy" data-src="' . get_template_directory_uri() . '/prune.php?src=' . $strResult[1][0] . '?imageView2/1/w/' . zm_get_option( 'img_w' ) . '/h/' . zm_get_option( 'img_h' ) . '/q/85" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_w' ) . '" height="' . zm_get_option( 'img_h' ) . '" /></a>';
			}

			} else { 
				if ( zm_get_option('rand_img_n') ) {
					$random = mt_rand(1, zm_get_option('rand_img_n'));
				} else {
					$random = mt_rand(1, 5);
				}
				if (zm_get_option('clipping_rand_img')) {
					echo '<a class="sc" rel="external nofollow" href="'.get_permalink().'"><img class="owl-lazy" data-src="'.get_template_directory_uri().'/prune.php?src='.$src.'&w='.zm_get_option('img_w').'&h='.zm_get_option('img_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.$post->post_title .'" width="'.zm_get_option('img_w').'" height="'.zm_get_option('img_h').'" /></a>';
				} else { 
					echo '<a class="sc" rel="external nofollow" href="'.get_permalink().'"><img class="owl-lazy" data-src="'. $src .'" alt="'.$post->post_title .'" width="'.zm_get_option('img_w').'" height="'.zm_get_option('img_h').'" /></a>';
				}
			}
		}
	}
}

} else {

// 不裁剪
function zm_thumbnail() {
	global $post;
	$content = $post->post_content;
	preg_match_all( '/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER );
	$n = count( $strResult[1] );
	if ( $n > 0 ) {
		if ( zm_get_option( 'lazy_s' ) ) {
			$be_get_img = 'data-src="' . $strResult[1][0] . '"';
		} else {
			$be_get_img = 'style="background-image: url(' . $strResult[1][0] . ');"';
		}
	}
	$random_img = explode(',' , zm_get_option('random_image_url'));
	$random_img_array = array_rand($random_img);
	$src = $random_img[$random_img_array];

	$fancy_box = get_post_meta( get_the_ID(), 'fancy_box', true );
	if ( get_post_meta( get_the_ID(), 'fancy_box', true ) ) {
		echo '<a class="fancy-box" rel="external nofollow" href="'. $fancy_box . '"></a>';
	}

	echo '<div class="thumbs-b lazy">';
	// 手动
	if ( get_post_meta( get_the_ID(), 'thumbnail', true ) ) {
		$image = get_post_meta(get_the_ID(), 'thumbnail', true );
		echo '<a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" data-src="'.$image.'"></a>';
	} else {
		// 特色
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'content');
			echo '<a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" data-src="'.$full_image_url[0].'"></a>';
		} else {
			// 自动
			if ( $n > 0 && !get_post_meta( get_the_ID(), 'rand_img', true ) ) {
				echo '<a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" ' . $be_get_img . '></a>';
			} else { 
				// 随机
				if ( zm_get_option('rand_img_n') ) {
					$random = mt_rand(1, zm_get_option('rand_img_n'));
				} else { 
					$random = mt_rand(1, 5);
				}
				echo '<a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" data-src="'.$src.'"></a>';
			}
		}
	}
	echo '</div>';
}

// 分类模块宽缩略图
function zm_long_thumbnail() {
	global $post;
	$content = $post->post_content;
	preg_match_all( '/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER );
	$n = count( $strResult[1] );
	if ( $n > 0 ) {
		if ( zm_get_option( 'lazy_s' ) ) {
			$be_get_img = 'data-src="' . $strResult[1][0] . '"';
		} else {
			$be_get_img = 'style="background-image: url(' . $strResult[1][0] . ');"';
		}
	}
	$random_img = explode(',' , zm_get_option('random_long_url'));
	$random_img_array = array_rand($random_img);
	$src = $random_img[$random_img_array];
	echo '<div class="thumbs-f lazy">';
	if ( get_post_meta(get_the_ID(), 'long_thumbnail', true) ) {
		$image = get_post_meta(get_the_ID(), 'long_thumbnail', true);
		echo '<a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" data-src="'.$image.'"></a>';
	} else {
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'long');
			echo '<a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" data-src="'.$full_image_url[0].'"></a>';
		} else {
			if ( $n > 0 && !get_post_meta( get_the_ID(), 'rand_img', true  ) ) {
				echo '<a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" ' . $be_get_img . '></a>';
			} else { 
				if ( zm_get_option('rand_img_n') ) {
					$random = mt_rand(1, zm_get_option('rand_img_n'));
				} else { 
					$random = mt_rand(1, 5);
				}
				echo '<a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" data-src="'.$src.'"></a>';
			}
		}
	}
	echo '</div>';
}

// 图片缩略图
function img_thumbnail() {
	global $post;
	$content = $post->post_content;
	preg_match_all( '/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER );
	$n = count( $strResult[1] );
	if ( $n > 0 ) {
		if ( zm_get_option( 'lazy_s' ) ) {
			$be_get_img = 'data-src="' . $strResult[1][0] . '"';
		} else {
			$be_get_img = 'style="background-image: url(' . $strResult[1][0] . ');"';
		}
	}
	$random_img = explode(',' , zm_get_option('random_image_url'));
	$random_img_array = array_rand($random_img);
	$src = $random_img[$random_img_array];

	$fancy_box = get_post_meta( get_the_ID(), 'fancy_box', true );
	if ( get_post_meta( get_the_ID(), 'fancy_box', true ) ) {
		echo '<a class="fancy-box" rel="external nofollow" href="'. $fancy_box . '"></a>';
	}

	echo '<div class="thumbs-i lazy">';
	if ( get_post_meta(get_the_ID(), 'thumbnail', true) ) {
		$image = get_post_meta(get_the_ID(), 'thumbnail', true);
		echo '<a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" data-src="'.$image.'"></a>';
	} else {
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'content');
			echo '<a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" data-src="'.$full_image_url[0].'"></a>';
		} else {
			if ( $n > 0 ) {
				echo '<a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" ' . $be_get_img . '></a>';
			} else { 
				if ( zm_get_option('rand_img_n') ) {
					$random = mt_rand(1, zm_get_option('rand_img_n'));
				} else { 
					$random = mt_rand(1, 5);
				}
				echo '<a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" data-src="'.$src.'"></a>';
			}
		}
	}
	echo '</div>';
}

// 视频缩略图
function videos_thumbnail() {
	global $post;
	$content = $post->post_content;
	preg_match_all( '/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER );
	$n = count( $strResult[1] );
	if ( $n > 0 ) {
		if ( zm_get_option( 'lazy_s' ) ) {
			$be_get_img = 'data-src="' . $strResult[1][0] . '"';
		} else {
			$be_get_img = 'style="background-image: url(' . $strResult[1][0] . ');"';
		}
	}
	$random_img = explode(',' , zm_get_option('random_image_url'));
	$random_img_array = array_rand($random_img);
	$src = $random_img[$random_img_array];
	echo '<div class="thumbs-v lazy">';
	if ( get_post_meta(get_the_ID(), 'small', true) ) {
		$image = get_post_meta(get_the_ID(), 'small', true);
		echo '<a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" data-src="'.$image.'"></a>';
	} else {
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'content');
			echo '<a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" data-src="'.$full_image_url[0].'"></a>';
		} else {
			if ( $n > 0 ) {
				echo '<a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" ' . $be_get_img . '></a>';
			} else { 
				if ( zm_get_option('rand_img_n') ) {
					$random = mt_rand(1, zm_get_option('rand_img_n'));
				} else { 
					$random = mt_rand(1, 5);
				}
				echo '<a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" data-src="'.$src.'"></a>';
			}
		}
	}
	echo '</div>';
}

// 商品缩略图
function tao_thumbnail() {
	global $post;
	$content = $post->post_content;
	preg_match_all( '/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER );
	$n = count( $strResult[1] );
	if ( $n > 0 ) {
		if ( zm_get_option( 'lazy_s' ) ) {
			$be_get_img = 'data-src="' . $strResult[1][0] . '"';
		} else {
			$be_get_img = 'style="background-image: url(' . $strResult[1][0] . ');"';
		}
	}
	$random_img = explode(',' , zm_get_option('random_image_url'));
	$random_img_array = array_rand($random_img);
	$src = $random_img[$random_img_array];

	$fancy_box = get_post_meta( get_the_ID(), 'fancy_box', true );
	if ( get_post_meta( get_the_ID(), 'fancy_box', true ) ) {
		echo '<a class="fancy-box" rel="external nofollow" href="'. $fancy_box . '"></a>';
	}

	echo '<div class="thumbs-t lazy">';
	$url = get_post_meta(get_the_ID(), 'taourl', true);
	if ( get_post_meta(get_the_ID(), 'thumbnail', true) ) {
		$image = get_post_meta(get_the_ID(), 'thumbnail', true);
		echo '<a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" data-src="'.$image.'"></a>';
	} else {
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'tao');
			echo '<a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" data-src="'.$full_image_url[0].'"></a>';
		} else {
			$content = $post->post_content;
			preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
			if ( $n > 0 ) {
				echo '<a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" ' . $be_get_img . '></a>';
			} else { 
				if ( zm_get_option('rand_img_n') ) {
					$random = mt_rand(1, zm_get_option('rand_img_n'));
				} else { 
					$random = mt_rand(1, 5);
				}
				echo '<a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" data-src="'.$src.'"></a>';
			}
		}
	}
	echo '</div>';
}

// 图像日志缩略图
function format_image_thumbnail() {
	global $post;
	$content = $post->post_content;
	preg_match_all( '/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER );
	if ( zm_get_option( 'lazy_s' ) ) {
		$be_get_img_a = 'data-src="' . $strResult[1][0] . '"';
		$be_get_img_b = 'data-src="' . $strResult[1][1] . '"';
		$be_get_img_c = 'data-src="' . $strResult[1][2] . '"';
		$be_get_img_d = 'data-src="' . $strResult[1][3] . '"';
	} else {
		$be_get_img_a = 'style="background-image: url(' . $strResult[1][0] . ');"';
		$be_get_img_b = 'style="background-image: url(' . $strResult[1][1] . ');"';
		$be_get_img_c = 'style="background-image: url(' . $strResult[1][2] . ');"';
		$be_get_img_d = 'style="background-image: url(' . $strResult[1][3] . ');"';
	}
	echo '<div class="thumbs-four">';
	$n = count($strResult[1]);
	if ( $n > 3 ) {
		echo '<div class="f4"><div class="format-img"><div class="thumbs-b lazy"><a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" ' . $be_get_img_a . '></a></div></div></div>';
		echo '<div class="f4"><div class="format-img"><div class="thumbs-b lazy"><a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" ' . $be_get_img_b . '></a></div></div></div>';
		echo '<div class="f4"><div class="format-img"><div class="thumbs-b lazy"><a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" ' . $be_get_img_c . '></a></div></div></div>';
		echo '<div class="f4"><div class="format-img"><div class="thumbs-b lazy"><a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" ' . $be_get_img_d . '></a></div></div></div>';
	} else {
		echo '<div class="f4-tip">文章中至少添加4张图片才能显示</div>';
	}
	echo '</div>';
}

// 图片布局缩略图
function zm_grid_thumbnail() {
	global $post;
	$content = $post->post_content;
	preg_match_all( '/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER );
	$n = count( $strResult[1] );
	if ( $n > 0 ) {
		if ( zm_get_option( 'lazy_s' ) ) {
			$be_get_img = 'data-src="' . $strResult[1][0] . '"';
		} else {
			$be_get_img = 'style="background-image: url(' . $strResult[1][0] . ');"';
		}
	}
	$random_img = explode(',' , zm_get_option('random_image_url'));
	$random_img_array = array_rand($random_img);
	$src = $random_img[$random_img_array];

	$fancy_box = get_post_meta( get_the_ID(), 'fancy_box', true );
	if ( get_post_meta( get_the_ID(), 'fancy_box', true ) ) {
		echo '<a class="fancy-box" rel="external nofollow" href="'. $fancy_box . '"></a>';
	}

	echo '<div class="thumbs-h lazy">';
	if ( get_post_meta(get_the_ID(), 'thumbnail', true) ) {
		$image = get_post_meta(get_the_ID(), 'thumbnail', true);
		echo '<a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" data-src="'.$image.'"></a>';
	} else {
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'content');
			echo '<a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" data-src="'.$full_image_url[0].'"></a>';
		} else {
			if ( $n > 0 && !get_post_meta( get_the_ID(), 'rand_img', true ) ) {
				echo '<a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" ' . $be_get_img . '></a>';
			} else { 
				if ( zm_get_option('rand_img_n') ) {
					$random = mt_rand(1, zm_get_option('rand_img_n'));
				} else { 
					$random = mt_rand(1, 5);
				}
				echo '<a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" data-src="'.$src.'"></a>';
			}
		}
	}
	echo '</div>';
}

// 宽缩略图分类
function zm_full_thumbnail() {
	global $post;
	$content = $post->post_content;
	preg_match_all( '/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER );
	$n = count( $strResult[1] );
	if ( $n > 0 ) {
		if ( zm_get_option( 'lazy_s' ) ) {
			$be_get_img = 'data-src="' . $strResult[1][0] . '"';
		} else {
			$be_get_img = 'style="background-image: url(' . $strResult[1][0] . ');"';
		}
	}
	$random_img = explode(',' , zm_get_option('random_long_url'));
	$random_img_array = array_rand($random_img);
	$src = $random_img[$random_img_array];
	echo '<div class="thumbs-w lazy">';
	if ( get_post_meta(get_the_ID(), 'full_thumbnail', true) ) {
		$image = get_post_meta(get_the_ID(), 'full_thumbnail', true);
		echo '<a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" data-src="'.$image.'"></a>';
	} else {
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'content');
			echo '<a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" data-src="'.$full_image_url[0].'"></a>';
		} else {
			if ( $n > 0 && !get_post_meta( get_the_ID(), 'rand_img', true ) ) {
				echo '<a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" ' . $be_get_img . '></a>';
			} else { 
				if ( zm_get_option('rand_img_n') ) {
					$random = mt_rand(1, zm_get_option('rand_img_n'));
				} else { 
					$random = mt_rand(1, 5);
				}
				echo '<a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" data-src="'.$src.'"></a>';
			}
		}
	}
	echo '</div>';
}

// 公司左右图
function gr_wd_thumbnail() {
	global $post;
	$content = $post->post_content;
	preg_match_all( '/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER );
	$n = count( $strResult[1] );
	if ( $n > 0 ) {
		if ( zm_get_option( 'lazy_s' ) ) {
			$be_get_img = 'data-src="' . $strResult[1][0] . '"';
		} else {
			$be_get_img = 'style="background-image: url(' . $strResult[1][0] . ');"';
		}
	}
	$random_img = explode(',' , zm_get_option('random_image_url'));
	$random_img_array = array_rand($random_img);
	$src = $random_img[$random_img_array];
	echo '<div class="thumbs-lr lazy">';
	if ( get_post_meta(get_the_ID(), 'wd_img', true) ) {
		$image = get_post_meta(get_the_ID(), 'wd_img', true);
		echo '<a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" data-src="'.$image.'"></a>';
	} else {
		if ( $n > 0 ) {
			echo '<a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" ' . $be_get_img . '></a>';
		} else { 
			if ( zm_get_option('rand_img_n') ) {
				$random = mt_rand(1, zm_get_option('rand_img_n'));
			} else { 
				$random = mt_rand(1, 5);
			}
			echo '<a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" data-src="'.$src.'"></a>';
		}
	}
	echo '</div>';
}

// 链接形式
function zm_thumbnail_link() {
	global $post;
	$content = $post->post_content;
	preg_match_all( '/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER );
	$n = count( $strResult[1] );
	if ( $n > 0 ) {
		if ( zm_get_option( 'lazy_s' ) ) {
			$be_get_img = 'data-src="' . $strResult[1][0] . '"';
		} else {
			$be_get_img = 'style="background-image: url(' . $strResult[1][0] . ');"';
		}
	}
	$random_img = explode(',' , zm_get_option('random_image_url'));
	$random_img_array = array_rand($random_img);
	$src = $random_img[$random_img_array];

	$fancy_box = get_post_meta( get_the_ID(), 'fancy_box', true );
	if ( get_post_meta( get_the_ID(), 'fancy_box', true ) ) {
		echo '<a class="fancy-box" rel="external nofollow" href="'. $fancy_box . '"></a>';
	}
	echo '<div class="thumbs-l lazy">';
	$direct = get_post_meta(get_the_ID(), 'direct', true);
	if ( get_post_meta(get_the_ID(), 'thumbnail', true) ) {
		$image = get_post_meta(get_the_ID(), 'thumbnail', true);
		echo '<a class="thumbs-back sc" href="'.$direct.'" data-src="'.$image.'"></a>';
	} else {
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'content');
			echo '<a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" data-src="'.$full_image_url[0].'"></a>';
		} else {
			if ( $n > 0 && !get_post_meta( get_the_ID(), 'rand_img', true ) ) {
				echo '<a class="thumbs-back sc" rel="external nofollow" href="'.$direct.'" ' . $be_get_img . '></a>';
			} else { 
				if ( zm_get_option('rand_img_n') ) {
					$random = mt_rand(1, zm_get_option('rand_img_n'));
				} else { 
					$random = mt_rand(1, 5);
				}
				echo '<a class="thumbs-back sc" rel="external nofollow" href="'.$direct.'" data-src="'.$src.'"></a>';
			}
		}
	}
	echo '</div>';
}

// 幻灯小工具
function zm_widge_thumbnail() {
	global $post;
	echo '<div class="thumbs-sw lazy">';
	if ( get_post_meta(get_the_ID(), 'widge_img', true) ) {
		$image = get_post_meta(get_the_ID(), 'widge_img', true);
		echo '<a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" style="background-image: url('.$image.');"></a>';
	} else {
		$content = $post->post_content;
		preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
		$n = count($strResult[1]);
		if ($n > 0) {
			echo '<a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" style="background-image: url('.$strResult[1][0].');"></a>';
		}
	}
	echo '</div>';
}

// 图片滚动
function zm_thumbnail_scrolling() {
	global $post;
	$random_img = explode(',' , zm_get_option('random_image_url'));
	$random_img_array = array_rand($random_img);
	$src = $random_img[$random_img_array];

	$fancy_box = get_post_meta( get_the_ID(), 'fancy_box', true );
	if ( get_post_meta( get_the_ID(), 'fancy_box', true ) ) {
		echo '<a class="fancy-box" rel="external nofollow" href="'. $fancy_box . '"></a>';
	}

	echo '<div class="thumbs-sg">';
	if ( get_post_meta(get_the_ID(), 'thumbnail', true) ) {
		$image = get_post_meta(get_the_ID(), 'thumbnail', true);
		echo '<a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" style="background-image: url('.$image.');"></a>';
	} else {
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'content');
			echo '<a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" style="background-image: url('.$full_image_url[0].');"></a>';
		} else {
			$content = $post->post_content;
			preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
			$n = count($strResult[1]);
			if ( $n > 0 ) {
				echo '<a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" style="background-image: url('.$strResult[1][0].');"></a>';
			} else { 
				if ( zm_get_option('rand_img_n') ) {
					$random = mt_rand(1, zm_get_option('rand_img_n'));
				} else { 
					$random = mt_rand(1, 5);
				}
				echo '<a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" style="background-image: url('.$src.');"></a>';
			}
		}
	}
	echo '</div>';
}
}

// 瀑布流
function zm_waterfall_img() {
	global $post;
	$random_img = explode( ',' , zm_get_option( 'random_image_url' ) );
	$random_img_array = array_rand( $random_img );
	$src = $random_img[$random_img_array];

	$fancy_box = get_post_meta( get_the_ID(), 'fancy_box', true );
	if ( get_post_meta( get_the_ID(), 'fancy_box', true ) ) {
		echo '<a class="fancy-box" rel="external nofollow" href="'. $fancy_box . '"></a>';
	}

	if ( get_post_meta( get_the_ID(), 'fall_img', true ) ) {
		$image = get_post_meta(get_the_ID(), 'fall_img', true);
		echo '<a rel="external nofollow" href="' . get_permalink() . '"><img src=';
		echo $image;
		echo ' alt="' . $post->post_title . '" width="' . zm_get_option('img_w') . '" height="' . zm_get_option( 'img_h' ) . '" /></a>';
	} else {
		$content = $post->post_content;
		preg_match_all( '/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER );
		$n = count( $strResult[1] );
		if ( $n > 0 ) {
			echo '<a rel="external nofollow" href="' . get_permalink() . '"><img src="' . $strResult[1][0] . '" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_w' ) . '" height="' . zm_get_option( 'img_h' ) . '" /></a>';
		} else { 
			if ( zm_get_option('rand_img_n') ) {
				$random = mt_rand( 1, zm_get_option( 'rand_img_n' ) );
			} else { 
					$random = mt_rand( 1, 5 );
			}
			echo '<a rel="external nofollow" href="'.get_permalink().'"><img src="' . $src . '" alt="' . $post->post_title . '" width="' . zm_get_option('img_w').'" height="' . zm_get_option('img_h') . '" /></a>';
		}
	}
	be_vip_meta();
}

// 菜单
function zm_menu_img() {
	$random_img = explode(',' , zm_get_option('random_image_url'));
	$random_img_array = array_rand($random_img);
	$src = $random_img[$random_img_array];
	global $post;
	echo '<div class="thumbs-t">';
	if ( get_post_meta(get_the_ID(), 'thumbnail', true) ) {
		$image = get_post_meta(get_the_ID(), 'thumbnail', true);
		echo '<a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" style="background-image: url('.$image.') !important;"></a>';
	} else {
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'content');
			echo '<a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" style="background-image: url('.$full_image_url[0].') !important;"></a>';
		} else {
			$content = $post->post_content;
			preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
			$n = count($strResult[1]);
			if ( $n > 0 && !get_post_meta( get_the_ID(), 'rand_img', true ) ) {
				echo '<a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" style="background-image: url('.$strResult[1][0].') !important;"></a>';
			} else { 
				if ( zm_get_option('rand_img_n') ) {
					$random = mt_rand(1, zm_get_option('rand_img_n'));
					$random = mt_rand(1, 5);
				}
				echo '<a class="thumbs-back sc" rel="external nofollow" href="'.get_permalink().'" style="background-image: url('.$src.') !important;"></a>';
			}
		}
	}
	echo '</div>';
}

// menu thumbnail
function be_menu_thumbnail() {
	global $post, $html;
	$random_img = explode(',' , zm_get_option('random_long_url'));
	$random_img_array = array_rand($random_img);
	$src = $random_img[$random_img_array];
	// 手动
	if ( get_post_meta( get_the_ID(), 'thumbnail', true ) ) {
		$post_img = get_post_meta( get_the_ID(), 'thumbnail', true );
		$html = '<span class="thumbs-m lazy menu-mix-post-img sc"><img src="' . $post_img . '" alt="' . get_the_title() . '" /></span>';
	} else {
		$content = $post->post_content;
		preg_match_all( '/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER );
		$n = count( $strResult[1] );
		if ( $n > 0 ) {
			$html = '<span class="menu-mix-post-img sc"><img src="' . $strResult[1][0] . '" alt="' . get_the_title() . '" /></span>';
		} else { 
			$html = '<span class="menu-mix-post-img sc">';
			if ( zm_get_option('rand_img_n') ) {
				$random = mt_rand(1, zm_get_option('rand_img_n'));
			} else { 
				$random = mt_rand(1, 5);
			}
			$html = '<span class="menu-mix-post-img sc"><img src="'. $src .'" alt="' . get_the_title() . '" /></span>';
			$html .= '</span>';
		}
	}
	return $html;
}

// 特色
if ( zm_get_option( 'wp_thumbnails' ) ) {
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'content', zm_get_option('img_w'), zm_get_option('img_h'), true );
	add_image_size( 'long', zm_get_option('img_k_w'), zm_get_option('img_k_h'), true );
	add_image_size( 'tao', zm_get_option('img_t_w'), zm_get_option('img_t_h'), true );
}