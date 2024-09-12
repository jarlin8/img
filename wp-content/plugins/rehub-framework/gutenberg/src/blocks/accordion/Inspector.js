/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';
import {Component} from '@wordpress/element';
import {InspectorControls} from '@wordpress/block-editor';
import {PanelBody, BaseControl, Button} from '@wordpress/components';

/**
 * Internal dependencies
 */
import CardList from '../../components/card-list';

/**
 * External dependencies
 */
import {cloneDeep} from 'lodash';

/**
 * Create an Inspector Controls wrapper Component
 */
export default class Inspector extends Component {
	render() {
		const {setAttributes, attributes} = this.props;
		const {tabs} = attributes;
		return (
			<InspectorControls>
				<PanelBody title={__('Tabs', 'rehub-framework')} initialOpen>
					<CardList
						items={tabs}
						propName='tabs'
						setAttributes={setAttributes}
						titlePlaceholder={__('Sample title', 'rehub-framework')}
						contentPlaceholder={__('Sample content', 'rehub-framework')}
						includeContentField
					/>
					<BaseControl className='rri-advanced-range-control text-center'>
						<Button isSecondary onClick={() => {
							const tabsClone = cloneDeep(tabs);
							tabsClone.push({
								title: __('Sample title', 'rehub-framework'),
								content: __('Sample content', 'rehub-framework')
							});

							setAttributes({
								tabs: tabsClone
							});
						}}>
							{__('Add Accordition', 'rehub-framework')}
						</Button>
					</BaseControl>
				</PanelBody>
			</InspectorControls>
		);
	}
}