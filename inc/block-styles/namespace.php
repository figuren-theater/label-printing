<?php
/**
 * Figuren_Theater label_printing Block_Styles.
 *
 * @package figuren-theater/label-printing
 */

namespace Figuren_Theater\Label_Printing\Block_Styles;

/**
 * Register the Label Printing block-styles.
 *
 * This function registers block styles used by the (inner workings of the) Label Printing block.
 *
 * @return void
 */
function register() :void {
	\add_action( 'init', __NAMESPACE__ . '\\bootstrap', 1 );
}

/**
 * Bootstrap block-style registration.
 * 
 * This function registers two block styles using the WordPress core API.
 *
 * @todo #9 Use JS to register block-styles
 * @todo #10 Use wp-scripts to build CSS from SCSS
 * @todo #11 Include '@page{size: A4 landscape|portrait;}' into CSS
 *
 * @return void
 */
function bootstrap() :void {

	// Register A4 portrait block style.
	\register_block_style(
		'core/group',
		[
			'name'         => 'label-overview-portrait',
			'label'        => __( 'A4 portrait', 'label-printing' ),
			'is_default'   => false,
			'inline_style' => '.wp-block-group.is-style-label-overview-portrait { --label-printing-doc-width: 21cm; --label-printing-doc-height: 29.7cm }',
		]
	);

	// Register A4 landscape block style.
	\register_block_style(
		'core/group',
		[
			'name'         => 'label-overview-landscape',
			'label'        => __( 'A4 landscape', 'label-printing' ),
			'is_default'   => false,
			'inline_style' => '.wp-block-group.is-style-label-overview-landscape { --label-printing-doc-width: 29.7cm; --label-printing-doc-height: 21cm }',
		]
	);
}

