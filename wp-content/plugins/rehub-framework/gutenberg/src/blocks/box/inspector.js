const {__} = wp.i18n;
const {Component} = wp.element;
const {
	      InspectorControls,
      } = wp.blockEditor || wp.editor;

const {
	      PanelBody,
	      SelectControl,
	      TextControl,
      } = wp.components;

import {ToggleControl} from "@wordpress/components";

/**
 * Create an Inspector Controls wrapper Component
 */
export default class Inspector extends Component {
	render() {
		const {
			      attributes: {
				      type,
				      float,
				      textalign,
				      takeDate,
				      label,
			      },
			      setAttributes
		      } = this.props;

		return (
			<InspectorControls>
				<PanelBody
					initialOpen={true}
					title={__('Main Settings', 'rehub-framework')}
				>
					<SelectControl
						label={__('Type', 'rehub-framework')}
						options={[
							{value: 'info', label: __('Info', 'rehub-framework')},
							{value: 'download', label: __('Download', 'rehub-framework')},
							{value: 'error', label: __('Error', 'rehub-framework')},
							{value: 'warning', label: __('Warning', 'rehub-framework')},
							{value: 'yellow', label: __('Yellow color box', 'rehub-framework')},
							{value: 'green', label: __('Green color box', 'rehub-framework')},
							{value: 'gray', label: __('Gray color box', 'rehub-framework')},
							{value: 'blue', label: __('Blue color box', 'rehub-framework')},
							{value: 'red', label: __('Red color box', 'rehub-framework')},
							{value: 'dashed_border', label: __('Dashed', 'rehub-framework')},
							{value: 'solid_border', label: __('Solid border', 'rehub-framework')},
							{value: 'transparent', label: __('Transparent background box', 'rehub-framework')},
						]}
						value={type}
						onChange={(type) => setAttributes({type})}
					/>
					<SelectControl
						label={__('Box float', 'rehub-framework')}
						options={[
							{value: 'none', label: __('None', 'rehub-framework')},
							{value: 'left', label: __('Left', 'rehub-framework')},
							{value: 'right', label: __('Right', 'rehub-framework')},
						]}
						value={float}
						onChange={(float) => setAttributes({float})}
					/>
					<SelectControl
						label={__('Text align', 'rehub-framework')}
						options={[
							{value: 'left', label: __('Left', 'rehub-framework')},
							{value: 'right', label: __('Right', 'rehub-framework')},
							{value: 'justify', label: __('Justify', 'rehub-framework')},
							{value: 'center', label: __('Center', 'rehub-framework')},
						]}
						value={textalign}
						onChange={(textalign) => setAttributes({textalign})}
					/>
					<ToggleControl
						label={__('Take current date', 'rehub-framework')}
						checked={takeDate}
						onChange={(takeDate) => {
							const date = new Date();
							setAttributes({takeDate, date: date.toLocaleDateString()})
						}}
					/>
					{!!+takeDate && <TextControl
						label={__('Label', 'rehub-framework')}
						value={label}
						onChange={(label) => setAttributes({label})}
					/>}
				</PanelBody>
			</InspectorControls>
		);
	}
}
