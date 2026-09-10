/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';
import {Component} from '@wordpress/element'
import {InspectorControls} from '@wordpress/block-editor';
import {PanelBody, TextControl, SelectControl, ToggleControl, ColorPicker, BaseControl} from '@wordpress/components';

/**
 * External dependencies
 */
import {cloneDeep} from "lodash";

/**
 * Internal dependencies
 */
import ImageControl from "../../components/image-control";

/**
 * Create an Inspector Controls wrapper Component
 */
export default class Inspector extends Component {
	constructor(props) {
		super(props);
		this.handleRemoveImage = this.handleRemoveImage.bind(this);
		this.handleChangeImage = this.handleChangeImage.bind(this);
	}

	handleRemoveImage(propName) {
		const {attributes, setAttributes} = this.props;
		const columnClone = cloneDeep(attributes[propName]);

		columnClone.image = '';
		columnClone.imageId = '';

		setAttributes({
			[propName]: columnClone
		});
	}

	handleChangeImage(propName, media) {
		const {attributes, setAttributes} = this.props;
		const columnClone = cloneDeep(attributes[propName]);

		columnClone.image = media.url;
		columnClone.imageId = media.id;

		setAttributes({
			[propName]: columnClone
		});
	}

	render() {
		const {attributes, setAttributes} = this.props;
		const {heading, subheading, type, firstColumn, secondColumn, thirdColumn, bg, color} = attributes;

		return (
			<InspectorControls>
				<PanelBody title={__('Versus Line Block', 'rehub-framework')} initialOpen={true}>
					<TextControl
						label={__('Heading', 'rehub-framework')}
						value={heading}
						onChange={(value) => {
							setAttributes({
								heading: value
							});
						}}
					/>
					<TextControl
						label={__('Subheading', 'rehub-framework')}
						value={subheading}
						onChange={(value) => {
							setAttributes({
								subheading: value
							});
						}}
					/>
					<SelectControl
						label={__('Type', 'rehub-framework')}
						value={type}
						options={[
							{label: __('Two Column', 'rehub-framework'), value: 'two'},
							{label: __('Three Column', 'rehub-framework'), value: 'three'}
						]}
						onChange={(value) => {
							setAttributes({
								type: value
							});
						}}
					/>
				</PanelBody>
				<PanelBody title={__('Versus Line Style', 'rehub-framework')} initialOpen={false}>
					<BaseControl label={__('Background color (optional)', 'rehub-framework')}>
						<ColorPicker
							color={bg}
							onChangeComplete={(value) => {
								setAttributes({bg: value.hex})
							}}
							disableAlpha
						/>
					</BaseControl>
					<BaseControl label={__('Text color (optional)', 'rehub-framework')}>
						<ColorPicker
							color={color}
							onChangeComplete={(value) => {
								setAttributes({color: value.hex})
							}}
							disableAlpha
						/>
					</BaseControl>
				</PanelBody>
				<PanelBody title={__('First Column', 'rehub-framework')} initialOpen={false}>
					<SelectControl
						label={__('Type', 'rehub-framework')}
						value={firstColumn.type}
						options={[
							{label: __('Text', 'rehub-framework'), value: 'text'},
							{label: __('Image', 'rehub-framework'), value: 'image'},
							{label: __('Check Icon', 'rehub-framework'), value: 'tick'},
							{label: __('Cross Icon', 'rehub-framework'), value: 'times'},
						]}
						onChange={(value) => {
							const firstColumnClone = cloneDeep(firstColumn);
							firstColumnClone.type = value;
							setAttributes({
								firstColumn: firstColumnClone
							});
						}}
					/>
					{firstColumn.type === 'text' && (
						<TextControl
							label={__('Place text', 'rehub-framework')}
							value={firstColumn.content}
							onChange={(value) => {
								const firstColumnClone = cloneDeep(firstColumn);
								firstColumnClone.content = value;
								setAttributes({
									firstColumn: firstColumnClone
								});
							}}
						/>
					)}
					{firstColumn.type === 'image' && (
						<ImageControl
							label={__('Upload Image', 'rehub-framework')}
							allowedTypes={['image']}
							imageURL={firstColumn.image}
							imageID={firstColumn.imageId}
							onRemove={() => {
								this.handleRemoveImage('firstColumn');
							}}
							onChange={(media) => {
								this.handleChangeImage('firstColumn', media);
							}}
						/>
					)}
					<ToggleControl
						label={__('Make column unhighlighted?', 'rehub-framework')}
						checked={firstColumn.isGrey}
						onChange={() => {
							const firstColumnClone = cloneDeep(firstColumn);
							firstColumnClone.isGrey = !firstColumn.isGrey;
							setAttributes({
								firstColumn: firstColumnClone
							});
						}}
					/>
				</PanelBody>
				<PanelBody title={__('Second Column', 'rehub-framework')} initialOpen={false}>
					<SelectControl
						label={__('Type', 'rehub-framework')}
						value={secondColumn.type}
						options={[
							{label: __('Text', 'rehub-framework'), value: 'text'},
							{label: __('Image', 'rehub-framework'), value: 'image'},
							{label: __('Check Icon', 'rehub-framework'), value: 'tick'},
							{label: __('Cross Icon', 'rehub-framework'), value: 'times'},
						]}
						onChange={(value) => {
							const secondColumnClone = cloneDeep(secondColumn);
							secondColumnClone.type = value;
							setAttributes({
								secondColumn: secondColumnClone
							});
						}}
					/>
					{secondColumn.type === 'text' && (
						<TextControl
							label={__('Place text', 'rehub-framework')}
							value={secondColumn.content}
							onChange={(value) => {
								const secondColumnClone = cloneDeep(secondColumn);
								secondColumnClone.content = value;
								setAttributes({
									secondColumn: secondColumnClone
								});
							}}
						/>
					)}
					{secondColumn.type === 'image' && (
						<ImageControl
							label={__('Upload Image', 'rehub-framework')}
							allowedTypes={['image']}
							imageURL={secondColumn.image}
							imageID={secondColumn.imageId}
							onRemove={() => {
								this.handleRemoveImage('secondColumn');
							}}
							onChange={(media) => {
								this.handleChangeImage('secondColumn', media);
							}}
						/>
					)}
					<ToggleControl
						label={__('Make first column unhighlighted?', 'rehub-framework')}
						checked={secondColumn.isGrey}
						onChange={() => {
							const secondColumnClone = cloneDeep(secondColumn);
							secondColumnClone.isGrey = !secondColumn.isGrey;
							setAttributes({
								secondColumn: secondColumnClone
							});
						}}
					/>
				</PanelBody>
				{type === 'three' && (
					<PanelBody title={__('Third Column', 'rehub-framework')} initialOpen={false}>
						<SelectControl
							label={__('Type', 'rehub-framework')}
							value={thirdColumn.type}
							options={[
								{label: __('Text', 'rehub-framework'), value: 'text'},
								{label: __('Image', 'rehub-framework'), value: 'image'},
								{label: __('Check Icon', 'rehub-framework'), value: 'tick'},
								{label: __('Cross Icon', 'rehub-framework'), value: 'times'},
							]}
							onChange={(value) => {
								const thirdColumnClone = cloneDeep(thirdColumn);
								thirdColumnClone.type = value;
								setAttributes({
									thirdColumn: thirdColumnClone
								});
							}}
						/>
						{thirdColumn.type === 'text' && (
							<TextControl
								label={__('Place text', 'rehub-framework')}
								value={thirdColumn.content}
								onChange={(value) => {
									const thirdColumnClone = cloneDeep(thirdColumn);
									thirdColumnClone.content = value;
									setAttributes({
										thirdColumn: thirdColumnClone
									});
								}}
							/>
						)}
						{thirdColumn.type === 'image' && (
							<ImageControl
								label={__('Upload Image', 'rehub-framework')}
								allowedTypes={['image']}
								imageURL={thirdColumn.image}
								imageID={thirdColumn.imageId}
								onRemove={() => {
									this.handleRemoveImage('thirdColumn');
								}}
								onChange={(media) => {
									this.handleChangeImage('thirdColumn', media);
								}}
							/>
						)}
						<ToggleControl
							label={__('Make first column unhighlighted?', 'rehub-framework')}
							checked={thirdColumn.isGrey}
							onChange={() => {
								const thirdColumnClone = cloneDeep(thirdColumn);
								thirdColumnClone.isGrey = !thirdColumn.isGrey;
								setAttributes({
									thirdColumn: thirdColumnClone
								});
							}}
						/>
					</PanelBody>
				)}
			</InspectorControls>
		);
	}
}