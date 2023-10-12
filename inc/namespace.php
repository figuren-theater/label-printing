<?php
/**
 * Figuren_Theater label_printing.
 *
 * @package figuren-theater/label-printing
 */

namespace Figuren_Theater\Label_Printing;

/**
 * Register module.
 *
 * @return void
 */
function register() :void {

	\load_plugin_textdomain(
		'label-printing',
		false,
		dirname( DIRECTORY ) . '/languages'
	);

	Blocks\register();
	Block_Styles\register();
	Block_Variations\register();
	Patterns\register();
}
