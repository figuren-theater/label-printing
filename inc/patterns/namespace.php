<?php
/**
 * Figuren_Theater label_printing Patterns.
 *
 * @package figuren-theater/label-printing
 */

namespace Figuren_Theater\Label_Printing\Patterns;

/**
 * Register module.
 *
 * @return void
 */
function register() :void {
	\add_action( 'init', __NAMESPACE__ . '\\bootstrap' );
}

/**
 * Bootstrap module, when enabled.
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

	// Register one sheet-pattern per each Label.
	array_map(
		__NAMESPACE__ . '\\register_block_patterns',
		$store->get_labels()
	);
}

/**
 * Register customized label-printing sheet pattern, based on one Label.
 *
 * @param  Label $label Printing-Label
 *
 * @return void
 */
function register_block_patterns( Label $label ) : void {

	$generator = new Generator( $label );

	\register_block_pattern(
		'figuren-theater/label-view-a4-' . $label->slug,
		[
			'title'         => 'A4 view of ' . $label->name . ' Label',
			'content'       => $generator->get_markup(),
			'description'   => _x( 'This is an overview block pattern for printing labels.', 'Block pattern description', 'label-printing' ),
			'viewportWidth' => 1500,
			'categories'    => [
				'text',
				'print',
				'label-printing',
			],
			'keywords'      => [
				'label',
				'print',
			],
		]
	);
}
