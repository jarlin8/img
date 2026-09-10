/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';
import {Component, Fragment} from '@wordpress/element';
import {compose} from "@wordpress/compose";
import {withFocusOutside} from "@wordpress/components";

/**
 * External dependencies
 */
import classnames from "classnames";
import {cloneDeep} from "lodash";

/**
 * Internal dependencies
 */
import Inspector from "./Inspector";
import Controls from './Controls';
import ItineraryItem from "./ItineraryItem";
import AddItemButton from "../../components/add-item-button";

class EditBlock extends Component {
	render() {
		const {className, isSelected, attributes, setAttributes} = this.props;
		const mainClasses = classnames([className, 'wpsm-itinerary']);
		const {items} = attributes;

		return (
			<Fragment>
				{isSelected && (
					<Fragment>
						<Inspector {...this.props} />
						<Controls {...this.props} />
					</Fragment>
				)}
				<div className={mainClasses}>
					{items.map((item, index) => {
						return (
							<ItineraryItem
								setAttributes={setAttributes}
								items={items}
								index={index}
								key={index}
							/>
						)
					})}
				</div>
				<AddItemButton handleClick={() => {
					const cloneItems = cloneDeep(items);
					cloneItems.push({
						icon: 'rhicon rhi-circle-solid',
						color: '#409cd1',
						content: __('Box Content', 'rehub-framework')
					});
					setAttributes({items: cloneItems})
				}}/>
			</Fragment>
		);
	}
}

export default compose(
	withFocusOutside
)(EditBlock);