/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';
import {Component} from '@wordpress/element'
import {InspectorControls} from '@wordpress/block-editor';
import {PanelBody} from '@wordpress/components';

/**
 * Internal dependencies
 */
import {updateOfferData} from "../utils/fetchService";
import Select from "../../../components/select";

/**
 * Create an Inspector Controls wrapper Component
 */
export default class Inspector extends Component {
	render() {
		const {attributes, setAttributes} = this.props;
		const {selectedPost} = attributes;

		return (
			<InspectorControls>
				<PanelBody title={__('Copy data from Posts', 'rehub-framework')} initialOpen={true}>
					<Select
						label={__('Post name', 'rehub-framework')}
						multiple={false}
						onChange={({value}) => {
							updateOfferData(value, setAttributes, attributes);
						}}
						currentValue={selectedPost}
						type='post'
					/>
				</PanelBody>
			</InspectorControls>
		);
	}
}
