<?php
if ( ! defined( 'ABSPATH' ) ) exit;
function textimg() {
	if ( zm_get_option('img_text' ) ) {
		return '<div class="textimg">' . get_the_title() . '</div>';
	}
}
if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( 'img_way') == 'd_img' ) || ( zm_get_option( 'img_way') == 's_img' ) || ( zm_get_option( 'img_way') == 'o_img' ) || ( zm_get_option( 'img_way') == 'q_img' ) || ( zm_get_option( 'img_way') == 'upyun' ) || ( zm_get_option( 'img_way') == 'cos_img' ) ) {
function be_lazy_thumb( $width = '', $height = '' ) {
	if ( zm_get_option( 'img_way' ) == 'o_img' ) {
		return zm_get_option( 'lazy_thumb' ) . '?x-oss-process=image/resize,m_fill,w_' . $width . ' ,h_' . $height . ', limit_0"';
	}
	elseif ( zm_get_option( 'img_way' ) == 'q_img' ) {
		return zm_get_option( 'lazy_thumb' ) . '?imageMogr2/gravity/Center/crop/' . $width . 'x' . $height . '"';
	}
	elseif ( zm_get_option( 'img_way' ) == 'upyun' ) {
		return zm_get_option( 'lazy_thumb' ) . '!/both/' . $width . 'x' . $height . '/format/webp/lossless/true"';
	}
	elseif ( zm_get_option( 'img_way' ) == 'cos_img' ) {
		return zm_get_option( 'lazy_thumb' ) . '?imageView2/1/w/' . $width . '/h/' . $height . '/q/85"';
	}
	else {
		return be_resize( zm_get_option( 'lazy_thumb' ), $width, $height, true );
	}
}

function is_ext_img( $strResult, $parseResult, $domains ) {
	$img_way = zm_get_option('img_way');
	return ( strpos( $strResult[1][0], $_SERVER['HTTP_HOST'] ) !== false || ( isset($parseResult['host']) && in_array( $parseResult['host'], $domains ) ) || in_array( $img_way, ['s_img', 'o_img', 'q_img', 'upyun', 'cos_img'] ) );
}

// 标准缩略图
function zm_thumbnail() {
	global $post;
	$html = '';
	$html .= textimg();
	$domains = explode( ',', zm_get_option( 'allowed_domains' ) );
	$beimg   = str_replace( array( "\n", "\r", " " ), '', explode( ',', zm_get_option( 'random_image_url' ) ) );
	$src = $beimg[array_rand( $beimg )];
	$width   = zm_get_option( 'img_w' );
	$height  = zm_get_option( 'img_h' );
	$loadimg = be_lazy_thumb( $width, $height );
	$fancy   = get_post_meta( get_the_ID(), 'fancy_box', true );
	$rel     = zm_get_option( 'img_rel_nofollow' ) ? 'external nofollow' : 'bookmark';

	if ( get_post_meta( get_the_ID(), 'fancy_box', true ) ) {
		$html .= '<a class="fancy-box" rel="' . $rel . '" href="' . $fancy . '"></a>';
	}

	if ( ! zm_get_option( 'rand_only' ) ) {
		// 手动
		if ( get_post_meta( get_the_ID(), 'thumbnail', true ) ) {
			$image = get_post_meta( get_the_ID(), 'thumbnail', true );
			if ( zm_get_option('lazy_s' ) ) {
				$html .= '<a class="sc load" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="' . $loadimg . '" data-original="';
			} else {
				$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="';
			}

			if ( ! zm_get_option( 'thumbnail_crop' ) ) {
				$html .= $image . '"';
			} else {
				if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( 'img_way' ) == 'd_img' ) ) {
					$html .= get_template_directory_uri() . '/prune.php?src=' . $image . '&w=' . $width . '&h=' . $height . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1"';
				}

				elseif ( zm_get_option( 'img_way' ) == 's_img' ) {
					$html .= be_get_image_url( attachment_url_to_postid( $image ), array( $width, $height ), true ) . '"';
				}

				elseif ( zm_get_option( 'img_way' ) == 'o_img' ) {
					$html .= $image . '?x-oss-process=image/resize,m_fill,w_' . $width . ' ,h_' . $height . ', limit_0"';
				}

				elseif ( zm_get_option( 'img_way' ) == 'q_img' ) {
					$html .= $image . '?imageMogr2/gravity/Center/crop/' . $width . 'x' . $height . '"';
				}

				elseif ( zm_get_option( 'img_way' ) == 'upyun' ) {
					$html .= $image . '!/both/' . $width . 'x' . $height . '/format/webp/lossless/true"';
				}

				elseif ( zm_get_option( 'img_way' ) == 'cos_img' ) {
					$html .= $image . '?imageView2/1/w/' . $width . '/h/' . $height . '/q/85"';
				}
			}

			$html .= ' alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
		} else {
			// 特色
			if ( has_post_thumbnail() ) {
				$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'content');
				if ( zm_get_option( 'lazy_s' ) ) {
					$html .= '<a class="sc load" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="' . $loadimg . '" data-original="';
				} else {
					$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="';
				}

				$html .= be_get_image_url( attachment_url_to_postid( $full_image_url[0] ), array( $width, $height ), true ) . '" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '">';
				$html .= '</a>';
			} else {
				// 自动
				$content = $post->post_content;
				preg_match_all( '/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER );
				$n = count( $strResult[1] );
				if ( $n > 0 && !get_post_meta( get_the_ID(), 'rand_img', true ) ) {

					if ( zm_get_option( 'lazy_s' ) ) {
						$be_get_img = 'data-src="' . $strResult[1][0] . '"';
					} else {
						$be_get_img = 'style="background-image: url(' . $strResult[1][0] . ');"';
					}

					$parseResult = wp_parse_url( $strResult[1][0] );
					if ( is_ext_img( $strResult, $parseResult, $domains ) ) {
						if ( zm_get_option( 'lazy_s' ) ) {
							$html .= '<a class="sc load" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="' . $loadimg . '" data-original="';
						} else {
							$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="';
						}
		
						if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( 'img_way' ) == 'd_img' ) ) {
							$html .= get_template_directory_uri() . '/prune.php?src=' . $strResult[1][0] . '&w=' . $width . '&h=' . $height . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
						}

						if ( zm_get_option( 'img_way' ) == 's_img' ) {
							$html .= be_get_image_url( attachment_url_to_postid( $strResult[1][0] ), array( $width, $height ), true ) . '" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
						}
					} else {
						$html .= '<div class="thumbs-b lazy"><a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '" ' . $be_get_img . '></a></div>';
					}

					if ( zm_get_option( 'img_way' ) == 'o_img' ) {
						$html .= $strResult[1][0] . '?x-oss-process=image/resize,m_fill,w_' . $width . ' ,h_' . $height . ', limit_0" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
					}

					if ( zm_get_option( 'img_way' ) == 'q_img' ) {
						$html .= $strResult[1][0] . '?imageMogr2/gravity/Center/crop/' . $width . 'x' . $height . '" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
					}

					if ( zm_get_option( 'img_way' ) == 'upyun' ) {
						$html .= $strResult[1][0] . '!/both/' . $width . 'x' . $height . '/format/webp/lossless/true" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
					}

					if ( zm_get_option( 'img_way' ) == 'cos_img' ) {
						$html .= $strResult[1][0] . '?imageView2/1/w/' . $width . '/h/' . $height . '/q/85" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
					}
					if ( is_ext_img( $strResult, $parseResult, $domains ) && zm_get_option( 'lazy_s' ) ) {
						$html .= '</span>';
					}
				} else { 
					// 随机
					if ( zm_get_option( 'lazy_s' ) ) {
						$html .= '<a class="sc load" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="' . $loadimg . '" data-original="';
					} else {
						$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="';
					}

					if ( ! zm_get_option( 'randimg_crop' ) ) {
						$html .= $src . '"';
					} else {
						if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( 'img_way' ) == 'd_img' ) ) {
							$html .= get_template_directory_uri() . '/prune.php?src=' . $src . '&w=' . $width . '&h=' . $height . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1"';
						}

						elseif ( zm_get_option( 'img_way' ) == 's_img' ) {
							$html .= be_get_image_url( attachment_url_to_postid( $src ), array( $width, $height ), true ) . '"';
						}

						elseif ( zm_get_option( 'img_way' ) == 'o_img' ) {
							$html .= $src . '?x-oss-process=image/resize,m_fill,w_' . $width . ' ,h_' . $height . ', limit_0"';
						}

						elseif ( zm_get_option( 'img_way' ) == 'q_img' ) {
							$html .= $src . '?imageMogr2/gravity/Center/crop/' . $width . 'x' . $height . '"';
						}

						elseif ( zm_get_option( 'img_way' ) == 'upyun' ) {
							$html .= $src . '!/both/' . $width . 'x' . $height . '/format/webp/lossless/true"';
						}

						elseif ( zm_get_option( 'img_way' ) == 'cos_img' ) {
							$html .= $src . '?imageView2/1/w/' . $width . '/h/' . $height . '/q/85"';
						}
					}

					$html .= ' alt="' . $post->post_title .'" width="' . $width . '" height="' . $height . '"></a>';
				}
			}
		}
	} else {
		// 随机
		if ( zm_get_option( 'lazy_s' ) ) {
			$html .= '<a class="sc load" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="' . $loadimg . '" data-original="';
		} else {
			$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="';
		}

		if ( ! zm_get_option( 'randimg_crop' ) ) {
			$html .= $src . '"';
		} else {
			if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( 'img_way' ) == 'd_img' ) ) {
				$html .= get_template_directory_uri() . '/prune.php?src=' . $src . '&w=' . $width . '&h=' . $height . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1"';
			}

			elseif ( zm_get_option( 'img_way' ) == 's_img' ) {
				$html .= be_get_image_url( attachment_url_to_postid( $src ), array( $width, $height ), true ) . '"';
			}

			elseif ( zm_get_option( 'img_way' ) == 'o_img' ) {
				$html .= $src . '?x-oss-process=image/resize,m_fill,w_' . $width . ' ,h_' . $height . ', limit_0"';
			}

			elseif ( zm_get_option( 'img_way' ) == 'q_img' ) {
				$html .= $src . '?imageMogr2/gravity/Center/crop/' . $width . 'x' . $height . '"';
			}

			elseif ( zm_get_option( 'img_way' ) == 'upyun' ) {
				$html .= $src . '!/both/' . $width . 'x' . $height . '/format/webp/lossless/true"';
			}

			elseif ( zm_get_option( 'img_way' ) == 'cos_img' ) {
				$html .= $src . '?imageView2/1/w/' . $width . '/h/' . $height . '/q/85"';
			}
		}

		$html .= ' alt="' . $post->post_title .'" width="' . $width . '" height="' . $height . '"></a>';
	}
	return $html;
}

