/**
 * BLOCK: Accordion.
 */

/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import {AccordionIcon} from "../../icons";
import schema from "./schema";
import edit from "./edit";

const blockProperty = {
	title: __('Accordion', 'rehub-framework'),
	description: __('Accordions are useful when you want to toggle between hiding and showing large amount of content', 'rehub-framework'),
	icon: AccordionIcon,
	category: 'helpler-modules',
	keywords: [
		'accordion',
		'rehub',
		'tabs',
		'collapsed',
		'expand'
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
	slug: `rehub/accordion`,
	blockProperty,
};