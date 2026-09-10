/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';
import {Component, Fragment} from '@wordpress/element'
import {PanelBody, TextControl, BaseControl, Button} from '@wordpress/components';

/**
 * Internal dependencies
 */
import CardList from '../card-list';

/**
 * External dependencies
 */
import {cloneDeep} from 'lodash';

export default class ConsProsInspector extends Component {
	render() {
		const {setAttributes, prosTitle, positives, consTitle, negatives} = this.props;

		return (
			<Fragment>
				<PanelBody title={__('Positives', 'rehub-framework')} initialOpen={false}>
					<TextControl
						label={__('Pros Title', 'rehub-framework')}
						value={prosTitle}
						placeholder={__('Positive', 'rehub-framework')}
						onChange={(value) => {
							setAttributes({prosTitle: value})
						}}
					/>
					<BaseControl
						className='rri-advanced-range-control'
						label={__('Positives', 'rehub-framework')}>
						<CardList
							items={positives}
							propName='positives'
							setAttributes={setAttributes}
							titlePlaceholder={__('Positive', 'rehub-framework')}
							includeValueField={false}
						/>
					</BaseControl>
					<BaseControl className='rri-advanced-range-control text-center'>
						<Button isSecondary onClick={() => {
							const positivesClone = cloneDeep(positives);
							positivesClone.push({
								title: 'Positive'
							});
							setAttributes({positives: positivesClone})
						}}>
							{__('Add Item', 'rehub-framework')}
						</Button>
					</BaseControl>
				</PanelBody>
				<PanelBody title={__('Negatives', 'rehub-framework')} initialOpen={false}>
					<TextControl
						label={__('Cons Title', 'rehub-framework')}
						value={consTitle}
						placeholder={__('Negatives', 'rehub-framework')}
						onChange={(value) => {
							setAttributes({consTitle: value})
						}}
					/>
					<BaseControl
						className='rri-advanced-range-control'
						label={__('Negatives', 'rehub-framework')}>
						<CardList
							items={negatives}
							propName='negatives'
							setAttributes={setAttributes}
							titlePlaceholder={__('Negative', 'rehub-framework')}
							includeValueField={false}
						/>
					</BaseControl>
					<BaseControl className='rri-advanced-range-control text-center'>
						<Button isSecondary onClick={() => {
							const negativesClone = cloneDeep(negatives);
							negativesClone.push({
								title: 'Negative'
							});
							setAttributes({negatives: negativesClone})
						}}>
							{__('Add Item', 'rehub-framework')}
						</Button>
					</BaseControl>
				</PanelBody>
			</Fragment>
		);
	}
}