// 分类模块宽缩略图
function zm_long_thumbnail() {
	global $post;
	$html = '';
	$domains = explode( ',', zm_get_option( 'allowed_domains' ) );
	$beimg   = str_replace( array( "\n", "\r", " " ), '', explode( ',', zm_get_option( 'random_long_url' ) ) );
	$src     = $beimg[array_rand( $beimg )];
	$width   = zm_get_option( 'img_k_w' );
	$height  = zm_get_option( 'img_k_h' );
	$loadimg = be_lazy_thumb( $width, $height );
	$rel     = zm_get_option( 'img_rel_nofollow' ) ? 'external nofollow' : 'bookmark';

	if ( get_post_meta( get_the_ID(), 'long_thumbnail', true ) ) {
		$image = get_post_meta( get_the_ID(), 'long_thumbnail', true );
		if (zm_get_option( 'lazy_s' ) ) {
			$html .= '<a class="sc load" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="' . $loadimg . '" data-original="';
		} else {
			$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="';
		}

		if ( ! zm_get_option( 'thumbnail_crop' ) ) {
			$html .= $image . '"';
		} else {
			if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( 'img_way' ) == 'd_img' ) ) {
				$html .= get_template_directory_uri() . '/prune.php?src=' . $image . '&w=' . $width . '&h=' . $height . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1"';
			}

			elseif ( zm_get_option( 'img_way' ) == 's_img' ) {
				$html .= be_get_image_url( attachment_url_to_postid( $image ), array( $width, $height ), true ) . '"';
			}

			elseif ( zm_get_option( 'img_way' ) == 'o_img' ) {
				$html .= $image . '?x-oss-process=image/resize,m_fill,w_' . $width . ' ,h_' . $height . ', limit_0"';
			}

			elseif ( zm_get_option( 'img_way' ) == 'q_img' ) {
				$html .= $image . '?imageMogr2/gravity/Center/crop/' . $width . 'x' . $height . '"';
			}

			elseif ( zm_get_option( 'img_way' ) == 'upyun' ) {
				$html .= $image . '!/both/' . $width . 'x' . $height . '/format/webp/lossless/true"';
			}

			elseif ( zm_get_option( 'img_way' ) == 'cos_img' ) {
				$html .= $image . '?imageView2/1/w/' . $width . '/h/' . $height . '/q/85"';
			}
		}

		$html .= ' alt="'.$post->post_title .'" width="'. $width .'" height="' . $height . '"></a>';
	} else {
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'long' );
			if (zm_get_option('lazy_s')) {
				$html .= '<a class="sc load" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '">';
			} else {
				$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '">';
			}

				if (zm_get_option('lazy_s')) {
					$html .= '<img src="' . $loadimg . '" data-original="'.$full_image_url[0].'" alt="'.get_the_title().'">';
				} else {
					$html .= '<img src="' . $full_image_url[0] . '" alt="' . get_the_title() . '" width="' . $width . '" height="' . $height . '" >';
				}

			$html .= '</a>';
		} else {
			$content = $post->post_content;
			preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
			$n = count($strResult[1]);
			if ($n > 0 && !get_post_meta( get_the_ID(), 'rand_img', true )) {

				if ( zm_get_option( 'lazy_s' ) ) {
					$be_get_img = 'data-src="' . $strResult[1][0] . '"';
				} else {
					$be_get_img = 'style="background-image: url(' . $strResult[1][0] . ');"';
				}

				$parseResult = wp_parse_url( $strResult[1][0] );
				if ( is_ext_img( $strResult, $parseResult, $domains ) ) {
					if (zm_get_option('lazy_s')) {
						$html .= '<a class="sc load" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="' . $loadimg . '" data-original="';
					} else {
						$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="';
					}

					if ( ! zm_get_option( 'img_way' ) || ( zm_get_option('img_way' ) == 'd_img' ) ) {
						$html .= get_template_directory_uri() . '/prune.php?src=' . $strResult[1][0] . '&w=' . $width . '&h=' . $height . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
					}

					if ( zm_get_option( 'img_way' ) == 's_img' ) {
						$html .= be_get_image_url( attachment_url_to_postid( $strResult[1][0]  ), array( $width, $height ), true ) . '" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
					}
				} else {
					$html .= '<div class="thumbs-f lazy"><a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '" ' . $be_get_img . '></a></div>';
				}

				if ( zm_get_option( 'img_way' ) == 'o_img' ) {
					$html .= $strResult[1][0] . '?x-oss-process=image/resize,m_fill,w_' . $width . ' ,h_' . $height . ', limit_0" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'q_img' ) {
					$html .= $strResult[1][0] . '?imageMogr2/gravity/Center/crop/' . $width . 'x' . $height . '" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'upyun' ) {
					$html .= $strResult[1][0] . '!/both/' . $width . 'x' . $height . '/format/webp/lossless/true" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'cos_img' ) {
					$html .= $strResult[1][0] . '?imageView2/1/w/' . $width . '/h/' . $height . '/q/85" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
				}
			} else { 
				if ( zm_get_option( 'lazy_s' ) ) {
					$html .= '<a class="sc load" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="' . $loadimg . '" data-original="';
				} else {
					$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="';
				}

				if ( ! zm_get_option( 'randimg_crop' ) ) {
					$html .= $src . '"';
				} else {
					if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( 'img_way' ) == 'd_img' ) ) {
						$html .= get_template_directory_uri() . '/prune.php?src=' . $src . '&w=' . $width . '&h=' . $height . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1"';
					}

					elseif ( zm_get_option( 'img_way' ) == 's_img' ) {
						$html .= be_get_image_url( attachment_url_to_postid( $src ), array( $width, $height ), true ) . '"';
					}

					elseif ( zm_get_option( 'img_way' ) == 'o_img' ) {
						$html .= $src . '?x-oss-process=image/resize,m_fill,w_' . $width . ' ,h_' . $height . ', limit_0"';
					}

					elseif ( zm_get_option( 'img_way' ) == 'q_img' ) {
						$html .= $src . '?imageMogr2/gravity/Center/crop/' . $width . 'x' . $height . '"';
					}

					elseif ( zm_get_option( 'img_way' ) == 'upyun' ) {
						$html .= $src . '!/both/' . $width . 'x' . $height . '/format/webp/lossless/true"';
					}

					elseif ( zm_get_option( 'img_way' ) == 'cos_img' ) {
						$html .= $src . '?imageView2/1/w/' . $width . '/h/' . $height . '/q/85"';
					}
				}
	
				$html .= ' alt="' . $post->post_title .'" width="' . $width . '" height="' . $height . '"></a>';
			}
		}
	}
	return $html;
}

// 图片缩略图
function img_thumbnail() {
	global $post;
	$html = '';
	$domains = explode( ',', zm_get_option( 'allowed_domains' ) );
	$beimg   = str_replace( array( "\n", "\r", " " ), '', explode( ',', zm_get_option( 'random_image_url' ) ) );
	$src     = $beimg[array_rand( $beimg )];
	$width   = zm_get_option( 'img_i_w' );
	$height  = zm_get_option( 'img_i_h' );
	$loadimg = be_lazy_thumb( $width, $height );
	$fancy   = get_post_meta( get_the_ID(), 'fancy_box', true );
	$rel     = zm_get_option( 'img_rel_nofollow' ) ? 'external nofollow' : 'bookmark';
	if ( get_post_meta( get_the_ID(), 'fancy_box', true ) ) {
		$html .= '<a class="fancy-box" rel="' . $rel . '" href="'. $fancy . '"></a>';
	}

	if ( get_post_meta(get_the_ID(), 'thumbnail', true) ) {
		$image = get_post_meta(get_the_ID(), 'thumbnail', true);
		if ( zm_get_option( 'lazy_s' ) ) {
			$html .= '<a class="sc load" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="' . $loadimg . '" data-original="';
		} else {
			$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="';
		}

		if ( ! zm_get_option( 'thumbnail_crop' ) ) {
			$html .= $image . '"';
		} else {
			if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( 'img_way' ) == 'd_img' ) ) {
				$html .= get_template_directory_uri() . '/prune.php?src=' . $image . '&w=' . $width . '&h=' . $height . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1"';
			}

			elseif ( zm_get_option( 'img_way' ) == 's_img' ) {
				$html .= be_get_image_url( attachment_url_to_postid( $image ), array( $width, $height ), true ) . '"';
			}

			elseif ( zm_get_option( 'img_way' ) == 'o_img' ) {
				$html .= $image . '?x-oss-process=image/resize,m_fill,w_' . $width . ' ,h_' . $height . ', limit_0"';
			}

			elseif ( zm_get_option( 'img_way' ) == 'q_img' ) {
				$html .= $image . '?imageMogr2/gravity/Center/crop/' . $width . 'x' . $height . '"';
			}

			elseif ( zm_get_option( 'img_way' ) == 'upyun' ) {
				$html .= $image . '!/both/' . $width . 'x' . $height . '/format/webp/lossless/true"';
			}

			elseif ( zm_get_option( 'img_way' ) == 'cos_img' ) {
				$html .= $image . '?imageView2/1/w/' . $width . '/h/' . $height . '/q/85"';
			}
		}
	} else {
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'content');
			if ( zm_get_option( 'lazy_s' ) ) {
				$html .= '<a class="sc load" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="' . $loadimg . '" data-original="';
			} else {
				$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="';
			}

			$html .= be_get_image_url( attachment_url_to_postid( $full_image_url[0] ), array( $width, $height ), true ) . '" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '">';
			$html .= '</a>';
		} else {
			$content = $post->post_content;
			preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
			$n = count($strResult[1]);
			if ($n > 0) {

				if ( zm_get_option( 'lazy_s' ) ) {
					$be_get_img = 'data-src="' . $strResult[1][0] . '"';
				} else {
					$be_get_img = 'style="background-image: url(' . $strResult[1][0] . ');"';
				}

				$parseResult = wp_parse_url( $strResult[1][0] );
				if ( is_ext_img( $strResult, $parseResult, $domains ) ) {
					if (zm_get_option('lazy_s')) {
						$html .= '<a class="sc load" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="' . $loadimg . '" data-original="';
					} else {
						$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="';
					}

					if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( 'img_way' ) == 'd_img' ) ) {
							$html .= get_template_directory_uri() . '/prune.php?src=' . $strResult[1][0] . '&w=' . $width . '&h=' . $height . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
					}

					if ( zm_get_option( 'img_way' ) == 's_img' ) {
						$html .= be_get_image_url( attachment_url_to_postid( $strResult[1][0]  ), array( $width, $height ), true ) . '" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
					}

				} else {
					$html .= '<div class="thumbs-i lazy"><a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '" ' . $be_get_img . '></a></div>';
				}

				if ( zm_get_option( 'img_way' ) == 'o_img' ) {
					$html .= $strResult[1][0] . '?x-oss-process=image/resize,m_fill,w_' . $width . ' ,h_' . $height . ', limit_0" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'q_img' ) {
					$html .= $strResult[1][0] . '?imageMogr2/gravity/Center/crop/' . $width . 'x' . $height . '" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'upyun' ) {
					$html .= $strResult[1][0] . '!/both/' . $width . 'x' . $height . '/format/webp/lossless/true" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'cos_img' ) {
					$html .= $strResult[1][0] . '?imageView2/1/w/' . $width . '/h/' . $height . '/q/85" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
				}
			} else { 
				if ( zm_get_option( 'lazy_s' ) ) {
					$html .= '<a class="sc load" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="' . $loadimg . '" data-original="';
				} else {
					$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="';
				}

				if ( ! zm_get_option( 'randimg_crop' ) ) {
					$html .= $src . '"';
				} else {
					if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( 'img_way' ) == 'd_img' ) ) {
						$html .= get_template_directory_uri() . '/prune.php?src=' . $src . '&w=' . $width . '&h=' . $height . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1"';
					}

					elseif ( zm_get_option( 'img_way' ) == 's_img' ) {
						$html .= be_get_image_url( attachment_url_to_postid( $src ), array( $width, $height ), true ) . '"';
					}

					elseif ( zm_get_option( 'img_way' ) == 'o_img' ) {
						$html .= $src . '?x-oss-process=image/resize,m_fill,w_' . $width . ' ,h_' . $height . ', limit_0"';
					}

					elseif ( zm_get_option( 'img_way' ) == 'q_img' ) {
						$html .= $src . '?imageMogr2/gravity/Center/crop/' . $width . 'x' . $height . '"';
					}

					elseif ( zm_get_option( 'img_way' ) == 'upyun' ) {
						$html .= $src . '!/both/' . $width . 'x' . $height . '/format/webp/lossless/true"';
					}

					elseif ( zm_get_option( 'img_way' ) == 'cos_img' ) {
						$html .= $src . '?imageView2/1/w/' . $width . '/h/' . $height . '/q/85"';
					}
				}
	
				$html .= ' alt="' . $post->post_title .'" width="' . $width . '" height="' . $height . '"></a>';
			}
		}
	}
	return $html;
}

