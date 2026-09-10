/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';
import {RichText} from '@wordpress/block-editor';
import {Component} from '@wordpress/element';

/**
 * External dependencies
 */
import {cloneDeep} from 'lodash';
import classnames from "classnames";

/**
 * Internal dependencies
 */
import AddItemButton from "../add-item-button";

export default class ConsPros extends Component {
	render() {
		const {setAttributes, prosTitle, positives, consTitle, negatives, className = ''} = this.props;

		const mainClasses = classnames(['c-cons-pros', className]);

		const columnClasses = classnames([
			'c-cons-pros__col',
			{'c-cons-pros__col--full': positives.length === 0 || negatives.length === 0}
		]);

		const PositivesBox = (
			<div className={columnClasses}>
				<RichText
					placeholder={__('Positive', 'rehub-framework')}
					tagName="div"
					className='c-cons-pros__title c-cons-pros__title--pros'
					value={prosTitle}
					onChange={(value) => {
						setAttributes({
							prosTitle: value
						});
					}}
					keepPlaceholderOnFocus
				/>
				<ul className='c-cons-pros__list c-cons-pros__list--pros'>
					{positives.map((item, index) => {
						const {title} = item;

						return (
							<li className='c-cons-pros__item' key={index}>
								<RichText
									placeholder={__('Positive', 'rehub-framework')}
									tagName="span"
									value={title}
									onChange={(value) => {
										const positivesClone = cloneDeep(positives);
										positivesClone[index].title = value;
										setAttributes({
											positives: positivesClone
										});
									}}
									keepPlaceholderOnFocus
								/>
							</li>
						);
					})}
					<AddItemButton handleClick={() => {
						const positivesClone = cloneDeep(positives);
						positivesClone.push({
							title: 'Positive'
						});
						setAttributes({positives: positivesClone})
					}}
					/>
				</ul>
			</div>
		);

		const NegativesBox = (
			<div className={columnClasses}>
				<RichText
					placeholder={__('Negatives', 'rehub-framework')}
					tagName="div"
					className='c-cons-pros__title c-cons-pros__title--cons'
					value={consTitle}
					onChange={(value) => {
						setAttributes({
							consTitle: value
						});
					}}
					keepPlaceholderOnFocus
				/>
				<ul className='c-cons-pros__list c-cons-pros__list--cons'>
					{negatives.map((item, index) => {
						const {title} = item;

						return (
							<li className='c-cons-pros__item' key={index}>
								<RichText
									placeholder={__('Negative', 'rehub-framework')}
									tagName="span"
									value={title}
									onChange={(value) => {
										const negativesClone = cloneDeep(negatives);
										negativesClone[index].title = value;
										setAttributes({
											negatives: negativesClone
										});
									}}
									keepPlaceholderOnFocus
								/>
							</li>
						);
					})}
					<AddItemButton handleClick={() => {
						const negativesClone = cloneDeep(negatives);
						negativesClone.push({
							title: 'Negative'
						});
						setAttributes({negatives: negativesClone})
					}}
					/>
				</ul>
			</div>
		);

		if (positives.length === 0 && negatives.length === 0) {
			return null;
		}

		return (
			<div className={mainClasses}>
				{positives.length > 0 && PositivesBox}
				{negatives.length > 0 && NegativesBox}
			</div>
		);
	}
}