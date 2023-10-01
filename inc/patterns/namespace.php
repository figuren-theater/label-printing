<?php
/**
 * Figuren_Theater label_printing Patterns.
 *
 * @package figuren-theater/label-printing
 */

namespace Figuren_Theater\Label_Printing\Patterns;

use stdClass;

/**
 * Register module.
 *
 * @return void
 */
function register() :void {

}

/**
 * Bootstrap module, when enabled.
 *
 * @return void
 */
function bootstrap() :void {

	$label_sizes = [
		[
			'name'         => 'A6',
			'width'        => 148,
			'height'       => 105,
			'a4_border_tb' => 0,
			'a4_border_lr' => 0,
		],
	]

	array_map(
		function( array $label_size ) : void {
			\register_block_pattern(
				'A4 view of ' . $label_size['name'],
				[

				]
			)
		},
		$label_sizes
	)

}
