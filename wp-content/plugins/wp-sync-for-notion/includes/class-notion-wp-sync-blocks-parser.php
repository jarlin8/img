<?php
/**
 * Manages Notions blocks parsing and transformation to Gutenberg blocks.
 * It also allows finding page children (defined in blocks).
 *
 * @package Notion_Wp_Sync
 */

namespace Notion_Wp_Sync;

/**
 * Notion_WP_Sync_Blocks_Parser class
 */
class Notion_WP_Sync_Blocks_Parser {

	/**
	 * Notion_WP_Sync_Blocks_Parser instance
	 *
	 * @var Notion_WP_Sync_Blocks_Parser $instance
	 */
	private static $instance;

	/**
	 * Returns Notion_WP_Sync_Blocks_Parser instance
	 *
	 * @return Notion_WP_Sync_Blocks_Parser
	 */
	public static function get_instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Rich text parser.
	 *
	 * @var Notion_WP_Sync_Rich_Text_Parser
	 */
	private $rich_text_parser;

	/**
	 * Attachment manager (file importer).
	 *
	 * @var Notion_WP_Sync_Attachments_Manager
	 */
	private $attachment_manager;

	/**
	 * Notion_WP_Sync_Blocks_Parser constructor.
	 * Manages dependencies and init blocks hooks.
	 */
	public function __construct() {
		$this->rich_text_parser   = Notion_WP_Sync_Rich_Text_Parser::get_instance();
		$this->attachment_manager = Notion_WP_Sync_Attachments_Manager::get_instance();
		$this->init_blocks();
	}

	/**
	 * Init blocks hooks.
	 *
	 * @return void
	 */
	public function init_blocks() {
		add_filter( 'notionwpsync/blocks_parser/paragraph', array( $this, 'parse_paragraph_block' ), 10, 2 );
		add_filter( 'notionwpsync/blocks_parser/heading_1', array( $this, 'parse_heading_block' ), 10, 2 );
		add_filter( 'notionwpsync/blocks_parser/heading_2', array( $this, 'parse_heading_block' ), 10, 2 );
		add_filter( 'notionwpsync/blocks_parser/heading_3', array( $this, 'parse_heading_block' ), 10, 2 );
		add_filter( 'notionwpsync/blocks_parser/bulleted_list_item', array( $this, 'parse_list_block' ), 10, 2 );
		add_filter( 'notionwpsync/blocks_parser/numbered_list_item', array( $this, 'parse_list_block' ), 10, 2 );
		add_filter( 'notionwpsync/blocks_parser/quote', array( $this, 'parse_quote_block' ), 10, 2 );
		add_filter( 'notionwpsync/blocks_parser/table', array( $this, 'parse_table_block' ), 10, 2 );
		add_filter( 'notionwpsync/blocks_parser/divider', array( $this, 'parse_divider_block' ), 10, 2 );
		add_filter( 'notionwpsync/blocks_parser/image', array( $this, 'parse_image_block' ), 10, 3 );
		add_filter( 'notionwpsync/blocks_parser/video', array( $this, 'parse_video_block' ), 10, 3 );
		add_filter( 'notionwpsync/blocks_parser/column_list', array( $this, 'parse_column_list_block' ), 10, 3 );
		add_filter( 'notionwpsync/blocks_parser/callout', array( $this, 'parse_callout_block' ), 10, 3 );
		add_filter( 'notionwpsync/blocks_parser/synced_block', array( $this, 'parse_synced_block_block' ), 10, 3 );
		add_filter( 'notionwpsync/blocks_parser/code', array( $this, 'parse_code_block' ), 10, 2 );
	}

