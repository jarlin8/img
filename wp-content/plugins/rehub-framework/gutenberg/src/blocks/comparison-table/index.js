import { __ } from '@wordpress/i18n';
import { InnerBlocks } from '@wordpress/block-editor';
import { iconTable } from "../../icons";

import EditClass from './edit';
import save from './save';

const blockProperty = {
	title: __( 'Comparison table', 'rehub-framework' ),
	description: __('Comparison table with products', 'rehub-framework'),
	category: 'helpler-modules',
	icon: iconTable,
	supports: {
		html: false,
	},
	attributes: {
        enableBadges: {
            type: 'boolean',
            default: false
        },
        enableImage: {
            type: 'boolean',
            default: true
        },
        enableTitle: {
            type: 'boolean',
            default: true
        },
        enableSubtitle: {
            type: 'boolean',
            default: true
        },
        enableStars: {
            type: 'boolean',
            default: true
        },
        enableNumbers: {
            type: 'boolean',
            default: false
        },
        enableButton: {
            type: 'boolean',
            default: true
        },
        enableBottom: {
            type: 'boolean',
            default: true
        },
        enablePros: {
            type: 'boolean',
            default: true
        },
        enableCons: {
            type: 'boolean',
            default: true
        },
        enableSpec: {
            type: 'boolean',
            default: false
        },
        enableCallout: {
            type: 'boolean',
            default: false
        },
        enableList: {
            type: 'boolean',
            default: false
        },
        enableListTitle: {
            type: 'boolean',
            default: true
        },
        responsiveView: {
            type: 'string',
            default: 'sracked'
        },
        bottomTitle: {
            type: 'string',
            default: 'Bottom Line'
        },
        prosTitle: {
            type: 'string',
            default: 'Pros'
        },
        consTitle: {
            type: 'string',
            default: 'Cons'
        },
        specTitle: {
            type: 'string',
            default: 'Spec'
        },
        titleTag: {
            type: 'string',
            default: 'div'
        },
        titleFont: {
            type: 'number',
            default: 18
        },
        contentFont: {
            type: 'number',
            default: 14
        }
    },
    providesContext: {
        'enableBadges': 'enableBadges',
        'enableImage': 'enableImage',
        'enableTitle': 'enableTitle',
        'enableSubtitle': 'enableSubtitle',
        'enableStars' : 'enableStars',
        'enableNumbers': 'enableNumbers',
        'enableButton': 'enableButton',
        'enableBottom': 'enableBottom',
        'enablePros' : 'enablePros',
        'enableCons' : 'enableCons',
        'enableSpec' : 'enableSpec',
        'enableCallout' : 'enableCallout',
        'enableList' : 'enableList',
        'enableListTitle' : 'enableListTitle',
        'titleFont' : 'titleFont',
        'contentFont' : 'contentFont',
        'titleTag' : 'titleTag',
        'bottomTitle': 'bottomTitle', 
        'prosTitle': 'prosTitle', 
        'consTitle': 'consTitle', 
        'specTitle': 'specTitle',
        'responsiveView': 'responsiveView'
	},
	edit: EditClass,
	save,
};

export default {
	slug: `rehub/comparison-table`,
	blockProperty,
};