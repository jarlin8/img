import {
	PanelBody,
	SelectControl,
	BaseControl,
} from '@wordpress/components';
import {Fragment, Component} from '@wordpress/element'
import {
	__,
} from '@wordpress/i18n';
import AsyncSelect from 'react-select/async';
import Select2 from 'react-select2-wrapper';
import 'react-select2-wrapper/css/select2.css';

async function postData( data = {} ) {
	// Значения по умолчанию обозначены знаком *
	return fetch(ajaxurl, {
		method: 'POST', // *GET, POST, PUT, DELETE, etc.
		mode: 'cors', // no-cors, cors, *same-origin
		cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
		credentials: 'same-origin', // include, *same-origin, omit
		headers: {
			'Accept': 'application/x-www-form-urlencoded',
			// 'Accept': 'application/json',
			// 'Content-Type': 'application/json',
			'Content-Type': 'application/x-www-form-urlencoded',
		},
		redirect: 'follow', // manual, *follow, error
		referrer: 'no-referrer', // no-referrer, *client
		body: new URLSearchParams(data), // тип данных в body должен соответвовать значению заголовка "Content-Type"
	})
		.then(response => response.json())
		.then(({data: {results}}) => results.map(({id, text: label, slug: value}) => ({id, label, value})))// парсит JSON ответ в Javascript объект
}


class WooQuery extends Component {
	constructor() {
		super(...arguments);
		this.state = {
			values: {
				data_source: 'cat',
				cat: [55, 18, 81],
			},
			options: {
				cat: [],
			}
		};
		this.getOptionValue = this.getOptionValue.bind(this);
		this.getOptionLabel = this.getOptionLabel.bind(this);
		this.getTaxonomyValue();
	}

	getTaxonomyValue() {
		const that = this;


		fetch(ajaxurl, {
			method: 'POST', // *GET, POST, PUT, DELETE, etc.
			mode: 'cors', // no-cors, cors, *same-origin
			cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
			credentials: 'same-origin', // include, *same-origin, omit
			headers: {
				'Accept': 'application/x-www-form-urlencoded',
				// 'Accept': 'application/json',
				// 'Content-Type': 'application/json',
				'Content-Type': 'application/x-www-form-urlencoded',
			},
			redirect: 'follow', // manual, *follow, error
			referrer: 'no-referrer', // no-referrer, *client
			body: new URLSearchParams({
				action: 'get_wc_products_cat_list',
				saved: this.state.values.cat
			}), // тип данных в body должен соответвовать значению заголовка "Content-Type"
		})
			.then(response => response.json())
			.then(({data: {results}}) => {
				this.setState({
					values: {
						...this.state.values,
						cat: results
					}
				});
			}).catch((error) => {
			that.setState({
				taxonomy: {error: true, errorMsg: error, data: []}
			});
		});
	}

	async getOptions(inputValue) {
		if (!inputValue) {
			return [];
		}
		return postData({
			search: inputValue, // search term
			action: 'get_wc_products_cat_list',
			page: 1,
		});
	}

	getOptionValue(option) {
		return option.value || option.id;
	}

	getOptionLabel(option) {
		return option.label || option.name;
	}

	render() {
		const {
			values: {
				data_source,
				cat,
			},
			options: {
				cat: options_cat,
			}
		} = this.state;


		return <Fragment>
			<PanelBody
				label={"Data query"}
			>
				<SelectControl
					label={__('Data source', 'rehub-theme')}
					options={[
						{value: 'cat', label: __('Category', 'rehub-theme')},
						{value: 'tag', label: __('Tag', 'rehub-theme')},
						{value: 'ids', label: __('Manual Select and Order', 'rehub-theme')},
						{value: 'type', label: __('Type Of Products', 'rehub-theme')},
						{value: 'auto', label: __('Auto detect archive data', 'rehub-theme')},
					]}
					value={data_source}
					onChange={(data_source) => this.setState({data_source})}
				/>

				<BaseControl
					label={__('Category', 'gt3pg_pro')}
					help={__('Filter output by custom taxonomies categories, enter category names here', 'gt3pg_pro')}
				>
					<Select2
						value={cat}
						data={options_cat}
						multiple
						closeOnSelect={false}
						options={{
							closeOnSelect: false,
							ajax: {
								url: ajaxurl,
								dataType: 'json',
								delay: 250,
								data: function (params) {
									return Object.assign({
										select2: true,
										exclude: cat,
										hide_empty: true,
										action: 'get_wc_products_cat_list',
										page: 1,
									}, params);
								},
								processResults: ({data: {results}}) => {
									return {
										results
									}
								}
							},
							minimumInputLength: 1,
						}}
						onChange={(event) => {
							let val = jQuery(event.currentTarget).val() === null ? [] : jQuery(event.currentTarget).val();

							this.setState({
								values: {
									...this.state.values,
									cat: val
								}
							});
						}}
					/>
				</BaseControl>

				<AsyncSelect
					cacheOptions
					value={this.state.values.cat}
					// valueKey={"slug"}
					// labelKey={"text"}
					defaultOptions={this.state.values.cat}

					// noOptionsMessage={this.noOptionsMessage}
					getOptionValue={this.getOptionValue}
					getOptionLabel={this.getOptionLabel}
					loadOptions={this.getOptions}
					// placeholder={placeholder}
					onChange={(cat) => {
						this.setState({
							values: {
								...this.state.values,
								cat
							}
						});
					}}
					isMulti
				/>
				<hr />
				<hr />
				<hr />
				<hr />
				<hr />
				<hr />
				<hr />
				<hr />
				<hr />
				<hr />
				<hr />
				<hr />
				<hr />
				<hr />
				<hr />
			</PanelBody>
		</Fragment>


	}
}


export default WooQuery;
