<?php
/**
 * Figuren_Theater label_printing Block_Variations.
 *
 * @package figuren-theater/label-printing
 */

namespace Figuren_Theater\Label_Printing\Block_Variations;

use Figuren_Theater\Label_Printing;

/**
 * Register module.
 *
 * @return void
 */
function register() :void {
		\add_action( 'init', __NAMESPACE__ . '\\register_assets', 5 );

		\add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\\enqueue_assets' );

}

/**
 * Run the asset-registration for all assets.
 *
 * @return void
 */
function register_assets() :void {

	\array_map(
		__NAMESPACE__ . '\\register_asset',
		get_assets()
	);
}

/**
 * Get backend-only editor assets.
 *
 * @return string[]
 */
function get_assets() : array {
	return [
		'label-overview',
	];
}

/**
 * Register a new script and sets translated strings for the script.
 *
 * @throws \Error If build-files doesn't exist errors out in local environments and writes to error_log otherwise.
 *
 * @param  string $asset Slug of the block to register scripts and translations for.
 *
 * @return void
 */
function register_asset( string $asset ) : void {

	$dir = Label_Printing\DIRECTORY;

	$script_asset_path = "$dir/build/$asset.asset.php";

	$error_message = "You need to run `npm start` or `npm run build` for the '$asset' block-asset first.";

	if ( ! file_exists( $script_asset_path ) ) {
		if ( \in_array( wp_get_environment_type(), [ 'local', 'development' ], true ) ) {
			throw new \Error(
				$error_message
			);
		} else {
			\error_log( $error_message );
			return;
		}
	}

	$index_js     = "build/$asset.js";
	$script_asset = require $script_asset_path; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable

	wp_register_script(
		"label-printing--$asset",
		plugins_url( $index_js, "$dir/plugin.php" ),
		$script_asset['dependencies'],
		$script_asset['version']
	);

	wp_set_script_translations(
		"label-printing--$asset",
		'label-printing',
		Label_Printing\DIRECTORY . '/languages'
	);
}

/**
 * Enqueue all scripts.
 *
 * @return void
 */
function enqueue_assets() : void {
	\array_map(
		__NAMESPACE__ . '\\enqueue_asset',
		get_assets()
	);
}

/**
 * Enqueue a script.
 *
 * @param  string $asset Slug of the block to load the frontend scripts for.
 *
 * @return void
 */
function enqueue_asset( string $asset ) : void {
	wp_enqueue_script( "label-printing--$asset" );
}
