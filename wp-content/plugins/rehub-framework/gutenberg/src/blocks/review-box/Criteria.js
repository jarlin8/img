/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';
import {RichText} from '@wordpress/block-editor';
import {Component} from '@wordpress/element';

/**
 * External dependencies
 */
import {cloneDeep} from "lodash";

/**
 * Internal dependencies
 */
import AddItemButton from "../../components/add-item-button";

export default class Criteria extends Component {
	render() {
		const {setAttributes, criterias, mainColor} = this.props;

		if (criterias.length === 0) {
			return null;
		}

		return (
			<div className='review-criteria'>
				{criterias.map((item, index) => {
					const {title, value} = item;
					const percent = +value * 10;
					const barStyles = {
						width: percent + '%',
						backgroundColor: mainColor
					};

					return (
						<div className="rate-bar clearfix" data-percent={`${percent}%`} key={index}>
							<div className='rate-bar-title'>
								<RichText
									placeholder={__('Criteria name', 'rehub-framework')}
									tagName="span"
									value={title}
									onChange={(value) => {
										const criteriasClone = cloneDeep(criterias);
										criteriasClone[index].title = value;
										setAttributes({
											criterias: criteriasClone
										});
									}}
									keepPlaceholderOnFocus
								/>
							</div>
							<div className="rate-bar-bar" style={barStyles}/>
							<div className="rate-bar-percent">{value}</div>
						</div>
					);
				})}
				<AddItemButton
					handleClick={() => {
						const criteriasClone = cloneDeep(criterias);
						criteriasClone.push({
							title: __('Criteria name', 'rehub-framework'),
							value: 10
						});
						setAttributes({criterias: criteriasClone})
					}}
				/>
			</div>
		);
	}
}