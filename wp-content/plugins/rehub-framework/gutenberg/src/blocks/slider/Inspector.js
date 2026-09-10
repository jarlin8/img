/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';
import {Component} from '@wordpress/element'
import {InspectorControls} from "@wordpress/block-editor";
import {BaseControl, Button, PanelBody} from "@wordpress/components";

/**
 * Internal dependencies
 */
import SlidesSettings from "./SlidesSettings";

/**
 * External dependencies
 */
import {cloneDeep} from "lodash";

/**
 * Create an Inspector Controls wrapper Component
 */
export default class Inspector extends Component {
	render() {
		const {attributes, setAttributes} = this.props;
		const {slides} = attributes;
		const slidesClone = cloneDeep(slides);

		return (
			<InspectorControls>
				<PanelBody title={__('General', 'rehub-framework')} initialOpen>
					<SlidesSettings
						items={slides}
						setAttributes={setAttributes}
						propName='slides'
					/>
					<BaseControl className='text-center'>
						<Button isPrimary onClick={() => {
							slidesClone.push({
								image: {
									id: '',
									url: `${window.RehubGutenberg.pluginDirUrl}/gutenberg/src/icons/noimage-placeholder.png`,
									width: '',
									height: '',
									alt: ''
								},
							});
							setAttributes({slides: slidesClone});
						}}>
							{__('Add item', 'rehub-framework')}
						</Button>
					</BaseControl>
				</PanelBody>
			</InspectorControls>
		);
	}
}