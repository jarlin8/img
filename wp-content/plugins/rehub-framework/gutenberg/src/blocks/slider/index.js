/**
 * BLOCK: Slider.
 */

/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import {SliderIcon} from "../../icons";
import edit from './edit';
import schema from "./schema";

const blockProperty = {
	title: __('Slider', 'rehub-framework'),
	description: __('Rehub slider', 'rehub-framework'),
	icon: SliderIcon,
	category: 'helpler-modules',
	keywords: [
		'rehub',
		'slider'
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
	slug: `rehub/slider`,
	blockProperty,
};