// 视频缩略图
function videos_thumbnail() {
	global $post;
	$html = '';
	$domains = explode( ',', zm_get_option( 'allowed_domains' ) );
	$beimg   = str_replace( array( "\n", "\r", " " ), '', explode( ',', zm_get_option( 'random_image_url' ) ) );
	$src     = $beimg[array_rand( $beimg )];
	$width   = zm_get_option( 'img_v_w' );
	$height  = zm_get_option( 'img_v_h' );
	$loadimg = be_lazy_thumb( $width, $height );
	$rel     = zm_get_option( 'img_rel_nofollow' ) ? 'external nofollow' : 'bookmark';

	if ( get_post_meta(get_the_ID(), 'small', true) ) {
		$image = get_post_meta(get_the_ID(), 'small', true);
		if (zm_get_option('lazy_s')) {
			$html .= '<a class="sc load" rel="' . $rel . '" ' . goal() . ' class="videos" href="' . get_permalink() . '"><img src="' . $loadimg . '" data-original="';
		} else {
			$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' class="videos" href="' . get_permalink() . '"><img src="';
		}

		if ( ! zm_get_option( 'thumbnail_crop' ) ) {
			$html .= $image . '"';
		} else {
			if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( 'img_way' ) == 'd_img' ) ) {
				$html .= get_template_directory_uri() . '/prune.php?src=' . $image . '&w=' . $width . '&h=' . $height . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1"';
			}

			elseif ( zm_get_option( 'img_way' ) == 's_img' ) {
				$html .= be_get_image_url( attachment_url_to_postid( $image ), array( $width, $height ), true ) . '"';
			}

			elseif ( zm_get_option( 'img_way' ) == 'o_img' ) {
				$html .= $image . '?x-oss-process=image/resize,m_fill,w_' . $width . ' ,h_' . $height . ', limit_0"';
			}

			elseif ( zm_get_option( 'img_way' ) == 'q_img' ) {
				$html .= $image . '?imageMogr2/gravity/Center/crop/' . $width . 'x' . $height . '"';
			}

			elseif ( zm_get_option( 'img_way' ) == 'upyun' ) {
				$html .= $image . '!/both/' . $width . 'x' . $height . '/format/webp/lossless/true"';
			}

			elseif ( zm_get_option( 'img_way' ) == 'cos_img' ) {
				$html .= $image . '?imageView2/1/w/' . $width . '/h/' . $height . '/q/85"';
			}
		}

		$html .= ' alt="'.$post->post_title .'" width="'.$width.'" height="'.$height.'"></a>';
	} else {
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'content');

			if ( zm_get_option( 'lazy_s' ) ) {
				$html .= '<a class="sc load" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="' . $loadimg . '" data-original="';
			} else {
				$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="';
			}

			$html .= be_get_image_url( attachment_url_to_postid( $full_image_url[0] ), array( $width, $height ), true ) . '" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '">';
			$html .= '<i class="be be-play"></i></a>';
		} else {
			$content = $post->post_content;
			preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
			$n = count($strResult[1]);
			if ($n > 0) {
				if ( zm_get_option( 'lazy_s' ) ) {
					$be_get_img = 'data-src="' . $strResult[1][0] . '"';
				} else {
					$be_get_img = 'style="background-image: url(' . $strResult[1][0] . ');"';
				}

				$parseResult = wp_parse_url( $strResult[1][0] );
				if ( is_ext_img( $strResult, $parseResult, $domains ) ) {
					if (zm_get_option('lazy_s')) {
						$html .= '<a class="sc load" rel="' . $rel . '" ' . goal() . ' class="videos" href="' . get_permalink() . '"><img src="' . $loadimg . '" data-original="';
					} else {
						$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' class="videos" href="' . get_permalink() . '"><img src="';
					}

					if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( 'img_way' ) == 'd_img' ) ) {
						$html .= get_template_directory_uri() . '/prune.php?src=' . $strResult[1][0] . '&w=' . $width . '&h=' . $height . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"><i class="be be-play"></i></a>';
					}

					if ( zm_get_option( 'img_way' ) == 's_img' ) {
						$html .= be_get_image_url( attachment_url_to_postid( $strResult[1][0]  ), array( $width, $height ), true ) . '" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
					}

				} else {
					$html .= '<div class="thumbs-v lazy"><a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '" ' . $be_get_img . '></a></div>';
				}

				if ( zm_get_option( 'img_way' ) == 'o_img' ) {
					$html .= $strResult[1][0] . '?x-oss-process=image/resize,m_fill,w_' . $width . ' ,h_' . $height . ', limit_0" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'q_img' ) {
					$html .= $strResult[1][0] . '?imageMogr2/gravity/Center/crop/' . $width . 'x' . $height . '" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'upyun' ) {
					$html .= $strResult[1][0] . '!/both/' . $width . 'x' . $height . '/format/webp/lossless/true" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'cos_img' ) {
					$html .= $strResult[1][0] . '?imageView2/1/w/' . $width . '/h/' . $height . '/q/85" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
				}
			} else { 
				if ( zm_get_option( 'lazy_s' ) ) {
					$html .= '<a class="sc load" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="' . $loadimg . '" data-original="';
				} else {
					$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="';
				}

				if ( ! zm_get_option( 'randimg_crop' ) ) {
					$html .= $src . '"';
				} else {
					if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( 'img_way' ) == 'd_img' ) ) {
						$html .= get_template_directory_uri() . '/prune.php?src=' . $src . '&w=' . $width . '&h=' . $height . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1"';
					}

					elseif ( zm_get_option( 'img_way' ) == 's_img' ) {
						$html .= be_get_image_url( attachment_url_to_postid( $src ), array( $width, $height ), true ) . '"';
					}

					elseif ( zm_get_option( 'img_way' ) == 'o_img' ) {
						$html .= $src . '?x-oss-process=image/resize,m_fill,w_' . $width . ' ,h_' . $height . ', limit_0"';
					}

					elseif ( zm_get_option( 'img_way' ) == 'q_img' ) {
						$html .= $src . '?imageMogr2/gravity/Center/crop/' . $width . 'x' . $height . '"';
					}

					elseif ( zm_get_option( 'img_way' ) == 'upyun' ) {
						$html .= $src . '!/both/' . $width . 'x' . $height . '/format/webp/lossless/true"';
					}

					elseif ( zm_get_option( 'img_way' ) == 'cos_img' ) {
						$html .= $src . '?imageView2/1/w/' . $width . '/h/' . $height . '/q/85"';
					}
				}
	
				$html .= ' alt="' . $post->post_title .'" width="' . $width . '" height="' . $height . '"><i class="be be-play"></i></a>';
			}
		}
	}
	return $html;
}

// 商品缩略图
function tao_thumbnail() {
	global $post;
	$html = '';
	$domains = explode( ',', zm_get_option( 'allowed_domains' ) );
	$beimg   = str_replace( array( "\n", "\r", " " ), '', explode( ',', zm_get_option( 'random_image_url' ) ) );
	$src     = $beimg[array_rand( $beimg )];
	$width   = zm_get_option( 'img_t_w' );
	$height  = zm_get_option( 'img_t_h' );
	$loadimg = be_lazy_thumb( $width, $height );
	$fancy   = get_post_meta( get_the_ID(), 'fancy_box', true );
	$rel     = zm_get_option( 'img_rel_nofollow' ) ? 'external nofollow' : 'bookmark';
	if ( get_post_meta( get_the_ID(), 'fancy_box', true ) ) {
		$html .= '<a class="fancy-box" rel="' . $rel . '" href="'. $fancy . '"></a>';
	}

	$url = get_post_meta(get_the_ID(), 'taourl', true);
	if ( get_post_meta(get_the_ID(), 'thumbnail', true) ) {
		$image = get_post_meta(get_the_ID(), 'thumbnail', true);

		if (zm_get_option('lazy_s')) {
			$html .= '<a class="sc load" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="' . $loadimg . '" data-original="';
		} else {
			$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="';
		}

		if ( ! zm_get_option( 'thumbnail_crop' ) ) {
			$html .= $image . '"';
		} else {
			if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( 'img_way' ) == 'd_img' ) ) {
				$html .= get_template_directory_uri() . '/prune.php?src=' . $image . '&w=' . $width . '&h=' . $height . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1"';
			}

			elseif ( zm_get_option( 'img_way' ) == 's_img' ) {
				$html .= be_get_image_url( attachment_url_to_postid( $image ), array( $width, $height ), true ) . '"';
			}

			elseif ( zm_get_option( 'img_way' ) == 'o_img' ) {
				$html .= $image . '?x-oss-process=image/resize,m_fill,w_' . $width . ' ,h_' . $height . ', limit_0"';
			}

			elseif ( zm_get_option( 'img_way' ) == 'q_img' ) {
				$html .= $image . '?imageMogr2/gravity/Center/crop/' . $width . 'x' . $height . '"';
			}

			elseif ( zm_get_option( 'img_way' ) == 'upyun' ) {
				$html .= $image . '!/both/' . $width . 'x' . $height . '/format/webp/lossless/true"';
			}

			elseif ( zm_get_option( 'img_way' ) == 'cos_img' ) {
				$html .= $image . '?imageView2/1/w/' . $width . '/h/' . $height . '/q/85"';
			}
		}

		$html .= ' alt="'.$post->post_title .'" width="'.$width.'" height="'.$height.'"></a>';
	} else {
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'tao');
			if (zm_get_option('lazy_s')) {
				$html .= '<a class="sc load" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '">';
			} else {
				$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '">';
			}
			if ( zm_get_option( 'lazy_s' ) ) {
				$html .= '<a class="sc load" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="' . $loadimg . '" data-original="';
			} else {
				$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="';
			}

			$html .= be_get_image_url( attachment_url_to_postid( $full_image_url[0] ), array( $width, $height ), true ) . '" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '">';
			$html .= '</a>';
		} else {
			$content = $post->post_content;
			preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
			$n = count($strResult[1]);
			if ($n > 0) {
				if ( zm_get_option( 'lazy_s' ) ) {
					$be_get_img = 'data-src="' . $strResult[1][0] . '"';
				} else {
					$be_get_img = 'style="background-image: url(' . $strResult[1][0] . ');"';
				}

				$parseResult = wp_parse_url( $strResult[1][0] );
				if ( is_ext_img( $strResult, $parseResult, $domains ) ) {
					if (zm_get_option('lazy_s')) {
						$html .= '<a class="sc load" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="' . $loadimg . '" data-original="';
					} else {
						$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="';
					}

					if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( 'img_way' ) == 'd_img' ) ) {
						$html .= get_template_directory_uri() . '/prune.php?src=' . $strResult[1][0] . '&w=' . $width . '&h=' . $height . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
					}

					if ( zm_get_option( 'img_way' ) == 's_img' ) {
						$html .= be_get_image_url( attachment_url_to_postid( $strResult[1][0]  ), array( $width, $height ), true ) . '" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
					}

				} else {
					$html .= '<div class="thumbs-t lazy"><a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '" ' . $be_get_img . '></a></div>';
				}

				if ( zm_get_option( 'img_way' ) == 'o_img' ) {
					$html .= $strResult[1][0] . '?x-oss-process=image/resize,m_fill,w_' . $width . ' ,h_' . $height . ', limit_0" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'q_img' ) {
					$html .= $strResult[1][0] . '?imageMogr2/gravity/Center/crop/' . $width . 'x' . $height . '" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'upyun' ) {
					$html .= $strResult[1][0] . '!/both/' . $width . 'x' . $height . '/format/webp/lossless/true" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'cos_img' ) {
					$html .= $strResult[1][0] . '?imageView2/1/w/' . $width . '/h/' . $height . '/q/85" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
				}
			} else { 
				if ( zm_get_option( 'lazy_s' ) ) {
					$html .= '<a class="sc load" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="' . $loadimg . '" data-original="';
				} else {
					$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="';
				}

				if ( ! zm_get_option( 'randimg_crop' ) ) {
					$html .= $src . '"';
				} else {
					if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( 'img_way' ) == 'd_img' ) ) {
						$html .= get_template_directory_uri() . '/prune.php?src=' . $src . '&w=' . $width . '&h=' . $height . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1"';
					}

					elseif ( zm_get_option( 'img_way' ) == 's_img' ) {
						$html .= be_get_image_url( attachment_url_to_postid( $src ), array( $width, $height ), true ) . '"';
					}

					elseif ( zm_get_option( 'img_way' ) == 'o_img' ) {
						$html .= $src . '?x-oss-process=image/resize,m_fill,w_' . $width . ' ,h_' . $height . ', limit_0"';
					}

					elseif ( zm_get_option( 'img_way' ) == 'q_img' ) {
						$html .= $src . '?imageMogr2/gravity/Center/crop/' . $width . 'x' . $height . '"';
					}

					elseif ( zm_get_option( 'img_way' ) == 'upyun' ) {
						$html .= $src . '!/both/' . $width . 'x' . $height . '/format/webp/lossless/true"';
					}

					elseif ( zm_get_option( 'img_way' ) == 'cos_img' ) {
						$html .= $src . '?imageView2/1/w/' . $width . '/h/' . $height . '/q/85"';
					}
				}
	
				$html .= ' alt="' . $post->post_title .'" width="' . $width . '" height="' . $height . '"></a>';
			}
		}
	}
	return $html;
}

