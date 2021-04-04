/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';
import {Component} from '@wordpress/element'
import {PanelBody, Button, BaseControl, ColorPicker, TextareaControl} from '@wordpress/components';

/**
 * External dependencies
 */
import {cloneDeep} from "lodash";
import {SortableContainer, SortableElement} from "../../react-sortable-hoc.esm";

/**
 * Internal dependencies
 */
import IconPopover from "../../components/IconPopover";

const SortableItem = SortableElement((props) => {
	const {attributes, setAttributes, sortIndex} = props;
	const {items} = attributes;
	const cloneItems = cloneDeep(items);
	const {color, icon, content} = items[sortIndex];

	const handleClose = (index) => {
		cloneItems.splice(index, 1);
		setAttributes({items: cloneItems});
	};

	const handleIconChange = (value) => {
		cloneItems[sortIndex].icon = value;
		setAttributes({items: cloneItems});
	};

	const handleIconReset = () => {
		cloneItems[sortIndex].icon = 'rhicon rhi-circle-solid';
		setAttributes({items: cloneItems});
	};

	return (
		<li className='components-card-list__item'>
			<PanelBody title={icon} initialOpen={false}>
				<BaseControl className='rri-advanced-range-control'
				             label={__('Icon', 'rehub-framework')}>
					<IconPopover
						onChange={handleIconChange}
						onReset={handleIconReset}
						currentIcon={icon}
					/>
				</BaseControl>
				<BaseControl className='rri-advanced-range-control'
				             label={__('Set background color', 'rehub-framework')}>
					<ColorPicker
						color={color}
						onChangeComplete={(value) => {
							cloneItems[sortIndex].color = value.hex;
							setAttributes({items: cloneItems});
						}}
						disableAlpha
					/>
				</BaseControl>
				<TextareaControl
					label={__('Content', 'rehub-framework')}
					value={content}
					onChange={(value) => {
						cloneItems[sortIndex].content = value;
						setAttributes({items: cloneItems});
					}}
				/>
				<BaseControl className='text-center'>
					<Button isSecondary onClick={() => handleClose(sortIndex)}>
						{__('Remove item', 'rehub-framework')}
					</Button>
				</BaseControl>
			</PanelBody>
		</li>
	);
});

const SortableList = SortableContainer((props) => {
	const {attributes, setAttributes} = props;
	const {items} = attributes;

	return (
		<ul className='components-card-list'>
			{items.map((value, index) => {
				return (
					<SortableItem
						key={`item-${index}`}
						index={index}
						sortIndex={index}
						attributes={attributes}
						setAttributes={setAttributes}
					/>
				);
			})}
		</ul>
	);
});

export default class SettingsList extends Component {
	constructor(props) {
		super(props);
		this.onSortEnd = this.onSortEnd.bind(this);
		this.shouldCancelStart = this.shouldCancelStart.bind(this);
	}

	onSortEnd({oldIndex, newIndex}) {
		const {attributes, setAttributes} = this.props;
		const cloneItems = cloneDeep(attributes.items);
		cloneItems.splice(newIndex, 0, cloneItems.splice(oldIndex, 1)[0]);
		setAttributes({items: cloneItems});
	}

	shouldCancelStart(ev) {
		if (ev.target.className !== 'components-panel__body-title') {
			return true;
		}
	}

	render() {
		return (
			<SortableList
				lockAxis='y'
				distance={20}
				attributes={this.props.attributes}
				setAttributes={this.props.setAttributes}
				onSortEnd={this.onSortEnd}
				shouldCancelStart={this.shouldCancelStart}
			/>
		);
	}
}