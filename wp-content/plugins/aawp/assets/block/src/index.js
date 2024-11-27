/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
import { registerBlockType } from '@wordpress/blocks';

import aawpIcon from '../../img/block-icon.svg';

/**
 * Internal dependencies
 */
import Edit from './edit';
import save from './save';

registerBlockType( 'aawp/aawp-block', {

    icon: <img src={aawpIcon} alt="AAWP Logo" height="24" width="24"/>,
    name: "aawp/aawp-block",
    title: "AAWP",
    category: "widgets",
    description: "The best WordPress plugin for Amazon Affiliates.",
    textdomain: "aawp",

	/**
     * @see ./edit.js
     */
	edit: Edit,

	/**
     * @see ./save.js
     */
	save,
} );