	/**
	 * Parse $blocks. For each Notion block returns a Gutenberg block as HTML or an empty string if the block is not supported.
	 *
	 * @param array  $blocks Notions blocks.
	 * @param array  $params Extra params for the context (importer, post_id).
	 * @param string $result The result as HTML string.
	 *
	 * @return string
	 */
	public function parse_blocks( $blocks, $params, $result = '' ) {
		$to_regroup          = array( 'bulleted_list_item', 'numbered_list_item' );
		$regrouped_item_type = null;
		$regrouped_items     = array();

		foreach ( $blocks as $block ) {
			if ( in_array( $block->type, $to_regroup, true ) ) {
				// Found all siblings items? parse blocks.
				if ( $block->type !== $regrouped_item_type && null !== $regrouped_item_type ) {
					$regrouped_item_type_key = sanitize_key( $regrouped_item_type );
					$result                 .= apply_filters( "notionwpsync/blocks_parser/{$regrouped_item_type_key}", '', $regrouped_items, $params );
					$regrouped_items         = array();
				}
				$regrouped_items[]   = $block;
				$regrouped_item_type = $block->type;
			} else {
				// If the last item was part of a group, parse blocks.
				if ( count( $regrouped_items ) > 0 ) {
					$result             .= apply_filters( 'notionwpsync/blocks_parser/' . sanitize_key( $regrouped_item_type ), '', $regrouped_items, $params );
					$regrouped_items     = array();
					$regrouped_item_type = null;
				}
				$result .= apply_filters( 'notionwpsync/blocks_parser/' . sanitize_key( $block->type ), '', $block, $params );
			}
		}
		// If the last item was part of a group, parse blocks.
		if ( count( $regrouped_items ) > 0 ) {
			$result .= apply_filters( 'notionwpsync/blocks_parser/' . sanitize_key( $regrouped_item_type ), '', $regrouped_items, $params );
		}
		return $result;
	}

	/**
	 * Parse paragraph block.
	 *
	 * @param string $html HTML.
	 * @param object $block Block.
	 *
	 * @return string
	 */
	public function parse_paragraph_block( $html, $block ) {
		if ( ! isset( $block->paragraph ) ) {
			return $html;
		}

		$paragraph   = $block->paragraph;
		$block_props = $this->init_gut_props( $paragraph );
		$block_html  = '';

		if ( isset( $paragraph->rich_text ) ) {
			$block_html = $this->rich_text_parser->parse_rich_text( $paragraph->rich_text );
		}

		if ( ! empty( $block_html ) ) {
			$html_attributes = $this->generate_attributes_from_props( $block_props );
			$block_html      = "<p$html_attributes>$block_html</p>";
			$block_html      = $this->wrap_gut( $block_html, 'paragraph', $block_props );
		}

		return $html . $block_html;
	}

	/**
	 * Parse heading block.
	 *
	 * @param string $html HTML.
	 * @param object $block Block.
	 *
	 * @return string
	 */
	public function parse_heading_block( $html, $block ) {
		if ( ! preg_match( '`^heading\_([1-6])$`', $block->type, $matches ) ) {
			return $html;
		}
		$heading_level = (int) $matches[1];

		if ( ! isset( $block->{'heading_' . $heading_level} ) ) {
			return $html;
		}

		$heading    = $block->{'heading_' . $heading_level};
		$block_html = '';
		$props      = $this->init_gut_props( $heading );
		if ( 2 !== $heading_level ) {
			$props['level'] = $heading_level;
		}

		if ( isset( $heading->rich_text ) ) {
			$block_html = $this->rich_text_parser->parse_rich_text( $heading->rich_text );
		}

		if ( ! empty( $block_html ) ) {
			$block_html = $this->wrap_gut( "<h$heading_level>$block_html</h$heading_level>", 'heading', $props );
		}

		return $html . $block_html;
	}

	/**
	 * Parse list block.
	 *
	 * @param string   $html HTML.
	 * @param object[] $blocks List of bulleted_list_item or ... blocks.
	 *
	 * @return string
	 */
	public function parse_list_block( $html, $blocks ) {
		if ( ! is_array( $blocks ) || empty( $blocks ) ) {
			return $html;
		}

		$type = $blocks[0]->type;
		if ( ! in_array( $type, array( 'bulleted_list_item', 'numbered_list_item' ), true ) ) {
			return $html;
		}

		$props   = array();
		$tagname = 'ul';
		if ( 'numbered_list_item' === $type ) {
			$props['ordered'] = true;
			$tagname          = 'ol';
		}

		$block_html = '';
		foreach ( $blocks as $block ) {
			$block_html = self::parse_list_item_block( $block_html, $block, $type );
		}

		if ( ! empty( $block_html ) ) {
			$block_html = $this->wrap_gut( "<$tagname>$block_html</$tagname>", 'list', $props );
		}

		return $html . $block_html;
	}


