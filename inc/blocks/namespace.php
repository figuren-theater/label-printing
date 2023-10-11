<?php
/**
 * Figuren_Theater label_printing Blocks.
 *
 * @package figuren-theater/label-printing
 */

namespace Figuren_Theater\Label_Printing\Blocks;

use Figuren_Theater\Label_Printing;

/**
 * Register block(s).
 *
 * @return void
 */
function register() :void {
	\add_action( 'init', __NAMESPACE__ . '\\bootstrap' );
}

/**
 * Bootstrap block registration.
 *
 * @return void
 */
function bootstrap() :void {
	register_blocks();

}

/**
 * Get list of blocks.
 *
 * @return string[] Array of block slugs.
 */
function get_blocks() : array {
	return [
		'label-printing',
		'label-proxy',
	];
}

/**
 * Register all blocks.
 *
 * @return void
 */
function register_blocks() : void {
	\array_map( __NAMESPACE__ . '\\register_block', get_blocks() );
}

/**
 * Register a block.
 *
 * @param  string $block Slug of the block to register.
 *
 * @return void
 */
function register_block( string $block ) : void {

	require_once Label_Printing\DIRECTORY . '/build/' . $block . '/index.php'; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant

	$namespaced_name = get_ns_name( $block );

	\register_block_type(
		Label_Printing\DIRECTORY . '/build/' . $block,
		[ // @phpstan-ignore-line argument.type (Seems to be missing from the stubs.)
			'render_callback' => __NAMESPACE__ . "\\$namespaced_name\\render",
		]
	);

	\wp_set_script_translations(
		"figuren-theater-$block-editor-script",
		'label-printing',
		Label_Printing\DIRECTORY . '/languages'
	);
}

/**
 * Generate namespaced name from block folder name.
 *
 * @param  string $block Block folder name.
 *
 * @return string Namespaced block name.
 */
function get_ns_name( string $block ) : string {
	return \ucfirst( \str_replace( 'label-', '', $block ) );
}
