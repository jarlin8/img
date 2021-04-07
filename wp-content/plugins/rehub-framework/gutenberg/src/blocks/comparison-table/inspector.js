import { __ } from '@wordpress/i18n';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, BaseControl, ToggleControl, FontSizePicker, ButtonGroup, Button, CheckboxControl } from '@wordpress/components';

export const getTableVisibility = ( attributes, setAttributes ) => {
	return (
		<InspectorControls>
			<PanelBody title= { __( 'Visibility' ) } initialOpen= { false }>
                <BaseControl>
                    <ToggleControl
                        label= { __( 'Enable Badges' ) }
                        checked={ attributes.enableBadges  }
                        onChange={ ( state ) => { setAttributes({ enableBadges: state });  } }
                    />
                </BaseControl>
                <BaseControl>
                    <ToggleControl
                        label= { __( 'Enable Image' ) }
                        checked={ attributes.enableImage  }
                        onChange={ ( state ) => { setAttributes({ enableImage: state });  } }
                    />
                </BaseControl>
                <BaseControl>
                    <ToggleControl
                        label= { __( 'Enable Title' ) }
                        checked={ attributes.enableTitle  }
                        onChange={ ( state ) => { setAttributes({ enableTitle: state });  } }
                    />
                </BaseControl>
                <BaseControl>
                    <ToggleControl
                        label= { __( 'Enable Subtitle' ) }
                        checked={ attributes.enableSubtitle }
                        onChange={ ( state ) => { setAttributes({ enableSubtitle: state }); } }
                    />
                </BaseControl>
                <BaseControl>
                    <ToggleControl
                        label= { __( 'Enable Stars' ) }
                        checked={ attributes.enableStars }
                        onChange={ ( state ) => { setAttributes({ enableStars: state }); } }
                    />
                </BaseControl>
                <BaseControl>
                    <ToggleControl
                        label= { __( 'Enable Numbers' ) }
                        checked={ attributes.enableNumbers }
                        onChange={ ( state ) => { setAttributes({ enableNumbers: state }); } }
                    />
                </BaseControl>
                <BaseControl>
                    <ToggleControl
                        label= { __( 'Enable List' ) }
                        checked={ attributes.enableList }
                        onChange={ ( state ) => { setAttributes({ enableList: state }); } }
                    />
                    { attributes.enableList && ( 
                        <CheckboxControl
                            label="List title"
                            checked={ attributes.enableListTitle }
                            onChange={ ( state ) => { setAttributes({ enableListTitle: state }); } }
                        />
                    ) }
                </BaseControl>
                <BaseControl>
                    <ToggleControl
                        label= { __( 'Enable Button' ) }
                        checked={ attributes.enableButton  }
                        onChange={ ( state ) => { setAttributes({ enableButton: state }); } }
                    />
                </BaseControl>
				<BaseControl>
                    <ToggleControl
                        label= { __( 'Enable Bottom Line' ) }
                        checked={ attributes.enableBottom  }
                        onChange={ ( state ) => { setAttributes({ enableBottom: state });  } }
                    />
                </BaseControl>
				<BaseControl>
                    <ToggleControl
                        label= { __( 'Enable Pros' ) }
                        checked={ attributes.enablePros  }
                        onChange={ ( state ) => { setAttributes({ enablePros: state });  } }
                    />
                </BaseControl>
				<BaseControl>
                    <ToggleControl
                        label= { __( 'Enable Cons' ) }
                        checked={ attributes.enableCons  }
                        onChange={ ( state ) => { setAttributes({ enableCons: state });  } }
                    />
                </BaseControl>
				<BaseControl>
                    <ToggleControl
                        label= { __( 'Enable Spec' ) }
                        checked={ attributes.enableSpec  }
                        onChange={ ( state ) => { setAttributes({ enableSpec: state });  } }
                    />
                </BaseControl>
				<BaseControl>
                    <ToggleControl
                        label= { __( 'Enable Callout' ) }
                        checked={ attributes.enableCallout  }
                        onChange={ ( state ) => { setAttributes({ enableCallout: state });  } }
                    />
                </BaseControl>
			</PanelBody>
		</InspectorControls>
	);
};

