/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';
import {Component} from '@wordpress/element';
import {InspectorControls} from '@wordpress/block-editor';
import {BaseControl, ColorPicker, PanelBody, TextareaControl} from '@wordpress/components';

/**
 * Create an Inspector Controls wrapper Component
 */
export default class Inspector extends Component {
	render() {
		const {attributes, setAttributes} = this.props;
		const {title, subtitle, backgroundColor, titleColor, subtitleColor} = attributes;

		return (
			<InspectorControls>
				<PanelBody title={__('General', 'rehub-framework')} initialOpen={true}>
					<TextareaControl
						label={__('Title', 'rehub-framework')}
						value={title}
						onChange={(value) => {
							setAttributes({title: value});
						}}
					/>
					<TextareaControl
						label={__('Subtitle', 'rehub-framework')}
						value={subtitle}
						onChange={(value) => {
							setAttributes({subtitle: value});
						}}
					/>
					<BaseControl label={__('Title color :', 'rehub-framework')}>
						<ColorPicker
							color={titleColor}
							onChangeComplete={(value) => {
								setAttributes({titleColor: value.hex})
							}}
							disableAlpha
						/>
					</BaseControl>
					<BaseControl label={__('Subtitle color :', 'rehub-framework')}>
						<ColorPicker
							color={subtitleColor}
							onChangeComplete={(value) => {
								setAttributes({subtitleColor: value.hex})
							}}
							disableAlpha
						/>
					</BaseControl>
					<BaseControl label={__('Background-color :', 'rehub-framework')}>
						<ColorPicker
							color={backgroundColor}
							onChangeComplete={(value) => {
								setAttributes({backgroundColor: value.hex})
							}}
							disableAlpha
						/>
					</BaseControl>
				</PanelBody>
			</InspectorControls>
		);
	}
}
