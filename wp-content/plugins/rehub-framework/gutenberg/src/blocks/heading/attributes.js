const {__} = wp.i18n;

const attributes = {
	textAlign: {
		type: 'string',
		default: 'left',
	},
	level: {
		type: "number",
		default: 2,
	},
	content: {
		type: 'string',
		default: __('Heading', 'rehub-theme'),
	},
	backgroundText: {
		type: 'string',
		default: __('01.', 'rehub-theme'),
	},
};

export default attributes;
