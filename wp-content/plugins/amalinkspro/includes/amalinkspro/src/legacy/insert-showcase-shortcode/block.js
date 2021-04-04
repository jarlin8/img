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
  el('path', { d: "M24 24h-24v-24h24v24zm-2-18h-20v16h20v-16zm-15 15h-4v-1h4v1zm14 0h-11v-14h11v14zm-1-13h-9v12h9v-12zm-11 11h-6v-1h6v1zm8.633-3.615c-.148.049-.308-.031-.357-.179 0 0-1.047-.352-2.291.062l.818 1.269c.085.125.025.295-.116.342l-.555.185-.117.019c-.105 0-.206-.044-.278-.125l-1.123-1.238c-.611.192-1.302-.031-1.534-.606-.053-.133-.08-.273-.08-.415 0-.41.229-.829.727-1.073 2.491-1.223 2.889-2.587 2.889-2.587-.06-.184.077-.372.269-.372.118 0 .228.075.267.193l1.66 4.167c.049.149-.031.308-.179.358zm-8.633 1.615h-6v-1h6v1zm-2-2.902h-4v-1h4v1zm11.814-.144l-.429-.183c.187-.443.205-.959.01-1.44-.196-.482-.566-.839-1.009-1.026l.181-.431c.887.375 1.433 1.24 1.433 2.164 0 .317-.064.629-.186.916zm-.744-.315l-.419-.178c.108-.256.119-.552.005-.83-.111-.277-.326-.483-.581-.59l.178-.421c.362.153.666.445.825.84.16.394.146.815-.008 1.179zm-9.07-1.639h-6v-1h6v1zm0-1.903h-6v-1h6v1zm0-2.097h-6v-1h6v1zm13-6h-20v2h20v-2z" } )
);



registerBlockType('amalinkspro-legacy/insert-showcase-shortcode', {   

	title: __( 'Legacy Product Showcase' ), // Block title.
    icon: iconEl, // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
    category: 'amalinkspro-legacy', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
    keywords: [
        __( 'Legacy Product Showcase' ),
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
        align: false,
        // inserter: alp_noapi,
    },

	edit: ( props, instanceId ) => {

		wp.alp_gut_active = true;
		wp.alp_gut_legacy_showcase = true;

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

						<Button id={'insert-amalinkspro-media' } className={ "alp-gutenberg-modal-btn amalinkspro-insert-media-btn" } data-alp-block={'ama-legacy-showcase-shortcode'} onClick={ onChangeButton }>Search Amazon</Button>

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
		                placeholder={ __( 'Click the big red button to the right to search Amazon' ) }
		                onChange={ onChangeShortcode }
		            />
		        </div>
            

            </div>
        );

	},



	save: ( props ) => {

		wp.alp_gut_active = true;
		wp.alp_gut_legacy_showcase = true;

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