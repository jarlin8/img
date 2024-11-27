/**
 * BLOCK: Post Offer Listing.
 */

/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import schema from '../schema';
import edit from './edit';
import {ReviewListIcon} from "../../../icons";

const blockProperty = {
	title: __('Post Offer Listing', 'rehub-framework'),
	description: __('Post Offer Listing description...', 'rehub-framework'),
	icon: ReviewListIcon,
	category: 'helpler-modules',
	keywords: [
		'rehub',
		'post',
		'offer',
		'review',
		'listing',
		'list',
		'table'
	],
	supports: {
		align: ['wide', 'full'],
		customClassName: false,
		html: false,
	},
	example: {},
	attributes: schema,
	save: () => null,
	edit
};

export default {
	slug: `rehub/post-offer-listing`,
	blockProperty,
};