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
	Blocks\register();
	Block_Styles\register();
	Block_Variations\register();
	Patterns\register();
}
