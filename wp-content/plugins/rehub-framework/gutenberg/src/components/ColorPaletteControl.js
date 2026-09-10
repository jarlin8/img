/**
 * Color Palette Control
 *
 */

/**
 * WordPress dependencies
 */
import {__, sprintf} from '@wordpress/i18n'
import {BaseControl, ColorIndicator, ColorPalette,} from '@wordpress/components';
import {compose, ifCondition} from '@wordpress/compose'
import {getColorObjectByColorValue, withColorContext} from '@wordpress/block-editor'
import {Fragment} from '@wordpress/element'


/**
 * External dependencies
 */
import classnames from 'classnames';

const colorIndicatorAriaLabel = __('(current %s: %s)', 'rehub-framework');

export const ColorPaletteControl = ({
	                                    colors,
	                                    disableCustomColors,
	                                    label,
	                                    onChange,
	                                    value,
	                                    className = '',
                                    }) => {
	const colorObject = getColorObjectByColorValue(colors, value);
	const colorName = colorObject && colorObject.name;
	const ariaLabel = sprintf(colorIndicatorAriaLabel, label.toLowerCase(), colorName || value);
	const labelElement = (
		<Fragment>
			{label}
			{value && (
				<ColorIndicator
					colorValue={value}
					aria-label={ariaLabel}
				/>
			)}
		</Fragment>
	);

	return (
		<BaseControl
			className={classnames([className, 'editor-color-palette-control'])}
			id="editor-color-palette-control"
			label={labelElement}>
			<ColorPalette
				className="editor-color-palette-control__color-palette"
				value={value}
				onChange={onChange}
				{...{colors, disableCustomColors}}
			/>
		</BaseControl>
	)
};

export default compose([
	withColorContext,
	ifCondition(({hasColorsToChoose}) => hasColorsToChoose),
])(ColorPaletteControl);