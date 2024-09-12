/**
 * BLOCK: Review Box.
 */

/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import {ReviewBoxIcon} from "../../icons";
import edit from './edit';
import schema from "./schema";

const blockProperty = {
	title: __('Review Box', 'rehub-framework'),
	description: __('Box with selection of scopes, positive and negative items', 'rehub-framework'),
	icon: ReviewBoxIcon,
	category: 'helpler-modules',
	keywords: [
		'review',
		'rehub',
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
	slug: `rehub/reviewbox`,
	blockProperty,
};