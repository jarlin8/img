/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';
import {Component, Fragment} from '@wordpress/element';
import {InspectorControls} from '@wordpress/block-editor';
import {
	PanelBody,
	ColorPicker,
	BaseControl,
	ToggleControl,
	SelectControl,
	TextControl,
	TextareaControl
} from '@wordpress/components';

/**
 * Create an Inspector Controls wrapper Component
 */
export default class Inspector extends Component {
	render() {
		const {attributes, setAttributes} = this.props;
		const {
			      backgroundColor,
			      textColor,
			      showBorder,
			      borderSize,
			      borderColor,
			      showHighlightBorder,
			      highlightColor,
			      highlightPosition,
			      showButton,
			      buttonLink,
			      buttonText,
			      title,
			      content
		      } = attributes;

		return (
			<InspectorControls>
				<PanelBody title={__('General', 'rehub-framework')} initialOpen={true}>
					<BaseControl label={__('Background-color :', 'rehub-framework')}>
						<ColorPicker
							color={backgroundColor}
							onChangeComplete={(value) => {
								setAttributes({backgroundColor: value.hex})
							}}
							disableAlpha
						/>
					</BaseControl>
					<BaseControl label={__('Text color :', 'rehub-framework')}>
						<ColorPicker
							color={textColor}
							onChangeComplete={(value) => {
								setAttributes({textColor: value.hex})
							}}
							disableAlpha
						/>
					</BaseControl>

					{/* Border */}
					<ToggleControl
						label={__('Show border?', 'rehub-framework')}
						checked={showBorder}
						onChange={() => {
							setAttributes({
								showBorder: !showBorder
							});
						}}
					/>
					{showBorder && (
						<Fragment>
							<SelectControl
								label={__('Border size :', 'rehub-framework')}
								value={borderSize}
								options={[
									{label: __('1px', 'rehub-framework'), value: 1},
									{label: __('2px', 'rehub-framework'), value: 2},
									{label: __('3px', 'rehub-framework'), value: 3},
									{label: __('4px', 'rehub-framework'), value: 4},
									{label: __('5px', 'rehub-framework'), value: 5},
								]}
								onChange={(value) => {
									setAttributes({
										borderSize: value
									});
								}}
							/>
							<BaseControl label={__('Border color :', 'rehub-framework')}>
								<ColorPicker
									color={borderColor}
									onChangeComplete={(value) => {
										setAttributes({borderColor: value.hex})
									}}
									disableAlpha
								/>
							</BaseControl>
						</Fragment>
					)}

					{/*	Highlight */}
					<ToggleControl
						label={__('Show highlight border?', 'rehub-framework')}
						checked={showHighlightBorder}
						onChange={() => {
							setAttributes({
								showHighlightBorder: !showHighlightBorder
							});
						}}
					/>
					{showHighlightBorder && (
						<Fragment>
							<BaseControl label={__('Highlight color :', 'rehub-framework')}>
								<ColorPicker
									color={highlightColor}
									onChangeComplete={(value) => {
										setAttributes({highlightColor: value.hex})
									}}
									disableAlpha
								/>
							</BaseControl>
							<SelectControl
								label={__('Highlight position :', 'rehub-framework')}
								value={highlightPosition}
								options={[
									{label: __('Left', 'rehub-framework'), value: 'Left'},
									{label: __('Top', 'rehub-framework'), value: 'Top'},
									{label: __('Right', 'rehub-framework'), value: 'Right'},
									{label: __('Bottom', 'rehub-framework'), value: 'Bottom'}
								]}
								onChange={(value) => {
									setAttributes({
										highlightPosition: value
									});
								}}
							/>
						</Fragment>
					)}

					{/*	Button */}
					<ToggleControl
						label={__('Show button?', 'rehub-framework')}
						checked={showButton}
						onChange={() => {
							setAttributes({
								showButton: !showButton
							});
						}}
					/>
					{showButton && (
						<Fragment>
							<TextControl
								label={__('Button link :', 'rehub-framework')}
								value={buttonLink}
								onChange={(value) => {
									setAttributes({
										buttonLink: value
									});
								}}
							/>
							<TextControl
								label={__('Button text :', 'rehub-framework')}
								value={buttonText}
								onChange={(value) => {
									setAttributes({
										buttonText: value
									});
								}}
							/>
						</Fragment>
					)}

					{/* Content */}
					<TextControl
						label={__('Title of box :', 'rehub-framework')}
						value={title}
						onChange={(value) => {
							setAttributes({
								title: value
							});
						}}
					/>
					<TextareaControl
						label={__('Text', 'rehub-framework')}
						value={content}
						onChange={(value) => {
							setAttributes({content: value});
						}}
					/>
				</PanelBody>
			</InspectorControls>
		);
	}
}
