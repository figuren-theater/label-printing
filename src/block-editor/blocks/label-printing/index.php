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

	// Define the pattern slug for the label.
	$pattern_slug = 'figuren-theater/label-view-a4-' . $label->ID;

	// Get the labels measurements.
	$meta = \get_post_meta( $label->ID, Patterns\META_KEY, true );

	// Check if the metadata is valid.
	if ( ! \is_array( $meta ) || ! isset( $meta['height'] ) || ! isset( $meta['width'] ) ) {
		return '';
	}

	// Generate Inline-CSS using a custom-property to set the labels printing dimensions.
	$css = \sprintf(
		'<style>:root { --label-printing-height:%smm;--label-printing-width:%smm; }</style>',
		$meta['height'],
		$meta['width'],
	);

	// Render the Inline-CSS and the block pattern with the specified pattern slug.
	return $css . \do_blocks( '<!-- wp:pattern {"slug":"' . $pattern_slug . '"} -->' );
}
