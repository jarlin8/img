import PropTypes from 'prop-types';

const {addQueryArgs} = wp.url;

const {__} = wp.i18n;
const {Component, Fragment} = wp.element;

const {SelectControl, TextControl, BaseControl} = wp.components;
import Select2 from "./Select2";

/**
 * Create a Block Controls wrapper Component
 */
class QueryControl extends Component {

	constructor() {
		super(...arguments);

		this.baseurl = window.location.href.substring(0, window.location.href.indexOf('/wp-admin'));

		const {attributes, query_field, showCategory} = this.props;

		if (!showCategory) return;

		const query = attributes[query_field];

		this.onChangeValue = this.onChangeValue.bind(this);
		this.query_object = query;

		this.state = {
			taxonomy: {
				data: query.taxonomy,
			},
			tags: {
				data: query.tags,
			},
			author__in: {
				data: query.author__in,
			},
			post__in: {
				data: query.post__in
			},
		};

		this.getTaxonomyValue = this.getTaxonomyValue.bind(this);
		this.getTagValue = this.getTagValue.bind(this);
		this.getUserValue = this.getUserValue.bind(this);
		this.getPostValue = this.getPostValue.bind(this);
	}

	onChangeValue(key, value) {
		let key_name = this.props.query_field;
		this.query_object = Object.assign({}, this.props.attributes[key_name]);
		this.query_object[key] = value;
		this.buildQuery();
	}

	componentDidMount() {
		this.getTaxonomyValue();
		this.getTagValue();
		this.getUserValue();
		this.getPostValue();
	}

	getTaxonomyValue() {
		const that = this;

		const {attributes, query_field, showCategory, post_taxonomy} = this.props;

		if (!showCategory) return;

		const query = attributes[query_field];
		wp.apiFetch({
			path: addQueryArgs(
				`/gt3/v1/gutenberg/get-taxonomy/`,
				{taxonomy: post_taxonomy, include: query.taxonomy}
			)
		}).then(data => {
			that.setState({
				taxonomy: {
					data: data.map((val) => {
						return {id: val.value, text: val.label};
					}),
					error: false,
				}
			});
		}).catch((error) => {
			that.setState({
				taxonomy: {error: true, errorMsg: error, data: []}
			});
		});
	}

	getTagValue() {
		const that = this;

		const {attributes, query_field, showTag, post_tag} = this.props;

		if (!showTag) return;


		const query = attributes[query_field];
		wp.apiFetch({
			path: addQueryArgs(
				`/gt3/v1/gutenberg/get-taxonomy/`,
				{taxonomy: post_tag, include: query.tags}
			)
		}).then(data => {
			that.setState({
				tags: {
					data: data.map((val) => {
						return {id: val.value, text: val.label};
					}),
					error: false,
				}
			});
		}).catch((error) => {
			that.setState({
				tags: {error: true, errorMsg: error, data: []}
			});
		});
	}

	getUserValue() {
		const that = this;

		const {attributes, query_field, showUser, post_type} = this.props;

		if (!showUser) return;

		const query = attributes[query_field];

		wp.apiFetch({
			path: addQueryArgs(
				`/gt3/v1/gutenberg/get-users/`,
				{post_type: post_type, include: query.author__in}
			)
		}).then(data => {
			that.setState({
				author__in: {
					data: data.map((val) => {
						return {id: val.value, text: val.label};
					}),
					error: false,
				}
			});
		}).catch((error) => {
			that.setState({
				author__in: {error: true, errorMsg: error, data: []}
			});
		});
	}

	getPostValue() {
		const that = this;

		const {attributes, query_field, showPost, post_type} = this.props;

		if (!showPost) return;

		const query = attributes[query_field];

		wp.apiFetch({
			path: addQueryArgs(
				`/gt3/v1/gutenberg/get-posts/`,
				{post_type: post_type, include: query.post__in}
			)
		}).then(data => {
			that.setState({
				post__in: {
					data: data.map((val) => {
						return {id: val.value, text: val.label};
					}),
					error: false,
				}
			});
		}).catch((error) => {
			that.setState({
				post__in: {error: true, errorMsg: error, data: []}
			});
		});
	}

