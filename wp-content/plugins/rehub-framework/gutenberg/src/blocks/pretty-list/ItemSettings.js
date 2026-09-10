/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';
import {Component} from '@wordpress/element'
import {Card, CardBody, Button, BaseControl, TextareaControl} from '@wordpress/components';

/**
 * External dependencies
 */
import {cloneDeep} from "lodash";
import {SortableContainer, SortableElement} from "../../react-sortable-hoc.esm";

const SortableItem = SortableElement((props) => {
	const {items, sortIndex, setAttributes, onClose} = props;
	const {text} = items[sortIndex];
	const cloneItems = cloneDeep(items);

	return (
		<li className='components-card-list__item'>
			<Card>
				<CardBody>
					<TextareaControl
						label={__('Text', 'rehub-framework')}
						value={text}
						onChange={(value) => {
							cloneItems[sortIndex].text = value;
							setAttributes({items: cloneItems});
						}}
					/>
				</CardBody>
				<BaseControl className='text-center'>
					<Button isSecondary onClick={() => onClose(sortIndex)}>
						{__('Remove item', 'rehub-framework')}
					</Button>
				</BaseControl>
			</Card>
		</li>
	);
});

const SortableList = SortableContainer((props) => {
	const {items, setAttributes, onClose} = props;

	return (
		<ul className='components-card-list'>
			{items.map((value, index) => {
				return (
					<SortableItem
						key={`item-${index}`}
						index={index}
						sortIndex={index}
						items={items}
						setAttributes={setAttributes}
						onClose={onClose}
					/>
				);
			})}
		</ul>
	);
});

export default class ItemSettings extends Component {
	constructor(props) {
		super(props);
		this.onSortEnd = this.onSortEnd.bind(this);
		this.onClose = this.onClose.bind(this);
	}

	onSortEnd({oldIndex, newIndex}) {
		const {items} = this.props;
		const cloneItems = cloneDeep(items);

		cloneItems.splice(newIndex, 0, cloneItems.splice(oldIndex, 1)[0]);
		this.props.setAttributes({[this.props.propName]: cloneItems});
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
				onSortEnd={this.onSortEnd}
				onClose={this.onClose}
				setAttributes={this.props.setAttributes}
			/>
		);
	}
}