// 图像日志缩略图
function format_image_thumbnail() {
	global $post;
	$html = '';
	$img_a = '';
	$img_b = '';
	$img_c = '';
	$img_d = '';
	$domains  = explode( ',', zm_get_option( 'allowed_domains' ) );
	$width    = zm_get_option( 'img_w' );
	$height   = zm_get_option( 'img_h' );
	$loadimg  = be_lazy_thumb( $width, $height );
	$rel      = zm_get_option( 'img_rel_nofollow' ) ? 'external nofollow' : 'bookmark';
	$content  = $post->post_content;
	preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
	$n = count($strResult[1]);
	if ( $n > 3 ) {
		if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( 'img_way') == 'd_img' ) ) {
			$parseResult = wp_parse_url( $strResult[1][0] );
			if ( is_ext_img( $strResult, $parseResult, $domains ) ) {
				$img_a = get_template_directory_uri() . '/prune.php?src=' . $strResult[1][0] . '&w=' . $width . '&h=' . $height . '&a='.zm_get_option( 'crop_top' ) . '&zc=1';
				$img_b = get_template_directory_uri() . '/prune.php?src=' . $strResult[1][1] . '&w=' . $width . '&h=' . $height . '&a='.zm_get_option( 'crop_top' ) . '&zc=1';
				$img_c = get_template_directory_uri() . '/prune.php?src=' . $strResult[1][2] . '&w=' . $width . '&h=' . $height . '&a='.zm_get_option( 'crop_top' ) . '&zc=1';
				$img_d = get_template_directory_uri() . '/prune.php?src=' . $strResult[1][3] . '&w=' . $width . '&h=' . $height . '&a='.zm_get_option( 'crop_top' ) . '&zc=1';

				if ( zm_get_option( 'lazy_s' ) ) {
					$html .= '<div class="f4"><div class="format-img"><a class="sc load" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'"><img src="' . $loadimg . '" data-original="' . $img_a . '" alt="'.$post->post_title .'" width="'.$width.'" height="'.$height.'"></a></div></div>';
					$html .= '<div class="f4"><div class="format-img"><a class="sc load" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'"><img src="' . $loadimg . '" data-original="' . $img_b . '" alt="'.$post->post_title .'" width="'.$width.'" height="'.$height.'"></a></div></div>';
					$html .= '<div class="f4"><div class="format-img"><a class="sc load" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'"><img src="' . $loadimg . '" data-original="' . $img_c . '" alt="'.$post->post_title .'" width="'.$width.'" height="'.$height.'"></a></div></div>';
					$html .= '<div class="f4"><div class="format-img"><a class="sc load" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'"><img src="' . $loadimg . '" data-original="' . $img_d . '" alt="'.$post->post_title .'" width="'.$width.'" height="'.$height.'"></a></div></div>';
				} else {
					$html .= '<div class="f4"><div class="format-img"><a class="sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'"><img src="' . $img_a . '" alt="'.$post->post_title .'" width="'.$width.'" height="'.$height.'"></a></div></div>';
					$html .= '<div class="f4"><div class="format-img"><a class="sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'"><img src="' . $img_b . '" alt="'.$post->post_title .'" width="'.$width.'" height="'.$height.'"></a></div></div>';
					$html .= '<div class="f4"><div class="format-img"><a class="sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'"><img src="' . $img_c . '" alt="'.$post->post_title .'" width="'.$width.'" height="'.$height.'"></a></div></div>';
					$html .= '<div class="f4"><div class="format-img"><a class="sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'"><img src="' . $img_d . '" alt="'.$post->post_title .'" width="'.$width.'" height="'.$height.'"></a></div></div>';
				}
			} else {
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

				$html .= '<div class="f4"><div class="format-img"><div class="thumbs-b lazy"><a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" ' . $be_get_img_a . '></a></div></div></div>';
				$html .= '<div class="f4"><div class="format-img"><div class="thumbs-b lazy"><a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" ' . $be_get_img_b . '></a></div></div></div>';
				$html .= '<div class="f4"><div class="format-img"><div class="thumbs-b lazy"><a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" ' . $be_get_img_c . '></a></div></div></div>';
				$html .= '<div class="f4"><div class="format-img"><div class="thumbs-b lazy"><a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" ' . $be_get_img_d . '></a></div></div></div>';
			}
		} else {
			if ( zm_get_option( 'img_way') == 's_img' ) {
				$img_a = be_get_image_url( attachment_url_to_postid( $strResult[1][0] ), array( $width, $height ), true );
				$img_b = be_get_image_url( attachment_url_to_postid( $strResult[1][1] ), array( $width, $height ), true );
				$img_c = be_get_image_url( attachment_url_to_postid( $strResult[1][2] ), array( $width, $height ), true );
				$img_d = be_get_image_url( attachment_url_to_postid( $strResult[1][3] ), array( $width, $height ), true );
			}


			if ( zm_get_option( 'img_way' ) == 'o_img' ) {
				$img_a = $strResult[1][0] . '?x-oss-process=image/resize,m_fill,w_' . $width . ' ,h_' . $height . ', limit_0';
				$img_b = $strResult[1][1] . '?x-oss-process=image/resize,m_fill,w_' . $width . ' ,h_' . $height . ', limit_0';
				$img_c = $strResult[1][2] . '?x-oss-process=image/resize,m_fill,w_' . $width . ' ,h_' . $height . ', limit_0';
				$img_d = $strResult[1][3] . '?x-oss-process=image/resize,m_fill,w_' . $width . ' ,h_' . $height . ', limit_0';
			}

			if ( zm_get_option( 'img_way' ) == 'q_img' ) {
				$img_a = $strResult[1][0] . '?imageMogr2/gravity/Center/crop/' . $width . 'x' . $height;
				$img_b = $strResult[1][1] . '?imageMogr2/gravity/Center/crop/' . $width . 'x' . $height;
				$img_c = $strResult[1][2] . '?imageMogr2/gravity/Center/crop/' . $width . 'x' . $height;
				$img_d = $strResult[1][3] . '?imageMogr2/gravity/Center/crop/' . $width . 'x' . $height;
			}

			if ( zm_get_option( 'img_way' ) == 'upyun' ) {
				$img_a = $strResult[1][0] . '!/both/' . $width . 'x' . $height . '/format/webp/lossless/true';
				$img_b = $strResult[1][1] . '!/both/' . $width . 'x' . $height . '/format/webp/lossless/true';
				$img_c = $strResult[1][2] . '!/both/' . $width . 'x' . $height . '/format/webp/lossless/true';
				$img_d = $strResult[1][3] . '!/both/' . $width . 'x' . $height . '/format/webp/lossless/true';
			}

			if ( zm_get_option( 'img_way' ) == 'cos_img' ) {
				$img_a = $strResult[1][0] . '?imageView2/1/w/' . $width . '/h/' . $height . '/q/85';
				$img_b = $strResult[1][1] . '?imageView2/1/w/' . $width . '/h/' . $height . '/q/85';
				$img_c = $strResult[1][2] . '?imageView2/1/w/' . $width . '/h/' . $height . '/q/85';
				$img_d = $strResult[1][3] . '?imageView2/1/w/' . $width . '/h/' . $height . '/q/85';
			}

			if ( zm_get_option( 'lazy_s' ) ) {
				$html .= '<div class="f4"><div class="format-img"><a class="sc load" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'"><img src="' . $loadimg . '" data-original="' . $img_a . '" alt="'.$post->post_title .'" width="'.$width.'" height="'.$height.'"></a></div></div>';
				$html .= '<div class="f4"><div class="format-img"><a class="sc load" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'"><img src="' . $loadimg . '" data-original="' . $img_b . '" alt="'.$post->post_title .'" width="'.$width.'" height="'.$height.'"></a></div></div>';
				$html .= '<div class="f4"><div class="format-img"><a class="sc load" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'"><img src="' . $loadimg . '" data-original="' . $img_c . '" alt="'.$post->post_title .'" width="'.$width.'" height="'.$height.'"></a></div></div>';
				$html .= '<div class="f4"><div class="format-img"><a class="sc load" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'"><img src="' . $loadimg . '" data-original="' . $img_d . '" alt="'.$post->post_title .'" width="'.$width.'" height="'.$height.'"></a></div></div>';
			} else {
				$html .= '<div class="f4"><div class="format-img"><a class="sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'"><img src="' . $img_a . '" alt="'.$post->post_title .'" width="'.$width.'" height="'.$height.'"></a></div></div>';
				$html .= '<div class="f4"><div class="format-img"><a class="sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'"><img src="' . $img_b . '" alt="'.$post->post_title .'" width="'.$width.'" height="'.$height.'"></a></div></div>';
				$html .= '<div class="f4"><div class="format-img"><a class="sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'"><img src="' . $img_c . '" alt="'.$post->post_title .'" width="'.$width.'" height="'.$height.'"></a></div></div>';
				$html .= '<div class="f4"><div class="format-img"><a class="sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'"><img src="' . $img_d . '" alt="'.$post->post_title .'" width="'.$width.'" height="'.$height.'"></a></div></div>';
			}
		}
	} else {
		$html .= '<div class="f4-tip">文章中至少添加4张图片才能显示</div>';
	}
	return $html;
}

// 图片布局缩略图
function zm_grid_thumbnail() {
	global $post;
	$html = '';
	$domains = explode( ',', zm_get_option( 'allowed_domains' ) );
	$beimg   = str_replace( array( "\n", "\r", " " ), '', explode( ',', zm_get_option( 'random_image_url' ) ) );
	$src     = $beimg[array_rand( $beimg )];
	$width   = zm_get_option( 'grid_w' );
	$height  = zm_get_option( 'grid_h' );
	$loadimg = be_lazy_thumb( $width, $height );
	$fancy   = get_post_meta( get_the_ID(), 'fancy_box', true );
	$rel     = zm_get_option( 'img_rel_nofollow' ) ? 'external nofollow' : 'bookmark';
	if ( get_post_meta( get_the_ID(), 'fancy_box', true ) ) {
		$html .= '<a class="fancy-box" rel="' . $rel . '" href="'. $fancy . '"></a>';
	}

	if ( get_post_meta(get_the_ID(), 'thumbnail', true) ) {
		$image = get_post_meta(get_the_ID(), 'thumbnail', true);
		if (zm_get_option('lazy_s')) {
			$html .= '<a class="sc load" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'"><img src="' . $loadimg . '" data-original="';
		} else {
			$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'"><img src="';
		}

		if ( ! zm_get_option( 'thumbnail_crop' ) ) {
			$html .= $image . '"';
		} else {
			if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( 'img_way' ) == 'd_img' ) ) {
				$html .= get_template_directory_uri() . '/prune.php?src=' . $image . '&w=' . $width . '&h=' . $height . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1"';
			}

			elseif ( zm_get_option( 'img_way' ) == 's_img' ) {
				$html .= be_get_image_url( attachment_url_to_postid( $image ), array( $width, $height ), true ) . '"';
			}

			elseif ( zm_get_option( 'img_way' ) == 'o_img' ) {
				$html .= $image . '?x-oss-process=image/resize,m_fill,w_' . $width . ' ,h_' . $height . ', limit_0"';
			}

			elseif ( zm_get_option( 'img_way' ) == 'q_img' ) {
				$html .= $image . '?imageMogr2/gravity/Center/crop/' . $width . 'x' . $height . '"';
			}

			elseif ( zm_get_option( 'img_way' ) == 'upyun' ) {
				$html .= $image . '!/both/' . $width . 'x' . $height . '/format/webp/lossless/true"';
			}

			elseif ( zm_get_option( 'img_way' ) == 'cos_img' ) {
				$html .= $image . '?imageView2/1/w/' . $width . '/h/' . $height . '/q/85"';
			}
		}

		$html .= ' alt="'.$post->post_title .'" width="'.$width.'" height="'.$height.'"></a>';
	} else {
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'content');

			if ( zm_get_option( 'lazy_s' ) ) {
				$html .= '<a class="sc load" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="' . $loadimg . '" data-original="';
			} else {
				$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="';
			}

			$html .= be_get_image_url( attachment_url_to_postid( $full_image_url[0] ), array( $width, $height ), true ) . '" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '">';
			$html .= '</a>';
		} else {
			$content = $post->post_content;
			preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
			$n = count($strResult[1]);
			if ($n > 0 && !get_post_meta( get_the_ID(), 'rand_img', true )) {
				if ( zm_get_option( 'lazy_s' ) ) {
					$be_get_img = 'data-src="' . $strResult[1][0] . '"';
				} else {
					$be_get_img = 'style="background-image: url(' . $strResult[1][0] . ');"';
				}

				$parseResult = wp_parse_url( $strResult[1][0] );
				if ( is_ext_img( $strResult, $parseResult, $domains ) ) {
					if (zm_get_option('lazy_s')) {
						$html .= '<a class="sc load" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'"><img src="' . $loadimg . '" data-original="';
					} else {
						$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'"><img src="';
					}


					if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( 'img_way' ) == 'd_img' ) ) {
						$html .= get_template_directory_uri() . '/prune.php?src=' . $strResult[1][0] . '&w=' . $width . '&h=' . $height . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
					}

					if ( zm_get_option( 'img_way' ) == 's_img' ) {
						$html .= be_get_image_url( attachment_url_to_postid( $strResult[1][0]  ), array( $width, $height ), true ) . '" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
					}

				} else {
					$html .= '<div class="thumbs-h lazy"><a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" ' . $be_get_img . '></a></div>';
				}

				if ( zm_get_option( 'img_way' ) == 'o_img' ) {
					$html .= $strResult[1][0] . '?x-oss-process=image/resize,m_fill,w_' . $width . ' ,h_' . $height . ', limit_0" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'q_img' ) {
					$html .= $strResult[1][0] . '?imageMogr2/gravity/Center/crop/' . $width . 'x' . $height . '" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'upyun' ) {
					$html .= $strResult[1][0] . '!/both/' . $width . 'x' . $height . '/format/webp/lossless/true" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'cos_img' ) {
					$html .= $strResult[1][0] . '?imageView2/1/w/' . $width . '/h/' . $height . '/q/85" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
				}
			} else { 
				if ( zm_get_option( 'lazy_s' ) ) {
					$html .= '<a class="sc load" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="' . $loadimg . '" data-original="';
				} else {
					$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="';
				}

				if ( ! zm_get_option( 'randimg_crop' ) ) {
					$html .= $src . '"';
				} else {
					if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( 'img_way' ) == 'd_img' ) ) {
						$html .= get_template_directory_uri() . '/prune.php?src=' . $src . '&w=' . $width . '&h=' . $height . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1"';
					}

					elseif ( zm_get_option( 'img_way' ) == 's_img' ) {
						$html .= be_get_image_url( attachment_url_to_postid( $src ), array( $width, $height ), true ) . '"';
					}

					elseif ( zm_get_option( 'img_way' ) == 'o_img' ) {
						$html .= $src . '?x-oss-process=image/resize,m_fill,w_' . $width . ' ,h_' . $height . ', limit_0"';
					}

					elseif ( zm_get_option( 'img_way' ) == 'q_img' ) {
						$html .= $src . '?imageMogr2/gravity/Center/crop/' . $width . 'x' . $height . '"';
					}

					elseif ( zm_get_option( 'img_way' ) == 'upyun' ) {
						$html .= $src . '!/both/' . $width . 'x' . $height . '/format/webp/lossless/true"';
					}

					elseif ( zm_get_option( 'img_way' ) == 'cos_img' ) {
						$html .= $src . '?imageView2/1/w/' . $width . '/h/' . $height . '/q/85"';
					}
				}
	
				$html .= ' alt="' . $post->post_title .'" width="' . $width . '" height="' . $height . '"></a>';
			}
		}
	}
	return $html;
}

