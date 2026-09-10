/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';
import {Component} from '@wordpress/element'
import {InspectorControls} from '@wordpress/block-editor';
import {PanelBody, TextControl, TextareaControl, ColorPicker, BaseControl, Button, Notice} from '@wordpress/components';

/**
 * Internal dependencies
 */
import AdvancedRangeControl from '../../components/advanced-range-control';
import ConsProsInspector from '../../components/cons-pros/inspector';
import CardList from '../../components/card-list';

/**
 * External dependencies
 */
import {cloneDeep} from 'lodash';

/**
 * Create an Inspector Controls wrapper Component
 */

export default class Inspector extends Component {
	constructor(props) {
		super(props);
		this.state = {
			parseError: '',
			parseSuccess: '',
			timer: ''
		};
	}
	updateData ( ) {
		let self = this;
		const {attributes} = this.props;
		const {postId} = attributes;
		if(!postId || 0 === postId.length){
			self.setState({
				parseError: "Select post before save"
			})
		} else {			
			self.setState({
				parseError: ""
			});
			var request = wp.ajax.post( 'update_review_meta', {
				attr: attributes
			});
			request.done( function( ) {
				self.setState({
					parseError: "",
					parseSuccess: 'Post meta has been updated'
				});
			});
			request.fail( function( response ) {
				self.setState({
					parseError:  response.message,
					parseSuccess: ''
				});
			});
		}	
	}
	componentDidUpdate() {
		let self = this;
		if(self.state.parseError.length > 0){
			clearTimeout( self.state.timer ); 
			self.state.timer = setTimeout(()=>{
				self.setState({
					parseError: ""
				})
			}, 5000);
		}
		if(self.state.parseSuccess.length > 0){
			clearTimeout( self.state.timer ); 
			self.state.timer = setTimeout(()=>{
				self.setState({
					parseSuccess: ""
				})
			}, 5000);
		}
	}
	render() {
		let self = this;
		const {attributes, setAttributes} = this.props;
		const {title, description, scoreManual, mainColor, criterias, prosTitle, positives, consTitle, negatives} = attributes;
		const { parseError, parseSuccess } = this.state;
		return (
			<InspectorControls>
				<PanelBody title={__('General', 'rehub-framework')} initialOpen={true}>
					<TextControl
						label={__('Title', 'rehub-framework')}
						value={title}
						placeholder={__('Awesome', 'rehub-framework')}
						onChange={(value) => {
							setAttributes({title: value})
						}}
					/>
					<TextareaControl
						label={__('Description', 'rehub-framework')}
						placeholder={__('Place here Description for your reviewbox', 'rehub-framework')}
						value={description}
						onChange={(value) => {
							setAttributes({description: value})
						}}
					/>
					<AdvancedRangeControl
						label={__('Score Value', 'rehub-framework')}
						value={scoreManual}
						min="0"
						max="10"
						step={0.5}
						onChange={(value) => {
							setAttributes({scoreManual: value})
						}}
					/>
					<BaseControl
						className='rri-advanced-range-control'
						label={__('Set background color or leave blank', 'rehub-framework')}>
						<ColorPicker
							color={mainColor}
							onChangeComplete={(value) => {
								setAttributes({mainColor: value.hex})
							}}
							disableAlpha
						/>
					</BaseControl>
				</PanelBody>
				<PanelBody title={__('Criterias', 'rehub-framework')} initialOpen={false}>
					<CardList
						items={criterias}
						propName='criterias'
						setAttributes={setAttributes}
						titlePlaceholder={__('Criteria name', 'rehub-framework')}
						includeValueField
					/>
					<BaseControl className='rri-advanced-range-control text-center'>
						<Button isSecondary onClick={() => {
							const criteriasClone = cloneDeep(criterias);
							criteriasClone.push({
								title: __('Criteria name', 'rehub-framework'),
								value: 10
							});
							setAttributes({criterias: criteriasClone})
						}}>
							{__('Add Item', 'rehub-framework')}
						</Button>
					</BaseControl>
				</PanelBody>
				<ConsProsInspector
					setAttributes={setAttributes}
					prosTitle={prosTitle}
					positives={positives}
					consTitle={consTitle}
					negatives={negatives}
				/>
				<PanelBody title={__('Save to post meta', 'rehub-framework')} initialOpen={false}>
					<BaseControl>
						<Button
							isPrimary
							onClick = {()=>{
								this.updateData();
							}}
							>
							{__('Save data', 'rehub-framework') }
						</Button>
						<BaseControl className='rehub-notice-box'>
							{parseError && (
								<Notice status="error" onRemove={() => self.setState({parseError: ''})}>
									{parseError}
								</Notice>
							)}
							{(parseSuccess && !parseError) && (
								<Notice status="success" onRemove={() => self.setState({parseSuccess: ''})}>
									{parseSuccess}
								</Notice>
							)}
						</BaseControl>
					</BaseControl>
				</PanelBody>
				
			</InspectorControls>
		);
	}
}