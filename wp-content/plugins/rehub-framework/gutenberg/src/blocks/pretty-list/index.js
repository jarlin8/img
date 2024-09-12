/**
 * BLOCK: Pretty list.
 */

/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import {ListIcon} from "../../icons";
import edit from './edit';
import schema from "./schema";

const blockProperty = {
	title: __('Pretty List', 'rehub-framework'),
	description: __('List with a rich settings of styles', 'rehub-framework'),
	icon: ListIcon,
	category: 'helpler-modules',
	keywords: [
		'rehub',
		'list',
		'pretty'
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
	slug: `rehub/pretty-list`,
	blockProperty,
};