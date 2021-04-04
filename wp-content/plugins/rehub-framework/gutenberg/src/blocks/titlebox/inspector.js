const {__} = wp.i18n;
const {Component} = wp.element;
const {InspectorControls} = wp.blockEditor || wp.editor;

const {
	PanelBody,
	SelectControl
} = wp.components;

/**
 * Create an Inspector Controls wrapper Component
 */
export default class Inspector extends Component {
	render() {
		const {
			attributes: {
				style
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
							{value: '1', label: __('Grey', 'rehub-framework')},
							{value: '2', label: __('Black', 'rehub-framework')},
							{value: '3', label: __('Orange', 'rehub-framework')},
							{value: 'main', label: __('Main Theme Color', 'rehub-framework')},
							{value: 'secondary', label: __('Secondary Theme Color', 'rehub-framework')},
							{value: '4', label: __('Double dotted', 'rehub-framework')},
						]}
						value={style}
						onChange={(style) => setAttributes({style})}
					/>
				</PanelBody>
			</InspectorControls>
		);
	}
}