export const getTableFonts = ( attributes, setAttributes ) => {
    const { titleTag } = attributes;
	return (
		<InspectorControls>
			<PanelBody title= { __( 'Font Settings' ) } initialOpen= { false }>
                <BaseControl>
                    <div><b>Title Tag</b></div>
                    <ButtonGroup>
                        <Button onClick = { () => setAttributes( { titleTag: 'h2' } ) } className = { titleTag === 'h2' ? 'is-primary' : "" } >
                            {__( 'h2' )}
                        </Button>
                        <Button onClick = { () => setAttributes( { titleTag: 'h3' } ) } className = { titleTag === 'h3' ? 'is-primary' : "" }>
                            {__( 'h3' )}
                        </Button>
                        <Button onClick = { () => setAttributes( { titleTag: 'h4' } ) } className = { titleTag === 'h4' ? 'is-primary' : "" }>
                            {__( 'h4' )}
                        </Button>
                        <Button onClick = { () => setAttributes( { titleTag: 'h5' } ) } className = { titleTag === 'h5' ? 'is-primary' : "" }>
                            {__( 'h5' )}
                        </Button>
                        <Button onClick = { () => setAttributes( { titleTag: 'h6' } ) } className = { titleTag === 'h6' ? 'is-primary' : "" }>
                            {__( 'h6' )}
                        </Button>
                        <Button onClick = { () => setAttributes( { titleTag: 'div' } ) } className = { titleTag === 'div' ? 'is-primary' : "" }>
                            {__( 'div' )}
                        </Button>
                    </ButtonGroup>
                </BaseControl>
                <BaseControl>
                    <div><b>Title font size</b></div>
                    <FontSizePicker
                        value={ attributes.titleFont }
                        fallbackFontSize = { 18 }
                        onChange={(value) => {
                            if(isNaN(value)){
                                value = 18;
                            }
                            setAttributes({ titleFont: value }); 
                        } }
                        fontSizes={ [
                        {
                            name: __( 'Small' ),
                            slug: 'small',
                            size: 12,
                        },
                        {
                            name: __( 'Regular' ),
                            slug: 'regular',
                            size: 16,
                        },
                        {
                            name: __( 'Large' ),
                            slug: 'large',
                            size: 20,
                        },
                        {
                            name: __( 'Larger' ),
                            slug: 'larger',
                            size: 24,
                        },
                        ] }
                    />
                </BaseControl>
                <BaseControl>
                    <div><b>Content font size</b></div>
                    <FontSizePicker
                        value={ attributes.contentFont }
                        fallbackFontSize = { 14 }
                        onChange={(value) => {
                            if(isNaN(value)){
                                value = 14;
                            }
                            setAttributes({ contentFont: value });  
                        } }
                        fontSizes={ [
                        {
                            name: __( 'Small' ),
                            slug: 'small',
                            size: 12,
                        },
                        {
                            name: __( 'Regular' ),
                            slug: 'regular',
                            size: 16,
                        },
                        {
                            name: __( 'Large' ),
                            slug: 'large',
                            size: 20,
                        },
                        {
                            name: __( 'Larger' ),
                            slug: 'larger',
                            size: 24,
                        },
                        ] }
                    />
                </BaseControl>
			</PanelBody>
		</InspectorControls>
	);
};

export const getTableResponsive = ( attributes, setAttributes ) => {
    const { responsiveView } = attributes;
	return (
		<InspectorControls>
			<PanelBody title= { __( 'Responsive' ) } initialOpen= { false }>
                <BaseControl>
                    <div style={{ marginBottom: 10 }}><b>Layout type</b></div>
                    <ButtonGroup>
                        <Button onClick = { () => setAttributes( { responsiveView: 'overflow' } ) } className = { responsiveView === 'overflow' ? 'is-primary' : "" } >
                            {__( 'Overflow' )}
                        </Button>
                        <Button onClick = { () => setAttributes( { responsiveView: 'stacked' } ) } className = { responsiveView === 'stacked' ? 'is-primary' : "" }>
                            {__( 'Stacked' )}
                        </Button>
                        <Button onClick = { () => setAttributes( { responsiveView: 'slide' } ) } className = { responsiveView === 'slide' ? 'is-primary' : "" }>
                            {__( 'Slide' )}
                        </Button>
                    </ButtonGroup>
                </BaseControl>
			</PanelBody>
		</InspectorControls>
	);
};

