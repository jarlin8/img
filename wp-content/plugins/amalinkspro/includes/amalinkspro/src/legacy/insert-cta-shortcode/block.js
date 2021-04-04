// PART 1: Import dependencies
import './style.scss';
import './editor.scss';

const { __ } = wp.i18n;
const el = wp.element.createElement
const RawHTML = wp.element.RawHTML;
const registerBlockType = wp.blocks.registerBlockType
const BlockControls = wp.editor.BlockControls
const PlainText = wp.editor.PlainText
const AlignmentToolbar = wp.editor.AlignmentToolbar
const BlockAlignmentToolbar = wp.editor.BlockAlignmentToolbar
const InspectorControls = wp.editor.InspectorControls
const Button = wp.components.Button
const TextControl = wp.components.TextControl
const ToggleControl = wp.components.ToggleControl
const { Dashicon } = wp.components;
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
  el('path', { d: "M19 6H5L3 8v8l2 2h14l2-2V8l-2-2zm0 10H5V8h14v8z" } )
);



registerBlockType('amalinkspro-legacy/insert-cta-shortcode', {   

	title: __( 'Amazon Affiliate CTA Button' ), // Block title.
    icon: iconEl, // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
    category: 'amalinkspro-legacy', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
    keywords: [
        __( 'Amazon Affiliate CTA Button' ),
        __( 'amazon' ),
        __( 'Amazon' ),
    ],

	attributes: { // Necessary for saving block content.
        shortcode: {
            type: 'string',
        },
    },

	transforms: {
        from: [
            {
                type: 'shortcode',
                // Per "Shortcode names should be all lowercase and use all
                // letters, but numbers and underscores should work fine too.
                // Be wary of using hyphens (dashes), you'll be better off not
                // using them." in https://codex.wordpress.org/Shortcode_API
                // Require that the first character be a letter. This notably
                // prevents footnote markings ([1]) from being caught as
                // shortcodes.
                tag: '[a-z][a-z0-9_-]*',
                attributes: {
                    text: {
                        type: 'string',
                        shortcode: ( attrs, { content } ) => {
                            return removep( autop( content ) );
                        },
                    },
                },
                priority: 20,
            },
        ],
    },

    supports: {
        customClassName: false,
        className: false,
        html: false,
        align: true,
        // inserter: alp_noapi,
    },

	edit: ( props, instanceId ) => {

		wp.alp_gut_active = true;
		wp.alp_gut_legacy_cta = true;

		const {
			className,
			attributes: {
				shortcode,
			},
			setAttributes,
		} = props;



		function onChangeShortcode (newShortcode) {
			props.setAttributes({ shortcode: newShortcode })
		}

		function onChangeButton (newLinkText) {
			window.alp_block_props = props
		}


		const inputId = `blocks-shortcode-input-${ instanceId }`;
		const PluginsUrl = ALPglobal.PluginsUrl + "/amalinkspro/includes/images/AmaLinks-Pro-Logo.png";



	    return (

            <div>

                <InspectorControls>

                	<div className="alp-gut-btn-wrap">

                		<img src={ PluginsUrl } alt="AmaLinks Pro - The Best Amazon Associate WordPress Plugin" />

						<Button id={'insert-amalinkspro-media' } className={ "alp-gutenberg-modal-btn amalinkspro-insert-media-btn" } data-alp-block={'ama-legacy-cta-shortcode'} onClick={ onChangeButton }>Search Amazon</Button>

					</div>
	                	
		        
                </InspectorControls>

                 <div className="wp-block-shortcode">
		             <label htmlFor={ inputId }>
		                <Dashicon icon="shortcode" />
		                { __( 'Shortcode' ) }
		            </label>
		            <PlainText
		                className="input-control"
		                id={ inputId }
		                value={ shortcode }
		                placeholder={ __( '' ) }
		                onChange={ onChangeShortcode }
		            />
		        </div>
            

            </div>
        );

	},



	save: ( props ) => {

		wp.alp_gut_active = true;
		wp.alp_gut_legacy_cta = true;

		const {
			className,
			attributes: {
				shortcode,
			},
			setAttributes,
		} = props;

		return (
			<div>{ shortcode }</div>
		)


		
	},

});