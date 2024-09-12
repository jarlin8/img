/**
 * BLOCK: Color Heading.
 */

/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import {ReviewHeadingIcon} from "../../icons";
import edit from './edit';
import schema from "./schema";

const blockProperty = {
	title: __('Color Heading', 'rehub-framework'),
	description: __('Simple heading with select of background color', 'rehub-framework'),
	icon: ReviewHeadingIcon,
	category: 'helpler-modules',
	keywords: [
		'rehub',
		'color',
		'heading',
		'header'
	],
	supports: {
		customClassName: false,
		html: false,
	},
	example: {},
	attributes: schema,
	save: () => null,
	edit,
	getEditWrapperProps({align}) {
		return {'data-align': align};
	}
};


export default {
	slug: `rehub/color-heading`,
	blockProperty,
};