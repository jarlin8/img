// PART 1: Import dependencies
import './style.scss';
import './editor.scss';

const { __ } = wp.i18n;
const el = wp.element.createElement
const registerBlockType = wp.blocks.registerBlockType
const BlockAlignmentToolbar = wp.editor.BlockAlignmentToolbar
const InspectorControls = wp.editor.InspectorControls
const Button = wp.components.Button
const TextControl = wp.components.TextControl
const ToggleControl = wp.components.ToggleControl

const withState = wp.compose.withState

const {
	Panel,
	PanelHeader,
    PanelBody,
    PanelRow
} = wp.components;

/**
 * Example of a custom SVG path taken from fontastic
*/
const iconEl = el('svg', { width: 24, height: 24 },
  el('path', { d: "M14 9l-2.519 4-2.481-1.96-5 6.96h16l-6-9zm8-5v16h-20v-16h20zm2-2h-24v20h24v-20zm-20 6c0-1.104.896-2 2-2s2 .896 2 2c0 1.105-.896 2-2 2s-2-.895-2-2z" } )
);



registerBlockType('amalinkspro-legacy/insert-imagelink-html', {   

	title: __( 'Amazon Affiliate Image Link' ), // Block title.
	icon: iconEl, // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
	category: 'amalinkspro-legacy', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
	keywords: [
		__( 'Amazon Affiliate Image Link' ),
		__( 'amazon' ),
		__( 'Amazon' ),
	],

	attributes: { // Necessary for saving block content.

        alt: {
            type: 'string',
        },
        url: {
            type: 'string',
            default: 'https://placehold.it/200x200',
        },
        href: {
            type: 'string',
            default: '#',
        },
        linkTarget: {
            type: 'boolean',
            default: true,
        },
        noFollow: {
            type: 'boolean',
            default: true,
        },
    },

	supports: {
	    align: true,
	    // inserter: alp_noapi,
	},

	edit: ( props ) => {

		wp.alp_gut_active = true;
		wp.alp_gut_legacy_img_link = true;

		const {
			className,
			attributes: {
				alt,
				url,
				href,
				linkTarget,
				noFollow,
				// alignment,
			},
			setAttributes,
		} = props;

		if ( url !== 'https://placehold.it/200x200' ) {
			var link_class = "alp-img-link-dummy";
		}
		else {
			var link_class = "alp-img-link-dummy link-disabled";
		}

		function onChangeImageAlt (newImageAlt) {
			props.setAttributes({ alt: newImageAlt })
		}

		function onChangeImageUrl (newImageUrl) {
			props.setAttributes({ url: newImageUrl })
		}

		function onChangeHref (newHref) {
			props.setAttributes({ href: newHref })
		}

		function onChangelinkTarget (newLinkTarget) {
			props.setAttributes({ linkTarget: newLinkTarget })
		}

		function onChangeNoFollow (newNoFollow) {
			props.setAttributes({ noFollow: newNoFollow })
		}

		function onChangeButton (newLinkText) {
			window.alp_block_props = props
		}

		const PluginsUrl = ALPglobal.PluginsUrl + "/amalinkspro/includes/images/AmaLinks-Pro-Logo.png";


		if ( linkTarget === true ) {
			var linkTargetString = '_blank';

			if ( noFollow === true ) {
				var noFollowString = 'nofollow noopener noreferrer';
			}
			else {
				var noFollowString = 'noopener noreferrer';
			}

		}
		else {
			var linkTargetString = "";

			if ( noFollow === true ) {
				var noFollowString = 'nofollow noopener noreferrer';
			}
			else {
				var noFollowString = 'noopener noreferrer';
			}
		}

			


	    return (

            <div>

                <InspectorControls>

                	<div className="alp-gut-btn-wrap">

                		<img src={ PluginsUrl } alt="AmaLinks Pro - The Best Amazon Associate WordPress Plugin" />

						<Button id={'insert-amalinkspro-media' } className={ "alp-gutenberg-modal-btn amalinkspro-insert-media-btn" } data-alp-block={'ama-legacy-image-link'} onClick={ onChangeButton }>Search Amazon</Button>

					</div>
	                	
                	<PanelBody title={'Amazon Link Settings'} initialOpen={true}>
		                <PanelRow>
		                    <TextControl
		                        label='Amazon Affiliate Link'
		                        value={ href }
		                        onChange={ onChangeHref }
		                    />
		                </PanelRow>
		              	<PanelRow>
		              		<ToggleControl
					          label={ __('Open link in new tab') }
					          checked={ linkTarget }
					          onChange={ onChangelinkTarget }
					        />
		                </PanelRow>
		              	<PanelRow>
		                    <ToggleControl
					          label={ __('noFollow this link') }
					          checked={ noFollow }
					          onChange={ onChangeNoFollow }
					        />
		                </PanelRow>
        				
		                
		            </PanelBody>

		            <PanelBody title={'Product Image Settings'} initialOpen={false}>

		            	<PanelRow>
		                    <TextControl
		                        label='Image URL'
		                        value={ url }
		                        onChange={ onChangeImageUrl }
		                    />
		                </PanelRow>
		                <PanelRow>
		                    <TextControl
		                        label='Image Alt Tag'
		                        value={ alt }
		                        onChange={ onChangeImageAlt }
		                    />
		                </PanelRow>
		              	
		            </PanelBody>

		        
                </InspectorControls>

				<div className="alp-img-wrap">
	                <a class={ link_class } href={ href } target={ linkTargetString } rel={ noFollowString }>
	                	<img className={ 'alp-img-link' } src={ url } alt={ alt } />
	                </a>
                </div>

            </div>
        );

	},



	save: ( props ) => {

		wp.alp_gut_active = true;
		wp.alp_gut_legacy_img_link = true;

		const {
			className,
			attributes: {
				asin,
				alt,
				url,
				href,
				linkTarget,
				noFollow,
			},
			setAttributes,
		} = props;

		if ( linkTarget === true ) {
			var linkTargetString = '_blank';

			if ( noFollow === true ) {
				var noFollowString = 'nofollow noopener noreferrer';
			}
			else {
				var noFollowString = 'noopener noreferrer';
			}

		}
		else {
			var linkTargetString = "";

			if ( noFollow === true ) {
				var noFollowString = 'nofollow noopener noreferrer';
			}
			else {
				var noFollowString = 'noopener noreferrer';
			}
		}

		return (
			<div className={ 'amalinkspro-image-link' }>
				<a href={ href } target={ linkTargetString } rel={ noFollowString }>
	            	<img className={ 'alp-img-link' } src={ url } alt={ alt } />
	            </a>
            </div>
		);

		
	},

});