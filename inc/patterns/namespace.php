<?php
/**
 * Figuren_Theater label_printing Patterns.
 *
 * @package figuren-theater/label-printing
 */

namespace Figuren_Theater\Label_Printing\Patterns;

const TRANSIENT_KEY = 'label_printing';
const META_KEY      = '_label_printing';

const WP_CORE_PATTERN_TAX = 'wp_pattern_category'; // Avail. from 16.7 / 6.4 !!

const PATTERN_TAX_TERM = 'Label Printing';

const STATIC_PATTERN_CATEGORY = TRANSIENT_KEY;

/**
 * Register block-patterns.
 *
 * @return void
 */
function register() :void {
	\add_action( 'init', __NAMESPACE__ . '\\bootstrap' );
}

/**
 * Bootstrap block-pattern registration.
 *
 * @return void
 */
function bootstrap() :void {

	$store = new Label_Store;

	// Flush the cache after each import.
	\add_action(
		__NAMESPACE__ . '\\save_post_wp_block_label',
		[ $store, 'delete_transient' ]
	);

	// Register a new pattern category.
	register_block_pattern_category();

	// Register one sheet-pattern per each Label.
	array_map( __NAMESPACE__ . '\\register_block_patterns', $store->get_labels() );

	// Register post_meta to make it available to the REST API.
	\register_post_meta(
		'wp_block',
		META_KEY,
		[
			// Importantly object refers to a JSON object,
			// this is equivalent to an associative array in PHP.
			'type' => 'object',
			'description' => 'Physical measurements of a printing label.',
			'single' => true,
			'show_in_rest' => [
				'schema' => [
					'type'       => 'object',
					'properties' => [
						'width' => [
							'type' => 'number',
						],
						'height'  => [
							'type' => 'number',
						],
						'a4_border_tb' => [
							'type' => 'number',
						],
						'a4_border_lr'  => [
							'type' => 'number',
						],
						'orientation'  => [
							'type' => 'string',
						],
					],
				],
			],
		]
	);
}

/**
 * Register customized label-printing sheet pattern, based on one Label.
 *
 * @param  Label $label Printing-Label object.
 *
 * @return void
 */
function register_block_patterns( Label $label ) : void {

	// Instantiate new pattern generator.
	$generator = new Generator( $label );

	// Register block-pattern to WordPress.
	\register_block_pattern(
		$generator::get_pattern_name( $label->post_ID ),
		[
			'title'         => $generator::get_pattern_title( $label->name ),
			'content'       => $generator->get_markup(),
			'description'   => _x( 'This is an overview block pattern for printing labels.', 'Block pattern description', 'label-printing' ),
			'viewportWidth' => 1500,
			'categories'    => [
				STATIC_PATTERN_CATEGORY,
			],
			'keywords'      => [
				'label',
				'sticker',
				'print',
			],
			'inserter'      => false,
		]
	);
}

/**
 * Registers a new pattern category.
 *
 * Beware, this is different to the 'wp_pattern_category' taxonomy but named the same.
 *
 * @return void
 */
function register_block_pattern_category() : void {
	\register_block_pattern_category(
		STATIC_PATTERN_CATEGORY,
		[
			'label' => PATTERN_TAX_TERM,
		]
	);
}
