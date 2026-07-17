/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';

export function fetchReviewData(value, setAttributes, type = 'post') {
	if (value.length === 0) {
		return false;
	}

	setAttributes({
		loading: true,
		selectedPosts: value,
		parseError: '',
		parseSuccess: ''
	});

	wp.apiFetch({
		path: '/rehub/v2/offer-listing/',
		method: 'POST',
		data: {
			posts_id: value,
			type: type
		}
	}).then(response => {
		const data = JSON.parse(response);
		setAttributes({
			loading: false,
			offers: data,
			parseError: '',
			parseSuccess: __('Fields updated', 'rehub-framework')
		});

	}).catch(error => {
		setAttributes({
			loading: false,
			parseError: error.message,
			parseSuccess: ''
		});
	});
}