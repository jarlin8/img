/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';
import {Component} from '@wordpress/element'
import {Card, CardBody, CardHeader, TextControl, TextareaControl} from '@wordpress/components';
import {Icon, close} from '@wordpress/icons';


/**
 * External dependencies
 */
import {cloneDeep} from "lodash";
import {SortableContainer, SortableElement} from '../../react-sortable-hoc.esm';

/**
 * Internal dependencies
 */
import AdvancedRangeControl from "../advanced-range-control";


const SortableItem = SortableElement((props) => {
	const {data, sortIndex, titlePlaceholder, contentPlaceholder, includeContentField, includeValueField, onChange, onClose} = props;
	const {title, content, value} = data;

	return (
		<li className='components-card-list__item'>
			<Card>
				<CardHeader>
					<div className="components-card-header">
						<div className='components-card-header__copy'>{title ? title : titlePlaceholder}</div>
						<div className='components-card-header__cta'>
							<Icon icon={close} onClick={() => onClose(sortIndex)}/>
						</div>
					</div>
				</CardHeader>
				<CardBody>
					<TextControl
						label={__('Title', 'rehub-framework')}
						value={title}
						placeholder={titlePlaceholder}
						onChange={(value) => onChange(value, 'title', sortIndex)}
					/>
					{includeContentField && (
						<TextareaControl
							label={__('Content', 'rehub-framework')}
							value={content}
							placeholder={contentPlaceholder}
							onChange={(value) => onChange(value, 'content', sortIndex)}
						/>
					)}
					{includeValueField && (
						<AdvancedRangeControl
							label={__('Value', 'rehub-framework')}
							value={value}
							min="0"
							max="10"
							step={0.5}
							onChange={(value) => onChange(value, 'value', sortIndex)}
						/>
					)}
				</CardBody>
			</Card>
		</li>
	);
});

const SortableList = SortableContainer((props) => {
	const {items, titlePlaceholder, contentPlaceholder, includeContentField, includeValueField, onChange, onClose} = props;

	return (
		<ul className='components-card-list'>
			{items.map((value, index) => {
				return (
					<SortableItem
						key={`item-${index}`}
						index={index}
						sortIndex={index}
						titlePlaceholder={titlePlaceholder}
						contentPlaceholder={contentPlaceholder}
						includeContentField={includeContentField}
						includeValueField={includeValueField}
						data={value}
						onChange={onChange}
						onClose={onClose}
					/>
				);
			})}
		</ul>
	);
});

export default class CardList extends Component {
	constructor(props) {
		super(props);
		this.onSortEnd = this.onSortEnd.bind(this);
		this.onChange = this.onChange.bind(this);
		this.onClose = this.onClose.bind(this);
	}

	onSortEnd({oldIndex, newIndex}) {
		const cloneItems = cloneDeep(this.props.items);

		cloneItems.splice(newIndex, 0, cloneItems.splice(oldIndex, 1)[0]);

		this.props.setAttributes({
			[this.props.propName]: cloneItems
		});
	}

	onChange(newValue, propertyName, itemIndex) {
		const cloneItems = cloneDeep(this.props.items);
		cloneItems[itemIndex][propertyName] = newValue;

		this.props.setAttributes({
			[this.props.propName]: cloneItems
		});
	}

	onClose(itemIndex) {
		const cloneItems = cloneDeep(this.props.items);
		cloneItems.splice(itemIndex, 1);

		this.props.setAttributes({
			[this.props.propName]: cloneItems
		});
	}

	render() {
		return (
			<SortableList
				lockAxis='y'
				distance={10}
				items={this.props.items}
				titlePlaceholder={this.props.titlePlaceholder}
				contentPlaceholder={this.props.contentPlaceholder}
				includeContentField={this.props.includeContentField}
				includeValueField={this.props.includeValueField}
				onSortEnd={this.onSortEnd}
				onChange={this.onChange}
				onClose={this.onClose}
			/>
		);
	}
}