	/**
	 * Parse bulleted_list_item or ... block
	 *
	 * @param string $html HTML.
	 * @param object $block Block.
	 * @param string $type List item type.
	 *
	 * @return string
	 */
	public function parse_list_item_block( $html, $block, $type ) {
		if ( ! isset( $block->{$type} ) ) {
			return $html;
		}

		$list_item  = $block->{$type};
		$block_html = '';
		if ( isset( $list_item->rich_text ) ) {
			$block_html = $this->rich_text_parser->parse_rich_text( $list_item->rich_text );
		}

		if ( ! empty( $block_html ) ) {
			$block_html = $this->wrap_gut( "<li>$block_html</li>", 'list-item' );
		}

		return $html . $block_html;
	}

	/**
	 * Parse quote block.
	 *
	 * @param string $html HTML.
	 * @param object $block Block.
	 *
	 * @return string
	 */
	public function parse_quote_block( $html, $block ) {
		if ( ! isset( $block->quote ) ) {
			return $html;
		}
		$quote       = $block->quote;
		$block_props = $this->init_gut_props( $quote );
		$block_html  = '';

		if ( isset( $quote->rich_text ) ) {
			$block_html = $this->parse_paragraph_block(
				$block_html,
				(object) array(
					'type'      => 'paragraph',
					'paragraph' => (object) array(
						'rich_text' => $quote->rich_text,
					),
				)
			);
		}

		if ( ! empty( $block_html ) ) {
			$block_attributes_props = $block_props;
			array_unshift( $block_attributes_props['className'], 'wp-block-quote' );
			$block_html = sprintf(
				"<blockquote%s>$block_html</blockquote>",
				$this->generate_attributes_from_props( $block_attributes_props )
			);
			$block_html = $this->wrap_gut( $block_html, 'quote', $block_props );
		}

		return $html . $block_html;
	}

	/**
	 * Parse table block.
	 *
	 * @param string $html HTML.
	 * @param object $block Block.
	 *
	 * @TODO: add test
	 *
	 * @return string
	 */
	public function parse_table_block( $html, $block ) {
		if ( ! isset( $block->table ) || ! isset( $block->children ) || ! is_array( $block->children ) || empty( $block->children ) ) {
			return $html;
		}

		$table = $block->table;
		// top row is header.
		$has_column_header = $table->has_column_header;
		// first col is header.
		$has_row_header = $table->has_row_header;
		$block_html     = '';
		$children       = $block->children;
		if ( $has_column_header ) {
			$block_html .= '<thead><tr>';
			foreach ( $children[0]->table_row->cells as $cell ) {
				$block_html .= '<th>' . $this->rich_text_parser->parse_rich_text( $cell ) . '</th>';
			}
			$block_html .= '</tr></thead>';
			array_shift( $children );
		}

		$block_html .= '<tbody>';
		foreach ( $children as $child ) {
			$is_first_col = true;
			if ( 'table_row' === $child->type && isset( $child->table_row ) ) {
				$block_html .= '<tr>';
				foreach ( $child->table_row->cells as $cell ) {
					$tagname      = ( $has_row_header && $is_first_col ? 'th' : 'td' );
					$block_html  .= "<$tagname>" . $this->rich_text_parser->parse_rich_text( $cell ) . "</$tagname>";
					$is_first_col = false;
				}
				$block_html .= '</tr>';
			}
		}
		$block_html .= "\n</tbody>";

		$block_html = $this->wrap_gut( "<figure class=\"wp-block-table\"><table>$block_html</table></figure>", 'table' );

		return $html . $block_html;
	}

	/**
	 * Parse divider block.
	 *
	 * @param string $html HTML.
	 * @param object $block Block.
	 *
	 * @TODO: add test
	 *
	 * @return string
	 */
	public function parse_divider_block( $html, $block ) {
		if ( ! isset( $block->divider ) ) {
			return $html;
		}

		$block_html = '<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>';
		$html      .= $this->wrap_gut( $block_html, 'separator', array( 'className' => 'is-style-wide' ) );

		return $html;
	}