// 宽缩略图分类
function zm_full_thumbnail() {
	global $post;
	$html = '';
	$domains = explode( ',', zm_get_option( 'allowed_domains' ) );
	$beimg   = str_replace( array( "\n", "\r", " " ), '', explode( ',', zm_get_option( 'random_long_url' ) ) );
	$src     = $beimg[array_rand( $beimg )];
	$width   = zm_get_option( 'img_full_w' );
	$height  = zm_get_option( 'img_full_h' );
	$loadimg = be_lazy_thumb( $width, $height );
	$rel     = zm_get_option( 'img_rel_nofollow' ) ? 'external nofollow' : 'bookmark';
	if ( get_post_meta(get_the_ID(), 'full_thumbnail', true) ) {
		$image = get_post_meta(get_the_ID(), 'full_thumbnail', true);
		if (zm_get_option('lazy_s')) {
			$html .= '<a class="sc load" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'"><img src="' . $loadimg . '" data-original="';
		} else {
			$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'"><img src="';
		}

		if ( ! zm_get_option( 'thumbnail_crop' ) ) {
			$html .= $image . '"';
		} else {
			if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( 'img_way' ) == 'd_img' ) ) {
				$html .= get_template_directory_uri() . '/prune.php?src=' . $image . '&w=' . $width . '&h=' . $height . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1"';
			}

			elseif ( zm_get_option( 'img_way' ) == 's_img' ) {
				$html .= be_get_image_url( attachment_url_to_postid( $image ), array( $width, $height ), true ) . '"';
			}

			elseif ( zm_get_option( 'img_way' ) == 'o_img' ) {
				$html .= $image . '?x-oss-process=image/resize,m_fill,w_' . $width . ' ,h_' . $height . ', limit_0"';
			}

			elseif ( zm_get_option( 'img_way' ) == 'q_img' ) {
				$html .= $image . '?imageMogr2/gravity/Center/crop/' . $width . 'x' . $height . '"';
			}

			elseif ( zm_get_option( 'img_way' ) == 'upyun' ) {
				$html .= $image . '!/both/' . $width . 'x' . $height . '/format/webp/lossless/true"';
			}

			elseif ( zm_get_option( 'img_way' ) == 'cos_img' ) {
				$html .= $image . '?imageView2/1/w/' . $width . '/h/' . $height . '/q/85"';
			}
		}

		$html .= ' alt="'.$post->post_title .'" width="'.$width.'" height="'.$height.'"></a>';
	} else {
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'content');
			if (zm_get_option('lazy_s')) {
				$html .= '<a class="sc load" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'">';
			} else {
				$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'">';
			}

			if ( zm_get_option( 'lazy_s' ) ) {
				$html .= '<a class="sc load" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="' . $loadimg . '" data-original="';
			} else {
				$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="';
			}

			$html .= be_get_image_url( attachment_url_to_postid( $full_image_url[0] ), array( $width, $height ), true ) . '" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '">';
			$html .= '</a>';
		} else {
			$content = $post->post_content;
			preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
			$n = count($strResult[1]);
			if ($n > 0 && !get_post_meta( get_the_ID(), 'rand_img', true )) {
				if ( zm_get_option( 'lazy_s' ) ) {
					$be_get_img = 'data-src="' . $strResult[1][0] . '"';
				} else {
					$be_get_img = 'style="background-image: url(' . $strResult[1][0] . ');"';
				}

				$parseResult = wp_parse_url( $strResult[1][0] );
				if ( is_ext_img( $strResult, $parseResult, $domains ) ) {
					if (zm_get_option('lazy_s')) {
						$html .= '<a class="sc load" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'"><img src="' . $loadimg . '" data-original="';
					} else {
						$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'"><img src="';
					}

					if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( 'img_way' ) == 'd_img' ) ) {
						$html .= get_template_directory_uri() . '/prune.php?src=' . $strResult[1][0] . '&w=' . $width . '&h=' . $height . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
					}

					if ( zm_get_option( 'img_way' ) == 's_img' ) {
						$html .= be_get_image_url( attachment_url_to_postid( $strResult[1][0]  ), array( $width, $height ), true ) . '" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
					}

				} else {
					$html .= '<div class="thumbs-w lazy"><a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" ' . $be_get_img . '></a></div>';
				}

				if ( zm_get_option( 'img_way' ) == 'o_img' ) {
					$html .= $strResult[1][0] . '?x-oss-process=image/resize,m_fill,w_' . $width . ' ,h_' . $height . ', limit_0" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'q_img' ) {
					$html .= $strResult[1][0] . '?imageMogr2/gravity/Center/crop/' . $width . 'x' . $height . '" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'upyun' ) {
					$html .= $strResult[1][0] . '!/both/' . $width . 'x' . $height . '/format/webp/lossless/true" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'cos_img' ) {
					$html .= $strResult[1][0] . '?imageView2/1/w/' . $width . '/h/' . $height . '/q/85" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
				}
			} else { 
				if ( zm_get_option( 'lazy_s' ) ) {
					$html .= '<a class="sc load" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="' . $loadimg . '" data-original="';
				} else {
					$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="';
				}

				if ( ! zm_get_option( 'randimg_crop' ) ) {
					$html .= $src . '"';
				} else {
					if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( 'img_way' ) == 'd_img' ) ) {
						$html .= get_template_directory_uri() . '/prune.php?src=' . $src . '&w=' . $width . '&h=' . $height . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1"';
					}

					elseif ( zm_get_option( 'img_way' ) == 's_img' ) {
						$html .= be_get_image_url( attachment_url_to_postid( $src ), array( $width, $height ), true ) . '"';
					}

					elseif ( zm_get_option( 'img_way' ) == 'o_img' ) {
						$html .= $src . '?x-oss-process=image/resize,m_fill,w_' . $width . ' ,h_' . $height . ', limit_0"';
					}

					elseif ( zm_get_option( 'img_way' ) == 'q_img' ) {
						$html .= $src . '?imageMogr2/gravity/Center/crop/' . $width . 'x' . $height . '"';
					}

					elseif ( zm_get_option( 'img_way' ) == 'upyun' ) {
						$html .= $src . '!/both/' . $width . 'x' . $height . '/format/webp/lossless/true"';
					}

					elseif ( zm_get_option( 'img_way' ) == 'cos_img' ) {
						$html .= $src . '?imageView2/1/w/' . $width . '/h/' . $height . '/q/85"';
					}
				}
	
				$html .= ' alt="' . $post->post_title .'" width="' . $width . '" height="' . $height . '"></a>';
			}
		}
	}
	return $html;
}

// 公司左右图
function gr_wd_thumbnail() {
	global $post;
	$html = '';
	$domains = explode( ',', zm_get_option( 'allowed_domains' ) );
	$beimg   = str_replace( array( "\n", "\r", " " ), '', explode( ',', zm_get_option( 'random_image_url' ) ) );
	$src     = $beimg[array_rand( $beimg )];
	$width   = '700';
	$height  = '380';
	$loadimg = be_lazy_thumb( $width, $height );
	$rel     = zm_get_option( 'img_rel_nofollow' ) ? 'external nofollow' : 'bookmark';
	if ( get_post_meta(get_the_ID(), 'wd_img', true) ) {
		$image = get_post_meta(get_the_ID(), 'wd_img', true);
		if (zm_get_option('lazy_s')) {
			$html .= '<a class="sc load" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'"><img src="' . $loadimg . '" data-original="';
		} else {
			$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'"><img src="';
		}

		if ( ! zm_get_option( 'thumbnail_crop' ) ) {
			$html .= $image . '"';
		} else {
			if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( 'img_way' ) == 'd_img' ) ) {
				$html .= get_template_directory_uri() . '/prune.php?src=' . $image . '&w=' . $width . '&h=' . $height . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1"';
			}

			elseif ( zm_get_option( 'img_way' ) == 's_img' ) {
				$html .= be_get_image_url( attachment_url_to_postid( $image ), array( $width, $height ), true ) . '"';
			}

			elseif ( zm_get_option( 'img_way' ) == 'o_img' ) {
				$html .= $image . '?x-oss-process=image/resize,m_fill,w_' . $width . ' ,h_' . $height . ', limit_0"';
			}

			elseif ( zm_get_option( 'img_way' ) == 'q_img' ) {
				$html .= $image . '?imageMogr2/gravity/Center/crop/' . $width . 'x' . $height . '"';
			}

			elseif ( zm_get_option( 'img_way' ) == 'upyun' ) {
				$html .= $image . '!/both/' . $width . 'x' . $height . '/format/webp/lossless/true"';
			}

			elseif ( zm_get_option( 'img_way' ) == 'cos_img' ) {
				$html .= $image . '?imageView2/1/w/' . $width . '/h/' . $height . '/q/85"';
			}
		}

		$html .= ' alt="'.$post->post_title .'" width="700" height="380"></a>';
	} else {
		$content = $post->post_content;
		preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
		$n = count($strResult[1]);
		if ($n > 0) {
			if ( zm_get_option( 'lazy_s' ) ) {
				$be_get_img = 'data-src="' . $strResult[1][0] . '"';
			} else {
				$be_get_img = 'style="background-image: url(' . $strResult[1][0] . ');"';
			}

			$parseResult = wp_parse_url( $strResult[1][0] );
			if ( is_ext_img( $strResult, $parseResult, $domains ) ) {
				if (zm_get_option('lazy_s')) {
					$html .= '<a class="sc load" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'"><img src="' . $loadimg . '" data-original="';
				} else {
					$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'"><img src="';
				}

				if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( 'img_way' ) == 'd_img' ) ) {
					$html .= get_template_directory_uri() . '/prune.php?src=' . $strResult[1][0] . '&w=' . $width . '&h=' . $height . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
				}

				if ( zm_get_option( 'img_way' ) == 's_img' ) {
					$html .= be_get_image_url( attachment_url_to_postid( $strResult[1][0]  ), array( $width, $height ), true ) . '" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
				}

			} else {
				$html .= '<div class="thumbs-lr lazy"><a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" ' . $be_get_img . '></a></div>';
			}

			if ( zm_get_option( 'img_way' ) == 'o_img' ) {
				$html .= $strResult[1][0] . '?x-oss-process=image/resize,m_fill,w_700,h_380, limit_0" alt="' . $post->post_title . '" width=' . $width . ' height=' . $height . '></a>';
			}

			if ( zm_get_option( 'img_way' ) == 'q_img' ) {
				$html .= $strResult[1][0] . '?imageMogr2/gravity/Center/crop/700x380" alt="' . $post->post_title . '" width=' . $width . ' height=' . $height . '></a>';
			}

			if ( zm_get_option( 'img_way' ) == 'upyun' ) {
				$html .= $strResult[1][0] . '!/both/700x380/format/webp/lossless/true" alt="' . $post->post_title . '" width=' . $width . ' height=' . $height . '></a>';
			}

			if ( zm_get_option( 'img_way' ) == 'cos_img' ) {
				$html .= $strResult[1][0] . '?imageView2/1/w/700/h/380/q/85" alt="' . $post->post_title . '" width=' . $width . ' height=' . $height . '></a>';
			}
		} else { 
			if ( zm_get_option( 'lazy_s' ) ) {
				$html .= '<a class="sc load" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="' . $loadimg . '" data-original="';
			} else {
				$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="';
			}

			if ( ! zm_get_option( 'randimg_crop' ) ) {
				$html .= $src . '"';
			} else {
				if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( 'img_way' ) == 'd_img' ) ) {
					$html .= get_template_directory_uri() . '/prune.php?src=' . $src . '&w=' . $width . '&h=' . $height . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1"';
				}

				elseif ( zm_get_option( 'img_way' ) == 's_img' ) {
					$html .= be_get_image_url( attachment_url_to_postid( $src ), array( $width, $height ), true ) . '"';
				}

				elseif ( zm_get_option( 'img_way' ) == 'o_img' ) {
					$html .= $src . '?x-oss-process=image/resize,m_fill,w_' . $width . ' ,h_' . $height . ', limit_0"';
				}

				elseif ( zm_get_option( 'img_way' ) == 'q_img' ) {
					$html .= $src . '?imageMogr2/gravity/Center/crop/' . $width . 'x' . $height . '"';
				}

				elseif ( zm_get_option( 'img_way' ) == 'upyun' ) {
					$html .= $src . '!/both/' . $width . 'x' . $height . '/format/webp/lossless/true"';
				}

				elseif ( zm_get_option( 'img_way' ) == 'cos_img' ) {
					$html .= $src . '?imageView2/1/w/' . $width . '/h/' . $height . '/q/85"';
				}
			}
	
			$html .= ' alt="' . $post->post_title .'" width="' . $width . '" height="' . $height . '"></a>';
		}
	}
	return $html;
}

