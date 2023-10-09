<?php
/**
 * Figuren_Theater label_printing Blocks.
 *
 * @package figuren-theater/label-printing
 */

namespace Figuren_Theater\Label_Printing\Blocks\Printing;

use Figuren_Theater\Label_Printing\Patterns;

/**
 * Render callback of the 'figuren-theater/label-printing' block.
 *
 * Shows a printable sheet with multiple same instances of the coosen label.
 *
 * @param  array<string, int> $attributes The blocks attributes.
 *
 * @return string
 */
function render( array $attributes ) : string {

	// Get the label post object based on the provided attribute.
	$label = \get_post( $attributes['wpLabelPost'] );

	if ( ! $label instanceof \WP_Post ) {
		return '';
	}

	// Check if metadata is available via attributes.
	if ( ! isset( $attributes['labelHeight'] ) || ! isset( $attributes['labelWidth'] ) ) {

		// Get the labels measurements.
		$meta = \get_post_meta( $label->ID, Patterns\META_KEY, true );

		// Check if the metadata is valid.
		if ( ! \is_array( $meta ) || ! isset( $meta['height'] ) || ! isset( $meta['width'] ) ) {
			return '';
		}
		$attributes['labelHeight'] = $meta['height'];
		$attributes['labelWidth']  = $meta['width'];
	}

	// Generate Inline-CSS using a custom-property to set the labels printing dimensions.
	$css = \sprintf(
		'<style>:root { --label-printing-height:%smm;--label-printing-width:%smm; }</style>',
		$attributes['labelHeight'],
		$attributes['labelWidth'],
	);

	// Generate the pattern slug from label.
	$pattern_slug = 'figuren-theater/label-view-a4-' . $label->ID;

	// Prepare block-pattern with the specified pattern slug for rendering.
	$pattern = \do_blocks( '<!-- wp:pattern {"slug":"' . $pattern_slug . '"} -->' );

	// Render the Inline-CSS and the block pattern.
	return $css . $pattern;
}