	/**
	 * Parse image block.
	 *
	 * @param string $html HTML.
	 * @param object $block Block.
	 * @param array  $params Extra params.
	 * @TODO: add test
	 *
	 * @return string
	 */
	public function parse_image_block( $html, $block, $params ) {
		if ( ! isset( $block->image ) ) {
			return $html;
		}

		$block_html  = '';
		$block_props = $this->init_gut_props(
			$block->image,
			array(
				'linkDestination' => 'none',
			)
		);

		$caption = ! empty( $block->image->caption ) ? $this->rich_text_parser->parse_rich_text( $block->image->caption ) : '';

		if ( ! in_array( $block->image->type, array( 'external', 'file' ), true ) ) {
			return $html;
		}

		$attachment_ids = $this->attachment_manager->get_set_files(
			array(
				$this->attachment_manager->notion_file_to_media(
					$block->id,
					! empty( $caption ) ? $this->rich_text_parser->to_plain_text( $block->image->caption ) : $block->type,
					$block->image
				),
			),
			$params['importer'],
			$params['post_id'] ?? null
		);

		if ( ! empty( $attachment_ids ) ) {
			$attachment_id                                  = $attachment_ids[0];
			list( $image_url, $image_width, $image_height ) = wp_get_attachment_image_src( $attachment_id, 'large' );
			$block_props['className'][]                     = 'size-large';
			$block_html                                     = sprintf( '<figure class="wp-block-image size-large"><img src="%s" alt=""/>', $image_url );
			if ( ! empty( $caption ) ) {
				$block_html .= sprintf( '<figcaption class="wp-element-caption">%s</figcaption>', $caption );
			}
			$block_html .= '</figure>';
		}

		if ( ! empty( $block_html ) ) {
			$html .= $this->wrap_gut( $block_html, 'image', $block_props );
		}

		return $html;
	}

	/**
	 * Parse video block.
	 *
	 * @param string $html HTML.
	 * @param object $block Block.
	 * @param array  $params Extra params.
	 * @TODO: add test
	 *
	 * @return string
	 */
	public function parse_video_block( $html, $block, $params ) {
		if ( ! isset( $block->video ) ) {
			return $html;
		}

		$block_props = $this->init_gut_props( $block->video );
		$block_html  = '';
		$caption     = ! empty( $block->video->caption ) ? $this->rich_text_parser->parse_rich_text( $block->video->caption ) : '';

		if ( 'external' === $block->video->type ) {
			$url     = $block->video->external->url;
			$request = new \WP_REST_Request( 'GET', '/oembed/1.0/proxy' );
			$request->set_query_params(
				array(
					'url' => $url,
				)
			);
			$response = rest_do_request( $request );
			if ( $response instanceof \WP_REST_Response ) {
				$response = rest_get_server()->response_to_data( $response, false );
				if ( $response ) {
					$block_props['url']              = $url;
					$block_props['type']             = $response->type;
					$block_props['providerNameSlug'] = sanitize_title( $response->provider_name );
					$block_props['responsive']       = true;
					$block_props['className'][]      = 'wp-embed-aspect-16-9';
					$block_props['className'][]      = 'wp-has-aspect-ratio';

					$block_html = sprintf(
						'<figure class="wp-block-embed is-type-%s is-provider-%s wp-block-embed-%s wp-embed-aspect-16-9 wp-has-aspect-ratio"><div class="wp-block-embed__wrapper">
%s
</div>%s</figure>',
						$block_props['type'],
						$block_props['providerNameSlug'],
						$block_props['providerNameSlug'],
						$url,
						! empty( $caption ) ? sprintf( '<figcaption class="wp-element-caption">%s</figcaption>', $caption ) : ''
					);
				}
			}

			if ( ! empty( $block_html ) ) {
				$html .= $this->wrap_gut( $block_html, 'embed', $block_props );
			}
		} elseif ( 'file' === $block->video->type ) {
			$file           = $this->attachment_manager->notion_file_to_media(
				$block->id,
				'video',
				$block->video,
				'mp4'
			);
			$attachments_id = $this->attachment_manager->get_set_files(
				array(
					$file,
				),
				$params['importer'],
				$params['post_id'] ?? null
			);
			if ( ! empty( $attachments_id ) ) {
				$attachment_id     = $attachments_id[0];
				$block_props['id'] = $attachment_id;
				$block_html        = sprintf(
					'<figure class="wp-block-video"><video controls src="%s"></video>%s</figure>',
					wp_get_attachment_url( $attachment_id ),
					! empty( $caption ) ? sprintf( '<figcaption class="wp-element-caption">%s</figcaption>', $caption ) : ''
				);
			}

			if ( ! empty( $block_html ) ) {
				$html .= $this->wrap_gut( $block_html, 'video', $block_props );
			}
		}

		return $html;
	}