// 链接形式
function zm_thumbnail_link() {
	global $post;
	$html = '';
	$html .= textimg();
	$domains = explode( ',', zm_get_option( 'allowed_domains' ) );
	$beimg   = str_replace( array( "\n", "\r", " " ), '', explode( ',', zm_get_option( 'random_image_url' ) ) );
	$src     = $beimg[array_rand( $beimg )];
	$width   = zm_get_option( 'img_w' );
	$height  = zm_get_option( 'img_h' );
	$loadimg = be_lazy_thumb( $width, $height );
	$fancy   = get_post_meta( get_the_ID(), 'fancy_box', true );
	$rel     = zm_get_option( 'img_rel_nofollow' ) ? 'external nofollow' : 'bookmark';
	if ( get_post_meta( get_the_ID(), 'fancy_box', true ) ) {
		$html .= '<a class="fancy-box" rel="' . $rel . '" href="'. $fancy . '"></a>';
	}
	$direct = get_post_meta(get_the_ID(), 'direct', true);

	if ( get_post_meta(get_the_ID(), 'thumbnail', true) ) {
		$image = get_post_meta(get_the_ID(), 'thumbnail', true);
		if (zm_get_option('lazy_s')) {
			$html .= '<a class="sc load" rel="' . $rel . '" ' . goal() . ' href="'.$direct.'"><img src="' . $loadimg . '" data-original="';
		} else {
			$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="'.$direct.'"><img src="';
		}

		if ( ! zm_get_option( 'thumbnail_crop' ) ) {
			$html .= $image . '"';
		} else {
			if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( 'img_way' ) == 'd_img' ) ) {
				$html .= get_template_directory_uri() . '/prune.php?src=' . $image . '&w=' . $width . '&h=' . $height . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1"';
			}

			elseif ( zm_get_option( 'img_way' ) == 's_img' ) {
				$html .= be_get_image_url( attachment_url_to_postid( $image ), array( $width, $height ), true ) . '"';
			}

			elseif ( zm_get_option( 'img_way' ) == 'o_img' ) {
				$html .= $image . '?x-oss-process=image/resize,m_fill,w_' . $width . ' ,h_' . $height . ', limit_0"';
			}

			elseif ( zm_get_option( 'img_way' ) == 'q_img' ) {
				$html .= $image . '?imageMogr2/gravity/Center/crop/' . $width . 'x' . $height . '"';
			}

			elseif ( zm_get_option( 'img_way' ) == 'upyun' ) {
				$html .= $image . '!/both/' . $width . 'x' . $height . '/format/webp/lossless/true"';
			}

			elseif ( zm_get_option( 'img_way' ) == 'cos_img' ) {
				$html .= $image . '?imageView2/1/w/' . $width . '/h/' . $height . '/q/85"';
			}
		}

		$html .= ' alt="'.$post->post_title .'" width="'.$width.'" height="'.$height.'"></a>';
	} else {
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'content');
			if (zm_get_option('lazy_s')) {
				$html .= '<a class="sc load" rel="' . $rel . '" ' . goal() . ' href="' . $direct . '">';
			} else {
				$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="' . $direct . '">';
			}

			if ( zm_get_option( 'lazy_s' ) ) {
				$html .= '<a class="sc load" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="' . $loadimg . '" data-original="';
			} else {
				$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="';
			}

			$html .= be_get_image_url( attachment_url_to_postid( $full_image_url[0] ), array( $width, $height ), true ) . '" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '">';
			$html .= '</a>';
		} else {
			$content = $post->post_content;
			preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
			$n = count($strResult[1]);
			if ($n > 0 && !get_post_meta( get_the_ID(), 'rand_img', true )) {
				if ( zm_get_option( 'lazy_s' ) ) {
					$be_get_img = 'data-src="' . $strResult[1][0] . '"';
				} else {
					$be_get_img = 'style="background-image: url(' . $strResult[1][0] . ');"';
				}

				$parseResult = wp_parse_url( $strResult[1][0] );
				if ( is_ext_img( $strResult, $parseResult, $domains ) ) {

					if (zm_get_option('lazy_s')) {
						$html .= '<a class="sc load" rel="' . $rel . '" ' . goal() . ' href="'.$direct.'"><img src="' . $loadimg . '" data-original="';
					} else {
						$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="' . $direct . '"><img src="';
					}

					if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( 'img_way' ) == 'd_img' ) ) {
						$html .= get_template_directory_uri() . '/prune.php?src=' . $strResult[1][0] . '&w=' . $width . '&h=' . $height . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
					}

					if ( zm_get_option( 'img_way' ) == 's_img' ) {
						$html .= be_get_image_url( attachment_url_to_postid( $strResult[1][0]  ), array( $width, $height ), true ) . '" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
					}

				} else {
					$html .= '<div class="thumbs-l lazy"><a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" ' . $be_get_img . '></a></div>';
				}

				if ( zm_get_option( 'img_way' ) == 'o_img' ) {
					$html .= $strResult[1][0] . '?x-oss-process=image/resize,m_fill,w_' . $width . ' ,h_' . $height . ', limit_0" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'q_img' ) {
					$html .= $strResult[1][0] . '?imageMogr2/gravity/Center/crop/' . $width . 'x' . $height . '" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'upyun' ) {
					$html .= $strResult[1][0] . '!/both/' . $width . 'x' . $height . '/format/webp/lossless/true" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'cos_img' ) {
					$html .= $strResult[1][0] . '?imageView2/1/w/' . $width . '/h/' . $height . '/q/85" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
				}
			} else { 
				if ( zm_get_option( 'lazy_s' ) ) {
					$html .= '<a class="sc load" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="' . $loadimg . '" data-original="';
				} else {
					$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="';
				}

				if ( ! zm_get_option( 'randimg_crop' ) ) {
					$html .= $src . '"';
				} else {
					if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( 'img_way' ) == 'd_img' ) ) {
						$html .= get_template_directory_uri() . '/prune.php?src=' . $src . '&w=' . $width . '&h=' . $height . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1"';
					}

					elseif ( zm_get_option( 'img_way' ) == 's_img' ) {
						$html .= be_get_image_url( attachment_url_to_postid( $src ), array( $width, $height ), true ) . '"';
					}

					elseif ( zm_get_option( 'img_way' ) == 'o_img' ) {
						$html .= $src . '?x-oss-process=image/resize,m_fill,w_' . $width . ' ,h_' . $height . ', limit_0"';
					}

					elseif ( zm_get_option( 'img_way' ) == 'q_img' ) {
						$html .= $src . '?imageMogr2/gravity/Center/crop/' . $width . 'x' . $height . '"';
					}

					elseif ( zm_get_option( 'img_way' ) == 'upyun' ) {
						$html .= $src . '!/both/' . $width . 'x' . $height . '/format/webp/lossless/true"';
					}

					elseif ( zm_get_option( 'img_way' ) == 'cos_img' ) {
						$html .= $src . '?imageView2/1/w/' . $width . '/h/' . $height . '/q/85"';
					}
				}
	
				$html .= ' alt="' . $post->post_title .'" width="' . $width . '" height="' . $height . '"></a>';
			}
		}
	}
	return $html;
}

// 幻灯小工具
function zm_widge_thumbnail() {
	global $post;
	$html = '';
	$domains = explode( ',', zm_get_option( 'allowed_domains' ) );
	$width   = zm_get_option( 'img_s_w' );
	$height  = zm_get_option( 'img_s_h' );
	$rel     = zm_get_option( 'img_rel_nofollow' ) ? 'external nofollow' : 'bookmark';
	if ( get_post_meta(get_the_ID(), 'widge_img', true) ) {
		$image = get_post_meta(get_the_ID(), 'widge_img', true);
		$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'"><img src=';
		$html .= be_get_image_url( attachment_url_to_postid( $image ), array( $width, $height ), true );
		$html .= ' alt="'.$post->post_title .'" width="'.zm_get_option('img_s_w').'" height="'.$height.'"></a>';
	} else {
		$content = $post->post_content;
		preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
		$n = count($strResult[1]);
		if ($n > 0) {
			$parseResult = wp_parse_url( $strResult[1][0] );
			if ( is_ext_img( $strResult, $parseResult, $domains ) ) {
				if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( 'img_way' ) == 'd_img' ) ) {
					$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="' . get_template_directory_uri() . '/prune.php?src=' . $strResult[1][0] . '&w=' . zm_get_option( 'img_s_w' ) . '&h=' . $height . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_s_w' ) . '" height="' . $height . '"></a>';
				}

				if ( zm_get_option( 'img_way' ) == 's_img' ) {
					$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="' . be_get_image_url( attachment_url_to_postid( $strResult[1][0]  ), array( $width, $height ), true ) . '" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_s_w' ) . '" height="' . $height . '"></a>';
				}

			} else {
				$html .= '<div class="thumbs-sw lazy"><a class="thumbs-back sc" rel="' . $rel . '" href="'.get_permalink().'" style="background-image: url('.$strResult[1][0].');"></a></div>';
			}

			if ( zm_get_option( 'img_way' ) == 'o_img' ) {
				$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="' . $strResult[1][0] . '?x-oss-process=image/resize,m_fill,w_' . zm_get_option( 'img_s_w' ) . ' ,h_' . $height . ', limit_0" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_s_w' ) . '" height="' . $height . '"></a>';
			}

			if ( zm_get_option( 'img_way' ) == 'q_img' ) {
				$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="' . $strResult[1][0] . '?imageMogr2/gravity/Center/crop/' . zm_get_option( 'img_s_w' ) . 'x' . $height . '" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_s_w' ) . '" height="' . $height . '"></a>';
			}

			if ( zm_get_option( 'img_way' ) == 'upyun' ) {
				$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="' . $strResult[1][0] . '!/both/' . zm_get_option( 'img_s_w' ) . 'x' . $height . '/format/webp/lossless/true" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_s_w' ) . '" height="' . $height . '"></a>';
			}

			if ( zm_get_option( 'img_way' ) == 'cos_img' ) {
				$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="' . $strResult[1][0] . '?imageView2/1/w/' . zm_get_option( 'img_s_w' ) . '/h/' . $height . '/q/85" alt="' . $post->post_title . '" width="' . zm_get_option( 'img_s_w' ) . '" height="' . $height . '"></a>';
			}
		}
	}
	return $html;
}

