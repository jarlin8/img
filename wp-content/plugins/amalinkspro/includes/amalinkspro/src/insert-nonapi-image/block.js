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



registerBlockType('amalinkspro/insert-nonapi-image', {    

	title: __( 'Amazon NO-API Image' ), // Block title.
	icon: iconEl, // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
	category: 'amalinkspro', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
	keywords: [
		__( 'Amazon Affiliate Image Link' ),
		__( 'amazon' ),
		__( 'Amazon' ),
	],

	attributes: { // Necessary for saving block content.

        href: {
            type: 'string',
        },
        img_src: {
            type: 'string',
            default: 'https://placehold.it/325x200',
        },
        pixel_src: {
            type: 'string',
        },
    },

	supports: {
	    align: true,
	    // inserter: alp_noapi,
	},

	edit: ( props ) => {

		const {
			className,
			attributes: {
				href,
				img_src,
				pixel_src,
			},
			setAttributes,
		} = props;

		// function onChangeHref (newAsin) {
		// 	props.setAttributes({ asin: newAsin })
		// }

		// function onChangeImageAlt (newImageAlt) {
		// 	props.setAttributes({ alt: newImageAlt })
		// }

		// function onChangeImageUrl (newImageUrl) {
		// 	props.setAttributes({ url: newImageUrl })
		// }

		function onChangeButton (newLinkText) {
			window.alp_block_props = props
		}


		const PluginsUrl = ALPglobal.PluginsUrl + "/amalinkspro/includes/images/AmaLinks-Pro-Logo.png";


	    return (

            <div>

                <InspectorControls>

                	<div className="alp-gut-btn-wrap">

                		<img src={ PluginsUrl } alt="AmaLinks Pro - The Best Amazon Associate WordPress Plugin" />

						<Button id={'insert-amalinkspro-media' } className={ "alp-gutenberg-modal-btn amalinkspro-insert-media-btn" } data-alp-block={'ama-image-link-noapi'} onClick={ onChangeButton }>Search Amazon</Button>

					</div>
	                	
                	

		            
		        
                </InspectorControls>

				<div className="alp-img-wrap">
					<a href={ href } target={ '_blank' } rel={ 'noopener noreferrer' }>
						<img border={ '0' } src={ img_src } />
					</a>
					<img src={ pixel_src } width={ '1' } height={ '1' } border={ '0' } alt="" style={{ border: 'none !important', margin: '0px !important' }} />
                </div>

            </div>
        );

	},



	save: ( props ) => {

		const {
			className,
			attributes: {
				href,
				img_src,
				pixel_src,
			},
			setAttributes,
		} = props;

		return (
			<div className="alp-img-wrap">
				<a href={ href } target={ '_blank' } rel={ 'noopener noreferrer' }>
					<img border={ '0' } src={ img_src } />
				</a>
				<img src={ pixel_src } width={ '1' } height={ '1' } border={ '0' } alt="" style={{ border: 'none !important', margin: '0px !important' }} />
            </div>
		);

		
	},

});