	/**
	 * Parse column_list block.
	 *
	 * @param string $html HTML.
	 * @param object $block Block.
	 * @param array  $params Extra params.
	 * @TODO: add test
	 *
	 * @return string
	 */
	public function parse_column_list_block( $html, $block, $params ) {
		if ( ! isset( $block->column_list ) || ! isset( $block->children ) || ! is_array( $block->children ) || empty( $block->children ) ) {
			return $html;
		}

		$block_html = '<div class="wp-block-columns">';
		foreach ( $block->children as $child ) {
			if ( 'column' !== $child->type ) {
				continue;
			}
			$column_html  = '<div class="wp-block-column">';
			$column_html  = $this->parse_blocks( $child->children, $params, $column_html );
			$column_html .= '</div>';

			$block_html .= $this->wrap_gut( $column_html, 'column' );
		}
		$block_html .= '</div>';

		$html .= $this->wrap_gut( $block_html, 'columns' );

		return $html;
	}

	/**
	 * Parse callout block.
	 *
	 * @param string $html HTML.
	 * @param object $block Block.
	 * @param array  $params Extra params.
	 * @TODO: add test
	 *
	 * @return string
	 */
	public function parse_callout_block( $html, $block, $params ) {
		if ( ! isset( $block->callout ) ) {
			return $html;
		}

		$rich_text = '';
		if ( isset( $block->callout->icon ) ) {
			$icon = $block->callout->icon;
			if ( 'emoji' === $icon->type ) {
				$rich_text .= $icon->emoji . ' ';
			} elseif ( 'external' === $icon->type || 'file' === $icon->type ) {
				$url = '';
				if ( 'external' === $icon->type && strpos( $icon->external->url, 'https://www.notion.so/icons/' ) === 0 ) {
					$url = $icon->external->url;
				} else {
					$attachments_id = $this->attachment_manager->get_set_files(
						array(
							$this->attachment_manager->notion_file_to_media(
								$block->id,
								'icon',
								$icon
							),
						),
						$params['importer'],
						$params['post_id'] ?? null
					);
					if ( ! empty( $attachments_id ) ) {
						list( $image_url, $image_width, $image_height ) = wp_get_attachment_image_src( $attachments_id[0], 'thumbnail' );
						$url = $image_url;
					}
				}
				if ( ! empty( $url ) ) {
					$rich_text .= sprintf( '<img style="height: 24px; width: 24px; object-fit: cover; border-radius: 3px; vertical-align: middle; margin-right: 8px;" src="%s" alt=""/>', $url );
				}
			}
		}

		$rich_text .= $this->rich_text_parser->parse_rich_text( $block->callout->rich_text );

		return $this->parse_paragraph_block(
			$html,
			(object) array(
				'type'      => 'paragraph',
				'paragraph' => (object) array(
					'rich_text' => $rich_text,
					'color'     => $block->callout->color ?? null,
				),
			)
		);
	}

	/**
	 * Parse code block.
	 *
	 * @param string $html HTML.
	 * @param object $block Block.
	 *
	 * @return string
	 */
	public function parse_code_block( $html, $block ) {
		if ( ! isset( $block->code ) ) {
			return $html;
		}

		$block_html = $this->rich_text_parser->parse_rich_text( $block->code->rich_text, array( 'nl2br' => false ) );

		if ( ! empty( $block_html ) ) {
			$block_html = "<pre class=\"wp-block-code\"><code>$block_html</code></pre>";
			$html      .= $this->wrap_gut( $block_html, 'code' );
		}

		if ( isset( $block->code->caption ) ) {
			$html = $this->parse_paragraph_block(
				$html,
				(object) array(
					'type'      => 'paragraph',
					'paragraph' => (object) array(
						'rich_text' => $block->code->caption,
					),
				)
			);
		}

		return $html;
	}