// 图片滚动
function zm_thumbnail_scrolling() {
	global $post;
	$html = '';
	$domains = explode( ',', zm_get_option( 'allowed_domains' ) );
	$beimg   = str_replace( array( "\n", "\r", " " ), '', explode( ',', zm_get_option( 'random_image_url' ) ) );
	$src     = $beimg[array_rand( $beimg )];
	$width   = zm_get_option( 'img_w' );
	$height  = zm_get_option( 'img_h' );
	$fancy   = get_post_meta( get_the_ID(), 'fancy_box', true );
	$rel     = zm_get_option( 'img_rel_nofollow' ) ? 'external nofollow' : 'bookmark';
	if ( get_post_meta( get_the_ID(), 'fancy_box', true ) ) {
		$html .= '<a class="fancy-box" rel="' . $rel . '" href="'. $fancy . '"></a>';
	}

	if ( get_post_meta(get_the_ID(), 'thumbnail', true) ) {
		$image = get_post_meta(get_the_ID(), 'thumbnail', true);
		$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'"><img src="';

		if ( ! zm_get_option( 'thumbnail_crop' ) ) {
			$html .= $image . '"';
		} else {
			if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( 'img_way' ) == 'd_img' ) ) {
				$html .= get_template_directory_uri() . '/prune.php?src=' . $image . '&w=' . $width . '&h=' . $height . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1"';
			}

			elseif ( zm_get_option( 'img_way' ) == 's_img' ) {
				$html .= be_get_image_url( attachment_url_to_postid( $image ), array( $width, $height ), true ) . '"';
			}

			elseif ( zm_get_option( 'img_way' ) == 'o_img' ) {
				$html .= $image . '?x-oss-process=image/resize,m_fill,w_' . $width . ' ,h_' . $height . ', limit_0"';
			}

			elseif ( zm_get_option( 'img_way' ) == 'q_img' ) {
				$html .= $image . '?imageMogr2/gravity/Center/crop/' . $width . 'x' . $height . '"';
			}

			elseif ( zm_get_option( 'img_way' ) == 'upyun' ) {
				$html .= $image . '!/both/' . $width . 'x' . $height . '/format/webp/lossless/true"';
			}

			elseif ( zm_get_option( 'img_way' ) == 'cos_img' ) {
				$html .= $image . '?imageView2/1/w/' . $width . '/h/' . $height . '/q/85"';
			}
		}

		$html .= ' alt="'.$post->post_title .'" width="'.$width.'" height="'.$height.'"></a>';
	} else {
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'content');
			$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'">';

			$html .= '<img src="' . be_get_image_url( attachment_url_to_postid( $full_image_url[0] ), array( $width, $height ), true ) . '" alt="' . get_the_title() . '" width="' . $width . '" height="' . $height . '" >';

			$html .= '</a>';
		} else {
			$content = $post->post_content;
			preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
			$n = count($strResult[1]);
			if ($n > 0) {
				$parseResult = wp_parse_url( $strResult[1][0] );
				if ( is_ext_img( $strResult, $parseResult, $domains ) ) {

					if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( 'img_way') == 'd_img' ) ) {
						$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="' . get_template_directory_uri() . '/prune.php?src=' . $strResult[1][0] . '&w=' . $width . '&h=' . $height . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
					}

					if ( zm_get_option( 'img_way' ) == 's_img' ) {
						$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="' . be_get_image_url( attachment_url_to_postid( $strResult[1][0]  ), array( $width, $height ), true ) . '" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
					}

				} else {
					$html .= '<div class="thumbs-sg lazy"><a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" style="background-image: url('.$strResult[1][0].');"></a></div>';
				}

				if ( zm_get_option( 'img_way' ) == 'o_img' ) {
					$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="' . $strResult[1][0] . '?x-oss-process=image/resize,m_fill,w_' . $width . ' ,h_' . $height . ', limit_0" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'q_img' ) {
					$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="' . $strResult[1][0] . '?imageMogr2/gravity/Center/crop/' . $width . 'x' . $height . '" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'upyun' ) {
					$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="' . $strResult[1][0] . '!/both/' . $width . 'x' . $height . '/format/webp/lossless/true" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
				}

				if ( zm_get_option( 'img_way' ) == 'cos_img' ) {
					$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="' . $strResult[1][0] . '?imageView2/1/w/' . $width . '/h/' . $height . '/q/85" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
				}

			} else {
				$html .= '<a class="sc" rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="';
				if ( ! zm_get_option( 'randimg_crop' ) ) {
					$html .= $src . '"';
				} else {
					if ( ! zm_get_option( 'img_way' ) || ( zm_get_option( 'img_way' ) == 'd_img' ) ) {
						$html .= get_template_directory_uri() . '/prune.php?src=' . $src . '&w=' . $width . '&h=' . $height . '&a=' . zm_get_option( 'crop_top' ) . '&zc=1"';
					}

					elseif ( zm_get_option( 'img_way' ) == 's_img' ) {
						$html .= be_get_image_url( attachment_url_to_postid( $src ), array( $width, $height ), true ) . '"';
					}

					elseif ( zm_get_option( 'img_way' ) == 'o_img' ) {
						$html .= $src . '?x-oss-process=image/resize,m_fill,w_' . $width . ' ,h_' . $height . ', limit_0"';
					}

					elseif ( zm_get_option( 'img_way' ) == 'q_img' ) {
						$html .= $src . '?imageMogr2/gravity/Center/crop/' . $width . 'x' . $height . '"';
					}

					elseif ( zm_get_option( 'img_way' ) == 'upyun' ) {
						$html .= $src . '!/both/' . $width . 'x' . $height . '/format/webp/lossless/true"';
					}

					elseif ( zm_get_option( 'img_way' ) == 'cos_img' ) {
						$html .= $src . '?imageView2/1/w/' . $width . '/h/' . $height . '/q/85"';
					}
				}
				$html .= ' alt="' . $post->post_title . '" width="' . $width.'" height="' . $height . '"></a>';
			}
		}
	}
	return $html;
}

} else {

// 不裁剪
function zm_thumbnail() {
	global $post;
	$html = '';
	$html .= textimg();
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
	$beimg = str_replace( array( "\n", "\r", " " ), '', explode( ',', zm_get_option( 'random_image_url' ) ) );
	$src   = $beimg[array_rand( $beimg )];

	$fancy = get_post_meta( get_the_ID(), 'fancy_box', true );
	$rel   = zm_get_option( 'img_rel_nofollow' ) ? 'external nofollow' : 'bookmark';
	if ( get_post_meta( get_the_ID(), 'fancy_box', true ) ) {
		$html .= '<a class="fancy-box" rel="' . $rel . '" href="'. $fancy . '"></a>';
	}

	$html .= '<div class="thumbs-b lazy">';
	if ( ! zm_get_option( 'rand_only' ) ) {
		// 手动
		if ( get_post_meta( get_the_ID(), 'thumbnail', true ) ) {
			$image = get_post_meta(get_the_ID(), 'thumbnail', true );
			$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" data-src="' . $image .'"></a>';
		} else {
			// 特色
			if ( has_post_thumbnail() ) {
				$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'content');
				$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" data-src="' . $full_image_url[0] . '"></a>';
			} else {
				// 自动
				if ( $n > 0 && !get_post_meta( get_the_ID(), 'rand_img', true ) ) {
					$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" ' . $be_get_img . '></a>';
				} else { 
					// 随机
					$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" data-src="'.$src.'"></a>';
				}
			}
		}
	} else {
		// 随机
		$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" data-src="'.$src.'"></a>';
	}
	$html .= '</div>';
	return $html;
}

// 分类模块宽缩略图
function zm_long_thumbnail() {
	global $post;
	$html = '';
	$rel  = zm_get_option( 'img_rel_nofollow' ) ? 'external nofollow' : 'bookmark';
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
	$beimg = str_replace( array( "\n", "\r", " " ), '', explode( ',', zm_get_option( 'random_long_url' ) ) );
	$src   = $beimg[array_rand( $beimg )];
	$html .= '<div class="thumbs-f lazy">';
	if ( get_post_meta(get_the_ID(), 'long_thumbnail', true) ) {
		$image = get_post_meta(get_the_ID(), 'long_thumbnail', true);
		$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" data-src="' . $image . '"></a>';
	} else {
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'long');
			$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" data-src="' . $full_image_url[0] . '"></a>';
		} else {
			if ( $n > 0 && !get_post_meta( get_the_ID(), 'rand_img', true  ) ) {
				$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" ' . $be_get_img . '></a>';
			} else { 
				$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" data-src="'.$src.'"></a>';
			}
		}
	}
	$html .= '</div>';
	return $html;
}

// 图片缩略图
function img_thumbnail() {
	global $post;
	$html = '';
	$rel  = zm_get_option( 'img_rel_nofollow' ) ? 'external nofollow' : 'bookmark';
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
	$beimg = str_replace( array( "\n", "\r", " " ), '', explode( ',', zm_get_option( 'random_image_url' ) ) );
	$src   = $beimg[array_rand( $beimg )];

	$fancy = get_post_meta( get_the_ID(), 'fancy_box', true );
	if ( get_post_meta( get_the_ID(), 'fancy_box', true ) ) {
		$html .= '<a class="fancy-box" rel="' . $rel . '" ' . goal() . ' href="'. $fancy . '"></a>';
	}

	$html .= '<div class="thumbs-i lazy">';
	if ( get_post_meta(get_the_ID(), 'thumbnail', true) ) {
		$image = get_post_meta(get_the_ID(), 'thumbnail', true);
		$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" data-src="' . $image . '"></a>';
	} else {
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'content');
			$html .= '<a class="thumbs-back sc" rel="' . $rel . '" href="'.get_permalink().'" data-src="' . $full_image_url[0] . '"></a>';
		} else {
			if ( $n > 0 ) {
				$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" ' . $be_get_img . '></a>';
			} else { 
				$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" data-src="'.$src.'"></a>';
			}
		}
	}
	$html .= '</div>';
	return $html;
}

// 视频缩略图
function videos_thumbnail() {
	global $post;
	$html = '';
	$rel  = zm_get_option( 'img_rel_nofollow' ) ? 'external nofollow' : 'bookmark';
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
	$beimg = str_replace( array( "\n", "\r", " " ), '', explode( ',', zm_get_option( 'random_image_url' ) ) );
	$src   = $beimg[array_rand( $beimg )];
	$html .= '<div class="thumbs-v lazy">';
	if ( get_post_meta(get_the_ID(), 'small', true) ) {
		$image = get_post_meta(get_the_ID(), 'small', true);
		$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" data-src="' . $image . '"></a>';
	} else {
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'content');
			$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" data-src="' . $full_image_url[0] . '"></a>';
		} else {
			if ( $n > 0 ) {
				$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" ' . $be_get_img . '></a>';
			} else { 
				$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" data-src="'.$src.'"></a>';
			}
		}
	}
	$html .= '</div>';
	return $html;
}

// 商品缩略图
function tao_thumbnail() {
	global $post;
	$html = '';
	$rel  = zm_get_option( 'img_rel_nofollow' ) ? 'external nofollow' : 'bookmark';
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
	$beimg = str_replace( array( "\n", "\r", " " ), '', explode( ',', zm_get_option( 'random_image_url' ) ) );
	$src   = $beimg[array_rand( $beimg )];

	$fancy = get_post_meta( get_the_ID(), 'fancy_box', true );
	if ( get_post_meta( get_the_ID(), 'fancy_box', true ) ) {
		$html .= '<a class="fancy-box" rel="' . $rel . '" ' . goal() . ' href="'. $fancy . '"></a>';
	}

	$html .= '<div class="thumbs-t lazy">';
	$url = get_post_meta(get_the_ID(), 'taourl', true);
	if ( get_post_meta(get_the_ID(), 'thumbnail', true) ) {
		$image = get_post_meta(get_the_ID(), 'thumbnail', true);
		$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" data-src="' . $image . '"></a>';
	} else {
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'tao');
			$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" data-src="' . $full_image_url[0] . '"></a>';
		} else {
			$content = $post->post_content;
			preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
			if ( $n > 0 ) {
				$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" ' . $be_get_img . '></a>';
			} else { 
				$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" data-src="'.$src.'"></a>';
			}
		}
	}
	$html .= '</div>';
	return $html;
}

// 图像日志缩略图
function format_image_thumbnail() {
	global $post;
	$html = '';
	$rel  = zm_get_option( 'img_rel_nofollow' ) ? 'external nofollow' : 'bookmark';
	$content = $post->post_content;
	preg_match_all( '/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER );
	$html .= '<div class="thumbs-four">';
	$n = count($strResult[1]);
	if ( $n > 3 ) {
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

		$html .= '<div class="f4"><div class="format-img"><div class="thumbs-b lazy"><a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" ' . $be_get_img_a . '></a></div></div></div>';
		$html .= '<div class="f4"><div class="format-img"><div class="thumbs-b lazy"><a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" ' . $be_get_img_b . '></a></div></div></div>';
		$html .= '<div class="f4"><div class="format-img"><div class="thumbs-b lazy"><a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" ' . $be_get_img_c . '></a></div></div></div>';
		$html .= '<div class="f4"><div class="format-img"><div class="thumbs-b lazy"><a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" ' . $be_get_img_d . '></a></div></div></div>';
	} else {
		$html .= '<div class="f4-tip">文章中至少添加4张图片才能显示</div>';
	}
	$html .= '</div>';
	return $html;
}

// 图片布局缩略图
function zm_grid_thumbnail() {
	global $post;
	$html = '';
	$rel  = zm_get_option( 'img_rel_nofollow' ) ? 'external nofollow' : 'bookmark';
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
	$beimg = str_replace( array( "\n", "\r", " " ), '', explode( ',', zm_get_option( 'random_image_url' ) ) );
	$src   = $beimg[array_rand( $beimg )];

	$fancy = get_post_meta( get_the_ID(), 'fancy_box', true );
	if ( get_post_meta( get_the_ID(), 'fancy_box', true ) ) {
		$html .= '<a class="fancy-box" rel="' . $rel . '" href="'. $fancy . '"></a>';
	}

	$html .= '<div class="thumbs-h lazy">';
	if ( get_post_meta(get_the_ID(), 'thumbnail', true) ) {
		$image = get_post_meta(get_the_ID(), 'thumbnail', true);
		$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" data-src="' . $image . '"></a>';
	} else {
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'content');
			$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" data-src="' . $full_image_url[0] . '"></a>';
		} else {
			if ( $n > 0 && !get_post_meta( get_the_ID(), 'rand_img', true ) ) {
				$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" ' . $be_get_img . '></a>';
			} else { 
				$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" data-src="'.$src.'"></a>';
			}
		}
	}
	$html .= '</div>';
	return $html;
}

