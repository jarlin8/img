/* eslint-disable no-undef */
/* eslint-disable react/no-unknown-property */
import { RichText } from '@wordpress/block-editor';

export default function save ( props ) {
    const { attributes } = props;
    const { productBadge, badgeColor, productImage, productTitle, productSubtitle, starRating, bottomText, prosText, consText, specText, listTitle, listItems, titleTag, titleFont } = attributes;
    const { enableBadge, enableBadges, enableImage, enableTitle, enableSubtitle, enableStars, enableNumbers, enableButton, enableList, enableListTitle } = attributes;
    const { enableBottom, enablePros, enableCons, enableSpec, enableCallout } = attributes;
    const { bottomTitle, prosTitle, consTitle, specTitle, responsiveView } = attributes;
    return (
            <div className = "comparison-item">
                <div className="item-header" data-match-height="itemHeader">
                    { enableNumbers && <div className="item-number" style = {{ color: attributes.numberColor }}>
                        { attributes.numberValue }
                    </div> }
                    {( ( enable ) => {
                        if(enable){
                            return (
                                <div className="item-badge" style = {{ backgroundColor: badgeColor }}>{ productBadge }</div>
                            );
                        }  
                    }) (enableBadge && enableBadges) }
                    {( ( enable ) => {
                        if(enable && productImage.url != null){
                            return (
                                <div className={ "product-image" }>
                                    <div className={ "image" }>
                                        <img src={ productImage.url } />
                                    </div>
                                </div>
                            );
                        }  
                    }) (enableImage) }
                    {( ( enable ) => {
                        if(enable && productTitle != ''){
                            return (
                                <RichText.Content 
                                    tagName={titleTag}
                                    className = { `item-title` }
                                    style = { {fontSize: titleFont } }
                                    value={ productTitle }
                                />
                            );
                        }  
                    }) (enableTitle) } 
                    {( ( enable ) => {
                        if(enable && productSubtitle != ''){
                            return (
                                <div class="item-subtitle">{ productSubtitle }</div>
                            );
                        }  
                    }) (enableSubtitle) }
                    { enableStars && (
                        <div className = { `item-rating` }>
                            <div className = { `item-stars-rating` }>
                                {(function (rows, i, j, len) {
                                    while (++i <= len) {
                                        rows.push(
                                            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="33 -90 360 360">
                                                <polygon stroke="#F6A123" stroke-width="20" stroke-linecap="square" stroke-linejoin="miter" fill="transparent" points="281.1,129.8 364,55.7 255.5,46.8 214,-59 172.5,46.8 64,55.4 146.8,129.7 121.1,241 212.9,181.1 213.9,181 306.5,241 "></polygon>
                                                <polygon fill="#F6A123" points="281.1,129.8 364,55.7 255.5,46.8 214,-59 172.5,46.8 64,55.4 146.8,129.7 121.1,241 213.9,181.1 213.9,181 306.5,241 "></polygon>
                                            </svg>
                                        );
                                    }
                                    if(len % 1 !== 0){
                                        rows.push(
                                            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="33 -90 360 360">
                                                <polygon stroke="#F6A123" stroke-width="20" stroke-linecap="square" stroke-linejoin="miter" fill="transparent" points="281.1,129.8 364,55.7 255.5,46.8 214,-59 172.5,46.8 64,55.4 146.8,129.7 121.1,241 212.9,181.1 213.9,181 306.5,241 "></polygon>
                                                <polygon fill="#F6A123" points="281.1,129.8 364,55.7 255.5,46.8 214,-59 172.5,46.8 64,55.4 146.8,129.7 121.1,241 213.9,181.1 213.9,181 306.5,241 "></polygon>
                                                <polygon fill="#fff" stroke="#F6A123" stroke-width="10" stroke-linecap="square" stroke-linejoin="miter" points="364,55.7 255.5,46.8 214,-59 213.9,181 306.5,241 281.1,129.8 "></polygon>
                                            </svg>
                                        );
                                    }
                                    while (++j <= (5 - len)) {
                                        rows.push(
                                            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="33 -90 360 360">
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
                        if(enable && listItems.length > 0){
                            return (
                                <div className="item-list">
                                    { enableListTitle && ( 
                                        <div className = { `item-list-title` }>{ listTitle }</div>
                                    ) }

                                    <ul className = { `item-list-links` }>
                                        {
                                            _.map(listItems, (item) =>{
                                                return( 
                                                    <li>
                                                        <RichText.Content tagName="div" value={ item.key } />
                                                    </li>
                                                );
                                            })
                                        }
                                    </ul>
                                </div>
                            );
                        }  
                    }) (enableList) }                
                    {( ( enable ) => {
                        if(enable){
                            let relAttr = attributes.buttonTarget ? 'noopener' : '';
                            relAttr += attributes.buttonRel ? ' nofollow' : '';
                            const unsafeProps = {
                                href: attributes.buttonUrl,
                                rel: relAttr,
                                target: attributes.buttonTarget && '_blank',
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
                            <div className="item-row-description item-row-bottomline" data-match-height="itemBottomline">
                                { 'overflow' !== responsiveView && 
                                    <div className="item-row-title">{bottomTitle}</div>
                                }
                                <RichText.Content tagName="div" value={ bottomText } />
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
                                <RichText.Content tagName="div" value={ prosText } />
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
                                <RichText.Content tagName="div" value={ consText } />
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
                                <RichText.Content tagName="div" value={ specText } />
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
    );
}
