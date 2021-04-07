import attributes from './attributes';
import edit from './edit';

import {slug, icon, title, description} from './help';

const blockProperty = {
	save: () => null,
	category: 'helpler-modules',
	supports: {
		align: ['wide', 'full'],
		customClassName: false,
		html: false,
	},
	icon: {
		src: icon,
	},
	title,
	description,
	keywords: [],
	attributes,
	edit,
};

export default {
	slug: `rehub/${slug}`,
	blockProperty,
};
