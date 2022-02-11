/**
 * Internal block libraries
 */
const { Component } = wp.element;
const {BlockControls} = wp.blockEditor || wp.editor;

export default class Controls extends Component {

	render() {
		return (
			<BlockControls>
				{/*  <BlockAlignmentToolbar
		            value={ blockAlignment }
		            onChange={ blockAlignment => setAttributes( { blockAlignment} ) }
		            controls={ [ 'wide', 'full' ] }
	            />
	            <AlignmentToolbar
		            value={ textalign }
		            onChange={ textalign => setAttributes( { textalign} ) }
                    alignmentControls={ALIGNMENT_CONTROLS}
	            />*/}
			</BlockControls>
		);
	}
}
