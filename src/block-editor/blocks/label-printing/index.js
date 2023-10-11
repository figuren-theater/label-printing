/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { _x } from '@wordpress/i18n';

/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
import { registerBlockType } from '@wordpress/blocks';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * All files containing `style` keyword are bundled together. The code used
 * gets applied both to the front of your site and to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './style.scss';

/**
 * Internal dependencies
 */
import json from './block.json';
import Edit from './edit';
import save from './save';

const { name, ...settings } = json;

/**
 * Every block starts by registering a new block type definition.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
registerBlockType(name, {
	...settings,
	title: _x('Label Printing', 'block title', 'label-printing'),
	description: _x('Make labels now.', 'block description', 'label-printing'),
	edit: Edit,
	save,
});


/**
 *      Is it possible to nest block styles in the theme.json
 * @see https://wordpress.stackexchange.com/questions/408194/is-it-possible-to-nest-block-styles-in-the-theme-json/419224#419224
 *
 *      Filtering theme.json client side
 * @see https://gutenberg.10up.com/reference/Themes/theme-json/#filtering-themejson-client-side
 */

import { select } from  '@wordpress/data';
import { addFilter } from '@wordpress/hooks';
import { store as blockEditorStore } from '@wordpress/block-editor';
/**
 * Enable custom FontSize controls on Group, Heading and Paragraph blocks
 * when placed inside of Label-Printing blocks.
 */
addFilter(
    'blockEditor.useSetting.before',
    'figuren-theater/useSetting.before',
    ( settingValue, settingName, clientId, blockName ) => {
        if ( blockName === 'core/group' || blockName === 'core/heading' || blockName === 'core/paragraph' ) {
            const { getBlockParents, getBlockName } = select( blockEditorStore );
            const blockParents = getBlockParents( clientId, true );
            const isNestedInLabelBlock =
				blockParents.some( ( ancestorId ) => getBlockName( ancestorId ) === name );

            if ( isNestedInLabelBlock && settingName === 'typography.customFontSize' ) {
                return true;
            }
        }

        return settingValue;
    }
);
