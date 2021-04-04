/**
 * Gutenberg Blocks
 *
 * All blocks related JavaScript files should be imported here.
 * You can create a new block folder in this dir and include code
 * for that block here as well.
 *
 * All blocks should be included here since this is the file that
 * Webpack is compiling as the input file.
 */


// Legacy Blocks
import './legacy/insert-textlink-html/block.js';
import './legacy/insert-imagelink-html/block.js';
import './legacy/insert-cta-shortcode/block.js';
import './legacy/insert-showcase-shortcode/block.js';


// 2019 & Beyond Blocks

// import './imagelink/block.js';
import './insert-table-shortcode/block.js';
import './insert-nonapi-image/block.js';
import './insert-nonapi-showcase-shortcode/block.js';
import './insert-nonapi-cta-shortcode/block.js';


wp.alp_gut_active = false;
wp.alp_gut_legacy_img_link = false;
wp.alp_gut_legacy_text_link = false;
wp.alp_gut_legacy_cta = false;
wp.alp_gut_legacy_showcase = false;