	buildQuery() {
		const {post_type, post_taxonomy, post_tag} = this.props;

		let key_name = this.props.query_field;
		let attributes = Object.assign({}, this.query_object);

		let query = {
			post_status: ['publish'],
			post_type,
		};
		if ('posts_per_page' in attributes) {
			attributes.posts_per_page = parseInt(attributes.posts_per_page);
			query.posts_per_page = attributes.posts_per_page >= -1 ? attributes.posts_per_page : 12;
		}
		if ('orderby' in attributes && attributes.orderby) {
			query.orderby = attributes.orderby;
		}
		if ('order' in attributes && attributes.order) {
			query.order = attributes.order;
		}

		if ('post__in' in attributes && Array.isArray(attributes.post__in) && !!attributes.post__in.length) {
			query.post__in = attributes.post__in;
		} else {
			if ('author__in' in attributes && Array.isArray(attributes.author__in) && !!attributes.author__in.length) {
				query.author__in = attributes.author__in;
			}
			if (('taxonomy' in attributes && Array.isArray(attributes.taxonomy) && !!attributes.taxonomy.length) ||
				('tags' in attributes && Array.isArray(attributes.tags) && !!attributes.tags.length)) {
				query.tax_query = {};
				query.tax_query.relation = 'AND';
				let tax_index = 0;

				if ('taxonomy' in attributes && Array.isArray(attributes.taxonomy) && !!attributes.taxonomy.length) {
					query.tax_query[tax_index++] = {
						field: 'slug',
						operator: 'IN',
						taxonomy: post_taxonomy,
						terms: attributes.taxonomy
					};
				}
				if ('tags' in attributes && Array.isArray(attributes.tags) && !!attributes.tags.length) {
					query.tax_query[tax_index++] = {
						field: 'slug',
						operator: 'IN',
						taxonomy: post_tag,
						terms: attributes.tags
					};
				}
			}
		}
		attributes.query = query;

		let new_obj = {};
		new_obj[key_name] = attributes;
		this.props.setAttributes(new_obj);
	}


