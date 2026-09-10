import HeadingToolbar from "./heading-toolbar";

/**
 * Internal block libraries
 */
const { __ } = wp.i18n;
const { Component } = wp.element;
const {
    AlignmentToolbar,
    BlockControls,
    BlockAlignmentToolbar,
} = wp.blockEditor || wp.editor;

const ALIGNMENT_CONTROLS = [
    {
        icon: 'editor-alignleft',
        title: __( 'Align Text Left','rehub-framework' ),
        align: 'left',
    },
    {
        icon: 'editor-aligncenter',
        title: __( 'Align Text Center','rehub-framework' ),
        align: 'center',
    },
    {
        icon: 'editor-alignright',
        title: __( 'Align Text Right','rehub-framework' ),
        align: 'right',
    },
   /* {
        icon: 'editor-justify',
        title: __( 'Align Text Justify','rehub-framework' ),
        align: 'justify',
    },*/
];

export default class Controls extends Component {

    render() {
        const {
            attributes: {
                blockAlignment,
                textAlign,
                level
            },
            setAttributes
        } = this.props;

        return (
            <BlockControls>
	            <BlockAlignmentToolbar
		            value={ blockAlignment }
		            onChange={ blockAlignment => setAttributes( { blockAlignment} ) }
		            controls={ [ 'wide', 'full' ] }
	            />
	            <AlignmentToolbar
		            value={ textAlign }
		            onChange={ textAlign => setAttributes( { textAlign} ) }
                    alignmentControls={ALIGNMENT_CONTROLS}
	            />
                <HeadingToolbar
                    isCollapsed={ false }
                    minLevel={ 2 }
                    maxLevel={ 6 }
                    selectedLevel={ level }
                    onChange={ ( level ) => setAttributes( { level } ) }
                />
            </BlockControls>
        );
    }
}
