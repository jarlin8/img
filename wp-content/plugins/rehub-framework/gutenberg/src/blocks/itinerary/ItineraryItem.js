/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';
import {RichText} from "@wordpress/block-editor";

/**
 * External dependencies
 */
import {cloneDeep} from "lodash";

const ItineraryItem = (props) => {
	const {items, index, setAttributes} = props;
	const {icon, color, content} = items[index];
	const iconStyles = {backgroundColor: color};

	return (
		<div className="wpsm-itinerary-item">
			<div className="wpsm-itinerary-icon">
				<span style={iconStyles}>
					<i className={icon}/>
				</span>
			</div>
			<div className="wpsm-itinerary-content">
				<RichText
					placeholder={__('Box Content', 'rehub-framework')}
					tagName="div"
					value={content}
					onChange={(value) => {
						const itemsClone = cloneDeep(items);
						itemsClone[index].content = value;
						setAttributes({items: itemsClone});
					}}
					keepPlaceholderOnFocus
				/>
			</div>
		</div>
	);
};

export default ItineraryItem;