import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';
import { useInnerBlocksProps, useBlockProps } from '@wordpress/block-editor';
import { useEntityRecords } from '@wordpress/core-data';
import { useSelect } from '@wordpress/data';
import {
	Flex,
	FlexBlock,
	FlexItem,
	SelectControl,
} from '@wordpress/components';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @param {Object}   root0               WordPress Block
 * @param {Array}    root0.attributes
 * @param {Function} root0.setAttributes
 * @param {boolean}  root0.isSelected
 * @param {string}   root0.clientId
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit({
	attributes,
	setAttributes,
	isSelected,
	clientId,
}) {
	/**
	 * Define the default block structure for a new label.
	 */
	const LABEL_TEMPLATE = [
		[
			'core/heading',
			{
				placeholder: __('Label Title', 'label-printing'),
				style: {
					spacing: {
						margin: {
							top: '0',
							bottom: '0',
						},
					},
				},
			},
		],
		[
			'core/columns',
			{},
			[
				[
					'core/column',
					{},
					[
						[
							'core/image',
							{
								aspectRatio: 'square',
								style: {
									color: {
										duotone:
											'var:preset|duotone|foreground-and-background',
									},
								},
							},
						],
					],
				],
				[
					'core/column',
					{},
					[
						[
							'core/paragraph',
							{
								placeholder: __(
									'Enter label content…',
									'label-printing'
								),
								style: {
									spacing: {
										margin: {
											top: '0',
											bottom: '0',
										},
									},
								},
							},
						],
					],
				],
			],
		],
	];

	/**
	 * Self or Child Selected
	 *
	 * This is essentially isSelected, extended to include if a child
	 * block is selected.
	 */
	const useIsSelectedOrChild = () => {
		let isSelectedOrChild = useSelect((select) =>
			select('core/block-editor').hasSelectedInnerBlock(clientId)
		);
		if (!isSelectedOrChild && isSelected !== null) {
			isSelectedOrChild = isSelected;
		}
		return isSelectedOrChild;
	};
	const isSelectedOrChild = useIsSelectedOrChild();

	const blockProps = useBlockProps();
	const innerBlocksProps = useInnerBlocksProps(
		{
			style: {
				width: attributes.labelWidth + 'mm',
				height: attributes.labelHeight + 'mm',
			},
		},
		{
			template: LABEL_TEMPLATE,
		}
	);

	/**
	 * Retrieves label data using the useEntityRecords hook,
	 * which fetches records of wp_block posts with a specific pattern category.
	 *
	 * @todo #14 useEntityRecords by TAXONOMY does nothing
	 */
	const labelsRequest = useEntityRecords('postType', 'wp_block', {
		status: 'publish',
		// wp_pattern_category: [227], // does nothing ????
	});

	// Renders a loading message if the label data is still resolving.
	if (labelsRequest.isResolving) {
		return <div {...blockProps}>{__('Loading…', 'label-printing')}</div>;
	}

	/**
	 * Reduce queried lists of 'wp_block' posts to only labels, based on the existence of the '_label_printing' post_meta.
	 *
	 * This is needed because the query doesn't respect the given taxonomy.
	 *
	 * So we do this manually.
	 *
	 * CAN BE REMOVED WHEN #14 IS CLOSED.
	 *
	 * @todo #14 useEntityRecords by TAXONOMY does nothing
	 *
	 * @return {Array} List of Labels, a subset of 'wp_block' posts.
	 */
	const getOnlyLabels = () => {
		return labelsRequest?.records?.filter(
			(page) =>
				Object.prototype.toString.call(page.meta._label_printing) !==
				'[object Array]'
		);
	};

	// Populates options for a SelectControl component,
	// which allows users to select a label template.
	// It retrieves label titles from the fetched data.
	const options = [];
	if (labelsRequest.records) {
		options.push({
			value: 0,
			label: __('Select your Printing Label template', 'label-printing'),
		});
		getOnlyLabels().forEach((page) => {
			options.push({ value: page.id, label: page.title.raw });
		});
	} else {
		options.push({ value: 0, label: __('Loading…', 'label-printing') });
	}

	// Get WP_Post object by post_id from already queried data.
	const getPostObject = (id) => {
		// Note: the second parameter of parseInt() signifies the radix for parsing;
		// in this case, the  number will be parsed as a decimal.
		id = parseInt(id, 10);

		return labelsRequest?.records?.filter((post) => post.id === id);
	};

	/**
	 * Renders the block UI,
	 * which includes
	 * - the customizable label template,
	 * - label selection dropdown
	 * - and the dimensions of selected label.
	 *
	 * Uses conditional rendering based on whether the block or its inner blocks are selected.
	 * This is a hack which updates the rendering of the SSR preview
	 * and should be removed when a real 'local reusable block' exists.
	 *
	 * @todo #12 Create a local-only reusable-block or a ‚reusable-block light‘
	 *
	 * @param {string} newId New Post ID that was selected.
	 */
	return (
		<div {...blockProps}>
			<Flex align="start" justify="initial">
				<FlexItem>
					<Flex align="start" direction="column">
						<FlexBlock>
							<SelectControl
								value={attributes.wpLabelPost ?? 0}
								options={options}
								onChange={(newId) => {
									const post = getPostObject(newId)[0];
									setAttributes({
										wpLabelPost: post.id,
										labelWidth:
											post.meta._label_printing.width,
										labelHeight:
											post.meta._label_printing.height,
									});
								}}
							/>
						</FlexBlock>
						<FlexBlock>
							<p>
								{__('Width', 'label-printing')}:{' '}
								{attributes.labelWidth}mm
							</p>
							<p>
								{__('Height', 'label-printing')}:{' '}
								{attributes.labelHeight}mm
							</p>
						</FlexBlock>
					</Flex>
				</FlexItem>
				<FlexItem {...innerBlocksProps}></FlexItem>
			</Flex>
			{isSelectedOrChild ? (
				<ServerSideRender
					block="figuren-theater/label-printing"
					attributes={attributes}
					style={{ marginTop: '0' }}
				/>
			) : (
				<ServerSideRender
					block="figuren-theater/label-printing"
					attributes={attributes}
					style={{ marginTop: '0.0001px' }}
				/>
			)}
		</div>
	);
}