	/**
	 * Parse synced block.
	 *
	 * @param string $html HTML.
	 * @param object $block Block.
	 * @param array  $params Extra params.
	 * @TODO: add test
	 *
	 * @return string
	 */
	public function parse_synced_block_block( $html, $block, $params ) {
		if ( ! isset( $block->children ) ) {
			return $html;
		}

		return $this->parse_blocks( $block->children, $params );
	}

	/**
	 * Add required comments around the generated HTML for the Gutenberg block to be valid.
	 *
	 * @param string $content HTML.
	 * @param string $block_name Gutenberg block name.
	 * @param array  $props Gutenberg block props.
	 *
	 * @return string
	 */
	public function wrap_gut( $content, $block_name, $props = array() ) {
		if ( isset( $props['className'] ) && is_array( $props['className'] ) ) {
			$props['className'] = array_diff( $props['className'], array( 'has-background', 'has-text-color' ) );
			$props['className'] = implode( ' ', $props['className'] );
		}
		if ( empty( $props['className'] ) ) {
			unset( $props['className'] );
		}

		$wrapped = sprintf( '<!-- wp:%s ', $block_name );
		if ( ! empty( $props ) ) {
			$wrapped .= wp_json_encode( $props ) . ' ';
		}
		$wrapped .= '-->';
		$wrapped .= "\n";
		$wrapped .= $content;
		$wrapped .= sprintf( "\n<!-- /wp:%s -->\n", $block_name );

		return $wrapped;
	}

	/**
	 * Normalize Gutenberg block props from Notion block.
	 *
	 * @param object $block_value Notion block.
	 * @param array  $props Gutenberg block props.
	 *
	 * @return array
	 */
	public function init_gut_props( $block_value, $props = array() ) {
		$props['className'] = array();
		if ( isset( $block_value->color ) && is_string( $block_value->color ) ) {
			$color = $block_value->color;

			if ( strpos( $color, '_background' ) !== false ) {
				$props['className'][]                  = 'has-background';
				$props['style']['color']['background'] = $this->rich_text_parser->bgcolor_to_rgb( $color );
			} else {
				$props['className'][]            = 'has-text-color';
				$props['style']['color']['text'] = $this->rich_text_parser->color_to_rgb( $color );
			}
		}
		return $props;
	}

	/**
	 * Generate HTML attributes from Gutenberg props.
	 *
	 * @param array $props Gutenberg props.
	 *
	 * @return string
	 */
	public function generate_attributes_from_props( $props ) {
		$attributes = '';
		if ( ! empty( $props['className'] ) ) {
			$attributes .= sprintf( ' class="%s"', implode( ' ', $props['className'] ) );
		}
		if ( ! empty( $props['style'] ) ) {
			$styles = array();
			if ( isset( $props['style']['color'] ) ) {
				foreach ( $props['style']['color'] as $color_key => $color ) {
					$color_prop = 'text' === $color_key ? 'color' : 'background-color';
					$styles[]   = $color_prop . ': ' . $color;
				}
			}
			if ( ! empty( $styles ) ) {
				$attributes .= sprintf( ' style="%s"', implode( '; ', $styles ) );
			}
		}
		return $attributes;
	}

	/**
	 * Get page children id from block.
	 * Notion stores page children as "child_page" block type.
	 *
	 * @param array $blocks Notion blocks.
	 *
	 * @return array
	 */
	public function get_page_children_id( $blocks ) {
		$pages_id = array();
		if ( ! is_array( $blocks ) ) {
			return $pages_id;
		}
		foreach ( $blocks as $block ) {
			if ( 'child_page' === $block->type ) {
				$pages_id[] = $block->id;
			}
		}
		return $pages_id;
	}

}