// 宽缩略图分类
function zm_full_thumbnail() {
	global $post;
	$html = '';
	$rel  = zm_get_option( 'img_rel_nofollow' ) ? 'external nofollow' : 'bookmark';
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
	$beimg = str_replace( array( "\n", "\r", " " ), '', explode( ',', zm_get_option( 'random_long_url' ) ) );
	$src   = $beimg[array_rand( $beimg )];
	$html .= '<div class="thumbs-w lazy">';
	if ( get_post_meta(get_the_ID(), 'full_thumbnail', true) ) {
		$image = get_post_meta(get_the_ID(), 'full_thumbnail', true);
		$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" data-src="' . $image . '"></a>';
	} else {
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'content');
			$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" data-src="' . $full_image_url[0] . '"></a>';
		} else {
			if ( $n > 0 && !get_post_meta( get_the_ID(), 'rand_img', true ) ) {
				$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" ' . $be_get_img . '></a>';
			} else { 
				$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" data-src="'.$src.'"></a>';
			}
		}
	}
	$html .= '</div>';
	return $html;
}

// 公司左右图
function gr_wd_thumbnail() {
	global $post;
	$html = '';
	$rel  = zm_get_option( 'img_rel_nofollow' ) ? 'external nofollow' : 'bookmark';
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
	$beimg = str_replace( array( "\n", "\r", " " ), '', explode( ',', zm_get_option( 'random_image_url' ) ) );
	$src   = $beimg[array_rand( $beimg )];

	$html .= '<div class="thumbs-lr lazy">';
	if ( get_post_meta(get_the_ID(), 'wd_img', true) ) {
		$image = get_post_meta(get_the_ID(), 'wd_img', true);
		$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" data-src="' . $image . '"></a>';
	} else {
		if ( $n > 0 ) {
			$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" ' . $be_get_img . '></a>';
		} else { 
			$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" data-src="'.$src.'"></a>';
		}
	}
	$html .= '</div>';
	return $html;
}

// 链接形式
function zm_thumbnail_link() {
	global $post;
	$html = '';
	$rel  = zm_get_option( 'img_rel_nofollow' ) ? 'external nofollow' : 'bookmark';
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
	$beimg = str_replace( array( "\n", "\r", " " ), '', explode( ',', zm_get_option( 'random_image_url' ) ) );
	$src   = $beimg[array_rand( $beimg )];

	$fancy = get_post_meta( get_the_ID(), 'fancy_box', true );
	if ( get_post_meta( get_the_ID(), 'fancy_box', true ) ) {
		$html .= '<a class="fancy-box" rel="' . $rel . '" href="'. $fancy . '"></a>';
	}
	$html .= '<div class="thumbs-l lazy">';
	$direct = get_post_meta(get_the_ID(), 'direct', true);
	if ( get_post_meta(get_the_ID(), 'thumbnail', true) ) {
		$image = get_post_meta(get_the_ID(), 'thumbnail', true);
		$html .= '<a class="thumbs-back sc" href="'.$direct.'" data-src="'.$image.'"></a>';
	} else {
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'content');
			$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" data-src="' . $full_image_url[0] . '"></a>';
		} else {
			if ( $n > 0 && !get_post_meta( get_the_ID(), 'rand_img', true ) ) {
				$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.$direct.'" ' . $be_get_img . '></a>';
			} else { 
				$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.$direct.'" data-src="'.$src.'"></a>';
			}
		}
	}
	$html .= '</div>';
	return $html;
}

// 幻灯小工具
function zm_widge_thumbnail() {
	global $post;
	$html = '';
	$rel  = zm_get_option( 'img_rel_nofollow' ) ? 'external nofollow' : 'bookmark';
	$html .= '<div class="thumbs-sw lazy">';
	if ( get_post_meta(get_the_ID(), 'widge_img', true) ) {
		$image = get_post_meta(get_the_ID(), 'widge_img', true);
		$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" style="background-image: url('.$image.');"></a>';
	} else {
		$content = $post->post_content;
		preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
		$n = count($strResult[1]);
		if ($n > 0) {
			$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" style="background-image: url('.$strResult[1][0].');"></a>';
		}
	}
	$html .= '</div>';
	return $html;
}

// 图片滚动
function zm_thumbnail_scrolling() {
	global $post;
	$html = '';
	$rel  = zm_get_option( 'img_rel_nofollow' ) ? 'external nofollow' : 'bookmark';
	$beimg = str_replace( array( "\n", "\r", " " ), '', explode( ',', zm_get_option( 'random_image_url' ) ) );
	$src   = $beimg[array_rand( $beimg )];

	$fancy = get_post_meta( get_the_ID(), 'fancy_box', true );
	if ( get_post_meta( get_the_ID(), 'fancy_box', true ) ) {
		$html .= '<a class="fancy-box" rel="' . $rel . '" href="'. $fancy . '"></a>';
	}

	$html .= '<div class="thumbs-sg">';
	if ( get_post_meta(get_the_ID(), 'thumbnail', true) ) {
		$image = get_post_meta(get_the_ID(), 'thumbnail', true);
		$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" style="background-image: url(' . $image . ');"></a>';
	} else {
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'content');
			$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" style="background-image: url(' . $full_image_url[0] . ');"></a>';
		} else {
			$content = $post->post_content;
			preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
			$n = count($strResult[1]);
			if ( $n > 0 ) {
				$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" style="background-image: url('.$strResult[1][0].');"></a>';
			} else { 
				$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" style="background-image: url('.$src.');"></a>';
			}
		}
	}
	$html .= '</div>';
	return $html;
}
}

// 瀑布流
function zm_waterfall_img() {
	global $post;
	$html = '';
	$rel  = zm_get_option( 'img_rel_nofollow' ) ? 'external nofollow' : 'bookmark';
	$beimg = str_replace( array( "\n", "\r", " " ), '', explode( ',', zm_get_option( 'random_image_url' ) ) );
	$src   = $beimg[array_rand( $beimg )];
	$width  = zm_get_option( 'img_w' );
	$height = zm_get_option( 'img_h' );
	$fancy = get_post_meta( get_the_ID(), 'fancy_box', true );
	if ( get_post_meta( get_the_ID(), 'fancy_box', true ) ) {
		$html .= '<a class="fancy-box" rel="' . $rel . '" href="'. $fancy . '"></a>';
	}

	if ( get_post_meta( get_the_ID(), 'fall_img', true ) ) {
		$image = get_post_meta(get_the_ID(), 'fall_img', true);
		$html .= '<a rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src=';
		$html .= $image;
		$html .= ' alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
	} else {
		$content = $post->post_content;
		preg_match_all( '/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER );
		$n = count( $strResult[1] );
		if ( $n > 0 ) {
			$html .= '<a rel="' . $rel . '" ' . goal() . ' href="' . get_permalink() . '"><img src="' . $strResult[1][0] . '" alt="' . $post->post_title . '" width="' . $width . '" height="' . $height . '"></a>';
		} else { 
			$html .= '<a rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'"><img src="' . $src . '" alt="' . $post->post_title . '" width="' . $width.'" height="' . $height . '"></a>';
		}
	}
	be_vip_meta();
	return $html;
}

// 菜单
function zm_menu_img() {
	global $post;
	$html = '';
	$rel  = zm_get_option( 'img_rel_nofollow' ) ? 'external nofollow' : 'bookmark';
	$beimg = str_replace( array( "\n", "\r", " " ), '', explode( ',', zm_get_option( 'random_image_url' ) ) );
	$src   = $beimg[array_rand( $beimg )];

	$html .= '<div class="thumbs-t">';
	if ( get_post_meta(get_the_ID(), 'thumbnail', true) ) {
		$image = get_post_meta(get_the_ID(), 'thumbnail', true);
		$html .= '<a class="thumbs-back" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" style="background-image: url(' . $image . ') !important;"></a>';
	} else {
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'content');
			$html .= '<a class="thumbs-back" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" style="background-image: url(' . $full_image_url[0] . ') !important;"></a>';
		} else {
			$content = $post->post_content;
			preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
			$n = count($strResult[1]);
			if ( $n > 0 && !get_post_meta( get_the_ID(), 'rand_img', true ) ) {
				$html .= '<a class="thumbs-back " rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" style="background-image: url('.$strResult[1][0].') !important;"></a>';
			} else { 
				$html .= '<a class="thumbs-back" rel="' . $rel . '" ' . goal() . ' href="'.get_permalink().'" style="background-image: url('.$src.') !important;"></a>';
			}
		}
	}
	$html .= '</div>';
	return $html;
}

// menu thumbnail
function be_menu_thumbnail() {
	global $post, $html;
	$beimg = str_replace( array( "\n", "\r", " " ), '', explode( ',', zm_get_option( 'random_long_url' ) ) );
	$src   = $beimg[array_rand( $beimg )];
	// 手动
	if ( get_post_meta( get_the_ID(), 'thumbnail', true ) ) {
		$post_img = get_post_meta( get_the_ID(), 'thumbnail', true );
		$html = '<span class="thumbs-m lazy menu-mix-post-img sc"><img src="' . $post_img . '" alt="' . get_the_title() . '"></span>';
	} else {
		$content = $post->post_content;
		preg_match_all( '/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER );
		$n = count( $strResult[1] );
		if ( $n > 0 ) {
			$html = '<span class="menu-mix-post-img"><img src="' . $strResult[1][0] . '" alt="' . get_the_title() . '"></span>';
		} else { 
			$html = '<span class="menu-mix-post-img">';
			$html = '<span class="menu-mix-post-img"><img src="'. $src .'" alt="' . get_the_title() . '"></span>';
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

// 图片背景
function be_img_fill() {
	global $post;
	$content = $post->post_content;
	preg_match_all( '/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER );

	if ( ! get_post_meta( get_the_ID(), 'be_img_fill', true ) ) {
		$html = '';
	} else {
		if ( ! get_post_meta( get_the_ID(), 'be_bg_img', true ) ) {
			$html = ' style="background-image: url( ' . $strResult[1][0] . ' );background-repeat: no-repeat;background-position: center;background-size: cover;"';
		} else {
			$html = ' style="background-image: url( ' . get_post_meta( get_the_ID(), 'be_bg_img', true ) . ' );background-repeat: no-repeat;background-position: center;background-size: cover;"';
		}
	}
	return $html;
}

function fill_class() {
	if ( ! get_post_meta( get_the_ID(), 'be_img_fill', true ) ) {
		$html = '';
	} else {
		$html = ' be-img-fill';
	}
	return $html;
}

// RSS图片
function rss_thumbnail( $content, $url ) {
	$html = '';
	$rel  = zm_get_option( 'img_rel_nofollow' ) ? 'external nofollow' : 'bookmark';
	preg_match( '/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $content, $image );

	$beimg = str_replace( array( "\n", "\r", " " ), '', explode( ',', zm_get_option( 'random_image_url' ) ) );
	$src   = $beimg[array_rand( $beimg )];

	$html .= '<div class="thumbs-b lazy">';
	if ( zm_get_option( 'lazy_s' ) ) {
		if ( ! empty( $image['src'] ) ) {
			$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="' . $url . '" data-src="' . $image['src'] . '"></a>';
		} else {
			$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="' . $url . '" data-src="' . $src . '"></a>';
		}
	} else {
		if ( ! empty( $image['src'] ) ) {
			$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="' . $url . '"  style="background-image: url(' . $image['src'] . ');"></a>';
		} else {
			$html .= '<a class="thumbs-back sc" rel="' . $rel . '" ' . goal() . ' href="' . $url . '" style="background-image: url(' . $src . ');"></a>';
		}
	}
	$html .= '</div>';
	return $html;
}

// 图文卡片
function each_img() {
	global $post;
	$content = $post->post_content;
	preg_match_all( '/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER );
	return $strResult[1][0];
}

function has_images() {
	global $post;
	$content = $post->post_content;
	preg_match_all( '/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER );
	$n = count( $strResult[1] );
	return $n > 0;
}