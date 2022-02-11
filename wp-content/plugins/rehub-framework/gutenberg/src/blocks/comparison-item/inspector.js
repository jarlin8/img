import { __ } from '@wordpress/i18n';
import { InspectorControls, URLInput } from '@wordpress/block-editor'
import { PanelBody ,BaseControl, Button, ToggleControl, TextControl, ColorPalette, RangeControl } from '@wordpress/components';

var _colorPalette = [
    {
        name: __( 'cyan' ),
        slug: 'cyan',
        color: '#1797d4'
    },
    {
        name: __( 'blue' ),
        slug: 'blue',
        color: '#655ec7'
    },
    {
        name: __( 'cobalt' ),
        slug: 'cobalt',
        color: '#3c2574'
    },
    {
        name: __( 'purple' ),
        slug: 'purple',
        color: '#7635f3'
    },
    {
        name: __( 'pink' ),
        slug: 'pink',
        color: '#c62891'
    },
    {
        name: __( 'crimson' ),
        slug: 'crimson',
        color: '#ff0041'
    },
    {
        name: __( 'orange' ),
        slug: 'orange',
        color: '#ff5900'
    },
    {
        name: __( 'amber' ),
        slug: 'amber',
        color: '#f57d00'
    },
    {
        name: __( 'yellow' ),
        slug: 'yellow',
        color: '#eae616'
    },
    {
        name: __( 'goldenrod' ),
        slug: 'goldenrod',
        color: '#c3ae00'
    },
    {
        name: __( 'olive' ),
        slug: 'olive',
        color: '#638b2d'
    },
    {
        name: __( 'green' ),
        slug: 'green',
        color: '#037833'
    }
];

export const renderMediaUploader = ( open, productImage ) => {
    if(productImage.url != null){
        return (
            <div className={ "product-image" }>
                <div className={ "image" } onClick={ open }>
                    <img src={ productImage.url } />
                </div>
            </div>
        );
    }else{
        return (
            <div className={ "image-preview" }>
                <div className={ "image" }>
                    <Button
                        onClick={ open }
                        className="editor-post-featured-image__toggle"
                    >
                        { __('Add product image') }
                    </Button>
                </div>
            </div>
        );
    }
}

export const getColBadge = ( attributes, setAttributes ) => {
	return (
		<InspectorControls>
			<PanelBody title= { __( 'Badge' ) } initialOpen= { false }>
				<BaseControl>
                    <ToggleControl
                        label= { __( 'Show Badge' ) }
                        checked={ attributes.enableBadge  }
                        onChange={ ( state ) => setAttributes({ enableBadge: state }) }
                    />
                </BaseControl>
                <BaseControl>
                    <BaseControl.VisualLabel>
                        <p>Badge Background Color</p>
                    </BaseControl.VisualLabel>
                    <ColorPalette
                        colors = { _colorPalette }
                        disableCustomColors={ false }
                        value={ attributes.badgeColor }
                        onChange={ ( color ) => {
                            setAttributes( { badgeColor: color  } );
                        } }
                    />
                </BaseControl>
			</PanelBody>
		</InspectorControls>
	);
};

export const getColNumbers = ( attributes, setAttributes ) => {
	return (
		<InspectorControls>
			<PanelBody title= { __( 'Numbers' ) } initialOpen= { false }>
                <BaseControl label="Number Value" className={"button-control"}>
                    <TextControl
                        type = 'number'
                        value={attributes.numberValue}
                        onChange = { ( value ) => setAttributes({ numberValue: value }) }/>
                </BaseControl>
                <BaseControl>
                    <BaseControl.VisualLabel>
                        <p>Number Color</p>
                    </BaseControl.VisualLabel>
                    <ColorPalette
                        colors = { _colorPalette }
                        disableCustomColors={ false }
                        value={ attributes.numberColor }
                        onChange={ ( color ) => {
                            setAttributes( { numberColor: color  } );
                        } }
                    />
                </BaseControl>
			</PanelBody>
		</InspectorControls>
	);
};

export const getColButton = ( attributes, setAttributes ) => {
	return (
		<InspectorControls>
			<PanelBody title= { __( 'Button' ) } initialOpen= { false }>
				<BaseControl label="Url of button" className={"button-control"}>
                    <URLInput 
                        value={attributes.buttonUrl}
                        onChange = { ( url ) => setAttributes({ buttonUrl: url }) }/>
                </BaseControl>
                <BaseControl label="Title of button" className={"button-control"}>
                    <TextControl 
                        value={attributes.buttonText}
                        onChange = { ( url ) => setAttributes({ buttonText: url }) }/>
                </BaseControl>
                <BaseControl>
                    <ToggleControl
                        label= { __( 'No follow attribute' ) }
                        checked={ attributes.buttonRel  }
                        onChange={ ( state ) => setAttributes({ buttonRel: state }) }
                    />
                </BaseControl>
                <BaseControl>
                    <ToggleControl
                        label= { __( 'Open in new tab' ) }
                        checked={ attributes.buttonTarget  }
                        onChange={ ( state ) => setAttributes({ buttonTarget: state }) }
                    />
                </BaseControl>
                <BaseControl>
                    <BaseControl.VisualLabel>
                        <p>Button Background Color</p>
                    </BaseControl.VisualLabel>
                    <ColorPalette
                        colors = { _colorPalette }
                        disableCustomColors={ false }
                        value={ attributes.buttonColor }
                        onChange={ ( color ) => {
                            setAttributes( { buttonColor: color } );
                        } }
                    />
                </BaseControl>
			</PanelBody>
		</InspectorControls>
	);
};
export const getRating = ( attributes, setAttributes ) => {
    return (
		<InspectorControls>
			<PanelBody title= { __( 'Rating' ) } initialOpen= { false }>
                <BaseControl>
                    <RangeControl
                        label= { __( 'Rating' ) }
                        value={ attributes.starRating }
                        min = { 1 }
                        max = { 5 }
                        step={ 0.5 }
                        marks = {
                            { value: 1, label: '1' },
                            { value: 2, label: '2' },
                            { value: 3, label: '3' },
                            { value: 4, label: '4' },
                            { value: 5, label: '5' }
                        }
                        onChange={ ( value ) => setAttributes({ starRating: value }) }
                    />
                </BaseControl>
			</PanelBody>
		</InspectorControls>
	);
}

