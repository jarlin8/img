const schema = {
	name: {
		type: 'string',
		default: ''
	},
	description: {
		type: 'string',
		default: ''
	},
	disclaimer: {
		type: 'string',
		default: ''
	},

	old_price: {
		type: 'string',
		default: ''
	},
	sale_price: {
		type: 'string',
		default: ''
	},
	hide_old_price: {
		type: 'boolean',
		default: false
	},

	coupon_code: {
		type: 'string',
		default: ''
	},
	mask_coupon_code: {
		type: 'boolean',
		default: false
	},
	mask_coupon_text: {
		type: 'string',
		default: ''
	},

	expiration_date: {
		type: 'string',
		default: ''
	},
	offer_is_expired: {
		type: 'boolean',
		default: false
	},

	button: {
		type: 'object',
		default: {
			text: 'Buy this item',
			url: '',
			newTab: false,
			noFollow: false
		}
	},
	thumbnail: {
		type: 'object',
		default: {
			id: '',
			url: '',
			width: '',
			height: ''
		}
	},
	brand_logo_url: {
		type: 'string',
		default: ''
	},

	discount_tag: {
		type: 'number',
		default: 0
	},
	discount: {
		type: 'string'
	},

	rating: {
		type: 'number',
		default: 0
	},
	borderColor: {
		type: 'string',
		default: ''
	},
	selectedPost: {
		type: 'string',
		default: '',
	},
	loading: {
		type: 'boolean',
		default: false
	},
	parseError: {
		type: 'string',
		default: ''
	},
	parseSuccess: {
		type: 'string',
		default: ''
	}
};

export default schema;
