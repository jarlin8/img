/**
 * BLOCK: WooCommerce Box.
 */

/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import {ProductIcon} from "../../icons";
import edit from './edit';
import schema from "./schema";

const blockProperty = {
	title: __('WooCommerce Box', 'rehub-framework'),
	description: __('Select a WooCommerce product', 'rehub-framework'),
	icon: ProductIcon,
	category: 'helpler-modules',
	keywords: [
		'WooCommerce',
		'product',
		'select',
		'box',
		'rehub',
		'wc'
	],
	supports: {
		customClassName: false,
		html: false,
	},
	example: {},
	attributes: schema,
	save: () => null,
	edit
};


export default {
	slug: `rehub/wc-box`,
	blockProperty,
};