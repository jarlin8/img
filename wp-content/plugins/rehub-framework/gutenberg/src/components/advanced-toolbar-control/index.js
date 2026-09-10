/**
 * Internal dependencies
 */
import BaseControlMultiLabel from '../base-control-multi-label';
import SVGIconBottom from './images/bottom.svg';
import SVGIconHorizontalCenter from './images/horizontal-center.svg';
import SVGIconLeft from './images/left.svg';
import SVGIconRight from './images/right.svg';
import SVGIconStretch from './images/stretch.svg';
import SVGIconTop from './images/top.svg';
import SVGIconVerticalCenter from './images/vertical-center.svg';

/**
 * External dependencies
 */
import {omit} from 'lodash';
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
import {BaseControl, Toolbar} from '@wordpress/components';
import {__} from '@wordpress/i18n';

const FLEX_HORIZONTAL_ALIGN_OPTIONS = [
	{
		value: 'flex-start',
		title: __('Align Left', 'rehub-framework'),
		icon: <SVGIconLeft/>,
	},
	{
		value: 'center',
		title: __('Align Center', 'rehub-framework'),
		icon: <SVGIconHorizontalCenter/>,
	},
	{
		value: 'flex-end',
		title: __('Align Right', 'rehub-framework'),
		icon: <SVGIconRight/>,
	},
];

const FLEX_VERTICAL_ALIGN_STRETCH_OPTIONS = [
	{
		value: 'flex-start',
		title: __('Align Top', 'rehub-framework'),
		icon: <SVGIconTop/>,
	},
	{
		value: 'center',
		title: __('Align Center', 'rehub-framework'),
		icon: <SVGIconVerticalCenter/>,
	},
	{
		value: 'flex-end',
		title: __('Align Bottom', 'rehub-framework'),
		icon: <SVGIconBottom/>,
	},
	{
		value: 'stretch',
		title: __('Stretch', 'rehub-framework'),
		icon: <SVGIconStretch/>,
	},
];

const FLEX_VERTICAL_ALIGN_OPTIONS = [
	{
		value: 'flex-start',
		title: __('Align Top', 'rehub-framework'),
		icon: <SVGIconTop/>,
	},
	{
		value: 'center',
		title: __('Align Center', 'rehub-framework'),
		icon: <SVGIconVerticalCenter/>,
	},
	{
		value: 'flex-end',
		title: __('Align Bottom', 'rehub-framework'),
		icon: <SVGIconBottom/>,
	},
];

const CONTROLS = {
	'flex-horizontal': FLEX_HORIZONTAL_ALIGN_OPTIONS,
	'flex-vertical': FLEX_VERTICAL_ALIGN_OPTIONS,
	'flex-vertical-with-stretch': FLEX_VERTICAL_ALIGN_STRETCH_OPTIONS,
};

const AdvancedToolbarControl = props => {
	const controls = typeof props.controls === 'string' ? CONTROLS[props.controls] : props.controls;

	const toolbarClasses = classnames({
		'rh-toolbar--full-width': props.fullwidth,
		'rh-toolbar--multiline': props.multiline,
	});

	return (
		<BaseControl
			help={props.help}
			className={classnames('rh-advanced-toolbar-control', props.className)}
		>
			<BaseControlMultiLabel
				label={props.label}
				units={props.units}
				unit={props.unit}
				onChangeUnit={props.onChangeUnit}
				screens={props.screens}
			/>
			<Toolbar
				{...omit(props, ['className', 'help', 'label', 'units', 'unit', 'onChangeUnit', 'screens', 'fullwidth', 'multiline'])}
				controls={controls.map(option => {
					return {
						...option,
						onClick: () => props.onChange(option.value !== props.value ? option.value : ''),
						isActive: props.value === option.value,
						extraProps: {
							...(!option.icon ? {
								children: option.custom ||
									<span className="rh-advanced-toolbar-control__text-button">{option.title}</span>
							} : {}),
						},
					}
				})}
				className={toolbarClasses}
			/>
		</BaseControl>
	)
};

AdvancedToolbarControl.defaultProps = {
	onChange: () => {
	},
	onChangeUnit: () => {
	},
	help: '',
	className: '',
	units: ['px'],
	unit: 'px',
	screens: ['desktop'],
	value: '',
	controls: [],
	multiline: false,
	fullwidth: true,
};

export default AdvancedToolbarControl;
