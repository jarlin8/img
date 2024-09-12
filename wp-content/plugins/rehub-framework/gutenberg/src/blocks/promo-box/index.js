/**
 * BLOCK: Promo Box.
 */

/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import {PromoBoxIcon} from "../../icons";
import edit from './edit';
import schema from "./schema";

const blockProperty = {
	title: __('Promo Box', 'rehub-framework'),
	description: __('Box specifically designed for promotional products to target key clients', 'rehub-framework'),
	icon: PromoBoxIcon,
	category: 'helpler-modules',
	keywords: [
		'rehub',
		'promo',
		'box'
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
	slug: `rehub/promo-box`,
	blockProperty,
};