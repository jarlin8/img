/* eslint-disable no-undef */
/* eslint-disable react/no-unknown-property */
import { __ } from '@wordpress/i18n';
import { Component } from '@wordpress/element';
import { RichText, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { Button, Dashicon } from '@wordpress/components';

import { equalColumns } from '../../components/equalizer';
import { renderMediaUploader, getColBadge, getColButton, getColNumbers, getRating }  from './inspector';
import { cloneDeep } from 'lodash';

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
		const {attributes, setAttributes } = this.props;
        const { productBadge, badgeColor, productImage, productTitle, productSubtitle, starRating, bottomText, prosText, consText, specText, listTitle, listItems, titleFont, titleTag } = attributes;
        const { enableBadge, enableBadges, enableImage, enableTitle, enableSubtitle, enableStars, enableNumbers, enableList, enableListTitle, enableButton, enableBottom, enablePros, enableCons, enableSpec, enableCallout } = attributes;
        const { bottomTitle, prosTitle, consTitle, specTitle, responsiveView } = attributes;
        const { context } = this.props;
        setAttributes( { 
            enableBadges: context.enableBadges, 
            enableImage: context.enableImage,
            enableTitle: context.enableTitle,
            enableSubtitle: context.enableSubtitle,
            enableStars: context.enableStars,
            enableNumbers: context.enableNumbers,
            enableButton: context.enableButton,
            enableBottom: context.enableBottom,
            enablePros: context.enablePros,
            enableCons: context.enableCons,
            enableSpec: context.enableSpec,
            enableCallout: context.enableCallout,
            enableList: context.enableList,
            enableListTitle: context.enableListTitle,
            titleTag: context.titleTag,
            titleFont: context.titleFont,
            contentFont: context.contentFont,
            bottomTitle: context.bottomTitle,
            prosTitle: context.prosTitle,
            consTitle: context.consTitle,
            specTitle: context.specTitle,
            responsiveView : context.responsiveView 
        } );
		return (
			<div>
				{[ 
					(enableBadges && getColBadge(attributes, setAttributes)),
                    (enableBadges && getColNumbers(attributes, setAttributes)),
                    getColButton(attributes, setAttributes),
                    getRating(attributes, setAttributes)
				]}
				<div className = "comparison-item">
                    <div className="item-header" data-match-height="itemHeader">
                        { enableNumbers && <div className="item-number" style = {{ color: attributes.numberColor }}>
                            { attributes.numberValue }
                        </div> }
                        {( ( enable ) => {
                            if(enable && enableBadges){
                                return (
                                    <RichText
                                        tagName="div"
                                        className = { `item-badge` }
                                        style = {{ backgroundColor: badgeColor }}
                                        placeholder = { __( 'Badge Title' ) }
                                        onChange= { (value) => { setAttributes( { productBadge: value } ); } }
                                        value={ productBadge }
                                    />
                                );
                            }  
                        }) (enableBadge) }
                        {( ( enable ) => {
                            if(enable){
                                return (
                                    <MediaUploadCheck>   
                                        <MediaUpload 
                                            title = { __( 'Product Image' ) }
                                            allowedTypes = { ['image'] }
                                            value = { productImage }
                                            onSelect = { ( value ) => { setAttributes( { productImage: value } );  } }
                                            render = { ( { open } )  => renderMediaUploader( open, productImage ) }
                                        />
                                    </MediaUploadCheck>
                                );
                            }  
                        }) (enableImage) }
                        {( ( enable ) => {
                            if(enable){
                                return (
                                    <RichText
                                        tagName={titleTag}
                                        style = { {fontSize: titleFont } }
                                        className = { `item-title` }
                                        placeholder = { __( 'Product Title' ) }
                                        onChange= { (value) => { setAttributes( { productTitle: value } );  } }
                                        value={ productTitle }
                                    />
                                );
                            }  
                        }) (enableTitle) } 
                        {( ( enable ) => {
                            if(enable){
                                return (
                                    <RichText
                                        tagName="div"
                                        className = { `item-subtitle` }
                                        placeholder = { __( 'Product Subtitle' ) }
                                        onChange= { (value) => { setAttributes( { productSubtitle: value } );  } }
                                        value={ productSubtitle }
                                    />
                                );
                            }  
                        }) (enableSubtitle) }
                        { enableStars && (
                            <div className = { `item-rating` }>
                                <div className = { `item-stars-rating` }>
                                    {(function (rows, i, j, len) {
                                        while (++i <= len) {
                                            rows.push(
                                                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20" height="20" viewBox="33 -90 360 360">
                                                    <polygon stroke="#F6A123" stroke-width="20" stroke-linecap="square" stroke-linejoin="miter" fill="transparent" points="281.1,129.8 364,55.7 255.5,46.8 214,-59 172.5,46.8 64,55.4 146.8,129.7 121.1,241 212.9,181.1 213.9,181 306.5,241 "></polygon>
                                                    <polygon fill="#F6A123" points="281.1,129.8 364,55.7 255.5,46.8 214,-59 172.5,46.8 64,55.4 146.8,129.7 121.1,241 213.9,181.1 213.9,181 306.5,241 "></polygon>
                                                </svg>
                                            );
                                        }
                                        if(len % 1 !== 0){
                                            rows.push(
                                                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20" height="20" viewBox="33 -90 360 360">
                                                    <polygon stroke="#F6A123" stroke-width="20" stroke-linecap="square" stroke-linejoin="miter" fill="transparent" points="281.1,129.8 364,55.7 255.5,46.8 214,-59 172.5,46.8 64,55.4 146.8,129.7 121.1,241 212.9,181.1 213.9,181 306.5,241 "></polygon>
                                                    <polygon fill="#F6A123" points="281.1,129.8 364,55.7 255.5,46.8 214,-59 172.5,46.8 64,55.4 146.8,129.7 121.1,241 213.9,181.1 213.9,181 306.5,241 "></polygon>
                                                    <polygon fill="#fff" stroke="#F6A123" stroke-width="20" stroke-linecap="square" stroke-linejoin="miter" points="364,55.7 255.5,46.8 214,-59 213.9,181 306.5,241 281.1,129.8 "></polygon>
                                                </svg>
                                            );
                                        }
                                        while (++j <= (5 - len)) {
                                            rows.push(
                                                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20" height="20" viewBox="33 -90 360 360">
                                                    <polygon stroke="#F6A123" stroke-width="20" stroke-linecap="square" stroke-linejoin="miter" fill="transparent" points="281.1,129.8 364,55.7 255.5,46.8 214,-59 172.5,46.8 64,55.4 146.8,129.7 121.1,241 212.9,181.1 213.9,181 306.5,241 "></polygon>
                                                </svg>
                                            );
                                        }
                                        return rows;
                                    })( [], 0, 0, starRating )}
                                </div>
                            </div>
                        ) }
                        {( ( enable ) => {
                            if(enable){
                                return (
                                    <div className="item-list">
                                        { enableListTitle && ( <RichText
                                            tagName="div"
                                            className = { `item-list-title` }
                                            placeholder = { __( 'List Title' ) }
                                            onChange= { (value) => { setAttributes( { listTitle: value } );  } }
                                            value={ listTitle }
                                        />)}
                                        <ul className = { `item-list-links` }>
                                            {
                                                _.map(listItems, (item, index) =>{
                                                    
                                                    return( 
                                                        <li>
                                                            <RichText
                                                                tagName="div"
                                                                className= { `item-key` }
                                                                placeholder = { __( 'Key' ) }
                                                                onChange= { (value) => {
                                                                    const temp = cloneDeep(listItems);
                                                                    temp[index].key = value;
                                                                    setAttributes( { listItems: temp } );
                                                                    
                                                                } }
                                                                value={ item.key }
                                                            />
                                                            <Button
                                                                isTertiary 
                                                                isSmall
                                                                onClick={ () => {
                                                                    const temp = cloneDeep(listItems);
                                                                    temp.splice(index,1);
                                                                    setAttributes( { listItems: temp } );
                                                                    
                                                                } }
                                                            >
                                                                <Dashicon icon="trash" />
                                                            </Button>
                                                        </li>
                                                    );
                                                })
                                            }
                                        </ul>
                                        <Button 
                                            isTertiary 
                                            isSmall
                                            onClick={ () => {
                                                const temp = cloneDeep(listItems);
                                                const template = {
                                                    key: '',
                                                };
                                                setAttributes( { listItems: [...temp, template] } );
                                                
                                            } } >
                                            <Dashicon icon="insert" />
                                        </Button>

                                    </div>
                                );
                            }
                        }) (enableList) }                      
                        {( ( enable ) => {
                            if(enable){
                                const unsafeProps = {
                                    href: attributes.buttonUrl,
                                    //target: attributes.buttonTarget && '_blank',
                                    rel: attributes.buttonTarget && 'noFollow'
                                };
                                return (
                                    <a { ...unsafeProps } style={{backgroundColor: attributes.buttonColor}} className="rehub-item-btn">{ attributes.buttonText }</a>
                                );
                            }  
                        }) (enableButton) }  
                    </div>
                    {( ( enable ) => {
                        if(enable){
                            return (
                                <div className="item-row-description item-row-bottomline" data-match-height="itemBottomline" >
                                    { 'overflow' !== responsiveView && 
                                        <div className="item-row-title">{bottomTitle}</div>
                                    }
                                    <RichText
                                        tagName="div"
                                        placeholder = { __( 'Bottom line text' ) }
                                        onChange= { (value) => { setAttributes( { bottomText: value } );  } }
                                        value={ bottomText }
                                    />
                                </div>
                            );
                        }  
                    }) (enableBottom) }
                    
                    {( ( enable ) => {
                        if(enable){
                            return (
                                <div className="item-row-description item-row-pros" data-match-height="itemPros">
                                    { 'overflow' !== responsiveView && 
                                        <div className="item-row-title">{prosTitle}</div>
                                    }
                                    <RichText
                                        tagName="div"
                                        placeholder = { __( 'Pros text' ) }
                                        onChange= { (value) => { setAttributes( { prosText: value } );  } }
                                        value={ prosText }
                                    />
                                </div>
                            );
                        }  
                    }) (enablePros) }

                    {( ( enable ) => {
                        if(enable){
                            return (
                                <div className="item-row-description item-row-cons" data-match-height="itemCons">
                                    { 'overflow' !== responsiveView && 
                                        <div className="item-row-title">{consTitle}</div>
                                    }
                                    <RichText
                                        tagName="div"
                                        placeholder = { __( 'Cons text' ) }
                                        onChange= { (value) => { setAttributes( { consText: value } );  } }
                                        value={ consText }
                                    />
                                </div>
                            );
                        }  
                    }) (enableCons) }
                    
                    {( ( enable ) => {
                        if(enable){
                            return (
                                <div className="item-row-description item-row-spec" data-match-height="itemSpec">
                                    { 'overflow' !== responsiveView && 
                                        <div className="item-row-title">{specTitle}</div>
                                    }
                                    <RichText
                                        tagName="div"
                                        placeholder = { __( 'Spec text' ) }
                                        onChange= { (value) => { setAttributes( { specText: value } );  } }
                                        value={ specText }
                                    />
                                </div>
                            );
                        }  
                    }) (enableSpec) }

                    {( ( enable ) => {
                        if(enable){
                            return (
                                <div className="item-row-description item-row-callout" data-match-height="itemCallout">
                                    <a href={ attributes.buttonUrl } style={{backgroundColor: attributes.buttonColor}} className="rehub-item-btn">{ attributes.buttonText }</a>
                                </div>
                            );
                        }  
                    }) (enableCallout) }
                </div>
			</div>
		);
	}
}