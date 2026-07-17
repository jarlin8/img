/**
 * BLOCK: Versus Table.
 */

/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import {TableIcon} from "../../icons";
import edit from './edit';
import schema from "./schema";

const blockProperty = {
	title: __('Versus Table', 'rehub-framework'),
	description: __('Table of versus items', 'rehub-framework'),
	icon: TableIcon,
	category: 'helpler-modules',
	keywords: [
		'table',
		'versus',
		'rehub',
		'list'
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
	slug: `rehub/versus-table`,
	blockProperty,
};