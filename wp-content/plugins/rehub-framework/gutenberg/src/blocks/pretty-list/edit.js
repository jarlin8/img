/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';
import {RichText} from "@wordpress/block-editor";
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
import AddItemButton from "../../components/add-item-button";

class EditBlock extends Component {
	render() {
		const {className, isSelected, attributes, setAttributes} = this.props;
		const {type, smallGap, prettyHover, darkLink, items} = attributes;

		const mainClasses = classnames([
			className,
			'wpsm_pretty_list',
			`wpsm_${type}list`,
			{
				'small_gap_list': smallGap,
				'wpsm_pretty_hover': prettyHover,
				'darklink': darkLink
			}
		]);

		return (
			<Fragment>
				{isSelected && (
					<Fragment>
						<Inspector {...this.props} />
						<Controls {...this.props} />
					</Fragment>
				)}
				<div className={mainClasses}>
					<ul>
						{items.map(({text}, index) => {
							return (
								<RichText
									key={index}
									placeholder={__('Sample Item', 'rehub-framework')}
									tagName="li"
									value={text}
									onChange={(value) => {
										const itemsClone = cloneDeep(items);
										itemsClone[index].text = value;
										setAttributes({items: itemsClone});
									}}
									keepPlaceholderOnFocus
								/>
							);
						})}
					</ul>
					<AddItemButton handleClick={() => {
						const itemsClone = cloneDeep(items);
						itemsClone.push({text: __('Sample Item', 'rehub-framework')});
						setAttributes({items: itemsClone})
					}}/>
				</div>
			</Fragment>
		);
	}
}

export default compose(
	withFocusOutside
)(EditBlock);