	render() {
		const {attributes, query_field, showCategory, showTag, showUser, showPost, post_type, post_taxonomy, post_tag} = this.props;
		const query = attributes[query_field];
		const select_options = {
			orderby: [
				{value: '', label: ''},
				{value: 'date', label: __('Date', 'gt3pg_pro')},
				{value: 'ID', label: __('ID', 'gt3pg_pro')},
				{value: 'author', label: __('Author', 'gt3pg_pro')},
				{value: 'title', label: __('Title', 'gt3pg_pro')},
				{value: 'modified', label: __('Modified', 'gt3pg_pro')},
				{value: 'rand', label: __('Random', 'gt3pg_pro')},
				{value: 'comment_count', label: __('Comment count', 'gt3pg_pro')},
				{value: 'menu_order', label: __('Menu order', 'gt3pg_pro')},
			],
			order: [
				{value: '', label: ''},
				{value: 'ASC', label: __('Ascending', 'gt3pg_pro')},
				{value: 'DESC', label: __('Descending', 'gt3pg_pro')},
			],
		};

		return (
			<Fragment>
				<TextControl
					label={__('Post Count', 'gt3pg_pro')}
					type={'number'}
					value={query.posts_per_page}
					min={-1}
					max={100}
					onChange={(value) => this.onChangeValue('posts_per_page', value)}
					help={__('How many teasers to show? Enter number, -1 for All.', 'gt3pg_pro')}
				/>
				{/*<ToggleControl
					label={__('Ignore Sticky Posts', 'gt3pg_pro')}
					help={__('If checked, cut off text in blog listing', 'gt3pg_pro')}
					checked={query.ignore_sticky_posts}
					onChange={(value) => this.onChangeValue('ignore_sticky_posts', value)}
				/>*/}

				<SelectControl
					label={__('Order By', 'gt3pg_pro')}
					options={select_options.orderby}
					value={query.orderby}
					onChange={(value) => this.onChangeValue('orderby', value)}
					help={<Fragment>
						{__('Select how to sort retrieved posts. More at', 'gt3pg_pro')} {' '}
						<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters"
						   without rel="noopener noreferrer"
						   target="_blank">
							{__('WordPress codex page', 'gt3pg_pro')}
						</a>
					</Fragment>}
				/>
				<SelectControl
					label={__('Order', 'gt3pg_pro')}
					options={select_options.order}
					value={query.order}
					onChange={(value) => this.onChangeValue('order', value)}
					help={__('Designates the ascending or descending order', 'gt3pg_pro')}
				/>
				{showPost && <Fragment>
					<BaseControl
						label={__('Posts', 'gt3pg_pro')}
						help={__('Filter output by custom Post, enter post names here', 'gt3pg_pro')}
					>
						<Select2
							value={query.post__in}
							data={this.state.post__in.data}
							multiple
							closeOnSelect={false}
							options={{
								ajax: {

									url: `${this.baseurl}/index.php?rest_route=/gt3/v1/gutenberg/get-posts/`,
									dataType: 'json',
									delay: 250,
									data: function (params) {
										return Object.assign({
											select2: true,
											post_type: post_type,
											exclude: query.post__in,
										}, params);
									},
									processResults: function (data) {
										return {
											results: data.map((val) => {
												return {
													id: val.value,
													text: val.label
												};
											})

										};
									},
								},
								minimumInputLength: 1,
							}}
							onChange={(event) => {
								let val = jQuery(event.currentTarget).val() === null ? [] : jQuery(event.currentTarget).val();

								this.onChangeValue('post__in', val);
							}}
						/>
					</BaseControl>
				</Fragment>}
				{!!query.post__in.length && <Fragment>
					<div style={{
						color: 'red',
						textAlign: 'center',
						padding: 10,
						margin: '5px 0 15px',
						border: '1px red solid'
					}}>{__('Note: if selected Custom Posts - other settings will be disabled', 'gt3pg_pro')}</div>
				</Fragment>}


				{!query.post__in.length && <Fragment>
					{showCategory && <Fragment>
						<BaseControl
							label={__('Category', 'gt3pg_pro')}
							help={__('Filter output by custom taxonomies categories, enter category names here', 'gt3pg_pro')}
						>
							<Select2
								value={query.taxonomy}
								data={this.state.taxonomy.data}
								multiple
								closeOnSelect={false}
								options={{
									closeOnSelect: false,
									ajax: {
										url: `${this.baseurl}/index.php?rest_route=/gt3/v1/gutenberg/get-taxonomy/`,
										dataType: 'json',
										delay: 250,
										data: function (params) {
											return Object.assign({
												select2: true,
												taxonomy: post_taxonomy,
												exclude: query.taxonomy,
												hide_empty: true,
											}, params);
										},
										processResults: function (data) {
											return {
												results: data.map((val) => {
													return {
														id: val.value,
														text: val.label
													};
												})
											};
										},
									},
									minimumInputLength: 1,
								}}
								onChange={(event) => {
									let val = jQuery(event.currentTarget).val() === null ? [] : jQuery(event.currentTarget).val();

									this.onChangeValue('taxonomy', val);
								}}
							/>
						</BaseControl>
					</Fragment>}
					{showTag && <Fragment>
						<BaseControl
							label={__('Tags', 'gt3pg_pro')}
							help={__('Filter output by custom Tags, enter tags names here', 'gt3pg_pro')}
						>
							<Select2
								value={query.tags}
								data={this.state.tags.data}
								multiple
								closeOnSelect={false}
								options={{
									ajax: {
										url: `${this.baseurl}/index.php?rest_route=/gt3/v1/gutenberg/get-taxonomy/`,
										dataType: 'json',
										delay: 250,
										data: function (params) {
											return Object.assign({
												select2: true,
												taxonomy: post_tag,
												exclude: query.tags,
												hide_empty: true,
											}, params);
										},
										processResults: function (data) {
											return {
												results: data.map((val) => {
													return {
														id: val.value,
														text: val.label
													};
												})

											};
										},
									},
									minimumInputLength: 1,
								}}
								onChange={(event) => {
									let val = jQuery(event.currentTarget).val() === null ? [] : jQuery(event.currentTarget).val();

									this.onChangeValue('tags', val);
								}}
							/>
						</BaseControl>
					</Fragment>}

					{showUser && <Fragment>
						<BaseControl
							label={__('Users', 'gt3pg_pro')}
							help={__('Filter output by custom Users, enter users names here', 'gt3pg_pro')}
						>
							<Select2
								value={query.author__in}
								data={this.state.author__in.data}
								multiple
								closeOnSelect={false}
								options={{
									ajax: {
										url: `${this.baseurl}/index.php?rest_route=/gt3/v1/gutenberg/get-users/`,
										dataType: 'json',
										delay: 250,
										data: function (params) {
											return Object.assign({
												select2: true,
												post_type: post_type,
												exclude: query.author__in,
											}, params);
										},
										processResults: function (data) {
											return {
												results: data.map((val) => {
													return {
														id: val.value,
														text: val.label
													};
												})

											};
										},
									},
									minimumInputLength: 1,
								}}
								onChange={(event) => {
									let val = jQuery(event.currentTarget).val() === null ? [] : jQuery(event.currentTarget).val();

									this.onChangeValue('author__in', val);
								}}
							/>
						</BaseControl>
					</Fragment>}
				</Fragment>}

			</Fragment>
		);
	}
}

QueryControl.defaultProps = {
	query_field: 'query',
	showCategory: false,
	showTag: false,
	showUser: false,
	showPost: false,

	post_type: 'post',
	post_taxonomy: 'category',
	post_tag: 'post_tag',
};

QueryControl.propTypes = {
	query_field: PropTypes.string,
	showCategory: PropTypes.bool,
	showTag: PropTypes.bool,
	showUser: PropTypes.bool,
	showPost: PropTypes.bool,

	post_type: PropTypes.string,
	post_taxonomy: PropTypes.string,
	post_tag: PropTypes.string,
};

export default QueryControl;

