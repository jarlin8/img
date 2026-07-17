/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';
import {Component} from '@wordpress/element';
import {InspectorControls} from '@wordpress/block-editor';
import {PanelBody, Notice, BaseControl} from '@wordpress/components';

/**
 * Internal dependencies
 */
import {ProductSelect} from "../../components/select";
import updateProductData from "./util/updateProductData";

/**
 * Create an Inspector Controls wrapper Component
 */
export default class Inspector extends Component {
	render() {
		const {attributes, setAttributes} = this.props;
		const {productId, parseError, parseSuccess} = attributes;

		return (
			<InspectorControls>
				<PanelBody title={__('Data query', 'rehub-framework')} initialOpen={true}>
					<ProductSelect
						label={__('Product name', 'rehub-framework')}
						multiple={false}
						onChange={({value}) => {
							updateProductData(value, setAttributes);
						}}
						currentValue={productId}
						type='product'
					/>
					<BaseControl className='rri-advanced-range-control'>
						{parseError && (
							<Notice status="error" onRemove={() => setAttributes({parseError: ''})}>
								{parseError}
							</Notice>
						)}
						{(parseSuccess && !parseError) && (
							<Notice status="success" onRemove={() => setAttributes({parseSuccess: ''})}>
								{parseSuccess}
							</Notice>
						)}
					</BaseControl>
				</PanelBody>
			</InspectorControls>
		);
	}
}

