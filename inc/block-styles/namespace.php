<?php
/**
 * Figuren_Theater label_printing Block_Styles.
 *
 * @package figuren-theater/label-printing
 */

namespace Figuren_Theater\Label_Printing\Block_Styles;

/**
 * Register module.
 *
 * @return void
 */
function register() :void {
	add_action( 'init', __NAMESPACE__ . '\\bootstrap', 1 );
}

/**
 * Bootstrap module, when enabled.
 *
 * @todo #9 Use JS to register block-styles
 * @todo #10 Use wp-scripts to build CSS from SCSS
 * @todo #11 Include '@page{size: A4 landscape|portrait;}' into CSS
 *
 * @return void
 */
function bootstrap() :void {

	\register_block_style(
		'core/group',
		[
			'name'         => 'label-overview-portrait',
			'label'        => __( 'A4 portrait', 'label-printing' ),
			'is_default'   => false,
			'inline_style' => '.wp-block-group.is-style-label-overview-portrait { --label-printing-doc-width: 21cm; --label-printing-doc-height: 29.7cm }' . get_print_css_basics(),
		]
	);

	\register_block_style(
		'core/group',
		[
			'name'         => 'label-overview-landscape',
			'label'        => __( 'A4 landscape', 'label-printing' ),
			'is_default'   => false,
			'inline_style' => '.wp-block-group.is-style-label-overview-landscape { --label-printing-doc-width: 29.7cm; --label-printing-doc-height: 21cm }' . get_print_css_basics(),
		]
	);
}

/**
 * Get common used CSS for print & screen.
 *
 * @return string
 */
function get_print_css_basics() : string {
	return '
		.wp-block-group.alignwide[class*="is-style-label-overview-"] {
			max-width: var(--label-printing-doc-width) !important;
		}
		@media print {
			html, body, .wp-site-blocks {
				margin: 0 !important;
				padding: 0 !important;
			}
		}
		@media screen {
			.wp-block-group.alignwide[class*="is-style-label-overview-"] {
				box-shadow: 0.3em 0.3em 1em grey;
			}
				.wp-block-group.alignwide[class*="is-style-label-overview-"] > .wp-block-group > * {
					outline: 1px dashed lightgrey;
				}

			body:has([class*="is-style-label-overview-"]) {
				background-color: Gainsboro !important;
				padding: 2cm 0 5cm;
			}
		}
	';
}
