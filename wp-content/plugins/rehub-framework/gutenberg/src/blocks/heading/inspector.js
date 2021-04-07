import HeadingToolbar
	from "./heading-toolbar";

const {__} = wp.i18n;
const {Component} = wp.element;
const {
	InspectorControls,
} = wp.blockEditor || wp.editor;

const {
	PanelBody,
	TextControl,
} = wp.components;

import {BaseControl} from "@wordpress/components";

/**
 * Create an Inspector Controls wrapper Component
 */
export default class Inspector extends Component {
	render() {
		const {
			attributes: {
				level,
				backgroundText,
			},
			setAttributes
		} = this.props;

		return (
			<InspectorControls>
				<PanelBody
					initialOpen={true}
					title={__('Main Settings', 'rehub-framework')}
				>
					<BaseControl
						label={ __( 'Level' ) }
					>
						<HeadingToolbar
							isCollapsed={ false }
							minLevel={ 1 }
							maxLevel={ 7 }
							selectedLevel={ level }
							onChange={ ( level ) => setAttributes( { level } ) }
						/>
					</BaseControl>

					<TextControl
						label={__('Background Text','rehub-framework')}
						value={backgroundText}
						onChange={(backgroundText) => setAttributes({backgroundText})}
					/>
				</PanelBody>
			</InspectorControls>
		);
	}
}
