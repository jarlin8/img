/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';
import {Component} from '@wordpress/element';
import {Spinner, BaseControl} from '@wordpress/components';
import {withSelect} from '@wordpress/data';
import apiFetch from '@wordpress/api-fetch';

/**
 * External dependencies
 */
import debounce from "debounce-promise";
import {trim} from 'lodash';
import AsyncSelect from 'react-select/async';

function mapPosts(posts) {
	let data = [];

	for (let i = 0; i < posts.length; i++) {
		data.push({
			label: posts[i].title.rendered,
			value: posts[i].id
		});
	}

	return data;
}

function isEmpty(value) {
	if (typeof value === 'object') {
		return value.length === 0;
	} else {
		return value === '';
	}
}

class Select extends Component {
	constructor(props) {
		super(props);
		this.state = {
			selectedOption: null,
			initialValueProcessed: false
		};
		this.handleChange = this.handleChange.bind(this);
		this.getPosts = this.getPosts.bind(this);
		this.getPostsDebounced = debounce(this.getPosts, 500);
		this.setupInitialValue = this.setupInitialValue.bind(this);
	}

	handleChange(value) {
		this.setState({selectedOption: value});
		this.props.onChange(value);
	}

	getPosts(inputValue, callback) {
		const {type} = this.props;
		let query = '/wp/v2/';

		if (!inputValue) {
			return callback([]);
		}

		query += type === 'post' ? 'posts?search=' : 'product?search=';
		query += trim(inputValue);

		return apiFetch({
			path: query,
			method: 'GET'
		}).then((response) => {
			callback(mapPosts(response));
		});
	}

	setupInitialValue(currentValue) {
		const {multiple, type} = this.props;
		let query = '/wp/v2/';
		query += type === 'post' ? 'posts?' : 'product?'

		if (multiple) {
			for (let i = 0; i < currentValue.length; i++) {
				query += `include[]=${currentValue[i]}&`;
			}
		} else {
			query += `include[]=${currentValue}`;
		}

		apiFetch({
			path: query,
			method: 'GET'
		}).then((response) => {
			const value = mapPosts(response);
			this.setState({
				selectedOption: value,
				initialValueProcessed: true
			});
		});
	}

	render() {
		const {label, posts, multiple, currentValue} = this.props;
		const {initialValueProcessed, selectedOption} = this.state;
		let defaultOption = null;
		let value = null;

		if (posts && posts.length) {
			defaultOption = mapPosts(posts);
		}

		if (initialValueProcessed === false && !selectedOption && !isEmpty(currentValue)) {
			this.setupInitialValue(currentValue);
		} else if (initialValueProcessed === false && isEmpty(currentValue)) {
			this.setState({initialValueProcessed: true});
		} else {
			value = selectedOption;
		}

		if (defaultOption && initialValueProcessed) {
			return (
				<BaseControl label={label}>
					<AsyncSelect
						cacheOptions
						defaultOptions={defaultOption}
						loadOptions={this.getPostsDebounced}
						onChange={this.handleChange}
						isMulti={multiple}
						value={value}
						className='react-select'
					/>
				</BaseControl>
			);
		}

		return <Spinner/>;
	}
}

Select.defaultProps = {
	multiple: false,
	type: 'post',
	label: __('Label', 'rehub-framework'),
	onChange: () => {
	},
	posts: [],
	currentValue: ''
};

const ProductSelect = withSelect(
	(select) => {
		return {
			posts: select('core').getEntityRecords('postType', 'product', {per_page: 10})
		};
	}
)(Select);

export {ProductSelect};

export default withSelect(
	(select) => {
		return {
			posts: select('core').getEntityRecords('postType', 'post', {per_page: 10})
		};
	}
)(Select);
