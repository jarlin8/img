// PART 1: Import dependencies
import './style.scss';
import './editor.scss';

const { __ } = wp.i18n;
const el = wp.element.createElement
const registerBlockType = wp.blocks.registerBlockType
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
  el('path', { d: "M22 0h-20v6h1.999c0-1.174.397-3 2.001-3h4v16.874c0 1.174-.825 2.126-2 2.126h-1v2h9.999v-2h-.999c-1.174 0-2-.952-2-2.126v-16.874h4c1.649 0 2.02 1.826 2.02 3h1.98v-6z" } )
);



registerBlockType('amalinkspro-legacy/insert-textlink-html', {   

	title: __( 'Amazon Affiliate Text Link' ), // Block title.
    icon: iconEl, // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
    category: 'amalinkspro-legacy', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
    keywords: [
        __( 'Amazon Affiliate Text Link' ),
        __( 'amazon' ),
        __( 'Amazon' ),
    ],

	attributes: { // Necessary for saving block content.

        asin: {
            type: 'string',
        },
        linkText: {
            type: 'string',
            default: 'Your Amazon link text will go here',
        },
        href: {
            type: 'string',
            default: '',
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
		wp.alp_gut_legacy_text_link = true;

		const {
			className,
			attributes: {
				href,
				linkText,
				linkTarget,
				noFollow,
			},
			setAttributes,
		} = props;


		if ( linkText !== 'Your Amazon link text will go here' ) {
			var link_class = 'alp-text-link-dummy';
		}
		else {
			var link_class = 'alp-text-link-dummy link-disabled';
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

		function onChangeLinkText (newLinkText) {
			props.setAttributes({ linkText: newLinkText })
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

						<Button id={'insert-amalinkspro-media' } className={ "alp-gutenberg-modal-btn amalinkspro-insert-media-btn" } data-alp-block={'ama-legacy-text-link'} onClick={ onChangeButton }>Search Amazon</Button>

					</div>

					
	                	
                	<PanelBody title={'Amazon Affiliate Link Settings'}>


	                

	                	<PanelRow>
	                		<TextControl
			                        label='Link Text'
			                        value={ linkText }
			                        onChange={ onChangeLinkText }
			                    />
		                    </PanelRow>
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
		        
                </InspectorControls>

                <div className={ 'text-link-wrap' }>

	                <a className={ link_class } href={ href } target={ linkTargetString } rel={ noFollowString }>{ linkText }</a>

	                <div class="components-placeholder__instructions-small"><button class="alp-copy-to-clipboard" data-clipboard-target={ '.alp-text-link-dummy' }>Copy Link</button> and paste it into any paragraph or leave it here as it's own block. linkText: { linkText }</div>

                </div>
            

            </div>
        );

	},



	save: ( props ) => {

		wp.alp_gut_active = true;
		wp.alp_gut_legacy_text_link = true;

		const {
			className,
			attributes: {
				href,
				linkText,
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

			<a href={ href } target={ linkTargetString } rel={ noFollowString }>{ linkText }</a>

		);

		
	},

});

jQuery( document ).ready(function() {
	new ClipboardJS('.alp-copy-to-clipboard');
});
