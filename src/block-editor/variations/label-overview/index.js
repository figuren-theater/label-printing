/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/#registering-a-block
 */
import { registerBlockVariation } from '@wordpress/blocks';

/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * 4. Add Custom Taxonomy Terms as Blocks
 */
registerBlockVariation('core/group', {
	// category:		'theatrebase', // blockvariations can't be added to blockCollections, yet
	name: 'figuren-theater/label-overview',
	title: __('Label Overview', 'label-printing'),
	description: __(
		'Shows multiple labels as printable document.',
		'label-printing'
	),
	keywords: [__('label', 'label-printing'), __('print', 'label-printing')],
	icon: 'art',
	isDefault: false,
	attributes: {
		className: 'is-style-label-overview-portrait',
		align: 'wide',
		style: {
			dimensions: {
				minHeight: 'var(--label-printing-doc-height)',
			},
			spacing: {
				padding: {
					top: '0',
					bottom: '0',
					left: '0',
					right: '0',
				},
				margin: {
					top: '0',
					bottom: '0',
				},
				blockGap: '0',
			},
		},
		backgroundColor: '#ffffff',
		layout: {
			type: 'constrained',
			contentSize: 'var(--label-printing-doc-width)',
			//   contentSize: "var:label|printing|doc|width)",
			wideSize: 'var(--label-printing-doc-width)',
			justifyContent: 'left',
		},
	},
	isActive: (blockAttributes) =>
		blockAttributes.className === 'is-style-label-overview-portrait',
	scope: [],
});
