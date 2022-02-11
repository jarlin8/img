import {
	registerBlockType,
} from '@wordpress/blocks';

import Blocks from './blocks/index.js';

Object.values(Blocks).forEach(({slug, blockProperty}) => {
	registerBlockType(slug, blockProperty);
});
