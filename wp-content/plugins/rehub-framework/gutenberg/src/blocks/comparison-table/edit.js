/* eslint-disable no-undef */
import { __ } from '@wordpress/i18n';
import { Component } from '@wordpress/element';
import { InnerBlocks, RichText } from '@wordpress/block-editor';
import { equalColumns } from '../../components/equalizer';
import { getTableVisibility, getTableFonts, getTableResponsive }  from './inspector';

export default class EditClass extends Component {
	constructor(props) {
		super(props);
	}
	componentDidMount() {
		equalColumns();
	}
	componentDidUpdate(prevProps) {
		const hasChanges = _.some(this.props.attributes, (el, index)=>{
			return this.props.attributes[index] !== prevProps.attributes[index]
		});
		if (hasChanges) {
			equalColumns();
		}
	}
	render(){
		const { attributes, setAttributes } = this.props;
		const { bottomTitle, prosTitle, consTitle, specTitle, contentFont } = attributes;
		const { enableBottom, enablePros, enableCons, enableSpec, enableCallout } = attributes;
		return (
			<div>
				{[ 
					getTableVisibility( attributes, setAttributes ),
					getTableFonts( attributes, setAttributes ),
					getTableResponsive( attributes, setAttributes ) 
				]}
				<div className={`comparison-table ${ attributes.responsiveView } ${ attributes.enableBadges ? 'has-badges' : '' }`}>
					<div width="100" className ="comparison-item comparison-header">
						<div className="item-header" data-match-height="itemHeader"></div>
						{( ( enable ) => {
							if(enable){
								return (
									<div className="item-row-description item-row-bottomline" data-match-height="itemBottomline">
										<RichText
											tagName="div"
											placeholder = { __( 'Bottom Line' ) }
											onChange= { (value) => { setAttributes( { bottomTitle: value } ); } }
											value={ bottomTitle }
										/>
									</div>
								);
							}  
						}) (enableBottom) }
						{( ( enable ) => {
							if(enable){
								return (
									<div className="item-row-description item-row-pros" data-match-height="itemPros">
										<RichText
											tagName="div"
											placeholder = { __( 'Pros' ) }
											onChange= { (value) => { setAttributes( { prosTitle: value } ); } }
											value={ prosTitle }
										/>
									</div>
								);
							}  
						}) (enablePros) }
						{( ( enable ) => {
							if(enable){
								return (
									<div className="item-row-description item-row-cons" data-match-height="itemCons">
										<RichText
											tagName="div"
											placeholder = { __( 'Cons' ) }
											onChange= { (value) => { setAttributes( { consTitle: value } ); } }
											value={ consTitle }
										/>
									</div>
								);
							}  
						}) (enableCons) }
						{( ( enable ) => {
							if(enable){
								return (
									<div className="item-row-description item-row-spec" data-match-height="itemSpec">
										<RichText
											tagName="div"
											placeholder = { __( 'Spec' ) }
											onChange= { (value) => { setAttributes( { specTitle: value } ); } }
											value={ specTitle }
										/>
									</div>
								);
							}  
						}) (enableSpec) }
						{( ( enable ) => {
							if(enable){
								return (
									<div className="item-row-description item-row-callout" data-match-height="itemCallout">&nbsp;</div>
								);
							}  
						}) (enableCallout) }
					</div>
					<div className="comparison-wrapper" style = { {fontSize: contentFont } }>
						<InnerBlocks 
							allowedBlocks={ ['rehub/comparison-item'] } 
							template = { [ ['rehub/comparison-item'], ['rehub/comparison-item'], ['rehub/comparison-item'] ] }
							/>
					</div>
				</div>
			</div>
		);
	}
}